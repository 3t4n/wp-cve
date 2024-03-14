<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class HTMega_Elementor_Widget_Scroll_Image extends Widget_Base {

    public function get_name() {
        return 'htmega-scrollimage-addons';
    }
    
    public function get_title() {
        return __( 'Scroll Image', 'htmega-addons' );
    }

    public function get_icon() {
        return 'htmega-icon eicon-exchange';
    }

    public function get_categories() {
        return [ 'htmega-addons' ];
    }

    public function get_keywords() {
        return ['image scroll', 'scroll image','image scrolling', 'single image scrolling', 'ht mega', 'htmega'];
    }

    public function get_help_url() {
        return 'https://wphtmega.com/docs/general-widgets/scroll-image-widget/';
    }

    public function get_style_depends(){
        return [
            'htmega-widgets',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'button_content',
            [
                'label' => __( 'Scroll Image', 'htmega-addons' ),
            ]
        );
            
            $this->add_control(
                'scroll_image',
                [
                    'label' => __( 'Choose Image', 'htmega-addons' ),
                    'type' => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                    'name' => 'scroll_image_size',
                    'default' => 'large',
                    'separator' => 'none',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_inner_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image .thumb',
                ]
            );
   
            $this->add_control(
                'link_type',
                [
                    'label'       => __( 'Link', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => [
                        'none' => __('None', 'htmega-addons'),
                        'url'  => __( 'URL', 'htmega-addons' ),
                        'lightbox' => __( 'Lightbox', 'htmega-addons' ),
                    ],
                    'default'     => 'url',
                    'label_block' => true,
                ]
            );
    
            $this->add_control(
                'scroll_image_link',
                [
                    'label' => __( 'Custom Link', 'htmega-addons' ),
                    'show_label' => false,
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://example.com/', 'htmega-addons' ),
                    'dynamic' => [
                        'active' => true,
                    ],
                    'condition'   => array(
                        'link_type'     => 'url',
                    ),
                ]
            );
            $this->add_control(
                'lightbox_required',
                [
                    'label' => __( 'Lightbox Note', 'htmega-addons'),
                    'show_label' => false,
                    'type' => \Elementor\Controls_Manager::RAW_HTML,
                    'raw' => __( "Ensure Elementor's Lightbox option is turned on.", 'htmega-addons' ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'condition' => [
                        'link_type' => 'lightbox',
                    ],
                ]
            );
            $this->add_responsive_control(
                'scroll_image_height',
                [
                    'label' => __( 'Container Height', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'step' => 1,
                            'max'=>5000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 600,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image .thumb,
                        {{WRAPPER}} .ht-scroll-type-vertical .ht-scroll-image-wrapper,
                        {{WRAPPER}} .ht-scroll-type-horizontal .ht-scroll-image-wrapper' => 'min-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'after',   
                ]
            );        
			$this->add_control(
				'image_height_width_notice',
				[
					'raw'             => __( "Ensure that the container's dimensions (height and width) are smaller than the image's actual dimensions (height and width) otherwise, scrolling will not function properly", 'htmega-addons' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				]
			);
            $this->add_control(
                'htmega_enable_mask_image',
                array(
                    'label'     => esc_html__( 'Mask Image', 'htmega-addons' ),
                    'type'      => Controls_Manager::SWITCHER,
                )
            );
    
            $this->add_control(
                'htmega_mask_shape',
                [
                    'label'       => __( 'Shape', 'htmega-addons' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => [
                        'circle' => esc_html__( 'Circle', 'htmega-addons' ),
                        'flower' => esc_html__( 'Flower', 'htmega-addons' ),
                        'sketch' => esc_html__( 'Sketch', 'htmega-addons' ),
                        'triangle' => esc_html__( 'Triangle', 'htmega-addons' ),
                        'blob' => esc_html__( 'Blob', 'htmega-addons' ),
                        'hexagon' => esc_html__( 'Hexagon', 'htmega-addons' ),
                        'custom' => esc_html__( 'Custom (Pro)', 'htmega-addons' ),
                    ],
                    'default'     => 'circle',
                    'label_block' => true,
                    'condition'   => array(
                        'htmega_enable_mask_image' => 'yes',
                    ),
                ]
            );
            htmega_pro_notice( $this,'htmega_mask_shape', 'custom', Controls_Manager::RAW_HTML );
    
            $this->add_control(
                'mask_size',
                array(
                    'label'     => __( 'Mask Size', 'htmega-addons' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => array(
                        'contain' => __( 'Contain', 'htmega-addons' ),
                        'cover'   => __( 'Cover', 'htmega-addons' ),
                        'custom'   => __( 'Custom (Pro)', 'htmega-addons' ),
                    ),
                    'default'   => 'contain',
                    'selectors' => array(
                        '{{WRAPPER}} .ht-scroll-image .thumb' => 'mask-size: {{VALUE}};-webkit-mask-size: {{VALUE}};',
                    ),
                    'condition' => array(
                        'htmega_enable_mask_image' => 'yes',
                    ),
                )
            );

            htmega_pro_notice( $this,'mask_size', 'custom', Controls_Manager::RAW_HTML );

            $this->add_control(
                'mask_position_cover',
                array(
                    'label'     => __( 'Mask Position', 'htmega-addons' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => array(
                        'center center' => __( 'Center Center', 'htmega-addons' ),
                        'center left'   => __( 'Center Left', 'htmega-addons' ),
                        'center right'  => __( 'Center Right', 'htmega-addons' ),
                        'top center'    => __( 'Top Center', 'htmega-addons' ),
                        'top left'      => __( 'Top Left', 'htmega-addons' ),
                        'top right'     => __( 'Top Right', 'htmega-addons' ),
                        'bottom center' => __( 'Bottom Center', 'htmega-addons' ),
                        'bottom left'   => __( 'Bottom Left', 'htmega-addons' ),
                        'bottom right'  => __( 'Bottom Right', 'htmega-addons' ),
                    ),
                    'default'   => 'center center',
                    'selectors' => array(
                        '{{WRAPPER}} .ht-scroll-image .thumb' => 'mask-position: {{VALUE}}; -webkit-mask-position: {{VALUE}}',
                    ),
                    'condition' => array(
                        'htmega_enable_mask_image' => 'yes',
                        'mask_size'                  => 'cover',
                    ),
                )
            );
    
            $this->add_control(
                'mask_position_contain',
                array(
                    'label'     => __( 'Mask Position', 'htmega-addons' ),
                    'type'      => Controls_Manager::SELECT,
                    'options'   => array(
                        'center center' => __( 'Center Center', 'htmega-addons' ),
                        'top center'    => __( 'Top Center', 'htmega-addons' ),
                        'bottom center' => __( 'Bottom Center', 'htmega-addons' ),
                    ),
                    'default'   => 'center center',
                    'selectors' => array(
                        '{{WRAPPER}} .ht-scroll-image .thumb' => 'mask-position: {{VALUE}}; -webkit-mask-position: {{VALUE}}',
                    ),
                    'condition' => array(
                        'htmega_enable_mask_image' => 'yes',
                        'mask_size'                  => 'contain',
                    ),
                )
            );
            $this->add_control(
                'htmega_enable_overlay',
                    array(
                        'label'     => esc_html__( 'Overlay', 'htmega-addons' ),
                        'type'      => Controls_Manager::SWITCHER,
                    )
            );
        $this->end_controls_section();
        // Scrolling settings
        $this->start_controls_section(
            'scrolling_settings',
            [
                'label' => __( 'Settings', 'htmega-addons' ),
            ]
        );
        $this->add_control(
            'show_badge',
            [
                'label' => __( 'Show Badge', 'htmega-addons' ),
                'type'  => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'htmega-addons' ),
                'label_off' => __( 'Hide', 'htmega-addons' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __( 'Badge Text', 'htmega-addons' ),
                'show_label' => false,
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Badge Text', 'htmega-addons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_badge' => 'yes',
                ]
            ]
        );        
        $this->add_control(
            'badge_position',
            [
                'label' => __( 'Position', 'htmega-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'htmega-addons'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'htmega-addons'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => false,
                'selectors_dictionary' => [
                    'left' => 'left:0 !important;right:auto !important',
                    'right' =>'right:0px !important;left:auto !important'
                ],
				'selectors' => [
                    '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => '{{VALUE}}',
                ],
                'condition' => [
                    'show_badge' => 'yes',
                    'badge_text!' => '',
                ],
            ]
        );
        $this->add_control(
			'trigger_type',
            [
                'label'         => __( 'Trigger On', 'htmega-addons' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'hover'   => __( 'Hover', 'htmega-addons' ),
                    'scroll'  => __( 'Scroll (Pro)', 'htmega-addons' ),
                ],
                'default'       => 'hover',
				'separator' => 'before',
            ]
        );

        htmega_pro_notice( $this,'trigger_type', 'scroll', Controls_Manager::RAW_HTML );

        $this->add_control(
			'scroll_type_p',
			[
				'label' => __( 'Scroll Type', 'htmega-addons' ) . ' <i class="eicon-pro-icon"></i>',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'vertical' => [
						'title' => __( 'Vertical', 'htmega-addons' ),
						'icon' => 'eicon-navigation-vertical',
					],
					'horizontal' => [
						'title' => __( 'Horizontal', 'htmega-addons' ),
						'icon' => 'eicon-navigation-horizontal',
					],
				],
				'default' => 'vertical',
				'toggle' => false,
                'classes' => 'htmega-disable-control',
			]
		);

		$this->add_control(
			'vtr_direction',
			[
				'label' => __( 'Direction', 'htmega-addons' ) . ' <i class="eicon-pro-icon"></i>',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'htmega-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'htmega-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false,
				'condition' => [
					'trigger_type' => 'hover',
					'scroll_type_p' => 'vertical',
				],
                'classes' => 'htmega-disable-control',
			]
		);


        $this->add_control(
            'speed_scroll_time',
            [
                'label'			=> __( 'Speed', 'htmega-addons' ),
                'type'			=> Controls_Manager::NUMBER,
                'min' => 0,
                'default'		=> 3,
                'selectors' => [
                    '{{WRAPPER}} .ht-scroll-image .thumb'   => 'transition-duration: {{Value}}s',
                ]
            ]
        );    

        $this->end_controls_section();
        // Style tab section
        $this->start_controls_section(
            'scroll_image_style_section',
            [
                'label' => __( 'Scroll Image Style', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'scroll_image_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_image_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'scroll_image_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_image_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'scroll_image_box_shadow',
                    'label' => __( 'Box Shadow', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image',
                ]
            );
            $this->add_control(
                'scroll_image_overlay_color',
                [
                    'label' => __( 'Overlay Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image-overlay' => 'background: {{VALUE}};',
                    ],
                    'condition'=> [
                        'htmega_enable_overlay' => 'yes'
                    ]
                ]
            );
        $this->end_controls_section();

        // Style tab section
        $this->start_controls_section(
            'scroll_badge_style_section',
            [
                'label' => __( 'Badge', 'htmega-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'=>[
                    'show_badge' => 'yes',
                ]
            ]
        );

            $this->add_control(
                'scroll_badge_offset_toggle',
                [
                    'label' => __( 'Offset', 'htmega-addons' ),
                    'type' => Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => __( 'None', 'htmega-addons' ),
                    'label_on' => __( 'Custom', 'htmega-addons' ),
                    'return_value' => 'yes',
                    'condition'=>[
                        'show_badge' => 'yes',
                    ],
                ]
            );
    
            $this->start_popover();
    
            $this->add_responsive_control(
                'scroll_badge_left_right',
                [
                    'label' => __( 'Left', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'step' => 1,
                            'max'=> 1000,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'left: {{SIZE}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'badge_position' => 'left',
                    ],

                ]
            );  
            $this->add_responsive_control(
                'scroll_badge_right',
                [
                    'label' => __( 'Right', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -200,
                            'step' => 1,
                            'max'=> 200,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'right: {{SIZE}}{{UNIT}}!important;',
                    ],
                    'condition'=>[
                        'badge_position' => 'right',
                    ],
                ]
            );  

            $this->add_responsive_control(
                'scroll_badge_top_bottom',
                [
                    'label' => __( 'Top-Bottom', 'htmega-addons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1000,
                            'step' => 1,
                            'max'=> 1000,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'top: {{SIZE}}{{UNIT}};',
                    ], 
                ]
            );

            $this->end_popover();            
                
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'scroll_badge_background',
                    'label' => __( 'Background', 'htmega-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                    'condition'=>[
                        'show_badge' => 'yes',
                    ]
                ]
            );

            $this->add_control(
                'scroll_badge_text_color',
                [
                    'label' => __( 'Color', 'htmega-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'color: {{VALUE}};',
                    ],
                    'condition'=>[
                        'show_badge' => 'yes',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'scroll_badge_border',
                    'label' => __( 'Border', 'htmega-addons' ),
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_margin',
                [
                    'label' => __( 'Margin', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_responsive_control(
                'scroll_badge_padding',
                [
                    'label' => __( 'Padding', 'htmega-addons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ht-scroll-image span.htmega-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' =>'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'scroll_badge_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'scroll_badge_typography',
                    'label' => __( 'Typography', 'htmega-addons' ),
                    'exclude' => [
                        'line_height'
                    ],
                    'default' => [
                        'font_size' => ['']
                    ],
                    'selector' => '{{WRAPPER}} .ht-scroll-image span.htmega-badge',
                ]
            );            

        $this->end_controls_section();
    }

    protected function render( $instance = [] ) {

        $settings   = $this->get_settings_for_display();
        $scroll_image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['scroll_image']['id'], 'scroll_image_size', $settings);

        if ( !$scroll_image_url ) {
            $scroll_image_url = $settings['scroll_image']['url'];
        }
        if ( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ) {
            $this->add_render_attribute('htbadge','class','htmega-badge');
        }

        $this->add_render_attribute('scrollimage_wrapper', 'class', 'ht-scroll-image');

        $this->add_render_attribute( 'scrollimage', 'class', 'thumb' );
        $this->add_render_attribute('scrollimage', 'style', 'background-image: url('.esc_url( $scroll_image_url ).');');

        if ( 'yes' == $settings['htmega_enable_mask_image'] &&  'custom' !== $settings['htmega_mask_shape'] ) {
            $this->add_render_attribute('scrollimage', 'style', 'mask-image: url('.ELEMENTOR_ASSETS_URL . 'mask-shapes/'.$settings['htmega_mask_shape'].'.svg'.'); -webkit-mask-image: url('.esc_url( ELEMENTOR_ASSETS_URL . 'mask-shapes/'.$settings['htmega_mask_shape'].'.svg' ).');');
        }

        // Link
        if ( isset( $settings['scroll_image_link'] ) ) {
            $this->add_link_attributes( 'url', $settings['scroll_image_link'] );

        }
		if ( 'lightbox' === $settings['link_type'] ) {
			$this->add_render_attribute( 'url', 'href', esc_url( $scroll_image_url ) );

		}
        if( !empty($settings['scroll_image_link']['url']) || 'lightbox' === $settings['link_type'] || 'link' === $settings['link_type'] ) { ?>
            <a <?php echo $this->get_render_attribute_string( 'url' ); ?> >
                <div <?php echo $this->get_render_attribute_string( 'scrollimage_wrapper' ); ?>>
                    <?php if( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ): ?>
                        <span <?php $this->print_render_attribute_string('htbadge'); ?>>
                            <?php echo wp_kses_post($settings['badge_text']);?>
                        </span>
                    <?php endif; ?>
                    <div class="ht-scroll-image-wrapper">
                        <div <?php echo $this->get_render_attribute_string( 'scrollimage' ); ?> >
                        <?php
                            if ( 'yes' == $settings['htmega_enable_overlay'] ) {
                                echo '<div class="ht-scroll-image-overlay"></div>';
                            } ?>
                        </div>
                    </div>
                </div>                        
            </a>                    
        <?php } else{ ?>
            <div <?php echo $this->get_render_attribute_string( 'scrollimage_wrapper' ); ?> >
                <?php if( 'yes'=== $settings['show_badge'] && $settings['badge_text'] ): ?>
                    <span <?php $this->print_render_attribute_string('htbadge'); ?>>
                        <?php echo wp_kses_post( $settings['badge_text'] );?>
                    </span>
                <?php endif; ?>
                <div class="ht-scroll-image-wrapper">
                    <div <?php echo $this->get_render_attribute_string( 'scrollimage' ); ?> >
                        <?php
                        if ( 'yes' == $settings['htmega_enable_overlay'] ) {
                            echo '<div class="ht-scroll-image-overlay"></div>';
                        } ?>
                    </div>
                </div>
            </div>
        <?php } 
    }
    
}