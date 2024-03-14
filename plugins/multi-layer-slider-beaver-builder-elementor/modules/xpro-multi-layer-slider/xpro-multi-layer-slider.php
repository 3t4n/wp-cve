<?php

/**
 * @class XPRODynamicSlider
 */
class XPRODynamicSlider extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Xpro Multilayer Slider', 'xpro-bb-addons' ),
				'description'     => __( 'An awesome addition by Xpro team!', 'xpro-bb-addons' ),
                'group'           => XPRO_Plugins_Helper::$branding_modules,
                'category'        => XPRO_Plugins_Helper::$advance_module,
				'dir'             => XPRO_SLIDER_FOR_BB_LITE_DIR . 'modules/xpro-multi-layer-slider/',
				'url'             => XPRO_SLIDER_FOR_BB_LITE_URL . 'modules/xpro-multi-layer-slider/',
				'partial_refresh' => true,
			)
		);
	}

	/**
	 * @method enqueue_scripts
	 */
	public function enqueue_scripts() {

        // Already registered
        $this->add_css('font-awesome');
        $this->add_css('font-awesome-5');
		// Register and enqueue your own
		$this->add_css( 'slick', XPRO_SLIDER_FOR_BB_LITE_URL . 'assets/css/slick.min.css', '', '1.8.0' );
		$this->add_js( 'slick', XPRO_SLIDER_FOR_BB_LITE_URL . 'assets/js/slick.min.js', array( 'jquery' ), '1.8.0', true );
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'XPRODynamicSlider',
	array(
		'general' => array(
			'title'    => __( 'General', 'xpro-bb-addons' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'General', 'xpro-bb-addons' ),
					'fields' => array(
						'slides_items' => array(
							'type'         => 'form',
							'label'        => __( 'Slide', 'xpro-bb-addons' ),
							'multiple'     => true,
							'preview_text' => 'title',
							'form'         => 'slides_items',
							'default'      => array(
								array(
									'title' => 'Slide 1',
								),
								array(
									'title' => 'Slide 2',
								),
								array(
									'title' => 'Slide 3',
								),
							),
						),
					),
				),
				'setting' => array(
					'title'     => __( 'Settings', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
						'slider_loop'             => array(
							'type'    => 'button-group',
							'label'   => __( 'Loop', 'xpro-bb-addons' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
						),
						'slider_mousedrag'        => array(
							'type'    => 'button-group',
							'label'   => __( 'Mouse Drag', 'xpro-bb-addons' ),
							'default' => 'true',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
						),
						'slider_mousewheel'       => array(
							'type'    => 'button-group',
							'label'   => __( 'Mouse Wheel', 'xpro-bb-addons' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
						),
						'slider_autoplay'         => array(
							'type'    => 'button-group',
							'label'   => __( 'Autoplay', 'xpro-bb-addons' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'true' => array(
									'fields' => array( 'slider_autoplay_timeout' ),
								),
							),
						),
						'slider_autoplay_timeout' => array(
							'type'         => 'unit',
							'label'        => __( 'Autoplay Timeout', 'xpro-bb-addons' ),
							'units'        => array( 'S' ),
							'default'      => 3,
							'slider'       => array(
								'S' => array(
									'min'  => 1,
									'max'  => 10,
									'step' => 1,
								),
							),
						),
						'slider_nav'              => array(
							'type'    => 'button-group',
							'label'   => __( 'Enable Nav', 'xpro-bb-addons' ),
							'default' => 'true',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'true' => array(
									'sections' => array( 'navigation' ),
								),
							),
						),
						'slider_dots'             => array(
							'type'    => 'button-group',
							'label'   => __( 'Enable Dots', 'xpro-bb-addons' ),
							'default' => 'false',
							'options' => array(
								'true'  => __( 'On', 'xpro-bb-addons' ),
								'false' => __( 'Off', 'xpro-bb-addons' ),
							),
							'toggle'  => array(
								'true' => array(
									'sections' => array( 'dots' ),
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
				'slider_styles' => array(
					'title'  => __( 'Slider Styles', 'xpro-bb-addons' ),
					'fields' => array(
						'slider_orientation' => array(
							'type'    => 'button-group',
							'label'   => __( 'Orientation', 'xpro-bb-addons' ),
							'default' => 'horizontal',
							'options' => array(
								'horizontal' => __( 'Horizontal', 'xpro-bb-addons' ),
								'vertical'   => __( 'Vertical', 'xpro-bb-addons' ),
							),
                            'toggle'  => array(
                                'vertical' => array(
                                    'fields' => array( 'slider_height' ),
                                ),
                            ),
						),
                        'slider_height'                => array(
                            'type'         => 'unit',
                            'label'        => 'Height',
                            'units'        => array( 'px', '%', 'vh' ),
                            'default_unit' => 'px',
                            'default'      => 400,
                            'responsive'   => true,
                            'slider'       => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 5,
                                ),
                            ),
                        ),
						'slide_animation'    => array(
							'type'    => 'button-group',
							'label'   => __( 'Animation', 'xpro-bb-addons' ),
							'default' => 'slide',
							'options' => array(
								'fade'  => __( 'Fade', 'xpro-bb-addons' ),
								'slide' => __( 'Slide', 'xpro-bb-addons' ),
							),
						),
						'slide_duration'     => array(
							'type'    => 'unit',
							'label'   => 'Slide Duration(ms)',
							'default' => 400,
                            'slider'       => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 100,
                                ),
                            ),
						),
					),
				),
				'navigation'    => array(
					'title'     => __( 'Navigation', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
                        'nav_layout'         => array(
                            'type'    => 'button-group',
                            'label'   => __( 'Layout', 'xpro-bb-addons' ),
                            'default' => 'classic',
                            'options' => array(
                                'classic' => __( 'Classic', 'xpro-bb-addons' ),
                                'modern'  => __( 'Modern', 'xpro-bb-addons' ),
                            ),
                        ),
                        'nav_orientation'    => array(
                            'type'    => 'button-group',
                            'label'   => __( 'Orientation', 'xpro-bb-addons' ),
                            'default' => 'horizontal',
                            'options' => array(
                                'horizontal' => __( 'Horizontal', 'xpro-bb-addons' ),
                                'vertical'   => __( 'Vertical', 'xpro-bb-addons' ),
                            ),
                        ),
                        'nav_position'       => array(
                            'type'    => 'select',
                            'label'   => __( 'Position', 'xpro-bb-addons' ),
                            'default' => 'default',
                            'options' => array(
                                'default'       => __( 'Default', 'xpro-bb-addons' ),
                                'top-left'      => __( 'Top Left', 'xpro-bb-addons' ),
                                'top-center'    => __( 'Top Center', 'xpro-bb-addons' ),
                                'top-right'     => __( 'Top Right', 'xpro-bb-addons' ),
                                'middle-left'   => __( 'Middle Left', 'xpro-bb-addons' ),
                                'middle-center' => __( 'Middle Center', 'xpro-bb-addons' ),
                                'middle-right'  => __( 'Middle Right', 'xpro-bb-addons' ),
                                'bottom-left'   => __( 'Bottom Left', 'xpro-bb-addons' ),
                                'bottom-center' => __( 'Bottom Center', 'xpro-bb-addons' ),
                                'bottom-right'  => __( 'Bottom Right', 'xpro-bb-addons' ),
                            ),
                            'toggle'  => array(
                                'default' => array(
                                    'fields' => array( 'nav_offset' ),
                                ),
                                'top-left' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'top-center' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'top-right' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'middle-left' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'middle-center' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'middle-right' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'bottom-left' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'bottom-center' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                                'bottom-right' => array(
                                    'fields' => array( 'nav_space_between', 'nav_wrap_margin' ),
                                ),
                            ),
                        ),
						'nav_icon_size'      => array(
							'type'         => 'unit',
							'label'        => 'Icon Size',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'default'      => 25,
							'slider'       => true,
							'preview'      => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
								'property' => 'font-size',
							),
						),
						'nav_bg_size'        => array(
							'type'         => 'unit',
							'label'        => 'Bg Size',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'responsive'   => true,
							'default'      => 50,
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
										'property' => 'width',
									),
									array(
										'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
										'property' => 'height',
									),
									array(
										'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
										'property' => 'line-height',
									),
								),
							),
						),
						'nav_offset'         => array(
							'type'         => 'unit',
							'label'        => 'Distance',
							'units'        => array( 'px' ),
							'responsive'   => true,
							'default_unit' => 'px',
							'default'      => -25,
							'slider'       => array(
								'px' => array(
									'min'  => -100,
									'max'  => 100,
									'step' => 1,
								),
							),
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} [class*=xpro-dynamic-slider-navigation-horizontal].xpro-dynamic-slider-navigation-position-default .slick-nav-prev',
										'property' => 'left',
									),
									array(
										'selector' => '{node} [class*=xpro-dynamic-slider-navigation-horizontal].xpro-dynamic-slider-navigation-position-default .slick-nav-next',
										'property' => 'right',
									),
                                    array(
                                        'selector' => '{node} [class*=xpro-dynamic-slider-navigation-vertical].xpro-dynamic-slider-navigation-position-default .slick-nav-prev',
                                        'property' => 'top',
                                    ),
                                    array(
                                        'selector' => '{node} [class*=xpro-dynamic-slider-navigation-vertical].xpro-dynamic-slider-navigation-position-default .slick-nav-next',
                                        'property' => 'bottom',
                                    ),
								),
							),
						),
                        'nav_space_between'         => array(
                            'type'         => 'unit',
                            'label'        => 'Space Between',
                            'units'        => array( 'px' ),
                            'responsive'   => true,
                            'default_unit' => 'px',
                            'default'      => 15,
                            'slider'       => array(
                                'px' => array(
                                    'min'  => 0,
                                    'max'  => 100,
                                    'step' => 1,
                                ),
                            ),
                            'preview'      => array(
                                'type'  => 'css',
                                'rules' => array(
                                    array(
                                        'selector' => '{node} .xpro-dynamic-slider-navigation',
                                        'property' => 'grid-gap',
                                    ),
                                ),
                            ),
                        ),
						'nav_bg_type'        => array(
							'type'    => 'button-group',
							'label'   => 'Background Type',
							'default' => 'normal',
							'options' => array(
								'normal' => 'Normal',
								'hover'  => 'Hover',
							),
							'toggle'  => array(
								'normal' => array(
									'fields' => array( 'nav_color', 'nav_bg_color', 'nav_border' ),
								),
								'hover'  => array(
									'fields' => array( 'nav_h_color', 'nav_h_bg_color', 'nav_h_border_color' ),
								),
							),
						),
						'nav_color'          => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
								'property' => 'color',
							),
						),
						'nav_bg_color'       => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
								'property' => 'background-color',
							),
						),
						'nav_h_color'        => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev:hover,{node} .slick-nav-next:hover',
								'property' => 'color',
							),
						),
						'nav_h_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev:hover,{node} .slick-nav-next:hover',
								'property' => 'background-color',
							),
						),
						'nav_h_border_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev:hover,{node} .slick-nav-next:hover',
								'property' => 'border-color',
							),
						),
						'nav_border'         => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-nav-prev,{node} .slick-nav-next',
							),
						),
                        'nav_margin'                 => array(
                            'type'       => 'dimension',
                            'label'      => 'Margins',
                            'units'      => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '{node} .xpro-dynamic-slider-navigation',
                                'property' => 'margin',
                            ),
                        ),
					),
				),
				'dots'          => array(
					'title'     => __( 'Dots', 'xpro-bb-addons' ),
					'collapsed' => true,
					'fields'    => array(
                        'dots_layout'              => array(
                            'type'    => 'button-group',
                            'label'   => __( 'Layout', 'xpro-bb-addons' ),
                            'default' => 'classic',
                            'options' => array(
                                'classic' => __( 'Classic', 'xpro-bb-addons' ),
                                'modern'  => __( 'Modern', 'xpro-bb-addons' ),
                            ),
                        ),
                        'dots_orientation'         => array(
                            'type'    => 'button-group',
                            'label'   => __( 'Orientation', 'xpro-bb-addons' ),
                            'default' => 'horizontal',
                            'options' => array(
                                'horizontal' => __( 'Horizontal', 'xpro-bb-addons' ),
                                'vertical'   => __( 'Vertical', 'xpro-bb-addons' ),
                            ),
                        ),
                        'dots_position'            => array(
                            'type'    => 'select',
                            'label'   => __( 'Position', 'xpro-bb-addons' ),
                            'default' => 'bottom-center',
                            'options' => array(
                                'top-left'      => __( 'Top Left', 'xpro-bb-addons' ),
                                'top-center'    => __( 'Top Center', 'xpro-bb-addons' ),
                                'top-right'     => __( 'Top Right', 'xpro-bb-addons' ),
                                'middle-left'   => __( 'Middle Left', 'xpro-bb-addons' ),
                                'middle-center' => __( 'Middle Center', 'xpro-bb-addons' ),
                                'middle-right'  => __( 'Middle Right', 'xpro-bb-addons' ),
                                'bottom-left'   => __( 'Bottom Left', 'xpro-bb-addons' ),
                                'bottom-center' => __( 'Bottom Center', 'xpro-bb-addons' ),
                                'bottom-right'  => __( 'Bottom Right', 'xpro-bb-addons' ),
                            ),
                        ),
						'dots_width'                => array(
							'type'         => 'unit',
							'label'        => 'Width',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'default'      => 12,
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-dynamic-slider .slick-dots > li > .slick-dot',
										'property' => '--xpro-dynamic-slider-dot-width',
									),
								),
							),
						),
						'dots_height'         => array(
							'type'         => 'unit',
							'label'        => 'Height',
							'units'        => array( 'px' ),
							'default_unit' => 'px',
							'default'      => 12,
							'responsive'   => true,
							'slider'       => true,
							'preview'      => array(
								'type'  => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .xpro-dynamic-slider .slick-dots > li > .slick-dot',
										'property' => 'height',
									),
								),
							),
						),
                        'dots_space_between'         => array(
                            'type'         => 'unit',
                            'label'        => 'Space Between',
                            'units'        => array( 'px' ),
                            'responsive'   => true,
                            'default_unit' => 'px',
                            'default'      => 5,
                            'slider'       => true,
                            'preview'      => array(
                                'type'  => 'css',
                                'rules' => array(
                                    array(
                                        'selector' => '{node} .xpro-dynamic-slider .slick-dots > li',
                                        'property' => 'margin-right',
                                    ),

                                ),
                            ),
                        ),
						'dots_bg_type'             => array(
							'type'    => 'button-group',
							'label'   => 'Background Type',
							'default' => 'normal',
							'options' => array(
								'normal' => 'Normal',
								'active' => 'Active',
							),
							'toggle'  => array(
								'normal' => array(
									'fields' => array( 'dots_bg_color', 'dots_border' ),
								),
								'active' => array(
									'fields' => array( 'dots_active_bg_color', 'dots_active_border_color' ),
								),
							),
						),
						'dots_bg_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-dots > li > .slick-dot',
								'property' => 'background-color',
							),
						),
						'dots_active_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-dots > li.slick-active > .slick-dot',
								'property' => 'background-color',
							),
						),
						'dots_active_border_color' => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'xpro-bb-addons' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-dots > li.slick-active > .slick-dot',
								'property' => 'border-color',
							),
						),
						'dots_border'              => array(
							'type'       => 'border',
							'label'      => 'Border',
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .slick-dots > li > .slick-dot',
							),
						),
                        'dots_margin'                 => array(
                            'type'       => 'dimension',
                            'label'      => 'Margins',
                            'units'      => array( 'px', '%' ),
                            'responsive' => true,
                            'preview'    => array(
                                'type'     => 'css',
                                'selector' => '{node} .xpro-dynamic-slider .slick-dots',
                                'property' => 'margin',
                            ),
                        ),
					),
				),
			),
		),
	)
);


/**
 * Register a settings form for Content Styles.
 */
FLBuilder::register_settings_form(
	'slides_items',
	array(
		'title' => __( 'Slide', 'xpro-bb-addons' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'Slide', 'xpro-bb-addons' ),
				'sections' => array(
                    'content' => array(
                        'title'  => __( 'Content', 'xpro-bb-addons' ),
                        'fields' => array(
                            'template_type'        => array(
                                'type'    => 'button-group',
                                'label'   => __( 'Select Type', 'xpro-bb-addons' ),
                                'default' => 'templates',
                                'options' => array(
                                    'templates' => __( 'Saved Templates', 'xpro-bb-addons' ),
                                    'rows' => __( 'Saved Rows', 'xpro-bb-addons' ),
                                ),
                                'toggle'  => array(
                                    'rows' => array(
                                        'fields' => array( 'content_bb_row', 'create_bb_row', 'edit_bb_row' ),
                                    ),
                                    'templates' => array(
                                        'fields' => array( 'content_row', 'edit_template', 'create_template' ),
                                    ),
                                ),
                            ),
                            'content_row'    => array(
                                'type'    => 'select',
                                'label'   => __( 'Slider Templates', 'xpro-bb-addons' ),
                                'default'   => 'no_template',
                                'options' => XPRO_Plugins_Helper::get_xpro_saved_templates(),
                            ),
                            'edit_template' => array(
                                'type'    => 'raw',
                                'label'   => __( ' ', 'xpro-bb-addons' ),
                                'content' => did_action( 'xpro_addons_for_bb_loaded' ) ? '<button data-url="'.home_url('/').'" class="xpro-template-edit-button">Edit Template</button>' : '',
                            ),
                            'create_template' => array(
                                'type'    => 'raw',
                                'label'   => __( ' ', 'xpro-bb-addons' ),
                                'content' => did_action( 'xpro_addons_for_bb_loaded' ) ? 'Wondering what is section template or need to create one? Please click <a href="' . esc_url( admin_url('/').'edit.php?post_type=xpro_bb_templates' ) . '" class="xpro-template-create-button" target="_blank">here</a>' : '',
                            ),
                            'content_bb_row'    => array(
                                'type'    => 'select',
                                'label'   => __( 'Saved Row', 'xpro-bb-addons' ),
                                'default'   => 'no_template',
                                'options' => XPRO_Plugins_Helper::get_saved_row_template(),
                            ),
                            'edit_bb_row' => array(
                                'type'    => 'raw',
                                'label'   => __( ' ', 'xpro-bb-addons' ),
                                'content' => '<button data-url="'.home_url('/').'" class="xpro-row-edit-button">Edit Saved Row</button>',
                            ),
                            'create_bb_row' => array(
                                'type'    => 'raw',
                                'label'   => __( ' ', 'xpro-bb-addons' ),
                                'content' => 'If you are using Beaver Builder Pro version you can simply edit any row and click on "Save As"',
                            ),
                        ),
                    ),
				),
			),
		),
	)
);
