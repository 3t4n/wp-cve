<?php 
    class arf_growth_plugin{

        public function __construct(){
            add_action('wp_ajax_arf_install_booking_press', array( $this, 'arf_install_booking_press_get_func'));  
            add_action('wp_ajax_arf_armember_install', array( $this, 'arf_armember_install_func'));         
            add_action('wp_ajax_arf_install_arprice', array( $this, 'arf_install_arprice_fun'));         
        }

        function arf_install_booking_press_get_func() {

            if( isset($_POST['arf_install_booking_press_nonce']) && $_POST['arf_install_booking_press_nonce'] != "" && wp_verify_nonce( $_POST['arf_install_booking_press_nonce'], 'arf_install_booking_press_nonce' ) ){ //phpcs:ignore

                if ( ! file_exists( WP_PLUGIN_DIR . '/bookingpress-appointment-booking/bookingpress-appointment-booking.php' ) ) {
        
                    if ( ! function_exists( 'plugins_api' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
                    }
                    $response = plugins_api(
                        'plugin_information',
                        array(
                            'slug'   => 'bookingpress-appointment-booking',
                            'fields' => array(
                                'sections' => false,
                                'versions' => true,
                            ),
                        )
                    );
                    if ( ! is_wp_error( $response ) && property_exists( $response, 'versions' ) ) {
                        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                        }
                        $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                        $source   = ! empty( $response->download_link ) ? $response->download_link : '';
                        
                        if ( ! empty( $source ) ) {
                            if ( $upgrader->install( $source ) === true ) {
                                activate_plugin( 'bookingpress-appointment-booking/bookingpress-appointment-booking.php' );
                                $arm_install_activate = 1; 
                            }
                        }
                    } else {
                        $source_url = 'https://bookingpressplugin.com/bpa_misc/bkp_lite_plugin_install_api.php';
                        $get_custom_response = wp_remote_get( $source_url, array( 'method' => 'GET') );
                        if(!is_wp_error($get_custom_response)) {
                            $get_custom_response_body = json_decode(wp_remote_retrieve_body($get_custom_response));
                            if(is_object($get_custom_response_body) && !empty($get_custom_response_body))
                            {
                                if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                                    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                                }
                                $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                                $source   = !empty( $get_custom_response_body->download_link ) ? $get_custom_response_body->download_link : '';
                                
                                if ( ! empty( $source ) ) {
                                    if ( $upgrader->install( $source ) === true ) {
                                        activate_plugin( 'bookingpress-appointment-booking/bookingpress-appointment-booking.php' );
                                        $arm_install_activate = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                if( $arm_install_activate = 1 ){
    
                    $response_data['variant']               = 'success';
                    $response_data['title']                 = esc_html__('Success', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('BookingPress Successfully installed.', 'arforms-form-builder');
                     $response_data['redirect_url']          = admin_url('admin.php?page=ARForms-Growth-Tools');
                } else {
    
                    $response_data['variant']               = 'error';
                    $response_data['title']                 = esc_html__('error', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('Something went wrong please try again later.', 'arforms-form-builder');
                }
                wp_send_json($response_data);
                die;
            }
        }
        
        function arf_armember_install_func() {

            if(isset( $_POST['arf_install_armember_nonce']) && $_POST['arf_install_armember_nonce'] !="" && wp_verify_nonce($_POST['arf_install_armember_nonce'],'arf_install_armember_nonce')){ //phpcs:ignore
                if ( ! file_exists( WP_PLUGIN_DIR . '/armember-membership/armember-membership.php' ) ) {
            
                    if ( ! function_exists( 'plugins_api' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
                    }
                    $response = plugins_api(
                        'plugin_information',
                        array(
                            'slug'   => 'armember-membership',
                            'fields' => array(
                                'sections' => false,
                                'versions' => true,
                            ),
                        )
                    );
                    if ( ! is_wp_error( $response ) && property_exists( $response, 'versions' ) ) {
                        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                        }
                        $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                        $source   = ! empty( $response->download_link ) ? $response->download_link : '';
                        
                        if ( ! empty( $source ) ) {
                            if ( $upgrader->install( $source ) === true ) {
                                activate_plugin( 'armember-membership/armember-membership.php' );
                                $arm_install_activate = 1; 
                            }
                        }
                    } else {
                        $source_url = 'https://www.armemberplugin.com/armember_lite_version/lite_plugin_install_api.php';
                        $get_custom_response = wp_remote_get( $source_url, array( 'method' => 'GET') );
                        if(!is_wp_error($get_custom_response)) {
                            $get_custom_response_body = json_decode(wp_remote_retrieve_body($get_custom_response));
                            if(is_object($get_custom_response_body) && !empty($get_custom_response_body))
                            {
                                if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                                    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                                }
                                $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                                $source   = !empty( $get_custom_response_body->download_link ) ? $get_custom_response_body->download_link : '';
                                
                                if ( ! empty( $source ) ) {
                                    if ( $upgrader->install( $source ) === true ) {
                                        activate_plugin( 'armember-membership/armember-membership.php' );
                                        $arm_install_activate = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                if( $arm_install_activate = 1 ){
    
                    $response_data['variant']               = 'success';
                    $response_data['title']                 = esc_html__('Success', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('ARMember Successfully installed.', 'arforms-form-builder');
                    $response_data['redirect_url']          = admin_url('admin.php?page=ARForms-Growth-Tools');
                } else {
    
                    $response_data['variant']               = 'error';
                    $response_data['title']                 = esc_html__('error', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('Something went wrong please try again later.', 'arforms-form-builder');
                }
                wp_send_json($response_data);
                die;
            }
        }

        function arf_install_arprice_fun(){
            
            if(isset( $_POST['arf_install_arprice_nonce'] ) && sanitize_text_field($_POST['arf_install_arprice_nonce']) !="" && wp_verify_nonce($_POST['arf_install_arprice_nonce'],'arf_install_arprice_nonce')){ //phpcs:ignore

                if ( ! file_exists( WP_PLUGIN_DIR . '/arprice-responsive-pricing-table/arprice-responsive-pricing-table.php' ) ) {
                    if ( ! function_exists( 'plugins_api' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
                    }
                    $response = plugins_api(
                        'plugin_information',
                        array(
                            'slug'   => 'arprice-responsive-pricing-table',
                            'fields' => array(
                                'sections' => false,
                                'versions' => true,
                            ),
                        )
                    );
                    if ( ! is_wp_error( $response ) && property_exists( $response, 'versions' ) ) {
                        if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                        }
                        $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                        $source   = ! empty( $response->download_link ) ? $response->download_link : '';
                        
                        if ( ! empty( $source ) ) {
                            if ( $upgrader->install( $source ) === true ) {
                                activate_plugin( 'arprice-responsive-pricing-table/arprice-responsive-pricing-table.php' );
                                $arp_install_activate = 1; 
                            }
                        }
                    } else {
                        $source_url = 'https://www.arpriceplugin.com/arp_misc/arprice-pricing-table/arprice-pricing-table-latest.zip';
                        $get_custom_response = wp_remote_get( $source_url, array( 'method' => 'GET') );
                        if(!is_wp_error($get_custom_response)) {
                            $get_custom_response_body = json_decode(wp_remote_retrieve_body($get_custom_response));
                            if(is_object($get_custom_response_body) && !empty($get_custom_response_body)){
                                if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
                                    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                                }
                                $upgrader = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );
                                $source   = !empty( $get_custom_response_body->download_link ) ? $get_custom_response_body->download_link : '';
                                
                                if ( ! empty( $source ) ) {
                                    if ( $upgrader->install( $source ) === true ) {
                                        activate_plugin( 'arprice-responsive-pricing-table/arprice-responsive-pricing-table.php' );
                                        $arp_install_activate = 1;
                                    }
                                }
                            }
                        }
                    }
                }
                if( $arp_install_activate = 1 ){
    
                    $response_data['variant']               = 'success';
                    $response_data['title']                 = esc_html__('Success', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('ARPrice Successfully installed.', 'arforms-form-builder');
                    $response_data['redirect_url']          = admin_url('admin.php?page=ARForms-Growth-Tools');
                } else {
    
                    $response_data['variant']               = 'error';
                    $response_data['title']                 = esc_html__('error', 'arforms-form-builder');
                    $response_data['msg']                   = esc_html__('Something went wrong please try again later.', 'arforms-form-builder');
                }
                wp_send_json($response_data);
                die;
            }
        }
        
    }
    

?>