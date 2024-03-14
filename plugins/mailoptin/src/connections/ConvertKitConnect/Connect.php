<?php

namespace MailOptin\ConvertKitConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Toggle_Control;
use MailOptin\Core\Connections\ConnectionInterface;
use function MailOptin\Core\moVar;

class Connect extends AbstractConvertKitConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'ConvertKitConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_filter('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'campaign_customizer_settings'));
        add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'campaign_customizer_controls'), 10, 4);

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
     * Register ConvertKit Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('ConvertKit', 'mailoptin');

        return $connections;
    }

    /**
     * Fetches convertkit sequences/courses.
     *
     * @return mixed
     */
    public function sequences()
    {
        if ( ! self::is_connected()) return;

        try {

            $sequence_array = [];

            if (empty($result) || false === $result) {

                $response = parent::convertkit_instance()->get_sequences();

                if ($response['status_code'] < 200 || $response['status_code'] > 299) {
                    return self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'convertkit');

                }

                $sequences = $response['body']->courses;

                foreach ($sequences as $sequence) {
                    $sequence_array[$sequence->id] = $sequence->name;
                }
            }

            return $sequence_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'convertkit');
        }
    }

    /**
     * Fetches tags.
     *
     * @return mixed
     */
    public function get_tags()
    {
        if ( ! self::is_connected()) return;

        $default = [];

        try {

            $cache_key = 'converkit_tags';

            $tag_array = get_transient($cache_key);

            if (empty($tag_array) || false === $tag_array) {

                $response = parent::convertkit_instance()->get_tags();

                if (self::is_http_code_not_success($response['status_code'])) {
                    self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'convertkit');

                    return $default;
                }

                $tags = $response['body']->tags;

                $tag_array = [];

                foreach ($tags as $tag) {
                    $tag_array[$tag->id] = $tag->name;
                }

                set_transient($cache_key, $tag_array, 10 * MINUTE_IN_SECONDS);
            }

            return $tag_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'convertkit');

            return $default;
        }
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['ConvertKitConnect_subscriber_sequences'] = apply_filters('mailoptin_customizer_optin_campaign_ConvertKitConnect_subscriber_sequences', []);

        $settings['ConvertKitConnect_subscriber_tags'] = apply_filters('mailoptin_customizer_optin_campaign_ConvertKitConnect_subscriber_tags', []);

        return $settings;
    }

    /**
     * @param array $controls
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {
            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'field'       => 'chosen_select',
                'name'        => 'ConvertKitConnect_subscriber_sequences',
                'choices'     => $this->sequences(),
                'label'       => __('Subscriber Sequences', 'mailoptin'),
                'description' => __('Select ConvertKit sequences subscribers will be added to.', 'mailoptin')
            ];

            $controls[] = [
                'field'       => 'chosen_select',
                'name'        => 'ConvertKitConnect_subscriber_tags',
                'choices'     => $this->get_tags(),
                'label'       => __('Subscriber Tags', 'mailoptin'),
                'description' => __('Select ConvertKit tags that will be assigned to subscribers.', 'mailoptin')
            ];

        } else {

            $content = sprintf(
                __("%sMailOptin Premium%s allows you to apply tags to subscribers, add subscribers to sequences.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=convertkit_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            // always prefix with the name of the connect/connection service.
            $controls[] = [
                'name'    => 'ConvertKitConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    public function campaign_customizer_settings($settings)
    {
        $settings['ConvertKitConnect_make_public'] = array(
            'default'   => false,
            'type'      => 'option',
            'transport' => 'postMessage',
        );

        return $settings;
    }

    public function campaign_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        // always prefix with the name of the connect/connection service.
        $controls['ConvertKitConnect_make_public'] = new WP_Customize_Toggle_Control(
            $wp_customize,
            $option_prefix . '[ConvertKitConnect_make_public]',
            [
                'label'       => __('Post to Your Public Feed', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[ConvertKitConnect_make_public]',
                'description' => sprintf(
                    __('Include emails from this campaign in customizable newsletter feed, embedded directly in your landing page. %sLearn more%s'),
                    '<a target="_blank" href="https://help.convertkit.com/en/articles/4446421-how-to-add-a-newsletter-feed-to-your-landing-page">', '</a>'
                ),
                'priority'    => 195,
            ]
        );

        return $controls;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = [
            '{{unsubscribe}}'
        ];

        $replace = [
            '{{ unsubscribe_link }}',
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
        if ('email' == moVar($_POST, 'ui') || isset($_GET['mailoptin_email_campaign_id'])) {
            return ['all' => __('All Subscribers', 'mailoptin')];
        }

        try {

            $response = $this->convertkit_instance()->get_forms();

            if (isset($response['body'], $response['body']->error)) {
                return self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'convertkit');
            }

            // an array with list id as key and name as value.
            $lists_array = array();

            if (self::is_http_code_success($response['status_code'])) {

                $forms = $response['body']->forms;

                if ( ! empty($forms)) {
                    foreach ($forms as $form) {
                        $lists_array[$form->id] = $form->name;
                    }
                }
            }

            return $lists_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'convertkit');
        }
    }

    public function get_optin_fields($list_id = '')
    {
        try {

            $response = $this->convertkit_instance()->get_custom_fields();

            if (isset($response['body'], $response['body']->error)) {
                return self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'convertkit');
            }

            if (self::is_http_code_success($response['status_code'])) {

                $result = $response['body']->custom_fields;

                $custom_fields_array = [];
                if ( ! empty($result)) {
                    foreach ($result as $custom_field) {
                        $custom_fields_array[$custom_field->key] = $custom_field->label;
                    }
                }

                return $custom_fields_array;
            }

            self::save_optin_error_log(json_encode($response['body']), 'convertkit');

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'convertkit');
        }
    }

    /**
     *
     * {@inheritdoc}
     *
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