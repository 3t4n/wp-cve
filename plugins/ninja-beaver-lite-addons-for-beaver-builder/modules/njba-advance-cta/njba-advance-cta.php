<?php

/**
 * @class NJBA_Cta_Module
 */
class NJBA_Cta_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Call to Action', 'bb-njba' ),
			'description'     => __( 'Display a heading, subheading and a button.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-advance-cta/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-advance-cta/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'icon'            => 'megaphone.svg',
		) );
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Cta_Module', array(
	'general'    => array( //Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'structure'       => array(
				'title'  => __( 'Structure', 'bb-njba' ),
				'fields' => array(
					'cta_layout' => array(
						'type'    => 'select',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 'inline',
						'options' => array(
							'inline'  => __( 'Inline', 'bb-njba' ),
							'stacked' => __( 'Stacked', 'bb-njba' )
						),
						'toggle'  => array(
							'inline'  => array(
								'fields' => array( 'cta_column' )
							),
							'stacked' => array(
								'fields'   => array( '' ),
								'sections' => array( 'btn_structure' )
							)
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-cta-text, .njba-btn-main',
							'property' => 'width'
						)
					),
					'cta_column' => array(
						'type'        => 'select',
						'label'       => __( 'Column', 'bb-njba' ),
						'default'     => __( '50_50', 'bb-njba' ),
						'options'     => array(
							'50_50' => __( '50/50', 'bb-njba' ),
							'60_40' => __( '60/40', 'bb-njba' ),
							'70_30' => __( '70/30', 'bb-njba' ),
							'80_20' => __( '80/20', 'bb-njba' )
						),
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-cta-text, .njba-btn-main',
							'property' => 'width',
							'unit'     => '%'
						)
					)
				)
			),
			'heading'         => array( // Section
				'title'  => __( 'Heading', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'main_title'     => array(
						'type'    => 'text',
						'label'   => __( 'Heading', 'bb-njba' ),
						'default' => 'NJBA HEADING',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					),
					'main_title_tag' => array(
						'type'    => 'select',
						'label'   => __( 'Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title'
						)
					)
				)
			),
			'sub_title_sec'   => array( // Section
				'title'  => __( 'Subtitle', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'sub_title' => array(
						'type'          => 'editor',
						'label'         => __( 'Subtitle', 'bb-njba' ),
						'media_buttons' => false,
						'rows'          => 6,
						'default'       => __( 'Enter description text here.', 'bb-njba' ),
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title'
						)
					)
				)
			),
			'separator_sec'   => array( // Section
				'title'  => __( 'Separator', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'separator_select' => array(
						'type'    => 'select',
						'label'   => __( 'Display Separator', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'bb-njba' ),
							'yes' => __( 'Yes', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'sections' => array( 'separator_style' ),
								'fields'   => array( '' )
							)
						)
					)
				)
			),
			'separator_style' => array( // Section
				'title'  => __( 'Separator Style', 'bb-njba' ), // Section Title,
				'fields' => array( // Section Fields
					'separator_type'             => array(
						'type'    => 'select',
						'default' => 'separator_normal',
						'label'   => __( 'Type', 'bb-njba' ),
						'options' => array(
							'separator_normal' => __( 'Normal', 'bb-njba' ),
							'separator_icon'   => __( 'Separator With Icon', 'bb-njba' ),
							'separator_image'  => __( 'Separator With Image', 'bb-njba' ),
							'separator_text'   => __( 'Separator With Text', 'bb-njba' ),
						),
						'toggle'  => array(
							'separator_normal' => array(
								'fields' => array( 'separator_normal_width' )
							),
							'separator_icon'   => array(
								'fields' => array( 'separator_icon_text', 'separator_icon_font_size', 'separator_icon_font_color' )
							),
							'separator_image'  => array(
								'fields' => array( 'separator_image_select' )
							),
							'separator_text'   => array(
								'fields' => array( 'separator_text_select', 'separator_text_font_size', 'separator_text_font_color', 'separator_text_line_height' )
							)
						)
					),
					'icon_position'              => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Position', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-icon-separator-left',
							'property' => 'width',
							'unit'     => '%'
						)
					),
					'separator_normal_width'     => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '3',
						'default'     => '50',
						'placeholder' => '50',
						'label'       => __( 'Separator Width', 'bb-njba' ),
						'description' => _x( '%', 'Value unit for separator Width. Such as: "50%"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-icon',
							'property' => 'width',
							'unit'     => '%'
						)
					),
					'separator_icon_text'        => array(
						'type'  => 'icon',
						'label' => __( 'Icon', 'bb-njba' )
					),
					'separator_icon_font_size'   => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '18',
						'label'       => __( 'Icon Size', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content i',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'separator_icon_font_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content i',
							'property' => 'color'
						)
					),
					'separator_image_select'     => array(
						'type'        => 'photo',
						'label'       => __( 'Image', 'bb-njba' ),
						'show_remove' => true
					),
					'separator_text_select'      => array(
						'type'    => 'text',
						'label'   => __( 'Text', 'bb-njba' ),
						'default' => 'Example',
						'help'    => __( 'Use a unique small word to highlight this Heading.', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-divider-content .separator-text'
						)
					),
					'separator_text_font_size'   => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '16',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'separator_text_line_height' => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => '8',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'separator_text_font_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Font Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-divider-content .separator-text',
							'property' => 'color'
						)
					),
					'separator_margintb'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 20,
							'bottom' => 20
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '..njba-icon',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							)
						)
					),
					'separator_border_width'     => array(
						'type'        => 'text',
						'default'     => '1',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-top',
							'unit'     => 'px'
						)
					),
					'separator_border_style'     => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-top-style',
						)
					),
					'separator_border_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-separator-line > span',
							'property' => 'border-color',
						)
					)
				)
			)
		)
	),
	'button_tab' => array( // Tab
		'title'    => __( 'Button', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'button_section'      => array(
				'title'  => '',
				'fields' => array(
					'button_text' => array(
						'type'    => 'text',
						'label'   => 'Text',
						'default' => __( 'GET STARTED', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-button-text'
						)
					),
				)
			),
			'button_link_section' => array(
				'title'  => __( 'Link', 'bb-njba' ), // Tab title',
				'fields' => array(
					'link'        => array(
						'type'        => 'link',
						'label'       => __( 'Link', 'bb-njba' ),
						'default'     => __( '#', 'bb-njba' ),
						'placeholder' => 'www.example.com',
						'preview'     => array(
							'type' => 'none'
						)
					),
					'link_target' => array(
						'type'        => 'select',
						'label'       => __( 'Target', 'bb-njba' ),
						'default'     => __( '_self', 'bb-njba' ),
						'placeholder' => 'www.example.com',
						'options'     => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' ),
						),
						'preview'     => array(
							'type' => 'none'
						)
					)
				)
			),
			'button_icon_section' => array(
				'title'  => __( 'Icon', 'bb-njba' ), // Tab title',
				'fields' => array(
					'buttton_icon_select'  => array(
						'type'    => 'select',
						'label'   => __( 'Display', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'      => __( 'No', 'bb-njba' ),
							'font_icon' => __( 'Yes', 'bb-njba' ),
						),
						'toggle'  => array(
							'font_icon' => array(
								'fields'   => array( 'button_font_icon', 'button_icon_aligment' ),
								'sections' => array( 'icon_section', 'icon_typography' ),
							)
						)
					),
					'button_font_icon'     => array(
						'type'  => 'icon',
						'label' => __( 'Icon', 'bb-njba' )
					),
					'button_icon_aligment' => array(
						'type'    => 'select',
						'label'   => __( 'Icon Position', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'  => __( 'Before Text', 'bb-njba' ),
							'right' => __( 'After Text', 'bb-njba' )
						),
					)
				)
			)
		)
	),
	'style'      => array(
		'title'    => __( 'Style', 'bb-njba' ),
		'sections' => array(
			'cta_heading_style_section'     => array(
				'title'  => __( 'Title', 'bb-njba' ),
				'fields' => array(
					'heading_title_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'color',
						)
					),
					'heading_title_alignment' => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'text-align'
						)
					),
					'heading_margin'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),
			'cta_sub_heading_style_section' => array(
				'title'  => __( 'Description', 'bb-njba' ),
				'fields' => array(
					'heading_sub_title_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'color',
						)
					),
					'heading_sub_title_alignment' => array(
						'type'    => 'select',
						'default' => 'center',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'text-align'
						)
					),
					'heading_subtitle_margin'     => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-top',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-heading-sub-title',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
				)
			),
			'button_style_section'          => array(
				'title'  => __( 'Button', 'bb-njba' ),
				'fields' => array(
					'button_style'                  => array(
						'type'    => 'select',
						'label'   => __( 'Style', 'bb-njba' ),
						'default' => 'flat',
						'class'   => 'creative_button_styles',
						'options' => array(
							'flat'        => __( 'Flat', 'bb-njba' ),
							'gradient'    => __( 'Gradient', 'bb-njba' ),
							'transparent' => __( 'Transparent', 'bb-njba' ),
							'threed'      => __( '3D', 'bb-njba' ),
						),
						'toggle'  => array(
							'flat'        => array(
								'fields'   => array( 'button_background_color', 'hover_button_style', 'button_box_shadow', 'button_box_shadow_color' ),
								'sections' => array( 'transition_section' )
							),
							'gradient'    => array(
								'fields' => array( 'button_background_color' )
							),
							'threed'      => array(
								'fields'   => array( 'button_background_color', 'hover_button_style' ),
								'sections' => array( 'transition_section' )
							),
							'transparent' => array(
								'fields'   => array( 'hover_button_style', 'button_box_shadow', 'button_box_shadow_color' ),
								'sections' => array( 'transition_section' )
							)
						)
					),
					'button_background_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dfdfdf',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'background'
						)
					),
					'button_background_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000',
						'preview'    => array(
							'type' => 'none'
						)
					),
					'button_text_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '404040',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'color'
						)
					),
					'button_text_hover_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff',
						'preview'    => array(
							'type' => 'none'
						)
					),
					'button_border_style'           => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'button_border_width', 'button_border_color', 'button_border_hover_color' )
							),
							'dotted' => array(
								'fields' => array( 'button_border_width', 'button_border_color', 'button_border_hover_color' )
							),
							'dashed' => array(
								'fields' => array( 'button_border_width', 'button_border_color', 'button_border_hover_color' )
							),
							'double' => array(
								'fields' => array( 'button_border_width', 'button_border_color', 'button_border_hover_color' )
							),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-style'
						)
					),
					'button_border_width'           => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-width'
						)
					),
					'button_border_radius'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top-left'     => 0,
							'top-right'    => 0,
							'bottom-left'  => 0,
							'bottom-right' => 0
						),
						'options'     => array(
							'top-left'     => array(
								'placeholder' => __( 'Top Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-top-left-radius',
									'unit'     => 'px'
								)
							),
							'top-right'    => array(
								'placeholder' => __( 'Top Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-top-right-radius',
									'unit'     => 'px'
								),
							),
							'bottom-left'  => array(
								'placeholder' => __( 'Bottom Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-bottom-left-radius',
									'unit'     => 'px'
								),
							),
							'bottom-right' => array(
								'placeholder' => __( 'Bottom Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'border-bottom-right-radius',
									'unit'     => 'px'
								),
							)
						)
					),
					'button_border_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-color'
						)
					),
					'button_border_hover_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000',
						'preview'    => array(
							'type' => 'none'
						)
					),
					'button_box_shadow'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'left_right' => 0,
							'top_bottom' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'left_right' => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-h'
							),
							'top_bottom' => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-v'
							),
							'blur'       => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa fa-circle-thin'
							),
							'spread'     => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa fa-circle'
							)
						)
					),
					'button_box_shadow_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'box-shadow'
						)
					),
					'button_padding'                => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 15,
							'bottom' => 10,
							'left'   => 15
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							)
						)
					),
					'button_margin'                 => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Button Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 10,
							'bottom' => 10,
							'left'   => 10
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					)
				)
			),
			'icon_section'                  => array(
				'title'  => __( 'Button Icon', 'bb-njba' ),
				'fields' => array(
					'icon_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn i',
							'property' => 'color'
						)
					),
					'icon_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Icon Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000'
					),
					'icon_padding'     => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							)
						)
					),
					'icon_margin'      => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 0,
							'right'  => 0,
							'bottom' => 0,
							'left'   => 0
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-btn-main a.njba-btn i',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					)
				)
			),
			'transition_section'            => array(
				'title'  => __( 'Button Transition', 'bb-njba' ),
				'fields' => array(
					'transition' => array(
						'type'        => 'text',
						'label'       => __( 'Transition Delay', 'bb-njba' ),
						'default'     => 0.3,
						'size'        => '5',
						'description' => 'sec',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn i',
							'property' => 'transition'
						)
					)
				)
			),
			'structure_section'             => array(
				'title'  => __( 'Button Structure', 'bb-njba' ),
				'fields' => array(
					'width'         => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'bb-njba' ),
						'default' => 'auto',
						'options' => array(
							'auto'       => __( 'Auto', 'bb-njba' ),
							'full_width' => __( 'Full Width', 'bb-njba' ),
							'custom'     => __( 'Custom', 'bb-njba' )
						),
						'toggle'  => array(
							'auto'       => array(
								'fields' => array( 'alignment' )
							),
							'full_width' => array(
								'fields' => array( '' )
							),
							'custom'     => array(
								'fields' => array( 'custom_width', 'custom_height', 'alignment' )
							)
						)
					),
					'custom_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Custom Width', 'bb-njba' ),
						'default'     => 200,
						'placeholder' => 200,
						'size'        => 10,
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'width'
						)
					),
					'custom_height' => array(
						'type'        => 'text',
						'label'       => __( 'Custom Height', 'bb-njba' ),
						'default'     => 45,
						'placeholder' => 45,
						'size'        => 10,
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'min-height'
						)
					),
					'alignment'     => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main',
							'property' => 'text-align'
						)
					)
				)
			)
		)
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'title_typography'   => array(
				'title'  => __( 'Title', 'bb-njba' ),
				'fields' => array(
					'heading_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-title'
						)
					),
					'heading_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '28',
							'medium'  => '24',
							'small'   => '20',
						),
						'description' => 'Please enter value in pixel.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '30',
							'medium'  => '26',
							'small'   => '22',
						),
						'description' => 'Please enter value in pixel.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
				)
			),
			'subhead_typography' => array(
				'title'  => __( 'Description', 'bb-njba' ),
				'fields' => array(
					'heading_sub_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-heading-sub-title'
						)
					),
					'heading_sub_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '18',
							'small'   => '18',
						),
						'description' => 'Please enter value in pixel.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_sub_title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '20',
							'medium'  => '18',
							'small'   => '18',
						),
						'description' => 'Please enter value in pixel.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-heading-sub-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
				)
			),
			'button_typography'  => array(
				'title'  => __( 'Button', 'bb-njba' ),
				'fields' => array(
					'button_font_family' => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-btn-main a.njba-btn'
						)
					),
					'button_font_size'   => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => 'Please enter value in pixel.',
						'default'     => array(
							'desktop' => '20',
							'medium'  => '16',
							'small'   => '16'
						)
					)
				)
			),
			'icon_typography'    => array(
				'title'  => __( 'Button Icon', 'bb-njba' ),
				'fields' => array(
					'icon_font_size' => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => 'Please enter value in pixel',
						'default'     => array(
							'desktop' => '20',
							'medium'  => '16',
							'small'   => '16'
						)
					)
				)
			)
		)
	)
) );
