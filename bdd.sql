CREATE TABLE IF NOT EXISTS `rubric` (
    `r_id` int(11) NOT NULL AUTO_INCREMENT,
    `r_title` varchar(255) NOT NULL,
    `r_description` text NOT NULL,
    `r_url_rw` varchar(255) NOT NULL,
    PRIMARY KEY (`r_id`),
    UNIQUE KEY `r_url_rw` (`r_url_rw`)
) ENGINE=InnoDB;
 
CREATE TABLE IF NOT EXISTS `content` (
    `c_id` int(11) NOT NULL AUTO_INCREMENT,
    `r_id` int(11) NOT NULL,
    `c_title` varchar(255) NOT NULL,
    `c_content` text NOT NULL,
    `c_cdate` datetime NOT NULL,
    `c_udate` datetime NOT NULL,
    `c_url_rw` varchar(255) NOT NULL,
    PRIMARY KEY (`c_id`),
    UNIQUE KEY `c_url_rw` (`c_url_rw`),
    CONSTRAINT fk_r_id FOREIGN KEY (r_id) REFERENCES rubric (r_id)
) ENGINE=InnoDB;
 
INSERT INTO `rubric`
VALUES
(1, 'Rubrique 1', 'La description de la rubrique 1', 'rubrique-1'),
(2, 'Rubrique 2', 'La description de la rubrique 2', 'rubrique-2'),
(3, 'Rubrique 3', 'La description de la rubrique 3', 'rubrique-3'),
(4, 'Rubrique 4', 'La description de la rubrique 4', 'rubrique-4');
 
INSERT INTO `content`
VALUES
(1, 1, 'Article 1', "Lorem ipsum 1. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-01-05 12:00:10', '0000-00-00 00:00:00', 'article-1'),
(2, 2, 'Article 2', "Lorem ipsum 2. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-03-10 12:00:10', '0000-00-00 00:00:00', 'article-2'),
(3, 1, 'Article 3', "Lorem ipsum 3. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-05-05 12:00:10', '0000-00-00 00:00:00', 'article-3'),
(4, 1, 'Article 4', "Lorem ipsum 4. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-06-06 12:00:10', '0000-00-00 00:00:00', 'article-4'),
(5, 2, 'Article 5', "Lorem ipsum 5. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-07-01 12:00:10', '0000-00-00 00:00:00', 'article-5'),
(6, 2, 'Article 6', "Lorem ipsum 6. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-07-02 12:00:10', '0000-00-00 00:00:00', 'article-6'),
(7, 3, 'Article 7', "Lorem ipsum 7. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-07-03 12:00:10', '0000-00-00 00:00:00', 'article-7'),
(8, 1, 'Article 8', "Lorem ipsum 8. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-08-31 12:00:10', '0000-00-00 00:00:00', 'article-8'),
(9, 4, 'Article 9', "Lorem ipsum 9. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-09-01 12:00:10', '0000-00-00 00:00:00', 'article-9'),
(10, 4, 'Article 10', "Lorem ipsum 10. Généralement, on utilise un texte en faux latin (le texte ne veut rien dire, il a été modifié), le Lorem ipsum ou Lipsum, qui permet donc de faire office de texte d'attente. L'avantage de le mettre en latin est que l'opérateur sait au premier coup d'oeil que la page contenant ces lignes n'est pas valide, et surtout l'attention du client n'est pas dérangée par le contenu, il demeure concentré seulement sur l'aspect graphique.", '2013-10-20 12:00:10', '0000-00-00 00:00:00', 'article-10');