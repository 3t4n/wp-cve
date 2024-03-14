<?php 
// customizer header settings
add_action( 'customize_register', 'amigo_industri_customizer_header_settings');
function amigo_industri_customizer_header_settings( $wp_customize ) {

	$default = amigo_industri_default_settings();

	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	// panel
	$wp_customize->add_panel( 
		'theme_header', 
		array(
			'priority'      => 22,
			'capability'    => 'edit_theme_options',
			'title'			=> __('Header', 'amigo-extensions'),
		) 
	);	

	//**********************************************
		//section header top 2
	//********************************************* 

	$wp_customize->add_section( 'aboe_header' , array(
		'title' =>  __( 'Above Header', 'amigo-extensions' ),
		'panel' => 'theme_header',
		'priority'      => 1,
	));	

	// show/hide header top bar
	$wp_customize->add_setting(
		'is_header_top_bar',
		array(
			'default' => $default['is_header_top_bar'],
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'is_header_top_bar',
		array(
			'label'   		=> __('Show/Hide Section','amigo-extensions'),
			'section'		=> 'aboe_header',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);

	// seprator	link button		
	$wp_customize->add_setting('separator_header_button', array('priority'=> 10));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_header_button', array(
			'label' => __('Button','amigo-extensions'),
			'settings' => 'separator_header_button',
			'section' => 'aboe_header',					
		)));
	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'header_button_text', array(
		'selector'            => '#header-top-right',				
		'render_callback'  => function() { return get_theme_mod( 'header_button_text' ); },

	) );



	// show/hide link button	
	$wp_customize->add_setting(
		'display_header_button',
		array(
			'default' => $default['display_header_button'],
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_header_button',
		array(
			'label'   		=> __('Show/Hide link button','amigo-extensions'),
			'section'		=> 'aboe_header',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);
	

	// button text 
	$wp_customize->add_setting(
		'header_button_text',
		array(
			'default' => esc_html( $default['header_button_text'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'header_button_text',
		array(
			'label'   		=> __('Button Text', 'amigo-extensions'),
			'section'		=> 'aboe_header',
			'type' 			=> 'text',
			'transport'      => $selective_refresh,
		)  
	);

	// button link 
	$wp_customize->add_setting(
		'header_button_link',
		array(
			'default' => esc_html( $default['header_button_link'] ),
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_text',
			'priority'      => 2,
		)
	);	

	$wp_customize->add_control( 
		'header_button_link',
		array(
			'label'   		=> __('Button Link', 'amigo-extensions'),
			'section'		=> 'aboe_header',
			'type' 			=> 'text',
			'transport'      => $selective_refresh,
		)  
	);	

	// seprator	office contact		
	$wp_customize->add_setting('separator_office_contact_items', array('priority'=> 10));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_office_contact_items', array(
			'label' => __('Office Details','amigo-extensions'),
			'settings' => 'separator_office_contact_items',
			'section' => 'aboe_header',					
		)));
	
	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'office_contact_items', array(
		'selector'            => '.header-top-left .header-top-contact',				
		'render_callback'  => function() { return get_theme_mod( 'office_contact_items' ); },

	) );
	

	// office contact
	$wp_customize->add_setting( 'office_contact_items', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_default_office_contact_items(),
	));
	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'office_contact_items', array(
		'label'   => esc_html__('Office Contact','amigo-extensions'),
		'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
		'section' => 'aboe_header',
		'priority' => 10,						
		'customizer_repeater_icon_control' => true,	
		'customizer_repeater_text_control' => true,								
		'customizer_repeater_link_control' => false,						

	) ) );

	// seprator	social icons		
	$wp_customize->add_setting('separator_social_icons', array('priority'=> 10));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_social_icons', array(
			'label' => __('Social Icons','amigo-extensions'),
			'settings' => 'separator_social_icons',
			'section' => 'aboe_header',					
		)));
	
	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'social_icons', array(
		'selector'            => '.header-top-right .social-media',				
		'render_callback'  => function() { return get_theme_mod( 'social_icons' ); },

	) );

	// social icons
	$wp_customize->add_setting( 'social_icons', array(
		'sanitize_callback' => 'amigo_repeater_sanitize',
		'default' => amigo_industri_default_social_icons(),
	));
	$wp_customize->add_control( new Amigo_Customizer_Repeater( $wp_customize, 'social_icons', array(
		'label'   => esc_html__('Social Icons','amigo-extensions'),
		'item_name' => esc_html__( 'Item', 'amigo-extensions' ),
		'section' => 'aboe_header',
		'priority' => 10,						
		'customizer_repeater_icon_control' => true,	
		// 'customizer_repeater_text_control' => true,								
		'customizer_repeater_link_control' => true,						

	) ) );
	

	//**********************************************
		//section header navigation 3
	//********************************************* 
	$wp_customize->add_section( 'header_navigation' , array(
		'title' =>  __( 'Header Navigation', 'amigo-extensions' ),
		'panel' => 'theme_header',
		'priority'      => 1,
	) );
	

	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'display_navigation_search_button', array(
		'selector'            => 'a.search-btn',				
		'render_callback'  => function() { return get_theme_mod( 'display_navigation_search_button' ); },

	) );

	// seprator	navigation search 		
	$wp_customize->add_setting('separator_navigation_search', array('priority'=> 10));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_navigation_search', array(
			'label' => __('Search','amigo-extensions'),
			'settings' => 'separator_navigation_search',
			'section' => 'header_navigation',					
		)));

	// show/hide search button	
	$wp_customize->add_setting(
		'display_navigation_search_button',
		array(
			'default' => $default['display_navigation_search_button'],
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_navigation_search_button',
		array(
			'label'   		=> __('Show/Hide','amigo-extensions'),
			'section'		=> 'header_navigation',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);

	// seprator	cart button 		
	$wp_customize->add_setting('separator_cart', array('priority'=> 10));
	$wp_customize->add_control(new Amigo_Separator( $wp_customize, 
		'separator_cart', array(
			'label' => __('Cart','amigo-extensions'),
			'settings' => 'separator_cart',
			'section' => 'header_navigation',					
		)));
	
	// selective refresh
	$wp_customize->selective_refresh->add_partial( 'display_cart_button', array(
		'selector'            => 'a.cart-btn',				
		'render_callback'  => function() { return get_theme_mod( 'display_cart_button' ); },

	) );

	// show/hide cart	
	$wp_customize->add_setting(
		'display_cart_button',
		array(
			'default' => $default['display_cart_button'],
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'display_cart_button',
		array(
			'label'   		=> __('Show/Hide','amigo-extensions'),
			'section'		=> 'header_navigation',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);
	

	//**********************************************
		//section header sticky 4
	//********************************************* 
	$wp_customize->add_section( 'header_sticky' , array(
		'title' =>  __( 'Sticky Header', 'amigo-extensions' ),
		'panel' => 'theme_header',
		'priority'      => 1,
	) );

	// sticky header	
	$wp_customize->add_setting(
		'sticky_header',
		array(
			'default' => $default['sticky_header'],
			'capability'     	=> 'edit_theme_options',
			'sanitize_callback' => 'amigo_sanitize_checkbox',
			'priority'      => 1,
		)
	);	

	$wp_customize->add_control( 
		'sticky_header',
		array(
			'label'   		=> __('Show/Hide Sticky Header','amigo-extensions'),
			'section'		=> 'header_sticky',
			'type' 			=> 'checkbox',
			'transport'         => $selective_refresh,
		)  
	);	

	//**********************************************
		//logo width 5
	//********************************************* 

	if ( class_exists( 'Amigo_Extensions_Range_Control' ) ) {
		$wp_customize->add_setting('logo_width',
			array(
				'default'     	=> 207,
				'capability'     	=> 'edit_theme_options',
				'sanitize_callback' => 'amigo_sanitize_range',
				'transport'         => 'postMessage',						
			)
		);
		$wp_customize->add_control( 
			new Amigo_Extensions_Range_Control( $wp_customize, 'logo_width', 
				array(
					'label'      => __( 'Logo Width', 'amigo-extensions'),
					'section'  => 'title_tagline',
					'priority' => 40,
					'input_attrs' => array(
						'min'    => 1,
						'step'   => 1,
						'max'    => 500,		

					),
				) ) 
		);
	}
}