<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CR_Reminders_Log' ) ) :

class CR_Reminders_Log {
	const LOGS_TABLE = 'cr_reminders_log';
	private $logs_tbl_name = '';

	public function __construct() {
	}

	public function check_create_table() {
		// check if the reminders logs table exists
		global $wpdb;
		$table_name = $wpdb->prefix . self::LOGS_TABLE;
		$name_check = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );
		if ( $name_check !== $table_name ) {
			// check if the database converted the table name to lowercase
			$table_name_l = strtolower( $table_name );
			if ( $name_check !== $table_name_l ) {
				if ( true !== $wpdb->query(
						"CREATE TABLE `$table_name` (
							`id` bigint unsigned NOT NULL AUTO_INCREMENT,
							`orderId` varchar(190) DEFAULT NULL,
							`customerEmail` varchar(1024) DEFAULT NULL,
							`customerName` varchar(1024) DEFAULT NULL,
							`status` varchar(20) DEFAULT NULL,
							`verification` varchar(20) DEFAULT NULL,
							`channel` varchar(20) DEFAULT NULL,
							`type` varchar(20) DEFAULT NULL,
							`dateCreated` datetime DEFAULT NULL,
							`dateSent` datetime DEFAULT NULL,
							`dateEmailOpened` datetime DEFAULT NULL,
							`dateFormOpened` datetime DEFAULT NULL,
							`dateReviewPosted` datetime DEFAULT NULL,
							`language` varchar(10) DEFAULT NULL,
							`reminder` json DEFAULT NULL,
							PRIMARY KEY (`id`),
							KEY `orderId_index` (`orderId`),
							KEY `customerEmail_index` (`customerEmail`),
							KEY `dateCreated_index` (`dateCreated`),
							KEY `dateSent_index` (`dateSent`)
						) CHARACTER SET 'utf8mb4';" ) ) {
					// it is possible that Maria DB is used that does not support JSON type
					if( true !== $wpdb->query(
							"CREATE TABLE `$table_name` (
								`id` bigint unsigned NOT NULL AUTO_INCREMENT,
								`orderId` varchar(190) DEFAULT NULL,
								`customerEmail` varchar(1024) DEFAULT NULL,
								`customerName` varchar(1024) DEFAULT NULL,
								`status` varchar(20) DEFAULT NULL,
								`verification` varchar(20) DEFAULT NULL,
								`channel` varchar(20) DEFAULT NULL,
								`type` varchar(20) DEFAULT NULL,
								`dateCreated` datetime DEFAULT NULL,
								`dateSent` datetime DEFAULT NULL,
								`dateEmailOpened` datetime DEFAULT NULL,
								`dateFormOpened` datetime DEFAULT NULL,
								`dateReviewPosted` datetime DEFAULT NULL,
								`language` varchar(10) DEFAULT NULL,
								`reminder` text DEFAULT NULL,
								PRIMARY KEY (`id`),
								KEY `orderId_index` (`orderId`),
								KEY `customerEmail_index` (`customerEmail`),
								KEY `dateCreated_index` (`dateCreated`),
								KEY `dateSent_index` (`dateSent`)
							) CHARACTER SET 'utf8mb4';" ) ) {
						return array( 'code' => 1, 'text' => 'Table ' . $table_name . ' could not be created' );
					}
				}
			} else {
				$table_name = $name_check;
			}
		}
		$this->logs_tbl_name = $table_name;
		return 0;
	}

	public function add( $order_id, $type, $channel, $result ) {
		global $wpdb;
		if ( 0 === $this->check_create_table() ) {
			$customerEmail = '';
			$customerName = '';
			$status = '';
			$verification = '';
			$dateCreated = gmdate('Y-m-d H:i:s');
			$dateSent = gmdate('Y-m-d H:i:s');
			$language = '';
			$reminder = array();
			if (
				isset( $result[2] ) &&
				isset( $result[2]['data'] )
			) {
				$data = $result[2]['data'];
				if (
					isset( $data['email'] ) &&
					isset( $data['email']['to'] )
				) {
					$customerEmail = $data['email']['to'];
				}
				if ( isset( $data['customer'] ) ) {
					$customerName = $data['customer']['firstname'] . ' ' . $data['customer']['lastname'];
				}
				if ( isset( $data['verification'] ) ) {
					$verification = $data['verification'];
				}
				if ( isset( $data['language'] ) ) {
					$language = $data['language'];
				}
			}
			if ( isset( $result[0] ) ) {
				if ( 0 === $result[0] ) {
					$status = 'sent';
				} else {
					$status = 'error';
					$reminder['errorDetails'] = $result[1];
				}
			}

			$insert = array(
				'orderId' => $order_id,
				'customerEmail' => $customerEmail,
				'customerName' => $customerName,
				'status' => $status,
				'verification' => $verification,
				'channel' => $channel,
				'type' => $type,
				'dateCreated' => $dateCreated,
				'dateSent' => $dateSent,
				'dateEmailOpened' => NULL,
				'dateFormOpened' => NULL,
				'dateReviewPosted' => NULL,
				'language' => $language,
				'reminder' => json_encode( $reminder )
			);
			$r = $wpdb->replace( $this->logs_tbl_name, $insert );
			if( false !== $r ) {
				return array( 'code' => 0, 'text' => '' );
			} else {
				return array( 'code' => 1, 'text' => 'Review Reminder could not be saved in the log. Error: ' . $wpdb->last_error );
			}
		}
	}

	public function get( $start, $per_page, $orderby, $order, $search ) {
		$order = strtoupper( $order );
		$order = ( $order === 'DESC' ) ? $order : 'ASC';

		switch ($orderby) {
			case 'order':
				$orderby = 'orderId';
				break;
			case 'customer':
				$orderby = 'customerName';
				break;
			case 'sent':
				$orderby = 'dateSent';
				break;
			default:
				$orderby = 'dateSent';
				break;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . self::LOGS_TABLE;
		if ( $search ) {
			$select_q = "SELECT * FROM `$table_name` WHERE `customerName` LIKE '%$search%' OR `customerEmail` LIKE '%$search%' OR `orderId` LIKE '%$search%' ORDER BY `$orderby` $order LIMIT $start, $per_page";
			$select_t = "SELECT COUNT(*) FROM `$table_name` WHERE `customerName` LIKE '%$search%' OR `customerEmail` LIKE '%$search%' OR `orderId` LIKE '%$search%' ORDER BY `$orderby` $order";
		} else {
			$select_q = "SELECT * FROM `$table_name` ORDER BY `$orderby` $order LIMIT $start, $per_page";
			$select_t = "SELECT COUNT(*) FROM `$table_name` ORDER BY `$orderby` $order";
		}
		$records = $wpdb->get_results(
			$select_q,
			ARRAY_A
		);

		$total = $wpdb->get_var( $select_t );
		if ( ! $total ) {
			$total = 0;
		}

		if( is_array( $records )  ) {
			return array(
				'records' => $records,
				'total' => intval( $total )
			);
		} else {
			return array(
				'records' => array(),
				'total' => 0
			);
		}
	}

	public function delete( $reminders ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::LOGS_TABLE;
		$ids = implode( ',', array_map( 'absint', $reminders ) );
		$wpdb->query( "DELETE FROM `$table_name` WHERE id IN($ids)" );
	}
}

endif;
