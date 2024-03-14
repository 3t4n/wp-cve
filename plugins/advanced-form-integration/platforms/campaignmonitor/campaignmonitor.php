<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_campaignmonitor_actions',
    10,
    1
);
function adfoin_campaignmonitor_actions( $actions )
{
    $actions['campaignmonitor'] = array(
        'title' => __( 'Campaign Monitor', 'advanced-form-integration' ),
        'tasks' => array(
        'create_subscriber' => __( 'Subscribe to List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_campaignmonitor_settings_tab',
    10,
    1
);
function adfoin_campaignmonitor_settings_tab( $providers )
{
    $providers['campaignmonitor'] = __( 'Campaign Monitor', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_campaignmonitor_settings_view',
    10,
    1
);
function adfoin_campaignmonitor_settings_view( $current_tab )
{
    if ( $current_tab != 'campaignmonitor' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_campaignmonitor_settings" );
    $api_token = ( get_option( 'adfoin_campaignmonitor_api_token' ) ? get_option( 'adfoin_campaignmonitor_api_token' ) : "" );
    ?>

    <form name="campaignmonitor_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_campaignmonitor_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_campaignmonitor_api_token"
                           value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Token', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p>
                        <?php 
    _e( 'Go to Account Settings > API Keys', 'advanced-form-integration' );
    ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php 
    submit_button();
    ?>
    </form>

    <?php 
}

add_action(
    'admin_post_adfoin_save_campaignmonitor_api_token',
    'adfoin_save_campaignmonitor_api_token',
    10,
    0
);
function adfoin_save_campaignmonitor_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_campaignmonitor_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST["adfoin_campaignmonitor_api_token"] );
    // Save tokens
    update_option( "adfoin_campaignmonitor_api_token", $api_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=campaignmonitor" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_campaignmonitor_js_fields',
    10,
    1
);
function adfoin_campaignmonitor_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_campaignmonitor_action_fields' );
function adfoin_campaignmonitor_action_fields()
{
    ?>
    <script type="text/template" id="campaignmonitor-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'create_subscriber'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Subscriber Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>
            <tr class="alternate" v-if="action.task == 'create_subscriber'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Client', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[accountId]" v-model="fielddata.accountId" required="true" @change="getList">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.accounts" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': accountLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'create_subscriber'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="true">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'create_subscriber'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock custom fields consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
        ?></span>
                        </td>
                    </tr>
                    <?php 
    }
    
    ?>
            
        </table>
    </script>

    <?php 
}

add_action(
    'wp_ajax_adfoin_get_campaignmonitor_accounts',
    'adfoin_get_campaignmonitor_accounts',
    10,
    0
);
/*
 * Get Campaign Monitor accounts
 */
function adfoin_get_campaignmonitor_accounts()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = get_option( "adfoin_campaignmonitor_api_token" );
    if ( !$api_token ) {
        return array();
    }
    $url = "https://api.createsend.com/api/v3.2/clients.json";
    $args = array(
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $api_token . ':x' ),
    ),
    );
    $accounts = wp_remote_get( $url, $args );
    
    if ( !is_wp_error( $accounts ) ) {
        $body = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body, 'Name', 'ClientID' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_campaignmonitor_list',
    'adfoin_get_campaignmonitor_list',
    10,
    0
);
/*
 * Get Campaign Monitor accounts
 */
function adfoin_get_campaignmonitor_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = get_option( "adfoin_campaignmonitor_api_token" );
    if ( !$api_token ) {
        wp_send_json_error();
    }
    $client = ( $_POST['accountId'] ? sanitize_text_field( $_POST['accountId'] ) : "" );
    if ( !$client ) {
        wp_send_json_error();
    }
    $url = "https://api.createsend.com/api/v3.2/clients/{$client}/lists.json";
    $args = array(
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ),
    ),
    );
    $accounts = wp_remote_get( $url, $args );
    
    if ( !is_wp_error( $accounts ) ) {
        $body = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body, 'Name', 'ListID' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

/*
 * Saves connection mapping
 */
function adfoin_campaignmonitor_save_integration()
{
    $params = array();
    parse_str( adfoin_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = ( isset( $_POST["triggerData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["triggerData"] ) : array() );
    $action_data = ( isset( $_POST["actionData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["actionData"] ) : array() );
    $field_data = ( isset( $_POST["fieldData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["fieldData"] ) : array() );
    $integration_title = ( isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "" );
    $form_provider_id = ( isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "" );
    $form_id = ( isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "" );
    $form_name = ( isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "" );
    $action_provider = ( isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "" );
    $task = ( isset( $action_data["task"] ) ? $action_data["task"] : "" );
    $type = ( isset( $params["type"] ) ? $params["type"] : "" );
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
    );
    global  $wpdb ;
    $integration_table = $wpdb->prefix . 'adfoin_integration';
    if ( $type == 'new_integration' ) {
        $result = $wpdb->insert( $integration_table, array(
            'title'           => $integration_title,
            'form_provider'   => $form_provider_id,
            'form_id'         => $form_id,
            'form_name'       => $form_name,
            'action_provider' => $action_provider,
            'task'            => $task,
            'data'            => json_encode( $all_data, true ),
            'status'          => 1,
        ) );
    }
    
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' && !empty($id) ) {
            return;
        }
        $result = $wpdb->update( $integration_table, array(
            'title'         => $integration_title,
            'form_provider' => $form_provider_id,
            'form_id'       => $form_id,
            'form_name'     => $form_name,
            'data'          => json_encode( $all_data, true ),
        ), array(
            'id' => $id,
        ) );
    }
    
    
    if ( $result ) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_campaignmonitor_job_queue',
    'adfoin_campaignmonitor_job_queue',
    10,
    1
);
function adfoin_campaignmonitor_job_queue( $data )
{
    adfoin_campaignmonitor_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Campaign Monitor API
 */
function adfoin_campaignmonitor_send_data( $record, $posted_data )
{
    $api_token = ( get_option( 'adfoin_campaignmonitor_api_token' ) ? get_option( 'adfoin_campaignmonitor_api_token' ) : "" );
    if ( !$api_token ) {
        return;
    }
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data["field_data"];
    $task = $record["task"];
    $account = ( empty($data["accountId"]) ? "" : $data["accountId"] );
    $list = ( empty($data["listId"]) ? "" : $data["listId"] );
    $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
    $name = ( empty($data["name"]) ? "" : adfoin_get_parsed_values( $data["name"], $posted_data ) );
    
    if ( $task == "create_subscriber" ) {
        $url = "https://api.createsend.com/api/v3.2/subscribers/{$list}.json";
        $body = array(
            "EmailAddress"   => $email,
            "Name"           => $name,
            "ConsentToTrack" => "Yes",
        );
        $args = array(
            'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ),
        ),
            'body'    => json_encode( $body ),
        );
        $response = wp_remote_post( $url, $args );
        adfoin_add_to_log(
            $response,
            $url,
            $args,
            $record
        );
    }
    
    return;
}
