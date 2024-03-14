<?php

namespace StaxWoocommerce\Widgets\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

use StaxWoocommerce\Woocommerce\CurrentQueryRenderer;
use StaxWoocommerce\Woocommerce\ProductsRenderer;

use StaxWoocommerce\Widgets\Base;

class Component extends Base {

	public function __construct( $data = [], $args = null, $resources = true ) {
		parent::__construct( $data, $args, $resources );

		$this->require_extra_classes();
	}

	public function get_name() {
		return 'stax-woo-products';
	}

	public function get_title() {
		return __( 'Products', 'stax-woo-addons-for-elementor' );
	}

	public function get_icon() {
		return 'sq-icon-woo_products sq-widget-label';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'products' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'               => __( 'Columns', 'stax-woo-addons-for-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'elementor-products-columns%s-',
				'min'                 => 1,
				'max'                 => 12,
				'default'             => ProductsRenderer::DEFAULT_COLUMNS_AND_ROWS,
				'required'            => true,
				'render_type'         => 'template',
				'device_args'         => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				],
			]
		);

		$this->add_control(
			'rows',
			[
				'label'       => __( 'Rows', 'stax-woo-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => ProductsRenderer::DEFAULT_COLUMNS_AND_ROWS,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'max' => 20,
					],
				],
			]
		);

		$this->add_control(
			'paginate',
			[
				'label'   => __( 'Pagination', 'stax-woo-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'allow_order',
			[
				'label'     => __( 'Allow Order', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'wc_notice_frontpage',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'stax-woo-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => [
					'paginate'    => 'yes',
					'allow_order' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_result_count',
			[
				'label'     => __( 'Show Result Count', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'stax-woo-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'query_post_type',
			[
				'label'   => __( 'Source', 'stax-woo-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'product',
				'options' => [
					'current_query' => __( 'Current Query', 'stax-woo-addons-for-elementor' ),
					'product'       => __( 'Latest Products', 'stax-woo-addons-for-elementor' ),
					'sale'          => __( 'Sale', 'stax-woo-addons-for-elementor' ),
					'featured'      => __( 'Featured', 'stax-woo-addons-for-elementor' )
				],
			]
		);

		$this->add_control(
			'query_offset',
			[
				'label'   => __( 'Offset', 'stax-woo-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
				'default' => 0,
			]
		);

		$this->add_control(
			'query_orderby',
			[
				'label'   => __( 'Order By', 'stax-woo-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date'       => __( 'Date', 'stax-woo-addons-for-elementor' ),
					'title'      => __( 'Title', 'stax-woo-addons-for-elementor' ),
					'price'      => __( 'Price', 'stax-woo-addons-for-elementor' ),
					'popularity' => __( 'Popularity', 'stax-woo-addons-for-elementor' ),
					'rating'     => __( 'Rating', 'stax-woo-addons-for-elementor' ),
					'rand'       => __( 'Random', 'stax-woo-addons-for-elementor' ),
					'menu_order' => __( 'Menu Order', 'stax-woo-addons-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'query_order',
			[
				'label'   => __( 'Order', 'stax-woo-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'  => __( 'ASC', 'stax-woo-addons-for-elementor' ),
					'desc' => __( 'DESC', 'stax-woo-addons-for-elementor' )
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_style',
			[
				'label' => __( 'Products', 'stax-woo-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'stax-woo-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'products_class',
			[
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'wc-products',
				'prefix_class' => 'elementor-products-grid elementor-',
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'          => __( 'Columns Gap', 'stax-woo-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 20,
				],
				'tablet_default' => [
					'size' => 20,
				],
				'mobile_default' => [
					'size' => 20,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'          => __( 'Rows Gap', 'stax-woo-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 40,
				],
				'tablet_default' => [
					'size' => 40,
				],
				'mobile_default' => [
					'size' => 40,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'        => __( 'Alignment', 'stax-woo-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'stax-woo-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'stax-woo-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'stax-woo-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-product-loop-item--align-',
				'selectors'    => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'label'     => __( 'Image', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'label'    => __( 'Box Shadow', 'stax-woo-addons-for-elementor' ),
				'selector' => '{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products .attachment-woocommerce_thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label'     => __( 'Title', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title'  => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title, ' .
				              '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title',

			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'em' => [
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_rating_style',
			[
				'label'     => __( 'Rating', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'star_color',
			[
				'label'     => __( 'Star Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label'     => __( 'Empty Star Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'star_size',
			[
				'label'     => __( 'Star Size', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'em',
				],
				'range'     => [
					'em' => [
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'rating_spacing',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'em' => [
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_price_style',
			[
				'label'     => __( 'Price', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price'             => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins'         => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price',
			]
		);

		$this->add_control(
			'price_margin',
			[
				'label'      => __( 'Margin', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_old_price_style',
			[
				'label'     => __( 'Regular Price', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'old_price_color',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del'         => 'color: {{VALUE}}',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'old_price_typography',
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount',
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .price del'
				]
			]
		);

		$this->add_control(
			'heading_button_style',
			[
				'label'     => __( 'Button', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_show',
			[
				'label'        => __( 'Show', 'stax-woo-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stax-woo-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'stax-woo-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'button_hidden_style',
			[
				'label'     => __( 'Button Hide', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'display: none;'
				],
				'condition' => [
					'button_show!' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_button_style', [
			'condition' => [
				'button_show' => 'yes'
			]
		] );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label'     => __( 'Border Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __( 'Text Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'      => 'button_border',
				'exclude'   => [ 'color' ],
				'selector'  => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
				'separator' => 'before',
				'condition' => [
					'button_show' => 'yes'
				]
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_show' => 'yes'
				]
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label'      => __( 'Text Padding', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_show' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'button_show' => 'yes'
				]
			]
		);

		$this->add_control(
			'heading_view_cart_style',
			[
				'label'     => __( 'View Cart', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_color',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products .added_to_cart' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'view_cart_typography',
				'selector' => '{{WRAPPER}}.elementor-wc-products .added_to_cart',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_box',
			[
				'label' => __( 'Box', 'stax-woo-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_border_width',
			[
				'label'      => __( 'Border Width', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => __( 'Padding', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab( 'classic_style_normal',
			[
				'label' => __( 'Normal', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product',
			]
		);

		$this->add_control(
			'box_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label'     => __( 'Border Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'classic_style_hover',
			[
				'label' => __( 'Hover', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_hover',
				'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover',
			]
		);

		$this->add_control(
			'box_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label'     => __( 'Border Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => __( 'Pagination', 'stax-woo-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_spacing',
			[
				'label'     => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'show_pagination_border',
			[
				'label'        => __( 'Border', 'stax-woo-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'stax-woo-addons-for-elementor' ),
				'label_on'     => __( 'Show', 'stax-woo-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'elementor-show-pagination-border-',
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'     => __( 'Border Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul'    => 'border-color: {{VALUE}}',
					'{{WRAPPER}} nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
				],
				'condition' => [
					'show_pagination_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'pagination_padding',
			[
				'label'      => __( 'Padding', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'em' => [
						'min'  => 0,
						'max'  => 2,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'em' ],
				'selectors'  => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
			]
		);

		$this->start_controls_tabs( 'pagination_style_tabs' );

		$this->start_controls_tab( 'pagination_style_normal',
			[
				'label' => __( 'Normal', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_link_color',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_style_hover',
			[
				'label' => __( 'Hover', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_link_color_hover',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_style_active',
			[
				'label' => __( 'Active', 'stax-woo-addons-for-elementor' ),
			]
		);

		$this->add_control(
			'pagination_link_color_active',
			[
				'label'     => __( 'Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sale_flash_style',
			[
				'label' => __( 'Sale Flash', 'stax-woo-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_onsale_flash',
			[
				'label'        => __( 'Sale Flash', 'stax-woo-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'stax-woo-addons-for-elementor' ),
				'label_on'     => __( 'Show', 'stax-woo-addons-for-elementor' ),
				'separator'    => 'before',
				'default'      => 'yes',
				'return_value' => 'yes',
				'selectors'    => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'display: block',
				],
			]
		);

		$this->add_control(
			'onsale_text_color',
			[
				'label'     => __( 'Text Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_text_background_color',
			[
				'label'     => __( 'Background Color', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'onsale_typography',
				'selector'  => '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale',
				'condition' => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_width',
			[
				'label'      => __( 'Width', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_height',
			[
				'label'      => __( 'Height', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_horizontal_position',
			[
				'label'                => __( 'Position', 'stax-woo-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'  => [
						'title' => __( 'Left', 'stax-woo-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'stax-woo-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'            => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'  => 'right: auto; left: 0',
					'right' => 'left: auto; right: 0',
				],
				'condition'            => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->add_control(
			'onsale_distance',
			[
				'label'      => __( 'Distance', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => - 20,
						'max' => 20,
					],
					'em' => [
						'min' => - 2,
						'max' => 2,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'margin: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_onsale_flash' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ ProductsRenderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';

			return new CurrentQueryRenderer( $settings, $type );
		}

		$type = 'products';

		return new ProductsRenderer( $settings, $type );
	}

	protected function render() {
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For ProductsRenderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // WPCS: override ok.
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content;
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
		}
	}

}
