<?php

add_filter( 'adfoin_action_providers', 'adfoin_livestorm_actions', 10, 1 );

function adfoin_livestorm_actions( $actions ) {

    $actions['livestorm'] = array(
        'title' => __( 'Livestorm', 'advanced-form-integration' ),
        'tasks' => array(
            'add_people'   => __( 'Add people to event session', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_livestorm_settings_tab', 10, 1 );

function adfoin_livestorm_settings_tab( $providers ) {
    $providers['livestorm'] = __( 'Livestorm', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_livestorm_settings_view', 10, 1 );

function adfoin_livestorm_settings_view( $current_tab ) {
    if( $current_tab != 'livestorm' ) {
        return;
    }

    $nonce   = wp_create_nonce( 'adfoin_livestorm_settings' );
    $api_token = get_option( 'adfoin_livestorm_api_token' ) ? get_option( 'adfoin_livestorm_api_token' ) : '';
    ?>

    <form name="livestorm_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_livestorm_save_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_livestorm_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Please go to Account Settings > App marketplace > Public API > Generate a Token<br>For new accounts API access may be disabled. Please contact Livestorm support to enable it.<br>or<br>Go to Account Settings > App marketplace > Zapier, then copy the key.', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_livestorm_save_api_token', 'adfoin_save_livestorm_api_token', 10, 0 );

function adfoin_save_livestorm_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_livestorm_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST["adfoin_livestorm_api_token"] );

    // Save tokens
    update_option( "adfoin_livestorm_api_token", $api_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=livestorm" );
}

add_action( 'adfoin_action_fields', 'adfoin_livestorm_action_fields' );

function adfoin_livestorm_action_fields() {
    ?>
    <script type="text/template" id="livestorm-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_people'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_people'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Event', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[eventId]" v-model="fielddata.eventId" @change="getSessions">
                            <option value=""> <?php _e( 'Select Event...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.events" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': eventLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'add_people'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Session', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[sessionId]" v-model="fielddata.sessionId">
                            <option value=""> <?php _e( 'Select Session...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.sessions" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': sessionLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            
        </table>
    </script>
    <?php
}

function adfoin_livestorm_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_token = get_option( 'adfoin_livestorm_api_token' ) ? get_option( 'adfoin_livestorm_api_token' ) : '';

    $base_url = 'https://api.livestorm.co/v1/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => $api_token
        )
    );

    if( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }

    $response = wp_remote_request( $url, $args );

    if( $record ) {
        adfoin_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_livestorm_events', 'adfoin_get_livestorm_events', 10, 1 );

/*
 * Get Livestorm Event list
 */
function adfoin_get_livestorm_events()
{
    // Security Check
    if (!wp_verify_nonce($_POST['_nonce'], 'advanced-form-integration')) {
        die(__('Security check Failed', 'advanced-form-integration'));
    }

    $data = adfoin_livestorm_request('events');

    if (is_wp_error($data)) {
        wp_send_json_error();
    }

    $body   = json_decode(wp_remote_retrieve_body( $data ), true );
    $events = wp_list_pluck( $body, 'title', 'id' );

    wp_send_json_success($events);
}

add_action( 'wp_ajax_adfoin_get_livestorm_sessions', 'adfoin_get_livestorm_sessions', 10, 1 );

/*
 * Get Livestorm Session list
 */
function adfoin_get_livestorm_sessions()
{
    // Security Check
    if (!wp_verify_nonce($_POST['_nonce'], 'advanced-form-integration')) {
        die(__('Security check Failed', 'advanced-form-integration'));
    }

    $event_id = isset( $_POST['eventId'] ) ? $_POST['eventId'] : '';

    $data = adfoin_livestorm_request('events/' . $event_id . '/sessions');

    if (is_wp_error($data)) {
        wp_send_json_error();
    }

    $body   = json_decode(wp_remote_retrieve_body( $data ), true );
    $sessions = array();

    foreach( $body as $session ) {

        if( isset( $session['estimated_started_at_in_timezone'] ) ) {
            $sessions[$session['id']] = $session['estimated_started_at_in_timezone'];
        } else {
            $sessions[$session['id']] = $session['id'];
        }
        
    }

    wp_send_json_success($sessions);
}

add_action( 'adfoin_livestorm_job_queue', 'adfoin_livestorm_job_queue', 10, 1 );

function adfoin_livestorm_job_queue( $data ) {
    adfoin_livestorm_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to livestorm API
 */
function adfoin_livestorm_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( 'cl', $record_data['action_data']) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data       = $record_data['field_data'];
    $task       = $record['task'];
    $session_id = $data['sessionId'];

    if( $task == 'add_people' ) {
        
        $email      = empty( $data['email'] ) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data );
        $first_name = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );
        $last_name  = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );

        $data = array(
            'data' =>array(
                'type' => 'people',
                'attributes' => array(
                    'fields' => array(
                        array(
                            "id"    => "email",
                            "value" => trim( $email )
                        ),
                        array(
                            "id"    => "first_name",
                            "value" => $first_name
                        ),
                        array(
                            "id"    => "last_name",
                            "value" => $last_name
                        ),
                    )
                    ),
            )
        );

        $return = adfoin_livestorm_request( 'sessions/' . $session_id . '/people', 'POST', $data, $record );

    }

    return;
}