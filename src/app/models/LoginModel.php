<?php
require_once __DIR__ . '/../../config/config.php';
class LoginModel
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }

    public function validaLogin($usuario, $senha)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM login WHERE usuario = ?");
        $consulta->execute([$usuario]);
        $usuarioDados = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuarioDados && password_verify($senha, $usuarioDados['senha'])) {
            return $usuarioDados;
        }
        return false;

    }

    public function cadastraUsuario($nome, $usuario, $cpf, $email, $telefone, $senha)
    {
        $consulta = $this->conexao->prepare("INSERT INTO login (nome, usuario, cpf, email, telefone, senha) VALUES (?, ?, ?, ?, ?, ?)");
        if ($consulta->execute([$nome, $usuario, $cpf, $email, $telefone, $senha])) {
            return "Usuário criado com sucesso!";
        } else {
            return "Erro ao criar usuário.";
        }
    }

    public function buscaUsuario($usuario)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM login WHERE usuario = ?");
        $consulta->execute([$usuario]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaCPF($cpf)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM login WHERE cpf = ?");
        $consulta->execute([$cpf]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaEmail($email)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM login WHERE email = ?");
        $consulta->execute([$email]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function alteraSenha($usuario, $novaSenha)
    {

        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $consulta = $this->conexao->prepare("UPDATE login SET senha = ? WHERE usuario = ?");
        if ($consulta->execute([$senhaHash, $usuario])) {
            return "Senha alterada com sucesso!";
        } else {
            return "Erro ao alterar a senha.";
        }
    }

}
?>