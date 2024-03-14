<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Button extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-button';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content',
			array(
				'label' => __( 'Content', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Text', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'Add your text here', 'thim-elementor-kit' ),
				'default'     => esc_html__( 'Click here', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'link_url',
			array(
				'label'       => __( 'Link', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'url'         => '#',
				),
				'placeholder' => __( 'https://your-link.com', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'Left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'elementor' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);
		$this->add_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'elementor' ),
					'right' => esc_html__( 'After', 'elementor' ),
				),
				'condition' => array( 'icon[value]!' => '' ),
			)
		);

		$this->add_control(
			'icon_spacing',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button span' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition' => array( 'icon[value]!' => '' ),
			)
		);
		$this->end_controls_section();

		$this->register_style_button();
		$this->register_style_button_hover();

	}

	protected function register_style_button() {
		$this->start_controls_section(
			'button_settings',
			array(
				'label' => esc_html__( 'Button', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'style_hover_effect',
			array(
				'label'   => esc_html__( 'Hover Effect', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-button a',
			)
		);
		$this->add_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}  .thim-ekits-button a i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button a svg' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'icon[value]!' => '' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-button a',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 25,
					'bottom' => 15,
					'left'   => 25,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_style_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-button a svg path' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'icon[value]!' => '' ),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'button_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'style_hover_effect!' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button:not(.custom-button-hover) a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'button_border_border!' => 'none',
					'style_hover_effect!'   => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_icon_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button a:hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekits-button a:hover svg path' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'icon[value]!' => '' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function register_style_button_hover() {
		$this->start_controls_section(
			'style_hover_settings',
			array(
				'label'     => esc_html__( 'Dot Hover Effect', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style_hover_effect' => 'yes',
				),
			)
		);

		$this->add_control(
			'dot_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'left',
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'dot_offset_h',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 0,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:before' => '{{dot_orientation_h.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'dot_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'top',
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'dot_offset_v',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 0,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:before' => '{{dot_orientation_v.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->start_controls_tabs(
			'button_style_hover_tabs'
		);

		$this->start_controls_tab(
			'button_style_hover_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);
		$this->add_responsive_control(
			'dot_with',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:before' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'dot_height',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:before' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'dot_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_style_hover_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_responsive_control(
			'dot_with_hover',
			array(
				'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:hover:before' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'dot_height_hover',
			array(
				'label'      => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:hover:before' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'dot_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-button.custom-button-hover a:hover:before' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'button', 'class', 'thim-ekits-button' );

		if ( isset( $settings['style_hover_effect'] ) && $settings['style_hover_effect'] == 'yes' ) {
			$this->add_render_attribute( 'button', 'class', 'custom-button-hover' );
		}
		if ( ! empty( $settings['link_url'] ) ) {
			$this->add_link_attributes( 'button-link', $settings['link_url'] );
		}
		?>

		<div <?php $this->print_render_attribute_string( 'button' ); ?>>
			<a <?php $this->print_render_attribute_string( 'button-link' ); ?> role="button">
				<span class="button-content-wrapper">
					<?php
					if ( ! empty( $settings['icon'] ) ) {
						Icons_Manager::render_icon(
							$settings['icon'],
							array(
								'aria-hidden' => 'true',
								'class'       => 'icon-align-' . esc_attr( $settings['icon_align'] ),
							)
						);
					}
					echo esc_html( $settings['title'] );
					?>
				</span>
			</a>
		</div>

		<?php
	}
}
