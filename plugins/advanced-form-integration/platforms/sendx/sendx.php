<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_sendx_actions',
    10,
    1
);
function adfoin_sendx_actions( $actions )
{
    $actions['sendx'] = array(
        'title' => __( 'SendX', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Add Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_sendx_settings_tab',
    10,
    1
);
function adfoin_sendx_settings_tab( $providers )
{
    $providers['sendx'] = __( 'SendX', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_sendx_settings_view',
    10,
    1
);
function adfoin_sendx_settings_view( $current_tab )
{
    if ( $current_tab != 'sendx' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_sendx_settings" );
    $team_id = ( get_option( 'adfoin_sendx_team_id' ) ? get_option( 'adfoin_sendx_team_id' ) : '' );
    $api_key = ( get_option( 'adfoin_sendx_api_key' ) ? get_option( 'adfoin_sendx_api_key' ) : '' );
    ?>

    <form name="sendx_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_sendx_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Team ID', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_sendx_team_id"
                           value="<?php 
    echo  esc_attr( $team_id ) ;
    ?>" placeholder="<?php 
    _e( 'Enter Team ID', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to <a target="_blank" rel="noopener noreferrer" href="https://app.sendx.io/setting">SendX settings page</a> and scroll down to the bottom.', 'advanced-form-integration' );
    ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_sendx_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
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
    'admin_post_adfoin_save_sendx_api_key',
    'adfoin_save_sendx_api_key',
    10,
    0
);
function adfoin_save_sendx_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_sendx_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $team_id = sanitize_text_field( $_POST['adfoin_sendx_team_id'] );
    $api_key = sanitize_text_field( $_POST['adfoin_sendx_api_key'] );
    // Save tokens
    update_option( 'adfoin_sendx_team_id', $team_id );
    update_option( 'adfoin_sendx_api_key', $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=sendx" );
}

add_action( 'adfoin_action_fields', 'adfoin_sendx_action_fields' );
function adfoin_sendx_action_fields()
{
    ?>
    <script type="text/template" id="sendx-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                    <tr valign="top" v-if="action.task == 'subscribe'">
                        <th scope="row">
                            <?php 
        esc_attr_e( 'Go Pro', 'advanced-form-integration' );
        ?>
                        </th>
                        <td scope="row">
                            <span><?php 
        printf( __( 'To unlock custom fields, consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
 * SendX API Request
 */
function adfoin_sendx_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $team_id = ( get_option( 'adfoin_sendx_team_id' ) ? get_option( 'adfoin_sendx_team_id' ) : '' );
    $api_key = ( get_option( 'adfoin_sendx_api_key' ) ? get_option( 'adfoin_sendx_api_key' ) : '' );
    $base_url = 'http://app.sendx.io/api/v1/';
    $url = $base_url . $endpoint;
    $url = add_query_arg( array(
        'team_id' => $team_id,
    ), $url );
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type' => 'application/json',
        'api_key'      => $api_key,
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

add_action(
    'adfoin_sendx_job_queue',
    'adfoin_sendx_job_queue',
    10,
    1
);
function adfoin_sendx_job_queue( $data )
{
    adfoin_sendx_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to SendX API
 */
function adfoin_sendx_send_data( $record, $posted_data )
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
    
    if ( $task == 'subscribe' ) {
        $email = ( empty($data['email']) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $first_name = ( empty($data['firstName']) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data ) );
        $last_name = ( empty($data['lastName']) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data ) );
        $company = ( empty($data['company']) ? '' : adfoin_get_parsed_values( $data['company'], $posted_data ) );
        $birthday = ( empty($data['birthday']) ? '' : adfoin_get_parsed_values( $data['birthday'], $posted_data ) );
        $contact_data = array(
            'email'     => trim( $email ),
            'firstName' => $first_name,
            'lastName'  => $last_name,
            'company'   => $company,
            'birthday'  => $birthday,
        );
        $return = adfoin_sendx_request(
            'contact/identify',
            'POST',
            $contact_data,
            $record
        );
    }

}
