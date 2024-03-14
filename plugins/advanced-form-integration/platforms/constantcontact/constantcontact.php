<?php

class ADFOIN_ConstantContact extends Advanced_Form_Integration_OAuth2 {

    const service_name           = 'constant_contact';
    const authorization_endpoint = 'https://authz.constantcontact.com/oauth2/default/v1/authorize';
    const token_endpoint         = 'https://authz.constantcontact.com/oauth2/default/v1/token';
    const refresh_token_endpoint = 'https://authz.constantcontact.com/oauth2/default/v1/token';

    private static $instance;
    protected      $contact_lists = array();

    public static function get_instance() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {

        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;

        $option = (array) maybe_unserialize( get_option( 'adfoin_constantcontact_keys' ) );

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
            if ( isset( $option['contact_lists'] ) ) {
                $this->contact_lists = $option['contact_lists'];
            }
        }

        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'adfoin_action_providers', array( $this, 'adfoin_constantcontact_actions' ), 10, 1 );
        add_filter( 'adfoin_settings_tabs', array( $this, 'adfoin_constantcontact_settings_tab' ), 10, 1 );
        add_action( 'adfoin_settings_view', array( $this, 'adfoin_constantcontact_settings_view' ), 10, 1 );
        add_action( 'admin_post_adfoin_save_constantcontact_keys', array( $this, 'adfoin_save_constantcontact_keys' ), 10, 0 );
        add_action( 'adfoin_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_adfoin_get_constantcontact_list', array( $this, 'get_constantcontact_list' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
    }

    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/constantcontact',
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
                    'action'  => 'adfoin_constantcontact_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=advanced-form-integration')
            );

            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function adfoin_constantcontact_actions( $actions ) {

        $actions['constantcontact'] = array(
            'title' => __( 'Constant Contact', 'advanced-form-integration' ),
            'tasks' => array(
                'subscribe'   => __( 'Subscribe To List', 'advanced-form-integration' )
            )
        );

        return $actions;
    }

    public function adfoin_constantcontact_settings_tab( $providers ) {
        $providers['constantcontact'] = __( 'Constant Contact', 'advanced-form-integration' );

        return $providers;
    }

    public function adfoin_constantcontact_settings_view( $current_tab ) {
        if( $current_tab != 'constantcontact' ) {
            return;
        }

        $option       = (array) maybe_unserialize( get_option( 'adfoin_constantcontact_keys' ) );
        $nonce        = wp_create_nonce( "adfoin_constantcontact_settings" );
        $api_key      = isset( $option['client_id'] ) ? $option['client_id'] : '';
        $api_secret   = isset( $option['client_secret'] ) ? $option['client_secret'] : '';
        $redirect_uri = $this->get_redirect_uri();
        ?>

        <form name="constantcontact_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
              method="post" class="container">

            <input type="hidden" name="action" value="adfoin_save_constantcontact_keys">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

            <table class="form-table">
            <tr valign="top">
                    <th scope="row"> <?php _e( 'Instructions', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            1. Go to <a target="_blank" rel="noopener noreferrer" href="https://app.constantcontact.com/pages/dma/portal/">Constant Contact Developer Portal</a>.</br>
                            2. Create an application, insert a suitable name.</br>
                            2. Select 'Authorization Code Flow and Implicit Flow'.</br>
                            3. Select 'Rotating Refresh Tokens' and click Create</br>
                            3. Edit the newly created app, Copy the URL from below and paste in <b>Redirect URI</b> input box.</br>
                            4. Generate API secret, then copy both API key and secreat from the app and paste below.</br>
                            5. Save the Application.</br>
                            6. Click <b>Authorize</b> below.
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'URL', 'advanced-form-integration' ); ?></th>
                    <td>
                        <code><?php echo esc_url( $redirect_uri ); ?></code>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'API Key', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type="text" name="adfoin_constantcontact_api_key"
                               value="<?php echo esc_attr( $api_key ); ?>" placeholder="<?php _e( 'Enter API Key', 'advanced-form-integration' ); ?>"
                               class="regular-text"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'API Secret', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type="text" name="adfoin_constantcontact_api_secret"
                               value="<?php echo esc_attr( $api_secret ); ?>" placeholder="<?php _e( 'Enter API Secret', 'advanced-form-integration' ); ?>"
                               class="regular-text"/>
                    </td>
                </tr>
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
            </table>
            <?php submit_button( __( 'Authorize', 'advanced-form-integration' ) ); ?>
        </form>

        <?php
    }

    public function adfoin_save_constantcontact_keys() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_constantcontact_settings' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $api_key    = isset( $_POST["adfoin_constantcontact_api_key"] ) ? sanitize_text_field( $_POST["adfoin_constantcontact_api_key"] ) : "";
        $api_secret = isset( $_POST["adfoin_constantcontact_api_secret"] ) ? sanitize_text_field( $_POST["adfoin_constantcontact_api_secret"] ) : "";

        if( !$api_key || !$api_secret ) {
            $this->reset_data();
        } else{
            $this->client_id     = trim( $api_key );
            $this->client_secret = trim( $api_secret );

            $this->save_data();
            $this->authorize( 'account_read contact_data campaign_data offline_access' );
        }

        advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=constantcontact" );
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="constantcontact-action-template">
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
                        <select name="fieldData[listId]" v-model="fielddata.listId" required="required">
                            <option value=""> <?php _e( 'Select List...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Permission Type', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[permission]" v-model="fielddata.permission">
                            <option value="explicit"> <?php _e( 'Express', 'advanced-form-integration' ); ?> </option>
                            <option value="implicit"> <?php _e( 'Implied', 'advanced-form-integration' ); ?> </option>
                        </select>
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

        if ( 'adfoin_constantcontact_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            if ( ! empty( $this->access_token ) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }

            wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=constantcontact' ) );

            exit();
        }
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
            $data["scope"] = $scope;
        }

        $endpoint = add_query_arg( $data, $this->authorization_endpoint );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function request_token( $authorization_code ) {

        $endpoint = add_query_arg(
            array(
                'code'          => $authorization_code,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                'grant_type'    => 'authorization_code'
            ),
            $this->token_endpoint
        );

        $request = [
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret),
            )
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

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'adfoin_constantcontact_keys' ) );

        $option = array_merge(
            $data,
            array(
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'contact_lists' => $this->contact_lists,
            )
        );

        update_option( 'adfoin_constantcontact_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {

        $this->client_id     = '';
        $this->client_secret = '';
        $this->access_token  = '';
        $this->refresh_token = '';
        $this->contact_lists = [ ];

        $this->save_data();
    }

    protected function get_redirect_uri() {

        return site_url( '/wp-json/advancedformintegration/constantcontact' );
    }

    public function create_contact( $properties, $record = array() ) {
        $response = $this->request( 'contacts', 'POST', $properties, $record );

        return $response;
    }

    public function update_contact( $contact_id, $properties, $record = array() ) {
        $response = $this->request( 'contacts/' . $contact_id, 'PUT', $properties, $record );

        return $response;
    }

    public function get_constantcontact_list() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $this->get_contact_lists();
    }

    public function request( $endpoint, $method = 'GET', $data = array(), $record = array() ) {
        $base_url = 'https://api.cc.email/v3/';
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

    public function get_contact_lists() {
        $response      = $this->request( 'contact_lists?limit=500' );
        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            return false;
        }

        $response_body = json_decode( $response_body, true );

        if ( !empty( $response_body['lists'] ) ) {
            $lists = wp_list_pluck( $response_body['lists'], 'name', 'list_id' );

            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }

    public function contact_exists( $email ) {
        $response      = $this->request( 'contacts?email=' . $email );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        $contact_id    = '';

        if( isset( $response_body['contacts'] ) && is_array( $response_body['contacts'] ) ) {
            if( count( $response_body['contacts'] ) > 0 && $response_body['contacts'][0]['email_address']['address'] == $email ) {
                $contact_id = $response_body['contacts'][0]['contact_id'];

                if( $contact_id ) {
                    return $contact_id;
                } else {
                    return false;
                }
            }
        }

        return false;
    }
}

$constantcontact = ADFOIN_ConstantContact::get_instance();

add_action( 'adfoin_constantcontact_job_queue', 'adfoin_constantcontact_job_queue', 10, 1 );

function adfoin_constantcontact_job_queue( $data ) {
    adfoin_constantcontact_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Constant Contact API
 */
function adfoin_constantcontact_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record['data'], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data       = $record_data['field_data'];
    $list_id    = isset( $data['listId'] ) ? $data['listId'] : '';
    $permission = isset( $data['permission'] ) ? $data['permission'] : 'explicit';
    $task       = $record['task'];


    if( $task == 'subscribe' ) {
        $email          = empty( $data['email'] ) ? '' : trim( adfoin_get_parsed_values( $data['email'], $posted_data ) );
        $first_name     = empty( $data['firstName'] ) ? '' : adfoin_get_parsed_values($data['firstName'], $posted_data );
        $last_name      = empty( $data['lastName'] ) ? '' : adfoin_get_parsed_values($data['lastName'], $posted_data );
        $company_name   = empty( $data['companyName'] ) ? '' : adfoin_get_parsed_values($data['companyName'], $posted_data );
        $job_title      = empty( $data['jobTitle'] ) ? '' : adfoin_get_parsed_values($data['jobTitle'], $posted_data );
        $work_phone     = empty( $data['workPhone'] ) ? '' : adfoin_get_parsed_values($data['workPhone'], $posted_data );
        $home_phone     = empty( $data['homePhone'] ) ? '' : adfoin_get_parsed_values($data['homePhone'], $posted_data );
        $mobile_phone   = empty( $data['mobilePhone'] ) ? '' : adfoin_get_parsed_values($data['mobilePhone'], $posted_data );
        $birthday_month = empty( $data['birthdayMonth'] ) ? '' : adfoin_get_parsed_values($data['birthdayMonth'], $posted_data );
        $birthday_day   = empty( $data['birthdayDay'] ) ? '' : adfoin_get_parsed_values($data['birthdayDay'], $posted_data );
        $anniversary    = empty( $data['anniversary'] ) ? '' : adfoin_get_parsed_values($data['anniversary'], $posted_data );
        $address_type   = empty( $data['addressType'] ) ? '' : adfoin_get_parsed_values($data['addressType'], $posted_data );
        $address1       = empty( $data['address1'] ) ? '' : adfoin_get_parsed_values($data['address1'], $posted_data );
        $city           = empty( $data['city'] ) ? '' : adfoin_get_parsed_values($data['city'], $posted_data );
        $state          = empty( $data['state'] ) ? '' : adfoin_get_parsed_values($data['state'], $posted_data );
        $zip            = empty( $data['zip'] ) ? '' : adfoin_get_parsed_values($data['zip'], $posted_data );
        $country        = empty( $data['country'] ) ? '' : adfoin_get_parsed_values($data['country'], $posted_data );
        $properties     = array();

        if( $email ) { $properties['email_address'] = array( 'address' => $email, 'permission_to_send' => $permission ); }
        if( $first_name ) { $properties['first_name'] = $first_name; }
        if( $last_name ) { $properties['last_name'] = $last_name; }
        if( $company_name ) { $properties['company_name'] = $company_name; }
        if( $job_title ) { $properties['job_title'] = $job_title; }
        if( $birthday_month ) { $properties['birthday_month'] = $birthday_month; }
        if( $birthday_day ) { $properties['birthday_day'] = $birthday_day; }
        if( $anniversary ) { $properties['anniversary'] = $anniversary; }

        if( $list_id ) {
            $properties['list_memberships'] = array( $list_id );
        }

        if( $work_phone || $home_phone || $mobile_phone ) {
            $properties['phone_numbers'] = array();

            if( $work_phone ) {
                array_push( $properties['phone_numbers'], array( 'phone_number' => $work_phone, 'kind' => 'work' ) );
            }

            if( $home_phone ) {
                array_push( $properties['phone_numbers'], array( 'phone_number' => $home_phone, 'kind' => 'home' ) );
            }

            if( $mobile_phone ) {
                array_push( $properties['phone_numbers'], array( 'phone_number' => $mobile_phone, 'kind' => 'mobile' ) );
            }
        }

        if( $address1 || $city || $state || $zip || $country ) {
            $kind = $address_type ? $address_type : 'home';
            $properties['street_addresses'] = array(array(
                'kind'        => $kind,
                'street'      => $address1,
                'city'        => $city,
                'state'       => $state,
                'postal_code' => $zip,
                'country'     => $country
            ));
        }

        $constantcontact = ADFOIN_ConstantContact::get_instance();
        $contact_id      = $constantcontact->contact_exists( $email );

        if( $contact_id ) {
            $properties['update_source'] = 'Account';
            $return = $constantcontact->update_contact( $contact_id, $properties, $record );
        } else {
            $properties['create_source'] = 'Account';
            $return = $constantcontact->create_contact( $properties, $record );
        }
    }

    return;
}