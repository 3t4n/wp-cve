<?php
if ( ! function_exists( 'plz_initialize_extension' ) ) :
	function plz_initialize_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-plezi-form-module.php';
	}
endif;
