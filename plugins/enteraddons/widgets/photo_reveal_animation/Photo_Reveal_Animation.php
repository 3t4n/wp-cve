<?php
namespace Enteraddons\Widgets\Photo_Reveal_Animation;

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
 * Enteraddons elementor Photo Reveal Animation widget.
 *
 * @since 1.0
 */

class Photo_Reveal_Animation extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-photo-reveal-animation';
	}

	public function get_title() {
		return esc_html__( 'Photo Reveal Animation', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-photo-reveal-animation';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Photo Reveal Animation content ------------------------------
        $this->start_controls_section(
            'enteraddons_photo_reveal_animation_content_settings',
            [
                'label' => esc_html__( 'Content', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src()
                ],
            ]
        );
        $this->add_control(
			'image_animation_style',
			[
				'label' => esc_html__( 'Image Animation Style', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'eaam-animation-rtl',
				'options' => [
                    ''  => esc_html__( 'Default', 'enteraddons' ),
					'eaam-animation-rtl'  => esc_html__( 'Style 1', 'enteraddons' ),
					'eaam-animation-ltr' => esc_html__( 'Style 2', 'enteraddons' ),
				],
			]
		);
        $this->add_control(
			'image_animation_data_delay',
			[
				'label' => esc_html__( 'Data Delay', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 5000,
				'step' => 10,
				'default' => 10,
			]
		);
        $this->end_controls_section(); // End  content

        //------------------------------ Photo Reveal Animation Style ------------------------------
        $this->start_controls_section(
            'enteraddons_photo_reveal_animation_style', [
                'label' => esc_html__( 'Image', 'enteraddons' ),
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
                    'unit' => 'px',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   
        $this->add_responsive_control(
            'img_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'img_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'img_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img',
            ]
        );
        $this->add_responsive_control(
            'img_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'img_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaam-has-animation.eaam-animate-in img',
            ]
        );
        $this->end_controls_section();//end photo reveal animation style section

        /**
         * Style Tab
         * ------------------------------ Photo Reveal Animation Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_photo_reveal_animation_style_settings', [
                'label' => esc_html__( 'Animation', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 
        $this->add_control(
			'before_animation_background_options',
			[
				'label' => esc_html__( 'Before Animation Color', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);    
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'before_animation_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eaam-has-animation.eaam-animate-in:before',
            ]
        );

        $this->add_control(
			'after_animation_background_options',
			[
				'label' => esc_html__( 'After Animation Color', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);    
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'after_animation_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eaam-has-animation.eaam-animate-in:after',
            ]
        );
        $this->end_controls_section();// end Animation Style 

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Photo_Reveal_Animation_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }

    public function get_script_depends() {
        return [ 'enteraddons-main' ]; 
    }
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
