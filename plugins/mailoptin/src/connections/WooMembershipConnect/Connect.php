<?php

namespace MailOptin\WooMembershipConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_WOOCOMMERCE_MEMBERSHIPS_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/WooMembershipConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_WOOCOMMERCE_MEMBERSHIPS_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var WCMembershipMailBGProcess
     */
    public $wc_ms_bg_process_instance;
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'WooMembershipConnect';

    public function __construct()
    {
        add_action('plugins_loaded', function () {

            if (class_exists('\WC_Memberships_Loader')) {

                OptinSubscription::get_instance();

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);
                add_filter('mo_page_targeting_search_response', [$this, 'select2_search'], 10, 3);

                $this->wc_ms_bg_process_instance = new WCMembershipMailBGProcess();

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
        $connections[self::$connectionName] = __('WooCommerce Memberships', 'mailoptin');

        return $connections;
    }

    public function select2_search($response, $search_type, $q)
    {
        if ($search_type == 'WooMembershipConnect_members') {
            $members = $this->get_members(500, '', '', $q);

            if (is_array($members) && ! empty($members)) {
                $response = [];
                foreach ($members as $member) {
                    $response[$member->ID] = $member->display_name . " ($member->user_email)";
                }
            }
        }

        return $response;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings_bucket = [
            'WooMembershipConnect_memberships',
            'WooMembershipConnect_members',
            'WooMembershipConnect_expired_memberships',
            'WooMembershipConnect_cancelled_memberships',
            'WooMembershipConnect_paused_memberships',
            'WooMembershipConnect_notice'
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

    public function get_membership_plans()
    {
        static $cache = [];

        if (empty($cache)) {

            $options = [];

            foreach (wc_memberships_get_membership_plans() as $plan_id => $plan) {
                $options[$plan_id] = $plan->name;
            }

            $cache = $options;
        }

        return $cache;
    }

    function get_members($limit = 0, $plan_id = '', $status = '', $search = '', $page = 0)
    {
        global $wpdb;
        $replacement = ['1'];
        $sql         = "SELECT DISTINCT u.user_email, u.display_name, u.ID FROM {$wpdb->posts} as p
                        LEFT JOIN {$wpdb->users} as u ON p.post_author = u.ID
                        WHERE 1 = %d
                        AND p.post_type = 'wc_user_membership'";

        if ( ! empty($status)) {
            $replacement[] = $status;
            $sql           .= " AND p.post_status = %s";
        }

        if ( ! empty($plan_id)) {
            $replacement[] = $plan_id;
            $sql           .= " AND p.post_parent = %d";
        }

        if ( ! empty($search)) {
            $search        = '%' . $wpdb->esc_like(sanitize_text_field($search)) . '%';
            $replacement[] = $search;
            $replacement[] = $search;
            $replacement[] = $search;
            $sql           .= " AND (u.user_login LIKE %s OR u.user_email LIKE %s OR u.display_name LIKE %s)";
        }

        if ($limit > 0) {
            $replacement[] = $limit;
            $sql           .= " LIMIT %d";
        }

        if ($limit > 0 && $page > 0) {
            $replacement[] = ($page - 1) * $limit;
            $sql           .= " OFFSET %d";
        }

        return $wpdb->get_results($wpdb->prepare($sql, $replacement));
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
        $controls['WooMembershipConnect_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_memberships]',
            [
                'label'       => __('Restrict to Active Membership Plans', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooMembershipConnect_memberships]',
                'description' => __('Select the membership plans whose active members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_plans(),
                'priority'    => 62
            ]
        );

        $controls['WooMembershipConnect_expired_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_expired_memberships]',
            [
                'label'       => __('Restrict to Expired Membership Plans', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooMembershipConnect_expired_memberships]',
                'description' => __('Select the membership plans whose expired members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_plans(),
                'priority'    => 63
            ]
        );

        $controls['WooMembershipConnect_cancelled_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_cancelled_memberships]',
            [
                'label'       => __('Restrict to Cancelled Membership Plans', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooMembershipConnect_cancelled_memberships]',
                'description' => __('Select the membership plans whose cancelled members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_plans(),
                'priority'    => 64
            ]
        );

        $controls['WooMembershipConnect_paused_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_paused_memberships]',
            [
                'label'       => __('Restrict to Paused Membership Plans', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooMembershipConnect_paused_memberships]',
                'description' => __('Select the membership plans whose paused members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_membership_plans(),
                'priority'    => 65
            ]
        );

        $controls['WooMembershipConnect_members'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_members]',
            [
                'label'       => __('Restrict to Selected Members', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[WooMembershipConnect_members]',
                'description' => __('Select the members that emails will be delivered to.', 'mailoptin'),
                'search_type' => 'WooMembershipConnect_members',
                'choices'     => (function () {
                    $members = $this->get_members(500);
                    $bucket  = [];
                    foreach ($members as $member) {
                        $bucket[$member->ID] = $member->display_name . " ($member->user_email)";
                    }

                    return $bucket;
                })(),
                'priority'    => 66
            ]
        );

        $controls['WooMembershipConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[WooMembershipConnect_notice]',
            array(
                'content'  => esc_html__('Leave "Restrict to Active Membership Plans", "Restrict to Expired Membership Plans", "Restrict to Cancelled Membership Plans", "Restrict to Paused Membership Plans" and "Restrict to Selected Members" empty to send to all members.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[WooMembershipConnect_notice]',
                'priority' => 67,
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
        $active_membership_plans    = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooMembershipConnect_memberships', []);
        $expired_membership_plans   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooMembershipConnect_expired_memberships', []);
        $cancelled_membership_plans = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooMembershipConnect_cancelled_memberships', []);
        $paused_membership_plans    = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooMembershipConnect_paused_memberships', []);

        $members = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'WooMembershipConnect_members', []);

        $bucket[] = [];

        if (empty($active_membership_plans) && empty($expired_membership_plans) && empty($cancelled_membership_plans) && empty($paused_membership_plans) && empty($members)) {

            $page  = 1;
            $loop  = true;
            $limit = 2000;

            while ($loop === true) {

                $_users = $this->get_members($limit, '', '', '', $page);

                if ( ! empty($_users)) {

                    foreach ($_users as $_user) {

                        if (in_array($_user->user_email, $bucket)) continue;

                        $item             = new \stdClass();
                        $item->user_email = $_user->user_email;
                        $bucket[]         = $_user->user_email;

                        $item->email_campaign_id = $email_campaign_id;
                        $item->campaign_log_id   = $campaign_log_id;

                        $this->wc_ms_bg_process_instance->push_to_queue($item);
                    }
                }

                if (count($_users) < $limit) {
                    $loop = false;
                }

                $page++;
            }

        } else {

            if (is_array($active_membership_plans) && ! empty($active_membership_plans)) {

                foreach ($active_membership_plans as $plan_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $users = $this->get_members($_limit, $plan_id, 'wcm-active', '', $_page);

                        if ( ! empty($users)) {

                            foreach ($users as $user) {

                                if (in_array($user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $user->user_email;
                                $bucket[]         = $user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_ms_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($cancelled_membership_plans) && ! empty($cancelled_membership_plans)) {

                foreach ($cancelled_membership_plans as $plan_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $users = $this->get_members($_limit, $plan_id, 'wcm-cancelled', '', $_page);

                        if ( ! empty($users)) {

                            foreach ($users as $user) {

                                if (in_array($user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $user->user_email;
                                $bucket[]         = $user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_ms_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($paused_membership_plans) && ! empty($paused_membership_plans)) {

                foreach ($paused_membership_plans as $plan_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $users = $this->get_members($_limit, $plan_id, 'wcm-paused', '', $_page);

                        if ( ! empty($users)) {

                            foreach ($users as $user) {

                                if (in_array($user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $user->user_email;
                                $bucket[]         = $user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_ms_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($expired_membership_plans) && ! empty($expired_membership_plans)) {

                foreach ($expired_membership_plans as $plan_id) {

                    $_page  = 1;
                    $_loop  = true;
                    $_limit = 2000;

                    while ($_loop === true) {

                        $users = $this->get_members($_limit, $plan_id, 'wcm-expired', '', $_page);

                        if ( ! empty($users)) {

                            foreach ($users as $user) {

                                if (in_array($user->user_email, $bucket)) continue;

                                $item             = new \stdClass();
                                $item->user_email = $user->user_email;
                                $bucket[]         = $user->user_email;

                                $item->email_campaign_id = $email_campaign_id;
                                $item->campaign_log_id   = $campaign_log_id;

                                $this->wc_ms_bg_process_instance->push_to_queue($item);
                            }
                        }

                        if (count($users) < $_limit) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if ( ! empty($members)) {

                foreach ($members as $member) {

                    $user = get_userdata(absint($member));

                    if (in_array($user->user_email, $bucket)) continue;

                    $item             = new \stdClass();
                    $item->user_email = $user->user_email;
                    $bucket[]         = $user->user_email;

                    $item->email_campaign_id = $email_campaign_id;
                    $item->campaign_log_id   = $campaign_log_id;

                    $this->wc_ms_bg_process_instance->push_to_queue($item);
                }
            }
        }

        $this->wc_ms_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                        ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_memberpress_unsubscribe']) || empty($_GET['mo_memberpress_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_memberpress_unsubscribe']);

        $contacts   = get_option('mo_memberpress_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_memberpress_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_memberpress_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_memberpress_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

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