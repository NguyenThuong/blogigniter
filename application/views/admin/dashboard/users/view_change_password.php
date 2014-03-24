<?php 
	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="u_old_pass">Ancien Password :</label>
		<input type="password" id="u_old_pass" class="form-control" name="u_old_pass" value="<?php if (isset($u_old_pass)) echo $u_old_pass; echo set_value('u_old_pass'); ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="u_pass">Nouveau Password :</label>
		<input type="password" id="u_pass" class="form-control" name="u_pass" value="<?php if (isset($u_pass)) echo $u_pass; echo set_value('u_pass'); ?>" required />
	</div><!-- end .form-group -->
	
	<input type="submit" class="btn btn-success" value="Modifier" ?>

</form>