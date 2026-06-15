<?php
session_start();
if (empty($_SESSION['aluno_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../classes/ApiClient.php';

$api = new ApiClient('localhost/hachathon-fixed/php-aluno/api.php');
$erro = '';
$notifs = [];

try {
    $notifs = $api->getNotificacoesAluno((int)$_SESSION['aluno_id']);
    foreach ($notifs as $n) {
        if (!$n['lida']) {
            $api->marcarNotificacaoLida((int)$n['id']);
        }
    }
} catch (RuntimeException $e) {
    $erro = 'Erro ao buscar notificacoes. ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificacoes - Portal UniALFA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div>
                <h1>Notificacoes</h1>
                <p>Ultimas atualizacoes</p>
            </div>
            <div>
                <a class="button-secondary" href="../index.php?logout=1">Sair</a>
            </div>
        </header>

        <nav class="menu">
            <a href="vagas.php">Vagas</a>
            <a href="minhas_candidaturas.php">Minhas Candidaturas</a>
            <a class="active" href="notificacoes.php">Notificacoes</a>
        </nav>

        <?php if ($erro): ?>
            <div class="alert"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if (empty($notifs)): ?>
            <div class="card">
                <p>Nenhuma notificacao.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifs as $n): ?>
                <div class="card">
                    <p><?php echo htmlspecialchars($n['mensagem']); ?></p>
                    <p><strong>Status:</strong> <?php echo $n['lida'] ? 'Enviada' : 'Respondida'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <footer>
            Portal de Estágios UniALFA — Hackathon 2026
        </footer>
    </div>
</body>
</html>
