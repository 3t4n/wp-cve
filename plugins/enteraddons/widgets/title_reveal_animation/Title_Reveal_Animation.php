<?php
namespace Enteraddons\Widgets\Title_Reveal_Animation;

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
 * Enteraddons elementor Title Reveal Animation widget.
 *
 * @since 1.0
 */
class Title_Reveal_Animation extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-title-reveal-animation';
	}

	public function get_title() {
		return esc_html__( 'Title Reveal Animation', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-title-reveal-animation';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ---------------------------------------- Title Reveal Animation content ------------------------------
        $this->start_controls_section(
            'enteraddons_title_reveal_animation_content_settings',
            [
                'label' => esc_html__( 'Title Reveal Animation', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'animation_title',
            [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'default' => 'REVEAL ANIMATION',
                'description' => esc_html__( 'This field support span,br,b,strong,small,i', 'enteraddons' )
            ]
        );
        $this->add_control(
            'animation_description',
            [
                'label'         => esc_html__( 'Description', 'enteraddons' ),
                'type'          => \Elementor\Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                 Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
                  when an unknown printer took a galley of type and scrambled'
            ]
        );
        $this->add_control(
			'title_data_delay',
			[
				'label' => esc_html__( 'Title Data Delay ', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 1000,
				'step' => 10,
				'default' => 10,
			]
		);
        $this->add_control(
			'Description_data_delay',
			[
				'label' => esc_html__( 'Description Data Delay ', 'enteraddons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 5000,
				'step' => 50,
				'default' => 1000,
			]
		);
    
        $this->end_controls_section(); // End Title Reveal Animation content

        /**
         * Style Tab
         * ------------------------------ Title Reveal Animation Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_title_reveal_animation_style_settings', [
                'label' => esc_html__( 'Title', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );     
        $this->start_controls_tabs( 'tab_title_reveal_animation' );

        //  Controls tab For Normal
        $this->start_controls_tab(
            'title_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaatbigger' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaatbigger',
            ]
        );
        $this->add_responsive_control(
            'title_alignment',
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
                    '{{WRAPPER}} .eaatbigger' => 'text-align: {{VALUE}} !important',
                ],
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
                    '{{WRAPPER}} .eaatbigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .eaatbigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaatbigger',
            ]
        );
        $this->add_responsive_control(
            'boder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eaatbigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        //  Controls tab For Hover
        $this->start_controls_tab(
            'title_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaatbigger:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab(); // End Controls tab

        $this->end_controls_tabs(); //  end controls tabs section

        $this->end_controls_section();// end Title Style


         /**
         * Style Tab
         * ------------------------------ Reveal Animation Description Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_description_reveal_animation_style_settings', [
                'label' => esc_html__( 'Description', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );     
       
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Description Color', 'enteraddons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaattext' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaattext',
            ]
        );
        $this->add_responsive_control(
            'description_alignment',
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
                    '{{WRAPPER}} .eaattext' => 'text-align: {{VALUE}} !important',
                ],
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
                    '{{WRAPPER}} .eaattext' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .eaattext' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'description_width',
            [
                'label' => esc_html__( 'Description Wrapper Width', 'enteraddons' ),
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
                    '{{WRAPPER}} .eaattext' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'description_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .eaattext',
            ]
        );
        $this->add_responsive_control(
            'description_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .eaattext' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
       
        $this->end_controls_section();// end Title Style

        /**
         * Style Tab
         * ------------------------------ Reveal Animation Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_reveal_animation_style_settings', [
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
                'selector' => '{{WRAPPER}} .eaathas-animation.eaatanimate-in:before',
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
                'selector' => '{{WRAPPER}} .eaathas-animation.eaatanimate-in:after',
            ]
        );
       
       
        $this->end_controls_section();// end Animation Style    

	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // Tema template render
        $obj = new Title_Reveal_Animation_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    public function get_script_depends() {
        return [ 'enteraddons-main'];
    }

    public function get_style_depends() {
        return [ 'enteraddons-global-style' ];
    }


}
