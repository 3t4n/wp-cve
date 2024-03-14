<?php
/**
 * @class XPROIconBoxModule
 */

class XPROLogoGridModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Logo Grid', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$media_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-logo-grid/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-logo-grid/',
				'partial_refresh' => true,
			)
		);
	}

	public function render_image( $item ) {
		$settings  = $item;
		$output    = '';
		$alt       = '';
		$image_src = '';

		$photo = FLBuilderPhoto::get_attachment_data( $settings->general_image );

		if ( ! empty( $photo->alt ) ) {
			$alt = htmlspecialchars( $photo->alt );
		} elseif ( ! empty( $photo->description ) ) {
			$alt = htmlspecialchars( $photo->description );
		} elseif ( ! empty( $photo->caption ) ) {
			$alt = htmlspecialchars( $photo->caption );
		} elseif ( ! empty( $photo->title ) ) {
			$alt = htmlspecialchars( $photo->title );
		}

		// get image from media library.
		if ( 'library' === $settings->general_image_source && ! empty( $settings->general_image_src ) ) {
			$image_src = $settings->general_image_src;
		}

		// get image external URL.
		elseif ( 'url' === $settings->general_image_source && ! empty( $settings->general_image_url ) ) {
			$image_src = $settings->general_image_url;
		} else {
			$image_src = XPRO_ADDONS_FOR_BB_URL . 'assets/images/placeholder-sm.webp';
		}

		$output .= '<img src="' . esc_url( $image_src ) . '" alt="' . $alt . '">';

		echo $output;
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XPROLogoGridModule',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'grid_columns' => array(
							'type'       => 'select',
							'label'      => __( 'Grid Columns', 'xpro-bb-addons' ),
							'responsive' => array(
								'default' => array(
									'default'    => '3',
									'medium'     => '2',
									'responsive' => '2',
								),
							),
							'options'    => array(
								'2' => __( '2', 'xpro-bb-addons' ),
								'3' => __( '3', 'xpro-bb-addons' ),
								'4' => __( '4', 'xpro-bb-addons' ),
								'5' => __( '5', 'xpro-bb-addons' ),
								'6' => __( '6', 'xpro-bb-addons' ),
							),
						),
					),
				),
				'item'    => array(
					'title'  => __( 'Item', 'xpro-bb-addons' ),
					'fields' => array(
						'logo_grid_form_field' => array(
							'type'     => 'form',
							'label'    => __( 'Logo Grid List', 'xpro-bb-addons' ),
							'multiple' => true,
							'form'     => 'logo_grid_form',
							'default'  => array(
								array(
									'general_image_source' => 'library',
								),
								array(
									'general_image_source' => 'library',
								),
								array(
									'general_image_source' => 'library',
								),
								array(
									'general_image_source' => 'library',
								),
								array(
									'general_image_source' => 'library',
								),
								array(
									'general_image_source' => 'library',
								),
							),
						),
					),
				),
			),
		),
		'style'   => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'grid_height'  => array(
							'type'         => 'unit',
							'label'        => __( 'Height', 'xpro-bb-addons' ),
							'units'        => array( 'px' ),
							'responsive'   => true,
							'default'      => 200,
							'slider'       => true,
							'default_unit' => 'px',
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-logo-grid-item',
								'property' => 'height',
							),
						),
						'grid_gb'      => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-logo-grid-figure',
								'property' => 'background-color',
							),
						),
						'grid_border'  => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-logo-grid-item',
							),
						),
						'grid_padding' => array(
							'type'         => 'dimension',
							'label'        => __( 'Padding', 'xpro-bb-addons' ),
							'units'        => array( 'px' ),
							'responsive'   => true,
							'slider'       => true,
							'default_unit' => 'px',
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-logo-grid-figure',
								'property' => 'padding',
							),
						),
					),
				),
			),
		),

	)
);

// Log.
FLBuilder::register_settings_form(
	'logo_grid_form',
	array(
		'title' => __( 'My Form Field', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => 'Logo List',
						'fields' => array(
							'general_image_source' => array(
								'type'    => 'button-group',
								'label'   => __( 'Photo Source', 'xpro-bb-addons' ),
								'default' => 'library',
								'options' => array(
									'library' => __( 'Media Library', 'xpro-bb-addons' ),
									'url'     => __( 'URL', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'library' => array(
										'fields' => array( 'general_image' ),
									),
									'url'     => array(
										'fields' => array( 'general_image_url' ),
									),
								),
							),
							'general_image'        => array(
								'type'        => 'photo',
								'label'       => __( 'Image', 'xpro-bb-addons' ),
								'default'     => '',
								'show_remove' => true,
								'connections' => array( 'general_image' ),
							),
							'general_image_url'    => array(
								'type'        => 'text',
								'label'       => __( 'Photo URL', 'xpro-bb-addons' ),
								'placeholder' => 'https://www.example.com',
							),
							'general_image_link'   => array(
								'type'          => 'link',
								'label'         => __( 'Link', 'xpro-bb-addons' ),
								'placeholder'   => 'https://www.example.com',
								'show_target'   => true,
								'show_nofollow' => true,
							),
						),
					),
				),
			),
		),
	)
);
