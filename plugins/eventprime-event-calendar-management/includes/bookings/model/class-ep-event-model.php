<?php 
/**
 * Class for define booking model
 */
defined( 'ABSPATH' ) || exit;

class EventM_Booking_Model {

    /**
     * Booking options.
     * 
     * @var array
     */
    public $booking_options = [];

    public function __construct() {
        $this->get_booking_options();
    }

    /**
     * Merge all booking related options
     */
    public function get_booking_options() {
        $booking = array(
            'em_id'                 => '',
            'em_event'              => '',
            'em_date'               => '',
            'em_order_info'         => array(),
            'em_notes'              => array(),
            'em_payment_log'        => array(),
            'em_user'               => '',
            'em_name'               => '',
            'em_booking_tmp_status' => 1,
            'em_status'             => 'pending',
            'em_booked_seats'       => array(),
            'em_attendee_names'     => array(),
            'em_multi_price_id'     => ''    
        );
        $this->booking_options = array_merge( $this->booking_options, $booking );
    }

}