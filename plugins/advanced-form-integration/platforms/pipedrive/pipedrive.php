<?php

add_filter( 'adfoin_action_providers', 'adfoin_pipedrive_actions', 10, 1 );

function adfoin_pipedrive_actions( $actions ) {

    $actions['pipedrive'] = array(
        'title' => __( 'Pipedrive', 'advanced-form-integration' ),
        'tasks' => array(
            'add_ocdna' => __( 'Create New Contact, Organization, Deal, Note, Activity', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_pipedrive_settings_tab', 10, 1 );

function adfoin_pipedrive_settings_tab( $providers ) {
    $providers['pipedrive'] = __( 'Pipedrive', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_pipedrive_settings_view', 10, 1 );

function adfoin_pipedrive_settings_view( $current_tab ) {
    if( $current_tab != 'pipedrive' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_pipedrive_settings' );
    $api_token = get_option( 'adfoin_pipedrive_api_token' ) ? get_option( 'adfoin_pipedrive_api_token' ) : '';
    ?>

    <form name="pipedrive_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_pipedrive_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_pipedrive_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Profile > Personal preferences > API to get API Token', 'advanced-form-integration' ); ?></a></p>
                </td>

            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_pipedrive_api_token', 'adfoin_save_pipedrive_api_token', 10, 0 );

function adfoin_save_pipedrive_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_pipedrive_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST['adfoin_pipedrive_api_token'] );

    // Save tokens
    update_option( 'adfoin_pipedrive_api_token', $api_token );

    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=pipedrive' );
}

add_action( 'adfoin_add_js_fields', 'adfoin_pipedrive_js_fields', 10, 1 );

function adfoin_pipedrive_js_fields( $field_data ) {}

add_action( 'adfoin_action_fields', 'adfoin_pipedrive_action_fields', 10, 1 );

function adfoin_pipedrive_action_fields() {
    ?>
    <script type="text/template" id="pipedrive-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_ocdna'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_ocdna'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Owner', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[owner]" v-model="fielddata.owner" required="required">
                        <option value=""> <?php _e( 'Select Owner...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.ownerList" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': ownerLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_ocdna'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Allow Duplicate Person', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="fieldData[duplicate]" value="true" v-model="fielddata.duplicate">
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_ocdna'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Allow Duplicate Organization', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="fieldData[duplicateOrg]" value="true" v-model="fielddata.duplicateOrg">
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>
    <?php
}

add_action( 'wp_ajax_adfoin_get_pipedrive_owner_list', 'adfoin_get_pipedrive_owner_list', 10, 0 );

/*
 * Get Pipedrive Owner list
 */
function adfoin_get_pipedrive_owner_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $data = adfoin_pipedrive_request( 'users?limit=500' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body  = json_decode( $data['body'] );
    $lists = wp_list_pluck( $body->data, 'name', 'id' );

    wp_send_json_success( $lists );
}

add_action( 'wp_ajax_adfoin_get_pipedrive_org_fields', 'adfoin_get_pipedrive_org_fields', 10, 0 );

/*
 * Get Pipedrive Organization Fields
 */
function adfoin_get_pipedrive_org_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $org_fields = array(
        array( 'key' => 'org_name', 'value' => 'Name [Organziation]', 'description' => '' ),
        array( 'key' => 'org_address', 'value' => 'Address [Organziation]', 'description' => '' ),
    );

    $data = adfoin_pipedrive_request( 'organizationFields?limit=500' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( $data['body'] );

    foreach( $body->data as $single ) {
        if( strlen( $single->key ) == 40 || $single->key == 'label' ) {

            $description = '';

            if( $single->field_type == 'enum' || $single->field_type == 'set' ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $org_fields, array( 'key' => 'org_' . $single->key, 'value' => $single->name . ' [Organziation]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $org_fields );
}

add_action( 'wp_ajax_adfoin_get_pipedrive_person_fields', 'adfoin_get_pipedrive_person_fields', 10, 0 );

/*
 * Get Pipedrive Peson Fields
 */
function adfoin_get_pipedrive_person_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $person_fields = array();
    $data          = adfoin_pipedrive_request( 'personFields?limit=500' );

    if( is_wp_error( $data ) ) {
        wp_send_json_success( $person_fields );
    }

    $body = json_decode( wp_remote_retrieve_body( $data ) );

    foreach( $body->data as $single ) {
        $description = '';

        if( true == $single->bulk_edit_allowed ) {

            if( 'name' == $single->key ) {
                $description = __( 'Required for creating a person', 'advanced-form-integration' );
            }

            if( 'visible_to' == $single->key ) {
                $description = __( 'Owner & followers (private): 1 Entire company (shared): 3', 'advanced-form-integration' );
            }

            if( 'first_name' == $single->key || 'last_name' == $single->key || 'org_id' == $single->key || 'owner_id' == $single->key ) {
                continue;
            }

            if( $single->field_type == 'enum' || $single->field_type == 'set' ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $person_fields, array( 'key' => 'per_' . $single->key, 'value' => $single->name . ' [Person]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $person_fields );
}

add_action( 'wp_ajax_adfoin_get_pipedrive_deal_fields', 'adfoin_get_pipedrive_deal_fields', 10, 0 );

/*
 * Get Pipedrive Deal Fields
 */
function adfoin_get_pipedrive_deal_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        wp_send_json_error( __( 'Security check failed', 'advanced-form-integration' ) );
    }

    $stages     = '';
    $stage_data = adfoin_pipedrive_request( 'stages?limit=500' );
    $stage_body = json_decode( $stage_data['body'] );

    foreach( $stage_body->data as $single ) {
        $stages .= $single->pipeline_name . '/' . $single->name . ': ' . $single->id . ' ';
    }

    $deal_fields = array(
        array( 'key' => 'deal_title', 'value' => 'Title [Deal]', 'description' => __( 'Required for creating a deal.', 'advanced-form-integration' ) ),
        array( 'key' => 'deal_value', 'value' => 'Value [Deal]', 'description' => 'Numeric value of the deal. If omitted, it will be set to 0.' ),
        array( 'key' => 'deal_currency', 'value' => 'Currency [Deal]', 'description' => 'Accepts a 3-character currency code. If omitted, currency will be set to the default currency of the authorized user.' ),
        array( 'key' => 'deal_probability', 'value' => 'Probability [Deal]', 'description' => '' ),
        array( 'key' => 'deal_stage_id', 'value' => 'Stage ID [Deal]', 'description' => $stages ),
        array( 'key' => 'deal_status', 'value' => 'Status [Deal]', 'description' => 'Example: open, lost, won, deleted' ),
        array( 'key' => 'deal_lost_reason', 'value' => 'Lost Reason [Deal]', 'description' => '' ),
        array( 'key' => 'deal_expected_close_date', 'value' => 'Expected Close Date [Deal]', 'description' => 'YYYY-MM-DD' )
    );

    $data = adfoin_pipedrive_request( 'dealFields?limit=500' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body = json_decode( $data['body'] );

    foreach( $body->data as $single ) {
        if( strlen( $single->key ) == 40 || $single->key == 'label' ) {

            $description = '';

            if( $single->field_type == 'enum' || $single->field_type == 'set' ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $deal_fields, array( 'key' => 'deal_' . $single->key, 'value' => $single->name . ' [Deal]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $deal_fields );
}

add_action( 'adfoin_pipedrive_job_queue', 'adfoin_pipedrive_job_queue', 10, 1 );

function adfoin_pipedrive_job_queue( $data ) {
    adfoin_pipedrive_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Pipedrive API
 */
function adfoin_pipedrive_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data          = $record_data['field_data'];
    $task          = $record['task'];
    $owner         = $data['owner'];
    $duplicate     = isset( $data['duplicate'] ) ? $data['duplicate'] : '';
    $duplicate_org = isset( $data['duplicateOrg'] ) ? $data['duplicateOrg'] : '';
    $org_id        = '';
    $person_id     = '';
    $deal_id       = '';

    if( $task == 'add_ocdna' ) {

        $holder      = array();
        $org_data    = array();
        $person_data = array();
        $deal_data   = array();
        $note_data   = array();
        $act_data    = array();

        foreach( $data as $key => $value ) {
            $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
        }

        foreach( $holder as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );

                $org_data[$key] = $value;
            }

            if( substr( $key, 0, 4 ) == 'per_' && $value ) {
                $key = substr( $key, 4 );

                $person_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'deal_' && $value ) {
                $key = substr( $key, 5 );

                $deal_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'note_' && $value ) {
                $key = substr( $key, 5 );

                $note_data[$key] = $value;
            }

            if( substr( $key, 0, 4 ) == 'act_' && $value ) {
                $key = substr( $key, 4 );

                $act_data[$key] = $value;
            }
        }

        if( isset( $org_data['name'] ) && $org_data['name'] ) {
            $org_data['owner_id'] = $owner;

            $org_data = array_filter( array_map( 'trim', $org_data ) );
            $org_id   = adfoin_pipedrive_organization_exists( $org_data['name'] );

            if( $org_id && 'true' != $duplicate_org ) {
                $org_response = adfoin_pipedrive_request( 'organizations/' . $org_id, 'PUT', $org_data, $record );
            } else{
                $org_response = adfoin_pipedrive_request( 'organizations', 'POST', $org_data, $record );
                $org_body     = json_decode( wp_remote_retrieve_body( $org_response ) );

                if( $org_body->success == true ) {
                    $org_id = $org_body->data->id;
                }
            }
        }

        if( isset( $person_data['name'] ) && $person_data['name'] ) {            
            $person_data['owner_id'] = $owner;

            if( $org_id ) {
                $person_data['org_id'] = $org_id;
            }

            $person_data = array_filter( array_map( 'trim', $person_data ) );

            if( isset( $person_data['email'] ) ) {
                $person_id = adfoin_pipedrive_person_exists( $person_data['email'] );
            }

            if( $person_id && 'true' != $duplicate ) {
                $person_response = adfoin_pipedrive_request( 'persons/' . $person_id, 'PUT', $person_data, $record );
            } else{
                $person_response = adfoin_pipedrive_request( 'persons', 'POST', $person_data, $record );
                $person_body     = json_decode( wp_remote_retrieve_body( $person_response ) );

                if( $person_body->success == true ) {
                    $person_id = $person_body->data->id;
                }
            }
        }

        if( isset( $deal_data['title'] ) && $deal_data['title'] ) {
            $deal_data['user_id'] = $owner;

            if( $org_id ) {
                $deal_data['org_id'] = $org_id;
            }

            if( $person_id ) {
                $deal_data['person_id'] = $person_id;
            }

            $deal_data     = array_filter( array_map( 'trim', $deal_data ) );
            $deal_response = adfoin_pipedrive_request( 'deals', 'POST', $deal_data, $record );
            $deal_body     = json_decode( wp_remote_retrieve_body( $deal_response ) );

            if( $deal_body->success == true ) {
                $deal_id = $deal_body->data->id;
            }
        }

        if( isset( $note_data['content'] ) && $note_data['content'] ) {
            $note_data['user_id'] = $owner;

            if( $org_id ) {
                $note_data['org_id'] = $org_id;
            }

            if( $person_id ) {
                $note_data['person_id'] = $person_id;
            }

            if( $deal_id ) {
                $note_data['deal_id'] = $deal_id;
            }

            $note_data     = array_filter( array_map( 'trim', $note_data ) );
            $note_response = adfoin_pipedrive_request( 'notes', 'POST', $note_data, $record );
            $note_body     = json_decode( wp_remote_retrieve_body( $note_response ) );
        }

        if( isset( $act_data['subject'] ) && $act_data['subject'] ) {
            $act_data['user_id'] = $owner;

            if( $org_id ) {
                $act_data['org_id'] = $org_id;
            }

            if( $person_id ) {
                $act_data['person_id'] = $person_id;
            }

            if( $deal_id ) {
                $act_data['deal_id'] = $deal_id;
            }

            if( isset( $act_data['after_days'] ) && $act_data['after_days'] ) {
                $after_days = (int) $act_data['after_days'];

                if( $after_days ) {
                    $timezone             = wp_timezone();
                    $date                 = date_create( '+' . $after_days . ' days', $timezone );
                    $formatted_date       = date_format( $date, 'Y-m-d' );
                    $act_data['due_date'] = $formatted_date;

                    unset( $act_data['after_days'] );
                }
            }

            $act_data     = array_filter( array_map( 'trim', $act_data ) );
            $act_response = adfoin_pipedrive_request( 'activities', 'POST', $act_data, $record );
            // $act_body     = json_decode( wp_remote_retrieve_body( $act_response ) );
        }
    }

    return;
}

function adfoin_pipedrive_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_token = get_option( 'adfoin_pipedrive_api_token' ) ? get_option( 'adfoin_pipedrive_api_token' ) : '';

    if( !$api_token ) {
        return;
    }

    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        )
    );

    $base_url = 'https://api.pipedrive.com/v1/';
    $url      = $base_url . $endpoint;
    $url      = add_query_arg( 'api_token', $api_token, $url );

    if( 'POST' == $method || 'PUT' == $method ) {
        $args['body'] = json_encode( $data );
    }

    $response = wp_remote_request( $url, $args );

    if( $record ) {
        adfoin_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}

function adfoin_pipedrive_person_exists( $email ) {
    $endpoint = 'persons/search';

    $query_args = array(
        'fields'      => 'email',
        'exact_match' => true,
        'term'        => $email
    );

    $person_id = adfoin_pipedrive_item_exists( $endpoint, $query_args );

    return $person_id;
}

function adfoin_pipedrive_organization_exists( $name ) {
    $endpoint = 'organizations/search';

    $query_args = array(
        'fields'      => 'name',
        'exact_match' => true,
        'term'        => $name
    );

    $org_id = adfoin_pipedrive_item_exists( $endpoint, $query_args );

    return $org_id;
}

function adfoin_pipedrive_item_exists( $endpoint, $query_args ) {
    $endpoint      = add_query_arg( $query_args, $endpoint );
    $response      = adfoin_pipedrive_request( $endpoint, 'GET' );
    $response_code = wp_remote_retrieve_response_code( $response );
    $item_id     = '';
    
    if( 200 == $response_code ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if( isset( $response_body['data']['items'] ) && is_array( $response_body['data']['items'] ) ) {
            if( count( $response_body['data']['items'] ) > 0 ) {
                $item_id = $response_body['data']['items'][0]['item']['id'];
            }
        }
    }

    if( $item_id ) {
        return $item_id;
    } else{
        return false;
    }
}