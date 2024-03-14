<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_robly_actions',
    10,
    1
);
function adfoin_robly_actions( $actions )
{
    $actions['robly'] = array(
        'title' => __( 'Robly', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Add Contact To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_robly_settings_tab',
    10,
    1
);
function adfoin_robly_settings_tab( $providers )
{
    $providers['robly'] = __( 'Robly', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_robly_settings_view',
    10,
    1
);
function adfoin_robly_settings_view( $current_tab )
{
    if ( $current_tab != 'robly' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_robly_settings" );
    $api_id = ( get_option( 'adfoin_robly_api_id' ) ? get_option( 'adfoin_robly_api_id' ) : '' );
    $api_key = ( get_option( 'adfoin_robly_api_key' ) ? get_option( 'adfoin_robly_api_key' ) : '' );
    ?>

    <form name="robly_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_robly_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API ID', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_robly_api_id"
                           value="<?php 
    echo  esc_attr( $api_id ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter the API ID', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to Settings > API Details to get API ID and API Key', 'advanced-form-integration' );
    ?></a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_robly_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter API Key', 'advanced-form-integration' );
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
    'admin_post_adfoin_save_robly_api_key',
    'adfoin_save_robly_api_key',
    10,
    0
);
function adfoin_save_robly_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_robly_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_id = sanitize_text_field( $_POST['adfoin_robly_api_id'] );
    $api_key = sanitize_text_field( $_POST['adfoin_robly_api_key'] );
    // Save tokens
    update_option( 'adfoin_robly_api_id', $api_id );
    update_option( 'adfoin_robly_api_key', $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=robly" );
}

add_action( 'adfoin_action_fields', 'adfoin_robly_action_fields' );
function adfoin_robly_action_fields()
{
    ?>
    <script type="text/template" id="robly-action-template">
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

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'List', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[listId]" v-model="fielddata.listId">
                        <option value=""> <?php 
    _e( 'Select List...', 'advanced-form-integration' );
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
        printf( __( 'To unlock custom fields and tags consider <a href="%s">upgrading to Pro</a>.', 'advanced-form-integration' ), admin_url( 'admin.php?page=advanced-form-integration-settings-pricing' ) );
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
 * Robly API Request
 */
function adfoin_robly_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_id = get_option( 'adfoin_robly_api_id' );
    $api_key = get_option( 'adfoin_robly_api_key' );
    $base_url = 'https://api.robly.com/api/v1/';
    $url = $base_url . $endpoint;
    $final_data = array(
        'api_id'  => $api_id,
        'api_key' => $api_key,
    );
    if ( $data ) {
        $final_data = $final_data + $data;
    }
    $url = add_query_arg( $final_data, $url );
    $args = array(
        'method'       => $method,
        'Content-Type' => 'application/json',
    );
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
    'wp_ajax_adfoin_get_robly_list',
    'adfoin_get_robly_list',
    10,
    0
);
/*
 * Get Mailchimp subscriber lists
 */
function adfoin_get_robly_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $return = adfoin_robly_request( 'sub_lists/show?include_all=true' );
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ), true );
        $lists = array();
        if ( is_array( $body ) ) {
            foreach ( $body as $single ) {
                $id = $single['sub_list']['id'];
                $name = $single['sub_list']['name'];
                $lists[$id] = $name;
            }
        }
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }

}

function adfoin_robly_check_if_contact_exists( $email )
{
    $return = adfoin_robly_request( 'contacts/search?email=' . $email );
    
    if ( !is_wp_error( $return ) ) {
        $body = json_decode( wp_remote_retrieve_body( $return ), true );
        
        if ( isset( $body['member'], $body['member']['id'] ) ) {
            return $body['member']['id'];
        } else {
            return;
        }
    
    } else {
        return false;
    }

}

add_action(
    'adfoin_robly_job_queue',
    'adfoin_robly_job_queue',
    10,
    1
);
function adfoin_robly_job_queue( $data )
{
    adfoin_robly_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Robly API
 */
function adfoin_robly_send_data( $record, $posted_data )
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
        $email = ( empty($data['email']) ? '' : adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $fname = ( empty($data['fname']) ? '' : adfoin_get_parsed_values( $data['fname'], $posted_data ) );
        $lname = ( empty($data['lname']) ? '' : adfoin_get_parsed_values( $data['lname'], $posted_data ) );
        $data = array(
            'sub_lists[]' => $list_id,
            'email'       => trim( $email ),
            'fname'       => $fname,
            'lname'       => $lname,
        );
        $contact_id = adfoin_robly_check_if_contact_exists( $email );
        
        if ( $contact_id ) {
            $return = adfoin_robly_request(
                'contacts/update_full_contact?member_id=' . $contact_id,
                'POST',
                $data,
                $record
            );
        } else {
            $return = adfoin_robly_request(
                'sign_up/generate',
                'POST',
                $data,
                $record
            );
        }
    
    }

}
