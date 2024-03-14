<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Admin Functions
 *
 * Handles to plugin admin functions
 *
 * @since WP Post Disclaimer 1.0.0
 **/
if( !function_exists('wppd_get_options') ) :
/**
 * Get Plugin Options
 *
 * Handles to return get plugin options
 * 
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_get_options() {
	$options = is_array( get_option('wppd_options') ) ? get_option('wppd_options') : array();
	return apply_filters( 'wppd_get_options', $options );
}
endif;
if( !function_exists('wppd_is_enabled') ) :
/**
 * Check Disclaimer is Enabled
 *
 * Handles to check disclaimer is enabled for post/page
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_is_enabled( $postid = 0 ){
	global $wppd_options, $post;
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;
	$individual_disable = get_post_meta($postid, '_wppd_post_disclaimer_disable',true);
	$individual_disable = empty( $individual_disable ) ? 1 : 0;	
	$default_enabled 	= isset( $wppd_options['enable'] ) && !empty( $wppd_options['enable'] ) ? true : false;	
	$wppd_enabled 		= empty( $individual_disable ) ? false : ( !empty( $default_enabled ) ? true : false );
	if( !empty( $wppd_enabled ) ) : //Check For Enabled or Not
 		$wppd_enabled = isset( $wppd_options['display_in_'.$post->post_type] ) && !empty( $wppd_options['display_in_'.$post->post_type] ) ? true : false;		
	endif; //Endif
	return $wppd_enabled;
}
endif;
if( !function_exists('wppd_disclaimer_position') ) :
/**
 * Check Disclaimer Position
 *
 * Handles to check disclaimer position
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_position( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;
	$individual_position = get_post_meta($postid, '_wppd_post_disclaimer_position',true);
	$default_position = isset( $wppd_options['display_in_'.$post->post_type.'_position'] ) && !empty( $wppd_options['display_in_'.$post->post_type.'_position'] ) ? esc_attr( $wppd_options['display_in_'.$post->post_type.'_position'] ) : 'bottom';
	$wppd_position = !empty( $individual_position ) ? $individual_position : $default_position;
	return $wppd_position;
}
endif;
if( !function_exists('wppd_disclaimer_title') ) :
/**
 * Disclaimer Title
 *
 * Handles to get disclaimer title
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_title( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid 		= isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;
	$post_data 		= get_post($postid);
	$individual_title = get_post_meta($postid, '_wppd_post_disclaimer_title',true);
	$default_title 	= isset( $wppd_options['disclaimer_title'] ) && !empty( $wppd_options['disclaimer_title'] ) ? esc_attr( $wppd_options['disclaimer_title'] ) : '';
	$wppd_title 	= !empty( $individual_title ) ? esc_attr( $individual_title ) : $default_title;
	$wppd_title		= !empty( $wppd_title ) ? $wppd_title : false;
	$wppd_title		= str_ireplace( array('%%title%%', '%%excerpt%%', '%%sitename%%'), array( $post_data->post_title, $post_data->post_excerpt, get_bloginfo('name') ), $wppd_title );
	return apply_filters('wppd_disclaimer_title', $wppd_title, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_content') ) :
/**
 * Disclaimer Title
 *
 * Handles to get disclaimer title
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_content( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid 		= isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;
	$post_data 		= get_post($postid);
	$individual_content = get_post_meta($postid, '_wppd_post_disclaimer_content',true);
	$default_content= isset( $wppd_options['disclaimer_content'] ) && !empty( $wppd_options['disclaimer_content'] ) ? $wppd_options['disclaimer_content'] : '';
	$wppd_content	= !empty( $individual_content ) ? $individual_content : $default_content;
	$wppd_content	= !empty( $wppd_content ) ? $wppd_content : false;
	$wppd_content	= str_ireplace( array('%%title%%', '%%excerpt%%', '%%sitename%%'), array( $post_data->post_title, $post_data->post_excerpt, get_bloginfo('name') ), $wppd_content );
	return apply_filters('wppd_disclaimer_content', $wppd_content, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_title_tag') ) :
/**
 * Disclaimer Title Tag
 *
 * Handles to get disclaimer title tag
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_title_tag( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;	
	$individual_title_tag = get_post_meta($postid, '_wppd_post_disclaimer_title_tag',true);
	$default_title_tag	= isset( $wppd_options['title_tag'] ) && !empty( $wppd_options['title_tag'] ) ? esc_attr( $wppd_options['title_tag'] ) : 'h6';
	$wppd_title_tag		= !empty( $individual_title_tag ) ? esc_attr( $individual_title_tag ) : $default_title_tag;
	return apply_filters('wppd_disclaimer_title_tag', $wppd_title_tag, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_style') ) :
/**
 * Disclaimer Style
 *
 * Handles to get disclaimer style
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_style( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;	
	$individual_style = get_post_meta($postid, '_wppd_post_disclaimer_style',true);
	$default_style	= isset( $wppd_options['style'] ) && !empty( $wppd_options['style'] ) ? $wppd_options['style'] : '';
	$wppd_style		= !empty( $individual_style ) ? esc_attr( $individual_style ) : $default_style;
	return apply_filters('wppd_disclaimer_style', $wppd_style, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_icon') ) :
/**
 * Disclaimer Icon
 *
 * Handles to get disclaimer icon
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_icon( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;	
	$individual_icon = get_post_meta($postid, '_wppd_post_disclaimer_icon',true);
	$default_icon	= isset( $wppd_options['icon'] ) && !empty( $wppd_options['icon'] ) ? esc_attr( $wppd_options['icon'] ) : '';
	$wppd_icon		= !empty( $individual_icon ) ? esc_attr( $individual_icon ) : $default_icon;
	return apply_filters('wppd_disclaimer_icon', $wppd_icon, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_icon_size') ) :
/**
 * Disclaimer Icon Size
 *
 * Handles to get disclaimer icon size
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_icon_size( $postid = 0 ){
	global $wppd_options, $post;	
	//Post ID
	$postid = isset( $postid ) && !empty( $postid ) ? $postid : $post->ID;	
	$individual_icon_size = get_post_meta($postid, '_wppd_post_disclaimer_icon_size',true);
	$default_icon_size	= isset( $wppd_options['icon_size'] ) && !empty( $wppd_options['icon_size'] ) ? esc_attr( $wppd_options['icon_size'] ) : 'sm';
	$wppd_icon_size		= !empty( $individual_icon_size ) ? esc_attr( $individual_icon_size ) : $default_icon_size;
	return apply_filters('wppd_disclaimer_icon_size', $wppd_icon_size, $postid);
}
endif;
if( !function_exists('wppd_disclaimer_html') ) :
/**
 * Disclaimer HTML
 *
 * Handles to get disclaimer HTML
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_disclaimer_html( $args = array() ){

	$wppd_disclaimer_title 	= ( isset( $args['title'] )		&& !empty( $args['title'] ) ) 		? esc_attr( $args['title'] ) 	: wppd_disclaimer_title();
	$wppd_disclaimer_content = ( isset( $args['content'] )	&& !empty( $args['content'] ) ) 	? esc_attr( $args['content'] ) 	: wppd_disclaimer_content();
	$wppd_title_tag	= ( isset( $args['title_tag'] ) 		&& !empty( $args['title_tag'] ) ) 	? wppd_validate_title_tag( $args['title_tag'] ) : wppd_disclaimer_title_tag();
	$wppd_style		= ( isset( $args['style'] ) 			&& !empty( $args['style'] ) ) 		? esc_attr( $args['style'] )	: wppd_disclaimer_style();
	$wppd_icon 		= ( isset( $args['icon'] ) 				&& !empty( $args['icon'] ) ) 		? esc_attr( $args['icon'] ) 	: wppd_disclaimer_icon();
	$wppd_icon_size	= ( isset( $args['icon_size'] )			&& !empty( $args['icon_size'] ) ) 	? esc_attr( $args['icon_size'] ): wppd_disclaimer_icon_size();
	
	//Escape All Values
	$wppd_disclaimer_title 	= esc_html( $wppd_disclaimer_title );
	$wppd_disclaimer_content = wp_kses_post( $wppd_disclaimer_content );
	$wppd_style				= esc_html( $wppd_style );
	$wppd_icon 				= esc_html( $wppd_icon );
	$wppd_icon_size			= esc_html( $wppd_icon_size );

	//Container HTML Class and ID
	$wppd_container_classes = apply_filters('wppd_disclaimer_container_class', array($wppd_style));	//Container Classes
	$wppd_container_id 		= apply_filters('wppd_disclaimer_container_id', esc_html('wppd-disclaimer-container')); //Container ID
	
	$output = '';	
	
	if( !empty( $wppd_icon ) ) : //Icon Class
		$wppd_icon_size	= ( isset( $args['icon_size'] ) && !empty( $args['icon_size'] ) )? esc_attr( $args['icon_size'] ) : wppd_disclaimer_icon_size();
		$output .= apply_filters('wppd_disclaimer_icon_html', '<i class="'.$wppd_icon.' fa-'.$wppd_icon_size.'"></i>');
	endif; //Endif
	
	if( !empty( $wppd_disclaimer_title ) ) : //Check Disclaimer Title
		$title_classes 		= array( esc_html('wppd-disclaimer-title') );
		$wppd_title_classes = apply_filters('wppd_disclaimer_title_class', $title_classes);	//Title Classes
		$title_html = '<'.$wppd_title_tag.' class="'.implode(' ', $wppd_title_classes).'">'.$wppd_disclaimer_title.'</'.$wppd_title_tag.'>';
		$output .= apply_filters('wppd_disclaimer_title_html', $title_html, $wppd_disclaimer_title, $wppd_title_tag );
	endif; //Endif
	if( !empty( $wppd_disclaimer_content ) ) : //Check Disclaimer Content
		$output .= wpautop( $wppd_disclaimer_content );
	endif; //Endif	
	$output = !empty( $output ) ? '<div id="'.$wppd_container_id.'" class="wppd-disclaimer-container '.implode(' ', $wppd_container_classes).'">'.$output.'</div>' : ''; //Output	
	return apply_filters('wppd_disclaimer_html', $output, array( 'title' => $wppd_disclaimer_title, 'content' => $wppd_disclaimer_content, 'title_tag' => $wppd_title_tag, 'style' => $wppd_style, 'icon' => $wppd_icon, 'icon_size' => $wppd_icon_size ) );
}
endif;
if( !function_exists('wppd_placement_position_options') ) :
/**
 * Placement Position
 *
 * Handles to placement position for disclaimer content
 *
 * @since WP Post Disclaimer 1.0.0
 **/
function wppd_placement_position_options( $position, $post_type = 'post' ){

	if( !isset( $position ) || empty( $position ) ) : //If not set position
		$position = 'bottom';
	endif; //Endif

	$html = '<select name="wppd_options[display_in_'.$post_type.'_position]">';
		$html .= '<option value="top" '.selected('top', $position, false).'>'.esc_html__('Top', 'wp-post-disclaimer').'</option>';
		$html .= '<option value="bottom" '.selected('bottom', $position, false).'>'.esc_html__('Bottom', 'wp-post-disclaimer').'</option>';
		$html .= '<option value="top_bottom" '.selected('top_bottom', $position, false).'>'.esc_html__('Top & Bottom', 'wp-post-disclaimer').'</option>';
		$html .= '<option value="shortcode" '.selected('shortcode', $position, false).'>'.esc_html__('Shortcode', 'wp-post-disclaimer').'</option>';
	$html .= '</select>';
	return $html;
}
endif;
if( !function_exists('wppd_fontawesome_icons_options') ) :
/** 
 * Font Awesome Icons
 *
 * Handles to get icons
 *
 * @sinec WP Post Disclaimer 1.0.0
 **/
function wppd_fontawesome_icons_options(){
	include_once( WPPD_PLUGIN_PATH . 'includes/font-awesome.php' );
	return apply_filters( 'wppd_icons_options_arr', $fa_icons );
}
endif;
if( !function_exists('wppd_fontawesome_icons_sizes_options') ) :
/** 
 * Icons Sizes Options
 *
 * Handles to get icons sizes options
 *
 * @sinec WP Post Disclaimer 1.0.0
 **/
function wppd_fontawesome_icons_sizes_options(){
	return apply_filters( 'wppd_icon_size_options_arr', array( 'xs' => esc_html__('Extra Small', 'wp-post-disclaimer'), 'sm' => esc_html__('Small', 'wp-post-disclaimer'), 'lg' => esc_html__('Large', 'wp-post-disclaimer')/*, '2x' => esc_html__('2X', 'wp-post-disclaimer'), '3x' => esc_html__('3X', 'wp-post-disclaimer'), '5x' => esc_html__('5X', 'wp-post-disclaimer'), '7x' => esc_html__('7X', 'wp-post-disclaimer'), '10x' => esc_html__('10X', 'wp-post-disclaimer')*/ ) );
}
endif;
if( !function_exists('wppd_style_options') ) :
/** 
 * Styles Options
 *
 * Handles to get styles options
 *
 * @sinec WP Post Disclaimer 1.0.0
 **/
function wppd_style_options(){
	return apply_filters( 'wppd_style_options_arr', array('red' => esc_html__('Red Theme', 'wp-post-disclaimer'), 'yellow' => esc_html__('Yellow Theme', 'wp-post-disclaimer'), 'blue' => esc_html__('Blue Theme', 'wp-post-disclaimer'), 'green Theme' => esc_html__('Green', 'wp-post-disclaimer'), 'grey' => esc_html__('Grey Theme', 'wp-post-disclaimer'), 'black' => esc_html__('Black Theme', 'wp-post-disclaimer'), 'white' => esc_html__('White Theme', 'wp-post-disclaimer') ) );
}
endif;
if( !function_exists('wppd_title_tag_options') ) :
/** 
 * Title Tag Options
 *
 * Handles to get title tag options
 *
 * @sinec WP Post Disclaimer 1.0.0
 **/
function wppd_title_tag_options(){
	return apply_filters( 'wppd_title_tag_options_arr', array('h1' => esc_html__('H1', 'wp-post-disclaimer'), 'h2' => esc_html__('H2', 'wp-post-disclaimer'), 'h3' => esc_html__('H3', 'wp-post-disclaimer'), 'h4' => esc_html__('H4', 'wp-post-disclaimer'), 'h5' => esc_html__('H5', 'wp-post-disclaimer'), 'h6' => esc_html__('H6', 'wp-post-disclaimer'), 'span' => esc_html__('Span', 'wp-post-disclaimer') ) );
}
endif;
if( !function_exists('wppd_sanitize_editor_field') ) :
/**
 * Sanitize Input Field
 *
 * @since WP Post Disclaimer 1.0.2
 **/
function wppd_sanitize_editor_field( $content ){
	$content = wp_filter_post_kses( $content ); 	//Filter kses
	$content = wp_kses_stripslashes( $content ); 	//Filter Slashes
	$content = stripslashes_deep( $content );		//Stripslashes
	return $content;
}
endif;
if( !function_exists('wppd_validate_title_tag') ) :
/**
 * Sanitize Input Field
 *
 * @since WP Post Disclaimer 1.0.3
 **/
function wppd_validate_title_tag( $title_tag ) {
	return in_array( $title_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div', 'strong', 'em', 'b') ) ? $title_tag : wppd_disclaimer_title_tag();
}
endif;