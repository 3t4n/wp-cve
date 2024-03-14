<?php 
/**
 * Class for define events model
 */
defined( 'ABSPATH' ) || exit;

class EventM_Event_Model {

    /**
     * Event options.
     * 
     * @var array
     */
    public $event_options = [];

    public function __construct() {
        $this->get_date_time_options();
        $this->get_booking_options();
        $this->get_other_event_options();
        $this->get_event_checkout_fields_options();
        
    }

    /**
     * Merge all date time metabox related options
     */
    public function get_date_time_options() {
        $datetime = array(
            'em_start_date'                         => '',
            'em_start_time'                         => '',
            'em_all_day'                            => '',
            'em_hide_event_start_time'              => 0,
            'em_hide_event_start_date'              => 0,
            'em_end_date'                           => '',
            'em_end_time'                           => '',
            'em_hide_event_end_time'                => 0,
            'em_hide_end_date'                      => 0,
            'em_event_date_placeholder'             => '',
            'em_event_date_placeholder_custom_note' => '',
            'em_event_more_dates'                   => '',
            'em_event_add_more_dates'               => array(),
        );
        $this->event_options = array_merge( $this->event_options, $datetime );
    }

    /**
     * Merge all bookings metabox related options
     */
    public function get_booking_options() {
        $booking = array(
            'em_enable_booking'         => 0,
            'em_custom_link_enabled'    => 0,
            'em_custom_link'            => '',
            'em_fixed_event_price'      => 0,
            'em_show_fixed_event_price' => 0,
            'em_hide_booking_status'    => 0,
        );
        $this->event_options = array_merge( $this->event_options, $booking );
    }

    /**
     * Merge all other event related options
     */
    public function get_other_event_options() {
        $booking = array(
            'em_name'                     => '',
            'em_id'                       => 0,
            'em_cover_image_id'           => '',
            'em_status'                   => 'publish',
            'em_descriptions'             => '',
            'em_event_type'               => 0,
            'em_venue'                    => 0,
            'em_performer'                => 0,
            'em_organizer'                => 0,
            'em_user'                     => 0,
            'em_user_submitted'           => 0,
            'em_hide_event_from_calendar' => 0,
            'em_hide_event_from_events'   => 0,
            'em_gallery_image_ids'        => 0,
            'em_event_text_color'         => 0,
        );
        $this->event_options = array_merge( $this->event_options, $booking );
    }

    /** 
     * Merge all checkout fields related options 
     */
    public function get_event_checkout_fields_options() {
        $checkout_fields = array(
            'em_event_checkout_name'                      => 0,
            'em_event_checkout_name_first_name'           => 0,
            'em_event_checkout_name_first_name_required'  => 0,
            'em_event_checkout_name_middle_name'          => 0,
            'em_event_checkout_name_middle_name_required' => 0,
            'em_event_checkout_name_last_name'            => 0,
            'em_event_checkout_name_last_name_required'   => 0,
            'em_event_checkout_fields_data'               => array(),
        );
        $this->event_options = array_merge( $this->event_options, $checkout_fields );
    }

    /**
     * Load model
     */
    public function ep_get_event_meta() {
        $event_meta_keys = $this->event_options;
        $event_meta_keys = apply_filters( 'ep_event_meta_keys', $event_meta_keys );
        return $event_meta_keys;
    }

}