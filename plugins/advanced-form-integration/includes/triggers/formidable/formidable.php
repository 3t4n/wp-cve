<?php

function adfoin_formidable_get_forms( $form_provider )
{
    if ( $form_provider != 'formidable' ) {
        return;
    }
    $raw_forms = FrmForm::get_published_forms();
    $forms = wp_list_pluck( $raw_forms, 'name', 'id' );
    return $forms;
}

function adfoin_formidable_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'formidable' ) {
        return;
    }
    $form_fields = FrmField::get_all_for_form( $form_id );
    $fields = array();
    foreach ( $form_fields as $single ) {
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name' == $single->type ) {
                $fields[$single->id . '_first'] = __( 'First Name', 'advanced-form-integration' );
                $fields[$single->id . '_middle'] = __( 'Middle Name', 'advanced-form-integration' );
                $fields[$single->id . '_last'] = __( 'Last Name', 'advanced-form-integration' );
            }
            
            if ( 'email' == $single->type ) {
                $fields[$single->id] = $single->name;
            }
        }
    
    }
    $fields['form_id'] = __( 'Form ID', 'advanced-form-integration' );
    $fields['form_key'] = __( 'Form Key', 'advanced-form-integration' );
    $fields['entry_id'] = __( 'Entry ID', 'advanced-form-integration' );
    $fields['entry_key'] = __( 'Entry Key', 'advanced-form-integration' );
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

add_action(
    'frm_after_create_entry',
    'adfoin_formidable_after_entry_processed',
    999,
    1
);
function adfoin_formidable_after_entry_processed( $entry_id )
{
    $entry = FrmEntry::getOne( $entry_id, true );
    global  $wpdb, $post ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'formidable' AND form_id = %s", $entry->form_id ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $form_fields = FrmField::get_all_for_form( $entry->form_id );
    $form_field_types = array();
    $posted_data = array();
    foreach ( $form_fields as $single ) {
        $form_field_types[$single->id] = $single->type;
    }
    foreach ( $entry->metas as $meta_key => $meta_value ) {
        $field_type = ( isset( $form_field_types[$meta_key] ) ? $form_field_types[$meta_key] : '' );
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name' == $field_type ) {
                $posted_data[$meta_key . '_first'] = ( isset( $meta_value['first'] ) ? $meta_value['first'] : '' );
                $posted_data[$meta_key . '_middle'] = ( isset( $meta_value['middle'] ) ? $meta_value['last'] : '' );
                $posted_data[$meta_key . '_last'] = ( isset( $meta_value['last'] ) ? $meta_value['last'] : '' );
            }
            
            if ( 'email' == $field_type ) {
                $posted_data[$meta_key] = $meta_value;
            }
        }
    
    }
    $posted_data['form_id'] = $entry->form_id;
    $posted_data['form_key'] = $entry->form_key;
    $posted_data['entry_id'] = $entry->id;
    $posted_data['entry_key'] = $entry->item_key;
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

function adfoin_formidable_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'formidable' ) {
        return;
    }
    global  $wpdb ;
    $form_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}frm_forms WHERE id = " . $form_id );
    return $form_name;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_formidable_trigger_fields' );
}
function adfoin_formidable_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'formidable'" is="formidable" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_formidable_trigger_template" );
function adfoin_formidable_trigger_template()
{
    ?>
        <script type="text/template" id="formidable-template">
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
