<?php

namespace MailOptin\TutorLMSConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var Mail_BG_Process
     */
    public $tutor_bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'TutorLMSConnect';


    public function __construct()
    {
        add_action('plugins_loaded', function () {

            if (class_exists('\TUTOR\Tutor')) {
                Course::get_instance();
                TutorInit::get_instance();

                add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
                add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
                add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

                $this->tutor_bg_process_instance = new Mail_BG_Process();

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
        $connections[self::$connectionName] = __('Tutor LMS', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['TutorLMSConnect_courses'] = [
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        ];

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
        $controls['TutorLMSConnect_courses'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[TutorLMSConnect_courses]',
            array(
                'label'       => __('Restrict to Courses', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[TutorLMSConnect_courses]',
                'description' => __('Select the courses whose enrolled users will receive the emails from this campaign.', 'mailoptin'),
                'choices'     => $this->get_courses(),
                'priority'    => 62
            )
        );

        return $controls;
    }

    protected function get_courses()
    {
        $args = [
            'post_type'   => 'courses',
            'post_status' => 'publish',
            'numberposts' => -1,
        ];

        $courses = get_posts($args);
        $options = [];

        foreach ($courses as $course) {
            $options[$course->ID] = $course->post_title;
        }

        return $options;
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
        $courses = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'TutorLMSConnect_courses', []);

        $bucket = [];

        if (is_array($courses) && ! empty($courses)) {

            foreach ($courses as $course_id) {

                $_offset = 0;
                $_loop   = true;
                $_limit  = 1000;

                while ($_loop === true) {

                    $_users = tutor_utils()->get_students($_offset, $_limit, '', $course_id);

                    if ( ! empty($_users)) {

                        foreach ($_users as $_user) {

                            if (in_array($_user->user_email, $bucket)) continue;

                            $_item             = new \stdClass();
                            $_item->user_email = $_user->user_email;
                            $bucket[]          = $_user->user_email;

                            $_item->email_campaign_id = $email_campaign_id;
                            $_item->campaign_log_id   = $campaign_log_id;

                            $this->tutor_bg_process_instance->push_to_queue($_item);
                        }

                        if (count($_users) < $_limit) {
                            $_loop = false;
                        }

                        $_offset += $_limit;

                    } else {
                        $_loop = false;
                    }
                }
            }
        }

        $this->tutor_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                        ->mo_dispatch($campaign_log_id, $email_campaign_id);


        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_tutorlms_unsubscribe']) || empty($_GET['mo_tutorlms_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_tutorlms_unsubscribe']);

        $contacts   = get_option('mo_tutorlms_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_tutorlms_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_tutorlms_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_tutorlms_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

        wp_die($success_message, $success_message, ['response' => 200]);
    }

    /**
     * @return self
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