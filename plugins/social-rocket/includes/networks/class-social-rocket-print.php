<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Print {

    
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
		$this->color_bg           = '#787878';
		$this->color_bg_hover     = '#666666';
		$this->color_border       = '#666666';
		$this->color_border_hover = '#4d4d4d';
		$this->cta                = __( 'Print', 'social-rocket' );
		$this->key                = 'print';
		$this->icon_class         = 'fas fa-print';
		$this->share_url          = 'javascript:window.print()';
		$this->wrapper_class      = 'social-rocket-no-pop';
		
		$this->configurable_settings = array(
			'cta'           => array(
				'title'        => __( 'Button Text', 'social-rocket' ),
				'default'      => $this->cta,
			),
			'icon_class'    => array(
				'title'        => __( 'Icon CSS Class', 'social-rocket' ),
				'default'      => $this->icon_class,
			),
			'color'         => array(
				'title'        => __( 'Icon Color', 'social-rocket' ),
				'default'      => $this->color,
				'type'         => 'colorpicker',
			),
			'color_hover'   => array(
				'title'        => __( 'Icon Hover Color', 'social-rocket' ),
				'default'      => $this->color_hover,
				'type'         => 'colorpicker',
			),
			'color_bg'      => array(
				'title'        => __( 'Background Color', 'social-rocket' ),
				'default'      => $this->color_bg,
				'type'         => 'colorpicker',
			),
			'color_bg_hover' => array(
				'title'        => __( 'Background Hover Color', 'social-rocket' ),
				'default'      => $this->color_bg_hover,
				'type'         => 'colorpicker',
			),
			'color_border'  => array(
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
		
		do_action( 'social_rocket_' . $this->key, $this );
		
	}
	
}
