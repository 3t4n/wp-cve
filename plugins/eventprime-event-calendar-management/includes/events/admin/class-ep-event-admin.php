<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Events related features
 */

class EventM_Events_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'before_delete_post', array( $this, 'ep_before_delete_events' ), 99, 2 );
        add_filter( 'post_updated_messages', array( $this, 'ep_event_updated_messages' ) );
        add_action( 'restrict_manage_posts', array( $this, 'ep_events_filters' ) );
        add_filter( 'parse_query', array( $this, 'ep_events_filters_arguments' ), 100, 1 );
        add_filter( 'months_dropdown_results', array( $this, 'ep_events_filters_remove_date' ) );
        // add duplicate event option in bulk actions
        add_filter( 'bulk_actions-edit-em_event', array( $this, 'ep_register_duplicate_event_actions' ) );
        // handle duplicate event bulk action
        add_filter( 'handle_bulk_actions-edit-em_event', array( $this, 'ep_duplicate_event_bulk_action_handler' ) , 10, 3 );
    }

	/**
	 * Includes event related admin files
	 */
	public function includes() {
		// Meta Boxes
		include_once __DIR__ . '/meta-boxes/class-ep-event-admin-meta-boxes.php';
	}

	public function ep_before_delete_events( $postid, $post ) {
		if( 'em_event' !== $post->post_type ) {
			return;
		}
		global $wpdb;
		// start process of delete event and event data
		$booking_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
		$event_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$event_data = $event_controllers->get_single_event( $postid );
		// first check for recurring events
		$metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );
		$metaboxes_controllers->ep_delete_child_events( $postid );
		// check category and tickets and delete them
		$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
		$price_options_table = $wpdb->prefix.'em_price_options';
		// delete all ticket categories
		if( ! empty( $event_data->ticket_categories ) ) {
			foreach( $event_data->ticket_categories as $category ) {
				if( ! empty( $category->id ) ) {
					$wpdb->delete( $cat_table_name, array( 'id' => $category->id ) );
				}
			}
		}
		// delete all tickets
		if( ! empty( $event_data->all_tickets_data ) ) {
			foreach( $event_data->all_tickets_data as $ticket ) {
				if( ! empty( $ticket->id ) ) {
					$wpdb->delete( $price_options_table, array( 'id' => $ticket->id ) );
				}
			}
		}
		// delete booking of this event
		$event_bookings = $booking_controllers->get_event_bookings_by_event_id( $postid );
		if( ! empty( $event_bookings ) ) {
			foreach( $event_bookings as $booking ) {
				// delete booking
				wp_delete_post( $booking->ID, true );
			}
		}
		// delete terms relationships
		wp_delete_object_term_relationships( $postid, array( EM_EVENT_VENUE_TAX, EM_EVENT_TYPE_TAX, EM_EVENT_ORGANIZER_TAX ) );
        // delete ext data
        do_action( 'ep_delete_event_data', $postid );
	}
        
    public function ep_event_updated_messages($message){
        $post = get_post();
        $messages['em_event'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Event updated.','eventprime-event-calendar-management' ),
            2  => esc_html__( 'Custom field updated.','eventprime-event-calendar-management' ),
            3  => esc_html__( 'Custom field deleted.','eventprime-event-calendar-management'),
            4  => esc_html__( 'Event updated.','eventprime-event-calendar-management' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Event restored to revision from %s','eventprime-event-calendar-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Event published.','eventprime-event-calendar-management' ),
            7  => esc_html__( 'Event saved.','eventprime-event-calendar-management' ),
            8  => esc_html__( 'Event submitted.', 'eventprime-event-calendar-management' ),
            9  => sprintf(
                esc_html__( 'Event scheduled for: <strong>%1$s</strong>.','eventprime-event-calendar-management' ),
                date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Event draft updated.','eventprime-event-calendar-management' )
        );
        $messages['em_coupon'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Event Coupon updated.','eventprime-event-calendar-management' ),
            2  => esc_html__( 'Custom field updated.','eventprime-event-calendar-management' ),
            3  => esc_html__( 'Custom field deleted.','eventprime-event-calendar-management'),
            4  => esc_html__( 'Event Coupon updated.','eventprime-event-calendar-management' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Event Coupon restored to revision from %s','eventprime-event-calendar-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Event Coupon published.','eventprime-event-calendar-management' ),
            7  => esc_html__( 'Event Coupon saved.','eventprime-event-calendar-management' ),
            8  => esc_html__( 'Event Coupon submitted.', 'eventprime-event-calendar-management' ),
            9  => sprintf(
                esc_html__( 'Event Coupon scheduled for: <strong>%1$s</strong>.','eventprime-event-calendar-management' ),
                date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Event Coupon draft updated.','eventprime-event-calendar-management' )
        );
        $messages['em_performer'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Event performer updated.','eventprime-event-calendar-management' ),
            2  => esc_html__( 'Custom field updated.','eventprime-event-calendar-management' ),
            3  => esc_html__( 'Custom field deleted.','eventprime-event-calendar-management'),
            4  => esc_html__( 'Event performer updated.','eventprime-event-calendar-management' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Event performer restored to revision from %s','eventprime-event-calendar-management' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Event performer published.','eventprime-event-calendar-management' ),
            7  => esc_html__( 'Event performer saved.','eventprime-event-calendar-management' ),
            8  => esc_html__( 'Event performer submitted.', 'eventprime-event-calendar-management' ),
            9  => sprintf(
                esc_html__( 'Event performer scheduled for: <strong>%1$s</strong>.','eventprime-event-calendar-management' ),
                date_i18n( esc_html__( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Event performer draft updated.','eventprime-event-calendar-management' )
        );
        return $messages;
	}
    /*
    * Adding Filter to Events
    */
    public function ep_events_filters(){
        global $typenow;
        $filter_types = array(
            'publish_date' => esc_html__( 'Created Date', 'eventprime-event-calendar-management' ),
            'event_date'   => esc_html__( 'Event Date', 'eventprime-event-calendar-management' ),
        );
        if ( $typenow == 'em_event' ) {
            wp_enqueue_style(
                'ep-daterangepicker-css',
                EP_BASE_URL . '/includes/events/assets/css/daterangepicker.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-daterangepicker-js',
                EP_BASE_URL . '/includes/events/assets/js/daterangepicker.min.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-events-list-js',
                EP_BASE_URL . '/includes/events/assets/js/ep-admin-events-list.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            $selected_filter = 'publish_date';
            if( isset( $_GET['filter_type'] ) ) {
                $selected_filter = sanitize_text_field( $_GET['filter_type'] );
            }?>
            <span><?php esc_html_e( 'Filter by', 'eventprime-event-calendar-management' );?>
                <select name="filter_type" id="filter_type">
                    <?php foreach( $filter_types as $key => $type ) {?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $selected_filter ); ?>><?php echo esc_attr( $type ); ?></option>
                    <?php } ?>
                </select>
            </span>
            <span>
                <?php esc_html_e( 'Date', 'eventprime-event-calendar-management' );
                $filter_date = '';
                if( isset( $_GET['ep_filter_date'] ) && ! empty( $_GET['ep_filter_date'] ) ) {
                    $filter_date = sanitize_text_field( $_GET['ep_filter_date'] );
                }?>
                <input id="event_date_picker" type="text" name="ep_filter_date" value="<?php echo esc_attr( $filter_date );?>" placeholder="<?php esc_attr_e( 'Select Date', 'eventprime-event-calendar-management' );?>" autocomplete="off"/>
            </span><?php 
        }
    }

    /*
    * Modify Filter Query
    */
    public function ep_events_filters_arguments( $query ) {
        global $pagenow;
        $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_event' && isset( $_GET['filter_type'] ) && sanitize_text_field( $_GET['filter_type'] ) == 'event_date' ) {
            if( isset( $_GET['ep_filter_date'] ) && ! empty( $_GET['ep_filter_date'] ) ) {
                $date_range = sanitize_text_field( $_GET['ep_filter_date'] );
                $dates = explode( ' - ', $date_range );
                $start_date = isset( $dates[0] ) && ! empty( $dates[0] ) ? $dates[0] : '';
                $end_date = isset( $dates[1] ) && ! empty( $dates[1] ) ? $dates[1] : '';

                if( ! empty( $start_date ) ) {
                    $start_meta = array (
                        'key'     => 'em_start_date',
                        'value'   => ep_date_to_timestamp( $start_date ),
                        'compare' => '>=',
                        'type'    => 'NUMERIC'
                    );
                    $query->query_vars['meta_query'][] = $start_meta;
                }
                if( ! empty( $end_date ) ) {
                    $end_meta = array (
                        'key'     => 'em_end_date',
                        'value'   => ep_datetime_to_timestamp( $end_date.' 11:59PM' ),
                        'compare' => '<=',
                        'type'    => 'NUMERIC'
                    );
                    $query->query_vars['meta_query'][] = $end_meta;
                }
            } else{
                $start_meta = array (
                    'key'     => 'em_start_date',
                    'value'   => ep_get_current_timestamp(),
                    'compare' => '>=',
                    'type'    => 'NUMERIC'
                );
                $query->query_vars['meta_query'][] = $start_meta;
            }
        }
        $post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
        if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'em_event' && isset( $_GET['filter_type'] ) && sanitize_text_field( $_GET['filter_type'] ) == 'publish_date' ) {
            if( isset( $_GET['ep_filter_date'] ) && ! empty( $_GET['ep_filter_date'] ) ) {
                $date_range = sanitize_text_field( $_GET['ep_filter_date'] );
                $dates = explode( ' - ', $date_range );
                $start_date = isset( $dates[0] ) && ! empty( $dates[0] ) ? $dates[0] : '';
                $end_date = isset( $dates[1] ) && ! empty( $dates[1] ) ? $dates[1] : '';
                if( ! empty( $start_date ) ) {
                    if(strpos($start_date, '/') !== false){
                        $start_date =  str_replace('/', '-', $start_date );
                    }elseif(strpos($start_date, '.') !== false){
                        $start_date =  str_replace('.', '-', $start_date );
                    }
                    $start_date = date('d-m-Y', strtotime( $start_date ) );
                    $start_publish = array (
                        'after' => $start_date,
                        'inclusive' => true,
                    );
                    $query->query_vars['date_query'][] = $start_publish;
                }
                if( ! empty( $end_date ) ) {
                    if(strpos($end_date, '/') !== false){
                        $end_date =  str_replace('/', '-', $end_date );
                    }elseif(strpos($end_date, '.') !== false){
                        $end_date =  str_replace('.', '-', $end_date );
                    }
                    $end_date = date('d-m-Y', strtotime("+1 day",strtotime( $end_date )) );
                    $end_publish = array (
                        'before' => $end_date,
                        'inclusive' => true,
                    );
                    $query->query_vars['date_query'][] = $end_publish;
                }
                //epd( $query->query_vars['date_query']);
            }else{
                if( $query->get('orderby') == '' ) {
                    $query->set('orderby','publish_date');
                }
                if( $query->get('order') == '' ) {
                    $query->set('order','desc');
                }
            }
        }
    }

    /*
    * Remove Date Filter
    */
    public function ep_events_filters_remove_date( $months ) {
        global $typenow;
        if ( $typenow == 'em_event' ) {
            return array();
        }
        return $months;
    }

    /*
    * Duplicate Event
    */
    public function ep_register_duplicate_event_actions( $bulk_actions ) {
        $bulk_actions['duplicate_event'] = esc_html__( 'Duplicate Event', 'eventprime-event-calendar-management' );
        return $bulk_actions;
    }

    /**
     * Duplicate event callback
     */
    public function ep_duplicate_event_bulk_action_handler( $redirect, $doaction, $object_ids ){
        if( ! empty( $object_ids ) ) {
            global $wpdb;
            $cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
            $price_options_table = $wpdb->prefix.'em_price_options';
            foreach ( $object_ids as $event_id ){
                $metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );  
                $post_id = 0;
                if( isset( $event_id ) && ! empty( $event_id ) ){
                    // get event post data
                    $event_data = get_post( $event_id );
                    $title = ! empty( $event_data->post_title ) ? sanitize_text_field( $event_data->post_title ) : '';
                    $description = ! empty( $event_data->post_content ) ? $event_data->post_content : '';
                    $status = ! empty( $event_data->post_status ) ? $event_data->post_status : 'publish';
                    $post_id = wp_insert_post(
                        array (
                            'post_type' => EM_EVENT_POST_TYPE,
                            'post_title' => $title,
                            'post_content' => $description,
                            'post_status' => $status,
                            'post_author' => get_current_user_id(),
                        )
                    );
                }
                if( $post_id ){
                    // $post = get_post( $post_id) ;
                    update_post_meta( $post_id, 'em_id', $post_id );
                    // get event meta data
                    $event = new stdClass();
                    $meta = get_post_meta( $event_id );
                    foreach ( $meta as $key => $val ) {
                        $event->{$key} = maybe_unserialize( $val[0] );
                    }
                    // event name
                    $em_name = ! empty( $event->em_name ) ? sanitize_text_field( $event->em_name ) : '';
                    update_post_meta( $post_id, 'em_name', $em_name);
                    // start date and end date
                    $em_start_date = ! empty( $event->em_start_date ) ? $event->em_start_date : '';
                    update_post_meta( $post_id, 'em_start_date', $em_start_date );
                    $em_end_date = ! empty( $event->em_end_date ) ? $event->em_end_date : '';
                    update_post_meta( $post_id, 'em_end_date', $em_end_date );
                    // start time and end time
                    $em_start_time = ! empty( $event->em_start_time ) ? $event->em_start_time : '';
                    update_post_meta( $post_id, 'em_start_time', $em_start_time );
                    $em_end_time = ! empty( $event->em_end_time ) ? $event->em_end_time : '';
                    update_post_meta( $post_id, 'em_end_time', $em_end_time );
                    // all day
                    $em_all_day = isset( $event->em_all_day ) ? $event->em_all_day : 0;
                    update_post_meta( $post_id, 'em_all_day', $em_all_day );
                    // if event is all day then end date will be same as start date
                    if( $em_all_day == 1 ) {
                        $em_end_date = $em_start_date;
                        update_post_meta( $post_id, 'em_end_date', $em_end_date );
                        $em_start_time = '12:00 AM'; $em_end_time = '11:59 PM';
                        update_post_meta( $post_id, 'em_start_time', $em_start_time );
                        update_post_meta( $post_id, 'em_end_time', $em_end_time );
                    } else{
                        if( $em_start_date > $em_end_date ) {
                            update_post_meta( $post_id, 'em_end_date', $em_start_date );
                        } else if( $em_start_date == $em_end_date ) {
                            if( $em_start_time == $em_end_time ) {
                                if( empty( $em_start_time ) ) {
                                    update_post_meta( $post_id, 'em_start_time', '12:00 AM' );
                                    update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
                                } else{
                                    if( $em_end_time !== '11:59 PM' ) {
                                        update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
                                    }
                                }
                            }
                        }
                    }
                    // update start and end datetime meta
                    $ep_date_time_format = 'Y-m-d';
                    $start_date = get_post_meta( $post_id, 'em_start_date', true );
                    $start_time = get_post_meta( $post_id, 'em_start_time', true );
                    $merge_start_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $start_date, 'Y-m-d', 1 ) . ' ' . $start_time, $ep_date_time_format, '', 0, 1 );
                    if( ! empty( $merge_start_date_time ) ) {
                        update_post_meta( $post_id, 'em_start_date_time', $merge_start_date_time );
                    }
                    $end_date = get_post_meta( $post_id, 'em_end_date', true );
                    $end_time = get_post_meta( $post_id, 'em_end_time', true );
                    $merge_end_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $end_date, 'Y-m-d', 1 ) . ' ' . $end_time, $ep_date_time_format, '', 0, 1 );
                    if( ! empty( $merge_end_date_time ) ) {
                        update_post_meta( $post_id, 'em_end_date_time', $merge_end_date_time );
                    }
                    // event type
                    $em_event_type = ! empty( $event->em_event_type ) ? $event->em_event_type : '';
                    update_post_meta( $post_id, 'em_event_type', $em_event_type );
                    wp_set_object_terms( $post_id, intval( $em_event_type ), EM_EVENT_TYPE_TAX );
                    // venue
                    $em_venue = ! empty( $event->em_venue ) ? $event->em_venue : '';
                    update_post_meta( $post_id, 'em_venue', $em_venue );
                    wp_set_object_terms( $post_id, intval( $em_venue ), EM_EVENT_VENUE_TAX );
                    // organizer
                    $em_organizer = ! empty( $event->em_organizer ) ? array_filter( $event->em_organizer ) : array();
                    update_post_meta( $post_id, 'em_organizer', $em_organizer );
                    if( ! empty( $em_organizer ) ) {
                        foreach( $em_organizer as $organizer ) {
                            if( ! empty( $organizer ) ) {
                                wp_set_object_terms( $post_id, intval( $organizer ), 'em_event_organizer' );
                            }
                        }
                    }
                    // performer
                    $em_performer = ! empty( $event->em_performer ) ? $event->em_performer : array();
                    update_post_meta( $post_id, 'em_performer', $em_performer );
                    // ticket price
                    $em_ticket_price = isset( $event->em_ticket_price ) ? sanitize_text_field( $event->em_ticket_price ) : '';
                    update_post_meta( $post_id, 'em_ticket_price', $em_ticket_price );
                    // fixed event price
                    $em_fixed_event_price = ! empty( $event->em_fixed_event_price ) ? sanitize_text_field( $event->em_fixed_event_price ) : '';
                    update_post_meta( $post_id, 'em_fixed_event_price', $em_fixed_event_price );
                    // enable booking
                    $em_enable_booking = isset( $event->em_enable_booking ) ? $event->em_enable_booking : 'bookings_off';
                    update_post_meta( $post_id, 'em_enable_booking', $em_enable_booking );
                    // custom link and meta
                    $em_custom_link = ! empty( $event->em_custom_link ) ? $event->em_custom_link : '';
                    update_post_meta( $post_id, 'em_custom_link', $em_custom_link );
                    $em_custom_meta = ! empty( $event->em_custom_meta ) ? $event->em_custom_meta : array();
                    update_post_meta( $post_id, 'em_custom_meta', $em_custom_meta );
                    // hide fields
                    $em_hide_start_time = ( isset( $event->em_hide_start_time ) && ! empty( $event->em_hide_start_time ) ) ? 1 : 0;
                    update_post_meta( $post_id, 'em_hide_event_start_time', $em_hide_start_time );
                    $em_hide_event_start_date = ( isset( $event->em_hide_event_start_date ) && ! empty( $event->em_hide_event_start_date ) ) ? 1 : 0;
                    update_post_meta( $post_id, 'em_hide_event_start_date', $em_hide_event_start_date );
                    $em_hide_event_end_time = ( isset( $event->em_hide_event_end_time ) && ! empty( $event->em_hide_event_end_time ) ) ? 1 : 0;
                    update_post_meta( $post_id, 'em_hide_event_end_time', $em_hide_event_end_time );
                    $em_hide_end_date = ( isset( $event->em_hide_end_date ) && ! empty( $event->em_hide_end_date ) ) ? 1 : 0;
                    update_post_meta( $post_id, 'em_hide_end_date', $em_hide_end_date );
                    // event image
                    $thumbnail_id = ! empty( $event->_thumbnail_id ) ? $event->_thumbnail_id : '';
                    set_post_thumbnail( $post_id, $thumbnail_id );
                    // gallery images ids
                    $em_gallery_image_ids = ! empty( $event->em_gallery_image_ids ) ? $event->em_gallery_image_ids : '';
                    update_post_meta( $post_id, 'em_gallery_image_ids', $em_gallery_image_ids );                
                    // add event more dates
                    $em_event_more_dates = ! empty( $event->em_event_more_dates ) ? 1 : 0;
                    if( $em_event_more_dates == 1 ){
                        update_post_meta( $post_id, 'em_event_more_dates', $em_event_more_dates );
                        $em_event_add_more_dates = ! empty( $event->em_event_add_more_dates ) ? $event->em_event_add_more_dates : array();
                        $event_more_dates = array();
                        if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                            foreach( $em_event_add_more_dates as $key => $more_dates ) {
                                $new_date = array();
                                $new_date['uid']    = absint( $more_dates['uid'] );
                                $new_date['date']   = sanitize_text_field( $more_dates['date'] );
                                $new_date['time']   = sanitize_text_field( $more_dates['time'] );
                                $new_date['label']  = sanitize_text_field( $more_dates['label'] );
                                $event_more_dates[] = $new_date;
                            }
                        }
                        if( ! empty( $event_more_dates ) ){
                            update_post_meta( $post_id, 'em_event_add_more_dates', $event_more_dates );
                        }
                    }
                    // fetch ticket category and ticket data from custom tables
                    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
                    $metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );
                    $em_ticket_category_data  = $event_controller->get_event_ticket_category( $event_id );
                    $solo_tickets             = $event_controller->get_event_solo_ticket( $event_id );                    
                    // save category                  
                    if( isset( $em_ticket_category_data ) && ! empty( $em_ticket_category_data ) ) {
                        $cat_priority = 1;
                        foreach( $em_ticket_category_data as $cat ) {
                            $save_data               = array();
                            $save_data['event_id']   = $post_id;
                            $save_data['name']       = $cat->name;
                            $save_data['capacity']   = $cat->capacity;
                            $save_data['priority']   = 1;
                            $save_data['status']     = 1;
                            $save_data['created_by'] = get_current_user_id();
                            $save_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
                            $result = $wpdb->insert( $cat_table_name, $save_data );
                            $cat_id = $wpdb->insert_id;
                            $cat_priority++;
                            //save tickets
                            if( isset( $cat->tickets ) && ! empty( $cat->tickets ) ) {
                                $cat_ticket_priority = 1;
                                foreach( $cat->tickets as $ticket ) {
                                    $ticket_data = array();
                                    $ticket_data['category_id']    = $cat_id;
                                    $ticket_data['event_id']       = $post_id;
                                    $ticket_data['name']           = addslashes( $ticket->name );
                                    $ticket_data['description']    = isset( $ticket->description ) ? addslashes( $ticket->description ) : '';
                                    $ticket_data['price']          = isset( $ticket->price ) ? $ticket->price : 0;
                                    $ticket_data['special_price']  = '';
                                    $ticket_data['capacity']       = isset( $ticket->capacity ) ? absint( $ticket->capacity ) : 0;
                                    $ticket_data['is_default']     = 1;
                                    $ticket_data['is_event_price'] = 0;
                                    $ticket_data['icon']           = isset( $ticket->icon ) ? absint( $ticket->icon ) : '';
                                    $ticket_data['priority']       = $cat_ticket_priority;
                                    $ticket_data['status']         = 1;
                                    $ticket_data['created_at']     = date_i18n("Y-m-d H:i:s", time());
                                    // new
                                    $ticket_data['additional_fees']    = ( isset( $ticket->ep_additional_ticket_fee_data ) && ! empty( $ticket->ep_additional_ticket_fee_data ) ) ? json_encode( $ticket->ep_additional_ticket_fee_data ) : '';
                                    $ticket_data['allow_cancellation'] = isset( $ticket->allow_cancellation ) ? absint( $ticket->allow_cancellation ) : 0;
                                    $ticket_data['show_remaining_tickets'] = isset( $ticket->show_remaining_tickets ) ? absint( $ticket->show_remaining_tickets ) : 0;
                                    // date
                                    $start_date = [];
                                    if( isset( $ticket->booking_starts ) && ! empty( $ticket->booking_starts ) ) {
                                        $booking_starts = json_decode( $ticket->booking_starts );
                                        $start_date['booking_type'] = $booking_starts->booking_type;
                                        if( $booking_starts->booking_type == 'custom_date' ) {
                                            if( isset( $booking_starts->start_date ) && ! empty( $booking_starts->start_date ) ) {
                                                $start_date['start_date'] = $booking_starts->start_date;
                                            }
                                            if( isset( $booking_starts->start_time ) && ! empty( $booking_starts->start_time ) ) {
                                                $start_date['start_time'] = $booking_starts->start_time;
                                            }
                                        } elseif( $booking_starts->booking_type == 'event_date' ) {
                                            $start_date['event_option'] = $booking_starts->event_option;
                                        } elseif( $booking_starts->booking_type == 'relative_date' ) {
                                            if( isset( $booking_starts->days ) && ! empty( $booking_starts->days ) ) {
                                                $start_date['days'] = $booking_starts->days;
                                            }
                                            if( isset( $booking_starts->days_option ) && ! empty( $booking_starts->days_option ) ) {
                                                $start_date['days_option'] = $booking_starts->days_option;
                                            }
                                            $start_date['event_option'] = $booking_starts->event_option;
                                        }
                                    }
                                    $ticket_data['booking_starts'] = json_encode( $start_date );
                                    // end date
                                    $end_date = [];
                                    if( isset( $ticket->booking_ends ) && ! empty( $ticket->booking_ends ) ) {
                                        $booking_ends = json_decode( $ticket->booking_ends );
                                        $end_date['booking_type'] = $booking_ends->booking_type;
                                        if( $booking_ends->booking_type == 'custom_date' ) {
                                            if( isset( $booking_ends->end_date ) && ! empty( $booking_ends->end_date ) ) {
                                                $end_date['end_date'] = $booking_ends->end_date;
                                            }
                                            if( isset( $booking_ends->end_time ) && ! empty( $booking_ends->end_time ) ) {
                                                $end_date['end_time'] = $booking_ends->end_time;
                                            }
                                        } elseif( $booking_ends->booking_type == 'event_date' ) {
                                            $end_date['event_option'] = $booking_ends->event_option;
                                        } elseif( $booking_ends->booking_type == 'relative_date' ) {
                                            if( isset( $booking_ends->end_date ) && ! empty( $booking_ends->end_date ) ) {
                                                $end_date['days'] = $booking_ends->end_date;
                                            }
                                            if( isset( $booking_ends->end_time ) && ! empty( $booking_ends->end_time ) ) {
                                                $end_date['days_option'] = $booking_ends->end_time;
                                            }
                                            $end_date['event_option'] = $booking_ends->event_option;
                                        }
                                    }
                                    $ticket_data['booking_ends'] = json_encode( $end_date );
                                    $ticket_data['show_ticket_booking_dates'] = ( isset( $ticket->show_ticket_booking_dates ) ) ? 1 : 0;
                                    $ticket_data['min_ticket_no'] = isset( $ticket->min_ticket_no ) ? $ticket->min_ticket_no : 0;
                                    $ticket_data['max_ticket_no'] = isset( $ticket->max_ticket_no ) ? $ticket->max_ticket_no : 0;
                                    // offer
                                    if( isset( $ticket->offers ) && ! empty( $ticket->offers ) ) {
                                        $ticket_data['offers'] = $ticket->offers;
                                    }
                                    $ticket_data['multiple_offers_option'] = ( isset( $ticket->multiple_offers_option ) && !empty( $ticket->multiple_offers_option ) ) ? $ticket->multiple_offers_option : '';
                                    $ticket_data['multiple_offers_max_discount'] = ( isset( $ticket->multiple_offers_max_discount ) && !empty( $ticket->multiple_offers_max_discount ) ) ? $ticket->multiple_offers_max_discount : '';
                                    $ticket_data['ticket_template_id'] = ( isset( $ticket->ticket_template_id ) && !empty( $ticket->ticket_template_id ) ) ? $ticket->ticket_template_id : '';
                                    $result = $wpdb->insert( $price_options_table, $ticket_data );                                    
                                    $cat_ticket_priority++;
                                }
                                update_post_meta( $post_id, 'em_enable_booking', 'bookings_on' );
                            }
                        }
                    }
                    // save tickets
                    if( isset( $solo_tickets ) && ! empty( $solo_tickets ) ) {
                        $tic = 0;
                        foreach( $solo_tickets as $ticket ) {
                            $ticket_data = array();
                            $ticket_data['category_id']    = 0;
                            $ticket_data['event_id']       = $post_id;
                            $ticket_data['name']           = addslashes( $ticket->name );
                            $ticket_data['description']    = isset( $ticket->description ) ? addslashes( $ticket->description ) : '';
                            $ticket_data['price']          = isset( $ticket->price ) ? $ticket->price : 0;
                            $ticket_data['special_price']  = '';
                            $ticket_data['capacity']       = isset( $ticket->capacity ) ? absint( $ticket->capacity ) : 0;
                            $ticket_data['is_default']     = 1;
                            $ticket_data['is_event_price'] = 0;
                            $ticket_data['icon']           = isset( $ticket->icon ) ? absint( $ticket->icon ) : '';
                            $ticket_data['priority']       = $cat_ticket_priority;
                            $ticket_data['status']         = 1;
                            $ticket_data['created_at']     = date_i18n("Y-m-d H:i:s", time());
                            // new
                            $ticket_data['additional_fees']    = ( isset( $ticket->ep_additional_ticket_fee_data ) && ! empty( $ticket->ep_additional_ticket_fee_data ) ) ? json_encode( $ticket->ep_additional_ticket_fee_data ) : '';
                            $ticket_data['allow_cancellation'] = isset( $ticket->allow_cancellation ) ? absint( $ticket->allow_cancellation ) : 0;
                            $ticket_data['show_remaining_tickets'] = isset( $ticket->show_remaining_tickets ) ? absint( $ticket->show_remaining_tickets ) : 0;
                            // date
                            $start_date = [];
                            if( isset( $ticket->booking_starts ) && ! empty( $ticket->booking_starts ) ) {
                                $booking_starts = json_decode( $ticket->booking_starts );
                                $start_date['booking_type'] = $booking_starts->booking_type;
                                if( $booking_starts->booking_type == 'custom_date' ) {
                                    if( isset( $booking_starts->start_date ) && ! empty( $booking_starts->start_date ) ) {
                                        $start_date['start_date'] = $booking_starts->start_date;
                                    }
                                    if( isset( $booking_starts->start_time ) && ! empty( $booking_starts->start_time ) ) {
                                        $start_date['start_time'] = $booking_starts->start_time;
                                    }
                                } elseif( $booking_starts->booking_type == 'event_date' ) {
                                    $start_date['event_option'] = $booking_starts->event_option;
                                } elseif( $booking_starts->booking_type == 'relative_date' ) {
                                    if( isset( $booking_starts->days ) && ! empty( $booking_starts->days ) ) {
                                        $start_date['days'] = $booking_starts->days;
                                    }
                                    if( isset( $booking_starts->days_option ) && ! empty( $booking_starts->days_option ) ) {
                                        $start_date['days_option'] = $booking_starts->days_option;
                                    }
                                    $start_date['event_option'] = $booking_starts->event_option;
                                }
                            }
                            $ticket_data['booking_starts'] = json_encode( $start_date );
                            // end date
                            $end_date = [];
                            if( isset( $ticket->booking_ends ) && ! empty( $ticket->booking_ends ) ) {
                                $booking_ends = json_decode( $ticket->booking_ends );
                                $end_date['booking_type'] = $booking_ends->booking_type;
                                if( $booking_ends->booking_type == 'custom_date' ) {
                                    if( isset( $booking_ends->end_date ) && ! empty( $booking_ends->end_date ) ) {
                                        $end_date['end_date'] = $booking_ends->end_date;
                                    }
                                    if( isset( $booking_ends->end_time ) && ! empty( $booking_ends->end_time ) ) {
                                        $end_date['end_time'] = $booking_ends->end_time;
                                    }
                                } elseif( $booking_ends->booking_type == 'event_date' ) {
                                    $end_date['event_option'] = $booking_ends->event_option;
                                } elseif( $booking_ends->booking_type == 'relative_date' ) {
                                    if( isset( $booking_ends->end_date ) && ! empty( $booking_ends->end_date ) ) {
                                        $end_date['days'] = $booking_ends->end_date;
                                    }
                                    if( isset( $booking_ends->end_time ) && ! empty( $booking_ends->end_time ) ) {
                                        $end_date['days_option'] = $booking_ends->end_time;
                                    }
                                    $end_date['event_option'] = $booking_ends->event_option;
                                }
                            }
                            $ticket_data['booking_ends'] = json_encode( $end_date );
                            $ticket_data['show_ticket_booking_dates'] = ( isset( $ticket->show_ticket_booking_dates ) ) ? 1 : 0;
                            $ticket_data['min_ticket_no'] = isset( $ticket->min_ticket_no ) ? $ticket->min_ticket_no : 0;
                            $ticket_data['max_ticket_no'] = isset( $ticket->max_ticket_no ) ? $ticket->max_ticket_no : 0;
                            // offer
                            if( isset( $ticket->offers ) && ! empty( $ticket->offers ) ) {
                                $ticket_data['offers'] = $ticket->offers;
                            }
                            $ticket_data['multiple_offers_option'] = ( isset( $ticket->multiple_offers_option ) && !empty( $ticket->multiple_offers_option ) ) ? $ticket->multiple_offers_option : '';
                            $ticket_data['multiple_offers_max_discount'] = ( isset( $ticket->multiple_offers_max_discount ) && !empty( $ticket->multiple_offers_max_discount ) ) ? $ticket->multiple_offers_max_discount : '';
                            $ticket_data['ticket_template_id'] = ( isset( $ticket->ticket_template_id ) && !empty( $ticket->ticket_template_id ) ) ? $ticket->ticket_template_id : '';
                            $result = $wpdb->insert( $price_options_table, $ticket_data );
                            $tic++;
                        }
                    }
                    // add other settings meta box
                    $em_event_text_color = ! empty( $event->em_event_text_color ) ? sanitize_text_field( $event->em_event_text_color ) : '';
                    update_post_meta( $post_id, 'em_event_text_color', $em_event_text_color );
                    $em_audience_notice = ! empty( $event->em_audience_notice ) ? sanitize_textarea_field( $event->em_audience_notice ) : '';
                    update_post_meta( $post_id, 'em_audience_notice', $em_audience_notice );
                    // hook for extension data
                    do_action( 'ep_duplicate_event_extension_data', $event, $post_id );
                }
            }
        }
        wp_redirect( $redirect );
    }
    
}

new EventM_Events_Admin();
