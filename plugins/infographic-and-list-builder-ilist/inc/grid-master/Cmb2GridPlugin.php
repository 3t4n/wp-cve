<?php
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_init', 'cmb2_grid_deactivate' );

	if ( ! function_exists( 'cmb2_grid_deactivate' ) ) {
		/**
		 * Check for parent plugin.
		 */
		function cmb2_grid_deactivate() {
			$file = plugin_basename( __FILE__ );

			if ( is_admin() && current_user_can( 'activate_plugins' ) && is_plugin_active( plugin_basename( $file ) ) ) {
				//add_action( 'admin_notices', create_function( null, 'echo \'<div class="error"><p>\', __( \'Activation failed: The CMB2 Grid plugin requires PHP 5.3+. Please contact your webhost and ask them to upgrade the PHP version for your webhosting account.\', \'cmb2-grid\' ), \'</a></p></div>\';' ) );

				deactivate_plugins( $file, false, is_network_admin() );

				// Add to recently active plugins list.
				if ( ! is_network_admin() ) {
					update_option( 'recently_activated', array( $file => time() ) + (array) get_option( 'recently_activated' ) );
				} else {
					update_site_option( 'recently_activated', array( $file => time() ) + (array) get_site_option( 'recently_activated' ) );
				}

				// Prevent trying again on page reload.
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}
	}
} else {
	/* PHP 5.3+, so load the plugin.  */
	include_once dirname( __FILE__ ) . '/Cmb2GridPluginLoad.php';
}
