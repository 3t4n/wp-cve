<?php

namespace MailOptin\Ctctv3Connect;

class ConnectSettingsPage extends AbstractCtctv3Connect
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
        if (self::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $disconnect_integration = sprintf(
            '<div style="text-align:center;font-size:14px;margin-top:20px;"><a class="button" onclick="return confirm(\'%s\')" href="%s">%s</a></div>',
            __('Are you sure you want to disconnect?', 'mailoptin'),
            wp_nonce_url(
                add_query_arg('mo-integration-disconnect', 'constantcontactv3', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE),
                'mo_disconnect_integration'
            ),
            __('Disconnect Integration', 'mailoptin')
        );

        $html = '<div style="font-weight: 500;line-height: 1.5;margin: 20px 0;">' . esc_html__('An application must be created with Constant Contact to get your API Key and App Secret. The app should be dedicated to this website, please do not use the same app with multiple sites.', 'mailoptin') . '</div>';
        $html .= '<ol>';
        $html .= '<li>' . sprintf(esc_html__('Login to the %1$sConstant Contact V3 Portal%2$s and create a "New Application".', 'mailoptin'), '<a href="https://app.constantcontact.com/pages/dma/portal" target="_blank">', '</a>') . '</li>';
        $html .= '<li>' . esc_html__('Enter "MailOptin" as the application name and click "Save".', 'mailoptin') . '</li>';
        $html .= '<li>' . esc_html__('Copy your Constant Contact API Key and paste it into the API Key field below.', 'mailoptin') . '</li>';
        $html .= '<li>' . esc_html__('Click the "Generate Secret" button, go through the secret generation process and paste the resulted key in the "App Secret" field below.', 'mailoptin') . '</li>';
        $html .= '<li>' . sprintf(esc_html__('Paste the URL %s into the Redirect URI field and click "Save" in the top right corner of the screen.', 'mailoptin'), '<strong>' . self::callback_url() . '</strong>') . '</li>';
        $html .= '<li>' . sprintf(esc_html__('After saving, click the Authorize button that will appear to connect your account. %sLearn more%s', 'mailoptin'), '<a href="https://mailoptin.io/article/connect-mailoptin-with-constant-contact/#v3" target="_blank">', '</a>') . '</li>';
        $html .= '</ol>';

        $settingsArg = array(
            'section_title_without_status' => __('Constant Contact', 'mailoptin'),
            'section_title'                => __('Constant Contact Connection', 'mailoptin') . " $status",
            'type'                         => self::EMAIL_MARKETING_TYPE,
            'ctctv3_auth'                  => array(
                'type' => 'arbitrary',
                'data' => '<p class="description" style="text-align:center">' . esc_html__('The major improvement in this new Constant Contact integration is the support for custom fields.', 'mailoptin') . '</p>'
            ),
            'ctctv3_instruction'           => array(
                'type' => 'arbitrary',
                'data' => $html
            ),
            'ctctv3_api_key'               => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Key', 'mailoptin')
            ),
            'ctctv3_api_secret'            => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Secret', 'mailoptin')
            ),
            'ctctv3_auth_connect'          => array(
                'type' => 'arbitrary',
                'data' => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>',
                    self::callback_url(),
                    'mobtnPurple',
                    __('AUTHORIZE YOUR ACCOUNT', 'mailoptin')
                ),
            ),
            'ctctv3_auth_disconnect'       => array(
                'type' => 'arbitrary',
                'data' => $disconnect_integration
            )
        );

        if (self::is_connected()) {
            unset($settingsArg['ctctv3_instruction']);
        }

        if (( ! self::is_connected() && ! self::is_api_saved()) || self::is_connected()) {
            unset($settingsArg['ctctv3_auth_connect']);
        }

        if (self::is_api_saved()) {
            unset($settingsArg['ctctv3_api_key']);
            unset($settingsArg['ctctv3_api_secret']);
            $settingsArg['disable_submit_button'] = true;
        } else {
            unset($settingsArg['ctctv3_auth_disconnect']);
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
            unset($sanitized_data['ctctv3_access_token']);
            unset($sanitized_data['ctctv3_refresh_token']);
            unset($sanitized_data['ctctv3_expires_at']);
            unset($sanitized_data['ctctv3_date_created']);
        }

        return $sanitized_data;
    }

    public function handle_integration_disconnection($option_name)
    {
        if ( ! isset($_GET['mo-integration-disconnect']) || $_GET['mo-integration-disconnect'] != 'constantcontactv3' || ! check_admin_referer('mo_disconnect_integration')) return;

        $old_data = get_option($option_name, []);
        unset($old_data['ctctv3_api_key']);
        unset($old_data['ctctv3_api_secret']);
        unset($old_data['ctctv3_access_token']);
        unset($old_data['ctctv3_refresh_token']);
        unset($old_data['ctctv3_expires_at']);
        unset($old_data['ctctv3_date_created']);

        update_option($option_name, $old_data);

        $connection = Connect::$connectionName;

        // delete connection cache
        delete_transient("_mo_connection_cache_$connection");

        wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
    }

    public function output_error_log_link($option, $args)
    {
        //Not a ctctv3 connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['ctctv3_auth'])) {
            return;
        }

        echo self::get_optin_error_log_link('constantcontactv3');
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
