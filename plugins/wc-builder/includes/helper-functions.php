<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/*
 * HTML Tag list
 * return array
 */
function wpbforwpbakery_html_tag_lists() {
    $html_tag_list = [
        'h1'   => __( 'H1', 'wpbforwpbakery' ),
        'h2'   => __( 'H2', 'wpbforwpbakery' ),
        'h3'   => __( 'H3', 'wpbforwpbakery' ),
        'h4'   => __( 'H4', 'wpbforwpbakery' ),
        'h5'   => __( 'H5', 'wpbforwpbakery' ),
        'h6'   => __( 'H6', 'wpbforwpbakery' ),
        'p'    => __( 'p', 'wpbforwpbakery' ),
        'div'  => __( 'div', 'wpbforwpbakery' ),
        'span' => __( 'span', 'wpbforwpbakery' ),
    ];
    return $html_tag_list;
}

/*
 * Text align options
 * return array
 */
function wpbforwpbakery_text_align_lists(){
	$text_align_list = [
	      __( 'Left', 'my_text_domain' )  =>  'left',
	      __( 'Center', 'my_text_domain' )  =>  'center',
	      __( 'Right', 'my_text_domain' )  =>  'right',
	  ];

	return $text_align_list;
}

/*
 * Post Type ids list
 * return array
 */
function wpbforwpbakery_post_list_arr($post_type = 'post', $per_page = 100){
	$arr = array();
	$arr[] = __('Default', 'wpbforwpbakery');

	$args = array(
		'post_type' => $post_type,
		'posts_per_page'	=> $per_page
	);

	$query = new WP_Query($args);
	while($query->have_posts()){
		$query->the_post();
		$arr[get_the_id()] = get_the_title();
	}
	wp_reset_postdata();

	return $arr;
}

/*
 * Plugins Options value
 * return on/off
 */
function wpbforwpbakery_get_option( $option, $section, $default = '', $empty_check = false ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {

        if($empty_check){

            if( !empty($options[$option]) ){
                return $options[$option];
            }

            return $default;

        } else {
            return $options[$option];
        }
        
    }
    return $default;
}

/*
 * WPbakery Google Fonts
 * return array
 */
function wpbforwpbakery_get_fonts_data( $font_data ) {
	$FontsParam = new Vc_Google_Fonts();
	$fieldSettings = array();
	$font_data = strlen( $font_data ) > 0 ? $FontsParam->_vc_google_fonts_parse_attributes( $fieldSettings, $font_data ) : '';
	return $font_data;
}

/*
 * Generate Inline Style for google font
 * inline style
 */
function wpbforwpbakery_get_font_inline_style($font_data){
	$styles = array();

	// Inline styles
	$font_family = explode( ':', $font_data['values']['font_family'] );
	if( isset($font_family[0]) ){
		$styles[] = 'font-family:' . $font_family[0];
	}

	$fontStyles = explode( ':', $font_data['values']['font_style'] );
	if( isset($fontStyles[1]) ){
		$styles[] = 'font-weight:' . $fontStyles[1];
	}

	if( isset($fontStyles[2]) ){
		$styles[] = 'font-style:' . $fontStyles[2];
	}
	 
	$inline_style = '';
	foreach( $styles as $attribute ){
		$inline_style .= $attribute.'; ';
	}
	return $inline_style;
}

/*
 * enqueue google font
 */
function wpbforwpbakery_enqueue_google_font( $font_data ){
	$settings = get_option( 'wpb_js_google_fonts_subsets' );
	if ( is_array( $settings ) && ! empty( $settings ) ) {
		$subsets = '&subset=' . implode( ',', $settings );
	} else {
		$subsets = '';
	}
	
	if ( isset( $font_data['values']['font_family'] ) ) {
		wp_enqueue_style('vc_google_fonts_' . vc_build_safe_css_class( $font_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $font_data['values']['font_family'] . $subsets );
	}
}

/*
 * wpbakery row custom class
 * return css class
 */
function wpbforwpbakery_get_vc_custom_class( $param_value, $prefix = '', $atts = '' ){
	if(function_exists('vc_shortcode_custom_css_class')){ 
		return vc_shortcode_custom_css_class($param_value, $prefix);
	}
	$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';

	return $css_class;
}

/**
 * Check if VC installed or not.
 * Few themes modify the js_composer plugin & supply it with the plugins, this is why this function introduced for
 * Must be loaded after, plugins loaded hook
 *
 * @since 1.0.12
 */
function wpbforwpbakery_find_vc_installed(){
    $plugin_file = '';

    if( function_exists('get_plugins') ){
        $plugins_list      = array_keys(get_plugins());

        foreach($plugins_list as $plugin){
            $pos = strpos($plugin, 'js_composer');
            if(gettype($pos) === 'integer'){
                $plugin_file = $plugin;
                break;
            }
        }
    }

    return $plugin_file;
}