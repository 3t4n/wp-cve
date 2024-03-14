<?php

namespace Elementor;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Accordion extends Widget_Base {
	public function get_name() {
		return 'thim-ekits-accordion';
	}

	public function get_title() {
		return esc_html__( 'Accordion', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-accordion';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'tab',
			'tabs',
		];
	}

	protected function register_controls() {
		/**
		 * Advance Accordion Content Settings
		 */
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'acc_title',
			[
				'label'   => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Accordion Title', 'thim-elementor-kit' ),
			]
		);

		$repeater->add_control(
			'acc_content',
			[
				'label'   => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vehicula lacus nibh, quis egestas ex tincidunt eu. Nunc et sem luctus, ultricies orci sit amet, gravida dui.', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => [
					[ 'acc_title' => esc_html__( 'Accordion Title 1', 'thim-elementor-kit' ) ],
					[ 'acc_title' => esc_html__( 'Accordion Title 2', 'thim-elementor-kit' ) ],
					[ 'acc_title' => esc_html__( 'Accordion Title 3', 'thim-elementor-kit' ) ],
				],
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{acc_title}}',
			]
		);

		$this->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
				'default'     => [
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid'   => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'icon_active',
			[
				'label'       => esc_html__( 'Active Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid'   => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin'        => 'inline',
				'condition'   => [
					'icon[value]!' => '',
				],
			]
		);
		$this->add_control(
			'faq_schema',
			[
				'label'     => esc_html__( 'FAQ Schema', 'elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->register_controls_style_item();

		$this->register_controls_style_title();

		$this->register_controls_style_content();

	}

	protected function register_controls_style_item() {

		$this->start_controls_section(
			'item_settings',
			[
				'label' => esc_html__( 'Item', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_space',
			[
				'label'      => __( 'Space', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-accordion-sections .accordion-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-accordion-sections .accordion-section',
			]
		);

		$this->end_controls_section();

	}

	protected function register_controls_style_title() {

		$this->start_controls_section(
			'title_settings',
			[
				'label' => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .accordion-section .accordion-title',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-title i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .accordion-section .accordion-title img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .accordion-section .accordion-title svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'acc_padding',
			[
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'acc_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'acc_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'acc_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .accordion-section .accordion-title',
				'exclude'=> ['color']
			]
		);

		$this->start_controls_tabs( 'tabs_header_tabs' );
		// Normal State Tab
		$this->start_controls_tab( 'tabs_header_normal', [ 'label' => esc_html__( 'Normal', 'thim-elementor-kit' ) ] );
		$this->add_control(
			'acc_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}}  .accordion-section .accordion-title' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'acc_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'acc_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title i'    => 'color: {{VALUE}};',
					'{{WRAPPER}}  .accordion-section .accordion-title svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'acc_icon_show' => 'yes',
				],
			]
		);
		$this->add_control(
			'acc_border',
			[
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title:hover,{{WRAPPER}} .accordion-section .accordion-title[aria-selected=true]' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 'acc_border_border!' => '' ],
			]
		);

		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab( 'tabs_header_hover', [ 'label' => esc_html__( 'Active', 'thim-elementor-kit' ) ] );
		$this->add_control(
			'acc_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title:hover,{{WRAPPER}} .accordion-section .accordion-title[aria-selected=true]' => 'background-color: {{VALUE}};',
 				],
			]
		);

		$this->add_control(
			'acc_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title:hover,{{WRAPPER}} .accordion-section .accordion-title[aria-selected=true]' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'acc_icon_color_hover',
			[
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title:hover >i,{{WRAPPER}} .accordion-section .accordion-title[aria-selected=true] > i'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .accordion-section .accordion-title:hover > svg,{{WRAPPER}} .accordion-section .accordion-title[aria-selected=true] > svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'acc_icon_show' => 'yes',
				],
			]
		);
		$this->add_control(
			'acc_border_hover',
			[
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-title' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 'acc_border_border!' => '' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	protected function register_controls_style_content() {
		/**
		 * -------------------------------------------
		 * Tab Style Advance Accordion Content Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'content_style_settings',
			[
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .accordion-section .accordion-content' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .accordion-section .accordion-content',
			]
		);
		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .accordion-section .accordion-content',
 			]
		);
		$this->add_responsive_control(
			'content_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion-section .accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'content_shadow',
				'selector'  => '{{WRAPPER}} .accordion-section .accordion-content',
				'separator' => 'before',
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$settings      = $this->get_settings_for_display();
		$prefix_acc_id = 'item-acc-' . $this->get_id() . '-';
		$this->add_render_attribute(
			'thim_acc_wrapper',
			[
				'id'    => "thim-accordions-{$this->get_id()}",
				'class' => [ 'thim-ekit-tablist'],
			]
		);
		?>

		<div <?php echo $this->get_render_attribute_string( 'thim_acc_wrapper' ); ?>>
			<div class="thim-accordion-sections" role="tablist">
				<?php
				foreach ( $settings['tabs'] as $index => $tab ) :
					$acc_count = $index + 1;
					$acc_title_setting_key = $this->get_repeater_setting_key( 'acc_title', 'tab', $index );
					$this->add_render_attribute( $acc_title_setting_key, [
						'class'         => 'accordion-title',
						'aria-selected' => 1 === $acc_count ? 'true' : 'false',
						'role'          => 'tab',
						'tabindex'      => 1 === $acc_count ? '0' : '-1',
						'aria-controls' => $prefix_acc_id . $acc_count,
					] );
					?>

					<div class="accordion-section">
						<div <?php $this->print_render_attribute_string( $acc_title_setting_key ); ?>>
							<?php
							echo $tab['acc_title'];

							if ( ! empty( $settings['icon'] ) || ! empty( $settings['icon_active'] ) ) {
								?>
								<span class="accordion-icon">
									<span
										class="accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['icon'] ); ?></span>
									<span
										class="accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['icon_active'] ); ?></span>
								</span>
							<?php } ?>
						</div>

						<div class="accordion-content" id="<?php echo esc_attr( $prefix_acc_id . $acc_count ); ?>"
							 role="tabpanel"
							 aria-labelledby="<?php echo esc_attr( $prefix_acc_id . $acc_count ); ?>"
							 tabindex="<?php echo esc_attr( 1 === $acc_count ? '0' : '-1' ); ?>" <?php echo esc_attr( 1 != $acc_count ? 'hidden' : '' ); ?>>
							<?php echo $tab['acc_content']; ?>
						</div>

					</div>

				<?php endforeach; ?>
			</div>
			<?php
			if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
				$json = [
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => [],
				];

				foreach ( $settings['tabs'] as $index => $item ) {
					$json['mainEntity'][] = [
						'@type'          => 'Question',
						'name'           => wp_strip_all_tags( $item['acc_title'] ),
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => $this->parse_text_editor( $item['acc_content'] ),
						],
					];
				}
				?>
				<script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
			<?php } ?>
		</div>
	<?php }
}
