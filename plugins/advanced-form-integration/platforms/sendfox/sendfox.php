<?php

add_filter( 'adfoin_action_providers', 'adfoin_sendfox_actions', 10, 1 );

function adfoin_sendfox_actions( $actions ) {

    $actions['sendfox'] = array(
        'title' => __( 'SendFox', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_sendfox_settings_tab', 10, 1 );

function adfoin_sendfox_settings_tab( $providers ) {
    $providers['sendfox'] = __( 'SendFox', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_sendfox_settings_view', 10, 1 );

function adfoin_sendfox_settings_view( $current_tab ) {
    if( $current_tab != 'sendfox' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_sendfox_settings' );
    $api_key = get_option( 'adfoin_sendfox_api_key' ) ? get_option( 'adfoin_sendfox_api_key' ) : '';
    ?>

    <form name="sendfox_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_sendfox_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'SendFox Personal Access token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_sendfox_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Enter Access Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to <a target="_blank" rel="noopener noreferrer" href="https://sendfox.com/account/oauth">https://sendfox.com/account/oauth</a> and click "Create New Token"', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_sendfox_api_key', 'adfoin_save_sendfox_api_key', 10, 0 );

function adfoin_save_sendfox_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_sendfox_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST['adfoin_sendfox_api_key'] );

    // Save tokens
    update_option( 'adfoin_sendfox_api_key', $api_key );

    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=sendfox' );
}

add_action( 'adfoin_add_js_fields', 'adfoin_sendfox_js_fields', 10, 1 );

function adfoin_sendfox_js_fields( $field_data ) { }

add_action( 'adfoin_action_fields', 'adfoin_sendfox_action_fields' );

function adfoin_sendfox_action_fields() {
?>
    <script type="text/template" id="sendfox-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'SendFox List', 'advanced-form-integration' ); ?>
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

/*
 * Sendfox API Request
 */
function adfoin_sendfox_request($endpoint, $method = 'GET', $data = array(), $record = array()) {

    $api_key  = get_option('adfoin_sendfox_api_key');
    $base_url = 'https://api.sendfox.com/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        $args['body'] = json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_sendfox_list', 'adfoin_get_sendfox_list', 10, 0 );

/*
 * Get Mailchimp subscriber lists
 */
function adfoin_get_sendfox_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $lists = array();
    $data  = adfoin_sendfox_request( 'lists' );

    if( !is_wp_error( $data ) ) {
        $body              = json_decode( wp_remote_retrieve_body( $data ), true );
        $lists             = $body['data'];
        $lists_total       = absint( $body['total'] );
        $list_per_page     = absint( $body['per_page'] );
        $pagination_needed = absint( $lists_total / $list_per_page ) + 1;

        if( $pagination_needed >= 2) {
            $response_pages = array();
            $response_body  = array();

            for( $i = 2; $i <= $pagination_needed; $i++ ){
                $response_pages[$i] = adfoin_sendfox_request( 'lists?page=' . $i );
                $response_body[$i]  = json_decode( wp_remote_retrieve_body( $response_pages[$i] ), true );

                if( $response_body[$i]['data'] && is_array( $response_body[$i]['data'] ) )
                {
                    $lists = array_merge( $lists, $response_body[$i]['data'] );
                }
            }
        }
        
        $final_list = wp_list_pluck( $lists, 'name', 'id' );

        wp_send_json_success( $final_list );
    } else {
        wp_send_json_error();
    }
}

add_action( 'adfoin_sendfox_job_queue', 'adfoin_sendfox_job_queue', 10, 1 );

function adfoin_sendfox_job_queue( $data ) {
    adfoin_sendfox_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to SendFox API
 */
function adfoin_sendfox_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data['field_data'];
    $list_id = $data['listId'];
    $task    = $record['task'];
    $email   = empty( $data['email'] ) ? '' : adfoin_get_parsed_values($data['email'], $posted_data);

    if( $task == 'subscribe' ) {
        $first_name = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values($data['firstName'], $posted_data);
        $last_name  = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values($data['lastName'], $posted_data);

        $subscriber_data = array(
            'email' => trim( $email )
        );

        if( $first_name ) { $subscriber_data['first_name'] = $first_name; }
        if( $last_name ) { $subscriber_data['last_name'] = $last_name; }

        if( $list_id ) {
            $subscriber_data['lists'] = array( $list_id );
        }

        $return = adfoin_sendfox_request( 'contacts', 'POST', $subscriber_data, $record );

        return;
    }
}