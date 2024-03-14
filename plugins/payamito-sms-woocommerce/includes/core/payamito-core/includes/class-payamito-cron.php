<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Cron" ) ) {
	class Payamito_Cron
	{

		public function __construct()
		{
			add_filter( 'cron_schedules', [ $this, 'cron_add_schedule' ] );
			add_action( 'kianfr_' . 'payamito' . '_save_before', [ $this, 'set_schedule' ], 10, 1 );
			add_action( 'payamito_remove_log', [ __CLASS__, 'remove_log' ], 10, 1 );
		}

		public static function remove_log( $options )
		{
			if ( $options['active'] != '1' ) {
				wp_clear_scheduled_hook( 'payamito_remove_log' );

				return;
			}

			$table_name = Payamito_DB::table_name();
			$order      = self::set_order( $options['order'] );
			$logic      = ( isset( $options['logic'] ) && count( $options['logic'] ) != 0 ) ? self::set_condition_logic( $options['logic'] ) : [];

			$ids = Payamito_DB::select( $table_name, $logic, null, [ 'id' ], [
				'column' => 'date',
				'order'  => $order,
			] );

			if ( count( $ids ) != 0 ) {
				Payamito_DB::delete( $table_name, $ids );
			}
		}

		public function remove_log_prepar_ids( array $ids )
		{
			$remove_ids = [];

			foreach ( $ids as $id ) {
				array_push( $remove_ids, $id['id'] );
			}

			return $remove_ids;
		}

		public static function set_condition_logic( array $conditions )
		{
			$where = [];

			foreach ( $conditions as $condition ) {
				switch ( $condition[0] ) {
					case "status_success":
						$where['status'] = $condition[1];
					case "status_failed":
						$where['status'] = $condition[1];
				}
			}

			return $where;
		}

		public static function set_order( $order )
		{
			switch ( $order ) {
				case "date_asc":
					return "ASC";
					break;
				case "date_desc":
					return "DESC";
					break;
				default:
					return "ASC";
			}
		}

		public function cron_add_schedule( $schedules )
		{
			$add = [
				[
					'key'      => "payamito_two_weeks",
					'interval' => 1209600,
					'display'  => __( 'Every two weeks', "payamito" ),
				],
				[
					'key'      => "payamito_once_month",
					'interval' => 2592000,
					'display'  => __( 'Every once month', "payamito" ),
				],
				[
					'key'      => "payamito_three_months",
					'interval' => 7776000,
					'display'  => __( 'Every three months', "payamito" ),
				],
				[
					'key'      => "payamito_six_months",
					'interval' => 15552000,
					'display'  => __( 'Every six months', "payamito" ),
				],
				[
					'key'      => "payamito_once_year",
					'interval' => 31104000,
					'display'  => __( 'Every Once year', "payamito" ),
				],
			];

			$add = apply_filters( 'payamito_add_schedule', $add );

			if ( is_array( $add ) ) {
				foreach ( $add as $a ) {
					$key = $a['key'];
					unset( $a['key'] );

					$schedules[ $key ] = $a;
				}
			}

			return $schedules;
		}

		public function set_schedule( $options )
		{
			self::set_remove_log( $options );
		}

		public static function set_remove_log( $options )
		{
			if ( $options['log_active'] == '1' ) {
				$old_options         = get_option( 'payamito' );
				$old['active']       = $old_options['log_active'];
				$old['recurrence']   = $old_options['log_recurrence'];
				$old['order']        = $old_options['log_order'];
				$old['logic_active'] = $old_options['log_logic_active'];
				$old['logic']        = $old_options['log_logic'];
				////////////////////////////////////////////new
				$new['active']       = $options['log_active'];
				$new['recurrence']   = $options['log_recurrence'];
				$new['order']        = $options['log_order'];
				$new['logic_active'] = $options['log_logic_active'];
				$new['logic']        = $options['log_logic'];
				/////////////////////////////////////////////

				$diff1 = array_diff( $old, $new );
				$diff2 = [];

				foreach ( $new['logic'] as $index => $item ) {
					$diff2 = array_diff( $old['logic'][ $index ], $item );

					if ( count( $diff2 ) != 0 || is_null( $diff2 ) ) {
						break;
					}
				}
				if ( count( $diff1 ) != 0 || count( $diff2 ) != 0 || is_null( $diff2 ) ) {
					wp_clear_scheduled_hook( 'payamito_remove_log' );

					if ( ! wp_next_scheduled( 'payamito_remove_log', $new ) ) {
						wp_schedule_event( time(), $new['recurrence'], 'payamito_remove_log', $new );
					}
				}
			} else {
				wp_clear_scheduled_hook( 'payamito_remove_log' );
			}
		}
	}
}
