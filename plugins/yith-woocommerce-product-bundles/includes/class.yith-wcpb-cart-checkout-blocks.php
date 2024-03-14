<?php
/**
 * Cart & Checkout blocks class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductBundles
 */

defined( 'YITH_WCPB' ) || exit;

if ( ! class_exists( 'YITH_WCPB_Cart_Checkout_Blocks' ) ) {
	/**
	 * Cart & Checkout blocks  class.
	 * Manage Cart and Checkout blocks' behaviors.
	 *
	 * @since 1.29.0
	 */
	class YITH_WCPB_Cart_Checkout_Blocks {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WCPB_Cart_Checkout_Blocks|YITH_WCPB_Cart_Checkout_Blocks_Premium
		 */
		protected static $instance;

		/**
		 * Singleton implementation.
		 *
		 * @return YITH_WCPB_Cart_Checkout_Blocks|YITH_WCPB_Cart_Checkout_Blocks_Premium
		 */
		public static function get_instance() {
			/**
			 * The class.
			 *
			 * @var YITH_WCPB_Cart_Checkout_Blocks|YITH_WCPB_Cart_Checkout_Blocks_Premium $self
			 */
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * The constructor.
		 */
		protected function __construct() {
			if ( did_action( 'woocommerce_blocks_loaded' ) ) {
				$this->initialize();
			} else {
				add_action( 'woocommerce_blocks_loaded', array( $this, 'initialize' ) );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Initialize Cart and Checkout blocks integration.
		 */
		public function initialize() {
			require_once YITH_WCPB_INCLUDES_PATH . '/class.yith-wcpb-cart-checkout-blocks-integration.php';
			add_action( 'woocommerce_blocks_cart_block_registration', array( $this, 'register_cart_and_checkout_blocks_integration' ) );
			add_action( 'woocommerce_blocks_checkout_block_registration', array( $this, 'register_cart_and_checkout_blocks_integration' ) );
			add_filter( 'woocommerce_store_api_product_quantity_minimum', array( $this, 'force_quantity_in_cart_for_bundled_items' ), 10, 3 );
			add_filter( 'woocommerce_store_api_product_quantity_maximum', array( $this, 'force_quantity_in_cart_for_bundled_items' ), 10, 3 );

			woocommerce_store_api_register_endpoint_data(
				array(
					'endpoint'        => \Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema::IDENTIFIER,
					'namespace'       => 'yith-woocommerce-product-bundles',
					'data_callback'   => array( $this, 'get_cart_item_bundle_data' ),
					'schema_callback' => array( $this, 'get_cart_item_bundle_data_schema' ),
					'schema_type'     => ARRAY_A,
				)
			);
		}

		/**
		 * Register Cart and Checkout blocks integration
		 *
		 * @param Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry $integration_registry The registry.
		 */
		public function register_cart_and_checkout_blocks_integration( $integration_registry ) {
			$integration_registry->register( new YITH_WCPB_Cart_Checkout_Blocks_Integration() );
		}

		/**
		 * Get bundle data of the cart item.
		 *
		 * @param array $cart_item Cart item data.
		 *
		 * @return array
		 */
		public function get_cart_item_bundle_data( $cart_item ): array {
			$is_bundle       = isset( $cart_item['cartstamp'] );
			$is_bundled_item = ! $is_bundle && isset( $cart_item['bundled_by'] );

			$data = array(
				'isBundle'      => $is_bundle,
				'isBundledItem' => $is_bundled_item,
			);

			$bundle_cart_item = $is_bundle ? $cart_item : array();
			$bundle_cart_item = $is_bundled_item ? ( WC()->cart->cart_contents[ $cart_item['bundled_by'] ] ?? array() ) : $bundle_cart_item;
			if ( $bundle_cart_item ) {
				$data['bundleData'] = array(
					'hasFixedPrice'   => true,
					'bundledItemKeys' => array_values( $bundle_cart_item['bundled_items'] ?? array() ),
				);
			}

			if ( $is_bundled_item ) {
				$data['itemData'] = array(
					'hasCustomName'     => false,
					'name'              => '',
					'isHidden'          => false,
					'isThumbnailHidden' => false,
				);
			}

			return $data;
		}

		/**
		 * Get the schema for the bundle data of cart items.
		 */
		public function get_cart_item_bundle_data_schema() {
			return array(
				'isBundle'      => array(
					'description' => __( 'True if the cart item is a bundle.', 'yith-woocommerce-product-bundles' ),
					'type'        => 'boolean',
					'readonly'    => true,
				),
				'isBundledItem' => array(
					'description' => __( 'True if the cart item is a bundled item.', 'yith-woocommerce-product-bundles' ),
					'type'        => 'boolean',
					'readonly'    => true,
				),
				'bundleData'    => array(
					'description' => __( 'The bundle data related to the current cart item if it\'s a bundle or to the related bundle if it\'s a bundled item.', 'yith-woocommerce-product-bundles' ),
					'type'        => array( 'object', 'null' ),
					'readonly'    => true,
					'properties'  => array(
						'hasFixedPrice'   => array(
							'description' => __( 'True if the bundle has a fixed price.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'boolean',
							'readonly'    => true,
						),
						'bundledItemKeys' => array(
							'description' => __( 'List of bundled item keys.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'array',
							'readonly'    => true,
						),
					),
				),
				'itemData'      => array(
					'description' => __( 'The bundled item data.', 'yith-woocommerce-product-bundles' ),
					'type'        => array( 'object', 'null' ),
					'readonly'    => true,
					'properties'  => array(
						'hasCustomName'     => array(
							'description' => __( 'True if the bundled item has a custom name.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'boolean',
							'readonly'    => true,
						),
						'name'              => array(
							'description' => __( 'The name set for the bundled item.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'string',
							'readonly'    => true,
						),
						'isHidden'          => array(
							'description' => __( 'True if the bundled item is hidden.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'boolean',
							'readonly'    => true,
						),
						'isThumbnailHidden' => array(
							'description' => __( 'True if the thumbnail of the bundled item is hidden.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'boolean',
							'readonly'    => true,
						),
						'bundledBy'         => array(
							'description' => __( 'The cart item key of the bundle including the current bundled item.', 'yith-woocommerce-product-bundles' ),
							'type'        => 'string',
							'readonly'    => true,
						),
					),
				),
			);
		}

		/**
		 * Force quantity in cart for bundled items, to prevent the customer from changing it.
		 *
		 * @param int        $value     The quantity.
		 * @param WC_Product $product   The product.
		 * @param array      $cart_item The cart item.
		 *
		 * @return int
		 */
		public function force_quantity_in_cart_for_bundled_items( $value, $product, $cart_item ) {
			if ( isset( $cart_item['bundled_by'] ) ) {
				return $cart_item['quantity'];
			}

			return $value;
		}

		/**
		 * Get frontend style.
		 *
		 * @return string
		 */
		protected function get_frontend_style() {
			return '
			.wc-block-components-order-summary .wc-block-components-order-summary-item.yith-wcpb-is-bundle:after {border: none !important;}
			.wc-block-cart .wc-block-cart-items tr.yith-wcpb-is-bundled-item .wc-block-cart-item__product .wc-block-cart-item__prices{display: none !important;}
			.wc-block-cart .wc-block-cart-items tr.yith-wcpb-is-bundled-item .wc-block-components-sale-badge{display: none !important;}
			.wc-block-components-order-summary .wc-block-components-order-summary-item.yith-wcpb-is-bundled-item .wc-block-components-product-price{display: none !important;}
			';
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_scripts() {
			wp_add_inline_style( 'yith_wcpb_bundle_frontend_style', $this->get_frontend_style() );
		}
	}
}
