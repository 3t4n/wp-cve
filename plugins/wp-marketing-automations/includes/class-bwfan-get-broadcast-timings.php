<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BWFAN_DEV_Get_Broadcast_Timings' ) ) {
	final class BWFAN_DEV_Get_Broadcast_Timings {
		private static $ins = null;

		public function __construct() {
			add_action( 'admin_head', [ $this, 'broadcast_details' ] );
		}

		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public function broadcast_details() {
			$bid = filter_input( INPUT_GET, 'admin_broadcast_id' );
			if ( empty( $bid ) ) {
				return;
			}

			$this->output_css();

			$data = $this->get_broadcast_timing( $bid );
			if ( empty( $data ) ) {
				echo "<h3>No Broadcast details found.</h3>";
				exit;
			}

			$start_time = ! empty( $data['start_time'] ) ? "Start time: " . $data['start_time'] : '';
			if ( empty( $start_time ) ) {
				echo "<h3>Broadcast not started yet.</h3>";
				exit;
			}

			$end_time = ! empty( $data['start_time'] ) ? "End time: " . $data['end_time'] : '';
			if ( empty( $end_time ) ) {
				echo "<h3>Broadcast is not finished yet.</h3>";
				exit;
			}

			$total_sent = ! empty( $data['sent'] ) ? "Total sent: " . $data['sent'] : '';
			if ( empty( $total_sent ) ) {
				echo "<h3>No emails sent yet.</h3>";
				exit;
			}

			$total_time = strtotime( $data['end_time'] ) - strtotime( $data['start_time'] );
			$per_sec    = ( intval( $total_time ) > 0 && $data['sent'] > 0 ) ? absint( $data['sent'] ) / $total_time : $data['sent'];

			echo '<div class="broadcast_data">';
			echo "$start_time<br>";
			echo "$end_time<br>";
			echo "$total_sent<br>";

			echo "Total time in secs: ";
			echo ( ! empty( $total_time ) ) ? $total_time : 1;
			echo "<br>";

			if ( ! empty( $total_time ) && ( $total_time > 60 ) ) {
				echo "Total time in mins: " . intval( $total_time / 60 ) . "<br>";
			}
			echo "Emails in one second: $per_sec";

			echo "<h3>Per second breakup</h3>";

			$data = $this->get_broadcast_details( $bid );

			echo '<div class="broadcast_table">';
			echo "<div><div>Date Time</div><div>Count</div></div>";
			foreach ( $data as $v ) {
				echo "<div><div>{$v['created_at']}</div><div>{$v['count']}</div></div>";
			}
			echo '</div>';

			echo '</div>';
			exit;
		}

		public function get_broadcast_timing( $oid ) {
			global $wpdb;
			$query = "SELECT MAX( created_at ) as end_time, MIN( created_at ) as start_time, COUNT( ID ) as sent FROM {$wpdb->prefix}bwfan_engagement_tracking WHERE `oid` = %d and `type` = %d";

			return $wpdb->get_row( $wpdb->prepare( $query, $oid, 2 ), ARRAY_A );
		}

		public function get_broadcast_details( $oid ) {
			global $wpdb;
			$query = "SELECT `created_at`, count(`created_at`) as `count` FROM `{$wpdb->prefix}bwfan_engagement_tracking` WHERE `type` = %d AND `oid` = %d GROUP BY `created_at` ORDER BY `created_at` DESC LIMIT 0, 100";

			return $wpdb->get_results( $wpdb->prepare( $query, 2, $oid ), ARRAY_A );
		}

		public function output_css() {
			?>
            <style>
                h3 {
                    margin: 20px;
                }

                .broadcast_data {
                    padding: 20px;
                    line-height: 2;
                    font-size: 16px;
                }

                .broadcast_table {
                    width: 500px;
                }

                .broadcast_table > div {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                }
            </style>
			<?php
		}
	}

	BWFAN_DEV_Get_Broadcast_Timings::get_instance();
}