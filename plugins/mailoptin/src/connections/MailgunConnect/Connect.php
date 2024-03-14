<?php

namespace MailOptin\MailgunConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractMailgunConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'MailgunConnect';

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
            self::EMAIL_CAMPAIGN_SUPPORT
        ];
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Mailgun', 'mailoptin');

        return $connections;
    }

    /**
     * Replace placeholder tags with actual Mailgun tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = [
            '{{unsubscribe}}'
        ];

        $replace = [
            // see https://documentation.mailgun.com/en/latest/user_manual.html#tracking-unsubscribes
            '%mailing_list_unsubscribe_url%'
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

            $response = $this->mailgun_instance()->make_request('lists', ['limit' => 100]);

            // an array with list id as key and name as value.
            $lists_array = [];

            if (isset($response['body']['items']) && is_array($response['body']['items'])) {

                foreach ($response['body']['items'] as $list) {
                    $lists_array[$list['address']] = $list['name'];
                }

            } else {
                throw new \Exception(is_string($response['body']) ? $response['body'] : wp_json_encode($response['body']));
            }

            return $lists_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'mailgun');

            return [];
        }
    }

    public function get_optin_fields($list_id = '')
    {
        return [];
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