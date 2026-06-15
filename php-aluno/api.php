<?php
header('Content-Type: application/json; charset=utf-8');

$dados = [
    'vagas' => [
        ['id' => 1, 'titulo' => 'Desenvolvedor Junior', 'empresa_nome' => 'UniALFA Tech', 'area' => 'Desenvolvimento', 'bolsa' => 1800, 'descricao' => 'Desenvolva sistemas web e participe de projetos internos.'],
        ['id' => 2, 'titulo' => 'Analista de QA', 'empresa_nome' => 'UniALFA Labs', 'area' => 'Qualidade', 'bolsa' => 1500, 'descricao' => 'Garanta a qualidade de aplicativos por meio de testes manuais e automáticos.'],
        ['id' => 3, 'titulo' => 'Marketing Digital', 'empresa_nome' => 'UniALFA MKT', 'area' => 'Comunicação', 'bolsa' => 1300, 'descricao' => 'Apoie campanhas digitais e redes sociais.'],
    ],
    'alunos' => [
        ['id' => 1, 'nome' => 'Ana Silva', 'ra' => '20260001', 'email' => 'ana.silva@unialfa.edu.br', 'apto' => true],
        ['id' => 2, 'nome' => 'Bruno Souza', 'ra' => '20260002', 'email' => 'bruno.souza@unialfa.edu.br', 'apto' => true],
        ['id' => 3, 'nome' => 'Caio Pereira', 'ra' => '20260003', 'email' => 'caio.pereira@unialfa.edu.br', 'apto' => false],
    ],
    'candidaturas' => [],
    'notificacoes' => [
        ['id' => 1, 'aluno_id' => 1, 'texto' => 'Sua candidatura para Desenvolvedor Junior foi encaminhada.', 'lida' => false],
        ['id' => 2, 'aluno_id' => 1, 'texto' => 'Nova vaga de Estágio em Front-end disponível.', 'lida' => false],
    ],
];

$acao = $_GET['acao'] ?? 'vagas';
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? [];

if ($acao === 'vagas' && $method === 'GET') {
    echo json_encode($dados['vagas']);
    exit;
}

if ($acao === 'aluno' && $method === 'GET') {
    $ra = $_GET['ra'] ?? '';
    $matches = array_values(array_filter($dados['alunos'], fn($a) => $a['ra'] === $ra));
    echo json_encode($matches);
    exit;
}

if ($acao === 'candidaturas' && $method === 'GET') {
    $alunoId = (int) ($_GET['aluno_id'] ?? 0);
    $matches = array_values(array_filter($dados['candidaturas'], fn($c) => $c['aluno_id'] === $alunoId));
    echo json_encode($matches);
    exit;
}

if ($acao === 'notificacoes' && $method === 'GET') {
    $alunoId = (int) ($_GET['aluno_id'] ?? 0);
    $matches = array_values(array_filter($dados['notificacoes'], fn($n) => $n['aluno_id'] === $alunoId));
    echo json_encode($matches);
    exit;
}

if ($acao === 'candidatar' && $method === 'POST') {
    $alunoId = (int) ($input['aluno_id'] ?? 0);
    $vagaId = (int) ($input['vaga_id'] ?? 0);
    if ($alunoId <= 0 || $vagaId <= 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'Aluno ou vaga invalido.']);
        exit;
    }

    $vaga = current(array_filter($dados['vagas'], fn($v) => $v['id'] === $vagaId));
    if (!$vaga) {
        http_response_code(404);
        echo json_encode(['erro' => 'Vaga nao encontrada.']);
        exit;
    }

    $candidatura = [
        'id' => count($dados['candidaturas']) + 1,
        'aluno_id' => $alunoId,
        'vaga_id' => $vagaId,
        'titulo' => $vaga['titulo'],
        'empresa_nome' => $vaga['empresa_nome'],
        'status' => 'em_analise',
        'criado_em' => date('Y-m-d H:i:s'),
    ];

    $dados['candidaturas'][] = $candidatura;
    echo json_encode($candidatura);
    exit;
}

if ($acao === 'notificacao_lida' && $method === 'PATCH') {
    $id = (int) ($_GET['id'] ?? 0);
    foreach ($dados['notificacoes'] as &$notif) {
        if ($notif['id'] === $id) {
            $notif['lida'] = true;
            echo json_encode($notif);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['erro' => 'Notificacao nao encontrada.']);
    exit;
}

http_response_code(404);
echo json_encode(['erro' => 'Acao invalida.']);
