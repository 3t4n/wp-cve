<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	// Init Options Global
	global $w2a_options;
	
    // create regular HTML Object
	ob_start(); ?>

		<div class="wrap">

			<h2><?php _e('Web2application WooCommerce Setting Page', 'web2application'); ?></h2>

			<p><?php _e('Web2application WooCommerce Setting Page', 'web2application'); ?></p>

			<?php settings_fields('w2a_settings_group'); ?>

			

		</div>

	<?php

	echo ob_get_clean();
