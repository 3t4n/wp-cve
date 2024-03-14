<?php
namespace Elementor;

if ( ! function_exists('xltab_insert_elementor') ){

	function xltab_insert_elementor($atts){
	    if(!class_exists('Elementor\Plugin')){
	        return '';
	    }
	    if($atts['id']){
			$post_id = $atts['id'];

			$response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
			if ($response){
				return $response;
			} else {
				return esc_html__('Please assign elementor template to display content', 'xltab');
			}
	    } else {
			return esc_html__('Please assign elementor template to display content', 'xltab');
		}
	}
 
}

add_shortcode('XLTAB_INSERT_TPL','Elementor\xltab_insert_elementor');


