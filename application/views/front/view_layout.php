<?php $this->load->view('front/include/view_header.php'); ?>

<div class="container">

	<!-- Start breadcrumb -->
	<ol class="breadcrumb" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">
		<?php if ($page == 'home'): ?>
		<li class="active">
			<span itemprop="title">
			<?php if(isset($numero_page)): ?>
				<a href="<?php echo base_url(); ?>">Home</a> - page <?php echo $numero_page; ?>
			<?php else: ?>
				<span itemprop="title">Home</span>
			<?php endif; ?>
			</span>
		</li>
		<?php else: ?>
		<li><span itemprop="title"><a itemprop="url" href="<?php echo base_url(); ?>">Home</a></span></li>
			<?php if ($page == 'rubric'): ?>
				<li class="active">
					<span itemprop="title">
					<?php if(isset($numero_page)): ?>
						<a href="<?php echo base_url($this->uri->segment(1)); ?>"><?php echo $title; ?></a> - page <?php echo $numero_page; ?>
					<?php else: ?>
						<?php echo $title; ?>
					<?php endif; ?>
					</span>
				</li>
			<?php endif; ?>
			<?php if ($page == 'content'): ?>
				<li>
					<span itemprop="title"><?php echo rubric_url($r_url_rw, $r_title); ?></span>
				</li>
				<li class="active">
					<span itemprop="title"><?php echo $c_title; ?></span>
				</li>
			<?php endif; ?>
			<?php if ($page == '404'): ?>
				<li class="active">
					<span itemprop="title">Erreur 404</span>
				</li>
			<?php endif; ?>
		<?php endif; ?>
	</ol>
	<!-- End breadcrumb -->

	<div class="page-header">
		<h1 class="text-center"><?php echo $title; ?></h1>
	</div>

	<div class="row">
		<div class="col-md-8">
			<?php switch ($page) {
				case 'home':
				case 'rubric':
						$this->load->view('front/view_listing.php');
					break;

				case 'content':
						$this->load->view('front/view_content.php');
					break;

				case '404':
						$this->load->view('front/view_404.php');
					break;

				default:
					break;
			}
			?>
		</div><!-- end of .col-md-8 -->

		<?php $this->load->view('front/include/view_sidebar.php'); ?>

	</div><!-- end of .row -->
</div><!-- end of .container -->

<?php $this->load->view('front/include/view_footer.php'); ?>