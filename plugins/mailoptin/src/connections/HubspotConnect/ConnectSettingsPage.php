<?php

namespace MailOptin\HubspotConnect;

use function MailOptin\Core\moVar;

class ConnectSettingsPage extends AbstractHubspotConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));

        add_filter('wp_cspa_santized_data', [$this, 'remove_access_token_persistence'], 10, 2);
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);

        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_access_token_persistence']);
        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_integration_disconnection']);

        add_action('admin_init', [$this, 'clear_connection_cache']);
    }

    /**
     * Build the settings metabox for hubspot
     *
     * @param array $arg
     *
     * @return array
     */
    public function connection_settings($arg)
    {
        $disconnect_integration = '';
        if (self::is_connected()) {
            $status                 = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
            $button_text            = __('RE-AUTHORIZE', 'mailoptin');
            $button_color           = 'mobtnGreen';
            $description            = sprintf(__('Only re-authorize if you want to connect another Hubspot account.', 'mailoptin'));
            $disconnect_integration = sprintf(
                '<div style="text-align:center;font-size:14px;"><a onclick="return confirm(\'%s\')" href="%s">%s</a></div>',
                __('Are you sure you want to disconnect?', 'mailoptin'),
                wp_nonce_url(
                    add_query_arg('mo-integration-disconnect', 'hubspot', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE),
                    'mo_disconnect_integration'
                ),
                __('Disconnect Integration', 'mailoptin')
            );
        } else {
            $status       = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
            $button_text  = __('AUTHORIZE', 'mailoptin');
            $button_color = 'mobtnPurple';
            $description  = sprintf(__('Authorization is required to grant <strong>%s</strong> access to interact with your Hubspot account.', 'mailoptin'), 'MailOptin');
        }

        $settings_config = array(
            'section_title_without_status' => __('HubSpot', 'mailoptin'),
            'section_title'                => __('HubSpot Connection', 'mailoptin') . " $status",
            'type'                         => self::CRM_TYPE,
            'hubspot_auth'                 => array(
                'type'        => 'arbitrary',
                'data'        => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>%s',
                    $this->get_oauth_url('hubspot'),
                    $button_color,
                    $button_text,
                    $disconnect_integration
                ),
                'description' => '<p class="description" style="text-align:center;margin-bottom: 30px">' . $description . '</p>',
            ),
            'hubspot_clear_cache'          => array(
                'type'        => 'arbitrary',
                'data'        => sprintf(
                    '<div class="mo-connection-clear-cache-wrap"><a href="%s" class="button-primary">%s</a></div>',
                    esc_url(wp_nonce_url(add_query_arg('mo-connection-clear-cache', 'hubspot'), 'mo-connection-clear-hubspot-cache')),
                    esc_html__('Clear HubSpot Cache', 'mailoptin')
                ),
                'description' => sprintf(
                    '<p class="description" style="text-align:center">%s</p>',
                    esc_html__("Due to HubSpot's API usage limits, MailOptin stores HubSpot data for a maximum period of one hour. If you added a new list, custom properties or made any changes to them, you might not see it reflected immediately due to this data caching. To manually clear the cache, click the button above.", 'mailoptin')
                ),
            ),
            'disable_submit_button'        => true,
        );

        if ( ! self::is_connected()) {
            unset($settings_config['hubspot_clear_cache']);
        }

        $settingsArg[] = $settings_config;

        return array_merge($arg, $settingsArg);
    }

    public function clear_connection_cache()
    {
        if (moVar($_GET, 'mo-connection-clear-cache') != 'hubspot') return;

        check_admin_referer('mo-connection-clear-hubspot-cache');

        delete_transient('mo_hubspot_get_email_list');
        delete_transient('mo_hubspot_get_owners');
        delete_transient('mo_hubspot_get_optin_fields_');
        delete_transient('mo_hubspot_get_property_options_hs_lead_status');
        delete_transient('mo_hubspot_get_property_options_lifecyclestage');

        wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
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
            unset($sanitized_data['hubspot_access_token']);
            unset($sanitized_data['hubspot_refresh_token']);
            unset($sanitized_data['hubspot_expires_at']);
        }

        return $sanitized_data;
    }

    public function handle_integration_disconnection($option_name)
    {
        if ( ! isset($_GET['mo-integration-disconnect']) || $_GET['mo-integration-disconnect'] != 'hubspot' || ! check_admin_referer('mo_disconnect_integration')) return;

        $old_data = get_option($option_name, []);
        unset($old_data['hubspot_access_token']);
        unset($old_data['hubspot_refresh_token']);
        unset($old_data['hubspot_expires_at']);

        update_option($option_name, $old_data);

        $connection = Connect::$connectionName;

        // delete connection cache
        delete_transient("_mo_connection_cache_$connection");

        wp_safe_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
    }

    /**
     * Persist access token.
     *
     * @param string $option_name DB wp_option key for saving connection settings.
     */
    public function handle_access_token_persistence($option_name)
    {
        if ( ! empty($_GET['mo-save-oauth-provider']) && in_array($_GET['mo-save-oauth-provider'], ['hubspot']) && ! empty($_GET['access_token'])) {

            check_admin_referer('mo_save_oauth_credentials', 'moconnect_nonce');

            $old_data = get_option($option_name, []);

            $new_data = array_map('rawurldecode', [
                'hubspot_access_token'  => $_GET['access_token'],
                'hubspot_refresh_token' => $_GET['refresh_token'],
                'hubspot_expires_at'    => $this->oauth_expires_at_transform($_GET['expires_at'])
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

    public function output_error_log_link($option, $args)
    {
        //Not a hubspot connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['hubspot_auth'])) {
            return;
        }

        //Output error log link if  there is one
        echo self::get_optin_error_log_link('hubspot');

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