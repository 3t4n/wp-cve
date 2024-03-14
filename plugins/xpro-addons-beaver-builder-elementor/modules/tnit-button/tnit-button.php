<?php
/**
 * @class TNITCreativeButtonsModule
 */

class TNITCreativeButtonsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Button', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-button/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-button/',
				'partial_refresh' => true,
			)
		);
	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {

		// Register and enqueue your own.
		$this->add_css( 'xpro-animate', XPRO_ADDONS_FOR_BB_URL . 'assets/css/animate.css', '', '1.0.0' );

	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITCreativeButtonsModule',
	array(
		'general'    => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general'          => array(
					'title'  => 'General',
					'fields' => array(
						'button_text'   => array(
							'type'    => 'text',
							'label'   => __( 'Button Text', 'xpro-bb-addons' ),
							'default' => __( 'Click Here', 'xpro-bb-addons' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.tnit-creative-button-text',
							),
						),
						'button_link'   => array(
							'type'          => 'link',
							'label'         => 'Link',
							'show_target'   => true,
							'show_nofollow' => true,
						),
						'icon_show'     => array(
							'type'    => 'button-group',
							'label'   => __( 'Show Icon', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'xpro-bb-addons' ),
								'no'  => __( 'No', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields'   => array( 'icon', 'icon_position' ),
									'sections' => array( 'icon' ),
								),
								'no'  => array(
									'sections' => array(),
								),
							),
						),
						'icon'          => array(
							'type'        => 'icon',
							'label'       => __( 'Icons', 'xpro-bb-addons' ),
							'default'     => 'fa fa-caret-right',
							'show_remove' => true,
						),
						'icon_position' => array(
							'type'    => 'select',
							'label'   => __( 'Icon Position', 'xpro-bb-addons' ),
							'default' => 'after',
							'options' => array(
								'before'      => __( 'Before Text', 'xpro-bb-addons' ),
								'after'       => __( 'After Text', 'xpro-bb-addons' ),
								'outer_left'  => __( 'Outer Left', 'xpro-bb-addons' ),
								'outer_right' => __( 'Outer Right', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'outer_left'  => array(
									'fields' => array( 'icon_border_color' ),
								),
								'outer_right' => array(
									'fields' => array( 'icon_border_color' ),
								),
							),
						),

					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'hover_effect_style'    => array(
							'type'    => 'select',
							'label'   => __( 'Hover Effect', 'xpro-bb-addons' ),
							'default' => 'effect-1',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'effect-1' => __( 'Left-Right-In', 'xpro-bb-addons' ),
								'effect-2' => __( 'Top-Bottom-In', 'xpro-bb-addons' ),
								'effect-3' => __( 'Left-Right-Out', 'xpro-bb-addons' ),
								'effect-4' => __( 'Top-Bottom-Out', 'xpro-bb-addons' ),
							),
						),
						'cta_width'             => array(
							'type'    => 'button-group',
							'label'   => 'Width',
							'default' => 'auto',
							'options' => array(
								'auto'   => __( 'Auto', 'xpro-bb-addons' ),
								'full'   => __( 'Full Width', 'xpro-bb-addons' ),
								'custom' => __( 'Custom', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'auto'   => array(
									'fields' => array( 'btn_alignment' ),
								),
								'custom' => array(
									'fields' => array( 'cta_custom_width', 'cta_custom_height', 'btn_alignment' ),
								),
							),
						),
						'cta_custom_width'      => array(
							'type'       => 'unit',
							'label'      => 'Custom Width',
							'units'      => array( 'px' ),
							'default'    => '200',
							'slider'     => true,
							'responsive' => true,
						),
						'btn_alignment'         => array(
							'type'       => 'align',
							'label'      => __( 'Button Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
						),
						'btn_text_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_text_hover_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Text Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'color_type'            => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Color Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'btn_bg_color' ),
								),
								'gradient' => array(
									'fields' => array( 'btn_bg_gradient' ),
								),
							),
						),
						'btn_bg_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_bg_gradient'       => array(
							'type'    => 'gradient',
							'label'   => __( 'Background Gradient', 'xpro-bb-addons' ),
							'default' => array(
								'colors' => array(
									'0' => '83c4b1',
									'1' => '6d9a8d',
								),
							),
						),
						'hover_color_type'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Hover Color Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'btn_bg_hover_color' ),
								),
								'gradient' => array(
									'fields' => array( 'btn_hover_bg_gradient' ),
								),
							),
						),
						'btn_bg_hover_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_hover_bg_gradient' => array(
							'type'    => 'gradient',
							'label'   => __( 'Background Hover Gradient', 'xpro-bb-addons' ),
							'default' => array(
								'colors' => array(
									'0' => '6d9a8d',
									'1' => '83c4b1',
								),
							),
						),
						'border'                => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
						),
						'boder_hover_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'btn_padding'           => array(
							'type'        => 'dimension',
							'label'       => 'Padding',
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'placeholder' => array(
								'top'    => '20',
								'right'  => '50',
								'bottom' => '20',
								'left'   => '50',
							),
						),
					),
				),
				'icon'    => array(
					'title'     => __( 'Icon', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'icon_size'               => array(
							'type'         => 'unit',
							'label'        => __( 'Icon Size', 'xpro-bb-addons' ),
							'placeholder'  => '18',
							'default_unit' => 'px',
							'units'        => array( 'px' ),
							'slider'       => true,
							'responsive'   => true,
						),
						'icon_style'              => array(
							'type'    => 'select',
							'label'   => __( 'Background Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle Background', 'xpro-bb-addons' ),
								'square' => __( 'Square Background', 'xpro-bb-addons' ),
								'custom' => __( 'Design your own', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'simple' => array(
									'fields' => array( 'icon_border_color' ),
								),
								'circle' => array(
									'fields' => array( 'icon_bg_size', 'icon_bg_color', 'icon_border_color', 'icon_bg_hover_color' ),
								),
								'square' => array(
									'fields' => array( 'icon_bg_size', 'icon_bg_color', 'icon_border_color', 'icon_bg_hover_color' ),
								),
								'custom' => array(
									'fields' => array( 'icon_bg_size', 'icon_border_style', 'icon_bg_color', 'icon_bg_hover_color' ),
								),
							),
						),
						'icon_bg_size'            => array(
							'type'        => 'unit',
							'label'       => 'Background Size',
							'units'       => array( 'px' ),
							'help'        => __( 'Icon Box Size', 'xpro-bb-addons' ),
							'placeholder' => '30',
							'responsive'  => true,
							'slider'      => true,
						),
						'icon_color'              => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_hover_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_bg_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_bg_hover_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_border_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_border_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_border_style'       => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'structure' => array(
					'title'  => 'Typography',
					'fields' => array(
						'my_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
						),
					),
				),
			),
		),
	)
);
