<?php 
	echo form_open(base_url(uri_string()));
?>

	<div class="form-group">
		<label for="p_title">Titre :</label>
		<input type="text" class="form-control" id="p_title" name="p_title" value="<?php if(isset($p_title)): echo $p_title; else: echo set_value('p_title'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="p_m_description">Description :</label>
		<input type="text" class="form-control" id="u_user" name="p_m_description" value="<?php if(isset($p_m_description)): echo $p_m_description; else: echo set_value('p_m_description'); endif; ?>" required />
	</div><!-- end .form-group -->

	<div class="form-group">
		<label for="p_about">A propos :</label>
		<textarea id="p_about" class="form-control" name="p_about" required><?php if(isset($p_about)): echo $p_about; else: echo set_value('p_about'); endif; ?></textarea>
	</div><!-- end .form-group -->

	<input type="submit" class="btn btn-default" value="Modifier" />

</form>