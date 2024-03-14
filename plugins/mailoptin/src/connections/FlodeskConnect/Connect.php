<?php

namespace MailOptin\FlodeskConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractFlodeskConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'FlodeskConnect';

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
     * Register Flodesk Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Flodesk', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['FlodeskConnect_enable_double_optin'] = false;

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

            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'FlodeskConnect_enable_double_optin',
                'label'       => __('Enable Double Optin', 'mailoptin'),
                'description' => __("Double optin requires users to confirm their email address before they are added or subscribed (recommended).", 'mailoptin')
            ];

        } else {

            $content = sprintf(
                __("%sMailOptin Premium%s allows you enable double-optin.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=flodesk_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'name'    => 'FlodeskConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    /**
     * Replace placeholder tags with actual Flodesk merge tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
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
        $bucket = [];

        try {

            $response = $this->flodesk_instance()->make_request('segments', ['per_page' => 100]);

            if (isset($response['body']->data) && is_array($response['body']->data)) {

                foreach ($response['body']->data as $segment) {
                    $bucket[$segment->id] = $segment->name;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'flodesk');
        }

        return $bucket;
    }

    public function get_optin_fields($list_id = '')
    {
        $custom_fields_array = [];

        try {

            $response = $this->flodesk_instance()->make_request('custom-fields');

            if (isset($response['body']->data) && is_array($response['body']->data)) {

                foreach ($response['body']->data as $customField) {

                    $custom_fields_array[$customField->key] = $customField->label;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'flodesk');
        }

        return $custom_fields_array;
    }

    /**
     *
     * {@inheritdoc}
     *
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
        return [];
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
     * @return Connect|null
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