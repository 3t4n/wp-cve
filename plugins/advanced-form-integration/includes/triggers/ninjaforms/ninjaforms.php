<?php

function adfoin_ninjaforms_get_forms( $form_provider )
{
    if ( $form_provider != 'ninjaforms' ) {
        return;
    }
    $data = Ninja_Forms()->form()->get_forms();
    $forms = array();
    foreach ( $data as $single ) {
        $forms[$single->get_id()] = $single->get_setting( "title" );
    }
    return $forms;
}

function adfoin_ninjaforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'ninjaforms' ) {
        return;
    }
    $data = Ninja_Forms()->form( $form_id )->get_fields();
    $fields = array();
    foreach ( $data as $single ) {
        $type = $single->get_settings( 'type' );
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'firstname' == $type || 'lastname' == $type || 'email' == $type ) {
                $fields[$single->get_id()] = $single->get_setting( "label" );
            }
        }
    }
    $special_tags = adfoin_get_special_tags();
    $fields["form_id"] = __( "Form ID", "advanced-form-integration" );
    if ( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }
    return $fields;
}

function adfoin_ninjaforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != "ninjaforms" ) {
        return;
    }
    $form = Ninja_Forms()->form( $form_id )->get();
    $form_name = $form->get_setting( "title" );
    return $form_name;
}

add_action( 'ninja_forms_after_submission', 'adfoin_ninjaforms_after_submission' );
function adfoin_ninjaforms_after_submission( $form_data )
{
    global  $wpdb, $post ;
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'ninjaforms' AND form_id = %s", $form_data['form_id'] ), ARRAY_A );
    if ( empty($saved_records) ) {
        return;
    }
    $posted_data = array();
    if ( isset( $form_data['fields'] ) && is_array( $form_data['fields'] ) ) {
        foreach ( $form_data['fields'] as $field ) {
            if ( isset( $field['id'] ) && isset( $field['value'] ) ) {
                if ( adfoin_fs()->is_not_paying() ) {
                    if ( 'firstname' == $field['type'] || 'lastname' == $field['type'] || 'email' == $field['type'] ) {
                        $posted_data[$field['id']] = $field['value'];
                    }
                }
            }
        }
    }
    
    if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        $post_id = url_to_postid( wp_get_referer() );
        if ( $post_id ) {
            $post = get_post( $post_id, 'OBJECT' );
        }
    }
    
    $special_tag_values = adfoin_get_special_tags_values( $post );
    if ( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    $posted_data["submission_date"] = date( "Y-m-d H:i:s" );
    $posted_data["form_id"] = $form_data["form_id"];
    $posted_data["submission_id"] = adfoin_ninjaforms_get_submission_id( $form_data["actions"]["save"]["sub_id"] );
    $posted_data["user_ip"] = adfoin_get_user_ip();
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
}

function adfoin_ninjaforms_get_submission_id( $post_id )
{
    $submission_id = 0;
    if ( $post_id ) {
        $submission_id = get_post_meta( $post_id, '_seq_num', true );
    }
    return $submission_id;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_ninjaforms_trigger_fields' );
}
function adfoin_ninjaforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'ninjaforms'" is="ninjaforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_ninjaforms_trigger_template" );
function adfoin_ninjaforms_trigger_template()
{
    ?>
        <script type="text/template" id="ninjaforms-template">
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
