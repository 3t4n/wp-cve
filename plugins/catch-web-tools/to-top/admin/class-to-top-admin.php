<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    To_Top
 * @subpackage To_Top/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    To_Top
 * @subpackage To_Top/admin
 * @author     Catch Plugins <info@catchplugins.com>
 */
class Catchwebtools_To_Top_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in To_Top_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The To_Top_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$option = catchwebtools_get_options( 'catchwebtools_to_top_options');

		if ( $option['show_on_admin'] ) {
			//Load CSS if  To Top is enabled on admin
			//No need to enqueue dashicons as it is already present in admin
			wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . '../public/css/catchwebtools-to-top-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in To_Top_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The To_Top_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		$option = catchwebtools_get_options( 'catchwebtools_to_top_options');

		if ( $option['show_on_admin'] ) {
			//Load JS if  To Top is enabled on admin
			wp_enqueue_script( $this->plugin_name. '-public', plugin_dir_url( __FILE__ ) . '../public/js/catchwebtools-to-top-public.js', array( 'jquery' ), $this->version, false );

			// Localize the script with new data
			wp_localize_script( $this->plugin_name. '-public', 'catchwebtools_to_top_options', $option );
		}
	}


	/**
	 * Add Options to customizer separating the basic and advanced controls
	 *
	 * @since    1.0.0
	 */
	public function customize_register( $wp_customize ){

		$catchwebtools_to_top_defaults = catchwebtools_to_top_default_options();
		//print_r($defaults); die();

		//Custom Controls
		require CATCHWEBTOOLS_PATH . '/to-top/admin/partials/customizer/customizer-custom-controls.php';

		/* Basic Settings Start */
		$wp_customize->add_section( 'catchwebtools_to_top_basic_settings', array(
			'description'	=> '',
			'panel'			=> 'catchwebtools_options',
			'title'    		=> esc_html__( 'To Top Basic Settings', 'catch-web-tools' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[status]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['status'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[status]', array(
			'label'    			=> esc_html__( 'Check to enable to top', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[status]',
			'type'     			=> 'checkbox',
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[scroll_offset]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['scroll_offset'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[scroll_offset]', array(
			'label'    			=> esc_html__( 'Scroll Offset (px)', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Number of pixels to be scrolled before the button appears', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[scroll_offset]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 0,
		            'max'   => 500,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[icon_opacity]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['icon_opacity'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[icon_opacity]', array(
			'label'    			=> esc_html__( 'Icon Opacity (%)', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[icon_opacity]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 0,
		            'max'   => 100,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[style]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['style'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[style]', array(
			'label'    			=> esc_html__( 'Style', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[style]',
			'type'     			=> 'select',
			'choices'			=> array(
					'icon'              => esc_html__( 'Icon Using Dashicons', 'to-top'),
					'genericon-icon'    => esc_html__( 'Icon Using Genericons', 'to-top'),
					'font-awesome-icon' => esc_html__( 'Icon Using Font Awesome Icons', 'to-top'),
					'image'             => esc_html__( 'Image', 'to-top')
				),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[icon_type]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['icon_type'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( new Catchwebtools_To_Top_Custom_Icons ( $wp_customize, 'catchwebtools_to_top_options[icon_type]', array(
			'label'    			=> esc_html__( 'Select Icon Type', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[icon_type]',
			'type'     			=> 'select',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_icon_setting_active' ),
		) ) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[icon_color]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['icon_color'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control ( $wp_customize, 'catchwebtools_to_top_options[icon_color]', array(
			'label'    			=> esc_html__( 'Icon Color', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[icon_color]',
			'type'     			=> 'color',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_icon_setting_active' ),
		) ) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[icon_bg_color]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['icon_bg_color'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control ( $wp_customize, 'catchwebtools_to_top_options[icon_bg_color]', array(
			'label'    			=> esc_html__( 'Icon Background Color', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[icon_bg_color]',
			'type'     			=> 'color',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_icon_setting_active' ),
		) ) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[icon_size]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['icon_size'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[icon_size]', array(
			'label'    			=> esc_html__( 'Icon Size (px)', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[icon_size]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_icon_setting_active' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[border_radius]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['border_radius'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[border_radius]', array(
			'label'    			=> esc_html__( 'Border Radius (%)', 'catch-web-tools' ),
			'description' 		=> esc_html__( '0 will make the icon background square, 50 will make it a circle', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[border_radius]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 0,
		            'max'   => 50,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_icon_setting_active' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[image]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['image'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control ( $wp_customize, 'catchwebtools_to_top_options[image]', array(
			'label'    			=> esc_html__( 'Image', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[image]',
			'type'     			=> 'image',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_image_setting_active' ),
		) ) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[image_width]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['image_width'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[image_width]', array(
			'label'    			=> esc_html__( 'Image Width (px)', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[image_width]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'max'   => 200,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_image_setting_active' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[image_alt]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['image_alt'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[image_alt]', array(
			'label'    			=> esc_html__( 'Image Alt', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_basic_settings',
			'settings' 			=> 'catchwebtools_to_top_options[image_alt]',
			'type'     			=> 'text',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_image_setting_active' ),
		) );

		/* Basic Settings End */

		/* Advanced Settings Start */

		$wp_customize->add_section( 'catchwebtools_to_top_advance_settings', array(
			'description'	=> '',
			'panel'			=> 'catchwebtools_options',
			'title'    		=> esc_html__( 'To Top Advanced Settings', 'catch-web-tools' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[location]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['location'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[location]', array(
			'label'    			=> esc_html__( 'Location', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[location]',
			'type'     			=> 'select',
			'choices'			=> array(
				'bottom-right'	=> esc_html__( 'Bottom Right', 'catch-web-tools' ),
				'bottom-left'	=> esc_html__( 'Bottom Left', 'catch-web-tools' ),
				'top-right'		=> esc_html__( 'Top Right', 'catch-web-tools' ),
				'top-left'		=> esc_html__( 'Top Left', 'catch-web-tools' ),
				),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[margin_x]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['margin_x'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[margin_x]', array(
			'label'    			=> esc_html__( 'Margin X (px)', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[margin_x]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[margin_y]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['border_radius'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[margin_y]', array(
			'label'      => esc_html__( 'Margin Y (px)', 'catch-web-tools' ),
			'description'=> '',
			'section'    => 'catchwebtools_to_top_advance_settings',
			'settings'   => 'catchwebtools_to_top_options[margin_y]',
			'type'       => 'number',
			'input_attrs'=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[show_on_admin]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['show_on_admin'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[show_on_admin]', array(
			'label'    			=> esc_html__( 'Check to show on WP-ADMIN', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Button will be shown on admin section', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[show_on_admin]',
			'type'     			=> 'checkbox',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[enable_autohide]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['enable_autohide'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[enable_autohide]', array(
			'label'    			=> esc_html__( 'Check to Enable Auto Hide', 'catch-web-tools' ),
			'description' 		=> '',
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[enable_autohide]',
			'type'     			=> 'checkbox',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[autohide_time]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['autohide_time'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[autohide_time]', array(
			'label'    			=> esc_html__( 'Auto Hide Time (secs)', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Button will be auto hidden after this duration in seconds, if enabled', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[autohide_time]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_auto_hide_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[enable_hide_small_device]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['enable_hide_small_device'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[enable_hide_small_device]', array(
			'label'    			=> esc_html__( 'Check to Hide on Small Devices', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Button will be hidden on small devices when the width below matches', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[enable_hide_small_device]',
			'type'     			=> 'checkbox',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[small_device_max_width]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['small_device_max_width'],
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[small_device_max_width]', array(
			'label'    			=> esc_html__( 'Small Device Max Width (px)', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Button will be hidden on devices with lesser or equal width', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_advance_settings',
			'settings' 			=> 'catchwebtools_to_top_options[small_device_max_width]',
			'type'     			=> 'number',
			'input_attrs' 	=> array(
		            'style' => 'width: 55px;',
		            'min'   => 1,
		            'step'  => 1,
		        	),
			'active_callback'	=> array( $this, 'catchwebtools_to_top_is_hide_on_small_devices_enabled' ),
		) );

		/* Advanced Settings End */

		/* Reset Settings Start */

		$wp_customize->add_section( 'catchwebtools_to_top_reset_settings', array(
			'description'	=> '',
			'panel'			=> 'catchwebtools_options',
			'title'    		=> esc_html__( 'To Top Reset Settings', 'catch-web-tools' ),
		) );

		$wp_customize->add_setting( 'catchwebtools_to_top_options[reset]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $catchwebtools_to_top_defaults['reset'],
			'type'				=> 'option',
			'transport'			=> 'postMessage',
		) );

		$wp_customize->add_control( 'catchwebtools_to_top_options[reset]', array(
			'label'    			=> esc_html__( 'Check to Reset All Settings', 'catch-web-tools' ),
			'description' 		=> esc_html__( 'Caution: All data will be lost. Refresh the page after save to view full effects.', 'catch-web-tools' ),
			'section'  			=> 'catchwebtools_to_top_reset_settings',
			'settings' 			=> 'catchwebtools_to_top_options[reset]',
			'type'     			=> 'checkbox',
			'active_callback'	=> array( $this, 'catchwebtools_to_top_enabled' ),
		) );

		/* Reset Settings End */
	}

	/**
	 * Custom scripts on Customizer for Catch Box
	 *
	 * @since To Top 1.0.0
	 */
	function customizer_enqueue_scripts() {

		$option = catchwebtools_get_options( 'catchwebtools_to_top');

	    wp_enqueue_script( 'cwt_to_top_customizer_custom_script', plugin_dir_url( __FILE__ ) . 'js/catchwebtools-to-top-customizer-scripts.js', array( 'jquery' ), '20151223', true );
	}

	/**
	 * Custom styles on Customizer for Catch Box
	 *
	 * @since To Top 1.0.0
	 */
	function customizer_enqueue_styles() {

	    wp_enqueue_style( 'cwt_to_top_customizer_custom_style', plugin_dir_url( __FILE__ ) . 'css/catchwebtools-customizer.css' );

	}



	/**
	 * Sanitizes Checkboxes
	 * @param  $input entered value
	 * @return sanitized output
	 *
	 * @since 1.0.0
	 */
	function sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

	/**
	 * Active Callbacks
	 * @return true or false
	 *
	 * @since 1.0.0
	 */

	function catchwebtools_to_top_enabled( $control ) {
		$enabled = $control->manager->get_setting( 'catchwebtools_to_top_options[status]' )->value();
		//return true only if to_top is enabled
		if( $enabled ) {
			return true;
		}
		else {
			return false;
		}
	}

	function catchwebtools_to_top_is_icon_setting_active( $control ) {
		$enabled = $control->manager->get_setting( 'catchwebtools_to_top_options[status]' )->value();

		$style = $control->manager->get_setting( 'catchwebtools_to_top_options[style]' )->value();

		//return true only if icon setting is selected
		if( ( $style === 'icon' || $style === 'genericon-icon' || $style === 'font-awesome-icon' ) && $enabled ) {
			return true;
		}
		else {
			return false;
		}
	}

	function catchwebtools_to_top_is_image_setting_active( $control ) {
		$enabled = $control->manager->get_setting( 'catchwebtools_to_top_options[status]' )->value();

		$style = $control->manager->get_setting( 'catchwebtools_to_top_options[style]' )->value();

		//return true only if icon setting is selected
		if( $style === 'image' && $enabled ) {
			return true;
		} else {
			return false;
		}
	}

	function catchwebtools_to_top_is_auto_hide_enabled( $control ) {
		$enabled = $control->manager->get_setting( 'catchwebtools_to_top_options[status]' )->value();

		$autohide = $control->manager->get_setting( 'catchwebtools_to_top_options[enable_autohide]' )->value();
		if ( $autohide && $enabled ) {
			return true;
		} else {
			return false;
		}
	}

	function catchwebtools_to_top_is_hide_on_small_devices_enabled( $control ) {
		$enabled = $control->manager->get_setting( 'catchwebtools_to_top_options[status]' )->value();

		$hide_on_small_devices = $control->manager->get_setting( 'catchwebtools_to_top_options[enable_hide_small_device]' )->value();
		if ( $hide_on_small_devices && $enabled ) {
			return true;
		} else {
			return false;
		}
	}
}
