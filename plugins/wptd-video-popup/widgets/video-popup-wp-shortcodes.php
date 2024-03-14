<?php
class WPTD_Shortcodes {
	
	public function __construct() {
		//Shortcode scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'wptd_scripts' ) );
		//Shortcode
		add_shortcode( 'wptd_video_popup', array( $this, 'wptd_video_popup_fun' ) );		
    }
	
	public static function wptd_random_number(){
		static $random_ = 1;
		return $random_++;
	}
	
	public function wptd_video_popup_fun( $atts ) {		
		$atts = shortcode_atts( array(
			'url' => '',
			'width' => '',
			'trigger' => 'text',
			'text' => esc_html__( 'Click', 'wptd-video-popup' ),
			'icon' => '',
			'img' => '',
			'bg_color' => 'rgba(0,0,0,0.5)',
			'autoplay' => 0,
			'extra_class' => ''
		), $atts );
		extract( $atts );
		
		wp_enqueue_style( 'magnific-popup' );
		wp_enqueue_style( 'wptd-video-popup' );
		wp_enqueue_script( 'magnific-popup' );
		wp_enqueue_script( 'wptd-video-popup' );
		
		$output = '';
		
		$shortcode_css = '';
		$shortcode_rand_id = $rand_class = 'wptd-rand-'. self::wptd_random_number();
		$body_class = 'body-'. $shortcode_rand_id;
		
		$shortcode_css .= isset( $width ) && $width != '' ? '.' . esc_attr( $body_class ) . ' .mfp-iframe-holder .mfp-content { max-width: '. esc_attr( $width ) .'px; }' : '';
		$shortcode_css .= isset( $bg_color ) && $bg_color != '' ? '.' . esc_attr( $body_class ) . ' .mfp-bg { background-color: '. esc_attr( $bg_color ) .'; }' : '';
		
		$extra_class = isset( $extra_class ) && !empty( $extra_class ) ? ' '. $extra_class : '';
		
		if( $shortcode_css ) {
			$output .= '<span class="wptd-inline-css" data-css="'. htmlspecialchars( json_encode( $shortcode_css ), ENT_QUOTES, 'UTF-8' ) .'" data-width="900"></span>';
		}
		
		$url = isset( $url ) && !empty( $url ) ? $url : '';
		$trigger = isset( $trigger ) && !empty( $trigger ) ? $trigger : 'text';
		if( $url ){
			switch( $trigger ){
				case "text":
					$text = isset( $text ) && !empty( $text ) ? $text : '';
					if( $text ){
						$output .= '<a href="'. esc_url( $url ) .'" class="wptd-popup-video wptd-popup-type-text'. esc_attr( $extra_class ) .'" data-id="'. esc_attr( $body_class ) .'">'. esc_html( $text ) .'</a>';
					}else{
						$output .= esc_html__( 'You have to enter text on text="" parameter', 'wptd-video-popup' );
					}
				break;
				case "icon":
					$icon = isset( $icon ) && !empty( $icon ) ? $icon : '';
					if( $icon ){
						$output .= '<a href="'. esc_url( $url ) .'" class="wptd-popup-video wptd-popup-type-icon'. esc_attr( $extra_class ) .'" data-id="'. esc_attr( $body_class ) .'"><span class="'. esc_attr( $icon ) .'"></span></a>';
					}else{
						$output .= esc_html__( 'You have to enter icon class on icon="" parameter', 'wptd-video-popup' );
					}
					
				break;
				case "img":
					$img = isset( $img ) && !empty( $img ) ? $img : '';
					if( $img ){
						$output .= '<a href="'. esc_url( $url ) .'" class="wptd-popup-video wptd-popup-type-img'. esc_attr( $extra_class ) .'" data-id="'. esc_attr( $body_class ) .'"><img src="'. esc_html( $img ) .'" alt="'. esc_html__( 'Popup', 'wptd-video-popup' ) .'"></a>';
					}else{
						$output .= esc_html__( 'You have to enter image path on img="" parameter', 'wptd-video-popup' );
					}
				break;
			}
		}
		return $output;				
	}
	
	public function wptd_scripts() {
		wp_register_style( 'magnific-popup', WPTD_EVP_URL .'assets/css/magnific-popup.min.css', array(), '1.1.0', 'all');
		wp_register_style( 'wptd-video-popup', WPTD_EVP_URL .'assets/css/wptd-video-popup.css', array(), '1.0', 'all');
		wp_register_script( 'magnific-popup', WPTD_EVP_URL . 'assets/js/jquery.magnific.popup.min.js',  array( 'jquery' ), '1.1.0', true );
		wp_register_script( 'wptd-video-popup', WPTD_EVP_URL . 'assets/js/wptd-video-popup.js' ,  array( 'jquery' ), '1.0', true );
	}
	
} 
$wptd = new WPTD_Shortcodes;