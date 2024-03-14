<?php

class ADFOIN_Bigin extends Advanced_Form_Integration_OAuth2
{
    const  authorization_endpoint = 'https://accounts.zoho.com/oauth/v2/auth' ;
    const  token_endpoint = 'https://accounts.zoho.com/oauth/v2/token' ;
    const  refresh_token_endpoint = 'https://accounts.zoho.com/oauth/v2/token' ;
    public  $data_center ;
    private static  $instance ;
    public static function get_instance()
    {
        if ( empty(self::$instance) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct()
    {
        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;
        $option = (array) maybe_unserialize( get_option( 'adfoin_bigin_keys' ) );
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
        add_filter(
            'adfoin_action_providers',
            array( $this, 'adfoin_bigin_actions' ),
            10,
            1
        );
        add_filter(
            'adfoin_settings_tabs',
            array( $this, 'adfoin_bigin_settings_tab' ),
            10,
            1
        );
        add_action(
            'adfoin_settings_view',
            array( $this, 'adfoin_bigin_settings_view' ),
            10,
            1
        );
        add_action(
            'admin_post_adfoin_save_bigin_keys',
            array( $this, 'adfoin_save_bigin_keys' ),
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
            'wp_ajax_adfoin_get_bigin_users',
            array( $this, 'get_users' ),
            10,
            0
        );
        add_action(
            'wp_ajax_adfoin_get_bigin_modules',
            array( $this, 'get_modules' ),
            10,
            0
        );
        add_action( 'rest_api_init', array( $this, 'create_webhook_route' ) );
        add_action( 'wp_ajax_adfoin_get_bigin_module_fields', array( $this, 'get_fields' ) );
    }
    
    public function create_webhook_route()
    {
        register_rest_route( 'advancedformintegration', '/bigin', array(
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
                'action'  => 'adfoin_bigin_auth_redirect',
                'code'    => $code,
            ], admin_url( 'admin.php?page=advanced-form-integration' ) );
            wp_safe_redirect( $redirect_to );
            exit;
        }
    
    }
    
    public function adfoin_bigin_actions( $actions )
    {
        $actions['bigin'] = array(
            'title' => __( 'Bigin', 'advanced-form-integration' ),
            'tasks' => array(
            'subscribe' => __( 'Add new record', 'advanced-form-integration' ),
        ),
        );
        return $actions;
    }
    
    public function adfoin_bigin_settings_tab( $providers )
    {
        $providers['bigin'] = __( 'Bigin', 'advanced-form-integration' );
        return $providers;
    }
    
    public function adfoin_bigin_settings_view( $current_tab )
    {
        if ( $current_tab != 'bigin' ) {
            return;
        }
        $option = (array) maybe_unserialize( get_option( 'adfoin_bigin_keys' ) );
        $nonce = wp_create_nonce( 'adfoin_bigin_settings' );
        $data_center = ( isset( $option['data_center'] ) ? $option['data_center'] : 'com' );
        $client_id = ( isset( $option['client_id'] ) ? $option['client_id'] : '' );
        $client_secret = ( isset( $option['client_secret'] ) ? $option['client_secret'] : '' );
        $redirect_uri = $this->get_redirect_uri();
        ?>

        <form name='bigin_save_form' action='<?php 
        echo  esc_url( admin_url( 'admin-post.php' ) ) ;
        ?>'
              method='post' class='container'>

            <input type='hidden' name='action' value='adfoin_save_bigin_keys'>
            <input type='hidden' name='_nonce' value='<?php 
        echo  $nonce ;
        ?>'/>

            <table class='form-table'>
            <tr valign='top'>
                    <th scope='row'> <?php 
        _e( 'Instructions', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <p>
                            1. Go to <a target='_blank' rel='noopener noreferrer' href='https://api-console.zoho.com/'>Zoho API Console</a>.</br>
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
                    <th scope='row'> <?php 
        _e( 'Redirect URI', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <code><?php 
        echo  esc_url( $redirect_uri ) ;
        ?></code>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php 
        _e( 'Data Center', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <select name="zoho_data_center" id="zoho-data-center">
                            <option value="com" <?php 
        selected( $data_center, 'com' );
        ?>>zoho.com</option>
                            <option value="eu" <?php 
        selected( $data_center, 'eu' );
        ?>>zoho.eu</option>
                            <option value="in" <?php 
        selected( $data_center, 'in' );
        ?>>zoho.in</option>
                            <option value="com.cn" <?php 
        selected( $data_center, 'com.cn' );
        ?>>zoho.com.cn</option>
                            <option value="com.au" <?php 
        selected( $data_center, 'com.au' );
        ?>>zoho.com.au</option>
                            <option value="jp" <?php 
        selected( $data_center, 'jp' );
        ?>>zoho.jp</option>
                            <option value="com.cn" <?php 
        selected( $data_center, 'com.cn' );
        ?>>zoho.com.cn</option>
                        </select>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php 
        _e( 'Client ID', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <input type='text' name='adfoin_bigin_client_id'
                               value='<?php 
        echo  esc_attr( $client_id ) ;
        ?>' placeholder='<?php 
        _e( 'Enter Client ID', 'advanced-form-integration' );
        ?>'
                               class='regular-text'/>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php 
        _e( 'Client Secret', 'advanced-form-integration' );
        ?></th>
                    <td>
                        <input type='text' name='adfoin_bigin_client_secret'
                               value='<?php 
        echo  esc_attr( $client_secret ) ;
        ?>' placeholder='<?php 
        _e( 'Enter Client Secret', 'advanced-form-integration' );
        ?>'
                               class='regular-text'/>
                    </td>
                </tr>
                <tr valign='top'>
                    <th scope='row'> <?php 
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
            </table>
            <?php 
        submit_button( __( 'Authorize', 'advanced-form-integration' ) );
        ?>
        </form>

        <?php 
    }
    
    public function adfoin_save_bigin_keys()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_bigin_settings' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $data_center = ( isset( $_POST['zoho_data_center'] ) ? sanitize_text_field( $_POST['zoho_data_center'] ) : 'com' );
        $client_id = ( isset( $_POST['adfoin_bigin_client_id'] ) ? sanitize_text_field( $_POST['adfoin_bigin_client_id'] ) : '' );
        $client_secret = ( isset( $_POST['adfoin_bigin_client_secret'] ) ? sanitize_text_field( $_POST['adfoin_bigin_client_secret'] ) : '' );
        
        if ( !$client_id || !$client_secret ) {
            $this->reset_data();
        } else {
            $this->data_center = trim( $data_center );
            $this->client_id = trim( $client_id );
            $this->client_secret = trim( $client_secret );
            $this->save_data();
            $this->authorize( 'ZohoBigin.modules.ALL,ZohoBigin.org.ALL,ZohoBigin.settings.ALL,ZohoBigin.users.ALL,ZohoBigin.coql.READ' );
        }
        
        advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings&tab=bigin' );
    }
    
    protected function authorize( $scope = '' )
    {
        $data = array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'access_type'   => 'offline',
            'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
        );
        if ( $scope ) {
            $data['scope'] = $scope;
        }
        $auth_endpoint = $this->authorization_endpoint;
        if ( $this->data_center && $this->data_center !== 'com' ) {
            $auth_endpoint = str_replace( 'com', $this->data_center, $this->authorization_endpoint );
        }
        $endpoint = add_query_arg( $data, $auth_endpoint );
        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit;
        }
    }
    
    protected function request_token( $authorization_code )
    {
        $tok_endpoint = $this->token_endpoint;
        if ( $this->data_center && $this->data_center !== 'com' ) {
            $tok_endpoint = str_replace( 'com', $this->data_center, $this->token_endpoint );
        }
        $endpoint = add_query_arg( array(
            'code'         => $authorization_code,
            'redirect_uri' => urlencode( $this->get_redirect_uri() ),
            'grant_type'   => 'authorization_code',
        ), $tok_endpoint );
        $request = [
            'headers' => [
            'Authorization' => $this->get_http_authorization_header( 'basic' ),
        ],
        ];
        $response = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        
        if ( 401 == $response_code ) {
            // Unauthorized
            $this->access_token = null;
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
    
    protected function refresh_token()
    {
        $ref_endpoint = $this->refresh_token_endpoint;
        if ( $this->data_center && $this->data_center !== 'com' ) {
            $ref_endpoint = str_replace( 'com', $this->data_center, $this->refresh_token_endpoint );
        }
        $endpoint = add_query_arg( array(
            'refresh_token' => $this->refresh_token,
            'grant_type'    => 'refresh_token',
        ), $ref_endpoint );
        $request = [
            'headers' => array(
            'Authorization' => $this->get_http_authorization_header( 'basic' ),
        ),
        ];
        $response = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        
        if ( 401 == $response_code ) {
            // Unauthorized
            $this->access_token = null;
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
    
    public function action_fields()
    {
        ?>
        <script type='text/template' id='bigin-action-template'>
            <table class='form-table'>
                <tr valign='top' v-if="action.task == 'subscribe'">
                    <th scope='row'>
                        <?php 
        esc_attr_e( 'Map Fields', 'advanced-form-integration' );
        ?>
                    </th>
                    <td scope='row'>

                    </td>
                </tr>

                <tr valign='top' class='alternate' v-if="action.task == 'subscribe'">
                    <td scope='row-title'>
                        <label for='tablecell'>
                            <?php 
        esc_attr_e( 'Owner', 'advanced-form-integration' );
        ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[userId]" v-model="fielddata.userId">
                            <option value=''> <?php 
        _e( 'Select Owner...', 'advanced-form-integration' );
        ?> </option>
                            <option v-for='(item, index) in fielddata.users' :value='index' > {{item}}  </option>
                        </select>
                        <div class='spinner' v-bind:class="{'is-active': userLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign='top' class='alternate' v-if="action.task == 'subscribe'">
                    <td scope='row-title'>
                        <label for='tablecell'>
                            <?php 
        esc_attr_e( 'Module', 'advanced-form-integration' );
        ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[moduleId]" v-model="fielddata.moduleId" @change=getFields>
                            <option value=''> <?php 
        _e( 'Select Module...', 'advanced-form-integration' );
        ?> </option>
                            <option v-for='(item, index) in fielddata.modules' :value='index' > {{item}}  </option>
                        </select>
                        <div class='spinner' v-bind:class="{'is-active': moduleLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>


                <editable-field v-for='field in fields' v-bind:key='field.value' v-bind:field='field' v-bind:trigger='trigger' v-bind:action='action' v-bind:fielddata='fielddata'></editable-field>

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
    
    public function auth_redirect()
    {
        $auth = ( isset( $_GET['auth'] ) ? trim( $_GET['auth'] ) : '' );
        $code = ( isset( $_GET['code'] ) ? trim( $_GET['code'] ) : '' );
        $action = ( isset( $_GET['action'] ) ? trim( $_GET['action'] ) : '' );
        
        if ( 'adfoin_bigin_auth_redirect' == $action ) {
            $code = ( isset( $_GET['code'] ) ? $_GET['code'] : '' );
            if ( $code ) {
                $this->request_token( $code );
            }
            
            if ( !empty($this->access_token) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }
            
            wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=bigin' ) );
            exit;
        }
    
    }
    
    protected function save_data()
    {
        $data = (array) maybe_unserialize( get_option( 'adfoin_bigin_keys' ) );
        $option = array_merge( $data, array(
            'data_center'   => $this->data_center,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'access_token'  => $this->access_token,
            'refresh_token' => $this->refresh_token,
        ) );
        update_option( 'adfoin_bigin_keys', maybe_serialize( $option ) );
    }
    
    protected function reset_data()
    {
        $this->data_center = 'com';
        $this->client_id = '';
        $this->client_secret = '';
        $this->access_token = '';
        $this->refresh_token = '';
        $this->save_data();
    }
    
    protected function get_redirect_uri()
    {
        return site_url( '/wp-json/advancedformintegration/bigin' );
    }
    
    public function bigin_request(
        $endpoint,
        $method = 'GET',
        $data = array(),
        $record = array()
    )
    {
        $base_url = 'https://www.zohoapis.com/bigin/v2/';
        if ( $this->data_center && $this->data_center !== 'com' ) {
            $base_url = str_replace( 'com', $this->data_center, $base_url );
        }
        $url = $base_url . $endpoint;
        $args = array(
            'method'  => $method,
            'headers' => array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        );
        if ( 'POST' == $method || 'PUT' == $method ) {
            if ( $data ) {
                $args['body'] = json_encode( $data );
            }
        }
        $response = $this->remote_request( $url, $args, $record );
        return $response;
    }
    
    protected function remote_request( $url, $request = array(), $record = array() )
    {
        static  $refreshed = false ;
        $request = wp_parse_args( $request, [] );
        $request['headers'] = array_merge( $request['headers'], array(
            'Authorization' => $this->get_http_authorization_header( 'bearer' ),
        ) );
        $response = wp_remote_request( esc_url_raw( $url ), $request );
        
        if ( 401 === wp_remote_retrieve_response_code( $response ) and !$refreshed ) {
            $this->refresh_token();
            $refreshed = true;
            $response = $this->remote_request( $url, $request );
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
    
    /*
     * Get Owners
     */
    public function get_users()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $response = $this->bigin_request( 'users?type=AdminUsers' );
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty($response_body) ) {
            wp_send_json_error();
        }
        
        if ( !empty($response_body['users']) && is_array( $response_body['users'] ) ) {
            $users = array();
            foreach ( $response_body['users'] as $value ) {
                $users[$value['id']] = $value['full_name'];
            }
            wp_send_json_success( $users );
        } else {
            wp_send_json_error();
        }
    
    }
    
    /*
     * Get Modules
     */
    public function get_modules()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $response = $this->bigin_request( 'settings/modules' );
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty($response_body) ) {
            wp_send_json_error();
        }
        
        if ( !empty($response_body['modules']) && is_array( $response_body['modules'] ) ) {
            $skip_list = array( 'Calls' );
            $modules = array();
            foreach ( $response_body['modules'] as $single ) {
                if ( in_array( $single['api_name'], $skip_list ) ) {
                    continue;
                }
                if ( isset( $single['editable'] ) && true == $single['editable'] && 'Associated_Products' != $single['api_name'] ) {
                    $modules[$single['api_name']] = $single['plural_label'];
                }
            }
            wp_send_json_success( $modules );
        } else {
            wp_send_json_error();
        }
    
    }
    
    /*
     * Get Module Fields
     */
    function get_fields()
    {
        // Security Check
        if ( !wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }
        $final_data = array();
        $module = ( isset( $_POST['module'] ) ? $_POST['module'] : '' );
        
        if ( $module ) {
            $response = $this->bigin_request( "settings/fields?module={$module}&type=all" );
            $body = json_decode( wp_remote_retrieve_body( $response ), true );
            
            if ( isset( $body['fields'] ) && is_array( $body['fields'] ) ) {
                $suppression_list = array(
                    'Created_By',
                    'Modified_By',
                    'Created_Time',
                    'Modified_Time',
                    'Layout',
                    'Tag',
                    'Recurring_Activity',
                    'BEST_TIME',
                    'What_Id',
                    'Record_Image'
                );
                foreach ( $body['fields'] as $field ) {
                    $helptext = '';
                    $data_type = $field['data_type'];
                    $api_name = $field['api_name'];
                    $display_label = $field['display_label'];
                    if ( isset( $field['field_read_only'] ) && $field['field_read_only'] == true ) {
                        continue;
                    }
                    if ( in_array( $api_name, $suppression_list ) ) {
                        continue;
                    }
                    if ( $field['custom_field'] == true ) {
                        continue;
                    }
                    if ( 'Contact_Name' == $api_name || 'Who_Id' == $api_name ) {
                        $display_label = 'Contact Email';
                    }
                    if ( 'bigint' == $data_type && 'Participants' == $api_name ) {
                        $helptext = 'Example: lead--john@example.com,contact--david@example.com';
                    }
                    
                    if ( 'multiselectpicklist' == $data_type && 'Tax' == $api_name ) {
                        $items = array();
                        if ( isset( $field['pick_list_values'] ) && is_array( $field['pick_list_values'] ) ) {
                            foreach ( $field['pick_list_values'] as $pick ) {
                                $items[] = $pick['display_value'] . ': ' . $pick['id'];
                            }
                        }
                        $helptext = implode( ', ', $items );
                    }
                    
                    
                    if ( 'picklist' == $data_type && is_array( $field['pick_list_values'] ) ) {
                        $picklist = wp_list_pluck( $field['pick_list_values'], 'actual_value' );
                        $helptext = implode( ' | ', $picklist );
                    }
                    
                    array_push( $final_data, array(
                        'key'         => $data_type . '__' . $api_name,
                        'value'       => $display_label,
                        'description' => $helptext,
                    ) );
                }
                
                if ( 'Tasks' == $module || 'Events' == $module ) {
                    array_push( $final_data, array(
                        'key'         => 'text__$se_module',
                        'value'       => 'Module Name',
                        'description' => 'Accounts | Deals',
                    ) );
                    array_push( $final_data, array(
                        'key'         => 'text__What_Id',
                        'value'       => 'Module Record',
                        'description' => 'Account Name | Deal Name',
                    ) );
                }
            
            }
        
        }
        
        wp_send_json_success( $final_data );
    }
    
    public function search_record(
        $module,
        $search_key,
        $search_value,
        $record
    )
    {
        sleep( 5 );
        $result = array(
            'id'   => '',
            'data' => array(),
        );
        $body = array(
            'select_query' => "select {$search_key}, id, Tag from {$module} where {$search_key} = '{$search_value}'",
        );
        $response = $this->bigin_request(
            'coql',
            'POST',
            $body,
            $record
        );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        
        if ( isset( $body['data'], $body['data'][0], $body['data'][0]['id'] ) ) {
            $result['id'] = $body['data'][0]['id'];
            $result['data'] = $body['data'][0];
        }
        
        return $result;
    }
    
    public function is_duplicate( $module, $holder, $record )
    {
        
        if ( 'Contacts' == $module ) {
            $contact_id = $this->search_record(
                'Contacts',
                'Email',
                $holder['Email'],
                $record
            )['id'];
            if ( $contact_id ) {
                return $contact_id;
            }
        }
        
        
        if ( 'Accounts' == $module ) {
            $account_id = $this->search_record(
                'Accounts',
                'Account_Name',
                $holder['Account_Name'],
                $record
            )['id'];
            if ( $account_id ) {
                return $account_id;
            }
        }
        
        // if( 'Leads' == $module ) {
        //     $lead_id = $this->search_record( 'Leads', 'Email', $holder['Email'], $record )['id];
        //     if( $lead_id ) {
        //         return $lead_id;
        //     }
        // }
        // if( 'Leads' == $module ) {
        //     $lead_id = $this->search_record( 'Leads', 'Email', $holder['Email'], $record )['id];
        //     if( $lead_id ) {
        //         return $lead_id;
        //     }
        // }
        return false;
    }

}
$bigin = ADFOIN_Bigin::get_instance();
add_action(
    'adfoin_bigin_job_queue',
    'adfoin_bigin_job_queue',
    10,
    1
);
function adfoin_bigin_job_queue( $data )
{
    adfoin_bigin_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Bigin API
 */
function adfoin_bigin_send_data( $record, $posted_data )
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
    $owner = ( isset( $data['userId'] ) ? $data['userId'] : '' );
    $module = ( isset( $data['moduleId'] ) ? $data['moduleId'] : '' );
    $task = $record['task'];
    unset( $data['userId'] );
    unset( $data['moduleId'] );
    
    if ( $task == 'subscribe' ) {
        $bigin = ADFOIN_Bigin::get_instance();
        $holder = array();
        $account_id = '';
        $contact_id = '';
        $campaign_id = '';
        $task_module = '';
        $account_lookups = array( 'Parent_Account', 'Account_Name' );
        $contact_lookups = array( 'Contact_Name', 'Who_Id', 'Related_To' );
        $campaign_lookups = array( 'Parent_Campaign', 'Campaign_Source' );
        foreach ( $data as $key => $value ) {
            list( $data_type, $original_key ) = explode( '__', $key );
            $value = adfoin_get_parsed_values( $value, $posted_data );
            
            if ( 'datetime' == $data_type && $value ) {
                // if( 'Start_DateTime' == $original_key || 'End_DateTime' == $original_key ) {
                $timezone = wp_timezone();
                $date = date_create( $value, $timezone );
                if ( $date ) {
                    $value = date_format( $date, 'c' );
                }
                // }
            }
            
            if ( 'multiselectpicklist' == $data_type && $value ) {
                
                if ( 'Tax' == $original_key ) {
                    $formatted_tax_ids = array();
                    $tax_ids = explode( ',', $value );
                    foreach ( $tax_ids as $tax_id ) {
                        array_push( $formatted_tax_ids, array(
                            'id' => $tax_id,
                        ) );
                    }
                    $value = $formatted_tax_ids;
                }
            
            }
            if ( 'bigint' == $data_type && $value ) {
                
                if ( 'Participants' == $original_key ) {
                    $participants = array();
                    $raw_participants = explode( ',', $value );
                    foreach ( $raw_participants as $single ) {
                        list( $type, $email ) = explode( '--', $single );
                        
                        if ( 'contact' == $type ) {
                            $participant_id = $bigin->search_record(
                                'Contacts',
                                'Email',
                                $email,
                                $record
                            )['id'];
                            if ( $participant_id ) {
                                array_push( $participants, array(
                                    'type'        => 'contact',
                                    'participant' => $participant_id,
                                ) );
                            }
                        }
                    
                    }
                    $value = $participants;
                }
            
            }
            
            if ( 'lookup' == $data_type && $value ) {
                
                if ( in_array( $original_key, $account_lookups ) ) {
                    $account_id = $bigin->search_record(
                        'Accounts',
                        'Account_Name',
                        $value,
                        $record
                    )['id'];
                    if ( $account_id ) {
                        $value = $account_id;
                    }
                }
                
                
                if ( in_array( $original_key, $contact_lookups ) ) {
                    $contact_id = $bigin->search_record(
                        'Contacts',
                        'Email',
                        $value,
                        $record
                    )['id'];
                    if ( $contact_id ) {
                        $value = $contact_id;
                    }
                }
                
                
                if ( in_array( $original_key, $campaign_lookups ) ) {
                    $campaign_id = $bigin->search_record(
                        'Campaigns',
                        'Campaign_Name',
                        $value,
                        $record
                    )['id'];
                    if ( $campaign_id ) {
                        $value = $campaign_id;
                    }
                }
                
                
                if ( 'Product_Name' == $original_key ) {
                    $product_id = $bigin->search_record(
                        'Products',
                        'Product_Name',
                        $value,
                        $record
                    )['id'];
                    if ( $product_id ) {
                        $value = $product_id;
                    }
                }
                
                
                if ( 'Deal_Name' == $original_key ) {
                    $deal_id = $bigin->search_record(
                        'Deals',
                        'Deal_Name',
                        $value,
                        $record
                    )['id'];
                    if ( $deal_id ) {
                        $value = $deal_id;
                    }
                }
                
                
                if ( 'Reporting_To' == $original_key && $account_id ) {
                    $contacts_response = $bigin->bigin_request( 'Accounts/' . $account_id . '/Contacts?fields=id,First_Name,Last_Name' );
                    $contacts_body = json_decode( wp_remote_retrieve_body( $contacts_response ), true );
                    if ( isset( $contacts_body['data'] ) && is_array( $contacts_body['data'] ) ) {
                        foreach ( $contacts_body['data'] as $contact ) {
                            $contact_name = $contact['First_Name'] . ' ' . $contact['Last_Name'];
                            $contact_id = ( $contact_name == $value ? $contact['id'] : '' );
                            if ( $contact_id ) {
                                $value = $contact_id;
                            }
                        }
                    }
                }
            
            }
            
            if ( 'boolean' == $data_type ) {
                
                if ( strtolower( $value ) == 'true' ) {
                    $value = true;
                } else {
                    $value = false;
                }
            
            }
            if ( '$se_module' == $original_key ) {
                $task_module = $value;
            }
            if ( 'What_Id' == $original_key ) {
                
                if ( 'Accounts' == $task_module ) {
                    $account_id = $bigin->search_record(
                        'Accounts',
                        'Account_Name',
                        $value,
                        $record
                    )['id'];
                    if ( $account_id ) {
                        $value = $account_id;
                    }
                }
            
            }
            $holder[$original_key] = $value;
        }
        if ( $owner ) {
            $holder['Owner'] = "{$owner}";
        }
        
        if ( $module && $holder ) {
            $request_data = array(
                'data' => array( array_filter( $holder ) ),
            );
            $return = $bigin->bigin_request(
                $module . '/upsert',
                'POST',
                $request_data,
                $record
            );
            // $id           = $bigin->is_duplicate( $module, $holder, $record );
            // if( $id ) {
            //     $return = $bigin->bigin_request( $module . '/' . $id, 'PUT', $request_data, $record );
            // } else {
            //     $return = $bigin->bigin_request( $module, 'POST', $request_data, $record );
            // }
        }
    
    }
    
    return;
}
