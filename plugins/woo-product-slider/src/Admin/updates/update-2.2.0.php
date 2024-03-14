<?php
/**
 * Update version.
 *
 * @package Woo_Product_Slider
 */

update_option( 'woo_product_slider_version', SP_WPS_VERSION );
update_option( 'woo_product_slider_db_version', SP_WPS_VERSION );

/**
 * Update settings.
 */
$setting_options = array(
	'enqueue_font_awesome' => true,
	'enqueue_slick_css'    => true,
	'enqueue_slick_js'     => true,
	'custom_css'           => '',
);
update_option( 'sp_woo_product_slider_options', $setting_options );

/**
 * Shortcode query for id.
 */
$args          = new WP_Query(
	array(
		'post_type'      => 'sp_wps_shortcodes',
		'post_status'    => 'any',
		'posts_per_page' => '300',
	)
);
$shortcode_ids = wp_list_pluck( $args->posts, 'ID' );

if ( count( $shortcode_ids ) > 0 ) {
	foreach ( $shortcode_ids as $shortcode_key => $shortcode_id ) {

		$theme_style                    = get_post_meta( $shortcode_id, 'wps_themes', true );
		$number_of_column               = intval( get_post_meta( $shortcode_id, 'wps_number_of_column', true ) );
		$number_of_column_desktop       = intval( get_post_meta( $shortcode_id, 'wps_number_of_column_desktop', true ) );
		$number_of_column_tablet        = intval( get_post_meta( $shortcode_id, 'wps_number_of_column_tablet', true ) );
		$number_of_column_mobile        = intval( get_post_meta( $shortcode_id, 'wps_number_of_column_mobile', true ) );
		$number_of_total_products       = intval( get_post_meta( $shortcode_id, 'wps_number_of_total_products', true ) );
		$product_order_by               = get_post_meta( $shortcode_id, 'wps_order_by', true );
		$product_order                  = get_post_meta( $shortcode_id, 'wps_order', true );
		$auto_play                      = 'on' == get_post_meta( $shortcode_id, 'wps_auto_play', true ) ? true : false;
		$auto_play_speed                = intval( get_post_meta( $shortcode_id, 'wps_auto_play_speed', true ) );
		$scroll_speed                   = intval( get_post_meta( $shortcode_id, 'wps_scroll_speed', true ) );
		$pause_on_hover                 = 'on' == get_post_meta( $shortcode_id, 'wps_pause_on_hover', true ) ? true : false;
		$rtl_mode                       = 'on' == get_post_meta( $shortcode_id, 'wps_rtl', true ) ? true : false;
		$show_navigation                = 'on' == get_post_meta( $shortcode_id, 'wps_show_navigation', true ) ? 'true' : 'false';
		$nav_arrow_color                = get_post_meta( $shortcode_id, 'wps_nav_arrow_color', true );
		$nav_arrow_bg                   = get_post_meta( $shortcode_id, 'wps_nav_arrow_bg', true );
		$pagination                     = 'on' == get_post_meta( $shortcode_id, 'wps_show_pagination', true ) ? 'true' : 'false';
		$pagination_color               = get_post_meta( $shortcode_id, 'wps_pagination_color', true );
		$pagination_active_color        = get_post_meta( $shortcode_id, 'wps_pagination_active_color', true );
		$touch_swipe                    = 'on' == get_post_meta( $shortcode_id, 'wps_touch_swipe', true ) ? true : false;
		$mouse_draggable                = 'on' == get_post_meta( $shortcode_id, 'wps_mouse_draggable', true ) ? true : false;
		$slider_title                   = 'on' == get_post_meta( $shortcode_id, 'wps_slider_title', true ) ? true : false;
		$product_name                   = 'on' == get_post_meta( $shortcode_id, 'wps_product_name', true ) ? true : false;
		$product_price                  = 'on' == get_post_meta( $shortcode_id, 'wps_product_price', true ) ? true : false;
		$discount_price_color           = get_post_meta( $shortcode_id, 'wps_discount_price_color', true );
		$product_rating                 = 'on' == get_post_meta( $shortcode_id, 'wps_product_rating', true ) ? true : false;
		$add_to_cart                    = 'on' == get_post_meta( $shortcode_id, 'wps_add_to_cart', true ) ? true : false;
		$add_to_cart_color              = get_post_meta( $shortcode_id, 'wps_add_to_cart_color', true );
		$add_to_cart_hover_color        = get_post_meta( $shortcode_id, 'wps_add_to_cart_hover_color', true );
		$add_to_cart_bg                 = get_post_meta( $shortcode_id, 'wps_add_to_cart_bg', true );
		$add_to_cart_hover_bg           = get_post_meta( $shortcode_id, 'wps_add_to_cart_hover_bg', true );
		$add_to_cart_border_color       = get_post_meta( $shortcode_id, 'wps_add_to_cart_border_color', true );
		$add_to_cart_border_hover_color = get_post_meta( $shortcode_id, 'wps_add_to_cart_border_hover_color', true );
		$slider_title_color             = get_post_meta( $shortcode_id, 'wps_slider_title_color', true );
		$slider_title_font_size         = get_post_meta( $shortcode_id, 'wps_slider_title_font_size', true );
		$product_name_font_size         = get_post_meta( $shortcode_id, 'wps_product_name_font_size', true );
		$product_name_color             = get_post_meta( $shortcode_id, 'wps_product_name_color', true );
		$product_name_hover_color       = get_post_meta( $shortcode_id, 'wps_product_name_hover_color', true );
		$price_color                    = get_post_meta( $shortcode_id, 'wps_price_color', true );

		$wps_shortcode_data['carousel_type']                  = 'product_carousel';
		$wps_shortcode_data['layout_preset']                  = 'slider';
		$wps_shortcode_data['theme_style']                    = $theme_style;
		$wps_shortcode_data['product_type']                   = 'latest_products';
		$wps_shortcode_data['number_of_column']               = array(
			'number1' => $number_of_column,
			'number2' => $number_of_column_desktop,
			'number3' => $number_of_column_tablet,
			'number4' => $number_of_column_mobile,
		);
		$wps_shortcode_data['number_of_total_products']       = $number_of_total_products;
		$wps_shortcode_data['product_order_by']               = $product_order_by;
		$wps_shortcode_data['product_order']                  = $product_order;
		$wps_shortcode_data['preloader']                      = false;
		$wps_shortcode_data['carousel_auto_play']             = $auto_play;
		$wps_shortcode_data['carousel_auto_play_speed']       = $auto_play_speed;
		$wps_shortcode_data['carousel_scroll_speed']          = $scroll_speed;
		$wps_shortcode_data['carousel_pause_on_hover']        = $pause_on_hover;
		$wps_shortcode_data['carousel_infinite']              = true;
		$wps_shortcode_data['rtl_mode']                       = true;
		$wps_shortcode_data['navigation_arrow']               = $show_navigation;
		$wps_shortcode_data['navigation_arrow_colors']        = array(
			'color'            => $nav_arrow_color,
			'hover_color'      => $nav_arrow_color,
			'background'       => $nav_arrow_bg,
			'hover_background' => $nav_arrow_bg,
			'border'           => $nav_arrow_bg,
			'hover_border'     => $nav_arrow_bg,
		);
		$wps_shortcode_data['pagination']                     = $pagination;
		$wps_shortcode_data['pagination_dots_bg']             = $pagination_color;
		$wps_shortcode_data['pagination_dots_active_bg']      = $pagination_active_color;
		$wps_shortcode_data['carousel_swipe']                 = $touch_swipe;
		$wps_shortcode_data['carousel_draggable']             = $mouse_draggable;
		$wps_shortcode_data['slider_title']                   = $slider_title;
		$wps_shortcode_data['product_name']                   = $product_name;
		$wps_shortcode_data['product_price']                  = $product_price;
		$wps_shortcode_data['product_del_price_color']        = $discount_price_color;
		$wps_shortcode_data['product_rating']                 = $product_rating;
		$wps_shortcode_data['product_rating_colors']          = array(
			'color'       => '#F4C100',
			'empty_color' => '#C8C8C8',
		);
		$wps_shortcode_data['add_to_cart_button']             = $add_to_cart;
		$wps_shortcode_data['add_to_cart_button_color']       = $add_to_cart_color;
		$wps_shortcode_data['add_to_cart_button_hover_color'] = $add_to_cart_hover_color;
		$wps_shortcode_data['add_to_cart_button_bg']          = $add_to_cart_bg;
		$wps_shortcode_data['add_to_cart_button_hover_bg']    = $add_to_cart_hover_bg;
		$wps_shortcode_data['add_to_cart_border']             = array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => $add_to_cart_border_color,
			'hover_color' => $add_to_cart_border_hover_color,
		);
		$wps_shortcode_data['product_image']                  = true;
		$wps_shortcode_data['product_image_border']           = array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => '#dddddd',
			'hover_color' => '#dddddd',
		);
		$wps_shortcode_data['image_sizes']                    = 'full';
		$wps_shortcode_data['slider_title_typography']        = array(
			'font-family'    => 'Open Sans',
			'font-weight'    => '600',
			'type'           => 'google',
			'font-size'      => $slider_title_font_size,
			'line-height'    => '23',
			'text-align'     => 'left',
			'text-transform' => 'none',
			'letter-spacing' => '',
			'color'          => $slider_title_color,
		);
		$wps_shortcode_data['product_name_typography']        = array(
			'font-family'    => 'Open Sans',
			'font-weight'    => '600',
			'type'           => 'google',
			'font-size'      => $product_name_font_size,
			'line-height'    => '20',
			'text-align'     => 'center',
			'text-transform' => 'none',
			'letter-spacing' => '',
			'color'          => $product_name_color,
			'hover_color'    => $product_name_hover_color,
		);
		$wps_shortcode_data['product_price_typography']       = array(
			'font-family'    => 'Open Sans',
			'font-weight'    => '700',
			'type'           => 'google',
			'font-size'      => '14',
			'line-height'    => '19',
			'text-align'     => 'center',
			'text-transform' => 'none',
			'letter-spacing' => '',
			'color'          => $price_color,
		);

		update_post_meta( $shortcode_id, 'sp_wps_shortcode_options', $wps_shortcode_data );

		delete_post_meta( $shortcode_id, 'wps_slider_type', get_post_meta( $shortcode_id, 'wps_slider_type', true ) );
		delete_post_meta( $shortcode_id, 'wps_themes', get_post_meta( $shortcode_id, 'wps_themes', true ) );
		delete_post_meta( $shortcode_id, 'wps_products_from', get_post_meta( $shortcode_id, 'wps_products_from', true ) );
		delete_post_meta( $shortcode_id, 'wps_number_of_column', get_post_meta( $shortcode_id, 'wps_number_of_column', true ) );
		delete_post_meta( $shortcode_id, 'wps_number_of_column_desktop', get_post_meta( $shortcode_id, 'wps_number_of_column_desktop', true ) );
		delete_post_meta( $shortcode_id, 'wps_number_of_column_tablet', get_post_meta( $shortcode_id, 'wps_number_of_column_tablet', true ) );
		delete_post_meta( $shortcode_id, 'wps_number_of_column_mobile', get_post_meta( $shortcode_id, 'wps_number_of_column_mobile', true ) );
		delete_post_meta( $shortcode_id, 'wps_number_of_total_products', get_post_meta( $shortcode_id, 'wps_number_of_total_products', true ) );
		delete_post_meta( $shortcode_id, 'wps_order_by', get_post_meta( $shortcode_id, 'wps_order_by', true ) );
		delete_post_meta( $shortcode_id, 'wps_order', get_post_meta( $shortcode_id, 'wps_order', true ) );
		delete_post_meta( $shortcode_id, 'wps_auto_play', get_post_meta( $shortcode_id, 'wps_auto_play', true ) );
		delete_post_meta( $shortcode_id, 'wps_auto_play_speed', get_post_meta( $shortcode_id, 'wps_auto_play_speed', true ) );
		delete_post_meta( $shortcode_id, 'wps_scroll_speed', get_post_meta( $shortcode_id, 'wps_scroll_speed', true ) );
		delete_post_meta( $shortcode_id, 'wps_pause_on_hover', get_post_meta( $shortcode_id, 'wps_pause_on_hover', true ) );
		delete_post_meta( $shortcode_id, 'wps_show_navigation', get_post_meta( $shortcode_id, 'wps_show_navigation', true ) );
		delete_post_meta( $shortcode_id, 'wps_nav_arrow_color', get_post_meta( $shortcode_id, 'wps_nav_arrow_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_nav_arrow_bg', get_post_meta( $shortcode_id, 'wps_nav_arrow_bg', true ) );
		delete_post_meta( $shortcode_id, 'wps_show_pagination', get_post_meta( $shortcode_id, 'wps_show_pagination', true ) );
		delete_post_meta( $shortcode_id, 'wps_pagination_color', get_post_meta( $shortcode_id, 'wps_pagination_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_pagination_active_color', get_post_meta( $shortcode_id, 'wps_pagination_active_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_touch_swipe', get_post_meta( $shortcode_id, 'wps_touch_swipe', true ) );
		delete_post_meta( $shortcode_id, 'wps_mouse_draggable', get_post_meta( $shortcode_id, 'wps_mouse_draggable', true ) );
		delete_post_meta( $shortcode_id, 'wps_rtl', get_post_meta( $shortcode_id, 'wps_rtl', true ) );
		delete_post_meta( $shortcode_id, 'wps_slider_title', get_post_meta( $shortcode_id, 'wps_slider_title', true ) );
		delete_post_meta( $shortcode_id, 'wps_slider_title_font_size', get_post_meta( $shortcode_id, 'wps_slider_title_font_size', true ) );
		delete_post_meta( $shortcode_id, 'wps_slider_title_color', get_post_meta( $shortcode_id, 'wps_slider_title_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_name', get_post_meta( $shortcode_id, 'wps_product_name', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_name_font_size', get_post_meta( $shortcode_id, 'wps_product_name_font_size', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_name_color', get_post_meta( $shortcode_id, 'wps_product_name_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_name_hover_color', get_post_meta( $shortcode_id, 'wps_product_name_hover_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_price', get_post_meta( $shortcode_id, 'wps_product_price', true ) );
		delete_post_meta( $shortcode_id, 'wps_price_color', get_post_meta( $shortcode_id, 'wps_price_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_discount_price_color', get_post_meta( $shortcode_id, 'wps_discount_price_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_product_rating', get_post_meta( $shortcode_id, 'wps_product_rating', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart', get_post_meta( $shortcode_id, 'wps_add_to_cart', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_color', get_post_meta( $shortcode_id, 'wps_add_to_cart_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_hover_color', get_post_meta( $shortcode_id, 'wps_add_to_cart_hover_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_bg', get_post_meta( $shortcode_id, 'wps_add_to_cart_bg', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_hover_bg', get_post_meta( $shortcode_id, 'wps_add_to_cart_hover_bg', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_border_color', get_post_meta( $shortcode_id, 'wps_add_to_cart_border_color', true ) );
		delete_post_meta( $shortcode_id, 'wps_add_to_cart_border_hover_color', get_post_meta( $shortcode_id, 'wps_add_to_cart_border_hover_color', true ) );
	}
}
