<?php

/**
 * Image grid widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined('ABSPATH') || die();

class Image_Hover_Effect extends Base {

	/**
	 * Default filter is the global filter
	 * and can be overriden from settings
	 *
	 * @var string
	 */

	public function get_title() {
		return __('Image Hover Effect', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-cursor-hover-click';
	}

	public function get_keywords() {
		return ['hover', 'image', 'effect'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_image_content',
			[
				'label' => __('Image Content', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'hover_image',
			[
				'label' => __('Image', 'skt-addons-elementor'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true
				],
			]
		);

		$this->add_control(
			'hover_image_alt_tag',
			[
				'label' => __('Image ALT Tag', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Image hover effect image', 'skt-addons-elementor'),
				'placeholder' => __('Type here image alt tag value', 'skt-addons-elementor'),
				'dynamic' => ['active' => true,],
			]
		);

		$this->add_control(
			'hover_title',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXTAREA,
				'description' => skt_addons_elementor_get_allowed_html_desc( 'intermediate' ),
				'rows' => 3,
				'default' => __('SKT <span>Addons</span>', 'skt-addons-elementor'),
				'placeholder' => __('Type your title here', 'skt-addons-elementor'),
				'dynamic' => ['active' => true],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				// 'separator' => 'before',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'hover_description',
			[
				'label' => __('Description', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __('Best Elementor Addons', 'skt-addons-elementor'),
				'placeholder' => __('Type your description here', 'skt-addons-elementor'),
				'condition' => [
					'hover_effect!' => 'skt-effect-honey',
				],
				'dynamic' => ['active' => true],
			]
		);

		$this->add_control(
			'hover_link',
			[
				'label' => __('Link URL', 'skt-addons-elementor'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://your-link.com', 'skt-addons-elementor'),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'dynamic' => ['active' => true],
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __('Hover Effect', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'skt-effect-apollo'  => __('Apollo', 'skt-addons-elementor'),
					'skt-effect-bubba'  => __('Bubba', 'skt-addons-elementor'),
					'skt-effect-chico'  => __('Chico', 'skt-addons-elementor'),
					'skt-effect-dexter'  => __('Dexter', 'skt-addons-elementor'),
					'skt-effect-duke'  => __('Duke', 'skt-addons-elementor'),
					'skt-effect-goliath'  => __('Goliath', 'skt-addons-elementor'),
					'skt-effect-honey'  => __('Honey', 'skt-addons-elementor'),
					'skt-effect-jazz'  => __('Jazz', 'skt-addons-elementor'),
					'skt-effect-layla'  => __('Layla', 'skt-addons-elementor'),
					'skt-effect-lexi'  => __('Lexi', 'skt-addons-elementor'),
					'skt-effect-lily'  => __('Lily', 'skt-addons-elementor'),
					'skt-effect-marley'  => __('Marley', 'skt-addons-elementor'),
					'skt-effect-milo'  => __('Milo', 'skt-addons-elementor'),
					'skt-effect-ming'  => __('Ming', 'skt-addons-elementor'),
					'skt-effect-moses'  => __('Moses', 'skt-addons-elementor'),
					'skt-effect-oscar'  => __('Oscar', 'skt-addons-elementor'),
					'skt-effect-romeo'  => __('Romeo', 'skt-addons-elementor'),
					'skt-effect-roxy'  => __('Roxy', 'skt-addons-elementor'),
					'skt-effect-ruby'  => __('Ruby', 'skt-addons-elementor'),
					'skt-effect-sadie'  => __('Sadie', 'skt-addons-elementor'),
					'skt-effect-sarah'  => __('Sarah', 'skt-addons-elementor'),
				],
				'default' => 'skt-effect-apollo',
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__overlay_style_controls();
	}

	protected function __common_style_controls() {

		$this->start_controls_section(
			'_section_common_style',
			[
				'label' => __('Common', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_container_height_width_control',
			[
				'label' => __('Container Max Width?', 'skt-addons-elementor'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'skt-addons-elementor'),
				'label_off' => __('No', 'skt-addons-elementor'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'hover_width',
			[
				'label' => __('Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 5,
					],
				],
				'devices' => ['desktop', 'tablet', 'mobile'],
				'desktop_default' => [
					'size' => 480,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 480,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}}{{UNIT}}/1.34);',
				],
				'condition' => [
					'hover_container_height_width_control' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'hover_border',
				'label' => __('Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig',
			]
		);

		$this->add_control(
			'hover_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __('Title Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-title',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_family' => [
						'default' => 'Roboto',
					],
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typo',
				'label' => __('Description Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-desc',
				'fields_options' => [
					'typography' => ['default' => 'yes'],
					'font_family' => [
						'default' => 'Roboto',
					],
				],
			]
		);

		$this->start_controls_tabs('_tabs_style');

		$this->start_controls_tab(
			'_tab_normal',
			[
				'label' => __('Normal', 'skt-addons-elementor'),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Title Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-title' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-title::before' => '--skt-ihe-title-before-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-title::after' => '--skt-ihe-title-after-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-caption::before' => '--skt-ihe-fig-before-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-caption::after' => '--skt-ihe-fig-after-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Description Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig .skt-ihe-desc' => 'color: {{VALUE}}; --skt-ihe-desc-border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_hover',
			[
				'label' => __('Hover', 'skt-addons-elementor'),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __('Title Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-title' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-title::before' => '--skt-ihe-title-before-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-title::after' => '-skt-ihe-title-after-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-caption::before' => '--skt-ihe-fig-before-color: {{VALUE}};',
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-caption::after' => '--skt-ihe-fig-after-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'description_hover_color',
			[
				'label' => __('Description Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover .skt-ihe-desc' => 'color: {{VALUE}}; --skt-ihe-desc-border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __overlay_style_controls() {

		$this->start_controls_section(
			'_section_overlay_style',
			[
				'label' => __('Background Overlay', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('_tabs_overlay_style');
		$this->start_controls_tab(
			'_tab_overlay_normal',
			[
				'label' => __('Normal', 'skt-addons-elementor'),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_overlay_normal',
				'label' => __('Background', 'skt-addons-elementor'),
				'show_label' => true,
				'types' => ['classic', 'gradient'],
				'exclude' => [
					'classic' => 'image'
				],
				'selector' => '{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig, {{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig.skt-effect-sadie .skt-ihe-caption::before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_overlay_hover',
			[
				'label' => __('Hover', 'skt-addons-elementor'),
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'hover_overlay_hover',
				'label' => __('Background', 'skt-addons-elementor'),
				'show_label' => true,
				'types' => ['classic', 'gradient'],
				'exclude' => [
					'classic' => 'image'
				],
				'selector' => '{{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig:hover, {{WRAPPER}} .skt-ihe-wrapper .skt-ihe-fig.skt-effect-sadie:hover .skt-ihe-caption::before',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$url_target = $settings['hover_link']['is_external'] ? ' target="_blank"' : '';
		$url_nofollow = $settings['hover_link']['nofollow'] ? ' rel="nofollow"' : '';
?>
		<div class="skt-ihe-wrapper grid">
			<figure class="skt-ihe-fig <?php echo esc_attr($settings['hover_effect']); ?>">
				<img class="skt-ihe-img" src="<?php echo esc_url($settings['hover_image']['url']); ?>" alt="<?php echo esc_attr($settings['hover_image_alt_tag']); ?>" />
				<figcaption class="skt-ihe-caption">
					<?php if ($settings['hover_effect'] == 'skt-effect-lily') : ?>
						<div>
						<?php endif; ?>
						<?php
						printf( '<%1$s class="skt-ihe-title">%2$s</%1$s>',
							skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
							skt_addons_elementor_kses_intermediate($settings['hover_title'])
						);
						?>
						<?php if ($settings['hover_effect'] != 'skt-effect-honey') : ?>
							<p class="skt-ihe-desc"><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate($settings['hover_description'])); ?></p>
						<?php endif; ?>
						<?php if ($settings['hover_effect'] == 'skt-effect-lily') : ?>
						</div>
					<?php endif; ?>
					<?php if ($settings['hover_link']['url'] != '') : ?>
						<a href="<?php echo esc_url($settings['hover_link']['url']); ?>" <?php echo esc_attr($url_target . $url_nofollow); ?>></a>
					<?php endif; ?>
				</figcaption>
			</figure>
		</div>
<?php
	}
}