<?php 
	echo form_open(current_url()); // $config['index_page'] = ''; dans config/config.php
?>

	<div class="form-group">
		<label for="c_title">Titre de l'article:</label>
		<input type="text" class="form-control" id="c_title" name="c_title" value="<?php if(isset($c_title)): echo $c_title; else: echo set_value('c_title'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="c_content">Contenu de l'article :</label>
		<textarea id="c_content" class="form-control" name="c_content" required><?php if(isset($c_content)): echo $c_content; else: echo set_value('c_content'); endif; ?></textarea>
	</div><!-- end .form-group -->

	<p>Rubriques :</p>
	<?php foreach ($rubrics->result() as $row): ?>
	<div class="radio">
		<label>
			<input type="radio" name="rubric" id="<?php echo $row->r_title ;?>" value="<?php echo $row->r_id ;?>" <?php if( $page == 'edit_content' and isset($rubrics) and $row->r_id == $r_id or set_value('rubrique') == $row->r_id ){ echo 'checked="checked"'; } ?> required />
			<?php echo $row->r_title; ?>
		</label>
	</div><!-- end .radio -->
	<?php endforeach; ?>

	<input type="submit" class="btn btn-default" value="<?php if ($page == 'add_content'){ echo 'Ajouter';} else{ echo 'Modifier'; }; ?>" />

</form>
