<?php
session_start();
if (empty($_SESSION['aluno_id'])) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../classes/ApiClient.php';
require_once __DIR__ . '/../classes/Vaga.php';

$api = new ApiClient('localhost/hachathon-fixed/php-aluno/api.php');
$erro = '';
$mensagem = '';
$vaga = null;

$vagaId = (int) ($_GET['vaga_id'] ?? 0);
if ($vagaId <= 0) {
    header('Location: vagas.php');
    exit;
}

try {
    $vagas = $api->getVagas();
    foreach ($vagas as $d) {
        if ((int)$d['id'] === $vagaId) {
            $vaga = new Vaga($d);
            break;
        }
    }

    if (!$vaga) {
        $erro = 'Vaga nao encontrada.';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $res = $api->candidatar((int)$_SESSION['aluno_id'], $vagaId);
        if (!empty($res['id'])) {
            $mensagem = 'Candidatura enviada com sucesso!';
        } else {
            $erro = $res['erro'] ?? 'Erro ao enviar candidatura.';
        }
    }
} catch (RuntimeException $e) {
    $erro = 'Erro ao conectar na API. ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatura - Portal UniALFA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div>
                <h1>Candidatura</h1>
                <p>Vaga selecionada</p>
            </div>
            <div>
                <a class="button-secondary" href="../index.php?logout=1">Sair</a>
            </div>
        </header>

        <nav class="menu">
            <a href="vagas.php">Vagas</a>
            <a href="minhas_candidaturas.php">Minhas Candidaturas</a>
            <a href="notificacoes.php">Notificacoes</a>
        </nav>

        <?php if ($erro): ?>
            <div class="alert"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if ($mensagem): ?>
            <div class="card">
                <p><?php echo htmlspecialchars($mensagem); ?></p>
                <a class="button-secondary" href="vagas.php">Ver mais vagas</a>
                <a class="button" href="minhas_candidaturas.php">Minhas Candidaturas</a>
            </div>
        <?php elseif ($vaga): ?>
            <div class="card">
                <h2><?php echo htmlspecialchars($vaga->getTitulo()); ?></h2>
                <p><strong>Empresa:</strong> <?php echo htmlspecialchars($vaga->getEmpresaNome()); ?></p>
                <p><strong>Area:</strong> <?php echo htmlspecialchars($vaga->getArea()); ?></p>
                <p><strong>Bolsa:</strong> <?php echo htmlspecialchars($vaga->getBolsaFormatada()); ?>/mes</p>
                <p><?php echo htmlspecialchars($vaga->getDescricao()); ?></p>

                <form method="post">
                    <button class="button" type="submit">Confirmar candidatura</button>
                </form>
            </div>
        <?php endif; ?>

        <footer>
            Portal de Estágios UniALFA — Hackathon 2026
        </footer>
    </div>
</body>
</html>
