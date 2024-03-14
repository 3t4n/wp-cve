<?php

/**
 *
 * @package   GS_Behance_Portfolio
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2016 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:		GS Behance Portfolio
 * Plugin URI:		https://www.gsplugins.com/wordpress-plugins
 * Description:     The Behance plugin for WordPress is an outstanding tool for showcasing your Behance projects on your website. With this plugin, you can effortlessly display projects anywhere using a shortcode like [gs_behance id=1], or by using widgets. It provides a range of shortcode examples and detailed documentation to aid your setup. For more information, visit the <a href="https://behance.gsplugins.com">GS Behance Portfolio Demos</a> & <a href="https://docs.gsplugins.com/gs-behance-portfolio">Documentation</a>.
 * Version:			3.0.6
 * Author:			GS Plugins
 * Author URI:		https://www.gsplugins.com
 * Text Domain:		gs-behance
 * License:			GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 */
// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;
/**
 * Defining constants
 * 
 * @since 2.0.12
 */
define( 'GSBEH_VERSION', '3.0.6' );
define( 'GSBEH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GSBEH_PLUGIN_URI', plugins_url( '', __FILE__ ) );
define( 'GSBEHANCE_PLUGIN_FILE', __FILE__ );

if ( !function_exists( 'wbp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wbp_fs()
    {
        global  $wbp_fs ;
        
        if ( !isset( $wbp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wbp_fs = fs_dynamic_init( array(
                'id'             => '12686',
                'slug'           => 'wordpress-behance-plugin',
                'type'           => 'plugin',
                'public_key'     => 'pk_fa39621508041d4dbcc1653943fee',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 10,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'       => 'gs-behance-shortcode',
                'first-path' => 'admin.php?page=gs-behance-plugins-help',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wbp_fs;
    }
    
    // Init Freemius.
    wbp_fs();
    // Signal that SDK was initiated.
    do_action( 'wbp_fs_loaded' );
}


if ( !class_exists( 'GSBEH\\Autoloader' ) ) {
    require GSBEH_PLUGIN_DIR . 'includes/autoloader.php';
    GSBEH\Autoloader::init();
}

require_once GSBEH_PLUGIN_DIR . 'includes/plugin.php';
require_once GSBEH_PLUGIN_DIR . 'includes/functions.php';
register_deactivation_hook( __FILE__, function () {
    wp_clear_scheduled_hook( 'gs_task_hook' );
} );