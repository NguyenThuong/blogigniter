		<article class="thumbnail">
			<div class="caption">
				<p class="row">
					<span class="col-md-2">
						<i class="glyphicon glyphicon-tag"></i> <a href="<?=base_url($r_url_rw);?>"><?php echo $r_title; ?></a>
					</span>
					<span class="col-md-3 col-md-offset-7 text-right">
						<i class="glyphicon glyphicon-calendar"></i> 
						<?php $jour  = date("d", strtotime($c_cdate)); ?>
						<?php $mois  = date("m", strtotime($c_cdate)); ?>
						<?php $annee = date("Y", strtotime($c_cdate)); ?>
						<em><?php echo date_fr($jour, $mois, $annee); ?></em>
					</span>
				</p><!-- end of .row -->
				<?php echo $c_content; ?>
			</div><!-- end of .caption -->
		</article><!-- end of .thumbnail -->

		<?php if($query_same_rubric->num_rows() > 0): ?>
		<h3>Article<?php if($query_same_rubric->num_rows() > 1){ echo 's';} ?> de la même catégorie :</h3>
		<ul>
		<?php foreach($query_same_rubric->result() as $row): ?>
			<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
