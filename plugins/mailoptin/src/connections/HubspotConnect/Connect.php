<?php

namespace MailOptin\HubspotConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractHubspotConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'HubspotConnect';

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
            self::OPTIN_CUSTOM_FIELD_SUPPORT
        ];
    }

    /**
     * Register Hubspot Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('HubSpot', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['HubspotConnect_lead_status']      = apply_filters('mailoptin_customizer_optin_campaign_HubspotConnect_lead_status', '');
        $settings['HubspotConnect_life_cycle_stage'] = apply_filters('mailoptin_customizer_optin_campaign_HubspotConnect_life_cycle_stage', '');
        $settings['HubspotConnect_lead_owner']       = apply_filters('mailoptin_customizer_optin_campaign_HubspotConnect_lead_owner', '');

        return $settings;
    }

    public function get_property_options($property)
    {
        try {

            $cache_key = 'mo_hubspot_get_property_options_' . $property;

            $options = get_transient($cache_key);

            if (empty($options) || false === $options) {

                $response = $this->hubspotInstance()->apiRequest(
                    sprintf('crm/v3/properties/contacts/%s', $property)
                );

                $options = [];

                if (isset($response->options)) {

                    $options = array_reduce($response->options, function ($carry, $item) {
                        $carry[$item->value] = $item->label;

                        return $carry;
                    }, []);

                    set_transient($cache_key, $options, DAY_IN_SECONDS);
                }
            }

            return $options;

        } catch (\Exception $e) {
            //self::save_optin_error_log($e->getMessage(), 'hubspot');
            return [];
        }
    }

    public function get_owners()
    {
        try {

            $options = get_transient('mo_hubspot_get_owners');

            if (empty($options) || false === $options) {

                $response = $this->hubspotInstance()->apiRequest('crm/v3/owners/');

                $options = [];

                if ( ! empty($response->results)) {

                    $options = array_reduce($response->results, function ($carry, $item) {
                        $carry[$item->id] = $item->firstName . ' ' . $item->lastName;

                        return $carry;
                    }, []);

                    set_transient('mo_hubspot_get_owners', $options, DAY_IN_SECONDS);
                }
            }

            return $options;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'hubspot');

            return [];
        }
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
                'field'       => 'select',
                'name'        => 'HubspotConnect_lead_status',
                'label'       => __('Lead Status', 'mailoptin'),
                'choices'     => ['' => '––––––––––'] + $this->get_property_options('hs_lead_status'),
                'description' => __('Select the lead status value the newly added contact should be assigned.', 'mailoptin'),
            ];

            $controls[] = [
                'field'       => 'select',
                'name'        => 'HubspotConnect_life_cycle_stage',
                'choices'     => ['' => '––––––––––'] + $this->get_property_options('lifecyclestage'),
                'label'       => __('Lifecycle Stage', 'mailoptin'),
                'description' => __('Select the lifecycle stage value the newly added contact should be assigned.', 'mailoptin'),
            ];

            $controls[] = [
                'field'       => 'select',
                'name'        => 'HubspotConnect_lead_owner',
                'choices'     => ['' => '––––––––––'] + $this->get_owners(),
                'label'       => __('Contact Owner', 'mailoptin'),
                'description' => __('Select a HubSpot user that will be assigned as the owner of the newly created contact.', 'mailoptin'),
            ];

        } else {

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to map custom fields, set Lifecycle Stage, Lead Status and Contact Owner.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=hubspot_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            $controls[] = [
                'name'    => 'HubspotConnect_upgrade_notice',
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
        $default = [
            'all' => __('All Contacts', 'mailoptin'),
        ];

        try {

            $lists = get_transient('mo_hubspot_get_email_list');

            if (empty($lists) || false === $lists) {
                $lists = $this->hubspotInstance()->getEmailList();
                set_transient('mo_hubspot_get_email_list', $lists, HOUR_IN_SECONDS);
            }

            return array_replace($default, $lists);

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'hubspot');

            return $default;
        }
    }

    /**
     * {@inherit_doc}
     *
     * @return mixed
     */
    public function get_optin_fields($list_id = '')
    {
        try {

            $cache_key = 'mo_hubspot_get_optin_fields_' . $list_id;

            $fields = get_transient($cache_key);

            if (empty($fields) || false === $fields) {
                $fields = $this->hubspotInstance()->getListCustomFields();
                set_transient($cache_key, $fields, DAY_IN_SECONDS);
            }

            return $fields;

        } catch (\Exception $e) {
            //self::save_optin_error_log($e->getMessage(), 'hubspot');
            return [];
        }
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
        return (new Subscription($email, $name, $list_id, $extras, $this))->subscribe();
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