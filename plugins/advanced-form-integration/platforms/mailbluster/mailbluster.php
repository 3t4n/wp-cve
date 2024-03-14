<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mailbluster_actions',
    10,
    1
);
function adfoin_mailbluster_actions( $actions )
{
    $actions['mailbluster'] = array(
        'title' => __( 'MailBluster', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Create New Lead', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mailbluster_settings_tab',
    10,
    1
);
function adfoin_mailbluster_settings_tab( $providers )
{
    $providers['mailbluster'] = __( 'MailBluster', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mailbluster_settings_view',
    10,
    1
);
function adfoin_mailbluster_settings_view( $current_tab )
{
    if ( $current_tab != 'mailbluster' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_mailbluster_settings' );
    $api_token = ( get_option( 'adfoin_mailbluster_api_token' ) ? get_option( 'adfoin_mailbluster_api_token' ) : '' );
    ?>

    <form name="mailbluster_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_mailbluster_api_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mailbluster_api_token"
                           value="<?php 
    echo  esc_attr( $api_token ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to Settings > API Keys and create new API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_mailbluster_api_token',
    'adfoin_save_mailbluster_api_token',
    10,
    0
);
function adfoin_save_mailbluster_api_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailbluster_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_token = sanitize_text_field( $_POST['adfoin_mailbluster_api_token'] );
    // Save tokens
    update_option( 'adfoin_mailbluster_api_token', $api_token );
    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=mailbluster' );
}

add_action( 'adfoin_action_fields', 'adfoin_mailbluster_action_fields' );
function adfoin_mailbluster_action_fields()
{
    ?>
    <script type="text/template" id="mailbluster-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Lead Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_contact'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Double Opt-In', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="fieldData[doptin]" value="true" v-model="fielddata.doptin">
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

/*
 * Mailbluster API Request
 */
function adfoin_mailbluster_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_key = get_option( 'adfoin_mailbluster_api_token' );
    $base_url = 'https://api.mailbluster.com/api/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => $api_key,
    ),
    );
    if ( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }
    $response = wp_remote_request( $url, $args );
    if ( $record ) {
        adfoin_add_to_log(
            $response,
            $url,
            $args,
            $record
        );
    }
    return $response;
}

/*
* Check if lead exists
*/
function adfoin_mailbluster_lead_exists( $hash )
{
    if ( !$hash ) {
        return false;
    }
    $return = adfoin_mailbluster_request( 'leads/' . $hash );
    
    if ( $return['response']['code'] == 200 ) {
        return true;
    } else {
        return false;
    }

}

add_action(
    'adfoin_mailbluster_job_queue',
    'adfoin_mailbluster_job_queue',
    10,
    1
);
function adfoin_mailbluster_job_queue( $data )
{
    adfoin_mailbluster_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to MailBluster API
 */
function adfoin_mailbluster_send_data( $record, $posted_data )
{
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
        $basic_fields = array(
            'email',
            'firstName',
            'lastName',
            'fullName',
            'timezone',
            'ipAddress'
        );
        $body = array(
            'subscribed' => true,
        );
        $doptin = ( isset( $data['doptin'] ) ? $data['doptin'] : '' );
        unset( $data['doptin'] );
        foreach ( $basic_fields as $field ) {
            
            if ( isset( $data[$field] ) ) {
                $parsed_field = adfoin_get_parsed_values( $data[$field], $posted_data );
                if ( $parsed_field ) {
                    $body[$field] = $parsed_field;
                }
            }
        
        }
        if ( $doptin ) {
            $body['doubleOptIn'] = true;
        }
        
        if ( $body ) {
            $email_hash = md5( $body['email'] );
            
            if ( adfoin_mailbluster_lead_exists( $email_hash ) ) {
                adfoin_mailbluster_request(
                    'leads/' . $email_hash,
                    'PUT',
                    $body,
                    $record
                );
            } else {
                adfoin_mailbluster_request(
                    'leads',
                    'POST',
                    $body,
                    $record
                );
            }
        
        }
    
    }
    
    return;
}
