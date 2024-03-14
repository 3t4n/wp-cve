<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop;

class Catelog_Ordering extends \Shop_Ready\extension\elewidgets\Widget_Base
{


	protected function register_controls()
	{
		$this->box_css(
			array(
				'title' => esc_html__('Container', 'shopready-elementor-addon'),
				'slug' => 'wooready_products_grid_product_title_style',
				'element_name' => 's__wooready_products_grid_product_title_style',
				'selector' => '{{WRAPPER}} .woocommerce-ordering',
				'hover_selector' => false,
				'disable_controls' => array(
					'position',
					'display',
					'alignment',
					'bg',
					'size',
				),
			)
		);

		$this->text_css(
			array(
				'title' => esc_html__('Select', 'shopready-elementor-addon'),
				'slug' => 'wooready_products_grid_product_selcte_style',
				'element_name' => 's__wooready_products_grid_product_select_style',
				'selector' => '{{WRAPPER}} .woocommerce-ordering select',
				'hover_selector' => '{{WRAPPER}} .woocommerce-ordering select:hover',
				'disable_controls' => array(
					'position',
					'display',
				),
			)
		);
		$this->text_minimum_css(
			array(
				'title' => esc_html__('Select Option', 'shopready-elementor-addon'),
				'slug' => 'wooready_products_grid_product_selcte_option_style',
				'element_name' => 's__wooready_products_grid_product_select_option_style',
				'selector' => '{{WRAPPER}} .woocommerce-ordering select option',
				'hover_selector' => false,
				'disable_controls' => array(
					'display',
					'border',
				),
			)
		);
	}

	protected function html()
	{
		$settings = $this->get_settings_for_display();
		if (shop_ready_is_elementor_mode()) {
			wc_get_template(
				'loop/orderby.php',
				array(
					'catalog_orderby_options' => array(
						'menu_order' => esc_html__('Default sorting', 'shopready-elementor-addon'),
						'popularity' => esc_html__('Sort by popularity', 'shopready-elementor-addon'),
						'rating' => esc_html__('Sort by average rating', 'shopready-elementor-addon'),
						'date' => esc_html__('Sort by latest', 'shopready-elementor-addon'),
						'price' => esc_html__('Sort by price: low to high', 'shopready-elementor-addon'),
						'price-desc' => esc_html__('Sort by price: high to low', 'shopready-elementor-addon'),
					),
					'orderby' => 'ASC',
					'show_default_orderby' => 'popularity',
				)
			);
		} else {
			woocommerce_catalog_ordering();
		}

	}
}
