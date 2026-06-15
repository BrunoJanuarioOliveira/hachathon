<?php
session_start();
require_once __DIR__ . '/classes/ApiClient.php';
require_once __DIR__ . '/classes/Aluno.php';

$erro = '';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ra = trim($_POST['ra'] ?? '');

    if ($ra === '') {
        $erro = 'Preencha o RA para entrar.';
    } else {
        try {
            $api = new ApiClient();
            $dados = $api->getAlunoPorRa($ra);

            if (!empty($dados)) {
                $aluno = new Aluno($dados);
                if (!$aluno->isApto()) {
                    $erro = 'Acesso bloqueado. Contate a UniALFA.';
                } else {
                    $_SESSION['aluno_id'] = $aluno->getId();
                    $_SESSION['aluno_nome'] = $aluno->getNome();
                    $_SESSION['aluno_ra'] = $aluno->getRa();
                    header('Location: pages/vagas.php');
                    exit;
                }
            } else {
                $erro = 'RA nao encontrado.';
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Estágios UniALFA</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div>
                    <h1>Portal de Estágios UniALFA</h1>
                    <p>Hackathon 2026 · Tecnologia em Sistemas para Internet</p>
                </div>
            </div>

            <?php if ($erro): ?>
                <div class="alert"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="ra">Registro Academico (RA)</label>
                    <input class="form-control" type="text" id="ra" name="ra" value="<?php echo htmlspecialchars($_POST['ra'] ?? ''); ?>" placeholder="Digite seu RA">
                </div>
                <button class="button" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
