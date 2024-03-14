<?php

function adfoin_fluentforms_get_forms( $form_provider )
{
    if ( $form_provider != 'fluentforms' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT id, title FROM {$wpdb->prefix}fluentform_forms";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms = wp_list_pluck( $result, 'title', 'id' );
    return $forms;
}

function adfoin_fluentforms_get_slingle_field( $single_field )
{
    $fields = array();
    $field_list = array(
        'input_email',
        'input_text',
        'textarea',
        'select_country',
        'input_number',
        'select',
        'input_radio',
        'input_checkbox',
        'input_url',
        'input_date',
        'input_image',
        'input_file',
        'phone'
    );
    
    if ( 'address' == $single_field->element ) {
        $fields[$single_field->attributes->name . '_address_line_1'] = $single_field->settings->label . ' Address Line 1';
        $fields[$single_field->attributes->name . '_address_line_2'] = $single_field->settings->label . ' Address Line 2';
        $fields[$single_field->attributes->name . '_city'] = $single_field->settings->label . ' City';
        $fields[$single_field->attributes->name . '_state'] = $single_field->settings->label . ' State';
        $fields[$single_field->attributes->name . '_zip'] = $single_field->settings->label . ' Zip';
        $fields[$single_field->attributes->name . '_country'] = $single_field->settings->label . ' Country';
    }
    
    
    if ( 'input_name' == $single_field->element ) {
        $number = intval( str_replace( 'names_', '', $single_field->attributes->name ) );
        $fn = 'first_name';
        $mn = 'middle_name';
        $ln = 'last_name';
        
        if ( $number > 0 ) {
            $fn = strval( $number ) . '_' . $fn;
            $mn = strval( $number ) . '_' . $mn;
            $ln = strval( $number ) . '_' . $ln;
        }
        
        $fields[$fn] = 'First Name';
        $fields[$mn] = 'Middle Name';
        $fields[$ln] = 'Last Name';
    }
    
    if ( in_array( $single_field->element, $field_list ) ) {
        $fields[$single_field->attributes->name] = $single_field->settings->label;
    }
    return $fields;
}

function adfoin_fluentforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'fluentforms' ) {
        return;
    }
    global  $wpdb ;
    $query = "SELECT form_fields FROM {$wpdb->prefix}fluentform_forms WHERE id = {$form_id}";
    $result = $wpdb->get_var( $query );
    $data = json_decode( $result );
    $fields = array();
    foreach ( $data->fields as $single_field ) {
        
        if ( 'container' == $single_field->element ) {
            foreach ( $single_field->columns as $single_column ) {
                foreach ( $single_column->fields as $single_column_field ) {
                    if ( adfoin_fs()->is_not_paying() ) {
                        
                        if ( 'input_name' == $single_column_field->element || 'input_email' == $single_column_field->element ) {
                            $single_field_value = adfoin_fluentforms_get_slingle_field( $single_column_field );
                            $fields = $fields + $single_field_value;
                        }
                    
                    }
                }
            }
            continue;
        }
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'input_name' == $single_field->element || 'input_email' == $single_field->element ) {
                $single_field_value = adfoin_fluentforms_get_slingle_field( $single_field );
                $fields = $fields + $single_field_value;
            }
        
        }
    }
    return $fields;
}

function adfoin_fluentforms_transform_form_fields( $fields )
{
    $data = [];
    foreach ( $fields['fields'] as $field ) {
        if ( !array_key_exists( 'name', $field['attributes'] ) ) {
            continue;
        }
        
        if ( adfoin_fluentforms_has_sub_fields( $field ) ) {
            $data = array_merge( $data, adfoin_fluentforms_get_sub_fields( $field ) );
            continue;
        }
        
        $data[] = [
            'id'    => $field['attributes']['name'],
            'label' => adfoin_fluentforms_get_label( $field['attributes']['name'] ),
        ];
    }
    return $data;
}

function adfoin_fluentforms_has_sub_fields( $field )
{
    return array_key_exists( 'fields', $field );
}

function adfoin_fluentforms_get_sub_fields( $field )
{
    $data = [];
    foreach ( $field['fields'] as $sub_field ) {
        if ( !array_key_exists( 'name', $sub_field['attributes'] ) ) {
            continue;
        }
        $data[] = [
            'id'    => $sub_field['attributes']['name'],
            'label' => adfoin_fluentforms_get_label( $sub_field['attributes']['name'] ),
        ];
    }
    return $data;
}

function adfoin_fluentforms_get_label( $label )
{
    return ucwords( str_replace( [ '-', '_' ], [ ' ', ' ' ], $label ) );
}

function adfoin_fluentforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != "fluentforms" ) {
        return;
    }
    $form = wpFluent()->table( 'fluentform_forms' )->select( 'title' )->where( 'id', $form_id )->first();
    return $form->title;
}

// add_action( 'fluentform_partial_submission_step_completed', 'adfoin_fluentforms_partial_submission', 99, 4 );
// function adfoin_fluentforms_partial_submission( $step, $data, $exist_id, $form_id ) {
//     $data['form_id']            = $form_id;
//     $data['submission_type']    = 'partial';
//     adfoin_fluentforms_submission( $data );
// }
add_action(
    "fluentform_before_insert_submission",
    "adfoin_fluentforms_submission",
    99,
    1
);
function adfoin_fluentforms_submission( $data )
{
    $form_id = $data['form_id'];
    global  $wpdb, $post ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'fluentforms' AND form_id = %s", $form_id ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    if ( isset( $data['submission_type'] ) && 'partial' == $data['submission_type'] ) {
        $posted_data = $data;
    }
    if ( isset( $data['response'] ) ) {
        $posted_data = json_decode( $data['response'], true );
    }
    $all_data = array();
    foreach ( $posted_data as $key => $value ) {
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( strpos( $key, 'names' ) !== false ) {
                $number = intval( str_replace( 'names_', '', $key ) );
                
                if ( $number > 0 ) {
                    if ( is_array( $value ) ) {
                        foreach ( $value as $name_part_key => $name_part_value ) {
                            $all_data[strval( $number ) . '_' . $name_part_key] = $name_part_value;
                        }
                    }
                } else {
                    $all_data = $all_data + $value;
                }
                
                $all_data[$key] = $value;
            }
            
            if ( strpos( $key, 'email' ) !== false ) {
                $all_data[$key] = $value;
            }
        }
    
    }
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $special_tag_values ) ) {
        $all_data = array_merge( $all_data, $special_tag_values );
    }
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];
        
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                'record'      => $record,
                'posted_data' => $all_data,
            ),
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $all_data );
        }
    
    }
    return;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_fluentforms_trigger_fields' );
}
function adfoin_fluentforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'fluentforms'" is="fluentforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_fluentforms_trigger_template" );
function adfoin_fluentforms_trigger_template()
{
    ?>
        <script type="text/template" id="fluentforms-template">
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
