<?php

add_filter( 'adfoin_action_providers', 'adfoin_webinarjam_actions', 10, 1 );

function adfoin_webinarjam_actions( $actions ) {

    $actions['webinarjam'] = array(
        'title' => __( 'WebinarJam', 'advanced-form-integration' ),
        'tasks' => array(
            'register_webinar' => __( 'Register to webinar', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_webinarjam_settings_tab', 10, 1 );

function adfoin_webinarjam_settings_tab( $providers ) {
    $providers['webinarjam'] = __( 'WebinarJam', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_webinarjam_settings_view', 10, 1 );

function adfoin_webinarjam_settings_view( $current_tab ) {
    if( $current_tab != 'webinarjam' ) {
        return;
    }

    $nonce     = wp_create_nonce( "adfoin_webinarjam_settings" );
    $api_token = get_option( 'adfoin_webinarjam_api_token' ) ? get_option( 'adfoin_webinarjam_api_token' ) : "";
    ?>

    <form name="webinarjam_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_webinarjam_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_webinarjam_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p>
                        Go to <b>My webinars</b>, click <b>ADVANCED</b> menu of any listed webinar, go to <b>API custom integration</b> and copy API Key
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_webinarjam_api_token', 'adfoin_save_webinarjam_api_token', 10, 0 );

function adfoin_save_webinarjam_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_webinarjam_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token   = sanitize_text_field( $_POST["adfoin_webinarjam_api_token"] );

    // Save tokens
    update_option( "adfoin_webinarjam_api_token", $api_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=webinarjam" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_webinarjam_js_fields', 10, 1 );

function adfoin_webinarjam_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_webinarjam_action_fields' );

function adfoin_webinarjam_action_fields() {
    ?>

    <script type="text/template" id="webinarjam-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'register_webinar'">
                <th scope="row">
                    <?php esc_attr_e( 'Registrant Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>
            <tr class="alternate" v-if="action.task == 'register_webinar'">
                <td>
                    <label for="tablecell">
                        <?php esc_attr_e( 'Instructions', 'advanced-form-integration' ); ?>
                    </label>
                </td>

                <td>
                    <p><?php _e('To learn more details on the fields go to the link: ', 'advanced-form-integration' );?><a target="_blank" rel="noopener noreferrer" href="https://documentation.webinarjam.com/register-a-person-to-a-specific-webinar/">https://documentation.webinarjam.com/register-a-person-to-a-specific-webinar/</a></p>
                </td>
            </tr>
            <tr class="alternate" v-if="action.task == 'register_webinar'">
                <td>
                    <label for="tablecell">
                        <?php esc_attr_e( 'Webinar', 'advanced-form-integration' ); ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[webinarId]" v-model="fielddata.webinarId" required="true" @change="getSchedule">
                        <option value=""><?php _e( 'Select...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in fielddata.webinars" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': webinarLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    <p class="description"><?php _e( 'Required', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'register_webinar'">
                <td>
                    <label for="tablecell">
                        <?php esc_attr_e( 'Schedule', 'advanced-form-integration' ); ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[scheduleId]" v-model="fielddata.scheduleId" required="true">
                        <option value=""><?php _e( 'Select...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in fielddata.schedules" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Required', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>

    <?php
}

add_action( 'wp_ajax_adfoin_get_webinarjam_webinars', 'adfoin_get_webinarjam_webinars', 10, 0 );
/*
 * Get Drip accounts
 */
function adfoin_get_webinarjam_webinars() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = get_option( "adfoin_webinarjam_api_token" );

    if( ! $api_token ) {
        return array();
    }

    $url    = "https://api.webinarjam.com/webinarjam/webinars";

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'api_key' => $api_token
        )
    );

    $accounts = wp_remote_request( $url, $args );

    if( !is_wp_error( $accounts ) ) {
        $body  = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body->webinars, 'name', 'webinar_id' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_adfoin_get_webinarjam_schedules', 'adfoin_get_webinarjam_schedules', 10, 0 );
/*
 * Get Drip list
 */
function adfoin_get_webinarjam_schedules() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = get_option( "adfoin_webinarjam_api_token" );

    if( ! $api_token ) {
        wp_send_json_error();
    }

    $webinar_id = $_POST["webinarId"] ? sanitize_text_field( $_POST["webinarId"] ) : "";

    if( ! $webinar_id ) {
        wp_send_json_error();
    }

    $url    = "https://api.webinarjam.com/webinarjam/webinar";

    $args = array(
        'method'  => 'POST',
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'api_key'    => $api_token,
            'webinar_id' => $webinar_id
        )
    );

    $webinars = wp_remote_request( $url, $args );

    if( !is_wp_error( $webinars ) ) {
        $body  = json_decode( $webinars["body"] );
        $schedules = wp_list_pluck( $body->webinar->schedules, 'date', 'schedule' );

        wp_send_json_success( $schedules );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function adfoin_webinarjam_save_integration() {
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

add_action( 'adfoin_webinarjam_job_queue', 'adfoin_webinarjam_job_queue', 10, 1 );

function adfoin_webinarjam_job_queue( $data ) {
    adfoin_webinarjam_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Webinarjam API
 */
function adfoin_webinarjam_send_data( $record, $posted_data ) {

    $api_token   = get_option( 'adfoin_webinarjam_api_token' ) ? get_option( 'adfoin_webinarjam_api_token' ) : "";

    if( !$api_token ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data         = $record_data["field_data"];
    $task         = $record["task"];
    $webinar_id   = empty( $data["webinarId"] ) ? "" : $data["webinarId"];
    $schedule_id  = empty( $data["scheduleId"] ) ? "" : $data["scheduleId"];
    $email        = empty( $data["email"] ) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data );
    $first_name   = empty( $data["firstName"] ) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data );
    $last_name    = empty( $data["lastName"] ) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data );
    $ip_address   = empty( $data["ipAddress"] ) ? "" : adfoin_get_parsed_values( $data["ipAddress"], $posted_data );
    $country_code = empty( $data["phoneCountryCode"] ) ? "" : adfoin_get_parsed_values( $data["phoneCountryCode"], $posted_data );
    $phone        = empty( $data["phone"] ) ? "" : adfoin_get_parsed_values( $data["phone"], $posted_data );
    $timezone     = empty( $data["timezone"] ) ? "" : adfoin_get_parsed_values( $data["timezone"], $posted_data );
    $date         = empty( $data["date"] ) ? "" : adfoin_get_parsed_values( $data["date"], $posted_data );

    if( $task == "register_webinar" ) {

        $url = "https://api.webinarjam.com/webinarjam/register";

        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'api_key'            => $api_token,
                'webinar_id'         => $webinar_id,
                'schedule'           => $schedule_id,
                'email'              => $email,
                'first_name'         => $first_name,
                'last_name'          => $last_name,
                'phone_country_code' => $country_code,
                'phone'              => $phone,
                'ip_address'         => $ip_address,
                'timezone'           => $timezone,
                'date'               => $date,
            )
        );

        $response = wp_remote_request( $url, $args );

        adfoin_add_to_log( $response, $url, $args, $record );
    }

    return;
}