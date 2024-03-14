<?php
/**
 * Class for global settings
 */

defined( 'ABSPATH' ) || exit;

class EventM_Admin_Controller_Settings {

    public function save_settings() {
        if( current_user_can( 'manage_options' ) ) {
            // setting type
            if( isset( $_POST['em_setting_type'] ) && ! empty( $_POST['em_setting_type'] ) ) {
                $setting_type = sanitize_text_field( $_POST['em_setting_type'] );
                if( $setting_type == 'regular_settings' ) {
                    $this->save_regular_settings();
                }else if($setting_type == 'timezone_settings'){
                    $this->save_timezone_settings();
                }else if($setting_type == 'external_settings'){
                    $this->save_external_settings();
                }else if($setting_type == 'seo_settings'){
                    $this->save_seo_settings();
                }else if($setting_type == 'payment_settings'){
                    $this->save_payment_settings();
                }else if ($setting_type == 'page_settings'){
                    $this->save_page_settings();
                } elseif( $setting_type == 'customcss_settings' ) {
                    $this->save_custom_css_settings();
                }else if ($setting_type == 'email_settings'){
                    $this->save_email_settings();
                } elseif( $setting_type == 'front_events_settings' ){
                    $this->save_front_events_settings();
                } elseif( $setting_type == 'event_type_settings' ){
                    $this->save_event_type_settings();
                } elseif( $setting_type == 'performer_settings' ){
                    $this->save_event_performer_settings();
                } elseif( $setting_type == 'organizer_settings' ){
                    $this->save_event_organizer_settings();
                } elseif( $setting_type == 'venue_settings' ){
                    $this->save_event_venue_settings();
                } elseif( $setting_type == 'login_form_settings' ){
                    $this->save_login_form_settings();
                } elseif( $setting_type == 'register_form_settings' ){
                    $this->save_register_form_settings();
                } elseif( $setting_type == 'front_event_submission_settings' ){
                    $this->save_frontend_sub_form_settings();
                } elseif( $setting_type == 'button_labels_settings' ){
                    $this->save_button_labels_settings();
                } elseif( $setting_type == 'checkout_registration_form_settings'){
                    $this->save_checkout_registration_form_settings();
                } elseif( $setting_type == 'front_event_details_settings'){
                    $this->save_front_event_details_settings();
                }
                
                // hook for save global settings from extensions
                do_action( 'ep_submit_global_setting' );
            }
        } else{
            EventM_Admin_Notices::ep_add_notice( 'error', esc_html__('You don\'t have permission to update settings.', 'eventprime-event-calendar-management' ) );
            echo "<script type='text/javascript'>
                window.location=document.location.href;
                </script>";
        }
    }

    /**
     * Save general settings - regular setting tab
     */
    public function save_regular_settings() {
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $form_data = $_POST;
        
        $global_settings_data->time_format                    = ( ! empty( $form_data['time_format'] ) ? sanitize_text_field( $form_data['time_format'] ) : 'h:mmt' );
        $global_settings_data->required_booking_attendee_name = isset($form_data['required_booking_attendee_name']) ? (int) $form_data['required_booking_attendee_name'] : 0;
        $global_settings_data->hide_0_price_from_frontend     = isset($form_data['hide_0_price_from_frontend']) ? (int) $form_data['hide_0_price_from_frontend'] : 0;
        $global_settings_data->datepicker_format              = sanitize_text_field($form_data['datepicker_format']);
        $global_settings_data->show_qr_code_on_ticket         = isset($form_data['show_qr_code_on_ticket']) ? (int) $form_data['show_qr_code_on_ticket'] : 0;
        $global_settings_data->checkout_page_timer            = isset( $form_data['checkout_page_timer'] ) ? absint( $form_data['checkout_page_timer'] ) : 4;
        $global_settings_data->ep_frontend_font_size          = isset( $form_data['ep_frontend_font_size'] ) ? absint( $form_data['ep_frontend_font_size'] ) : 14;
        $global_settings_data->hide_wishlist_icon             = isset( $form_data['hide_wishlist_icon'] ) ? absint( $form_data['hide_wishlist_icon'] ) : 0;
        $global_settings_data->enable_dark_mode               = isset( $form_data['enable_dark_mode'] ) ? absint( $form_data['enable_dark_mode'] ) : 0;
        $global_settings->ep_save_settings( $global_settings_data );

        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=general" );
        wp_redirect($redirect_url);
        exit();   
    }
    
    /*
     * Save general settings - timezone setting tab
     */
    
    public function save_timezone_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $form_data = $_POST;
        
        $global_settings_data->enable_event_time_to_user_timezone  = isset( $form_data['enable_event_time_to_user_timezone'] ) ? absint( $form_data['enable_event_time_to_user_timezone'] ) : 0;
        $global_settings_data->show_timezone_message_on_event_page = isset( $form_data['show_timezone_message_on_event_page'] ) ? absint( $form_data['show_timezone_message_on_event_page'] ) : 0;
        $global_settings_data->timezone_related_message            = isset( $form_data['timezone_related_message'] ) ? $form_data['timezone_related_message'] : '';
        $global_settings->ep_save_settings( $global_settings_data );

        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=general&sub_tab=timezone" );
        wp_redirect($redirect_url);
        exit();  
    }
     
    /**
     * Save general settings - external setting tab
     */
    public function save_external_settings() {
        $global_settings                                   = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                              = $global_settings->ep_get_settings();
        $form_data                                         = $_POST;
        
        $global_settings_data->gmap_api_key                = sanitize_text_field($form_data['gmap_api_key']);
        $global_settings_data->social_sharing              = isset($form_data['social_sharing']) ? (int) $form_data['social_sharing'] : 0;
        $global_settings_data->gcal_sharing                = isset($form_data['gcal_sharing']) ? (int) $form_data['gcal_sharing'] : 0;
        $global_settings_data->google_cal_client_id        = sanitize_text_field($form_data['google_cal_client_id']);
        $global_settings_data->google_cal_api_key          = sanitize_text_field($form_data['google_cal_api_key']);
        $global_settings_data->google_recaptcha            = isset($form_data['google_recaptcha']) && !empty($form_data['google_recaptcha']) ? 1 : 0;
        $global_settings_data->google_recaptcha_site_key   = sanitize_text_field($form_data['google_recaptcha_site_key']);
        $global_settings_data->google_recaptcha_secret_key = sanitize_text_field($form_data['google_recaptcha_secret_key']);

        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__( 'Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=general&sub_tab=external" );
        wp_redirect( $redirect_url );
        exit();
    }

    /**
     * Save general settings - seo setting tab
     */
    public function save_seo_settings() {
        $global_settings                       = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                  = $global_settings->ep_get_settings();
        $form_data                             = $_POST;
        $seo_settings                          = new stdClass();
        $seo_settings->event_page_type_url     = ( ! empty( $form_data['event_page_type_url'] ) ) ? sanitize_text_field( $form_data['event_page_type_url'] ) : '';
        $seo_settings->performer_page_type_url = ( ! empty( $form_data['performer_page_type_url'] ) ) ? sanitize_text_field( $form_data['performer_page_type_url'] ) : '';
        $seo_settings->organizer_page_type_url = ( ! empty( $form_data['organizer_page_type_url'] ) ) ? sanitize_text_field( $form_data['organizer_page_type_url'] ) : '';
        $seo_settings->venues_page_type_url    = ( ! empty( $form_data['venues_page_type_url'] ) ) ? sanitize_text_field( $form_data['venues_page_type_url'] ) : '';
        $seo_settings->types_page_type_url     = ( ! empty( $form_data['types_page_type_url'] ) ) ? sanitize_text_field( $form_data['types_page_type_url'] ) : '';
        $seo_settings->sponsor_page_type_url   = ( ! empty( $form_data['sponsor_page_type_url'] ) ) ? sanitize_text_field( $form_data['sponsor_page_type_url'] ) : '';
        //$ep_desk_normal_screen               = sanitize_text_field( $form_data['ep_desk_normal_screen'] );
        //$ep_desk_large_screen                = sanitize_text_field( $form_data['ep_desk_large_screen'] );
        
        $global_settings_data->enable_seo_urls = isset( $form_data['enable_seo_urls'] ) ? (int)$form_data['enable_seo_urls'] : 0;        
        $global_settings_data->seo_urls        = $seo_settings;
        //$global_settings_data->ep_desk_normal_screen = isset( $ep_desk_normal_screen ) ? $ep_desk_normal_screen : '';
        //$global_settings_data->ep_desk_large_screen = isset( $ep_desk_large_screen ) ? $ep_desk_large_screen : '';        

        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__( 'Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=general&sub_tab=seo" );
        wp_redirect( $redirect_url );
        exit();
    }
    
    /**
     * Save Payment Setting tab
     */
    public function save_payment_settings(){
        $payment_gateway = apply_filters('ep_payments_gateways_list', array());
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        
        $form_data = $_POST;
        
        if(isset($form_data) && isset($form_data['em_payment_type'])){
            if($form_data['em_payment_type'] == 'basic'){               
                foreach ($payment_gateway as $key => $method){
                    $enable_key = $method['enable_key'];
                    if(isset($form_data[$enable_key])){
                       $global_settings_data->$enable_key = 1;
                    }else{
                       $global_settings_data->$enable_key = 0;
                    }
                }
               //$global_settings_data->payment_order = $form_data['payment_order'];
               $global_settings_data->currency = sanitize_text_field($form_data['currency']);
               $global_settings_data->currency_position = sanitize_text_field($form_data['currency_position']);
            }
            if($form_data['em_payment_type'] == 'paypal'){
                $global_settings_data->paypal_processor = isset($form_data['paypal_processor']) ? (int) $form_data['paypal_processor'] : 0;
                $global_settings_data->paypal_client_id = sanitize_text_field($form_data['paypal_client_id']);
                
            }
            $global_settings->ep_save_settings( $global_settings_data );
            //update_option(EM_GLOBAL_SETTINGS, $global_settings_data, true);
        }
        do_action('ep_save_payments_setting', $form_data);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        
        //also sent on _wp_http_referer
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=payments" );
        
        wp_redirect($redirect_url);
        exit();
    }
    
    public function save_page_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        
        $form_data = $_POST;
        $global_settings_data->performers_page = isset($form_data['performers_page']) ? sanitize_text_field($form_data['performers_page']) : 0;
        $global_settings_data->venues_page = isset($form_data['venues_page']) ? sanitize_text_field($form_data['venues_page']) : 0;
        $global_settings_data->events_page = isset($form_data['events_page']) ? sanitize_text_field($form_data['events_page']) : 0;
        $global_settings_data->booking_page = isset($form_data['booking_page']) ? sanitize_text_field($form_data['booking_page']) : 0;
        $global_settings_data->profile_page = isset($form_data['profile_page']) ? sanitize_text_field($form_data['profile_page']) : 0;
        $global_settings_data->event_types = isset($form_data['event_types']) ? sanitize_text_field($form_data['event_types']) : 0;
        $global_settings_data->event_submit_form = isset($form_data['event_submit_form']) ? sanitize_text_field($form_data['event_submit_form']) : 0;
        $global_settings_data->booking_details_page = isset($form_data['booking_details_page']) ? sanitize_text_field($form_data['booking_details_page']) : 0;
        $global_settings_data->event_organizers = isset($form_data['event_organizers']) ? sanitize_text_field($form_data['event_organizers']) : 0;
        $global_settings_data->login_page = isset($form_data['login_page']) ? sanitize_text_field($form_data['login_page']) : 0;
        $global_settings_data->register_page = isset($form_data['register_page']) ? sanitize_text_field($form_data['register_page']) : 0;
        
        $global_settings->ep_save_settings( $global_settings_data );
        do_action('ep_save_pages_setting', $form_data);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=pages" );
        wp_redirect($redirect_url);
        exit();
    }
    
    public function save_email_settings(){
        global $wpdb, $wp_roles;
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $form_data = $_POST;
        if(isset($form_data) && isset($form_data['em_emailer_type'])){
            if($form_data['em_emailer_type'] == 'basic'){
                $global_settings_data->disable_admin_email = isset($form_data['disable_admin_email']) ? (int) $form_data['disable_admin_email'] : 0;
                $global_settings_data->disable_frontend_email = isset($form_data['disable_frontend_email']) ? (int) $form_data['disable_frontend_email'] : 0;
            }
            if($form_data['em_emailer_type'] == 'registration'){
                $global_settings_data->registration_email_subject = sanitize_text_field($form_data['registration_email_subject']);
                $global_settings_data->registration_email_content = wp_kses_post($form_data['registration_email_content']);
            }
            if($form_data['em_emailer_type'] == 'reset_password'){
                $global_settings_data->reset_password_mail_subject = sanitize_text_field($form_data['reset_password_mail_subject']);
                $global_settings_data->reset_password_mail = wp_kses_post($form_data['reset_password_mail']);
            }
            if($form_data['em_emailer_type'] == 'booking_pending'){
                $global_settings_data->send_booking_pending_email = isset($form_data['send_booking_pending_email']) ? (int) $form_data['send_booking_pending_email'] : 0;
                $global_settings_data->booking_pending_email_subject = sanitize_text_field($form_data['booking_pending_email_subject']);
                $global_settings_data->booking_pending_email = wp_kses_post($form_data['booking_pending_email']);
                $global_settings_data->booking_pending_email_cc = wp_kses_post($form_data['booking_pending_email_cc']);
            
            }
            if($form_data['em_emailer_type'] == 'booking_confirm'){
                $global_settings_data->send_booking_confirm_email = isset($form_data['send_booking_confirm_email']) ? (int) $form_data['send_booking_confirm_email'] : 0;
                $global_settings_data->booking_confirm_email_subject = sanitize_text_field($form_data['booking_confirm_email_subject']);
                $global_settings_data->booking_confirmed_email = wp_kses_post($form_data['booking_confirmed_email']);
                //->booking_confirmed_email_cc = wp_kses_post($form_data['booking_confirmed_email_cc']);
            
            }
            if($form_data['em_emailer_type'] == 'booking_canceled'){
                $global_settings_data->send_booking_cancellation_email = isset($form_data['send_booking_cancellation_email']) ? (int) $form_data['send_booking_cancellation_email'] : 0;
                $global_settings_data->booking_cancelation_email_subject = sanitize_text_field($form_data['booking_cancelation_email_subject']);
                $global_settings_data->booking_cancelation_email = wp_kses_post($form_data['booking_cancelation_email']);
                $global_settings_data->booking_cancelation_email_cc = wp_kses_post($form_data['booking_cancelation_email_cc']);
            }
            if($form_data['em_emailer_type'] == 'booking_refund'){
                $global_settings_data->send_booking_refund_email = isset($form_data['send_booking_refund_email']) ? (int) $form_data['send_booking_refund_email'] : 0;
                $global_settings_data->booking_refund_email_subject = sanitize_text_field($form_data['booking_refund_email_subject']);
                $global_settings_data->booking_refund_email = wp_kses_post($form_data['booking_refund_email']);
                $global_settings_data->booking_refund_email_cc = wp_kses_post($form_data['booking_refund_email_cc']);
            }
            if($form_data['em_emailer_type'] == 'event_submitted'){
                $global_settings_data->send_event_submitted_email = isset($form_data['send_event_submitted_email']) ? (int) $form_data['send_event_submitted_email'] : 0;
                $global_settings_data->event_submitted_email_subject = sanitize_text_field($form_data['event_submitted_email_subject']);
                $global_settings_data->event_submitted_email = wp_kses_post($form_data['event_submitted_email']);
                $global_settings_data->event_submitted_email_cc = wp_kses_post($form_data['event_submitted_email_cc']);
            }
            if($form_data['em_emailer_type'] == 'event_approval'){
                $global_settings_data->send_event_approved_email = isset($form_data['send_event_approved_email']) ? (int) $form_data['send_event_approved_email'] : 0;
                $global_settings_data->event_approved_email_subject = sanitize_text_field($form_data['event_approved_email_subject']);
                $global_settings_data->event_approved_email = wp_kses_post($form_data['event_approved_email']);
            }
            if($form_data['em_emailer_type'] == 'booking_confirmed_admin'){
                $global_settings_data->send_admin_booking_confirm_email = isset($form_data['send_admin_booking_confirm_email']) ? (int) $form_data['send_admin_booking_confirm_email'] : 0;
                $global_settings_data->admin_booking_confirmed_email_subject = sanitize_text_field($form_data['admin_booking_confirmed_email_subject']);
                $global_settings_data->admin_booking_confirmed_email = wp_kses_post($form_data['admin_booking_confirmed_email']);
                $global_settings_data->admin_booking_confirmed_email_cc = wp_kses_post($form_data['admin_booking_confirmed_email_cc']);
                $global_settings_data->admin_booking_confirm_email_attendees = isset($form_data['admin_booking_confirm_email_attendees']) ? 1 : 0;
            }
            if( isset( $form_data['ep_admin_email_to'] ) && ! empty( $form_data['ep_admin_email_to'] ) ){
                $global_settings_data->ep_admin_email_to = sanitize_email( $form_data['ep_admin_email_to'] );
            }
            if( isset( $form_data['ep_admin_email_from'] ) && ! empty( $form_data['ep_admin_email_from'] ) ){
                $global_settings_data->ep_admin_email_from = sanitize_email( $form_data['ep_admin_email_from'] );
            }
        }
        $global_settings->ep_save_settings( $global_settings_data );
        //update_option(EM_GLOBAL_SETTINGS, $global_settings_data, true);
        
        do_action('ep_save_emailer_setting', $form_data);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=emails" );
        
        wp_redirect($redirect_url);
        exit();
    }

    /**
     * Save custom css
     */
    public function save_custom_css_settings() {
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $global_settings_data->custom_css = isset( $_POST['custom_css']) ? sanitize_text_field( $_POST['custom_css'] ) : '';
        $global_settings->ep_save_settings( $global_settings_data );
        
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=customcss" );
        wp_redirect($redirect_url);
        exit();
    }
    
    /*
     * Save Events Settings
     */
    
    public function save_front_events_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $form_data = $_POST;

        $global_settings_data->default_cal_view                = sanitize_text_field( $form_data['default_cal_view'] );
        $global_settings_data->enable_default_calendar_date    = isset( $form_data['enable_default_calendar_date'] ) ? (int) $form_data['enable_default_calendar_date'] : 0;
        $global_settings_data->default_calendar_date           = isset( $form_data['default_calendar_date'] ) ? sanitize_text_field( $form_data['default_calendar_date'] ) : '';
        $global_settings_data->calendar_title_format           = sanitize_text_field( $form_data['calendar_title_format'] );
        $global_settings_data->hide_calendar_rows              = isset( $form_data['hide_calendar_rows'] ) ? (int) $form_data['hide_calendar_rows'] : 0;
        $global_settings_data->hide_time_on_front_calendar     = isset( $form_data['hide_time_on_front_calendar'] ) ? (int) $form_data['hide_time_on_front_calendar'] : 0;
        $global_settings_data->front_switch_view_option        = $form_data['front_switch_view_option'];
        $global_settings_data->hide_past_events                = isset($form_data['hide_past_events']) ? (int) $form_data['hide_past_events'] : 0;
        $global_settings_data->show_no_of_events_card          = sanitize_text_field( $form_data['show_no_of_events_card'] );
        $global_settings_data->card_view_custom_value          = isset( $form_data['card_view_custom_value'] ) ? (int) $form_data['card_view_custom_value'] : 1;
        $global_settings_data->disable_filter_options          = isset( $form_data['disable_filter_options'] ) ? (int) $form_data['disable_filter_options'] : 0;
        $global_settings_data->hide_old_bookings               = isset( $form_data['hide_old_bookings'] ) ? (int) $form_data['hide_old_bookings'] : 0;
        $global_settings_data->calendar_column_header_format   = sanitize_text_field( $form_data['calendar_column_header_format'] );
        $global_settings_data->shortcode_hide_upcoming_events  = isset( $form_data['shortcode_hide_upcoming_events'] ) ? (int) $form_data['shortcode_hide_upcoming_events'] : 0;
        $global_settings_data->redirect_third_party            = isset( $form_data['redirect_third_party'] ) ? (int) $form_data['redirect_third_party'] : 0;
        $global_settings_data->hide_event_custom_link          = isset( $form_data['hide_event_custom_link'] ) ? (int) $form_data['hide_event_custom_link'] : 0;
        $global_settings_data->show_max_event_on_calendar_date = isset( $form_data['show_max_event_on_calendar_date'] ) ? (int) $form_data['show_max_event_on_calendar_date'] : 2;
        $global_settings_data->event_booking_status_option     = isset( $form_data['event_booking_status_option'] ) ? $form_data['event_booking_status_option'] : '';
        $global_settings_data->open_detail_page_in_new_tab     = isset( $form_data['open_detail_page_in_new_tab'] ) ? $form_data['open_detail_page_in_new_tab'] : 0;
        $global_settings_data->events_no_of_columns            = ( ! empty( $form_data['events_no_of_columns'] ) ) ? absint( $form_data['events_no_of_columns'] ) : '';
        $global_settings_data->events_image_visibility_options = ( ! empty( $form_data['events_image_visibility_options'] ) ) ? sanitize_text_field( $form_data['events_image_visibility_options'] ) : '';
        $global_settings_data->events_image_height             = ( ! empty( $form_data['events_image_height'] ) ) ? absint( $form_data['events_image_height'] ) : '';
        // trending event type settings
        $global_settings_data->show_trending_event_types       = isset( $form_data['show_trending_event_types'] ) ? (int) $form_data['show_trending_event_types'] : 0;
        $global_settings_data->no_of_event_types_displayed     = ( ! empty( $form_data['no_of_event_types_displayed'] ) ) ? (int) $form_data['no_of_event_types_displayed'] : 5;
        $global_settings_data->show_events_per_event_type      = isset( $form_data['show_events_per_event_type'] ) ? (int) $form_data['show_events_per_event_type'] : 0;
        $global_settings_data->sort_by_events_or_bookings      = isset( $form_data['sort_by_events_or_bookings'] ) ? $form_data['sort_by_events_or_bookings'] : '';

        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=events" );
        wp_redirect($redirect_url);
        exit();   
    }
    
    /**
     * Save Event Type settings
     */
    public function save_event_type_settings() {
        $global_settings                                      = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                                 = $global_settings->ep_get_settings();
        $global_settings_data->type_display_view              = isset( $_POST['type_display_view'] ) ? sanitize_text_field( $_POST['type_display_view'] ) : 'card';
        $global_settings_data->type_box_color                 = isset( $_POST['type_box_color'] ) ? array_map( 'sanitize_text_field', $_POST['type_box_color'] ) : '';
        $global_settings_data->type_limit                     = isset( $_POST['type_limit'] ) ? absint( $_POST['type_limit'] ) : 0;
        $global_settings_data->type_no_of_columns             = isset( $_POST['type_no_of_columns'] ) ? absint( $_POST['type_no_of_columns'] ) : 4;
        $global_settings_data->type_load_more                 = isset( $_POST['type_load_more'] ) ? 1 : 0;
        $global_settings_data->type_search                    = isset( $_POST['type_search'] ) ? 1 : 0;
        $global_settings_data->single_type_show_events        = isset( $_POST['single_type_show_events'] ) ? 1 : 0;
        $global_settings_data->single_type_event_display_view = isset( $_POST['single_type_event_display_view'] ) ? sanitize_text_field( $_POST['single_type_event_display_view'] ) : 'card';
        $global_settings_data->single_type_event_limit        = isset( $_POST['single_type_event_limit'] ) ? absint( $_POST['single_type_event_limit'] ) : 0;
        $global_settings_data->single_type_event_column       = isset( $_POST['single_type_event_column'] ) ? absint( $_POST['single_type_event_column'] ) : 4;
        $global_settings_data->single_type_event_load_more    = isset( $_POST['single_type_event_load_more'] ) ? 1 : 0;
        $global_settings_data->single_type_hide_past_events   = isset( $_POST['single_type_hide_past_events'] ) ? 1 : 0;
        $global_settings_data->single_type_event_order    = isset( $_POST['single_type_event_order'] ) ? sanitize_text_field( $_POST['single_type_event_order'] ) : 'asc';
        $global_settings_data->single_type_event_orderby   = isset( $_POST['single_type_event_orderby'] )? sanitize_text_field( $_POST['single_type_event_orderby'] ) : 'em_start_date_time';
        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=eventtypes" );
        wp_redirect( $redirect_url );
        exit();
    }

    /**
     * Save Event Performer settings
     */
    public function save_event_performer_settings() {
        $global_settings                                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                                      = $global_settings->ep_get_settings();
        $global_settings_data->performer_display_view              = isset( $_POST['performer_display_view'] ) ? sanitize_text_field( $_POST['performer_display_view'] ) : 'card';
        $global_settings_data->performer_box_color                 = isset( $_POST['performer_box_color'] ) ? array_map( 'sanitize_text_field', $_POST['performer_box_color'] ) : '';
        $global_settings_data->performer_limit                     = isset( $_POST['performer_limit'] ) ? absint( $_POST['performer_limit'] ) : 0;
        $global_settings_data->performer_no_of_columns             = isset( $_POST['performer_no_of_columns'] ) ? absint( $_POST['performer_no_of_columns'] ) : 4;
        $global_settings_data->performer_load_more                 = isset( $_POST['performer_load_more'] ) ? 1 : 0;
        $global_settings_data->performer_search                    = isset( $_POST['performer_search'] ) ? 1 : 0;
        $global_settings_data->single_performer_show_events        = isset( $_POST['single_performer_show_events'] ) ? 1 : 0;
        $global_settings_data->single_performer_event_display_view = isset( $_POST['single_performer_event_display_view'] ) ? sanitize_text_field( $_POST['single_performer_event_display_view'] ) : 'card';
        $global_settings_data->single_performer_event_limit        = isset( $_POST['single_performer_event_limit'] ) ? absint( $_POST['single_performer_event_limit'] ) : 0;
        $global_settings_data->single_performer_event_column       = isset( $_POST['single_performer_event_column'] ) ? absint( $_POST['single_performer_event_column'] ) : 4;
        $global_settings_data->single_performer_event_load_more    = isset( $_POST['single_performer_event_load_more'] ) ? 1 : 0;
        $global_settings_data->single_performer_hide_past_events   = isset( $_POST['single_performer_hide_past_events'] ) ? 1 : 0;
        $global_settings->ep_save_settings( $global_settings_data );
        
        do_action('ep_save_performer_setting', $_POST);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=performers" );
        wp_redirect( $redirect_url );
        exit();
    }
    
    /**
     * Save Event Organizer settings
     */
    public function save_event_organizer_settings() {
        $global_settings                                           = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                                      = $global_settings->ep_get_settings();
        $global_settings_data->organizer_display_view              = isset( $_POST['organizer_display_view'] ) ? sanitize_text_field( $_POST['organizer_display_view'] ) : 'card';
        $global_settings_data->organizer_box_color                 = isset( $_POST['organizer_box_color'] ) ? array_map( 'sanitize_text_field', $_POST['organizer_box_color'] ) : '';
        $global_settings_data->organizer_limit                     = isset( $_POST['organizer_limit'] ) ? absint( $_POST['organizer_limit'] ) : 0;
        $global_settings_data->organizer_no_of_columns             = isset( $_POST['organizer_no_of_columns'] ) ? absint( $_POST['organizer_no_of_columns'] ) : 4;
        $global_settings_data->organizer_load_more                 = isset( $_POST['organizer_load_more'] ) ? 1 : 0;
        $global_settings_data->organizer_search                    = isset( $_POST['organizer_search'] ) ? 1 : 0;
        $global_settings_data->single_organizer_show_events        = isset( $_POST['single_organizer_show_events'] ) ? 1 : 0;
        $global_settings_data->single_organizer_event_display_view = isset( $_POST['single_organizer_event_display_view'] ) ? sanitize_text_field( $_POST['single_organizer_event_display_view'] ) : 'card';
        $global_settings_data->single_organizer_event_limit        = isset( $_POST['single_organizer_event_limit'] ) ? absint( $_POST['single_organizer_event_limit'] ) : 0;
        $global_settings_data->single_organizer_event_column       = isset( $_POST['single_organizer_event_column'] ) ? absint( $_POST['single_organizer_event_column'] ) : 4;
        $global_settings_data->single_organizer_event_load_more    = isset( $_POST['single_organizer_event_load_more'] ) ? 1 : 0;
        $global_settings_data->single_organizer_hide_past_events   = isset( $_POST['single_organizer_hide_past_events'] ) ? 1 : 0;
        $global_settings->ep_save_settings( $global_settings_data );
        do_action('ep_save_organizer_setting', $_POST);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=organizers" );
        wp_redirect( $redirect_url );
        exit();
    }
    
    /**
     * Save Event Venue settings
     */
    public function save_event_venue_settings() {
        $global_settings                                       = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data                                  = $global_settings->ep_get_settings();
        $global_settings_data->venue_display_view              = isset( $_POST['venue_display_view'] ) ? sanitize_text_field( $_POST['venue_display_view'] ) : 'card';
        $global_settings_data->venue_box_color                 = isset( $_POST['venue_box_color'] ) ? array_map( 'sanitize_text_field', $_POST['venue_box_color'] ) : '';
        $global_settings_data->venue_limit                     = isset( $_POST['venue_limit'] ) ? absint( $_POST['venue_limit'] ) : 0;
        $global_settings_data->venue_no_of_columns             = isset( $_POST['venue_no_of_columns'] ) ? absint( $_POST['venue_no_of_columns'] ) : 4;
        $global_settings_data->venue_load_more                 = isset( $_POST['venue_load_more'] ) ? 1 : 0;
        $global_settings_data->venue_search                    = isset( $_POST['venue_search'] ) ? 1 : 0;
        $global_settings_data->single_venue_show_events        = isset( $_POST['single_venue_show_events'] ) ? 1 : 0;
        $global_settings_data->single_venue_event_display_view = isset( $_POST['single_venue_event_display_view'] ) ? sanitize_text_field( $_POST['single_venue_event_display_view'] ) : 'card';
        $global_settings_data->single_venue_event_limit        = isset( $_POST['single_venue_event_limit'] ) ? absint( $_POST['single_venue_event_limit'] ) : 0;
        $global_settings_data->single_venue_event_column       = isset( $_POST['single_venue_event_column'] ) ? absint( $_POST['single_venue_event_column'] ) : 4;
        $global_settings_data->single_venue_event_load_more    = isset( $_POST['single_venue_event_load_more'] ) ? 1 : 0;
        $global_settings_data->single_venue_hide_past_events   = isset( $_POST['single_venue_hide_past_events'] ) ? 1 : 0;
        $global_settings->ep_save_settings( $global_settings_data );
        do_action('ep_save_venue_setting', $_POST);
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=venues" );
        wp_redirect( $redirect_url );
        exit();
    }

    /**
     * Save Login form settings
     */
    public function save_login_form_settings() {
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        
        $global_settings_data->login_id_field                  = isset( $_POST['login_id_field'] ) ? sanitize_text_field( $_POST['login_id_field'] ) : 'username';
        $global_settings_data->login_id_field_label_setting    = isset( $_POST['login_id_field_label_setting'] ) ? sanitize_text_field( $_POST['login_id_field_label_setting'] ) : '';
        $global_settings_data->login_show_rememberme           = isset( $_POST['login_show_rememberme'] ) ? 1 : 0;
        $global_settings_data->login_show_rememberme_label     = isset( $_POST['login_show_rememberme_label'] ) ? sanitize_text_field( $_POST['login_show_rememberme_label'] ) : '';
        $global_settings_data->login_show_forgotpassword       = isset( $_POST['login_show_forgotpassword'] ) ? 1 : 0;
        $global_settings_data->login_show_forgotpassword_label = isset( $_POST['login_show_forgotpassword_label'] ) ? sanitize_text_field( $_POST['login_show_forgotpassword_label'] ) : '';
        $global_settings_data->login_google_recaptcha       = isset( $_POST['login_google_recaptcha'] ) ? 1 : 0;
        //$global_settings_data->login_google_recaptcha_label = isset( $_POST['login_google_recaptcha_label'] ) ? sanitize_text_field( $_POST['login_google_recaptcha_label'] ) : '';
        
        $global_settings_data->login_password_label            = isset( $_POST['login_password_label'] ) ? sanitize_text_field( $_POST['login_password_label'] ) : '';
        $global_settings_data->login_heading_text              = isset( $_POST['login_heading_text'] ) ? sanitize_text_field( $_POST['login_heading_text'] ) : '';
        $global_settings_data->login_subheading_text           = isset( $_POST['login_subheading_text'] ) ? sanitize_text_field( $_POST['login_subheading_text'] ) : '';
        $global_settings_data->login_button_label              = isset( $_POST['login_button_label'] ) ? sanitize_text_field( $_POST['login_button_label'] ) : '';
        $global_settings_data->login_redirect_after_login      = isset( $_POST['login_redirect_after_login'] ) ? sanitize_text_field( $_POST['login_redirect_after_login'] ) : '';
        $global_settings_data->login_show_registerlink       = isset( $_POST['login_show_registerlink'] ) ? 1 : 0;
        $global_settings_data->login_show_registerlink_label = isset( $_POST['login_show_registerlink_label'] ) ? sanitize_text_field( $_POST['login_show_registerlink_label'] ) : '';
        
        $global_settings->ep_save_settings( $global_settings_data );
        
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=forms&section=login" );
        wp_redirect($redirect_url);
        exit();
    }

    /**
     * Save Register form settings
     */
    public function save_register_form_settings() {
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $global_settings_data->login_registration_form       = isset( $_POST['login_registration_form'] ) ? sanitize_text_field( $_POST['login_registration_form'] ) : '';
        $global_settings_data->login_rm_registration_form    = isset( $_POST['login_rm_registration_form'] ) ? sanitize_text_field( $_POST['login_rm_registration_form'] ) : '';
        $global_settings_data->register_google_recaptcha     = isset( $_POST['register_google_recaptcha']) ? 1 : 0;
        $global_settings_data->register_username             = isset( $_POST['register_username'] ) ? $_POST['register_username'] : array();
        $global_settings_data->register_email                = isset( $_POST['register_email'] ) ? $_POST['register_email'] : array();
        $global_settings_data->register_password             = isset( $_POST['register_password'] ) ? $_POST['register_password'] : array();
        $global_settings_data->register_repeat_password      = isset( $_POST['register_repeat_password'] ) ? $_POST['register_repeat_password'] : array();
        $global_settings_data->register_dob                  = isset( $_POST['register_dob'] ) ? $_POST['register_dob'] : array();
        $global_settings_data->register_phone                = isset( $_POST['register_phone'] ) ? $_POST['register_phone'] : array();
        $global_settings_data->register_timezone             = isset( $_POST['register_timezone'] ) ? $_POST['register_timezone'] : array();
        
        $global_settings->ep_save_settings( $global_settings_data );
        
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=forms&section=register" );
        wp_redirect($redirect_url);
        exit();
    }

    /*
     * Save Checkout Registration Form
     */
    
    public function save_checkout_registration_form_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        
        $global_settings_data->checkout_register_fname             = isset( $_POST['checkout_register_fname'] ) ? $_POST['checkout_register_fname'] : array();
        $global_settings_data->checkout_register_lname             = isset( $_POST['checkout_register_lname'] ) ? $_POST['checkout_register_lname'] : array();
        $global_settings_data->checkout_register_username             = isset( $_POST['checkout_register_username'] ) ? $_POST['checkout_register_username'] : array();
        $global_settings_data->checkout_register_email                = isset( $_POST['checkout_register_email'] ) ? $_POST['checkout_register_email'] : array();
        $global_settings_data->checkout_register_password             = isset( $_POST['checkout_register_password'] ) ? $_POST['checkout_register_password'] : array();
        $global_settings_data->checkout_reg_google_recaptcha = isset($_POST['checkout_reg_google_recaptcha']) && !empty($_POST['checkout_reg_google_recaptcha']) ? 1 : 0;
        $global_settings->ep_save_settings( $global_settings_data );
        
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=forms&section=checkout_registration" );
        wp_redirect($redirect_url);
        exit();
    }
    
    /*
     * Save Frontend Submission Form
     */
    public function save_frontend_sub_form_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $global_settings_data->ues_confirm_message                  = isset( $_POST['ues_confirm_message'] ) ? sanitize_text_field($_POST['ues_confirm_message']) : '';
        $global_settings_data->allow_submission_by_anonymous_user   = isset( $_POST['allow_submission_by_anonymous_user'] ) ? 1 : 0;
        $global_settings_data->ues_login_message                    = isset( $_POST['ues_login_message'] ) ? sanitize_text_field( $_POST['ues_login_message'] ) : '';
        $global_settings_data->ues_default_status                   = isset( $_POST['ues_default_status'] ) ? sanitize_text_field( $_POST['ues_default_status'] ) : '';
        $global_settings_data->frontend_submission_roles            = isset( $_POST['frontend_submission_roles']) ? $_POST['frontend_submission_roles'] : array();
        $global_settings_data->ues_restricted_submission_message    = isset( $_POST['ues_restricted_submission_message'] ) ? $_POST['ues_restricted_submission_message'] : '';
        $global_settings_data->frontend_submission_sections         = isset( $_POST['frontend_submission_sections'] ) ? $_POST['frontend_submission_sections'] : array();
        $global_settings_data->frontend_submission_required         = isset( $_POST['frontend_submission_required'] ) ? $_POST['frontend_submission_required'] : array();
        $global_settings_data->fes_allow_media_library              = isset( $_POST['fes_allow_media_library'] ) ? 1 : 0;
        $global_settings_data->fes_allow_user_to_delete_event       = isset( $_POST['fes_allow_user_to_delete_event'] ) ? 1 : 0;
        $global_settings_data->fes_show_add_event_in_profile        = isset( $_POST['fes_show_add_event_in_profile'] ) ? 1 : 0;
        
        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=forms&section=fes" );
        wp_redirect($redirect_url);
        exit();
    }

    /**
     * Save button labels settings
     */
    public function save_button_labels_settings() {
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $button_titles = array();
        if( isset( $_POST['button_titles'] ) && ! empty( $_POST['button_titles'] ) ) {
            foreach( $_POST['button_titles'] as $bt_key => $bt ) {
                $button_titles[$bt_key] = sanitize_text_field( $bt );
            }
        }
        $global_settings_data->button_titles = $button_titles;
        // save global settings
        $global_settings->ep_save_settings( $global_settings_data );
        // redirect and show message
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__('Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=buttonlabels" );
        wp_redirect( $redirect_url );
        exit();
    }
    
    /**
     * Get checkout fields data
     */
    public function ep_get_checkout_fields_data() {
        global $wpdb;
        $get_field_data = array();
        $table_name = $wpdb->prefix.'eventprime_checkout_fields';
        $check_checkout_fields_table = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s ",
                $wpdb->dbname,
                $table_name
            )
        );
        if( ! empty( $check_checkout_fields_table ) ) {
            $get_field_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC", OBJECT_K );
        }
        return $get_field_data;
    }
    
    public function save_front_event_details_settings(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_settings_data = $global_settings->ep_get_settings();
        $form_data = $_POST;
        $global_settings_data->hide_weather_tab = ( ! empty( $form_data['hide_weather_tab'] ) ? 1 : 0 );
        if( empty( $global_settings_data->hide_weather_tab ) ) {
            $global_settings_data->weather_unit_fahrenheit  = ( ! empty( $form_data['weather_unit_fahrenheit'] ) ? 1 : 0 );
        } else{
            $global_settings_data->weather_unit_fahrenheit  = 0;
        }
        $global_settings_data->hide_map_tab                       = ( ! empty( $form_data['hide_map_tab'] ) ? 1 : 0 );
        $global_settings_data->hide_other_event_tab               = ( ! empty( $form_data['hide_other_event_tab'] ) ? 1 : 0 );
        $global_settings_data->hide_age_group_section             = ( ! empty( $form_data['hide_age_group_section'] ) ? 1 : 0 );
        $global_settings_data->hide_note_section                  = ( ! empty( $form_data['hide_note_section'] ) ? 1 : 0 );
        $global_settings_data->show_qr_code_on_single_event       = ( ! empty( $form_data['show_qr_code_on_single_event'] ) ? 1 : 0 );
        $global_settings_data->hide_performers_section            = ( ! empty( $form_data['hide_performers_section'] ) ? 1 : 0 );
        $global_settings_data->hide_organizers_section            = ( ! empty( $form_data['hide_organizers_section'] ) ? 1 : 0 );
        $global_settings_data->event_detail_image_width           = ( ! empty( $form_data['event_detail_image_width'] ) ? $form_data['event_detail_image_width'] : '' );
        $global_settings_data->event_detail_image_height          = ( ! empty( $form_data['event_detail_image_height'] ) ? $form_data['event_detail_image_height'] : 'auto' );
        $global_settings_data->event_detail_image_height_custom   = ( ! empty( $form_data['event_detail_image_height_custom'] ) ? $form_data['event_detail_image_height_custom'] : '' );
        $global_settings_data->event_detail_image_align           = ( ! empty( $form_data['event_detail_image_align'] ) ? $form_data['event_detail_image_align'] : '' );
        $global_settings_data->event_detail_image_auto_scroll     = ( ! empty( $form_data['event_detail_image_auto_scroll'] ) ? $form_data['event_detail_image_auto_scroll'] : 0 );
        $global_settings_data->event_detail_image_slider_duration = ( ! empty( $form_data['event_detail_image_slider_duration'] ) ? $form_data['event_detail_image_slider_duration'] : 4 );
        $global_settings_data->event_detail_message_for_recap     = ( ! empty( $_POST['event_detail_message_for_recap'] ) ) ? sanitize_text_field( $_POST['event_detail_message_for_recap'] ) : '';
        
        $global_settings->ep_save_settings( $global_settings_data );
        EventM_Admin_Notices::ep_add_notice( 'success', esc_html__( 'Setting saved successfully', 'eventprime-event-calendar-management' ) );
        $redirect_url = admin_url( "edit.php?post_type=em_event&page=ep-settings&tab=frontviews&sub_tab=eventdetails" );
        wp_redirect( $redirect_url );
        exit();
    }
}