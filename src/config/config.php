<?php 

class Conexao {
    public static function conectar(){
        #Credenciais de conexão do banco de dados
        $host = "db";              // ← mudou aqui
        $base = "biblioteca";
        $usuario = "root"; 
        $senha = "root";           // ← mudou aqui também

        try{
            $conexao = new PDO("mysql:host=$host;dbname=$base", $usuario, $senha);
            return $conexao;
        } catch(PDOException $e){
            die("Erro ao conectar: " . $e->getMessage());
        }
    }
}
?>