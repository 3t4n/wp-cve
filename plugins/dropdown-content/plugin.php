<?php
/**
 * Plugin Name: Dropdown Content
 * Plugin URI: https://github.com/metaloha/dropdown-content
 * Description: Allows different content to be displayed based on the value of an author-defined drop-down box.
 * Version: 1.0.2
 * Author: Metaloha
 * Author URI: https://metaloha.com
 * Text Domain: dropdowncontent
 * Domain Path: /languages/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MDRDOCO__VERSION', '1.0.2' );
define( 'MDRDOCO__SLUG', 'dropdown-content' );
define( 'MDRDOCO__URL', plugin_dir_url( __FILE__ ) );
define( 'MDRDOCO__DIR', plugin_dir_path( __FILE__ ) );

require( MDRDOCO__DIR . 'dropdown-content.php' );

new DropdownContent();
