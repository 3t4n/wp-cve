<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_activecampaign_actions',
    10,
    1
);
function adfoin_activecampaign_actions( $actions )
{
    $actions['activecampaign'] = array(
        'title' => __( 'ActiveCampaign', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Add Contact/Deal/Note', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_activecampaign_settings_tab',
    10,
    1
);
function adfoin_activecampaign_settings_tab( $providers )
{
    $providers['activecampaign'] = __( 'ActiveCampaign', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_activecampaign_settings_view',
    10,
    1
);
function adfoin_activecampaign_settings_view( $current_tab )
{
    if ( $current_tab != 'activecampaign' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_activecampaign_settings" );
    $api_key = ( get_option( 'adfoin_activecampaign_api_key' ) ? get_option( 'adfoin_activecampaign_api_key' ) : '' );
    $url = ( get_option( 'adfoin_activecampaign_url' ) ? get_option( 'adfoin_activecampaign_url' ) : '' );
    ?>

    <form name="activecampaign_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_activecampaign_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'URL', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_activecampaign_url"
                           value="<?php 
    echo  esc_attr( $url ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter URL', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p>
                        Go to Settings > Developer to get URL
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_activecampaign_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p>
                        Go to Settings > Developer to get Key
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
    'admin_post_adfoin_save_activecampaign_api_key',
    'adfoin_save_activecampaign_api_key',
    10,
    0
);
function adfoin_save_activecampaign_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_activecampaign_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_activecampaign_api_key"] );
    $url = sanitize_text_field( $_POST["adfoin_activecampaign_url"] );
    // Save tokens
    update_option( "adfoin_activecampaign_api_key", $api_key );
    update_option( "adfoin_activecampaign_url", $url );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=activecampaign" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_activecampaign_js_fields',
    10,
    1
);
function adfoin_activecampaign_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_activecampaign_action_fields' );
function adfoin_activecampaign_action_fields()
{
    ?>
    <script type="text/template" id="activecampaign-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr class="alternate" v-if="action.task == 'subscribe'">
                <td>
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Instructions', 'advanced-form-integration' );
    ?>
                    </label>
                </td>

                <td>
                    <p><?php 
    _e( 'This action will create/update contact at first and then add it to other tasks if filled. For example if you want to add the contact to a list or automation or deal just select/fill those fields only, leave other fields blank.', 'advanced-form-integration' );
    ?></p>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Account', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[accountId]" v-model="fielddata.accountId">
                        <option value=""> <?php 
    _e( 'Select Account...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.accounts" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': accountLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
                        <option value=""> <?php 
    _e( 'Select List...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Automation', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[automationId]" v-model="fielddata.automationId">
                        <option value=""> <?php 
    _e( 'Select Automation...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.automations" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': automationLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Update if contact already exists', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <input name="fieldData[update]" value="true" type="checkbox" v-model="fielddata.update">
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

/*
 * ActiveCampaign API Request
 */
function adfoin_activecampaign_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_key = ( get_option( 'adfoin_activecampaign_api_key' ) ? get_option( 'adfoin_activecampaign_api_key' ) : '' );
    $base_url = ( get_option( 'adfoin_activecampaign_url' ) ? get_option( 'adfoin_activecampaign_url' ) : '' );
    $url = $base_url . '/api/3/' . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type' => 'application/json',
        'Api-Token'    => $api_key,
    ),
    );
    if ( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }
    $response = wp_remote_request( $url, $args );
    if ( $record ) {
        adfoin_add_to_log(
            $response,
            $url,
            $args,
            $record
        );
    }
    return $response;
}

add_action(
    'wp_ajax_adfoin_get_activecampaign_list',
    'adfoin_get_activecampaign_list',
    10,
    0
);
/*
 * Get ActiveCampaign subscriber lists
 */
function adfoin_get_activecampaign_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $page = 0;
    $limit = 100;
    $has_value = true;
    $all_data = array();
    while ( $has_value ) {
        $offset = $page * $limit;
        $endpoint = "lists?limit={$limit}&offset={$offset}";
        $data = adfoin_activecampaign_request( $endpoint );
        $body = json_decode( wp_remote_retrieve_body( $data ) );
        
        if ( empty($body->lists) ) {
            $has_value = false;
        } else {
            $lists = wp_list_pluck( $body->lists, 'name', 'id' );
            $all_data = $all_data + $lists;
            $page++;
        }
    
    }
    wp_send_json_success( $all_data );
}

add_action(
    'wp_ajax_adfoin_get_activecampaign_automations',
    'adfoin_get_activecampaign_automations',
    10,
    0
);
/*
 * Get ActiveCampaign subscriber automations
 */
function adfoin_get_activecampaign_automations()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $page = 0;
    $limit = 100;
    $has_value = true;
    $all_data = array();
    while ( $has_value ) {
        $offset = $page * $limit;
        $endpoint = "automations?limit={$limit}&offset={$offset}";
        $data = adfoin_activecampaign_request( $endpoint );
        $body = json_decode( wp_remote_retrieve_body( $data ) );
        
        if ( empty($body->automations) ) {
            $has_value = false;
        } else {
            $automations = wp_list_pluck( $body->automations, 'name', 'id' );
            $all_data = $all_data + $automations;
            $page++;
        }
    
    }
    wp_send_json_success( $all_data );
}

add_action(
    'wp_ajax_adfoin_get_activecampaign_accounts',
    'adfoin_get_activecampaign_accounts',
    10,
    0
);
/*
 * Get ActiveCampaign subscriber automations
 */
function adfoin_get_activecampaign_accounts()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $page = 0;
    $limit = 100;
    $has_value = true;
    $all_data = array();
    while ( $has_value ) {
        $offset = $page * $limit;
        $endpoint = "accounts?limit={$limit}&offset={$offset}";
        $data = adfoin_activecampaign_request( $endpoint );
        $body = json_decode( wp_remote_retrieve_body( $data ) );
        
        if ( empty($body->accounts) ) {
            $has_value = false;
        } else {
            $accounts = wp_list_pluck( $body->accounts, 'name', 'id' );
            $all_data = $all_data + $accounts;
            $page++;
        }
    
    }
    wp_send_json_success( $all_data );
}

add_action(
    'wp_ajax_adfoin_get_activecampaign_deal_fields',
    'adfoin_get_activecampaign_deal_fields',
    10,
    0
);
/*
 * Get Pipedrive Dal Fields
 */
function adfoin_get_activecampaign_deal_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = get_option( "adfoin_activecampaign_api_key" );
    $base_url = get_option( "adfoin_activecampaign_url" );
    if ( !$api_key || !$base_url ) {
        return array();
    }
    $url = "{$base_url}/api/3/dealGroups?limit=100";
    $args = array(
        "headers" => array(
        "Content-Type" => "application/json",
        "Api-Token"    => $api_key,
    ),
    );
    $data = wp_remote_get( $url, $args );
    $stages = "";
    $stage_body = json_decode( wp_remote_retrieve_body( $data ) );
    $pipelines = wp_list_pluck( $stage_body->dealGroups, "title", "id" );
    if ( isset( $stage_body->dealStages ) ) {
        if ( is_array( $stage_body->dealStages ) ) {
            foreach ( $stage_body->dealStages as $single ) {
                $stages .= $pipelines[$single->group] . '/' . $single->title . ': ' . $single->id . ' ';
            }
        }
    }
    $user_url = "{$base_url}/api/3/users?limit=100";
    $user_data = wp_remote_get( $user_url, $args );
    $users = "";
    $user_body = json_decode( wp_remote_retrieve_body( $user_data ) );
    foreach ( $user_body->users as $single ) {
        $users .= $single->username . ': ' . $single->id . ' ';
    }
    $deal_fields = array(
        array(
        'key'         => 'dealTitle',
        'value'       => 'Title [Deal]',
        'description' => 'Required for deal creation, leave blank if not needed',
    ),
        array(
        'key'         => 'dealDescription',
        'value'       => 'Description [Deal]',
        'description' => '',
    ),
        array(
        'key'         => 'dealCurrency',
        'value'       => 'Currency [Deal]',
        'description' => '',
    ),
        array(
        'key'         => 'dealStage',
        'value'       => 'Stage ID [Deal]',
        'description' => $stages,
    ),
        array(
        'key'         => 'dealOwner',
        'value'       => 'Owner ID [Deal]',
        'description' => $users,
    ),
        array(
        'key'         => 'dealValue',
        'value'       => 'Value [Deal]',
        'description' => '',
    )
    );
    wp_send_json_success( $deal_fields );
    return;
}

/*
 * Saves connection mapping
 */
function adfoin_activecampaign_save_integration()
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
    'adfoin_activecampaign_job_queue',
    'adfoin_activecampaign_job_queue',
    10,
    1
);
function adfoin_activecampaign_job_queue( $data )
{
    adfoin_activecampaign_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to ActiveCampaign API
 */
function adfoin_activecampaign_send_data( $record, $posted_data )
{
    $api_key = ( get_option( 'adfoin_activecampaign_api_key' ) ? get_option( 'adfoin_activecampaign_api_key' ) : "" );
    $base_url = ( get_option( 'adfoin_activecampaign_url' ) ? get_option( 'adfoin_activecampaign_url' ) : "" );
    if ( !$api_key || !$base_url ) {
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
    $list_id = $data["listId"];
    $aut_id = $data["automationId"];
    $acc_id = $data["accountId"];
    $task = $record["task"];
    $update = $data["update"];
    $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
    
    if ( $task == "subscribe" ) {
        $first_name = ( empty($data["firstName"]) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data ) );
        $last_name = ( empty($data["lastName"]) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data ) );
        $phone_number = ( empty($data["phoneNumber"]) ? "" : adfoin_get_parsed_values( $data["phoneNumber"], $posted_data ) );
        $deal_tile = ( empty($data["dealTitle"]) ? "" : adfoin_get_parsed_values( $data["dealTitle"], $posted_data ) );
        $note = ( empty($data["note"]) ? "" : adfoin_get_parsed_values( $data["note"], $posted_data ) );
        $url = "{$base_url}/api/3/contacts";
        if ( "true" == $update ) {
            $url = "{$base_url}/api/3/contact/sync";
        }
        $request_data = array(
            "contact" => array(
            "email"      => $email,
            "first_name" => $first_name,
            "last_name"  => $last_name,
            "phone"      => $phone_number,
        ),
        );
        $request_data = array_map( 'array_filter', $request_data );
        $args = array(
            'headers' => array(
            'Content-Type' => 'application/json',
            'Api-Token'    => $api_key,
        ),
            'body'    => json_encode( $request_data ),
        );
        $return = wp_remote_post( $url, $args );
        adfoin_add_to_log(
            $return,
            $url,
            $args,
            $record
        );
        $contact_id = "";
        
        if ( !is_wp_error( $return ) ) {
            $return_body = json_decode( wp_remote_retrieve_body( $return ) );
            $contact_id = $return_body->contact->id;
        }
        
        
        if ( $contact_id && $list_id ) {
            $url = "{$base_url}/api/3/contactLists";
            $request_data = array(
                "contactList" => array(
                "list"    => $list_id,
                "contact" => $contact_id,
                "status"  => 1,
            ),
            );
            $args = array(
                'headers' => array(
                'Content-Type' => 'application/json',
                'Api-Token'    => $api_key,
            ),
                'body'    => json_encode( $request_data ),
            );
            $return = wp_remote_post( $url, $args );
            adfoin_add_to_log(
                $return,
                $url,
                $args,
                $record
            );
        }
        
        
        if ( $contact_id && $aut_id ) {
            $url = "{$base_url}/api/3/contactAutomations";
            $request_data = array(
                "contactAutomation" => array(
                "automation" => $aut_id,
                "contact"    => $contact_id,
            ),
            );
            $args = array(
                'headers' => array(
                'Content-Type' => 'application/json',
                'Api-Token'    => $api_key,
            ),
                'body'    => json_encode( $request_data ),
            );
            $return = wp_remote_post( $url, $args );
            adfoin_add_to_log(
                $return,
                $url,
                $args,
                $record
            );
        }
        
        
        if ( $contact_id && $acc_id ) {
            $url = "{$base_url}/api/3/accountContacts";
            $request_data = array(
                "accountContact" => array(
                "account" => $acc_id,
                "contact" => $contact_id,
            ),
            );
            $args = array(
                'headers' => array(
                'Content-Type' => 'application/json',
                'Api-Token'    => $api_key,
            ),
                'body'    => json_encode( $request_data ),
            );
            $return = wp_remote_post( $url, $args );
            adfoin_add_to_log(
                $return,
                $url,
                $args,
                $record
            );
        }
        
        
        if ( $contact_id && $deal_tile ) {
            $deal_description = ( empty($data["dealDescription"]) ? "" : adfoin_get_parsed_values( $data["dealDescription"], $posted_data ) );
            $deal_currency = ( empty($data["dealCurrency"]) ? "" : adfoin_get_parsed_values( $data["dealCurrency"], $posted_data ) );
            $deal_stage = ( empty($data["dealStage"]) ? "" : adfoin_get_parsed_values( $data["dealStage"], $posted_data ) );
            $deal_owner = ( empty($data["dealOwner"]) ? "" : adfoin_get_parsed_values( $data["dealOwner"], $posted_data ) );
            $deal_value = ( empty($data["dealValue"]) ? "" : adfoin_get_parsed_values( $data["dealValue"], $posted_data ) );
            $url = "{$base_url}/api/3/deals";
            $request_data = array(
                "deal" => array(
                "title"       => $deal_tile,
                "description" => $deal_description,
                "currency"    => $deal_currency,
                "owner"       => $deal_owner,
                "value"       => $deal_value * 100,
                "stage"       => $deal_stage,
                "contact"     => $contact_id,
            ),
            );
            $args = array(
                'headers' => array(
                'Content-Type' => 'application/json',
                'Api-Token'    => $api_key,
            ),
                'body'    => json_encode( $request_data ),
            );
            $return = wp_remote_post( $url, $args );
            adfoin_add_to_log(
                $return,
                $url,
                $args,
                $record
            );
        }
        
        
        if ( $contact_id && $note ) {
            $url = "{$base_url}/api/3/notes";
            $request_data = array(
                "note" => array(
                "note"    => $note,
                "relid"   => $contact_id,
                "reltype" => "Subscriber",
            ),
            );
            $args = array(
                'headers' => array(
                'Content-Type' => 'application/json',
                'Api-Token'    => $api_key,
            ),
                'body'    => json_encode( $request_data ),
            );
            $return = wp_remote_post( $url, $args );
            adfoin_add_to_log(
                $return,
                $url,
                $args,
                $record
            );
        }
        
        return;
    }

}
