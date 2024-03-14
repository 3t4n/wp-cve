<?php

class ADFOIN_GoogleSheets extends Advanced_Form_Integration_OAuth2
{
    const  service_name = 'googlesheets' ;
    const  authorization_endpoint = 'https://accounts.google.com/o/oauth2/auth' ;
    const  token_endpoint = 'https://www.googleapis.com/oauth2/v3/token' ;
    private static  $instance ;
    protected  $client_id = '' ;
    protected  $client_secret = '' ;
    protected  $google_access_code = '' ;
    protected  $sheet_lists = array() ;
    public static function get_instance()
    {
        if ( empty(self::$instance) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->token_endpoint = self::token_endpoint;
        $this->authorization_endpoint = self::authorization_endpoint;
        $option = (array) maybe_unserialize( get_option( 'adfoin_googlesheets_keys' ) );
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
        if ( $this->is_active() ) {
            if ( isset( $option['sheet_lists'] ) ) {
                $this->sheet_lists = $option['sheet_lists'];
            }
        }
        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter(
            'adfoin_action_providers',
            array( $this, 'adfoin_googlesheets_actions' ),
            10,
            1
        );
        add_filter(
            'adfoin_settings_tabs',
            array( $this, 'adfoin_googlesheets_settings_tab' ),
            10,
            1
        );
        add_action(
            'adfoin_settings_view',
            array( $this, 'adfoin_googlesheets_settings_view' ),
            10,
            1
        );
        add_action(
            'admin_post_adfoin_save_googlesheets_keys',
            array( $this, 'adfoin_save_googlesheets_keys' ),
            10,
            0
        );
        add_action(
            'adfoin_action_fields',
            array( $this, 'action_fields' ),
            10,
            1
        );
        add_action(
            'wp_ajax_adfoin_get_spreadsheet_list',
            array( $this, 'get_spreadsheet_list' ),
            10,
            0
        );
        add_action(
            'wp_ajax_adfoin_googlesheets_get_worksheets',
            array( $this, 'get_worksheets' ),
            10,
            0
        );
        add_action(
            'wp_ajax_adfoin_googlesheets_get_headers',
            array( $this, 'get_headers' ),
            10,
            0
        );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
    }
    
    public function create_webhook_route()
    {
        register_rest_route( 'advancedformintegration', '/googlesheets', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_webhook_data' ),
            'permission_callback' => '__return_true',
        ) );
    }
    
    public function get_webhook_data( $request )
    {
        $params = $request->get_params();
        $code = ( isset( $params['code'] ) ? trim( $params['code'] ) : '' );
        
        if ( $code ) {
            $redirect_to = add_query_arg( [
                'service' => 'authorize',
                'action'  => 'adfoin_googlesheets_auth_redirect',
                'code'    => $code,
            ], admin_url( 'admin.php?page=advanced-form-integration' ) );
            wp_safe_redirect( $redirect_to );
            exit;
        }
    
    }
    
    public function auth_redirect()
    {
        $action = ( isset( $_GET['action'] ) ? sanitize_text_field( trim( $_GET['action'] ) ) : '' );
        
        if ( 'adfoin_googlesheets_auth_redirect' == $action ) {
            $code = ( isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '' );
            if ( $code ) {
                $this->request_token( $code );
            }
            wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=googlesheets' ) );
            exit;
        }
    
    }
    
    public function adfoin_googlesheets_actions( $actions )
    {
        $actions['googlesheets'] = array(
            'title' => __( 'Google Sheets', 'advanced-form-integration' ),
            'tasks' => array(
            'add_row' => __( 'Add New Row', 'advanced-form-integration' ),
        ),
        );
        return $actions;
    }
    
    public function adfoin_googlesheets_settings_tab( $providers )
    {
        $providers['googlesheets'] = __( 'Google Sheets', 'advanced-form-integration' );
        return $providers;
    }
    
    public function adfoin_googlesheets_settings_view( $current_tab )
    {
        if ( $current_tab != 'googlesheets' ) {
            return;
        }
        $option = (array) maybe_unserialize( get_option( 'adfoin_googlesheets_keys' ) );
        $nonce = wp_create_nonce( "adfoin_googlesheets_settings" );
        $client_id = ( isset( $option['client_id'] ) ? $option['client_id'] : "" );
        $client_secret = ( isset( $option['client_secret'] ) ? $option['client_secret'] : "" );
        $redirect_uri = $this->get_redirect_uri();
        $domain = parse_url( get_site_url() );
        $host = $domain['host'];
        ?>

        <form name="googlesheets_save_form" action="<?php 
        echo  esc_url( admin_url( 'admin-post.php' ) ) ;
        ?>"
              method="post" class="container">

            <input type="hidden" name="action" value="adfoin_save_googlesheets_keys">
            <input type="hidden" name="_nonce" value="<?php 
        echo  $nonce ;
        ?>"/>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <?php 
        _e( 'Instructions', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <p>
                            <?php 
        _e( '1. Go to <a target="_blank" rel="noopener noreferrer" href="https://console.developers.google.com/">Google Developer Console</a> and create a <b>New Project</b><br>
                                      2. Go to <b>Library</b> side menu and search for <b>Google Sheets API</b>, open it and click <b>ENABLE</b>.<br>
                                      3. Again search for <b>Google Drive API</b>, open it and click <b>ENABLE</b>.<br>', 'advanced-form-integration' );
        ?>
                                      <?php 
        printf( __( '4. Go to <b>OAuth consent screen</b>, select <b>External</b> click <b>Create</b>. Put an <b>Applicatoin name</b> as you want, select user support email, enter <code><i>%s</i></code> in <b>Authorized domains</b>, put your email on developer contact email. In scopes add spreadsheet read/write and drive readonly scopes. then click <b>Save</b>. Please set the publishing status as <b>in production</b>, otherwise you might get a 403 error.<br>', 'advanced-form-integration' ), $host );
        ?>
                                      <?php 
        printf( __( '5. Go to <b>Credentials</b>, click <b>CREATE CREDENTIALS</b>, select <b>OAuth client ID</b>, select application type as <b>Web application</b>, click <b>Create</b>, put anything in <b>Name</b>, save <code><i>%s</i></code> in <b>Authorized redirect URIs</b>, click <b>Create</b>.<br>', 'advanced-form-integration' ), $redirect_uri );
        ?>
                                      <?php 
        _e( '6. Copy <b>Client ID</b> and <b>Client Secret</b> from newly created app and save below.<br>', 'advanced-form-integration' );
        ?>
                                      <?php 
        _e( '7. Click <b>Save & Authorize</b>, if appears <b>App is not verified</b> error click <b>show advanced</b> and then <b>Go to App</b>.<br><br>', 'advanced-form-integration' );
        ?>
                                      <?php 
        _e( '(For more detailed instructions, check <a target="_blank" rel="noopener noreferrer" href="https://advancedformintegration.com/docs/afi/receiver-platforms/google-sheets/">the documentation</a>.)', 'advanced-form-integration' );
        ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php 
        _e( 'Status', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <?php 
        
        if ( $this->is_active() ) {
            _e( 'Connected', 'advanced-form-integration' );
        } else {
            _e( 'Not Connected', 'advanced-form-integration' );
        }
        
        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php 
        _e( 'Client ID', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <input type="text" name="adfoin_googlesheets_client_id"
                               value="<?php 
        echo  esc_attr( $client_id ) ;
        ?>" placeholder="<?php 
        _e( 'Enter Client ID', 'advanced-form-integration' );
        ?>"
                               class="regular-text"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php 
        _e( 'Client Secret', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <input type="text" name="adfoin_googlesheets_client_secret"
                               value="<?php 
        echo  esc_attr( $client_secret ) ;
        ?>" placeholder="<?php 
        _e( 'Enter Client Secret', 'advanced-form-integration' );
        ?>"
                               class="regular-text"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php 
        _e( 'Re-Authorize', 'advanced-form-integration' );
        ?></th>
                    <td>
                    <?php 
        _e( 'Try re-authorizing if you face issues. Go to <a target="_blank" rel="noopener noreferrer" href="https://myaccount.google.com/permissions" ><b>Google App Permissions</b></a> and hit <b>REMOVE ACCESS</b> on any previous authorization of this app. Now click on the <b>Save & Authorize</b> button below and finish the authorization process again.', 'advanced-form-integration' );
        ?>
                    </td>
                </tr>
            </table>
            <?php 
        submit_button( __( 'Save & Authorize', 'advanced-form-integration' ) );
        ?>
        </form>

        <?php 
    }
    
    public function adfoin_save_googlesheets_keys()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_googlesheets_settings' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $client_id = ( isset( $_POST["adfoin_googlesheets_client_id"] ) ? sanitize_text_field( $_POST["adfoin_googlesheets_client_id"] ) : "" );
        $client_secret = ( isset( $_POST["adfoin_googlesheets_client_secret"] ) ? sanitize_text_field( $_POST["adfoin_googlesheets_client_secret"] ) : "" );
        $this->client_id = trim( $client_id );
        $this->client_secret = trim( $client_secret );
        $this->save_data();
        $this->authorize( 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.readonly' );
        //        advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=googlesheets" );
    }
    
    protected function authorize( $scope = '' )
    {
        $endpoint = add_query_arg( array(
            'response_type' => 'code',
            'access_type'   => 'offline',
            'client_id'     => $this->client_id,
            'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
            'scope'         => urlencode( $scope ),
        ), $this->authorization_endpoint );
        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit;
        }
    }
    
    protected function request_token( $authorization_code )
    {
        $args = array(
            'headers' => array(),
            'body'    => array(
            'code'          => $authorization_code,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri'  => $this->get_redirect_uri(),
            'grant_type'    => 'authorization_code',
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ),
        );
        $response = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        if ( isset( $response_body['access_token'] ) ) {
            $this->access_token = $response_body['access_token'];
        }
        if ( isset( $response_body['refresh_token'] ) ) {
            $this->refresh_token = $response_body['refresh_token'];
        }
        $this->save_data();
        return $response;
    }
    
    public function action_fields()
    {
        ?>
        <script type="text/template" id="googlesheets-action-template">
            <table class="form-table">
                <tr valign="top" v-if="action.task == 'add_row'">
                    <th scope="row">
                        <?php 
        esc_attr_e( 'Map Fields', 'advanced-form-integration' );
        ?>
                    </th>
                    <td scope="row">

                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'add_row'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php 
        esc_attr_e( 'Spreadsheet', 'advanced-form-integration' );
        ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[spreadsheetId]" v-model="fielddata.spreadsheetId" @change="getWorksheets" required="required">
                            <option value=""> <?php 
        _e( 'Select Spreadsheet...', 'advanced-form-integration' );
        ?> </option>
                            <option v-for="(item, index) in fielddata.spreadsheetList" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'add_row'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php 
        esc_attr_e( 'Worksheet', 'advanced-form-integration' );
        ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[worksheetId]" v-model="fielddata.worksheetId" @change="getHeaders" required="required">
                            <option value=""> <?php 
        _e( 'Select Worksheet...', 'advanced-form-integration' );
        ?> </option>
                            <option v-for="(item, index) in fielddata.worksheetList" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': worksheetLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
                <input type="hidden" name="fieldData[worksheetName]" :value="fielddata.worksheetName" />
                <input type="hidden" name="fieldData[worksheetList]" :value="JSON.stringify( fielddata.worksheetList )" />
            </table>
        </script>
        <?php 
    }
    
    protected function save_data()
    {
        $data = (array) maybe_unserialize( get_option( 'adfoin_googlesheets_keys' ) );
        $option = array_merge( $data, array(
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token'  => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'sheet_lists'   => $this->sheet_lists,
        ) );
        update_option( 'adfoin_googlesheets_keys', maybe_serialize( $option ) );
    }
    
    protected function reset_data()
    {
        $this->client_id = '';
        $this->client_secret = '';
        $this->google_access_code = '';
        $this->access_token = '';
        $this->refresh_token = '';
        $this->sheet_lists = array();
        $this->save_data();
    }
    
    protected function get_redirect_uri()
    {
        return site_url( '/wp-json/advancedformintegration/googlesheets' );
    }
    
    public function get_spreadsheet_list()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $endpoint = "https://www.googleapis.com/drive/v3/files?q=mimeType%20%3D%20'application%2Fvnd.google-apps.spreadsheet'&pageSize=1000&access_token=" . $this->access_token;
        $request = array(
            'method'  => 'GET',
            'headers' => array(),
        );
        $response = $this->remote_request( $endpoint, $request );
        $response_body = wp_remote_retrieve_body( $response );
        if ( empty($response_body) ) {
            return false;
        }
        $body = json_decode( $response_body, true );
        $spreadsheet_list = $body['files'];
        $spreadsheets_id_and_title = wp_list_pluck( $spreadsheet_list, 'name', 'id' );
        wp_send_json_success( $spreadsheets_id_and_title );
    }
    
    public function get_worksheets()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $spreadsheet_id = ( isset( $_POST['spreadsheetId'] ) ? $_POST['spreadsheetId'] : "" );
        if ( !$spreadsheet_id ) {
            return;
        }
        $endpoint = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/?access_token={$this->access_token}";
        $request = array(
            'method'  => 'GET',
            'headers' => array(),
        );
        $response = $this->remote_request( $endpoint, $request );
        $body = json_decode( $response['body'], true );
        $sheets = array();
        foreach ( $body['sheets'] as $value ) {
            $sheets[$value['properties']['sheetId']] = $value['properties']['title'];
        }
        
        if ( empty($sheets) ) {
            return wp_send_json_error();
        } else {
            return wp_send_json_success( $sheets );
        }
    
    }
    
    public function get_headers()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $spreadsheet_id = ( isset( $_REQUEST['spreadsheetId'] ) ? $_REQUEST['spreadsheetId'] : "" );
        $worksheet_name = ( isset( $_REQUEST['worksheetName'] ) ? $_REQUEST['worksheetName'] : "" );
        if ( !$spreadsheet_id || !$worksheet_name ) {
            return;
        }
        $endpoint = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$worksheet_name}!A1:ZZ1?access_token={$this->access_token}";
        $request = array(
            'method'  => 'GET',
            'headers' => array(),
        );
        $response = $this->remote_request( $endpoint, $request );
        if ( $response['response']['code'] != 200 ) {
            return false;
        }
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        # Err handaling
        if ( !isset( $body['values'] ) ) {
            wp_send_json_error();
        }
        $combined = array();
        $key = "A";
        if ( is_array( $body['values'][0] ) ) {
            foreach ( $body['values'][0] as $value ) {
                $combined[$key] = $value;
                $key++;
                if ( $key == "ZZ" ) {
                    break;
                }
            }
        }
        wp_send_json_success( $combined );
    }
    
    protected function remote_request( $url, $request = array(), $record = array() )
    {
        if ( !$this->check_token_expiry( $this->access_token ) ) {
            $this->refresh_token();
        }
        $request['headers'] = array(
            'Content-Type'  => 'application/json',
            'Authorization' => sprintf( 'Bearer %s', $this->access_token ),
        );
        $request['timeout'] = 30;
        $response = wp_remote_request( esc_url_raw( $url ), $request );
        
        if ( 401 === wp_remote_retrieve_response_code( $response ) ) {
            $this->refresh_token();
            $response = $this->remote_request( $url, $request, $record );
        }
        
        if ( $record ) {
            adfoin_add_to_log(
                $response,
                $url,
                $request,
                $record
            );
        }
        return $response;
    }
    
    public function check_token_expiry( $token = '' )
    {
        if ( empty($token) ) {
            return false;
        }
        $return = wp_remote_get( 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token );
        if ( is_wp_error( $return ) ) {
            return false;
        }
        $body = json_decode( $return['body'], true );
        if ( $return['response']['code'] == 200 ) {
            return true;
        }
        return false;
    }
    
    protected function refresh_token()
    {
        $args = array(
            'headers' => array(),
            'body'    => array(
            'refresh_token' => $this->refresh_token,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    => 'refresh_token',
        ),
        );
        $response = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        if ( isset( $response_body['access_token'] ) ) {
            $this->access_token = $response_body['access_token'];
        }
        if ( isset( $response_body['refresh_token'] ) ) {
            $this->refresh_token = $response_body['refresh_token'];
        }
        $this->save_data();
        return $response;
    }
    
    public function append_new_row(
        $record,
        $spreadsheet_id = '',
        $worksheet_name = '',
        $data_array = array()
    )
    {
        if ( empty($worksheet_name) || empty($data_array) ) {
            return "worksheet_name or data_array is empty";
        }
        $final = array();
        foreach ( $data_array as $key => $val ) {
            
            if ( $val ) {
                $final[] = $val;
            } else {
                $final[] = "";
            }
        
        }
        $last_key = key( array_slice(
            $data_array,
            -1,
            1,
            true
        ) );
        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheet_id}/values/{$worksheet_name}!A:{$last_key}:append?valueInputOption=USER_ENTERED";
        $args = array(
            'method' => 'POST',
            'body'   => '{"range":"' . $worksheet_name . '!A:' . $last_key . '","majorDimension":"ROWS","values":[' . json_encode( $final ) . ']}',
        );
        $return = $this->remote_request( $url, $args, $record );
    }

}
$googlesheets = ADFOIN_GoogleSheets::get_instance();
/*
 * Saves connection mapping
 */
function adfoin_googlesheets_save_integration()
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

add_action(
    'adfoin_googlesheets_job_queue',
    'adfoin_googlesheets_job_queue',
    10,
    1
);
function adfoin_googlesheets_job_queue( $data )
{
    adfoin_googlesheets_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Google Sheets API
 */
function adfoin_googlesheets_send_data( $record, $submitted_data )
{
    $record_data = json_decode( $record["data"], true );
    if ( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if ( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if ( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $submitted_data ) ) {
                return;
            }
        }
    }
    $all_data = apply_filters( 'afi_googlesheets_before_process', array(
        'submitted_data' => $submitted_data,
        'record_data'    => $record_data,
    ) );
    $posted_data = $all_data['submitted_data'];
    $data = $all_data['record_data']["field_data"];
    $spreadsheet_id = $data["spreadsheetId"];
    $worksheet_name = $data["worksheetName"];
    $task = $record["task"];
    
    if ( $task == "add_row" ) {
        unset( $data["spreadsheetId"] );
        unset( $data["spreadsheetList"] );
        unset( $data["worksheetId"] );
        unset( $data["worksheetList"] );
        unset( $data["worksheetName"] );
        $holder = array();
        $googlesheets = ADFOIN_GoogleSheets::get_instance();
        
        if ( empty($data) ) {
            $key = "A";
            
            if ( is_array( $posted_data ) ) {
                $posted_data = array_filter( $posted_data );
                foreach ( $posted_data as $value ) {
                    $holder[$key] = $value;
                    $key++;
                    if ( $key == "ZZ" ) {
                        break;
                    }
                }
            }
        
        } else {
            foreach ( $data as $key => $value ) {
                $holder[$key] = adfoin_get_parsed_values( $data[$key], $posted_data );
            }
        }
        
        $googlesheets->append_new_row(
            $record,
            $spreadsheet_id,
            $worksheet_name,
            $holder
        );
    }
    
    return;
}
