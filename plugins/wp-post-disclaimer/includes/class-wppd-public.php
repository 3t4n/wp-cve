<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( !class_exists('WP_Post_Disclaimer_Public') ) :
/**
 * Post Disclaimer Public Class
 *
 * Handles post disclaimer public class
 *
 * @since WP Post Disclaimer 1.0.0
 **/
class WP_Post_Disclaimer_Public{
	
	//Class Constructor
	public function __construct(){
		//Add Disclaimer
		add_filter( 'the_content', 			array($this, 'post_disclaimer_render'), 999 );
		//Shortcode for Disclaimer
		add_shortcode( 'wppd_disclaimer', 	array($this, 'post_disclaimer_shortcode') );
		//Add Style or Script
		add_action( 'wp_enqueue_scripts',	array($this, 'register_scripts_styles') );
		//Add Theme Body Class
		if( !class_exists( 'woocommerce' ) ) : //Check Woocommerce Active			
			add_action( 'body_class',		array($this, 'add_theme_body_class' ), 1 );
		endif; //Endif
	}	
	/**
	 * Add Theme Classes
	 **/
	public function add_theme_body_class( $classes ) {
		$classes[] = 'theme-' . get_template();
		return $classes;
	}
	/**
	 * Add Scripts / Styles for Post Disclaimer
	 *
	 **/
	public function register_scripts_styles(){
		global $wppd_options;
		
		//Only Show on Singular Post
		if( !is_singular() ) 
			return;
		
		if( wppd_is_enabled() ) : //Check is Enable
		
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';			
			
			if( empty( $wppd_options['disable_fa'] ) ) : //Check Font Awesome Disabled or Not
				wp_register_style('fontawesome', WPPD_PLUGIN_URL . 'assets/css/fontawesome/all'.$suffix.'.css', array(), WPPD_PLUGIN_VERSION);
				wp_enqueue_style('fontawesome');
			endif; //Endif
			
			//Post Disclaimer Styles			
			wp_register_style('wppd-styles', WPPD_PLUGIN_URL . 'assets/css/styles'.$suffix.'.css', array(), WPPD_PLUGIN_VERSION);
			wp_enqueue_style('wppd-styles');
			
			if( !empty( $wppd_options['custom_css'] ) && file_exists( WPPD_PLUGIN_PATH . 'assets/css/custom.css' ) ) : //Check Custom CSS
				wp_register_style('wppd-custom-styles', WPPD_PLUGIN_URL . 'assets/css/custom.css', array(), WPPD_PLUGIN_VERSION);
				wp_enqueue_style('wppd-custom-styles');
			endif; //Endif
			
		endif; //Endif
		
		
	}
	/**
	 * Render Post Disclaimer in Content	 
	 **/
	public function post_disclaimer_render( $content ){
		global $post;
		
		//Only Show on Singular Post
		if( !is_singular() ) 
			return $content;
		
		if( wppd_is_enabled() ) : //Check Disclaimer Enabled or Not
			$output = '';
			$wppd_position	= wppd_disclaimer_position();
			$wppd_disclaimer= wppd_disclaimer_html();
			if( $wppd_position !== 'shortcode' ) :
				if( $wppd_position == 'bottom' ) : //Check Position Bottom
					$output = $content.$wppd_disclaimer;
				elseif( $wppd_position == 'top' ) : //Show at Top Position
					$output = $wppd_disclaimer.$content;
				elseif( $wppd_position == 'top_bottom' ) : //Show at Top Position
					$output = $wppd_disclaimer.$content.$wppd_disclaimer;
				endif; //Endif
			endif; //Endif
			return !empty( $output ) ? $output : $content; //Return with Disclaimer
		endif; //Endif
		return $content;
		
	}
	/**
	 * Render Post Disclaimer via Shortcode
	 **/
	public function post_disclaimer_shortcode($atts, $content = null){
		
		//Show only on Singular Page
		if( !is_singular() ) :
			return '';
		endif; //Endif		
		
		$output = '';		
		if( wppd_is_enabled() && wppd_disclaimer_position() == 'shortcode' ) : //Check Enable or Not

			extract( shortcode_atts( array(
				'title'		=> '',
				'title_tag' => '',
				'style'		=> '',
				'icon'		=> '',
				'icon_size'	=> ''
			), $atts, 'wppd_disclaimer' ) );
			
			$output = wppd_disclaimer_html( array( 'title' => $title, 'content' => $content, 'title_tag' => $title_tag, 'style' => $style, 'icon' => $icon, 'icon_size' => $icon_size ) );

		endif; //Endif

		return apply_filters('wppd_disclaimer_shortcode_html', $output);
	}
}
//Run Class and Create Object
$wppd_public = new WP_Post_Disclaimer_Public();
endif;