<?php
namespace Enteraddons\Widgets\Business_Hours;

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
 * Enteraddons elementor Team widget.
 *
 * @since 1.0
 */

class Business_Hours extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-business-hours';
	}

	public function get_title() {
		return esc_html__( 'Business Hours', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-business-hours';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

		$repeater = new \Elementor\Repeater();

        // ----------------------------------------  Business Hours content ------------------------------
        $this->start_controls_section(
            'enteraddons_business_hours_content_settings',
            [
                'label' => esc_html__( 'Business Hours Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'enteraddons' ),
				'label_off' => esc_html__( 'Hide', 'enteraddons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
            'business_hours_heading',
            [
                'label' => esc_html__( 'Heading', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Business Hours'
            ]
        );
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'business_hours_day',
            [
                'label' => esc_html__( 'Day', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'Saturday'
            ]
        );
        $repeater->add_control(
            'business_hours_time',
            [
                'label' => esc_html__( 'Time', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => '9:00 AM to 6:00 PM'
            ]
        );

        $repeater->add_control(
            'item_holiday_color',
            [
                'label' => esc_html__( 'Hodiday  Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default'=> '#00000',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}} !important',
                    
                ],
            ]
        );
        $this->add_control(
            'business_hours_list',
            [
                'label' => esc_html__( 'Business Hours List', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ business_hours_day }}}',
                'default' => [
                    [
                        'business_hours_day'   => esc_html__( 'Saturday', 'enteraddons' ),
                        'business_hours_time'   => esc_html__( '9:00 AM to 6:00 PM', 'enteraddons' ),   
                    ],
                    [
                        'business_hours_day'   => esc_html__( 'Sunday', 'enteraddons' ),
                        'business_hours_time'   => esc_html__( '9:00 AM to 6:00 PM', 'enteraddons' ),
                    ],
                    [
                        'business_hours_day'   => esc_html__( 'Monday', 'enteraddons' ),
                        'business_hours_time'   => esc_html__( '9:00 AM to 6:00 PM', 'enteraddons' ),
                    ],
                    [
                        'business_hours_day'   => esc_html__( 'Tuesday', 'enteraddons' ),
                        'business_hours_time'  => esc_html__( 'Closed', 'enteraddons' ),
                        'item_holiday_color'   => '#FF0000',
                    ],
                    
                ]
            ]
        );
       
        $this->end_controls_section(); // End Business Hours content
        /**
         * Style Tab
         * ------------------------------ Business Hours Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_bussiness_hours_wrapper_settings', [
                'label' => esc_html__( 'Wrapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_width',
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
                    '{{WRAPPER}} .ea-business-hours-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_height',
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
                    '{{WRAPPER}} .ea-business-hours-wrap' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'wrapper_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-business-hours-wrap',
                ]
            );
            $this->add_responsive_control(
                'wrapper_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-business-hours-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'wrapper_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-business-hours-wrap',
                ]
            ); 
          $this->add_group_control(
             \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap',
            ]
        );

        $this->end_controls_section(); //End Wrapper Style
         
         /**
         * Style Tab
         * ------------------------------ Business Hours Header  Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_bussiness_hours_content_settings', [
                'label' => esc_html__( 'Header Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );
        $this->start_controls_tabs( 'bussiness_hours_title' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'bussiness_hours_heading_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header h4',
            ]
        );
        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'heading_alignment',
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
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header' => 'text-align: {{VALUE}} ',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'heading_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header',
                ]
            );
            $this->add_responsive_control(
                'heading_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'Header_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header',
                ]
            ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'heading_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-business-hours-header',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'heading_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'heading_hover_color',
            [
                'label' => esc_html__( 'Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap:hover .ea-business-hours-header h4' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'heading_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap:hover .ea-business-hours-header',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section
        
        $this->end_controls_section();  

        /**
         * Style Tab
         * ------------------------------ Business Hours Item Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_bussiness_hours_Item_settings', [
                'label' => esc_html__( 'Item Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'item_color',
            [
                'label' => esc_html__( 'Item Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item .ea-day, .ea-business-hours-wrap .ea-single-item .ea-time ',
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
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'item_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item',
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
                        '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'label' => esc_html__( 'Item Box Shadow', 'enteraddons' ),
                    'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item',
                ]
            ); 
            $this->add_control(
                'item_odd_bg_option',
                [
                    'label' => esc_html__( 'Odd Item Background', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'item_odd_color',
                [
                    'label' => esc_html__( 'Odd Item Color', 'enteraddons' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item:nth-child(odd)' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
             \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_odd_background',
                'label' => esc_html__( 'Odd Item Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item:nth-child(odd)',
            ]
            );
        $this->add_control(
            'item_even_bg_option',
            [
                'label' => esc_html__( 'Even Item Background', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'item_even_color',
            [
                'label' => esc_html__( 'Even Item Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item:nth-child(even)' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
           [
               'name' => 'item_even_background',
               'label' => esc_html__( 'Even Item Background', 'enteraddons' ),
               'types' => [ 'classic', 'gradient' ],
               'selector' => '{{WRAPPER}} .ea-business-hours-wrap .ea-single-item:nth-child(even)',
           ]
       );

        $this->end_controls_section();  // end Item Style

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Business_Hours_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}