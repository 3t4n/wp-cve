<?php

require_once GFS_DIR . 'includes/class-ib-customizer.php';

add_action( 'after_setup_theme', 'gfs_register_customizer' );
function gfs_register_customizer() {
	IBCustomizer::add_panel(
		array(
			'gfs_style'	=> array(
				'title'	=> __( 'Styler for Gravity Forms', 'gfs' ),
				'priority'	=> 500,
				'sections'	=> array(
					'gfs_form_style'	=> array(
						'title'			=> __( 'Colors', 'gfs' ),
						'description'	=> __( 'Customize forms text color, background color, add a background image.', 'gfs' ),
						'fields'            => array(
							'gfs_form_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper',
									'property'		=> 'color'
								)
		                    ),
							'gfs_form_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper',
									'property'      => 'background-color',
								)
		                    ),
							'gfs_form_bg_image' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'class'          => 'WP_Customize_Image_Control',
		                            'label'         => __('Background Image', 'gfs'),
		                        )
		                    ),
							'gfs_form_bg_size' => array(
		                        'setting'    => array(
		                            'default' => 'cover',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Background Size', 'gfs'),
									'choices'		=> array(
										'cover'		=> __('Cover', 'gfs'),
										'contain'		=> __('Contain', 'gfs'),
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper',
									'property'		=> 'background-size'
								)
							),
							'gfs_form_bg_repeat' => array(
		                        'setting'    => array(
		                            'default' => 'no-repeat',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Background Repeat', 'gfs'),
									'choices'		=> array(
										'repeat-x'      => __('Repeat X', 'gfs'),
			                            'repeat-y'      => __('Repeat Y', 'gfs'),
			                            'no-repeat'     => __('No Repeat', 'gfs'),
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper',
									'property'		=> 'background-repeat'
								)
							),
							'gfs_form_bg_overlay' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Overlay', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper:before',
									'property'      => 'background-color',
								)
		                    ),
							'gfs_form_bg_opacity' => array(
		                        'setting'    => array(
		                            'default' 		=> 0.7,
									'transport'    	=> 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Background Opacity', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 1,
		                                'step'                 => 0.1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper:before',
									'property'		=> 'opacity',
								)
		                    ),
							'gfs_product_price_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Product Price Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper span.ginput_product_price, div.gform_wrapper .gfield_price .ginput_container_total span',
									'property'		=> 'color'
								)
		                    ),
						)
					),
					'gfs_form_padding_margin'	=> array(
						'title'	=> __( 'Structure', 'gfs' ),
						'fields'	=> array(
							'gfs_form_padding' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'       => 0,
		                                'bottom'    => 0,
		                                'left'      => 0,
		                                'right'     => 0
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Padding (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top' => array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'padding-top',
											'unit'			=> 'px'
										),
										'bottom' => array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'padding-bottom',
											'unit'			=> 'px'
										),
										'left' => array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'padding-left',
											'unit'			=> 'px'
										),
										'right' => array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'padding-right',
											'unit'			=> 'px'
										),
									)
								)
		                    ),
							'gfs_form_margin' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        	=> 0,
			                            'bottom'    	=> 0,
		                                'left'        	=> 0,
			                            'right'        	=> 0
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Margin (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top' 			=> array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'margin-top',
											'unit'			=> 'px'
										),
										'bottom' 		=> array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'margin-bottom',
											'unit'			=> 'px'
										),
										'left' 			=> array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'margin-left',
											'unit'			=> 'px'
										),
										'right' 		=> array(
											'selector'      => '.gform_wrapper',
											'property'		=> 'margin-right',
											'unit'			=> 'px'
										),
									)
								)
		                    ),
							'gfs_form_border_width' => array(
		                        'setting'    => array(
		                            'default' 		=> 0,
									'transport'    	=> 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_form_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'			=> 'css',
									'selector'      => '.gform_wrapper',
									'property'      => 'border-color',
								)
		                    ),
							'gfs_form_border_radius' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => '.gform_wrapper',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
						)
					),
					'gfs_form_title_style'	=> array(
						'title'	=> __( 'Title', 'gfs' ),
						'description'	=> __( 'Style the title of a form by adding color, font size and alignment.', 'gfs' ),
						'fields'	=> array(
							'gfs_title_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h3.gform_title',
									'property'		=> 'color'
								)
		                    ),
							'gfs_title_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h3.gform_title',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_title_alignment' => array(
		                        'setting'    => array(
		                            'default' => 'left',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Alignment', 'gfs'),
									'choices'		=> array(
										'left'		=> __('Left', 'gfs'),
										'center'		=> __('Center', 'gfs'),
										'right'		=> __('Right', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h3.gform_title',
									'property'		=> 'text-align'
								)
							),
							'gfs_title_text_transform' => array(
		                        'setting'    => array(
		                            'default' => 'none',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Text Transform', 'gfs'),
									'choices'		=> array(
										'none'		=> __('None', 'gfs'),
										'lowercase'		=> __('lowercase', 'gfs'),
										'uppercase'		=> __('UPPERCASE', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h3.gform_title',
									'property'		=> 'text-transform'
								)
							),
							'gfs_title_letter_spacing' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Letter Spacing (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => -10,
		                                'max'                => 10,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h3.gform_title',
									'property'		=> 'letter-spacing',
									'unit'			=> 'px'
								)
		                    ),
						)
					),
					'gfs_form_description_style'	=> array(
						'title'	=> __( 'Description', 'gfs' ),
						'description'	=> __( 'Style the description of a form by adding color, font size and alignment.', 'gfs' ),
						'fields'	=> array(
							'gfs_description_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper span.gform_description',
									'property'		=> 'color'
								)
		                    ),
							'gfs_description_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper span.gform_description',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_description_alignment' => array(
		                        'setting'    => array(
		                            'default' => 'left',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Alignment', 'gfs'),
									'choices'		=> array(
										'left'		=> __('Left', 'gfs'),
										'center'		=> __('Center', 'gfs'),
										'right'		=> __('Right', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper span.gform_description',
									'property'		=> 'text-align'
								)
							),
						)
					),
					'gfs_form_label_style'	=> array(
						'title'	=> __( 'Labels', 'gfs' ),
						'description'	=> __( 'Style the labels of a form by adding color, font size, text transform and letter spacing.', 'gfs' ),
						'fields'	=> array(
							'gfs_labels' => array(
		                        'setting'    => array(
		                            'default' => 'block',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Hide Labels?', 'gfs'),
									'choices'		=> array(
										'none'		=> __('Yes', 'gfs'),
										'block'		=> __('No', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'display'
								),
								'toggle'		=> array(
									'block'	=> array( 'gfs_label_color', 'gfs_label_font_size', 'gfs_label_text_transform', 'gfs_label_letter_spacing', 'gfs_label_alignment' )
								)
							),
							'gfs_label_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'color'
								)
		                    ),
							'gfs_label_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_label_text_transform' => array(
		                        'setting'    => array(
		                            'default' => 'none',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Text Transform', 'gfs'),
									'choices'		=> array(
										'none'		=> __('None', 'gfs'),
										'lowercase'		=> __('lowercase', 'gfs'),
										'uppercase'		=> __('UPPERCASE', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'text-transform'
								)
							),
							'gfs_label_letter_spacing' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Letter Spacing (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => -10,
		                                'max'                => 10,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'letter-spacing',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_label_alignment' => array(
		                        'setting'    => array(
		                            'default' => 'left',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Alignment', 'gfs'),
									'choices'		=> array(
										'left'		=> __('Left', 'gfs'),
										'center'		=> __('Center', 'gfs'),
										'right'		=> __('Right', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .top_label .gfield_label',
									'property'		=> 'text-align'
								)
							),
						)
					),
					'gfs_form_section_style'	=> array(
						'title'	=> __( 'Sections', 'gfs' ),
						'description'	=> __( 'Style the sections of a form by adding font size, color and border.', 'gfs' ),
						'fields'	=> array(
							'gfs_section_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h2.gsection_title',
									'property'		=> 'color'
								)
		                    ),
							'gfs_section_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper h2.gsection_title',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_section_border_width' => array(
		                        'setting'    => array(
		                            'default' => 1,
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gsection',
									'property'		=> 'border-bottom-width',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_section_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gsection',
									'property'		=> 'border-bottom-color'
								)
		                    ),
							'gfs_section_margin_bottom' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Margin Bottom (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gsection',
									'property'		=> 'margin-bottom',
									'unit'			=> 'px'
								)
		                    ),
						)
					),
					'gfs_form_input_style'	=> array(
						'title'	=> __( 'Inputs', 'gfs' ),
						'description'	=> __( 'Style the input fields of a form by adding width, colors, border, padding and margins.', 'gfs' ),
						'fields'	=> array(
							'gfs_input_width'      => array(
                                'setting'    => array(
                                    'default' => 'no',
									'transport'    => 'postMessage'
                                ),
                                'control'    => array(
                                    'type'              => 'radio',
                                    'label'             => __('Full Width?', 'gfs'),
                                    'choices'           => array(
                                        '100%'          => __('Yes', 'gfs'),
                                        'no'        => __('No', 'gfs')
                                    ),
                                ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'width'
								)
                            ),
							'gfs_input_height'      => array(
                                'setting'    => array(
                                    'default' => 'auto',
									'transport'    => 'postMessage'
                                ),
                                'control'    => array(
                                    'type'          => 'radio',
                                    'label'         => __('Height', 'gfs'),
                                    'choices'       => array(
                                        'auto'          => __('Auto', 'gfs'),
                                        'custom'        => __('Custom', 'gfs')
                                    ),
                                ),
								'toggle'	=> array(
									'custom'	=> array('gfs_input_height_custom', 'gfs_textarea_height_custom')	
								)
                            ),
							'gfs_input_height_custom' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Input Height (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'               => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]), div.gform_wrapper .gfield select',
									'property'		=> 'height',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_textarea_height_custom' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Textarea Height (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 500,
		                                'step'               => 10
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield textarea',
									'property'		=> 'height',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_input_alignment' => array(
		                        'setting'    => array(
		                            'default' => 'left',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Text Alignment', 'gfs'),
									'choices'		=> array(
										'left'		=> __('Left', 'gfs'),
										'center'		=> __('Center', 'gfs'),
										'right'		=> __('Right', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'text-align'
								)
							),
							'gfs_input_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'color',
								)
		                    ),
							'gfs_input_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'background-color',
								)
		                    ),
							'gfs_inputs_padding' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        => 10,
		                                'bottom'    => 10,
		                                'left'        => 10,
		                                'right'        => 10
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Padding (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'padding-top',
											'unit'			=> 'px'
										),
										'bottom'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'padding-bottom',
											'unit'			=> 'px'
										),
										'left'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'padding-left',
											'unit'			=> 'px'
										),
										'right'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'padding-right',
											'unit'			=> 'px'
										)
									)
								)
		                    ),
							'gfs_input_margin' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Margin (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'               => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper ul.gform_fields li.gfield',
									'property'		=> 'margin-top',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_input_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_input_border_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_input_border_width' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        => 1,
		                                'bottom'    	=> 1,
		                                'left'        => 1,
		                                'right'        => 1
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
									'type'          => 'ib-multitext',
		                            'label'         => __( 'Border Width (px)', 'gfs' ),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __( 'Top', 'gfs' ),
		                                'bottom'         => __( 'Bottom', 'gfs' ),
		                                'left'           => __( 'Left', 'gfs' ),
		                                'right'          => __( 'Right', 'gfs' )
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'border-top-width',
											'unit'			=> 'px'
										),
										'bottom'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'border-bottom-width',
											'unit'			=> 'px'
										),
										'left'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'border-left-width',
											'unit'			=> 'px'
										),
										'right'		=> array(
											'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
											'property'		=> 'border-right-width',
											'unit'			=> 'px'
										)
									)
								)
		                    ),
							'gfs_input_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'border-color',
								)
		                    ),
							'gfs_input_focus_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Focus Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, div.gform_wrapper .gfield select:focus, div.gform_wrapper .gfield textarea:focus',
									'property'		=> 'border-color',
								)
		                    ),
							'gfs_input_border_radius' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper select, div.gform_wrapper textarea',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_input_placeholder_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_input_placeholder'      => array(
                                'setting'    => array(
                                    'default' => 'no',
									'transport'    => 'postMessage'
                                ),
                                'control'    => array(
                                    'type'              => 'select',
                                    'label'             => __('Hide Placeholder?', 'gfs'),
                                    'choices'           => array(
                                        'yes'          => __('Yes', 'gfs'),
                                        'no'        => __('No', 'gfs')
                                    ),
                                ),
								'toggle'		=> array(
									'no'	=> array( 'gfs_input_placeholder_color' )
								)
                            ),
							'gfs_input_placeholder_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Placeholder Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input::-webkit-input-placeholder, div.gform_wrapper .gfield select::-webkit-input-placeholder, div.gform_wrapper .gfield textarea::-webkit-input-placeholder',
									'property'		=> 'color',
								)
		                    ),
							'gfs_input_description_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_input_desc_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __( 'Description Font Size (px)', 'gfs' ),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield .gfield_description',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_input_desc_line_height' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_float',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __( 'Description Line height', 'gfs' ),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 3,
		                                'step'                 => 0.1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield .gfield_description',
									'property'		=> 'line-height',
								)
		                    ),
							'gfs_input_desc_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Description Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield .gfield_description',
									'property'		=> 'color',
								)
		                    ),
						)
					),
					'gfs_form_radio_checkbox_style'	=> array(
						'title'	=> __( 'Radio & Checkbox', 'gfs' ),
						'description'	=> __( 'Style the radio and checkbox fields of a form by adding size, colors and border.', 'gfs' ),
						'fields'	=> array(
							'gfs_radio_cb_style' => array(
		                        'setting'    => array(
		                            'default' => 'no',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Enable Custom Style', 'gfs'),
									'choices'		=> array(
										'yes'			=> __('Yes', 'gfs'),
										'no'			=> __('No', 'gfs'),
									),
		                        ),
								'toggle'	=> array(
									'yes'	=> array( 'gfs_radio_cb_size', 'gfs_radio_cb_color', 'gfs_radio_cb_checked_color', 'gfs_radio_cb_border_separator', 'gfs_radio_cb_border_width', 'gfs_radio_cb_border_color', 'gfs_radio_cb_radius', 'gfs_radio_cb_checkbox_radius' )
								)
							),
							'gfs_radio_cb_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __( 'Size (px)', 'gfs' ),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 20,
		                                'step'               => 1 
		                            ),
		                        ),
		                    ),
							'gfs_radio_cb_label_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __( 'Label Size (px)', 'gfs' ),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'               => 1 
		                            ),
		                        ),
		                    ),
							'gfs_radio_cb_color' => array(
		                        'setting'    => array(
		                            'default' => '#dddddd',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_radio li input[type=radio], div.gform_wrapper .gfield_checkbox li input[type=checkbox], div.gform_wrapper .gfield_radio li input[type=radio]:focus, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus',
									'property'		=> 'background-color',
								)
		                    ),
							'gfs_radio_cb_checked_color' => array(
		                        'setting'    => array(
		                            'default' => '#999999',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Checked Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_radio li input[type=radio]:checked:before, div.gform_wrapper .gfield_radio li input[type=radio]:focus:checked:before, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:checked:before, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus:checked:before',
									'property'		=> 'background-color',
								)
		                    ),
							'gfs_radio_cb_border_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_radio_cb_border_width' => array(
		                        'setting'    => array(
		                            'default' => '1',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 10,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_radio li input[type=radio], div.gform_wrapper .gfield_checkbox li input[type=checkbox], div.gform_wrapper .gfield_radio li input[type=radio]:focus, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_radio_cb_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_radio li input[type=radio], div.gform_wrapper .gfield_checkbox li input[type=checkbox], div.gform_wrapper .gfield_radio li input[type=radio]:focus, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus',
									'property'		=> 'border-color',
								)
		                    ),
							'gfs_radio_cb_radius' => array(
		                        'setting'    => array(
		                            'default' => 50,
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Radio Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_radio li input[type=radio], div.gform_wrapper .gfield_radio li input[type=radio]:focus, div.gform_wrapper .gfield_radio li input[type=radio]:before, div.gform_wrapper .gfield_radio li input[type=radio]:focus:before',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_radio_cb_checkbox_radius' => array(
		                        'setting'    => array(
		                            'default' => 0,
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Checkbox Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_checkbox li input[type=checkbox], div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:before, div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus:before',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
						),
					),
					'gfs_form_file_upload_style'	=> array(
						'title'	=> __( 'File Upload', 'gfs' ),
						'description'	=> __( 'Style the file upload fields of a form by adding colors, border and padding.', 'gfs' ),
						'fields'	=> array(
							'gfs_file_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input[type=file]',
									'property'		=> 'background-color'
								),
		                    ),
							'gfs_file_text_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input[type=file]',
									'property'		=> 'color'
								),
		                    ),
							'gfs_file_border_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_file_border_width' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input[type=file]',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								),
		                    ),
							'gfs_file_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input[type=file]',
									'property'		=> 'border-color'
								),
		                    ),
							'gfs_file_border_style' => array(
		                        'setting'    => array(
		                            'default' => 'solid',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Border Style', 'gfs'),
									'choices'		=> array(
										'solid'			=> __('Solid', 'gfs'),
										'dashed'		=> __('Dashed', 'gfs'),
										'dotted'		=> __('Dotted', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield input[type=file]',
									'property'		=> 'border-style'
								)
							),
							'gfs_file_padding_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_file_padding' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        => '',
		                                'bottom'    => '',
		                                'left'        => '',
		                                'right'        => ''
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Padding (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top'		=> array(
											'selector'      => 'div.gform_wrapper .gfield input[type=file]',
											'property'		=> 'padding-top',
											'unit'			=> 'px'
										),
										'bottom'	=> array(
											'selector'      => 'div.gform_wrapper .gfield input[type=file]',
											'property'		=> 'padding-bottom',
											'unit'			=> 'px'
										),
										'left'		=> array(
											'selector'      => 'div.gform_wrapper .gfield input[type=file]',
											'property'		=> 'padding-left',
											'unit'			=> 'px'
										),
										'right'		=> array(
											'selector'      => 'div.gform_wrapper .gfield input[type=file]',
											'property'		=> 'padding-right',
											'unit'			=> 'px'
										)
									)
								)
		                    ),
						),
					),
					'gfs_form_button_style'	=> array(
						'title'	=> __( 'Button', 'gfs' ),
						'description'	=> __( 'Customize the form button with colors, border, even change the hover state colors.', 'gfs' ),
						'fields'	=> array(
							'gfs_button_width'      => array(
                                'setting'    => array(
                                    'default' => 'auto',
									'transport'    => 'postMessage'
                                ),
                                'control'    => array(
                                    'type'          => 'radio',
                                    'label'         => __('Full Width?', 'gfs'),
                                    'choices'		=> array(
                                        '100%'          => __('Yes', 'gfs'),
                                        'auto'        	=> __('No', 'gfs')
                                    ),
                                ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'width'
								),
								'toggle'		=> array(
									'auto'	=> array( 'gfs_button_alignment' )
								)
                            ),
							'gfs_button_alignment' => array(
		                        'setting'    => array(
		                            'default' => 'left',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Alignment', 'gfs'),
									'choices'		=> array(
										'left'		=> __('Left', 'gfs'),
										'center'		=> __('Center', 'gfs'),
										'right'		=> __('Right', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer, div.gform_wrapper .gform_page_footer',
									'property'		=> 'text-align'
								)
							),
							'gfs_button_text_transform' => array(
		                        'setting'    => array(
		                            'default' => 'none',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Text Transform', 'gfs'),
									'choices'		=> array(
										'none'		=> __('None', 'gfs'),
										'lowercase'		=> __('lowercase', 'gfs'),
										'uppercase'		=> __('UPPERCASE', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'text-transform'
								)
							),
							'gfs_button_letter_spacing' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Letter Spacing (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => -10,
		                                'max'                => 10,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'letter-spacing',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_button_font_size' => array(
								'setting'    => array(
									'default' => '',
									'sanitize_callback' => 'gfs_sanitize_integer',
									'transport'    => 'postMessage'
								),
								'control'    => array(
									'type'          => 'ib-slider',
									'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
										'min'                => 0,
										'max'                => 100,
										'step'                 => 1
									),
								),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
							),
							'gfs_button_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'color',
								)
		                    ),
							'gfs_button_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'background-color',
								)
		                    ),
							'gfs_button_hover_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Text Hover Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button:hover, div.gform_wrapper .gform_page_footer .button:hover',
									'property'		=> 'color',
								)
		                    ),
							'gfs_button_bg_hover_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Background Hover Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button:hover, div.gform_wrapper .gform_page_footer .button:hover',
									'property'		=> 'background-color',
								)
		                    ),
							'gfs_button_padding' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        => 10,
		                                'bottom'    => 10,
		                                'left'        => 10,
		                                'right'        => 10
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Padding (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top'		=> array(
											'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
											'property'		=> 'padding-top',
											'unit'			=> 'px'
										),
										'bottom'	=> array(
											'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
											'property'		=> 'padding-bottom',
											'unit'			=> 'px'
										),
										'left'		=> array(
											'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
											'property'		=> 'padding-left',
											'unit'			=> 'px'
										),
										'right'		=> array(
											'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
											'property'		=> 'padding-right',
											'unit'			=> 'px'
										)
									)
								)
		                    ),
							'gfs_button_border_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_button_border_width' => array(
		                        'setting'    => array(
		                            'default' => 0,
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_button_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Border Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'border-color'
								)
		                    ),
							'gfs_button_border_radius' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gform_footer .gform_button, div.gform_wrapper .gform_page_footer .button',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
						)
					),
					'gfs_form_errors_style'	=> array(
						'title'			=> __( 'Errors', 'gfs' ),
						'description'	=> __( 'Customize the form errors with colors, border, even change the style of error fields.', 'gfs' ),
						'fields'		=> array(
							'gfs_error_validation' => array(
		                        'setting'    => array(
		                            'default' => 'block',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Hide Error Validation?', 'gfs'),
									'choices'		=> array(
										'none'		=> __('Yes', 'gfs'),
										'block'		=> __('No', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper div.validation_error',
									'property'		=> 'display'
								),
								'toggle'		=> array(
									'block'	=> array( 'gfs_error_description_color' )
								)
							),
							'gfs_error_description_color' => array(
		                        'setting'    => array(
		                            'default' => '#790000',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Description Color', 'gfs'),
									'default' 		=> '#790000',
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper div.validation_error',
									'property'		=> 'color'
								)
		                    ),
							'gfs_error_border_color' => array(
		                        'setting'    => array(
		                            'default' => '#790000',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Border Color', 'gfs' ),
									'default' 		=> '#790000',
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										array(
											'selector'      => 'div.gform_wrapper div.validation_error',
											'property'		=> 'border-top-color'
										),
										array(
											'selector'      => 'div.gform_wrapper div.validation_error',
											'property'		=> 'border-bottom-color'
										)
									)
								)
		                    ),
							'gfs_error_field_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '#ffdfe0',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Field Background Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper li.gfield.gfield_error',
									'property'		=> 'background-color'
								)
		                    ),
							'gfs_error_field_label_color' => array(
		                        'setting'    => array(
		                            'default' => '#790000',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Field Label Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .gfield_error .gfield_label',
									'property'		=> 'color'
								)
		                    ),
							'gfs_error_field_input_border_color' => array(
		                        'setting'    => array(
		                            'default' => '#790000',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Field Input Border Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper li.gfield_error textarea',
									'property'		=> 'border-color',
								)
		                    ),
							'gfs_error_field_input_border_width' => array(
		                        'setting'    => array(
		                            'default' => 0,
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Error Field Input Border Width', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), div.gform_wrapper li.gfield_error textarea',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_error_field_message' => array(
		                        'setting'    => array(
		                            'default' => 'block',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Hide Error Field Message?', 'gfs'),
									'choices'		=> array(
										'none'		=> __('Yes', 'gfs'),
										'block'		=> __('No', 'gfs')
									),
		                        ),
								'toggle'		=> array(
									'block'	=> array( 'gfs_error_field_message_color' )
								),
								'preview'		=> array(
									'type'		=> 'css',
									'selector'	=> 'div.gform_wrapper .validation_message',
									'property'	=> 'display'
								)
							),
							'gfs_error_field_message_color' => array(
		                        'setting'    => array(
		                            'default' => '#790000',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Error Field Message Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_wrapper .validation_message',
									'property'		=> 'color'
								)
		                    ),
						)
					),
					'gfs_form_success_msg_style'	=> array(
						'title'			=> __( 'Success Message', 'gfs' ),
						'description'	=> __( 'Customize the form success message with colors, border, font size.', 'gfs' ),
						'fields'		=> array(
							'gfs_success_msg_bg_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Background Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper',
									'property'		=> 'background-color'
								)
		                    ),
							'gfs_success_msg_text_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __( 'Text Color', 'gfs' ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper .gform_confirmation_message',
									'property'		=> 'color'
								)
		                    ),
							'gfs_success_msg_border_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_success_msg_border_width' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Width (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper',
									'property'		=> 'border-width',
									'unit'			=> 'px'
								),
		                    ),
							'gfs_success_msg_border_color' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'color',
		                            'label'         => __('Border Color', 'gfs'),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper',
									'property'		=> 'border-color'
								),
		                    ),
							'gfs_success_msg_border_style' => array(
		                        'setting'    => array(
		                            'default' => 'solid',
									'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'select',
		                            'label'         => __('Border Style', 'gfs'),
									'choices'		=> array(
										'solid'			=> __('Solid', 'gfs'),
										'dashed'		=> __('Dashed', 'gfs'),
										'dotted'		=> __('Dotted', 'gfs')
									),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper',
									'property'		=> 'border-style'
								)
							),
							'gfs_success_msg_border_radius' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Border Radius (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper',
									'property'		=> 'border-radius',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_success_msg_padding_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_success_msg_padding' => array(
		                        'setting'    => array(
									'default' => array(
		                                'top'        => '',
		                                'bottom'    => '',
		                                'left'        => '',
		                                'right'        => ''
		                            ),
	                            	'transport'    => 'postMessage'
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-multitext',
		                            'label'         => __('Padding (px)', 'gfs'),
		                            'class' 		=> 'IBCustomizerControl',
									'choices'            => array(
		                                'top'            => __('Top', 'gfs'),
		                                'bottom'         => __('Bottom', 'gfs'),
		                                'left'           => __('Left', 'gfs'),
		                                'right'          => __('Right', 'gfs')
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'rules'			=> array(
										'top'		=> array(
											'selector'      => 'div.gform_confirmation_wrapper',
											'property'		=> 'padding-top',
											'unit'			=> 'px'
										),
										'bottom'	=> array(
											'selector'      => 'div.gform_confirmation_wrapper',
											'property'		=> 'padding-bottom',
											'unit'			=> 'px'
										),
										'left'		=> array(
											'selector'      => 'div.gform_confirmation_wrapper',
											'property'		=> 'padding-left',
											'unit'			=> 'px'
										),
										'right'		=> array(
											'selector'      => 'div.gform_confirmation_wrapper',
											'property'		=> 'padding-right',
											'unit'			=> 'px'
										)
									)
								)
		                    ),
							'gfs_success_msg_typography_separator' => array(
		                        'setting'    => array(
		                            'default' => '',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-line',
									'class'			=> 'IBCustomizerControl',
		                            'label'         => '',
		                        ),
							),
							'gfs_success_msg_font_size' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_integer',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __('Font Size (px)', 'gfs'),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 100,
		                                'step'                 => 1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper .gform_confirmation_message',
									'property'		=> 'font-size',
									'unit'			=> 'px'
								)
		                    ),
							'gfs_success_msg_line_height' => array(
		                        'setting'    => array(
		                            'default' => '',
									'transport'    => 'postMessage',
									'sanitize_callback' => 'gfs_sanitize_float',
		                        ),
		                        'control'    => array(
		                            'type'          => 'ib-slider',
		                            'label'         => __( 'Line height', 'gfs' ),
									'class'			=> 'IBCustomizerControl',
									'choices'            => array(
		                                'min'                => 0,
		                                'max'                => 3,
		                                'step'                 => 0.1
		                            ),
		                        ),
								'preview'       => array(
									'type'          => 'css',
									'selector'      => 'div.gform_confirmation_wrapper .gform_confirmation_message',
									'property'		=> 'line-height',
								)
		                    ),
						)
					)
				)
			)
		)
	);

}

add_action( 'wp_head', 'gfs_output_styles', 1000 );
function gfs_output_styles() {
	$form_padding 		 = IBCustomizer::get_mod('gfs_form_padding', true);
	$form_margin 		 = IBCustomizer::get_mod('gfs_form_margin', true);
	$input_padding 		 = IBCustomizer::get_mod('gfs_inputs_padding', true);
	$input_border		 = IBCustomizer::get_mod('gfs_input_border_width', true);
	$button_padding 	 = IBCustomizer::get_mod('gfs_button_padding', true);
	$file_padding 		 = IBCustomizer::get_mod('gfs_file_padding', true);
	$success_msg_padding = IBCustomizer::get_mod('gfs_success_msg_padding', true);
	 ?>
	<style type="text/css">
		div.gform_wrapper {
			position: relative;
			background-color: <?php echo (IBCustomizer::get_mod( 'gfs_form_bg_color' )) ? IBCustomizer::get_mod( 'gfs_form_bg_color' ) : 'transparent' ; ?>;
			<?php if ( IBCustomizer::get_mod('gfs_form_bg_image') ) { ?>
			background-image: url(<?php echo IBCustomizer::get_mod('gfs_form_bg_image'); ?>);
			background-size: <?php echo IBCustomizer::get_mod('gfs_form_bg_size'); ?>;
			background-repeat: <?php echo IBCustomizer::get_mod('gfs_form_bg_repeat'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_form_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod( 'gfs_form_color' ); ?>;
			<?php } ?>
			<?php if ( isset( $form_padding['top'] ) && $form_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $form_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_padding['bottom'] ) && $form_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $form_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_padding['left'] ) && $form_padding['left'] >= 0 ) { ?>
			padding-left: <?php echo $form_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_padding['right'] ) && $form_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $form_padding['right']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_margin['top'] ) && ( $form_margin['top'] >= 0 || $form_margin['top'] < 0 ) ) { ?>
			margin-top: <?php echo $form_margin['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_margin['bottom'] ) && ( $form_margin['bottom'] >= 0 || $form_margin['bottom'] < 0 ) ) { ?>
			margin-bottom: <?php echo $form_margin['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_margin['left'] ) && ( $form_margin['left'] >= 0 || $form_margin['left'] < 0 ) ) { ?>
			margin-left: <?php echo $form_margin['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $form_margin['right'] ) && ( $form_margin['right'] >= 0 || $form_margin['right'] < 0 ) ) { ?>
			margin-right: <?php echo $form_margin['right']; ?>px;
			<?php } ?>
			border-style: solid;
			border-width: 0;
			<?php if ( IBCustomizer::get_mod('gfs_form_border_width') != '' && IBCustomizer::get_mod('gfs_form_border_width') >= 0 ) { ?>
			border-width: <?php echo IBCustomizer::get_mod('gfs_form_border_width'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_form_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_form_border_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_form_border_radius') != '' && IBCustomizer::get_mod('gfs_form_border_radius') >= 0 ) { ?>
			border-radius: <?php echo IBCustomizer::get_mod('gfs_form_border_radius'); ?>px;
			<?php } ?>
		}

		<?php if ( IBCustomizer::get_mod('gfs_form_bg_image') ) { ?>
		div.gform_wrapper:before {
			content: "";
			display: block;
			position: absolute;;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: <?php echo ( IBCustomizer::get_mod('gfs_form_bg_overlay') ) ? gfs_hex2rgba( IBCustomizer::get_mod('gfs_form_bg_overlay'), IBCustomizer::get_mod('gfs_form_bg_opacity')) : 'transparent'; ?>;
		}
		<?php } ?>

		div.gform_wrapper form {
			position: relative;
		}

		div.gform_wrapper .gform_title {
			<?php if ( IBCustomizer::get_mod('gfs_title_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod( 'gfs_title_color' ); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_title_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_title_font_size'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_title_alignment') ) { ?>
			text-align: <?php echo IBCustomizer::get_mod('gfs_title_alignment'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_title_text_transform') ) { ?>
			text-transform: <?php echo IBCustomizer::get_mod('gfs_title_text_transform'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_title_letter_spacing') ) { ?>
			letter-spacing: <?php echo IBCustomizer::get_mod('gfs_title_letter_spacing'); ?>px !important;
			<?php } ?>
		}

		div.gform_wrapper span.gform_description {
			<?php if ( IBCustomizer::get_mod('gfs_description_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod( 'gfs_description_color' ); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_description_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_description_font_size'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_description_alignment') ) { ?>
			text-align: <?php echo IBCustomizer::get_mod('gfs_description_alignment'); ?>;
			<?php } ?>
		}

		div.gform_wrapper .top_label .gfield_label {
			<?php if ( IBCustomizer::get_mod('gfs_labels') ) { ?>
				display: <?php echo IBCustomizer::get_mod('gfs_labels'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_label_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_label_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_label_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_label_font_size'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_label_text_transform') ) { ?>
			text-transform: <?php echo IBCustomizer::get_mod('gfs_label_text_transform'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_label_letter_spacing') ) { ?>
			letter-spacing: <?php echo IBCustomizer::get_mod('gfs_label_letter_spacing'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_label_alignment') ) { ?>
			text-align: <?php echo IBCustomizer::get_mod('gfs_label_alignment'); ?>;
			<?php } ?>
		}

		div.gform_wrapper .gfield .ginput_complex.ginput_container label {
			<?php if ( IBCustomizer::get_mod('gfs_labels') ) { ?>
			display: <?php echo IBCustomizer::get_mod('gfs_labels'); ?>;
			<?php } ?>
			margin-bottom: 0;
		}

		div.gform_wrapper .ginput_container label,
		div.gform_wrapper table.gfield_list thead th,
		div.gform_wrapper span.ginput_product_price_label,
		div.gform_wrapper span.ginput_quantity_label,
		div.gform_wrapper .gfield_html {
			<?php if ( IBCustomizer::get_mod('gfs_label_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_label_color'); ?>;
			<?php } ?>
		}

		div.gform_wrapper span.ginput_product_price,
		div.gform_wrapper .gfield_price .ginput_container_total span {
			<?php if ( IBCustomizer::get_mod('gfs_product_price_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_product_price_color'); ?> !important;
			<?php } ?>
		}

		div.gform_wrapper .gsection {
			<?php if ( IBCustomizer::get_mod('gfs_section_border_width') >= 0 ) { ?>
				border-bottom-width: <?php echo IBCustomizer::get_mod('gfs_section_border_width'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_section_border_color') ) { ?>
				border-bottom-color: <?php echo IBCustomizer::get_mod('gfs_section_border_color'); ?>;
			<?php } ?>
			margin-right: 0;
			<?php if ( IBCustomizer::get_mod('gfs_section_margin_bottom') >= 0 ) { ?>
				margin-bottom: <?php echo IBCustomizer::get_mod('gfs_section_margin_bottom'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper h2.gsection_title {
			<?php if ( IBCustomizer::get_mod('gfs_section_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_section_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_section_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_section_font_size'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper ul.gfield_radio li input:not([type='radio']):not([type='checkbox']):not([type='submit']):not([type='button']):not([type='image']):not([type='file']) {
		    width: auto !important;
			display: inline-block;
		}

		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]),
		div.gform_wrapper select,
		div.gform_wrapper textarea {
			<?php if ( IBCustomizer::get_mod('gfs_input_width') == '100%' ) { ?>
				width: <?php echo IBCustomizer::get_mod('gfs_input_width'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_input_alignment') ) { ?>
			text-align: <?php echo IBCustomizer::get_mod('gfs_input_alignment'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_input_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_input_color'); ?>;
			<?php } ?>
			background-color: <?php echo (IBCustomizer::get_mod('gfs_input_bg_color')) ? IBCustomizer::get_mod('gfs_input_bg_color') : 'transparent'; ?>;
			<?php if ( isset( $input_padding['top'] ) && $input_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $input_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_padding['bottom'] ) && $input_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $input_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_padding['left'] ) && $input_padding['left'] >= 0 ) {	 ?>
			padding-left: <?php echo $input_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_padding['right'] ) && $input_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $input_padding['right']; ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_input_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_input_font_size'); ?>px;
			<?php } ?>
			<?php if ( isset( $input_border['top'] ) && $input_border['top'] >= 0 ) { ?>
			border-top-width: <?php echo $input_border['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_border['bottom'] ) && $input_border['bottom'] >= 0 ) { ?>
			border-bottom-width: <?php echo $input_border['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_border['left'] ) && $input_border['left'] >= 0 ) { ?>
			border-left-width: <?php echo $input_border['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $input_border['right'] ) && $input_border['right'] >= 0 ) { ?>
			border-right-width: <?php echo $input_border['right']; ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_input_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_input_border_color'); ?>;
			<?php } ?>
			border-style: solid;
			<?php if ( IBCustomizer::get_mod('gfs_input_border_radius') >= 0 ) { ?>
			border-radius: <?php echo IBCustomizer::get_mod('gfs_input_border_radius'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper .ginput_complex input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=image]):not([type=file]) {
		    width: 100%;
		}

		div.gform_wrapper .gfield input:not([type='radio']):not([type='checkbox']):not([type='submit']):not([type='button']):not([type='image']):not([type='file']),
		div.gform_wrapper .gfield select {
			<?php if ( IBCustomizer::get_mod('gfs_input_height') == 'custom' ) { ?>
				height: <?php echo IBCustomizer::get_mod('gfs_input_height_custom'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper .gfield textarea {
			<?php if ( IBCustomizer::get_mod('gfs_input_height') == 'custom' ) { ?>
				height: <?php echo IBCustomizer::get_mod('gfs_textarea_height_custom'); ?>px;
			<?php } ?>
		}
		
		<?php if ( IBCustomizer::get_mod('gfs_input_placeholder') == 'no' && IBCustomizer::get_mod('gfs_input_placeholder_color') != '' ) { ?>
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])::-webkit-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])::-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):-ms-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield textarea::-webkit-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield textarea:-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield textarea::-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield textarea:-ms-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield select::-webkit-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield select:-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield select::-moz-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		div.gform_wrapper .gfield select:-ms-input-placeholder {
		    color: <?php echo IBCustomizer::get_mod('gfs_input_placeholder_color'); ?>;
		}
		<?php } ?>

		<?php if ( IBCustomizer::get_mod('gfs_input_placeholder') == 'yes' ) { ?>
		div.gform_wrapper .gfield input::-webkit-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])::-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):-ms-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield textarea::-webkit-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield textarea:-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield textarea::-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield textarea:-ms-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield select::-webkit-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield select:-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield select::-moz-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		div.gform_wrapper .gfield select:-ms-input-placeholder {
		    color: transparent;
		    opacity: 0;
		}
		<?php } ?>

		<?php if ( IBCustomizer::get_mod('gfs_input_focus_color') ) { ?>
		div.gform_wrapper .gfield input:not([type='radio']):not([type='checkbox']):not([type='submit']):not([type='button']):not([type='image']):not([type='file']):focus,
		div.gform_wrapper .gfield select:focus,
		div.gform_wrapper .gfield textarea:focus {
		    border-color: <?php echo IBCustomizer::get_mod('gfs_input_focus_color'); ?>;
		}
		<?php } ?>

		div.gform_wrapper .top_label input.medium,
		div.gform_wrapper .top_label select.medium {
			width: <?php echo (IBCustomizer::get_mod('gfs_input_width') == '100%') ? '100%' : '49%'; ?>;
		}

		div.gform_wrapper .gfield .gfield_description {
		    <?php if ( IBCustomizer::get_mod('gfs_input_desc_font_size') ) { ?>
		    font-size: <?php echo IBCustomizer::get_mod('gfs_input_desc_font_size'); ?>px;
		    <?php } ?>
		    <?php if ( IBCustomizer::get_mod('gfs_input_desc_color') ) { ?>
		    color: <?php echo IBCustomizer::get_mod('gfs_input_desc_color'); ?>;
		    <?php } ?>
		    <?php if ( IBCustomizer::get_mod('gfs_input_desc_line_height') ) { ?>
		    line-height: <?php echo IBCustomizer::get_mod('gfs_input_desc_line_height'); ?>;
		    <?php } ?>
		}

		div.gform_wrapper ul.gform_fields li.gfield:not(.gf_left_third):not(.gf_middle_third)::not(.gf_right_third) {
			margin-bottom: 0;
			padding-right: 0;
			<?php if ( IBCustomizer::get_mod('gfs_input_margin') >= 0 ) { ?>
			margin-top: <?php echo IBCustomizer::get_mod('gfs_input_margin'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper .gform_footer,
		div.gform_wrapper .gform_page_footer {
			text-align: <?php echo IBCustomizer::get_mod('gfs_button_alignment'); ?>;
		}
		div.gform_wrapper .gform_footer .gform_button,
		div.gform_wrapper .gform_page_footer .button {
			<?php if ( IBCustomizer::get_mod('gfs_button_width') ) { ?>
				width: <?php echo IBCustomizer::get_mod('gfs_button_width'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_width') == '100%' ) { ?>
				margin-bottom: 5px !important;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_button_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_font_size') ) { ?>
			font-size: <?php echo IBCustomizer::get_mod('gfs_button_font_size'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_text_transform') ) { ?>
			text-transform: <?php echo IBCustomizer::get_mod('gfs_button_text_transform'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_letter_spacing') ) { ?>
			letter-spacing: <?php echo IBCustomizer::get_mod('gfs_button_letter_spacing'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_bg_color') ) { ?>
			background-color: <?php echo IBCustomizer::get_mod('gfs_button_bg_color'); ?>;
			<?php } ?>
			<?php if ( isset( $button_padding['top'] ) && $button_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $button_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['bottom'] ) && $button_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $button_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['left'] ) && $button_padding['left'] >= 0 ) { ?>
			padding-left: <?php echo $button_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['right'] ) && $button_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $button_padding['right']; ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_border_width') >= 0 ) { ?>
			border-width: <?php echo IBCustomizer::get_mod('gfs_button_border_width'); ?>px;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_button_border_color'); ?>;
			<?php } ?>
			border-style: solid;
			<?php if ( IBCustomizer::get_mod('gfs_button_border_radius') >= 0 ) { ?>
			border-radius: <?php echo IBCustomizer::get_mod('gfs_button_border_radius'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper.gf_browser_ie .gform_footer .gform_button,
		div.gform_wrapper.gf_browser_ie .gform_page_footer .button {
			<?php if ( isset( $button_padding['top'] ) && $button_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $button_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['bottom'] ) && $button_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $button_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['left'] ) && $button_padding['left'] >= 0 ) { ?>
			padding-left: <?php echo $button_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $button_padding['right'] ) && $button_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $button_padding['right']; ?>px;
			<?php } ?>
		}

		div.gform_wrapper .gform_footer input[type=submit]:last-child,
		div.gform_wrapper .gform_page_footer input[type=button]:last-child,
		div.gform_wrapper .gform_page_footer input[type=submit]:last-child {
			margin-bottom: 0 !important;
		}

		div.gform_wrapper .gform_footer .gform_button:hover, 
		div.gform_wrapper .gform_page_footer .button:hover {
			<?php if ( IBCustomizer::get_mod('gfs_button_hover_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_button_hover_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_button_bg_hover_color') ) { ?>
			background-color: <?php echo IBCustomizer::get_mod('gfs_button_bg_hover_color'); ?>;
			<?php } ?>
		}

		<?php if ( 'yes' == IBCustomizer::get_mod('gfs_radio_cb_style') ) : ?>
			/* Radio & Checkbox */
			div.gform_wrapper ul.gfield_radio li input[type=radio],
			div.gform_wrapper ul.gfield_checkbox li input[type=checkbox],
			div.gform_wrapper ul.gfield_radio li input[type=radio]:focus,
			div.gform_wrapper ul.gfield_checkbox li input[type=checkbox]:focus {
				-webkit-appearance: none;
				-moz-appearance: none;
				outline: none;
				margin-top: 0 !important;
				<?php if ( IBCustomizer::get_mod('gfs_radio_cb_size') >= 0 ) : ?>
					width: <?php echo IBCustomizer::get_mod('gfs_radio_cb_size'); ?>px !important;
					height: <?php echo IBCustomizer::get_mod('gfs_radio_cb_size'); ?>px !important;
				<?php endif; ?>
				<?php if ( ! empty( IBCustomizer::get_mod('gfs_radio_cb_color') ) ) : ?>
					background: <?php echo IBCustomizer::get_mod('gfs_radio_cb_color'); ?>;
					background-color: <?php echo IBCustomizer::get_mod('gfs_radio_cb_color'); ?>;
				<?php endif; ?>
				<?php if ( IBCustomizer::get_mod('gfs_radio_cb_border_width') >= 0 && ! empty( IBCustomizer::get_mod('gfs_radio_cb_border_color') ) ) : ?>
					border: <?php echo IBCustomizer::get_mod('gfs_radio_cb_border_width'); ?>px solid <?php echo IBCustomizer::get_mod('gfs_radio_cb_border_color'); ?>;
				<?php endif; ?>
				padding: 2px;
			}
			div.gform_wrapper .gfield_radio li input[type=radio],
			div.gform_wrapper .gfield_radio li input[type=radio]:focus,
			div.gform_wrapper .gfield_radio li input[type=radio]:before,
			div.gform_wrapper .gfield_radio li input[type=radio]:focus:before {
				<?php if ( IBCustomizer::get_mod('gfs_radio_cb_radius') >= 0 ) : ?>
					border-radius: <?php echo IBCustomizer::get_mod('gfs_radio_cb_radius'); ?>px;
				<?php endif; ?>
			}
			div.gform_wrapper .gfield_checkbox li input[type=checkbox],
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:before,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus:before {
				<?php if ( IBCustomizer::get_mod('gfs_radio_cb_checkbox_radius') >= 0 ) : ?>
					border-radius: <?php echo IBCustomizer::get_mod('gfs_radio_cb_checkbox_radius'); ?>px;
				<?php endif; ?>
			}
			div.gform_wrapper .gfield_radio li input[type=radio]:before,
			div.gform_wrapper .gfield_radio li input[type=radio]:focus:before,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:before,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus:before {
				content: "";
				width: 100%;
				height: 100%;
				padding: 0;
				margin: 0;
				display: block;
			}
			div.gform_wrapper .gfield_radio li input[type=radio]:checked:before,
			div.gform_wrapper .gfield_radio li input[type=radio]:focus:checked:before,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:checked:before,
			div.gform_wrapper .gfield_checkbox li input[type=checkbox]:focus:checked:before {
				<?php if ( ! empty( IBCustomizer::get_mod('gfs_radio_cb_checked_color') ) ) : ?>
					background: <?php echo IBCustomizer::get_mod('gfs_radio_cb_checked_color'); ?>;
					background-color: <?php echo IBCustomizer::get_mod('gfs_radio_cb_checked_color'); ?>;
				<?php endif; ?>
			}
		<?php endif; ?>

		<?php if ( '' != IBCustomizer::get_mod('gfs_radio_cb_label_size') ) : ?>
			div.gform_wrapper .gfield_radio li label,
			div.gform_wrapper .gfield_checkbox li label {
				font-size: <?php echo IBCustomizer::get_mod('gfs_radio_cb_label_size'); ?>px;
			}
		<?php endif; ?>

		/* File Upload */
		div.gform_wrapper .gfield input[type=file] {
			background-color: <?php echo IBCustomizer::get_mod('gfs_file_bg_color') ? IBCustomizer::get_mod('gfs_file_bg_color') : 'transparent'; ?>;
			<?php if ( IBCustomizer::get_mod('gfs_file_text_color') ) { ?>color: <?php echo IBCustomizer::get_mod('gfs_file_text_color'); ?>;<?php } ?>
			border-width: <?php echo ( IBCustomizer::get_mod('gfs_file_border_width') !== '' ) ? IBCustomizer::get_mod('gfs_file_border_width') : 0; ?>px;
			<?php if ( IBCustomizer::get_mod('gfs_file_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_file_border_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_file_border_style') ) { ?>
			border-style: <?php echo IBCustomizer::get_mod('gfs_file_border_style'); ?>;
			<?php } ?>
			<?php if ( isset( $file_padding['top'] ) && $file_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $file_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $file_padding['bottom'] ) && $file_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $file_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $file_padding['left'] ) && $file_padding['left'] >= 0 ) { ?>
			padding-left: <?php echo $file_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $file_padding['right'] ) && $file_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $file_padding['right']; ?>px;
			<?php } ?>
		}

		div.gform_wrapper div.validation_error {
			<?php if ( IBCustomizer::get_mod('gfs_error_validation') ) { ?>
				display: <?php echo IBCustomizer::get_mod('gfs_error_validation'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_error_description_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_error_description_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_error_border_color') ) { ?>
			border-top-color: <?php echo IBCustomizer::get_mod('gfs_error_border_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_error_border_color') ) { ?>
			border-bottom-color: <?php echo IBCustomizer::get_mod('gfs_error_border_color'); ?>;
			<?php } ?>
		}

		div.gform_wrapper li.gfield.gfield_error {
			background-color: <?php echo ( IBCustomizer::get_mod( 'gfs_error_field_bg_color' ) ) ? IBCustomizer::get_mod( 'gfs_error_field_bg_color' ) : 'transparent'; ?>;
		}

		div.gform_wrapper .gfield_error .gfield_label {
			<?php if ( IBCustomizer::get_mod('gfs_error_field_label_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_error_field_label_color'); ?>;
			<?php } ?>
		}

		div.gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]),
		div.gform_wrapper li.gfield_error textarea {
			<?php if ( IBCustomizer::get_mod('gfs_error_field_input_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_error_field_input_border_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_error_field_input_border_width') >= 0 ) { ?>
			border-width: <?php echo IBCustomizer::get_mod('gfs_error_field_input_border_width'); ?>px;
			<?php } ?>
		}

		div.gform_wrapper .validation_message {
			<?php if ( IBCustomizer::get_mod('gfs_error_field_message') ) { ?>
				display: <?php echo IBCustomizer::get_mod('gfs_error_field_message'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_error_field_message_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_error_field_message_color'); ?>;
			<?php } ?>
		}

		/* Success Message */
		div.gform_confirmation_wrapper {
			background-color: <?php echo IBCustomizer::get_mod('gfs_success_msg_bg_color') ? IBCustomizer::get_mod('gfs_success_msg_bg_color') : 'transparent'; ?>;
			border-width: <?php echo ( IBCustomizer::get_mod('gfs_success_msg_border_width') !== '' ) ? IBCustomizer::get_mod('gfs_success_msg_border_width') : 0; ?>px;
			<?php if ( IBCustomizer::get_mod('gfs_success_msg_border_color') ) { ?>
			border-color: <?php echo IBCustomizer::get_mod('gfs_success_msg_border_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_success_msg_border_style') ) { ?>
			border-style: <?php echo IBCustomizer::get_mod('gfs_success_msg_border_style'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_success_msg_border_radius') >= 0 ) { ?>
			border-radius: <?php echo IBCustomizer::get_mod('gfs_success_msg_border_radius'); ?>px;
			<?php } ?>
			<?php if ( isset( $success_msg_padding['top'] ) && $success_msg_padding['top'] >= 0 ) { ?>
			padding-top: <?php echo $success_msg_padding['top']; ?>px;
			<?php } ?>
			<?php if ( isset( $success_msg_padding['bottom'] ) && $success_msg_padding['bottom'] >= 0 ) { ?>
			padding-bottom: <?php echo $success_msg_padding['bottom']; ?>px;
			<?php } ?>
			<?php if ( isset( $success_msg_padding['left'] ) && $success_msg_padding['left'] >= 0 ) { ?>
			padding-left: <?php echo $success_msg_padding['left']; ?>px;
			<?php } ?>
			<?php if ( isset( $success_msg_padding['right'] ) && $success_msg_padding['right'] >= 0 ) { ?>
			padding-right: <?php echo $success_msg_padding['right']; ?>px;
			<?php } ?>
		}
		div.gform_confirmation_wrapper .gform_confirmation_message {
			<?php if ( IBCustomizer::get_mod('gfs_success_msg_text_color') ) { ?>
			color: <?php echo IBCustomizer::get_mod('gfs_success_msg_text_color'); ?>;
			<?php } ?>
			<?php if ( IBCustomizer::get_mod('gfs_success_msg_font_size') ) { ?>
		    font-size: <?php echo IBCustomizer::get_mod('gfs_success_msg_font_size'); ?>px;
		    <?php } ?>
		    <?php if ( IBCustomizer::get_mod('gfs_success_msg_line_height') ) { ?>
		    line-height: <?php echo IBCustomizer::get_mod('gfs_success_msg_line_height'); ?>;
		    <?php } ?>
		}

	</style>
	<?php
}

function gfs_sanitize_integer( $input ) {
    if ( is_numeric( $input ) ) {
        return intval( $input );
    }
}

function gfs_sanitize_float( $input ) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

function gfs_hex2rgba($hex, $opacity) {
   $hex = str_replace("#", "", $hex);

   if (strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgba = array($r, $g, $b, $opacity);
   return 'rgba(' . implode(",", $rgba) . ')'; // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}
