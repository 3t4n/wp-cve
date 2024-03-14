<?php

add_filter( 'adfoin_action_providers', 'adfoin_nimble_actions', 10, 1 );

function adfoin_nimble_actions( $actions ) {

    $actions['nimble'] = array(
        'title' => __( 'Nimble (Beta)', 'advanced-form-integration' ),
        'tasks' => array(
            'add_contact'   => __( 'Add New Contact', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_nimble_settings_tab', 10, 1 );

function adfoin_nimble_settings_tab( $providers ) {
    $providers['nimble'] = __( 'Nimble', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_nimble_settings_view', 10, 1 );

function adfoin_nimble_settings_view( $current_tab ) {
    if( $current_tab != 'nimble' ) {
        return;
    }

    $nonce   = wp_create_nonce( 'adfoin_nimble_settings' );
    $api_key = get_option( 'adfoin_nimble_api_key' ) ? get_option( 'adfoin_nimble_api_key' ) : '';
    ?>

    <form name="nimble_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_nimble_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_nimble_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Please go to My Account > API Tokens and generate a token', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_nimble_save_api_key', 'adfoin_save_nimble_api_key', 10, 0 );

function adfoin_save_nimble_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_nimble_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST["adfoin_nimble_api_key"] );

    // Save tokens
    update_option( "adfoin_nimble_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=nimble" );
}

add_action( 'adfoin_action_fields', 'adfoin_nimble_action_fields' );

function adfoin_nimble_action_fields() {
    ?>
    <script type="text/template" id="nimble-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php esc_attr_e( 'Contact Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            
        </table>
    </script>
    <?php
}

function adfoin_nimble_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_key = get_option( 'adfoin_nimble_api_key' ) ? get_option( 'adfoin_nimble_api_key' ) : '';

    if(!$api_key ) {
        return;
    }

    $base_url = 'https://api.nimble.com/api/v1/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json',
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

add_action( 'adfoin_nimble_job_queue', 'adfoin_nimble_job_queue', 10, 1 );

function adfoin_nimble_job_queue( $data ) {
    adfoin_nimble_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Nimble API
 */
function adfoin_nimble_send_data( $record, $posted_data ) {

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

    if( $task == 'add_contact' ) {
        $email       = empty( $data['email'] ) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data );
        $first_name  = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );
        $last_name  = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );

        $data = array(
            'record_type' => 'person',
            'fields' => array(
                'email' => array(
                    array(
                        'value' => trim( $email )
                    )
                ),
                'first name' => array(
                    array(
                        'value' => $first_name
                    )
                ),
                'last name' => array(
                    array(
                        'value' => $last_name
                    )
                )
            )
        );

        $return = adfoin_nimble_request( 'contact', 'POST', $data, $record );

    }

    return;
}