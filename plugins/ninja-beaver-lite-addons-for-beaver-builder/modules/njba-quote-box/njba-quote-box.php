<?php

/**
 * @class NJBA_QuoteBox_Module
 */
class NJBA_QuoteBox_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Quote Box', 'bb-njba' ),
			'description'     => __( 'Addon to display quote box.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-quote-box/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-quote-box/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'icon'            => 'format-quote.svg',
			'partial_refresh' => true, // Set this to true to enable partial refresh.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'font-awesome' );
	}

	// For Post Image
	public function njba_profile_image() {
		$photo = $this->settings->photo;
		echo '<div class="njba-quote-image">';
		$quote_image = wp_get_attachment_image_src( $photo );
		if ( ! is_wp_error( $quote_image ) ) {
			$photo_src    = $quote_image[0];
			$photo_width  = $quote_image[1];
			$photo_height = $quote_image[2];
		}
		if ( $photo !== '' ) {
			echo '<img src="' . $this->settings->photo_src . '" width="' . $photo_width . '" height="' . $photo_height . '" class="njba-image-responsive"/>';
		} else {
			echo '<img src="' . NJBA_MODULE_URL . 'modules/njba-quote-box/images/placeholder.png" />';
		}
		echo '</div>';
	}

	// For Name
	public function njba_profile_name() {
		$name = $this->settings->name;
		if ( $name !== '' ) {
			echo '<h2 class="name-selector">' . $name . '</h2>';
		}
	}

	// For Designation
	public function njba_profile_designation() {
		$profile = $this->settings->profile;
		if ( $profile !== '' ) {
			echo '<h3 class="designation-selector">' . $profile . '</h3>';
		}
	}

	// For Profile Content
	public function njba_profile_content() {
		$content = $this->settings->content;
		if ( $content !== '' ) {
			echo '<h4>' . $content . '</h4>';
		}
	}

	public function njba_right_quotesign() {
		echo '<div class="njba-quote-icon-two">
                <i class="fa fa-quote-right"></i>
              </div>';
	}

	public function njba_left_quotesign() {
		echo '<div class="njba-quote-icon">
                <i class="fa fa-quote-left"></i>
              </div>';
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

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_QuoteBox_Module', array(
	'quotebox'   => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'quotebox' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'quotebox_layout' => array(
						'type'    => 'select',
						'label'   => __( 'Layout Type', 'bb-njba' ),
						'default' => 1,
						'options' => array(
							'1'  => 'Style 1',
							'2'  => 'Style 2',
							'3'  => 'Style 3',
							'4'  => 'Style 4',
							'5'  => 'Style 5',
							'6'  => 'Style 6',
							'7'  => 'Style 7',
							'8'  => 'Style 8',
							'9'  => 'Style 9',
							'10' => 'Style 10',
							'11' => 'Style 11',
						),
						'toggle'  => array(
							'4'  => array(
								'fields'   => array( 'photo', 'box_border_radius' ),
								'sections' => array( 'image_borders' )
							),
							'5'  => array(
								'fields'   => array( 'photo', 'box_border_radius' ),
								'sections' => array( 'image_borders', )
							),
							'6'  => array(
								'fields'   => array( 'photo', 'image_bg', 'box_border_radius' ),
								'sections' => array( 'image_borders' )
							),
							'7'  => array(
								'fields'   => array( 'photo', 'highlight_border', 'box_border_radius' ),
								'sections' => array( 'image_borders' )
							),
							'8'  => array(
								'fields'   => array( 'photo', 'highlight_border', 'box_border_radius' ),
								'sections' => array( 'image_borders' )
							),
							'1'  => array(
								'fields'   => array( 'box_border_radius' ),
								'sections' => array( 'box_borders' ),
							),
							'2'  => array(
								'fields'   => array( 'box_border_radius' ),
								'sections' => array( 'box_borders' )
							),
							'3'  => array(
								'fields'   => array( 'box_border_radius' ),
								'sections' => array( 'box_borders' )
							),
							'9'  => array(
								'fields'   => array( 'quote_sign_bg_color', 'quote_sign_padding' ),
								'sections' => array()
							),
							'10' => array(
								'fields'   => array( 'quote_sign_bg_color', 'quote_sign_padding', 'quote_shape_height' ),
								'sections' => array()
							),
							'11' => array(
								'fields'   => array( 'quote_sign_bg_color', 'quote_boxcontent_rotate', 'quote_box_rotate' ),
								'sections' => array()
							)
						)
					),
					'name'            => array(
						'type'    => 'text',
						'label'   => __( 'Name', 'bb-njba' ),
						'default' => 'Name',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.name-selector'
						)
					),
					'profile'         => array(
						'type'    => 'text',
						'label'   => __( 'Profile', 'bb-njba' ),
						'default' => 'Profile',
						'preview' => array(
							'type'     => 'text',
							'selector' => '.designation-selector'
						)
					),
					'photo'           => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'bb-njba' ),
						'show_remove' => true
					),
					'content'         => array(
						'type'          => 'editor',
						'label'         => 'Content',
						'default'       => 'Enter description here.',
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.njba-quote-box-content h4'
						),
						'media_buttons' => false,
						'rows'          => 8,
					),
				)
			),
		)
	),
	'styles'     => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'box_style'     => array(
				'title'  => __( 'Content Box', 'bb-njba' ),
				'fields' => array( // Section Fields
					'box_border_radius'       => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main.layout-1 .njba-quote-box,.njba-quote-box-main.layout-2 .njba-quote-box',
							'property' => 'border-radius',
						)
					),
					'content_bg'              => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
					),
					'quote_sign_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Quote Icon Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
					),
					'quote_sign_bg_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Quote Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
					),
					'quote_shape_height'      => array(
						'type'        => 'text',
						'default'     => '',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Shape Height', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
					),
					'quote_box_rotate'        => array(
						'type'        => 'text',
						'default'     => '-8',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Box Rotation', 'bb-njba' ),
						'description' => _x( 'deg', 'Value unit for border radius. Such as: "5 deg"', 'bb-njba' ),
					),
					'quote_boxcontent_rotate' => array(
						'type'        => 'text',
						'default'     => '8',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Content Box Rotation', 'bb-njba' ),
						'description' => _x( 'deg', 'Value unit for border radius. Such as: "5 deg"', 'bb-njba' ),
					),
					'quote_sign_padding'      => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Quote Box Padding', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
					),
					'highlight_border'        => array(
						'type'       => 'color',
						'label'      => __( 'Highlight Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
					),
					'content_box_padding'     => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Padding', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'help'    => 'This padding will be applied inside the box container.',
						'options' => array(
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
					'content_padding'         => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Content Padding', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'help'    => 'This padding will be applied outside description content.',
						'options' => array(
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
				),
			),
			'box_borders'   => array(
				'title'  => __( 'Box Border', 'bb-njba' ),
				'fields' => array( // Section Fields
					'box_border_style' => array(
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
								'fields' => array( 'box_border_width', 'box_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'box_border_width', 'box_border_color' )
							),
							'dashed' => array(
								'fields' => array( 'box_border_width', 'box_border_color' )
							),
							'double' => array(
								'fields' => array( 'box_border_width', 'box_border_color' )
							),
						)
					),
					'box_border_width' => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'box_border_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dddddd'
					),
				)
			),
			'image_borders' => array(
				'title'  => __( 'Image Box', 'bb-njba' ),
				'fields' => array( // Section Fields
					'image_bg'            => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main.layout-3 .njba-quote-box',
							'property' => 'background-color',
						)
					),
					'image_border_style'  => array(
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
								'fields' => array( 'image_border_width', 'image_border_color', 'image_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'image_border_width', 'image_border_color', 'image_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'image_border_width', 'image_border_color', 'image_border_radius' )
							),
							'double' => array(
								'fields' => array( 'image_border_width', 'image_border_color', 'image_border_radius' )
							),
						)
					),
					'image_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'image_border_radius' => array(
						'type'        => 'text',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'default'     => '50',
						'size'        => '5',
						'description' => _x( '%', 'Value unit for spacer width. Such as: "10%"', 'bb-njba' )

					),
					'image_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dddddd'
					),
				)
			),
		)
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'name_fonts'    => array(
				'title'  => __( 'Name', 'bb-njba' ),
				'fields' => array(
					'name_alignment' => array(
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
							'selector' => '.njba-quote-box-main h2',
							'property' => 'text-align'
						)
					),
					'name_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-quote-box-main h2',
						)
					),
					'name_font_size' => array(
						'type'        => 'njba-simplify',
						'default'     => array(
							'desktop' => '14',
							'medium'  => '13',
							'small'   => '12',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h2',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'name_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h2',
							'property' => 'color',
						)
					),
					'name_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'bottom' => '',
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),

						)
					),
				)
			),
			'profile_fonts' => array(
				'title'  => __( 'Profile', 'bb-njba' ),
				'fields' => array(
					'profile_alignment' => array(
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
							'selector' => '.njba-quote-box-main h3',
							'property' => 'text-align'
						)
					),
					'profile_font'      => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-quote-box-main h3',
						)
					),
					'profile_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '13',
							'medium'  => '12',
							'small'   => '11',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h3',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'profile_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h3',
							'property' => 'color',
						)
					),
					'profile_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'bottom' => '',

						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
						)
					),
				)
			),
			'content_fonts' => array(
				'title'  => __( 'Content', 'bb-njba' ),
				'fields' => array(
					'content_alignment' => array(
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
							'selector' => '.njba-quote-box-main h4',
							'property' => 'text-align'
						)
					),
					'content_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-quote-box-main h4',
						)
					),
					'content_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '17',
							'medium'  => '16',
							'small'   => '15',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'description' => 'Please Enter Value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h4',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'content_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-main h4',
							'property' => 'color',
						)
					),
					'content_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'bottom' => '',

						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
						)
					),
				),
			),
		)
	)
) );
