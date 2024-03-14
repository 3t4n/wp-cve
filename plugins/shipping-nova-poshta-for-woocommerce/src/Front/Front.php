<?php
/**
 * Front area
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Front;

use NovaPoshta\Main;
use NovaPoshta\Language;

/**
 * Class Front
 *
 * @package NovaPoshta\Front
 */
class Front {

	/**
	 * Language
	 *
	 * @var Language
	 */
	private $language;

	/**
	 * Front constructor.
	 *
	 * @param Language $language Language.
	 */
	public function __construct( Language $language ) {

		$this->language = $language;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue styles
	 */
	public function enqueue_styles() {

		if ( ! is_checkout() ) {
			return;
		}
		wp_enqueue_style( Main::PLUGIN_SLUG, NOVA_POSHTA_URL . 'assets/build/css/main.css', [], Main::VERSION );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {

		if ( ! is_checkout() ) {
			return;
		}

		wp_enqueue_script(
			'np-select2',
			NOVA_POSHTA_URL . 'assets/build/js/vendor/select2.min.js',
			[ 'jquery' ],
			Main::VERSION,
			true
		);

		wp_enqueue_script(
			'select2-i18n-' . $this->language->get_current_language(),
			NOVA_POSHTA_URL . 'assets/build/js/vendor/i18n/' . $this->language->get_current_language() . '.js',
			[ 'jquery', 'np-select2' ],
			Main::VERSION,
			true
		);

		wp_enqueue_script(
			Main::PLUGIN_SLUG,
			NOVA_POSHTA_URL . 'assets/build/js/main.js',
			[
				'jquery',
				'np-select2',
			],
			Main::VERSION,
			true
		);

		wp_localize_script(
			Main::PLUGIN_SLUG,
			'shippingNovaPoshtaForWoocommerce',
			[
				'url'      => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( Main::PLUGIN_SLUG ),
				'language' => $this->language->get_current_language(),
			]
		);
	}
}
