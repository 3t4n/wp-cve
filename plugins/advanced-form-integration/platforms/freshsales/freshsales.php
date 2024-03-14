<?php

add_filter( 'adfoin_action_providers', 'adfoin_freshsales_actions', 10, 1 );

function adfoin_freshsales_actions( $actions ) {

    $actions['freshsales'] = array(
        'title' => __( 'Freshworks CRM', 'advanced-form-integration' ),
        'tasks' => array(
            'add_ocdna' => __( 'Create New Account, Contact, Deal', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_freshsales_settings_tab', 10, 1 );

function adfoin_freshsales_settings_tab( $providers ) {
    $providers['freshsales'] = __( 'Freshworks CRM', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_freshsales_settings_view', 10, 1 );

function adfoin_freshsales_settings_view( $current_tab ) {
    if( $current_tab != 'freshsales' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_freshsales_settings' );
    $api_key   = get_option( 'adfoin_freshsales_api_key' ) ? get_option( 'adfoin_freshsales_api_key' ) : '';
    $subdomain = get_option( 'adfoin_freshsales_subdomain' ) ? get_option( 'adfoin_freshsales_subdomain' ) : '';
    ?>

    <form name="freshsales_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_freshsales_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'Subdomain', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_freshsales_subdomain"
                           value="<?php echo esc_attr( $subdomain ); ?>" placeholder="<?php _e( 'Please enter Subdomain', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p>
                        1. This is subdomain part of Freshsales app url<br>
                        2. For example: if app url is 'https://nasirahmed.myfreshworks.com' <br>
                        3. Copy 'nasirahmed' and paste above
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_freshsales_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter Acess Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p>
                        1. Click on your profile picture and select Profile Settings. <br>
                        2. Click on the API Settings tab. <br>
                        4. Copy the CRM API Key and paste above. Don't copy the chat API.
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_freshsales_api_key', 'adfoin_save_freshsales_api_key', 10, 0 );

function adfoin_save_freshsales_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_freshsales_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key   = sanitize_text_field( $_POST["adfoin_freshsales_api_key"] );
    $subdomain = sanitize_text_field( $_POST["adfoin_freshsales_subdomain"] );

    // Save tokens
    update_option( "adfoin_freshsales_api_key", $api_key );
    update_option( "adfoin_freshsales_subdomain", $subdomain );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=freshsales" );
}

add_action( 'adfoin_add_js_fields', 'adfoin_freshsales_js_fields', 10, 1 );

function adfoin_freshsales_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_freshsales_action_fields' );

function adfoin_freshsales_action_fields() {
    ?>
    <script type="text/template" id="freshsales-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_ocdna'">
                <th scope="row">
                    <?php esc_attr_e( 'Freshsales Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>

    <?php
}

function adfoin_freshsales_request( $endpoint, $method, $data = array(), $record = array() ) {

    $api_key   = get_option( 'adfoin_freshsales_api_key' ) ? get_option( 'adfoin_freshsales_api_key' ) : '';
    $subdomain = get_option( 'adfoin_freshsales_subdomain' ) ? get_option( 'adfoin_freshsales_subdomain' ) : '';

    if( !$api_key || !$subdomain ) {
        return array();
    }

    $args = array(
        'method' => $method,
        'headers' => array(
            'Authorization' => "Token token={$api_key}",
            'Content-Type'  => 'application/json'
        )
    );
    $base_url = "https://{$subdomain}.myfreshworks.com/crm/sales/api/";
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

function adfoin_freshsales_if_contact_exists( $email ) {
    $contact_id = '';
    $endpoint   = "search?q={$email}&include=contact";

    $data = adfoin_freshsales_request( $endpoint, 'GET' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( wp_remote_retrieve_body( $data ), true );

    if( isset( $body[0], $body[0]['id'] ) ) {
        $contact_id = $body[0]['id'];
    }

    return $contact_id;
}

add_action( 'wp_ajax_adfoin_get_freshsales_account_fields', 'adfoin_get_freshsales_account_fields', 10, 0 );

/*
 * Get Freshsales Account Fields
 */
function adfoin_get_freshsales_account_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $ignore_list = array( 
        'parent_sales_account_id',
        'last_contacted',
        'last_contacted_mode', 
        'last_contacted_sales_activity_mode',
        'last_contacted_via_sales_activity',
        'active_sales_sequences',
        'completed_sales_sequences',
        'creater_id',
        'created_at',
        'updater_id',
        'updated_at',
        'last_assigned_at',
        'recent_note'
    );

    $data = adfoin_freshsales_request( 'settings/sales_accounts/fields', 'GET' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body           = json_decode( wp_remote_retrieve_body( $data ) );
    $account_fields = array();

    foreach( $body->fields as $single ) {
        $description = '';

        if( !in_array( $single->name, $ignore_list ) ) {

            if( isset( $single->choices ) && !empty( $single->choices ) ) {
                $parts = array();
                foreach( $single->choices as $single_choice ) {
                    $parts[] = $single_choice->value . ': ' . $single_choice->id;
                }

                $description = implode( ', ', $parts );
            }

            array_push( $account_fields, array( 'key' => 'account_' . $single->name, 'value' => $single->label . ' [Account]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $account_fields );
}

add_action( 'wp_ajax_adfoin_get_freshsales_contact_fields', 'adfoin_get_freshsales_contact_fields', 10, 0 );

/*
 * Get Freshsales Contact Fields
 */
function adfoin_get_freshsales_contact_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $ignore_list = array( 
        'sales_accounts',
        'emails',
        'time_zone', 
        'phone_numbers',
        'campaign_id',
        'last_contacted',
        'last_contacted_mode',
        'last_contacted_sales_activity_mode',
        'last_contacted_via_sales_activity',
        'active_sales_sequences',
        'completed_sales_sequences',
        'last_seen',
        'customer_fit',
        'creater_id',
        'created_at',
        'updater_id',
        'updated_at',
        'web_form_ids',
        'last_assigned_at',
        'lost_reason_id',
        'contact_status_id',
        'recent_note'
    );

    $data = adfoin_freshsales_request( 'settings/contacts/fields', 'GET' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body           = json_decode( wp_remote_retrieve_body( $data ) );
    $contact_fields = array(
        array( 'key' => 'contact_email', 'value' => 'Email [Contact]', 'description' => 'Required' )
    );

    foreach( $body->fields as $single ) {
        $description = '';

        if( !in_array( $single->name, $ignore_list ) ) {

            if( isset( $single->choices ) && !empty( $single->choices ) ) {
                $parts = array();
                foreach( $single->choices as $single_choice ) {
                    $parts[] = $single_choice->value . ': ' . $single_choice->id;
                }

                $description = implode( ', ', $parts );
            }

            array_push( $contact_fields, array( 'key' => 'contact_' . $single->name, 'value' => $single->label . ' [Contact]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $contact_fields );
}

add_action( 'wp_ajax_adfoin_get_freshsales_deal_fields', 'adfoin_get_freshsales_deal_fields', 10, 0 );

/*
 * Get Freshsales Deal Fields
 */
function adfoin_get_freshsales_deal_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $ignore_list = array( 
        'sales_account_id',
        'contacts',
        'deal_reason_id', 
        'closed_date',
        'campaign_id',
        'last_contacted_sales_activity_mode',
        'last_contacted_via_sales_activity',
        'active_sales_sequences',
        'completed_sales_sequences',
        'creater_id',
        'created_at',
        'updater_id',
        'updated_at',
        'web_form_ids',
        'upcoming_activities_time',
        'stage_updated_time',
        'last_assigned_at',
        'web_form_id',
        'recent_note'
    );

    $data = adfoin_freshsales_request( 'settings/deals/fields', 'GET' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body        = json_decode( wp_remote_retrieve_body( $data ) );
    $deal_fields = array();

    foreach( $body->fields as $single ) {
        $description = '';

        if( !in_array( $single->name, $ignore_list ) ) {

            if( isset( $single->choices ) && !empty( $single->choices ) ) {
                $parts = array();
                foreach( $single->choices as $single_choice ) {
                    $parts[] = $single_choice->value . ': ' . $single_choice->id;
                }

                $description = implode( ', ', $parts );
            }

            array_push( $deal_fields, array( 'key' => 'deal_' . $single->name, 'value' => $single->label . ' [Deal]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $deal_fields );
}

add_action( 'adfoin_freshsales_job_queue', 'adfoin_freshsales_job_queue', 10, 1 );

function adfoin_freshsales_job_queue( $data ) {
    adfoin_freshsales_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Freshsales API
 */
function adfoin_freshsales_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data       = $record_data['field_data'];
    $task       = $record['task'];
    $account_id = '';
    $contact_id = '';
    $deal_id    = '';

    if( $task == 'add_ocdna' ) {

        $holder                = array();
        $account_fields        = array();
        $account_custom_fields = array();
        $contact_fields        = array();
        $contact_custom_fields = array();
        $deal_fields           = array();
        $deal_custom_fields    = array();

        foreach( $data as $key => $value ) {
            $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
        }

        foreach( $holder as $key => $value ) {
            if( substr( $key, 0, 8 ) == 'account_' && $value ) {
                $key = substr( $key, 8 );

                if( substr( $key, 0, 3 ) == 'cf_' ) {
                    $account_custom_fields[$key] = $value;
                    continue;
                }

                $account_fields[$key] = $value;
                continue;
            }

            if( substr( $key, 0, 8 ) == 'contact_' && $value ) {
                $key = substr( $key, 8 );

                if( substr( $key, 0, 3 ) == 'cf_' ) {
                    $contact_custom_fields[$key] = $value;
                    continue;
                }

                $contact_fields[$key] = $value;
                continue;
            }

            if( substr( $key, 0, 5 ) == 'deal_' && $value ) {
                $key = substr( $key, 5 );

                if( substr( $key, 0, 3 ) == 'cf_' ) {
                    $deal_custom_fields[$key] = $value;
                    continue;
                }

                $deal_fields[$key] = $value;
                continue;
            }
        }

        if( !empty( $account_fields ) ) {

            if( !empty( $account_custom_fields ) ) {
                $account_fields['custom_field'] = $account_custom_fields;
            }

            $body = array(
                'sales_account' => $account_fields
            );

            $response = adfoin_freshsales_request( 'sales_accounts', 'POST', $body, $record );
            $body     = json_decode( wp_remote_retrieve_body( $response ) );

            if( $response['response']['code'] == 200 ) {
                $account_id = $body->sales_account->id;
            }
        }

        if( !empty( $contact_fields ) ) {

            if( $account_id ) {
                $contact_fields['sales_accounts'] = array(
                    array(
                        'id'         => $account_id,
                        'is_primary' => true
                    )
                );
            }

            if( !empty( $contact_custom_fields ) ) {
                $contact_fields['custom_field'] = $contact_custom_fields;
            }

            $body = array(
                'contact' => $contact_fields
            );

            if( isset( $contact_fields['email'] ) && $contact_fields['email'] ){
                $contact_id = adfoin_freshsales_if_contact_exists( $contact_fields['email'] );
            }

            if( $contact_id ) {
                $response = adfoin_freshsales_request( 'contacts/' . $contact_id, 'PUT', $body, $record );
            } else{
                $response = adfoin_freshsales_request( 'contacts', 'POST', $body, $record );
            }

            
            $body     = json_decode( wp_remote_retrieve_body( $response ) );

            if( $response['response']['code'] == 200 ) {
                $contact_id = $body->contact->id;
            }
        }

        if( !empty( $deal_fields ) ) {

            if ( $account_id ) {
                $deal_fields['sales_account_id'] = $account_id;
            }

            if( $contact_id ) {
                $deal_fields['contacts_added_list'] = array( $contact_id );
            }

            if( !empty( $deal_custom_fields ) ) {
                $deal_fields['custom_field'] = $deal_custom_fields;
            }

            $body = array(
                'deal' => $deal_fields
            );

            $response = adfoin_freshsales_request( 'deals', 'POST', $body, $record );
            $body     = json_decode( wp_remote_retrieve_body( $response ) );

            if( $response['response']['code'] == 200 ) {
                $deal_id = $body->deal->id;
            }
        }
    }
    return;
}