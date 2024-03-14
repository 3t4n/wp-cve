<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package woo-product-slider.
 * @subpackage woo-product-slider/Frontend.
 */

namespace ShapedPlugin\WooProductSlider\Frontend;

/**
 * Helper
 */
class Helper {

	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function wps_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'woo-product-slider/templates';
		}
		if ( ! $default_path ) {
			$default_path = SP_WPS_PATH . 'Frontend/views/templates/';
		}
		$template = locate_template( trailingslashit( $template_path ) . $template_name );
		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}
	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_with_space( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), ' ', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}


	/**
	 * Product custom query
	 *
	 * @param  mixed $product_order_by product order by.
	 * @param  mixed $product_type product type.
	 * @param  mixed $number_of_total_products how many product to show.
	 * @param  mixed $hide_out_of_stock_product hide out of stock product from query.
	 * @param  mixed $product_order product ordering.
	 * @param  mixed $grid_pagination Check grid pagination true or false.
	 * @param  mixed $grid_pagination_type The pagination type.
	 * @param  mixed $layout_preset The layout type.
	 * @param  mixed $products_per_page product per page.
	 * @param  mixed $post_id The post ID.
	 * @param  mixed $paged paged.
	 * @return object
	 */
	public static function spwps_product_query( $product_order_by, $product_type, $number_of_total_products, $hide_out_of_stock_product, $product_order, $grid_pagination, $grid_pagination_type, $layout_preset, $products_per_page, $post_id, $paged ) {
		$posts_per_page = $number_of_total_products;
		if ( $grid_pagination && ( 'slider' !== $layout_preset ) ) {
			$posts_per_page = $products_per_page;
		}
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		$arg                         = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'orderby'        => $product_order_by,
			'order'          => 'DESC',
			'fields'         => 'ids',
			'posts_per_page' => $number_of_total_products,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['exclude-from-catalog'],
					'operator' => 'NOT IN',
				),
			),
		);
		if ( 'featured_products' === $product_type ) {
			$arg['tax_query'][]          = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['featured'],
			);
		}
		if ( $hide_out_of_stock_product ) {
			$arg['tax_query'][]          = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['outofstock'],
				'operator' => 'NOT IN',
			);
		}
		$viewed_products = apply_filters( 'sp_wps_product_ids', get_posts( $arg ), $post_id );
		// $viewed_products = get_posts(
		// $arg
		// );

		$args = array();
		if ( $viewed_products ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'orderby'        => $product_order_by,
				'order'          => $product_order,
				'post__in'       => $viewed_products,
				'posts_per_page' => $posts_per_page,
			);
		}

		$final_arg = $args;
		if ( $grid_pagination && ( 'slider' !== $layout_preset ) ) {
			$paged_arg = array(
				'paged' => $paged,
			);
			$final_arg = array_merge( $final_arg, $paged_arg );
		}

		return new \WP_Query( $final_arg );
	}

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $shortcode_data get all meta options.
	 * @param array $main_section_title shows section title.
	 */
	public static function spwps_html_show( $post_id, $shortcode_data, $main_section_title ) {
		$setting_options = get_option( 'sp_woo_product_slider_options' );
		// General Settings.
		$layout_preset  = isset( $shortcode_data['layout_preset'] ) ? $shortcode_data['layout_preset'] : 'slider';
		$theme_style    = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme_one';
		$template_style = isset( $shortcode_data['template_style'] ) ? $shortcode_data['template_style'] : 'pre-made';
		$template_class = 'pre-made' === $template_style ? $theme_style : 'custom-template';

		$product_type              = isset( $shortcode_data['product_type'] ) ? $shortcode_data['product_type'] : 'latest_products';
		$number_of_total_products  = isset( $shortcode_data['number_of_total_products'] ) ? $shortcode_data['number_of_total_products'] : 16;
		$hide_out_of_stock_product = isset( $shortcode_data['hide_out_of_stock_product'] ) ? $shortcode_data['hide_out_of_stock_product'] : false;
		$number_of_column          = isset( $shortcode_data['number_of_column'] ) ? $shortcode_data['number_of_column'] : array(
			'number1' => '4',
			'number2' => '3',
			'number3' => '2',
			'number4' => '1',
		);
		$product_order_by          = isset( $shortcode_data['product_order_by'] ) ? $shortcode_data['product_order_by'] : 'date';
		$product_order             = isset( $shortcode_data['product_order'] ) ? $shortcode_data['product_order'] : 'DESC';
		$preloader                 = isset( $shortcode_data['preloader'] ) ? $shortcode_data['preloader'] : false;

		// Slider Controls.
		$auto_play         = isset( $shortcode_data['carousel_auto_play'] ) && $shortcode_data['carousel_auto_play'] ? 'true' : 'false';
		$auto_play_speed   = isset( $shortcode_data['carousel_auto_play_speed'] ) ? $shortcode_data['carousel_auto_play_speed'] : 3000;
		$scroll_speed      = isset( $shortcode_data['carousel_scroll_speed'] ) ? $shortcode_data['carousel_scroll_speed'] : 600;
		$pause_on_hover    = isset( $shortcode_data['carousel_pause_on_hover'] ) && $shortcode_data['carousel_pause_on_hover'] ? 'true' : 'false';
		$carousel_infinite = isset( $shortcode_data['carousel_infinite'] ) && $shortcode_data['carousel_infinite'] ? 'true' : 'false';
		$rtl_mode          = isset( $shortcode_data['rtl_mode'] ) && $shortcode_data['rtl_mode'] ? 'true' : 'false';
		$the_rtl           = ( 'true' === $rtl_mode ) ? ' dir="rtl"' : ' dir="ltr"';
		// Navigation data.
		$carousel_navigation = isset( $shortcode_data['wps_carousel_navigation'] ) ? $shortcode_data['wps_carousel_navigation'] : array();
		$hide_on_mobile      = isset( $carousel_navigation['nav_hide_on_mobile'] ) ? $carousel_navigation['nav_hide_on_mobile'] : false;
		$navigation_data     = isset( $carousel_navigation['navigation_arrow'] ) ? $carousel_navigation['navigation_arrow'] : true;
		if ( $navigation_data ) {
			$navigation        = 'true';
			$navigation_mobile = 'true';
		} elseif ( $navigation_data && $hide_on_mobile ) {
			$navigation        = 'true';
			$navigation_mobile = 'false';
		} else {
			$navigation        = 'false';
			$navigation_mobile = 'false';
		}
		// Pagination.
		$carousel_pagination       = isset( $shortcode_data['wps_carousel_pagination'] ) ? $shortcode_data['wps_carousel_pagination'] : array();
		$pagination_hide_on_mobile = isset( $carousel_pagination['wps_pagination_hide_on_mobile'] ) ? $carousel_pagination['wps_pagination_hide_on_mobile'] : false;
		$pagination_data           = isset( $carousel_pagination['pagination'] ) ? $carousel_pagination['pagination'] : true;
		if ( $pagination_data ) {
			$pagination        = 'true';
			$pagination_mobile = 'true';
		} elseif ( $pagination_data && $pagination_hide_on_mobile ) {
			$pagination        = 'true';
			$pagination_mobile = 'false';
		} else {
			$pagination        = 'false';
			$pagination_mobile = 'false';
		}

		$carousel_swipe       = isset( $shortcode_data['carousel_swipe'] ) && $shortcode_data['carousel_swipe'] ? 'true' : 'false';
		$carousel_draggable   = isset( $shortcode_data['carousel_draggable'] ) && $shortcode_data['carousel_draggable'] ? 'true' : 'false';
		$carousel_free_mode   = isset( $shortcode_data['carousel_free_mode'] ) && $shortcode_data['carousel_free_mode'] ? 'true' : 'false';
		$carousel_mouse_wheel = isset( $shortcode_data['carousel_mouse_wheel'] ) && $shortcode_data['carousel_mouse_wheel'] ? 'true' : 'false';
		$adaptive_height      = isset( $shortcode_data['carousel_adaptive_height'] ) && $shortcode_data['carousel_adaptive_height'] ? 'true' : 'false';
		$carousel_tab_key_nav = isset( $shortcode_data['carousel_tab_key_nav'] ) && $shortcode_data['carousel_tab_key_nav'] ? 'true' : 'false';
		$product_margin       = isset( $shortcode_data['product_margin']['all'] ) ? (int) $shortcode_data['product_margin']['all'] : 20;

		// Display Options.
		$slider_title              = isset( $shortcode_data['slider_title'] ) ? $shortcode_data['slider_title'] : false;
		$product_name              = isset( $shortcode_data['product_name'] ) ? $shortcode_data['product_name'] : true;
		$product_price             = isset( $shortcode_data['product_price'] ) ? $shortcode_data['product_price'] : true;
		$product_rating            = isset( $shortcode_data['product_rating'] ) ? $shortcode_data['product_rating'] : true;
		$add_to_cart_button        = isset( $shortcode_data['add_to_cart_button'] ) ? $shortcode_data['add_to_cart_button'] : true;
		$show_quick_view_button    = isset( $shortcode_data['quick_view'] ) ? $shortcode_data['quick_view'] : false;
		$grid_pagination           = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : true;
		$grid_pagination_type      = isset( $shortcode_data['grid_pagination_type'] ) ? $shortcode_data['grid_pagination_type'] : 'normal';
		$grid_pagination_alignment = isset( $shortcode_data['grid_pagination_alignment'] ) ? $shortcode_data['grid_pagination_alignment'] : 'wpspro-align-center';

		$products_per_page = isset( $shortcode_data['products_per_page'] ) ? $shortcode_data['products_per_page'] : '8';
		// Image Settings.
		$product_image   = isset( $shortcode_data['product_image'] ) ? $shortcode_data['product_image'] : '';
		$image_sizes     = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'full';
		$paged_var       = 'paged' . $post_id;
		$paged           = isset( $_GET[ "$paged_var" ] ) ? $_GET[ "$paged_var" ] : 1;
		$shortcode_query = self::spwps_product_query( $product_order_by, $product_type, $number_of_total_products, $hide_out_of_stock_product, $product_order, $grid_pagination, $grid_pagination_type, $layout_preset, $products_per_page, $post_id, $paged );

		$item_class = ( 'grid' === $layout_preset ) ? 'sp-wps-col-xl-' . $number_of_column['number1'] . ' sp-wps-col-lg-' . $number_of_column['number2'] . ' sp-wps-col-md-' . $number_of_column['number3'] . ' sp-wps-col-sm-' . $number_of_column['number4'] . '' : '';

		$slider_data = ' data-layout="' . $layout_preset . '"';
		$class       = 'wpsf-grid-item ';
		if ( 'slider' === $layout_preset ) {
			$class       = ' swiper-slide';
			$slider_data = 'data-swiper=\'{ "pauseOnHover": ' . $pause_on_hover . ', "infinite": ' . $carousel_infinite . ', "slidesToShow": ' . $number_of_column['number1'] . ', "speed": ' . $scroll_speed . ',"spaceBetween": ' . $product_margin . ', "autoplay": ' . $auto_play . ', "autoplaySpeed": ' . $auto_play_speed . ', "swipe": ' . $carousel_swipe . ', "draggable": ' . $carousel_draggable . ',"freeMode":' . $carousel_free_mode . ',"carousel_accessibility": ' . $carousel_tab_key_nav . ',"mousewheel": ' . $carousel_mouse_wheel . ',"adaptiveHeight": ' . $adaptive_height . ', "slidesPerView":{"lg_desktop":' . $number_of_column['number1'] . ', "desktop":' . $number_of_column['number2'] . ', "tablet":' . $number_of_column['number3'] . ', "mobile":' . $number_of_column['number4'] . '} }\'';
			wp_enqueue_script( 'sp-wps-swiper-js' );
		}
		include self::wps_locate_template( 'carousel.php' );
		wp_enqueue_script( 'sp-wps-scripts' );
	}
}
