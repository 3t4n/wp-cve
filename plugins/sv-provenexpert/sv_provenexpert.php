<?php
/*
Version: 2.0.02
Plugin Name: SV ProvenExpert
Text Domain: sv_provenexpert
Description: Show rating stars via ProvenExpert.com in WordPress.
Plugin URI: https://straightvisions.com/
Author: straightvisions GmbH
Author URI: https://straightvisions.com
Domain Path: /languages
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

namespace sv_provenexpert;

if(!class_exists('\sv_dependencies\init')){
	require_once( 'lib/core_plugin/dependencies/sv_dependencies.php' );
}

if ( $GLOBALS['sv_dependencies']->set_instance_name( 'SV ProvenExpert' )->check_php_version() ) {
	require_once( dirname(__FILE__) . '/init.php' );
} else {
	$GLOBALS['sv_dependencies']->php_update_notification()->prevent_plugin_activation();
}