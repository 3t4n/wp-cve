<?php

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Cron\WC_BPost_Shipping_Cron_Runner;
use WC_BPost_Shipping\Label\Exception\WC_BPost_Shipping_Label_Exception_Base;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Attachment;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Controller;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Meta_Box_Controller;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Order_Overview;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Post;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping\Status\WC_BPost_Shipping_Status_Controller;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Solver;
use WC_BPost_Shipping\WC_Bpost_Shipping_Container as Container;
use WC_BPost_Shipping\Zip\WC_BPost_Shipping_Zip_Filename;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_BPost_Shipping_Hooks {

	/**
	 * Everywhere: Init when we use the shipping
	 */
	public function bpost_shipping_init() {
		load_plugin_textdomain(
			'bpost_shipping',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	function plugin_action_links( $links, $file ) {
		if ( basename( $file ) === 'bpost-shipping.php' ) {
			$links[] = sprintf(
				'<a href="%s">%s</a>',
				'admin.php?page=wc-settings&tab=shipping&section=bpost_shipping',
				bpost__( 'Configure' )
			);
		}

		return $links;
	}

	/**
	 * This implementation will disable the shipping rate cache.
	 * To conditionally disable the cache, replace `wp_rand()` with a conditional value.
	 * Changing the conditional value will invalidate the cache.
	 * Example: A hidden form field or a query string parameter.
	 *
	 * @see https://gist.github.com/woogists/271654709e1d27648546e83253c1a813
	 */
	public function woocommerce_cart_shipping_packages( $packages ) {
		foreach ( $packages as &$package ) {
			$package['rate_cache'] = wp_rand();
		}

		return $packages;
	}

	/**
	 * Admin: Add the plugin to the shipping methods list
	 *
	 * @param array $methods
	 *
	 * @return array
	 */
	public function bpost_shipping_add_method( $methods ) {
		$methods[ BPOST_PLUGIN_ID ] = 'WC_BPost_Shipping_Method';

		return $methods;
	}

	public function bpost_shipping_add_order_bulk_action( $actions ) {
		if ( ! isset( $actions['bpost_shipping_print_labels'] ) ) {
			$actions['bpost_shipping_print_labels'] = bpost__( 'Print bpost labels' );
		}

		return $actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $action Action name.
	 * @param array $ids List of ids.
	 *
	 * @return string
	 */
	public function handle_bulk_actions( $redirect_to, $action, $ids ) {
		if ( 'bpost_shipping_print_labels' === $action ) {
			return esc_url_raw( Container::get_label_url_generator()->get_generate_url( $ids ) );
		}

		return $redirect_to;
	}

	/**
	 * @param WC_Abstract_Order $order
	 *
	 * @return string
	 * @todo Externalize me!
	 */
	private function bpost_shipping_get_shipping_method_id( WC_Abstract_Order $order ) {
		$shipping_methods = $order->get_shipping_methods();
		if ( ! $shipping_methods ) {
			return '';
		}
		/** @var WC_Order_Item_Shipping|array $shipping_method */
		$shipping_method = array_pop( $shipping_methods );

		if ( is_array( $shipping_method ) && ! array_key_exists( 'method_id', $shipping_method ) ) {
			return $shipping_method['method_id'];
		}

		if ( $shipping_method instanceof WC_Order_Item_Shipping ) {
			$data = $shipping_method->get_data();
			if ( array_key_exists( 'method_id', $data ) ) {
				return $data['method_id'];
			}

			return $data['method_id'];
		}

		return '';
	}

	/**
	 * Checkout: After the order creation, update order meta with bpost data
	 *
	 * @param int $order_id
	 */
	public function bpost_shipping_update_order_metas( $order_id ) {
		$this->bpost_shipping_feed_info( $order_id, $_POST );
	}

	/**
	 * Checkout: After the closing of the SHM, save bpost data into the order
	 *
	 * @param int $order_id
	 * @param array $posted
	 */
	public function bpost_shipping_feed_info( $order_id, $posted ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			Container::get_logger()->error( __FUNCTION__ . ": order_id #$order_id is not an order", $posted );

			return;
		}

		if ( ! $this->is_bpost_shipping_from_order( $order ) ) {
			return;
		}

		Container::get_logger()->debug( __FUNCTION__ . '.posted', $posted );

		$api_factory = new WC_BPost_Shipping_Api_Factory(
			new WC_BPost_Shipping_Options_Base(),
			Container::get_logger()
		);

		$order_updater = new WC_BPost_Shipping_Order_Updater( $order, $posted, $api_factory->get_geo6_search() );
		$order_updater->update_order();
	}

	/**
	 * Order-received: Add a bpost block to show the shipping info
	 *
	 * @param WC_Order $order
	 */
	public function bpost_shipping_info_block( WC_Order $order ) {
		if ( ! $this->is_bpost_shipping_from_order( $order ) ) {
			return;
		}

		$order_display = new WC_BPost_Shipping_Order_Details_Controller(
			Container::get_adapter(),
			Container::get_assets_management(),
			new WC_BPost_Shipping_Meta_Handler(
				Container::get_adapter(),
				Container::get_meta_type(),
				$order->get_id()
			),
			Container::get_options_label(),
			$order
		);
		$order_display->load_template();
	}

	/**
	 * Checkout: Put 'as from' at the estimated shipping cost
	 *
	 * @param $method_label
	 * @param WC_Shipping_Rate $method
	 *
	 * @return string
	 */
	public function bpost_shipping_prefix_estimated_cost( $method_label, WC_Shipping_Rate $method ) {
		if ( $method->method_id !== BPOST_PLUGIN_ID ) {
			return $method_label;
		}

		$img = '<img
		style="display: inline-block; width: 32px; margin: 0 4px;"
		id="bpost-logo-list"
		alt="bpost-logo"
		src="' . BPOST_PLUGIN_URL . '/public/images/bpost-logo.png"
		/>';

		if ( strpos( $method->id, '_error' ) !== false ) {
			return str_replace(
				$method->label,
				$img . $method->label,
				$method_label
			);
		}

		$as_from_text = ' ' . bpost__( '(as from)' );

		if ( (int) $method->cost === 0 ) {
			// We want « bpost shipping as from (Free) » instead of « bpost shipping (as from) (Free) »
			$as_from_text = ': ' . bpost__( 'Free shipping available' );
		}
		if ( array_key_exists( 'post_data', $_POST ) ) {
			$post_data = wp_parse_args( $_POST['post_data'] );
			if ( $post_data['bpost_shm_already_called'] === 'yes' ) {
				$as_from_text = '';
			}
		}

		return str_replace(
			$method->label,
			$img . $method->label . $as_from_text,
			$method_label
		);
	}

	/**
	 * Admin: We add a block in the order details page with the bpost shipping info
	 *
	 * @param WC_Order $order
	 */
	public function bpost_shipping_admin_details( WC_Order $order ) {
		if ( ! $this->is_bpost_shipping_from_order( $order ) ) {
			return;
		}

		$admin_order_data = new WC_BPost_Shipping_Admin_Order_Data_Controller(
			Container::get_adapter(),
			Container::get_assets_management(),
			new WC_BPost_Shipping_Meta_Handler(
				Container::get_adapter(),
				Container::get_meta_type(),
				$order->get_id()
			),
			$order
		);
		$admin_order_data->load_template();
	}

	/**
	 * Create and maintains a adapter instance
	 * @return WC_BPost_Shipping_Adapter_Woocommerce
	 */
	private function bpost_shipping_get_adapter() {
		return WC_BPost_Shipping_Adapter_Woocommerce::get_instance();
	}

	/**
	 * After shm popin: create virtual page for shm callback
	 */
	public function bpost_shipping_virtual_page_shm_callback() {
		$regexp_list = implode(
			'|',
			array(
				\WC_BPost_Shipping_Shm_Callback_Controller::RESULT_SHM_CALLBACK_CONFIRM,
				\WC_BPost_Shipping_Shm_Callback_Controller::RESULT_SHM_CALLBACK_CANCEL,
				\WC_BPost_Shipping_Shm_Callback_Controller::RESULT_SHM_CALLBACK_ERROR,
			)
		);

		$callback = new WC_BPost_Shipping_Shm_Callback_Controller(
			Container::get_adapter(),
			Container::get_assets_management(),
			Container::get_logger(),
			filter_input(
				INPUT_GET,
				'result',
				FILTER_VALIDATE_REGEXP,
				array(
					'options' => array(
						'regexp' => '#^(' . $regexp_list . ')$#',
					),
				)
			)
		);
		$callback->load_template();

		Container::get_logger()->debug( __FUNCTION__ . '._POST', $_POST );

		ob_flush();
		die();
	}

	/**
	 * @throws Exception
	 */
	public function bpost_virtual_page_label() {
		$label_controller = new WC_BPost_Shipping_Label_Controller(
			Container::get_adapter(),
			Container::get_options_label(),
			Container::get_label_retriever(),
			new WC_BPost_Shipping_Zip_Filename( new DateTime() ),
			$_GET
		);
		try {
			$label_controller->load_template();

			ob_flush();
			die();
		} catch ( WC_BPost_Shipping_Label_Exception_Base $e ) {
			Container::get_logger()->critical( $e->getMessage() );
			wp_die(
				"
<div class='bpost-error'>
	<img src='" . BPOST_PLUGIN_URL . "public/images/bpost_logo_4c_c.png' width='100px'>
</div>
<p>Plugin bpost-shipping error: <br>" . $e->getMessage() . '</p>
'
			);
		}
	}

	public function bpost_refresh_bpost_status() {
		$status_controller = new WC_BPost_Shipping_Status_Controller(
			Container::get_adapter(),
			Container::get_api_factory()->get_api_status(),
			$_GET
		);
		$status_controller->load_template();

		die();
	}

	/**
	 * Before checkout: api for param validation
	 */
	public function bpost_shipping_api_loader() {
		$posted_obj           = new WC_BPost_Shipping_Posted( $_POST );
		$bpost_street_builder = new WC_BPost_Shipping_Street_Builder( new WC_BPost_Shipping_Street_Solver() );

		$cart = new WC_BPost_Shipping_Cart( WC()->cart );

		$data_builder = new WC_BPost_Shipping_Data_Builder(
			$cart,
			new WC_BPost_Shipping_Address( $bpost_street_builder, WC()->customer, $posted_obj ),
			new WC_BPost_Shipping_Options_Base(),
			$bpost_street_builder,
			new WC_BPost_Shipping_Delivery_Methods(
				Container::get_api_factory()->get_api_connector()->fetchProductConfig()
			)
		);

		header( 'Content-Type: application/json', true );

		$result                     = array( 'status' => true );
		$result['bpost_data']       = $data_builder->get_bpost_data();
		$result['shipping_address'] = $data_builder->get_shipping_address();

		echo json_encode( $result );
		ob_flush();
		die();
	}

	/**
	 * Checkout: add fields to include into checkout process
	 *
	 * @param array $checkout_fields
	 *
	 * @return array
	 */
	public function bpost_shipping_filter_checkout_fields( $checkout_fields ) {
		$checkout_fields['bpost'] = array(
			'bpost_email'              => array(),
			'bpost_phone'              => array(),
			'bpost_delivery_method'    => array(),
			'bpost_delivery_price'     => array(),
			'bpost_delivery_date'      => array(),
			'bpost_delivery_point_id'  => array(),
			'bpost_delivery_address'   => array(),
			'bpost_postal_location'    => array(),
			'bpost_order_reference'    => array(),
			'bpost_shm_already_called' => array(),
		);

		return $checkout_fields;
	}

	/**
	 * Checkout: add bpost status after the shipping method
	 *
	 * @param string $shipping_method
	 * @param WC_Abstract_Order $order
	 *
	 * @return string
	 */
	public function bpost_shipping_order_shipping_method( $shipping_method, WC_Abstract_Order $order ) {
		if ( ! $this->is_bpost_shipping_from_order( $order ) ) {
			return $shipping_method;
		}

		if ( ! Container::get_adapter()->is_admin() ) {
			return $shipping_method;
		}

		$meta_handler = new \WC_BPost_Shipping_Meta_Handler(
			Container::get_adapter(),
			Container::get_meta_type(),
			$order->get_id()
		);

		return $shipping_method . ' - ' . bpost__( 'status: ' ) . $meta_handler->get_status();
	}

	private function must_the_button_is_displayed( string $chosen_shipping_method ): bool {
		switch ( $chosen_shipping_method ) {
			case BPOST_PLUGIN_ID:
			case BPOST_PLUGIN_ID . '_error':
				return true;
			default:
				return false;
		}
	}

	private function is_bpost_shipping_from_session(): bool {
		if ( ! $this->need_shipping() ) {
			return false;
		}

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

		foreach ( $chosen_shipping_methods as $shipping_method ) {
			switch ( $shipping_method ) {
				case BPOST_PLUGIN_ID:
				case BPOST_PLUGIN_ID . '_error':
					Container::get_logger()->notice( 'It is a bpost shipping', $chosen_shipping_methods );

					return true;
			}
		}

		Container::get_logger()->notice( 'It is not a bpost shipping', $chosen_shipping_methods );

		return false;
	}

	/**
	 * Does the cart need a shipping? -> Does teh cart
	 * false if the cart contains only virtual items, else true
	 */
	private function need_shipping(): bool {
		$shipping_packages       = WC()->cart->get_shipping_packages();
		$shipping_packages_count = count( $shipping_packages );

		Container::get_logger()->notice(
			'bpost_shipping_options_validation: bpost order ?',
			[
				'shipping_packages'       => $shipping_packages,
				'chosen_shipping_methods' => WC()->session->get( 'chosen_shipping_methods' ),
			]
		);

		// Condition to avoid bug if there is no item or more than 1 item
		if ( $shipping_packages_count !== 1 ) {
			Container::get_logger()->notice(
				"There is $shipping_packages_count shipping package(s)!",
				$shipping_packages
			);

			return false;
		}

		$contents = $shipping_packages[0]['contents'];

		if ( empty( $contents ) ) {
			// all cart products are virtual (or the cart is empty...)
			Container::get_logger()->notice( 'All products seem virtual (no shipping needed)' );

			return false;
		}

		return true;
	}

	/**
	 * @param WC_Abstract_Order $order
	 *
	 * @return bool
	 */
	private function is_bpost_shipping_from_order( WC_Abstract_Order $order ) {
		return $this->bpost_shipping_get_shipping_method_id( $order ) === BPOST_PLUGIN_ID;
	}

	/**
	 * @param string $post_type
	 * @param WP_Post $post
	 */
	public function bpost_order_details_box_meta( $post_type, $post ) {
		if ( $post_type !== 'shop_order' || ! $post instanceof WP_Post ) {
			return;
		}
		$order = new WC_Order( $post->ID );

		if ( ! $this->is_bpost_shipping_from_order( $order ) ) {
			return;
		}

		add_meta_box(
			'bpost-order-box',
			bpost__( 'bpost labels' ),
			array( $this, 'bpost_order_details_box_meta_add' ),
			'shop_order',
			'side',
			'high'
		);
	}

	/**
	 * @param WP_Post $post
	 */
	public function bpost_order_details_box_meta_add( WP_Post $post ) {
		$adapter      = Container::get_adapter();
		$meta_handler = new \WC_BPost_Shipping_Meta_Handler(
			$adapter,
			new \WC_BPost_Shipping_Meta_Type( $adapter ),
			$post->ID
		);

		$label_meta_box_controller = new WC_BPost_Shipping_Label_Meta_Box_Controller(
			$adapter,
			new WC_BPost_Shipping_Label_Attachment(
				Container::get_adapter(),
				Container::get_options_label(),
				Container::get_label_url_generator(),
				Container::get_label_retriever(),
				Container::get_label_resolver_path(),
				new WC_BPost_Shipping_Label_Post( $meta_handler, new WC_Order( $post->ID ) )
			)
		);

		$label_meta_box_controller->load_template();
	}

	/**
	 * @param $actions
	 * @param WC_Order $the_order
	 *
	 * @return string[]
	 */
	public function bpost_order_review_admin_actions( $actions, WC_Order $the_order ) {
		if ( ! $this->is_bpost_shipping_from_order( $the_order ) ) {
			return $actions;
		}

		$meta_handler = new \WC_BPost_Shipping_Meta_Handler(
			Container::get_adapter(),
			Container::get_meta_type(),
			$the_order->get_id()
		);

		$label_attachment = new WC_BPost_Shipping_Label_Attachment(
			Container::get_adapter(),
			Container::get_options_label(),
			Container::get_label_url_generator(),
			Container::get_label_retriever(),
			Container::get_label_resolver_path(),
			new WC_BPost_Shipping_Label_Post( $meta_handler, $the_order )
		);
		$order_overview   = new WC_BPost_Shipping_Label_Order_Overview( $label_attachment );

		return $order_overview->filter_actions( $actions );
	}

	/**
	 * Schedule the cache cleaning for each day on plugin activation
	 */
	public function bpost_shipping_cron_cache_activation() {
		wp_schedule_event(
			current_time( 'timestamp' ),
			'daily',
			'cache_clean'
		); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
	}

	/**
	 * Unschedule the cache cleaning
	 */
	public function bpost_shipping_cron_cache_deactivation() {
		wp_clear_scheduled_hook( 'cache_clean' );
	}

	/**
	 * Run bpost cache cleaning cron
	 */
	public function bpost_shipping_cron_cache_clean_run() {
		$cron_runner = new WC_BPost_Shipping_Cron_Runner(
			Container::get_adapter(),
			Container::get_options_label()
		);
		$cron_runner->execute();
	}

	public function enqueue_scripts_frontend() {
		Container::get_assets_management()->wp_enqueue_script();
	}

	public function enqueue_scripts_admin() {
		Container::get_assets_management()->admin_enqueue_script();
	}

	public function add_custom_checkout_fields() {
		if ( ! is_checkout() ) {
			return;
		}

		$inputs = [
			'bpost_email',
			'bpost_phone',
			'bpost_delivery_method',
			'bpost_delivery_price',
			'bpost_delivery_date',
			'bpost_delivery_point_id',
			'bpost_postal_location',
			'bpost_order_reference',
			'bpost_shm_already_called',
		];
		echo <<<HTML
<div style="display: none">
<h2>bpost shipping details</h2>
HTML;

		foreach ( $inputs as $key ) {
			echo <<<HTML
<p class="form-row " id="{$key}_field" data-priority="">
	<label for="$key" class="">
		$key
		<span class="optional">(facultatif)</span>
		</label>
	<span class="woocommerce-input-wrapper">
		<input type="text" class="input-text " name="$key" id="$key" placeholder="" value="">
	</span>
</p>
HTML;
		}

		$key = 'bpost_delivery_address';
		echo <<<HTML
<p class="form-row " id="{$key}_field" data-priority="">
	<label for="$key" class="">
		$key
		<span class="optional">(facultatif)</span>
		</label>
	<span class="woocommerce-input-wrapper">
		<textarea class="input-text " name="$key" id="$key" placeholder="" value=""></textarea>
	</span>
</p>
HTML;
		echo '</div>';
	}

	public function woocommerce_after_shipping_rate_add_shipping_options( $method, $index ) {
		/** @var WC_Shipping_Rate $method */
		if ( $method->get_method_id() !== BPOST_PLUGIN_ID ) {
			return;
		}

		if ( ! is_checkout() ) {
			return;
		}

		$chosen_shipping_methods = WC()->session->chosen_shipping_methods;
		$chosen_shipping_method  = isset( $chosen_shipping_methods[ $index ] ) ? $chosen_shipping_methods[ $index ] : '';

		if ( ! $this->must_the_button_is_displayed( $chosen_shipping_method ) ) {
			return;
		}

		$posted_data = wp_parse_args( $_POST['post_data'] ?? [] );

		// array_key_exists( 'bpost_shm_already_called', $posted_data ) && $posted_data['bpost_shm_already_called'] === 'yes'
		if (
			!array_key_exists( 'bpost_shm_already_called', $posted_data )
			|| $posted_data['bpost_shm_already_called'] !== 'yes'
		) {
			$parcel_shop_info       = bpost__( 'You have to specify a delivery method' );
			$parcel_shop_info_color = 'red';
			$button_label           = bpost__( 'Your bpost delivery method' );
			$button_class           = 'alt';
		} else {
			// Here to help the generation of the pot file:
			if ( false ) {
				bpost__( 'bpost_method_Regular' );
				bpost__( 'bpost_method_Pugo' );
				bpost__( 'bpost_method_Parcels depot' );
				bpost__( 'bpost_method_bpack BUSINESS' );
				bpost__( 'bpost_method_Pugo international' );
			}
			$parcel_shop_method     = bpost__( 'bpost_method_' . $posted_data['bpost_delivery_method'] );
			$delivery_address       = nl2br( $posted_data['bpost_delivery_address'] );
			$parcel_shop_info       = <<<HTML
$parcel_shop_method<br>
$delivery_address<br>
HTML;
			$parcel_shop_info_color = 'green';
			$button_label           = bpost__( 'Change the delivery method' );
			$button_class           = '';
		}

		echo <<<HTML
<p style="font-weight: 400">
	<button class="js-bpost-shipping-options-modal button $button_class">$button_label</button>
	<br>
	<span id="bpost_shipping_info" style="color: $parcel_shop_info_color">$parcel_shop_info</span>
</p>
HTML;
	}

	public function bpost_shipping_options_validation() {
		// Check that a Delivery Point has been chosen when mandatory
		$posted_data = $_POST;
		if ( ! $this->is_bpost_shipping_from_session() ) {
			return;
		}

		Container::get_logger()->notice(
			'bpost_shm_already_called = ' . $posted_data['bpost_shm_already_called'],
			array_keys( WC()->payment_gateways->get_available_payment_gateways() )
		);

		if ( $posted_data['bpost_shm_already_called'] !== 'yes' ) {
			$error_message = bpost__( 'Please, specify a bpost delivery method!' );

			Container::get_logger()->warning( $error_message );

			throw new Exception( $error_message ); // throw an exception will provide a wp_notice(..., 'error')
		}
	}
}
