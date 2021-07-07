-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 07 juil. 2021 à 15:10
-- Version du serveur :  8.0.18
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blogphp`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `is_validated` tinyint(2) NOT NULL,
  `id_post` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_post` (`id_post`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `pseudo`, `content`, `date`, `is_validated`, `id_post`) VALUES
(4, 'Kevin&Clara', 'C\'est intéressant comme sujet', '2021-06-30 13:31:44', 1, 3),
(1, 'Floryss', 'Vous trouverez mes coordonnées dans le pied de page, ou vous pouvez utiliser le formulaire de la page d\'accueil si vous souhaitez me contacter !', '2021-06-30 13:23:58', 1, 3),
(2, 'ERIC', 'Je ferai appel à vous quand j\'aurai monté mon entreprise d\'oreillers !', '2021-06-30 13:27:01', 1, 3),
(3, 'ERIC', 'Ah vous êtes aussi d\'Openclassrooms ? Mon fils y fait aussi ses études ! La formation en ligne c\'est à la mode ! De mon temps on devait aller à Paris en vélo pour faire de bonnes études, tant mieux que vous puissiez faire des études depuis chez vous.', '2021-06-30 13:28:51', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `is_processed` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `first_name`, `last_name`, `email`, `title`, `content`, `is_processed`) VALUES
(1, 'Prunelle', 'Delacroix', 'floryss.devweb+6@gmail.com', 'Question sur votre CV', 'Bonjour,\r\nJ\'ai vu votre CV sur votre site et j\'ai une question : où en êtes-vous de votre formation chez Openclassrooms ? Si vous avez un stage à réaliser en juin 2022, mon agence LesFansWeb à Rouen se fera un plaisir de vous avoir comme stagiaire !\r\nBonne continuation,\r\nPrunelle Delacroix', 0),
(2, 'Trevor', 'Terter', 'floryss.devweb+3@gmail.com', 'Votre blog est super !', 'Je me permets de vous contacter juste pour vous dire que j\'aime bien les couleurs que vous avez utilisées sur votre blog. Pouvez-vous m\'envoyer les valeurs en hex svp ?\r\nCordialement', 0),
(3, 'Hacker', 'Malveillant', 'floryss.devweb+7@gmail.com', 'Ceci est un message malveillant', '<strong>J\'ai cassé l\'affichage de votre site !</strong>', 1),
(4, 'mr.', 'Gaston', 'floryss.devweb+2@gmail.com', 'test', 'test', 0);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `slug` varchar(255) NOT NULL,
  `heading` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_publication` datetime NOT NULL,
  `date_last_update` datetime DEFAULT NULL,
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `slug`, `heading`, `content`, `date_publication`, `date_last_update`, `author`) VALUES
(2, 'Mes premiers essais en PHP', 'mes-premiers-essais-en-php', '<p>Mes premiers essais en PHP n\'&eacute;taient pas glorieux mais il faut un d&eacute;but &agrave; tout... Je vous parle dans cet article de mes toutes premi&egrave;res pages !</p>', '<p>Mes toutes premi&egrave;res pages &eacute;taient longues comme le bras, et surtout je ne connaissais rien des normes psr - autant vous dire que c\'&eacute;tait la pagaille totale.</p>\r\n<p>Il m\'est arriv&eacute; une fois de reprendre un code que j\'avais commenc&eacute; un mois avant, impossible de me comprendre. Je laissais plein de commentaires pour expliquer chaque ligne de PHP mais vous vous doutez bien que ce n\'&eacute;tait pas aussi propre que de la doc php...</p>\r\n<p>Et &eacute;videmment, tout &eacute;tait en php proc&eacute;dural donc le code n\'&eacute;tait vraiment pas optimis&eacute;. Tous mes noms de variables &eacute;taient en fran&ccedil;ais. Git ? Qu\'est-ce que c\'est ? Jamais entendu parler...</p>\r\n<p>Et m&ecirc;me GitHub que m\'avait rapidement pr&eacute;sent&eacute; un camarade, je ne savais pas bien l\'utiliser.. Et bien s&ucirc;r, mes d&eacute;buts ont commenc&eacute; avec NotePad++ ! Je ne suis pass&eacute;e &agrave; SublimeText qu\'un an et des gal&egrave;res apr&egrave;s, pour enfin finir sur VSCode, l\'&eacute;diteur que je pr&eacute;f&egrave;re aujourd\'hui.</p>\r\n<p>Cette &eacute;pop&eacute;e a &eacute;t&eacute; bien longue, mais ce sont les d&eacute;buts et tout le monde en a eu !</p>', '2021-06-30 12:44:36', '2021-07-06 15:29:19', 'Floryss'),
(1, 'Mon parcours chez Openclassrooms', 'mon-parcours-chez-openclassrooms', '<p>Apr&egrave;s mon BAC, je me suis lanc&eacute;e dans l\'univers de la cr&eacute;ation de sites web, mais savoir ce que je voulais apprendre ne suffisait pas pour trouver la <strong>bonne</strong> &eacute;cole o&ugrave; l\'apprendre !</p>\r\n<p>Si vous avez soif de p&eacute;rip&eacute;ties et d\'aventures, vous serez servi avec l\'histoire de mon parcours post-bac !</p>', '<p>C\'est en terminale que j\'ai d&eacute;cid&eacute; de prendre la voie du web. J\'ai commenc&eacute; par une premi&egrave;re ann&eacute;e de DUT MMI (m&eacute;tiers du multim&eacute;dia et de l\'internet), mais le contenu des cours &eacute;tait bien trop vari&eacute; par rapport &agrave; ce que j\'attendais de ce DUT. Etant all&eacute;e &agrave; la journ&eacute;e portes ouvertes, j\'&eacute;tais pourtant s&ucirc;re que cette voie me permettrait de devenir d&eacute;veloppeuse web, c\'est ce que l\'on m\'avait assur&eacute;, mais en cours d\'ann&eacute;e, nos professeurs nous ont clairement dit que le DUT n\'apprenait que des bases et qu\'il ne serait pas suffisant pour travailler ensuite dans l\'un des domaines auquel il devait nous former.</p>\r\n<p>Cette ann&eacute;e-l&agrave;, l\'universit&eacute; a ferm&eacute; soudainement en mars &agrave; la suite de la pand&eacute;mie de covid-19, et j\'ai suivi le reste de l\'ann&eacute;e en distanciel. Cela a &eacute;t&eacute; loin d\'&ecirc;tre joyeux, et j\'ai &eacute;t&eacute; encore plus d&eacute;&ccedil;ue de cette poursuite d\'&eacute;tudes. J\'ai alors cherch&eacute; une autre solution pour apprendre &agrave; programmer (et seulement &agrave; programmer) et devenir rapidement d&eacute;veloppeuse web.</p>\r\n<p>Je cherchais alors une formation dipl&ocirc;mante, rapide, fiable, qui me donnerait les comp&eacute;tences que je souhaitais avoir et proche de chez moi. En plus de ma mauvaise exp&eacute;rience avec les cours en distanciel g&eacute;r&eacute;s difficilement par les organismes qui ont l\'habitude du pr&eacute;sentiel, Openclassrooms s\'est pr&eacute;sent&eacute; comme une &eacute;vidence pour moi.</p>\r\n<p>D&egrave;s juin 2020 et la fin de ma premi&egrave;re ann&eacute;e de DUT MMI, je savais que je voulais m\'engager dans le parcours D&eacute;veloppeur d\'applications, qui correspondait parfaitement &agrave; ce que je voulais apprendre. Je souhaitais apprendre le PHP et je comptais aussi me former &agrave; terme sur Symfony : dans ce parcours tout y &eacute;tait !</p>\r\n<p>Je suis &agrave; pr&eacute;sent tr&egrave;s heureuse de suivre ce parcours et d\'apprendre exactement ce qui me servira dans ma vie professionnelle.</p>', '2021-06-21 15:24:01', '2021-07-07 14:43:56', 'Floryss'),
(3, 'N\'ayez plus peur du web !', 'n-ayez-plus-peur-du-web', '<p>Si vous souhaitez avoir un site s&ucirc;r et bien g&eacute;r&eacute;, vous &ecirc;tes bien tomb&eacute;s !</p>', '<p>Internet n\'a &eacute;t&eacute; invent&eacute; que dans la fin des ann&eacute;es 80, il s\'est donc aggrandi et complexifi&eacute; extr&ecirc;mement vite. Il est normal que certaines personnes ne soient pas form&eacute;es &agrave; utiliser ce services, ou m&ecirc;me avoir peur de l\'utiliser.</p>\r\n<p>Pourtant, il y a des milliards d\'internautes aujourd\'hui et se priver de communication sur internet n\'est plus une possibilit&eacute; secondaire... Avoir un site web r&eacute;f&eacute;renc&eacute; est une bonne id&eacute;e pour faire conna&icirc;tre les produits de sa boutique, les horaires de son salon de coiffure ou encore la localisation de son cabinet de m&eacute;decine.</p>\r\n<p>Pour cela il faut demander le service d\'une agence ou d\'un freelance. Je ne suis pas encore pr&ecirc;te &agrave; entrer dans le monde du travail donc je ne me fais pas de la publicit&eacute; en disant cela, je fais juste moi aussi partie de ces clients qui n\'ont aucun moyen de consulter les articles d\'une boutique avant d\'y aller, de s\'organiser pour aller au salon de coiffure ou de pouvoir conna&icirc;tre l\'emplacement du cabinet de mon m&eacute;decin.</p>\r\n<p>Conclusion : avoir une image sur internet peut &ecirc;tre important. Pensez-y.</p>', '2021-06-30 13:04:02', '2021-07-07 14:52:09', 'Floryss');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(100) NOT NULL,
  `password` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `admin` tinyint(2) NOT NULL,
  `email_validated` tinyint(2) NOT NULL,
  `avatar_number` int(2) NOT NULL DEFAULT '1',
  `uuid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `password`, `email`, `admin`, `email_validated`, `avatar_number`, `uuid`) VALUES
(1, 'Floryss', '$2y$10$UjvRwvni5sSdKliUmE6dveqHI4Hci6cx0FRXfL.ZGZCWXAcZMmIa6', 'floryss.devweb@gmail.com', 1, 1, 5, NULL),
(3, 'Kevin&Clara', '$2y$10$EyCSF0YwVpVOEaKGxFeK9.uIXyYGqp74GcoZm21ZMUxoHP7LsZr3y', 'floryss.devweb+3@gmail.com', 0, 1, 3, NULL),
(2, 'ERIC', '$2y$10$097BIvfitkk3nzg0lxgKhuEDoWDwOA7fKGQwHQZxBLGds.WtxgmyW', 'floryss.devweb+2@gmail.com', 0, 1, 1, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
