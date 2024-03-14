<?php

class Furgonetka_Blocks {

	/**
	 * Fields
	 */
	const FIELD_SELECTED_POINT     = 'selected_point';
	const FIELD_SELECTED_POINT_COD = 'selected_point_cod';

	const FIELD_SERVICE = 'service';
	const FIELD_CODE    = 'code';
	const FIELD_NAME    = 'name';
	const FIELD_COD     = 'cod';

	/**
	 * @var Furgonetka_Loader
	 */
	private $loader;

	/**
	 * @var Furgonetka_Public
	 */
	private $public;

	/**
	 * @var Furgonetka_Loader $loader
	 * @var Furgonetka_Public $public
	 */
	public function __construct( $loader, $public ) {
		$this->loader = $loader;
		$this->public = $public;
	}

	/**
	 * Initialize blocks backend
	 *
	 * @return void
	 */
	public function init() {
		/**
		 * Register WooCommerce Blocks integration (Store API)
		 */
		$this->add_action( 'woocommerce_blocks_checkout_block_registration', array( $this, 'register_checkout_integrations' ) );

		/**
		 * Register WooCommerce Blocks integration extension data/endpoint (Store API)
		 */
		$this->add_action( 'woocommerce_blocks_loaded', array( $this, 'register_extension' ) );

		/**
		 * Order point validation (Store API)
		 */
		$this->add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'checkout_validation' ) );

		/**
		 * Order point validation (Store API)
		 */
		$this->add_action( 'woocommerce_store_api_checkout_update_order_meta', array( $this, 'save_point_to_order' ) );
	}

	/**
	 * Register checkout integrations instances
	 *
	 * @param \Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry $integration_registry
	 * @return void
	 */
	public function register_checkout_integrations( $integration_registry ) {
		if ( ! interface_exists( \Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface::class ) ) {
			/**
			 * Blocks integration is not supported
			 */
			return;
		}

		require_once __DIR__ . '/class-furgonetka-pickup-point-block-integration.php';

		$integration_registry->register( new Furgonetka_Pickup_Point_Block_Integration() );
	}

	/**
	 * Register extension data callbacks
	 *
	 * @return void
	 */
	public function register_extension() {
		if (
			! function_exists( 'woocommerce_store_api_register_update_callback' ) ||
			! function_exists( 'woocommerce_store_api_register_endpoint_data' ) ||
			! class_exists( \Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema::class )
		) {
			/**
			 * Store API is not supported
			 */
			return;
		}

		woocommerce_store_api_register_update_callback(
			array(
				'namespace' => 'furgonetka',
				'callback'  => array( $this, 'set_extension_data' ),
			)
		);

		woocommerce_store_api_register_endpoint_data(
			array(
				'endpoint'        => \Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema::IDENTIFIER,
				'namespace'       => 'furgonetka',
				'data_callback'   => array( $this, 'get_extension_data' ),
				'schema_callback' => array( $this, 'get_extension_schema' ),
				'schema_type'     => ARRAY_A,
			)
		);
	}

	/**
	 * Get schema returned via extension
	 *
	 * @return array[]
	 */
	public function get_extension_schema() {
		$selected_point_schema = array(
			self::FIELD_SERVICE => array(
				'description' => __( 'Pickup point service', 'furgonetka' ),
				'type'        => 'string',
				'readonly'    => true,
			),
			self::FIELD_CODE    => array(
				'description' => __( 'Pickup point code', 'furgonetka' ),
				'type'        => 'string',
				'readonly'    => true,
			),
			self::FIELD_NAME    => array(
				'description' => __( 'Pickup point name', 'furgonetka' ),
				'type'        => 'string',
				'readonly'    => true,
			),
			self::FIELD_COD     => array(
				'description' => __( 'Cash on delivery', 'furgonetka' ),
				'type'        => 'string',
				'readonly'    => true,
			),
		);

		return array(
			self::FIELD_SELECTED_POINT     => array(
				'description' => __( 'Selected point', 'furgonetka' ),
				'type'        => 'object',
				'readonly'    => true,
				'properties'  => $selected_point_schema,
			),
			self::FIELD_SELECTED_POINT_COD => array(
				'description' => __( 'Selected point (COD)', 'furgonetka' ),
				'type'        => 'object',
				'readonly'    => true,
				'properties'  => $selected_point_schema,
			),
		);
	}

	/**
	 * Get data to return via extension
	 *
	 * @return array
	 */
	public function get_extension_data() {
		/**
		 * Get session data
		 */
		$service = $this->get_selected_service();

		$current_selection_by_service     = WC()->session->get( FURGONETKA_PLUGIN_NAME . '_pointTo' );
		$current_selection_by_service_cod = WC()->session->get( FURGONETKA_PLUGIN_NAME . '_pointToCod' );

		/**
		 * Parse session data
		 */
		$data     = isset( $current_selection_by_service[ $service ] ) ? $current_selection_by_service[ $service ] : array();
		$data_cod = isset( $current_selection_by_service_cod[ $service ] ) ? $current_selection_by_service_cod[ $service ] : array();

		return array(
			self::FIELD_SELECTED_POINT     => array(
				self::FIELD_SERVICE => isset( $data[ self::FIELD_SERVICE ] ) ? $data[ self::FIELD_SERVICE ] : '',
				self::FIELD_CODE    => isset( $data[ self::FIELD_CODE ] ) ? $data[ self::FIELD_CODE ] : '',
				self::FIELD_NAME    => isset( $data[ self::FIELD_NAME ] ) ? $data[ self::FIELD_NAME ] : '',
			),
			self::FIELD_SELECTED_POINT_COD => array(
				self::FIELD_SERVICE => isset( $data_cod[ self::FIELD_SERVICE ] ) ? $data_cod[ self::FIELD_SERVICE ] : '',
				self::FIELD_CODE    => isset( $data_cod[ self::FIELD_CODE ] ) ? $data_cod[ self::FIELD_CODE ] : '',
				self::FIELD_NAME    => isset( $data_cod[ self::FIELD_NAME ] ) ? $data_cod[ self::FIELD_NAME ] : '',
			),
		);
	}

	/**
	 * Set extension data
	 *
	 * @param array $data
	 * @return void
	 */
	public function set_extension_data( $data ) {
		$this->public->save_point_to_session_internal(
			isset( $data[ self::FIELD_SERVICE ] ) ? $data[ self::FIELD_SERVICE ] : '',
			isset( $data[ self::FIELD_CODE ] ) ? $data[ self::FIELD_CODE ] : '',
			isset( $data[ self::FIELD_NAME ] ) ? $data[ self::FIELD_NAME ] : '',
			isset( $data[ self::FIELD_COD ] ) ? $data[ self::FIELD_COD ] : ''
		);
	}

	/**
	 * Validate selected point
	 *
	 * @param WC_Order $order
	 * @return void
	 * @throws \Automattic\WooCommerce\StoreApi\Exceptions\RouteException
	 */
	public function checkout_validation( $order ) {
		/**
		 * Save point when payment method has changed since last Cart API request
		 */
		$this->save_point_to_order( $order );

		/**
		 * Validate
		 */
		$service        = $this->get_selected_service();
		$extension_data = $this->get_extension_data();
		$data           = $order->get_payment_method() !== 'cod' ?
			$extension_data[ self::FIELD_SELECTED_POINT ] : $extension_data[ self::FIELD_SELECTED_POINT_COD ];

		if ( ! empty( $service ) && empty( $data['code'] ) ) {
			throw new \Automattic\WooCommerce\StoreApi\Exceptions\RouteException(
				'furgonetka_missing_pickup_point',
				__( 'Please select delivery point.', 'furgonetka' ),
				400,
				array()
			);
		}
	}

	/**
	 * Save point from session into the order
	 *
	 * @param WC_Order $order
	 * @return void
	 */
	public function save_point_to_order( $order ) {
		$extension_data = $this->get_extension_data();
		$data           = $order->get_payment_method() !== 'cod' ?
			$extension_data[ self::FIELD_SELECTED_POINT ] : $extension_data[ self::FIELD_SELECTED_POINT_COD ];

		$order->update_meta_data(
			'_furgonetkaService',
			sanitize_text_field( wp_unslash( $data['service'] ) )
		);

		$order->update_meta_data(
			'_furgonetkaPoint',
			sanitize_text_field( wp_unslash( $data['code'] ) )
		);

		$order->update_meta_data(
			'_furgonetkaPointName',
			sanitize_text_field( wp_unslash( $data['name'] ) )
		);
	}

	/**
	 * Add callback to the action
	 *
	 * @param $hook
	 * @param $callback
	 * @return void
	 */
	private function add_action( $hook, $callback ) {
		$this->loader->add_action( $hook, $callback[0], $callback[1] );
	}

	/**
	 * Get currently selected service
	 *
	 * @return string|null
	 */
	private function get_selected_service() {
		$chosen_method_array = WC()->session->get( 'chosen_shipping_methods' );
		$delivery_to_type    = get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' );

		if ( ! isset( $chosen_method_array[0], $delivery_to_type[ $chosen_method_array[0] ] ) ) {
			return null;
		}

		if ( ! is_string( $delivery_to_type[ $chosen_method_array[0] ] ) ) {
			return null;
		}

		return $delivery_to_type[ $chosen_method_array[0] ];
	}
}
