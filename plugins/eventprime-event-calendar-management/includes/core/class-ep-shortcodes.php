<?php
defined( 'ABSPATH' ) || exit;
/**
 * Shortcode class
 */
class EventM_Shortcodes {
	/**
	 * Init action
	 */
	public static function init() {
		$shortcodes = array(
            'em_events'            => __CLASS__. '::load_events',
			'em_performers'        => __CLASS__. '::load_performers',
			'em_performer'         => __CLASS__. '::load_single_performer',
			'em_event_organizers'  => __CLASS__. '::load_event_organizers',
			'em_event_organizer'   => __CLASS__. '::load_single_event_organizer',
			'em_event_types'       => __CLASS__. '::load_event_types',
			'em_event_type'        => __CLASS__. '::load_single_event_type',
			'em_sites'             => __CLASS__. '::load_venues',
			'em_event_site'        => __CLASS__. '::load_single_venue',
			'em_profile'           => __CLASS__. '::load_profile',
			'em_login'             => __CLASS__. '::load_login',
			'em_register'          => __CLASS__. '::load_register',
			'em_event_submit_form' => __CLASS__. '::load_event_submit_form',
			'em_booking'           => __CLASS__. '::load_booking',
			'em_booking_details'   => __CLASS__. '::load_event_booking_details',
			'em_event'             => __CLASS__. '::load_single_event',
			'em_sponsors'		   => __CLASS__. '::load_sponsors',
			'em_sponsor'		   => __CLASS__. '::load_single_sponsor',
        );

        foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode", $shortcode ), $function );
		}
	}

    /**
     * Display all events
     * 
     * @param array $atts Attributes.
     * @return string
     */
    public static function load_events( $atts ) {
        $events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if( isset( $_GET['event'] ) && ! empty( $_GET['event'] ) || isset( $atts['id'] ) && ! empty( $atts['id'] ) && ! isset( $atts['view'] ) ) {
			if( ! empty( $_GET['event'] ) ){ 
				$event_id = absint( $_GET['event'] );
			}
			if( ! empty( $atts['id'] ) ){
				if( strpos( $atts['id'], ',' ) !== false ) {
					$atts['id'] = explode( ',', $atts['id'] );
					return $events->render_template( $atts );
				}
				$event_id = absint( $atts['id'] );
			}
			$atts['id'] = $event_id;
			return $events->render_detail_template( $atts );
		} else{
			return $events->render_template( $atts );
		}
    }

	/**
     * Display event detail
     * 
     * @param array $atts Attributes.
     * @return string
     */
    public static function load_single_event( $atts ) {
        $events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$event_id = absint( $atts['id'] );
		$event_data = $events->get_single_event( $event_id );
		if( $event_data && 'trash' !== $event_data->post_status ) {
			return $events->render_detail_template( $atts );
		} else{
			return;
		}
    }

	/**
	 * Load performers template
	 * 
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function load_performers( $atts ) {
		$performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if( isset( $_GET['performer'] ) && ! empty( $_GET['performer'] ) ) {
			$performer_id = absint( $_GET['performer'] );
			$atts['id'] = $performer_id;
			return $performers->render_detail_template( $atts );
		} else{
			return $performers->render_template( $atts );
		}
	}

	/**
	 * Load organizer template
	 * 
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function load_event_organizers( $atts ) {
		$organizers = EventM_Factory_Service::ep_get_instance( 'EventM_Organizer_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if( isset( $_GET['organizer'] ) && ! empty( $_GET['organizer'] ) ) {
			$organizer_id = absint( $_GET['organizer'] );
			$atts['id'] = $organizer_id;
			return $organizers->render_detail_template( $atts );
		} else{
			return $organizers->render_template( $atts );
		}
	}

	/**
	 * Load Event Type template
	 * 
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function load_event_types( $atts ) {
		$event_types = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if( isset( $_GET['event_type'] ) && ! empty( $_GET['event_type'] ) ) {
			$event_type_id = absint( $_GET['event_type'] );
			$atts['id'] = $event_type_id;
			return $event_types->render_detail_template( $atts );
		} else{
			return $event_types->render_template( $atts );
		}
	}

	/**
	 * Load Venues template
	 * 
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function load_venues( $atts ) {
		$venues = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		if( isset( $_GET['venue'] ) && ! empty( $_GET['venue'] ) ) {
			$venue_id = absint( $_GET['venue'] );
			$atts['id'] = $venue_id;
			return $venues->render_detail_template( $atts );
		} else{
			return $venues->render_template( $atts );
		}
	}

	/**
	 * Load profile template
	 */
	public static function load_profile( $atts ) {
		$users = EventM_Factory_Service::ep_get_instance( 'EventM_User_Controller' );
		return $users->render_template( $atts );
	}

	/*
	* Load Login template
	*/
	public static function load_login( $atts ) {
		$users = EventM_Factory_Service::ep_get_instance( 'EventM_User_Controller' );
		return $users->render_login_template( $atts );
	}
	
	/*
	* Load Register template
	*/
	public static function load_register( $atts ) {
		$users = EventM_Factory_Service::ep_get_instance( 'EventM_User_Controller' );
		return $users->render_register_template( $atts );
	}
	/**
	 * Load Performer detail page
	 */
	public static function load_single_performer( $atts ) {
		$performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
		return $performers->render_detail_template( $atts );
	}

	/**
	 * Load Organizer detail page
	 */
	public static function load_single_event_organizer( $atts ) {
		$organizers = EventM_Factory_Service::ep_get_instance( 'EventM_Organizer_Controller_List' );
		return $organizers->render_detail_template( $atts );
	}

	/**
	 * Load Event Type detail page
	 */
	public static function load_single_event_type( $atts ) {
		$event_types = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
		return $event_types->render_detail_template( $atts );
	}

	/**
	 * Load Venue detail page
	 */
	public static function load_single_venue( $atts ) {
		$venues = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
		return $venues->render_detail_template( $atts );
	}

	/**
     * Frontend Event Submission
     * 
     * @param array $atts Attributes.
     * @return string
     */
    public static function load_event_submit_form( $atts ) {
        $event_submission = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_Frontend_Submission' );
        return $event_submission->render_template( $atts );
    }

	/**
     * Bookings
     * 
     * @param array $atts Attributes.
     * @return string
     */
    public static function load_booking( $atts ) {
        $bookings = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        return $bookings->render_template( $atts );
    }

	/**
     * Booking Detail
     * 
     * @param array $atts Attributes.
     * @return string
     */
	public static function load_event_booking_details( $atts ) {
		$bookings = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
        return $bookings->render_booking_detail_template( $atts );
	}

	/**
	 * Load sponsors template
	 * 
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function load_sponsors( $atts ) {
		$extensions = EP()->extensions;
		if( ! empty( $extensions ) && in_array( 'sponsor', $extensions ) ) {
			$sponsors = EventM_Factory_Service::ep_get_instance( 'EventM_Sponsor_Controller_List' );
			if( ! empty( $sponsors ) ) {
				$atts = array_change_key_case( (array) $atts, CASE_LOWER );
				if( isset( $_GET['sponsor'] ) && ! empty( $_GET['sponsor'] ) ) {
					$sponsor_id = absint( $_GET['sponsor'] );
					$atts['id'] = $sponsor_id;
					return $sponsors->render_detail_template( $atts );
				} else{
					return $sponsors->render_template( $atts );
				}
			}
		}
	}

	/**
	 * Load Sponsor detail page
	 */
	public static function load_single_sponsor( $atts ) {
		$extensions = EP()->extensions;
		if( ! empty( $extensions ) && in_array( 'sponsor', $extensions ) ) {
			$sponsors = EventM_Factory_Service::ep_get_instance( 'EventM_Sponsor_Controller_List' );
			if( ! empty( $sponsors ) ) {
				return $sponsors->render_detail_template( $atts );
			}
		}
	}

}

EventM_Shortcodes::init();