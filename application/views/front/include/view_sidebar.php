			<aside class="col-md-4 hidden-xs">

				<h3>About</h3>
				<p>Lorem ipsum Eiusmod irure sint Ut magna incididunt ut esse eu enim consequat et mollit cupidatat irure veniam laborum veniam dolore amet in et aliqua deserunt occaecat laborum proident Ut officia sunt laboris laborum adipisicing reprehenderit anim proident quis.</p>
				<?php if ($query_all_rubrics->num_rows > 0): ?>

				<h3>Catégories (<?php echo $query_all_rubrics->num_rows(); ?>)</h3>
				<ul class="unstyled">
				<?php foreach ($query_all_rubrics->result() as $row): ?>
					<li>
						<a href="<?php echo base_url($row->r_url_rw); ?>" <?php if ($this->uri->segment(1) == $row->r_url_rw): echo 'title="Categorie actuelle"'; endif; ?>><?php echo $row->r_title; ?></a>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				<?php if ($all_content->num_rows > 0): ?>
				<h3>Archives (<?php echo $all_content->num_rows(); ?>) </h3>
				<ul class="unstyled">
 				<?php foreach ($all_content->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach;?>
				</ul>
				<?php endif; ?>

			</aside><!-- end of .col-md-4 -->
