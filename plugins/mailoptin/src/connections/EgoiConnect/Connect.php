<?php

namespace MailOptin\EgoiConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractEgoiConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'EgoiConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_filter('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        parent::__construct();
    }

    public static function features_support()
    {
        return [
            self::OPTIN_CAMPAIGN_SUPPORT,
            self::EMAIL_CAMPAIGN_SUPPORT,
            self::OPTIN_CUSTOM_FIELD_SUPPORT
        ];
    }

    /**
     * Fetches tags.
     *
     * @return mixed
     */
    public function get_tags()
    {
        $tag_array = [];

        if (self::is_connected()) {

            try {

                $cache_key = 'egoi_tags';

                $tag_array = get_transient($cache_key);

                if (empty($tag_array) || false === $tag_array) {

                    $response = $this->egoi_instance()->make_request('tags');

                    if (self::is_http_code_success($response['status_code'])) {

                        $tags = $response['body']['items'] ?? [];

                        foreach ($tags as $tag) {
                            $tag_array[$tag['tag_id']] = $tag['name'];
                        }

                        set_transient($cache_key, $tag_array, 10 * MINUTE_IN_SECONDS);

                    } else {
                        throw new \Exception(is_string($response['body']) ? $response['body'] : wp_json_encode($response['body']));
                    }
                }

            } catch (\Exception $e) {
                self::save_optin_error_log($e->getMessage(), 'egoi');
            }
        }

        return $tag_array;
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('E-Goi', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['EgoiConnect_enable_double_optin'] = false;

        $settings['EgoiConnect_subscriber_tags'] = [];

        return $settings;
    }

    /**
     * @param array $controls
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {
            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'field'       => 'chosen_select',
                'name'        => 'EgoiConnect_subscriber_tags',
                'choices'     => $this->get_tags(),
                'label'       => __('Subscriber Tags', 'mailoptin'),
                'description' => __('Select E-goi tags that will be assigned to subscribers.', 'mailoptin')
            ];

            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'EgoiConnect_enable_double_optin',
                'label'       => __('Enable Double Optin', 'mailoptin'),
                'description' => __("Double optin requires users to confirm their email address before they are added or subscribed (recommended).", 'mailoptin')
            ];

        } else {

            $content = sprintf(
                __("%sMailOptin Premium%s allows you to enable double-optin and apply tags to subscribers.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=egoi_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'name'    => 'EgoiConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    /**
     * Replace placeholder tags with actual E-Goi tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = ['{{unsubscribe}}'];

        $replace = ['!remove'];

        $content = str_replace($search, $replace, $content);

        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * {@inherit_doc}
     *
     * Return array of email list
     *
     * @return mixed
     */
    public function get_email_list()
    {
        $lists_array = [];

        try {

            $response = $this->egoi_instance()->make_request('lists', ['limit' => 100]);

            if (is_array($response['body']['items'])) {

                foreach ($response['body']['items'] as $list) {
                    $lists_array[$list['list_id']] = $list['public_name'];
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'egoi');
        }

        return $lists_array;
    }

    public function get_optin_fields($list_id = '')
    {
        $custom_fields_array = [];

        try {

            $response = $this->egoi_instance()->make_request("lists/$list_id/fields");

            if (isset($response['body']) && ! empty($response['body'])) {

                foreach ($response['body'] as $customField) {

                    if ($customField['editable'] === false) continue;

                    $fieldID = $customField['field_id'];

                    if (in_array($fieldID, ['first_name', 'last_name', 'email', 'subscription_status', 'ios_oken', 'ios_token', 'android_token'])) continue;

                    $custom_fields_array[$fieldID] = $customField['name'];
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'egoi');
        }

        return $custom_fields_array;
    }

    /**
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     * @param string $subject
     * @param string $content_html
     * @param string $content_text
     *
     * @return array
     * @throws \Exception
     *
     */
    public function send_newsletter($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text)
    {
        return (new SendCampaign($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text))->send();
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $list_id ID of email list to add subscriber to
     * @param mixed|null $extras
     *
     * @return mixed
     */
    public function subscribe($email, $name, $list_id, $extras = null)
    {
        return (new Subscription($email, $name, $list_id, $extras))->subscribe();
    }

    /**
     * @return Connect
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}