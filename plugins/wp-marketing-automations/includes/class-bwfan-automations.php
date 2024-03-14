<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
#[AllowDynamicProperties]
class BWFAN_Automations {
	private static $ins = null;

	public $automation_id = null;
	public $return_all = false;
	public $per_page = 10;
	public $automation_transient_data = [];
	public $toggle_automation = false;
	public $current_automation_id = null;
	public $current_automation_sync_state = 'data-sync-state="off"';
	private $automation_details = null;

	public function __construct() {
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/** return single automation or all automation json data
	 *
	 * @param null $automation_id
	 *
	 * @return false|mixed|string|void
	 */
	public static function get_json( $automation_id = null, $version = 1 ) {
		global $wpdb;

		$automation_json = '';

		if ( empty( $automation_id ) ) {

			$automation_table = $wpdb->prefix . 'bwfan_automations';
			$query            = "SELECT ID FROM $automation_table WHERE v = $version";
			$all_automations  = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL

			if ( empty( $all_automations ) ) {
				return;
			}

			$automations_data = array();
			foreach ( $all_automations as $key => $auto ) {
				$automation_meta                  = BWFAN_Core()->automations->get_automation_data_meta( $auto['ID'] );
				$automations_data[ $key ]['data'] = array(
					'source' => $automation_meta['source'],
					'event'  => $automation_meta['event'],
					'start'  => $automation_meta['start'],
					'v'      => $automation_meta['v']
				);
				$automations_data[ $key ]['meta'] = array(
					'title'           => isset( $automation_meta['title'] ) ? $automation_meta['title'] : '',
					'event_meta'      => isset( $automation_meta['event_meta'] ) ? $automation_meta['event_meta'] : '',
					'actions'         => isset( $automation_meta['actions'] ) ? $automation_meta['actions'] : '',
					'a_track_id'      => 0,
					'condition'       => isset( $automation_meta['condition'] ) ? $automation_meta['condition'] : '',
					'run_count'       => 0,
					'ui'              => isset( $automation_meta['ui'] ) && ! empty( $automation_meta['ui'] ) ? $automation_meta['ui'] : '',
					'requires_update' => isset( $automation_meta['requires_update'] ) ? $automation_meta['requires_update'] : '',
					'uiData'          => isset( $automation_meta['uiData'] ) ? $automation_meta['uiData'] : '',
				);

				if ( 2 === absint( $automation_meta['v'] ) ) {
					if ( isset( $automation_meta['steps'] ) && is_array( $automation_meta['steps'] ) ) {
						$automations_data[ $key ]['step_data'] = self::get_steps_data( $automation_meta['steps'] );
					}
					$automations_data[ $key ]['meta']['steps']                = isset( $automation_meta['steps'] ) ? $automation_meta['steps'] : '';
					$automations_data[ $key ]['meta']['links']                = isset( $automation_meta['links'] ) ? $automation_meta['links'] : '';
					$automations_data[ $key ]['meta']['count']                = isset( $automation_meta['count'] ) ? $automation_meta['count'] : 0;
					$automations_data[ $key ]['meta']['requires_update']      = 0;
					$automations_data[ $key ]['meta']['step_iteration_array'] = isset( $automation_meta['step_iteration_array'] ) ? $automation_meta['step_iteration_array'] : '';
				}
			}

			$automation_json = wp_json_encode( $automations_data );
		} else {
			$automation_meta            = BWFAN_Core()->automations->get_automation_data_meta( $automation_id );
			$json_data_array            = array(
				'data' => array(),
				'meta' => array(),
			);
			$json_data_array[0]['data'] = array(
				'source' => $automation_meta['source'],
				'event'  => $automation_meta['event'],
				'start'  => $automation_meta['start'],
				'v'      => $automation_meta['v']
			);
			$json_data_array[0]['meta'] = array(
				'title'           => isset( $automation_meta['title'] ) ? $automation_meta['title'] : '',
				'event_meta'      => isset( $automation_meta['event_meta'] ) ? $automation_meta['event_meta'] : '',
				'actions'         => isset( $automation_meta['actions'] ) ? $automation_meta['actions'] : '',
				'a_track_id'      => 0,
				'condition'       => isset( $automation_meta['condition'] ) ? $automation_meta['condition'] : '',
				'run_count'       => 0,
				'ui'              => isset( $automation_meta['ui'] ) ? $automation_meta['ui'] : '',
				'requires_update' => isset( $automation_meta['requires_update'] ) ? $automation_meta['requires_update'] : '',
				'uiData'          => isset( $automation_meta['uiData'] ) ? $automation_meta['uiData'] : '',
			);

			if ( 2 === absint( $automation_meta['v'] ) ) {
				if ( isset( $automation_meta['steps'] ) && is_array( $automation_meta['steps'] ) ) {
					$json_data_array[0]['step_data'] = self::get_steps_data( $automation_meta['steps'] );
				}

				$json_data_array[0]['meta']['steps']                = isset( $automation_meta['steps'] ) ? $automation_meta['steps'] : '';
				$json_data_array[0]['meta']['links']                = isset( $automation_meta['links'] ) ? $automation_meta['links'] : '';
				$json_data_array[0]['meta']['count']                = isset( $automation_meta['count'] ) ? $automation_meta['count'] : '';
				$json_data_array[0]['meta']['requires_update']      = 0;
				$json_data_array[0]['meta']['step_iteration_array'] = isset( $automation_meta['step_iteration_array'] ) ? $automation_meta['step_iteration_array'] : '';
			}

			$automation_json = wp_json_encode( $json_data_array );
		}

		return $automation_json;
	}

	/** Made the data for recent recovered cart in dashboard screen.
	 * @return array
	 */
	public static function get_recovered_carts( $offset, $limit ) {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return array();
		}

		global $wpdb;
		$where         = '';
		$post_statuses = apply_filters( 'bwfan_recovered_cart_excluded_statuses', array( 'wc-pending', 'wc-failed', 'wc-cancelled', 'wc-refunded', 'trash', 'draft' ) );
		$post_status   = '(';
		foreach ( $post_statuses as $status ) {
			$post_status .= "'" . $status . "',";
		}
		$where       .= " AND m.meta_value > 0 ";
		$post_status .= "'')";

		if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
			$query           = $wpdb->prepare( "SELECT p.id FROM {$wpdb->prefix}wc_orders as p LEFT JOIN {$wpdb->prefix}wc_orders_meta as m ON p.id = m.order_id WHERE p.type = %s AND p.status NOT IN $post_status AND m.meta_key = %s $where ORDER BY p.date_updated_gmt DESC LIMIT $offset,$limit", 'shop_order', '_bwfan_ab_cart_recovered_a_id' );
			$recovered_carts = $wpdb->get_results( $query, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL
		} else {
			$query           = $wpdb->prepare( "SELECT p.ID as id FROM {$wpdb->prefix}posts as p LEFT JOIN {$wpdb->prefix}postmeta as m ON p.ID = m.post_id WHERE p.post_type = %s AND p.post_status NOT IN $post_status AND m.meta_key = %s $where ORDER BY p.post_modified DESC LIMIT $offset,$limit", 'shop_order', '_bwfan_ab_cart_recovered_a_id' );
			$recovered_carts = $wpdb->get_results( $query, ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL
		}
		if ( empty( $recovered_carts ) ) {
			return array();
		}

		$found_posts = array();
		$items       = array();
		foreach ( $recovered_carts as $recovered_cart ) {
			$items[] = wc_get_order( $recovered_cart['id'] );
		}

		$found_posts['items'] = $items;
		if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
			$found_posts['total_record'] = $wpdb->get_var( $wpdb->prepare( "SELECT count(p.id) as total FROM {$wpdb->prefix}wc_orders as p LEFT JOIN {$wpdb->prefix}wc_orders_meta as m ON p.id = m.order_id WHERE p.type = %s AND p.status NOT IN $post_status AND m.meta_key = %s $where ORDER BY p.date_updated_gmt DESC LIMIT $offset,$limit", 'shop_order', '_bwfan_ab_cart_recovered_a_id' ) );//phpcs:ignore WordPress.DB.PreparedSQL
			if ( ! empty( $found_posts['total_record'] ) && 0 !== $found_posts['total_record'] ) {
				return $found_posts;
			}
		}

		$found_posts['total_record'] = $wpdb->get_var( $wpdb->prepare( "SELECT count(p.ID) as total FROM {$wpdb->prefix}posts as p LEFT JOIN {$wpdb->prefix}postmeta as m ON p.ID = m.post_id WHERE p.post_type = %s AND p.post_status NOT IN $post_status AND m.meta_key = %s $where ORDER BY p.post_modified DESC LIMIT $offset,$limit", 'shop_order', '_bwfan_ab_cart_recovered_a_id' ) );//phpcs:ignore WordPress.DB.PreparedSQL

		return $found_posts;
	}

	public static function get_recent_abandoned() {
		global $wpdb;
		$abandoned_table = $wpdb->prefix . 'bwfan_abandonedcarts';
		$contact_table   = $wpdb->prefix . 'bwf_contact';

		$query = "SELECT abandon.email,COALESCE(abandon.checkout_data,'')as checkout_data, abandon.total as revenue, abandon.currency as currency, COALESCE(con.id, 0) as id, COALESCE(con.f_name, '') as f_name, COALESCE(con.l_name, '') as l_name from $abandoned_table as abandon LEFT JOIN $contact_table as con ON abandon.email = con.email ORDER BY abandon.ID DESC LIMIT 5 OFFSET 0";

		$result = $wpdb->get_results( $query );
		foreach ( $result as $key => $recent_abandoned ) {
			if ( ! isset( $recent_abandoned->currency ) ) {
				continue;
			}
			$result[ $key ]->currency = BWFAN_Automations::get_currency( $recent_abandoned->currency );
		}

		return $result;
	}

	/** get the currency details
	 *
	 * @param $currency
	 *
	 * @return array
	 */
	public static function get_currency( $currency ) {
		return [
			'code'              => ! empty( $currency ) ? $currency : get_option( 'woocommerce_currency' ),
			'precision'         => wc_get_price_decimals(),
			'symbol'            => html_entity_decode( get_woocommerce_currency_symbol( $currency ) ),
			'symbolPosition'    => get_option( 'woocommerce_currency_pos' ),
			'decimalSeparator'  => wc_get_price_decimal_separator(),
			'thousandSeparator' => wc_get_price_thousand_separator(),
			'priceFormat'       => html_entity_decode( get_woocommerce_price_format() ),
		];
	}

	/**
	 * Return automation id
	 * @return null
	 */
	public function get_automation_id() {
		return $this->automation_id;
	}

	/**
	 * Set automation id
	 *
	 * @param $automation_id
	 */
	public function set_automation_id( $automation_id ) {
		$this->automation_id = $automation_id;
	}

	/**
	 * Return automation details
	 * @return null
	 */
	public function get_automation_details() {
		return $this->automation_details;
	}

	/**
	 * Set automation details
	 */
	public function set_automation_details() {
		$this->automation_details = $this->get_automation_data_meta( $this->automation_id );
	}

	public function get_automation_data_meta( $automation_id ) {
		$data = BWFAN_Model_Automations::get( $automation_id );
		if ( ! is_array( $data ) || 0 === count( $data ) ) {
			return [];
		}
		$meta = BWFAN_Model_Automationmeta::get_automation_meta( $automation_id );
		if ( 2 === absint( $data['v'] ) && isset( $meta['title'] ) ) {
			unset( $meta['title'] );
		}

		return array_merge( $data, $meta );
	}

	public function get_active_automations_for_event( $event_slug, $v = 1 ) {
		$automations        = [];
		$active_automations = $this->get_active_automations( $v, $event_slug );
		foreach ( $active_automations as $automation_id => $automation ) {
			$automations[ $automation_id ] = $automation;
		}

		return $automations;
	}

	/**
	 * Get active automations by version. Default v1
	 *
	 * @param int $v
	 * @param string $event_slug
	 *
	 * @return array
	 */
	public function get_active_automations( $v = 2, $event_slug = '' ) {
		global $wpdb;
		$v   = ( 2 === absint( $v ) ) ? 2 : 1;
		$key = ( 2 === $v ) ? 'bwfan_active_automations_v2' : 'bwfan_active_automations';
		$key = empty( $event_slug ) ? $key : $key . '_' . $event_slug;

		$core_cache_obj     = WooFunnels_Cache::get_instance();
		$active_automations = $core_cache_obj->get_cache( $key, 'autonami' );
		if ( false === $active_automations ) {
			$query = $wpdb->prepare( 'Select * FROM {table_name} WHERE status = 1 AND v = %d', $v );
			if ( ! empty( $event_slug ) ) {
				$query .= $wpdb->prepare( ' AND event = %s', $event_slug );
			}
			$active_automations = BWFAN_Model_Automations::get_results( $query );
			$active_automations = $this->filter_automations( $active_automations, $v );

			$core_cache_obj->set_cache( $key, $active_automations, 'autonami' );
		}

		$final_automation_data = [];
		if ( 0 === count( $active_automations ) ) {
			return $final_automation_data;
		}

		foreach ( $active_automations as $automation ) {
			$id                           = $automation['ID'];
			$final_automation_data[ $id ] = [
				'id'     => $id,
				'source' => $automation['source'],
				'event'  => $automation['event'],
				'meta'   => BWFAN_Model_Automationmeta::get_automation_meta( $id ),
			];

			if ( 2 === $v ) {
				$final_automation_data[ $id ]['version'] = isset( $automation['v'] ) ? $automation['v'] : 1;
				$final_automation_data[ $id ]['start']   = isset( $automation['start'] ) ? $automation['start'] : 0;
				$final_automation_data[ $id ]['goal']    = isset( $automation['benchmark'] ) ? json_decode( $automation['benchmark'], true ) : [];
			}
		}

		return $final_automation_data;
	}

	/**
	 * Filter v1 automations in case let automation active but stop tasks creation
	 *
	 * @param $automations
	 * @param $version
	 *
	 * @return mixed
	 */
	public function filter_automations( $automations, $version ) {
		if ( 1 !== intval( $version ) ) {
			return $automations;
		}

		$v1_automations_avoid = apply_filters( 'bwfan_avoid_v1_automations', self::get_migrated_automations() );
		if ( empty( $v1_automations_avoid ) ) {
			return $automations;
		}

		$automations = array_filter( $automations, function ( $automation ) use ( $v1_automations_avoid ) {
			return ! ( in_array( $automation['ID'], $v1_automations_avoid ) );
		} );

		return $automations;
	}

	public function create_automation( $title = '' ) {
		$post = [
			'status' => 2,
		];

		if ( empty( $title ) ) {
			$title = __( '(No title)', 'wp-marketing-automations' );
		}

		BWFAN_Model_Automations::insert( $post );
		$automation_id = BWFAN_Model_Automations::insert_id();

		if ( 0 === $automation_id || is_wp_error( $automation_id ) ) {
			return false;
		}

		$meta = [
			'bwfan_automation_id' => $automation_id,
		];

		$meta['meta_key']   = 'title';
		$meta['meta_value'] = $title;
		BWFAN_Model_Automationmeta::insert( $meta );

		$meta['meta_key']   = 'c_date';
		$meta['meta_value'] = current_time( 'mysql', 1 );
		BWFAN_Model_Automationmeta::insert( $meta );

		$meta['meta_key']   = 'm_date';
		$meta['meta_value'] = current_time( 'mysql', 1 );
		BWFAN_Model_Automationmeta::insert( $meta );

		$meta['meta_key']   = 'requires_update';
		$meta['meta_value'] = 1;
		BWFAN_Model_Automationmeta::insert( $meta );

		do_action( 'bwfan_automation_saved', $automation_id );

		return $automation_id;
	}

	/**
	 * Delete automations from DB.
	 *
	 * @param $automation_ids
	 */
	public function delete_automation( $automation_ids ) {
		global $wpdb;
		$automation_count      = count( $automation_ids );
		$string_placeholders   = array_fill( 0, $automation_count, '%s' );
		$prepared_placeholders = implode( ', ', $string_placeholders );
		$sql_query             = "Delete FROM {table_name} WHERE ID IN ($prepared_placeholders)";
		$sql_query             = $wpdb->prepare( $sql_query, $automation_ids ); // WPCS: unprepared SQL OK
		BWFAN_Model_Automations::delete_multiple( $sql_query );
	}

	/**
	 * Delete automation meta from DB.
	 *
	 * @param $automation_ids
	 */
	public function delete_automationmeta( $automation_ids ) {
		global $wpdb;
		$automation_count      = count( $automation_ids );
		$string_placeholders   = array_fill( 0, $automation_count, '%s' );
		$prepared_placeholders = implode( ', ', $string_placeholders );
		$sql_query             = "Delete FROM {table_name} WHERE bwfan_automation_id IN ($prepared_placeholders)";
		$sql_query             = $wpdb->prepare( $sql_query, $automation_ids ); // WPCS: unprepared SQL OK
		BWFAN_Model_Automationmeta::delete_multiple( $sql_query );
	}

	/**
	 * Get all the unique actions which are present in a single automation.
	 *
	 * @param $all_actions
	 *
	 * @return array
	 */
	public function get_unique_automation_actions( $all_actions ) {
		$unique_actions = [];
		if ( ! is_array( $all_actions ) || count( $all_actions ) === 0 ) {
			return $unique_actions;
		}

		foreach ( $all_actions as $value1 ) {
			foreach ( $value1 as $value2 ) {
				if ( isset( $value2['action_slug'] ) && $value2['integration_slug'] ) {
					$unique_actions[ $value2['action_slug'] ] = $value2['integration_slug'];
				}
			}
		}

		return $unique_actions;
	}

	/**
	 * Return the group_id and action_id of all the actions made in a single automation.
	 *
	 * @param $all_actions
	 *
	 * @return array
	 */
	public function get_automation_actions_indexes( $all_actions ) {
		$unique_actions = [];
		foreach ( $all_actions as $row_index => $row_actions ) {
			foreach ( $row_actions as $action_index => $action_details ) {
				if ( isset( $unique_actions[ $action_details['action_slug'] ] ) && is_array( $unique_actions[ $action_details['action_slug'] ] ) ) {
					array_push( $unique_actions[ $action_details['action_slug'] ], $row_index . '_' . $action_index );
				} else {
					$unique_actions[ $action_details['action_slug'] ] = array( $row_index . '_' . $action_index );
				}
			}
		}

		return $unique_actions;
	}

	/**
	 * Return all the automations
	 * @return array
	 */
	public function get_all_automations( $no_limit = null, $return_all = false, $v = 1 ) {
		global $wpdb;

		$offset = 0;
		if ( class_exists( 'BWFAN_Post_Table' ) ) {
			$this->per_page = BWFAN_Post_Table::$per_page;
			$offset         = ( BWFAN_Post_Table::$current_page - 1 ) * $this->per_page;
		}

		$query = "SELECT * FROM {table_name} WHERE v = $v ORDER BY ID DESC";

		if ( is_null( $no_limit ) && ( false === $this->return_all && false === $return_all ) ) {
			$query = $wpdb->prepare( 'SELECT * FROM {table_name} WHERE v = %d ORDER BY ID DESC LIMIT %d OFFSET %d', $v, $this->per_page, $offset );

			if ( isset( $_GET['status'] ) && 'all' !== sanitize_text_field( $_GET['status'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
				$status = sanitize_text_field( $_GET['status'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
				$status = ( 'active' === $status ) ? 1 : 2;
				$query  = $wpdb->prepare( 'SELECT * FROM {table_name} WHERE status = %d AND v = %d ORDER BY ID DESC', $status, $v );
			}
		} elseif ( $this->return_all || $return_all ) {
			$query = "SELECT * FROM {table_name} WHERE v = $v ORDER BY ID DESC";
		}

		$core_cache_obj = WooFunnels_Cache::get_instance();

		$all_automations = $core_cache_obj->get_cache( md5( $query ), 'fka-automations' );
		if ( false === $all_automations ) {
			$all_automations = BWFAN_Model_Automations::get_results( $query );
			$core_cache_obj->set_cache( md5( $query ), $all_automations, 'fka-automations' );
		}

		if ( empty( $all_automations ) || ! is_array( $all_automations ) ) {
			return [];
		}

		$final_automation_data = [];
		foreach ( $all_automations as $automation ) {
			$id                           = $automation['ID'];
			$final_automation_data[ $id ] = [
				'id'       => $id,
				'source'   => $automation['source'],
				'event'    => $automation['event'],
				'status'   => $automation['status'],
				'priority' => $automation['priority'],
				'title'    => ( 2 === absint( $v ) && isset( $automation['title'] ) ) ? $automation['title'] : '',
				'meta'     => BWFAN_Model_Automationmeta::get_automation_meta( $id ),
			];
		}

		return $final_automation_data;
	}

	/**
	 * In date time firstly the timezone offset is added to the store time and the store time is set. The UTC 0 time is saved in db
	 *
	 * @param $hours
	 * @param $minutes
	 *
	 * @return int
	 * @throws Exception
	 */
	public function get_automation_execution_time( $hours, $minutes ) {
		$date = new DateTime();
		$date->modify( '+' . BWFAN_Common::get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );
		$date->setTime( $hours, $minutes, 0 );
		$date->modify( '-' . BWFAN_Common::get_timezone_offset() * HOUR_IN_SECONDS . ' seconds' );

		return $date->getTimestamp();
	}

	/**
	 * Returns all the migration's status of the automations.
	 * sync_status = 1 denotes active migrations
	 * sync_status = 2 denotes completed migrations
	 *
	 * @param $status
	 * @param $all_automations
	 *
	 * @return array
	 */

	/**
	 * Removing unnecessary html from the db saved data so that it doesn't break the json.
	 *
	 * @param $db_saved_value
	 *
	 * @return array|string
	 */
	public function get_filtered_automation_saved_data( $db_saved_value ) {
		$db_saved_value_filtered = '';

		if ( ! is_array( $db_saved_value ) || count( $db_saved_value ) === 0 ) {
			return $db_saved_value_filtered;
		}

		$all_actions = BWFAN_Core()->integration->get_actions();
		foreach ( $db_saved_value as $group_id => $group_actions ) {
			foreach ( $group_actions as $key1 => $value1 ) {
				if ( isset( $value1['integration_slug'] ) && isset( $all_actions[ $value1['action_slug'] ] ) && $all_actions[ $value1['action_slug'] ]->is_editor_supported() ) {
					unset( $db_saved_value[ $group_id ][ $key1 ]['data']['body'] );
				}
			}
		}

		$db_saved_value_filtered = $db_saved_value;

		return $db_saved_value_filtered;
	}

	/**
	 * Get all the merge tags from all actions from a single automation.
	 *
	 * @param $automation_data
	 *
	 * @return array
	 */
	public function get_merge_tags_from_automation_posted_data( $automation_data ) {
		$all_section_merge_tags = array();
		foreach ( $automation_data as $group_id => $single_section ) {
			$data_value = ( isset( $single_section['data'] ) ) ? $single_section['data'] : array();
			if ( ! is_array( $data_value ) || count( $data_value ) === 0 ) {
				$all_section_merge_tags[ $group_id ] = BWFAN_Common::get_merge_tags_from_text( $data_value );
				continue;
			}

			$merge_tags = array();
			foreach ( $data_value as $value2 ) {
				if ( ! is_array( $value2 ) || count( $value2 ) === 0 ) {
					$inner_merge_tags = BWFAN_Common::get_merge_tags_from_text( $value2 );
					if ( is_array( $inner_merge_tags ) && count( $inner_merge_tags ) > 0 ) {
						$merge_tags = array_merge( $merge_tags, $inner_merge_tags );
					}
					$all_section_merge_tags[ $group_id ] = $merge_tags;
					continue;
				}

				foreach ( $value2 as $value3 ) {
					if ( ! is_array( $value3 ) || count( $value3 ) === 0 ) {
						$inner_merge_tags = BWFAN_Common::get_merge_tags_from_text( $value3 );
						if ( is_array( $inner_merge_tags ) && count( $inner_merge_tags ) > 0 ) {
							$merge_tags = array_merge( $merge_tags, $inner_merge_tags );
						}
						continue;
					}

					foreach ( $value3 as $value4 ) {
						$sub_inner_merge_tags = BWFAN_Common::get_merge_tags_from_text( $value4 );
						if ( is_array( $sub_inner_merge_tags ) && count( $sub_inner_merge_tags ) > 0 ) {
							$merge_tags = array_merge( $merge_tags, $sub_inner_merge_tags );
						}
					}
				}
				$all_section_merge_tags[ $group_id ] = $merge_tags;

			}
		}

		return $all_section_merge_tags;
	}

	/**
	 * Increase the automation run count
	 *
	 * @param $automation_id
	 * @param bool $increment
	 * @param null $automation_meta
	 */
	public function update_automation_run_count( $automation_id ) {
		$run_count = BWFAN_Model_Automationmeta::get_meta( $automation_id, 'run_count' );
		$update    = false;

		if ( ! empty( $run_count ) ) {
			$update = true;
		} else {
			$run_count = 0;
		}
		$run_count = intval( $run_count ) + 1;

		if ( $update ) {
			$meta_data               = array();
			$meta_data['meta_value'] = $run_count;
			$where                   = array(
				'bwfan_automation_id' => $automation_id,
				'meta_key'            => 'run_count',
			);
			BWFAN_Model_Automationmeta::update( $meta_data, $where );
		} else {
			$meta_data                        = array();
			$meta_data['bwfan_automation_id'] = $automation_id;
			$meta_data['meta_key']            = 'run_count';
			$meta_data['meta_value']          = $run_count;
			BWFAN_Model_Automationmeta::insert( $meta_data );
		}
	}

	/** duplicate automations using automation_id
	 *
	 * @param $automation_id
	 */
	public function duplicate( $automation_id ) {

		$automation_meta = BWFAN_Core()->automations->get_automation_data_meta( $automation_id );

		if ( empty( $automation_meta ) ) {
			return false;
		}

		$post              = array();
		$post['status']    = 2;
		$post['source']    = isset( $automation_meta['source'] ) ? $automation_meta['source'] : '';
		$post['event']     = isset( $automation_meta['event'] ) ? $automation_meta['event'] : '';
		$post['priority']  = 0;
		$post['v']         = isset( $automation_meta['v'] ) ? $automation_meta['v'] : 1;
		$post['title']     = isset( $automation_meta['title'] ) ? $automation_meta['title'] . ' (Copy) ' : '';
		$post['benchmark'] = isset( $automation_meta['benchmark'] ) ? $automation_meta['benchmark'] : '';

		BWFAN_Model_Automations::insert( $post );
		$automation_id = BWFAN_Model_Automations::insert_id();
		if ( 0 === $automation_id || is_wp_error( $automation_id ) ) {
			wp_send_json( [ 'status' => 0 ] );
		}

		BWFAN_Core()->automations->set_automation_id( $automation_id );
		BWFAN_Core()->automations->set_automation_data( 'status', $post['status'] );

		/** Unique Keys for Webhook Received Events */
		if ( isset( $automation_meta['event_meta']['bwfan_unique_key'] ) ) {
			$automation_meta['event_meta']['bwfan_unique_key'] = md5( uniqid( time(), true ) );
		}

		$post['meta'] = array(
			'title'           => isset( $automation_meta['title'] ) ? $automation_meta['title'] : '',
			'event_meta'      => isset( $automation_meta['event_meta'] ) ? $automation_meta['event_meta'] : '',
			'actions'         => isset( $automation_meta['actions'] ) ? $automation_meta['actions'] : '',
			'a_track_id'      => 0,
			'condition'       => isset( $automation_meta['condition'] ) ? $automation_meta['condition'] : '',
			'run_count'       => 0,
			'ui'              => isset( $automation_meta['ui'] ) ? $automation_meta['ui'] : '',
			'requires_update' => isset( $automation_meta['requires_update'] ) ? $automation_meta['requires_update'] : '',
			'uiData'          => isset( $automation_meta['uiData'] ) ? $automation_meta['uiData'] : '',
		);

		if ( isset( $automation_meta['v'] ) && 2 === absint( $automation_meta['v'] ) ) {
			$post                            = [];
			$post['meta']['steps']           = isset( $automation_meta['steps'] ) ? $automation_meta['steps'] : [];
			$post['meta']['links']           = isset( $automation_meta['links'] ) ? $automation_meta['links'] : [];
			$post['meta']['count']           = isset( $automation_meta['count'] ) ? $automation_meta['count'] : 0;
			$post['meta']['requires_update'] = isset( $automation_meta['requires_update'] ) ? $automation_meta['requires_update'] : '';
			$post['meta']['event_meta']      = isset( $automation_meta['event_meta'] ) ? $automation_meta['event_meta'] : '';

			/**Get all steps data for create duplicate steps */
			$steps_data = self::get_steps_data( $post['meta']['steps'] );
		}
		$links = isset( $post['meta']['links'] ) ? $post['meta']['links'] : [];
		foreach ( $post['meta'] as $key => $auto_meta ) {
			if ( 'steps' === $key ) {
				$start_node_id = self::get_start_node_id( $links );

				$auto_meta = $this->get_prepared_steps( $steps_data, $auto_meta, $automation_id, $start_node_id );
			}

			if ( is_array( $auto_meta ) ) {
				$auto_meta = maybe_serialize( $auto_meta );
			}
			$meta                        = array();
			$meta['bwfan_automation_id'] = $automation_id;
			$meta['meta_key']            = $key;
			$meta['meta_value']          = $auto_meta;
			BWFAN_Model_Automationmeta::insert( $meta );
			BWFAN_Core()->automations->set_automation_data( $key, $meta['meta_value'] );
		}

		/** for inserting created and modify date of automation **/
		$meta = array(
			'bwfan_automation_id' => $automation_id,
			'meta_key'            => 'c_date',
			'meta_value'          => current_time( 'mysql', 1 ),
		);
		BWFAN_Model_Automationmeta::insert( $meta );
		BWFAN_Core()->automations->set_automation_data( 'c_date', $meta['meta_value'] );

		$meta['meta_key'] = 'm_date';
		BWFAN_Model_Automationmeta::insert( $meta );
		BWFAN_Core()->automations->set_automation_data( 'm_date', $meta['meta_value'] );

		do_action( 'bwfan_automation_saved', $automation_id );

		return $automation_id;
	}

	public function set_automation_data( $key, $value1 ) {
		$this->automation_transient_data[ $key ] = $value1;
	}

	/**
	 * Get all steps data from automation
	 *
	 * @param $automation_steps
	 *
	 * @return array
	 */
	public static function get_steps_data( $automation_steps ) {
		$step_data = [];
		foreach ( $automation_steps as $step ) {
			if ( ! isset( $step['stepId'] ) ) {
				continue;
			}

			$data = BWFAN_Model_Automation_Step::get_step_data_by_id( $step['stepId'] );

			if ( empty( $data ) ) {
				continue;
			}

			$step_data[ $step['stepId'] ] = $data;
		}

		return $step_data;
	}

	/**
	 * Get prepared steps data
	 *
	 * @param $steps
	 *
	 * @return array
	 */
	public static function get_prepared_steps( $steps_data, $steps, $aid, $start_node_id = 0, $is_recipe = false ) {
		if ( ! is_array( $steps ) || empty( $steps ) ) {
			return [];
		}
		$new_steps    = [];
		$mapped_step  = [];
		$sidebar_data = [];
		foreach ( $steps as $step ) {
			if ( isset( $step['stepId'] ) ) {
				$step_data = isset( $steps_data[ $step['stepId'] ] ) ? $steps_data[ $step['stepId'] ] : [];
				$action    = isset( $step_data['action'] ) ? json_decode( $step_data['action'], true ) : [];
				/** Append the global setting's footer in the email body while importing recipes */
				if ( ! empty( $action ) && true === $is_recipe && isset( $action['action'] ) && 'wp_sendemail' === $action['action'] ) {
					$step_data = self::append_footer_in_email_body( $step_data );
				}

				unset( $step_data['ID'] );
				$step_data['created_at']      = current_time( 'mysql', 1 );
				$step_data['updated_at']      = current_time( 'mysql', 1 );
				$step_data['aid']             = $aid;
				$new_step_id                  = BWFAN_Model_Automation_Step::create_new_automation_step( $step_data );
				$sidebar_data[ $new_step_id ] = json_decode( $step_data['data'], true );

				if ( absint( $start_node_id ) === absint( $step['id'] ) ) {
					BWFAN_Model_Automations::update( [ 'start' => $new_step_id ], [ 'ID' => $aid ] );
				}
				$mapped_step[ $step['stepId'] ] = $new_step_id;
				$step['stepId']                 = $new_step_id;
				$step['data']['completed']      = 0;
				$step['data']['queued']         = 0;
				$new_steps[]                    = $step;

				continue;
			}
			$new_steps[] = $step;
		}
		/** Update jumped step id */
		self::update_jumped_step_data( $mapped_step, $sidebar_data );

		return $new_steps;
	}

	/**
	 * Update new jump step id
	 *
	 * @param $mapped_step
	 * @param $sidebar_data
	 *
	 * @return void
	 */
	public static function update_jumped_step_data( $mapped_step, $sidebar_data ) {
		if ( empty( $mapped_step ) && empty( $sidebar_data ) ) {
			return;
		}

		$value_changed = false;
		foreach ( $sidebar_data as $step_id => $data ) {
			if ( isset( $data['sidebarData']['data']['skip_to_step']['step'] ) ) {
				$data['sidebarData']['data']['skip_to_step']['step'] = $mapped_step[ $data['sidebarData']['data']['skip_to_step']['step'] ];
				$value_changed                                       = true;
			} elseif ( isset( $data['sidebarData']['jump_to']['step'] ) ) {
				$data['sidebarData']['jump_to']['step'] = $mapped_step[ $data['sidebarData']['jump_to']['step'] ];
				$value_changed                          = true;
			}
			if ( true === $value_changed ) {
				BWFAN_Model_Automation_Step::update( [ 'data' => wp_json_encode( $data ) ], [
					'ID' => $step_id,
				] );
			}
		}
	}

	public static function append_footer_in_email_body( $step_data ) {
		$sidebarData = isset( $step_data['data'] ) ? json_decode( $step_data['data'], true ) : [];
		$settings    = BWFAN_Common::get_global_settings();
		$footer      = isset( $settings['bwfan_email_footer_setting'] ) ? $settings['bwfan_email_footer_setting'] : '';
		if ( isset( $sidebarData['sidebarData']['bwfan_email_data']['template'] ) ) {
			$sidebarData['sidebarData']['bwfan_email_data']['template'] .= $footer;
		}
		$step_data['data'] = wp_json_encode( $sidebarData );

		return $step_data;
	}

	/** toggle automation state
	 *
	 * @param $automation
	 * @param $automation_id
	 */
	public function toggle_state( $automation_id, $automation ) {

		if ( ! isset( $automation['status'] ) ) {
			return false;
		}

		BWFAN_Core()->automations->set_automation_id( $automation_id );
		BWFAN_Core()->automations->toggle_automation = true;
		BWFAN_Core()->automations->set_automation_data( 'status', $automation['status'] );
		$where = array(
			'ID' => $automation_id,
		);
		BWFAN_Model_Automations::update( $automation, $where );

		do_action( 'bwfan_automation_saved', $automation_id );

		return true;
	}

	/** imported json file to create new automations
	 *
	 * @param array $import_file_data
	 * @param string $automation_title
	 *
	 * @return int automation id
	 */
	public function import( $import_file_data, $automation_title = '', $tips = [], $is_recipe = false, $version = 0 ) {
		$import_file_data = is_string( $import_file_data ) ? json_decode( $import_file_data, true ) : $import_file_data;

		if ( empty( $import_file_data ) ) {
			return false;
		}

		$automation_id = 0;
		foreach ( $import_file_data as $import_data ) {
			if ( empty( $import_data['data'] ) || ! isset( $import_data['meta']['title'] ) || '' === $import_data['meta']['title'] ) {
				continue;
			}

			if ( ! empty( $version ) && ( ! isset( $import_data['data']['v'] ) || absint( $version ) !== absint( $import_data['data']['v'] ) ) ) {
				return [
					'status'  => 'failed',
					'version' => isset( $import_data['data']['v'] ) ? $import_data['data']['v'] : ''
				];
			}

			$post             = array();
			$post['status']   = 2;
			$post['source']   = isset( $import_data['data']['source'] ) ? $import_data['data']['source'] : '';
			$post['event']    = isset( $import_data['data']['event'] ) ? $import_data['data']['event'] : '';
			$post['start']    = isset( $import_data['data']['start'] ) ? $import_data['data']['start'] : 0;
			$post['v']        = isset( $import_data['data']['v'] ) ? $import_data['data']['v'] : 1;
			$post['priority'] = 0;

			if ( 2 === absint( $post['v'] ) ) {
				$post['title'] = ! empty( $automation_title ) ? $automation_title : $import_data['meta']['title'];
			}

			BWFAN_Model_Automations::insert( $post );
			$automation_id = BWFAN_Model_Automations::insert_id();
			if ( 0 === $automation_id || is_wp_error( $automation_id ) ) {
				continue;
			}

			BWFAN_Core()->automations->set_automation_id( $automation_id );
			BWFAN_Core()->automations->set_automation_data( 'status', $post['status'] );
			$links = isset( $import_data['meta']['links'] ) ? $import_data['meta']['links'] : [];

			if ( ! empty( $tips ) ) {
				$import_data['meta']['tips'] = $tips;
			}

			if ( ! empty( $import_data['meta'] ) ) {
				foreach ( $import_data['meta'] as $key => $auto_meta ) {
					if ( 'steps' === $key ) {
						$start_node_id = self::get_start_node_id( $links );

						$auto_meta = $this->get_prepared_steps( $import_data['step_data'], $auto_meta, $automation_id, $start_node_id, $is_recipe );
					}

					if ( is_array( $auto_meta ) ) {
						$auto_meta = maybe_serialize( $auto_meta );
					}
					$meta                        = array();
					$meta['bwfan_automation_id'] = $automation_id;
					$meta['meta_key']            = $key;
					$meta['meta_value']          = $auto_meta;
					BWFAN_Model_Automationmeta::insert( $meta );
					BWFAN_Core()->automations->set_automation_data( $key, $meta['meta_value'] );
				}
			}

			$meta = array(
				'bwfan_automation_id' => $automation_id,
				'meta_key'            => 'c_date',
				'meta_value'          => current_time( 'mysql', 1 ),
			);
			BWFAN_Model_Automationmeta::insert( $meta );
			BWFAN_Core()->automations->set_automation_data( 'c_date', $meta['meta_value'] );

			$meta['meta_key'] = 'm_date';
			BWFAN_Model_Automationmeta::insert( $meta );
			BWFAN_Core()->automations->set_automation_data( 'm_date', $meta['meta_value'] );

			do_action( 'bwfan_automation_saved', $automation_id );
		}

		return $automation_id;
	}

	public static function get_start_node_id( $links ) {

		if ( ! is_array( $links ) || empty( $links ) ) {
			return 0;
		}

		$start_node = array_filter( array_map( function ( $link ) {
			if ( isset( $link['source'] ) && 'start' === $link['source'] ) {
				return $link['target'];
			}
		}, $links ) );
		sort( $start_node );

		return isset( $start_node[0] ) ? $start_node[0] : 0;
	}

	public function get_all_actions() {
		$all_actions = BWFAN_Core()->integration->get_integration_actions_localize_data();
		$all_sources = BWFAN_Core()->sources->get_source_localize_data();
		uasort( $all_sources, function ( $a, $b ) {
			return $a['priority'] <= $b['priority'] ? - 1 : 1;
		} );
		$actions = array_map( function ( $all_source ) use ( $all_actions ) {
			if ( isset( $all_actions[ $all_source['slug'] ] ) ) {
				$all_source['actions'] = $all_actions[ $all_source['slug'] ];
			}

			return $all_source;
		}, $all_sources );

		return $actions;
	}

	/**
	 * Get active v1 automations id and names
	 * @return array
	 */
	public function get_active_v1_automation_names() {
		global $wpdb;

		$table_automation      = $wpdb->prefix . 'bwfan_automations';
		$table_automation_meta = $wpdb->prefix . 'bwfan_automationmeta';

		$query = "SELECT a.ID, a.event, meta.meta_value FROM `{$table_automation}` as `a` INNER JOIN `{$table_automation_meta}` as `meta` ON meta.bwfan_automation_id = a.ID WHERE a.v = 1 AND a.status = 1 AND meta.meta_key = 'title'";

		$core_cache_obj = WooFunnels_Cache::get_instance();

		$result = $core_cache_obj->get_cache( md5( $query ), 'fka-automations' );
		if ( false === $result ) {
			$result = $wpdb->get_results( $query, ARRAY_A );
			$core_cache_obj->set_cache( md5( $query ), $result, 'fka-automations' );
		}

		return $result;
	}

	/**
	 * Check if automation is active or not
	 *
	 * @param $aid
	 *
	 * @return bool
	 */
	public function is_automation_active( $aid ) {
		if ( empty( $aid ) ) {
			return false;
		}
		global $wpdb;
		$query = $wpdb->prepare( 'SELECT `ID` FROM {table_name} WHERE ID = %d AND status = 1', $aid );

		$core_cache_obj = WooFunnels_Cache::get_instance();

		$result = $core_cache_obj->get_cache( md5( $query ), 'fka-automations' );
		if ( false === $result ) {
			$result = BWFAN_Model_Automations::get_var( $query );
			$core_cache_obj->set_cache( md5( $query ), $result, 'fka-automations' );
		}

		return ! empty( $result );
	}

	/**
	 * Get migrated v1 automations
	 *
	 * @return array
	 */
	public static function get_migrated_automations() {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT `bwfan_automation_id` FROM {$wpdb->prefix}bwfan_automationmeta WHERE `meta_key` = %s AND `meta_value` = %d", 'v1_migrate', 1 );

		$core_cache_obj       = WooFunnels_Cache::get_instance();
		$migrated_automations = $core_cache_obj->get_cache( md5( $query ), 'fka-automations' );
		if ( false === $migrated_automations ) {
			$migrated_automations = $wpdb->get_col( $query );
			$core_cache_obj->set_cache( md5( $query ), $migrated_automations, 'fka-automations' );
		}

		return $migrated_automations;
	}
}

BWFAN_Core::register( 'automations', 'BWFAN_Automations' );
