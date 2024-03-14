<?php
/**
 * @package Free_Education_Helper
 */
/*
Plugin Name: Free Education Helper
Description: Used for Free Education theme.
Version: 1.0.1
Author: scorpionthemes
Author URI: http://www.scorpionthemes.com/
License: GPLv2 or later
Text Domain: free-education-helper
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2018 Automattic, Inc.
*/

//If this file is ccalled directly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You idiot man!' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


/**
 * The code that runs during plugin activation
 */
function activate_free_education_helper_plugin() {
	require_once('inc/Base/Activate.php');
}
register_activation_hook( __FILE__, 'activate_free_education_helper_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_free_education_helper_plugin() {
	require_once('inc/Base/Deactivate.php');
}
register_deactivation_hook( __FILE__, 'deactivate_free_education_helper_plugin' );


require_once('inc/Base/FreeEducationRepeaterController.php');

require_once('inc/custom-post-types.php');
$free_education_theme = wp_get_theme();
if($free_education_theme->get( 'TextDomain' ) == 'free-education'):
	require_once('inc/user-avatar-class.php');
endif;


/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::register_services();
}