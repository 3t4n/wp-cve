<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Pinterest {

    
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
	
		$this->anchor_data        = 'data-pin-do="none"';
		$this->color              = '#ffffff';
		$this->color_hover        = '#ffffff';
		$this->color_bg           = '#bd081c';
		$this->color_bg_hover     = '#930617';
		$this->color_border       = '#930617';
		$this->color_border_hover = '#62040f';
		$this->cta                = __( 'Pin', 'social-rocket' );
		$this->key                = 'pinterest';
		$this->icon_class         = 'fab fa-pinterest-p';
		$this->share_url          = 'https://pinterest.com/pin/create/button/?url=%url%%pinterest_image%%pinterest_description%';
		
		$this->countable          = true;
		$this->api_url            = 'https://api.pinterest.com/v1/urls/count.json?url=%url%';
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
		
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_pinterest_description' ), 10, 6 );
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_pinterest_image' ), 10, 6 );
		
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
		$response = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $response ); // this is stupid, why can't they give us a properly formed json response?
		$response_data = json_decode( $response, true );
		
		if ( isset( $response_data['count'] ) ) {	
			$count = intval( $response_data['count'] );
		}
		
		return apply_filters( 'social_rocket_'.$this->key.'_get_count_from_api', $count, $url );
	}
	
	
	public function wildcard_pinterest_image( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$SR  = Social_Rocket::get_instance();
		
		$media = '';
		
		// get the image
		$pinterest_image = false;
		if ( $type === 'post' ) {
			$pinterest_image = get_post_meta( $id, 'social_rocket_pinterest_image', true );
		} elseif ( $type === 'term' ) {
			$pinterest_image = get_term_meta( $id, 'social_rocket_pinterest_image', true );
		}
		
		$featured_image = get_post_thumbnail_id( $id );
		
		if ( $pinterest_image ) {
			
			// 1) use pinterest image set in post's post meta
			$pinterest_image_url = wp_get_attachment_url( $pinterest_image );
			
			if ( $pinterest_image_url > '' ) {
				$media = '&media=' . urlencode( $pinterest_image_url );
			}
			
		} elseif ( $SR->_isset( $SR->settings['pinterest']['image_fallback'] ) === 'featured' && $featured_image ) {
		
			// 2) use post's featured image, if available
			$featured_image_url = wp_get_attachment_url( $featured_image );
			
			if ( $featured_image_url > '' ) {
				$media = '&media=' . urlencode( $featured_image_url );
			}
			
		} else {
		
			// 3) if none of the above, user will be asked to choose an image. so we can omit the &media= argument.
			$media = '';
			
		}
		
		$output = str_replace( '%pinterest_image%', $media, $output );
		
		return $output;
		
	}
	
	
	public function wildcard_pinterest_description( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$SR = Social_Rocket::get_instance();
		
		// 1) get the image
		$pinterest_image      = false;
		$pinterest_image_url  = false;
		$pinterest_image_desc = false;
		$pinterest_image_alt  = false;
		if ( $type === 'post' ) {
			$pinterest_image = get_post_meta( $id, 'social_rocket_pinterest_image', true );
		} elseif ( $type === 'term' ) {
			$pinterest_image = get_term_meta( $id, 'social_rocket_pinterest_image', true );
		}
		if ( $pinterest_image ) {
			$pinterest_image_url  = wp_get_attachment_url( $pinterest_image );
			$pinterest_image_desc = get_post_meta( $pinterest_image, 'social_rocket_pinterest_description', true );
			$pinterest_image_alt  = get_post_meta( $pinterest_image, '_wp_attachment_image_alt', true );
		}
		
		// 2) get the description
		$pinterest_description = false;
		if ( $pinterest_image_desc > '' ) {
			// i) use pinterest description from image's (attachment's) post meta
			$pinterest_description = $pinterest_image_desc;
		} else {
			// ii) use pinterest description from post's post meta
			if ( $type === 'post' ) {
				$pinterest_description = get_post_meta( $id, 'social_rocket_pinterest_description', true );
			} elseif ( $type === 'term' ) {
				$pinterest_description = get_term_meta( $id, 'social_rocket_pinterest_description', true );
			}
		}
		if ( ! $pinterest_description > '' ) {
			// iii) if still nothing, use image's alt text
			$pinterest_description = $pinterest_image_alt;
		}
		if ( ! $pinterest_description > '' ) {
			// iv) last resort, use title
			if ( $type === 'post' ) {
				$pinterest_description = get_the_title( $id );
			}/* elseif ( $type === 'term' ) {
				$pinterest_description = get_the_archive_title( $id );
			}*/ else {
				$pinterest_description = wp_title( null, false );
			}
		}
		
		// 3) add via @username, if set
		$pinterest_username = $SR->_isset( $SR->settings['social_identity']['pinterest'] );
		if ( $pinterest_description && $pinterest_username > '' ) {
 			$pinterest_description .= ' ' . __( 'via', 'social-rocket' ) . ' @' . str_replace( '@' , '' , $pinterest_username );
 		}
		
		// 4) wrap up
		if ( $pinterest_description > '' ) {
			$pinterest_description = '&description=' . rawurlencode( $pinterest_description );
		} else {
			$pinterest_description = '';
		}
		
		$output = str_replace( '%pinterest_description%', $pinterest_description, $output );
		
		return $output;
		
	}
	
}
