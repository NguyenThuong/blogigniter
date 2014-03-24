<?php if (isset($query) && $query->num_rows() > 0): ?>

	<?php foreach ($query->result() as $row): ?>
		<article class="thumbnail">
			<div class="caption">
				<p class="row">
					<span class="col-md-2">
						<i class="glyphicon glyphicon-tag"></i> <?php echo rubric_url($row->r_url_rw, $row->r_title); ?>
					</span>
					<span class="col-md-3 col-md-offset-7 text-right">
						<i class="glyphicon glyphicon-calendar"></i> 
						<?php $jour  = date("d", strtotime($row->c_cdate)); ?>
						<?php $mois  = date("m", strtotime($row->c_cdate)); ?>
						<?php $annee = date("Y", strtotime($row->c_cdate)); ?>
						<em><?php echo date_fr($jour, $mois, $annee); ?></em>
					</span>
				</p><!-- end of .row -->
				<?php if (!empty($row->c_image)): ?>
					<img src="<?php echo $row->c_image; ?>" alt="" class="img-responsive" style="margin: 0 auto;" width="280px" heigth="120px" />
				<?php endif; ?>

				<h2><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></h2>
				<p><?php echo character_limiter($row->c_content, 256); ?></p>
				<?php echo content_url_button($row->r_url_rw, $row->c_url_rw); ?>
			</div><!-- end of .caption -->
		</article><!-- end of .thumbnail -->
	<?php endforeach; ?>

	<?php echo $pagination; ?>

<?php else: ?>
	<?php if ($page == 'search'): ?>
		<?php if (isset($error)): ?>
			<p><?php echo $error; ?></p>
		<?php else: ?>
			<p>Aucun résultat trouvé ne correpond à votre recherche.</p>
		<?php endif; ?>
	<?php else: ?>
	<p>Aucun article n'est disponible pour le moment</p>
	<?php endif; ?>
<?php endif; ?>
