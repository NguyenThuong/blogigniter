<?php 
	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="u_login">Login :</label>
		<input type="text" class="form-control" id="u_login" name="u_login" value="<?php if (isset($u_login)) echo $u_login; echo set_value('u_login'); ?>" required />
	</div><!-- end .form-group -->

	<?php if($page == 'add_user'): ?>
	<div class="form-group">
		<label for="u_pass">Password :</label>
		<input type="password" id="u_pass" class="form-control" name="u_pass" value="<?php if (isset($u_pass)) echo $u_pass; echo set_value('u_pass'); ?>" required />
	</div><!-- end .form-group -->
	<?php endif; ?>

	<div class="form-group">
		<label for="u_email">Email :</label>
		<input type="text" class="form-control" id="u_email" name="u_email" value="<?php if (isset($u_email)) echo $u_email; echo set_value('u_email'); ?>" required />
	</div><!-- end .form-group -->	

	<div class="form-group">
		<label for="u_biography">Biographie :</label>
		<input type="text" class="form-control" id="u_biography" name="u_biography" value="<?php if (isset($u_biography)) echo $u_biography; echo set_value('u_biography'); ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<p>Level :</p>
		<?php
		$array_level = array(0 => "Modérateur", "Admin");
		foreach ($array_level as $key => $value): ?>
			<label class="radio" for="<?php echo strtolower($value); ?>"><?php echo $value; ?>
				<input type="radio" id="<?php echo strtolower($value); ?>" name="u_level" value="<?php echo $key; ?>" <?php if(isset($u_level) and $u_level == $key or set_value('u_level') == $key) echo 'checked="checked"'; ?> required />
			</label>
		<?php endforeach; ?>
	</div><!-- end .form-group -->

	<input type="submit" class="btn btn-success" value="<?php if ($page == 'add_user') echo 'Ajouter'; else echo 'Modifier'; ?>" />

</form>