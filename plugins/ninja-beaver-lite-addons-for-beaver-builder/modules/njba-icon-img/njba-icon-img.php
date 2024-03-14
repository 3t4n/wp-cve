<?php

/**
 * @class NJBA_Icon_Img_Module
 */
class NJBA_Icon_Img_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Icon / Image', 'bb-njba' ),
			'description'     => __( 'Addon for icon and image.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-icon-img/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-icon-img/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Defaults to false and can be omitted.
			'icon'            => 'star-filled.svg',
		) );
		$this->add_css( 'font-awesome' );
	}

	/**
	 * Use this method to work with settings data before
	 * it is saved. You must return the settings object.
	 *
	 * @method update
	 * @param $settings {object}
	 *
	 * @return object
	 */
	public function update( $settings ) {
		return $settings;
	}

	/**
	 * This method will be called by the builder
	 * right before the module is deleted.
	 *
	 * @method delete
	 */
	public function delete() {
	}
}

FLBuilder::register_module( 'NJBA_Icon_Img_Module', array(
	'general' => array(
		'title'    => __( 'General', 'bb-njba' ),
		'sections' => array(
			'type_general'   => array( // Section
				'title'  => __( 'Icon / Image', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'image_type'                 => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'icon',
						'options' => array(
							'none'  => __( 'None', 'Image type.', 'bb-njba' ),
							'icon'  => __( 'Icon', 'bb-njba' ),
							'photo' => __( 'Photo', 'bb-njba' ),
						),
						'class'   => 'class_image_type',
						'toggle'  => array(
							'icon'  => array(
								'fields'   => array( 'overall_alignment_img_icon' ),
								'sections' => array( 'icon_basic', 'icon_style', 'icon_colors', 'common_style' )
							),
							'photo' => array(
								'fields'   => array( 'overall_alignment_img_icon' ),
								'sections' => array( 'img_basic', 'img_style', 'common_style' )
							)
						),
					),
					'img_icon_show_link'         => array(
						'type'    => 'select',
						'label'   => __( 'Display Link', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'icon_image_link', 'icon_image_link_target' )
							)
						)
					),
					'icon_image_link'            => array(
						'type'        => 'link',
						'label'       => __( 'Link', 'bb-njba' ),
						'default'     => __( '#', 'bb-njba' ),
						'placeholder' => 'www.example.com',
						'preview'     => array(
							'type' => 'none'
						)
					),
					'icon_image_link_target'     => array(
						'type'    => 'select',
						'label'   => __( 'Link Target', 'bb-njba' ),
						'default' => __( '_self', 'bb-njba' ),
						'options' => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'overall_alignment_img_icon' => array(
						'type'    => 'select',
						'label'   => __( 'Overall Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img',
							'property' => 'text-align'
						)
					)
				),
			),
			'icon_basic'     => array( // Section
				'title'  => __( 'Icon Basics', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'icon'             => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'bb-njba' ),
						'show_remove' => true,
						'default'     => 'fas fa-laugh'
					),
					'icon_size'        => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Icon Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '30',
							'medium'  => '20',
							'small'   => ''
						),
						'description' => 'Please Enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img-main .njba-icon-img ',
							'property' => 'font-size',
							'unit'     => 'px'
						),
					),
					'icon_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Icon Width / Height', 'bb-njba' ),
						'placeholder' => '50',
						'default'     => array(
							'desktop' => '50',
							'medium'  => '30',
							'small'   => ''
						),
						'help'        => 'Icon box width and height will be equal.',
						'size'        => '5',
						'description' => 'Please Enter value in pixels.',
					),
				)
			),
			/* Image Basic Setting */
			'img_basic'      => array( // Section
				'title'  => __( 'Image Basics', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'photo'    => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
					),
					'img_size' => array(
						'type'        => 'text',
						'label'       => __( 'Width', 'bb-njba' ),
						'placeholder' => 'auto',
						'help'        => __( 'This size is adjust your photo and it\'s Background.', 'bb-njba' ),
						'maxlength'   => '5',
						'size'        => '6',
						'description' => 'px',
					)
				)
			),
			'img_icon_style' => array(
				'title'  => 'Border of Icon / Image',
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
								'fields' => array(
									'img_icon_border_width',
									'icon_img_border_radius_njba',
									'img_icon_border_style',
									'img_icon_border_color',
									'img_icon_border_hover_color'
								)
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
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img',
							'property' => 'border',
							'unit'     => 'px'
						)
					),
					'icon_img_border_radius_njba' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'help'        => 'To display proper layout please enter proper padding to icon.',
						'default'     => array(
							'topleft'     => 5,
							'topright'    => 5,
							'bottomleft'  => 5,
							'bottomright' => 5
						),
						'options'     => array(
							'topleft'     => array(
								'placeholder' => __( 'Top-Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img',
									'property' => 'border-top-left-radius',
									'unit'     => 'px'
								)
							),
							'topright'    => array(
								'placeholder' => __( 'Top-Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img',
									'property' => 'border-top-right-radius',
									'unit'     => 'px'
								)
							),
							'bottomright' => array(
								'placeholder' => __( 'Bottom-Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img',
									'property' => 'border-bottom-right-radius',
									'unit'     => 'px'
								)
							),
							'bottomleft'  => array(
								'placeholder' => __( 'Bottom-Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img',
									'property' => 'border-bottom-left-radius',
									'unit'     => 'px'
								)
							),
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
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img',
							'property' => 'border-style',
						)
					),
					'img_icon_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'img_icon_border_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'default'    => '5b5b5b',
						'show_reset' => true,
					),
				)
			),
			'icon_colors'    => array( // Section
				'title'  => __( 'Colors', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					/* Icon Color */
					'icon_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img i',
							'property' => 'color',
						)
					),
					'icon_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Icon Hover Color', 'bb-njba' ),
						'default'    => '5b5b5b',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none',
						)
					),
					'icon_transition'  => array(
						'type'        => 'text',
						'label'       => __( 'Transition', 'bb-njba' ),
						'default'     => '0.3',
						'description' => 'sec',
						'maxlength'   => '3',
						'size'        => '5',
					)
				)
			),
			'common_style'   => array( // Section
				'title'  => __( '', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'img_icon_padding'            => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 5,
							'right'  => 5,
							'bottom' => 5,
							'left'   => 5
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
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
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'margin-top',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'margin-right',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'margin-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-icon-img i',
									'property' => 'margin-left',
									'unit'     => 'px'
								),
							)
						)
					),
					/* Background Color Dependent on Icon Style **/
					'img_icon_bg_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-icon-img',
							'property' => 'background'
						)
					),
					'img_icon_bg_color_opc'       => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'img_icon_bg_hover_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type' => 'none',
						)
					),
					'img_icon_bg_hover_color_opc' => array(
						'type'        => 'text',
						'label'       => __( 'Background Hover Color Opacity', 'bb-njba' ),
						'default'     => '',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
					),
				)
			)
		)
	)
) );
?>
