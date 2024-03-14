<?php
function jewelry_store_customizer_testimonial( $wp_customize ){

	$option = jewelry_store_reset_data();

		$wp_customize->add_section( 'testimonial_section' ,
			array(
				'priority'    => 4,
				'title'       => esc_html__( 'Testimonial', 'britetechs-companion' ),
				'panel'       => 'frontpage',
			)
		);
			$wp_customize->add_setting( 'jewelrystore_option[testimonial_enable]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['testimonial_enable'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[testimonial_enable]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Testimonial Enable', 'britetechs-companion'),
					'section'     => 'testimonial_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[testimonial_subtitle]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['testimonial_subtitle'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[testimonial_subtitle]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'britetechs-companion'),
					'section'     => 'testimonial_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[testimonial_title]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['testimonial_title'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[testimonial_title]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'britetechs-companion'),
					'section'     => 'testimonial_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[testimonial_desc]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['testimonial_desc'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[testimonial_desc]',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Section Subtitle', 'britetechs-companion'),
					'section'     => 'testimonial_section',
				)
			);

			$wp_customize->add_setting(
				'jewelrystore_option[testimonial_contents]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'type' => 'option',
					'default' => json_encode(bc_testimonial_default_contents()),
				) );

			$wp_customize->add_control(
				new Jewelry_Store_Customize_Repeatable_Control(
					$wp_customize,
					'jewelrystore_option[testimonial_contents]',
					array(
						'label'     => esc_html__('Testimonial Content', 'britetechs-companion'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'testimonial_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'britetechs-companion'), // [live_title]
						'max_item'      => 4,
						'limited_msg'   => wp_kses_post( __('<a target="_blank" href="'.esc_url('https://britetechs.com/jewelry-store-pro-wordpress-theme/').'">Upgrade to PRO</a>', 'britetechs-companion' ) ),
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Client Image', 'britetechs-companion'),
								'type'  =>'media',
								'default' => array(
									'url' => '',
									'id' => ''
								)
							),
							'title' => array(
								'title' => esc_html__('Client Name', 'britetechs-companion'),
								'type'  =>'text',
								'default' => esc_html__('Client Name', 'britetechs-companion'),
							),
							'position' => array(
								'title' => esc_html__('Designation', 'britetechs-companion'),
								'type'  =>'text',
								'default' => esc_html__('Client Designation', 'britetechs-companion'),
							),
							'desc' => array(
								'title' => esc_html__('Testimonial Content', 'britetechs-companion'),
								'type'  =>'editor',
								'default' => esc_html__('Client Review Content', 'britetechs-companion'),
							),
					
						),

					)
				)
			);

			// container width
            $wp_customize->add_setting( 'jewelrystore_option[testimonial_container_width]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['testimonial_container_width'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[testimonial_container_width]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Container Width', 'britetechs-companion'),
                    'section'     => 'testimonial_section',
                    'choices' => array(
                    	'container'=> __('Container','britetechs-companion'),
                    	'container-fluid'=> __('Container Full','britetechs-companion')
                    	),
                )
            );

            // column layout
            $wp_customize->add_setting( 'jewelrystore_option[testimonial_column]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['testimonial_column'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[testimonial_column]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Column Layout', 'britetechs-companion'),
                    'section'     => 'testimonial_section',
                    'choices' => array(
                    	2 => __('2 Column','britetechs-companion'),
                    	3 => __('3 Column','britetechs-companion'),
                    	4 => __('4 Column','britetechs-companion'),
                    	),
                )
            );

			$wp_customize->add_setting( 'jewelrystore_option[testimonial_bg_image]',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => $option['testimonial_bg_image'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( new wp_Customize_Image_Control( $wp_customize,'jewelrystore_option[testimonial_bg_image]',
				array(
					'label'       => esc_html__('Section Background Image', 'britetechs-companion'),
					'section'     => 'testimonial_section',
				) )
			);
}
add_action('customize_register','jewelry_store_customizer_testimonial');