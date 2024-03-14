<?php

add_filter( 'adfoin_action_providers', 'adfoin_keap_actions', 10, 1 );

function adfoin_keap_actions( $actions ) {

    $actions['keap'] = array(
        'title' => __( 'Keap (Beta)', 'advanced-form-integration' ),
        'tasks' => array(
            'add_contact'   => __( 'Create New Contact', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_keap_settings_tab', 10, 1 );

function adfoin_keap_settings_tab( $providers ) {
    $providers['keap'] = __( 'Keap', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_keap_settings_view', 10, 1 );

function adfoin_keap_settings_view( $current_tab ) {
    if( $current_tab != 'keap' ) {
        return;
    }

    $nonce     = wp_create_nonce( "adfoin_keap_settings" );
    $api_key = get_option( 'adfoin_keap_api_key' ) ? get_option( 'adfoin_keap_api_key' ) : "";
    ?>

    <form name="keap_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_keap_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_keap_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p>
                        Go to Settings > API and generate Service Account Key
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_keap_api_key', 'adfoin_save_keap_api_key', 10, 0 );

function adfoin_save_keap_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_keap_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST["adfoin_keap_api_key"] );

    // Save tokens
    update_option( "adfoin_keap_api_key", $api_key );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=keap" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_keap_js_fields', 10, 1 );

function adfoin_keap_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_keap_action_fields' );

function adfoin_keap_action_fields() {
    ?>
    <script type="text/template" id="keap-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_contact'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

add_action( 'adfoin_keap_job_queue', 'adfoin_keap_job_queue', 10, 1 );

function adfoin_keap_job_queue( $data ) {
    adfoin_keap_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Keap API
 */
function adfoin_keap_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data['field_data'];
    $task = $record['task'];

    if( $task == 'add_contact' ) {
        $email                 = empty( $data['email'] ) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data );
        $title                 = empty( $data['title'] ) ? '' : adfoin_get_parsed_values( $data['title'], $posted_data );
        $first_name            = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data );
        $middle_name           = empty( $data['middleName'] ) ? '' : adfoin_get_parsed_values( $data['middleName'], $posted_data );
        $last_name             = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data );
        $suffix                = empty( $data['suffix'] ) ? '' : adfoin_get_parsed_values( $data['suffix'], $posted_data );
        $company               = empty( $data['company'] ) ? '' : adfoin_get_parsed_values( $data['company'], $posted_data );
        $contact_type          = empty( $data['contactType'] ) ? '' : adfoin_get_parsed_values( $data['contactType'], $posted_data );
        $optin                 = empty( $data['optin'] ) ? '' : adfoin_get_parsed_values( $data['optin'], $posted_data );
        $job_title             = empty( $data['jobTitle'] ) ? '' : adfoin_get_parsed_values( $data['jobTitle'], $posted_data );
        $website               = empty( $data['website'] ) ? '' : adfoin_get_parsed_values( $data['website'], $posted_data );
        $email2                = empty( $data['email2'] ) ? '' : adfoin_get_parsed_values( $data['email2'], $posted_data );
        $email3                = empty( $data['email3'] ) ? '' : adfoin_get_parsed_values( $data['email3'], $posted_data );
        $mobile_phone          = empty( $data['mobilePhone'] ) ? '' : adfoin_get_parsed_values( $data['mobilePhone'], $posted_data );
        $work_phone            = empty( $data['workPhone'] ) ? '' : adfoin_get_parsed_values( $data['workPhone'], $posted_data );
        $home_phone            = empty( $data['homePhone'] ) ? '' : adfoin_get_parsed_values( $data['homePhone'], $posted_data );
        $billing_street1       = empty( $data['billingStreet1'] ) ? '' : adfoin_get_parsed_values( $data['billingStreet1'], $posted_data );
        $billing_street2       = empty( $data['billingStreet2'] ) ? '' : adfoin_get_parsed_values( $data['billingStreet2'], $posted_data );
        $billing_city          = empty( $data['billingCity'] ) ? '' : adfoin_get_parsed_values( $data['billingCity'], $posted_data );
        $billing_state         = empty( $data['billingState'] ) ? '' : adfoin_get_parsed_values( $data['billingState'], $posted_data );
        $billing_zip           = empty( $data['billingZip'] ) ? '' : adfoin_get_parsed_values( $data['billingZip'], $posted_data );
        $billing_country_code  = empty( $data['billingCountryCode'] ) ? '' : adfoin_get_parsed_values( $data['billingCountryCode'], $posted_data );
        $shipping_street1      = empty( $data['shippingStreet1'] ) ? '' : adfoin_get_parsed_values( $data['shippingStreet1'], $posted_data );
        $shipping_street2      = empty( $data['shippingStreet2'] ) ? '' : adfoin_get_parsed_values( $data['shippingStreet2'], $posted_data );
        $shipping_city         = empty( $data['shippingCity'] ) ? '' : adfoin_get_parsed_values( $data['shippingCity'], $posted_data );
        $shipping_state        = empty( $data['shippingState'] ) ? '' : adfoin_get_parsed_values( $data['shippingState'], $posted_data );
        $shipping_zip          = empty( $data['shippingZip'] ) ? '' : adfoin_get_parsed_values( $data['shippingZip'], $posted_data );
        $shipping_country_code = empty( $data['shippingCountryCode'] ) ? '' : adfoin_get_parsed_values( $data['shippingCountryCode'], $posted_data );
        $birthday              = empty( $data['birthday'] ) ? '' : adfoin_get_parsed_values( $data['birthday'], $posted_data );
        $anniversary           = empty( $data['anniversary'] ) ? '' : adfoin_get_parsed_values( $data['anniversary'], $posted_data );
        $spouse_name           = empty( $data['spouseName'] ) ? '' : adfoin_get_parsed_values( $data['spouseName'], $posted_data );
        $facebook              = empty( $data['facebook'] ) ? '' : adfoin_get_parsed_values( $data['facebook'], $posted_data );
        $linkedin              = empty( $data['linkedin'] ) ? '' : adfoin_get_parsed_values( $data['linkedin'], $posted_data );
        $twitter               = empty( $data['twitter'] ) ? '' : adfoin_get_parsed_values( $data['twitter'], $posted_data );
        


        $body = array(
            'duplicate_option' => 'Email'
        );

        if( $email ) {
            $body['email_addresses'] = array(
                array(
                    'email' => trim( $email ),
                    'field' => 'EMAIL1'
                )
            );
        }

        if( $email2 ) {
            array_push( $body['email_addresses'], array( 'email' => $email2, 'field' => 'EMAIL2' ) );
        }

        if( $email3 ) {
            array_push( $body['email_addresses'], array( 'email' => $email3, 'field' => 'EMAIL3' ) );
        }

        if( $title ) { $body['title'] = $title; }
        if( $first_name ) { $body['given_name'] = $first_name; }
        if( $middle_name ) { $body['middle_name'] = $middle_name; }
        if( $last_name ) { $body['family_name'] = $last_name; }
        if( $suffix ) { $body['suffix'] = $suffix; }
        if( $contact_type ) { $body['contact_type'] = $contact_type; }
        if( $job_title ) { $body['job_title'] = $job_title; }
        if( $website ) { $body['website'] = $website; }
        if( $birthday ) { $body['birthday'] = $birthday; }
        if( $anniversary ) { $body['anniversary'] = $anniversary; }
        if( $spouse_name ) { $body['spouse_name'] = $spouse_name; }
        if( $optin ) {
            if( 'true' == $optin ) {
                $body['opt_in_reason'] = 'User opted in';
            }
        }

        if( $company ) {
            $company_id = adfoin_keap_company_exists( $company );

            if( !$company_id ) {
                $company_id = adfoin_keap_company_create( $company, $record );
            }

            if( $company_id ) {
                $body['company'] = array( 'id' => $company_id );
            }
        }

        if( $mobile_phone || $work_phone || $home_phone ) {
            $body['phone_numbers'] = array();

            if( $mobile_phone ) {
                array_push( $body['phone_numbers'], array( 'field' => 'PHONE1', 'type' => 'Mobile', 'number' => $mobile_phone ) );
            }

            if( $work_phone ) {
                array_push( $body['phone_numbers'], array( 'field' => 'PHONE2', 'type' => 'Work', 'number' => $work_phone ) );
            }

            if( $home_phone ) {
                array_push( $body['phone_numbers'], array( 'field' => 'PHONE3', 'type' => 'Home', 'number' => $home_phone ) );
            }
        }

        if( $billing_street1 || $billing_city || $billing_zip || $billing_state || $billing_country_code ) {
            $body['addresses'] = array();
            $billing_address   = array( 'field' => 'BILLING' );

            if( $billing_street1 ) {
                $billing_address['line1'] = $billing_street1;
            }

            if( $billing_street2 ) {
                $billing_address['line2'] = $billing_street2;
            }

            if( $billing_city ) {
                $billing_address['locality'] = $billing_city;
            }

            if( $billing_zip ) {
                $billing_address['zip_code'] = $billing_zip;
            }

            if( $billing_state ) {
                $billing_address['region'] = $billing_state;
            }

            if( $billing_country_code ) {
                $billing_address['country_code'] = $billing_country_code;
            }

            array_push( $body['addresses'], $billing_address );
        }

        if( $shipping_street1 || $shipping_city || $shipping_zip || $shipping_state || $shipping_country_code ) {
            if( !isset( $body['addresses'] ) ) {
                $body['addresses'] = array();
            }
            
            $shipping_address = array( 'field' => 'SHIPPING' );

            if( $shipping_street1 ) {
                $shipping_address['line1'] = $shipping_street1;
            }

            if( $shipping_street2 ) {
                $shipping_address['line2'] = $shipping_street2;
            }

            if( $shipping_city ) {
                $shipping_address['locality'] = $shipping_city;
            }

            if( $shipping_zip ) {
                $shipping_address['zip_code'] = $shipping_zip;
            }

            if( $shipping_state ) {
                $shipping_address['region'] = $shipping_state;
            }

            if( $shipping_country_code ) {
                $shipping_address['country_code'] = $shipping_country_code;
            }

            array_push( $body['addresses'], $shipping_address );
        }

        if( $facebook || $twitter || $linkedin ) {
            $body['social_accounts'] = array();

            if( $facebook ) {
                array_push( $body['social_accounts'], array( 'name' => $facebook, 'type' => 'Facebook' ) );
            }

            if( $twitter ) {
                array_push( $body['social_accounts'], array( 'name' => $twitter, 'type' => 'Twitter' ) );
            }

            if( $linkedin ) {
                array_push( $body['social_accounts'], array( 'name' => $linkedin, 'type' => 'LinkedIn' ) );
            }
        }

        $return = adfoin_keap_request( 'contacts', 'PUT', $body, $record );
    }

    return;
}

function adfoin_keap_request( $endpoint, $method, $data = array(), $record = array() ) {

    $api_key = get_option( 'adfoin_keap_api_key' ) ? get_option( 'adfoin_keap_api_key' ) : '';

    if( !$api_key ) {
        return;
    }

    $args = array(
        'method'  => $method,
        'headers' => array(
            'X-Keap-API-Key' => $api_key,
            'Content-Type'   => 'application/json'
        )
    );

    $base_url = 'https://api.infusionsoft.com/crm/rest/';
    $url      = $base_url . $endpoint;

    if( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }

    $response = wp_remote_request( $url, $args );

    if( $record ) {
        adfoin_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}

function adfoin_keap_company_exists( $company_name ) {
    if( !$company_name ) {
        return;
    }

    $endpoint      = "companies?company_name={$company_name}";
    $response      = adfoin_keap_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $company_id    = '';
    
    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( $response_body['count'] > 0 ) {
            $company_id = $response_body['companies'][0]['id'];
        }
    }

    if( $company_id ) {
        return $company_id;
    } else{
        return false;
    }
}

function adfoin_keap_company_create( $company_name, $record ) {
    if( !$company_name ) {
        return false;
    }

    $company_id    = '';
    $response      = adfoin_keap_request( 'companies', 'POST', array( 'company_name' => $company_name ), $record );
    $response_code = wp_remote_retrieve_response_code( $response );

    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( isset( $response_body['id'] ) ) {
            $company_id = $response_body['id'];
        }
    }

    if( $company_id ) {
        return $company_id;
    } else{
        return false;
    }
}
