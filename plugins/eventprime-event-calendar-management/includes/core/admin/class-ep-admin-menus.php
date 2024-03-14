<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for admin menus
 */
class EventM_Admin_Menus {

    public $ep_setting_tabs = array();

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_settings_scripts' ) );
        add_action( 'admin_menu', array( $this, 'menus' ) );
        add_filter( 'ep_payments_gateways_list', array($this, 'ep_payments_gateways_list'));
        // setting form submit button
        add_action( 'ep_setting_submit_button', array( $this, 'ep_setting_submit_button_callback' ) );
        // action to submit setting form
        add_action( 'admin_post_ep_setting_form', array( $this, 'ep_setting_form_submit' ) );
        add_filter( 'parent_file', array($this, 'admin_menu_separator') );
    }
    
    /**
     * Enqueue meta box scripts
     */

    public function enqueue_admin_settings_scripts( $hook ) {
        //if( $hook && ('eventprime_page_ep-settings' == $hook || 'eventprime_page_ep-bulk-emails' == $hook) ){
        if( $hook && ( strpos( $hook, 'ep-settings' ) != false || strpos( $hook, 'ep-bulk-emails' ) != false ) ){
            wp_enqueue_script(
                'ep-admin-settings-js',
                EP_BASE_URL . '/includes/core/assets/js/ep-admin-settings.js',
                array( 'jquery', 'jquery-ui-core', 'jquery-ui-tooltip', 'jquery-effects-highlight', 'jquery-ui-sortable', 'jquery-ui-datepicker' ), EVENTPRIME_VERSION
            );
            $params = array(
                'save_checkout_fields_nonce'    => wp_create_nonce( 'save-checkout-fields' ),
                'delete_checkout_fields_nonce'  => wp_create_nonce( 'delete-checkout-fields' ),
                'edit_checkout_field_title'     => esc_html__( 'Edit Field', 'eventprime-event-calendar-management' ),
                'delete_checkout_field_message' => esc_html__( 'Are you sure you want to delete this field?', 'eventprime-event-calendar-management' ),
                'edit_text'    	  		        => esc_html__( 'Edit', 'eventprime-event-calendar-management' ),
                'delete_text'    	  		    => esc_html__( 'Delete', 'eventprime-event-calendar-management' ),
                'default_payment_processor_nonce' => wp_create_nonce( 'ep-default-payment-processor' ),
                'activate_payment'    	  		=> esc_html__( 'Please activate the', 'eventprime-event-calendar-management' ),
                'payment_text'    	  		    => esc_html__( 'payment', 'eventprime-event-calendar-management' ),
            );
            wp_localize_script( 'ep-admin-settings-js', 'ep_admin_settings', $params );
            // enqueue select2
            wp_enqueue_style( 'em-admin-select2-css' );
            wp_enqueue_script( 'em-admin-select2-js' );
            wp_enqueue_style(
                'ep-admin-settings-css',
                EP_BASE_URL . '/includes/core/assets/css/ep-admin-settings.css',
                false, EVENTPRIME_VERSION
            );
            if( isset( $_GET['sub_tab'] ) && array_key_exists( sanitize_text_field( $_GET['sub_tab'] ), $this->ep_get_front_view_settings_sub_tabs() ) ) {
                wp_enqueue_script( 'em-admin-jscolor' );
            }
            wp_enqueue_style( 'ep-toast-css' );
            wp_enqueue_script( 'ep-toast-js' );
            wp_enqueue_script( 'ep-toast-message-js' );
        }
    }

    /**
     * Load admin menus
     */
    public function menus() {
        $user = wp_get_current_user();
        $em_user_caps_list = array_keys( $user->allcaps );
        $ep_user_menus_caps = 'edit_em_event';
        if( in_array( 'edit_posts', $em_user_caps_list ) ) {
            $ep_user_menus_caps = 'edit_posts';
        }

        add_menu_page( esc_html__('Events', 'eventprime-event-calendar-management'), esc_html__('Events', 'eventprime-event-calendar-management'), $ep_user_menus_caps, "edit.php?post_type=em_event", '', 'dashicons-tickets-alt', '25');
        
        remove_menu_page('edit.php?post_type=em_event');
        add_submenu_page( 'edit.php?post_type=em_event', esc_html__( 'Calendar', 'eventprime-event-calendar-management' ), esc_html__( 'Calendar', 'eventprime-event-calendar-management' ), $ep_user_menus_caps, 'ep-event-calendar', array( $this, 'ep_event_calendar' ), 2 );
        
        $report_admin = EventM_Factory_Service::ep_get_instance( 'EventM_Report_Admin' );
        
        add_submenu_page( "edit.php?post_type=em_event", esc_html__( 'Reports', 'eventprime-event-calendar-management'), esc_html__('Reports', 'eventprime-event-calendar-management'), $ep_user_menus_caps, "ep-events-reports", array( $report_admin, 'eventprime_reports' ), class_exists( 'EM_Sponsor' ) ? 10 : 9 );
        add_submenu_page( 'edit.php?post_type=em_event', esc_html__( 'Email', 'eventprime-event-calendar-management' ), esc_html__( 'Email', 'eventprime-event-calendar-management' ), $ep_user_menus_caps, 'ep-bulk-emails', array( $this, 'bulk_emails_page' ), 13 );
        if( get_option( 'ep_db_need_to_run_migration' ) == 1 ) {
            add_submenu_page( "edit.php?post_type=em_event", esc_html__( 'Migration', 'eventprime-event-calendar-management'), esc_html__('Migration', 'eventprime-event-calendar-management'), $ep_user_menus_caps, "ep-revamp-migration", array( $this, 'eventprime_revamp_migration' ) );
        }
        add_submenu_page( "edit.php?post_type=em_event", esc_html__( 'Shortcodes', 'eventprime-event-calendar-management'), esc_html__('Shortcodes', 'eventprime-event-calendar-management'), $ep_user_menus_caps, "ep-publish-shortcodes", array( $this, 'eventprime_publish_shortcodes' ) );
        
        do_action( 'ep_admin_menus' );

        add_submenu_page( 'edit.php?post_type=em_event', esc_html__( 'EventPrime settings', 'eventprime-event-calendar-management' ), esc_html__( 'Settings', 'eventprime-event-calendar-management' ), 'manage_options', 'ep-settings', array( $this, 'settings_page' ) );

        add_submenu_page( "edit.php?post_type=em_event", esc_html__( 'Extensions', 'eventprime-event-calendar-management'), esc_html__('Extensions', 'eventprime-event-calendar-management'), $ep_user_menus_caps, "ep-extensions", array( $this, 'eventprime_extensions' ) );
        // attendees list page
        add_submenu_page( "", __( 'Attendees List', 'eventprime-event-calendar-management'), __('Attendees List', 'eventprime-event-calendar-management'), $ep_user_menus_caps, 'ep-event-attendees-list', array( $this, 'ep_show_event_attendees_list' ) );
    }

    public function admin_menu_separator( $parent_file ) {
        $menu = &$GLOBALS['menu'];
        $submenu = &$GLOBALS['submenu'];
        //epd($submenu);
        $available_sub_menus = array();
        foreach( $submenu as $key => $item ) {
            foreach ( $item as $index => $data ){   
                $available_sub_menus[] = $data[2];
                if( strpos( $data[2], "em_event_type2" ) !== false ){
                    $data[4] = 'ep-show-divider';
                    $submenu[ $key ][ $index ] = $data;
                } elseif( strpos( $data[2], "em_performer" ) !== false ){
                    // $data[4] = 'ep-show-divider';
                    $submenu[ $key ][ $index ] = $data;
                }
            }
            foreach ( $item as $index => $data ) {   
                if( in_array( 'ep-settings', $available_sub_menus ) ) {
                    if( strpos($data[2], "ep-settings" ) !== false ) {
                        // $data[4] = 'ep-show-divider';
                        $submenu[$key][$index] = $data;
                    }
                }
            }
        }
        return $parent_file;
    }

    /**
     * EventPrime Global Settings
     */
    public function settings_page() {
        $extension_setting = 0;
        if(isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->ep_get_settings_tabs()['extension'] )){
            $extension_setting = 1;
            $active_tab = $_GET['tab'];
        }else{
            $active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->ep_get_settings_tabs()['core'] ) ? $_GET['tab'] : 'general';
        }?>
        <div class="wrap ep-admin-setting-tabs">
            <form method="post" id="ep_setting_form" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
                <h2 class="nav-tab-wrapper">
                    <?php
                    $tab_url = remove_query_arg( array( 'section', 'sub_tab' ) );
                    
                    foreach ( $this->ep_get_settings_tabs()['core'] as $tab_id => $tab_name ) {
                        $tab_url = add_query_arg( 
                            array( 'tab' => $tab_id),
                            $tab_url
                        );
                        $active = $active_tab == $tab_id ? ' nav-tab-active' : '';
                        echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
                            echo esc_html( $tab_name );
                        echo '</a>';
                    }
                    if($extension_setting){
                        $tab_url = add_query_arg( 
                            array( 'tab' => $active_tab),
                            $tab_url
                        );
                        $active =' nav-tab-active';
                        echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $this->ep_get_settings_tabs()['extension'][$active_tab] ) . '" class="nav-tab' . $active . '">';
                            echo esc_html( $this->ep_get_settings_tabs()['extension'][$active_tab] );
                        echo '</a>';
                    }?>
                </h2>
                <?php $this->ep_get_settings_tabs_content( $active_tab );
                
                do_action( 'ep_setting_submit_button' );?>
            </form>
        </div><?php
        do_action( 'ep_add_custom_banner' );
    }

    /**
     * EventPrime setting tabs
     */
    public function ep_get_settings_tabs() {
        $tabs = array();
        $tabs['general']        = esc_html__( 'General', 'eventprime-event-calendar-management' );
        $tabs['payments']       = esc_html__( 'Payments', 'eventprime-event-calendar-management' );
        $tabs['pages']          = esc_html__( 'Pages', 'eventprime-event-calendar-management' );
        $tabs['emails']         = esc_html__( 'Emails', 'eventprime-event-calendar-management' );
        $tabs['checkoutfields'] = esc_html__( 'Checkout Fields', 'eventprime-event-calendar-management' );
        $tabs['customcss']      = esc_html__( 'Custom CSS', 'eventprime-event-calendar-management' );
        $tabs['buttonlabels']   = esc_html__( 'Language', 'eventprime-event-calendar-management' );
        $tabs['frontviews']     = esc_html__( 'Frontend Views', 'eventprime-event-calendar-management' );
        $tabs['forms']          = esc_html__( 'Forms', 'eventprime-event-calendar-management' );
        $tabs['license']        = esc_html__( 'Licenses', 'eventprime-event-calendar-management' );
        $tabs['extensions']     = esc_html__( 'Extensions', 'eventprime-event-calendar-management' );
        
        $this->ep_setting_tabs = array_keys( $tabs );
        $tabs_list['core'] = $tabs;
        $tabs_list['extension'] = apply_filters( 'ep_admin_settings_tabs', array() );
        
        return $tabs_list;
    }

    /**
     * Return setting tabs content
     */
    public function ep_get_settings_tabs_content( $active_tab ) {
        global $wpdb, $wp_roles;
        $options = array();
	    $global_options                  = get_option( EM_GLOBAL_SETTINGS );
        $settings                        = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options['global']               = $settings->ep_get_settings();
        $options['payments']             = $this->ep_payment_gateways_order();
        $options['payments_settings']    = $this->ep_payments_gateways_setting();
        $options['emailers']             = $this->ep_emailer_list();
        $options['emailers_settings']    = $this->ep_emailer_setting();
        $options['checkout_field_types'] = $this->ep_checkout_field_types();
        $options['checkout_fields_data'] = $this->ep_get_checkout_fields_data();
        $options['pages']                = ep_get_all_pages_list();
        $options['buttonsections']       = $this->get_button_section_lists();
        $options['labelsections']        = $this->get_label_section_lists();
        $options['buttons_help_text']    = $this->get_label_section_help_text_lists();
        $options['form_list']            = $this->ep_settings_forms_list();
        $options['extensions']           = $this->ep_setting_extensions_list();

        ob_start();
        if( in_array( $active_tab, $this->ep_setting_tabs ) ){
            include __DIR__ .'/settings/settings-tab-'. $active_tab .'.php';
        }else{
            do_action( 'ep_get_extended_settings_tabs_content', $active_tab );
        }
        $tab_content = ob_get_clean();

        apply_filters( 'ep_get_settings_tab_content', $tab_content );
        
        echo $tab_content;
    }
    
    public function ep_payment_gateways_order(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_options = $global_settings->ep_get_settings();
        $gateways = $this->ep_payments_gateways_list();
        $ordered_gateways = array();
        if( isset( $global_options->payment_order ) && ! empty( $global_options->payment_order ) ) {
            foreach( $global_options->payment_order as $payment_order ) {
                if( isset( $gateways[$payment_order] ) ) {
                    $ordered_gateways[$payment_order] = $gateways[$payment_order];
                }
            }
            foreach ( $gateways as $key => $method ) {
                if( ! isset( $ordered_gateways[$key] ) ) {
                    $ordered_gateways[$key] = $method;
                }
            }
            return $ordered_gateways;
        }
        else{
            return $gateways;
        }
    }
    
    //Register payment gateways
    public function ep_payments_gateways_list(){
        $gateways = array();
        $gateways['none'] = array(
            'method'       => esc_html__( 'None', 'eventprime-event-calendar-management' ),
            'description'  => '',
            'icon_url'     => '',
            'enable_key'   => '',
            'show_in_list' => 0
        );
        $gateways['paypal'] = array(
            'method'       => esc_html__( 'Paypal', 'eventprime-event-calendar-management' ),
            'description'  => esc_html__( 'Accept payments using PayPal checkout.', 'eventprime-event-calendar-management' ),
            'icon_url'     => esc_url( EP_BASE_URL.'/includes/assets/images/payment-paypal.png' ),
            'enable_key'   => 'paypal_processor',
            'show_in_list' => 1
        );
        return apply_filters('ep_payments_gateways_list_add',$gateways);
    }
    
    public function ep_payments_gateways_setting(){
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $global_options = $global_settings->ep_get_settings();
        $gateway_settings = array();
        ob_start();
        include __DIR__ .'/settings/payments/payment-paypal.php';
        $gateway_settings_content = ob_get_clean();
        $gateway_settings['paypal'] = $gateway_settings_content;
        return apply_filters('ep_payments_gateways_setting_add', $gateway_settings);
    }
    
    public function ep_emailer_list(){
        $emailers = array();
        $emailers['registration'] = array(
            'title'=>__('Registration Email','eventprime-event-calendar-management'),
            'description'=>__('Sends confirmation to the users upon successful registration through EventPrime registration form.','eventprime-event-calendar-management'),
            'enable_key'=> '',
            'recipient'=>__('User','eventprime-event-calendar-management')
            );
        $emailers['reset_password'] = array(
            'title'=>__('Reset User Password','eventprime-event-calendar-management'),
            'description'=>__('Sends new password to the users on password reset request.','eventprime-event-calendar-management'),
            'enable_key'=> '',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['booking_pending'] = array(
            'title'=>__('Booking Pending Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the users when their booking is in pending state.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_booking_pending_email',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['booking_confirm'] = array(
            'title'=>__('Booking Confirmation Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the users when their booking is confirmed.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_booking_confirm_email',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['booking_canceled'] = array(
            'title'=>__('Booking Cancellation Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the users when their booking is cancelled.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_booking_cancellation_email',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['booking_refund'] = array(
            'title'=>__('Booking Refund Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the users when their booking is refunded.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_booking_refund_email',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['event_submitted'] = array(
            'title'=>__('Event Submitted Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the admin on successfully submitting an event from the frontend form on this website.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_event_submitted_email',
            'recipient'=>__('Admin','eventprime-event-calendar-management')
        );
        $emailers['event_approval'] = array(
            'title'=>__('Event Approval Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the users when their submitted event has been approved by the admin.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_event_approved_email',
            'recipient'=>__('User','eventprime-event-calendar-management')
        );
        $emailers['booking_confirmed_admin'] = array(
            'title'=>__('Admin Booking Confirmation Email','eventprime-event-calendar-management'),
            'description'=>__('Informs the admin when a new booking is created by a user, for any event.','eventprime-event-calendar-management'),
            'enable_key'=> 'send_admin_booking_confirm_email',
            'recipient'=>__('Admin','eventprime-event-calendar-management')
        );
        return apply_filters('ep_emailer_list_add', $emailers);
    }
    public function ep_emailer_setting(){
        if( isset( $_GET['section'] ) && isset( $_GET['tab'] ) && 'emails' == sanitize_text_field( $_GET['tab'] ) ){
            $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
            $global_options = $global_settings->ep_get_settings();
            $emailer_settings = array();
            $section = sanitize_text_field( $_GET['section'] );
            if( array_key_exists( $section, $this->ep_emailer_list() ) ) {
                $email_file_path = __DIR__ .'/settings/emailers/emailer-'.$section.'.php';
                if( file_exists( $email_file_path ) ) {
                    ob_start();
                    include __DIR__ .'/settings/emailers/emailer-'.$section.'.php';
                    $emailer_settings_content = ob_get_clean();
                    $emailer_settings[$section] = $emailer_settings_content;
                }
            }
            return apply_filters( 'ep_emailer_setting_add', $emailer_settings, $section );
        }
    }

    /**
     * Get checkout field types
     */
    public function ep_checkout_field_types() {
        $field_types = ep_get_core_checkout_fields();
        return apply_filters( 'ep_checkout_fields_options', $field_types );
    }

    /**
     * Get checkout fields data
     */
    public function ep_get_checkout_fields_data() {
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Controller_Settings' );
        $get_field_data = $settings->ep_get_checkout_fields_data();
        return $get_field_data;
    }

    
    public function ep_setting_submit_button_callback(){ 
        $tabs_list = array('forms','extensions','checkoutfields','license');
        $tabs = isset($_GET['tab']) && !empty($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
        $section = isset($_GET['section']) && !empty($_GET['section']) ? sanitize_text_field($_GET['section']) : '';
        if( empty( $section ) ) {
            if( ! in_array( $tabs, $tabs_list ) ) {?>
                <p class="submit">
                    <input type="hidden" name="action" value="ep_setting_form">
                    <button name="save" class="button-primary ep-save-button" type="submit" value="<?php esc_attr_e( 'Save Changes', 'eventprime-event-calendar-management' ); ?>">
                        <?php esc_html_e( 'Save Changes', 'eventprime-event-calendar-management' ); ?>
                    </button>
                    <?php wp_nonce_field( 'ep_save_global_settings', 'ep_global_settings_nonce' ); ?>
                </p><?php
            }
        }else{?>
            <p class="submit">
                <input type="hidden" name="action" value="ep_setting_form">
                <button name="save" class="button-primary ep-save-button" type="submit" value="<?php esc_attr_e( 'Save Changes', 'eventprime-event-calendar-management' ); ?>">
                    <?php esc_html_e( 'Save Changes', 'eventprime-event-calendar-management' ); ?>
                </button>
                <?php wp_nonce_field( 'ep_save_global_settings', 'ep_global_settings_nonce' ); ?>
            </p><?php
        }
    }

    /**
     * Setting form submission handler
     */
    public function ep_setting_form_submit() {
        // Check the nonce.
		if ( empty( $_POST['ep_global_settings_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ep_global_settings_nonce'] ), 'ep_save_global_settings' ) ) {
			return;
		}

        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Controller_Settings' );
        $settings->save_settings();
    }

    /**
     * Frontend event submission list
     */
    public function get_fes_section_lists() {
        $fesSections = array(
            //'fes_event_text_color'     => esc_html__( 'Event Text Color','eventprime-event-calendar-management' ),
            'fes_event_featured_image' => esc_html__( 'Event Featured Image','eventprime-event-calendar-management' ),
            'fes_event_booking'        => esc_html__( 'Event Booking','eventprime-event-calendar-management' ),
            'fes_event_link'           => esc_html__( 'Event Link','eventprime-event-calendar-management' ),
            'fes_event_type'           => esc_html__( 'Event-Type','eventprime-event-calendar-management' ),
            'fes_new_event_type'       => esc_html__( 'Add New Event-Type','eventprime-event-calendar-management' ),
            'fes_event_location'       => esc_html__( 'Venues','eventprime-event-calendar-management' ),
            'fes_new_event_location'   => esc_html__( 'Add New Venues','eventprime-event-calendar-management' ),
            'fes_event_performer'      => esc_html__( 'Event Performer','eventprime-event-calendar-management' ),
            'fes_new_event_performer'  => esc_html__( 'Add New Event Performer','eventprime-event-calendar-management' ),
            'fes_event_organizer'      => esc_html__( 'Event Organizer','eventprime-event-calendar-management' ),
            'fes_new_event_organizer'  => esc_html__( 'Add New Event Organizer','eventprime-event-calendar-management' ),
            //'fes_event_more_options'   => esc_html__( 'Event More Options','eventprime-event-calendar-management ')
        );
        return $fesSections;
    }

    /**
     * Frontend event submission required list
     */
    public function get_fes_required_lists() {
        $fesRequired = array(
            'fes_event_description' => esc_html__( 'Event Description','eventprime-event-calendar-management' ),
            //'fes_event_booking'     => esc_html__( 'Event Booking','eventprime-event-calendar-management' ),
            //'fes_booking_price'     => esc_html__( 'Event Booking Price','eventprime-event-calendar-management' ),
            //'fes_event_link'        => esc_html__( 'Event Link','eventprime-event-calendar-management' ),
            'fes_event_type'        => esc_html__( 'Event-Type','eventprime-event-calendar-management' ),
            'fes_event_location'    => esc_html__( 'Venues','eventprime-event-calendar-management' ),
            'fes_event_performer'   => esc_html__( 'Event Performer','eventprime-event-calendar-management' ),
            'fes_event_organizer'   => esc_html__( 'Event Organizer','eventprime-event-calendar-management ')
        );
        return $fesRequired;
    }

    /**
     * Button sections
     */
    public function get_button_section_lists() {
        $buttonsections = array( 'Buy Tickets', 'Booking closed', 'Booking start on', 'Free', 'View Details', 'Get Tickets Now', 'Checkout', 'Register', 'Add Details & Checkout', 'Submit Payment', 'Sold Out' );
        return apply_filters( 'ep_settings_language_buttons', $buttonsections );
    }

    /**
     * Label sections
     */
    public function get_label_section_lists() {
        $labelsections = array( 'Event-Type', 'Event-Types', 'Venue', 'Venues', 'Performer', 'Performers', 'Organizer', 'Organizers', 'Add To Wishlist', 'Remove From Wishlist', 'Ticket', 'Tickets Left', 'Organized by' );
        return apply_filters( 'ep_settings_language_labels', $labelsections );
    }

    /**
     * Label help text
     */
    public function get_label_section_help_text_lists() {
        $label_help_text['Event-Type']           = 'Label representing singular word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Event-Type\' will be used across EventPrime.';
        $label_help_text['Event-Types']          = 'Label representing plural word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Event-Types\' will be used across EventPrime.';
        $label_help_text['Venue']                = 'Label representing singular word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Venue\' will be used across EventPrime.';
        $label_help_text['Venues']               = 'Label representing plural word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Venues\' will be used across EventPrime.';
        $label_help_text['Performer']            = 'Label representing singular word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Performer\' will be used across EventPrime.';
        $label_help_text['Performers']           = 'Label representing plural word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Performers\' will be used across EventPrime.';
        $label_help_text['Organizer']            = 'Label representing singular word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Organizer\' will be used across EventPrime.';
        $label_help_text['Organizers']           = 'Label representing plural word for event participants based on your industry. For example: Speaker, Actor, Player etc. If you choose to leave it blank, the word \'Organizers\' will be used across EventPrime.';
        $label_help_text['Add To Wishlist']      = 'Appears when hovering above the wishlist icon.';
        $label_help_text['Remove From Wishlist'] = 'Appears in the Wishlist section of the user area.';
        $label_help_text['Ticket']               = 'Appears as heading while adding attendees during the first step of checkout.';
        $label_help_text['Tickets Left']         = 'Appears on event listings and inside the tickets selection pop-up.';
        return $label_help_text;
    }

    /**
     * EventPrime frontend views sub tabs
     */
    public function ep_get_front_view_settings_sub_tabs() {
        $sub_tabs = array();
        $sub_tabs['events']       = esc_html__( 'Event Listings', 'eventprime-event-calendar-management' );
        $sub_tabs['eventdetails'] = esc_html__( 'Event', 'eventprime-event-calendar-management' );
        $sub_tabs['eventtypes']   = esc_html__( 'Event-Types', 'eventprime-event-calendar-management' );
        $sub_tabs['performers']   = esc_html__( 'Performers', 'eventprime-event-calendar-management' );
        $sub_tabs['venues']       = esc_html__( 'Venues', 'eventprime-event-calendar-management' );
        $sub_tabs['organizers']   = esc_html__( 'Organizers', 'eventprime-event-calendar-management' );
        
        return apply_filters( 'ep_admin_front_view_settings_sub_tabs', $sub_tabs );
    }

    /**
     * Frontend views content
     */
    public function ep_get_settings_front_views_content( $active_sub_tab ) {
        $sub_options = $options = array();
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options['global'] = $settings->ep_get_settings();
        $core_tabs = array('events','eventdetails','eventtypes','performers','venues','organizers');
        $sub_options['front_view_list_styles'] = $this->ep_frontend_views_list_styles();
        $sub_options['front_view_event_styles'] = $this->ep_frontend_views_event_styles();
        $sub_options['default_cal_view'] = EventM_Factory_Service::get_event_views();
        $sub_options['events_per_page'] = array( 10, 20, 30, 50, 'all', 'custom' );
        $sub_options['time_format'] = array( 'h:mmt' => '12-hour', 'HH:mm' => '24-hour' );
        $sub_options['calendar_title_format'] = array(
            'DD MMMM, YYYY' => date("d F, Y"),
            'MMMM DD, YYYY' => date("F d, Y"),
            'DD-MMMM-YYYY'  => date("d-F-Y"),
            'MMMM-DD-YYYY'  => date('F-d-Y'),
            'DD/MMMM/YYYY'  => date('d/F/Y'),
            'MMMM/DD/YYYY'  => date("F/d/Y"),
            'MMMM YYYY'     => date("F Y"),
            'MMMM, YYYY'    => date("F, Y"),
        );

        $sub_options['calendar_header_format'] = array(
            'ddd'     => date("D"),
            'dddd'    => date("l"),
            /* 'ddd D/M' => date("D j/m"),
            'ddd M/D' => date("D m/j"), */
        );
        $sub_options['datepicker_format'] = array(
            'dd-mm-yy&d-m-Y' => date('d-m-Y') .' (d-m-Y)',
            'mm-dd-yy&m-d-Y' => date('m-d-Y') .' (m-d-Y)',
            'yy-mm-dd&Y-m-d' => date('Y-m-d') .' (Y-m-d)',
            'dd/mm/yy&d/m/Y' => date('d/m/Y') .' (d/m/Y)',
            'mm/dd/yy&m/d/Y' => date('m/d/Y') .' (m/d/Y)',
            'yy/mm/dd&Y/m/d' => date('Y/m/d') .' (Y/m/d)',
            'dd.mm.yy&d.m.Y' => date('d.m.Y') .' (d.m.Y)',
            'mm.dd.yy&m.d.Y' => date('m.d.Y') .' (m.d.Y)',
            'yy.mm.dd&Y.m.d' => date('Y.m.d') .' (Y.m.d)',
        );
        
        $sub_options['image_visibility_options'] = EventM_Factory_Service::get_image_visibility_options();

        ob_start();
        if(in_array($active_sub_tab, $core_tabs)){
            include __DIR__ .'/settings/settings-tab-'. $active_sub_tab .'.php';
        }else{
            do_action( 'ep_get_settings_sub_tab_content', $active_sub_tab, $sub_options );
        }
        $sub_tab_content = ob_get_clean();
        
        echo $sub_tab_content;
    }

    /**
     * Frontend views listing page styles
     */
    public function ep_frontend_views_list_styles(){
        $listing_page_view_options = array( "grid" => "Square Grid", "colored_grid" => "Colored Square Grid", "rows" => "Stacked Rows" );
        return $listing_page_view_options;
    }
    
    /**
     * Frontend views event styles
     */
    public function ep_frontend_views_event_styles(){
        $upcoming_event_view_options = array( "grid" => "Square Grid", "rows" => "Stacked Rows", "plain_list" => "Plain List" );
        return $upcoming_event_view_options;
    }

    /**
     * EventPrime general settings sub tabs
     */
    public function ep_get_general_settings_sub_tabs() {
        $sub_tabs = array();
        $sub_tabs['regular']  = esc_html__( 'Setup', 'eventprime-event-calendar-management' );
        $sub_tabs['timezone'] = esc_html__( 'Timezone', 'eventprime-event-calendar-management' );
        $sub_tabs['external'] = esc_html__( 'Third-Party', 'eventprime-event-calendar-management' );
        $sub_tabs['seo']      = esc_html__( 'SEO', 'eventprime-event-calendar-management' );
        
        return apply_filters( 'ep_admin_general_settings_sub_tabs', $sub_tabs );
    }

    /**
     * General settings tabs content
     */
    public function ep_get_settings_general_tabs_content( $active_sub_tab ) {
        $sub_options = $options = array();
        $settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options['global'] = $settings->ep_get_settings();

        $sub_options['default_cal_view'] = EventM_Factory_Service::get_event_views();
        $sub_options['events_per_page'] = array( 10, 20, 30, 50, 'all', 'custom' );
        $sub_options['time_format'] = array( 'h:mmt' => '12-hour', 'HH:mm' => '24-hour' );
        $sub_options['calendar_title_format'] = array(
            'DD MMMM, YYYY' => date("d F, Y"),
            'MMMM DD, YYYY' => date("F d, Y"),
            'DD-MMMM-YYYY'  => date("d-F-Y"),
            'MMMM-DD-YYYY'  => date('F-d-Y'),
            'DD/MMMM/YYYY'  => date('d/F/Y'),
            'MMMM/DD/YYYY'  => date("F/d/Y"),
        );

        $sub_options['calendar_header_format'] = array(
            'ddd'     => date("D"),
            'dddd'    => date("l"),
            'ddd D/M' => date("D j/m"),
            'ddd M/D' => date("D m/j"),
        );
        $sub_options['datepicker_format'] = array(
            'dd-mm-yy&d-m-Y' => date('d-m-Y') .' (d-m-Y)',
            'mm-dd-yy&m-d-Y' => date('m-d-Y') .' (m-d-Y)',
            'yy-mm-dd&Y-m-d' => date('Y-m-d') .' (Y-m-d)',
            'dd/mm/yy&d/m/Y' => date('d/m/Y') .' (d/m/Y)',
            'mm/dd/yy&m/d/Y' => date('m/d/Y') .' (m/d/Y)',
            'yy/mm/dd&Y/m/d' => date('Y/m/d') .' (Y/m/d)',
            'dd.mm.yy&d.m.Y' => date('d.m.Y') .' (d.m.Y)',
            'mm.dd.yy&m.d.Y' => date('m.d.Y') .' (m.d.Y)',
            'yy.mm.dd&Y.m.d' => date('Y.m.d') .' (Y.m.d)',
        );

        $sub_options['timezone_related_message'] = $options['global']->timezone_related_message;
        if( empty( $options['global']->timezone_related_message ) ) {
            $sub_options['timezone_related_message'] = esc_html__( 'All the event times coming as per {{$timezone}} timezone.', 'eventprime-event-calendar-management' );
        }
        
        ob_start();
        include __DIR__ .'/settings/settings-tab-'. $active_sub_tab .'.php';
        $sub_tab_content = ob_get_clean();

        apply_filters( 'ep_get_settings_tab_content', $sub_tab_content, $options );
        
        echo $sub_tab_content;
    }

    /**
     * List of frontend forms
     */
    public function ep_settings_forms_list(){
        $forms = array();
        $forms['fes'] = array(
            'title'       => esc_html__( 'Frontend Event Submission', 'eventprime-event-calendar-management' ),
            'description' => esc_html__( 'Form used by your users to submit events on your website.','eventprime-event-calendar-management' ),
        );

        $forms['login'] = array(
            'title'       => esc_html__( 'Login Form', 'eventprime-event-calendar-management' ),
            'description' => esc_html__( 'EventPrime\'s in-built login form.','eventprime-event-calendar-management' ),
        );

        $forms['register'] = array(
            'title'       => esc_html__( 'Registration Form', 'eventprime-event-calendar-management' ),
            'description' => esc_html__( 'EventPrime\'s in-build registration form.','eventprime-event-calendar-management' ),
        );
        
        $forms['checkout_registration'] = array(
            'title'       => esc_html__( 'Checkout Registration Form', 'eventprime-event-calendar-management' ),
            'description' => esc_html__( 'A form that appears during checkout for guest users allowing them to register while booking their first event.','eventprime-event-calendar-management' ),
        );
        
        return apply_filters( 'ep_settings_forms_list_add', $forms );
    }

    /**
     * Load form settings html
     * 
     * @param string $section Section name.
     */
    public function get_form_settings_html( $section ) {
        $options = array();
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options['global'] = $global_settings->ep_get_settings();
        
        $options['fes_sections'] = $this->get_fes_section_lists();
        $options['fes_required'] = $this->get_fes_required_lists();
        if( empty( $options['global']->em_ues_confirm_message ) ) {
            $options['global']->em_ues_confirm_message = __( 'Thank you for submitting your event. We will review and publish it soon.', 'eventprime-event-calendar-management' );
        }
        if( empty( $options['global']->em_ues_login_message ) ) {
            $options['global']->em_ues_login_message = __( 'Please login to submit your event.', 'eventprime-event-calendar-management' );
        }
        if( empty( $options['global']->em_ues_restricted_submission_message ) ) {
            $options['global']->em_ues_restricted_submission_message = __( 'You are not authorised to access this page. Please contact with your administrator.', 'eventprime-event-calendar-management' );
        }
        $options['status_list'] = array(
            "publish" => __( 'Active','eventprime-event-calendar-management' ),
            "draft"   => __( 'Draft','eventprime-event-calendar-management' )
        );
        $registration_forms_list = array(
            'ep' => 'EventPrime',
            'rm' => 'RegistrationMagic',
            'wp' => 'WordPress Core',
        );
        $options['registration_forms_list'] = apply_filters( 'ep_settings_registration_forms_list', $registration_forms_list );
        $options['rm_forms'] = EventM_Factory_Service::ep_get_rm_forms();
        // define core forms
        $default_core_forms = array( 'fes', 'login', 'register', 'checkout_registration' );
        //$options['global']->frontend_submission_roles = $options['global']->frontend_submission_sections = $options['global']->frontend_submission_required = array();
        $manage_form_data = '';
        ob_start();
        // if section is in the core form then include the file else call hook
        if( in_array( $section, $default_core_forms ) ) {
            include __DIR__ .'/settings/forms/form-'.$section.'.php';
        } else{
            do_action( 'ep_get_extended_form_settings_content', $section, $options );
        }
        $manage_form_data = ob_get_clean();
        echo $manage_form_data;
    }

    public function eventprime_extensions() {
        include __DIR__ .'/template/extensions.php';
    }
    
    public function ep_setting_extensions_list(){
        $extension_settings = array();
        return apply_filters( 'ep_extensions_settings', $extension_settings );
    }
    
    public function bulk_emails_page(){
        $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
        //$events = $event_controller->get_events_post_data(array());
        $events = $event_controller->get_events_field_data(array( 'id', 'name' ) );
        include __DIR__ .'/settings/settings-tab-bulk-emails.php';
    }

    // Migration page
    public function eventprime_revamp_migration() {
        if( get_option( 'ep_db_need_to_run_migration' ) == 1 ) {
            wp_enqueue_script(
                'ep-admin-migration-page-js',
                EP_BASE_URL . '/includes/core/assets/js/ep-admin-migration.js',
                array( 'jquery' ), EVENTPRIME_VERSION
            );

            $params = array(
                'run_migration_nonce' => wp_create_nonce( 'ep-migration-nonce' ),
                'event_page_url'      => admin_url( 'edit.php?post_type=em_event' ),
                'plugin_page_url'     => admin_url( 'plugins.php' ),
                'dashboard_url'       => admin_url(),
            );

            wp_localize_script( 'ep-admin-migration-page-js', 'ep_admin_migration_settings', $params );

            include __DIR__ .'/template/migration.php';
        } else{
            wp_safe_redirect( admin_url( 'edit.php?post_type=em_event' ) );
        }
    }

    // publish shortcodes
    public function eventprime_publish_shortcodes() {
        include __DIR__ .'/template/publish-shortcodes.php';
    }
    
    public function ep_event_calendar(){
        $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'em-admin-jquery-timepicker' );
	    wp_enqueue_script( 'em-admin-timepicker-js' );
        //wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_style(
            'em-admin-calendar-jquery-ui',
            EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
            false, EVENTPRIME_VERSION
        );

        // load calendar library
        wp_enqueue_style(
            'ep-admin-calendar-event-calendar-css',
            EP_BASE_URL . '/includes/assets/css/ep-calendar.min.css',
            false, EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-admin-calendar-event-moment-js',
            EP_BASE_URL . '/includes/assets/js/moment.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-front-event-calendar-js',
            EP_BASE_URL . '/includes/assets/js/ep-calendar.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-admin-calendar-event-fulcalendar-moment-js',
            EP_BASE_URL . '/includes/assets/js/fullcalendar-moment.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        wp_enqueue_script(
            'ep-admin-calendar-event-fulcalendar-local-js',
            EP_BASE_URL . '/includes/assets/js/locales-all.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-admin-calendar-event-toast-main-js',
            EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-admin-calendar-event-toast-js',
            EP_BASE_URL . '/includes/assets/js/toast-message.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        wp_enqueue_style(
            'ep-admin-calendar-toast-css',
            EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_style(
            'ep-admin-calendar-events-css',
            EP_BASE_URL . '/includes/events/assets/css/ep-admin-calendar-events.css',
            false, EVENTPRIME_VERSION
        );
        wp_enqueue_script(
            'ep-admin-calendar-events-js',
            EP_BASE_URL . '/includes/events/assets/js/ep-admin-calendar-events.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );
        // get calendar events
        $events_posts = $event_controller->get_multiple_events_post_data( array( 'orderby' => 'meta_value_num', 'post_status' => array( 'publish', 'draft' ) ) );
        $events_data['events'] = $events_posts;
        $events_data['event_atts'] = array();
        $cal_events = array();
        if( ! empty( $events_data['events']->posts ) ) {
            $cal_events = $event_controller->get_admin_calendar_view_event( $events_data['events']->posts );
        }

        wp_localize_script(
            'ep-admin-calendar-events-js', 
            'em_admin_calendar_event_object', 
            array(
                '_nonce'               => wp_create_nonce('ep-frontend-nonce'),
                'ajaxurl'              => admin_url( 'admin-ajax.php', null ),
                'filters_applied_text' => esc_html__( 'Filters Applied', 'eventprime-event-calendar-management' ),
                'nonce_error'          => esc_html__( 'Please refresh the page and try again.', 'eventprime-event-calendar-management' ),
                'event_attributes'     => $events_data['event_atts'],
                'start_of_week'        => get_option( 'start_of_week' ),
                'cal_events'           => $cal_events,
                'local'                => ep_get_calendar_locale(),
                'errors'               => array(
                    'title'       => esc_html__( 'Event Title is required.', 'eventprime-event-calendar-management' ),
                    'start_date'  => esc_html__( 'Event Title is required.', 'eventprime-event-calendar-management' ),
                    'end_date'    => esc_html__( 'Event Title is required.', 'eventprime-event-calendar-management' ),
                    'date_error'  => esc_html__( 'Event start date should not greater than end date.', 'eventprime-event-calendar-management' ),
                    'event_price' => esc_html__( 'Event price is required.', 'eventprime-event-calendar-management' ),
                    'quantity'    => esc_html__( 'Event quantity is required.', 'eventprime-event-calendar-management' ),
                    'popup_new'   => esc_html__( 'Add New Event', 'eventprime-event-calendar-management' ),
                    'popup_edit'  => esc_html__( 'Edit Event', 'eventprime-event-calendar-management' ),
                ),
                'image_title'          => esc_html__( 'Insert logo', 'eventprime-event-calendar-management' ),
                'image_text'           => esc_html__( 'Use this image', 'eventprime-event-calendar-management' ),
                'add_event_message'    => esc_html__( 'Click on a date to add a new event.', 'eventprime-event-calendar-management' ),
                'frontend_label'       => esc_html__( 'Frontend', 'eventprime-event-calendar-management' ),
                'frontend_event_page'  => get_permalink( ep_get_global_settings( 'events_page' ) ),
                'list_week_btn_text'   => esc_html__( 'Agenda', 'eventprime-event-calendar-management' ), 
            )
        );
        $event_types = EventM_Factory_Service::ep_get_event_types( array( 'id', 'name' ) );
        $performers  = EventM_Factory_Service::ep_get_performers( array( 'id', 'name' ) );
        $organizers  = EventM_Factory_Service::ep_get_organizers( array( 'id', 'name' ) );
        $venues      = EventM_Factory_Service::ep_get_venues( array( 'id', 'name' ) );
        include __DIR__ .'/settings/settings-admin-calendar.php';
    }
    
    /**
     * Show attendees lists of the event
     */
    public function ep_show_event_attendees_list() {
        if( isset( $_GET['event_id'] ) && ! empty( $_GET['event_id'] ) ) {
            $event_id = absint( $_GET['event_id'] );
            $em_event_checkout_attendee_fields = get_post_meta( $event_id, 'em_event_checkout_attendee_fields', true );
            $attendee_fileds_data = ( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] ) ? $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] : array() );
            include_once EP_BASE_DIR . 'includes/core/admin/template/event-attendee-list.php';
        }
    }
}

return new EventM_Admin_Menus();
