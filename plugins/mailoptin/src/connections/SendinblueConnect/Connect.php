<?php

namespace MailOptin\SendinblueConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractSendinblueConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'SendinblueConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_action('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        add_filter('mo_optin_integrations_advance_controls', array($this, 'customizer_advance_controls'));
        add_filter('mo_optin_form_integrations_default', [$this, 'customizer_advance_controls_defaults']);

        add_filter('mo_connections_with_advance_settings_support', function ($val) {
            $val[] = self::$connectionName;

            return $val;
        });

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

    public function customizer_advance_controls_defaults($defaults)
    {
        $defaults['SendinblueConnect_first_name_field_key'] = 'FIRSTNAME';
        $defaults['SendinblueConnect_last_name_field_key']  = 'LASTNAME';

        return $defaults;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['SendinblueConnect_enable_double_optin'] = apply_filters('mailoptin_customizer_optin_campaign_SendinblueConnect_enable_double_optin', false);

        return $settings;
    }

    /**
     * @param $controls
     *
     * @return array
     */
    public function integration_customizer_controls($controls)
    {
        $controls[] = [
            'field'       => 'toggle',
            'name'        => 'SendinblueConnect_enable_double_optin',
            'label'       => __('Enable Double Optin', 'mailoptin'),
            'description' => __("Double optin requires users to confirm their email address before they are added or subscribed (recommended).", 'mailoptin')
        ];

        return $controls;
    }

    /**
     * @param $controls
     *
     * @return array
     */
    public function customizer_advance_controls($controls)
    {
        // always prefix with the name of the connect/connection service.
        $controls[] = [
            'field'       => 'text',
            'name'        => 'SendinblueConnect_first_name_field_key',
            'label'       => __('First Name Attribute', 'mailoptin'),
            'description' => sprintf(
                __('If subscribers first names are missing, change this to the correct attribute name. %sLearn more%s', 'mailoptin'),
                '<a href="https://mailoptin.io/article/subscriber-name-missing-fix/" target="_blank">', '</a>'
            )
        ];
        $controls[] = [
            'field'       => 'text',
            'name'        => 'SendinblueConnect_last_name_field_key',
            'label'       => __('Last Name Attribute', 'mailoptin'),
            'description' => sprintf(
                __('If subscribers last names are missing, change this to the correct attribute name. %sLearn more%s', 'mailoptin'),
                '<a href="https://mailoptin.io/article/subscriber-name-missing-fix/" target="_blank">', '</a>'
            )
        ];

        return $controls;
    }

    public function get_double_optin_template()
    {
        try {
            $args = ['templateStatus' => 'true', 'limit' => 1000];

            $response = $this->sendinblue_instance()->make_request('smtp/templates', $args);

            $double_optin_templates = [];

            if (isset($response['body']->templates) && ! empty($response['body']->templates)) {

                foreach ($response['body']->templates as $template) {
                    $double_optin_templates[$template->id] = $template->name;
                }
            }

            return $double_optin_templates;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'sendinblue');

            return [];
        }
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Brevo (Sendinblue)', 'mailoptin');

        return $connections;
    }

    /**
     * Replace placeholder tags with actual Sendinblue tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        // https://help.sendinblue.com/hc/en-us/articles/209553645-Insert-default-header-and-footer-to-your-campaign
        $search = [
            '{{webversion}}',
            '{{unsubscribe}}'
        ];

        $replace = [
            '{{ mirror }}',
            '{{ unsubscribe }}'
        ];

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
        try {

            $list_array = [];

            $offset = 0;
            $limit  = 50;
            $loop   = true;

            while ($loop === true) {

                // note any value > 50 results in {"code":"out_of_range","message":"Limit exceeds max value"}
                $response = $this->sendinblue_instance()->make_request('contacts/lists', ['offset' => $offset, 'limit' => $limit]);

                if (isset($response['body']->lists) && is_array($response['body']->lists)) {

                    foreach ($response['body']->lists as $list) {
                        $list_array[$list->id] = $list->name;
                    }

                    if (count($response['body']->lists) < $limit) {
                        $loop = false;
                    }

                    $offset += $limit;
                } else {
                    $loop = false;
                    self::save_optin_error_log(json_encode($response['body']), 'sendinblue');
                }
            }

            return $list_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'sendinblue');

            return [];
        }
    }

    public function get_optin_fields($list_id = '')
    {
        try {

            $response = $this->sendinblue_instance()->make_request('contacts/attributes');

            if ( ! isset($response['body']->attributes)) {
                return self::save_optin_error_log(json_encode($response['body']), 'sendinblue');
            }

            $response = $response['body']->attributes;

            if (is_array($response) && ! empty($response)) {
                $custom_fields_array = [];
                foreach ($response as $customField) {
                    $attribute = $customField->name;

                    $firstname_key = $this->get_first_name_attribute();
                    $lastname_key  = $this->get_last_name_attribute();

                    $exclude_list = [
                        'BLACKLIST',
                        'CLICKERS',
                        'READERS',
                        'NOMBRE',
                        'PRENOM',
                        'VORNAME',
                        'NOME',
                        'SURNAME',
                        'NOM',
                        'NACHNAME',
                        'SOBRENOME',
                        'COGNOME',
                        $firstname_key,
                        $lastname_key
                    ];

                    if (in_array($attribute, $exclude_list)) continue;
                    $custom_fields_array[$attribute] = $attribute;
                }

                return $custom_fields_array;
            }

            return self::save_optin_error_log(json_encode($response['body']), 'sendinblue');

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'sendinblue');
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
