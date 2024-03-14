<?php

namespace MailOptin\LifterLMSConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Repositories\EmailCampaignRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_LLMS_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/LifterLMSConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_LLMS_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class Connect extends \MailOptin\RegisteredUsersConnect\Connect
{
    /**
     * @var Mail_BG_Process
     */
    public $llms_bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'LifterLMSConnect';

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init()
    {
        if (function_exists('llms') && version_compare('5.0', LLMS()->version, '<=')) {
            add_filter('lifterlms_integrations', array($this, 'register_integration'), 10, 1);

            CourseSidebarSettings::get_instance();

            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('admin_footer', [$this, 'admin_scripts']);
            add_filter('mo_mailoptin_js_globals', [$this, 'set_global_variables'], 10, 1);

            add_action('wp_ajax_mo_llms_fetch_lists', [$this, 'fetch_lists']);
            add_action('wp_ajax_mo_llms_fetch_custom_fields', [$this, 'fetch_custom_fields']);

            add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
            add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
            add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

            $this->llms_bg_process_instance = new Mail_BG_Process();

            add_action('init', [$this, 'unsubscribe_handler']);
            add_action('init', [$this, 'view_online_version']);
        }
    }

    /**
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('LifterLMS', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['LifterLMSConnect_enrolled_courses'] = [
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        ];

        $settings['LifterLMSConnect_cancelled_courses'] = [
            'default'   => '',
            'type'      => 'option',
            'transport' => 'postMessage',
        ];

        $settings['LifterLMSConnect_expired_courses'] = [
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
        $choices = (function () {
            $options     = [];
            $courses     = $this->get_courses_memberships();
            $memberships = $this->get_courses_memberships(true);

            if ( ! empty($courses)) {
                $options[esc_html__('Courses', 'mailoptin')] = $courses;
            }

            if ( ! empty($memberships)) {
                $options[esc_html__('Memberships', 'mailoptin')] = $memberships;
            }

            return $options;

        })();

        // always prefix with the name of the connect/connection service.
        $controls['LifterLMSConnect_enrolled_courses'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[LifterLMSConnect_enrolled_courses]',
            array(
                'label'       => __('Restrict to Enrolled Courses/Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[LifterLMSConnect_enrolled_courses]',
                'description' => __('Select the courses whose enrolled users will receive the emails from this campaign.', 'mailoptin'),
                'choices'     => $choices,
                'priority'    => 62
            )
        );

        $controls['LifterLMSConnect_cancelled_courses'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[LifterLMSConnect_cancelled_courses]',
            array(
                'label'       => __('Restrict to Cancelled Courses/Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[LifterLMSConnect_cancelled_courses]',
                'description' => __('Select the courses whose cancelled users will receive the emails from this campaign.', 'mailoptin'),
                'choices'     => $choices,
                'priority'    => 63
            )
        );

        $controls['LifterLMSConnect_expired_courses'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[LifterLMSConnect_expired_courses]',
            array(
                'label'       => __('Restrict to Expired Courses/Memberships', 'mailoptin'),
                'section'     => $customizerClassInstance->campaign_settings_section_id,
                'settings'    => $option_prefix . '[LifterLMSConnect_expired_courses]',
                'description' => __('Select the courses whose expired users will receive the emails from this campaign.', 'mailoptin'),
                'choices'     => $choices,
                'priority'    => 64
            )
        );

        return $controls;
    }

    public function register_integration($integrations)
    {
        $integrations[] = 'MailOptin\LifterLMSConnect\MOLifterLMS';

        return $integrations;
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['llms_post_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if (in_array($post->post_type, ['course', 'llms_membership'])) {
                wp_enqueue_script('mailoptin-llms', MAILOPTIN_LLMS_CONNECT_ASSETS_URL . 'lifterlms.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
                wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);

                wp_localize_script('mailoptin-llms', 'moLLMS', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-llms'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();

        if (strpos($screen->id, 'llms') !== false) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);
            wp_enqueue_style('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
            wp_enqueue_script('mailoptin-llms-settings', MAILOPTIN_LLMS_CONNECT_ASSETS_URL . 'settings.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);
        }
    }

    public function admin_scripts()
    {
        global $post;

        if ( ! empty($post)) {

            if (in_array($post->post_type, ['course', 'llms_membership'])) {
                ob_start();
                ?>
                <style>
                    .mo-llms-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-llms-form-field label {
                        display: block;
                    }

                    .mo-llms-form-field select,
                    .mo-llms-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-llms-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-llms-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-llms-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-llms-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-llms-form-field .select2-container .select2-selection {
                        width: 225px;
                        border-color: #c3c4c7;
                    }
                </style>
                <?php
                echo mo_minify_css(ob_get_clean());
            }
        }

        $screen = get_current_screen();

        if (strpos($screen->id, 'llms') !== false) {
            ob_start();
            ?>
            <style>
                .mo_llms_select2 .select2 {
                    min-width: 300px;
                }

                .mailoptin_llms_fields label {
                    display: block;
                }

                .mailoptin_llms_sub_header {
                    position: relative;
                }

                .mailoptin_llms_sub_header th {
                    line-height: 0.1em;
                    margin: 10px 0 20px;
                    text-align: left;
                    border-bottom: 1px solid #c3c4c7;
                    padding: 9px 0 0;
                    position: absolute;
                    width: 100%;
                }

                .mailoptin_llms_sub_header th strong {
                    background: #F0F0F1;
                    padding-right: 10px;
                }

                .mo-llms-upsell-block {
                    background-color: #d9edf7;
                    border: 1px solid #bce8f1;
                    box-sizing: border-box;
                    color: #31708f;
                    outline: 0;
                    margin: 10px;
                    padding: 10px;
                }

                .mo-llms-upsell-block p {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                }
            </style>
            <?php
            echo mo_minify_css(ob_get_clean());
        }
    }

    /**
     * @return array
     */
    public static function select_integration_options()
    {
        $integrations = self::email_service_providers();

        if ( ! empty($integrations)) {

            $options[''] = esc_html__('Select...', 'mailoptin');

            foreach ($integrations as $value => $label) {

                if (empty($value)) continue;

                // Add list to select options.
                $options[$value] = $label;
            }

            return $options;
        }

        return [];
    }

    /**
     * @return mixed
     */
    public static function email_service_providers()
    {
        $connections = ConnectionsRepository::get_connections();

        $connections['leadbank'] = __('MailOptin Leads', 'mailoptin');

        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
    }

    /**
     * @return array
     */
    public static function email_list_options($saved_integration)
    {
        $lists = [];
        if ( ! empty($saved_integration) && $saved_integration != 'leadbank') {
            $lists = ConnectionFactory::make($saved_integration)->get_email_list();
        }

        if ( ! empty($lists)) {

            $options[''] = esc_html__('Select...', 'mailoptin');

            foreach ($lists as $value => $label) {

                if (empty($value)) continue;

                // Add list to select options.
                $options[$value] = $label;
            }

            return $options;
        }

        return [];
    }

    public static function double_optin_settings($saved_integration, $field_id = 'mo_llms_doi')
    {
        if (empty($saved_integration)) return false;

        $is_double_optin          = false;
        $double_optin_connections = Init::double_optin_support_connections();
        foreach ($double_optin_connections as $key => $value) {
            if ($saved_integration === $key) {
                $is_double_optin = $value;
            }
        }

        if (in_array($saved_integration, Init::double_optin_support_connections(true))) {

            return [
                'desc_tip' => true,
                'id'       => $field_id,
                'name'     => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'desc'     => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'type'     => 'checkbox'
            ];
        }

        return [];
    }

    /**
     * Retrieve an array of LifterLMS core custom fields from the checkout & registration screens.
     *
     * @return array
     */
    public static function get_core_fields()
    {
        $fields = ['' => '&mdash;&mdash;&mdash;'];

        $fields_raw = [];

        if (class_exists('LLMS_Forms')) {
            $fields_raw = array_merge(
                \LLMS_Forms::instance()->get_form_fields('checkout'),
                \LLMS_Forms::instance()->get_form_fields('registration')
            );
        }

        $exclude = array('email_address', 'email_address_confirm', 'password', 'password_confirm', 'llms_voucher');

        foreach ($fields_raw as $key => $field) {

            $field_key = ! empty($field['data_store_key']) ? $field['data_store_key'] : 'id';

            if (in_array($field['id'], $exclude, true) ||
                array_key_exists($field_key, $fields) ||
                (empty($field['label']) && 'llms_billing_address_2' !== $field['id']) ||
                (isset($field['data_store']) && ! in_array($field['data_store'], array('usermeta', 'users'), true))) {
                continue;
            }

            $fields[$field_key] = 'llms_billing_address_2' !== $field_key ? $field['label'] : __('Street Address 2', 'mailoptin');
        }

        return $fields;
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-llms', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        if (empty($_POST['post_id'])) wp_send_json_error([]);

        ob_start();

        $post_id = absint($_POST['post_id']);

        $saved_double_optin    = get_post_meta($post_id, $connection . '[mailoptinLLMSDoubleOptin]', true);
        $double_optin_settings = self::double_optin_settings($saved_double_optin, $connection);

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = self::email_list_options($connection);
            $saved_lists = get_post_meta($post_id, $connection . '[mailoptinLLMSSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        CourseSidebarSettings::mailoptin_select_field(
            [
                'id'          => 'mailoptinLLMSSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list or audience to add students.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            CourseSidebarSettings::mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-llms', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['post_id'])) wp_send_json_error([]);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        $post_id = absint($_POST['post_id']);
        ?>
        <h2 class="mo-llms-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinLLMSMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($post_id, $connection . '[' . $mapped_key . ']', true);

            CourseSidebarSettings::mailoptin_select_field([
                'id'      => $mapped_key,
                'label'   => $value,
                'value'   => $saved_mapped_field,
                'options' => Connect::get_core_fields(),
            ]);
        }

        $saved_tags = '';
        if (in_array($connection, Init::text_tag_connections())) {
            $tags_key   = $connection . '[mailoptinLLMSTextTags]';
            $saved_tags = get_post_meta($post_id, $tags_key, true);
        } elseif (in_array($connection, Init::select2_tag_connections())) {
            $tags_key   = $connection . '[mailoptinLLMSSelectTags]';
            $saved_tags = json_decode(get_post_meta($post_id, $tags_key, true));
        }

        CourseSidebarSettings::lead_tag_settings($saved_tags, $connection);

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function get_courses_memberships($membership_type = false)
    {
        $post_type = $membership_type === false ? 'course' : 'llms_membership';

        $args = array(
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
        );

        $courses = get_posts($args);
        $options = array();

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
        $enrolled_courses  = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'LifterLMSConnect_enrolled_courses', []);
        $cancelled_courses = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'LifterLMSConnect_cancelled_courses', []);
        $expired_courses   = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'LifterLMSConnect_expired_courses', []);

        $bucket = [];

        if (is_array($enrolled_courses) && ! empty($enrolled_courses)) {

            foreach ($enrolled_courses as $course_id) {

                $_offset = 0;
                $_loop   = true;
                $_limit  = 2000;

                while ($_loop === true) {

                    $_users = llms_get_enrolled_students($course_id, 'enrolled', $_limit, $_offset);

                    if ( ! empty($_users) && is_array($_users)) {

                        $_users = array_map(function ($user_id) {
                            return get_userdata($user_id);
                        }, $_users);

                        foreach ($_users as $_user) {

                            if (in_array($_user->user_email, $bucket)) continue;

                            $_item             = new \stdClass();
                            $_item->user_email = $_user->user_email;
                            $bucket[]          = $_user->user_email;

                            $_item->email_campaign_id = $email_campaign_id;
                            $_item->campaign_log_id   = $campaign_log_id;

                            $this->llms_bg_process_instance->push_to_queue($_item);
                        }

                        if (count($_users) < $_limit) $_loop = false;

                        $_offset += $_limit;

                    } else {
                        $_loop = false;
                    }
                }
            }
        }

        if (is_array($cancelled_courses) && ! empty($cancelled_courses)) {

            foreach ($cancelled_courses as $course_id) {

                $_offset = 0;
                $_loop   = true;
                $_limit  = 2000;

                while ($_loop === true) {

                    $_users = llms_get_enrolled_students($course_id, 'cancelled', $_limit, $_offset);

                    if ( ! empty($_users) && is_array($_users)) {

                        $_users = array_map(function ($user_id) {
                            return get_userdata($user_id);
                        }, $_users);

                        foreach ($_users as $_user) {

                            if (in_array($_user->user_email, $bucket)) continue;

                            $_item             = new \stdClass();
                            $_item->user_email = $_user->user_email;
                            $bucket[]          = $_user->user_email;

                            $_item->email_campaign_id = $email_campaign_id;
                            $_item->campaign_log_id   = $campaign_log_id;

                            $this->llms_bg_process_instance->push_to_queue($_item);
                        }

                        if (count($_users) < $_limit) $_loop = false;

                        $_offset += $_limit;

                    } else {
                        $_loop = false;
                    }
                }
            }
        }

        if (is_array($expired_courses) && ! empty($expired_courses)) {

            foreach ($expired_courses as $course_id) {

                $_offset = 0;
                $_loop   = true;
                $_limit  = 2000;

                while ($_loop === true) {

                    $_users = llms_get_enrolled_students($course_id, 'expired', $_limit, $_offset);

                    if ( ! empty($_users) && is_array($_users)) {

                        $_users = array_map(function ($user_id) {
                            return get_userdata($user_id);
                        }, $_users);

                        foreach ($_users as $_user) {

                            if (in_array($_user->user_email, $bucket)) continue;

                            $_item             = new \stdClass();
                            $_item->user_email = $_user->user_email;
                            $bucket[]          = $_user->user_email;

                            $_item->email_campaign_id = $email_campaign_id;
                            $_item->campaign_log_id   = $campaign_log_id;

                            $this->llms_bg_process_instance->push_to_queue($_item);
                        }

                        if (count($_users) < $_limit) $_loop = false;

                        $_offset += $_limit;

                    } else {
                        $_loop = false;
                    }
                }
            }
        }

        $this->llms_bg_process_instance->mo_save($campaign_log_id, $email_campaign_id)
                                       ->mo_dispatch($campaign_log_id, $email_campaign_id);

        return ['success' => true];
    }

    public function unsubscribe_handler()
    {
        if ( ! isset($_GET['mo_lifterlms_unsubscribe']) || empty($_GET['mo_lifterlms_unsubscribe'])) return;

        $email = sanitize_text_field($_GET['mo_lifterlms_unsubscribe']);

        $contacts   = get_option('mo_lifterlms_unsubscribers', []);
        $contacts[] = $email;

        update_option('mo_lifterlms_unsubscribers', $contacts, false);

        $this->delete_unsubscribe_leadbank_contact($email);

        do_action('mo_lifterlms_unsubscribe', $contacts, $email);

        $success_message = apply_filters('mo_lifterlms_unsubscribe_message', esc_html__("You've successfully been unsubscribed.", 'mailoptin'));

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