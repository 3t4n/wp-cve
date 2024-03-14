<?php
namespace Enteraddons\Widgets\Image_Hotspot;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Image Hotspot widget.
 *
 * @since 1.0
 */

class Image_Hotspot extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-image-hotspot';
	}

	public function get_title() {
		return esc_html__( 'Image Hotspot', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-image-hotspot';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Image Hotspot content ------------------------------
        $this->start_controls_section(
            'enteraddons_image_hotspot_content_settings',
            [
                'label' => esc_html__( 'Image Hotspot Content', 'enteraddons' ),
            ]
        );
        $repeater = new \Elementor\Repeater();

        $this->add_control(
            'background_img', [
                'label' => esc_html__( 'Background Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
			'tooltip_type',
			[
				'label' => esc_html__( 'Tooltip Type', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'hover'  => esc_html__( 'Hover', 'enteraddons' ),
					'click' => esc_html__( 'Click', 'enteraddons' ),

				],
			]
		);

        $repeater->add_responsive_control(
            'value_x',
            [
                'label' => esc_html__( 'Horizontal Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'size' => 200,
                ]
            ]
        );

        $repeater->add_responsive_control(
            'value_y',
            [
                'label' => esc_html__( 'Vertical Position', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'size' => 50,
                ]
            ]
        );
        $repeater->add_control(
			'show_image',
			[
				'label' => esc_html__( 'Image Show', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
        $repeater->add_control(
            'img', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    '$show_image' => 'yes',
                ],
            ]
        );
        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'USA', 'enteraddons' )
            ]
        );
        $repeater->add_control(
            'description',
            [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( '24 Cities', 'enteraddons' )
            ]
        );
        $this->add_control(
            'image_hotspot_list',
            [
                'label' => esc_html__( 'Hotspot List', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'value_x' => [
                            'size' => 200,
                            'unit' => 'px',
                        ],
                        'value_y' => [
                            'size' => 50,
                            'unit' => 'px',
                        ],
                        'title'   => esc_html__( 'USA', 'enteraddons' ),
                        'description'   => esc_html__( '24 Cities', 'enteraddons' ),
                        'img'   => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        
                    ],
                    [
                        'value_x' => [
                            'size' => 586,
                            'unit' => 'px',
                        ],
                        'value_y' => [
                            'size' => 326,
                            'unit' => 'px',
                        ],
                        'title'   => esc_html__( 'England', 'enteraddons' ),
                        'description'   => esc_html__( '41 Cities', 'enteraddons' ),
                        
                    ],
                    [
                        'value_x' => [
                            'size' => 1013,
                            'unit' => 'px',
                        ],
                        'value_y' => [
                            'size' => 120,
                            'unit' => 'px',
                        ],
                        'title'   => esc_html__( 'Russia', 'enteraddons' ),
                        'description'   => esc_html__( '47 Cities', 'enteraddons' ),
                        
                    ],
                    [
                        'value_x' => [
                            'size' => 865,
                            'unit' => 'px',
                        ],
                        'value_y' => [
                            'size' => 570,
                            'unit' => 'px',
                        ],
                        'title'   => esc_html__( 'Norway', 'enteraddons' ),
                        'description'   => esc_html__( '17 Cities', 'enteraddons' ),
                        
                    ],
                    
                    
                ]
            ]
        );
        $this->end_controls_section();

         /**
         * Style Tab
         * ------------------------------ Image Hotspot Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_wrapper_style_settings', [
                'label' => esc_html__( ' Wrapper', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_wrapper_width',
            [
                'label' => esc_html__( 'Wrapper Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_wrapper_height',
            [
                'label' => esc_html__( 'Wrapper Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot img' => 'height: {{SIZE}}{{UNIT}};',
                    
                ],
            ]
             );    
            $this->add_responsive_control(
                'image_hotspot_wrapper_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_wrapper_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                 \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'image_hotspot_wrapper_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-image-hotspot img',
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_wrapper_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_hotspot_wrapper_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-image-hotspot img',
                ]
            );
        $this->end_controls_section();

          /**
         * Style Tab
         * ------------------------------ Image Hotspot Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_style_settings', [
                'label' => esc_html__( 'Hotspot Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_width',
            [
                'label' => esc_html__( 'Hotspot Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_height',
            [
                'label' => esc_html__( 'Hotspot Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot' => 'height: {{SIZE}}{{UNIT}};',
                    
                ],
            ]
             );    
            $this->add_group_control(
                 \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'image_hotspot_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot',
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'image_hotspot_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot',
                ]
            );
            $this->add_control(
                'image_hotspot_animation_circle',
                [
                    'label' => esc_html__( 'Hotspot Circle Animation Style', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'image_hotspot_animation_width',
                [
                    'label' => esc_html__( 'animation Circle Width', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                        
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-circle' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_animation_height',
                [
                    'label' => esc_html__( 'animation Circle  Height', 'enteraddons' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-circle' => 'height: {{SIZE}}{{UNIT}};',
                        
                    ],
                ]
                 );    
                $this->add_group_control(
                     \Elementor\Group_Control_Border::get_type(),
                    [
                        'name'      => 'image_hotspot_Animation_border',
                        'label'     => esc_html__( 'Border', 'enteraddons' ),
                        'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-circle',
                    ]
                );
        $this->end_controls_section();

         /**
         * Style Tab
         * ------------------------------ Image Hotspot Tooltip Area  Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_tooltip_area_style_settings', [
                'label' => esc_html__( 'Tooltip Area Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_tooltip_width',
            [
                'label' => esc_html__( 'Tooltip Area Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_tooltip_height',
            [
                'label' => esc_html__( 'Tooltip Area Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip' => 'height: {{SIZE}}{{UNIT}};',
                    
                ],
            ]
             );  
             $this->add_responsive_control(
                'image_hotspot_offset_x',
                [
                    'label'      => __('Offset X', 'enteraddons'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -1000,
                            'max'  => 1000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default'    => [
                        'unit' => 'px',
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_offset_y',
                [
                    'label'      => __('Offset Y', 'enteraddons'),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range'      => [
                        'px' => [
                            'min'  => -1000,
                            'max'  => 1000,
                            'step' => 5,
                        ],
                        '%'  => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default'    => [
                        'unit' => 'px',
                    ],
                    'selectors'  => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                 \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'image_hotspot_tooltip_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip',
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_tooltip_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_hotspot_tooltip_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip',
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'image_hotspot_tooltip_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip',
                ]
            );
        $this->end_controls_section();

         /**
         * Style Tab
         * ------------------------------ Image Hotspot Tooltip Image  Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_tooltip_image_style_settings', [
                'label' => esc_html__( 'Tooltip Image', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_tooltip_image_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_hotspot_tooltip_image_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img' => 'height: {{SIZE}}{{UNIT}};',
                    
                ],
            ]
             );    
            $this->add_responsive_control(
                'image_hotspot_tooltip_image_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_tooltip_image_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                 \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'image_hotspot_tooltip_image_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img',
                ]
            );
            $this->add_responsive_control(
                'image_hotspot_tooltip_image_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_hotspot_tooltip_image_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip .ea-img-row img',
                ]
            );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Image Hotspot Title Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_title_style', [
                'label' => esc_html__( 'Tooltip Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4',
            ]
        );
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
           [
               'name'      => 'image_hotspot_title_border',
               'label'     => esc_html__( 'Border', 'enteraddons' ),
               'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4',
           ]
       );
       $this->add_responsive_control(
           'image_hotspot_title_radius',
           [
               'label' => esc_html__( 'Border Radius', 'enteraddons' ),
               'type' => Controls_Manager::DIMENSIONS,
               'devices' => [ 'desktop', 'tablet', 'mobile' ],
               'size_units' => [ 'px', '%', 'em' ],
               'selectors' => [
                   '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                   
               ],
           ]
       );
        $this->add_responsive_control(
            'image_hotspot_title_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4' => 'text-align: {{VALUE}} !important',
                    
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip h4',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Image Hotspot Description Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_image_hotspot_description_style', [
                'label' => esc_html__( 'Tooltip Description', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p',
            ]
        );
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
           [
               'name'      => 'image_hotspot_description_border',
               'label'     => esc_html__( 'Border', 'enteraddons' ),
               'selector'  => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p',
           ]
       );
       $this->add_responsive_control(
           'image_hotspot_description_radius',
           [
               'label' => esc_html__( 'Border Radius', 'enteraddons' ),
               'type' => Controls_Manager::DIMENSIONS,
               'devices' => [ 'desktop', 'tablet', 'mobile' ],
               'size_units' => [ 'px', '%', 'em' ],
               'selectors' => [
                   '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                   
               ],
           ]
       );
        $this->add_responsive_control(
            'image_hotspot_description_align',
            [
                'label' => esc_html__( 'Alignment', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'enteraddons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'enteraddons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'enteraddons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p' => 'text-align: {{VALUE}} !important',
                    
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'description_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-image-hotspot .ea-hot-spot .ea-tooltip p',
            ]
        );
        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        //  template render
        $obj = new Image_Hotspot_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    public function get_script_depends() {
        return [ 'image-hotspot', 'enteraddons-main' ];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
