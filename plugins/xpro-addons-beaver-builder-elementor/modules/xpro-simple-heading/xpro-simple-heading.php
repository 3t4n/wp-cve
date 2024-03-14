<?php

/**
 * @class XPROIconBoxModule
 */
class XPROSimpleHeadingModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Heading', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-simple-heading/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-simple-heading/',
				'partial_refresh' => true,
			)
		);
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XPROSimpleHeadingModule',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'before_title' => array(
							'type'        => 'text',
							'label'       => __( 'Title Before', 'xpro-bb-addons' ),
							'default'     => __( 'Your', 'xpro-bb-addons' ),
							'placeholder' => __( 'Before Title', 'xpro-bb-addons' ),
						),
						'center_title' => array(
							'type'        => 'text',
							'label'       => __( 'Title Center', 'xpro-bb-addons' ),
							'default'     => __( 'Simple', 'xpro-bb-addons' ),
							'placeholder' => __( 'Center Title', 'xpro-bb-addons' ),
						),
						'after_title'  => array(
							'type'        => 'text',
							'label'       => __( 'Title After', 'xpro-bb-addons' ),
							'default'     => __( 'Heading', 'xpro-bb-addons' ),
							'placeholder' => __( 'After Title', 'xpro-bb-addons' ),
						),
						'title_tag'    => array(
							'type'    => 'select',
							'label'   => __( 'HTML Title Tag', 'xpro-bb-addons' ),
							'default' => 'h3',
							'options' => array(
								'h1' => __( 'H1', 'xpro-bb-addons' ),
								'h2' => __( 'H2', 'xpro-bb-addons' ),
								'h3' => __( 'H3', 'xpro-bb-addons' ),
								'h4' => __( 'H4', 'xpro-bb-addons' ),
								'h5' => __( 'H5', 'xpro-bb-addons' ),
								'h6' => __( 'H6', 'xpro-bb-addons' ),
							),
						),
						'box_link'     => array(
							'type'          => 'link',
							'label'         => __( 'Box Link', 'xpro-bb-addons' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'placeholder'   => __( 'http://example.com', 'xpro-bb-addons' ),
						),
						'alignment'    => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
						),
					),
				),
			),
		),
		'style'   => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'title'        => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_typography'      => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
							),
						),
						'title_color_type'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Color Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'title_color' ),
								),
								'gradient' => array(
									'fields' => array( 'title_gradient' ),
								),
							),
						),
						'title_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
								'property' => 'color',
							),
						),
						'title_gradient'        => array(
							'type'    => 'gradient',
							'label'   => 'Gradient Color',
							'preview' => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
								'property' => 'background-image',
							),
						),
						'title_stroke_txt_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Stroke Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'xpro-bb-addons' ),
								'stroke' => __( 'Stroke Text', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'stroke' => array(
									'fields' => array( 'title_stroke_width', 'title_stroke_color' ),
								),
							),
						),
						'title_stroke_width'    => array(
							'type'         => 'unit',
							'label'        => 'Width',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'slider'       => true,
							'default'      => 1,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
								'property' => '-webkit-text-stroke-width',
							),
						),
						'title_stroke_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Stroke Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
								'property' => '-webkit-text-stroke-color',
							),
						),
						'title_blend_mode_type' => array(
							'type'    => 'select',
							'label'   => __( 'Blend Mode', 'xpro-bb-addons' ),
							'default' => 'normal',
							'options' => array(
								'normal'      => __( 'Normal', 'xpro-bb-addons' ),
								'multiply'    => __( 'Multiply', 'xpro-bb-addons' ),
								'screen'      => __( 'Screen', 'xpro-bb-addons' ),
								'overlay'     => __( 'Overlay', 'xpro-bb-addons' ),
								'darken'      => __( 'Darken', 'xpro-bb-addons' ),
								'lighten'     => __( 'Lighten', 'xpro-bb-addons' ),
								'color-dodge' => __( 'Color Dodge', 'xpro-bb-addons' ),
								'saturation'  => __( 'Saturation', 'xpro-bb-addons' ),
								'color'       => __( 'Color', 'xpro-bb-addons' ),
								'difference'  => __( 'Difference', 'xpro-bb-addons' ),
								'exclusion'   => __( 'Exclusion', 'xpro-bb-addons' ),
								'hue'         => __( 'Hue', 'xpro-bb-addons' ),
								'luminosity'  => __( 'Luminosity', 'xpro-bb-addons' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.xpro-heading-title',
								'property' => 'mix-blend-mode',
							),
						),
					),
				),
				'center-title' => array(
					'title'     => __( 'Center Title', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'center_title_typography'      => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
							),
						),
						'center_title_color_type'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Color Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'center_title_color' ),
								),
								'gradient' => array(
									'fields' => array( 'center_title_gradient' ),
								),
							),
						),
						'center_title_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'color',
							),
						),
						'center_title_gradient'        => array(
							'type'       => 'gradient',
							'label'      => 'Gradient Color',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'background-image',
							),
						),
						'center_title_bg_type'         => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'color'    => __( 'Color', 'xpro-bb-addons' ),
								'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'center_title_bg_color' ),
								),
								'gradient' => array(
									'fields' => array( 'center_title_bg_gradient' ),
								),
							),
						),
						'center_title_bg_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'background-color',
							),
						),
						'center_title_bg_gradient'     => array(
							'type'       => 'gradient',
							'label'      => 'Gradient Color',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'background-image',
							),
						),
						'center_title_stroke_txt_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Stroke Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'   => __( 'None', 'xpro-bb-addons' ),
								'stroke' => __( 'Stroke Text', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'stroke' => array(
									'fields' => array( 'center_title_stroke_width', 'center_title_stroke_color' ),
								),
							),
						),
						'center_title_stroke_width'    => array(
							'type'         => 'unit',
							'label'        => 'Width',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'default'      => 1,
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => '-webkit-text-stroke-width',
							),
						),
						'center_title_stroke_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Stroke Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => '-webkit-text-stroke-color',
							),
						),
						'center_title_blend_mode_type' => array(
							'type'    => 'select',
							'label'   => __( 'Blend Mode', 'xpro-bb-addons' ),
							'default' => 'normal',
							'options' => array(
								'normal'      => __( 'Normal', 'xpro-bb-addons' ),
								'multiply'    => __( 'Multiply', 'xpro-bb-addons' ),
								'screen'      => __( 'Screen', 'xpro-bb-addons' ),
								'overlay'     => __( 'Overlay', 'xpro-bb-addons' ),
								'darken'      => __( 'Darken', 'xpro-bb-addons' ),
								'lighten'     => __( 'Lighten', 'xpro-bb-addons' ),
								'color-dodge' => __( 'Color Dodge', 'xpro-bb-addons' ),
								'saturation'  => __( 'Saturation', 'xpro-bb-addons' ),
								'color'       => __( 'Color', 'xpro-bb-addons' ),
								'difference'  => __( 'Difference', 'xpro-bb-addons' ),
								'exclusion'   => __( 'Exclusion', 'xpro-bb-addons' ),
								'hue'         => __( 'Hue', 'xpro-bb-addons' ),
								'luminosity'  => __( 'Luminosity', 'xpro-bb-addons' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'mix-blend-mode',
							),
						),
						'center_title_border'          => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
							),
						),
						'center_title_padding'         => array(
							'type'         => 'dimension',
							'label'        => __( 'Padding', 'xpro-bb-addons' ),
							'units'        => array( 'px', 'vw', '%' ),
							'slider'       => true,
							'responsive'   => true,
							'default_unit' => 'px',
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-title-focus',
								'property' => 'padding',
							),
						),
					),
				),
			),
		),
	)
);
