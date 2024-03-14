<?php

namespace MailOptin\SalesforceConnect;

class ConnectSettingsPage extends AbstractSalesforceConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));

        add_filter('wp_cspa_santized_data', [$this, 'remove_access_token_persistence'], 10, 2);
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);

        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_integration_disconnection']);
    }

    /**
     * @param array $arg
     *
     * @return array
     */
    public function connection_settings($arg)
    {
        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=salesforce';

            $settingsArg[] = [
                'section_title'          => __('Salesforce', 'mailoptin'),
                'type'                   => self::EMAIL_MARKETING_TYPE,
                'salesforce_instruction' => [
                    'type' => 'arbitrary',
                    'data' => sprintf(
                        '<p style="text-align:center;font-size: 15px;" class="description">%s</p><div class="moBtncontainer"><a target="_blank" href="%s" style="padding:0;margin: 0 auto;" class="mobutton mobtnPush mobtnGreen">%s</a></div>',
                        __('Salesforce integration is not available on your plan. Upgrade to any of our premium offerings to get it.', 'mailoptin'),
                        $url,
                        __('Upgrade Now!', 'mailoptin')
                    )
                ],
                'disable_submit_button'  => true
            ];

            return array_merge($arg, $settingsArg);
        }

        if (self::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $disconnect_integration = sprintf(
            '<div style="text-align:center;font-size:14px;margin-top:20px;"><a class="button" onclick="return confirm(\'%s\')" href="%s">%s</a></div>',
            __('Are you sure you want to disconnect?', 'mailoptin'),
            wp_nonce_url(
                add_query_arg('mo-integration-disconnect', 'salesforce', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE),
                'mo_disconnect_integration'
            ),
            __('Disconnect Integration', 'mailoptin')
        );

        $html = '<div style="font-weight: 500;line-height: 1.5;margin: 20px 0;">' . sprintf(esc_html__('An application must be created with Salesforce to get your API Key and App Secret. %sLearn more%s', 'mailoptin'), '<a target="_blank" href="https://mailoptin.io/article/how-to-connect-salesforce-to-wordpress/">', '</a>') . '</div>';
        $html .= '<ol>';
        $html .= '<li>' . esc_html__('In Salesforce, go to Setup -> App -> App Manager and click on "New Connected App".', 'mailoptin') . '</li>';
        $html .= '<li>' . esc_html__('Enter Application Name(eg. My App), email address then check "Enable OAuth Settings" checkbox.', 'mailoptin') . '</li>';
        $html .= '<li>' . sprintf(__('Enter %s as the Callback URL.', 'mailoptin'), '<code>' . self::callback_url() . '</code>') . '</li>';
        $html .= '<li>' . sprintf(__('Select %s and %s as OAuth Scopes then Save to create the application.', 'mailoptin'), '<code>Manage user data via APIs (api)</code>', '<code>Perform requests at any time (refresh_token, offline_access)</code>') . '</li>';
        $html .= '<li>' . esc_html__('Copy the Consumer Key and Secret of the app and save them here.', 'mailoptin') . '</li>';
        $html .= '</ol>';

        $settingsArg = array(
            'section_title_without_status' => __('Salesforce', 'mailoptin'),
            'section_title'                => __('Salesforce Connection', 'mailoptin') . " $status",
            'type'                         => self::EMAIL_MARKETING_TYPE,
            'salesforce_instruction'       => array(
                'type' => 'arbitrary',
                'data' => $html
            ),
            'salesforce_consumer_key'      => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Consumer Key', 'mailoptin')
            ),
            'salesforce_consumer_secret'   => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Consumer Secret', 'mailoptin')
            ),
            'salesforce_auth_connect'      => array(
                'type' => 'arbitrary',
                'data' => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>',
                    self::callback_url(),
                    'mobtnPurple',
                    __('AUTHORIZE YOUR ACCOUNT', 'mailoptin')
                )
            ),
            'salesforce_auth_disconnect'   => array(
                'type' => 'arbitrary',
                'data' => $disconnect_integration
            )
        );

        if (self::is_connected()) {
            $settingsArg['salesforce_instruction']['data'] = '';
        }

        if (( ! self::is_connected() && ! self::is_api_saved()) || self::is_connected()) {
            unset($settingsArg['salesforce_auth_connect']);
        }

        if (self::is_api_saved()) {
            unset($settingsArg['salesforce_consumer_key']);
            unset($settingsArg['salesforce_consumer_secret']);
            $settingsArg['disable_submit_button'] = true;
        } else {
            unset($settingsArg['salesforce_auth_disconnect']);
        }

        return array_merge($arg, [$settingsArg]);
    }

    /**
     * Prevent access token from being overridden when settings page is saved.
     *
     * @param array $sanitized_data
     * @param string $option_name
     *
     * @return mixed
     */
    public function remove_access_token_persistence($sanitized_data, $option_name)
    {
        // remove the access token from being overridden on save of settings.
        if ($option_name == MAILOPTIN_CONNECTIONS_DB_OPTION_NAME) {
            unset($sanitized_data['salesforce_access_token']);
            unset($sanitized_data['salesforce_refresh_token']);
        }

        return $sanitized_data;
    }

    public function handle_integration_disconnection($option_name)
    {
        if ( ! isset($_GET['mo-integration-disconnect']) || $_GET['mo-integration-disconnect'] != 'salesforce' || ! check_admin_referer('mo_disconnect_integration')) return;

        $old_data = get_option($option_name, []);
        unset($old_data['salesforce_consumer_key']);
        unset($old_data['salesforce_consumer_secret']);
        unset($old_data['salesforce_access_token']);
        unset($old_data['salesforce_refresh_token']);

        update_option($option_name, $old_data);

        $connection = Connect::$connectionName;

        // delete connection cache
        delete_transient("_mo_connection_cache_$connection");

        wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
    }

    public function output_error_log_link($option, $args)
    {
        //Not a salesforce connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['salesforce_instruction'])) {
            return;
        }

        echo self::get_optin_error_log_link('salesforce');
    }

    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
