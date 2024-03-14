<?php

class CarModel {

	public $dbtable;

	public function __construct() {
		 global $wpdb;

		$this->dbtable = $wpdb->prefix . 'tblight_cars';
	}

	public function getItems() {
		global $wpdb;

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable}"
			)
		);

		return $rows;
	}

	public function getItemById( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		return $row;
	}

	public function getDefaultData() {
		$row = new stdClass();

		$row->id                 = 0;
		$row->title              = '';
		$row->state              = 1;
		$row->min_passenger_no   = 0;
		$row->passenger_no       = 0;
		$row->suitcase_no        = 0;
		$row->child_seat_no      = 0;
		$row->child_seat_price   = 0;
		$row->image              = '';
		$row->price              = 0;
		$row->minmil             = 0;
		$row->minprice           = 0;
		$row->unit_price         = 0;
		$row->charge_per_min     = 0;
		$row->track_availability = 1;
		$row->days_availability  = '[{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"},{"is_available":"1","opening_hrs":"-1","opening_mins":"-1","closing_hrs":"-1","closing_mins":"-1"}]';
		$row->blocked_dates      = '[]';
		$row->text               = '';

		return $row;
	}

	public function store( $post_data ) {
		global $wpdb;

		$id                 = (int) $post_data['id'];
		$title              = $post_data['title'];
		$alias              = sanitize_title( $post_data['title'] );
		$state              = $post_data['state'];
		$min_passenger_no   = $post_data['min_passenger_no'];
		$passenger_no       = $post_data['passenger_no'];
		$suitcase_no        = $post_data['suitcase_no'];
		$child_seat_no      = $post_data['child_seat_no'];
		$child_seat_price   = $post_data['child_seat_price'];
		$image              = $post_data['image'];
		$price              = $post_data['price'];
		$minmil             = $post_data['minmil'];
		$minprice           = $post_data['minprice'];
		$unit_price         = $post_data['unit_price'];
		$charge_per_min     = $post_data['charge_per_min'];
		$track_availability = $post_data['track_availability'];
		$days_availability  = $post_data['days_availability'];
		// $blocked_dates = $post_data['blocked_dates'];
		$text = $post_data['text'];

		$blocked_dates_arr = array();
		if ( ! empty( $post_data['blocked_dates'] ) ) {
			foreach ( $post_data['blocked_dates'] as $v ) {
				if ( $v != '' ) {
					$blocked_dates_arr[] = $v;
				}
			}
		}

		if ( $id == 0 ) { // New Item
			$row = $wpdb->insert(
				$this->dbtable,
				array(
					'title'              => $title,
					'alias'              => $alias,
					'state'              => $state,
					'min_passenger_no'   => $min_passenger_no,
					'passenger_no'       => $passenger_no,
					'suitcase_no'        => $suitcase_no,
					'child_seat_no'      => $child_seat_no,
					'child_seat_price'   => $child_seat_price,
					'image'              => $image,
					'price'              => $price,
					'minmil'             => $minmil,
					'minprice'           => $minprice,
					'unit_price'         => $unit_price,
					'charge_per_min'     => $charge_per_min,
					'track_availability' => $track_availability,
					'days_availability'  => json_encode( $days_availability ),
					'blocked_dates'      => json_encode( $blocked_dates_arr ),
					'text'               => $text,
					'created_by'         => get_current_user_id(),
					'created'            => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'%s', // title
					'%s', // alias
					'%d', // state
					'%d', // min_passenger_no
					'%d', // passenger_no
					'%d', // suitcase_no
					'%d', // child_seat_no
					'%d', // child_seat_price
					'%s', // image
					'%d', // price
					'%d', // minmil
					'%d', // minprice
					'%d', // unit_price
					'%d', // charge_per_min
					'%d', // track_availability
					'%s', // days_availability
					'%s', // blocked_dates
					'%s', // text
					'%d', // created_by
					'%s',  // created
				)
			);

			$id = (int) $wpdb->insert_id;
		} elseif ( $id > 0 ) {
			$row = $wpdb->update(
				$this->dbtable,
				array(
					'title'              => $title,
					'alias'              => $alias,
					'state'              => $state,
					'min_passenger_no'   => $min_passenger_no,
					'passenger_no'       => $passenger_no,
					'suitcase_no'        => $suitcase_no,
					'child_seat_no'      => $child_seat_no,
					'child_seat_price'   => $child_seat_price,
					'image'              => $image,
					'price'              => $price,
					'minmil'             => $minmil,
					'minprice'           => $minprice,
					'unit_price'         => $unit_price,
					'charge_per_min'     => $charge_per_min,
					'track_availability' => $track_availability,
					'days_availability'  => json_encode( $days_availability ),
					'blocked_dates'      => json_encode( $blocked_dates_arr ),
					'text'               => $text,
					'modified_by'        => get_current_user_id(),
					'modified'           => current_time( 'Y-m-d H:i:s' ),
				),
				array(
					'id' => $id,
				)
			);
		}

		return $id;
	}

	public function delete( $id = 0 ) {
		global $wpdb;

		return $wpdb->delete(
			$this->dbtable,
			array( 'id' => $id ),
			array( '%d' )
		);
	}

	public function status( $id = 0 ) {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT state FROM {$this->dbtable} WHERE id = %d",
				(int) $id
			)
		);

		if ( $row->state == 0 ) {
			$wpdb->update( $this->dbtable, array( 'state' => 1 ), array( 'id' => $id ) );
		} else {
			$wpdb->update( $this->dbtable, array( 'state' => 0 ), array( 'id' => $id ) );
		}

		return true;
	}
}
