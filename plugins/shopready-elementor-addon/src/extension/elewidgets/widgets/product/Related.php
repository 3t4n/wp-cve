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
 * WooCommerce Product Related
 *
 * @see https://docs.woocommerce.com/document/related-products-up-sells-and-cross-sells/
 * @author quomodosoft.com
 */
class Related extends \Shop_Ready\extension\elewidgets\Widget_Base {



	/**
	 * Html Wrapper Class of html
	 */
	public $wrapper_class = false;

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
			'section_related_products_content',
			array(
				'label' => __( 'Related Products', 'shopready-elementor-addon' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Layout', 'shopready-elementor-addon' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'shopready-elementor-addon' ),
					// 'wready-rating-two'   => esc_html__('Style 2','shopready-elementor-addon'),

				),
			)
		);

		$this->add_responsive_control(
			'product_title_line',
			array(
				'label'      => esc_html__( 'Product Name line', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 1,
						'max'  => 5,
						'step' => 1,
					),

				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-related-product-layout .related .product h2.woocommerce-loop-product__title' => '-webkit-line-clamp:{{SIZE}}',
					'{{WRAPPER}} .wooready_product_content_box .wooready_title .title a' => '-webkit-line-clamp:{{SIZE}}',
				),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Products Per Page', 'shopready-elementor-addon' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'range'   => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_responsive_control(
			'product_grid_column_items',
			array(
				'label'      => esc_html__( 'Grid Items', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 130,
						'step' => 2,
					),

				),
				'default'    => array(
					'unit' => 'px',
					'size' => 3,
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-related-product-layout .woo-ready-products' => 'grid-template-columns: repeat( {{SIZE}}, 1fr);;',
				),
			)
		);

		$this->add_responsive_control(
			'product_grid_column_items_gap',
			array(
				'label'      => esc_html__( 'Column gap', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 130,
						'step' => 2,
					),

				),
				'default'    => array(
					'unit' => 'px',
					'size' => 3,
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-products' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_grid_row_items_gap',
			array(
				'label'      => esc_html__( 'Row gap', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,

				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 0,
						'max'  => 130,
						'step' => 2,
					),

				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-products' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_section_related_grid_col',
			array(
				'label'      => esc_html__( 'Columns', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(

					'px' => array(
						'min'  => 1,
						'max'  => 13,
						'step' => 1,
					),

				),
				'default'    => array(
					'unit' => 'px',
					'size' => 3,
				),

				'selectors'  => array(
					'{{WRAPPER}} .woo-ready-products' => 'grid-template-columns: repeat( {{SIZE}}, 1fr);',

				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'shopready-elementor-addon' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Date', 'shopready-elementor-addon' ),
					'title'      => __( 'Title', 'shopready-elementor-addon' ),
					'price'      => __( 'Price', 'shopready-elementor-addon' ),
					'popularity' => __( 'Popularity', 'shopready-elementor-addon' ),
					'rating'     => __( 'Rating', 'shopready-elementor-addon' ),
					'rand'       => __( 'Random', 'shopready-elementor-addon' ),
					'menu_order' => __( 'Menu Order', 'shopready-elementor-addon' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'shopready-elementor-addon' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => __( 'ASC', 'shopready-elementor-addon' ),
					'desc' => __( 'DESC', 'shopready-elementor-addon' ),
				),
			)
		);

		$this->add_control(
			'content_text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'shopready-elementor-addon' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'shopready-elementor-addon' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'shopready-elementor-addon' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'shopready-elementor-addon' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'body {{WRAPPER}} .wooready_product_content_box' => 'text-align: {{VALUE}};',
					'body {{WRAPPER}} .wooready_price_box' => 'justify-content: {{VALUE}};',
					'body {{WRAPPER}} .wooready_product_color' => 'justify-content: {{VALUE}};',
					'body {{WRAPPER}} .wooready-slider-product-layout .product-details .sr-review-rating' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->box_css(
			array(
				'title'            => esc_html__( 'Product Box', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_box_item',
				'element_name'     => 'wrating_product_box_item',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .woo-ready-products .product',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'alignment' ),
			)
		);

		$grid_style = get_option( 'wooready_products_archive_shop_grid_style' );
		if ( $grid_style == 'eforest' ) {

			$this->text_css(
				array(
					'title'            => esc_html__( 'Product Details', 'shopready-elementor-addon' ),
					'slug'             => 'wready_wc_product_box_detail_item',
					'element_name'     => 'wrating_product_box_details_item',
					'selector'         => '{{WRAPPER}} .wooready-slider-product-layout .product-details',
					'hover_selector'   => '{{WRAPPER}} .wooready-slider-product-layout:hover .product-details',
					'disable_controls' => array( 'position', 'alignment', 'display', 'box-shadow' ),
				)
			);
		}

		$this->text_css(
			array(
				'title'            => esc_html__( 'Box Top Heading', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_top_heading',
				'element_name'     => 'wrating_product_top_heading',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .related>h2',
				'hover_selector'   => '{{WRAPPER}} .woo-ready-related-product-layout .related>h2:hover',
				'disable_controls' => array( 'position', 'size' ),
			)
		);

		$this->text_minimum_css(
			array(
				'title'            => esc_html__( 'Product Image', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_related_product_image',
				'element_name'     => 'wready_wc_related_product_image',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout img',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'display', 'alignment' ),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Product Title', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_related_product_title',
				'element_name'     => 'wready_wc_related_product_title',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .related .product h2.woocommerce-loop-product__title,{{WRAPPER}} .wooready_product_content_box .wooready_title .title a',
				'hover_selector'   => '{{WRAPPER}} .woo-ready-related-product-layout .related .product h2.woocommerce-loop-product__title:hover, {{WRAPPER}} .wooready_product_content_box .wooready_title .title:hover a',
				'disable_controls' => array( 'position', 'size' ),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Normal Price', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_price_normal',
				'element_name'     => 'wrating_product_price_normall',
				'selector'         => '{{WRAPPER}} span.price .woocommerce-Price-amount',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'display' ),

			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Discount Price', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_price_discount',
				'element_name'     => 'wrating_product_price_discountt',
				'selector'         => '{{WRAPPER}} span.price ins .woocommerce-Price-amount',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'display' ),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Sale badge', 'shopready-elementor-addon' ),
				'slug'             => 'wready_related_product_badge',
				'element_name'     => '_wready_related_product_badge',
				'selector'         => '{{WRAPPER}} .related .product span.wooready_sell_discount',
				'disable_controls' => array(
					'display',
					'size',
				),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Add To Cart', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_product_sold_by_cart',
				'element_name'     => 'wrating_product_sold_byy_cartt',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .related .product .add_to_cart_button,{{WRAPPER}} .woo-ready-related-product-layout .related .product .button.product_type_simple, {{WRAPPER}} .woo-ready-related-product-layout .related .product .button.product_type_variable',
				'hover_selector'   => '{{WRAPPER}} .woo-ready-related-product-layout .related .product .add_to_cart_button:hover, {{WRAPPER}} .woo-ready-related-product-layout .related .product .button.product_type_simple:hover, {{WRAPPER}} .woo-ready-related-product-layout .related .product .button.product_type_variable:hover',
				'disable_controls' => array( 'display' ),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Rating', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_related_product_rating',
				'element_name'     => 'wready_wc_related_product_rating',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .related .product .star-rating:before',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'size', 'display', 'bg', 'border', 'box-shadow' ),
			)
		);

		$this->text_css(
			array(
				'title'            => esc_html__( 'Active Rating', 'shopready-elementor-addon' ),
				'slug'             => 'wready_wc_related_product_active_rating',
				'element_name'     => 'wready_wc_related_product_active_rating',
				'selector'         => '{{WRAPPER}} .woo-ready-related-product-layout .related .product .star-rating span:before',
				'hover_selector'   => false,
				'disable_controls' => array( 'position', 'size', 'display', 'dimensions', 'bg', 'border', 'box-shadow' ),

			)
		);

		$this->box_css(
			array(
				'title'            => esc_html__( 'Sold Wrapper', 'shopready-elementor-addon' ),
				'slug'             => 'wready_product_sold_wrapper',
				'element_name'     => '_wready_product_sold_wrapperr',
				'selector'         => '{{WRAPPER}} .wooready_product_content_box .wooready_product_sold_range',
				'disable_controls' => array(
					'position',
					'dimensions',
					'border',
					'box-shadow',

				),
			)
		);

		$this->box_css(
			array(
				'title'          => esc_html__( 'Product Meta Wrapper', 'shopready-elementor-addon' ),
				'slug'           => 'wready_wc_product_meta_wrapper',
				'element_name'   => 'wrating_product_meta_wrapperr',
				'selector'       => '{{WRAPPER}} .woo-ready-related-product-layout .product-meta',
				'hover_selector' => false,

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
					'{{WRAPPER}} .woo-ready-related-product-layout .sr-ef-sold-by span' => 'color: {{VALUE}} !important;',

				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_ma_vendor_content_typography',
				'selector' => '{{WRAPPER}} .woo-ready-related-product-layout .sr-ef-sold-by span',
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
					'{{WRAPPER}} .woo-ready-related-product-layout .sr-ef-sold-by' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woo-ready-related-product-layout .sr-ef-sold-by a' => 'color: {{VALUE}} !important;',

				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_taasd_vendor_content_typography',
				'selector' => '{{WRAPPER}} .woo-ready-related-product-layout .sr-ef-sold-by a',
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

		$this->add_render_attribute(
			'wrapper_style',
			array(
				'class' => array( 'woo-ready-related-product-layout', $settings['style'] ),
			)
		);
		if ( shop_ready_is_elementor_mode() ) {
			$temp_id = WC()->session->get( 'sr_single_product_id' );

			if ( is_numeric( $temp_id ) ) {
				setup_postdata( $temp_id );
			} else {
				setup_postdata( shop_ready_get_single_product_key() );
			}
		}
		echo wp_kses_post( sprintf( '<div %s>', $this->get_render_attribute_string( 'wrapper_style' ) ) );

		if ( file_exists( dirname( __FILE__ ) . '/template-parts/related/' . $settings['style'] . '.php' ) ) {

			shop_ready_widget_template_part(
				'product/template-parts/related/' . $settings['style'] . '.php',
				array(
					'settings' => $settings,

				)
			);
		} else {

			shop_ready_widget_template_part(
				'product/template-parts/related/default.php',
				array(
					'settings' => $settings,
				)
			);
		}

		echo wp_kses_post( '</div>' );
	}
}
