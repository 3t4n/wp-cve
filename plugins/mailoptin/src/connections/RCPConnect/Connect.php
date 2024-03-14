<?php

namespace MailOptin\RCPConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var Mail_BG_Process
     */
    public $mp_bg_process_instance;
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'RCPConnect';

    public function __construct()
    {
        add_action('plugins_loaded', function () {

            if (class_exists('\Restrict_Content_Pro')) {
                Membership::get_instance();
                RCPSettings::get_instance();
                RCPInit::get_instance();

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

                $this->mp_bg_process_instance = new Mail_BG_Process();

                add_action('init', [$this, 'unsubscribe_handler']);
                add_action('init', [$this, 'view_online_version']);
            }
        });
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Restrict Content Pro', 'mailoptin');

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
            'RCPConnect_active_memberships',
            'RCPConnect_expired_memberships',
            'RCPConnect_cancelled_memberships',
            'RCPConnect_members',
            'RCPConnect_notice'
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

    public function get_membership_levels()
    {
        static $cache = null;

        if (is_null($cache)) {

            $levels = [];

            if (function_exists('rcp_get_membership_levels')) {

                $levels = rcp_get_membership_levels([
                    'number' => 0
                ]);
            }

            $options = array();

            foreach ($levels as $level) {
                $options[$level->get_id()] = $level->get_name();
            }

            $cache = $options;
        }

        return $cache;
    }

    public function get_members($level_id = '', $status = '')
    {
        $args = ['number' => 0, 'fields' => 'user_id'];

        if ( ! empty($level_id) || ! empty($status)) {

            if ( ! empty($level_id)) $args['object_id'] = (int)$level_id;

            if ( ! empty($status)) $args['status'] = sanitize_text_field($status);

            return rcp_get_memberships($args);
        }

        return rcp_get_customers($args);
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
        $controls['RCPConnect_active_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[RCPConnect_active_memberships]',
            array(
                'label'       => __('Restrict to Active Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[RCPConnect_active_memberships]',
                'description' => __('Select the membership levels whose active members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_levels(),
                'priority'    => 62
            )
        );

        $controls['RCPConnect_expired_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[RCPConnect_expired_memberships]',
            array(
                'label'       => __('Restrict to Expired Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[RCPConnect_expired_memberships]',
                'description' => __('Select the membership levels whose expired members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_levels(),
                'priority'    => 63
            )
        );

        $controls['RCPConnect_cancelled_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[RCPConnect_cancelled_memberships]',
            array(
                'label'       => __('Restrict to Cancelled Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[RCPConnect_cancelled_memberships]',
                'description' => __('Select the membership levels whose cancelled members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_levels(),
                'priority'    => 63
            )
        );

        $controls['RCPConnect_members'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[RCPConnect_members]',
            array(
                'label'       => __('Restrict to Selected Members', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[RCPConnect_members]',
                'description' => __('Select the members that emails will only be delivered to.', 'mailoptin'),
                'choices'     => (function () {
                    $members = $this->get_members();
                    $output  = [];
                    foreach ($members as $user_id) {
                        $user             = get_userdata($user_id);
                        $output[$user_id] = $user->display_name . " ($user->user_email)";
                    }

                    return $output;
                })(),
                'priority'    => 64
            )
        );

        $controls['RCPConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[RCPConnect_notice]',
            array(
                'content'  => esc_html__('Leave all "Restrict to ..." settings empty to send to all members.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[RCPConnect_notice]',
                'priority' => 65,
            )
        );

        return $controls;
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
        $active_memberships    = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'RCPConnect_active_memberships', []);
        $expired_memberships   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'RCPConnect_expired_memberships', []);
        $cancelled_memberships = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'RCPConnect_cancelled_memberships', []);
        $members               = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'RCPConnect_members', []);

        $bucket = [];

        if (empty($active_memberships) && empty($expired_memberships) && empty($cancelled_memberships) && empty($members)) {

            $users = $this->get_members();

            if ( ! empty($users)) {

                foreach ($users as $user_id) {

                    if (in_array($user_id, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = get_userdata($user_id)->user_email;
                    $bucket[]         = $user_id;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->mp_bg_process_instance->push_to_queue($item);
                }
            }

        } else {

            if (is_array($active_memberships) && ! empty($active_memberships)) {

                foreach ($active_memberships as $membership_level) {

                    $_users = $this->get_members($membership_level, 'active');

                    if ( ! empty($_users)) {

                        foreach ($_users as $user_id) {

                            if (in_array($user_id, $bucket)) continue;

                            $item             = new \stdClass();
                            $item->user_email = get_userdata($user_id)->user_email;
                            $bucket[]         = $user_id;

                            $item->email_campaign_id = $email_campaign_id;
                            $item->campaign_log_id   = $campaign_log_id;

                            $this->mp_bg_process_instance->push_to_queue($item);
                        }
                    }
                }
            }

            if (is_array($cancelled_memberships) && ! empty($cancelled_memberships)) {

                foreach ($cancelled_memberships as $membership_level) {

                    $_users = $this->get_members($membership_level, 'cancelled');

                    if ( ! empty($_users)) {

                        foreach ($_users as $user_id) {

                            if (in_array($user_id, $bucket)) continue;

                            $item             = new \stdClass();
                            $item->user_email = get_userdata($user_id)->user_email;
                            $bucket[]         = $user_id;

                            $item->email_campaign_id = $email_campaign_id;
                            $item->campaign_log_id   = $campaign_log_id;

                            $this->mp_bg_process_instance->push_to_queue($item);
                        }
                    }
                }
            }

            if (is_array($expired_memberships) && ! empty($expired_memberships)) {

                foreach ($expired_memberships as $membership_level) {

                    $_users = $this->get_members($membership_level, 'expired');

                    if ( ! empty($_users)) {

                        foreach ($_users as $user_id) {

                            if (in_array($user_id, $bucket)) continue;

                            $item             = new \stdClass();
                            $item->user_email = get_userdata($user_id)->user_email;
                            $bucket[]         = $user_id;

                            $item->email_campaign_id = $email_campaign_id;
                            $item->campaign_log_id   = $campaign_log_id;

                            $this->mp_bg_process_instance->push_to_queue($item);
                        }
                    }
                }
            }

            if ( ! empty($members)) {

                foreach ($members as $user_id) {

                    if (in_array($user_id, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = get_userdata($user_id)->user_email;
                    $bucket[]         = $user_id;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->mp_bg_process_instance->push_to_queue($item);
                }
            }
        }

        $this->mp_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                     ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_rcp_unsubscribe']) || empty($_GET['mo_rcp_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_rcp_unsubscribe']);

        $contacts   = get_option('mo_rcp_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_rcp_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_rcp_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_rcp_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
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