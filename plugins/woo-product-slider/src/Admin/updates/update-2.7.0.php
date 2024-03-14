<?php
/**
 * Update version.
*/
update_option( 'woo_product_slider_version', '2.7.0' );
update_option( 'woo_product_slider_db_version', '2.7.0' );

/**
 * Shortcode query for id.
 */
$args = new WP_Query(
	array(
		'post_type'      => 'sp_wps_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '3000',
	)
);

$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );
/**
 * Update metabox data along with previous data.
 */
if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_wps_shortcode_options', true );
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}

		// Carousel navigation enable and position.
		$old_nav_data = isset( $shortcode_data['navigation_arrow'] ) ? $shortcode_data['navigation_arrow'] : '';
		switch ( $old_nav_data ) {
			case 'true':
				$shortcode_data['wps_carousel_navigation']['navigation_arrow'] = true;
				break;
			case 'false':
				$shortcode_data['wps_carousel_navigation']['navigation_arrow'] = false;
				break;
			case 'hide_on_mobile':
				$shortcode_data['wps_carousel_navigation']['navigation_arrow']   = true;
				$shortcode_data['wps_carousel_navigation']['nav_hide_on_mobile'] = true;
				break;
		}
		$nav_arrow_border_color              = isset( $shortcode_data['navigation_arrow_colors']['border'] ) ? $shortcode_data['navigation_arrow_colors']['border'] : '#aaaaaa';
		$nav_arrow_border_hover_color        = isset( $shortcode_data['navigation_arrow_colors']['hover_border'] ) ? $shortcode_data['navigation_arrow_colors']['hover_border'] : '#444444';
		$shortcode_data['navigation_border'] = array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => $nav_arrow_border_color,
			'hover_color' => $nav_arrow_border_hover_color,
		);

		// Carousel pagination.
		$old_show_pagination = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : '';
		switch ( $old_show_pagination ) {
			case 'true':
				$shortcode_data['wps_carousel_pagination']['pagination'] = true;
				break;
			case 'false':
				$shortcode_data['wps_carousel_pagination']['pagination'] = false;
				break;
			case 'hide_on_mobile':
				$shortcode_data['wps_carousel_pagination']['pagination']                    = true;
				$shortcode_data['wps_carousel_pagination']['wps_pagination_hide_on_mobile'] = true;
				break;
		}
		$shortcode_data['carousel_ticker_mode'] = 'standard';

		update_post_meta( $shortcode_id, 'sp_wps_shortcode_options', $shortcode_data );
	}
}
