<?php
/**
 * Nouvello WeManage Worker Webhooks Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Nouvello_WeManage_Worker_Webhooks' ) ) :

	/**
	 * Webhooks Class.
	 *
	 * @since 1.0
	 */
	final class Nouvello_WeManage_Worker_Webhooks {

		/**
		 * Constructor.
		 */
		public function __construct() {

			// products.
			add_action( 'save_post', array( $this, 'nouvello_wemanage_webhooks_save_post_cb' ), 10, 3 );
			add_action( 'woocommerce_update_product', array( $this, 'nouvello_wemanage_webhooks_product_create_or_update_cb' ), 10, 1 ); // edit / create.
			add_action( 'wp_trash_post', array( $this, 'nouvello_wemanage_webhooks_product_trash_cb' ), 10, 1 ); // trash.
			add_action( 'untrash_post', array( $this, 'nouvello_wemanage_webhooks_product_restore_from_trash_cb' ), 10, 2 ); // restore from trash.
			add_action( 'after_delete_post', array( $this, 'nouvello_wemanage_webhooks_product_destroy_cb' ), 10, 2 ); // destroy (clear trash).

			// product taxonomies.
			add_action( 'saved_product_cat', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );
			add_action( 'delete_product_cat', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );
			add_action( 'saved_product_tag', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );
			add_action( 'delete_product_tag', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );

			// product attributes.
			add_action( 'woocommerce_attribute_added', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );
			add_action( 'woocommerce_attribute_updated', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );
			add_action( 'woocommerce_attribute_deleted', array( $this, 'nouvello_wemanage_webhooks_save_product_taxonomy_cb' ) );

			// product attribute terms.
			add_action( 'create_term', array( $this, 'nouvello_wemanage_webhooks_save_product_term_cb' ), 10, 3 );
			add_action( 'edit_term', array( $this, 'nouvello_wemanage_webhooks_save_product_term_cb' ), 10, 3 );
			add_action( 'delete_term', array( $this, 'nouvello_wemanage_webhooks_delete_product_term_cb' ), 10, 3 );

			// orders.
			add_action( 'woocommerce_new_order', array( $this, 'nouvello_wemanage_webhooks_order_create_cb' ), 10, 2 );
			add_action( 'woocommerce_order_status_changed', array( $this, 'nouvello_wemanage_webhooks_order_status_changed_cb' ), 10, 3 );
			add_action( 'woocommerce_payment_complete', array( $this, 'nouvello_wemanage_webhooks_order_payment_complete_cb' ), 10, 1 );
			// admin create order.
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'nouvello_wemanage_webhooks_admin_order_create_cb' ), 10, 2 );
		}

		/**
		 * Mark a product as 'new' if it's not an update by setting a transient we can check.
		 * the transient will be removed in nouvello_wemanage_webhooks_product_update_cb().
		 *
		 * @param  [type] $post_id [description].
		 * @param  [type] $post    [description].
		 * @param  [type] $update  [description].
		 */
		public function nouvello_wemanage_webhooks_save_post_cb( $post_id, $post, $update ) {
			if ( false == $update && 'product' == $post->post_type && 'trash' != $post->post_status ) {
				// mark as a new product (not an update).
				$creating_product_id = 'create_product_' . $post_id;
				set_transient( $creating_product_id, $post_id ); // no expiration time.
				$creating_product_transient = get_transient( $creating_product_id ); // check transient to see if it's a new product or an update.
			}
		}

		/**
		 * Fires when a product category, tag or attribute has been created, updated or deleted.
		 * the transient will be removed in nouvello_wemanage_webhooks_product_update_cb().
		 */
		public function nouvello_wemanage_webhooks_save_product_taxonomy_cb() {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'product.taxonomy';
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * [nouvello_wemanage_webhooks_save_product_term_cb description]
		 *
		 * @param  [type] $term_id          [description].
		 * @param  [type] $term_taxonomy_id [description].
		 * @param  [type] $taxonomy_slug    [description].
		 */
		public function nouvello_wemanage_webhooks_save_product_term_cb( $term_id, $term_taxonomy_id, $taxonomy_slug ) {
			if ( strpos( $taxonomy_slug, 'pa_' ) !== false ) {
				$payload = $this->get_payload_array();
				$payload['trigger'] = 'product.taxonomy';
				$this->nouvello_wemanage_webhooks_fire( $payload );
			}
		}

		/**
		 * [nouvello_wemanage_webhooks_save_product_term_cb description]
		 */
		public function nouvello_wemanage_webhooks_delete_product_term_cb() {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'product.taxonomy';
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * Product Create / Update.
		 *
		 * @param  [type] $product_id [description].
		 */
		public function nouvello_wemanage_webhooks_product_create_or_update_cb( $product_id ) {
			// the woocommerce_update_product fires twice due to internal wp id retrieval.
			// the code below ensures we'll fire the webhook only one time.
			$updating_product_id = 'update_product_' . $product_id;
			$updating_product_transient = get_transient( $updating_product_id ); // check transient to ensure we fire once.

			$creating_product_id = 'create_product_' . $product_id;
			$creating_product_transient = get_transient( $creating_product_id ); // check transient to see if it's a new product or an update.

			// check if it's a new product or an update.
			if ( $creating_product_transient == $product_id ) { // product create.
				$trigger = 'product.create';
			} else { // product update.
				if ( false === $updating_product_transient ) {
					// We'll get here only once! within 2 seconds for each product id.
					$trigger = 'product.update';
					// ensures only one call is fired. change 2 seconds if not enough.
				}
			}

			if ( isset( $trigger ) ) {
				// setup webhook and fire.
				$payload = $this->get_payload_array();
				$payload['trigger'] = $trigger;
				$payload['product_id'] = $product_id;
				$this->nouvello_wemanage_webhooks_fire( $payload );
			}

			set_transient( $updating_product_id, $product_id, 2 );
			delete_transient( $creating_product_id ); // delete new product transient.
		}

		/**
		 * Trash product.
		 *
		 * @param  [type] $post_id [description].
		 */
		public function nouvello_wemanage_webhooks_product_trash_cb( $post_id ) {
			$type = get_post_type( $post_id );
			if ( 'product' == $type ) {
				$payload = $this->get_payload_array();
				$payload['trigger'] = 'product.trash';
				$payload['product_id'] = $post_id;
				$this->nouvello_wemanage_webhooks_fire( $payload );
			}
		}

		/**
		 * [nouvello_wemanage_webhooks_product_restore_from_trash_cb description]
		 *
		 * @param  [type] $post_id         [description].
		 * @param  [type] $previous_status [description].
		 */
		public function nouvello_wemanage_webhooks_product_restore_from_trash_cb( $post_id, $previous_status ) {
			$type = get_post_type( $post_id );
			if ( 'product' == $type ) {
				$payload = $this->get_payload_array();
				$payload['trigger'] = 'product.restore';
				$payload['product_id'] = $post_id;
				$this->nouvello_wemanage_webhooks_fire( $payload );
			}
		}

		/**
		 * Destroy product.
		 *
		 * @param  [type] $post_id [description].
		 * @param  [type] $post    [description].
		 */
		public function nouvello_wemanage_webhooks_product_destroy_cb( $post_id, $post ) {
			if ( 'product' == $post->post_type ) {
				$payload = $this->get_payload_array();
				$payload['trigger'] = 'product.destroy';
				$payload['product_id'] = $post_id;
				$this->nouvello_wemanage_webhooks_fire( $payload );
			}
		}

		/**
		 * Order Create Webhook
		 *
		 * @param  [type] $order_id [description].
		 * @param  [type] $order    [description].
		 */
		public function nouvello_wemanage_webhooks_order_create_cb( $order_id, $order ) {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'order.create';
			$payload['order_id'] = $order_id;
			$payload['order_total'] = $order->get_total();
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * Order Update Webhook
		 *
		 * @param  [type] $order_id   [description].
		 * @param  [type] $old_status [description].
		 * @param  [type] $new_status [description].
		 */
		public function nouvello_wemanage_webhooks_order_status_changed_cb( $order_id, $old_status, $new_status ) {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'order.update';
			$payload['order_id'] = $order_id;
			$payload['order_old_status'] = $old_status;
			$payload['order_new_status'] = $new_status;
			$this->nouvello_wemanage_webhooks_fire( $payload );

			// check if this order was created by the admin.
			// if so, fire payment complete webhook.
			$admin_creating_order_id = 'admin_create_order_' . $order_id;
			$admin_creating_order_transient = get_transient( $admin_creating_order_id );
			if ( $admin_creating_order_transient == $order_id ) {
				$this->nouvello_wemanage_webhooks_order_payment_complete_cb( $order_id );
				delete_transient( $admin_creating_order_transient ); // delete transient.
			}
		}

		/**
		 * Payment Complete Webhook
		 *
		 * @param  [type] $order_id [description].
		 */
		public function nouvello_wemanage_webhooks_order_payment_complete_cb( $order_id ) {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'order.payment_complete';
			$order = wc_get_order( $order_id );
			$payload['order_id'] = $order_id;
			$payload['order_total'] = $order->get_total();
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * On admin create order from wp admin dashboard - set transient to mark order as such.
		 *
		 * @param  [type] $post_id [description].
		 * @param  [type] $post    [description].
		 */
		public function nouvello_wemanage_webhooks_admin_order_create_cb( $post_id, $post ) {
			// mark as a new order created by admin from wp admin dashboard.
			$admin_creating_order_id = 'admin_create_order_' . $post_id;
			set_transient( $admin_creating_order_id, $post_id ); // no expiration time.
		}


		/**
		 * Plugin reactivation
		 */
		public function nouvello_wemanage_webhooks_plugin_reactivation() {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'wp_plugin.reactivation';
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * Plugin update
		 */
		public function nouvello_wemanage_webhooks_plugin_update() {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'wp_plugin.update';
			$payload['plugin_version'] = NSWMW_VER;
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * Plugin deactivation
		 */
		public function nouvello_wemanage_webhooks_plugin_deactivation() {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'wp_plugin.deactivation';
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * [nouvello_wemanage_webhooks_form_lead_data description]
		 *
		 * @param  [type] $data [description].
		 */
		public function nouvello_wemanage_webhooks_form_lead_data( $data ) {
			$payload = $this->get_payload_array();
			$payload['trigger'] = 'lead_form.data';
			$payload['data'] = $data;
			$this->nouvello_wemanage_webhooks_fire( $payload );
		}

		/**
		 * The basic payload array needed for any webhook.
		 *
		 * @return [type] [description]
		 */
		public function get_payload_array() {
			return array(
				'key' => nouvello_wemanage_worker()->init->return_activation_key(),
				'url' => get_home_url(),
				'type' => 'WordPress',
			);
		}

		/**
		 * Fire the webhook
		 *
		 * @param  [type] $payload [description].
		 */
		public function nouvello_wemanage_webhooks_fire( $payload ) {

			$args = array(
				'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'body'        => json_encode( $payload ),
				'method'      => 'POST',
				'data_format' => 'body',
			);

			$url = WEMANAGE_SERVER_URL . '/api/webhooks/jhb123asd';
			wp_remote_post( $url, $args );

		}

	} // end of class

endif; // end if class exist.
