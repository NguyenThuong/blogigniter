		<article class="thumbnail">
		<?php echo form_open(current_url()); // $config['index_page'] = ''; dans config/config.php ?>
			<label for="name">Nom</label><?php echo form_error('name', '<span style="color: red;">', '</span>'); ?>
			<br /><input type="text" name="name" id="name" placeholder="Votre nom" value="<?php echo set_value('name'); ?>" />
			<br /><label for="email">Email</label><?php echo form_error('email', '<span style="color: red">', '</span>'); ?>
			<br /><input type="text" name="email" id="email" placeholder="Votre email" value="<?php echo set_value('email'); ?>" />
			<br /><label for="message">Votre message</label><?php echo form_error('message', '<span style="color: red">', '</span>'); ?>
			<br /><textarea name="message" id="message" placeholder="Votre message"><?php echo set_value('message'); ?></textarea>
			<br /><input type="submit" value="Envoyer">
		</form>
		</article><!-- end of .thumbnail -->