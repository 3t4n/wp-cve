<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Buffer {

    
	public $configurable_settings;
	
	public $color;
	
	public $color_hover;
	
	public $color_bg;
	
	public $color_bg_hover;
	
	public $color_border;
	
	public $color_border_hover;
	
	public $cta;
	
	public $key;
	
	public $icon_class;
	
	public $share_url;
	
	
	protected static $instance = null;

    
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	
	public function __construct() {
	
		$this->color              = '#ffffff';
		$this->color_hover        = '#ffffff';
		$this->color_bg           = '#323b43';
		$this->color_bg_hover     = '#21272c';
		$this->color_border       = '#21272c';
		$this->color_border_hover = '#000000';
		$this->cta                = __( 'Share', 'social-rocket' );
		$this->key                = 'buffer';
		$this->icon_class         = 'fab fa-buffer';
		$this->share_url          = 'https://bufferapp.com/add?url=%url%';
		
		$this->countable          = true;
		$this->api_url            = 'https://api.bufferapp.com/1/links/shares.json?url=%url%';
		$this->api_throttle       = 1;
		
		$this->configurable_settings = array(
			'cta'          => array(
				'title'        => __( 'Button Text', 'social-rocket' ),
				'default'      => $this->cta,
			),
			'icon_class'   => array(
				'title'        => __( 'Icon CSS Class', 'social-rocket' ),
				'default'      => $this->icon_class,
			),
			'color'        => array(
				'title'        => __( 'Icon Color', 'social-rocket' ),
				'default'      => $this->color,
				'type'         => 'colorpicker',
			),
			'color_hover'  => array(
				'title'        => __( 'Icon Hover Color', 'social-rocket' ),
				'default'      => $this->color_hover,
				'type'         => 'colorpicker',
			),
			'color_bg'     => array(
				'title'        => __( 'Background Color', 'social-rocket' ),
				'default'      => $this->color_bg,
				'type'         => 'colorpicker',
			),
			'color_bg_hover' => array(
				'title'        => __( 'Background Hover Color', 'social-rocket' ),
				'default'      => $this->color_bg_hover,
				'type'         => 'colorpicker',
			),
			'color_border' => array(
				'title'        => __( 'Border Color', 'social-rocket' ),
				'default'      => $this->color_border,
				'type'         => 'colorpicker',
			),
			'color_border_hover' => array(
				'title'        => __( 'Border Hover Color', 'social-rocket' ),
				'default'      => $this->color_border_hover,
				'type'         => 'colorpicker',
			),
		);
		
		add_filter( 'social_rocket_' . $this->key . '_throttle', array( $this, 'api_throttle_value' ) );
		
		do_action( 'social_rocket_' . $this->key, $this );
		
	}
	
	
	public function api_throttle_value( $value ) {
		return $this->api_throttle;
	}
	
	
	public function get_count_from_api( $url ) {
		
		$count = 0;
		
		$api_url = str_replace( '%url%', urlencode( $url ), $this->api_url );
		
		// record the time
		update_option( '_social_rocket_'.$this->key.'_last_call', time() );
		
		// call api
		$response = Social_Rocket::http_get( $api_url );
		
		// parse data
		$response_data = json_decode( $response, true );
		
		if ( isset( $response_data['shares'] ) ) {	
			$count = intval( $response_data['shares'] );
		}
		
		return apply_filters( 'social_rocket_'.$this->key.'_get_count_from_api', $count, $url );
		
	}
	
}
