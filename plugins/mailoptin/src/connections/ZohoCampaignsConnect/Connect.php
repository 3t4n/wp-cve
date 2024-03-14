<?php

namespace MailOptin\ZohoCampaignsConnect;

use MailOptin\Core\Connections\ConnectionInterface;
use MailOptin\Core\Logging\CampaignLogRepository;

class Connect extends AbstractZohoCampaignsConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'ZohoCampaignsConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));


        add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'campaign_customizer_settings'));
        add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'campaign_customizer_controls'), 10, 4);

        add_action('init', [$this, 'campaign_log_public_preview']);

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
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Zoho Campaigns', 'mailoptin');

        return $connections;
    }

    public function campaign_customizer_settings($settings)
    {
        $settings['ZohoCampaignsConnect_topic'] = array(
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        );

        return $settings;
    }

    public function campaign_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        $controls['ZohoCampaignsConnect_topic'] = array(
            'type'        => 'select',
            'label'       => __('Select a Topic', 'mailoptin'),
            'choices'     => $this->get_topics(),
            'section'     => $customizerClassInstance->campaign_settings_section_id,
            'settings'    => $option_prefix . '[ZohoCampaignsConnect_topic]',
            'description' => __("Topics in Zoho Campaigns categorize your mailing lists so your contacts can choose the topics they want to hear from you.", 'mailoptin'),
            'priority'    => 199
        );

        return $controls;
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
            '$[LI:VIEWINBROWSER]$',
            '$[LI:UNSUBSCRIBE]$',
        ];

        $content = str_replace($search, $replace, $content);

        return $this->replace_footer_placeholder_tags($content);
    }

    public function campaign_log_public_preview()
    {
        if (isset($_GET['zohocampaigns_preview_type'], $_GET['uuid'])) {

            $preview_type = sanitize_text_field($_GET['zohocampaigns_preview_type']);

            if ( ! in_array($preview_type, ['text', 'html'])) return;

            $campaign_uuid = sanitize_text_field($_GET['uuid']);

            $campaign_log_id = absint($this->uuid_to_campaignlog_id($campaign_uuid, 'zohocampaigns_email_fetcher'));

            $type_method = 'retrieveContent' . ucfirst($preview_type);

            echo $this->replace_placeholder_tags(CampaignLogRepository::instance()->$type_method($campaign_log_id), $preview_type);
            exit;
        }
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

            $offset = 0;
            $loop   = true;

            $lists_array = [];

            while ($loop === true) {

                $response = $this->zcInstance()->apiRequest('getmailinglists?resfmt=JSON', 'GET', ['range' => 1000, 'fromindex' => $offset]);

                if (isset($response->list_of_details) && is_array($response->list_of_details) && ! empty($response->list_of_details)) {

                    foreach ($response->list_of_details as $list) {
                        $lists_array[$list->listkey] = $list->listname;
                    }

                    if (count($response->list_of_details) < 1000) {
                        $loop = false;
                    }

                    $offset += 1000;
                } else {
                    $loop = false;

                    self::save_optin_error_log(json_encode($response), 'zohocampaigns');
                }
            }

            return $lists_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'zohocampaigns');

            return [];
        }
    }

    public function get_optin_fields($list_id = '')
    {
        try {

            $response = $this->zcInstance()->apiRequest('contact/allfields?type=json');

            $fields = [];
            if (isset($response->response->fieldnames->fieldname) && is_array($response->response->fieldnames->fieldname)) {
                foreach ($response->response->fieldnames->fieldname as $field) {
                    if (in_array($field->FIELD_DISPLAY_NAME, ['CONTACT_EMAIL', 'FIRSTNAME', 'LASTNAME'])) continue;
                    $fields[$field->DISPLAY_NAME] = $field->DISPLAY_NAME;
                }

                return $fields;
            }

            return self::save_optin_error_log(json_encode($response), 'zohocampaigns');

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'zohocampaigns');

            return [];
        }
    }

    public function get_topics()
    {
        $topics = ['' => '&mdash;&mdash;&mdash;&mdash;&mdash;'];

        try {

            $response = $this->zcInstance()->apiRequest('topics?details={from_index:0,range:1000}');

            if (isset($response->topicDetails)) {

                foreach ($response->topicDetails as $topic_detail) {
                    $topics[$topic_detail->topicId] = $topic_detail->topicName;
                }

                return $topics;
            }

            self::save_optin_error_log(json_encode($response), 'zohocampaigns');

            return $topics;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'zohocampaigns');

            return $topics;
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