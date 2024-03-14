<?php
/**
 * @class XPROIconBoxModule
 */

class XPRODropCapModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Drop Cap', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
				'group'           => XPRO_Plugins_Helper::$branding_modules,
				'category'        => XPRO_Plugins_Helper::$content_modules,
				'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-drop-cap/',
				'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-drop-cap/',
				'partial_refresh' => true,
			)
		);
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XPRODropCapModule',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'dropcap_description' => array(
							'type'          => 'editor',
							'label'         => __( 'Content', 'xpro-bb-addons' ),
							'media_buttons' => true,
							'rows'          => 6,
							'wpautop'       => false,
							'connections'   => array( 'string' ),
						),
					),
				),
			),
		),
		'style'   => array(
			'title'    => __( 'Style', 'xpro-bb-addons' ),
			'sections' => array(
				'general'  => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'dropcap_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p',
							),
						),
						'dropcap_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p',
								'property' => 'color',
							),
						),
					),
				),
				'drop_cap' => array(
					'title'     => __( 'DropCap', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'dropcap_letter_typography' => array(
							'type'       => 'typography',
							'label'      => 'Typography',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
							),
						),
						'dropcap_letter_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
								'property' => 'color',
							),
						),
						'dropcap_letter_bg_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
								'property' => 'background-color',
							),
						),
						'dropcap_letter_border'     => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
							),
						),
						'dropcap_letter_padding'    => array(
							'type'         => 'dimension',
							'label'        => 'Padding',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
								'property' => 'padding',
							),
						),
						'dropcap_letter_margin'     => array(
							'type'         => 'dimension',
							'label'        => 'Margin',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'preview'      => array(
								'type'     => 'css',
								'selector' => '.xpro-dropcap-wrapper > p:first-of-type::first-letter',
								'property' => 'margin',
							),
						),
					),
				),
			),
		),
	)
);
