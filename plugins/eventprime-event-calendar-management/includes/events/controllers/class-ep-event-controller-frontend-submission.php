<?php
/**
 * Class for frontend event submission
 */
defined( 'ABSPATH' ) || exit;
class EventM_Event_Controller_Frontend_Submission {
    /**
     * Term Type.
     * 
     * @var string
     */
    private $post_type = EM_EVENT_POST_TYPE;
    /**
     * Instance
     *
     * @var Instance
     */
    public static $instance = null;
    /**
     * Constructor
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function enqueue_style(){
        wp_enqueue_style(
			'em-admin-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
	
        wp_enqueue_style(
            'ep-user-views-css',
            EP_BASE_URL . '/includes/assets/css/ep-frontend-views.css',
            false, EVENTPRIME_VERSION
        );
    }
    public function enqueue_script(){
        wp_enqueue_script(
            'em-public-jscolor',
            EP_BASE_URL . '/includes/assets/js/jscolor.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'em-admin-select2-js',
            EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
		);
        wp_enqueue_script(
		    'em-admin-timepicker-js',
		    EP_BASE_URL . '/includes/assets/js/jquery.timepicker.min.js',
		    false, EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-front-events-fes-js',
            EP_BASE_URL . '/includes/events/assets/js/ep-frontend-event-submission.js',
            array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-slider' ), EVENTPRIME_VERSION
        );
        
        wp_enqueue_style(
		    'em-admin-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );
        // Ui Timepicker css
	    wp_enqueue_style(
		    'em-admin-jquery-timepicker',
		    EP_BASE_URL . '/includes/assets/css/jquery.timepicker.min.css',
		    false, EVENTPRIME_VERSION
        );
        $required_fields = new stdClass();
        $fields = ep_get_global_settings( 'frontend_submission_required' );
        if(!empty($fields) && is_array($fields)){
            foreach($fields as $key => $field){
                $required_fields->$key = $field;
            }
        }
        wp_localize_script(
            'ep-front-events-fes-js', 
            'em_event_fes_object', 
            array(
                'before_event_scheduling' => esc_html__( 'Please choose start & end date before enable scheduling!', 'eventprime-event-calendar-management' ),
                'before_event_recurrence' => esc_html__( 'Please choose start & end date before enable recurrence!', 'eventprime-event-calendar-management' ),
                'add_schedule_btn'  	  => esc_html__( 'Add New Hourly Schedule', 'eventprime-event-calendar-management' ),
                'add_day_title_label'  	  => esc_html__( 'Title', 'eventprime-event-calendar-management' ),
                'start_time_label'  	  => esc_html__( 'Start Time', 'eventprime-event-calendar-management' ),
                'end_time_label'  	      => esc_html__( 'End Time', 'eventprime-event-calendar-management' ),
                'description_label'  	  => esc_html__( 'Description', 'eventprime-event-calendar-management' ),
                'remove_label'  	      => esc_html__( 'Remove', 'eventprime-event-calendar-management' ),
                'material_icons'          => EventM_Constants::get_material_icons(),
                'icon_text'  	   	      => esc_html__( 'Icon', 'eventprime-event-calendar-management' ),
                'icon_color_text'  	      => esc_html__( 'Icon Color', 'eventprime-event-calendar-management' ),
                'additional_date_text' 	  => esc_html__( 'Date', 'eventprime-event-calendar-management' ),
                'additional_time_text' 	  => esc_html__( 'Time', 'eventprime-event-calendar-management' ),
                'optional_text' 	      => esc_html__( '(Optional)', 'eventprime-event-calendar-management' ),
                'additional_label_text'   => esc_html__( 'Label', 'eventprime-event-calendar-management' ),
                'countdown_activate_text' => esc_html__( 'Activates', 'eventprime-event-calendar-management' ),
                'countdown_activated_text'=> esc_html__( 'Activated', 'eventprime-event-calendar-management' ),
                'countdown_on_text'	      => esc_html__( 'On', 'eventprime-event-calendar-management' ),
                'countdown_ends_text'     => esc_html__( 'Ends', 'eventprime-event-calendar-management' ),
                'countdown_activates_on'  => array( 'right_away' => esc_html__( 'Right Away', 'eventprime-event-calendar-management' ), 'custom_date' => esc_html__( 'Custom Date', 'eventprime-event-calendar-management' ), 'event_date' => esc_html__( 'Event Date', 'eventprime-event-calendar-management' ), 'relative_date' => esc_html__( 'Relative Date', 'eventprime-event-calendar-management' ) ),
                'countdown_days_options'  => array( 'before' => esc_html__( 'Days Before', 'eventprime-event-calendar-management' ), 'after' => esc_html__( 'Days After', 'eventprime-event-calendar-management' ) ),
                'countdown_event_options' => array( 'event_start' => esc_html__( 'Event Start', 'eventprime-event-calendar-management' ), 'event_ends' => esc_html__( 'Event Ends', 'eventprime-event-calendar-management' ) ),
                'ticket_capacity_text'    => esc_html__( 'Capacity', 'eventprime-event-calendar-management' ),
                'add_ticket_text'    	  => esc_html__( 'Add Ticket Type', 'eventprime-event-calendar-management' ),
                'add_text'                => esc_html__( 'Add', 'eventprime-event-calendar-management' ),
                'edit_text'    	  	      => esc_html__( 'Edit', 'eventprime-event-calendar-management' ),
                'update_text'    	      => esc_html__( 'Update', 'eventprime-event-calendar-management' ),
                'add_ticket_category_text'=> esc_html__( 'Add Tickets Category', 'eventprime-event-calendar-management' ),
                'price_text'              => esc_html__( 'Fee Per Ticket', 'eventprime-event-calendar-management' ),
                'offer_text'		      => esc_html__( 'Offer', 'eventprime-event-calendar-management' ),
                'no_ticket_found_error'   => esc_html__( 'Booking will be turn off if no ticket found. Are you sure you want to continue?', 'eventprime-event-calendar-management' ),
                'max_capacity_error'      => esc_html__( 'Max allowed capacity is', 'eventprime-event-calendar-management' ),
                'max_less_then_min_error' => esc_html__( 'Maximum tickets number can\'t be less then minimum tickets number.', 'eventprime-event-calendar-management' ),
                'required_text'		      => esc_html__( 'Required', 'eventprime-event-calendar-management' ),
                'one_checkout_field_req'  => esc_html__( 'Please select atleast one attendee field.', 'eventprime-event-calendar-management' ),
                'no_name_field_option'    => esc_html__( 'Please select name field option.', 'eventprime-event-calendar-management' ),
                'some_issue_found'    	  => esc_html__( 'Some issue found. Please refresh the page and try again later.', 'eventprime-event-calendar-management' ),
                'fixed_field_not_selected'=> esc_html__( 'Please selecte fixed field.', 'eventprime-event-calendar-management' ),
                'fixed_field_term_option_required'=> esc_html__( 'Please select one terms option.', 'eventprime-event-calendar-management' ),
                'repeat_child_event_prompt'=> esc_html__( 'This event have multiple child events. They will be deleted after update event.', 'eventprime-event-calendar-management' ),
                'empty_event_title'       => esc_html__( 'Event title is required.', 'eventprime-event-calendar-management' ),
                'empty_start_date'        => esc_html__( 'Event start date is required.', 'eventprime-event-calendar-management' ),
                'end_date_less_from_start'=> esc_html__( 'Event end date can not be less then event start date.', 'eventprime-event-calendar-management' ),
                'event_required_fields'   => $required_fields,
                'event_name_error'        => esc_html__( 'Event Name can not be empty.', 'eventprime-event-calendar-management' ),
                'event_desc_error'        => esc_html__( 'Event Description can not be empty.', 'eventprime-event-calendar-management' ),
                'event_start_date_error'  => esc_html__( 'Event start date can not be empty.', 'eventprime-event-calendar-management' ),
                'event_end_date_error'    => esc_html__( 'Event end date can not be empty.', 'eventprime-event-calendar-management' ),
                'event_custom_link_error' => esc_html__( 'Event Url can not be empty.', 'eventprime-event-calendar-management' ),
                'event_custom_link_val_error' => esc_html__( 'Please enter valid url.', 'eventprime-event-calendar-management' ),
                'event_type_error'        => esc_html__( 'Please Select Event Types.', 'eventprime-event-calendar-management' ),
                'event_type_name_error'   => esc_html__( 'Event Type name can not be empty.', 'eventprime-event-calendar-management' ),
                'event_venue_error'       => esc_html__( 'Please Select Event Venues.', 'eventprime-event-calendar-management' ),
                'event_venue_name_error'  => esc_html__( 'Event Venues name can not be empty.', 'eventprime-event-calendar-management' ),
                'event_performer_error'   => esc_html__( 'Please Select Event Performers.', 'eventprime-event-calendar-management' ),
                'event_performer_name_error' => esc_html__( 'Event Perfomer name can not be empty.', 'eventprime-event-calendar-management' ),
                'event_organizer_error'   => esc_html__( 'Please Select Event Organizers.', 'eventprime-event-calendar-management' ),
                'event_organizer_name_error' => esc_html__( 'Event Organizer name can not be empty.', 'eventprime-event-calendar-management' ),
                'fes_nonce'               => wp_create_nonce( 'ep-frontend-event-submission-nonce' ),
                'choose_image_label'      => esc_html__( 'Choose Image', 'eventprime-event-calendar-management' ),
                'use_image_label'         => esc_html__( 'Use Image', 'eventprime-event-calendar-management' ),
            )
        );
        $gmap_api_key = ep_get_global_settings('gmap_api_key');
        if ($gmap_api_key):
            $gmap_uri = 'https://maps.googleapis.com/maps/api/js?key=' . $gmap_api_key . '&libraries=places';
        else:
            $gmap_uri = false;
        endif;
        
    }
     /**
     * Render template on the frontend
     */
    
    public function render_template( $atts = array() ) {
        $this->enqueue_style();
        $this->enqueue_script();
        $settings     = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $fes_settings = $settings->ep_get_settings( 'fes' );
        $args         = new stdClass();
        $args->event_id = isset($_GET['event_id']) && !empty($_GET['event_id']) ? absint( sanitize_text_field($_GET['event_id']) ) : 0;
        $fes_data     = $this->get_event_submission_options( $args );
        $fes_data     = apply_filters( 'ep_filter_frontend_event_submission_options', $fes_data, $atts );
        ob_start();
        ep_get_template_part( 'events/frontend-submission/form', null, $fes_data );
        
        return ob_get_clean();
    }
    
    public function get_event_submission_options( $args ) {
        $args->ues_confirm_message = ep_get_global_settings( 'ues_confirm_message' );
        $args->allow_submission_by_anonymous_user = ep_get_global_settings('allow_submission_by_anonymous_user');
        $args->login_required = true;
        $login_page_id = ep_get_global_settings('login_page');
        //$args->login_page_url = get_permalink( $login_page_id ).'?redirect='.get_permalink();
        $args->login_page_url = ep_get_custom_page_url( 'login_page' );
        if( ! empty( $args->allow_submission_by_anonymous_user ) || is_user_logged_in() ) {
            $args->login_required = false;
        }
        $args->ues_login_message = ep_get_global_settings('ues_login_message');
        if( ! empty( $args->allow_submission_by_anonymous_user ) ) {
           $args->ues_login_message = '';
        }
        $args->ues_default_status = ep_get_global_settings('ues_default_status');
        $args->frontend_submission_roles = ep_get_global_settings('frontend_submission_roles');
        $args->ues_restricted_submission_message = ep_get_global_settings('ues_restricted_submission_message');
        
        $frontend_submission_sections = (array)ep_get_global_settings('frontend_submission_sections');
        $args->fes_event_text_color = false;
        $args->fes_event_featured_image = false;
        $args->fes_event_booking = false;
        $args->fes_event_link = false;
        $args->fes_event_type = false;
        $args->fes_new_event_type = false;
        $args->fes_event_location = false;
        $args->fes_new_event_location = false;
        $args->fes_event_performer = false;
        $args->fes_new_event_performer = false;
        $args->fes_event_organizer = false;
        $args->fes_new_event_organizer = false;
        $args->fes_event_more_options = false;
        if( ! empty( $frontend_submission_sections ) ) {
            if(isset($frontend_submission_sections['fes_event_text_color'])){
                $args->fes_event_text_color = true;
            }
            if(isset($frontend_submission_sections['fes_event_featured_image'])){
                $args->fes_event_featured_image = true;
            }
            if(isset($frontend_submission_sections['fes_event_booking'])){
                $args->fes_event_booking = true;
            }
            if(isset($frontend_submission_sections['fes_event_link'])){
                $args->fes_event_link = true;
            }
            if(isset($frontend_submission_sections['fes_event_type'])){
                $args->fes_event_type = true;
            }
            if(isset($frontend_submission_sections['fes_new_event_type'])){
                $args->fes_new_event_type = true;
            }
            if(isset($frontend_submission_sections['fes_event_location'])){
                $args->fes_event_location = true;
            }
            if(isset($frontend_submission_sections['fes_new_event_location'])){
                $args->fes_new_event_location = true;
            }
            if(isset($frontend_submission_sections['fes_event_performer'])){
                $args->fes_event_performer = true;
            }
            if(isset($frontend_submission_sections['fes_new_event_performer'])){
                $args->fes_new_event_performer = true;
            }
            if(isset($frontend_submission_sections['fes_event_organizer'])){
                $args->fes_event_organizer = true;
            }
            if(isset($frontend_submission_sections['fes_new_event_organizer'])){
                $args->fes_new_event_organizer = true;
            }
            if(isset($frontend_submission_sections['fes_event_more_options'])){
                $args->fes_event_more_options = true;
            }
        }
        
        //Required Section
        $frontend_submission_required = (array)ep_get_global_settings('frontend_submission_required');
        $args->fes_event_description_req = false;
        $args->fes_event_booking_req = false;
        $args->fes_booking_price_req = false;
        $args->fes_event_link_req = false;
        $args->fes_event_type_req = false;
        $args->fes_event_location_req = false;
        $args->fes_event_performer_req = false;
        $args->fes_event_organizer_req = false;
        if( ! empty( $frontend_submission_required ) ) {
            if(isset($frontend_submission_required['fes_event_description'])){
                $args->fes_event_description_req = true;
            }
            if(isset($frontend_submission_required['fes_event_booking'])){
                $args->fes_event_booking_req = true;
            }
            if(isset($frontend_submission_required['fes_booking_price'])){
                $args->fes_booking_price_req = true;
            }
            if(isset($frontend_submission_required['fes_event_link'])){
                $args->fes_event_link_req = true;
            }
            if(isset($frontend_submission_required['fes_event_type'])){
                $args->fes_event_type_req = true;
            }
            if(isset($frontend_submission_required['fes_event_location'])){
                $args->fes_event_location_req = true;
            }
            if(isset($frontend_submission_required['fes_event_performer'])){
                $args->fes_event_performer_req = true;
            }
            if(isset($frontend_submission_required['fes_event_organizer'])){
                $args->fes_event_organizer_req = true;
            }
        }
        
        //Event Types lists
        $event_type_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List');
        $event_types = $event_type_controller->get_event_types_data();
        $args->event_types = new stdClass();
        if(isset($event_types->terms)){
            $args->event_types = $event_types->terms;
        }
        
        //Event Venues lists
        $event_venue_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List');
        $event_venues = $event_venue_controller->get_venues_data();
        $args->event_venues = new stdClass();
        if(isset($event_venues->terms)){
            $args->event_venues = $event_venues->terms;
        }
        
        //Event Performers lists
        $event_performer_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List');
        $event_performers = $event_performer_controller->get_performer_all_data();;
        $args->event_performers = new stdClass();
        if(count($event_performers)){
            $args->event_performers = $event_performers;
        }
        
        //Event Organizers lists
        $event_organizer_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Organizer_Controller_List');
        $event_organizers = $event_organizer_controller->get_organizers_data();
        $args->event_organizers = new stdClass();
        if(isset($event_organizers->terms)){
            $args->event_organizers = $event_organizers->terms;
        }
        
        //Ages
        $args->ages_groups = array(
            'all'               => esc_html__( 'All', 'eventprime-event-calendar-management' ),
            'parental_guidance' => esc_html__( 'All ages but parental guidance', 'eventprime-event-calendar-management' ),
            'custom_group'      => esc_html__(' Custom Age', 'eventprime-event-calendar-management' )
        );

        $args->fes_allow_media_library = ep_get_global_settings( 'fes_allow_media_library' );
        
        //Edit Event
        $event_controllers     = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
        
        if( ! empty( $args->event_id ) ) {
            $args->event = $event_controllers->get_single_event( $args->event_id ); 
        }
        return $args;
    }
    
    public function insert_frontend_event_post_data( $post_data ) {
        global $wpdb;
        $metaboxes_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Admin_Meta_Boxes' );
        $post_id = 0;
        if( ! empty( $post_data ) ) {
            $title = isset($post_data['name']) ? sanitize_text_field($post_data['name']) : 'Event';
            $description = isset($post_data['description']) ? $post_data['description'] : '';
            $status = isset($post_data['status']) ? $post_data['status'] : 'publish';
            if( isset( $post_data['event_id'] ) && ! empty( $post_data['event_id'] ) ){
                $post_id = $post_data['event_id'];
                $post_update = array(
                    'ID'         => $post_id,
                    'post_title' => $title,
                    'post_content' => $description,
                );
                wp_update_post( $post_update );
            }else{
                $post_id = wp_insert_post(array (
                    'post_type' => EM_EVENT_POST_TYPE,
                    'post_title' => $title,
                    'post_content' => $description,
                    'post_status' => $status,
                    'post_author' => get_current_user_id(),
                )); 
            }
        }
        if( $post_id ){
            $post = get_post($post_id);
            update_post_meta( $post_id, 'em_user_submitted', get_current_user_id() );
            $em_name = isset($post_data['name']) ? sanitize_text_field($post_data['name']) : '';
            $em_start_date = isset($post_data['em_start_date']) ? $post_data['em_start_date'] : '';
            $em_end_date = isset($post_data['em_end_date']) ? $post_data['em_end_date'] : '';
            $em_start_time = isset($post_data['em_start_time']) ? $post_data['em_start_time'] : '';
            $em_end_time = isset($post_data['em_end_time']) ? $post_data['em_end_time'] : '';
            $em_all_day = isset($post_data['em_all_day']) ? $post_data['em_all_day'] : 0;
            
            $em_ticket_price = isset($post_data['em_ticket_price']) ? sanitize_text_field($post_data['em_ticket_price']) : '';
            $em_venue = isset($post_data['em_venue']) ? $post_data['em_venue'] : '';
            $em_performer = isset($post_data['em_performer']) ? $post_data['em_performer'] : array();
            $em_organizer = isset($post_data['em_organizer']) ? $post_data['em_organizer'] : array();
            $em_event_type = isset($post_data['em_event_type']) ? $post_data['em_event_type'] : '';
            
            $em_enable_booking = isset($post_data['em_enable_booking']) ? $post_data['em_enable_booking'] : 'bookings_off';
            $em_custom_link = isset($post_data['em_custom_link']) ? $post_data['em_custom_link'] : '';
            $em_custom_meta = isset($post_data['em_custom_meta']) ? $post_data['em_custom_meta'] : array();
            
            $em_hide_start_time = isset( $post_data['em_hide_event_start_time'] ) && !empty($post_data['em_hide_event_start_time']) ? 1 : 0;
            $em_hide_event_start_date = isset( $post_data['em_hide_event_start_date'] ) && !empty($post_data['em_hide_event_start_date']) ? 1 : 0;
            $em_hide_event_end_time = isset( $post_data['em_hide_event_end_time'] ) && !empty($post_data['em_hide_event_end_time']) ? 1 : 0;
            $em_hide_end_date = isset( $post_data['em_hide_end_date'] ) ? 1 : 0;
            
            $em_event_more_dates = isset( $post_data['em_event_more_dates'] ) ? 1 : 0;
            $event_more_dates = isset( $post_data['em_event_add_more_dates'] ) ? $post_data['em_event_add_more_dates'] : array();
            
            $em_hide_booking_status = isset( $post_data['em_hide_booking_status'] ) ? 1 : 0;
            $em_allow_cancellations = isset( $post_data['em_allow_cancellations'] ) ? 1 : 0;
            
            $em_event_date_placeholder = isset($post_data['em_event_date_placeholder']) ? sanitize_text_field($post_data['em_event_date_placeholder']) : '';
            $em_event_date_placeholder_custom_note = isset($post_data['em_event_date_placeholder_custom_note']) ? sanitize_text_field($post_data['em_event_date_placeholder_custom_note']) : '';
            
            $thumbnail_id = isset( $post_data['thumbnail'] ) ? $post_data['thumbnail'] : 0;
            set_post_thumbnail($post_id, $thumbnail_id);
            
            update_post_meta($post_id, 'em_id', $post_id );
            update_post_meta($post_id, 'em_name', $em_name);
            update_post_meta($post_id, 'em_start_date', $em_start_date);
            update_post_meta($post_id, 'em_end_date', $em_end_date);
            update_post_meta($post_id, 'em_end_time', $em_end_time);
            update_post_meta($post_id, 'em_start_time', $em_start_time);
            update_post_meta($post_id, 'em_all_day', $em_all_day);
            
            update_post_meta($post_id, 'em_ticket_price', $em_ticket_price);
            update_post_meta($post_id, 'em_venue', $em_venue);
            update_post_meta($post_id, 'em_event_type', $em_event_type);
            update_post_meta($post_id, 'em_organizer', $em_organizer);
            update_post_meta($post_id, 'em_performer', $em_performer);
            wp_set_post_terms($post_id, $em_venue , 'em_venue', false);
            wp_set_post_terms($post_id, $em_event_type , 'em_event_type', false);
            wp_set_post_terms($post_id, $em_organizer , 'em_event_organizer', false);
            wp_set_post_terms($post_id, $em_performer , 'em_performer', false);
            
            update_post_meta($post_id, 'em_enable_booking', $em_enable_booking);
            update_post_meta($post_id, 'em_custom_link', $em_custom_link);
            update_post_meta($post_id, 'em_custom_meta', $em_custom_meta);
            
            update_post_meta( $post_id, 'em_hide_event_start_time', $em_hide_start_time );
            update_post_meta( $post_id, 'em_hide_event_start_date', $em_hide_event_start_date );
            update_post_meta( $post_id, 'em_hide_event_end_time', $em_hide_event_end_time );
            update_post_meta( $post_id, 'em_hide_end_date', $em_hide_end_date );
            
            update_post_meta( $post_id, 'em_event_more_dates', $em_event_more_dates );
            update_post_meta( $post_id, 'em_event_add_more_dates', $event_more_dates );
            
            update_post_meta( $post_id, 'em_hide_booking_status', $em_hide_booking_status );
            update_post_meta( $post_id, 'em_allow_cancellations', $em_allow_cancellations );
            
            update_post_meta( $post_id, 'em_event_date_placeholder', $em_event_date_placeholder );
            update_post_meta( $post_id, 'em_event_date_placeholder_custom_note', $em_event_date_placeholder_custom_note );

            // save category
            $cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
            $price_options_table = $wpdb->prefix.'em_price_options';
            if( isset( $_POST['em_ticket_category_data'] ) && ! empty( $_POST['em_ticket_category_data'] ) ) {
                $em_ticket_category_data = json_decode( stripslashes( $_POST['em_ticket_category_data'] ), true) ;
            }
            if( ! empty( $em_ticket_category_data ) ) {
                $cat_priority = 1;
                foreach( $em_ticket_category_data as $cat ) {
                    $cat_id = $cat['id'];
                    $get_field_data = '';
                    if( !empty( $cat_id ) ) {
                        $get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d AND `id` = %d", $post_id, $cat_id ) );
                    }
                    if( empty( $get_field_data ) ) {
                        $save_data 				 = array();
                        $save_data['event_id'] 	 = $post_id;
                        $save_data['name'] 	     = $cat['name'];
                        $save_data['capacity']   = $cat['capacity'];
                        $save_data['priority']   = 1;
                        $save_data['status']     = 1;
                        $save_data['created_by'] = get_current_user_id();
                        $save_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
                        $result = $wpdb->insert( $cat_table_name, $save_data );
                        $cat_id = $wpdb->insert_id;
                    } else{
                        $wpdb->update( $cat_table_name, 
                            array( 
                                'name' 		  	  => $cat['name'],
                                'capacity' 		  => $cat['capacity'],
                                'priority'		  => $cat_priority,
                                'last_updated_by' => get_current_user_id(),
                                'updated_at' 	  => date_i18n("Y-m-d H:i:s", time())
                            ), 
                            array( 'id' => $cat_id )
                        );
                    }
                    $cat_priority++;
                    //save tickets
                    if( isset( $cat['tickets'] ) && ! empty( $cat['tickets'] ) ) {
                        $cat_ticket_priority = 1;
                        foreach( $cat['tickets'] as $ticket ) {
                            $ticket_data = array();
                            if( isset( $ticket['id'] ) && ! empty( $ticket['id'] ) ) {
                                $ticket_id = $ticket['id'];
                                $get_ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $ticket_id ) );
                                if( ! empty( $get_ticket_data ) ) {
                                    $ticket_data['name'] 		   		   = addslashes( $ticket['name'] );
                                    $ticket_data['description']    		   = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                                    $ticket_data['price'] 		   		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                                    $ticket_data['capacity'] 	   		   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                                    $ticket_data['icon'] 		   		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                                    $ticket_data['priority'] 	   		   = $cat_ticket_priority;
                                    $ticket_data['updated_at'] 	   		   = date_i18n("Y-m-d H:i:s", time());
                                    $ticket_data['additional_fees']    	   = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                                    $ticket_data['allow_cancellation'] 	   = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                                    $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                                    // date
                                    $start_date = [];
                                    if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                        $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                        if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                            if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                                $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                            }
                                            if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                                $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                            }
                                        } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                            $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                        } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                            if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                                $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                            }
                                            if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                                $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                            }
                                            $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                        }
                                    }
                                    $ticket_data['booking_starts'] = json_encode( $start_date );
                                    // end date
                                    $end_date = [];
                                    if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                        $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                        if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                            if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                                $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                            }
                                            if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                                $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                            }
                                        } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                            $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                        } elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
                                            if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                                $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                            }
                                            if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                                $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                            }
                                            $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                        }
                                    }
                                    $ticket_data['booking_ends'] = json_encode( $end_date );
                                    
                                    $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                                    $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                                    $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                                    $wpdb->update( $price_options_table, 
                                        $ticket_data, 
                                        array( 'id' => $ticket_id )
                                    );
                                } else{
                                    $ticket_data['category_id']    = $cat_id;
                                    $ticket_data['event_id'] 	   = $post_id;
                                    $ticket_data['name'] 		   = addslashes( $ticket['name'] );
                                    $ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                                    $ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                                    $ticket_data['special_price']  = '';
                                    $ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                                    $ticket_data['is_default']     = 1;
                                    $ticket_data['is_event_price'] = 0;
                                    $ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                                    $ticket_data['priority'] 	   = $cat_ticket_priority;
                                    $ticket_data['status'] 		   = 1;
                                    $ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());

                                    // new
                                    $ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                                    $ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                                    $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                                    // date
                                    $start_date = [];
                                    if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                        $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                        if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                            if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                                $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                            }
                                            if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                                $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                            }
                                        } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                            $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                        } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                            if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                                $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                            }
                                            if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                                $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                            }
                                            $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                        }
                                    }
                                    $ticket_data['booking_starts'] = json_encode( $start_date );
                                    // end date
                                    $end_date = [];
                                    if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                        $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                        if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                            if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                                $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                            }
                                            if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                                $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                            }
                                        } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                            $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                        } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_ends' ) {
                                            if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                                $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                            }
                                            if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                                $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                            }
                                            $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                        }
                                    }
                                    $ticket_data['booking_ends'] = json_encode( $end_date );
                                
                                    $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                                    $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                                    $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                                    $result = $wpdb->insert( $price_options_table, $ticket_data );
                                }
                            } else{
                                $ticket_data['category_id']    = $cat_id;
                                $ticket_data['event_id'] 	   = $post_id;
                                $ticket_data['name'] 		   = addslashes( $ticket['name'] );
                                $ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                                $ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                                $ticket_data['special_price']  = '';
                                $ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                                $ticket_data['is_default']     = 1;
                                $ticket_data['is_event_price'] = 0;
                                $ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                                $ticket_data['priority'] 	   = $cat_ticket_priority;
                                $ticket_data['status'] 		   = 1;
                                $ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());

                                // new
                                $ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                                $ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                                $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                                // date
                                $start_date = [];
                                if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                    $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                    if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                            $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                            $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                            $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                            $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                        }
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_starts'] = json_encode( $start_date );
                                // end date
                                $end_date = [];
                                if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                    $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                    if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                            $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                            $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_ends' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                            $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                            $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                        }
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_ends'] = json_encode( $end_date );

                                $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                                $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                                $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                                $result = $wpdb->insert( $price_options_table, $ticket_data );
                            }
                            $cat_ticket_priority++;
                        }
                    }
                }
            }

            // delete category
            if( isset( $_POST['em_ticket_category_delete_ids'] ) && !empty( $_POST['em_ticket_category_delete_ids'] ) ) {
                $em_ticket_category_delete_ids = $_POST['em_ticket_category_delete_ids'];
                $del_ids = json_decode( stripslashes( $em_ticket_category_delete_ids ) );
                if( is_string( $em_ticket_category_delete_ids ) && is_array( json_decode( stripslashes( $em_ticket_category_delete_ids ) ) ) &&  json_last_error() == JSON_ERROR_NONE ) {
                    foreach( $del_ids as $id ) {
                        $get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `id` = %d", $id ) );
                        if( ! empty( $get_field_data ) ) {
                            $wpdb->delete( $cat_table_name, array( 'id' => $id ) );
                        }
                    }
                }
            }

            // save tickets
            if( isset( $_POST['em_ticket_individual_data'] ) && ! empty( $_POST['em_ticket_individual_data'] ) ) {
                $em_ticket_individual_data = json_decode( stripslashes( $_POST['em_ticket_individual_data'] ), true) ;
                if( isset( $em_ticket_individual_data ) && ! empty( $em_ticket_individual_data ) ) {
                    foreach( $em_ticket_individual_data as $ticket ) {
                        if( isset( $ticket['id'] ) && ! empty( $ticket['id'] ) ) {
                            $ticket_id = $ticket['id'];
                            $get_ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $ticket_id ) );
                            if( ! empty( $get_ticket_data ) ) {
                                $ticket_data 				   = array();
                                $ticket_data['name'] 		   = addslashes( $ticket['name'] );
                                $ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                                $ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                                $ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                                $ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                                $ticket_data['updated_at'] 	   = date_i18n("Y-m-d H:i:s", time());
                                $ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                                $ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                                $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                                // date
                                $start_date = [];
                                if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                    $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                    if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                            $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                            $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                            $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                            $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                        }
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_starts'] = json_encode( $start_date );
                                // end date
                                $end_date = [];
                                if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                    $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                    if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                            $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                            $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                            $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                            $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                        }
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_ends'] = json_encode( $end_date );

                                $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                                $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                                $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                                $wpdb->update( $price_options_table, 
                                    $ticket_data, 
                                    array( 'id' => $ticket_id )
                                );
                            } else{
                                $ticket_data 				   = array();
                                $ticket_data['category_id']    = 0;
                                $ticket_data['event_id'] 	   = $post_id;
                                $ticket_data['name'] 		   = addslashes( $ticket['name'] );
                                $ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                                $ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                                $ticket_data['special_price']  = '';
                                $ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                                $ticket_data['is_default']     = 1;
                                $ticket_data['is_event_price'] = 0;
                                $ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                                $ticket_data['priority'] 	   = 1;
                                $ticket_data['status'] 		   = 1;
                                $ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());

                                // new
                                $ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                                $ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                                $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                                // date
                                $start_date = [];
                                if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                    $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                    if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                            $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                            $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                        if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                            $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                            $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                        }
                                        $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_starts'] = json_encode( $start_date );
                                // end date
                                $end_date = [];
                                if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                    $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                    if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                            $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                            $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                        }
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    } elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
                                        if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                            $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                        }
                                        if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                            $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                        }
                                        $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                    }
                                }
                                $ticket_data['booking_ends'] = json_encode( $end_date );

                                $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                                $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                                $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                                $result = $wpdb->insert( $price_options_table, $ticket_data );
                            }
                        } else{
                            $ticket_data 				   = array();
                            $ticket_data['category_id']    = 0;
                            $ticket_data['event_id'] 	   = $post_id;
                            $ticket_data['name'] 		   = addslashes( $ticket['name'] );
                            $ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( $ticket['description'] ) : '';
                            $ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
                            $ticket_data['special_price']  = '';
                            $ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
                            $ticket_data['is_default']     = 1;
                            $ticket_data['is_event_price'] = 0;
                            $ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
                            $ticket_data['priority'] 	   = 1;
                            $ticket_data['status'] 		   = 1;
                            $ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());

                            // new
                            $ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
                            $ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
                            $ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
                            // date
                            $start_date = [];
                            if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
                                $start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
                                if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
                                    if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
                                        $start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
                                    }
                                    if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
                                        $start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
                                    }
                                } elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
                                    $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                } elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
                                    if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
                                        $start_date['days'] = $ticket['em_ticket_start_booking_days'];
                                    }
                                    if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
                                        $start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
                                    }
                                    $start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
                                }
                            }
                            $ticket_data['booking_starts'] = json_encode( $start_date );
                            // end date
                            $end_date = [];
                            if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
                                $end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
                                if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
                                    if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
                                        $end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
                                    }
                                    if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
                                        $end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
                                    }
                                } elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
                                    $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                } elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
                                    if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
                                        $end_date['days'] = $ticket['em_ticket_ends_booking_days'];
                                    }
                                    if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
                                        $end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
                                    }
                                    $end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
                                }
                            }
                            $ticket_data['booking_ends'] = json_encode( $end_date );

                            $ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
                            $ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
                            $ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;

                            $result = $wpdb->insert( $price_options_table, $ticket_data );
                        }
                    }
                }
            }

            // delete tickets
            if( isset( $_POST['em_ticket_individual_delete_ids'] ) && !empty( $_POST['em_ticket_individual_delete_ids'] ) ) {
                $em_ticket_individual_delete_ids = $_POST['em_ticket_individual_delete_ids'];
                $del_ids = json_decode( stripslashes( $em_ticket_individual_delete_ids ) );
                if( is_string( $em_ticket_individual_delete_ids ) && is_array( json_decode( stripslashes( $em_ticket_individual_delete_ids ) ) ) &&  json_last_error() == JSON_ERROR_NONE ) {
                    foreach( $del_ids as $id ) {
                        $get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $id ) );
                        if( ! empty( $get_field_data ) ) {
                            $wpdb->delete( $price_options_table, array( 'id' => $id ) );
                        }
                    }
                }
            }
            
            // handel recurring events request
            /* if( isset( $post_data['em_enable_recurrence'] ) && $post_data['em_enable_recurrence'] == 1 ) {
                update_post_meta( $post_id, 'em_enable_recurrence', 1 );
                $em_recurrence_step = (isset( $post_data['em_recurrence_step'] ) && !empty( $post_data['em_recurrence_step'] ) ) ? absint( $post_data['em_recurrence_step'] ) : 1;
                update_post_meta( $post_id, 'em_recurrence_step', $em_recurrence_step );
                if( isset( $post_data['em_recurrence_interval'] ) && ! empty( $post_data['em_recurrence_interval'] ) ) { 
                    $em_recurrence_interval = sanitize_text_field( $post_data['em_recurrence_interval'] );
                    update_post_meta( $post_id, 'em_recurrence_interval', $em_recurrence_interval );

                    // first delete old child events
                    $metaboxes_controllers->ep_delete_child_events( $post_id );

                    $em_recurrence_ends = (isset( $post_data['em_recurrence_ends'] ) && !empty( $post_data['em_recurrence_ends'] ) ) ? $post_data['em_recurrence_ends'] : 'after';
                    update_post_meta( $post_id, 'em_recurrence_ends', $em_recurrence_ends );
                    $last_date_on = $stop_after = $recurrence_limit_timestamp = $start_date_only = '';
                    if( $em_recurrence_ends == 'on' ) {
                        $last_date_on = ep_date_to_timestamp( sanitize_text_field( $post_data['em_recurrence_limit'] ) );
                        update_post_meta( $post_id, 'em_recurrence_limit', $last_date_on );
                        $recurrence_limit = new DateTime( '@' . $last_date_on );
                        //$recurrence_limit->setTime( 0,0,0,0 );
                        $recurrence_limit_timestamp = $recurrence_limit->getTimestamp();
                        // update start date format
                        $start_date_only = new DateTime( '@' . $em_start_date );
                        $start_date_only->setTime( 0,0,0,0 );
                    }
                    if( $em_recurrence_ends == 'after' ) {
                        $stop_after = absint( $post_data['em_recurrence_occurrence_time'] );
                        update_post_meta( $post_id, 'em_recurrence_occurrence_time', $stop_after );
                    }

                    $data = array( 
                        'start_date' => $em_start_date,
                        'start_time' => $em_start_time,
                        'end_date' => $em_end_date,
                        'end_time' => $em_end_time,
                        'recurrence_step' => $em_recurrence_step,
                        'recurrence_interval' => $em_recurrence_interval,
                        'last_date_on' => $last_date_on,
                        'stop_after' => $stop_after,
                        'recurrence_limit_timestamp' => $recurrence_limit_timestamp,
                        'start_date_only' => $start_date_only,
                        'em_add_slug_in_event_title' => isset($post_data['em_add_slug_in_event_title']) ? $post_data['em_add_slug_in_event_title'] : '',
                        'em_event_slug_type_options' => isset($post_data['em_event_slug_type_options']) ? $post_data['em_event_slug_type_options'] : '',
                        'em_recurring_events_slug_format' => isset($post_data['em_recurring_events_slug_format']) ? $post_data['em_recurring_events_slug_format'] : '',
                        'em_selected_weekly_day' => isset($post_data['em_selected_weekly_day0']) ? $post_data['em_selected_weekly_day'] : '',
                        'em_recurrence_monthly_weekno' => isset($post_data['em_recurrence_monthly_weekno']) ? $post_data['em_recurrence_monthly_weekno'] : '',
                        'em_recurrence_monthly_fullweekday' => isset($post_data['em_recurrence_monthly_fullweekday']) ? $post_data['em_recurrence_monthly_fullweekday'] : '',
                        'em_recurrence_monthly_day' => isset($post_data['em_recurrence_monthly_day']) ? $post_data['em_recurrence_monthly_day'] : '',
                        'em_recurrence_yearly_weekno' => isset($post_data['em_recurrence_yearly_weekno']) ? $post_data['em_recurrence_yearly_weekno'] : '',
                        'em_recurrence_yearly_fullweekday' => isset($post_data['em_recurrence_yearly_fullweekday']) ? $post_data['em_recurrence_yearly_fullweekday'] : '',
                        'em_recurrence_yearly_monthday' => isset($post_data['em_recurrence_yearly_monthday']) ? $post_data['em_recurrence_yearly_monthday'] : '',
                        'em_recurrence_yearly_day' => isset($post_data['em_recurrence_yearly_day']) ? $post_data['em_recurrence_yearly_day'] : '',
                        'em_recurrence_advanced_dates' => isset($post_data['em_recurrence_advanced_dates']) ? json_encode($post_data['em_recurrence_advanced_dates']) : '',
                        'em_recurrence_selected_custom_dates' => isset($post_data['em_recurrence_selected_custom_dates']) ? $post_data['em_recurrence_selected_custom_dates'] : '',
                    );
                                    
                    switch( $em_recurrence_interval ) {
                        case 'daily':
                            $metaboxes_controllers->ep_event_daily_recurrence( $post, $data, $post_data );
                            break;
                        case 'weekly':
                            $metaboxes_controllers->ep_event_weekly_recurrence( $post, $data, $post_data );
                            break;
                        case 'monthly':
                            $metaboxes_controllers->ep_event_monthly_recurrence( $post, $data, $post_data );
                            break;
                        case 'yearly':
                            $metaboxes_controllers->ep_event_yearly_recurrence( $post, $data, $post_data );
                            break;
                        case 'advanced':
                            $metaboxes_controllers->ep_event_advanced_recurrence( $post, $data, $post_data );
                            break;
                        case 'custom_dates':
                            $metaboxes_controllers->ep_event_custom_dates_recurrence( $post, $data, $post_data );
                            break;
                    }
                }
            } */
        }
        return $post_id;
    }

}