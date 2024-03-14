<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE' ) ) {
	class VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE {
		/**
		 * Create table
		 */
		public static function create_table() {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';

			$query = "CREATE TABLE IF NOT EXISTS {$table} (
                             `id` bigint(20) NOT NULL AUTO_INCREMENT,
                             `order_id` bigint(20),
                             `tracking_number` VARCHAR(50) NOT NULL,
                             `status` VARCHAR(50),
                             `carrier_id` VARCHAR(50),
                             `carrier_name` VARCHAR(50)  ,
                             `shipping_country_code` VARCHAR(50),
                             `track_info` LONGTEXT,
                             `last_event` LONGTEXT,
                             `create_at` DATETIME,
                             `modified_at` DATETIME,
                             PRIMARY KEY  (`id`)
                             )";
			$wpdb->query( $query );
		}

		/**
		 * @param $id
		 * @param string $order_id
		 * @param bool $status
		 * @param bool $carrier_id
		 * @param bool $carrier_name
		 * @param bool $shipping_country_code
		 * @param bool $track_info
		 * @param bool $last_event
		 * @param bool $modified_at
		 */
		public static function update( $id, $order_id = '', $status = false, $carrier_id = false, $carrier_name = false, $shipping_country_code = false, $track_info = false, $last_event = false, $modified_at = false ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			if ( $modified_at === false ) {
				$modified_at = date( 'Y-m-d H:i:s' );
			}
			$update = array(
				'modified_at' => $modified_at
			);
			if ( $order_id ) {
				$update['order_id'] = $order_id;
			}
			if ( $status !== false ) {
				$update['status'] = $status;
			}
			if ( $carrier_id !== false ) {
				$update['carrier_id'] = $carrier_id;
			}
			if ( $carrier_name !== false ) {
				$update['carrier_name'] = $carrier_name;
			}
			if ( $shipping_country_code !== false ) {
				$update['shipping_country_code'] = $shipping_country_code;
			}

			if ( $track_info !== false ) {
				$update['track_info'] = $track_info;
			}
			if ( $last_event !== false ) {
				$update['last_event'] = $last_event;
			}
			$wpdb->update( $table,
				$update,
				array(
					'id' => $id,
				)
			);
		}

		/**Update tracking table
		 *
		 * @param $tracking_number
		 * @param bool $status
		 * @param string $carrier_id
		 * @param bool $carrier_name
		 * @param bool $shipping_country_code
		 * @param bool $track_info
		 * @param bool $last_event
		 *
		 * @return bool|false|int
		 */
		public static function update_by_tracking_number( $tracking_number, $status = false, $carrier_id = '', $carrier_name = false, $shipping_country_code = false, $track_info = false, $last_event = false ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$query = "UPDATE {$table} SET";
			$args  = array();
			$run   = false;
			if ( $status !== false ) {
				$run    = true;
				$query  .= " status = %s,";
				$args[] = $status;
			}
			if ( $track_info !== false ) {
				$run    = true;
				$query  .= " track_info = %s,";
				$args[] = $track_info;
			}
			if ( $last_event !== false ) {
				$run    = true;
				$query  .= " last_event = %s,";
				$args[] = $last_event;
			}
			if ( $shipping_country_code !== false ) {
				$run    = true;
				$query  .= " shipping_country_code = %s,";
				$args[] = $shipping_country_code;
			}

			if ( $carrier_name !== false ) {
				$run    = true;
				$query  .= " carrier_name = %s,";
				$args[] = $carrier_name;
			}
			if ( $run ) {
				$query  .= " modified_at = %s WHERE tracking_number = %s";
				$args[] = date( 'Y-m-d H:i:s' );
				$args[] = $tracking_number;
				if ( $carrier_id ) {
					$query  .= " AND carrier_id = %s";
					$args[] = $carrier_id;
				}

				return $wpdb->query( $wpdb->prepare( $query, $args ) );
			} else {
				return false;
			}
		}

		/**Insert data to table
		 *
		 * @param $order_id
		 * @param $tracking_number
		 * @param $status
		 * @param $carrier_id
		 * @param $carrier_name
		 * @param string $shipping_country_code
		 * @param string $track_info
		 * @param string $last_event
		 * @param bool $modified_at
		 *
		 * @return int
		 */
		public static function insert( $order_id, $tracking_number, $status, $carrier_id, $carrier_name, $shipping_country_code = '', $track_info = '', $last_event = '', $modified_at = false ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$now   = date( 'Y-m-d H:i:s' );
			if ( $modified_at === false ) {
				$modified_at = $now;
			}

			$wpdb->insert( $table,
				array(
					'order_id'              => $order_id,
					'tracking_number'       => $tracking_number,
					'status'                => $status,
					'carrier_id'            => $carrier_id,
					'carrier_name'          => $carrier_name,
					'shipping_country_code' => $shipping_country_code,
					'track_info'            => $track_info,
					'last_event'            => $last_event,
					'create_at'             => $now,
					'modified_at'           => $modified_at,
				),
				array(
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				)
			);
			$return = $wpdb->insert_id;

			return $return;
		}

		/**Delete row
		 *
		 * @param $id
		 *
		 * @return false|int
		 */
		public static function delete( $id ) {
			global $wpdb;
			$table  = $wpdb->prefix . 'wotv_woo_track_info';
			$delete = $wpdb->delete( $table,
				array(
					'id' => $id,
				),
				array(
					'%d',
				)
			);

			return $delete;
		}

		/**Get row
		 *
		 * @param $id
		 *
		 * @return array|null|object
		 */
		public static function get_row( $id ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$query = "SELECT * FROM {$table} WHERE id=%d";

			return $wpdb->get_row( $wpdb->prepare( $query, $id ), ARRAY_A );
		}

		/**Get tracking number from database
		 *
		 * @param $tracking_number
		 * @param string $carrier_id
		 * @param string $order_id
		 * @param string $email
		 *
		 * @return array|object|null
		 */
		public static function get_row_by_tracking_number( $tracking_number, $carrier_id = '', $order_id = '', $email = '' ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$args  = array( $tracking_number );
			if ( $email ) {
				if (get_option( 'woocommerce_feature_custom_order_tables_enabled' ) === 'yes' && get_option( 'woocommerce_custom_orders_table_data_sync_enabled' ) === 'no'){
					$query = "SELECT * FROM {$table} as woo_orders_tracking_track_info JOIN {$wpdb->prefix}wc_orders as woo_orders_tracking_wp_postmeta ON woo_orders_tracking_track_info.order_id=woo_orders_tracking_wp_postmeta.id WHERE tracking_number=%s AND woo_orders_tracking_wp_postmeta.billing_email=%s";
				}else {
					$query = "SELECT * FROM {$table} as woo_orders_tracking_track_info JOIN {$wpdb->prefix}postmeta as woo_orders_tracking_wp_postmeta ON woo_orders_tracking_track_info.order_id=woo_orders_tracking_wp_postmeta.post_id WHERE tracking_number=%s AND woo_orders_tracking_wp_postmeta.meta_key='_billing_email' AND woo_orders_tracking_wp_postmeta.meta_value=%s";
				}
				$args[] = $email;
			} else {
				$query = "SELECT * FROM {$table} WHERE tracking_number=%s";
			}
			if ( $order_id ) {
				if ( is_array( $order_id ) ) {
					if ( count( $order_id ) === 1 ) {
						$query  .= " AND order_id=%s";
						$args[] = $order_id[0];
					} else {
						$query .= " AND order_id IN (" . implode( ', ', array_fill( 0, count( $order_id ), '%s' ) ) . ")";
						foreach ( $order_id as $v ) {
							$args[] = $v;
						}
					}
				} else {
					$query  .= " AND order_id=%s";
					$args[] = $order_id;
				}
			}
			if ( $carrier_id ) {
				$query   .= " AND carrier_id=%s";
				$args[]  = $carrier_id;
				$results = $wpdb->get_results( $wpdb->prepare( $query, $args ), ARRAY_A );
				if ( count( $results ) ) {
					return $results[0];
				} else {
					return $results;
				}
			} else {
				$query   .= " GROUP BY carrier_id";
				$results = $wpdb->get_results( $wpdb->prepare( $query, $args ), ARRAY_A );
				if ( count( $results ) === 1 ) {
					return $results[0];
				} else {
					return $results;
				}
			}
		}

		public static function get_rows_by_tracking_number_carrier_pairs( $pairs ) {
			$results = array();
			if ( count( $pairs ) ) {
				global $wpdb;
				$table = $wpdb->prefix . 'wotv_woo_track_info';
				$where = array();
				$args  = array();
				$query = "SELECT * FROM {$table}";
				foreach ( $pairs as $pair ) {
					$where[] = 'tracking_number=%s AND carrier_id=%s';
					$args[]  = $pair['tracking_number'];
					$args[]  = $pair['carrier_slug'];
				}
				$query   .= ' WHERE ' . implode( ' OR ', $where );
				$results = $wpdb->get_results( $wpdb->prepare( $query, $args ), ARRAY_A );
			}

			return $results;
		}

		public static function get_rows_by( $tracking_number = '', $carrier_id = '', $order_id = '', $email = '', $status = array(), $exc_status = array() ) {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$where = array();
			$args  = array();
			if ( $tracking_number ) {
				$where[] = 'tracking_number=%s';
				$args[]  = $tracking_number;
			}
			if ( $email ) {
				if (get_option( 'woocommerce_feature_custom_order_tables_enabled' ) === 'yes' && get_option( 'woocommerce_custom_orders_table_data_sync_enabled' ) === 'no'){
					$query = "SELECT * FROM {$table} as woo_orders_tracking_track_info JOIN {$wpdb->prefix}wc_orders as woo_orders_tracking_wp_postmeta ON woo_orders_tracking_track_info.order_id=woo_orders_tracking_wp_postmeta.id";
					$where[]      = "woo_orders_tracking_wp_postmeta.billing_email = %s";
				}else {
					$query   = "SELECT * FROM {$table} as woo_orders_tracking_track_info JOIN {$wpdb->prefix}postmeta as woo_orders_tracking_wp_postmeta ON woo_orders_tracking_track_info.order_id=woo_orders_tracking_wp_postmeta.post_id";
					$where[] = "woo_orders_tracking_wp_postmeta.meta_key='_billing_email'";
					$where[] = 'woo_orders_tracking_wp_postmeta.meta_value=%s';
				}
				$args[]  = $email;
			} else {
				$query = "SELECT * FROM {$table}";
			}

			if ( $order_id ) {
				if ( is_array( $order_id ) ) {
					$where[] = "order_id IN (" . implode( ', ', array_fill( 0, count( $order_id ), '%s' ) ) . ")";
					foreach ( $order_id as $v ) {
						$args[] = $v;
					}
				} else {
					$where[] = "order_id=%s";
					$args[]  = $order_id;
				}
			}
			if ( $carrier_id ) {
				$where[] = "carrier_id=%s";
				$args[]  = $carrier_id;
			}
			$status_count = count( $status );
			if ( $status_count === 1 ) {
				$where[] = "status=%s";
				$args[]  = $status[0];
			} elseif ( $status_count > 1 ) {
				$where[] = "status IN (" . implode( ', ', array_fill( 0, count( $status ), '%s' ) ) . ")";
				foreach ( $status as $st ) {
					$args[] = $st;
				}
			} else {
				$exc_status_count = count( $exc_status );
				if ( $exc_status_count === 1 ) {
					$where[] = "status!=%s";
					$args[]  = $exc_status[0];
				} elseif ( $exc_status_count > 1 ) {
					$where[] = "status NOT IN (" . implode( ', ', array_fill( 0, count( $exc_status ), '%s' ) ) . ")";
					foreach ( $exc_status as $st ) {
						$args[] = $st;
					}
				}
			}
			if ( count( $where ) ) {
				$query .= ' WHERE ' . implode( ' AND ', $where );
			}
			$results = $wpdb->get_results( $wpdb->prepare( $query, $args ), ARRAY_A );

			return $results;
		}

		/**Get rows
		 *
		 * @param int $limit
		 * @param int $offset
		 * @param bool $count
		 *
		 * @return array|null|object|string
		 */
		public static function get_rows( $limit = 0, $offset = 0, $count = false ) {
			global $wpdb;
			$table  = $wpdb->prefix . 'wotv_woo_track_info';
			$select = '*';
			if ( $count ) {
				$select = 'count(*)';
				$query  = "SELECT {$select} FROM {$table}";

				return $wpdb->get_var( $query );
			} else {
				$query = "SELECT {$select} FROM {$table}";
				if ( $limit ) {
					$query .= " LIMIT {$offset},{$limit}";
				}

				return $wpdb->get_results( $query, ARRAY_A );
			}
		}

		public static function get_existing_carriers() {
			global $wpdb;
			$table = $wpdb->prefix . 'wotv_woo_track_info';
			$query = "SELECT DISTINCT `carrier_id` from {$table}";

			return $wpdb->get_results( $query, ARRAY_A );
		}


		/**
		 * @return array
		 */
		public static function get_cols() {
			return array(
				'id'                    => '',
				'order_id'              => '',
				'tracking_number'       => '',
				'status'                => '',
				'carrier_id'            => '',
				'carrier_name'          => '',
				'shipping_country_code' => '',
				'track_info'            => '',
				'last_event'            => '',
				'create_at'             => '',
				'modified_at'           => '',
			);
		}
	}
}