<?php
/**
 * @class XproImageMasking
 */

if ( ! class_exists( 'XproFeaturedImageModule' ) ) {

	class XproFeaturedImageModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Featured Image', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$themer_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-featured-image/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-featured-image/',
					'partial_refresh' => true,
				)
			);

		}

	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XproFeaturedImageModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'General', 'xpro-bb-addons' ),
						'fields' => array(
							'featured_image_thumbnail' => array(
								'type'    => 'photo-sizes',
								'label'   => __( 'Image Size', 'fl-builder' ),
								'default' => 'medium',
							),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'general' => array(
						'title'  => __( 'Image', 'xpro-bb-addons' ),
						'fields' => array(
							'featured_alignment'     => array(
								'type'       => 'align',
								'label'      => __( 'Alignment', 'xpro-bb-addons' ),
								'default'    => 'left',
								'responsive' => true,
							),
							'featured_width'         => array(
								'type'         => 'unit',
								'label'        => __( 'Width', 'xpro-bb-addons' ),
								'units'        => array( 'px', '%' ),
								'responsive'   => true,
								'slider'       => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-featured-image > img',
									'property' => 'width',
								),
							),
							'featured_max_width'     => array(
								'type'         => 'unit',
								'label'        => __( 'Max-Width', 'xpro-bb-addons' ),
								'units'        => array( 'px', '%' ),
								'slider'       => true,
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-featured-image > img',
									'property' => 'max-width',
								),
							),
							'featured_height'        => array(
								'type'         => 'unit',
								'label'        => __( 'Height', 'xpro-bb-addons' ),
								'units'        => array( 'px', '%' ),
								'slider'       => true,
								'responsive'   => true,
								'default_unit' => 'px',
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-featured-image > img',
									'property' => 'height',
								),
							),
							'featured_image_opacity' => array(
								'type'    => 'unit',
								'label'   => __( 'Opacity', 'xpro-bb-addons' ),
								'slider'  => array(
									'min'  => 0,
									'max'  => 1,
									'step' => 0.1,
								),
								'preview' => array(
									'type'     => 'css',
									'selector' => '.xpro-featured-image > img',
									'property' => 'opacity',
								),
							),
							'featured_image_border'  => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-featured-image > img',
								),
							),
						),
					),
				),
			),
		)
	);
}
