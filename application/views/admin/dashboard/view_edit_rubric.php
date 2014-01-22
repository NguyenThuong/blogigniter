<?php 
	if (validation_errors()):
		echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>');
	endif;

	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="r_title">Titre de la rubrique:</label>
		<input type="text" class="form-control" id="r_title" name="r_title" value="<?php if(isset($r_title)): echo $r_title; else: echo set_value('r_title'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="r_description">Description (256 caractères) de la rubrique :</label>
		<input type="text" id="r_description" class="form-control" name="r_description" value="<?php if(isset($r_description)): echo $r_description; else: echo set_value('r_description'); endif; ?>" required />
	</div><!-- end .form-group -->

	<input type="submit" class="btn btn-default" value="<?php if ($page == 'add_rubric'){ echo 'Ajouter';} else{ echo 'Modifier'; }; ?>" />

</form>