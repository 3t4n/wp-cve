<?php
/*
Plugin Name: 404 Page by SeedProd
Plugin URI: http://www.seedprod.com/wordpress-404-page-plugin/
Description: The Ultimate 404 Page Plugin
Version:  1.0.0
Author: SeedProd
Author URI: http://www.seedprod.com
TextDomain: seedprod
License: GPLv2
*/

/* Copyright 2015 SEEDPROD LLC (email : john@seedprod.com, twitter : @seedprod) */


/**
 * Default Constants
 */
define( 'SEED_S404F_SHORTNAME', 'seed_s404f' ); // Used to reference namespace functions.
define( 'SEED_S404F_SLUG', '404-page-seedprod/404-page-seedprod.php' ); // Used for settings link.
define( 'SEED_S404F_TEXTDOMAIN', 'seedprod' ); // i18 for reference only
define( 'SEED_S404F_PLUGIN_NAME', __( '404 Page by SeedProd', 'seedprod' ) ); // Plugin Name shows up on the admin settings screen.
define( 'SEED_S404F_VERSION', '1.0.0' ); // Plugin Version Number. Recommend you use Semantic Versioning http://semver.org/
define( 'SEED_S404F_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // Example output: /Applications/MAMP/htdocs/wordpress/wp-content/plugins/seed_csp3/
define( 'SEED_S404F_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // Example output: http://localhost:8888/wordpress/wp-content/plugins/seed_csp3/


/**
 * Load Translations
 */
function seed_s404f_load_textdomain() {
    load_plugin_textdomain( 'seedprod', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'seed_s404f_load_textdomain');




/**
 * Upon activation of the plugin set defaults
 *
 * @since 0.1.0
 */
function seed_s404f_activation(){
	require_once( 'includes/default-settings.php' );
	add_option('seed_s404f_settings_content',unserialize($seed_s404f_settings_defaults['seed_s404f_settings_content']));
	add_option('seed_s404f_settings_design',unserialize($seed_s404f_settings_defaults['seed_s404f_settings_design']));
	add_option('seed_s404f_settings_advanced',unserialize($seed_s404f_settings_defaults['seed_s404f_settings_advanced']));
}
register_activation_hook( __FILE__, 'seed_s404f_activation' );



/**
 * Load Required Files and Action
 */

 //Global

 require_once( 'includes/class-s404f.php' );
 require_once( 'includes/seed-s404f-plugin-template-loader.php' );
 require_once( 'includes/template-tags.php' );
 add_action( 'plugins_loaded', array( 'SEED_S404F', 'get_instance' ) );

/**
 * Set options global
 */
 // Global
 global $seed_s404f;

 require_once( 'framework/get-settings.php' );
 $seed_s404f = seed_s404f_get_settings();


 if( is_admin() ) {
// Admin Only
	require_once( 'includes/config-settings.php' );
    require_once( 'framework/framework.php' );
    add_action( 'plugins_loaded', array( 'SEED_S404F_ADMIN', 'get_instance' ) );
} else {
// Public only

}
