<?php

/**
 * Update page.
 *
 * @link       https://shapedplugin.com/
 * @since      1.4.14
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Update version.
update_option( 'woo_category_slider_version', '1.4.15' );
update_option( 'woo_category_slider_db_version', '1.4.15' );

/**
 * Category slider query for id.
 */
$args       = new WP_Query(
	array(
		'post_type'      => 'sp_wcslider',
		'post_status'    => 'any',
		'posts_per_page' => '3000',
	)
);
$slider_ids = wp_list_pluck( $args->posts, 'ID' );

/**
 * Update metabox data along with previous data.
 */
if ( count( $slider_ids ) > 0 ) {
	foreach ( $slider_ids as $slider_id ) {
		$shortcode_meta = get_post_meta( $slider_id, 'sp_wcsp_shortcode_options', true );
		if ( ! is_array( $shortcode_meta ) ) {
			continue;
		}
		// Old color fields.
		$cat_shop_button_color             = isset( $shortcode_meta['wcsp_cat_shop_button_color'] ) ? $shortcode_meta['wcsp_cat_shop_button_color'] : '';
		$cat_shop_button_color_color       = isset( $cat_shop_button_color['color'] ) ? $cat_shop_button_color['color'] : '';
		$cat_shop_button_color_hover_color = isset( $cat_shop_button_color['hover_color'] ) ? $cat_shop_button_color['hover_color'] : '';

		$section_title_color = isset( $shortcode_meta['wcsp_section_title_color'] ) ? $shortcode_meta['wcsp_section_title_color'] : '';
		$description_color   = isset( $shortcode_meta['wcsp_description_color'] ) ? $shortcode_meta['wcsp_description_color'] : '';
		$cat_name_color      = isset( $shortcode_meta['wcsp_cat_name_color'] ) ? $shortcode_meta['wcsp_cat_name_color'] : '';
		$product_count_color = isset( $shortcode_meta['wcsp_product_count_color'] ) ? $shortcode_meta['wcsp_product_count_color'] : '';
		// Old color fields update to new fields in typography.
		if ( isset( $shortcode_meta['wcsp_shop_now_typography'] ) ) {
			$shortcode_meta['wpsp_section_title_typography']['color']  = $section_title_color;
			$shortcode_meta['wcsp_description_typography']['color']    = $description_color;
			$shortcode_meta['wcsp_cat_name_typography']['color']       = $cat_name_color;
			$shortcode_meta['wcsp_cat_name_typography']['hover-color'] = $cat_name_color;
			$shortcode_meta['wcsp_product_count_typography']['color']  = $product_count_color;
			$shortcode_meta['wcsp_shop_now_typography']['color']       = $cat_shop_button_color_color;
			$shortcode_meta['wcsp_shop_now_typography']['hover-color'] = $cat_shop_button_color_hover_color;
		}

		update_post_meta( $slider_id, 'sp_wcsp_shortcode_options', $shortcode_meta );
	}
}
