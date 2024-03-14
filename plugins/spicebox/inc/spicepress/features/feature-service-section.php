<?php
$Spicebox_Repeater_path = trailingslashit( SPICEB_PLUGIN_DIR ) . '/inc/controls/customizer-repeater/functions.php';
if ( file_exists( $Spicebox_Repeater_path ) ) {
require_once( $Spicebox_Repeater_path );
}

if ( ! function_exists( 'spiceb_spicepress_service_customize_register' ) ) :
function spiceb_spicepress_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	
/* Services section */
	$wp_customize->add_section( 'services_section' , array(
		'title'      => __('Service settings', 'spicebox'),
		'panel'  => 'section_settings',
		'priority'   => 1,
	) );
		
		
		// Enable service more btn
		$wp_customize->add_setting( 'home_service_section_enabled' , array( 'default' => 'on') );
		$wp_customize->add_control(	'home_service_section_enabled' , array(
				'label'    => __( 'Enable Services on homepage', 'spicebox' ),
				'section'  => 'services_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'spicebox'),
					'off'=>__('OFF', 'spicebox')
				)
		));
		
		
		// Service section title
		$wp_customize->add_setting( 'home_service_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Nisl At Est?','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_service_section_title',array(
		'label'   => __('Title','spicebox'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//room section discription
		$wp_customize->add_setting( 'home_service_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim mei ludus efficiendi ei sea summo mazim ex.','spicebox'),
		'sanitize_callback' => 'spiceb_spicepress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'home_service_section_discription',array(
		'label'   => __('Description','spicebox'),
		'section' => 'services_section',
		'type' => 'textarea',
		));	
		
		if ( class_exists( 'Spicebox_Repeater' ) ) {
			$wp_customize->add_setting( 'spicepress_service_content', array(
			) );

			$wp_customize->add_control( new Spicebox_Repeater( $wp_customize, 'spicepress_service_content', array(
				'label'                             => esc_html__( 'Service content', 'spicebox' ),
				'section'                           => 'services_section',
				'priority'                          => 10,
				'add_field_label'                   => esc_html__( 'Add new Service', 'spicebox' ),
				'item_name'                         => esc_html__( 'Service', 'spicebox' ),
				'customizer_repeater_icon_control'  => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				'customizer_repeater_image_control' => true,
				) ) );
		}	
                
if (wp_get_theme()->name == 'Stacy' && version_compare(wp_get_theme()->get('Version'), '1.3.3') > 0):
        //    set value for old & new user after this version
    // Service Layout settings
    if(get_option('stacy_user', 'new')=='old') {
        $wp_customize->add_setting('service_design', array(
            'default' => 'default',
            'sanitize_callback' => 'stacy_image_radio_button_sanitization',
        ));
        
    } else {
        $wp_customize->add_setting('service_design', array(
            'default' => 'slide-effect',
            'sanitize_callback' => 'stacy_image_radio_button_sanitization',
        ));
        
    }
    
        $wp_customize->add_control(new Spicebox_Image_Radio_Button_Custom_Control($wp_customize, 'service_design',
            array(
                'label' => esc_html__('Service Design', 'stacy'),
                'section' => 'services_section',
                'choices' => array(
                    'default' => array(
                        'image' => SPICEB_PLUGIN_URL . '/inc/spicepress/images/stacy/stacy-service-default.png',
                        'name' => esc_html__('Standard', 'stacy')
                    ),
                    'slide-effect' => array(
                        'image' => SPICEB_PLUGIN_URL . '/inc/spicepress/images/stacy/stacy-service-slide-type.png',
                        'name' => esc_html__('Slide-effect', 'stacy')
                    )
                )
            )
    ));
    
    endif;
	
}

add_action( 'customize_register', 'spiceb_spicepress_service_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_spicepress_register_home_service_section_partials( $wp_customize ){

	//Slider section
	$wp_customize->selective_refresh->add_partial( 'spicepress_service_content', array(
		'selector'            => '.service-section #service_content_section, .section-module.services2.service_wrapper #service_content_section',
		'settings'            => 'spicepress_service_content',
	
	) );
	
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'home_service_section_title', array(
		'selector'            => '.service-section .section-header .widget-title,.section-module.services5 .widget-title, .section-module.services2.service_wrapper .section-header h1',
		'settings'            => 'home_service_section_title',
		'render_callback'  => 'spiceb_spicepress_service_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'home_service_section_discription', array(
		'selector'            => '.service-section .section-header p,.section-module.services5 .section-header p,.section-module.services2.service_wrapper .section-header p',
		'settings'            => 'home_service_section_discription',
		'render_callback'  => 'spiceb_spicepress_service_section_discription_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'spiceb_spicepress_register_home_service_section_partials' );


function spiceb_spicepress_service_section_title_render_callback() {
	return get_theme_mod( 'home_service_section_title' );
}

function spiceb_spicepress_service_section_discription_render_callback() {
	return get_theme_mod( 'home_service_section_discription' );
}