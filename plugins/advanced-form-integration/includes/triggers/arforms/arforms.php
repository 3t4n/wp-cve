<?php

// Get Forms List
function adfoin_arforms_get_forms( $form_provider ) {

    if( $form_provider != 'arforms' ) {
        return;
    }
    
    global $wpdb;
    $raw = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}arf_forms", ARRAY_A );
    $forms = wp_list_pluck( $raw, 'name', 'id' );

    return $forms;
}

// Get Form Fields
function adfoin_arforms_get_form_fields( $form_provider, $form_id ) {

    if( $form_provider != 'arforms' ) {
        return;
    }

    global $wpdb;
    $raw = $wpdb->get_results("SELECT id, field_key, name, type FROM {$wpdb->prefix}arf_fields WHERE form_id = {$form_id}");
    $fields = wp_list_pluck( $raw, 'name', 'id' );

    return $fields;
}

// Get Form Name
function adfoin_arforms_get_form_name( $form_id ) {
    global $wpdb;
    $form_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}arforms WHERE id = {$form_id}");
    return $form_name;
}

add_action( 'arfliteentryexecute', 'adfoin_arforms_submission', 10, 4 );

add_action( 'arfentryexecute', 'adfoin_arforms_submission', 10, 4 );

function adfoin_arforms_submission( $params, $errors, $form, $item_meta_values ) {

    $form_id = $form->id;

    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'arforms', $form_id );

    if( empty( $saved_records ) ) {
        return;
    }

    global $post;

    $posted_data = $item_meta_values;

    $special_tag_values = adfoin_get_special_tags_values( $post );

    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }

    $posted_data = apply_filters( 'adfoin_arforms_submission', $posted_data, $form_id );

    $integration->send( $saved_records, $posted_data );
}