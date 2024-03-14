<?php

/**
 * Site_Tagline widget class
 *
 * @package Skt_Addons
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || die();

class Site_Tagline extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Site Tagline', 'skt-addons-elementor');
	}

	public function get_custom_help_url() {
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-tag';
	}

	public function get_keywords() {
		return ['site', 'tagline'];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_site_tagline',
			[
				'label' => __('Tagline', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => __('Icon', 'skt-addons-elementor'),
				'type'        => Controls_Manager::ICONS,
				'label_block' => 'true',
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => __('Icon Spacing', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-st-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label'              => __('Alignment', 'skt-addons-elementor'),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'    => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __('Justify', 'skt-addons-elementor'),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .skt-site-tagline' => 'text-align: {{VALUE}};',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__site_logo_style_controls();
	}


	protected function __site_logo_style_controls() {

		$this->start_controls_section(
			'_section_style_tagline',
			[
				'label' => __('Tagline', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tagline_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .skt-site-tagline',
			]
		);
		$this->add_control(
			'tagline_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-site-tagline' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-st-icon i'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-st-icon svg'     => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __('Icon Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => [
					'icon[value]!' => '',
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .skt-st-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-st-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
?>
		<div class="skt-site-tagline skt-site-tagline-wrapper">
			<?php if ('' !== $settings['icon']['value']) { ?>
				<span class="skt-st-icon">
					<?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
				</span>
			<?php } ?>
			<span>
				<?php echo wp_kses_post(get_bloginfo('description')); ?>
			</span>
		</div>
<?php
	}
}
