<?php
/**
 * Plugin content area.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/public/layout/partials
 */

defined( 'ABSPATH' ) || exit;

$thumbnail_id = '';
if ( ! empty( get_term_meta( $wcs_term->term_id, 'thumbnail_id', true ) ) ) {
	$thumbnail_id = get_term_meta( $wcs_term->term_id, 'thumbnail_id', true );
}
$thumbnail_url_full       = wp_get_attachment_image_src( $thumbnail_id, 'full' );
$thumbnail_url            = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );
$thumbnail_src            = ! empty( $thumbnail_url ) ? $thumbnail_url[0] : '';
$thumbnail_data           = $thumbnail && $thumbnail_src ? '<div class="sp-wcsp-cat-thumbnail"><a href="' . esc_url( get_term_link( $wcs_term->term_id ) ) . '"><img src="' . $thumbnail_src . '" alt="' . esc_html( $wcs_term->name ) . '" class="sp-wcsp-cat-thumb"/></a></div>' : '';
$cat_product_count_data   = $cat_product_count ? $cat_product_count_before . esc_html( $wcs_term->count ) . $cat_product_count_after : '';
$beside_cat_product_count = $cat_product_count && 'beside_cat' == $cat_product_count_position ? $cat_product_count_data : '';
$under_cat_product_count  = $cat_name && $cat_product_count && 'under_cat' == $cat_product_count_position ? '<div class="sp-wcsp-product-count">' . $cat_product_count_data . '</div>' : '';
$cat_description_data     = $cat_description && $wcs_term->description ? '<div class="sp-wcsp-cat-desc">' . $wcs_term->description . '</div>' : '';
$cat_shop_now_button_data = $cat_shop_now_button ? '<div class="sp-wcsp-text-center" ><a href="' . esc_url( get_term_link( $wcs_term->term_id ) ) . '" class="sp-wcsp-shop-now" target="' . $cat_link_target . '">' . $cat_shop_now_button_text . '</a></div>' : '';
$cat_name_data            = $cat_name ? '<div class="sp-wcsp-cat-name"><a href="' . esc_url( get_term_link( $wcs_term->term_id ) ) . '">' . esc_html( $wcs_term->name ) . '' . $beside_cat_product_count . '</a></div>' . $under_cat_product_count : '';

$output .= '<div class="sp-wcsp-cat-item' . $item_class . '"><div class="sp-wcsp-cat-item-thumb-content">' . $thumbnail_data . '<div class="sp-wcsp-cat-details"><div class="sp-wcsp-cat-details-content">' . $cat_name_data;
$output .= $cat_description_data . $cat_shop_now_button_data . '</div></div></div></div>'; // sp-wcsp-cat-item.
