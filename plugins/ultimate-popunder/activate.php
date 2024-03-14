<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * The hook into activation for setup
 */
function ultimate_popunder_publisher_install()
{
	if ( ! current_user_can( 'activate_plugins' ) )
	{
		return;
	}

	// Add the version for upgrades
	if ( get_option( '_ultimate_popunder_version' ) === FALSE)
		add_option( '_ultimate_popunder_version', ULTIMATE_POPUNDER_VERSION );

}
register_activation_hook( __FILE__, 'ultimate_popunder_publisher_install' );

?>