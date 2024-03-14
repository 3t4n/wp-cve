<?php
/**
 * @package   	      Ninja Forms Signature Contract Add-On
 * @contributors      Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me), Arafat Rahman (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       Ninja Forms Signature Contract Add-On by ApproveMe.com
 * Plugin URI:        http://aprv.me/2ko4gfL
 * Description:       This add-on makes it possible to automatically email a WP E-Signature contract (or redirect a user to a contract) after the user has successfully submitted a Ninja Form. You can also insert data from the submitted Ninja Form into the WP E-Signature contract.
 * Version:           3.1.8.0
 * Author:            ApproveMe.com
 * Author URI:        http://aprv.me/2ko4gfL
 * Text Domain:       esig-nfds
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.com/terms-conditions/
 * Privacy Policy: http://www.approveme.com/privacy-policy/
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */
require_once( plugin_dir_path(__FILE__) . 'includes/esig-nf-functions.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/esig-nf-settings.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/esig-nfds.php' );
require_once( plugin_dir_path(__FILE__) . 'admin/esig-ninja-filters.php' );
//require_once( plugin_dir_path( __FILE__ ) . 'admin/nf-admin-controller.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */

register_activation_hook(__FILE__, array('ESIG_NFDS', 'activate'));
register_deactivation_hook(__FILE__, array('ESIG_NFDS', 'deactivate'));

require_once( plugin_dir_path( __FILE__ ) . 'admin/about/autoload.php' );

//if (is_admin()) {

require_once( plugin_dir_path(__FILE__) . 'admin/esig-nfds-admin.php' );
add_action('plugins_loaded', array('ESIG_NFDS_Admin', 'get_instance'));
add_action('plugins_loaded', array('esigNinjaFilters', 'instance'));
//add_action( 'plugins_loaded', array( 'esig_NF_Ajax_Controller', 'get_instance' ) );

require_once( plugin_dir_path(__FILE__) . 'includes/esig-ninjaform-document-view.php' );

function Load_ninja_esignature_actions($actions) {

    require_once( plugin_dir_path(__FILE__) . 'includes/esignature-action.php' );
    $actions[strtolower('E-Signature')] = new NF_Actions_Esignature();
    return $actions;
}

add_filter('ninja_forms_register_actions', 'Load_ninja_esignature_actions', 10, 1);

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_nfds_load_textdomain() {

    load_plugin_textdomain('esig-nfds', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'esig_nfds_load_textdomain');

require_once( plugin_dir_path( __FILE__ ) . 'admin/rating-widget/esign-rating-widget.php' );
add_action( 'plugins_loaded', array( 'esignRatingWidgetNinja', 'get_instance' ) );
