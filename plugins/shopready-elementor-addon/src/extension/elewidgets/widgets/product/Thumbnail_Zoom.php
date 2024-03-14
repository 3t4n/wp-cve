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
use Elementor\Utils;

/**
 * WooCommerce Product Zoom Image
 * @see https://docs.woocommerce.com/document/managing-products/
 * @author quomodosoft.com
 */
class Thumbnail_Zoom extends \Shop_Ready\extension\elewidgets\Widget_Base
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
				'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => esc_html__('Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon'),
				'content_classes' => 'woo-ready-product-page-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__('Editor Refresh', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_product_content',
			[
				'label' => esc_html__('Content Refresh?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
				'label_off' => esc_html__('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'wready_product_id',
			[
				'label' => esc_html__('Demo Product', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default' => '',
				'options' => shop_ready_get_latest_products_id(100)
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
				'label' => esc_html__('Layout', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'product-image',
				'options' => [

					'flex-slider' => esc_html__('Flex Slider', 'shopready-elementor-addon'),
					// 'flex-vslider'   => esc_html__('Vertical Slider', 'shopready-elementor-addon'),
					// 'flex-vslider-right'   => esc_html__('Right Vertical Slider', 'shopready-elementor-addon'),
					'product-image' => esc_html__('Default', 'shopready-elementor-addon'),

				]
			]
		);


		$this->add_control(
			'thumb_align',
			[
				'label' => esc_html__('Thumb Align', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'sr-thumb-left' => esc_html__('Left', 'shopready-elementor-addon'),
					'sr-thumb-left sr-thumb-right' => esc_html__('Right', 'shopready-elementor-addon'),
					'sr-thumb-bottom' => esc_html__('Bottom', 'shopready-elementor-addon'),
					'sr-thumb-bottom sr-overflow' => esc_html__('Bottom Overflow', 'shopready-elementor-addon'),
					'sr-thumb-top' => esc_html__('top', 'shopready-elementor-addon'),
				],

				'condition' => [
					'style' => [
						'product-image'
					]
				]

			]
		);

		$this->add_responsive_control(
			'default_image_height',
			[
				'label' => esc_html__('Width', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .flex-viewport' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'style' => [
						'product-image'
					]
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
			'show_gallery',
			[
				'label' => __('Gallery?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'shopready-elementor-addon'),
				'label_off' => __('Hide', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'style!' => [
						'product-image'
					]
				]

			]
		);

		$this->add_control(
			'show_flash',
			[
				'label' => __('Flash Sale?', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'shopready-elementor-addon'),
				'label_off' => __('No', 'shopready-elementor-addon'),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					// 'style' => [
					// 	//'product-image'
					// ]
				]

			]
		);

		$this->add_control(
			'flex_thumbtitle_nav_style',
			[
				'label' => esc_html__('navigation', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'flex' => esc_html__('Show', 'shopready-elementor-addon'),
					'none' => esc_html__('Hide', 'shopready-elementor-addon'),


				],
				'selectors' => [
					'{{WRAPPER}} .woo-ready-product-zimage-layout.product-image .woocommerce-product-gallery ul.flex-direction-nav' => 'display: {{VALUE}} !important;',
				],
				'condition' => [
					'thumb_align' => [
						'sr-thumb-left',
						'sr-thumb-left sr-thumb-right'
					]
				]
			]
		);


		$this->end_controls_section();

		$this->element_size(
			[
				'title' => esc_html__('Video Iframe', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_iframe_sizer',
				'element_name' => 'wrating_product_iframe_size',
				'selector' => '{{WRAPPER}} .sr--product--video',
				'hover_selector' => false,
				'condition' => [
					'style' => [
						'flex-slider'
					]
				]

			]
		);

		$this->element_size(
			[
				'title' => esc_html__('Thumbnail Size', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_thumbnail_sizer',
				'element_name' => 'wrating_product_thumbnail_size',
				'selector' => '{{WRAPPER}} .shop-ready-product-thumb',
				'hover_selector' => false,
				'condition' => [
					'style!' => [
						'product-image'
					]
				]

			]
		);

		$this->element_size(
			[
				'title' => esc_html__('Slider Thumb Size', 'shopready-elementor-addon'),
				'slug' => 'wready_wc_product_thumbnail_slider_sizer',
				'element_name' => 'wrating_product_thumbnail_slider_size',
				'selector' => '{{WRAPPER}} .shop-ready-product-thumb-gly',
				'hover_selector' => false,
				'condition' => [
					'style!' => [
						'product-image'
					]
				]

			]
		);

		$this->text_css([
			'title' => esc_html__('Sale Flash', 'shopready-elementor-addon'),
			'slug' => 'woo_ready_product_sale_flash',
			'element_name' => '__woo_ready_product_sale_flash',
			'selector' => '{{WRAPPER}} .woo-ready-product-zimage-layout.product-image .onsale,.woocommerce {{WRAPPER}} span.onsale',
			'hover_selector' => '{{WRAPPER}} .woo-ready-product-zimage-layout.product-image .onsale:hover',
			'disable_controls' => ['box-shadow', 'display']
		]);

		$this->box_css([
			'title' => esc_html__('Image trigger', 'shopready-elementor-addon'),
			'slug' => 'woo_ready_product_galler_trigger',
			'element_name' => '__woo_ready_product_hal_trigger',
			'selector' => '{{WRAPPER}} .woocommerce-product-gallery__trigger',
			'hover_selector' => false,
			'disable_controls' => ['box-shadow', 'display', 'alignment'],
			'style' => ['product-image'],
		]);

		$this->start_controls_section(
			'flex_thumb_content_section',
			[
				'label' => esc_html__('Thumb ', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_margin',
			[
				'label' => esc_html__('Margin', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wooready_product_details_small_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',

				],
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_padding',
			[
				'label' => esc_html__('Padding', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} .wooready_product_details_small_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_item_container_width',
			[
				'label' => esc_html__('Container Width', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [

					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],

				'selectors' => [
					'body.woocommerce {{WRAPPER}} .sr-thumb-left ol.flex-control-thumbs' => 'width: {{SIZE}}{{UNIT}} !important;',
					'body.woocommerce {{WRAPPER}} .sr-thumb-bottom ol.flex-control-thumbs' => 'width: {{SIZE}}{{UNIT}} !important;',
					'body.woocommerce {{WRAPPER}} .wooready_product_details_small_item' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_item_width',
			[
				'label' => esc_html__('Item Width', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [

					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs li' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wooready_product_details_small_item .item img' => 'width: {{SIZE}}{{UNIT}} !important;',
					'.woocommerce div.product div.images .flex-control-thumbs li' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'flex_thumb__title_item_flex-height',
			[
				'label' => esc_html__('Item Height', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wooready_product_details_small_item .item img' => 'heights: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'style' => [
						'flex-slider'
					]
				]
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_float_style',
			[
				'label' => esc_html__('Align', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__('Left', 'shopready-elementor-addon'),
					'right' => esc_html__('right', 'shopready-elementor-addon'),
					'center' => esc_html__('None', 'shopready-elementor-addon'),

				],
				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs' => 'justify-content: {{VALUE}} !important;',
					'{{WRAPPER}} .flex-control-thumbs li' => 'float: {{VALUE}}!important;',
					'.woocommerce div.product div.images .flex-control-thumbs li' => 'float: {{VALUE}};',
				],
				'condition' => [
					'style' => ['product-image'],
					'thumb_align!' => [
						'sr-thumb-left',
						'sr-thumb-left sr-thumb-right'
					]
				]
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_gap',
			[
				'label' => esc_html__('Gap', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 1,
					],

				],

				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs' => 'gap: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .slick-slide' => 'margin: 0 {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .slick-list' => 'margin: 0 {{SIZE}}{{UNIT}} !important;',
				],

			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_border_adssradius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],

				],

				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wooready_product_details_small_item .item img' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'flex_thumbtitle_u_background',
				'label' => esc_html__('Background', 'shopready-elementor-addon'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '.woocommerce {{WRAPPER}} .flex-control-thumbs,{{WRAPPER}} .wooready_product_details_small_item',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'flex_thumbtitle_u_box_shadow',
				'label' => esc_html__('Box Shadow', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} .flex-control-thumbs, {{WRAPPER}} .wooready_product_details_small_item',
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_positiuon',
			[
				'label' => esc_html__('Position', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'sticky' => esc_html__('sticky', 'shopready-elementor-addon'),
					'absolute' => esc_html__('absolute', 'shopready-elementor-addon'),
					'relative' => esc_html__('relative', 'shopready-elementor-addon'),
					'static' => esc_html__('static', 'shopready-elementor-addon'),

				],

				'selectors' => [
					'body.woocommerce {{WRAPPER}} ol.flex-control-thumbs li' => 'position: {{VALUE}};',
					'body.woocommerce {{WRAPPER}} .wooready_product_details_small_item' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_image_right_pos',
			[
				'label' => esc_html__('Right Position', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [

					'%' => [
						'min' => -50,
						'max' => 100,
					],
					'px' => [
						'min' => -500,
						'max' => 1000,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .sr-thumb-left.sr-thumb-right .flex-control-nav' => 'right: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .wooready_product_details_small_item' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'thumb_align' => [
						'sr-thumb-left',
						'sr-thumb-right'
					]
				]
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_image_bot_pos',
			[
				'label' => esc_html__('Bottom ', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [

					'%' => [
						'min' => -50,
						'max' => 100,
					],
					'px' => [
						'min' => -500,
						'max' => 1000,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wooready_product_details_small_item' => 'bottom: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .sr-thumb-bottom.sr-overflow ol.flex-control-nav.flex-control-thumbs' => 'bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'thumb_align' => [
						'sr-thumb-bottom',
						'sr-thumb-bottom sr-overflow'
					]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'flex_thumb_content_slider_section',
			[
				'label' => esc_html__('Image', 'shopready-elementor-addon'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_image_width',
			[
				'label' => esc_html__('Image Width', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range' => [

					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .sr-thumb-left .flex-viewport' => 'width: {{SIZE}}{{UNIT}} !important;',
				],

			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_image_left_pos',
			[
				'label' => esc_html__('Left Position', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [

					'%' => [
						'min' => -30,
						'max' => 100,
					],
					'px' => [
						'min' => -400,
						'max' => 1000,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .sr-thumb-left .flex-viewport' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'thumb_align' => [
						'sr-thumb-left'
					]
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'flexs_thumbtitle_image_border',
				'label' => esc_html__('Border', 'shopready-elementor-addon'),
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-product-gallery ol.flex-control-nav.flex-control-thumbs li img',
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_opacity',
			[
				'label' => esc_html__('Opacity', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],

				],

				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs li img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'flex_thumbtitle_border_asradius',
			[
				'label' => esc_html__('Border Radius', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],

				],

				'selectors' => [
					'{{WRAPPER}} .flex-control-thumbs li img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
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

		if (shop_ready_is_elementor_mode()) {

			$temp_id = WC()->session->get('sr_single_product_id');

			if ($settings['show_product_content'] == 'yes' && is_numeric($settings['wready_product_id'])) {
				$temp_id = $settings['wready_product_id'];
			}

			if (is_numeric($temp_id)) {
				setup_postdata($temp_id);
			} else {
				setup_postdata(shop_ready_get_single_product_key());
			}

		}

		$this->add_render_attribute(
			'wrapper_style',
			[
				'class' => ['woo-ready-product-zimage-layout', $settings['style'], $settings['thumb_align']],
			]
		);

		echo wp_kses_post(sprintf("<div %s>", $this->get_render_attribute_string('wrapper_style')));

		if (file_exists(dirname(__FILE__) . '/template-parts/image/' . $settings['style'] . '.php')) {

			shop_ready_widget_template_part(
				'product/template-parts/image/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,
				)
			);

		} else {

			shop_ready_widget_template_part(
				'product/template-parts/image/product-image.php',
				array(
					'settings' => $settings,
				)
			);

		}

		echo wp_kses_post('</div>');
	}
}