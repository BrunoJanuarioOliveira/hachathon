<?php
session_start();
if (empty($_SESSION['aluno_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../classes/ApiClient.php';
require_once __DIR__ . '/../classes/Candidatura.php';

$api = new ApiClient('http://localhost/hackatoon/api.php');
$erro = '';
$candidaturas = [];

try {
    $dados = $api->getCandidaturasAluno((int)$_SESSION['aluno_id']);
    foreach ($dados as $d) {
        $candidaturas[] = new Candidatura($d);
    }
} catch (RuntimeException $e) {
    $erro = 'Erro ao buscar candidaturas. ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Candidaturas - Portal UniALFA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div>
                <h1>Minhas Candidaturas</h1>
                <p>Historico de status</p>
            </div>
            <div>
                <a class="button-secondary" href="../index.php?logout=1">Sair</a>
            </div>
        </header>

        <nav class="menu">
            <a href="vagas.php">Vagas</a>
            <a class="active" href="minhas_candidaturas.php">Minhas Candidaturas</a>
            <a href="notificacoes.php">Notificacoes</a>
        </nav>

        <?php if ($erro): ?>
            <div class="alert"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (empty($candidaturas)): ?>
            <div class="card">
                <p>Sem candidaturas ainda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($candidaturas as $c): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($c->getTituloVaga()); ?></h3>
                    <p><strong>Empresa:</strong> <?php echo htmlspecialchars($c->getEmpresaNome()); ?></p>
                    <span class="badge <?php echo $c->getStatusBadge(); ?>"><?php echo htmlspecialchars($c->getStatusTexto()); ?></span>
                    <p>Enviada em: <?php echo htmlspecialchars($c->getCriadoEm()); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <footer>
            Portal de Estágios UniALFA — Hackathon 2026
        </footer>
    </div>
</body>
</html>
