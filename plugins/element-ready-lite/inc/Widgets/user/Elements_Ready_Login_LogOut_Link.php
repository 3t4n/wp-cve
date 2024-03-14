<?php

namespace Element_Ready\Widgets\user;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elements_Ready_Login_LogOut_Link extends Widget_Base {

	public function get_name() {
		return 'Elements_Ready_Login_LogOut_Link';
	}

	public function get_title(){
		return esc_html__( 'ER LoginLogout Link' , 'element-ready-lite' );
	}

	public function get_style_depends() {

		wp_register_style( 'eready-login-logout-link' , ELEMENT_READY_ROOT_CSS.'widgets/eready-login-logout-link.min.css' ); 
		
		return[
			'eready-login-logout-link'
		];
	}


	public function get_icon() {
		return 'eicon-date';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'widget', 'login link', 'logout link' ];
    }

	protected function register_controls() {

	
		$this->start_controls_section(
			'section_Settings',
			[
				'label' => esc_html__( 'Settings', 'element-ready-lite' ),
			]
		);

		$this->add_control(
			'redirect_url', [
				'label'			  => esc_html__( 'Custom Redirect Link', 'element-ready-lite' ),
				'type'			  => Controls_Manager::URL,
				'label_block'	  => true,
				
			]
		);

		$this->end_controls_section();
       
		$this->start_controls_section(
			'style_date_section',
			[
				'label' => esc_html__( 'Style', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .er-log-link-wrapper a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .er-log-link-wrapper a',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'date_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-log-link-wrapper a',
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_date_wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'flex_width_gap',
				[
					'label' => esc_html__( 'Gap', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .er-log-link-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'timer_show' => ['yes']
					]

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'flex_width_wrapper_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .er-log-link-wrapper',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'flex_width_wrapper_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-log-link-wrapper',
				]
			);

			$this->add_control(
				'flex_width_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .er-log-link-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'_section_align_section_e__text_align',
				[
					'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [

						'left'    => esc_html__( 'Left', 'element-ready-lite' ),
						'right'      => esc_html__( 'Right', 'element-ready-lite' ),
						'center'        => esc_html__( 'Center', 'element-ready-lite' ),
						
					],

					'selectors' => [
						'{{WRAPPER}} .er-log-link-wrapper' => 'text-align: {{VALUE}};'
				],
				]
				
			);

		$this->end_controls_section();

	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();
	    $redirect = $settings['redirect_url'];
		$custom_url = isset($redirect['url']) && $redirect['url'] !='' ? $redirect['url'] : ''; 
	    ?>
		<div class="er-log-link-wrapper">
			<?php wp_loginout(esc_url($custom_url)); ?>
		</div>
		<?php	
	}	
}
