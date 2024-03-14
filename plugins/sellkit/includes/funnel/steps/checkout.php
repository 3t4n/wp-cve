<?php

namespace Sellkit\Funnel\Steps;

use Sellkit\Funnel\Analytics\Data_Updater;
use Sellkit\Funnel\Contacts\Base_Contacts;
use Sellkit\Database;
use Sellkit_Funnel;
use Sellkit\Global_Checkout\Checkout as Global_Checkout;

defined( 'ABSPATH' ) || die();

/**
 * Class Sellkit_Checkout.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @since 1.1.0
 */
class Checkout {

	/**
	 * Funnel instance.
	 *
	 * @var Sellkit_Funnel Funnel object.
	 * @since 1.1.0
	 */
	public $funnel;

	/**
	 * Checkout constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		$this->update_funnel_data();

		if ( empty( $this->funnel->funnel_id ) ) {
			return;
		}

		add_action( 'woocommerce_checkout_create_order', [ $this, 'save_order_custom_meta_data' ], 10 );
		add_action( 'woocommerce_after_order_notes', [ $this, 'custom_checkout_hidden_fields' ], 10 );
		add_action( 'wc_ajax_update_order_review', [ $this, 'do_actions' ] );
	}

	/**
	 * Adding items to the checkout page.
	 *
	 * @since 1.1.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function do_actions() {
		// These variables should be used in checkout builder widget.
		$funnel_data       = $this->funnel->current_step_data;
		$optimization_data = ! empty( $funnel_data['data']['optimization'] ) ? $funnel_data['data']['optimization'] : '';
		$redirect_url      = ! empty( $optimization_data['closed_checkout_redirect_url'] ) ? $optimization_data['closed_checkout_redirect_url'] : site_url();
		$reset_cart        = isset( $funnel_data['data']['products']['reset_cart'] ) ? $funnel_data['data']['products']['reset_cart'] : 'true';

		add_action( 'template_redirect', function () use ( $funnel_data, $reset_cart ) {
			$product_settings = ! empty( $funnel_data['data']['products']['settings'] ) ? $funnel_data['data']['products']['settings'] : '';
			$bump_data        = ! empty( $funnel_data['bump'] ) ? $funnel_data['bump'] : '';

			if ( ! empty( $product_settings ) ) {
				set_query_var( 'funnel_product_settings', $product_settings );
				set_query_var( 'funnel_reset_cart', $reset_cart );
			}

			if ( ! empty( $bump_data ) ) {
				$bump_data = $this->get_valid_bumps( $bump_data );

				set_query_var( 'bump_data', $bump_data );
			}
		} );

		if (
			! empty( $optimization_data['expiration_date'] ) &&
			! empty( $optimization_data['expire_checkout_coupons_switch'] ) &&
			time() > $optimization_data['expiration_date'] &&
			'on' === $optimization_data['expire_checkout_coupons_switch']
		) {
			wp_redirect( $this->add_http_to_url( $redirect_url ) ); // phpcs:ignore
			exit;
		}

		if ( empty( $funnel_data ) ) {
			return;
		}

		if (
			empty( $funnel_data['data']['products']['list'] ) ||
			! is_array( $funnel_data['data']['products']['list'] )
		) {
			return;
		}

		$funnel_products = $funnel_data['data']['products']['list'];

		if ( ! sellkit()->has_valid_dependencies() ) {
			return;
		}

		$action = sellkit_htmlspecialchars( INPUT_GET, 'wc-ajax' );

		if ( 'update_order_review' !== $action && 'checkout' !== $action ) {
			if ( 'true' !== $reset_cart ) {
				foreach ( WC()->cart->get_cart() as $item ) {
					if ( empty( $item['product_id'] ) ) {
						continue;
					}

					if ( array_key_exists( $item['product_id'], $funnel_products ) ) {
						$funnel_products[ $item['product_id'] ]['quantity'] = $item['quantity'];

						continue;
					}

					$key = $item['product_id'];

					if ( ! empty( $item['variation_id'] ) ) {
						$key = $item['variation_id'];
					}

					$funnel_products[ $key ] = [
						'quantity' => $item['quantity'],
						'discount' => '',
						'discountType' => 'fixed',
					];
				}
			}

			WC()->cart->empty_cart();

			foreach ( $funnel_products as $product_id => $product ) {
				if ( empty( $product ) ) {
					continue;
				}

				$quantity = ! empty( $product['quantity'] ) ? $product['quantity'] : 1;

				wc()->cart->add_to_cart( $product_id, $quantity );
			}
		}

		if ( empty( $optimization_data ) ) {
			return;
		}

		if ( self::apply_coupon_validation( $optimization_data ) ) {
			foreach ( $optimization_data['auto_apply_coupons'] as $auto_apply_coupon ) {
				wc()->cart->add_discount( get_the_title( $auto_apply_coupon['value'] ) );
			}

			wc_clear_notices();
		}
	}

	/**
	 * Adds http if it is needed.
	 *
	 * @since 1.5.0
	 * @param string $url Entered url.
	 * @return string|boolean
	 */
	private function add_http_to_url( $url ) {
		if ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
			return 'http://' . $url;
		}

		return $url;
	}

	/**
	 * Saving the funnel data to the order.
	 *
	 * @since 1.1.0
	 * @param object $order Order object.
	 */
	public function save_order_custom_meta_data( $order ) {
		if ( empty( $this->funnel->next_step_data ) ) {
			return;
		}

		Base_Contacts::step_is_passed();
		$this->check_bump_acceptance( $order );

		$analytics_updater = new Data_Updater();

		$analytics_updater->set_funnel_id( $this->funnel->funnel_id );
		$analytics_updater->add_new_order_log( $order );

		$order->update_meta_data( 'sellkit_funnel_next_step_data', $this->funnel->next_step_data );
		$order->update_meta_data( 'sellkit_funnel_id', $this->funnel->funnel_id );
	}

	/**
	 * Adding page id field to the inputs.
	 *
	 * @since 1.1.0
	 */
	public function custom_checkout_hidden_fields() {
		$id                  = get_the_ID();
		$global_checkout_id  = get_option( Global_Checkout::SELLKIT_GLOBAL_CHECKOUT_OPTION, 0 );
		$default_checkout_id = wc_get_page_id( 'checkout' );

		if (
			(int) $global_checkout_id > 0 &&
			'publish' === get_post_status( $global_checkout_id ) &&
			$id === $default_checkout_id
		) {
			$steps       = get_post_meta( $global_checkout_id, 'nodes', true );
			$checkout_id = 0;

			foreach ( $steps as $step ) {
				$step['type'] = (array) $step['type'];

				if ( 'checkout' === $step['type']['key'] ) {
					$checkout_id = $step['page_id'];
				}
			}

			if ( (int) $checkout_id > 0 ) {
				$id = $checkout_id;
			}
		}

		woocommerce_form_field( 'sellkit_current_page_id', [
			'type'  => 'hidden',
			'class' => [ 'hidden form-row-wide' ],
		], $id );
	}

	/**
	 * Check If a coupon should be applied.
	 *
	 * @since 1.1.0
	 * @param array $optimization_data Optimization data.
	 */
	public static function apply_coupon_validation( $optimization_data ) {
		if (
			empty( $optimization_data['auto_apply_coupons_switch'] ) ||
			'on' !== $optimization_data['auto_apply_coupons_switch']
		) {
			return false;
		}

		if (
			empty( $optimization_data['auto_apply_coupons'] ) ||
			! is_array( $optimization_data['auto_apply_coupons'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Calculate total discount.
	 *
	 * @since 1.1.0
	 * @param string $total Total.
	 * @param string $value Value.
	 * @param string $type  Type.
	 * @return false|float|int|mixed
	 */
	public function calculate_discount( $total, $value, $type ) {
		if ( empty( $value ) || 1 > $value ) {
			return 0;
		}

		if ( strpos( $type, 'fixed' ) !== false ) {
			return $value;
		}

		if ( strpos( $type, 'percentage' ) !== false ) {
			return ( floatval( $total ) * floatval( $value ) ) / 100;
		}

		return 0;
	}

	/**
	 * Gets extracted post data.
	 *
	 * @since 1.1.0
	 * @param string $post_data Post data.
	 */
	private function get_current_page_id_by_post_data( $post_data ) {
		$vars           = explode( '&', $post_data );
		$extracted_data = [];

		foreach ( $vars as $value ) {
			$result                       = explode( '=', urldecode( $value ) );
			$extracted_data[ $result[0] ] = $result[1];
		}

		if ( empty( $extracted_data['sellkit_current_page_id'] ) ) {
			return null;
		}

		return $extracted_data['sellkit_current_page_id'];
	}

	/**
	 * Checks all bumps and return data.
	 *
	 * @since 1.1.0
	 * @param array $bump_data Bump data.
	 */
	public function get_valid_bumps( $bump_data ) {
		$valid_bumps = [];

		foreach ( $bump_data as $bump ) {
			$conditions = ! empty( $bump['data']['conditions'] ) ? $bump['data']['conditions'] : '';

			if ( ! empty( $conditions ) && empty( sellkit_conditions_validation( $conditions ) ) ) {
				continue;
			}

			$valid_bumps[] = $bump;
		}

		return $valid_bumps;
	}

	/**
	 * Checks if bump is accepted during checkout or not and updates table accordingly.
	 *
	 * @param object $order WooCommerce order object.
	 * @return void
	 *
	 * @since 1.5.0
	 * @access private
	 */
	private function check_bump_acceptance( $order ) {
		if (
			empty( $this->funnel->funnel_id ) ||
			empty( $this->funnel->current_step_data['bump'] )
		) {
			return;
		}

		$bump_product_ids = [];
		foreach ( $this->funnel->current_step_data['bump'] as $bump ) {
			if ( empty( $bump['data']['products'] ) ) {
				continue;
			}

			$bump_product_ids = array_merge( $bump_product_ids, array_keys( $bump['data']['products']['list'] ) );
		}

		$bump_exists_in_order = false;

		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_changes()['product_id'];

			if ( in_array( $product_id, $bump_product_ids, true ) ) {
				$bump_exists_in_order = true;
				break;
			}
		}

		if ( ! $bump_exists_in_order ) {
			return;
		}

		$database   = new Database();
		$old_values = [];

		// Getting old data.
		$result = $database->get( 'funnel_contact', [ 'id' => $_SESSION['entered_funnel_id'] ] );

		if ( ! empty( $result[0]['order_bump'] ) ) {
			$old_values = unserialize( $result[0]['order_bump'] ); // phpcs:ignore
		}

		$new_values = array_merge( $old_values, [ $this->funnel->current_step_data['page_id'] ] );

		$database->update(
			'funnel_contact',
			[ 'order_bump' => $new_values ],
			[ 'id' => $_SESSION['entered_funnel_id'] ]
		);
	}

	/**
	 * Updates funnel data.
	 *
	 * @since 1.1.0
	 */
	public function update_funnel_data() {
		if ( is_admin() ) {
			return;
		}

		$action = sellkit_htmlspecialchars( INPUT_GET, 'wc-ajax' );

		if ( 'update_order_review' === $action ) {
			$post_data = sellkit_htmlspecialchars( INPUT_POST, 'post_data' );

			$this->funnel = new Sellkit_Funnel( $this->get_current_page_id_by_post_data( $post_data ) );

			if ( empty( $this->funnel->funnel_id ) ) {
				$this->global_checkout_funnel_integration();
			}

			return;
		}

		$this->funnel = Sellkit_Funnel::get_instance();

		if ( empty( $this->funnel->funnel_id ) ) {
			$this->global_checkout_funnel_integration();
		}
	}

	/**
	 * Setup global checkout as a native sellkit funnel, so every functions works with no issue.
	 * detect funnel id in the global checkout.
	 *
	 * @since 1.7.4
	 */
	public function global_checkout_funnel_integration() {
		$global_checkout_id = get_option( Global_Checkout::SELLKIT_GLOBAL_CHECKOUT_OPTION, 0 );

		if ( $global_checkout_id > 0 && 'publish' === get_post_status( $global_checkout_id ) ) {
			$steps       = get_post_meta( $global_checkout_id, 'nodes', true );
			$checkout_id = 0;

			if ( ! is_array( $steps ) ) {
				return;
			}

			foreach ( $steps as $step ) {
				$step['type'] = (array) $step['type'];

				if ( 'checkout' === $step['type']['key'] ) {
					$checkout_id = $step['page_id'];
				}
			}

			if ( (int) $checkout_id > 0 ) {
				$this->funnel = new Sellkit_Funnel( $checkout_id );

				add_filter( 'sellkit_global_checkout_activated', function() {
					return true;
				} );
			}
		}
	}
}
