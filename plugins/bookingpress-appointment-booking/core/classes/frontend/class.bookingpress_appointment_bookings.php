<?php
if (! class_exists('bookingpress_appointment_bookings')  && class_exists('BookingPress_Core')) {
    class bookingpress_appointment_bookings Extends BookingPress_Core
    {
        var $bookingpress_form_category;
        var $bookingpress_form_service;
        var $bookingpress_hide_category_service;
        var $bookingpress_default_date_format;
        var $bookingpress_default_time_format;
        var $bookingpress_form_fields_error_msg_arr;
        var $bookingpress_form_fields_new;
        var $bookingpress_is_service_load_from_url;
        var $bookingpress_selected_service_param;

        var $bookingpress_mybooking_random_id;
        var $bookingpress_mybooking_default_date_format;
        var $bookingpress_mybooking_customer_username;
        var $bookingpress_mybooking_customer_email;
        var $bookingpress_mybooking_login_user_id;
        var $bookingpress_mybooking_wpuser_id;
        var $bookingpress_delete_customer_profile;
        var $bookingpress_calendar_list;
        var $bookingpress_google_calendar_link;
        var $bookingpress_yahoo_calendar_link;

        var $bookingpress_all_service_data;

        function __construct()
        {
            global $BookingPress;

            $this->bookingpress_form_category               = 0;
            $this->bookingpress_form_service                = 0;
            $this->bookingpress_hide_category_service       = 0;
            $this->bookingpress_default_date_format         = get_option('date_format');
            $this->bookingpress_default_time_format         = get_option('time_format');
            $this->bookingpress_form_fields_error_msg_arr   = array();
            $this->bookingpress_form_fields_new             = array();
            $this->bookingpress_is_service_load_from_url    = 0;
            $this->bookingpress_calendar_list               = '';
            $this->bookingpress_google_calendar_link        = '';
            $this->bookingpress_yahoo_calendar_link         = '';
            $this->bookingpress_mybooking_customer_username = '';
            $this->bookingpress_mybooking_customer_email    = '';
            $this->bookingpress_mybooking_wpuser_id         = 0;

            $this->bookingpress_all_service_data           = array();

            add_filter('bookingpress_front_booking_dynamic_data_fields', array( $this, 'bookingpress_booking_dynamic_data_fields_func' ), 10, 5);

            add_filter('bookingpress_front_booking_dynamic_helper_vars', array( $this, 'bookingpress_booking_dynamic_helper_vars_func' ), 10, 1);

            if( $BookingPress->bpa_is_pro_exists() && $BookingPress->bpa_is_pro_active() ){
                if( !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '1.5', '>' ) ){
                    add_filter( 'bookingpress_front_booking_dynamic_on_load_methods', array( $this, 'bookingpress_booking_dynamic_on_load_methods_func_with_pro'));
                } else {
                    add_filter('bookingpress_front_booking_dynamic_on_load_methods', array( $this, 'bookingpress_booking_dynamic_on_load_methods_func' ), 10, 1);
                }
            } else {
                add_filter('bookingpress_front_booking_dynamic_on_load_methods', array( $this, 'bookingpress_booking_dynamic_on_load_methods_func' ), 10, 1);
            }
            add_filter( 'bookingpress_front_booking_dynamic_on_load_methods', array( $this, 'bookingpress_call_autofocus_method'), 100 );
            
            add_filter('bookingpress_front_booking_dynamic_vue_methods', array( $this, 'bookingpress_booking_dynamic_vue_methods_func' ), 10, 1);
            add_action('media_buttons', array( $this, 'bookingpress_insert_shortcode_button' ), 20);

            add_shortcode('bookingpress_form', array( $this, 'bookingpress_front_booking_form' ));
            add_shortcode('bookingpress_company_avatar', array( $this, 'bookingpress_company_avatar_func' ));
            add_shortcode('bookingpress_company_name', array( $this, 'bookingpress_company_name_func' ));
            add_shortcode('bookingpress_company_website', array( $this, 'bookingpress_company_website_func' ));
            add_shortcode('bookingpress_company_address', array( $this, 'bookingpress_company_address_func' ));
            add_shortcode('bookingpress_company_phone', array( $this, 'bookingpress_company_phone_func' ));
            add_shortcode('bookingpress_appointment_service', array( $this, 'bookingpress_appointment_service_func' ));
            add_shortcode('bookingpress_appointment_datetime', array( $this, 'bookingpress_appointment_datetime_func' ));
            add_shortcode('bookingpress_appointment_customername', array( $this, 'bookingpress_appointment_customername_func' ));
            add_shortcode('bookingpress_my_appointments', array( $this, 'bookingpress_my_appointments_func' ));
            add_shortcode('bookingpress_delete_account', array($this, 'bookingpress_delete_account_func'));
            add_shortcode('booking_id', array($this, 'bookingpress_booking_id_func'));
            add_shortcode('bookingpress_appointment_cancellation_confirmation', array($this, 'bookingpress_appointment_cancellation_confirmation_func'));

            add_action('wp_ajax_bookingpress_front_get_category_services', array( $this, 'bookingpress_get_category_service_data' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_front_get_category_services', array( $this, 'bookingpress_get_category_service_data' ), 10);

            add_action('wp_ajax_bookingpress_front_get_timings', array( $this, 'bookingpress_retrieve_timeslots' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_front_get_timings', array( $this, 'bookingpress_retrieve_timeslots' ), 10);

            add_action('wp_ajax_bookingpress_front_save_appointment_booking', array( $this, 'bookingpress_save_appointment_booking_func' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_front_save_appointment_booking', array( $this, 'bookingpress_save_appointment_booking_func' ), 10);

            add_action('wp_ajax_nopriv_bookingpress_validate_username', array( $this,'validate_bookingpress_username'),10);

            /* add_action('wp_ajax_bookingpress_before_book_appointment', array( $this, 'bookingpress_before_book_appointment_func' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_before_book_appointment', array( $this, 'bookingpress_before_book_appointment_func' ), 10); */

            add_action('wp_ajax_bookingpress_cancel_appointment', array( $this, 'bookingpress_cancel_appointment' ), 10);
            add_action('wp', array( $this, 'bookingpress_cancel_appointment_func' ), 10);

            add_action('wp_ajax_bookingpress_front_cancel_appointment', array( $this, 'bookingpress_front_cancel_appointment_func' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_front_cancel_appointment', array( $this, 'bookingpress_front_cancel_appointment_func' ), 10);

            /* fornt-end mybooking */

            add_action('bookingpress_front_appointments_dynamic_data_fields', array( $this, 'bookingpress_front_appointments_dynamic_data_fields_func' ));
            add_action('bookingpress_front_appointments_dynamic_helper_vars', array( $this, 'bookingpress_front_appointments_dynamic_helper_vars_func' ));
            add_action('bookingpress_front_appointments_dynamic_on_load_methods', array( $this, 'bookingpress_front_appointments_dynamic_on_load_methods_func' ));
            add_action('bookingpress_front_appointments_dynamic_vue_methods', array( $this, 'bookingpress_front_appointments_dynamic_vue_methods_func' ));

            add_action('wp_ajax_bookingpress_get_customer_appointments', array( $this, 'bookingpress_get_customer_appointments_func' ), 10);

            add_action('wp_ajax_bookingpress_get_disable_date', array( $this, 'bookingpress_get_disable_date_func' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_get_disable_date', array( $this, 'bookingpress_get_disable_date_func' ), 10);
            // New action for BG Calls to check disable dates for full day booking
            add_action('wp_ajax_bookingpress_get_whole_day_appointments', array( $this, 'bookingpress_get_whole_day_appointments_func' ), 10);
            add_action('wp_ajax_nopriv_bookingpress_get_whole_day_appointments', array( $this, 'bookingpress_get_whole_day_appointments_func' ), 10);                      

            add_filter( 'bookingpress_check_available_timeslot_manual_block', array( $this, 'bookingpress_check_available_timeslot_manual_block_func'), 12, 2);

            /** Calendar Integration Data */
			add_shortcode( 'bookingpress_appointment_calendar_integration', array( $this, 'bookingpress_booking_calendar_options' ) );
			add_action( 'init', array( $this, 'bookingpress_download_ics_file' ) );

            add_action('wp_ajax_bookingpress_delete_account', array($this, 'bookingpress_delete_customer_account_func'));          
            
            add_action('bookingpress_cancellation_confirmation_dynamic_data_fields', array( $this, 'bookingpress_cancellation_confirmation_dynamic_data_fields_func' ));
            add_action('bookingpress_cancellation_confirmation_dynamic_helper_vars', array( $this, 'bookingpress_cancellation_confirmation_dynamic_helper_vars_func' ));
            add_action('bookingpress_cancellation_confirmation_dynamic_vue_methods', array( $this, 'bookingpress_cancellation_confirmation_dynamic_vue_methods_func' ));

            /** Action to set pre-loaded fonts */
            add_action( 'wp_head', array( $this, 'bookingpress_preloaded_fonts'), 99999 );

            add_filter( 'jet-reviews/frontend/deps-scripts', array( $this, 'change_jet_review_deps') );
        }

        function change_jet_review_deps( $jet_vue_deps ){
			foreach( $jet_vue_deps as $key => $dep ){
				if( 'jet-vue' == $dep ){
					$jet_vue_deps[ $key ] = 'bookingpress_vue_js';
				}
			}

			return $jet_vue_deps;
		}

        function validate_bookingpress_username(){

            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs,$tbl_bookingpress_customers,$bookingpress_payment_gateways;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                $response['redirect_url'] = '';
                return wp_json_encode($response);
                
            }
            $response['variant']    = 'success';
            $response['title']      = '';
            $response['msg']        = '';

            if( !empty( $_REQUEST['_username'] ) && !is_array( $_REQUEST['_username'] ) ){
                $response = apply_filters('bpa_password_field_validation_outside', $response);
                $username = sanitize_text_field($_REQUEST['_username']); 
                $check_username = validate_username( $username );
                if( $check_username == 1 ){
                    $response['variant'] = 'success'; 
                    $response['title']   = esc_html__('success', 'bookingpress-appointment-booking');
                    $response['msg']     = esc_html__('Validate Username', 'bookingpress-appointment-booking');
                    echo json_encode( $response );
                } else {
                    $response['variant'] = 'error'; 
                    $response['title']   = esc_html__('error', 'bookingpress-appointment-booking');
                    $response['msg']     = esc_html__('Please enter validate username', 'bookingpress-appointment-booking');
                    echo json_encode( $response );
                }
            }
            die;
        }

        function bookingpress_front_cancel_appointment_func() {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs, $bookingpress_email_notifications, $bookingpress_services;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                wp_send_json($response);
                die();
            }

            $response['variant']    = 'error';
            $response['title']      = 'Error';
            $response['msg']        = 'Something went wrong when process with cancel appointment....';
            
            if(!empty($_REQUEST['appointment_id']) && !empty($_REQUEST['cancel_token'])) {

                $appointment_id = intval($_REQUEST['appointment_id']);// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_id'] sanitized properly
                $bookingpress_cancel_token = sanitize_text_field($_REQUEST['cancel_token']);
                
                $bookingpress_appointment_log_data = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_customer_email,bookingpress_service_id,bookingpress_appointment_date,bookingpress_appointment_time,bookingpress_customer_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d AND bookingpress_appointment_token = %s", $appointment_id,$bookingpress_cancel_token), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_log_data is table name defined globally. False Positive alarm

                if (! empty($bookingpress_appointment_log_data) ) {

                    $bookingpress_appointment_date = $bookingpress_appointment_log_data['bookingpress_appointment_date'];
                    $bookingpress_appointment_time = $bookingpress_appointment_log_data['bookingpress_appointment_time'];

                    $bookingpress_appointment_datetime = $bookingpress_appointment_date." ".$bookingpress_appointment_time;                        
                    $current_datetime = date( 'Y-m-d H:i:s', current_time('timestamp') );
                    $allow_cancel_appointment = true;
                    
                    if( $bookingpress_appointment_datetime <= $current_datetime ){
                        $allow_cancel_appointment = false;
                    }

                    $allow_cancel_appointment = apply_filters( 'bookingpress_modify_cancel_appointment_flag', $allow_cancel_appointment, $bookingpress_appointment_log_data );

                    if($allow_cancel_appointment){
                        $appointment_cancelled_successfully = $BookingPress->bookingpress_get_settings('appointment_cancelled_successfully', 'message_setting');
                        $response['variant']      = 'success';
                        $response['title']        = esc_html__('Success', 'bookingpress-appointment-booking');
                        $response['msg']          = $appointment_cancelled_successfully;
                        $response = apply_filters('bookingpress_refund_process_before_cancel_appointment',$response,$appointment_id);
                        if($response['variant'] == 'success' ) {
                            $bookingress_customer_email = $bookingpress_appointment_log_data['bookingpress_customer_email'];
                            $bookingpress_after_canceled_payment_page_id = $BookingPress->bookingpress_get_customize_settings('after_cancelled_appointment_redirection', 'booking_my_booking');                        
                            $bookingpress_after_canceled_payment_url     = get_permalink($bookingpress_after_canceled_payment_page_id);
                            $bookingpress_after_canceled_payment_url = ! empty($bookingpress_after_canceled_payment_url) ? $bookingpress_after_canceled_payment_url : BOOKINGPRESS_HOME_URL;
                            $wpdb->update($tbl_bookingpress_appointment_bookings, array( 'bookingpress_appointment_status' => '3' ), array( 'bookingpress_appointment_booking_id' => $appointment_id ));
                            $bookingpress_email_notifications->bookingpress_send_after_payment_log_entry_email_notification('Appointment Canceled', $appointment_id, $bookingress_customer_email);
                            $wpdb->update($tbl_bookingpress_appointment_bookings,array('bookingpress_appointment_token' => ''),array('bookingpress_appointment_booking_id' => $appointment_id));
                            do_action('bookingpress_after_cancel_appointment', $appointment_id);
                            $response['redirect_url'] = $bookingpress_after_canceled_payment_url;
                        }

                    } else {                                
                        $response['variant']    = 'error';
                        $response['title']      = 'Error';
                        $response['msg']        = __("We're sorry! you can't cancel this appointment because minimum required time for cancellation is already passed", "bookingpress-appointment-booking");
                    }                    
                }
            }    
            wp_send_json( $response);
            die();
        }
             
        /**
         * bookingpress_cancellation_confirmation_dynamic_data_fields_func
         *
         * @return void
         */
        function bookingpress_cancellation_confirmation_dynamic_data_fields_func()
        {
            global $bookingpress_front_confirmation_appointment_vue_data_fields, $BookingPress, $bookingpress_global_options;
            $bookingpress_front_confirmation_appointment_vue_data_fields = array();
            $bookingpress_front_confirmation_appointment_vue_data_fields['is_cancel_appointment_loader'] = '0';
            $bookingpress_front_confirmation_appointment_vue_data_fields['is_cancel_button_disabled'] = false;

            $bookingpress_front_confirmation_appointment_vue_data_fields['bookingpress_cancel_variant'] = '';
            $bookingpress_front_confirmation_appointment_vue_data_fields['bookingpress_cancel_msg'] = '';

            $bookingpress_front_confirmation_appointment_vue_data_fields = apply_filters('bookingpress_cancellation_confirmation_add_dynamic_data', $bookingpress_front_confirmation_appointment_vue_data_fields);

            echo json_encode($bookingpress_front_confirmation_appointment_vue_data_fields);
        }        
        /**
         * bookingpress_cancellation_confirmation_dynamic_helper_vars_func
         *
         * @return void
         */
        function bookingpress_cancellation_confirmation_dynamic_helper_vars_func()
        {
            global $bookingpress_global_options;
            $bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_locale_lang = $bookingpress_options['locale'];
            ?>
            var lang = ELEMENT.lang.<?php echo esc_html($bookingpress_locale_lang); ?>;
            ELEMENT.locale(lang);
            <?php
        }        
        
        /**
         * bookingpress_cancellation_confirmation_dynamic_vue_methods_func
         *
         * @return void
         */
        function bookingpress_cancellation_confirmation_dynamic_vue_methods_func() {
            global $bookingpress_notification_duration;
            ?>
            bookingpress_cancel_appointment(appointment_id,cancel_token) {
                const vm = this;
                vm.is_cancel_appointment_loader = '1'
                vm.is_cancel_button_disabled = true
                var appointment_cancel_data = { action: 'bookingpress_front_cancel_appointment',appointment_id:appointment_id,cancel_token,_wpnonce:'<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>' };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( appointment_cancel_data ) )
                .then(function(response){
                    vm.is_cancel_appointment_loader = '0'
                    vm.is_cancel_button_disabled = false
                    
                    if(response.data.variant != undefined && response.data.variant != 'error'){
                        window.location.href = response.data.redirect_url;
                    } else {
                        vm.bookingpress_cancel_variant = response.data.variant;
                        vm.bookingpress_cancel_msg = response.data.msg;
                    }
                }).catch(function(error){
                    console.log(error);
                    vm.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-appointment-booking'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-appointment-booking'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                        duration:<?php echo intval($bookingpress_notification_duration); ?>,
                    });
                });
            },
            <?php            
        }        

        function bookingpress_preloaded_fonts(){

            global $post;

            if( empty( $post ) ){
                return;
            }

            $post_content = $post->post_content;
            
            $pattern = '/\[(bookingpress_(.*?)\])/';

            if( preg_match( $pattern, $post_content ) ){

                global $BookingPress, $bookingpress_global_options;

                $google_fonts = $bookingpress_global_options->bookingpress_get_google_fonts();
                $bookingpress_title_fonts = $BookingPress->bookingpress_get_customize_settings(
                    'title_font_family',
                    'booking_form'
                );
                
                $bpa_font_dir_url =  BOOKINGPRESS_URL.'/css/fonts';

                $bpa_preload_font_file_arr = array(
                    'element-icons.woff2'
                );

                if( 'poppins' == strtolower( trim( $bookingpress_title_fonts) ) ){
                    $bpa_preload_font_file_arr = array_merge( $bpa_preload_font_file_arr, array(
                        'poppins/Poppins-Bold.woff2',
                        'poppins/Poppins-Medium.woff2',
                        'poppins/Poppins-Regular.woff2',
                        'poppins/Poppins-SemiBold.woff2'
                    ));
                }

                $bpa_preload_font_file_arr = apply_filters( 'bookingpress_modify_preload_font_arr', $bpa_preload_font_file_arr );

                if( !empty( $bpa_preload_font_file_arr ) ){
                    foreach( $bpa_preload_font_file_arr as $bpa_font_file ){
                        if( filter_var( $bpa_font_file, FILTER_VALIDATE_URL ) ){
                            echo '<link rel="preload" href="'.$bpa_font_file.'" as="font" type="font/woff2" crossorigin />'; //phpcs:ignore
                        } else {
                            echo '<link rel="preload" href="'.$bpa_font_dir_url.'/'.$bpa_font_file.'" as="font" type="font/woff2" crossorigin />'; //phpcs:ignore
                        }
                    }
                }

                
            }
        }
        
        /**
         * Used for delete customer account
         *
         * @return void
         */
        function bookingpress_delete_customer_account_func(){
            global $wpdb, $tbl_bookingpress_customers, $tbl_bookingpress_appointment_bookings, $BookingPress, $tbl_bookingpress_customers_meta,$tbl_bookingpress_payment_logs;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');

            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                wp_send_json($response);
                die();
            }

            $response['variant']    = 'error';
            $response['title']      = 'Error';
            $response['msg']        = 'Something went wrong....';

            $bookingpress_login_user_id = get_current_user_id();

            if(!empty($bookingpress_login_user_id)){
                $bookingpress_customer_rows = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d ORDER BY bookingpress_customer_id DESC", $bookingpress_login_user_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_customers is a table name. false alarm
                
                do_action('bookingpress_delete_customer_log',$bookingpress_customer_rows,$_REQUEST);

                if(!empty($bookingpress_customer_rows)){
                    do_action( 'bookingpress_before_delete_customer', $bookingpress_customer_rows['bookingpress_customer_id'] );
                    $delete = $wpdb->delete( $tbl_bookingpress_customers, array( 'bookingpress_customer_id' => $bookingpress_customer_rows['bookingpress_customer_id'] ), array( '%d' ) );
                    if ( $delete > 0 ) {
                        $delete1 = $wpdb->delete( $tbl_bookingpress_customers_meta, array( 'bookingpress_customer_id' => $bookingpress_customer_rows['bookingpress_customer_id'] ), array( '%d' ) );
                        $delete2 = $wpdb->delete( $tbl_bookingpress_appointment_bookings, array( 'bookingpress_customer_id' => $bookingpress_customer_rows['bookingpress_customer_id'] ), array( '%d' ) );
                        $delete3 = $wpdb->delete( $tbl_bookingpress_payment_logs, array( 'bookingpress_customer_id' => $bookingpress_customer_rows['bookingpress_customer_id'] ), array( '%d' ) );
                        $customer_role_id = new WP_User( $bookingpress_login_user_id );
                        $customer_role_id->remove_role('bookingpress-customer');

                        wp_logout();

                        $response['variant'] = 'success';
                        $response['title'] = esc_html__('Success', 'bookingpress-appointment-booking');
                        $response['msg'] = esc_html__('Account Deleted Successfully', 'bookingpress-appointment-booking');
                    }
                }else{
                    $response['msg'] = esc_html__('No customers exist in BookingPress', 'bookingpress-appointment-booking');    
                }
            }else{
                $response['msg'] = esc_html__('Please login to your account for delete BookingPress account', 'bookingpress-appointment-booking');
            }

            echo wp_json_encode($response);
            exit;
        }
        
        /**
         * Customer delete account shortcode callable function
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_delete_account_func($atts, $content, $tag){
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries,$bookingpress_global_options;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1); 
            $BookingPress->bookingpress_load_mybooking_custom_css();
            //$BookingPress->bookingpress_load_mybookings_custom_js();

            $bookingpress_short_atts = array(
                'cancel_button_text' => esc_html__('Cancel', 'bookingpress-appointment-booking'),
                'delete_button_text' => esc_html__('Delete', 'bookingpress-appointment-booking'),
            );

            $atts = shortcode_atts($bookingpress_short_atts, $atts, $tag);

            $bookingpress_nonce = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $content = '<div class="bpa-front-dcw__body-btn-group">';
            $content .= '<el-button class="el-button bpa-front-btn bpa-front-btn__medium" @click="bookingpress_cancel_delete_account()"><span>'. $atts['cancel_button_text'].'</span></el-button>';
            $content .= '<el-button class="el-button bpa-front-btn bpa-front-btn__medium bpa-front-btn--danger" @click="bookingpress_delete_account()"><span>'.$atts['delete_button_text'].'</span></el-button>';
            $content .= '</div>';

            return do_shortcode($content);
        }
                
        /**
         * bookingpress_appointment_cancellation_confirmation_func
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_appointment_cancellation_confirmation_func($atts, $content, $tag)  {

            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries,$bookingpress_global_options;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1); 
            $BookingPress->bookingpress_load_booking_form_custom_css();
            $bookingpress_uniq_id = uniqid();
            $appointment_id = 0;

            if(!empty($_GET['appointment_id']) && !empty($_GET['cancel_token'])){
                $bkp_ap_id = intval(base64_decode($_GET['appointment_id'])); //phpcs:ignore
                $bookingpress_cancel_token = sanitize_text_field($_GET['cancel_token']);

                $bpa_cancel_booking_id_text = $BookingPress->bookingpress_get_customize_settings('cancel_booking_id_text', 'booking_my_booking');
                $bpa_cancel_service_text = $BookingPress->bookingpress_get_customize_settings('cancel_service_text', 'booking_my_booking');
                $bpa_cancel_date_time_text = $BookingPress->bookingpress_get_customize_settings('cancel_date_time_text', 'booking_my_booking');
                $bpa_cancel_button_text = $BookingPress->bookingpress_get_customize_settings('cancel_button_text', 'booking_my_booking');
                $bpa_appointment_cancellation_confirmation = $BookingPress->bookingpress_get_settings('cancel_appointment_confirmation','message_setting');
                $bpa_no_appointment_available_for_cancel = $BookingPress->bookingpress_get_settings('no_appointment_available_for_cancel','message_setting');

                $bpa_cancel_booking_id_text = !empty($bpa_cancel_booking_id_text) ? esc_html($bpa_cancel_booking_id_text) : '';
                $bpa_cancel_service_text = !empty($bpa_cancel_service_text) ? esc_html($bpa_cancel_service_text) : '';
                $bpa_cancel_date_time_text = !empty($bpa_cancel_date_time_text) ? esc_html($bpa_cancel_date_time_text) : '';
                $bpa_cancel_button_text = !empty($bpa_cancel_button_text) ? esc_html($bpa_cancel_button_text) : '';

                $bpa_no_appointment_available_for_cancel = !empty($bpa_no_appointment_available_for_cancel) ? esc_html($bpa_no_appointment_available_for_cancel) : '';
                
                $bpa_appointment_cancellation_confirmation = !empty($bpa_appointment_cancellation_confirmation) ? esc_html($bpa_appointment_cancellation_confirmation) : '';

                $bookingpress_appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_appointment_status,bookingpress_appointment_timezone,bookingpress_appointment_date,bookingpress_appointment_time,bookingpress_booking_id,bookingpress_service_name FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d",$bkp_ap_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                
                if(!empty($bookingpress_appointment_data) && $bookingpress_appointment_data['bookingpress_appointment_status'] != '3') {
                    $bookingpress_global_details     = $bookingpress_global_options->bookingpress_global_options();
                    $bookingpress_default_date_time_format = $bookingpress_global_details['wp_default_date_format'] . ' ' . $bookingpress_global_details
                    ['wp_default_time_format'];

                    $booked_appointment_datetime = esc_html($bookingpress_appointment_data['bookingpress_appointment_date']) . ' ' . esc_html($bookingpress_appointment_data['bookingpress_appointment_time']);
                    $bpa_appointment_booking_id = '#'.esc_html($bookingpress_appointment_data['bookingpress_booking_id']);
                    $bpa_appointment_service_name = stripslashes_deep(esc_html($bookingpress_appointment_data['bookingpress_service_name']));

                    $booked_appointment_datetime = apply_filters( 'bookingpress_appointment_change_to_client_timezone', $booked_appointment_datetime, $bookingpress_appointment_data['bookingpress_appointment_timezone'], $bookingpress_appointment_data );
                    $bpa_appointment_date_time = date($bookingpress_default_date_time_format, strtotime($booked_appointment_datetime));

                    $content .=' <el-main class="bpa-appointment-cancellation_container" id="bookingpress_appointment_cancellation_form_'.$bookingpress_uniq_id.'">
                    <div class="bpa-front-thankyou-module-container bpa-front-cancel-confirmation-container">
                    <div class="bpa-front-cc__error-toast-notification" v-if="bookingpress_cancel_variant == \'error\'">{{bookingpress_cancel_msg}}</div>
                    <div class="bpa-front-tmc__head">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" class="bpa-front-tmc__vector--confirmation" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M90.7002 85.4609C84.4375 93.0097 72.3956 90.9929 62.8277 93.3709C53.5335 95.6809 44.3866 102.22 35.2915 99.2322C26.2099 96.2487 22.8396 85.5958 16.7964 78.2483C10.8588 71.029 1.14803 65.6955 0.0978856 56.4422C-0.951171 47.1985 6.67152 39.5221 11.4957 31.5412C15.8676 24.3085 20.1527 17.1388 26.9344 12.0453C34.1982 6.58966 42.3994 1.52536 51.5153 1.48363C60.7 1.44158 68.4886 7.22002 76.4135 11.8188C84.8089 16.6906 95.7104 20.0478 99.0838 29.0876C102.453 38.117 94.606 47.2497 93.1897 56.7734C91.7484 66.465 96.9793 77.8922 90.7002 85.4609Z"
                                class="bpa-front-vc__bg" />
                            <path
                                d="M82.6133 69.7307C82.3433 70.1385 82.386 70.6258 82.2851 71.0748C80.968 76.9215 75.5745 81.4923 68.9549 81.3047C62.9363 81.13 57.6068 76.5791 56.5156 70.5647C56.1894 68.8291 56.2157 67.0455 56.5929 65.3202C56.9701 63.5949 57.6905 61.9631 58.7112 60.5219C59.7319 59.0808 61.0321 57.8596 62.5344 56.9311C64.0367 56.0027 65.7104 55.3859 67.4559 55.1175C74.6368 53.9809 81.444 59.0447 82.4911 66.3093C82.5082 66.4259 82.4286 66.5935 82.6133 66.6475V69.7307ZM63.219 73.5939C63.2276 73.7806 63.2905 73.9608 63.4 74.1123C63.5096 74.2637 63.661 74.3799 63.8357 74.4464C64.2903 74.6283 64.6541 74.4777 64.9723 74.1622C66.3932 72.7414 67.814 71.3419 69.2078 69.9126C69.492 69.617 69.6468 69.5772 69.958 69.8983C71.2026 71.1856 72.48 72.4402 73.7473 73.7062C73.8633 73.8323 73.9877 73.9505 74.1196 74.0599C74.3031 74.2167 74.5389 74.2986 74.7801 74.2894C75.0212 74.2802 75.2501 74.1805 75.4211 74.0102C75.5943 73.8436 75.6998 73.6188 75.7173 73.3791C75.7349 73.1394 75.6633 72.9017 75.5163 72.7116C75.399 72.5594 75.2698 72.4169 75.1298 72.2853C73.8511 71.0066 72.5723 69.7179 71.2936 68.4491C71.0449 68.2061 71.0222 68.0754 71.2865 67.8268C72.021 67.1348 72.7244 66.406 73.4404 65.6955L75.4296 63.7178C75.7223 63.4251 75.8473 63.0798 75.7138 62.6777C75.6621 62.5025 75.5615 62.3457 75.4238 62.2257C75.2862 62.1057 75.1172 62.0274 74.9366 62C74.5103 61.9176 74.2105 62.1321 73.9292 62.4134C72.6391 63.7092 71.3248 64.9837 70.0646 66.3065C69.6809 66.7086 69.4962 66.6319 69.1538 66.2838C67.8751 64.9738 66.5736 63.6908 65.2807 62.3978C65.1997 62.3168 65.1201 62.2344 65.0377 62.1563C64.5106 61.649 63.9635 61.6007 63.5245 62.0142C63.0855 62.4277 63.1267 63.0244 63.651 63.5771L63.732 63.6581C65.0661 64.9908 66.3804 66.3463 67.7472 67.6435C68.1848 68.0598 68.1209 68.2601 67.7245 68.6381C66.3647 69.9452 65.0491 71.2964 63.7106 72.6249C63.4378 72.8949 63.2119 73.1662 63.219 73.5939Z"
                                class="bpa-head__vector-item" />
                            <path
                                d="M46.9914 34.1763C54.4242 34.1763 61.8599 34.1716 69.2983 34.1621C69.8312 34.1621 69.942 34.3042 69.9406 34.8171C69.9216 40.6899 69.9216 46.5626 69.9406 52.4353C69.9406 52.9127 69.8212 53.0165 69.3523 53.0207C65.7787 53.0403 62.3274 54.3247 59.6103 56.6461C56.8933 58.9676 55.086 62.1762 54.509 65.703C54.2154 67.4168 54.24 69.1699 54.5815 70.8748C54.6738 71.3209 54.553 71.4133 54.1126 71.4133C45.7453 71.4005 37.3781 71.4133 29.0109 71.4005C26.0414 71.4005 24.0678 69.4113 24.0678 66.4489C24.0678 55.8894 24.0621 45.3303 24.0508 34.7716C24.0508 34.2189 24.2398 34.1735 24.6973 34.1735C32.1244 34.182 39.5558 34.1829 46.9914 34.1763ZM45.5976 49.1689H53.8824C54.7789 49.1689 55.6755 49.1816 56.5706 49.1589C56.6996 49.1597 56.8275 49.1347 56.9467 49.0855C57.0659 49.0363 57.1742 48.9638 57.265 48.8722C57.3559 48.7807 57.4277 48.6719 57.476 48.5523C57.5244 48.4327 57.5484 48.3047 57.5467 48.1757C57.5375 47.9256 57.436 47.6877 57.2617 47.508C57.0875 47.3283 56.853 47.2195 56.6033 47.2024C56.4328 47.1868 56.2609 47.1953 56.0889 47.1953H35.0536C34.8822 47.1889 34.7105 47.1918 34.5393 47.2039C34.4009 47.2136 34.2662 47.2528 34.1442 47.3188C34.0221 47.3847 33.9155 47.4759 33.8315 47.5863C33.7475 47.6967 33.6879 47.8237 33.6569 47.9589C33.6258 48.0941 33.6239 48.2344 33.6513 48.3704C33.738 48.9003 34.1273 49.1674 34.8476 49.1674C38.4309 49.1703 42.0143 49.1708 45.5976 49.1689ZM41.1419 58.3943H47.3097C48.2545 58.3943 48.7461 58.049 48.7461 57.3897C48.7461 56.7305 48.2659 56.3952 47.3054 56.3952H35.0281C34.838 56.3897 34.6479 56.4006 34.4597 56.4279C33.9724 56.5159 33.6783 56.8101 33.6413 57.3116C33.6044 57.8132 33.8332 58.1428 34.2992 58.3246C34.4993 58.3872 34.7096 58.4108 34.9187 58.3943H41.1419Z"
                                fill="#727E95" />
                            <path
                                d="M46.9156 32.214C39.4838 32.214 32.051 32.2177 24.6172 32.2253C24.1469 32.2253 24.0034 32.1259 24.0318 31.6428C24.1114 30.2561 23.9025 28.8565 24.1455 27.4783C24.3243 26.4557 24.8489 25.5255 25.6315 24.8434C26.4142 24.1614 27.4074 23.7689 28.4449 23.7316C30.4824 23.6748 32.5227 23.7174 34.5616 23.7004C34.867 23.7004 34.9466 23.7941 34.9409 24.0883C34.9239 24.9265 34.9296 25.7648 34.9409 26.6031C34.9409 27.3533 35.3331 27.8137 35.9355 27.8137C36.5379 27.8137 36.9301 27.3547 36.9301 26.6017C36.9386 25.8217 36.9599 25.0388 36.9215 24.2588C36.9002 23.8112 37.0295 23.6904 37.4799 23.6904C42.4528 23.7075 47.4257 23.7018 52.3986 23.7018C53.7896 23.7018 55.182 23.7132 56.573 23.6933C56.9154 23.6933 57.0362 23.7657 57.0234 24.1309C56.9935 24.9493 57.0077 25.7691 57.0148 26.5889C57.0219 27.3434 57.4027 27.8023 58.0094 27.808C58.6161 27.8137 59.004 27.3519 59.0168 26.606C59.0253 25.7861 59.0338 24.9663 59.0168 24.1479C59.0083 23.814 59.0722 23.6876 59.443 23.6919C61.3668 23.7132 63.2935 23.6805 65.2173 23.7075C67.9623 23.7458 69.9188 25.7847 69.906 28.5383C69.906 29.567 69.8762 30.5971 69.9174 31.6243C69.9358 32.1017 69.808 32.2154 69.3306 32.214C61.8618 32.2045 54.3901 32.2045 46.9156 32.214Z"
                                fill="#727E95" />
                            <path
                                d="M8.12945 32.8244L8.16195 32.9235H8.25458L8.17964 32.9774L8.21066 33.072L8.12945 33.0136L8.04823 33.072L8.07925 32.9774L8.00431 32.9235H8.09695L8.12945 32.8244Z"
                                stroke="#12D488" stroke-width="0.758084" />
                            <path
                                d="M98.6334 39.8249L98.6659 39.924H98.7585L98.6835 39.9779L98.7146 40.0725L98.6334 40.0141L98.5521 40.0725L98.5832 39.9779L98.5082 39.924H98.6009L98.6334 39.8249Z"
                                stroke="#12D488" stroke-width="0.758084" />
                            <path
                                d="M70.5904 92.4333L70.6229 92.5324H70.7155L70.6406 92.5863L70.6716 92.6809L70.5904 92.6225L70.5092 92.6809L70.5402 92.5863L70.4652 92.5324H70.5579L70.5904 92.4333Z"
                                stroke="#F5AE41" stroke-width="0.758084" />
                            <path
                                d="M47.4161 1.12892C47.4161 1.53965 47.079 1.8788 46.6554 1.8788C46.2318 1.8788 45.8947 1.53965 45.8947 1.12892C45.8947 0.718185 46.2318 0.379042 46.6554 0.379042C47.079 0.379042 47.4161 0.718185 47.4161 1.12892Z"
                                stroke="#EE2445" stroke-opacity="0.7" stroke-width="0.758084" />
                            <path
                                d="M94.1466 65.4766C94.1466 65.8873 93.8094 66.2265 93.3859 66.2265C92.9623 66.2265 92.6251 65.8873 92.6251 65.4766C92.6251 65.0658 92.9623 64.7267 93.3859 64.7267C93.8094 64.7267 94.1466 65.0658 94.1466 65.4766Z"
                                stroke="#EE2445" stroke-opacity="0.6" stroke-width="0.758084" />
                            <path
                                d="M2.50988 65.4766C2.50988 65.8873 2.17272 66.2265 1.74915 66.2265C1.32558 66.2265 0.988417 65.8873 0.988417 65.4766C0.988417 65.0658 1.32558 64.7267 1.74915 64.7267C2.17272 64.7267 2.50988 65.0658 2.50988 65.4766Z"
                                stroke="#EE2445" stroke-opacity="0.6" stroke-width="0.758084" />
                            <path
                                d="M77.6641 12.8238C78.4239 12.8238 78.7152 12.1011 78.7658 11.7397C78.7658 12.4874 79.551 12.7889 79.9436 12.8388C79.0318 12.8388 78.7785 13.6113 78.7658 13.9976C78.7658 13.1602 78.0313 12.8612 77.6641 12.8238Z"
                                stroke="#F4B125" stroke-width="0.758084" stroke-linejoin="round" />
                            <line x1="78.6483" y1="10.683" x2="78.6483" y2="10.3121" stroke="#F4B125" stroke-width="0.758084"
                                stroke-linecap="round" />
                            <line x1="78.6483" y1="16.3275" x2="78.6483" y2="15.2793" stroke="#F4B125" stroke-width="0.758084"
                                stroke-linecap="round" />
                            <path d="M80.625 12.897H81.9927" stroke="#F4B125" stroke-width="0.758084" stroke-linecap="round" />
                            <path d="M75.6094 12.897H76.4642" stroke="#F4B125" stroke-width="0.758084" stroke-linecap="round" />
                        </svg>
                        <div class="bpa-front-tmc__booking-id">
                            <div class="bpa-front-bi__label">'.$bpa_appointment_cancellation_confirmation.'</div>
                        </div>
                    </div>                    
                    <div class="bpa-front-tmc__summary-content">
                        <div class="bpa-front-tmc__sc-item">
                            <div class="bpa-front-sc-item__label">'.$bpa_cancel_booking_id_text.':</div>
                            <div class="bpa-front-sc-item__val">'.$bpa_appointment_booking_id.'</div>
                        </div>
                        <div class="bpa-front-tmc__sc-item">
                            <div class="bpa-front-sc-item__label">'.$bpa_cancel_service_text.':</div>
                            <div class="bpa-front-sc-item__val">'.$bpa_appointment_service_name.'</div>
                        </div>
                        <div class="bpa-front-tmc__sc-item">
                            <div class="bpa-front-sc-item__label">'.$bpa_cancel_date_time_text.':</div>
                            <div class="bpa-front-sc-item__val">'.$bpa_appointment_date_time.'</div>
                        </div>
                    </div>';                    
                    $content = apply_filters('bookingpress_add_cancel_appointment_extra_content',$content,$bkp_ap_id);
                    $content .="<el-button class='bpa-front-btn bpa-front-btn--primary' @click='bookingpress_cancel_appointment(bookingpress_appointment_id,bookingpress_cancel_token)' :disabled='is_cancel_button_disabled' :class='(is_cancel_appointment_loader == \"1\") ? \"bpa-front-btn--is-loader\" : \"\"'>";                
                        $content .= '<span class="bpa-btn__label">'.$bpa_cancel_button_text.'</span>
                                <div class="bpa-front-btn--loader__circles">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>                    
                        </el-button>                        
                        </div> 

                    </el-main>';
                } else {
                    $content .='
                    <div class="bpa-front-thankyou-module-container bpa-front-cancel-confirmation-container">
                    <div class="bpa-front-tmc__head">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" class="bpa-front-tmc__vector--confirmation" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M90.7002 85.4609C84.4375 93.0097 72.3956 90.9929 62.8277 93.3709C53.5335 95.6809 44.3866 102.22 35.2915 99.2322C26.2099 96.2487 22.8396 85.5958 16.7964 78.2483C10.8588 71.029 1.14803 65.6955 0.0978856 56.4422C-0.951171 47.1985 6.67152 39.5221 11.4957 31.5412C15.8676 24.3085 20.1527 17.1388 26.9344 12.0453C34.1982 6.58966 42.3994 1.52536 51.5153 1.48363C60.7 1.44158 68.4886 7.22002 76.4135 11.8188C84.8089 16.6906 95.7104 20.0478 99.0838 29.0876C102.453 38.117 94.606 47.2497 93.1897 56.7734C91.7484 66.465 96.9793 77.8922 90.7002 85.4609Z"
                                class="bpa-front-vc__bg" />
                            <path
                                d="M82.6133 69.7307C82.3433 70.1385 82.386 70.6258 82.2851 71.0748C80.968 76.9215 75.5745 81.4923 68.9549 81.3047C62.9363 81.13 57.6068 76.5791 56.5156 70.5647C56.1894 68.8291 56.2157 67.0455 56.5929 65.3202C56.9701 63.5949 57.6905 61.9631 58.7112 60.5219C59.7319 59.0808 61.0321 57.8596 62.5344 56.9311C64.0367 56.0027 65.7104 55.3859 67.4559 55.1175C74.6368 53.9809 81.444 59.0447 82.4911 66.3093C82.5082 66.4259 82.4286 66.5935 82.6133 66.6475V69.7307ZM63.219 73.5939C63.2276 73.7806 63.2905 73.9608 63.4 74.1123C63.5096 74.2637 63.661 74.3799 63.8357 74.4464C64.2903 74.6283 64.6541 74.4777 64.9723 74.1622C66.3932 72.7414 67.814 71.3419 69.2078 69.9126C69.492 69.617 69.6468 69.5772 69.958 69.8983C71.2026 71.1856 72.48 72.4402 73.7473 73.7062C73.8633 73.8323 73.9877 73.9505 74.1196 74.0599C74.3031 74.2167 74.5389 74.2986 74.7801 74.2894C75.0212 74.2802 75.2501 74.1805 75.4211 74.0102C75.5943 73.8436 75.6998 73.6188 75.7173 73.3791C75.7349 73.1394 75.6633 72.9017 75.5163 72.7116C75.399 72.5594 75.2698 72.4169 75.1298 72.2853C73.8511 71.0066 72.5723 69.7179 71.2936 68.4491C71.0449 68.2061 71.0222 68.0754 71.2865 67.8268C72.021 67.1348 72.7244 66.406 73.4404 65.6955L75.4296 63.7178C75.7223 63.4251 75.8473 63.0798 75.7138 62.6777C75.6621 62.5025 75.5615 62.3457 75.4238 62.2257C75.2862 62.1057 75.1172 62.0274 74.9366 62C74.5103 61.9176 74.2105 62.1321 73.9292 62.4134C72.6391 63.7092 71.3248 64.9837 70.0646 66.3065C69.6809 66.7086 69.4962 66.6319 69.1538 66.2838C67.8751 64.9738 66.5736 63.6908 65.2807 62.3978C65.1997 62.3168 65.1201 62.2344 65.0377 62.1563C64.5106 61.649 63.9635 61.6007 63.5245 62.0142C63.0855 62.4277 63.1267 63.0244 63.651 63.5771L63.732 63.6581C65.0661 64.9908 66.3804 66.3463 67.7472 67.6435C68.1848 68.0598 68.1209 68.2601 67.7245 68.6381C66.3647 69.9452 65.0491 71.2964 63.7106 72.6249C63.4378 72.8949 63.2119 73.1662 63.219 73.5939Z"
                                class="bpa-head__vector-item" />
                            <path
                                d="M46.9914 34.1763C54.4242 34.1763 61.8599 34.1716 69.2983 34.1621C69.8312 34.1621 69.942 34.3042 69.9406 34.8171C69.9216 40.6899 69.9216 46.5626 69.9406 52.4353C69.9406 52.9127 69.8212 53.0165 69.3523 53.0207C65.7787 53.0403 62.3274 54.3247 59.6103 56.6461C56.8933 58.9676 55.086 62.1762 54.509 65.703C54.2154 67.4168 54.24 69.1699 54.5815 70.8748C54.6738 71.3209 54.553 71.4133 54.1126 71.4133C45.7453 71.4005 37.3781 71.4133 29.0109 71.4005C26.0414 71.4005 24.0678 69.4113 24.0678 66.4489C24.0678 55.8894 24.0621 45.3303 24.0508 34.7716C24.0508 34.2189 24.2398 34.1735 24.6973 34.1735C32.1244 34.182 39.5558 34.1829 46.9914 34.1763ZM45.5976 49.1689H53.8824C54.7789 49.1689 55.6755 49.1816 56.5706 49.1589C56.6996 49.1597 56.8275 49.1347 56.9467 49.0855C57.0659 49.0363 57.1742 48.9638 57.265 48.8722C57.3559 48.7807 57.4277 48.6719 57.476 48.5523C57.5244 48.4327 57.5484 48.3047 57.5467 48.1757C57.5375 47.9256 57.436 47.6877 57.2617 47.508C57.0875 47.3283 56.853 47.2195 56.6033 47.2024C56.4328 47.1868 56.2609 47.1953 56.0889 47.1953H35.0536C34.8822 47.1889 34.7105 47.1918 34.5393 47.2039C34.4009 47.2136 34.2662 47.2528 34.1442 47.3188C34.0221 47.3847 33.9155 47.4759 33.8315 47.5863C33.7475 47.6967 33.6879 47.8237 33.6569 47.9589C33.6258 48.0941 33.6239 48.2344 33.6513 48.3704C33.738 48.9003 34.1273 49.1674 34.8476 49.1674C38.4309 49.1703 42.0143 49.1708 45.5976 49.1689ZM41.1419 58.3943H47.3097C48.2545 58.3943 48.7461 58.049 48.7461 57.3897C48.7461 56.7305 48.2659 56.3952 47.3054 56.3952H35.0281C34.838 56.3897 34.6479 56.4006 34.4597 56.4279C33.9724 56.5159 33.6783 56.8101 33.6413 57.3116C33.6044 57.8132 33.8332 58.1428 34.2992 58.3246C34.4993 58.3872 34.7096 58.4108 34.9187 58.3943H41.1419Z"
                                fill="#727E95" />
                            <path
                                d="M46.9156 32.214C39.4838 32.214 32.051 32.2177 24.6172 32.2253C24.1469 32.2253 24.0034 32.1259 24.0318 31.6428C24.1114 30.2561 23.9025 28.8565 24.1455 27.4783C24.3243 26.4557 24.8489 25.5255 25.6315 24.8434C26.4142 24.1614 27.4074 23.7689 28.4449 23.7316C30.4824 23.6748 32.5227 23.7174 34.5616 23.7004C34.867 23.7004 34.9466 23.7941 34.9409 24.0883C34.9239 24.9265 34.9296 25.7648 34.9409 26.6031C34.9409 27.3533 35.3331 27.8137 35.9355 27.8137C36.5379 27.8137 36.9301 27.3547 36.9301 26.6017C36.9386 25.8217 36.9599 25.0388 36.9215 24.2588C36.9002 23.8112 37.0295 23.6904 37.4799 23.6904C42.4528 23.7075 47.4257 23.7018 52.3986 23.7018C53.7896 23.7018 55.182 23.7132 56.573 23.6933C56.9154 23.6933 57.0362 23.7657 57.0234 24.1309C56.9935 24.9493 57.0077 25.7691 57.0148 26.5889C57.0219 27.3434 57.4027 27.8023 58.0094 27.808C58.6161 27.8137 59.004 27.3519 59.0168 26.606C59.0253 25.7861 59.0338 24.9663 59.0168 24.1479C59.0083 23.814 59.0722 23.6876 59.443 23.6919C61.3668 23.7132 63.2935 23.6805 65.2173 23.7075C67.9623 23.7458 69.9188 25.7847 69.906 28.5383C69.906 29.567 69.8762 30.5971 69.9174 31.6243C69.9358 32.1017 69.808 32.2154 69.3306 32.214C61.8618 32.2045 54.3901 32.2045 46.9156 32.214Z"
                                fill="#727E95" />
                            <path
                                d="M8.12945 32.8244L8.16195 32.9235H8.25458L8.17964 32.9774L8.21066 33.072L8.12945 33.0136L8.04823 33.072L8.07925 32.9774L8.00431 32.9235H8.09695L8.12945 32.8244Z"
                                stroke="#12D488" stroke-width="0.758084" />
                            <path
                                d="M98.6334 39.8249L98.6659 39.924H98.7585L98.6835 39.9779L98.7146 40.0725L98.6334 40.0141L98.5521 40.0725L98.5832 39.9779L98.5082 39.924H98.6009L98.6334 39.8249Z"
                                stroke="#12D488" stroke-width="0.758084" />
                            <path
                                d="M70.5904 92.4333L70.6229 92.5324H70.7155L70.6406 92.5863L70.6716 92.6809L70.5904 92.6225L70.5092 92.6809L70.5402 92.5863L70.4652 92.5324H70.5579L70.5904 92.4333Z"
                                stroke="#F5AE41" stroke-width="0.758084" />
                            <path
                                d="M47.4161 1.12892C47.4161 1.53965 47.079 1.8788 46.6554 1.8788C46.2318 1.8788 45.8947 1.53965 45.8947 1.12892C45.8947 0.718185 46.2318 0.379042 46.6554 0.379042C47.079 0.379042 47.4161 0.718185 47.4161 1.12892Z"
                                stroke="#EE2445" stroke-opacity="0.7" stroke-width="0.758084" />
                            <path
                                d="M94.1466 65.4766C94.1466 65.8873 93.8094 66.2265 93.3859 66.2265C92.9623 66.2265 92.6251 65.8873 92.6251 65.4766C92.6251 65.0658 92.9623 64.7267 93.3859 64.7267C93.8094 64.7267 94.1466 65.0658 94.1466 65.4766Z"
                                stroke="#EE2445" stroke-opacity="0.6" stroke-width="0.758084" />
                            <path
                                d="M2.50988 65.4766C2.50988 65.8873 2.17272 66.2265 1.74915 66.2265C1.32558 66.2265 0.988417 65.8873 0.988417 65.4766C0.988417 65.0658 1.32558 64.7267 1.74915 64.7267C2.17272 64.7267 2.50988 65.0658 2.50988 65.4766Z"
                                stroke="#EE2445" stroke-opacity="0.6" stroke-width="0.758084" />
                            <path
                                d="M77.6641 12.8238C78.4239 12.8238 78.7152 12.1011 78.7658 11.7397C78.7658 12.4874 79.551 12.7889 79.9436 12.8388C79.0318 12.8388 78.7785 13.6113 78.7658 13.9976C78.7658 13.1602 78.0313 12.8612 77.6641 12.8238Z"
                                stroke="#F4B125" stroke-width="0.758084" stroke-linejoin="round" />
                            <line x1="78.6483" y1="10.683" x2="78.6483" y2="10.3121" stroke="#F4B125" stroke-width="0.758084"
                                stroke-linecap="round" />
                            <line x1="78.6483" y1="16.3275" x2="78.6483" y2="15.2793" stroke="#F4B125" stroke-width="0.758084"
                                stroke-linecap="round" />
                            <path d="M80.625 12.897H81.9927" stroke="#F4B125" stroke-width="0.758084" stroke-linecap="round" />
                            <path d="M75.6094 12.897H76.4642" stroke="#F4B125" stroke-width="0.758084" stroke-linecap="round" />
                        </svg>
                        <div class="bpa-front-tmc__booking-id">
                            <div class="bpa-front-bi__label">'.$bpa_appointment_cancellation_confirmation.'</div>
                        </div>
                    </div>
                    <div class="bpa-front-refund-confirmation-content">
                    <div class="bpa-front-rcc__body">
                        <div class="bpa-front-rcc__empty-msg">'.$bpa_no_appointment_available_for_cancel.'</div>
                    </div>
                </div>';
                } 
                
                add_action(
                    'wp_footer',
                    function () use ( &$bookingpress_uniq_id,$bkp_ap_id,$bookingpress_cancel_token ) {
                        global $bookingpress_global_options ,$BookingPress;
                        $requested_module                = 'cancellation_confirmation';

                        ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', function() {
                                <?php do_action('bookingpress_' . $requested_module . '_dynamic_helper_vars'); ?>
                                var app = new Vue({
                                        el: '#bookingpress_appointment_cancellation_form_<?php echo esc_html($bookingpress_uniq_id); ?>',
                                        directives: { <?php do_action('bookingpress_' . $requested_module . '_dynamic_directives'); ?> },
                                        components: { <?php do_action('bookingpress_' . $requested_module . '_dynamic_components'); ?> },
                                    data() {
                                        var bookingpress_return_data = <?php do_action('bookingpress_' . $requested_module . '_dynamic_data_fields'); ?>;
                                        bookingpress_return_data['bookingpress_uniq_id'] = '<?php echo esc_html($bookingpress_uniq_id); ?>';
                                        bookingpress_return_data['bookingpress_appointment_id'] = '<?php echo intval($bkp_ap_id); ?>';
                                        bookingpress_return_data['bookingpress_cancel_token'] = '<?php echo esc_html($bookingpress_cancel_token); ?>';
                                        return bookingpress_return_data;
                                    },
                                    filters:{

                                    },
                                    beforeCreate(){
                                        this.is_front_appointment_empty_loader = '1';
                                    },
                                    created(){
                                    },
                                    mounted() {
                                    <?php do_action('bookingpress_' . $requested_module . '_dynamic_on_load_methods'); ?>
                                    },
                                    methods: {
                                    <?php do_action('bookingpress_' . $requested_module . '_dynamic_vue_methods'); ?>
                                    },
                                });
                            });
                        </script>
                        <?php
                    },
                    100
                );
            }
            return do_shortcode($content);
        }       
        
        /**
         * Get appointment details for thank you page calendar
         *
         * @return void
         */
        function bookingpress_get_appointment_details_for_calendar_func( $appointment_id, $return = false ){
            global $wpdb, $tbl_bookingpress_entries, $tbl_bookingpress_appointment_bookings, $BookingPress;
            $response              = array();

            $response['variant']    = 'error';
            $response['title']      = 'Error';
            $response['msg']        = 'Something went wrong....';
            $response['google_calendar_link'] = '';
            $response['yahoo_calendar_link'] = '';

            $bookingpress_appointment_id = $appointment_id;
            
            if(!empty($bookingpress_appointment_id)){
                $appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d OR bookingpress_entry_id = %d", $bookingpress_appointment_id, $bookingpress_appointment_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm

                if(!empty($appointment_data)){
                    $service_id = intval( $appointment_data['bookingpress_service_id'] );

                    $bookingpress_start_time = $start_time =  sanitize_text_field( $appointment_data['bookingpress_appointment_time'] );
                    $bookingpress_end_time = $end_time = sanitize_text_field( $appointment_data['bookingpress_appointment_end_time'] );

                    $bookingpress_appointment_date_temp = $appointment_data['bookingpress_appointment_date'];
                    if ($bookingpress_end_time === '24:00:00') {
                        $bookingpress_appointment_date_temp = date('Y-m-d', strtotime($appointment_data['bookingpress_appointment_date'] . ' +1 day'));
                        $bookingpress_end_time = '00:00:00';
                    }
                    
                    $service_duration = sanitize_text_field( $appointment_data['bookingpress_service_duration_val'] );

                    $service_duration_unit = sanitize_text_field( $appointment_data['bookingpress_service_duration_unit'] );

                    $bookingpress_start_time_for_yahoo = date( 'Ymd', strtotime( $appointment_data['bookingpress_appointment_date'] ) ) . 'T' . date( 'His', strtotime( $bookingpress_start_time ) ) . 'Z';

                    $bookingpress_start_time = $this->bookingpress_convert_date_time_to_utc( $appointment_data['bookingpress_appointment_date'], $bookingpress_start_time );
                    $bookingpress_end_time = $this->bookingpress_convert_date_time_to_utc( $bookingpress_appointment_date_temp , $bookingpress_end_time );         
                    
                    $bookingpress_service_name = ! empty( $appointment_data['bookingpress_service_name'] ) ? stripslashes_deep($appointment_data['bookingpress_service_name']) : '';
                                         
                    if ( 'd' != $service_duration_unit ) {
                        $bookingpress_tmp_start_time = new DateTime($start_time);
                        $bookingpress_tmp_end_time = new DateTime($end_time);
                        $booking_date_interval = $bookingpress_tmp_start_time->diff($bookingpress_tmp_end_time);
                        $service_duration_time = ($booking_date_interval->h * 60) + ($booking_date_interval->i);
                        $service_duration_time = '00'.$service_duration_time;    
                    } else {
                        $service_duration_time = $service_duration . '00';
                        $bookingpress_start_time = date('Ymd', strtotime( $appointment_data['bookingpress_appointment_date'] ) );
                        $bookingpress_end_time = date('Ymd', strtotime( $appointment_data['bookingpress_appointment_date'] . '+' . $service_duration.' days' ) );
                    }

                    $response['variant'] = 'success';
                    $response['title'] = 'Success';
                    $response['msg'] = 'Links generated successfully';
                    $response['google_calendar_link'] = urlencode($bookingpress_service_name)."&dates=".esc_html($bookingpress_start_time)."/".esc_html($bookingpress_end_time);
                    $response['yahoo_calendar_link'] = urlencode($bookingpress_service_name)."&st=".esc_html($bookingpress_start_time_for_yahoo)."&dur=".esc_html($service_duration_time);
                }
            }

            return $response;
        }


        /**
         * Background function for disable future dates
         *
         * @return void
         */
        function bookingpress_get_whole_day_appointments_func($return_data = false) {
            // phpcs:ignore WordPress.Security.NonceVerification
            global $BookingPress;

            $month_check = !empty( $_POST['next_month'] ) ? sanitize_text_field( $_POST['next_month'] ) : date('m', current_time('timestamp') ); // phpcs:ignore WordPress.Security.NonceVerification
            $year_check = !empty( $_POST['next_year'] ) ? sanitize_text_field( $_POST['next_year'] ) : date( 'Y', current_time('timestamp') ); // phpcs:ignore WordPress.Security.NonceVerification
            if( !empty( $_POST['appointment_data_obj'] ) && !is_array( $_POST['appointment_data_obj'] ) ){ // phpcs:ignore WordPress.Security.NonceVerification
                $_POST['appointment_data_obj'] = json_decode( stripslashes_deep( $_POST['appointment_data_obj'] ), true ); //phpcs:ignore
                $_POST['appointment_data_obj'] =  !empty($_POST['appointment_data_obj']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            }

            $bookingpress_disabled_dates = !empty($_POST['days_off_disabled_dates']) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), explode(',',$_POST['days_off_disabled_dates']) ) : array(); // phpcs:ignore
            $daysoff_dates = $bookingpress_disabled_dates;

            $first_date_of_month = $year_check . '-' . $month_check . '-01';
            $last_date_of_month = date('Y-m-t', strtotime( $first_date_of_month ) );

            $start_date = new DateTime( $first_date_of_month );
            $end_date = new DateTime( date('Y-m-d', strtotime($last_date_of_month .'+1 day') ) );

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod( $start_date, $interval, $end_date );

            $extra_disabled_dates = array();
            $extra_disabled_dates_t = array();
            foreach( $period as $dt ){
                $current_date = $dt->format("Y-m-d H:i:s");
                $date_t = date('c', strtotime( $current_date ) );
                if( !in_array( $date_t, $daysoff_dates ) ){
                    $current_selected_date = $dt->format( 'Y-m-d' );
                    $is_time_slot_available = $this->bookingpress_retrieve_timeslots( $current_selected_date, true, true );
                    if( false == $is_time_slot_available ){
                        $daysoff_dates[] = $date_t;
                        $extra_disabled_dates_t[] = $date_t;
                        $extra_disabled_dates[] = date('Y-m-d', strtotime( $date_t) );
                    }
                }
            }

            $max_available_month = !empty( $_POST['max_available_month'] ) ? sanitize_text_field( $_POST['max_available_month'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
            $response['prevent_next_month_check']  = false;
            if( !empty( $max_available_month ) && $max_available_month == $month_check && $_POST['max_available_year'] < date('Y', current_time('timestamp') ) ){ // phpcs:ignore
                $response['prevent_next_month_check']  = true;
            }

            $bookingpress_selected_service = !empty($_REQUEST['selected_service']) ? intval($_REQUEST['selected_service']) : '';

            $bookingpress_appointment_data = !empty($_POST['appointment_data_obj']) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            
            if(empty($bookingpress_selected_service)){
                $bookingpress_selected_service = $bookingpress_appointment_data['selected_service'];
            }

            $before_daysoff_dates = $daysoff_dates;

            $daysoff_dates = apply_filters( 'bookingpress_modify_disable_dates_with_staffmember', $daysoff_dates, $bookingpress_selected_service, $first_date_of_month );

            $after_daysoff_dates = $daysoff_dates;

            foreach( $extra_disabled_dates_t as $key => $v ){
                if( !in_array( $v, $daysoff_dates ) ){
                    unset( $extra_disabled_dates[ $key ] );
                }
            }

            $extra_disabled_dates = array_values( $extra_disabled_dates );

            $response[ 'days_off_disabled_dates' ] = implode( ',', $daysoff_dates );
            $response['days_off_disabled_dates_string']  =  implode(',',$extra_disabled_dates );

            $response['next_month'] = date( 'm', strtotime( $first_date_of_month . '+1 month') );
            $response['next_year'] =  date( 'Y', strtotime( $first_date_of_month . '+1 month') );
            
            $next_month_check = date('Y-m', strtotime( $response['next_year'] .'-'. $response['next_month'] .'-01' ) );

            $max_available_year = !empty( $_POST['max_available_year'] ) ? sanitize_text_field( $_POST['max_available_year'] ) : ''; // phpcs:ignore
            $max_month_check = date('Y-m', strtotime( $max_available_year . '-' . $max_available_month . '-01' ) ); // phpcs:ignore
            
            if( strtotime( $max_month_check ) < strtotime( $next_month_check ) ){
                $response['prevent_next_month_check'] = true;
            }
            
            $response = array_merge( $_POST, $response ); // phpcs:ignore
            if($return_data == true){
                return $response;                    
            }
            echo json_encode( $response );

            die;
        }

        function bookingpress_get_disable_date_func(){

            global $BookingPress;

            $use_legacy = false;
            if( $BookingPress->bpa_is_pro_active() && version_compare( $BookingPress->bpa_pro_plugin_version(), '3.0', '<') ){
                $use_legacy = true;
            }
            
            if( !$use_legacy && is_plugin_active( 'bookingpress-custom-service-duration/bookingpress-custom-service-duration.php') ){
                global $bookingpress_custom_service_duration_version;

                if( version_compare( $bookingpress_custom_service_duration_version, '1.7', '<') ){
                    $use_legacy = true;
                }
            }

            if( !$use_legacy && is_plugin_active( 'bookingpress-waiting-list/bookingpress-waiting-list.php' ) ){
                global $bookingpress_waiting_list_version;

                if( version_compare( $bookingpress_waiting_list_version, '1.2', '<') ){
                    $use_legacy = true;
                }
            }

            if( true == $use_legacy ){
                $this->bookingpress_get_disable_date_func_legacy();
            } else {
                $this->bookingpress_get_disable_date_func_optimized();
            }

        }

        function bookingpress_get_disable_date_func_optimized($return_data = false){

            $start_ms = microtime( true );
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');

            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }      
                wp_send_json($response);
                die();
            }

            $response['variant']    = 'error';
            $response['title']      = 'Error';
            $response['msg']        = 'Something went wrong....';

            //$consider_selected_date = false;

            if( !empty( $_POST['appointment_data_obj'] ) && !is_array( $_POST['appointment_data_obj'] ) ){
                $_POST['appointment_data_obj'] = json_decode( stripslashes_deep( $_POST['appointment_data_obj'] ), true ); //phpcs:ignore
                $_REQUEST['appointment_data_obj'] = $_POST['appointment_data_obj'] =  !empty($_POST['appointment_data_obj']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            }

            $bookingpress_appointment_data = !empty($_POST['appointment_data_obj']) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            $bookingpress_selected_date = !empty($_REQUEST['selected_date']) ? sanitize_text_field($_REQUEST['selected_date']) : '';

            $selected_service_duration_unit = $bookingpress_appointment_data['selected_service_duration_unit'];
            $selected_service_duration = $bookingpress_appointment_data['selected_service_duration'];

            $selected_service_duration_in_min = '';
            if( 'm' == $selected_service_duration_unit ){
                $selected_service_duration_in_min = $selected_service_duration;
            } else {
                if( 'h' == $selected_service_duration_unit ){
                    $selected_service_duration_in_min = ( MINUTE_IN_SECONDS * $selected_service_duration );
                }
            }

            if(!empty($bookingpress_selected_date)){
                $bookingpress_selected_date = date('Y-m-d', strtotime($bookingpress_selected_date));
            }
            
            if( "NaN-NaN-NaN" == $bookingpress_selected_date || '1970-01-01' == $bookingpress_selected_date || !preg_match('/(\d{4}\-\d{2}\-\d{2})/', $bookingpress_selected_date ) ){
                $bookingpress_selected_date = date('Y-m-d', current_time('timestamp') );
            }
            
            $bookingpress_selected_service= !empty($_REQUEST['selected_service']) ? intval($_REQUEST['selected_service']) : '';
        
            if(empty($bookingpress_selected_service)){
                $bookingpress_selected_service = $bookingpress_appointment_data['selected_service'];
            }
        
            if(empty($bookingpress_appointment_data['selected_service_duration_unit']) || empty($bookingpress_appointment_data['selected_service_duration']) ){
                $bookingpress_service_data = $BookingPress->get_service_by_id($bookingpress_selected_service);
                if(!empty($bookingpress_service_data['bookingpress_service_duration_unit'])){
                    $bookingpress_appointment_data['selected_service_duration_unit'] = $bookingpress_service_data['bookingpress_service_duration_unit'];
                    $bookingpress_appointment_data['selected_service_duration'] = intval($bookingpress_service_data['bookingpress_service_duration_val']);
                }
            }

            /** get maximum period available from booking */
            $get_period_available_for_booking = $BookingPress->bookingpress_get_settings('period_available_for_booking', 'general_setting');
            if( empty( $get_period_available_for_booking ) || !$BookingPress->bpa_is_pro_active() ){
                $get_period_available_for_booking = 365;
            }

            $posted_disabled_dates = !empty( $_POST['disabled_dates'] ) ? json_decode( stripslashes_deep( $_POST['disabled_dates'] ), true ) : array(); // phpcs:ignore

            if( !empty( $posted_disabled_dates ) ){
                $posted_disabled_dates = array_filter( $posted_disabled_dates );                
            }

            $max_service_capacity = 1;
            $max_service_capacity = apply_filters( 'bookingpress_retrieve_capacity', $max_service_capacity, $bookingpress_selected_service );

            if( !empty( $bookingpress_selected_service ) ){

                $response['prevent_next_month_check'] = false;

                $bookingpress_start_date = date('Y-m-d', current_time('timestamp') );

                $bookingpress_start_date_with_time = date('Y-m-d H:i:s', current_time( 'timestamp') );

                $bookingpress_start_date_initial = $bookingpress_start_date;

                /** apply filter to modify start date. in case of Minimum Time Required Booking */
                $bookingpress_start_date = apply_filters( 'bookingpress_modify_disable_date_start_date', $bookingpress_start_date, $bookingpress_selected_service, $bookingpress_start_date_with_time );

                $bookingpress_start_date_without_time = date('Y-m-d', strtotime( $bookingpress_start_date ) );

                $exclude_dates = array();
                if( $bookingpress_start_date_initial != $bookingpress_start_date ){
                    //$start_date_with_time = date('Y-m-d H:i:s', strtotime( $bookingpress_start_date_initial ) );

                    $timestamp_init_date = strtotime( $bookingpress_start_date_with_time );
                    $timestamp_start_date = strtotime( $bookingpress_start_date );

                    $time_diff_minuts =  round(abs($timestamp_start_date - $timestamp_init_date) / 60,2);

                    if( 1440 >= $time_diff_minuts ){
                        $exclude_dates[] = date('Y-m-d', strtotime( $bookingpress_start_date ) );
                    }
                    
                }


                /** Modify get available time of booking if the service expiration time is set */
                $get_period_available_for_booking = apply_filters( 'bookingpress_modify_max_available_time_for_booking', $get_period_available_for_booking, $bookingpress_start_date, $bookingpress_selected_service );

                /* if( true == $consider_selected_date && !empty( $bpa_selected_date ) ){
                    $bookingpress_start_date = $bpa_selected_date;
                } */

                $bookingpress_temp_end_date = date('Y-m-d', strtotime('last day of this month', strtotime( $bookingpress_start_date )));

                $bookingpress_end_date = date('Y-m-d', strtotime( '+' . $get_period_available_for_booking . ' days') );

                $next_month = date( 'm', strtotime( $bookingpress_temp_end_date . '+1 day' ) );
                $next_year = date( 'Y', strtotime( $bookingpress_temp_end_date . '+1 day' ) );
                
                $bookingpress_selected_staffmember_id = !empty($bookingpress_appointment_data['bookingpress_selected_staff_member_details']['selected_staff_member_id']) ? intval($bookingpress_appointment_data['bookingpress_selected_staff_member_details']['selected_staff_member_id']) : '';

                /** Get the default days off in the above limit */
                $bpa_retrieves_default_disabled_dates = $BookingPress->bookingpress_retrieve_off_days( $bookingpress_start_date_without_time, ( $get_period_available_for_booking + 1 ), $bookingpress_selected_service, $selected_service_duration_in_min, $bookingpress_selected_staffmember_id );

                $bpa_retrieves_default_disabled_dates = array_merge( $bpa_retrieves_default_disabled_dates, $posted_disabled_dates );

                /** loop through each days until the limit has been reached
                 * for lite - it'll check for the next 365 days
                 * for pro - it'll check up to the X number of days defined in the settings
                 */
                $bpa_begin_date = new DateTime( $bookingpress_selected_date );
                $bpa_end_date = new DateTime( date('Y-m-d', strtotime($bookingpress_end_date . '+1 day')) );
                
                $bpa_interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($bpa_begin_date, $bpa_interval, $bpa_end_date);

                $bookingpress_selected_date = $bookingpress_end_date;
                $front_timings_data = array();

                $count = 0;
                $stop_date = '';

                

                if( !empty( $bookingpress_appointment_data['appointment_update_id'] ) ){
                    $sel_date = !empty($_REQUEST['selected_date']) ? sanitize_text_field($_REQUEST['selected_date']) : '';
                    $front_timings_data = $this->bookingpress_retrieve_timeslots( $sel_date, true );
                    $bookingpress_selected_date = $sel_date;
                } else {   
                    foreach( $period as $dt ){
                        $bpa_check_date = $dt->format('Y-m-d');

                        if( in_array( $dt->format('Y-m-d H:i:s'), $bpa_retrieves_default_disabled_dates ) && !in_array( $dt->format('Y-m-d'), $exclude_dates )){
                            continue;
                        }

                        /** Stop the loop if the time slot is available & date is equals to the last day of the available date month */
                        if( !empty( $stop_date ) && $bpa_check_date > date( 'Y-m-d', strtotime( 'last day of this month', strtotime( $stop_date) ) ) ){
                            $last_date = date('Y-m-d', strtotime( 'last day of this month', strtotime( $stop_date ) ) );
                            $next_month = date('m', strtotime( $last_date . '+1 day') );
                            $next_year = date('Y', strtotime( $last_date . '+1 day') );
                            break;
                        }

                        $bookingpress_time_slots = $this->bookingpress_retrieve_timeslots( $bpa_check_date, true, true, false );
                        
                        if( empty( $bookingpress_time_slots ) ){
                            $bpa_retrieves_default_disabled_dates[] = date('Y-m-d H:i:s', strtotime( $bpa_check_date ) );
                        } else {
                            if( $count < 1 ){
                                $date1 = new DateTime( $bookingpress_start_date );
                                $date2 = new DateTime( $bpa_check_date );
                                $interval = $date1->diff($date2);
                                $interval_in_days = $interval->days;
                                if( $interval_in_days < $get_period_available_for_booking ){
                                    $bookingpress_selected_date = $bpa_check_date;
                                    /** Check if the selected date is greater than end date in respect to service expiration */
                                    $front_timings_data = $this->bookingpress_retrieve_timeslots( $bpa_check_date, true );

                                    $stop_date = $bpa_check_date;
                                    $count++;
                                }
                            }
                        }
                    }
                }

                $bookingpress_disable_date = array();
                foreach( $bpa_retrieves_default_disabled_dates as $dis_key => $disabled_dates ){
                    $dis_date = date('Y-m-d', strtotime( $disabled_dates) );
                    if( !empty( $exclude_dates ) && in_array( $dis_date, $exclude_dates ) ){
                        unset( $bpa_retrieves_default_disabled_dates[ $dis_key ] );
                        continue;
                    }

                    $bookingpress_disable_date[] = date('c', strtotime( $disabled_dates ) );
                }

                $bpa_retrieves_default_disabled_dates = array_values( $bpa_retrieves_default_disabled_dates );

                $attributes = array();

                $response['prevent_next_month_check'] = false;
                if( !empty( $get_period_available_for_booking ) ){           
                    $bookingpress_current_date = date('Y-m-d', current_time('timestamp') );
                    $max_available_date = date('Y-m-d', strtotime( $bookingpress_current_date . '+' . $get_period_available_for_booking . ' days') );
                    $response['max_available_date'] = $max_available_date;
                    $response['max_available_month'] = date('m', strtotime( $max_available_date ) );
                    $response['max_available_year'] = date('Y', strtotime( $max_available_date ) );
                    if( isset($response['selected_date']) && $max_available_date < $response['selected_date'] ){
                        $response['front_timings'] = array();
                        $response['next_month'] = $next_month;
                        if($return_data){
                            return $response;
                        }
                        wp_send_json( $response );
                        die;
                    }
                }
                
                if( !empty( $response['max_available_month'] ) && $next_month > $response['max_available_month'] && $response['max_available_year'] < date('Y', current_time('timestamp') ) ){
                    $response['prevent_next_month_check'] = true;
                }

                $response['variant']    = 'success';
                $response['title']      = 'Success';
                $response['msg']        = 'Data reterive successfully';                            
                $response['days_off_disabled_dates']  =  implode(',',$bookingpress_disable_date );
                $response['days_off_disabled_dates_string']  =  implode(',',$bpa_retrieves_default_disabled_dates );
                $response['selected_date']  = date('Y-m-d', strtotime($bookingpress_selected_date));
                $response['next_month'] = $next_month;
                $response['vcal_attributes'] = $attributes;
                $response['max_capacity_capacity'] = $max_service_capacity;
                $response['front_timings'] = $front_timings_data;
                $response['next_year'] = $next_year;
                $response['msg']        = 'Data reterive successfully';
            }

            $end_ms = microtime( true );
            $response['time_taken'] = ( $end_ms - $start_ms ) . ' seconds';

            if($return_data){
                return $response;
            }
            wp_send_json($response);
            exit;
        }
        
        /**
         * Get default disable dates
         *
         * @return void
         */
        function bookingpress_get_disable_date_func_legacy( $bpa_selected_date = '', $consider_selected_date = false, $counter = 1, $bpa_total_booked_appointment = array(), $single_disable_date = array()) {
            
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs;            
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');

            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                $response['redirect_url'] = '';
                wp_send_json($response);
                die();
            }

            $response['variant']    = 'error';
            $response['title']      = 'Error';
            $response['msg']        = 'Something went wrong....';

            
            if( !empty( $_POST['appointment_data_obj'] ) && !is_array( $_POST['appointment_data_obj'] ) ){
               $_POST['appointment_data_obj'] = json_decode( stripslashes_deep( $_POST['appointment_data_obj'] ), true ); //phpcs:ignore
               $_REQUEST['appointment_data_obj'] = $_POST['appointment_data_obj'] =  !empty($_POST['appointment_data_obj']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            }
            
            $bookingpress_appointment_data = !empty($_POST['appointment_data_obj']) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            $bookingpress_selected_date = !empty($_REQUEST['selected_date']) ? sanitize_text_field($_REQUEST['selected_date']) : '';
            
            if(!empty($bookingpress_selected_date)){
                $bookingpress_selected_date = date('Y-m-d', strtotime($bookingpress_selected_date));
            }
            
            if( "NaN-NaN-NaN" == $bookingpress_selected_date || '1970-01-01' == $bookingpress_selected_date || !preg_match('/(\d{4}\-\d{2}\-\d{2})/', $bookingpress_selected_date ) ){
                $bookingpress_selected_date = date('Y-m-d', current_time('timestamp') );
            }
            
            $bookingpress_selected_service= !empty($_REQUEST['selected_service']) ? intval($_REQUEST['selected_service']) : '';

            if(empty($bookingpress_selected_service)){
                $bookingpress_selected_service = $bookingpress_appointment_data['selected_service'];
            }

            if(empty($bookingpress_appointment_data['selected_service_duration_unit']) || empty($bookingpress_appointment_data['selected_service_duration']) ){
                $bookingpress_service_data = $BookingPress->get_service_by_id($bookingpress_selected_service);
                if(!empty($bookingpress_service_data['bookingpress_service_duration_unit'])){
                    $bookingpress_appointment_data['selected_service_duration_unit'] = $bookingpress_service_data['bookingpress_service_duration_unit'];
                    $bookingpress_appointment_data['selected_service_duration'] = intval($bookingpress_service_data['bookingpress_service_duration_val']);
                }
            }

            if(empty($bookingpress_selected_date)){
                $bookingpress_selected_date = !empty( $bookingpress_appointment_data['selected_date'] ) ? $bookingpress_appointment_data['selected_date'] : date('Y-m-d', current_time('timestamp') );
            }
            if( true == $consider_selected_date && !empty( $bpa_selected_date ) ){
                $bookingpress_selected_date = $bpa_selected_date;
            }

            if( "NaN-NaN-NaN" == $bookingpress_selected_date || '1970-01-01' == $bookingpress_selected_date || !preg_match('/(\d{4}\-\d{2}\-\d{2})/', $bookingpress_selected_date ) ){
                $bookingpress_selected_date = date('Y-m-d', current_time('timestamp') );
            }

            $bookingpress_selected_staffmember_id = !empty($bookingpress_appointment_data['bookingpress_selected_staff_member_details']['selected_staff_member_id']) ? intval($bookingpress_appointment_data['bookingpress_selected_staff_member_details']['selected_staff_member_id']) : '';
            

            $is_multiple_day_event = false;
            if( !empty( $bookingpress_appointment_data['selected_service_duration_unit'] ) && 'd' == $bookingpress_appointment_data['selected_service_duration_unit'] ){                
                $is_multiple_day_event = true;
                $multiple_day_response = array();
                $multiple_day_response = apply_filters( 'bookingpress_get_multiple_days_disable_dates', $multiple_day_response, $bookingpress_selected_date, $bookingpress_selected_service, $bookingpress_appointment_data );
                if( !empty( $multiple_day_response ) ){
                    echo wp_json_encode( $multiple_day_response );
                    die;
                }
            }

            if(!empty($bookingpress_selected_service)) {
                $bookingpress_disable_date = $BookingPress->bookingpress_get_default_dayoff_dates('','',$bookingpress_selected_service,$bookingpress_selected_staffmember_id);
                
                $bookingpress_disable_date = apply_filters('bookingpress_modify_disable_dates', $bookingpress_disable_date, $bookingpress_selected_service, $bookingpress_selected_date, $bookingpress_appointment_data);
                
                $bookingpress_start_date = date('Y-m-d', current_time('timestamp'));
                if( true == $consider_selected_date && !empty( $bpa_selected_date ) ){
                    $bookingpress_start_date = $bpa_selected_date;
                }
                $bookingpress_end_date = date('Y-m-d', strtotime('last day of this month', strtotime( $bookingpress_start_date )));
                
                $next_month = date( 'm', strtotime( $bookingpress_end_date . '+1 day' ) );
                $next_year = date( 'Y', strtotime( $bookingpress_end_date . '+1 day' ) );
                
                $bookingpress_total_booked_appointment_where_clause = '';
                $bookingpress_total_booked_appointment_where_clause = apply_filters( 'bookingpress_total_booked_appointment_where_clause', $bookingpress_total_booked_appointment_where_clause );

                $max_service_capacity = 1;
                $max_service_capacity = apply_filters( 'bookingpress_retrieve_capacity', $max_service_capacity, $bookingpress_selected_service );

                $bpa_begin_date = new DateTime( $bookingpress_start_date );
                $bpa_end_date = new DateTime( date('Y-m-d', strtotime($bookingpress_end_date . '+1 day')) );
                
                $bpa_interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($bpa_begin_date, $bpa_interval, $bpa_end_date);

                foreach ($period as $dt) {
                    $bpa_check_date = $dt->format('Y-m-d');
                    
                    $bpa_check_date_formatted = $dt->format( 'c' );
                    if( !empty( $bookingpress_disable_date) && in_array( $bpa_check_date_formatted, $bookingpress_disable_date ) ){
                        continue;
                    }

                    $bookingpress_total_appointment = $wpdb->get_results($wpdb->prepare("SELECT bookingpress_appointment_date,bookingpress_service_duration_val,bookingpress_service_duration_unit FROM " . $tbl_bookingpress_appointment_bookings . " WHERE (bookingpress_appointment_status = %s OR bookingpress_appointment_status = %s) AND bookingpress_service_id= %d AND bookingpress_appointment_date = %s ".$bookingpress_total_booked_appointment_where_clause." GROUP BY bookingpress_appointment_date",'1','2',$bookingpress_selected_service,$bpa_check_date), ARRAY_A); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                    $bookingpress_total_appointment = apply_filters( 'bookingpress_modify_total_booked_appointment', $bookingpress_total_appointment, $bookingpress_appointment_data);

                    /** reputelog - check for this loop as it's adding the current selected date to disabled time slots if an appointment is booked in the selected date */
                    if( !empty( $bpa_total_booked_appointment ) ){
                        $bookingpress_total_appointment = array_merge( $bookingpress_total_appointment, $bpa_total_booked_appointment );
                    }

                    $multiple_days_event = array();
                    
                    if( count( $bookingpress_total_appointment ) > 0 ){
                        foreach($bookingpress_total_appointment as $key  => $value) {
                            if(!empty($value['bookingpress_appointment_date'])){
                                $bookingpress_appointment_date = !empty($value['bookingpress_appointment_date']) ? $value['bookingpress_appointment_date'] : '';
                                //$bookingpress_time_slot_data = $BookingPress->bookingpress_get_service_available_time($bookingpress_selected_service,$bookingpress_appointment_date );
                                $bookingpress_time_slots = $this->bookingpress_check_booked_appointments( $bookingpress_appointment_date );
                                
                                $bookingpress_time_slot_data = array_merge(
                                    $bookingpress_time_slots['morning_time'],
                                    $bookingpress_time_slots['afternoon_time'],
                                    $bookingpress_time_slots['evening_time'],
                                    $bookingpress_time_slots['night_time']
                                );
                                
                                if(!empty($bookingpress_time_slot_data)) {
                                    $is_booked = 1;   
                                    foreach($bookingpress_time_slot_data as $key2 => $value2) {                            
                                        if( ( isset($value2['is_booked']) && $value2['is_booked'] == 0 ) || empty( $value2['is_booked'] ) ){
                                            if( isset( $value2['max_capacity'] ) && isset( $value2['total_booked'] ) && $value2['total_booked'] >= $value2['max_total_capacity'] ) {
                                                /** Do nothing */
                                            } else {
                                                $is_booked = 0;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    $bookingpress_allow_to_disable_booked_date = apply_filters('bookingpress_allow_to_disable_booked_date',true,$bookingpress_selected_service);

                                    if($is_booked == 1 && $bookingpress_allow_to_disable_booked_date) {
                                        $bookingpress_disable_date[] = date('c', strtotime( $bookingpress_appointment_date));
                                    }
                                } else {
                                    if( $is_multiple_day_event ){
                                        $service_duration_val = $value['bookingpress_service_duration_val'];
                                        if( empty( $multiple_days_event[ date('Y-m-d', strtotime( $bookingpress_appointment_date)) ] ) ){
                                            $multiple_days_event[ date('Y-m-d', strtotime( $bookingpress_appointment_date)) ] = 1;
                                        } else {
                                            $multiple_days_event[ date('Y-m-d', strtotime( $bookingpress_appointment_date)) ]++;
                                        }
                                        for( $d = 1; $d < $service_duration_val; $d++ ){
                                            if( empty( $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '+' . $d . ' days' )) ] ) ){
                                                $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '+' . $d . ' days' )) ] = 1;
                                            } else {
                                                $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '+' . $d . ' days' )) ]++;
                                            }
                                        }
                                        
                                        for( $dm = $service_duration_val - 1; $dm > 0; $dm-- ){
                                            if( empty( $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '-' . $dm . ' days' )) ] ) ){
                                                $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '-' . $dm . ' days' )) ] = 1;
                                            } else {
                                                $multiple_days_event[ date( 'Y-m-d', strtotime( $bookingpress_appointment_date . '-' . $dm . ' days' )) ]++;
                                            }
                                        }
                                    } else {
                                        $bookingpress_disable_date[] = date('Y-m-d', strtotime( $bookingpress_appointment_date));
                                    }
                                }
                            }
                        };
                    } else {
                        if( !$is_multiple_day_event ){

                            $booked_time_slots = apply_filters( 'bookingpress_check_available_timeslot_manual_block', true, $bpa_check_date );

                            if( false == $booked_time_slots ){
                                $bookingpress_disable_date[] = date('Y-m-d', strtotime($bpa_check_date) );
                            }
                        }
                    }
                }

                $attributes = array();
                if( !empty( $multiple_days_event ) ){
                    $bookingpress_slot_left_text = $BookingPress->bookingpress_get_customize_settings('slot_left_text','booking_form');
                    $bookingpress_slot_left_text = !empty($bookingpress_slot_left_text) ? stripslashes_deep($bookingpress_slot_left_text) : esc_html__('Slots left', 'bookingpress-appointment-booking');
                    foreach( $multiple_days_event as $md_date => $md_cap ){
                        
                        if( $md_cap >= $max_service_capacity ){
                            $bookingpress_disable_date[] = $md_date;
                        }
                        $remaining_capacity = ( $max_service_capacity - $md_cap );
                        $attributes[ $md_date ] = ( ($remaining_capacity < 0 ) ? 0 : $remaining_capacity )  .' '. $bookingpress_slot_left_text;
                    }
                }
                
                $bookingpress_disable_date = apply_filters( 'bookingpress_modify_disable_dates_with_staffmember', $bookingpress_disable_date, $bookingpress_selected_service);
                $bookingpress_selected_date = $BookingPress->bookingpress_select_date_before_load($bookingpress_selected_date,$bookingpress_disable_date);
                
                $bookingpress_disable_date = array_unique( $bookingpress_disable_date );

                if( !empty( $single_disable_date ) ){
                    $bookingpress_disable_date = array_merge( $bookingpress_disable_date, $single_disable_date );
                }

                $bpa_disable_dates = array();
                foreach( $bookingpress_disable_date as $disable_date ){
                    $bpa_disable_dates[] = date('Y-m-d H:i:s', strtotime( $disable_date ) );
                }
                
                $response['variant']    = 'success';
                $response['title']      = 'Success';
                $response['msg']        = 'Data reterive successfully';                            
                $response['days_off_disabled_dates']  =  implode(',',$bookingpress_disable_date );
                $response['days_off_disabled_dates_string']  =  implode(',',$bpa_disable_dates );
                $response['selected_date']  = date('Y-m-d', strtotime($bookingpress_selected_date));
                $response['next_month'] = $next_month;
                $response['vcal_attributes'] = $attributes;
                $response['max_capacity_capacity'] = $max_service_capacity;
                $response['next_year'] = $next_year;
                $response['msg']        = 'Data reterive successfully';                            
            }
            
            /* $_SESSION['disable_dates'] = array();    
            $_SESSION['disable_dates'] = $bookingpress_disable_date; */
            /** Get Front Timings Changes Start */
            $get_period_available_for_booking = $BookingPress->bookingpress_get_settings('period_available_for_booking', 'general_setting');
            
            /** Set max 365 days if setting not set or pro version is deactivated */
            if( empty( $get_period_available_for_booking ) || !$BookingPress->bpa_is_pro_active() ){
                $get_period_available_for_booking = 365;
            }

            $response['prevent_next_month_check'] = false;
            if( !empty( $get_period_available_for_booking ) ){           
                $bookingpress_current_date = date('Y-m-d', current_time('timestamp') );
                $max_available_date = date('Y-m-d', strtotime( $bookingpress_current_date . '+' . $get_period_available_for_booking . ' days') );
                $response['max_available_date'] = $max_available_date;
                $response['max_available_month'] = date('m', strtotime( $max_available_date ) );
                $response['max_available_year'] = date('Y', strtotime( $max_available_date ) );
                if( $max_available_date < $response['selected_date'] ){
                    $response['front_timings'] = array();
                    $response['next_month'] = $next_month;
                    wp_send_json( $response );
                    die;
                }
            }
            
            if( !empty( $response['max_available_month'] ) && $next_month > $response['max_available_month'] && $response['max_available_year'] < date('Y', current_time('timestamp') ) ){
                $response['prevent_next_month_check'] = true;
            }
            $response['check_for_multiple_days_event'] = false;
            if( !empty( $bookingpress_appointment_data['selected_service_duration_unit'] ) && 'd' == $bookingpress_appointment_data['selected_service_duration_unit'] ){
                $response['check_for_multiple_days_event'] = true;
            }
            

            /** multiple days event */
            $multiple_days_event = false;
            if( !empty( $_POST['appointment_data_obj']['selected_service_duration_unit'] ) && $_POST['appointment_data_obj']['selected_service_duration_unit'] == 'd' ){
                $multiple_days_event = true;
            }

            $front_timings = $this->bookingpress_retrieve_timeslots( $response['selected_date'], true );

            $is_custom_duration = ( !empty( $front_timings['is_custom_duration'] ) && 1 == $front_timings['is_custom_duration'] ) ? true : false;
            if( !empty( $front_timings ) && !$is_custom_duration ){
                
                $is_front_timings_empty = false;
                $total_time_slots = 0;
                $total_booked_time_slots = 0;
                $total_timings = count( $front_timings );
                $empty_slots = 0;

                foreach( $front_timings as $k => $val ){
                    
                    if( !empty( $val ) && count( $val ) > 0 ){
                        foreach( $val as $ik => $iv ){
                            if( 1 == $iv['is_booked'] ){
                                $total_booked_time_slots++;
                            } else if( isset( $iv['max_capacity'] ) && isset( $iv['total_booked'] ) && $iv['total_booked'] >= $iv['max_total_capacity'] ){
                                $total_booked_time_slots++;
                            }
                            $total_time_slots++;
                        }
                    } else if( empty( $val ) ){
                        $empty_slots++;
                    }
                }
                if( ( $total_time_slots == $total_booked_time_slots && 0 < $total_time_slots && $total_booked_time_slots ) || $total_timings == $empty_slots ){
                    $is_front_timings_empty = true;
                }
                
                if( $is_front_timings_empty && $multiple_days_event ){
                    $is_front_timings_empty = false;
                }
                
                $response['front_timings'] = $front_timings;

                $bookingpress_add_single_disable_date = apply_filters('bookingpress_add_single_disable_date_when_no_timeslot',true,$bookingpress_selected_service,$front_timings);
                if( true == $is_front_timings_empty && $bookingpress_add_single_disable_date){
                    $response['empty_front_timings'] = true;
                    if( true == $consider_selected_date ){
                        $posted_selected_date = $bpa_selected_date;
                    } else {
                        $posted_selected_date = !empty($_REQUEST['selected_date']) ? sanitize_text_field($_REQUEST['selected_date']) : '';
                    }
                    //$bookingpress_selected_date = $BookingPress->bookingpress_select_date_before_load($posted_selected_date,$bookingpress_disable_date); /** reputelog - need to check with pro version data */                    
                    $response['next_available_date'] = date('Y-m-d', strtotime($posted_selected_date.'+1 day') );
                    if( !isset( $single_disable_date ) || !is_array( $single_disable_date ) ){
                        $single_disable_date = array();
                    }
                    $single_disable_date[] = date('c',strtotime($posted_selected_date));
                    
                    $this->bookingpress_get_disable_date_func_legacy( $response['next_available_date'], true, $counter++, $bookingpress_total_appointment, $single_disable_date );
                    
                }
            }

            
            /** Get Front Timings Changes End */            

            $response = apply_filters('bookingpress_modify_disable_date_data',$response);    

            
            
            wp_send_json($response);
            exit;
        }

        function bookingpress_check_available_timeslot_manual_block_func( $block_date, $check_date ){

            if( false != $block_date ){
                $block_date = $this->bookingpress_check_booked_appointments( $check_date, true, false, true );

            } else{
                return true;
            }
            return $block_date;
        }

        function bookingpress_check_booked_appointments( $disabled_date, $return = true, $check_for_whole_day = false, $checked_for_one_slot = false ){
            return $this->bookingpress_retrieve_timeslots( $disabled_date, $return, $check_for_whole_day, $checked_for_one_slot );
        }

                
        /**
         * Insert shortcode from classic editor
         *
         * @param  mixed $content
         * @return void
         */
        function bookingpress_insert_shortcode_button( $content )
        {
            global $bookingpress_global_options;
            $allowed_pages_for_media_button = array( 'post.php', 'post-new.php' );

            if (isset($_SERVER['PHP_SELF']) && ! in_array(basename($_SERVER['PHP_SELF']), $allowed_pages_for_media_button) ) {
                return;
            }
            if (! isset($post_type) ) {
                $post_type = '';
            }
            if (isset($_SERVER['PHP_SELF']) && basename(sanitize_text_field($_SERVER['PHP_SELF'])) == 'post.php' ) {
                $post_id   = isset($_REQUEST['post']) ? sanitize_text_field($_REQUEST['post']) : 0;
                $post_type = get_post_type($post_id);
            }
            if (isset($_SERVER['PHP_SELF']) && basename(sanitize_text_field($_SERVER['PHP_SELF'])) == 'post-new.php' ) {
                if (isset($_REQUEST['post_type']) ) {
                    $post_type = sanitize_text_field($_REQUEST['post_type']);
                } else {
                    $post_type = 'post';
                }
            }

            if( $content != 'content'){
                return;
            }

            $allowed_post_types = array( 'post', 'page' );

            if (! in_array($post_type, $allowed_post_types) ) {
                return;
            }
            if (! wp_script_is('jquery', 'enqueued') ) {
                wp_enqueue_script('jquery');
            }
            if (! wp_style_is('bookingpress_tinymce', 'enqueued') ) {
                wp_enqueue_style('bookingpress_tinymce', BOOKINGPRESS_URL . '/css/bookingpress_tinymce.css', array(), BOOKINGPRESS_VERSION);
            }
            wp_register_script('bookingpress_vue_js', BOOKINGPRESS_URL . '/js/bookingpress_vue.min.js', array(), BOOKINGPRESS_VERSION, 0);
            wp_register_script('bookingpress_element_js', BOOKINGPRESS_URL . '/js/bookingpress_element.js', array( '' ), '2.51.5', 0);
            wp_register_script('bookingpress_element_en_js', BOOKINGPRESS_URL . '/js/bookingpress_element_en.js', array( '' ), '2.51.5', 0);
            wp_register_script('bookingpress_wordpress_vue_helper_js', BOOKINGPRESS_URL . '/js/bookingpress_wordpress_vue_qs_helper.js', array( '' ), '6.5.1', 0);

            wp_enqueue_script('bookingpress_vue_js');
            wp_enqueue_script('bookingpress_element_js');
            wp_enqueue_script('bookingpress_element_en_js');
            wp_enqueue_script('bookingpress_wordpress_vue_helper_js');

            wp_register_style('bookingpress_element_css', BOOKINGPRESS_URL . '/css/bookingpress_element_theme.css', array(), BOOKINGPRESS_VERSION);
            wp_enqueue_style('bookingpress_element_css');

            if (wp_script_is('bookingpress_vue_js', 'enqueued') ) {
                $this->bookingpress_insert_shortcode_popup();
            }

            $bookingpress_site_current_language = $bookingpress_global_options->bookingpress_get_site_current_language();

            if ($bookingpress_site_current_language != 'en' ) {
                wp_register_script('bookingpress_vue_cal_locale', BOOKINGPRESS_URL . '/js/locales/' . $bookingpress_site_current_language . '.js', array(), BOOKINGPRESS_VERSION, true);
                wp_enqueue_script('bookingpress_vue_cal_locale');

                wp_register_script('bookingpress_elements_locale', BOOKINGPRESS_URL . '/js/elements_locale/' . $bookingpress_site_current_language . '.js', array(), BOOKINGPRESS_VERSION, true);
                wp_enqueue_script('bookingpress_elements_locale');
            } else {
                wp_register_script('bookingpress_elements_locale', BOOKINGPRESS_URL . '/js/bookingpress_element_en.js', array(), BOOKINGPRESS_VERSION, true);
                wp_enqueue_script('bookingpress_elements_locale');
            }

            $bpa_inline_script_data = '         				        					        		
					var lang = ELEMENT.lang.' . $bookingpress_site_current_language . ';
					ELEMENT.locale(lang);			
					var app = new Vue({						
						el: "#bookingpress_shortcode_form",
						data() {
							var bookingpress_return_data = {
								open_bookingpress_shortcode_modal: false,
								close_modal_on_esc: true,
								centerDialogVisible: false,
								selected_bookingpress_shortcode: "", 
								append_modal_to_body: true,
							};
							return bookingpress_return_data;			
						},
						mounted(){
						},
						methods: {							
							model_action() {
								const vm= this;
								if(vm.open_bookingpress_shortcode_modal == true ) {
									vm.open_bookingpress_shortcode_modal = false;		
								} else {
									vm.open_bookingpress_shortcode_modal = true;
								}					
							},
							bookingpress_open_form_shortcode_popup(){
								this.model_action();
							},
							add_bookingpress_shortcode(){
								const vm = this;
								if(vm.selected_bookingpress_shortcode != "") {
									if(tinyMCE.activeEditor != null){
										var editorContent = tinyMCE.activeEditor.getContent()
										editorContent += "["+vm.selected_bookingpress_shortcode+"]"
										tinyMCE.activeEditor.setContent(editorContent)
									}
									else{
										var textEditorContent = document.getElementById("content").innerHTML
										textEditorContent += "\n["+vm.selected_bookingpress_shortcode+"]"
										document.getElementById("content").innerHTML = textEditorContent
									}
									vm.model_action();
								}
							}
						},
					});';

            wp_add_inline_script('bookingpress_elements_locale', $bpa_inline_script_data);
        }
        
        /**
         * Load HTML content of classic editor button view
         *
         * @return void
         */
        function bookingpress_insert_shortcode_popup()
        {
            if (file_exists(BOOKINGPRESS_VIEWS_DIR . '/bookingpress_tinymce_options_shortcodes.php') ) {
                include BOOKINGPRESS_VIEWS_DIR . '/bookingpress_tinymce_options_shortcodes.php';
            }
            ?>
            <?php
        }
        
        /**
         * Server Side Validaton - Backend Side Validation
         *
         * @return void
         */
        function bookingpress_before_book_appointment_func()
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs,$tbl_bookingpress_customers,$bookingpress_payment_gateways,$tbl_bookingpress_form_fields;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                $response['redirect_url'] = '';
                return wp_json_encode($response);
                
            }
            $response['variant']    = 'success';
            $response['title']      = '';
            $response['msg']        = '';
            $response['error_type'] = '';
            
            if( !empty( $_REQUEST['appointment_data'] ) && !is_array( $_REQUEST['appointment_data'] ) ){
                $_REQUEST['appointment_data'] = json_decode( stripslashes_deep( $_REQUEST['appointment_data'] ), true ); //phpcs:ignore                
                $_POST['appointment_data'] = $_REQUEST['appointment_data'] =  !empty($_REQUEST['appointment_data']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_REQUEST['appointment_data'] ) : array(); // phpcs:ignore
            }
            $bookingpress_unique_id =  !empty($_REQUEST['appointment_data']['bookingpress_uniq_id']) ? sanitize_text_field( $_REQUEST['appointment_data']['bookingpress_uniq_id'] ) : '';
            $bookingpress_form_token = !empty( $_REQUEST['appointment_data']['bookingpress_form_token'] ) ? sanitize_text_field( $_REQUEST['appointment_data']['bookingpress_form_token'] ) : $bookingpress_unique_id;
            
            $no_service_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_service_selected_for_the_booking', 'message_setting');

            $no_appointment_date_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_date_selected_for_the_booking', 'message_setting');

            $no_appointment_time_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_time_selected_for_the_booking', 'message_setting');

            $no_payment_method_is_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_payment_method_is_selected_for_the_booking', 'message_setting');

            $duplicate_email_address_found = $BookingPress->bookingpress_get_settings('duplicate_email_address_found', 'message_setting');

            $unsupported_currecy_selected_for_the_payment = $BookingPress->bookingpress_get_settings('unsupported_currecy_selected_for_the_payment', 'message_setting');

            $duplidate_appointment_time_slot_found = $BookingPress->bookingpress_get_settings('duplidate_appointment_time_slot_found', 'message_setting');

            $bookingpress_service_price = isset($_REQUEST['appointment_data']['service_price_without_currency']) ? floatval($_REQUEST['appointment_data']['service_price_without_currency']) : 0;

            /* server side validation */
            $all_fields = $wpdb->get_results( "SELECT bookingpress_field_error_message,bookingpress_form_field_name,bookingpress_field_is_default FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_required = 1 AND bookingpress_field_is_hide = 0" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_form_fields is a table name. false alarm
  
            $field_validation_message = array();
            $is_required_validation = false;
            if ( ! empty( $all_fields ) ) {
                foreach ( $all_fields as $field_data ) {

                    $field_error_msg = $field_data->bookingpress_field_error_message;

                    if( $field_data->bookingpress_field_is_default == 1 ){

                        if( $field_data->bookingpress_form_field_name == 'firstname'){
                            $bpa_visible_field_key = 'customer_firstname';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'lastname'){
                            $bpa_visible_field_key = 'customer_lastname';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'email_address'){
                            $bpa_visible_field_key = 'customer_email';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'note'){
                            $bpa_visible_field_key = 'appointment_note';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'phone_number'){
                            $bpa_visible_field_key = 'customer_phone';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'fullname'){
                            $bpa_visible_field_key = 'customer_name';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'username'){
                            $bpa_visible_field_key = 'customer_username';		
                        }
                        if( $field_data->bookingpress_form_field_name == 'terms_and_conditions'){
                            $bpa_visible_field_key = 'appointment_terms_conditions';		
                        }
                    } 
                    
                    $val = isset($_POST['appointment_data'][ $bpa_visible_field_key ]) ? $_POST['appointment_data'][ $bpa_visible_field_key ] : ''; //phpcs:ignore

                    if( $bpa_visible_field_key == 'appointment_terms_conditions'){

                        if( empty($val[0])){
                            $is_required_validation = true;
                            $field_validation_message[] = $field_error_msg;
                        }
                    } else {
                        if( '' === $val ){
                            $is_required_validation = true;
                            $field_validation_message[] = $field_error_msg;
                        }
                    }

                }
            }

            if( true == $is_required_validation ){
				$response['variant'] = 'error';
				$response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
				$response['msg']     = !empty($field_validation_message) ? implode(' ', $field_validation_message) : array();
				return wp_json_encode($response);	
			}

            if (empty($_POST['appointment_data']['selected_service']) ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html($no_service_selected_for_the_booking);
                return wp_json_encode($response);
            }

            if (empty($_POST['appointment_data']['selected_date']) ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html($no_appointment_date_selected_for_the_booking);
                return wp_json_encode($response);
            }

            if (empty($_POST['appointment_data']['selected_start_time']) || empty($_POST['appointment_data']['selected_end_time']) ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html($no_appointment_time_selected_for_the_booking);
                return wp_json_encode($response);
            }

            if (empty($_POST['appointment_data']['selected_payment_method']) && $bookingpress_service_price > 0 ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html($no_payment_method_is_selected_for_the_booking);
                return wp_json_encode($response);
            }

            $bookingpress_fullname  = ! empty($_POST['appointment_data']['customer_name']) ? trim(sanitize_text_field($_POST['appointment_data']['customer_name'])) : '';
            $bookingpress_firstname = ! empty($_POST['appointment_data']['customer_firstname']) ? trim(sanitize_text_field($_POST['appointment_data']['customer_firstname'])) : '';
            $bookingpress_lastname  = ! empty($_POST['appointment_data']['customer_lastname']) ? trim(sanitize_text_field($_POST['appointment_data']['customer_lastname'])) : '';
            $bookingpress_email     = ! empty($_POST['appointment_data']['customer_email']) ? sanitize_email($_POST['appointment_data']['customer_email']) : '';

            if (strlen($bookingpress_fullname) > 255 ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Fullname is too long...', 'bookingpress-appointment-booking');
                return wp_json_encode($response);
            }
            if (strlen($bookingpress_firstname) > 255 ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Firstname is too long...', 'bookingpress-appointment-booking');
                return wp_json_encode($response);
            }
            if (strlen($bookingpress_lastname) > 255 ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Lastname is too long...', 'bookingpress-appointment-booking');
                return wp_json_encode($response);
            }
            if (strlen($bookingpress_email) > 255 ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Email address is too long...', 'bookingpress-appointment-booking');
                return wp_json_encode($response);
            }
            $bookingpress_selected_payment_method = sanitize_text_field($_POST['appointment_data']['selected_payment_method']);
            $bookingpress_currency_name           = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');

            $bookingpress_paypal_currency = $bookingpress_payment_gateways->bookingpress_paypal_supported_currency_list();            
            if ($bookingpress_selected_payment_method == 'paypal' && !in_array($bookingpress_currency_name,$bookingpress_paypal_currency ) ) {
                
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html($unsupported_currecy_selected_for_the_payment);
                return wp_json_encode($response);
            }

            $appointment_service_id    = intval($_POST['appointment_data']['selected_service']);
            $appointment_selected_date = date('Y-m-d', strtotime(sanitize_text_field($_POST['appointment_data']['selected_date'])));
            $appointment_start_time    = date('H:i:s', strtotime(sanitize_text_field($_POST['appointment_data']['selected_start_time'])));
            $appointment_end_time      = date('H:i:s', strtotime(sanitize_text_field($_POST['appointment_data']['selected_end_time'])));

            $is_appointment_exists = $BookingPress->bookingpress_is_appointment_booked($appointment_service_id, $appointment_selected_date, $appointment_start_time, $appointment_end_time);
            if ($is_appointment_exists) {
                $response['variant']              = 'error';
                $response['title']                = 'Error';
                $response['msg']                  = esc_html($duplidate_appointment_time_slot_found);
                return wp_json_encode($response);
            }

            // If selected date is day off then display error.
            $bookingpress_search_query              = preg_quote($appointment_selected_date, '~');
            $bookingpress_get_default_daysoff_dates = $BookingPress->bookingpress_get_default_dayoff_dates();
            $bookingpress_search_date               = preg_grep('~' . $bookingpress_search_query . '~', $bookingpress_get_default_daysoff_dates);
            if (! empty($bookingpress_search_date) ) {
                $booking_dayoff_msg     = esc_html__('Selected date is off day', 'bookingpress-appointment-booking');
                $booking_dayoff_msg    .= '. ' . esc_html__('So please select new date', 'bookingpress-appointment-booking') . '.';
                $response['error_type'] = 'dayoff';
                $response['variant']    = 'error';
                $response['title']      = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']        = $booking_dayoff_msg;
                return wp_json_encode($response);
            }

            // If payment gateway is disable then return error
            if ($bookingpress_selected_payment_method == 'on-site' && $bookingpress_service_price > 0 ) {
                $on_site_payment = $BookingPress->bookingpress_get_settings('on_site_payment', 'payment_setting');
                if (empty($on_site_payment) || ( $on_site_payment == 'false' ) ) {
                    $response['variant'] = 'error';
                    $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                    $response['msg']     = __('On site payment gateway is not active', 'bookingpress-appointment-booking') . '.';
                    return wp_json_encode($response);
                }
            } elseif ( $bookingpress_selected_payment_method == 'paypal' && $bookingpress_service_price > 0 ) {
                $paypal_payment = $BookingPress->bookingpress_get_settings('paypal_payment', 'payment_setting');
                if (empty($paypal_payment) || ( $paypal_payment == 'false' ) ) {
                    $response['variant'] = 'error';
                    $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                    $response['msg']     = __('PayPal payment gateway is not active', 'bookingpress-appointment-booking') . '.';
                    return wp_json_encode($response);
                }

                if ($bookingpress_service_price < floatval('0.1') ) {
                    $response['variant'] = 'error'; 
                    $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                    $response['msg']     = esc_html__('Paypal supports minimum amount 0.1', 'bookingpress-appointment-booking');
                    return wp_json_encode($response);
                }
            }

            $bpa_front_timings_key = 'bpa_front_timings_' .$bookingpress_form_token.'_'.$appointment_selected_date;
            
            $bpa_front_timings_data = $this->bookingpress_get_transient( $bpa_front_timings_key );
            
            // double confirm the timings
            $timings = array_values($bpa_front_timings_data);
            
            $appointment_start_time = date('H:i',strtotime($appointment_start_time));
            $appointment_end_time = $appointment_end_time != '00:00:00' ? date('H:i',strtotime($appointment_end_time)) : '24:00';
            $time_slot_start_key = array_search($appointment_start_time, array_column( $timings, 'store_start_time' ) );				
            $time_slot_end_key = array_search( $appointment_end_time, array_column( $timings, 'store_end_time' ) );
            if( ( trim($time_slot_start_key) === '' || trim($time_slot_end_key) === '' || trim($time_slot_start_key) != trim($time_slot_end_key) ) && 'd' != $posted_data['appointment_data']['selected_service_duration_unit'] ){
                $response['variant']              = 'error';
                $response['title']                = 'Error';
                $response['msg']                  = esc_html__("Sorry, Booking can not be done as booking time is different than selected timeslot", "bookingpress-appointment-booking");
                return wp_json_encode($response);
            }

            do_action('bookingpress_validate_booking_form', $_POST);

        }
        
        /**
         * Cancel appointment from customer cancel link
         * @deprecated on 07th March 2023
         * 
         * @return void
         */
        function bookingpress_cancel_appointment_func()
        {
            if( !empty($_SERVER["HTTP_USER_AGENT"]) && $_SERVER["HTTP_USER_AGENT"] == 'WhatsApp' ){
                return;
            }
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs, $bookingpress_email_notifications, $bookingpress_services;
            $cancel_id    = ! empty($_REQUEST['bpa_cancel']) ? intval(base64_decode($_REQUEST['bpa_cancel'])) : 0; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_REQUEST['bpa_cancel'] is sanitized properly
            $cancel_token = ! empty($_REQUEST['cancel_id']) ? sanitize_text_field($_REQUEST['cancel_id']) : '';

            if (! empty($cancel_id) && ! empty($cancel_token) ) {

                $bookingpress_appointment_log_data = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_customer_email,bookingpress_service_id,bookingpress_appointment_date,bookingpress_appointment_time,bookingpress_customer_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d AND bookingpress_appointment_token = %s", $cancel_id,$cancel_token), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_log_data is table name defined globally. False Positive alarm

                if (! empty($bookingpress_appointment_log_data) ) {

                    $bookingpress_appointment_date = $bookingpress_appointment_log_data['bookingpress_appointment_date'];
                    $bookingpress_appointment_time = $bookingpress_appointment_log_data['bookingpress_appointment_time'];
                    $bookingpress_appointment_datetime = $bookingpress_appointment_date." ".$bookingpress_appointment_time;
                    $bookingpress_service_id = $bookingpress_appointment_log_data['bookingpress_service_id'];                        
                    $current_datetime = date( 'Y-m-d H:i:s', current_time('timestamp') );

                    $allow_cancel_appointment = true;
                    if( $bookingpress_appointment_datetime <= $current_datetime ){
                        $allow_cancel_appointment = false;
                    }

                    if($allow_cancel_appointment == true){
                        $bookingpress_min_time_before_cancel = $BookingPress->bookingpress_get_settings('default_minimum_time_for_canceling', 'general_setting');

                        //Check service level minimum time required before cancel
                        $bookingpress_service_min_time_require_before_cancel = $bookingpress_services->bookingpress_get_service_meta($bookingpress_service_id, 'minimum_time_required_before_cancelling');
                        if(!empty($bookingpress_service_min_time_require_before_cancel)){
                            if($bookingpress_service_min_time_require_before_cancel == 'disabled'){
                                $bookingpress_min_time_before_cancel = 'disabled';
                            }else if($bookingpress_service_min_time_require_before_cancel != 'inherit'){
                                $bookingpress_min_time_before_cancel = $bookingpress_service_min_time_require_before_cancel;
                            }
                        }

                        //Check minimum cancel time
                        if($allow_cancel_appointment && !empty($bookingpress_min_time_before_cancel) && $bookingpress_min_time_before_cancel != 'disabled'){
                            $bookingpress_from_time = current_time('timestamp');
                            $bookingpress_to_time = strtotime($bookingpress_appointment_datetime);
                            $bookingpress_time_diff_for_cancel = round(abs($bookingpress_to_time - $bookingpress_from_time) / 60, 2);

                            if($bookingpress_time_diff_for_cancel < $bookingpress_min_time_before_cancel){
                                $allow_cancel_appointment = false;
                            }
                        }
                    }

                    if($allow_cancel_appointment){
                        $bookingress_customer_email = $bookingpress_appointment_log_data['bookingpress_customer_email'];
                        $bookingpress_after_canceled_payment_page_id = $BookingPress->bookingpress_get_customize_settings('after_cancelled_appointment_redirection', 'booking_my_booking');                        
                        $bookingpress_after_canceled_payment_url     = get_permalink($bookingpress_after_canceled_payment_page_id);
                        $bookingpress_after_canceled_payment_url = ! empty($bookingpress_after_canceled_payment_url) ? $bookingpress_after_canceled_payment_url : BOOKINGPRESS_HOME_URL;

                        $wpdb->update($tbl_bookingpress_appointment_bookings, array( 'bookingpress_appointment_status' => '3' ), array( 'bookingpress_appointment_booking_id' => $cancel_id ));

                        $bookingpress_email_notifications->bookingpress_send_after_payment_log_entry_email_notification('Appointment Canceled', $cancel_id, $bookingress_customer_email);

                        $wpdb->update($tbl_bookingpress_appointment_bookings,array('bookingpress_appointment_token' => ''),array('bookingpress_appointment_booking_id' => $cancel_id));

                        do_action('bookingpress_after_cancel_appointment', $cancel_id);

                        wp_redirect($bookingpress_after_canceled_payment_url);

                    }else{
                        $bookingpress_alert_msg = esc_html__("We're sorry! you can't cancel this appointment because minimum required time for cancellation is already passed", "bookingpress-appointment-booking");

                        $bookingpress_alert_script = "<script>";
                        $bookingpress_alert_script .= "alert('".$bookingpress_alert_msg."')";
                        $bookingpress_alert_script .= "</script>";

                        echo $bookingpress_alert_script; // phpcs:ignore
                    }
                }
            }
        }
        
        /**
         * Cancel appointment from ajax request
         *
         * @return void
         */
        function bookingpress_cancel_appointment($return_data = false,$user_id = "")
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_payment_logs, $bookingpress_email_notifications;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant']      = 'error';
                $response['title']        = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                $response['redirect_url'] = '';
                if($return_data){
                    return $response;
                }
                wp_send_json($response);
                die();
            }

            $response['variant'] = 'error';
            $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
            $response['msg']     = esc_html__('Something went wrong..', 'bookingpress-appointment-booking');

            $appointment_cancelled_successfully = $BookingPress->bookingpress_get_settings('appointment_cancelled_successfully', 'message_setting');
            $cancel_id                          = ! empty($_REQUEST['cancel_id']) ? intval($_REQUEST['cancel_id']) : 0;

            $allow_cancel_appointment = $this->bookingpress_check_cancel_appointment_permission_func( $cancel_id,$user_id );
            
            if (! empty($cancel_id) && true == $allow_cancel_appointment ) {                

                $bookingpress_after_canceled_payment_page_id = $BookingPress->bookingpress_get_customize_settings('after_cancelled_appointment_redirection', 'booking_my_booking');
                $bookingpress_after_canceled_payment_url = get_permalink($bookingpress_after_canceled_payment_page_id);

                $bookingpress_after_canceled_payment_url = ! empty($bookingpress_after_canceled_payment_url) ? $bookingpress_after_canceled_payment_url : BOOKINGPRESS_HOME_URL;
                
                $response['variant']      = 'success';
                $response['title']        = esc_html__('Success', 'bookingpress-appointment-booking');
                $response['msg']          = esc_html($appointment_cancelled_successfully);
                $response['redirect_url'] = $bookingpress_after_canceled_payment_url;

                
                $response = apply_filters('bookingpress_refund_process_before_cancel_appointment',$response,$cancel_id);

                if($response['variant'] == 'success' ) {
                    $wpdb->update($tbl_bookingpress_appointment_bookings, array( 'bookingpress_appointment_status' => '3' ), array( 'bookingpress_appointment_booking_id' => $cancel_id ));

                    do_action('bookingpress_after_cancel_appointment_without_check_payment', $cancel_id);
                    // Get payment log id and insert canceled appointment entry
                    $payment_log_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_appointment_booking_ref = %d", $cancel_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_payment_logs is table name defined globally. False Positive alarm                
                    
                    if (! empty($payment_log_data) ) {
                        $bookingress_customer_email = $payment_log_data['bookingpress_customer_email'];

                        $bookingpress_email_notifications->bookingpress_send_after_payment_log_entry_email_notification('Appointment Canceled', $cancel_id, $bookingress_customer_email);

                        do_action('bookingpress_after_cancel_appointment', $cancel_id);
                    }
                }
            }
            if($return_data){
                return $response;
            }
            echo json_encode($response);
            exit();
        }
        
        /**
         * My appointment shortcode callback function
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_my_appointments_func( $atts, $content, $tag )
        {   

                global $wpdb, $BookingPress,$bookingpress_global_options;

                $this->bookingpress_mybooking_login_user_id = get_current_user_id();                
                $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
                $bookingpress_default_date_time_format = $bookingpress_global_options_arr['wp_default_date_format'];
                $bookingpress_default_date_format = 'MMMM D, YYYY';  
                if ($bookingpress_default_date_time_format == 'F j, Y' ) {
                    $bookingpress_default_date_format = 'MMMM D, YYYY';
                } elseif ($bookingpress_default_date_time_format == 'Y-m-d' ) {
                    $bookingpress_default_date_format = 'YYYY-MM-DD';
                } elseif ($bookingpress_default_date_time_format == 'm/d/Y' ) {
                    $bookingpress_default_date_format = 'MM/DD/YYYY';
                } elseif($bookingpress_default_date_time_format == 'd/m/Y') {
                    $bookingpress_default_date_format = 'DD/MM/YYYY';
                } elseif ($bookingpress_default_date_time_format == 'd.m.Y') {
                    $bookingpress_default_date_format = 'DD.MM.YYYY';
                } elseif ($bookingpress_default_date_time_format == 'd-m-Y') {
                    $bookingpress_default_date_format = 'DD-MM-YYYY';
                }

                $this->bookingpress_mybooking_default_date_format = $bookingpress_default_date_format;

                $BookingPress->set_front_css(1);
                $BookingPress->set_front_js(1);
                $BookingPress->bookingpress_load_mybooking_custom_css();
               // $BookingPress->bookingpress_load_mybookings_custom_js();               

                $bookingpress_uniq_id = uniqid();
                ob_start();
                $bookingpress_my_appointments_file_url = BOOKINGPRESS_VIEWS_DIR . '/frontend/appointment_my_appointments.php';
                $bookingpress_my_appointments_file_url = apply_filters('bookingpress_change_my_appointmens_shortcode_file_url', $bookingpress_my_appointments_file_url);
                include $bookingpress_my_appointments_file_url;
                $content .= ob_get_clean();

                add_action(
                    'wp_footer',
                    function () use ( &$bookingpress_uniq_id ) {
                        global $bookingpress_global_options , $BookingPress;
                        $bookingpress_global_details     = $bookingpress_global_options->bookingpress_global_options();
                        $bookingpress_formatted_timeslot = $bookingpress_global_details['bpa_time_format_for_timeslot'];
                        $requested_module                = 'front_appointments';
                        ?>
                        <script>
                            window.addEventListener('DOMContentLoaded', function() {
                                var bookingpress_start_of_week = '<?php echo esc_html($bookingpress_global_details['start_of_week']); ?>';
                                var bpa_customer_username = '<?php echo esc_html($this->bookingpress_mybooking_customer_email); ?>';
                                var bpa_customer_email = '<?php echo esc_html($this->bookingpress_mybooking_customer_email); ?>';
                                var bpa_customer_id = '<?php echo esc_html($this->bookingpress_mybooking_wpuser_id); ?>';
                                <?php do_action('bookingpress_' . $requested_module . '_dynamic_helper_vars'); ?>
                            var app = new Vue({
                                    el: '#bookingpress_booking_form_<?php echo esc_html($bookingpress_uniq_id); ?>',
                                    directives: { <?php do_action('bookingpress_' . $requested_module . '_dynamic_directives'); ?> },
                                    components: { <?php do_action('bookingpress_' . $requested_module . '_dynamic_components'); ?> },
                                data() {
                                    var bookingpress_return_data = <?php do_action('bookingpress_' . $requested_module . '_dynamic_data_fields'); ?>;
                                    bookingpress_return_data['is_display_loader'] = '0';
                                    bookingpress_return_data['bookingpress_uniq_id'] = '<?php echo esc_html($bookingpress_uniq_id); ?>';
                                    bookingpress_return_data['pickerOptions'] = 
                                    {                                         
                                        disabledDate(Time) 
                                        {                  
                                            var dd = String(Time.getDate()).padStart(2, '0');
                                            var mm = String(Time.getMonth() + 1).padStart(2, '0'); /* January is 0! */
                                            var yyyy = Time.getFullYear();
                                            var time = yyyy+ '-' + mm + '-' + dd;
                                            if( "undefined" == typeof bookingpress_return_data['disabledDates'].indexOf ){
                                                var date = new Date();
                                                var newDateArr = [];
                                                for( let dcount in bookingpress_return_data['disabledDates'] ){
                                                    let dis_date = bookingpress_return_data['disabledDates'][dcount];
                                                    newDateArr.push( dis_date );
                                                }
                                                var disable_date = newDateArr.indexOf( time ) > -1;
                                                var date = new Date();
                                                date.setDate(date.getDate()-1);
                                                var disable_past_date = Time.getTime() < date.getTime();
                                                if(disable_date == true) {
                                                    return disable_date; 
                                                } else {
                                                    return disable_past_date;
                                                } 
                                            } else {
                                                var disable_date= bookingpress_return_data['disabledDates'].indexOf(time)>-1;
                                                var date = new Date();
                                                date.setDate(date.getDate()-1);
                                                var disable_past_date = Time.getTime() < date.getTime();
                                                if(disable_date == true) {
                                                    return disable_date; 
                                                } else {
                                                    return disable_past_date;
                                                }
                                            }
                                        },                                         
                                    };
                                    bookingpress_return_data['filter_pickerOptions'] = {
                                        'firstDayOfWeek': parseInt(bookingpress_start_of_week),
                                    };
                                    return bookingpress_return_data;
                                },
                                filters:{
                                    bookingpress_format_date: function(value){
                                        var default_date_format = '<?php echo esc_html($this->bookingpress_mybooking_default_date_format); ?>';
                                        <?php $bookingpress_site_current_lang_moment_locale = get_locale(); ?>
                                        return moment(String(value)).locale('<?php echo esc_html($bookingpress_site_current_lang_moment_locale); ?>').format(default_date_format);
                                    },
                                    bookingpress_format_time: function(value){
                                        var default_time_format = '<?php echo esc_html($bookingpress_formatted_timeslot); ?>';
                                        <?php $bookingpress_site_current_lang_moment_locale = get_locale(); ?>
                                        return moment(String(value), "HH:mm:ss").locale('<?php echo esc_html($bookingpress_site_current_lang_moment_locale); ?>').format(default_time_format)
                                    }
                                },
                                beforeCreate(){
                                    this.is_front_appointment_empty_loader = '1';
                                },
                                created(){
                                },
                                mounted() {
                                        <?php do_action('bookingpress_' . $requested_module . '_dynamic_on_load_methods'); ?>            
                                },
                                methods: {
                                    <?php do_action('bookingpress_' . $requested_module . '_dynamic_vue_methods'); ?>
                                },
                            });
                        });
                        </script>
                        <?php
                    },
                    100
                );

                $bookingpress_custom_css = $BookingPress->bookingpress_get_customize_settings('custom_css', 'booking_form');
                $bookingpress_custom_css = !empty($bookingpress_custom_css) ? stripslashes_deep($bookingpress_custom_css) : '';
                wp_add_inline_style( 'bookingpress_front_mybookings_custom_css', $bookingpress_custom_css, 'after' );            
            return do_shortcode($content);
        }
        
        /**
         * Get customers my appointments list
         *
         * @return void
         */
        function bookingpress_get_customer_appointments_func($return_data = false, $user_id = "")
        {
            global $BookingPress,$wpdb,$tbl_bookingpress_appointment_bookings,$tbl_bookingpress_customers,$bookingpress_global_options, $tbl_bookingpress_payment_logs;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                if($return_data){
                    return $response;
                }
                wp_send_json($response);
                die();
            }
            $bpa_login_customer_id             = get_current_user_id();
            if(!empty($user_id)){
                $bpa_login_customer_id = $user_id;
            }
            
            $bookingpress_get_customer_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id =%d AND bookingpress_user_status = 1 ORDER BY bookingpress_customer_id DESC", $bpa_login_customer_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
            $bookingpress_current_user_id      = ! empty($bookingpress_get_customer_details['bookingpress_customer_id']) ? $bookingpress_get_customer_details['bookingpress_customer_id'] : 0;                               
            $bookingpress_customer_data = $appointments_data = array();
            $bookingpress_customer_data['bookingpress_user_email'] = '';
            $bookingpress_customer_data['bookingpress_user_fullname'] = '';
            $bookingpress_customer_data['bookingpress_avatar_url'] = BOOKINGPRESS_IMAGES_URL . '/default-avatar.jpg';

            if(empty($bookingpress_current_user_id) && (is_user_logged_in() || !empty($user_id))) {

                $bookingpress_current_user_obj = wp_get_current_user();
                if(!empty($user_id)){
                    $bookingpress_current_user_obj = get_user_by('id', $user_id);
                }
                $bookingpress_customer_name  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                $bookingpress_customer_data['bookingpress_user_email'] = stripslashes_deep($bookingpress_customer_email);
                $bookingpress_customer_data['bookingpress_user_fullname'] = stripslashes_deep($bookingpress_customer_name);
            }

            $bookingpress_total_appointments = 0;

            $perpage     = isset($_POST['perpage']) ? intval($_POST['perpage']) : 10;
            $currentpage = isset($_POST['currentpage']) ? intval($_POST['currentpage']) : 1;
            $offset      = ( ! empty($currentpage) && $currentpage > 1 ) ? ( ( $currentpage - 1 ) * $perpage ) : 0;

            if (! empty($bookingpress_current_user_id) ) {
                $bpa_customer_firstname = ! empty($bookingpress_get_customer_details['bookingpress_user_firstname']) ? stripslashes_deep($bookingpress_get_customer_details['bookingpress_user_firstname']) : '';
                $bpa_customer_lastname = ! empty($bookingpress_get_customer_details['bookingpress_user_lastname']) ? stripslashes_deep($bookingpress_get_customer_details['bookingpress_user_lastname']) : '';		
                $bookingpress_user_fullname = $bpa_customer_firstname.' '.$bpa_customer_lastname ; 
                if( is_user_logged_in() ){
                    if( is_user_logged_in() ){   
                        $bookingpress_current_user_obj = wp_get_current_user();
                        
                        $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                        $bookingpress_user_email = stripslashes_deep($bookingpress_customer_email);
                    }
                } else {
                    $bookingpress_user_email = ! empty( $bookingpress_get_customer_details['bookingpress_user_email'] ) ? sanitize_email( $bookingpress_get_customer_details['bookingpress_user_email'] ) : '';            
                }
                $bpa_avatar_url = get_avatar_url( $bpa_login_customer_id );

                $bookingpress_get_existing_avatar_url = $BookingPress->get_bookingpress_customersmeta( $bookingpress_current_user_id, 'customer_avatar_details' );
                $bookingpress_get_existing_avatar_url = ! empty( $bookingpress_get_existing_avatar_url ) ? maybe_unserialize( $bookingpress_get_existing_avatar_url ) : array();            
                $use_default_placeholder = false;
                if ( ! empty( $bookingpress_get_existing_avatar_url[0]['url'] ) ) {
                    $bookingpress_user_avatar = $bookingpress_get_existing_avatar_url[0]['url'];
                } else {
                    $bookingpress_user_avatar = BOOKINGPRESS_IMAGES_URL . '/default-avatar.jpg';
                    $use_default_placeholder = true;
                }
                $bookingpress_customer_data['bookingpress_user_email'] = $bookingpress_user_email;
                $bookingpress_customer_data['bookingpress_user_fullname'] = $bookingpress_user_fullname;
                $bookingpress_customer_data['bookingpress_avatar_url'] = $bookingpress_user_avatar;         
                $bookingpress_customer_data['bookingpress_use_placeholder'] = $use_default_placeholder;

                 // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_REQUEST['search_data'] contains mixed array and it's been sanitized properly using 'appointment_sanatize_field' function
                $bookingpress_search_data        = ! empty($_REQUEST['search_data']) ? array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['search_data']) : array();
                $bookingpress_search_query       = '';
                if( is_user_logged_in() ){
                    $bookingpress_current_user_wpid = get_current_user_id();
                    $bookingpress_search_query_where = "WHERE 1=1 AND ( bookingpress_customer_id IN ( SELECT bookingpress_customer_id FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = {$bookingpress_current_user_wpid} ) ) ";
                } else {
                    $bookingpress_search_query_where = "WHERE 1=1 AND (bookingpress_customer_id={$bookingpress_current_user_id}) ";
                }

                if (! empty($bookingpress_search_data) ) {
                    if (! empty($bookingpress_search_data['search_appointment']) ) {
                        $bookingpress_search_string       = $bookingpress_search_data['search_appointment'];
                        $bookingpress_search_query_where .= "AND (bookingpress_service_name LIKE '%{$bookingpress_search_string}%') ";
                    }
                    if ( !empty ( $bookingpress_search_data['selected_date_range'] ) && ! empty($bookingpress_search_data['selected_date_range'][0] && $bookingpress_search_data['selected_date_range'][1]) ) {                        
                        $bookingpress_search_date         = $bookingpress_search_data['selected_date_range'];
                        $start_date                       = date('Y-m-d', strtotime($bookingpress_search_date[0]));
                        $end_date                         = date('Y-m-d', strtotime($bookingpress_search_date[1]));
                        $bookingpress_search_query_where .= "AND (bookingpress_appointment_date BETWEEN '{$start_date}' AND '{$end_date}')";
                    }
                }

                $bookingpress_global_data = $bookingpress_global_options->bookingpress_global_options();
                $bookingpress_date_format = $bookingpress_global_data['wp_default_date_format'];
                $bookingpress_time_format = $bookingpress_global_data['wp_default_time_format'];
                $bookingpress_appointment_statuses = $bookingpress_global_data['appointment_status'];
                $bookingpress_payment_statuses = $bookingpress_global_data['payment_status'];
                
                $bookingpress_total_appointments = $wpdb->get_var("SELECT COUNT(bookingpress_appointment_booking_id) FROM {$tbl_bookingpress_appointment_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} ORDER BY bookingpress_appointment_date DESC"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm
                $appointments_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_appointment_bookings} {$bookingpress_search_query} {$bookingpress_search_query_where} ORDER BY bookingpress_appointment_date DESC LIMIT {$offset} , {$perpage}", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm

                if(!empty($appointments_data) && is_array($appointments_data) ){
                    foreach($appointments_data as $k => $v){
                        $bookingpress_appointment_date = date_i18n($bookingpress_date_format, strtotime($v['bookingpress_appointment_date']));
                        $appointments_data[$k]['bookingpress_appointment_formatted_date'] = $bookingpress_appointment_date;

                        $bookingpress_appointment_start_time = date($bookingpress_time_format, strtotime($v['bookingpress_appointment_time']));
                        $appointments_data[$k]['bookingpress_appointment_formatted_start_time'] = $bookingpress_appointment_start_time;
                        $bookingpress_appointment_end_time = date($bookingpress_time_format, strtotime($v['bookingpress_appointment_end_time']));
                        $appointments_data[$k]['bookingpress_appointment_formatted_end_time'] = $bookingpress_appointment_end_time;

                        $bookingpress_appointment_duration_unit_label = esc_html__('Minutes', 'bookingpress-appointment-booking');
                        if($v['bookingpress_service_duration_unit'] == 'h'){
                            $bookingpress_appointment_duration_unit_label = esc_html__('Hours', 'bookingpress-appointment-booking');
                        } else if( 'd' == $v['bookingpress_service_duration_unit'] ){
                            $bookingpress_appointment_duration_unit_label = esc_html__('Days', 'bookingpress-appointment-booking');
                        }
                        
                        $appointments_data[$k]['bookingpress_service_name'] = !empty($v['bookingpress_service_name']) ? stripslashes_deep($v['bookingpress_service_name']) : '';
                        $appointments_data[$k]['bookingpress_service_duration_label'] = $bookingpress_appointment_duration_unit_label;

                        $bookingpress_appointment_status_label = '';
                        foreach($bookingpress_appointment_statuses as $k2 => $v2){
                            if($v2['value'] == $v['bookingpress_appointment_status']){
                                $bookingpress_appointment_status_label = $v2['text'];
                            }
                        }
                        $appointments_data[$k]['bookingpress_appointment_status_label'] = $bookingpress_appointment_status_label;

                        $currency_name   = $v['bookingpress_service_currency'];
                        $currency_symbol = $BookingPress->bookingpress_get_currency_symbol($currency_name);
                        $bookingpress_paid_price_with_currency = $BookingPress->bookingpress_price_formatter_with_currency_symbol($v['bookingpress_paid_amount'], $currency_symbol);
                        $appointments_data[$k]['bookingpress_paid_price_with_currency'] = $bookingpress_paid_price_with_currency;

                        //get payment log details
                        $bookingpress_appointment_id = intval($v['bookingpress_appointment_booking_id']);
                        $bookingpress_payment_log_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_payment_logs} WHERE bookingpress_appointment_booking_ref = %d", $bookingpress_appointment_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_payment_logs is a table name. false alarm

                        $appointments_data[$k]['booking_id'] = !empty($v['bookingpress_booking_id']) ? sanitize_text_field($v['bookingpress_booking_id']) : 1;

                        $bookingpress_payment_status = $bookingpress_payment_status_label = $bookingpress_payment_method = '';
                        if(!empty($bookingpress_payment_log_details)){
                            $bookingpress_payment_method = $bookingpress_payment_log_details['bookingpress_payment_gateway'];
                            $bookingpress_payment_status = $bookingpress_payment_log_details['bookingpress_payment_status'];

                            foreach($bookingpress_payment_statuses as $k2 => $v2){
                                if($v2['value'] == $bookingpress_payment_status){
                                    $bookingpress_payment_status_label = $v2['text'];
                                }
                            }
                        }
                        $appointments_data[$k]['bookingpress_payment_status'] = $bookingpress_payment_status;
                        $appointments_data[$k]['bookingpress_payment_status_label'] = $bookingpress_payment_status_label;
                        $bookingpress_payment_method_label = $bookingpress_payment_method;

                        if(!empty($bookingpress_payment_method) && $bookingpress_payment_method == 'on-site' ) {
                            $bookingpress_payment_method_label = $BookingPress->bookingpress_get_customize_settings('locally_text','booking_form');
                        } elseif(!empty($bookingpress_payment_method) && $bookingpress_payment_method != 'manual') {
                            $bookingpress_payment_method_label = $BookingPress->bookingpress_get_customize_settings($bookingpress_payment_method.'_text','booking_form');
                            if(empty($bookingpress_payment_method_label)) {
                                $bookingpress_payment_method_label = $bookingpress_payment_method;
                            }
                        }  
                        $appointments_data[$k]['bookingpress_payment_method'] = $bookingpress_payment_method;
                        $appointments_data[$k]['bookingpress_payment_method_label'] = $bookingpress_payment_method_label;

                        $appointment_status_cls = '';
                        $appointments_data[$k]['bookingpress_payment_status_class'] = $appointment_status_cls;
                                              
                        $appointments_data = apply_filters('bookingpress_modify_my_appointment_data', $appointments_data, $k);
                    }
                }
            }
            $data['customer_details'] = $bookingpress_customer_data;
            $data['items'] = $appointments_data;
            $data['total_records'] = $bookingpress_total_appointments;

            $data = apply_filters('bookingpress_modify_my_appointments_data', $data);
            if($return_data){
                return $data;
            }
            wp_send_json($data);
            exit;
        }
                
        /**
         * Callback function of [bookingpress_appointment_service] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_appointment_service_func( $atts, $content, $tag )
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();
            $bookingpress_short_atts = array(
            'appointment_id' => 0,
            );

            $atts           = shortcode_atts($bookingpress_short_atts, $atts, $tag);
            $appointment_id = $atts['appointment_id'];

            $bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field($_POST['bookingpress_uniq_id']) : '';

            if(!empty($bookingpress_uniq_id)){
                if(!empty($_COOKIE['bookingpress_cart_id'])) {
                    $appointment_id = base64_decode($_COOKIE['bookingpress_cart_id']); //phpcs:ignore
                } else {
                    $bookingpress_cookie_name = $bookingpress_uniq_id."_appointment_data";
                    if(!empty($_COOKIE[$bookingpress_cookie_name])){
                        $bookingpress_cookie_value = sanitize_text_field($_COOKIE[$bookingpress_cookie_name]);
                        $bookingpress_entry_id = base64_decode($bookingpress_cookie_value);

                        if(!empty($bookingpress_entry_id)){
                            $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_appointment_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d",$bookingpress_entry_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm

                            if(!empty($bookingpress_entry_details['bookingpress_appointment_booking_id'])){
                                $appointment_id = $bookingpress_entry_details['bookingpress_appointment_booking_id'];
                            }
                        }
                    }
                }    
            }

            $appointment_data = array();

            $bookingpress_nonce_val = !empty($_GET['bp_tp_nonce']) ? sanitize_text_field($_GET['bp_tp_nonce']) : '';
            $bookingpress_verification_hash = !empty($_GET['appointment_id']) ? md5(base64_decode(sanitize_text_field($_GET['appointment_id']))) : '';
            $bookingpress_nonce_verification = wp_verify_nonce($bookingpress_nonce_val, 'bpa_nonce_url-'.$bookingpress_verification_hash);

            if (empty($appointment_id) && !empty($_GET['appointment_id']) && $bookingpress_nonce_verification) {
                $appointment_id = intval(base64_decode($_GET['appointment_id'])); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_id'] sanitized properly

                
                //$bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm


                $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                if( false == $bookingpress_entry_details ){
                    $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                    wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $bookingpress_entry_details);
                }


                    
                if (! empty($bookingpress_entry_details) ) {
                    $bookingpress_service_id         = $bookingpress_entry_details['bookingpress_service_id'];
                    $bookingpress_appointment_date   = $bookingpress_entry_details['bookingpress_appointment_date'];
                    $bookingpress_appointment_time   = $bookingpress_entry_details['bookingpress_appointment_time'];
                    $bookingpress_appointment_status = $bookingpress_entry_details['bookingpress_appointment_status'];

                    
                    //$appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_appointment_date = %s AND bookingpress_appointment_time = %s AND bookingpress_appointment_status = %s", $bookingpress_service_id, $bookingpress_appointment_date, $bookingpress_appointment_time, $bookingpress_appointment_status ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                    $bookingpress_entry_id = $appointment_id;
                    //$appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                    $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                    if( false == $bookingpress_entry_details ){
                        $appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $appointment_data);
                    }
                    
                    if (empty($appointment_data) ) {
                        // If no appointment data found then display data from entries table.
                        $appointment_data = $bookingpress_entry_details;
                    }
                }
            } else {
                

                if($appointment_id > 0){
                    $appointment_data = wp_cache_get( 'bpa_appointment_data_with_id_'.$appointment_id );
                    if( false == $appointment_data ){
                        $appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_appointment_data_with_id_'.$appointment_id , $appointment_data);
                    }
                }

            }

            $appointment_data = apply_filters('bookingpress_modify_service_shortcode_details', $appointment_data, $appointment_id);

            $content .= '<div class="bookingpress_service_shortcode_container">';
            if (! empty($appointment_data) ) {
                if(empty($appointment_data['bookingpress_service_name'])){
                    foreach($appointment_data as $appointment_data_key => $appointment_data_val){
                        $content .= "<div class='bookingpress_service_name_div'>";
                        $content .= "<span class='bookingpress_service_name'>" . stripslashes_deep(esc_html($appointment_data_val['bookingpress_service_name'])) . '</span>';
                        $content .= '</div><br/>';
                    }
                }else{
                    $content .= "<div class='bookingpress_service_name_div'>";
                    $content .= "<span class='bookingpress_service_name'>" . stripslashes_deep(esc_html($appointment_data['bookingpress_service_name'])) . '</span>';
                    $content .= '</div>';
                }
            }
            $content .= '</div>';

            return do_shortcode($content);
        }
        
        /**
         * Callback function of [bookingpress_appointment_datetime] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_appointment_datetime_func( $atts, $content, $tag )
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries,$bookingpress_global_options;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $bookingpress_short_atts = array(
            'appointment_id' => 0,
            );

            $atts           = shortcode_atts($bookingpress_short_atts, $atts, $tag);
            $appointment_id = $atts['appointment_id'];

            $bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field($_POST['bookingpress_uniq_id']) : '';
            if(!empty($bookingpress_uniq_id)){
                if(!empty($_COOKIE['bookingpress_cart_id'])) {
                    $appointment_id = base64_decode($_COOKIE['bookingpress_cart_id']); //phpcs:ignore
                } else {
                    $bookingpress_cookie_name = $bookingpress_uniq_id."_appointment_data";
                    if(!empty($_COOKIE[$bookingpress_cookie_name])){
                        $bookingpress_cookie_value = sanitize_text_field($_COOKIE[$bookingpress_cookie_name]);
                        $bookingpress_entry_id = base64_decode($bookingpress_cookie_value);

                        if(!empty($bookingpress_entry_id)){
                            $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_appointment_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d",$bookingpress_entry_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm

                            if(!empty($bookingpress_entry_details['bookingpress_appointment_booking_id'])){
                                $appointment_id = $bookingpress_entry_details['bookingpress_appointment_booking_id'];
                            }
                        }
                    }
                }    
            }

            $appointment_data = array();

            $bookingpress_nonce_val = !empty($_GET['bp_tp_nonce']) ? sanitize_text_field($_GET['bp_tp_nonce']) : '';
            $bookingpress_verification_hash = !empty($_GET['appointment_id']) ? md5(base64_decode(sanitize_text_field($_GET['appointment_id']))) : '';
            $bookingpress_nonce_verification = wp_verify_nonce($bookingpress_nonce_val, 'bpa_nonce_url-'.$bookingpress_verification_hash);

            if (empty($appointment_id) && ! empty($_GET['appointment_id']) && $bookingpress_nonce_verification ) {
                $appointment_id = intval(base64_decode($_GET['appointment_id']));// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_id'] sanitized properly
                
                //$bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm

                $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                if( false == $bookingpress_entry_details ){
                    $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                    wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $bookingpress_entry_details);
                }


                
                if (! empty($bookingpress_entry_details) ) {
                    $bookingpress_service_id         = $bookingpress_entry_details['bookingpress_service_id'];
                    $bookingpress_appointment_date   = $bookingpress_entry_details['bookingpress_appointment_date'];
                    $bookingpress_appointment_time   = $bookingpress_entry_details['bookingpress_appointment_time'];
                    $bookingpress_appointment_status = $bookingpress_entry_details['bookingpress_appointment_status'];

                    //$appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_appointment_date = %s AND bookingpress_appointment_time = %s AND bookingpress_appointment_status = %s", $bookingpress_service_id, $bookingpress_appointment_date, $bookingpress_appointment_time, $bookingpress_appointment_status ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                    $bookingpress_entry_id = $appointment_id;
                    //$appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                    $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                    if( false == $bookingpress_entry_details ){
                        $appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $appointment_data);
                    }

                    
                    if (empty($appointment_data) ) {
                        // If no appointment data found then display data from entries table.
                        $appointment_data = $bookingpress_entry_details;
                    }
                }
            } else {
                

                if($appointment_id > 0){
                    $appointment_data = wp_cache_get( 'bpa_appointment_data_with_id_'.$appointment_id );
                    if( false == $appointment_data ){
                        $appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d",$appointment_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_appointment_data_with_id_'.$appointment_id , $appointment_data);
                    }
                }


            }

            $appointment_data = apply_filters('bookingpress_modify_datetime_shortcode_data', $appointment_data, $appointment_id);

            $content .= '<div class="bookinpress-datetime-container">';
            $content = apply_filters('bookingpress_modify_datetime_shortcode_content', $content, $appointment_data);            
            $bookingpress_is_group_datetime = apply_filters('bookingpress_check_group_order_for_thankyou_datetime', 0, $appointment_data);
            
            if ($bookingpress_is_group_datetime == 0 && !empty($appointment_data) ) {
                $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
                $bookingpress_default_date_time_format = $bookingpress_global_options_arr['wp_default_date_format'] . ' ' . $bookingpress_global_options_arr['wp_default_time_format'];

                if(empty($appointment_data['bookingpress_appointment_date'])){
                    foreach($appointment_data as $appointment_data_key => $appointment_data_val){
                        $content .= "<div class='bookingpress_appointment_datetime_div'>";
                        $booked_appointment_datetime = esc_html($appointment_data_val['bookingpress_appointment_date']) . ' ' . esc_html($appointment_data_val['bookingpress_appointment_time']);

                        if(empty($bookingpress_entry_details['bookingpress_customer_timezone'])){
                            $bookingpress_entry_id = !empty($appointment_data_val['bookingpress_entry_id']) ? intval($appointment_data_val['bookingpress_entry_id']) : 0;
                            if(!empty($bookingpress_entry_id)){

                                //Get entries details
                                $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                            }
                        }
                
                        $booked_appointment_datetime = apply_filters( 'bookingpress_appointment_change_to_client_timezone', $booked_appointment_datetime, $bookingpress_entry_details['bookingpress_customer_timezone'], $bookingpress_entry_details );
                        
                        $booked_appointment_date = date($bookingpress_default_date_time_format, strtotime($booked_appointment_datetime));
                        
                        $content .= "<span class='bookingpress_appointment_datetime'>" . $booked_appointment_date . '</span>';
                        $content .= '</div><br/>';
                    }
                }else{
                    $booked_appointment_datetime = esc_html($appointment_data['bookingpress_appointment_date']) . ' ' . esc_html($appointment_data['bookingpress_appointment_time']);

                    if(empty($bookingpress_entry_details['bookingpress_customer_timezone'])){
                        $bookingpress_entry_id = !empty($appointment_data['bookingpress_entry_id']) ? intval($appointment_data['bookingpress_entry_id']) : 0;
                        if(!empty($bookingpress_entry_id)){

                            //Get entries details
                            $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                        }
                    }
                    
                    $booked_appointment_datetime = apply_filters( 'bookingpress_appointment_change_to_client_timezone', $booked_appointment_datetime, $bookingpress_entry_details['bookingpress_customer_timezone'], $bookingpress_entry_details );
                    
                    $booked_appointment_date = date_i18n($bookingpress_default_date_time_format, strtotime($booked_appointment_datetime));
                    $content .= "<div class='bookingpress_appointment_datetime_div'>";
                    $content .= "<span class='bookingpress_appointment_datetime'>" . $booked_appointment_date . '</span>';
                    $content .= '</div>';
                }
            }
            $content .= '</div>';

            return do_shortcode($content);
        }
        
        /**
         * Callback function of [booking_id] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_booking_id_func($atts, $content, $tag){
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries, $tbl_bookingpress_payment_logs;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $bookingpress_short_atts = array(
                'appointment_id' => 0,
            );

            $atts           = shortcode_atts($bookingpress_short_atts, $atts, $tag);
            $appointment_id = $atts['appointment_id'];

            $entry_id = 0;

            $bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field($_POST['bookingpress_uniq_id']) : '';
            if(!empty($bookingpress_uniq_id)){
                if(!empty($_COOKIE['bookingpress_cart_id'])) {
                    $appointment_id = base64_decode($_COOKIE['bookingpress_cart_id']); //phpcs:ignore
                    if(!empty($appointment_id)){
                        $bookingpress_appointment_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_order_id = %d", $appointment_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $bookingpress_appointment_details is table name defined globally. False Positive alarm
                        if(!empty($bookingpress_appointment_details)){
                            $content .= '#'.$bookingpress_appointment_details['bookingpress_booking_id'];    
                            return do_shortcode($content);        
                        }
                    }                    
                } else {
                    $bookingpress_cookie_name = $bookingpress_uniq_id."_appointment_data";
                    if(!empty($_COOKIE[$bookingpress_cookie_name])){
                        $bookingpress_cookie_value = sanitize_text_field($_COOKIE[$bookingpress_cookie_name]);
                        $entry_id = base64_decode($bookingpress_cookie_value);
                    }
                }    
            }
            
            if(empty($appointment_id) && !empty($_GET['appointment_id']) ){
                $entry_id = intval(base64_decode($_GET['appointment_id']));// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_id'] sanitized properly

                $bookingpress_nonce_val = !empty($_GET['bp_tp_nonce']) ? sanitize_text_field($_GET['bp_tp_nonce']) : '';
                $bookingpress_verification_hash = !empty($_GET['appointment_id']) ? md5(base64_decode(sanitize_text_field($_GET['appointment_id']))) : '';
                $bookingpress_nonce_verification = wp_verify_nonce($bookingpress_nonce_val, 'bpa_nonce_url-'.$bookingpress_verification_hash);
                
                if(!$bookingpress_nonce_verification){
                    return do_shortcode($content);
                }
            }

            

            $appointment_data  = array();
            $bookingpress_booking_id = '';
            
            if(!empty($entry_id)){
                //Get appointment details
                $bookingpress_appointment_details = $wpdb->get_row($wpdb->prepare("SELECT bookingpress_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                
                $bookingpress_booking_id = !empty($bookingpress_appointment_details['bookingpress_booking_id']) ? $bookingpress_appointment_details['bookingpress_booking_id'] : '';
            }

            $bookingpress_booking_id = apply_filters('bookingpress_modify_booking_id_shortcode_data', $bookingpress_booking_id, $entry_id);
            
            if(!empty($bookingpress_booking_id)){
                $content .= '#'.$bookingpress_booking_id;
            }
            return do_shortcode($content);
        }
        
        /**
         * Callback function of [bookingpress_appointment_customername] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_appointment_customername_func( $atts, $content, $tag )
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $bookingpress_short_atts = array(
            'appointment_id' => 0,
            );

            $atts           = shortcode_atts($bookingpress_short_atts, $atts, $tag);
            $appointment_id = $atts['appointment_id'];

            $bookingpress_uniq_id = !empty($_POST['bookingpress_uniq_id']) ? sanitize_text_field($_POST['bookingpress_uniq_id']) : '';

            if(!empty($bookingpress_uniq_id)){
                if(!empty($_COOKIE['bookingpress_cart_id'])) {
                    $appointment_id = base64_decode($_COOKIE['bookingpress_cart_id']); //phpcs:ignore
                } else {
                    $bookingpress_cookie_name = $bookingpress_uniq_id."_appointment_data";
                    if(!empty($_COOKIE[$bookingpress_cookie_name])){
                        $bookingpress_cookie_value = sanitize_text_field($_COOKIE[$bookingpress_cookie_name]);
                        $bookingpress_entry_id = base64_decode($bookingpress_cookie_value);
                        if(!empty($bookingpress_entry_id)){
                            $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT bookingpress_appointment_booking_id FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d",$bookingpress_entry_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm

                            if(!empty($bookingpress_entry_details['bookingpress_appointment_booking_id'])){
                                $appointment_id = $bookingpress_entry_details['bookingpress_appointment_booking_id'];
                            }
                        }
                    }
                }
            }            

            $appointment_data  = array();

            $bookingpress_nonce_val = !empty($_GET['bp_tp_nonce']) ? sanitize_text_field($_GET['bp_tp_nonce']) : '';
            $bookingpress_verification_hash = !empty($_GET['appointment_id']) ? md5(base64_decode(sanitize_text_field($_GET['appointment_id']))) : '';
            $bookingpress_nonce_verification = wp_verify_nonce($bookingpress_nonce_val, 'bpa_nonce_url-'.$bookingpress_verification_hash);

            $customer_fullname = '';
            if (empty($appointment_id) && ! empty($_GET['appointment_id']) && $bookingpress_nonce_verification ) {
                $appointment_id = intval(base64_decode($_GET['appointment_id']));// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_id'] sanitized properly
                
                //$bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                if( false == $bookingpress_entry_details ){
                    $bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d",$appointment_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_entries is table name defined globally. False Positive alarm
                    wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $bookingpress_entry_details);
                }


                if (! empty($bookingpress_entry_details) ) {
                    $bookingpress_service_id         = $bookingpress_entry_details['bookingpress_service_id'];
                    $bookingpress_appointment_date   = $bookingpress_entry_details['bookingpress_appointment_date'];
                    $bookingpress_appointment_time   = $bookingpress_entry_details['bookingpress_appointment_time'];
                    $bookingpress_appointment_status = $bookingpress_entry_details['bookingpress_appointment_status'];

                    //$appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_appointment_date = %s AND bookingpress_appointment_time = %s AND bookingpress_appointment_status = %s", $bookingpress_service_id, $bookingpress_appointment_date, $bookingpress_appointment_time, $bookingpress_appointment_status ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm

                    $bookingpress_entry_id = $appointment_id;
                    //$appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                    $bookingpress_entry_details = wp_cache_get( 'bpa_bookingpress_entry_details_id_'.$appointment_id );
                    if( false == $bookingpress_entry_details ){
                        $appointment_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_entry_id = %d", $bookingpress_entry_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_bookingpress_entry_details_id_'.$appointment_id , $appointment_data);
                    }

                    if (empty($appointment_data) ) {
                        // If no data found from appointments then display data from entries table.
                        $appointment_data = $bookingpress_entry_details;
                    }
                }
            } else {
                
                if($appointment_id > 0){
                    $appointment_data = wp_cache_get( 'bpa_appointment_data_with_id_'.$appointment_id );
                    if( false == $appointment_data ){
                        $appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_booking_id = %d",$appointment_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_appointment_bookings is table name defined globally. False Positive alarm
                        wp_cache_set( 'bpa_appointment_data_with_id_'.$appointment_id , $appointment_data);
                    }
                }


            }

            $appointment_data = apply_filters('bookingpress_modify_customer_details_shortcode_data', $appointment_data, $appointment_id);

            $content .= "<div class='bookingpress-appointment-customer-container'>";
            if (! empty($appointment_data) ) {

                $customer_firstname = ! empty($appointment_data['bookingpress_customer_firstname']) ? $appointment_data['bookingpress_customer_firstname'] : '';
                $customer_lastname  = ! empty($appointment_data['bookingpress_customer_lastname']) ? $appointment_data['bookingpress_customer_lastname'] : '';
                $customer_email     = ! empty($appointment_data['bookingpress_customer_email']) ? $appointment_data['bookingpress_customer_email'] : '';                
                $customer_fullname = !empty($appointment_data['bookingpress_customer_name']) ? $appointment_data['bookingpress_customer_name'] : ($customer_firstname . ' ' . $customer_lastname);
                if(empty($appointment_data['bookingpress_customer_name']) && empty($customer_firstname) && empty($customer_lastname) ) {
                    $customer_fullname = $customer_email;
                }
                $content .= "<div class='bookingpress_appointment_customername_div'>";
                $content .= "<span class='bookingpress_appointment_customername'>" . stripslashes_deep(esc_html($customer_fullname)) . '</span>';
                $content .= '</div>';
            }
            $content .= "</div>";

            return do_shortcode($content);
        }
                
        /**
         * Callback function of [bookingpress_company_avatar] shortcode
         *
         * @return void
         */
        function bookingpress_company_avatar_func()
        {
            global $BookingPress;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $content                         = '';
            $bookingpress_company_avatar_url = $BookingPress->bookingpress_get_settings('company_avatar_url', 'company_setting');
            if ($bookingpress_company_avatar_url != '' ) {
                $bookingpress_company_avatar_url = esc_url($bookingpress_company_avatar_url);
                $content                         = '<img src=' . $bookingpress_company_avatar_url . ' width=100 height=100 >';
            } else {
                $content = esc_html_e('Company avatar not found', 'bookingpress-appointment-booking');
            }
            return do_shortcode($content);
        }
                
        /**
         * Callback function of [bookingpress_company_name] shortcode
         *
         * @return void
         */
        function bookingpress_company_name_func()
        {
            global $BookingPress;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $content                   = '';
            $bookingpress_company_name = $BookingPress->bookingpress_get_settings('company_name', 'company_setting');
            if ($bookingpress_company_name != '' ) {
                $content = esc_html($bookingpress_company_name);
            } else {
                $content = esc_html_e('Company name not found', 'bookingpress-appointment-booking');
            }
            return do_shortcode($content);
        }
                
        /**
         * Callback function of [bookingpress_company_website] shortcode
         *
         * @return void
         */
        function bookingpress_company_website_func()
        {
            global $BookingPress;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $content                      = '';
            $bookingpress_company_website = $BookingPress->bookingpress_get_settings('company_website', 'company_setting');
            if ($bookingpress_company_website != '' ) {
                $content = esc_html($bookingpress_company_website);
            } else {
                $content = esc_html_e('Company website name not found', 'bookingpress-appointment-booking');
            }
            return do_shortcode($content);
        }

        
        /**
         * Callback function of [bookingpress_company_address] shortcode
         *
         * @return void
         */
        function bookingpress_company_address_func()
        {
            global $BookingPress;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $content                      = '';
            $bookingpress_company_address = $BookingPress->bookingpress_get_settings('company_address', 'company_setting');
            if ($bookingpress_company_address != '' ) {
                $content = esc_html($bookingpress_company_address);
            } else {
                $content = esc_html_e('Company address not found', 'bookingpress-appointment-booking');
            }
            return do_shortcode($content);
        }
                
        /**
         * Callback function of [bookingpress_company_phone] shortcode
         *
         * @return void
         */
        function bookingpress_company_phone_func()
        {
            global $BookingPress;
            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $content                      = '';
            $bookingpress_company_phone   = $BookingPress->bookingpress_get_settings('company_phone_number', 'company_setting');
            $bookingpress_company_country = $BookingPress->bookingpress_get_settings('company_phone_country', 'company_setting');

            if ($bookingpress_company_phone != '' ) {
                $content = esc_html($bookingpress_company_phone);
            } else {
                $content = esc_html_e('Company phone number not found', 'bookingpress-appointment-booking');
            }
            return do_shortcode($content);
        }
 
        /**
         * Function for add/update appointment
         *
         * @return void
         */
        function bookingpress_save_appointment_booking_func()
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_services, $tbl_bookingpress_customer_bookings, $tbl_bookingpress_customers, $bookingpress_payment_gateways, $bookingpress_debug_payment_log_id;
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                wp_send_json($response);
                die();
            }
            $response['variant']       = 'error';
            $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
            $response['msg']           = esc_html__('Something went wrong..', 'bookingpress-appointment-booking');
            $response['is_redirect']   = 0;
            $response['redirect_data'] = '';
            $response['is_spam']       = 1;

            if( !empty( $_REQUEST['appointment_data'] ) && !is_array( $_REQUEST['appointment_data'] ) ){
                $_REQUEST['appointment_data'] = json_decode( stripslashes_deep( $_REQUEST['appointment_data'] ), true ); //phpcs:ignore
                $_POST['appointment_data'] = $_REQUEST['appointment_data'] =  !empty($_REQUEST['appointment_data']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_REQUEST['appointment_data'] ) : array(); // phpcs:ignore
            }

            $response = apply_filters('bookingpress_validate_spam_protection', $response, array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['appointment_data'])); // phpcs:ignore

            $booking_response = $this->bookingpress_before_book_appointment_func();

            if( !empty( $booking_response ) ){
                $booking_response_arr = json_decode( $booking_response, true );                
                if( !empty( $booking_response_arr['variant'] ) && 'error' == $booking_response_arr['variant'] ){
                    if(!empty($booking_response_arr['msg'])) {
                        $booking_response_arr['msg'] = stripslashes_deep(html_entity_decode($booking_response_arr['msg'],ENT_QUOTES));
                    }                                     
                    wp_send_json($booking_response_arr);
                    die;
                }
            }

            $appointment_booked_successfully = $BookingPress->bookingpress_get_settings('appointment_booked_successfully', 'message_setting');

            if (! empty($_REQUEST) && ! empty($_REQUEST['appointment_data']) ) {
             
                $bookingpress_appointment_data            = array_map(array( $BookingPress, 'appointment_sanatize_field' ), $_REQUEST['appointment_data']); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized --Reason - $_GET['appointment_data'] contains mixed array and sanitized properly using 'appointment_sanatize_field' function
                $bookingpress_payment_gateway             = ! empty($bookingpress_appointment_data['selected_payment_method']) ? $bookingpress_appointment_data['selected_payment_method'] : '';
                $bookingpress_appointment_on_site_enabled = ( $bookingpress_appointment_data['selected_payment_method'] == 'onsite' ) ? 1 : 0;
                $payment_gateway                          = ( $bookingpress_appointment_on_site_enabled ) ? 'on-site' : $bookingpress_payment_gateway;

                $bookingpress_service_price = isset($bookingpress_appointment_data['service_price_without_currency']) ? floatval($bookingpress_appointment_data['service_price_without_currency']) : 0;
                if ($bookingpress_service_price == 0 ) {
                    $payment_gateway = ' - ';
                }

                $bpa_selected_service = $bookingpress_appointment_data['selected_service'];

                $bpa_service_data               = $BookingPress->get_service_by_id( $bpa_selected_service );
                $bpa_service_amount             = ! empty($bpa_service_data['bookingpress_service_price']) ? (float) $bpa_service_data['bookingpress_service_price'] : 0;

                if( $bpa_service_amount != $bookingpress_service_price ){
                    $bookingpress_invalid_amount = esc_html__('Sorry! Appointment could not be processed', 'bookingpress-appointment-booking');

                    $response['variant']       = 'error';
                    $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
                    $response['msg']           = $bookingpress_invalid_amount;
                    $response['is_redirect']   = 0;
                    $response['reason']        = 'price mismatched ' . $bpa_service_amount . ' --- ' . $bookingpress_service_price;
                    $response['redirect_data'] = '';
                    $response['is_spam']       = 0;

                    echo json_encode($response);
                    exit;
                }

                $bookingpress_return_data = apply_filters('bookingpress_validate_submitted_form', $payment_gateway, $bookingpress_appointment_data);

                if ($payment_gateway == 'on-site' && $bookingpress_service_price > 0 ) {
                    $entry_id = ! empty($bookingpress_return_data['entry_id']) ? $bookingpress_return_data['entry_id'] : 0;
                    $bookingpress_appointment_status = $BookingPress->bookingpress_get_settings('onsite_appointment_status', 'general_setting');

                    if($bookingpress_appointment_status ==  '1' ) {               
                        $bookingpress_payment_gateways->bookingpress_confirm_booking($entry_id, array(), '1', '', '', 1);
                        $bookingpress_redirect_url = $bookingpress_return_data['approved_appointment_url'];
                    } else {                    
                        $bookingpress_payment_gateways->bookingpress_confirm_booking($entry_id, array(), '2', '', '', 1);
                        $bookingpress_redirect_url = $bookingpress_return_data['pending_appointment_url'];
                    }
                    if (! empty($bookingpress_redirect_url) ) {
                        $response['variant']       = 'redirect_url';
                        $response['title']         = '';
                        $response['msg']           = '';
                        $response['is_redirect']   = 1;
                        $response['redirect_data'] = $bookingpress_redirect_url;
                    } else {
                        $response['variant'] = 'success';
                        $response['title']   = esc_html__('Success', 'bookingpress-appointment-booking');
                        $response['msg']     = esc_html($appointment_booked_successfully);
                    }
                } elseif ($bookingpress_service_price == 0 ) {
                    $entry_id = ! empty($bookingpress_return_data['entry_id']) ? $bookingpress_return_data['entry_id'] : 0;
                    $bookingpress_payment_gateways->bookingpress_confirm_booking($entry_id, array(), '1', '', '', 1);

                    $redirect_url                    = $bookingpress_return_data['approved_appointment_url'];
                    $bookingpress_appointment_status = $BookingPress->bookingpress_get_settings('appointment_status', 'general_setting');
                    if ($bookingpress_appointment_status == 'Pending' ) {
                        $redirect_url = $bookingpress_return_data['pending_appointment_url'];
                    }

                    $bookingpress_redirect_url = $redirect_url;
                    if (! empty($bookingpress_redirect_url) ) {
                        $response['variant']       = 'redirect_url';
                        $response['title']         = '';
                        $response['msg']           = '';
                        $response['is_redirect']   = 1;
                        $response['redirect_data'] = $bookingpress_redirect_url;
                    } else {
                        $response['variant'] = 'success';
                        $response['title']   = esc_html__('Success', 'bookingpress-appointment-booking');
                        $response['msg']     = esc_html($appointment_booked_successfully);
                    }
                } else {
                    if ($payment_gateway == 'paypal' ) {
                        $bookingpress_payment_mode    = $BookingPress->bookingpress_get_settings('paypal_payment_mode', 'payment_setting');
                        $bookingpress_is_sandbox_mode = ( $bookingpress_payment_mode != 'live' ) ? true : false;
                        $bookingpress_gateway_status  = $BookingPress->bookingpress_get_settings('paypal_payment', 'payment_setting');
                        $bookingpress_merchant_email  = $BookingPress->bookingpress_get_settings('paypal_merchant_email', 'payment_setting');
                        $bookingpress_api_username    = $BookingPress->bookingpress_get_settings('paypal_api_username', 'payment_setting');
                        $bookingpress_api_password    = $BookingPress->bookingpress_get_settings('paypal_api_password', 'payment_setting');
                        $bookingpress_api_signature   = $BookingPress->bookingpress_get_settings('paypal_api_signature', 'payment_setting');

                        $bookingpress_paypal_error_msg  = esc_html__('PayPal Configuration Error', 'bookingpress-appointment-booking');
                        $bookingpress_paypal_error_msg .= ': ';
                        if (empty($bookingpress_merchant_email) ) {
                               $bookingpress_paypal_error_msg .= esc_html__('Please configure merchant email address', 'bookingpress-appointment-booking');

                               $response['variant']       = 'error';
                               $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
                               $response['msg']           = $bookingpress_paypal_error_msg;
                               $response['is_redirect']   = 0;
                               $response['redirect_data'] = '';
                               $response['is_spam']       = 0;

                               echo json_encode($response);
                               exit;
                        }

                        if (empty($bookingpress_api_username) ) {
                            $bookingpress_paypal_error_msg .= esc_html__('Please configure PayPal API Username', 'bookingpress-appointment-booking');

                            $response['variant']       = 'error';
                            $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
                            $response['msg']           = $bookingpress_paypal_error_msg;
                            $response['is_redirect']   = 0;
                            $response['redirect_data'] = '';
                            $response['is_spam']       = 0;

                            echo json_encode($response);
                            exit;
                        }

                        if (empty($bookingpress_api_password) ) {
                            $bookingpress_paypal_error_msg .= esc_html__('Please configure PayPal API Password', 'bookingpress-appointment-booking');

                            $response['variant']       = 'error';
                            $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
                            $response['msg']           = $bookingpress_paypal_error_msg;
                            $response['is_redirect']   = 0;
                            $response['redirect_data'] = '';
                            $response['is_spam']       = 0;

                            echo json_encode($response);
                            exit;
                        }

                        if (empty($bookingpress_api_signature) ) {
                            $bookingpress_paypal_error_msg .= esc_html__('Please configure PayPal API Signature', 'bookingpress-appointment-booking');

                            $response['variant']       = 'error';
                            $response['title']         = esc_html__('Error', 'bookingpress-appointment-booking');
                            $response['msg']           = $bookingpress_paypal_error_msg;
                            $response['is_redirect']   = 0;
                            $response['redirect_data'] = '';
                            $response['is_spam']       = 0;

                            echo json_encode($response);
                            exit;
                        }

                        $entry_id                          = $bookingpress_return_data['entry_id'];
                        $currency                          = $bookingpress_return_data['currency'];
                        $currency_symbol                   = $BookingPress->bookingpress_get_currency_code($currency);
                        $bookingpress_final_payable_amount = isset($bookingpress_return_data['payable_amount']) ? $bookingpress_return_data['payable_amount'] : 0;
                        $customer_details                  = $bookingpress_return_data['customer_details'];
                        $customer_email                    = ! empty($customer_details['customer_email']) ? $customer_details['customer_email'] : '';

                        $bookingpress_service_name = ! empty($bookingpress_return_data['service_data']['bookingpress_service_name']) ? $bookingpress_return_data['service_data']['bookingpress_service_name'] : __('Appointment Booking', 'bookingpress-appointment-booking');

                        $custom_var = $entry_id;

                        $sandbox = $bookingpress_is_sandbox_mode ? 'sandbox.' : '';

                        $notify_url = $bookingpress_return_data['notify_url'];

                        $redirect_url                    = $bookingpress_return_data['approved_appointment_url'];
                        $bookingpress_appointment_status = $BookingPress->bookingpress_get_settings('appointment_status', 'general_setting');
                        if ($bookingpress_appointment_status == 'Pending' ) {
                            $redirect_url = $bookingpress_return_data['pending_appointment_url'];
                        }

                        $bookingpress_paypal_cancel_url_id = $BookingPress->bookingpress_get_customize_settings('after_failed_payment_redirection', 'booking_form');
                        $bookingpress_paypal_cancel_url = get_permalink($bookingpress_paypal_cancel_url_id);
                        $cancel_url                     = ! empty($bookingpress_paypal_cancel_url) ? $bookingpress_paypal_cancel_url : BOOKINGPRESS_HOME_URL;
                        $cancel_url                     = add_query_arg('is_cancel', 1, esc_url($cancel_url));

                        $cmd          = '_xclick';
                        $paypal_form  = '<form name="_xclick" id="bookingpress_paypal_form" action="https://www.' . $sandbox . 'paypal.com/cgi-bin/webscr" method="post">';
                        $paypal_form .= '<input type="hidden" name="cmd" value="' . $cmd . '" />';
                        $paypal_form .= '<input type="hidden" name="amount" value="' . $bookingpress_final_payable_amount . '" />';
                        $paypal_form .= '<input type="hidden" name="business" value="' . $bookingpress_merchant_email . '" />';
                        $paypal_form .= '<input type="hidden" name="notify_url" value="' . $notify_url . '" />';
                        $paypal_form .= '<input type="hidden" name="cancel_return" value="' . $cancel_url . '" />';
                        $paypal_form .= '<input type="hidden" name="return" value="' . $redirect_url . '" />';
                        $paypal_form .= '<input type="hidden" name="rm" value="2" />';
                        $paypal_form .= '<input type="hidden" name="lc" value="en_US" />';
                        $paypal_form .= '<input type="hidden" name="no_shipping" value="1" />';
                        $paypal_form .= '<input type="hidden" name="custom" value="' . $custom_var . '" />';
                        $paypal_form .= '<input type="hidden" name="on0" value="user_email" />';
                        $paypal_form .= '<input type="hidden" name="os0" value="' . $customer_email . '" />';
                        $paypal_form .= '<input type="hidden" name="currency_code" value="' . $currency_symbol . '" />';
                        $paypal_form .= '<input type="hidden" name="page_style" value="primary" />';
                        $paypal_form .= '<input type="hidden" name="charset" value="UTF-8" />';
                        $paypal_form .= '<input type="hidden" name="item_name" value="' . $bookingpress_service_name . '" />';
                        $paypal_form .= '<input type="hidden" name="item_number" value="1" />';
                        $paypal_form .= '<input type="submit" value="Pay with PayPal!" />';
                        $paypal_form .= '</form>';

                        do_action('bookingpress_payment_log_entry', 'paypal', 'payment form redirected data', 'bookingpress', $paypal_form, $bookingpress_debug_payment_log_id);

                        $paypal_form .= '<script type="text/javascript">document.getElementById("bookingpress_paypal_form").submit();</script>';

                        $response['variant']       = 'redirect';
                        $response['title']         = '';
                        $response['msg']           = '';
                        $response['is_redirect']   = 1;
                        $response['redirect_data'] = $paypal_form;
                        $response['entry_id']      = $entry_id;
                    }
                }
            }

            echo json_encode($response);
            exit();
        }

                
        /**
         * Function for retrieve booking time slots
         *
         * @param  mixed $selected_date          Pass selected date
         * @param  mixed $return                 If paramter set to true then timeslots data will return
         * @param  mixed $check_for_whole_days   Time should be check for whole day or not
         * @param  mixed $check_only_one_slot    Check only for one available slots or not 
         * @return void
         */
        function bookingpress_retrieve_timeslots( $selected_date = '' , $return = false, $check_for_whole_days = false, $check_only_one_slot = false ){
            
            global $wpdb, $BookingPress, $tbl_bookingpress_services, $bookingpress_global_options, $bookingpress_other_debug_log_id, $tbl_bookingpress_appointment_bookings, $bookingpress_services, $bookingpress_other_debug_log_id;

            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                echo json_encode($response);
                exit();
            }
            if(!empty($_POST['appointment_data_obj']) && !is_array($_POST['appointment_data_obj'])) {
                $_POST['appointment_data_obj'] = !empty( $_POST['appointment_data_obj'] ) ? json_decode( stripslashes_deep( $_POST['appointment_data_obj'] ), true ) : array(); //phpcs:ignore
				$_POST['appointment_data_obj'] =  !empty($_POST['appointment_data_obj']) ? array_map(array($this,'bookingpress_boolean_type_cast'), $_POST['appointment_data_obj'] ) : array(); // phpcs:ignore
            }

            $selected_service_id = ! empty($_POST['service_id']) ? intval($_POST['service_id']) : 0;
            if( empty( $selected_date ) ){
                $selected_date       = ! empty($_POST['selected_date']) ? date('Y-m-d', strtotime(sanitize_text_field($_POST['selected_date']))) : date('Y-m-d',current_time('timestamp'));
            }
            $service_timings = array();          

            $service_timings_data = array(
                'is_daysoff' => false,
                'service_timings' => array()
            );
            
            $bookingpress_form_token = (isset($_POST['appointment_data_obj']['bookingpress_form_token']))?sanitize_text_field($_POST['appointment_data_obj']['bookingpress_form_token']):'';
            if(empty($bookingpress_form_token)){
                $bookingpress_form_token = (isset($_POST['appointment_data_obj']['bookingpress_uniq_id']))?sanitize_text_field($_POST['appointment_data_obj']['bookingpress_uniq_id']):'';    
            }
            //$bookingpress_form_token = !empty( $_POST['appointment_data_obj']['bookingpress_form_token'] ) ? sanitize_text_field( $_POST['appointment_data_obj']['bookingpress_form_token'] ) : sanitize_text_field( $_POST['appointment_data_obj']['bookingpress_uniq_id'] ) ;

            $no_timeslots_available = $BookingPress->bookingpress_get_settings('no_timeslots_available', 'message_setting');

            /** filter to check minimum time requirement */
            $minimum_time_required = 'disabled';
            $minimum_time_required = apply_filters( 'bookingpress_retrieve_minimum_required_time', $minimum_time_required, $selected_service_id );

            /** Check for the available capacity */
            $max_service_capacity = 1;
            $max_service_capacity = apply_filters( 'bookingpress_retrieve_capacity', $max_service_capacity, $selected_service_id );
            
            /** timeslot steps settings */
            $bookingpress_show_time_as_per_service_duration = $BookingPress->bookingpress_get_settings( 'show_time_as_per_service_duration', 'general_setting' );
            $bookingpress_shared_service_timeslot = $BookingPress->bookingpress_get_settings('share_timeslot_between_services', 'general_setting');
            /** total booked appointment of the selected date */
            $where_clause = '';
            if( 'true' != $bookingpress_shared_service_timeslot ){
                $where_clause = $wpdb->prepare( ' AND bookingpress_service_id = %d ', $selected_service_id );
                $where_clause = apply_filters( 'bookingpress_booked_appointment_where_clause', $where_clause );
            }else{                
                $where_clause = apply_filters( 'bookingpress_booked_appointment_with_share_timeslot_where_clause_check', $where_clause,$selected_service_id);
            }

            $where_clause .= $wpdb->prepare( ' AND (bookingpress_appointment_status = %s OR bookingpress_appointment_status = %s)', '1', '2' );

            $bookingpress_hide_already_booked_slot = $BookingPress->bookingpress_get_customize_settings( 'hide_already_booked_slot', 'booking_form' );
            $bookingpress_hide_already_booked_slot = ( $bookingpress_hide_already_booked_slot == 'true' ) ? 1 : 0;
            $bookingpress_hide_already_booked_slot = apply_filters( 'bookingpress_change_hide_already_booked_slot_for_service', $bookingpress_hide_already_booked_slot, $selected_service_id);
                

            $bpa_appointment_edit_id = !empty( $_POST['appointment_data_obj']['appointment_update_id'] ) ? intval( $_POST['appointment_data_obj']['appointment_update_id'] ) : 0;

            if( !empty( $bpa_appointment_edit_id ) ){
                $where_clause .= $wpdb->prepare( ' AND bookingpress_appointment_booking_id != %d', $bpa_appointment_edit_id );
            }

            $total_booked_appiontments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_appointment_date = %s $where_clause", $selected_date), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm

            

            if( empty( $total_booked_appiontments ) ){
                wp_cache_set( 'bpa_total_booked_appointment_' . $selected_date,  'empty_data' );
            } else {
                wp_cache_set( 'bpa_total_booked_appointment_' . $selected_date,  $total_booked_appiontments );
            }

            $shared_quantity = apply_filters('bookingpress_get_shared_capacity_data', 'true' );

            /** service buffer times ends */

            /** retrieving staff member and service time slots */
            $service_timings_data = apply_filters('bookingpress_retrieve_pro_modules_timeslots', $service_timings_data, $selected_service_id, $selected_date, $minimum_time_required, $max_service_capacity, $bookingpress_show_time_as_per_service_duration );

            if( empty( $service_timings_data['service_timings'] ) && false == $service_timings_data['is_daysoff'] ){
                $service_timings = $BookingPress->bookinpgress_retrieve_default_workhours($selected_service_id, $selected_date, $minimum_time_required, $max_service_capacity, $bookingpress_show_time_as_per_service_duration);
            } else {
                $service_timings = $service_timings_data['service_timings'];
            }
            
            if( true == $check_only_one_slot ){
                if( 0 < count( $service_timings ) ){
                    return true;
                } else {
                    return false;
                }
            }

            wp_cache_delete( 'bpa_total_booked_appointment_' . $selected_date );

            $selected_service_duration_unit = !empty( $_POST['appointment_data_obj']['selected_service_duration_unit']) ? sanitize_text_field($_POST['appointment_data_obj']['selected_service_duration_unit']) : '';
            $selected_service_duration_val = !empty( $_POST['appointment_data_obj']['selected_service_duration'] ) ? intval( $_POST['appointment_data_obj']['selected_service_duration'] ) : '';

            
            $total_booked_appiontments = apply_filters( 'bookingpress_modify_booked_appointment_data', $total_booked_appiontments, $selected_date, $service_timings, $selected_service_id );
            
            
            /** Remove Booked Time Slots from the final service timings - start */
            
            if( !empty( $total_booked_appiontments ) && 'd' == $selected_service_duration_unit && 1 == $selected_service_duration_val && empty( $service_timings ) ){

                $service_timings = array(
                    array(
                        'start_time' => '00:00',
                        'end_time' => '00:00',
                        'store_start_time' => '00:00',
                        'store_end_time' => '00:00',
                        'break_start_time' => '',
                        'break_end_time' => '',
                        'store_service_date' => $selected_date,
                        'is_booked' => 0,
                        'max_capacity' => $max_service_capacity,
                        'total_booked' => ''
                    )
                );
            }

            $bpa_remove_crossed_time = apply_filters( 'bookingpress_remove_crossed_timeslot', true);
            
            $booked_timing_keys = array();
            
            if( !empty( $total_booked_appiontments ) && !empty( $service_timings ) ){
                foreach( $total_booked_appiontments as $booked_appointment_data ){
                    $total_guests = 0;
                    
                    $booked_appointment_start_time = $booked_appointment_data['bookingpress_appointment_time'];
                    $booked_appointment_end_time = $booked_appointment_data['bookingpress_appointment_end_time'];

                    if( '00:00:00' == $booked_appointment_end_time ){
                        $booked_appointment_end_time = '24:00:00';
                    }
                    
                    foreach( $service_timings as $sk => $time_slot_data ){
                        $current_time_start = $time_slot_data['store_start_time'].':00';
                        $current_time_end = $time_slot_data['store_end_time'].':00';
                        if( ( $booked_appointment_start_time >= $current_time_start && $booked_appointment_end_time <= $current_time_end ) || ( $booked_appointment_start_time < $current_time_end && $booked_appointment_end_time > $current_time_start) ){

                            $service_timings[ $sk ]['total_booked']++;
                            $capacity_count = 1;
                            /** increase capacity count if booked appointment has the extra members */                            

                            $extra_members = !empty($booked_appointment_data['bookingpress_selected_extra_members'] ) ? intval($booked_appointment_data['bookingpress_selected_extra_members']) : 0;
                            
                            if( $BookingPress->bpa_is_pro_exists() && $BookingPress->bpa_is_pro_active() ){
                                if( !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '1.5', '>' ) ){
                                    $extra_members = $extra_members - 1;
                                }
                            }

                            if( isset($booked_appointment_data['bookingpress_selected_extra_members']) && $booked_appointment_data['bookingpress_selected_extra_members'] > 0 ){
                                $capacity_count += $extra_members;
                                $total_guests = $extra_members;
                                $service_timings[ $sk ]['guest_members'] = $total_guests;
                            } else {                                
                                $service_timings[ $sk ]['guest_members'] = $total_guests;
                            }
                            
                            if( 'true' == $shared_quantity ){
                                $service_timings[ $sk ]['max_capacity'] -= $capacity_count;
                                if( $service_timings[ $sk ]['max_capacity'] < 0 ){
                                    $service_timings[ $sk ]['max_capacity'] = 0;
                                }
                                $service_timings[ $sk ]['is_reduced_capacity'] = true;
                            } else {
                                if( $booked_appointment_start_time == $current_time_start && $booked_appointment_end_time == $current_time_end ){
                                    
                                    $service_timings[ $sk ]['max_capacity'] -= $capacity_count; // reduce capacity for exact time slot
                                    if( $service_timings[ $sk ]['max_capacity'] < 0 ){
                                        $service_timings[ $sk ]['max_capacity'] = 0;
                                    }

                                    if( 0 == $service_timings[ $sk ]['max_capacity'] ){
                                        $service_timings[ $sk ]['is_booked'] = 1;
                                    }
                                    $booked_timing_keys[ $booked_appointment_data['bookingpress_appointment_booking_id'] ] = $sk;
                                } else {
                                    /** Removed time slot for booking if the booked appointment's time slots are crossed between time slots and capacity is not sharing */
                                    if( true == $bpa_remove_crossed_time ){
                                        unset( $service_timings[ $sk ] );
                                    }
                                }
                            }

                            /** Filter to Check BAWY */
                            $service_timings = apply_filters( 'bookingpress_modify_timeslot_data_for_bawy', $service_timings, $sk );
                            
                            /** shared timeslot */
                            
                            if( 'true' == $bookingpress_shared_service_timeslot && !empty( $service_timings[$sk] )  ){
                                if( empty( $service_timings[ $sk ]['reason_for_not_available'] ) ){
                                    $service_timings[ $sk ]['reason_for_not_available'] = array( 'Due to shared time slot from ' . $booked_appointment_start_time . ' to  ' . $booked_appointment_end_time );
                                } else {
                                    $service_timings[ $sk ]['reason_for_not_available'][] = 'Due to shared time slot from ' . $booked_appointment_start_time . ' to  ' . $booked_appointment_end_time;
                                }
                                if( 0 == $service_timings[ $sk ]['max_capacity'] ){
                                    $service_timings[ $sk ]['is_booked'] = 1;
                                }
                            }
                            $service_timings[ $sk ]['is_booked_appointment'] = true;
                        }

                        $service_timings[ $sk ]['max_total_capacity'] = $max_service_capacity;

                        if(!isset( $service_timings[ $sk ]['store_start_time'] ) || !isset($service_timings[ $sk ]['store_end_time'])) { 
                            unset( $service_timings[ $sk ] );
                        }

                    }
                }
            }

            /** Remove Booked Time Slots from the final service timings - end */
            
            $service_timings = apply_filters( 'bookingpress_check_available_timings_with_staffmember', $service_timings, $selected_service_id, $selected_date, $total_booked_appiontments );

            $service_timings = apply_filters( 'bookingpress_buffer_calculations', $service_timings, $total_booked_appiontments, $selected_service_id, $shared_quantity, $booked_timing_keys );

            $service_timings = array_values( $service_timings );

            if( true == $check_for_whole_days ){
                $is_available = false;
                foreach( $service_timings as $sk => $time_slot_data ){                   
                    if( !empty( $time_slot_data['guest_members'] ) ){
                        $time_slot_data['total_booked'] += $time_slot_data['guest_members'];
                    }
                    $current_time_start  = $time_slot_data['start_time'];
                    $current_time_end  = $time_slot_data['end_time'];
                    $service_max_capacity = $time_slot_data['max_capacity'];
                    $total_booked = $time_slot_data['total_booked'];
                    $is_booked = isset( $time_slot_data['is_booked'] ) ? $time_slot_data['is_booked'] : false;

                    $total_members = apply_filters( 'bookingpress_fetch_bring_members', 1 );

                    
                    
                    if ( !empty( $time_slot_data['disable_timeslot'] ) && 1 == $time_slot_data['disable_timeslot'] ) {
                        continue;
                    } else {
                        if(isset($time_slot_data['max_total_capacity'])){
                            $total_max_capacity = $time_slot_data['max_total_capacity'];
                            if( !$is_booked && ( $total_booked < $total_max_capacity || $total_members < $service_max_capacity ) ){
                                $is_available = true;
                                break;
                            }
                        } else{
                            if( !$is_booked && ( $total_booked < $service_max_capacity || $total_members < $service_max_capacity ) ){
                                $is_available = true;
                                break;
                            }                        
                        }
                    }

                }
                return $is_available;
            }

            $bookingpress_global_details = $bookingpress_global_options->bookingpress_global_options();
            $bpa_wp_default_time_format  = $bookingpress_global_details['wp_default_time_format'];
            $bpa_wp_default_time_format = apply_filters('bookingpress_change_time_slot_format',$bpa_wp_default_time_format);

            $service_timings = apply_filters('bookingpress_modify_service_timings_data_filter', $service_timings, $selected_service_id, $selected_date, $_POST, $max_service_capacity);

            if(is_plugin_active('bookingpress-appointment-booking-pro/bookingpress-appointment-booking-pro.php')){
                $bookingpress_pro_version = get_option( 'bookingpress_pro_version');
                if( version_compare( $bookingpress_pro_version, '2.6', '<' ) ){                    

                    if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['front_timings'] = array();
                    $_SESSION['front_timings'] = $service_timings;                     
                    
                }
            }   
            if(is_plugin_active('bookingpress-cart/bookingpress-cart.php')){
                $bookingpress_cart_version = get_option( 'bookingpress_cart_module' );
                if( version_compare( $bookingpress_cart_version, '2.2', '<' ) ){

                    if(session_id() == '' OR session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['front_timings'] = array();
                    $_SESSION['front_timings'] = $service_timings;                     

                }
            }

            $bpa_front_timings_key = 'bpa_front_timings_' .$bookingpress_form_token.'_'.$selected_date;
            $bpa_front_timings_key_old = 'bpa_front_timings_' .$bookingpress_form_token;
            $this->bookingpress_update_transient( $bpa_front_timings_key, $service_timings, HOUR_IN_SECONDS );

            /** For premium older version compatability */
            $bpa_front_timings_expiration = ( 60 * MINUTE_IN_SECONDS );
            set_transient( $bpa_front_timings_key, $service_timings, $bpa_front_timings_expiration );
            set_transient( $bpa_front_timings_key_old, $service_timings, $bpa_front_timings_expiration );
            /** For premium older version compatability */

            $morning_time   = array();
            $afternoon_time = array();
            $evening_time   = array();
            $night_time     = array();

            if (! empty($service_timings) ) {
                $an = 1;
                $total_members = apply_filters( 'bookingpress_fetch_bring_members', 1 );
                foreach ( $service_timings as $service_time_key => $service_time_val ) {
                    if(!empty($service_time_val['start_time']) && $service_time_val['end_time']) {
                        $service_start_time = date('H', strtotime($service_time_val['start_time']));
                        $service_end_time   = date('H', strtotime($service_time_val['end_time']));
                        
                        $service_formatted_start_time = date_i18n($bpa_wp_default_time_format, strtotime($service_time_val['start_time']));
                        $service_formatted_end_time   = date_i18n($bpa_wp_default_time_format, strtotime($service_time_val['end_time']));

                        if( $service_formatted_end_time == '00:00') {
                            $service_formatted_end_time = '24:00';
                        }
                        $service_time_arr = $service_time_val;
                        if( !empty( $service_time_arr['guest_members'] ) ){
                            $service_time_arr['total_booked'] += $service_time_arr['guest_members'];
                        }
                        $service_time_arr['disable_flag_timeslot'] = false;
                        if( $service_time_arr['total_booked'] >= $max_service_capacity || $total_members > $max_service_capacity ){
                            /** Remove timeslot when Hide already booked time slot option is enabled */   
                            if( !empty( $bookingpress_hide_already_booked_slot ) && 1 == $bookingpress_hide_already_booked_slot ){
                                unset( $service_timings[ $service_time_key ] );
                                continue;
                            } else {
                                $service_time_arr['disable_flag_timeslot'] = true;
                            }
                        }
                        if( !isset( $service_time_arr['max_total_capacity'] ) ){    
                            $service_time_arr['max_total_capacity'] = $max_service_capacity;
                        }
                        
                        $service_time_arr = apply_filters('bookingpress_modify_single_time_slot_data',$service_time_arr, $selected_service_id, $selected_date);

                        $service_time_arr['css_animation_class'] = $css_animation_class = 'bpa-front--ts-item-' . $an;
                        if ($service_start_time >= 0 && $service_start_time < 12 ) {
                            $morning_time[] = array_merge( $service_time_arr, array(
                                'formatted_start_time' => $service_formatted_start_time,
                                'formatted_end_time'   => $service_formatted_end_time,
                                'class'                => ( $service_time_arr['is_booked'] ) ? '__bpa-is-disabled' : '',
                            ) );
                        } elseif ($service_start_time >= 12 && $service_start_time < 16 ) {
                            $afternoon_time[] = array_merge( $service_time_arr, array(
                                'formatted_start_time' => $service_formatted_start_time,
                                'formatted_end_time'   => $service_formatted_end_time,
                                'class'                => ( $service_time_arr['is_booked'] ) ? '__bpa-is-disabled' : '',
                            ) );
                        } elseif ($service_start_time >= 16 && $service_start_time < 20 ) {
                            $evening_time[] = array_merge( $service_time_arr, array(
                                'formatted_start_time' => $service_formatted_start_time,
                                'formatted_end_time'   => $service_formatted_end_time,
                                'class'                => ( $service_time_arr['is_booked'] ) ? '__bpa-is-disabled' : '',
                            ) );
                        } else {
                            $night_time[] = array_merge( $service_time_arr, array(
                                'formatted_start_time' => $service_formatted_start_time,
                                'formatted_end_time'   => $service_formatted_end_time,
                                'class'                => ( $service_time_arr['is_booked'] ) ? '__bpa-is-disabled' : '',
                            ) );
                        }
                        $an++;
                    }                    
                }
            }

            $bookingpress_timeslot_counts = count($service_timings);
            $bookingpress_selected_start_time = "";
            $bookingpress_selected_end_time = "";
            if($bookingpress_timeslot_counts == 1 && (!empty($service_timings[0]['end_time']) && $service_timings[0]['end_time'] == "24:00") && ($service_timings[0]['is_booked'] == 0) ){
                $bookingpress_selected_start_time = $service_timings[0]['start_time'];
                $bookingpress_selected_end_time = $service_timings[0]['end_time'];
            }

            $return_data = array(
                'morning_time'   => $morning_time,
                'afternoon_time' => $afternoon_time,
                'evening_time'   => $evening_time,
                'night_time'     => $night_time,
            );

            if( !empty( $service_timings_data['is_custom_duration'] ) ){
                $return_data['is_custom_duration'] = true;
            }


            //$return_data = apply_filters('bookingpress_modify_service_return_timings_filter', $return_data, $selected_service_id, $selected_date, $_POST, $max_service_capacity);

            if( true == $return ){
                return $return_data;
            } else {
                echo json_encode($return_data);
                exit();
            }

        }
        
        /**
         * Get category specific service
         *
         * @return void
         */
        function bookingpress_get_category_service_data()
        {
            global $wpdb, $BookingPress, $tbl_bookingpress_services, $tbl_bookingpress_servicesmeta, $bookingpress_other_debug_log_id;
            do_action('bookingpress_other_debug_log_entry', 'appointment_debug_logs', 'Get category service posted data', 'bookingpress_bookingform', $_REQUEST, $bookingpress_other_debug_log_id);
            $response              = array();
            $wpnonce               = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';
            $bpa_verify_nonce_flag = wp_verify_nonce($wpnonce, 'bpa_wp_nonce');
            if (! $bpa_verify_nonce_flag ) {
                $response['variant'] = 'error';
                $response['title']   = esc_html__('Error', 'bookingpress-appointment-booking');
                $response['msg']     = esc_html__('Sorry, Your request can not be processed due to security reason.', 'bookingpress-appointment-booking');
                echo json_encode($response);
                exit();
            }            
			if ( ! empty( $_POST['category_id'] ) || intval($_POST['category_id']) == 0 ) {
                $selected_category_id        = intval($_POST['category_id']);
                $bookingpress_posted_data = !empty($_POST['posted_data']) ? array_map( array( $BookingPress, 'appointment_sanatize_field' ), $_POST['posted_data'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $bookingpress_total_services = 0;
                if (! empty($_POST['total_service']) ) {
                    $bookingpress_total_services = sanitize_text_field($_POST['total_service']);
                }
				if ( ! empty( $_POST['total_category'] ) ) {
					$bookingpress_total_category = sanitize_text_field( $_POST['total_category'] );
				}
                // Fetch services of selected categories
                $bookingpress_search_query_where       = '';
				if ( ! empty( $selected_category_id ) && $selected_category_id != 0 ) {
					$bookingpress_search_query_where .= " WHERE (bookingpress_category_id = '{$selected_category_id}')";
				}
                $bookingpress_search_query_placeholder = '';
				if ( ! empty( $bookingpress_total_category ) && $bookingpress_total_category != 0 ) {
					$bookingpress_search_query_where       .= ! empty( $bookingpress_search_query_where ) ? ' AND' : ' WHERE';
					$bookingpress_search_query_placeholder  = ' bookingpress_category_id IN (';
					$bookingpress_total_category_arr        = explode( ',', $bookingpress_total_category );
					$bookingpress_search_query_placeholder .= rtrim( str_repeat( '%d,', count( $bookingpress_total_category_arr ) ), ',' );
					$bookingpress_search_query_placeholder .= ')';
					array_unshift( $bookingpress_total_category_arr, $bookingpress_search_query_placeholder );
					$bookingpress_search_query_where .= call_user_func_array( array( $wpdb, 'prepare' ), $bookingpress_total_category_arr );
				}
				$bookingpress_search_query_placeholder = '';
                if (! empty($bookingpress_total_services) && $bookingpress_total_services != 0 ) {
					$bookingpress_search_query_where       .= ! empty( $bookingpress_search_query_where ) ? ' AND' : ' WHERE';
					$bookingpress_search_query_placeholder  = ' bookingpress_service_id IN (';
                    $bookingpress_total_services_arr        = explode(',', $bookingpress_total_services);
                    $bookingpress_search_query_placeholder .= rtrim(str_repeat('%d,', count($bookingpress_total_services_arr)), ',');
                    $bookingpress_search_query_placeholder .= ')';
                    array_unshift($bookingpress_total_services_arr, $bookingpress_search_query_placeholder);
					$bookingpress_search_query_where .= call_user_func_array( array( $wpdb, 'prepare' ), $bookingpress_total_services_arr );
                }

				$service_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_services} {$bookingpress_search_query_where} ORDER BY bookingpress_service_position ASC" ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally and $bookingpress_search_query_where is properly prepared. False Positive alarm

                $bookingpress_display_service_description = $BookingPress->bookingpress_get_customize_settings('display_service_description', 'booking_form');

                foreach ( $service_data as $service_key => $service_val ) {
                    $service_data[ $service_key ]['bookingpress_service_price']     = $BookingPress->bookingpress_price_formatter_with_currency_symbol($service_val['bookingpress_service_price']);
                    $service_data[ $service_key ]['service_price_without_currency'] = (float) $service_val['bookingpress_service_price'];
                    $service_data[ $service_key ]['bookingpress_service_name'] = stripslashes_deep($service_val['bookingpress_service_name']);
                    $service_id                              = $service_val['bookingpress_service_id'];
                    $service_meta_details                    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$tbl_bookingpress_servicesmeta} WHERE bookingpress_service_id = %d AND bookingpress_servicemeta_name = 'service_image_details'", $service_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_servicesmeta is table name defined globally. False Positive alarm
                    $service_img_details                     = ! empty($service_meta_details['bookingpress_servicemeta_value']) ? maybe_unserialize($service_meta_details['bookingpress_servicemeta_value']) : array();
                    $service_data[ $service_key ]['img_url'] = ! empty($service_img_details[0]['url']) ? $service_img_details[0]['url'] : BOOKINGPRESS_URL . '/images/placeholder-img.jpg';
                    $service_data[ $service_key ]['bookingpress_service_description'] = stripslashes_deep($service_data[ $service_key ]['bookingpress_service_description']);
                    if ($bookingpress_display_service_description == 'false' ) {
                        $service_data[ $service_key ]['display_read_more_less'] = 1;
                        $default_service_description   = $service_data[ $service_key ]['bookingpress_service_description'];
                        if (strlen($default_service_description) > 140 ) {
                               $service_data[ $service_key ]['bookingpress_service_description_with_excerpt'] = substr($default_service_description, 0, 140);
                               $service_data[ $service_key ]['display_details_more']                          = 0;
                               $service_data[ $service_key ]['display_details_less']                          = 1;
                        } else {
                            $service_data[ $service_key ]['display_read_more_less'] = 0;
                        }
                    }
                }        
                $service_data = apply_filters('bookingpress_modify_service_data_on_category_selection', $service_data, $selected_category_id, $bookingpress_posted_data);

                do_action('bookingpress_other_debug_log_entry', 'appointment_debug_logs', 'Get category service - service data', 'bookingpress_bookingform', $service_data, $bookingpress_other_debug_log_id);

                echo wp_json_encode($service_data);
                exit();
            }
        }
        
        /**
         * Callback function for [bookingpress_form] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_front_booking_form( $atts, $content, $tag )
        {
            global $wpdb, $BookingPress, $bookingpress_common_date_format, $tbl_bookingpress_form_fields, $tbl_bookingpress_services, $tbl_bookingpress_customers, $bookingpress_global_options,$bookingpress_front_vue_data_fields;
            
            do_action('bookingpress_front_booking_form_load_before');
            
            $defaults = array(
            'service'  => 0,
            'category' => 0,
            'selected_service' => 0,
            );
            $args     = shortcode_atts($defaults, $atts, $tag);

            if( !empty( $atts ) ){
                if( !empty( $atts['service'] ) && !preg_match( '/^[(\d+)\,]+$/', $atts['service'] ) ){
                    $atts['service'] = '';
                }            
                
                $atts['category'] = !empty( $atts['category'] ) ? intval( $atts['category'] ) : '';
                $atts['selected_service'] = !empty( $atts['selected_service'] ) ? intval( $atts['selected_service'] ) : '';
            }
            
            extract($args);
            $Bookingpress_service  = 0;
            $Bookingpress_category = 0;
            $selected_category = 0;
            if (! empty($category) && $category != 0 ) {
                $Bookingpress_category            = $category;
                $this->bookingpress_form_category = $category;
            }            
            $service = apply_filters('bookingpress_modify_booking_form_default_display_services', $service);
            if( ! empty($service) && $service != 0 ) {
                $Bookingpress_service            = $service;
                $this->bookingpress_form_service = $service;
            }

            /** Set flag to display no service placeholder */
            $bookingpress_display_no_service_placeholder = false;

            /** Fetch all services */

            if (( ! empty($service) && $service != 0 ) || ( !empty($selected_service) && $selected_service != 0 ) || ( isset($_GET['bpservice_id']) && ! empty($_GET['bpservice_id']) ) || ( isset($_GET['s_id']) && ! empty($_GET['s_id']) ) ) {
                $total_service = array();   
                $bookingpress_is_service_load_from_url = 0;             
                $bookingpress_search_query_where = 'WHERE 1=1 ';
                if (!empty($_GET['bpservice_id']) || !empty($_GET['s_id']) ) {
                    if(!empty($_GET['bpservice_id']) && !isset($_GET['s_id']) ){
                        $selected_service = intval($_GET['bpservice_id']);
                    }else if(!empty($_GET['s_id'])){
                        $selected_service = intval($_GET['s_id']);
                        $category = 0;
                        $service = 0;
                        $this->bookingpress_form_service = 0;
                        $this->bookingpress_form_category = 0;
                    }
                    $bookingpress_is_service_load_from_url = 1;
                } else {
                    $selected_service = intval($selected_service);                                 
                    $this->bookingpress_form_service = $service;    
                }
                
                if(!empty($category)) {
                   $bookingpress_search_query_where .= " AND bookingpress_category_id IN ({$category})";
                }
                if(!empty($service)) {
                    $bookingpress_search_query_where .= " AND bookingpress_service_id IN ({$service})";
                }                   
                $is_service_exist = ''; 
                if(!empty($selected_service)) {
                    
                    $bookingpress_search_query_where .= " AND bookingpress_service_id = {$selected_service}";
                    $is_service_exist = $wpdb->get_row( "SELECT bookingpress_service_id FROM ".$tbl_bookingpress_services." ".$bookingpress_search_query_where); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally. False Positive alarm                
                    
                    if(!empty($selected_service) && !empty($is_service_exist)) {
                        // Get category id
                        $bookingpress_service_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_services} WHERE bookingpress_service_id = %d", $selected_service ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_services is table name defined globally. False Positive alarm
                        if (! empty($bookingpress_service_details) ) {
                            $selected_category = $bookingpress_service_details['bookingpress_category_id'];
                        }
                        if($bookingpress_is_service_load_from_url == 1) {
                            $this->bookingpress_is_service_load_from_url = 1;
                        }
                    } else{
                        $selected_service = 0;
                    }
                } 
            }

            if( !empty( $atts['selected_service'] ) ){
                $this->bookingpress_selected_service_param = true;
            }

            $bpa_all_services = $BookingPress->bookingpress_retrieve_all_services( $service, $selected_service, $Bookingpress_category );
            $this->bookingpress_all_service_data = $bpa_all_services;

            $bookingpress_is_display_empty_view = apply_filters( 'bpa_is_display_emtpy_view', false, $bpa_all_services );

            if( empty( $bpa_all_services ) || true == $bookingpress_is_display_empty_view ){
                $bookingpress_display_no_service_placeholder = true;
            }

            $bookingpress_front_vue_data_fields['bookingpress_display_no_service_placeholder'] = $bookingpress_display_no_service_placeholder;
            $bookingpress_front_vue_data_fields['bookingpress_all_services_data'] = $bpa_all_services;

            $bookingpress_front_vue_data_fields['browser_details'] = '';
            $bookingpress_front_vue_data_fields['browser_version'] = '';

            $bpa_all_categories = $BookingPress->bookingpress_retrieve_all_categories( $bpa_all_services );

            $bookingpress_front_vue_data_fields['hide_category_selection'] = false;
            if( count( $bpa_all_categories ) == 1 && 0 < $bpa_all_categories[0]['category_id'] ){
                $bookingpress_front_vue_data_fields['hide_category_selection'] = true;
            }

            $bookingpress_front_vue_data_fields['bookingpress_all_categories'] = $bpa_all_categories;

            $bookingpress_service_details = $BookingPress->get_bookingpress_service_data_group_with_category();

            // Get labels and tabs names generated from customize
            // -----------------------------------------------------

            $bookingpress_customize_settings = $BookingPress->bookingpress_get_customize_settings(
                array(
                    'service_title',
                    'datetime_title',
                    'basic_details_title',
                    'summary_title',
                    'category_title',
                    'service_heading_title',
                    'timeslot_text',
                    'summary_content_text',
                    'service_duration_label',
                    'service_price_label',
                    'paypal_text',
                    'locally_text',
                    'total_amount_text',
                    'service_text',
                    'customer_text',
                    'date_time_text',
                    'appointment_details',
                    'payment_method_text',
                    'morning_text',
                    'afternoon_text',
                    'evening_text',
                    'night_text',
                    'goback_button_text',
                    'next_button_text',
                    'book_appointment_btn_text',
                    'book_appointment_hours_text',
                    'book_appointment_min_text',
                    'booking_form_tabs_position',
                    'hide_category_service_selection',                    
                    'title_font_family',
                    'content_font_family',
                    'display_service_description',
                    'all_category_title',
                    'date_time_step_note',
                    'summary_step_note',                    
                ),
                'booking_form'
            );         

            $bookingpress_first_tab_name  = stripslashes_deep($bookingpress_customize_settings['service_title']);//$BookingPress->bookingpress_get_customize_settings('service_title', 'booking_form');
            $bookingpress_second_tab_name = stripslashes_deep($bookingpress_customize_settings['datetime_title']);//$BookingPress->bookingpress_get_customize_settings('', 'booking_form');
            $bookingpress_third_tab_name  = stripslashes_deep($bookingpress_customize_settings['basic_details_title']);//$BookingPress->bookingpress_get_customize_settings('basic_details_title', 'booking_form');
            $bookingpress_fourth_tab_name = stripslashes_deep($bookingpress_customize_settings['summary_title']);//$BookingPress->bookingpress_get_customize_settings('summary_title', 'booking_form');
	        $bookingpress_all_category_title = stripslashes_deep($bookingpress_customize_settings['all_category_title']);//$BookingPress->bookingpress_get_customize_settings('all_category_title', 'booking_form');
            $bookingpress_category_title       = stripslashes_deep($bookingpress_customize_settings['category_title']);//$BookingPress->bookingpress_get_customize_settings('category_title', 'booking_form');
            $bookingpress_services_title       = stripslashes_deep($bookingpress_customize_settings['service_heading_title']);//$BookingPress->bookingpress_get_customize_settings('service_heading_title', 'booking_form');
            $bookingpress_timeslot_title       = stripslashes_deep($bookingpress_customize_settings['timeslot_text']);//$BookingPress->bookingpress_get_customize_settings('timeslot_text', 'booking_form');
            $bookingpress_summary_content_text = stripslashes_deep($bookingpress_customize_settings['summary_content_text']);//$BookingPress->bookingpress_get_customize_settings('summary_content_text', 'booking_form');
            $bookingpress_date_time_step_note = !empty( $bookingpress_customize_settings['date_time_step_note'] ) ? stripslashes_deep($bookingpress_customize_settings['date_time_step_note']) : '';
            $bookingpress_summary_step_note = !empty( $bookingpress_customize_settings['summary_step_note'] ) ? stripslashes_deep($bookingpress_customize_settings['summary_step_note']) : '';

            $bookingpress_service_duration_text = !empty($bookingpress_customize_settings['service_duration_label']) ? stripslashes_deep($bookingpress_customize_settings['service_duration_label']) : '';//$BookingPress->bookingpress_get_customize_settings('service_duration_label', 'booking_form');
            if (empty($bookingpress_service_duration_text) ) {
                $bookingpress_service_duration_text = __('Duration', 'bookingpress-appointment-booking') . ':';
            }
            $bookingpress_service_price_text = !empty($bookingpress_customize_settings['service_price_label']) ? stripslashes_deep($bookingpress_customize_settings['service_price_label']) : '';//$BookingPress->bookingpress_get_customize_settings('service_price_label', 'booking_form');
            if (empty($bookingpress_service_price_text) ) {
                $bookingpress_service_price_text = __('Price', 'bookingpress-appointment-booking') . ':';
            }

            $bookingpress_paypal_text = stripslashes_deep($bookingpress_customize_settings['paypal_text']);//$BookingPress->bookingpress_get_customize_settings('paypal_text', 'booking_form');
            if (empty($bookingpress_paypal_text) ) {
                $bookingpress_paypal_text = __('PayPal', 'bookingpress-appointment-booking');
            }

            $bookingpress_locally_text = stripslashes_deep($bookingpress_customize_settings['locally_text']);//$BookingPress->bookingpress_get_customize_settings('locally_text', 'booking_form');
            if (empty($bookingpress_locally_text) ) {
                $bookingpress_locally_text = __('Pay Locally', 'bookingpress-appointment-booking');
            }

            $bookingpress_total_amount_text = stripslashes_deep($bookingpress_customize_settings['total_amount_text']);//$BookingPress->bookingpress_get_customize_settings('total_amount_text', 'booking_form');
            if (empty($bookingpress_total_amount_text) ) {
                $bookingpress_total_amount_text = __('Total Amount Payable', 'bookingpress-appointment-booking');
            }

            $bookingpress_service_text = stripslashes_deep($bookingpress_customize_settings['service_text']);//$BookingPress->bookingpress_get_customize_settings('service_text', 'booking_form');
            if (empty($bookingpress_service_text) ) {
                $bookingpress_service_text = __('Service', 'bookingpress-appointment-booking');
            }

            $bookingpress_customer_text = stripslashes_deep($bookingpress_customize_settings['customer_text']);//$BookingPress->bookingpress_get_customize_settings('customer_text', 'booking_form');
            if (empty($bookingpress_customer_text) ) {
                $bookingpress_customer_text = __('Customer', 'bookingpress-appointment-booking');
            }

            $bookingpress_date_time_text = stripslashes_deep($bookingpress_customize_settings['date_time_text']);//$BookingPress->bookingpress_get_customize_settings('date_time_text', 'booking_form');
            if (empty($bookingpress_date_time_text) ) {
                $bookingpress_date_time_text = __('Date &amp; Time', 'bookingpress-appointment-booking');
            }

            $bookingpress_appointment_details_title_text = stripslashes_deep($bookingpress_customize_settings['appointment_details']);//$BookingPress->bookingpress_get_customize_settings('date_time_text', 'booking_form');
            if (empty($bookingpress_appointment_details_title_text) ) {
                $bookingpress_appointment_details_title_text = __('Appointment Details', 'bookingpress-appointment-booking');
            }

            $bookingpress_payment_method_text = stripslashes_deep($bookingpress_customize_settings['payment_method_text']);//$BookingPress->bookingpress_get_customize_settings('payment_method_text', 'booking_form');
            if (empty($bookingpress_payment_method_text) ) {
                $bookingpress_payment_method_text = __('Select Payment Method', 'bookingpress-appointment-booking');
            }

            $bookingpress_morning_text = stripslashes_deep($bookingpress_customize_settings['morning_text']);//$BookingPress->bookingpress_get_customize_settings('morning_text', 'booking_form');
            if (empty($bookingpress_morning_text) ) {
                $bookingpress_morning_text = esc_html__('Morning', 'bookingpress-appointment-booking');
            }
            $bookingpress_afternoon_text = stripslashes_deep($bookingpress_customize_settings['afternoon_text']);//$BookingPress->bookingpress_get_customize_settings('afternoon_text', 'booking_form');
            if (empty($bookingpress_afternoon_text) ) {
                $bookingpress_afternoon_text = esc_html__('Afternoon', 'bookingpress-appointment-booking');
            }
            $bookingpress_evening_text = stripslashes_deep($bookingpress_customize_settings['evening_text']);//$BookingPress->bookingpress_get_customize_settings('evening_text', 'booking_form');
            if (empty($bookingpress_evening_text) ) {
                $bookingpress_evening_text = esc_html__('Evening', 'bookingpress-appointment-booking');
            }
            $bookingpress_night_text = stripslashes_deep($bookingpress_customize_settings['night_text']);//$BookingPress->bookingpress_get_customize_settings('night_text', 'booking_form');
            if (empty($bookingpress_night_text) ) {
                $bookingpress_night_text = esc_html__('Night', 'bookingpress-appointment-booking');
            }
            $bookingpress_date_time_step_note = !empty($bookingpress_date_time_step_note) ? stripslashes_deep($bookingpress_date_time_step_note) : '';
            $bookingpress_summary_step_note = !empty($bookingpress_summary_step_note) ? stripslashes_deep($bookingpress_summary_step_note) : '';

            $bookingpress_goback_btn_text           = stripslashes_deep($bookingpress_customize_settings['goback_button_text']);//$BookingPress->bookingpress_get_customize_settings('goback_button_text', 'booking_form');
            $bookingpress_next_btn_text             = stripslashes_deep($bookingpress_customize_settings['next_button_text']);//$BookingPress->bookingpress_get_customize_settings('next_button_text', 'booking_form');
            $bookingpress_book_appointment_btn_text = stripslashes_deep($bookingpress_customize_settings['book_appointment_btn_text']);//$BookingPress->bookingpress_get_customize_settings('book_appointment_btn_text', 'booking_form');
            $bookingpress_book_appointment_hours_label = stripslashes_deep($bookingpress_customize_settings['book_appointment_hours_text']);
            $bookingpress_book_appointment_min_label = stripslashes_deep($bookingpress_customize_settings['book_appointment_min_text']);
            $bookingpress_tabs_position             = $bookingpress_customize_settings['booking_form_tabs_position'];//$BookingPress->bookingpress_get_customize_settings('booking_form_tabs_position', 'booking_form');

            $bookingpress_hide_category_service       = $bookingpress_customize_settings['hide_category_service_selection'];//$BookingPress->bookingpress_get_customize_settings('hide_category_service_selection', 'booking_form');

            
            
            
            $bookingpress_front_vue_data_fields['bookingpress_book_appointment_btn_text'] = $bookingpress_book_appointment_btn_text;
            $bookingpress_front_vue_data_fields['bookingpress_total_amount_text'] = $bookingpress_total_amount_text;

            
            $bookingpress_hide_category_service       = ( $bookingpress_hide_category_service == 'true' ) ? 1 : 0;
            $bookingpress_loaded_from_share_url       = false;
            if(!empty($_GET['s_id']) && ( isset($_GET['allow_modify']) && $_GET['allow_modify'] == '0' ) ){
                $bookingpress_hide_category_service = 1;
                $bookingpress_loaded_from_share_url = true;
            } else if(!empty($_GET['s_id']) && ( isset($_GET['allow_modify']) && $_GET['allow_modify'] == '1' ) ){
                $bookingpress_hide_category_service = 0;
                $bookingpress_loaded_from_share_url = true;
            }

            
            if( 1 == $bookingpress_hide_category_service && $bookingpress_loaded_from_share_url ){
                if( $selected_service == 0 ){
                    $bookingpress_hide_category_service = 0;
                }
            }

            $this->bookingpress_hide_category_service = $bookingpress_hide_category_service;

            $bookingpress_global_options_arr       = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_default_date_time_format = $bookingpress_global_options_arr['wp_default_date_format'];
            
            $bookingpress_default_date_format = 'MMMM D, YYYY';
            if ($bookingpress_default_date_time_format == 'F j, Y' ) {
                $bookingpress_default_date_format = 'MMMM D, YYYY';
            } elseif ($bookingpress_default_date_time_format == 'Y-m-d' ) {
                $bookingpress_default_date_format = 'YYYY-MM-DD';
            } elseif ($bookingpress_default_date_time_format == 'm/d/Y' ) {
                $bookingpress_default_date_format = 'MM/DD/YYYY';
            } elseif($bookingpress_default_date_time_format == 'd/m/Y') {
                $bookingpress_default_date_format = 'DD/MM/YYYY';
            } elseif ($bookingpress_default_date_time_format == 'd.m.Y') {
                $bookingpress_default_date_format = 'DD.MM.YYYY';
            } elseif ($bookingpress_default_date_time_format == 'd-m-Y') {
                $bookingpress_default_date_format = 'DD-MM-YYYY';
            }

            $this->bookingpress_default_date_format = $bookingpress_default_date_format;

            // -----------------------------------------------------

            // Get form fields details
            // -----------------------------------------------------

            /** Check if Pro version is exists but not activated */
            if( $BookingPress->bpa_is_pro_exists() && !$BookingPress->bpa_is_pro_active() ){
                if( empty( $BookingPress->bpa_pro_plugin_version() ) ){
                    $bookingpress_form_fields               = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_form_fields} ORDER BY bookingpress_field_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                } else {
                    $bookingpress_form_fields               = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_form_fields} WHERE bookingpress_field_is_default = %d ORDER BY bookingpress_field_position ASC", 1), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm
                }
            } else {
                

                $bookingpress_form_fields = wp_cache_get( 'bpa_appointment_booking_form_fields_data_');

               
				if( false == $bookingpress_form_fields )
                {
                    $bookingpress_form_fields = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_form_fields} ORDER BY bookingpress_field_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_form_fields is table name defined globally. False Positive alarm

                    wp_cache_set( 'bpa_appointment_booking_form_fields_data_', $bookingpress_form_fields);
                }

            }


            $bookingpress_form_fields_error_msg_arr = $bookingpress_form_fields_new = array();
            
            $bookingpress_form_fields        = apply_filters('bookingpress_modify_field_data_before_prepare', $bookingpress_form_fields);
            

            foreach ( $bookingpress_form_fields as $bookingpress_form_field_key => $bookingpress_form_field_val ) {

                if($bookingpress_form_field_val['bookingpress_field_is_hide'] == 0) {

                    $bookingpress_v_model_value = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_v_model_value = 'customer_name';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_v_model_value = 'customer_firstname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_v_model_value = 'customer_lastname';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_v_model_value = 'customer_email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_v_model_value = 'customer_phone';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_v_model_value = 'appointment_note';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_v_model_value = 'customer_username';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions' ) {
                        $bookingpress_v_model_value = 'appointment_terms_conditions';
                    } else {
                        $bookingpress_v_model_value = $bookingpress_form_field_val['bookingpress_field_meta_key'];
                    }

                    $bookingpress_front_vue_data_fields['appointment_step_form_data'][$bookingpress_v_model_value] = '';
                    if( 'appointment_terms_conditions' == $bookingpress_v_model_value ){
                        $bookingpress_front_vue_data_fields['appointment_step_form_data'][$bookingpress_v_model_value] = array();
                    }

                    $bookingpress_field_type = '';
                    if ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'fullname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'firstname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'lastname' ) {
                        $bookingpress_field_type = 'Text';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'email_address' ) {
                        $bookingpress_field_type = 'Email';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'phone_number' ) {
                        $bookingpress_field_type = 'Dropdown';
                    } elseif ($bookingpress_form_field_val['bookingpress_form_field_name'] == 'note' ) {
                        $bookingpress_field_type = 'Textarea';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'username' ){
                        $bookingpress_field_type = 'Text';
                    } elseif($bookingpress_form_field_val['bookingpress_form_field_name'] == 'terms_and_conditions'){
                        $bookingpress_field_type = 'terms_and_conditions';
                    } else {
                        $bookingpress_field_type = $bookingpress_form_field_val['bookingpress_field_type'];
                    }

                    $bookingpress_field_setting_fields_tmp                   = array();
                    $bookingpress_field_setting_fields_tmp['id']             = intval($bookingpress_form_field_val['bookingpress_form_field_id']);
                    $bookingpress_field_setting_fields_tmp['field_name']     = $bookingpress_form_field_val['bookingpress_form_field_name'];
                    $bookingpress_field_setting_fields_tmp['field_type']     = $bookingpress_field_type;
                    $bookingpress_field_setting_fields_tmp['is_edit']        = false;

                    $bookingpress_field_setting_fields_tmp['is_required']    = ( $bookingpress_form_field_val['bookingpress_field_required'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['label']          = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']);
                    $bookingpress_field_setting_fields_tmp['placeholder']    = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_placeholder']);
                    $bookingpress_field_setting_fields_tmp['error_message']  = stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']);
                    $bookingpress_field_setting_fields_tmp['is_hide']        = ( $bookingpress_form_field_val['bookingpress_field_is_hide'] == 0 ) ? false : true;
                    $bookingpress_field_setting_fields_tmp['field_position'] = floatval($bookingpress_form_field_val['bookingpress_field_position']);
                    $bookingpress_field_setting_fields_tmp['v_model_value']  = $bookingpress_v_model_value;                    

                    $bookingpress_field_setting_fields_tmp = apply_filters( 'bookingpress_arrange_form_fields_outside', $bookingpress_field_setting_fields_tmp, $bookingpress_form_field_val);

                    
                    $bookingpress_front_vue_data_fields['appointment_step_form_data'] = apply_filters('bookingpress_add_appointment_step_form_data_filter',$bookingpress_front_vue_data_fields['appointment_step_form_data'],$bookingpress_field_setting_fields_tmp);
                    
                    array_push( $bookingpress_form_fields_new, $bookingpress_field_setting_fields_tmp );

                    if ($bookingpress_form_field_val['bookingpress_field_required'] == '1' ) {
                        if ($bookingpress_v_model_value == 'customer_email' ) {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ] = array(
                                array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                                ),
                                array(
                                'type'    => 'email',
                                'message' => esc_html__('Please enter valid email address', 'bookingpress-appointment-booking'),
                                'trigger' => 'blur',
                            ),
                         );
                        } elseif( $bookingpress_v_model_value == 'appointment_terms_conditions') {
                               
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'change',
                            ); 
                        } else {                 
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][] = array(
                                'required' => true,
                                'message'  => stripslashes_deep($bookingpress_form_field_val['bookingpress_field_error_message']),
                                'trigger'  => 'blur',
                            );                                                       
                        }

                        if(isset($bookingpress_form_fields_error_msg_arr[$bookingpress_v_model_value][0]['message']) && $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value][0]['message'] == '') {
                            $bookingpress_form_fields_error_msg_arr[ $bookingpress_v_model_value ][0]['message'] = !empty($bookingpress_form_field_val['bookingpress_field_label']) ?  stripslashes_deep($bookingpress_form_field_val['bookingpress_field_label']).' '.__('is required','bookingpress-appointment-booking') : '';
                        }           
                    }                                       
                    $bookingpress_form_fields_error_msg_arr = apply_filters( 'bookingpress_modify_form_fields_rules_arr', $bookingpress_form_fields_error_msg_arr,$bookingpress_field_setting_fields_tmp );
                }    
            }

            $this->bookingpress_form_fields_error_msg_arr = apply_filters( 'bookingpress_modify_form_fields_msg_array', $bookingpress_form_fields_error_msg_arr );
                      
            $this->bookingpress_form_fields_new           = $bookingpress_form_fields_new;
			
	        $bookingress_load_js_css_all_pages = $BookingPress->bookingpress_get_settings('load_js_css_all_pages', 'general_setting');
            
            // -----------------------------------------------------

            if (is_user_logged_in() ) {
                $bookingpress_wp_user_id              = get_current_user_id();
                $bookingpress_check_user_exist_or_not = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(bookingpress_customer_id) as total FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d AND bookingpress_user_status = 0 AND bookingpress_user_type = 0", $bookingpress_wp_user_id ));  // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
                if ($bookingpress_check_user_exist_or_not > 0 ) {
                    $bookingpress_update_customer_data = array(
                    'bookingpress_user_status' => 1,
                    'bookingpress_user_type'   => 2,
                    );

                    $bookingpress_where_condition = array(
                    'bookingpress_wpuser_id' => $bookingpress_wp_user_id,
                    );

                    $wpdb->update($tbl_bookingpress_customers, $bookingpress_update_customer_data, $bookingpress_where_condition);
                }
            }

            $bookingpress_uniq_id = uniqid();

            $BookingPress->set_front_css(1);
            $BookingPress->set_front_js(1);
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $BookingPress->bookingpress_load_mybookings_custom_js();

			//Code for modify front shortcode data from outside
			//-------------------------------------------------------
				$bookingpress_class_vars_val = array(
					'form_category' => $this->bookingpress_form_category,
					'form_service' => $this->bookingpress_form_service,
					'hide_category_service' => $this->bookingpress_hide_category_service,
					'default_date_format' => $this->bookingpress_default_date_format,
					'default_time_format' => $this->bookingpress_default_time_format,
					'form_field_err_msg_arr' => $this->bookingpress_form_fields_error_msg_arr,
					'form_fields_new' => $this->bookingpress_form_fields_new,
					'is_service_load_from_url' => $this->bookingpress_is_service_load_from_url,
				);

				do_action('bookingpress_add_dynamic_details_booking_shortcode', $bookingpress_uniq_id, $bookingpress_class_vars_val, $args);
			//-------------------------------------------------------	

            ob_start();
            $bookingpress_shortcode_file_url = BOOKINGPRESS_VIEWS_DIR . '/frontend/appointment_booking_form.php';
            $bookingpress_shortcode_file_url = apply_filters('bookingpress_change_booking_shortcode_file_url', $bookingpress_shortcode_file_url);
            include $bookingpress_shortcode_file_url;
            $content .= ob_get_clean();

            // Main data loading script
            $bookingpress_global_details     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_formatted_timeslot = $bookingpress_global_details['bpa_time_format_for_timeslot'];
            $bookingpress_wp_default_time_format = $bookingpress_global_details['wp_default_time_format'];
            
            $bookingpress_site_current_language = get_locale();
            if ($bookingpress_site_current_language == 'ru_RU' ) {
                $bookingpress_site_current_language = 'ru';
            } elseif ($bookingpress_site_current_language == 'ar' ) {
                $bookingpress_site_current_language = 'ar'; // arabic
            } elseif ($bookingpress_site_current_language == 'bg_BG' ) {
                $bookingpress_site_current_language = 'bg'; // Bulgeria
            } elseif ($bookingpress_site_current_language == 'ca' ) {
                $bookingpress_site_current_language = 'ca'; // Canada
            } elseif ($bookingpress_site_current_language == 'da_DK' ) {
                $bookingpress_site_current_language = 'da'; // Denmark
            } elseif ($bookingpress_site_current_language == 'de_DE' || $bookingpress_site_current_language == 'de_CH_informal' || $bookingpress_site_current_language == 'de_AT' || $bookingpress_site_current_language == 'de_CH' || $bookingpress_site_current_language == 'de_DE_formal' ) {
                $bookingpress_site_current_language = 'de'; // Germany
            } elseif ($bookingpress_site_current_language == 'el' ) {
                $bookingpress_site_current_language = 'el'; // Greece
            } elseif ($bookingpress_site_current_language == 'es_ES' ) {
                $bookingpress_site_current_language = 'es'; // Spain
            } elseif ($bookingpress_site_current_language == 'fr_FR' ) {
                $bookingpress_site_current_language = 'fr'; // France
            } elseif ($bookingpress_site_current_language == 'hr' ) {
                $bookingpress_site_current_language = 'hr'; // Croatia
            } elseif ($bookingpress_site_current_language == 'hu_HU' ) {
                $bookingpress_site_current_language = 'hu'; // Hungary
            } elseif ($bookingpress_site_current_language == 'id_ID' ) {
                $bookingpress_site_current_language = 'id'; // Indonesia
            } elseif ($bookingpress_site_current_language == 'is_IS' ) {
                $bookingpress_site_current_language = 'is'; // Iceland
            } elseif ($bookingpress_site_current_language == 'it_IT' ) {
                $bookingpress_site_current_language = 'it'; // Italy
            } elseif ($bookingpress_site_current_language == 'ja' ) {
                $bookingpress_site_current_language = 'ja'; // Japan
            } elseif ($bookingpress_site_current_language == 'ka_GE' ) {
                $bookingpress_site_current_language = 'ka'; // Georgia
            } elseif ($bookingpress_site_current_language == 'ko_KR' ) {
                $bookingpress_site_current_language = 'ko'; // Korean
            } elseif ($bookingpress_site_current_language == 'lt_LT' ) {
                $bookingpress_site_current_language = 'lt'; // Lithunian
            } elseif ($bookingpress_site_current_language == 'mn' ) {
                $bookingpress_site_current_language = 'mn'; // Mongolia
            } elseif ($bookingpress_site_current_language == 'nl_NL' ) {
                $bookingpress_site_current_language = 'nl'; // Netherlands
            } elseif ($bookingpress_site_current_language == 'nn_NO' ) {
                $bookingpress_site_current_language = 'no'; // Norway
            } elseif ($bookingpress_site_current_language == 'pl_PL' ) {
                $bookingpress_site_current_language = 'pl'; // Poland
            } elseif ($bookingpress_site_current_language == 'pt_BR' ) {
                $bookingpress_site_current_language = 'pt-br'; // Portuguese
            } elseif ($bookingpress_site_current_language == 'ro_RO' ) {
                $bookingpress_site_current_language = 'ro'; // Romania
            } elseif ($bookingpress_site_current_language == 'sk_SK' ) {
                $bookingpress_site_current_language = 'sk'; // Slovakia
            } elseif ($bookingpress_site_current_language == 'sl_SI' ) {
                $bookingpress_site_current_language = 'sl'; // Slovenia
            } elseif ($bookingpress_site_current_language == 'sq' ) {
                $bookingpress_site_current_language = 'sq'; // Albanian
            } elseif ($bookingpress_site_current_language == 'sr_RS' ) {
                $bookingpress_site_current_language = 'sr'; // Suriname
            } elseif ($bookingpress_site_current_language == 'sv_SE' ) {
                $bookingpress_site_current_language = 'sv'; // El Salvador
            } elseif ($bookingpress_site_current_language == 'tr_TR' ) {
                $bookingpress_site_current_language = 'tr'; // Turkey
            } elseif ($bookingpress_site_current_language == 'uk' ) {
                $bookingpress_site_current_language = 'uk'; // Ukrain
            } elseif ($bookingpress_site_current_language == 'vi' ) {
                $bookingpress_site_current_language = 'vi'; // Virgin Islands (U.S.)
            } elseif ($bookingpress_site_current_language == 'zh_CN' ) {
                $bookingpress_site_current_language = 'zh-cn'; // Chinese
            } elseif ($bookingpress_site_current_language == 'nl_BE'){
                $bookingpress_site_current_language = 'nl-be'; // Nederlands ( Belgi )
            } elseif ($bookingpress_site_current_language == 'cs_CZ'){
                $bookingpress_site_current_language = 'cs';
            }elseif ($bookingpress_site_current_language == 'pt_PT'){
                $bookingpress_site_current_language = 'pt';
            }elseif ($bookingpress_site_current_language == 'et'){
                $bookingpress_site_current_language = 'et';
            }elseif ($bookingpress_site_current_language == 'nb_NO'){
                $bookingpress_site_current_language = 'no';
            } elseif ($bookingpress_site_current_language == 'lv'){
                $bookingpress_site_current_language = 'lv';
            }elseif ($bookingpress_site_current_language == 'az'){
                $bookingpress_site_current_language = 'az';
            }elseif ($bookingpress_site_current_language == 'fi'){
                $bookingpress_site_current_language = 'fi'; //Finnish
            }
             else {
                $bookingpress_site_current_language = 'en';
            }

            $no_appointment_time_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_time_selected_for_the_booking', 'message_setting');

            $no_service_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_service_selected_for_the_booking', 'message_setting');

            $bookingpress_script_return_data = '';

            $bookingpress_front_booking_dynamic_helper_vars = '';
            $bookingpress_front_booking_dynamic_helper_vars = apply_filters('bookingpress_front_booking_dynamic_helper_vars', $bookingpress_front_booking_dynamic_helper_vars);

            $bookingpress_vue_root_element_id = '#bookingpress_booking_form_' . $bookingpress_uniq_id;
            $bookingpress_vue_root_element_id_without_hash = 'bookingpress_booking_form_' . $bookingpress_uniq_id;
            $bookingpress_vue_root_element_id_el = 'method_' . $bookingpress_uniq_id;

            $bookingpress_dynamic_directive_data = '';
            $bookingpress_dynamic_directive_data = apply_filters('bookingpress_front_booking_dynamic_directives', $bookingpress_dynamic_directive_data);
            
            $bookingpress_dynamic_data_fields = '';
            $bookingpress_dynamic_data_fields = apply_filters('bookingpress_front_booking_dynamic_data_fields', $bookingpress_dynamic_data_fields, $this->bookingpress_form_category, $this->bookingpress_form_service,$selected_service,$selected_category);





            $bookingpress_dynamic_on_load_methods_data = '';
            $bookingpress_dynamic_on_load_methods_data = apply_filters('bookingpress_front_booking_dynamic_on_load_methods', $bookingpress_dynamic_on_load_methods_data);

            $bookingpress_vue_methods_data = '';
            $bookingpress_vue_methods_data = apply_filters('bookingpress_front_booking_dynamic_vue_methods', $bookingpress_vue_methods_data);

            $bookingpress_password_check_data = '';
            $bookingpress_password_check_data = apply_filters('bookingpress_front_check_password_validation', $bookingpress_password_check_data);

            $bookingpress_password_check_for_username = '';
            $bookingpress_password_check_for_username = apply_filters('bookingpress_front_check_password_with_username_validation', $bookingpress_password_check_for_username);
            

            if (! empty($bookingpress_front_booking_dynamic_helper_vars) ) {
                $bookingpress_script_return_data .= $bookingpress_front_booking_dynamic_helper_vars;
            }

            $bookingpress_script_return_data .= "var bookingpress_uniq_id_js_var = '" . $bookingpress_uniq_id . "';";

            $bookingpress_nonce = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $bookingpress_site_date = date('Y-m-d H:i:s', current_time( 'timestamp') );
            $bookingpress_site_date = apply_filters( 'bookingpress_modify_current_date', $bookingpress_site_date );
            if( !empty( $bookingpress_site_date ) ){
                $bookingpress_site_current_date = date( 'Y-m-d', strtotime( $bookingpress_site_date ) ) . ' 00:00:00';
            } else {
                $bookingpress_site_current_date = "";
            }
            $bookingpress_site_date = str_replace('-', '/', $bookingpress_site_date);

            $bpa_allow_modify_from_url = !empty($_GET['allow_modify']) ? 1 : 0;

            if( ( isset($_GET['bpservice_id']) ) || isset($_GET['s_id']) ){
                $this->bookingpress_is_service_load_from_url = 1;
            }

            if( 1 == $this->bookingpress_is_service_load_from_url ){
                if( empty( $selected_service ) ){
                    $this->bookingpress_is_service_load_from_url = 0;
                }
            }

            $first_day_of_week = (int)  $bookingpress_global_options_arr['start_of_week'];
            $first_day_of_week_inc = $first_day_of_week + 1;
	    
	    $bookingpress_site_current_lang_moment_locale = get_locale();

            if($bookingpress_site_current_lang_moment_locale == "am" || $bookingpress_site_current_lang_moment_locale == "ary" || $bookingpress_site_current_lang_moment_locale == "skr") {
                $bookingpress_site_current_lang_moment_locale = "ar";
            }else if( $bookingpress_site_current_lang_moment_locale == "azb" ) {
                $bookingpress_site_current_lang_moment_locale = "fa_AF";
            }else if( $bookingpress_site_current_lang_moment_locale == "dsb" || $bookingpress_site_current_lang_moment_locale == "hsb" || $bookingpress_site_current_lang_moment_locale == "szl" ) {
                $bookingpress_site_current_lang_moment_locale = "pl";
            }else if( $bookingpress_site_current_lang_moment_locale == "fur" ) {
                $bookingpress_site_current_lang_moment_locale = "it";
            }else if ( $bookingpress_site_current_lang_moment_locale == "ckb" ) {
                $bookingpress_site_current_lang_moment_locale = "ku";
            }else if ( $bookingpress_site_current_lang_moment_locale == "oci" ) {
                $bookingpress_site_current_lang_moment_locale = "ca";
            }else if ( $bookingpress_site_current_lang_moment_locale == "sah" ) {
                $bookingpress_site_current_lang_moment_locale = "ky";
            }else if ( $bookingpress_site_current_lang_moment_locale == "tl" ) {
                $bookingpress_site_current_lang_moment_locale = "fil";
            }else if ( $bookingpress_site_current_lang_moment_locale == "as" ) {
                $bookingpress_site_current_lang_moment_locale = "bn";
            }else if ( $bookingpress_site_current_lang_moment_locale == "hy" ) {
                $bookingpress_site_current_lang_moment_locale = "hy-am";
            }else if ( $bookingpress_site_current_lang_moment_locale == "et" ) {
                $bookingpress_site_current_lang_moment_locale = "et";
            }else if ( $bookingpress_site_current_lang_moment_locale == "nb_NO" ) {
                $bookingpress_site_current_lang_moment_locale = "no";
            }

            $bookingpress_global_details  = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_wp_default_time_format = $bookingpress_global_details['wp_default_time_format'];
            $bookingpress_inherit_from_wordpress_arr = json_decode($bookingpress_global_details['bookingpress_inherit_from_wordpress_arr'],true);

            if(isset($bookingpress_inherit_from_wordpress_arr[$bookingpress_wp_default_time_format])){
                $bookingpress_formatted_timeslot = $bookingpress_inherit_from_wordpress_arr[$bookingpress_wp_default_time_format];
            }

            $bookingpress_script_return_data .= 'app = new Vue({ 
				el: "' . $bookingpress_vue_root_element_id . '",
				components: {},
				directives: { ' . $bookingpress_dynamic_directive_data . ' },
				data(){
                    
                    var bpa_check_username = ( rule, value, callback ) =>{
                        const vm = this;
                        
                        if( "undefined" == vm.appointment_step_form_data.check_username_validation || false == vm.appointment_step_form_data.check_username_validation ){
                            if( "undefined" != vm.appointment_step_form_data.invalid_customer_username && true == vm.appointment_step_form_data.invalid_customer_username ){
                                return callback( new Error( vm.appointment_step_form_data.invalid_customer_message ) );
                            } else {
                                return callback();
                            }
                        }

                        var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                        {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }
                        else {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
                        let bookingpress_username_value = value;
                        
                        var bookingpress_username = { action:"bookingpress_validate_username", _username: bookingpress_username_value, _wpnonce:bkp_wpnonce_pre_fetch};
                        axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( bookingpress_username ) )
                        .then( function (response) {
                            vm.appointment_step_form_data.check_username_validation = false;
                            '.$bookingpress_password_check_data.';
                            if(response.data.variant == "error"){
                                vm.appointment_step_form_data.invalid_customer_username = true;
                                vm.appointment_step_form_data.invalid_customer_message = response.data.msg;
                                return callback(new Error( response.data.msg ));
                            } else {
                                vm.appointment_step_form_data.invalid_customer_username = false;
                                callback();
                            }
                        }.bind(this) )
                        .catch( function (error) {
                            vm.bookingpress_set_error_msg(error)
                        });
                    };
                    
					var bookingpress_return_data = ' . $bookingpress_dynamic_data_fields . ';
					bookingpress_return_data["jsCurrentDate"] = new Date('. ( !empty( $bookingpress_site_date ) ? '"'.$bookingpress_site_date.'"' : '' ) .');
					bookingpress_return_data["jsCurrentDateFormatted"] = new Date ('. ( !empty( $bookingpress_site_current_date ) ? '"'.$bookingpress_site_current_date.'"' : '' ) .');
					bookingpress_return_data["appointment_step_form_data"]["stime"] = ' . ( time() + 14921 ) . ';
					bookingpress_return_data["appointment_step_form_data"]["spam_captcha"] = "";
					bookingpress_return_data["hide_category_service"] = "' . $this->bookingpress_hide_category_service . '";
					bookingpress_return_data["default_date_format"] = "' . $this->bookingpress_default_date_format . '";
					bookingpress_return_data["customer_details_rule"] = ' . json_encode($this->bookingpress_form_fields_error_msg_arr) . ';

                    if( "undefined" != typeof bookingpress_return_data["customer_details_rule"].customer_username && bookingpress_return_data["check_bookingpress_username_set"] == 0 ){

                        let rule_for_username = {
                            "validator": bpa_check_username,
                            "trigger": "blur"
                        };

                        bookingpress_return_data["customer_details_rule"].customer_username.push( rule_for_username );
                       
                    }

					bookingpress_return_data["customer_form_fields"] = ' . json_encode($this->bookingpress_form_fields_new) . ';
					bookingpress_return_data["is_error_msg"] = "";
					bookingpress_return_data["is_display_error"] = "0";
					bookingpress_return_data["is_service_loaded_from_url"] = "' . $this->bookingpress_is_service_load_from_url . '";
					bookingpress_return_data["booking_cal_maxdate"] = new Date( Date.now() + ( 3600 * 1000 * (24 * 365) ) );
                    bookingpress_return_data["is_booking_form_empty_loader"] = "1";
                    bookingpress_return_data["bpa_allow_modify_from_url"] = "'.$bpa_allow_modify_from_url.'";

					bookingpress_return_data["site_locale"] = "' . $bookingpress_site_current_language . '";    
					bookingpress_return_data["appointment_step_form_data"]["bookingpress_uniq_id"] = "' . $bookingpress_uniq_id . '";
					var bookingpress_captcha_key = "bookingpress_captcha_' . $bookingpress_uniq_id . '";
					bookingpress_return_data["appointment_step_form_data"][bookingpress_captcha_key] = "";

                    bookingpress_return_data["first_day_of_week"] = "' . $first_day_of_week_inc. '"; 
                    bookingpress_return_data["filter_pickerOptions"] = {
                        "firstDayOfWeek": '.$first_day_of_week.',
                    };
                    bookingpress_return_data["appointment_step_form_data"]["base_price_without_currency"] = 0;
                    bookingpress_return_data["use_base_price_for_calculation"] = true;

                    bookingpress_return_data["modelConfig"] = {
                        "type": "string",
                        "mask": "YYYY-MM-DD",
                    };

                    
					return bookingpress_return_data;
				},
				filters: {
					bookingpress_format_date: function(value){
                        var default_date_format = "' . $this->bookingpress_default_date_format . '";
                        return moment(String(value)).locale("'.$bookingpress_site_current_lang_moment_locale.'").format(default_date_format)
					},
					bookingpress_format_time: function(value){
						var default_time_format = "' . $bookingpress_formatted_timeslot . '";
                        return moment(String(value), "HH:mm:ss").locale("'.$bookingpress_site_current_lang_moment_locale.'").format(default_time_format)
					}
				},
                beforeCreate(){
					this.is_booking_form_empty_loader = "1";
				},
				created(){
					this.bookingpress_load_booking_form();
				},
				mounted(){
                    const vm_onload = this;

                    vm_onload.bpa_check_browser();
                    vm_onload.bpa_check_browser_version();

                    let selected_category = vm_onload.appointment_step_form_data.selected_category;
                    vm_onload.bpa_select_category( selected_category );
                    
					this.loadSpamProtection();
					' . $bookingpress_dynamic_on_load_methods_data . '

				},
                computed:{
                    bpasortedServices: function(){
                        let bookingpress_all_services_data = [];
                        for( let i in this.bookingpress_all_services_data ){
                            bookingpress_all_services_data.push( this.bookingpress_all_services_data[i] );
                        }
                        return bookingpress_all_services_data.sort( (a, b) =>{
                            return ( parseInt( a.bookingpress_service_position ) < parseInt( b.bookingpress_service_position ) ) ? -1 : 1;
                        });
                    }
                },
				methods: {
                    bpa_check_username_validation(bpa_username){
                        const vm = this;
                        if( bpa_username != ""){
                            vm.appointment_step_form_data.check_username_validation = true;
                        } else {
                            vm.appointment_step_form_data.check_username_validation = false;
                            '.$bookingpress_password_check_for_username.';
                        }
                    },
                    bookingpress_load_booking_form(){
                        const vm = this;
                        setTimeout(function(){
                            vm.is_booking_form_empty_loader = "0";
                            setTimeout(function(){
                                var elms = document.querySelectorAll("#bpa-front-tabs");
                                for(var i = 0; i < elms.length; i++)  {
                                    elms[i].style.display = "flex";
                                }
                                
                                
                                var elms2 = document.querySelectorAll("#bpa-front-data-empty-view");
                                for(var i = 0; i < elms2.length; i++)  {
                                   elms2[i].style.display = "flex";
                                }

                            }, 500);
                        }, 2000);
                    },
					generateSpamCaptcha(){
						const vm = this;
                        var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                        var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                        if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                        {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                        }
                        else {
                            bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                        }
						var postData = { action: "bookingpress_generate_spam_captcha", _wpnonce:bkp_wpnonce_pre_fetch };
							axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
						.then( function (response) {
							if(response.variant != "error" && (response.data.captcha_val != "" && response.data.captcha_val != undefined)){
								vm.appointment_step_form_data.spam_captcha = response.data.captcha_val;
							}else{
                                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                                if(typeof bkp_wpnonce_pre_fetch!="undefined" && bkp_wpnonce_pre_fetch!=null && response.data.updated_nonce!="")
                                {
                                    document.getElementById("_wpnonce").value = response.data.updated_nonce;
                                } else {
                                    vm.$notify({
                                        title: response.data.title,
                                        message: response.data.msg,
                                        type: response.data.variant,
                                        customClass: "error_notification"
                                    });
                                }
							}
						}.bind(this) )
						.catch( function (error) {
							console.log(error);
						});
					},
					loadSpamProtection(){
						const vm = this;
						vm.generateSpamCaptcha();
					},
                    bookingpress_price_with_currency_symbol( price_amount, ignore_symbol = false ){
                        const vm = this;
                        if( "String" == typeof price_amount ){
                            price_amount = parseFloat( price_amount );
                        }
                        
                        let currency_separator = vm.bookingpress_currency_separator;
                        let decimal_points = vm.bookingpress_decimal_points;

                        if( "comma-dot" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ".", ",", ignore_symbol );
                        } else if( "dot-comma" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ",", ".", ignore_symbol );
                        } else if( "space-dot" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ".", " ", ignore_symbol );
                        } else if( "space-comma" == currency_separator ){
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, ",", " ", ignore_symbol );
                        } else if( "Custom" == currency_separator){
                            let custom_comma_separator = vm.bookingpress_custom_comma_separator;
                            let custom_thousand_separator = vm.bookingpress_custom_thousand_separator;
                            price_amount = vm.bookingpress_number_format( price_amount, decimal_points, custom_comma_separator, custom_thousand_separator );
                        }

                        if( true == ignore_symbol ){
                            return price_amount;
                        }

                        let currency_symbol = vm.bookingpress_currency_symbol;
                        let currency_symbol_pos = vm.bookingpress_currency_symbol_position;

                        if( "before" == currency_symbol_pos ){
                            price_amount = currency_symbol + price_amount;
                        } else if( "before_with_space" == currency_symbol_pos ){
                            price_amount = currency_symbol + " " + price_amount;
                        } else if( "after" == currency_symbol_pos ){
                            price_amount = price_amount + currency_symbol;
                        } else if( "after_with_space" == currency_symbol_pos ){
                            price_amount = price_amount + " " + currency_symbol;
                        }

                        return price_amount;

                    },
                    bookingpress_number_format( number, decimals, decPoint, thousandsSep, skip_separator = false ){
                        number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
                        const n = !isFinite(+number) ? 0 : +number;
                        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
                        const sep = (typeof thousandsSep === "undefined") ? "," : thousandsSep;
                        const dec = (typeof decPoint === "undefined" || true == skip_separator ) ? "." : decPoint;
                        let s = "";
                        const toFixedFix = function (n, prec) {
                            if (("" + n).indexOf("e") === -1) {
                                return +(Math.round(n + "e+" + prec) + "e-" + prec);
                            } else {
                                const arr = ("" + n).split("e");
                                let sig = "";
                                if (+arr[1] + prec > 0) {
                                    sig = "+";
                                }
                                return (+(Math.round(+arr[0] + "e" + sig + (+arr[1] + prec)) + "e-" + prec)).toFixed(prec);
                            }
                        };
                        /* @todo: for IE parseFloat(0.55).toFixed(0) = 0; */
                        s = (prec ? toFixedFix(n, prec).toString() : "" + Math.round(n)).split(".");
                        if( false == skip_separator ){
                            if (s[0].length > 3) {
                                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                            }
                        }
                        if ((s[1] || "").length < prec) {
                            s[1] = s[1] || "";
                            s[1] += new Array(prec - s[1].length + 1).join("0");
                        }
                        if( true == skip_separator ){
                            return parseFloat( s.join(dec) );
                        } else {
                            return s.join(dec);
                        }
                    },
					' . $bookingpress_vue_methods_data . '
				},
			});';

            $bpa_script_data = " var app;  
			var is_script_loaded_$bookingpress_vue_root_element_id_el = false;
            bookingpress_beforeload_data = '';
            if( null != document.getElementById('$bookingpress_vue_root_element_id_without_hash') ){
                bookingpress_beforeload_data = document.getElementById('$bookingpress_vue_root_element_id_without_hash').innerHTML;
            }
            window.addEventListener('DOMContentLoaded', function() {
                if( is_script_loaded_$bookingpress_vue_root_element_id_el == false) {
                    is_script_loaded_$bookingpress_vue_root_element_id_el = true;
                    bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el();
                }
            });

            window.addEventListener( 'elementor/popup/show', (event) => {
                let element = event.detail.instance.\$element[0].querySelector('.bpa-frontend-main-container');
                if( 'undefined' != typeof element ){
                    document.getElementById('$bookingpress_vue_root_element_id_without_hash').innerHTML = bookingpress_beforeload_data;
                    bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el();
                }
            });

            function bpa_load_vue_shortcode_$bookingpress_vue_root_element_id_el(){
                {$bookingpress_script_return_data}
            }";
                
            if( $bookingress_load_js_css_all_pages == 'true' ){
                wp_enqueue_script('bookingpress_elements_locale');
                $bpa_script_data .= 'if( false == is_script_loaded_'.$bookingpress_vue_root_element_id_el.' ) {  is_script_loaded_'.$bookingpress_vue_root_element_id_el.' = true; bpa_load_vue_shortcode_'.$bookingpress_vue_root_element_id_el.'(); }';
            }
                
                wp_add_inline_script('bookingpress_elements_locale', $bpa_script_data, 'after');

                $bookingpress_custom_css = $BookingPress->bookingpress_get_customize_settings('custom_css', 'booking_form');            
                $bookingpress_custom_css = !empty($bookingpress_custom_css) ? stripslashes_deep( $bookingpress_custom_css ) : '';
                wp_add_inline_style( 'bookingpress_front_custom_css', $bookingpress_custom_css, 'after' );


                $this->bookingpress_form_category = 0;
                $this->bookingpress_form_service = 0 ;
                $this->bookingpress_hide_category_service= 0;
                $this->bookingpress_is_service_load_from_url = 0;
                $this->bookingpress_form_fields_error_msg_arr = array();
                $this->bookingpress_form_fields_new = array();

                return do_shortcode( $content );
            }
        
        /**
         * Hook for add data variables for Booking Form shortcode
         *
         * @param  mixed $bookingpress_dynamic_data_fields      Global data variable for Booking Form
         * @param  mixed $bookingpress_category                 Shortcode allowed category
         * @param  mixed $bookingpress_service                  Shortcode allowed service
         * @param  mixed $selected_service                      Shortcode default selected service
         * @param  mixed $selected_category                     Shortcode default selected category
         * @return void
         */
        function bookingpress_booking_dynamic_data_fields_func( $bookingpress_dynamic_data_fields, $bookingpress_category, $bookingpress_service, $selected_service,$selected_category )
        {
            global $wpdb, $BookingPress, $bookingpress_front_vue_data_fields, $tbl_bookingpress_customers, $tbl_bookingpress_categories, $tbl_bookingpress_services, $tbl_bookingpress_servicesmeta, $tbl_bookingpress_form_fields, $bookingpress_global_options;
            // Get categories

            $bookingpress_front_vue_data_fields['appointment_step_form_data']['is_waiting_list'] = 0;
            $bookingpress_search_query_where = 'WHERE 1=1 ';
            $bookingpress_search_query_join  = '';
            if (! empty($bookingpress_category) ) {
                $bookingpress_search_query_where .= " AND category.bookingpress_category_id IN ({$bookingpress_category})";
		        $bookingpress_front_vue_data_fields['appointment_step_form_data']['total_category'] = $bookingpress_category;
            }
            $bookingpress_search_query_join  .= "LEFT JOIN {$tbl_bookingpress_services} AS service ON category.bookingpress_category_id = service.bookingpress_category_id";
            $bookingpress_search_query_where .= ' AND category.bookingpress_category_id = service.bookingpress_category_id';
            if (! empty($bookingpress_service) ) {
                $bookingpress_search_query_where .= " AND service.bookingpress_service_id IN ({$bookingpress_service})";
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['total_services'] = $bookingpress_service;
            }

            
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['check_username_validation'] = false;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['invalid_customer_username'] = false;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['invalid_customer_message'] = 'Invalid Username';

            $bookingpress_search_query_where .= ' GROUP BY bookingpress_category_id';
            $bookingpress_service_categories  = $wpdb->get_results("SELECT category.* FROM {$tbl_bookingpress_categories} AS category {$bookingpress_search_query_join} {$bookingpress_search_query_where} ORDER BY bookingpress_category_position ASC", ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_categories is a table name. false alarm

            foreach (  $bookingpress_service_categories as $key => $val ) {
                $bookingpress_service_categories[$key]['bookingpress_category_name'] = stripslashes_deep($val['bookingpress_category_name']);                
                $bookingpress_service_categories[$key]['is_visible'] = true;
            }
            $bookingpress_front_vue_data_fields['service_categories'] = $bookingpress_service_categories;
            $default_service_category = ! empty($bookingpress_service_categories[0]['bookingpress_category_id']) ? $bookingpress_service_categories[0]['bookingpress_category_id'] : 0;
            $default_service_category  = empty($selected_category) ? $default_service_category : $selected_category; 
            

            
            //$bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] = $default_service_category;
            
            $all_categories = $bookingpress_front_vue_data_fields['bookingpress_all_categories'];


            
            if( !empty($all_categories) && count( $all_categories ) == 1 ){
                $first_category_data = $all_categories[ array_key_first( $all_categories ) ];
                if( 0 === $first_category_data['category_id'] ){

                }
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] = $all_categories[ array_key_first( $all_categories ) ]['category_id'];
                
            } else if( count( $all_categories ) > 1 ){
                $default_category = '';
                $n = 0;
                foreach( $all_categories as $all_cat_data ){
                    if( $n == 0 && $all_cat_data['category_id'] == 0 ){
                        $n++;
                        continue;
                    }
                    if( $n < 2 && empty( $default_category )){
                        $default_category = $all_cat_data['category_id'];
                        break;
                    }
                    $n++;
                }

                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] = $default_category;
            }
            
            $bookingpress_service_search_query_where = '';
            $bookingpress_service_cache_param = $default_service_category;
            
            if (! empty($bookingpress_service) ) {
                $bookingpress_service_search_query_where .= " AND bookingpress_service_id IN ({$bookingpress_service})";
                $bookingpress_service_cache_param .= '_' . $bookingpress_service;
            }

            //$service_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_services} WHERE bookingpress_category_id = {$default_service_category} {$bookingpress_service_search_query_where} ORDER BY bookingpress_service_position", ARRAY_A);               
            $all_service_data = $wpdb->get_results("SELECT * FROM {$tbl_bookingpress_services} WHERE 1=1 {$bookingpress_service_search_query_where} ORDER BY bookingpress_service_position", ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_services is a table name. false alarm
            $service_data = array();
            $temp_service_data = array();
            $bpa_services_categories_data = array();
            $all_services_data = array();
            $bookingpress_display_service_description = $BookingPress->bookingpress_get_customize_settings('display_service_description', 'booking_form');

            $all_service_data = apply_filters( 'bookingpress_remove_disabled_services', $all_service_data );

            $min_text = $BookingPress->bookingpress_get_customize_settings('book_appointment_min_text', 'booking_form'); 
            $hour_text = $BookingPress->bookingpress_get_customize_settings('book_appointment_hours_text', 'booking_form'); 

            $bookingpress_duration_suffix_labels = array(
                'm' => !empty( $min_text ) ? stripslashes_deep($min_text) : esc_html__('m', 'bookingpress-appointment-booking'),
                'h' => !empty( $hour_text ) ? stripslashes_deep($hour_text) : esc_html__('h', 'bookingpress-appointment-booking'),
            );

            $bookingpress_duration_suffix_labels = apply_filters( 'bookingpress_modify_service_duration_label', $bookingpress_duration_suffix_labels );
            

            $bookingpress_related_category_service = array();
            $bookingpress_all_service_data = (isset($bookingpress_front_vue_data_fields['bookingpress_all_services_data']))?$bookingpress_front_vue_data_fields['bookingpress_all_services_data']:array();
            foreach ( $all_service_data as $service_key => $service_val ) {
                $temp_service_data[ $service_key ] = $all_service_data[ $service_key ];

                $temp_service_data[ $service_key ]['bookingpress_service_duration_label'] = !empty( $bookingpress_duration_suffix_labels[ $service_val['bookingpress_service_duration_unit'] ] ) ? $bookingpress_duration_suffix_labels[ $service_val['bookingpress_service_duration_unit'] ] : $service_val['bookingpress_service_duration_unit'];
                $temp_service_data[ $service_key ]['service_position'] = $service_val['bookingpress_service_position'];
                $temp_service_data[ $service_key ]['service_price_without_currency'] = $service_val['bookingpress_service_price'];
                $temp_service_data[ $service_key ]['bookingpress_service_price']     = $BookingPress->bookingpress_price_formatter_with_currency_symbol($service_val['bookingpress_service_price']);
                $temp_service_data[ $service_key ]['bookingpress_service_name'] = stripslashes($service_val['bookingpress_service_name']);
                
                $service_id                              = $service_val['bookingpress_service_id'];                
                $service_img_details                     = '';
                if($service_id && !empty($bookingpress_all_service_data) && isset($bookingpress_all_service_data[$service_id]['services_meta'])){                    
                    $service_img_details  = (isset($bookingpress_all_service_data[$service_id]['services_meta']['service_image_details']))?$bookingpress_all_service_data[$service_id]['services_meta']['service_image_details']:array();
                    $service_img_details = (!empty($service_img_details))?$service_img_details:array();
                }else{                                        
                    $service_meta_details                    = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_servicesmeta} WHERE bookingpress_service_id = %d AND bookingpress_servicemeta_name = 'service_image_details'", $service_id), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_servicesmeta is table name defined globally. False Positive alarm                    
                    $service_img_details                     = ! empty($service_meta_details['bookingpress_servicemeta_value']) ? maybe_unserialize($service_meta_details['bookingpress_servicemeta_value']) : array();                        
                }

                $bpa_user_placeholder = !empty( $service_img_details[0]['url'] ) ? false : true;
                $temp_service_data[ $service_key ]['img_url'] = ! empty($service_img_details[0]['url']) ? $service_img_details[0]['url'] : BOOKINGPRESS_URL . '/images/placeholder-img.jpg';
                $temp_service_data[ $service_key ]['use_placeholder'] = $bpa_user_placeholder;
                $temp_service_data[ $service_key ]['is_visible'] = true;
                
                $default_service_description = ! empty($service_val['bookingpress_service_description']) ? $service_val['bookingpress_service_description'] : '';
                
                if ($bookingpress_display_service_description == 'false' ) {
                    $temp_service_data[ $service_key ]['display_read_more_less']           = 1;
                    $temp_service_data[ $service_key ]['bookingpress_service_description'] = stripslashes_deep($default_service_description);
                    if (strlen($default_service_description) > 140 ) {
                        $temp_service_data[ $service_key ]['bookingpress_service_description_with_excerpt'] = stripslashes(substr($default_service_description, 0, 140));
                        $temp_service_data[ $service_key ]['display_details_more']                          = 0;
                        $temp_service_data[ $service_key ]['display_details_less']                          = 1;
                    } else {
                        $temp_service_data[ $service_key ]['display_read_more_less'] = 0;
                    }
                }   
                if($service_val['bookingpress_category_id'] || $service_val['bookingpress_category_id'] == 0){
                    $bookingpress_related_category_service[$service_val['bookingpress_category_id']][] = $service_val['bookingpress_service_id']; 
                }

                if( $service_val['bookingpress_category_id'] == $default_service_category ){
                    $service_data[ $service_key ] = $temp_service_data[ $service_key ];
                }
                if( empty( $bpa_services_categories_data[ $service_val['bookingpress_category_id'] ] ) ){
                    $bpa_services_categories_data[ $service_val['bookingpress_category_id'] ] = array(); 
                }
                $all_services_data[ $service_key ] = $temp_service_data[ $service_key ];
                $bpa_services_categories_data[ $service_val['bookingpress_category_id'] ][] = $temp_service_data[ $service_key ];
            }

            if ($bookingpress_display_service_description == 'false' ) {
                $bookingpress_front_vue_data_fields['display_service_description'] = '1';
            }  

            $bookingpress_front_vue_data_fields['services_data'] = $service_data;
            
            $bookingpress_front_vue_data_fields['bpa_services_data_from_categories'] = $bpa_services_categories_data;
            $bookingpress_front_vue_data_fields['all_services_data'] = $all_services_data;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['related_category_service'] = $bookingpress_related_category_service;

            $bookingpress_is_uncategorized_service_added = 0;
            foreach($all_services_data as $ser_key => $ser_val){
                if(empty($ser_val['bookingpress_category_id']) && empty($bookingpress_service) && empty($bookingpress_category)){
                    $bookingpress_is_uncategorized_service_added = 1;
                    break;
                }else if(empty($ser_val['bookingpress_category_id']) && !empty($bookingpress_service) && empty($bookingpress_category)){
                    $bookingpress_is_uncategorized_service_added = 1;
                    break;
                }
            }
            $bookingpress_front_vue_data_fields['is_uncategorize_service_added'] = $bookingpress_is_uncategorized_service_added;
            $default_service_id = 0;
            
            $default_service_name = $default_price = $default_service_duration = $default_service_duration_unit = $default_price_with_currency = "";
            $service_data= array_values($service_data);
            if(!empty($service_data)) {
                foreach($service_data as $key => $val) {
                    if((!empty($selected_service) ) ) {                    
                        if($selected_service == $val['bookingpress_service_id']) {
                            $default_service_id                                  = ! empty($val['bookingpress_service_id']) ? $val['bookingpress_service_id'] : 0;
                            $default_service_name                                = ! empty($val['bookingpress_service_name']) ? stripslashes_deep($val['bookingpress_service_name']) : '';
                            $default_price                                       = ! empty($val['bookingpress_service_price']) ? $val['bookingpress_service_price'] : 0;
                            $default_price_with_currency                         = ! empty($val['service_price_without_currency']) ? $val['service_price_without_currency'] : 0;
                            $default_service_duration_unit                         = ! empty($val['bookingpress_service_duration_unit']) ? $val
                            ['bookingpress_service_duration_unit'] : '';
                            $default_service_duration                         = ! empty($val['bookingpress_service_duration_val']) ? $val
                            ['bookingpress_service_duration_val'] : '';                            
                            
                        }
                    } else {
                        $default_service_id   = ! empty($service_data[0]['bookingpress_service_id']) ? $service_data[0]['bookingpress_service_id'] : $all_services_data[0]['bookingpress_service_id'];
                        $default_service_name  = ! empty($service_data[0]['bookingpress_service_name']) ? stripslashes($service_data[0]['bookingpress_service_name']) : '';
                        $default_price   = ! empty($service_data[0]['bookingpress_service_price']) ? $service_data[0]['bookingpress_service_price'] : 0;
                        $default_price_with_currency  = ! empty($service_data[0]['service_price_without_currency']) ? $service_data[0]['service_price_without_currency'] : 0;
                        $default_service_duration_unit= ! empty($service_data[0]['bookingpress_service_duration_unit']) ? $service_data[0]
                        ['bookingpress_service_duration_unit'] : '';
                        $default_service_duration                         = ! empty($service_data[0]['bookingpress_service_duration_val']) ? $service_data[0]
                        ['bookingpress_service_duration_val'] : '';                            
                    }
                }
            }
            
            if(empty($default_service_id) && !empty($selected_service)){
                //If default no service selected and selected service parameter pass from booking form shortcode then this condition will executed
                foreach($all_services_data as $key => $val) {
                    if((!empty($selected_service) ) ) {                    
                        if($selected_service == $val['bookingpress_service_id']) {
                            $default_service_id                                  = ! empty($val['bookingpress_service_id']) ? $val['bookingpress_service_id'] : 0;
                            $default_service_name                                = ! empty($val['bookingpress_service_name']) ? stripslashes_deep($val['bookingpress_service_name']) : '';
                            $default_price                                       = ! empty($val['bookingpress_service_price']) ? $val['bookingpress_service_price'] : 0;
                            $default_price_with_currency                         = ! empty($val['service_price_without_currency']) ? $val['service_price_without_currency'] : 0;
                            $default_service_duration_unit                         = ! empty($val['bookingpress_service_duration_unit']) ? $val
                            ['bookingpress_service_duration_unit'] : '';
                            $default_service_duration                         = ! empty($val['bookingpress_service_duration_val']) ? $val
                            ['bookingpress_service_duration_val'] : '';
                        }
                    }
                }
            }

            $bookingpress_is_hide_category_service_selection = $BookingPress->bookingpress_get_customize_settings('hide_category_service_selection', 'booking_form');

            $bpa_move_from_service_selection_step = false;
            
            if( empty( $default_service_id ) ){
                $default_service_id = apply_filters( 'bookingpress_modify_default_servide_id', $default_service_id, $bookingpress_front_vue_data_fields );
            }

			if ( $bookingpress_is_hide_category_service_selection == 'true' || ! empty( $selected_service ) || ( !empty($bookingpress_service) && (count($all_services_data) == 1) && empty($bookingpress_category) ) ) {
                // If hide category service step option enabled then by default service selected
                // If only 1 service display from shortcode parameter then by default that 1 service also selected automatically
                // If there is any service selected from parameter then also service automatically selected
                
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service']               = $default_service_id;
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service_name']          = $default_service_name;
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service_price']         = $default_price;
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['service_price_without_currency'] = $default_price_with_currency;
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service_duration_unit'] = $default_service_duration_unit;
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service_duration'] = $default_service_duration;

                $bookingpress_front_vue_data_fields['displayResponsiveCalendar'] = "1";
            }
            
            if( 'true' == $bookingpress_is_hide_category_service_selection ){
                $bpa_move_from_service_selection_step = true;
            } else if( !empty( $default_service_id ) && 1 == $this->bookingpress_is_service_load_from_url ){
                $bpa_move_from_service_selection_step = true;
            }

            $bpa_move_from_service_selection_step = apply_filters( 'bookingpress_check_flag_to_move_next_from_serfice', $bpa_move_from_service_selection_step, $bookingpress_front_vue_data_fields );

            $on_site_payment = $BookingPress->bookingpress_get_settings('on_site_payment', 'payment_setting');
            $paypal_payment  = $BookingPress->bookingpress_get_settings('paypal_payment', 'payment_setting');

            $bookingpress_front_vue_data_fields['on_site_payment'] = $on_site_payment;
            $bookingpress_front_vue_data_fields['paypal_payment']  = $paypal_payment;

            $bookingpress_total_configure_gateways = 0;
            $bookingpress_is_only_onsite_enabled   = 0;
            if (( $on_site_payment == 'true' || $on_site_payment == '1' ) && ( $paypal_payment == 'true' || $paypal_payment == '1' ) ) {
                $bookingpress_total_configure_gateways = 2;
                $bookingpress_is_only_onsite_enabled   = 0;
            } elseif (( $on_site_payment == 'true' || $on_site_payment == '1' ) && ( $paypal_payment == 'false' || empty($paypal_payment) ) ) {
                $bookingpress_total_configure_gateways = 1;
                $bookingpress_is_only_onsite_enabled   = 1;
            } elseif (( $on_site_payment == 'false' || empty($on_site_payment) ) && ( $paypal_payment == 'true' || $paypal_payment == '1' ) ) {
                $bookingpress_total_configure_gateways = 1;
                $bookingpress_is_only_onsite_enabled   = 0;
            }

            $bookingpress_front_vue_data_fields['total_configure_gateways'] = $bookingpress_total_configure_gateways;
            $bookingpress_front_vue_data_fields['is_only_onsite_enabled']   = $bookingpress_is_only_onsite_enabled;

            if ($bookingpress_is_only_onsite_enabled == 1 ) {
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_payment_method'] = 'on-site';
            }

            if ($bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_payment_method'] == '' && ( $paypal_payment == 'true' ) ) {
                $bookingpress_front_vue_data_fields['paypal_payment'] = 'paypal';
            }

            $bookingpress_front_vue_data_fields['check_bookingpress_username_set'] = 0;
            $bookingpress_front_vue_data_fields['bpa_check_user_login'] = 0;

            if (is_user_logged_in() ) {
                $current_user_id               = get_current_user_id();
                $bookingpress_current_user_obj = new WP_User($current_user_id);
                $bookingpress_front_vue_data_fields['bpa_check_user_login'] = 1;

                $get_current_user_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_customers} WHERE bookingpress_wpuser_id = %d AND bookingpress_user_type = 2", $current_user_id ), ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_bookingpress_customers is table name defined globally. False Positive alarm
                if (! empty($get_current_user_data) ) {
                        $bookingpress_firstname = stripslashes_deep($get_current_user_data['bookingpress_user_firstname']);
                        $bookingpress_lastname = stripslashes_deep($get_current_user_data['bookingpress_user_lastname']);
                        $bookingpress_customername = !empty($get_current_user_data['bookingpress_user_name']) ? stripslashes_deep($get_current_user_data['bookingpress_user_name']) : '';
                        $bookingpress_customername_full_name = !empty($get_current_user_data['bookingpress_customer_full_name']) ? stripslashes_deep($get_current_user_data['bookingpress_customer_full_name']) : '';

                        /* if( empty($bookingpress_customername_full_name)){
                            $bookingpress_customername_full_name = $bookingpress_customername;
                        } */

                        if( !empty($bookingpress_customername)){
                            $bookingpress_front_vue_data_fields['check_bookingpress_username_set'] = 1;
                        }

                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_name'])) {                            
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_name'] = stripslashes_deep($bookingpress_customername_full_name);
                        }
                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_username'])) {                            
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_username'] = stripslashes_deep($bookingpress_customername);
                        }
                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_phone'])) {                            
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_phone'] = $get_current_user_data['bookingpress_user_phone'];
                        }
                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_email'])) {
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_email'] = stripslashes_deep($get_current_user_data['bookingpress_user_email']);
                        }
                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_firstname'])) {
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_firstname'] = stripslashes_deep($bookingpress_firstname);
                        }
                        if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_lastname'])) {
                            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_lastname']  = stripslashes_deep($bookingpress_lastname);
                        }
                } elseif (! empty($current_user_id) && ! empty($bookingpress_current_user_obj) ) {
                    $bookingpress_customer_name  = ! empty($bookingpress_current_user_obj->data->user_login) ? $bookingpress_current_user_obj->data->user_login : '';
                    $bookingpress_customer_email = ! empty($bookingpress_current_user_obj->data->user_email) ? $bookingpress_current_user_obj->data->user_email : '';
                    $bookingpress_firstname      = get_user_meta($current_user_id, 'first_name', true);
                    $bookingpress_lastname       = get_user_meta($current_user_id, 'last_name', true);

                    if( !empty($bookingpress_customer_name)){
                        $bookingpress_front_vue_data_fields['check_bookingpress_username_set'] = 1;
                    }
                    
                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_username'])) {                            
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_username'] = stripslashes_deep($bookingpress_customer_name);
                    }

                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_name'])) {
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_name'] = stripslashes_deep($bookingpress_customer_name);
                    }                    
                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_email'])) {
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_email']     = stripslashes_deep($bookingpress_customer_email);
                    }
                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_firstname'])) {
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_firstname'] = stripslashes_deep($bookingpress_firstname);
                    }
                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_lastname'])) {
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_lastname']  = stripslashes_deep($bookingpress_lastname);
                    }
                }
            }
            $bookingpress_phone_mandatory_option = $BookingPress->bookingpress_get_settings('phone_number_mandatory', 'general_setting');
            if (! empty($bookingpress_phone_mandatory_option) && $bookingpress_phone_mandatory_option == 'true' ) {
                $mandatory_field_data = array(
                'required' => true,
                'message'  => __('Please enter customer phone number', 'bookingpress-appointment-booking'),
                'trigger'  => 'blur',
                );
                $bookingpress_front_vue_data_fields['customer_details_rule']['customer_phone'] = $mandatory_field_data;
            }

            $bookingpress_phone_country_option = $BookingPress->bookingpress_get_settings('default_phone_country_code', 'general_setting');
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_phone_country'] = $bookingpress_phone_country_option;
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['customer_phone_dial_code'] = '';
            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_customer_timezone'] = $bookingpress_global_options->bookingpress_get_site_timezone_offset();

            $bookingpress_front_vue_data_fields['bookingpress_tel_input_props'] = array(
                'defaultCountry' => $bookingpress_phone_country_option,
                'inputOptions'   => array(
                    'placeholder' => '',
                ),
                'validCharactersOnly' => true,
            );

            $default_daysoff_details = $BookingPress->bookingpress_get_default_dayoff_dates();
            $disabled_date           = implode(',', $default_daysoff_details);
            $bookingpress_front_vue_data_fields['days_off_disabled_dates'] = $disabled_date;

            $bookingpress_front_vue_data_fields['v_calendar_disable_dates'] = array();
            $bookingpress_front_vue_data_fields['v_calendar_attributes'] = array();
            $bookingpress_front_vue_data_fields['v_calendar_attributes_current'] = array();
            $bookingpress_front_vue_data_fields['v_calendar_default_label'] = array();

            $bookingpress_front_vue_data_fields['v_calendar_check_month_dates'] = false;
            $bookingpress_front_vue_data_fields['v_calendar_next_month_dates'] = array();

            $bookingpress_selected_date = $BookingPress->bookingpress_select_date_before_load();
            
            if (! empty($bookingpress_selected_date) ) {
                $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_date'] = $bookingpress_selected_date;
            }
            $bookingpress_front_vue_data_fields['bookingpress_activate_payment_gateway_counter'] = 0;

            if($bookingpress_front_vue_data_fields['bookingpress_activate_payment_gateway_counter'] > 0) {
				$bookingpress_front_vue_data_fields['is_only_onsite_enabled']   = 0;
			}

            $bookingpress_customize_settings = $BookingPress->bookingpress_get_customize_settings(
                array(
                    'service_title',
                    'datetime_title',
                    'basic_details_title',
                    'summary_title',
                    'hide_category_service_selection'
                ),
                'booking_form'
            );    
            
            $bookingpress_hide_category_service_selection = stripslashes_deep($bookingpress_customize_settings['hide_category_service_selection']);
            $bookingpress_is_loaded_from_share_url = false;
            if(!empty($_GET['s_id']) && (isset($_GET['allow_modify']) && $_GET['allow_modify'] == '0' ) ){
                $bookingpress_hide_category_service_selection = 'true';
                $bookingpress_is_loaded_from_share_url = true;
            }else if(!empty($_GET['s_id']) && (isset($_GET['allow_modify']) && $_GET['allow_modify'] == '1' ) ){
                $bookingpress_hide_category_service_selection = 'false';
                $bookingpress_is_loaded_from_share_url = true;
            }
            
            if( 'true' == $bookingpress_hide_category_service_selection && empty( $selected_service ) && true == $bookingpress_is_loaded_from_share_url ){
                $bookingpress_hide_category_service_selection = 'false';
            }

            $bookingpress_service_tab_name  = stripslashes_deep($bookingpress_customize_settings['service_title']);
            $bookingpress_datetime_tab_name = stripslashes_deep($bookingpress_customize_settings['datetime_title']);//
            $bookingpress_basic_details_tab_name  = stripslashes_deep($bookingpress_customize_settings['basic_details_title']);
            $bookingpress_summary_tab_name = stripslashes_deep($bookingpress_customize_settings['summary_title']);

            $no_service_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_service_selected_for_the_booking', 'message_setting');

            $no_appointment_date_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_date_selected_for_the_booking', 'message_setting');

            $no_appointment_time_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_time_selected_for_the_booking', 'message_setting');

            $no_payment_method_is_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_payment_method_is_selected_for_the_booking', 'message_setting');

            $bookingpress_date_time_step_note = $BookingPress->bookingpress_get_customize_settings('date_time_step_note','booking_form');
            $bookingpress_date_time_step_note = !empty($bookingpress_date_time_step_note) ? stripslashes_deep($bookingpress_date_time_step_note) : '';
            $bookingpress_front_vue_data_fields['date_time_step_note'] = $bookingpress_date_time_step_note;

            $bookingpress_summary_step_note = $BookingPress->bookingpress_get_customize_settings('summary_step_note','booking_form');
            $bookingpress_summary_step_note = !empty($bookingpress_summary_step_note) ? stripslashes_deep($bookingpress_summary_step_note) : '';
            $bookingpress_front_vue_data_fields['summary_step_note'] = $bookingpress_summary_step_note;

            $bookingpress_sidebar_steps_data = array(
                'service' => array(
                    'tab_name' => $bookingpress_service_tab_name,
                    'tab_value' => 'service',
                    'tab_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13H5c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zM7 19c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM19 3H5c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM7 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>',
                    'next_tab_name' => 'datetime',
                    'next_tab_label' => '',
                    'previous_tab_name' => '',
                    'validate_fields' => array(
                        'selected_service',
                    ),
                    'auto_focus_tab_callback' => array(),
                    'validation_msg' => array(
                        'selected_service' => stripslashes_deep($no_service_selected_for_the_booking),
                    ),
                    'is_allow_navigate' => 1,
                    'is_navigate_to_next' => $bpa_move_from_service_selection_step,
                    'is_display_step' => 1,
                    'sorting_key' => 'service_selection',
                ),
                'datetime' => array(
                    'tab_name' => $bookingpress_datetime_tab_name,
                    'tab_value' => 'datetime',
                    'tab_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 4h-1V3c0-.55-.45-1-1-1s-1 .45-1 1v1H8V3c0-.55-.45-1-1-1s-1 .45-1 1v1H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 15c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1V9h14v10zM7 11h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>',
                    'next_tab_name' => 'basic_details',
                    'previous_tab_name' => 'service',
                    'auto_focus_tab_callback' => array(
                        'bookingpress_disable_date' => array()
                    ),
                    'validate_fields' => array(
                        'selected_date',
                        'selected_start_time',
                    ),
                    'validation_msg' => array(
                        'selected_date' => stripslashes_deep($no_appointment_date_selected_for_the_booking),
                        'selected_start_time' => stripslashes_deep($no_appointment_time_selected_for_the_booking),
                    ),
                    'is_allow_navigate' => 0,
                    'is_display_step' => 1,
                    'is_navigate_to_next' => false,
                    'sorting_key' => 'datetime_selection',
                ),
                'basic_details' => array(
                    'tab_name' => $bookingpress_basic_details_tab_name,
                    'tab_value' => 'basic_details',
                    'tab_icon' => '<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><g><rect fill="none" height="24" width="24"/><path d="M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M13,17H8c-0.55,0-1-0.45-1-1 c0-0.55,0.45-1,1-1h5c0.55,0,1,0.45,1,1C14,16.55,13.55,17,13,17z M16,13H8c-0.55,0-1-0.45-1-1c0-0.55,0.45-1,1-1h8 c0.55,0,1,0.45,1,1C17,12.55,16.55,13,16,13z M16,9H8C7.45,9,7,8.55,7,8c0-0.55,0.45-1,1-1h8c0.55,0,1,0.45,1,1 C17,8.55,16.55,9,16,9z"/></g></svg>',
                    'auto_focus_tab_callback' => array(),
                    'next_tab_name' => 'summary',
                    'previous_tab_name' => 'datetime',
                    'validate_fields' => array(),
                    'is_allow_navigate' => 0,
                    'is_display_step' => 1,
                    'is_navigate_to_next' => false,
                    'sorting_key' => 'basic_details_selection',
                ),
                'summary' => array(
                    'tab_name' => $bookingpress_summary_tab_name,
                    'tab_value' => 'summary',
                    'tab_icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9.29 16.29L6.7 13.7c-.39-.39-.39-1.02 0-1.41.39-.39 1.02-.39 1.41 0L10 14.17l5.88-5.88c.39-.39 1.02-.39 1.41 0 .39.39.39 1.02 0 1.41l-6.59 6.59c-.38.39-1.02.39-1.41 0z"/></svg>',
                    'next_tab_name' => 'summary',
                    'auto_focus_tab_callback' => array(),
                    'previous_tab_name' => 'basic_details',
                    'validate_fields' => array(),
                    'is_allow_navigate' => 0,
                    'is_display_step' => 1,
                    'is_navigate_to_next' => false,
                    'sorting_key' => 'summary_selection',
                ),
            );
            
            
            if($bookingpress_hide_category_service_selection == 'true'){
                if( $BookingPress->bpa_is_pro_exists() && $BookingPress->bpa_is_pro_active() ){
                    if( !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '1.5', '>' ) ){
                        $bookingpress_sidebar_steps_data['service']['is_display_step'] = 0;
                    } else {
                         /** remove service step when pro version is lower than 1.5 */
                        unset( $bookingpress_sidebar_steps_data['service'] );
                    }
                } else {
                    $bookingpress_sidebar_steps_data['service']['is_display_step'] = 0;
                }
            }

            /** compatibility change for pro version lower than 1.7 */
            if( $BookingPress->bpa_is_pro_exists() && $BookingPress->bpa_is_pro_active() ){
                if( !empty( $BookingPress->bpa_pro_plugin_version() ) && version_compare( $BookingPress->bpa_pro_plugin_version(), '1.7', '<' ) ){
                    $bookingpress_sidebar_steps_data['service']['tab_icon'] = 'dns';
                    $bookingpress_sidebar_steps_data['datetime']['tab_icon'] = 'date_range';
                    $bookingpress_sidebar_steps_data['basic_details']['tab_icon'] = 'article';
                    $bookingpress_sidebar_steps_data['summary']['tab_icon'] = 'assignment_turned_in';
                }
            }
            /** compatibility change for pro version lower than 1.7 */
            
            $bookingpress_front_vue_data_fields['bookingpress_sidebar_step_data'] = $bookingpress_sidebar_steps_data;
            $bookingpress_front_vue_data_fields['isLoadClass'] = 1;

            $bookingpress_front_vue_data_fields['bookingpress_external_html'] = '';
			$bookingpress_front_vue_data_fields['bookingpress_is_display_external_html'] = true;

            $bookingpress_decimal_points = $BookingPress->bookingpress_get_settings('price_number_of_decimals', 'payment_setting');
            $bookingpress_decimal_points = intval($bookingpress_decimal_points);
            $bookingpress_front_vue_data_fields['bookingpress_decimal_points'] = $bookingpress_decimal_points;

            $bookingpress_currency_separator = $BookingPress->bookingpress_get_settings('price_separator', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_currency_separator'] = $bookingpress_currency_separator;

            $bookingpress_currency_name = $BookingPress->bookingpress_get_settings('payment_default_currency', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_currency_name'] = $bookingpress_currency_name;

            $bookingpress_front_vue_data_fields['bookingpress_currency_symbol'] = $BookingPress->bookingpress_get_currency_symbol($bookingpress_currency_name);

            $bookingpress_price_symbol_position = $BookingPress->bookingpress_get_settings('price_symbol_position', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_currency_symbol_position'] = $bookingpress_price_symbol_position;

            $bookingpress_custom_comma_separator = $BookingPress->bookingpress_get_settings('custom_comma_separator', 'payment_setting');
            $bookingpress_custom_thousand_separator = $BookingPress->bookingpress_get_settings('custom_dot_separator', 'payment_setting');
            $bookingpress_front_vue_data_fields['bookingpress_custom_comma_separator'] = $bookingpress_custom_comma_separator;
            $bookingpress_front_vue_data_fields['bookingpress_custom_thousand_separator'] = $bookingpress_custom_thousand_separator;

            $selected_service = !empty( $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service'] ) ? $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_service'] : '';
			$default_selected_category = !empty( $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] ) ? $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] : '';

            if( !empty( $selected_service ) ){
				$selected_services_data = $bookingpress_front_vue_data_fields['bookingpress_all_services_data'][ $selected_service ];

				$selected_service_category = $selected_services_data['bookingpress_category_id'];

				if( $selected_service_category != $default_selected_category ){
					$bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] = $selected_service_category;
				}
			}

            $bookingpress_default_select_all_category = $BookingPress->bookingpress_get_customize_settings('default_select_all_category','booking_form');
            if(!empty($bookingpress_default_select_all_category ) && $bookingpress_default_select_all_category == 'true') {                
                $hide_category_service_selection = $BookingPress->bookingpress_get_customize_settings('hide_category_service_selection','booking_form');
                if($hide_category_service_selection != 'true') {
                    if(isset($bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'])){
                        $bookingpress_front_vue_data_fields['appointment_step_form_data']['selected_category'] = 0;
                    }
                }
            }

            $bookingpress_front_vue_data_fields['bookingpress_selected_date_range'] = array();

            $bookingpress_front_vue_data_fields['vue_tel_mode'] = 'international';
            $bookingpress_front_vue_data_fields['vue_tel_auto_format'] = true;

            $bookingpress_front_vue_data_fields['appointment_step_form_data']['bookingpress_form_token'] = '';

            $bookingpress_front_vue_data_fields = apply_filters('bookingpress_frontend_apointment_form_add_dynamic_data', $bookingpress_front_vue_data_fields);
            
            $bookingpress_dynamic_data_fields = wp_json_encode($bookingpress_front_vue_data_fields);
            return $bookingpress_dynamic_data_fields;
        }
        
        /**
         * Callback function for [bookingpress_appointment_calendar_integration] shortcode
         *
         * @param  mixed $atts
         * @param  mixed $content
         * @param  mixed $tag
         * @return void
         */
        function bookingpress_booking_calendar_options($atts, $content, $tag) {
            global $wpdb, $tbl_bookingpress_appointment_bookings, $tbl_bookingpress_entries;
            $defaults = array(
                'gateways'  => 'google,yahoo,outlook,ical',
                'gateways_label' => '',
                'button_spacing' => '12'
            );
            $args = shortcode_atts($defaults, $atts, $tag);
            extract($args);       
            $bookingpress_calendar_list = array();                
            $bookingpress_default_arr = array('google' => __('Google Calendar','bookingpress-appointment-booking'),
                'yahoo'=> __('Yahoo Calendar','bookingpress-appointment-booking'),
                'outlook'=>  __('Outlook Calendar','bookingpress-appointment-booking'),
                'ical'=>  __('iCal Calendar','bookingpress-appointment-booking'),
            );   
            $bookingpress_default_arr2 = array('google' => 'google_calendar' ,
                'yahoo'=> 'yahoo_calendar' ,
                'outlook'=> 'outlook_calendar',
                'ical'=> 'ical_calendar',
            );   
            if(!empty($gateways)) {                
                $gateways = explode(',',$gateways);        
                $gateways_label = explode(',',$gateways_label); 
                foreach($gateways as $key => $value ) {                              
                    if(array_key_exists($value,$bookingpress_default_arr) ) {
                        $label_value =!empty($gateways_label[$key]) ? sanitize_text_field($gateways_label[$key]) : $bookingpress_default_arr[$value];        
                        if(!empty($label_value)) {
                            $bookingpress_calendar_list[] = array(                                                
                                'value' => $bookingpress_default_arr2[$value],                        
                                'name' => $label_value, 
                            );
                        }
                    }
                }          
            }        
            $this->bookingpress_calendar_list = wp_json_encode($bookingpress_calendar_list);

			global $BookingPress;
			$BookingPress->set_front_css( 1 );
			$BookingPress->set_front_js( 1 );
            $BookingPress->bookingpress_load_booking_form_custom_css();

            $bookingpress_is_render_calendar = 1;
            $bookingpress_calendar_html = "";

            $appointment_id = 0;
            if(!empty($_GET['appointment_id'])){
                $bookingpress_nonce_val = !empty($_GET['bp_tp_nonce']) ? sanitize_text_field($_GET['bp_tp_nonce']) : '';
                $bookingpress_verification_hash = !empty($_GET['appointment_id']) ? md5(base64_decode(sanitize_text_field($_GET['appointment_id']))) : '';
                $bookingpress_nonce_verification = wp_verify_nonce($bookingpress_nonce_val, 'bpa_nonce_url-'.$bookingpress_verification_hash);
                $appointment_id = base64_decode(sanitize_text_field($_GET['appointment_id']));
                if(!$bookingpress_nonce_verification){
                    $bookingpress_is_render_calendar = 0;
                }
            }

            $bpa_calendar_details = $this->bookingpress_get_appointment_details_for_calendar_func($appointment_id,true);
            
            $bpa_google_data = $bpa_calendar_details['google_calendar_link'];
            $bpa_yahoo_data =  $bpa_calendar_details['yahoo_calendar_link'];
            
            $bpa_google_link = "https://www.google.com/calendar/render?action=TEMPLATE&text=$bpa_google_data";
            $bpa_yahoo_link = "http://calendar.yahoo.com/?v=60&view=d&type=20&title=$bpa_yahoo_data";

            $this->bookingpress_google_calendar_link = $bpa_google_link;
            $this->bookingpress_yahoo_calendar_link = $bpa_yahoo_link;

            if($bookingpress_is_render_calendar){
                $bookingpress_calendar_html = '<div id="bpa-front-module-calendar-integration">';
                    $bookingpress_calendar_html     .= '<div class="bpa-front-module--atc-wrapper">';					
                                $bookingpress_calendar_html .= '								
                                        <div v-for="item in bookingpress_calendar_list" :class="\'bpa-front-module--atc__item bpa-fm--atc__\'+item.value" style="margin:0px '.$button_spacing.'px '.$button_spacing.'px 0px">
                                            <el-button class="bpa-front-btn bpa-front-btn__medium bpa-front-btn--full-width" id="bookingpress_ical_calendar" v-if="item.value == \'ical_calendar\'">
                                                <span>
                                                    <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1235_2762)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.21165 1.39313C8.26508 0.00788564 9.72934 0 9.72934 0C9.72934 0 9.94793 1.30376 8.89977 2.55758C7.78313 3.89814 6.51375 3.67734 6.51375 3.67734C6.51375 3.67734 6.2741 2.6233 7.21165 1.39313Z" fill="black"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.64714 4.59251C7.18965 4.59251 8.19568 3.84863 9.50456 3.84863C11.7589 3.84863 12.6438 5.44942 12.6438 5.44942C12.6438 5.44942 10.9109 6.33524 10.9109 8.48014C10.9109 10.901 13.0704 11.7369 13.0704 11.7369C13.0704 11.7369 11.5614 15.9794 9.52037 15.9794C8.58281 15.9794 7.85595 15.3486 6.86836 15.3486C5.86233 15.3486 4.86421 16.0031 4.21372 16.0031C2.35178 16.0004 0 11.9787 0 8.743C0 5.55982 1.99098 3.89069 3.85818 3.89069C5.07226 3.89332 6.01508 4.59251 6.64714 4.59251Z" fill="black"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1235_2762">
                                                        <rect width="13.0704" height="16" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>  
                                                {{ item.name}}
                                            </el-button>           
                                            <el-link :href="bookingpress_google_calendar_link" target="_blank" class="bpa-front-btn bpa-front-btn__medium bpa-front-btn--full-width bpa-front-link--no-underline" id="bookingpress_google_calendar" v-if="item.value == \'google_calendar\'">
                                                <span>
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6444 8.17812C15.6444 7.64479 15.5556 7.02257 15.4667 6.57812H8V9.68924H12.2667C12.0889 10.667 11.5556 11.467 10.6667 12.0892V14.1337H13.3333C14.8444 12.7115 15.6444 10.5781 15.6444 8.17812Z" fill="#4285F4"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99978 15.9996C10.1331 15.9996 11.9998 15.2885 13.3331 14.0441L10.6664 12.0885C9.95534 12.5329 9.06645 12.8885 7.99978 12.8885C5.95534 12.8885 4.17756 11.4663 3.55534 9.59961H0.888672V11.5552C2.13312 14.2218 4.88867 15.9996 7.99978 15.9996Z" fill="#34A853"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.55556 9.511C3.37778 9.06656 3.28889 8.53322 3.28889 7.99989C3.28889 7.46656 3.37778 6.93322 3.55556 6.48878V4.44434H0.888889C0.355556 5.511 0 6.75545 0 7.99989C0 9.24434 0.266667 10.4888 0.888889 11.5554L3.55556 9.511Z" fill="#FBBC05"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99978 3.2C9.15534 3.2 10.222 3.64444 11.022 4.35556L13.3331 2.04444C11.9998 0.8 10.1331 0 7.99978 0C4.88867 0 2.13312 1.77778 0.888672 4.44444L3.55534 6.48889C4.17756 4.62222 5.95534 3.2 7.99978 3.2Z" fill="#EA4335"/>
                                                    </svg>
                                                </span>
                                                {{ item.name}}
                                            </el-link>                                             
                                            <el-button class="bpa-front-btn bpa-front-btn__medium bpa-front-btn--full-width" id="bookingpress_outlook_calendar" v-if="item.value ==  \'outlook_calendar\'">                                                
                                                <span>
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1235_2768)">
                                                        <path d="M7.57897 0H0V7.57897H7.57897V0Z" fill="#F25022"/>
                                                        <path d="M7.57897 8.4209H0V15.9999H7.57897V8.4209Z" fill="#00A4EF"/>
                                                        <path d="M16.0008 0H8.42188V7.57897H16.0008V0Z" fill="#7FBA00"/>
                                                        <path d="M16.0008 8.4209H8.42188V15.9999H16.0008V8.4209Z" fill="#FFB900"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1235_2768">
                                                        <rect width="16" height="16" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>
                                                </span>                                     
                                                {{ item.name}}
                                            </el-button>                                             
                                            <el-link :href="bookingpress_yahoo_calendar_link" target="_blank" class="bpa-front-btn bpa-front-btn__medium bpa-front-btn--full-width bpa-front-link--no-underline" id="bookingpress_yahoo_calendar" v-if="item.value == 
                                            \'yahoo_calendar\'">                                                
                                                <span>
                                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_1235_2766)">
                                                        <path d="M0 3.89506H3.43247L5.43118 9.00836L7.45588 3.89506H10.7976L5.76558 16H2.40215L3.77968 12.7924L0.000106295 3.89506H0ZM14.6891 7.98076H10.9461L14.2682 0L17.9975 0.000159442L14.6891 7.98076V7.98076ZM11.9266 8.74459C13.075 8.74459 14.006 9.67558 14.006 10.8238C14.006 11.9721 13.075 12.9031 11.9266 12.9031C10.7783 12.9031 9.84751 11.9721 9.84751 10.8238C9.84751 9.67558 10.7784 8.74459 11.9266 8.74459Z" fill="#5F01D1"/>
                                                        </g>
                                                        <defs>
                                                        <clipPath id="clip0_1235_2766">
                                                        <rect width="17.9975" height="16" fill="white"/>
                                                        </clipPath>
                                                        </defs>
                                                    </svg>  
                                                </span>                                      
                                                {{ item.name}}
                                            </el-link>  
                                        </div>';
                    $bookingpress_calendar_html             .= '</div>';
                $bookingpress_calendar_html .= '</div>';
        
                add_action(
                    'wp_footer',
                    function() {
                        $appointment_id = ( ! empty( $_REQUEST['appointment_id'] ) ? intval( base64_decode( $_REQUEST['appointment_id'] ) ) : '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                        ?>
                    <script>
                        wp.hooks.addAction('bpa_calendar_js_init' , 'bookingpress-appointment-booking-pro', bookingpress_load_calendar_list, 10, 1 );
                        function bookingpress_load_calendar_list(bookingpress_appointment_id){
                            var app = new Vue({
                                el:'#bpa-front-module-calendar-integration',
                                data(){
                                    var bookingpress_return_data = {};
                                    bookingpress_return_data['bookingpress_calendar_list'] = <?php echo _wp_specialchars($this->bookingpress_calendar_list,ENT_NOQUOTES,'UTF-8', true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
                                    bookingpress_return_data['bookingpress_selected_calendar'] = '';
                                    bookingpress_return_data['bookingpress_appointment_id'] = '<?php echo esc_html($appointment_id); ?>';
                                    bookingpress_return_data['bookingpress_calendar_link'] = '';
                                    bookingpress_return_data['bookingpress_google_calendar_link'] = '<?php echo esc_url($this->bookingpress_google_calendar_link); ?>'; //phpcs:ignore
                                    bookingpress_return_data['bookingpress_yahoo_calendar_link'] = '<?php echo esc_url($this->bookingpress_yahoo_calendar_link); ?>'; //phpcs:ignore
                                    return bookingpress_return_data;
                                },
                                mounted(){
                                    const vm = this;
                                    document.getElementById("bpa-front-module-calendar-integration").style.display = "block";
                                    document.getElementById("bookingpress_ical_calendar").addEventListener("click", function(e){
                                        var bookingpress_calendar_link = "<?php echo esc_url(BOOKINGPRESS_HOME_URL)."?bpa_page=bookingpress_download&action=generate_ics&state=".esc_html(wp_create_nonce('bookingpress_calendar_ics'))."&appointment_id="; ?>";
                                        bookingpress_calendar_link = bookingpress_calendar_link + bookingpress_appointment_id;
                                        bookingpress_calendar_link = wp.hooks.applyFilters( 'bookingpress_change_calendar_url', bookingpress_calendar_link, 'ical', bookingpress_appointment_id );
                                        window.open(bookingpress_calendar_link, '_self');
                                    });
                                    document.getElementById("bookingpress_outlook_calendar").addEventListener("click", function(e){
                                        var bookingpress_calendar_link = "<?php echo esc_url(BOOKINGPRESS_HOME_URL)."?bpa_page=bookingpress_download&action=generate_ics&state=".esc_html(wp_create_nonce('bookingpress_calendar_ics'))."&appointment_id="; ?>";
                                        bookingpress_calendar_link = bookingpress_calendar_link + bookingpress_appointment_id;
                                        bookingpress_calendar_link = wp.hooks.applyFilters( 'bookingpress_change_calendar_url', bookingpress_calendar_link, 'outlook_calendar', bookingpress_appointment_id );
                                        window.open(bookingpress_calendar_link, '_self');
                                    });
                                    <?php do_action( 'bookingpress_calendar_integration_events' ); ?>
                                },
                            });
                        }

                        var bookingpress_redirection_mode = '<?php echo esc_html(!empty($appointment_id) ? 'external_redirection' : 'in_built'); ?>';
                        if(bookingpress_redirection_mode == "external_redirection"){
                            var bookingpress_appointment_id = '<?php echo esc_html($appointment_id); ?>';
                            wp.hooks.doAction("bpa_calendar_js_init", bookingpress_appointment_id);
                        }
                    </script>
                        <?php
                    },
                    100
                );
            }

			return $bookingpress_calendar_html;
		}
        
        /**
         * Download ICS file from if it enable in email notification
         *
         * @return void
         */
        function bookingpress_download_ics_file() {

			if ( ( ( !empty( $_GET['page'] ) && 'bookingpress_download' == $_GET['page'] ) || ( !empty( $_GET['bpa_page'] ) && 'bookingpress_download' == $_GET['bpa_page'] )) && ! empty( $_GET['action'] ) && 'generate_ics' == $_GET['action'] ) {

				$nonce = ! empty( $_GET['state'] ) ? sanitize_text_field( $_GET['state'] ) : '';
				if ( ! wp_verify_nonce( $nonce, 'bookingpress_calendar_ics' ) ) {
					return false;
				}

				if ( empty( $_GET['appointment_id'] ) ) {
					return false;
				}

				$appointment_id = intval( $_GET['appointment_id'] );

				global $wpdb,$tbl_bookingpress_entries, $tbl_bookingpress_appointment_bookings, $BookingPress, $bookingpress_global_options;
				// $appointment_id = base64_decode( $_REQUEST['appointment_id'] );
				$bookingpress_entry_details = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_entries} WHERE bookingpress_entry_id = %d", $appointment_id ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_entries is a table name. false alarm

				if ( ! empty( $bookingpress_entry_details ) ) {
					$bookingpress_service_id         = $bookingpress_entry_details['bookingpress_service_id'];
					$bookingpress_appointment_date   = $bookingpress_entry_details['bookingpress_appointment_date'];
					$bookingpress_appointment_time   = $bookingpress_entry_details['bookingpress_appointment_time'];
					$bookingpress_appointment_status = $bookingpress_entry_details['bookingpress_appointment_status'];

					$appointment_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$tbl_bookingpress_appointment_bookings} WHERE bookingpress_service_id = %d AND bookingpress_appointment_date = %s AND bookingpress_appointment_time = %s AND bookingpress_appointment_status = %s", $bookingpress_service_id, $bookingpress_appointment_date, $bookingpress_appointment_time, $bookingpress_appointment_status ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared --Reason: $tbl_bookingpress_appointment_bookings is a table name. false alarm

					if ( ! empty( $appointment_data ) ) {
						$service_id              = intval( $appointment_data['bookingpress_service_id'] );

						$bookingpress_start_time = sanitize_text_field( $appointment_data['bookingpress_appointment_time'] );
                        $bookingpress_end_time   = sanitize_text_field( $appointment_data['bookingpress_appointment_end_time'] );

                        $bookingpress_appointment_date_temp = $appointment_data['bookingpress_appointment_date'];
						if ($bookingpress_end_time === '24:00:00') {
							$bookingpress_appointment_date_temp = date('Y-m-d', strtotime($appointment_data['bookingpress_appointment_date'] . ' +1 day'));
							$bookingpress_end_time = '00:00:00';
						}

						$bookingpress_start_time = date( 'Ymd', strtotime( $appointment_data['bookingpress_appointment_date'] ) ) . 'T' . date( 'His', strtotime( $bookingpress_start_time ) );

                        $bookingpress_end_time = date( 'Ymd', strtotime( $bookingpress_appointment_date_temp ) ) . 'T' . date( 'His', strtotime( $bookingpress_end_time ) );
						$user_timezone             = $bookingpress_global_options->bookingpress_get_site_timezone_offset();
						$bookingpress_service_name = ! empty( $appointment_data['bookingpress_service_name'] ) ? sanitize_text_field( $appointment_data['bookingpress_service_name'] ) : '';
					}

					$booking_stime = $this->bookingpress_convert_date_time_to_utc( $appointment_data['bookingpress_appointment_date'], $bookingpress_start_time );
					$booking_etime = $this->bookingpress_convert_date_time_to_utc( $bookingpress_appointment_date_temp, $bookingpress_end_time );
					$current_dtime = $this->bookingpress_convert_date_time_to_utc( date( 'm/d/Y' ), 'g:i A' );

					$string  = "BEGIN:VCALENDAR\r\n";
					$string .= "VERSION:2.0\r\n";
					$string .= 'PRODID:BOOKINGPRESS APPOINTMENT BOOKING\\\\' . get_bloginfo('title') . "\r\n";
					$string .= "X-PUBLISHED-TTL:P1W\r\n";
					$string .= "BEGIN:VEVENT\r\n";
					$string .= 'UID:' . md5( time() ) . "\r\n";
					$string .= 'DTSTART:' . $booking_stime . "\r\n";
					$string .= "SEQUENCE:0\r\n";
					$string .= "TRANSP:OPAQUE\r\n";
					$string .= "DTEND:{$booking_etime}\r\n";
					$string .= "SUMMARY:{$bookingpress_service_name}\r\n";
					$string .= "CLASS:PUBLIC\r\n";
					$string .= "DTSTAMP:{$current_dtime}\r\n";
					$string .= "END:VEVENT\r\n";
					$string .= "END:VCALENDAR\r\n";
                    
                    $string  = apply_filters( 'bpa_add_timezone_parameters_for_ics', $string, $appointment_data );
                    
					header( 'Content-Type: text/calendar; charset=utf-8' );
					header( 'Content-Disposition: attachment; filename="cal.ics"' );


					echo $string; //phpcs:ignore
				}

				die;

			}
		}
                
        /**
         * Convert Date and Time to UTC format
         *
         * @param  mixed $date      Convert date
         * @param  mixed $time      Convert time
         * @param  mixed $formated  Formatted date time should be return
         * @return void
         */
        function bookingpress_convert_date_time_to_utc( $date, $time, $formated = false ) {

			if ( empty( $date ) ) {
				$date = date( 'm/d/Y' );
			}

			if ( empty( $time ) ) {
				$time = date( 'g:i A' );
			}

			$bookingpress_time = date( 'm/d/Y', strtotime( $date ) ) . ' ' . date( 'g:i A', strtotime( $time ) );

			$tz_from = wp_timezone_string();
			$tz_to   = 'UTC';
			if ( $formated ) {
				$format = 'Y-m-d\TH:i:s\Z';
			} else {
				$format = 'Ymd\THis\Z';
			}

			$start_dt = new DateTime( $bookingpress_time, new DateTimeZone( $tz_from ) );
			$start_dt->setTimeZone( new DateTimeZone( $tz_to ) );
			$bookingpress_time = $start_dt->format( $format );

			return $bookingpress_time;

		}
        
        /**
         * Helper variables for Booking Form Shortcode
         *
         * @param  mixed $bookingpress_front_booking_dynamic_helper_vars
         * @return void
         */
        function bookingpress_booking_dynamic_helper_vars_func( $bookingpress_front_booking_dynamic_helper_vars )
        {
            global $bookingpress_global_options;
            $bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_locale_lang = $bookingpress_options['locale'];

            $bookingpress_front_booking_dynamic_helper_vars .= 'var lang = ELEMENT.lang.' . $bookingpress_locale_lang . ';';
            $bookingpress_front_booking_dynamic_helper_vars .= 'ELEMENT.locale(lang);';

            $bookingpress_front_booking_dynamic_helper_vars = apply_filters('bookingpress_add_frontbooking_dynamic_helper_vars', $bookingpress_front_booking_dynamic_helper_vars);

            return $bookingpress_front_booking_dynamic_helper_vars;
        }

        
        /**
         * Booking Form Shortcode onload methods
         *
         * @param  mixed $bookingpress_dynamic_on_load_methods_data
         * @return void
         */
        function bookingpress_booking_dynamic_on_load_methods_func( $bookingpress_dynamic_on_load_methods_data )
        {
            $bookingpress_dynamic_on_load_methods_data .= 'this.bookingpress_onload_func();';

            $bookingpress_dynamic_on_load_methods_data .= 'this.appointment_step_form_data.bookingpress_customer_timezone = new Date().getTimezoneOffset();';

            $bookingpress_dynamic_on_load_methods_data .= 'if(this.hide_category_service == "1" || this.is_service_loaded_from_url == "1"){
                this.bookingpress_current_tab = "datetime";
            }';
            $bookingpress_dynamic_on_load_methods_data = apply_filters('bookingpress_add_appointment_booking_on_load_methods', $bookingpress_dynamic_on_load_methods_data);

            return $bookingpress_dynamic_on_load_methods_data;
        }

        /**
         * Booking Form Shortcode onload methods
         *
         * @param  mixed $bookingpress_dynamic_on_load_methods_data
         * @return void
         */
        function bookingpress_booking_dynamic_on_load_methods_func_with_pro( $bookingpress_dynamic_on_load_methods_data ){
            
            $bookingpress_dynamic_on_load_methods_data .= 'this.bookingpress_onload_func();';

            $bookingpress_dynamic_on_load_methods_data .= 'this.appointment_step_form_data.bookingpress_customer_timezone = new Date().getTimezoneOffset();';

            $bookingpress_dynamic_on_load_methods_data = apply_filters('bookingpress_add_appointment_booking_on_load_methods', $bookingpress_dynamic_on_load_methods_data);

            $bookingpress_is_guest_load_from_share_url = !empty($_GET['g_id']) ? 1 : 0;
            $bookingpress_selected_guest_from_url = !empty($_GET['g_id']) ? intval($_GET['g_id']) : 0;
            
            $bookingpress_dynamic_on_load_methods_data .= '

            var bookingpress_guests_load_from_share_url = "'.$bookingpress_is_guest_load_from_share_url.'";
            var bookingpress_selected_guest_from_url = "'.$bookingpress_selected_guest_from_url.'";
            
            let current_tab = this.bookingpress_current_tab;
            let step_data = this.bookingpress_sidebar_step_data[ current_tab ];
            
            if( this.hide_category_service == "1" || this.is_service_loaded_from_url == "1" ){
                
                let next_tab = this.bookingpress_sidebar_step_data["service"].next_tab_name;
                let bookingpress_selected_service_id = vm.appointment_step_form_data.selected_service;
                let bookingpress_selected_service_name = vm.appointment_step_form_data.selected_service_name;
                let bookingpress_selected_service_price = vm.appointment_step_form_data.selected_service_price;
                let bookingpress_selected_service_price_without_currency = vm.appointment_step_form_data.service_price_without_currency;
                
                let useFlag = "false";
                if( "undefined" != typeof vm.is_bring_anyone_with_you_activated && 1 == vm.is_bring_anyone_with_you_activated && bookingpress_guests_load_from_share_url == "1" && bookingpress_selected_guest_from_url != "" ){
                    /** Do Nothing as selectDate function has already been called before */
                } else {   
                    vm.selectDate( bookingpress_selected_service_id, bookingpress_selected_service_name, bookingpress_selected_service_price, bookingpress_selected_service_price_without_currency, useFlag );
                }
            } else {
                if( "undefined" != typeof step_data.is_navigate_to_next && true == step_data.is_navigate_to_next ){
                    this.bookingpress_current_tab = step_data.next_tab_name;
                }
            }
            ';
            /*'if((this.is_staffmember_activated == 1 && typeof this.appointment_step_form_data.hide_staff_selection !== "undefined" && this.appointment_step_form_data.hide_staff_selection == "false" && this.hide_category_service == "1" && this.is_service_loaded_from_url != "1" )) {
                this.bookingpress_current_tab = "staffmembers";
            }'; */

            return $bookingpress_dynamic_on_load_methods_data;
        }

        function bookingpress_call_autofocus_method( $bookingpress_dynamic_on_load_methods_data ){

            $bookingpress_dynamic_on_load_methods_data .= '
                let bpa_current_tab = this.bookingpress_current_tab;
            
                let bpa_side_bar_step_data = this.bookingpress_sidebar_step_data[bpa_current_tab];
                let bpa_callback_funcs = bpa_side_bar_step_data.auto_focus_tab_callback;
                
                for( let callback in bpa_callback_funcs ){
                    let args = bpa_callback_funcs[callback];
                    
                    this[callback].apply( callback, args );
                }
                
            ';

            return $bookingpress_dynamic_on_load_methods_data;
        }
        
        /**
         * Booking Form Shortcode methods or functions
         *
         * @param  mixed $bookingpress_vue_methods_data
         * @return void
         */
        function bookingpress_booking_dynamic_vue_methods_func( $bookingpress_vue_methods_data )
        {
            global $BookingPress;

            $bookingpress_current_date                    = date('Y-m-d', current_time('timestamp'));
            $no_appointment_time_selected_for_the_booking = $BookingPress->bookingpress_get_settings('no_appointment_time_selected_for_the_booking', 'message_setting');
            $no_service_selected_for_the_booking          = $BookingPress->bookingpress_get_settings('no_service_selected_for_the_booking', 'message_setting');

            $bookingpress_nonce = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $bookingpress_current_user_id = get_current_user_id();

            $bookingpress_before_book_appointment_data = '';
            $bookingpress_before_book_appointment_data = apply_filters('bookingpress_before_book_appointment', $bookingpress_before_book_appointment_data);

            $bookingpress_before_selecting_booking_service_data = '';
            $bookingpress_before_selecting_booking_service_data = apply_filters('bookingpress_before_selecting_booking_service', $bookingpress_before_selecting_booking_service_data);

            $bookingpress_after_selecting_booking_service_data = '';
            $bookingpress_after_selecting_booking_service_data = apply_filters('bookingpress_after_selecting_booking_service', $bookingpress_after_selecting_booking_service_data);

            $bookingpress_after_timeslot_data_load_for_single_date = '';
            $bookingpress_after_timeslot_data_load_for_single_date = apply_filters('bookingpress_after_timeslot_data_load_for_single_date', $bookingpress_after_timeslot_data_load_for_single_date);


            $bookingpress_after_selecting_payment_method_data = '';
            $bookingpress_after_selecting_payment_method_data = apply_filters('bookingpress_after_selecting_payment_method', $bookingpress_after_selecting_payment_method_data);

            $bookingpress_dynamic_add_params_for_timeslot_request = '';
            $bookingpress_dynamic_add_params_for_timeslot_request = apply_filters('bookingpress_dynamic_add_params_for_timeslot_request', $bookingpress_dynamic_add_params_for_timeslot_request);

            $bookingpress_add_data_for_first_step_on_next_page = '';
            $bookingpress_add_data_for_first_step_on_next_page = apply_filters('bookingpress_add_data_for_first_step_on_next_page', $bookingpress_add_data_for_first_step_on_next_page);

            $bookingpress_add_data_for_previous_page = '';
            $bookingpress_add_data_for_previous_page = apply_filters('bookingpress_add_data_for_previous_page', $bookingpress_add_data_for_previous_page);

            $bookingpress_add_data_for_second_step_on_next_page = '';
            $bookingpress_add_data_for_second_step_on_next_page = apply_filters('bookingpress_add_data_for_second_step_on_next_page', $bookingpress_add_data_for_second_step_on_next_page);
	    
            $bookingpress_dynamic_next_page_request_filter = '';
            $bookingpress_dynamic_next_page_request_filter = apply_filters('bookingpress_dynamic_next_page_request_filter', $bookingpress_dynamic_next_page_request_filter);

            $bookingpress_dynamic_validation_for_step_change = '';
            $bookingpress_dynamic_validation_for_step_change = apply_filters('bookingpress_dynamic_validation_for_step_change', $bookingpress_dynamic_validation_for_step_change);

            $bookingpress_disable_date_xhr_data = '';
            $bookingpress_disable_date_xhr_data = apply_filters( 'bookingpress_disable_date_xhr_data', $bookingpress_disable_date_xhr_data );

            $bookingpress_disable_date_pre_xhr_data = '';
            $bookingpress_disable_date_pre_xhr_data = apply_filters( 'bookingpress_disable_date_pre_xhr_data', $bookingpress_disable_date_pre_xhr_data );

            $bookingpress_disable_date_vue_data = '';
            $bookingpress_disable_date_vue_data = apply_filters( 'bookingpress_disable_date_vue_data_modify', $bookingpress_disable_date_vue_data );

            $bookingpress_modify_select_step_category = '';
            $bookingpress_modify_select_step_category = apply_filters('bookingpress_modify_select_step_category', $bookingpress_modify_select_step_category);

            $bookingpress_modify_select_service_category = '';
            $bookingpress_modify_select_service_category = apply_filters( 'bookingpress_modify_select_service_category', $bookingpress_modify_select_service_category );


            $bookingpress_disable_multiple_days_event_xhr_resp_after = '';
            $bookingpress_disable_multiple_days_event_xhr_resp_after = apply_filters( 'bookingpress_disable_multiple_days_event_xhr_resp_after', $bookingpress_disable_multiple_days_event_xhr_resp_after );


            $bookingpress_disable_multiple_days_event_xhr_resp = '';
            $bookingpress_disable_multiple_days_event_xhr_resp = apply_filters( 'bookingpress_disable_multiple_days_xhr_response', $bookingpress_disable_multiple_days_event_xhr_resp );

            $bookingpress_dynamic_time_select_after = '';
            $bookingpress_dynamic_time_select_after = apply_filters('bookingpress_dynamic_time_select_after', $bookingpress_dynamic_time_select_after);

            $bookingpress_site_date = date('Y-m-d H:i:s', current_time( 'timestamp') );
            $bookingpress_site_date = apply_filters( 'bookingpress_modify_current_date', $bookingpress_site_date );

            $bookingpress_disable_timeslot_select_data = '';
            $bookingpress_disable_timeslot_select_data = apply_filters('bookingpress_disable_timeslot_select_data', $bookingpress_disable_timeslot_select_data);

            $bookingpress_disable_date_send_data = '';
            $bookingpress_disable_date_send_data = apply_filters('bookingpress_disable_date_send_data_before', $bookingpress_disable_date_send_data);            

            $bookingpress_vue_methods_data .= '
            get_formatted_date(iso_date){

                if( true == /(\d{2})\T/.test( iso_date ) ){
                    let date_time_arr = iso_date.split("T");
                    return date_time_arr[0];
                }
				var __date = new Date(iso_date);
				var __year = __date.getFullYear();
				var __month = __date.getMonth()+1;
				var __day = __date.getDate();
				if (__day < 10) {
					__day = "0" + __day;
				}
				if (__month < 10) {
					__month = "0" + __month;
				}
				var formatted_date = __year+"-"+__month+"-"+__day;
				return formatted_date;
			},
            get_formatted_datetime(iso_date) {			
                var __date = new Date(iso_date);
                var hour = __date.getHours();
                var minute = __date.getMinutes();
                var second = __date.getSeconds();

                if (minute < 10) {
                    minute = "0" + minute;
                }
                if (second < 10) {
                    second = "0" + second;
                }
                var formatted_time = hour + ":" + minute + ":" + second;				
                var __year = __date.getFullYear();
                var __month = __date.getMonth()+1;
                var __day = __date.getDate();
                if (__day < 10) {
                    __day = "0" + __day;
                }
                if (__month < 10) {
                    __month = "0" + __month;
                }

                var formatted_date = __year+"-"+__month+"-"+__day;
                return formatted_date+" "+formatted_time; 
            },
			bookingpress_set_error_msg(error_msg){
				const vm = this;
                let container = vm.$el;
                let pos = 0;
                if( null != container ){
                    pos = container.getBoundingClientRect().top + window.scrollY;
                }
				vm.is_display_error = "1";
				vm.is_error_msg = error_msg;
				const myVar = Error().stack;

                let allow_scroll = true;
                if( /mounted/.test( myVar ) ){
                    allow_scroll = false;
                }
                if( allow_scroll ){
					window.scrollTo({
						top: pos,
						behavior: "smooth",
					});
				}
                setTimeout(function(){
                    vm.bookingpress_remove_error_msg();
                },6000);
			},
			bookingpress_remove_error_msg(){
				const vm = this;
				vm.is_display_error = "0";
				vm.is_error_msg = "";
			},
			checkBeforeBookAppointment(){
				const vm = this;
				setTimeout(function(){
                    var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                    var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                    if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                    {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                    }
                    else {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                    }

					var postData = { action:"bookingpress_before_book_appointment",_wpnonce:bkp_wpnonce_pre_fetch };
                    postData.appointment_data = JSON.stringify( vm.appointment_step_form_data );
					axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
					.then( function (response) {
						if(response.data.variant == "error"){
							vm.bookingpress_set_error_msg(response.data.msg);
							if(response.data.error_type == "dayoff"){
								vm.service_timing = [];
							}
                            vm.isLoadBookingLoader = "0";
                            vm.isBookingDisabled = false;
						}else{
							vm.bookingpress_remove_error_msg();
                            vm.bookingpress_process_to_book_appointment();
						}
					}.bind(this) )
					.catch( function (error) {
						vm.bookingpress_set_error_msg(error);
					});
				},1500);
			},
			book_appointment(){
				const vm2 = this;
				vm2.isLoadBookingLoader = "1";
				vm2.isBookingDisabled = true;
				vm2.bookingpress_process_to_book_appointment();
				
			},
            async bookingpress_process_to_book_appointment(){
                const vm2 = this;
                if(vm2.is_display_error != "1"){
                    var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                    var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                    if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                    {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                    }
                    else {
                        bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                    }

                    var postData = { action:"bookingpress_front_save_appointment_booking", _wpnonce:bkp_wpnonce_pre_fetch };
                    postData.appointment_data = JSON.stringify( vm2.appointment_step_form_data );
                    ' . $bookingpress_before_book_appointment_data . '
                    axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                    .then( function (response) {
                        vm2.isLoadBookingLoader = "0";
                        vm2.isBookingDisabled = false;
                        if(response.data.variant == "redirect"){
                            vm2.bookingpress_remove_error_msg();
                            vm2.bookingpress_external_html = response.data.redirect_data;
                            setTimeout(function(){
                                var scripts = document.getElementById("bpa-external-script").querySelectorAll("script");
                                if(scripts.length > 0){
                                    var text = scripts[scripts.length - 1].textContent;
                                    eval(text);
                                }
                            },50);
                        }else if(response.data.variant == "redirect_url"){
                            vm2.bookingpress_remove_error_msg();
                            window.location.href = response.data.redirect_data;
                        }else if(response.data.variant == "error"){
                            vm2.bookingpress_set_error_msg(response.data.msg);
                        }else{
                            vm2.bookingpress_remove_error_msg();
                        }
                        if(response.data.error_type == "dayoff"){
                            vm2.service_timing = [];
                        }
                    }.bind(this) )
                    .catch( function (error) {
                        vm2.bookingpress_set_error_msg(error);
                    });
                }else{
                    vm2.isLoadBookingLoader = "0";
                    vm2.isBookingDisabled = false;
                }
            },
            bpa_check_browser(){
                const vm = this;
                let userAgent = navigator.userAgent;
                let browserName;
                if(userAgent.match(/edg/i)){
                    browserName = "edge";
                } else if(userAgent.match(/opr\//i)){
                    browserName = "opera";
                } else if(userAgent.match(/chrome|chromium|crios/i)){
                    browserName = "chrome";
                } else if(userAgent.match(/firefox|fxios/i)) {
                    browserName = "firefox";
                } else if(userAgent.match(/safari/i)) {
                    browserName = "safari";
                } else {
                    browserName="Unknown";
                }

                vm.browser_details = browserName;
            },
            bpa_check_browser_version(){
                const vm = this;
                var objappVersion = navigator.appVersion;
                var browserAgent = navigator.userAgent;
                var browserName = navigator.appName;
                var browserVersion = "" + parseFloat(navigator.appVersion);
                var browserMajorVersion = parseInt(navigator.appVersion, 10);
                var Offset, OffsetVersion, ix;
                
                /* For Chrome */
                if ((OffsetVersion = browserAgent.indexOf("Chrome")) != -1) {
                    browserName = "Chrome";
                    browserVersion = browserAgent.substring(OffsetVersion + 7);
                }
                
                /* For Microsoft internet explorer  */
                else if ((OffsetVersion = browserAgent.indexOf("MSIE")) != -1) {
                    browserName = "Microsoft Internet Explorer";
                    browserVersion = browserAgent.substring(OffsetVersion + 5);
                }
                
                /* For Firefox */
                else if ((OffsetVersion = browserAgent.indexOf("Firefox")) != -1) {
                    browserName = "Firefox";
                }
                
                /* For Safari */
                else if ((OffsetVersion = browserAgent.indexOf("Safari")) != -1) {
                    browserName = "Safari";
                    browserVersion = browserAgent.substring(OffsetVersion + 7);
                    if ((OffsetVersion = browserAgent.indexOf("Version")) != -1)
                        browserVersion = browserAgent.substring(OffsetVersion + 8);
                }
                
                /* For other browser "name/version" is at the end of userAgent */
                else if ((Offset = browserAgent.lastIndexOf(" ") + 1) < (OffsetVersion = browserAgent.lastIndexOf("/"))) {
                    browserName = browserAgent.substring(Offset, OffsetVersion);
                    browserVersion = browserAgent.substring(OffsetVersion + 1);
                    if (browserName.toLowerCase() == browserName.toUpperCase()) {
                        browserName = navigator.appName;
                    }
                }
                
                /* Trimming the fullVersion string at */
                /* semicolon/space if present */
                if ((ix = browserVersion.indexOf(";")) != -1)
                    browserVersion = browserVersion.substring(0, ix);
                if ((ix = browserVersion.indexOf(" ")) != -1)
                    browserVersion = browserVersion.substring(0, ix);
                
                
                browserMajorVersion = parseInt("" + browserVersion, 10);
                if (isNaN(browserMajorVersion)) {
                    browserVersion = "" + parseFloat(navigator.appVersion);
                    browserMajorVersion = parseInt(navigator.appVersion, 10);
                }

                vm.browser_version = browserMajorVersion;
            },
            bpa_select_category( selected_cat_id, selected_cat_name, total_services, total_category){
                const vm = this;
                let category_id = parseInt( selected_cat_id );
                vm.isLoadClass = 0;
                if( 0 == selected_cat_id ){
                    for( let bpa_service_id in vm.bookingpress_all_services_data ){
                        let current_service = vm.bookingpress_all_services_data[ bpa_service_id ];
                        vm.bookingpress_all_services_data[ bpa_service_id ].is_visible = true;
                        '.$bookingpress_modify_select_service_category.'
                    }
                } else {
                    if( vm.bookingpress_all_services_data != "" ){
                        for( let bpa_service_id in vm.bookingpress_all_services_data ){
                            let current_service = vm.bookingpress_all_services_data[ bpa_service_id ];
                            let current_category = current_service.bookingpress_category_id;
                            vm.bookingpress_all_services_data[ bpa_service_id ].is_visible = false;
                            if( current_category == category_id ){
                                vm.bookingpress_all_services_data[ bpa_service_id ].is_visible = true;
                            }
                            '.$bookingpress_modify_select_service_category.'
                        }
                    }
                }
                setTimeout(function(){
                    vm.isLoadClass = 1;
                },1);
                vm.appointment_step_form_data.selected_category = selected_cat_id;
                vm.appointment_step_form_data.selected_cat_name = selected_cat_name;
            },
			selectStepCategory(selected_cat_id, selected_cat_name = "", total_services = 0, total_category="", display_warn = true ){
                if( true == display_warn ){
                    console.trace("Deprecated step category function " );
                    console.warn("selectStepCategory function is deprecated. Please update the BookingPress lite, premium, and add-ons to the latest version");
                }
				const vm = this;
                vm.isLoadClass = 0;
                if( 0 == selected_cat_id ){
                    let temp_services = [];
                    let m = 0;
                    for( let x in vm.bpa_services_data_from_categories ){
                        let service_details = vm.bpa_services_data_from_categories[x];                                                
                        for( let n in service_details ){
                            let current_service = service_details[n];                            
                            if( "undefined" != typeof current_service.bookingpress_staffmembers ){
                                let selected_staffmember = vm.appointment_step_form_data.bookingpress_selected_staff_member_details.selected_staff_member_id;
                                if( current_service.bookingpress_staffmembers.includes( selected_staffmember ) && selected_staffmember != ""){
                                    var bookingpress_service_pos = parseFloat(current_service.bookingpress_service_position);                                      
                                    temp_services[bookingpress_service_pos] = current_service;                                    
                                } else {
                                    var bookingpress_service_pos = parseFloat(current_service.bookingpress_service_position );                                
                                    temp_services[bookingpress_service_pos] = current_service;
                                } 
                            } else {
                                var bookingpress_service_pos = parseFloat(current_service.bookingpress_service_position );                                
                                temp_services[bookingpress_service_pos] = current_service;                                
                            }
                            '.$bookingpress_modify_select_step_category.'
                            m++;                            
                        }                        
                    }                    
                    var bpa_temp_services= [];                     
                    temp_services.sort();
                    for( let n in temp_services ){                        
                        if(temp_services[n] != "") {                            
                            bpa_temp_services[n] = temp_services[n];   
                        }                        
                    }                    
                    vm.services_data = bpa_temp_services.sort();
                } else {
                    vm.services_data = vm.bpa_services_data_from_categories[selected_cat_id];
                    '.$bookingpress_modify_select_step_category.'
                }

				vm.appointment_step_form_data.selected_category = selected_cat_id;
				vm.appointment_step_form_data.selected_cat_name = selected_cat_name;
                setTimeout(function(){
                    vm.isLoadClass = 1;
                },1);
                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
			},
			async selectDate(selected_service_id, service_name, service_price, service_price_without_currency, is_move_to_next, service_duration_val = "",service_duration_unit = ""){
				const vm = this;
                if(typeof vm.appointment_step_form_data.cart_items == "undefined" && (selected_service_id != vm.appointment_step_form_data.selected_service && vm.appointment_step_form_data.selected_service != "")){
                    var bookingpress_selected_date = vm.appointment_step_form_data.selected_date;
                    let newDate = new Date('.( !empty( $bookingpress_site_date ) ? '"' . $bookingpress_site_date . '"' : '' ).');
                    let pattern = /(\d{4}\-\d{2}\-\d{2})/;
                    if( !pattern.test( newDate ) ){

                        let sel_month = newDate.getMonth() + 1;
                        let sel_year = newDate.getFullYear();
                        let sel_date = newDate.getDate();

                        if( sel_month < 10 ){
                            sel_month = "0" + sel_month;
                        }

                        if( sel_date < 10 ){
                            sel_date = "0" + sel_date;
                        }
                        
                        newDate = sel_year + "-" + sel_month + "-" + sel_date;
                    }
                    
                    vm.appointment_step_form_data.selected_date = newDate;
                    vm.appointment_step_form_data.selected_start_time = "";
				    vm.appointment_step_form_data.selected_end_time = "";
                }
                '.$bookingpress_before_selecting_booking_service_data.'
                
                vm.appointment_step_form_data.selected_service = selected_service_id;
                
                vm.appointment_step_form_data.selected_service_name = service_name;
                vm.appointment_step_form_data.selected_service_price = service_price;
                vm.appointment_step_form_data.service_price_without_currency = service_price_without_currency;
                
                /* vm.appointment_step_form_data.base_price = service_price; */
                vm.appointment_step_form_data.base_price_without_currency = service_price_without_currency;
                if( "" == service_duration_val ){
                    service_duration_val = vm.bookingpress_all_services_data[ selected_service_id ].bookingpress_service_duration_val;
                }

                if( "" == service_duration_unit ){   
                    service_duration_unit = vm.bookingpress_all_services_data[ selected_service_id ].bookingpress_service_duration_unit;
                }

                vm.appointment_step_form_data.selected_service_duration = service_duration_val;
                vm.appointment_step_form_data.selected_service_duration_unit = service_duration_unit;
                
                if(vm.previous_selected_tab_id === 1 || vm.previous_selected_tab_id === 2 || vm.current_selected_tab_id === 1){
                    vm.displayResponsiveCalendar = "1";
                }
                
                vm.v_calendar_disable_dates = [];
                
                if(is_move_to_next === "true"){
                    vm.bookingpress_step_navigation(vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].next_tab_name, vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].next_tab_name, vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].previous_tab_name);
                }


                var selected_date = vm.appointment_step_form_data.selected_date;
                var formatted_date = vm.get_formatted_date(selected_date);
                vm.bookingpress_remove_error_msg();
                ' . $bookingpress_after_selecting_booking_service_data . '
			},
            dayClicked(day) {
                const vm = this;
                let disable_dates = vm.v_calendar_disable_dates;
                let max_available_date = vm.get_formatted_date( vm.booking_cal_maxdate );
                if( disable_dates.includes( day.id + " 00:00:00" ) || disable_dates.includes( day.id ) || max_available_date < day.id || (day.date < vm.jsCurrentDateFormatted && false == day.isToday) ){
                    return false;
                }
                vm.appointment_step_form_data.selected_date = day.id;
                vm.get_date_timings( day.id );
            },
			get_date_timings(current_selected_date = ""){
                
				const vm = this;
                if( window.innerWidth <= 991 ){
				    vm.service_timing = "-1";
                }else{
                    vm.service_timing = "-2";
                }
                vm.displayResponsiveCalendar = "0";
                
                if( null == vm.appointment_step_form_data.selected_date ){
                    vm.appointment_step_form_data.selected_date = new Date('.( !empty( $bookingpress_site_date ) ? '"' . $bookingpress_site_date . '"' : '' ).');
                }

				if( current_selected_date == "") {
					current_selected_date =	vm.appointment_step_form_data.selected_date;
				}
				vm.appointment_step_form_data.selected_date = current_selected_date;
				var selected_date = vm.appointment_step_form_data.selected_date;
                if( "undefined" != typeof this.$refs.bkp_front_calendar ){
                    const calendar = this.$refs.bkp_front_calendar;
                    calendar.move(current_selected_date);
                }
                let pattern = /(\d{4}\-\d{2}\-\d{2})/;
                if( !pattern.test( selected_date ) ){

                    let sel_month = selected_date.getMonth() + 1;
                    let sel_year = selected_date.getFullYear();
                    let sel_date = selected_date.getDate();

                    if( sel_month < 10 ){
                        sel_month = "0" + sel_month;
                    }

                    if( sel_date < 10 ){
                        sel_date = "0" + sel_date;
                    }
                    
                    selected_date = sel_year + "-" + sel_month + "-" + sel_date;
                }

				vm.appointment_step_form_data.selected_date = selected_date;

				vm.appointment_step_form_data.selected_start_time = "";
				vm.appointment_step_form_data.selected_end_time = "";
				var selected_service_id = vm.appointment_step_form_data.selected_service;
                
                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }

				var postData = { action:"bookingpress_front_get_timings", service_id: selected_service_id, selected_date: selected_date, _wpnonce:bkp_wpnonce_pre_fetch, };
				' . $bookingpress_dynamic_add_params_for_timeslot_request . '                
                postData.appointment_data_obj = JSON.stringify(vm.appointment_step_form_data);
                postData.bpa_change_store_date = false;
                if( "undefined" != typeof vm.bookingpress_timezone_offset ){
                    postData.client_timezone_offset = vm.bookingpress_timezone_offset;
                    postData.bpa_change_store_date = true;                
                }

                if( "undefined" != typeof vm.bookingpress_dst_timezone ){
                    postData.client_dst_timezone = vm.bookingpress_dst_timezone;
                }
                vm.no_timeslot_available = false;
				axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
				.then( function (response) {
                    var set_waiting_time_limit = 1500;
                    '.$bookingpress_after_timeslot_data_load_for_single_date.'                                    
					setTimeout(function(){
						vm.service_timing = response.data;
                        if( response.data.morning_time.length <= 0 && response.data.afternoon_time.length <= 0 && response.data.evening_time.length <= 0 && response.data.night_time.length <= 0 ){
                            vm.no_timeslot_available = true;
                        }
						vm.isLoadTimeLoader = "0";
						vm.displayResponsiveCalendar = "0";
                        if(response.data == ""){
                            vm.service_timing = null;
                        }
					}, set_waiting_time_limit);

					' . $bookingpress_after_selecting_booking_service_data . '
				}.bind(this) )
				.catch( function (error) {
					console.log(error);
				});
			},
            selectDisableTiming(time_details){                                
                '.$bookingpress_disable_timeslot_select_data.'
            },            
			selectTiming(selected_start_time, selected_end_time, store_start_time = "", store_end_time = "", store_selected_date = "" ,formated_start_time = "",formated_end_time = "",time_details = ""){
				const vm = this;
				vm.appointment_step_form_data.selected_start_time = selected_start_time;
				vm.appointment_step_form_data.selected_end_time = selected_end_time;           

                if( "" != formated_end_time && "" != formated_start_time ) {                    
                    vm.appointment_step_form_data.selected_formatted_start_time = formated_start_time;
                    vm.appointment_step_form_data.selected_formatted_end_time = formated_end_time;
                }

                if( /* "undefined" != typeof vm.bookingpress_timezone_offset && */ "" != store_start_time && "" != store_end_time && "" != store_selected_date ){
                    vm.appointment_step_form_data.store_start_time = store_start_time;
                    vm.appointment_step_form_data.store_end_time = store_end_time;
                    vm.appointment_step_form_data.client_offset = vm.bookingpress_timezone_offset;
                    vm.appointment_step_form_data.store_selected_date = store_selected_date;
                }

                '.$bookingpress_dynamic_time_select_after.'
                
                vm.bookingpress_step_navigation(vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].next_tab_name, vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].next_tab_name, vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].previous_tab_name)
			},
			resetForm(){
				const vm2 = this;
				vm2.appointment_formdata.appointment_selected_customer = "' . $bookingpress_current_user_id . '";
				vm2.appointment_formdata.appointment_selected_service = "";
				vm2.appointment_formdata.appointment_booked_date = "' . $bookingpress_current_date . '";
				vm2.appointment_formdata.appointment_booked_time = "";
			},
			select_service(selected_service_id){
				const vm = this;
				vm.appointment_step_form_data.selected_service = selected_service_id;
			},
			automatic_next_page(next_tab_id){
				const vm = this;
                '.$bookingpress_dynamic_next_page_request_filter.';
				vm.current_selected_tab_id = parseInt(next_tab_id);
				vm.bookingpress_remove_error_msg();
                var bookingpress_scroll_pos = document.querySelector("#bookingpress_booking_form_"+vm.appointment_step_form_data.bookingpress_uniq_id);
                bookingpress_scroll_pos = bookingpress_scroll_pos.getBoundingClientRect();
                var bookingpress_scroll_position = (bookingpress_scroll_pos.top + window.scrollY) - 300;
                window.scrollTo({
					top: bookingpress_scroll_position,
				});
			},
			next_page(customer_form = "", current_selected_element = "", next_selection_element = ""){
				const vm = this;
				var current_selected_tab = bpa_selected_tab = parseFloat(vm.current_selected_tab_id);
				vm.previous_selected_tab_id = parseInt(current_selected_tab);
				if(current_selected_element != undefined && current_selected_element != null){
					current_selected_tab = parseInt(current_selected_element);
				}
                var bookingpress_scroll_pos = document.querySelector("#bookingpress_booking_form_"+vm.appointment_step_form_data.bookingpress_uniq_id);
                bookingpress_scroll_pos = bookingpress_scroll_pos.getBoundingClientRect();
                var bookingpress_scroll_position = (bookingpress_scroll_pos.top + window.scrollY) - 300;
                window.scrollTo({
					top: bookingpress_scroll_position,
				});

                if(current_selected_tab === 1 || vm.previous_selected_tab_id === 1){
                    vm.is_display_error = "0";
					if(vm.appointment_step_form_data.selected_service == "" || vm.appointment_step_form_data.selected_service == undefined || vm.appointment_step_form_data.selected_service == "undefined"){
						vm.bookingpress_set_error_msg("' . $no_service_selected_for_the_booking . '");
						vm.current_selected_tab_id = 1;
						return false;
					}else{
                        '.$bookingpress_add_data_for_first_step_on_next_page.';
						if(next_selection_element != ""){
                            current_selected_tab = next_selection_element;
                        }else{
                            current_selected_tab = current_selected_tab;
                        }
					}
				}else if(current_selected_tab === 2){
					if(current_selected_element != undefined && current_selected_element == 2  && vm.appointment_step_form_data.selected_start_time == "" && bpa_selected_tab == "2" && vm.appointment_step_form_data.selected_service_duration_unit != "d") {
						vm.bookingpress_set_error_msg("' . $no_appointment_time_selected_for_the_booking . '");
						vm.current_selected_tab_id = 2;
						return false;
					}        
                    if(vm.appointment_step_form_data.selected_service != ""  && vm.appointment_step_form_data.selected_start_time == "" && vm.appointment_step_form_data.selected_service_duration_unit != "d") {
                        vm.selectDate(vm.appointment_step_form_data.selected_service, vm.appointment_step_form_data.selected_service_name, vm.appointment_step_form_data.selected_service_price, vm.appointment_step_form_data.service_price_without_currency, "true",vm.appointment_step_form_data.selected_service_duration,vm.appointment_step_form_data.selected_service_duration_unit);
                    }                                 
					if(vm.is_display_error != "1"){
                        if(next_selection_element != ""){
                            current_selected_tab = next_selection_element
                        }else{
                            current_selected_tab = current_selected_tab;
                        }
						vm.bookingpress_remove_error_msg()
					}else{
						if(vm.is_error_msg == ""){
							vm.bookingpress_set_error_msg("' . esc_html__('Something went wrong', 'bookingpress-appointment-booking') . '")
						}
					}                                   
				}else if(current_selected_tab === 3){
					if(vm.appointment_step_form_data.selected_start_time == "" && vm.appointment_step_form_data.is_enable_validations == 1 && vm.appointment_step_form_data.selected_service_duration_unit != "d"){
						vm.bookingpress_set_error_msg("' . $no_appointment_time_selected_for_the_booking . '");
						vm.current_selected_tab_id = 2;                                      
						return false;
					}else{
						vm.$refs[customer_form].validate((valid) => {
							if (valid) {
                                if(next_selection_element != ""){
                                    current_selected_tab = next_selection_element
                                }else{
								    current_selected_tab = current_selected_tab;
                                }
							}
						});	
					}
				}else{
                    if(vm.appointment_step_form_data.selected_start_time == "" && vm.appointment_step_form_data.is_enable_validations == 1 && vm.appointment_step_form_data.selected_service_duration_unit != "d"){
						vm.bookingpress_set_error_msg("' . $no_appointment_time_selected_for_the_booking . '");
						vm.current_selected_tab_id = 2;                                      
						return false;
					} else {
                        vm.$refs[customer_form].validate((valid) => {
                            if (valid) {
                                if(next_selection_element != ""){
                                    current_selected_tab = next_selection_element;
                                }else{
                                    current_selected_tab = current_selected_tab;
                                }
                            }else{
                                current_selected_tab = 3;
                            }
                        });
                    }
				}
				if(current_selected_tab === 2 && vm.appointment_step_form_data.selected_start_time == "" && vm.appointment_step_form_data.selected_date != "" ) {
					vm.get_date_timings();
				}

                vm.current_selected_tab_id = parseInt(current_selected_tab);
                if(current_selected_tab === 2 && vm.appointment_step_form_data.selected_service_duration_unit == "d"){
                    vm.next_selected_tab_id = 3;
                }

                '.$bookingpress_dynamic_next_page_request_filter.'
			},
			previous_page(previous_selection_tab_id = ""){                
				const vm = this;
                var current_selected_tab = parseFloat(vm.current_selected_tab_id);
                if(previous_selection_tab_id != ""){
                    current_selected_tab = previous_selection_tab_id;
                }else{
				    vm.previous_selected_tab_id = parseInt(current_selected_tab);                    
				    current_selected_tab = current_selected_tab - 1;
                }
                '.$bookingpress_dynamic_next_page_request_filter.'
                
				vm.current_selected_tab_id = parseInt(current_selected_tab);
                if(vm.previous_selected_tab_id == "1"){
                    vm.displayResponsiveCalendar = 1;
                }
                var bookingpress_scroll_pos = document.querySelector("#bookingpress_booking_form_"+vm.appointment_step_form_data.bookingpress_uniq_id);
                bookingpress_scroll_pos = bookingpress_scroll_pos.getBoundingClientRect();
                var bookingpress_scroll_position = (bookingpress_scroll_pos.top + window.scrollY) - 300;
                window.scrollTo({
					top: bookingpress_scroll_position,
				});
			},
			select_payment_method(payment_method){
				const vm = this;
				vm.appointment_step_form_data.selected_payment_method = payment_method;
				var bookingpress_allowed_payment_gateways_for_card_fields = [];
				' . $bookingpress_after_selecting_payment_method_data . ';
				if(bookingpress_allowed_payment_gateways_for_card_fields.includes(payment_method)){
					vm.is_display_card_option = 1;
				}else{
					vm.is_display_card_option = 0;
				}


			},
			displayCalendar(){
				const vm = this;
				vm.displayResponsiveCalendar = "1";
			},
			Change_front_appointment_description(service_id) {
				const vm = this;
				vm.services_data.forEach(function(item, index, arr){					
					if(item.bookingpress_service_id == service_id ){						
						if(item.display_details_more == 0 && item.display_details_less == 1) {
							item.display_details_less = 0;
							item.display_details_more = 1;								
						} else {
							item.display_details_more = 0;
							item.display_details_less = 1;
						}
					}					
				});
			},
			bookingpress_phone_country_change_func(bookingpress_country_obj){
				const vm = this;
                var bookingpress_selected_country = bookingpress_country_obj.iso2;
				vm.appointment_step_form_data.customer_phone_country = bookingpress_selected_country;
                vm.appointment_step_form_data.customer_phone_dial_code = bookingpress_country_obj.dialCode;
                let exampleNumber = window.intlTelInputUtils.getExampleNumber( bookingpress_selected_country, true, 1 );                                
                if( typeof vm.bookingpress_phone_default_placeholder == "undefined" &&  "" != exampleNumber ){
                    vm.bookingpress_tel_input_props.inputOptions.placeholder = exampleNumber;
                } else if(vm.bookingpress_phone_default_placeholder == "false" && "" != exampleNumber){
                    vm.bookingpress_tel_input_props.inputOptions.placeholder = exampleNumber;
                }
			},
            bookingpress_phone_country_open( vmodel ){
                const vm = this;
                if( "" != vmodel ){
                    let elm = document.querySelector(`div[data-tel-id="${vmodel}"]`);
                    if( null != elm ){
                        let parent = vm.bookingpress_get_parents( elm, ".el-col" );
                        if( 0 < parent.length && null != parent[0] ){
                            parent[0].classList.add("bpa-active-col");
                        }
                    }
                }
            },
            bookingpress_phone_country_close( vmodel ){
                const vm = this;
                if( "" != vmodel ){
                    let elm = document.querySelector(`div[data-tel-id="${vmodel}"]`);
                    if( null != elm ){
                        let parent = vm.bookingpress_get_parents( elm, ".bpa-active-col" );
                        if( 0 < parent.length && null != parent[0] ){
                            parent[0].classList.remove("bpa-active-col");
                        }
                    }
                }
            },
            bookingpress_get_parents( elem, selector ){
                if (!Element.prototype.matches) {
                    Element.prototype.matches = Element.prototype.matchesSelector ||
                        Element.prototype.mozMatchesSelector ||
                        Element.prototype.msMatchesSelector ||
                        Element.prototype.oMatchesSelector ||
                        Element.prototype.webkitMatchesSelector ||
                        function(s) {
                            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                                i = matches.length;
                            while (--i >= 0 && matches.item(i) !== this) {}
                            return i > -1;
                        };
                }
            
                var parents = [];
            
                for (; elem && elem !== document; elem = elem.parentNode) {
                    if (selector) {
                        if (elem.matches(selector)) {
                            parents.push(elem);
                        }
                        continue;
                    }
                    parents.push(elem);
                }
            
                return parents;
            },
            async bookingpress_disable_date( bpa_selected_service = "", bpa_selected_date = "" ){
                '.$bookingpress_disable_date_pre_xhr_data.'
                this.bookingpress_disable_date_xhr( bpa_selected_service, bpa_selected_date );
            },
            bookingpress_disable_date_xhr( bpa_selected_service = "", bpa_selected_date = "", showLoader = true ){

                const vm = this;

                if( true == showLoader ){
                    vm.isLoadTimeLoader = "1";
                    vm.isLoadDateTimeCalendarLoad = "1";
                }

                vm.service_timing = "-3";

                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null){
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                } else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }

                if( "" == bpa_selected_service && "" != vm.appointment_step_form_data.selected_service ){
                    bpa_selected_service = vm.appointment_step_form_data.selected_service;
                }

                if( "undefined" != typeof vm.bookingpress_dst_timezone ){
                    vm.appointment_step_form_data.client_dst_timezone = vm.bookingpress_dst_timezone;
                }

                if( typeof vm.appointment_step_form_data.cart_items == "undefined" || vm.appointment_step_form_data.cart_items.length == 0 ){
                    vm.appointment_step_form_data.bookingpress_form_token = vm.appointment_step_form_data.bookingpress_uniq_id + "_" + ( Math.random().toString(36).slice(2) );
                }
                
                var postData = { action: "bookingpress_get_disable_date", service_id: bpa_selected_service, selected_service:bpa_selected_service, selected_date:bpa_selected_date, service_id:bpa_selected_service,_wpnonce:bkp_wpnonce_pre_fetch };

                postData.disabled_dates = JSON.stringify( vm.v_calendar_disable_dates );

                postData.appointment_data_obj = JSON.stringify(vm.appointment_step_form_data);
                
                postData.bpa_change_store_date = false;
                if( "undefined" != typeof vm.bookingpress_timezone_offset ){
                    postData.client_timezone_offset = vm.bookingpress_timezone_offset;
                    postData.bpa_change_store_date = true;
                }

                vm.no_timeslot_available = false;
                vm.v_calendar_check_month_dates = false;
                vm.v_calendar_next_month_dates = {};
                vm.days_off_disabled_dates = "";
                '.$bookingpress_disable_date_send_data.';
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) 
                {
                    vm.service_timing = [];
                    if(response.data.variant == "success" && (response.data.selected_date != undefined && response.data.days_off_disabled_dates != undefined)){
                        '.$bookingpress_disable_date_vue_data.'
                        vm.days_off_disabled_dates = "";

                        /*V-Calendar disabled dates change start*/
                        let bpa_disable_date = response.data.days_off_disabled_dates_string.split(",");
                        vm.v_calendar_disable_dates = [];
                        for( let temp_date in bpa_disable_date ){
                            let disabled_date = bpa_disable_date[temp_date];
                            if( "string" != typeof disabled_date){
                                continue;
                            }
                            
                            if( "undefined" != typeof vm.browser_details && "safari" == vm.browser_details && 16 > vm.browser_version ){
                                let disable_date_data = disabled_date.split(" ");
                                vm.v_calendar_disable_dates.push( disable_date_data[0] );
                            } else {
                                vm.v_calendar_disable_dates.push( disabled_date );
                            }
                        }
                        /*V-Calendar disabled dates change end*/

                        let resp_selected_date = response.data.selected_date;

                        if( vm.current_screen_size == "mobile" && vm.appointment_step_form_data.selected_service_duration_unit != "d" ){
                            vm.displayResponsiveCalendar = 0;
                        }

                        /* V-date-picker attributes */
                        let vcal_attributes = response.data.vcal_attributes;
                        
                        if( vcal_attributes.length != "" ){
                            let vcal_attr_data = [];
                            let vcal_attr_data_current = {};
                            let k = 1;
                            for( let vcal_date in vcal_attributes ){
                                let vcal_data = vcal_attributes[ vcal_date ];
                                vcal_attr_data_current[ vcal_date ] = vcal_data;
                                let vcal_attr_obj = {
                                    key: k,
                                    dates: vcal_date,
                                    customData:{
                                        title: vcal_data
                                    }
                                };
                                vcal_attr_data.push( vcal_attr_obj );
                                k++;
                            }
                            vm.v_calendar_attributes = vcal_attr_data;
                            vm.v_calendar_attributes_current = vcal_attr_data_current;
                        }

                        vm.v_calendar_default_label = response.data.max_capacity_capacity;
                        /* V-date-picker attributes */

                        vm.days_off_disabled_dates = response.data.days_off_disabled_dates;
                        vm.appointment_step_form_data.selected_date = response.data.selected_date;
                        vm.bookingpress_select_multi_day_range();
                        if( "undefined" != typeof response.data.front_timings ){
                            vm.service_timing = response.data.front_timings;
                            
                            if( response.data.front_timings.length <= 0 ){
                                vm.no_timeslot_available = true;
                            }
                        }
                        if( "undefined" != typeof this.$refs.bkp_front_calendar ){
                            const calendar = this.$refs.bkp_front_calendar;
                            calendar.move(vm.appointment_step_form_data.selected_date);
                        }

                        vm.isLoadTimeLoader = "0";
                        if( "undefined" != typeof response.data.empty_front_timings && true == response.data.empty_front_timings  ){
                            vm.isLoadDateTimeCalendarLoad = "1";
                            vm.appointment_step_form_data.selected_date = response.data.next_available_date;
                            vm.bookingpress_disable_date( bpa_selected_service, response.data.next_available_date );
                            return;
                        } else {
                            /* Check full day appointments block */
                            
                            if( false == response.data.prevent_next_month_check ){
                                let postDataAction = "bookingpress_get_whole_day_appointments";
                                if( true == response.data.check_for_multiple_days_event ){
                                    postDataAction = "bookingpress_get_whole_day_appointments_multiple_days";
                                }

                                var bkp_wpnonce_pre = "' . $bookingpress_nonce . '";
                                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                                {
                                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                                }
                                else {
                                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                                }

                                var postData = { action: postDataAction,days_off_disabled_dates: vm.days_off_disabled_dates, service_id: bpa_selected_service, max_available_year: response.data.max_available_year, max_available_month:response.data.max_available_month,  selected_service:bpa_selected_service, selected_date:bpa_selected_date, service_id:bpa_selected_service,_wpnonce:bkp_wpnonce_pre_fetch, "next_month": response.data.next_month, "next_year": response.data.next_year, "counter": 1 };

                                postData.bpa_change_store_date = false;
                                if( "undefined" != typeof vm.bookingpress_timezone_offset ){
                                    postData.client_timezone_offset = vm.bookingpress_timezone_offset;
                                    postData.bpa_change_store_date = true;
                                }

                                postData.appointment_data_obj = JSON.stringify( vm.appointment_step_form_data );
                                '.$bookingpress_disable_date_xhr_data.'
                                vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData );
                            }
                            setTimeout(function(){
                                vm.isLoadDateTimeCalendarLoad = "0"
                            },200);
                        }
                    }
                    
                }.bind(this) )
                .catch( function (error) {
                    console.log(error);
                });
            },
            bookingpress_retrieve_daysoff_for_booked_appointment( postData ){
                const vm = this;
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) ).then( function( response ) {
                    vm.days_off_disabled_dates = response.data.days_off_disabled_dates;
                    '.$bookingpress_disable_multiple_days_event_xhr_resp_after.'

                    vm.isLoadDateTimeCalendarLoad = 0;
                    if(false == response.data.prevent_next_month_check && response.data.counter <= 3 ){ /** Currently data will be checked for next 3 months */
                        postData.days_off_disabled_dates = vm.days_off_disabled_dates;
                        postData.next_month = response.data.next_month;
                        postData.next_year = response.data.next_year;
                        postData.counter++;

                        if( postData.counter < 4 ){
                            vm.isHoldBookingRequest = true;
                            vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData );
                        } else {
                            vm.v_calendar_check_month_dates = true;
                            let next_month_year = {
                                "month": parseInt( response.data.next_month ),
                                "year": response.data.next_year,
                                "postData": postData
                            };
                            vm.v_calendar_next_month_dates = next_month_year;
                            vm.isHoldBookingRequest = false;
                        }
                        
                        /* V-Calendar disabled dates change start */
                        if( "" != response.data.days_off_disabled_dates_string ){
                            let bpa_disable_date = response.data.days_off_disabled_dates_string.split(",");
                            for( let temp_date in bpa_disable_date ){
                                let disabled_date = bpa_disable_date[temp_date];
                                if( "string" != typeof disabled_date ){
                                    continue;
                                }
                                if( "undefined" != typeof vm.browser_details && "safari" == vm.browser_details && 16 > vm.browser_version ){
                                    let disable_date_data = disabled_date.split(" ");
                                    vm.v_calendar_disable_dates.push( disable_date_data[0] );
                                } else {
                                    vm.v_calendar_disable_dates.push( disabled_date );
                                }
                            }
                            
                        }
                        /* V-Calendar disabled dates change end */

                        '.$bookingpress_disable_multiple_days_event_xhr_resp.'
                    } else {
                        /* V-Calendar disabled dates change start */
                        if( "" != response.data.days_off_disabled_dates_string ){
                            let bpa_disable_date = response.data.days_off_disabled_dates_string.split(",");
                            for( let temp_date in bpa_disable_date ){
                                let disabled_date = bpa_disable_date[temp_date];
                                if( "string" != typeof disabled_date ){
                                    continue;
                                }
                                if( "undefined" != typeof vm.browser_details && "safari" == vm.browser_details && 16 > vm.browser_version ){
                                    let disable_date_data = disabled_date.split(" ");
                                    vm.v_calendar_disable_dates.push( disable_date_data[0] );
                                } else {
                                    vm.v_calendar_disable_dates.push( disabled_date );
                                }
                            }
                            
                        }
                        /* V-Calendar disabled dates change end */

                        '.$bookingpress_disable_multiple_days_event_xhr_resp.'
					}
                });
            },
            bpaMoveMonthResponsive( page ){
                const vm = this;
                if( "undefined" != typeof vm.v_calendar_check_month_dates && true == vm.v_calendar_check_month_dates && "undefined" != typeof vm.v_calendar_next_month_dates && false == vm.isHoldBookingRequest ){

                    let current_month = page.month;
                    let current_year = page.year;

                    let next_page_month = parseInt( vm.v_calendar_next_month_dates.month ) || null;
                    let next_page_year = parseInt( vm.v_calendar_next_month_dates.year ) || null;

                    if( null != next_page_month && null != next_page_year ){
                        let postData = vm.v_calendar_next_month_dates.postData;
                        if( current_year == next_page_year && current_month == next_page_month ){
                            postData.counter = 0;
                            postData.next_month = next_page_month;
                            postData.next_year = next_page_year;

                            vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData, true );
                        } else if( current_year == next_page_year && current_month > next_page_month ){
                            let current_date = new Date( `${current_year}-${current_month}-1` );
                            let next_page_date = new Date( `${next_page_year}-${next_page_month}-1` );

                            const monthDiff = current_date.getMonth() - next_page_date.getMonth();
                            
                            postData.counter = 0 - monthDiff;
                            postData.next_month = next_page_month;
                            postData.next_year = next_page_year;

                            vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData, true );
                        }
                    }

                }
            },
            bpaMoveMonth( page ){
                const vm = this;
                if( "undefined" != typeof vm.v_calendar_check_month_dates && true == vm.v_calendar_check_month_dates && "undefined" != typeof vm.v_calendar_next_month_dates && false == vm.isHoldBookingRequest ){

                    let current_month = page.month;
                    let current_year = page.year;

                    let next_page_month = parseInt( vm.v_calendar_next_month_dates.month ) || null;
                    let next_page_year = parseInt( vm.v_calendar_next_month_dates.year ) || null;

                    if( null != next_page_month && null != next_page_year ){
                        let postData = vm.v_calendar_next_month_dates.postData;
                        if( current_year == next_page_year && current_month == next_page_month ){
                            postData.counter = 0;
                            postData.next_month = next_page_month;
                            postData.next_year = next_page_year;

                            vm.isLoadDateTimeCalendarLoad = 1;
                            vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData );
                        } else if( current_year == next_page_year && current_month > next_page_month ){
                            let current_date = new Date( `${current_year}-${current_month}-1` );
                            let next_page_date = new Date( `${next_page_year}-${next_page_month}-1` );

                            const monthDiff = current_date.getMonth() - next_page_date.getMonth();
                            
                            postData.counter = 0 - monthDiff;
                            postData.next_month = next_page_month;
                            postData.next_year = next_page_year;

                            vm.isLoadDateTimeCalendarLoad = 1;
                            vm.bookingpress_retrieve_daysoff_for_booked_appointment( postData );
                        }
                    }

                }
            },
            bookingpress_get_all_parent_node_with_overflow_hidden( elem ){
                if (!Element.prototype.matches) {
                    Element.prototype.matches = Element.prototype.matchesSelector ||
                        Element.prototype.mozMatchesSelector ||
                        Element.prototype.msMatchesSelector ||
                        Element.prototype.oMatchesSelector ||
                        Element.prototype.webkitMatchesSelector ||
                        function(s) {
                            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                                i = matches.length;
                            while (--i >= 0 && matches.item(i) !== this) {}
                            return i > -1;
                        };
                }
            
                var parents = [];
            
                for (; elem && elem !== document; elem = elem.parentNode) {
                    let computed_style = getComputedStyle( elem );
                    
                    if( computed_style.overflow == "hidden" || computed_style.overflowX == "hidden" || computed_style.overflowY == "hidden" ){
                        parents.push(elem);
                    }
                }
                return parents;
            },
            bookingpress_onload_func(){
                const vm = this;
                vm.current_screen_size = "desktop";
                if(window.outerWidth >= 1200){
                    vm.current_screen_size = "desktop";
                }else if(window.outerWidth < 1200 && window.outerWidth >= 768){
                    vm.current_screen_size = "tablet";
                }else if(window.outerWidth < 768){
                    vm.current_screen_size = "mobile";
                }
                if(window.innerWidth <= 576){
                    vm.bookingpress_container_dynamic_class = "";
                    let bookingpress_container = vm.$el;
                    let parents_with_hidden_overflow = vm.bookingpress_get_all_parent_node_with_overflow_hidden( bookingpress_container );
                    let apply_overflow = ( parents_with_hidden_overflow.length > 0 ) ? true : false;
                    window.addEventListener("scroll", function(e){
                        
                        let bookingpress_scrollTop = bookingpress_container.getBoundingClientRect().top;
                        let bookingpress_scrollBottom = bookingpress_container.getBoundingClientRect().bottom;
                        let bpa_current_scroll = window.scrollY;
                        bookingpress_scrollBottom = bpa_current_scroll + bookingpress_scrollBottom + bookingpress_scrollTop;

                        if( bookingpress_scrollTop < 50 && bpa_current_scroll >= bookingpress_scrollTop && bpa_current_scroll <= bookingpress_scrollBottom ){
                            vm.bookingpress_container_dynamic_class = "bpa-front__mc--is-sticky" ;
                            vm.bookingpress_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                            if( apply_overflow ){
                                for( let i = 0; i < parents_with_hidden_overflow.length; i++ ){
                                    let parent = parents_with_hidden_overflow[i];
                                    parent.classList.add("--bpa-is-overflow-visible");
                                }
                            }
                        } else {
                            vm.bookingpress_container_dynamic_class = ""; 
                            vm.bookingpress_footer_dynamic_class = ""; /* Change this string */
                            if( apply_overflow ){
                                for( let i = 0; i < parents_with_hidden_overflow.length; i++ ){
                                    let parent = parents_with_hidden_overflow[i];
                                    parent.classList.remove("--bpa-is-overflow-visible");
                                }
                            }
                        } 
                    });
                }
                window.addEventListener("resize", function(e){
                    if( window.innerWidth <= 576 ){
                        vm.bookingpress_container_dynamic_class = "";
                        let bookingpress_container = vm.$el;

                        let bookingpress_scrollTop = bookingpress_container.getBoundingClientRect().top;
                        let bookingpress_scrollBottom = bookingpress_container.getBoundingClientRect().bottom;
                        let bpa_current_scroll = window.scrollY;
                        bookingpress_scrollBottom = bpa_current_scroll + bookingpress_scrollBottom + bookingpress_scrollTop;

                        if( bookingpress_scrollTop < 50 && bpa_current_scroll >= bookingpress_scrollTop && bpa_current_scroll <= bookingpress_scrollBottom ){
                            vm.bookingpress_container_dynamic_class = "bpa-front__mc--is-sticky"; 
                            vm.bookingpress_footer_dynamic_class = "__bpa-is-sticky" /* Change this string */
                        } else {
                            vm.bookingpress_container_dynamic_class = ""; 
                            vm.bookingpress_footer_dynamic_class = ""; /* Change this string */
                        } 
                    }
                });
            },
            bookingpress_step_navigation(current_tab, next_tab, previous_tab, is_strict_validate = 1){

                const vm = this;
                var bookingpress_is_validate = 0;

                vm.bookingpress_remove_error_msg();

                var bookingpress_validate_fields_arr = vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].validate_fields;

                if((vm.bookingpress_current_tab == "basic_details") && vm.bookingpress_current_tab != next_tab && current_tab != previous_tab){
                    bookingpress_validate_fields_arr.forEach(function(currentValue, index, arr){
                        if(vm.bookingpress_current_tab == vm.bookingpress_current_tab && vm.appointment_step_form_data[currentValue] == "" && vm.bookingpress_current_tab != next_tab && current_tab != previous_tab){
                            vm.bookingpress_set_error_msg(vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].validation_msg[currentValue]);
                            bookingpress_is_validate = 1;
                        }
                    });

                    if(bookingpress_is_validate == 0 && is_strict_validate == 1){
                        var customer_form = "appointment_step_form_data";
                        vm.$refs[customer_form].validate((valid) => {
                            if (!valid) {
                                bookingpress_is_validate = 1;
                            }else{
                                bookingpress_is_validate = 0;
                            }
                        });
                    }
                }else{
                    if(is_strict_validate == 1){
                        bookingpress_validate_fields_arr.forEach(function(currentValue, index, arr){
                            if(vm.bookingpress_current_tab == vm.bookingpress_current_tab && vm.appointment_step_form_data[currentValue] == "" && vm.bookingpress_current_tab != next_tab && current_tab != previous_tab){
                                if( currentValue == "selected_start_time" && vm.appointment_step_form_data[currentValue] == "" ) {
                                    if( vm.appointment_step_form_data.selected_service_duration_unit != "d" ){
                                        vm.bookingpress_set_error_msg(vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].validation_msg[currentValue]);
                                        bookingpress_is_validate = 1;
                                    }
                                } else {
                                    vm.bookingpress_set_error_msg(vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].validation_msg[currentValue]);
                                    bookingpress_is_validate = 1;
                                }
                            }
                        });
                    }
                    
                    '.$bookingpress_dynamic_validation_for_step_change.'

                    
                    /* if( "undefined" == typeof retrieved_timeslots && "datetime" == next_tab && 0 == bookingpress_is_validate ){
                        let selected_service_id = vm.appointment_step_form_data.selected_service;
                        vm.bookingpress_disable_date(selected_service_id,vm.appointment_step_form_data.selected_date);
                    } */
                }

                if( "service" == current_tab && "service" != vm.bookingpress_current_tab ){
                    var bookingpress_selected_date = vm.appointment_step_form_data.selected_date+"T00:00:00+00:00";
                    var bookingpress_disable_dates_arr = vm.days_off_disabled_dates.split(",");
                    if(bookingpress_disable_dates_arr.includes(bookingpress_selected_date)){
                        let newDate = new Date('.( !empty( $bookingpress_site_date ) ? '"' . $bookingpress_site_date . '"' : '' ).');
                        let pattern = /(\d{4}\-\d{2}\-\d{2})/;
                        if( !pattern.test( newDate ) ){

                            let sel_month = newDate.getMonth() + 1;
                            let sel_year = newDate.getFullYear();
                            let sel_date = newDate.getDate();

                            if( sel_month < 10 ){
                                sel_month = "0" + sel_month;
                            }

                            if( sel_date < 10 ){
                                sel_date = "0" + sel_date;
                            }
                            
                            newDate = sel_year + "-" + sel_month + "-" + sel_date;
                        }
                        
                        vm.appointment_step_form_data.selected_date = newDate;
                    }
                }                                
                
                if( ("basic_details" == current_tab && "service" == vm.bookingpress_current_tab) || ("summary" == current_tab && "service" == vm.bookingpress_current_tab) ){                  
                    if(vm.appointment_step_form_data.selected_service_duration_unit != "d"){                                                
                        if(vm.appointment_step_form_data.selected_start_time == ""){
                            bookingpress_is_validate = 1;                            
                        }
                    }
                }

                if(bookingpress_is_validate == 0){
                    vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].is_allow_navigate = 1;
                    vm.bookingpress_current_tab = current_tab;
                    vm.bookingpress_next_tab = next_tab;
                    vm.bookngpress_previous_tab = previous_tab;
                    vm.bookingpress_sidebar_step_data[vm.bookingpress_current_tab].is_allow_navigate = 1;
                    if( "datetime" == current_tab ){
                        let selected_service_id = vm.appointment_step_form_data.selected_service;
                        vm.bookingpress_disable_date(selected_service_id,vm.appointment_step_form_data.selected_date);
                    }
                }

                if( window.innerWidth <= 576 ){
                    let container = vm.$el;
                    let pos = 0;
                    if( null != container ){
                        pos = container.getBoundingClientRect().top + window.scrollY;
                    }

                    const myVar = Error().stack;
                    let allow_scroll = true;
                    if( /mounted/.test( myVar ) ){
                        allow_scroll = false;
                    }
                    if( allow_scroll ){
                    setTimeout(function(){
                        window.scrollTo({
                            top: pos,
                            behavior: "smooth",
                        });
                    }, 500);
                    }
                }

                if( "summary" == current_tab && "summary" == vm.bookingpress_current_tab ) {
                    var total_payment_div_count = document.querySelectorAll(".bpa-front-module--pm-body__item").length;
                    if(total_payment_div_count == 1){
                        var total_payment_div = document.querySelector(".bpa-front-module--pm-body__item");
                        if( null != total_payment_div && "undefined" != typeof total_payment_div) {
                            vm.prevent_verification_on_load = true;
                            total_payment_div.click();
                            vm.prevent_verification_on_load = false;
                        }
                    }
                }

                '.$bookingpress_dynamic_next_page_request_filter.';
            },
            bookingpress_select_multi_day_range(day = ""){
                const vm = this;
                if(vm.appointment_step_form_data.selected_date){
                    day = vm.appointment_step_form_data.selected_date;
                    vm.bookingpress_selected_date_range = [];
                    if(vm.appointment_step_form_data.selected_service_duration_unit == "d"){
                        var selected_date = new Date(day);
                        var selected_service_duration = vm.appointment_step_form_data.selected_service_duration;
                        var bookingpress_selected_date_range = [];
                        var new_date = new Date(day);
                        bookingpress_selected_date_range.push(day);
                        for(var i = 1; i < selected_service_duration; i++) {							
                            new_date.setDate(new_date.getDate() + 1);
                            var month = "" + (new_date.getMonth() + 1),
                            day = "" + new_date.getDate(),
                            year = new_date.getFullYear();					
                            if (month.length < 2){ 
                                month = "0" + month;
                            }
                            if (day.length < 2){ 
                                day = "0" + day;
                            }
                            var add_date = [year, month, day].join("-");
                            bookingpress_selected_date_range.push(add_date);
                        }
                        if(bookingpress_selected_date_range.length > 0){
                            vm.bookingpress_selected_date_range = bookingpress_selected_date_range;
                        }
                    }
                }
            },';

            $bookingpress_vue_methods_data = apply_filters('bookingpress_add_appointment_booking_vue_methods', $bookingpress_vue_methods_data);

            return $bookingpress_vue_methods_data;
        }
        
        /**
         * My Appointments Shortcode Data Fields function
         *
         * @return void
         */
        function bookingpress_front_appointments_dynamic_data_fields_func()
        {
            global $bookingpress_front_appointment_vue_data_fields, $BookingPress, $bookingpress_global_options;
            $default_daysoff_details = $BookingPress->bookingpress_get_default_dayoff_dates();
            if (! empty($default_daysoff_details) ) {
                $default_daysoff_details = array_map(
                    function ( $date ) {
                        return date('Y-m-d', strtotime($date));
                    },
                    $default_daysoff_details
                );
                $bookingpress_front_appointment_vue_data_fields['disabledDates'] = $default_daysoff_details;
            } else {
                $bookingpress_front_appointment_vue_data_fields['disabledDates'] = '';
            }

            $bookingpress_mybooking_title_text      = $BookingPress->bookingpress_get_customize_settings('mybooking_title_text', 'booking_my_booking');
            $bookingpress_hide_customer_details     = $BookingPress->bookingpress_get_customize_settings('hide_customer_details', 'booking_my_booking');            
            $bookingpress_allow_cancel_appointments = $BookingPress->bookingpress_get_customize_settings('allow_to_cancel_appointment', 'booking_my_booking');            
            $bookingpress_reset_button_label        = $BookingPress->bookingpress_get_customize_settings('reset_button_title', 'booking_my_booking');
            $bookingpress_apply_button_label        = $BookingPress->bookingpress_get_customize_settings('apply_button_title', 'booking_my_booking');
            $bookingpress_search_appointment_label  = $BookingPress->bookingpress_get_customize_settings('search_appointment_title', 'booking_my_booking');
            $bookingpress_search_date_title  = $BookingPress->bookingpress_get_customize_settings('search_date_title', 'booking_my_booking');
            $bookingpress_search_end_date_title  = $BookingPress->bookingpress_get_customize_settings('search_end_date_title', 'booking_my_booking');
            $bookingpress_my_appointment_menu_title  = $BookingPress->bookingpress_get_customize_settings('my_appointment_menu_title', 'booking_my_booking');
            $bookingpress_delete_appointment_menu_title  = $BookingPress->bookingpress_get_customize_settings('delete_appointment_menu_title', 'booking_my_booking');
            $confirmation_message_for_the_cancel_appointment = $BookingPress->bookingpress_get_settings('confirmation_message_for_the_cancel_appointment', 'message_setting');

            $bookingpress_mybooking_title_text = !empty($bookingpress_mybooking_title_text) ? stripslashes_deep($bookingpress_mybooking_title_text) : '';
            $bookingpress_reset_button_label = !empty($bookingpress_reset_button_label) ? stripslashes_deep($bookingpress_reset_button_label) : '';
            $bookingpress_apply_button_label = !empty($bookingpress_apply_button_label) ? stripslashes_deep($bookingpress_apply_button_label) : '';
            $bookingpress_search_appointment_label = !empty($bookingpress_search_appointment_label) ? stripslashes_deep($bookingpress_search_appointment_label) : '';
            $bookingpress_search_date_title = !empty($bookingpress_search_date_title) ? stripslashes_deep($bookingpress_search_date_title) : '';
            $bookingpress_search_end_date_title = !empty($bookingpress_search_end_date_title) ? stripslashes_deep($bookingpress_search_end_date_title) : '';
            $bookingpress_my_appointment_menu_title = !empty($bookingpress_my_appointment_menu_title) ? stripslashes_deep($bookingpress_my_appointment_menu_title) : '';
            $bookingpress_delete_appointment_menu_title = !empty($bookingpress_delete_appointment_menu_title) ? stripslashes_deep($bookingpress_delete_appointment_menu_title) : '';
            $confirmation_message_for_the_cancel_appointment = !empty($confirmation_message_for_the_cancel_appointment) ? stripslashes_deep($confirmation_message_for_the_cancel_appointment) : '';
            $bookingpress_hide_customer_details = $bookingpress_hide_customer_details = ( $bookingpress_hide_customer_details == 'true' ) ? 1 : 0;
            $bookingpress_allow_cancel_appointments = $bookingpress_allow_cancel_appointments = ( $bookingpress_allow_cancel_appointments == 'true' ) ? 1 : 0;

            $bookingpress_front_appointment_vue_data_fields['mybooking_title_text'] = $bookingpress_mybooking_title_text;
            $bookingpress_front_appointment_vue_data_fields['hide_customer_details'] = $bookingpress_hide_customer_details;
            $bookingpress_front_appointment_vue_data_fields['allow_cancel_appointments'] = $bookingpress_allow_cancel_appointments;
            $bookingpress_front_appointment_vue_data_fields['reset_button_title'] = $bookingpress_reset_button_label;
            $bookingpress_front_appointment_vue_data_fields['apply_button_title'] = $bookingpress_apply_button_label;
            $bookingpress_front_appointment_vue_data_fields['search_appointment_title'] = $bookingpress_search_appointment_label;
            $bookingpress_front_appointment_vue_data_fields['search_date_title'] = $bookingpress_search_date_title;
            $bookingpress_front_appointment_vue_data_fields['search_end_date_title'] = $bookingpress_search_end_date_title;
            $bookingpress_front_appointment_vue_data_fields['my_appointment_menu_title'] = $bookingpress_my_appointment_menu_title;
            $bookingpress_front_appointment_vue_data_fields['delete_appointment_menu_title'] = $bookingpress_delete_appointment_menu_title;
            $bookingpress_front_appointment_vue_data_fields['confirmation_message_for_the_cancel_appointment'] = $confirmation_message_for_the_cancel_appointment;
            $bookingpress_front_appointment_vue_data_fields['bookingpress_is_user_logged_in'] =  is_user_logged_in() ? '1' : '0';              	                        
            $bookingpress_front_appointment_vue_data_fields['bookingpress_user_fullname'] =  '';
            $bookingpress_front_appointment_vue_data_fields['bookingpress_user_email'] =  '';           
            $bookingpress_front_appointment_vue_data_fields['bookingpress_avatar_url'] = '';
            

            $bookingpress_front_appointment_vue_data_fields["current_screen_size"] = "";
			$bookingpress_front_appointment_vue_data_fields["container_size"] = "";

			$bookingpress_front_appointment_vue_data_fields['bookingpress_myappointment_footer_dynamic_class'] = '';
			$bookingpress_front_appointment_vue_data_fields['bookingpress_myappointment_header_dynamic_class'] = '';

            $bookingpress_front_appointment_vue_data_fields['bookingpress_my_booking_current_tab'] = 'my_appointment';

            $bookingpress_global_options_arr = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_default_date_format = $BookingPress->bookingpress_check_common_date_format($bookingpress_global_options_arr['wp_default_date_format']);

            $bookingpress_front_appointment_vue_data_fields['masks'] = array(
                'input' => strtoupper($bookingpress_default_date_format),
            );

            $bookingpress_delete_account_content  = $BookingPress->bookingpress_get_customize_settings('delete_account_content', 'booking_my_booking');
            $bookingpress_front_appointment_vue_data_fields['delete_account_content'] = do_shortcode(stripslashes($bookingpress_delete_account_content));

            $bookingpress_front_appointment_vue_data_fields['bookingpress_cancel_appointment_drawer'] = false;
            $bookingpress_front_appointment_vue_data_fields['bookingpress_cancel_drawer_direction'] = 'btt';

            $bookingpress_front_appointment_vue_data_fields['bookingpress_previous_row_obj'] = '';

            $bookingpress_front_appointment_vue_data_fields['bookingpress_created_nonce'] = esc_html(wp_create_nonce('bpa_wp_nonce'));

            $bookingpress_front_appointment_vue_data_fields['is_display_pagination'] = 0; 

            $bookingpress_front_appointment_vue_data_fields['disable_my_appointments_apply'] = false;

            $bookingpress_front_appointment_vue_data_fields = apply_filters('bookingpress_front_appointment_add_dynamic_data', $bookingpress_front_appointment_vue_data_fields);            

            echo json_encode($bookingpress_front_appointment_vue_data_fields);
        }
        
        /**
         * My Appointments Shortcode Helper Variables
         *
         * @return void
         */
        function bookingpress_front_appointments_dynamic_helper_vars_func()
        {
            global $bookingpress_global_options;
            $bookingpress_options     = $bookingpress_global_options->bookingpress_global_options();
            $bookingpress_locale_lang = $bookingpress_options['locale'];
            ?>
            var lang = ELEMENT.lang.<?php echo esc_html($bookingpress_locale_lang); ?>;
            ELEMENT.locale(lang);
            <?php
            do_action('bookingpress_add_front_appointment_helper_vars');
        }

        /**
         * My Appointments Shortcode On Load Methods
         *
         * @return void
         */
        function bookingpress_front_appointments_dynamic_on_load_methods_func()
        {   if(is_user_logged_in()) {
            ?>   
            this.loadFrontAppointments();            
            <?php
             }
            ?>
            this.bookingpress_load_mybooking_form(); 
            this.bookingpress_myappointments_onload_func();
            this.bookingpress_dynamic_add_onload_myappointment_methods_func();
            <?php
            do_action('bookingpress_dynamic_add_onload_myappointment_methods');
        }
        
        /**
         * My Appointments methods or functions
         *
         * @return void
         */
        function bookingpress_front_appointments_dynamic_vue_methods_func()
        {
        ?>
            bookingpress_toggle_calendar(){
                const vm = this;
                vm.$refs.bookingpress_range_calendar.togglePopover();
            },
            bookingpress_clear_datepicker(){
                const vm = this;
                vm.appointment_date_range = '';
            },
            bookingpress_myappointments_onload_func(){
                const vm = this;
                if(window.innerWidth <= 576){
                    vm.bookingpress_myappointment_header_dynamic_class = "";
                    var bookingpress_uniq_id = vm.bookingpress_uniq_id;
                    var bookingpress_container_vars = document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop;
                    var bookingpress_container_bottom = (document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop + document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetHeight) - (window.innerHeight - 100);
                    var bookingpress_container_top = bookingpress_container_vars - 20;
                    var current_selected_tab = vm.current_selected_tab_id;
                    window.addEventListener("scroll", function(e){
                        bookingpress_container_vars = document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop;
                        bookingpress_container_bottom = (document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop + document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetHeight) - (window.innerHeight - 100);
                        bookingpress_container_top = bookingpress_container_vars;
                            
                        var current_position = window.scrollY;
                        if(current_position >= bookingpress_container_top && current_position <= bookingpress_container_bottom){
                            vm.bookingpress_myappointment_header_dynamic_class = "bpa-front__mc--is-sticky"; 
                            vm.bookingpress_myappointment_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                        }else{
                            vm.bookingpress_myappointment_header_dynamic_class = "";
                            vm.bookingpress_myappointment_footer_dynamic_class = "";
                        }
                    });
                }
                window.addEventListener("resize", function(e){ 
                    if(window.innerWidth <= 576){
                        vm.bookingpress_myappointment_header_dynamic_class = "";
                        var bookingpress_uniq_id = vm.bookingpress_uniq_id;

                        var bookingpress_container_vars = document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop;
                        var bookingpress_container_bottom = (document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop + document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetHeight);
                        var bookingpress_container_top = bookingpress_container_vars - 20;
                        
                        
                        bookingpress_container_vars = document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop;
                        bookingpress_container_bottom = (document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop + document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetHeight) - (window.innerHeight - 100);
                        bookingpress_container_top = bookingpress_container_vars;

                        var current_position = window.scrollY;
                        
                        if(current_position >= bookingpress_container_top || current_position <= bookingpress_container_bottom){
                            if( current_position > bookingpress_container_bottom || current_position < bookingpress_container_top ){
                                vm.bookingpress_myappointment_header_dynamic_class = "";
                                vm.bookingpress_myappointment_footer_dynamic_class = "";
                            } else {
                                vm.bookingpress_myappointment_header_dynamic_class = "bpa-front__mc--is-sticky";
                                vm.bookingpress_myappointment_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                            }
                        }else if(vm.bookingpress_myappointment_header_dynamic_class != ""){
                            vm.bookingpress_myappointment_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                        }else{
                            vm.bookingpress_myappointment_header_dynamic_class = "";
                            vm.bookingpress_myappointment_footer_dynamic_class = "";
                        }
                        
                        window.addEventListener("scroll", function(e){
                            bookingpress_container_vars = document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop;
                            bookingpress_container_bottom = (document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetTop + document.getElementById("bookingpress_booking_form_"+bookingpress_uniq_id).offsetHeight) - (window.innerHeight - 100);
                            bookingpress_container_top = bookingpress_container_vars;

                            var current_position = window.scrollY;
                            
                            if(current_position >= bookingpress_container_top || current_position <= bookingpress_container_bottom){
                                if( current_position > bookingpress_container_bottom || current_position < bookingpress_container_top ){
                                    vm.bookingpress_myappointment_header_dynamic_class = "";
                                    vm.bookingpress_myappointment_footer_dynamic_class = "";
                                } else {
                                    vm.bookingpress_myappointment_header_dynamic_class = "bpa-front__mc--is-sticky";
                                    vm.bookingpress_myappointment_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                                }
                            }else if(vm.bookingpress_myappointment_header_dynamic_class != ""){
                                vm.bookingpress_myappointment_footer_dynamic_class = "__bpa-is-sticky"; /* Change this string */
                            }else{
                                vm.bookingpress_myappointment_header_dynamic_class = "";
                                vm.bookingpress_myappointment_footer_dynamic_class = "";
                            }
                        });
                    }else{
                        vm.bookingpress_myappointment_header_dynamic_class = "";
                        vm.bookingpress_myappointment_footer_dynamic_class = "";
                    }
                });
            },
            bookingpress_dynamic_add_onload_myappointment_methods_func(){
                const vm = this;
                vm.current_screen_size = "desktop";
                if(window.outerWidth >= 1200){
                    vm.current_screen_size = "desktop";
                }else if(window.outerWidth < 1200 && window.outerWidth >= 768){
                    vm.current_screen_size = "tablet";
                }else if(window.outerWidth < 768){
                    vm.current_screen_size = "mobile";
                }
                                    
                window.addEventListener('resize', function(event) {
                    if(window.outerWidth >= 1200){
                        vm.current_screen_size = "desktop";
                    }else if(window.outerWidth < 1200 && window.outerWidth >= 768){
                        vm.current_screen_size = "tablet";
                    }else if(window.outerWidth < 768){
                        vm.current_screen_size = "mobile";
                    }
                    /* vm.current_screen_size = document.getElementById("bpa-front-customer-panel-container").offsetWidth; */
                });
            },
            toggleBusy() {
                if(this.is_display_loader == '1'){
                    this.is_display_loader = '0';
                }else{
                    this.is_display_loader = '1';
                }
            },    
            bookingpress_load_mybooking_form(){
                const vm = this;
                setTimeout(function(){
                    vm.is_front_appointment_empty_loader = "0";
                    setTimeout(function(){
                        if(document.getElementById("bpa-front-customer-panel-container") != null){
                            document.getElementById("bpa-front-customer-panel-container").style.display = "block";
                        }
                        if(document.getElementById("bpa-front-data-empty-view--my-bookings") != null){
                            document.getElementById("bpa-front-data-empty-view--my-bookings").style.display = "flex";
                        }
                    }, 500);
                }, 1000);
            },
            loadFrontAppointments( is_display_loader = 0 ) {   

                const vm = this;
                vm.disable_my_appointments_apply = true;
                this.toggleBusy();
                var bookingpress_search_data = { 'search_appointment':this.search_appointment,'selected_date_range': this.appointment_date_range};

                if(is_display_loader == 1 ){
                    vm.is_front_my_appointment_empty_loader = "1";
                }

                var bkp_wpnonce_pre = vm.bookingpress_created_nonce;
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                
                var postData = { action:'bookingpress_get_customer_appointments', perpage:this.per_page, currentpage:this.currentPage, search_data: bookingpress_search_data,_wpnonce:bkp_wpnonce_pre_fetch};
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )                
                .then( function (response) {
                    this.toggleBusy();
                    vm.disable_my_appointments_apply = false;
                    this.items = response.data.items;
                    this.total_records = parseInt(response.data.total_records);                    
                    this.is_display_pagination = 0;

                    if( is_display_loader == 1){
                        vm.is_front_appointment_empty_loader = "0";
                        vm.is_front_my_appointment_empty_loader = "0";
                    }

                    if(this.total_records > 10) {
                        this.is_display_pagination = 1;
                    }
                   
                    this.bookingpress_user_fullname = response.data.customer_details.bookingpress_user_fullname;
                    this.bookingpress_user_email = response.data.customer_details.bookingpress_user_email;
                    this.bookingpress_avatar_url = response.data.customer_details.bookingpress_avatar_url;
                    this.bookingpress_use_placeholder = response.data.customer_details.bookingpress_use_placeholder;
                }.bind(this) )
                .catch( function (error) {     
                    vm.disable_my_appointments_apply = false;               
                    vm.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-appointment-booking'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-appointment-booking'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                    });
                });
            },
            get_formatted_date(iso_date){

                if( true == /(\d{2})\T/.test( iso_date ) ){
                    let date_time_arr = iso_date.split('T');
                    return date_time_arr[0];
                }
                var __date = new Date(iso_date);
                var __year = __date.getFullYear();
                var __month = __date.getMonth()+1;
                var __day = __date.getDate();
                if (__day < 10) {
                    __day = '0' + __day;
                }
                if (__month < 10) {
                    __month = '0' + __month;
                }
                var formatted_date = __year+'-'+__month+'-'+__day;
                return formatted_date;
            },
            cancelAppointment( appointment_id){                
                const vm = new Vue();
                const vm2 = this;
                vm2.is_display_loader = '1';
                vm2.is_disabled = true;
                var cancel_id = appointment_id;

                var bkp_wpnonce_pre = vm2.bookingpress_created_nonce;
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }

                var appointment_cancel_data = { action: 'bookingpress_cancel_appointment', cancel_id: cancel_id, _wpnonce: bkp_wpnonce_pre_fetch };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( appointment_cancel_data ) )
                .then(function(response){
                    vm2.is_display_loader = '0';
                    vm2.is_disabled = false;
                    if(response.data.variant != 'error'){
                        window.location.href = response.data.redirect_url;
                    }else{
                        vm2.$notify({
                            title: response.data.title,
                            message: response.data.msg,
                            type: response.data.variant,
                            customClass: response.data.variant+'_notification',
                        });
                        vm2.loadFrontAppointments();
                    }
                }).catch(function(error){
                    console.log(error);
                    vm2.$notify({
                        title: '<?php esc_html_e('Error', 'bookingpress-appointment-booking'); ?>',
                        message: '<?php esc_html_e('Something went wrong..', 'bookingpress-appointment-booking'); ?>',
                        type: 'error',
                        customClass: 'error_notification',
                    });
                });
            },
            resetFilter(){                
                const vm = this;
                if(vm.search_appointment != '' || vm.appointment_date_range != '') {                    
                    vm.search_appointment = '';
                    vm.appointment_date_range = '';
                    vm.loadFrontAppointments();
                }
            },
            bookingpress_activate_myboooking_tab(tab_name){
                const vm = this;
                vm.bookingpress_my_booking_current_tab = tab_name;
                <?php
                do_action('bookingpress_activate_my_booking_tab_data');
                ?>
            },
            bookingpress_open_cancel_drawer(){
                const vm = this;
                vm.bookingpress_cancel_appointment_drawer = true;
                vm.bookingpress_hide_drawer_overlay('bpa-front-cp-cancel-mob-drawer');
            },
            bookingpress_close_cancel_drawer(){
                const vm = this;
                vm.bookingpress_cancel_appointment_drawer = false;
            },
            bookingpress_full_row_clickable(row, column, event){
                const vm = this;
                let target = event.target;
                let getParent = vm.bookingpress_get_parent_node( target, '.bpa-ma--action-btn-wrapper' );
                if( 0 < getParent.length && getParent[0] != null ){
                    /* Do Nothing */
                } else {
                    vm.$refs.multipleTable.toggleRowExpansion(row);
                }
            },
            bookingpress_get_parent_node( elem, selector ){
                if (!Element.prototype.matches) {
                    Element.prototype.matches = Element.prototype.matchesSelector ||
                        Element.prototype.mozMatchesSelector ||
                        Element.prototype.msMatchesSelector ||
                        Element.prototype.oMatchesSelector ||
                        Element.prototype.webkitMatchesSelector ||
                        function(s) {
                            var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                                i = matches.length;
                            while (--i >= 0 && matches.item(i) !== this) {}
                            return i > -1;
                        };
                }
            
                var parents = [];
            
                for (; elem && elem !== document; elem = elem.parentNode) {
                    if (selector) {
                        if (elem.matches(selector)) {
                            parents.push(elem);
                        }
                        continue;
                    }
                    parents.push(elem);
                }
            
                return parents;
            },
            bookingpress_row_expand(row, expanded){
                const vm = this;
                if(vm.bookingpress_previous_row_obj != ''){
                    vm.$refs.multipleTable.toggleRowExpansion(vm.bookingpress_previous_row_obj, false);
                    if(vm.bookingpress_previous_row_obj != row){
                        vm.$refs.multipleTable.toggleRowExpansion(vm.bookingpress_previous_row_obj);
                        vm.bookingpress_previous_row_obj = row;
                    }else{
                        if(expanded.length == undefined){
                            vm.$refs.multipleTable.toggleRowExpansion(row);
                        }
                        vm.bookingpress_previous_row_obj = '';
                    }
                }else{
                    if(expanded.length == undefined){
                        vm.$refs.multipleTable.toggleRowExpansion(row);
                    }
                    vm.bookingpress_previous_row_obj = row;
                }
                if(typeof vm.bookingpress_reschedule_drawer != "undefined") {
                    vm.bookingpress_reschedule_drawer = false;	
                }      
                if(typeof vm.bookingpress_refund_appointment_drawer != "undefined") {
                    vm.bookingpress_refund_appointment_drawer = false;	
                } 
                if(typeof vm.bookingpress_cancel_appointment_drawer != "undefined") {
                    vm.bookingpress_cancel_appointment_drawer = false;	
                }  
            },
            bookingpress_cancel_delete_account(){
                const vm = this;
                if( 1 == vm.allow_customer_edit_profile ){
                    tab_name = "edit_account";
                } else {
                    tab_name = "my_appointment";
                }
                vm.bookingpress_my_booking_current_tab = tab_name;
            },     
            bookingpress_delete_account(){
                
                var bkp_wpnonce_pre = "<?php echo esc_html(wp_create_nonce('bpa_wp_nonce')); ?>";
                var bkp_wpnonce_pre_fetch = document.getElementById("_wpnonce");
                if(typeof bkp_wpnonce_pre_fetch=="undefined" || bkp_wpnonce_pre_fetch==null)
                {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre;
                }
                else {
                    bkp_wpnonce_pre_fetch = bkp_wpnonce_pre_fetch.value;
                }
                var postData = { action: "bookingpress_delete_account", _wpnonce:bkp_wpnonce_pre_fetch };
                axios.post( appoint_ajax_obj.ajax_url, Qs.stringify( postData ) )
                .then( function (response) {
                    if(response.variant != "error"){
                        location.reload();
                    }else{
                        vm.$notify({
                            title: response.data.title,
                            message: response.data.msg,
                            type: response.data.variant,
                            customClass: "error_notification",
                        });
                    }
                }.bind(this) )
                .catch( function (error) {
                    console.log(error);
                });
            },            
            bookingpress_hide_drawer_overlay(selector_class){
                setTimeout(function(){
                    if(document.getElementsByClassName(selector_class).length > 0 && document.getElementsByClassName("v-modal").length > 0){
                        document.getElementsByClassName("v-modal")[0].style.display = "none";
                    }
                },2);
            },
            <?php
            do_action('bookingpress_front_appointment_add_vue_method');
        }
    }
}

global $bookingpress_appointment_bookings, $bookingpress_front_vue_data_fields,$bookingpress_front_appointment_vue_data_fields, $bookingpress_version;
$bookingpress_appointment_bookings = new bookingpress_appointment_bookings();
$bookingpress_options              = $bookingpress_global_options->bookingpress_global_options();
$bookingpress_country_list         = $bookingpress_options['country_lists'];


$bookingpress_front_vue_data_fields             = array(
    'appointment_services_list'   => array(),
    'appointment_formdata'        => array(
        'appointment_selected_customer' => get_current_user_id(),
        'appointment_selected_service'  => '',
        'appointment_booked_date'       => date('Y-m-d', current_time('timestamp')),
        'appointment_booked_time'       => '',
        'appointment_on_site_enabled'   => false,
    ),
    'phone_countries_details'     => json_decode($bookingpress_country_list),
    'final_payable_amount'        => '',
    'activeStepNumber'            => 0,
    'service_categories'          => array(),
    'bookingpress_all_services'   => array(),
    'services_data'               => array(),
    'service_timing'              => array(),
    'no_timeslot_available'       => false,
    'on_site_payment'             => false,
    'paypal_payment'              => false,
    'appointment_step_form_data'  => array(
        'selected_category'              => '',
        'selected_cat_name'              => '',
        'selected_service'               => '',
        'selected_service_name'          => '',
        'selected_service_price'         => '',
        'service_price_without_currency' => 0,
        'selected_date'                  => date('Y-m-d', current_time('timestamp')),
        'selected_start_time'            => '',
        'selected_end_time'              => '',
        'customer_email'                 => '',
        'selected_payment_method'        => '',
        'customer_phone_country'         => 'us',
        'total_services'                 => '',
		'total_category'                 => '',
        'selected_service_duration'      => '',
        'selected_service_duration_unit' => '',        
        'is_enable_validations'          => 1,
        'check_bookingpress_username_set' => '0',
        'bpa_check_user_login'           => '0',
    ),
    'customer_details_rule'       => array(
        'customer_name'  => array(
            'required' => true,
            'message'  => __('Please enter customer name', 'bookingpress-appointment-booking'),
            'trigger'  => 'blur',
        ),
        'customer_email' => array(
            'required' => true,
            'message'  => __('Please enter customer email', 'bookingpress-appointment-booking'),
            'trigger'  => 'blur',
        ),
    ),
    'current_selected_tab_id'     => 1,
    'previous_selected_tab_id'    => 1,
    'next_selected_tab_id'        => '2',
    'isLoadTimeLoader'            => '0',
    'isServiceLoadTimeLoader'     => '0',
    'isLoadDateTimeCalendarLoad'  => '0',
    'isLoadBookingLoader'         => '0',
    'isHoldBookingRequest'       => false,
    'isBookingDisabled'           => false,
    'displayResponsiveCalendar'   => '0',
    'display_service_description' => '0',
    'bookingpress_container_dynamic_class' => '',
    'bookingpress_footer_dynamic_class' => '',
    'bookingpress_current_tab' => 'service',
    'bookingpress_next_tab' => 'datetime',
    'bookingpress_version' => $bookingpress_version,
    'bookngpress_previous_tab' => '',
    'prevent_verification_on_load' => false
);

$bookingpress_front_appointment_vue_data_fields = array(
    'items'                    => array(),
    'search_appointment'       => '',
    'appointment_date_range'   => array(),
    'appointment_service_name' => '',
    'appointment_date'         => '',
    'appointment_duration'     => '',
    'appointment_status'       => '',
    'appointment_payment'      => '',
    'is_disabled'              => false,
    'is_front_appointment_empty_loader' => '1',
    'is_front_my_appointment_empty_loader' => '0',
    'bookingpress_is_user_logged_in' => '0',
    'per_page' => 10,
    'pagination_length' => 10,
    'currentPage' => 1,
    'total_records' => 0,
    'bookingpress_version' => $bookingpress_version,
    'hide_on_single_page' => true,
);
