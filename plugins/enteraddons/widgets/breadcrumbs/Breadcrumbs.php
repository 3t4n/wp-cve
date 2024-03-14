<?php
namespace Enteraddons\Widgets\Breadcrumbs;

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

class Breadcrumbs extends Widget_Base {

	public function get_name() {
		return 'enteraddons-breadcrumbs';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-breadcrumbs';
	}

	public function get_categories() {
		return ['enteraddons-header-footer-category'];
	}
    
	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ---------------------------------------- Breadcrumbs content ------------------------------
        $this->start_controls_section(
            'enteraddons_breadcrumbs_content',
            [
                'label' => esc_html__( 'Breadcrumbs Content Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'breadcrumbs_type',
            [
                'label' => esc_html__( 'Breadcrumbs Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dynamic',
                'options' => [
                    'dynamic' => esc_html__( 'Dynamic', 'enteraddons' ),
                    'custom'  => esc_html__( 'Custom', 'enteraddons' )
                ],
            ]
        );
        $this->add_control(
            'delimiter',
            [
                'label' => esc_html__( 'Set Delimiter', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '&#47;',
                'options' => [
                    '&#47;'    => '&#47;',
                    '&#8594;'  => '&#8594;',
                    '&#46;'    => '&#46;',
                    '&#166;'   => '&#166;',
                    '&#10072;' => '&#10072;',
                    '&#168;'   => '&#168;',
                    '&#8229;'  => '&#8229;',
                    '&#8230;'  => '&#8230;',
                    '&#8943;'  => '&#8943;',
                    '&#8942;'  => '&#8942;',
                    '&#8944;'  => '&#8944;',
                    '&#45;'    => '&#45;',
                    '&#187;'   => '&#187;',
                    '&#95;'    => '&#95;',
                    '&#62;'    => '&#62;',
                    '&#58;'    => '&#58;',
                    '&#126;'   => '&#126;',
                    '&#8249;'  => '&#8249;',
                    '&#8250;'  => '&#8250;',
                    '&#8759;'  => '&#8759;',
                    '&#8888;'  => '&#8888;',
                    '&#8900;'  => '&#8900;',
                    '&#8658;'  => '&#8658;',
                    '&#8669;'  => '&#8669;',
                    '&#8702;'  => '&#8702;',
                    '&#10233;' => '&#10233;',
                    '&#10509;' => '&#10509;',
                    '&#10551;' => '&#10551;',
                    '&#10556;' => '&#10556;',
                    '&#10553;' => '&#10553;',
                ],
            ]
        );
        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Home' , 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );        
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'enteraddons' ),
                'dynamic' => [
                    'active' => true,
                ],
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
                
        $this->add_control(
            'custom_breadcrumbs',
            [
                'label' => esc_html__( 'Add Breadcrumbs', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => [ 'breadcrumbs_type' => 'custom' ],
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__( 'Home', 'enteraddons' ),
                    ]
                    
                ]
            ]
        );
        $this->add_control(
            'home_title',
            [
                'label' => esc_html__( 'Home Title', 'enteraddons' ),
                'condition' => [ 'breadcrumbs_type' => 'dynamic' ],
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Home', 'enteraddons' )
            ]
        );
        
        $this->end_controls_section(); // End content

        /**
         * Style Tab
         * ------------------------------ Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_breadcrumbs_content_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'wrap_alignment',
                [
                    'label' => esc_html__( 'Content Alignment', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'enteraddons' ),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'enteraddons' ),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'enteraddons' ),
                            'icon' => 'eicon-text-align-right',
                        ],
                    ],
                    'default' => 'center',
                    'toggle' => true,
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap ul' => 'justify-content: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_responsive_control(
                'wrap_width',
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
                        '{{WRAPPER}} .ea-breadcrumbs-wrap' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrap_height',
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
                        '{{WRAPPER}} .ea-breadcrumbs-wrap' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrap_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'wrap_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'wrap_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-breadcrumbs-wrap',
                ]
            );
            $this->add_responsive_control(
                'wrap_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'wrap_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-breadcrumbs-wrap',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'wrap_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-breadcrumbs-wrap',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Item Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_item_style_settings', [
                'label' => esc_html__( 'Item Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'item_typography',
                    'label' => esc_html__( 'Typography', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-breadcrumbs-wrap ul li a, {{WRAPPER}} .ea-breadcrumbs-wrap ul li',
                ]
            );
            $this->add_responsive_control(
                'item_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'item_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-breadcrumbs-wrap ul li',
                ]
            );
            $this->add_responsive_control(
                'item_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-breadcrumbs-wrap ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-breadcrumbs-wrap ul li',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'item_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .ea-breadcrumbs-wrap ul li',
                ]
            );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Link Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_link_style_settings', [
                'label' => esc_html__( 'Link Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
             
        $this->start_controls_tabs( 'tab_link_style' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-breadcrumbs-wrap ul li a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'link_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'link_hover_color',
            [
                'label' => esc_html__( 'Text Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-breadcrumbs-wrap ul li a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Active Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_active_settings', [
                'label' => esc_html__( 'Active Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'active_text_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-breadcrumbs-wrap ul li.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ea-breadcrumbs-wrap ul li' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Delimiter Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_delimiter_settings', [
                'label' => esc_html__( 'Delimiter Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'delimiter_color',
            [
                'label' => esc_html__( 'Delimiter Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-breadcrumb-delimiter' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'delimiter_font_size',
            [
                'label' => esc_html__( 'Delimiter Font Size', 'enteraddons' ),
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
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-breadcrumb-delimiter' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Breadcrumbs template render
        $obj = new \Enteraddons\Widgets\Breadcrumbs\Breadcrumbs_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
