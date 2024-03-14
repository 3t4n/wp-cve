<?php

add_filter( 'adfoin_action_providers', 'adfoin_beehiiv_actions', 10, 1 );

function adfoin_beehiiv_actions( $actions ) {

    $actions['beehiiv'] = array(
        'title' => __( 'beehiiv', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Subscribe To Publication', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_beehiiv_settings_tab', 10, 1 );

function adfoin_beehiiv_settings_tab( $providers ) {
    $providers['beehiiv'] = __( 'beehiiv', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_beehiiv_settings_view', 10, 1 );

function adfoin_beehiiv_settings_view( $current_tab ) {
    if( $current_tab != 'beehiiv' ) {
        return;
    }

    $nonce     = wp_create_nonce( "adfoin_beehiiv_settings" );
    $api_key = get_option( 'adfoin_beehiiv_api_key' ) ? get_option( 'adfoin_beehiiv_api_key' ) : "";
    ?>

    <form name="beehiiv_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_beehiiv_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_beehiiv_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Please go to Account Settings > Advanced to get API Key', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_beehiiv_save_api_key', 'adfoin_save_beehiiv_api_key', 10, 0 );

function adfoin_save_beehiiv_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_beehiiv_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST["adfoin_beehiiv_api_key"] );

    // Save tokens
    update_option( "adfoin_beehiiv_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=beehiiv" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_beehiiv_js_fields', 10, 1 );

function adfoin_beehiiv_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_beehiiv_action_fields' );

function adfoin_beehiiv_action_fields() {
    ?>
    <script type="text/template" id="beehiiv-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Subscriber Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Sequence', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
                        <option value=""> <?php _e( 'Select Publication...', 'advanced-form-integration' ); ?> </option>
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

add_action( 'wp_ajax_adfoin_get_beehiiv_list', 'adfoin_get_beehiiv_list', 10, 0 );
/*
 * Get beehiiv subscriber lists
 */
function adfoin_get_beehiiv_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $data = adfoin_beehiiv_request( 'publications' );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $data ) );
        $lists = wp_list_pluck( $body->data, 'name', 'id' );

        wp_send_json_success( $lists );
    }
}

add_action( 'adfoin_beehiiv_job_queue', 'adfoin_beehiiv_job_queue', 10, 1 );

function adfoin_beehiiv_job_queue( $data ) {
    adfoin_beehiiv_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to beehiiv API
 */
function adfoin_beehiiv_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( 'cl', $record_data['action_data']) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data['field_data'];
    $task = $record['task'];

    if( $task == 'subscribe' ) {
        $publication_id = isset( $data['listId'] ) ? $data['listId'] : '';
        $subscriber_data = array();

        if( !empty( $data['email'] ) ) { $subscriber_data['email'] = trim( adfoin_get_parsed_values( $data['email'], $posted_data ) ); }
        if( !empty( $data['utm_source'] ) ) { $subscriber_data['utm_source'] = adfoin_get_parsed_values( $data['utm_source'], $posted_data ); }
        if( !empty( $data['utm_campaign'] ) ) { $subscriber_data['utm_campaign'] = adfoin_get_parsed_values( $data['utm_campaign'], $posted_data ); }
        if( !empty( $data['utm_medium'] ) ) { $subscriber_data['utm_medium'] = adfoin_get_parsed_values( $data['utm_medium'], $posted_data ); }
        if( !empty( $data['referring_site'] ) ) { $subscriber_data['referring_site'] = adfoin_get_parsed_values( $data['referring_site'], $posted_data ); }
        
        $subscriber_data = array_filter( $subscriber_data );

        if( $publication_id ) {
            $return = adfoin_beehiiv_request( "publications/{$publication_id}/subscriptions", 'POST', $subscriber_data, $record );
        }
    }

    return;
}

function adfoin_beehiiv_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_key = get_option( 'adfoin_beehiiv_api_key' ) ? get_option( 'adfoin_beehiiv_api_key' ) : '';

    $base_url = 'https://api.beehiiv.com/v2/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $api_key
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