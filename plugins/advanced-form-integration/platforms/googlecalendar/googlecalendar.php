<?php

class ADFOIN_GoogleCalendar extends Advanced_Form_Integration_OAuth2 {

    const service_name           = 'googlecalendar';
    const authorization_endpoint = 'https://accounts.google.com/o/oauth2/auth';
    const token_endpoint         = 'https://www.googleapis.com/oauth2/v3/token';

    private static $instance;
    protected $client_id          = '';
    protected $client_secret      = '';
    protected $google_access_code = '';
    protected $calendar_lists     = array();

    public static function get_instance() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        $this->token_endpoint         = self::token_endpoint;
        $this->authorization_endpoint = self::authorization_endpoint;

        $option = (array) maybe_unserialize( get_option( 'adfoin_googlecalendar_keys' ) );

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
            if ( isset( $option['calendar_lists'] ) ) {
                $this->calendar_lists = $option['calendar_lists'];
            }
        }

        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'adfoin_action_providers', array( $this, 'adfoin_googlecalendar_actions' ), 10, 1 );
        add_filter( 'adfoin_settings_tabs', array( $this, 'adfoin_googlecalendar_settings_tab' ), 10, 1 );
        add_action( 'adfoin_settings_view', array( $this, 'adfoin_googlecalendar_settings_view' ), 10, 1 );
        add_action( 'admin_post_adfoin_save_googlecalendar_keys', array( $this, 'adfoin_save_googlecalendar_keys' ), 10, 0 );
        add_action( 'adfoin_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_adfoin_get_googlecalendar_list', array( $this, 'get_calendar_list' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
    }

    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/googlecalendar',
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
                    'action'  => 'adfoin_googlecalendar_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=advanced-form-integration')
            );

            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function auth_redirect() {

        $action = isset( $_GET['action'] ) ? sanitize_text_field( trim( $_GET['action'] ) ) : '';

        if ( 'adfoin_googlecalendar_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            wp_safe_redirect( admin_url( 'admin.php?page=advanced-form-integration-settings&tab=googlecalendar' ) );

            exit();
        }
    }

    public function adfoin_googlecalendar_actions( $actions ) {

        $actions['googlecalendar'] = array(
            'title' => __( 'Google Calendar', 'advanced-form-integration' ),
            'tasks' => array(
                'addEvent'   => __( 'Add New Event', 'advanced-form-integration' )
            )
        );

        return $actions;
    }

    public function adfoin_googlecalendar_settings_tab( $providers ) {
        $providers['googlecalendar'] = __( 'Google Calendar', 'advanced-form-integration' );

        return $providers;
    }

    public function adfoin_googlecalendar_settings_view( $current_tab ) {
        if( $current_tab != 'googlecalendar' ) {
            return;
        }

        $option        = (array) maybe_unserialize( get_option( 'adfoin_googlecalendar_keys' ) );
        $nonce         = wp_create_nonce( "adfoin_googlecalendar_settings" );
        $client_id     = isset( $option['client_id'] ) ? $option['client_id'] : "";
        $client_secret = isset( $option['client_secret'] ) ? $option['client_secret'] : "";
        $redirect_uri  = $this->get_redirect_uri();
        $domain        = parse_url( get_site_url() );
        $host          = $domain['host'];
        ?>

        <form name="googlecalendar_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
              method="post" class="container">

            <input type="hidden" name="action" value="adfoin_save_googlecalendar_keys">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Instructions', 'advanced-form-integration' ); ?></th>
                    <td>
                        <p>
                            <?php _e( '1. Go to <a target="_blank" rel="noopener noreferrer" href="https://console.cloud.google.com/project">Google Developer Console</a> and create a <b>New Project</b><br>
                                      2. Go to <b>Library</b> side menu and search for <b>Google Calendar API</b>, open it and click <b>ENABLE</b>.<br>', 'advanced-form-integration' ); ?>
                                      <?php printf( __( '4. Go to <b>OAuth consent screen</b>, select <b>External</b> click <b>Create</b>. Put an <b>Applicatoin name</b> as you want, select user support email, enter <code><i>%s</i></code> in <b>Authorized domains</b>, put your email on developer contact email. In scopes calendar read/write scopes. then click <b>Save</b>. Please set the publishing status as <b>in production</b>, otherwise you might get a 403 error.<br>', 'advanced-form-integration' ), $host ) ?>
                                      <?php printf( __( '5. Go to <b>Credentials</b>, click <b>CREATE CREDENTIALS</b>, select <b>OAuth client ID</b>, select application type as <b>Web application</b>, click <b>Create</b>, put anything in <b>Name</b>, save <code><i>%s</i></code> in <b>Authorized redirect URIs</b>, click <b>Create</b>.<br>', 'advanced-form-integration' ), $redirect_uri ) ?>
                                      <?php _e( '6. Copy <b>Client ID</b> and <b>Client Secret</b> from newly created app and save below.<br>', 'advanced-form-integration' ); ?>
                                      <?php _e( '7. Click <b>Save & Authorize</b>, if appears <b>App is not verified</b> error click <b>show advanced</b> and then <b>Go to App</b>.<br><br>', 'advanced-form-integration' ); ?>
                                      <?php _e( '(You can also check this video instruction <a target="_blank" rel="noopener noreferrer" href="https://youtu.be/omYFbXN0ECw">https://youtu.be/omYFbXN0ECw</a>)', 'advanced-form-integration' ); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Status', 'advanced-form-integration' ); ?></th>
                    <td>
                        <?php
                        if( isset( $option['refresh_token'] ) && $option['refresh_token'] ) {
                            _e( 'Connected', 'advanced-form-integration' );
                        } else {
                            _e( 'Not Connected', 'advanced-form-integration' );
                        }
                        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Client ID', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type="text" name="adfoin_googlecalendar_client_id"
                               value="<?php echo esc_attr( $client_id ); ?>" placeholder="<?php _e( 'Enter Client ID', 'advanced-form-integration' ); ?>"
                               class="regular-text"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Client Secret', 'advanced-form-integration' ); ?></th>
                    <td>
                        <input type="text" name="adfoin_googlecalendar_client_secret"
                               value="<?php echo esc_attr( $client_secret ); ?>" placeholder="<?php _e( 'Enter Client Secret', 'advanced-form-integration' ); ?>"
                               class="regular-text"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php _e( 'Re-Authorize', 'advanced-form-integration' ); ?></th>
                    <td>
                    <?php
                        _e( 'Try re-authorizing if you face issues. Go to <a target="_blank" rel="noopener noreferrer" href="https://myaccount.google.com/permissions" ><b>Google App Permissions</b></a> and hit <b>REMOVE ACCESS</b> on any previous authorization of this app. Now click on the <b>Save & Authorize</b> button below and finish the authorization process again.', 'advanced-form-integration' );
                        ?>
                    </td>
                </tr>
            </table>
            <?php submit_button( __( 'Save & Authorize', 'advanced-form-integration' ) ); ?>
        </form>

        <?php
    }

    public function adfoin_save_googlecalendar_keys() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'adfoin_googlecalendar_settings' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $client_id     = isset( $_POST['adfoin_googlecalendar_client_id'] ) ? sanitize_text_field( $_POST['adfoin_googlecalendar_client_id'] ) : '';
        $client_secret = isset( $_POST['adfoin_googlecalendar_client_secret'] ) ? sanitize_text_field( $_POST['adfoin_googlecalendar_client_secret'] ) : '';

        if( empty( $client_id ) || empty( $client_secret ) ) {
            $this->reset_data();
            
            advanced_form_integration_redirect( "admin.php?page=advanced-form-integration-settings&tab=googlecalendar" );

            exit;
        }

        $this->client_id     = trim( $client_id );
        $this->client_secret = trim( $client_secret );

        $this->save_data();
        $this->authorize( 'https://www.googleapis.com/auth/calendar' );
    }

    protected function authorize( $scope = '' ) {

        $endpoint = add_query_arg(
            array(
                'response_type' => 'code',
                'access_type'   => 'offline',
                'client_id'     => $this->client_id,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                'scope'         => urlencode( $scope ),
            ),
            $this->authorization_endpoint
        );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function request_token( $authorization_code ) {

        $args = array(
            'headers' => array(),
            'body'    => array(
                'code'          => $authorization_code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->get_redirect_uri(),
                'grant_type'    => 'authorization_code',
                'access_type'   => 'offline',
                'prompt'        => 'consent'
            )
        );

        $response      = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
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

    public function action_fields() {
        ?>
        <script type="text/template" id="googlecalendar-action-template">
            <table class="form-table">
                <tr valign="top" v-if="action.task == 'addEvent'">
                    <th scope="row">
                        <?php esc_attr_e( 'Map Fields', 'advanced-form-integration' ); ?>
                    </th>
                    <td scope="row">

                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Documentation', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <?php _e( 'Need help? See <a target="_blank" rel="noopener noreferrer" href="https://advancedformintegration.com/docs/receiver-platforms/google-calendar/">Google Calendar Documentation</a>.', 'advanced-form-integration' ); ?>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Calendar', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="fieldData[calendarId]" v-model="fielddata.calendarId" required="required">
                            <option value=""> <?php _e( 'Select Calendar...', 'advanced-form-integration' ); ?> </option>
                            <option v-for="(item, index) in fielddata.calendarList" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Title', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.title" name="fieldData[title]" required="required">
                        <select @change="updateFieldValue('title')" v-model="title">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Title of the event.', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Description', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <textarea class="regular-text" v-model="fielddata.description" name="fieldData[description]" rows="8"></textarea>
                        <select @change="updateFieldValue('description')" v-model="description">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Description of the event. Can contain HTML.', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'All Day Event', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="checkbox" name="fieldData[allDayEvent]" value="true" v-model="fielddata.allDayEvent">
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Start Date Time', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.start" name="fieldData[start]" required="required">
                        <select @change="updateFieldValue('start')" v-model="start">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Required, use a valid Date or DateTime format', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'End Date Time', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.end" name="fieldData[end]">
                        <select @change="updateFieldValue('end')" v-model="end">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Required, use a valid Date or DateTime format', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Timezone', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.timezone" name="fieldData[timezone]">
                        <select @change="updateFieldValue('timezone')" v-model="timezone">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Optional, overrides default WordPress timezone. (Formatted as an <a target="_blank" rel="noopener noreferrer" href="https://en.wikipedia.org/wiki/List_of_tz_database_time_zones">IANA Time Zone</a> Database name, e.g. "Europe/Zurich".)', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Location', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.location" name="fieldData[location]">
                        <select @change="updateFieldValue('location')" v-model="location">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Geographic location of the event as free-form text. Optional.', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'addEvent'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_attr_e( 'Attendees', 'advanced-form-integration' ); ?>
                        </label>
                    </td>
                    <td>
                        <input type="text" class="regular-text" v-model="fielddata.attendees" name="fieldData[attendees]">
                        <select @change="updateFieldValue('attendees')" v-model="attendees">
                            <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                        <p class="description"><?php _e( 'Accepts attendee\'s email. Use comma for multiple attendees.', 'advanced-form-integration' ); ?></p>
                    </td>
                </tr>

                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            </table>
        </script>
        <?php
    }

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'adfoin_googlecalendar_keys' ) );

        $option = array_merge(
            $data,
            array(
                'client_id'      => $this->client_id,
                'client_secret'  => $this->client_secret,
                'access_token'   => $this->access_token,
                'refresh_token'  => $this->refresh_token,
                'calendar_lists' => $this->calendar_lists
            )
        );

        update_option( 'adfoin_googlecalendar_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {

        $this->client_id          = '';
        $this->client_secret      = '';
        $this->google_access_code = '';
        $this->access_token       = '';
        $this->refresh_token      = '';
        $this->calendar_lists     = array();

        $this->save_data();
    }

    protected function get_redirect_uri() {

        return site_url( '/wp-json/advancedformintegration/googlecalendar' );
    }

    public function get_calendar_list() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
            die( __( 'Security check Failed', 'advanced-form-integration' ) );
        }

        $endpoint = "https://www.googleapis.com/calendar/v3/users/me/calendarList";

        $request = array(
            'method'  => 'GET',
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->access_token
            ),
        );

        $response      = $this->remote_request( $endpoint, $request );
        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            return false;
        }

        $body = json_decode( $response_body, true );

        $calendar_list = $body['items'] ;
        $list          = wp_list_pluck( $calendar_list, 'summary', 'id' );

        wp_send_json_success( $list );
    }

    protected function remote_request( $url, $request = array() ) {

        if( !$this->check_token_expiry( $this->access_token ) ) {
            $this->refresh_token();
        }

        $request = wp_parse_args( $request, array() );

        $request['headers'] = array_merge(
            $request['headers'],
            array(
                'Authorization' => $this->get_http_authorization_header( 'bearer' ),
            )
        );

        $response = wp_remote_request( esc_url_raw( $url ), $request );

        return $response;
    }

    public function check_token_expiry( $token ='' ) {
        $response = array();

        if ( empty( $token ) ) {
            return;
        }

        $return = wp_remote_get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token );

        if( is_wp_error( $return ) ) {
            return false;
        }

        $body = json_decode( $return['body'], true );

        if ( $return['response']['code'] == 200 ) {
            return true;
        }

        return false;
    }

    protected function refresh_token() {

        $args = array(
            'headers' => array(),
            'body'    => array(
                'refresh_token' => $this->refresh_token,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type'    => 'refresh_token',
            )
        );

        $response      = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
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

    public function create_event( $calendar_id, $calendar_data, $record ) {

        if ( !$calendar_id || empty( $calendar_data ) ) {
            return false;
        }

        if( !$this->check_token_expiry( $this->access_token ) ) {
            $this->refresh_token();
        }

        $endpoint = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events";

        $request = array(
            'method'  => 'POST',
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->access_token
            ),
            'body' => json_encode( $calendar_data )
        );

        $response = $this->remote_request( $endpoint, $request );

        adfoin_add_to_log( $response, $endpoint, $request, $record );
    }
}

$googlecalendar = ADFOIN_GoogleCalendar::get_instance();

add_action( 'adfoin_googlecalendar_job_queue', 'adfoin_googlecalendar_job_queue', 10, 1 );

function adfoin_googlecalendar_job_queue( $data ) {
    adfoin_googlecalendar_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles sending data to Google Calendar API
 */
function adfoin_googlecalendar_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !adfoin_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }

    $data = $record_data['field_data'];
    $task = $record['task'];

    if( $task == 'addEvent' ) {
        $calendar_id   = isset( $data['calendarId'] ) ? $data['calendarId'] : '';
        $all_day_event = isset( $data['allDayEvent'] ) ? $data['allDayEvent'] : '';
        $summary       = empty( $data['title'] ) ? '' : adfoin_get_parsed_values( $data['title'], $posted_data );
        $description   = empty( $data['description'] ) ? '' : adfoin_get_parsed_values( $data['description'], $posted_data );
        $start         = empty( $data['start'] ) ? '' : adfoin_get_parsed_values( $data['start'], $posted_data );
        $end           = empty( $data['end'] ) ? '' : adfoin_get_parsed_values( $data['end'], $posted_data );
        $timezone      = empty( $data['timezone'] ) ? '' : adfoin_get_parsed_values( $data['timezone'], $posted_data );
        $location      = empty( $data['location'] ) ? '' : adfoin_get_parsed_values( $data['location'], $posted_data );
        $attendees     = empty( $data['attendees'] ) ? '' : adfoin_get_parsed_values( $data['attendees'], $posted_data );
        $startdatetime = '';
        $enddatetime   = '';

        $calendar_data = array(
            'summary'     => $summary,
            'description' => $description,
            'start'       => array()
        );

        if( 'true' == $all_day_event ) {
            $startdatetime = adfoin_googlecalendar_get_formatted_datetime( $start, $timezone, 'Y-m-d' );
            $enddatetime   = adfoin_googlecalendar_get_formatted_datetime( $end, $timezone, 'Y-m-d' );

            if( $startdatetime ) {
                $calendar_data['start']['date'] = $startdatetime;
            }

            if( $enddatetime ) {
                $calendar_data['end']['date'] = $enddatetime;
            }
        } else {
            $startdatetime = adfoin_googlecalendar_get_formatted_datetime( $start, $timezone );
            $enddatetime   = adfoin_googlecalendar_get_formatted_datetime( $end, $timezone );

            if( $startdatetime ) {
                $calendar_data['start']['dateTime'] = $startdatetime;
            }

            if( $enddatetime ) {
                $calendar_data['end']['dateTime'] = $enddatetime;
            }
        }

        if( $timezone ) {
            if( isset( $calendar_data['start'] ) ) {
                $calendar_data['start']['timezone'] = $timezone;
            }

            if( isset( $calendar_data['end'] ) ) {
                $calendar_data['end']['timezone'] = $timezone;
            }
        }

        if( $location ) {
            $calendar_data['location'] = $location;
        }

        if( $attendees ) {
            $attendees = explode( ',', $attendees );
            $formatted = array();

            if( is_array( $attendees ) ) {
                foreach( $attendees as $attendee ) {
                    array_push( $formatted, array( 'email' => trim( $attendee ) ) );
                }
            }

            if( !empty( $formatted ) ) {
                $calendar_data['attendees'] = $formatted;
            }
        }

        if ( $calendar_id ) {
            $googlecalendar = ADFOIN_GoogleCalendar::get_instance();
            $googlecalendar->create_event( $calendar_id, $calendar_data, $record );
        }
    }

    return;
}

function adfoin_googlecalendar_get_formatted_datetime( $data, $timezone, $format = '' ) {
    if( false === strtotime( $data ) ) {
        return false;
    }

    if( empty( $timezone ) ) {
        $timezone = wp_timezone();
    } else {
        $timezone = new DateTimeZone( $timezone );
    }

    $dt                 = date_create( $data, $timezone );
    $formatted_datetime = '';

    if( 'Y-m-d' == $format ) {
        $formatted_datetime = date_format( $dt, 'Y-m-d' );
    } else {
        $formatted_datetime = date_format( $dt, DateTime::RFC3339 );
    }

    return $formatted_datetime;
}