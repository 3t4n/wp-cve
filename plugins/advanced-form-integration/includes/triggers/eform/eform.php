<?php

function adfoin_eform_get_forms( $form_provider ) {

    if ( $form_provider != 'eform' ) {
        return;
    }

    global $wpdb;

    $query  = "SELECT id, name FROM {$wpdb->prefix}fsq_form";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms  = wp_list_pluck( $result, 'name', 'id' );

    return $forms;
}

function adfoin_eform_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'eform' ) {
        return;
    }

    global $wpdb;

    $query        = $wpdb->prepare( "SELECT pinfo, freetype FROM {$wpdb->prefix}fsq_form WHERE id = %s", $form_id );
    $result       = $wpdb->get_results( $query, ARRAY_A );
    $pinfo        = maybe_unserialize( $result[0]['pinfo'] );
    $freetype     = maybe_unserialize( $result[0]['freetype'] );
    $fields1      = wp_list_pluck( $pinfo, 'title', 'type' );
    $fields2      = wp_list_pluck( $freetype, 'title', 'type' );
    $special_tags = adfoin_get_special_tags();
    $all_fields   = $fields1 + $fields2 + $special_tags;

    return $all_fields;
}

/*
 * Get Form name by form id
 */
function adfoin_eform_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "eform" ) {
        return;
    }

    global $wpdb;

    $form_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}fsq_form WHERE id = " . $form_id );

    return $form_name;
}

add_action( 'ipt_fsqm_hook_save_success', 'adfoin_eform_submission', 10, 1 );

function adfoin_eform_submission( $form ) {

    $form_id     = $form->form_id;
    $posted_data = (array) $form->data;

    $posted_data["submission_date"] = date( "Y-m-d H:i:s" );
    $posted_data["user_ip"]         = adfoin_get_user_ip();

    global $wpdb, $post;

    $special_tag_values = adfoin_get_special_tags_values( $post );

    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }

    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'eform' AND form_id = %s", $form_id ), ARRAY_A );
    $job_queue     = get_option( 'adfoin_general_settings_job_queue' );

    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];

        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record'      => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    }
}
