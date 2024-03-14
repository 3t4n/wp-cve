<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Electro {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_electro_hooks' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function remove_electro_hooks() {
		remove_action( 'customize_controls_print_styles', 'x_customizer_preloader' );
	}

	public function internal_css() {
		if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
			return;
		}

		echo '<style>';
		echo 'body{overflow-x: visible;}';
		echo 'section {margin-bottom: 0;}';
		echo '.woocommerce-checkout h3 {border: none;line-height: 1.5;padding: 0;margin: 0 0 10px;}';
		echo '#customer_login h2::after, #payment .place-order button[type=submit], #reviews #comments>h2::after, #reviews:not(.electro-advanced-reviews) #comments>h2::after, .address header.title h3::after, .addresses header.title h3::after, .cart-collaterals h2:not(.woocommerce-loop-product__title)::after, .comment-reply-title::after, .comments-title::after, .contact-page-title::after, .cpf-type-range .tm-range-picker .noUi-origin .noUi-handle, .customer-login-form h2::after, .departments-menu .departments-menu-dropdown, .departments-menu .menu-item-has-children>.dropdown-menu, .ec-tabs>li.active a::after, .edit-account legend::after, .footer-widgets .widget-title:after, .header-v1 .navbar-search .input-group .btn, .header-v1 .navbar-search .input-group .form-control, .header-v1 .navbar-search .input-group .input-group-addon, .header-v3 .navbar-search .input-group .btn, .header-v3 .navbar-search .input-group .form-control, .header-v3 .navbar-search .input-group .input-group-addon, .navbar-primary .navbar-mini-cart .dropdown-menu-mini-cart, .pings-title::after, .products-2-1-2 .nav-link.active::after, .products-6-1 header ul.nav .active .nav-link, .products-carousel-tabs .nav-link.active::after, .sidebar .widget-title::after, .sidebar-blog .widget-title::after, .single-product .electro-tabs+section.products>h2::after, .single-product .electro-tabs~div.products>h2::after, .single-product .woocommerce-tabs+section.products>h2::after, .single-product .woocommerce-tabs~div.products>h2::after, .track-order h2::after, .wc-tabs>li.active a::after, .widget.widget_tag_cloud .tagcloud a:focus, .widget.widget_tag_cloud .tagcloud a:hover, .widget_electro_products_carousel_widget .section-products-carousel .owl-nav .owl-next:hover, .widget_electro_products_carousel_widget .section-products-carousel .owl-nav .owl-prev:hover, .widget_price_filter .ui-slider .ui-slider-handle:last-child, .woocommerce-account h2::after, .woocommerce-checkout h3::after, .woocommerce-edit-address form h3::after, .woocommerce-order-received h2::after, .wpb-accordion .vc_tta.vc_general .vc_tta-panel.vc_active .vc_tta-panel-heading .vc_tta-panel-title>a i, section header .h1::after, section header h1::after, section.section-onsale-product, section.section-onsale-product-carousel .onsale-product-carousel, section.section-product-cards-carousel header ul.nav .active .nav-link{
	display:none;
}';
		echo "body #wfacp-e-form .woocommerce-checkout #payment ul.payment_methods li {padding: 11px !important;}";
		echo "body #wfacp-e-form #shipping_method li label > span {position: relative;right: auto;left: auto;top: auto;}";
		echo "body #wfacp-e-form .wfacp_order_summary tr.shipping > td{display: table-cell;flex: auto;}";
		echo "body #wfacp-e-form .wfacp_order_summary tr.shipping > th {display: table-cell;flex: auto;}";
		echo "body #wfacp-e-form .woocommerce-checkout-review-order-table tbody > tr{display: table-row;width: 100%;justify-content: initial;}";
		echo "body #wfacp-e-form .woocommerce-checkout-review-order-table tfoot > tr{display: table-row;width: 100%;justify-content: initial;}";
		echo "body #wfacp-e-form .woocommerce-checkout-review-order-table thead > tr{display: table-row;width: 100%;justify-content: initial;}";
		echo "body #wfacp-e-form .wfacp_shipping_options .border:last-child table {margin-bottom: 0;}";
		echo "body #wfacp-e-form .wfacp_main_form .wfacp_shipping_table.wfacp_shipping_recurring tr.shipping:last-child td {padding-bottom: 0;margin-bottom: 0;}";
		echo '</style>';

	}
}

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
	return;
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Electro(), 'electro' );

