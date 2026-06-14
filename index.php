<?php
session_start();
if (isset($_GET['logout'])) { session_destroy(); header('Location: index.php'); exit; }
if (!empty($_SESSION['empresa_id'])) { header('Location: pages/dashboard.php'); exit; }

require_once 'classes/ApiClient.php';
require_once 'classes/Empresa.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        $erro = 'Digite seu e-mail.';
    } else {
        try {
            $api = new ApiClient();
            $res = $api->getEmpresaPorEmail($email);
            if (!empty($res[0])) {
                $emp = new Empresa($res[0]);
                if (!$emp->estaAprovada())
                    $erro = 'Cadastro pendente de aprovacao pela UniALFA.';
                else {
                    $_SESSION['empresa_id']   = $emp->getId();
                    $_SESSION['empresa_nome'] = $emp->getNome();
                    header('Location: pages/dashboard.php'); exit;
                }
            } else {
                $erro = 'E-mail nao encontrado.';
            }
        } catch (RuntimeException $e) {
            $erro = 'Erro ao conectar na API. Verifique se ela esta rodando.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login — Painel Empresa UniALFA</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Painel da Empresa</h1>
        <p>Acesso exclusivo para empresas parceiras UniALFA</p>
        <?php if ($erro): ?>
            <div class="alert-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>E-mail cadastrado</label>
            <input type="email" name="email" required autofocus placeholder="empresa@email.com">
            <button type="submit">Entrar</button>
        </form>
        <p style="margin-top:16px;font-size:13px">
            Nao tem conta? <a href="pages/cadastro.php">Cadastre sua empresa</a>
        </p>
    </div>
</body>
</html>
