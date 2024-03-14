<?php
/**
 * Admin area
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Admin;

use NovaPoshta\Main;
use NovaPoshta\Language;

/**
 * Class Admin
 *
 * @package NovaPoshta\Admin
 */
class Admin {

	/**
	 * Plugin language.
	 *
	 * @var Language
	 */
	private $language;

	/**
	 * Admin constructor.
	 *
	 * @param Language $language Plugin language.
	 */
	public function __construct( Language $language ) {

		$this->language = $language;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue styles
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			'np-notice',
			NOVA_POSHTA_URL . 'assets/build/css/admin/notice.css',
			[],
			Main::VERSION
		);

		if ( ! $this->is_plugin_page() ) {
			return;
		}

		wp_enqueue_style(
			Main::PLUGIN_SLUG,
			NOVA_POSHTA_URL . 'assets/build/css/admin/admin.css',
			[],
			Main::VERSION
		);
	}

	/**
	 * Default weight and volume options.
	 */
	const WEIGH_OPTIONS = [
		'0.5' => [ 16, 11, 10 ],
		'1'   => [ 23, 16, 10 ],
		'2'   => [ 33, 23, 10 ],
		'5'   => [ 39, 23, 21 ],
		'10'  => [ 39, 34, 28 ],
		'20'  => [ 46, 42, 39 ],
		'30'  => [ 70, 42, 40 ],
	];

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			'np-notice',
			NOVA_POSHTA_URL . 'assets/build/js/admin/notice.js',
			[ 'jquery' ],
			Main::VERSION,
			true
		);
		wp_localize_script(
			'np-notice',
			'shippingNovaPoshtaForWoocommerce',
			[
				'url'           => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( Main::PLUGIN_SLUG ),
				'language'      => $this->language->get_current_language(),
				'weightOptions' => $this->prepare_default_weight_options(),
			]
		);

		if ( ! $this->is_plugin_page() ) {
			return;
		}

		wp_enqueue_script(
			'np-select2',
			NOVA_POSHTA_URL . 'assets/build/js/vendor/select2.min.js',
			[ 'jquery' ],
			'4.0.13',
			true
		);
		wp_enqueue_script(
			'np-select2-i18n-' . $this->language->get_current_language(),
			NOVA_POSHTA_URL . 'assets/build/js/vendor/i18n/' . $this->language->get_current_language() . '.js',
			[ 'jquery', 'np-select2' ],
			Main::VERSION,
			true
		);
		wp_enqueue_script(
			'np-tip-tip',
			NOVA_POSHTA_URL . 'assets/build/js/vendor/jquery.tip-tip.min.js',
			[ 'jquery' ],
			'1.3',
			true
		);
		wp_enqueue_script(
			'np-lity',
			NOVA_POSHTA_URL . 'assets/build/js/vendor/lity.min.js',
			[ 'jquery' ],
			'2.4.1',
			true
		);

		wp_enqueue_script(
			Main::PLUGIN_SLUG,
			NOVA_POSHTA_URL . 'assets/build/js/admin/admin.js',
			[
				'jquery',
				'np-select2',
				'np-tip-tip',
			],
			Main::VERSION,
			true
		);
	}

	/**
	 * Prepare default weight option for a select field.
	 *
	 * @return array
	 */
	private function prepare_default_weight_options(): array {

		$default_options = [
			'0' => esc_html__( 'Order Weight', 'shipping-nova-poshta-for-woocommerce' ),
		];

		foreach ( self::WEIGH_OPTIONS as $weight => $volume ) {
			$default_options[ (string) $weight ] = implode( ' x ', $volume ) . ' (' . $weight . ' kg)';
		}

		return $default_options;
	}

	/**
	 * Is current page
	 *
	 * @return bool
	 */
	private function is_plugin_page(): bool {

		global $current_screen, $post_type;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $_GET['page'] ) && $_GET['page'] === 'wc-orders' ) {
			return true;
		}

		if ( ! $current_screen ) {
			return false;
		}

		return 0 === strpos( $current_screen->base, 'toplevel_page_' . Main::PLUGIN_SLUG ) || 'shop_order' === $post_type || 'product' === $post_type || 'product_cat' === $current_screen->taxonomy;
	}

}
