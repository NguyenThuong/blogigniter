		<article class="thumbnail">
			<div class="caption">
				<p class="row">
					<span class="col-md-2">
						<i class="glyphicon glyphicon-tag"></i> <a href="<?=base_url($r_url_rw);?>"><?php echo $r_title; ?></a>
					</span>
					<span class="col-md-3 col-md-offset-7 text-right">
						<i class="glyphicon glyphicon-calendar"></i> 
						<em><?php echo date_fr(date("d", strtotime($c_cdate)), date("m", strtotime($c_cdate)), date("Y", strtotime($c_cdate))); ?></em>
					</span>
				</p><!-- end of .row -->
				<p>
					Rédigé par <a href="<?php echo base_url('auteur/' . $u_login); ?>" title="Voir tous les autres articles de cet auteur"><?php echo $u_login; ?></a>
					<?php if ($c_cdate !== $c_udate): ?>, mis à jour le <em><?php echo date_fr(date("d", strtotime($c_udate)), date("m", strtotime($c_udate)), date("Y", strtotime($c_udate))); ?></em>
					<?php endif; ?>
				</p>

				<?php if ($c_image): ?>
					<img src="<?php echo $c_image; ?>" alt="" class="img-responsive" style="margin: 0 auto;" width="280px" heigth="120px" />
				<?php endif; ?>
				<?php echo $c_content; ?>
				<br />
				<br />

			</div><!-- end of .caption -->
		</article><!-- end of .thumbnail -->

		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#home" data-toggle="tab">Bio</a>
			</li>
			<?php if ($query_same_user->num_rows() > 0): ?>
			<li>
				<a href="#profile" data-toggle="tab">Ses derniers articles</a>
			</li>
			<?php endif; ?>
			<?php if ($query_same_rubric->num_rows() > 0): ?>
			<li>
				<a href="#same_articles" data-toggle="tab">
					Article<?php if ($query_same_rubric->num_rows() > 1){ echo 's';} ?> de la même catégorie :
				</a>
			</li>
			<?php endif; ?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">

			<div class="tab-pane active" id="home">
				<p><?php echo $u_login; ?></p>
				<?php if (!empty($u_biography)): ?>
				<p><?php echo $u_biography; ?></p>
				<?php else: ?>
				<p><em>Cet auteur n'a pas rédigé sa biographie</em></p>
				<?php endif; ?>
			</div>

			<?php if ($query_same_user->num_rows() > 0): ?>
			<div class="tab-pane" id="profile">
				<ul class="list-unstyled">
				<?php foreach ($query_same_user->result() as $row): ?>
					<li>
						<?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?>
						<br />
						<?php echo $row->c_cdate; ?>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if ($query_same_rubric->num_rows() > 0): ?>
			<div class="tab-pane" id="same_articles">
				<ul class="list-unstyled">
				<?php foreach ($query_same_rubric->result() as $row): ?>
					<li><?php echo content_url($row->r_url_rw, $row->c_url_rw, $row->c_title); ?></li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>


		<?php if (isset($comments) && $comments->num_rows() > 0): ?>

			<?php echo $comments->num_rows(); ?> avis - <a href="#comments">Ajouter le votre</a>
			<ul class="list-group">
			<?php foreach ($comments->result() as $comment): ?>
				 <li class="list-group-item">
					<span class="badge"> <?php echo date_fr(date("d", strtotime($comment->com_date)), date("m", strtotime($comment->com_date)), date("Y", strtotime($comment->com_date))); ?> à <?php echo date("H", strtotime($comment->com_date)); ?>h<?php echo date("m", strtotime($comment->com_date)); ?></span>
					<p>Posté par <?php echo $comment->com_nickname; ?> 
					<br />
					<br />
					<?php echo $comment->com_content; ?></p>
				</li>
			<?php endforeach; ?>
			</ul>

		<?php endif; ?>

		<h3 id="comments">Poster un avis</h3>

		<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<?php echo $this->session->flashdata('success'); ?> <a class="close" data-dismiss="alert" href="#">&times;</a>
		</div>
		<?php endif; ?>

		<?php if (validation_errors()): ?>
			<?php echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>'); ?>
		<?php endif; ?>

		<?php 
			echo form_open('');
		?>

		<div class="form-group">
			<label for="com_nickname">Pseudo :</label>
			<input type="text" class="form-control" id="com_nickname" name="com_nickname" value="<?php if (isset($com_nickname)) echo $com_nickname; echo set_value('com_nickname'); ?>" required />
		</div><!-- end .form-group -->

		<div class="form-group">
			<label for="com_content">Contenu :</label>
			<textarea type="text" id="com_content" class="form-control" name="com_content" required><?php if (isset($com_content)) echo $com_content; echo set_value('com_content'); ?></textarea>
		</div><!-- end .form-group -->

		<div class="form-group">
			<label for="captcha">Captcha <?php echo $captcha_image; ?></label>
			<input type="text" class="form-control" id="captcha" name="captcha" value="" required />
		</div><!-- end .form-group -->


		<input type="submit" class="btn btn-default" value="Envoyer" />

		</form>