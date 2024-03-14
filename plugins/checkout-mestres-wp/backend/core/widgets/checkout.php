<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use ElementorPro\Plugin;
class Checkout extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'checkout-mwp';
	}
	public function get_title() {
		return esc_html__( 'Checkout', 'checkout-mestres-wp' );
	}
	public function get_icon() {
		return 'eicon-checkout';
	}
	public function get_custom_help_url() {
		return 'https://docs.mestresdowp.com.br/';
	}
	public function get_categories() {
		return [ 'cwmp-addons' ];
	}
	public function get_script_depends() {
		return [
			'wc-checkout',
			'wc-password-strength-meter',
			'selectWoo',
		];
	}
	public function get_style_depends() {
		return [ 'cwmp_frontend_styles' ];
	}

	public function get_keywords() {
		return [ 'checkout', 'mestres wp', 'mestres' ];
	}
	protected function register_controls() {

	}
	protected function render() {
		echo do_shortcode( '[woocommerce_checkout]' );
	}



}