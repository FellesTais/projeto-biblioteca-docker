<?php

require_once __DIR__ . '/../models/AdmModel.php';
require_once __DIR__ . '/../models/InicialModel.php';

class AdmController
{

    private $AdmModel;
    private $InicialModel;
    public function __construct()
    {
        //Define a conexão como global
        global $conexao;

        $this->AdmModel = new AdmModel($conexao);
        $this->InicialModel = new InicialModel($conexao);
    }

    public function visualizaLivroAdm($livroId)
    {
        $livro = $this->InicialModel->buscaLivroPorId($livroId);
        include __DIR__ . '/../views/inicialadm/visualizacao_livro_dinamico_adm.php';

    }

    public function exibeInicialAdm()
    {
        $livrosCarrossel = $this->InicialModel->buscaLivrosEmAlta(6);
        /*$livros = $this->InicialModel->buscaLivro();*/
        $categorias = $this->AdmModel->buscaCategorias();
        include __DIR__ . '/../views/inicialadm/inicial_adm.php';
    }

    public function cadastraLivro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $sinopse = $_POST['sinopse'] ?? '';
            $categoria = $_POST['categoria'] ?? '';
            $data_lancamento = $_POST['data_lancamento'] ?? '';

            $capa = null;
            if (!empty($_FILES['capa']['tmp_name'])) {
                $upload_dir = realpath(__DIR__ . '/../..') . '/assets/img/';
                $capa_nome = basename($_FILES['capa']['name']);
                $capa_nome_unico = $capa_nome;
                $capa_caminho = $upload_dir . $capa_nome_unico;

                if (move_uploaded_file($_FILES['capa']['tmp_name'], $capa_caminho)) {
                    $capa = 'assets/img/' . $capa_nome_unico;
                    ;
                } else {
                    echo "Erro ao fazer upload da imagem.";
                    exit;
                }
            }

            try {
                $livroExistente = $this->AdmModel->verificaExistenciaLivro($titulo, $autor);

                if ($livroExistente) {
                    echo json_encode(['success' => false, 'mensagem' => 'Livro já cadastrado.']);
                    exit;
                } else {
                    $resultado = $this->AdmModel->cadastraLivro($titulo, $autor, $sinopse, $categoria, $data_lancamento, $capa);

                    if ($resultado) {
                        echo json_encode(['success' => true, 'mensagem' => 'Livro cadastrado com sucesso.']);
                    } else {
                        echo json_encode(['success' => false, 'mensagem' => 'Erro ao cadastrar livro.']);
                    }
                    exit;
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'mensagem' => 'Erro ao cadastrar livro.']);
                exit;
            }
        }
    }

    public function configuraGuiasLivrosAdm()
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
            $livros = $this->AdmModel->buscaLivrosGuiaRetirados($limite, $offset, $categoria, $pesquisa);
            $total = $this->AdmModel->qtdLivrosGuiaRetirados($categoria, $pesquisa);
        } elseif ($guia === 'atrasados') {
            $livros = $this->AdmModel->buscaLivrosGuiaAtrasados($limite, $offset, $categoria, $pesquisa);
            $total = $this->AdmModel->qtdLivrosGuiaAtrasados($categoria, $pesquisa);
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

    public function buscaSolicitacoes()
    {
        $solicitacoes = $this->AdmModel->buscaSolicitacoes();
        echo json_encode($solicitacoes);
        exit;
    }

    public function removeSolicitacao($idSolicitacao)
    {
        $this->AdmModel->excluiSolicitacao($idSolicitacao);
        echo json_encode([
            'success' => true,
            'mensagem' => 'Solicitação cancelada.'
        ]);
        exit;
    }

    public function confirmaSolicitacao($idSolicitacao)
    {
        $idLivro = $this->AdmModel->buscaLivroPorSolicitacao($idSolicitacao);
        $idUsuario = $this->AdmModel->buscaUsuarioPorSolicitacao($idSolicitacao);
        $tipoSolicitacao = $this->AdmModel->buscaTipoSolicitacao($idSolicitacao);
        $idReserva = $this->InicialModel->verificaReservaExistente($idLivro, $idUsuario);

        if ($tipoSolicitacao == 'Reserva') {
            $statusLivro = 1;
            $this->AdmModel->cadastraReserva($idLivro, $idUsuario);
            $this->AdmModel->atualizaStatusLivro($idLivro, $statusLivro);
            $this->AdmModel->atualizaHistoricoRetirada($idLivro, $idUsuario);
            $this->AdmModel->excluiSolicitacaoEmLote($idLivro);
            $this->AdmModel->criaNotificacaoRetirada($idUsuario, $idLivro);
        } else if ($tipoSolicitacao == 'Devolução') {
            $statusLivro = 0;
            $this->AdmModel->devolveLivro($idReserva['id']);
            $this->AdmModel->atualizaStatusLivro($idLivro, $statusLivro);
            $this->AdmModel->atualizaHistoricoDevolucao($idLivro, $idUsuario);
            $this->AdmModel->excluiSolicitacao($idSolicitacao);
            $this->AdmModel->criaNotificacaoDevolucao( $idUsuario, $idLivro);
        } else {

        }

        echo json_encode([
            'success' => true,
            'mensagem' => 'Solicitação confirmada.'
        ]);
        exit;
    }

    public function cadastraCategoria($novaCategoria)
    {
        $novaCategoria = trim($novaCategoria);

        try {
            $categoriaExistente = $this->AdmModel->buscaCategoriaExistente($novaCategoria);

            if ($categoriaExistente) {
                echo json_encode([
                    'success' => false,
                    'mensagem' => 'Categoria já cadastrada.'
                ]);
            } else {
                $this->AdmModel->criaCategoria($novaCategoria);
                echo json_encode([
                    'success' => true,
                    'mensagem' => 'Categoria cadastrada com sucesso.'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'mensagem' => 'Erro ao cadastrar categoria.'
            ]);
        }
        exit;
    }

    public function buscaCategorias()
    {
        $categorias = $this->AdmModel->buscaCategorias();

        header('Content-Type: application/json');
        echo json_encode($categorias);
        exit;
    }

    public function buscaInformacoes($id)
    {
        $idLivro = $_GET['id'];

        $informacoes = $this->AdmModel->buscaInformacoesLivroRetirado($idLivro);

        if ($informacoes) {
            echo json_encode([
                'usuario' => $informacoes['usuario'],
                'data_retirada' => date('d/m/Y', strtotime($informacoes['data_retirada'])),
                'data_devolucao' => date('d/m/Y', strtotime($informacoes['data_devolucao']))
            ]);
        } else {
            echo json_encode([
                'usuario' => 'Não encontrado',
                'data_retirada' => 'Não encontrado',
                'data_devolucao' => 'Não encontrado'
            ]);
        }

        exit;

    }

    public function excluiLivro($id)
    {
        $resultado = $this->AdmModel->excluiLivro($id);

        echo json_encode([
            'success' => true,
            'mensagem' => 'Livro excluído com sucesso.'
        ]);
        exit;
    }

    public function buscaHistoricoPorUsuario($usuario)
    {
        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        if (empty($idUsuario)) {
            echo json_encode([]);
            exit;
        }

        $resultado = $this->AdmModel->buscaHistoricoPorUsuario($idUsuario);

        if (!$resultado || !is_array($resultado)) {
            echo json_encode([]);
            exit;
        }

        echo json_encode($resultado);
        exit;
    }

    public function buscaHistoricoPorLivro($livro)
    {
        $idLivro = $this->AdmModel->buscaIdPorLivro($livro);
        if (empty($idLivro)) {
            echo json_encode([]);
            exit;
        }

        $resultado = $this->AdmModel->buscaHistoricoPorLivro($idLivro);

        if (!$resultado || !is_array($resultado)) {
            echo json_encode([]);
            exit;
        }

        echo json_encode($resultado);
        exit;
    }

    public function buscaPendenciasPorUsuario($usuario)
    {

        $idUsuario = $this->InicialModel->buscaIdPorUsuario($usuario);
        if (empty($idUsuario)) {
            echo json_encode([]);
            exit;
        }

        $resultado = $this->AdmModel->buscaPendenciaPorUsuario($idUsuario);

        if (!$resultado || !is_array($resultado)) {
            echo json_encode([]);
            exit;
        }

        echo json_encode($resultado);
        exit;
    }


    public function buscaPendenciasPorLivro($livro)
    {
        $idLivro = $this->AdmModel->buscaIdPorLivro($livro);
        if (empty($idLivro)) {
            echo json_encode([]);
            exit;
        }

        $resultado = $this->AdmModel->buscaPendenciaPorLivro($idLivro);

        if (!$resultado || !is_array($resultado)) {
            echo json_encode([]);
            exit;
        }

        echo json_encode($resultado);
        exit;
    }

    public function buscaIndicacoesAdm()
    {
        $indicacoes = $this->AdmModel->buscaIndicacoes();
        echo json_encode($indicacoes);
        exit;
    }

    public function buscaLivrosEmAtraso()
    {
        $livros = $this->AdmModel->buscaLivrosEmAtraso();
        echo json_encode($livros);
        exit;
    }

    public function buscaUsuariosEmAtraso()
    {
        $livros = $this->AdmModel->buscaUsuariosEmAtraso();
        echo json_encode($livros);
        exit;
    }

    public function buscaDadosLivro($id)
    {
        $dados = $this->AdmModel->buscaDadosLivro($id);
        echo json_encode($dados);
        exit;
    }

    public function modificaLivro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            $titulo = $_POST['titulo'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $sinopse = $_POST['sinopse'] ?? '';
            $categoria = $_POST['categoria'] ?? '';
            $data_lancamento = $_POST['data_lancamento'] ?? '';

            $capa = null;
            if (!empty($_FILES['capa']['tmp_name'])) {
                $upload_dir = realpath(__DIR__ . '/../..') . '/assets/img/';
                $capa_nome = basename($_FILES['capa']['name']);
                $ext = pathinfo($capa_nome, PATHINFO_EXTENSION);
                $capa_nome_unico = uniqid('capa_', true) . '.' . $ext;
                $capa_caminho = $upload_dir . $capa_nome_unico;

                if (move_uploaded_file($_FILES['capa']['tmp_name'], $capa_caminho)) {
                    $capa = 'assets/img/' . $capa_nome_unico;
                } else {
                    echo json_encode(['success' => false, 'mensagem' => 'Erro ao fazer upload da imagem.']);
                    exit;
                }
            }

            $resultado = $this->AdmModel->modificaLivro($titulo, $autor, $sinopse, $categoria, $data_lancamento, $capa, $id);

            echo json_encode([
                'success' => $resultado,
                'mensagem' => $resultado ? 'Livro modificado com sucesso.' : 'Erro ao modificar livro.'
            ]);
            exit;
        }
    }


}

?>