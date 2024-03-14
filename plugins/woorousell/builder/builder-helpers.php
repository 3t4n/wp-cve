<?php
/**
 * Builder Helper Functions
 *
 * @author 		MojofyWP
 * @package 	builder
 *
 */

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrslb_sample_func') ) :
/**
 * Sample
 *
 * @return string
 */
function wrslb_sample_func() {
	
	return apply_filters( 'wrslb_sample_func' , ( !empty( $output ) ? $output : '' ) );
}
endif;

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrslb_options_page_url') ) :
/**
 * Sample
 *
 * @return string
 */
function wrslb_options_page_url( $args = array() ) {
	
	$defaults = array(
		'page' => 'wrsl-builder', 
		'view' => 'overview',
	);

	$instance = wp_parse_args( $args, $defaults );
	extract( $instance );

	$url 	= admin_url( 'admin.php' );
	$count	= 1;
	
	if ( !empty( $instance ) && is_array( $instance ) ) {
		foreach ( $instance as $key => $value ) {
			if ( !empty( $value ) ) {
				$url .= ( $count == 1 ? '?' : '&' ) . $key . '=' . $value;
				$count++;
			}				
		}
	}

	return apply_filters( 'wrslb_options_page_url' , esc_url( $url ) , $instance );
}
endif;

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrslb_get_meta') ) :
/**
 * Get meta value
 *
 * @param array $args = array (
 * 		@type int id - post ID
 * 		@type string key - meta key
 * 		@type mixed default - default value
 * 		@type bool single - whether to return only single result
 * 		@type string prefix - meta key prefix
 * )
 * @return mixed
 */
function wrslb_get_meta( $args = array() ) {

	$defaults = array(
		'id' => null, 
		'key' => null,
		'default' => '',
		'single' => true,
		'prefix' => wrsl()->plugin_meta_prefix(),
		'esc' => null,
	);

	$instance = wp_parse_args( $args, $defaults );
	extract( $instance );

	if ( is_null( $id ) || is_null( $key ) )
		return;

	$value = get_post_meta( $id , $prefix . $key , $single );

	if ( isset( $value ) )
		$return = $value;
	else
		$return = $default;

	if ( !is_null( $esc ) ) {
		if ( $esc == 'attr' )
			$return = esc_attr( $return ); 
		elseif ( $esc == 'url' )
			$return = esc_url( $return ); 
	}

	return apply_filters( 'wrslb_get_meta' , $return , $instance );
}
endif;

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrsl_default_meta') ) :
/**
 * Lists of default meta value for Carousel Mojo
 *
 * @return array
 */
function wrsl_default_meta( $type = 'post' ) {

	$values = array(
			'carousel_type' => 'product',
			'col_bg' => '#F5F5F5',
			'btn_color' => '#454545',
			'price_bg' => null,
			'sale_badge_bg' => null,
			'box_style' => 'style-1',
			'text_style' => 'regular',
			'category' => 0,
			'category_relation' => 'IN',
			'show_media' => 'on',
			'show_titles' => 'on',
			'show_excerpts' => null,
			'show_price' => 'on',
			'show_badges' => 'on',
			'show_ratings' => 'on',
			'show_buy_button' => 'on',
			'content_align' => 'text-left',
			'excerpt_length' => 200,
			'posts_per_page' => 6,
			'order' => 'newest-first',
			'filter_by' => false,
			'filter_price_range_max' => 0,
			'filter_price_range_min' => 0,
			'filter_price_range_from' => 0,
			'filter_price_range_until' => 0,
			'hide_on_sale' => null,
			'hide_oos' => null,
			'related_products' => null,
            'total_col' => 3,
            'c_mode' => 'horizontal',
            'c_speed' => 500,
            'c_moveone' => 'on',
            'c_slidemargin' => 10,
            'c_randomstart' => null,
            'c_adaptiveheight' => null,
            'c_adaptiveheightspeed' => 500,
            'c_touchenabled' => 'on',
            'c_swipethreshold' => 50,
            'c_auto' => null,
            'c_pause' => 4000,
            'c_autohover' => null,
            'c_autodelay' => 0,
            'c_ticker' => null,
            'c_ticker_hover' => 'on',
            'controller_type' => 'center',
            'controller_color' => '#373737',
            'controller_icon' => 'caret',
		);

	return apply_filters( 'wrsl_default_meta' , $values , $type );
}
endif;

/* ------------------------------------------------------------------------------- */

if ( ! function_exists('wrslb_checkbox_meta') ) :
/**
 * Retrieve a list of checkboxes in meta settings
 *
 * @return array
 */
function wrslb_checkbox_meta() {

	$checkboxes = array(
            'show_media',
            'show_titles',
            'show_excerpts',
            'show_dates',
            'show_author',
            'show_tags',
            'show_categories',
			'show_call_to_action',
			'related_products',
			'hide_oos',
            'c_moveone',
            'c_randomstart',
            'c_adaptiveheight',
            'c_touchenabled',
            'c_auto',
            'c_autohover',
            'c_ticker',
            'c_ticker_hover',
            'show_price',
            'show_badges',
            'show_ratings',
            'show_buy_button',
		);
	
	return apply_filters( 'wrslb_checkbox_meta' , ( !empty( $output ) ? $output : '' ) );
}
endif;
