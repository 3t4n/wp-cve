<?php

function adfoin_wpforms_get_forms( $form_provider )
{
    if ( $form_provider != 'wpforms' ) {
        return;
    }
    $args = [
        'post_type'      => 'wpforms',
        'posts_per_page' => -1,
    ];
    $data = get_posts( $args );
    $forms = wp_list_pluck( $data, 'post_title', 'ID' );
    return $forms;
}

function adfoin_wpforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'wpforms' ) {
        return;
    }
    $form = get_post( $form_id );
    $data = json_decode( $form->post_content );
    $raw_fields = $data->fields;
    $fields = array();
    foreach ( $raw_fields as $field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name' == $field->type || 'email' == $field->type ) {
                
                if ( 'name' == $field->type ) {
                    $fields[$field->id . '_first'] = __( 'First Name', 'advanced-form-integration' );
                    $fields[$field->id . '_middle'] = __( 'Middle Name', 'advanced-form-integration' );
                    $fields[$field->id . '_last'] = __( 'Last Name', 'advanced-form-integration' );
                }
                
                if ( 'email' == $field->type ) {
                    $fields[$field->id] = $field->label;
                }
            }
        
        }
    }
    $fields['form_id'] = __( 'Form ID', 'advanced-form-integration' );
    $fields['form_title'] = __( 'Form Title', 'advanced-form-integration' );
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_wpforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'wpforms' ) {
        return;
    }
    $form = get_post( $form_id );
    return $form->post_title;
}

add_action(
    'wpforms_process_complete',
    'adfoin_wpforms_submission',
    10,
    3
);
function adfoin_wpforms_submission( $fields, $entry, $form_data )
{
    global  $wpdb, $post ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'wpforms' AND form_id = %s", $form_data['id'] ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $form_fields = $form_data['fields'];
    $form_field_types = array();
    $posted_data = array();
    foreach ( $form_fields as $key => $value ) {
        $form_field_types[$value['id']] = $value['type'];
    }
    foreach ( $entry['fields'] as $key => $value ) {
        $field_type = $form_field_types[$key];
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name' == $field_type ) {
                $posted_data[$key . '_first'] = ( isset( $value['first'] ) ? $value['first'] : '' );
                $posted_data[$key . '_middle'] = ( isset( $value['middle'] ) ? $value['middle'] : '' );
                $posted_data[$key . '_last'] = ( isset( $value['last'] ) ? $value['last'] : '' );
                $posted_data[$key] = ( isset( $fields[$key], $fields[$key]['value'] ) ? $fields[$key]['value'] : '' );
            }
            
            
            if ( 'email' == $field_type ) {
                
                if ( is_array( $value ) && isset( $value['primary'] ) ) {
                    $posted_data[$key] = $value['primary'];
                    continue;
                }
                
                $posted_data[$key] = $value;
            }
        
        }
    
    }
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
    $posted_data['form_id'] = $form_data['id'];
    $posted_data['form_title'] = $form_data['settings']['form_title'];
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
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_wpforms_trigger_fields' );
}
function adfoin_wpforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'wpforms'" is="wpforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_wpforms_trigger_template" );
function adfoin_wpforms_trigger_template()
{
    ?>
        <script type="text/template" id="wpforms-template">
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
