<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_button_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'wpsection_wps_button';
	}

	public function get_title()
	{
		return __('Button Details', 'wpsection');
	}

	public function get_icon()
	{
		return 'eicon-button';
	}

	public function get_keywords()
	{
		return ['wpsection', 'button'];
	}

	public function get_categories()
	{
		return ['wpsection_category'];
	}


	protected function register_controls()
	{
		$this->start_controls_section(
			'button',
			[
				'label' => esc_html__('button', 'wpsection'),
			]
		);

		$this->add_control(
			'wps_button',
			[
				'label'       => esc_html__('Buton Text', 'element-path'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Button Text',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'wps_button_link',
			[
				'label'       => esc_html__('Buton Link', 'element-path'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => ' Button Link',
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'wpsection_btn_style_holder',
			[
				'label' => esc_html__('Button', 'wpsection'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wps_button_alingment',
			array(
				'label' => esc_html__('Alignment', 'wpsection'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'wpsection'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'wpsection'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'wpsection'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => array(
					'{{WRAPPER}}  .defult_wps' => 'text-align: {{VALUE}} !important',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wpsection_btn_typography',
				'label'    => esc_html__('Typography', 'wpsection'),
				'selector' => '{{WRAPPER}} .wpsection-btn',
			]
		);
		$this->add_responsive_control(
			'wpsection_btn_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wpsection_btn_border_padding',
			[
				'label'      => esc_html__('Padding', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpsection_btn_style_margin',
			[
				'label'      => esc_html__('Margin', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'wpsection_btn_style_use_width_height',
			[
				'label'        => esc_html__('Use Height Width', 'wpsection'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', 'wpsection'),
				'label_off'    => esc_html__('Hide', 'wpsection'),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);
		$this->add_responsive_control(
			'wpsection_btn_width',
			[
				'label'      => esc_html__('Width', 'wpsection'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wpsection_btn_style_use_width_height' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'wpsection_btn_style_height',
			[
				'label'      => esc_html__('Height', 'wpsection'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wpsection_btn_style_use_width_height' => 'yes'
				]
			]
		);
		$this->add_responsive_control(
			'wpsection_btn_style_line_height',
			[
				'label'      => esc_html__('Line Height', 'wpsection'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-btn' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wpsection_btn_style_use_width_height' => 'yes'
				]
			]
		);
		$this->start_controls_tabs(
			'wpsection_btn_normal_and_hover_tabs'
		);
		$this->start_controls_tab(
			'wpsection_btn_normal_tab',
			[
				'label' => esc_html__('Normal', 'wpsection'),
			]
		);

		$this->add_responsive_control(
			'wpsection_btn_color',
			[
				'label'     => esc_html__('Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpsection-btn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'wpsection_btn_background',
				'label'    => esc_html__('Background', 'wpsection'),
				'types'    => ['classic', 'gradient',],
				'default'   => '#f0393b',
				'selector' => '{{WRAPPER}} .wpsection-btn',
				'exclude'  => [
					'image'
				]
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wpsection_btn_box_shadow',
				'label'    => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wpsection-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wpsection_btn_border',
				'label'    => esc_html__('Border', 'wpsection'),
				'selector' => '{{WRAPPER}} .wpsection-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'wpsection_btn_text_shadow',
				'selector' => '{{WRAPPER}} .wpsection-btn',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'wpsection_btn_hover_tab',
			[
				'label' => esc_html__('Hover', 'wpsection'),
			]
		);

		$this->add_responsive_control(
			'wpsection_btn_color_hover',
			[
				'label'     => esc_html__('Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpsection-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'wpsection_btn_hover_background',
				'label'    => esc_html__('Background', 'wpsection'),
				'types'    => ['classic', 'gradient',],
				'selector' => '{{WRAPPER}} .wpsection-btn:hover',
				'exclude'  => [
					'image'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wpsection_btn_box_shadow_hover',
				'label'    => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wpsection-btn:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wpsection_btn_border_hover',
				'label'    => esc_html__('Border', 'wpsection'),
				'selector' => '{{WRAPPER}} .wpsection-btn:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'wpsection_btn_text_shadow_hover',
				'selector' => '{{WRAPPER}} .wpsection-btn:hover',
			]
		);



		$this->end_controls_tab();
		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');
?>


		<?php



		?>


		<div class="defult_wps">
			<a href="<?php echo $settings['wps_button_link']; ?>" class="wpsection-btn"><?php echo $settings['wps_button']; ?></a>
		</div>


<?php
	}
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_button_Widget());
