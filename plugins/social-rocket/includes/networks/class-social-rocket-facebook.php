<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Facebook {

    
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
	
	public $api_url;
	
	
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
		$this->color_bg           = '#3b5998';
		$this->color_bg_hover     = '#324b81';
		$this->color_border       = '#324b81';
		$this->color_border_hover = '#0a4fa9';
		$this->cta                = __( 'Share', 'social-rocket' );
		$this->key                = 'facebook';
		$this->icon_class         = 'fab fa-facebook-f';
		$this->share_url          = 'http://www.facebook.com/share.php?u=%url%';
		
		$this->countable          = true;
		$this->api_url            = 'https://graph.facebook.com/v7.0/?fields=engagement&id=%url%&access_token=%access_token%';
		
		$this->api_throttle       = 36; // max 100 requests per hour. FB's rate limit is 200 per hour, but they send warning emails around 75-80% of this which freaks people out.
		
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
		
		add_filter( 'social_rocket_'.$this->key.'_throttle', array( $this, 'api_throttle_value' ) );
		
		do_action( 'social_rocket_' . $this->key, $this );
		
	}
	
	
	public function api_throttle_value( $value ) {
		return $this->api_throttle;
	}
	
	public function get_count_from_api( $url ) {
		
		$SR = Social_Rocket::get_instance();
		$access_token = $SR->_isset( $SR->settings['facebook']['access_token'], '' );
		
		if ( ! ( $access_token > '' ) ) {
			return 0; // don't even attempt it if no access token provided, it won't work.
		}
		
		$api_url = str_replace( '%url%', urlencode( $url ), $this->api_url );
		$api_url = str_replace( '%access_token%', $access_token, $api_url );
		
		// record the time
		update_option( '_social_rocket_'.$this->key.'_last_call', time() );
		
		// call api
		$response = Social_Rocket::http_get( $api_url );
		
		// parse data
		$response_data = json_decode( $response, true );
		
		if ( isset( $response_data['error'] ) ) {
			// if it's an invalid oauth token, we'll display an admin notice
			if ( isset( $response_data['error']['type'] ) && $response_data['error']['type'] === 'OAuthException' ) {
				update_option( '_social_rocket_facebook_invalid_token', $response_data['error']['message'] );
			}
			return false;
		} else {
			// clear any previous error, if present
			delete_option( '_social_rocket_facebook_invalid_token' );
		}
		
		$count = 0;
		
		if ( isset( $response_data['engagement']['reaction_count'] ) ) {
			$count = $count + intval( $response_data['engagement']['reaction_count'] );
		}
		if ( isset( $response_data['engagement']['comment_count'] ) ) {
			$count = $count + intval( $response_data['engagement']['comment_count'] );
		}
		if ( isset( $response_data['engagement']['share_count'] ) ) {
			$count = $count + intval( $response_data['engagement']['share_count'] );
		}
		if ( isset( $response_data['engagement']['comment_plugin_count'] ) ) {
			$count = $count + intval( $response_data['engagement']['comment_plugin_count'] );
		}

		return $count;
		
	}
	
}
