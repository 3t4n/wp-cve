<?php
/**
 * Update version.
 */
update_option( 'woo_product_slider_version', SP_WPS_VERSION );
update_option( 'woo_product_slider_db_version', SP_WPS_VERSION );

/**
 * Shortcode query for id.
 */
$args = new WP_Query(
	array(
		'post_type'      => 'sp_wps_shortcodes',
		'post_status'    => 'publish',
		'posts_per_page' => '300',
	)
);

$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

// Check Quick View Plugin exist.
if ( is_plugin_active( 'woo-quickview/woo-quick-view.php' ) || is_plugin_active( 'woo-quick-view-pro/woo-quick-view-pro.php' ) ) {
	$quick_view_options = get_option( '_sp_wqvpro_options' );
	$enable_quick_view  = isset( $quick_view_options['wqvpro_enable_quick_view'] ) ? $quick_view_options['wqvpro_enable_quick_view'] : false;

	// Check if quick view button is enabled or not and shortcode id's total number is greater than 0.
	if ( $enable_quick_view && count( $shortcode_ids ) > 0 ) {
		foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
			$wpsp_shortcode_data = get_post_meta( $shortcode_id, 'sp_wps_shortcode_options', true );
			if ( ! is_array( $wpsp_shortcode_data ) ) { // Make sure that, It is an array.
				continue;
			}
			$wpsp_shortcode_data['quick_view'] = '1';
			update_post_meta( $shortcode_id, 'sp_wps_shortcode_options', $wpsp_shortcode_data );
		}
	}
}
