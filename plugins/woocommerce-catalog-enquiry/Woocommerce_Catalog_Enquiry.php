<?php
/**
 * Plugin Name: Product Catalog Enquiry
 * Plugin URI: https://multivendorx.com/
 * Description: Convert your WooCommerce store into a catalog website in a click
 * Author: MultiVendorX
 * Version: 5.0.7
 * Author URI: https://multivendorx.com/
 * WC requires at least: 4.2
 * WC tested up to: 8.5.2
 * Text Domain: woocommerce-catalog-enquiry
 * Domain Path: /languages/
*/

if ( ! class_exists( 'Woocommerce_Catalog_Enquiry_Dependencies' ) )
	require_once trailingslashit(dirname(__FILE__)).'includes/class-woocommerce-catalog-enquiry-dependencies.php';
require_once trailingslashit(dirname(__FILE__)).'includes/woocommerce-catalog-enquiry-core-functions.php';
require_once trailingslashit(dirname(__FILE__)).'woocommerce-catalog-enquiry-config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WOOCOMMERCE_CATALOG_ENQUIRY_PLUGIN_TOKEN')) exit;
if(!defined('WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN')) exit;
if(!Woocommerce_Catalog_Enquiry_Dependencies::woocommerce_active_check()) {
  add_action( 'admin_notices', 'woocommerce_catalog_enquiry_alert_notice' );
}
/* Plugin activation hook */
register_activation_hook(__FILE__, 'migration_from_previous');
/**
* Plugin page links
*/
function woocommerce_catalog_enquiry_plugin_links( $links ) {	
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=catalog#&tab=settings&subtab=general' ) . '">' . __( 'Settings', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>',
		'<a href="https://multivendorx.com/support-forum/forum/wcmp-catalog-enquiry/">' . __( 'Support', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>',			
	);	
	$links = array_merge( $plugin_links, $links );
	if ( apply_filters( 'woocommerce_catalog_enquiry_free_active', true ) ) {
        $links[] = '<a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" target="_blank">' . __( 'Upgrade to Pro', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>';
    }
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_catalog_enquiry_plugin_links' );

add_filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );

function plugin_row_meta( $links, $file ) {
    if($file == 'woocommerce-catalog-enquiry/Woocommerce_Catalog_Enquiry.php' && apply_filters( 'woocommerce_catalog_enquiry_free_active', true )){
        $row_meta = array(
            'pro'    => '<a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" title="' . esc_attr( __( 'Upgrade to Pro', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) ) . '">' . __( 'Upgrade to Pro', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>'
        );
        return array_merge( $links, $row_meta );
    }else{
        return $links;
    }
}

/*if(!Woocommerce_Catalog_Enquiry_Dependencies::woocommerce_catalog_enquiry_pro_active_check()) {*/
	if(!class_exists('Woocommerce_Catalog_Enquiry')) {
		require_once( trailingslashit(dirname(__FILE__)).'classes/class-woocommerce-catalog-enquiry.php' );
		global $Woocommerce_Catalog_Enquiry;
		$Woocommerce_Catalog_Enquiry = new Woocommerce_Catalog_Enquiry( __FILE__ );
		$GLOBALS['Woocommerce_Catalog_Enquiry'] = $Woocommerce_Catalog_Enquiry;
	}
//}
add_action ( 'before_woocommerce_init', function () {  
    if ( class_exists ( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility ( 'custom_order_tables', WP_CONTENT_DIR.'/plugins/woocommerce-catalog-enquiry/Woocommerce_Catalog_Enquiry.php', true );  
    } 
});
