<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mailchimp_actions',
    10,
    1
);
function adfoin_mailchimp_actions( $actions )
{
    $actions['mailchimp'] = array(
        'title' => __( 'Mailchimp', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' ),
        'unsubscribe' => __( 'Unsubscribe From List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mailchimp_settings_tab',
    10,
    1
);
function adfoin_mailchimp_settings_tab( $providers )
{
    $providers['mailchimp'] = __( 'Mailchimp', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mailchimp_settings_view',
    10,
    1
);
function adfoin_mailchimp_settings_view( $current_tab )
{
    if ( $current_tab != 'mailchimp' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_mailchimp_settings" );
    $api_key = ( get_option( 'adfoin_mailchimp_api_key' ) ? get_option( 'adfoin_mailchimp_api_key' ) : "" );
    ?>

    <form name="mailchimp_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_mailchimp_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Mailchimp API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mailchimp_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Please go to Account > Extras > API Keys', 'advanced-form-integration' );
    ?></a></p>
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
    'admin_post_adfoin_save_mailchimp_api_key',
    'adfoin_save_mailchimp_api_key',
    10,
    0
);
function adfoin_save_mailchimp_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailchimp_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_mailchimp_api_key"] );
    // Save tokens
    update_option( "adfoin_mailchimp_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=mailchimp" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_mailchimp_js_fields',
    10,
    1
);
function adfoin_mailchimp_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_mailchimp_action_fields' );
function adfoin_mailchimp_action_fields()
{
    ?>
    <script type="text/template" id="mailchimp-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Mailchimp Audience', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php 
    _e( 'Select Audience...', 'advanced-form-integration' );
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
    esc_attr_e( 'Double Opt-In', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" value="true" name="fieldData[doubleoptin]" v-model="fielddata.doubleoptin">
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
    'wp_ajax_adfoin_get_mailchimp_list',
    'adfoin_get_mailchimp_list',
    10,
    0
);
/*
 * Get Mailchimp subscriber lists
 */
function adfoin_get_mailchimp_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_mailchimp_request( 'lists?count=1000', 'GET' );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->lists, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

/**
 * Makes a request to the Mailchimp API.
 *
 * @param string $endpoint The API endpoint to request.
 * @param string $method The HTTP method to use for the request (e.g., GET, POST, PUT).
 * @param array $data The data to send in the request body (for POST and PUT requests).
 * @param array $record The record to log (optional).
 *
 * @return mixed The response from the Mailchimp API.
 */
function adfoin_mailchimp_request(
    $endpoint,
    $method,
    $data = array(),
    $record = array()
)
{
    $api_key = ( get_option( 'adfoin_mailchimp_api_key' ) ? get_option( 'adfoin_mailchimp_api_key' ) : '' );
    if ( !$api_key ) {
        return;
    }
    $prefix = explode( '-', $api_key )[1];
    $base_url = "https://{$prefix}.api.mailchimp.com/3.0/";
    $url = $base_url . $endpoint;
    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'api_key ' . $api_key,
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
    'adfoin_mailchimp_job_queue',
    'adfoin_mailchimp_job_queue',
    10,
    1
);
function adfoin_mailchimp_job_queue( $data )
{
    adfoin_mailchimp_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Mailchimp API
 */
function adfoin_mailchimp_send_data( $record, $posted_data )
{
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
    $dopt = $data["doubleoptin"];
    $task = $record["task"];
    $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
    
    if ( $task == "subscribe" ) {
        $first_name = ( empty($data["firstName"]) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data ) );
        $last_name = ( empty($data["lastName"]) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data ) );
        $phone_number = ( empty($data["phoneNumber"]) ? "" : adfoin_get_parsed_values( $data["phoneNumber"], $posted_data ) );
        $status = ( "true" == $dopt ? "pending" : "subscribed" );
        $subscriber_data = array(
            "email_address" => $email,
            "status"        => $status,
            "merge_fields"  => array(
            "FNAME" => ( $first_name === NULL ? "" : $first_name ),
            "LNAME" => ( $last_name === NULL ? "" : $last_name ),
            "PHONE" => ( $phone_number === NULL ? "" : $phone_number ),
        ),
        );
        $endpoint = "lists/{$list_id}/members";
        $return = adfoin_mailchimp_request(
            $endpoint,
            'POST',
            $subscriber_data,
            $record
        );
    }
    
    
    if ( $task == 'unsubscribe' ) {
        $search_endpoint = "search-members?query={$email}";
        $member = adfoin_mailchimp_request( $search_endpoint, 'GET' );
        
        if ( !is_wp_error( $member ) ) {
            $body = json_decode( $member['body'], true );
            $id = $body['exact_matches']['members'][0]['id'];
            $unsub_end = "lists/{$list_id}/members/{$id}";
            $return = adfoin_mailchimp_request(
                $unsub_end,
                'DELETE',
                array(),
                $record
            );
            
            if ( $return['response']['code'] == 204 ) {
                return array( 1 );
            } else {
                return array( 0, $return );
            }
        
        } else {
            return;
        }
    
    }

}
