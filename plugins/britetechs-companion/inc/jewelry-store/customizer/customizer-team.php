<?php
function jewelry_store_customizer_team( $wp_customize ){

	$option = jewelry_store_reset_data();

		$wp_customize->add_section( 'team_section' ,
			array(
				'priority'    => 5,
				'title'       => esc_html__( 'Team', 'britetechs-companion' ),
				'panel'       => 'frontpage',
			)
		);
			$wp_customize->add_setting( 'jewelrystore_option[team_enable]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_checkbox',
					'default'           => $option['team_enable'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[team_enable]',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Team Enable', 'britetechs-companion'),
					'section'     => 'team_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[team_subtitle]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['team_subtitle'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[team_subtitle]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Subtitle', 'britetechs-companion'),
					'section'     => 'team_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[team_title]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['team_title'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[team_title]',
				array(
					'type'        => 'text',
					'label'       => esc_html__('Title', 'britetechs-companion'),
					'section'     => 'team_section',
				)
			);

			$wp_customize->add_setting( 'jewelrystore_option[team_desc]',
				array(
					'sanitize_callback' => 'wp_kses_post',
					'default'           => $option['team_desc'],
					'type' => 'option',
				)
			);
			$wp_customize->add_control( 'jewelrystore_option[team_desc]',
				array(
					'type'        => 'textarea',
					'label'       => esc_html__('Description', 'britetechs-companion'),
					'section'     => 'team_section',
				)
			);

			$wp_customize->add_setting(
				'jewelrystore_option[team_contents]',
				array(
					'sanitize_callback' => 'jewelry_store_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'type' => 'option',
					'default' => json_encode(bc_team_default_contents()),
				) );

			$wp_customize->add_control(
				new Jewelry_Store_Customize_Repeatable_Control(
					$wp_customize,
					'jewelrystore_option[team_contents]',
					array(
						'label'     => esc_html__('Team Content', 'britetechs-companion'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'team_section',
						'live_title_id' => 'title', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'britetechs-companion'), // [live_title]
						'max_item'      => 4,
						'limited_msg'   => wp_kses_post( __('<a target="_blank" href="'.esc_url('https://britetechs.com/jewelry-store-pro-wordpress-theme/').'">Upgrade to PRO</a>', 'britetechs-companion' ) ),
						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Team Image', 'britetechs-companion'),
								'type'  =>'media',
								'default' => array(
									'url' => '',
									'id' => ''
								)
							),
							'title' => array(
								'title' => esc_html__('Team Name', 'britetechs-companion'),
								'type'  =>'text',
								'default' => esc_html__('Team Name', 'britetechs-companion'),
							),
							'position' => array(
								'title' => esc_html__('Designation', 'britetechs-companion'),
								'type'  =>'text',
								'default' => esc_html__('Team Designation', 'britetechs-companion'),
							),
						),

					)
				)
			);

			// container width
            $wp_customize->add_setting( 'jewelrystore_option[team_container_width]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['team_container_width'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[team_container_width]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Container Width', 'britetechs-companion'),
                    'section'     => 'team_section',
                    'choices' => array(
                    	'container'=> __('Container','britetechs-companion'),
                    	'container-fluid'=> __('Container Full','britetechs-companion')
                    	),
                )
            );

            // column layout
            $wp_customize->add_setting( 'jewelrystore_option[team_column]',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => $option['team_column'],
                    'transport'			=> 'postMessage',
                    'type' => 'option',
                )
            );
            $wp_customize->add_control( 'jewelrystore_option[team_column]',
                array(
                    'type'        => 'radio',
                    'label'       => esc_html__('Column Layout', 'britetechs-companion'),
                    'section'     => 'team_section',
                    'choices' => array(
                    	2 => __('2 Column','britetechs-companion'),
                    	3 => __('3 Column','britetechs-companion'),
                    	4 => __('4 Column','britetechs-companion'),
                    	),
                )
            );
}
add_action('customize_register','jewelry_store_customizer_team');