<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class OTB_Beam_Me_Up_Scotty_Settings {

	/**
	 * The single instance of OTB_Beam_Me_Up_Scotty_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;
		
		$this->base = 'beam_me_up_scotty_';
		
		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );
		
		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );
	}
	
	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}
	
	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {
		
		wp_enqueue_script( 'jscolor-js', $this->parent->assets_url . 'js/jscolor' . $this->parent->script_suffix . '.js', array( 'jquery' ), '2.0.4' );		
		
		wp_register_script( $this->parent->_text_domain . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'jquery' ), $this->parent->_version );				
		wp_enqueue_script( $this->parent->_text_domain . '-settings-js' );
		
	}	
	
	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {
		
		// Create a new settings configuration to be used on the Settings tab
		$settings['general'] = array(
			'title'					=> __( 'Button Settings', 'beam-me-up-scotty' ),
			'description'			=> __( 'Customize your back to top button with the following settings', 'beam-me-up-scotty' ),
			'fields'				=> array(
				array(
					'id' 			=> 'size',
					'label'			=> __( 'Size', 'beam-me-up-scotty' ),
					'type'			=> 'select',
					'options'		=> array(
						'small'	 => 'Small',
						'medium' => 'Medium',
						'large'  => 'Large',
						'custom' => 'Custom'
					),
					'default'		=> 'medium',
					'description'	=> ''
				),
				array(
					'id' 			=> 'width',
					'label'			=> __( 'Width', 'beam-me-up-scotty' ),
					'type'			=> 'pixels',
					'default'		=> '49',
					'description'	=> ''
				),
				array(
					'id' 			=> 'height',
					'label'			=> __( 'Height', 'beam-me-up-scotty' ),
					'type'			=> 'pixels',
					'default'		=> '49',
					'description'	=> ''
				),
				array(
					'id' 			=> 'style',
					'label'			=> __( 'Style', 'beam-me-up-scotty' ),
					'type'			=> 'select',
					'options'		=> array(
						'square'  => 'Square',
						'rounded' => 'Rounded',
						'circle'  => 'Circle'
					),
					'default'		=> 'square',
					'description'	=> ''
				),
				array(
					'id' 			=> 'indentation',
					'label'			=> __( 'Side Indentation', 'beam-me-up-scotty' ),
					'type'			=> 'pixels',
					'default'		=> '20',
					'description'	=> ''
				),
				array(
					'id' 			=> 'bottom_indentation',
					'label'			=> __( 'Bottom Indentation', 'beam-me-up-scotty' ),
					'type'			=> 'pixels',
					'default'		=> '20',
					'description'	=> ''
				),
				array(
					'id' 			=> 'color',
					'label'			=> __( 'Color', 'beam-me-up-scotty' ),
					'type'			=> 'color',
					'default'		=> '#21759B',
					'description'	=> ''
				),
				array(
					'id' 			=> 'opacity',
					'label'			=> __( 'Opacity', 'beam-me-up-scotty' ),
					'type'			=> 'range',
					'default'		=> '1',
					'show_labels'	=> true,
					'min_labels' 	=> true,
			    	'input_attrs' => array(
			    		'min'   => 0,
			    		'max'   => 1,
			    		'step'  => 0.1,
			    		'style' => 'color: #000000'
			   		),
					'description'	=> ''
				),
				array(
					'id' 			=> 'rollover_color',
					'label'			=> __( 'Rollover Color', 'beam-me-up-scotty' ),
					'type'			=> 'color',
					'default'		=> '#3F84A4',
					'description'	=> ''
				),
				array(
					'id' 			=> 'rollover_opacity',
					'label'			=> __( 'Rollover Opacity', 'beam-me-up-scotty' ),
					'type'			=> 'range',
					'default'		=> '1',
					'show_labels'	=> true,
					'min_labels' 	=> true,
			    	'input_attrs' => array(
			    		'min'   => 0,
			    		'max'   => 1,
			    		'step'  => 0.1,
			    		'style' => 'color: #000000'
			   		),
					'description'	=> ''
				),
				array(
					'id' 			=> 'icon_color',
					'label'			=> __( 'Arrow Color', 'beam-me-up-scotty' ),
					'type'			=> 'color',
					'default'		=> '#FFFFFF',
					'description'	=> ''
				),
				array(
					'id' 			=> 'animation',
					'label'			=> __( 'Show / Hide Animation', 'beam-me-up-scotty' ),
					'type'			=> 'select',
					'options'		=> array(
						'none' 	=> 'None',
						'slide' => 'Slide',
						'fade' 	=> 'Fade'
					),
					'default'		=> 'none',
					'description'	=> ''
				),
				array(
					'id' 			=> 'hide_when_inactive',
					'label'			=> __( 'Hide when not scrolling', 'beam-me-up-scotty' ),
					'type'			=> 'checkbox',
					'default'		=> false,
					'description'	=> ''
				),
				array(
					'id' 			=> 'hide_delay',
					'label'			=> __( 'Delay before hiding', 'beam-me-up-scotty' ),
					'type'			=> 'milliseconds',
					'default'		=> '1200',
					'description'	=> ''
				)
			)
		);
		
		$settings = apply_filters( $this->parent->_text_domain . '_settings_fields', $settings );
	
		return $settings;
	}
	
	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {
	
			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}
	
			foreach ( $this->settings as $section => $data ) {
	
				if ( $current_section && $current_section != $section ) continue;
	
				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_text_domain . '_settings' );
	
				foreach ( $data['fields'] as $field ) {
					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}
	
					// Register field
					$option_name = $this->base  . $field['id'];
					
					register_setting( $this->parent->_text_domain . '_settings', $option_name, $validation );
	
					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_text_domain . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}
	
				if ( ! $current_section ) break;
			}
		}
	}
	
	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}
	
	/**
	 * Main OTB_Beam_Me_Up_Scotty_Settings Instance
	 *
	 * Ensures only one instance of OTB_Beam_Me_Up_Scotty_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see OTB_Beam_Me_Up_Scotty()
	 * @return Main OTB_Beam_Me_Up_Scotty_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}