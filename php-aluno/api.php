<?php
header('Content-Type: application/json; charset=utf-8');

// ---------------------------------------------------------------------------
// Conexão com MySQL — lê .env se existir, cai nas variáveis de ambiente
// ---------------------------------------------------------------------------
$envFile = __DIR__ . '/../api-ts/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v);
    }
}

$dbHost = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost';
$dbUser = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
$dbPass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '';
$dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'portal_estagios';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Falha na conexão com o banco: ' . $e->getMessage()]);
    exit;
}

// ---------------------------------------------------------------------------
// Roteamento
// ---------------------------------------------------------------------------
$acao  = $_GET['acao'] ?? 'vagas';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

// GET ?acao=vagas — lista vagas ativas com nome da empresa
if ($acao === 'vagas' && $method === 'GET') {
    $stmt = $pdo->query(
        'SELECT v.id, v.titulo, v.area, v.bolsa, v.descricao, e.nome AS empresa_nome
         FROM vagas v
         JOIN empresas e ON e.id = v.empresa_id
         WHERE v.ativa = 1
         ORDER BY v.criado_em DESC'
    );
    echo json_encode($stmt->fetchAll());
    exit;
}

// GET ?acao=aluno&ra=XXXX — busca aluno pelo RA
if ($acao === 'aluno' && $method === 'GET') {
    $ra = $_GET['ra'] ?? '';
    $stmt = $pdo->prepare(
        'SELECT id, nome, ra, email, apto FROM alunos WHERE ra = ?'
    );
    $stmt->execute([$ra]);
    $aluno = $stmt->fetch();
    echo json_encode($aluno ?: []);
    exit;
}

// GET ?acao=candidaturas&aluno_id=N — candidaturas do aluno com título e status
if ($acao === 'candidaturas' && $method === 'GET') {
    $alunoId = (int)($_GET['aluno_id'] ?? 0);
    $stmt = $pdo->prepare(
        'SELECT c.id, c.aluno_id, c.vaga_id, c.status, c.criado_em,
                v.titulo, e.nome AS empresa_nome
         FROM candidaturas c
         JOIN vagas v    ON v.id = c.vaga_id
         JOIN empresas e ON e.id = v.empresa_id
         WHERE c.aluno_id = ?
         ORDER BY c.criado_em DESC'
    );
    $stmt->execute([$alunoId]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// GET ?acao=notificacoes&aluno_id=N — notificações do aluno
if ($acao === 'notificacoes' && $method === 'GET') {
    $alunoId = (int)($_GET['aluno_id'] ?? 0);
    $stmt = $pdo->prepare(
        'SELECT id, aluno_id, candidatura_id, mensagem, lida, criado_em
         FROM notificacoes
         WHERE aluno_id = ?
         ORDER BY criado_em DESC'
    );
    $stmt->execute([$alunoId]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// POST ?acao=candidatar — realiza candidatura
if ($acao === 'candidatar' && $method === 'POST') {
    $alunoId = (int)($input['aluno_id'] ?? 0);
    $vagaId  = (int)($input['vaga_id']  ?? 0);

    if ($alunoId <= 0 || $vagaId <= 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'aluno_id e vaga_id são obrigatórios.']);
        exit;
    }

    // Verifica se aluno existe e está apto
    $stmtA = $pdo->prepare('SELECT id, apto FROM alunos WHERE id = ?');
    $stmtA->execute([$alunoId]);
    $aluno = $stmtA->fetch();
    if (!$aluno) {
        http_response_code(404);
        echo json_encode(['erro' => 'Aluno não encontrado.']);
        exit;
    }
    if (!$aluno['apto']) {
        http_response_code(403);
        echo json_encode(['erro' => 'Aluno não está apto para candidaturas.']);
        exit;
    }

    // Verifica se a vaga existe e está ativa
    $stmtV = $pdo->prepare(
        'SELECT v.id, v.titulo, e.nome AS empresa_nome
         FROM vagas v JOIN empresas e ON e.id = v.empresa_id
         WHERE v.id = ? AND v.ativa = 1'
    );
    $stmtV->execute([$vagaId]);
    $vaga = $stmtV->fetch();
    if (!$vaga) {
        http_response_code(404);
        echo json_encode(['erro' => 'Vaga não encontrada ou inativa.']);
        exit;
    }

    // Evita candidatura duplicada (UNIQUE KEY unica já faz isso no banco, mas retornamos mensagem amigável)
    try {
        $ins = $pdo->prepare(
            'INSERT INTO candidaturas (aluno_id, vaga_id, status) VALUES (?, ?, ?)'
        );
        $ins->execute([$alunoId, $vagaId, 'enviada']);
        $candId = (int)$pdo->lastInsertId();

        // Cria notificação automática
        $msg = "Sua candidatura para \"{$vaga['titulo']}\" em {$vaga['empresa_nome']} foi enviada.";
        $notif = $pdo->prepare(
            'INSERT INTO notificacoes (aluno_id, candidatura_id, mensagem) VALUES (?, ?, ?)'
        );
        $notif->execute([$alunoId, $candId, $msg]);

        echo json_encode([
            'id'           => $candId,
            'aluno_id'     => $alunoId,
            'vaga_id'      => $vagaId,
            'titulo'       => $vaga['titulo'],
            'empresa_nome' => $vaga['empresa_nome'],
            'status'       => 'enviada',
            'criado_em'    => date('Y-m-d H:i:s'),
        ]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            http_response_code(409);
            echo json_encode(['erro' => 'Você já se candidatou a esta vaga.']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao salvar candidatura: ' . $e->getMessage()]);
        }
    }
    exit;
}

// PATCH ?acao=notificacao_lida&id=N — marca notificação como lida
if ($acao === 'notificacao_lida' && $method === 'PATCH') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare('UPDATE notificacoes SET lida = 1 WHERE id = ?');
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['erro' => 'Notificação não encontrada.']);
        exit;
    }
    $row = $pdo->prepare('SELECT * FROM notificacoes WHERE id = ?');
    $row->execute([$id]);
    echo json_encode($row->fetch());
    exit;
}

http_response_code(404);
echo json_encode(['erro' => 'Ação inválida.']);
