<?php
require_once __DIR__ . '/../../config/config.php';

class InicialModel
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = Conexao::conectar();
    }

    public function buscaLivro()
    {
        $consulta = $this->conexao->prepare("SELECT livros.*, categorias.categoria AS nome_categoria FROM livros LEFT JOIN categorias ON livros.id_categoria = categorias.id ORDER BY livros.id DESC");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaLivrosEmAlta($limit = 6)
    {
        $consulta = $this->conexao->prepare("SELECT livros.*, categorias.categoria AS nome_categoria FROM livros LEFT JOIN categorias ON livros.id_categoria = categorias.id WHERE livros.status = 0 ORDER BY livros.reservas DESC LIMIT ?");
        $consulta->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaLivroPorId($id)
    {
        $consulta = $this->conexao->prepare("SELECT livros.*, categorias.categoria AS nome_categoria FROM livros LEFT JOIN categorias ON livros.id_categoria = categorias.id WHERE livros.id = ?");
        $consulta->execute([$id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaLivrosPorId($favoritos)
    {
        if (empty($favoritos))
            return [];

        $placeholders = implode(',', array_fill(0, count($favoritos), '?'));
        $sql = "SELECT id, titulo FROM livros WHERE id IN ($placeholders) ORDER BY titulo ASC";
        $consulta = $this->conexao->prepare($sql);
        $consulta->execute($favoritos);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaIdPorUsuario($usuario)
    {
        $consulta = $this->conexao->prepare("SELECT id FROM login WHERE usuario = ?");
        $consulta->execute([$usuario]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return null;
        }

        return $resultado['id'];
    }

    public function favoritaLivro($idUsuario, $idLivro)
    {
        $consulta = $this->conexao->prepare("SELECT * FROM favoritos_usuario WHERE id_usuario = ? and id_livro = ?");
        $consulta->execute([$idUsuario, $idLivro]);
        $validaExistencia = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($validaExistencia) {
            $novoFavorito = $validaExistencia['favorito'] == 1 ? 0 : 1;
            $sqlUpdate = "UPDATE favoritos_usuario SET favorito = ? WHERE id_usuario = ? AND id_livro = ?";
            $stmtUpdate = $this->conexao->prepare($sqlUpdate);
            return $stmtUpdate->execute([$novoFavorito, $idUsuario, $idLivro]);
        } else {
            $consulta = $this->conexao->prepare("INSERT INTO favoritos_usuario (id_usuario, id_livro, favorito) VALUES (?, ?, 1)");
            return $consulta->execute([$idUsuario, $idLivro]);
        }

    }

    public function verificaFavorito($idUsuario, $idLivro)
    {
        $consulta = $this->conexao->prepare("SELECT favorito FROM favoritos_usuario WHERE id_usuario = ? AND id_livro = ?");
        $consulta->execute([$idUsuario, $idLivro]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            return (int) $resultado['favorito'];
        }
        return 0;
    }

    public function buscaFavoritosPorUsuario($idUsuario)
    {
        $consulta = $this->conexao->prepare(query: "SELECT id_livro FROM favoritos_usuario WHERE id_usuario = ? AND favorito = 1");
        $consulta->execute([$idUsuario]);
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        // Retorna um array com os IDs dos livros favoritados
        return array_column($resultado, 'id_livro');
    }

    public function buscaStatusFavorito($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $consulta = $this->conexao->prepare("SELECT id, status FROM livros WHERE id IN ($placeholders)");
        $consulta->execute($ids);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaPendentesPorUsuario($idUsuario)
    {
        $consulta = $this->conexao->prepare("
        SELECT r.id_livro, r.data_devolucao, l.titulo
        FROM reservas r
        JOIN livros l ON r.id_livro = l.id
        WHERE r.id_usuario = ?
          AND r.devolvido = 0
          AND r.data_devolucao < CURDATE()
    ");
        $consulta->execute([$idUsuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaIndicacoesPorUsuario($idUsuario)
    {
        $consulta = $this->conexao->prepare("SELECT id, titulo, autor FROM indicacoes WHERE id_usuario = ?");
        $consulta->execute([$idUsuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastraIndicacao($idUsuario, $titulo, $autor)
    {
        $consulta = $this->conexao->prepare("INSERT INTO indicacoes (id_usuario, titulo, autor) VALUES (?, ?, ?)");
        if ($consulta->execute([$idUsuario, $titulo, $autor])) {
            return "Indicação criada com sucesso!";
        } else {
            return "Erro ao criar indicação.";
        }
    }

    public function excluiIndicacao($idIndicacao)
    {
        $consulta = $this->conexao->prepare("DELETE FROM indicacoes WHERE id = ?");
        $consulta->execute([$idIndicacao]);
    }

    public function buscaIndicacoesAdicionadas($titulo, $autor)
    {
        $consulta = $this->conexao->prepare(query: "SELECT id FROM livros WHERE titulo = ? AND autor = ?");
        $consulta->execute([$titulo, $autor]);
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($resultado && isset($resultado['id'])) {
            return $resultado['id'];
        }
        return null;
    }

    public function solicitaReserva($idUsuario, $idLivro)
    {
        try {
            $consulta = $this->conexao->prepare("INSERT INTO solicitacoes (id_usuario, id_livro, solicitacao) VALUES (?, ?, 'Reserva')");
            $consulta->execute([$idUsuario, $idLivro]);
            return 1;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function solicitaDevolucao($idUsuario, $idLivro)
    {
        try {
            $consulta = $this->conexao->prepare("INSERT INTO solicitacoes (id_usuario, id_livro, solicitacao) VALUES (?, ?, 'Devolução')");
            $consulta->execute([$idUsuario, $idLivro]);
            return 0;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function verificaDisponibilidadeLivro($id)
    {
        $consulta = $this->conexao->prepare("SELECT status FROM livros WHERE id = ?");
        $consulta->execute([$id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function verificaReservaExistente($id, $idUsuario)
    {
        $consulta = $this->conexao->prepare("SELECT id FROM reservas WHERE id_livro = ? and id_usuario = ? and devolvido = 0");
        $consulta->execute([$id, $idUsuario]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaDataDevolucao($reserva)
    {
        $consulta = $this->conexao->prepare("SELECT data_devolucao FROM reservas WHERE id = ?");
        $consulta->execute([$reserva]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscaSolicitacoesPorUsuario($idUsuario)
    {
        $consulta = $this->conexao->prepare("SELECT s.*, lgn.usuario AS usuario_nome, lv.titulo AS livro_titulo
        FROM solicitacoes s
        JOIN login lgn ON s.id_usuario = lgn.id
        JOIN livros lv ON s.id_livro = lv.id
        WHERE s.id_usuario = ?
        ORDER BY s.id DESC
    ");
        $consulta->execute([$idUsuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscaHistorico($idUsuario)
    {
        $consulta = $this->conexao->prepare("SELECT h.*, l.titulo AS livro_titulo FROM historico h JOIN livros l ON h.id_livro = l.id WHERE h.id_usuario = ? ORDER BY h.id DESC;");
        $consulta->execute([$idUsuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criaComentario($idLivro, $idUsuario, $comentario)
    {
        $consulta = $this->conexao->prepare("INSERT INTO comentarios (id_livro, id_usuario, comentario) VALUES (?, ?, ?)");
        $consulta->execute([$idLivro, $idUsuario, $comentario]);
        return $consulta;
    }

    public function buscaComentariosPorLivro($idLivro)
    {
        $consulta = $this->conexao->prepare("SELECT c.id, c.id_usuario, l.usuario, c.comentario FROM comentarios c JOIN login l ON c.id_usuario = l.id WHERE c.id_livro = ? ORDER BY c.id DESC");
        $consulta->execute([$idLivro]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluiComentario($id)
    {
        $consulta = $this->conexao->prepare("DELETE FROM comentarios WHERE id = ?");
        return $consulta->execute([$id]);
    }

    
    public function buscaLivrosGuiaTodos($limite, $offset, $categoria = '', $pesquisa = '')
    {
        $sql = "SELECT livros.*, categorias.categoria AS nome_categoria
            FROM livros
            LEFT JOIN categorias ON livros.id_categoria = categorias.id
            WHERE 1=1";

        $params = [];

        if (!empty($categoria)) {
            $sql .= " AND livros.id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND livros.titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $sql .= " ORDER BY (livros.status = 0) DESC, livros.id DESC
              LIMIT ? OFFSET ?";

        $params[] = $limite;
        $params[] = $offset;

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function qtdLivrosGuiaTodos($categoria = '', $pesquisa = '')
    {
        $sql = "SELECT COUNT(*) FROM livros WHERE 1=1";
        $params = [];

        if (!empty($categoria)) {
            $sql .= " AND id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchColumn();
    }



    public function buscaLivrosGuiaDisponiveis($limite, $offset, $categoria = '', $pesquisa = '')
    {
        $sql = "SELECT livros.*, categorias.categoria AS nome_categoria
            FROM livros
            LEFT JOIN categorias ON livros.id_categoria = categorias.id
            WHERE livros.status = 0";

        $params = [];

        if (!empty($categoria)) {
            $sql .= " AND livros.id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND livros.titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $sql .= " ORDER BY livros.id DESC
              LIMIT ? OFFSET ?";

        $params[] = $limite;
        $params[] = $offset;

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function qtdLivrosGuiaDisponiveis($categoria = '', $pesquisa = '')
    {
        $sql = "SELECT COUNT(*) FROM livros WHERE status = 0";
        $params = [];

        if (!empty($categoria)) {
            $sql .= " AND id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchColumn();
    }


    public function buscaLivrosGuiaRetirados($idUsuario, $limite, $offset, $categoria = '', $pesquisa = '')
    {
        $sql = "SELECT l.*, c.categoria AS nome_categoria
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            LEFT JOIN categorias c ON l.id_categoria = c.id
            WHERE l.status = 1 AND r.id_usuario = ? AND r.devolvido = 0";

        $params = [$idUsuario];

        if (!empty($categoria)) {
            $sql .= " AND l.id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND l.titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $sql .= " ORDER BY l.id DESC LIMIT ? OFFSET ?";
        $params[] = $limite;
        $params[] = $offset;

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public function qtdLivrosGuiaRetirados($idUsuario, $categoria = '', $pesquisa = '')
    {
        $sql = "SELECT COUNT(*) 
            FROM livros l
            JOIN reservas r ON r.id_livro = l.id
            WHERE l.status = 1 AND r.id_usuario = ? AND r.devolvido = 0";

        $params = [$idUsuario];

        if (!empty($categoria)) {
            $sql .= " AND l.id_categoria = ?";
            $params[] = $categoria;
        }

        if (!empty($pesquisa)) {
            $sql .= " AND l.titulo LIKE ?";
            $params[] = '%' . $pesquisa . '%';
        }

        $consulta = $this->conexao->prepare($sql);

        foreach ($params as $i => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $consulta->bindValue($i + 1, $valor, $tipo);
        }

        $consulta->execute();
        return $consulta->fetchColumn();
    }

    public function buscaNotificacoes($idUsuario){
        $consulta = $this->conexao->prepare("SELECT * FROM notificacoes WHERE id_usuario = ?");
        $consulta->execute([$idUsuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluiNotificacao($id)
    {
        $consulta = $this->conexao->prepare("DELETE FROM notificacoes WHERE id = ?");
        $consulta->execute([$id]);
    }


}
?>