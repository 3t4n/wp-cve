<?php

class ADFOIN_Zohosheet extends Advanced_Form_Integration_OAuth2 {

    const authorization_endpoint = 'https://accounts.zoho.com/oauth/v2/auth';
    const token_endpoint         = 'https://accounts.zoho.com/oauth/v2/token';
    const refresh_token_endpoint = 'https://accounts.zoho.com/oauth/v2/token';

    public $data_center;
    private static $instance;

    public static function get_instance() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {

        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;

        $option = (array) maybe_unserialize( get_option( 'adfoin_zohosheet_keys' ) );

        if ( isset( $option['data_center'] ) ) {
            $this->data_center = $option['data_center'];
        }

        if ( isset( $option['client_id'] ) ) {
            $this->client_id = $option['client_id'];
        }

        if ( isset( $option['client_secret'] ) ) {
            $this->client_secret = $option['client_secret'];
        }

        if ( isset( $option['access_token'] ) ) {
            $this->access_token = $option['access_token'];
        }

        if ( isset( $option['refresh_token'] ) ) {
            $this->refresh_token = $option['refresh_token'];
        }

        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'adfoin_action_providers', array( $this, 'adfoin_zohosheet_actions' ), 10, 1 );
        add_filter( 'adfoin_settings_tabs', array( $this, 'adfoin_zohosheet_settings_tab' ), 10, 1 );
        add_action( 'adfoin_settings_view', array( $this, 'adfoin_zohosheet_settings_view' ), 10, 1 );
        add_action( 'admin_post_adfoin_save_zohosheet_keys', array( $this, 'adfoin_save_zohosheet_keys' ), 10, 0 );
        add_action( 'adfoin_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_adfoin_get_zohosheet_workbooks', array( $this, 'get_workbooks' ), 10, 0 );
        add_action( 'wp_ajax_adfoin_get_zohosheet_worksheets', array( $this, 'get_worksheets' ), 10, 0 );
        add_action( 'wp_ajax_adfoin_get_zohosheet_fields', array( $this, 'get_fields' ) );
        add_action( 'rest_api_init', array( $this, 'create_webhook_route' ) );
    }

    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/zohosheet',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'get_webhook_data' ),
                'permission_callback' => '__return_true'
            )
        );
    }

    public function get_webhook_data( $request ) {
        $params = $request->get_params();

        $code = isset( $params['code'] ) ? trim( $params['code'] ) : '';

        if ( $code ) {

            $redirect_to = add_query_arg(
                [
                    'service' => 'authorize',
                    'action'  => 'adfoin_zohosheet_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=advanced-form-integration')
            );

            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function adfoin_zohosheet_actions( $actions ) {

        $actions['zohosheet'] = array(
            'title' => __( 'Zoho Sheet', 'advanced-form-integration' ),
            'tasks' => array(
                'add_row'   => __( 'Add new record', 'advanced-form-integration' )
            )
        );

        return $actions;
    }

    public function adfoin_zohosheet_settings_tab( $providers ) {
        $providers['zohosheet'] = __( 'Zoho Sheet', 'advanced-form-integration' );

        return $providers;
    }

    public function adfoin_zohosheet_settings_view( $current_tab ) {
        if( $current_tab != 'zohosheet' ) {
            return;
        }

        $option        = (array) maybe_unserialize( get_option( 'adfoin_zohosheet_keys' ) );
        $nonce         = wp_create_nonce( 'adfoin_zohosheet_settings' );
        $data_center   = isset( $option['data_center'] ) ? $option['data_center'] : 'com';
        $client_id     = isset( $option['client_id'] ) ? $option['client_id'] : '';
        $client_secret = isset( $option['client_secret'] ) ? $option['client_secret'] : '';
        $redirect_uri  = $this->get_redirect_uri();
        ?>

        <form name='zohosheet_save_form' action='<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>'
              method='post' class='container'>

            <input type='hidden' name='action' value='adfoin_save_zohosheet_keys'>
            <input type='hidden' name='_nonce' value='<?php echo $nonce ?>'/>

            <table class='form-table'>
            <tr valign='top'>
                    <th scope='row'> <?php _e( 'Instructions', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            1. Go to <a target='_blank' rel='noopener noreferrer' href='https://api-console.zoho.com/'>Zohosheet API Console</a>.</br>
                            2. Click Add Client, Choose Server-based Applications.</br>
                            3. Insert a suitable Client Name.</br>
                            4. Insert URL of your website as Homepage URL.</br>
                            5. Copy the URI from below and paste in <b>Authorized Redirect URIs</b> input box.</br>
                            6. Click CREATE.</br>
                            7. You will receive Client ID and Client Secret copy and paste below.</br>
                            8. Click <b>Authorize</b> below.
                        </p>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php _e( 'Redirect URI', 'advanced-form-integration' ); ?></th>
                    <td>
                        <code><?php echo esc_url( $redirect_uri ); ?></code>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php _e( 'Data Center', 'advanced-form-integration' ); ?></th>
                    <td>
                        <select name="zoho_data_center" id="zoho-data-center">
                            <option value="com" <?php selected( $data_center, 'com' ); ?>>zoho.com</option>
                            <option value="eu" <?php selected( $data_center, 'eu' ); ?>>zoho.eu</option>
                            <option value="in" <?php selected( $data_center, 'in' ); ?>>zoho.in</option>
                            <option value="com.cn" <?php selected( $data_center, 'com.cn' ); ?>>zoho.com.cn</option>
                            <option value="com.au" <?php selected( $data_center, 'com.au' ); ?>>zoho.com.au</option>
                            <option value="jp" <?php selected( $data_center, 'jp' ); ?>>zoho.jp</option>
                        </select>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php _e( 'Client ID', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type='text' name='adfoin_zohosheet_client_id'
                               value='<?php echo esc_attr( $client_id ); ?>' placeholder='<?php _e( 'Enter Client ID', 'advanced-form-integration' ); ?>'
                               class='regular-text'/>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php _e( 'Client Secret', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type='text' name='adfoin_zohosheet_client_secret'
                               value='<?php echo esc_attr( $client_secret ); ?>' placeholder='<?php _e( 'Enter Client Secret', 'advanced-form-integration' ); ?>'
                               class='regular-text'/>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php _e( 'Status', 'advanced-form-integration' ); ?></th>
                    <td>
                        <?php
                        if( $this->is_active() ) {
                            _e( 'Connected', 'advanced-form-integration' );
                        } else {
                            _e( 'Not Connected', 'advanced-form-integration' );
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button( __( 'Authorize', 'advanced-form-integration' ) ); ?>
        </form>

        <?php
    }

    public function adfoin_save_zohosheet_keys() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_zohosheet_settings' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $data_center   = isset( $_POST['zoho_data_center'] ) ? sanitize_text_field( $_POST['zoho_data_center'] ) : 'com';
        $client_id     = isset( $_POST['adfoin_zohosheet_client_id'] ) ? sanitize_text_field( $_POST['adfoin_zohosheet_client_id'] ) : '';
        $client_secret = isset( $_POST['adfoin_zohosheet_client_secret'] ) ? sanitize_text_field( $_POST['adfoin_zohosheet_client_secret'] ) : '';

        if( !$client_id || !$client_secret ) {
            $this->reset_data();
        } else{
            $this->data_center   = trim( $data_center );
            $this->client_id     = trim( $client_id );
            $this->client_secret = trim( $client_secret );

            $this->save_data();
            $this->authorize( 'ZohoSheet.dataAPI.READ,ZohoSheet.dataAPI.UPDATE' );
        }

        advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=zohosheet' );
    }

    protected function authorize( $scope = '' ) {
        
        $data = array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'prompt'        => 'Consent',
            'access_type'   => 'offline',
            'redirect_uri'  => urlencode( $this->get_redirect_uri() )
        );

        if( $scope ) {
            $data['scope'] = $scope;
        }

        $auth_endpoint = $this->authorization_endpoint;

        if( $this->data_center && $this->data_center !== 'com' ) {
            $auth_endpoint = str_replace( 'com', $this->data_center, $this->authorization_endpoint );
        }

        $endpoint = add_query_arg( $data, $auth_endpoint );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function request_token( $authorization_code ) {

        $tok_endpoint = $this->token_endpoint;

        if( $this->data_center && $this->data_center !== 'com' ) {
            $tok_endpoint = str_replace( 'com', $this->data_center, $this->token_endpoint );
        }

        $endpoint = add_query_arg(
            array(
                'code'         => $authorization_code,
                'redirect_uri' => urlencode( $this->get_redirect_uri() ),
                'grant_type'   => 'authorization_code',
            ),
            $tok_endpoint
        );

        $request = [
            'headers' => [
                'Authorization' => $this->get_http_authorization_header( 'basic' ),
            ],
        ];

        $response      = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );

        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }

            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            } else {
                $this->refresh_token = null;
            }
        }

        $this->save_data();

        return $response;
    }

    protected function refresh_token() {

        $ref_endpoint = $this->refresh_token_endpoint;

        if( $this->data_center && $this->data_center !== 'com' ) {
            $ref_endpoint = str_replace( 'com', $this->data_center, $this->refresh_token_endpoint );
        }

        $endpoint = add_query_arg(
            array(
                'refresh_token' => $this->refresh_token,
                'grant_type'    => 'refresh_token',
            ),
            $ref_endpoint
        );

        $request = [
            'headers' => array(
                'Authorization' => $this->get_http_authorization_header( 'basic' ),
            ),
        ];

        $response      = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );

        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }

            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            }
        }

        $this->save_data();

        return $response;
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="zohosheet-action-template">
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
                            <?php esc_attr_e( 'Workbook', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[workbookId]" v-model="fielddata.workbookId" required="required" @change="getWorksheets">
                            <option value=""> <?php _e( 'Select Workbook...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.workbooks" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': workbookLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'add_row'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Worksheet', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[worksheetId]" v-model="fielddata.worksheetId" required="required" @change="getFields">
                            <option value=""> <?php _e( 'Select Worksheet...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.worksheets" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': worksheetLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>

            </table>
        </script>
        <?php
    }

    public function auth_redirect() {

        $auth   = isset( $_GET['auth'] ) ? trim( $_GET['auth'] ) : '';
        $code   = isset( $_GET['code'] ) ? trim( $_GET['code'] ) : '';
        $action = isset( $_GET['action'] ) ? trim( $_GET['action'] ) : '';

        if ( 'adfoin_zohosheet_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            if ( ! empty( $this->access_token ) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }

            wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=zohosheet' ) );

            exit();
        }
    }

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'adfoin_zohosheet_keys' ) );

        $option = array_merge(
            $data,
            array(
                'data_center'  => $this->data_center,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token
            )
        );

        update_option( 'adfoin_zohosheet_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {

        $this->data_center  = 'com';
        $this->client_id     = '';
        $this->client_secret = '';
        $this->access_token  = '';
        $this->refresh_token = '';

        $this->save_data();
    }

    protected function get_redirect_uri() {

        return site_url( '/wp-json/advancedformintegration/zohosheet' );
    }

    public function zohosheet_request($endpoint, $method = 'GET', $data = array(), $record = array() ) {

        $base_url = 'https://sheet.zoho.com/api/v2/';
    
    
        if( $this->data_center && $this->data_center !== 'com' ) {
            $base_url = str_replace( 'com', $this->data_center, $base_url );
        }
    
        $url = $base_url . $endpoint;
    
        $args = array(
            'method'  => $method,
            'timeout' => 30,
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
        );
    
        if ('POST' == $method || 'PUT' == $method) {
            if( $data ) {
                $args['body'] = json_encode( $data );
            }
            
        }
    
        $response = $this->remote_request( $url, $args, $record );
    
        return $response;
    }
    
    protected function remote_request( $url, $request = array(), $record = array() ) {
    
        static $refreshed = false;
    
        $request = wp_parse_args( $request, [ ] );
    
        $request['headers'] = array_merge(
            $request['headers'],
            array( 'Authorization' => $this->get_http_authorization_header( 'bearer' ), )
            
        );
    
        $response = wp_remote_request( $url, $request );
    
        if ( 401 === wp_remote_retrieve_response_code( $response )
            and !$refreshed
        ) {
            $this->refresh_token();
            $refreshed = true;
    
            $response = $this->remote_request( $url, $request );
        }
    
        if( $record ) {
            adfoin_add_to_log( $response, $url, $request, $record );
        }
    
        return $response;
    }

    /*
    * Get zohosheet Workbook List
    */
    public function get_workbooks() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $data = $this->zohosheet_request( 'workbooks?method=workbook.list' );

        if( is_wp_error( $data ) ) {
            wp_send_json_error();
        }

        $body      = json_decode( wp_remote_retrieve_body( $data ), true );
        $workbooks = array();

        if( isset( $body['workbooks'] ) && is_array( $body['workbooks'] ) ) {
            foreach( $body['workbooks'] as $workbook ) {
                $workbooks[$workbook['resource_id']] = $workbook['workbook_name'];
            }
        }

        wp_send_json_success( $workbooks );
    }

    /*
    * Get Worksheets
    */
    function get_worksheets() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $workbook_id = isset( $_POST['workbookId'] ) ? $_POST['workbookId'] : '';
        $data        = $this->zohosheet_request( $workbook_id . '?method=worksheet.list' );

        if( is_wp_error( $data ) ) {
            wp_send_json_error();
        }

        $body       = json_decode( wp_remote_retrieve_body( $data ), true );
        $worksheets = array();

        if( isset( $body['worksheet_names'] ) && is_array( $body['worksheet_names'] ) ) {
            foreach( $body['worksheet_names'] as $worksheet ) {
                $worksheets[$worksheet['worksheet_name']] = $worksheet['worksheet_name'];
            }
        }

        wp_send_json_success( $worksheets );
    }

    /*
    * Get zohosheet Fields
    */
    function get_fields() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $workbook_id  = isset( $_POST['workbookId'] ) ? $_POST['workbookId'] : '';
        $worksheet_id = isset( $_POST['worksheetId'] ) ? $_POST['worksheetId'] : '';
        $data         = $this->zohosheet_request( $workbook_id . '?method=range.content.get&worksheet_name=' . $worksheet_id . '&start_row=1&start_column=1&end_row=1&end_column=1024' );

        if( is_wp_error( $data ) ) {
            wp_send_json_error();
        }

        $body   = json_decode( wp_remote_retrieve_body( $data ), true );
        $fields = array();

        if( isset( $body['range_details'] ) && 
        is_array( $body['range_details'] ) && 
        isset( $body['range_details'][0] ) && 
        isset( $body['range_details'][0]['row_details'] ) ) {

            $header_row = $body['range_details'][0]['row_details'];

            if( is_array( $header_row ) ) {
                foreach( $header_row as $header ) {
                    array_push( $fields, array(
                        'key'         => $header['content'],
                        'value'       => $header['content'],
                        'description' => ''
                    ));
                }
            }
        }

        wp_send_json_success( $fields );
    }

}

$zohosheet = ADFOIN_Zohosheet::get_instance();

add_action( 'adfoin_zohosheet_job_queue', 'adfoin_zohosheet_job_queue', 10, 1 );

function adfoin_zohosheet_job_queue( $data ) {
    adfoin_zohosheet_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to zohosheet API
 */
function adfoin_zohosheet_send_data( $record, $posted_data ) {

    $record_data    = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data         = $record_data['field_data'];
    $workbook_id  = $data['workbookId'];
    $worksheet_id = $data['worksheetId'];
    $task         = $record['task'];

    if( $task == 'add_row' ) {

        unset( $data['workbookId'] );
        unset( $data['worksheetId'] );
        unset( $data['workbooks'] );
        unset( $data['worksheets'] );

        $zohosheet = ADFOIN_Zohosheet::get_instance();
        $holder    = array();

        foreach ( $data as $key => $value ) {
            if( $value ) {
                $parsed_value = adfoin_get_parsed_values( $value, $posted_data );
                $holder[$key] = $parsed_value;
            }
            
        }

        $endpoint = $workbook_id . '?method=worksheet.records.add&worksheet_name=' . $worksheet_id . '&json_data=[' . json_encode( $holder ) . ']';

        $endpont = 'https://sheet.zoho.com/api/v2/5q2yj4d27fcabc7d544cfb9668387966083aa?method=worksheet.records.add&worksheet_name=Sheet1&header_row=1&json_data=[{"Email":"manna@pluginja.com","First Name":"Manna","Last Name":"Salwa"}]';

        $return = $zohosheet->zohosheet_request( $endpoint, 'POST', array(), $record );
    }

    return;
}