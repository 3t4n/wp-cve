<?php
/**
 * PeachPay express checkout extension entry class.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

define( 'PEACHPAY_EXPRESS_CHECKOUT_SLUG', 'express-checkout' );
define( 'PEACHPAY_EXPRESS_CHECKOUT_VERSION', '3' );

require_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-admin-section.php';
require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-extension.php';

register_deactivation_hook( PEACHPAY_PLUGIN_FILE, 'PeachPay_Express_Checkout::maybe_delete_express_checkout_page' );

/**
 * PeachPay Express checkout extension.
 */
class PeachPay_Express_Checkout {
	use PeachPay_Extension;

	/**
	 * Should the extension load?
	 */
	public static function should_load() {
		return true;
	}

	/**
	 * Is the integration enabled?
	 */
	public static function enabled() {
		// Only enabled if the plugin is premium
		return PeachPay::has_premium()
			&& PeachPay::get_option( 'pp_checkout_enable', 'yes' ) === 'yes';
	}

	/**
	 * Callback method for when the extension is loaded.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function includes( $enabled ) {
		if ( $enabled ) {
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/hooks.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/functions.php';

			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/routes/wc-ajax-calculate-cart.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/routes/wc-ajax-change-quantity.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/routes/wc-ajax-validate-checkout.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/routes/wp-ajax-login.php';

			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/shortcodes/shortcode-checkout-button.php';

			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/compatibility/wp-rocket.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/compatibility/breeze.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/compatibility/woocommerce-manual-phone-orders.php';

		}
		if ( is_admin() ) {
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/functions.php';
			include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/routes/wp-ajax-checkout-enable.php';

			add_action( 'wp_ajax_pp-checkout-enable', 'pp_checkout_wp_ajax_checkout_enable' );
		}
	}

	/**
	 * Callback method for WordPress init action.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function init( $enabled ) {
		if ( ! $enabled ) {
			self::maybe_delete_express_checkout_page();
			return;
		}

		self::maybe_insert_express_checkout_page();

		if ( pp_is_express_checkout() ) {
			define( 'PEACHPAY_CHECKOUT', 1 );
		} elseif ( peachpay_get_settings_option( 'peachpay_express_checkout_window', 'make_pp_the_only_checkout' ) && pp_should_display_public() ) {
			// Hides "proceed to checkout" WooCommerce checkout button on the cart page and mini cart.
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
			remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
		}
	}

	/**
	 * Callback method for the "wp_enqueue_scripts" action.
	 *
	 * @param boolean $enabled If the extension is enabled.
	 */
	private function enqueue_public_scripts( $enabled ) {
		if ( ! $enabled || ! pp_should_display_public() ) {
			return;
		}

		PeachPay::enqueue_style( 'pp-icon', 'public/icon.css' );
		PeachPay::enqueue_script( 'pp-sentry-lib', 'https://browser.sentry-cdn.com/7.59.2/bundle.min.js', array(), false, true );

		if ( ! pp_is_express_checkout() ) {
			PeachPay::enqueue_style( 'pp-button', 'public/dist/express-checkout-button.bundle.css' );
			PeachPay::enqueue_script( 'pp-button', 'public/dist/express-checkout-button.bundle.js' );
			PeachPay::register_script_data( 'pp-button', 'peachpay_button', pp_checkout_button_data() );
		}
	}

	/**
	 * Inserts the PeachPay Express Checkout WordPress page if it does not exist.
	 */
	public static function maybe_insert_express_checkout_page() {
		$page = array(
			'post_type'      => 'page',
			'post_name'      => PEACHPAY_EXPRESS_CHECKOUT_SLUG,
			'post_title'     => 'Express Checkout',
			'post_content'   => '<!-- wp:paragraph --><p>Oops you are not supposed to see this.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you are viewing this outside of the Wordpress admin dashboard then PeachPay may have been deactivated or uninstalled unexpectedly or something has gone wrong.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>If you deactivated or uninstalled PeachPay this page may be safely removed in the Wordpress admin dashboard "Pages" section.</p><!-- /wp:paragraph -->',
			'post_template'  => peachpay_locate_template( 'html-express-checkout.php', PEACHPAY_ABSPATH . 'core/modules/express-checkout/templates/' ),
			'post_status'    => 'publish',
			'post_author'    => 1,
			'theme_location' => 'none',
		);

		global $pp_checkout_page_id;

		$existing_page = get_page_by_path( PEACHPAY_EXPRESS_CHECKOUT_SLUG, OBJECT, 'page' );
		if ( ! is_null( $existing_page ) && $existing_page instanceof WP_Post ) {
			$pp_checkout_page_id = $existing_page->ID;

			if ( PeachPay::get_option( 'express_checkout_page_version' ) === PEACHPAY_EXPRESS_CHECKOUT_VERSION ) {
				return;
			}

			$page['ID'] = $existing_page->ID;
		}

		$post_id = wp_insert_post( $page, true );
		if ( ! is_int( $post_id ) ) {
			PeachPay::delete_option( 'express_checkout_page_version' );
			return;
		}

		$pp_checkout_page_id = $post_id;
		PeachPay::update_option( 'express_checkout_page_version', PEACHPAY_EXPRESS_CHECKOUT_VERSION );

		/**
		 * Fires after the PeachPay Express Checkout WordPress page is added.
		 *
		 * @since 1.99.2
		 */
		do_action( 'pp_checkout_page_added' );
	}

	/**
	 * Removes the PeachPay Express Checkout WordPress page if it exists.
	 */
	public static function maybe_delete_express_checkout_page() {
		$existing_page = get_page_by_path( PEACHPAY_EXPRESS_CHECKOUT_SLUG, OBJECT, 'page' );
		if ( is_null( $existing_page ) || ! $existing_page instanceof WP_Post ) {
			return;
		}

		PeachPay::delete_option( 'express_checkout_page_version' );

		wp_delete_post( $existing_page->ID, true );

		/**
		 * Fires after the PeachPay Express Checkout WordPress page is removed.
		 *
		 * @since 1.99.2
		 */
		do_action( 'pp_checkout_page_removed' );
	}
}
PeachPay_Express_Checkout::instance();
