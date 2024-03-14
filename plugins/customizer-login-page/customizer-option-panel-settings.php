<?php
class Customizer_Login_Entities {

	public function __construct() {
		$this->_hooks();
	}

	/**
	* Hook into actions and filters
	*
	* @since 1.0.0
	*/
	private function _hooks() {
		// WP Customizer
		add_action( 'customize_register', array( $this, 'awp_login_customize_register' ) );
		
		// wordpress Customizer login
		add_action( 'login_enqueue_scripts', array( $this, 'wp_login_customizer' ) );
		
		// Filters link URL of the header logo above login form.
		add_action( 'login_headerurl', array( $this, 'wp_login_customizer_logo_url' ) );
		
		//Filters the Site title attribute of the header logo above login form.
		add_action( 'login_headertitle', array( $this, 'wp_login_customizer_logo_url_title' ) );
			
	}	
	
	/**
	 * Preview JS
	 * @since 1.0.0
	 */
	
	function awp_login_customize_register( $wp_customize ) {
		//range-bar php file
		include AWP_CLP_PLUGIN_DIR .'include/range-bar.php';
		
		$wp_customize->add_panel(
			'awp_login_customizer_panel', array(
				'priority'       => 30,
				'capability'     => 'edit_theme_options',
				'title'          => __( 'Customizer Login', AWP_CPL_TXTDM ),
				'description'    => __( 'This section allows you to customize the login page of your website.<br/>Login Customizer by <a target="_blank" rel="nofollow" href="http://awplife.com/">A WP Life</a>', AWP_CPL_TXTDM ),
			)
		);
		
		//logo setting
		$wp_customize->add_section(
			'awp_login_logo_section', array(
				'priority' => 5,
				'title' => __( 'Logo Settings', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		); 
			

			$wp_customize->add_setting(
			'awp_login_logo', array(
				'type' => 'option',
				'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'awp_login_logo', array(
						'label' => __( 'Login Page Logo', AWP_CPL_TXTDM ),
						'section' => 'awp_login_logo_section',
						'priority' => 5,
						'settings' => 'awp_login_logo',
					)
				)
			);
					
			$wp_customize->add_setting( 'awp_login_logo_width', array(
					'default'        => get_theme_mod( 'awp_login_logo_width', 84 ),
					'type' 			 => 'option',
					'capability'     => 'edit_theme_options',
					'sanitize_callback'     => 'absint',
			) );
			
			 $wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_logo_width', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_logo_section',
				'settings' => 'awp_login_logo_width',
				'priority' => 10,
				'label'    => __( 'Logo Width', AWP_CPL_TXTDM ),
				'description'    => __( 'Logo Width by Default 84px', AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 540,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) );   

			$wp_customize->add_setting(
				'awp_login_logo_height', array(
					'default' 	 => 84,
					'type'		 => 'option',
					'capability' => 'edit_theme_options',
					'sanitize_callback'     => 'absint',
				)
			);
			
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_logo_height', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_logo_section',
				'settings' => 'awp_login_logo_height',
				'priority' => 15,
				'label'    => __( 'Logo Height', AWP_CPL_TXTDM ),
				'description'    => __( 'Logo Height by Default 84px', AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 540,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) );   

			$wp_customize->add_setting(
				'awp_login_logo_padding', array(
					'default' 	=> 5,
					'type' 		=> 'option',
					'capability' => 'edit_theme_options',
				)
			);
			
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_logo_padding', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_logo_section',
				'settings' => 'awp_login_logo_padding',
				'priority' => 20,
				'label'    => __( 'Padding Bottom', AWP_CPL_TXTDM ),
				'description'    => __( 'Set Gap Between Logo and Login Form', AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 100,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) );   

		// 2. background setting
		$wp_customize->add_section(
			'awp_login_background_section', array(
				'priority' => 10,
				'title' => __( 'Login Page Background', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);
		
			$wp_customize->add_setting(
				'awp_login_bg_image', array(
					'default' 	=>	AWP_CLP_PLUGIN_URL . 'images/background_image_1.jpg',
					'type' 		=> 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize, 'awp_login_bg_image', array(
						'label' => __( 'Page Background Image', AWP_CPL_TXTDM ),
						'section' => 'awp_login_background_section',
						'priority' => 5,
						'settings' => 'awp_login_bg_image',
					)
				)
			);
			
			
			 /* Color setting */
			$wp_customize->add_setting( 'awp_login_customizer_bg_color', array(
				'default'    => '#e5e5e5',
				'capability' => 'edit_theme_options',
				'type' => 'option',
			));	

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_bg_color', array(
						'label' => __( 'Page Background Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_background_section',
						'priority' => 10,
						'settings' => 'awp_login_customizer_bg_color',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_bg_size', array(
					'default'     		=> 'cover',
					'sanitize_callback' => 'awp_customizer_sanitize_radio',
				)
			);

			$wp_customize->add_control(
				'awp_login_customizer_bg_size', array(
					'type'      => 'radio',
					'label' => __( 'Page Background Image Size', AWP_CPL_TXTDM ),
					'section' => 'awp_login_background_section',
					'priority' => 15,
					'choices'   => array(
						'auto'      => __( 'auto', AWP_CPL_TXTDM ),
						'contain'   => __( 'contain', AWP_CPL_TXTDM ),
						'cover'     => __( 'cover', AWP_CPL_TXTDM ),
						'initial'   => __( 'initial', AWP_CPL_TXTDM ),
						'inherit'   => __( 'inherit', AWP_CPL_TXTDM ),
						'unset'     => __( 'unset', AWP_CPL_TXTDM ),			
					),
				)
			);
		// 2. background setting finish
		
		// 3. Form Background
		$wp_customize->add_section(
			'awp_login_customizer_form_bg_section', array(
				'priority' => 15,
				'title' => __( 'Customize Form Background', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);
		
			$wp_customize->add_setting(
				'awp_login_customizer_form_bg_image', array(
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize, 'awp_login_customizer_form_bg_image', array(
						'label' => __( 'Form Background Image', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_form_bg_section',
						'priority' => 5,
						'settings' => 'awp_login_customizer_form_bg_image',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_form_bg_color', array(
					'default' => '#FFF',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_form_bg_color', array(
						'label' => __( 'Form Background Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_form_bg_section',
						'priority' => 10,
						'settings' => 'awp_login_customizer_form_bg_color',
					)
				)
			);			
			
			$wp_customize->add_setting(
				'awp_login_customizer_form_bg_size', array(
					'default'     		=> 'cover',
					'sanitize_callback' => 'awp_customizer_sanitize_radio',
				)
			);

			$wp_customize->add_control(
				'awp_login_customizer_form_bg_size', array(
					'type'      => 'radio',
					'label' => __( 'Form Background Image Size', AWP_CPL_TXTDM ),
					'section' => 'awp_login_customizer_form_bg_section',
					'priority' => 15,
					'choices'   => array(
						'auto'      => __( 'auto', AWP_CPL_TXTDM ),
						'contain'   => __( 'contain', AWP_CPL_TXTDM ),
						'cover'     => __( 'cover', AWP_CPL_TXTDM ),
						'initial'   => __( 'initial', AWP_CPL_TXTDM ),
						'inherit'   => __( 'inherit', AWP_CPL_TXTDM ),
						'unset'     => __( 'unset', AWP_CPL_TXTDM ),			
					),
				)
			);
			
		//4. Form Styling
		$wp_customize->add_section(
			'awp_login_customizer_form_section', array(
				'priority' => 20,
				'title' => __( 'Customize Form Styling', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);	

			$wp_customize->add_setting(
				'awp_login_customizer_form_width', array(
					'default' => 350,
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);
			
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_form_width', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_form_section',
				'settings' => 'awp_login_customizer_form_width',
				'priority' => 15,
				'label'    => __( 'Form Width', AWP_CPL_TXTDM ),
				'description'    => __( 'Form Width by Default 350px', AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 320,
					'max'    => 540,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) );   

			$wp_customize->add_setting(
				'awp_login_customizer_form_height', array(
					'default' => 224,
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);
			
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_form_height', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_form_section',
				'settings' => 'awp_login_customizer_form_height',
				'priority' => 15,
				'label'    => __( 'Form Height', AWP_CPL_TXTDM ),
				'description'    => __( 'Form Height by Default 224px', AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 220,
					'max'    => 540,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) );   

			//form Padding
			$wp_customize->add_setting(
				'awp_login_customizer_form_padding', array(
					'default' => '26px 24px 46px',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);
			$wp_customize->add_control(
				'awp_login_customizer_form_padding', array(
					'label' => __( 'Padding', AWP_CPL_TXTDM ),
					'description'    => __( 'Form Padding by Default 26px 24px 46px', AWP_CPL_TXTDM ),
					'section' => 'awp_login_customizer_form_section',
					'priority' => 25,
					'settings' => 'awp_login_customizer_form_padding',
				)
			);
			//form Border (Example: 4px double black)
			$wp_customize->add_setting(
				'awp_login_customizer_form_border', array(
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);	
			$wp_customize->add_control(
				'awp_login_customizer_form_border', array(
					'label' => __( 'Border (Example: 4px double black) ', AWP_CPL_TXTDM ),
					'section' => 'awp_login_customizer_form_section',
					'priority' => 30,
					'settings' => 'awp_login_customizer_form_border',
				)
			);		
			//form border radius
			$wp_customize->add_setting(
				'awp_login_customizer_form_border_radius', array(
					'default' => 10,
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);			
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_form_border_radius', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_form_section',
				'settings' => 'awp_login_customizer_form_border_radius',
				'priority' => 35,
				'label'    => __( 'Border Radius',AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 100,
					'step'   => 1,
					'suffix' => 'px', //optional suffix
				),
			) ) ); 
			
			// form shadow opacity
			$wp_customize->add_setting(
				'awp_login_customizer_form_shadow', array(
					'default' => 10,
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);		
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_form_shadow', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_form_section',
				'settings' => 'awp_login_customizer_form_shadow',
				'priority' => 40,
				'label'    => __( 'Form Shadow Opacity',AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0,
					'max'    => 100,
					'step'   => 1,
					'suffix' => '%', //optional suffix
				),
			) ) ); 
			
			// form opacity
			$wp_customize->add_setting(
				'awp_login_customizer_form_bg_opacity', array(
					'default' => '0.9',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);		
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_form_bg_opacity', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_form_section',
				'settings' => 'awp_login_customizer_form_bg_opacity',
				'priority' => 40,
				'label'    => __( 'Form Opacity',AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 0.5,
					'max'    => 1,
					'step'   => .1,
					//'suffix' => '', //optional suffix
				),
			) ) );  
			// Login Form Position	
			$wp_customize->add_setting(
				'awp_login_customizer_form_position', array(
					'default'     		=> 'center',
					'sanitize_callback' => 'awp_customizer_sanitize_radio',
				)
			);

			$wp_customize->add_control(
				'awp_login_customizer_form_position', array(
					'type'      => 'radio',
					'label' => __( 'Login Form Position	', AWP_CPL_TXTDM ),
					'section' => 'awp_login_customizer_form_section',
					'priority' => 45,
					'choices'   => array(
						'left'      => __( 'Left', AWP_CPL_TXTDM ),
						'center'   => __( 'Center', AWP_CPL_TXTDM ),
						'right'     => __( 'Right', AWP_CPL_TXTDM ),		
					),
				)
			);
			
			
			
		// 5. input Fields Styling
		$wp_customize->add_section(
			'awp_login_customizer_field_section', array(
				'priority' => 25,
				'title' => __( 'Form Fields Styling', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);
			
			$wp_customize->add_setting(
				'awp_login_customizer_field_width', array(
					'default' => '100%',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);
			$wp_customize->add_control(new Customizer_Range_Value_Control($wp_customize, 'awp_login_customizer_field_width', array(
				'type'     => 'range-value',
				'section'  => 'awp_login_customizer_field_section',
				'settings' => 'awp_login_customizer_field_width',
				'priority' => 5,
				'label'    => __( 'Input Field Width',AWP_CPL_TXTDM ),
				'input_attrs' => array(
					'min'    => 10,
					'max'    => 100,
					'step'   => 1,
					'suffix' => '%', //optional suffix
				),
			) ) );  

			$wp_customize->add_setting(
				'awp_login_customizer_field_margin', array(
					'default' => '2px 6px 16px 0px',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				'awp_login_customizer_field_margin', array(
					'label' => __( 'Input Field Margin', AWP_CPL_TXTDM ),
					'description' => __( 'Input Field Margin by Default 2px 6px 16px 0px', AWP_CPL_TXTDM ),
					'section' => 'awp_login_customizer_field_section',
					'priority' => 10,
					'settings' => 'awp_login_customizer_field_margin',
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_field_bg', array(
					'default' => '#FFF',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_field_bg', array(
						'label' => __( 'Input Field Background', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_field_section',
						'priority' => 15,
						'settings' => 'awp_login_customizer_field_bg',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_field_color', array(
					'default' => '#333',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_field_color', array(
						'label' => __( 'Input Field Text Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_field_section',
						'priority' => 20,
						'settings' => 'awp_login_customizer_field_color',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_field_label', array(
					'default' => '#777',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_field_label', array(
						'label' => __( 'Label Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_field_section',
						'priority' => 25,
						'settings' => 'awp_login_customizer_field_label',
					)
				)
			);

			
		// 6. Button settings
		$wp_customize->add_section(
			'awp_login_customizer_button_section', array(
				'priority' => 30,
				'title' => __( 'Button Styling', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);

			$wp_customize->add_setting(
				'awp_login_customizer_button_bg', array(
					'default' => '#2EA2CC',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_bg', array(
						'label' => __( 'Button Background', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 5,
						'settings' => 'awp_login_customizer_button_bg',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_button_border', array(
					'default' => '#0074A2',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_border', array(
						'label' => __( 'Button Border', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 10,
						'settings' => 'awp_login_customizer_button_border',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_button_hover_bg', array(
					'default' => '#1E8CBE',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_hover_bg', array(
						'label' => __( 'Button Background (Hover)', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 15,
						'settings' => 'awp_login_customizer_button_hover_bg',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_button_hover_border', array(
					'default' => '#0074A2',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_hover_border', array(
						'label' => __( 'Button Border (Hover)', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 20,
						'settings' => 'awp_login_customizer_button_hover_border',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_button_shadow', array(
					'default' => '#78C8E6',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_shadow', array(
						'label' => __( 'Button Box Shadow', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 25,
						'settings' => 'awp_login_customizer_button_shadow',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_button_color', array(
					'default' => '#FFF',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_button_color', array(
						'label' => __( 'Button Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_button_section',
						'priority' => 30,
						'settings' => 'awp_login_customizer_button_color',
					)
				)
			);
		
		// 7. form footer
		$wp_customize->add_section(
			'awp_login_customizer_other_section', array(
				'priority' => 35,
				'title' => __( 'Form Footer Color', AWP_CPL_TXTDM ),
				'panel'  => 'awp_login_customizer_panel',
			)
		);
			
			$wp_customize->add_setting(
				'awp_login_customizer_form_footer_color', array(
					'default' => '#999',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_form_footer_color', array(
						'label' => __( 'Text Color', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_other_section',
						'priority' => 5,
						'settings' => 'awp_login_customizer_form_footer_color',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_form_footer_color_hover', array(
					'default' => '#2EA2CC',
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize, 'awp_login_customizer_form_footer_color_hover', array(
						'label' => __( 'Text Color (Hover)', AWP_CPL_TXTDM ),
						'section' => 'awp_login_customizer_other_section',
						'priority' => 10,
						'settings' => 'awp_login_customizer_form_footer_color_hover',
					)
				)
			);

			$wp_customize->add_setting(
				'awp_login_customizer_other_css', array(
					'type' => 'option',
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_control(
				'awp_login_customizer_other_css', array(
					'label' => __( 'Custom CSS', AWP_CPL_TXTDM ),
					'type' => 'textarea',
					'section' => 'awp_login_customizer_other_section',
					'priority' => 15,
					'settings' => 'awp_login_customizer_other_css',
				)
			);
	}
	

	function wp_login_customizer() {
		// 1. logo setting
		$awp_logo_url = get_option( 'awp_login_logo' );
		$awp_logo_width = get_option( 'awp_login_logo_width' , 84 );
		$awp_logo_height = get_option( 'awp_login_logo_height' , 84 );
		$awp_logo_padding = get_option( 'awp_login_logo_padding' , 5 );
		// 2. background setting
		$awp_bg_img = get_option('awp_login_bg_image', AWP_CLP_PLUGIN_URL . 'images/background_image_1.jpg');
		$awp_bg_color = get_option( 'awp_login_customizer_bg_color' ,'#e5e5e5' );
		$awp_bg_size = get_theme_mod('awp_login_customizer_bg_size', 'cover'); //get_theme_mod
		// 3. Form background
		$awp_form_bg_image = get_option( 'awp_login_customizer_form_bg_image' );
		$awp_form_bg_color = get_option( 'awp_login_customizer_form_bg_color' ,'#FFF');
		$awp_form_bg_size = get_theme_mod( 'awp_login_customizer_form_bg_size', 'cover' ); //get_theme_mod
		// 4. Form Styling
		$awp_form_width = get_option( 'awp_login_customizer_form_width',350 );
		$awp_form_height = get_option( 'awp_login_customizer_form_height', 224 );		
		$awp_form_padding = get_option( 'awp_login_customizer_form_padding' ,'' );
		$awp_form_border = get_option( 'awp_login_customizer_form_border','' );	
		$awp_form_border_radius = get_option( 'awp_login_customizer_form_border_radius' ,10 );
		$awp_form_shadow = get_option( 'awp_login_customizer_form_shadow', 10 );
		$awp_form_bg_opacity = get_option( 'awp_login_customizer_form_bg_opacity', 0.9 );
		$awp_form_postion = get_theme_mod( 'awp_login_customizer_form_position', 'center' ); //get_theme_mod
		//5. Form Fields Styling
		$awp_field_width = get_option( 'awp_login_customizer_field_width' ,'100%' );
		$awp_field_margin = get_option( 'awp_login_customizer_field_margin' ,'' );
		$awp_field_bg = get_option( 'awp_login_customizer_field_bg', '#FFF' );
		$awp_field_color = get_option( 'awp_login_customizer_field_color' ,'' );
		$awp_field_label = get_option( 'awp_login_customizer_field_label' ,'' );
		//6. Button styling Setting
		$awp_button_bg = get_option( 'awp_login_customizer_button_bg' ,'#2EA2CC');
		$awp_button_border = get_option( 'awp_login_customizer_button_border' ,'#0074A2');
		$awp_button_shadow = get_option( 'awp_login_customizer_button_shadow' ,'#78C8E6' );
		$awp_button_color = get_option( 'awp_login_customizer_button_color' ,'#FFF');
		$awp_button_hover_bg = get_option( 'awp_login_customizer_button_hover_bg' ,'#1E8CBE' );
		$awp_button_hover_border = get_option( 'awp_login_customizer_button_hover_border' ,'#0074A2');
		// 7. Footer other CSS
		$awp_form_footer_color = get_option( 'awp_login_customizer_form_footer_color','#999' );
		$awp_form_footer_color_hover = get_option( 'awp_login_customizer_form_footer_color_hover' ,'#2EA2CC');
		$awp_other_css = get_option( 'awp_login_customizer_other_css' );
	?>
	<style type="text/css">
		html, body {
			<?php if ( ! empty( $awp_bg_img ) ) : ?>
				background-image: url(<?php echo $awp_bg_img; ?>) !important;
			<?php endif; ?>
			
			<?php if ( ! empty( $awp_bg_color ) ) : ?>
				background-color: <?php echo $awp_bg_color; ?> !important;
			<?php endif; ?>

			<?php if ( ! empty( $awp_bg_size ) ) : ?>
				background-size: <?php echo $awp_bg_size; ?> !important;
			<?php endif; ?>
		}
	body.login div#login h1 a {
		/* margin-right:25px;  for logo left right center*/
	
		<?php if ( ! empty( $awp_logo_url ) ) : ?>
			background-image: url(<?php echo $awp_logo_url; ?>) !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_logo_width ) ) : ?>
			width: <?php echo $awp_logo_width; ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_logo_height ) ) : ?>
			height: <?php echo $awp_logo_height; ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_logo_width ) || ! empty( $awp_logo_height ) ) : ?>
			background-size: <?php echo $awp_logo_width; ?>px <?php echo $awp_logo_height; ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_logo_padding ) ) : ?>
			padding-bottom: <?php echo $awp_logo_padding; ?>px !important;
		<?php endif; ?>
	}
	#loginform {
	<?php if ( ! empty( $awp_form_bg_image ) ) : ?>
		background-image: url(<?php echo $awp_form_bg_image; ?>) !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_form_bg_color ) ) : ?>
		background-color: <?php echo $awp_form_bg_color; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_form_bg_size ) ) : ?>
		background-size: <?php echo $awp_form_bg_size; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_form_height ) ) : ?>
		height: <?php echo $awp_form_height; ?>px !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_form_padding ) ) : ?>
		padding: <?php echo $awp_form_padding; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_form_border ) ) : ?>
		border: <?php echo $awp_form_border; ?> !important;
	<?php endif; ?>
	} 

	#login {
		float:<?php echo $awp_form_postion; ?> !important;
			position: relative !important;
		<?php if($awp_form_postion == "left") { ?>
			margin-left: 35px !important;
		<?php }?>
		<?php if($awp_form_postion == "right") { ?>
			margin-right: 35px !important;
		<?php } ?>
		
		<?php if ( ! empty( $awp_form_width ) ) : ?>
			width: <?php echo $awp_form_width; ?>px !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_form_bg_color ) ) : ?>
			background-color: <?php echo $awp_form_bg_color; ?> !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_form_border_radius ) ) : ?>
			border-radius: <?php echo $awp_form_border_radius; ?>px;
		<?php endif; ?>		
		<?php if ( ! empty( $awp_form_shadow ) ) : ?>
			box-shadow: rgb(0, 0, 0) 0px 0px <?php echo $awp_form_shadow; ?>px;
		<?php endif; ?>
		<?php if ( ! empty( $awp_form_bg_opacity ) ) : ?>
			opacity: <?php echo $awp_form_bg_opacity; ?>;
		<?php endif; ?>
		
		padding: 26px 26px 6px 26px !important;
		margin-top: 2% !important;
		
	}
	.login form .input, .login input[type="text"] {
	<?php if ( ! empty( $awp_field_width ) ) : ?>
		width: <?php echo $awp_field_width; ?>% !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_field_margin ) ) : ?>
		margin: <?php echo $awp_field_margin; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_field_bg ) ) : ?>
		background: <?php echo $awp_field_bg; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_field_color ) ) : ?>
		color: <?php echo $awp_field_color; ?> !important;
	<?php endif; ?>
	}
	.login label {
	<?php if ( ! empty( $awp_field_label ) ) : ?>
		color: <?php echo $awp_field_label; ?> !important;
	<?php endif; ?>
	}
	
	/* button settings */
	.wp-core-ui .button-primary {
	<?php if ( ! empty( $awp_button_bg ) ) : ?>
		background: <?php echo $awp_button_bg; ?> !important;
	<?php endif; ?>
	<?php if ( ! empty( $awp_button_border ) ) : ?>
		border-color: <?php echo $awp_button_border; ?> !important;
	<?php endif; ?>

	<?php if ( ! empty( $awp_button_color ) ) : ?>
		color: <?php echo $awp_button_color; ?> !important;
	<?php endif; ?>
	text-shadow : none !important;
	}
	.wp-core-ui #login .button-primary {
		<?php if ( ! empty( $awp_button_shadow ) ) : ?>
			box-shadow: 1px 1px 10px <?php echo $awp_button_shadow; ?> inset, 0px 0px 10px 3px <?php echo $awp_button_shadow; ?> !important;
		<?php endif; ?>
		height: auto !important;
		line-height: 16px !important;
		padding: 13px !important;
		padding-top: 13px !important;
		padding-bottom: 13px !important;
	}
	.login input[type="submit"] {
		margin: 15px 0 7px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		-ms-border-radius: 5px;
	}
	.login input[type="submit"] {
		font-size: 15px;
		width: 100%;
		border-radius: 5px;
	}
	
	.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
		<?php if ( ! empty( $awp_button_hover_bg ) ) : ?>
			background: <?php echo $awp_button_hover_bg; ?> !important;
		<?php endif; ?>
		<?php if ( ! empty( $awp_button_hover_border ) ) : ?>
			border-color: <?php echo $awp_button_hover_border; ?> !important;
		<?php endif; ?>
	}
	
	.login #backtoblog a, .login #nav a {
	<?php if ( ! empty( $awp_form_footer_color ) ) : ?>
		color: <?php echo $awp_form_footer_color; ?> !important;
	<?php endif; ?>
	}
	.login #backtoblog a:hover, .login #nav a:hover, .login h1 a:hover {
	<?php if ( ! empty( $awp_form_footer_color_hover ) ) : ?>
		color: <?php echo $awp_form_footer_color_hover; ?> !important;
	<?php endif; ?>
	}
	.login #nav {
		text-align: center;
	}
	.login #backtoblog, .login #nav{
		text-align: center;
	}	
	body.login div#login form p label {
	
	}
	<?php if ( ! empty( $awp_other_css ) ) : ?>
		<?php echo $awp_other_css; ?>
	<?php endif; ?>
	</style>
	<?php
	}

	// Filters link URL of the header logo above login form.
	function wp_login_customizer_logo_url() {
		return get_bloginfo( 'url' );
	}
	

	//Filters the Site title attribute of the header logo above login form.
	function wp_login_customizer_logo_url_title() {
		$title = get_bloginfo( 'name', 'display' );
		return $title;
	}
	
}
//radio box sanitization function
function awp_customizer_sanitize_radio( $input, $setting ){
 
	//input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
	$input = sanitize_key($input);

	//get the list of possible radio box options 
	$choices = $setting->manager->get_control( $setting->id )->choices;
					 
	//return input if valid or return default option
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );                
	 
}
?>
