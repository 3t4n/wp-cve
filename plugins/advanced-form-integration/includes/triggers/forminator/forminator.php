<?php

function adfoin_forminator_get_forms( $form_provider )
{
    if ( $form_provider != 'forminator' ) {
        return;
    }
    global  $wpdb ;
    $form_data = get_posts( array(
        'post_type'      => 'forminator_forms',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ) );
    $forms = wp_list_pluck( $form_data, "post_title", "ID" );
    return $forms;
}

function adfoin_forminator_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'forminator' ) {
        return;
    }
    if ( !$form_id ) {
        return;
    }
    $fields = array();
    $form_data = get_post_meta( $form_id );
    $data = maybe_unserialize( $form_data["forminator_form_meta"][0] );
    foreach ( $data['fields'] as $field ) {
        if ( 'html' == $field['type'] ) {
            continue;
        }
        
        if ( adfoin_fs()->is_not_paying() ) {
            
            if ( 'name' == $field['type'] && 'true' == $field['multiple_name'] ) {
                $fields[$field['id'] . '-prefix'] = __( 'Prefix', 'advanced-form-integration' );
                $fields[$field['id'] . '-first-name'] = __( 'First Name', 'advanced-form-integration' );
                $fields[$field['id'] . '-middle-name'] = __( 'Middle Name', 'advanced-form-integration' );
                $fields[$field['id'] . '-last-name'] = __( 'Last Name', 'advanced-form-integration' );
                $fields[$field['id']] = __( 'Name', 'advanced-form-integration' );
            }
            
            if ( 'name' == $field['type'] && 'false' == $field['multiple_name'] ) {
                $fields[$field['id']] = ( isset( $field['field_label'] ) ? $field['field_label'] : $field['id'] );
            }
            if ( 'email' == $field['type'] ) {
                $fields[$field['id']] = ( isset( $field['field_label'] ) ? $field['field_label'] : $field['id'] );
            }
        }
    
    }
    $special_tags = adfoin_get_special_tags();
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

/*
 * Get Form name by form id
 */
function adfoin_forminator_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != "forminator" ) {
        return;
    }
    $form = get_post( $form_id );
    $form_name = $form->post_title;
    return $form_name;
}

add_action(
    'forminator_custom_form_submit_before_set_fields',
    'adfoin_forminator_submission',
    999,
    3
);
function adfoin_forminator_submission( $entry, $form_id, $field_data_array )
{
    global  $wpdb, $post ;
    if ( !$post ) {
        if ( wp_get_referer() ) {
            $post = get_post( url_to_postid( wp_get_referer() ) );
        }
    }
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'forminator' AND form_id = %s", $form_id ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    foreach ( $_POST as $key => $value ) {
        
        if ( adfoin_fs()->is_not_paying() ) {
            if ( strpos( $key, 'name' ) !== false ) {
                $posted_data[$key] = adfoin_sanitize_text_or_array_field( $value );
            }
            if ( strpos( $key, 'email' ) !== false ) {
                $posted_data[$key] = adfoin_sanitize_text_or_array_field( $value );
            }
        }
    
    }
    $posted_data["submission_date"] = date( "Y-m-d H:i:s" );
    $posted_data["user_ip"] = adfoin_get_user_ip();
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    $job_queue = get_option( "adfoin_general_settings_job_queue" );
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
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_forminator_trigger_fields' );
}
function adfoin_forminator_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'forminator'" is="forminator" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_forminator_trigger_template" );
function adfoin_forminator_trigger_template()
{
    ?>
        <script type="text/template" id="forminator-template">
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
