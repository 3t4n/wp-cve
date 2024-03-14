<?php

function adfoin_gravityforms_get_forms( $form_provider )
{
    if ( $form_provider != 'gravityforms' ) {
        return;
    }
    if ( !class_exists( 'GFAPI' ) ) {
        return array();
    }
    $result = GFAPI::get_forms();
    $forms = wp_list_pluck( $result, 'title', 'id' );
    return $forms;
}

function adfoin_gravityforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'gravityforms' ) {
        return;
    }
    if ( !class_exists( 'GFAPI' ) ) {
        return array();
    }
    $form = GFAPI::get_form( $form_id );
    $fields = array();
    $raw_fields = json_decode( json_encode( $form['fields'] ) );
    foreach ( $raw_fields as $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( $field->type == 'name' || $field->type == 'email' ) {
                
                if ( $field->inputs ) {
                    foreach ( $field->inputs as $input ) {
                        $fields[$input->id] = $input->label;
                    }
                    continue;
                }
                
                $fields[$field->id] = $field->label;
            }
        
        }
    }
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_gravityforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != "gravityforms" ) {
        return;
    }
    if ( !class_exists( 'GFAPI' ) ) {
        return array();
    }
    $form = GFAPI::get_form( $form_id );
    return $form['title'];
}

// add_action( 'gform_partialentries_post_entry_saved', 'adfoin_gravityforms_after_submission', 10, 2 );
add_action(
    'gform_after_submission',
    'adfoin_gravityforms_after_submission',
    10,
    2
);
function adfoin_gravityforms_after_submission( $entry, $form )
{
    if ( isset( $entry['status'] ) && 'spam' == $entry['status'] ) {
        return;
    }
    global  $post, $wpdb ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'gravityforms' AND form_id = %s", $entry['form_id'] ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $fields = $form['fields'];
    $field_types = array();
    foreach ( $fields as $key => $value ) {
        $field_types[$value['id']] = $value['type'];
    }
    $posted_data = array();
    $last_key = '';
    foreach ( $entry as $key => $value ) {
        $intkey = intval( $key );
        
        if ( $last_key !== $intkey ) {
            $field_type = ( isset( $field_types[$intkey] ) ? $field_types[$intkey] : '' );
            if ( adfoin_fs()->is_not_paying() ) {
                if ( 'name' == $field_type || 'email' == $field_type ) {
                    $posted_data[$key] = $value;
                }
            }
        } else {
            $posted_data[$key] = $value;
        }
        
        $last_key = $intkey;
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
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
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_gravityforms_trigger_fields' );
}
function adfoin_gravityforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'gravityforms'" is="gravityforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_gravityforms_trigger_template" );
function adfoin_gravityforms_trigger_template()
{
    ?>
        <script type="text/template" id="gravityforms-template">
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
