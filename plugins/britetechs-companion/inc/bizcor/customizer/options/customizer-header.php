<?php
function bc_bizcor_customizer_header( $wp_customize ){

	    global $bizcor_options;
		
		// Header Above Section
		$wp_customize->add_section( 'header_above',
			array(
				'priority'    => 2,
				'title'       => esc_html__('Header Above','bizcor'),
				'panel'       => 'bizcor_header',
			)
		);
			// bizcor_topbar_disable
			$wp_customize->add_setting('bizcor_topbar_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_topbar_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_topbar_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide header topbar?', 'bizcor'),
					'section'     => 'header_above',
				)
			);

			// bizcor_topbar_content
			$wp_customize->add_setting('bizcor_topbar_content',array(
					'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
					'transport'         => 'refresh', // refresh or postMessage
					'priority'          => 2,
					'default'           => $bizcor_options['bizcor_topbar_content'],
				) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_topbar_content',
					array(
						'label'         => esc_html__('Header Text','bizcor'),
						'section'       => 'header_above',
						'live_title_id' => 'text', // apply for unput text and textarea only
						'title_format'  => esc_html__( '[live_title]','bizcor'), // [live_title]
						'max_item'      => 2,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							'text' => array(
								'title' => esc_html__('Text','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
						),
					)
				)
			);

			// bizcor_topbar_icons
			$wp_customize->add_setting('bizcor_topbar_icons',array(
				'sanitize_callback' => 'bizcor_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
				'priority'  => 3,
				'default' => $bizcor_options['bizcor_topbar_icons'],
			) );

			$wp_customize->add_control(new Bizcor_Repeatable_Control($wp_customize,'bizcor_topbar_icons',
					array(
						'label'         => esc_html__('Social Icons','bizcor'),
						'section'       => 'header_above',
						'live_title_id' => 'icon', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]','bizcor'), // [live_title]
						'max_item'      => 3,
						'limited_msg' 	=> bizcor_upgrade_pro_msg(),
						'fields'    => array(
							'icon_type'  => array(
								'title' => esc_html__('Custom icon','bizcor'),
								'type'  =>'select',
								'options' => array(
									'icon' => esc_html__('Icon', 'bizcor'),
									//'image' => esc_html__('image','bizcor'),
								),
							),
							'icon'  => array(
								'title' => esc_html__('Icon','bizcor'),
								'type'  =>'icon',
								'required' => array('icon_type','=','icon'),
							),
							'image'  => array(
								'title' => esc_html__('Image','bizcor'),
								'type'  =>'media',
								'required' => array('icon_type','=','image'),
							),
							'link' => array(
								'title' => esc_html__('Link','bizcor'),
								'type'  =>'text',
								'desc'  => '',
							),
						),
					)
				)
			);

			// bizcor_topbar_icons_target
			$wp_customize->add_setting('bizcor_topbar_icons_target',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_topbar_icons_target'],
						'priority'          => 4,
					)
				);
			$wp_customize->add_control('bizcor_topbar_icons_target',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Social icons open in new tab?', 'bizcor'),
					'section'     => 'header_above',
				)
			);

		// Header Bottom Section
		$wp_customize->add_section( 'header_bottom',
			array(
				'priority'    => 4,
				'title'       => esc_html__('Header Bottom','bizcor'),
				'panel'       => 'bizcor_header',
			)
		);

			// bizcor_h_left_icon
			$wp_customize->add_setting('bizcor_h_left_icon',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_left_icon'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control(new Bizcor_Iconpicker_Control($wp_customize,'bizcor_h_left_icon',
				array(
					'label'       => esc_html__('Header left info icon', 'bizcor'),
					'section'     => 'header_bottom',
				)
			) );

			// bizcor_h_left_title
			$wp_customize->add_setting('bizcor_h_left_title',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_left_title'],
						'priority'          => 2,
					)
				);
			$wp_customize->add_control('bizcor_h_left_title',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Header left info title','bizcor'),
					'section'     => 'header_bottom',
				)
			);

			// bizcor_h_left_desc
			$wp_customize->add_setting('bizcor_h_left_desc',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_left_desc'],
						'priority'          => 3,
					)
				);
			$wp_customize->add_control('bizcor_h_left_desc',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Header left info description','bizcor'),
					'section'     => 'header_bottom',
				)
			);

			// bizcor_h_right_icon
			$wp_customize->add_setting('bizcor_h_right_icon',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_right_icon'],
						'priority'          => 4,
					)
				);
			$wp_customize->add_control(new Bizcor_Iconpicker_Control($wp_customize,'bizcor_h_right_icon',
				array(
					'label'       => esc_html__('Header right info icon', 'bizcor'),
					'section'     => 'header_bottom',
				)
			) );

			// bizcor_h_right_title
			$wp_customize->add_setting('bizcor_h_right_title',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_right_title'],
						'priority'          => 5,
					)
				);
			$wp_customize->add_control('bizcor_h_right_title',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Header right info title','bizcor'),
					'section'     => 'header_bottom',
				)
			);

			// bizcor_h_right_desc
			$wp_customize->add_setting('bizcor_h_right_desc',
					array(
						'sanitize_callback' => 'sanitize_text_field',
						'default'           => $bizcor_options['bizcor_h_right_desc'],
						'priority'          => 6,
					)
				);
			$wp_customize->add_control('bizcor_h_right_desc',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Header right info description','bizcor'),
					'section'     => 'header_bottom',
				)
			);

		// Header Sticky
		$wp_customize->add_section( 'header_sticky',
			array(
				'priority'    => 5,
				'title'       => esc_html__('Header Sticky','bizcor'),
				'panel'       => 'bizcor_header',
			)
		);

			// bizcor_h_sticky_disable
			$wp_customize->add_setting('bizcor_h_sticky_disable',
					array(
						'sanitize_callback' => 'bizcor_sanitize_checkbox',
						'default'           => $bizcor_options['bizcor_h_sticky_disable'],
						'priority'          => 1,
					)
				);
			$wp_customize->add_control('bizcor_h_sticky_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide sticky header?','bizcor'),
					'section'     => 'header_sticky',
				)
			);
}
add_action('customize_register','bc_bizcor_customizer_header');