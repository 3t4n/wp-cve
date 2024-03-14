<?php

/**
 *
 */
class Resp_Accordion_Slider_Shortcode {


	private $loader;

	function __construct() {

		$this->loader  = new Resp_Accordion_Slider_Template_Loader();

		add_shortcode( 'resp-slider', array( $this, 'resp_accordion_slider_shortcode_handler' ) );
		add_shortcode( 'Resp-Slider', array( $this, 'resp_accordion_slider_shortcode_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'resp_accordion_slider_scripts' ) );

	}

	public function resp_accordion_slider_scripts() {

		global $post;
		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'resp-slider') ) {

			wp_enqueue_style( 'resp-accordion-slider-css', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'css/accordion-design.css', null, RESP_ACCORDION_SLIDER_CURRENT_VERSION );


			wp_enqueue_script( 'resp-accordion-slider-js', RESP_ACCORDION_SLIDER_ASSETS_PATH . 'js/responsive-accordion-slider-js.js', array('jquery'), RESP_ACCORDION_SLIDER_CURRENT_VERSION, true );
		}

	}


	public function resp_accordion_slider_shortcode_handler( $Id ) {
		// Id return id

		ob_start();	
		if(!isset($Id['id'])) 
		 {
			$TKPM_Slider_ID = "";
		 } 
		else 
		{
			$TKPM_Slider_ID = $Id['id'];
		}

		$post_type = "ras-accordion-slider";
		$AllTeams = array(  'p' => $TKPM_Slider_ID, 'post_type' => $post_type, 'orderby' => 'ASC');
	    $loop = new WP_Query( $AllTeams );
		
		while ( $loop->have_posts() ) : $loop->the_post();
			
			$PostId = get_the_ID();
			$settings = get_post_meta( $PostId, 'ras-accordion-slider-settings', true );
			$default  = RESP_ACCORDION_SLIDER_CPT_Fields_Helper::resp_accordion_slider_get_defaults();
			$settings = wp_parse_args( $settings, $default );

			$images = apply_filters( 'accordion_slider_before_shuffle_images', get_post_meta( $PostId, 'slider-images', true ), $settings );
			

			$design = $settings['designName'];

			require "designs/$design/index.php";

		endwhile;

		wp_reset_query();
    return ob_get_clean();
	  
	}
	
}

new Resp_Accordion_Slider_Shortcode();