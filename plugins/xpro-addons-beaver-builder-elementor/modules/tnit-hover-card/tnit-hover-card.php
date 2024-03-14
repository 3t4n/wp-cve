<?php
/**
 * @class TNITHoverCard
 */

class TNITHoverCard extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Hover Card', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$media_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-hover-card/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-hover-card/',
				'partial_refresh' => true,
			)
		);

	}

	/**
	 * Function that renders Icon
	 *
	 * @method render_photo
	 */
	public function render_photo( $i ) {
		$hover_card = $this->settings->hcard_form_items[ $i ];
		$output     = '';

		/**
		 * Get photo data
		 *
		 * @variable $photo
		 */
		if ( ! empty( $hover_card->photo ) ) {
			$photo = FLBuilderPhoto::get_attachment_data( $hover_card->photo );

			// get src.
			$src = $hover_card->photo_src;
			$alt = '';

			// get alt.
			if ( ! empty( $photo->alt ) ) {
				$alt = htmlspecialchars( $photo->alt );
			} elseif ( ! empty( $photo->description ) ) {
				$alt = htmlspecialchars( $photo->description );
			} elseif ( ! empty( $photo->caption ) ) {
				$alt = htmlspecialchars( $photo->caption );
			} elseif ( ! empty( $photo->title ) ) {
				$alt = htmlspecialchars( $photo->title );
			}

			// get classes.
			$photo_classes = array( 'tnit-hover-card-img' );

			if ( is_object( $photo ) ) {
				$photo_classes[] = 'wp-image-' . $photo->id;

				if ( isset( $photo->sizes ) ) {
					foreach ( $photo->sizes as $key => $size ) {

						if ( $size->url === $hover_card->photo_src ) {
							$photo_classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}
			$photo_classes = implode( ' ', $photo_classes );
			$output       .= '<img src="' . $src . '" class="' . $photo_classes . '" alt="' . $alt . '">';
		}
		echo $output;
	}

	/**
	 * Returns a gradient value string. Must be passed a
	 * gradient setting array from a gradient field.
	 *
	 * @since 1.1.3
	 * @param array $setting
	 * @return string
	 */
	function tnit_form_gradient( $setting ) {
		$gradient = '';
		$values   = array();
		$setting  = json_decode( json_encode( $setting ), true );

		if ( ! is_array( $setting ) ) {
			return $gradient;
		}

		foreach ( $setting['colors'] as $i => $color ) {
			$stop = $setting['stops'][ $i ];

			if ( empty( $color ) ) {
				$color = 'rgba(255,255,255,0)';
			}
			if ( ! strstr( $color, 'rgb' ) ) {
				$color = '#' . $color;
			}
			if ( ! is_numeric( $stop ) ) {
				$stop = 0;
			}

			$values[] = $color . ' ' . $stop . '%';
		}

		$values = implode( ', ', $values );

		if ( 'linear' === $setting['type'] ) {
			if ( ! is_numeric( $setting['angle'] ) ) {
				$setting['angle'] = 0;
			}
			$gradient = 'linear-gradient(' . $setting['angle'] . 'deg, ' . $values . ')';
		} else {
			$gradient = 'radial-gradient(at ' . $setting['position'] . ', ' . $values . ')';
		}

		return $gradient;
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITHoverCard',
	array(
		'general'            => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'style_type'       => array(
					'title'  => '',
					'fields' => array(
						'hover_card_style'  => array(
							'type'    => 'select',
							'label'   => __( 'Select Style', 'xpro-bb-addons' ),
							'default' => 'style-1',
							'options' => array(
								'style-1'  => __( 'Style 1', 'xpro-bb-addons' ),
								'style-2'  => __( 'Style 2', 'xpro-bb-addons' ),
								'style-3'  => __( 'Style 3', 'xpro-bb-addons' ),
								'style-4'  => __( 'Style 4', 'xpro-bb-addons' ),
								'style-5'  => __( 'Style 5', 'xpro-bb-addons' ),
								'style-6'  => __( 'Style 6', 'xpro-bb-addons' ),
								'style-7'  => __( 'Style 7', 'xpro-bb-addons' ),
								'style-8'  => __( 'Style 8', 'xpro-bb-addons' ),
								'style-9'  => __( 'Style 9', 'xpro-bb-addons' ),
								'style-10' => __( 'Style 10', 'xpro-bb-addons' ),
								'style-11' => __( 'Style 11', 'xpro-bb-addons' ),
								'style-12' => __( 'Style 12', 'xpro-bb-addons' ),
							),
						),
						'gutter_size'       => array(
							'type'       => 'unit',
							'label'      => __( 'Gutter Size', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '30',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'hover_card_height' => array(
							'type'       => 'unit',
							'label'      => __( 'Height', 'xpro-bb-addons' ),
							'units'      => array( 'px' ),
							'slider'     => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '350',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'help'       => __( 'Height will also control the width of cards in Style 8.', 'xpro-bb-addons' ),
						),
					),
				),
				'hover_card_count' => array(
					'title'  => __( 'Number of Cards in a row', 'xpro-bb-addons' ),
					'fields' => array(
						'grid_numbers' => array(
							'type'       => 'unit',
							'label'      => __( 'Grid Numbers', 'xpro-bb-addons' ),
							'units'      => array( 'columns' ),
							'slider'     => true,
							'responsive' => array(
								'placeholder' => array(
									'default'    => '4',
									'medium'     => '2',
									'responsive' => '1',
								),
							),
							'help'       => __( 'This is how many Hovercards you want to show at one time on desktop, tablet and mobile.', 'xpro-bb-addons' ),
						),
					),
				),
			),
		),
		'hover_card_content' => array(
			'title'    => __( 'Hover Cards', 'xpro-bb-addons' ),
			'sections' => array(
				'hover_card_content' => array(
					'title'  => '',
					'fields' => array(
						'hcard_form_items' => array(
							'type'         => 'form',
							'label'        => __( 'Hover Card', 'xpro-bb-addons' ),
							'form'         => 'hover_card_form',
							'preview_text' => 'label',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'style'              => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'style'             => array(
					'title'  => 'Hover Card',
					'fields' => array(
						'outer_overall_alignment' => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
						),
						'hover_card_box_padding'  => array(
							'type'        => 'dimension',
							'label'       => __( 'Padding', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'placeholder' => array(
								'top'    => '20',
								'right'  => '35',
								'bottom' => '20',
								'left'   => '35',
							),
						),
						'card_box_border'         => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
						),
					),
				),
				'title_style'       => array(
					'title'     => __( 'Title', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'label_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'label_hover_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'title_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '14',
							'responsive'  => true,
						),
					),
				),
				'description_style' => array(
					'title'     => __( 'Description', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'des_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'des_hover_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
						),
						'des_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '22',
							'responsive'  => true,
						),
					),
				),
				'icon_style'        => array(
					'title'     => __( 'Icon', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'icon_size'          => array(
							'type'        => 'unit',
							'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '40',
							'responsive'  => true,
						),
						'icon_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_hover_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Top', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
						),
						'icon_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '18',
							'responsive'  => true,
						),
					),
				),
				'button_style'      => array(
					'title'     => __( 'Button', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'button_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Text / Icon Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'button_hover_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Text / Icon Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'button_bg_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'button_bg_hvr_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'button_border'        => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'button_hvr_border'    => array(
							'type'       => 'border',
							'label'      => __( 'Border Hover', 'xpro-bb-addons' ),
							'responsive' => true,
						),
						'button_margin_top'    => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Top', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
						),
						'button_margin_bottom' => array(
							'type'        => 'unit',
							'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
							'slider'      => true,
							'units'       => array( 'px' ),
							'placeholder' => '0',
							'responsive'  => true,
						),
					),
				),
			),
		),
		'typography'         => array(
			'title'    => __( 'Typography', 'xpro-bb-addons' ),
			'sections' => array(
				'label_typography'   => array(
					'title'  => __( 'Title', 'xpro-bb-addons' ),
					'fields' => array(
						'label_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
					),
				),
				'content_typography' => array(
					'title'  => __( 'Description', 'xpro-bb-addons' ),
					'fields' => array(
						'content_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
						),
					),
				),
				'button_typography'  => array(
					'title'  => __( 'Button', 'xpro-bb-addons' ),
					'fields' => array(
						'button_typography' => array(
							'type'       => 'typography',
							'label'      => __( 'Typography', 'xpro-bb-addons' ),
							'responsive' => true,
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
	'hover_card_form',
	array(
		'title' => __( 'Add Hover Card', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general'      => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => 'General',
						'fields' => array(
							'photo'       => array(
								'type'        => 'photo',
								'label'       => __( 'Background Image', 'tnit' ),
								'show_remove' => true,
							),
							'label'       => array(
								'type'        => 'text',
								'label'       => __( 'Title', 'xpro-bb-addons' ),
								'placeholder' => __( 'Enter Label', 'xpro-bb-addons' ),
								'default'     => __( 'Enter Label', 'xpro-bb-addons' ),
							),
							'description' => array(
								'type'        => 'textarea',
								'label'       => __( 'Description', 'xpro-bb-addons' ),
								'placeholder' => __( 'Enter Description', 'xpro-bb-addons' ),
								'default'     => __( 'Enter Description', 'xpro-bb-addons' ),
								'rows'        => '6',
							),
						),
					),
				),
			),
			'styles'       => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'static_content_Styling' => array(
						'title'  => 'Hover Card',
						'fields' => array(
							'overall_alignment' => array(
								'type'       => 'align',
								'label'      => __( 'Alignment', 'xpro-bb-addons' ),
								'default'    => 'center',
								'responsive' => true,
							),
						),
					),
					'bgimage_overlay'        => array(
						'title'  => __( 'Overlay', 'xpro-bb-addons' ),
						'fields' => array(
							'bg_overlay_color_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Overlay Type', 'xpro-bb-addons' ),
								'default' => 'option-1',
								'options' => array(
									'color'    => __( 'Color', 'xpro-bb-addons' ),
									'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'color'    => array(
										'fields' => array( 'bg_overlay_color' ),
									),
									'gradient' => array(
										'fields' => array( 'bg_overlay_gradient' ),
									),
								),
							),
							'bg_overlay_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Overlay Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'bg_overlay_gradient'   => array(
								'type'  => 'gradient',
								'label' => __( 'Overlay Gradient', 'xpro-bb-addons' ),
							),
						),
					),
					'image_overlay'          => array(
						'title'  => __( 'Hover Overlay', 'xpro-bb-addons' ),
						'fields' => array(
							'overlay_color_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Overlay Type', 'xpro-bb-addons' ),
								'default' => 'option-1',
								'options' => array(
									'color'    => __( 'Color', 'xpro-bb-addons' ),
									'gradient' => __( 'Gradient', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'color'    => array(
										'fields' => array( 'overlay_color' ),
									),
									'gradient' => array(
										'fields' => array( 'overlay_gradient' ),
									),
								),
							),
							'overlay_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Overlay Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'overlay_gradient'   => array(
								'type'  => 'gradient',
								'label' => __( 'Overlay Gradient', 'xpro-bb-addons' ),
							),
						),
					),
					'title_style'            => array(
						'title'  => __( 'Title', 'xpro-bb-addons' ),
						'fields' => array(
							'hover_card_title_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'hover_card_title_color_h' => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
					'description_style'      => array(
						'title'  => __( 'Description', 'xpro-bb-addons' ),
						'fields' => array(
							'hover_card_description_color' => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'hover_card_description_color_h' => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
						),
					),
				),
			),
			'icon_tab'     => array(
				'title'    => __( 'Icon', 'xpro-bb-addons' ),
				'sections' => array(
					'icon_sec'         => array(
						'title'  => 'Icon Basics',
						'fields' => array(
							'card_icon' => array(
								'type'    => 'button-group',
								'label'   => __( 'Show Icon', 'xpro-bb-addons' ),
								'default' => 'no',
								'options' => array(
									'yes' => __( 'Yes', 'xpro-bb-addons' ),
									'no'  => __( 'No', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'yes' => array(
										'sections' => array( 'card_icon_style', 'card_icon_margin' ),
										'fields'   => array( 'icon' ),
									),
									'no'  => array(
										'fields' => array( '' ),
									),
								),
							),
							'icon'      => array(
								'type'        => 'icon',
								'label'       => __( 'Upload Icon', 'xpro-bb-addons' ),
								'default'     => 'fas fa-mail-bulk',
								'show_remove' => true,
							),

						),
					),
					'card_icon_style'  => array(
						'title'  => __( 'Icon Styles', 'xpro-bb-addons' ),
						'fields' => array(
							'card_icon_size'        => array(
								'type'        => 'unit',
								'placeholder' => '40',
								'label'       => __( 'Size', 'xpro-bb-addons' ),
								'units'       => array( 'px' ),
								'slider'      => true,
								'responsive'  => true,
							),
							'card_icon_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'card_icon_hover_color' => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
					'card_icon_margin' => array(
						'title'  => __( 'Margins', 'xpro-bb-addons' ),
						'fields' => array(
							'icon_margin_top'    => array(
								'type'        => 'unit',
								'label'       => __( 'Margin Top', 'xpro-bb-addons' ),
								'slider'      => true,
								'units'       => array( 'px' ),
								'placeholder' => '0',
								'responsive'  => true,
							),
							'icon_margin_bottom' => array(
								'type'        => 'unit',
								'label'       => __( 'Margin Bottom', 'xpro-bb-addons' ),
								'slider'      => true,
								'units'       => array( 'px' ),
								'placeholder' => '18',
								'responsive'  => true,
							),
						),
					),
				),
			),
			'seprator_tab' => array(
				'title'    => __( 'Seprator', 'xpro-bb-addons' ),
				'sections' => array(
					'separator_sec' => array(
						'title'  => 'Seprator',
						'fields' => array(
							'border_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Border Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'separator_thickness' => array(
								'type'       => 'unit',
								'label'      => __( 'Thickness', 'xpro-bb-addons' ),
								'slider'     => true,
								'units'      => array( 'px' ),
								'responsive' => true,
								'help'       => __( 'Adjust thikness of the border.', 'xpro-bb-addons' ),
							),
							'separator_height'    => array(
								'type'        => 'unit',
								'label'       => __( 'Height', 'xpro-bb-addons' ),
								'slider'      => true,
								'units'       => array( 'px' ),
								'placeholder' => '40',
								'responsive'  => true,
								'help'        => __( 'Adjust height of the border.', 'xpro-bb-addons' ),
							),
							'separator_width'     => array(
								'type'         => 'unit',
								'label'        => __( 'Width', 'xpro-bb-addons' ),
								'slider'       => true,
								'units'        => array( 'px', '%' ),
								'default_unit' => '%',
								'responsive'   => true,
								'help'         => __( 'Adjust width of the border.', 'xpro-bb-addons' ),
							),
							'separator_margin'    => array(
								'type'        => 'dimension',
								'label'       => 'Margin',
								'units'       => array( 'px' ),
								'slider'      => true,
								'responsive'  => true,
								'placeholder' => array(
									'top'    => '5',
									'right'  => '0',
									'bottom' => '5',
									'left'   => '0',
								),
							),

						),
					),
				),
			),
			'link'         => array(
				'title'    => __( 'Link', 'xpro-bb-addons' ),
				'sections' => array(
					'cta_type'      => array(
						'title'  => __( 'Call to Action', 'xpro-bb-addons' ),
						'fields' => array(
							'link_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Link Type', 'xpro-bb-addons' ),
								'default' => 'button',
								'options' => array(
									'none'   => __( 'None', 'xpro-bb-addons' ),
									'icon'   => __( 'Icon', 'xpro-bb-addons' ),
									'button' => __( 'Button', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'button' => array(
										'fields'   => array( 'cta_text', 'cta_bg_color', 'cta_bg_hvr_color', 'cta_border_hvr_color' ),
										'sections' => array( 'cta_link', 'cta_colors', 'cta_structure', 'cta_text_typography' ),
									),
									'icon'   => array(
										'fields'   => array( 'cta_icon', 'cta_icon_size' ),
										'sections' => array( 'cta_link', 'cta_colors' ),
									),
								),
							),
						),
					),
					'cta_link'      => array(
						'title'  => __( 'Link', 'xpro-bb-addons' ),
						'fields' => array(
							'cta_text'      => array(
								'type'    => 'text',
								'label'   => __( 'Text', 'xpro-bb-addons' ),
								'default' => __( 'Learn More', 'xpro-bb-addons' ),
							),
							'cta_icon'      => array(
								'type'        => 'icon',
								'label'       => __( 'Icon', 'xpro-bb-addons' ),
								'default'     => 'fas fa-plus-circle',
								'show_remove' => true,
							),
							'cta_icon_size' => array(
								'type'        => 'unit',
								'label'       => __( 'Icon Size', 'xpro-bb-addons' ),
								'units'       => array( 'px' ),
								'slider'      => true,
								'placeholder' => '14',
							),
							'button_link'   => array(
								'type'          => 'link',
								'label'         => __( 'Link', 'xpro-bb-addons' ),
								'show_target'   => true,
								'show_nofollow' => true,
							),
						),
					),
					'cta_colors'    => array(
						'title'  => __( 'Colors', 'xpro-bb-addons' ),
						'fields' => array(
							'cta_color'        => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'cta_hvr_color'    => array(
								'type'       => 'color',
								'label'      => __( 'Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'cta_bg_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
							'cta_bg_hvr_color' => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
							),
						),
					),
					'cta_structure' => array(
						'title'  => __( 'Structure', 'xpro-bb-addons' ),
						'fields' => array(
							'cta_width'         => array(
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
										'fields' => array( 'cta_custom_width', 'cta_custom_height' ),
									),
								),
							),
							'cta_custom_width'  => array(
								'type'    => 'unit',
								'label'   => 'Custom Width',
								'units'   => array( 'px' ),
								'default' => '200',
								'slider'  => true,
								'help'    => __( 'Maximum width will be 100% of hover card.', 'xpro-bb-addons' ),
							),
							'cta_padding'       => array(
								'type'        => 'dimension',
								'label'       => 'Padding',
								'units'       => array( 'px' ),
								'slider'      => true,
								'responsive'  => true,
								'placeholder' => array(
									'top'    => '12',
									'right'  => '25',
									'bottom' => '12',
									'left'   => '25',
								),
							),
							'cta_border'        => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
							),
							'button_hvr_border' => array(
								'type'       => 'border',
								'label'      => __( 'Border Hover', 'xpro-bb-addons' ),
								'responsive' => true,
							),
						),
					),
				),
			),
		),
	)
);
