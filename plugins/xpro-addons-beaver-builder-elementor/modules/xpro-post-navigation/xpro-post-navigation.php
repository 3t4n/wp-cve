<?php

/**
 * @class XPROIconBoxModule
 */

if ( ! class_exists( 'XPROPostNavigationModule' ) ) {

	class XPROPostNavigationModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Post Navigation', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$themer_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/xpro-post-navigation/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-post-navigation/',
					'partial_refresh' => true,
				)
			);
		}

	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'XPROPostNavigationModule',
		array(
			'general' => array(
				'title'    => __( 'General', 'xpro-bb-addons' ),
				'sections' => array(
					'post' => array(
						'title'  => __( 'Post Navigation', 'xpro-bb-addons' ),
						'fields' => array(
							'show_label'     => array(
								'type'    => 'button-group',
								'label'   => __( 'Enable Label', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
								'toggle'  => array(
									'yes' => array(
										'fields' => array( 'prev_label', 'next_label' ),
									),
								),
							),
							'prev_label'     => array(
								'type'        => 'text',
								'label'       => __( 'Prev Label', 'xpro-bb-addons' ),
								'default'     => 'Prev Label',
								'placeholder' => __( '', 'xpro-bb-addons' ),
							),
							'next_label'     => array(
								'type'        => 'text',
								'label'       => __( 'Next Label', 'xpro-bb-addons' ),
								'default'     => 'Next Label',
								'placeholder' => __( '', 'xpro-bb-addons' ),
							),
							'show_arrow'     => array(
								'type'    => 'select',
								'label'   => __( 'Arrows Type', 'xpro-bb-addons' ),
								'default' => 'fas fa-arrow-left',
								'options' => array(
									'none'                => __( 'Show', 'xpro-bb-addons' ),
									'fas fa-arrow-left'   => __( 'Arrow', 'xpro-bb-addons' ),
									'fas fa-arrow-circle-left' => __( 'Arrow Circle', 'xpro-bb-addons' ),
									'fas fa-angle-left'   => __( 'Angle', 'xpro-bb-addons' ),
									'fas fa-angle-double-left' => __( 'Angle Circle', 'xpro-bb-addons' ),
									'fas fa-chevron-left' => __( 'Chevron', 'xpro-bb-addons' ),
									'fas fa-chevron-circle-left' => __( 'Chevron Circle', 'xpro-bb-addons' ),
									'fas fa-caret-left'   => __( 'Caret', 'xpro-bb-addons' ),
									'xi xi-long-arrow-left' => __( 'Long Arrow', 'xpro-bb-addons' ),
								),
							),
							'show_title'     => array(
								'type'    => 'button-group',
								'label'   => __( 'Enable Post Title', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
							),
							'show_separator' => array(
								'type'    => 'button-group',
								'label'   => __( 'Enable Separator', 'xpro-bb-addons' ),
								'default' => 'yes',
								'options' => array(
									'yes' => __( 'Show', 'xpro-bb-addons' ),
									'no'  => __( 'Hide', 'xpro-bb-addons' ),
								),
							),
						),
					),
				),
			),
			'style'   => array(
				'title'    => __( 'Style', 'xpro-bb-addons' ),
				'sections' => array(
					'label'     => array(
						'title'  => __( 'Label', 'xpro-bb-addons' ),
						'fields' => array(
							'label_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => 'span.xpro-post-navigation-prev-label, span.xpro-post-navigation-next-label',
								),
							),
							'label_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-prev-label, .xpro-post-navigation-next-label',
									'property' => 'color',
								),
							),
							'label_hv_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Color Hover', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-link > a:hover .xpro-post-navigation-prev-label, .xpro-post-navigation-link > a:hover .xpro-post-navigation-next-label',
									'property' => 'color',
								),
							),
						),
					),
					'title'     => array(
						'title'     => __( 'Title', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'title_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => 'span.xpro-post-navigation-prev-title, span.xpro-post-navigation-next-title',
								),
							),
							'title_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => 'span.xpro-post-navigation-prev-title, span.xpro-post-navigation-next-title',
									'property' => 'color',
								),
							),
							'title_hv_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Color Hover', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-link > a:hover .xpro-post-navigation-prev-title, .xpro-post-navigation-link > a:hover .xpro-post-navigation-next-title',
									'property' => 'color',
								),
							),
						),
					),
					'arrow'     => array(
						'title'     => __( 'Arrow', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'arrow_color'    => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i, .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i',
									'property' => 'color',
								),
							),
							'arrow_hv_color' => array(
								'type'       => 'color',
								'label'      => __( 'Color Hover', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-link > a:hover .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i, .xpro-post-navigation-link > a:hover .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i',
									'property' => 'color',
								),
							),
							'arrow_size'     => array(
								'type'         => 'unit',
								'label'        => 'Size',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'responsive'   => true,
								'slider'       => true,
								'preview'      => array(
									'type'     => 'css',
									'selector' => '.xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i, .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i',
									'property' => 'font-size',
								),
							),
							'arrow_gap'      => array(
								'type'         => 'unit',
								'label'        => 'Gap',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'slider'       => true,
								'responsive'   => true,
								'preview'      => array(
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-post-navigation-arrow-next',
											'property' => 'padding-right',
										),
										array(
											'selector' => '.xpro-post-navigation-arrow-prev',
											'property' => 'padding-left',
										),
									),
								),
							),
						),
					),
					'separator' => array(
						'title'     => __( 'Separator', 'xpro-bb-addons' ),
						'collapsed' => true,
						'fields'    => array(
							'separator_color' => array(
								'type'       => 'color',
								'label'      => __( 'Color', 'xpro-bb-addons' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
								'preview'    => array(
									'type'  => 'css',
									'rules' => array(
										array(
											'selector' => '.xpro-post-navigation-separator',
											'property' => 'background-color',
										),
										array(
											'selector' => '.xpro-post-navigation-navigation',
											'property' => 'color',
										),
									),
								),
							),
							'separator_size'  => array(
								'type'         => 'unit',
								'label'        => 'Size',
								'units'        => array( 'px' ),
								'default_unit' => 'px',
								'slider'       => true,
							),
						),
					),
				),
			),
		)
	);
}
