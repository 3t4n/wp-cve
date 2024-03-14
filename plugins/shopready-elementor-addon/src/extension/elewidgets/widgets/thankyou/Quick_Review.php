<?php

namespace Shop_Ready\extension\elewidgets\widgets\thankyou;

class Quick_Review extends \Shop_Ready\extension\elewidgets\Widget_Base
{

	public $wrapper_class = true;

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
				'default' => 'woo-ready-thankyou-order-preset-one',
				'options' => [
					'woo-ready-thankyou-order-preset-one' => esc_html__('Preset 1', 'shopready-elementor-addon'),
					'woo-ready-thankyou-order-preset-two' => esc_html__('Preset 2', 'shopready-elementor-addon'),
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
			'email',
			[
				'label' => esc_html__('Email?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);

		$this->add_control(
			'invoice',
			[
				'label' => esc_html__('Invoice?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);

		$this->add_control(
			'amount',
			[
				'label' => esc_html__('Amount?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'shopready-elementor-addon'),
				'label_off' => esc_html__('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);



		$this->end_controls_section();

		$this->start_controls_section(
			'table_content_section',
			[
				'label' => esc_html__('Table Heading', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'product_label',
			[
				'label' => esc_html__('Product Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Product', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'amount_label',
			[
				'label' => esc_html__('Amount Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Amount', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'invoice_label',
			[
				'label' => esc_html__('Invoice Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Invoice', 'shopready-elementor-addon'),
			]
		);

		$this->add_control(
			'email_label',
			[
				'label' => esc_html__('Email Label', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Email', 'shopready-elementor-addon'),
			]
		);

		$this->end_controls_section();


		$this->text_minimum_css(
			array(

				'title' => esc_html__('Label', 'shopready-elementor-addon'),
				'slug' => 'th_wr_desc_text_style',
				'element_name' => '_rt_edesc_woo_ready_',
				'selector' => '{{WRAPPER}} .wr-order-details .label',
				'hover_selector' => false,
			)
		);

		$this->text_minimum_css(
			array(
				'title' => esc_html__('Value', 'shopready-elementor-addon'),
				'slug' => 'sr_value_desc_text_style',
				'element_name' => '_we_value_woo_ready_',
				'selector' => '{{WRAPPER}} .wr-order-details .wr-value',
				'hover_selector' => false,
			)
		);

		$this->box_css(
			array(

				'title' => esc_html__('Inner Container', 'shopready-elementor-addon'),
				'slug' => 'werwrapper_inner_box_style',
				'element_name' => 'wrapper_inner_woows_ready_',
				'selector' => '{{WRAPPER}} .wr-order-details',
				'disable_controls' => ['position', 'size'],
			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Date', 'shopready-elementor-addon'),
				'slug' => 'wrapper_innertr_box_style_date',
				'element_name' => 'wrapper_innerserwoo_ready_date',
				'selector' => '{{WRAPPER}} .wr-order-details.wramount',
				'hover_selector' => false,
			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Amount', 'shopready-elementor-addon'),
				'slug' => 'wrapper_innerpp_box_style_date',
				'element_name' => 'wrapper_inneruuuwoo_ready_date',
				'selector' => '{{WRAPPER}} .wr-order-details.wramount',
				'hover_selector' => false,
			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Email', 'shopready-elementor-addon'),
				'slug' => 'wrapper_inner_boxpowt_style_email',
				'element_name' => 'wrapper_inner_woowqet_ready_email',
				'selector' => '{{WRAPPER}} .wr-order-details.wremail',
				'hover_selector' => false,

			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Product', 'shopready-elementor-addon'),
				'slug' => 'wrapper_inner_rewrebox_producty_style',
				'element_name' => 'wrapper_inner_wooere_ready_',
				'selector' => '{{WRAPPER}} .wr-order-details.wrproduct',
				'hover_selector' => false,
			)
		);

		$this->box_css(
			array(

				'title' => esc_html__('Main Wrapper', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_qwlkbox_style',
				'element_name' => 'wrapper_body_rtyielement_ready_',
				'selector' => '{{WRAPPER}} .wr-thankyou-container',
				'disable_controls' => ['position', 'size'],
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

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-thankyou-review', $settings['preset']],
			]
		);


		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/quick_review/' . $settings['preset'] . '.php')) {

			shop_ready_widget_template_part(
				'thankyou/template-parts/quick_review/' . $settings['preset'] . '.php',
				array(
					'settings' => $settings,
					'order' => $order,
					'order_id' => $order_id,
				)
			);
		} else {

			shop_ready_widget_template_part(
				'thankyou/template-parts/quick_review/woo-ready-thankyou-order-preset-one.php',
				array(
					'settings' => $settings,
					'order' => $order,
					'order_id' => $order_id,
				)
			);
		}

		echo wp_kses_post('</div>');
	}
}