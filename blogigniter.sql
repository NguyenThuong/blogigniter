--
-- Base de données: `blogigniter`
--
CREATE DATABASE IF NOT EXISTS `blogigniter` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `blogigniter`;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL,
  `com_nickname` varchar(255) NOT NULL,
  `com_content` text NOT NULL,
  `com_date` datetime NOT NULL,
  `com_status` int(11) NOT NULL,
  PRIMARY KEY (`com_id`),
  KEY `com_id` (`com_id`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
  `c_image` varchar(255) NOT NULL,
  `c_tags` varchar(255) NOT NULL,
  `c_state` int(11) NOT NULL,
  `c_cdate` datetime NOT NULL,
  `c_udate` datetime NOT NULL,
  `c_url_rw` varchar(255) NOT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `c_url_rw` (`c_url_rw`),
  UNIQUE KEY `c_title` (`c_title`),
  KEY `fk_r_id` (`r_id`),
  KEY `fk_u_id` (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `params`
--

CREATE TABLE IF NOT EXISTS `params` (
  `p_id` int(11) NOT NULL,
  `p_title` varchar(255) NOT NULL,
  `p_m_description` varchar(255) NOT NULL,
  `p_about` text NOT NULL,
  `p_nb_listing` int(11) NOT NULL,
  `p_nb_listing_f` int(11) NOT NULL,
  `p_email` varchar(255) NOT NULL,
  `p_twitter` varchar(255) NOT NULL,
  PRIMARY KEY (`p_id`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  UNIQUE KEY `r_url_rw` (`r_url_rw`),
  UNIQUE KEY `r_title` (`r_title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_word` varchar(255) NOT NULL,
  `s_date` datetime NOT NULL,
  `s_score` int(11) NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_login` varchar(255) NOT NULL,
  `u_pass` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_level` int(11) NOT NULL COMMENT '1:admin, 0 : modérateur',
  `u_biography` varchar(255) NOT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_login` (`u_login`),
  UNIQUE KEY `u_email` (`u_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_c_id` FOREIGN KEY (`c_id`) REFERENCES `content` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `fk_r_id` FOREIGN KEY (`r_id`) REFERENCES `rubric` (`r_id`),
  ADD CONSTRAINT `fk_u_id` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`);