<?php
/**
 * @class TNITFlipBoxModule
 */

class TNITFlipBoxModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Flip Box', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-flipbox/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-flipbox/',
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

		// Register and enqueue your own.
		$this->add_css( 'xpro-default', XPRO_ADDONS_FOR_BB_URL . 'assets/css/default.css', '', '1.5.1' );
		$this->add_css( 'xpro-animate', XPRO_ADDONS_FOR_BB_URL . 'assets/css/animate.css', '', '1.0.0' );
	}

	/**
	 * Function that renders Front Icon
	 *
	 * @method render_front_icon
	 */
	public function render_front_icon() {
		$icon_settings = $this->settings->icon_settings;

		if ( ! empty( $icon_settings->icon ) ) {

			$output  = '<div class="tnit-flipbox-icon-wrap">';
			$output .= '<span class="flipbox-icon flipbox-icon_v1">';
			$output .= '<i class="' . $icon_settings->icon . '" aria-hidden="true"></i>';
			$output .= '</span>';
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Front Title
	 *
	 * @method render_front_title
	 */
	public function render_front_title() {
		if ( '' !== $this->settings->front_title ) {

			$output  = '<' . $this->settings->front_title_tag . ' class="tnit-flipbox-title">';
			$output .= $this->settings->front_title;
			$output .= '</' . $this->settings->front_title_tag . '>';

			echo $output;
		}
	}

	/**
	 * Function that renders Front Description
	 *
	 * @method render_front_description
	 */
	public function render_front_description() {
		if ( '' !== $this->settings->front_description ) {

			global $wp_embed;

			$output  = '<div class="tnit-flipbox-text">';
			$output .= wpautop( $wp_embed->autoembed( $this->settings->front_description ) );
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that get link nofollow
	 *
	 * @method get_back_nofollow
	 */
	public function get_back_nofollow() {
		if ( 'title' === $this->settings->link_type || 'button' === $this->settings->link_type || 'title-button' === $this->settings->link_type ) {
			if ( 'yes' === $this->settings->back_link_nofollow ) {
				$nofollow = ' rel=nofollow';
			} else {
				$nofollow = '';
			}

			return $nofollow;
		}
	}

	/**
	 * Function that renders Back Title
	 *
	 * @method render_back_title
	 */
	public function render_back_title() {
		if ( '' !== $this->settings->back_title ) {

			$output = '<' . $this->settings->back_title_tag . ' class="tnit-flipbox-title">';

			if ( 'title' === $this->settings->link_type || 'title-button' === $this->settings->link_type ) {
				$back_nofollow = $this->get_back_nofollow();

				$output .= '<a href="' . $this->settings->back_link . '" target="' . $this->settings->back_link_target . '" ' . $back_nofollow . '>';
				$output .= $this->settings->back_title;
				$output .= '</a>';
			} else {
				$output .= $this->settings->back_title;
			}

			$output .= '</' . $this->settings->back_title_tag . '>';

			echo $output;
		}
	}

	/**
	 * Function that renders Back Description
	 *
	 * @method render_back_description
	 */
	public function render_back_description() {
		if ( '' !== $this->settings->back_description ) {

			global $wp_embed;

			$output  = '<div class="tnit-flipbox-text">';
			$output .= wpautop( $wp_embed->autoembed( $this->settings->back_description ) );
			$output .= '</div>';

			echo $output;
		}
	}

	/**
	 * Function that renders Back Button
	 *
	 * @method render_back_button
	 */
	public function render_back_button() {
		if ( 'button' === $this->settings->link_type || 'title-button' === $this->settings->link_type ) {
			$button_settings = $this->settings->button_settings;
			$back_nofollow   = $this->get_back_nofollow();

			$output = '<div class="tnit-flipbox-button-wrap">';

			if ( 'text' === $button_settings->button_type ) {
				$output .= '<a href="' . $this->settings->back_link . '" class="tnit-btn-arrow tnit-btn-arrow_v2 tnit-btn-text" target="' . $this->settings->back_link_target . '" ' . $back_nofollow . '>';
				$output .= $button_settings->button_text;
				$output .= '</a>';
			} elseif ( 'icon' === $button_settings->button_type ) {
				$output .= '<a href="' . $this->settings->back_link . '" class="tnit-btn-arrow tnit-btn-icon" target="' . $this->settings->back_link_target . '" ' . $back_nofollow . '>';
				$output .= '<i class="' . $button_settings->button_icon . '" aria-hidden="true"></i>';
				$output .= '</a>';
			}

			$output .= '</div>';

			echo $output;
		}
	}

}


/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITFlipBoxModule',
	array(
		'flipbox_front' => array(
			'title'    => __( 'Flip Box Front', 'xpro-bb-addons' ),
			'sections' => array(
				'front_icon'        => array(
					'title'  => 'Front Icon',
					'fields' => array(
						'icon_settings' => array(
							'type'         => 'form',
							'label'        => __( 'Icon Settings', 'xpro-bb-addons' ),
							'form'         => 'tnit_flipbox_icon_form',
							'preview_text' => 'icon',
						),
					),
				),
				'front_title'       => array(
					'title'  => 'Front Title',
					'fields' => array(
						'front_title'               => array(
							'type'        => 'text',
							'label'       => __( 'Title', 'xpro-bb-addons' ),
							'default'     => __( 'Website Design', 'xpro-bb-addons' ),
							'connections' => array( 'string', 'html' ),
						),
						'front_title_tag'           => array(
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
						'front_title_font'          => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'front_title_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'front_title_margin_top'    => array(
							'type'        => 'unit',
							'label'       => 'Margin Top',
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
							'slider'      => true,
						),
						'front_title_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => 'Margin Bottom',
							'units'       => array( 'px' ),
							'placeholder' => '15',
							'responsive'  => true,
							'slider'      => true,
						),
					),
				),
				'front_description' => array(
					'title'  => 'Front Description',
					'fields' => array(
						'front_description'               => array(
							'type'          => 'editor',
							'media_buttons' => false,
							'wpautop'       => false,
							'default'       => __( 'Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'xpro-bb-addons' ),
							'connections'   => array( 'string', 'html' ),
						),
						'front_description_font'          => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'front_description_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'front_description_margin_top'    => array(
							'type'        => 'unit',
							'label'       => 'Margin Top',
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
							'slider'      => true,
						),
						'front_description_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => 'Margin Bottom',
							'units'       => array( 'px' ),
							'placeholder' => '15',
							'responsive'  => true,
							'slider'      => true,
						),
					),
				),
				'front_style'       => array(
					'title'  => 'Front Style',
					'fields' => array(
						'front_bg_type'          => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color' => 'Color',
								'photo' => 'Photo',
							),
							'toggle'  => array(
								'color' => array(
									'fields' => array( 'front_bg_color' ),
								),
								'photo' => array(
									'fields' => array( 'front_bg_photo', 'front_bg_overlay' ),
								),
							),
						),
						'front_bg_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'front_bg_photo'         => array(
							'type'        => 'photo',
							'label'       => __( 'Background Photo', 'fl-builder' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'front_bg_overlay'       => array(
							'type'        => 'color',
							'label'       => __( 'Background Overlay', 'xpro-bb-addons' ),
							'default'     => 'rgba(0,0,0,0.6)',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'front_border_type'      => array(
							'type'    => 'button-group',
							'label'   => __( 'Border Type', 'xpro-bb-addons' ),
							'default' => 'custom',
							'options' => array(
								'corners' => 'Corners',
								'custom'  => 'Custom',
							),
							'toggle'  => array(
								'corners' => array(
									'fields' => array( 'front_corners_color', 'front_corners_thikness' ),
								),
								'custom'  => array(
									'fields' => array( 'front_border' ),
								),
							),
						),
						'front_border'           => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => array(
								'shadow' => array(
									'color'      => 'rgba(0,0,0,0.04)',
									'horizontal' => '0',
									'vertical'   => '0',
									'blur'       => '40',
									'spread'     => '0',
								),
							),
						),
						'front_corners_color'    => array(
							'type'        => 'color',
							'label'       => __( 'Corners Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'connections' => array( 'color' ),
						),
						'front_corners_thikness' => array(
							'type'        => 'unit',
							'label'       => __( 'Corners Thikness', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '5',
							'slider'      => true,
						),
						'front_padding'          => array(
							'type'        => 'dimension',
							'label'       => __( 'Front Padding', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => array(
								'top'    => 30,
								'right'  => 20,
								'bottom' => 30,
								'left'   => 20,
							),
							'slider'      => true,
							'responsive'  => true,
						),
					),
				),
			),
		),
		'flipbox_back'  => array(
			'title'    => __( 'Flip Box Back', 'xpro-bb-addons' ),
			'sections' => array(
				'back_title'       => array(
					'title'  => 'Back Title',
					'fields' => array(
						'back_title'               => array(
							'type'        => 'text',
							'label'       => __( 'Title', 'xpro-bb-addons' ),
							'default'     => __( 'Website Design', 'xpro-bb-addons' ),
							'connections' => array( 'string', 'html' ),
						),
						'back_title_tag'           => array(
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
						'back_title_font'          => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'back_title_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'back_title_margin_top'    => array(
							'type'        => 'unit',
							'label'       => 'Margin Top',
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
							'slider'      => true,
						),
						'back_title_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => 'Margin Bottom',
							'units'       => array( 'px' ),
							'placeholder' => '10',
							'responsive'  => true,
							'slider'      => true,
						),
					),
				),
				'back_description' => array(
					'title'  => 'Back Description',
					'fields' => array(
						'back_description'               => array(
							'type'          => 'editor',
							'media_buttons' => false,
							'wpautop'       => false,
							'default'       => __( 'Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'xpro-bb-addons' ),
							'connections'   => array( 'string', 'html' ),
						),
						'back_description_font'          => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'back_description_color'         => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'back_description_margin_top'    => array(
							'type'        => 'unit',
							'label'       => 'Margin Top',
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
							'slider'      => true,
						),
						'back_description_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => 'Margin Bottom',
							'units'       => array( 'px' ),
							'placeholder' => '15',
							'responsive'  => true,
							'slider'      => true,
						),
					),
				),
				'back_style'       => array(
					'title'  => 'Back Style',
					'fields' => array(
						'back_bg_type'    => array(
							'type'    => 'button-group',
							'label'   => __( 'Background Type', 'xpro-bb-addons' ),
							'default' => 'color',
							'options' => array(
								'color' => 'Color',
								'photo' => 'Photo',
							),
							'toggle'  => array(
								'color' => array(
									'fields' => array( 'back_bg_color' ),
								),
								'photo' => array(
									'fields' => array( 'back_bg_photo', 'back_bg_overlay' ),
								),
							),
						),
						'back_bg_color'   => array(
							'type'        => 'color',
							'label'       => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'back_bg_photo'   => array(
							'type'        => 'photo',
							'label'       => __( 'Background Photo', 'fl-builder' ),
							'show_remove' => true,
							'connections' => array( 'photo' ),
						),
						'back_bg_overlay' => array(
							'type'        => 'color',
							'label'       => __( 'Background Overlay', 'xpro-bb-addons' ),
							'default'     => 'rgba(0,0,0,0.6)',
							'show_reset'  => true,
							'show_alpha'  => true,
							'connections' => array( 'color' ),
						),
						'back_border'     => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
							'default'    => array(
								'shadow' => array(
									'color'      => 'rgba(0,0,0,0.04)',
									'horizontal' => '0',
									'vertical'   => '0',
									'blur'       => '40',
									'spread'     => '0',
								),
							),
						),
						'back_padding'    => array(
							'type'        => 'dimension',
							'label'       => __( 'Back Padding', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'placeholder' => array(
								'top'    => 30,
								'right'  => 20,
								'bottom' => 30,
								'left'   => 20,
							),
						),
					),
				),
				'back_link'        => array(
					'title'  => 'Back Link',
					'fields' => array(
						'link_type'       => array(
							'type'    => 'select',
							'label'   => __( 'Link Type', 'xpro-bb-addons' ),
							'default' => 'title-button',
							'options' => array(
								'none'         => 'None',
								'title'        => 'Title Only',
								'button'       => 'Button Only',
								'title-button' => 'Title & Button',
							),
							'toggle'  => array(
								'title'        => array(
									'fields' => array( 'back_link' ),
								),
								'button'       => array(
									'fields' => array( 'back_link', 'button_settings' ),
								),
								'title-button' => array(
									'fields' => array( 'back_link', 'button_settings' ),
								),
							),
						),
						'back_link'       => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'xpro-bb-addons' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'connections'   => array( 'url' ),
						),
						'button_settings' => array(
							'type'         => 'form',
							'label'        => __( 'Button Settings', 'xpro-bb-addons' ),
							'form'         => 'tnit_flipbox_button_form',
							'preview_text' => 'button_type',
						),
					),
				),
			),
		),
		'style'         => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => 'General',
					'fields' => array(
						'flip_type' => array(
							'type'    => 'select',
							'label'   => __( 'Flip Type', 'xpro-bb-addons' ),
							'default' => 'horizontal',
							'options' => array(
								'horizontal' => 'Horizontal',
								'vertical'   => 'Vertical',
								'zoomIn'     => 'Zoom In',
								'skewUp'     => 'Skew',
							),
						),
					),
				),
			),
		),
	)
);


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form(
	'tnit_flipbox_icon_form',
	array(
		'title' => __( 'Add Icon', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general'      => array(
						'title'  => 'Icon Basics',
						'fields' => array(
							'icon'         => array(
								'type'        => 'icon',
								'label'       => __( 'Choose Icon', 'xpro-bb-addons' ),
								'default'     => 'fas fa-pencil-alt',
								'show_remove' => true,
							),
							'icon_size'    => array(
								'type'        => 'unit',
								'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
								'units'       => array( 'px' ),
								'placeholder' => '50',
								'responsive'  => true,
								'slider'      => true,
							),
							'icon_bg_size' => array(
								'type'        => 'unit',
								'label'       => __( 'Background Size', 'xpro-bb-addons' ),
								'units'       => array( 'px' ),
								'placeholder' => '100',
								'responsive'  => true,
								'slider'      => true,
							),
						),
					),
					'icon_style'   => array(
						'title'  => __( 'Icon Style', 'xpro-bb-addons' ),
						'fields' => array(
							'icon_color'    => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_bg_color' => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_border'   => array(
								'type'    => 'border',
								'label'   => __( 'Border', 'xpro-bb-addons' ),
								'default' => array(
									'style'  => 'solid',
									'color'  => '8bc34a',
									'width'  => array(
										'top'    => '1',
										'right'  => '1',
										'bottom' => '1',
										'left'   => '1',
									),
									'radius' => array(
										'top_left'     => '100',
										'top_right'    => '100',
										'bottom_left'  => '100',
										'bottom_right' => '100',
									),
								),
							),
						),
					),
					'icon_margins' => array(
						'title'  => __( 'Icon Margins', 'xpro-bb-addons' ),
						'fields' => array(
							'imgicon_margin_top'    => array(
								'type'        => 'unit',
								'label'       => 'Top',
								'units'       => array( 'px' ),
								'placeholder' => '0',
								'responsive'  => true,
								'slider'      => true,
							),
							'imgicon_margin_bottom' => array(
								'type'        => 'unit',
								'label'       => 'Bottom',
								'units'       => array( 'px' ),
								'placeholder' => '30',
								'responsive'  => true,
								'slider'      => true,
							),
						),
					),
				),
			),
		),
	)
);


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form(
	'tnit_flipbox_button_form',
	array(
		'title' => __( 'Add Button', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general'    => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general'          => array(
						'title'  => __( 'Basics', 'xpro-bb-addons' ),
						'fields' => array(
							'button_type'      => array(
								'type'    => 'button-group',
								'label'   => __( 'Button Type', 'xpro-bb-addons' ),
								'default' => 'text',
								'options' => array(
									'text' => __( 'Text', 'xpro-bb-addons' ),
									'icon' => __( 'Icon', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'text' => array(
										'fields'   => array( 'button_text', 'button_bg_color', 'button_bg_hvr_color', 'button_border_hvr_color' ),
										'sections' => array( 'button_structure' ),
										'tabs'     => array( 'typography' ),
									),
									'icon' => array(
										'fields' => array( 'button_icon', 'button_icon_size' ),
									),
								),
							),
							'button_text'      => array(
								'type'    => 'text',
								'label'   => __( 'Button Text', 'fl-builder' ),
								'default' => 'Learn More',
							),
							'button_icon'      => array(
								'type'        => 'icon',
								'label'       => __( 'Button Icon', 'xpro-bb-addons' ),
								'default'     => 'fas fa-arrow-right',
								'show_remove' => true,
							),
							'button_icon_size' => array(
								'type'        => 'unit',
								'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
								'units'       => array( 'px' ),
								'placeholder' => '24',
								'responsive'  => true,
								'slider'      => true,
							),
						),
					),
					'button_structure' => array(
						'title'  => __( 'Structure', 'xpro-bb-addons' ),
						'fields' => array(
							'button_width'        => array(
								'type'    => 'select',
								'label'   => __( 'Button Width', 'xpro-bb-addons' ),
								'default' => 'auto',
								'options' => array(
									'auto'   => __( 'Auto', 'xpro-bb-addons' ),
									'full'   => __( 'Full Width', 'xpro-bb-addons' ),
									'custom' => __( 'Custom Width', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'custom' => array(
										'fields' => array( 'button_custom_width' ),
									),
								),
							),
							'button_custom_width' => array(
								'type'       => 'unit',
								'label'      => 'Custom Width',
								'units'      => array( 'px' ),
								'default'    => '200',
								'slider'     => true,
								'responsive' => true,
							),
							'button_border'       => array(
								'type'    => 'border',
								'label'   => __( 'Border', 'xpro-bb-addons' ),
								'default' => array(
									'style'  => 'solid',
									'color'  => 'ffffff',
									'width'  => array(
										'top'    => '1',
										'right'  => '1',
										'bottom' => '1',
										'left'   => '1',
									),
									'radius' => array(
										'top_left'     => '30',
										'top_right'    => '30',
										'bottom_left'  => '30',
										'bottom_right' => '30',
									),
								),
							),
							'button_padding'      => array(
								'type'        => 'dimension',
								'label'       => 'Padding',
								'units'       => array( 'px' ),
								'slider'      => true,
								'responsive'  => true,
								'placeholder' => array(
									'top'    => 13,
									'right'  => 35,
									'bottom' => 13,
									'left'   => 35,
								),
							),
						),
					),
					'button_colors'    => array(
						'title'  => __( 'Colors', 'xpro-bb-addons' ),
						'fields' => array(
							'button_color'            => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'button_hvr_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'button_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'button_bg_hvr_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'button_border_hvr_color' => array(
								'type'       => 'color',
								'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
					'button_margins'   => array(
						'title'  => __( 'Margins', 'xpro-bb-addons' ),
						'fields' => array(
							'button_margin_top'    => array(
								'type'        => 'unit',
								'label'       => 'Top',
								'units'       => array( 'px' ),
								'placeholder' => '0',
								'responsive'  => true,
								'slider'      => true,
							),
							'button_margin_bottom' => array(
								'type'        => 'unit',
								'label'       => 'Bottom',
								'units'       => array( 'px' ),
								'placeholder' => '0',
								'responsive'  => true,
								'slider'      => true,
							),
						),
					),
				),
			),
			'typography' => array(
				'title'    => __( 'Typography', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'Button', 'xpro-bb-addons' ),
						'fields' => array(
							'button_font' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
							),
						),
					),
				),
			),
		),
	)
);
