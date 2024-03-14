<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Twitter {

    
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
		$this->color_bg           = '#00aced';
		$this->color_bg_hover     = '#0096cc';
		$this->color_border       = '#0096cc';
		$this->color_border_hover = '#007099';
		$this->cta                = __( 'Tweet', 'social-rocket' );
		$this->key                = 'twitter';
		$this->icon_class         = 'fab fa-twitter';
		$this->share_url          = 'https://twitter.com/intent/tweet?text=%tweet%&url=%url%';
		
		$this->countable          = true;
		$this->api_url            = null;
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
		
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_tweet' ), 10, 6 );
		
		add_filter( 'social_rocket_'.$this->key.'_throttle', array( $this, 'api_throttle_value' ) );
		
		do_action( 'social_rocket_' . $this->key, $this );
		
	}
	
	
	public function api_throttle_value( $value ) {
		return $this->api_throttle;
	}
	
	
	public function get_count_from_api( $url ) {
		
		// record the time
		update_option( '_social_rocket_'.$this->key.'_last_call', time() );
		
		return apply_filters( 'social_rocket_'.$this->key.'_get_count_from_api', 0, $url );
	}
	
	
	public function wildcard_tweet( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		
		$SR = Social_Rocket::get_instance();
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$custom_tweet = false;
		if ( $type === 'post' ) {
			$custom_tweet = get_post_meta( $id, 'social_rocket_twitter_message', true );
		} elseif ( $type === 'term' ) {
			$custom_tweet = get_term_meta( $id, 'social_rocket_twitter_message', true );
		}
		if ( $custom_tweet > '' ) {
			$tweet = $custom_tweet;
		} elseif ( $type === 'post' ) {
			$tweet = html_entity_decode( the_title_attribute( 'echo=0' ) );
		} else {
			$tweet = html_entity_decode( wp_title( null, false ) );
		}
		
		$tweet = rawurlencode( $tweet );
		
		if ( $SR->_isset( $SR->settings['social_identity']['twitter'], '' ) > '' ) {
			$username = str_replace( '@', '', $SR->settings['social_identity']['twitter'] );
			$tweet .= '&via=' . $username;
		}
		
		$output = str_replace( '%tweet%', $tweet, $output );
		
		return $output;
		
	}
	
}
