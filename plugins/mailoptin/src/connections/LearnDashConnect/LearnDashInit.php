<?php

namespace MailOptin\LearnDashConnect;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_LEARNDASH_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/LearnDashConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_LEARNDASH_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class LearnDashInit
{
    public function __construct()
    {
        LearnDashSettings::get_instance();
        add_filter('mo_mailoptin_js_globals', [$this, 'set_learndash_global_variables'], 10, 1);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);

        add_action('wp_ajax_mo_learndash_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_learndash_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('init', [$this, 'course_subscribe_form_handler']);
        add_action('init', [$this, 'course_or_group_subscribe_form_handler']);

        add_action('learndash_update_course_access', [$this, 'auto_subscribe'], 10, 4);
        add_action('ld_added_group_access', array($this, 'auto_subscribe_group_access'), 10, 2);

        add_filter('learndash_payment_button', [$this, 'add_subscription_checkbox']);
        add_filter('ld_after_course_status_template_container', [$this, 'after_course_status'], 10, 1);
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if (in_array($page, ['sfwd-courses', 'groups'])) {
                wp_enqueue_script('mailoptin-learndash', MAILOPTIN_LEARNDASH_CONNECT_ASSETS_URL . 'learndash.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);

                wp_localize_script('mailoptin-learndash', 'moLearnDash', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-learndash'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-learndash-settings', MAILOPTIN_LEARNDASH_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);
            wp_localize_script('mailoptin-learndash-settings', 'moLearnDash', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-learndash'),
                'select2_tag_connections' => Init::select2_tag_connections(),
                'text_tag_connections'    => Init::text_tag_connections()
            ]);
        }
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_learndash_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['learndash_course_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function admin_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if (in_array($page, ['sfwd-courses', 'groups'])) {
                ob_start();
                ?>
                <style>
                    .mo-learndash-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-learndash-form-field label {
                        display: block;
                    }

                    .mo-learndash-form-field select,
                    .mo-learndash-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-learndash-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-learndash-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-learndash-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-learndash-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-learndash-form-field .select2-container .select2-selection {
                        width: 225px;
                        border-color: #c3c4c7;
                    }
                </style>
                <?php
                echo mo_minify_css(ob_get_clean());
            }
        }
    }


    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-learndash', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        if (empty($_POST['course_id'])) wp_send_json_error([]);

        ob_start();

        $course_id = absint($_POST['course_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = LearnDashInit::get_instance()->learndash_get_field($connection . '[mailoptinLearnDashDoubleOptin]', $course_id);
            $double_optin_settings = $this->learndash_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = LearnDashInit::get_instance()->learndash_get_field($connection . '[mailoptinLearnDashSelectList]', $course_id);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::ld_mailoptin_select_field(
            [
                'id'          => 'mailoptinLearnDashSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list, audience or segment to add students to.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::ld_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }


    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-learndash', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['course_id'])) wp_send_json_error([]);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        $course_id = absint($_POST['course_id']);
        ?>
        <h2 class="mo-learndash-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinLearnDashMappedFields-' . $key);
            $saved_mapped_field = LearnDashInit::get_instance()->learndash_get_field($connection . '[' . $mapped_key . ']', $course_id);

            self::ld_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->learndash_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinLearnDashTextTags]';
                $saved_tags = LearnDashInit::get_instance()->learndash_get_field($tags_key, $course_id);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinLearnDashSelectTags]';
                $saved_tags = json_decode(LearnDashInit::get_instance()->learndash_get_field($tags_key, $course_id), true);
            }
            $this->learndash_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @return false|void
     */
    public function learndash_lead_tag_settings($saved_tags, $saved_integration)
    {
        if (empty($saved_integration)) return false;

        if (in_array($saved_integration, Init::select2_tag_connections())) {

            $tags     = [];
            $instance = ConnectionFactory::make($saved_integration);
            if (is_object($instance) && method_exists($instance, 'get_tags')) {
                $tags = $instance->get_tags();
            }

            $options = [];

            foreach ($tags as $value => $label) {
                if (empty($value)) continue;

                $options[$value] = $label;
            }

            self::ld_mailoptin_select_field(
                [
                    'id'          => 'mailoptinLearnDashSelectTags',
                    'name'        => 'mailoptinLearnDashSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'molearndash_select2',
                    'description' => esc_html__('Select tags to assign to enrolled students.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.molearndash_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::ld_mailoptin_text_input(
                [
                    'id'          => 'mailoptinLearnDashTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to enrolled students. The course title is automatically included in the list of tags.', 'mailoptin'),
                ]
            );
        }
    }

    public static function ld_mailoptin_select_field($field)
    {
        $field = wp_parse_args(
            $field, array(
                'class'    => 'select short',
                'value'    => ! empty($field['value']) ? $field['value'] : [],
                'name'     => $field['id'],
                'multiple' => '',
            )
        );

        $field_attributes['id']    = $field['id'];
        $field_attributes['name']  = $field['name'];
        $field_attributes['class'] = $field['class'];

        ?>
        <div class="mo-learndash-form-field">
            <label for="<?= $field['id'] ?>"><?php echo wp_kses_post($field['label']); ?></label>
            <select class="<?= $field['class'] ?>" name="<?= $field['name'] ?>" id="<?= $field['id'] ?>" <?= ! empty($field['multiple']) ? 'multiple="multiple"' : ''; ?>>
                <?php
                if ( ! empty($field['multiple'])) {
                    foreach ($field['options'] as $key => $value) {
                        $selected = isset($field['value']) && is_array($field['value']) && in_array($key, $field['value']) ? 'selected' : '';
                        echo '<option value="' . esc_attr($key) . '"' . $selected . '>' . esc_html($value) . '</option>';
                    }
                } else {
                    foreach ($field['options'] as $key => $value) {
                        echo '<option value="' . esc_attr($key) . '"' . selected($key, $field['value']) . '>' . esc_html($value) . '</option>';
                    }
                }
                ?>
            </select>
            <?php if ( ! empty($field['description'])) : ?>
                <span class="description"><?php echo wp_kses_post($field['description']); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Output a checkbox input box.
     *
     * @param array $field
     */
    public static function ld_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-learndash-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-learndash-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public function ld_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-learndash-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" /> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }


    /**
     * @return array|false
     */
    public function learndash_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinLearnDashDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
            ];
        }

        return [];
    }

    public function add_subscription_checkbox($join_button)
    {
        $auto_subscribe = Settings::instance()->mailoptin_learndash_subscribe_students();

        if (is_user_logged_in() && $auto_subscribe !== 'no') {
            $user_id = get_current_user_id();

            $course_id = get_the_ID();

            $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_id);

            $global_connection = Settings::instance()->mailoptin_learndash_integration_connections();

            $is_connected = ! empty($connection) || ! empty($global_connection);

            $course_price_type = $this->get_price_course_type($course_id);

            $is_user_subscribed = get_user_meta($user_id, $connection . '[mailoptinLearnDashSubscribed-' . $course_id . ']', true);

            if ($is_connected && ! $is_user_subscribed && $course_price_type !== 'open') {
                $subscribe_text = Settings::instance()->mailoptin_learndash_subscription_registration_message();
                $join_button    = preg_replace_callback(
                    '/(<input.* id="btn-join*".*>)/',
                    function ($matches) use ($subscribe_text, $course_id) {
                        return '<div class="mo-learndash-frontend-subscription-form"><input id="mo-learndash-checkbox-course-' . $course_id . '" type="checkbox" name="mo_learndash_checkbox_course" value="1" class="mo-learndash-map-btn-join" /><label for="mo-learndash-checkbox-course-' . $course_id . '">' . $subscribe_text . '</label></div>' . $matches[0];
                    },
                    $join_button
                );
            }
        }

        return $join_button;
    }

    /**
     * @param $course_id
     *
     * @return mixed|string
     */
    public function get_price_course_type($course_id)
    {
        $meta = get_post_meta($course_id, '_sfwd-courses', true);

        if ( ! empty($meta['sfwd-courses_course_price_type'])) {
            return $meta['sfwd-courses_course_price_type'];
        }

        return '';
    }

    public function after_course_status($after_course_status)
    {
        $user_id = get_current_user_id();

        $subscribe_type = Settings::instance()->mailoptin_learndash_subscribe_students();

        if ($subscribe_type == 'yes') { // yes is to ask for permission

            if ($user_id) {
                $course_id = get_the_ID();

                $course_price_type = $this->get_price_course_type($course_id);

                $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_id);

                $global_connection = Settings::instance()->mailoptin_learndash_integration_connections();

                $is_connected = ! empty($connection) || ! empty($global_connection);

                $user_returned_message = ! empty ($_GET['mo-learndash-status']) ? $_GET['mo-learndash-status'] : false;

                $is_user_subscribed = get_user_meta($user_id, $connection . '[mailoptinLearnDashSubscribed-' . $course_id . ']', true);

                $user_can_take_course = sfwd_lms_has_access($course_id, $user_id);

                if ($course_price_type == 'open') {

                    if ($is_connected && ! $is_user_subscribed && $user_can_take_course) {
                        $subscribe_text = Settings::instance()->mailoptin_learndash_subscription_registration_message();

                        ob_start();
                        ?>
                        <form action="" method="post" class="mo-learndash-frontend-subscription-form">
                            <input type="hidden" name="mo_learndash_course_id" value="<?php echo $course_id; ?>">
                            <?php wp_nonce_field("mo_learndash_subscribe_course_id_$course_id", 'mo_learndash_subscribe_course_nonce'); ?>
                            <input type="submit" name="mo_learndash_submit_subscribed" value="<?php echo $subscribe_text; ?>"/>
                        </form>
                        <?php

                        $after_course_status .= ob_get_clean();

                    } elseif ( ! empty($connection) && $is_user_subscribed && ! empty($user_returned_message)) {
                        $subscribe_success_text = Settings::instance()->mailoptin_learndash_subscription_success_message();
                        ob_start();

                        ?>
                        <div class="mo-learndash-frontend-subscription-form">
                            <?php if ($user_returned_message == 'success') { ?>
                                <p><?= $subscribe_success_text ?></p>
                            <?php } else { ?>
                                <p><?= __('There was an error saving your contact. Please try again.', 'mailoptin') ?></p>
                            <?php } ?>
                        </div>
                        <?php

                        $after_course_status .= ob_get_clean();
                    }
                }
            }
        }

        return $after_course_status;
    }

    /**
     * Subscribes User to MailOptin List Tag automatically when Enrolling in a Course
     *
     * @param integer $user_id User ID
     * @param integer $course_id Course ID
     * @param array $access_list Course Access List
     * @param boolean $remove True if removing the Course from user, false otherwise.
     *
     * @return        void
     */
    public function auto_subscribe($user_id, $course_id, $access_list, $remove)
    {
        $auto_subscribe = Settings::instance()->mailoptin_learndash_subscribe_students();

        if ($auto_subscribe !== 'no') {
            return;
        }

        $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_id);

        if ( ! empty($connection)) {
            Course::get_instance()->process_submission($connection, $course_id, $user_id);
        }

        // check if mailoptin is connected in the settings
        if ( ! empty(Settings::instance()->mailoptin_learndash_integration_connections())) {
            LearnDashSettings::get_instance()->process_submission($course_id, $user_id);
        }
    }

    public function auto_subscribe_group_access($user_id, $group_id)
    {
        $auto_subscribe = Settings::instance()->mailoptin_learndash_subscribe_students();

        if ($auto_subscribe !== 'no') {
            return;
        }

        $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $group_id);

        if ( ! empty($connection)) {
            Course::get_instance()->process_submission($connection, $group_id, $user_id);
        }

        // check if mailoptin is connected in the settings
        if ( ! empty(Settings::instance()->mailoptin_learndash_integration_connections())) {
            LearnDashSettings::get_instance()->process_submission($group_id, $user_id);
        }

    }

    /**
     * Subscription via subscribe button
     */
    public function course_subscribe_form_handler()
    {
        $course_id = isset($_POST['mo_learndash_course_id']) ? absint($_POST['mo_learndash_course_id']) : false;

        if ( ! $course_id || ! isset($_POST['mo_learndash_submit_subscribed']) || ! wp_verify_nonce($_REQUEST['mo_learndash_subscribe_course_nonce'], "mo_learndash_subscribe_course_id_$course_id")) return false;

        $user_id = get_current_user_id();

        $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_id);

        $is_optin_successful = [];

        if ($user_id) {

            if ( ! empty($connection)) {
                array_push($is_optin_successful, Course::get_instance()->process_submission($connection, $course_id, $user_id));
            }

            // check if mailoptin is connected in the settings
            if ( ! empty(Settings::instance()->mailoptin_learndash_integration_connections())) {
                array_push($is_optin_successful, LearnDashSettings::get_instance()->process_submission($course_id, $user_id));
            }

            $course_price_type = $this->get_price_course_type($course_id);

            if ($course_price_type == 'open') {
                if (in_array(false, $is_optin_successful)) {
                    $message = __('failed', 'mailoptin');
                } else {
                    $message = __('success', 'mailoptin');
                    update_user_meta($user_id, $connection . '[mailoptinLearnDashSubscribed-' . $course_id . ']', true);
                }

                $redirect_url = esc_url_raw(add_query_arg(['mo-learndash-status' => $message]));

                learndash_safe_redirect($redirect_url);
            }
        }
    }

    /**
     * Subscription via checkbox
     */
    public function course_or_group_subscribe_form_handler()
    {
        $user_id            = get_current_user_id();
        $course_or_group_id = '';

        if ($user_id && ! empty($_POST['mo_learndash_checkbox_course']) && $_POST['mo_learndash_checkbox_course'] === '1') {
            if ( ! empty($_POST['group_id'])) $course_or_group_id = absint($_POST['group_id']);
            if ( ! empty($_POST['course_id'])) $course_or_group_id = absint($_POST['course_id']);
        }

        if ( ! empty($course_or_group_id)) {

            $connection = $this->learndash_get_field('mailoptinLearnDashSelectIntegration', $course_or_group_id);

            if ( ! empty($connection)) {
                Course::get_instance()->process_submission($connection, $course_or_group_id, $user_id);
            }

            // check if mailoptin is connected in the settings
            if ( ! empty(Settings::instance()->mailoptin_learndash_integration_connections())) {
                LearnDashSettings::get_instance()->process_submission($course_or_group_id, $user_id);
            }
        }

    }


    public function get_field_value($value, $user_id)
    {
        if ( ! empty($value)) {

            $user = get_userdata($user_id);

            if ($user instanceof \WP_user) {
                $user_field = $user->$value;

                if ($user_field) return $user_field;
            }
        }

        return '';
    }

    /**
     * @return mixed
     */
    public static function email_service_providers()
    {
        $connections = ConnectionsRepository::get_connections();

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $connections['leadbank'] = __('MailOptin Leads', 'mailoptin');
        }

        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
    }


    public function learndash_fields()
    {
        $user_fields = [
            ''              => '&mdash;&mdash;&mdash;',
            'ID'            => __('User ID', 'mailoptin'),
            'user_login'    => __('Username', 'mailoptin'),
            'user_nicename' => __('User Nicename', 'mailoptin'),
            'user_url'      => __('Website URL', 'mailoptin'),
            'user_email'    => __('Email address', 'mailoptin'),
            'display_name'  => __('Display Name', 'mailoptin'),
            'nickname'      => __('Nickname', 'mailoptin'),
            'first_name'    => __('First Name', 'mailoptin'),
            'last_name'     => __('Last Name', 'mailoptin'),
            'description'   => __('Biographical Info ', 'mailoptin')
        ];

        return apply_filters('mo_learndash_custom_users_mapped_fields', $user_fields);
    }

    /**
     * @return array
     */
    public function learndash_select_integration_options()
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

    public function learndash_get_field($name, $post_ID = false, $single = true)
    {
        return get_post_meta($post_ID, $name, $single);
    }


    /**
     * @return LearnDashInit
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