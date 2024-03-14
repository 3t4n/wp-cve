<?php
namespace Enteraddons\Widgets\Review_Badge;

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
 * Enteraddons elementor Heading widget.
 *
 * @since 1.0
 */
class Review_Badge extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-review-badge';
	}

	public function get_title() {
		return esc_html__( 'Review Badge', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-review-badge';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Review Badge content ------------------------------
        $this->start_controls_section(
            'enteraddons_review_badge_content_settings',
            [
                'label' => esc_html__( 'Review Badge', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'ratings',
            [
                'label' => esc_html__( 'Ratings', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => true,
                'default' => '4.5',
                'options' => [
                    '1'     => esc_html__( '1', 'enteraddons' ),
                    '1.5'   => esc_html__( '1.5', 'enteraddons' ),
                    '2'     => esc_html__( '2', 'enteraddons' ),
                    '2.5'   => esc_html__( '2.5', 'enteraddons' ),
                    '3'     => esc_html__( '3', 'enteraddons' ),
                    '3.5'   => esc_html__( '3.5', 'enteraddons' ),
                    '4'     => esc_html__( '4', 'enteraddons' ),
                    '4.5'   => esc_html__( '4.5', 'enteraddons' ),
                    '5'     => esc_html__( '5', 'enteraddons' )
                ]
            ]
        );
        $this->add_control(
            'rating_img',
            [
                'label' => esc_html__( 'Ratings Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'rating_text',
            [
                'label' => esc_html__( 'Text', 'enteraddons' ),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );
        $this->end_controls_section(); // End content

        /**
         * Style Tab
         * ------------------------------ Content Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_review_badges_wrapper_style_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'content_alignment',
                [
                    'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
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
                        '{{WRAPPER}} .enteraddons-customer-review' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );            
            $this->add_responsive_control(
                'review_badges_wrap_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'review_badges_wrap_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-customer-review',
                ]
            );
            $this->add_responsive_control(
                'review_badges_wrap_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'review_badges_wrap_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'review_badges_wrap_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review',
                ]
            );
        $this->end_controls_section();
        //------------------------------ Image Style ------------------------------
        $this->start_controls_section(
            'enteraddons_review_badges_image_style', [
                'label' => esc_html__( 'Image Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'img_width',
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
                    '{{WRAPPER}} .enteraddons-customer-review img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_height',
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
                    '{{WRAPPER}} .enteraddons-customer-review img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'img_display_style',
            [
                'label' => esc_html__( 'Display', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inline-block',
                'options' => [
                    'block'  => esc_html__( 'Block', 'enteraddons' ),
                    'inline-block'  =>  esc_html__( 'Inline Block', 'enteraddons' )
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review img' => 'display: {{VALUE}};',
                ]
            ]
        );
        $this->end_controls_section();

        //------------------------------ Star Style ------------------------------
        $this->start_controls_section(
            'enteraddons_review_badges_star_style', [
                'label' => esc_html__( 'Star Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'star_icon_size',
            [
                'label' => esc_html__( 'Star Icon Size', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review .rating i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'star_color',
            [
                'label' => esc_html__( 'Star Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review .rating i' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'star_margin',
            [
                'label' => esc_html__( 'Star Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review .rating i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'star_padding',
            [
                'label' => esc_html__( 'Star Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review .rating i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'review_badges_star_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-customer-review .rating i',
                ]
            );
            $this->add_responsive_control(
                'review_badges_star_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review .rating i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'review_badges_star_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review .rating i',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'review_badges_star_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review .rating i',
                ]
            );
            //
            $this->add_control(
                'star_block_style',
                [
                    'label' => esc_html__( 'Star Block Style', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'review_badges_star_block_margin',
                [
                    'label' => esc_html__( 'Star Block Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review .rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'review_badges_star_block_padding',
                [
                    'label' => esc_html__( 'Star Block Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review .rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'review_badges_star_block_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-customer-review .rating',
                ]
            );
            $this->add_responsive_control(
                'review_badges_star_block_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review .rating' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'review_badges_star_block_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review .rating',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'review_badges_star_block_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review .rating',
                ]
            );
        $this->end_controls_section();
        //------------------------------ Ratings number Style ------------------------------
        $this->start_controls_section(
            'enteraddons_review_badges_rating_number_style', [
                'label' => esc_html__( 'Rating Number Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'rating_number_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review h3' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'rating_number_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-customer-review h3',
            ]
        );
        $this->add_responsive_control(
            'rating_number_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'rating_number_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'rating_number_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .enteraddons-customer-review h3',
                ]
            );
            $this->add_responsive_control(
                'rating_number_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-customer-review h3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'rating_number_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review h3',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'rating_number_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-customer-review h3',
                ]
            );
        $this->end_controls_section();
        //------------------------------ Text Style ------------------------------
        $this->start_controls_section(
            'enteraddons_review_badges_text_style', [
                'label' => esc_html__( 'Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review p' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-customer-review p',
            ]
        );
        $this->add_responsive_control(
            'text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-customer-review p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Review_Badge_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
