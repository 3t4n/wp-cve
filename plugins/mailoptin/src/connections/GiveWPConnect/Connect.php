<?php

namespace MailOptin\GiveWPConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\ControlsHelpers;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var MailBGProcess
     */
    public $gwp_bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'GiveWPConnect';

    public function __construct()
    {
        add_action('give_init', function () {

            GWPInit::get_instance();

            add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
            add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
            add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

            $this->gwp_bg_process_instance = new MailBGProcess();

            add_action('init', [$this, 'unsubscribe_handler']);
            add_action('init', [$this, 'view_online_version']);

            add_filter('mo_page_targeting_search_response', [$this, 'select2_search'], 10, 3);
        });
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('GiveWP', 'mailoptin');

        return $connections;
    }


    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings_bucket = [
            'GiveWPConnect_forms',
            'GiveWPConnect_donors',
            'GiveWPConnect_notice'
        ];

        foreach ($settings_bucket as $item) {

            $settings[$item] = [
                'default'   => '',
                'type'      => 'option',
                'transport' => 'postMessage',
            ];
        }

        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param Customizer $customizerClassInstance
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        // always prefix with the name of the connect/connection service.
        $controls['GiveWPConnect_forms'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[GiveWPConnect_forms]',
            array(
                'label'       => __('Restrict to Donation Forms', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[GiveWPConnect_forms]',
                'description' => __('Select the forms whose donors will receive emails from this campaign.', 'mailoptin'),
                'search_type' => 'gwp_forms',
                'choices'     => ControlsHelpers::get_post_type_posts('give_forms'),
                'priority'    => 62
            )
        );

        $controls['GiveWPConnect_donors'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[GiveWPConnect_donors]',
            array(
                'label'       => __('Restrict to Selected Donors', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[GiveWPConnect_donors]',
                'description' => __('Select the donors that emails will only be delivered to.', 'mailoptin'),
                'search_type' => 'gwp_donors',
                'choices'     => $this->get_donors(),
                'priority'    => 63
            )
        );

        $controls['GiveWPConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[GiveWPConnect_notice]',
            array(
                'content'  => esc_html__('Leave all "Restrict to ..." settings empty to send to all donors.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[GiveWPConnect_notice]',
                'priority' => 64
            )
        );

        return $controls;
    }

    public function select2_search($response, $search_type, $q)
    {
        if ($search_type == 'gwp_forms') {
            $response = ControlsHelpers::get_post_type_posts('download', 500, 'publish', $q);
        }

        if ($search_type == 'gwp_donors') {
            $response = $this->get_donors(500, $q);
        }

        return $response;
    }

    protected function get_donors($number = 200, $form_id = '', $search = '')
    {
        $cache_key = sprintf('gwp_customer_%s_%s_%s', $number, $form_id, $search);

        static $cache = [];

        if ( ! isset($cache[$cache_key])) {

            $all_users = (new \Give_Donors_Query([
                'fields'     => ['email', 'name'],
                'number'     => intval($number),
                'give_forms' => $form_id,
                's'          => $search,
            ]))->get_donors();

            $result = [];

            foreach ($all_users as $user) {
                $result[$user->email] = sprintf('%s (%s)', $user->name, $user->email);
            }

            $cache[$cache_key] = $result;
        }

        return $cache[$cache_key];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_givewp_unsubscribe']) || empty($_GET['mo_givewp_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_givewp_unsubscribe']);

        $contacts   = get_option('mo_givewp_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_givewp_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_givewp_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_givewp_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
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
        $forms  = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'GiveWPConnect_forms', []);
        $donors = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'GiveWPConnect_donors', []);

        $bucket = [];

        if (empty($forms) && empty($donors)) {

            $users = $this->get_donors(0);

            if ( ! empty($users)) {

                foreach ($users as $user_email => $user_name) {

                    if (in_array($user_email, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $user_email;
                    $bucket[]         = $user_email;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->gwp_bg_process_instance->push_to_queue($item);
                }
            }

        } else {

            if (is_array($forms) && ! empty($forms)) {

                foreach ($forms as $form_id) {

                    $_users = $this->get_donors(0, $form_id);

                    if ( ! empty($_users)) {

                        foreach ($_users as $_user_email => $user_name) {

                            if (in_array($_user_email, $bucket)) continue;

                            $item             = new \stdClass();
                            $item->user_email = $_user_email;
                            $bucket[]         = $_user_email;

                            $item->email_campaign_id = $email_campaign_id;
                            $item->campaign_log_id   = $campaign_log_id;

                            $this->gwp_bg_process_instance->push_to_queue($item);
                        }
                    }
                }
            }

            if ( ! empty($donors)) {

                foreach ($donors as $donor) {

                    if (in_array($donor, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $donor;
                    $bucket[]         = $donor;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->gwp_bg_process_instance->push_to_queue($item);
                }
            }
        }

        $this->gwp_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                      ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
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