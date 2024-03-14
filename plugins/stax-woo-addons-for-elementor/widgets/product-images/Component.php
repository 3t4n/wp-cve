<?php

namespace StaxWoocommerce\Widgets\ProductImages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

use StaxWoocommerce\Widgets\Base;

class Component extends Base {

	public function __construct( $data = [], $args = null, $resources = true ) {
		parent::__construct( $data, $args, $resources );

		add_filter( 'woocommerce_gallery_thumbnail_size', [ $this, 'test' ], 10 );
	}

	public function test( $size ) {
		return [ 300, 300 ];
	}

	public function get_name() {
		return 'stax-woo-product-images';
	}

	public function get_title() {
		return __( 'Product Images', 'stax-woo-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-product-images sq-widget-label';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'image', 'product', 'gallery', 'lightbox' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_product_gallery_style',
			[
				'label' => __( 'Style', 'stax-woo-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'default_style',
			[
				'label'     => __( 'Default Style', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => '1',
				'selectors' => [
					'{{WRAPPER}} div.images' => 'overflow: auto;'
				]
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
			'sale_flash',
			[
				'label'        => __( 'Sale Flash', 'stax-woo-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'stax-woo-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'stax-woo-addons-for-elementor' ),
				'render_type'  => 'template',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '.woocommerce {{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
				.woocommerce {{WRAPPER}} .flex-viewport, .woocommerce {{WRAPPER}} .flex-control-thumbs img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .woocommerce-product-gallery__trigger + .woocommerce-product-gallery__wrapper,
					.woocommerce {{WRAPPER}} .flex-viewport' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'spacing',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .flex-viewport:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_thumbs_style',
			[
				'label'     => __( 'Thumbnails', 'stax-woo-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'thumbs_border',
				'selector' => '.woocommerce {{WRAPPER}} .flex-control-thumbs img',
			]
		);

		$this->add_responsive_control(
			'thumbs_border_radius',
			[
				'label'      => __( 'Border Radius', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'spacing_thumbs',
			[
				'label'      => __( 'Spacing', 'stax-woo-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .flex-control-thumbs li' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: {{SIZE}}{{UNIT}}',
					'.woocommerce {{WRAPPER}} .flex-control-thumbs'    => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		if ( 'yes' === $settings['sale_flash'] ) {
			wc_get_template( 'loop/sale-flash.php' );
		}
		wc_get_template( 'single-product/product-image.php' );

		// On render widget from Editor - trigger the init manually.
		if ( wp_doing_ajax() ) {
			?>
            <script>
                jQuery('.woocommerce-product-gallery').each(function () {
                    jQuery(this).wc_product_gallery();
                });
            </script>
			<?php
		}
	}
}
