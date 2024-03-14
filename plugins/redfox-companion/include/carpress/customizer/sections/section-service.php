<?php 
function carpress_section_service( $wp_customize ){
	$option = carpress_default_data();
	
	$pages  =  get_pages();
	$option_pages = array();
	$option_pages[0] = esc_html__( 'Select page', 'carpress' );
	foreach( $pages as $p ){
		$option_pages[ $p->ID ] = $p->post_title;
	}

	$users = get_users( array(
		'orderby'      => 'display_name',
		'order'        => 'ASC',
		'number'       => '',
	) );

	$option_users[0] = esc_html__( 'Select member', 'carpress' );
	foreach( $users as $user ){
		$option_users[ $user->ID ] = $user->display_name;
	}
	
	$wp_customize->add_panel( 'service_panel', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'      => __('Section : Service', 'carpress'),
	) );	

		$wp_customize->add_section( 'service_setting' , array(
			'title'      => __('Service Settings', 'carpress'),
			'panel'  => 'service_panel',
		) );

			$wp_customize->add_setting( 'carpress_options[service_enable]' , array(
			'default'    => $option['service_enable'],
			'sanitize_callback' => 'carpress_sanitize_checkbox',
			'type'=>'option',
			));
			$wp_customize->add_control('carpress_options[service_enable]' , array(
			'label' => __('Service Section Enable','carpress' ),
			'description' => __('Check this setting to enable service section on the FrontPage.','carpress' ),
			'section' => 'service_setting',
			'type'=>'checkbox',
			) );

			$wp_customize->add_setting( 'carpress_options[service_title]' , array(
			'default'    => $option['service_title'],
			'sanitize_callback' => 'wp_kses_post',
			'type'=>'option',
			'transport' => 'postMessage',
			));
			$wp_customize->add_control('carpress_options[service_title]' , array(
			'label' => __('Service Title','carpress' ),
			'description' => __('This setting for service section title.','carpress' ),
			'section' => 'service_setting',
			'type'=>'text',
			) );

			$wp_customize->add_setting( 'carpress_options[service_subtitle]' , array(
			'default'    => $option['service_subtitle'],
			'sanitize_callback' => 'wp_kses_post',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[service_subtitle]' , array(
			'label' => __('Service Subtitle','carpress' ),
			'description' => __('This setting for service section subtitle.','carpress' ),
			'section' => 'service_setting',
			'type'=>'text',
			) );
			
			$wp_customize->add_setting( 'carpress_options[service_layout]' , array(
			'default'    => $option['service_layout'],
			'sanitize_callback' => 'sanitize_text_field',
			'type'=>'option'
			));
			$wp_customize->add_control('carpress_options[service_layout]' , array(
			'label' => __('Service layout settings','carpress' ),
			'description' => __('Choose your service layout column.','carpress' ),
			'section' => 'service_setting',
			'type'=>'select',
			'choices' => array(
				'12' => __('1 Column','carpress'),
				'6' => __('2 Column','carpress'),
				'4' => __('3 Column','carpress'),
				'3' => __('4 Column','carpress'),
			) ) );
			
		$wp_customize->add_section( 'service_content' , array(
			'title'      => __('Service Content', 'carpress'),
			'panel'  => 'service_panel',
		) );	

			$wp_customize->add_setting('carpress_options[services]',	array(
				'sanitize_callback' => 'carpress_sanitize_repeatable_data_field',
				'transport' => 'refresh',
				'type'=>'option',
			) );
			$wp_customize->add_control(new carpress_Customize_Repeatable_Control($wp_customize,
					'carpress_options[services]',
					array(
						'label'     	=> esc_html__('Service content', 'carpress'),
						'description'   => '',
						'section'       => 'service_content',
						'live_title_id' => 'content_page', 
						'title_format'  => esc_html__('[live_title]', 'carpress'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="http://redfoxthemes.com/themes/">carpress Pro</a> to be able to add more items and unlock other premium features!', 'carpress' ) ),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon', 'carpress'),
								'type'  =>'select',
								'options' => array(
									'icon' => esc_html__('Icon', 'carpress'),
									'image' => esc_html__('image', 'carpress'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon', 'carpress'),
								'type'  =>'icon',
								'required' => array( 'icon_type', '=', 'icon' ),
							),
							'image'  => array(
								'title' => esc_html__('Image', 'carpress'),
								'type'  =>'media',
								'required' => array( 'icon_type', '=', 'image' ),
							),
							'title'  => array(
								'title' => esc_html__('Title', 'carpress'),
								'type'  =>'text',
							),
							'desc'  => array(
								'title' => esc_html__('Description', 'carpress'),
								'type'  =>'editor',
							),
							'btntext'  => array(
								'title' => esc_html__('Buttton Text', 'carpress'),
								'type'  =>'text',
							),
							'btnlink'  => array(
								'title' => esc_html__('Buttton Link', 'carpress'),
								'type'  =>'text',
							),
						),

					)
				)
			);			
}
add_action( 'customize_register', 'carpress_section_service' );