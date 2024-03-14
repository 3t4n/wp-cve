<?php

function adfoin_calderaforms_get_forms( $form_provider )
{
    if ( $form_provider != 'calderaforms' ) {
        return;
    }
    if ( !class_exists( 'Caldera_Forms_Forms' ) ) {
        return;
    }
    $forms = Caldera_Forms_Forms::get_forms();
    $data = [];
    foreach ( $forms as $form ) {
        $data[] = Caldera_Forms_Forms::get_form( $form );
    }
    $filtered = wp_list_pluck( $data, 'name', 'ID' );
    return $filtered;
}

function adfoin_calderaforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'calderaforms' ) {
        return;
    }
    $data = Caldera_Forms_Forms::get_form( $form_id );
    $fields = array();
    foreach ( $data['fields'] as $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'text' == $field['type'] || 'email' == $field['type'] ) {
                $fields[$field['ID']] = $field['label'];
            }
        }
    }
    $special_tags = adfoin_get_special_tags();
    $fields = array_merge( $fields, $special_tags );
    return $fields;
}

function adfoin_calderaforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'calderaforms' ) {
        return;
    }
    $data = Caldera_Forms_Forms::get_form( $form_id );
    $form_name = $data['name'];
    return $form_name;
}

add_action( 'caldera_forms_submit_complete', 'adfoin_calderaforms_submission', 55 );
function adfoin_calderaforms_submission( $form )
{
    $data = array();
    foreach ( $form['fields'] as $field_id => $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'text' == $field['type'] || 'email' == $field['type'] ) {
                $posted_data[$field_id] = Caldera_Forms::get_field_data( $field_id, $form );
            }
        }
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
    $form_id = $form['ID'];
    global  $wpdb, $post ;
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $special_tag_values ) ) {
        $posted_data = array_merge( $posted_data, $special_tag_values );
    }
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'calderaforms' AND form_id = %s", $form_id ), ARRAY_A );
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
    return;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_calderaforms_trigger_fields' );
}
function adfoin_calderaforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'calderaforms'" is="calderaforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_calderaforms_trigger_template" );
function adfoin_calderaforms_trigger_template()
{
    ?>
        <script type="text/template" id="calderaforms-template">
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
