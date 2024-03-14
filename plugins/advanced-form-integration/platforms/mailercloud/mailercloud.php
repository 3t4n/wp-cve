<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_mailercloud_actions',
    10,
    1
);
function adfoin_mailercloud_actions( $actions )
{
    $actions['mailercloud'] = array(
        'title' => __( 'Mailercloud', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_mailercloud_settings_tab',
    10,
    1
);
function adfoin_mailercloud_settings_tab( $providers )
{
    $providers['mailercloud'] = __( 'Mailercloud', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_mailercloud_settings_view',
    10,
    1
);
function adfoin_mailercloud_settings_view( $current_tab )
{
    if ( $current_tab != 'mailercloud' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_mailercloud_settings" );
    $api_key = ( get_option( 'adfoin_mailercloud_api_key' ) ? get_option( 'adfoin_mailercloud_api_key' ) : "" );
    ?>

    <form name="mailercloud_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_mailercloud_save_api_key">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php 
    _e( 'API Key', 'advanced-form-integration' );
    ?></th>
                <td>
                    <input type="text" name="adfoin_mailercloud_api_key"
                           value="<?php 
    echo  esc_attr( $api_key ) ;
    ?>" placeholder="<?php 
    _e( 'Please enter the API Key', 'advanced-form-integration' );
    ?>"
                           class="regular-text"/>
                    <p class="description" id="code-description"><?php 
    _e( 'Go to Profile > Account > Integrations > API Integrations. Create an API key', 'advanced-form-integration' );
    ?></p>
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
    'admin_post_adfoin_mailercloud_save_api_key',
    'adfoin_save_mailercloud_api_key',
    10,
    0
);
function adfoin_save_mailercloud_api_key()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_mailercloud_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $api_key = sanitize_text_field( $_POST["adfoin_mailercloud_api_key"] );
    // Save tokens
    update_option( "adfoin_mailercloud_api_key", $api_key );
    advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=mailercloud" );
}

add_action(
    'adfoin_add_js_fields',
    'adfoin_mailercloud_js_fields',
    10,
    1
);
function adfoin_mailercloud_js_fields( $field_data )
{
}

add_action( 'adfoin_action_fields', 'adfoin_mailercloud_action_fields' );
function adfoin_mailercloud_action_fields()
{
    ?>
    <script type="text/template" id="mailercloud-action-template">
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
    esc_attr_e( 'Mailercloud List', 'advanced-form-integration' );
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

/*
 * Mailercloud API Request
 */
function adfoin_mailercloud_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_token = get_option( 'adfoin_mailercloud_api_key' );
    $base_url = 'https://cloudapi.mailercloud.com/v1/';
    $url = $base_url . $endpoint;
    $args = array(
        'timeout' => 30,
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => $api_token,
    ),
    );
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
    'wp_ajax_adfoin_get_mailercloud_list',
    'adfoin_get_mailercloud_list',
    10,
    0
);
/*
 * Get Mailercloud subscriber lists
 */
function adfoin_get_mailercloud_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $lists = array();
    $limit = 100;
    $data = adfoin_mailercloud_request( 'lists/search', 'POST', array(
        'limit' => $limit,
        'page'  => 1,
    ) );
    
    if ( !is_wp_error( $data ) ) {
        $body = json_decode( wp_remote_retrieve_body( $data ), true );
        $lists = $body['data'];
        $lists_total = absint( $body['list_count'] );
        $pagination_needed = absint( $lists_total / $limit ) + 1;
        
        if ( $pagination_needed >= 2 ) {
            $response_pages = array();
            $response_body = array();
            for ( $i = 2 ;  $i <= $pagination_needed ;  $i++ ) {
                $response_pages[$i] = adfoin_mailercloud_request( 'lists/search', 'POST', array(
                    'limit' => $limit,
                    'page'  => $i,
                ) );
                $response_body[$i] = json_decode( wp_remote_retrieve_body( $response_pages[$i] ), true );
                if ( $response_body[$i]['data'] && is_array( $response_body[$i]['data'] ) ) {
                    $lists = array_merge( $lists, $response_body[$i]['data'] );
                }
            }
        }
        
        $final_list = wp_list_pluck( $lists, 'name', 'id' );
    }
    
    wp_send_json_success( $final_list );
}

add_action(
    'wp_ajax_adfoin_get_mailercloud_contact_fields',
    'adfoin_get_mailercloud_contact_fields',
    10,
    0
);
/*
* Get contact fields
*/
function adfoin_get_mailercloud_contact_fields()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $contact_fidlds = array();
    $endpoint = "contact/property/search";
    $params = array(
        'limit'  => 100,
        'page'   => 1,
        'search' => '',
    );
    $data = adfoin_mailercloud_request( $endpoint, 'POST', $params );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    foreach ( $body->data as $single ) {
        if ( $single->is_default == 1 ) {
            array_push( $contact_fidlds, array(
                'key'   => $single->field_value,
                'value' => $single->field_name,
            ) );
        }
    }
    wp_send_json_success( array_reverse( $contact_fidlds ) );
}

add_action(
    'adfoin_mailercloud_job_queue',
    'adfoin_mailercloud_job_queue',
    10,
    1
);
function adfoin_mailercloud_job_queue( $data )
{
    adfoin_mailercloud_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to mailercloud API
 */
function adfoin_mailercloud_send_data( $record, $posted_data )
{
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data['field_data'];
    $list_id = $data['listId'];
    $task = $record['task'];
    $holder = array();
    unset( $data['listId'] );
    foreach ( $data as $key => $value ) {
        $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
    }
    
    if ( $task == 'subscribe' ) {
        $holder = array_filter( $holder );
        if ( $list_id ) {
            $holder['list_id'] = $list_id;
        }
        $return = adfoin_mailercloud_request(
            'contacts',
            'POST',
            $holder,
            $record
        );
    }
    
    return;
}
