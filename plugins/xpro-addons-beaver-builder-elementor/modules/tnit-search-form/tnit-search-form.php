<?php

/**
 * @class TNITSearchModule
 */

if ( ! class_exists( 'TNITSearchModule' ) ) {

	class TNITSearchModule extends FLBuilderModule {

		/**
		 * @method __construct
		 */
		public function __construct() {
			parent::__construct(
				array(
					'name'            => __( 'Search Form', 'xpro-bb-addons' ),
					'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
					'group'           => XPRO_Plugins_Helper::$branding_modules,
					'category'        => XPRO_Plugins_Helper::$creative_modules,
					'dir'             => XPRO_ADDONS_FOR_BB_DIR . 'modules/tnit-search-form/',
					'url'             => XPRO_ADDONS_FOR_BB_URL . 'modules/tnit-search-form/',
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
	}

	/**
	 * Register the module and its form settings.
	 */
	FLBuilder::register_module(
		'TNITSearchModule',
		array(
			'general'    => array(
				'title'    => __( 'General', 'tnit' ),
				'sections' => array(
					'form_structure' => array(
						'title'  => 'Form',
						'fields' => array(
							'search_layout' => array(
								'type'    => 'select',
								'label'   => __( 'Search Form Layout', 'tnit' ),
								'default' => 'style-1',
								'options' => array(
									'style-1' => __( 'Style 1', 'tnit' ),
									'style-2' => __( 'Style 2', 'tnit' ),
									'style-3' => __( 'Style 3', 'tnit' ),
								),
								'toggle'  => array(
									'style-1' => array(
										'fields' => array( 'input_bg_focus_color', 'input_text_focus_color', 'input_border' ),
									),
									'style-2' => array(
										'fields' => array( 'input_text_focus_color' ),
									),
									'style-3' => array(
										'fields'   => array( 'input_bg_focus_color', 'input_text_focus_color', 'input_border' ),
										'sections' => array( 'toggle_btn', 'toggle_button_style' ),
									),
								),
							),
							'placeholder'   => array(
								'type'    => 'text',
								'label'   => __( 'Placeholder', 'tnit' ),
								'default' => __( 'Search...', 'tnit' ),
								'class'   => '.tnit-form-search .input-field',
							),
							'form_height'   => array(
								'type'    => 'unit',
								'label'   => __( 'Form Height', 'tnit' ),
								'default' => '60',
								'units'   => array( 'px' ),
								'slider'  => true,

							),
						),
					),
					'toggle_btn'     => array(
						'title'  => __( 'Toggle Button', 'tnit' ),
						'fields' => array(
							'toggle_btn_icon'  => array(
								'type'        => 'icon',
								'label'       => __( 'Toggle Icons', 'tnit' ),
								'default'     => 'fas fa-search',
								'show_remove' => true,
							),
							'toggle_icon_size' => array(
								'type'         => 'unit',
								'label'        => __( 'Toggle Icon Size', 'tnit' ),
								'placeholder'  => '14',
								'default_unit' => 'px',
								'units'        => array( 'px' ),
								'slider'       => true,
							),
							'button_size'      => array(
								'type'       => 'unit',
								'label'      => __( 'Box Size', 'tnit' ),
								'default'    => '40',
								'maxlength'  => '3',
								'size'       => '4',
								'units'      => array( 'px' ),
								'slider'     => true,
								'responsive' => true,
							),
						),
					),

				),
			),
			'style'      => array(
				'title'    => __( 'Input', 'tnit' ),
				'sections' => array(
					'input_styles' => array(
						'title'  => __( 'Input Styles', 'tnit' ),
						'fields' => array(
							'input_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'tnit' ),
								'default'    => 'f8f8f8',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'input_bg_focus_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Background Focus Color', 'tnit' ),
								'default'    => 'cccccc',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'ph_color'               => array(
								'type'       => 'color',
								'label'      => __( 'Placeholder Color', 'tnit' ),
								'default'    => '673ab7',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'input_text_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Text Color', 'tnit' ),
								'default'    => '',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'input_text_focus_color' => array(
								'type'       => 'color',
								'label'      => __( 'Text Focus Color', 'tnit' ),
								'default'    => '000000',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'input_border'           => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.tnit-outer-border',
								),
							),
						),
					),
				),
			),
			'button'     => array(
				'title'    => __( 'Button', 'tnit' ),
				'sections' => array(
					'search_button'        => array(
						'title'  => __( 'Button Basics', 'tnit' ),
						'fields' => array(
							'button_type' => array(
								'type'    => 'button-group',
								'label'   => __( 'Button Type', 'tnit' ),
								'default' => 'icon',
								'options' => array(
									'icon' => __( 'Icon', 'tnit' ),
									'text' => __( 'Text', 'tnit' ),
								),
								'toggle'  => array(
									'icon' => array(
										'fields' => array( 'btn_icon', 'icon_size' ),
									),
									'text' => array(
										'fields'   => array( 'btn_text' ),
										'sections' => array( 'button_type' ),
									),
								),
							),
							'btn_icon'    => array(
								'type'        => 'icon',
								'label'       => __( 'Button Icon', 'tnit' ),
								'default'     => 'fas fa-search',
								'show_remove' => true,
							),
							'icon_size'   => array(
								'type'         => 'unit',
								'label'        => __( 'Icon Size', 'tnit' ),
								'placeholder'  => '14',
								'default_unit' => 'px',
								'units'        => array( 'px' ),
								'slider'       => true,
							),
							'btn_text'    => array(
								'type'    => 'text',
								'label'   => __( 'Button Text', 'tnit' ),
								'default' => __( 'CLICK ON!', 'tnit' ),
								'class'   => '.btn-submit',
							),
						),
					),
					'search_button_colors' => array(
						'title'  => __( 'Button Colors', 'tnit' ),
						'fields' => array(
							'btn_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'tnit' ),
								'default'    => '673ab7',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'btn_bg_hover_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'tnit' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'btn_icon_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'tnit' ),
								'show_reset' => true,
							),
							'btn_icon_hover_color' => array(
								'type'       => 'color',
								'label'      => __( 'Icon Hover Color', 'tnit' ),
								'show_reset' => true,
							),
						),
					),
					'toggle_button_style'  => array(
						'title'  => __( 'Toggle Button Style', 'tnit' ),
						'fields' => array(
							'tog_btn_bg_color'         => array(
								'type'       => 'color',
								'label'      => __( 'Background Color', 'tnit' ),
								'default'    => '673ab7',
								'show_reset' => true,
								'show_alpha' => true,
							),
							'tog_btn_bg_hover_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Background Hover Color', 'tnit' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'tog_btn_icon_color'       => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'tnit' ),
								'show_reset' => true,
							),
							'tog_btn_icon_hover_color' => array(
								'type'       => 'color',
								'label'      => __( 'Icon Hover Color', 'tnit' ),
								'show_reset' => true,
							),
							'tog_btn_border'           => array(
								'type'       => 'border',
								'label'      => 'Border',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '#trigger-tnit-search',
								),
							),
						),
					),
				),
			),
			'typography' => array(
				'title'    => __( 'Typography', 'tnit' ),
				'sections' => array(
					'Input_type'  => array(
						'title'  => 'Input',
						'fields' => array(
							'input_typography' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.tnit-form-search .input-field',
								),
							),
						),
					),
					'button_type' => array(
						'title'  => 'Button',
						'fields' => array(
							'button_typo' => array(
								'type'       => 'typography',
								'label'      => 'Typography',
								'responsive' => true,
								'preview'    => array(
									'type'     => 'css',
									'selector' => '.tnit-form-search .btn-submit, .tnit-search-box #tnit-trigger-btn, .tnit-search-animated-form .input-field',
								),
							),
						),
					),
				),
			),
		)
	);

}
