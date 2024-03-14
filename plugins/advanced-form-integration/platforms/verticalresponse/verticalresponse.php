<?php

class VerticalResponse extends Advanced_Form_Integration_OAuth2 {

    // const service_name           = 'verticalresponse';
    const authorization_endpoint = 'https://vrapi.verticalresponse.com/api/v1/oauth/authorize';
    const token_endpoint         = 'https://vrapi.verticalresponse.com/api/v1/oauth/access_token';
    public $client_id     = 'etufm7r8ncfkj9d4bdxvwkjw';
    public $client_secret = 'ct4ghxeJ7EKwtatgDuyzwx9P';

    private static $instance;

    public static function get_instance() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    protected function __construct() {

        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;

        $option = (array) maybe_unserialize( get_option( 'adfoin_verticalresponse_keys' ) );

        if ( isset( $option['client_id'] ) ) {
            $this->client_id = $option['client_id'];
        }

        if ( isset( $option['client_secret'] ) ) {
            $this->client_secret = $option['client_secret'];
        }

        if ( isset( $option['access_token'] ) ) {
            $this->access_token = $option['access_token'];
        }

        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'adfoin_action_providers', array( $this, 'actions' ), 10, 1 );
        add_filter( 'adfoin_settings_tabs', array( $this, 'settings_tab' ), 10, 1 );
        add_action( 'adfoin_settings_view', array( $this, 'settings_view' ), 10, 1 );
        add_action( 'admin_post_adfoin_save_verticalresponse_keys', array( $this, 'save_keys' ), 10, 0 );
        add_action( 'adfoin_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_adfoin_get_verticalresponse_list', array( $this, 'get_verticalresponse_list' ), 10, 0 );
        add_action( 'rest_api_init', array( $this, 'create_webhook_route' ) );
    }

    public function is_active() {
        return !empty( $this->access_token );
    }

    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/verticalresponse',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'get_webhook_data' ),
                'permission_callback' => '__return_true'
            )
        );
    }

    public function get_webhook_data( $request ) {
        $params = $request->get_params();
        $code   = isset( $params['code'] ) ? trim( $params['code'] ) : '';

        if ( $code ) {

            $redirect_to = add_query_arg(
                [
                    'service' => 'authorize',
                    'action'  => 'adfoin_verticalresponse_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=advanced-form-integration')
            );

            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function actions( $actions ) {

        $actions['verticalresponse'] = array(
            'title' => __( 'Vertical Response', 'advanced-form-integration' ),
            'tasks' => array(
                'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' )
            )
        );

        return $actions;
    }

    public function settings_tab( $providers ) {
        $providers['verticalresponse'] = __( 'Vertical Response', 'advanced-form-integration' );

        return $providers;
    }

    public function settings_view( $current_tab ) {
        if( $current_tab != 'verticalresponse' ) {
            return;
        }

        $auth_req_data = array(
            'client_id'    => $this->client_id,
            'redirect_uri' => $this->get_redirect_uri()
        );

        $auth_url = add_query_arg( $auth_req_data, $this->authorization_endpoint );
        ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Status', 'advanced-form-integration' ); ?></th>
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
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Connect your account', 'advanced-form-integration' ); ?></th>
                    <td>
                        <a href="<?php echo $auth_url; ?>" class="button button-primary"><?php _e( 'Authorize', 'advanced-form-integration' ); ?></a>
                    </td>
                </tr>
            </table>
        <?php
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="verticalresponse-action-template">
            <table class="form-table">
                <tr valign="top" v-if="action.task == 'subscribe'">
                    <th scope="row">
                        <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                    </th>
                    <td scope="row">

                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Constant Contact List', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[listId]" v-model="fielddata.listId">
                            <option value=""> <?php _e( 'Select List...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            </table>
        </script>


        <?php
    }

    public function auth_redirect() {

        $action = isset( $_GET['action'] ) ? trim( $_GET['action'] ) : '';

        if ( 'adfoin_verticalresponse_auth_redirect' != $action ) {
            return;
        }

        $code = isset( $_GET['code'] ) ? trim( $_GET['code'] ) : '';

        if ( $code ) {
            $this->request_token( $code );
        }

        wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=verticalresponse' ) );

        exit();
    }

    protected function authorize( $scope = '' ) {

        $data = array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
            'state'         => 'advancedformintegration',
            'nonce'         => 'advancedformintegration'
        );

        if( $scope ) {
            $data['scope'] = $scope;
        }

        $endpoint = add_query_arg( $data, $this->authorization_endpoint );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function request_token( $code ) {

        $endpoint = add_query_arg(
            array(
                'code'          => $code,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret
            ),
            $this->token_endpoint
        );

        $response      = wp_remote_get( $endpoint );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }
        }

        $this->save_data();

        return $response;
    }

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'adfoin_verticalresponse_keys' ) );

        $option = array_merge(
            $data,
            array(
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token
            )
        );

        update_option( 'adfoin_verticalresponse_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {

        $this->client_id     = '';
        $this->client_secret = '';
        $this->access_token  = '';

        $this->save_data();
    }

    protected function get_redirect_uri() {
        return site_url( '/wp-json/advancedformintegration/verticalresponse' );
    }

    public function create_contact( $properties, $record = array() ) {
        $contact_id = $this->contact_exists( $properties['email'] );

        if( $contact_id ) {
            unset( $properties['email'] );
            $response = $this->request( 'contacts/' . $contact_id, 'PUT', $properties, $record );
        } else {
            $response = $this->request( 'contacts', 'POST', $properties, $record );
        }

        return $response;
    }

    public function add_to_list( $list_id, $email, $record = array() ) {
        $response = $this->request(
            'lists/' . $list_id . '/contacts', 
            'POST', 
            array( 'email' => $email ), 
            $record
        );

        return $response;
    }

    public function get_verticalresponse_list() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $this->get_contact_lists();
    }

    public function get_contact_lists() {
        $response      = $this->request( 'lists' );
        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            return false;
        }

        $response_body = json_decode( $response_body, true );

        if ( isset( $response_body['items'] ) && !empty( $response_body['items'] ) ) {
            $lists = array();

            foreach( $response_body['items'] as $item ) {
                $lists[$item['attributes']['id']] = $item['attributes']['name'];
            }

            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }

    public function request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
        $base_url = 'https://vrapi.verticalresponse.com/api/v1/';
        $url      = $base_url . $endpoint;

        $args = array(
            'method'  => $method,
            'headers' => array(
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ),
        );

        if ( 'POST' == $method || 'PUT' == $method ) {
            $args['body'] = json_encode( $data );
        }

        $response = $this->remote_request($url, $args);

        if ( $record ) {
            adfoin_add_to_log($response, $url, $args, $record);
        }

        return $response;
    }

    public function contact_exists( $email ) {
        if( !$email ) {
            return false;
        }

        $return = $this->request( 'contacts?email=' . $email );
        $body = json_decode( wp_remote_retrieve_body( $return ), true );

        if( isset( $body['items'] ) && 
        is_array( $body['items'] ) && 
        count( $body['items'] ) > 0 ) {
            $contact_id = $body['items'][0]['attributes']['id'];

            return $contact_id;
        } else {
            return false;
        }
    }
}

$verticalresponse = VerticalResponse::get_instance();

add_action( 'adfoin_verticalresponse_job_queue', 'adfoin_verticalresponse_job_queue', 10, 1 );

function adfoin_verticalresponse_job_queue( $data ) {
    adfoin_verticalresponse_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Vertical Response API
 */
function adfoin_verticalresponse_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data['field_data'];
    $list_id = isset( $data['listId'] ) ? $data['listId'] : '';
    $task    = $record['task'];


    if( $task == 'subscribe' ) {
        unset( $data['listId'] );
        unset( $data['task'] );

        $properties = array();

        foreach( $data as $key => $value ) {
            if( $value ) {
                $parsed_value = adfoin_get_parsed_values( $value, $posted_data );

                if( $parsed_value ) {
                    $properties[$key] = $parsed_value;
                }
            }
        }
        
        $verticalresponse = VerticalResponse::get_instance();

        $verticalresponse->create_contact( $properties, $record );

        if( $list_id ) {
            $verticalresponse->add_to_list( $list_id, $properties['email'], $record );
        }
    }

    return;
}