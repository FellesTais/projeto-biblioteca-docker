<?php
require_once __DIR__ . '/config/config.php';
$conexao = Conexao::conectar();

require_once __DIR__ . '/app/controllers/LoginController.php';
require_once __DIR__ . '/app/controllers/InicialController.php';
require_once __DIR__ . '/app/controllers/AdmController.php';

$view = $_GET['view'] ?? null;
$action = $_GET["action"] ?? null;

$controllerLogin = new LoginController();
$controllerInicial = new InicialController();
$controllerAdm = new AdmController();

if (is_null($view) && is_null($action)) {
    $controllerLogin->exibeLogin();
} else {
}

if ($view === 'login') {
    $controllerLogin->exibeLogin();
} else if ($view === 'redefinirSenha') {
    $controllerLogin->exibeTelaRedefinirSenha();
} else if ($view === 'exibeLivro' && isset($_GET['id'])) {
    $controllerInicial->visualizaLivro($_GET['id']);
} else if ($view === 'exibeLivroAdm' && isset($_GET['id'])) {
    $controllerAdm->visualizaLivroAdm($_GET['id']);
} else if ($view === 'encerrarSessao') {
    $controllerInicial->encerraSessao();
} else {
}

if ($action === 'validarLogin') {
    $controllerLogin->validaLogin();
} else if ($action === 'cadastrarUsuario') {
    $controllerLogin->cadastraUsuario();
} else if ($action === 'redefinirSenha') {
    $controllerLogin->redefineSenha();
} else if ($action === 'telaInicial') {
    $controllerInicial->exibeInicial();
} else if ($action === 'configurarGuiasLivros') {
    $controllerInicial->configuraGuiasLivros();
    echo "ACTION: " . $action;
exit;
} else if ($action === 'favoritarLivro') {
    $controllerInicial->favoritaLivro();
} else if ($action === 'buscarFavoritos') {
    $controllerInicial->buscaFavoritos();
} else if ($action === 'buscarPendenciasPorUsuario') {
    $controllerInicial->buscaPendenciasPorUsuario();
} else if ($action === 'verificaFavorito') {
    $controllerInicial->verificaFavorito();
} else if ($action === 'buscarIndicacoes') {
    $controllerInicial->buscaIndicacoes();
} else if ($action === 'indicarLivro') {
    $controllerInicial->indicaLivro();
} else if ($action === 'excluirIndicacao' && isset($_GET['id'])) {
    $controllerInicial->excluiIndicacao($_GET['id']);
} else if ($action === 'solicitarReservaLivro' && isset($_GET['id'])) {
    $controllerInicial->solicitaReservaLivro($_GET['id']);
} else if ($action === 'solicitarDevolucaoLivro' && isset($_GET['id'])) {
    $controllerInicial->solicitaDevolucaoLivro($_GET['id']);
} else if ($action === 'verificarDisponibilidadeLivro' && isset($_GET['id'])) {
    $controllerInicial->verificaDisponibilidadeLivro($_GET['id']);
} else if ($action === 'buscarSolicitacoesPorUsuario') {
    $controllerInicial->buscaSolicitacoesPorUsuario();
} else if ($action === 'buscarHistorico') {
    $controllerInicial->buscaHistorico();
} else if ($action === 'enviarComentario') {
    $controllerInicial->enviaComentario();
} else if ($action === 'buscarComentarios' && isset($_GET['idLivro'])) {
    $controllerInicial->buscaComentarios($_GET['idLivro']);
} else if ($action === 'excluirComentario' && isset($_GET['id'])) {
    $controllerInicial->excluiComentario($_GET['id']);
} else if ($action === 'buscarNotificacoes') {
    $controllerInicial->buscaNotificacoes();
} else if ($action === 'excluirNotificacao' && isset($_GET['id'])) {
    $controllerInicial->excluiNotificacao($_GET['id']);
} else if ($action === 'configurarGuiasLivrosAdm') {
    $controllerAdm->configuraGuiasLivrosAdm();
} else if ($action === 'telaInicialAdm') {
    $controllerAdm->exibeInicialAdm();
} else if ($action === 'cadastrarLivro') {
    $controllerAdm->cadastraLivro();
} else if ($action === 'buscarSolicitacoes') {
    $controllerAdm->buscaSolicitacoes();
} else if ($action === 'confirmarSolicitacao' && isset($_GET['id'])) {
    $controllerAdm->confirmaSolicitacao($_GET['id']);
} else if ($action === 'cancelarSolicitacao' && isset($_GET['id'])) {
    $controllerAdm->removeSolicitacao($_GET['id']);
} else if ($action === 'cadastrarCategoria' && isset($_GET['novaCategoria'])) {
    $controllerAdm->cadastraCategoria($_GET['novaCategoria']);
} else if ($action === 'buscarCategorias') {
    $controllerAdm->buscaCategorias();
} else if ($action === 'buscarInformacoes' && isset($_GET['id'])) {
    $controllerAdm->buscaInformacoes($_GET['id']);
} else if ($action === 'excluirLivro' && isset($_GET['id'])) {
    $controllerAdm->excluiLivro($_GET['id']);
} else if ($action === 'buscarHistoricoPorUsuario' && isset($_GET['usuario'])) {
    $controllerAdm->buscaHistoricoPorUsuario($_GET['usuario']);
} else if ($action === 'buscarHistoricoPorLivro' && isset($_GET['livro'])) {
    $controllerAdm->buscaHistoricoPorLivro($_GET['livro']);
} else if ($action === 'buscarPendenciasPorUsuarioAdm' && isset($_GET['usuario'])) {
    $controllerAdm->buscaPendenciasPorUsuario($_GET['usuario']);
} else if ($action === 'buscarPendenciasPorLivroAdm' && isset($_GET['livro'])) {
    $controllerAdm->buscaPendenciasPorLivro($_GET['livro']);
} else if ($action === 'buscarIndicacoesAdm') {
    $controllerAdm->buscaIndicacoesAdm();
} else if ($action === 'buscarLivrosEmAtraso') {
    $controllerAdm->buscaLivrosEmAtraso();
} else if ($action === 'buscarUsuariosEmAtraso') {
    $controllerAdm->buscaUsuariosEmAtraso();
} else if ($action === 'buscarDadosLivro' && isset($_GET['id'])) {
    $controllerAdm->buscaDadosLivro($_GET['id']);
} else if ($action === 'modificarLivro') {
    $controllerAdm->modificaLivro();
} else {
}
?>