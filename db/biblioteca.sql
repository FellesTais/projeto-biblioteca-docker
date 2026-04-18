-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/06/2025 às 01:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`) VALUES
(1, 'Terror'),
(2, 'Fantasia'),
(3, 'Drama'),
(4, 'Romance'),
(6, 'Literatura Infantil'),
(7, 'Literatura Juvenil'),
(8, 'Ciências sociais'),
(9, 'Literatura contemporânea'),
(10, 'Literatura brasileira'),
(11, 'Suspense'),
(12, 'Thriller'),
(13, 'Mistério'),
(14, 'Biografia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_livro`, `id_usuario`, `comentario`) VALUES
(58, 25, 4, 'comentario 1'),
(61, 25, 3, 'comentario 4'),
(66, 1, 3, 'Péssimo'),
(67, 1, 8, 'OK'),
(68, 1, 9, 'Muito bom');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos_usuario`
--

CREATE TABLE `favoritos_usuario` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `favoritos_usuario`
--

INSERT INTO `favoritos_usuario` (`id`, `id_usuario`, `id_livro`, `favorito`) VALUES
(2, 4, 4, 1),
(3, 4, 7, 1),
(5, 4, 1, 1),
(6, 4, 2, 1),
(8, 4, 5, 1),
(9, 3, 6, 0),
(10, 4, 6, 1),
(11, 3, 5, 0),
(12, 4, 3, 1),
(13, 3, 2, 1),
(14, 3, 1, 1),
(15, 9, 1, 1),
(16, 9, 7, 1),
(17, 11, 1, 1),
(18, 11, 7, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `acao` varchar(10) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`id`, `id_livro`, `id_usuario`, `acao`, `data`) VALUES
(3, 1, 4, 'Retirada', '2025-06-21'),
(4, 2, 4, 'Retirada', '2025-06-21'),
(5, 2, 4, 'Devolução', '2025-06-21'),
(6, 1, 4, 'Devolução', '2025-06-21'),
(7, 1, 3, 'Retirada', '2025-06-22'),
(8, 2, 4, 'Retirada', '2025-06-22'),
(9, 1, 3, 'Devolução', '2025-06-22'),
(10, 5, 4, 'Retirada', '2025-06-22'),
(11, 5, 4, 'Devolução', '2025-06-22'),
(12, 2, 4, 'Devolução', '2025-06-22'),
(13, 4, 8, 'Retirada', '2025-06-22'),
(14, 3, 8, 'Retirada', '2025-06-22'),
(15, 20, 8, 'Retirada', '2025-06-22'),
(16, 24, 3, 'Retirada', '2025-06-24'),
(17, 6, 8, 'Retirada', '2025-06-24'),
(18, 25, 4, 'Retirada', '2025-06-24'),
(19, 4, 8, 'Devolução', '2025-06-24'),
(20, 10, 4, 'Retirada', '2025-06-24'),
(23, 2, 4, 'Retirada', '2025-06-24'),
(24, 2, 4, 'Devolução', '2025-06-24'),
(25, 7, 3, 'Retirada', '2025-06-24'),
(26, 7, 3, 'Devolução', '2025-06-24'),
(27, 7, 4, 'Retirada', '2025-06-25'),
(28, 22, 4, 'Retirada', '2025-06-25'),
(29, 22, 4, 'Devolução', '2025-06-25'),
(30, 5, 4, 'Retirada', '2025-06-25'),
(31, 5, 4, 'Devolução', '2025-06-25'),
(32, 1, 4, 'Retirada', '2025-06-25'),
(33, 2, 4, 'Retirada', '2025-06-25'),
(34, 1, 4, 'Devolução', '2025-06-25'),
(35, 2, 4, 'Devolução', '2025-06-25'),
(36, 1, 9, 'Retirada', '2025-06-25'),
(37, 4, 8, 'Retirada', '2025-06-25'),
(38, 2, 8, 'Retirada', '2025-06-25'),
(39, 10, 8, 'Retirada', '2025-06-25'),
(40, 7, 8, 'Retirada', '2025-06-25'),
(41, 20, 9, 'Retirada', '2025-06-25'),
(42, 1, 11, 'Retirada', '2025-06-25'),
(43, 1, 11, 'Devolução', '2025-06-25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `indicacoes`
--

CREATE TABLE `indicacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `indicacoes`
--

INSERT INTO `indicacoes` (`id`, `id_usuario`, `titulo`, `autor`) VALUES
(13, 4, 'As Vantagens de Ser Invisível', 'Stephen Chbosky'),
(21, 4, 'Entrevista com o Vampiro', 'Anne Rice'),
(22, 4, 'Relatos de um gato viajante', 'Hiro Arikawa'),
(35, 3, 'Relatos de um gato viajante', 'Hiro Arikawa'),
(37, 9, 'As Vantagens de Ser Invisível', 'Stephen Chbosky'),
(38, 9, 'Teste', 'teste'),
(39, 10, 'O menino do pijama listrado', 'John Boyne'),
(40, 10, 'O menino do pijama listrado', 'John Boyne'),
(41, 10, 'O menino do pijama listrado', 'John Boyne'),
(42, 10, 'O menino do pijama listrado', 'John Boyne'),
(43, 10, 'Extraordinário', 'R. J. Palacio'),
(44, 11, 'teste', 'teste'),
(45, 11, 'As Vantagens de Ser Invisível', 'Stephen Chbosky');

-- --------------------------------------------------------

--
-- Estrutura para tabela `livros`
--

CREATE TABLE `livros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `sinopse` text NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `reservas` int(11) NOT NULL,
  `data_lancamento` date NOT NULL,
  `capa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livros`
--

INSERT INTO `livros` (`id`, `titulo`, `autor`, `sinopse`, `id_categoria`, `status`, `reservas`, `data_lancamento`, `capa`) VALUES
(1, 'Ed & Lorraine Warren: Demonologistas', 'Gerald Brittle', 'Em Ed & Lorraine Warren: Demonologistas, o jornalista Gerald Brittle revela os casos mais impressionantes investigados pelo casal de demonologistas Ed e Lorraine Warren. Através de relatos detalhados e acesso exclusivo aos arquivos dos Warren, o livro apresenta fenômenos sobrenaturais como possessões demoníacas, poltergeists e casas mal-assombradas. Com uma abordagem séria e investigativa, a obra oferece uma visão única sobre o trabalho dos Warren, conhecidos por sua atuação em casos como o da casa de Amityville e a boneca Annabelle.', 1, 0, 18, '2016-10-06', 'assets/img/91Cu2hkpMlL._UF1000,1000_QL80_.jpg'),
(2, 'Ed & Lorraine Warren: Vidas Eternas', 'Robert Curran', 'Em Ed & Lorraine Warren: Vidas Eternas, o jornalista Robert Curran narra o aterrador caso da família Smurl, que enfrentou uma intensa assombração em sua casa na Pensilvânia. Através de testemunhos dos moradores e da atuação dos investigadores paranormais Ed e Lorraine Warren, o livro revela manifestações demoníacas, exorcismos e o impacto psicológico vivido pela família. O relato é uma imersão em um dos casos mais perturbadores documentados pelos Warren.', 1, 1, 24, '2019-06-18', 'assets/img/46039614.jpg'),
(3, 'Ed & Lorraine Warren: Lugar Sombrio', 'Ray Garton', 'Após a mudança para a nova residência, a família Snedeker começa a vivenciar uma série de fenômenos inexplicáveis e perturbadores. Os membros da família são atacados por uma presença sinistra que se manifesta de diversas formas, desde vozes na escuridão até manifestações físicas assustadoras. Desesperados, eles recorrem aos Warren, conhecidos por sua experiência em lidar com casos paranormais. O livro relata detalhadamente a investigação e os esforços do casal para confrontar e combater as forças malignas que assombravam a casa.', 1, 0, 7, '2017-09-14', 'assets/img/9788594541147_p0_v1_s1200x630.jpg'),
(4, 'As Vantagens de Ser Invisível', 'Stephen Chbosky', 'Ao mesmo tempo engraçado e atordoante, ‘As Vantagens de Ser Invisível’ é uma história sobre o amadurecimento que explora o lado confuso da adolescência com honestidade brutal e sensibilidade tocante.\r\n\r\nCharlie é um adolescente introvertido e sensível que escreve cartas anônimas a alguém que ele acredita ser uma boa pessoa. Nessas cartas, ele compartilha suas experiências com amizades, traumas, primeiros amores, festas, livros e músicas — tudo enquanto lida com a dor da perda de seu melhor amigo e uma história familiar marcada por segredos.\r\n\r\nCom a ajuda de dois novos amigos — Sam e Patrick — Charlie começa a descobrir o valor das pequenas coisas e o significado de “pertencer”.\r\n\r\nUma obra sincera, profunda e memorável, que já marcou gerações de leitores ao redor do mundo.', 3, 0, 7, '1999-02-01', 'assets/img/image.jpg'),
(5, 'O Lar da Srta. Peregrine para Crianças Peculiares', 'Ransom Riggs', 'Tudo começa quando Jacob, um adolescente de 16 anos, sofre uma tragédia familiar e parte para uma ilha remota no País de Gales. Lá, ele descobre as ruínas do orfanato onde seu avô viveu. Mas, à medida que explora o local, percebe que talvez as crianças que lá viveram — incluindo uma garota que levita e um menino invisível — não fossem apenas fruto da imaginação do avô.\r\n\r\nConforme Jacob se aprofunda nesse mistério, ele é levado a um mundo mágico e sombrio, onde o tempo se comporta de forma estranha e onde crianças peculiares vivem escondidas para se proteger de ameaças perigosas.\r\n\r\nÉ uma história envolvente que mistura fantasia, aventura e fotografias reais, criando um universo único e misterioso.', 2, 0, 6, '2016-11-12', 'assets/img/71RMRi+OpiL._AC_UF1000,1000_QL80_DpWeblab_.jpg'),
(6, 'Entrevista com o Vampiro', 'Anne Rice', 'Um jornalista biógrafo entrevista um jovem que afirma ser vampiro. Louis de Pointe du Lac, um homem que perdeu tudo, narra suas experiências dos últimos 200 anos e reconta seu encontro com Lestat de Lioncourt, uma criatura da noite.', 4, 0, 6, '1976-04-12', 'assets/img/71uPPT8QB2L.jpg'),
(7, 'Harry Potter e o Prisioneiro de Azkaban', 'J. K. Rowling', 'Em \"Harry Potter e o Prisioneiro de Azkaban\", Harry, Ron e Hermione enfrentam o terceiro ano em Hogwarts e uma nova ameaça: Sirius Black, um bruxo que fugiu da prisão de Azkaban, alegadamente um aliado de Voldemort, e que acredita-se estar a procurar Harry. A escola é protegida por Dementadores, criaturas que sugam a felicidade, e a investigação de Black leva Harry a descobrir a verdade sobre o seu passado e a identidade de um traidor.', 2, 1, 12, '1999-07-08', 'assets/img/9786555324037.jpg'),
(10, 'O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 'Um piloto cai com seu avião no deserto do Saara, onde conhece um pequeno príncipe que veio de um asteroide distante. O príncipe compartilha suas histórias e aprendizados sobre a vida, o amor, a amizade e a essência das coisas realmente importantes — que muitas vezes são invisíveis aos olhos.\r\n\r\nPor meio de encontros com diferentes personagens em sua jornada, o Pequeno Príncipe revela verdades profundas sobre a natureza humana, a inocência da infância e a importância de cuidar do que se ama.\r\n\r\nUma fábula poética e filosófica que encanta leitores de todas as idades com sua simplicidade e sabedoria atemporal.', 6, 1, 2, '1943-04-06', 'assets/img/81SVIwe5L9L._SL1500_.jpg'),
(17, 'O Sol é para Todos', 'Harper Lee', 'O Sol é para Todos é uma obra que acompanha a infância de Scout Finch na pequena cidade de Maycomb, Alabama, durante a Grande Depressão. Scout e seu irmão Jem crescem sob os ensinamentos do pai, Atticus Finch, um advogado íntegro que aceita defender Tom Robinson, um homem negro acusado injustamente de estuprar uma mulher branca. Através dos olhos de Scout, a narrativa expõe o racismo, a injustiça social e as tensões que permeiam a comunidade, enquanto a menina descobre lições profundas sobre empatia, coragem e a complexidade da natureza humana.', 4, 0, 0, '1960-07-11', 'assets/img/91WKPd60P4L._UF1000,1000_QL80_.jpg'),
(18, 'A Menina que Roubava Livros', 'Markus Zusak', 'A história se passa na Alemanha nazista durante a Segunda Guerra Mundial e é narrada pela Morte. Ela acompanha Liesel Meminger, uma jovem que é enviada para viver com uma família adotiva após perder o irmão e ser separada da mãe. Liesel desenvolve uma paixão pelos livros, que ela começa a “roubar” para encontrar conforto e esperança em meio ao caos da guerra. Enquanto enfrenta o medo, a perda e as dificuldades da época, Liesel cria laços profundos com as pessoas ao seu redor e descobre o poder das palavras para resistir às adversidades.', 7, 0, 0, '2007-02-15', 'assets/img/61L+4OBhm-L._UF1000,1000_QL80_.jpg'),
(19, 'Sapiens: Uma Breve História da Humanidade', 'Yuval Noah Harari', 'O livro traça a trajetória da espécie humana desde o surgimento do Homo sapiens na África até os dias atuais. Yuval Noah Harari explora como fatores biológicos, culturais e sociais moldaram a nossa história, destacando momentos decisivos como a Revolução Cognitiva, a Revolução Agrícola e a Revolução Científica. A obra apresenta uma visão ampla e crítica sobre como o ser humano conseguiu dominar o planeta, criar civilizações complexas e influenciar o meio ambiente, além de discutir as implicações éticas e futuras da tecnologia e da ciência para a humanidade.', 8, 0, 0, '2020-11-13', 'assets/img/71-ghLb8qML._SL1500_.jpg'),
(20, 'O Alquimista', 'Paulo Coelho', 'O livro conta a jornada de Santiago, um jovem pastor espanhol que sonha em encontrar um tesouro escondido nas pirâmides do Egito. Movido por esse sonho, ele deixa sua vida simples para trás e parte em uma viagem repleta de descobertas e desafios. Ao longo do caminho, Santiago aprende a ouvir seu coração, interpretar os sinais do universo e a valorizar o verdadeiro significado da vida. A história é uma metáfora sobre a busca pelos sonhos, a importância da perseverança e o autoconhecimento.', 9, 1, 2, '1988-04-01', 'assets/img/81slUinjTlS.jpg'),
(21, 'A Guerra dos Tronos', 'George R. R. Martin', 'Em um mundo onde os verões duram décadas e os invernos podem durar uma vida inteira, sete grandes casas nobres disputam o controle do trono de ferro e dos Sete Reinos de Westeros. No norte, a Casa Stark luta para manter a honra em meio à corrupção e traições da corte. Enquanto isso, além do Mar Estreito, a jovem Daenerys Targaryen tenta reconquistar o trono que sua família perdeu. Intrigas políticas, batalhas épicas, ambição e magia antiga se entrelaçam em uma narrativa rica e imprevisível. Com múltiplos pontos de vista, o livro mergulha o leitor em uma saga complexa e fascinante, marcada por personagens profundos e reviravoltas marcantes.', 2, 0, 0, '1996-08-06', 'assets/img/91+1SUO3vUL.jpg'),
(22, 'Dom Casmurro', 'Machado de Assis', 'O romance narra a história de Bentinho, um homem de classe alta do século XIX que decide escrever suas memórias para reconstruir o passado. Desde jovem, Bentinho é apaixonado por sua vizinha Capitu, uma moça de personalidade forte e olhos “de cigana oblíqua e dissimulada”. Após conseguirem se casar, Bentinho passa a desconfiar da fidelidade da esposa, especialmente em relação à paternidade de seu filho. Movido por ciúmes e insegurança, ele mergulha em um turbilhão de dúvidas que nunca são plenamente resolvidas, deixando o leitor diante de uma narrativa ambígua e subjetiva.\r\n\r\nDom Casmurro é um dos maiores clássicos da literatura brasileira e um marco do realismo psicológico, colocando em questão a verdade dos fatos contados por um narrador possivelmente não confiável.', 10, 0, 2, '1900-04-14', 'assets/img/32902.jpg'),
(23, 'O Código Da Vinci', 'Dan Brown', 'Quando o curador do Museu do Louvre é assassinado, o simbologista Robert Langdon se vê envolvido em um mistério complexo que mistura códigos secretos, obras de arte e sociedades secretas. Junto com a criptóloga Sophie Neveu, ele embarca em uma corrida contra o tempo para desvendar pistas escondidas em pinturas famosas, que podem revelar um segredo que mudaria a história da humanidade. Repleto de suspense, reviravoltas e teorias controversas, o livro explora temas como religião, poder e verdade.', 12, 0, 0, '2003-03-18', 'assets/img/91QSDmqQdaL._SL1500_.jpg'),
(24, 'A Sombra do Vento', 'Carlos Ruiz Zafón', 'Em meio à Barcelona da década de 1940, o jovem Daniel Sempere é levado pelo pai a um lugar secreto chamado \"Cemitério dos Livros Esquecidos\", onde escolhe um livro intitulado A Sombra do Vento. Fascinado pela obra e seu autor misterioso, Daniel se vê envolvido em uma trama de mistérios, intrigas e segredos que permeiam não só o livro, mas também sua própria vida. Conforme desvenda os enigmas, ele enfrenta perigos, descobertas sobre sua família e uma rede complexa de personagens que atravessam o tempo. Uma história que mistura suspense, romance e um profundo amor pela literatura.', 13, 0, 1, '2007-04-04', 'assets/img/91p3HUZop-L._UF1000,1000_QL80_.jpg'),
(25, 'Rita Lee: uma autobiografia Livro por Rita Lee', 'Rita Lee', 'Em Rita Lee: Uma autobiografia, a cantora e compositora brasileira compartilha de forma franca e irreverente os detalhes de sua vida pessoal e carreira artística. A obra aborda desde sua infância e os primeiros passos na música até momentos marcantes como sua prisão em 1976, o encontro com Roberto de Carvalho e os altos e baixos de sua trajetória. Com humor característico e sinceridade, Rita Lee revela os bastidores de sua vida, incluindo suas relações com outros artistas, como Elis Regina e Gilberto Gil, e os desafios enfrentados ao longo dos anos. A narrativa é enriquecida com fotos inéditas e legendas criadas pela própria autora, oferecendo uma visão íntima e pessoal de uma das maiores estrelas da música brasileira.', 14, 0, 1, '2016-10-06', 'assets/img/81vgnVeezUL.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `cpf` char(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `login`
--

INSERT INTO `login` (`id`, `nome`, `usuario`, `cpf`, `email`, `telefone`, `senha`) VALUES
(3, 'Ana', 'Ana', '0293847584', 'ana@gmail.com', '51928393839', '$2y$10$gJrnxtnl8DrHCfhGx9Im/.H.6ZvWDFmYyB63LnYSes9blghM/dtgS'),
(4, 'Tais', 'Tais', '11111111111', 'tais@gmail.com', '51999999999', '$2y$10$06uibruKufTPpWsgy2.QjOVb/sJOIZFQ93E1H.1sbO/KC0mjXNHhu'),
(5, 'Admin', 'Admin', '22222222222', 'admin@gmail.com', '51988888888', '$2y$10$K0d3bhk/Co6eKzsvh1BlXeESI6jBdxcPCewy7NcH.1yUHPgs9LVL2'),
(6, 'Angelica Souza', 'Angelica', '0433423989', 'angelica@gmail.com', '51980195027', '$2y$10$Ea6ccX9mDo.xnFnTz7xwoetX2UPFoqmGlMXPQc0TqDjVQt4Sv2mS2'),
(7, 'Marcia Silva de Souza', 'marciasilvadesouza', '00000000000', 'marcia@gmail.com', '51989898989', '$2y$10$DYu2.A2oSPWrOYrQTcJQyuPbzOFP7DtZjOgAXHMXPnHaIqUFPM/1C'),
(8, 'Maria Rosa', 'Maria', '04444433390', 'maria@gmail.com', '519900342964', '$2y$10$zN/d096E25qpLMErPBLEK.rUnOqXLhwIfhvG99DMoC4fIi32Ex2cO'),
(9, 'Felles', 'Felles', '04433399989', 'felles@gmail.com', '519900342964', '$2y$10$jwktxMLuKAXmSHZ/yqryEeyyuzeszft271PNTtdjmse5iW3evQmeW'),
(10, 'Jose', 'Jose', '04433399998', 'jose@gmail.com', '51989898989', '$2y$10$EooMkNBNXB.uAcnPx8f50eqUAgP5bH7vMCufq6jM3F.peGbG7cEcm'),
(11, 'TaisFelles', 'TaisFelles', '12345678901', 'taisfelles@cigam.com.br', '5199999999', '$2y$10$pPSQiQpkW9WxtFho6hxfxOu9J6bfG3piyopju0Kqo7T9hFb0QvbMK');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `id_usuario`, `id_livro`, `texto`) VALUES
(3, 3, 7, 'Retirada de livro confirmada.'),
(4, 3, 7, 'Devolução de livro confirmada.'),
(14, 9, 1, 'Retirada de livro confirmada.'),
(15, 8, 4, 'Retirada de livro confirmada.'),
(16, 8, 2, 'Retirada de livro confirmada.'),
(17, 8, 10, 'Retirada de livro confirmada.'),
(18, 8, 7, 'Retirada de livro confirmada.'),
(19, 9, 20, 'Retirada de livro confirmada.'),
(21, 11, 1, 'Devolução de livro confirmada.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_retirada` date NOT NULL,
  `data_devolucao` date NOT NULL,
  `devolvido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `id_livro`, `id_usuario`, `data_retirada`, `data_devolucao`, `devolvido`) VALUES
(34, 1, 4, '2025-06-21', '2025-06-28', 1),
(35, 2, 4, '2025-06-13', '2025-06-20', 1),
(36, 1, 3, '2025-06-22', '2025-06-29', 1),
(37, 2, 4, '2025-06-22', '2025-06-29', 1),
(38, 5, 4, '2025-06-22', '2025-06-29', 1),
(39, 4, 8, '2025-06-01', '2025-06-07', 1),
(40, 3, 8, '2025-06-02', '2025-06-08', 1),
(41, 20, 8, '2025-06-22', '2025-06-29', 1),
(42, 24, 3, '2025-06-24', '2025-07-01', 1),
(43, 6, 8, '2025-06-24', '2025-07-01', 1),
(44, 25, 4, '2025-06-24', '2025-07-01', 1),
(45, 10, 4, '2025-06-24', '2025-07-01', 1),
(48, 2, 4, '2025-06-24', '2025-07-01', 1),
(49, 7, 3, '2025-06-24', '2025-07-01', 1),
(50, 7, 4, '2025-06-25', '2025-07-02', 1),
(51, 22, 4, '2025-06-25', '2025-07-02', 1),
(52, 5, 4, '2025-06-25', '2025-07-02', 1),
(53, 1, 4, '2025-06-25', '2025-07-02', 1),
(54, 2, 4, '2025-06-25', '2025-07-02', 1),
(55, 1, 9, '2025-06-25', '2025-07-02', 1),
(56, 4, 8, '2025-05-01', '2025-05-07', 1),
(57, 2, 8, '2025-06-11', '2025-06-18', 0),
(58, 10, 8, '2025-06-10', '2025-06-17', 0),
(59, 7, 8, '2025-06-04', '2025-06-11', 0),
(60, 20, 9, '2025-06-16', '2025-06-23', 0),
(61, 1, 11, '2025-06-25', '2025-07-02', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_livro` int(11) NOT NULL,
  `solicitacao` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comentarios_livro` (`id_livro`),
  ADD KEY `fk_comentarios_usuario` (`id_usuario`);

--
-- Índices de tabela `favoritos_usuario`
--
ALTER TABLE `favoritos_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_favoritos_usuario_login` (`id_usuario`),
  ADD KEY `fk_favoritos_livro` (`id_livro`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_historico_livro` (`id_livro`),
  ADD KEY `fk_historico_usuario` (`id_usuario`);

--
-- Índices de tabela `indicacoes`
--
ALTER TABLE `indicacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_indicacoes_usuario` (`id_usuario`);

--
-- Índices de tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`id_categoria`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_livro` (`id_livro`),
  ADD KEY `fk_id_usuario` (`id_usuario`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservas_livro` (`id_livro`),
  ADD KEY `fk_reservas_usuario` (`id_usuario`);

--
-- Índices de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_solicitacoes_usuario` (`id_usuario`),
  ADD KEY `fk_solicitacoes_livro` (`id_livro`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de tabela `favoritos_usuario`
--
ALTER TABLE `favoritos_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `indicacoes`
--
ALTER TABLE `indicacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `livros`
--
ALTER TABLE `livros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_comentarios_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentarios_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favoritos_usuario`
--
ALTER TABLE `favoritos_usuario`
  ADD CONSTRAINT `fk_favoritos_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favoritos_usuario_login` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `fk_historico_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historico_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `indicacoes`
--
ALTER TABLE `indicacoes`
  ADD CONSTRAINT `fk_indicacoes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`);

--
-- Restrições para tabelas `livros`
--
ALTER TABLE `livros`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_id_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservas_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `fk_solicitacoes_livro` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitacoes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
