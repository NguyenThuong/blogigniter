<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $meta_title; ?></title>
		<meta name="description" content="<?php echo $meta_desc; ?>" />
		<?php if ($page !== '404'): ?>
    <?php if ($page == 'search'): ?>
    <link rel="canonical" href="<?php echo base_url('s?q=' . $research); ?>" />
    <?php else: ?>
		<link rel="canonical" href="<?php echo current_url(); ?>" />
    <?php endif; ?>
		<?php endif; ?>
		<?php if (isset($meta_pagination)): ?>
			<?php echo $meta_pagination; ?>
		<?php endif; ?>
		<?php echo css_url('bootstrap.min'); ?>
	</head>

<div class="container">
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo $p_title; ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav  pull-right" role="navigation">
				<?php foreach ($query_all_rubrics->result() as $row): ?>
                            <li <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'class="active"'; }?>>
                                <a href="<?php echo base_url($row->r_url_rw); ?>" <?php if($this->uri->segment(1) == $row->r_url_rw){ echo 'title="Rubrique actuelle"';}?>>
                                    <?php echo $row->r_title; ?>
                                </a>
                            </li>				
                 <?php endforeach; ?>
				</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
