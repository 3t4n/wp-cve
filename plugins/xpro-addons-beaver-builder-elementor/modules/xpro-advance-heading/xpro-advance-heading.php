<?php

/**
 * @class XPROAdvanceHeading
 *
 */

if ( ! class_exists( 'XPROAdvanceHeading' ) ) {

	class XPROAdvanceHeading extends FLBuilderModule {

		/**
		 * @method __construct
		 *
		 */
		public function __construct()
		{
			parent::__construct(array(
				'name'            => __( 'Advance Heading', 'xpro-bb-addons' ),
				'description' 	  => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-advance-heading',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-advance-heading/',
				'partial_refresh' => true,
			));
	
		}
	
	}
	
	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module('XPROAdvanceHeading', array(
		'general'       => array(
			'title'         => __('General', 'xpro-bb-addons'),
			'sections'      => array(
				'title'       => array(
					'title'         => __('Title', 'xpro-bb-addons'),
					'fields'        => array(
						'adv_title_before' => array(
							'type'          => 'text',
							'label'         => __( 'Title Before', 'xpro-bb-addons' ),
							'placeholder'   => __( 'Title Before', 'xpro-bb-addons' ),
							'default'   => __( 'Your', 'xpro-bb-addons' ),
						),
						'adv_title_center' => array(
							'type'          => 'text',
							'label'         => __( 'Title Center', 'xpro-bb-addons' ),
							'placeholder'   => __( 'Title Center', 'xpro-bb-addons' ),
							'default'   => __( 'Main', 'xpro-bb-addons' ),
						),
						'adv_title_after' => array(
							'type'          => 'text',
							'label'         => __( 'Title After', 'xpro-bb-addons' ),
							'placeholder'   => __( 'Title After', 'xpro-bb-addons' ),
							'default'   => __( 'Heading', 'xpro-bb-addons' ),
						),
						'adv_box_link' => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'xpro-bb-addons' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'placeholder'   => __( 'http://example.com', 'xpro-bb-addons' ),
						),
						'adv_title_tag' => array(
							'type'          => 'select',
							'label'         => __( 'HTML Title Tag', 'xpro-bb-addons' ),
							'default'       => 'h2',
							'options'       => array(
								'h1'      => __( 'H1', 'xpro-bb-addons' ),
								'h2'      => __( 'H2', 'xpro-bb-addons' ),
								'h3'      => __( 'H3', 'xpro-bb-addons' ),
								'h4'      => __( 'H4', 'xpro-bb-addons' ),
								'h5'      => __( 'H5', 'xpro-bb-addons' ),
								'h6'      => __( 'H6', 'xpro-bb-addons' ),
							),
						),
                        'adv_title_separator_toggle' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Separator', 'xpro-bb-addons' ),
                            'default'       => 'enable',
                            'options'       => array(
                                'enable'      => __( 'Enable', 'xpro-bb-addons' ),
                                'disable'      => __( 'Disable', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                'enable'      => array(
                                    'sections'        => array( 'adv_separator' ),
                                ),
                            )
                        ),
					)
				),
				'adv_subtitle'       => array(
					'title'         => __('SubTitle', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_enable_sub_title' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Enable SubTitle', 'xpro-bb-addons' ),
                            'default'       => '1',
                            'options'       => array(
                                '0'      => __( 'Hide', 'xpro-bb-addons' ),
                                '1'      => __( 'Show', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                '1'      => array(
                                    'fields'        => array( 'adv_subtitle_title','adv_subtitle_title_tag','adv_subtitle_position'),
                                ),
                            )
                        ),
						'adv_subtitle_title' => array(
							'type'          => 'text',
							'label'         => __( 'Title', 'xpro-bb-addons' ),
							'placeholder'   => __( 'Sub Heading Here', 'xpro-bb-addons' ),
							'default'   => __( 'Sub Heading Here', 'xpro-bb-addons' ),
						),
						'adv_subtitle_title_tag' => array(
							'type'          => 'select',
							'label'         => __( 'HTML Title Tag', 'xpro-bb-addons' ),
							'default'       => 'h3',
							'options'       => array(
								'h1'      => __( 'H1', 'xpro-bb-addons' ),
								'h2'      => __( 'H2', 'xpro-bb-addons' ),
								'h3'      => __( 'H3', 'xpro-bb-addons' ),
								'h4'      => __( 'H4', 'xpro-bb-addons' ),
								'h5'      => __( 'H5', 'xpro-bb-addons' ),
								'h6'      => __( 'H6', 'xpro-bb-addons' ),
							),
						),
						'adv_subtitle_position' => array(
							'type'          => 'button-group',
							'label'         => __( 'Position', 'xpro-bb-addons' ),
							'default'       => 'before-title',
							'options'       => array(
								'before-title'      => __( 'Before Title', 'xpro-bb-addons' ),
								'after-title'      => __( 'After Title', 'xpro-bb-addons' ),
							),
						),
					)
				),
				'adv_description'       => array(
					'title'         => __('Description', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_enable_general_desc' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Enable Description', 'xpro-bb-addons' ),
                            'default'       => '1',
                            'options'       => array(
                                '0'      => __( 'Hide', 'xpro-bb-addons' ),
                                '1'      => __( 'Show', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                '1'      => array(
                                    'fields'        => array( 'adv_general_description'),
                                ),
                            )
                        ),
						'adv_general_description' => array(
							'type'          => 'editor',
							'label'         => __('', 'xpro-bb-addons'),
							'default'       => __( 'It is a long established fact that a reader will be distracted by the
						readable content of a page when looking at its layout normal distribution of letters.', 'xpro-bb-addons' ),
                            'wpautop'       => false,
                            'media_buttons' => false,
                            'connections' => array( 'string', 'html' ),
						),
					)
				),
				'adv_shadow'       => array(
					'title'         => __('Shadow', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_enable_shadow' => array(
                            'type'          => 'button-group',
                            'label'         => __( 'Enable Shadow', 'xpro-bb-addons' ),
                            'default'       => '1',
                            'options'       => array(
                                '0'      => __( 'Hide', 'xpro-bb-addons' ),
                                '1'      => __( 'Show', 'xpro-bb-addons' ),
                            ),
                            'toggle'        => array(
                                '1'      => array(
                                    'fields'        => array( 'adv_shadow_title', 'adv_shadow_title_tag'),
                                ),
                            )
                        ),
						'adv_shadow_title' => array(
							'type'          => 'text',
							'label'         => __( 'Title', 'xpro-bb-addons' ),
							'placeholder'   => __( 'Shadow Text Here', 'xpro-bb-addons' ),
							'default'   => __( 'Shadow Text Here', 'xpro-bb-addons' ),
						),
						'adv_shadow_title_tag' => array(
							'type'          => 'select',
							'label'         => __( 'HTML Title Tag', 'xpro-bb-addons' ),
							'default'       => 'h4',
							'options'       => array(
								'h1'      => __( 'H1', 'xpro-bb-addons' ),
								'h2'      => __( 'H2', 'xpro-bb-addons' ),
								'h3'      => __( 'H3', 'xpro-bb-addons' ),
								'h4'      => __( 'H4', 'xpro-bb-addons' ),
								'h5'      => __( 'H5', 'xpro-bb-addons' ),
								'h6'      => __( 'H6', 'xpro-bb-addons' ),
							),
						),
					)
				),
			)
		),
		'adv_style'       => array(
			'title'         => __('Style', 'xpro-bb-addons'),
			'sections'      => array(
				'general'       => array(
					'title'         => __('General', 'xpro-bb-addons'),
					'fields'        => array(
						'adv_general_alignment' => array(
							'type'    => 'align',
							'label'   => __( 'Alignment', 'xpro-bb-addons' ),
							'responsive'  => true,
                            'preview' => array(
                                'type'       => 'css',
                                'selector'   => '{node} .xpro-heading-wrapper',
                                'property'   => 'text-align',
                            ),
						),
						'adv_vertical_alignment' => array(
							'type'          => 'button-group',
							'label'         => __( 'Vertical Align', 'xpro-bb-addons' ),
							'default'       => 'flex-start',
							'options'       => array(
								'flex-start'      => __( 'Top', 'xpro-bb-addons' ),
								'center'      => __( 'Center', 'xpro-bb-addons' ),
								'flex-end'      => __( 'Bottom', 'xpro-bb-addons' )
							),
							'preview' => array(
								'type'       => 'css',
								'selector'   => '{node} .xpro-heading-wrapper-inner, {node} .xpro-heading-wrapper .xpro-heading-top',
								'property'   => 'align-items',
							),
						),
						'adv_general_custom_width' => array(
							'type'         => 'unit',
							'label'        => __( 'Max Width', 'xpro-bb-addons' ),
							'responsive' => 'true',
							'units'          => array( 'px', 'vw', '%' ),
							'default_unit' => 'px',
							'slider' => array(
								'px'    => array(
									'min' =>0,
									'max' => 1000,
									'step'    => 1,
								),
								'%'    => array(
									'min' => 0,
									'max' => 1000,
									'step'    => 1,
								),
								'vw'    => array(
									'min' => 0,
									'max' => 100,
									'step'    => 1,
								),
							),
							'preview'    => array(
								'type'          => 'css',
								'selector'      => '{node} .xpro-heading-wrapper-inner',
								'property'      => 'max-width',
							),
						),
					)
				),
				'adv_title'       => array(
					'title'         => __('Title', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_title_typography' => array(
                            'type'       => 'typography',
                            'label'      =>  __( 'Typography', 'xpro-bb-addons' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-title',
                            ),
                        ),
						'adv_title_color_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Color Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
								'photo'      => __( 'Photo', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_title_color' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_title_gradient' ),
								),
								'photo'      => array(
									'fields'        => array( 'adv_title_image_masking', 'adv_title_image_position' ),
								),
							)
						),
						'adv_title_color' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'color',
							),
						),
						'adv_title_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'default'    => array(
								'colors'    => array(
									'0'            => '83c4b1',
									'1'            => '6d9a8d',
								),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-image',
							),
						),
						'adv_title_image_masking' => array(
							'type'          => 'photo',
							'label'         => ' ',
							'show_remove'   => false,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-image',
							),
						),
						'adv_title_image_position' => array(
							'type'          => 'select',
							'label'         => __( 'Photo Position', 'xpro-bb-addons' ),
							'default'       => 'center center',
							'options'       => array(
								'center center'      => __( 'Center Center', 'xpro-bb-addons' ),
								'center left'      => __( 'Center Left', 'xpro-bb-addons' ),
								'center right'      => __( 'Center Right', 'xpro-bb-addons' ),
								'top center'      => __( 'Top Center', 'xpro-bb-addons' ),
								'top left'      => __( 'Top Left', 'xpro-bb-addons' ),
								'top right'      => __( 'Top Right', 'xpro-bb-addons' ),
								'bottom center'      => __( 'Bottom Center', 'xpro-bb-addons' ),
								'bottom left'      => __( 'Bottom Left', 'xpro-bb-addons' ),
								'bottom right'      => __( 'Bottom Right', 'xpro-bb-addons' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-position'
							),
						),
						'adv_title_background_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Background Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_title_background' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_title_background_gradient' ),
								),
							)
						),
						'adv_title_background' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-color',
							),
						),
						'adv_title_background_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-image',
							),
						),
						'adv_title_border' => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
							),
						),
						'adv_title_padding' => array(
							'type'        => 'dimension',
							'label'       =>  __( 'Padding', 'xpro-bb-addons' ),
							'units'          => array( 'px', 'vw', '%' ),
							'slider'  => true,
                            'responsive'  => true,
							'default_unit' => 'px',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'padding',
							),
						),
						'adv_title_margin' => array(
							'type'        => 'dimension',
							'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
							'units'          => array( 'px', 'vw', '%' ),
							'slider'  => true,
							'responsive'  => true,
							'default_unit' => 'px',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'margin',
							),
						),
					)
				),
				'adv_center_title'       => array(
					'title'         => __('Center Title', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_center_title_typography' => array(
                            'type'       => 'typography',
                            'label'      =>  __( 'Typography', 'xpro-bb-addons' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '{node} .xpro-heading-wrapper .xpro-title-focus',
                            ),
                        ),
						'adv_center_title_color_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Color Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
								'photo'      => __( 'Photo', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_center_title_color' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_center_title_gradient' ),
								),
								'photo'      => array(
									'fields'        => array( 'adv_center_title_image_masking', 'adv_center_title_image_position' ),
								),
							)
						),
						'adv_center_title_color' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'color',
							),
						),
						'adv_center_title_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'default'    => array(
								'colors'    => array(
									'0'            => '83c4b1',
									'1'            => '6d9a8d',
								),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-title',
								'property' => 'background-image',
							),
						),
						'adv_center_title_image_masking' => array(
							'type'          => 'photo',
							'label'         => __('Photo', 'xpro-bb-addons'),
							'show_remove'   => false,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'background-image',
							),
						),
						'adv_center_title_image_position' => array(
							'type'          => 'select',
							'label'         => __( 'Photo Position', 'xpro-bb-addons' ),
							'default'       => 'center center',
							'options'       => array(
								'center center'      => __( 'Center Center', 'xpro-bb-addons' ),
								'center left'      => __( 'Center Left', 'xpro-bb-addons' ),
								'center right'      => __( 'Center Right', 'xpro-bb-addons' ),
								'top center'      => __( 'Top Center', 'xpro-bb-addons' ),
								'top left'      => __( 'Top Left', 'xpro-bb-addons' ),
								'top right'      => __( 'Top Right', 'xpro-bb-addons' ),
								'bottom center'      => __( 'Bottom Center', 'xpro-bb-addons' ),
								'bottom left'      => __( 'Bottom Left', 'xpro-bb-addons' ),
								'bottom right'      => __( 'Bottom Right', 'xpro-bb-addons' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'background-position'
							),
						),
						'adv_center_title_background_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Background Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_center_title_background' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_center_title_background_gradient' ),
								),
							)
						),
						'adv_center_title_background' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'background-color',
							),
						),
						'adv_center_title_background_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'background-image',
							),
						),
						'adv_center_title_border' => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
							),
						),
						'adv_center_title_padding' => array(
							'type'        => 'dimension',
							'label'       =>  __( 'Padding', 'xpro-bb-addons' ),
							'units'          => array( 'px', 'vw', '%' ),
							'slider'  => true,
                            'responsive'  => true,
							'default_unit' => 'px',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-title-focus',
								'property' => 'padding',
							),
						),
					)
				),
				'adv_subtitle'       => array(
					'title'         => __('SubTitle', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_subtitle_typography' => array(
                            'type'       => 'typography',
                            'label'      =>  __( 'Typography', 'xpro-bb-addons' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
                            ),
                        ),
						'adv_subtitle_color_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Color Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
								'photo'      => __( 'Photo', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_subtitle_color' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_subtitle_gradient' ),
								),
								'photo'      => array(
									'fields'        => array( 'adv_subtitle_image_masking', 'adv_subtitle_image_position' ),
								),
							)
						),
						'adv_subtitle_color' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'color',
							),
						),
						'adv_subtitle_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'default'    => array(
								'colors'    => array(
									'0'            => '83c4b1',
									'1'            => '6d9a8d',
								),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'background-image',
							),
						),
						'adv_subtitle_image_masking' => array(
							'type'          => 'photo',
							'label'         => ' ',
							'show_remove'   => false,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'background-image',
							),
						),
						'adv_subtitle_image_position' => array(
							'type'          => 'select',
							'label'         => __( 'Photo Position', 'xpro-bb-addons' ),
							'default'       => 'center-center',
							'options'       => array(
								'center-center'      => __( 'Center Center', 'xpro-bb-addons' ),
								'center-left'      => __( 'Center Left', 'xpro-bb-addons' ),
								'center-right'      => __( 'Center Right', 'xpro-bb-addons' ),
								'top-center'      => __( 'Top Center', 'xpro-bb-addons' ),
								'top-left'      => __( 'Top Left', 'xpro-bb-addons' ),
								'top-right'      => __( 'Top Right', 'xpro-bb-addons' ),
								'bottom-center'     => __( 'Bottom Center', 'xpro-bb-addons' ),
								'bottom-left'      => __( 'Bottom Left', 'xpro-bb-addons' ),
								'bottom-right'      => __( 'Bottom Right', 'xpro-bb-addons' )
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'background-position',
							),
						),
						'adv_subtitle_background_type' => array(
							'type'          => 'button-group',
							'label'         => __( 'Background Type', 'xpro-bb-addons' ),
							'default'       => 'color',
							'options'       => array(
								'color'      => __( 'Color', 'xpro-bb-addons' ),
								'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
							),
							'toggle'        => array(
								'color'      => array(
									'fields'        => array( 'adv_subtitle_background' ),
								),
								'gradient'      => array(
									'fields'        => array( 'adv_subtitle_background_gradient' ),
								),
							)
						),
						'adv_subtitle_background' => array(
							'type'          => 'color',
							'label'         => ' ',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'background-color',
							),
						),
						'adv_subtitle_background_gradient' => array(
							'type'    => 'gradient',
							'label'   => ' ',
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'background-image',
							),
						),
						'adv_subtitle_border' => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
							),
						),
                        'adv_subtitle_padding' => array(
                            'type'        => 'dimension',
                            'label'       =>  __( 'Padding', 'xpro-bb-addons' ),
                            'units'          => array( 'px', 'vw', '%' ),
                            'slider'  => true,
                            'responsive'  => true,
                            'default_unit' => 'px',
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
                                'property' => 'padding',
                            ),
                        ),
                        'adv_subtitle_margin' => array(
							'type'        => 'dimension',
							'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
							'units'          => array( 'px', 'vw', '%' ),
							'slider'  => true,
                            'responsive'  => true,
							'default_unit' => 'px',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-subtitle',
								'property' => 'margin',
							),
						),
					)
				),
				'adv_description'       => array(
					'title'         => __('Description', 'xpro-bb-addons'),
					'collapsed'     => true,
					'fields'        => array(
                        'adv_description_typography' => array(
                            'type'       => 'typography',
                            'label'      => 'Typography',
                            'responsive' => true,
                            'preview'    => array(
                                'type'      => 'css',
                                'selector'  => '{node} .xpro-heading-wrapper .xpro-heading-description',
                            ),
                        ),
						'adv_description_color' => array(
							'type'          => 'color',
							'label'         => __( 'Color', 'xpro-bb-addons' ),
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-description',
								'property' => 'color',
							),
						),
						'adv_description_margin' => array(
							'type'        => 'dimension',
							'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
							'units'          => array( 'px', 'vw', '%' ),
							'slider'  => true,
                            'responsive'  => true,
							'default_unit' => 'px',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .xpro-heading-wrapper .xpro-heading-description',
								'property' => 'margin',
							),
						),
					)
				),
                'adv_separator'       => array(
                    'title'         => __('Separator', 'xpro-bb-addons'),
                    'collapsed'     => true,
                    'fields'        => array(
                        'adv_separator_styles' => array(
                            'type'         => 'form',
                            'label'        => __( 'Separator Text', 'xpro-bb-addons' ),
                            'form'         => 'xpro_adv_heading_separator_form',
                            'preview_text' => 'Style Separator Text',
                        ),
                    )
                ),
                'adv_shadow'       => array(
                    'title'     => __( 'Shadow Text', 'xpro-bb-addons' ),
                    'collapsed' => true,
                    'fields'    => array(
                        'shadow_styles' => array(
                            'type'         => 'form',
                            'label'        => __( 'Shadow Text', 'xpro-bb-addons' ),
                            'form'         => 'xpro_adv_heading_shadow_form',
                            'preview_text' => 'Style Shadow Text',
                        ),
                    ),
                ),
			)
		),
	));

    /**
     * Register a settings form to use in the "form" field type above.
     */
    FLBuilder::register_settings_form(
        'xpro_adv_heading_separator_form',
        array(
            'title' => __( 'Separator Text', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Separator Text', 'xpro-bb-addons' ),
                    'sections' => array(
                        'adv_separator_settings'       => array(
                            'title'         => __('Separator', 'xpro-bb-addons'),
                            'fields'        => array(
                                'adv_separator_style' => array(
                                    'type'          => 'select',
                                    'label'         => __( 'Style', 'xpro-bb-addons' ),
                                    'default'       => 'text',
                                    'options'       => array(
                                        'text'      => __( 'Text', 'xpro-bb-addons' ),
                                        'icon'      => __( 'Icon', 'xpro-bb-addons' ),
                                        'simple'      => __( 'Simple', 'xpro-bb-addons' ),
                                        'double'      => __( 'Double', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'text'      => array(
                                            'fields'        => array( 'adv_separator_title', 'adv_separator_text_typography',
                                                'adv_separator_text_color', 'adv_separator_after_before_color', 'adv_separator_width',
                                                'adv_separator_height', 'adv_separator_border_radius', 'adv_separator_margin'),
                                        ),
                                        'icon'      => array(
                                            'fields'        => array( 'adv_separator_icon', 'adv_separator_icon_size', 'adv_separator_icon_background_size',
                                                'adv_separator_icon_color', 'adv_separator_icon_background_color', 'adv_separator_icon_border',
                                                'adv_separator_after_before_color', 'adv_separator_width', 'adv_separator_height', 'separator_border',
                                                'adv_separator_border_radius', 'adv_separator_margin'),
                                        ),
                                        'simple'      => array(
                                            'fields'        => array( 'adv_separator_width', 'adv_separator_height', 'adv_separator_after_before_color',
                                                'adv_separator_border_radius', 'adv_separator_margin'),
                                        ),
                                        'double'      => array(
                                            'fields'        => array( 'adv_separator_width', 'adv_separator_height', 'adv_separator_after_before_color',
                                                'adv_separator_border_radius', 'adv_separator_margin'),
                                        ),
                                    )
                                ),
                                'adv_separator_icon' => array(
                                    'type'          => 'icon',
                                    'label'         => __( 'Icon', 'xpro-bb-addons' ),
                                    'show_remove'   => true,
                                    'default' => 'fas fa-gem',

                                ),
                                'adv_separator_title' => array(
                                    'type'          => 'text',
                                    'label'         => __( 'Title', 'xpro-bb-addons' ),
                                    'placeholder'   => __( 'Separator Text Here', 'xpro-bb-addons' ),
                                    'default'   => __( 'Separator Text Here', 'xpro-bb-addons' ),
                                ),
                                'adv_separator_position' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Position', 'xpro-bb-addons' ),
                                    'default'       => 'after-title',
                                    'options'       => array(
                                        'before-title'      => __( 'Before Title', 'xpro-bb-addons' ),
                                        'after-title'      => __( 'After Title', 'xpro-bb-addons' ),
                                    ),
                                ),
                            )
                        ),
                        'adv_separator_style'       => array(
                            'title'         => __('Separator', 'xpro-bb-addons'),
                            'collapsed'     => true,
                            'fields'        => array(
                                'adv_separator_text_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => 'Typography',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '{node} .xpro-heading-separator-text',
                                    ),
                                ),
                                'adv_separator_text_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Text Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-separator-text',
                                        'property' => 'color',
                                    ),
                                ),
                                'adv_separator_icon_size' => array(
                                    'type'        => 'unit',
                                    'label'       => 'Icon Size',
                                    'units'          => array( 'px', 'vw', '%' ),
                                    'slider'  => true,
                                    'responsive' => true ,
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'          => 'css',
                                        'selector'      => '{node} .xpro-heading-separator-icon > i',
                                        'property'      => 'font-size',
                                    ),
                                ),
                                'adv_separator_icon_background_size' => array(
                                    'type'        => 'unit',
                                    'label'       => 'Background Size',
                                    'units'          => array( 'px', 'vw', '%' ),
                                    'slider'  => true,
                                    'responsive' => true ,
                                    'default_unit' => 'px',
                                    'preview'       => array(
                                        'type'          => 'css',
                                        'rules'           => array(
                                            array(
                                                'selector'      => '{node} .xpro-heading-separator-icon > i',
                                                'property'      => 'width',
                                                'unit'          => 'px'
                                            ),
                                            array(
                                                'selector'      => '{node} .xpro-heading-separator-icon > i',
                                                'property'      => 'height',
                                                'unit'          => 'px'
                                            ),
                                        ),
                                    ),
                                ),
                                'adv_separator_icon_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-separator-icon > i',
                                        'property' => 'color',
                                    ),
                                ),
                                'adv_separator_icon_background_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Background Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-separator-icon > i',
                                        'property' => 'background-color',
                                    ),
                                ),
                                'adv_separator_icon_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'My Border',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-separator-icon > i',
                                    ),
                                ),
                                'adv_separator_after_before_color' => array(
                                    'type'          => 'color',
                                    'label'         => __( 'Separator Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                ),
                                'adv_separator_width' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Width',
                                    'slider'  =>  'ture',
                                    'units'          => array( 'px', 'vw', '%' ),
                                    'default_unit' => 'px',
                                    'default' => 60,
                                    'responsive'   => true,
                                ),
                                'adv_separator_height' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Height',
                                    'slider'  =>  true,
                                    'units'          => array( 'px'),
                                    'default_unit' => 'px',
                                    'default' => 1,
                                    'responsive'   => true,
                                ),
                                'adv_separator_border_radius' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Border Radius',
                                    'slider'  =>  'ture',
                                    'units'          => array( 'px'),
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'          => 'css',
                                        'selector'      => '{node} .xpro-heading-separator-simple::before, .xpro-heading-separator-double:before, .xpro-heading-separator-double:after, .xpro-heading-separator-text::before, .xpro-heading-separator-text::after, .xpro-heading-separator-icon::before, .xpro-heading-separator-icon::after',
                                        'property'      => 'border-radius',
                                    ),
                                ),
                                'adv_separator_margin' => array(
                                    'type'        => 'dimension',
                                    'label'       =>  __( 'Margin', 'xpro-bb-addons' ),
                                    'units'          => array( 'px', 'vw', '%' ),
                                    'slider'  => true,
                                    'responsive'  => true,
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} [class*=xpro-heading-separator]',
                                        'property' => 'margin',
                                    ),
                                ),
                            )
                        ),
                    ),
                ),
            ),
        )
    );

    /**
     * Register a settings form to use in the "form" field type above.
     */
    FLBuilder::register_settings_form(
        'xpro_adv_heading_shadow_form',
        array(
            'title' => __( 'Shadow Text', 'xpro-bb-addons' ),
            'tabs'  => array(
                'general' => array(
                    'title'    => __( 'Shadow Text', 'xpro-bb-addons' ),
                    'sections' => array(
                        'adv_shadow'       => array(
                            'title'         => __('Shadow Text', 'xpro-bb-addons'),
                            'fields'        => array(
                                'adv_shadow_typography' => array(
                                    'type'       => 'typography',
                                    'label'      => __( 'Typography', 'xpro-bb-addons' ),
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'      => 'css',
                                        'selector'  => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                    ),
                                ),
                                'adv_shadow_outline_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Outline Text', 'xpro-bb-addons' ),
                                    'default'       => 'disable',
                                    'options'       => array(
                                        'enable'      => __( 'Enable', 'xpro-bb-addons' ),
                                        'disable'      => __( 'Disable', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'enable'      => array(
                                            'fields'        => array( 'adv_shadow_outline_text', 'adv_shadow_outline_width' ),
                                        ),
                                        'disable'      => array(
                                            'fields'        => array( 'adv_shadow_color_type', 'adv_shadow_background_type'),
                                        ),
                                    )
                                ),
                                'adv_shadow_outline_text' => array(
                                    'type'         => 'color',
                                    'label'        => __( 'Stroke Color', 'xpro-bb-addons' ),
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'default'    => '#2b2b2b',
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => '-webkit-text-stroke-color',
                                    ),
                                ),
                                'adv_shadow_outline_width' => array(
                                    'type'        => 'unit',
                                    'label'       =>  __( 'Stroke Width', 'xpro-bb-addons' ),
                                    'units'          => array( 'px', '%' ),
                                    'slider'  => true,
                                    'default' => 1,
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => '-webkit-text-stroke-width',
                                    ),
                                ),
                                'adv_shadow_color_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Color Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'color'      => __( 'Color', 'xpro-bb-addons' ),
                                        'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'color'      => array(
                                            'fields'        => array( 'adv_shadow_color' ),
                                        ),
                                        'gradient'      => array(
                                            'fields'        => array( 'adv_shadow_gradient' ),
                                        ),
                                    )
                                ),
                                'adv_shadow_color' => array(
                                    'type'          => 'color',
                                    'label'         => 'Color ',
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'color',
                                    ),
                                ),
                                'adv_shadow_gradient' => array(
                                    'type'    => 'gradient',
                                    'label'   => ' ',
                                    'default'    => array(
                                        'colors'    => array(
                                            '0'            => '83c4b1',
                                            '1'            => '6d9a8d',
                                        ),
                                    ),
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'background-image',
                                    ),
                                ),
                                'adv_shadow_background_type' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Background Type', 'xpro-bb-addons' ),
                                    'default'       => 'none',
                                    'options'       => array(
                                        'none'      => __( 'None', 'xpro-bb-addons' ),
                                        'color'      => __( 'Color', 'xpro-bb-addons' ),
                                        'gradient'      => __( 'Gradient', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'color'      => array(
                                            'fields'        => array( 'adv_shadow_background' ),
                                        ),
                                        'gradient'      => array(
                                            'fields'        => array( 'adv_shadow_background_gradient' ),
                                        ),
                                    )
                                ),
                                'adv_shadow_background' => array(
                                    'type'          => 'color',
                                    'label'         => ' ',
                                    'show_reset'    => true,
                                    'show_alpha'    => true,
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'background-color',
                                    ),
                                ),
                                'adv_shadow_background_gradient' => array(
                                    'type'    => 'gradient',
                                    'label'   => ' ',
                                    'preview' => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'background-image',
                                    ),
                                ),
                                'adv_shadow_transform' => array(
                                    'type'          => 'button-group',
                                    'label'         => __( 'Transform', 'xpro-bb-addons' ),
                                    'default'       => 'disable',
                                    'options'       => array(
                                        'enable'      => __( 'Enable', 'xpro-bb-addons' ),
                                        'disable'      => __( 'Disable', 'xpro-bb-addons' ),
                                    ),
                                    'toggle'        => array(
                                        'enable'      => array(
                                            'fields'        => array( 'adv_shadow_horizontal_offset', 'adv_shadow_vertical_offset',
                                                'adv_shadow_rotate', 'adv_shadow_origin'),
                                        ),
                                    ),
                                ),
                                'adv_shadow_horizontal_offset' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Horizontal Offset',
                                    'units'          => array( 'px', '%', 'em' ),
                                    'responsive' => 'true',
                                    'slider' => array(
                                        'px'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                        '%'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                        'em'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                    ),
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'          => 'css',
                                        'selector'      => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property'      => '--xpro-shadow-translate-x',
                                    ),
                                ),
                                'adv_shadow_vertical_offset' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Vertical Offset',
                                    'units'          => array( 'px', '%', 'em' ),
                                    'responsive' => 'true',
                                    'slider' => array(
                                        'px'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                        '%'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                        'em'    => array(
                                            'min' => -1000,
                                            'max' => 1000,
                                            'step'    => 1,
                                        ),
                                    ),
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'          => 'css',
                                        'selector'      => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property'      => '--xpro-shadow-translate-y',
                                    ),
                                ),
                                'adv_shadow_rotate' => array(
                                    'type'         => 'unit',
                                    'label'        => 'Rotate',
                                    'units'          => array( 'deg'),
                                    'responsive' => 'true',
                                    'slider' => array(
                                        'deg'    => array(
                                            'min' => -360,
                                            'max' => 360,
                                            'step'    => 1,
                                        )
                                    ),
                                    'default_unit' => 'deg',
                                    'preview'    => array(
                                        'type'          => 'css',
                                        'selector'      => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property'      => '--xpro-shadow-rotate',
                                    ),
                                ),
                                'adv_shadow_origin' => array(
                                    'type'          => 'select',
                                    'label'         => __( 'Transform Origin', 'xpro-bb-addons' ),
                                    'default'       => 'center center',
                                    'options'       => array(
                                        'center center'      => __( 'Center Center', 'xpro-bb-addons' ),
                                        'center left'      => __( 'Center Left', 'xpro-bb-addons' ),
                                        'center right'      => __( 'Center Right', 'xpro-bb-addons' ),
                                        'top center'      => __( 'Top Center', 'xpro-bb-addons' ),
                                        'top left'      => __( 'Top Left', 'xpro-bb-addons' ),
                                        'top right'      => __( 'Top Right', 'xpro-bb-addons' ),
                                        'bottom center'      => __( 'Bottom Center', 'xpro-bb-addons' ),
                                        'bottom left'      => __( 'Bottom Left', 'xpro-bb-addons' ),
                                        'bottom right'      => __( 'Bottom Right', 'xpro-bb-addons' ),
                                    ),
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'transform-origin'
                                    ),
                                ),
                                'adv_shadow_border' => array(
                                    'type'       => 'border',
                                    'label'      => 'Border',
                                    'responsive' => true,
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                    ),
                                ),
                                'adv_shadow_padding' => array(
                                    'type'        => 'dimension',
                                    'label'       =>  __( 'Padding', 'xpro-bb-addons' ),
                                    'units'          => array( 'px', 'vw', '%' ),
                                    'slider'  => true,
                                    'responsive'  => true,
                                    'default_unit' => 'px',
                                    'preview'    => array(
                                        'type'     => 'css',
                                        'selector' => '{node} .xpro-heading-wrapper .xpro-shadow-text',
                                        'property' => 'padding',
                                    ),
                                ),
                            )
                        ),
                    ),
                ),
            ),
        )
    );
	
}