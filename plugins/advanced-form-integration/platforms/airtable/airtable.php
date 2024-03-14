<?php

add_filter( 'adfoin_action_providers', 'adfoin_airtable_actions', 10, 1 );

function adfoin_airtable_actions( $actions ) {

    $actions['airtable'] = array(
        'title' => __( 'Airtable', 'advanced-form-integration' ),
        'tasks' => array(
            'add_row'   => __( 'Add New Row', 'advanced-form-integration' ),
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_airtable_settings_tab', 10, 1 );

function adfoin_airtable_settings_tab( $providers ) {
    $providers['airtable'] = __( 'Airtable', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_airtable_settings_view', 10, 1 );

function adfoin_airtable_settings_view( $current_tab ) {
    if( $current_tab != 'airtable' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_airtable_settings' );
    $api_token = get_option( 'adfoin_airtable_api_token' ) ? get_option( 'adfoin_airtable_api_token' ) : '';
    ?>

    <form name="airtable_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_airtable_save_api_token">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'API Token', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_airtable_api_token"
                           value="<?php echo esc_attr( $api_token ); ?>" placeholder="<?php _e( 'Please enter API Token', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description">
                        1. Go to <a target="_blank" rel="noopener noreferrer" href="https://airtable.com/create/tokens">https://airtable.com/create/tokens</a> and click on Create new token.
                        <br>
                        2. Insert a name for the token.
                        <br>
                        3. Select the scopes: data.records:read, data.record:write and schema.bases:read.
                        <br>
                        4. Select the bases you want to integrate or select all workspaces.
                        <br>
                        5. Click Create token.

                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_airtable_save_api_token', 'adfoin_save_airtable_api_token', 10, 0 );

function adfoin_save_airtable_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_airtable_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_token = sanitize_text_field( $_POST["adfoin_airtable_api_token"] );

    // Save tokens
    update_option( "adfoin_airtable_api_token", $api_token );

    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=airtable" );
}

add_action( 'adfoin_action_fields', 'adfoin_airtable_action_fields' );

function adfoin_airtable_action_fields() {
    ?>
    <script type="text/template" id="airtable-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'add_row'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                <div class="spinner" v-bind:class="{'is-active': fieldLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_row'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Base', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[baseId]" v-model="fielddata.baseId" required="required" @change="getTables">
                        <option value=""> <?php _e( 'Select Base...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.bases" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': baseLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'add_row'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Table', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[tableId]" v-model="fielddata.tableId" required="required" @change="getFields">
                        <option value=""> <?php _e( 'Select Table...', 'advanced-form-integration' ); ?> </option>
                        <option v-for="(item, index) in fielddata.tables" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': tableLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>

        </table>
    </script>
    <?php
}

/*
 * Airtable API Request
 */
function adfoin_airtable_request($endpoint, $method = 'GET', $data = array(), $record = array())
{
    $api_token = get_option( 'adfoin_airtable_api_token' );
    $base_url  = 'https://api.airtable.com/v0/';
    $url       = $base_url . $endpoint;

    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );

    if ( 'POST' == $method || 'PUT' == $method|| 'PATCH' == $method ) {
        $args['body'] = json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_airable_bases', 'adfoin_get_airable_bases', 10, 0 );
/*
 * Get Airtable Base List
 */
function adfoin_get_airable_bases() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $data = adfoin_airtable_request( 'meta/bases' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body  = json_decode( wp_remote_retrieve_body( $data ), true );
    $bases = array();

    if( isset( $body['bases'] ) && is_array( $body['bases'] ) ) {
        foreach( $body['bases'] as $base ) {
            if( 'create' == $base['permissionLevel'] ) {
                $bases[$base['id']] = $base['name'];
            }
        }
    }

    wp_send_json_success( $bases );
}

add_action( 'wp_ajax_adfoin_get_airtable_tables', 'adfoin_get_airtable_tables', 10, 0 );
/*
 * Get Airtable Base List
 */
function adfoin_get_airtable_tables() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $base_id = isset( $_POST['baseId'] ) ? $_POST['baseId'] : '';
    $data = adfoin_airtable_request( 'meta/bases/' . $base_id . '/tables' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body   = json_decode( wp_remote_retrieve_body( $data ), true );
    $tables = array();

    if( isset( $body['tables'] ) && is_array( $body['tables'] ) ) {
        foreach( $body['tables'] as $table ) {
            
            $tables[$table['id']] = $table['name'];
        }
    }

    wp_send_json_success( $tables );
}

add_action( 'wp_ajax_adfoin_get_airtable_fields', 'adfoin_get_airtable_fields', 10, 0 );
/*
 * Get Airtable Fields
 */
function adfoin_get_airtable_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $base_id  = isset( $_POST['baseId'] ) ? $_POST['baseId'] : '';
    $table_id = isset( $_POST['tableId'] ) ? $_POST['tableId'] : '';
    $data = adfoin_airtable_request( 'meta/bases/' . $base_id . '/tables' );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }

    $body   = json_decode( wp_remote_retrieve_body( $data ), true );
    $fields = array();

    $accepted_field_types = array(
        'singleLineText',
        'multilineText',
        'date',
        'phoneNumber',
        'email',
        'url',
        'number',
        'currency',
        'percent',
        'duration',
        'rating',
        'barcode'
    );


    if( isset( $body['tables'] ) && is_array( $body['tables'] ) ) {
        foreach( $body['tables'] as $table ) {
            if( $table_id == $table['id'] ) {
                if( isset( $table['fields'] ) && is_array( $table['fields'] ) ) {
                    foreach( $table['fields'] as $field ) {
                        if( isset( $field['type'] ) && in_array( $field['type'], $accepted_field_types ) ) {
                            array_push( $fields, array(
                                'key'         => $field['type'] . '__' . $field['id'],
                                'value'       => $field['name'],
                                'description' => ''
                            ));
                        }
                    }
                }
            }
        }
    }

    wp_send_json_success( $fields );
}

add_action( 'adfoin_airtable_job_queue', 'adfoin_airtable_job_queue', 10, 1 );

function adfoin_airtable_job_queue( $data ) {
    adfoin_airtable_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Airtable API
 */
function adfoin_airtable_send_data( $record, $posted_data ) {

    $record_data    = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data     = $record_data['field_data'];
    $base_id  = $data['baseId'];
    $table_id = $data['tableId'];
    $task     = $record['task'];

    if( $task == 'add_row' ) {

        unset( $data['baseId'] );
        unset( $data['tableId'] );
        unset( $data['bases'] );
        unset( $data['tables'] );

        $holder = array();

        foreach ( $data as $key => $value ) {
            if( $value ) {
                $parsed_value = adfoin_get_parsed_values( $value, $posted_data );

                if( $parsed_value ) {
                    list( $field_type, $field_key ) = explode( '__', $key );
                    $holder[$field_key] = $parsed_value;
                }
            }
            
        }

        $row_data = array(
            'records' => array(
                array(
                    'fields' => $holder
                )
            )
        );

        $return = adfoin_airtable_request( $base_id . '/' . $table_id, 'POST', $row_data, $record );
    }

    return;
}