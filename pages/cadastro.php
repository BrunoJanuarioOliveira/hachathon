<?php
session_start();
require_once '../classes/ApiClient.php';

$api = new ApiClient();
$msg = $erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome'  => trim($_POST['nome']  ?? ''),
        'cnpj'  => trim($_POST['cnpj']  ?? ''),
        'email' => trim($_POST['email'] ?? ''),
    ];
    if (empty($dados['nome']) || empty($dados['cnpj']) || empty($dados['email'])) {
        $erro = 'Todos os campos sao obrigatorios.';
    } else {
        $res = $api->cadastrarEmpresa($dados);
        $msg = !empty($res['id'])
            ? 'Cadastro enviado! Aguarde aprovacao da UniALFA.'
            : ($res['erro'] ?? 'Erro ao cadastrar.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Empresa — UniALFA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="login-container" style="max-width:480px">
        <h1>Cadastrar Empresa</h1>
        <p>Preencha os dados para solicitar acesso ao portal</p>
        <?php if ($msg):  ?><div class="alert-sucesso"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="alert-erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <form method="POST">
            <label>Nome da Empresa *</label>
            <input type="text" name="nome" required placeholder="Ex: TechSoft Sistemas">
            <label>CNPJ *</label>
            <input type="text" name="cnpj" required placeholder="00.000.000/0001-00">
            <label>E-mail *</label>
            <input type="email" name="email" required placeholder="contato@empresa.com">
            <button type="submit">Enviar Cadastro</button>
        </form>
        <p style="margin-top:16px;font-size:13px">
            Ja tem conta? <a href="../index.php">Fazer Login</a>
        </p>
    </div>
</body>
</html>
