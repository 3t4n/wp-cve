<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_omnisend_actions',
    10,
    1
);
function adfoin_omnisend_actions( $actions )
{
    $actions['omnisend'] = array(
        'title' => __( 'Omnisend', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Create New Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_omnisend_settings_tab',
    10,
    1
);
function adfoin_omnisend_settings_tab( $providers )
{
    $providers['omnisend'] = __( 'Omnisend', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_omnisend_settings_view',
    10,
    1
);
function adfoin_omnisend_settings_view( $current_tab )
{
    if ( $current_tab != 'omnisend' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_omnisend_settings' );
    $api_token = ( get_option( 'adfoin_omnisend_api_token' ) ? get_option( 'adfoin_omnisend_api_token' ) : '' );
    ?>

    <form name="omnisend_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_omnisend_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_omnisend_api_token"
                           value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Please go to Store Settings > Integrations & API > API Keys to get API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_omnisend_api_token',
    'adfoin_save_omnisend_api_token',
    10,
    0
);
function adfoin_save_omnisend_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_omnisend_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST['adfoin_omnisend_api_token'] );
    // Save tokens
    update_option( 'adfoin_omnisend_api_token', $api_token );
    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=omnisend' );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_omnisend_js_fields',
    10,
    1
);
function adfoin_omnisend_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_omnisend_action_fields' );
function adfoin_omnisend_action_fields()
{
    ?>
    <script type="text/template" id="omnisend-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Contact Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>
            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'add_contact'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock tags and custom fields consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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

add_action(
    'adfoin_omnisend_job_queue',
    'adfoin_omnisend_job_queue',
    10,
    1
);
function adfoin_omnisend_job_queue( $data )
{
    adfoin_omnisend_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Omnisend API
 */
function adfoin_omnisend_send_data( $record, $posted_data )
{
    $api_token = ( get_option( 'adfoin_omnisend_api_token' ) ? get_option( 'adfoin_omnisend_api_token' ) : '' );
    if ( !$api_token ) {
        return;
    }
    $record_data = json_decode( $record['data'], true );
    if ( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if ( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if ( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data['field_data'];
    $task = $record['task'];
    
    if ( $task == 'add_contact' ) {
        $email = ( empty($data['email']) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $first_name = ( empty($data['firstName']) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data ) );
        $last_name = ( empty($data['lastName']) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data ) );
        $phone = ( empty($data['phone']) ? '' : adfoin_get_parsed_values( $data['phone'], $posted_data ) );
        $address = ( empty($data['address']) ? '' : adfoin_get_parsed_values( $data['address'], $posted_data ) );
        $city = ( empty($data['city']) ? '' : adfoin_get_parsed_values( $data['city'], $posted_data ) );
        $state = ( empty($data['state']) ? '' : adfoin_get_parsed_values( $data['state'], $posted_data ) );
        $zip = ( empty($data['zip']) ? '' : adfoin_get_parsed_values( $data['zip'], $posted_data ) );
        $country = ( empty($data['country']) ? '' : adfoin_get_parsed_values( $data['country'], $posted_data ) );
        $birthday = ( empty($data['birthday']) ? '' : adfoin_get_parsed_values( $data['birthday'], $posted_data ) );
        $gender = ( empty($data['gender']) ? '' : adfoin_get_parsed_values( $data['gender'], $posted_data ) );
        $url = 'https://api.omnisend.com/v3/contacts';
        $headers = array(
            'X-API-KEY'    => $api_token,
            'Content-Type' => 'application/json',
        );
        $body = array(
            'firstName'   => $first_name,
            'lastName'    => $last_name,
            'address'     => $address,
            'city'        => $city,
            'state'       => $state,
            'postalCode'  => $zip,
            'country'     => $country,
            'birthdate'   => $birthday,
            'identifiers' => array( array(
            'type'     => 'email',
            'id'       => trim( $email ),
            'channels' => array(
            'email' => array(
            'status'     => 'subscribed',
            'statusDate' => date( 'c' ),
        ),
        ),
        ) ),
        );
        if ( $phone ) {
            $body['identifiers'][] = array(
                'type'     => 'phone',
                'id'       => $phone,
                'channels' => array(
                'sms' => array(
                'status'     => 'subscribed',
                'statusDate' => date( 'c' ),
            ),
            ),
            );
        }
        
        if ( $gender ) {
            $gender = ( strtolower( $gender )[0] == 'f' ? 'f' : 'm' );
            $body['gender'] = $gender;
        }
        
        $body = array_filter( $body );
        $args = array(
            'headers' => $headers,
            'body'    => json_encode( $body, true ),
        );
        $response = wp_remote_post( $url, $args );
        adfoin_add_to_log(
            $response,
            $url,
            $args,
            $record
        );
    }
    
    return;
}
