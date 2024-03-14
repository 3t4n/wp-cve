<?php

/**
 * Plugin shortcode
 * Author : WpBean
 */


/**
 * WooCommerce Category Slider ShortCode
 */

add_shortcode( 'wpb-woo-category-slider', 'wpb_wcs_shortcode' );

if( !function_exists('wpb_wcs_shortcode') ){
	function wpb_wcs_shortcode( $atts ) {

		$shortcode_atts =  shortcode_atts(
		array(
			'autoplay'				=> 'true',
			'items'					=> 4,
			'desktopsmall'			=> 3,
			'tablet'				=> 2,
			'mobile'				=> 1,
			'navigation'			=> 'true',
			'pagination'			=> 'true',
			'loop'					=> 'false',
			'type'					=> 'slider', // slider, grid
			'column'				=> '4', // bootstrap 4 columns
			'column_class'			=> '', // bootstrap formated css class
			'content_type'			=> 'plain_text', // plain_text, with_info, with_image, with_icon
			'need_description'		=> 'off',
			'need_child_cat'		=> 'off',
			'need_cat_count'		=> 'on',
			'need_btn'				=> 'off',
			'btn_text'				=> esc_html__( 'Shop Now', WPB_WCS_TEXTDOMAIN ),
			'order_by'				=> wpb_wcs_get_option( 'wpb_wcs_order_by', 'general_settings', 'name' ),
			'order'					=> wpb_wcs_get_option( 'wpb_wcs_order', 'general_settings', 'ASC' ),
			'hide_empty'			=> ( wpb_wcs_get_option( 'wpb_wcs_hide_empty', 'general_settings', 'off' ) == 'on' ? 1 : 0 ),
			'exclude'				=> wpb_wcs_get_option( 'wpb_wcs_exclude', 'general_settings', '' ),
			'include'				=> wpb_wcs_get_option( 'wpb_wcs_include', 'general_settings', '' ),
			'number'				=> wpb_wcs_get_option( 'wpb_wcs_number', 'general_settings', '' ),
			'parent'				=> 0, // If parent => 0 is passed, only top-level terms will be returned
		), $atts );

		wp_enqueue_script('owl-carousel');
		wp_enqueue_style('owl-carousel');
		wp_enqueue_script('wpb-wcs-main');
		wp_enqueue_style('wpb-wcs-main');
		wp_enqueue_style('font-awesoume');
		wp_enqueue_style('wpb-wcs-plugin-icons-collections');

		$args = array(
			'taxonomy'          => 'product_cat',
			'hide_empty'		=> $shortcode_atts['hide_empty'],
			'parent'        	=> $shortcode_atts['parent'],
			'orderby'        	=> $shortcode_atts['order_by'],
			'order'        		=> $shortcode_atts['order'],
			'exclude'        	=> ( $shortcode_atts['exclude'] != '' ? explode(',', $shortcode_atts['exclude']) : ''),
			'include'        	=> ( $shortcode_atts['include'] != '' ? explode(',', $shortcode_atts['include']) : ''),
			'number'        	=> $shortcode_atts['number'],
	    );

		$terms = get_terms( apply_filters( 'wpb_wcs_get_terms_args', $args ) );


		$loop_css_classes = '';

		if( $shortcode_atts['type'] == 'grid' ){
			if( $shortcode_atts['column_class'] ){
				$loop_css_classes = $shortcode_atts['column_class'];
			}else {
				if( $shortcode_atts['column'] ){
					$column = 12/$shortcode_atts['column'];
				}else{
					$column = '3';
				}
				$loop_css_classes = apply_filters( 'wpb_wcs_column_class', 'col-lg-' . $column . ' col-md-4 col-sm-6' );
			}
		}

		// Template loader instantiated elsewhere, such as the main plugin file.
		$wpb_wcs_template_loader = new WPB_WCS_Template_Loader;
		$data = array( 'terms' => $terms, 'atts' => $shortcode_atts, 'loop_css_classes' => $loop_css_classes );

		ob_start();
		
		if( $terms ){

			if( $shortcode_atts['type'] == 'slider' ){

				$wpb_wcs_template_loader->set_template_data( $data );
				$wpb_wcs_template_loader->get_template_part( 'slider' );

			}else{

				$wpb_wcs_template_loader->set_template_data( $data );
				$wpb_wcs_template_loader->get_template_part( 'grid' );
			}

		}

		return ob_get_clean();
	}
}