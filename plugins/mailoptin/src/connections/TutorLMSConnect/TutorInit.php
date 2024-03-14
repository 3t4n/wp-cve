<?php

namespace MailOptin\TutorLMSConnect;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_TUTORLMS_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/TutorLMSConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_TUTORLMS_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class TutorInit
{
    public function __construct()
    {
        TutorSettings::get_instance();
        add_filter('mo_mailoptin_js_globals', [$this, 'set_tutorlms_global_variables'], 10, 1);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);

        add_action('wp_ajax_mo_tutorlms_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_tutorlms_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('tutor_after_enrolled', [$this, 'auto_subscribe'], 10, 2);
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {

            $page = $post->post_type;

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if (in_array($page, ['courses'])) {
                wp_enqueue_script('mailoptin-tutorlms', MAILOPTIN_TUTORLMS_CONNECT_ASSETS_URL . 'tutorlms.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);

                wp_localize_script('mailoptin-tutorlms', 'moTutorLMS', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-tutorlms'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-tutorlms-settings', MAILOPTIN_TUTORLMS_CONNECT_ASSETS_URL . 'settings.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);
            wp_localize_script('mailoptin-tutorlms-settings', 'moTutorLMS', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-tutorlms'),
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
    public function set_tutorlms_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['tutorlms_course_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function admin_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if (in_array($page, ['courses'])) {
                ob_start();
                ?>
                <style>
                    .mo-tutorlms-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-tutorlms-form-field label {
                        display: block;
                    }

                    .mo-tutorlms-form-field select,
                    .mo-tutorlms-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-tutorlms-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-tutorlms-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-tutorlms-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-tutorlms-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-tutorlms-form-field .select2-container .select2-selection {
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
        check_ajax_referer('mailoptin-tutorlms', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        if (empty($_POST['course_id'])) wp_send_json_error([]);

        ob_start();

        $course_id = absint($_POST['course_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = TutorInit::get_instance()->tutorlms_get_field($connection . '[mailoptinTutorLMSDoubleOptin]', $course_id);
            $double_optin_settings = $this->tutorlms_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = TutorInit::get_instance()->tutorlms_get_field($connection . '[mailoptinTutorLMSSelectList]', $course_id);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::tl_mailoptin_select_field(
            [
                'id'          => 'mailoptinTutorLMSSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list, audience or segment to add students to.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::tl_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }


    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-tutorlms', 'nonce');

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
        <h2 class="mo-tutorlms-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinTutorLMSMappedFields-' . $key);
            $saved_mapped_field = TutorInit::get_instance()->tutorlms_get_field($connection . '[' . $mapped_key . ']', $course_id);

            self::tl_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->tutorlms_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinTutorLMSTextTags]';
                $saved_tags = TutorInit::get_instance()->tutorlms_get_field($tags_key, $course_id);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinTutorLMSSelectTags]';
                $saved_tags = json_decode(TutorInit::get_instance()->tutorlms_get_field($tags_key, $course_id), true);
            }
            $this->tutorlms_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @return false|void
     */
    public function tutorlms_lead_tag_settings($saved_tags, $saved_integration)
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

            self::tl_mailoptin_select_field(
                [
                    'id'          => 'mailoptinTutorLMSSelectTags',
                    'name'        => 'mailoptinTutorLMSSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'motutorlms_select2',
                    'description' => esc_html__('Select tags to assign to enrolled students.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.motutorlms_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::tl_mailoptin_text_input(
                [
                    'id'          => 'mailoptinTutorLMSTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to enrolled students. The course title is automatically included in the list of tags.', 'mailoptin'),
                ]
            );
        }
    }

    public static function tl_mailoptin_select_field($field)
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
        <div class="mo-tutorlms-form-field">
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
    public static function tl_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-tutorlms-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-tutorlms-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

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
    public function tl_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-tutorlms-form-field">
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
    public function tutorlms_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinTutorLMSDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
            ];
        }

        return [];
    }

    /**
     * Subscribes User to MailOptin List Tag automatically when Enrolling in a Course
     *
     * @param int $course_id Course ID
     * @param int $user_id User ID
     *
     * @return void
     */
    public function auto_subscribe($course_id, $user_id)
    {
        $connection = $this->tutorlms_get_field('mailoptinTutorLMSSelectIntegration', $course_id);

        if ( ! empty($connection)) {
            Course::get_instance()->process_submission($connection, $course_id, $user_id);
        }

        // check if mailoptin is connected in the settings
        if ( ! empty(Settings::instance()->mailoptin_tutorlms_integration_connections())) {
            TutorSettings::get_instance()->process_submission($course_id, $user_id);
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

    public function tutorlms_fields()
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

        return apply_filters('mo_tutorlms_custom_users_mapped_fields', $user_fields);
    }

    /**
     * @return array
     */
    public function tutorlms_select_integration_options()
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

    public function tutorlms_get_field($name, $post_ID = false, $single = true)
    {
        return get_post_meta($post_ID, $name, $single);
    }


    /**
     * @return TutorInit
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