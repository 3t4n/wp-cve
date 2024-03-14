<?php

class arfliteinstallermodel {

	function __construct() {

	}

	

	function arflite_get_count( $table, $args = array() ) {

		global $wpdb, $ARFLiteMdlDb;

		extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $args ) );

		$query = "SELECT COUNT(*) FROM {$table}{$where}";

		$query = $wpdb->prepare( $query, $values ); //phpcs:ignore

		return $wpdb->get_var( $query ); //phpcs:ignore
	}

	function arflite_get_where_clause_and_values( $args ) {

		$where = '';

		$values = array();

		if ( is_array( $args ) ) {

			foreach ( $args as $key => $value ) {

				$where .= ( ! empty( $where ) ) ? ' AND' : ' WHERE';

				$where .= " {$key}=";

				$where .= ( is_numeric( $value ) ) ? '%d' : '%s';

				$values[] = $value;
			}
		}

		return compact( 'where', 'values' );
	}

	function arfliteget_var( $table, $args = array(), $field = 'id', $order_by = '' ) {

		global $wpdb, $ARFLiteMdlDb;

		extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $args ) );

		if ( ! empty( $order_by ) ) {
			$order_by = " ORDER BY {$order_by}";
		}

		$query = $wpdb->prepare( "SELECT {$field} FROM {$table}{$where}{$order_by} LIMIT 1", $values ); //phpcs:ignore

		return $wpdb->get_var( $query ); //phpcs:ignore
	}

	function arfliteget_col( $table, $args = array(), $field = 'id', $order_by = '' ) {

		global $wpdb, $ARFLiteMdlDb;

		extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $args ) );

		if ( ! empty( $order_by ) ) {
			$order_by = " ORDER BY {$order_by}";
		}

		$query = $wpdb->prepare( "SELECT {$field} FROM {$table}{$where}{$order_by}", $values ); //phpcs:ignore

		return $wpdb->get_col( $query );  //phpcs:disable
	}

	function arflite_get_one_record( $table, $args = array(), $fields = '*', $order_by = '' ) {

		global $wpdb, $ARFLiteMdlDb;

		extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $args ) );

		if ( ! empty( $order_by ) ) {
			$order_by = " ORDER BY {$order_by}";
		}

		$query = "SELECT {$fields} FROM {$table}{$where} {$order_by} LIMIT 1";

		$query = $wpdb->prepare( $query, $values ); //phpcs:disable

		return $wpdb->get_row( $query ); //phpcs:disable
	}

	function arflite_get_records( $table, $args = array(), $order_by = '', $limit = '', $fields = '*' ) {

		global $wpdb, $ARFLiteMdlDb;

		extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $args ) );

		if ( ! empty( $order_by ) ) {
			$order_by = " ORDER BY {$order_by}";
		}

		if ( ! empty( $limit ) ) {
			$limit = " LIMIT {$limit}";
		}

		$query = "SELECT {$fields} FROM {$table}{$where}{$order_by}{$limit}";

		$query = $wpdb->prepare( $query, $values ); //phpcs:disable

		return $wpdb->get_results( $query ); //phpcs:disable
	}

	function arflite_assign_rand_value( $num ) {

		switch ( $num ) {
			case '1':
				$rand_value = 'a';
				break;
			case '2':
				$rand_value = 'b';
				break;
			case '3':
				$rand_value = 'c';
				break;
			case '4':
				$rand_value = 'd';
				break;
			case '5':
				$rand_value = 'e';
				break;
			case '6':
				$rand_value = 'f';
				break;
			case '7':
				$rand_value = 'g';
				break;
			case '8':
				$rand_value = 'h';
				break;
			case '9':
				$rand_value = 'i';
				break;
			case '10':
				$rand_value = 'j';
				break;
			case '11':
				$rand_value = 'k';
				break;
			case '12':
				$rand_value = 'l';
				break;
			case '13':
				$rand_value = 'm';
				break;
			case '14':
				$rand_value = 'n';
				break;
			case '15':
				$rand_value = 'o';
				break;
			case '16':
				$rand_value = 'p';
				break;
			case '17':
				$rand_value = 'q';
				break;
			case '18':
				$rand_value = 'r';
				break;
			case '19':
				$rand_value = 's';
				break;
			case '20':
				$rand_value = 't';
				break;
			case '21':
				$rand_value = 'u';
				break;
			case '22':
				$rand_value = 'v';
				break;
			case '23':
				$rand_value = 'w';
				break;
			case '24':
				$rand_value = 'x';
				break;
			case '25':
				$rand_value = 'y';
				break;
			case '26':
				$rand_value = 'z';
				break;
			case '27':
				$rand_value = '0';
				break;
			case '28':
				$rand_value = '1';
				break;
			case '29':
				$rand_value = '2';
				break;
			case '30':
				$rand_value = '3';
				break;
			case '31':
				$rand_value = '4';
				break;
			case '32':
				$rand_value = '5';
				break;
			case '33':
				$rand_value = '6';
				break;
			case '34':
				$rand_value = '7';
				break;
			case '35':
				$rand_value = '8';
				break;
			case '36':
				$rand_value = '9';
				break;
		}
		return $rand_value;
	}

	function arflite_get_rand_alphanumeric( $length ) {
		global $ARFLiteMdlDb;
		if ( $length > 0 ) {
			$rand_id = '';
			for ( $i = 1; $i <= $length; $i++ ) {
				mt_srand( microtime( true ) * 1000000 );
				$num      = mt_rand( 1, 36 );
				$rand_id .= $ARFLiteMdlDb->arflite_assign_rand_value( $num );
			}
		}
		return $rand_id;
	}
}
