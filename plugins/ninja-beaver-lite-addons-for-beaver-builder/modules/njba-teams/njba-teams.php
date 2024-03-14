<?php

/**
 * @class NJBA_Teams_Module
 */
class NJBA_Teams_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Teams', 'bb-njba' ),
			'description'     => __( 'Addon to display Teams.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'carousel' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-teams/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-teams/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'partial_refresh' => true, // Set this to true to enable partial refresh.
			'enabled'         => true, // Defaults to true and can be omitted.
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered
		$this->add_css( 'jquery-bxslider' );
		$this->add_css( 'font-awesome' );
		$this->add_js( 'jquery-bxslider' );
		$this->add_css( 'njba-teams-fields', NJBA_MODULE_URL . 'modules/njba-teams/css/fields.css' );
		$this->add_css( 'njba-teams-frontend', NJBA_MODULE_URL . 'modules/njba-teams/css/frontend.css' );
	}

	/**
	 * Get Teams Images
	 *
	 * @param $i
	 *
	 * @since 1.0.2
	 */
	public function njba_image_render( $i ) {
		$photo       = $this->settings->teams[ $i ]->photo;
		$teams_image = wp_get_attachment_image_src( $photo );
		if ( ! is_wp_error( $teams_image ) ) {
			$photo_src    = $teams_image[0];
			$photo_width  = $teams_image[1];
			$photo_height = $teams_image[2];
		}
		if ( $photo !== '' ) {
			echo '<img src="' . $this->settings->teams[ $i ]->photo_src . '" width="' . $photo_width . '" height="' . $photo_height . '" class="njba-img-responsive">';
		} else {
			echo '<img src="' . NJBA_MODULE_URL . 'modules/njba-teams/images/placeholder.jpg" class="njba-image-responsive" />';
		}
	}

	/**
	 * For Name,Designation,Bio
	 *
	 * @param $i
	 *
	 * @since 1.0.2
	 */
	public function njba_short_bio( $i ) {
		$teams       = $this->settings->teams[ $i ];
		$team_layout = $this->settings->team_layout;
		if ( $teams->name ) {
			if ( $teams->url !== '' && $team_layout != 1 && $team_layout != 3 && $team_layout != 4 ) {
				echo '<a href="' . $teams->url . '"  target="' . $teams->link_target . '">';
			}
			echo '<h4 class="njba-team-name-selector">' . $teams->name . '</h4>';

			if ( $teams->url !== '' && $team_layout != 1 && $team_layout != 3 && $team_layout != 4 ) {
				echo '</a>';
			}
		}
		if ( $teams->designation ) {
			echo '<h5 class="njba-team-designation-selector">' . $teams->designation . '</h5>';
		}
		if ( $teams->member_description !== '' && $team_layout != 3 && $team_layout != 4 && $team_layout != 5 ) {
			echo $teams->member_description;
		}
	}


	/**
	 * For Social Media
	 *
	 * @param $i
	 *
	 * @since 1.0.2
	 */
	public function njba_social_media( $i ) {
		$teams       = $this->settings->teams[ $i ];
		$team_layout = $this->settings->team_layout;
		$effect      = array();
		$effect_1    = array( 'left', 'left', 'right', 'right' );
		$effect_2    = array( 'left', 'top', 'bottom', 'right' );
		if ( $this->settings->effect_selection === 'effect-1' ) {
			$effect[] = $effect_1;
		}
		if ( $this->settings->effect_selection === 'effect-2' ) {
			$effect[] = $effect_2;
		}
		$social_array = array();

		if ( $teams->facebook_url !== '' ) {
			$social_array[] = '1';
		}
		if ( $teams->twitter_url !== '' ) {
			$social_array[] = '2';
		}
		if ( $teams->googleplus_url !== '' ) {
			$social_array[] = '3';
		}
		if ( $teams->linkedin_url !== '' ) {
			$social_array[] = '4';
		}
		echo '<ul>';
		if ( $team_layout != 2 && $team_layout != 3 ) {
			for ( $j = 0, $jMax = count( $social_array ); $j <= $jMax; $j ++ ) {
				$k = $j;
				if ( $teams->facebook_url !== '' ) {
					echo '<li class="' . $effect[0][ $k ] . '"><a href="' . $teams->facebook_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-facebook"></i></a></li>';
					$k ++;
				}
				if ( $teams->twitter_url !== '' ) {
					echo '<li class="' . $effect[0][ $k ] . '"><a href="' . $teams->twitter_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-twitter"></i></a></li>';
					$k ++;
				}
				if ( $teams->googleplus_url !== '' ) {
					echo '<li class="' . $effect[0][ $k ] . '"><a href="' . $teams->googleplus_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-google-plus"></i></a></li>';
					$k ++;
				}
				if ( $teams->linkedin_url !== '' ) {
					echo '<li class="' . $effect[0][ $k ] . '"><a href="' . $teams->linkedin_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-linkedin"></i></a></li>';
					$k ++;
				}
				$j = $k;
			}
		} else {
			if ( $teams->facebook_url !== '' ) {
				echo '<li><a href="' . $teams->facebook_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-facebook"></i></a></li>';
			}
			if ( $teams->twitter_url !== '' ) {
				echo '<li><a href="' . $teams->twitter_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-twitter"></i></a></li>';
			}
			if ( $teams->googleplus_url !== '' ) {
				echo '<li><a href="' . $teams->googleplus_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-google-plus"></i></a></li>';
			}
			if ( $teams->linkedin_url !== '' ) {
				echo '<li><a href="' . $teams->linkedin_url . '" target="' . $teams->social_link_target . '" ><i class="fa fa-linkedin"></i></a></li>';
			}
		}
		echo '</ul>';
	}

	/**
	 * for Button Render
	 *
	 * @param $i
	 *
	 * @since 1.0.2
	 */
	public function njba_button_render( $i ) {
		$teams = $this->settings->teams[ $i ];

		if ( $teams->url_text !== '' && $teams->url !== '' ) {
			$btn_settings = array(
				'button_text' => $this->settings->teams[ $i ]->url_text, //Button text
				'link'        => $teams->url, //Button Link
			);
			FLBuilder::render_module_html( 'njba-button', $btn_settings );
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
FLBuilder::register_module( 'NJBA_Teams_Module', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'heading'          => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'teams_layout_view' => array(
						'type'    => 'select',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 'box',
						'options' => array(
							'box'    => __( 'Grid', 'bb-njba' ),
							'slider' => __( 'Carousel', 'bb-njba' )
						),
						'toggle'  => array(
							'slider' => array(
								'sections' => array( 'slider', 'carousel_section', 'arrow_nav', 'dot_nav' ),
							),
							'box'    => array(
								'sections' => array( 'box' ),
							)
						),
					),
				)
			),
			'box'              => array( // Section
				'title'  => __( 'Grid Settings', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'show_col' => array(
						'type'    => 'select',
						'label'   => __( 'Show Columns', 'bb-njba' ),
						'default' => 3,
						'options' => array(
							'12' => '1',
							'6'  => '2',
							'4'  => '3',
							'3'  => '4',
						),
						'toggle'  => array(
							'12' => array(
								'sections' => array( 'content_border' )
							),
						)
					),

				)
			),
			'slider'           => array( // Section
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
						'type'    => 'njba-simplify',
						'label'   => __( 'Margin Between Slides', 'bb-njba' ),
						'default' => array(
							'desktop' => '0',
							'medium'  => '0',
							'small'   => '0',
						),
						'size'    => '5',
					),

				)
			),
			'arrow_nav'        => array( // Section
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
								'fields' => array(
									'arrows_size',
									'arrow_background',
									'arrow_color',
									'arrow_border_width',
									'arrow_border_style',
									'arrow_border_color',
									'arrow_border_color',
									'arrow_border_radius'
								)
							)
						)
					),
					'arrows_size'         => array(
						'type'        => 'text',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => '20',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => 'px',
						'help'        => __( 'Arrow Size.', 'bb-njba' ),
					),
					'arrow_background'    => array(
						'type'       => 'color',
						'label'      => __( 'Arrow Background', 'bb-njba' ),
						'default'    => 'dddddd',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-teams-main .njba-slider-nav a i',
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
							'selector' => '.njba-teams-main .njba-slider-nav a i',
							'property' => 'color'
						)
					),
					'arrow_border_radius' => array(
						'type'        => 'text',
						'default'     => '0',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Arrow Border Radius', 'bb-njba' ),
						'description' => _x( 'px', 'Value unit for border radius. Such as: "5 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-teams-main .njba-slider-nav a i',
							'property' => 'border-radius'
						)
					),
				)
			),
			'dot_nav'          => array( // Section
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
							'selector' => '.njba-testimonials-wrap .bx-wranjbaer .bx-pager a',
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
							'selector' => '.njba-testimonials-wrap .bx-wranjbaer .bx-pager a.active',
							'property' => 'background'
						)
					),
				)
			)
		)
	),

	'layouts'    => array(
		'title'    => __( 'Layout', 'bb-njba' ),
		'sections' => array(
			'layout' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'team_layout' => array(
						'type'    => 'njba-radio',
						'label'   => __( 'Layout', 'bb-njba' ),
						'default' => 1,
						'options' => array(
							'1' => 'layout_1',
							'2' => 'layout_2',
							'3' => 'layout_3',
							'4' => 'layout_4',
							'5' => 'layout_5',
						),
						'toggle'  => array(
							'1' => array(
								'fields'   => array( 'effect_selection' ),
								'sections' => array( 'button' ),
								'tabs'     => array( 'styles' ),
							),
							'3' => array(
								'sections' => array( 'button' ),
								'tabs'     => array( 'styles' ),
							),
							'4' => array(
								'sections' => array( 'button' ),
								'tabs'     => array( 'styles' ),
							),
							'5' => array(
								'fields' => array( 'effect_selection' ),
								'tabs'   => array( 'styles' ),
							),

						),
					),
				)
			),
		),
	),
	'teams'      => array( // Tab
		'title'    => __( 'Teams', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'teams' => array(
						'type'         => 'form',
						'label'        => __( 'Teams', 'bb-njba' ),
						'form'         => 'njba_teampanel_form', // ID from registered form below
						'preview_text' => 'name', // Name of a field to use for the preview text
						'multiple'     => true
					),
				)
			)
		)
	),
	'styles'     => array(
		'title'    => __( 'Styles', 'bb-njba' ),
		'sections' => array(
			'title_fonts' => array(
				'title'  => __( 'Column Settings', 'bb-njba' ),
				'fields' => array(

					'col_bg_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-section',
							'property' => 'background-color',
						)
					),
					'col_border_style'       => array(
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
								'fields' => array(
									'col_border_width',
									'col_border_color',
									'col_border_hover_color',
									'col_border_radius',
									'col_box_shadow',
									'col_box_shadow_color'
								)
							),
							'dotted' => array(
								'fields' => array(
									'col_border_width',
									'col_border_color',
									'col_border_hover_color',
									'col_border_radius',
									'col_box_shadow',
									'col_box_shadow_color'
								)
							),
							'dashed' => array(
								'fields' => array(
									'col_border_width',
									'col_border_color',
									'col_border_hover_color',
									'col_border_radius',
									'col_box_shadow',
									'col_box_shadow_color'
								)
							),
							'double' => array(
								'fields' => array(
									'col_border_width',
									'col_border_color',
									'col_border_hover_color',
									'col_border_radius',
									'col_box_shadow',
									'col_box_shadow_color'
								)
							),
						)
					),
					'col_border_width'       => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'col_border_radius'      => array(
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
					'col_border_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000'
					),
					'col_border_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000'
					),
					'col_box_shadow'         => array(
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
					'col_box_shadow_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff'
					),
					'effect_selection'       => array(
						'type'    => 'select',
						'label'   => __( 'Social Icon Effect', 'bb-njba' ),
						'default' => 'effect-1',
						'options' => array(
							'effect-1' => __( 'Effect 1', 'bb-njba' ),
							'effect-2' => __( 'Effect 2', 'bb-njba' ),

						),

					),
					'overly_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Hover Background', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'dddddd',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-overlay',
							'property' => 'background-color',
						)
					),
					'overly_color_opacity'   => array(
						'type'        => 'text',
						'label'       => __( 'Hover Background Color Opacity', 'bb-njba' ),
						'default'     => '100',
						'maxlength'   => '3',
						'size'        => '5',
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-overlay',
							'property' => 'background-color',
						)
					),
				),
			),
		),
	),
	'typography' => array(
		'title'    => __( 'Typography', 'bb-njba' ),
		'sections' => array(
			'content_border'    => array(
				'title'  => __( 'Content Border', 'bb-njba' ),
				'fields' => array(
					'content_border_style'  => array(
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
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'content_border_width', 'content_border_radius', 'content_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'content_border_width', 'content_border_radius', 'content_border_color' )
							),
							'dashed' => array(
								'fields' => array( 'content_border_width', 'content_border_radius', 'content_border_color' )
							),
							'double' => array(
								'fields' => array( 'content_border_width', 'content_border_radius', 'content_border_color' )
							),
						)
					),
					'content_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' )
					),
					'content_border_radius' => array(
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
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'top-right'    => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom-left'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'bottom-right' => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						)
					),
					'content_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'e0e0e0'
					),
					'content_padding'      => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Padding', 'bb-njba' ),
						'default' => array(
							'top'    => 20,
							'bottom' => 20,
							'left'   => 20,
							'right'  => 20,
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content',
									'property' => 'padding-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content',
									'property' => 'padding-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content',
									'property' => 'padding-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content',
									'property' => 'padding-right',
								),
							)
						)
					),
				),
			),
			'title_fonts'       => array(
				'title'  => __( 'Member Name', 'bb-njba' ),
				'fields' => array(

					'name_alignment'   => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h4',
							'property' => 'text-align'
						)
					),
					'name_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-team-content h4'
						)
					),
					'name_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h4',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'name_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',

					),
					'name_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h4',
							'property' => 'color',
						)
					),
					'name_margin'      => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => '',
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h4',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h4',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h4',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h4',
									'property' => 'margin-right',
								),
							)
						)
					),

				)
			),
			'designation_fonts' => array(
				'title'  => __( 'Designation', 'bb-njba' ),
				'fields' => array(
					'designation_alignment'   => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h5',
							'property' => 'text-align'
						)
					),
					'designation_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-team-content h5'
						)
					),
					'designation_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h5',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'designation_line_height' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',
					),
					'designation_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '7f7f7f',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-content h5',
							'property' => 'color',
						)
					),
					'designation_margin'      => array(
						'type'    => 'njba-multinumber',
						'label'   => __( 'Margin', 'bb-njba' ),
						'default' => array(
							'top'    => '',
							'bottom' => '',
							'left'   => '',
							'right'  => '',
						),
						'options' => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h5',
									'property' => 'margin-top',
								),
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h5',
									'property' => 'margin-bottom',
								),
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h5',
									'property' => 'margin-left',
								),
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right',
								'description' => 'px',
								'preview'     => array(
									'selector' => '.njba-team-content h5',
									'property' => 'margin-right',
								),
							)
						)
					),
				)
			),
			'content_fonts'     => array(
				'title'  => __( 'Short Bio', 'bb-njba' ),
				'fields' => array(
					'content_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-team-content p',
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
							'selector' => '.njba-team-content p'
						)
					),
					'text_font_size'    => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-team-content p',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'text_line_height'  => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => '',
						),
						'size'        => '5',
						'maxlength'   => '2',

					),
					'text_color'        => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-content p',
							'property' => 'color',
						)
					),

				),
			),

			'button'       => array( // Section
				'title'  => __( 'View More Button', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'alignment'                     => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						)
					),
					'button_font_family'            => array(
						'type'    => 'font',
						'label'   => __( 'Font Family', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-btn-main a.njba-btn'
						)
					),
					'button_font_size'              => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'description' => __( 'Pleas enter value in pixels.', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						)
					),
					'button_background_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'cccccc'
					),
					'button_background_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => ''
					),
					'button_text_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => ''
					),
					'button_text_hover_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => ''
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
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'dotted' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'dashed' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'double' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
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
							'top-left'     => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'top-right'    => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom-left'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'bottom-right' => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
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
							'right'  => 12,
							'bottom' => 10,
							'left'   => 12
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

						),
					),

				)
			), // Section
			'social_fonts' => array(
				'title'  => __( 'Social Media ', 'bb-njba' ),
				'fields' => array(
					'social_alignment' => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'center',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-team-social',
							'property' => 'text-align'
						)
					),

					'border_radius'                 => array(
						'type'        => 'text',
						'default'     => '',
						'maxlength'   => '3',
						'size'        => '5',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => '%',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-team-section li a',
							'property' => 'border-radius',

						)
					),
					'social_background_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'default'    => 'dddddd',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-social i',

						)
					),
					'hover_social_background_color' => array(
						'type'       => 'color',
						'label'      => __( 'Hover Background Color', 'bb-njba' ),
						'default'    => 'dddddd',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-social i',

						)
					),
					'social_color'                  => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-social i',
							'property' => 'color',
						)
					),
					'hover_social_color'            => array(
						'type'       => 'color',
						'label'      => __( 'Hover Color', 'bb-njba' ),
						'default'    => '000000',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-team-social i',
							'property' => 'color',
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
FLBuilder::register_settings_form( 'njba_teampanel_form', array(
	'title' => __( 'Add Team Member', 'bb-njba' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'bb-njba' ), // Tab title
			'sections' => array( // Tab Sections
				'member_details' => array(
					'title'  => 'Member Details',
					'fields' => array(
						'name'        => array(
							'type'    => 'text',
							'label'   => __( 'Name', 'bb-njba' ),
							'default' => 'Name',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-team-name-selector'
							)
						),
						'designation' => array(
							'type'    => 'text',
							'label'   => __( 'Designation', 'bb-njba' ),
							'default' => 'Designation',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-team-designation-selector'
							)
						),
						'photo'       => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'bb-njba' ),
							'show_remove' => true
						),
						'url'         => array(
							'type'        => 'link',
							'label'       => __( 'Link', 'fl-builder' ),
							'default'     => '#',
							'placeholder' => 'http://www.example.com',
							'preview'     => array(
								'type' => 'none'
							)
						),
						'url_text'    => array(
							'type'    => 'text',
							'label'   => __( 'Link Text', 'fl-builder' ),
							'default' => 'Read More',
							'preview' => array(
								'type'     => 'text',
								'selector' => '.njba-read-more',
							)
						),
						'link_target' => array(
							'type'    => 'select',
							'label'   => __( 'Link Target', 'bb-njba' ),
							'default' => '_blank',
							'options' => array(
								'_self'  => __( 'Same Window', 'bb-njba' ),
								'_blank' => __( 'New Window', 'bb-njba' )
							)
						),
					),
				),
				'short_bio'      => array(
					'title'  => 'Short Bio',
					'fields' => array(
						'member_description' => array(
							'type'          => 'editor',
							'label'         => '',
							'media_buttons' => false,
							'default'       => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s. ',
							'rows'          => 8,
							'preview'       => array(
								'type'     => 'text',
								'selector' => '.njba-member-description'
							)
						),
					),
				),
				'social_details' => array(
					'title'  => 'Social Links',
					'fields' => array(
						'facebook_url'       => array(
							'type'    => 'text',
							'label'   => __( 'Facebook URL', 'bb-njba' ),
							'default' => '#',
						),
						'twitter_url'        => array(
							'type'    => 'text',
							'label'   => __( 'Twitter URL', 'bb-njba' ),
							'default' => '#',
						),
						'googleplus_url'     => array(
							'type'    => 'text',
							'label'   => __( 'Google Plus URL', 'bb-njba' ),
							'default' => '#',
						),
						'linkedin_url'       => array(
							'type'    => 'text',
							'label'   => __( 'Linkedin URL', 'bb-njba' ),
							'default' => '#',
						),
						'social_link_target' => array(
							'type'    => 'select',
							'label'   => __( 'Link Target', 'bb-njba' ),
							'default' => '_blank',
							'options' => array(
								'_self'  => __( 'Same Window', 'bb-njba' ),
								'_blank' => __( 'New Window', 'bb-njba' )
							)
						)
					),
				),

			)
		)
	)
) );
