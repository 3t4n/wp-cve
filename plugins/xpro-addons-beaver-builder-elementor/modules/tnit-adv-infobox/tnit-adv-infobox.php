<?php
/**
 * @class TNITAdvanceInfoBoxModule
 */

class TNITAdvanceInfoBoxModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Advance Info Box', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-adv-infobox/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-adv-infobox/',
				'editor_export'   => true,
				'enabled'         => true,
				'partial_refresh' => true,
			)
		);
	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {
        // Already registered
		$this->add_css( 'font-awesome' );
		$this->add_css( 'font-awesome-5' );

		// Register and enqueue your own
		$this->add_css( 'xpro-animate', XPRO_ADDONS_FOR_BB_URL . 'assets/css/animate.css', '', '1.0.0' );
	}

	/**
	 * @method update
	 */
	public function update( $settings ) {
		// get default photo
		if ( empty( $settings->photo ) ) {
			$settings->photo_src = $this->url . 'images/placeholder-sm.webp';
		}

		return $settings;
	}

	/**
	 * Function that get link nofollow
	 *
	 * @method render_button
	 */
	public function get_nofollow() {
		if ( $this->settings->link_nofollow == 'yes' ) {
			$nofollow = ' rel="nofollow"';
		} else {
			$nofollow = '';
		}

		return $nofollow;
	}

	/**
	 * Function that renders Icon
	 *
	 * @method render_icon
	 */
	public function render_icon() {
		if ( $this->settings->image_type == 'icon' ) {
			$icon_classes  = 'info-icon tnit-icon';
			$icon_classes .= ( $this->settings->icon_bg_style == 'circle' ) ? ' tnit-icon-circle' : '';
			$icon_classes .= ( $this->settings->icon_bg_style == 'square' ) ? ' tnit-icon-square' : '';
			$icon_classes .= ( $this->settings->icon_bg_style == 'custom' ) ? ' tnit-icon-custom' : '';

			$output  = '<span class="' . $icon_classes . '">';
			$output .= '<i class="' . $this->settings->icon . '" aria-hidden="true"></i>';
			$output .= '</span>';

			echo $output;
		}
	}

	/**
	 * Function that renders Photo
	 *
	 * @method render_photo
	 */
	public function render_photo() {
		/**
		 * Get photo data
		 *
		 * @variable $photo
		 */
		if ( ! empty( $this->settings->photo ) ) {
			$photo = FLBuilderPhoto::get_attachment_data( $this->settings->photo );

			// get src
			$src = $this->settings->photo_src;
			$alt = '';

			// get alt
			if ( ! empty( $photo->alt ) ) {
				$alt = htmlspecialchars( $photo->alt );
			} elseif ( ! empty( $photo->description ) ) {
				$alt = htmlspecialchars( $photo->description );
			} elseif ( ! empty( $photo->caption ) ) {
				$alt = htmlspecialchars( $photo->caption );
			} elseif ( ! empty( $photo->title ) ) {
				$alt = htmlspecialchars( $photo->title );
			}

			// get classes
			$photo_classes = array( 'tnit-photo-img' );

			if ( is_object( $photo ) ) {
				$photo_classes[] = 'wp-image-' . $photo->id;

				if ( isset( $photo->sizes ) ) {
					foreach ( $photo->sizes as $key => $size ) {
						if ( $size->url == $this->settings->photo_src ) {
							$photo_classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}

			$photo_classes = implode( ' ', $photo_classes );
		} else {
			// get placeholder image data
			$src           = $this->settings->photo_src;
			$alt           = 'placeholder-image';
			$photo_classes = 'tnit-photo-img';
		}

		if ( $this->settings->image_type == 'photo' ) {
			$output  = '<figure class="tnit-info-thumb">';
			$output .= '<img src="' . $src . '" class="' . $photo_classes . '" alt="' . $alt . '">';
			$output .= '</figure>';

			echo $output;
		}
	}

	/**
	 * Function that renders Title Prefix
	 *
	 * @method render_title_prefix
	 */
	public function render_title_prefix() {
		if ( '' != $this->settings->title_prefix ) {

			$nofollow = $this->get_nofollow();

			$output  = '<div class="tnit-title-prefix-wrap">';
			$output .= '<' . $this->settings->title_prefix_tag . ' class="tnit-title-prefix">';
			$output .= $this->settings->title_prefix;
			$output .= '</' . $this->settings->title_prefix_tag . '>';
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Title
	 *
	 * @method render_title
	 */
	public function render_title() {
		if ( '' != $this->settings->title ) {

			$nofollow = $this->get_nofollow();

			$output  = '<div class="tnit-infobox-title-wrap">';
			$output .= '<' . $this->settings->title_tag . ' class="info-title">';

			if ( $this->settings->link_type == 'title' ) {
				$output .= '<a href="' . $this->settings->link . '" target="' . $this->settings->link_target . '"' . $nofollow . '>';
				$output .= $this->settings->title;
				$output .= '</a>';
			} else {
				$output .= $this->settings->title;
			}

			$output .= '</' . $this->settings->title_tag . '>';
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Title Postfix
	 *
	 * @method render_title_postfix
	 */
	public function render_title_postfix() {
		if ( '' != $this->settings->title_postfix ) {

			$nofollow = $this->get_nofollow();

			$output  = '<div class="tnit-title-postfix-wrap">';
			$output .= '<' . $this->settings->title_postfix_tag . ' class="tnit-title-postfix">';
			$output .= $this->settings->title_postfix;
			$output .= '</' . $this->settings->title_postfix_tag . '>';
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Description
	 *
	 * @method render_description
	 */
	public function render_description() {
		$output = '<div class="tnit-infobox-text-wrap">';

		global $wp_embed;
		$output .= wpautop( $wp_embed->autoembed( $this->settings->text ) );

		$output .= '</div>';

		echo $output;
	}

	/**
	 * Function that renders Button
	 *
	 * @method render_button
	 */
	public function render_button() {
		$nofollow = $this->get_nofollow();

		if ( 'button' == $this->settings->link_type ) {

			$output  = '<div class="tnit-infobox-button-wrap">';
			$output .= '<a href="' . $this->settings->link . '" class="tnit-btn-arrow tnit-btn-arrow_v3" target="' . $this->settings->link_target . '"' . $nofollow . '>';

			if ( ! empty( $this->settings->cta_icon ) && $this->settings->cta_icon_position == 'before' ) {
				$output .= '<span class="tnit-cta-icon tnit-cta-icon-before">';
				$output .= '<i class="' . $this->settings->cta_icon . '" aria-hidden="true"></i>';
				$output .= '</span>';
			}

			if ( $this->settings->cta_text != '' ) {
				$output .= '<span class="tnit-cta-text">';
				$output .= $this->settings->cta_text;
				$output .= '</span>';
			}

			if ( ! empty( $this->settings->cta_icon ) && $this->settings->cta_icon_position == 'after' ) {
				$output .= '<span class="tnit-cta-icon tnit-cta-icon-after">';
				$output .= '<i class="' . $this->settings->cta_icon . '" aria-hidden="true"></i>';
				$output .= '</span>';
			}

			$output .= '</a>';
			$output .= '</div>';

			echo $output;
		} elseif ( 'icon' == $this->settings->link_type ) {

			$btn_animation = ( $this->settings->cta_icon_animation == 'yes' ) ? ' btn-animate-Right' : '';

			$output  = '<div class="tnit-infobox-button-wrap">';
			$output .= '<a href="' . $this->settings->link . '" class="tnit-btn-arrow' . $btn_animation . '" target="' . $this->settings->link_target . '"' . $nofollow . '>';
			$output .= '<i class="' . $this->settings->cta_icon . '" aria-hidden="true"></i>';
			$output .= '</a>';
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Box Link
	 *
	 * @method render_separator
	 */
	public function render_box_link() {
		 $nofollow = $this->get_nofollow();

		$output = '';

		if ( 'full-box' == $this->settings->link_type ) {
			$output = '<a href="' . $this->settings->link . '" class="tnit-box-link" target="' . $this->settings->link_target . '"' . $nofollow . '></a>';
		}

		echo $output;
	}

	/**
	 * Function that renders Separator
	 *
	 * @method render_separator
	 */
	public function render_separator( $pos ) {
		if ( 'yes' == $this->settings->enable_separator && ( $pos == $this->settings->separator_pos ) ) {
			echo '<div class="tnit-separator-wrapper"><span class="tnit-separator"></span></div>';
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITAdvanceInfoBoxModule',
	array(
		'general'    => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'title'     => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_prefix'  => array(
							'type'        => 'text',
							'label'       => __( 'Title Prefix', 'xpro-bb-addons' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.tnit-title-prefix',
							),
						),
						'title'         => array(
							'type'        => 'text',
							'label'       => __( 'Title', 'xpro-bb-addons' ),
							'default'     => __( 'Info Box', 'xpro-bb-addons' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.info-title',
							),
						),
						'title_postfix' => array(
							'type'        => 'text',
							'label'       => __( 'Title Postfix', 'xpro-bb-addons' ),
							'connections' => array( 'string' ),
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.tnit-title-postfix',
							),
						),
					),
				),
				'text'      => array(
					'title'  => __( 'Description', 'xpro-bb-addons' ),
					'fields' => array(
						'text' => array(
							'type'          => 'editor',
							'media_buttons' => false,
							'rows'          => 6,
							'wpautop'       => false,
							'default'       => __( 'Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'xpro-bb-addons' ),
							'connections'   => array( 'string' ),
							'preview'       => array(
								'type'     => 'text',
								'selector' => '.tnit-infobox-text-wrap',
							),
						),
					),
				),
				'separator' => array(
					'title'  => __( 'Separator', 'xpro-bb-addons' ),
					'fields' => array(
						'enable_separator'        => array(
							'type'    => 'button-group',
							'label'   => __( 'Enable Separator', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'yes' => __( 'Yes', 'xpro-bb-addons' ),
								'no'  => __( 'No', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'separator_pos', 'separator_color', 'separator_thickness', 'separator_style', 'separator_width', 'separator_alignment', 'separator_margin_top', 'separator_margin_bottom' ),
								),
							),
						),
						'separator_pos'           => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'xpro-bb-addons' ),
							'default' => 'below_title',
							'options' => array(
								'above_prefix'  => __( 'Above Title Prefix', 'xpro-bb-addons' ),
								'below_prefix'  => __( 'Below Title Prefix', 'xpro-bb-addons' ),
								'below_title'   => __( 'Below Title', 'xpro-bb-addons' ),
								'below_postfix' => __( 'Below Title Postfix', 'xpro-bb-addons' ),
								'below_desc'    => __( 'Below Description', 'xpro-bb-addons' ),
							),
						),
						'separator_alignment'     => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-separator-wrapper',
								'property' => 'text-align',
							),
						),
						'separator_style'         => array(
							'type'    => 'select',
							'label'   => __( 'Style', 'xpro-bb-addons' ),
							'default' => 'solid',
							'help'    => __( 'The type of border to use. Double borders must have a height of at least 3px to render properly.', 'xpro-bb-addons' ),
							'options' => array(
								'solid'  => __( 'Solid', 'xpro-bb-addons' ),
								'dashed' => __( 'Dashed', 'xpro-bb-addons' ),
								'dotted' => __( 'Dotted', 'xpro-bb-addons' ),
								'double' => __( 'Double', 'xpro-bb-addons' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'border-bottom-style',
							),
						),
						'separator_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'border-bottom-color',
							),
						),
						'separator_thickness'     => array(
							'type'        => 'unit',
							'label'       => __( 'Thickness', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '2',
							'help'        => __( 'Adjust thickness of border.', 'xpro-bb-addons' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'border-bottom-width',
							),
						),
						'separator_width'         => array(
							'type'         => 'unit',
							'label'        => __( 'Width', 'xpro-bb-addons' ),
							'units'        => array( 'px', '%' ),
							'default_unit' => 'px',
							'placeholder'  => '50',
							'slider'       => true,
							'responsive'   => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'width',
							),
						),
						'separator_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Top', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'margin-top',
							),
						),
						'separator_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '15',
							'responsive'  => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-separator',
								'property' => 'margin-bottom',
							),
						),
					),
				),
			),
		),
		'imageicon'  => array(
			'title'    => __( 'Image / Icon', 'xpro-bb-addons' ),
			'sections' => array(
				'image_type'      => array(
					'title'  => __( 'Image / Icon', 'xpro-bb-addons' ),
					'fields' => array(
						'image_type' => array(
							'type'    => 'button-group',
							'label'   => __( 'Image Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'  => __( 'None', 'xpro-bb-addons' ),
								'icon'  => __( 'Icon', 'xpro-bb-addons' ),
								'photo' => __( 'Photo', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'icon'  => array(
									'fields'   => array( 'imgicon_position' ),
									'sections' => array( 'icon_basic', 'icon_style', 'icon_colors', 'imgicon_margins' ),
								),
								'photo' => array(
									'fields'   => array( 'imgicon_position' ),
									'sections' => array( 'img_basic', 'imgicon_margins' ),
								),
							),
						),
					),
				),
				'icon_basic'      => array(
					'title'  => __( 'Icon Basics', 'xpro-bb-addons' ),
					'fields' => array(
						'icon'      => array(
							'type'        => 'icon',
							'label'       => __( 'Choose Icon', 'xpro-bb-addons' ),
							'default'     => 'fas fa-globe',
							'show_remove' => true,
						),
						'icon_size' => array(
							'type'        => 'unit',
							'label'       => 'Icon Size',
							'units'       => array( 'px' ),
							'placeholder' => '36',
							'responsive'  => true,
							'slider'      => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-icon',
								'property' => 'font-size',
							),
						),
					),
				),
				'icon_style'      => array(
					'title'  => __( 'Icon Style', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_bg_style' => array(
							'type'    => 'select',
							'label'   => __( 'Icon Background Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle Background', 'xpro-bb-addons' ),
								'square' => __( 'Square Background', 'xpro-bb-addons' ),
								'custom' => __( 'Custom', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'simple' => array(
									'fields' => array( 'icon_color', 'icon_hover_color' ),
								),
								'circle' => array(
									'fields' => array( 'icon_color', 'icon_hover_color', 'icon_bg_color', 'icon_bg_hover_color' ),
								),
								'square' => array(
									'fields' => array( 'icon_color', 'icon_hover_color', 'icon_bg_color', 'icon_bg_hover_color' ),
								),
								'custom' => array(
									'fields' => array( 'icon_color', 'icon_hover_color', 'icon_bg_color', 'icon_bg_hover_color', 'icon_bg_size', 'icon_border_hover_color', 'icon_border' ),
								),
							),
						),
						'icon_bg_size'  => array(
							'type'        => 'unit',
							'label'       => 'Background Size',
							'units'       => array( 'px' ),
							'placeholder' => '72',
							'responsive'  => true,
							'slider'      => true,
							'preview'     => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '.tnit-infoBox .tnit-icon',
										'property' => 'width',
									),
									array(
										'selector' => '.tnit-infoBox .tnit-icon',
										'property' => 'height',
									),
								),
							),
						),
						'icon_border'   => array(
							'type'       => 'border',
							'label'      => 'Icon Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-icon',
							),
						),
					),
				),
				'icon_colors'     => array(
					'title'  => __( 'Icon Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_color'              => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-icon',
								'property' => 'color',
							),
						),
						'icon_hover_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-icon',
								'property' => 'color',
							),
						),
						'icon_bg_color'           => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-icon',
								'property' => 'background-color',
							),
						),
						'icon_bg_hover_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-icon',
								'property' => 'background-color',
							),
						),
						'icon_border_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-icon:',
								'property' => 'border-color',
							),
						),
					),
				),
				'img_basic'       => array(
					'title'  => __( 'Image Basic', 'xpro-bb-addons' ),
					'fields' => array(
						'photo'      => array(
							'type'        => 'photo',
							'label'       => __( 'Photo', 'xpro-bb-addons' ),
							'show_remove' => true,
						),
						'photo_size' => array(
							'type'       => 'unit',
							'label'      => 'Photo Size',
							'units'      => array( 'px' ),
							'responsive' => true,
							'slider'     => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-imgicon-wrap .tnit-info-thumb .tnit-photo-img',
								'property' => 'width',
							),
						),
						'img_border' => array(
							'type'       => 'border',
							'label'      => 'Photo Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-imgicon-wrap .tnit-info-thumb .tnit-photo-img',
							),
						),
					),
				),
				'imgicon_margins' => array(
					'title'  => __( 'Image / Icon Margin', 'xpro-bb-addons' ),
					'fields' => array(
						'imgicon_margin' => array(
							'type'        => 'dimension',
							'label'       => __( 'Margin', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'placeholder' => array(
								'top'    => '0',
								'bottom' => '20',
								'left'   => '0',
								'right'  => '0',
							),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-imgicon-wrap',
								'property' => 'margin',
							),
						),
					),
				),
			),
		),
		'style'      => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'structure'      => array(
					'title'  => 'Structure',
					'fields' => array(
						'imgicon_position'           => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'xpro-bb-addons' ),
							'default' => 'top',
							'options' => array(
								'top'   => __( 'Above the Content', 'xpro-bb-addons' ),
								'left'  => __( 'Left of the Content', 'xpro-bb-addons' ),
								'right' => __( 'Right of the Content', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'left'  => array(
									'fields' => array( 'imgicon_ver_alignment', 'mobile_structure' ),
								),
								'right' => array(
									'fields' => array( 'imgicon_ver_alignment', 'mobile_structure' ),
								),
							),
							'help'    => __( 'Image/Icon position', 'xpro-bb-addons' ),
						),
						'imgicon_ver_alignment'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Verticle Alignment', 'xpro-bb-addons' ),
							'default' => 'top',
							'options' => array(
								'flex-start' => __( 'Top', 'xpro-bb-addons' ),
								'center'     => __( 'Center', 'xpro-bb-addons' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-infobox-holder .tnit-img-position',
								'property' => 'align-items',
							),
							'help'    => __( 'Image/Icon verticle alignment.', 'xpro-bb-addons' ),
						),
						'mobile_structure'           => array(
							'type'    => 'button-group',
							'label'   => __( 'Mobile Structure', 'xpro-bb-addons' ),
							'default' => 'stack',
							'options' => array(
								'inline' => __( 'Inline', 'xpro-bb-addons' ),
								'stack'  => __( 'Stack', 'xpro-bb-addons' ),
							),
							'help'    => __( 'Image / Icon position on mobile.', 'xpro-bb-addons' ),
						),
						'overall_alignment'          => array(
							'type'       => 'align',
							'label'      => __( 'Overall Alignment', 'xpro-bb-addons' ),
							'default'    => 'left',
							'responsive' => true,
							'help'       => 'The alignment that will apply to all elements within the infobox.',
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infobox-holder, .tnit-infoBox',
								'property' => 'text-align',
							),
						),
						'infoboxes_border'           => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox',
							),
						),
						'infoboxes_border_hvr_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover',
								'property' => 'border-color',
							),
						),
						'infobox_padding'            => array(
							'type'       => 'dimension',
							'label'      => 'Box Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => array(
								'placeholder' => array(
									'default' => array(
										'top'    => '0',
										'right'  => '0',
										'bottom' => '0',
										'left'   => '0',
									),
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox',
								'property' => 'padding',
							),
						),
					),
				),
				'general_colors' => array(
					'title'  => __( 'General Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'infoboxes_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox',
								'property' => 'background-color',
							),
						),
						'infoboxes_bg_hvr_color' => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover',
								'property' => 'background-color',
							),
						),
					),
				),
			),
		),
		'link'       => array(
			'title'    => __( 'Link', 'xpro-bb-addons' ),
			'sections' => array(
				'cta_type'       => array(
					'title'  => __( 'Call to Action', 'xpro-bb-addons' ),
					'fields' => array(
						'link_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'xpro-bb-addons' ),
							'default' => 'none',
							'options' => array(
								'none'     => __( 'None', 'xpro-bb-addons' ),
								'title'    => __( 'Title', 'xpro-bb-addons' ),
								'icon'     => __( 'Icon', 'xpro-bb-addons' ),
								'button'   => __( 'Button', 'xpro-bb-addons' ),
								'full-box' => __( 'Full Box', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'title'    => array(
									'sections' => array( 'cta_link' ),
								),
								'icon'     => array(
									'fields'   => array( 'cta_icon_size' ),
									'sections' => array( 'cta_link', 'cta_icons', 'cta_icon_style', 'cta_colors' ),
								),
								'button'   => array(
									'fields'   => array( 'cta_bg_color', 'cta_bg_hvr_color', 'cta_border_hvr_color' ),
									'sections' => array( 'cta_text', 'cta_link', 'cta_icons', 'cta_colors', 'cta_structure', 'cta_text_typography' ),
								),
								'full-box' => array(
									'sections' => array( 'cta_link' ),
								),
							),
						),
					),
				),
				'cta_text'       => array(
					'title'  => __( 'Link Text', 'xpro-bb-addons' ),
					'fields' => array(
						'cta_text' => array(
							'type'        => 'text',
							'label'       => __( 'Text', 'xpro-bb-addons' ),
							'default'     => __( 'Learn More', 'xpro-bb-addons' ),
							'connections' => array( 'string', 'html' ),
							'preview'     => array(
								'type'     => 'text',
								'selector' => '.tnit-cta-text',
							),
						),
					),
				),
				'cta_link'       => array(
					'title'  => __( 'Link', 'xpro-bb-addons' ),
					'fields' => array(
						'link' => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'xpro-bb-addons' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'connections'   => array( 'url' ),
							'preview'       => 'none',
						),
					),
				),
				'cta_icons'      => array(
					'title'  => __( 'CTA Icons', 'xpro-bb-addons' ),
					'fields' => array(
						'cta_icon'          => array(
							'type'        => 'icon',
							'label'       => __( 'Icon', 'xpro-bb-addons' ),
							'default'     => 'fas fa-plus-circle',
							'show_remove' => true,
						),
						'cta_icon_position' => array(
							'type'    => 'button-group',
							'label'   => __( 'Icon Position', 'xpro-bb-addons' ),
							'default' => 'before',
							'options' => array(
								'before' => __( 'Before Text', 'xpro-bb-addons' ),
								'after'  => __( 'After Text', 'xpro-bb-addons' ),
							),
						),
					),
				),
				'cta_icon_style' => array(
					'title'  => __( 'CTA Icon Style', 'xpro-bb-addons' ),
					'fields' => array(
						'cta_icon_animation' => array(
							'type'    => 'button-group',
							'label'   => __( 'Icon Animation', 'xpro-bb-addons' ),
							'default' => 'no',
							'options' => array(
								'no'  => __( 'No', 'xpro-bb-addons' ),
								'yes' => __( 'Yes', 'xpro-bb-addons' ),
							),
						),
						'cta_icon_size'      => array(
							'type'        => 'unit',
							'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'slider'      => true,
							'placeholder' => '24',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'font-size',
							),
						),
					),
				),
				'cta_structure'  => array(
					'title'  => __( 'Structure', 'xpro-bb-addons' ),
					'fields' => array(
						'cta_width'        => array(
							'type'    => 'select',
							'label'   => 'Width',
							'default' => 'auto',
							'options' => array(
								'auto'   => __( 'Auto', 'xpro-bb-addons' ),
								'full'   => __( 'Full Width', 'xpro-bb-addons' ),
								'custom' => __( 'Custom', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'cta_custom_width' ),
								),
							),
						),
						'cta_custom_width' => array(
							'type'    => 'unit',
							'label'   => 'Custom Width',
							'units'   => array( 'px' ),
							'default' => '200',
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'width',
							),
						),
						'cta_alignment'    => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							// 'default'         => 'center',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infobox-button-wrap',
								'property' => 'text-align',
							),
						),
						'cta_padding'      => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => array(
								'placeholder' => array(
									'default' => array(
										'top'    => '12',
										'right'  => '30',
										'bottom' => '12',
										'left'   => '30',
									),
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'padding',
							),
						),
						'cta_border'       => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
							),
						),
					),
				),
				'cta_colors'     => array(
					'title'  => __( 'Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'cta_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'color',
							),
						),
						'cta_hvr_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'color',
							),
						),
						'cta_bg_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'background-color',
							),
						),
						'cta_bg_hvr_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'background-color',
							),
						),
						'cta_border_hvr_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property' => 'border-color',
							),
						),
					),
				),
			),
		),
		'typography' => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'title_prefix'        => array(
					'title'  => __( 'Title Prefix', 'xpro-bb-addons' ),
					'fields' => array(
						'title_prefix_tag'         => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'xpro-bb-addons' ),
							'default' => 'h4',
							'options' => array(
								'h1'   => __( 'H1', 'xpro-bb-addons' ),
								'h2'   => __( 'H2', 'xpro-bb-addons' ),
								'h3'   => __( 'H3', 'xpro-bb-addons' ),
								'h4'   => __( 'H4', 'xpro-bb-addons' ),
								'h5'   => __( 'H5', 'xpro-bb-addons' ),
								'h6'   => __( 'H6', 'xpro-bb-addons' ),
								'div'  => __( 'Div', 'xpro-bb-addons' ),
								'p'    => __( 'p', 'xpro-bb-addons' ),
								'span' => __( 'span', 'xpro-bb-addons' ),
							),
						),
						'title_prefix_typography'  => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-prefix',
							),
						),
						'title_prefix_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-prefix',
								'property' => 'color',
							),
						),
						'title_prefix_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-title-prefix,.tnit-infoBox:hover .tnit-title-prefix a',
								'property' => 'color',
							),
						),
						'title_prefix_padding'     => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-prefix-wrap',
								'property' => 'padding',
							),
						),
						'title_prefix_margin'      => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-prefix-wrap',
								'property' => 'margin',
							),
						),
					),
				),
				'title'               => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'title_tag'         => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'xpro-bb-addons' ),
							'default' => 'h3',
							'options' => array(
								'h1'   => __( 'H1', 'xpro-bb-addons' ),
								'h2'   => __( 'H2', 'xpro-bb-addons' ),
								'h3'   => __( 'H3', 'xpro-bb-addons' ),
								'h4'   => __( 'H4', 'xpro-bb-addons' ),
								'h5'   => __( 'H5', 'xpro-bb-addons' ),
								'h6'   => __( 'H6', 'xpro-bb-addons' ),
								'div'  => __( 'Div', 'xpro-bb-addons' ),
								'p'    => __( 'p', 'xpro-bb-addons' ),
								'span' => __( 'span', 'xpro-bb-addons' ),
							),
						),
						'title_font_typo'   => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .info-title',
							),
						),
						'title_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .info-title',
								'property' => 'color',
							),
						),
						'title_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .info-title,.tnit-infoBox:hover .info-title',
								'property' => 'color',
							),
						),
						'title_padding'     => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .info-title',
								'property' => 'padding',
							),
						),
						'title_margin'      => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .info-title',
								'property' => 'margin',
							),
						),
					),
				),
				'title_postfix'       => array(
					'title'  => __( 'Title Postfix', 'xpro-bb-addons' ),
					'fields' => array(
						'title_postfix_tag'         => array(
							'type'    => 'select',
							'label'   => __( 'HTML Tag', 'xpro-bb-addons' ),
							'default' => 'h4',
							'options' => array(
								'h1'   => __( 'H1', 'xpro-bb-addons' ),
								'h2'   => __( 'H2', 'xpro-bb-addons' ),
								'h3'   => __( 'H3', 'xpro-bb-addons' ),
								'h4'   => __( 'H4', 'xpro-bb-addons' ),
								'h5'   => __( 'H5', 'xpro-bb-addons' ),
								'h6'   => __( 'H6', 'xpro-bb-addons' ),
								'div'  => __( 'Div', 'xpro-bb-addons' ),
								'p'    => __( 'p', 'xpro-bb-addons' ),
								'span' => __( 'span', 'xpro-bb-addons' ),
							),
						),
						'title_postfix_typography'  => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-postfix',
							),
						),
						'title_postfix_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-postfix',
								'property' => 'color',
							),
						),
						'title_postfix_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-title-postfix,.tnit-infoBox:hover .tnit-title-postfix a',
								'property' => 'color',
							),
						),
						'title_postfix_padding'     => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-postfix-wrap',
								'property' => 'padding',
							),
						),
						'title_postfix_margin'      => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-title-postfix-wrap',
								'property' => 'margin',
							),
						),
					),
				),
				'text'                => array(
					'title'  => __( 'Description', 'xpro-bb-addons' ),
					'fields' => array(
						'infoboxes_text_font_typo'   => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-text-wrap',
							),
						),
						'infoboxes_text_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-text-wrap,.tnit-infoBox .tnit-infobox-text-wrap *',
								'property' => 'color',
							),
						),
						'infoboxes_text_hover_color' => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox:hover .tnit-infobox-text-wrap,.tnit-infoBox:hover .tnit-infobox-text-wrap *',
								'property' => 'color',
							),
						),
						'desc_padding'               => array(
							'type'       => 'dimension',
							'label'      => 'Padding',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-text-wrap, .tnit-infoBox .tnit-infobox-text-wrap *',
								'property' => 'padding',
							),
						),
						'desc_margin'                => array(
							'type'       => 'dimension',
							'label'      => 'Margin',
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.tnit-infoBox .tnit-infobox-text-wrap, .tnit-infoBox .tnit-infobox-text-wrap *',
								'property' => 'margin',
							),
						),
					),
				),
				'cta_text_typography' => array(
					'title'  => __( 'CTA Link', 'xpro-bb-addons' ),
					'fields' => array(
						'infoboxes_cta_text_font_typo'     => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow',
								'property'  => 'font',
								'important' => true,
							),
						),
						'infoboxes_cta_text_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Top', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '15',
							'slider'      => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.tnit-infoBox .tnit-infobox-button-wrap',
								'property'  => 'margin-top',
								'important' => true,
							),
						),
						'infoboxes_cta_text_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'slider'      => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.tnit-infoBox .tnit-infobox-button-wrap',
								'property'  => 'margin-bottom',
								'important' => true,
							),
						),
					),
				),
			),
		),
	)
);
