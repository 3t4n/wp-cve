<?php

add_filter( 'adfoin_action_providers', 'adfoin_encharge_actions', 10, 1 );

function adfoin_encharge_actions( $actions ) {

    $actions['encharge'] = array(
        'title' => __( 'Encharge', 'advanced-form-integration' ),
        'tasks' => array(
            'subscribe'   => __( 'Create new person', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_filter( 'adfoin_settings_tabs', 'adfoin_encharge_settings_tab', 10, 1 );

function adfoin_encharge_settings_tab( $providers ) {
    $providers['encharge'] = __( 'Encharge', 'advanced-form-integration' );

    return $providers;
}

add_action( 'adfoin_settings_view', 'adfoin_encharge_settings_view', 10, 1 );

function adfoin_encharge_settings_view( $current_tab ) {
    if( $current_tab != 'encharge' ) {
        return;
    }

    $nonce     = wp_create_nonce( 'adfoin_encharge_settings' );
    $api_key = get_option( 'adfoin_encharge_api_key' ) ? get_option( 'adfoin_encharge_api_key' ) : '';
    ?>

    <form name="encharge_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_encharge_api_key">
        <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php _e( 'Encharge API Key', 'advanced-form-integration' ); ?></th>
                <td>
                    <input type="text" name="adfoin_encharge_api_key"
                           value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Please enter API Key', 'advanced-form-integration' ); ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php _e( 'Go to Settings > Your Account and copy the API Key', 'advanced-form-integration' ); ?></a></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <?php
}

add_action( 'admin_post_adfoin_save_encharge_api_key', 'adfoin_save_encharge_api_key', 10, 0 );

function adfoin_save_encharge_api_key() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_encharge_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $api_key = sanitize_text_field( $_POST['adfoin_encharge_api_key'] );

    // Save tokens
    update_option( 'adfoin_encharge_api_key', $api_key );

    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=encharge' );
}

add_action( 'adfoin_action_fields', 'adfoin_encharge_action_fields' );

function adfoin_encharge_action_fields() {
?>
    <script type="text/template" id="encharge-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe'">
                <th scope="row">
                    <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">
                    <div class="spinner" v-bind:class="{'is-active': fieldLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
        </table>
    </script>


<?php
}

 /*
 * Encharge API Request
 */
function adfoin_encharge_request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {

    $api_token = get_option( 'adfoin_encharge_api_key' ) ? get_option( 'adfoin_encharge_api_key' ) : '';

    $base_url = 'https://api.encharge.io/v1/';
    $url      = $base_url . $endpoint;

    $args = array(
        'method'  => $method,
        'headers' => array(
            'Content-Type'     => 'application/json',
            'Accept'           => 'application/json',
            'X-Encharge-Token' => $api_token,
        ),
    );

    if ('POST' == $method || 'PUT' == $method) {
        $args['body'] = json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if ($record) {
        adfoin_add_to_log($response, $url, $args, $record);
    }

    return $response;
}

add_action( 'wp_ajax_adfoin_get_encharge_fields', 'adfoin_get_encharge_fields', 10, 0 );

/*
 * Get Encharge fields
 */
function adfoin_get_encharge_fields() {

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $data   = adfoin_encharge_request( 'fields', 'GET' );
    $fields = array();

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $data ) );

        foreach( $body->items as $single ) {

            if( false == $single->readOnly ) {
                array_push(
                    $fields,
                    array(
                        'key' => $single->name,
                        'value' => isset( $single->title ) ? $single->title : $single->name,
                        'description' => ''
                    )
                );
            }
        }

        array_push(
            $fields,
            array(
                'key' => 'tags',
                'value' => 'Tags',
                'description' => 'Use comma to add multiple tags'
            )
        );

        wp_send_json_success( $fields );
    } else {
        wp_send_json_error();
    }
}

add_action( 'adfoin_encharge_job_queue', 'adfoin_encharge_job_queue', 10, 1 );

function adfoin_encharge_job_queue( $data ) {
    adfoin_encharge_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Encharge API
 */
function adfoin_encharge_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data['field_data'];
    $task = isset( $record['task'] ) ? $record['task'] : '';

    if( $task == 'subscribe' ) {

        $holder = array();
        $tags   = null;

        foreach ( $data as $key => $value ) {
            if( $value ) {
                $holder[$key] = adfoin_get_parsed_values( $value, $posted_data );
            }
        }

        $holder = array_filter( $holder );

        if( isset( $holder['tags'] ) && $holder['tags'] ) {
            $tags = $holder['tags'];

            unset( $holder['tags'] );
        }

        adfoin_encharge_request( 'people', 'POST', $holder, $record );

        if( $tags ) {
            $email = isset( $holder['email'] ) ? $holder['email'] : '';

            if( $email ) {

                $tag_data = array(
                    'tag'   => $tags,
                    'email' => $email
                );

                sleep(5);

                adfoin_encharge_request( 'tags', 'POST', $tag_data, $record );
            }
        }
    }

    return;
}