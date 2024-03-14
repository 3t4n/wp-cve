<?php

namespace MailOptin\IContactConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractIContactConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'IContactConnect';

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
            self::EMAIL_CAMPAIGN_SUPPORT,
            self::OPTIN_CUSTOM_FIELD_SUPPORT
        ];
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('iContact', 'mailoptin');

        return $connections;
    }

    /**
     * Replace placeholder tags with actual iContact tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = ['{{webversion}}', '{{unsubscribe}}'];
        $replace = ['[webversionurl]', '[manage_your_subscription_url]'];

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
        $lists_array = [];

        try {

            $response = $this->icontact_instance()->make_request('lists');

            if (is_array($response['body']['lists'])) {

                foreach ($response['body']['lists'] as $list) {
                    $lists_array[$list['listId']] = $list['name'];
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'icontact');
        }

        return $lists_array;
    }

    public function get_optin_fields($list_id = '')
    {
        $custom_fields_array = [
            'prefix'     => esc_html__('Contact Prefix', 'mailoptin'),
            'suffix'     => esc_html__('Contact Suffix', 'mailoptin'),
            'street'     => esc_html__('Address 1', 'mailoptin'),
            'street2'    => esc_html__('Address 2', 'mailoptin'),
            'city'       => esc_html__('City', 'mailoptin'),
            'state'      => esc_html__('State', 'mailoptin'),
            'postalCode' => esc_html__('Postal Code', 'mailoptin'),
            'phone'      => esc_html__('Phone Number', 'mailoptin'),
            'fax'        => esc_html__('Fax Number', 'mailoptin'),
            'business'   => esc_html__('Business Phone Number', 'mailoptin'),
        ];

        try {

            $response = $this->icontact_instance()->make_request('customfields');

            if (isset($response['body']['customfields']) && ! empty($response['body']['customfields'])) {

                foreach ($response['body']['customfields'] as $customField) {
                    $custom_fields_array[$customField['customFieldId']] = $customField['publicName'];
                }
            }

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'icontact');
        }

        return $custom_fields_array;
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