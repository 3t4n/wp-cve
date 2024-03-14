<?php
namespace Enteraddons\Widgets\Accordion;

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
 * Enteraddons elementor Accordion widget.
 *
 * @since 1.0
 */
class Accordion extends Widget_Base {

	public function get_name() {
		return 'enteraddons-accordion';
	}

	public function get_title() {
		return esc_html__( 'Accordion', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-accordion';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();
        
        // ----------------------------------------  Accordion content ------------------------------
        $this->start_controls_section(
            'enteraddons_accordion_settings',
            [
                'label' => esc_html__( 'Accordion Content', 'enteraddons' ),
            ]
        );
        
        $repeater->add_control(
            'accordion_title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'accordion_active', [
                'label' => esc_html__( 'Active', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' =>esc_html__( 'Yes', 'enteraddons' ),
                'label_off' =>esc_html__( 'No', 'enteraddons' ),
                
            ]
        );
        $repeater->add_control(
            'accordion_desc',
            [
                'label'         => esc_html__( 'Description', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default'       => esc_html__( 'Default description', 'enteraddons' )
            ]
        );
        $this->add_control(
            'accordion',
            [
                'label' => esc_html__( 'Accordion', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ accordion_title }}}',
                'default' => [
                    [
                        'accordion_title'    => esc_html__( 'What are the requirements to use Enter Addons for Elementor?', 'enteraddons' ),
                        'accordion_desc'  => esc_html__( 'Only the Elementor Plugin is needed. Before using Enter Addons, you only require to update the Elementor plugin on your website', 'enteraddons' ),
                    ],
                    [
                        'accordion_title'    => esc_html__( 'How many built-in demo blocks do you provide?', 'enteraddons' ),
                        'accordion_desc'  => esc_html__( 'We have created more than 500 built-in blocks with customizable layouts. When building your website, you can use these templates and modify them.', 'enteraddons' ),
                    ],
                    [
                        'accordion_title'    => esc_html__( 'Do you provide free widgets?', 'enteraddons' ),
                        'accordion_desc'  => esc_html__( 'We have included more than 60 free widgets for Elementor. Those can be used to create your website. But you should use the Enter Addons Elementor Pro widgets for a better experience.', 'enteraddons' ),
                    ],
                ],
            ]
        );


        $this->end_controls_section(); // End Accordion content

        // ----------------------------------------  Accordion Icon Settings ------------------------------
        $this->start_controls_section(
            'enteraddons_accordion_icon_settings',
            [
                'label' => esc_html__( 'Accordion Icon Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'number_count_show',
            [
                'label' => esc_html__( 'Show Number Count', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'enteraddons' ),
                'label_off' => esc_html__( 'Hide', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__( 'Show Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'enteraddons' ),
                'label_off' => esc_html__( 'No', 'enteraddons' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__( 'Icon Position Type', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'right',
                'condition' => [ 'show_icon' => 'yes' ],
                'options' => [
                    'left'    => esc_html__( 'Left', 'enteraddons' ),
                    'right'   => esc_html__( 'Right', 'enteraddons' ),
                    'both'    => esc_html__( 'Both Side', 'enteraddons' )

                ]
            ]
        );
        $this->add_control(
            'right_icon_position',
            [
                'label' => esc_html__( 'Right Icon Position', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'ab-right',
                'condition' => [ 'show_icon' => 'yes' ],
                'options' => [
                    'near-text'    => esc_html__( 'Near Text', 'enteraddons' ),
                    'ab-right'   => esc_html__( 'Absolute Right', 'enteraddons' )

                ]
            ]
        );
        
        $this->add_control(
            'left_active_icon',
            [
                'label' => esc_html__( 'Left Active Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [ 'icon_position' => ['left','both'],'show_icon' => 'yes' ]
            ]
        );
        $this->add_control(
            'left_close_icon',
            [
                'label' => esc_html__( 'Left Close Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [ 'icon_position' => ['left','both'],'show_icon' => 'yes' ]
            ]
        );
        $this->add_control(
            'right_active_icon',
            [
                'label' => esc_html__( 'Right Active Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'solid',
                ],
                'condition' => [ 'icon_position' => ['right','both'], 'show_icon' => 'yes' ]
            ]
        );
        $this->add_control(
            'right_close_icon',
            [
                'label' => esc_html__( 'Right Close Icon', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'solid',
                ],
                'condition' => [ 'icon_position' => ['right','both'],'show_icon' => 'yes' ]
            ]
        );
        $this->end_controls_section(); // End Accordion Icon Settings
        
        /**
         * Style Tab
         * ------------------------------ Accordion Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_accordion_wrapper_settings', [
                'label' => esc_html__( 'Wrapper Area Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-faq' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-faq' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-faq',
            ]
        );
        $this->add_responsive_control(
            'content_wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-faq' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-faq',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-faq',
            ]
        );
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Accordion layout Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_accordion_layout_settings', [
                'label' => esc_html__( 'Item Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'layout_area_margin',
                [
                    'label' => esc_html__( 'Margin', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-faq' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'layout_area_padding',
                [
                    'label' => esc_html__( 'Padding', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-faq' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'layout_area_border_border',
                    'label' => esc_html__( 'Border', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-faq',
                ]
            );
            $this->add_responsive_control(
                'layout_area_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .enteraddons-single-faq' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'layout_area_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .enteraddons-single-faq',
                ]
            ); 
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'layout_area_background',
                    'label' => esc_html__( 'Background', 'enteraddons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .enteraddons-single-faq',
                ]
            );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Accordion Title Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_accordion_title_settings', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //controls tabs start
        $this->start_controls_tabs( 'tab_accordion_title' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'normal_title',
            [
                'label' => esc_html__( 'Normal Style', 'enteraddons' ),
            ]
        ); 
        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title',
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
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title',
            ]
        );
        $this->add_responsive_control(
            'title_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For title active
        $this->start_controls_tab(
            'active_title',
            [
                'label' => esc_html__( 'Active Style', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'title_active_color',
            [
                'label' => esc_html__( 'Title Active Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq.active .enteraddons-faq-title' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'title_active_bg_color',
                'label' => esc_html__( 'Title Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq.active .enteraddons-faq-title',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Accordion Descriptions Style Settings ------------------------------
         *
         */
        
        $this->start_controls_section(
            'enteraddons_accordion_descriptions_settings', [
                'label' => esc_html__( 'Descriptions', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'descriptions_color',
            [
                'label' => esc_html__( 'Text Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'descriptions_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content',
            ]
        );
        $this->add_responsive_control(
            'descriptions_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'descriptions_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'descriptions_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content',
            ]
        );
        $this->add_responsive_control(
            'descriptions_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'descriptions_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-content',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Accordion Icon Style Settings ------------------------------
         *
         */
        
        $this->start_controls_section(
            'enteraddons_accordion_icon_style_settings', [
                'label' => esc_html__( 'Icon', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_icon' => 'yes' ]
            ]
        );
        $this->add_responsive_control(
            'icon_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        //controls tabs start
        $this->start_controls_tabs( 'tab_accordion_icon' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'icon_normal',
            [
                'label' => esc_html__( 'Normal Style', 'enteraddons' ),
            ]
        );
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Icon Active Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .close-icon i' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon',
            ]
        );
        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq .enteraddons-faq-title .ea-faq-title-icon',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        //  Controls tab For active
        $this->start_controls_tab(
            'icon_active',
            [
                'label' => esc_html__( 'Active Style', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'icon_active_color',
            [
                'label' => esc_html__( 'Icon Active Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq.active .enteraddons-faq-title .active-icon i' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'icon_active_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-wid-con .enteraddons-single-faq.active .enteraddons-faq-title .active-icon',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Accordion Number Style Settings ------------------------------
         *
         */
        
        $this->start_controls_section(
            'enteraddons_accordion_number_style_settings', [
                'label' => esc_html__( 'Number Count', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'number_count_show' => 'yes' ]
            ]
        );

        $this->add_responsive_control(
            'number_size',
            [
                'label' => esc_html__( 'Number Size', 'enteraddons' ),
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
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'number_width',
            [
                'label' => esc_html__( 'Width', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'number_height',
            [
                'label' => esc_html__( 'Height', 'enteraddons' ),
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
                    'size' => '15',
                ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'number_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count',
            ]
        );
        $this->add_responsive_control(
            'number_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        //controls tabs start
        $this->start_controls_tabs( 'tab_accordion_number' );
        //  Controls tab For Normal
        $this->start_controls_tab(
            'number_normal',
            [
                'label' => esc_html__( 'Normal Style', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'number_color',
            [
                'label' => esc_html__( 'Number Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'number_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-faq .enteraddons-faq-title .faq-number-count',
            ]
        );

        $this->end_controls_tab(); // End Controls tab
        //  Controls tab For active
        $this->start_controls_tab(
            'number_active',
            [
                'label' => esc_html__( 'Active Style', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'number_active_color',
            [
                'label' => esc_html__( 'Icon Active Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .enteraddons-single-faq.active .enteraddons-faq-title .faq-number-count' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'number_active_bg_color',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'show_label' => true,
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .enteraddons-single-faq.active .enteraddons-faq-title .faq-number-count',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs
        $this->end_controls_section();

	}

	protected function render() {

        $settings = $this->get_settings_for_display();

        // Testimonial template render
        $obj = new \Enteraddons\Widgets\Accordion\Accordion_Templates();
        //
        $obj::setDisplaySettings( $settings );
        //
        $obj::renderTemplate();
    }
	
    public function get_script_depends() {
        return ['enteraddons-main'];
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style', 'fontawesome' ];
    }



}

