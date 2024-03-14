<?php
function jewelry_store_customizer_slider( $wp_customize ){

	$option = jewelry_store_reset_data();

	$wp_customize->add_panel( 'frontpage',
		array(
			'priority'       => 31,
			'capability'     => 'edit_theme_options',
			'title'          => esc_html__( 'Frontpage Sections', 'britetechs-companion' ),
		)
	);
		$wp_customize->add_section( 'slider_section' ,
			array(
				'priority'    => 1,
				'title'       => esc_html__( 'Slider', 'britetechs-companion' ),
				'description' => '',
				'panel'       => 'frontpage',
			)
		);
			$wp_customize->add_setting( 'jewelrystore_option[slider_enable]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['slider_enable'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_enable]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Slider Enable', 'britetechs-companion'),
					'section'     => 'slider_section',
				)
			);

			// Container width
            $wp_customize->add_setting( 'jewelrystore_option[slider_container_width]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['slider_container_width'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[slider_container_width]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Container Width', 'britetechs-companion'),
                    'section'     => 'slider_section',
                    'choices' => array(
                    	'container'=> __('Container','britetechs-companion'),
                    	'container-fluid'=> __('Container Full','britetechs-companion')
                    	),
                )
            );

			$wp_customize->add_setting( 'jewelrystore_option[slider_arrow_show]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['slider_arrow_show'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_arrow_show]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Show banner navigations', 'britetechs-companion'),
					'section'     => 'slider_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[slider_pagination_show]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['slider_pagination_show'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_pagination_show]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Show banner navigations dots', 'britetechs-companion'),
					'section'     => 'slider_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[slider_mouse_drag]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['slider_mouse_drag'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_mouse_drag]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Enable drag feature', 'britetechs-companion'),
					'section'     => 'slider_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[slider_smart_speed]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_select',
					'default'           => $option['slider_smart_speed'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_smart_speed]',
				array(
					'type'        => 'select',
					'label'       => esc_html__('Smart Speed', 'britetechs-companion'),
					'section'     => 'slider_section',
					'choices' => array(
						100 => 100,
						500 => 500,
						1000 => 1000,
						1500 => 1500,
						2000 => 2000,
						2500 => 2500,
						3000 => 3000,
						3500 => 3500,
						4000 => 4000,
						4500 => 4500,
						5000 => 5000,
						)
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[slider_scroll_speed]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_select',
					'default'           => $option['slider_scroll_speed'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[slider_scroll_speed]',
				array(
					'type'        => 'select',
					'label'       => esc_html__('Scroll Speed', 'britetechs-companion'),
					'section'     => 'slider_section',
					'choices' => array(
						100 => 100,
						500 => 500,
						1000 => 1000,
						1500 => 1500,
						2000 => 2000,
						2500 => 2500,
						3000 => 3000,
						3500 => 3500,
						4000 => 4000,
						4500 => 4500,
						5000 => 5000,
						)
				)
			);

			$wp_customize->add_setting(
				'jewelrystore_option[slider_images]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'type' => 'option',
					'default' => json_encode(bc_slider_default_contents()),
				) );

			$wp_customize->add_control(
				new Jewelry_Store_Customize_Repeatable_Control(
					$wp_customize,
					'jewelrystore_option[slider_images]',
					array(
						'label'     => esc_html__('Background Images', 'britetechs-companion'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'slider_section',
						'live_title_id' => 'large_text',
						'title_format'  => esc_html__( '[live_title]', 'britetechs-companion'), // [live_title]
						'max_item'      => 2,
						'limited_msg'   => wp_kses_post( __('<a target="_blank" href="'.esc_url('https://britetechs.com/jewelry-store-pro-wordpress-theme/').'">Upgrade to PRO</a>', 'britetechs-companion' ) ),
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'britetechs-companion'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/images/slide1.jpg',
									'id' => ''
								)
							),
							'subtitle' => array(
								'title' => esc_html__('Subtitle', 'britetechs-companion'),
								'type'  =>'textarea',
								'default' => __('Save <span>50%</span> OFF','britetechs-companion'),
							),
							'large_text' => array(
								'title' => esc_html__('Title', 'britetechs-companion'),
								'type'  =>'textarea',
								'default' => __('Welcome To jewelry Store','britetechs-companion'),
							),
							'small_text' => array(
								'title' => esc_html__('Description', 'britetechs-companion'),
								'type'  =>'textarea',
								'default' => __('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.','britetechs-companion'),
							),
							'btn_text' => array(
								'title' => esc_html__('Primary Button Text', 'britetechs-companion'),
								'type'  =>'text',
								'default' => __('Shop Now','britetechs-companion'),
							),
							'btn_link' => array(
								'title' => esc_html__('Primary Button URL', 'britetechs-companion'),
								'type'  =>'text',
								'default' => '#',
							),
							'btn_target' => array(
								'title' => esc_html__('Open in new tab', 'britetechs-companion'),
								'type'  =>'checkbox',
								'default' => true,
							),
							'content_align' => array(
								'title' => esc_html__('Alignment', 'britetechs-companion'),
								'type'  =>'select',
								'default' => 'left',
								'options' => array(
									'start' => __('Left','britetechs-companion'),
									'center' => __('Center','britetechs-companion'),
									'end' => __('Right','britetechs-companion'),
									)
							),

						),

					)
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[slider_overlay_color]',
				array(
					'sanitize_callback' => 'sanitize_hex_color',
					'default'           => $option['slider_overlay_color'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'jewelrystore_option[slider_overlay_color]',
				array(
					'label'       => esc_html__('Overlay Color', 'britetechs-companion'),
					'section'     => 'slider_section',
				)
			) );

			if ( class_exists( 'Jewelry_Store_Customizer_Range_Control' ) ) {

                $wp_customize->add_setting( 'jewelrystore_option[slider_overlay_color_opacity]', array(
                    'default'           => $option['slider_overlay_color_opacity'],
                    'capability'        => 'edit_theme_options',
                    'sanitize_callback' => 'jewelry_store_sanitize_range_value',
                    'type'          => 'option',
                    'priority'      => 5,
                ) );
                $wp_customize->add_control( new Jewelry_Store_Customizer_Range_Control( $wp_customize, 'jewelrystore_option[slider_overlay_color_opacity]', 
                    array(
                        'label'      => __( 'Overlay Color Opacity', 'jewelry-store' ),
                        'section'  => 'slider_section',
                        'media_query'   => false,
                        'input_attr'    => array(
                            'mobile'  => array(
                                'min'           => 0,
                                'max'           => 1,
                                'step'          => 0.1,
                                'default_value' => $option['slider_overlay_color_opacity'],
                            ),
                            'tablet'  => array(
                                'min'           => 0,
                                'max'           => 1,
                                'step'          => 0.1,
                                'default_value' => $option['slider_overlay_color_opacity'],
                            ),
                            'desktop' => array(
                                'min'           => 0,
                                'max'           => 1,
                                'step'          => 0.1,
                                'default_value' => $option['slider_overlay_color_opacity'],
                            ),
                        ),
                    ) ) 
                );

            }
}
add_action('customize_register','jewelry_store_customizer_slider');