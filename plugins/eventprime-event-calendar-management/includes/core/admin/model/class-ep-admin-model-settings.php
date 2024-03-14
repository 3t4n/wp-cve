<?php 
/**
 * Class for define settings model
 */
defined( 'ABSPATH' ) || exit;

class EventM_Admin_Model_Settings {
    
    /**
     * Setting options.
     * 
     * @var array
     */
    public $setting_options = [];

    public function __construct() {
        $this->get_custom_css_setting_options();
        $this->get_button_labels_setting_options();
        $this->get_frontend_views_settings_options();
        $this->get_pages_setting_options();
        $this->get_payment_setting_options();
        $this->get_email_setting_options();
        $this->get_general_setting_options();
        $this->get_forms_setting_options();
        $this->get_license_setting_options();
    }
    
    /**
     * Merge all front end submission related settings
     */
    public function get_fes_setting_options() {
        $fes_options = array(
            'ues_confirm_message'                => esc_html__( 'Thank you for submitting your event. We will review and publish it soon.', 'eventprime-event-calendar-management' ),
            'ues_login_message'                  => esc_html__( 'Please login to submit your event.', 'eventprime-event-calendar-management' ),
            'ues_default_status'                 => 'draft',
            'allow_submission_by_anonymous_user' => '',
            'frontend_submission_roles'          => array(),
            'ues_restricted_submission_message'  => esc_html__( 'You are not authorised to access this page. Please contact with your administrator.', 'eventprime-event-calendar-management' ),
            'frontend_submission_sections'       => array( 'fes_event_featured_image' => 1, 'fes_event_booking' => 1, 'fes_event_link' => 1, 'fes_event_type' => 1, 'fes_event_location' => 1, 'fes_event_performer' => 1, 'fes_event_organizer' => 1, 'fes_event_more_options' => 1, 'fes_event_text_color' => 1 ),
            'frontend_submission_required'       => array( 'fes_event_description' => 0, 'fes_event_booking' => 0, 'fes_booking_price' => 0,'fes_event_link' => 0, 'fes_event_type' => 0, 'fes_event_location' => 0, 'fes_event_performer' => 0, 'fes_event_organizer' => 0 ),
            'fes_allow_media_library'            => '',
            'fes_allow_user_to_delete_event'     => '',
            'fes_show_add_event_in_profile'      => '',
        );
        $this->setting_options = array_merge( $this->setting_options, $fes_options );
    }

    /**
     * Merge custom css related settings
     */
    public function get_custom_css_setting_options() {
        $custom_css_options = array(
            'custom_css' => ''
        );
        $this->setting_options = array_merge( $this->setting_options, $custom_css_options );
    }

    /**
     * Merge button labels related settings
     */
    public function get_button_labels_setting_options() {
        $button_titles_options = array(
            'button_titles' => ''
        );
        $this->setting_options = array_merge( $this->setting_options, $button_titles_options );
    }

    /**
     * Merge frontend views related settings
     */
    public function get_frontend_views_settings_options() {
        $this->get_performers_setting_options();
        $this->get_events_setting_options();
        $this->get_event_types_setting_options();
        $this->get_venues_setting_options();
        $this->get_organizers_setting_options();
        $this->get_event_details_setting_options();
    }

    /**
     * Merge performers view related options
     */
    public function get_performers_setting_options( $return_options = FALSE ) {
        $performers_options = array(
            'performer_display_view'              => 'card',
            'performer_limit'                     => 0,
            'pop_performer_limit'                 => 5,
            'performer_no_of_columns'             => 4,
            'performer_load_more'                 => 1,
            'performer_search'                    => 1,
            'single_performer_show_events'        => 1,
            'single_performer_event_display_view' => 'mini-list',
            'single_performer_event_limit'        => 0,
            'single_performer_event_column'       => 4,
            'single_performer_event_load_more'    => 1,
            'single_performer_hide_past_events'   => 0,
            'performer_box_color'                 => array('A6E7CF', 'DBEEC1', 'FFD3B6', 'FFA9A5'),
        );
        $performers_options = apply_filters('ep_performers_options',$performers_options);
        if( $return_options == TRUE ) {
            return $performers_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $performers_options );
    }
    
    /*
     * Merge event types view related options
     */
    public function get_events_setting_options($return_options = FALSE){
        $events_options = array(
            'default_cal_view'                => 'month',
            'enable_default_calendar_date'    => 0,
            'calendar_title_format'           => 'MMMM, YYYY',
            'hide_calendar_rows'              => 0,
            'hide_time_on_front_calendar'     => 0,
            'front_switch_view_option'        => array( 'month', 'week', 'day', 'listweek', 'square_grid', 'staggered_grid', 'slider', 'rows' ),
            'hide_past_events'                => 0,
            'show_no_of_events_card'          => 10,
            'card_view_custom_value'          => 1,
            'disable_filter_options'          => 0,
            'hide_old_bookings'               => 0,
            'calendar_column_header_format'   => 'dddd',
            'shortcode_hide_upcoming_events'  => 0,
            'redirect_third_party'            => 0,
            'hide_event_custom_link'          => 0,
            'show_qr_code_on_single_event'    => 1,
            'show_max_event_on_calendar_date' => 3,
            'event_booking_status_option'     => '',
            'open_detail_page_in_new_tab'     => 0,
            'events_no_of_columns'            => '',
            'events_image_visibility_options' => 'cover',
            'events_image_height'             => '',
            'show_trending_event_types'       => 0,
            'no_of_event_types_displayed'     => 5,
            'show_events_per_event_type'      => 0,
            'sort_by_events_or_bookings'      => '',
        );
        if( $return_options == TRUE ) {
            return $events_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $events_options );
    }
    /**
     * Merge event types view related options
     */
    public function get_event_types_setting_options( $return_options = FALSE ) {
        $event_types_options = array(
            'type_display_view'              => 'card',
            'type_limit'                     => 0,
            'type_no_of_columns'             => 4,
            'type_load_more'                 => 1,
            'type_search'                    => 1,
            'single_type_show_events'        => 1,
            'single_type_event_display_view' => 'mini-list',
            'single_type_event_limit'        => 0,
            'single_type_event_column'       => 4,
            'single_type_event_load_more'    => 1,
            'single_type_hide_past_events'   => 0,
            'type_box_color'                 => array('A6E7CF', 'DBEEC1', 'FFD3B6', 'FFA9A5'),
            'single_type_event_order'        => 'asc',
            'single_type_event_orderby'      => 'em_start_date_time',
        );
        if( $return_options == TRUE ) {
            return $event_types_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $event_types_options );
    }

    /**
     * Merge venues view related options
     */
    public function get_venues_setting_options( $return_options = FALSE ) {
        $venues_options = array(
            'venue_display_view'              => 'card',
            'venue_limit'                     => 0,
            'venue_no_of_columns'             => 4,
            'venue_load_more'                 => 1,
            'venue_search'                    => 1,
            'single_venue_show_events'        => 1,
            'single_venue_event_display_view' => 'mini-list',
            'single_venue_event_limit'        => 0,
            'single_venue_event_column'       => 4,
            'single_venue_event_load_more'    => 1,
            'single_venue_hide_past_events'   => 0,
            'venue_box_color'                 => array('A6E7CF', 'DBEEC1', 'FFD3B6', 'FFA9A5'),
        );
        $venues_options = apply_filters('ep_venues_options',$venues_options);
        if( $return_options == TRUE ) {
            return $venues_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $venues_options );
    }
    /**
     * Merge organizers view related options
     */
    public function get_organizers_setting_options( $return_options = FALSE ) {
        $organizers_options = array(
            'organizer_display_view'              => 'card',
            'organizer_limit'                     => 0,
            'organizer_no_of_columns'             => 4,
            'organizer_load_more'                 => 1,
            'organizer_search'                    => 1,
            'single_organizer_show_events'        => 1,
            'single_organizer_event_display_view' => 'mini-list',
            'single_organizer_event_limit'        => 0,
            'single_organizer_event_column'       => 4,
            'single_organizer_event_load_more'    => 1,
            'single_organizer_hide_past_events'   => 0,
            'organizer_box_color'                 => array('A6E7CF', 'DBEEC1', 'FFD3B6', 'FFA9A5'),
        );
        $organizers_options = apply_filters('ep_organizers_options',$organizers_options);
        if( $return_options == TRUE ) {
            return $organizers_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $organizers_options );
    }

    /**
     * Merge event detail view related options
     */
    public function get_event_details_setting_options( $return_options = FALSE ){
        $event_detail_options = array(
            'show_qr_code_on_single_event'       => 1,
            'hide_weather_tab'                   => 0,
            'weather_unit_fahrenheit'            => 0,
            'hide_map_tab'                       => 0,
            'hide_other_event_tab'               => 0,
            'hide_age_group_section'             => 0,
            'hide_note_section'                  => 0,
            'hide_performers_section'            => 0,
            'hide_organizers_section'            => 0,
            'event_detail_image_width'           => '',
            'event_detail_image_height'          => 'auto',
            'event_detail_image_height_custom'   => '',
            'event_detail_image_align'           => '',
            'event_detail_image_auto_scroll'     => 0,
            'event_detail_image_slider_duration' => 4,
            'event_detail_message_for_recap'     => 'This event has ended and results are now available.',
            'event_detail_result_heading'        => 'Results',
            'event_detail_result_button_label'   => 'View Results',
        );
        if( $return_options == TRUE ) {
            return $event_detail_options;
        }
        $this->setting_options = array_merge( $this->setting_options, $event_detail_options );
    }

    /**
     * Merge Pages related options
     */
    public function get_pages_setting_options(){
        $pages_options = array(
            'performers_page'      => '',
            'venues_page'          => '',
            'events_page'          => '',
            'booking_page'         => '',
            'profile_page'         => '',
            'event_types'          => '',
            'event_submit_form'    => '',
            'booking_details_page' => '',
            'event_organizers'     => '',
            'login_page'           => '',
            'register_page'        => '',
        );
        $pages_options = apply_filters('ep_add_pages_options',$pages_options);
        $this->setting_options = array_merge( $this->setting_options, $pages_options );
    }
    /**
     * Merge Payment related options
     */
    public function get_payment_setting_options() {
        $payment_options = array(
            'payment_order'       => array(),
            'currency'            => EP_DEFAULT_CURRENCY,
            'currency_position'   => 'before',
            'paypal_processor'    => '',
            'paypal_client_id'    => '',
            'default_payment_processor' => ''
        );
        $payment_options          = apply_filters( 'ep_add_emailer_options', $payment_options );
        $this->setting_options    = array_merge( $this->setting_options, $payment_options );
    }
    public function get_email_setting_options(){
        $admin_email = get_option('admin_email');
        $email_options = array(
            'disable_admin_email'                   => '',
            'disable_frontend_email'                => '',
            'registration_email_subject'            => esc_html__( 'User registration successful!', 'eventprime-event-calendar-management' ),
            'registration_email_content'            => '',
            'reset_password_mail_subject'           => esc_html__( 'Reset your password', 'eventprime-event-calendar-management' ),
            'reset_password_mail'                   => '',
            'send_booking_pending_email'            => 1,
            'booking_pending_email_subject'         => esc_html__( 'Your payment is pending', 'eventprime-event-calendar-management' ),
            'booking_pending_email'                 => '',
            'booking_pending_email_cc'              => '',
            'send_booking_confirm_email'            => 1,
            'booking_confirm_email_subject'         => esc_html__( 'Your booking is confirmed!', 'eventprime-event-calendar-management' ),
            'booking_confirmed_email'               => '',
            'booking_confirmed_email_cc'            => '',
            'send_booking_cancellation_email'       => 1,
            'booking_cancelation_email_subject'     => esc_html__( 'Your booking has been cancelled', 'eventprime-event-calendar-management' ),
            'booking_cancelation_email'             => '',
            'booking_cancelation_email_cc'          => '',
            'send_booking_refund_email'             => 1,
            'booking_refund_email_subject'          => esc_html__( 'Refund for your booking', 'eventprime-event-calendar-management' ),
            'booking_refund_email'                  => '',
            'booking_refund_email_cc'               => '',
            'send_event_submitted_email'            => 1,
            'event_submitted_email_subject'         => esc_html__( 'Event submitted successfully!', 'eventprime-event-calendar-management' ),
            'event_submitted_email'                 => '',
            'event_submitted_email_cc'              => '',
            'send_event_approved_email'             => 1,
            'event_approved_email_subject'          => esc_html__( 'Your event is now live!', 'eventprime-event-calendar-management' ),
            'event_approved_email'                  => '',
            'send_admin_booking_confirm_email'      => 1,
            'admin_booking_confirmed_email_subject' => esc_html__( 'New event booking', 'eventprime-event-calendar-management' ),
            'admin_booking_confirmed_email'         => '',
            'admin_booking_confirmed_email_cc'      => '',
            'admin_booking_confirm_email_attendees' => '',
            'ep_admin_email_to'                     => $admin_email,
            'ep_admin_email_from'                   => $admin_email,
        );
        $email_options = apply_filters('ep_add_emailer_options',$email_options);
        $this->setting_options = array_merge( $this->setting_options, $email_options );
    }
    
    /**
     * Genral options Setting
     */
    public function get_general_setting_options(){
        $general_options = array(
            // regular settings
            'time_format'                         => 'h:mmt',
            'default_calendar_date'               => ep_get_local_timestamp(),
            'required_booking_attendee_name'      => 0,
            'hide_0_price_from_frontend'          => 0,
            'datepicker_format'                   => 'yy-mm-dd&Y-m-d',
            'show_qr_code_on_ticket'              => 1,
            'checkout_page_timer'                 => 4,
            'enable_event_time_to_user_timezone'  => 1,
            'show_timezone_message_on_event_page' => 1,
            'timezone_related_message'            => 'All event times are displayed based on {{$timezone}} timezone.',
            'ep_frontend_font_size'               => 14,
            'hide_wishlist_icon'                  => 0,
            'enable_dark_mode'                    => 0,
            
            //SEO
            'enable_seo_urls'                     => 0,
            'seo_urls'                            => array( 'event_page_type_url' => 'event', 'performer_page_type_url' => 'performer', 'organizer_page_type_url' => 'organizer', 'venues_page_type_url' => 'venue', 'types_page_type_url' => 'event-type', 'sponsor_page_type_url' => 'sponsor' ),
            'ep_desk_normal_screen'               => '',
            'ep_desk_large_screen'                => '',

            //EXternal
            'gmap_api_key'                        => '',
            'social_sharing'                      => 0,
            'gcal_sharing'                        => 0,
            'google_cal_client_id'                => '',
            'google_cal_api_key'                  => '',
            'google_recaptcha'                    => 0,
            'google_recaptcha_site_key'           => '',
            'google_recaptcha_secret_key'         => ''
        );
        $general_options = apply_filters('ep_add_general_options',$general_options);
        $this->setting_options = array_merge( $this->setting_options, $general_options );
    }

    /**
     * Merge forms related settings
     */
    public function get_forms_setting_options() {
        $this->get_fes_setting_options();
        $this->get_login_form_setting_options();
        $this->get_register_form_setting_options();
        $this->get_checkout_register_form_setting_options();
    }

    /**
     * Merge all login form related settings
     */
    public function get_login_form_setting_options() {
        $login_options = array(
            'login_id_field'                   => 'username',
            'login_id_field_label_setting'     => 'User Name',
            'login_password_label'             => 'Password',
            'login_show_rememberme'            => '1',
            'login_show_rememberme_label'      => 'Remember me',
            'login_show_forgotpassword'        => '1',
            'login_show_forgotpassword_label'  => 'Forgot password?',
            'login_google_recaptcha'           => '',
            'login_google_recaptcha_label'     => '',
            'login_heading_text'               => '',
            'login_subheading_text'            => '',
            'login_button_label'               => 'Login',
            'login_redirect_after_login'       => ( ! empty( ep_get_global_settings( 'profile_page' ) ) ? ep_get_global_settings( 'profile_page' ) : '' ),
            'login_show_registerlink'          => 1,
            'login_show_registerlink_label'    => 'Register',
        );
        $this->setting_options = array_merge( $this->setting_options, $login_options );
    }

    /**
     * Merge all register form related settings
     */
    public function get_register_form_setting_options() {
        $register_options = array(
            'login_registration_form'           => 'ep',
            'login_rm_registration_form'        => '',
            'register_google_recaptcha'         => '',
            'register_username'                 => array( 'show' => 1, 'mandatory' => 1, 'label' => 'User Name' ),
            'register_email'                    => array( 'show' => 1, 'mandatory' => 1, 'label' => 'User Email' ),
            'register_password'                 => array( 'show' => 1, 'mandatory' => 0, 'label' => 'Password' ),
            'register_repeat_password'          => array( 'show' => 1, 'mandatory' => 0, 'label' => 'Repeat Password' ),
            'register_dob'                      => array( 'show' => 0, 'mandatory' => 0, 'label' => 'Date of Birth' ),
            'register_phone'                    => array( 'show' => 1, 'mandatory' => 0, 'label' => 'Phone' ),
            'register_timezone'                 => array( 'show' => 0, 'mandatory' => 0, 'label' => 'Timezone' ),
        );
        $this->setting_options = array_merge( $this->setting_options, $register_options );
    }
    
    /**
     * Merge all checkout register form related settings
     */
    public function get_checkout_register_form_setting_options() {
        $register_options = array(
            'checkout_register_fname'       => array( 'label'=> 'First Name' ),
            'checkout_register_lname'       => array( 'label'=> 'Last Name' ),
            'checkout_register_username'    => array( 'label'=> 'User Name' ),
            'checkout_register_email'       => array( 'label'=> 'Email' ),
            'checkout_register_password'    => array( 'label'=> 'Password' ),
            'checkout_reg_google_recaptcha' => 0,
        );
        $this->setting_options = array_merge( $this->setting_options, $register_options );
    }

    /**
     * Return settings. If option name then only get settings for that option
     */
    public function ep_get_settings( $option_name = null ) { 
        $options = get_option(EM_GLOBAL_SETTINGS);
        if( ! empty( $option_name ) ) {
            // if option name passed then call option setting method
            $setting_call = 'get_'.$option_name.'_setting_options';
            $this->setting_options = $this->$setting_call( TRUE );
        }
        $settings = (object)$this->setting_options;
        foreach ( $options as $key => $val ) {
            if ( property_exists( $settings, $key ) ) {
               $settings->{ $key } = maybe_unserialize( $val );
            }
        }
        $settings = apply_filters( 'ep_add_global_setting_options', $settings, $options );
        return $settings;
    }

    /**
     * Save global settings
     */
    public function ep_save_settings( $global_settings ) {
        if( ! current_user_can( 'manage_options' ) ) return;
        
        $options = (object)get_option( EM_GLOBAL_SETTINGS );
        foreach( $global_settings as $key => $val ){
            $options->$key = $val;
        }
        update_option( EM_GLOBAL_SETTINGS, $options );
    }

    /**
     * Merge license related settings
    */
    public function get_license_setting_options() {
        $license_options = array(
            'ep_premium_license_option_value' => '',
            'ep_free_license_item_id'    => 23935,
            'ep_free_license_item_name'  => 'EventPrime Free',
            'ep_premium_license_item_id'    => 19088,
            'ep_premium_license_item_name'  => 'EventPrime Business',
            'ep_premium_license_key'        => '',
            'ep_premium_license_status'     => '',
            'ep_premium_license_response'   => '',
            'ep_professional_license_item_id'   => 23912,
            'ep_professional_license_item_name' => 'EventPrime Professional',
            'ep_essential_license_item_id'   => 23902,
            'ep_essential_license_item_name' => 'EventPrime Essential',
            'ep_premium_plus_license_item_id'   => 21789,
            'ep_premium_plus_license_item_name' => 'EventPrime Premium+',
            'ep_metabundle_license_item_id'   => 22462,
            'ep_metabundle_license_item_name' => 'EventPrime for MetaBundle',
            'ep_metabundle_plus_license_item_id'   => 21790,
            'ep_metabundle_plus_license_item_name' => 'EventPrime for MetaBundle+',
        );
        
        $this->setting_options = array_merge( $this->setting_options, $license_options );
    }
}