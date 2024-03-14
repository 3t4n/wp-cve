<?php

namespace MailOptin\NewsmanConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractNewsmanConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'NewsmanConnect';

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
            self::OPTIN_CUSTOM_FIELD_SUPPORT,
            self::EMAIL_CAMPAIGN_SUPPORT
        ];
    }

    /**
     * Register Newsman Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Newsman', 'mailoptin');

        return $connections;
    }

    /**
     * Fulfill interface contract.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = [
            '{{webversion}}',
            '{{unsubscribe}}'
        ];

        $replace = [
            '##NEWSMAN:view_online##',
            '##NEWSMAN:list_unsubscribe##'
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

            $lists_array = get_transient('newsman_get_email_list');

            if (empty($lists_array) || false === $lists_array) {

                $response = $this->newsmanInstance()->apiRequest('list.all.json');

                $lists_array = array();

                if (is_array($response) && ! empty($response)) {
                    foreach ($response as $list) {
                        $lists_array[$list->list_id] = $list->list_name;
                    }
                }

                set_transient('newsman_get_email_list', $lists_array, 10 * MINUTE_IN_SECONDS);
            }

            return $lists_array;


        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'newsman');

            return [];
        }
    }

    public function get_optin_fields($list_id = '')
    {
        try {

            $response = $this->newsmanInstance()->apiRequest('list.getSubscriberVariables.json', 'GET', ['list_id' => $list_id]);

            $custom_fields_array = array();

            if (is_array($response) && ! empty($response)) {

                foreach ($response as $field) {

                    $custom_fields_array[$field->name] = $field->name;
                }
            }

            return $custom_fields_array;


        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'newsman');

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