<?php
/**
 * Plugin Name:       WPB Custom Tab Manager for WooCommerce
 * Plugin URI:        https://wpbean.com/downloads/wpb-woocommerce-custom-tab-manager-pro/
 * Description:       Customizing WooCommerce product tab is super easy with this plugin.
 * Version:           1.3
 * Author:            wpbean
 * Author URI:        https://wpbean.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpb-woocommerce-custom-tab-manager
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}


/**
 * Define constants
 */

if ( ! defined( 'WPB_WCTM_FREE_INIT' ) ) {
    define( 'WPB_WCTM_FREE_INIT', plugin_basename( __FILE__ ) );
}

/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_WCTM_PREMIUM' ) ) {
    function wpb_wctm_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Accordion Menu or Category while you are using the premium one.', 'wpb-woocommerce-custom-tab-manager' ); ?></p>
        </div>
    <?php
    }

    add_action( 'admin_notices', 'wpb_wctm_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}


/**
 * Plugin Action Links
 */


function wpb_wctm_add_action_links ( $links ) {

	$links[] = '<a target="_blank" style="color: #39b54a; font-weight: 700;" target="_blank" href="'. esc_url( 'http://bit.ly/1VYKvqV' ) .'">'. esc_html__( 'Go PRO!', 'wpb-woocommerce-custom-tab-manager' ) .'</a>';

	return $links;
}


/**
 * Pro version discount
 */

function wpb_wctm_pro_discount_admin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'wpb_wctm_pro_discount_dismissed' ) ){
        printf('<div class="wpb-wctm-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'wpb-woocommerce-custom-tab-manager' ), 'https://wpbean.com/downloads/wpb-woocommerce-custom-tab-manager-pro/', esc_html__( 'WPB Custom Tab Manager for WooCommerce', 'wpb-woocommerce-custom-tab-manager' ), esc_html__( 'Use discount code - ', 'wpb-woocommerce-custom-tab-manager' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-wctm-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', 'wpb-woocommerce-custom-tab-manager' ));
    }
}


function wpb_wctm_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-wctm-pro-discount-admin-notice-dismissed'] ) ){
        add_user_meta( $user_id, 'wpb_wctm_pro_discount_dismissed', 'true', true );
    }
}


/**
 * Plugin Deactivation
 */

function wpb_wctm_lite_plugin_deactivation() {
  $user_id = get_current_user_id();
  if ( get_user_meta( $user_id, 'wpb_wctm_pro_discount_dismissed' ) ){
  	delete_user_meta( $user_id, 'wpb_wctm_pro_discount_dismissed' );
  }
}



/**
 * Plugin Init
 */

function wpb_wctm_free_plugin_init(){
	load_plugin_textdomain( 'wpb-woocommerce-custom-tab-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	register_deactivation_hook( plugin_basename( __FILE__ ), 'wpb_wctm_lite_plugin_deactivation' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_wctm_add_action_links' );
	add_action( 'admin_notices', 'wpb_wctm_pro_discount_admin_notice' );
	add_action( 'admin_init', 'wpb_wctm_pro_discount_admin_notice_dismissed' );

	require_once dirname( __FILE__ ) . '/inc/class.wpb-woocommerce-custom-tab-manager.php';
	require_once dirname( __FILE__ ) . '/admin/meta-box/meta_box.php';
	require_once dirname( __FILE__ ) . '/admin/meta-box/class.wpb-wctm-meta-box-config.php';
}
add_action( 'plugins_loaded', 'wpb_wctm_free_plugin_init' );