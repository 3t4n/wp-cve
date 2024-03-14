<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class CLP_Customizer {
	
	// public $parent = null;

	public $settings;

	public function __construct( $wp_customize ) {

		// $this->parent = $parent;
		$this->init_settings();
		$this->register_settings( $wp_customize );

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer' ) );
		add_action( 'customize_preview_init', array( $this, 'change_login_template' ) );
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview' ) );
	}
	
	/**
	 * displays custom login template for customizer
	 * @since 1.0.0
	**/
	public function change_login_template() {
		include_once( CLP_PLUGIN_DIR . 'includes/template-customization.php' );
	}

	/**
	 * enqueue customizer scripts for custom functions
	 * @since 1.0.0
	**/
	public function enqueue_customizer() {
		wp_enqueue_script( 'clp-customizer-functions', CLP_PLUGIN_PATH . 'assets/js/customizer-functions.js', array( 'jquery', 'customize-controls' ), CLP_Helper_Functions::assets_version('assets/js/customizer-functions.js'), true );
		wp_enqueue_script( 'clp-customizer-controls', CLP_PLUGIN_PATH . 'assets/js/customizer-controls.js', array( 'jquery' ), CLP_Helper_Functions::assets_version('assets/js/customizer-controls.js'), true );
		wp_localize_script(
			'clp-customizer-controls', 'clpCfg', array(
				'siteurl' => get_option( 'siteurl' ),
				'url' => CLP_PLUGIN_PATH,
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'proUrl' => 'https://customloginpage.com/',
				'clpCustomizerUrl' => add_query_arg( array( 'autofocus[panel]' => 'clp_panel' ), admin_url( 'customize.php' ) )
			)
		);
		wp_enqueue_style( 'clp-customizer-controls', CLP_PLUGIN_PATH . 'assets/css/customizer-controls.css', array(), CLP_Helper_Functions::assets_version('assets/css/customizer-controls.css') );

	}
	/**
	 * Enqueue customizer live preview script
	 * @since 1.0.0
	**/
	public function enqueue_customizer_preview() {
		wp_enqueue_script( 'clp-customizer-preview', CLP_PLUGIN_PATH . 'assets/js/customizer-preview.js', array( 'jquery', 'customize-preview' ), CLP_Helper_Functions::assets_version('assets/js/customizer-preview.js'), true );
		wp_localize_script(
			'clp-customizer-preview', 'clpCfg', array(
				'url' => CLP_PLUGIN_PATH,
				'ajaxUrl' => admin_url('admin-ajax.php'),
			
			)
		);
	}	

	/**
	 * Populate $settings class variable with settings fields
	 * @since 1.0.0
	**/
	public function init_settings() {
		$customizer_settings = new CLP_Customizer_Settings;
		$this->settings = $customizer_settings->get_settings_fields();
	}

	/**
	 * Register settings in the customizer
	 */
	public function register_settings( $wp_customize ) {
		$wp_customize->add_panel(
			'clp_panel',
			array(
				'title'          => esc_html__( 'Custom Login Page', 'clp-custom-login-page' ),
			)
		);

		foreach ( $this->settings as $section => $control ) {

			// add sections
			$wp_customize->add_section(
				'clp_' . $section,
				array(
					'title'       => $control['title'],
					'description' => $control['description'],
					'panel'       => 'clp_panel',
				)
			);

			foreach ( $control['fields'] as $setting ) {
				// register settings
				$wp_customize->add_setting( $setting['id'], array(
					'type'				=> 'option',
					'default'           => isset($setting['default']) ? $setting['default'] : '',
					'transport'         => isset($setting['transport']) ? $setting['transport'] : 'refresh',
					'sanitize_callback' => isset($setting['sanitize_callback']) ? $setting['sanitize_callback'] : '',
				) );

				// build control args
				$control_args = array(
					'label'     =>  isset($setting['label']) ? $setting['label'] : '',
					'section'   => 'clp_' . $section,
					'settings'  => $setting['id'],
					'type'   	=> $setting['type'],
				);

				if ( isset( $setting['choices'] ) ) {
					$control_args['choices'] = $setting['choices'];
				}

				if ( isset( $setting['active_callback'] ) ) {
					$control_args['active_callback'] = $setting['active_callback'];
				}

				if ( isset( $setting['input_attrs'] ) ) {
					$control_args['input_attrs'] = $setting['input_attrs'];
				}
				
				if ( isset( $setting['default'] ) ) {
					$control_args['default'] =  $setting['default'];
				}

				if ( isset( $setting['description'] ) ) {
					$control_args['description'] =  $setting['description'];
				}

				if ( isset( $setting['code_type'] ) ) {
					$control_args['code_type'] =  $setting['code_type'];
				}

				// register controls
				switch ( $setting['type'] ) {

					case 'range':
						$wp_customize->add_control(
							new CLP_Customizer_Range_Value_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'color':
						$wp_customize->add_control(
							new WP_Customize_Color_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;
						
					case 'alpha-color':
						$wp_customize->add_control(
							new CLP_Customizer_Alpha_Color_Picker_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'unsplash':
						$wp_customize->add_control(
							new CLP_Customizer_Unsplash_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'media':
						$control_args['mime_type'] = $setting['mime_type'];
						$wp_customize->add_control(
							new WP_Customize_Media_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'separator':
						$wp_customize->add_control(
							new CLP_Customizer_Separator_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'toggle':
						$wp_customize->add_control(
							new CLP_Customizer_Toggle_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'google-fonts':
						$wp_customize->add_control(
							new CLP_Customizer_Google_Fonts_control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'clp-template':
						$wp_customize->add_control(
							new CLP_Customizer_template_Control( $wp_customize, $setting['id'], $control_args )
						);
						break;

					case 'code':
						unset($control_args['type']);
						$wp_customize->add_control(
							new WP_Customize_Code_Editor_Control( $wp_customize, $setting['id'], $control_args )
						);
					
						break;

					case 'clp_import_export':
						unset($control_args['type']);
						$wp_customize->add_control(
							new CLP_Customizer_Import_Export_control( $wp_customize, $setting['id'], $control_args )
						);
					
						break;
						
					case 'editor':
						unset($control_args['type']);
						$wp_customize->add_control(
							new CLP_Customizer_Editor_control( $wp_customize, $setting['id'], $control_args )
						);
					
						break;
					case 'social-icons':
						unset($control_args['type']);
						$wp_customize->add_control(
							new CLP_Customizer_Social_Icons_Control( $wp_customize, $setting['id'], $control_args )
						);
					
						break;

					default:
						$wp_customize->add_control( $setting['id'], $control_args );
						break;
				}

		
			}

		}
		

	}

}
