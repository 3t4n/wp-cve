<?php

function adfoin_formcraftb_get_forms( $form_provider )
{
    if ( $form_provider != 'formcraftb' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT id, name FROM {$wpdb->prefix}formcraft_b_forms";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms = wp_list_pluck( $result, 'name', 'id' );
    return $forms;
}

function adfoin_formcraftb_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'formcraftb' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT * FROM {$wpdb->prefix}formcraft_b_forms WHERE id = {$form_id}";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $field_data = json_decode( stripslashes( $result[0]['meta_builder'] ), 1 );
    foreach ( $field_data['fields'] as $field ) {
        $field_title = ( isset( $field['elementDefaults'], $field['elementDefaults']['main_label'] ) ? $field['elementDefaults']['main_label'] : $field['identifier'] );
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'oneLineText' == $field['type'] || 'email' == $field['type'] ) {
                $fields[$field['identifier']] = $field_title;
            }
        }
    }
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_formcraftb_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'formcraftb' ) {
        return;
    }
    global  $wpdb ;
    $form_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}formcraft_b_forms WHERE id = " . $form_id );
    return $form_name;
}

add_action( 'wp_ajax_formcraft_basic_form_submit', 'adfoin_formcraftb_submission' );
function adfoin_formcraftb_submission()
{
    if ( !isset( $_POST['id'] ) || !ctype_digit( $_POST['id'] ) ) {
        return;
    }
    if ( isset( $_POST['website'] ) && $_POST['website'] != '' ) {
        return;
    }
    global  $wpdb ;
    $form_id = sanitize_text_field( $_POST['id'] );
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'formcraftb' AND form_id = %s", $form_id ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    foreach ( $_POST as $key => $value ) {
        $posted_data[$key] = html_entity_decode( sanitize_text_field( $value ) );
    }
    $posted_data['submission_date'] = date( 'Y-m-d' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];
        
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                'record'      => $record,
                'posted_data' => $posted_data,
            ),
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    
    }
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_formcraftb_trigger_fields' );
}
function adfoin_formcraftb_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'formcraftb'" is="formcraftb" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_formcraftb_trigger_template" );
function adfoin_formcraftb_trigger_template()
{
    ?>
        <script type="text/template" id="formcraftb-template">
            <tr valign="top" class="alternate" v-if="trigger.formId">
                <td scope="row-title">
                    <label for="tablecell">
                        <span class="dashicons dashicons-info-outline"></span>
                    </label>
                </td>
                <td>
                    <p>
                        <?php 
    esc_attr_e( 'The basic AFI plugin supports name and email fields only', 'advanced-form-integration' );
    ?>
                    </p>
                </td>
            </tr>
        </script>
    <?php 
}
