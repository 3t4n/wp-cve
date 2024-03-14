<?php

function adfoin_everestforms_get_forms( $form_provider )
{
    if ( $form_provider != 'everestforms' ) {
        return;
    }
    global  $wpdb ;
    $form_data = get_posts( array(
        'post_type'           => 'everest_form',
        'ignore_sticky_posts' => true,
        'nopaging'            => true,
        'post_status'         => 'publish',
        'posts_per_page'      => -1,
    ) );
    $forms = wp_list_pluck( $form_data, 'post_title', 'ID' );
    return $forms;
}

function adfoin_everestforms_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'everestforms' ) {
        return;
    }
    if ( !$form_id ) {
        return;
    }
    $form = get_post( $form_id );
    $form_data = json_decode( $form->post_content, true );
    $field_data = array_values( $form_data['form_fields'] );
    $fields = array();
    foreach ( $field_data as $single_field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'first-name' == $single_field['type'] || 'last-name' == $single_field['type'] || 'email' == $single_field['type'] ) {
                $fields[$single_field['id']] = $single_field['label'];
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
function adfoin_everestforms_get_form_name( $form_provider, $form_id )
{
    if ( $form_provider != 'everestforms' ) {
        return;
    }
    $form = get_post( $form_id );
    $form_name = $form->post_title;
    return $form_name;
}

add_action(
    'everest_forms_process_complete',
    'adfoin_everestforms_submission',
    30,
    4
);
function adfoin_everestforms_submission(
    $form_fields,
    $entry,
    $form_data,
    $entry_id
)
{
    global  $wpdb, $post ;
    $form_id = $form_data['id'];
    $saved_records = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND form_provider = 'everestforms' AND form_id = %s", $form_id ), ARRAY_A );
    $posted_data = array();
    foreach ( $form_fields as $single_field ) {
        if ( adfoin_fs()->is_not_paying() ) {
            if ( 'first-name' == $single_field['type'] || 'last-name' == $single_field['type'] || 'email' == $single_field['type'] ) {
                $posted_data[$single_field['id']] = $single_field['value'];
            }
        }
    }
    $posted_data['submission_date'] = date( 'Y-m-d H:i:s' );
    $posted_data['user_ip'] = adfoin_get_user_ip();
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
    return;
}

if ( adfoin_fs()->is_not_paying() ) {
    add_action( 'adfoin_trigger_extra_fields', 'adfoin_everestforms_trigger_fields' );
}
function adfoin_everestforms_trigger_fields()
{
    ?>
    <tr v-if="trigger.formProviderId == 'everestforms'" is="everestforms" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData"></tr>
    <?php 
}

add_action( "adfoin_trigger_templates", "adfoin_everestforms_trigger_template" );
function adfoin_everestforms_trigger_template()
{
    ?>
        <script type="text/template" id="everestforms-template">
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
