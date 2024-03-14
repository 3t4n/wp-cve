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
 * WooCommerce Product UpSell
 *
 * @see https://docs.woocommerce.com/document/related-products-up-sells-and-cross-sells/
 * @author quomodosoft.com
 */
class UpSell extends \Shop_Ready\extension\elewidgets\Widget_Base {


	/**
	 * Html Wrapper Class of html
	 */
	public $wrapper_class = true;

	protected function register_controls() {
		// Notice
		$this->start_controls_section(
			'notice_content_section',
			array(
				'label' => esc_html__( 'Notice', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'woo_ready_usage_direction_notice',
			array(
				'label'           => esc_html__( 'Important Note', 'shopready-elementor-addon' ),
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Use This Widget in WooCommerce Product Details page  Template.', 'shopready-elementor-addon' ),
				'content_classes' => 'woo-ready-product-page-notice',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'editor_content_section',
			array(
				'label' => esc_html__( 'Editor Refresh', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_product_content',
			array(
				'label'        => esc_html__( 'Content Refresh?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'wready_product_id',
			array(
				'label'    => esc_html__( 'Demo Product', 'shopready-elementor-addon' ),
				'type'     => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'default'  => shop_ready_get_single_product_key(),
				'options'  => shop_ready_get_latest_products_id( 50 ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_upsell_products_content',
			array(
				'label' => __( 'UpSell Products', 'shopready-elementor-addon' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Limit', 'shopready-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 10,
				'step'    => 1,
				'default' => 4,
			)
		);

		$this->add_responsive_control(
			'_grid_col',
			array(
				'label'      => esc_html__( 'Columns', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),

				),

				'default'    => array(
					'size' => 4,
					'unit' => 'px',
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-products' => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',

				),
			)
		);

		$this->add_responsive_control(
			'_grid_cols_gap',
			array(
				'label'      => esc_html__( 'Column Gap', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),

				),

				'selectors'  => array(

					'{{WRAPPER}} .woo-ready-products' => 'column-gap: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->add_responsive_control(
			'_grid_row_gap',
			array(
				'label'      => esc_html__( 'Row Gap', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-products' => 'row-gap: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => __( 'Order By', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => __( 'Date', 'shopready-elementor-addon' ),
					'title'      => __( 'Title', 'shopready-elementor-addon' ),
					'price'      => __( 'Price', 'shopready-elementor-addon' ),
					'popularity' => __( 'Popularity', 'shopready-elementor-addon' ),
					'rating'     => __( 'Rating', 'shopready-elementor-addon' ),
					'rand'       => __( 'Random', 'shopready-elementor-addon' ),
					'menu_order' => __( 'Menu Order', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'style' => array( 'default', 'style1' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => __( 'Order', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => array(
					'asc'  => __( 'ASC', 'shopready-elementor-addon' ),
					'desc' => __( 'DESC', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'style' => array( 'default', 'style1' ),
				),
			)
		);

		$this->end_controls_section();

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Heading', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_heading',
				'element_name'     => 'wrating_product_heading',
				'selector'         => '{{WRAPPER}} .up-sells h2',
				'hover_selector'   => false,
				'disable_controls' => array(
					'position',
					'size',
					'bg',
					'border',
					'box-shadow',
				),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Product Title', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_related_product_title',
				'element_name'     => 'wready_wc_related_product_title',
				'selector'         => '{{WRAPPER}} .woo-ready-upsell-product-layout .related .product h2.woocommerce-loop-product__title,{{WRAPPER}} .wooready_product_content_box .wooready_title .title a ,{{WRAPPER}} .wready-product-loop-title-link',

				'hover_selector'   => '{{WRAPPER}} .woo-ready-upsell-product-layout .related .product h2.woocommerce-loop-product__title:hover, {{WRAPPER}} .wooready_product_content_box .wooready_title .title:hover a ,{{WRAPPER}} .wready-product-loop-title-link:hover',
				'disable_controls' => array(
					'position',
					'size',
				),
			)
		);

		$this->start_controls_section(
			'wready_wc_product_vendor_wrapper',
			array(
				'label' => esc_html__( 'Vendor Label', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_vendor_label_text_color',
			array(
				'label'     => esc_html__( 'label Color', 'shopready-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woo-ready-upsell-product-layout .sr-ef-sold-by span' => 'color: {{VALUE}} !important;',

				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_ma_vendor_content_typography',
				'selector' => '{{WRAPPER}} .woo-ready-upsell-product-layout .sr-ef-sold-by span',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wready_w_product_vendor_asd_wrapper',
			array(
				'label' => esc_html__( 'Vendor Name', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_me_venror_text_cmargin',
			array(
				'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-upsell-product-layout .sr-ef-sold-by' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'product_m_venror_text_color',
			array(
				'label'     => esc_html__( ' Color', 'shopready-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woo-ready-upsell-product-layout .sr-ef-sold-by a' => 'color: {{VALUE}} !important;',

				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_taasd_vendor_content_typography',
				'selector' => '{{WRAPPER}} .woo-ready-upsell-product-layout .sr-ef-sold-by a',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_align_shop_ready_order_content_section',
			array(
				'label' => __( 'Order Content', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_title_element',
			array(
				'label'      => esc_html__( 'Title Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_title' => 'order: {{SIZE}}',

				),

			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_priceelement',
			array(
				'label'      => esc_html__( 'Price Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_price_box' => 'order: {{SIZE}}',

				),

			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_review_element',
			array(
				'label'      => esc_html__( 'Review Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_review' => 'order: {{SIZE}}',

				),

			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_color_element',
			array(
				'label'      => esc_html__( 'Color Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_product_color' => 'order: {{SIZE}}',

				),

			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_range_element',
			array(
				'label'      => esc_html__( 'Sold Ranger Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_product_sold_range' => 'order: {{SIZE}}',

				),

			)
		);

		$this->add_responsive_control(
			'shop_ready_products_archive_shop_grid_order_image_element',
			array(
				'label'      => esc_html__( 'Image Order', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -30,
						'max'  => 100,
						'step' => 1,
					),

				),

				'selectors'  => array(
					'{{WRAPPER}} .wooready_product_thumb ' => 'order: {{SIZE}}',

				),

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wready_w_product_box_asd_wrapper',
			array(
				'label' => esc_html__( 'Item Box', 'shopready-elementor-addon' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wready_w_product_boxupsel_margin',
			array(
				'label'      => esc_html__( 'Margin', 'shopready-elementor-addon' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .upsells .product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Override By elementor render method
	 *
	 * @return void
	 */
	protected function html() {
		$settings = $this->get_settings_for_display();

		wp_enqueue_style( 'woocommerce-general' );

		$this->add_render_attribute(
			'wrapper_style',
			array(
				'class' => array( 'woo-ready-upsell-product-layout', 'default' ),
			)
		);

		echo wp_kses_post( sprintf( '<div %s>', $this->get_render_attribute_string( 'wrapper_style' ) ) );

		shop_ready_widget_template_part(
			'product/template-parts/upsell/default.php',
			array(
				'settings' => $settings,
			)
		);

		echo wp_kses_post( '</div>' );
	}
}
