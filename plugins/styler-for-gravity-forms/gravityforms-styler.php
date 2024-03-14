<?php
/**
 * Plugin Name: Styler for Gravity Forms
 * Plugin URI: http://ideaboxcreations.com/
 * Description: Provide Gravity Forms styling options in customizer.
 * Version: 1.2.1
 * Author: IdeaBox Creations
 * Author URI: http://ideaboxcreations.com/
 * Copyright: (c) 2017 IdeaBox Creations
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gfs
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) )
{
   exit;
}

define( 'GFS_DIR', plugin_dir_path( __FILE__ ) );

if ( class_exists( 'GFForms' ) ) {
    require_once 'includes/customizer.php';
}
