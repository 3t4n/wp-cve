<?php

/**
 *
 * @class NJBA_FlipBox_Module
 */
class NJBA_FlipBox_Module extends FLBuilderModule {
	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Flip Box', 'bb-njba' ),
			'description'     => __( 'Flip Box', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-flip-box/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-flip-box/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
	}
}

FLBuilder::register_settings_form( 'njba_flip_box_icon_form_field', array(
	'title' => __( 'Image / Icon', 'bb-njba' ),
	'tabs'  => array(
		'general' => array(
			'title'    => __( 'General', 'bb-njba' ),
			'sections' => array(
				'type_general'   => array( // Section
					'title'  => __( 'Image / Icon', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'image_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none'  => __( 'None', 'Image type.', 'bb-njba' ),
								'icon'  => __( 'Icon', 'bb-njba' ),
								'photo' => __( 'Photo', 'bb-njba' ),
							),
							'class'   => 'class_image_type',
							'toggle'  => array(
								'icon'  => array(
									'sections' => array( 'icon_basic', 'img_icon_style', 'icon_colors' ),
									'fields'   => array( '' )
								),
								'photo' => array(
									'sections' => array( 'img_basic', 'img_icon_style' ),
									'fields'   => array( '' )
								)
							),
						),
					)
				),
				'icon_basic'     => array( // Section
					'title'  => __( 'Icon Basics', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'icon'             => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'bb-njba' ),
							'show_remove' => true
						),
						'icon_size'        => array(
							'type'    => 'njba-simplify',
							'size'    => '5',
							'label'   => __( 'Font Size', 'bb-njba' ),
							'default' => array(
								'desktop' => '18',
								'medium'  => '16',
								'small'   => ''
							),
							'preview' => array(
								'type' => 'refresh',
							)
						),
						'icon_line_height' => array(
							'type'        => 'text',
							'label'       => __( 'Width / Height / Line Height', 'bb-njba' ),
							'default'     => '30',
							'help'		  => __( 'Width , Height & Line Height Values are Equel', 'bb-njba' ),
							'placeholder' => '30',
							'maxlength'   => '5',
							'size'        => '6',
							'description' => 'px',
							'preview'     => array(
								'type' => 'refresh',
							),
						),
					)
				),
				/* Image Basic Setting */
				'img_basic'      => array( // Section
					'title'  => __( 'Image Basics', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'info_photo' => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'bb-njba' ),
							'show_remove' => true,
						),
						'img_size'   => array(
							'type'        => 'text',
							'label'       => __( 'Size', 'bb-njba' ),
							'placeholder' => 'auto',
							'help'        => __( 'This size is adjust your photo and it\'s Background.', 'bb-njba' ),
							'maxlength'   => '5',
							'size'        => '6',
							'description' => 'px',
						)
					)
				),
				'img_icon_style' => array(
					'title'  => 'Style',
					'fields' => array(
						'img_icon_show_border'        => array(
							'type'    => 'select',
							'label'   => __( 'Show Border', 'bb-njba' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'bb-njba' ),
								'no'  => __( 'No', 'bb-njba' )
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'img_icon_border_width', 'icon_img_border_radius_njba', 'img_icon_border_style', 'img_icon_border_color' )
								)
							)
						),
						'img_icon_border_width'       => array(
							'type'        => 'text',
							'label'       => __( 'Border Width', 'bb-njba' ),
							'default'     => '1',
							'description' => 'px',
							'maxlength'   => '3',
							'size'        => '5',
						),
						'icon_img_border_radius_njba' => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Border Radius', 'bb-njba' ),
							'description' => 'px',
							'help'        => 'Enter Padding for Full Info box.',
							'default'     => array(
								'topleft'     => 0,
								'topright'    => 0,
								'bottomleft'  => 0,
								'bottomright' => 0
							),
							'options'     => array(
								'topleft'     => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'topright'    => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottomleft'  => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'bottomright' => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
						'img_icon_border_style'       => array(
							'type'    => 'select',
							'label'   => __( 'Border Style', 'bb-njba' ),
							'default' => 'solid',
							'options' => array(
								'none'   => __( 'None', 'bb-njba' ),
								'solid'  => __( 'Solid', 'bb-njba' ),
								'dotted' => __( 'Dotted', 'bb-njba' ),
								'dashed' => __( 'Dashed', 'bb-njba' ),
								'double' => __( 'Double', 'bb-njba' ),
							)
						),
						'img_icon_border_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'bb-njba' ),
							'default'    => 'ffffff',
							'show_reset' => true,
						),
						/* Background Color Dependent on Icon Style **/
						'img_icon_bg_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-njba' ),
							'default'    => '',
							'show_reset' => true,
						),
						'img_icon_bg_color_opc'       => array(
							'type'        => 'text',
							'label'       => __( 'Background Opacity', 'bb-njba' ),
							'default'     => '',
							'description' => '%',
							'maxlength'   => '3',
							'size'        => '5',
						),
						'img_icon_padding'            => array(
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
						'img_icon_margin'             => array(
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
					)
				),
				'icon_colors'    => array( // Section
					'title'  => __( 'Colors', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'icon_color' => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-njba' ),
							'default'    => '000000',
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.njba-icon-img i',
								'property' => 'color'
							)
						),
					)
				)
			)
		)
	)
) );
FLBuilder::register_settings_form( 'njba_button_form_field', array(
	'title' => __( 'Button', 'bb-njba' ),
	'tabs'  => array(
		'button_tab'     => array( // Tab
			'title'    => __( 'Button', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'button_section'      => array(
					'title'  => '',
					'fields' => array(
						'button_text' => array(
							'type'    => 'text',
							'label'   => 'Text',
							'default' => __( 'SUBMIT', 'bb-njba' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => ''
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
							'label'       => __( 'Link Target', 'bb-njba' ),
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
							'label'   => __( 'Icon Type', 'bb-njba' ),
							'default' => 'none',
							'options' => array(
								'none'        => __( 'None', 'bb-njba' ),
								'font_icon'   => __( 'Icon', 'bb-njba' ),
								'custom_icon' => __( 'Image', 'bb-njba' )
							),
							'toggle'  => array(
								'font_icon'   => array(
									'fields'   => array( 'button_font_icon', 'button_icon_aligment' ),
									'sections' => array( 'icon_section', 'icon_typography' ),
								),
								'custom_icon' => array(
									'fields'   => array( 'button_custom_icon', 'button_icon_aligment' ),
									'sections' => array( '' ),
								),
							)
						),
						'button_font_icon'     => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'bb-njba' )
						),
						'button_custom_icon'   => array(
							'type'  => 'photo',
							'label' => __( 'Custom Image', 'bb-njba' ),
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
		'style_tab'      => array(
			'title'    => __( 'Style', 'bb-njba' ),
			'sections' => array(
				'button_style_section' => array(
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
							'default'    => 'dfdfdf'
						),
						'button_background_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'button_text_color'             => array(
							'type'       => 'color',
							'label'      => __( 'Text Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '404040'
						),
						'button_text_hover_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Text Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => 'ffffff'
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
							)
						),
						'button_border_width'           => array(
							'type'        => 'text',
							'label'       => __( 'Border Width', 'bb-njba' ),
							'default'     => '1',
							'size'        => '5',
							'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
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
								'top-left'    => array(
									'placeholder' => __( 'Top-Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'top-right'  => array(
									'placeholder' => __( 'Top-Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom-left' => array(
									'placeholder' => __( 'Bottom-Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'bottom-right'   => array(
									'placeholder' => __( 'Bottom-Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
						'button_border_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'button_border_hover_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
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
							'default'    => 'ffffff'
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						),
						'button_margin'                 => array(
							'type'        => 'njba-multinumber',
							'label'       => __( 'Button Margin', 'bb-njba' ),
							'description' => 'px',
							'default'     => array(
								'top'    => 10,
								'right'  => 0,
								'bottom' => 10,
								'left'   => 0
							),
							'options'     => array(
								'top'    => array(
									'placeholder' => __( 'Top', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						)
					)
				),
				'icon_section'         => array(
					'title'  => __( 'Icon', 'bb-njba' ),
					'fields' => array(
						'icon_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => '000000'
						),
						'icon_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'bb-njba' ),
							'show_reset' => true,
							'default'    => 'ffffff'
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
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
									'icon'        => 'fa-long-arrow-up'
								),
								'right'  => array(
									'placeholder' => __( 'Right', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-right'
								),
								'bottom' => array(
									'placeholder' => __( 'Bottom', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-down'
								),
								'left'   => array(
									'placeholder' => __( 'Left', 'bb-njba' ),
									'icon'        => 'fa-long-arrow-left'
								)
							)
						)
					)
				),
				'transition_section'   => array(
					'title'  => __( 'Transition', 'bb-njba' ),
					'fields' => array(
						'transition' => array(
							'type'        => 'text',
							'label'       => __( 'Transition delay', 'bb-njba' ),
							'default'     => 0.3,
							'size'        => '5',
							'description' => 'sec'
						),
					)
				),
				'structure_section'    => array(
					'title'  => __( 'Structure', 'bb-njba' ),
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
							'type'    => 'text',
							'label'   => __( 'Custom Width', 'bb-njba' ),
							'default' => 200,
							'size'    => 10
						),
						'custom_height' => array(
							'type'    => 'text',
							'label'   => __( 'Custom Height', 'bb-njba' ),
							'default' => 45,
							'size'    => 10
						),
						'alignment'     => array(
							'type'    => 'select',
							'label'   => __( 'Alignment', 'bb-njba' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'bb-njba' ),
								'center' => __( 'Center', 'bb-njba' ),
								'right'  => __( 'Right', 'bb-njba' )
							)
						)
					)
				)
			)
		),
		'typography_tab' => array(
			'title'    => __( 'Typography', 'bb-njba' ),
			'sections' => array(
				'button_typography' => array(
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
							'type'    => 'njba-simplify',
							'size'    => '5',
							'label'   => __( 'Font Size', 'bb-njba' ),
							'default' => array(
								'desktop' => '18',
								'medium'  => '14',
								'small'   => '12'
							)
						)
					)
				),
				'icon_typography'   => array(
					'title'  => __( 'Icon', 'bb-njba' ),
					'fields' => array(
						'icon_font_size' => array(
							'type'    => 'njba-simplify',
							'size'    => '5',
							'label'   => __( 'Font Size', 'bb-njba' ),
							'default' => array(
								'desktop' => '18',
								'medium'  => '14',
								'small'   => '12'
							)
						)
					)
				)
			)
		)
	)
) );
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_FlipBox_Module', array(
	'flip_front' => array( // Tab
		'title'    => __( 'Front', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'title'        => array( // Section
				'title'  => __( 'Front', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'title_icon'  => array(
						'type'         => 'form',
						'label'        => __( 'Image / Icon Settings', 'bb-njba' ),
						'form'         => 'njba_flip_box_icon_form_field', // ID of a registered form.
						'preview_text' => 'icon', // ID of a field to use for the preview text.
					),
					'title_front' => array(
						'type'    => 'text',
						'label'   => __( 'Title', 'bb-njba' ),
						'default' => __( 'Hover Me!', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-title',
						)
					),
					'desc_front'  => array(
						'type'          => 'editor',
						'media_buttons' => false,
						'rows'          => 10,
						'label'         => __( 'Description', 'bb-njba' ),
						'default'       => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-heading-sub-title',
						)
					),
				)
			),
			'front_styles' => array( // Section
				'title'  => __( 'Box Styles', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'front_background_type'      => array(
						'type'    => 'select',
						'label'   => __( 'Background Type', 'bb-njba' ),
						'default' => 'color',
						'options' => array(
							'color' => __( 'Color', 'bb-njba' ),
							'image' => __( 'Image', 'bb-njba' ),
						),
						'toggle'  => array(
							'color' => array(
								'fields' => array( 'front_background_color', 'front_background_color_opc' )
							),
							'image' => array(
								'fields' => array(
									'front_bg_image',
									'front_bg_image_repeat',
									'front_bg_image_display',
									'front_bg_image_pos',
									'front_background_color',
									'front_background_color_opc'
								)
							)
						)
					),
					'front_bg_image'             => array(
						'type'        => 'photo',
						'label'       => __( 'Background Image', 'bb-njba' ),
						'show_remove' => true,
					),
					'front_bg_image_pos'         => array(
						'type'    => 'select',
						'label'   => __( 'Background Image Position', 'bb-njba' ),
						'default' => 'center center',
						'options' => array(
							'left top'      => __( 'Left Top', 'bb-njba' ),
							'left center'   => __( 'Left Center', 'bb-njba' ),
							'left bottom'   => __( 'Left Bottom', 'bb-njba' ),
							'center top'    => __( 'Center Top', 'bb-njba' ),
							'center center' => __( 'Center Center', 'bb-njba' ),
							'center bottom' => __( 'Center Bottom', 'bb-njba' ),
							'right top'     => __( 'Right Top', 'bb-njba' ),
							'right center'  => __( 'Right Center', 'bb-njba' ),
							'right bottom'  => __( 'Right Bottom', 'bb-njba' ),
						),
					),
					'front_bg_image_repeat'      => array(
						'type'    => 'select',
						'label'   => __( 'Repeat', 'bb-njba' ),
						'default' => 'no-repeat',
						'options' => array(
							'repeat'    => 'Repeat',
							'no-repeat' => 'No Repeat',
						),
					),
					'front_bg_image_display'     => array(
						'type'    => 'select',
						'label'   => __( 'Display Sizes', 'bb-njba' ),
						'default' => 'cover',
						'options' => array(
							'initial' => __( 'Initial', 'bb-njba' ),
							'cover'   => __( 'Cover', 'bb-njba' ),
							'contain' => __( 'Contain', 'bb-njba' ),
						),
					),
					'front_background_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-front',
							'property' => 'background'
						)
					),
					'front_background_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Background Opacity', 'bb-njba' ),
						'default'     => '1',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '0 to 1'
					),
					'front_box_border_style'     => array(
						'type'    => 'select',
						'label'   => __( 'Box Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
							'inset'  => __( 'Inset', 'bb-njba' ),
							'outset' => __( 'Outset', 'bb-njba' )
						),
						'toggle'  => array(
							'none'   => array(
								'fields' => array()
							),
							'solid'  => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
							'dashed' => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
							'double' => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
							'inset'  => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
							'outset' => array(
								'fields' => array( 'front_border_size', 'front_border_color' )
							),
						),
					),
					'front_border_size'          => array(
						'type'        => 'text',
						'label'       => __( 'Border Size', 'bb-njba' ),
						'size'        => '8',
						'class'       => '',
						'description' => 'px',
						'default'     => '1',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.bb-njba-front',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),
					'front_border_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'dbdbdb',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-front',
							'property' => 'border-color'
						)
					),
				)
			),
		)
	),
	'flip_back'  => array( // Tab
		'title'    => __( 'Back', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'title'       => array( // Section
				'title'  => __( 'Back', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'title_back' => array(
						'type'    => 'text',
						'label'   => __( 'Title', 'bb-njba' ),
						'default' => __( 'Cool Right!', 'bb-njba' )
					),
					'desc_back'  => array(
						'type'          => 'editor',
						'media_buttons' => false,
						'rows'          => 10,
						'label'         => '',
						'default'       => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',
							'bb-njba' ),
					),
				)
			),
			'back_styles' => array( // Section
				'title'  => __( 'Box Styles', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'back_background_type'      => array(
						'type'    => 'select',
						'label'   => __( 'Background Type', 'bb-njba' ),
						'default' => 'color',
						'options' => array(
							'color' => __( 'Color', 'bb-njba' ),
							'image' => __( 'Image', 'bb-njba' ),
						),
						'toggle'  => array(
							'color' => array(
								'fields' => array( 'back_background_color', 'back_background_color_opc' )
							),
							'image' => array(
								'fields' => array(
									'back_bg_image',
									'back_bg_image_repeat',
									'back_bg_image_display',
									'back_bg_image_pos',
									'back_background_color',
									'back_background_color_opc'
								)
							)
						)
					),
					'back_bg_image'             => array(
						'type'        => 'photo',
						'label'       => __( 'Background Image', 'bb-njba' ),
						'show_remove' => true,
					),
					'back_bg_image_pos'         => array(
						'type'    => 'select',
						'label'   => __( 'Background Image Position', 'bb-njba' ),
						'default' => 'center center',
						'options' => array(
							'left top'      => __( 'Left Top', 'bb-njba' ),
							'left center'   => __( 'Left Center', 'bb-njba' ),
							'left bottom'   => __( 'Left Bottom', 'bb-njba' ),
							'center top'    => __( 'Center Top', 'bb-njba' ),
							'center center' => __( 'Center Center', 'bb-njba' ),
							'center bottom' => __( 'Center Bottom', 'bb-njba' ),
							'right top'     => __( 'Right Top', 'bb-njba' ),
							'right center'  => __( 'Right Center', 'bb-njba' ),
							'right bottom'  => __( 'Right Bottom', 'bb-njba' ),
						),
					),
					'back_bg_image_repeat'      => array(
						'type'    => 'select',
						'label'   => __( 'Repeat', 'bb-njba' ),
						'default' => 'no-repeat',
						'options' => array(
							'repeat'    => 'Repeat',
							'no-repeat' => 'No Repeat',
						),
					),
					'back_bg_image_display'     => array(
						'type'    => 'select',
						'label'   => __( 'Display Sizes', 'bb-njba' ),
						'default' => 'cover',
						'options' => array(
							'initial' => __( 'Initial', 'bb-njba' ),
							'cover'   => __( 'Cover', 'bb-njba' ),
							'contain' => __( 'Contain', 'bb-njba' ),
						),
					),
					'back_background_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'back_background_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '1',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '0 to 1'
					),
					'back_box_border_style'     => array(
						'type'    => 'select',
						'label'   => __( 'Box Border Style', 'bb-njba' ),
						'default' => 'solid',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
							'inset'  => __( 'Inset', 'bb-njba' ),
							'outset' => __( 'Outset', 'bb-njba' )
						),
						'toggle'  => array(
							'none'   => array(
								'fields' => array()
							),
							'solid'  => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
							'dashed' => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
							'double' => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
							'inset'  => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
							'outset' => array(
								'fields' => array( 'back_border_size', 'back_border_color' )
							),
						),
					),
					'back_border_size'          => array(
						'type'        => 'text',
						'label'       => __( 'Border Size', 'bb-njba' ),
						'size'        => '8',
						'class'       => '',
						'default'     => '1',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.bb-njba-back',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),
					'back_border_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'dbdbdb',
						'show_reset' => true,
					),
				)
			),
			'button'      => array( // Section
				'title'  => __( 'Button', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'show_button' => array(
						'type'    => 'select',
						'label'   => __( 'Show button', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'no'  => __( 'No', 'bb-njba' ),
							'yes' => __( 'Yes', 'bb-njba' ),
						),
						'toggle'  => array(
							'no'  => array(
								'fields' => array()
							),
							'yes' => array(
								'fields' => array( 'button', 'button_margin_top', 'button_margin_bottom' )
							)
						),
					),
					'button'      => array(
						'type'         => 'form',
						'label'        => __( 'Button Settings', 'bb-njba' ),
						'form'         => 'njba_button_form_field', // ID of a registered form.
						'preview_text' => 'text', // ID of a field to use for the preview text.
					),
				)
			),
		)
	),
	'style'      => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => __( 'Flipbox', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'box_minimum_height' => array(
						'type'        => 'text',
						'label'       => __( 'Minimum Height', 'bb-njba' ),
						'default'     => '',
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '3',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-face.njba-front,.njba-face.njba-back',
							'property' => 'min-height',
							'unit'     => 'px'
						),
					),
					'flip_type'          => array(
						'type'    => 'select',
						'label'   => __( 'Flip Type', 'bb-njba' ),
						'default' => 'horizontal_flip_left',
						'options' => array(
							'horizontal_flip_left'  => __( 'Flip Horizontally From Left', 'bb-njba' ),
							'horizontal_flip_right' => __( 'Flip Horizontally From Right', 'bb-njba' ),
							'vertical_flip_top'     => __( 'Flip Vertically From Top', 'bb-njba' ),
							'vertical_flip_bottom'  => __( 'Flip Vertically From Bottom', 'bb-njba' ),
						)
					),
					'inner_padding'      => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
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
								'icon'        => 'fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						)
					),
					'box_border_radius'  => array(
						'type'        => 'text',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-flip-box .njba-face',
							'property' => 'border-radius',
							'unit'     => 'px'
						),
						
					),
				)
			),
		)
	),
	'typography' => array( // Tab
		'title'    => __( 'Typography', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'front_title_typography' => array(
				'title'  => __( 'Front Title', 'bb-njba' ),
				'fields' => array(
					'front_title_typography_tag_selection' => array(
						'type'    => 'select',
						'label'   => __( 'Title Tag', 'bb-njba' ),
						'default' => 'h2',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
					),
					'front_title_typography_font_family'   => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
					),
					'front_title_typography_font_size'     => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '28',
							'medium'  => '22',
							'small'   => '18',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'front_title_typography_line_height'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '36',
							'medium'  => '28',
							'small'   => '22',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'front_title_typography_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'front_title_typography_margin'        => array(
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
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					),
				)
			),
			'front_desc_typography'  => array(
				'title'  => __( 'Front Description', 'bb-njba' ),
				'fields' => array(
					'front_desc_typography_font_family' => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
					),
					'front_desc_typography_font_size'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '14',
							'medium'  => '13',
							'small'   => '12',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'front_desc_typography_line_height' => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '20',
							'medium'  => '18',
							'small'   => '16',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'front_desc_typography_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'front_desc_typography_margin'      => array(
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
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					),
				)
			),
			'back_title_typography'  => array(
				'title'  => __( 'Back Title', 'bb-njba' ),
				'fields' => array(
					'back_title_typography_tag_selection' => array(
						'type'    => 'select',
						'label'   => __( 'Title Tag', 'bb-njba' ),
						'default' => 'h2',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' )
						),
					),
					'back_title_typography_font_family'   => array(
						'type'    => 'font',
						'label'   => __( 'Font Family', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
					),
					'back_title_typography_font_size'     => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '28',
							'medium'  => '22',
							'small'   => '18',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'back_title_typography_line_height'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '36',
							'medium'  => '28',
							'small'   => '22',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'back_title_typography_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Back Title Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'back_title_typography_margin'        => array(
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
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					),
				)
			),
			'back_desc_typography'   => array(
				'title'  => __( 'Back Description', 'bb-njba' ),
				'fields' => array(
					'back_desc_typography_font_family' => array(
						'type'    => 'font',
						'label'   => __( 'Font Family', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.bb-njba-back-flip-box-section-content'
						),
					),
					'back_desc_typography_font_size'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '14',
							'medium'  => '13',
							'small'   => '12',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'back_desc_typography_line_height' => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Line Height', 'bb-njba' ),
						'default' => array(
							'desktop' => '20',
							'medium'  => '18',
							'small'   => '16',
						),
						'description'  => 'Please Enter Values in pixels.',
					),
					'back_desc_typography_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Back Description Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'back_desc_typography_margin'      => array(
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
									'selector' => '.njba-heading-title',
									'property' => 'margin-top',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-right',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-heading-title',
									'property' => 'margin-left',
								),
							)
						)
					),
				)
			),
		)
	),
) );
