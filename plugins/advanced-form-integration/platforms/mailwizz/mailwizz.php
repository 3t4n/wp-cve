<?php

add_filter( 'adfoin_action_providers', 'adfoin_mailwizz_actions', 10, 1 );

function adfoin_mailwizz_actions( $actions ) {

    $actions['mailwizz'] = array(
        'title' => __( 'MailWizz', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_mailwizz_settings_tab', 10, 1 );

function adfoin_mailwizz_settings_tab( $providers ) {
    $providers['mailwizz'] = __( 'MailWizz', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_mailwizz_settings_view', 10, 1 );

function adfoin_mailwizz_settings_view( $current_tab ) {
    if( $current_tab != 'mailwizz' ) {
        return;
    }

    $nonce    = wp_create_nonce( 'adfoin_mailwizz_settings' );
    $api_url = get_option( 'adfoin_mailwizz_api_url' ) ? get_option( 'adfoin_mailwizz_api_url' ) : '';
    $api_key = get_option( 'adfoin_mailwizz_api_key' ) ? get_option( 'adfoin_mailwizz_api_key' ) : '';
    ?>

    <form name="mailwizz_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_mailwizz_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API URL', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_mailwizz_api_url"
                           value="<?php echo esc_attr( $api_url ); ?>" placeholder="<?php _e( 'Enter API URL', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>

                    <p class="description" id="code-description"><?php _e( 'e.g. https://yourdomain.com/api/index.php', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_mailwizz_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description"><?php _e( 'Login as customer, go to API Keys menu and create new API Key', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_mailwizz_save_api_key', 'adfoin_save_mailwizz_api_key', 10, 0 );

function adfoin_save_mailwizz_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailwizz_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_url = sanitize_text_field( $_POST['adfoin_mailwizz_api_url'] );
    $api_key = sanitize_text_field( $_POST['adfoin_mailwizz_api_key'] );

    // Save keys
    update_option( 'adfoin_mailwizz_api_url', $api_url );
    update_option( 'adfoin_mailwizz_api_key', $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=mailwizz" );
}

add_action( 'adfoin_action_fields', 'adfoin_mailwizz_action_fields' );

function adfoin_mailwizz_action_fields() {
    ?>
    <script type="text/template" id="mailwizz-action-template">
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
 * Mailwizz API Request
 */
function adfoin_mailwizz_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
    $api_url = get_option('adfoin_mailwizz_api_url') ? get_option('adfoin_mailwizz_api_url') : '';
    $api_key = get_option('adfoin_mailwizz_api_key') ? get_option('adfoin_mailwizz_api_key') : '';
    $url     = $api_url . $endpoint;

    $args = array(
        'method'  => $method,
        'timeout' => 30,
        'headers' => array(
            'Content-Type'    => 'application/x-www-form-urlencoded',
            'X-MW-PUBLIC-KEY' => $api_key,
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        $args['body'] = $data;
    }

    $response = wp_remote_request( $url, $args );

    if ( $record ) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_mailwizz_list', 'adfoin_get_mailwizz_list', 10, 0 );
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_mailwizz_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $lists = array();
    $page = 1;
    $hasnext = true;

    do{
        $data = adfoin_mailwizz_request( "/lists?page={$page}&per_page=50" );

        if( is_wp_error( $data ) ) {
            wp_send_json_error();
        }

        $body = json_decode( wp_remote_retrieve_body( $data ) );
        
        foreach( $body->data->records as $list ) {
            $lists[$list->general->list_uid] = $list->general->display_name;
        }

        if( $body->data->next_page ) {
            $page = $body->data->next_page;
        }else{
            $hasnext = false;
        }
    } while( $hasnext );
    
    wp_send_json_success( $lists );
}

add_action( 'adfoin_mailwizz_job_queue', 'adfoin_mailwizz_job_queue', 10, 1 );

function adfoin_mailwizz_job_queue( $data ) {
    adfoin_mailwizz_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Mailwizz API
 */
function adfoin_mailwizz_send_data( $record, $posted_data ) {

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
        $email      = empty( $data['email'] ) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data );
        $first_name = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );
        $last_name  = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );

        $body_data = array(
            'EMAIL' => trim( $email ),
            'FNAME' => $first_name,
            'LNAME' => $last_name
        );

        $return = adfoin_mailwizz_request( '/lists/' . $list_id . '/subscribers', 'POST', $body_data, $record );
    }

    return;
}