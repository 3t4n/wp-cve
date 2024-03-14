<?php

defined( 'ABSPATH' ) || exit;

/**
 * Auth class.
 */
class MyPOS_Auth {

    /**
     * Setup class.
     */
    public function __construct() {
        // Add query vars.
        add_filter('query_vars', array($this, 'add_query_vars'), 0);

        // Register auth endpoint.
        add_action('init', array(__CLASS__, 'add_endpoint'), 0);

        // Handle auth requests.
        add_action('parse_request', array($this, 'handle_auth_requests'), 0);
    }

    /**
     * Add query vars.
     *
     * @param array $vars Query variables.
     * @return string[]
     */
    public function add_query_vars(array $vars)
    {
        $vars[] = 'mp-auth-route';
        return $vars;
    }

    /**
     * Add auth endpoint.
     */
    public static function add_endpoint()
    {
        add_rewrite_rule( '^mp-auth/(.*)?', 'index.php?mp-auth-route=$matches[1]', 'top' );
    }

    /**
     * Handle auth requests.
     *
     * @throws Exception When auth_endpoint validation fails.
     */
    public function handle_auth_requests()
    {
        global $wp;

        if (!empty($_GET['mp-auth-route'])) {
            $wp->query_vars['mp-auth-route'] = mypos_clean(wp_unslash($_GET['mp-auth-route']));
        }

        // mp-auth endpoint requests.
        if (!empty($wp->query_vars['mp-auth-route'])) {
            $this->auth_endpoint( $wp->query_vars['mp-auth-route'] );
        }
    }

    /**
     * Build auth urls.
     *
     * @param array $data     Data to build URL.
     * @param string $endpoint Endpoint.
     * @return string
     */
    protected function build_url( array $data, string $endpoint )
    {
        $url = mypos_get_endpoint_url('mp-auth', $endpoint, home_url( '/' ));

        return add_query_arg(
            array(
                'return_url'   => rawurlencode($this->get_formatted_url($data['return_url'])),
                'success_url'   => rawurlencode($this->get_formatted_url($data['success_url'])),
                'store_name' => mypos_clean($data['store_name']),
                'developer_package'     => mypos_clean($data['developer_package']),
            ), $url
        );
    }

    /**
     * Decode and format a URL.
     *
     * @param string $url URL.
     * @return string
     */
    protected function get_formatted_url($url)
    {
        $url = urldecode($url);

        if (!strstr($url, '://')) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    /**
     * Make validation.
     *
     * @throws Exception When validate fails.
     */
    protected function make_validation()
    {
        $data   = [];
        $params = [
            'return_url',
            'success_url',
            'store_name',
            'developer_package',
        ];

        foreach ($params as $param) {
            if (empty($_REQUEST[$param])) { // WPCS: input var ok, CSRF ok.
                /* translators: %s: parameter */
                throw new RuntimeException(sprintf(__( 'Missing parameter %s', 'mypos' ), $param));
            }

            $data[$param] = wp_unslash($_REQUEST[$param]); // WPCS: input var ok, CSRF ok, sanitization ok.
        }

        foreach (['return_url', 'success_url'] as $param) {
            $param = $this->get_formatted_url($data[$param]);

            if (false === filter_var($param, FILTER_VALIDATE_URL)) {
                /* translators: %s: url */
                throw new RuntimeException(sprintf(__('The %s is not a valid URL', 'mypos'), $param));
            }
        }
    }

    /**
     * Update Store Configuration Options.
     *
     * @param $developerPackage
     * @return bool
     * @throws Exception
     */
    protected function update_options($developerPackage)
    {
        $newOptions = '';

        if (($oldOptions = get_option('woocommerce_mypos_virtual_settings')) !== false) {
            $newOptions = $oldOptions;

            $newOptions['test'] = 'no';
            $newOptions['production_package'] = $developerPackage;
        }

        if (get_option('woocommerce_mypos_virtual_settings') !== $newOptions &&
            false === update_option('woocommerce_mypos_virtual_settings', $newOptions)) {
            throw new RuntimeException(__('Could not make an update', 'mypos'));
        }

        return true;
    }

    /**
     * Auth endpoint.
     *
     * @param string $route Route.
     * @throws Exception When validation fails.
     */
    protected function auth_endpoint(string $route)
    {
        ob_start();

        try {
            $route = strtolower($route);

            $this->make_validation();

            $data = wp_unslash($_REQUEST);

            // Login endpoint.
            if ('login' === $route && !is_user_logged_in()) {
                mypos_get_template(
                    'auth/form-login.php', array(
                        'return_url' => $this->get_formatted_url($data['return_url']),
                        'redirect_url' => $this->build_url($data, 'authorize'),
                        'store_name' => mypos_clean($data['store_name']),
                    )
                );
                exit;

            }

            if ('login' === $route && is_user_logged_in()) {
                // Redirect with user is logged in.
                wp_redirect(esc_url_raw($this->build_url($data, 'authorize')));
                exit;

            } elseif ('authorize' === $route && !is_user_logged_in()) {
                // Redirect with user is not logged in and trying to access the authorize endpoint.
                wp_redirect(esc_url_raw($this->build_url($data, 'login')));
                exit;

            } elseif ('authorize' === $route && current_user_can('manage_woocommerce')) {
                // Authorize endpoint.
                mypos_get_template(
                    'auth/form-grant-access.php', array(
                        'store_name' => mypos_clean($data['store_name']),
                        'return_url' => $this->get_formatted_url($data['return_url']),
                        'granted_url' => wp_nonce_url($this->build_url($data, 'access_granted'), 'mp_auth_grant_access', 'mp_auth_nonce'),
                        'logout_url' => wp_logout_url($this->build_url($data, 'login')),
                        'user' => wp_get_current_user(),
                    )
                );
                exit;

            } elseif ('access_granted' === $route && current_user_can('manage_woocommerce')) {
                // Granted access endpoint.
                if (!isset($_GET['mp_auth_nonce']) || !wp_verify_nonce(sanitize_key(wp_unslash($_GET['mp_auth_nonce'])), 'mp_auth_grant_access')) { // WPCS: input var ok.
                    throw new Exception(__('Invalid nonce verification', 'mypos'));
                }

                if ($this->update_options($data['developer_package'])) {
                    wp_redirect(
                        esc_url_raw(
                           $this->get_formatted_url($data['success_url'])
                        )
                    );
                    exit;
                }
            } else {
                throw new RuntimeException(__('You do not have permission to access this page', 'mypos'));
            }
        } catch (Exception $e) {
            /* translators: %s: error message */
            wp_die(sprintf(esc_html__('Error: %s.', 'mypos'), esc_html($e->getMessage())), esc_html__('Access denied', 'mypos'), array('response' => 401));
        }
    }
}
new MyPOS_Auth();
