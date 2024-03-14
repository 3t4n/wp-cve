<?php

namespace MailOptin\MemberPressConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var WP_MemberPress_Mail_BG_Process
     */
    public $mp_bg_process_instance;
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'MemberPressConnect';

    public function __construct()
    {
        add_action('plugins_loaded', function () {

            if (class_exists('MeprAppCtrl')) {
                Membership::get_instance();
                MemberPressSettings::get_instance();
                MemberPressInit::get_instance();

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

                $this->mp_bg_process_instance = new WP_MemberPress_Mail_BG_Process();

                add_action('init', [$this, 'unsubscribe_handler']);
                add_action('init', [$this, 'view_online_version']);

                add_filter('mo_new_publish_post_loop_check', [$this, 'check_membership_post_author'], 10, 3);
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
        $connections[self::$connectionName] = __('MemberPress', 'mailoptin');

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
            'memberpress_post_author',
            'MemberPressConnect_memberships',
            'MemberPressConnect_inactive_memberships',
            'MemberPressConnect_members',
            'MemberPressConnect_notice'
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

    public function get_memberships()
    {
        static $cache = null;

        if (is_null($cache)) {
            $args = array(
                'post_type'   => 'memberpressproduct',
                'post_status' => 'publish',
                'numberposts' => -1,
            );

            $memberships = get_posts($args);

            $options = array();

            foreach ($memberships as $membership) {
                $options[$membership->ID] = $membership->post_title;
            }

            $cache = $options;
        }

        return $cache;
    }

    /**
     * @param bool $boolean
     * @param \WP_Post $post
     * @param int $email_campaign_id
     *
     * @return bool
     */
    public function check_membership_post_author($boolean, $post, $email_campaign_id)
    {
        $author_memberships = EmailCampaignRepository::get_merged_customizer_value($email_campaign_id, 'memberpress_post_author');

        if ( ! empty($author_memberships)) {

            $member_memberships = (new \MeprUser($post->post_author))->active_product_subscriptions('ids', true);

            return ! empty($member_memberships) &&
                   count(array_intersect($member_memberships, $author_memberships)) > 0;
        }

        return $boolean;
    }

    public function get_members()
    {
        static $cache = null;

        if (is_null($cache)) {

            $members = \MeprUser::all('objects', [], '', 999);

            if ( ! is_array($members) || empty($members)) return [];

            $output = [];
            foreach ($members as $member) {
                $output[$member->ID] = $member->full_name() . " ($member->user_email)";
            }

            $cache = $output;
        }

        return $cache;
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
        // restricting to New post notification only cause email digest seamingly is impossible to do.
        if ($customizerClassInstance->email_campaign_type == EmailCampaignRepository::NEW_PUBLISH_POST) {
            // MemberPressConnect prefix isn't used because we dont want the control hidden when integration dropdown is changed.
            $controls['memberpress_post_author'] = new WP_Customize_Chosen_Select_Control(
                $wp_customize,
                $option_prefix . '[memberpress_post_author]', [
                    'label'       => __('Restrict to Authors of Selected Memberships (MemberPress)', 'mailoptin'),
                    'description' => __('Only include posts that are published by authors that belongs to selected memberships in MemberPress.', 'mailoptin'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[memberpress_post_author]',
                    'choices'     => $this->get_memberships(),
                    'priority'    => 47
                ]
            );
        }

        // always prefix with the name of the connect/connection service.
        $controls['MemberPressConnect_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[MemberPressConnect_memberships]',
            array(
                'label'       => __('Restrict to Active Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[MemberPressConnect_memberships]',
                'description' => __('Select the memberships whose active members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_memberships(),
                'priority'    => 62
            )
        );

        $controls['MemberPressConnect_inactive_memberships'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[MemberPressConnect_inactive_memberships]',
            array(
                'label'       => __('Restrict to Inactive/Expired Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[MemberPressConnect_inactive_memberships]',
                'description' => __('Select the memberships whose inactive and expired members will receive emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_memberships(),
                'priority'    => 63
            )
        );

        $controls['MemberPressConnect_members'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[MemberPressConnect_members]',
            array(
                'label'       => __('Restrict to Selected Members', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[MemberPressConnect_members]',
                'description' => __('Select the members that emails will only be delivered to.', 'mailoptin'),
                'search_type' => 'MemberPressConnect_members',
                'choices'     => $this->get_members(),
                'priority'    => 64
            )
        );

        $controls['MemberPressConnect_notice'] = new WP_Customize_Custom_Content(
            $wp_customize,
            $option_prefix . '[MemberPressConnect_notice]',
            array(
                'content'  => esc_html__('Leave "Restrict to Active Memberships", "Restrict to Inactive/Expired Memberships" and "Restrict to Selected Members" empty to send to all members.', 'mailoptin'),
                'section'  => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[MemberPressConnect_notice]',
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
        $active_memberships   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'MemberPressConnect_memberships', []);
        $inactive_memberships = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'MemberPressConnect_inactive_memberships', []);
        $members              = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'MemberPressConnect_members', []);

        $bucket = [];

        if (empty($active_memberships) && empty($inactive_memberships) && empty($members)) {

            $page = 1;
            $loop = true;

            while ($loop === true) {

                $users = \MeprUser::list_table('', '', $page, '', 'any', '2000');

                if ( ! empty($users['results'])) {

                    foreach ($users['results'] as $user) {

                        if (in_array($user->email, $bucket)) continue;

                        $item             = new \stdClass();
                        $item->user_email = $user->email;
                        $bucket[]         = $user->email;

                        $item->email_campaign_id = $email_campaign_id;
                        $item->campaign_log_id   = $campaign_log_id;

                        $this->mp_bg_process_instance->push_to_queue($item);
                    }
                }

                if (count($users['results']) < 2000) {
                    $loop = false;
                }

                $page++;
            }

        } else {

            if (is_array($active_memberships) && ! empty($active_memberships)) {

                foreach ($active_memberships as $membership) {

                    $_page = 1;
                    $_loop = true;

                    while ($_loop === true) {

                        $_users = \MeprUser::list_table('', '', $_page, '', 'any', '2000', ['membership' => absint($membership), 'status' => 'active']);

                        if ( ! empty($_users['results'])) {

                            foreach ($_users['results'] as $_user) {

                                if (in_array($_user->email, $bucket)) continue;

                                $_item             = new \stdClass();
                                $_item->user_email = $_user->email;
                                $bucket[]          = $_user->email;

                                $_item->email_campaign_id = $email_campaign_id;
                                $_item->campaign_log_id   = $campaign_log_id;

                                $this->mp_bg_process_instance->push_to_queue($_item);
                            }
                        }

                        if (count($_users['results']) < 2000) {
                            $_loop = false;
                        }

                        $_page++;
                    }
                }
            }

            if (is_array($inactive_memberships) && ! empty($inactive_memberships)) {

                foreach ($inactive_memberships as $inactive_membership) {

                    $__page = 1;
                    $__loop = true;

                    while ($__loop === true) {

                        $_users = \MeprUser::list_table('', '', $__page, '', 'any', '2000', ['membership' => absint($inactive_membership), 'status' => 'inactive']);

                        if ( ! empty($_users['results'])) {

                            foreach ($_users['results'] as $_user) {

                                if (in_array($_user->email, $bucket)) continue;

                                $_item             = new \stdClass();
                                $_item->user_email = $_user->email;
                                $bucket[]          = $_user->email;

                                $_item->email_campaign_id = $email_campaign_id;
                                $_item->campaign_log_id   = $campaign_log_id;

                                $this->mp_bg_process_instance->push_to_queue($_item);
                            }
                        }

                        if (count($_users['results']) < 2000) {
                            $__loop = false;
                        }

                        $__page++;
                    }

                    // reset loop
                    $__2page = 1;
                    $__2loop = true;

                    while ($__2loop === true) {

                        $_users = \MeprUser::list_table('', '', $__2page, '', 'any', '2000', ['membership' => absint($inactive_membership), 'status' => 'expired']);

                        if ( ! empty($_users['results'])) {

                            foreach ($_users['results'] as $_user) {

                                if (in_array($_user->email, $bucket)) continue;

                                $_item             = new \stdClass();
                                $_item->user_email = $_user->email;
                                $bucket[]          = $_user->email;

                                $_item->email_campaign_id = $email_campaign_id;
                                $_item->campaign_log_id   = $campaign_log_id;

                                $this->mp_bg_process_instance->push_to_queue($_item);
                            }
                        }

                        if (count($_users['results']) < 2000) {
                            $__2loop = false;
                        }

                        $__2page++;
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