<?php
session_start();
if (empty($_SESSION['empresa_id'])) { header('Location: ../index.php'); exit; }

require_once '../classes/ApiClient.php';

$api    = new ApiClient();
$vagaId = (int)($_GET['vaga_id'] ?? 0);
if ($vagaId <= 0) { header('Location: vagas.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cId = (int)($_POST['cand_id'] ?? 0);
    $st  = $_POST['status'] ?? '';
    if (in_array($st, ['aprovada','reprovada','em_analise']) && $cId > 0)
        $api->atualizarStatusCandidatura($cId, $st);
    header('Location: candidatos.php?vaga_id=' . $vagaId); exit;
}

$candidatos = $api->getCandidatosPorVaga($vagaId);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Candidatos — Painel Empresa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <span class="nav-brand">Painel Empresa — UniALFA</span>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="vagas.php" class="ativo">Minhas Vagas</a>
        </div>
    </nav>
    <main class="container">
        <h1>Candidatos — Vaga #<?= $vagaId ?></h1>
        <?php if (empty($candidatos)): ?>
            <p class="vazio">Nenhum candidato ainda.</p>
        <?php else: ?>
            <table class="tabela">
                <thead>
                    <tr><th>Aluno</th><th>RA</th><th>Email</th><th>Status</th><th>Acoes</th></tr>
                </thead>
                <tbody>
                <?php foreach ($candidatos as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['aluno_nome']) ?></td>
                        <td><?= htmlspecialchars($c['ra']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $c['status'])) ?></td>
                        <td>
                            <form method="POST" style="display:flex;gap:4px">
                                <input type="hidden" name="cand_id" value="<?= $c['id'] ?>">
                                <button name="status" value="aprovada"   class="btn-success btn-sm">Aprovar</button>
                                <button name="status" value="em_analise" class="btn-outline btn-sm">Analisar</button>
                                <button name="status" value="reprovada"  class="btn-danger btn-sm">Reprovar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="vagas.php" class="btn-outline" style="margin-top:16px;display:inline-block">Voltar para Vagas</a>
    </main>
</body>
</html>
