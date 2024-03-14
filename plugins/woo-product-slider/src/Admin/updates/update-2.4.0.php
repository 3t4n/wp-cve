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
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);

$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$wpsp_shortcode_data = get_post_meta( $shortcode_id, 'sp_wps_shortcode_options', true );

		/**
		 * Update old to new add to cart button color.
		 */
		$add_to_cart_button_color       = $wpsp_shortcode_data['add_to_cart_button_color'];
		$add_to_cart_button_bg          = $wpsp_shortcode_data['add_to_cart_button_bg'];
		$add_to_cart_button_hover_color = $wpsp_shortcode_data['add_to_cart_button_hover_color'];
		$add_to_cart_button_hover_bg    = $wpsp_shortcode_data['add_to_cart_button_hover_bg'];

		$wpsp_shortcode_data['add_to_cart_button_colors'] = array(
			'color'            => $add_to_cart_button_color,
			'hover_color'      => $add_to_cart_button_hover_color,
			'background'       => $add_to_cart_button_bg,
			'hover_background' => $add_to_cart_button_hover_bg,
		);

		/**
		 * Update old to new add to pagination dots color.
		 */
		$pagination_dots_bg        = $wpsp_shortcode_data['pagination_dots_bg'];
		$pagination_dots_active_bg = $wpsp_shortcode_data['pagination_dots_active_bg'];

		$wpsp_shortcode_data['pagination_dots_color'] = array(
			'color'        => $pagination_dots_bg,
			'active_color' => $pagination_dots_active_bg,
		);

		update_post_meta( $shortcode_id, 'sp_wps_shortcode_options', $wpsp_shortcode_data );
	}
}
