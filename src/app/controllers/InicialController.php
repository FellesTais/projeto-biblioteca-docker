<?php

require_once __DIR__ . '/../models/InicialModel.php';
require_once __DIR__ . '/../models/AdmModel.php';

class InicialController
{

    private $InicialModel;
    private $AdmModel;
    public function __construct()
    {
        $this->InicialModel = new InicialModel();
        $this->AdmModel = new AdmModel();
    }

    public function exibeInicial()
    {
        $livrosCarrossel = $this->InicialModel->buscaLivrosEmAlta(6);
        $livros = $this->InicialModel->buscaLivro();
        $categorias = $this->AdmModel->buscaCategorias();
        include __DIR__ . '/../views/inicial/inicial.php';
    }

    public function encerraSessao()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

    public function visualizaLivro($livroId)
    {

        session_start();
        $usuario = $_SESSION['usuario'] ?? null;
        $idUsuario = null;
        $favoritosUsuario = [];

        $favorito = 0;

        if ($usuario) {
            $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
            if ($idUsuario) {
                $favorito = $this->InicialModel->verificaFavorito($idUsuario, $livroId);
            }
        }

        $livro = $this->InicialModel->buscaLivroPorId($livroId);
        include __DIR__ . '/../views/inicial/visualizacao_livro_dinamico.php';

    }

    public function configuraGuiasLivros()
    {
        $guia = $_GET['guia'] ?? 'todos';
        $pagina = intval($_GET['pagina'] ?? 1);
        $limite = 12;
        $offset = ($pagina - 1) * $limite;
        $categoria = $_GET['categoria'] ?? '';
        $pesquisa = $_GET['pesquisa'] ?? '';

        if ($guia === 'todos') {
            $livros = $this->InicialModel->buscaLivrosGuiaTodos($limite, $offset, $categoria, $pesquisa);
            $total = $this->InicialModel->qtdLivrosGuiaTodos($categoria, $pesquisa);
        } elseif ($guia === 'disponiveis') {
            $livros = $this->InicialModel->buscaLivrosGuiaDisponiveis($limite, $offset, $categoria, $pesquisa);
            $total = $this->InicialModel->qtdLivrosGuiaDisponiveis($categoria, $pesquisa);
        } elseif ($guia === 'retirados') {
            session_start();
            $usuario = $_SESSION['usuario'];
            $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
            $livros = $this->InicialModel->buscaLivrosGuiaRetirados($idUsuario, $limite, $offset, $categoria, $pesquisa);
            $total = $this->InicialModel->qtdLivrosGuiaRetirados($idUsuario, $categoria, $pesquisa);
        } else {
            echo json_encode([]);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'livros' => $livros,
            'limite' => $limite,
            'pagina' => $pagina,
            'total' => $total
        ]);
        exit;
    }
    

    public function buscaIdPorUsuario($usuario)
    {
        return $this->InicialModel->buscaIdPorUsuario($usuario);
    }

    public function favoritaLivro()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            echo 'Usuário não logado.';
            return;
        }

        if (!isset($_POST['id'])) {
            echo 'ID do livro não recebido.';
            return;
        }

        $usuario = $_SESSION['usuario'];
        $livroId = $_POST['id'];

        // Aqui deve buscar o id do usuário no banco (pela tabela login) a partir do nome de usuário
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        if (!$idUsuario) {
            echo 'Usuário inválido.';
            return;
        }

        // Agora, chama o model para salvar o favorito (insert/update)
        $result = $this->InicialModel->favoritaLivro($idUsuario, $livroId);
    }

    public function verificaFavorito()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['favorito' => 0]);
            return;
        }

        if (!isset($_GET['id'])) {
            echo json_encode(['favorito' => 0]);
            return;
        }

        $usuario = $_SESSION['usuario'];
        $livroId = (int) $_GET['id'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        if (!$idUsuario) {
            echo json_encode(['favorito' => 0]);
            return;
        }

        $favorito = $this->InicialModel->verificaFavorito($idUsuario, $livroId);

        echo json_encode(['favorito' => (int) $favorito]);
    }

    public function buscaFavoritos()
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        $favoritos = $this->InicialModel->buscaFavoritosPorUsuario($idUsuario);
        if (empty($favoritos)) {
            echo json_encode([]);
            return;
        }

        $livrosFavoritos = $this->InicialModel->buscaLivrosPorId($favoritos);
        $statusFavoritos = $this->InicialModel->buscaStatusFavorito($favoritos);

        $statusMap = [];
        foreach ($statusFavoritos as $status) {
            $statusMap[$status['id']] = $status['status'];
        }

        foreach ($livrosFavoritos as &$livro) {
            $livro['disponivel'] = isset($statusMap[$livro['id']]) && ($statusMap[$livro['id']] == 0);
        }

        header('Content-Type: application/json');
        echo json_encode($livrosFavoritos);
    }

    public function buscaPendenciasPorUsuario()
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $pendentes = $this->InicialModel->buscaPendentesPorUsuario($idUsuario);

        echo json_encode($pendentes);
        exit;
    }

    public function buscaIndicacoes()
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        if (!$idUsuario) {
            echo json_encode([]);
            return;
        }

        $indicacoes = $this->InicialModel->buscaIndicacoesPorUsuario($idUsuario);
        if (empty($indicacoes)) {
            echo json_encode([]);
            return;
        }

        $resultado = [];

        foreach ($indicacoes as $item) {
            $registro = [
                'titulo' => $item['titulo'],
                'autor' => $item['autor'],
                'id_indicacao' => $item['id'],
            ];

            // Verifica se já existe livro com esse título e autor
            $idLivro = $this->InicialModel->buscaIndicacoesAdicionadas($item['titulo'], $item['autor']);
            if ($idLivro) {
                $registro['existente'] = true;
                $registro['id'] = $idLivro;
            } else {
                $registro['existente'] = false;
            }

            $resultado[] = $registro;
        }

        header('Content-Type: application/json');
        echo json_encode($resultado);
    }


    public function indicaLivro()
    {
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: ../index.php?view=login&erro=nao-logado");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        if (!$idUsuario) {
            // Redireciona com erro de ID
            header("Location: ../index.php?view=inicial&erro=id-usuario-nao-encontrado");
            exit;
        }

        if (isset($_POST['tituloIndicacao'], $_POST['autorIndicacao'])) {
            $titulo = trim($_POST['tituloIndicacao']);
            $autor = trim($_POST['autorIndicacao']);

            $resultado = $this->InicialModel->cadastraIndicacao($idUsuario, $titulo, $autor);
        }

        if ($resultado) {
            header("Location: ../index.php?action=telaInicial&status=sucesso&mensagem=" . urlencode($resultado));
        } else {
            header("Location: ../index.php?action=telaInicial&status=erro&mensagem=" . urlencode($resultado));
        }
    }

    public function excluiIndicacao($idIdentificacao)
    {
        $this->InicialModel->excluiIndicacao($idIdentificacao);
    }

    public function solicitaReservaLivro($idLivro)
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        $resultado = $this->InicialModel->solicitaReserva($idUsuario, $idLivro);

        if ($resultado) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Reserva solicitada']);
        } else {
        }
        exit;
    }

    public function solicitaDevolucaoLivro($idLivro)
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);

        $resultado = $this->InicialModel->solicitaDevolucao($idUsuario, $idLivro);

        if ($resultado !== null) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Devolução solicitada']);
        } else {
        }
        exit;
    }

    public function verificaDisponibilidadeLivro($id)
    {
        session_start();

        $usuario = $_SESSION['usuario'];

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $resultado = $this->InicialModel->verificaDisponibilidadeLivro($id);
        $reserva = $this->InicialModel->verificaReservaExistente($id, $idUsuario);
        $devolucao = $reserva ? $this->InicialModel->buscaDataDevolucao($reserva['id']) : null;

        if ($resultado) {
            echo json_encode([
                'statusDisponibilidade' => $resultado['status'],
                'reservaAtiva' => $reserva ? true : false,
                'dataDevolucao' => $devolucao ? $devolucao['data_devolucao'] : null
            ]);
        } else {
        }
        exit;
    }


    public function buscaSolicitacoesPorUsuario()
    {
        session_start();

        $usuario = $_SESSION['usuario'];

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $solicitacoes = $this->InicialModel->buscaSolicitacoesPorUsuario($idUsuario);
        echo json_encode($solicitacoes);
        exit;
    }


    public function buscaHistorico()
    {
        session_start();

        $usuario = $_SESSION['usuario'];

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $historico = $this->InicialModel->buscaHistorico($idUsuario);
        echo json_encode($historico);
        exit;
    }

    public function enviaComentario()
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $comentarioTexto = $_POST['comentario'] ?? '';
        $idLivro = $_POST['idLivro'] ?? null;

        if (!$comentarioTexto || !$idLivro) {
            echo json_encode(['success' => false, 'mensagem' => 'Dados insuficientes.']);
            exit;
        }

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $this->InicialModel->criaComentario($idLivro, $idUsuario, $comentarioTexto);

        echo json_encode(['success' => true]);
        exit;
    }

    public function buscaComentarios($idLivro)
    {
        session_start();

        $usuario = $_SESSION['usuario'];

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $comentarios = $this->InicialModel->buscaComentariosPorLivro($idLivro);

        header('Content-Type: application/json');
        echo json_encode([
            'comentarios' => $comentarios,
            'usuario' => $idUsuario
        ]);
        exit;
    }

    public function excluiComentario($id)
    {
        $resultado = $this->InicialModel->excluiComentario($id);

        header('Content-Type: application/json');
        echo json_encode(['success' => ($resultado === true)]);
        exit;
    }

    public function buscaNotificacoes()
    {
        session_start();

        $usuario = $_SESSION['usuario'];
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        $notificacoes = $this->InicialModel->buscaNotificacoes($idUsuario);

        header('Content-Type: application/json');
        echo json_encode($notificacoes);
        exit;
    }

    public function excluiNotificacao($id)
    {
        $this->InicialModel->excluiNotificacao($id);
    }




}

?>