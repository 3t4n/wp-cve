<?php
/**
 * Class for license
 */

defined( 'ABSPATH' ) || exit;

class EventM_Admin_Controller_License {

    // activate license
    public function ep_activate_license_settings( $form_data ){
        // listen for our activate button to be clicked
        $response = array();
        $error_status = '';
        if( isset( $form_data['ep_license_activate'] ) && ! empty( $form_data['ep_license_activate'] ) ) {
            $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
            $options = $global_settings->ep_get_settings();
            $item_id = $item_name = $license_status_block = $expire_date = $message = '';
            
            // set item details
            $license = isset( $form_data['ep_license_key'] ) ? $form_data['ep_license_key'] : '';
            $ep_store_url = "https://theeventprime.com/";
            $home_url = home_url();

            if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_free' ){
                $item_id = $options->ep_free_license_item_id;
                $item_name = $options->ep_free_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium' ){
                $item_id = $options->ep_premium_license_item_id;
                $item_name = $options->ep_premium_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_professional' ){
                $item_id = $options->ep_professional_license_item_id;
                $item_name = $options->ep_professional_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_essential' ){
                $item_id = $options->ep_essential_license_item_id;
                $item_name = $options->ep_essential_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium_plus' ){
                $item_id = $options->ep_premium_plus_license_item_id;
                $item_name = $options->ep_premium_plus_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_metabundle' ){
                $item_id = $options->ep_metabundle_license_item_id;
                $item_name = $options->ep_metabundle_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_metabundle_plus' ){
                $item_id = $options->ep_metabundle_plus_license_item_id;
                $item_name = $options->ep_metabundle_plus_license_item_name;
            }else{
                $item_id = apply_filters( 'ep_pupulate_license_item_id', $item_id, $form_data );
                $item_name = apply_filters( 'ep_pupulate_license_item_name', $item_name, $form_data );
            }

            // data to send in our API request
            $api_params = array(
                'edd_action' => 'activate_license',
                'license'    => $license,
                'item_name'  => $item_name,
                'item_id'    => $item_id,
                'url'        => home_url()
            );
        
            // Call the custom API.
            $response = wp_remote_post( $ep_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
            
            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            } else {
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                $error_status = $license_data->error;
                if ( false === $license_data->success ) {
                    if( isset( $license_data->error ) ){
                        switch( $license_data->error ) {
                            case 'expired' :
                                $message = sprintf(
                                    __( 'Your license key expired on %s.', 'eventprime-event-calendar-management' ),
                                    date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                                );
                                break;
                            case 'revoked' :
                                $message = __( 'Your license key has been disabled.' , 'eventprime-event-calendar-management' );
                                break;
                            case 'missing' :
                                $message = __( 'Your license key is invalid.' , 'eventprime-event-calendar-management' );
                                break;
                            case 'invalid' :
                            case 'site_inactive' :
                                $message = __( 'Your license is not active for this URL.' , 'eventprime-event-calendar-management' );
                                break;
                            case 'item_name_mismatch' :
                                $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'eventprime-event-calendar-management'  ), $item_name );
                                break;
                            case 'no_activations_left':
                                $message = __( 'Your license key has reached its activation limit.', 'eventprime-event-calendar-management'  );
                                break;
                            default :
                                $message = __( 'An error occurred, please try again.', 'eventprime-event-calendar-management'  );
                                break;
                        }
                    }
                }
            }

            // Check if anything passed on a message constituting a failure
            if ( ! empty( $message ) ) {
            }

            // if( ! empty( $error_status ) && $error_status == 'invalid_item_id' ){
            //     if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium' || $form_data['ep_license_type'] == 'ep_premium_plus' || $form_data['ep_license_type'] == 'ep_meta_bundle' || $form_data['ep_license_type'] == 'ep_meta_bundle_plus' ){
            //         $item_id = $options->ep_premium_plus_license_item_id;
            //         $item_name = $options->ep_premium_plus_license_item_name;
            //     }
            //      // data to send in our API request
            //     $api_params = array(
            //         'edd_action' => 'activate_license',
            //         'license'    => $license,
            //         'item_name'  => $item_name,
            //         'item_id'    => $item_id,
            //         'url'        => home_url()
            //     );
            
            //     // Call the custom API.
            //     $response = wp_remote_post( $ep_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
                
            //     // make sure the response came back okay
            //     if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            //         $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            //     } else {
            //         $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            //         if ( false === $license_data->success ) {
            //             if( isset( $license_data->error ) ){
            //                 switch( $license_data->error ) {
            //                     case 'expired' :
            //                         $message = sprintf(
            //                             __( 'Your license key expired on %s.', 'eventprime-event-calendar-management' ),
            //                             date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
            //                         );
            //                         break;
            //                     case 'revoked' :
            //                         $message = __( 'Your license key has been disabled.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'missing' :
            //                         $message = __( 'Your license key is invalid.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'invalid' :
            //                     case 'site_inactive' :
            //                         $message = __( 'Your license is not active for this URL.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'item_name_mismatch' :
            //                         $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'eventprime-event-calendar-management'  ), $item_name );
            //                         break;
            //                     case 'no_activations_left':
            //                         $message = __( 'Your license key has reached its activation limit.', 'eventprime-event-calendar-management'  );
            //                         break;
            //                     default :
            //                         $message = __( 'An error occurred, please try again.', 'eventprime-event-calendar-management'  );
            //                         break;
            //                 }
            //             }
            //         }
            //     }
            // }

            if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_free' || $form_data['ep_license_type'] == 'ep_premium' || $form_data['ep_license_type'] == 'ep_professional' || $form_data['ep_license_type'] == 'ep_essential' || $form_data['ep_license_type'] == 'ep_premium_plus' || $form_data['ep_license_type'] == 'ep_metabundle' || $form_data['ep_license_type'] == 'ep_metabundle_plus' && ! empty( $license_data ) ){
                // $license_data->license will be either "valid" or "invalid"
                $options->ep_premium_license_key  = ( isset( $form_data['ep_license_key'] ) && ! empty( $form_data['ep_license_key'] )  && ( $license_data->license == 'valid' || $license_data->license = 'deactivated' ) ) ? $form_data['ep_license_key'] : '';
                $options->ep_premium_license_status  = ( isset( $license_data->license ) && ! empty( $license_data->license ) && $license_data->license == 'valid' ) ? $license_data->license : '';
                $options->ep_premium_license_response  = ( isset( $license_data ) && ! empty( $license_data ) ) ? $license_data : '';
                $options->ep_premium_license_option_value  = ( ! empty( $form_data['ep_license_type'] ) ) ? $form_data['ep_license_type'] : '';
                $global_settings->ep_save_settings( $options );
            }
            
            do_action( 'ep_save_license_settings', $form_data, $license_data );

            if( isset( $license_data->expires ) && ! empty( $license_data->expires ) ) {
                if( $license_data->expires == 'lifetime' ){
                    $expire_date = __( 'Your license key is activated for lifetime', 'eventprime-event-calendar-management' );
                }else{
                    $expire_date = sprintf( __( 'Your license Key expires on %s.', 'eventprime-event-calendar-management' ), date( 'F d, Y', strtotime($license_data->expires) ) );
                }
            }else{
                $expire_date = '';
            }     
            if( $form_data['ep_license_type'] == 'ep_free' || $form_data['ep_license_type'] == 'ep_professional' || $form_data['ep_license_type'] == 'ep_essential' || $form_data['ep_license_type'] == 'ep_metabundle' || $form_data['ep_license_type'] == 'ep_metabundle_plus' || $form_data['ep_license_type'] == 'ep_premium_plus' ){
                ob_start(); ?>
                <?php if( isset( $license_data->license ) && $license_data->license == 'valid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_deactivate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_deactivate" id="ep_premium_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'invalid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }else{ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php } ?>      
                <?php
                $license_status_block = ob_get_clean();
            }else{
                ob_start(); ?>
                <?php if( isset( $license_data->license ) && $license_data->license == 'valid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_deactivate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_deactivate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'invalid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }else{ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php } ?>      
                <?php
                $license_status_block = ob_get_clean();
            }

            if ( empty( $message ) || $license_data->license == 'valid' ) {
                if( isset( $license_data->license ) && $license_data->license == 'valid' ){
                    $message = __( 'Your License key is activated.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'invalid' ){
                    $message = __( 'Your license key is invalid.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'deactivated' ){
                    $message = __( 'Your License key is deactivated.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'failed' ){
                    $message = __( 'Your License key deactivation failed. Please try after some time.', 'eventprime-event-calendar-management'  );
                }
            }

            $response = array( 'license_data' => $license_data, 'license_status_block' => $license_status_block, 'expire_date' => $expire_date, 'message' => $message );
        
            return $response;
        }
        
    }

    // deactivate license
    public function ep_deactivate_license_settings( $form_data ){
        // listen for our deactivate button to be clicked
        $response = array();
        $error_status = '';
        if( isset( $form_data['ep_license_deactivate'] ) && ! empty( $form_data['ep_license_deactivate'] ) ) {
            $global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
            $options = $global_settings->ep_get_settings();
            $item_id = $item_name = $license_status_block = $expire_date = $message = '';

            // set item details
            $license = isset( $form_data['ep_license_key'] ) ? $form_data['ep_license_key'] : '';
            $ep_store_url = "https://theeventprime.com/";
            $home_url = home_url();

            if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_free' ){
                $item_id = $options->ep_free_license_item_id;
                $item_name = $options->ep_free_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium' ){
                $item_id = $options->ep_premium_license_item_id;
                $item_name = $options->ep_premium_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_professional' ){
                $item_id = $options->ep_professional_license_item_id;
                $item_name = $options->ep_professional_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_essential' ){
                $item_id = $options->ep_essential_license_item_id;
                $item_name = $options->ep_essential_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium_plus' ){
                $item_id = $options->ep_premium_plus_license_item_id;
                $item_name = $options->ep_premium_plus_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_metabundle' ){
                $item_id = $options->ep_metabundle_license_item_id;
                $item_name = $options->ep_metabundle_license_item_name;
            }elseif( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_metabundle_plus' ){
                $item_id = $options->ep_metabundle_plus_license_item_id;
                $item_name = $options->ep_metabundle_plus_license_item_name;
            }else{
                $item_id = apply_filters( 'ep_pupulate_license_item_id', $item_id, $form_data );
                $item_name = apply_filters( 'ep_pupulate_license_item_name', $item_name, $form_data );
            }

            // data to send in our API request
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license'    => $license,
                'item_name'  => $item_name,
                'item_id'    => $item_id, // the name of our product in EDD
                'url'        => home_url()
            );
        
            // Call the custom API.
            $response = wp_remote_post( $ep_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
            
            // make sure the response came back okay
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            } else {
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                $error_status = $license_data->error;
                if ( false === $license_data->success ) {
                    if( isset( $license_data->error ) ){
                        switch( $license_data->error ) {
                            case 'expired' :
                                $message = sprintf(
                                    __( 'Your license key expired on %s.' ),
                                    date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                                );
                                break;
                            case 'revoked' :
                                $message = __( 'Your license key has been disabled.', 'eventprime-event-calendar-management'   );
                                break;
                            case 'missing' :
                                $message = __( 'Your license key is invalid.', 'eventprime-event-calendar-management'   );
                                break;
                            case 'invalid' :
                            case 'site_inactive' :
                                $message = __( 'Your license is not active for this URL.', 'eventprime-event-calendar-management'   );
                                break;
                            case 'item_name_mismatch' :
                                $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'eventprime-event-calendar-management'   ), $item_name );
                                break;
                            case 'no_activations_left':
                                $message = __( 'Your license key has reached its activation limit.', 'eventprime-event-calendar-management'   );
                                break;
                            default :
                                $message = __( 'An error occurred, please try again.', 'eventprime-event-calendar-management'   );
                                break;
                        }
                    }
                }
            }

            // Check if anything passed on a message constituting a failure
            if ( ! empty( $message ) ) {

            }

            // if( ! empty( $error_status ) && $error_status == 'invalid_item_id' ){
            //     if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_premium_plus' ){
            //         $item_id = $options->ep_premium_plus_license_item_id;
            //         $item_name = $options->ep_premium_plus_license_item_name;
            //     }
            //      // data to send in our API request
            //     $api_params = array(
            //         'edd_action' => 'deactivate_license',
            //         'license'    => $license,
            //         'item_name'  => $item_name,
            //         'item_id'    => $item_id,
            //         'url'        => home_url()
            //     );
            
            //     // Call the custom API.
            //     $response = wp_remote_post( $ep_store_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
                
            //     // make sure the response came back okay
            //     if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            //         $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
            //     } else {
            //         $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            //         if ( false === $license_data->success ) {
            //             if( isset( $license_data->error ) ){
            //                 switch( $license_data->error ) {
            //                     case 'expired' :
            //                         $message = sprintf(
            //                             __( 'Your license key expired on %s.', 'eventprime-event-calendar-management' ),
            //                             date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
            //                         );
            //                         break;
            //                     case 'revoked' :
            //                         $message = __( 'Your license key has been disabled.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'missing' :
            //                         $message = __( 'Your license key is invalid.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'invalid' :
            //                     case 'site_inactive' :
            //                         $message = __( 'Your license is not active for this URL.' , 'eventprime-event-calendar-management' );
            //                         break;
            //                     case 'item_name_mismatch' :
            //                         $message = sprintf( __( 'This appears to be an invalid license key for %s.', 'eventprime-event-calendar-management'  ), $item_name );
            //                         break;
            //                     case 'no_activations_left':
            //                         $message = __( 'Your license key has reached its activation limit.', 'eventprime-event-calendar-management'  );
            //                         break;
            //                     default :
            //                         $message = __( 'An error occurred, please try again.', 'eventprime-event-calendar-management'  );
            //                         break;
            //                 }
            //             }
            //         }
            //     }
            // }

            if( ! empty( $license_data->expires ) ){
                $license_data->expires = ep_timestamp_to_datetime( $license_data->expires );
            } else{
                $license_data->expires = '';
            }

            if( isset( $form_data['ep_license_type'] ) && $form_data['ep_license_type'] == 'ep_free' || $form_data['ep_license_type'] == 'ep_premium' || $form_data['ep_license_type'] == 'ep_professional' || $form_data['ep_license_type'] == 'ep_essential' || $form_data['ep_license_type'] == 'ep_premium_plus' || $form_data['ep_license_type'] == 'ep_metabundle' || $form_data['ep_license_type'] == 'ep_metabundle_plus' && ! empty( $license_data ) ){
                // $license_data->license will be either "valid" or "invalid"
                $options->ep_premium_license_key  = ( isset( $form_data['ep_license_key'] ) && ! empty( $form_data['ep_license_key'] )  && ( $license_data->license == 'valid' || $license_data->license = 'deactivated' ) ) ? $form_data['ep_license_key'] : '';
                $options->ep_premium_license_status  = ( isset( $license_data->license ) && ! empty( $license_data->license ) && $license_data->license == 'valid' ) ? $license_data->license : '';
                $options->ep_premium_license_response  = ( isset( $license_data ) && ! empty( $license_data ) ) ? $license_data : '';
                $options->ep_premium_license_option_value  = ( ! empty( $form_data['ep_license_type'] ) ) ? $form_data['ep_license_type'] : '';
                $global_settings->ep_save_settings( $options );
            }
            
            do_action( 'ep_save_license_settings', $form_data, $license_data );
            
            if( isset( $license_data->expires ) && ! empty( $license_data->expires ) ) {
                if( $license_data->expires == 'lifetime' ){
                    $expire_date = __( 'Your license key is activated for lifetime', 'eventprime-event-calendar-management' );
                }else{
                    $expire_date = sprintf( __( 'Your License Key expires on %s.', 'eventprime-event-calendar-management' ), date('F d, Y', strtotime( $license_data->expires ) ) );
                }
            }else{
                $expire_date = '';
            }           
            if( $form_data['ep_license_type'] == 'ep_free' || $form_data['ep_license_type'] == 'ep_professional' || $form_data['ep_license_type'] == 'ep_essential' || $form_data['ep_license_type'] == 'ep_metabundle' || $form_data['ep_license_type'] == 'ep_metabundle_plus' || $form_data['ep_license_type'] == 'ep_premium_plus' ){
                ob_start(); ?>
                <?php if( isset( $license_data->license ) && $license_data->license == 'valid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_deactivate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_deactivate" id="ep_premium_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'invalid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'failed' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }else{ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="ep_premium_license_activate" id="ep_premium_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php } ?>    
                <?php
                $license_status_block = ob_get_clean();
            }else{
                ob_start(); ?>
                <?php if( isset( $license_data->license ) && $license_data->license == 'valid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_deactivate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_deactivate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Deactivate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'invalid' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }elseif( isset( $license_data->license ) && $license_data->license == 'failed' ){ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php }else{ ?>
                    <button type="button" class="button action ep-my-2 ep_license_activate" data-prefix="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>" name="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" id="<?php echo esc_attr( $form_data['ep_license_type'] ); ?>_license_activate" value="<?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Activate License', 'eventprime-event-calendar-management' );?></button>
                <?php } ?>    
                <?php
                $license_status_block = ob_get_clean();
            }

            if ( empty( $message ) || $license_data->license == 'valid' ) {
                if( isset( $license_data->license ) && $license_data->license == 'valid' ){
                    $message = __( 'Your License key is activated.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'invalid' ){
                    $message = __( 'Your license key is invalid.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'deactivated' ){
                    $message = __( 'Your License key is deactivated.', 'eventprime-event-calendar-management'  );
                }
                if( isset( $license_data->license ) && $license_data->license == 'failed' ){
                    $message = __( 'Your License key deactivation failed. Please try after some time.', 'eventprime-event-calendar-management'  );
                }
            }

            $response = array( 'license_data' => $license_data, 'license_status_block' => $license_status_block, 'expire_date' => $expire_date, 'message' => $message );
          
        }
        
        return $response;
    }
}