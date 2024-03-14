<?php

// add_filter( 'adfoin_form_providers', 'adfoin_smartforms_add_provider' );

// function adfoin_smartforms_add_provider( $providers ) {

//     if ( is_plugin_active( 'smart-forms/smartforms.php' ) ) {
//         $providers['smartforms'] = __( 'Smart Forms', 'advanced-form-integration' );
//     }

//     return $providers;
// }

function adfoin_smartforms_get_forms( $form_provider ) {

    if ( $form_provider != 'smartforms' ) {
        return;
    }

    global $wpdb;

    $query  = "SELECT form_id, form_name FROM {$wpdb->prefix}rednao_smart_forms_table_name";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms  = wp_list_pluck( $result, 'form_name', 'form_id' );

    return $forms;
}

function adfoin_smartforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'smartforms' ) {
        return;
    }

    global $wpdb;

    $query        = $wpdb->prepare( "SELECT element_options FROM {$wpdb->prefix}rednao_smart_forms_table_name WHERE form_id = %s", $form_id );
    $result       = $wpdb->get_results( $query, ARRAY_A );
    $decoded      = json_decode( $result[0]["element_options"] );
    $fields       = wp_list_pluck( $decoded, 'Label', 'Id' );
    $special_tags = adfoin_get_special_tags();

    if( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }

    return $fields;
}

/*
 * Get Form name by form id
 */
function adfoin_smartforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "smartforms" ) {
        return;
    }

    global $wpdb;

    $form_name = $wpdb->get_var( "SELECT form_name FROM {$wpdb->prefix}rednao_smart_forms_table_name WHERE form_id = " . $form_id );

    return $form_name;
}

add_action( 'sf_after_saving_form', 'adfoin_smartforms_submission' );

function adfoin_smartforms_submission( $data ) {

    $form_id     = $data->FormId;
    $posted_data = array();

    if( is_array( $data->FormEntryData ) ) {
        foreach( $data->FormEntryData as $key => $value ) {
            $posted_data[$key] = $value["value"];
        }
    }

    $posted_data["submission_date"] = date( "Y-m-d H:i:s" );
    $posted_data["user_ip"]         = adfoin_get_user_ip();

    global $wpdb, $post;

    $special_tag_values = adfoin_get_special_tags_values( $post );

    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }

    $saved_records = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'smartforms' AND form_id = " . $form_id, ARRAY_A );
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

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_smartforms_trigger_fields' );
}

function adfoin_smartforms_trigger_fields() {
    ?>
    <tr v-if="trigger.formProviderId == 'smartforms'" is="smartforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php
}

add_action( "adfoin_trigger_templates", "adfoin_smartforms_trigger_template" );

function adfoin_smartforms_trigger_template() {
    ?>
        <script type="text/template" id="smartforms-template">
            <tr valign="top" class="alternate" v-if="trigger.formId">
                <td scope="row-title">
                    <label for="tablecell">
                        <span class="dashicons dashicons-info-outline"></span>
                    </label>
                </td>
                <td>
                    <p>
                        <?php esc_attr_e( 'The basic AFI plugin supports name and email fields only', 'advanced-form-integration' ); ?>
                    </p>
                </td>
            </tr>
        </script>
    <?php
}
