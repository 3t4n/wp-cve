<?php

class BWFAN_Model_Automationmeta extends BWFAN_Model {
	static $primary_key = 'ID';

	public static function get_meta( $id, $key ) {
		$rows  = self::get_automation_meta( $id );
		$value = false;
		if ( count( $rows ) > 0 && isset( $rows[ $key ] ) ) {
			$value = $rows[ $key ];
		}

		return $value;
	}

	public static function get_automation_meta( $automation_id ) {
		if ( empty( $automation_id ) ) {
			return [];
		}
		$WooFunnels_Cache_obj = WooFunnels_Cache::get_instance();
		$meta                 = $WooFunnels_Cache_obj->get_cache( 'bwfan_automations_meta_' . $automation_id, 'autonami' );

		if ( false === $meta ) {
			global $wpdb;
			$table     = self::_table();
			$sql_query = "SELECT bwfan_automation_id, meta_key, meta_value FROM {$table} WHERE bwfan_automation_id =%d";
			$sql_query = $wpdb->prepare( $sql_query, $automation_id ); // WPCS: unprepared SQL OK
			$result    = $wpdb->get_results( $sql_query, ARRAY_A ); // WPCS: unprepared SQL OK
			$meta      = [];

			if ( is_array( $result ) && count( $result ) > 0 ) {
				foreach ( $result as $meta_values ) {
					$key          = $meta_values['meta_key'];
					$meta[ $key ] = maybe_unserialize( $meta_values['meta_value'] );
				}
			}
			$WooFunnels_Cache_obj->set_cache( 'bwfan_automations_meta_' . $automation_id, $meta, 'autonami' );
		}

		return $meta;
	}

	public static function update_automation_meta_values( $automation_id, $data ) {
		if ( empty( $automation_id ) || empty( $data ) ) {
			return false;
		}

		global $wpdb;
		$table = self::_table();
		foreach ( $data as $key => $value ) {
			$wpdb->update( $table, [
				'meta_value' => $value
			], [
				'bwfan_automation_id' => intval( $automation_id ),
				'meta_key'            => $key
			] );
		}

		return true;
	}

	public static function insert_automation_meta_data( $automation_id, $data ) {
		if ( empty( $automation_id ) || empty( $data ) ) {
			return false;
		}

		global $wpdb;
		$table = self::_table();
		foreach ( $data as $key => $value ) {
			$wpdb->insert( $table, [
				'bwfan_automation_id' => intval( $automation_id ),
				'meta_key'            => $key,
				'meta_value'          => $value,
			] );
		}

		return true;
	}

	public static function get_automations_meta( $aids, $meta_key = '' ) {
		if ( empty( $aids ) || ! is_array( $aids ) ) {
			return [];
		}
		$aids = implode( ', ', $aids );
		global $wpdb;
		$table     = self::_table();
		$sql_query = "SELECT bwfan_automation_id, meta_key, meta_value FROM {$table} WHERE bwfan_automation_id IN ($aids)";

		if ( !empty( $meta_key ) ) {
			$sql_query .= " AND meta_key= 'event_meta'";
		}
		
		$result    = $wpdb->get_results( $sql_query, ARRAY_A ); // WPCS: unprepared SQL OK
		$meta      = [];

		if ( is_array( $result ) && count( $result ) > 0 ) {
			foreach ( $result as $meta_values ) {
				$key          = $meta_values['meta_key'];
				$meta[ $meta_values['bwfan_automation_id'] ][ $key ] = maybe_unserialize( $meta_values['meta_value'] );
			}
		}

		return $meta;
	}

	public static function delete_automation_meta( $aid, $meta_key ) {
		global $wpdb;
		$table = self::_table();
		$query = "DELETE FROM {$table} WHERE `bwfan_automation_id` = %d AND `meta_key` = %s ";

		$wpdb->query( $wpdb->prepare( $query, $aid, $meta_key ) ); 
	}
}
