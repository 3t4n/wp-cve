<?php
function WooNotify_360Messenger_action_links( $links ) {

	$links = array_merge( array(
		'<a style="font-weight:bold;color:red;" href="' . esc_url( admin_url( '/admin.php?page=wooNotify-woocommerece-360Messenger-pro' ) ) . '">'.esc_html('پیکربندی').'</a>',
	), $links );

	return $links;

}
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'WooNotify_360Messenger_action_links' );

update_option( 'WooNotify_hide_about_page', '0' );


add_filter( 'plugin_row_meta', 'plugin_action_meta_links_360Messenger', 10, 2 );

function plugin_action_meta_links_360Messenger( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) ) {
        $links[] = '<a style="font-weight:bold;color:red;" href="' . esc_url( admin_url( '/admin.php?page=wooNotify-woocommerece-360Messenger-pro' ) ) . '">'.esc_html('پیکربندی').'</a>';
    }
    return $links;
}