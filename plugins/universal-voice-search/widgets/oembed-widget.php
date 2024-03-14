<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Uvs_Elementor_Floating_Mic_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'Floating Mic';
	}

	public function get_title()
	{
		return esc_html__('Voice Mic', 'universal-voice-search');
	}


	public function get_icon()
	{
		return 'fas fa-microphone';
	}


	public function get_categories()
	{
		return ['speak2web'];
	}

	public function get_keywords()
	{
		return ['mic', 'voice', 'speech'];
	}

	protected function register_controls()
	{

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Content', 'elementor-oembed-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__('Icon Color', 'textdomain'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .my-icon-wrapper' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_animation_color',
			[
				'label' => esc_html__('Icon Animation Color', 'textdomain'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .my-icon-animation-wrapper' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pulse_animation_color',
			[
				'label' => esc_html__('Pulse Animation Color', 'textdomain'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .my-icon-animation-wrapper .pulse-color' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__('Icon', 'textdomain'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-microphone',
					'library' => 'fa-solid',
				],
			],
		);

		$this->add_control(
			'width',
			[
				'label' => esc_html__('Icon Size', 'textdomain'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
						'step' => 5,
					],
					'%' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .floating-mic' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

	}

	protected function render()
	{

		$settings = $this->get_settings_for_display();
		$this->add_render_attribute(
			'wrapper',
			[
				'id' => 'flt-mic',
				'class' => 'floating-mic'
			]
		);
		?>
		<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
			<div class="my-icon-wrapper">
				<?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
			</div>
		</div>
		<?php

	}

	protected function content_template()
	{
		?>
		<# view.addRenderAttribute( 'wrapper' , { 'id' : 'custom-widget-id' , 'class' : [ 'elementor-tab-title' ,
			settings.floating-mic ], } ); var iconHTML=elementor.helpers.renderIcon( view, settings.selected_icon,
			{ 'aria-hidden' : true }, 'i' , 'object' ); #>
			<div class="my-icon-wrapper">
				{{{ iconHTML.value }}}
			</div>
			<?php
	}

}