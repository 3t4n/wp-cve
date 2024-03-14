<?php
/**
 * @class TNITSpacerModule
 */

class TNITSpacer extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Spacer', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$creative_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-spacer/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-spacer/',
				'partial_refresh' => true,
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'TNITSpacer',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'space' => array(
							'type'         => 'unit',
							'label'        => __( 'Space', 'xpro-bb-addons' ),
							'placeholder'  => '30',
							'default_unit' => 'px',
							'units'        => array( 'px' ),
							'slider'       => true,
							'responsive'   => true,
							'preview'      => array(
								'type'      => 'css',
								'selector'  => '.tnit-content.tnit-spacer',
								'property'  => 'height',
								'unit'      => 'px',
								'important' => true,
							),
						),
					),
				),
			),
		),
	)
);
