<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="description" content="<?php echo $title ; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php echo css_url('bootstrap.min'); ?>
		<?php 
		if ($page == 'add_content' or $page == 'edit_content'):
		 	echo css_url('redactor');
		endif; 
		?>
	</head>


	<body class="container">

		<nav class="navbar navbar-default" role="navigation">

			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button><!-- end .navbar-toggle -->
				<a class="navbar-brand" href="<?php echo base_url('admin/content'); ?>">Dashboard</a>
			</div><!-- end .navbar-header -->

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li <?php if ($page == 'home' or $page == 'add_content' or $page == 'edit_content' or $page == 'author'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/content'); ?>" class="dropdown-toggle">Articles</a>
					</li>
					<li <?php if ($page == 'rubric' or $page == 'add_rubric' or $page == 'edit_rubric'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/rubric'); ?>">Rubriques</a>
					</li>
					<li <?php if ($page == 'comment' or $page == 'add_comment' or $page == 'edit_comment'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/comments'); ?>">Commentaires 
						<?php if (!empty($nb_comments)): ?>
							(<b><?php echo $nb_comments; ?></b>)
						<?php endif; ?>
						</a>
					</li>
					<li <?php if ($page == 'users' or $page == 'add_user' or $page == 'edit_user'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/user'); ?>">Utilisateurs</a>
					</li>
					<li <?php if ($page == 'gallery'){ echo "class='active'"; }; ?>>
						<a href="<?php echo base_url('admin/medias'); ?>">Galerie</a>
					</li>
				</ul><!-- end .nav navbar-nav -->
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo base_url(); ?>" target="_blank">Le blog</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user_data['login']; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php if ($user_data['level'] == 1): ?>
							<li>
								<a href="<?php echo base_url('admin/params'); ?>">
									<i class="glyphicon glyphicon-cog"></i> Paramêtres
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('admin/search'); ?>">
									<i class="glyphicon glyphicon-search"></i> Mots recherches
								</a>
							</li>
							<?php endif; ?>
							<li>
								<a href="<?php echo base_url('admin/user/change_password'); ?>">
									<i class="glyphicon glyphicon-user"></i> Changer mot de passe
								</a>
							</li>
							<li>
								<a href="<?php echo base_url('admin/logout'); ?>">
									<i class="glyphicon glyphicon-off"></i> Se déconnecter
								</a>
							</li>
						</ul><!-- end .dropdown-menu -->
					</li><!-- end .dropdown -->
				</ul><!-- end .nav .navbar-nav .navbar-right -->
			</div><!-- end .collapse .navbar-collapse #bs-example-navbar-collapse-1 -->

		</nav><!-- end .navbar .navbar-default -->

		<?php if (validation_errors()): ?>
			<?php echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>'); ?>
		<?php endif; ?>

		<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<?php echo $this->session->flashdata('success'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
		</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('alert')): ?>
		<div class="alert alert-danger">
			<?php echo $this->session->flashdata('alert'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
		</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('warning')): ?>
		<div class="alert alert-warning">
			<?php echo $this->session->flashdata('warning'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
		</div>
		<?php endif; ?>


	<div class="row">

		<div class="col-md-12">

			<section>
				<ul class="list-inline">
					<li>
						<button onClick="window.location.href='<?php echo base_url('admin/content/edit'); ?>'" class="btn btn-danger">
							<i class="glyphicon glyphicon-plus"></i> Ajouter un article
						</button>
					</li>
					<li>
						<button onClick="window.location.href='<?php echo base_url('admin/rubric/edit'); ?>'" class="btn btn-primary">
							<i class="glyphicon glyphicon-plus"></i> Ajouter une rubrique
						</button>
					</li>
					<?php if ($user_data['level'] == 1): ?>
					<li>
						<button onClick="window.location.href='<?php echo base_url('admin/user/edit'); ?>'" class="btn">
							<i class="glyphicon glyphicon-plus"></i> Ajouter un utilisateur
						</button>
					</li>
					<?php endif; ?>
					<li>
						<?php if (current_url() !== base_url('admin/user/' . $user_data['id_user'])): ?>
						<a href="<?php echo base_url('admin/user/' . $user_data['id_user']); ?>">
							Mes articles (<?php echo ($this->model_content->get_content_by_user($user_data['id_user'], '')->num_rows); ?>)
						</a>
						<?php else:?>
						<a href="<?php echo base_url('admin/content'); ?>">Tous les articles</a>
						<?php endif; ?>

					</li>
				</ul>
			</section>

		</div><!-- end of .col-md-12 -->

	</div><!-- end of .row -->

	<div class="row">

		<div class="col-md-12">
		<?php switch ($page) {
			case 'home':
			case 'author':
				$this->load->view('admin/dashboard/content/view_listing_content');
				break;

			case 'add_content':
			case 'edit_content':
				$this->load->view('admin/dashboard/content/view_edit_content');
				break;

			case 'rubric':
				$this->load->view('admin/dashboard/rubrics/view_listing_rubric');
				break;

			case 'add_rubric':
			case 'edit_rubric':
				$this->load->view('admin/dashboard/rubrics/view_edit_rubric');
				break;

			case 'comment':
				$this->load->view('admin/dashboard/comments/view_listing_comments');
				break;

			case 'users':
				$this->load->view('admin/dashboard/users/view_listing_users');
				break;

			case 'add_user':
			case 'edit_user':
				$this->load->view('admin/dashboard/users/view_edit_user');
				break;

			case 'change_password':
				$this->load->view('admin/dashboard/users/view_change_password');
				break;

			case 'gallery':
				$this->load->view('admin/dashboard/gallery/view_listing_gallery');
				break;

			case 'params':
				$this->load->view('admin/dashboard/params/view_edit_params');
				break;

			case 'search':
				$this->load->view('admin/dashboard/search/view_listing_search');
				break;

			default:
				$this->load->view('admin/dashboard');
				break;
		}
		?>
		</div><!-- end .col-md-10 -->

	</div><!-- end .row --> 

	<footer>
		<footer data-role="footer">
			<p class="footer" style="text-align: center"><br />Propulsé par Codeigniter - Temps d'exécution : <strong>{elapsed_time}</strong> seconds</p>
		</footer>
	</footer>

	<?php
		echo js_url('jquery.min');
		echo js_url('bootstrap.min');
		if ($page == 'add_content'):
		echo js_url('bootstrap-datepicker');
	?>

	<script>
		$('#datetimepicker input').datepicker({
	});
	</script>
		<script src="http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>

	<?php
		endif;
		if ($page == 'add_content' || $page == 'edit_content'):
			echo js_url('redactor.min');
			echo js_url('bootstrap-tagsinput.min');
	?>

	<script>
		$(function()
		{
			$('#c_content').redactor();
		});
	</script>
	<script>
		$('.get-img').fadeOut(0);

		$('.show-img').css('cursor', 'pointer');
		$('.to_hide').css('cursor', 'pointer');

		$('.show-img').click(function() {
			$( ".get-img" ).fadeIn(0);
		});

		$('.to_hide').click(function() {
			$( ".get-img" ).fadeOut(0);
		});
	</script>
	<?php
		endif;
	?>

	</body>

</html>