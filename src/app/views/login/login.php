<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/estilo_login.css">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">-->
    <title>Login</title>
</head>

<body style="font-family: 'Comfortaa', sans-serif;">
    <section class="container">
        <div class="box sign_in">
            <h2>Já possui uma conta?</h2>
            <button class="botoes" id="sign_in_btn">Entrar</button>
        </div>
        <div class="box sign_up">
            <h2>Não tem uma conta?</h2>
            <button class="botoes" id="sign_up_btn">Cadastre-se</button>
        </div>
        <div class="form_container">
            <div class="form_box sign_in_form">
                <form action="../index.php?action=validarLogin" method="POST">
                    <h3>Entrar</h3>
                    <input type="text" name="usuario" placeholder="Usuário" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <button class="primary">Entrar</button>
                    <a href="../index.php?view=redefinirSenha">Esqueceu sua senha?</a>

                    <!-- Mensagem de erro -->
                    <?php if (isset($erroLogin)): ?>
                        <p class="mensagem_erro"><?= $erroLogin ?></p>
                    <?php endif; ?>

                </form>
            </div>
            <div class="form_box sign_up_form">
                <?php include 'form_cadastro.php'; ?>
            </div>
        </div>
    </section>

    <script src="assets/login.js"></script>
    <?php if (isset($erroCadastro)): ?>
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                document.getElementById('sign_up_btn').click();
            });
        </script>
    <?php endif; ?>
</body>

</html>