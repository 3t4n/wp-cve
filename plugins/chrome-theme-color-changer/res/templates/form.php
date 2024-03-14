<div class='wrap'>
	<h1>Chrome Theme Color Changer Settings</h1>

	<form action="" method="post" class="chrome-theme-color-changer-form">
		<?php wp_nonce_field('chrome-theme-color-changer-key', 'chrome-theme-color-changer'); ?>

		<div class="form-select-color">
			<label for="color-selector">Theme Color: </label>
			<input id="color-selector" type="text" name="color" value="<?php echo esc_attr(get_option('chrome-theme-color-changer-color')); ?>" class="jscolor color-picker">
			<div class="color-preview"></div>
		</div>

		<input type="hidden" name="update" value="update">

		<?php submit_button(); ?>
	</form>
</div>
