<?php
/**
 * Class for Putler-WooCommercee connector.
 *
 * @package     woocommerce-putler-connector/classes/
 * @version     1.0.0
 */

use Automattic\WooCommerce\Utilities\OrderUtil;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce_Putler_Connector' ) ) {

	/**
	 * WooCommerce Putler Connector.
	 */
	class WooCommerce_Putler_Connector {

		/**
		 * Gateway name.
		 *
		 * @var string $hide_fields
		 */
		private $name;

		/**
		 * Wpdb.
		 *
		 * @var mixed $wpdb1
		 */
		private $wpdb1;

		/**
		 * Is HPOS enabled.
		 *
		 * @var bool $is_hpos_enabled
		 */
		private $is_hpos_enabled;

		/**
		 * WC table prefix for HPOS.
		 *
		 * @var string $wc_prefix
		 */
		private $wc_prefix;

		/**
		 *  Constructor.
		 */
		public function __construct() {

			global $wpdb;

			$this->wpdb1 = $wpdb;

			$this->name = ( defined( 'PUTLER_GATEWAY' ) ? PUTLER_GATEWAY : 'WooCommerce' );

			$this->is_hpos_enabled = self::is_hpos_enabled();

			add_filter( 'putler_connector_get_order_count', array( &$this, 'get_order_count' ) );
			add_filter( 'putler_connector_get_orders', array( &$this, 'get_orders' ) );
			add_filter( 'putler_connector_sub_updated', array( &$this, 'get_sub' ) );

			// Flag for woo2.2+.
			if ( version_compare( WOOCOMMERCE_VERSION, '2.2.0', '<' ) ) {
				define( 'WPC_IS_WOO22', 'false' );
			} else {
				define( 'WPC_IS_WOO22', 'true' );
			}

			if ( is_admin() ) {
				add_action( 'before_delete_post', array( &$this, 'delete_post' ), 9, 1 );
			}

			// Action for WooCommerce v7.1 custom order tables related compatibility.
			add_action( 'before_woocommerce_init', array( &$this, 'declare_hpos_compatibility' ) );
		}

		/**
		 * Function to validate the transaction.
		 *
		 * @param array $transaction The transaction.
		 *
		 * @return array Return the response.
		 */
		public function validate_transaction( $transaction ) {

			$response = array();

			$response_keys = array(
				'Date',
				'Time',
				'Time_Zone',
				'Source',
				'Name',
				'Type',
				'Status',
				'Currency',
				'Gross',
				'Fee',
				'Net',
				'From_Email_Address',
				'To_Email_Address',
				'Transaction_ID',
				'Counterparty_Status',
				'Address_Status',
				'Item_Title',
				'Item_ID',
				'Shipping_and_Handling_Amount',
				'Insurance_Amount',
				'Discount',
				'Sales_Tax',
				'Option_1_Name',
				'Option_1_Value',
				'Option_2_Name',
				'Option_2_Value',
				'Auction_Site',
				'Buyer_ID',
				'Item_URL',
				'Closing_Date',
				'Escrow_ID',
				'Invoice_ID',
				'Reference_Txn_ID',
				'Invoice_Number',
				'Custom_Number',
				'Quantity',
				'Receipt_ID',
				'Balance',
				'Note',
				'Address_Line_1',
				'Address_Line_2',
				'Town_City',
				'State_Province',
				'Zip_Postal_Code',
				'Country',
				'Contact_Phone_Number',
				'Subscription_ID',
				'Raw_Data',
				'Payment_Source',
				'External_Trans_ID',
				'IP_Address',
				'Sub_Meta',
			);

			$response_float_keys = array( 'Gross', 'Fee', 'Net', 'Shipping_and_Handling_Amount', 'Insurance_Amount', 'Discount', 'Sales_Tax', 'Balance' );

			foreach ( $response_keys as $key ) {

				$default_value = '';

				$float_value = in_array( $key, $response_float_keys, true );

				if ( ! empty( $float_value ) ) {
					$default_value = '0.00';
				} else {
					if ( 'Currency' === $key ) {
						$default_value = 'USD';
					} elseif ( 'Sub_Meta' === $key ) {
						$default_value = wp_json_encode( json_decode( '{}' ) );
					}
				}

				$response[ $key ] = ( ! empty( $transaction[ $key ] ) ) ? $transaction[ $key ] : $default_value;
			}

			return $response;
		}

		/**
		 * Function to handle storing of the transactions on deletion.
		 *
		 * @param int $id The post Id.
		 *
		 * @return void.
		 */
		public function delete_post( $id ) {

			if ( empty( $id ) ) {
				return;
			}

			global $wpdb;

			$post_type       = ( $this->is_hpos_enabled ) ? OrderUtil::get_order_type( $id ) : get_post_type( $id );
			$valid_post_type = array( 'shop_order', 'shop_subscription', 'scheduled-action' );
			$order           = wc_get_order( $id );
			if ( empty( $post_type ) || in_array( $post_type, $valid_post_type, true ) === false || ( $this->is_hpos_enabled && ! ( $order instanceof WC_Order ) ) ) {
				return;
			}

			// array to store the transaction details.
			$putler_transaction = array(
				'Date'            => get_post_modified_time( 'm/d/Y', true, $id, false ),
				'Time'            => get_post_modified_time( 'H:i:s', true, $id, false ),
				'Source'          => $this->name,
				'Time_Zone'       => 'GMT',
				'Type'            => ( ( 'shop_order' !== $post_type ) ? 'Recurring Payment' : 'Shopping Cart Payment Received' ),
				'Status'          => 'Delete',
				'Transaction_ID'  => ( ( 'shop_order' === $post_type ) ? $id : '' ),
				'Subscription_ID' => ( ( 'shop_order' === $post_type ) ? $id : '' ),
			);

			if ( $this->is_hpos_enabled ) {
				$order_modified_date        = $order->get_date_modified();
				$putler_transaction['Date'] = $this->format_date_time( $order_modified_date, 'm/d/Y' );
				$putler_transaction['Time'] = $this->format_date_time( $order_modified_date, 'H:i:s' );
			}

			if ( 'shop_order' === $post_type ) {

				// query to find if the order is a subscription renewal order.
				if ( $this->is_hpos_enabled ) {
					$results = $wpdb->get_row(
						$wpdb->prepare(
							"SELECT id as id,
										'' as order_type
										FROM {$wpdb->prefix}wc_orders
										WHERE parent_order_id = %d
										UNION
										SELECT meta_value as id,
											'renewal' as order_type
										FROM {$wpdb->prefix}wc_orders_meta
										WHERE order_id = %d
											AND meta_key IN ('_subscription_renewal')",
							$id,
							$id
						),
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				} else {
					$results = $wpdb->get_row(
						$wpdb->prepare(
							"SELECT ID as id,
										'' as order_type
										FROM {$wpdb->prefix}posts
										WHERE post_parent = %d
										UNION
										SELECT meta_value as id,
											'renewal' as order_type
										FROM {$wpdb->prefix}postmeta
										WHERE post_id = %d
											AND meta_key IN ('_subscription_renewal')",
							$id,
							$id
						),
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				}

				if ( ! empty( $results ) && count( $results ) > 0 ) {

					$sub_id = ( ! empty( $results['id'] ) ) ? $results['id'] : '';

					if ( empty( $results['order_type'] ) ) {
						// query to get the order item ids for subscription orders.
						$results = $wpdb->get_col(
							$wpdb->prepare(
								"SELECT oim.meta_value AS pid
							FROM {$wpdb->prefix}woocommerce_order_items AS oi
								JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim 
									ON (oim.order_item_id = oi.order_item_id
											AND oi.order_item_type = 'line_item')
							WHERE oi.order_id = %d
								AND oim.meta_key = '_product_id'",
								$id
							)
						); // WPCS: cache ok, db call ok.

						$sub  = 0;
						$prod = 0;

						if ( ! empty( $results ) && count( $results ) > 0 ) {

							foreach ( $results as $pid ) {
								$product_terms = wp_get_object_terms( $pid, 'product_type', array( 'fields' => 'slugs' ) );

								if ( ! empty( $product_terms[0] ) && ( 'variable-subscription' === $product_terms[0] || 'subscription' === $product_terms[0] ) ) {
									$sub ++;
								} else {
									$prod ++;
								}
							}
						}

						if ( 1 === $sub && 0 === $prod ) {
							$putler_transaction['Subscription_ID'] = $sub_id;
							$putler_transaction['Type']            = 'Subscription Payment Received';
						} elseif ( ( $sub > 1 && 0 === $prod ) || ( $sub >= 1 && $prod > 0 ) ) {
							$putler_transaction['Type'] = 'Subscription Shopping Cart Payment Received';
						}
					} else { // renewal order.
						$putler_transaction['Subscription_ID'] = $sub_id;
						$putler_transaction['Type']            = 'Subscription Payment Received';
					}
				}
			} else {
				if ( $this->is_hpos_enabled ) {
					$putler_transaction['Transaction_ID'] = $order->get_parent_id() . '_D_' . $this->format_date_time( $order_modified_date, 'G' );
				} else {
					$putler_transaction['Transaction_ID'] = wp_get_post_parent_id( $id ) . '_D_' . get_post_modified_time( 'G', true, $id, false );
				}
			}

			$transient_name = '';
			$data           = array();

			if ( ! empty( sanitize_text_field( wp_unslash( $_GET['delete_all'] ) ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$results = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT option_name,
                                option_value 
                            FROM {$wpdb->prefix}options
                            WHERE option_name LIKE %s 
                                AND (UNIX_TIMESTAMP() - IFNULL(SUBSTRING_INDEX(option_name,'_transient_wpc_deleted_empty_trash_',-1),0) ) < 300",
						'%' . $wpdb->esc_like( '_transient_wpc_deleted_empty_trash_' ) . '%'
					),
					'ARRAY_A'
				);  // WPCS: cache ok, db call ok.
				if ( ! empty( $results ) && count( $results ) > 0 ) {

					$transient_name = ( ! empty( $results['option_name'] ) ) ? substr( $results['option_name'], 11 ) : '';
					$data           = maybe_unserialize( $results['option_value'] );
					$data[]         = $putler_transaction;

				} else {
					$transient_name = 'wpc_deleted_empty_trash_' . time();
					$data           = array( $putler_transaction );
				}
			} else {
				if ( ! empty( $putler_transaction ) ) {
					$transient_name = 'wpc_deleted_' . time();
					$data           = array( $putler_transaction );
				}
			}

			if ( ! empty( $transient_name ) && ! empty( $data ) ) {
				set_transient( $transient_name, $data, ( DAY_IN_SECONDS * 180 ) );
			}
		}

		/**
		 * Function for handling WooCommerce status mapping.
		 *
		 * @param array $v WooCommerce status array.
		 *
		 * @return array.
		 */
		public function new_status_map( $v ) {
			return $v[0];
		}

		/**
		 * Function to get sub data when updated.
		 *
		 * @param string $status subscription status.
		 *
		 * @return string.
		 */
		public function get_sub_status( $status ) {

			$sub_status = array();

			$sub_status = array(
				'wc-active'         => 'Active',
				'wc-cancelled'      => 'Canceled',
				'wc-expired'        => 'Expired',
				'wc-pending'        => 'Pending',
				'wc-pending-cancel' => 'Pending Canceled',
				'wc-on-hold'        => 'Held',
				'wc-suspend'        => 'Suspend',
				'wc-reactivated'    => 'Reactivated',
				'wc-created'        => 'Created',
				'wc-switched'       => 'Switched', // added if subscription not properly updated to v2.0.
				'trash'             => 'Delete',
			);

			$new_status = ( ! empty( $sub_status[ $status ] ) ) ? $sub_status[ $status ] : ( ( ! empty( $sub_status[ 'wc-' . $status ] ) ) ? $sub_status[ 'wc-' . $status ] : $status );

			return $new_status;
		}

		/**
		 * Function to get sub data when updated.
		 *
		 * @param array $params subscription params.
		 *
		 * @return array.
		 */
		public function get_sub( $params ) {

			if ( empty( $params ) || empty( $params['sub_id'] ) ) {
				return;
			}

			global $wpdb;

			$new_status      = ( ! empty( $params['sub_details'] ) && ! empty( $params['sub_details']['post_status'] ) ) ? $params['sub_details']['post_status'] : ( ! empty( $params['new_status'] ) ? $params['new_status'] : '' );
			$new_status      = ( ! empty( $params['sub_details'] ) && 'trash' === $params['sub_details']['post_status'] ) ? $params['sub_details']['post_status'] : $new_status; // added for handling trashed status.
			$new_status      = $this->get_sub_status( $new_status );
			$new_status_abbr = implode( '', array_map( array( $this, 'new_status_map' ), explode( ' ', $new_status ) ) );

			$response = array(
				'Item_Title' => 'Unknown',
				'Quantity'   => 1,
			);

			$transaction = array();

			if ( ( empty( $params['sub_details'] ) ) ) {
				// query to get the sub details.
				if ( $this->is_hpos_enabled ) {
					$results = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT date_format(date_updated_gmt, %s) AS modified_date,
									date_format(date_updated_gmt, %s) AS formatted_modified_gmt_date,
									date_format(date_updated_gmt, %s) AS formatted_modified_gmt_time,
									parent_order_id AS order_id
							FROM {$wpdb->prefix}wc_orders
							WHERE id = %d",
							'%Y-%m-%d %T',
							'%m/%d/%Y',
							'%T',
							$params['sub_id']
						),
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				} else {
					$results = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT date_format(post_modified_gmt, %s) AS modified_date,
									date_format(post_modified_gmt, %s) AS formatted_modified_gmt_date,
									date_format(post_modified_gmt, %s) AS formatted_modified_gmt_time,
									post_parent AS order_id
							FROM {$wpdb->prefix}posts
							WHERE id = %d",
							'%Y-%m-%d %T',
							'%m/%d/%Y',
							'%T',
							$params['sub_id']
						),
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				}

				if ( ! empty( $results ) && count( $results ) > 0 ) {
					foreach ( $results as $result ) {
						$response ['Date']            = $result['formatted_modified_gmt_date'];
						$response ['Time']            = $result['formatted_modified_gmt_time'];
						$response ['Time_Zone']       = 'GMT';
						$response ['Source']          = $this->name;
						$response ['Type']            = 'Recurring Payment';
						$response ['Status']          = $new_status;
						$response ['Transaction_ID']  = $result['order_id'] . '_' . $new_status_abbr . '_' . time();
						$response ['Subscription_ID'] = $params['sub_id'];
					}
				}
			} else {
				$response = array();

				$transaction ['Date']               = $params['sub_details']['post_modified_gmt_date'];
				$transaction ['Time']               = $params['sub_details']['post_modified_gmt_time'];
				$transaction ['Time_Zone']          = 'GMT';
				$transaction ['Source']             = $this->name;
				$transaction ['Name']               = ( ! empty( $params['sub_details']['_billing_first_name'] ) ) ? $params['sub_details']['_billing_first_name'] : '';
				$transaction ['Name']              .= ' ' . ( ( ! empty( $params['sub_details']['_billing_last_name'] ) ) ? $params['sub_details']['_billing_last_name'] : '' );
				$transaction ['Type']               = 'Recurring Payment';
				$transaction ['Status']             = $new_status;
				$transaction ['Currency']           = ( ! empty( $params['sub_details']['_order_currency'] ) ) ? $params['sub_details']['_order_currency'] : 'USD';
				$transaction ['From_Email_Address'] = ( ! empty( $params['sub_details']['_billing_email'] ) ) ? $params['sub_details']['_billing_email'] : '';
				$transaction ['Transaction_ID']     = ( 'Created' !== $new_status ) ? $params['sub_details']['post_parent'] . '_' . $new_status_abbr . '_' . strtotime( $params['sub_details']['post_modified_gmt'] ) : $params['sub_id'];
				$transaction ['Subscription_ID']    = $params['sub_id'];

				$response = $this->validate_transaction( $transaction );

			}

			$pm_cond = ( ! empty( $params['sub_details'] ) ) ? " AND meta_key NOT IN ('_edit_lock', '_edit_last') " : " AND meta_key IN ('_order_currency', '_billing_email', '_billing_first_name', '_billing_last_name', '_payment_method', '_billing_interval', '_billing_period', '_schedule_trial_end', '_schedule_end') ";

			$sub_details = ( ! empty( $params['sub_meta'] ) ) ? $params['sub_details'] : array();
			$sub_meta    = ( ! empty( $params['sub_meta'] ) ) ? $params['sub_meta'] : array();

			if ( empty( $sub_details ) && empty( $sub_meta ) ) {// for non-initial data sync.

				$sub_details = array(
					'created_date' => ( $response ['Date'] . ' ' . $response ['Time'] ),
					'status'       => $new_status,
				);

				$sub_meta_keys = array();

				$sub_meta_keys['_payment_method']     = 'payment_method';
				$sub_meta_keys['_billing_interval']   = 'interval';
				$sub_meta_keys['_billing_period']     = 'period';
				$sub_meta_keys['_schedule_trial_end'] = 'trial_end';
				$sub_meta_keys['_schedule_end']       = 'sub_end';

				// query to get the sub meta details.
				$results = $this->wpdb1->get_results(
					"SELECT meta_key as mkey,
							meta_value as mvalue
					FROM {$wpdb->prefix}postmeta
					WHERE post_id = " . $params['sub_id'] . "
						$pm_cond
					GROUP BY post_id, meta_key",
					'ARRAY_A'
				); // WPCS: cache ok, db call ok.

				if ( ! empty( $results ) && count( $results ) > 0 ) {

					$name = '';

					foreach ( $results as $result ) {
						if ( '_order_currency' === $result['mkey'] ) {
							$response ['Currency'] = $result['mvalue'];
						} elseif ( '_billing_email' === $result['mkey'] ) {
							$response ['From_Email_Address'] = $result['mvalue'];
						} elseif ( '_billing_first_name' === $result['mkey'] ) {
							$name = $result['mvalue'];
						} elseif ( '_billing_last_name' === $result['mkey'] ) {
							$name .= ' ' . $result['mvalue'];
						}

						$response ['Name'] = $name;

						// code for sub meta when sub is updated.
						if ( ! empty( $params['sub_details'] ) ) {
							$sub_details[ $result['mkey'] ] = $result['mvalue']; // WPCS: slow query ok.
						}

						if ( ! empty( $result['mkey'] ) && ! empty( $sub_meta_keys[ $result['mkey'] ] ) ) {
							$sub_meta[ $sub_meta_keys[ $result['mkey'] ] ] = $result['mvalue']; // WPCS: slow query ok.
						}
					}
				}
			} else {

				if ( ! empty( $sub_details['post_modified_gmt'] ) ) {
					$sub_details['created_date'] = $sub_details['post_modified_gmt'];
					unset( $sub_details['post_created_gmt'] );
					unset( $sub_details['post_modified_gmt'] );
					unset( $sub_details['post_parent'] );
				}
			}

			$response ['Raw_Data'] = '';

			if ( ! empty( $params['sub_details'] ) && empty( $params['new_status'] ) ) {
				if ( empty( $params['trash'] ) ) {

					foreach ( $sub_details as $key => $value ) {
						$sub_details[ $key ] = maybe_unserialize( $value );
					}

					$response ['Raw_Data'] = wp_json_encode( array( 'subscriptions' => array( $params['sub_id'] => $sub_details ) ) );
				}
			}

			$response ['Payment_Source']    = '';
			$response ['External_Trans_ID'] = '';
			$response ['IP_Address']        = '';

			if ( ! empty( $sub_meta ) ) {
				$sub_meta['status']    = $new_status;
				$response ['Sub_Meta'] = wp_json_encode( $sub_meta );
			} else {
				$response ['Sub_Meta'] = wp_json_encode( json_decode( '{}' ) );
			}

			$params['data'] = array( $response );

			return $params;
		}

		/**
		 * Function to get total order count based on passed params.
		 *
		 * @param int $count current order count.
		 *
		 * @return int.
		 */
		public function get_order_count( $count ) {
			global $wpdb;
			$order_count     = 0;
			$post_order_cond = '';

			if ( $this->is_hpos_enabled ) {
				$order_count_result = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT COUNT(orders.id) as id
						FROM {$wpdb->prefix}wc_orders AS orders 
						WHERE orders.type IN ('shop_order', 'shop_order_refund') 
							AND orders.date_created_gmt != %s
							AND orders.status NOT IN ('trash', 'auto-draft', 'draft')",
						'0000-00-00 00:00:00'
					)
				); // WPCS: cache ok, db call ok.
			} else {
				$order_count_result = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT COUNT(posts.ID) as id
						FROM {$wpdb->prefix}posts AS posts 
						WHERE posts.post_type IN ('shop_order', 'shop_order_refund') 
							AND posts.post_date_gmt != %s
							AND posts.post_status NOT IN ('trash', 'auto-draft', 'draft')",
						'0000-00-00 00:00:00'
					)
				); // WPCS: cache ok, db call ok.
			}

			if ( ! empty( $order_count_result ) ) {
				$order_count = $order_count_result[0];
			}

			return $count + $order_count;
		}

		/**
		 * Function to get the order details.
		 *
		 * @param array $params request params.
		 *
		 * @return array.
		 */
		public function get_orders( $params ) {
			global $wpdb;
			$orders = array();

			$wc_order_status = ( defined( 'WPC_IS_WOO22' ) && 'true' === WPC_IS_WOO22 ) ? wc_get_order_statuses() : array();

			// Code to get the last order sent.

			$cond = '';

			if ( empty( $params['order_id'] ) ) {
				$start_limit     = ( isset( $params['offset'] ) ) ? $params['offset'] : 0;
				$sub_start_limit = ( isset( $params['sub_offset'] ) ) ? $params['sub_offset'] : 0;
				$batch_limit     = ( isset( $params['limit'] ) ) ? $params['limit'] : 50;
			} else {
				$start_limit = 0;
				$batch_limit = 1;

				// For Handling Refund Transactions.
				if ( 'wc-refunded' === get_post_status( $params['order_id'] ) ) {
					if ( $this->is_hpos_enabled ) {
						$refund_id = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT MAX(id) AS id
									FROM {$wpdb->prefix}wc_orders
									WHERE type = 'shop_order_refund'
										AND parent_order_id = %d",
								$params['order_id']
							)
						); // WPCS: cache ok, db call ok.
					} else {
						$refund_id = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT MAX(id) AS id
									FROM {$wpdb->prefix}posts
									WHERE post_type = 'shop_order_refund'
										AND post_parent = %d",
								$params['order_id']
							)
						); // WPCS: cache ok, db call ok.
					}

					if ( ! empty( $refund_id ) ) {
						$params ['refund_parent_id'] = $params['order_id'];
						$params ['order_id']         = $refund_id;
					}
				}

				$cond = ' AND posts.ID IN(' . intval( $params['order_id'] ) . ') ';
			}

			// Flag for woo2.2+.
			if ( defined( 'WPC_IS_WOO22' ) && 'true' === WPC_IS_WOO22 ) {
				$terms_post_join   = '';
				$select_status_col = ( $this->is_hpos_enabled ) ? 'orders.status AS order_status' : 'posts.post_status AS order_status';
				if ( ! empty( $params['trash'] ) ) {
					$post_order_cond = ( $this->is_hpos_enabled ) ? " AND orders.status = 'trash'" : " AND posts.post_status = 'trash'";
				} else {
					$post_order_cond = ( $this->is_hpos_enabled ) ? " AND orders.status NOT IN ('auto-draft', 'draft')" : " AND posts.post_status NOT IN ('auto-draft', 'draft')";
				}
			}

			// Code for handling manual refunds.
			if ( ! empty( $params ['refund_parent_id'] ) ) {
				$cond_type = ( $this->is_hpos_enabled ) ? " AND orders.type = 'shop_order_refund' " : " AND posts.post_type = 'shop_order_refund' ";
			} elseif ( ! empty( $params['order_id'] ) ) {
				$cond_type = ( $this->is_hpos_enabled ) ? " AND orders.type = 'shop_order' " : " AND posts.post_type = 'shop_order' ";
			} elseif ( empty( $params['order_id'] ) ) {
				$cond_type = ( $this->is_hpos_enabled ) ? " AND orders.type IN ('shop_order', 'shop_order_refund') " : " AND posts.post_type IN ('shop_order', 'shop_order_refund') ";
			}

			// Code to get all subscriptions in the specified date range.
			$modified_sub_ids     = array();
			$modified_sub_details = array();

			if ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

				$ps_cond = " ('auto-draft', 'draft') ";
				$results = $this->wpdb1->get_results(
					"SELECT ID as sub_id, 
						post_parent as order_id,
						date_format(post_date_gmt,'%Y-%m-%d %T') AS date,
						date_format(post_modified_gmt,'%Y-%m-%d %T') AS modified_date,
						date_format(post_modified_gmt,'%m/%d/%Y') AS formatted_modified_gmt_date,
						date_format(post_modified_gmt,'%T') AS formatted_modified_gmt_time,
						post_status as status
					FROM {$wpdb->prefix}posts
					WHERE post_status NOT IN $ps_cond
						AND post_type = 'shop_subscription'
						AND post_date_gmt != '0000-00-00 00:00:00'
						AND post_status != ''
						AND post_status IS NOT NULL
						AND post_modified_gmt BETWEEN '" . $params['start_date'] . "' AND '" . $params['end_date'] . "'
					GROUP BY id
					LIMIT " . $sub_start_limit . ',' . $batch_limit,
					'ARRAY_A'
				); // WPCS: cache ok, db call ok.

				if ( ! empty( $results ) && count( $results ) > 0 ) {

					foreach ( $results as $result ) {

						if ( empty( $modified_sub_details[ $result['sub_id'] ] ) ) {
							$modified_sub_details[ $result['sub_id'] ] = array(
								'post_created_gmt'       => $result['date'],
								'post_modified_gmt'      => $result['modified_date'],
								'post_modified_gmt_date' => $result['formatted_modified_gmt_date'],
								'post_modified_gmt_time' => $result['formatted_modified_gmt_time'],
								'post_status'            => $this->get_sub_status( $result['status'] ),
								'post_parent'            => $result['order_id'],
							);
						}
					}

					if ( ! empty( $modified_sub_details ) ) {

						$modified_sub_ids = array_keys( $modified_sub_details );

						$results = $this->wpdb1->get_results(
							"SELECT post_id,
								meta_key as mkey,
								meta_value as mvalue
							FROM {$wpdb->prefix}postmeta
							WHERE meta_key NOT IN ('_edit_lock', '_edit_last')
								AND post_id IN (" . implode( ',', $modified_sub_ids ) . ')',
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.

						if ( ! empty( $results ) && count( $results ) > 0 ) {

							foreach ( $results as $result ) {
								$modified_sub_details[ $result['post_id'] ][ $result['mkey'] ] = $result['mvalue'];
							}
						}
					}
				}

				if ( ! empty( $modified_sub_details ) ) {
					foreach ( $modified_sub_details as $sub_id => $modified_sub_detail ) {

						// Code for creating 'Recurring Payment' 'Created' transaction for subscriptions.
						$modified_sub_detail_og = $modified_sub_detail;

						if ( 'Delete' !== trim( $modified_sub_detail['post_status'] ) ) { // code for sending the created transaction only in non-trashed transactions.

							$modified_sub_detail['post_status']       = 'Created';
							$modified_sub_detail['post_modified_gmt'] = $modified_sub_detail['post_created_gmt'];

							$args = array(
								'sub_id'      => $sub_id,
								'sub_details' => ( ( ! empty( $modified_sub_detail ) ) ? $modified_sub_detail : array() ),
								'sub_meta'    => array(
									'payment_method' => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_payment_method'] ) ) ? $modified_sub_detail['_payment_method'] : '' ),
									'interval'       => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_billing_interval'] ) ) ? $modified_sub_detail['_billing_interval'] : '' ),
									'period'         => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_billing_period'] ) ) ? $modified_sub_detail['_billing_period'] : '' ),
									'trial_end'      => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_schedule_trial_end'] ) ) ? $modified_sub_detail['_schedule_trial_end'] : '' ),
									'sub_end'        => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_schedule_end'] ) ) ? $modified_sub_detail['_schedule_end'] : '' ),
								),
							);

							$sub_trans = apply_filters( 'putler_connector_sub_updated', $args );

							if ( ! empty( $sub_trans['data'] ) ) {
								$sub_transactions[] = $sub_trans['data'][0];
							}
						}

						// Code for creating 'Recurring Payment' current status transaction for subscriptions.
						$modified_sub_detail = $modified_sub_detail_og;

						if ( 'Canceled' === trim( $modified_sub_detail['post_status'] ) ) {
							$modified_sub_detail['post_modified_gmt'] = ( ! empty( $modified_sub_detail['_schedule_cancelled'] ) ) ? $modified_sub_detail['_schedule_cancelled'] : $modified_sub_detail['post_modified_gmt'];
						}

						$args = array(
							'sub_id'      => $sub_id,
							'new_status'  => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['post_status'] ) ) ? $modified_sub_detail['post_status'] : '' ),
							'sub_details' => ( ( ! empty( $modified_sub_detail ) ) ? $modified_sub_detail : array() ),
							'sub_meta'    => array(
								'payment_method' => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_payment_method'] ) ) ? $modified_sub_detail['_payment_method'] : '' ),
								'interval'       => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_billing_interval'] ) ) ? $modified_sub_detail['_billing_interval'] : '' ),
								'period'         => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_billing_period'] ) ) ? $modified_sub_detail['_billing_period'] : '' ),
								'trial_end'      => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_schedule_trial_end'] ) ) ? $modified_sub_detail['_schedule_trial_end'] : '' ),
								'sub_end'        => ( ( ! empty( $modified_sub_detail ) && ! empty( $modified_sub_detail['_schedule_end'] ) ) ? $modified_sub_detail['_schedule_end'] : '' ),
							),
						);

						$sub_trans = apply_filters( 'putler_connector_sub_updated', $args );

						if ( ! empty( $sub_trans['data'] ) ) {
							$sub_transactions[] = $sub_trans['data'][0];
						}
					}
				}
			}

			if ( $this->is_hpos_enabled ) {
				$order_ids_hpos        = array();
				$results_order_details = $this->wpdb1->get_results(
					"SELECT orders.id AS id, orders.type AS type, orders.parent_order_id AS parent_id, orders.customer_note AS order_note, orders.currency AS currency,
							orders.tax_amount AS tax_amount, orders.total_amount AS total_amount, orders.billing_email AS billing_email, orders.ip_address AS ip_address, 
							orders.payment_method AS payment_method, orders.transaction_id AS transaction_id,
							date_format(orders.date_created_gmt,'%Y-%m-%d %T') AS date,
							date_format(orders.date_created_gmt,'%m/%d/%Y') AS formatted_gmt_date,
							date_format(orders.date_created_gmt,'%T') AS formatted_gmt_time,
							date_format(orders.date_updated_gmt,'%Y-%m-%d %T') AS modified_date,
							date_format(orders.date_updated_gmt,'%m/%d/%Y') AS formatted_modified_gmt_date,
							date_format(orders.date_updated_gmt,'%T') AS formatted_modified_gmt_time,
							$select_status_col
							FROM {$wpdb->prefix}wc_orders AS orders 
							WHERE orders.date_created_gmt != '0000-00-00 00:00:00'
								AND orders.date_updated_gmt BETWEEN '" . $params['start_date'] . "' AND '" . $params['end_date'] . "'
								$cond_type
								$cond
								$post_order_cond
							GROUP BY orders.id
							LIMIT " . $start_limit . ',' . $batch_limit,
					'ARRAY_A'
				); // WPCS: cache ok, db call ok.
				if ( ! empty( $results_order_details ) ) {
					$order_ids_hpos = array_column( $results_order_details, 'id' );
					$discount_data  = $this->wpdb1->get_results(
						"SELECT order_id, SUM(discount_amount) AS total_discount 
						FROM {$wpdb->prefix}wc_order_coupon_lookup 
						WHERE order_id IN ( " . implode( ',', $order_ids_hpos ) . ' )
						GROUP BY order_id',
						'ARRAY_A'
					);
					$discounts      = array();
					if ( ! empty( $discount_data ) ) {
						foreach ( $discount_data as $d ) {
							$discounts[ $d['order_id'] ] = $d;
						}
					}

					$billing_address_data = $this->wpdb1->get_results(
						"SELECT order_id, first_name, last_name, email as billing_email, phone, address_1, 
						address_2, city, state, postcode, country
						FROM {$wpdb->prefix}wc_order_addresses
						WHERE order_id IN ( " . implode( ',', $order_ids_hpos ) . " ) AND address_type = 'billing'",
						'ARRAY_A'
					);
					$billing_address      = array();
					if ( ! empty( $billing_address_data ) ) {
						foreach ( $billing_address_data as $b ) {
							$billing_address[ $b['order_id'] ] = $b;
						}
					}

					$order_stats_data = $this->wpdb1->get_results(
						"SELECT order_id, total_sales, tax_total, shipping_total, net_total
						FROM {$wpdb->prefix}wc_order_stats
						WHERE order_id IN ( " . implode( ',', $order_ids_hpos ) . ' )',
						'ARRAY_A'
					);
					$order_stats      = array();
					if ( ! empty( $order_stats_data ) ) {
						foreach ( $order_stats_data as $o ) {
							$order_stats[ $o['order_id'] ] = $o;
						}
					}
				}
			} else {
				$results_order_details = $this->wpdb1->get_results(
					"SELECT posts.ID as id,
																		posts.post_type as type,
																		posts.post_parent as parent_id,
																		posts.post_excerpt as order_note,
																		date_format(posts.post_date_gmt,'%Y-%m-%d %T') AS date,
																		date_format(posts.post_date_gmt,'%m/%d/%Y') AS formatted_gmt_date,
																		date_format(posts.post_date_gmt,'%T') AS formatted_gmt_time,
																		date_format(posts.post_modified_gmt,'%Y-%m-%d %T') AS modified_date,
																		date_format(posts.post_modified_gmt,'%m/%d/%Y') AS formatted_modified_gmt_date,
																		date_format(posts.post_modified_gmt,'%T') AS formatted_modified_gmt_time,
																		$select_status_col
																		FROM {$wpdb->prefix}posts AS posts 
																			$terms_post_join
																		WHERE posts.post_date_gmt != '0000-00-00 00:00:00'
																			AND posts.post_modified_gmt BETWEEN '" . $params['start_date'] . "' AND '" . $params['end_date'] . "'
																				$cond_type
																				$post_order_cond
																		GROUP BY posts.ID
																		LIMIT " . $start_limit . ',' . $batch_limit,
					'ARRAY_A'
				); // WPCS: cache ok, db call ok.
			}
			$results_order_details_count = $wpdb->num_rows;

			if ( $results_order_details_count > 0 ) {

				$order_ids           = array();
				$order_trash_ids     = array();
				$man_refund_ids      = array(); // array for storing the manual refund ids.
				$recurring_order_ids = array(); // array for storing the recurring order_ids.

				foreach ( $results_order_details as $results_order_detail ) {
					$order_ids[] = ( ! empty( $results_order_detail['type'] ) && 'shop_order_refund' === $results_order_detail['type'] ) ? $results_order_detail['parent_id'] : $results_order_detail['id'];

					// for trash order ids.
					if ( ! empty( $results_order_detail['order_status'] ) && 'trash' === $results_order_detail['order_status'] ) {
						$order_trash_ids[] = $results_order_detail['id'];
					}

					if ( ! empty( $results_order_detail['type'] ) && 'shop_order_refund' === $results_order_detail['type'] ) {
						$man_refund_ids[ $results_order_detail['id'] ] = $results_order_detail['parent_id'];
					}
				}

				$order_ids = ( ! empty( $params ['refund_parent_id'] ) ) ? array( $params ['refund_parent_id'] ) : $order_ids; // for handling meta data for manual refunds.

				// Query to get the Order_items.

				$item_details             = array();
				$results_cart_items       = $this->wpdb1->get_results(
					"SELECT orderitems.order_id,
                                                orderitems.order_item_name,
                                                orderitems.order_item_id,
                                                orderitems.order_item_type AS item_type,
                                                itemmeta.meta_value AS mvalue,
                                                itemmeta.meta_key AS mkey
                                        FROM {$wpdb->prefix}woocommerce_order_items AS orderitems 
                                                JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta 
                                                    ON (orderitems.order_item_id = itemmeta.order_item_id)
                                        WHERE orderitems.order_item_type IN ('line_item', 'shipping', 'coupon')
                                            AND orderitems.order_id IN (" . implode( ',', $order_ids ) . ')
                                        GROUP BY orderitems.order_id, orderitems.order_item_id, meta_key',
					'ARRAY_A'
				); // WPCS: cache ok, db call ok.
				$results_cart_items_count = $wpdb->num_rows;

				$variation_ids = array();
				$product_ids   = array();
				$coupon_codes  = array();

				if ( $results_cart_items_count > 0 ) {

					foreach ( $results_cart_items as $cart_item ) {
						$order_id      = $cart_item['order_id'];
						$order_item_id = $cart_item['order_item_id'];

						if ( ! isset( $item_details[ $order_id ] ) ) {
							$item_details[ $order_id ]                           = array();
							$item_details[ $order_id ]['tot_qty']                = 0;
							$item_details[ $order_id ]['cart_items']             = array();
							$item_details[ $order_id ]['coupons']                = array();
							$item_details[ $order_id ]['_shipping_method_title'] = '';
						}

						// for getting the shipping related data.
						if ( 'shipping' === $cart_item['item_type'] ) {
							$item_details[ $order_id ]['_shipping_method_title'] = $cart_item['order_item_name'];
							continue;
						}

						// for getting the coupon related data.
						if ( 'coupon' === $cart_item['item_type'] ) {
							if ( ! isset( $item_details[ $order_id ]['coupons'][ $cart_item['order_item_name'] ] ) ) {
								$item_details[ $order_id ]['coupons'][ $cart_item['order_item_name'] ] = array();
							}

							// storing the distinct coupon codes.
							if ( false === array_search( $cart_item['order_item_name'], $coupon_codes, true ) ) {
								$coupon_codes[] = $cart_item['order_item_name'];
							}

							$item_details[ $order_id ]['coupons'][ $cart_item['order_item_name'] ]['code'] = $cart_item['order_item_name'];
							$item_details[ $order_id ]['coupons'][ $cart_item['order_item_name'] ]['amt']  = ( 'discount_amount' === $cart_item['mkey'] && ! empty( $cart_item['mvalue'] ) ) ? $cart_item['mvalue'] : 0;
							continue;
						}

						if ( ! isset( $item_details[ $order_id ]['cart_items'][ $order_item_id ] ) ) {
							$item_details[ $order_id ]['cart_items'][ $order_item_id ] = array();
							$item_details[ $order_id ]['tot_qty'] ++;
							$item_details[ $order_id ]['cart_items'][ $order_item_id ]['product_name'] = $cart_item['order_item_name'];
						}

						$item_details[ $order_id ]['cart_items'][ $order_item_id ][ $cart_item['mkey'] ] = $cart_item['mvalue'];

						if ( '_variation_id' === $cart_item['mkey'] && ! empty( $cart_item['mvalue'] ) ) {
							$variation_ids [] = $cart_item['mvalue'];
							$product_ids []   = $cart_item['mvalue'];
						}

						if ( '_product_id' === $cart_item['mkey'] && ! empty( $cart_item['mvalue'] ) ) {
							$product_ids [] = $cart_item['mvalue'];
						}
					}
				}

				// Code to get the coupon meta data.
				$coupon_data = array();

				if ( ! empty( $coupon_codes ) ) {
					$results = $this->wpdb1->get_results(
						"SELECT p.post_date as created_date,
														p.post_name as slug,
														p.post_title as code,
														p.post_excerpt as descrip,
														pm.meta_value AS mvalue,
														pm.meta_key AS mkey
													FROM {$wpdb->prefix}posts AS p
														JOIN {$wpdb->prefix}postmeta AS pm ON (pm.post_id = p.id 
																								AND p.post_type = 'shop_coupon'
																								AND pm.meta_key NOT IN ('_edit_lock', '_edit_last'))
													WHERE p.post_name IN ('" . implode( "','", $coupon_codes ) . "')
													GROUP BY p.id, meta_key",
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {
							if ( empty( $coupon_data[ $result['slug'] ] ) ) {
								$coupon_data[ $result['slug'] ]                 = array();
								$coupon_data[ $result['slug'] ]['code']         = $result['code'];
								$coupon_data[ $result['slug'] ]['description']  = $result['descrip'];
								$coupon_data[ $result['slug'] ]['created_date'] = $result['created_date'];
							}
							$coupon_data[ $result['slug'] ][ $result['mkey'] ] = $result['mvalue'];
						}
					}
				}

				$products_sku = array();
				$sub_item_ids = array();

				if ( ! empty( $product_ids ) ) {

					// Code to get the SKU for the products.

					$results_sku       = $this->wpdb1->get_results(
						"SELECT postmeta.post_id AS id,
																postmeta.meta_value AS sku
															FROM {$wpdb->prefix}posts AS posts
																JOIN {$wpdb->prefix}postmeta AS postmeta ON (posts.id = postmeta.post_id)
															WHERE posts.id IN (" . implode( ',', array_unique( $product_ids ) ) . ")
																AND postmeta.meta_key IN ('_sku')
																AND postmeta.meta_value <> ''
															GROUP BY posts.id",
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
					$results_sku_count = $wpdb->num_rows;

					if ( $results_sku_count > 0 ) {
						foreach ( $results_sku as $product_sku ) {
							$products_sku [ $product_sku['id'] ] = $product_sku['sku'];
						}
					}

					// Code to get the type for the products.
					$sub_taxonomy_ids = $wpdb->get_col(
						$wpdb->prepare(
							"SELECT tt.term_taxonomy_id AS taxonomy_id
								FROM {$wpdb->prefix}term_taxonomy AS tt
									JOIN {$wpdb->prefix}terms AS t
										ON (t.term_id = tt.term_id 
										AND tt.taxonomy = %s)
								WHERE t.slug IN ('subscription', 'variable-subscription')",
							'product_type'
						)
					); // WPCS: cache ok, db call ok.

					if ( ! empty( $sub_taxonomy_ids ) && count( $sub_taxonomy_ids ) > 0 ) {
						$sub_item_ids = $this->wpdb1->get_col(
							"SELECT tr.object_id AS id
														FROM {$wpdb->prefix}term_relationships AS tr
														JOIN {$wpdb->prefix}term_taxonomy AS tt 
															ON (tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = 'product_type')
														WHERE tt.term_taxonomy_id IN (" . implode( ',', array_unique( $sub_taxonomy_ids ) ) . ')'
						); // WPCS: cache ok, db call ok.
					}
				}

				// Query to get the variation Attributes.

				if ( ! empty( $variation_ids ) ) {
					$results_variation_att       = $this->wpdb1->get_results(
						"SELECT post_id AS id,
                                                   meta_value AS meta_value,
                                                   meta_key AS meta_key
                                                FROM {$wpdb->prefix}postmeta
                                                WHERE meta_key LIKE 'attribute_%'
                                                    AND post_id IN (" . implode( ',', array_unique( $variation_ids ) ) . ')
                                                GROUP BY id,meta_key',
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
					$results_variation_att_count = $wpdb->num_rows;

					if ( $results_variation_att_count > 0 ) {

						$i                = 0;
						$attributes_terms = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT terms.slug as slug, terms.name as term_name
									FROM {$wpdb->prefix}terms AS terms
									JOIN {$wpdb->prefix}postmeta AS postmeta 
										ON ( postmeta.meta_value = terms.slug 
												AND postmeta.meta_key LIKE %s ) 
									GROUP BY terms.slug",
								$wpdb->esc_like( 'attribute_' ) . '%'
							),
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.

						$attributes = array();
						foreach ( $attributes_terms as $attributes_term ) {
							$attributes[ $attributes_term['slug'] ] = $attributes_term['term_name'];
						}

						$variations = array();
						// Formatting of the Variations Names.
						foreach ( $results_variation_att as $variation_att ) {

							$att_name = '';
							$att_val  = '';

							if ( empty( $variations[ $variation_att['id'] ] ) ) {
								$i                                  = 0;
								$variations[ $variation_att['id'] ] = array();
							}

							if ( false !== strpos( $variation_att['meta_key'], 'pa' ) ) {
								$att_name = substr( $variation_att['meta_key'], strpos( $variation_att['meta_key'], 'pa' ) + 3 );
								$att_val  = ( ! empty( $attributes[ $variation_att['meta_value'] ] ) ) ? $attributes[ $variation_att['meta_value'] ] : '';
							} else {
								$att_name = ucfirst( substr( $variation_att['meta_key'], strpos( $variation_att['meta_key'], '_' ) + 1 ) );
								$att_val  = $variation_att['meta_value'];
							}

							if ( $i > 1 ) {

								if ( 2 === $i ) {
									$variations[ $variation_att['id'] ][0]['option1_value'] = $variations[ $variation_att['id'] ][0]['option1_name'] . ' : ' . $variations[ $variation_att['id'] ][0]['option1_value'];

									if ( ! empty( $variations[ $variation_att['id'] ][1] ) ) {
										$variations[ $variation_att['id'] ][0]['option1_value'] .= ', ' . $variations[ $variation_att['id'] ][1]['option1_name'] . ' : ' . $variations[ $variation_att['id'] ][1]['option1_value'];
										unset( $variations[ $variation_att['id'] ][1] );
									}
								}

								$variations[ $variation_att['id'] ][0]['option1_name']  = '';
								$variations[ $variation_att['id'] ][0]['option1_value'] = $variations[ $variation_att['id'] ][0]['option1_value'] . ', '
																						. $att_name . ' : ' . $att_val;

							} else {
								$variations[ $variation_att['id'] ][ $i ]['option1_name']  = $att_name;
								$variations[ $variation_att['id'] ][ $i ]['option1_value'] = $att_val;
							}

							$i ++;
						}
					}
				}

				// Code for handling subscriptions meta data.
				if ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
					$sub_ids      = array();
					$sub_item_ids = array();
					$sub_details  = array();
					$sub_o_ids    = array();

					$ps_cond = " ('auto-draft', 'draft') ";

					// query to get subscriptions and renewal subscription ids.
					if ( $this->is_hpos_enabled ) {
						$results = $this->wpdb1->get_results(
							"SELECT id AS sub_id,
									parent_order_id AS order_id,
									'' AS meta_key
								FROM {$wpdb->prefix}wc_orders
								WHERE type = 'shop_subscription'
									AND parent_order_id IN (" . implode( ',', $order_ids ) . ")
									AND status NOT IN $ps_cond
									AND date_created_gmt != '0000-00-00 00:00:00'
								GROUP BY id
								UNION
								SELECT om.meta_value AS sub_id,
									om.order_id AS order_id,
									om.meta_key AS meta_key
								FROM {$wpdb->prefix}wc_orders_meta AS om
									JOIN {$wpdb->prefix}wc_orders AS o ON (om.order_id = o.id 
																		AND o.type = 'shop_order'
																		AND o.parent_order_id = 0 
																		AND om.meta_key = '_subscription_renewal')
								WHERE om.order_id IN (" . implode( ',', $order_ids ) . ")
									AND o.status NOT IN $ps_cond
									AND o.date_created_gmt != '0000-00-00 00:00:00'
								GROUP BY om.meta_value, om.order_id",
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.
					} else {
						$results = $this->wpdb1->get_results(
							"SELECT id AS sub_id,
															post_parent AS order_id,
															'' AS meta_key
													FROM {$wpdb->prefix}posts
													WHERE post_type = 'shop_subscription'
														AND post_parent IN (" . implode( ',', $order_ids ) . ")
														AND post_status NOT IN $ps_cond
														AND post_date_gmt != '0000-00-00 00:00:00'
													GROUP BY id
													UNION
													SELECT pm.meta_value AS sub_id,
														pm.post_id AS order_id,
														pm.meta_key AS meta_key
													FROM {$wpdb->prefix}postmeta AS pm
														JOIN {$wpdb->prefix}posts AS p ON (pm.post_id = p.id 
																							AND p.post_type = 'shop_order'
																							AND p.post_parent = 0
																							AND pm.meta_key = '_subscription_renewal')
													WHERE pm.post_id IN (" . implode( ',', $order_ids ) . ")
														AND p.post_status NOT IN $ps_cond
														AND p.post_date_gmt != '0000-00-00 00:00:00'
													GROUP BY pm.meta_value, pm.post_id",
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.
					}

					$trashed_sub_o_ids = array(); // to store sub_ids whose order has been trashed.

					// Code to merge all modified subscriptions & Renewal Subscriptions.

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {

							$trashed_o_id = array_search( $result['order_id'], $order_trash_ids, true );

							if ( false !== $trashed_o_id ) {
								$trashed_sub_o_ids[] = $result['sub_id'];
							}

							$sub_o_ids[ $result['order_id'] ] = $result['sub_id'];

							if ( ! empty( $result['meta_key'] ) ) {
								$recurring_order_ids[ $result['order_id'] ] = $result['meta_key'];
							}

							// storing the distinct subscription ids.
							if ( false === array_search( $result['sub_id'], $sub_ids, true ) ) {
								$sub_ids[] = $result['sub_id'];
							}
						}
					}

					if ( ! empty( $sub_ids ) ) {

						// query to get sub data.
						$results = $this->wpdb1->get_results(
							"SELECT id as sub_id, 
															post_parent as order_id,
															date_format(post_date_gmt,'%Y-%m-%d %T') AS date,
															date_format(post_modified_gmt,'%Y-%m-%d %T') AS modified_date,
															date_format(post_modified_gmt,'%m/%d/%Y') AS formatted_modified_date,
															date_format(post_modified_gmt,'%T') AS formatted_modified_time,
															post_status as status
													FROM {$wpdb->prefix}posts
													WHERE post_type = 'shop_subscription'
														AND id IN (" . implode( ',', $sub_ids ) . ")
														AND post_status NOT IN $ps_cond
														AND post_date_gmt != '0000-00-00 00:00:00'
													GROUP BY id",
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.

						if ( ! empty( $results ) && count( $results ) > 0 ) {
							foreach ( $results as $result ) {
								if ( ! isset( $sub_details[ $result['sub_id'] ] ) && empty( $params['order_id'] ) ) {
									$sub_details[ $result['sub_id'] ] = array(
										'post_created_gmt' => $result['date'],
										'post_modified_gmt' => $result['modified_date'],
										'post_modified_gmt_date' => $result['formatted_modified_date'],
										'post_modified_gmt_time' => $result['formatted_modified_time'],
										'post_status'      => $this->get_sub_status( $result['status'] ),
										'post_parent'      => $result['order_id'],
									);
								}
							}
						}

						if ( ! empty( $item_details ) ) {
							foreach ( $item_details as $order_id => $item_detail ) {
								if ( empty( $sub_o_ids[ $order_id ] ) ) {
									continue;
								}

								if ( ! empty( $item_details[ $order_id ]['subscriptions'] ) ) {
									$item_details[ $order_id ]['subscriptions'] = array();
								}

								if ( ! empty( $sub_details[ $sub_o_ids[ $order_id ] ] ) ) {
									$item_details[ $order_id ]['subscriptions'] [ $sub_o_ids[ $order_id ] ] = array(
										'created_date'  => $sub_details[ $sub_o_ids[ $order_id ] ]['post_created_gmt'],
										'modified_date' => $sub_details[ $sub_o_ids[ $order_id ] ]['post_modified_gmt'],
										'status'        => $sub_details[ $sub_o_ids[ $order_id ] ]['post_status'],
									);

									if ( ! empty( $recurring_order_ids[ $order_id ] ) ) {
										$item_details[ $order_id ]['subscriptions'][ $recurring_order_ids[ $order_id ] ] = 1;
									}
								}

								// Code for handling multiple subscriptions in single order.
								if ( ! empty( $sub_order_ids[ $order_id ] ) && ! is_array( $sub_order_ids[ $order_id ] ) ) {
									$temp                          = $sub_order_ids[ $order_id ];
									$sub_order_ids[ $order_id ]    = array();
									$sub_order_ids[ $order_id ][0] = $temp;
									$sub_order_ids[ $order_id ][1] = $sub_o_ids[ $order_id ];
								} elseif ( ! empty( $sub_order_ids[ $order_id ] ) && is_array( $sub_order_ids[ $order_id ] ) ) {
									$sub_order_ids[ $order_id ][] = $sub_o_ids[ $order_id ];
								} else {
									$sub_order_ids[ $order_id ] = $sub_o_ids[ $order_id ];
								}
							}
						}

						// Code for getting the subscription meta data.
						$results = $this->wpdb1->get_results(
							"SELECT post_id as id,
																meta_value as mvalue,
																meta_key as mkey    
														FROM {$wpdb->prefix}postmeta
														WHERE post_id IN (" . implode( ',', $sub_ids ) . ")
															AND meta_key NOT IN ('_edit_lock', '_edit_last')
														GROUP BY id,meta_key",
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.

						if ( ! empty( $results ) && count( $results ) > 0 ) {

							foreach ( $item_details as $order_id => &$item_detail ) {

								if ( empty( $item_detail['subscriptions'] ) ) {
									continue;
								}

								foreach ( $results as $result ) {

									if ( isset( $item_detail['subscriptions'][ $result['id'] ] ) ) {
										$item_detail['subscriptions'][ $result['id'] ][ $result['mkey'] ] = $result['mvalue'];
									}

									// code for storing sub meta for 'Recurring Payment' transaction.
									if ( ! empty( $sub_details[ $result['id'] ] ) && empty( $params['order_id'] ) ) {
										$sub_details[ $result['id'] ][ $result['mkey'] ] = $result['mvalue'];
									}
								}
							}

							$sub_order_details = array();

							// code to get the billing meta information from orders (useful in case the sub has not recorded any billing info).
							if ( ! empty( $sub_o_ids ) ) {

								$o_ids = array_keys( $sub_o_ids );
								if ( $this->is_hpos_enabled ) {
									$results = $this->wpdb1->get_results(
										"SELECT order_id as id,
														meta_value as mvalue,
														meta_key as mkey    
												FROM {$wpdb->prefix}wc_orders_meta
												WHERE order_id IN (" . implode( ',', $o_ids ) . ")
													AND meta_key IN ('Stripe Fee', '_stripe_fee')
												GROUP BY id,meta_key",
										'ARRAY_A'
									); // WPCS: cache ok, db call ok.
								} else {
									$results = $this->wpdb1->get_results(
										"SELECT post_id as id,
														meta_value as mvalue,
														meta_key as mkey    
												FROM {$wpdb->prefix}postmeta
												WHERE post_id IN (" . implode( ',', $o_ids ) . ")
													AND meta_key IN ('_billing_first_name', '_billing_last_name', '_order_currency', '_billing_email', 'Stripe Fee', '_stripe_fee')
												GROUP BY id,meta_key",
										'ARRAY_A'
									); // WPCS: cache ok, db call ok.
								}

								if ( ! empty( $results ) && count( $results ) > 0 ) {

									foreach ( $results as $result ) {
										if ( ! isset( $sub_order_details[ $result['id'] ] ) ) {
											$sub_order_details[ $result['id'] ] = array();
										}

										$sub_order_details[ $result['id'] ][ $result['mkey'] ] = $result['mvalue'];
									}
								}
							}

							// Code for creating 'Recurring Payment' transactions for subscriptions.
							if ( empty( $params['order_id'] ) ) { // only for data sync.

								foreach ( $sub_ids as $sub_id ) {

									if ( empty( $sub_details[ $sub_id ]['post_status'] ) || in_array( $sub_id, $modified_sub_ids, true ) ) { // code for ignoring the subscriptions with blank status or whose sub_transaction is already present.
										continue;
									}

									// Code for checking if the billing info is blank for the subs.
									$sub_o_id   = array_search( $sub_id, $sub_o_ids, true );
									$stripe_fee = ( ! empty( $sub_details[ $sub_id ]['Stripe Fee'] ) ) ? $sub_details[ $sub_id ]['Stripe Fee'] : '';

									$sub_details[ $sub_id ]['_billing_first_name'] = ( empty( $sub_details[ $sub_id ]['_billing_first_name'] ) && ! empty( $sub_o_id ) ) ? $sub_order_details[ $sub_o_id ]['_billing_first_name'] : $sub_details[ $sub_id ]['_billing_first_name'];
									$sub_details[ $sub_id ]['_billing_last_name']  = ( empty( $sub_details[ $sub_id ]['_billing_last_name'] ) && ! empty( $sub_o_id ) ) ? $sub_order_details[ $sub_o_id ]['_billing_last_name'] : $sub_details[ $sub_id ]['_billing_last_name'];
									$sub_details[ $sub_id ]['_order_currency']     = ( empty( $sub_details[ $sub_id ]['_order_currency'] ) && ! empty( $sub_o_id ) ) ? $sub_order_details[ $sub_o_id ]['_order_currency'] : $sub_details[ $sub_id ]['_order_currency'];
									$sub_details[ $sub_id ]['_billing_email']      = ( empty( $sub_details[ $sub_id ]['_billing_email'] ) && ! empty( $sub_o_id ) ) ? $sub_order_details[ $sub_o_id ]['_billing_email'] : $sub_details[ $sub_id ]['_billing_email'];
									$sub_details[ $sub_id ]['Stripe Fee']          = ( empty( $stripe_fee ) && ! empty( $sub_o_id ) && ! empty( $sub_order_details[ $sub_o_id ]['Stripe Fee'] ) ) ? $sub_order_details[ $sub_o_id ]['Stripe Fee'] : $stripe_fee;
									$sub_details[ $sub_id ]['Stripe Fee']          = ( empty( $sub_details[ $sub_id ]['_stripe_fee'] ) && ! empty( $sub_o_id ) && ! empty( $sub_order_details[ $sub_o_id ]['_stripe_fee'] ) ) ? $sub_order_details[ $sub_o_id ]['_stripe_fee'] : $stripe_fee;

									// Code for creating 'Recurring Payment' 'Created' transaction for subscriptions.

									$sub_details_og = $sub_details[ $sub_id ];

									if ( 'Delete' !== trim( $sub_details[ $sub_id ]['post_status'] ) ) { // code for sending the created transaction only in non-trashed transactions.

										$sub_details[ $sub_id ]['post_status']       = 'Created';
										$sub_details[ $sub_id ]['post_modified_gmt'] = $sub_details[ $sub_id ]['post_created_gmt'];

										$args = array(
											'sub_id'      => $sub_id,
											'sub_details' => ( ( ! empty( $sub_details[ $sub_id ] ) ) ? $sub_details[ $sub_id ] : array() ),
											'sub_meta'    => array(
												'payment_method' => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_payment_method'] ) ) ? $sub_details[ $sub_id ]['_payment_method'] : '' ),
												'interval' => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_billing_interval'] ) ) ? $sub_details[ $sub_id ]['_billing_interval'] : '' ),
												'period'   => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_billing_period'] ) ) ? $sub_details[ $sub_id ]['_billing_period'] : '' ),
												'trial_end' => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_schedule_trial_end'] ) ) ? $sub_details[ $sub_id ]['_schedule_trial_end'] : '' ),
												'sub_end'  => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_schedule_end'] ) ) ? $sub_details[ $sub_id ]['_schedule_end'] : '' ),
											),
										);

										$sub_trans = apply_filters( 'putler_connector_sub_updated', $args );

										if ( ! empty( $sub_trans['data'] ) ) {
											$sub_transactions[] = $sub_trans['data'][0];
										}
									}

									// Code for creating 'Recurring Payment' current status transaction for subscriptions.

									$sub_details[ $sub_id ] = $sub_details_og;

									if ( 'Canceled' === trim( $sub_details[ $sub_id ]['post_status'] ) ) {
										$sub_details[ $sub_id ]['post_modified_gmt'] = ( ! empty( $sub_details[ $sub_id ]['_schedule_cancelled'] ) ) ? $sub_details[ $sub_id ]['_schedule_cancelled'] : $sub_details[ $sub_id ]['post_modified_gmt'];
									}

									$args = array(
										'sub_id'      => $sub_id,
										'new_status'  => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['post_status'] ) ) ? $sub_details[ $sub_id ]['post_status'] : '' ),
										'sub_details' => ( ( ! empty( $sub_details[ $sub_id ] ) ) ? $sub_details[ $sub_id ] : array() ),
										'sub_meta'    => array(
											'payment_method' => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_payment_method'] ) ) ? $sub_details[ $sub_id ]['_payment_method'] : '' ),
											'interval'  => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_billing_interval'] ) ) ? $sub_details[ $sub_id ]['_billing_interval'] : '' ),
											'period'    => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_billing_period'] ) ) ? $sub_details[ $sub_id ]['_billing_period'] : '' ),
											'trial_end' => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_schedule_trial_end'] ) ) ? $sub_details[ $sub_id ]['_schedule_trial_end'] : '' ),
											'sub_end'   => ( ( ! empty( $sub_details[ $sub_id ] ) && ! empty( $sub_details[ $sub_id ]['_schedule_end'] ) ) ? $sub_details[ $sub_id ]['_schedule_end'] : '' ),
										),
									);

									$sub_trans = apply_filters( 'putler_connector_sub_updated', $args );

									if ( ! empty( $sub_trans['data'] ) ) {
										$sub_transactions[] = $sub_trans['data'][0];
									}
								}
							}
						}

						$sub_pids     = array();
						$sub_prod_ids = array();

						// query to get the subscription order item ids.
						if ( ! empty( $sub_order_ids ) ) {
							$results = $this->wpdb1->get_results(
								"SELECT oi.order_id as id,
																GROUP_CONCAT(oim.meta_key order by oim.meta_id SEPARATOR ' #wpc# ') AS meta_key,
																GROUP_CONCAT(oim.meta_value order by oim.meta_id SEPARATOR ' #wpc# ') AS meta_value
															FROM {$wpdb->prefix}woocommerce_order_items AS oi
																JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim 
																	ON (oim.order_item_id = oi.order_item_id
																			AND oi.order_item_type = 'line_item')
															WHERE oi.order_id IN (" . implode( ',', $sub_ids ) . ")
																AND oim.meta_key IN ('_product_id', '_variation_id')
															GROUP BY id",
								'ARRAY_A'
							); // WPCS: cache ok, db call ok.

							if ( ! empty( $results ) && count( $results ) > 0 ) {
								foreach ( $results as $result ) {
									$meta_values = explode( ' #wpc# ', $result['meta_value'] );
									$meta_key    = explode( ' #wpc# ', $result['meta_key'] );

									if ( count( $meta_values ) !== count( $meta_key ) ) {
										continue;
									}

									$temp = array_combine( $meta_key, $meta_values );

									$sub_pids[]                    = ( ! empty( $temp['_variation_id'] ) ) ? $temp['_variation_id'] : $temp['_product_id'];
									$sub_prod_ids[ $result['id'] ] = ( ! empty( $temp['_variation_id'] ) ) ? $temp['_variation_id'] : $temp['_product_id'];
								}

								// query to get the order item ids for subscription orders.
								$results = $this->wpdb1->get_results(
									"SELECT oi.order_id AS id,
																	oi.order_item_id AS item_id,
																	oim.meta_value AS pid
																FROM {$wpdb->prefix}woocommerce_order_items AS oi
																	JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS oim 
																		ON (oim.order_item_id = oi.order_item_id
																				AND oi.order_item_type = 'line_item')
																WHERE oi.order_id IN (" . implode( ',', array_keys( $sub_order_ids ) ) . ')
																	AND oim.meta_value IN (' . implode( ',', $sub_pids ) . ")
																	AND oim.meta_key IN ('_product_id', '_variation_id')",
									'ARRAY_A'
								); // WPCS: cache ok, db call ok.

								if ( ! empty( $results ) && count( $results ) > 0 ) {
									foreach ( $results as $result ) {

										if ( empty( $sub_order_ids[ $result['id'] ] ) ) {
											continue;
										}

										if ( is_array( $sub_order_ids[ $result['id'] ] ) ) {
											foreach ( $sub_order_ids[ $result['id'] ] as $sub_id ) {

												if ( empty( $sub_prod_ids[ $sub_id ] ) ) {
													continue;
												}

												if ( intval( $sub_prod_ids[ $sub_id ] ) === intval( $result['pid'] ) ) {
													$sub_item_ids[ $result['item_id'] ] = $sub_id;
													break;
												}
											}
										} else {
											$sub_item_ids[ $result['item_id'] ] = $sub_order_ids[ $result['id'] ];
										}
									}
								}
							}
						}
					}
				}

				$sub_renewal_id = array();
				$sub_parent_id  = array();
				$sub_meta       = array();

				if ( ! empty( $sub_item_ids ) ) {

					// Code for generating subscription meta.
					$sub_meta_keys = array();

					$sub_meta_keys['_payment_method']     = 'payment_method';
					$sub_meta_keys['_billing_interval']   = 'interval';
					$sub_meta_keys['_billing_period']     = 'period';
					$sub_meta_keys['_schedule_trial_end'] = 'trial_end';
					$sub_meta_keys['_schedule_end']       = 'sub_end';

					// query to get the sub meta details.
					$results = $this->wpdb1->get_results(
						"SELECT post_id,
															meta_key as mkey,
															meta_value as mvalue
													FROM {$wpdb->prefix}postmeta
													WHERE post_id IN ( " . implode( ',', $sub_item_ids ) . " )
														AND meta_key IN ('_payment_method', '_billing_interval', '_billing_period', '_schedule_trial_end', '_schedule_end') 
													GROUP BY post_id, meta_key",
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {
							if ( ! empty( $result['mkey'] ) && ! empty( $sub_meta_keys[ $result['mkey'] ] ) ) {

								if ( empty( $sub_meta[ $result['post_id'] ] ) ) {
									$sub_meta[ $result['post_id'] ] = array();
								}

								$sub_meta[ $result['post_id'] ][ $sub_meta_keys[ $result['mkey'] ] ] = $result['mvalue'];
							}

							if ( ! isset( $sub_meta[ $result['post_id'] ]['status'] ) ) {
								$sub_meta[ $result['post_id'] ]['status'] = $this->get_sub_status( get_post_status( $result['post_id'] ) );
							}
						}
					}

					// code to get sub meta for switch subscriptions.
					if ( $this->is_hpos_enabled ) {
						$results = $this->wpdb1->get_results(
							"SELECT om.meta_value AS sub_id,
									MAX(om.order_id) AS order_id
							FROM {$wpdb->prefix}wc_orders_meta AS om
								JOIN {$wpdb->prefix}wc_orders AS o ON (o.id = om.order_id 
																	AND o.type = 'shop_order' 
																	AND om.meta_key = '_subscription_switch')
							WHERE o.id IN ( SELECT parent_order_id FROM {$wpdb->prefix}wc_orders WHERE type = 'shop_subscription' AND id IN ( " . implode( ',', $sub_item_ids ) . ' ) )
							GROUP BY sub_id',
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.
					} else {
						$results = $this->wpdb1->get_results(
							"SELECT pm.meta_value AS sub_id,
																MAX(pm.post_id) AS order_id
														FROM {$wpdb->prefix}postmeta AS pm
															JOIN {$wpdb->prefix}posts AS p ON (p.id = pm.post_id 
																								AND p.post_type = 'shop_order' 
																								AND pm.meta_key = '_subscription_switch')
														WHERE p.id IN ( SELECT post_parent FROM {$wpdb->prefix}posts WHERE post_type = 'shop_subscription' AND id IN ( " . implode( ',', $sub_item_ids ) . ' ) )
														GROUP BY sub_id',
							'ARRAY_A'
						); // WPCS: cache ok, db call ok.
					}

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {
							$sub_renewal_id[ $result['order_id'] ] = $result['sub_id'];
						}
					}

					// code to get the parent ids for switch subscriptions.
					$results = $this->wpdb1->get_results(
						"SELECT id AS sub_id,
														post_parent AS order_id
													FROM {$wpdb->prefix}posts
													WHERE post_type = 'shop_subscription'
														AND id IN ( " . implode( ',', $sub_item_ids ) . ' )
													GROUP BY sub_id',
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {
							$sub_parent_id[ $result['sub_id'] ] = $result['order_id'];
						}
					}
				}

				// Query to get the Order Details.
				if ( $this->is_hpos_enabled ) {
					$results_order_item_details = $this->wpdb1->get_results(
						"SELECT order_id as id,
							meta_value as mvalue,
							meta_key as mkey	
							FROM {$wpdb->prefix}wc_orders_meta
							WHERE order_id IN (" . implode( ',', $order_ids_hpos ) . ")
								AND meta_key NOT IN ('_edit_lock', '_edit_last')
							GROUP BY id,meta_key",
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				} else {
					$results_order_item_details = $this->wpdb1->get_results(
						"SELECT post_id as id,
																						meta_value as mvalue,
																						meta_key as mkey	
																						FROM {$wpdb->prefix}postmeta
																						WHERE post_id IN (" . implode( ',', $order_ids ) . ")
																							AND meta_key NOT IN ('_edit_lock', '_edit_last')
																						GROUP BY id,meta_key",
						'ARRAY_A'
					); // WPCS: cache ok, db call ok.
				}

				$results_order_item_details_count = $wpdb->num_rows;

				if ( $results_order_item_details_count > 0 ) {

					$order_items = array();
					// Structuring the order items.
					foreach ( $results_order_item_details as $detail ) {

						if ( ! isset( $order_items[ $detail['id'] ] ) ) {
							$order_items[ $detail['id'] ] = array();
						}

						$order_items[ $detail['id'] ][ $detail['mkey'] ] = $detail['mvalue'];
					}

					$paid_order_statuses = ( function_exists( 'wc_get_is_paid_statuses' ) ) ? wc_get_is_paid_statuses() : apply_filters( 'woocommerce_order_is_paid_statuses', array( 'processing', 'completed' ) ); // for handling custom order statuses to be considered as sales.

					$paid_order_statuses = apply_filters( 'woocommerce_reports_order_statuses', $paid_order_statuses );

					// Code for Data Mapping as per Putler.
					foreach ( $results_order_details as $order_detail ) {

						$sub_trans = false; // flag for handling subscription transactions.

						$order_id = ( ! empty( $params['refund_parent_id'] ) ) ? $params['refund_parent_id'] : ( ( 'shop_order_refund' === $order_detail['type'] ) ? $man_refund_ids[ $order_detail['id'] ] : $order_detail['id'] );
						if ( ( ( ! empty( $params['refund_parent_id'] ) || 'shop_order_refund' === $order_detail['type'] ) ) && ! $this->is_hpos_enabled ) {
							$refund_amount = get_post_meta( $order_detail['id'], '_refund_amount', true );
							$order_total   = is_numeric( $refund_amount ) ? round( floatval( $refund_amount ), 2 ) : 0.00;
						} else {
							$order_total = ( ! empty( $order_items[ $order_id ] ) && ! empty( $order_items[ $order_id ]['_order_total'] ) && is_numeric( $order_items[ $order_id ]['_order_total'] ) ) ? round( floatval( $order_items[ $order_id ]['_order_total'] ), 2 ) : 0.00;
						}
						$date_in_gmt = $order_detail['formatted_gmt_date'];
						$time_in_gmt = $order_detail['formatted_gmt_time'];

						if ( defined( 'WPC_IS_WOO22' ) && 'true' === WPC_IS_WOO22 ) {
							$order_status_new = ( ! empty( $order_detail['order_status'] ) ) ? ( ( 'wc-' === substr( $order_detail['order_status'], 0, 3 ) ) ? substr( $order_detail['order_status'], 3 ) : $order_detail['order_status'] ) : '';
						}

						if ( empty( $order_status_new ) ) { // code for ignoring the orders with blank status.
							continue;
						}
						$order_status_display = ( ! empty( $order_detail['order_status'] ) && ! empty( $wc_order_status[ $order_detail['order_status'] ] ) ) ? $wc_order_status[ $order_detail['order_status'] ] : $order_status_new;
						$verbose_status       = $order_status_display;

						if ( 'on-hold' === $order_status_new || 'pending' === $order_status_new || 'failed' === $order_status_new ) {
							$order_status_display = ( 'failed' !== $order_status_new ) ? 'Pending' : 'Failed';
						} elseif ( 'completed' === $order_status_new || 'processing' === $order_status_new || in_array( $order_status_new, $paid_order_statuses, true ) ) {
							$order_status_display = 'Completed';
						} elseif ( 'refunded' === $order_status_new ) {
							$order_status_display = 'Refunded';
						} elseif ( 'cancelled' === $order_status_new ) {
							$order_status_display = 'Cancelled';
						} else {
							if ( defined( 'WPC_IS_WOO22' ) && 'true' === WPC_IS_WOO22 ) { // for handling any custom order statuses.

								if ( ! empty( $order_detail['order_status'] ) && 'trash' === $order_detail['order_status'] ) {
									$order_status_display = 'Delete';
								}
							} else {
								$order_status_display = $order_status_new;

								if ( ! empty( $params['trash'] ) ) {
									$order_status_display = 'Delete';
								}
							}
						}

						$response ['Date']      = $date_in_gmt;
						$response ['Time']      = $time_in_gmt;
						$response ['Time_Zone'] = 'GMT';

						$response ['Source']                       = $this->name;
						$response ['Name']                         = ( ! empty( $order_items[ $order_id ]['_billing_first_name'] ) ) ? $order_items[ $order_id ]['_billing_first_name'] : '';
						$response ['Name']                        .= ( ! empty( $order_items[ $order_id ]['_billing_last_name'] ) ) ? ' ' . $order_items[ $order_id ]['_billing_last_name'] : '';
						$response ['Type']                         = 'Shopping Cart Payment Received';
						$response ['Status']                       = ucfirst( $order_status_display );
						$response ['Currency']                     = ( ! empty( $order_items[ $order_id ]['_order_currency'] ) ) ? $order_items[ $order_id ]['_order_currency'] : '';
						$response ['Gross']                        = $order_total;
						$response ['Fee']                          = ( ! empty( $order_items[ $order_id ]['Stripe Fee'] ) ) ? $order_items[ $order_id ]['Stripe Fee'] : 0.00;
						$response ['Fee']                          = ( empty( $response ['Fee'] ) && ! empty( $order_items[ $order_id ]['_stripe_fee'] ) ) ? $order_items[ $order_id ]['_stripe_fee'] : 0.00;
						$response ['Net']                          = $order_total;
						$response ['From_Email_Address']           = ( ! empty( $order_items[ $order_id ]['_billing_email'] ) ) ? $order_items[ $order_id ]['_billing_email'] : '';
						$response ['To_Email_Address']             = '';
						$response ['Transaction_ID']               = $order_id;
						$response ['Counterparty_Status']          = '';
						$response ['Address_Status']               = '';
						$response ['Item_Title']                   = 'Shopping Cart';
						$response ['Item_ID']                      = 0; // Set to 0 for main Order Transaction row.
						$response ['Shipping_and_Handling_Amount'] = ( isset( $order_items[ $order_id ]['_order_shipping'] ) && ( empty( $params['refund_parent_id'] ) && 'shop_order_refund' !== $order_detail['type'] ) && is_numeric( $order_items[ $order_id ]['_order_shipping'] ) ) ? round( floatval( $order_items[ $order_id ]['_order_shipping'] ), 2 ) : 0.00;
						$response ['Insurance_Amount']             = '';
						$response ['Discount']                     = ( isset( $order_items[ $order_id ]['_order_discount'] ) && ( empty( $params['refund_parent_id'] ) && $order_detail['type'] ) && is_numeric( $order_items[ $order_id ]['_order_discount'] ) ) ? round( floatval( $order_items[ $order_id ]['_order_discount'] ), 2 ) : 0.00;
						$response ['Discount']                    += ( isset( $order_items[ $order_id ]['_cart_discount'] ) && ( empty( $params['refund_parent_id'] ) && 'shop_order_refund' !== $order_detail['type'] ) && is_numeric( $order_items[ $order_id ]['_cart_discount'] ) ) ? round( floatval( $order_items[ $order_id ]['_cart_discount'] ), 2 ) : 0.00;
						$response ['Sales_Tax']                    = ( isset( $order_items[ $order_id ]['_order_tax'] ) && ( empty( $params['refund_parent_id'] ) && 'shop_order_refund' !== $order_detail['type'] ) && is_numeric( $order_items[ $order_id ]['_order_tax'] ) ) ? round( floatval( $order_items[ $order_id ]['_order_tax'] ), 2 ) : 0.00;
						$response ['Option_1_Name']                = '';
						$response ['Option_1_Value']               = '';
						$response ['Option_2_Name']                = '';
						$response ['Option_2_Value']               = '';
						$response ['Auction_Site']                 = ( '' !== $verbose_status ) ? $verbose_status : '';
						$response ['Buyer_ID']                     = '';
						$response ['Item_URL']                     = '';
						$response ['Closing_Date']                 = '';
						$response ['Escrow_ID']                    = '';
						$response ['Invoice_ID']                   = '';
						$response ['Reference_Txn_ID']             = '';
						$response ['Invoice_Number']               = '';
						$response ['Custom_Number']                = '';
						$response ['Quantity']                     = ( ! empty( $item_details[ $order_id ]['tot_qty'] ) ) ? $item_details[ $order_id ]['tot_qty'] : '1';
						$response ['Receipt_ID']                   = '';
						$response ['Balance']                      = '';
						$response ['Note']                         = ( ! empty( $order_detail['order_note'] ) ) ? $order_detail['order_note'] : '';
						$response ['Address_Line_1']               = ( ! empty( $order_items[ $order_id ]['_billing_address_1'] ) ) ? $order_items[ $order_id ]['_billing_address_1'] : '';
						$response ['Address_Line_2']               = ! empty( $order_items[ $order_id ]['_billing_address_2'] ) ? $order_items[ $order_id ]['_billing_address_2'] : '';
						$response ['Town_City']                    = ! empty( $order_items[ $order_id ]['_billing_city'] ) ? $order_items[ $order_id ]['_billing_city'] : '';
						$response ['State_Province']               = ! empty( $order_items[ $order_id ]['_billing_state'] ) ? $order_items[ $order_id ]['_billing_state'] : '';
						$response ['Zip_Postal_Code']              = ! empty( $order_items[ $order_id ]['_billing_postcode'] ) ? $order_items[ $order_id ]['_billing_postcode'] : '';
						$response ['Country']                      = ! empty( $order_items[ $order_id ]['_billing_country'] ) ? $order_items[ $order_id ]['_billing_country'] : '';
						$response ['Contact_Phone_Number']         = ! empty( $order_items[ $order_id ]['_billing_phone'] ) ? $order_items[ $order_id ]['_billing_phone'] : '';
						$response ['Subscription_ID']              = '';

						// coupon data.
						if ( ! empty( $item_details[ $order_id ]['coupons'] ) ) {
							foreach ( $item_details[ $order_id ]['coupons'] as $key => &$value ) {
								$coupon_data[ $key ]['amt'] = ( ! empty( $value['amt'] ) && is_numeric( $value['amt'] ) ) ? round( floatval( $value['amt'] ), 2 ) : 0.00;
								$value                      = ( ! empty( $coupon_data[ $key ] ) ) ? $coupon_data[ $key ] : $value;
							}
						}

						$order_items[ $order_id ]['coupons']                = ( ! empty( $item_details[ $order_id ]['coupons'] ) ) ? $item_details[ $order_id ]['coupons'] : array();
						$order_items[ $order_id ]['_shipping_method_title'] = ( ! empty( $item_details[ $order_id ]['_shipping_method_title'] ) ) ? $item_details[ $order_id ]['_shipping_method_title'] : '-';

						$order_items[ $order_id ]['subscriptions'] = array();

						if ( ! empty( $item_details[ $order_id ]['subscriptions'] ) ) {
							$sub_meta_data = array();

							foreach ( $item_details[ $order_id ]['subscriptions'] as $subid => $sub ) {

								$sub_meta_data[ $subid ] = array();

								if ( ! empty( $sub ) && is_array( $sub ) ) {
									foreach ( $sub as $key => $value ) {
										$sub_meta_data[ $subid ][ $key ] = maybe_unserialize( $value );
									}
								}
							}

							$order_items[ $order_id ]['subscriptions'] = $sub_meta_data;

							if ( ! empty( $sub_item_ids ) ) {

								$sub           = 0;
								$prod          = 0;
								$order_sub_ids = array();

								foreach ( $item_details[ $order_id ]['cart_items'] as $key => $value ) {
									if ( ! empty( $sub_item_ids[ $key ] ) ) {

										// For handling renewal orders in subscription meta.
										if ( ! empty( $order_items[ $order_id ]['_subscription_renewal'] ) ) {
											$sub_meta[ $sub_item_ids[ $key ] ]['is_renewal'] = 1;
										} else {
											if ( isset( $sub_meta[ $sub_item_ids[ $key ] ]['is_renewal'] ) ) {
												unset( $sub_meta[ $sub_item_ids[ $key ] ]['is_renewal'] );
											}
										}

										// For handling switched subscription orders in subscription meta.
										if ( ! empty( $order_items[ $order_id ]['_subscription_switch'] ) ) {

											$sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'] = ( ! empty( $sub_renewal_id[ $order_id ] ) ) ? $sub_renewal_id[ $order_id ] : '';
											$sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'] = ( empty( $sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'] ) && ! empty( $sub_parent_id[ $sub_item_ids[ $key ] ] ) ) ? $sub_parent_id[ $sub_item_ids[ $key ] ] : $sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'];
										} else {
											if ( isset( $sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'] ) ) {
												unset( $sub_meta[ $sub_item_ids[ $key ] ]['sub_switch'] );
											}
										}

										$sub ++;
									} else {
										$prod ++;
									}
								}

								// code for handling different subscription order conditions.
								if ( 1 === $sub && 0 === $prod ) {
									$response ['Type']            = 'Subscription Payment Received';
									$sub_id                       = array_keys( $item_details[ $order_id ]['subscriptions'] );
									$response ['Subscription_ID'] = ( ! empty( $sub_id ) ) ? $sub_id[0] : '';
									$sub_trans                    = true;
								} elseif ( ( $sub > 1 && 0 === $prod ) || ( $sub >= 1 && $prod > 0 ) ) {
									$response ['Type'] = 'Subscription Shopping Cart Payment Received';
								}
							}
						}

						if ( empty( $params['trash'] ) ) {

							$order_meta = array();
							foreach ( $order_items[ $order_id ] as $key => $value ) {
								$order_meta[ $key ] = maybe_unserialize( $value );
							}

							$response ['Raw_Data'] = wp_json_encode( $order_meta );
						} else {
							$response ['Raw_Data'] = '';
						}

						// Payment Title & Transaction id -- added from v2.6.
						$response ['Payment_Source']    = ! empty( $order_items[ $order_id ]['_payment_method'] ) ? $order_items[ $order_id ]['_payment_method'] : '';
						$response ['External_Trans_ID'] = ! empty( $order_items[ $order_id ]['_transaction_id'] ) ? $order_items[ $order_id ]['_transaction_id'] : '';

						// for older woo versions > 2.0.
						$response ['External_Trans_ID'] = ( empty( $response ['External_Trans_ID'] ) && ! empty( $order_items[ $order_id ]['Transaction ID'] ) ) ? $order_items[ $order_id ]['Transaction ID'] : $response ['External_Trans_ID'];

						// trans id for stripe.
						$response ['External_Trans_ID'] = ( ! empty( $response ['Payment_Source'] ) && ( 'stripe' === $response ['Payment_Source'] ) && ! empty( $order_items[ $order_id ]['_stripe_charge_id'] ) ) ? $order_items[ $order_id ]['_stripe_charge_id'] : $response ['External_Trans_ID'];

						// trans id for 2Checkout.
						$response ['External_Trans_ID'] = ( ! empty( $response ['Payment_Source'] ) && ( '2Checkout' === $response ['Payment_Source'] ) && ! empty( $order_items[ $order_id ]['two_checkout_sale_id'] ) ) ? $order_items[ $order_id ]['two_checkout_sale_id'] : $response ['External_Trans_ID'];

						// customer ip_address.
						$response ['IP_Address'] = ! empty( $order_items[ $order_id ]['_customer_ip_address'] ) ? $order_items[ $order_id ]['_customer_ip_address'] : '';

						$response ['Sub_Meta'] = wp_json_encode( json_decode( '{}' ) ); // for subscription meta.

						$cart_items = ( ! empty( $item_details[ $order_id ]['cart_items'] ) ) ? $item_details[ $order_id ]['cart_items'] : array();
						$sub        = ( ! empty( $item_details[ $order_id ]['subscriptions'] ) ) ? $item_details[ $order_id ]['subscriptions'] : array();

						if ( $this->is_hpos_enabled ) {
							$temp_order_id                             = $order_id;
							$response ['IP_Address']                   = ( ! empty( $order_detail['ip_address'] ) ) ? $order_detail['ip_address'] : '';
							$response ['Shipping_and_Handling_Amount'] = ( ! empty( $order_stats[ $temp_order_id ]['shipping_total'] ) && is_numeric( $order_stats[ $temp_order_id ]['shipping_total'] ) ) ? round( floatval( $order_stats[ $temp_order_id ]['shipping_total'] ), 2 ) : 0.00;
							$response ['Discount']                     = ( ! empty( $discounts[ $temp_order_id ]['total_discount'] ) && is_numeric( $discounts[ $temp_order_id ]['total_discount'] ) ) ? round( floatval( $discounts[ $temp_order_id ]['total_discount'] ), 2 ) : 0.00;

							if ( 'shop_order_refund' === $order_detail['type'] ) {
								$temp_order_id                             = ( isset( $order_detail['parent_id'] ) && $order_detail['parent_id'] ) ? $order_detail['parent_id'] : $order_id;
								$order_total                               = ( ! empty( $order_items[ $order_detail['id'] ]['_refund_amount'] ) && is_numeric( $order_items[ $order_detail['id'] ]['_refund_amount'] ) ) ? round( floatval( $order_items[ $order_detail['id'] ]['_refund_amount'] ), 2 ) : 0.00;
								$response ['Shipping_and_Handling_Amount'] = ( ! empty( $order_stats[ $order_detail['id'] ]['shipping_total'] ) && is_numeric( $order_stats[ $order_detail['id'] ]['shipping_total'] ) ) ? round( floatval( $order_stats[ $order_detail['id'] ]['shipping_total'] ), 2 ) : 0.00;
								$response ['Discount']                     = ( ! empty( $discounts[ $order_detail['id'] ]['total_discount'] ) && is_numeric( $discounts[ $order_detail['id'] ]['total_discount'] ) ) ? round( floatval( $discounts[ $order_detail['id'] ]['total_discount'] ), 2 ) : 0.00;
								if ( ! $response ['IP_Address'] ) {
									$parent_order            = array_filter(
										$results_order_details,
										function ( $item ) use ( $order_detail ) {
											return $item['id'] === $order_detail['parent_id'];
										}
									);
									$parent_order            = reset( $parent_order );
									$response ['IP_Address'] = ( ! empty( $parent_order['ip_address'] ) ) ? $parent_order['ip_address'] : '';
								}
							}
							$response ['Name']                 = ( ! empty( $billing_address[ $temp_order_id ]['first_name'] ) ) ? $billing_address[ $temp_order_id ]['first_name'] : '';
							$response ['Name']                .= ( ! empty( $billing_address[ $temp_order_id ]['last_name'] ) ) ? ' ' . $billing_address[ $temp_order_id ]['last_name'] : '';
							$response ['Currency']             = ( ! empty( $order_detail['currency'] ) ) ? $order_detail['currency'] : '';
							$response ['Gross']                = ( ! empty( $order_stats[ $temp_order_id ]['total_sales'] ) && is_numeric( $order_stats[ $temp_order_id ]['total_sales'] ) ) ? round( floatval( $order_stats[ $temp_order_id ]['total_sales'] ), 2 ) : 0.00;
							$response ['Net']                  = $response ['Gross'];
							$response ['From_Email_Address']   = ( ! empty( $billing_address[ $temp_order_id ]['billing_email'] ) ) ? $billing_address[ $temp_order_id ]['billing_email'] : null;
							$response ['Address_Line_1']       = ( ! empty( $billing_address[ $temp_order_id ]['address_1'] ) ) ? $billing_address[ $temp_order_id ]['address_1'] : '';
							$response ['Address_Line_2']       = ( ! empty( $billing_address[ $temp_order_id ]['address_2'] ) ) ? $billing_address[ $temp_order_id ]['address_2'] : '';
							$response ['Town_City']            = ( ! empty( $billing_address[ $temp_order_id ]['city'] ) ) ? $billing_address[ $temp_order_id ]['city'] : '';
							$response ['State_Province']       = ( ! empty( $billing_address[ $temp_order_id ]['state'] ) ) ? $billing_address[ $temp_order_id ]['state'] : '';
							$response ['Zip_Postal_Code']      = ( ! empty( $billing_address[ $temp_order_id ]['postcode'] ) ) ? $billing_address[ $temp_order_id ]['postcode'] : '';
							$response ['Country']              = ( ! empty( $billing_address[ $temp_order_id ]['country'] ) ) ? $billing_address[ $temp_order_id ]['country'] : '';
							$response ['Contact_Phone_Number'] = ( ! empty( $billing_address[ $temp_order_id ]['phone'] ) ) ? $billing_address[ $temp_order_id ]['phone'] : '';
							$response ['Payment_Source']       = ( ! empty( $order_detail['payment_method'] ) ) ? $order_detail['payment_method'] : '';
							$response ['External_Trans_ID']    = ( ! empty( $order_detail['transaction_id'] ) ) ? $order_detail['transaction_id'] : '';

						}

						if ( ( ! empty( $params['order_id'] ) && ( 'refunded' === $order_status_new || ! empty( $params ['refund_parent_id'] ) ) ) ||
								( empty( $params['order_id'] ) && 'shop_order_refund' === $order_detail['type'] ) ) {

							$refund_id = ''; // for handling manual refunds.

							if ( ! empty( $params ['refund_parent_id'] ) || 'shop_order_refund' === $order_detail['type'] ) {
								$refund_id = ( ! empty( $params ['refund_parent_id'] ) ) ? $params['order_id'] : $order_detail['id'];
							}

							$response ['Date']              = $order_detail['formatted_modified_gmt_date'];
							$response ['Time']              = $order_detail['formatted_modified_gmt_time'];
							$response ['Type']              = 'Refund';
							$response ['Status']            = 'Completed';
							$response ['Auction_Site']      = '';
							$response ['Item_Title']        = 'Unknown';
							$response ['Gross']             = - $order_total; // for handling partial & full refunds.
							$response ['Net']               = - $order_total;
							$response ['Transaction_ID']    = ( ( ! empty( $params ['refund_parent_id'] ) || 'shop_order_refund' === $order_detail['type'] ) && ! empty( $refund_id ) ) ? $order_id . '_R_' . $refund_id : $order_id . '_R';
							$response ['Reference_Txn_ID']  = $order_id;
							$response ['Raw_Data']          = '';
							$response ['Payment_Source']    = '';
							$response ['External_Trans_ID'] = ''; // for type refund transactions the same should be blank.

							if ( empty( $cart_items ) || 1 !== count( $cart_items ) ) {
								$transactions [] = $response;
								continue;
							}
						} elseif ( false === $sub_trans && empty( $params['order_id'] ) && 'refunded' === $order_status_new ) { // For sending parent transactions for refunded orders during data sync.
							$transactions [] = $response;
						}

						if ( false === $sub_trans && ( 'refunded' !== $order_status_new && empty( $params ['refund_parent_id'] ) && 'shop_order_refund' !== $order_detail['type'] ) ) { // for handling orders not having only 1 subscription.
							$transactions [] = $response;
						}

						// Code for line items.
						foreach ( $cart_items as $item_id => $cart_item ) {

							$order_item                = array();
							$order_item ['Type']       = ( false === $sub_trans && 'Refund' !== $response ['Type'] ) ? 'Shopping Cart Item' : $response ['Type'];
							$order_item ['Item_Title'] = ( ! empty( $cart_item['_product_id'] ) ) ? get_the_title( $cart_item['_product_id'] ) : $cart_item['product_name'];

							if ( ! empty( $cart_item['_variation_id'] ) ) {
								$order_item ['Item_ID'] = ( isset( $products_sku[ $cart_item['_variation_id'] ] ) ) ? $products_sku[ $cart_item['_variation_id'] ] : $cart_item['_variation_id'];
								$product_id             = $cart_item['_variation_id'];
							} else {
								$order_item ['Item_ID'] = ( isset( $products_sku[ $cart_item['_product_id'] ] ) ) ? $products_sku[ $cart_item['_product_id'] ] : $cart_item['_product_id'];
								$product_id             = $cart_item['_product_id'];
							}

							$order_item ['Gross']    = ( false === $sub_trans && 'Refund' !== $response ['Type'] && is_numeric( $cart_item['_line_total'] ) ) ? round( floatval( $cart_item['_line_total'] ), 2 ) : $response ['Gross'];
							$order_item ['Quantity'] = $cart_item['_qty'];

							// code to handle special case where order_total & Discount both are zero make the line_total = 0.
							if ( 0.00 === floatval( $response ['Discount'] ) && 0.00 === floatval( $response ['Gross'] ) ) {
								$order_item ['Gross'] = 0.00;
							}

							if ( isset( $variations[ $product_id ] ) ) {
								if ( 'attributes' !== $variations[ $product_id ][0]['option1_name'] ) {
									$order_item ['Option_1_Name']  = ( ! empty( $variations[ $product_id ][0]['option1_name'] ) ) ? $variations[ $product_id ][0]['option1_name'] : '';
									$order_item ['Option_1_Value'] = ( ! empty( $variations[ $product_id ][0]['option1_value'] ) ) ? $variations[ $product_id ][0]['option1_value'] : '';
									$order_item ['Option_2_Name']  = ( ! empty( $variations[ $product_id ][1]['option1_name'] ) ) ? $variations[ $product_id ][1]['option1_name'] : '';
									$order_item ['Option_2_Value'] = ( ! empty( $variations[ $product_id ][1]['option1_value'] ) ) ? $variations[ $product_id ][1]['option1_value'] : '';
								} else {
									$order_item ['Option_1_Name']  = ( ! empty( $variations[ $product_id ][0]['option1_name'] ) ) ? $variations[ $product_id ][0]['option1_name'] : '';
									$order_item ['Option_1_Value'] = ( ! empty( $variations[ $product_id ][0]['option1_value'] ) ) ? $variations[ $product_id ][0]['option1_value'] : '';
								}
							}

							$order_item ['Subscription_ID'] = ( ! empty( $sub_item_ids[ $item_id ] ) ) ? $sub_item_ids[ $item_id ] : ''; // Assigning the subscription id.

							if ( empty( $params['trash'] ) ) {
								$response ['Raw_Data'] = ( false === $sub_trans || 'Refund' === $response ['Type'] ) ? '' : $response ['Raw_Data'];
							} else {
								$response ['Raw_Data'] = '';
							}

							if ( ! empty( $sub_item_ids[ $item_id ] ) && empty( $params['trash'] ) ) { // for handling subscription meta.
								$response ['Sub_Meta'] = wp_json_encode( ( ! empty( $sub_meta[ $sub_item_ids[ $item_id ] ] ) ) ? $sub_meta[ $sub_item_ids[ $item_id ] ] : json_decode( '{}' ) );
							} else {
								$response ['Sub_Meta'] = wp_json_encode( json_decode( '{}' ) );
							}

							$response = array_merge( $response, $order_item );

							$transactions [] = $response;
						}
					}
				}
			}

			if ( empty( $params['order_id'] ) ) {

				if ( 0 === $start_limit ) { // Code for fetching the deleted transactions only in first batch.
					$results = $wpdb->get_col(
						$wpdb->prepare(
							"SELECT option_value 
	                                FROM {$wpdb->prefix}options
	                                WHERE (option_name LIKE %s 
	                                    AND convert_tz(FROM_UNIXTIME(SUBSTRING_INDEX(option_name,'_transient_wpc_deleted_empty_trash_',-1)),@@session.time_zone,'+00:00') BETWEEN %s AND %s)
	                                    OR (option_name LIKE %s
	                                    AND convert_tz(FROM_UNIXTIME(SUBSTRING_INDEX(option_name,'_transient_wpc_deleted_',-1)),@@session.time_zone,'+00:00') BETWEEN %s AND %s)",
							'%' . $wpdb->esc_like( '_transient_wpc_deleted_empty_trash_' ) . '%',
							$params['start_date'],
							$params['end_date'],
							'%' . $wpdb->esc_like( '_transient_wpc_deleted_' ) . '%',
							$params['start_date'],
							$params['end_date']
						)
					); // WPCS: cache ok, db call ok.

					if ( ! empty( $results ) && count( $results ) > 0 ) {
						foreach ( $results as $result ) {

							$deleted_trans = maybe_unserialize( $result );

							foreach ( $deleted_trans as $trans ) {
								$transactions[] = $this->validate_transaction( $trans );
							}
						}
					}
				}

				$order_count           = ( ! empty( $results_order_details ) && is_array( $results_order_details ) ) ? count( $results_order_details ) : 0;
				$data                  = ( ! empty( $transactions ) ) ? $transactions : array();
				$data                  = ( ! empty( $sub_transactions ) ) ? array_merge( $data, $sub_transactions ) : $data;
				$params[ $this->name ] = array(
					'count'            => $order_count,
					'last_start_limit' => $start_limit,
					'data'             => $data,
				);
			} else {
				$params['data'] = $transactions;
			}

			return $params;
		}

		/**
		 * Function to format the date.
		 *
		 * @param string $date Date string.
		 * @param string $format Format string.
		 *
		 * @return string    The formatted date.
		 */
		private function format_date_time( $date, $format = 'm/d/Y H:i:s' ) {
			$date_time = new DateTime( $date );
			if ( 'U' === $format || 'G' === $format ) {
				$time = $date_time->getTimestamp();
			} else {
				$time = $date_time->format( $format );
			}
			return $time;
		}

		/**
		 * Function to check HPOS enabled.
		 */
		public static function is_hpos_enabled() {
			return ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && is_callable( array( '\Automattic\WooCommerce\Utilities\OrderUtil', 'custom_orders_table_usage_is_enabled' ) ) ) ? \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() : false;
		}

		/**
		 * Function to declare WooCommerce HPOS compatibility.
		 */
		public function declare_hpos_compatibility() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'woocommerce-putler-connector/woocommerce-putler-connector.php', true );
			}
		}

	}
}
