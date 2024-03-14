<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_emailoctopus_actions',
    10,
    1
);
function adfoin_emailoctopus_actions( $actions )
{
    $actions['emailoctopus'] = array(
        'title' => __( 'EmailOctopus', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_emailoctopus_settings_tab',
    10,
    1
);
function adfoin_emailoctopus_settings_tab( $providers )
{
    $providers['emailoctopus'] = __( 'EmailOctopus', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_emailoctopus_settings_view',
    10,
    1
);
function adfoin_emailoctopus_settings_view( $current_tab )
{
    if ( $current_tab != 'emailoctopus' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_emailoctopus_settings' );
    $api_key = ( get_option( 'adfoin_emailoctopus_api_key' ) ? get_option( 'adfoin_emailoctopus_api_key' ) : '' );
    ?>

    <form name="emailoctopus_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_emailoctopus_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'EmailOctopus API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_emailoctopus_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><a
                            href="https://emailoctopus.com/api-documentation"
                            target="_blank" rel="noopener noreferrer"><?php 
    _e( 'Click here to the get API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_emailoctopus_api_key',
    'adfoin_save_emailoctopus_api_key',
    10,
    0
);
function adfoin_save_emailoctopus_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_emailoctopus_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST['adfoin_emailoctopus_api_key'] );
    // Save tokens
    update_option( 'adfoin_emailoctopus_api_key', $api_key );
    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=emailoctopus' );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_emailoctopus_js_fields',
    10,
    1
);
function adfoin_emailoctopus_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_emailoctopus_action_fields' );
function adfoin_emailoctopus_action_fields()
{
    ?>
    <script type="text/template" id="emailoctopus-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <th scope="row">
                    <?php 
    esc_attr_e( 'Map Fields', 'advanced-form-integration' );
    ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe' || action.task == 'unsubscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'EmailOctopus List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                        <option value=""> <?php 
    _e( 'Select List...', 'advanced-form-integration' );
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
                    <input type="checkbox" value="true" name="fieldData[doubleoptin]" v-model="fielddata.doubleoptin">
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
        printf( __( 'To unlock custom fields and update contact consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
    'wp_ajax_adfoin_get_emailoctopus_list',
    'adfoin_get_emailoctopus_list',
    10,
    0
);
/*
 * Get emailoctopus subscriber lists
 */
function adfoin_get_emailoctopus_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = get_option( 'adfoin_emailoctopus_api_key' );
    if ( !$api_key ) {
        return array();
    }
    $url = 'https://emailoctopus.com/api/1.6/lists?api_key=' . $api_key;
    $args = array(
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'api_key ' . $api_key,
    ),
    );
    $data = wp_remote_request( $url, $args );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( $data['body'] );
        $lists = wp_list_pluck( $body->data, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

add_action(
    'adfoin_emailoctopus_job_queue',
    'adfoin_emailoctopus_job_queue',
    10,
    1
);
function adfoin_emailoctopus_job_queue( $data )
{
    adfoin_emailoctopus_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to emailoctopus API
 */
function adfoin_emailoctopus_send_data( $record, $posted_data )
{
    $api_key = ( get_option( 'adfoin_emailoctopus_api_key' ) ? get_option( 'adfoin_emailoctopus_api_key' ) : '' );
    if ( !$api_key ) {
        return;
    }
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
    $doubleoption = $data['doubleoptin'];
    $email = ( empty($data['email']) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data ) );
    
    if ( $task == 'subscribe' ) {
        $first_name = ( empty($data['firstName']) ? '' : adfoin_get_parsed_values( $data['firstName'], $posted_data ) );
        $last_name = ( empty($data['lastName']) ? '' : adfoin_get_parsed_values( $data['lastName'], $posted_data ) );
        $subscriber_data = array(
            'api_key'       => $api_key,
            'email_address' => trim( $email ),
            'status'        => 'SUBSCRIBED',
            'fields'        => array(
            'FirstName' => $first_name,
            'LastName'  => $last_name,
        ),
        );
        if ( 'true' == $doubleoption ) {
            unset( $subscriber_data['status'] );
        }
        $sub_url = "https://emailoctopus.com/api/1.6/lists/{$list_id}/contacts";
        $sub_args = array(
            'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'api_key ' . $api_key,
        ),
            'body'    => json_encode( $subscriber_data ),
        );
        $return = wp_remote_post( $sub_url, $sub_args );
        adfoin_add_to_log(
            $return,
            $sub_url,
            $sub_args,
            $record
        );
        
        if ( $return['response']['code'] == 200 ) {
            return array( 1 );
        } else {
            return array( 0, $return );
        }
    
    }

}
