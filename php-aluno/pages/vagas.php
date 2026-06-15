<?php
session_start();
if (empty($_SESSION['aluno_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../classes/ApiClient.php';
require_once __DIR__ . '/../classes/Vaga.php';

$api = new ApiClient('http://localhost/hackatoon/api.php');
$dados = [];
$erro = '';

try {
    $dados = $api->getVagas();
} catch (RuntimeException $e) {
    $erro = 'Erro ao buscar vagas. ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas - Portal UniALFA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div>
                <h1>Portal UniALFA</h1>
                <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['aluno_nome']); ?>.</p>
            </div>
            <div>
                <a class="button-secondary" href="../index.php?logout=1">Sair</a>
            </div>
        </header>

        <nav class="menu">
            <a class="active" href="vagas.php">Vagas</a>
            <a href="minhas_candidaturas.php">Minhas Candidaturas</a>
            <a href="notificacoes.php">Notificacoes</a>
        </nav>

        <?php if ($erro): ?>
            <div class="alert"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Vagas Disponiveis</h2>
            <p>Selecione uma vaga e envie sua candidatura.</p>
        </div>

        <?php if (empty($dados)): ?>
            <div class="card">
                <p>Nenhuma vaga disponivel.</p>
            </div>
        <?php else: ?>
            <?php foreach ($dados as $d): $vaga = new Vaga($d); ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($vaga->getTitulo()); ?></h3>
                    <p><strong>Empresa:</strong> <?php echo htmlspecialchars($vaga->getEmpresaNome()); ?></p>
                    <p><strong>Area:</strong> <?php echo htmlspecialchars($vaga->getArea()); ?></p>
                    <p><strong>Bolsa:</strong> <?php echo htmlspecialchars($vaga->getBolsaFormatada()); ?>/mes</p>
                    <p><?php echo htmlspecialchars($vaga->getDescricao()); ?></p>
                    <a class="button" href="candidatura.php?vaga_id=<?php echo $vaga->getId(); ?>">Candidatar-se</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <footer>
            Portal de Estágios UniALFA — Hackathon 2026
        </footer>
    </div>
</body>
</html>
