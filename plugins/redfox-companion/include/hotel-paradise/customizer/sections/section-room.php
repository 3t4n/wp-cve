<?php 
function rfc_customizer_room_section( $settings ){
	/* room */
	$settings['room_setting'] =	array(
			'room_s_hide' => array(
				'default' => false,
				'sanitize_callback' => 'hotel_paradise_sanitize_checkbox',
				'label' => __('Room Section Disable?','redfox-companion'),
				'desc' => __('Check this to hide room section from Front Page Template.','redfox-companion'),
				'type' => 'checkbox',
			),
			'room_s_column' => array(
				'default' => 3,
				'label' => __('Select Column Layout','redfox-companion'),
				'desc' => __('Please select your rooms column layout.','redfox-companion'),
				'type' => 'select',				
				'choices' => array(
					6 => __('2 Column','redfox-companion'),
					4 => __('3 Column','redfox-companion'),
					3 => __('4 Column','redfox-companion'),
				),				
			),	
		);
		
		$settings['room_header'] =	array(
			'room_s_title' => array(
				'default' => '',
				'sanitize_callback' => 'wp_kses_post',
				'label' => __('Section Title','redfox-companion'),
				'desc' => __('Enter your section title','redfox-companion'),
				'type' => 'text',
			),
			'room_s_subtitle' => array(
				'default' => '',
				'sanitize_callback' => 'wp_kses_post',
				'label' => __('Section Subtitle','redfox-companion'),
				'desc' => __('Enter your section subtitle','redfox-companion'),
				'type' => 'text',
			),
		);
		
		$settings['room_contents'] =	array(
			'room_s_content' => array(
				'default' => '',
				'sanitize_callback' => 'hotel_paradise_sanitize_repeatable_data_field',
				'transport' => 'refresh',
				
				'label' => __('Room Content','redfox-companion'),
				'desc' => __('Click on Add an Item button to add new room.','redfox-companion'),
				'type' => 'theme_repeater',
				'live_title_id' => 'content_page',
				'title_format'  => esc_html__( '[live_title]', 'redfox-companion'),
				'limited_msg' 	=> wp_kses_post( __('Upgrade to <a target="_blank" href="https://redfoxthemes.com/themes/">Hotel Paradise Pro</a> to be able to add more items and unlock other premium features!', 'redfox-companion' ) ),
				'max_item'      => 30,
				'fields'    => array(
					'image'  => array(
						'title' => esc_html__('Room Photo', 'redfox-companion'),
						'type'  =>'media',
					),
					'title'  => array(
						'title' => esc_html__('Room Title', 'redfox-companion'),
						'type'  =>'text',
					),
					'desc'  => array(
						'title' => esc_html__('Room Content', 'redfox-companion'),
						'type'  =>'textarea',
					),
					'link'  => array(
						'title' => esc_html__('Custom Link', 'redfox-companion'),
						'type'  =>'text',
					),
				),
			),			
		);
		
		$settings['room_back'] =	array(
			'room_s_bgcolor' => array(
				'default' => '',
				'label' => __('Background Color','redfox-companion'),
				'desc' => __('Select section background color.','redfox-companion'),
				'type' => 'color',
			),
			'room_s_bgimage' => array(
				'default' => '',
				'label' => __('background Image','redfox-companion'),
				'desc' => __('Upload image for the section.','redfox-companion'),
				'type' => 'image',
			),
		);
		/* End room */
		
	return $settings;
}
add_filter('hotel_paradise_customize_settings','rfc_customizer_room_section');