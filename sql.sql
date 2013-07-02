CREATE TABLE IF NOT EXISTS `rubrique` (
    `r_id` int(11) NOT NULL AUTO_INCREMENT,
    `r_title` varchar(255) NOT NULL,
    `r_description` text NOT NULL,
    `r_url_rw` varchar(255) NOT NULL,
    PRIMARY KEY (`r_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `content` (
    `c_id` int(11) NOT NULL AUTO_INCREMENT,
    `r_id` int(11) NOT NULL,
    `c_title` varchar(255) NOT NULL,
    `c_content` text NOT NULL,
    `c_image` varchar(255) NOT NULL,
    `c_cdate` datetime NOT NULL,
    `c_udate` datetime NOT NULL,
    `c_url_rw` varchar(255) NOT NULL,
    PRIMARY KEY (`c_id`),
    CONSTRAINT fk_r_id FOREIGN KEY (r_id) REFERENCES rubrique (r_id)
) ENGINE=InnoDB;
