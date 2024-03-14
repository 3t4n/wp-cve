<?php
/**
 * @class TNITSocialIconsModule
 */

class TNITSocialIconsModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Social Icons', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$social_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-social-icons/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-social-icons/',
				'partial_refresh' => true,
			)
		);

	}


	/**
	 * Function that renders Social Icon
	 *
	 * @method render_social_icon
	 */
	public function render_social_icon( $i ) {
		$settings    = $this->settings;
		$social_icon = $settings->social_icons[ $i ];

		$output = '';

		if ( 'icon' === $social_icon->image_type && ! empty( $social_icon->icon ) ) {
			$output .= '<span class="tnit-social-icon tnit-social-icon-icon">';
			$output .= '<i class="' . $social_icon->icon . '"></i>';
			$output .= '</span>';
		}

		echo $output;
	}

	/**
	 * Function that renders Social Photo
	 *
	 * @method render_social_photo
	 */
	public function render_social_photo( $i ) {
		$settings    = $this->settings;
		$social_icon = $settings->social_icons[ $i ];

		/**
		 * Get photo data
		 *
		 * @variable $photo
		 */
		if ( ! empty( $social_icon->photo ) ) {
			$photo = FLBuilderPhoto::get_attachment_data( $social_icon->photo );

			// get src.
			$src = $social_icon->photo_src;
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
			$photo_classes = array( 'tnit-photo' );

			if ( is_object( $photo ) ) {
				$photo_classes[] = 'wp-image-' . $photo->id;

				if ( isset( $photo->sizes ) ) {
					foreach ( $photo->sizes as $key => $size ) {
						if ( $size->url === $social_icon->photo_src ) {
							$photo_classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}

			$photo_classes = implode( ' ', $photo_classes );
		}

		if ( 'photo' === $social_icon->image_type ) {
			$output  = '<span class="tnit-social-icon tnit-social-icon-image">';
			$output .= '<img src="' . $src . '" class="' . $photo_classes . '" alt="' . $alt . '">';
			$output .= '</span>';

			echo $output;
		}
	}


}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITSocialIconsModule',
	array(
		'social_icons' => array(
			'title'    => __( 'Social Icons', 'xpro-bb-addons' ),
			'sections' => array(
				'title' => array(
					'title'  => '',
					'fields' => array(
						'social_icons' => array(
							'type'         => 'form',
							'label'        => __( 'Social Icons', 'xpro-bb-addons' ),
							'form'         => 'tnit_social_icon_form',
							'preview_text' => 'label',
							'multiple'     => true,
						),
					),
				),
			),
		),
		'style'        => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'structure' => array(
					'title'  => __( 'Structure', 'xpro-bb-addons' ),
					'fields' => array(
						'social_icon_layout'   => array(
							'type'    => 'button-group',
							'label'   => __( 'Layout', 'xpro-bb-addons' ),
							'default' => 'horizontal',
							'options' => array(
								'horizontal' => __( 'Horizontal', 'xpro-bb-addons' ),
								'vertical'   => __( 'Vertical', 'xpro-bb-addons' ),
							),
						),
						'align'                => array(
							'type'       => 'align',
							'label'      => __( 'Alignment', 'xpro-bb-addons' ),
							'default'    => 'center',
							'responsive' => true,
							'help'       => __( 'The overall alignment of Icon', 'xpro-bb-addons' ),
						),
						'social_icon_bg_style' => array(
							'type'    => 'select',
							'label'   => __( 'Background Style', 'xpro-bb-addons' ),
							'default' => 'simple',
							'options' => array(
								'simple' => __( 'Simple', 'xpro-bb-addons' ),
								'circle' => __( 'Circle Background', 'xpro-bb-addons' ),
								'square' => __( 'Square Background', 'xpro-bb-addons' ),
								'custom' => __( 'Design your own', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'circle' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color' ),
								),
								'square' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color' ),
								),
								'custom' => array(
									'fields' => array( 'icon_bg_color', 'icon_bg_hvr_color', 'icon_bg_size', 'icon_border', 'icon_border_hvr_color' ),
								),
							),
						),
						'size'                 => array(
							'type'        => 'unit',
							'label'       => __( 'Icon / Image Size', 'xpro-bb-addons' ),
							'placeholder' => '25',
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
						),
						'icon_bg_size'         => array(
							'type'        => 'unit',
							'label'       => __( 'Icon Background Size', 'xpro-bb-addons' ),
							'units'       => array( 'px' ),
							'placeholder' => '50',
							'slider'      => true,
							'responsive'  => true,
						),
						'spacing'              => array(
							'type'        => 'unit',
							'label'       => __( 'Spacing', 'xpro-bb-addons' ),
							'placeholder' => '10',
							'units'       => array( 'px' ),
							'slider'      => true,
							'responsive'  => true,
							'help'        => __( 'To manage the space between Icons / Images use this option.', 'xpro-bb-addons' ),
						),
						'icon_border'          => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'xpro-bb-addons' ),
							'responsive' => true,
						),
					),
				),
				'colors'    => array(
					'title'  => __( 'Colors', 'xpro-bb-addons' ),
					'fields' => array(
						'icon_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'icon_hvr_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
						),
						'icon_bg_color'         => array(
							'type'       => 'color',
							'label'      => __( 'Icon Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_bg_hvr_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Icon Background Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
						),
						'icon_border_hvr_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
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
	'tnit_social_icon_form',
	array(
		'title' => __( 'Add Social Icon/Image', 'xpro-bb-addons' ),
		'tabs'  => array(
			'form_general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => '',
						'fields' => array(
							'label'      => array(
								'type'    => 'text',
								'label'   => __( 'Label', 'xpro-bb-addons' ),
								'default' => __( 'Facebook', 'xpro-bb-addons' ),
							),
							'image_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Image Type', 'xpro-bb-addons' ),
								'default' => 'icon',
								'options' => array(
									'icon'  => __( 'Icon', 'xpro-bb-addons' ),
									'photo' => __( 'Photo', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'icon'  => array(
										'fields' => array( 'icon' ),
									),
									'photo' => array(
										'fields' => array( 'photo' ),
									),
								),
							),
							'icon'       => array(
								'type'    => 'icon',
								'label'   => __( 'Icon', 'xpro-bb-addons' ),
								'default' => 'fab fa-facebook-f',
							),
							'photo'      => array(
								'type'  => 'photo',
								'label' => __( 'Photo', 'xpro-bb-addons' ),
							),
							'link'       => array(
								'type'          => 'link',
								'label'         => 'Link',
								'show_target'   => true,
								'show_nofollow' => true,
							),
						),
					),
				),
			),
			'form_style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'colors' => array(
						'title'  => __( 'Colors', 'xpro-bb-addons' ),
						'fields' => array(
							'icon_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'icon_hvr_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Icon Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Icon Background Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'bg_hvr_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Icon Background Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'border_color'     => array(
								'type'       => 'color',
								'label'      => __( 'Border Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'border_hvr_color' => array(
								'type'       => 'color',
								'label'      => __( 'Border Hover Color', 'xpro-bb-addons' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
				),
			),
		),
	)
);
