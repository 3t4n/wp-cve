<?php

add_filter( 'adfoin_action_providers', 'adfoin_customerio_actions', 10, 1 );

function adfoin_customerio_actions( $actions ) {
    $actions['customerio'] = array(
        'title' => __( 'Customer.io', 'advanced-form-integration' ),
        'tasks' => array(
        'add_people'     => __( 'Add People', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_customerio_settings_tab', 10, 1 );

function adfoin_customerio_settings_tab( $providers ) {
    $providers['customerio'] = __( 'Customer.io', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_customerio_settings_view', 10, 1 );

function adfoin_customerio_settings_view( $current_tab ) {
    if ( $current_tab != 'customerio' ) {
        return;
    }

    $nonce   = wp_create_nonce( 'adfoin_customerio_settings' );
    // $region  = get_option( 'adfoin_customerio_region' ) ? get_option( 'adfoin_customerio_region' ) : '';
    $site_id = get_option( 'adfoin_customerio_site_id' ) ? get_option( 'adfoin_customerio_site_id' ) : '';
    $api_key = get_option( 'adfoin_customerio_api_key' ) ? get_option( 'adfoin_customerio_api_key' ) : '';
    ?>

    <form name="customerio_save_form" action="<?php echo  esc_url( admin_url( 'admin-post.php' ) ) ;?>" method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_customerio_site_id">
        <input type="hidden" name="_nonce" value="<?php echo  $nonce ; ?>"/>

        <table class="form-table">
        <!-- <tr valign="top">
            <th scope="row"> <?php //_e( 'Region', 'advanced-form-integration' ); ?></th>
            <td>
                <input type="text" name="adfoin_customerio_region" value="<?php //echo  $region; ?>" placeholder="<?php _e( 'Enter region you selected', 'advanced-form-integration' ); ?>" class="regular-text"/>
                <p>
                    Go to Settings > Account Settings > Privacy & Data > Data Center
                </p>
            </td>
        </tr> -->
        <tr valign="top">
            <th scope="row"> <?php _e( 'Site ID', 'advanced-form-integration' ); ?></th>
            <td>
                <input type="text" name="adfoin_customerio_site_id" value="<?php echo esc_attr( $site_id );?>" placeholder="<?php _e( 'Enter Site ID', 'advanced-form-integration' ); ?>" class="regular-text"/>
                <p>
                    Go to Settings > Account Settings > API Credentials
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
            <td>
                <input type="text" name="adfoin_customerio_api_key" value="<?php echo esc_attr( $api_key );?>" placeholder="<?php _e( 'Enter API Key', 'advanced-form-integration' ); ?>" class="regular-text"/>
                <p>
                    Go to Settings > Account Settings > API Credentials
                </p>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
    </form>
    <?php 
}

add_action( 'admin_post_adfoin_save_customerio_site_id', 'adfoin_save_customerio_site_id', 10, 0 );

function adfoin_save_customerio_site_id() {
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_customerio_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    // $region    = sanitize_text_field( $_POST['adfoin_customerio_region'] );
    $site_id = sanitize_text_field( $_POST['adfoin_customerio_site_id'] );
    $api_key = sanitize_text_field( $_POST['adfoin_customerio_api_key'] );

    // Save tokens
    update_option( 'adfoin_customerio_site_id', $site_id );
    update_option( 'adfoin_customerio_api_key', $api_key );

    $args = array(
        'headers' => array(
            'Authorization' => 'Basic '. base64_encode( $site_id .':'. $api_key)
        )
    );

    $url = 'https://track.customer.io/api/v1/accounts/region';
 
    $response = wp_remote_get( $url, $args );
    $body = wp_remote_retrieve_body( $response, true );

    if( isset( $body['region'] ) ) {
        $region = $body['region'];

        update_option( 'adfoin_customerio_region', $region );
    }

    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=customerio' );
}

add_action( 'adfoin_action_fields', 'adfoin_customerio_action_fields' );

function adfoin_customerio_action_fields() {
    ?>
    <script type="text/template" id="customerio-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_people'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' );?>
                </th>
                <td scope="row"></td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>


<?php 
}

/*
 * customerio API Call
 */
function adfoin_customerio_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
 
    $region    = get_option( 'adfoin_customerio_region' ) ? get_option( 'adfoin_customerio_region' ) : '';
    $site_id    = get_option( 'adfoin_customerio_site_id' ) ? get_option( 'adfoin_customerio_site_id' ) : '';
    $api_key = get_option( 'adfoin_customerio_api_key' ) ? get_option( 'adfoin_customerio_api_key' ) : '';
 
    if( !$region || !$site_id || !$api_key ) {
        return;
    }
 
    $base_url = "https://track-{$region}.customer.io/api/v1/";
    $url      = $base_url . $endpoint;
 
    $args = array(
        'method'  => $method,
        'headers' => array(
            'Authorization' => 'Basic '. base64_encode( $site_id .':'. $api_key)
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

add_action( 'adfoin_customerio_job_queue', 'adfoin_customerio_job_queue', 10, 1 );

function adfoin_customerio_job_queue( $data ) {
    adfoin_customerio_send_data( $data['record'], $data['posted_data'] );
}
 
/*
 * Handles sending data to customerio API
 */
function adfoin_customerio_send_data( $record, $posted_data ) {
 
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

    if( $task == 'add_people' ){
        $email        = empty( $data['email'] ) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $firstName    = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );       
        $lastName     = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );
 
        $data = array(
            'email'      => $email,
            'first_name'    => $firstName,
            'last_name'     => $lastName,               
            
        );
            
        $return = adfoin_customerio_request( 'customers/' . $email , 'PUT', $data, $record );
    }
 
    return;
}