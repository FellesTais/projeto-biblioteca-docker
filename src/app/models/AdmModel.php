<?php
require_once __DIR__ . '/../../config/config.php';
class AdmModel
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }

    public function verificaExistenciaLivro($titulo, $autor)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM livros WHERE titulo = ? AND autor = ?");
        $consulta->execute([$titulo, $autor]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado !== false;
    }

    public function cadastraLivro($titulo, $autor, $sinopse, $categoria, $data_lancamento, $capa)
    {
        $consulta = $this->conexao->prepare("INSERT INTO livros (titulo, autor, sinopse, id_categoria, data_lancamento, capa) VALUES (?, ?, ?, ?, ?, ?)");
        $consulta->execute([$titulo, $autor, $sinopse, $categoria, $data_lancamento, $capa]);
        return $consulta;
    }

    public function buscaSolicitacoes()
    {
        $consulta = $this->conexao->prepare("SELECT s.*, lgn.usuario AS usuario_nome, lv.titulo AS livro_titulo FROM solicitacoes s JOIN login lgn ON s.id_usuario = lgn.id JOIN livros lv ON s.id_livro = lv.id ORDER BY s.id DESC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluiSolicitacao($idSolicitacao)
    {
        $consulta = $this->conexao->prepare("DELETE FROM solicitacoes WHERE id = ?");
        $consulta->execute([$idSolicitacao]);
    }

    public function excluiSolicitacaoEmLote($idLivro)
    {
        $consulta = $this->conexao->prepare("DELETE FROM solicitacoes WHERE id_livro = ? and solicitacao = 'Reserva'");
        $consulta->execute([$idLivro]);
    }

    public function buscaLivroPorSolicitacao($idSolicitacao)
    {
        $consulta = $this->conexao->prepare("SELECT id_livro FROM solicitacoes where id = ?");
        $consulta->execute([$idSolicitacao]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['id_livro'] : null;
    }

    public function buscaUsuarioPorSolicitacao($idSolicitacao)
    {
        $consulta = $this->conexao->prepare("SELECT id_usuario FROM solicitacoes where id = ?");
        $consulta->execute([$idSolicitacao]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['id_usuario'] : null;
    }

    public function buscaTipoSolicitacao($idSolicitacao)
    {
        $consulta = $this->conexao->prepare("SELECT solicitacao FROM solicitacoes where id = ?");
        $consulta->execute([$idSolicitacao]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['solicitacao'] : null;
    }

    public function cadastraReserva($idLivro, $idUsuario)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $dataRetirada = date('Y-m-d');
        $dataDevolucao = date('Y-m-d', strtotime('+7 days'));

        $consulta = $this->conexao->prepare("INSERT INTO reservas (id_livro, id_usuario, data_retirada, data_devolucao, devolvido) VALUES (?, ?, ?, ?, 0)");
        $consulta->execute([$idLivro, $idUsuario, $dataRetirada, $dataDevolucao]);
        return $consulta;
    }

    public function atualizaStatusLivro($idLivro, $statusLivro)
    {
        $consulta = $this->conexao->prepare("UPDATE livros SET status = ?, reservas = reservas + 1 WHERE id = ?");
        $consulta->execute([$statusLivro, $idLivro]);
        return $consulta;
    }

    public function devolveLivro($idReserva)
    {
        $consulta = $this->conexao->prepare("UPDATE reservas SET devolvido = 1 WHERE id = ?");
        $consulta->execute([$idReserva]);
        return $consulta;
    }

    public function atualizaHistoricoRetirada($idLivro, $idUsuario)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $dataRetirada = date('Y-m-d');

        $consulta = $this->conexao->prepare("INSERT INTO historico (id_livro, id_usuario, acao, data) VALUES (?, ?, 'Retirada', ?)");
        $consulta->execute([$idLivro, $idUsuario, $dataRetirada]);
        return $consulta;
    }

    public function atualizaHistoricoDevolucao($idLivro, $idUsuario)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $dataDevolucao = date('Y-m-d');

        $consulta = $this->conexao->prepare("INSERT INTO historico (id_livro, id_usuario, acao, data) VALUES (?, ?, 'Devolução', ?)");
        $consulta->execute([$idLivro, $idUsuario, $dataDevolucao]);
        return $consulta;
    }

    public function criaCategoria($novaCategoria)
    {
        $consulta = $this->conexao->prepare("INSERT INTO categorias (categoria) VALUES (?)");
        $resultado = $consulta->execute([$novaCategoria]);
        return $resultado;
    }

    public function buscaCategoriaExistente($novaCategoria)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM categorias WHERE categoria = ?");
        $consulta->execute([$novaCategoria]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado !== false;
    }

    public function buscaCategorias()
    {
        $consulta = $this->conexao->prepare("SELECT * FROM categorias ORDER BY categoria ASC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaLivrosGuiaRetirados($limite, $offset, $categoria = '', $pesquisa = '')
    {
        $sql = "SELECT l.*, c.categoria AS nome_categoria
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            LEFT JOIN categorias c ON l.id_categoria = c.id
            WHERE l.status = 1 AND r.devolvido = 0 ";

        $params = [];

        if ($categoria !== '') {
            $sql .= " AND l.id_categoria = ? ";
            $params[] = $categoria;
        }

        if ($pesquisa !== '') {
            $sql .= " AND (l.titulo LIKE ? OR l.autor LIKE ?) ";
            $params[] = "%$pesquisa%";
            $params[] = "%$pesquisa%";
        }

        $sql .= " ORDER BY l.id DESC LIMIT ? OFFSET ?";

        $params[] = $limite;
        $params[] = $offset;

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $index => $param) {
            if ($index >= count($params) - 2) {
                $consulta->bindValue($index + 1, $param, PDO::PARAM_INT);
            } else {
                $consulta->bindValue($index + 1, $param);
            }
        }

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function qtdLivrosGuiaRetirados($categoria = '', $pesquisa = '')
    {
        $sql = "SELECT COUNT(*)
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            WHERE l.status = 1 AND r.devolvido = 0 ";

        $params = [];

        if ($categoria !== '') {
            $sql .= " AND l.id_categoria = ? ";
            $params[] = $categoria;
        }

        if ($pesquisa !== '') {
            $sql .= " AND (l.titulo LIKE ? OR l.autor LIKE ?) ";
            $params[] = "%$pesquisa%";
            $params[] = "%$pesquisa%";
        }

        $consulta = $this->conexao->prepare($sql);
        $consulta->execute($params);
        return $consulta->fetchColumn();
    }

    public function buscaLivrosGuiaAtrasados($limite, $offset, $categoria = '', $pesquisa = '')
    {
        date_default_timezone_set('America/Sao_Paulo');

        $sql = "SELECT l.*, c.categoria AS nome_categoria
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            LEFT JOIN categorias c ON l.id_categoria = c.id
            WHERE l.status = 1 
              AND r.devolvido = 0
              AND DATE(r.data_devolucao) < CURDATE() ";

        $params = [];

        if ($categoria !== '') {
            $sql .= " AND l.id_categoria = ? ";
            $params[] = $categoria;
        }

        if ($pesquisa !== '') {
            $sql .= " AND (l.titulo LIKE ? OR l.autor LIKE ?) ";
            $params[] = "%$pesquisa%";
            $params[] = "%$pesquisa%";
        }

        $sql .= " ORDER BY l.id DESC LIMIT ? OFFSET ?";

        $params[] = $limite;
        $params[] = $offset;

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $index => $param) {
            if ($index >= count($params) - 2) {
                $consulta->bindValue($index + 1, $param, PDO::PARAM_INT);
            } else {
                $consulta->bindValue($index + 1, $param);
            }
        }

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public function qtdLivrosGuiaAtrasados($categoria = '', $pesquisa = '')
    {
        $sql = "SELECT COUNT(*)
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            WHERE l.status = 1
              AND r.devolvido = 0
              AND DATE(r.data_devolucao) < CURDATE()";

        $params = [];

        if ($categoria !== '') {
            $sql .= " AND l.id_categoria = ? ";
            $params[] = $categoria;
        }

        if ($pesquisa !== '') {
            $sql .= " AND (l.titulo LIKE ? OR l.autor LIKE ?) ";
            $params[] = "%$pesquisa%";
            $params[] = "%$pesquisa%";
        }

        $consulta = $this->conexao->prepare($sql);
        $consulta->execute($params);

        return $consulta->fetchColumn();
    }


    public function buscaInformacoesLivroRetirado($idLivro)
    {
        $consulta = $this->conexao->prepare("SELECT l.usuario AS usuario, r.data_retirada, r.data_devolucao FROM reservas r JOIN login l ON l.id = r.id_usuario WHERE r.id_livro = ? AND r.devolvido = 0");
        $consulta->execute([$idLivro]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function excluiLivro($id)
    {
        $consulta = $this->conexao->prepare("DELETE FROM livros WHERE id = ?");
        $consulta->execute([$id]);
        return $consulta;
    }

    public function buscaHistoricoPorUsuario($idUsuario)
    {
        $consulta = $this->conexao->prepare("SELECT h.*, l.titulo FROM historico h JOIN livros l ON h.id_livro = l.id WHERE h.id_usuario = ? ORDER BY id DESC");
        $consulta->execute([$idUsuario]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado === false) {
            return [];
        }
        return $resultado;
    }

    public function buscaIdPorLivro($livro)
    {
        $consulta = $this->conexao->prepare("SELECT id FROM livros WHERE titulo = ?");
        $consulta->execute([$livro]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return null;
        }

        return $resultado['id'];
    }

    public function buscaHistoricoPorLivro($idLivro)
    {
        $consulta = $this->conexao->prepare("SELECT h.*, l.usuario FROM historico h JOIN login l ON h.id_usuario = l.id WHERE h.id_livro = ? ORDER BY id DESC");
        $consulta->execute([$idLivro]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado === false) {
            return [];
        }
        return $resultado;
    }

    public function buscaPendenciaPorUsuario($idUsuario)
    {
        $dataAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dataFormatada = $dataAtual->format('Y-m-d');

        $consulta = $this->conexao->prepare("SELECT l.titulo, r.data_devolucao FROM reservas r JOIN livros l ON r.id_livro = l.id WHERE r.id_usuario = ? AND r.devolvido = 0 AND r.data_devolucao < ? ORDER BY r.id DESC");
        $consulta->execute([$idUsuario, $dataFormatada]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado === false) {
            return [];
        }

        foreach ($resultado as &$item) {
            $dataDevolucao = new DateTime($item['data_devolucao']);
            $interval = $dataDevolucao->diff($dataAtual);
            $item['dias_atraso'] = $interval->days;
        }

        return $resultado;
    }

    public function buscaPendenciaPorLivro($idLivro)
    {
        $dataAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dataFormatada = $dataAtual->format('Y-m-d');

        $consulta = $this->conexao->prepare("SELECT r.data_devolucao, l.usuario FROM reservas r JOIN login l ON r.id_usuario = l.id WHERE r.id_livro = ? AND r.devolvido = 0 AND r.data_devolucao < ? ORDER BY r.id DESC");
        $consulta->execute([$idLivro, $dataFormatada]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if ($resultado === false) {
            return [];
        }

        foreach ($resultado as &$item) {
            $dataDevolucao = new DateTime($item['data_devolucao']);
            $interval = $dataDevolucao->diff($dataAtual);
            $item['dias_atraso'] = $interval->days;
        }

        return $resultado;
    }

    public function buscaIndicacoes()
    {
        $consulta = $this->conexao->prepare("SELECT i.titulo, i.autor, COUNT(*) AS total_indicacoes FROM indicacoes i WHERE NOT EXISTS (SELECT 1 FROM livros l WHERE l.titulo = i.titulo AND l.autor = i.autor) GROUP BY i.titulo, i.autor ORDER BY total_indicacoes DESC LIMIT 10");
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function buscaLivrosEmAtraso()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $consulta = $this->conexao->prepare("SELECT l.titulo, l.autor, r.data_devolucao, DATEDIFF(CURDATE(), r.data_devolucao) AS dias_atraso FROM reservas r JOIN livros l ON r.id_livro = l.id WHERE r.devolvido = 0 AND r.data_devolucao < CURDATE() ORDER BY dias_atraso DESC LIMIT 10");
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function buscaUsuariosEmAtraso()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $consulta = $this->conexao->prepare("SELECT l.usuario, r.id_usuario, COUNT(*) AS total_pendencias FROM reservas r JOIN login l ON r.id_usuario = l.id WHERE r.devolvido = 0 GROUP BY r.id_usuario, l.usuario ORDER BY total_pendencias DESC LIMIT 10");
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function buscaDadosLivro($id)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM livros WHERE id = ?");
        $consulta->execute([$id]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function modificaLivro($titulo, $autor, $sinopse, $categoria, $data_lancamento, $id, $capa = null)
    {
        if ($capa === null) {
            $consulta = $this->conexao->prepare("SELECT capa FROM livros WHERE id = ?");
            $consulta->execute([$id]);
            $capa = $consulta->fetchColumn();
        }

        $consulta = $this->conexao->prepare(
            "UPDATE livros 
         SET titulo = ?, autor = ?, sinopse = ?, id_categoria = ?, data_lancamento = ?, capa = ? 
         WHERE id = ?"
        );
        $resultado = $consulta->execute([$titulo, $autor, $sinopse, $categoria, $data_lancamento, $capa, $id]);

        return $resultado;
    }

    public function criaNotificacaoRetirada($idUsuario, $idLivro)
    {
        $consulta = $this->conexao->prepare("INSERT INTO notificacoes (id_usuario, id_livro, texto) VALUES (?, ?, 'Retirada de livro confirmada.')");
        $consulta->execute([$idUsuario, $idLivro]);
        return $consulta;
    }

    public function criaNotificacaoDevolucao($idUsuario, $idLivro)
    {
        $consulta = $this->conexao->prepare("INSERT INTO notificacoes (id_usuario, id_livro, texto) VALUES (?, ?, 'Devolução de livro confirmada.')");
        $consulta->execute([$idUsuario, $idLivro]);
        return $consulta;
    }

}
?>