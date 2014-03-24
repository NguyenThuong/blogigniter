--
-- Base de données: `blogigniter`
--
CREATE DATABASE IF NOT EXISTS `blogigniter` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `blogigniter`;

-- --------------------------------------------------------

--
-- Structure de la table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `r_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `c_title` varchar(255) NOT NULL,
  `c_content` text NOT NULL,
  `c_cdate` datetime NOT NULL,
  `c_udate` datetime NOT NULL,
  `c_url_rw` varchar(255) NOT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `c_url_rw` (`c_url_rw`),
  KEY `fk_r_id` (`r_id`),
  KEY `fk_u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `content`
--

INSERT INTO `content` (`c_id`, `r_id`, `u_id`, `c_title`, `c_content`, `c_cdate`, `c_udate`, `c_url_rw`) VALUES
(1, 1, 1, 'Article 1', 'Lorem ipsum 1. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-01-05 12:00:10', '2013-01-05 12:00:10', 'article-1'),
(3, 1, 1, 'Article 3', 'Lorem ipsum 3. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-05-05 12:00:10', '2013-05-05 12:00:10', 'article-3'),
(4, 1, 1, 'Article 4', 'Lorem ipsum 4. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-06-06 12:00:10', '2013-06-06 12:00:10', 'article-4'),
(5, 2, 1, 'Article 5', 'Lorem ipsum 5. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-07-01 12:00:10', '2013-07-01 12:00:10', 'article-5'),
(6, 2, 1, 'Article 6', 'Lorem ipsum 6. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-07-02 12:00:10', '2013-07-02 12:00:10', 'article-6'),
(7, 3, 1, 'Article 7', 'Lorem ipsum 7. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-07-03 12:00:10', '2013-07-03 12:00:10', 'article-7'),
(8, 1, 1, 'Article 8', 'Lorem ipsum 8. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-08-31 12:00:10', '2013-08-31 12:00:10', 'article-8'),
(9, 4, 1, 'Article 9', 'Lorem ipsum 9. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-09-01 12:00:10', '2013-09-01 12:00:10', 'article-9'),
(10, 4, 1, 'Article 10', 'Lorem ipsum 10. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d''attente. L''avantage de le mettre en latin est que l''opérateur sait au premier coup d''oeil que la page contenant ces lignes n''est pas valide, et surtout l''attention du client n''est pas dérangée par le contenu, il demeure concentré seulement sur l''aspect graphique.', '2013-10-20 12:00:10', '2013-10-20 12:00:10', 'article-10'),
(12, 5, 1, 'a', 'aa', '2014-01-17 19:38:28', '2014-01-17 22:51:40', 'aa'),
(13, 6, 1, 'test', 'aaa', '2014-01-17 23:36:40', '2014-01-17 23:40:34', 'test'),
(14, 2, 1, 'afze', 'fzefez', '2014-01-17 23:40:40', '2014-01-17 23:40:40', 'afze'),
(15, 1, 1, 'l''éléphant', 'tzetzter', '2014-01-17 23:51:40', '2014-01-17 23:51:40', 'lelephant'),
(17, 6, 1, 'azerty', 'fzefze', '2014-01-25 15:37:57', '2014-01-25 15:38:12', '12333');

-- --------------------------------------------------------

--
-- Structure de la table `rubric`
--

CREATE TABLE IF NOT EXISTS `rubric` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `r_title` varchar(255) NOT NULL,
  `r_description` text NOT NULL,
  `r_url_rw` varchar(255) NOT NULL,
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `r_url_rw` (`r_url_rw`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `rubric`
--

INSERT INTO `rubric` (`r_id`, `r_title`, `r_description`, `r_url_rw`) VALUES
(1, 'Rubrique 1', 'La description de la rubrique 1', 'rubrique-1'),
(2, 'Rubrique 2', 'La description de la rubrique 2', 'rubrique-2'),
(3, 'Rubrique 3', 'La description de la rubrique 3', 'rubrique-3'),
(4, 'Rubrique 4', 'La description de la rubrique 4', 'rubrique-4'),
(5, 'Rubrique 5', 'toto', 'rubrique-5'),
(6, 'tati', 'aaa', 'a');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_login` varchar(255) NOT NULL,
  `u_pass` varchar(255) NOT NULL,
  `u_level` int(11) NOT NULL COMMENT '1:admin, 0 : modérateur',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`u_id`, `u_login`, `u_pass`, `u_level`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72 ', 1);


--
-- Contraintes pour la table `content`
--

ALTER TABLE `content`
  ADD CONSTRAINT `fk_r_id` FOREIGN KEY (`r_id`) REFERENCES `rubric` (`r_id`);

ALTER TABLE `content`
  ADD CONSTRAINT `fk_u_id` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);