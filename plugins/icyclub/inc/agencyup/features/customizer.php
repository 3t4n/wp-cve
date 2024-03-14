<?php if ( ! function_exists( 'icycp_agencyup_slider_customize_register' ) ) :
function icycp_agencyup_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	class Icycp_Agencyup_Toggle_Switch_Custom_control extends WP_Customize_Control {
		/**
		 * The type of control being rendered
		 */
		public $type = 'toogle_switch';
		/**
		 * Enqueue our scripts and styles
		 */
		/**
		 * Render the control in the customizer
		 */
		public function render_content(){
		?>
			<div class="toggle-switch-control">
				<div class="toggle-switch">
					<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
					<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
						<span class="toggle-switch-inner"></span>
						<span class="toggle-switch-switch"></span>
					</label>
				</div>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
			</div>
		<?php
		}
	}


	/**
	 * Custom Controls of theme
	 *
	 * @since 1.0.0
	 *
	 * @see WP_Customize_Control
	 */

	class Icycp_Section_Title extends WP_Customize_Control {
		public $type = 'section-title';
		public $label = '';
		public $description = '';

		public function render_content() {
			?>
			<h3><?php echo esc_html( $this->label ); ?></h3>
			<?php if (!empty($this->description)) { ?>
				<span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php } ?>
			<?php
		}
	}

	/* Frontpage Section */
	$wp_customize->add_panel( 'homepage_sections', array(
		'priority' => 4,
		'capability' => 'edit_theme_options',
		'title' => __('Homepage Section Settings', 'icyclub'),
	) );

	/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider Settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 1,
   	) );
		
	// Enable slider 
	$wp_customize->add_setting( 'home_page_slider_enabled',
		array(
			'default' => 1,
			'transport' => 'refresh',
			'sanitize_callback' => 'icycp_switch_sanitization'
		)
	);
		
	$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'home_page_slider_enabled',
		array(
			'label' => esc_html__( 'Slider Enable/Disable' ),
			'section' => 'slider_section'
		)
	) );


	//Slider Box Alignment
	$wp_customize->add_setting(
		'agencyup_slider_align', array(
		'default' => 'start',
		'transport' => 'refresh',
	));

	$wp_customize->add_control( new Custom_Radio_Image_Control( $wp_customize, 'agencyup_slider_align',
		array(
		'label' => esc_html__( 'Slider Text Alignment','icyclub'),
		'section' => 'slider_section',
		'choices'       => array(
		'start' => ICYCP_PLUGIN_URL .'inc/agencyup/images/left-alignment.png',  
		'center'    => ICYCP_PLUGIN_URL .'inc/agencyup/images/center-alignment.png',
		'end'    => ICYCP_PLUGIN_URL .'inc/agencyup/images/right-alignment.png',
	)
	) ) );
	//Slider Image
	$wp_customize->add_setting( 'slider_image',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/slider/banner.jpg',
		'sanitize_callback' => 'esc_url_raw', 
	//'transport' => $selective_refresh,
	));
 
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'slider_image',
			array(
				'type'        => 'upload',
				'label' => __('Image','icyclub'),
				'settings' =>'slider_image',
				'section' => 'slider_section',
				
			)
		)
	); 
	// Slider title
	$wp_customize->add_setting( 'slider_title',array(
		'default' => __('We are Best in Premium Consulting Services','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'slider_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
	));	
	
	//Slider discription
	$wp_customize->add_setting( 'slider_discription',array(
		'default' => 'we bring the proper people along to challenge established thinking and drive transformation.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'slider_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'slider_section',
		'type' => 'textarea',
	)); 
	// Slider button text
	$wp_customize->add_setting( 'slider_btn_txt',array(
		'default' => __('Read more','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'slider_btn_txt',array(
		'label'   => __('Button Text','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
	)); 
	// Slider button link
	$wp_customize->add_setting( 'slider_btn_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'slider_btn_link',array(
		'label'   => __('Button Link','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
	)); 
	// Slider button target
	$wp_customize->add_setting(
	'slider_btn_target', 
		array(
		'default'        => false));
	$wp_customize->add_control('slider_btn_target', array(
		'label'   => __('Open link in new tab', 'icyclub'),
		'section' => 'slider_section',
		'type' => 'checkbox',
	)); 
		
		
}

add_action( 'customize_register', 'icycp_agencyup_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_agencyup_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.consultup-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'slider_title', array(
		'selector'            => '.slide-title',
		'settings'            => 'slider_title',
		'render_callback'  => 'icycp_agencyup_slider_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_discription', array(
		'selector'            => '.slide-caption div p',
		'settings'            => 'slider_discription',
		'render_callback'  => 'icycp_agencyup_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_btn_txt', array(
		'selector'            => '.btn-tislider',
		'settings'            => 'slider_btn_txt',
		'render_callback'  => 'icycp_agencyup_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_agencyup_register_slider_section_partials' );


function icycp_agencyup_slider_title_render_callback() {
	return get_theme_mod( 'slider_title' );
}

function icycp_agencyup_slider_iscription_render_callback() {
	return get_theme_mod( 'slider_discription' );
}

function icycp_agencyup_slider_btn_render_callback() {
	return get_theme_mod( 'slider_btn_txt' );
}


if ( ! function_exists( 'icycp_agencyup_contact_info_customize_register' ) ) :
function icycp_agencyup_contact_info_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';


/* Services section */
	$wp_customize->add_section( 'contact_info_section' , array(
		'title'      => __('Contact Info Settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 2,
	) );


	$wp_customize->add_setting( 'contact_info_section_show',
		array(
			'default' => 1,
			'transport' => 'refresh',
			'sanitize_callback' => 'icycp_switch_sanitization'
		)
	);
		
	$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'contact_info_section_show',
		array(
			'label' => esc_html__( 'Conact Info Enable/Disable' ),
			'section' => 'contact_info_section'
		)
	) );


	// contact icon feature setting
	$wp_customize->add_setting( 'contact_one_icon',array(
	'default' => 'fa-map-marker',
	));	
	$wp_customize->add_control( 'contact_one_icon',array(
	'label'   => __('Contact Icon','icyclub'),
	'section' => 'contact_info_section',
	'type' => 'text',
	));	
	
	
	
	// conact section title
	$wp_customize->add_setting( 'contact_one_title',array(
	'default' => __('Head Office','icyclub'),
	'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_one_title',array(
	'label'   => __('Title','icyclub'),
	'section' => 'contact_info_section',
	'type' => 'text',
	));	
	
	//conatct section discription
	$wp_customize->add_setting( 'contact_one_description',array(
	'default' => '4578 Marmora Road, Glasgow',
	'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_one_description',array(
	'label'   => __('Description','icyclub'),
	'section' => 'contact_info_section',
	'type' => 'textarea',
	)); 
	//Contact Icon two settings
	$wp_customize->add_setting( 'contact_two_icon',array(
	'default' => 'fa-phone',
	));	
	$wp_customize->add_control( 'contact_two_icon',array(
	'label'   => __('Contact Icon','icyclub'),
	'section' => 'contact_info_section',
	'type' => 'text',
	));	 
	
	// Service section title
	$wp_customize->add_setting( 'contact_two_title',array(
	'default' => __('Call Us','icyclub'),
	'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_two_title',array(
	'label'   => __('Title','icyclub'),
	'section' => 'contact_info_section',
	'type' => 'text',
	));	
	
	//Service section discription
	$wp_customize->add_setting( 'contact_two_description',array(
		'default' => '(+81) 123-456-7890',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_two_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'textarea',
	));

	//Contact Icon three settings
	$wp_customize->add_setting( 'contact_three_icon',array(
		'default' => 'fa-envelope-open',
	));	
	$wp_customize->add_control( 'contact_three_icon',array(
		'label'   => __('Contact Icon','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
	)); 
	// contact section title
	$wp_customize->add_setting( 'contact_three_title',array(
		'default' => __('7:30 AM - 7:30 PM','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_three_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'text',
	));	
	
	//Service section discription
	$wp_customize->add_setting( 'contact_three_description',array(
		'default' => 'Monday to Saturday',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
	));	
	$wp_customize->add_control( 'contact_three_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'contact_info_section',
		'type' => 'textarea',
	)); 

}

add_action( 'customize_register', 'icycp_agencyup_contact_info_customize_register' );
endif;



if ( ! function_exists( 'icycp_agencyup_service_customize_register' ) ) :
function icycp_agencyup_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Services section */
	$wp_customize->add_section( 'services_section' , array(
		'title'      => __('Service Settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 2,
	) );
		
		$wp_customize->add_setting( 'service_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'service_section_show',
		   array(
			  'label' => esc_html__( 'Service Enable/Disable' ),
			  'section' => 'services_section'
		   )
		) );


		$wp_customize->add_setting( 'service_section_subtitle',array(
		'capability'     => 'edit_theme_options',
		'default' => __('SERVICE WE PROVIDE','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_subtitle',array(
		'label'   => __('Subtitle','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// Service section title
		$wp_customize->add_setting( 'service_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Why We Best in Business Services','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'service_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));

		
		// Service icon feature setting
		$wp_customize->add_setting( 'service_one_icon',array(
		'default' => 'fa-hands-helping',
		));	
		$wp_customize->add_control( 'service_one_icon',array(
		'label'   => __('Service Icon','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		
		
		// Service section title
		$wp_customize->add_setting( 'service_one_title',array(
		'default' => __('Market Analysis','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_one_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'service_one_description',array(
		'default' => 'laoreet Pellentesque molestie laoreet laoreet.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_one_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));

		$wp_customize->add_setting( 'service_image_1',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service1.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport' => $selective_refresh,
	));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'service_image_1',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'service_image_1',
					'section' => 'services_section',
					
				)
			)
		);
		
		
		// service read more button text
		$wp_customize->add_setting( 'ser_one_btn_text',array(
		'default' => __('Read more','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_one_btn_text',array(
		'label'   => __('Button Text','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button link
		$wp_customize->add_setting( 'ser_one_btn_link',array(
		'default' => __('#','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_one_btn_link',array(
		'label'   => __('Button Link','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button tab
		$wp_customize->add_setting(
		'ser_one_btn_tab', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		));
		$wp_customize->add_control('ser_one_btn_tab', array(
			'label'   => __('Open link in new tab/window', 'icyclub'),
			'section' => 'services_section',
			'type' => 'checkbox',
		));
		
		
		// Service icon two feature setting
		$wp_customize->add_setting( 'service_two_icon',array(
		'default' => 'fa-chart-line',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'service_two_icon',array(
		'label'   => __('Service Icon','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		
		
		// Service section title
		$wp_customize->add_setting( 'service_two_title',array(
		'default' => __('Business Planning','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_two_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'service_two_description',array(
		'default' => 'laoreet Pellentesque molestie laoreet laoreet.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_two_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));


		$wp_customize->add_setting( 'service_image_2',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service2.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport' => $selective_refresh,
	));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'service_image_2',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'service_image_2',
					'section' => 'services_section',
					
				)
			)
		);
		
		
		// service read more button text
		$wp_customize->add_setting( 'ser_two_btn_text',array(
		'default' => __('Read more','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_two_btn_text',array(
		'label'   => __('Button Text','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button link
		$wp_customize->add_setting( 'ser_two_btn_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_two_btn_link',array(
		'label'   => __('Button Link','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button tab
		$wp_customize->add_setting(
		'ser_two_btn_tab', 
			array(
			'default'        => false,


		));
		$wp_customize->add_control('ser_two_btn_tab', array(
			'label'   => __('Open link in new tab/window', 'icyclub'),
			'section' => 'services_section',
			'type' => 'checkbox',
		));
		
		
		// Service icon three feature setting
		$wp_customize->add_setting( 'service_three_icon',array(
		'default' => 'fa-briefcase',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'service_three_icon',array(
		'label'   => __('Service Icon','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		
		
		// Service section title
		$wp_customize->add_setting( 'service_three_title',array(
		'default' => __('Financial Planning','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_three_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'service_three_description',array(
		'default' => 'laoreet Pellentesque molestie laoreet laoreet.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_three_description',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));


		$wp_customize->add_setting( 'service_image_3',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service3.jpg',
		'sanitize_callback' => 'esc_url_raw', 
	));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'service_image_3',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'service_image_3',
					'section' => 'services_section',
					
				)
			)
		);



		
		
		// service read more button text
		$wp_customize->add_setting( 'ser_three_btn_text',array(
		'default' => __('Read more','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_three_btn_text',array(
		'label'   => __('Button Text','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button link
		$wp_customize->add_setting( 'ser_three_btn_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'ser_three_btn_link',array(
		'label'   => __('Button Link','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		// service read more button tab
		$wp_customize->add_setting(
		'ser_three_btn_tab', 
			array(
			'default'        => false,
		));
		$wp_customize->add_control('ser_three_btn_tab', array(
			'label'   => __('Open link in new tab/window', 'icyclub'),
			'section' => 'services_section',
			'type' => 'checkbox',
		));
	
}

add_action( 'customize_register', 'icycp_agencyup_service_customize_register' );
endif;


/**
 * Selective refresh for service section
 */
function icycp_agencyup_register_service_section_partials( $wp_customize ){

	//Service

	$wp_customize->selective_refresh->add_partial( 'service_section_subtitle', array(
		'selector'            => '.service h3',
		'settings'            => 'service_section_subtitle',
		'render_callback'  => 'icycp_consultup_service_subtitle_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'service_section_title', array(
		'selector'            => '.service h2',
		'settings'            => 'service_section_title',
		'render_callback'  => 'icycp_consultup_service_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'service_section_discription', array(
		'selector'            => '.service .bs-heading p',
		'settings'            => 'service_section_discription',
		'render_callback'  => 'icycp_consultup_service_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'service_one_title', array(
		'selector'            => '.service-one h4 a',
		'settings'            => 'service_one_title',
		'render_callback'  => 'icycp_consultup_service_one_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_one_description', array(
		'selector'            => '.service-one p',
		'settings'            => 'service_one_description',
		'render_callback'  => 'icycp_consultup_service_one_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_one_btn_text', array(
		'selector'            => '.service-one a',
		'settings'            => 'ser_one_btn_text',
		'render_callback'  => 'icycp_consultup_service_one_btn_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'service_two_title', array(
		'selector'            => '.service-two h4 a',
		'settings'            => 'service_two_title',
		'render_callback'  => 'icycp_consultup_service_two_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_two_description', array(
		'selector'            => '.service-two p',
		'settings'            => 'service_two_description',
		'render_callback'  => 'icycp_consultup_service_two_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_two_btn_text', array(
		'selector'            => '.service-two a',
		'settings'            => 'ser_two_btn_text',
		'render_callback'  => 'icycp_consultup_service_two_btn_render_callback',
	
	) );
	
	
	//Service three
	$wp_customize->selective_refresh->add_partial( 'service_three_title', array(
		'selector'            => '.service-three h4 a',
		'settings'            => 'service_three_title',
		'render_callback'  => 'icycp_consultup_service_three_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_three_description', array(
		'selector'            => '.service-three p',
		'settings'            => 'service_three_description',
		'render_callback'  => 'icycp_consultup_service_three_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_three_btn_text', array(
		'selector'            => '.service-three a',
		'settings'            => 'ser_three_btn_text',
		'render_callback'  => 'icycp_consultup_service_three_btn_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_agencyup_register_service_section_partials' );


function icycp_consultup_service_subtitle_render_callback() {
	return get_theme_mod( 'service_section_subtitle' );
}

function icycp_consultup_service_title_render_callback() {
	return get_theme_mod( 'service_section_title' );
}

function icycp_consultup_service_discription_render_callback() {
	return get_theme_mod( 'service_section_discription' );
}

//Service one

function icycp_consultup_service_one_title_render_callback() {
	return get_theme_mod( 'service_one_title' );
}

function icycp_consultup_service_one_desc_render_callback() {
	return get_theme_mod( 'service_one_description' );
}

function icycp_consultup_service_one_btn_render_callback() {
	return get_theme_mod( 'ser_one_btn_text' );
}


//Service two

function icycp_consultup_service_two_title_render_callback() {
	return get_theme_mod( 'service_two_title' );
}

function icycp_consultup_service_two_desc_render_callback() {
	return get_theme_mod( 'service_two_description' );
}

function icycp_consultup_service_two_btn_render_callback() {
	return get_theme_mod( 'ser_two_btn_text' );
}

//Service three

function icycp_consultup_service_three_title_render_callback() {
	return get_theme_mod( 'service_three_title' );
}

function icycp_consultup_service_three_desc_render_callback() {
	return get_theme_mod( 'service_three_description' );
}

function icycp_consultup_service_three_btn_render_callback() {
	return get_theme_mod( 'ser_three_btn_text' );
}



/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_agencyup_register_callout_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'callout_title', array(
		'selector'            => '.consultup-callout h3',
		'settings'            => 'callout_title',
		'render_callback'  => 'icycp_consultup_callout_section_title_render_callback',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'callout_discription', array(
		'selector'            => '.consultup-callout p',
		'settings'            => 'callout_discription',
		'render_callback'  => 'icycp_consultup_callout_section_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'callout_btn_txt', array(
		'selector'            => '.consultup-callout a',
		'settings'            => 'callout_btn_txt',
		'render_callback'  => 'icycp_consultup_callout_btn_txt_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_agencyup_register_callout_section_partials' );


function icycp_consultup_callout_section_title_render_callback() {
	return get_theme_mod( 'callout_title' );
}

function icycp_consultup_callout_section_desc_render_callback() {
	return get_theme_mod( 'callout_discription' );
}

function icycp_consultup_callout_btn_txt_render_callback() {
	return get_theme_mod( 'callout_btn_txt' );
}

//Project Section
if ( ! function_exists( 'icycp_agencyup_project_customizer' ) ) :
function icycp_agencyup_project_customizer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/* project Section */
	$wp_customize->add_section( 'project_section' , array(
			'title'      => __('Project/Portfolio Settings', 'icyclub'),
			'panel'  => 'homepage_sections',
			'priority'   => 3,
		) );
		
		$wp_customize->add_setting( 'project_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'project_section_enable',
		   array(
			  'label' => esc_html__( 'Project Enable/Disable' ),
			  'section' => 'project_section'
		   )
		) );


		// project section title
		$wp_customize->add_setting( 'portfolio_section_subtitle',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'default' => __('OUR PORTFOLIO','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_subtitle',array(
		'label'   => __('Subtitle','icyclub'),
		'section' => 'project_section',
		'type' => 'text',
		));	
		
		// project section title
		$wp_customize->add_setting( 'portfolio_section_title',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'default' => __('Our Portfolio','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'project_section',
		'type' => 'text',
		));	
		
		//project section discription
		$wp_customize->add_setting( 'portfolio_section_discription',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'portfolio_section_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'project_section',
		'type' => 'textarea',
		));	
	 
	 
		//project one image
		$wp_customize->add_setting( 'project_image_one',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio1.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport' => $selective_refresh, 
	));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_one',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_one',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project one Title
		$wp_customize->add_setting(
		'project_title_one', array(
			'default'        => __('Financial Project','agencyup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_one', array(
			'label'   => __('Title', 'icyclub'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		
		
		
		//project two image
		$wp_customize->add_setting( 'project_image_two',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio2.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
	));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_two',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_two',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project two Title
		$wp_customize->add_setting(
		'project_title_two', array(
			'default'        => __('Investment','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_two', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		
		//project three image
		$wp_customize->add_setting( 'project_image_three',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio3.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_three',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_three',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		//Portfolio three Title
		$wp_customize->add_setting(
		'project_title_three', array(
			'default'        => __('Invoicing','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_three', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));


		//project three image
		$wp_customize->add_setting( 'project_image_four',array('default' => ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio4.jpg',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_four',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'project_image_four',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		//Portfolio three Title
		$wp_customize->add_setting(
		'project_title_four', array(
			'default'        => __('Team Management','icyclub'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_four', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));

}		
add_action( 'customize_register', 'icycp_agencyup_project_customizer' );
endif;

/**
 * Add selective refresh for project section.
 */
function icycp_agencyup_register_project_section_partials( $wp_customize ){

	
	//Portfolio section

	$wp_customize->selective_refresh->add_partial( 'portfolio_section_subtitle', array(
		'selector'            => '.portfolios h3',
		'settings'            => 'portfolio_section_subtitle',
		'render_callback'  => 'icycp_consultup_portfolio_section_subtitle_render_callback',
	
	) );


	$wp_customize->selective_refresh->add_partial( 'portfolio_section_title', array(
		'selector'            => '.portfolios h2',
		'settings'            => 'portfolio_section_title',
		'render_callback'  => 'icycp_consultup_portfolio_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'portfolio_section_discription', array(
		'selector'            => '.portfolios p',
		'settings'            => 'portfolio_section_discription',
		'render_callback'  => 'icycp_consultup_portfolio_section_discription_render_callback',
	
	) );
	
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_one', array(
		'selector'            => '.project-one h2 a',
		'settings'            => 'project_title_one',
		'render_callback'  => 'icycp_consultup_project_title_one_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_two', array(
		'selector'            => '.project-two h2 a',
		'settings'            => 'project_title_two',
		'render_callback'  => 'icycp_consultup_project_title_two_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'project_title_three', array(
		'selector'            => '.project-three h2 a',
		'settings'            => 'project_title_three',
		'render_callback'  => 'icycp_consultup_project_title_three_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'project_title_four', array(
		'selector'            => '.project-four h2 a',
		'settings'            => 'project_title_four',
		'render_callback'  => 'icycp_consultup_project_title_four_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_agencyup_register_project_section_partials' );

//Project Section
function icycp_consultup_portfolio_section_subtitle_render_callback() {
	return get_theme_mod( 'portfolio_section_subtitle' );
}



function icycp_consultup_portfolio_section_title_render_callback() {
	return get_theme_mod( 'portfolio_section_title' );
}

function icycp_consultup_portfolio_section_discription_render_callback() {
	return get_theme_mod( 'portfolio_section_discription' );
}

//Project


function icycp_consultup_project_title_one_render_callback() {
	return get_theme_mod( 'project_title_one' );
}


function icycp_consultup_project_title_two_render_callback() {
	return get_theme_mod( 'project_title_two' );
}


function icycp_consultup_project_title_three_render_callback() {
	return get_theme_mod( 'project_title_three' );
}

function icycp_consultup_project_title_four_render_callback() {
	return get_theme_mod( 'project_title_four' );
}


if ( ! function_exists( 'icycp_agencyup_testimonial_customize_register' ) ) :
function icycp_agencyup_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
			'title'      => __('Testimonial Settings', 'icyclub'),
			'panel'  => 'homepage_sections',
			'priority'   => 4,
		) );


		// Enable testimonial section
		$wp_customize->add_setting( 'testimonial_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'testimonial_section_enable',
		   array(
			  'label' => esc_html__( 'Testimonial Enable/Disable' ),
			  'section' => 'testimonial_section'
		   )
		) );

		//Testimonial background Image
		$wp_customize->add_setting( 'testimonial_background_image',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'testimonial_background_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'testimonial_background_image',
					'section' => 'testimonial_section',
					
				)
			)
		);
		
		// Image overlay
		$wp_customize->add_setting( 'testimonial_back_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('testimonial_back_image_overlay', array(
			'label'    => __('Enable testimonial image overlay', 'consultup' ),
			'section'  => 'testimonial_section',
			'type' => 'checkbox',
		) );

		//CTA Background Overlay Color
		$wp_customize->add_setting( 'testimonial_back_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0,41,84,0.8)',
            ) );	
            
            $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,'testimonial_back_overlay_color', array(
               'label'      => __('Testimonial image overlay color','icyclub' ),
                'palette' => true,
                'section' => 'testimonial_section')
            ) );
		

		// testimonial section title
		$wp_customize->add_setting( 'testimonial_section_subtitle',array(
		'capability'     => 'edit_theme_options',
		'default' => __('TESTIMONIALS','consultup'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_section_subtitle',array(
		'label'   => __('Title','consultup'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		// testimonial section title
		$wp_customize->add_setting( 'testimonial_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('What are clients says','consultup'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_section_title',array(
		'label'   => __('Title','consultup'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));	
		
		//testimonial section discription
		$wp_customize->add_setting( 'testimonial_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default'=> 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_section_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'textarea',
		));
		
		//testimonial one image
		$wp_customize->add_setting( 'testimonial_one_thumb',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/testimonial/testi1.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport'         => $selective_refresh,
	));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'testimonial_one_thumb',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'testimonial_one_thumb',
					'section' => 'testimonial_section',
					'type' => 'upload',
				)
			)
		);
		
		
		
		$wp_customize->add_setting( 'testimonial_one_name',array(
		'default' => __('Williams Moore','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_one_name',array(
		'label'   => __('Name','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		$wp_customize->add_setting( 'testimonial_one_designation',array(
		'default' => __('Creative Designer','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_one_designation',array(
		'label'   => __('Designation','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));

		//testimonial description
		$wp_customize->add_setting( 'testimonial_one_desc',array(
		'capability'     => 'edit_theme_options',
		'default' => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_one_desc',array(
		'label'   => __('Description','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
}

add_action( 'customize_register', 'icycp_agencyup_testimonial_customize_register' );
endif;


/**
 * Selective refresh for testimonial section
 */
function icycp_agencyp_register_testimonial_section_partials( $wp_customize ){


	
	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'testimonial_callout_background', array(
		'selector'            => 'section.testimonial-section',
		'settings'            => 'testimonial_callout_background',
	
	) );
	
	//Testimonial one
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_subtitle', array(
		'selector'            => '.testimonials h3',
		'settings'            => 'testimonial_section_subtitle',
		'render_callback'  => 'icycp_consultup_testimonial_section_subtitle_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'testimonial_section_title', array(
		'selector'            => '.testimonials h2',
		'settings'            => 'testimonial_section_title',
		'render_callback'  => 'icycp_consultup_testimonial_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_discription', array(
		'selector'            => '.testimonials p',
		'settings'            => 'testimonial_section_discription',
		'render_callback'  => 'icycp_consultup_testimonial_section_discription_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_desc', array(
		'selector'            => '.testi .testimonial-dec p',
		'settings'            => 'testimonial_one_desc',
		'render_callback'  => 'icycp_consultup_testimonial_one_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_name', array(
		'selector'            => '.testimonials h6',
		'settings'            => 'testimonial_one_name',
		'render_callback'  => 'icycp_consultup_testimonial_name_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_designation', array(
		'selector'            => '.testimonials .details',
		'settings'            => 'testimonial_one_designation',
		'render_callback'  => 'icycp_consultup_testimonial_designation_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_thumb', array(
		'selector'            => '.testimonials .clg img',
		'settings'            => 'testimonial_one_thumb',
	
	) );
	
}

add_action( 'customize_register', 'icycp_agencyp_register_testimonial_section_partials' );

//Testimonial Section
function icycp_consultup_testimonial_section_title_render_callback() {
	return get_theme_mod( 'testimonial_section_title' );
}

function icycp_consultup_testimonial_section_discription_render_callback() {
	return get_theme_mod( 'testimonial_section_discription' );
}

//Testimonial One
function icycp_consultup_testimonial_one_title_render_callback() {
	return get_theme_mod( 'testimonial_one_title' );
}

function icycp_consultup_testimonial_one_desc_render_callback() {
	return get_theme_mod( 'testimonial_one_desc' );
}

function icycp_consultup_testimonial_name_render_callback() {
	return get_theme_mod( 'testimonial_one_name' );
}


function icycp_consultup_testimonial_designation_render_callback() {
	return get_theme_mod( 'testimonial_one_designation' );
}


//Testimonial two
function icycp_consultup_testimonial_two_title_render_callback() {
	return get_theme_mod( 'testimonial_two_title' );
}

function icycp_consultup_testimonial_two_desc_render_callback() {
	return get_theme_mod( 'testimonial_two_desc' );
}

function icycp_consultup_testimonial_two_name_render_callback() {
	return get_theme_mod( 'testimonial_two_name' );
}


function icycp_consultup_testimonial_two_designation_render_callback() {
	return get_theme_mod( 'testimonial_two_designation' );
}


//News
if ( ! function_exists( 'icycp_agencyup_news_customize_register' ) ) :
function icycp_agencyup_news_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

$wp_customize->add_section( 'news_section' , array(
		'title' => __('News Settings', 'icyclub'),
		'panel' => 'homepage_sections',
		'priority' => 10
   	) );
	
	$wp_customize->add_setting( 'news_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'news_section_show',
		   array(
			  'label' => esc_html__( 'News Enable/Disable','icyclub'),
			  'section' => 'news_section'
		   )
	) );
	
	$wp_customize->add_setting(
		'news_section_subtitle', array(
        'capability' => 'edit_theme_options',
		'default' => __('Latest News','agencyup'),
		//'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_subtitle', array(
        'label' => __('Subtitle', 'agencyup'),
        'section' => 'news_section',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'news_section_title', array(
        'capability' => 'edit_theme_options',
		'default' => __('Latest News','icyclub'),
		//'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_title', array(
        'label' => __('News section title', 'consultup'),
        'section' => 'news_section',
        'type' => 'text',
    ) );
	
	$wp_customize->add_setting(
		'news_section_description', array(
        'capability' => 'edit_theme_options',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'transport' => $selective_refresh
    ) );
    $wp_customize->add_control( 'news_section_description', array(
        'label' => __('News section description', 'icyclub'),
        'section' => 'news_section',
        'type' => 'textarea',
    ) );


    // date in header display type
    $wp_customize->add_setting( 'consultup_post_content_type', array(
        'default'           => 'content',
        'capability'        => 'edit_theme_options',
    ) );

    $wp_customize->add_control( 'consultup_post_content_type', array(
        'type'     => 'radio',
        'label'    => esc_html__( 'Blog Post Content Type', 'consultup' ),
        'choices'  => array(
            'content'          => esc_html__( 'Content', 'consultup' ),
            'excerpt' => esc_html__( 'Excerpt', 'consultup' ),
        ),
        'section'  => 'news_section',
        'settings' => 'consultup_post_content_type',
    ) );


    $wp_customize->add_setting(
		'consultup_excerpt_length', array(
        'capability' => 'edit_theme_options',
		'default' => __('180','consultup'),
    ) );
    $wp_customize->add_control( 'consultup_excerpt_length', array(
        'label' => __('Excerpt length', 'icyclub'),
        'section' => 'news_section',
        'type' => 'number',
    ) );



    $wp_customize->add_setting(
		'news_section_post_count', array(
        'capability' => 'edit_theme_options',
		'default' => __('3','icyclub'),
    ) );
    $wp_customize->add_control( 'news_section_post_count', array(
        'label' => __('Number of Items', 'icyclub'),
        'section' => 'news_section',
        'type' => 'select',
        'choices' => array('3'=>__('3', 'icyclub'),'6' => __('6','icyclub'), '9' => __('9','icyclub'),'12'=> __('12','icyclub')),
    ) );
}

add_action( 'customize_register', 'icycp_agencyup_news_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icypb_agencyup_home_news_register_section_partials( $wp_customize ){

	//News Title
	$wp_customize->selective_refresh->add_partial( 'news_section_title', array(
		'selector'            => '.blog .bs-heading h2',
		'settings'            => 'news_section_title',
		'render_callback'  => 'icypb_consultup_news_section_title_render_callback',
	
	) );
	
	//News Description
	$wp_customize->selective_refresh->add_partial( 'news_section_description', array(
		'selector'            => '.blog .bs-heading p',
		'settings'            => 'news_section_description',
		'render_callback'  => 'icypb_consultup_news_section_description_render_callback',
	
	) );
}

add_action( 'customize_register', 'icypb_agencyup_home_news_register_section_partials' );


//Callout
if ( ! function_exists( 'icycp_agencyup_callout_customize_register' ) ) :
function icycp_agencyup_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'home_callout_section' , array(
		'title'      => __('Callout Settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 30,
   	) );
		
		// Enable slider
		
		
		
		$wp_customize->add_setting( 'homepage_callout_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Agencyup_Toggle_Switch_Custom_control( $wp_customize, 'homepage_callout_show',
		   array(
			  'label' => esc_html__( 'Callout Enable/Disable' ),
			  'section' => 'home_callout_section'
		   )
		) );

		//Callout background Image
		$wp_customize->add_setting( 'callout_background_image',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'callout_background_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'callout_background_image',
					'section' => 'home_callout_section',
					
				)
			)
		);
		
		// Image overlay
		$wp_customize->add_setting( 'callout_back_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('callout_back_image_overlay', array(
			'label'    => __('Enable callout image overlay', 'consultup' ),
			'section'  => 'home_callout_section',
			'type' => 'checkbox',
		) );
		
		
		//CTA Background Overlay Color
		$wp_customize->add_setting( 'callout_back_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
            ) );	
            
            $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,'callout_back_overlay_color', array(
               'label'      => __('Callout image overlay color','consultup' ),
                'palette' => true,
                'section' => 'home_callout_section')
            ) );
		
		
		// callout title
		$wp_customize->add_setting( 'callout_title',array(
		'default' => __('Trusted By Over 10,000 Worldwide Businesses. Try Today!','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_title',array(
		'label'   => __('Title','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'callout_discription',array(
		'default' => 'We must explain to you how all this mistaken idea of denouncing pleasure',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_discription',array(
		'label'   => __('Description','consultup'),
		'section' => 'home_callout_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'callout_btn_txt',array(
		'default' => __('Get Started Now!','consultup'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'callout_btn_txt',array(
		'label'   => __('Button Text','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'callout_btn_link',array(
		'default' => 'https://themeansar.com/themes/agencyup-pro/',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		));	
		$wp_customize->add_control( 'callout_btn_link',array(
		'label'   => __('Button Link','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'callout_btn_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		));
		$wp_customize->add_control('callout_btn_target', array(
			'label'   => __('Open link in new tab/window', 'consultup'),
			'section' => 'home_callout_section',
			'type' => 'checkbox',
		));
		
		
		
}

add_action( 'customize_register', 'icycp_agencyup_callout_customize_register' );
endif;


/**
 * Selective refresh for testimonial section
 */
function icycp_agencyup_register_testimonial_section_partials( $wp_customize ){


	//Callout title
	$wp_customize->selective_refresh->add_partial( 'callout_title', array(
		'selector'            => '.calltoaction h3',
		'settings'            => 'callout_title',
		'render_callback'  => 'icycp_consultup_callout_title_render_callback',
	
	) );	

	//Description
	$wp_customize->selective_refresh->add_partial( 'callout_discription', array(
		'selector'            => '.calltoaction h2',
		'settings'            => 'callout_discription',
		'render_callback'  => 'icycp_consultup_callout_discription_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'callout_btn_txt', array(
		'selector'            => '.calltoaction .btn-theme-two',
		'settings'            => 'callout_btn_txt',
		'render_callback'  => 'icycp_consultup_callout_btn_txt_render_callback',
	
	) );


	
}

add_action( 'customize_register', 'icycp_agencyup_register_testimonial_section_partials' );

//Callout Section
function icycp_consultup_callout_title_render_callback() {
	return get_theme_mod( 'callout_title' );
}

function icycp_consultup_callout_discription_render_callback() {
	return get_theme_mod( 'callout_discription' );
}



function icypb_consultup_news_section_description_render_callback() {
	return get_theme_mod( 'news_section_description' );
}

//Sanatize text validation
function icycp_conulstup_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}