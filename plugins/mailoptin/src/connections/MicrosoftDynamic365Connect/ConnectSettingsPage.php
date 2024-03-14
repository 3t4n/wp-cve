<?php

namespace MailOptin\MicrosoftDynamic365Connect;

class ConnectSettingsPage extends AbstractMicrosoftDynamic365Connect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));

        add_filter('wp_cspa_santized_data', [$this, 'remove_access_token_persistence'], 10, 2);
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);

        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_access_token_persistence']);
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
            $url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=microsoftdynamic365';

            $settingsArg[] = [
                'section_title'                   => __('Microsoft Dynamics 365', 'mailoptin'),
                'type'                            => self::EMAIL_MARKETING_TYPE,
                'microsoftdynamic365_instruction' => [
                    'type' => 'arbitrary',
                    'data' => sprintf(
                        '<p style="text-align:center;font-size: 15px;" class="description">%s</p><div class="moBtncontainer"><a target="_blank" href="%s" style="padding:0;margin: 0 auto;" class="mobutton mobtnPush mobtnGreen">%s</a></div>',
                        __('Microsoft Dynamics 365 integration is not available on your plan. Upgrade to any of our premium offerings to get it.', 'mailoptin'),
                        $url,
                        __('Upgrade Now!', 'mailoptin')
                    )
                ],
                'disable_submit_button'           => true
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
                add_query_arg('mo-integration-disconnect', 'microsoftdynamic365', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE),
                'mo_disconnect_integration'
            ),
            __('Disconnect Integration', 'mailoptin')
        );

        $html = '<div style="font-weight: 500;line-height: 1.5;margin: 20px 0;">' . sprintf(esc_html__('An application must be created with Microsoft Dynamics 365 to get your Client ID and Client Secret. %sLearn more%s', 'mailoptin'), '<a target="_blank" href="https://mailoptin.io/article/how-to-connect-microsoft-dynamics-365-to-wordpress/">', '</a>') . '</div>';
        $html .= '<ol>';
        $html .= '<li>' . sprintf(esc_html__('Login to %1$sMicrosoft Azure Portal%2$s, go to %3$sApp Registration%2$s and click on "New Registration".', 'mailoptin'), '<a target="_blank" href="https://portal.azure.com">', '</a>', '<a target="_blank" href="https://portal.azure.com/#view/Microsoft_AAD_RegisteredApps/ApplicationsListBlade">') . '</li>';
        $html .= '<li>' . esc_html__('Enter a name, select "Accounts in any organizational directory and personal Microsoft accounts" as Supported account types.', 'mailoptin') . '</li>';
        $html .= '<li>' . sprintf(__('Under "Redirect URI", select "Web" as platform, enter %s as the URL and click the Register button to create the app.', 'mailoptin'), '<code>' . MAILOPTIN_OAUTH_URL . "/microsoftdynamic/" . '</code>') . '</li>';
        $html .= '<li>' . esc_html__('Go to "Certificates & secrets", click on "New secret". Enter a description and expiry duration and save to generate your app secret value.', 'mailoptin') . '</li>';
        $html .= '<li>' . sprintf(__('Go to "API permissions", click on "Add a permission". Select "Dynamics CRM", check "user_impersonation" and save.', 'mailoptin'), '<code>Manage user data via APIs (api)</code>', '<code>Perform requests at any time (refresh_token, offline_access)</code>') . '</li>';
        $html .= '<li>' . esc_html__('Copy the Application (client) ID and Secret value of the app and save them here together with your Dynamic CRM URL.', 'mailoptin') . '</li>';
        $html .= '</ol>';

        $settingsArg = array(
            'section_title_without_status'        => __('Microsoft Dynamics 365', 'mailoptin'),
            'section_title'                       => __('Microsoft Dynamics 365 Connection', 'mailoptin') . " $status",
            'type'                                => self::EMAIL_MARKETING_TYPE,
            'microsoftdynamic365_instruction'     => array(
                'type' => 'arbitrary',
                'data' => $html
            ),
            'microsoftdynamic365_org_url'         => array(
                'type'        => 'text',
                'placeholder' => 'https://contoso.crm.dynamics.com',
                'label'       => __('Dynamics 365 URL', 'mailoptin')
            ),
            'microsoftdynamic365_client_id'       => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Client ID', 'mailoptin')
            ),
            'microsoftdynamic365_client_secret'   => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Client Secret', 'mailoptin')
            ),
            'microsoftdynamic365_auth_connect'    => array(
                'type' => 'arbitrary',
                'data' => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>',
                    $this->callback_url(),
                    'mobtnPurple',
                    __('AUTHORIZE YOUR ACCOUNT', 'mailoptin')
                )
            ),
            'microsoftdynamic365_auth_disconnect' => array(
                'type' => 'arbitrary',
                'data' => $disconnect_integration
            )
        );

        if (self::is_connected()) {
            $settingsArg['microsoftdynamic365_instruction']['data'] = '';
        }

        if (( ! self::is_connected() && ! self::is_api_saved()) || self::is_connected()) {
            unset($settingsArg['microsoftdynamic365_auth_connect']);
        }

        if (self::is_api_saved()) {
            unset($settingsArg['microsoftdynamic365_org_url']);
            unset($settingsArg['microsoftdynamic365_client_id']);
            unset($settingsArg['microsoftdynamic365_client_secret']);
            $settingsArg['disable_submit_button'] = true;
        } else {
            unset($settingsArg['microsoftdynamic365_auth_disconnect']);
        }

        return array_merge($arg, [$settingsArg]);
    }

    /**
     * Persist access token.
     *
     * @param string $option_name DB wp_option key for saving connection settings.
     */
    public function handle_access_token_persistence($option_name)
    {
        if ( ! empty($_GET['mo-save-oauth-provider']) && $_GET['mo-save-oauth-provider'] == 'microsoftdynamic' && ! empty($_GET['access_token'])) {

            check_admin_referer('mo_save_oauth_credentials', 'moconnect_nonce');

            $old_data   = get_option($option_name, []);
            $expires_at = $this->oauth_expires_at_transform($_GET['expires_at']);
            $new_data   = array_map('rawurldecode', [
                'microsoftdynamic365_access_token'  => $_GET['access_token'],
                'microsoftdynamic365_refresh_token' => $_GET['refresh_token'],
                'microsoftdynamic365_expires_at'    => $expires_at
            ]);

            $new_data = array_filter($new_data, [$this, 'data_filter']);

            update_option($option_name, array_merge($old_data, $new_data));

            $connection = Connect::$connectionName;

            // delete connection cache
            delete_transient("_mo_connection_cache_$connection");

            wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
            exit;
        }
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
            unset($sanitized_data['microsoftdynamic365_access_token']);
            unset($sanitized_data['microsoftdynamic365_refresh_token']);
            unset($sanitized_data['microsoftdynamic365_expires_at']);
        }

        return $sanitized_data;
    }

    public function handle_integration_disconnection($option_name)
    {
        if ( ! isset($_GET['mo-integration-disconnect']) || $_GET['mo-integration-disconnect'] != 'microsoftdynamic365' || ! check_admin_referer('mo_disconnect_integration')) return;

        $old_data = get_option($option_name, []);
        unset($old_data['microsoftdynamic365_org_url']);
        unset($old_data['microsoftdynamic365_client_id']);
        unset($old_data['microsoftdynamic365_client_secret']);
        unset($old_data['microsoftdynamic365_access_token']);
        unset($old_data['microsoftdynamic365_refresh_token']);

        update_option($option_name, $old_data);

        $connection = Connect::$connectionName;

        // delete connection cache
        delete_transient("_mo_connection_cache_$connection");

        wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
    }

    public function output_error_log_link($option, $args)
    {
        //Not a microsoftdynamic365 connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['microsoftdynamic365_instruction'])) {
            return;
        }

        echo self::get_optin_error_log_link('microsoftdynamic365');
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
