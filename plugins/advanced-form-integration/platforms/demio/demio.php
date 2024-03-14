<?php

add_filter( 'adfoin_action_providers', 'adfoin_demio_actions', 10, 1 );

function adfoin_demio_actions( $actions ) {

    $actions['demio'] = array(
        'title' => __( 'Demio', 'advanced-form-integration' ),
        'tasks' => array(
            'reg_people'   => __( 'Register people to Webinar', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_demio_settings_tab', 10, 1 );

function adfoin_demio_settings_tab( $providers ) {
    $providers['demio'] = __( 'Demio', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_demio_settings_view', 10, 1 );

function adfoin_demio_settings_view( $current_tab ) {
    if( $current_tab != 'demio' ) {
        return;
    }

    $nonce   = wp_create_nonce( 'adfoin_demio_settings' );
    $api_key = get_option( 'adfoin_demio_api_key' ) ? get_option( 'adfoin_demio_api_key' ) : '';
    $api_secret = get_option( 'adfoin_demio_api_secret' ) ? get_option( 'adfoin_demio_api_secret' ) : '';
    ?>

    <form name="demio_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_demio_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_demio_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Enter API key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Settings > API > copy API Key and API Secret', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Secret', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_demio_api_secret"
                           value="<?php echo esc_attr( $api_secret ); ?>" placeholder="<?php _e( 'Enter API Secret', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_demio_save_api_key', 'adfoin_save_demio_api_key', 10, 0 );

function adfoin_save_demio_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_demio_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key    = sanitize_text_field( $_POST["adfoin_demio_api_key"] );
    $api_secret = sanitize_text_field( $_POST["adfoin_demio_api_secret"] );

    // Save keys
    update_option( "adfoin_demio_api_key", $api_key );
    update_option( "adfoin_demio_api_secret", $api_secret );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=demio" );
}

add_action( 'adfoin_action_fields', 'adfoin_demio_action_fields' );

function adfoin_demio_action_fields() {
    ?>
    <script type="text/template" id="demio-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'reg_people'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'reg_people'">
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
 
                <tr valign="top" class="alternate" v-if="action.task == 'reg_people'">
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

add_action( 'wp_ajax_adfoin_get_demio_events', 'adfoin_get_demio_events', 10, 1 );
 
/*
 * Get demio Event list
 */
function adfoin_get_demio_events()
{
    // Security Check
    if (!wp_verify_nonce($_POST['_nonce'], 'advanced-form-integration')) {
        die(__('Security check Failed', 'advanced-form-integration'));
    }
 
    $data = adfoin_demio_request('events');
 
    if (is_wp_error($data)) {
        wp_send_json_error();
    }
  
    $body  = json_decode( wp_remote_retrieve_body( $data ) );
    $events = wp_list_pluck( $body, 'name', 'id' );

 
    wp_send_json_success($events);
}

add_action( 'wp_ajax_adfoin_get_demio_sessions', 'adfoin_get_demio_sessions', 10, 1 );
 
/*
 * Get demio Session list
 */
function adfoin_get_demio_sessions()
{
    // Security Check
    if (!wp_verify_nonce($_POST['_nonce'], 'advanced-form-integration')) {
        die(__('Security check Failed', 'advanced-form-integration'));
    }
 
    $event_id = isset( $_POST['eventId'] ) ? $_POST['eventId'] : '';
 
    $data = adfoin_demio_request('event/' . $event_id . '?active=active');
 
    if (is_wp_error($data)) {
        wp_send_json_error();
    }
 
    $body   = json_decode(wp_remote_retrieve_body( $data ), true );
    $sessions = array();
 
    foreach( $body['dates'] as $session ) {
        $sessions[$session['date_id']] = $session['datetime'];
    }
 
    wp_send_json_success($sessions);
}

function adfoin_demio_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_key = get_option( 'adfoin_demio_api_key' ) ? get_option( 'adfoin_demio_api_key' ) : '';
    $api_secret = get_option( 'adfoin_demio_api_secret' ) ? get_option( 'adfoin_demio_api_secret' ) : '';

    if(!$api_key || !$api_secret ) {
        return;
    }

    $base_url = 'https://my.demio.com/api/v1/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json',
            'Api-Key'      => $api_key,
            'Api-Secret'   => $api_secret,

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

add_action( 'adfoin_demio_job_queue', 'adfoin_demio_job_queue', 10, 1 );

function adfoin_demio_job_queue( $data ) {
    adfoin_demio_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to demio API
 */
function adfoin_demio_send_data( $record, $posted_data ) {

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
    $event_id   = $data['eventId'];
    $session_id = $data['sessionId'];

    if( $task == 'reg_people' ) {
        $email        = empty( $data['email'] ) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $name         = empty( $data['name'] ) ? '' : adfoin_get_parsed_values( $data['name'], $posted_data );
        // $last_name    = empty( $data['last_name'] ) ? '' : adfoin_get_parsed_values( $data['last_name'], $posted_data );
        // $company      = empty( $data['company'] ) ? '' : adfoin_get_parsed_values( $data['company'], $posted_data );
        // $website      = empty( $data['website'] ) ? '' : adfoin_get_parsed_values( $data['website'], $posted_data );
        // $phone_number = empty( $data['phone_number'] ) ? '' : adfoin_get_parsed_values( $data['phone_number'], $posted_data );
        // $gdpr         = empty( $data['gdpr'] ) ? '' : adfoin_get_parsed_values( $data['gdpr'], $posted_data );
        // $refUrl       = empty( $data['refUrl'] ) ? '' : adfoin_get_parsed_values( $data['refUrl'], $posted_data );
        
        $data = array(
            'id'           => $event_id,
            'date_id'      => $session_id,
            'name'         => $name,
            'email'        => $email,
            // 'last_name'    => $last_name,
            // 'company'      => $company,
            // 'website'      => $website,
            // 'phone_number' => $phone_number,
            // 'gdpr'         => $gdpr,
            // 'ref_url'      => $refUrl

        );

        $data = array_filter( $data );

        $return = adfoin_demio_request( 'event/register', 'POST', $data, $record );

    }

    return;
}