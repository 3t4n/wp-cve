<?php

add_filter( 'adfoin_action_providers', 'adfoin_companyhub_actions', 10, 1 );

function adfoin_companyhub_actions( $actions ) {

    $actions['companyhub'] = array(
        'title' => __( 'CompanyHub (Beta)', 'advanced-form-integration' ),
        'tasks' => array(
            'add_contact'   => __( 'Add New Contact', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_companyhub_settings_tab', 10, 1 );

function adfoin_companyhub_settings_tab( $providers ) {
    $providers['companyhub'] = __( 'CompanyHub', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_companyhub_settings_view', 10, 1 );

function adfoin_companyhub_settings_view( $current_tab ) {
    if( $current_tab != 'companyhub' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_companyhub_settings' );
    $api_key   = get_option( 'adfoin_companyhub_api_key' ) ? get_option( 'adfoin_companyhub_api_key' ) : '';
    $subdomain = get_option( 'adfoin_companyhub_subdomain' ) ? get_option( 'adfoin_companyhub_subdomain' ) : '';
    ?>

    <form name="companyhub_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_companyhub_save_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
        <tr valign="top">
                <th scope="row"> <?php _e( 'Subdomain', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_companyhub_subdomain"
                           value="<?php echo esc_attr( $subdomain ); ?>" placeholder="<?php _e( 'Please enter subdomain', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_companyhub_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                           <p class="description" id="code-description"><?php _e( 'Go to Settings > Integrations and click on the Generate API Key button.', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_companyhub_save_api_key', 'adfoin_save_companyhub_api_key', 10, 0 );

function adfoin_save_companyhub_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_companyhub_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $subdomain = sanitize_text_field( $_POST["adfoin_companyhub_subdomain"] );
    $api_key   = sanitize_text_field( $_POST["adfoin_companyhub_api_key"] );

    // Save tokens
    update_option( "adfoin_companyhub_subdomain", $subdomain );
    update_option( "adfoin_companyhub_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=companyhub" );
}

add_action( 'adfoin_action_fields', 'adfoin_companyhub_action_fields' );

function adfoin_companyhub_action_fields() {
    ?>
    <script type="text/template" id="companyhub-action-template">
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

function adfoin_companyhub_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_key   = get_option( 'adfoin_companyhub_api_key' ) ? get_option( 'adfoin_companyhub_api_key' ) : '';
    $subdomain = get_option( 'adfoin_companyhub_subdomain' ) ? get_option( 'adfoin_companyhub_subdomain' ) : '';

    $base_url = 'https://api.companyhub.com/v1/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => $subdomain . ' ' . $api_key
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

add_action( 'adfoin_companyhub_job_queue', 'adfoin_companyhub_job_queue', 10, 1 );

function adfoin_companyhub_job_queue( $data ) {
    adfoin_companyhub_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to CompanyHub API
 */
function adfoin_companyhub_send_data( $record, $posted_data ) {

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
        $email       = empty( $data['email'] ) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $first_name  = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );
        $last_name  = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );
        // $comapny    = empty( $data['company'] ) ? '' : adfoin_get_parsed_values( $data['company'], $posted_data );

        // $comapny_data = array(
        //     'Name' => $comapny
        // );

        // $company_api_return = adfoin_companyhub_request( 'tables/company', 'POST', $comapny_data, $record );
        // $company_body = json_decode( wp_remote_retrieve_body( $company_api_return ), true );
        // $company_id = $company_body['Id'];
        
        $data = array(
            'FirstName' => $first_name,
            'LastName'  => $last_name,
            'Email'     => $email
        );

        // if( $company_id ) {
        //     $data['Company'] = array( 'ID' => $company_id );
        // }

        $return = adfoin_companyhub_request( 'tables/contact', 'POST', $data, $record );

    }

    return;
}