<?php
session_start();
if (empty($_SESSION['empresa_id'])) { header('Location: ../index.php'); exit; }

require_once '../classes/ApiClient.php';

$api = new ApiClient();
$msg = $erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'empresa_id' => (int)$_SESSION['empresa_id'],
        'titulo'     => trim($_POST['titulo']    ?? ''),
        'area'       => trim($_POST['area']      ?? ''),
        'bolsa'      => (float)($_POST['bolsa']  ?? 0),
        'descricao'  => trim($_POST['descricao'] ?? ''),
    ];
    if (strlen($dados['titulo']) < 3) {
        $erro = 'O titulo deve ter pelo menos 3 caracteres.';
    } else {
        $res = $api->criarVaga($dados);
        $msg = !empty($res['id'])
            ? 'Vaga publicada com sucesso! Visivel para os alunos.'
            : ($res['erro'] ?? 'Erro ao publicar vaga.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Vaga — Painel Empresa</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar">
        <span class="nav-brand">Painel Empresa — UniALFA</span>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="vagas.php">Minhas Vagas</a>
            <a href="nova_vaga.php" class="ativo">+ Nova Vaga</a>
        </div>
    </nav>
    <main class="container" style="max-width:600px">
        <h1>Publicar Nova Vaga</h1>
        <?php if ($msg):  ?><div class="alert-sucesso"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="alert-erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <form method="POST" class="form-card">
            <div class="form-grupo">
                <label>Titulo da Vaga *</label>
                <input type="text" name="titulo" required placeholder="Ex: Estagio em Desenvolvimento Web">
            </div>
            <div class="form-linha">
                <div class="form-grupo">
                    <label>Area</label>
                    <input type="text" name="area" placeholder="Ex: Desenvolvimento">
                </div>
                <div class="form-grupo">
                    <label>Bolsa (R$/mes)</label>
                    <input type="number" name="bolsa" step="0.01" min="0" placeholder="0.00">
                </div>
            </div>
            <div class="form-grupo">
                <label>Descricao</label>
                <textarea name="descricao" rows="4" placeholder="Descreva as atividades do estagio..."></textarea>
            </div>
            <div style="display:flex;gap:8px">
                <button type="submit" class="btn-primary">Publicar Vaga</button>
                <a href="vagas.php" class="btn-outline">Cancelar</a>
            </div>
        </form>
    </main>
</body>
</html>
