<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mailerlite2_actions',
    10,
    1
);
function adfoin_mailerlite2_actions( $actions )
{
    $actions['mailerlite2'] = array(
        'title' => __( 'MailerLite', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To Group', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mailerlite2_settings_tab',
    10,
    1
);
function adfoin_mailerlite2_settings_tab( $providers )
{
    $providers['mailerlite2'] = __( 'MailerLite', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mailerlite2_settings_view',
    10,
    1
);
function adfoin_mailerlite2_settings_view( $current_tab )
{
    if ( $current_tab != 'mailerlite2' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_mailerlite2_settings' );
    $api_key = ( get_option( 'adfoin_mailerlite2_api_key' ) ? get_option( 'adfoin_mailerlite2_api_key' ) : '' );
    ?>

    <form name="mailerlite2_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_mailerlite2_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'MailerLIte API Token', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mailerlite2_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Enter API Token', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Please go to Integrations > API and generate a new token', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_mailerlite2_api_key',
    'adfoin_save_mailerlite2_api_key',
    10,
    0
);
function adfoin_save_mailerlite2_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailerlite2_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST['adfoin_mailerlite2_api_key'] );
    // Save tokens
    update_option( 'adfoin_mailerlite2_api_key', $api_key );
    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=mailerlite2' );
}

add_action( 'adfoin_action_fields', 'adfoin_mailerlite2_action_fields' );
function adfoin_mailerlite2_action_fields()
{
    ?>
    <script type="text/template" id="mailerlite2-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'subscribe_to_group'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">
                <div class="spinner" v-bind:class="{'is-active': fieldsLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'MailerLite Group', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
                        <option value=""> <?php 
    _e( 'Select Group...', 'advanced-form-integration' );
    ?> </option>
                        <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
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
        printf( __( 'To unlock custom fields consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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

function adfoin_mailerlite2_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = ( get_option( 'adfoin_mailerlite2_api_key' ) ? get_option( 'adfoin_mailerlite2_api_key' ) : '' );
    if ( !$api_token ) {
        return;
    }
    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
        'User-Agent'    => 'advanced-form-integration',
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
        'Authorization' => 'Bearer ' . $api_token,
    ),
    );
    $base_url = 'https://connect.mailerlite.com/api/';
    $url = $base_url . $endpoint;
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
    'wp_ajax_adfoin_get_mailerlite2_list',
    'adfoin_get_mailerlite2_list',
    10,
    0
);
/*
 * Get MailerLite subscriber lists
 */
function adfoin_get_mailerlite2_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_mailerlite2_request( 'groups' );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( wp_remote_retrieve_body( $data ) );
        $lists = wp_list_pluck( $body->data, 'name', 'id' );
        wp_send_json_success( $lists );
    }
    
    wp_send_json_error();
}

add_action(
    'wp_ajax_adfoin_get_mailerlite2_custom_fields',
    'adfoin_get_mailerlite2_custom_fields',
    10,
    0
);
/*
 * Get MailerLite fields
 */
function adfoin_get_mailerlite2_custom_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_mailerlite2_request( 'fields' );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( wp_remote_retrieve_body( $data ) );
        $fields = array();
        foreach ( $body->data as $single ) {
            if ( true == $single->is_default ) {
                array_push( $fields, array(
                    'key'   => $single->key,
                    'value' => $single->name,
                ) );
            }
        }
        wp_send_json_success( $fields );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_mailerlite2_job_queue',
    'adfoin_mailerlite2_job_queue',
    10,
    1
);
function adfoin_mailerlite2_job_queue( $data )
{
    adfoin_mailerlite2_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to MailerLite API
 */
function adfoin_mailerlite2_send_data( $record, $posted_data )
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
    $list_id = $data['listId'];
    $task = $record['task'];
    
    if ( $task == 'subscribe' ) {
        $holder = array();
        foreach ( $data as $key => $value ) {
            $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
        }
        $email = ( isset( $holder['email'] ) ? $holder['email'] : '' );
        $status = ( isset( $holder['status'] ) ? $holder['status'] : '' );
        $ip_address = ( isset( $holder['ip_address'] ) ? $holder['ip_address'] : '' );
        unset( $holder['list'] );
        unset( $holder['listId'] );
        unset( $holder['email'] );
        unset( $holder['status'] );
        unset( $holder['ip_address'] );
        $holder = array_filter( $holder );
        $subscriber_data = array(
            'email' => $email,
        );
        if ( $holder ) {
            $subscriber_data['fields'] = $holder;
        }
        if ( $ip_address ) {
            $subscriber_data['ip_address'] = $ip_address;
        }
        if ( $status ) {
            $subscriber_data['status'] = $status;
        }
        if ( $list_id ) {
            $subscriber_data['groups'] = array( $list_id );
        }
        adfoin_mailerlite2_request(
            'subscribers',
            'POST',
            $subscriber_data,
            $record
        );
        return;
    }

}
