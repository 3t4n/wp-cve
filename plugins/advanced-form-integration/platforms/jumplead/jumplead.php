<?php

add_filter( 'adfoin_action_providers', 'adfoin_jumplead_actions', 10, 1 );

function adfoin_jumplead_actions( $actions ) {

    $actions['jumplead'] = array(
        'title' => __( 'Jumplead', 'advanced-form-integration' ),
        'tasks' => array(
            'add_contact' => __( 'Add New Contact', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_jumplead_settings_tab', 10, 1 );

function adfoin_jumplead_settings_tab( $providers ) {
    $providers['jumplead'] = __( 'Jumplead', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_jumplead_settings_view', 10, 1 );

function adfoin_jumplead_settings_view( $current_tab ) {
    if( $current_tab != 'jumplead' ) {
        return;
    }

    $nonce   = wp_create_nonce( "adfoin_jumplead_settings" );
    $api_key = get_option( 'adfoin_jumplead_api_key' ) ? get_option( 'adfoin_jumplead_api_key' ) : "";
    ?>

    <form name="jumplead_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_jumplead_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'Personal Access Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_jumplead_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter Personal Access Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><a
                            href="https://app.jumplead.com/settings/access-tokens"
                            target="_blank" rel="noopener noreferrer"><?php _e( 'Click here to get Personal Access Token', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_jumplead_api_key', 'adfoin_save_jumplead_api_key', 10, 0 );

function adfoin_save_jumplead_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_jumplead_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST["adfoin_jumplead_api_key"] );

    // Save tokens
    update_option( "adfoin_jumplead_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=jumplead" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_jumplead_js_fields', 10, 1 );

function adfoin_jumplead_js_fields( $field_data ) { }

add_action( 'adfoin_action_fields', 'adfoin_jumplead_action_fields' );

function adfoin_jumplead_action_fields() {
    ?>
    <script type="text/template" id="jumplead-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>
            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>


    <?php
}

add_action( 'wp_ajax_adfoin_get_jumplead_list', 'adfoin_get_jumplead_list', 10, 0 );

/*
 * Get jumplead subscriber lists
 */
function adfoin_get_jumplead_get_client_id( $api_key ) {

    $url = "https://api.jumplead.com/v2/clients";

    $args = array(
        'headers' => array(
            'Content-Type'    => 'application/json',
            'X-Jumplead-Auth' => $api_key
        )
    );

    $data = wp_remote_request( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body      = json_decode( $data["body"] );
        $client_id = $body->meta->session->clientId;

        if( $client_id ) {
            return $client_id;
        } else {
            return false;
        }
    }
}

/*
 * Saves connection mapping
 */
function adfoin_jumplead_save_integration() {
    $params = array();
    parse_str( adfoin_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
    $task              = isset( $action_data["task"] ) ? $action_data["task"] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";

    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data
    );

    global $wpdb;

    $integration_table = $wpdb->prefix . 'adfoin_integration';

    if ( $type == 'new_integration' ) {

        $result = $wpdb->insert(
            $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'action_provider' => $action_provider,
                'task'            => $task,
                'data'            => json_encode( $all_data, true ),
                'status'          => 1
            )
        );
    }

    if ( $type == 'update_integration' ) {

        $id = esc_sql( trim( $params['edit_id'] ) );

        if ( $type != 'update_integration' &&  !empty( $id ) ) {
            return;
        }

        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array(
                'id' => $id
            )
        );
    }

    if ( $result ) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

add_action( 'adfoin_jumplead_job_queue', 'adfoin_jumplead_job_queue', 10, 1 );

function adfoin_jumplead_job_queue( $data ) {
    adfoin_jumplead_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to jumplead API
 */
function adfoin_jumplead_send_data( $record, $posted_data ) {

    $api_key   = get_option( 'adfoin_jumplead_api_key' ) ? get_option( 'adfoin_jumplead_api_key' ) : "";

    if(!$api_key ) {
        return;
    }

    $client_id = adfoin_get_jumplead_get_client_id( $api_key );
    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data["field_data"];
    $task = $record["task"];

    if( $task == "add_contact" ) {
        $email      = empty( $data["email"] ) ? "" : adfoin_get_parsed_values($data["email"], $posted_data);
        $first_name = empty( $data["firstName"] ) ? "" : adfoin_get_parsed_values($data["firstName"], $posted_data);
        $last_name  = empty( $data["lastName"] ) ? "" : adfoin_get_parsed_values($data["lastName"], $posted_data);

        $subscriber_data = array(
            "data" => array(
                "type" => "contacts",
                "attributes" => array(
                    "firstName" => $first_name,
                    "lastName"  => $last_name,
                    "email"     => $email
                ),
                "relationships" => array(
                    "client" => array(
                        "meta" => array(
                            "relation" => "primary",
                            "readOnly" => false
                        ),
                        "data" => array(
                            "type" => "clients",
                            "id" => "{$client_id}"
                        )
                    )
                )
            )
        );

        $sub_url = "https://api.jumplead.com/v2/contacts";

        $sub_args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Jumplead-Auth' => $api_key
            ),
            'body' => json_encode( $subscriber_data )
        );

        $return = wp_remote_post( $sub_url, $sub_args );

        adfoin_add_to_log( $return, $sub_url, $sub_args, $record );

        return;
    }
}