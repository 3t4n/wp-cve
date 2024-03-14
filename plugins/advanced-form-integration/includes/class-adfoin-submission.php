<?php

class Advanced_Form_Integration_Submission {

    public function __construct() {
        add_action( 'wp_ajax_adfoin_get_forms', array( $this,'get_forms' ) );
        add_action( 'wp_ajax_adfoin_get_form_fields', array( $this,'get_form_fields' ) );
        add_action( 'wp_ajax_adfoin_get_tasks', array( $this,'get_tasks' ) );
        add_action( 'admin_post_adfoin_save_integration', array( $this,'save_integration' ) );
        add_action( 'admin_post_adfoin_resend_log_data', array( $this,'resend_log_data' ) );
        add_action('wp_ajax_adfoin_enable_integration', array( $this, 'adfoin_enable_integration' ) );
    }

    public function get_forms() {
        if( !wp_verify_nonce( $_POST['nonce'], 'advanced-form-integration' ) ) {
            return;
        }

        $form_provider = sanitize_text_field( $_POST['formProviderId'] );

        if( $form_provider ) {
            $forms = call_user_func( "adfoin_{$form_provider}_get_forms", $form_provider );

            if( !is_wp_error( $forms ) ) {
                wp_send_json_success( $forms );
            }
        }

        wp_die();
    }

    /*
     * Get all fields for a specific form
     */
    public function get_form_fields() {
        if( !wp_verify_nonce( $_POST['nonce'], 'advanced-form-integration' ) ) {
            return;
        }

        $form_provider = sanitize_text_field( $_POST['formProviderId'] );
        $form_id       = sanitize_text_field( $_POST['formId'] );

        if( $form_provider && $form_id ) {
            $fields = call_user_func( "adfoin_{$form_provider}_get_form_fields", $form_provider, $form_id );

            if( !is_wp_error( $fields ) ) {
                wp_send_json_success( $fields );
            }
        }

        wp_die();
    }

    /*
     * Get Tasks for a action provider
     */
    public function get_tasks() {
        if( !wp_verify_nonce( $_POST['nonce'], 'advanced-form-integration' ) ) {
            return;
        }

        $action_provider = sanitize_text_field( $_POST['actionProviderId'] );

        if( $action_provider ) {
            $tasks = adfoin_get_action_tasks( $action_provider );

            if( !is_wp_error( $tasks ) ) {
                wp_send_json_success( $tasks );
            }
        }

        wp_die();
    }

    /*
     * Save Integration
     */
    public function save_integration() {
        if( !wp_verify_nonce( $_POST['_wpnonce'], 'adfoin-integration' ) ) {
            return;
        }

        $action_provider_id = isset( $_POST['action_provider'] ) ? sanitize_text_field( $_POST['action_provider'] ) : '';

        $trigger_data = isset( $_POST['triggerData'] ) ? adfoin_sanitize_text_or_array_field( $_POST['triggerData'] ) : array();
        if( $trigger_data ) {
            $trigger_data = json_decode( $trigger_data, true );
        }

        $action_data = isset( $_POST['actionData'] ) ? adfoin_sanitize_text_or_array_field( $_POST['actionData'] ) : array();

        if( $action_data ) {
            $action_data = json_decode( $action_data, true );
        }

        $field_data = isset( $_POST['fieldData'] ) ? adfoin_sanitize_text_or_array_field( $_POST['fieldData'] ) : array();

        $integration_title = isset( $trigger_data['integrationTitle'] ) ? $trigger_data['integrationTitle'] : '';
        $form_provider_id  = isset( $trigger_data['formProviderId'] ) ? $trigger_data['formProviderId'] : '';
        $form_id           = isset( $trigger_data['formId'] ) ? $trigger_data['formId'] : '';
        $form_name         = isset( $trigger_data['formName'] ) ? $trigger_data['formName'] : '';
        $action_provider   = isset( $action_data['actionProviderId'] ) ? $action_data['actionProviderId'] : '';
        $task              = isset( $action_data['task'] ) ? $action_data['task'] : '';
        $type              = isset( $_POST['type'] ) ? adfoin_sanitize_text_or_array_field( $_POST['type'] ) : '';

        $all_data = array(
            'trigger_data' => $trigger_data,
            'action_data'  => $action_data,
            'field_data'   => $field_data
        );

        global $wpdb;

        $integration_table = $wpdb->prefix . 'adfoin_integration';

        if ( $type == 'new_integration' ) {

            $result = $wpdb->insert(
                $integration_table,
                array(
                    'title'           => $integration_title,
                    'form_provider'   => $form_provider_id,
                    'form_id'         => $form_id,
                    'form_name'       => $form_name,
                    'action_provider' => $action_provider,
                    'task'            => $task,
                    'data'            => json_encode( $all_data, true ),
                    'status'          => 1
                )
            );
        }

        if ( $type == 'update_integration' ) {

            $id = esc_sql( trim( $_POST['edit_id'] ) );

            if ( $type != 'update_integration' &&  !empty( $id ) ) {
                exit;
            }

            $result = $wpdb->update( $integration_table,
                array(
                    'title'           => $integration_title,
                    'form_provider'   => $form_provider_id,
                    'form_id'         => $form_id,
                    'form_name'       => $form_name,
                    'action_provider' => $action_provider,
                    'task'            => $task,
                    'data'            => json_encode( $all_data, true ),
                ),
                array(
                    'id' => $id
                )
            );
        }

        advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration' );
    }

    /*
     * Resend Log Data
     */
    public function resend_log_data() {
        if( !wp_verify_nonce( $_POST['_wpnonce'], 'adfoin-resend-log' ) ) {
            return;
        }

        $log_id         = isset( $_POST['log_id'] ) ? sanitize_text_field( $_POST['log_id'] ) : '';
        $integration_id = isset( $_POST['integration_id'] ) ? sanitize_text_field( $_POST['integration_id'] ) : '';
        $raw_data       = isset( $_POST['request-data'] ) ? sanitize_text_field( $_POST['request-data'] ) : '';
        $data           = json_decode( stripslashes( $raw_data ), true );
        $url            = isset( $data['url'] ) ? $data['url'] : '';
        $args           = isset( $data['args'] ) ? $data['args'] : array();
        $args['body']   = json_encode( $args['body'] );
        $response       = adfoin_remote_request( $url, $args );

        adfoin_add_to_log( $response, $url, $args, array( 'id' => $integration_id ), $log_id );
        wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-log&action=view&id=' . $log_id ) );
    }

    public function adfoin_enable_integration() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $id      = isset( $_POST['id'] ) && $_POST['id'] ? sanitize_text_field( $_POST['id'] ) : '';
        $enabled = isset( $_POST['enabled'] ) && $_POST['enabled'] ? sanitize_text_field( $_POST['enabled'] ) : '';

        global $wpdb;

        $table = $wpdb->prefix . 'adfoin_integration';

        if ( '1' == $enabled ) {
            $action_status = $wpdb->update( $table,
                array(
                    'status' => true,
                ),
                array( 'id'=> $id )
            );
        }else{
            $action_status = $wpdb->update( $table,
                array(
                    'status' => false,
                ),
                array( 'id'=> $id )
            );
        }
    }
}