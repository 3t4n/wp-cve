<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Outward_Actions' ) ) {
	return;
}

use Payever\Sdk\Inventory\Http\RequestEntity\InventoryChangedRequestEntity;
use Payever\Sdk\Inventory\Http\RequestEntity\InventoryCreateRequestEntity;
use Payever\Sdk\Products\Http\RequestEntity\ProductRemovedRequestEntity;
use Payever\Sdk\ThirdParty\Enum\ActionEnum;

/**
 * WC_Payever_Outward_Actions class.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Outward_Actions {

	use WC_Payever_Export_Products_Transformer_Trait;
	use WC_Payever_Helper_Wrapper_Trait;
	use WC_Payever_Logger_Trait;
	use WC_Payever_Synchronization_Manager_Trait;
	use WC_Payever_WP_Wrapper_Trait;

	/** @var array */
	private $stockOrigins = array();

	/**
	 * WC_Payever_Outward_Actions constructor.
	 */
	public function __construct( $wp_wrapper = null ) {
		if ( null !== $wp_wrapper ) {
			$this->set_wp_wrapper( $wp_wrapper );
		}

		WC_Payever_Helper::assert_wc_version_exists();
		if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$this->get_wp_wrapper()->add_action( 'woocommerce_new_product', array( $this, 'product_new_after' ) );
			$this->get_wp_wrapper()->add_action( 'woocommerce_update_product', array( $this, 'product_update_after' ) );
			$this->get_wp_wrapper()->add_action(
				'woocommerce_delete_product_variation',
				array(
					$this,
					'product_variant_delete_after',
				)
			);
			$this->get_wp_wrapper()->add_action(
				'woocommerce_trash_product_variation',
				array(
					$this,
					'product_variant_delete_after',
				)
			);
			$this->get_wp_wrapper()->add_action( 'wp_delete_post', array( $this, 'product_delete_after' ) );
			$this->get_wp_wrapper()->add_action( 'wp_trash_post', array( $this, 'product_delete_after' ) );
			$this->get_wp_wrapper()->add_action(
				'woocommerce_product_object_updated_props',
				array(
					$this,
					'product_object_updated_props',
				),
				10,
				2
			);
			$this->get_wp_wrapper()->add_filter(
				'woocommerce_update_product_stock_query',
				array(
					$this,
					'product_update_stock_after',
				),
				10,
				4
			);
			return;
		}
		$this->get_wp_wrapper()->add_action( 'pre_post_update', array( $this, 'product_update_before' ) );
		$this->get_wp_wrapper()->add_action( 'save_post', array( $this, 'product_save_after' ) );
		$this->get_wp_wrapper()->add_action( 'trash_product', array( $this, 'product_delete_after' ) );
	}

	/**
	 * @param int $product_id
	 */
	public function product_update_before( $product_id ) {
		$stock = $this->get_wp_wrapper()->get_post_meta( $product_id, '_stock', true );
		if ( null !== $stock && '' !== $stock ) {
			$this->stockOrigins[ $product_id ] = $stock;
		}
	}

	/**
	 * @param int $product_id
	 *
	 * @return bool
	 */
	public function product_save_after( $product_id ) {
		$product = $this->get_wp_wrapper()->wc_get_product( $product_id );
		if ( in_array( $product->post->post_status, array( 'auto-draft', 'trash' ) ) ) {

			return false;
		}
		$this->create_or_update_product( $product_id, $product, false );
		$newStock = $this->get_wp_wrapper()->get_post_meta( $product_id, '_stock', true );
		if ( ! empty( $this->stockOrigins[ $product_id ] ) && $newStock ) {
			$diff = $newStock - $this->stockOrigins[ $product_id ];
			$this->send_stock_changed_event_to_payever( $product->get_sku(), $diff );
		} elseif ( $newStock ) {
			$this->send_stock_created_event_to_payever( $product->get_sku(), $newStock );
		}

		return true;
	}

	/**
	 * New product event
	 *
	 * @param $product_id
	 * @param null $product
	 */
	public function product_new_after( $product_id, $product = null ) {
		if ( ! $product ) {
			$product = $this->get_wp_wrapper()->wc_get_product( $product_id );
		}
		$this->create_or_update_product( $product_id, $product, true );
		$this->send_stock_created_event_to_payever( $product->get_sku(), $product->get_stock_quantity() );
	}

	/**
	 * Update product event
	 *
	 * @param $product_id
	 * @param null $product
	 *
	 * @return bool
	 */
	public function product_update_after( $product_id, $product = null ) {
		return $this->create_or_update_product( $product_id, $product, false );
	}

	/**
	 * Delete product event
	 *
	 * @param $product_id
	 *
	 * @return bool
	 */
	public function product_delete_after( $product_id ) {
		$post = $this->get_wp_wrapper()->get_post( $product_id );
		if ( ! $this->is_action_valid() || 'product' !== $post->post_type ) {

			return false;
		}

		$this->remove_product_request( $product_id );

		return true;
	}

	/**
	 * Delete product variant event
	 *
	 * @param $product_id
	 * @param null $product
	 *
	 * @return bool
	 */
	public function product_variant_delete_after( $product_id ) {
		if ( ! $this->is_action_valid() ) {

			return false;
		}

		$this->remove_product_request( $product_id );

		return true;
	}

	/**
	 * @param int|string $product_id
	 * @return bool
	 */
	private function remove_product_request( $product_id ) {
		$this->get_logger()->debug( __( 'Handling woocommerce_delete/trash_product event', 'payever-woocommerce-gateway' ) );

		$sku                     = $this->get_wp_wrapper()->get_post_meta( $product_id, '_sku', true );
		$product_removed_request = new ProductRemovedRequestEntity();
		$product_removed_request->setSku( $sku );
		$product_removed_request->setExternalId( $this->get_synchronization_manager()->get_external_id() );

		$this->get_synchronization_manager()->handle_outward_action(
			ActionEnum::ACTION_REMOVE_PRODUCT,
			$product_removed_request
		);

		return true;
	}

	/**
	 * Stock update event
	 *
	 * @param string|mixed $sql
	 * @param string|mixed $product_id_with_stock
	 * @param string|mixed $new_stock
	 * @return mixed
	 */
	public function product_update_stock_after( $sql, $product_id_with_stock, $new_stock ) {
		if ( ! $this->is_action_valid() ) {

			return $sql;
		}

		$this->get_logger()->debug( __( 'Handling woocommerce_update_product_stock_query event', 'payever-woocommerce-gateway' ) );

		$product = $this->get_wp_wrapper()->wc_get_product( $product_id_with_stock );
		$diff    = $product->get_stock_quantity() - $new_stock;
		$this->send_stock_changed_event_to_payever( $product->get_sku(), $diff );

		return $sql;
	}

	/**
	 * Processes stock if it was changed manually
	 *
	 * @param $product
	 * @param $updated_props
	 *
	 * @return bool
	 */
	public function product_object_updated_props( $product, $updated_props ) {
		if ( ! $this->is_action_valid() ) {

			return false;
		}

		if ( in_array( 'stock_quantity', $updated_props ) ) {
			$this->get_logger()->debug( __( 'Handling woocommerce_product_object_updated_props event', 'payever-woocommerce-gateway' ) );
			$data = $product->get_data();
			$changes = $product->get_changes();
			$current_stock_qty = $data['stock_quantity'];
			if ( null !== $current_stock_qty ) {
				$diff = $changes['stock_quantity'] - $data['stock_quantity'];
				$this->send_stock_changed_event_to_payever( $product->get_sku(), $diff );

				return true;
			}
			if ( ! empty( $changes['manage_stock'] ) ) {
				$this->send_stock_created_event_to_payever( $product->get_sku(), $changes['stock_quantity'] );
			}
		}

		return true;
	}

	/**
	 * @param string $sku
	 * @param float $stock
	 *
	 * @return bool
	 */
	private function send_stock_created_event_to_payever( $sku, $stock ) {
		if ( ! $this->is_action_valid() ) {

			return false;
		}
		$inventory_request_entity = new InventoryCreateRequestEntity();
		$inventory_request_entity->setSku( $sku );
		$inventory_request_entity->setStock( $stock );
		$inventory_request_entity->setExternalId( $this->get_synchronization_manager()->get_external_id() );
		$this->get_synchronization_manager()->handle_outward_action(
			ActionEnum::ACTION_SET_INVENTORY,
			$inventory_request_entity
		);

		return true;
	}

	/**
	 * Sends stock changed event to payever
	 *
	 * @param $sku
	 * @param $diff
	 *
	 * @return bool
	 */
	private function send_stock_changed_event_to_payever( $sku, $diff ) {
		if ( ! $this->is_action_valid() || 0 === $diff ) {

			return false;
		}

		$inventory_request = new InventoryChangedRequestEntity();
		$inventory_request->setSku( $sku );
		$inventory_request->setQuantity( abs( $diff ) );
		$inventory_request->setExternalId( $this->get_synchronization_manager()->get_external_id() );

		$this->get_synchronization_manager()->handle_outward_action(
			$diff < 0
				? ActionEnum::ACTION_SUBTRACT_INVENTORY
				: ActionEnum::ACTION_ADD_INVENTORY,
			$inventory_request
		);

		return true;
	}

	/**
	 * @param $product_id
	 * @param $product
	 * @param mixed $is_new_product
	 *
	 * @return bool
	 */
	private function create_or_update_product( $product_id, $product, $is_new_product = null ) {
		if ( ! $this->can_update() ) {

			return false;
		}

		$this->get_logger()->debug( sprintf( __( 'Handling %s event', 'payever-woocommerce-gateway' ), $is_new_product ? 'woocommerce_new_product' : 'woocommerce_update_product' ) );

		$product = ( ! is_null( $product ) ) ? $product : $this->get_wp_wrapper()->wc_get_product( $product_id );
		if ( in_array( $product->get_type(), array( 'simple', 'external', 'variable', 'downloadable', 'virtual' ) ) ) {
			$product_request = $this->get_products_transformer()->transform_woocommerce_into_payever( $product );
			$product_request->setExternalId( $this->get_helper_wrapper()->get_product_sync_token() );

			$this->get_synchronization_manager()->handle_outward_action(
				$is_new_product ? ActionEnum::ACTION_CREATE_PRODUCT : ActionEnum::ACTION_UPDATE_PRODUCT,
				$product_request
			);
		}

		return true;
	}

	/**
	 * @return bool
	 */
	private function can_update() {
		if ( $this->is_atributes_saved() || $this->is_link_variations_action() || ! $this->is_action_valid() || ( function_exists( 'is_checkout' ) && $this->get_wp_wrapper()->is_checkout() ) ) {
			// Do not update products on checkout - seems to cause problems with WPML
			return false;
		}

		return true;
	}

	private function is_atributes_saved() {
		return ! empty( $_POST['product_type'] ) &&
			! empty( $_POST['action'] ) &&
			'variable' === sanitize_text_field( wp_unslash( $_POST['product_type'] ) ) && // WPCS: input var ok, CSRF ok.
			'woocommerce_save_attributes' === sanitize_text_field( wp_unslash( $_POST['action'] ) ); // WPCS: input var ok, CSRF ok.
	}

	private function is_link_variations_action() {
		return ! empty( $_POST['action'] ) && 'woocommerce_link_all_variations' === sanitize_text_field( wp_unslash( $_POST['action'] ) ); // WPCS: input var ok, CSRF ok.
	}

	/**
	 * @return bool
	 */
	private function is_action_valid() {
		if ( ! $this->get_helper_wrapper()->is_products_sync_enabled()
			|| strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'payever_synchronization_incoming' ) ) { // WPCS: input var ok, CSRF ok.

			return false;
		}

		return true;
	}
}
