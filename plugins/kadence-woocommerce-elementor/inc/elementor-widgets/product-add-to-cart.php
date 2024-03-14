<?php
/**
 * Build Elementor Element
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Elementor Add to cart widget
 */
class Product_Add_To_Cart_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-add-to-cart';
	}

	public function get_title() {
		return __( 'Product Add to Cart', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Add to Cart', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products add to cart form.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->add_control(
			'content_options',
			[
				'label' => __( 'Output Options', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'kadence-woocommerce-elementor' ),
					'hide_qty' => __( 'Hide Quantity Box', 'kadence-woocommerce-elementor' ),
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'kadence-woocommerce-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'kadence-woocommerce-elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button:hover, {{WRAPPER}} .single_add_to_cart_button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'placeholder' => '1px',
				'default' => '0',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} a.single_add_to_cart_button, {{WRAPPER}} .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_align',
			[
				'label'        => __( 'Alignment', 'kadence-woocommerce-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_quantity_style',
			[
				'label' => __( 'Quantity Input', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'qty_typography',
				'selector' => '{{WRAPPER}} .quantity input.qty',
			]
		);
		$this->start_controls_tabs( 'tabs_quantity_style' );

		$this->start_controls_tab(
			'tab_quantity_normal',
			[
				'label' => __( 'Normal', 'kadence-woocommerce-elementor' ),
			]
		);

		$this->add_control(
			'quantity_text_color',
			[
				'label' => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .quantity input.qty, {{WRAPPER}} .quantity' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_background_color',
			[
				'label' => __( 'Background Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .quantity input.qty, {{WRAPPER}} .quantity' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_quantity_hover',
			[
				'label' => __( 'Hover', 'kadence-woocommerce-elementor' ),
			]
		);

		$this->add_control(
			'quantity_hover_color',
			[
				'label' => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .quantity:hover input.qty, {{WRAPPER}} .quantity:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_background_hover_color',
			[
				'label' => __( 'Background Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .quantity:hover input.qty, {{WRAPPER}} .quantity:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'quantity_hover_border_color',
			[
				'label' => __( 'Border Color', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'quantity_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .quantity:hover input.qty' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'quantity_border',
				'placeholder' => '1px',
				'default' => '0',
				'selector' => '{{WRAPPER}} .quantity input.qty',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'quantity_border_radius',
			[
				'label' => __( 'Border Radius', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .quantity input.qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'quantity_box_shadow',
				'selector' => '{{WRAPPER}} .quantity input.qty',
			]
		);


		$this->add_responsive_control(
			'quantity_padding',
			[
				'label' => __( 'Padding', 'kadence-woocommerce-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .quantity input.qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}


	protected function render() {
		$post_type = get_post_type();
		$settings = $this->get_settings_for_display();
		if( 'hide_qty' == $settings['content_options'] ) {
			add_filter('woocommerce_quantity_input_args', 'kadence_woo_ele_hide_quantity');
		}
		if ( 'product' == $post_type ) {
			global $product;
			echo '<div class="entry-summary">';
			woocommerce_template_single_add_to_cart();
			echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {

	    	echo '<div class="woocommerce"><div class="product"><div class="entry-summary"><form class="cart">';
	    	if( 'hide_qty' != $settings['content_options'] ) {
		    	echo '<div class="quantity">
					<label class="screen-reader-text" for="quantity_5b199a6b7d6f6">Quantity</label>
					<input type="number" id="quantity_5b199a6b7d6f6" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="">
				</div>';
			}
			echo '<button type="submit" name="add-to-cart" value="null" class="single_add_to_cart_button button alt">Add to cart</button>
			</form></div></div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Product_Add_To_Cart_Element() );
