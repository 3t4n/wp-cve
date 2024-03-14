<?php
namespace Enteraddons\Widgets\Lottie_Animation;

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

class Lottie_Animation extends Widget_Base {

	public function get_name() {
		return 'enteraddons-lottie-animation';
	}

	public function get_title() {
		return esc_html__( 'Lottie Animation', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera eicon-lottie';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}
    
	protected function register_controls() {


        // ----------------------------------------  Lottie Animation Content ------------------------------
        $this->start_controls_section(
            'enteraddons_lottie_animation_content',
            [
                'label' => esc_html__( 'Lottie Animation', 'enteraddons' ),
            ]
        );

        $this->add_responsive_control(
            'source_file',
            [
                'label' => esc_html__( 'Source', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'media_file',
                'options' => [
                    'media_file' => esc_html__( 'Media File', 'enteraddons' ),
                    'external_url'     => esc_html__( 'External URL', 'enteraddons' )
                ],
            ]
        );
        $this->add_control(
            'source_external_link',
            [
                'label' => esc_html__( 'External URL', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => ['source_file' => 'external_url',],
                'placeholder' => esc_html__( 'Enter Your URL', 'enteraddons' ),
                'description' =>  '<a href="https://lottiefiles.com/marketplace/featured" target="_blank">Take Ready Lottie Animation to Use</a>',
                'show_external' => true,
            ]
        );
        $this->add_control(
            'source_json',
            [
                'label' => esc_html__( 'Upload JSON File', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'media_type' => 'application/json',
                'description' =>  '<a href="https://lottiefiles.com/marketplace/featured" target="_blank">Take Ready Lottie Animation to Use</a>',
                'condition' => [ 'source_file' => 'media_file' ],
                'default' => [
                    'url' => 'https://assets9.lottiefiles.com/datafiles/MUp3wlMDGtoK5FK/data.json',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );
        $this->add_control(
            'wrapper_link',
            [
                'label'         => esc_html__( 'Do you want to set wrapper Link?', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'enteraddons' ),
                'label_off'     => esc_html__( 'No', 'enteraddons' ),
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );
        $this->add_control(
            'link',
            [
                'label' => esc_html__( 'Wrapper Link', 'enteraddons' ),
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
        $this->end_controls_section();

        // Animation Settings Option 
        $this->start_controls_section(
            'enteraddons_lottie_animation_settings',
            [
                'label' => esc_html__( 'Animation Settings', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'controls',
            [
                'label'         => esc_html__( 'Show Control Bar', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'enteraddons' ),
                'label_off'     => esc_html__( 'Hide', 'enteraddons' ),
                'return_value'  => 'controls',
                'default'       => '',
            ]
        );
        $this->add_control(
            'mode',
            [
                'label'         => esc_html__( 'Mode', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Bounce', 'enteraddons' ),
                'label_off'     => esc_html__( 'Normal', 'enteraddons' ),
                'return_value'  => 'bounce',
                'default'       => '',
            ]
        );
        $this->add_control(
            'direction',
            [
                'label'         => esc_html__( 'Direction', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Forward', 'enteraddons' ),
                'label_off'     => esc_html__( 'BackWard', 'enteraddons' ),
                'return_value'  => 1,
                'default'       => 1,
            ]
        );
        $this->add_control(
            'hover',
            [
                'label'         => esc_html__( 'Hover', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'ON', 'enteraddons' ),
                'label_off'     => esc_html__( 'OFF', 'enteraddons' ),
                'return_value'  => 'hover',
                'default'       => '',
            ]
        );
        $this->add_control(
            'loop',
            [
                'label'         => esc_html__( 'Animation Loop', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'ON', 'enteraddons' ),
                'label_off'     => esc_html__( 'OFF', 'enteraddons' ),
                'return_value'  => 'loop',
                'default'       => 'loop',
            ]
        );
        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'ON', 'enteraddons' ),
                'label_off'     => esc_html__( 'OFF', 'enteraddons' ),
                'return_value'  => 'autoplay',
                'default'       => 'autoplay',
            ]
        );
        $this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' =>1,
				'max' => 10,
				'step' => 1,
				'default' => 1,
			]
		);
        $this->add_control(
			'intermission',
			[
				'label' => esc_html__( 'Loop Intermission', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' =>0,
				'max' => 10000,
				'step' => 1,
				'default' => 1,
			]
		);
        $this->add_control(
			'count',
			[
				'label' => esc_html__( 'Count Loop', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' =>1,
				'max' => 1000,
				'step' => 5,
			]
		);
        $this->end_controls_section();

        /**
         * Style Tab
         * ------------------------------ Lottie Animation Wrapper Style ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_lottie_animation_wrapper_settings', [
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
                    '{{WRAPPER}} .ea-lottie-animation-container' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-lottie-animation-container' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-lottie-animation-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-lottie-animation-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tab_lottie_animation_normal' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'wrapper_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );    
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'wrapper_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-lottie-animation-container',
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
                    '{{WRAPPER}} .ea-lottie-animation-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-lottie-animation-container',
            ]
        ); 
        $this->add_group_control(
             \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-lottie-animation-container',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'lottie_animation_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
                [
                    'name'      => 'wrapper_hover_border',
                    'label'     => esc_html__( 'Border', 'enteraddons' ),
                    'selector'  => '{{WRAPPER}} .ea-lottie-animation-container:hover',
                ]
        );
        $this->add_responsive_control(
            'wrapper_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-lottie-animation-container:hover',
            ]
        ); 
        $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-lottie-animation-container:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section(); //End Wrapper Style

         /**
         * Style Tab
         * ------------------------------ Lottie Animation  Style ------------------------------
         *
         */
         $this->start_controls_section(
            'enteraddons_lottie_animation_style', [
                'label' => esc_html__( 'Lottie Animation Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'lottie_width',
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
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'lottie_height',
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
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'lottie_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'lottie_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs( 'tab_lottie_animation' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'lottie_animation_normal_style',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );    
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'lottie_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-lottie-animation',
            ]
        );
        $this->add_responsive_control(
            'lottie_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'lottie_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-lottie-animation',
            ]
        ); 
        $this->add_group_control(
             \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'lottie_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-lottie-animation',
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'lottie_animation_hover_style',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'lottie_hover_border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-lottie-animation:hover',
            ]
        );
        $this->add_responsive_control(
            'lottie_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-lottie-animation:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'lottie_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-lottie-animation:hover',
            ]
        ); 
        $this->add_group_control(
             \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'lottie_hover_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-lottie-animation:hover',
            ]
        );
        $this->end_controls_tab(); // End Controls tab
        $this->end_controls_tabs(); //  end controls tabs section
        $this->end_controls_section(); 

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Template render
        $obj = new \Enteraddons\Widgets\Lottie_Animation\Lottie_Animation_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();
    }
	
    public function get_script_depends() {
        return [ 'enteraddons-main','lottie-player'];
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
