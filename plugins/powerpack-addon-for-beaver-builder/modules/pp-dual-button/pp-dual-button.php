<?php

/**
 * @class PPDualButtonModule
 */
class PPDualButtonModule extends FLBuilderModule {

	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'          => __( 'Dual Button', 'bb-powerpack' ),
				'description'   => __( 'A module for Dual Button.', 'bb-powerpack' ),
				'group'         => pp_get_modules_group(),
				'category'      => pp_get_modules_cat( 'content' ),
				'dir'           => BB_POWERPACK_DIR . 'modules/pp-dual-button/',
				'url'           => BB_POWERPACK_URL . 'modules/pp-dual-button/',
				'editor_export' => true, // Defaults to true and can be omitted.
				'enabled'       => true, // Defaults to true and can be omitted.
			)
		);
	}

	public function enqueue_icon_styles() {
		$enqueue = false;
		$settings = $this->settings;

		if ( 'font_icon' === $settings->button_icon_select_1 && ! empty( $settings->button_font_icon_1 ) ) {
			$enqueue = true;
		}
		if ( 'font_icon' !== $settings->button_icon_select_2 && ! empty( $settings->button_font_icon_2 ) ) {
			$enqueue = true;
		}

		if ( $enqueue && is_callable( 'parent::enqueue_icon_styles' ) ) {
			parent::enqueue_icon_styles();
		}
	}

	public function filter_settings( $settings, $helper ) {
		// Handle old link field - Button 1.
		$settings = PP_Module_Fields::handle_link_field(
			$settings,
			array(
				'button_link_1'    => array(
					'type' => 'link',
				),
				'link_target_1'    => array(
					'type' => 'target',
				),
				'link_no_follow_1' => array(
					'type' => 'nofollow',
				),
			),
			'button_1_link'
		);

		// Handle old link field - Button 2.
		$settings = PP_Module_Fields::handle_link_field(
			$settings,
			array(
				'button_link_2'    => array(
					'type' => 'link',
				),
				'link_target_2'    => array(
					'type' => 'target',
				),
				'link_no_follow_2' => array(
					'type' => 'nofollow',
				),
			),
			'button_2_link'
		);

		// Handle old Background dual color field - Button 1.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_1_bg_color',
			array(
				'primary'   => 'button_1_bg_color_default',
				'secondary' => 'button_1_bg_color_hover',
			)
		);

		// Handle old Text dual color field - Button 1.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_1_text_color',
			array(
				'primary'   => 'button_1_text_color_default',
				'secondary' => 'button_1_text_color_hover',
			)
		);

		// Handle old border dual color field - Button 1.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_1_border_color',
			array(
				'primary'   => 'button_1_border_color_default',
				'secondary' => 'button_1_border_color_hover',
			)
		);

		// Handle old Background dual color field - Button 2.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_2_bg_color',
			array(
				'primary'   => 'button_2_bg_color_default',
				'secondary' => 'button_2_bg_color_hover',
			)
		);

		// Handle old Text dual color field - Button 2.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_2_text_color',
			array(
				'primary'   => 'button_2_text_color_default',
				'secondary' => 'button_2_text_color_hover',
			)
		);

		// Handle old border dual color field - Button 2.
		$settings = PP_Module_Fields::handle_dual_color_field(
			$settings,
			'button_2_border_color',
			array(
				'primary'   => 'button_2_border_color_default',
				'secondary' => 'button_2_border_color_hover',
			)
		);

		// Handle old button padding multitext field.
		$settings = PP_Module_Fields::handle_multitext_field(
			$settings,
			'button_padding',
			'padding',
			'button_padding',
			array(
				'top'    => 'button_top_padding',
				'bottom' => 'button_bottom_padding',
				'left'   => 'button_left_padding',
				'right'  => 'button_right_padding',
			)
		);

		// Handle old typography fields.
		$settings = PP_Module_Fields::handle_typography_field(
			$settings,
			array(
				'button_font_family'    => array(
					'type' => 'font',
				),
				'button_font_size'      => array(
					'type' => 'font_size',
					'keys' => array(
						'desktop' => 'button_font_size_desktop',
						'tablet'  => 'button_font_size_tablet',
						'mobile'  => 'button_font_size_mobile',
					),
				),
				'button_line_height'    => array(
					'type' => 'line_height',
					'keys' => array(
						'desktop' => 'button_line_height_desktop',
						'tablet'  => 'button_line_height_tablet',
						'mobile'  => 'button_line_height_mobile',
					),
				),
				'button_letter_spacing' => array(
					'type' => 'letter_spacing',
				),
			),
			'button_typography'
		);

		return $settings;
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'PPDualButtonModule',
	array(
		'button_1_tab'      => array( // Tab
			'title'    => __( 'Button 1', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'button_sections_1' => array(
					'title'  => '',
					'fields' => array(
						'button_1_title'           => array(
							'type'    => 'text',
							'label'   => 'Text',
							'default' => __( 'Button 1', 'bb-powerpack' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.pp-dual-button-1 span.pp-button-1-text',
							),
						),
						'button_1_link'            => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'bb-powerpack' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow' => true,
						),
						'button_icon_select_1'     => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Icon Type', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'        => __( 'None', 'bb-powerpack' ),
								'font_icon'   => __( 'Icon', 'bb-powerpack' ),
								'custom_icon' => __( 'Image', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'font_icon'   => array(
									'fields'   => array( 'button_font_icon_1', 'button_1_font_icon_size', 'button_1_font_icon_hover_color', 'button_1_icon_aligment' ),
									'sections' => array( 'icon_style' ),
								),
								'custom_icon' => array(
									'fields'   => array( 'button_custom_icon_1', 'button_1_custom_icon_width', 'button_1_icon_aligment' ),
									'sections' => array( 'icon_style' ),
								),
							),
						),
						'button_font_icon_1'       => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'bb-powerpack' ),
						),
						'button_custom_icon_1'     => array(
							'type'        => 'photo',
							'label'       => __( 'Custom Image', 'bb-powerpack' ),
							'connections' => array( 'photo' ),
						),
						'button_1_icon_aligment'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Icon Position', 'bb-powerpack' ),
							'default' => 'left',
							'options' => array(
								'left'  => __( 'Before Text', 'bb-powerpack' ),
								'right' => __( 'After Text', 'bb-powerpack' ),
							),
						),
						'button_1_effect'          => array(
							'type'    => 'select',
							'label'   => __( 'Hover Transition', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'                   => __( 'None', 'bb-powerpack' ),
								'fade'                   => __( 'Fade', 'bb-powerpack' ),
								'sweep_right'            => __( 'Sweep To Right', 'bb-powerpack' ),
								'sweep_left'             => __( 'Sweep To Left', 'bb-powerpack' ),
								'sweep_bottom'           => __( 'Sweep To Bottom', 'bb-powerpack' ),
								'sweep_top'              => __( 'Sweep To Top', 'bb-powerpack' ),
								'bounce_right'           => __( 'Bounce To Right', 'bb-powerpack' ),
								'bounce_left'            => __( 'Bounce To Left', 'bb-powerpack' ),
								'bounce_bottom'          => __( 'Bounce To Bottom', 'bb-powerpack' ),
								'bounce_top'             => __( 'Bounce To Top', 'bb-powerpack' ),
								'radial_out'             => __( 'Radial Out', 'bb-powerpack' ),
								'radial_in'              => __( 'Radial In', 'bb-powerpack' ),
								'rectangle_out'          => __( 'Rectangle Out', 'bb-powerpack' ),
								'rectangle_in'           => __( 'Rectangle In', 'bb-powerpack' ),
								'shutter_in_horizontal'  => __( 'Shutter In Horizontal', 'bb-powerpack' ),
								'shutter_out_horizontal' => __( 'Shutter Out Horizontal', 'bb-powerpack' ),
								'shutter_in_vertical'    => __( 'Shutter In Vertical', 'bb-powerpack' ),
								'shutter_out_vertical'   => __( 'Shutter Out Vertical', 'bb-powerpack' ),
								'shutter_out_diagonal'   => __( 'Shutter Out Diagonal', 'bb-powerpack' ),
								'shutter_in_diagonal'    => __( 'Shutter In Diagonal', 'bb-powerpack' ),
							),
						),
						'button_1_effect_duration' => array(
							'type'        => 'text',
							'label'       => __( 'Transition Speed', 'bb-powerpack' ),
							'size'        => 5,
							'maxlength'   => 4,
							'default'     => 200,
							'description' => 'ms',
						),
						'button_1_css_class'       => array(
							'type'    => 'text',
							'label'   => __( 'CSS Class', 'bb-powerpack' ),
							'default' => '',
						),
						'enable_title_attr_1'      => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Enable Title Attribute', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'title_attr_1' ),
								),
							),
						),
						'title_attr_1'             => array(
							'type'    => 'text',
							'label'   => __( 'Attribute Text', 'bb-powerpack' ),
							'default' => __( 'My Button 1', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'button_2_tab'      => array(
			'title'    => __( 'Button 2', 'bb-powerpack' ),
			'sections' => array(
				'button_sections_2' => array(
					'title'  => '',
					'fields' => array(
						'button_2_title'           => array(
							'type'    => 'text',
							'label'   => 'Text',
							'default' => __( 'Button 2', 'bb-powerpack' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.pp-dual-button-2 span.pp-button-2-text',
							),
						),
						'button_2_link'            => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'bb-powerpack' ),
							'connections'   => array( 'url' ),
							'show_target'   => true,
							'show_nofollow' => true,
						),
						'button_icon_select_2'     => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Icon Type', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'        => __( 'None', 'bb-powerpack' ),
								'font_icon'   => __( 'Icon', 'bb-powerpack' ),
								'custom_icon' => __( 'Image', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'font_icon'   => array(
									'fields'   => array( 'button_font_icon_2', 'button_2_font_icon_size', 'button_2_font_icon_hover_color', 'button_2_icon_aligment' ),
									'sections' => array( 'icon_style' ),
								),
								'custom_icon' => array(
									'fields'   => array( 'button_custom_icon_2', 'button_2_custom_icon_width', 'button_2_icon_aligment' ),
									'sections' => array( 'icon_style' ),
								),
							),
						),
						'button_font_icon_2'       => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'bb-powerpack' ),
						),
						'button_custom_icon_2'     => array(
							'type'        => 'photo',
							'label'       => __( 'Custom Image', 'bb-powerpack' ),
							'connections' => array( 'photo' ),
						),
						'button_2_icon_aligment'   => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Icon Position', 'bb-powerpack' ),
							'default' => 'left',
							'options' => array(
								'left'  => __( 'Before Text', 'bb-powerpack' ),
								'right' => __( 'After Text', 'bb-powerpack' ),
							),
						),
						'button_2_effect'          => array(
							'type'    => 'select',
							'label'   => __( 'Hover Transition', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'                   => __( 'None', 'bb-powerpack' ),
								'fade'                   => __( 'Fade', 'bb-powerpack' ),
								'sweep_right'            => __( 'Sweep To Right', 'bb-powerpack' ),
								'sweep_left'             => __( 'Sweep To Left', 'bb-powerpack' ),
								'sweep_bottom'           => __( 'Sweep To Bottom', 'bb-powerpack' ),
								'sweep_top'              => __( 'Sweep To Top', 'bb-powerpack' ),
								'bounce_right'           => __( 'Bounce To Right', 'bb-powerpack' ),
								'bounce_left'            => __( 'Bounce To Left', 'bb-powerpack' ),
								'bounce_bottom'          => __( 'Bounce To Bottom', 'bb-powerpack' ),
								'bounce_top'             => __( 'Bounce To Top', 'bb-powerpack' ),
								'radial_out'             => __( 'Radial Out', 'bb-powerpack' ),
								'radial_in'              => __( 'Radial In', 'bb-powerpack' ),
								'rectangle_out'          => __( 'Rectangle Out', 'bb-powerpack' ),
								'rectangle_in'           => __( 'Rectangle In', 'bb-powerpack' ),
								'shutter_in_horizontal'  => __( 'Shutter In Horizontal', 'bb-powerpack' ),
								'shutter_out_horizontal' => __( 'Shutter Out Horizontal', 'bb-powerpack' ),
								'shutter_in_vertical'    => __( 'Shutter In Vertical', 'bb-powerpack' ),
								'shutter_out_vertical'   => __( 'Shutter Out Vertical', 'bb-powerpack' ),
								'shutter_out_diagonal'   => __( 'Shutter Out Diagonal', 'bb-powerpack' ),
								'shutter_in_diagonal'    => __( 'Shutter In Diagonal', 'bb-powerpack' ),
							),
						),
						'button_2_effect_duration' => array(
							'type'        => 'text',
							'label'       => __( 'Transition Speed', 'bb-powerpack' ),
							'size'        => 5,
							'maxlength'   => 4,
							'default'     => '200',
							'description' => 'ms',
						),
						'button_2_css_class'       => array(
							'type'    => 'text',
							'label'   => __( 'CSS Class', 'bb-powerpack' ),
							'default' => '',
						),
						'enable_title_attr_2'      => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Enable Title Attribute', 'bb-powerpack' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-powerpack' ),
								'no'  => __( 'No', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'title_attr_1' ),
								),
							),
						),
						'title_attr_2'             => array(
							'type'    => 'text',
							'label'   => __( 'Attribute Text', 'bb-powerpack' ),
							'default' => __( 'My Button 2', 'bb-powerpack' ),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'style'             => array( // Tab
			'title'    => __( 'Style', 'bb-powerpack' ), // Tab title
			'sections' => array( // Tab Sections
				'structure'      => array( // Section
					'title'  => __( 'Structure', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_alignment'            => array(
							'type'    => 'align',
							'label'   => __( 'Alignment', 'bb-powerpack' ),
							'default' => 'left',
							'responsive' => true,
						),
						'button_padding'              => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'bb-powerpack' ),
							'default'    => '10',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content a.pp-button',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'button_border_style'         => array(
							'type'    => 'pp-switch',
							'label'   => __( 'Border Style', 'bb-powerpack' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'bb-powerpack' ),
								'solid'  => __( 'Solid', 'bb-powerpack' ),
								'dashed' => __( 'Dashed', 'bb-powerpack' ),
								'dotted' => __( 'Dotted', 'bb-powerpack' ),
							),
							'toggle'  => array(
								'solid'  => array(
									'fields' => array( 'button_border_width', 'button_1_border_color_default', 'button_1_border_color_hover', 'button_2_border_color_default', 'button_2_border_color_hover' ),
								),
								'dashed' => array(
									'fields' => array( 'button_border_width', 'button_1_border_color_default', 'button_1_border_color_hover', 'button_2_border_color_default', 'button_2_border_color_hover' ),
								),
								'dotted' => array(
									'fields' => array( 'button_border_width', 'button_1_border_color_default', 'button_1_border_color_hover', 'button_2_border_color_default', 'button_2_border_color_hover' ),
								),
							),
						),
						'button_border_width'         => array(
							'type'    => 'unit',
							'label'   => __( 'Border Width', 'bb-powerpack' ),
							'default' => '1',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-button',
								'property' => 'border-width',
								'unit'     => 'px',
							),
						),
						'button_border_radius'        => array(
							'type'    => 'unit',
							'label'   => __( 'Round Corners', 'bb-powerpack' ),
							'default' => '0',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.pp-dual-button-1 .pp-button',
										'property' => 'border-top-left-radius',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-dual-button-1 .pp-button',
										'property' => 'border-bottom-left-radius',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-dual-button-2 .pp-button',
										'property' => 'border-top-right-radius',
										'unit'     => 'px',
									),
									array(
										'selector' => '.pp-dual-button-2 .pp-button',
										'property' => 'border-bottom-right-radius',
										'unit'     => 'px',
									),
								),
							),
						),
						'button_width'                => array(
							'type'    => 'unit',
							'label'   => __( 'Width', 'bb-powerpack' ),
							'default' => '200',
							'units'   => array( 'px' ),
							'responsive'   => true,
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-button',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
						'button_spacing'              => array(
							'type'    => 'unit',
							'label'   => __( 'Spacing', 'bb-powerpack' ),
							'default' => '10',
							'units'   => array( 'px' ),
							'slider'  => true,
							'responsive'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-spacer',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
						'responsive_breakpoint'       => array(
							'type'    => 'unit',
							'label'   => __( 'Stack Buttons on Breakpoint', 'bb-powerpack' ),
							'default' => '480',
							'units'   => array( 'px' ),
							'help'    => __( 'Buttons will be stacked on top of each other.', 'bb-powerpack' ),
						),
					),
				),
				'button_1_style' => array( // Section
					'title'  => __( 'Button 1', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_1_bg_color_default'     => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => 'eeeeee',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-1 a',
								'property' => 'background-color',
							),
						),
						'button_1_text_color_default'   => array(
							'type'        => 'color',
							'label'       => __( 'Text Color', 'bb-powerpack' ),
							'default'     => '565656',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-1 a',
								'property' => 'color',
							),
						),
						'button_1_bg_color_hover'       => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
							'default'     => 'dddddd',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_1_text_color_hover'     => array(
							'type'        => 'color',
							'label'       => __( 'Text Hover Color', 'bb-powerpack' ),
							'default'     => '565656',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_1_border_color_default' => array(
							'type'        => 'color',
							'label'       => __( 'Border Color', 'bb-powerpack' ),
							'default'     => '333333',
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-1 .pp-button',
								'property' => 'border-color',
							),
						),
						'button_1_border_color_hover'   => array(
							'type'        => 'color',
							'label'       => __( 'Border Hover Color', 'bb-powerpack' ),
							'default'     => 'c6c6c6',
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_1_font_icon_size'       => array(
							'type'    => 'unit',
							'label'   => __( 'Icon Size', 'bb-powerpack' ),
							'default' => '20',
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-1 .pp-font-icon',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'button_1_custom_icon_width'    => array(
							'type'    => 'unit',
							'label'   => __( 'Image Width', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => '20',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-1 .pp-custom-icon',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
					),
				),
				'button_2_style' => array( // Section
					'title'  => __( 'Button 2', 'bb-powerpack' ), // Section Title
					'fields' => array( // Section Fields
						'button_2_bg_color_default'     => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'bb-powerpack' ),
							'default'     => 'dddddd',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-2 a',
								'property' => 'background-color',
							),
						),
						'button_2_text_color_default'   => array(
							'type'        => 'color',
							'label'       => __( 'Text Color', 'bb-powerpack' ),
							'default'     => '565656',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-2 a',
								'property' => 'color',
							),
						),
						'button_2_bg_color_hover'       => array(
							'type'        => 'color',
							'label'       => __( 'Background Hover Color', 'bb-powerpack' ),
							'default'     => 'eeeeee',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_2_text_color_hover'     => array(
							'type'        => 'color',
							'label'       => __( 'Text Hover Color', 'bb-powerpack' ),
							'default'     => '565656',
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_2_border_color_default' => array(
							'type'        => 'color',
							'label'       => __( 'Border Color', 'bb-powerpack' ),
							'default'     => '333333',
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-2 .pp-button',
								'property' => 'border-color',
							),
						),
						'button_2_border_color_hover'   => array(
							'type'        => 'color',
							'label'       => __( 'Border Hover Color', 'bb-powerpack' ),
							'default'     => 'c6c6c6',
							'show_alpha'  => true,
							'show_reset'  => true,
							'connections' => array( 'color' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'button_2_font_icon_size'       => array(
							'type'    => 'unit',
							'label'   => __( 'Icon Size', 'bb-powerpack' ),
							'default' => 20,
							'units'   => array( 'px' ),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-2 .pp-font-icon',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'button_2_custom_icon_width'    => array(
							'type'    => 'unit',
							'label'   => __( 'Image Width', 'bb-powerpack' ),
							'units'   => array( 'px' ),
							'slider'  => true,
							'default' => 20,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.pp-dual-button-content .pp-dual-button-2 .pp-custom-icon',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'button_typography' => array(
			'title'    => __( 'Typography', 'bb-powerpack' ),
			'sections' => array(
				'typography' => array(
					'title'  => '',
					'fields' => array(
						'button_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'bb-powerpack' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'font',
								'selector' => '.pp-dual-button-content .pp-button',
							),
						),
					),
				),
			),
		),
	)
);
