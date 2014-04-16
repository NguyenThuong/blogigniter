<?php 
	echo form_open(current_url()); // $config['index_page'] = ''; dans config/config.php
?>

	<div class="row">

		<div class="row">

			<select name="" id="type">
				<option value="article">Article</option>
				<option value="diaporama">Diaporama</option>
			</select>

			<div class="col-md-6">
				<div class="form-group">
					<p>Etat</p>
					<?php
					$array_state = array(0 => "Brouillon", "Publié");
					foreach ($array_state as $key => $value): ?>
						<label class="radio" for="<?php echo strtolower($value); ?>"><?php echo $value; ?>
							<input type="radio" id="<?php echo strtolower($value); ?>" name="c_state" value="<?php echo $key; ?>" <?php if(isset($c_state) and $c_state == $key or set_value('c_state') == $key) echo 'checked="checked"'; ?> required />
						</label>
					<?php endforeach; ?>
				</div><!-- end .form-group -->

		<?php if ($page == 'edit_content'): ?>
			<input type="checkbox" name="c_udate" id="c_udate" value="1" <?php echo set_checkbox('c_udate', '1'); ?> checked="checked" />
			<label for="c_udate">Mettre à jour la date de modification</label>
		</div>
		<div class="col-md-6 text-right">
			<p class="show-img">
				Image actuelle
				<br />
				<?php if (!empty($c_image)): ?>
				<img  src="<?php echo $c_image; ?>" alt="" width="128px" heigth="128" />
				<?php else: ?>
				<em>Aucune image</em>
				<?php endif; ?>
			</p>
		</div>
		
		<?php else: ?>
			<div id="datetimepicker" class="form-group" data-date="<?php echo date("d-m-y"); ?>" data-date-format="dd-mm-yyyy">
			<label for="c_cdate">Date de l'article</label>
				<input type="text" class="form-control" name="c_cdate" id="c_cdate" value="">
			</div><!-- end .form-group -->
			</div>
		<?php endif; ?>
		</div>

		<div class="form-group">
		<?php if ($page == 'add_content'): ?>
		<p class="show-img"><b>Image de l'article</b></p>
		<?php endif; ?>

			<div class="get-img">
				<?php foreach ($images as $image): ?>
					<?php // faire un var_dump($query) pour comprendre
						$var = $image['relative_path'];
						$var = strstr($var, 'assets');
						$var = str_replace("\\","/", $var);
						$link_image = base_url($var . '/' . $image['name']);
					?>
					<input type="radio" name="c_image" id="<?php echo $image['name']; ?>" value="<?php echo $link_image; ?>" <?php if(isset($c_image) && $c_image == $link_image) echo 'checked="checked"'; ?>>
					<label for="<?php echo $image['name']; ?>"><img class="img-thumbnail" src="<?php echo $link_image; ?>" alt="<?php echo $image['name']; ?>" /></label>
				<?php endforeach; ?>
				<p class="to_hide">Cacher</p>
			</div>
		</div><!-- end .form-group -->

		<div class="form-group">
			<label for="c_title">Titre de l'article</label>
			<input type="text" class="form-control" id="c_title" name="c_title" value="<?php if (isset($c_title)) echo $c_title; ?>" required />
		</div><!-- end .form-group -->

		<div class="form-group">
			<label for="c_content">Contenu de l'article</label>
			<textarea id="c_content" class="form-control" name="c_content" required><?php if (isset($c_content)) echo $c_content; echo set_value('c_content'); ?></textarea>
		</div><!-- end .form-group -->

		<?php if ($page == 'edit_content'): ?>
		<div class="form-group">
			<label for="c_url_rw">Url de l'article</label>
			<input type="text" id="c_url_rw" class="form-control" name="c_url_rw" value="<?php if (isset($c_url_rw)) echo $c_url_rw; echo set_value('c_url_rw'); ?>" required />
		</div><!-- end .form-group -->
		<?php endif; ?>

		<div class="row">

			<div class="col-md-6">
				<p><b>Rubriques</b></p>
				<?php foreach ($rubrics->result() as $row): ?>
				<div class="radio">
					<label>
						<input type="radio" name="rubric" id="<?php echo $row->r_title ;?>" value="<?php echo $row->r_id ;?>" <?php if ($page == 'edit_content' and isset($rubrics) and $row->r_id == $r_id or set_value('rubrique') == $row->r_id) echo 'checked="checked"'; ?> required />
						<?php echo $row->r_title; ?>
					</label>
				</div><!-- end .radio -->
				<?php endforeach; ?>
			</div>

			<div class="col-md-6">
				<label for="u_login">Auteur</label>
				<select class="form-control" id="u_login" name="u_id">
				<?php foreach ($users->result() as $user): ?>
					<option value="<?php echo $user->u_id; ?>" <?php if ($page == 'edit_content' and isset($users) and $user->u_id == $u_id or set_value('u_id') == $user->u_id) echo 'selected="selected"'; ?>><?php echo $user->u_login; ?></option>
				<?php endforeach; ?>
				</select>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label for="c_tags">Tag</label>
					<input type="text" value="<?php if (isset($c_tags)) echo $c_tags; echo set_value('c_tags'); ?>" name="c_tags" id="c_tags" class="form-control" />
				</div>
			</div>

		</div>
		<input type="submit" class="btn btn-success" value="<?php if ($page == 'add_content') echo 'Ajouter'; else echo 'Modifier'; ?>" />

	</div>
</form>