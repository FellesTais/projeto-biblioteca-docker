<form action="../index.php?action=cadastrarUsuario" method="POST">
    <h3>Cadastrar</h3>
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="text" name="usuario" placeholder="Nome usuário" required>
    <input type="text" name="cpf" placeholder="CPF" required>
    <input type="text" name="email" placeholder="E-mail" required>
    <input type="text" name="telefone" placeholder="Telefone" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="password" name="senhac" placeholder="Confirmar senha" required>
    <button class="secondary">Criar</button>

    <!-- Mensagem de erro -->
    <?php if (isset($erroCadastro)): ?>
        <p class="mensagem_erro"><?= $erroCadastro ?></p>
    <?php endif; ?>
</form>