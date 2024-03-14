<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mailerlite_actions',
    10,
    1
);
function adfoin_mailerlite_actions( $actions )
{
    $actions['mailerlite'] = array(
        'title' => __( 'MailerLite Classic', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To Group', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mailerlite_settings_tab',
    10,
    1
);
function adfoin_mailerlite_settings_tab( $providers )
{
    $providers['mailerlite'] = __( 'MailerLite Classic', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mailerlite_settings_view',
    10,
    1
);
function adfoin_mailerlite_settings_view( $current_tab )
{
    if ( $current_tab != 'mailerlite' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_mailerlite_settings" );
    $api_key = ( get_option( 'adfoin_mailerlite_api_key' ) ? get_option( 'adfoin_mailerlite_api_key' ) : "" );
    ?>

    <form name="mailerlite_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_mailerlite_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'MailerLite API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mailerlite_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Please go to Integrations > Developer API to get API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_mailerlite_api_key',
    'adfoin_save_mailerlite_api_key',
    10,
    0
);
function adfoin_save_mailerlite_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailerlite_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_mailerlite_api_key"] );
    // Save tokens
    update_option( "adfoin_mailerlite_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=mailerlite" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_mailerlite_js_fields',
    10,
    1
);
function adfoin_mailerlite_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_mailerlite_action_fields' );
function adfoin_mailerlite_action_fields()
{
    ?>
    <script type="text/template" id="mailerlite-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'subscribe_to_group'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

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

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Double Opt-in', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="fieldData[doubleoptin]" value="true" v-model="fielddata.doubleoptin">
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

add_action(
    'wp_ajax_adfoin_get_mailerlite_list',
    'adfoin_get_mailerlite_list',
    10,
    0
);
/*
 * Get MailerLite subscriber lists
 */
function adfoin_get_mailerlite_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = get_option( "adfoin_mailerlite_api_key" );
    if ( !$api_key ) {
        return array();
    }
    $url = "http://api.mailerlite.com/api/v2/groups";
    $args = array(
        'headers' => array(
        'Content-Type'        => 'application/json',
        'X-MailerLite-ApiKey' => $api_key,
    ),
    );
    $data = wp_remote_request( $url, $args );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_mailerlite_job_queue',
    'adfoin_mailerlite_job_queue',
    10,
    1
);
function adfoin_mailerlite_job_queue( $data )
{
    adfoin_mailerlite_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Saves connection mapping
 */
function adfoin_mailerlite_save_integration()
{
    $params = array();
    parse_str( adfoin_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = ( isset( $_POST["triggerData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["triggerData"] ) : array() );
    $action_data = ( isset( $_POST["actionData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["actionData"] ) : array() );
    $field_data = ( isset( $_POST["fieldData"] ) ? adfoin_sanitize_text_or_array_field( $_POST["fieldData"] ) : array() );
    $integration_title = ( isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "" );
    $form_provider_id = ( isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "" );
    $form_id = ( isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "" );
    $form_name = ( isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "" );
    $action_provider = ( isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "" );
    $task = ( isset( $action_data["task"] ) ? $action_data["task"] : "" );
    $type = ( isset( $params["type"] ) ? $params["type"] : "" );
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
    );
    global  $wpdb ;
    $integration_table = $wpdb->prefix . 'adfoin_integration';
    if ( $type == 'new_integration' ) {
        $result = $wpdb->insert( $integration_table, array(
            'title'           => $integration_title,
            'form_provider'   => $form_provider_id,
            'form_id'         => $form_id,
            'form_name'       => $form_name,
            'action_provider' => $action_provider,
            'task'            => $task,
            'data'            => json_encode( $all_data, true ),
            'status'          => 1,
        ) );
    }
    
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' && !empty($id) ) {
            return;
        }
        $result = $wpdb->update( $integration_table, array(
            'title'         => $integration_title,
            'form_provider' => $form_provider_id,
            'form_id'       => $form_id,
            'form_name'     => $form_name,
            'data'          => json_encode( $all_data, true ),
        ), array(
            'id' => $id,
        ) );
    }
    
    
    if ( $result ) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }

}

/*
 * Handles sending data to MailerLite API
 */
function adfoin_mailerlite_send_data( $record, $posted_data )
{
    $api_key = ( get_option( 'adfoin_mailerlite_api_key' ) ? get_option( 'adfoin_mailerlite_api_key' ) : "" );
    if ( !$api_key ) {
        return;
    }
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data["field_data"];
    $list_id = $data["listId"];
    $task = $record["task"];
    $doubleoption = ( isset( $data["doubleoptin"] ) && $data["doubleoptin"] ? $data["doubleoptin"] : "" );
    
    if ( $task == "subscribe" ) {
        $email = ( empty($data["email"]) ? "" : adfoin_get_parsed_values( $data["email"], $posted_data ) );
        $name = ( empty($data["name"]) ? "" : adfoin_get_parsed_values( $data["name"], $posted_data ) );
        $headers = array(
            'Content-Type'        => 'application/json',
            'X-MailerLite-ApiKey' => $api_key,
        );
        $subscriber_data = array(
            "email" => $email,
            "name"  => $name,
        );
        if ( "true" == $doubleoption ) {
            wp_remote_post( 'https://api.mailerlite.com/api/v2/settings/double_optin', array(
                'headers' => $headers,
                'body'    => json_encode( array(
                'enable' => true,
            ) ),
            ) );
        }
        $sub_url = "https://api.mailerlite.com/api/v2/subscribers";
        if ( $list_id ) {
            $sub_url = "https://api.mailerlite.com/api/v2/groups/{$list_id}/subscribers";
        }
        $sub_args = array(
            'headers' => $headers,
            'body'    => json_encode( $subscriber_data ),
        );
        $return = wp_remote_post( $sub_url, $sub_args );
        adfoin_add_to_log(
            $return,
            $sub_url,
            $sub_args,
            $record
        );
    }

}
