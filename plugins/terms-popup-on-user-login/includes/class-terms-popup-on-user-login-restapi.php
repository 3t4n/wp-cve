<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;
use WpLHLAdminUi\LicenseKeys\LicenseKeyLocalValidator;
use WpLHLAdminUi\LicenseKeys\LicenseKeyFastCheck;
// use WpLHLAdminUi\Process\ProcessLocker;


defined('ABSPATH') || exit;

/**
 * Extend the main WP_REST_Posts_Controller to a private endpoint controller.
 */

// https://stackoverflow.com/questions/60327492/wp-rest-api-custom-post-endpoint-not-working-404-error/60402626#60402626
// ca

class Terms_Popup_On_User_Login_Rest_API extends WP_REST_Posts_Controller {

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespace = 'terms-popup-on-user-login/v1';

    /**
     * Rest base for the current object.
     *
     * @var string
     */
    protected $rest_base = 'action';

    protected $terms_options;
    private $license_is_active;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $get_terms_options = get_option('tpul_settings_term_modal_options');
        $this->terms_options = $get_terms_options;

        $this->terms_options['terms_modal_designated_test_user'] = '';
        if (!empty($this->terms_options['terms_modal_designated_test_user'])) {
            $this->terms_options['terms_modal_designated_test_user'] = $this->terms_options['terms_modal_designated_test_user'] ?: '';
        }

        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;
        $this->license_is_active = $license_key_handler->is_active();
    }

    /**
     * Register the routes for the objects of the controller.
     *
     * Nearly the same as WP_REST_Posts_Controller::register_routes(), but all of these
     * endpoints are hidden from the index.
     */
    public function register_routes() {

        /* Accept Terms
         * wp-json/terms-popup-on-user-login/v1/action/accept-terms
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/accept-terms', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'set_user_accepted'),
                'permission_callback' => array($this, 'set_user_accepted_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Accept Terms
         * wp-json/terms-popup-on-user-login/v1/action/logout-user
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/logout-user', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'logout_user'),
                'permission_callback' => array($this, 'logout_user_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Accept Terms
         * wp-json/terms-popup-on-user-login/v1/action/logout-user
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/reset-users', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'reset_users'),
                'permission_callback' => array($this, 'reset_users_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Accept Terms
         * /wp-json/terms-popup-on-user-login/v1/action/reset-single-user
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/reset-single-user', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'reset_single_user'),
                'permission_callback' => array($this, 'reset_single_user_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Validate Key
         * wp-json/terms-popup-on-user-login/v1/action/activatekey
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/activatekey', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'activatekey'),
                'permission_callback' => array($this, 'activatekey_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Deactivate Key
         * wp-json/terms-popup-on-user-login/v1/action/deactivatekey
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/deactivatekey', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'deactivatekey'),
                'permission_callback' => array($this, 'deactivatekey_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Enable Log
         * wp-json/terms-popup-on-user-login/v1/action/log
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/log', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'db_log'),
                'permission_callback' => array($this, 'db_log_enable_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Log Purge
         * wp-json/terms-popup-on-user-login/v1/action/log/purge
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/log/purge', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'db_log_purge'),
                'permission_callback' => array($this, 'db_log_purge_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Log Purge
         * wp-json/terms-popup-on-user-login/v1/action/log/report-generate
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/log/report-generate', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'report_generate'),
                'permission_callback' => array($this, 'report_generate_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Log Generate
         * wp-json/terms-popup-on-user-login/v1/action/log/log-generate
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/log/log-generate', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'log_generate'),
                'permission_callback' => array($this, 'log_generate_permission_check'),
                'show_in_index'       => false,
            ),
        ));

        /* Send Test Email
         * wp-json/erms-popup-on-user-login/v1/action/email/testemail
         */
        register_rest_route($this->namespace, '/' . $this->rest_base . '/email/test-email', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'email_test'),
                'permission_callback' => array($this, 'email_test_permission_check'),
                'show_in_index'       => false,
            ),
        ));
    }

    /**
     * Accept Terms
     */

    public function set_user_accepted_permission_check($request) {
        return true;
    }

    public function set_user_accepted($request) {

        $error = new WP_Error();
        $response = array();

        $response['code'] = "updated_user";
        $response['message'] = __("Updated User", "terms-popup-on-user-login");
        $response['data'] = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            return $this->__handle_anonymous_accepting_terms($request);
        }


        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data']['nonce'] = 'correct';
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        $response['data']['user_id'] = $user_id;
        $response['data']['test_user_id'] = $this->terms_options['terms_modal_designated_test_user'];

        /**
         * Don't mark as Accepted if this is the special TEST user
         * mark as accepted for everyone else
         */
        if ($user_id != $this->terms_options['terms_modal_designated_test_user']) {

            $gen_options = new TPUL_Modal_Options();

            $request_body = $request->get_json_params();
            $response['data']['got'] = $request_body['useragent'];

            $user_state_manager = new TPUL_User_State($user->ID);

            $accepted_options['useragent'] = $request_body['useragent'];

            if ($gen_options->get_track_location()) {
                $accepted_options['locationCoordinates'] = json_decode($request_body['locationCoordinates'], true);
            }

            if ($gen_options->get_track_IP()) {
                $accepted_options['clientIP'] = [
                    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? 'n/a',
                    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'n/a',
                    'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? 'n/a',
                ];
            }

            $user_state_manager->useraction_accepted($accepted_options);

            /**
             * Send Email notification they they accepted if
             * this osption is turned on
             */
            if ($this->license_is_active) {
                $emailOptions = new TPUL_Email_Options();
                $options = $emailOptions->get_options();
                $email_sender = new TPUL_EmailSender();
                if (!empty($options['email_send_to_user'])) {
                    $email_sender->notify_accept_user($user_id);
                }
            }
        }

        $response['data']['accepted'] = 2;

        /**
         * Redirect
         */
        if ($this->terms_options['terms_modal_accept_redirect']) {
            $response['data']['redirect'] = $this->terms_options['terms_modal_accept_redirect'];
        }

        /**
         * Log Action if Addvanced Logging is turned on
         */
        $logging = get_option('tpul_addv_logging');
        if ($logging) {
            $data = [
                'created_at' => current_time('mysql'),
                'the_user_id' => $user_id,
                'user_displayname' => $user->display_name,
                'user_username' => $user->user_login,
                'user_action' => 'Accepted'
            ];
            $create_log_table = termspul\Tpul_DB::insert($data);
        }


        // Response
        return new WP_REST_Response($response, 200);
    }

    /**
     * Handle the case for non logged in user hitting accept
     * in case of woo popup etc.
     */
    private function __handle_anonymous_accepting_terms($request) {
        $error = new WP_Error();

        $general_options = new TPUL_General_Options();
        $modal_options = new TPUL_Modal_Options();
        $woo_options = new TPUL_Woo_Options();

        $request_body = $request->get_json_params();

        /**
         * check if woocommerce mode is on
         * check if popup is set to show for anonymous users as well
         */
        if (
            ('terms_and_conditions_modal_woo' === $general_options->get_modal_to_show())
            &&
            ($woo_options->is_user_type_anonymous())
        ) {
            // continue down below
        } else {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        $user_id = 0;
        $tpul_visitor_id = sanitize_text_field($request_body['tpul_visitor_id']);
        $site_name = get_bloginfo('name');
        $currentURL = esc_url($request_body['currentURL']);


        $request_body = $request->get_json_params();
        if ($modal_options->get_track_location()) {
            $accepted_options['locationCoordinates'] = json_decode($request_body['locationCoordinates'], true);
        }

        $ip_for_email = '';
        if ($modal_options->get_track_IP()) {
            $accepted_options['clientIP'] = [
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? 'n/a',
                'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'n/a',
                'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? 'n/a',
            ];
            $ip_for_email = implode(", ", $accepted_options['clientIP']);
        }

        // if we should notify admins about anonymous acceptance
        if ($this->license_is_active) {
            $emailOptions = new TPUL_Email_Options();
            $options = $emailOptions->get_options();
            $email_sender = new TPUL_EmailSender();
            if (!empty($options['email_notify_about_anonymous'])) {
                $email_sender->notify_admins($tpul_visitor_id, $site_name, $currentURL, $ip_for_email,);
            }
        }

        /**
         * Log Action if Addvanced Logging is turned on
         */
        $logging = get_option('tpul_addv_logging');
        if ($logging) {
            $data = [
                'created_at' => current_time('mysql'),
                'the_user_id' => $tpul_visitor_id,
                'user_displayname' => "anonymous visitor",
                'user_username' => 'anonymous visitor',
                'user_action' => 'Accepted'
            ];
            $create_log_table = termspul\Tpul_DB::insert($data);
        }

        $response['code'] = "updated_user";
        $response['message'] = __("Updated User", "terms-popup-on-user-login");
        $response['data']['user_id'] = $user_id;
        $response['data']['accepted'] = 2;
        if ($this->terms_options['terms_modal_accept_redirect']) {
            $response['data']['redirect'] = $this->terms_options['terms_modal_accept_redirect'];
        }
        // Response
        return new WP_REST_Response($response, 200);
    }

    /**
     * Logout user
     */
    public function logout_user_permission_check($request) {
        return true;
    }

    public function logout_user($request) {

        $error = new WP_Error();
        $response = array();

        $response['code'] = "logout_user";
        $response['message'] = __("logout_user", "terms-popup-on-user-login");
        $response['data'] = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => rest_cookie_check_errors($request));
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        $user_state_manager = new TPUL_User_State($user_id);
        $user_state_manager->useraction_declined();

        /**
         * Log Action if Addvanced Logging is turned on
         */
        $logging = get_option('tpul_addv_logging');
        if ($logging) {
            $data = [
                'created_at' => current_time('mysql'),
                'the_user_id' => $user_id,
                'user_displayname' => $user->display_name,
                'user_username' => $user->user_login,
                'user_action' => 'Declined'
            ];
            $create_log_table = termspul\Tpul_DB::insert($data);
        }


        // Log Out User
        // unless they have an active license key and hve turned off this feature
        $modal_options = get_option('tpul_settings_term_modal_options');
        $nologout = $modal_options['terms_modal_decline_nologout'];
        if ($this->license_is_active && empty($nologout)) {
            $this->logout_go_home();
        }

        // Tell Ajax Return to redirect user to Home page
        $response['data']['wasset'] = $user_state_manager->get_user_state();
        $response['data']['uid'] = $user_id;

        if ($this->terms_options['terms_modal_decline_redirect']) {
            $response['data']['redirect'] = $this->terms_options['terms_modal_decline_redirect'];
        }

        return new WP_REST_Response($response, 200);
    }


    public function reset_single_user_permission_check() {
        if (current_user_can('manage_options')) {
            return true;
        }
    }

    public function reset_single_user($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        // Check if premium feature is available
        if (!$this->license_is_active) {
            $error->add("license_inactive", __('You need a License Key to use this feature.'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        $response['code'] = "reset_single_user";
        $response['message'] = __("reset_single_user", "terms-popup-on-user-login");

        // $the_user_id  = 0;
        /**
         * Get Parameters
         */
        $parameters = $request->get_json_params();

        if (empty($parameters['userId']) || !is_numeric($parameters['userId'])) {
            $error->add("invalid_user_id", __('Bad user ID provided.'), array('status' => 404));
            return $error;
        }
        $the_user_id = $parameters['userId'];

        /**
         * Reset User
         */
        $user_state_manager = new TPUL_User_State($the_user_id);
        $user_state_manager->useraction_reset();

        $response['data']['user'][] = $user;

        /**
         * Registen when Reset Ran
         */
        $the_time = time();
        $rest_info = get_option('tpul_settings_term_modal_reset_info');
        $rest_info['last_ran'] = $the_time;
        update_option('tpul_settings_term_modal_reset_info', $rest_info);


        /**
         * Return
         */
        return new WP_REST_Response($response, 200);
    }

    public function reset_users_permission_check() {
        if (current_user_can('manage_options')) {
            return true;
        }
    }


    public function reset_users($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;

        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        // Check if premium feature is available
        if (!$this->license_is_active) {
            $error->add("license_inactive", __('You need a License Key to use this feature.'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        $response['code'] = "reset_user";
        $response['message'] = __("reset_user", "terms-popup-on-user-login");


        $users = get_users(['fields' => ['ID']]);
        foreach ($users as $user) {
            $the_user_id   = (int) $user->ID;

            /**
             * Reset User
             */
            $user_state_manager = new TPUL_User_State($the_user_id);
            $user_state_manager->useraction_reset();

            $response['data']['users'][] = $user;
        }

        /**
         * Registen when Reset Ran
         */
        $the_time = time();
        $rest_info = get_option('tpul_settings_term_modal_reset_info');
        $rest_info['last_ran'] = $the_time;
        update_option('tpul_settings_term_modal_reset_info', $rest_info);


        /**
         * Return
         */
        return new WP_REST_Response($response, 200);
    }


    public function activatekey_permission_check($request) {
        return true;
    }

    public function activatekey($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }


        /**
         * Check if local license is present
         * this will not use the lciense key but rather a local license key file provided
         * this handles special use cases where the site can not reach lehelmatyus.com
         * to activa the key online
         * 
         * see /terms-popup-on-user-login/local-license/readme.txt
         * 
         */
        $pluginsDirectoryPath = WP_PLUGIN_DIR;
        $licenseFilePath = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/license.pem';
        $publicKeyPath  = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/public_key.pem';
        if (file_exists($licenseFilePath) && file_exists($publicKeyPath)) {
            return $this->_local_activation($request);
        }

        /**
         * Get Parameters
         */
        $parameters = $request->get_json_params();


        /**
         * Init Handler
         */

        $key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;

        /**
         * Get License Key from request
         */
        if (empty($parameters["license_key"])) {
            $error->add("no_key_provided", __($key_handler->get_message('no_key_provided')), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }


        /**
         * Activate License
         */

        /**
         * Fast Check First
         */

        $key = $parameters["license_key"];

        $fast_check_server = 'https://lehelmatyus-license-keys-default-rtdb.firebaseio.com/license-keys/terms-popup-on-user-login/';
        if (strpos($key, "APSU-") === 0) {
            $fast_check_server .= 'appsumo-license-keys/';
        } else {
            $fast_check_server .= 'license-keys/';
        }

        $fast_checker = new LicenseKeyFastCheck($fast_check_server, $key);
        if ($fast_checker->checkForLicense() > 0) {

            // Save Stuff in WP DB and return success message
            $key_activated_succesfully = $key_handler->activate_key($parameters["license_key"]);

            // Successfully activated by License Manager
            $response['code'] = "activated";
            $response['message'] = __($key_handler->get_message('activated'), "terms-popup-on-user-login");
            $response['key_activated_succesfully'] = $key_activated_succesfully;

            return new WP_REST_Response($response, 200);
        }

        /**
         * End of Fastcheck
         * fall back to regular check if not found
         */

        /**
         * Check License Server
         */
        // Decode response from activator.
        $_com_response = $key_handler->_comm__activate_key($parameters["license_key"]);

        if (empty($_com_response)) {
            $error->add("empty_response", __($key_handler->get_message('empty_response')), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }

        $response['_com_response'] = $_com_response;

        /**
         * If License Key manager says there is no such key
         */
        if (!empty($_com_response->data->status) && $_com_response->data->status == 404) {
            $error->add("key_not_good", __($key_handler->get_message('key_not_good') . ' ' . $_com_response->message), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }

        /**
         * Check if License expiration date is missing or expiration date in past
         */
        if (
            (!empty($_com_response->data->expiresAt) && $key_handler->check_if_a_date_is_in_past($_com_response->data->expiresAt))
        ) {
            $error->add("key_expired", __($key_handler->get_message('key_expired') . ' expired at: ' . $_com_response->data->expiresAt . ' ' . $_com_response->message), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }

        /**
         * Test For Success
         */
        if (
            !empty($_com_response->success) &&
            ($_com_response->success == true) &&
            !empty($_com_response->data->timesActivated) &&
            ($_com_response->data->timesActivated <= $_com_response->data->timesActivatedMax)
        ) {

            // Save Stuff in WP DB and return success message
            $key_activated_succesfully = $key_handler->activate_key($parameters["license_key"], $_com_response->data->expiresAt);
            // error_log(print_r($_com_response, true));

            // Successfully activated by License Manager
            $response['code'] = "activated";
            $response['message'] = __($key_handler->get_message('activated'), "terms-popup-on-user-login");
            $response['key_activated_succesfully'] = $key_activated_succesfully;

            return new WP_REST_Response($response, 200);
        }


        /**
         * Was not able to Activate
         */
        $error->add("unable_to_activate", __($key_handler->get_message('unable_to_activate') . " " . $_com_response->message), array('status' => 404));
        $key_handler->flush_key_related_info();
        return $error;
    }

    /**
     * Veryfies a license.pem file
     * against the current domain
     * to see if the license.pem was truly generated for the current license or not
     * 
     * for websites that would prefer not to connect to lehelmatyus.com
     * for license verification
     * 
     * a license.pem file must be personally asked for a domain
     * through lehelmatyus.com/contact
     */
    private function _local_activation() {

        $error = new WP_Error();
        $response = array();
        $key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;


        $pluginsDirectoryPath = WP_PLUGIN_DIR;

        $publicKeyPath  = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/public_key.pem';
        $privateKeyPath = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/private_key.pem';

        $domainsFilePath = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/domains.txt';
        $licenseFilePath = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/license.pem';

        $encryptionManager = new LicenseKeyLocalValidator($publicKeyPath, $privateKeyPath);

        $home_url = home_url();
        $parsed_url = wp_parse_url($home_url);
        $currentDomain = $parsed_url['host'];
        $currentDomain = "standoutnow.com";

        $domains = $encryptionManager->decryptFromFile($licenseFilePath);

        if (strpos($domains, $currentDomain) !== false) {
            // Activated with Local Key
            $key_activated_succesfully = $key_handler->activate_key("Local License Key", "");
            $response['code'] = "activated";
            $response['message'] = __($key_handler->get_message('activated'), "terms-popup-on-user-login");
            $response['key_activated_succesfully'] = $key_activated_succesfully;
            return new WP_REST_Response($response, 200);
        } else {
            // Local Key not good
            $error->add("bad_local_license", __($key_handler->get_message('bad_local_license')), array('status' => 400));
            $key_handler->flush_key_related_info();
            return $error;
        }
    }

    public function db_log_enable_permission_check($request) {
        if (current_user_can('manage_options')) {
            return true;
        }
    }

    public function log_generate($request) {

        /***************************************
         * Checks
         ***************************************/

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /*************************************
         * Generate
         ************************************/

        $logger = new User_Log_CSV();
        $path_to_file = $logger->expost_log_CSV("userlog");

        if (empty($path_to_file)) {
            $error->add("generator_error", __('Generator Error'), array('status' => 401));
            return $error;
        }

        $response['code'] = "report_generated";
        $response['url'] = $path_to_file;
        $response['message'] =  __("Your report is ready",  "terms-popup-on-user-login");

        return new WP_REST_Response($response, 200);
    }


    /**
     * Generate user report
     */
    public function report_generate($request) {

        /***************************************
         * Checks
         ***************************************/

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /*************************************
         * Generate
         ************************************/

        $logger = new User_Log_CSV();
        $path_to_file = $logger->expost_log_CSV("report");

        if (empty($path_to_file)) {
            $error->add("generator_error", __('Generator Error'), array('status' => 401));
            return $error;
        }

        $response['code'] = "report_generated";
        $response['url'] = $path_to_file;
        $response['message'] =  __("Your report is ready.",  "terms-popup-on-user-login");

        return new WP_REST_Response($response, 200);
    }

    /**
     * Turn on loggin on or off
     */
    public function db_log($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /**
         * Do the stuff
         */

        $logging = get_option('tpul_addv_logging');
        // Toggle the value
        $logging = $logging ? FALSE : TRUE;
        update_site_option('tpul_addv_logging', $logging);

        if ($logging) {
            $primary_key = termspul\Tpul_DB::$primary_key;
            $create_log_table = termspul\Tpul_DB::create_log_table();
        }

        $response['create_log_table'] = $create_log_table;
        $response['code'] = "log";
        $response['tpul_addv_logging'] = $logging;

        return new WP_REST_Response($response, 200);
    }


    public function db_log_purge($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /**
         * Do the stuff
         */

        $parameters = $request->get_json_params();
        $date = $parameters['date'];

        // invalid date
        if (!(bool)strtotime($date)) {
            $error->add("invalid_date", __('Invalid date'), array('status' => 401));
            return $error;
        }

        $date2 = $this->convertToMysqlDate($date, 'Y-m-d');

        $table = termspul\Tpul_DB::purge_older_than($date);


        // $response['table'] = $table;
        $response['params'] = $parameters;
        $response['date'] = $date;
        $response['message'] = __('Affected rows :', 'terms-popup-on-user-login') . $table;
        $response['table'] = $table;

        return new WP_REST_Response($response, 200);
    }




    public function email_test($request) {

        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /**
         * Do the stuff
         */

        $email_sender = new TPUL_EmailSender();
        $email_sender->send_test_email();

        $response['message'] = __('Email Sent', 'terms-popup-on-user-login');

        return new WP_REST_Response($response, 200);
    }

    public function db_log_purge_permission_check($request) {
        return true;
    }

    public function report_generate_permission_check($request) {
        return true;
    }

    public function log_generate_permission_check($request) {
        return true;
    }

    public function email_test_permission_check($request) {
        if (current_user_can('manage_options')) {
            return true;
        }
    }

    public function deactivatekey_permission_check($request) {
        return true;
    }

    public function deactivatekey($request) {
        $error = new WP_Error();
        $response = array();

        /**
         * Check if user is not logged in
         */
        // $user_id = get_current_user_id();
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;

        // $user_id = $user->ID;
        if ($user_id == 0) {
            $error->add("no_such_user", __('No such user 0'), array('status' => 401));
            return $error;
        }

        /**
         * Check Admin Referrer, make sure this is called by and Admin
         */
        check_admin_referer();

        /**
         * Check if nonce is bad
         */
        if (rest_cookie_check_errors($request)) {
            // Nonce is correct!
            $response['data'] = array('nonce' => 'correct');
        } else {
            // Don't send the data, it's a trap!
            $error->add("no_such_user", __('No such user'), array('status' => 401));
            return $error;
        }

        /**
         * Init Handler
         */
        $key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $license_key = $key_handler->get_license_key();

        /**
         * Check if local license is present
         * this will not use the lciense key but rather a local license key file provided
         * this handles special use cases where the site can not reach lehelmatyus.com
         * to activa the key online
         * 
         * see /terms-popup-on-user-login/local-license/readme.txt
         * 
         */
        $pluginsDirectoryPath = WP_PLUGIN_DIR;
        $licenseFilePath = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/license.pem';
        $publicKeyPath  = $pluginsDirectoryPath . '/terms-popup-on-user-login/local-license/public_key.pem';
        if (file_exists($licenseFilePath) && file_exists($publicKeyPath)) {
            $key_handler->flush_key_related_info();
            $response['code'] = "deactivated";
            $response['message'] = __($key_handler->get_message('deactivated'), "terms-popup-on-user-login") . "";
            return new WP_REST_Response($response, 200);
        }

        /**
         * Deactivate License
         */

        // Decode response from activator.
        $_com_response = $key_handler->_comm__deactivate_key($license_key);

        if (empty($_com_response)) {
            $error->add("empty_response", __($key_handler->get_message('empty_response')), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }

        $response['_com_response'] = $_com_response;

        /**
         * If License Key manager says there is no such key
         */
        if (!empty($_com_response->data->status) && $_com_response->data->status == 404) {
            $error->add("key_not_good", __($key_handler->get_message('key_not_good') . ' ' . $_com_response->message), array('status' => 404));
            $key_handler->flush_key_related_info();
            return $error;
        }

        /**
         * Test For Success
         */
        if (
            !empty($_com_response->success) &&
            ($_com_response->success == true)
        ) {
            $key_handler->flush_key_related_info();
            $response['code'] = "deactivated";
            $response['message'] = __($key_handler->get_message('deactivated'), "terms-popup-on-user-login") . "";
            return new WP_REST_Response($response, 200);
        }


        /**
         * Was not able to deactivate
         */
        $error->add("unable_to_deactivate", __($key_handler->get_message('unable_to_deactivate') . " " . $_com_response->message), array('status' => 404));
        $key_handler->flush_key_related_info();
        return $error;
    }


    public function logout_go_home() {
        wp_logout();
        ob_clean();
        // wp_redirect( home_url() );
        // exit();
    }

    public function convertToMysqlDate($mydate, $dtformat) {
        $dt = new \DateTime();
        $date = $dt->createFromFormat($dtformat, $mydate);
        $convertdt = $date->format('Y-m-d');
        return $convertdt;
    }
}
