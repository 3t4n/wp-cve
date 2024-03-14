<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e('Masonry', 'so-masonry') ?></h2>

	<form action="options.php" method="POST">
		<?php do_settings_sections( 'siteorigin-masonry' ); ?>
		<?php settings_fields( 'siteorigin-masonry-group' ); ?>
		<?php submit_button(); ?>
	</form>
</div>