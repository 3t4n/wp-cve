<?php

add_action( 'wpcf7_submit', 'adfoin_cf7_submission', 10, 2 );

/**
 * Handle Contact Form 7 submissions.
 *
 * @param WPCF7_Submission $contact_form The Contact Form 7 submission object.
 * @param array $result The Contact Form 7 submission result.
 * @return void
 */
function adfoin_cf7_submission( $contact_form, $result ) {

    if( 'validation_failed' == $result['status'] || 'spam' == $result['status'] ) {
        return;
    }

    // Get the form ID.
    $form_id = $contact_form->id();

    // Get the saved integration records.
    global $wpdb;

    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'cf7' AND form_id = %s", $form_id ), ARRAY_A );

    if( empty( $saved_records ) ) {
        return;
    }

    // Set the maximum execution time to 5 minutes.
    @set_time_limit(300);

    // Get the global post object.
    global $post;

    // Get the WPCF7 submission object.
    $submission  = WPCF7_Submission::get_instance();

    // Get the posted data.
    $posted_data = $submission->get_posted_data();

    // Get the form fields.
    $form_fields = $contact_form->scan_form_tags();

    // Loop through the form fields.
    foreach( $form_fields as $field ) {
        
        // Check if the field is a select, radio, or checkbox field.
        if( isset( $field['type'] ) && in_array( $field['type'], array( 'select', 'select*', 'radio', 'radio*', 'checkbox', 'checkbox*' ) ) ) {
            
            // Check if the field exists in the POST data.
            if( array_key_exists( $field['name'], $_POST ) ) {

                // Sanitize the field value.
                $pipe_value = adfoin_sanitize_text_or_array_field( $_POST[$field['name']] );

                // Add the field value to the posted data array.
                $posted_data['raw_' . $field['name']] = $pipe_value;
            }
        }

        // check if field type is file and file is uploaded
        if( isset( $field['type'] ) && in_array( $field['type'], array( 'file', 'file*' ) ) ) {
            $uploaded_files = $submission->uploaded_files();

            if( !empty( $uploaded_files ) ) {
                foreach( $uploaded_files as $key => $file ) {
                    if( is_array( $file ) ) {
                        foreach( $file as $index => $file_path ) {
                            try {
                                $file_name = basename( $file_path );
                                $file_name = time() . '_' . $file_name;
                                $file_upload_dir = wp_upload_dir();
                                $file_upload_path = $file_upload_dir['path'] . '/' . $file_name;
                                $file_upload_url = $file_upload_dir['url'] . '/' . $file_name;
                                copy( $file_path, $file_upload_path );
                                $posted_data[$key] = $file_upload_url;
                            } catch ( Exception $e ) {
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }

    // Get the post ID.
    $post_id = (int) $submission->get_meta( 'container_post_id' );

    // Get the post object, if it exists.
    if( $post_id ) {
        $post = get_post( $post_id, 'OBJECT' );
    }

    // Get the special tag values.
    $special_tag_values = adfoin_get_special_tags_values( $post );

    // Merge the posted data and special tag values arrays.
    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    
    // Set the submission date.
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );

    // Set the form ID.
    $posted_data['form_id']         = $form_id;

    // Set the form name.
    $posted_data['form_name']       = $contact_form->title();

    // Job queue setting.
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    // Loop through the saved integration records.
    foreach ( $saved_records as $record ) {

        // Get the action provider.
        $action_provider = $record['action_provider'];

        // If the job queue setting is enabled, enqueue the action.
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            // Call the action provider function.
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}

/*
 * Get Forms list
 */
function adfoin_cf7_get_forms( $form_provider ) {
    if( $form_provider != 'cf7' ) {
        return;
    }

    $args     = array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1 );
    $cf7Forms = get_posts( $args );
    $forms    = wp_list_pluck( $cf7Forms, 'post_title', 'ID' );

    return $forms;
}

/*
 * Get form fields
 */
function adfoin_cf7_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'cf7' ) {
        return;
    }

    $ContactForm  = WPCF7_ContactForm::get_instance( $form_id );
    $form_fields  = $ContactForm->scan_form_tags();
    $final_fields = array();

    foreach( $form_fields as $field ) {
        if( $field['name'] ) {
            if( isset( $field['type'] ) && in_array( $field['type'], array( 'select', 'select*', 'radio', 'radio*', 'checkbox', 'checkbox*' ) ) ) {
                if( isset( $field['pipes'] ) && $field['pipes'] ) {
                    $final_fields['raw_' . $field['name']] = $field['name'] . ' (before pipe)';
                }
            }
            
            $final_fields[$field['name']] = $field['name'];
        }
    }

    $special_tags = adfoin_get_special_tags();

    if( is_array( $final_fields ) && is_array( $special_tags ) ) {
        $final_fields = $final_fields + $special_tags;
    }

    $final_fields['form_id']   = __( 'Form ID', 'advanced-form-integration' );
    $final_fields['form_name'] = __( 'Form Name', 'advanced-form-integration' );
    return $final_fields;
}