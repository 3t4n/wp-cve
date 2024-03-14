<?php

add_filter(
    'adfoin_action_providers',
    'adfoin_acelle_actions',
    10,
    1
);
function adfoin_acelle_actions( $actions )
{
    $actions['acelle'] = array(
        'title' => __( 'Acelle Mail', 'advanced-form-integration' ),
        'tasks' => array(
        'subscribe' => __( 'Subscribe To List', 'advanced-form-integration' ),
    ),
    );
    return $actions;
}

add_filter(
    'adfoin_settings_tabs',
    'adfoin_acelle_settings_tab',
    10,
    1
);
function adfoin_acelle_settings_tab( $providers )
{
    $providers['acelle'] = __( 'Acelle Mail', 'advanced-form-integration' );
    return $providers;
}

add_action(
    'adfoin_settings_view',
    'adfoin_acelle_settings_view',
    10,
    1
);
function adfoin_acelle_settings_view( $current_tab )
{
    if ( $current_tab != 'acelle' ) {
        return;
    }
    $nonce = wp_create_nonce( 'adfoin_acelle_settings' );
    ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					
						<h2 class="hndle"><span><?php 
    esc_attr_e( 'Acelle Accounts', 'advanced-form-integration' );
    ?></span></h2>
						<div class="inside">
                            <div id="acelle-auth">


                                <table v-if="tableData.length > 0" class="wp-list-table widefat striped">
                                    <thead>
                                        <tr>
                                            <th><?php 
    _e( 'Title', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'API Ednpoint', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'API Token', 'advanced-form-integration' );
    ?></th>
                                            <th><?php 
    _e( 'Actions', 'advanced-form-integration' );
    ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in tableData" :key="index">
                                            <td>{{ row.title }}</td>
                                            <td>{{ row.apiEndpoint }}</td>
                                            <td>{{ formatApiKey(row.apiToken) }}</td>
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
    _e( 'API Endpoint', 'advanced-form-integration' );
    ?></th>
                                            <td>
                                                <input type="text" class="regular-text"v-model="rowData.apiEndpoint" placeholder="API Endpoint" />
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"> <?php 
    _e( 'API Token', 'advanced-form-integration' );
    ?></th>
                                            <td>
                                                <input type="text" class="regular-text"v-model="rowData.apiToken" placeholder="API Token" required />
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
                                    <li>Go to My Profile > Account > API Token.</li>
                                    <li>Copy API Endpoint and API Token here.</li>
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

function adfoin_acelle_credentials_list()
{
    $html = '';
    $credentials = adfoin_read_credentials( 'acelle' );
    foreach ( $credentials as $option ) {
        $html .= '<option value="' . $option['id'] . '">' . $option['title'] . '</option>';
    }
    echo  $html ;
}

add_action( 'adfoin_action_fields', 'adfoin_acelle_action_fields' );
function adfoin_acelle_action_fields()
{
    ?>
    <script type="text/template" id="acelle-action-template">
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
    esc_attr_e( 'acelle List', 'advanced-form-integration' );
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

function adfoin_acelle_get_credentials( $cred_id )
{
    $credentials = array();
    $all_credentials = adfoin_read_credentials( 'acelle' );
    
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
 * Acelle API Private Request
 */
function adfoin_acelle_request(
    $endpoint,
    $method = 'GET',
    $data = array(),
    $record = array(),
    $cred_id = ''
)
{
    $credentials = adfoin_acelle_get_credentials( $cred_id );
    $api_endpoint = ( isset( $credentials['apiEndpoint'] ) ? $credentials['apiEndpoint'] : '' );
    $api_token = ( isset( $credentials['apiToken'] ) ? $credentials['apiToken'] : '' );
    $base_url = $api_endpoint;
    $base_url = rtrim( $base_url, '/' ) . '/';
    $url = $base_url . $endpoint;
    $url = add_query_arg( 'api_token', $api_token, $url );
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

add_action(
    'wp_ajax_adfoin_get_acelle_credentials',
    'adfoin_get_acelle_credentials',
    10,
    0
);
function adfoin_get_acelle_credentials()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $all_credentials = adfoin_read_credentials( 'acelle' );
    wp_send_json_success( $all_credentials );
}

add_action(
    'wp_ajax_adfoin_save_acelle_credentials',
    'adfoin_save_acelle_credentials',
    10,
    0
);
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_save_acelle_credentials()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $platform = sanitize_text_field( $_POST['platform'] );
    
    if ( 'acelle' == $platform ) {
        $data = $_POST['data'];
        adfoin_save_credentials( $platform, $data );
    }
    
    wp_send_json_success();
}

add_action(
    'wp_ajax_adfoin_get_acelle_list',
    'adfoin_get_acelle_list',
    10,
    0
);
/*
 * Get Kalviyo subscriber lists
 */
function adfoin_get_acelle_list()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $data = adfoin_acelle_request( 'lists' );
    if ( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $data ) );
    $lists = wp_list_pluck( $body, 'name', 'uid' );
    wp_send_json_success( $lists );
}

add_action(
    'adfoin_acelle_job_queue',
    'adfoin_acelle_job_queue',
    10,
    1
);
function adfoin_acelle_job_queue( $data )
{
    adfoin_acelle_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to acelle API
 */
function adfoin_acelle_send_data( $record, $posted_data )
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
        $email = ( empty($data['EMAIL']) ? '' : adfoin_get_parsed_values( $data['EMAIL'], $posted_data ) );
        $subscriber_data = array(
            'EMAIL' => trim( $email ),
        );
        if ( isset( $data['FIRST_NAME'] ) && $data['FIRST_NAME'] ) {
            $subscriber_data['FIRST_NAME'] = adfoin_get_parsed_values( $data['FIRST_NAME'], $posted_data );
        }
        if ( isset( $data['LAST_NAME'] ) && $data['LAST_NAME'] ) {
            $subscriber_data['LAST_NAME'] = adfoin_get_parsed_values( $data['LAST_NAME'], $posted_data );
        }
        if ( $list_id ) {
            $subscriber_data['list_uid'] = $list_id;
        }
        $return = adfoin_acelle_request(
            'subscribers',
            'POST',
            $subscriber_data,
            $record,
            $cred_id
        );
    }
    
    return;
}
