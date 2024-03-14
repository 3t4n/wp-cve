<?php

add_filter( 'hester_customizer_options', 'hester_customizer_slider_options' );
function hester_customizer_slider_options( array $options ) {

	$options['setting']['hester_enable_front_page'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'        => 'hester-toggle',
			'label'       => esc_html__( 'Enable front page sections', 'hester-core' ),
			'description' => esc_html__( 'Enable disable front page sections.', 'hester-core' ),
			'section'     => 'static_front_page',
		),
	);

	$options['setting']['hester_sections_order'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_order',
		'control'           => array(
			'type'    => 'hidden',
			'section' => 'static_front_page',
		),
	);

	// Slider section.
	$options['section']['hester_section_slider'] = array(
		'title'          => esc_html__( 'Slider', 'hester-core' ),
		'panel'          => 'hester_panel_homepage',
		'class'          => 'Hester_Customizer_Control_Section_Hiding',
		'hiding_control' => 'hester_enable_slider',
		'priority'       => (int) apply_filters( 'hester_section_priority', 0, 'hester_section_slider' ),
	);

	// Slider enable.
	$options['setting']['hester_enable_slider'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_toggle',
		'control'           => array(
			'type'        => 'hester-toggle',
			'label'       => esc_html__( 'EnableSlider', 'hester-core' ),
			'description' => esc_html__( 'Add a slider to your homepage.', 'hester-core' ),
			'section'     => 'hester_section_slider',
		),
	);

	$options['setting']['hester_slider_slides'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_repeater_sanitize',
		'control'           => array(
			'type'          => 'hester-repeater',
			'label'         => esc_html__( 'Slider', 'hester-core' ),
			'section'       => 'hester_section_slider',
			'item_name'     => esc_html__( 'Slide', 'hester-core' ),

			'live_title_id' => 'title', // apply for unput text and textarea only
			'title_format'  => esc_html__( '[live_title]', 'hester-core' ), // [live_title]
			'add_text'      => esc_html__( 'Add new slide', 'hester-core' ),
			'max_item'      => 3, // Maximum item can add,
			'limited_msg'   => wp_kses_post( __( 'Upgrade to <a target="_blank" href="https://peregrine-themes.com/hester/?utm_medium=customizer&utm_source=slider&utm_campaign=upgradeToPro">Hester Pro</a> to be able to add more items and unlock other premium features!', 'hester-core' ) ),
			'fields'        => array(
				'image'               => array(
					'title' => esc_html__( 'Slide image', 'hester-core' ),
					'type'  => 'media',
				),

				'background'          => array(
					'title'   => esc_html__( 'Slide background', 'hester-core' ),
					'type'    => 'design-options',
					'display' => array(
						'background' => array(
							'color'    => esc_html__( 'Solid Color', 'hester-core' ),
							'gradient' => esc_html__( 'Gradient', 'hester-core' ),
						),
					),
				),
				'accent_color'        => array(
					'title' => esc_html__( 'Slide Accent color', 'hester-core' ),
					'type'  => 'color',
				),
				'text_color'          => array(
					'title' => esc_html__( 'Slide Text color', 'hester-core' ),
					'type'  => 'color',
				),
				'title'               => array(
					'title' => esc_html__( 'Slide Title', 'hester-core' ),
					'type'  => 'text',
				),
				'subtitle'            => array(
					'title' => esc_html__( 'Slide Subtitle', 'hester-core' ),
					'type'  => 'text',
				),
				'text'                => array(
					'title' => esc_html__( 'Description', 'hester-core' ),
					'type'  => 'editor',
				),
				'btn_1_text'          => array(
					'title' => esc_html__( 'Button 1 text', 'hester-core' ),
					'type'  => 'text',
				),
				'btn_1_url'           => array(
					'title' => esc_html__( 'Button 1 URL', 'hester-core' ),
					'type'  => 'url',
				),
				'btn_1_class'         => array(
					'title'       => esc_html__( 'Button 1 Style', 'hester-core' ),
					'desc'        => esc_html__( 'Add predefined classes btn-primary, btn-secondary, btn-white. Add `btn-outline` class to outline the button', 'hester-core' ),
					'type'        => 'text',
				),
				'btn_2_text'          => array(
					'title' => esc_html__( 'Button 2 text', 'hester-core' ),
					'type'  => 'text',
				),
				'btn_2_url'           => array(
					'title' => esc_html__( 'Button 2 URL', 'hester-core' ),
					'type'  => 'url',
				),
				'btn_2_class'         => array(
					'title'       => esc_html__( 'Button 2 Style', 'hester-core' ),
					'desc'        => esc_html__( 'Add predefined classes btn-primary, btn-secondary, btn-white. Add `btn-outline` class to outline the button', 'hester-core' ),
					'type'        => 'text',
				),
				'alignment'           => array(
					'title'   => esc_html__( 'Align', 'hester-core' ),
					'type'    => 'button_set',
					'options' => array(
						'start'  => 'dashicons dashicons-editor-alignleft',
						'center' => 'dashicons dashicons-editor-aligncenter',
						'end'    => 'dashicons dashicons-editor-alignright',
					),
				),
				'side_content_source' => array(
					'title'    => esc_html__( 'Slide side content source', 'hester-core' ),
					'type'     => 'select',
					'desc'     => esc_html__( 'Choose what you want to display on left or right side of the slider. You can display either Image or put a shortcode.' ),
					'options'  => array(
						'image'     => esc_html__( 'Image' ),
						'url'       => esc_html__( 'Video URL' ),
						'shortcode' => esc_html__( 'Shortcode' ),
					),
					'required' => array(
						array(
							'alignment',
							'!=',
							'center',
						),
					),
				),

				'side_image'          => array(
					'title'    => esc_html__( 'Image', 'hester-core' ),
					'type'     => 'media',
					'required' => array(
						array(
							'side_content_source',
							'=',
							'image',
						),
						array(
							'alignment',
							'!=',
							'center',
						),

					),
				),

				'side_shortcode'      => array(
					'title'    => esc_html__( 'Shortcode', 'hester-core' ),
					'desc'     => esc_html__( 'Put plugin shortcode here.' ),
					'type'     => 'text',
					'required' => array(
						array(
							'side_content_source',
							'=',
							'shortcode',
						),
					),
				),

				'url'                 => array(
					'title'    => esc_html__( 'Video URL', 'hester-core' ),
					'desc'     => esc_html__( 'Youtube or vimeo video URL' ),
					'type'     => 'url',
					'required' => array(
						array(
							'side_content_source',
							'=',
							'url',
						),
					),
				),

				'open_in_popup'       => array(
					'title'    => esc_html__( 'Open content in popup', 'hester-core' ),
					'desc'     => esc_html__( 'Enable this to open Slider side content in a popup box.' ),
					'type'     => 'checkbox',
					'required' => array(
						array(
							'side_content_source',
							'!=',
							'shortcode',
						),
						array(
							'alignment',
							'!=',
							'center',
						),
					),
				),

				'popup_icon'          => array(
					'title'    => esc_html__( 'Choose a popup Icon', 'hester-core' ),
					'desc'     => esc_html__( 'Select an icon , clicking upon will open that popup.' ),
					'type'     => 'icon',
					'required' => array(
						array(
							'side_content_source',
							'!=',
							'shortcode',
						),
						array(
							'open_in_popup',
							'=',
							true,
						),
					),
				),

			),

			'required'      => array(
				array(
					'control'  => 'hester_section_slider',
					'value'    => true,
					'operator' => '==',
				),
			),
		),

	);

	$options['setting']['hester_slider_shape'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'        => 'hester-select',
			'label'       => esc_html__( 'Slider shape', 'hester-core' ),
			'description' => esc_html__( 'Choose a slider shape. Upgrade to PRO for more options.', 'hester-core' ),
			'section'     => 'hester_section_slider',
			'choices'     => array(
				''       => esc_html__( 'None', 'hester-core' ),
				'wave' => esc_html__( 'Wave', 'hester-core' ),
			),
		),
	);

	$options['setting']['hester_slider_style'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_select',
		'control'           => array(
			'type'     => 'hester-select',
			'label'    => esc_html__( 'Style', 'hester-core' ),
			'section'  => 'hester_section_slider',
			'choices'  => array(
				''  => esc_html__( '1 style', 'hester-core' ),
				'2' => esc_html__( '2 style', 'hester-core' ),
			),
			'required' => array(
				array(
					'control'  => 'hester_enable_slider',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_slider_height'] = array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'hester_sanitize_responsive',
		'control'           => array(
			'type'       => 'hester-range',
			'label'      => esc_html__( 'Slider Height', 'hester-core' ),
			'section'    => 'hester_section_slider',
			'priority'   => 100,
			'min'        => 8,
			'max'        => 30,
			'step'       => 1,
			'responsive' => true,
			'unit'       => array(
				array(
					'id'   => 'px',
					'name' => 'px',
					'min'  => 8,
					'max'  => 1080,
					'step' => 1,
				),
				array(
					'id'   => 'em',
					'name' => 'em',
					'min'  => 0.5,
					'max'  => 108,
					'step' => 0.01,
				),
				array(
					'id'   => 'rem',
					'name' => 'rem',
					'min'  => 0.5,
					'max'  => 108,
					'step' => 0.01,
				),
			),
			'required'   => array(
				array(
					'control'  => 'hester_enable_slider',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	$options['setting']['hester_slider_title_font'] = array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'hester_sanitize_typography',
		'control'           => array(
			'type'        => 'hester-typography',
			'label'       => esc_html__( 'Slider Title Typography', 'hester-core' ),
			'description' => esc_html__( 'Adds a separate font for styling of slide title, so you can create stylish typographic elements.', 'hester-core' ),
			'section'     => 'hester_section_slider',
			'display'     => array(
				'font-family'     => array(),
				'font-subsets'    => array(),
				'font-weight'     => array(),
				'font-style'      => array(),
				'text-transform'  => array(),
				'text-decoration' => array(),
				'letter-spacing'  => array(),
				'font-size'       => array(),
				'line-height'     => array(),
			),
			'required'    => array(
				array(
					'control'  => 'hester_enable_slider',
					'value'    => true,
					'operator' => '==',
				),
			),
		),
	);

	return $options;
}
