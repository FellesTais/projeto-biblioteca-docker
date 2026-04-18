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
        <div class="form_container2">
            <div class="form_box sign_in_form">
                <form action="../index.php?action=redefinirSenha" method="POST">
                    <h3>Redefinir senha</h3>
                    <input type="text" name="usuario" placeholder="Usuário" required>
                    <input type="password" name="novaSenha" placeholder="Nova senha" required>
                    <input type="password" name="confirmaNovaSenha" placeholder="Confirmar senha" required>
                    <div class="botoes_alinhados">
                        <button type="submit" class="primary" action="../index.php?action=redefinirSenha" method="POST">Redefinir</button>
                        <button type="button" class="secondary" onclick="window.location.href='../index.php?view=login'">Voltar</button>
                    </div>

                    <!-- Mensagem de erro -->
                    <?php if (isset($erroRedefinirSenha)): ?>
                        <p class="mensagem_erro"><?= $erroRedefinirSenha ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
</body>

</html>