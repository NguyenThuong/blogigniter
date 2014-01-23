<?php 
	if (validation_errors()):
		echo validation_errors('<div class="alert alert-danger">', ' <a class="close" data-dismiss="alert" href="#">&times;</a></div>');
	endif;

	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="u_login">Login :</label>
		<input type="text" class="form-control" id="u_user" name="u_login" value="<?php if(isset($u_login)): echo $u_login; else: echo set_value('u_login'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="u_pass">Password :</label>
		<input type="text" id="u_pass" class="form-control" name="u_pass" value="<?php if(isset($u_pass)): echo $u_pass; else: echo set_value('u_pass'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<p>Level :</p>
		<?php
		$array_level = array(0 => "Modérateur", "Admin");
		foreach ($array_level as $key => $value): ?>
				<label class="radio" for="<?php echo strtolower($value); ?>"><?php echo $value; ?>
						<input type="radio" id="<?php echo strtolower($value); ?>" name="u_level" value="<?php echo $key; ?>" <?php if(isset($u_level) and $u_level == $key or set_value('u_level') == $key){ echo 'checked="checked"'; } ?> required />
				</label>
		<?php endforeach; ?>
	</div><!-- end .form-group -->

	<input type="submit" class="btn btn-default" value="<?php if ($page == 'add_user'){ echo 'Ajouter';} else{ echo 'Modifier'; }; ?>" />

</form>