<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_klaviyo_actions',
    10,
    1
);
function adfoin_klaviyo_actions( $actions )
{
    $actions['klaviyo'] = array(
        'title' => __( 'Klaviyo', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_klaviyo_settings_tab',
    10,
    1
);
function adfoin_klaviyo_settings_tab( $providers )
{
    $providers['klaviyo'] = __( 'Klaviyo', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_klaviyo_settings_view',
    10,
    1
);
function adfoin_klaviyo_settings_view( $current_tab )
{
    if ( $current_tab != 'klaviyo' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_klaviyo_settings' );
    $pub_api_key = ( get_option( 'adfoin_klaviyo_public_api_key' ) ? get_option( 'adfoin_klaviyo_public_api_key' ) : '' );
    $api_token = ( get_option( 'adfoin_klaviyo_api_token' ) ? get_option( 'adfoin_klaviyo_api_token' ) : '' );
    ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					
						<h2 class="hndle"><span><?php 
    esc_attr_e( 'Klaviyo Accounts', 'advanced-form-integration' );
    ?></span></h2>
						<div class="inside">
                            <div id="klaviyo-auth">


                                <table v-if="tableData.length > 0" class="wp-list-table widefat striped">
                                    <thead>
                                        <tr>
                                            <th><?php 
    _e( 'Title', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'Public API Key', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'Privat API Key', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'Actions', 'advanced-form-integration' );
    ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in tableData" :key="index">
                                            <td>{{ row.title }}</td>
                                            <td>{{ formatApiKey(row.publicKey) }}</td>
                                            <td>{{ formatApiKey(row.privateKey) }}</td>
                                            <td>
                                                <button @click="editRow(index)"><span class="dashicons dashicons-edit"></span></button>
                                                <button @click="confirmDelete(index)"><span class="dashicons dashicons-trash"></span></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <form @submit.prevent="addOrUpdateRow">
                                    <table class="form-table">
                                        <tr valign="top">
                                            <th scope="row"> <?php 
    _e( 'Title', 'advanced-form-integration' );
    ?></th>
                                            <td>
                                                <input type="text" class="regular-text"v-model="rowData.title" placeholder="Add any title here" required />
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"> <?php 
    _e( 'Public API Key', 'advanced-form-integration' );
    ?></th>
                                            <td>
                                                <input type="text" class="regular-text"v-model="rowData.publicKey" placeholder="Public API Key" />
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"> <?php 
    _e( 'Private API Key', 'advanced-form-integration' );
    ?></th>
                                            <td>
                                                <input type="text" class="regular-text"v-model="rowData.privateKey" placeholder="Private API Key" required />
                                            </td>
                                        </tr>
                                    </table>
                                    <button class="button button-primary" type="submit">{{ isEditing ? 'Update' : 'Add' }}</button>
                                </form>


                            </div>
						</div>
						<!-- .inside -->
					
				</div>
				<!-- .meta-box-sortables .ui-sortable -->
			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
						<h2 class="hndle"><span><?php 
    esc_attr_e( 'Instructions', 'advanced-form-integration' );
    ?></span></h2>
						<div class="inside">
                        <div class="card" style="margin-top: 0px;">
                            <p>
                                <ol>
                                    <li><a href="https://www.klaviyo.com/account#api-keys-tab" target="_blank" rel="noopener noreferrer"><?php 
    _e( 'Click here to get the API Keys', 'advanced-form-integration' );
    ?></a></li>
                                    <li>Generate a Private API Key with full access.</li>
                                    <li>Public API Key is required only for tracking integrations.</li>
                                <ol>
                            </p>
                        </div>
                        
						</div>
						<!-- .inside -->
				</div>
				<!-- .meta-box-sortables -->
			</div>
			<!-- #postbox-container-1 .postbox-container -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear">
	</div>
    <?php 
}

add_filter(
    'adfoin_get_credentials',
    'adfoin_klaviyo_modify_credentials',
    10,
    2
);
function adfoin_klaviyo_modify_credentials( $credentials, $platform )
{
    
    if ( 'klaviyo' == $platform && empty($credentials) ) {
        $private_key = ( get_option( 'adfoin_klaviyo_api_token' ) ? get_option( 'adfoin_klaviyo_api_token' ) : '' );
        if ( $private_key ) {
            $credentials[] = array(
                'id'         => '123456',
                'title'      => __( 'Untitled', 'advanced-form-integration' ),
                'publicKey'  => ( get_option( 'adfoin_klaviyo_public_api_key' ) ? get_option( 'adfoin_klaviyo_public_api_key' ) : '' ),
                'privateKey' => $private_key,
            );
        }
    }
    
    return $credentials;
}

// add_action( 'admin_post_adfoin_klaviyo_save_api_token', 'adfoin_save_klaviyo_api_token', 10, 0 );
// function adfoin_save_klaviyo_api_token() {
//     // Security Check
//     if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_klaviyo_settings' ) ) {
//         die( __( 'Security check Failed', 'advanced-form-integration' ) );
//     }
//     $pub_api_key = sanitize_text_field( $_POST["adfoin_klaviyo_public_api_key"] );
//     $api_token   = sanitize_text_field( $_POST["adfoin_klaviyo_api_token"] );
//     // Save tokens
//     update_option( "adfoin_klaviyo_public_api_key", $pub_api_key );
//     update_option( "adfoin_klaviyo_api_token", $api_token );
//     advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=klaviyo" );
// }
// add_action( 'adfoin_add_js_fields', 'adfoin_klaviyo_js_fields', 10, 1 );
// function adfoin_klaviyo_js_fields( $field_data ) {}
function adfoin_klaviyo_credentials_list()
{
    $html = '';
    $credentials = adfoin_read_credentials( 'klaviyo' );
    foreach ( $credentials as $option ) {
        $html .= '<option value="' . $option['id'] . '">' . $option['title'] . '</option>';
    }
    echo  $html ;
}

add_action( 'adfoin_action_fields', 'adfoin_klaviyo_action_fields' );
function adfoin_klaviyo_action_fields()
{
    ?>
    <script type="text/template" id="klaviyo-action-template">
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
    esc_attr_e( 'Klaviyo Account', 'advanced-form-integration' );
    ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[credId]" v-model="fielddata.credId" @change="getLists">
                    <option value=""> <?php 
    _e( 'Select Account...', 'advanced-form-integration' );
    ?> </option>
                        <?php 
    adfoin_klaviyo_credentials_list();
    ?>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php 
    esc_attr_e( 'Klaviyo List', 'advanced-form-integration' );
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

function adfoin_klaviyo_get_credentials( $cred_id )
{
    $credentials = array();
    $all_credentials = adfoin_read_credentials( 'klaviyo' );
    
    if ( is_array( $all_credentials ) ) {
        $credentials = $all_credentials[0];
        foreach ( $all_credentials as $single ) {
            if ( $cred_id && $cred_id == $single['id'] ) {
                $credentials = $single;
            }
        }
    }
    
    return $credentials;
}

/*
 * Klaviyo API Private Request
 */
function adfoin_klaviyo_private_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array(),
    $cred_id = ''
)
{
    $credentials = adfoin_klaviyo_get_credentials( $cred_id );
    $api_token = ( isset( $credentials['privateKey'] ) ? $credentials['privateKey'] : '' );
    $base_url = 'https://a.klaviyo.com/api/v2/';
    $url = $base_url . $endpoint;
    $url = add_query_arg( 'api_key', $api_token, $url );
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type' => 'application/json',
    ),
    );
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

/*
 * Klaviyo API Private Request revision 2023-02-22
 */
function adfoin_klaviyo_private_request_20230222(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array()
)
{
    $api_key = get_option( 'adfoin_klaviyo_api_token' );
    $base_url = 'https://a.klaviyo.com/api/';
    $url = $base_url . $endpoint;
    $args = array(
        'method'  => $method,
        'headers' => array(
        'Content-Type'  => 'application/json',
        'Accpet'        => 'application/json',
        'revision'      => '2023-02-22',
        'Authorization' => 'Klaviyo-API-Key ' . $api_key,
    ),
    );
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
    'wp_ajax_adfoin_get_klaviyo_credentials',
    'adfoin_get_klaviyo_credentials',
    10,
    0
);
function adfoin_get_klaviyo_credentials()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $all_credentials = adfoin_read_credentials( 'klaviyo' );
    wp_send_json_success( $all_credentials );
}

add_action(
    'wp_ajax_adfoin_save_klaviyo_credentials',
    'adfoin_save_klaviyo_credentials',
    10,
    0
);
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_save_klaviyo_credentials()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $platform = sanitize_text_field( $_POST['platform'] );
    
    if ( 'klaviyo' == $platform ) {
        $data = $_POST['data'];
        adfoin_save_credentials( $platform, $data );
    }
    
    wp_send_json_success();
}

add_action(
    'wp_ajax_adfoin_get_klaviyo_list',
    'adfoin_get_klaviyo_list',
    10,
    0
);
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_klaviyo_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $cred_id = sanitize_text_field( $_POST['credId'] );
    $data = adfoin_klaviyo_private_request(
        'lists',
        'GET',
        array(),
        array(),
        $cred_id
    );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    $lists = wp_list_pluck( $body, 'list_name', 'list_id' );
    wp_send_json_success( $lists );
}

add_action(
    'adfoin_klaviyo_job_queue',
    'adfoin_klaviyo_job_queue',
    10,
    1
);
function adfoin_klaviyo_job_queue( $data )
{
    adfoin_klaviyo_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Klaviyo API
 */
function adfoin_klaviyo_send_data( $record, $posted_data )
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
    $list_id = ( isset( $data['listId'] ) ? $data['listId'] : '' );
    $cred_id = ( isset( $data['credId'] ) ? $data['credId'] : '' );
    $task = $record['task'];
    
    if ( $task == 'subscribe' ) {
        $email = ( empty($data['email']) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) ) );
        $req_data = array(
            'profiles' => array(
            'email' => $email,
        ),
        );
        if ( isset( $data['firstName'] ) && $data['firstName'] ) {
            $req_data['profiles']['first_name'] = adfoin_get_parsed_values( $data['firstName'], $posted_data );
        }
        if ( isset( $data['lastName'] ) && $data['lastName'] ) {
            $req_data['profiles']['last_name'] = adfoin_get_parsed_values( $data['lastName'], $posted_data );
        }
        if ( isset( $data['title'] ) && $data['title'] ) {
            $req_data['profiles']['$title'] = adfoin_get_parsed_values( $data['title'], $posted_data );
        }
        if ( isset( $data['organization'] ) && $data['organization'] ) {
            $req_data['profiles']['$organization'] = adfoin_get_parsed_values( $data['organization'], $posted_data );
        }
        if ( isset( $data['address1'] ) && $data['address1'] ) {
            $req_data['profiles']['$address1'] = adfoin_get_parsed_values( $data['address1'], $posted_data );
        }
        if ( isset( $data['address2'] ) && $data['address2'] ) {
            $req_data['profiles']['$address2'] = adfoin_get_parsed_values( $data['address2'], $posted_data );
        }
        if ( isset( $data['city'] ) && $data['city'] ) {
            $req_data['profiles']['$city'] = adfoin_get_parsed_values( $data['city'], $posted_data );
        }
        if ( isset( $data['region'] ) && $data['region'] ) {
            $req_data['profiles']['$region'] = adfoin_get_parsed_values( $data['region'], $posted_data );
        }
        if ( isset( $data['zip'] ) && $data['zip'] ) {
            $req_data['profiles']['$zip'] = adfoin_get_parsed_values( $data['zip'], $posted_data );
        }
        if ( isset( $data['country'] ) && $data['country'] ) {
            $req_data['profiles']['$country'] = adfoin_get_parsed_values( $data['country'], $posted_data );
        }
        if ( isset( $data['latitude'] ) && $data['latitude'] ) {
            $req_data['profiles']['$latitude'] = adfoin_get_parsed_values( $data['latitude'], $posted_data );
        }
        if ( isset( $data['longitude'] ) && $data['longitude'] ) {
            $req_data['profiles']['$longitude'] = adfoin_get_parsed_values( $data['longitude'], $posted_data );
        }
        if ( isset( $data['timezone'] ) && $data['timezone'] ) {
            $req_data['profiles']['$timezone'] = adfoin_get_parsed_values( $data['timezone'], $posted_data );
        }
        if ( isset( $data['externalId'] ) && $data['externalId'] ) {
            $req_data['profiles']['$id'] = adfoin_get_parsed_values( $data['externalId'], $posted_data );
        }
        if ( isset( $data['source'] ) && $data['source'] ) {
            $req_data['profiles']['$source'] = adfoin_get_parsed_values( $data['source'], $posted_data );
        }
        
        if ( isset( $data['phoneNumber'] ) && $data['phoneNumber'] ) {
            $phone_number = preg_replace( '/[^0-9+]/', '', adfoin_get_parsed_values( $data['phoneNumber'], $posted_data ) );
            if ( strlen( $phone_number ) > 7 ) {
                $req_data['profiles']['phone_number'] = $phone_number;
            }
        }
        
        $sub_endpoint = "list/{$list_id}/subscribe";
        $return = adfoin_klaviyo_private_request(
            $sub_endpoint,
            'POST',
            $req_data,
            $record,
            $cred_id
        );
    }
    
    return;
}
