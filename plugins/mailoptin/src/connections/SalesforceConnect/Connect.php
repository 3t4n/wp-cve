<?php

namespace MailOptin\SalesforceConnect;

use Authifly\Provider\Salesforce;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\ConnectionInterface;
use MailOptin\Core\PluginSettings\Connections;
use function MailOptin\Core\current_user_has_privilege;
use function MailOptin\Core\moVar;

class Connect extends AbstractSalesforceConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'SalesforceConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_action('admin_init', [$this, 'authorize_integration']);

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
     * Register Salesforce Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Salesforce', 'mailoptin');

        return $connections;
    }

    public function authorize_integration()
    {
        if ( ! current_user_has_privilege()) return;

        if ( ! isset($_GET['moauth'])) return;

        if ($_GET['moauth'] != 'salesforce') return;

        $connections_settings       = Connections::instance(true);
        $salesforce_consumer_key    = $connections_settings->salesforce_consumer_key();
        $salesforce_consumer_secret = $connections_settings->salesforce_consumer_secret();

        $config = [
            'callback' => self::callback_url(),
            'keys'     => ['id' => $salesforce_consumer_key, 'secret' => $salesforce_consumer_secret]
        ];

        $instance = new Salesforce($config, null, new OAuthCredentialStorage());

        try {

            $instance->authenticate();

            $access_token = $instance->getAccessToken();

            $old_data = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME, []);

            $new_data = [
                'salesforce_instance_url'  => moVar($access_token, 'instance_url'),
                'salesforce_access_token'  => moVar($access_token, 'access_token'),
                'salesforce_refresh_token' => moVar($access_token, 'refresh_token')
            ];

            $new_data = array_filter($new_data, [$this, 'data_filter']);

            update_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME, array_merge($old_data, $new_data));

            // delete connection cache
            delete_transient("_mo_connection_cache_" . self::$connectionName);

        } catch (\Exception $e) {

            self::save_optin_error_log($e->getMessage(), 'salesforce');
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
        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * {@inherit_doc}
     *
     * @return mixed
     */
    public function get_email_list()
    {
        $bucket = [];

        try {

            $standard = [
                'Account'     => esc_html__('Account', 'mailoptin'),
                'Campaign'    => esc_html__('Campaign', 'mailoptin'),
                'Case'        => esc_html__('Case', 'mailoptin'),
                'Contact'     => esc_html__('Contact', 'mailoptin'),
                'Lead'        => esc_html__('Lead', 'mailoptin'),
                'Opportunity' => esc_html__('Opportunity', 'mailoptin'),
                'Product2'    => esc_html__('Product', 'mailoptin'),
            ];

            $response = $this->makeRequest('sobjects');

            $objectBucket = [];

            if (isset($response->sobjects) && is_array($response->sobjects)) {

                foreach ($response->sobjects as $sobject) {
                    // https://developer.salesforce.com/docs/atlas.en-us.uiapi.meta/uiapi/ui_api_responses_object_info.htm
                    if ($sobject->createable == true && $sobject->layoutable == true) {
                        $objectBucket[$sobject->name] = $sobject->label;
                    }
                }
            }

            // doing this so found $standard object will be at the top
            $bucket = array_merge(array_intersect_key($standard, $objectBucket), $objectBucket);

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'salesforce');
        }

        return $bucket;
    }

    public function getObjectFields($object, $allFields = false, $returnKeys = false)
    {
        $bucket = [];

        try {

            $response = $this->makeRequest('sobjects/' . $object . '/describe');

            if (isset($response->fields) && is_array($response->fields)) {

                foreach ($response->fields as $field) {
                    // Skip fields with those parameters (they are not available for filling).
                    if ( ! $field->createable || $field->deprecatedAndHidden || $field->type == 'boolean') continue;

                    if ($allFields === false && in_array($field->name, ['FirstName', 'LastName', 'Email'])) continue;

                    if ($returnKeys === true) {
                        $bucket[] = $field->name;
                    } else {
                        $bucket[$field->name] = $field->label;
                    }
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'salesforce');
        }

        return $bucket;
    }

    public function get_optin_fields($list_id = '')
    {
        return $this->getObjectFields($list_id);
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
     *
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