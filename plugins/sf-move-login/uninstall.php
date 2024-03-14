<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || die( 'Cheatin\' uh?' );


include_once( plugin_dir_path( __FILE__ ) . 'inc/classes/class-sfml-singleton.php' );
include_once( plugin_dir_path( __FILE__ ) . 'inc/classes/class-sfml-options.php' );

delete_site_option( 'sfml_version' );
delete_site_option( SFML_Options::OPTION_NAME );
