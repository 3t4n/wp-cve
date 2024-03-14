<?php
namespace Shop_Ready\extension\elewidgets\widgets\thankyou;

class Customer_Address extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	public $wrapper_class = false;

	protected function register_controls()
	{

		// Notice 
		$this->start_controls_section(
			'notice_content_section',
			[
				'label' => esc_html__('Notice', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			[
				'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Use This Widget in WooCommerce Thank you page', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-account-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_content_section',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'preset',
			[
				'label' => esc_html__('Preset', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'order-details-customer-address',
				'options' => [
					'order-details-customer-address' => esc_html__('Billing', 'shopready-elementor-addon'),
					'order-details-customer-shipping' => esc_html__('Shipping', 'shopready-elementor-addon'),

				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'setting_content_section',
			[
				'label' => esc_html__('Settings', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => esc_html__('Heading', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Shipping', 'shopready-elementor-addon'),
				'condition' => [
					'preset' => ['order-details-customer-shipping']
				]
			]
		);

		$this->add_control(
			'shiping_default_content',
			[
				'label' => esc_html__('Shipping Default Content', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('n/a', 'shopready-elementor-addon'),
				'condition' => [
					'preset' => ['order-details-customer-shipping']
				]
			]
		);


		$this->end_controls_section();



		$this->text_minimum_css(
			array(

				'title' => esc_html__('Heading', 'shopready-elementor-addon'),
				'slug' => '_wr_desc_text_style',
				'element_name' => '_rt_edesc_woo_ready_',
				'selector' => '{{WRAPPER}} .woo-ready-shipping-address',
				'hover_selector' => false,
				'condition' => [

					'preset' => ['order-details-customer-shipping']
				]
			)
		);


		$this->text_css(
			array(

				'title' => esc_html__('Shipping Container', 'shopready-elementor-addon'),
				'slug' => 'wrapper_shipping_box_style',
				'element_name' => 'wrapper_ship_woo_ready_',
				'selector' => '{{WRAPPER}} .woo-ready-shipping',
				'hover_selector' => false,
				'condition' => [

					'preset' => ['order-details-customer-shipping']
				]

			)
		);

		$this->text_css(

			array(

				'title' => esc_html__('Billing Container', 'shopready-elementor-addon'),
				'slug' => 'wrapper_billing_box_style',
				'element_name' => 'wrapper_billing_woo_ready_',
				'selector' => '{{WRAPPER}} .woo-ready-billing-address',
				'hover_selector' => false,
				'condition' => [
					'preset' => ['order-details-customer-address']
				]

			)

		);
		$this->text_minimum_css(
			array(

				'title' => esc_html__('Email', 'shopready-elementor-addon'),
				'slug' => 'wrapper_email_style',
				'element_name' => 'wrapper_email_ready_',
				'selector' => '{{WRAPPER}} .woocommerce-customer-details--email',
				'hover_selector' => false,

			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Phone', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_phone_style',
				'element_name' => 'wrapper_body_phone_',
				'selector' => '{{WRAPPER}} .woocommerce-customer-details--phone',
				'hover_selector' => false,

			)
		);



	}

	public function get_order()
	{

		if (shop_ready_is_elementor_mode()) {

			$customer = new \WC_Customer(get_current_user_id());
			$demo_order = $customer->get_last_order();
			return $demo_order ? $demo_order->get_order_key() : false;
		} else {

			if (!isset($_REQUEST['key'])) {

				return false;
			}

			return sanitize_text_field($_REQUEST['key']);
		}


	}

	public function proceed()
	{

		if (shop_ready_is_elementor_mode()) {
			return true;
		}

		if (is_wc_endpoint_url('order-received')) {
			return true;
		}

		return false;
	}

	protected function html()
	{

		$settings = $this->get_settings_for_display();
		$order_key = $this->get_order();

		if (!$order_key) {
			return false;
		}

		if (!$this->proceed()) {
			return;
		}

		$order_key = sanitize_text_field($order_key);
		$order_id = wc_get_order_id_by_order_key($order_key);

		if ($order_id < 1) {
			return;
		}

		$order = wc_get_order($order_id);
		$show_shipping = !wc_ship_to_billing_address_only();
		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-thankyou-address', $settings['preset']],
			]
		);


		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/customer/' . $settings['preset'] . '.php')) {

			shop_ready_widget_template_part(
				'thankyou/template-parts/customer/' . $settings['preset'] . '.php',
				array(
					'settings' => $settings,
					'order' => $order,
					'order_id' => $order_id,
					'heading' => $settings['heading'],
					'shiping_default_content' => $settings['shiping_default_content'],
					'show_shipping' => $show_shipping
				)
			);

		} else {

			shop_ready_widget_template_part(
				'thankyou/template-parts/customer/order-details-customer-address.php',
				array(
					'settings' => $settings,
					'order' => $order,
					'order_id' => $order_id,
					'heading' => $settings['heading'],
					'shiping_default_content' => $settings['shiping_default_content'],
					'show_shipping' => $show_shipping
				)
			);

		}

		echo wp_kses_post('</div>');
	}


}