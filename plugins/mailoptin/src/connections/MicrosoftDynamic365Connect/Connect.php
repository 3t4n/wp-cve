<?php

namespace MailOptin\MicrosoftDynamic365Connect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractMicrosoftDynamic365Connect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'MicrosoftDynamic365Connect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

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
     * Register Microsoft Dynamics 365 Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Microsoft Dynamics 365', 'mailoptin');

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
     * @return mixed
     */
    public function get_email_list()
    {
        $bucket = [];

        try {

            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/query-data-web-api
            $response = $this->makeRequest('lists');

            if (isset($response['body']->value) && is_array($response['body']->value)) {

                foreach ($response['body']->value as $value) {
                    $bucket[$value->listid] = $value->listname;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'microsoftdynamic365');
        }

        return $bucket;
    }

    public function get_optin_fields($list_id = '')
    {
        $bucket = [];

        try {

            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/retrieve-metadata-name-metadataid
            // https://learn.microsoft.com/en-us/power-apps/developer/data-platform/webapi/query-metadata-web-api
            $response = $this->makeRequest("EntityDefinitions(LogicalName='contact')/Attributes");

            if (isset($response['body']->value) && is_array($response['body']->value)) {

                foreach ($response['body']->value as $field) {
                    // Skip fields with those parameters (they are not available for filling).
                    if ( ! $field->IsValidForCreate || $field->AttributeType == 'Lookup') continue;

                    if (in_array($field->LogicalName, ['firstname', 'lastname', 'emailaddress1'])) continue;

                    if ( ! isset($field->DisplayName->UserLocalizedLabel->Label)) continue;

                    $bucket[$field->LogicalName] = $field->DisplayName->UserLocalizedLabel->Label;
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'microsoftdynamic365');
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