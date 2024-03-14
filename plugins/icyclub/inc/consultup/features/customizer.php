<?php if ( ! function_exists( 'icycp_consultup_slider_customize_register' ) ) :
function icycp_consultup_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

class Icycp_Consultup_Toggle_Switch_Custom_control extends WP_Customize_Control {
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


/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider settings', 'icyclub'),
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
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'home_page_slider_enabled',
		   array(
			  'label' => esc_html__( 'Slider Enable/Disable' ),
			  'section' => 'slider_section'
		   )
		) );

		
		//Slider Image
		$wp_customize->add_setting( 'slider_image',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/slider/banner.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
 
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
		
		// Image overlay
		$wp_customize->add_setting( 'slider_image_overlay', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('slider_image_overlay', array(
			'label'    => __('Enable slider image overlay', 'icyclub' ),
			'section'  => 'slider_section',
			'type' => 'checkbox',
		) );
		
		
		//Slider Background Overlay Color
		$wp_customize->add_setting( 'slider_overlay_section_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0,0,0,0.30)',
            ) );	
            
            $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,'slider_overlay_section_color', array(
               'label'      => __('Slider image overlay color','icyclub' ),
                'palette' => true,
                'section' => 'slider_section')
            ) );
		
		
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

add_action( 'customize_register', 'icycp_consultup_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_consultup_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.consultup-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'slider_title', array(
		'selector'            => '.slide-caption div h1',
		'settings'            => 'slider_title',
		'render_callback'  => 'icycp_consultup_slider_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_discription', array(
		'selector'            => '.slide-caption div p',
		'settings'            => 'slider_discription',
		'render_callback'  => 'icycp_consultup_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_btn_txt', array(
		'selector'            => '.slide-caption div a',
		'settings'            => 'slider_btn_txt',
		'render_callback'  => 'icycp_consultup_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_consultup_register_slider_section_partials' );


function icycp_consultup_slider_title_render_callback() {
	return get_theme_mod( 'slider_title' );
}

function icycp_consultup_slider_iscription_render_callback() {
	return get_theme_mod( 'slider_discription' );
}

function icycp_consultup_slider_btn_render_callback() {
	return get_theme_mod( 'slider_btn_txt' );
}


if ( ! function_exists( 'icycp_consultup_service_customize_register' ) ) :
function icycp_consultup_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Services section */
	$wp_customize->add_section( 'services_section' , array(
		'title'      => __('Service settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 1,
	) );
		
		$wp_customize->add_setting( 'service_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'service_section_show',
		   array(
			  'label' => esc_html__( 'Service Enable/Disable' ),
			  'section' => 'services_section'
		   )
		) );

		
		
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
		'default' => 'fa fa-thumbs-up',
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
		'default' => 'far fa-newspaper',
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
		'default' => 'fa fa-bank',
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

add_action( 'customize_register', 'icycp_consultup_service_customize_register' );
endif;


/**
 * Selective refresh for service section
 */
function icycp_consultup_register_service_section_partials( $wp_customize ){

	//Service
	$wp_customize->selective_refresh->add_partial( 'service_section_title', array(
		'selector'            => '.consultup-service-section .consultup-heading h3',
		'settings'            => 'service_section_title',
		'render_callback'  => 'icycp_consultup_service_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'service_section_discription', array(
		'selector'            => '.consultup-service-section .consultup-heading p',
		'settings'            => 'service_section_discription',
		'render_callback'  => 'icycp_consultup_service_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'service_one_title', array(
		'selector'            => '.service-one h3',
		'settings'            => 'service_one_title',
		'render_callback'  => 'icycp_consultup_service_one_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_one_description', array(
		'selector'            => '.service-one .ta-service.three p',
		'settings'            => 'service_one_description',
		'render_callback'  => 'icycp_consultup_service_one_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_one_btn_text', array(
		'selector'            => '.service-one a',
		'settings'            => 'ser_one_btn_text',
		'render_callback'  => 'icycp_consultup_service_one_btn_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'service_two_title', array(
		'selector'            => '.service-two h3',
		'settings'            => 'service_two_title',
		'render_callback'  => 'icycp_consultup_service_two_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_two_description', array(
		'selector'            => '.service-two .ta-service.three p',
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
		'selector'            => '.service-three h3',
		'settings'            => 'service_three_title',
		'render_callback'  => 'icycp_consultup_service_three_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_three_description', array(
		'selector'            => '.service-three .ta-service.three p',
		'settings'            => 'service_three_description',
		'render_callback'  => 'icycp_consultup_service_three_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_three_btn_text', array(
		'selector'            => '.service-three a',
		'settings'            => 'ser_three_btn_text',
		'render_callback'  => 'icycp_consultup_service_three_btn_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_consultup_register_service_section_partials' );


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


//Callout
if ( ! function_exists( 'icycp_consultup_callout_customize_register' ) ) :
function icycp_consultup_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'home_callout_section' , array(
		'title'      => __('Callout settings', 'consultup'),
		'panel'  => 'homepage_sections',
		'priority'   => 3,
   	) );
		
		// Enable slider
		
		
		
		$wp_customize->add_setting( 'homepage_callout_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'homepage_callout_show',
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
					'label' => __('Image','consultup'),
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
		
		
		//Slider Background Overlay Color
		$wp_customize->add_setting( 'callout_back_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0,41,84,0.8)',
            ) );	
            
            $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,'callout_back_overlay_color', array(
               'label'      => __('Callout image overlay color','consultup' ),
                'palette' => true,
                'section' => 'home_callout_section')
            ) );
		
		
		// callout title
		$wp_customize->add_setting( 'callout_title',array(
		'default' => __('Trusted By Over 10,000 Worldwide Businesses. Try Today!','consultup'),
		));	
		$wp_customize->add_control( 'callout_title',array(
		'label'   => __('Title','consultup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'callout_discription',array(
		'default' => 'We must explain to you how all this mistaken idea of denouncing pleasure',
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
		'default' => 'https://themeansar.com/themes/consultup-pro/',
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

add_action( 'customize_register', 'icycp_consultup_callout_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_consultup_register_callout_section_partials( $wp_customize ){

	
	
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

add_action( 'customize_register', 'icycp_consultup_register_callout_section_partials' );


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
if ( ! function_exists( 'icycp_consultup_project_customizer' ) ) :
function icycp_consultup_project_customizer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/* project Section */
	$wp_customize->add_section( 'project_section' , array(
			'title'      => __('Project/Portfolio settings', 'icyclub'),
			'panel'  => 'homepage_sections',
			'priority'   => 4,
		) );
		
		$wp_customize->add_setting( 'project_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'project_section_enable',
		   array(
			  'label' => esc_html__( 'Project Enable/Disable' ),
			  'section' => 'project_section'
		   )
		) );
		
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
		$wp_customize->add_setting( 'project_image_one',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio1.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh, ));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_one',
				array(
					'label' => __('Image','consultup'),
					'settings' =>'project_image_one',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project one Title
		$wp_customize->add_setting(
		'project_title_one', array(
			'default'        => __('Financial Project','consultup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_one', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		//project one description
		$wp_customize->add_setting(
		'project_desc_one', array(
			'default'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit..',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_desc_one', array(
			'label'   => __('Description', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		
		
		//project two image
		$wp_customize->add_setting( 'project_image_two',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio2.jpg',
		'sanitize_callback' => 'esc_url_raw','transport'         => $selective_refresh,));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_two',
				array(
					'label' => __('Image','consultup'),
					'settings' =>'project_image_two',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		
		//project two Title
		$wp_customize->add_setting(
		'project_title_two', array(
			'default'        => __('Investment','consultup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_two', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		//project two description
		$wp_customize->add_setting(
		'project_desc_two', array(
			'default'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit..',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_desc_two', array(
			'label'   => __('Description', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		//project three image
		$wp_customize->add_setting( 'project_image_three',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio3.jpg',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => $selective_refresh,
		));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'project_image_three',
				array(
					'label' => __('Image','consultup'),
					'settings' =>'project_image_three',
					'section' => 'project_section',
					'type' => 'upload',
				)
			)
		);
		
		//Portfolio three Title
		$wp_customize->add_setting(
		'project_title_three', array(
			'default'        => __('Invoicing','consultup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_title_three', array(
			'label'   => __('Title', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		//Portfolio three description
		$wp_customize->add_setting(
		'project_desc_three', array(
			'default'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit..',
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => $selective_refresh,
		));
		$wp_customize->add_control('project_desc_three', array(
			'label'   => __('Description', 'consultup'),
			'section' => 'project_section',
			'type' => 'text',
		));
		
		

}		
add_action( 'customize_register', 'icycp_consultup_project_customizer' );
endif;

/**
 * Add selective refresh for project section.
 */
function icycp_consultup_register_project_section_partials( $wp_customize ){

	
	//Portfolio section
	$wp_customize->selective_refresh->add_partial( 'portfolio_section_title', array(
		'selector'            => '.consultup-portfolio .consultup-heading h3',
		'settings'            => 'portfolio_section_title',
		'render_callback'  => 'icycp_consultup_portfolio_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'portfolio_section_discription', array(
		'selector'            => '.consultup-portfolio .consultup-heading p',
		'settings'            => 'portfolio_section_discription',
		'render_callback'  => 'icycp_consultup_portfolio_section_discription_render_callback',
	
	) );
	
	//Project one
	$wp_customize->selective_refresh->add_partial( 'project_image_one', array(
		'settings'            => 'project_image_one',
		'selector'            => '.project-one .consultup-portfolio-block img',
		'render_callback'  => 'icycp_consultup_project_image_one_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_title_one', array(
		'selector'            => '.project-one h5',
		'settings'            => 'project_title_one',
		'render_callback'  => 'icycp_consultup_project_title_one_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_desc_one', array(
		'selector'            => '.project-one .ta-portfolio-block p',
		'settings'            => 'project_desc_one',
		'render_callback'  => 'icycp_consultup_project_desc_one_render_callback',
	
	) );
	
	//Project two
	$wp_customize->selective_refresh->add_partial( 'project_image_two', array(
		'settings'            => 'project_image_two',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_title_two', array(
		'selector'            => '.project-two h5',
		'settings'            => 'project_title_two',
		'render_callback'  => 'icycp_consultup_project_title_two_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_desc_two', array(
		'selector'            => '.project-two .ta-portfolio-block p',
		'settings'            => 'project_desc_two',
		'render_callback'  => 'icycp_consultup_project_desc_two_render_callback',
	
	) );
	
	
	//Project three
	$wp_customize->selective_refresh->add_partial( 'project_image_three', array(
		'selector'            => '.port1 .entry-header .entry-title > a',
		'settings'            => 'project_image_three',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_title_three', array(
		'selector'            => '.project-three h5',
		'settings'            => 'project_title_three',
		'render_callback'  => 'icycp_consultup_project_title_three_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'project_desc_three', array(
		'selector'            => '.project-three .ta-portfolio-block p',
		'settings'            => 'project_desc_three',
		'render_callback'  => 'icycp_consultup_project_desc_three_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_consultup_register_project_section_partials' );

//Project Section
function icycp_consultup_portfolio_section_title_render_callback() {
	return get_theme_mod( 'portfolio_section_title' );
}

function icycp_consultup_portfolio_section_discription_render_callback() {
	return get_theme_mod( 'portfolio_section_discription' );
}

//Project
function icycp_consultup_project_image_one_render_callback() {
	return get_theme_mod( 'project_img_one' );
}

function icycp_consultup_project_title_one_render_callback() {
	return get_theme_mod( 'project_title_one' );
}

function icycp_consultup_project_desc_one_render_callback() {
	return get_theme_mod( 'project_desc_one' );
}

function icycp_consultup_project_title_two_render_callback() {
	return get_theme_mod( 'project_title_two' );
}

function icycp_consultup_project_desc_two_render_callback() {
	return get_theme_mod( 'project_desc_two' );
}

function icycp_consultup_project_title_three_render_callback() {
	return get_theme_mod( 'project_title_three' );
}

function icycp_consultup_project_desc_three_render_callback() {
	return get_theme_mod( 'project_desc_three' );
}


if ( ! function_exists( 'icycp_consultup_testimonial_customize_register' ) ) :
function icycp_consultup_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
			'title'      => __('Testimonial settings', 'icyclub'),
			'panel'  => 'homepage_sections',
			'priority'   => 7,
		) );
		
		// Enable testimonial section
		$wp_customize->add_setting( 'testimonial_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'testimonial_section_enable',
		   array(
			  'label' => esc_html__( 'Testimonial Enable/Disable' ),
			  'section' => 'testimonial_section'
		   )
		) );
		
		// testimonial section title
		$wp_customize->add_setting( 'testimonial_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Our Clients Says','consultup'),
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
		'sanitize_callback' => 'esc_url_raw', 'transport'         => $selective_refresh,));
	 
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
		
		// testimonial section title
		$wp_customize->add_setting( 'testimonial_one_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Professional Team','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_one_title',array(
		'label'   => __('Title','icyclub'),
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
		
		
		//testimonial two image
		$wp_customize->add_setting( 'testimonial_two_thumb',array('default' => ICYCP_PLUGIN_URL .'inc/consultup/images/testimonial/testi2.jpg',
		'sanitize_callback' => 'esc_url_raw', 'transport'         => $selective_refresh,));
	 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'testimonial_two_thumb',
				array(
					'label' => __('Image','icyclub'),
					'settings' =>'testimonial_two_thumb',
					'section' => 'testimonial_section',
					'type' => 'upload',
				)
			)
		);
		
		// testimonial section title
		$wp_customize->add_setting( 'testimonial_two_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Professional Team','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_two_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		//testimonial description
		$wp_customize->add_setting( 'testimonial_two_desc',array(
		'capability'     => 'edit_theme_options',
		'default' => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_two_desc',array(
		'label'   => __('Description','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		$wp_customize->add_setting( 'testimonial_two_name',array(
		'default' => __('Williams Moore','consultup'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_two_name',array(
		'label'   => __('Name','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		
		$wp_customize->add_setting( 'testimonial_two_designation',array(
		'default' => __('Creative Designer','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_two_designation',array(
		'label'   => __('Designation','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));
		
		
		
		
}

add_action( 'customize_register', 'icycp_consultup_testimonial_customize_register' );
endif;


/**
 * Selective refresh for testimonial section
 */
function icycp_consultup_register_testimonial_section_partials( $wp_customize ){


	
	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'testimonial_callout_background', array(
		'selector'            => 'section.testimonial-section',
		'settings'            => 'testimonial_callout_background',
	
	) );
	
	//Testimonial one
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_title', array(
		'selector'            => '.testimonials-section .consultup-heading h3',
		'settings'            => 'testimonial_section_title',
		'render_callback'  => 'icycp_consultup_testimonial_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_discription', array(
		'selector'            => '.testimonials-section .consultup-heading p',
		'settings'            => 'testimonial_section_discription',
		'render_callback'  => 'icycp_consultup_testimonial_section_discription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_title', array(
		'selector'            => '.testimonial-one .testimonials_qute .sub-qute h5',
		'settings'            => 'testimonial_one_title',
		'render_callback'  => 'icycp_consultup_testimonial_one_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_desc', array(
		'selector'            => '.testimonial-one .testimonials_qute .sub-qute p',
		'settings'            => 'testimonial_one_desc',
		'render_callback'  => 'icycp_consultup_testimonial_one_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_name', array(
		'selector'            => '.testimonial-one .testimonials_qute .consultup-client-info h6',
		'settings'            => 'testimonial_one_name',
		'render_callback'  => 'icycp_consultup_testimonial_name_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_designation', array(
		'selector'            => '.testimonial-one .consultup-client-info p',
		'settings'            => 'testimonial_one_designation',
		'render_callback'  => 'icycp_consultup_testimonial_designation_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_one_thumb', array(
		'selector'            => '.testmonial-area .author-box',
		'settings'            => 'testimonial_one_thumb',
	
	) );
	
	
	//Testimonial one
	$wp_customize->selective_refresh->add_partial( 'testimonial_two_title', array(
		'selector'            => '.testimonial-two .testimonials_qute .sub-qute h5',
		'settings'            => 'testimonial_two_title',
		'render_callback'  => 'icycp_consultup_testimonial_two_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_two_desc', array(
		'selector'            => '.testimonial-two .testimonials_qute .sub-qute p',
		'settings'            => 'testimonial_two_desc',
		'render_callback'  => 'icycp_consultup_testimonial_two_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_two_name', array(
		'selector'            => '.testimonial-two .testimonials_qute .consultup-client-info h6',
		'settings'            => 'testimonial_two_name',
		'render_callback'  => 'icycp_consultup_testimonial_two_name_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_two_designation', array(
		'selector'            => '.testimonial-two .testimonial_two_designation-client-info p',
		'settings'            => 'testimonial_two_designation',
		'render_callback'  => 'icycp_consultup_testimonial_two_designation_render_callback',
	
	) );
	
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_two_thumb', array(
		'selector'            => '.testimonial-two .testmonial-area .author-box',
		'settings'            => 'testimonial_two_thumb',
	
	) );
	
	
}

add_action( 'customize_register', 'icycp_consultup_register_testimonial_section_partials' );

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
if ( ! function_exists( 'icycp_consultup_news_customize_register' ) ) :
function icycp_consultup_news_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

$wp_customize->add_section( 'news_section' , array(
		'title' => __('News settings', 'consultup'),
		'panel' => 'homepage_sections',
   	) );
	
	$wp_customize->add_setting( 'news_section_show',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Consultup_Toggle_Switch_Custom_control( $wp_customize, 'news_section_show',
		   array(
			  'label' => esc_html__( 'News Enable/Disable','consultup'),
			  'section' => 'news_section'
		   )
	) );
	
	
	
	$wp_customize->add_setting(
		'news_section_title', array(
        'capability' => 'edit_theme_options',
		'default' => __('Latest News','consultup'),
		'transport' => $selective_refresh
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
        'label' => __('News section description', 'consultup'),
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
        'label' => __('Excerpt length', 'consultup'),
        'section' => 'news_section',
        'type' => 'number',
    ) );



    $wp_customize->add_setting(
		'news_section_post_count', array(
        'capability' => 'edit_theme_options',
		'default' => __('3','consultup'),
    ) );
    $wp_customize->add_control( 'news_section_post_count', array(
        'label' => __('Number of Items', 'consultup'),
        'section' => 'news_section',
        'type' => 'select',
        'choices' => array('3'=>__('3', 'consultup'),'6' => __('6','consultup'), '9' => __('9','consultup'),'12'=> __('12','consultup')),
    ) );

    $wp_customize->add_setting(
        'header_img_bg_color', array( 'sanitize_callback' => 'sanitize_text_field',
        'default' =>'#051b44 ',
    ) );
    
    $wp_customize->add_control(new Consultup_Customize_Alpha_Color_Control( $wp_customize,
        'header_img_bg_color', array(
        'label'      => __('Overlay Color', 'consultup' ),
        'palette' => true,
        'section' => 'header_image')
    ) );
    
}

add_action( 'customize_register', 'icycp_consultup_news_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icypb_consultup_home_news_register_section_partials( $wp_customize ){

	
	
	//News Title
	$wp_customize->selective_refresh->add_partial( 'news_section_title', array(
		'selector'            => '.consultup-blog-section .consultup-heading h3',
		'settings'            => 'news_section_title',
		'render_callback'  => 'icypb_consultup_news_section_title_render_callback',
	
	) );
	
	//News Description
	$wp_customize->selective_refresh->add_partial( 'news_section_description', array(
		'selector'            => '.consultup-blog-section .consultup-heading p',
		'settings'            => 'news_section_description',
		'render_callback'  => 'icypb_consultup_news_section_description_render_callback',
	
	) );
}

add_action( 'customize_register', 'icypb_consultup_home_news_register_section_partials' );


function icypb_consultup_news_section_title_render_callback() {
	return get_theme_mod( 'news_section_title' );
}

function icypb_consultup_news_section_description_render_callback() {
	return get_theme_mod( 'news_section_description' );
}


//Sanatize text validation
function icycp_conulstup_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}