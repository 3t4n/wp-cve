<?php
namespace Elementor;

class Widget_EP_Pricing_list extends Widget_Base {

	public function get_name() {
		return 'ep-pricing-list';
	}

	public function get_title() {
		return __( 'Pricing List Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-dollar-currency';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Element Content', 'elements-plus' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_title',
			[
				'label'       => __( 'Title', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Item Title', 'elements-plus' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_price',
			[
				'label'       => __( 'Price', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Item Price', 'elements-plus' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'item_content',
			[
				'label'       => __( 'Content', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => __( 'List item description', 'elements-plus' ),
				'show_label'  => false,
			]
		);

		$repeater->add_control(
			'item_image',
			[
				'label'   => __( 'Choose Image', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'thumbnail',
			]
		);

		$this->add_control(
			'pricing_list',
			[
				'label'       => __( 'Pricing List', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
			]
		);

		$this->add_control(
			'image_appearance',
			[
				'label'   => __( 'Image Appearance', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => __( 'Left', 'elements-plus' ),
					'right' => __( 'Right', 'elements-plus' ),
					'none'  => __( 'None', 'elements-plus' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'elements-plus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-pricing-list-item-thumb img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ep-pricing-list-item-title' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ep-pricing-list-item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'title_border',
				'label'    => __( 'Border', 'elements-plus' ),
				'selector' => '{{WRAPPER}} .ep-pricing-list-item-title',
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ep-pricing-list-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'elements-plus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ep-pricing-list-item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ep-pricing-list-item-title .ep-pricing-list-item-price' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ep-pricing-list-item-title .ep-pricing-list-item-price',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description_style',
			[
				'label' => __( 'Description Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ep-pricing-list-item-description' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ep-pricing-list-item-description',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings();
		$pricing_list = $settings['pricing_list'];

		if ( ! $pricing_list ) {
			return;
		}

		?>
		<div class="ep-pricing-list">
		<?php
		foreach ( $pricing_list as $list_item ) {
			?>
			<div class="ep-pricing-list-item ep-image-<?php echo esc_attr( $settings['image_appearance'] ); ?>">
				<?php if ( 'none' !== $settings['image_appearance'] ) : ?>
					<div class="ep-pricing-list-item-thumb ep-image-<?php echo esc_attr( $settings['image_appearance'] ); ?>">
						<?php echo wp_get_attachment_image( $list_item['item_image']['id'], $list_item['image_size'] ); ?>
					</div>
				<?php endif; ?>
				<div class="ep-pricing-list-item-content">
					<p class="ep-pricing-list-item-title">
						<?php echo $list_item['item_title']; ?>
						<?php if ( $list_item['item_price'] ) : ?>
							<span class="ep-pricing-list-item-price">
								<?php echo $list_item['item_price']; ?>
							</span>
						<?php endif; ?>
					</p>
					<?php if ( $list_item['item_content'] ) : ?>
						<p class="ep-pricing-list-item-description">
							<?php echo $list_item['item_content']; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
		?>
		</div>
		<?php

	}

}

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_EP_Pricing_list() );
	}
);
