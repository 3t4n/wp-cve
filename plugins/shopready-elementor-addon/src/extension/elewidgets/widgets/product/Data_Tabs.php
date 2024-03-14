<?php

namespace Shop_Ready\extension\elewidgets\widgets\product;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

/**
 * WooCommerce Product Tabs
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Data_Tabs extends \Shop_Ready\extension\elewidgets\Widget_Base
{
	/**
	 * Html Wrapper Class of html 
	 */
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
				'label'           => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__('Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-product-page-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_contentss_section',
			[
				'label' => esc_html__('Editor Refresh', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$products = shop_ready_get_latest_products_id(50);

		$this->add_control(
			'wready_product_id',
			[
				'label'   => esc_html__('Demo Product', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default' => isset($products[0]) ? key($products[0]) : '',
				'options' => $products
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layouts_product_data_tabs_section',
			[
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__('Layout', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					//'tabs'     => esc_html__('Style 1', 'shopready-elementor-addon'),
					'default'     => esc_html__('Default', 'shopready-elementor-addon'),
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'content_rating_section',
			[
				'label' => esc_html__('Settings', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_tab_menu',
			[
				'label'        => __('Tab Menu?', 'shopready-elementor-addon'),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __('Show', 'shopready-elementor-addon'),
				'label_off'    => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default'      => 'yes',

			]
		);

		$this->add_control(
			'hide_tabs',
			[
				'label'    => __('Hide Tabs', 'shopready-elementor-addon'),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => $this->get_tab_option(),
				'default'  => [],
			]
		);



		$this->end_controls_section();


		/**
		 * Layouts
		 */
		$this->box_layout(
			[
				'title'          => esc_html__('Container Wrapper', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc__product_tabs_wrapper',
				'element_name'   => '__wrapper_container',
				'selector'       => '{{WRAPPER}} .wc-tabs-wrapper',
				'disable_controls' => [
					'position', 'box-size',
				],
			]
		);

		$this->box_layout(
			[
				'title'          => esc_html__('Tab Menu', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_default_product_tab_menu',
				'element_name'   => 'star_wrapper_tab_menu_layout',
				'selector'       => '{{WRAPPER}} .wc-tabs-wrapper .wc-tabs',
				'disable_controls' => [
					'position', 'box-size',
				],
			]
		);

		$this->box_layout(
			[
				'title'          => esc_html__('Tab Content', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_default_product_tab_content',
				'element_name'   => 'star_wrapper_tab_content_layout',
				'selector'       => '{{WRAPPER}} .wready-product-tab-content',
				'disable_controls' => [
					'position', 'box-size',
				],
			]
		);

		$this->box_layout(
			[
				'title'          => esc_html__('Tab Panel', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_default_product_tab_panel',
				'element_name'   => 'star_wrapper_panel_layout',
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel',
				'disable_controls' => [
					'position', 'box-size',
				],
			]
		);

		/* Layouts End */


		/* Style Tab starts */
		// $this->box_css(
		// 	[
		// 		'title'          => esc_html__('Tab List', 'shopready-elementor-addon'),
		// 		'slug'           => 'wready_wc_product_tab_menu_csss_list',
		// 		'element_name'   => 'wrating_product_menus_li_list',
		// 		'selector'       => 'body.single-product {{WRAPPER}} .woo-ready-product-tabs-layout.tabs ul.tabs li',
		// 		'hover_selector' => 'body.single-product {{WRAPPER}} .woo-ready-product-tabs-layout.tabs ul.tabs li:hover',
		// 		'disable_controls' => [
		// 			'position', 'size', 'alignment', 'display',
		// 		],

		// 	]
		// );

		$this->text_minimum_css(
			[
				'title'          => esc_html__('Tab menu', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_product_tab_menu_css',
				'element_name'   => 'wrating_product_menu_li',
				'selector'       => '{{WRAPPER}} .wc-tabs-wrapper .wc-tabs li a',
				'hover_selector' => '{{WRAPPER}} .wc-tabs-wrapper .wc-tabs li a:hover',
				'disable_controls' => [
					'display',
				],
			]
		);

		$this->start_controls_section(
			'yab_menu_active_content_section',
			[
				'label' => __('Active Tab Style', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'active_tab_menu_title_color',
			[
				'label' => __('Menu Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce div.product {{WRAPPER}} .woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woo-ready-product-tabs-layout .woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'active_tab_menu_title_border',
				'label' => __('Active Border', 'shopready-elementor-addon'),
				'selector' => '{{WRAPPER}} .woo-ready-product-tabs-layout .woocommerce-tabs ul.tabs li.active a',
			]
		);

		$this->add_control(
			'active_tab_menu_active_borderdd_color',
			[
				'label' => __('Border Active Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce div.product {{WRAPPER}} .woocommerce-tabs ul.tabs li.active a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woo-ready-product-tabs-layout .woocommerce-tabs ul.tabs li.active a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tab_menu_additional_content_section',
			[
				'label' => __('Additional Tab Content', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_menu_additional_table_heading_color',
			[
				'label' => __('Heading Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} table.shop_attributes th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tab_menu_additional_table_heading__typography',
				'label' => __('Heading Typography', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} table.shop_attributes th',
			]
		);

		$this->add_control(
			'tab_menu_additional_table_value_color',
			[
				'label' => __('Value Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} table.shop_attributes td p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tab_menu_additional_table_value__typography',
				'label' => __('Value Typography', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} table.shop_attributes td p',
			]
		);

		$this->add_control(
			'tab_menu_additional_table_padding',
			[
				'label' => __('Row Padding', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'.woocommerce {{WRAPPER}} table.shop_attributes th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.woocommerce {{WRAPPER}} table.shop_attributes td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'active_tab_menu_row_border',
				'label' => __(' Border', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} table.shop_attributes td, .woocommerce {{WRAPPER}} table.shop_attributes th',
			]
		);

		$this->add_control(
			'tab_menu_additional_table_margin',
			[
				'label' => __('Table margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'.woocommerce {{WRAPPER}} table.shop_attributes' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		$this->end_controls_section();



		/** Tab Content style */
		$this->text_minimum_css(
			[
				'title'          => esc_html__('Tab Content Title', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_product_content_title',
				'element_name'   => 'wrating_product_content_tile',
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel h2',
				'hover_selector' => false,
				'disable_controls' => [
					'border', 'bg', 'display'
				],

			]
		);

		$this->text_minimum_css(
			[
				'title'          => esc_html__('Tab Content', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_product_content_desc',
				'element_name'   => 'wrating_product_content_desc',
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel,{{WRAPPER}} .wready-product-tab-content',
				'hover_selector' => false,
			]
		);



		/* Review Styles */
		$this->start_controls_section(
			'tab_menu_additional_review_section',
			[
				'label' => __('Review Style', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_menu_review_form_textarea_margin',
			[
				'label' => __('TextArea margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .comment-form-comment textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'tab_menu_review_form_textarea__value_color',
			[
				'label' => __('Textarea Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} .comment-form-comment textarea' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'tab_menu_review_form_textarea_row_border',
				'label' => __('Textarea Border', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} .comment-form-comment textarea, .woocommerce-Tabs-panel--reviews #review_form_wrapper #respond .comment-form-comment
				textarea',
			]
		);

		$this->add_responsive_control(
			'tab_menu_review_form_textarea_row_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .woocommerce-Tabs-panel--reviews #review_form_wrapper #respond .comment-form-comment textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		// rating

		$this->add_control(
			'tab_menu_review_form_ratin_value_color',
			[
				'label' => __('Rating label Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} .comment-form-rating label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_menu_review_form_reply_rating_label_margin',
			[
				'label' => __('Rating Label margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .comment-form-rating label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'tab_menu_review_form_reply_value_color',
			[
				'label' => __('Rating reply Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} .comment-reply-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_menu_review_form_reply_rating_margin',
			[
				'label' => __('Reply Rating margin', 'shopready-elementor-addon'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .comment-reply-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		$this->add_control(
			'tab_menu_review_form_ratinmg_value_color',
			[
				'label' => __('Rating Icon Color', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'.woocommerce {{WRAPPER}} p.stars a' => 'color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .star-rating span::before' => 'color: {{VALUE}} !important',
				],
				'hover_selector' => '.woocommerce {{WRAPPER}}  p.stars a:hover',
			]
		);

		$this->end_controls_section();

		$this->text_minimum_css(
			[
				'title'          => esc_html__('Review Submit Button', 'shopready-elementor-addon'),
				'slug'           => 'wready_wc_product_review_butn',
				'element_name'   => 'wrating_product_review_button',
				'selector'       => '{{WRAPPER}} .woocommerce-Tabs-panel--reviews #review_form_wrapper #respond .form-submit input.submit',
				'hover_selector' => '{{WRAPPER}} .woocommerce-Tabs-panel--reviews #review_form_wrapper #respond .form-submit input.submit:hover',
				'disable_controls' => [
					'display',
				],
			]
		);
	}

	public function get_tab_option()
	{

		return get_option('wready_product_tab_data_keys');
	}

	/**
	 * Override By elementor render method
	 * @return void
	 * 
	 */
	protected function html()
	{

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-product-tabs-layout', $settings['style']],
			]
		);

		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/tabs/' . $settings['style'] . '.php')) {
			shop_ready_widget_template_part(
				'product/template-parts/tabs/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,

				)
			);
		} else {
			shop_ready_widget_template_part(
				'product/template-parts/tabs/tabs.php',
				array(
					'settings'  => $settings,
				)
			);
		}

		echo  wp_kses_post('</div>');
	}
}
