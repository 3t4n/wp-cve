<?php

add_filter( 'adfoin_action_providers', 'adfoin_salesrocks_actions', 10, 1 );

function adfoin_salesrocks_actions( $actions ) {

    $actions['salesrocks'] = array(
        'title' => __( 'Sales Rocks', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_salesrocks_settings_tab', 10, 1 );

function adfoin_salesrocks_settings_tab( $providers ) {
    $providers['salesrocks'] = __( 'Sales Rocks', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_salesrocks_settings_view', 10, 1 );

function adfoin_salesrocks_settings_view( $current_tab ) {
    if( $current_tab != 'salesrocks' ) {
        return;
    }

    $nonce    = wp_create_nonce( 'adfoin_salesrocks_settings' );
    $username = get_option( 'adfoin_salesrocks_username' ) ? get_option( 'adfoin_salesrocks_username' ) : '';
    $password = get_option( 'adfoin_salesrocks_password' ) ? get_option( 'adfoin_salesrocks_password' ) : '';
    ?>

    <form name="salesrocks_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_salesrocks_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'Username', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_salesrocks_username"
                           value="<?php echo esc_attr( $username ); ?>" placeholder="<?php _e( 'Enter Username', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"> <?php _e( 'Password', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="password" name="adfoin_salesrocks_password"
                           value="<?php echo esc_attr( $password ); ?>" placeholder="<?php _e( 'Enter Password', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_salesrocks_save_api_key', 'adfoin_save_salesrocks_api_key', 10, 0 );

function adfoin_save_salesrocks_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_salesrocks_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $username = sanitize_text_field( $_POST['adfoin_salesrocks_username'] );
    $password = sanitize_text_field( $_POST['adfoin_salesrocks_password'] );

    // Save keys
    update_option( "adfoin_salesrocks_username", $username );
    update_option( "adfoin_salesrocks_password", $password );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=salesrocks" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_salesrocks_js_fields', 10, 1 );

function adfoin_salesrocks_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_salesrocks_action_fields' );

function adfoin_salesrocks_action_fields() {
    ?>
    <script type="text/template" id="salesrocks-action-template">
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
                        <?php esc_attr_e( 'List', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
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

/*
 * Sales.rocks API Request
 */
function adfoin_salesrocks_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
    $access_token = adfoin_salesrocks_get_access_token();

    $base_url = 'https://api.sales.rocks/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'timeout' => 30,
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        $args['body'] = json_encode( $data );
    }

    $response = wp_remote_request( $url, $args );

    if ( $record ) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

function adfoin_salesrocks_get_access_token() {
    $username     = get_option('adfoin_salesrocks_username') ? get_option('adfoin_salesrocks_username') : '';
    $password     = get_option('adfoin_salesrocks_password') ? get_option('adfoin_salesrocks_password') : '';
    $url          = 'https://api.sales.rocks/auth/accessToken';
    $access_token = '';

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'timeout' => 30,
        'body' => json_encode( array(
            'username' => $username,
            'password' => $password
        ))
    );

    $response = wp_remote_post( $url, $args );
    
    if( 200 == wp_remote_retrieve_response_code( $response ) ) {
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( isset( $body['AccessToken'] ) ) {
            $access_token = $body['AccessToken'];
        }
    }

    return $access_token;
}

add_action( 'wp_ajax_adfoin_get_salesrocks_list', 'adfoin_get_salesrocks_list', 10, 0 );
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_salesrocks_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $data = adfoin_salesrocks_request( 'editable-lists/getLists', 'POST' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body  = json_decode( wp_remote_retrieve_body( $data ) );
    $lists = wp_list_pluck( $body->data, 'name', 'uuid' );

    wp_send_json_success( $lists );
}

add_action( 'adfoin_salesrocks_job_queue', 'adfoin_salesrocks_job_queue', 10, 1 );

function adfoin_salesrocks_job_queue( $data ) {
    adfoin_salesrocks_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Sales Rocks API
 */
function adfoin_salesrocks_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data['field_data'];
    $task = $record['task'];

    if( $task == 'subscribe' ) {
        $list_id = $data['listId'];
        $email   = empty( $data['email'] ) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data );
        $name    = empty( $data['name'] ) ? '' : adfoin_get_parsed_values( $data['name'], $posted_data );

        $data = array(
            'list_id' => $list_id,
            'data' => array(
                array(
                    'name'  => $name,
                    'email' => trim( $email )
                )
            )
        );

        $return = adfoin_salesrocks_request( 'editable-lists/addToList', 'POST', $data, $record );
    }

    return;
}