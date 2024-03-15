<?php
/*
Plugin Name: Kopa Nictitate Toolkit
Plugin URI: http://kopatheme.com
Description: A specific plugin use in Nictitate Theme to help you register post types and shortcodes.
Version: 1.0.2
Author: Kopatheme
Author URI: http://kopatheme.com
License: GPLv3

Kopa Nictitate Toolkit plugin, Copyright 2014 Kopatheme.com
Kopa Nictitate Toolkit is distributed under the terms of the GNU GPL
*/

/*
 * Plugin domain
 */
function kopa_nictitate_toolkit_init() {
    load_plugin_textdomain( 'kopa-nictitate-toolkit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'kopa_nictitate_toolkit_init');

/*
 * Register post types
 */
require plugin_dir_path( __FILE__ ) . 'posttype/service.php';
require plugin_dir_path( __FILE__ ) . 'posttype/portfolio.php';
require plugin_dir_path( __FILE__ ) . 'posttype/client.php';
require plugin_dir_path( __FILE__ ) . 'posttype/testimonial.php';
require plugin_dir_path( __FILE__ ) . 'posttype/staff.php';
require plugin_dir_path( __FILE__ ) . 'posttype/options_client.php';
require plugin_dir_path( __FILE__ ) . 'posttype/options_testimonial.php';
require plugin_dir_path( __FILE__ ) . 'posttype/options_service.php';
require plugin_dir_path( __FILE__ ) . 'posttype/options_staff.php';
require plugin_dir_path( __FILE__ ) . 'posttype/options_portfolio.php';

/*
 * Register shortcodes
 */
require plugin_dir_path( __FILE__ ) . 'kopa-shortcodes.php';