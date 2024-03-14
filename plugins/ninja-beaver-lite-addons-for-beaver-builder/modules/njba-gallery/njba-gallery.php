<?php

/**
 * @class NJBA_Gallery_Module
 */
class NJBA_Gallery_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Gallery', 'bb-njba' ),
			'description'     => __( 'Addon to display Image Carousel.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'creative' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-gallery/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-gallery/',
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'icon'            => 'format-gallery.svg',

		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered


		$this->add_js( 'jquery-magnificpopup' );
		$this->add_css( 'jquery-magnificpopup' );
		$this->add_js( 'njba-gallery-masonary', NJBA_MODULE_URL . 'modules/njba-gallery/js/gallery-masonary.js' );
		//$this->add_js('jquery-imagesloaded');
		$this->add_js( 'imagesloaded' );

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
FLBuilder::register_module( 'NJBA_Gallery_Module', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general'       => array(
				'title'  => '',
				'fields' => array(
					'layout'        => array(
						'type'    => 'select',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 'collage',
						'options' => array(
							'grid'     => __( 'Grid', 'bb-njba' ),
							'masonary' => __( 'Masonry', 'bb-njba' )
						),
					),
					'photos'        => array(
						'type'  => 'multiple-photos',
						'label' => __( 'Photos', 'bb-njba' )
					),
					'image_size'    => array(
						'type'    => 'select',
						'label'   => __( 'Photo Size', 'bb-njba' ),
						'default' => 'medium',
						'options' => array(
							'thumbnail' => __( 'Thumbnail', 'bb-njba' ),
							'medium'    => __( 'Medium', 'bb-njba' ),
							'full'      => __( 'Full', 'bb-njba' )
						)
					),
					'show_col'      => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Show Column' ),
						'help'    => 'Number of colums in one row',
						'default' => array(
							'desktop' => '3',
							'medium'  => '2',
							'small'   => '1',
						),
						'size'    => '5',
					),
					'photo_spacing' => array(
						'type'        => 'text',
						'label'       => __( 'Photo Spacing', 'bb-njba' ),
						'mode'        => 'padding',
						'placeholder' => '5',
						'default'     => '5',
						'size'        => '5',
						'description' => 'px',
					),
				)
			),
			'image_setting' => array(
				'title'  => __( 'Photo Settings', 'bb-njba' ),
				'fields' => array(
					'hover_effects'        => array(
						'type'    => 'select',
						'label'   => __( 'Image Hover Effect', 'bb-njba' ),
						'default' => 'zoom-in',
						'options' => array(
							'none'         => __( 'None', 'bb-njba' ),
							'rotate-left'  => __( 'Rotate Left', 'bb-njba' ),
							'rotate-right' => __( 'Rotate Right', 'bb-njba' ),
							'zoom-in'      => __( 'Zoom In', 'bb-njba' ),
							'zoom-out'     => __( 'Zoom Out', 'bb-njba' ),
						)
					),
					'transition'                    => array(
						'type'        => 'text',
						'label'       => __( 'Transition', 'bb-njba' ),
						'size'        => '5',
						'description' => 'sec',
						'default'     => '0.3',
						'help'        => __( 'set image transition', 'bb-njba' ),
					),
					'click_action'         => array(
						'type'    => 'select',
						'label'   => __( 'Click Action', 'bb-njba' ),
						'default' => 'lightbox',
						'options' => array(
							'none'     => __( 'None', 'bb-njba' ),
							'lightbox' => __( 'Lightbox', 'bb-njba' )
						),
						'toggle'  => array(
							'lightbox' => array(
								'fields' => array( 'hover_icon', 'icon_size', 'icon_color' )
							)
						)
					),
					'hover_icon'	  => array(
						'type'        => 'icon',
						'label'       => __( 'Hover Icon', 'bb-njba' ),
						'show_remove' => true,
						'default'     => 'fa fa-search-plus'
					),
					'icon_size'        => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Icon Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '16',
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
					'icon_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Icon Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'box_show_border'        => array(
						'type'    => 'select',
						'label'   => __( 'Display Box Border', 'bb-njba' ),
						'default' => 'no',
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array(
									'box_border_width',
									'box_border_radius',
									'box_border_style',
									'box_border_color',
								)
							)
						)
					),
					'box_border_width'       => array(
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
					'box_border_radius' => array(
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
					'box_border_style'       => array(
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
					'box_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'ffffff',
						'show_reset' => true,
					),
					'box_shadow'      => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'vertical'   => 0,
							'horizontal' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'vertical'   => array(
								'placeholder' => __( 'Vertical', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-h'
							),
							'horizontal' => array(
								'placeholder' => __( 'Horizontal', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-v'
							),
							'blur'       => array(
								'placeholder' => __( 'Blur', 'bb-njba' ),
								'icon'        => 'fa fa-circle-thin'
							),
							'spread'     => array(
								'placeholder' => __( 'Spread', 'bb-njba' ),
								'icon'        => 'fa fa-circle'
							)

						)
					),
					'box_shadow_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Shadow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'overly_color'         => array(
						'type'       => 'color',
						'label'      => __( 'Hover Background', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-image-box-overlay',
							'property' => 'background-color',
						)
					),
					'overly_color_opacity' => array(
						'type'        => 'text',
						'label'       => __( 'Opacity', 'bb-njba' ),
						'default'     => '50',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-image-box-overlay',
							'property' => 'background-color',
						)
					),
				)
			),
		)
	),
) );
