<?php
function industryup_features_setting( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';
	/*=========================================
	Service  Section
	=========================================*/
	$wp_customize->add_section(
		'feature_setting', array(
			'title' => esc_html__( 'Features Section', 'icyclub' ),
			'priority' => 30,
			'panel' => 'homepage_sections',
		)
	);
	

	// Enable slider
		$wp_customize->add_setting( 'feature_enable_disable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'feature_enable_disable',
		   array(
			  'label' => esc_html__( 'Feature Enable/Disable' ),
			  'section' => 'feature_setting'
		   )
		) );
	
	// Feature Title // 
	$wp_customize->add_setting(
    	'feature_title',
    	array(
	        'default'			=> __('Usefull Feature','icyclub'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'industryup_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 4,
		)
	);	
	
	$wp_customize->add_control( 
		'feature_title',
		array(
		    'label'   => __('Title','icyclub'),
		    'section' => 'feature_setting',
			'type'           => 'text',
		)  
	);
	
	// Service Subtitle // 
	$wp_customize->add_setting(
    	'feature_subtitle',
    	array(
	        'default'			=> __('Features we Provide','icyclub'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'industryup_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 5,
		)
	);	
	
	$wp_customize->add_control( 
		'feature_subtitle',
		array(
		    'label'   => __('Subtitle','icyclub'),
		    'section' => 'feature_setting',
			'type'           => 'textarea',
		)  
	);
	
	// Feature Description // 
	$wp_customize->add_setting(
    	'feature_description',
    	array(
	        'default'			=> __('Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim idm est laborum.','icyclub'),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'industryup_sanitize_text',
			'transport'         => $selective_refresh,
			'priority' => 6,
		)
	);	
	
	$wp_customize->add_control( 
		'feature_description',
		array(
		    'label'   => __('Description','icyclub'),
		    'section' => 'feature_setting',
			'type'           => 'textarea',
		)  
	);

	// Fetaures content Section // 
	
	$wp_customize->add_setting(
		'feature_contents_head'
			,array(
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'industryup_sanitize_text',
			'priority' => 7,
		)
	);

	$wp_customize->add_control(
	'feature_contents_head',
		array(
			'type' => 'hidden',
			'label' => __('Content','icyclub'),
			'section' => 'feature_setting',
		)
	);
	
	/**
	 * Customizer Repeater for add Features
	 */
	
		$wp_customize->add_setting( 'features_contents', 
			array(
			 //'transport'         => $selective_refresh,
			 'priority' => 8,
			 'default' => industryup_get_features_default()
			)
		);
		
		$wp_customize->add_control( 
			new Industryup_Repeater( $wp_customize, 
				'features_contents', 
					array(
						'label'   => esc_html__('Features','icyclub'),
						'section' => 'feature_setting',
						'add_field_label'                   => esc_html__( 'Add New Feature', 'icyclub' ),
						'item_name'                         => esc_html__( 'Feature', 'icyclub' ),
						'customizer_repeater_icon_control' => true,
						'customizer_repeater_title_control' => true,
						'customizer_repeater_text_control' => true,
					) 
				) 
			);
			
			
		//Pro feature
		class Industryup_feature__section_upgrade extends WP_Customize_Control {
			public function render_content() { ?>
				<h3 class="customizer_industryupservice_upgrade_section" style="display: none;">
		<?php _e('To add More Feature? Then','icyclub'); ?><a href="<?php echo esc_url( 'https://themeansar.com/industryup-pro' ); ?>" target="_blank">
					<?php _e('Upgrade to Pro','icyclub'); ?> </a>  
				</h3>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'industryup_fearure_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Industryup_services__section_upgrade(
			$wp_customize,
			'industryup_fearure_upgrade_to_pro',
				array(
					'section'				=> 'feature_setting',
					'settings'				=> 'industryup_fearure_upgrade_to_pro',
				)
			)
		);
			
}

add_action( 'customize_register', 'industryup_features_setting' );

// Feature selective refresh
function industryup_features_section_partials( $wp_customize ){	
	// feature_title
	$wp_customize->selective_refresh->add_partial( 'feature_title', array(
		'selector'            => '.features .bs-subtitle',
		'settings'            => 'feature_title',
		'render_callback'  => 'industryup_feature_title_render_callback',
	
	) );
	
	// feature_subtitle
	$wp_customize->selective_refresh->add_partial( 'feature_subtitle', array(
		'selector'            => '.features .bs-title',
		'settings'            => 'feature_subtitle',
		'render_callback'  => 'industryup_feature_subtitle_render_callback',
	
	) );
	
	// feature_description
	$wp_customize->selective_refresh->add_partial( 'feature_description', array(
		'selector'            => '.features .bs-desc',
		'settings'            => 'feature_description',
		'render_callback'  => 'industryup_feature_description_render_callback',
	
	) );
	// features_contents
	$wp_customize->selective_refresh->add_partial( 'features_contents', array(
		'selector'            => '#features-section .features-area'
	
	) );
	
	}

add_action( 'customize_register', 'industryup_features_section_partials' );

// feature_title
function industryup_feature_title_render_callback() {
	return get_theme_mod( 'feature_title' );
}

// feature_subtitle
function industryup_feature_subtitle_render_callback() {
	return get_theme_mod( 'feature_subtitle' );
}

// feature description
function industryup_feature_description_render_callback() {
	return get_theme_mod( 'feature_description' );
}

function industryup_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}