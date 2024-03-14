<?php
/*
Plugin Name: Accedeme for WP
Text Domain: wp-accedeme
Domain Path: /languages
Plugin URI: https://accedeme.com/plataforma-framework/wordpress
Description: Añade a tu sitio WordPress el Widget de Accesibilidad de AccedeMe. Permite que cualquiera pueda acceder a tu página web independientemente de sus capacidades, conocimientos y del dispositivo usado.
Version: 0.5
Author: AccedeMe
Author URI: https://accedeme.com/
License: GPLv2 or later
Text Domain: accedeme
*/

define( 'ACCEDEME_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACCEDEME_URL', plugin_dir_url( __FILE__ ) );
define( 'ACCEDEME_TABLE_NAME', 'accedeme' );

load_plugin_textdomain( 'wp-accedeme', false, basename( dirname( __FILE__ ) ) . '/languages' );

require_once ACCEDEME_DIR . 'includes/wp-accedeme-activator.php';
register_activation_hook( __FILE__, array( 'wp_accedeme_activator', 'activate' ) );

require_once ACCEDEME_DIR . 'includes/wp-accedeme-deactivator.php';
register_deactivation_hook( __FILE__, array( 'wp_accedeme_deactivator', 'deactivate' ) );

require_once ACCEDEME_DIR . 'includes/wp-accedeme-uninstall.php';
register_uninstall_hook( __FILE__, array( 'wp_accedeme_uninstall', 'uninstall' ) );


require_once ACCEDEME_DIR . 'includes/wp-accedeme-footer.php';
$footer = new wp_accedeme_footer();

if ( is_admin() ) 
{
    require_once ACCEDEME_DIR . 'admin/wp-accedeme-admin.php';
    $admin = new wp_accedeme_admin();
}