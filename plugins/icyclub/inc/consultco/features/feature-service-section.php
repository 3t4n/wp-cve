<?php
if ( ! function_exists( 'icycp_consultco_service_customize_register' ) ) :
function icycp_consultco_service_customize_register($wp_customize){
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


		// Service section title
		$wp_customize->add_setting( 'service_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Services','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	

		$wp_customize->add_setting( 'service_section_subtitle',array(
		'capability'     => 'edit_theme_options',
		'default' => __('We Create Digital Opportunities','icyclub'),
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_subtitle',array(
		'label'   => __('Subtitle','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));
		
		
		
		//Service section discription
		$wp_customize->add_setting( 'service_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default' => 'Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim idm est laborum.',
		'sanitize_callback' => 'icycp_conulstup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'service_section_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));
		
		$wp_customize->add_setting( 'service_contents', 
			array(
			 //'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => consultco_get_service_default()
			)
		);
		
		$wp_customize->add_control( 
			new Consultco_Repeater( $wp_customize, 
				'service_contents', 
					array(
						'label'   => esc_html__('Service','icyclub'),
						'section' => 'services_section',
						'add_field_label'                   => esc_html__( 'Add New Service', 'icyclub' ),
						'item_name'                         => esc_html__( 'Service', 'icyclub' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
						'customizer_repeater_link_control' => true,
					) 
				) 
			);	

		//Pro Button
		class Consultco_services__section_upgrade extends WP_Customize_Control {
			public function render_content() { ?>
				<h3 class="customizer_consultcoservice_upgrade_section" style="display: none;">
		<?php _e('To add More Service? Then','icyclub'); ?><a href="<?php echo esc_url( 'https://themeansar.com/consultco-pro' ); ?>" target="_blank">
					<?php _e('Upgrade to Pro','icyclub'); ?> </a>  
				</h3>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'consultco_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Consultco_services__section_upgrade(
			$wp_customize,
			'consultco_service_upgrade_to_pro',
				array(
					'section'				=> 'services_section',
					'settings'				=> 'consultco_service_upgrade_to_pro',
				)
			)
		);
}
add_action( 'customize_register', 'icycp_consultco_service_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_consultco_register_home_service_section_partials( $wp_customize ){

	//Slider section
	$wp_customize->selective_refresh->add_partial( 'consultco_service_content', array(
		'selector'            => '.service-section #service_content_section',
		'settings'            => 'consultco_service_content',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'service_section_title', array(
		'selector'            => '.service .bs-subtitle',
		'settings'            => 'service_section_title',
		'render_callback'  => 'icycp_consultco_service_section_title_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'service_section_subtitle', array(
		'selector'            => '.service .bs-title',
		'settings'            => 'service_section_subtitle',
		'render_callback'  => 'icycp_consultco_service_section_subtitle_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'service_section_discription', array(
		'selector'            => '.service .bs-desc',
		'settings'            => 'service_section_discription',
		'render_callback'  => 'icycp_consultco_service_section_discription_render_callback',
	
	) );
	
}
add_action( 'customize_register', 'icycp_consultco_register_home_service_section_partials' );

function icycp_consultco_service_section_title_render_callback() {
	return get_theme_mod( 'service_section_title' );
}

function icycp_consultco_service_section_subtitle_render_callback() {
	return get_theme_mod( 'service_section_subtitle' );
}

function icycp_consultco_service_section_discription_render_callback() {
	return get_theme_mod( 'service_section_discription' );
}