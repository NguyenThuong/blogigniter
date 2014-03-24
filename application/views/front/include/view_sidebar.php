			<aside class="col-md-3 hidden-xs">

				<h3 style="margin-top: 0">About</h3>
				<?php if (isset($about)): ?>
				<?php echo $about; ?>
				<?php endif; ?>

				<h3>Recherche</h3>
				<form action="<?php echo base_url('s'); ?>" method="get" role="search">
					<input name="q" type="text" placeholder="Rechercher" required>
					<input type="submit" value="Ok"/>
				</form>

				<?php if ($all_content->num_rows > 0): ?>
				<h3>Archives (<?php echo $all_content->num_rows(); ?>) </h3>
				<ul class="list-unstyled">
 				<?php foreach ($all_content->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

				<?php if ($all_authors->num_rows > 0): ?>
				<h3>Auteurs</h3>
				<ul class="list-unstyled">
 				<?php foreach ($all_authors->result() as $row): ?>
					<li><?php echo author_url($row->u_login); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

				<h3>RSS</h3>
				<a href="<?php echo base_url('rss'); ?>"><img src="<?php echo base_url('assets/img/rss.jpg'); ?>" alt="Flux RSS" /></a>

			</aside><!-- end of .col-md-3 -->
