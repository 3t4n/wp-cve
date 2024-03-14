<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Thim_EL_Kit\Utilities\Widget_Loop_Trait;

defined( 'ABSPATH' ) || exit;

class Thim_Ekit_Widget_Loop_Product_Price extends Widget_Base {

	use Widget_Loop_Trait;

	public function get_name() {
		return 'thim-loop-product-price';
	}

	public function show_in_panel() {
		$post_type = get_post_meta( get_the_ID(), 'thim_loop_item_post_type', true );
		if ( ! empty( $post_type ) && $post_type == 'product' ) {
			return true;
		}

		return false;
	}

	public function get_title() {
		return esc_html__( 'Product Price', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-price';
	}

	public function get_keywords() {
		return array( 'button', 'add to cart' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Price', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
 		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER }} .woocommerce-Price-amount' => 'color: {{VALUE}}'
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER }} .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'price_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER }} .price del' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_sale_price_style',
			array(
				'label'     => esc_html__( 'Sale Price', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER }} del .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sale_price_typography',
				'selector' => '{{WRAPPER }} del .woocommerce-Price-amount',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$product = wc_get_product( false );

		if ( ! $product ) {
			return;
		}
		woocommerce_template_loop_price();
	}

	public function render_plain_content() {
	}
}
