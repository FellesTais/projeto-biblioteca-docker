<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/estilo_adm.css">
    <link rel="stylesheet" href="assets/estilo_geral.css">
    <title>Tela Inicial</title>
</head>

<body style="font-family: 'Comfortaa', sans-serif;">

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand">
                <img src="/assets/img/icone.jpg" alt="Logo" class="rounded-circle"
                    style="height: 40px; width: 40px; object-fit: cover;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" onclick="abrirSolicitacoes()">Solicitacoes</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Cadastrar
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" onclick="abrirCadastroLivros()">Livros</a></li>
                                <li><a class="dropdown-item" onclick="abrirCadastroCategorias()">Categorias</a></li>
                        </li>
                    </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Indicadores
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" onclick="abrirIndicacoesAdm()">Indicações</a></li>
                            <li><a class="dropdown-item" onclick="abrirLivrosEmAtraso()">Livros em atraso</a></li>
                            <li><a class="dropdown-item" onclick="abrirUsuariosEmAtraso()">Usuários em Atraso</a></li>
                    </li>
                    </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Buscar
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" onclick="abrirHistoricoPorUsuario()">Histórico por usuário</a>
                            </li>
                            <li><a class="dropdown-item" onclick="abrirHistoricoPorLivro()">Histórico por livro</a>
                            </li>
                            <li><a class="dropdown-item" onclick="abrirPendenciasPorUsuarioAdm()">Pendências por
                                    usuário</a>
                            </li>
                            <li><a class="dropdown-item" onclick="abrirPendenciasPorLivroAdm()">Pendências por livro</a>
                            </li>
                    </li>
                    </ul>
                    </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Opções
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" onclick="encerrarSessao()">Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <?php
    function chunkArray($array, $chunkSize)
    {
        $chunks = [];
        for ($i = 0; $i < count($array); $i += $chunkSize) {
            $chunks[] = array_slice($array, $i, $chunkSize);
        }
        return $chunks;
    }
    $livros_chunks = chunkArray($livrosCarrossel, 3);

    ?>
    <section class="componentes">
        <div>
            <h1>Em alta</h1>
        </div>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <?php foreach ($livros_chunks as $index => $chunk): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row justify-content-center">
                            <?php foreach ($chunk as $livro): ?>
                                <div class="col-md-3">
                                    <div class="card card-wrapper" style="width: 18rem;">
                                        <div class="conteudo">
                                            <div class="conteudo-front">
                                                <img src="<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top"
                                                    alt="Capa do livro <?= htmlspecialchars($livro['titulo']) ?>">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
                                                </div>
                                            </div>
                                            <div class="conteudo-back">
                                                <span
                                                    class="badge bg-secondary"><?= htmlspecialchars($livro['nome_categoria']) ?></span>
                                                <div class="card-body carrossel-sinopse">
                                                    <?= htmlspecialchars($livro['sinopse']) ?>
                                                </div>
                                                <div class="carrossel-botao">
                                                    <button class="btn btn-secondary" onclick="abrirVisualizacaoLivroAdm(this)"
                                                        data-id="<?= $livro['id'] ?>"
                                                        data-titulo="<?= htmlspecialchars($livro['titulo']) ?>">
                                                        Ver mais
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section class="mt-5">
        <div class="container" style="max-width: 600px;">
            <div class="row g-2 align-items-center justify-content-center">
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-funnel-fill me-2"></i>Categoria
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item categoria-item" href="#" data-id="">
                                    Todas
                                </a>
                            </li>
                            <?php foreach ($categorias as $categoria): ?>
                                <li>
                                    <a class="dropdown-item categoria-item" href="#" data-id="<?= $categoria['id'] ?>">
                                        <?= htmlspecialchars($categoria['categoria']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pesquisar...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tabs-section mt-4">
        <div class="container">
            <ul class="nav nav-tabs" id="tabsLivros">
                <li class="nav-item">
                    <a class="nav-link active" data-tab="todos">Todos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="disponiveis">Disponíveis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="retirados">Retirados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-tab="atrasados">Atrasados</a>
                </li>
            </ul>
            <div id="livrosContainer" class="mt-4 container livrosContainer">
                <div class="row g-4">
                    <?php foreach ($livros as $livro): ?>
                        <div class="col-sm-6 col-lg-3">
                            <a onclick="abrirVisualizacaoLivroAdm(this)" data-id="<?= $livro['id'] ?>"
                                data-titulo="<?= htmlspecialchars($livro['titulo']) ?>">
                                <div class="card rounded-4 shadow-sm h-100 d-flex flex-column exibicao-livro-card">
                                    <div class="exibicao-livro-titulo">
                                        <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($livro['titulo']) ?></h5>
                                    </div>
                                    <img src="<?= !empty($livro['capa']) ? htmlspecialchars($livro['capa']) : 'https://via.placeholder.com/250x150' ?>"
                                        class="card-img-top rounded-3 mt-1 mb-0 exibicao-livro-capa" alt="Imagem do livro">
                                    <div class="text-center mt-1">
                                        <p class="mb-0 text-muted small">
                                            <strong><?= htmlspecialchars($livro['autor']) ?></strong>
                                        </p>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($livro['categoria']) ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Contato</h5>
                    <p class="mb-1">R. Frederico Mentz, 526 – Hamburgo Velho, Novo Hamburgo – RS</p>
                    <p class="mb-1">Telefone: (51) 3594-3022</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Redes Sociais</h5>
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="https://linklist.ai/hrrUV0xzZK" class="text-white fs-4" target="_blank"
                            aria-label="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://www.instagram.com/ienhoficial?igsh=ZHd6bzQwNDg5cjQ4" class="text-white fs-4"
                            target="_blank" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/share/16gDk7sbNq/?mibextid=wwXIfr" class="text-white fs-4"
                            target="_blank" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php include 'modal_solicitacoes.php'; ?>
    <?php include 'modal_cadastro_categorias.php'; ?>
    <?php include 'modal_cadastro_livros.php'; ?>
    <?php include 'modal_visualizacao_livro_adm.php'; ?>
    <?php include 'modal_historico_por_usuario.php'; ?>
    <?php include 'modal_historico_por_livro.php'; ?>
    <?php include 'modal_pendencias_por_usuario.php'; ?>
    <?php include 'modal_pendencias_por_livro.php'; ?>
    <?php include 'modal_indicacoes_adm.php'; ?>
    <?php include 'modal_livros_em_atraso.php'; ?>
    <?php include 'modal_usuarios_em_atraso.php'; ?>
    <?php include 'modal_modificacao_livro.php'; ?>
    <?php include __DIR__ . '/../confirmacao_encerrar_sessao.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3/build/global/luxon.min.js"></script>
    <script src="assets/main.js"></script>
    <script src="assets/adm.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            configurarGuiasAdm('tabsLivros');
        });
    </script>

</body>

</html>