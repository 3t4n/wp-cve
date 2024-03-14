<?php

/**
 * @class NJBA_Testimonials_Module
 */
class NJBA_Testimonials_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Testimonials', 'bb-njba' ),
			'description'     => __( 'Addon to display testimonials.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'carousel' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-testimonials/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-testimonials/',
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
		$this->add_css( 'jquery-bxslider' );
		$this->add_css( 'font-awesome' );
		$this->add_css( 'njba-testimonials-fields', NJBA_MODULE_URL . 'modules/njba-testimonials/css/fields.css' );
		$this->add_css( 'njba-testimonials-frontend', NJBA_MODULE_URL . 'modules/njba-testimonials/css/frontend.css' );
		$this->add_js( 'jquery-bxslider' );
	}

	/**
	 * For Post Image
	 *
	 * @param $i
	 *
	 * @since 1.0.0
	 */
	public function njba_profile_image_render( $i ) {
		$photo            = $this->settings->testimonials[ $i ]->photo;
		$testimoals_image = wp_get_attachment_image_src( $photo );
		if ( ! is_wp_error( $testimoals_image ) ) {
			$photo_src = $testimoals_image[0];
			if ( $this->settings->image_size === '' ) {
				$photo_height = 'width="' . $testimoals_image[2] . '"';
				$photo_width  = 'height="' . $testimoals_image[1] . '"';
			} else {
				$photo_height = '';
				$photo_width  = '';
			}

		}
		echo '<div class="njba-testimonial-image" itemscope itemprop="image" itemtype="http://schema.org/ImageObject">';
		if ( ! empty( $photo ) ) {
			echo '<img src="' . $this->settings->testimonials[ $i ]->photo_src . '"  ' . $photo_width . '  ' . $photo_height . ' />';
		} else {
			echo '<img src="' . NJBA_MODULE_URL . 'modules/njba-testimonials/images/placeholder.jpg" />';
		}
		echo '</div>';
	}

	/**
	 * Get Profile Name
	 *
	 * @param $i
	 *
	 * @since 1.0.0
	 */
	public function njba_profile_name( $i ) {
		$title = $this->settings->testimonials[ $i ]->title;
		if ( $title !== '' ) {
			echo '<div class="njba-testimonial-title" itemscope itemprop="audience" itemtype="http://schema.org/Audience ">' . $title . '</div>';
		}
	}

	/**
	 * Get Profile Designation
	 *
	 * @param $i
	 *
	 * @since 1.0.0
	 */
	public function njba_profile_designation( $i ) {
		$subtitle = $this->settings->testimonials[ $i ]->subtitle;
		if ( $subtitle !== '' ) {
			echo '<div class="njba-testimonial-sub-title">' . $subtitle . '</div>';
		}
	}

	/**
	 * Get Profile Content
	 *
	 * @param $i
	 *
	 * @since 1.0.0
	 */
	public function njba_profile_content( $i ) {
		$content = $this->settings->testimonials[ $i ]->testimonial;
		if ( $content !== '' ) {
			echo '<div class="njba-testimonial-content">' . $content . '</div>';
		}
	}

	/**
	 * Get Profile Ratings
	 *
	 * @param $i
	 *
	 * @since 1.0.0
	 */
	public function njba_profile_ratings( $i ) {
		$rate_show    = $this->settings->testimonials[ $i ]->rate_show;
		$profile_rate = $this->settings->testimonials[ $i ]->profile_rate;
		if ( $rate_show != 0 ) {
			echo '<div class="njba-review" itemscope itemprop="audience" itemtype="http://schema.org/Review">';
			for ( $r = 1; $r <= $profile_rate; $r ++ ) {
				echo '<i class="fa fa-star"></i>';
			}
			$notrate = ( 5 - $profile_rate );
			if ( $notrate != 0 ) {
				for ( $n = 1; $n <= $notrate; $n ++ ) {
					echo '<i class="fa fa-star-o"></i>';
				}
			}
			echo '</div>';
		}
	}

	/**
	 *Get Left Quote
	 *
	 * @since 1.0.0
	 */
	public function njba_left_quotesign() {
		if ( $this->settings->show_quote === 'yes' ) {
			echo '<div class="njba-testimonial-quote-icon">
                    	<i class="fa fa-quote-left"></i>
                  </div>';
		}
	}

	/**
	 *Get Right Quote
	 * @since 1.0.0
	 */
	public function njba_right_quotesign() {
		if ( $this->settings->show_quote === 'yes' ) {
			echo '<div class="njba-testimonial-quote-icon-two">
                    	<i class="fa fa-quote-right"></i>
                  </div>';
		}
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
FLBuilder::register_module( 'NJBA_Testimonials_Module', array(
	'general'      => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'          => array( // Section
				'title'  => __( 'Heading', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'heading' => array(
						'type'    => 'text',
						'default' => __( 'Testimonials', 'bb-njba' ),
						'label'   => __( 'Main Title', 'bb-njba' ),
						'preview' => array(
							'type'     => 'text',
							'selector' => '.njba-testimonial-heading h1'
						)
					),
				)
			),
			'slider'           => array( // Section
				'title'  => __( 'Settings', 'bb-njba' ), // Section Title
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
						'label'   => __( 'Pause on hover', 'bb-njba' ),
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
						'description' => _x( 'sec', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'bb-njba' )
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
			'carousel_section' => array( // Section
				'title'  => 'Carousel Settings',
				'fields' => array( // Section Fields
					'max_slides'   => array(
						'type'    => 'njba-simplify',
						'label'   => __( 'Testimonial Per Row' ),
						'default' => array(
							'desktop' => '1',
							'medium'  => '1',
							'small'   => '1',
						),
						'size'    => '5',
					),
					'slide_margin' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Testimonial Margin ', 'bb-njba' ),
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
			'arrow_nav'        => array( // Section
				'title'  => 'Arrow Settings',
				'fields' => array( // Section Fields
					'arrows'          => array(
						'type'    => 'select',
						'label'   => __( 'Show Arrows', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'arrows_size', 'arrow_color', 'arrow_alignment' )
							)
						)
					),
					'arrows_size'     => array(
						'type'        => 'text',
						'label'       => __( 'Arrows Size', 'bb-njba' ),
						'default'     => '25',
						'size'        => '5',
						'description' => 'px',
						'help'        => __( 'Arrow Size.', 'bb-njba' ),
					),
					'arrow_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Arrow Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
					),
					'arrow_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Arrow Alignment', 'bb-njba' ),
						'default' => 'njba-center-arrow-side',
						'options' => array(
							'njba-top-arrow-side'    => __( 'Top Side Arrow', 'bb-njba' ),
							'njba-top-arrow'         => __( 'Top Center Arrow', 'bb-njba' ),
							'njba-center-arrow-side' => __( 'Center Side  Arrow', 'bb-njba' ),
							'njba-bottom-arrow-side' => __( 'Bottom Side Arrow', 'bb-njba' ),
							'njba-bottom-arrow'      => __( 'Bottom Center Arrow', 'bb-njba' ),

						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-arrow-wranjbaer',
							'property' => 'text-align'
						)
					),
				)
			),
			'dot_nav'          => array( // Section
				'title'  => 'Dot Settings', // Section Title
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
						'label'      => __( 'Dot Color', 'bb-njba' ),
						'default'    => '999999',
						'show_reset' => true,

					),
					'active_dot_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Dot Color', 'bb-njba' ),
						'default'    => '999999',
						'show_reset' => true,

					),
				)
			)
		)
	),
	'testimonials' => array( // Tab
		'title'    => __( 'Testimonials', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'testimonials' => array(
						'type'         => 'form',
						'label'        => __( 'Testimonial', 'bb-njba' ),
						'form'         => 'njba_testimonials_form', // ID from registered form below
						'preview_text' => 'title', // Name of a field to use for the preview text
						'multiple'     => true
					),
				)
			)
		)
	),
	'layouts'      => array(
		'title'    => __( 'Layout', 'bb-njba' ),
		'sections' => array(
			'layout' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'testimonial_layout' => array(
						'type'    => 'njba-radio',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 1,
						'options' => array(
							'1' => 'layout_1',
							'2' => 'layout_2',
							'3' => 'layout_3',
							'4' => 'layout_4',
							'5' => 'layout_5',
							'6' => 'layout_6',
							'7' => 'layout_7',
							'8' => 'layout_8',
							'9' => 'layout_9',
						),
						'toggle'  => array(
							'3' => array(
								'fields' => array( 'show_indicator' ),
							),
							'7' => array(
								'fields'   => array( 'show_indicator', 'show_quote', 'img_bg_color' ),
								'sections' => array( 'quote_style' ),
							),
							'8' => array(
								'fields'   => array( 'show_indicator', 'show_quote', 'img_bg_color' ),
								'sections' => array( 'quote_style' ),
							),
							'9' => array(
								'fields'   => array(
									'show_quote',
									'quote_sign_bg_color',
									'quote_sign_color',
									'quote_sign_bg_color',
									'quote_boxcontent_rotate',
									'quote_box_rotate'
								),
								'sections' => array( 'quote_style' ),
							)
						),
					),
				)
			),
		),
	),
	'styles'       => array( // Tab
		'title'    => __( 'Style', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'box_borders' => array(
				'title'  => __( 'Content Box', 'bb-njba' ),
				'fields' => array( // Section Fields
					'box_border_style'    => array(
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
								'fields' => array( 'box_border_width', 'box_border_color', 'box_border_radius' )
							),
							'dotted' => array(
								'fields' => array( 'box_border_width', 'box_border_color', 'box_border_radius' )
							),
							'dashed' => array(
								'fields' => array( 'box_border_width', 'box_border_color', 'box_border_radius' )
							),
							'double' => array(
								'fields' => array( 'box_border_width', 'box_border_color', 'box_border_radius' )
							),
						)
					),
					'box_border_width'    => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border width. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-testimonial-body.layout_1',
									'property' => 'border-width',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_2',
									'property' => 'border-width',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_3',
									'property' => 'border-width',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_4',
									'property' => 'border-width',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_5',
									'property' => 'border-width',
									'unit'     => 'px'
								),
							),
						)
					),
					'box_border_color'    => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'default'    => 'F8F8F8',
						'show_reset' => true,
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-testimonial-body.layout_1',
									'property' => 'border-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_2',
									'property' => 'border-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_3',
									'property' => 'border-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_4',
									'property' => 'border-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_5',
									'property' => 'border-color',
								),
							),
						)
					),
					'box_border_radius'   => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-testimonial-body.layout_1',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_2',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_3',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_4',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-testimonial-body.layout_5',
									'property' => 'border-radius',
									'unit'     => 'px'
								),
							),
						)
					),
					'layout_4_content_bg' => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff',
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-testimonial-body.layout_1',
									'property' => 'background-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_2',
									'property' => 'background-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_3',
									'property' => 'background-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_4',
									'property' => 'background-color',
								),
								array(
									'selector' => '.njba-testimonial-body.layout_5',
									'property' => 'background-color',
								),

							),
						)
					),
					'show_indicator'      => array(
						'type'    => 'select',
						'default' => 'yes',
						'label'   => __( 'Show Content Indicator', 'bb-njba' ),
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
					),

					'content_box_padding' => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'default'     => array(
							'top'    => 40,
							'right'  => 40,
							'bottom' => 40,
							'left'   => 40
						),
						'description' => 'px',
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'preview'     => array(
									'selector' => '.njba-profile-name',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'preview'     => array(
									'selector' => '.njba-profile-name',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'preview'     => array(
									'selector' => '.njba-profile-name',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'preview'     => array(
									'selector' => '.njba-profile-name',
									'property' => 'margin-right',
								),
							)
						)
					),
				),
			),
			'quote_style' => array(
				'title'  => __( 'Quote Box', 'bb-njba' ),
				'fields' => array( // Section Fields
					'show_quote'              => array(
						'type'    => 'select',
						'default' => 'no',
						'label'   => __( 'Show Quote', 'bb-njba' ),
						'options' => array(
							'yes' => __( 'Yes', 'bb-njba' ),
							'no'  => __( 'No', 'bb-njba' ),
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'quote_sign_color' ),
							),
							'no'  => array(
								'fields' => array( '' ),
							),
						),
					),
					'quote_sign_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Quote Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-icon i',
							'property' => 'color',
						)
					),
					'quote_sign_bg_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Quote Box Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-quote-icon',
							'property' => 'background-color',
						)
					),
					'quote_box_rotate'        => array(
						'type'        => 'text',
						'default'     => '-8',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Box Rotation', 'bb-njba' ),
						'description' => _x( 'deg', 'Value unit for border radius. Such as: "5 deg"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box',
							'property' => 'transform',
						)
					),
					'quote_boxcontent_rotate' => array(
						'type'        => 'text',
						'default'     => '8',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Content Box Rotation', 'bb-njba' ),
						'description' => _x( 'deg', 'Value unit for border radius. Such as: "5 deg"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-quote-box-content',
							'property' => 'transform',
						)
					),

				)
			),
			'borders'     => array(
				'title'  => __( 'Image Box', 'bb-njba' ),
				'fields' => array( // Section Fields
					'image_size'         => array(
						'type'        => 'text',
						'label'       => __( 'Size', 'bb-njba' ),
						'size'        => 5,
						'default'     => 100,
						'description' => 'px'
					),
					'img_bg_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial.layout-7 .njba-testimonial-body-right,.njba-testimonial.layout-8 .njba-testimonial-body-right',
							'property' => 'border-color',
						)
					),
					'image_border_style' => array(
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
								'fields' => array( 'border_width', 'border_color', 'border_radius' ),
							),
							'dotted' => array(
								'fields' => array( 'border_width', 'border_color', 'border_radius' ),
							),
							'dashed' => array(
								'fields' => array( 'border_width', 'border_color', 'border_radius' ),
							),
							'double' => array(
								'fields' => array( 'border_width', 'border_color', 'border_radius' ),
							),
						)
					),
					'border_width'       => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '2',
						'size'        => '5',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'description' => 'px',
					),
					'border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
					),
					'border_radius'      => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
					),

				)
			),
		)
	),
	'typography'   => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'heading_fonts'  => array(
				'title'  => __( 'Heading', 'bb-njba' ),
				'fields' => array( // Section Fields
					'heading_alignment' => array(
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
							'selector' => '.njba-testimonial-heading h1',
							'property' => 'text-align'
						)
					),
					'heading_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-testimonial-heading h1',
						)
					),
					'heading_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '40',
							'medium'  => '22',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-heading h1',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'heading_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Hight' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '40',
							'medium'  => '22',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-heading h1',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'heading_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-heading h1',
							'property' => 'color',
						)
					),
				)
			),
			'title_fonts'    => array(
				'title'  => __( 'Client Name', 'bb-njba' ),
				'fields' => array(
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
							'selector' => '.njba-testimonial-title',
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
							'selector' => '.njba-testimonial-title'
						)
					),
					'title_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '18',
							'medium'  => '18',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'title_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Hight' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '40',
							'medium'  => '22',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'title_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-title',
							'property' => 'color',
						)
					),
					'title_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-title',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-title',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-title',
									'property' => 'margin-right',
								),
							)
						)
					),
				)
			),
			'subtitle_fonts' => array(
				'title'  => __( 'Client Profile', 'bb-njba' ),
				'fields' => array(
					'subtitle_alignment' => array(
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
							'selector' => '.njba-testimonial-sub-title',
							'property' => 'text-align'
						)
					),
					'subtitle_font'      => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-testimonial-sub-title'
						)
					),
					'subtitle_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '15',
							'medium'  => '15',
							'small'   => '15',
						),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-sub-title',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'subtitle_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Hight' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '40',
							'medium'  => '22',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-sub-title',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'subtitle_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-sub-title',
							'property' => 'color',
						)
					),
					'subtitle_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-sub-title',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-sub-title',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-sub-title',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-sub-title',
									'property' => 'margin-right',
								),
							)
						)
					),
				)
			),
			'rate_option'    => array(
				'title'  => __( 'Ratings', 'bb-njba' ),
				'fields' => array(
					'rate_alignment'      => array(
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
							'selector' => '.njba-review',
							'property' => 'text-align'
						)
					),
					'rate_font_size'      => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '17',
							'medium'  => '17',
							'small'   => '17',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-review i.fa',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'rate_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Hight' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '40',
							'medium'  => '22',
							'small'   => '18',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-review i.fa',
							'property' => 'line-height',
							'unit'     => 'px'
						)
					),
					'active_rate_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Active Rate Color', 'bb-njba' ),
						'default'    => 'e5b300',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-review i.fa.fa-star',
							'property' => 'color'
						)
					),
					'deactive_rate_color' => array(
						'type'       => 'color',
						'label'      => __( 'Deactive Rate Color', 'bb-njba' ),
						'default'    => 'd3d3d3',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-review i.fa.fa-star-o',
							'property' => 'color'
						)
					),

				)
			),
			'content_fonts'  => array(
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
							'selector' => '.njba-testimonial-content',
							'property' => 'text-align'
						)
					),
					'text_font'         => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-testimonial-content'
						)
					),
					'text_font_size'    => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'default'     => array(
							'desktop' => '16',
							'medium'  => '16',
							'small'   => '16',
						),
						'size'        => '5',
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-content',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'text_line_height'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height' ),
						'default'     => array(
							'desktop' => '16',
							'medium'  => '16',
							'small'   => '16',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-content',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'text_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-testimonial-content',
							'property' => 'color',
						)
					),
					'content_margin'    => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => 20,
							'right'  => 20,
							'bottom' => 20,
							'left'   => 20
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',

								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-content',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',

								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-content',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',

								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-content',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',

								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-testimonial-content',
									'property' => 'margin-right',
								),
							)
						)
					),
				),
			),
		)
	)
) );
/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form( 'njba_testimonials_form', array(
	'title' => __( 'Add Testimonial', 'bb-njba' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'title'   => array(
					'title'  => '',
					'fields' => array(
						'title'        => array(
							'type'    => 'text',
							'label'   => __( 'Name', 'bb-njba' ),
							'default' => 'Name',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-testimonial-title'
							)
						),
						'subtitle'     => array(
							'type'    => 'text',
							'label'   => __( 'Profile', 'bb-njba' ),
							'default' => 'Profile',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-testimonial-sub-title'
							)
						),
						'photo'        => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'bb-njba' ),
							'show_remove' => true
						),
						'rate_show'    => array(
							'type'    => 'select',
							'label'   => __( 'Rating Show', 'bb-njba' ),
							'default' => '1',
							'options' => array(
								'1' => __( 'Yes', 'bb-njba' ),
								'0' => __( 'No', 'bb-njba' ),
							),
							'toggle'  => array(
								'1' => array(
									'fields' => array( 'profile_rate' )
								),
								'0' => array()
							)

						),
						'profile_rate' => array(
							'type'    => 'select',
							'label'   => __( 'Rating', 'bb-njba' ),
							'default' => 5,
							'options' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',

							),
						),
					),
				),
				'content' => array( // Section
					'title'  => __( 'Content', 'bb-njba' ), // Section Title
					'fields' => array( // Section Fields
						'testimonial' => array(
							'type'    => 'editor',
							'label'   => '',
							'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.',
						)
					)
				),
			)
		)
	)
) );
