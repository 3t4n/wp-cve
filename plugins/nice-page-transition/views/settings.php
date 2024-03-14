<h2>Nice page transition settings</h2>
<form action="" method="post" class="leafs_form">

	<?php wp_nonce_field( 'npt_settings' ); ?>
	<label>Transition type: </label>
	<select name="type">
		<option value="">None (disabled)</option>
		<option value="brightness" <?php echo ($type == 'brightness' ? 'selected="selected"' : ''); ?>>Brightness</option>
		<option value="contrast" <?php echo ($type == 'contrast' ? 'selected="selected"' : ''); ?>>Contrast</option>
		<option value="opacity" <?php echo ($type == 'opacity' ? 'selected="selected"' : ''); ?>>Opacity</option>
		<option value="grayscale" <?php echo ($type == 'grayscale' ? 'selected="selected"' : ''); ?>>Grayscale</option>
		<option value="invert" <?php echo ($type == 'invert' ? 'selected="selected"' : ''); ?>>Invert</option>
		<option value="blur" <?php echo ($type == 'blur' ? 'selected="selected"' : ''); ?>>Blur</option>
	</select><br />
	<input type="image" src="<?php echo plugins_url('images/save.png', dirname(__FILE__)) ?>" />

</form>

<h3><br />Need help? <a href="http://www.info-d-74.com" target="_blank">Click for support</a> <br/>
and like InfoD74 to discover my new plugins: <a href="https://www.facebook.com/infod74/" target="_blank"><img src="<?php echo plugins_url( 'images/fb.png', dirname(__FILE__)) ?>" alt="" /></a></h3>