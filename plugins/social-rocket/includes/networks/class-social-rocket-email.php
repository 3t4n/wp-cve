<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Email {

    
	public $configurable_settings;
	
	public $color;
	
	public $color_hover;
	
	public $color_bg;
	
	public $color_bg_hover;
	
	public $color_border;
	
	public $color_border_hover;
	
	public $cta;
	
	public $email_body;
	
	public $email_subject;
	
	public $key;
	
	public $icon_class;
	
	public $share_url;
	
	public $wrapper_class;
	
	
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
		$this->color_bg           = '#cccccc';
		$this->color_bg_hover     = '#b3b3b3';
		$this->color_border       = '#b3b3b3';
		$this->color_border_hover = '#999999';
		$this->cta                = __( 'Email', 'social-rocket' );
		$this->email_body         = __( 'I saw this and thought of you! %url%', 'social-rocket' );
		$this->email_subject      = '%page_title%';
		$this->key                = 'email';
		$this->icon_class         = 'fas fa-envelope';
		$this->share_url          = 'mailto:?Subject=%email_subject%&Body=%email_body%';
		$this->wrapper_class      = 'social-rocket-no-pop';
		
		$this->configurable_settings = array(
			'cta'           => array(
				'title'        => __( 'Button Text', 'social-rocket' ),
				'default'      => $this->cta,
			),
			'email_subject' => array(
				'title'        => __( 'Email Subject', 'social-rocket' ),
				'default'      => $this->email_subject,
			),
			'email_body'    => array(
				'title'        => __( 'Email Body', 'social-rocket' ),
				'default'      => $this->email_body,
				'desc'         => __( 'The following wildcards are allowed: %page_title%, %url%', 'social-rocket' ),
				'type'         => 'html',
			),
			'icon_class'    => array(
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
		
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_email_body' ), 10, 6 );
		add_filter( 'social_rocket_get_share_url', array( $this, 'wildcard_email_subject' ), 10, 6 );
		
		do_action( 'social_rocket_' . $this->key, $this );
		
	}
	
	
	public function wildcard_email_body( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$SR = Social_Rocket::get_instance();
		
		if ( $type === 'post' ) {
			$page_title = the_title_attribute( 'echo=0' );
		} else {
			$page_title = wp_title( null, false );
		}
		
		$email_body = '';
		if ( isset( $SR->settings[$scope.'_buttons']['networks'][$this->key]['settings']['email_body'] ) ) {
			$email_body = $SR->settings[$scope.'_buttons']['networks'][$this->key]['settings']['email_body'];
		}
		$email_body = $email_body > '' ? $email_body : $this->email_body;
		$email_body = str_replace( '%page_title%', $page_title, $email_body );
		$email_body = str_replace( '%url%', $url, $email_body );
		
		$output = str_replace( '%email_body%', rawurlencode( $email_body ), $output );
		return $output;
	}
	
	
	public function wildcard_email_subject( $output, $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline' ) {
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$SR = Social_Rocket::get_instance();
		
		if ( $type === 'post' ) {
			$page_title = the_title_attribute( 'echo=0' );
		} else {
			$page_title = wp_title( null, false );
		}
		
		$email_subject = '';
		if ( isset( $SR->settings[$scope.'_buttons']['networks'][$this->key]['settings']['email_subject'] ) ) {
			$email_subject = $SR->settings[$scope.'_buttons']['networks'][$this->key]['settings']['email_subject'];
		}
		$email_subject = $email_subject > '' ? $email_subject : $this->email_subject;
		$email_subject = str_replace( '%page_title%', $page_title, $email_subject );
		$email_subject = str_replace( '%url%', $url, $email_subject );
		
		$output = str_replace( '%email_subject%', rawurlencode( $email_subject ), $output );
		return $output;
	}
	
}
