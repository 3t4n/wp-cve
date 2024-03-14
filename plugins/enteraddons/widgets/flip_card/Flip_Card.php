<?php
namespace Enteraddons\Widgets\Flip_Card;

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
 * Enteraddons elementor widget.
 *
 * @since 1.0
 */

class Flip_Card extends Widget_Base {

	public function get_name() {
		return 'enteraddons-flip-card';
	}

	public function get_title() {
		return esc_html__( 'Flip Card', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-flip-card';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}
    
	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Flip Front content ------------------------------
        $this->start_controls_section(
            'enteraddons_flip_front_content',
            [
                'label' => esc_html__( 'Flip Front Content', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'front_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Flip Card', 'enteraddons' )
            ]
        );
        $this->add_control(
            'front_desc',
            [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet', 'enteraddons' )
            ]
        );
        $this->add_control(
            'front_icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'enteraddons' ),
                    'img'  => esc_html__( 'Image', 'enteraddons' ),
                ],
            ]
        );
        $this->add_control(
            'front_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'front_icon_type' => 'icon' ],
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'front_image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [ 'front_icon_type' => 'img' ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->end_controls_section(); // End content
        // ---------------------------------------- Flip Back ------------------------------
        $this->start_controls_section(
            'enteraddons_flip_back_content',
            [
                'label' => esc_html__( 'Flip Back Content', 'enteraddons' ),
            ]
        );
        
        $this->add_control(
            'back_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Flip Card', 'enteraddons' )
            ]
        );
        $this->add_control(
            'back_desc',
            [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', 'enteraddons' )
            ]
        );
        $this->add_control(
            'back_icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => esc_html__( 'Icon', 'enteraddons' ),
                    'img'  => esc_html__( 'Image', 'enteraddons' ),
                ],
            ]
        );
        $this->add_control(
            'back_icon',
            [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'condition' => [ 'back_icon_type' => 'icon' ],
                'default' => [
                    'value' => '',
                    'library' => 'solid',
                ],
            ]
        );
        $this->add_control(
            'back_image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [ 'back_icon_type' => 'img' ],
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'btn_text',
            [
                'label' => esc_html__( 'Button Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__( 'Read More', 'enteraddons' )
            ]
        );
        $this->add_control(
            'btn_link',
            [
                'label' => esc_html__( 'Button Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $this->end_controls_section(); // End

        /**
         * Style Tab
         * ------------------------------ Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_flip_wrap_content_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'flip_wrap_width',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'flip_wrap_height',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'flip_wrap_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'flip_wrap_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'flip_wrap_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card',
            ]
        );
        $this->add_responsive_control(
            'flip_wrap_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'flip_wrap_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'flip_wrap_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-wrap .ea-flip-card',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Flip Card Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_flip_card_content_settings', [
                'label' => esc_html__( 'Flip Card Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tab_flip_card' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'flip_card_front',
            [
                'label' => esc_html__( 'Front', 'enteraddons' ),
            ]
        );

            $this->add_responsive_control(
                'flip_front_horizontal_alignment',
                [
                    'label' => esc_html__( 'Horizontal Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-front' => 'align-items: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'flip_front_vertical_alignment',
                [
                    'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-front' => 'justify-content: {{VALUE}} !important',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'flip_front_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-front' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'flip_front_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'flip_front_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-flip-card-front',
                ]
            );
            $this->add_responsive_control(
                'flip_front_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-front' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'flip_front_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-flip-card-front',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'flip_front_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-flip-card-front',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'flip_card_back',
            [
                'label' => esc_html__( 'Back', 'enteraddons' ),
            ]
        );
            
            $this->add_responsive_control(
                'flip_back_horizontal_alignment',
                [
                    'label' => esc_html__( 'Horizontal Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'align-items: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'flip_back_vertical_alignment',
                [
                    'label' => esc_html__( 'Vertical Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__( 'Top', 'enteraddons' ),
                            'icon' => 'eicon-v-align-top',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-v-align-middle',
                        ],
                        'end' => [
                            'title' => esc_html__( 'Bottom', 'enteraddons' ),
                            'icon' => 'eicon-v-align-bottom',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'justify-content: {{VALUE}} !important',
                    ],
                ]
            );
            $this->add_responsive_control(
                'flip_back_text_alignment',
                [
                    'label' => esc_html__( 'Text Alignment', 'enteraddons' ),
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
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );
            
            $this->add_responsive_control(
                'flip_back_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'flip_back_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'flip_back_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-flip-card-back',
                ]
            );
            $this->add_responsive_control(
                'flip_back_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-flip-card-back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'flip_back_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-flip-card-back',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'flip_back_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-flip-card-back',
                ]
            );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        
        /**
         * Style Tab
         * ------------------------------ Title Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_flip_title_settings', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
             
        $this->start_controls_tabs( 'tab_flip_title' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'title_front',
            [
                'label' => esc_html__( 'Front', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'front_title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front h5' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front h5',
            ]
        );
        $this->add_responsive_control(
            'front_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Back
        $this->start_controls_tab(
            'flip_title_back',
            [
                'label' => esc_html__( 'Back', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'back_title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back h5' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-back h5',
            ]
        );
        $this->add_responsive_control(
            'back_title_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back h5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_title_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Descriptions Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_flip_descriptions_settings', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_flip_descriptions' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'descriptions_front',
            [
                'label' => esc_html__( 'Front', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'front_descriptions_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .flip-desc' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'front_descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front .flip-desc',
            ]
        );
        $this->add_responsive_control(
            'front_descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .flip-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .flip-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        //  Controls tab For flip Back
        $this->start_controls_tab(
            'descriptions_back',
            [
                'label' => esc_html__( 'Back', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'back_descriptions_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .flip-desc' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'back_descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-back .flip-desc',
            ]
        );
        $this->add_responsive_control(
            'back_descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .flip-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .flip-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Flip Icon Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_flip_icon_settings', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                //'condition' => [ 'icon_type' => 'img' ],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_flip_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'icon_front',
            [
                'label' => esc_html__( 'Front', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'front_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
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
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'front_icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon',
            ]
        );
        $this->add_responsive_control(
            'front_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon.flip-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'front_icon_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For back
        $this->start_controls_tab(
            'icon_back',
            [
                'label' => esc_html__( 'Back', 'enteraddons' ),
            ]
        );
        
        $this->add_control(
            'back_icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
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
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_icon_width',
            [
                'label' => esc_html__( 'Icon Container Width', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_icon_height',
            [
                'label' => esc_html__( 'Icon Container Height', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'back_icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon',
            ]
        );
        $this->add_responsive_control(
            'back_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'back_icon_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'back_icon_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon.flip-icon',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Image Style Settings ------------------------------
         *
         */
        
        $this->start_controls_section(
            'enteraddons_flip_img_icon_settings', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                //'condition' => [ 'icon_type' => 'img' ],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'tab_flip_img_icon' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'front_img',
            [
                'label' => esc_html__( 'Front', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'front_img_width',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_img_height',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'front_img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'front_img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'front_img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img',
            ]
        );
        $this->add_responsive_control(
            'front_img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'front_img_box_bg',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'front_img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-front .enteraddons-flip-card-icon img',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For image style settings
        $this->start_controls_tab(
            'back_flip_image',
            [
                'label' => esc_html__( 'Back', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'back_img_width',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_img_height',
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'back_img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'back_img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-back .enteraddons-flip-card-icon img',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ IconCard Icon Link/Button Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'flip_card_btn_settings', [
                'label' => esc_html__( 'Button', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        //  Controls tab start
        $this->start_controls_tabs( 'btn_tabs_start' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'btn_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'btn_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-btn',
            ]
        );
        $this->add_responsive_control(
            'btn_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-btn',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_bg_color',
                'label' => esc_html__( 'Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-btn',
            ]
        );

        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'btn_hover_normal',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => esc_html__( 'Hover Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'btn_hover_border',
                'label' => esc_html__( 'Hover Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-btn:hover',
            ]
        );
        $this->add_responsive_control(
            'btn_hover_radius',
            [
                'label' => esc_html__( 'Hover Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-flip-card-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_hover_box_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-flip-card-btn:hover',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_hover_bg_color',
                'label' => esc_html__( 'Hover Button Background Color', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-flip-card-btn:hover',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section();

        

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Flip Card template render
        $obj = new \Enteraddons\Widgets\Flip_Card\Flip_Card_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'fontawesome' ];
    }


}
