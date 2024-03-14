<?php

namespace MailOptin\BenchmarkEmailConnect;

use MailOptin\Core\Connections\ConnectionInterface;


class Connect extends AbstractBenchmarkEmailConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'BenchmarkEmailConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', [$this, 'register_connection']);

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
     * Register Benchmark Email Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Benchmark Email', 'mailoptin');

        return $connections;
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
        $lists = [];

        try {

            $response = $this->benchmarkemail_instance()->make_request('Contact/', ['PageSize' => 1000]);

            if (isset($response['body']->Response->Data)) {
                foreach ($response['body']->Response->Data as $list) {
                    $lists[$list->ID] = $list->Name;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'benchmarkemail');
        }

        return $lists;
    }

    public function get_optin_fields($list_id = '')
    {
        $bucket = [];

        try {

            $response = $this->benchmarkemail_instance()->make_request(sprintf('Contact/%s', $list_id));

            if (isset($response['body']->Response->Data)) {

                $fields = (array)$response['body']->Response->Data;

                foreach ($fields as $fieldId => $fieldLabel) {
                    if (strstr($fieldId, 'Field') !== false) {
                        if (strstr($fieldId, 'Type') !== false) continue;
                        $bucket[str_replace('Name', '', $fieldId)] = $fieldLabel;
                    }
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'benchmarkemail');
        }

        return $bucket;
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