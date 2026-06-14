<?php
session_start();
if (empty($_SESSION['empresa_id'])) { header('Location: ../index.php'); exit; }

require_once '../classes/ApiClient.php';

$api    = new ApiClient();
$empId  = (int)$_SESSION['empresa_id'];
$vagas  = $api->getVagasEmpresa($empId);
$totalC = 0; $emAnal = 0;
foreach ($vagas as $v) {
    $c       = $api->getCandidatosPorVaga($v['id']);
    $totalC += count($c);
    $emAnal += count(array_filter($c, fn($x) => $x['status'] === 'em_analise'));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Painel Empresa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <span class="nav-brand">Painel Empresa — UniALFA</span>
        <div class="nav-links">
            <a href="dashboard.php" class="ativo">Dashboard</a>
            <a href="vagas.php">Minhas Vagas</a>
            <a href="nova_vaga.php">+ Nova Vaga</a>
            <a href="../index.php?logout=1">Sair</a>
        </div>
        <span class="nav-user"><?= htmlspecialchars($_SESSION['empresa_nome']) ?></span>
    </nav>
    <main class="container">
        <h1>Dashboard</h1>
        <div class="grid-metricas">
            <div class="metrica">
                <div class="metrica-valor"><?= count($vagas) ?></div>
                <div class="metrica-label">Vagas ativas</div>
            </div>
            <div class="metrica">
                <div class="metrica-valor"><?= $totalC ?></div>
                <div class="metrica-label">Candidaturas recebidas</div>
            </div>
            <div class="metrica">
                <div class="metrica-valor"><?= $emAnal ?></div>
                <div class="metrica-label">Em analise</div>
            </div>
        </div>
        <div style="margin-top:20px;display:flex;gap:8px">
            <a href="nova_vaga.php" class="btn-primary">+ Publicar Nova Vaga</a>
            <a href="vagas.php" class="btn-outline">Ver Minhas Vagas</a>
        </div>
    </main>
</body>
</html>
