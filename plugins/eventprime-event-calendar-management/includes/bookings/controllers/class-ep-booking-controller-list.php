<?php
/**
 * Class for booking module
 */

defined( 'ABSPATH' ) || exit;

class EventM_Booking_Controller_List {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $post_type = EM_BOOKING_POST_TYPE;
    /**
     * Instance
     *
     * @var Instance
     */
    public static $instance = null;
    
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load order detail
     * 
     * @param int $order_id Order Id.
     * 
     * @param bool $with_event Load booking with or without event.
     * 
     * @return object $order.
     */
    public function load_booking_detail( $order_id, $with_event = true ) {
        if( empty( $order_id ) ) return;

        $post = get_post( $order_id );
        if( empty( $post ) ) return;
        
        $booking = new stdClass();
        
        $meta = get_post_meta( $order_id );
        foreach ( $meta as $key => $val ) {
            $booking->{$key} = maybe_unserialize( $val[0] );
        }

        $detail_url = get_permalink( ep_get_global_settings( 'booking_details_page' ) );
        $booking->booking_detail_url = add_query_arg( array( 'order_id' => $order_id ), $detail_url );
        $booking->post_data = $post;
        $booking->event_data = array();
        if( ! empty( $with_event ) ) {
            // load event data
            $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
            $booking->event_data = $event_controller->get_single_event( $booking->em_event );
        }
        
        return $booking;
    }
    
    /**
     * Get post data
     */
    public function get_bookings_post_data( $args = array(), $with_event = true ) {
        $default = array(
            'orderby'   => 'date',
            'order'       => 'ASC',
            'post_type'   => $this->post_type,
            'numberposts' => -1,
            'offset'      => 0
        );
        $args = wp_parse_args( $args, $default );
        $posts = get_posts( $args );
        if( empty( $posts ) )
           return array();
       
        $bookings = array();
        foreach( $posts as $post ) {
            if( empty( $post ) || empty( $post->ID ) ) continue;
            $booking = $this->load_booking_detail( $post->ID, $with_event );
            if( ! empty( $booking ) ) {
                $bookings[] = $booking;
            }
        }

        $wp_query = new WP_Query( $args );
        $wp_query->posts = $bookings;

        return $wp_query;
    }
    /**
     * Render template on the frontend
     */
    public function render_template( $atts = array() ) {
        $booking_data = array();$previous_event_url = '';
        if( isset( $_POST['action'] ) && 'edit_booking' == sanitize_text_field( $_POST['action'] ) ) {
            $booking_data['ep_nonce_verified'] = false;
            if( wp_verify_nonce( $_POST['ep_edit_event_booking_nonce'], 'ep_edit_event_booking_action' ) ) {
                $booking_data['ep_nonce_verified'] = true;
                if( ! empty( $_POST['booking_id'] ) ) {
                    $booking_id = absint( $_POST['booking_id'] );
                    $single_booking = $this->load_booking_detail( $booking_id );
                    if( ! empty( $single_booking->em_user ) ) {
                        if( $single_booking->em_user == get_current_user_id() ) {
                            $booking_data['booking_data'] = $single_booking;
                            $booking_data = apply_filters( 'ep_booking_edit_booking_data', $booking_data, $_POST );
                        }
                    }
                }
            }
            ob_start();

            wp_enqueue_style(
                'ep-booking-checkout-style',
                EP_BASE_URL . '/includes/bookings/assets/css/ep-frontend-booking-checkout.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-event-booking-script',
                EP_BASE_URL . '/includes/bookings/assets/js/ep-event-booking.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            $checkout_text = ep_global_settings_button_title('Checkout');
            wp_localize_script(
                'ep-event-booking-script', 
                'ep_event_booking', 
                array(
                    'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
                    'confirm_booking_text'            => esc_html__( 'Confirm Booking', 'eventprime-event-calendar-management' ),
                    'checkout_text'                   => $checkout_text,
                    'flush_booking_timer_nonce'       => wp_create_nonce( 'flush_event_booking_timer_nonce' ),
                    'booking_item_expired'            => esc_html__( 'Your cart has expired. Redirecting..', 'eventprime-event-calendar-management' ),
                    'previous_event_url'              => $previous_event_url,
                    'event_page_url'                  => esc_url( get_permalink( ep_get_global_settings( 'event_page' ) ) ),
                    'is_payment_method_enabled'       => em_is_payment_gateway_enabled(),
                    'booking_data'                    => $booking_data,
                    'enabled_guest_booking'           => ep_enabled_guest_booking(),
                    'enabled_woocommerce_integration' => ep_enabled_woocommerce_integration(),
                    'enabled_woocommerce_checkout'    => ep_enabled_woocommerce_checkout(),
                )
            );

            ep_get_template_part( 'bookings/edit-booking', null, (object)$booking_data );
            return ob_get_clean();
        } else{
            if( ! empty( $_POST ) && isset( $_POST['ep_event_booking_data'] ) && ! empty( $_POST['ep_event_booking_data'] ) ) {
                if( '0' === get_option( 'ep_event_booking_timer_start' ) ) {
                    delete_option( 'ep_event_booking_timer_start' );
                    $_POST = array();
                } else if( FALSE === get_option( 'ep_event_booking_timer_start' ) ) {
                    add_option( 'ep_event_booking_timer_start', 1 );
                }
                $ep_event_booking_data = json_decode( stripslashes( $_POST['ep_event_booking_data'] ) );
                if( ! empty( $ep_event_booking_data->ticket ) ) {
                    $booking_data['tickets'] = json_decode( $ep_event_booking_data->ticket );
                }
                $ep_event_offer_data = isset( $_POST['ep_event_offer_data'] ) ? json_decode( stripslashes( $_POST['ep_event_offer_data'] ) ) : '';
                if( ! empty($ep_event_offer_data) ) {
                    $booking_data['ep_event_offer_data'] = $ep_event_offer_data;
                }
                if( ! empty( $ep_event_booking_data->event ) ) {
                    $event_id = base64_decode( $ep_event_booking_data->event );
                    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
                    $booking_data['event'] = $event_controller->get_single_event( $event_id );
                    $previous_event_url = $booking_data['event']->event_url;
                }
                // add data in booking data
                $booking_data = apply_filters( 'ep_booking_detail_add_booking_data', $booking_data, $ep_event_booking_data );
            }
            $register_fname = ep_get_global_settings( 'checkout_register_fname' );
            $register_lname = ep_get_global_settings( 'checkout_register_lname' );
            $register_username = ep_get_global_settings( 'checkout_register_username' );
            $register_email = ep_get_global_settings( 'checkout_register_email' );
            $register_password = ep_get_global_settings( 'checkout_register_password' );
            $account_settings = array(
                'fname_label'    =>  isset($register_fname['label']) && !empty($register_fname['label']) ? $register_fname['label'] : esc_html__('First Name','eventprime-event-calendar-management'),
                'lname_label'    =>  isset($register_lname['label']) && !empty($register_lname['label']) ? $register_lname['label'] : esc_html__('last Name','eventprime-event-calendar-management'),
                'username_label' =>  isset($register_username['label']) && !empty($register_username['label']) ? $register_username['label'] : esc_html__('Username','eventprime-event-calendar-management'),
                'email_label'    =>  isset($register_email['label']) && !empty($register_email['label']) ? $register_email['label'] : esc_html__('Email','eventprime-event-calendar-management'),
                'password_label' =>  isset($register_password['label']) && !empty($register_password['label']) ? $register_password['label'] : esc_html__('Password','eventprime-event-calendar-management')
            );
            $booking_data['account_form'] = (object)$account_settings;
            
            $create_account_validation = array(
                'fname_required'     => sprintf(__("%s is required.", 'eventprime-event-calendar-management'), $booking_data['account_form']->fname_label),
                'lname_required'     => sprintf(__("%s is required.", 'eventprime-event-calendar-management'), $booking_data['account_form']->lname_label),
                'email_required'     => sprintf(__("%s is required.", 'eventprime-event-calendar-management'), $booking_data['account_form']->email_label),
                'username_required'  => sprintf(__("%s is required.", 'eventprime-event-calendar-management'), $booking_data['account_form']->username_label),
                'password_required'  => sprintf(__("%s is required.", 'eventprime-event-calendar-management'), $booking_data['account_form']->password_label),
                'email_duplicate'    => sprintf(__("%s is already exists.", 'eventprime-event-calendar-management'), $booking_data['account_form']->email_label),
                'username_duplicate' => sprintf(__("%s is already exist.", 'eventprime-event-calendar-management'), $booking_data['account_form']->username_label)
            );

            ob_start();

            wp_enqueue_style(
                'ep-booking-checkout-style',
                EP_BASE_URL . '/includes/bookings/assets/css/ep-frontend-booking-checkout.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-event-booking-script',
                EP_BASE_URL . '/includes/bookings/assets/js/ep-event-booking.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );
            $checkout_text = ep_global_settings_button_title('Checkout');
            $default_payment_processor = ep_get_global_settings( 'default_payment_processor' );
            if( empty( $default_payment_processor ) ) {
                $default_payment_processor = 'paypal_processor';
            }
            // check for extensions
            if( ! empty( $default_payment_processor ) && 'paypal_processor' !== $default_payment_processor ) {
                $extensions = EP()->extensions;
                if( ! in_array( 'offline_payments', $extensions ) && ! in_array( 'stripe', $extensions ) ) {
                    $default_payment_processor = 'paypal_processor';
                }
                // check if other payments options disabled
                if( empty( ep_get_global_settings( 'offline_processor' ) ) && empty( ep_get_global_settings( 'stripe_processor' ) ) ) {
                    $default_payment_processor = 'paypal_processor';
                }
            }
            wp_localize_script(
                'ep-event-booking-script', 
                'ep_event_booking', 
                array(
                    'ajaxurl'                         => admin_url( 'admin-ajax.php' ),
                    'confirm_booking_text'            => esc_html__( 'Confirm Booking', 'eventprime-event-calendar-management' ),
                    'checkout_text'                   => $checkout_text,
                    'flush_booking_timer_nonce'       => wp_create_nonce( 'flush_event_booking_timer_nonce' ),
                    'booking_item_expired'            => esc_html__( 'Your cart has expired. Redirecting..', 'eventprime-event-calendar-management' ),
                    'previous_event_url'              => $previous_event_url,
                    'event_page_url'                  => esc_url( get_permalink( ep_get_global_settings( 'event_page' ) ) ),
                    'is_payment_method_enabled'       => em_is_payment_gateway_enabled(),
                    'booking_data'                    => $booking_data,
                    'enabled_guest_booking'           => ep_enabled_guest_booking(),
                    'enabled_woocommerce_integration' => ep_enabled_woocommerce_integration(),
                    'create_account_validation'       => $create_account_validation,
                    'event_registration_form_nonce'   => wp_create_nonce( 'event-registration-form-nonce' ),
                    'reload_user_area_nonce'          => wp_create_nonce( 'event-reload-checkout-user-area' ),
                    'enable_captcha_registration'     => ep_enabled_reg_captcha(),
                    'default_payment_processor'       => $default_payment_processor,
                    'enabled_woocommerce_checkout'    => ep_enabled_woocommerce_checkout(),
                )
            );

            ep_get_template_part( 'bookings/checkout', null, (object)$booking_data );
            return ob_get_clean();
        }
    }

    /**
     * Render booking detail template on the frontend
     */
    public function render_booking_detail_template( $atts = array() ) {
        $booking_data = array();
        if( isset( $_GET['order_id'] ) && ! empty( $_GET['order_id'] ) ) {
            $order_id = absint( $_GET['order_id'] );
            $booking_data = $this->load_booking_detail( $order_id );
        }
        ob_start();

        wp_enqueue_style(
            'ep-booking-checkout-style',
            EP_BASE_URL . '/includes/bookings/assets/css/ep-frontend-booking-checkout.css',
            false, EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-event-booking-detail-script',
            EP_BASE_URL . '/includes/bookings/assets/js/ep-event-booking-detail.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_localize_script(
            'ep-event-booking-detail-script', 
            'ep_event_booking_detail', 
            array(
                'ajaxurl'              => admin_url( 'admin-ajax.php' ),
                'booking_cancel_nonce' => wp_create_nonce( 'event-booking-cancellation-nonce' ),
                'booking_print_ticket_nonce' => wp_create_nonce( 'event-booking-print-ticket-nonce' )
            )
        );

        // enqueue custom scripts and styles from extension
        do_action( 'ep_bookingh_detail_enqueue_custom_scripts' );

		ep_get_template_part( 'bookings/booking-detail', null, (object)$booking_data );
		return ob_get_clean();
    }

    /**
     * Confirm Booking
     * 
     * @param int $booking_id Booking ID.
     * 
     * @param array $data Payment Data.
     */
    public function confirm_booking( $booking_id, $data = array() ) {
        $booking = $this->load_booking_detail( $booking_id );
        if( empty( $booking->em_id ) ) return;

        // update booking status
        $booking_status = $this->ep_get_event_booking_status( $booking_id, $data );
        $postData = [ 'ID' => $booking->em_id, 'post_status' => $booking_status ];
        wp_update_post( $postData );

        update_post_meta( $booking->em_id, 'em_status', $booking_status );

        do_action( 'ep_after_update_booking_status', $booking->em_id, $data );

        // update order info
        $order_info = apply_filters( 'ep_add_booking_order_info', $booking->em_order_info, $data );
        $order_info['payment_gateway'] = $data['payment_gateway'];
        update_post_meta( $booking->em_id, 'em_order_info', $order_info );

        // update payment log
        $payment_log = apply_filters( 'ep_add_booking_payment_log', $data );
        update_post_meta( $booking->em_id, 'em_payment_log', $payment_log );

        do_action( 'ep_after_booking_complete', $booking->em_id, $data );

        // send email notification
        if ( strtolower( $data['payment_status'] ) == 'completed' ) {
            EventM_Notification_Service::booking_confirmed( $booking->em_id );
        } else if ( strtolower( $data['payment_status'] ) == 'refunded' ) {
            do_action( 'ep_booking_refunded', $booking );
            EventM_Notification_Service::booking_refund( $booking->em_id );
        } else {
            EventM_Notification_Service::booking_pending( $booking->em_id );
        }
    }

    /**
     * Get user upcoming bookings
     * 
     * @param int $user_id User Id.
     * 
     * @return array
     */
    public function get_user_upcoming_bookings( $user_id ) {
        $args = array(
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_status' => 'any',
            'meta_query'  => array(
                'relation' => 'AND',
                array(
                    'key'     => 'em_user', 
                    'value'   => $user_id, 
                    'compare' => '=', 
                    'type'    => 'NUMERIC,'
                ),
                array(
                    'key'     => 'em_status', 
                    'value'   => 'completed', 
                    'compare' => 'LIKE', 
                ),
            ),
            'post_type'   => $this->post_type
        );
        $bookings = get_posts( $args );
        $upcoming_bookings = array();
        if( ! empty( $bookings ) && count( $bookings ) > 0 ) {
            $booked_events = array();
            foreach( $bookings as $booking ) {
                $booking_event_id = get_post_meta( $booking->ID, 'em_event', true );
                if( ! empty( $booking_event_id ) ) {
                    $event_start_date = get_post_meta( $booking_event_id, 'em_start_date', true );
                    if( $event_start_date > current_time( 'timestamp' ) ) {
                        if( ! in_array( $booking_event_id, $booked_events ) ) {
                            $booked_events[] = $booking_event_id;
                            $event_booking_data = $this->load_booking_detail( $booking->ID );
                            $event_booking_data->running_status = 'upcoming';
                            $upcoming_bookings[] = $event_booking_data;
                        }
                    } else{
                        $event_end_date = get_post_meta( $booking_event_id, 'em_end_date', true );
                        if( ! empty( $event_end_date ) ) {
                            if( $event_end_date > current_time( 'timestamp' ) ) {
                                if( ! in_array( $booking_event_id, $booked_events ) ) {
                                    $booked_events[] = $booking_event_id;
                                    $event_booking_data = $this->load_booking_detail( $booking->ID );
                                    $event_booking_data->running_status = 'ongoing';
                                    $upcoming_bookings[] = $event_booking_data;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $upcoming_bookings;
    }

    /**
     * Get user all bookings
     * 
     * @param int $user_id User Id.
     * 
     * @return array
     */
    public function get_user_all_bookings( $user_id ) {
        $args = array(
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_status' => 'any',
            'meta_query'  => array(
                'relation' => 'AND',
                array(
                    'key'     => 'em_user', 
                    'value'   => $user_id, 
                    'compare' => '=', 
                    'type'    => 'NUMERIC,'
                ),
            ),
            'post_type'   => $this->post_type
        );
        $bookings = get_posts( $args );
        $all_bookings = array();
        if( ! empty( $bookings ) && count( $bookings ) > 0 ) {
            foreach( $bookings as $booking ) {
                $all_bookings[] = $this->load_booking_detail( $booking->ID );
            }
        }
        return $all_bookings;
    }

    
    /*
     * Add note
     * @param $booking_id, str $note
     * retrun $reponse html;
     */
    public function add_notes($booking_id, $note){
        $response = array( 'message' => esc_html__( 'Successfully added.', 'eventprime-event-calendar-management' ), 'note' => $note );
        $notes = maybe_unserialize( get_post_meta( $booking_id, 'em_notes', true ) );
        if( is_array( $notes ) ) {
            $notes[] = $note;
        }else{
            $notes = array($note);
        }
        update_post_meta( $booking_id, 'em_notes', $notes );
        return $response;
    }
    
    /*
     * Update Order Status
     * @param $booking_id, str status
     * retrun $reponse html;
     */
    
    public function update_status($booking_id, $status = 'pending'){
        $response = array( 'message'=> esc_html__( 'Updated Successfully.', 'eventprime-event-calendar-management' ) );
        if($status == 'refunded'){
            if ( class_exists( 'EventM_Live_Seating_List_Controller' ) ) {
                $seating_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Live_Seating_List_Controller' );
                $seating_controller->refund_and_cancelled_seats_handler( $booking_id ); 
            }
            return $this->mark_booking_refunded($booking_id);
        }
        $postData = array( 'ID' => $booking_id, 'post_status' => $status );
        wp_update_post( $postData );

        update_post_meta( $booking_id, 'em_status', $status );

        $booking = $this->load_booking_detail( $booking_id );
        // update booking transient
        $key = 'ep_admin_all_bookings_data';
        $all_booking_data = ( ! empty( get_transient( $key ) ) ? get_transient( $key ) : array() );
        $all_booking_data[$booking_id] = $booking;
        set_transient( $key, $all_booking_data, 3600 );
        
        if ( strtolower( $status ) == 'completed' || strtolower( $status ) == 'publish' ) {
            EventM_Notification_Service::booking_confirmed( $booking_id );
        } else if ( strtolower( $status ) == 'refunded' ) {
            do_action( 'ep_booking_refunded', $booking );
            EventM_Notification_Service::booking_refund( $booking_id );
        } else if ( strtolower( $status ) == 'cancelled' ) {
            if ( class_exists( 'EventM_Live_Seating_List_Controller' ) ) {
                $seating_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Live_Seating_List_Controller' );
                $seating_controller->refund_and_cancelled_seats_handler( $booking_id ); 
            }
            EventM_Notification_Service::booking_cancel( $booking_id );
        } else {
            EventM_Notification_Service::booking_pending( $booking_id );
        }
        
        return $response;
    }
    
    /*
     * Update Order Status
     * @param $booking_id
     * retrun $reponse html;
     */
    public function mark_booking_refunded($booking_id){
        $response = array( 'message' => esc_html__( 'Successfully refunded.', 'eventprime-event-calendar-management' ) );
        $booking = $this->load_booking_detail( $booking_id );
        $refunded = apply_filters( 'ep_booking_refunded', true, $booking );
        if($refunded){
            $postData = array( 'ID' => $booking_id, 'post_status' => 'refunded' );
            wp_update_post( $postData );
            update_post_meta( $booking_id, 'em_status', 'refunded' );
            $booking = $this->load_booking_detail( $booking_id );
            // update booking transient
            $booking_meta_box_class = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Admin_Meta_Boxes' );
            $booking_meta_box_class->set_booking_cache( $booking_id, $booking );

            EventM_Notification_Service::booking_refund( $booking_id );
        }else{
            $response = array( 'message' => esc_html__( 'Something went wrong.', 'eventprime-event-calendar-management' ) );
        }
        return $response;
    }
    
    /**
     * Check event booking by user id
     * 
     * @param int $event_id Event ID.
     * 
     * @param int $user_id User ID.
     * 
     * @return int Booking ID.
     */
    public function check_event_booking_by_user( $event_id, $user_id ){
        $booking_id = '';
        if( ! empty( $event_id ) && ! empty( $user_id ) ) {
            $args = array(
                'numberposts' => -1,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'post_status' => 'any',
                'meta_query'  => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'em_user', 
                        'value'   => $user_id, 
                        'compare' => '=', 
                        'type'    => 'NUMERIC,'
                    ),
                    array(
                        'key'     => 'em_event', 
                        'value'   => $event_id, 
                        'compare' => '=', 
                        'type'    => 'NUMERIC,'
                    ),
                ),
                'post_type'   => $this->post_type
            );
            $bookings = get_posts( $args );
            if( ! empty( $bookings ) && count( $bookings ) > 0 ) {
                $booking_id = $bookings[0]->ID;
            }
        }
        return $booking_id;
    }

    /**
     * Check event booking by user id
     * 
     * @param int $event_id Event ID.
     * 
     * @return array Bookings.
     */
    public function get_event_bookings_by_event_id( $event_id ){
        $bookings = array();
        if( ! empty( $event_id ) ) {
            $args = array(
                'numberposts' => -1,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'post_status' => 'completed',
                'meta_query'  => array(
                    array(
                        'key'     => 'em_event', 
                        'value'   => $event_id, 
                        'compare' => '=', 
                        'type'    => 'NUMERIC,'
                    ),
                ),
                'post_type'   => $this->post_type
            );
            $bookings = get_posts( $args );
        }
        return $bookings;
    }
    
    public function update_offline_payment_status($booking_id, $status){
        $response = array( 'message' =>esc_html__('Updated Successfully.', 'eventprime-event-calendar-management'),'status'=>$status);
        // update booking status
        $booking = $this->load_booking_detail( $booking_id );
        if( empty( $booking->em_id ) ) return;
        $payment_log = isset($booking->em_payment_log) ? $booking->em_payment_log : array();
        $payment_log['offline_status'] = $status;
        update_post_meta($booking_id,'em_payment_log', $payment_log);
        
        if( strtolower( $status ) == 'cancelled' ){
            $postData = [ 'ID' => $booking_id, 'post_status' => 'cancelled'];
            wp_update_post( $postData );   
            update_post_meta( $booking_id, 'em_status', 'cancelled' );
        }
        // update booking transient
        $booking = $this->load_booking_detail( $booking_id );
        $booking_meta_box_class = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Admin_Meta_Boxes' );
        $booking_meta_box_class->set_booking_cache( $booking_id, $booking );
        
        return $response;
    }
    
    /*
     * Export Bookings
     */
    public function export_bookings_bulk_action($action_type='all_export',$post_ids = array()){
        if($action_type == 'selected_export'){
            $args = array(
                'post_status' => array('completed','pending','cancelled','refunded','published'),
                'post__in' => $post_ids
            );
            $bookings = $this->get_bookings_post_data( $args );
            if( isset( $bookings->posts ) && count( $bookings->posts ) > 0 ) {
                $this->process_booking_downloadable_csv( $bookings->posts );
            }
        }
    }
    
    /*
     * Eport All bookings 
     */
    public function export_bookings_all($filters){
        $args = array(
            'post_status' => array('completed','pending','cancelled','refunded','published'),
            'meta_query' => array('relation'=>'AND')
        );
        if(isset($filters['status']) && sanitize_text_field($filters['status']) != 'all'){
            $args['post_status'] = array(sanitize_text_field($filters['status']));
        }
        if(isset($filters['event_id']) && sanitize_text_field($filters['event_id']) != 'all'){
            $args['meta_query'][] = array(
                'key'     => 'em_event',
                'value'   => sanitize_text_field($filters['event_id']),
                'compare' => '=',
                'type'    => 'NUMERIC'
            );
        }
        if(isset($filters['pay_method']) && sanitize_text_field($filters['pay_method']) != 'all'){
            $args['meta_query'][] = array(
                'key'     => 'em_payment_method',
                'value'   => sanitize_text_field($filters['pay_method']),
                'compare' => '=',
            );
        }
        if(isset($filters['start_date']) && !empty($filters['start_date'])){
            $start_date = $filters['start_date'];
            
            $args['meta_query'][] = array(
                'key'     => 'em_date',
                'value'   => strtotime($start_date),
                'compare' => '>=',
                'type'=>'NUMERIC'
            );
        }
        if(isset($filters['end_date']) && !empty($filters['end_date'])){
            $end_date = $filters['end_date'];
            
            $args['meta_query'][] = array(
                'key'     => 'em_date',
                'value'   => strtotime($end_date),
                'compare' => '<=',
                'type'=>'NUMERIC'
            );
        }
        $bookings = $this->get_bookings_post_data( $args );
        if( isset( $bookings->posts ) && count( $bookings->posts ) > 0 ) {
            return $this->process_booking_downloadable_csv( $bookings->posts );
        }
        return;
    }
    
    /*
     * Download Bookings CSV
     */
    public function process_booking_downloadable_csv( $bookings ) {
        $bookings_data = array(); 
        $bookings_data[0]['id'] =              __('Booking ID', 'eventprime-event-calendar-management');
        $bookings_data[0]['user_name'] =       __('User Name', 'eventprime-event-calendar-management');
        $bookings_data[0]['email'] =           __('Email', 'eventprime-event-calendar-management');
        $bookings_data[0]['event'] =           __('Event Name', 'eventprime-event-calendar-management');
        $bookings_data[0]['sdate'] =           __('Start Date', 'eventprime-event-calendar-management');
        $bookings_data[0]['stime'] =           __('Start Time', 'eventprime-event-calendar-management');
        $bookings_data[0]['edate'] =           __('End Date', 'eventprime-event-calendar-management');
        $bookings_data[0]['etime'] =           __('End Time', 'eventprime-event-calendar-management');
        $bookings_data[0]['event_type'] =      __('Event Type', 'eventprime-event-calendar-management');
        $bookings_data[0]['venue'] =           __('Venue', 'eventprime-event-calendar-management');
        $bookings_data[0]['address'] =         __('Address', 'eventprime-event-calendar-management');
        $bookings_data[0]['seat_type'] =       __('Seating Type', 'eventprime-event-calendar-management');
        $bookings_data[0]['attendees'] =       __('Attendees', 'eventprime-event-calendar-management');
        $bookings_data[0]['seat'] =            __('Seat No.', 'eventprime-event-calendar-management');
        $bookings_data[0]['currency'] =        __('Currency', 'eventprime-event-calendar-management');
        $bookings_data[0]['price'] =           __('Price', 'eventprime-event-calendar-management');
        $bookings_data[0]['attendees_count'] = __('Ticket Count', 'eventprime-event-calendar-management');
        $bookings_data[0]['subtotal'] =        __('Subtotal', 'eventprime-event-calendar-management');
        $bookings_data[0]['event_price'] =     __('Fixed Event Price', 'eventprime-event-calendar-management');
        $bookings_data[0]['discount'] =        __('Discount', 'eventprime-event-calendar-management');
        $bookings_data[0]['amount_received'] = __('Amount Received', 'eventprime-event-calendar-management');
        $bookings_data[0]['gateway'] =         __('Payment Gateway', 'eventprime-event-calendar-management');
        $bookings_data[0]['booking_status'] =  __('Booking Status', 'eventprime-event-calendar-management');
        $bookings_data[0]['payment_status'] =  __('Payment Status', 'eventprime-event-calendar-management');
        $bookings_data[0]['log'] =             __('Transacton Log', 'eventprime-event-calendar-management');
        $bookings_data[0]['guest'] =           __('Guest Booking Data', 'eventprime-event-calendar-management');
        if( ! empty( $bookings ) ) {
            $row = 1;
            foreach( $bookings as $booking ) {
                $bookings_data[$row]['id']= $booking->em_id;
                $bookings_data[$row]['user_name'] = $bookings_data[$row]['email'] = '';
                $user_id = isset($booking->em_user) ? (int) $booking->em_user : 0;
                if($user_id){
                    $user = get_userdata($user_id);
                        $bookings_data[$row]['user_name'] = $user->user_login ;
                        $bookings_data[$row]['email'] = $user->user_email;
                    }else{
                    $bookings_data[$row]['user_name'] =  __('Guest','eventprime-event-calendar-management');
                    $bookings_data[$row]['email'] =  __('Guest','eventprime-event-calendar-management');
                }
                
                $bookings_data[$row]['event'] = $booking->em_name;
                $bookings_data[$row]['sdate'] = $bookings_data[$row]['stime'] = $bookings_data[$row]['edate'] = $bookings_data[$row]['etime'] = $bookings_data[$row]['event_type'] = $bookings_data[$row]['venue'] = $bookings_data[$row]['address'] = $bookings_data[$row]['seat_type'] = '';
                if(isset($booking->event_data) && !empty($booking->event_data)){
                    $event = $booking->event_data;
                    $bookings_data[$row]['sdate'] = isset($event->em_start_date) && !empty($event->em_start_date) ? ep_timestamp_to_date($event->em_start_date): '';
                    $bookings_data[$row]['edate'] = isset($event->em_end_date) && !empty($event->em_end_date) ? ep_timestamp_to_date($event->em_end_date): '';
                    $bookings_data[$row]['stime'] = isset($event->em_start_time) && !empty($event->em_start_time) ? $event->em_start_time: '';
                    $bookings_data[$row]['etime'] = isset($event->em_end_time) && !empty($event->em_end_time) ? $event->em_end_time: '';
                    
                    if(isset($event->event_type_details) && !empty($event->event_type_details)){
                        $bookings_data[$row]['event_type'] = $booking->event_data->event_type_details->name;
                    }
                    if(isset($event->venue_details) && !empty($event->venue_details)){
                       $venue = $booking->event_data->venue_details;
                       $bookings_data[$row]['venue']= $venue->name; 
                       $bookings_data[$row]['address']=isset($venue->em_address) ? $venue->em_address : '';
                       $bookings_data[$row]['seat_type']=isset($venue->em_type) ? $venue->em_type : '';
                    }
                }
                $bookings_data[$row]['attendees'] = '';
                $bookings_data[$row]['seat'] = '';
                $bookings_data[$row]['currency']=isset($booking->em_payment_log['currency']) ? $booking->em_payment_log['currency'] : ep_get_global_settings('currency');
                
                $order_info = isset($booking->em_order_info) ? $booking->em_order_info : array();
                $tickets = isset($order_info['tickets']) ? $order_info['tickets'] : array();
                $ticket_sub_total = 0;
                if( ! empty( $tickets ) ):
                    foreach($tickets as $ticket):
                        $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;           
                    endforeach;
                endif;
                $bookings_data[$row]['price']=ep_price_with_position($ticket_sub_total);
                $bookings_data[$row]['attendees_count']= '';
                $bookings_data[$row]['subtotal']=ep_price_with_position($ticket_sub_total);
                $bookings_data[$row]['event_price']='';
                
                if( !empty( $order_info['event_fixed_price'] ) ) {
                    $bookings_data[$row]['event_price']= ep_price_with_position($order_info['event_fixed_price']);
                }
                $bookings_data[$row]['discount']='';
                
                if(isset($order_info['coupon_code'])){
                    $bookings_data[$row]['discount']= ep_price_with_position($order_info['discount']);
                }
                $bookings_data[$row]['amount_received']= ep_price_with_position($order_info['booking_total']) ;
                
                $bookings_data[$row]['gateway']= isset($booking->em_payment_method) ? ucfirst($booking->em_payment_method) : 'N/A';
                $bookings_data[$row]['booking_status'] = isset($booking->em_status) ? ucfirst($booking->em_status) : 'N/A';
                $payment_log = isset($booking->em_payment_log) ? $booking->em_payment_log : array();
                $payment_status='';
                if(strtolower($bookings_data[$row]['gateway']) == 'offline'){
                    $payment_status = isset($payment_log['offline_status']) ? $payment_log['offline_status'] : '';
                }else{
                    $payment_status = isset($payment_log['payment_status']) ? $payment_log['payment_status'] : '';
                }
                $bookings_data[$row]['payment_status']= $payment_status;
                $bookings_data[$row]['log']=serialize($payment_log);
                $except = array('multi_price_option_data', 'coupon_code', 'coupon_discount', 'coupon_amount', 'coupon_type', 'applied_ebd', 'ebd_id', 'ebd_name', 'ebd_rule_type', 'ebd_discount_type', 'ebd_discount', 'ebd_discount_amount', 'ep_rg_field_password' );
                if(!empty($payment_log)){
                    foreach($payment_log as $logs_key => $logs){
                        if(in_array($logs_key, $except)){
                            unset($payment_log[$logs_key]);
                        }
                    }
                }
                $bookings_data[$row]['log']=serialize($payment_log);
                $bookings_data[$row]['guest']='';
                if(isset($booking->em_guest_booking) && !empty($booking->em_guest_booking)){
                    $bookings_data[$row]['guest'] = serialize($order_info['guest_booking_custom_data']);
                }
                
                $attendees_count = 0;
                if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {
                    $attendee_names = isset($booking->em_attendee_names) &&!empty($booking->em_attendee_names) ? maybe_unserialize($booking->em_attendee_names): array();
                    foreach( $attendee_names as $ticket_id => $attendee_data ) {
                        foreach( $attendee_data as $booking_attendees ) {
                            $booking_attendees_val = array_values( $booking_attendees );
                            $attendees_count++;
                        }
                    }
                    $bookings_data[$row]['attendees_count']=$attendees_count;
                    $booking_attendees_field_labels = array();
                    $count = 0;
                    foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                        $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[1] );
                        foreach( $attendee_data as $booking_attendees ) {
                            $seat = '';
                            $booking_attendees_val = array_values( $booking_attendees );
                            $attendees = '';
                            foreach( $booking_attendees_field_labels as $label_key => $labels ){
                                $formated_val = ep_get_slug_from_string( $labels );
                                $at_val = '---';
                                foreach( $booking_attendees_val as $key => $baval ) {
                                    if($formated_val == 'seat'){
                                        if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                            $seat = $baval[$formated_val];
                                        }
                                    }
                                    if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                        $at_val = $baval[$formated_val];
                                        break;
                                    }
                                }
                                if( empty( $at_val ) ) {
                                    $formated_val = strtolower( $labels );
                                    foreach( $booking_attendees_val as $key => $baval ) {
                                        if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                            $at_val = $baval[$formated_val];
                                            break;
                                        }
                                    }
                                }
                                $attendees .= esc_html__( $labels, 'eventprime-event-calendar-management' ).' : '.$at_val.' | ';
                            }
                            $bookings_data[$row]['attendees'] = $attendees;
                            $bookings_data[$row]['seat'] = $seat;
                            if( $count < $attendees_count - 1 ) {
                                $row++;
                                $bookings_data[$row]['id']='';
                                $bookings_data[$row]['user_name']='';
                                $bookings_data[$row]['email']='';
                                $bookings_data[$row]['event']='';
                                $bookings_data[$row]['sdate']='';
                                $bookings_data[$row]['stime']='';
                                $bookings_data[$row]['edate']='';
                                $bookings_data[$row]['etime']='';
                                $bookings_data[$row]['event_type']='';
                                $bookings_data[$row]['venue']='';
                                $bookings_data[$row]['address']='';
                                $bookings_data[$row]['seat_type']='';
                                $bookings_data[$row]['attendees']=$attendees;
                                $bookings_data[$row]['seat']= $seat;
                                $bookings_data[$row]['currency']='';
                                $bookings_data[$row]['price']='';
                                $bookings_data[$row]['attendees_count']='';
                                $bookings_data[$row]['subtotal']='';
                                $bookings_data[$row]['event_price']='';
                                $bookings_data[$row]['discount']='';
                                $bookings_data[$row]['amount_received']='';
                                $bookings_data[$row]['gateway']='';
                                $bookings_data[$row]['booking_status']='';
                                $bookings_data[$row]['payment_status']='';
                                $bookings_data[$row]['log']='';
                                $bookings_data[$row]['guest']='';
                            }
                            $count++;
                        }
                    }
                }
                $row++;
            }
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="ep-bookings-'.md5(time().mt_rand(100, 999)).'.csv"');
        $f = fopen('php://output', 'w');
        foreach ( $bookings_data as $line ) {
            fputcsv( $f, $line );
        }
        die;
    }

    /**
     * Check if booking eligible for edit
     * 
     * @param int $event_id Event ID.
     * 
     * @return bool
     */
    public function check_booking_eligible_for_edit( $event_id ) {
        $status = 0;
        if( ! empty( $event_id ) && empty( check_event_has_expired( $event_id ) ) ) {
            $em_allow_edit_booking = get_post_meta( $event_id, 'em_allow_edit_booking', true );
            if( ! empty( $em_allow_edit_booking ) ) {
                $em_edit_booking_date_data = get_post_meta( $event_id, 'em_edit_booking_date_data', true );
                if( empty( $em_edit_booking_date_data ) ) {
                    return 1;
                }
                $em_edit_booking_date_type = ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) ? $em_edit_booking_date_data['em_edit_booking_date_type'] : '' );
                if( $em_edit_booking_date_type == 'custom_date' ) {
                    $em_edit_booking_date_date = $em_edit_booking_date_data['em_edit_booking_date_date'];
                    $em_edit_booking_date_time = $em_edit_booking_date_data['em_edit_booking_date_time'];
                    if( empty( $em_edit_booking_date_date ) ) {
                        $status = 1;
                    } else{
                        $end_date = $em_edit_booking_date_date;
                        if( ! empty( $em_edit_booking_date_time ) ) {
                            $end_date = ep_timestamp_to_date( $em_edit_booking_date_date );
                            $end_date .= ' ' . $em_edit_booking_date_time;
                            $end_date = ep_datetime_to_timestamp( $end_date, 'Y-m-d', ep_get_current_user_timezone() );
                        }
                        if( $end_date > ep_get_current_timestamp() ) {
                            $status = 1;
                        }
                    }
                } else if( $em_edit_booking_date_type == 'event_date' ) {
                    $em_edit_booking_date_event_option = $em_edit_booking_date_data['em_edit_booking_date_event_option'];
                    if( $em_edit_booking_date_event_option == 'event_ends' ) {
                        $status = 1;
                    } else if( $em_edit_booking_date_event_option == 'event_start' ) {
                        $em_start_date = get_post_meta( $event_id, 'em_start_date', true );
                        $em_start_time = get_post_meta( $event_id, 'em_start_time', true );
                        $start_date = $em_start_date;
                        if( ! empty( $em_start_time ) ) {
                            $start_date = ep_timestamp_to_date( $em_start_date );
                            $start_date .= ' ' . $em_start_time;
                            $start_date = ep_datetime_to_timestamp( $start_date );
                        }
                        if( $start_date > ep_get_current_timestamp() ) {
                            $status = 1;
                        }
                    }
                } else if( $em_edit_booking_date_type == 'relative_date' ) {
                    $days = $em_edit_booking_date_data['em_edit_booking_date_days'];
                    $days_option = $em_edit_booking_date_data['em_edit_booking_date_days_option'];
                    $event_option = $em_edit_booking_date_data['em_edit_booking_date_event_option'];
                    $days_string  = ' days';
                    if( $days == 1 ) {
                        $days_string = ' day';
                    }
                    // + or - days
                    $days_icon = '- ';
                    if( $days_option == 'after' ) {
                        $days_icon = '+ ';
                    }
                    if( $event_option == 'event_start' ) {
                        $em_start_date = get_post_meta( $event_id, 'em_start_date', true );
                        $em_start_time = get_post_meta( $event_id, 'em_start_time', true );
                        $start_date = ep_timestamp_to_date( $em_start_date );
                        if( ! empty( $em_start_time ) ) {
                            $start_date .= ' ' . $em_start_time;
                        }
                        $start_timestamp = ep_datetime_to_timestamp( $start_date );
                        $min_start = strtotime( $days_icon . $days . $days_string, $start_timestamp );
                        if( $min_start > ep_get_current_timestamp() ) {
                            $status = 1;
                        }
                    } else if( $event_option == 'event_ends' ) {
                        $em_end_date = get_post_meta( $event_id, 'em_end_date', true );
                        $em_end_time = get_post_meta( $event_id, 'em_end_time', true );
                        $book_start_date = ep_timestamp_to_date( $em_end_date );
                        if( ! empty( $em_end_time ) ) {
                            $book_start_date .= ' ' . $em_end_time;
                        }
                        $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                        $min_start = strtotime( $days_icon . $days . $days_string, $book_start_timestamp );
                        if( $min_start > ep_get_current_timestamp() ) {
                            $status = 1;
                        }
                    }
                } else{
                    $status = 1;
                }
            }
        }
        return $status;
    }

    /**
     * Get booking status
     * 
     * @param int $booking_id Booking ID.
     * 
     * @param array $data Booking Payment data.
     * 
     * @return string $booking_status
     * 
     * @since 3.2.2
     */
    public function ep_get_event_booking_status( $booking_id, $data = array() ) {
        $booking_status = 'completed';
        if( ! empty( $booking_id ) && ! empty( $data ) ) {
            if( ! empty( $data['payment_gateway'] ) && 'offline' == $data['payment_gateway'] ) {
                $default_booking_status = ep_get_global_settings( 'default_booking_status' );
                if( ! empty( $default_booking_status ) ) {
                    $booking_status = $default_booking_status;
                }
            }
        }

        return $booking_status;
    }
}