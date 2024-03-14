<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_getresponse_actions',
    10,
    1
);
function adfoin_getresponse_actions( $actions )
{
    $actions['getresponse'] = array(
        'title' => __( 'GetResponse', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_getresponse_settings_tab',
    10,
    1
);
function adfoin_getresponse_settings_tab( $providers )
{
    $providers['getresponse'] = __( 'GetResponse', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_getresponse_settings_view',
    10,
    1
);
function adfoin_getresponse_settings_view( $current_tab )
{
    if ( $current_tab != 'getresponse' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_getresponse_settings" );
    $api_key = ( get_option( 'adfoin_getresponse_api_key' ) ? get_option( 'adfoin_getresponse_api_key' ) : "" );
    ?>

    <form name="getresponse_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_getresponse_save_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_getresponse_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><a
                            href="https://app.getresponse.com/api"
                            target="_blank" rel="noopener noreferrer"><?php 
    _e( 'Click here to get the API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_getresponse_save_api_key',
    'adfoin_save_getresponse_api_key',
    10,
    0
);
function adfoin_save_getresponse_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_getresponse_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_getresponse_api_key"] );
    // Save keys
    update_option( "adfoin_getresponse_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=getresponse" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_getresponse_js_fields',
    10,
    1
);
function adfoin_getresponse_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_getresponse_action_fields' );
function adfoin_getresponse_action_fields()
{
    ?>
    <script type="text/template" id="getresponse-action-template">
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

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'GetResponse List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php 
    _e( 'Select List...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
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
    'wp_ajax_adfoin_get_getresponse_list',
    'adfoin_get_getresponse_list',
    10,
    0
);
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_getresponse_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_getresponse_request( 'campaigns' );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body, "name", "campaignId" );
    wp_send_json_success( $lists );
}

add_action(
    'adfoin_getresponse_job_queue',
    'adfoin_getresponse_job_queue',
    10,
    1
);
function adfoin_getresponse_job_queue( $data )
{
    adfoin_getresponse_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to GetResponse API
 */
function adfoin_getresponse_send_data( $record, $posted_data )
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
    $task = $record["task"];
    
    if ( $task == "subscribe" ) {
        $list_id = $data["listId"];
        $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
        $name = ( empty($data["name"]) ? "" : adfoin_get_parsed_values( $data["name"], $posted_data ) );
        $ip = ( empty($data["ipAddress"]) ? "" : adfoin_get_parsed_values( $data["ipAddress"], $posted_data ) );
        $data = array(
            'email'    => $email,
            'campaign' => array(
            'campaignId' => $list_id,
        ),
            'name'     => $name,
        );
        if ( $ip ) {
            $data['ipAddress'] = $ip;
        }
        $return = adfoin_getresponse_request(
            'contacts',
            'POST',
            $data,
            $record
        );
    }
    
    return;
}

function adfoin_getresponse_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_key = ( get_option( 'adfoin_getresponse_api_key' ) ? get_option( 'adfoin_getresponse_api_key' ) : "" );
    $base_url = 'https://api.getresponse.com/v3/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type' => 'application/json',
        'X-Auth-Token' => 'api-key ' . $api_key,
    ),
    );
    if ( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }
    $args = apply_filters( 'adfoin_getresponse_before_sent', $args, $url );
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
