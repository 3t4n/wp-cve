<?php
/**
 * Dynamic style for the plugin
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/public
 */

$cat_padding  = isset( $shortcode_meta['wcsp_cat_padding'] ) ? $shortcode_meta['wcsp_cat_padding'] : '';
$wcsp_options = get_option( 'sp_wcsp_settings' );

$layout_preset              = isset( $shortcode_meta['wcsp_layout_presets'] ) ? $shortcode_meta['wcsp_layout_presets'] : '';
$section_title              = isset( $shortcode_meta['wcsp_section_title'] ) && $shortcode_meta['wcsp_section_title'] ? $shortcode_meta['wcsp_section_title'] : false;
$cat_description            = isset( $shortcode_meta['wcsp_cat_description'] ) ? $shortcode_meta['wcsp_cat_description'] : '';
$cat_name                   = isset( $shortcode_meta['wcsp_cat_name'] ) ? $shortcode_meta['wcsp_cat_name'] : '';
$cat_product_count          = isset( $shortcode_meta['wcsp_cat_product_count'] ) ? $shortcode_meta['wcsp_cat_product_count'] : '';
$navigation                 = isset( $shortcode_meta['wcsp_navigation'] ) ? $shortcode_meta['wcsp_navigation'] : '';
$preloader                  = isset( $shortcode_meta['wcsp_preloader'] ) ? $shortcode_meta['wcsp_preloader'] : '';
$cat_product_count_position = isset( $shortcode_meta['wcsp_cat_product_count_position'] ) ? $shortcode_meta['wcsp_cat_product_count_position'] : '';
$cat_shop_now_button        = isset( $shortcode_meta['wcsp_cat_shop_now_button'] ) ? $shortcode_meta['wcsp_cat_shop_now_button'] : '';
$cat_shop_button_color      = isset( $shortcode_meta['wcsp_cat_shop_button_color'] ) ? $shortcode_meta['wcsp_cat_shop_button_color'] : '';
$border_box_shadow          = isset( $shortcode_meta['wcsp_cat_border_box_shadow'] ) ? $shortcode_meta['wcsp_cat_border_box_shadow'] : '';
$thumb_border               = isset( $shortcode_meta['wcsp_cat_thumb_border'] ) ? $shortcode_meta['wcsp_cat_thumb_border'] : '';
$section_title_margin       = isset( $shortcode_meta['wcsp_section_title_margin'] ) ? $shortcode_meta['wcsp_section_title_margin'] : '';
$thumb_margin               = isset( $shortcode_meta['wcsp_thumb_margin'] ) ? $shortcode_meta['wcsp_thumb_margin'] : '';


if ( $section_title ) {
	$section_title_color = isset( $shortcode_meta['wpsp_section_title_typography']['color'] ) ? $shortcode_meta['wpsp_section_title_typography']['color'] : '#444444';

	$section_title_margin_bottom = $section_title_margin['bottom'];
	if ( 'show' === $navigation && 'slider' === $layout_preset || 'hide_mobile' === $navigation && 'slider' === $layout_preset ) {
		$section_title_margin_bottom = $section_title_margin['bottom'] - 50;
	}
	$dynamic_style .= '.sp-wcsp-slider-area.sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-section-title {
		margin: ' . $section_title_margin['top'] . $section_title_margin['unit'] . ' ' . $section_title_margin['right'] . $section_title_margin['unit'] . ' ' . $section_title_margin_bottom . $section_title_margin['unit'] . ' ' . $section_title_margin['left'] . $section_title_margin['unit'] . ';
		color: ' . $section_title_color . ';
		font-size: 20px;
		line-height: 20px;
		letter-spacing: 0;
		text-transform: none;
		text-align: left;
		font-weight: 600;
	}';
}

if ( $cat_description ) {
	$description_margin = isset( $shortcode_meta['wcsp_description_margin'] ) ? $shortcode_meta['wcsp_description_margin'] : '';
	$description_color  = isset( $shortcode_meta['wcsp_description_typography']['color'] ) ? $shortcode_meta['wcsp_description_typography']['color'] : '#444444';
	$dynamic_style     .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-desc {
		margin: ' . $description_margin['top'] . $description_margin['unit'] . ' ' . $description_margin['right'] . $description_margin['unit'] . ' ' . $description_margin['bottom'] . $description_margin['unit'] . ' ' . $description_margin['left'] . $description_margin['unit'] . ';
		color: ' . $description_color . ';
		font-size: 14px;
		line-height: 18px;
		letter-spacing: 0;
		text-transform: none;
		text-align: center;
		padding: 0;
		font-weight: 400;
		font-style: normal;
	}';
}

if ( $cat_name ) {
	$cat_name_margin      = isset( $shortcode_meta['wcsp_cat_name_margin'] ) ? $shortcode_meta['wcsp_cat_name_margin'] : '';
	$product_count_margin = isset( $shortcode_meta['wcsp_product_count_margin'] ) ? $shortcode_meta['wcsp_product_count_margin'] : '';
	$cat_name_color       = isset( $shortcode_meta['wcsp_cat_name_typography']['color'] ) ? $shortcode_meta['wcsp_cat_name_typography']['color'] : '#444444';
	$cat_name_hover_color = isset( $shortcode_meta['wcsp_cat_name_typography']['hover-color'] ) ? $shortcode_meta['wcsp_cat_name_typography']['hover-color'] : '#444444';

	$dynamic_style .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content .sp-wcsp-cat-name {
		text-align: center;
	}
	.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content .sp-wcsp-cat-name a {
		margin: ' . $cat_name_margin['top'] . $cat_name_margin['unit'] . ' ' . $cat_name_margin['right'] . $cat_name_margin['unit'] . ' ' . $cat_name_margin['bottom'] . $cat_name_margin['unit'] . ' ' . $cat_name_margin['left'] . $cat_name_margin['unit'] . ';
		color: ' . $cat_name_color . ';
		font-size: 16px;
		line-height: 18px;
		letter-spacing: 0;
		text-transform: none;
		text-align: center;
		font-weight: 700;
		font-style: normal;
	}
	.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content .sp-wcsp-cat-name a:hover{
		color: ' . $cat_name_hover_color . ';
	}';
	if ( $cat_product_count && 'under_cat' === $cat_product_count_position ) {
		$product_count_color = isset( $shortcode_meta['wcsp_product_count_typography']['color'] ) ? $shortcode_meta['wcsp_product_count_typography']['color'] : '#777777';

		$dynamic_style .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content .sp-wcsp-product-count {
			margin: ' . $product_count_margin['top'] . $product_count_margin['unit'] . ' ' . $product_count_margin['right'] . $product_count_margin['unit'] . ' ' . $product_count_margin['bottom'] . $product_count_margin['unit'] . ' ' . $product_count_margin['left'] . $product_count_margin['unit'] . ';
			color: ' . $product_count_color . ';
			font-size: 14px;
			line-height: 20px;
			letter-spacing: 0;
			text-transform: none;
			text-align: center;
			font-weight: 400;
			font-style: normal;
		}';
	}
}

$make_it_card_style = isset( $shortcode_meta['wcsp_make_it_card_style'] ) ? $shortcode_meta['wcsp_make_it_card_style'] : '';
if ( $make_it_card_style ) {
	$cat_background = isset( $shortcode_meta['wcsp_cat_background'] ) ? $shortcode_meta['wcsp_cat_background'] : '';
	$cat_border     = isset( $shortcode_meta['wcsp_cat_border'] ) ? $shortcode_meta['wcsp_cat_border'] : '';
	$dynamic_style .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content {
		background: ' . $cat_background['background'] . ';
		border-top: ' . $cat_border['top'] . 'px;
		border-left: ' . $cat_border['left'] . 'px;
		border-right: ' . $cat_border['right'] . 'px;
		border-bottom: ' . $cat_border['bottom'] . 'px;
		border-style: ' . $cat_border['style'] . ';
		border-color: ' . $cat_border['color'] . ';
	}
	.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item:hover .sp-wcsp-cat-details .sp-wcsp-cat-details-content {
		background: ' . $cat_background['hover_background'] . ';
		border-color: ' . $cat_border['hover_color'] . ';
	}';
}

$cat_padding_top    = isset( $cat_padding['top'] ) ? $cat_padding['top'] : '';
$cat_padding_right  = isset( $cat_padding['right'] ) ? $cat_padding['right'] : '';
$cat_padding_bottom = isset( $cat_padding['bottom'] ) ? $cat_padding['bottom'] : '';
$cat_padding_left   = isset( $cat_padding['left'] ) ? $cat_padding['left'] : '';
$dynamic_style     .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-details .sp-wcsp-cat-details-content {
	padding: ' . $cat_padding_top . 'px ' . $cat_padding_right . 'px ' . $cat_padding_bottom . 'px ' . $cat_padding_left . 'px;
}';

$thumb_margin_unit   = isset( $thumb_margin['unit'] ) ? $thumb_margin['unit'] : '';
$thumb_margin_top    = isset( $thumb_margin['top'] ) ? $thumb_margin['top'] : '';
$thumb_margin_right  = isset( $thumb_margin['right'] ) ? $thumb_margin['right'] : '';
$thumb_margin_bottom = isset( $thumb_margin['bottom'] ) ? $thumb_margin['bottom'] : '';
$thumb_margin_left   = isset( $thumb_margin['left'] ) ? $thumb_margin['left'] : '';
$dynamic_style      .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-thumbnail {
	margin: ' . $thumb_margin_top . $thumb_margin_unit . ' ' . $thumb_margin_right . $thumb_margin_unit . ' ' . $thumb_margin_bottom . $thumb_margin_unit . ' ' . $thumb_margin_left . $thumb_margin_unit . ';
}';

// Navigation.
if ( 'hide_mobile' === $navigation ) {
	$dynamic_style .= '@media (max-width: 480px) {
		.sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-button {
			display: none;
		}
	}';
}
$nav_colors                     = isset( $shortcode_meta['wcsp_nav_colors'] ) ? $shortcode_meta['wcsp_nav_colors'] : '';
$nav_border                     = isset( $shortcode_meta['wcsp_nav_border'] ) ? $shortcode_meta['wcsp_nav_border'] : '';
$nav_border_all                 = isset( $nav_border['all'] ) ? $nav_border['all'] : '';
$nav_border_style               = isset( $nav_border['style'] ) ? $nav_border['style'] : '';
$nav_border_color               = isset( $nav_border['color'] ) ? $nav_border['color'] : '';
$nav_colors_color               = isset( $nav_colors['color'] ) ? $nav_colors['color'] : '';
$nav_colors_background          = isset( $nav_colors['background'] ) ? $nav_colors['background'] : '';
$nav_border_hover_color         = isset( $nav_border['hover_color'] ) ? $nav_border['hover_color'] : '';
$nav_colors_hover_color         = isset( $nav_colors['hover_color'] ) ? $nav_colors['hover_color'] : '';
$nav_colors_hover_background    = isset( $nav_colors['hover_background'] ) ? $nav_colors['hover_background'] : '';
$dynamic_style                 .= '.sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-button-prev, .sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-button-next{
	border: ' . $nav_border_all . 'px ' . $nav_border_style . ' ' . $nav_border_color . ';
	color: ' . $nav_colors_color . ';
	background: ' . $nav_colors_background . ';
	height: 30px;
	line-height: 28px;
	font-size: 20px;
	width: 30px;
}
.sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-button-prev:hover,
.sp-wcsp-slider-area-' . $post_id . ' .sp-wcsp-button-next:hover{
	border-color: ' . $nav_border_hover_color . ';
	color: ' . $nav_colors_hover_color . ';
	background: ' . $nav_colors_hover_background . ';
}';
$pagination_colors              = isset( $shortcode_meta['wcsp_pagination_colors'] ) ? $shortcode_meta['wcsp_pagination_colors'] : '';
$pagination_number_colors       = isset( $shortcode_meta['wcsp_pagination_number_colors'] ) ? $shortcode_meta['wcsp_pagination_number_colors'] : '';
$pagination_colors_color        = isset( $pagination_colors['color'] ) ? $pagination_colors['color'] : '';
$pagination_colors_active_color = isset( $pagination_colors['active_color'] ) ? $pagination_colors['active_color'] : '';
// Pagination.
$dynamic_style .= '#sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-pagination span {
	margin: 0 3px;
	width: 12px;
	height: 12px;
	background: ' . $pagination_colors_color . ';
	opacity: 1;
	font-size: 14px;
	text-indent: -999px;
	overflow: hidden;
}
#sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-pagination span.swiper-pagination-bullet-active {
	background: ' . $pagination_colors_active_color . ';
}';

// Shop Now button.
if ( $cat_shop_now_button ) {
	$cat_shop_button_border             = isset( $shortcode_meta['wcsp_cat_shop_button_border'] ) ? $shortcode_meta['wcsp_cat_shop_button_border'] : '';
	$cat_button_margin                  = isset( $shortcode_meta['wcsp_cat_button_margin'] ) ? $shortcode_meta['wcsp_cat_button_margin'] : '';
	$cat_button_margin_unit             = isset( $cat_button_margin['unit'] ) ? $cat_button_margin['unit'] : '';
	$cat_button_margin_top              = isset( $cat_button_margin['top'] ) ? $cat_button_margin['top'] : '';
	$cat_button_margin_right            = isset( $cat_button_margin['right'] ) ? $cat_button_margin['right'] : '';
	$cat_button_margin_bottom           = isset( $cat_button_margin['bottom'] ) ? $cat_button_margin['bottom'] : '';
	$cat_button_margin_left             = isset( $cat_button_margin['left'] ) ? $cat_button_margin['left'] : '';
	$cat_shop_button_border_all         = isset( $cat_shop_button_border['all'] ) ? $cat_shop_button_border['all'] : '';
	$cat_shop_button_border_style       = isset( $cat_shop_button_border['style'] ) ? $cat_shop_button_border['style'] : '';
	$cat_shop_button_border_color       = isset( $cat_shop_button_border['color'] ) ? $cat_shop_button_border['color'] : '';
	$cat_shop_button_border_hover_color = isset( $cat_shop_button_border['hover_color'] ) ? $cat_shop_button_border['hover_color'] : '';

	$cat_shop_button_typo             = isset( $shortcode_meta['wcsp_shop_now_typography'] ) ? $shortcode_meta['wcsp_shop_now_typography'] : '';
	$cat_shop_typo_button_color       = isset( $cat_shop_button_typo['color'] ) ? $cat_shop_button_typo['color'] : '#ffffff';
	$cat_shop_typo_button_hover_color = isset( $cat_shop_button_typo['hover-color'] ) ? $cat_shop_button_typo['hover-color'] : '#ffffff';

	$cat_shop_button_color_background       = isset( $cat_shop_button_color['background'] ) ? $cat_shop_button_color['background'] : '';
	$cat_shop_button_color_hover_background = isset( $cat_shop_button_color['hover_background'] ) ? $cat_shop_button_color['hover_background'] : '';

	$dynamic_style .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-shop-now {
		margin: ' . $cat_button_margin_top . $cat_button_margin_unit . ' ' . $cat_button_margin_right . $cat_button_margin_unit . ' ' . $cat_button_margin_bottom . $cat_button_margin_unit . ' ' . $cat_button_margin_left . $cat_button_margin_unit . ';
		border-width: ' . $cat_shop_button_border_all . 'px;
		border-style: ' . $cat_shop_button_border_style . ';
		border-color: ' . $cat_shop_button_border_color . ';
		color: ' . $cat_shop_typo_button_color . ';
		font-size: 15px;
		line-height: 20px;
		letter-spacing: 0;
		text-transform: none;
		text-align: center;
		font-weight: 700;
		font-style: normal;
		background: ' . $cat_shop_button_color['background'] . ';
		z-index: 99;
		position: relative;
	}
	.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-shop-now:hover {
		border-color: ' . $cat_shop_button_border_hover_color . ';
		color: ' . $cat_shop_typo_button_hover_color . ';
		background: ' . $cat_shop_button_color_hover_background . ';
	}';
}

// Preloader.
if ( $preloader ) {
	$dynamic_style .= '
	.sp-wcsp-slider-area-' . $post_id . '{
		position: relative;
	}
	#sp-wcsp-slider-section-' . $post_id . ' {
		opacity: 0;
	}
	#wcsp-preloader-' . $post_id . '{
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		width: 100%;
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	';
}

if ( is_array( $border_box_shadow ) ) {
	// Border.
	if ( in_array( 'border', $border_box_shadow, true ) ) {
		$dynamic_style .= '.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item .sp-wcsp-cat-thumbnail {
			border-top: ' . $thumb_border['top'] . 'px;
			border-right: ' . $thumb_border['right'] . 'px;
			border-bottom: ' . $thumb_border['bottom'] . 'px;
			border-left: ' . $thumb_border['left'] . 'px;
			border-style: ' . $thumb_border['style'] . ';
			border-color: ' . $thumb_border['color'] . ';
		}
		.sp-wcsp-slider-area #sp-wcsp-slider-section-' . $post_id . ' .sp-wcsp-cat-item:hover .sp-wcsp-cat-thumbnail {
			border-color: ' . $thumb_border['hover_color'] . ';
		}';
	}
}
