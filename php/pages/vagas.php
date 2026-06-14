<?php
session_start();
if (empty($_SESSION['empresa_id'])) { header('Location: ../index.php'); exit; }

require_once '../classes/ApiClient.php';
require_once '../classes/Vaga.php';

$api   = new ApiClient();
$empId = (int)$_SESSION['empresa_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['encerrar_id'])) {
    $api->deletarVaga((int)$_POST['encerrar_id']);
    header('Location: vagas.php'); exit;
}

$vagas = array_map(fn($d) => new Vaga($d), $api->getVagasEmpresa($empId));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Vagas — Painel Empresa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <span class="nav-brand">Painel Empresa — UniALFA</span>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="vagas.php" class="ativo">Minhas Vagas</a>
            <a href="nova_vaga.php">+ Nova Vaga</a>
            <a href="../index.php?logout=1">Sair</a>
        </div>
    </nav>
    <main class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h1 style="margin:0">Minhas Vagas</h1>
            <a href="nova_vaga.php" class="btn-primary">+ Nova Vaga</a>
        </div>
        <?php if (empty($vagas)): ?>
            <p class="vazio">Nenhuma vaga publicada ainda.</p>
        <?php else: foreach ($vagas as $v): ?>
            <div class="card-vaga">
                <div class="card-header">
                    <div>
                        <h2><?= htmlspecialchars($v->getTitulo()) ?></h2>
                        <p class="empresa">Area: <?= htmlspecialchars($v->getArea()) ?> | Bolsa: <?= $v->getBolsaFormatada() ?></p>
                    </div>
                    <span class="badge-green">Ativa</span>
                </div>
                <div style="display:flex;gap:8px;margin-top:8px">
                    <a href="editar_vaga.php?id=<?= $v->getId() ?>" class="btn-outline btn-sm">Editar</a>
                    <a href="candidatos.php?vaga_id=<?= $v->getId() ?>" class="btn-outline btn-sm">Ver Candidatos</a>
                    <form method="POST" style="display:inline" onsubmit="return confirm('Encerrar esta vaga?')">
                        <input type="hidden" name="encerrar_id" value="<?= $v->getId() ?>">
                        <button type="submit" class="btn-danger btn-sm">Encerrar</button>
                    </form>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </main>
</body>
</html>
