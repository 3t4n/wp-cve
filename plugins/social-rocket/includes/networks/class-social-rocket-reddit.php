<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Reddit {

    
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
		$this->color_bg           = '#fe4403';
		$this->color_bg_hover     = '#cb3701';
		$this->color_border       = '#cb3701';
		$this->color_border_hover = '#982901';
		$this->cta                = __( 'Share', 'social-rocket' );
		$this->key                = 'reddit';
		$this->icon_class         = 'fab fa-reddit-alien';
		$this->share_url          = 'http://reddit.com/submit?url=%url%&title=%page_title%';
		
		$this->countable          = true;
		$this->api_url            = 'https://www.reddit.com/api/info.json?url=%url%';
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
		
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_page_title' ), 10, 6 );
		
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
		// something to keep in mind: https://github.com/reddit-archive/reddit/wiki/API
		$response = Social_Rocket::http_get( $api_url );
		
		// parse data
		$response_data = json_decode( $response, true );
		
		if ( isset( $response_data['data']['children'] ) ) {	
			foreach ( $response_data['data']['children'] as $child ) {
				if ( isset( $child['data']['score'] ) ) {
					$count = $count + intval( $child['data']['score'] );
				}
			}
		}
		
		return apply_filters( 'social_rocket_'.$this->key.'_get_count_from_api', $count, $url );
		
	}
	
	
	public function wildcard_page_title( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		if ( $type === 'post' ) {
			$page_title = the_title_attribute( 'echo=0' );
		} else {
			$page_title = wp_title( null, false );
		}
		$output = str_replace( '%page_title%', $page_title, $output );
		return $output;
	}
	
}
