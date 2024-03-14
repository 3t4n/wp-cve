<?php

namespace Definitive_Addons_Elementor\Elements;
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Reuse {
	
	public function __construct() {
		
	   add_filter( 'excerpt_more', array( $this, 'dafe_post_read_more_link' ));
       add_filter( 'excerpt_length', array( $this, 'dafe_excerpt_length' ),999);

		if ( function_exists( 'YITH_WCWL' ) ) {	
		add_action( 'woocommerce_widget_product_items_end', array( $this,'dafe_wcwl_move_wishlist_button' ));
		}
		
		
	}
	
public static function dafe_post_read_more_link($more){

	$read_more_btn_txt = __('Read More','definitive-addons-for-elementor');
		return sprintf( '<div class="blog-buttons"><a href="%1$s" class="more-link">%2$s</a></div>',
          esc_url( get_permalink( get_the_ID() ) ),esc_attr($read_more_btn_txt),
          sprintf( __( 'Continue reading %s', 'definitive-addons-for-elementor' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
    );

}


public static function dafe_excerpt_length( $length ) {
	
  $excerpt = 34;
  return absint($excerpt);
  
}



public static function dafe_wcwl_move_wishlist_button(){
if ( !function_exists( 'YITH_WCWL' ) ) {
return;
}	
	echo do_shortcode( '[yith_wcwl_add_to_wishlist icon="fa fa-heart-o"]' );

}


	
public static function dafe_product_categories_lists() {
		$args = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 0,
				'title_li'   => '',
		);
		$prod_cats = get_categories($args);
		$prod_categories = array();
		if ( ! empty( $prod_cats ) ) {
			
				foreach ( $prod_cats as $prod_cat ) {

					if ( ! empty( $prod_cat->term_id )) {
					
						if (! empty( $prod_cat->name ) ) {
							$prod_categories[ $prod_cat->term_id ] = sanitize_text_field($prod_cat->name);
						}

					}
				}
		}
		return $prod_categories;
}
	
	public static function dafe_post_categories()
    {
	
		$args = array(
				'taxonomy'   => 'category',
				'hide_empty' => 0,
				'title_li'   => '',
		);
		$post_cats = get_categories($args);
		$post_categories = array();
		if ( ! empty( $post_cats ) ) {
			
				foreach ( $post_cats as $post_cat ) {

					if ( ! empty( $post_cat->term_id )) {
					
						if (! empty( $post_cat->name ) ) {
							$post_categories[ $post_cat->term_id ] = $post_cat->name;
						}

					}
				}
		}
		
		return $post_categories;
    }

	public static function dafe_posted_on() {
	
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		/* translators: author */
		$byline = sprintf(_x( 'By %s', 'post author', 'definitive-addons-for-elementor' ),'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>');
	
		echo '<i class="eicon-clock-o"></i><span class="da-posted-on">' . esc_html( get_the_date()) . '</span><i class="eicon-user-circle-o"></i><span class="da-byline"> ' . wp_kses_post($byline). '</span>';
	
		

	}
	
	public static function dafe_posted_date() {
	
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}
		
		echo '<i class="eicon-clock-o"></i><span class="da-posted-on">' . esc_html( get_the_date()) . '</span>';

	}
	
	public static function dafe_posted_byline() {
	
		/* translators: author */
		$byline = sprintf(_x( 'By %s', 'post author', 'definitive-addons-for-elementor' ),'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>');
	
		echo '<i class="eicon-user-circle-o"></i><span class="da-byline"> ' . wp_kses_post($byline). '</span>';
	
		

	}

	
	function dafe_get_wpforms_list(){
		
		$args = array('post_type' => 'wpforms', 'posts_per_page' => 777);
		$form_list = array();
		$form_msg = '';
		if( $list_val = get_posts($args)){
			foreach($list_val as $key){
			$form_list[$key->ID] = esc_html($key->post_title);
			}
			return	$form_list;
		}else{
		
		return	$form_msg = __( 'No Form found. Create New form ', 'definitive-addons-for-elementor' ) .' <a href="'.esc_url(admin_url( 'admin.php?page=wpforms-builder' )).'" target="_blank">'
		. __( 'Click here', 'definitive-addons-for-elementor' ) .'</a>';
		}
			
}

	function dafe_get_ninjaforms_list(){
		
		 if (!class_exists('Ninja_Forms')) {
			return; 
		 }
		$form_list = array();
		$form_msg = '';
		if( $list_val = Ninja_Forms()->form()->get_forms()){
			foreach($list_val as $key){
			$form_list[$key->get_id()] = esc_html($key->get_setting('title'));
			}
			return	$form_list;
		}else{
		
		return	$form_msg = __('No Form found. Create New form ', 'definitive-addons-for-elementor' );
		}
	
			
}



function dafe_get_form_list(){
		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => 777);
		$cf7_list = array();
		$cf7_msg = '';
		if( $list_val = get_posts($args)){
			foreach($list_val as $key){
			$cf7_list[$key->ID] = esc_html($key->post_title);
			}
			return	$cf7_list;
		}else{
		
		return	$cf7_msg = __( 'No Form found. Create New form ', 'definitive-addons-for-elementor' ) .' <a href="'.esc_url(admin_url( 'admin.php?page=wpcf7-new' )).'" target="_blank">'
		. __( 'Click here', 'definitive-addons-for-elementor' ) .'</a>';
		}
			
}



public static function dafe_social_icon_brands() {
	$social_brands = array();
		return $social_brands =
		[				
						'500px',
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						
						'facebook',
						'flickr',
						'foursquare',
						
						'github',
						
						'globe',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						
						'steam',
						'stumbleupon',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						
						'cc-amex',
						'cc-mastercard',
						'cc-paypal',
						'cc-stripe',
						'cc-visa',
						'credit-card',
						'google-wallet',
						'paypal',
						'cc-discover',
						'cc-jcb'
					];
}


public static function dafe_css_animations() {
		return apply_filters( 'dafe_css_animation', array(
	'defaults' => __( 'none', 'definitive-addons-for-elementor' ),
'bounce' => __( 'bounce', 'definitive-addons-for-elementor' ),
'flash' => __( 'flash', 'definitive-addons-for-elementor' ),
'pulse' => __( 'pulse', 'definitive-addons-for-elementor' ),
'rubberBand' => __( 'rubberBand', 'definitive-addons-for-elementor' ),
'shake' => __( 'shake', 'definitive-addons-for-elementor' ),
'headShake' => __( 'headShake', 'definitive-addons-for-elementor' ),
'swing' => __( 'swing', 'definitive-addons-for-elementor' ),
'tada' => __( 'tada', 'definitive-addons-for-elementor' ),
'wobble' => __( 'wobble', 'definitive-addons-for-elementor' ),
'jello' => __( 'jello', 'definitive-addons-for-elementor' ),
'bounceIn' => __( 'bounceIn', 'definitive-addons-for-elementor' ),
'bounceInDown' => __( 'bounceInDown', 'definitive-addons-for-elementor' ),
'bounceInLeft' => __( 'bounceInLeft', 'definitive-addons-for-elementor' ),
'bounceInLeft' => __( 'bounceInLeft', 'definitive-addons-for-elementor' ),
'bounceInUp' => __( 'bounceInUp', 'definitive-addons-for-elementor' ),
'bounceOut' => __( 'bounceOut', 'definitive-addons-for-elementor' ),
'bounceOutDown' => __( 'bounceOutDown', 'definitive-addons-for-elementor' ),
'bounceOutLeft' => __( 'bounceOutLeft', 'definitive-addons-for-elementor' ),
'bounceOutRight' => __( 'bounceOutRight', 'definitive-addons-for-elementor' ),
'bounceOutUp' => __( 'bounceOutUp', 'definitive-addons-for-elementor' ),
'fadeIn' => __( 'fadeIn', 'definitive-addons-for-elementor' ),
'fadeInDown' => __( 'fadeInDown', 'definitive-addons-for-elementor' ),
'fadeInDownBig' => __( 'fadeInDownBig', 'definitive-addons-for-elementor' ),
'fadeInLeft' => __( 'fadeInLeft', 'definitive-addons-for-elementor' ),
'fadeInLeftBig' => __( 'fadeInLeftBig', 'definitive-addons-for-elementor' ),
'fadeInRight' => __( 'fadeInRight', 'definitive-addons-for-elementor' ),
'fadeInRightBig' => __( 'fadeInRightBig', 'definitive-addons-for-elementor' ),
'fadeInUp' => __( 'fadeInUp', 'definitive-addons-for-elementor' ),
'fadeInUpBig' => __( 'fadeInUpBig', 'definitive-addons-for-elementor' ),
'fadeOut' => __( 'fadeOut', 'definitive-addons-for-elementor' ),
'fadeOutDown' => __( 'fadeOutDown', 'definitive-addons-for-elementor' ),
'fadeOutDownBig' => __( 'fadeOutDownBig', 'definitive-addons-for-elementor' ),
'fadeOutLeft' => __( 'fadeOutLeft', 'definitive-addons-for-elementor' ),
'fadeOutLeftBig' => __( 'fadeOutLeftBig', 'definitive-addons-for-elementor' ),
'fadeOutRight' => __( 'fadeOutRight', 'definitive-addons-for-elementor' ),
'fadeOutRightBig' => __( 'fadeOutRightBig', 'definitive-addons-for-elementor' ),
'fadeOutUp' => __( 'fadeOutUp', 'definitive-addons-for-elementor' ),
'fadeOutUpBig' => __( 'fadeOutUpBig', 'definitive-addons-for-elementor' ),
'flipInX' => __( 'flipInX', 'definitive-addons-for-elementor' ),
'flipInY' => __( 'flipInY', 'definitive-addons-for-elementor' ),
'flipOutX' => __( 'flipOutX', 'definitive-addons-for-elementor' ),
'flipOutY' => __( 'flipOutY', 'definitive-addons-for-elementor' ),
'lightSpeedIn' => __( 'lightSpeedIn', 'definitive-addons-for-elementor' ),
'lightSpeedOut' => __( 'lightSpeedOut', 'definitive-addons-for-elementor' ),
'rotateIn' => __( 'rotateIn', 'definitive-addons-for-elementor' ),
'rotateInDownLeft' => __( 'rotateInDownLeft', 'definitive-addons-for-elementor' ),
'rotateInDownRight' => __( 'rotateInDownRight', 'definitive-addons-for-elementor' ),
'rotateInUpLeft' => __( 'rotateInUpLeft', 'definitive-addons-for-elementor' ),
'rotateInUpRight' => __( 'rotateInUpRight', 'definitive-addons-for-elementor' ),
'rotateOut' => __( 'rotateOut', 'definitive-addons-for-elementor' ),
'rotateOutDownLeft' => __( 'rotateOutDownLeft', 'definitive-addons-for-elementor' ),
'rotateOutDownRight' => __( 'rotateOutDownRight', 'definitive-addons-for-elementor' ),
'rotateOutUpLeft' => __( 'rotateOutUpLeft', 'definitive-addons-for-elementor' ),
'rotateOutUpRight' => __( 'rotateOutUpRight', 'definitive-addons-for-elementor' ),
'hinge' => __( 'hinge', 'definitive-addons-for-elementor' ),
'jackInTheBox' => __( 'jackInTheBox', 'definitive-addons-for-elementor' ),
'rollIn' => __( 'rollIn', 'definitive-addons-for-elementor' ),
'rollOut' => __( 'rollOut', 'definitive-addons-for-elementor' ),
'zoomIn' => __( 'zoomIn', 'definitive-addons-for-elementor' ),
'zoomInDown' => __( 'zoomInDown', 'definitive-addons-for-elementor' ),
'zoomInLeft' => __( 'zoomInLeft', 'definitive-addons-for-elementor' ),
'zoomInRight' => __( 'zoomInRight', 'definitive-addons-for-elementor' ),
'zoomInUp' => __( 'zoomInUp', 'definitive-addons-for-elementor' ),
'zoomOut' => __( 'zoomOut', 'definitive-addons-for-elementor' ),
'zoomOutDown' => __( 'zoomOutDown', 'definitive-addons-for-elementor' ),
'zoomOutLeft' => __( 'zoomOutLeft', 'definitive-addons-for-elementor' ),
'zoomOutRight' => __( 'zoomOutRight', 'definitive-addons-for-elementor' ),
'zoomOutUp' => __( 'zoomOutUp', 'definitive-addons-for-elementor' ),
'slideInDown' => __( 'slideInDown', 'definitive-addons-for-elementor' ),
'slideInLeft' => __( 'slideInLeft', 'definitive-addons-for-elementor' ),
'slideInRight' => __( 'slideInRight', 'definitive-addons-for-elementor' ),
'slideInUp' => __( 'slideInUp', 'definitive-addons-for-elementor' ),
'slideOutDown' => __( 'slideOutDown', 'definitive-addons-for-elementor' ),
'slideOutLeft' => __( 'slideOutLeft', 'definitive-addons-for-elementor' ),
'slideOutRight' => __( 'slideOutRight', 'definitive-addons-for-elementor' ),
'slideOutUp' => __( 'slideOutUp', 'definitive-addons-for-elementor' ),
'none' => __( 'None', 'definitive-addons-for-elementor' )

	 ));
}
}
$reused = new Reuse();
