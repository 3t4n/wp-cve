<?php

/**
 * Creating a class to handle a WordPress shortcode is overkill in most cases.
 * However, it is extremely handy when your shortcode requires helper functions.
 */
class ContentAd__Includes__ExitPage {

    protected
        $atts = array(),
        $content = false,
        $output = false;

    protected static $defaults = array();

    public static function contentad_exitpage( $atts, $content = '', $tag = '' ) {
		
		if( isset($atts['widget'])) {
			
			$post_id = self::get_post_id($atts['widget']);
			
			if( get_post_meta( $post_id, 'placement', true ) == 'in_shortcode' ) {
				// Fetch shortcode widgets
				return ContentAd__Includes__API::get_code_for_single_ad($post_id);
			} else {
				// Otherwise return nothing
				return;
			}
		
		}
		
		return;
    }

	public static function get_post_id($content_ad_widget_id) {
		$get_post_id_args = array(
			'post_type'		=>	'content_ad_widget',
			'meta_query'	=>	array(
				array(
					'value'	=>	$content_ad_widget_id
				)
			)
		);
		$get_post_id_query = new WP_Query( $get_post_id_args );
		if( $get_post_id_query->have_posts() ) {
		  while( $get_post_id_query->have_posts() ) {
			$get_post_id_query->the_post();
			return get_the_ID();
		  } // end while
		} // end if
	}
	
}