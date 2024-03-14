<?php 
function rfc_customizer_service_section( $settings ){

	/* service */
	$settings['service_setting'] =	array(
			'service_s_hide' => array(
				'default' => false,
				'sanitize_callback' => 'hotel_paradise_sanitize_checkbox',
				'label' => __('Service Section Disable?','redfox-companion'),
				'desc' => __('Check this to hide service section from Front Page Template.','redfox-companion'),
				'type' => 'checkbox',
			),
			'service_s_column' => array(
				'default' => 3,
				'label' => __('Select Column Layout','redfox-companion'),
				'desc' => __('Please select your services column layout.','redfox-companion'),
				'type' => 'select',				
				'choices' => array(
					6 => __('2 Column','redfox-companion'),
					4 => __('3 Column','redfox-companion'),
					3 => __('4 Column','redfox-companion'),
				),				
			),	
		);
		
		$settings['service_header'] =	array(
				'service_s_title' => array(
					'default' => '',
					'sanitize_callback' => 'wp_kses_post',
					'label' => __('Section Title','redfox-companion'),
					'desc' => __('Enter your section title','redfox-companion'),
					'type' => 'text',
				),
				'service_s_subtitle' => array(
					'default' => '',
					'sanitize_callback' => 'wp_kses_post',
					'label' => __('Section Subtitle','redfox-companion'),
					'desc' => __('Enter your section subtitle','redfox-companion'),
					'type' => 'text',
				),
			);
		
		$settings['service_contents'] =	array(
			'service_s_content' => array(
				'default' => '',
				'sanitize_callback' => 'hotel_paradise_sanitize_repeatable_data_field',
				'transport' => 'refresh',
				
				'label' => __('Service Content','redfox-companion'),
				'desc' => __('Click on Add an Item button to add new service.','redfox-companion'),
				'type' => 'theme_repeater',
				'live_title_id' => 'title',
				'title_format'  => esc_html__( '[live_title]', 'redfox-companion'),
				'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://redfoxthemes.com/themes/">Hotel Paradise Pro</a> to be able to add more items and unlock other premium features!', 'redfox-companion' ) ),
				'max_item'      => 3,
				'fields'    => array(
					'icon_type'  => array(
						'title' => esc_html__('Custom icon', 'redfox-companion'),
						'type'  =>'select',
						'options' => array(
							'icon' => esc_html__('Icon', 'redfox-companion'),
							'image' => esc_html__('image', 'redfox-companion'),
						),
					),
					'icon'  => array(
						'title' => esc_html__('Icon', 'redfox-companion'),
						'type'  =>'icon',
						'required' => array( 'icon_type', '=', 'icon' ),
					),
					'iconcolor'  => array(
						'title' => esc_html__('Icon Color', 'redfox-companion'),
						'type'  =>'color',
					),
					'image'  => array(
						'title' => esc_html__('Image', 'redfox-companion'),
						'type'  =>'media',
						'required' => array( 'icon_type', '=', 'image' ),
					),

					'title'  => array(
						'title' => esc_html__('Title', 'redfox-companion'),
						'type'  =>'text',
					),
					'desc'  => array(
						'title' => esc_html__('Content', 'redfox-companion'),
						'type'  =>'textarea',
					),
					'link'  => array(
						'title' => esc_html__('Custom Link', 'redfox-companion'),
						'type'  =>'text',
					),
					'enable_link'  => array(
						'title' => esc_html__('Link to single page', 'redfox-companion'),
						'type'  =>'checkbox',
					),
				),
			),			
		);
		
		$settings['service_back'] =	array(
			'service_s_bgcolor' => array(
				'default' => '',
				'label' => __('Background Color','redfox-companion'),
				'desc' => __('Select section background color.','redfox-companion'),
				'type' => 'color',
			),
			'service_s_bgimage' => array(
				'default' => '',
				'label' => __('background Image','redfox-companion'),
				'desc' => __('Upload image for the section.','redfox-companion'),
				'type' => 'image',
			),
		);
		/* End Service */
	return $settings;
}
add_filter('hotel_paradise_customize_settings','rfc_customizer_service_section');