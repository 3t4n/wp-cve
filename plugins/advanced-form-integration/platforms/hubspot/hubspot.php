<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_hubspot_actions',
    10,
    1
);
function adfoin_hubspot_actions( $actions )
{
    $actions['hubspot'] = array(
        'title' => __( 'Hubspot CRM', 'advanced-form-integration' ),
        'tasks' => array(
        'add_contact' => __( 'Create New Contact', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_hubspot_settings_tab',
    10,
    1
);
function adfoin_hubspot_settings_tab( $providers )
{
    $providers['hubspot'] = __( 'Hubspot CRM', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_hubspot_settings_view',
    10,
    1
);
function adfoin_hubspot_settings_view( $current_tab )
{
    if ( $current_tab != 'hubspot' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_hubspot_settings" );
    $access_token = ( get_option( 'adfoin_hubspot_access_token' ) ? get_option( 'adfoin_hubspot_access_token' ) : "" );
    ?>

    <form name="hubspot_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_hubspot_access_token">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'Access Token', 'advanced-form-integration' );
    ?></th>
                


                <?php 
    
    if ( adfoin_fs()->is_not_paying() ) {
        ?>
                        <td>
                    <input type="text" name="adfoin_hubspot_access_token"
                           value="<?php 
        echo  esc_attr( $access_token ) ;
        ?>" placeholder="<?php 
        _e( 'Please enter Access Token', 'advanced-form-integration' );
        ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
        _e( '1. Go to Settings > Integrations > Private Apps.<br>
                    2. Click on the <b>Create a private app</b> button.<br>
                    3. Go to the Scopes tab, under CRM section mark both checkboxes for <b>crm.objects.contacts</b>.<br>
                    4. Click on the Create button.<br>
                    5. Click on <b>Show token</b> and copy the key.', 'advanced-form-integration' );
        ?></p>
                </td>
                        <?php 
    }
    
    ?>

            </tr>
        </table>
        <?php 
    submit_button();
    ?>
    </form>

    <?php 
}

add_action(
    'admin_post_adfoin_save_hubspot_access_token',
    'adfoin_save_hubspot_access_token',
    10,
    0
);
function adfoin_save_hubspot_access_token()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_hubspot_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $access_token = sanitize_text_field( $_POST["adfoin_hubspot_access_token"] );
    // Save tokens
    update_option( "adfoin_hubspot_access_token", $access_token );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=hubspot" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_hubspot_js_fields',
    10,
    1
);
function adfoin_hubspot_js_fields( $field_data )
{
}

add_action(
    'adfoin_action_fields',
    'adfoin_hubspot_action_fields',
    10,
    1
);
function adfoin_hubspot_action_fields()
{
    ?>
    <script type="text/template" id="hubspot-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php 
}

function adfoin_hubspot_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = ( get_option( 'adfoin_hubspot_api_token' ) ? get_option( 'adfoin_hubspot_api_token' ) : '' );
    $access_token = ( get_option( 'adfoin_hubspot_access_token' ) ? get_option( 'adfoin_hubspot_access_token' ) : '' );
    $args = array(
        'method'  => $method,
        'timeout' => 30,
        'headers' => array(
        'Content-Type' => 'application/json',
    ),
    );
    $base_url = 'https://api.hubapi.com/crm/v3/';
    $url = $base_url . $endpoint;
    
    if ( $access_token ) {
        $args['headers']['Authorization'] = 'Bearer ' . $access_token;
    } else {
        $url = add_query_arg( array(
            'hapikey' => $api_token,
        ), $url );
    }
    
    if ( 'POST' == $method || 'PUT' == $method || 'PATCH' == $method ) {
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
    'wp_ajax_adfoin_get_hubspot_contact_fields',
    'adfoin_get_hubspot_contact_fields',
    10,
    0
);
/*
 * Get hubspot Peson Fields
 */
function adfoin_get_hubspot_contact_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $contact_fields = array();
    $data = adfoin_hubspot_request( 'properties/contacts' );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    if ( isset( $body->results ) && is_array( $body->results ) ) {
        foreach ( $body->results as $single ) {
            
            if ( false == $single->modificationMetadata->readOnlyValue ) {
                $description = $single->description;
                if ( $single->options ) {
                    
                    if ( is_array( $single->options ) ) {
                        $description .= " Possible values are: ";
                        $values = wp_list_pluck( $single->options, 'value' );
                        $description .= implode( ' | ', $values );
                    }
                
                }
                array_push( $contact_fields, array(
                    'key'         => $single->name,
                    'value'       => $single->label,
                    'description' => $description,
                ) );
            }
        
        }
    }
    wp_send_json_success( $contact_fields );
}

add_action(
    'adfoin_hubspot_job_queue',
    'adfoin_hubspot_job_queue',
    10,
    1
);
function adfoin_hubspot_job_queue( $data )
{
    adfoin_hubspot_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Hubspot API
 */
function adfoin_hubspot_send_data( $record, $posted_data )
{
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data["field_data"];
    $task = $record["task"];
    
    if ( $task == "add_contact" ) {
        $holder = array();
        $contact_id = '';
        $method = 'POST';
        $endpoint = 'objects/contacts';
        if ( $data ) {
            foreach ( $data as $key => $value ) {
                $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
            }
        }
        $email = ( isset( $holder['email'] ) ? $holder['email'] : '' );
        
        if ( $email ) {
            $contact_id = adfoin_hubspot_contact_exists( $email );
            
            if ( $contact_id ) {
                $method = 'PATCH';
                $endpoint = "objects/contacts/{$contact_id}";
            }
        
        }
        
        $body = array(
            'properties' => array_filter( $holder ),
        );
        $response = adfoin_hubspot_request(
            $endpoint,
            $method,
            $body,
            $record
        );
    }
    
    return;
}

function adfoin_hubspot_contact_exists( $email )
{
    $data = array(
        'filterGroups' => array( array(
        'filters' => array( array(
        'value'        => $email,
        'propertyName' => 'email',
        'operator'     => 'EQ',
    ) ),
    ) ),
    );
    $result = adfoin_hubspot_request( 'objects/contacts/search', 'POST', $data );
    
    if ( 200 == wp_remote_retrieve_response_code( $result ) ) {
        $body = json_decode( wp_remote_retrieve_body( $result ), true );
        if ( isset( $body['total'] ) && $body['total'] > 0 ) {
            return $body['results'][0]['id'];
        }
    }
    
    return false;
}
