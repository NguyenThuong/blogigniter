<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?php echo $meta_title; ?></title>
        <meta name="description" content="<?php echo $meta_desc; ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-responsive.min.css'); ?>" />
    </head>
    <body data-role="page">

        <div class="container">
            <header class="navbar" data-role="header">
                <div class="navbar-inner">
                    <nav data-role="navigation">
                        <a class="brand" href="<?php echo base_url(''); ?>" <?php if($this->uri->total_segments() == 0){ echo 'title="Page actuelle"'; }?> >
                            Mon site
                        </a>
                        <ul class="nav pull-right" data-role="menubar">
                            <?php foreach ($query_all_rubriques as $row): ?>
                            <li <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'class="active"'; }?>>
                                <a href="<?php echo base_url('').$row->r_url_rw; ?>" <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'title="Rubrique actuelle"';}?>>
                                    <?php echo $row->r_title; ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul><!-- end of .nav pull-right -->
                    </nav>
                </div><!-- end of .navbar-inner -->
            </header>

            <div class="row" data-role="content">
                <div class="span8">
                    <div class="row">
                        <?php if($this->uri->total_segments() == 2): ?>
                        <article class="span8" role="article">
                            <img src="<?php echo base_url('assets/img/' . $c_image . '');?>" alt="" />
                            <h1><?php echo $c_title; ?></h1>
                            <span><a href="<?php echo base_url($r_url_rw); ?>"><?php echo $r_title; ?></a></span>
                            <br />
                            <?php
                                $jour  = date("d", strtotime($c_cdate));
                                $mois  = date("m", strtotime($c_cdate));
                                $annee = date("Y", strtotime($c_cdate));
                                echo datefr($jour, $mois, $annee);
                            ?>
                            <br /><?php echo $c_content; ?>
                        </article><!-- end of .span8 -->
                        <?php if(!empty($query_same_rubrique)): ?>
                        <div class="span8">
                            <h3>Article de la même catégorie :</h3>
                            <ul>
                            <?php foreach ($query_same_rubrique as $row): ?>
                                <li><a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>"><?php echo $row->c_title;?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php else: ?>
                        <h1 class="span8"><?php echo $title; ?></h1>
                        <section>
                            <?php foreach ($query as $row): ?>
                            <article class="span8">
                                <img src="<?php echo base_url().'assets/' . $row->c_image . ' ';?>" alt="" />
                                <h2><a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>"><?php echo $row->c_title; ?></a></h2>
                                <span>
                                    <a href="<?php echo base_url('').$row->r_url_rw; ?>" <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'title="Rubrique actuelle"'; }?>><?php echo $row->r_title; ?></a>
                                </span>
                                <br />
                                <?php
                                    $jour  = date("d", strtotime($row->c_cdate));
                                    $mois  = date("m", strtotime($row->c_cdate));
                                    $annee = date("Y", strtotime($row->c_cdate));
                                    echo datefr($jour, $mois, $annee);
                                ?>
                                <br /><?php echo $row->c_content; ?>
                                <br /><a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>">Lire la suite</a>
                            </article><!-- end of .span8 -->
                            <?php endforeach; ?>
                        </section>
                        <?php endif; ?>
                    </div><!-- end of .row -->
                </div><!-- end of .span8 -->

                <aside class="span4">
                <?php if($this->uri->total_segments() == 2): ?>
                    <h3>Autres articles</h3>
                <?php else: ?>
                    <h3>Archives</h3>
                <?php endif; ?>
                    <ul>
                    <?php foreach($all_content as $row): ?>
                        <li><a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>"><?php echo $row->c_title; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </aside><!-- end of .span4 -->

            </div><!-- end of .row -->

        </div><!-- end of .container -->

        <footer data-role="footer">
            <p class="footer" style="text-align: center;">Propulsé par Codeigniter - Page rendered in <strong>{elapsed_time}</strong> seconds - http://etienner.fr</p>
        </footer>