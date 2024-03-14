<?php
// Get Beaver Triggers
function adfoin_beaver_get_forms( $form_provider ) {
    if( $form_provider != 'beaver' ) {
        return;
    }

    $triggers = array(
        'contact_form' => __( 'Contact Form', 'advanced-form-integration' ),
        'subscription_form' => __( 'Subscription Form', 'advanced-form-integration' ),
        'login_form' => __( 'Login Form', 'advanced-form-integration' )
    );

    return $triggers;
}

// Get Form Fields
function adfoin_beaver_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'beaver' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'contact_form' ) ) ) {
        $fields['name'] = __( 'Name', 'advanced-form-integration' );
        $fields['email'] = __( 'Email', 'advanced-form-integration' );
        $fields['phone'] = __( 'Phone', 'advanced-form-integration' );
        $fields['subject'] = __( 'Subject', 'advanced-form-integration' );
        $fields['message'] = __( 'Message', 'advanced-form-integration' );
        $fields['post_id'] = __( 'Post ID', 'advanced-form-integration' );
        $fields['post_title'] = __( 'Post Title', 'advanced-form-integration' );
        $fields['node_id'] = __( 'Node ID', 'advanced-form-integration' );
    } elseif( in_array( $form_id, array( 'subscription_form' ) ) ) {
        $fields['email'] = __( 'Email', 'advanced-form-integration' );
        $fields['name'] = __( 'Name', 'advanced-form-integration' );
        $fields['post_id'] = __( 'Post ID', 'advanced-form-integration' );
        $fields['post_title'] = __( 'Post Title', 'advanced-form-integration' );
        $fields['node_id'] = __( 'Node ID', 'advanced-form-integration' );
    } elseif( in_array( $form_id, array( 'login_form' ) ) ) {
        $fields['name'] = __( 'Name', 'advanced-form-integration' );
        $fields['password'] = __( 'Password', 'advanced-form-integration' );
        $fields['post_id'] = __( 'Post ID', 'advanced-form-integration' );
        $fields['post_title'] = __( 'Post Title', 'advanced-form-integration' );
        $fields['node_id'] = __( 'Node ID', 'advanced-form-integration' );
    }

    return $fields;
}

function adfoin_beaver_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ($saved_records as $record) {
        $action_provider = $record['action_provider'];
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func("adfoin_{$action_provider}_send_data", $record, $posted_data);
        }
    }
}

add_action( 'fl_module_contact_form_after_send', 'adfoin_beaver_handle_contact_form_data', 10, 6 );

function adfoin_beaver_handle_contact_form_data( $mailto, $subject, $template, $headers, $result ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'beaver', 'contact_form' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $posted_data['name'] = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $posted_data['email'] = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $posted_data['phone'] = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $posted_data['subject'] = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
    $posted_data['message'] = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
    $posted_data['post_id'] = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : '';
    $posted_data['node_id'] = isset( $_POST['node_id'] ) ? sanitize_text_field( $_POST['node_id'] ) : '';
    $posted_data['post_title'] = get_the_title( $posted_data['post_id'] );

    adfoin_beaver_send_data( $saved_records, $posted_data );
}

add_action( 'fl_builder_subscribe_form_submission_complete', 'adfoin_beaver_handle_subscribe_form_data', 10, 6 );

function adfoin_beaver_handle_subscribe_form_data( $response, $settings, $email, $name, $template_id, $post_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'beaver', 'subscription_form' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $posted_data['name'] = $name;
    $posted_data['email'] = $email;
    $posted_data['post_id'] = $post_id;
    $posted_data['node_id'] = isset( $_POST['node_id'] ) ? sanitize_text_field( $_POST['node_id'] ) : '';
    $posted_data['post_title'] = get_the_title( $post_id );

    adfoin_beaver_send_data( $saved_records, $posted_data );
}

add_action( 'fl_builder_login_form_submission_complete', 'adfoin_beaver_handle_login_form_data', 10, 5 );

function adfoin_beaver_handle_login_form_data( $settings, $password, $name, $template_id, $post_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'beaver', 'login_form' );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();
    $posted_data['name'] = $name;
    $posted_data['password'] = $password;
    $posted_data['post_id'] = $post_id;
    $posted_data['node_id'] = isset( $_POST['node_id'] ) ? sanitize_text_field( $_POST['node_id'] ) : '';
    $posted_data['post_title'] = get_the_title( $post_id );

    adfoin_beaver_send_data( $saved_records, $posted_data );
}