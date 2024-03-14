<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for core admin use
 */
class EventM_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'plugin_redirect' ) );
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'ep_admin_enqueues' ) );
        add_filter( 'display_post_states',array($this,'ep_display_post_states'),10, 2);
        //add_action( 'admin_notices', array($this,'ep_check_required_pages') );
        add_action( 'admin_footer', array( $this, 'ep_deactivation_feedback_form' ) );

        // admin notice for elementor
        add_action( 'admin_notices', array( $this, 'ep_check_for_elementor_plugin' ) );
        add_action( 'admin_init', array( $this, 'ep_check_licenses' ) );
    }
    
    
    public function ep_get_activate_extensions() {
                $ext = array(
                    'EP_Admin_Attendee_Booking'=>array('858','Admin Attendee Booking','ep_aab'),
                    'EP_Advanced_Checkout_Fields'=>array('22434','Advanced Checkout Fields','ep_acf'),
                    'EM_Advanced_Reports'=>array('21781','Advanced Reports','ep_reports'),
                    'EP_Attendees_List'=>array('966','Attendees List','ep_attendees'),
                    'EP_Elementor_Integration'=>array('22432','Elementor Integration','ep_elementor'),
                    'EP_Events_Import_Export'=>array('849','Events Import Export','ep_eix'),
                    'EP_List_Widget'=>array('852','Events List Widgets','ep_lw'),
                    'EM_Event_Tickets'=>array('861','Events Tickets','ep_ticket'),
                    'EP_Guest_Booking'=>array('864','Guest Booking','ep_guest'),
                    'EM_Event_Invoices'=>array('867','Invoices','ep_invoice'),
                    'EP_Live_Seating'=>array('870','Live Seating','ep_live_seating'),
                    'EP_Offline'=>array('876','Offline Payment','ep_offline'),
                    'EP_RSVP'=>array('23282','RSVP','ep_rsvp'),
                    'EP_Woocommerce_Integration'=>array('526','WooCommerce Integration','ep_wci'),
                    'EP_Coupons'=>array('846','Coupon Code','ep_coupons'),
                    'EM_Sponsor'=>array('855','EventPrime Sponsors','ep_sponsor'),
                    'EP_Feedback'=>array('22845','User Feedback','ep_feedback'),
                    'EP_Reviews'=>array('25465','Ratings and Reviews','ep_ratings_reviews'),
                    'EP_SMS_Integration'=>array('882','Twilio Text Notifications','ep_twilio'),
                    'EP_MailPoet'=>array('873','MailPoet Integration','ep_mailpoet'),
                    'EP_Zoom_Meetings'=>array('888','Zoom Integration','ep_zoom'),
                    'EP_Zapier_Integration'=>array('885','Zapier Integration','ep_zapier'),
                    'EP_Mailchimp_Integration'=>array('22842','Mailchimp Integration','ep_mailchimp'),
                    'EP_Stripe'=>array('879','Stripe Payment','ep_stripe'),
                    'EP_Woocommerce_Checkout_Integration'=>array('23284','WooCommerce Checkout','ep_wc_checkout'),
                    'EP_Certificate_Notification'=>array('26150','EventPrime Certificate Notification','ep_cn'),
                    'EP_Addresses_Separation'=>array('26147','EventPrime Addresses Separation','ep_as'),
                    'EM_Event_Badges'=>array('26143','EventPrime Badges','ep_badge')
                );

		$activate = array();
                //$activate['pg_premium'] =  array(70264, 'ProfileGrid Premium');
                //$activate['pg_premium_plus'] =  array(70261, 'ProfileGrid Premium+');
		foreach ( $ext as $key=>$value ) {
			if ( class_exists( $key ) ) {
                            $activate[$key] = $value;
			}
		}

		return $activate;
	}

    public function ep_check_licenses()
    {
            
     
         // Check if 24 hours have passed since the last check
        $last_check_time = get_transient('ep_license_last_check_time');
        // If last check time doesn't exist or it's more than 24 hours ago
        if (!$last_check_time || (time() - $last_check_time) > 24 * 60 * 60) {
           
            $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
            $options = $global_settings->ep_get_settings();
            //print_r($options);
            $ep_premium_license_option_value = $options->ep_premium_license_option_value;
            $activate_extensions = $this->ep_get_activate_extensions();
            if(isset($ep_premium_license_option_value) && !empty($ep_premium_license_option_value))
            {
                $ep_premium_license_option_value = $options->ep_premium_license_option_value;
                
                $item_name = $ep_premium_license_option_value.'_license_item_name';
                $item_id = $ep_premium_license_option_value.'_license_item_id';
                $premium_item_name = $options->$item_name;
                $premium_item_id = $options->$item_id;
                $activate_extensions['EP_PREMIUM'] = array($premium_item_id,$premium_item_name,'ep_premium');
            }
            
            if(!empty($activate_extensions))
            {
                foreach($activate_extensions as $key=>$extension)
                {
                    //print_r($extension);
                    $this->ep_check_license_status($extension);
                }
            }
            //die;
            set_transient('ep_license_last_check_time', time());
        }

    }
    
    public function ep_check_license_status($extension) 
    {
        
        $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        $options = $global_settings->ep_get_settings();
        $store_url='https://theeventprime.com';
        $item_name = $extension[1];
        $key = $extension[2].'_license_key';
        $status = $extension[2].'_license_status';
        $license_response = $extension[2].'_license_response';
        //echo $item_name;
        $license    = $options->$key;
        $item_id = $extension[0];
        if(!empty($license))
        {
            $api_params = array(
                    'edd_action' => 'check_license',
                    'license' => $license,
                    'item_id' => $item_id,
                    'url' => home_url(),
                    'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',

            );
            $response = wp_remote_post( $store_url, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );
            if ( is_wp_error( $response ) ) {
                    //return false;
            }

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            //print_r($license_data);
            if(isset($license_data) && !empty($license_data))
            {
                if( $license_data->license == 'valid' ) {

                        // this license is still valid
                } else {
                        $options->$status = ( isset( $license_data->license ) && ! empty( $license_data->license ) ) ? $license_data->license : '';
                        $options->$license_response  = ( isset( $license_data ) && ! empty( $license_data ) ) ? $license_data : '';
                        $global_settings->ep_save_settings( $options );
                        //exit;
                        // this license is no longer valid
                }
            }
        }
    }

    /**
     * Redirect plugin after activate
     */
    public function plugin_redirect() {
        if ( get_option( 'event_magic_do_activation_redirect', false ) ) {
            delete_option( 'event_magic_do_activation_redirect' );
            $check_for_migration = get_option( 'ep_db_need_to_run_migration' );
            $update_migration = get_option( 'ep_update_revamp_version' );
            if( ! empty( $check_for_migration ) && empty( $update_migration ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event&page=ep-revamp-migration' ) );
            } else{
                wp_safe_redirect( admin_url( 'edit.php?post_type=em_event' ) );
            }
            exit;
        }
    }

    /**
     * Include classes for admin use
     */
    public function includes() {
        // admin menu class
        include_once __DIR__ . '/class-ep-admin-menus.php';
        include_once __DIR__ . '/class-ep-admin-notices.php';
    }

    /**
     * Load common scripts and styles for admin
     */
    public function ep_admin_enqueues() {
        wp_enqueue_script(
            'ep-common-script',
            EP_BASE_URL . '/includes/assets/js/ep-common-script.js',
            array( 'jquery' ), EVENTPRIME_VERSION
        );

        // localized global settings
        $global_settings = ep_get_global_settings();
        $currency_symbol = ep_currency_symbol();
        wp_localize_script(
            'ep-common-script', 
            'eventprime', 
            array(
                'global_settings' => $global_settings,
                'currency_symbol' => $currency_symbol,
                'ajaxurl'         => admin_url( 'admin-ajax.php' ),
                'trans_obj'       => EventM_Factory_Service::ep_define_common_field_errors(),
            )
        );

        wp_enqueue_script(
			'ep-admin-utility-script',
			EP_BASE_URL . 'includes/assets/js/ep-admin-common-utility.js',
			array( 'jquery', 'jquery-ui-tooltip', 'jquery-ui-dialog' ), EVENTPRIME_VERSION
        );

        wp_localize_script(
            'ep-admin-utility-script', 
            'ep_admin_utility_script', 
            array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' )
            )
        );

        wp_enqueue_style(
			'ep-admin-utility-style',
			EP_BASE_URL . 'includes/assets/css/ep-admin-common-utility.css',
			false, EVENTPRIME_VERSION
        );

        //wp_enqueue_style( 'ep-material-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), EVENTPRIME_VERSION );
        wp_enqueue_style( 'ep-material-fonts', EP_BASE_URL . '/includes/assets/css/ep-material-fonts-icon.css', array(), EVENTPRIME_VERSION );
        
        // register common scripts
        wp_register_script(
			'em-admin-jscolor',
			EP_BASE_URL . '/includes/assets/js/jscolor.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
			'em-admin-select2-css',
			EP_BASE_URL . '/includes/assets/css/select2.min.css',
			false, EVENTPRIME_VERSION
		);
		wp_register_script(
			'em-admin-select2-js',
			EP_BASE_URL . '/includes/assets/js/select2.full.min.js',
			false, EVENTPRIME_VERSION
		);

        wp_register_style(
		    'em-admin-jquery-ui',
		    EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
		    false, EVENTPRIME_VERSION
        );
		// Ui Timepicker css
		wp_register_style(
		    'em-admin-jquery-timepicker',
		    EP_BASE_URL . '/includes/assets/css/jquery.timepicker.min.css',
		    false, EVENTPRIME_VERSION
        );

        // timepicker js
		wp_register_script(
		    'em-admin-timepicker-js',
		    EP_BASE_URL . '/includes/assets/js/jquery.timepicker.min.js',
		    false, EVENTPRIME_VERSION
        );

        // register toast
        wp_register_style(
            'ep-toast-css',
            EP_BASE_URL . '/includes/assets/css/jquery.toast.min.css',
            false, EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-js',
            EP_BASE_URL . '/includes/assets/js/jquery.toast.min.js',
            array('jquery'), EVENTPRIME_VERSION
        );
        wp_register_script(
            'ep-toast-message-js',
            EP_BASE_URL . '/includes/assets/js/toast-message.js',
            array('jquery'), EVENTPRIME_VERSION
        );

        // Blocks style for admin
        wp_register_script(
            'eventprime-admin-blocks-js',
            EP_BASE_URL . '/includes/assets/js/blocks/index.js',
            array( 'wp-blocks', 'wp-editor', 'wp-i18n', 'wp-element', 'wp-components' ),
            EVENTPRIME_VERSION
        );

		wp_register_style(
		    'ep-admin-blocks-style',
		    EP_BASE_URL . '/includes/assets/css/ep-admin-blocks-style.css',
		    false, EVENTPRIME_VERSION
        );
    }
    
    public function ep_display_post_states($post_states, $post){
        if ( intval( ep_get_global_settings( 'performers_page' ) ) === $post->ID ) {
            $post_states['ep_performers_page'] = __( 'EventPrime Performer Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'venues_page' ) ) === $post->ID ) {
            $post_states['ep_venues_page'] = __( 'EventPrime Venues Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'events_page' ) ) === $post->ID ) {
            $post_states['ep_events_page'] = __( 'EventPrime Events Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'booking_page' ) ) === $post->ID ) {
            $post_states['ep_booking_page'] = __( 'EventPrime Checkout Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'profile_page' ) ) === $post->ID ) {
            $post_states['ep_profile_page'] = __( 'EventPrime Profile Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_types' ) ) === $post->ID ) {
            $post_states['ep_event_types'] = __( 'EventPrime Event Types Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_submit_form' ) ) === $post->ID ) {
            $post_states['ep_event_submit_form'] = __( 'EventPrime Submit Event Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'booking_details_page' ) ) === $post->ID ) {
            $post_states['ep_booking_details_page'] = __( 'EventPrime Booking Details Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'event_organizers' ) ) === $post->ID ) {
            $post_states['ep_event_organizers'] = __( 'EventPrime Organizers Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'login_page' ) ) === $post->ID ) {
            $post_states['ep_login_page'] = __( 'EventPrime Login Page', 'eventprime-event-calendar-management' );
	}
        if ( intval( ep_get_global_settings( 'register_page' ) ) === $post->ID ) {
            $post_states['ep_register_page'] = __( 'EventPrime Registeration Page', 'eventprime-event-calendar-management' );
	}
        
	return $post_states;
    }
    
    public function ep_check_required_pages() {
        $notices = '';
        $pages = array(
            "events_page" => array("Event List", "[em_events"),
            "venues_page" => array("Site & Location", "[em_sites"),
            "booking_page" => array("Booking", "[em_booking"),
            "profile_page" => array("User Profile", "[em_profile"),
            "performers_page" => array("Performer List", "[em_performers"),
            "booking_details_page" => array("Booking Details", "[em_booking_details")
        );
        foreach ( $pages as $key => $value ) {
            $page_id = ep_get_global_settings( $key );
            $post = get_post( $page_id );
            if( empty( $post ) ) {
                $notices .= '<p> For ' . $value[0] . ' use ' . $value[1] . '] shortcode</p>';
                continue;
            }
            $short_code_exists = strpos( $post->post_content, $value[1] );
            if (empty($post) || $post->post_status == "trash" || $short_code_exists === false) {
                $notices .= '<p> For ' . $value[0] . ' use ' . $value[1] . '] shortcode</p>';
            }
        }

        if ( ! empty( $notices ) ) {
            echo '<div class="notice notice-error is-dismissible">EventPrime: It seems all the required pages are not configured.' . $notices .
            '<b>Note*: Once you have pasted all the shortcodes inside corresponding pages, you can configure the default pages in EventPrime Settings -> Pages. </b>' .
            '</div>';
        }
    }

    public function ep_deactivation_feedback_form() {
        // Enqueue feedback form scripts and render HTML on the Plugins backend page
        if ( get_current_screen()->parent_base == 'plugins' ) {
            wp_enqueue_script(
                'ep-plugin-feedback-js',
                EP_BASE_URL . '/includes/core/assets/js/ep-plugin-feedback.js',
                array('jquery'), EVENTPRIME_VERSION
            );
            wp_localize_script(
                'ep-plugin-feedback-js', 
                'ep_feedback', 
                array(
                    'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                    'option_error'   => esc_html__( 'Please select one option', 'eventprime-event-calendar-management' ),
                    'feedback_nonce' => wp_create_nonce( 'ep-plugin-deactivation-nonce' ),
                )
            );
            include_once __DIR__ . '/template/plugin-feedback.php';
        }
    }

    public function ep_check_for_elementor_plugin() {
        if ( get_current_screen()->parent_base == 'plugins' ) {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $installed_plugins = get_plugins();
            if( isset( $installed_plugins['elementor/elementor.php'] ) && ! empty( $installed_plugins['elementor/elementor.php'] ) && ! class_exists( 'EP_Elementor_Integration' ) ) {?>
                <div class="notice notice-error is-dismissible ep-p-2">
                    EventPrime widgets for Elementor with Elementor Integration Extension.
                    <a target="_blank" href="<?php echo esc_url( 'https://theeventprime.com/all-extensions/elementor-integration-extension/' );?>"><?php echo esc_html( 'Download Now', 'eventprime-event-calendar-management' );?></a>
                </div><?php
            }
        }
    }
}

return new EventM_Admin();