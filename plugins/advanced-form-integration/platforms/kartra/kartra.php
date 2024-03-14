<?php

add_filter( 'adfoin_action_providers', 'adfoin_kartra_actions', 10, 1 );

function adfoin_kartra_actions( $actions ) {

    $actions['kartra'] = array(
        'title' => __( 'Kartra', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Add Lead To List', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_kartra_settings_tab', 10, 1 );

function adfoin_kartra_settings_tab( $providers ) {
    $providers['kartra'] = __( 'Kartra', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_kartra_settings_view', 10, 1 );

function adfoin_kartra_settings_view( $current_tab ) {
    if( $current_tab != 'kartra' ) {
        return;
    }

    $nonce        = wp_create_nonce( "adfoin_kartra_settings" );
    $api_key      = get_option( 'adfoin_kartra_api_key' ) ? get_option( 'adfoin_kartra_api_key' ) : "";
    $api_password = get_option( 'adfoin_kartra_api_password' ) ? get_option( 'adfoin_kartra_api_password' ) : "";
    ?>

    <form name="kartra_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_kartra_save_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'Kartra API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_kartra_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><a
                            href="https://app.kartra.com/integrations/api/key"
                            target="_blank" rel="noopener noreferrer"><?php _e( 'Click here to get API Key and API Password', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'Kartra API Password', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_kartra_api_password"
                           value="<?php echo esc_attr( $api_password ); ?>" placeholder="<?php _e( 'Please enter API Password', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_kartra_save_api_token', 'adfoin_save_kartra_api_token', 10, 0 );

function adfoin_save_kartra_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_kartra_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key      = sanitize_text_field( $_POST["adfoin_kartra_api_key"] );
    $api_password = sanitize_text_field( $_POST["adfoin_kartra_api_password"] );

    // Save tokens
    update_option( "adfoin_kartra_api_key", $api_key );
    update_option( "adfoin_kartra_api_password", $api_password );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=kartra" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_kartra_js_fields', 10, 1 );

function adfoin_kartra_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_kartra_action_fields' );

function adfoin_kartra_action_fields() {
    ?>
    <script type="text/template" id="kartra-action-template">
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
                        <?php esc_attr_e( 'Kartra List', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php _e( 'Select List...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

add_action( 'wp_ajax_adfoin_get_kartra_list', 'adfoin_get_kartra_list', 10, 0 );
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_kartra_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key      = get_option( "adfoin_kartra_api_key" );
    $api_password = get_option( "adfoin_kartra_api_password" );
    $app_id       = "zrbNVFqSAJLw";

    if( !$api_key || !$api_password ) {
        return array();
    }

    $body = array(
        'actions' => array(
            array(
                'cmd' => 'retrieve_account_lists'
            )
        )
    );

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode( $body )
    );

    $url  = "https://app.kartra.com/api?app_id={$app_id}&api_key={$api_key}&api_password={$api_password}";
    $data = wp_remote_post( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body  = json_decode( $data["body"] );
    $lists = array();
    if( is_array( $body->account_lists ) ) {
        $lists = array_combine( $body->account_lists, $body->account_lists );
    }

    wp_send_json_success( $lists );
}

/*
 * Saves connection mapping
 */
function adfoin_kartra_save_integration() {
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

add_action( 'adfoin_kartra_job_queue', 'adfoin_kartra_job_queue', 10, 1 );

function adfoin_kartra_job_queue( $data ) {
    adfoin_kartra_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Kartra API
 */
function adfoin_kartra_send_data( $record, $posted_data ) {

    $api_key      = get_option( 'adfoin_kartra_api_key' ) ? get_option( 'adfoin_kartra_api_key' ) : "";
    $api_password = get_option( 'adfoin_kartra_api_password' ) ? get_option( 'adfoin_kartra_api_password' ) : "";
    $app_id       = "zrbNVFqSAJLw";

    if(!$api_key || !$api_password ) {
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

    $data    = $record_data["field_data"];
    $list_id = $data["listId"];
    $task    = $record["task"];

    if( $task == "subscribe" ) {
        $email         = empty( $data["email"] ) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data );
        $first_name    = empty( $data["firstName"] ) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data );
        $middle_name   = empty( $data["middleName"] ) ? "" : adfoin_get_parsed_values( $data["middleName"], $posted_data );
        $last_name     = empty( $data["lastName"] ) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data );
        $last_name2    = empty( $data["lastName2"] ) ? "" : adfoin_get_parsed_values( $data["lastName2"], $posted_data );
        $p_c_code      = empty( $data["phoneCountryCode"] ) ? "" : adfoin_get_parsed_values( $data["phoneCountryCode"], $posted_data );
        $phone         = empty( $data["phone"] ) ? "" : adfoin_get_parsed_values( $data["phone"], $posted_data );
        $ip            = empty( $data["ip"] ) ? "" : adfoin_get_parsed_values( $data["ip"], $posted_data );
        $address       = empty( $data["address1"] ) ? "" : adfoin_get_parsed_values( $data["address1"], $posted_data );
        $zip           = empty( $data["zip"] ) ? "" : adfoin_get_parsed_values( $data["zip"], $posted_data );
        $city          = empty( $data["city"] ) ? "" : adfoin_get_parsed_values( $data["city"], $posted_data );
        $state         = empty( $data["state"] ) ? "" : adfoin_get_parsed_values( $data["state"], $posted_data );
        $country       = empty( $data["country"] ) ? "" : adfoin_get_parsed_values( $data["country"], $posted_data );
        $company       = empty( $data["company"] ) ? "" : adfoin_get_parsed_values( $data["company"], $posted_data );
        $website       = empty( $data["website"] ) ? "" : adfoin_get_parsed_values( $data["website"], $posted_data );
        $facebook      = empty( $data["facebook"] ) ? "" : adfoin_get_parsed_values( $data["facebook"], $posted_data );
        $twitter       = empty( $data["twitter"] ) ? "" : adfoin_get_parsed_values( $data["twitter"], $posted_data );
        $linkedin      = empty( $data["linkedin"] ) ? "" : adfoin_get_parsed_values( $data["linkedin"], $posted_data );

        $body = array(
            'lead' => array(
                array(
                    'email'              => $email,
                    'first_name'         => $first_name,
                    'middle_name'        => $middle_name,
                    'last_name'          => $last_name,
                    'last_name2'         => $last_name2,
                    'phone_country_code' => $p_c_code,
                    'phone'              => $phone,
                    'ip'                 => $ip,
                    'address'            => $address,
                    'zip'                => $zip,
                    'city'               => $city,
                    'state'              => $state,
                    'country'            => $country,
                    'company'            => $company,
                    'website'            => $website,
                    'facebook'           => $facebook,
                    'twitter'            => $twitter,
                    'linkedin'           => $linkedin,
                )
            ),
            'actions' => array(
                array(
                    'cmd' => 'create_lead'
                ),
                array(
                    'cmd' => 'subscribe_lead_to_list',
                    'list_name' => $list_id
                )
            )
        );

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $body )
        );

        $url    = "https://app.kartra.com/api?app_id={$app_id}&api_key={$api_key}&api_password={$api_password}";
        $return = wp_remote_post( $url, $args );

        adfoin_add_to_log( $return, $url, $args, $record );
    }

    return;
}