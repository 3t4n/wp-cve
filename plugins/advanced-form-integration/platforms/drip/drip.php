<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_drip_actions',
    10,
    1
);
function adfoin_drip_actions( $actions )
{
    $actions['drip'] = array(
        'title' => __( 'Drip', 'advanced-form-integration' ),
        'tasks' => array(
        'create_subscriber' => __( 'Create Subscriber', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_drip_settings_tab',
    10,
    1
);
function adfoin_drip_settings_tab( $providers )
{
    $providers['drip'] = __( 'Drip', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_drip_settings_view',
    10,
    1
);
function adfoin_drip_settings_view( $current_tab )
{
    if ( $current_tab != 'drip' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_drip_settings" );
    $api_token = ( get_option( 'adfoin_drip_api_token' ) ? get_option( 'adfoin_drip_api_token' ) : "" );
    ?>

    <form name="drip_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_drip_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Token', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_drip_api_token"
                           value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Token', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p>
                        To find your API token login to your Drip account and go to <a target="_blank" rel="noopener noreferrer" href="https://www.getdrip.com/user/edit">https://www.getdrip.com/user/edit</a>. It will be near the bottom under 'API Token'
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
    'admin_post_adfoin_save_drip_api_token',
    'adfoin_save_drip_api_token',
    10,
    0
);
function adfoin_save_drip_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_drip_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST["adfoin_drip_api_token"] );
    // Save tokens
    update_option( "adfoin_drip_api_token", $api_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=drip" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_drip_js_fields',
    10,
    1
);
function adfoin_drip_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_drip_action_fields' );
function adfoin_drip_action_fields()
{
    ?>

    <script type="text/template" id="drip-action-template">
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
    esc_attr_e( 'Account', 'advanced-form-integration' );
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
    esc_attr_e( 'Campaign', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[campaignId]" v-model="fielddata.campaignId">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'create_subscriber'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Workflow', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <select name="fieldData[workflowId]" v-model="fielddata.workflowId">
                        <option value=""><?php 
    _e( 'Select...', 'advanced-form-integration' );
    ?></option>
                        <option v-for="(item, index) in fielddata.workflows" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'subscribe'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock custom fields and tags consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
    'wp_ajax_adfoin_get_drip_accounts',
    'adfoin_get_drip_accounts',
    10,
    0
);
/*
 * Get Drip accounts
 */
function adfoin_get_drip_accounts()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = get_option( "adfoin_drip_api_token" );
    if ( !$api_token ) {
        return array();
    }
    $url = "https://api.getdrip.com/v2/accounts";
    $args = array(
        'timeout' => 20,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ),
    ),
    );
    $accounts = wp_remote_get( $url, $args );
    
    if ( !is_wp_error( $accounts ) ) {
        $body = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body->accounts, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_drip_list',
    'adfoin_get_drip_list',
    20,
    0
);
/*
 * Get Drip list
 */
function adfoin_get_drip_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = get_option( "adfoin_drip_api_token" );
    if ( !$api_token ) {
        wp_send_json_error();
    }
    $account_id = ( $_POST["accountId"] ? sanitize_text_field( $_POST["accountId"] ) : "" );
    if ( !$account_id ) {
        wp_send_json_error();
    }
    $url = "https://api.getdrip.com/v2/{$account_id}/campaigns";
    $args = array(
        'timeout' => 20,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'User-Agent'    => 'advanced-form-integraion',
        'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ),
    ),
    );
    $accounts = wp_remote_get( $url, $args );
    
    if ( !is_wp_error( $accounts ) ) {
        $body = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body->campaigns, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'wp_ajax_adfoin_get_drip_workflows',
    'adfoin_get_drip_workflows',
    20,
    0
);
/*
 * Get Drip list
 */
function adfoin_get_drip_workflows()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = get_option( "adfoin_drip_api_token" );
    if ( !$api_token ) {
        wp_send_json_error();
    }
    $account_id = ( $_POST["accountId"] ? sanitize_text_field( $_POST["accountId"] ) : "" );
    if ( !$account_id ) {
        wp_send_json_error();
    }
    $url = "https://api.getdrip.com/v2/{$account_id}/workflows";
    $args = array(
        'timeout' => 20,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'User-Agent'    => 'advanced-form-integraion',
        'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ),
    ),
    );
    $accounts = wp_remote_get( $url, $args );
    
    if ( !is_wp_error( $accounts ) ) {
        $body = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body->workflows, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

/*
 * Saves connection mapping
 */
function adfoin_drip_save_integration()
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
    'adfoin_drip_job_queue',
    'adfoin_drip_job_queue',
    10,
    1
);
function adfoin_drip_job_queue( $data )
{
    adfoin_drip_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Drip API
 */
function adfoin_drip_send_data( $record, $posted_data )
{
    $api_token = ( get_option( 'adfoin_drip_api_token' ) ? get_option( 'adfoin_drip_api_token' ) : "" );
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
    $campaign = ( empty($data["campaignId"]) ? "" : $data["campaignId"] );
    $workflow = ( empty($data["workflowId"]) ? "" : $data["workflowId"] );
    $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
    $first_name = ( empty($data["firstName"]) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data ) );
    $last_name = ( empty($data["lastName"]) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data ) );
    $phone = ( empty($data["phone"]) ? "" : adfoin_get_parsed_values( $data["phone"], $posted_data ) );
    $address1 = ( empty($data["address1"]) ? "" : adfoin_get_parsed_values( $data["address1"], $posted_data ) );
    $address2 = ( empty($data["address2"]) ? "" : adfoin_get_parsed_values( $data["address2"], $posted_data ) );
    $city = ( empty($data["city"]) ? "" : adfoin_get_parsed_values( $data["city"], $posted_data ) );
    $state = ( empty($data["state"]) ? "" : adfoin_get_parsed_values( $data["state"], $posted_data ) );
    $zip = ( empty($data["zip"]) ? "" : adfoin_get_parsed_values( $data["zip"], $posted_data ) );
    $country = ( empty($data["country"]) ? "" : adfoin_get_parsed_values( $data["country"], $posted_data ) );
    
    if ( $task == "create_subscriber" ) {
        $url = "https://api.getdrip.com/v2/{$account}/subscribers";
        $body = array(
            "subscribers" => array( array(
            "email"      => $email,
            "first_name" => $first_name,
            "last_name"  => $last_name,
            "phone"      => $phone,
            "address1"   => $address1,
            "address2"   => $address2,
            "city"       => $city,
            "state"      => $state,
            "zip"        => $zip,
            "country"    => $country,
        ) ),
        );
        $args = array(
            'timeout' => 20,
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
        
        if ( $campaign ) {
            $camp_url = "https://api.getdrip.com/v2/{$account}/campaigns/{$campaign}/subscribers";
            $camp_body = array(
                "subscribers" => array( array(
                "email" => $email,
            ) ),
            );
            $args["body"] = json_encode( $camp_body );
            $camp_response = wp_remote_post( $camp_url, $args );
            adfoin_add_to_log(
                $camp_response,
                $camp_url,
                $args,
                $record
            );
        }
        
        
        if ( $workflow ) {
            $wfl_url = "https://api.getdrip.com/v2/{$account}/workflows/{$workflow}/subscribers";
            $wfl_body = array(
                "subscribers" => array( array(
                "email" => $email,
            ) ),
            );
            $args["body"] = json_encode( $wfl_body );
            $wfl_response = wp_remote_post( $wfl_url, $args );
            adfoin_add_to_log(
                $wfl_response,
                $wfl_url,
                $args,
                $record
            );
        }
    
    }
    
    return;
}
