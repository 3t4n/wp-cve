<?php

if ( ! function_exists( 'dvmc_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function dvmc_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/DiviManychat.php';
}
add_action( 'divi_extensions_init', 'dvmc_initialize_extension' );
endif;
