<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Back_To_Top extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-back-to-top';
	}

	public function get_title() {
		return esc_html__( 'Back To Top', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-arrow-up';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'back to top',
			'to top',
		];
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'back_to_top_section_tab_content',
			array(
				'label' => esc_html__( 'Back To Top Settings', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_icons',
			array(
				'label'   => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-arrow-up',
					'library' => 'Font Awesome 5 Free',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'description' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'        => 'eicon-text-align-left',
					),
					'center' => array(
						'description' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'        => 'eicon-text-align-center',
					),
					'right'  => array(
						'description' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'        => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .back-to-top__swapper' => 'cursor: pointer; text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
		$this->_register_setting_button_style();
	}

	protected function _register_setting_button_style() {
		$this->start_controls_section(
			'back_to_top_style_section',
			array(
				'label' => esc_html__( 'Button Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'button_typography',
				'label'          => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector'       => '{{WRAPPER}} .back-to-top__button',
				'exclude'        => array( 'letter_spacing', 'font_style', 'text_decoration' ),
				'fields_options' => array(
					'typography'     => array(
						'default' => 'custom',
					),
					'font_weight'    => array(
						'default' => '400',
					),
					'font_size'      => array(
						'default'    => array(
							'size' => '16',
							'unit' => 'px',
						),
						'size_units' => array( 'px' ),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => esc_html__( 'Width (px)', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .back-to-top__button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_height',
			array(
				'label'      => esc_html__( 'Height (px)', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .back-to-top__button' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .back-to-top__button',
			)
		);

		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .back-to-top__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .back-to-top__button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_normal_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .back-to-top__button' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_shadow_normal',
				'label'    => esc_html__( 'Box Shadow', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .back-to-top__button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_hover_clr',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .back-to-top__button:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .back-to-top__button:focus' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_bg_clr',
			array(
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .back-to-top__button:hover' => 'background: {{VALUE}}',
					'{{WRAPPER}} .back-to-top__button:focus' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_shadow_hover',
				'label'    => esc_html__( 'Box Shadow', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .back-to-top__button:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="back-to-top__swapper" id="back-to-top-kits">
			<div class="back-to-top__button">
				<?php Icons_Manager::render_icon( $settings['button_icons'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
		</div>
		<?php
	}
}
