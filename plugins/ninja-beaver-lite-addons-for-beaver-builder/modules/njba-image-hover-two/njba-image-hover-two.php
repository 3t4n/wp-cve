<?php

/**
 * @class NJBA_Image_Hover_Two_Module
 */
class NJBA_Image_Hover_Two_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Image Hover 2', 'bb-njba' ),
			'description'     => __( 'Addon to display image hover 2.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-image-hover-two/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-image-hover-two/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Defaults to false and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered

		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-image-hover-two-frontend', NJBA_MODULE_URL . 'modules/njba-image-hover-two/css/frontend.css' );
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

	/**
	 * @param $style
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function njbaImageHoverCaptionModule( $style ) {
		if ( $this->settings->caption ) :
			$html = $this->settings->caption;
		endif;

		return $html;
	}

	/**
	 * @param $style
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function njbaImageHoverSubCaptionModule( $style ) {
		if ( $this->settings->sub_caption ) :
			$html = $this->settings->sub_caption;
		endif;

		return $html;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Image_Hover_Two_Module', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'style'       => array(
						'type'    => 'select',
						'label'   => __( 'Photo Style', 'bb-njba' ),
						'default' => 'simple',
						'options' => array(
							'1' => __( 'Style 1', 'bb-njba' ),
							'2' => __( 'Style 2', 'bb-njba' ),
							'3' => __( 'Style 3', 'bb-njba' ),
							'4' => __( 'Style 4', 'bb-njba' ),
							'5' => __( 'Style 5', 'bb-njba' ),

						),
						'toggle'  => array(
							'1' => array(
								'sections' => array( 'caption', 'sub_caption' ),
								'fields'   => array(
									'caption',
									'sub_caption',
									'margin1',
									'caption_alignment',
									'sub_caption_alignment',
									'caption_padding',
									'sub_caption_padding',
									'background_color',
									'background_color_opacity',
									'background_hover_color',
									'background_hover_color_opacity'
								)
							),
							'2' => array(
								'sections' => array( 'caption' ),
								'fields'   => array(
									'caption',
									'padding1',
									'caption_alignment',
									'sub_caption_alignment',
									'caption_padding',
									'background_color',
									'background_color_opacity',
									'background_hover_color',
									'background_hover_color_opacity'
								)
							),
							'3' => array(
								'sections' => array( 'caption', 'sub_caption' ),
								'fields'   => array(
									'caption',
									'sub_caption',
									'caption_margin',
									'caption_alignment',
									'sub_caption_alignment',
									'sub_caption_margin',
									'background_blur'
								)
							),
							'4' => array(
								'sections' => array( 'border_hover_style_section', 'caption', 'sub_caption' ),
								'fields'   => array( 'caption', 'sub_caption' )
							),
							'5' => array(
								'sections' => array( 'caption', 'sub_caption' ),
								'fields'   => array(
									'caption',
									'sub_caption',
									'caption_alignment',
									'sub_caption_alignment',
									'background_hover_color',
									'background_hover_color_opacity',
									'caption_padding',
									'sub_caption_padding'
								)
							)
						),
					),
					'photo'       => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true,
					),
					'caption'     => array(
						'type'    => 'text',
						'label'   => __( 'Main Caption', 'bb-njba' ),
						'default' => 'Main Caption',
					),
					'sub_caption' => array(
						'type'    => 'text',
						'label'   => __( 'Sub Caption', 'bb-njba' ),
						'default' => 'Sub Caption',
					),
					'link_type'   => array(
						'type'    => 'select',
						'label'   => __( 'Link Type', 'bb-njba' ),
						'options' => array(
							''    => _x( 'None', 'Link type.', 'bb-njba' ),
							'url' => __( 'URL', 'bb-njba' ),
						),
						'toggle'  => array(
							''    => array(),
							'url' => array(
								'fields' => array( 'link_url', 'link_target' )
							)
						),
						'help'    => __( 'Link type applies to how the image should be linked on click. You can choose a specific URL.', 'bb-njba' ),
					),
					'link_url'    => array(
						'type'  => 'link',
						'label' => __( 'Link URL', 'bb-njba' ),

					),
					'link_target' => array(
						'type'    => 'select',
						'label'   => __( 'Link Target', 'bb-njba' ),
						'default' => '_self',
						'options' => array(
							'_self'  => __( 'Same Window', 'bb-njba' ),
							'_blank' => __( 'New Window', 'bb-njba' )
						),
					),
					'transition'  => array(
						'type'        => 'text',
						'label'       => __( 'Transition', 'bb-njba' ),
						'default'     => 0.5,
						'size'        => '5',
						'description' => 's'
					)
				)
			)
		)
	),
	'box'     => array(
		'title'    => __( 'Box Style', 'bb-njba' ),
		'sections' => array(
			'box_style_section'          => array(
				'title'  => __( 'Box', 'bb-njba' ),
				'fields' => array(
					'margin1'                        => array(
						'type'        => 'text',
						'label'       => __( 'Margin', 'bb-njba' ),
						'size'        => '5',
						'description' => 'px',
					),
					'padding1'                       => array(
						'type'        => 'text',
						'label'       => __( 'Padding', 'bb-njba' ),
						'size'        => '5',
						'description' => 'px',
					),
					'background_color'               => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'background_color_opacity'       => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'default'     => '50',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '100'
					),
					'background_hover_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'background_hover_color_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'Background Hover Color Opacity', 'bb-njba' ),
						'default'     => '50',
						'description' => '%',
						'maxlength'   => '3',
						'size'        => '5',
						'placeholder' => '100'
					),
					'background_blur'                => array(
						'type'        => 'text',
						'label'       => __( 'Blur', 'bb-njba' ),
						'size'        => '5',
						'description' => 'px'
					),
					'box_shadow'                     => array(
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
					'box_shadow_color'               => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000'
					)
				)
			),
			'border_style_section'       => array(
				'title'  => __( 'Border', 'bb-njba' ),
				'fields' => array(
					'box_border_style'   => array(
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
								'fields' => array( 'box_border_width', 'box_border_radius', 'border_color', 'hover_border_color' ),
							),
							'dotted' => array(
								'fields' => array( 'box_border_width', 'box_border_radius', 'border_color', 'hover_border_color' ),
							),
							'dashed' => array(
								'fields' => array( 'box_border_width', 'box_border_radius', 'border_color', 'hover_border_color' ),
							),
							'double' => array(
								'fields' => array( 'box_border_width', 'box_border_radius', 'border_color', 'hover_border_color' ),
							),
						),
					),
					'box_border_width'   => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'box_border_radius'  => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
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
					'border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'hover_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Hover Border Color', 'bb-njba' ),
						'default'    => 'a0a0a0 ',
						'show_reset' => true,
					)
				)
			),
			'border_hover_style_section' => array(
				'title'  => __( ' Hover Border', 'bb-njba' ),
				'fields' => array(
					'box_Hover_border_style'  => array(
						'type'    => 'select',
						'label'   => __( 'Hover Border Style', 'bb-njba' ),
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
								'fields' => array( 'box_hover_border_width', 'box_hover_border_radius' ),
							),
							'dotted' => array(
								'fields' => array( 'box_hover_border_width', 'box_hover_border_radius' ),
							),
							'dashed' => array(
								'fields' => array( 'box_hover_border_width', 'box_hover_border_radius' ),
							),
							'double' => array(
								'fields' => array( 'box_hover_border_width', 'box_hover_border_radius' ),
							),
						),
					),
					'box_hover_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Hover Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'box_hover_border_radius' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Hover Border Radius', 'bb-njba' ),
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
			)
		)
	),
	'caption' => array( // Tab
		'title'    => __( 'Typography', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'caption'     => array(
				'title'  => __( 'Main Caption', 'bb-njba' ),
				'fields' => array(
					'caption_font'       => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => ''
						)
					),
					'caption_font_size'  => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '22',
							'medium'  => '20',
							'small'   => '16'
						)
					),
					'caption_font_color' => array(
						'type'       => 'color',
						'label'      => __( 'Font Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'caption_alignment'  => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						)
					),
					'caption_margin'	 => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => '%',
						'default'     => array(
							'top'    => 30,
							'right'  => 30,
							'bottom' => 30,
							'left'   => 30,
						),
						'options'     => array(
							'top'    => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Top', 'bb-njba' ),
								'tooltip'     => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-top',
									'unit'     => '%'
								)
							),
							'bottom' => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'tooltip'     => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-bottom',
									'unit'     => '%'
								)
							),
							'left'   => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Left', 'bb-njba' ),
								'tooltip'     => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-left',
									'unit'     => '%'
								)
							),
							'right'  => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Right', 'bb-njba' ),
								'tooltip'     => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-right',
									'unit'     => '%'
								)
							)
						)
					),
					'caption_padding'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => '',
						'default'     => array(
							'top'    => 30,
							'right'  => 15,
							'bottom' => 15,
							'left'   => 15,
						),
						'options'     => array(
							'top'    => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Top', 'bb-njba' ),
								'tooltip'     => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'bottom' => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'tooltip'     => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								)
							),
							'left'   => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Left', 'bb-njba' ),
								'tooltip'     => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-left',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Right', 'bb-njba' ),
								'tooltip'     => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-right',
									'unit'     => 'px'
								)
							)
						)
					),
				)
			),
			'sub_caption' => array(
				'title'  => __( 'Sub Caption', 'bb-njba' ),
				'fields' => array(
					'sub_caption_font'       => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'sub_caption_font_size'  => array(
						'type'    => 'njba-simplify',
						'size'    => '5',
						'label'   => __( 'Font Size', 'bb-njba' ),
						'default' => array(
							'desktop' => '18',
							'medium'  => '16',
							'small'   => '12'
						)
					),
					'sub_caption_font_color' => array(
						'type'       => 'color',
						'label'      => __( 'Font Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'sub_caption_alignment'  => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
						)
					),
					'sub_caption_margin'	 => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Margin', 'bb-njba' ),
						'description' => '%',
						'default'     => array(
							'top'    => 30,
							'right'  => 30,
							'bottom' => 30,
							'left'   => 30,
						),
						'options'     => array(
							'top'    => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Top', 'bb-njba' ),
								'tooltip'     => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-top',
									'unit'     => '%'
								)
							),
							'bottom' => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'tooltip'     => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-bottom',
									'unit'     => '%'
								)
							),
							'left'   => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Left', 'bb-njba' ),
								'tooltip'     => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-left',
									'unit'     => '%'
								)
							),
							'right'  => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Right', 'bb-njba' ),
								'tooltip'     => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '',
									'property' => 'margin-right',
									'unit'     => '%'
								)
							)
						)
					),
					'sub_caption_padding'    => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 15,
							'right'  => 15,
							'bottom' => 15,
							'left'   => 15,
						),
						'options'     => array(
							'top'    => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Top', 'bb-njba' ),
								'tooltip'     => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-top',
									'unit'     => 'px'
								)
							),
							'bottom' => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'tooltip'     => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								)
							),
							'left'   => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Left', 'bb-njba' ),
								'tooltip'     => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-left',
									'unit'     => 'px'
								)
							),
							'right'  => array(
								'maxlength'   => 3,
								'placeholder' => __( 'Right', 'bb-njba' ),
								'tooltip'     => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '',
									'property' => 'padding-right',
									'unit'     => 'px'
								)
							)
						)
					),

				)
			)
		)
	),
) );
