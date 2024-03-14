<?php

/**************************************************
**** Front Page Contents
***************************************************/

function Businesswp_frontpage_sections_settings( $wp_customize ){

	if ( class_exists( 'Businesswp_Customizer_Repeater' ) ) {

			$wp_customize->add_setting('businesswp_option[slider_content]', array(
				'default'           => businesswp_slider_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[slider_content]', array(
				'label'                             => esc_html__( 'Slider Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_slider_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new slide item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Slide Item', 'britetechs-companion' ),
				'customizer_repeater_image_control' => true,
				'customizer_repeater_icon_control' => false,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_subtitle_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				'customizer_repeater_content_align' => true,
				)
		    ) );

		    $wp_customize->add_setting('businesswp_option[service_content]',	array(
		    	'default'           => businesswp_service_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[service_content]', array(
				'label'                             => esc_html__( 'service Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_service_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new service item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Service Item', 'britetechs-companion' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => false,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => false,
				)
		    ) );

		    $wp_customize->add_setting('businesswp_option[portfolio_content]',	array(
		    	'default'           => businesswp_portfolio_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[portfolio_content]', array(
				'label'                             => esc_html__( 'Portfolio Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_portfolio_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new portfolio item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Portfolio Item', 'britetechs-companion' ),
				'customizer_repeater_icon_control' => false,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => false,
				'customizer_repeater_link_control'  => false,
				'customizer_repeater_checkbox_control' => false,
				)
		    ) );

		    $wp_customize->add_setting('businesswp_option[testimonial_content]', array(
		    	'default'           => businesswp_testimonial_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[testimonial_content]', array(
				'label'                             => esc_html__( 'Testimonial Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_testimonial_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new testimonial item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Testimonial Item', 'britetechs-companion' ),
				'customizer_repeater_icon_control' => false,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_designation_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => false,
				'customizer_repeater_link_control'  => false,
				'customizer_repeater_checkbox_control' => false,
				'customizer_repeater_repeater_control' => false,
				)
		    ) );

		    $wp_customize->add_setting('businesswp_option[team_content]', array(
		    	'default'           => businesswp_team_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[team_content]',array(
				'label'                             => esc_html__( 'Team Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_team_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new team item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Team Item', 'britetechs-companion' ),
				'customizer_repeater_icon_control' => false,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_designation_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => false,
				'customizer_repeater_link_control'  => false,
				'customizer_repeater_checkbox_control' => false,
				'customizer_repeater_repeater_control' => false,
				)
		    ) );

		    $wp_customize->add_setting('businesswp_option[contact_content]', array(
		    	'default'           => businesswp_contact_default_contents(),
				'sanitize_callback' => 'businesswp_customizer_repeater_sanitize',
				'type' => 'option',
			) );

		    $wp_customize->add_control( new Businesswp_Customizer_Repeater( $wp_customize,'businesswp_option[contact_content]', array(
				'label'                             => esc_html__( 'Contact Items Content', 'britetechs-companion' ),
				'section'                           => 'businesswp_contact_section',
				'priority' 							=> 100,
				'add_field_label'                   => esc_html__( 'Add new contact item', 'britetechs-companion' ),
				'item_name'                         => esc_html__( 'Contact Item', 'britetechs-companion' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				)
		    ) );

		    $sections = array(
				'service',
				'portfolio',
				'testimonial',
				'team',
				'contact',
			);

			foreach ($sections as $section) {
				$wp_customize->get_setting( 'businesswp_option['.$section.'_subtitle]' )->transport  = 'postMessage';
				$wp_customize->selective_refresh->add_partial(
					'businesswp_option['.$section.'_subtitle]',
					array(
						'selector'        => '.home_section.'.$section.' .section-subtitle',
						'render_callback' => array( 'Businesswp_Customizer_Partials', $section.'_subtitle' ),
					)
				);

				$wp_customize->get_setting( 'businesswp_option['.$section.'_title]' )->transport  = 'postMessage';
				$wp_customize->selective_refresh->add_partial(
					'businesswp_option['.$section.'_title]',
					array(
						'selector'        => '.home_section.'.$section.' .section-title',
						'render_callback' => array( 'Businesswp_Customizer_Partials', $section.'_title' ),
					)
				);

				$wp_customize->get_setting( 'businesswp_option['.$section.'_desc]' )->transport  = 'postMessage';
				$wp_customize->selective_refresh->add_partial(
					'businesswp_option['.$section.'_desc]',
					array(
						'selector'        => '.home_section.'.$section.' .section-description',
						'render_callback' => array( 'Businesswp_Customizer_Partials', $section.'_desc' ),
					)
				);
			}

			// blog
			$wp_customize->get_setting( 'businesswp_option[blog_subtitle]' )->transport  = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				'businesswp_option[blog_subtitle]',
				array(
					'selector'        => '.home_section.news .section-subtitle',
					'render_callback' => array( 'Businesswp_Customizer_Partials', 'blog_subtitle' ),
				)
			);

			$wp_customize->get_setting( 'businesswp_option[blog_title]' )->transport  = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				'businesswp_option[blog_title]',
				array(
					'selector'        => '.home_section.news .section-title',
					'render_callback' => array( 'Businesswp_Customizer_Partials', 'blog_title' ),
				)
			);

			$wp_customize->get_setting( 'businesswp_option[blog_desc]' )->transport  = 'postMessage';
			$wp_customize->selective_refresh->add_partial(
				'businesswp_option[blog_desc]',
				array(
					'selector'        => '.home_section.news .section-description',
					'render_callback' => array( 'Businesswp_Customizer_Partials', 'blog_desc' ),
				)
			);

		}

	}

add_action( 'customize_register', 'Businesswp_frontpage_sections_settings', 30 );