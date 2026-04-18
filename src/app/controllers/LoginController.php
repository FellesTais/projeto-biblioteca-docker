<?php

require_once __DIR__ . '/../models/LoginModel.php';

class LoginController
{

    private $LoginModel;
    public function __construct()
    {
        //Define a conexão como global
        global $conexao;

        $this->LoginModel = new LoginModel($conexao);
    }

    public function exibeLogin($erroLogin = null)
    {
        include __DIR__ . '/../views/login/login.php';
    }

    public function exibeTelaCadastrar($erroCadastro = null)
    {
        include __DIR__ . '/../views/login/login.php';
    }

    public function exibeTelaRedefinirSenha($erroRedefinirSenha = null)
    {
        include __DIR__ . '/../views/login/redefinicao_senha.php';
    }

    public function validaLogin()
    {
        session_start();

        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        $usuarioValido = $this->LoginModel->validaLogin($usuario, $senha);
        if ($usuarioValido && $usuario === 'admin') {
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php?action=telaInicialAdm');
            exit();
        } else if ($usuarioValido && $usuario != 'admin'){
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php?action=telaInicial');
            exit();
        }
        else {
            $this->exibeLogin("Usuário ou senha incorretos.");
        }
    }

    public function cadastraUsuario()
    {
        session_start();

        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $senha = $_POST['senha'];
        $senhac = $_POST['senhac'];

        //validar se ja existe usuario com esse nome
        // validar se ja existe esse cpf cadastrado
        // validar se ja existe esse email cadastrado  

        $usuarioExistente = $this->LoginModel->buscaUsuario($usuario);
        $cpfExistente = $this->LoginModel->buscaCPF($cpf);
        $emailExistente = $this->LoginModel->buscaEmail($email);

        if ($usuarioExistente === false) {
            if ($cpfExistente === false) {
                if ($emailExistente === false) {
                    if ($senha === $senhac) {
                        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                        $mensagem = $this->LoginModel->cadastraUsuario($nome, $usuario, $cpf, $email, $telefone, $senhaHash);
                        $this->exibeTelaCadastrar($mensagem);
                    } else {
                        $this->exibeTelaCadastrar("As senhas não coincidem.");
                    }
                } else {
                    $this->exibeTelaCadastrar("E-mail já cadastrado.");
                }
            } else {
                $this->exibeTelaCadastrar("CPF já cadastrado.");
            }
        } else {
            $this->exibeTelaCadastrar("Usuário já cadastrado.");
        }
    }

    public function redefineSenha()
    {
        $usuario = $_POST['usuario'];
        $novaSenha = $_POST['novaSenha'];
        $confirmaNovaSenha = $_POST['confirmaNovaSenha'];

        $usuarioValido = $this->LoginModel->buscaUsuario($usuario);

        if ($usuarioValido) {
            if ($novaSenha === $confirmaNovaSenha) {
                $mensagem = $this->LoginModel->alteraSenha( $usuario, $novaSenha);
                $this->exibeTelaRedefinirSenha($mensagem);
            } else {
                $this->exibeTelaRedefinirSenha("As senhas não coincidem.");
            }
        } else {
            $this->exibeTelaRedefinirSenha("Usuário não encontrado.");
        }
    }

}

?>