<?php 
	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="r_title">Titre de la rubrique:</label>
		<input type="text" class="form-control" id="r_title" name="r_title" value="<?php if (isset($r_title)) echo $r_title; echo set_value('r_title'); ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="r_description">Description (256 caractères) de la rubrique :</label>
		<input type="text" id="r_description" class="form-control" name="r_description" value="<?php if (isset($r_description)) echo $r_description; echo set_value('r_description'); ?>" required />
	</div><!-- end .form-group -->

	<?php if ($page == 'edit_rubric'): ?>
	<div class="form-group">
		<label for="r_url_rw">Url de la rubrique :</label>
		<input type="text" id="r_url_rw" class="form-control" name="r_url_rw" value="<?php if (isset($r_url_rw)) echo $r_url_rw; echo set_value('r_url_rw'); ?>" required />
	</div><!-- end .form-group -->
	<?php endif; ?>

	<input type="submit" class="btn btn-success" value="<?php if ($page == 'add_rubric') echo 'Ajouter'; else echo 'Modifier'; ?>" />

	<?php if ($page == 'edit_rubric'): ?>
		<?php if ($content->num_rows > 0): ?>
			<h3 id="others">Article(s) associé(s) à cette rubrique</h3>
			<ul class="unstyled">
			<?php foreach ($content->result() as $row): ?>
				<li><a href="<?php echo base_url('admin/dashboard/edit/' . $row->c_id); ?>"><?php echo $row->c_title; ?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p>Aucun article n'est rattaché à cette catégorie</p>
		<?php endif; ?>

	<?php endif; ?>

</form>