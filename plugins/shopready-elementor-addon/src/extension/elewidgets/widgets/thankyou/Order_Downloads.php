<?php

namespace Shop_Ready\extension\elewidgets\widgets\thankyou;

class Order_Downloads extends \Shop_Ready\extension\elewidgets\Widget_Base
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
				'default' => 'woo-ready-order-download-one',
				'options' => [
					'woo-ready-order-download-one' => esc_html__('Preset 1', 'shopready-elementor-addon'),
					//'woo-ready-thankyou-order-details-two'  => esc_html__( 'Preset 2', 'shopready-elementor-addon' ),
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
			'show_heading',
			[
				'label' => __('Show Heading?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',

			]
		);


		$this->end_controls_section();

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Heading', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_head',
				'element_name' => 'wrapper_body_heading',
				'selector' => '{{WRAPPER}} .woocommerce-order-downloads__title',
				'hover_selector' => false

			)
		);

		$this->text_minimum_css(
			array(
				'title' => esc_html__('Table Heading', 'shopready-elementor-addon'),
				'slug' => '_time_desc_text_style',
				'element_name' => '_time_edesc_woo_ready_',
				'selector' => '{{WRAPPER}} .woocommerce-table--order-downloads tr th',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
				]
			)
		);

		$this->text_minimum_css(
			array(
				'title' => esc_html__('Value', 'shopready-elementor-addon'),
				'slug' => '_value_desc_text_style',
				'element_name' => '_time_value_element_ready_',
				'selector' => '{{WRAPPER}} tr td, {{WRAPPER}} tr td a',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
				]
			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Table', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_box_style',
				'element_name' => 'wrapper_body_element_ready_',
				'selector' => '{{WRAPPER}} .woocommerce-table--order-downloads',
				'hover_selector' => false,
				'disable_controls' => [
					'display',
					'dimensions'
				]
			)
		);

		$this->box_minimum_css(
			array(

				'title' => esc_html__('Table Row', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_box_row_style',
				'element_name' => 'wrapper_body_worw_',
				'selector' => '{{WRAPPER}} .woocommerce-table--order-downloads tr',
				'disable_controls' => [
					'display',
					'alignment'
				]
			)
		);

		$this->box_minimum_css(
			array(

				'title' => esc_html__('Table Col', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_box_col_style',
				'element_name' => 'wrapper_body_worw_',
				'selector' => '{{WRAPPER}} .woocommerce-table--order-downloads tr td, {{WRAPPER}} .woocommerce-table--order-downloads tr th',
				'disable_controls' => [
					'display',
				]
			)
		);

		$this->text_minimum_css(
			array(

				'title' => esc_html__('Download Button', 'shopready-elementor-addon'),
				'slug' => 'wrapper_body_box_down_btn',
				'element_name' => 'wrapper_body_worw_btn',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-downloads-file',
				'hover_selector' => 'body {{WRAPPER}} table tr a.woocommerce-MyAccount-downloads-file:hover',
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
				'class' => ['woo-ready-thankyou-download', $settings['preset']],
			]
		);


		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/order_downloads/' . $settings['preset'] . '.php')) {

			shop_ready_widget_template_part(
				'thankyou/template-parts/order_downloads/' . $settings['preset'] . '.php',
				array(
					'settings' => $settings,
					'order' => $order,
					'order_id' => $order_id,
				)
			);
		} else {

			shop_ready_widget_template_part(
				'thankyou/template-parts/order_downloads/woo-ready-order-download-one.php',
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