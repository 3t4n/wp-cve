<?php
function jewelry_store_customizer_service( $wp_customize ){

	$option = jewelry_store_reset_data();

		$wp_customize->add_section( 'service_section' ,
			array(
				'priority'    => 2,
				'title'       => esc_html__( 'Service', 'britetechs-companion' ),
				'description' => '',
				'panel'       => 'frontpage',
			)
		);
			$wp_customize->add_setting( 'jewelrystore_option[service_enable]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['service_enable'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[service_enable]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Service Enable', 'britetechs-companion'),
					'section'     => 'service_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[service_subtitle]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['service_subtitle'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[service_subtitle]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'britetechs-companion'),
					'section'     => 'service_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[service_title]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['service_title'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[service_title]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'britetechs-companion'),
					'section'     => 'service_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[service_desc]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['service_desc'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[service_desc]',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Section Subtitle', 'britetechs-companion'),
					'section'     => 'service_section',
				)
			);

			$wp_customize->add_setting(
				'jewelrystore_option[service_contents]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'type' => 'option',
					'default' => json_encode(bc_service_default_contents()),
				) );

			$wp_customize->add_control(
				new Jewelry_Store_Customize_Repeatable_Control(
					$wp_customize,
					'jewelrystore_option[service_contents]',
					array(
						'label'     => esc_html__('Service Content', 'britetechs-companion'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'service_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'britetechs-companion'), // [live_title]
						'limited_msg'   => wp_kses_post( __('<a target="_blank" href="'.esc_url('https://britetechs.com/jewelry-store-pro-wordpress-theme/').'">Upgrade to PRO</a>', 'britetechs-companion' ) ),
						'max_item'      => 4,
						'fields'    => array(
							'icon' => array(
								'title' => esc_html__('Icon', 'britetechs-companion'),
								'type'  =>'icon',
								'default' => 'fa-mobile',
							),
							'title' => array(
								'title' => esc_html__('title', 'britetechs-companion'),
								'type'  =>'text',
								'default' => esc_html__('Your service title', 'britetechs-companion'),
							),
							'desc' => array(
								'title' => esc_html__('Description', 'britetechs-companion'),
								'type'  =>'editor',
								'default' => esc_html__('Your service description', 'britetechs-companion'),
							),
					
						),

					)
				)
			);

            $wp_customize->add_setting( 'jewelrystore_option[service_container_width]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['service_container_width'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[service_container_width]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Container Width', 'britetechs-companion'),
                    'section'     => 'service_section',
                    'choices' => array(
                    	'container'=> __('Container','britetechs-companion'),
                    	'container-fluid'=> __('Container Full','britetechs-companion')
                    	),
                )
            );

            // column layout
            $wp_customize->add_setting( 'jewelrystore_option[service_column]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['service_column'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[service_column]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Column Layout', 'britetechs-companion'),
                    'section'     => 'service_section',
                    'choices' => array(
                    	2 => __('2 Column','britetechs-companion'),
                    	3 => __('3 Column','britetechs-companion'),
                    	4 => __('4 Column','britetechs-companion'),
                    	),
                )
            );
}
add_action('customize_register','jewelry_store_customizer_service');