<?php
function hotelone_customizer_slider( $wp_customize ){
		global $hotelone_options_default;

		$wp_customize->add_section( 'hotelone_slider_section' ,
			array(
				'priority'    => 1,
				'title'       => esc_html__( 'Section: Slider', 'hotelone' ),
				'panel'       => 'frontpage_panel',
			)
		);
		
			$wp_customize->add_setting('hotelone_slider_disable',
				array(
					'sanitize_callback' => 'hotelone_sanitize_checkbox',
					'default'           => $hotelone_options_default['hotelone_slider_disable'],
					'priority'    => 1,
				)
			);
			$wp_customize->add_control('hotelone_slider_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'hotelone'),
					'section'     => 'hotelone_slider_section',
				)
			);

		    $wp_customize->add_setting('hotelone_slider_images',
			array(
				'sanitize_callback' => 'hotelone_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
				'priority'    => 10,
				'default' => $hotelone_options_default['hotelone_slider_images'],
			) );
			$wp_customize->add_control(new HotelOne_Customize_Repeatable_Control($wp_customize,'hotelone_slider_images',
					array(
						'label'     => esc_html__('Background Images', 'hotelone'),
						'priority'     => 40,
						'section'       => 'hotelone_slider_section',
						'title_format'  => esc_html__( 'Background', 'hotelone'), // [live_title]
						'max_item'      => 2,
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'hotelone'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/images/slide1.jpg',
									'id' => ''
								)
							),
							'large_text' => array(
								'title' => esc_html__('Large Text', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'small_text' => array(
								'title' => esc_html__('Small Text', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'buttontext1' => array(
								'title' => esc_html__('Primary Button Text', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'buttonlink1' => array(
								'title' => esc_html__('Primary Button Link', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'buttontarget1' => array(
								'title' => esc_html__('Open Window In New Tab.', 'hotelone'),
								'type'  =>'checkbox',
								'desc'  => '',
							),
							'buttontext2' => array(
								'title' => esc_html__('Secondary Button Text', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'buttonlink2' => array(
								'title' => esc_html__('Secondary Button Link', 'hotelone'),
								'type'  =>'text',
								'desc'  => '',
							),
							'buttontarget2' => array(
								'title' => esc_html__('Open Window In New Tab.', 'hotelone'),
								'type'  =>'checkbox',
								'desc'  => '',
							),

						),

					)
				)
			);
}
add_action('customize_register','hotelone_customizer_slider');