<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Booking related features
 */

class EventM_Booking_Admin {
    
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'before_delete_post', array( $this, 'ep_before_delete_event_bookings' ), 99, 2 );

		// add banner
		add_action( 'load-edit.php', function(){
			$screen = get_current_screen();
			if( 'edit-em_booking' === $screen->id ) {
				add_action( 'admin_footer', function(){
					do_action( 'ep_add_custom_banner' );
				});
			}
		});
	}

	/**
	 * Includes event related admin files
	 */
	public function includes() {
		// Meta Boxes
		include_once __DIR__ . '/meta-boxes/class-ep-booking-admin-meta-boxes.php';
	}

	/**
	 * Before dekete bookings
	 */
	public function ep_before_delete_event_bookings( $postid, $post ) {
		if( 'em_booking' !== $post->post_type ) {
			return;
		}

		global $wpdb;
		// start process of delete event and event data
		$booking_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
		$event_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		
		$booking = $booking_controllers->load_booking_detail( $postid );
		if( empty( $booking ) ) return;

		// if booked event is seating type, then on delete booking, booked seats should be free
		$event_data = $booking->event_data;
		if( ! empty( $event_data ) ) {
			$event_venue_type = '';
			if( ! empty( $event_data->venue_details ) && ! empty( $event_data->venue_details->em_type ) ) {
				$event_venue_type = $event_data->venue_details->em_type;
			}
			if( $event_venue_type == 'seats' ) {
				$event_id = $booking->em_event;
				// get event seat data
				$event_seat_data = get_post_meta( $event_id, 'em_seat_data', true );
				if( ! empty( $event_seat_data ) ) {
					$event_seat_data = maybe_unserialize( $event_seat_data );
					$em_order_info = $booking->em_order_info;
					if( ! empty( $em_order_info ) ) {
						$tickets_data = $em_order_info['tickets'];
						if( ! empty( $tickets_data ) && count( $tickets_data ) > 0 ) {
							$extensions = (array)EP()->extensions;
							$seating_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Live_Seating_List_Controller' );
							foreach( $tickets_data as $tickets ) {
								if( empty( $tickets->seats ) ) {
									continue;
								}
								foreach( $tickets->seats as $seat_datas ) {
									$area_id = $seat_datas->area_id;
									// get event seat area from order seat area
									$area_seat_data = $event_seat_data->{$area_id};
									if( ! empty( $area_seat_data ) ) {
										$order_seats = $seat_datas->seat_data;
										if( ! empty( $order_seats ) ) {
											foreach( $order_seats as $order_seat ) {
												if( ! empty( $order_seat->uid ) ) {
													$seat_uid = $order_seat->uid;
													$seat_uid = explode( '-', $seat_uid );
													$row_index = $seat_uid[0];
													$col_index = $seat_uid[1];
													if( ! empty( $area_seat_data->seats[$row_index][$col_index] ) ) {
														$area_seat_data->seats[$row_index][$col_index]->type = 'general';
														if( ! empty( $seating_controller ) && in_array( 'live_seating', $extensions ) ) {
															$area_seat_data->seats[$row_index][$col_index]->seatColor = $seating_controller->get_ticket_available_color( $area_seat_data->seats[$row_index][$col_index]->ticket_id, $event_id );
														} else{
															$area_seat_data->seats[$row_index][$col_index]->seatColor = '#8cc600';
														}
													}
												}
											}
											$event_seat_data->{$area_id} = $area_seat_data;
											update_post_meta( $event_id, 'em_seat_data', maybe_serialize( $event_seat_data ) );
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

new EventM_Booking_Admin();