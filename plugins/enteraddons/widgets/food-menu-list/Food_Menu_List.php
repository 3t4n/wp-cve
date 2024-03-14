<?php
namespace Enteraddons\Widgets\Food_Menu_List;

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
 * Enteraddons elementor Social Icon widget.
 *
 * @since 1.0
 */
class Food_Menu_List extends Widget_Base {

	public function get_name() {
		return 'enteraddons-food-menu-list';
	}

	public function get_title() {
		return esc_html__( 'Food Menu List', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-food-menu-list';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ---------------------------------------- content ------------------------------
        $this->start_controls_section(
            'enteraddons_food_menu_content',
            [
                'label' => esc_html__( 'Food Menu Content', 'enteraddons' ),
            ]
        );
        
        $repeater->add_control(
            'title', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( 'Lorem ipsum' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'price', [
                'label' => esc_html__( 'Price', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html__( '$25' , 'enteraddons' ),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'is_link',
            [
                'label'         => esc_html__( 'Do you want to active link?', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'enteraddons' ),
                'label_off'     => esc_html__( 'No', 'enteraddons' ),
                'return_value'  => 'yes',
                'default'       => 'no',
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
                'condition'   => [ 'is_link' => 'yes' ],
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
            ]
        );
        $this->add_control(
            'menu_list',
            [
                'label' => esc_html__( 'List', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__( 'Sliced Bread', 'enteraddons' ),
                        'price' => '$30'
                    ],
                    [
                        'title' => esc_html__( 'Alu Parata', 'enteraddons' ),
                        'price' => '$20'
                    ]
                    
                ]
            ]
        );

        $this->end_controls_section(); // End  content

        /**
         * Style Tab
         * ------------------------------ Wrapper Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_food_menu_wrapper_settings', [
                'label' => esc_html__( 'Wrapper Settings', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-menu-list-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-menu-list-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-menu-list-items',
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
                    '{{WRAPPER}} .ea-menu-list-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_wrapper_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-menu-list-items',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-menu-list-items',
            ]
        );
        $this->end_controls_section();
        
        /**
         * Style Tab
         * ------------------------------ Item Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'food_menu_item_settings', [
                'label' => esc_html__( 'Item Settings', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .ea-menu-list-items .menu-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-menu-list-items .menu-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-menu-list-items .menu-list-item',
            ]
        );
        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-menu-list-items .menu-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-menu-list-items .menu-list-item',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-menu-list-items .menu-list-item',
            ]
        );                         
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Text Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'text_style_settings', [
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
                    '{{WRAPPER}} .ea-menu-list-items .menu-list-item' => 'color: {{VALUE}}',
                ],
            ]
        );   
        $this->add_control(
            'text_hover_color',
            [
                'label' => esc_html__( 'Text Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-menu-list-items .menu-list-item:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-menu-list-items .menu-list-item',
            ]
        );

        $this->end_controls_section();
        /**
         * Style Tab
         * ------------------------------ Line Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'line_style_settings', [
                'label' => esc_html__( 'Line Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'line_border',
                'label' => esc_html__( 'Line Style', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-food-menu-list .menu-list-item:before'
            ]
        );
        $this->add_responsive_control(
            'line_margin',
            [
                'label' => esc_html__( 'Line Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-food-menu-list .menu-list-item:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_section();   

	}

	protected function render() {
        // get settings
        $settings = $this->get_settings_for_display();
        // Template render
        $obj = new Food_Menu_List_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
