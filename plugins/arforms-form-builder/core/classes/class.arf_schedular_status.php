<?php

class arf_schedular_status{

    function __construct(){

        add_action( 'wp_ajax_arforms_retrieve_schdular_status', array( $this, 'arforms_retrieve_schdular_status' ) );

        add_action( 'wp_ajax_arf_manually_execute_schedular', array( $this, 'arf_manually_execute_schedular_callback') );

    }

    function arf_manually_execute_schedular_callback(){

        global $arformsmain;

        if ( !isset( $_POST['_wpnonce_arflite'] ) || (isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' )) ) {
            echo esc_attr( 'security_error' );
            die;
		}

        $return_arr = array(
            'is_stop' => false    
        );

        $schedular_data = $arformsmain->arforms_get_settings( 'arforms_schedular_data', 'scheduling_settings' );

        $schedular_data = json_decode( $schedular_data, true );

        if( empty( $schedular_data ) ){
            $return_arr['is_stop'] = true;
        } else {
            
            $task_details = array();
            foreach( $schedular_data as $version => $sche_data ){
            
                $task_details[ $version ]['total'] = count( $sche_data );
                $task_details[ $version ]['pending'] = 0;
                foreach( $sche_data as $action_hook ){
                    $hook_process_status = get_option( 'arforms_process_' . $action_hook .'_status' );
                    if( '0' !== $hook_process_status ){
                        continue;
                    }
                    $task_details[ $version ]['pending']++;
                }
            }
        }

        $run_cron = false;
        if( !empty( $task_details ) ){
            foreach( $task_details as $version => $task_data ){
                if( $task_data['pending'] > 0 ){
                    $run_cron = true;
                    break;
                }
            }
        }

        if( true == $run_cron ){
            spawn_cron();
        } else {
            $return_arr['is_stop'] = true;
        }
 
        wp_send_json( $return_arr );
        die;

    }

    function arforms_retrieve_schdular_status(){
        global $wpdb, $arformsmain;

        $schedular_data = $arformsmain->arforms_get_settings( 'arforms_schedular_data', 'scheduling_settings' );

        $schedular_data = !empty( $schedular_data) ? json_decode( $schedular_data, true ) : array();

        $return_arr = array();
        $action_details = array();

        $counter = 0;
        $ai = 0;
        if ( !empty( $schedular_data ) && count( $schedular_data ) > 0 ) {
            foreach( $schedular_data as $version => $sche_data ){

                foreach( $sche_data as $action_hook_key => $action_hook ){

                    $hook_status = get_option( 'arforms_process_'. $action_hook .'_status' );
                    
                    $action_details[$ai][0] = $action_hook;
                    $action_details[$ai][1] = $this->arforms_get_schedular_status_name( $hook_status );

                    $schedular_date_details = $this->arforms_get_schedular_date_details( $action_hook );

                    $action_details[$ai][2] = $schedular_date_details['scheduled_date'];

                    if( !empty( get_option( 'arforms_process_'.$action_hook.'_end_timestamp' ) ) ){
                        $action_details[$ai][3] = date('Y-m-d H:i:s', get_option( 'arforms_process_'.$action_hook.'_end_timestamp' ) ) ;
                    } else {
                        $current_timestamp = current_time('timestamp');
                        $scheduled_timestamp = !empty( $schedular_date_details['scheduled_date'] ) ? strtotime( $schedular_date_details['scheduled_date'] ) : false;

                        if( false === $scheduled_timestamp ){
                            $this->arforms_run_manual_schedular( $action_hook );
                        } else {
                            if( $current_timestamp > $scheduled_timestamp ){
                                global $wpdb, $tbl_arf_fields;    
                                if( 300 <= ($current_timestamp - $scheduled_timestamp) ){ //manually run the scheduled hook if the time different is more than 5 min
                                    $this->arforms_run_manual_schedular( $action_hook );
                                }
                            }
                        }
                        $action_details[$ai][3] = '-';
                    }
                    $action_details[$ai][4] = $version;
                    
                    $ai++;
                }

                $return_arr = array(
                    'aaData'               => $action_details,
                    'wp_scheduler_status'  => ( defined( 'DISABLE_WP_CRON' ) ? DISABLE_WP_CRON : true )
                );
                $counter++;
            }
        } else {
            $return_arr = array(
                'aaData'               => $action_details,
                'wp_scheduler_status'  => ( defined( 'DISABLE_WP_CRON' ) ? DISABLE_WP_CRON : true )
            );
        }

        echo wp_json_encode( $return_arr );
        die;

    }

    function arforms_run_manual_schedular( $action_hook ){
        $action_hook_details = get_option($action_hook.'_schedular_callback');
        if( !empty( $action_hook_details ) ){
            update_option( $action_hook.'_callback_status', '' );
            $action_hook_details = json_decode( $action_hook_details, true );
            $action_hook_params = $action_hook_details['args'][0];            
            do_action( $action_hook, $action_hook_params );
        }
    }

    function arforms_get_schedular_status_name( $schedular_status ){

        if( '0' === $schedular_status ){
            return esc_html__( 'Scheduled', 'arforms-form-builder' );
        } else if( 1 == $schedular_status ){
            return esc_html__( 'In Process', 'arforms-form-builder' );
        } else if( 2 == $schedular_status ){
            return esc_html__( 'Completed', 'arforms-form-builder' );
        }

        return esc_html__( 'Pending', 'arforms-form-builder' );
    }

    function arforms_get_schedular_date_details( $action_hook ){

        $return_data = array();
        if( !empty( get_option( $action_hook.'_process' ) ) ){
            $return_data['scheduled_date'] = date('Y-m-d H:i:s', get_option( $action_hook.'_process' ) );
        } else {
            $action_details_data = get_option( $action_hook . '_schedular_callback' );
            if( !empty( $action_details_data ) ){
                $action_details_data = json_decode( $action_details_data, true );
                
                $return_data['scheduled_date'] = date( 'Y-m-d H:i:s', $action_details_data['timestamp'] );
            } else {
                $return_data['scheduled_date'] = '-';
            }
        }
        return $return_data;
    }

}

new arf_schedular_status();