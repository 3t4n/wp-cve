<?php

/**
 * @class NJBA_Logo_Grid_Module
 */
class NJBA_Logo_Grid_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Logo Grid & Carousel ', 'bb-njba' ),
			'description'     => __( 'Addon to display logos.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'carousel' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-logo-grid-carousel/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-logo-grid-carousel/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Defaults to false and can be omitted.
			'icon'            => 'slides.svg',
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'jquery-bxslider' );
		$this->add_css( 'font-awesome' );
		$this->add_js( 'jquery-bxslider' );
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

	public function delete() {
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_Logo_Grid_Module', array(
	'logos'      => array( // Tab
		'title'    => __( 'Logos', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'logos' => array(
						'type'         => 'form',
						'label'        => __( 'Logo', 'bb-njba' ),
						'form'         => 'njba_logospanel_form', // ID from registered form below
						'preview_text' => 'logo_title', // Name of a field to use for the preview text
						'multiple'     => true
					),
				)
			)
		)
	),
	'general'    => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'           => array( // Section
				'title'  => __( '', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'logos_layout_view' => array(
						'type'    => 'select',
						'label'   => __( 'Layout Type', 'bb-njba' ),
						'default' => 'grid',
						'options' => array(
							'grid'     => __( 'Grid', 'bb-njba' ),
							'carousel' => __( 'Carousel', 'bb-njba' )
						),
						'toggle'  => array(
							'carousel' => array(
								'sections' => array( 'carousel_settings', 'carousel_section', 'arrow_nav', 'dot_nav' ),
							),
							'grid'     => array(
								'sections' => array( 'grid' ),
							)
						),
					),
					'img_max_width'     => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Logos Width ', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '170',
							'medium'  => '',
							'small'   => ''
						),
						'size'        => '5',
					),
					'show_logo_title'   => array(
						'type'    => 'select',
						'label'   => __( 'Show Logo Title', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'bb-njba' ),
							'yes' => __( 'Yes', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'tabs' => array( 'typography' ),
							),
							'no'  => array(
								'tabs' => array(),
							)
						),
					),

				)
			),
			'grid'              => array( // Section
				'title'  => __( 'Grid Settings', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'show_col' => array(
						'type'    => 'select',
						'label'   => __( 'Show on Row', 'bb-njba' ),
						'default' => 4,
						'options' => array(
							'12' => '1',
							'6'  => '2',
							'4'  => '3',
							'3'  => '4',
							'2'  => '6',
						),
						'preview' => array(
							'type' => 'none'
						)
					),
				)
			),
			'carousel_settings' => array( // Section
				'title'  => __( 'Carousel Settings', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'autoplay'        => array(
						'type'    => 'select',
						'label'   => __( 'Autoplay', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
					),
					'hover_pause'     => array(
						'type'    => 'select',
						'label'   => __( 'Pause On Hover', 'bb-njba' ),
						'default' => '1',
						'help'    => __( 'Pause when mouse hovers over slider' ),
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' ),
						),
					),
					'transition'      => array(
						'type'    => 'select',
						'label'   => __( 'Mode', 'bb-njba' ),
						'default' => 'horizontal',
						'options' => array(
							'horizontal' => _x( 'Horizontal', 'Transition type.', 'bb-njba' ),
							'vertical'   => _x( 'Vertical', 'Transition type.', 'bb-njba' ),
							'fade'       => __( 'Fade', 'bb-njba' )
						),
					),
					'pause'           => array(
						'type'        => 'text',
						'label'       => __( 'Delay', 'bb-njba' ),
						'default'     => '4',
						'maxlength'   => '4',
						'size'        => '5',
						'description' => _x( 'sec', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'bb-njba' )
					),
					'speed'           => array(
						'type'        => 'text',
						'label'       => __( 'Transition Speed', 'bb-njba' ),
						'default'     => '0.5',
						'maxlength'   => '4',
						'size'        => '5',
						'description' => _x( 'sec', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'bb-njba' ),
						'preview'     => array(
							'type' => 'none'
						)
					),
					'loop'            => array(
						'type'    => 'select',
						'label'   => __( 'Loop', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' ),
						),
					),
					'adaptive_height' => array(
						'type'    => 'select',
						'label'   => __( 'Fixed Height', 'bb-njba' ),
						'default' => 'yes',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' )
						),
						'help'    => __( 'Fix height to the tallest item.', 'bb-njba' )
					)
				)
			),
			'carousel_section'  => array( // Section
				'title'  => '',
				'fields' => array( // Section Fields
					'max_slides'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Slides Per Row' ),
						'default' => array(
							'desktop' => '3',
							'medium'  => '2',
							'small'   => '1',
						),
						'size'    => '5',
					),
					'slide_margin' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Margin Between Slides ', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '0',
							'medium'  => '0',
							'small'   => '0',
						),
						'size'        => '5',
					),
				)
			),
			'arrow_nav'         => array( // Section
				'title'  => '',
				'fields' => array( // Section Fields
					'arrows'              => array(
						'type'    => 'select',
						'label'   => __( 'Show Arrows', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'arrows_size', 'arrow_background', 'arrow_color', 'arrow_border_radius' )
							)
						),
					),
					'arrows_size'         => array(
						'type'        => 'text',
						'size'        => '5',
						'maxlength'   => '2',
						'label'       => __( 'Arrow Size', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for Arrow font size. Such as: "14 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.bx-wrapper .bx-controls-direction a',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'arrow_background'    => array(
						'type'       => 'color',
						'label'      => __( 'Arrow Background', 'bb-njba' ),
						'default'    => 'dddddd',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-wrapper .bx-controls-direction a',
							'property' => 'background'
						)
					),
					'arrow_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Arrow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-wrapper .bx-controls-direction a',
							'property' => 'color'
						)
					),
					'arrow_border_radius' => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Arrow Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.bx-wrapper .bx-controls-direction a',
							'property' => 'border-radius'
						)
					),
				)
			),
			'dot_nav'           => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'dots'             => array(
						'type'    => 'select',
						'label'   => __( 'Show Dots', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' ),
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'dot_color', 'active_dot_color' )
							)
						)
					),
					'dot_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Dots Color', 'bb-njba' ),
						'default'    => '999999',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-pager.bx-default-pager a.bx-pager-link',
							'property' => 'background'
						)
					),
					'active_dot_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Dot Color', 'bb-njba' ),
						'default'    => '999999',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.bx-pager.bx-default-pager a.bx-pager-link.active ',
							'property' => 'background'
						)
					),
				)
			)
		)
	),
	'style'      => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general'              => array( // Section
				'title'  => 'Column Settings', // Section Title
				'fields' => array( // Section Fields
					'col_height'              => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'placeholder' => 'auto',
						'default'     => array(
							'desktop' => '230',
							'medium'  => '',
							'small'   => ''
						),
						'size'        => '5',
					),
					'col_bg_color'            => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dddddd',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'background-color',
						)
					),
					'col_bg_opc'              => array(
						'type'        => 'text',
						'label'       => __( 'Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '%',
						'placeholder' => '100',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'opacity',
						)
					),
					'col_hover_bg_color'      => array(
						'type'       => 'color',
						'label'      => __( 'Hover Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dddddd',
						'preview'    => array(
							'type' => 'none'
						)
					),
					'col_hover_bg_opc'        => array(
						'type'        => 'text',
						'label'       => __( 'Hover Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'placeholder' => '100',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '%',
						'preview'     => array(
							'type' => 'none',
						)
					),
					'col_border_style'        => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'col_border_width', 'col_border_color', 'col_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'col_border_width', 'col_border_color', 'col_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'col_border_width', 'col_border_color', 'col_border_radius' )
							),
							'double' => array(
								'fields' => array( 'col_border_width', 'col_border_color', 'col_border_radius' )
							)
						)
					),
					'col_border_width'        => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '5',
						'default'     => '1',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),
					'col_border_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-color',
						)
					),
					'col_border_radius'       => array(
						'type'        => 'text',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '5',
						'default'     => '0',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-radius',
							'unit'     => 'px'
						)
					),
					'col_hover_border_style'  => array(
						'type'    => 'select',
						'label'   => __( 'Hover Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'col_hover_border_width', 'col_hover_border_color', 'col_hover_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'col_hover_border_width', 'col_hover_border_color', 'col_hover_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'col_hover_border_width', 'col_hover_border_color', 'col_hover_border_radius' )
							),
							'double' => array(
								'fields' => array( 'col_hover_border_width', 'col_hover_border_color', 'col_hover_border_radius' )
							)
						)
					),
					'col_hover_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Hover Border Width', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '5',
						'default'     => '1',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-width',
							'unit'     => 'px'
						)
					),
					'col_hover_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Hover Border Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-color',
						)
					),
					'col_hover_border_radius' => array(
						'type'        => 'text',
						'label'       => __( 'Hover Border Radius', 'bb-njba' ),
						'description' => 'px',
						'maxlength'   => '3',
						'size'        => '5',
						'default'     => '0',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-inner',
							'property' => 'border-radius',
							'unit'     => 'px'
						)
					),
					'col_out_padding'         => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding Outside', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => '',
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-out-side',
									'property' => 'padding-top',
									'unit'     => 'px'
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-out-side',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-out-side',
									'property' => 'padding-left',
									'unit'     => 'px'
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'type'     => 'css',
									'selector' => '.njba-out-side',
									'property' => 'padding-right',
									'unit'     => 'px'
								),
							)
						),
					),
					'col_inner_padding'       => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding Inside', 'bb-njba' ),
						'description' => __( 'px', 'bb-njba' ),
						'default'     => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => '',
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-logo-inner',
									'property' => 'padding-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-logo-inner',
									'property' => 'padding-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-logo-inner',
									'property' => 'padding-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-logo-inner',
									'property' => 'padding-right',
								),
							)
						)
					),
				)
			),
			'logo_grid_logo_style' => array( // Section
				'title'  => __( 'Logo', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'logo_grid_grayscale'       => array(
						'type'    => 'select',
						'label'   => __( 'Logo Color', 'bb-njba' ),
						'default' => 'original',
						'options' => array(
							'original'  => __( 'Original', 'bb-njba' ),
							'grayscale' => __( 'Greyscale', 'bb-njba' )
						),
					),
					'logo_grid_grayscale_hover' => array(
						'type'    => 'select',
						'label'   => __( 'On Hover', 'bb-njba' ),
						'default' => 'original',
						'options' => array(
							'original'  => __( 'Original', 'bb-njba' ),
							'grayscale' => __( 'Greyscale', 'bb-njba' )
						),
					),

				)
			),
		)
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'logo_title_section' => array(
				'title'  => __( 'Title', 'bb-njba' ),
				'fields' => array( // Section Fields
					'title_tag'       => array(
						'type'    => 'select',
						'label'   => __( 'Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1' => __( 'H1', 'bb-njba' ),
							'h2' => __( 'H2', 'bb-njba' ),
							'h3' => __( 'H3', 'bb-njba' ),
							'h4' => __( 'H4', 'bb-njba' ),
							'h5' => __( 'H5', 'bb-njba' ),
							'h6' => __( 'H6', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'title_alignment' => array(
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
							'selector' => '.njba-logo-title',
							'property' => 'text-align'
						)
					),
					'title_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-logo-title'
						)
					),
					'title_font_size' => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'title_line_height' => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'maxlength'   => '2',
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'label'       => __( 'Line Height', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'title_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-logo-title',
							'property' => 'color',
						)
					),
					'title_margin'    => array(
						'type'        => 'text',
						'label'       => __( 'Margin Top', 'bb-njba' ),
						'default'     => '10',
						'description' => 'px',
						'size'        => '5',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-logo-title',
							'property' => 'margin-top',
						)
					),
				)
			),
		)
	)
) );
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'njba_logospanel_form', array(
	'title' => __( 'Add Logo', 'bb-njba' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'logo_details' => array(
					'title'  => '',
					'fields' => array(
						'logo_title'  => array(
							'type'    => 'text',
							'label'   => __( 'Title', 'bb-njba' ),
							'preview' => array(
								'type' => 'none'
							)
						),
						'logo'        => array(
							'type'        => 'photo',
							'label'       => __( 'Select Logo', 'bb-njba' ),
							'show_remove' => true
						),
						'url'         => array(
							'type'        => 'link',
							'label'       => __( 'Link', 'fl-builder' ),
							'placeholder' => 'http://www.example.com',
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
						),
					),
				),
			)
		)
	)
) );
