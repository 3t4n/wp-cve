<?php
namespace Enteraddons\Widgets\Typing_Animation;

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
 * Enteraddons elementor Typing Animation widget.
 *
 * @since 1.0
 */

class Typing_Animation extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-typing-animation';
	}

	public function get_title() {
		return esc_html__( 'Typing Animation', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-typing-animation';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        $repeater = new \Elementor\Repeater();
        // ---------------------------------------- Typing Animation Content ------------------------------
        $this->start_controls_section(
            'enteraddons_typing_animation_content',
            [
                'label' => esc_html__( 'Typing Animation Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'first_title',
            [
                'label' => esc_html__( 'Before Animation Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'EnterAddons '
            ]
        );
        $repeater->add_control(
            'animation_title',
            [
                'label' => esc_html__( 'Animation Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => esc_html__('Ultimate Template Builder', 'enteraddons'), 
            ]
        );
        $this->add_control(
			'animation_text_list',
			[
				'label' => esc_html__( 'Animation Text List', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'animation_title' => esc_html__('Ultimate Template Builder', 'enteraddons'),
						
					],
				],
			]
		);
        $this->add_control(
            'second_title',
            [
                'label' => esc_html__( 'After Animation Text', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => ' for Elementor'
            ]
        );
        $this->end_controls_section(); // End Typing Animation content

        // ---------------------------------------- Typing Animation Settings ------------------------------

        $this->start_controls_section(
            'enteraddons_typing_animation_content_settings',
            [
                'label' => esc_html__( 'Typing Animation Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
			'type_speed',
			[
				'label' => esc_html__( 'Type Speed', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 20,
				'max' => 500,
				'step' => 10,
				'default' => 100,
			]
		);
        $this->add_control(
			'start_delay',
			[
				'label' => esc_html__( 'Start Delay', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' =>0,
				'max' => 100,
				'step' => 10,
				'default' => 0,
			]
		);
        $this->add_control(
			'back_speed',
			[
				'label' => esc_html__( 'Back Speed', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 20,
				'max' => 500,
				'step' => 10,
				'default' => 60,
			]
		);
        $this->add_control(
			'back_delay',
			[
				'label' => esc_html__( 'Back Delay', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 50,
				'max' => 10000,
				'step' => 10,
				'default' => 2000,
			]
		);
        $this->add_control(
			'data_loop',
			[
				'label' => esc_html__( 'Loop', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->end_controls_section(); // End Typing Animation content

         /**
         * Style Tab
         * ------------------------------Typing Animation  Wrapper Style ------------------------------
         *
         */

        $this->start_controls_section(
            'enteraddons_title_wrapper_style_settings', [
                'label' => esc_html__( 'Content Wrapper Settings', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-at-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-at-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'item_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-at-wrapper',
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
                    '{{WRAPPER}} .ea-at-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-at-wrapper',
            ]
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-at-wrapper',
            ]
        );
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Normal Title Style ------------------------------
        *
        */

        $this->start_controls_section(
            'enteraddons_title_style_settings', [
                'label' => esc_html__( 'Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-animate-typing' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-animate-typing',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'title_stroke',
				'selector' => '{{WRAPPER}} .ea-animate-typing',
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
                    '{{WRAPPER}} .ea-animate-typing ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-animate-typing ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-animate-typing ',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-animate-typing ',
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
                    '{{WRAPPER}} .ea-animate-typing' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'label' => esc_html__( 'Background', 'enteraddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-animate-typing',
			]
		);
        $this->add_responsive_control(
            'title_alignment',
            [
                'label' => esc_html__( 'Title Alignment', 'enteraddons' ),
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
                    '{{WRAPPER}} .ea-at-wrapper' => 'text-align: {{VALUE}} !important',
                ],
            ]
        );
        $this->end_controls_section();

        /**
        * Style Tab
        * ------------------------------ Typing Animation Title Style ------------------------------
        *
        */
        
        $this->start_controls_section(
            'enteraddons_animation_title_style', [
                'label' => esc_html__( 'Animation Text Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'animation_text_color',
            [
                'label' => esc_html__( 'Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ea-typed' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'animation_text_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-typed',
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'animation_text_stroke',
				'selector' => '{{WRAPPER}} .ea-typed',
			]
		);
        $this->add_responsive_control(
            'animation_text_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-typed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'animation_text_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-typed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'animation_text_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-typed',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'animation_text_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-typed',
            ]
        );
        $this->add_responsive_control(
            'animation_text_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-typed' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'animation_text_background',
				'label' => esc_html__( 'Background', 'enteraddons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-typed',
			]
		);
        $this->end_controls_section();

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // template render
        $obj = new Typing_Animation_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main','typed'];
    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
