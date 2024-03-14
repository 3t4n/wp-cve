<?php

add_filter( 'adfoin_action_providers', 'adfoin_twilio_actions', 10, 1 );

function adfoin_twilio_actions( $actions ) {

    $actions['twilio'] = array(
        'title' => __( 'Twilio', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Send SMS', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_twilio_settings_tab', 10, 1 );

function adfoin_twilio_settings_tab( $providers ) {
    $providers['twilio'] = __( 'Twilio', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_twilio_settings_view', 10, 1 );

function adfoin_twilio_settings_view( $current_tab ) {
    if( $current_tab != 'twilio' ) {
        return;
    }

    $nonce       = wp_create_nonce( "adfoin_twilio_settings" );
    $account_sid = get_option( 'adfoin_twilio_account_sid' ) ? get_option( 'adfoin_twilio_account_sid' ) : "";
    $auth_token  = get_option( 'adfoin_twilio_auth_token' ) ? get_option( 'adfoin_twilio_auth_token' ) : "";
    ?>

    <form name="twilio_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_twilio_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'ACCOUNT SID', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_twilio_account_sid"
                           value="<?php echo esc_attr( $account_sid ); ?>" placeholder="<?php _e( 'Please enter ACCOUNT SID', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'AUTH TOKEN', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_twilio_auth_token"
                           value="<?php echo esc_attr( $auth_token ); ?>" placeholder="<?php _e( 'Please enter AUTH TOKEN', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_twilio_api_key', 'adfoin_save_twilio_api_key', 10, 0 );

function adfoin_save_twilio_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_twilio_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $account_sid = sanitize_text_field( $_POST["adfoin_twilio_account_sid"] );
    $auth_token  = sanitize_text_field( $_POST["adfoin_twilio_auth_token"] );

    // Save tokens
    update_option( "adfoin_twilio_account_sid", $account_sid );
    update_option( "adfoin_twilio_auth_token", $auth_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=twilio" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_twilio_js_fields', 10, 1 );

function adfoin_twilio_js_fields( $field_data ) { }

add_action( 'adfoin_action_fields', 'adfoin_twilio_action_fields' );

function adfoin_twilio_action_fields() {
    ?>
    <script type="text/template" id="twilio-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'From', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[from]" v-model="fielddata.from" required="required">
                        <option value=""> <?php _e( 'Select Number...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

<!--            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">-->
<!--                <td scope="row-title">-->
<!--                    <label for="tablecell">-->
<!--                        --><?php //esc_attr_e( 'To', 'advanced-form-integration' ); ?>
<!--                    </label>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input class="regular-text" type="text" v-model="fielddata.to" required="required">-->
<!--                </td>-->
<!--            </tr>-->
<!---->
<!--            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">-->
<!--                <td scope="row-title">-->
<!--                    <label for="tablecell">-->
<!--                        --><?php //esc_attr_e( 'Body', 'advanced-form-integration' ); ?>
<!--                    </label>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <textarea type="text" v-model="fielddata.body"></textarea>-->
<!--                </td>-->
<!--            </tr>-->

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>


        </table>
    </script>


    <?php
}

add_action( 'wp_ajax_adfoin_get_twilio_list', 'adfoin_get_twilio_list', 10, 0 );

/*
 * Get Mailchimp subscriber lists
 */
function adfoin_get_twilio_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $account_sid = get_option( "adfoin_twilio_account_sid" );
    $auth_token  = get_option( "adfoin_twilio_auth_token" );

    if( !$account_sid || !$auth_token ) {
        return array();
    }

    $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/IncomingPhoneNumbers.json";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $account_sid . ':' . $auth_token )
        )
    );
    $data  = wp_remote_get( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );

        $lists = wp_list_pluck( $body->incoming_phone_numbers, 'phone_number', 'phone_number' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function adfoin_twilio_save_integration() {
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

add_action( 'adfoin_twilio_job_queue', 'adfoin_twilio_job_queue', 10, 1 );

function adfoin_twilio_job_queue( $data ) {
    adfoin_twilio_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Twilio API
 */
function adfoin_twilio_send_data( $record, $posted_data ) {

    $account_sid = get_option( 'adfoin_twilio_account_sid' ) ? get_option( 'adfoin_twilio_account_sid' ) : "";
    $auth_token  = get_option( 'adfoin_twilio_auth_token' ) ? get_option( 'adfoin_twilio_auth_token' ) : "";

    if(!$account_sid || !$auth_token ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data["field_data"];
//    $list_id = $data["listId"];
    $task    = $record["task"];

    if( $task == "subscribe" ) {
        $from = empty( $data["from"] ) ? "" : adfoin_get_parsed_values( $data["from"], $posted_data );
        $to   = empty( $data["to"] ) ? "" : adfoin_get_parsed_values( $data["to"], $posted_data );
        $body = empty( $data["body"] ) ? "" : adfoin_get_parsed_values( $data["body"], $posted_data );

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/Messages.json";

        $sms_data = array(
            'From' => $from,
            'To'   => $to,
            'Body' => $body
        );

        $args = array(

            'headers' => array(
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $account_sid . ':' . $auth_token )
            ),
            'body' => $sms_data
        );

        $return = wp_remote_post( $url, $args );

        adfoin_add_to_log( $return, $url, $args, $record );

        return;
    }
}