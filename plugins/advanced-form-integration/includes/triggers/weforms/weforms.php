<?php

// add_filter( 'adfoin_form_providers', 'adfoin_weforms_add_provider' );
// function adfoin_weforms_add_provider( $providers ) {
//     if ( is_plugin_active( 'weforms/weforms.php' ) ) {
//         $providers['weforms'] = __( 'weForms', 'advanced-form-integration' );
//     }
//     return $providers;
// }
function adfoin_weforms_get_forms( $form_provider )
{
    if ( $form_provider != 'weforms' ) {
        return;
    }
    $forms = weforms()->form->get_forms();
    $filtered = wp_list_pluck( $forms['forms'], 'name', 'id' );
    return $filtered;
}

function adfoin_weforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'weforms' ) {
        return;
    }
    $form = weforms()->form->get( $form_id );
    $form_fields = $form->get_fields();
    $fields = array();
    foreach ( $form_fields as $single_field ) {
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name_field' == $single_field['template'] ) {
                $fields[$single_field['name']] = $single_field['label'];
                
                if ( 'first-middle-last' == $single_field['format'] ) {
                    $fields[$single_field['name'] . '_first_name'] = $single_field['name'] . ' First Name';
                    $fields[$single_field['name'] . '_middle_name'] = $single_field['name'] . ' Middle Name';
                    $fields[$single_field['name'] . '_last_name'] = $single_field['name'] . ' Last Name';
                }
                
                
                if ( 'first-last' == $single_field['format'] ) {
                    $fields[$single_field['name'] . '_first_name'] = $single_field['name'] . ' First Name';
                    $fields[$single_field['name'] . '_last_name'] = $single_field['name'] . ' Last Name';
                }
            
            }
            
            if ( 'email_address' == $single_field['template'] ) {
                $fields[$single_field['name']] = $single_field['label'];
            }
        }
    
    }
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_weforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'weforms' ) {
        return;
    }
    $form = weforms()->form->get( $form_id );
    $form_name = $form->get_name();
    return $form_name;
}

add_action(
    'weforms_entry_submission',
    'adfoin_weforms_submission',
    10,
    2
);
function adfoin_weforms_submission( $entry_id, $form_id )
{
    global  $wpdb, $post ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'weforms' AND form_id = %s", $form_id ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $entry = weforms_get_entry_data( $entry_id );
    $posted_data = $entry['data'];
    foreach ( $posted_data as $key => $value ) {
        $field_type = $entry['fields'][$key]['type'];
        
        if ( 'name_field' == $field_type ) {
            $name_values = explode( '|', $value );
            if ( count( $name_values ) == 2 ) {
                $name_organized = array(
                    $key . '_first_name' => trim( $name_values[0] ),
                    $key . '_last_name'  => trim( $name_values[1] ),
                );
            }
            if ( count( $name_values ) == 3 ) {
                $name_organized = array(
                    $key . '_first_name'  => trim( $name_values[0] ),
                    $key . '_middle_name' => trim( $name_values[1] ),
                    $key . '_last_name'   => trim( $name_values[2] ),
                );
            }
            $posted_data = $posted_data + $name_organized;
        }
    
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
    
    if ( !is_object( $post ) ) {
        $post_id = url_to_postid( wp_get_referer() );
        if ( $post_id ) {
            $post = get_post( $post_id, 'OBJECT' );
        }
    }
    
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
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_weforms_trigger_fields' );
}
function adfoin_weforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'weforms'" is="weforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_weforms_trigger_template" );
function adfoin_weforms_trigger_template()
{
    ?>
        <script type="text/template" id="weforms-template">
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
