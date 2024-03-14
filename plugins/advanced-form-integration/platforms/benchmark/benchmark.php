<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_benchmark_actions',
    10,
    1
);
function adfoin_benchmark_actions( $actions )
{
    $actions['benchmark'] = array(
        'title' => __( 'Benchmark', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Add Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_benchmark_settings_tab',
    10,
    1
);
function adfoin_benchmark_settings_tab( $providers )
{
    $providers['benchmark'] = __( 'Benchmark', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_benchmark_settings_view',
    10,
    1
);
function adfoin_benchmark_settings_view( $current_tab )
{
    if ( $current_tab != 'benchmark' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_benchmark_settings" );
    $api_key = ( get_option( 'adfoin_benchmark_api_key' ) ? get_option( 'adfoin_benchmark_api_key' ) : "" );
    ?>

    <form name="benchmark_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_benchmark_save_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_benchmark_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to Profile > Integrations > API Key and copy the key', 'advanced-form-integration' );
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
    'admin_post_adfoin_benchmark_save_api_key',
    'adfoin_save_benchmark_api_key',
    10,
    0
);
function adfoin_save_benchmark_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_benchmark_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_benchmark_api_key"] );
    // Save tokens
    update_option( "adfoin_benchmark_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=benchmark" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_benchmark_js_fields',
    10,
    1
);
function adfoin_benchmark_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_benchmark_action_fields' );
function adfoin_benchmark_action_fields()
{
    ?>
    <script type="text/template" id="benchmark-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Contact Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Contact List', 'advanced-form-integration' );
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
        printf( __( 'To unlock custom fields, consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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

function adfoin_benchmark_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_key = get_option( "adfoin_benchmark_api_key" );
    if ( !$api_key ) {
        return array();
    }
    $base_url = 'https://clientapi.benchmarkemail.com/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'timeout' => 30,
        'headers' => array(
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8',
        'AuthToken'    => $api_key,
    ),
    );
    if ( 'POST' == $method || 'PUT' == $method || 'PATCH' == $method ) {
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
    'wp_ajax_adfoin_get_benchmark_list',
    'adfoin_get_benchmark_list',
    10,
    0
);
/*
 * Get subscriber lists
 */
function adfoin_get_benchmark_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $endpoint = "Contact/?pageSize=1000";
    $data = adfoin_benchmark_request( $endpoint );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->Response->Data, 'Name', 'ID' );
    wp_send_json_success( $lists );
}

function adfoin_benchmark_create_contact( $list_id, $properties, $record )
{
    $endpoint = "Contact/{$list_id}/ContactDetails";
    $response = adfoin_benchmark_request(
        $endpoint,
        'POST',
        $properties,
        $record
    );
    return $response;
}

function adfoin_benchmark_update_contact(
    $list_id,
    $contact_id,
    $properties,
    $record
)
{
    $endpoint = "Contact/{$list_id}/ContactDetails/{$contact_id}";
    $response = adfoin_benchmark_request(
        $endpoint,
        'PATCH',
        $properties,
        $record
    );
    return $response;
}

// Check if contact exists
function adfoin_benchmark_check_if_contact_exists( $email )
{
    $endpoint = "Contact/ContactDetails?Search={$email}";
    $data = adfoin_benchmark_request( $endpoint );
    if ( is_wp_error( $data ) ) {
        return false;
    }
    $body = json_decode( wp_remote_retrieve_body( $data ), true );
    
    if ( isset(
        $body['Response'],
        $body['Response']['Data'],
        $body['Response']['Data'][0],
        $body['Response']['Data'][0]['ID']
    ) ) {
        return $body['Response']['Data'][0]['ID'];
    } else {
        return false;
    }

}

add_action(
    'adfoin_benchmark_job_queue',
    'adfoin_benchmark_job_queue',
    10,
    1
);
function adfoin_benchmark_job_queue( $data )
{
    adfoin_benchmark_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Benchmark API
 */
function adfoin_benchmark_send_data( $record, $posted_data )
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
        $first_name = ( empty($data["firstName"]) ? "" : adfoin_get_parsed_values( $data["firstName"], $posted_data ) );
        $middle_name = ( empty($data["middleName"]) ? "" : adfoin_get_parsed_values( $data["middleName"], $posted_data ) );
        $last_name = ( empty($data["lastName"]) ? "" : adfoin_get_parsed_values( $data["lastName"], $posted_data ) );
        $data = array(
            "Data" => array(
            "Email"      => $email,
            "FirstName"  => $first_name,
            "MiddleName" => $middle_name,
            "LastName"   => $last_name,
            "EmailPerm"  => 1,
        ),
        );
        $data = array_filter( $data );
        $contact_id = adfoin_benchmark_check_if_contact_exists( $email );
        
        if ( $contact_id ) {
            $return = adfoin_benchmark_update_contact(
                $list_id,
                $contact_id,
                $data,
                $record
            );
        } else {
            $return = adfoin_benchmark_create_contact( $list_id, $data, $record );
        }
    
    }
    
    return;
}
