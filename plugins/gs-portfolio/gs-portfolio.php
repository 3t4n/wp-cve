<?php
/**
 *
 * @package   GS_Portfolio
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2015 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:			GS Portfolio Lite
 * Plugin URI:			https://www.gsplugins.com/wordpress-plugins
 * Description:       	Best Responsive Portfolio plugin for your WordPress site. Display anywhere at your site using shortcode like [gs_portfolio hover_effect="effect-sadie"] Check Filterable Portfolio <a href="https://portfolio.gsplugins.com">Demo</a> and <a href="https://docs.gsplugins.com/gs-portfolio">Documention</a> 
 * Version:           	1.6.3
 * Author:       		GS Plugins
 * Author URI:       	https://www.gsplugins.com
 * Text Domain:       	gsportfolio
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 */

if( ! defined( 'GSPORTFOLIO_HACK_MSG' ) ) define( 'GSPORTFOLIO_HACK_MSG', __( 'Sorry cowboy! This is not your place', 'gsportfolio' ) );

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GSPORTFOLIO_HACK_MSG );

/**
 * Defining constants
 */
if( ! defined( 'GSPORTFOLIO_VERSION' ) ) define( 'GSPORTFOLIO_VERSION', '1.6.3' );
if( ! defined( 'GSPORTFOLIO_MENU_POSITION' ) ) define( 'GSPORTFOLIO_MENU_POSITION', 34 );
if( ! defined( 'GSPORTFOLIO_PLUGIN_DIR' ) ) define( 'GSPORTFOLIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'GSPORTFOLIO_PLUGIN_URI' ) ) define( 'GSPORTFOLIO_PLUGIN_URI', plugins_url( '', __FILE__ ) );
if( ! defined( 'GSPORTFOLIO_FILES_DIR' ) ) define( 'GSPORTFOLIO_FILES_DIR', GSPORTFOLIO_PLUGIN_DIR . 'gsportfolio-files' );
if( ! defined( 'GSPORTFOLIO_FILES_URI' ) ) define( 'GSPORTFOLIO_FILES_URI', GSPORTFOLIO_PLUGIN_URI . '/gsportfolio-files' );

if( !function_exists( 'remove_admin_notices' ) ) {
    function remove_admin_notices( ) {
        if ( isset( $_GET['post_type']) && $_GET['post_type'] === 'gs-portfolio' ) {
            remove_all_actions( 'network_admin_notices' );
            remove_all_actions( 'user_admin_notices' );
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }
}
add_action( 'in_admin_header',  'remove_admin_notices' );

function disable_portfolio_pro() {
    if ( is_plugin_active( 'gs-portfolio-pro/gs-portfolio.php' ) ) {
        deactivate_plugins( 'gs-portfolio-pro/gs-portfolio.php' );
    }
    add_option('gsportfolio_activation_redirect', true);
}

register_activation_hook( __FILE__, 'disable_portfolio_pro');

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_gs_portfolio() {

    if ( ! class_exists( 'AppSero\Insights' ) ) {
        require_once GSPORTFOLIO_FILES_DIR . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '714ad138-6b90-4549-bc3c-e8d66d5338e4', 'GS Portfolio', __FILE__  );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_gs_portfolio();

add_action( 'plugins_loaded', function() {
    require_once GSPORTFOLIO_FILES_DIR . '/includes/gs-portfolio-root.php';
}, -999999 );
