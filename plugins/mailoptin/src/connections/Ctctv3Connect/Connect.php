<?php

namespace MailOptin\Ctctv3Connect;

use Authifly\Provider\ConstantContactV3;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\ConnectionInterface;
use MailOptin\Core\PluginSettings\Connections;
use function MailOptin\Core\current_user_has_privilege;
use function MailOptin\Core\moVar;

class Connect extends AbstractCtctv3Connect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'Ctctv3Connect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_action('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'), 10, 4);

        add_action('mailoptin_admin_notices', function () {
            add_action('admin_notices', array($this, 'admin_notice'));
        });

        add_action('admin_init', [$this, 'authorize_integration']);

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
     * Register Constant Contact v3 Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Constant Contact', 'mailoptin');

        return $connections;
    }

    public function authorize_integration()
    {
        if ( ! current_user_has_privilege()) return;

        if ( ! isset($_GET['moauth'])) return;

        if ($_GET['moauth'] != 'ctctv3') return;

        $connections_settings = Connections::instance(true);
        $ctctv3_api_key       = $connections_settings->ctctv3_api_key();
        $ctctv3_api_secret    = $connections_settings->ctctv3_api_secret();

        $config = [
            'callback' => self::callback_url(),
            'keys'     => ['id' => $ctctv3_api_key, 'secret' => $ctctv3_api_secret]
        ];


        $instance = new ConstantContactV3($config, null, new OAuthCredentialStorage());

        try {

            $instance->authenticate();

            $access_token = $instance->getAccessToken();

            $old_data = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME, []);
            unset($old_data['ctctv3_date_created']);

            $new_data = [
                'ctctv3_access_token'  => moVar($access_token, 'access_token'),
                'ctctv3_refresh_token' => moVar($access_token, 'refresh_token'),
                'ctctv3_expires_at'    => time() + (int)moVar($access_token, 'expires_in')
            ];

            $new_data = array_filter($new_data, [$this, 'data_filter']);

            update_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME, array_merge($old_data, $new_data));

            $connection = self::$connectionName;

            // delete connection cache
            delete_transient("_mo_connection_cache_$connection");

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getMessage(), 'constantcontactv3');
        }

        $instance->disconnect();

        wp_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
        exit;
    }

    /**
     * Fulfill interface contract.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = [
            '{{webversion}}'
        ];

        $replace = [
            '[[viewAsWebpage]]'
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

            return $this->ctctv3Instance()->getContactList();

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'constantcontactv3');

            return [];
        }
    }

    public function get_optin_fields($list_id = '')
    {
        $custom_fields = [
            'job_title'         => __('Job Title', 'mailoptin'),
            'company_name'      => __('Company Name', 'mailoptin'),
            'birthday_month'    => __('Birthday Month', 'mailoptin'),
            'birthday_day'      => __('Birthday Day', 'mailoptin'),
            'anniversary'       => __('Anniversary', 'mailoptin'),
            'phone_number'      => __('Phone Number', 'mailoptin'),

            // Home address
            'mohma_street'      => __('Home Address Street', 'mailoptin'),
            'mohma_city'        => __('Home Address City', 'mailoptin'),
            'mohma_state'       => __('Home Address State', 'mailoptin'),
            'mohma_postal_code' => __('Home Address Postal Code', 'mailoptin'),
            'mohma_country'     => __('Home Address Country', 'mailoptin'),

            // Work address
            'mowka_street'      => __('Work Address Street', 'mailoptin'),
            'mowka_city'        => __('Work Address City', 'mailoptin'),
            'mowka_state'       => __('Work Address State', 'mailoptin'),
            'mowka_postal_code' => __('Work Address Postal Code', 'mailoptin'),
            'mowka_country'     => __('Work Address Country', 'mailoptin'),

            // Other address
            'moota_street'      => __('Other Address Street', 'mailoptin'),
            'moota_city'        => __('Other Address City', 'mailoptin'),
            'moota_state'       => __('Other Address State', 'mailoptin'),
            'moota_postal_code' => __('Other Address Postal Code', 'mailoptin'),
            'moota_country'     => __('Other Address Country', 'mailoptin'),
        ];

        try {

            $fields = get_transient('ctctv3_get_optin_fields_' . $list_id);

            if (empty($fields) || false === $fields) {

                $fields = $this->ctctv3Instance()->getContactsCustomFields();

                set_transient('ctctv3_get_optin_fields_' . $list_id, $fields, 10 * MINUTE_IN_SECONDS);
            }

            if (is_array($fields) && ! empty($fields)) {

                // custom fields (cufd)
                foreach ($fields as $field) {
                    $custom_fields['cufd_' . $field->custom_field_id] = $field->label;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'constantcontactv3');
        }

        return $custom_fields;
    }

    /**
     * @param $controls
     *
     * @return array
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {
            $controls[] = [
                'field'       => 'chosen_select',
                'name'        => 'Ctctv3Connect_contact_tags',
                'choices'     => $this->get_tags(),
                'label'       => __('Subscriber Tags', 'mailoptin'),
                'description' => __('Select Constant Contact tags that will be assigned to contacts.', 'mailoptin')
            ];

            return $controls;
        }
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['Ctctv3Connect_contact_tags'] = apply_filters('mailoptin_customizer_optin_campaign_Ctctv3Connect_contact_tags', []);

        return $settings;
    }

    /**
     * Fetches tags.
     *
     * @return mixed
     */
    public function get_tags()
    {
        if ( ! self::is_connected()) return [];

        $default = [];

        try {

            $cache_key = 'ctctv3_tags';

            $tag_array = get_transient($cache_key);

            if (empty($tag_array) || false === $tag_array) {
                $tags = $this->ctctv3Instance()->getTags();

                $tag_array = [];

                foreach ($tags as $tag) {
                    $tag_array[$tag->tag_id] = $tag->name;
                }

                set_transient($cache_key, $tag_array, 10 * MINUTE_IN_SECONDS);
            }

            return $tag_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'constantcontactv3');

            return $default;
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

    public function admin_notice()
    {
        $connections_settings = Connections::instance(true);
        $access_token         = $connections_settings->ctctv3_access_token();
        $api_key              = $connections_settings->ctctv3_api_key();
        $api_secret           = $connections_settings->ctctv3_api_secret();

        if ( ! empty($access_token) && empty($api_key) && empty($api_secret)) {

            $message = sprintf(
            /* translators: 1: open <a> tag, 2: close </a> tag */
                esc_html__(
                    'On March 31, 2022, Constant Contact is ending support for their previous authorization management service. %1$sPlease re-authorize your Constant Contact integration%2$s urgently to get your MailOptin form working again.',
                    'mailoptin'
                ),
                '<a href="' . MAILOPTIN_CONNECTIONS_SETTINGS_PAGE . '#ctctv3_auth">',
                '</a>'
            )
            ?>

            <div class="notice notice-error">
                <p><?php echo wp_kses($message, array('a' => array('href' => true))); ?></p>
            </div>
            <?php
        }
    }

    /**
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