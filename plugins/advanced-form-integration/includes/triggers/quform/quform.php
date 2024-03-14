<?php

function adfoin_quform_get_forms( $form_provider )
{
    if ( $form_provider != 'quform' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT id, name FROM {$wpdb->prefix}quform_forms";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms = wp_list_pluck( $result, 'name', 'id' );
    return $forms;
}

function adfoin_quform_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'quform' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT config FROM {$wpdb->prefix}quform_forms WHERE id = {$form_id}";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $data = maybe_unserialize( base64_decode( stripslashes( $result[0]["config"] ) ) );
    $fields = array();
    if ( isset( $data['elements'] ) && is_array( $data['elements'] ) ) {
        foreach ( $data['elements'] as $element ) {
            if ( isset( $element['elements'] ) && is_array( $element['elements'] ) ) {
                foreach ( $element['elements'] as $field ) {
                    if ( 'submit' != $field['type'] ) {
                        if ( adfoin_fs()->is_not_paying() ) {
                            if ( 'text' == $field['type'] || 'email' == $field['type'] ) {
                                $fields['quform_' . $form_id . '_' . $field['id']] = $field['label'];
                            }
                        }
                    }
                }
            }
        }
    }
    return $fields;
}

function adfoin_quform_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != "quform" ) {
        return;
    }
    global  $wpdb ;
    $form_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}quform_forms WHERE id = " . $form_id );
    return $form_name;
}

add_filter(
    'quform_post_process',
    'adfoin_quform_post_process',
    10,
    2
);
function adfoin_quform_post_process( $result, $form )
{
    $posted_data = $form->getValues();
    $form_id = $form->getId();
    global  $wpdb, $post ;
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'quform' AND form_id = %s", $form_id ), ARRAY_A );
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
    return $result;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_quform_trigger_fields' );
}
function adfoin_quform_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'quform'" is="quform" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_quform_trigger_template" );
function adfoin_quform_trigger_template()
{
    ?>
        <script type="text/template" id="quform-template">
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
