<?php
session_start();
if (empty($_SESSION['empresa_id'])) { header('Location: ../index.php'); exit; }

require_once '../classes/ApiClient.php';

$api    = new ApiClient();
$vagaId = (int)($_GET['id'] ?? 0);
if ($vagaId <= 0) { header('Location: vagas.php'); exit; }

$vaga = $api->getVagaPorId($vagaId);
$msg  = $erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'titulo'    => trim($_POST['titulo']    ?? ''),
        'area'      => trim($_POST['area']      ?? ''),
        'bolsa'     => (float)($_POST['bolsa']  ?? 0),
        'descricao' => trim($_POST['descricao'] ?? ''),
        'ativa'     => 1,
    ];
    if (strlen($dados['titulo']) < 3) {
        $erro = 'Titulo muito curto (minimo 3 caracteres).';
    } else {
        $res  = $api->atualizarVaga($vagaId, $dados);
        $msg  = !empty($res['mensagem']) ? 'Vaga atualizada com sucesso!' : ($res['erro'] ?? 'Erro ao atualizar.');
        $vaga = $api->getVagaPorId($vagaId);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Vaga — Painel Empresa</title>
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
    <main class="container" style="max-width:600px">
        <h1>Editar Vaga</h1>
        <?php if ($msg):  ?><div class="alert-sucesso"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="alert-erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <form method="POST" class="form-card">
            <div class="form-grupo">
                <label>Titulo *</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($vaga['titulo'] ?? '') ?>" required>
            </div>
            <div class="form-linha">
                <div class="form-grupo">
                    <label>Area</label>
                    <input type="text" name="area" value="<?= htmlspecialchars($vaga['area'] ?? '') ?>">
                </div>
                <div class="form-grupo">
                    <label>Bolsa (R$/mes)</label>
                    <input type="number" step="0.01" name="bolsa" value="<?= htmlspecialchars($vaga['bolsa'] ?? 0) ?>">
                </div>
            </div>
            <div class="form-grupo">
                <label>Descricao</label>
                <textarea name="descricao" rows="4"><?= htmlspecialchars($vaga['descricao'] ?? '') ?></textarea>
            </div>
            <div style="display:flex;gap:8px">
                <button type="submit" class="btn-primary">Salvar Alteracoes</button>
                <a href="vagas.php" class="btn-outline">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
