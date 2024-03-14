<?php

namespace MailOptin\OmnisendConnect;

use MailOptin\Core\Connections\ConnectionInterface;


class Connect extends AbstractOmnisendConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'OmnisendConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', [$this, 'register_connection']);

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_filter('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        parent::__construct();
    }

    public static function features_support()
    {
        return [
            self::OPTIN_CAMPAIGN_SUPPORT,
            self::OPTIN_CUSTOM_FIELD_SUPPORT
        ];
    }

    /**
     * Register Omnisend Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Omnisend', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['OmnisendConnect_lead_tags']        = '';
        $settings['OmnisendConnect_sendWelcomeEmail'] = '';

        return $settings;
    }

    /**
     * @param $controls
     *
     * @return array
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {
            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'field'       => 'text',
                'name'        => 'OmnisendConnect_lead_tags',
                'label'       => __('Lead Tags', 'mailoptin'),
                'placeholder' => 'tag1, tag2',
                'description' => __('Enter comma-separated list of tags to assign to subscribers who opt-in via this campaign.', 'mailoptin'),
            ];

            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'OmnisendConnect_sendWelcomeEmail',
                'label'       => __('Send Welcome Email', 'mailoptin'),
                'description' => __("Enable to send welcome email (if welcome workflow is turned on)", 'mailoptin'),
            ];

        } else {

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to apply tags to leads, send welcome email and get access to loads of conversion features.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=omnisend_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            $controls[] = [
                'name'    => 'OmnisendConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    /**
     * Fulfill interface contract.
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
        return ['none' => __('All Contacts', 'mailoptin')];
    }

    public function get_optin_fields($list_id = '')
    {
        return [
            'country'     => esc_html__('Country', 'mailoptin'),
            'countryCode' => esc_html__('Country Code', 'mailoptin'),
            'state'       => esc_html__('State', 'mailoptin'),
            'city'        => esc_html__('City', 'mailoptin'),
            'address'     => esc_html__('Address', 'mailoptin'),
            'postalCode'  => esc_html__('Postal or Zip Code', 'mailoptin'),
            'gender'      => esc_html__('Gender', 'mailoptin'),
            'birthdate'   => esc_html__('Birthdate', 'mailoptin'),
        ];
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
     * Singleton poop.
     *
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