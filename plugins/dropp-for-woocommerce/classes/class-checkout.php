<?php
/**
 * Ajax
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Actions\Create_Dropp_Location_Script_Url_Action;
use Dropp\Components\Choose_Location_Button;
use Dropp\Components\Location_Picker;
use WC_Order;

/**
 * Checkout
 */
class Checkout {
	/**
	 * Setup
	*/
	public static function setup() {
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::checkout_javascript' );
		add_action( 'woocommerce_checkout_order_processed', __CLASS__ . '::tag_order', 10, 3 );
		add_action( 'dropp_schedule_add_new', 'Dropp\Checkout::add_new', 10, 0 );
		add_action( 'woocommerce_blocks_enqueue_checkout_block_scripts_before', __CLASS__ . '::enqueue_stuff' );
	}

	/**
	 * Tag order for processing
	 *
	 * @param integer $order_id Order ID.
	 * @param array $posted_data POST data.
	 * @param WC_Order $order Order Order.
	*/
	public static function tag_order( int $order_id, array $posted_data, WC_Order $order ) {
		$adapter = new Order_Adapter( $order );
		if ( ! $adapter->is_dropp() ) {
			return;
		}

		// Tag the order and schedule a new event.
		$order->update_meta_data( '_dropp_added', 0 );
		$order->save();

		if ( ! wp_next_scheduled( 'dropp_schedule_add_new' ) ) {
			// Schedule adding new items.
			wp_schedule_single_event( time() + 5, 'dropp_schedule_add_new' );
		}
	}

	/**
	 * Add new
	*/
	public static function add_new(): void {
		global $wpdb;

		// API request to add new.
		$post_ids = $wpdb->get_col(
			"SELECT ID FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_ID
			AND pm.meta_key = \"_dropp_added\"
			AND pm.meta_value = \"0\""
		);

		foreach ( $post_ids as $post_ID ) {
			$order   = wc_get_order( $post_ID );
			$adapter = new Order_Adapter( $order );

			// @TODO: Consider logging events where $adapter->add_new() returns false.
			$adapter->add_new();

			$order->update_meta_data( '_dropp_added', '1' );
			$order->save();
		}
	}

	public static function checkout_javascript(): void {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		self::enqueue_stuff();
	}

	/**
	 * Load checkout javascript
	*/
	public static function enqueue_stuff(): void {
		// Add styles.
		wp_register_style(
			'dropp-for-woocommerce',
			plugins_url( 'assets/css/dropp.css', __DIR__ ),
			[],
			Dropp::VERSION
		);
		wp_enqueue_style( 'dropp-for-woocommerce' );

		// Add javascript.
		wp_register_script(
			'dropp-for-woocommerce',
			plugins_url( 'assets/js/dropp.js', __DIR__ ),
			array( 'jquery' ),
			Dropp::VERSION,
			true
		);
		wp_enqueue_script( 'dropp-for-woocommerce' );

		$location_data = WC()->session->get( 'dropp_session_location' );
		// Add javascript variables.
		wp_localize_script(
			'dropp-for-woocommerce',
			'_dropp',
			[
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'storeid'           => Shipping_Method\Dropp::get_instance()->store_id,
				'dropplocationsurl' => (new Create_Dropp_Location_Script_Url_Action)(),
				'location_picker'   => (new Location_Picker(null))->render(),
				'location_selected' => !empty($location_data),
				'i18n'              => [
					'choose_location_instructions' => esc_html__( 'Please select a dropp location before you continue', 'dropp-for-woocommerce' ),
					'error_loading' => esc_html__( 'Could not load the location selector. Someone from the store will contact you regarding the delivery location.', 'dropp-for-woocommerce' ),
				],
			]
		);
	}
}
