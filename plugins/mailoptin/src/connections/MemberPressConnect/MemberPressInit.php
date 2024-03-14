<?php

namespace MailOptin\MemberPressConnect;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;
use MailOptin\Core\Repositories\ConnectionsRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_MEMBERPRESS_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/MemberPressConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_MEMBERPRESS_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class MemberPressInit
{
    public function __construct()
    {
        add_filter('mo_mailoptin_js_globals', [$this, 'set_memberpress_global_variables'], 10, 1);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);

        add_action('wp_ajax_mo_memberpress_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_memberpress_fetch_custom_fields', [$this, 'fetch_custom_fields']);
        add_action('mepr-user-signup-fields', [$this, 'display_signup_field']);
        add_action('mepr-signup-user-loaded', [$this, 'process_signup']);
    }

    public function enqueue_scripts()
    {
        global $post;

        if (isset($post) && isset($post->post_type)) {
            $page = $post->post_type;

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if ($page == 'memberpressproduct') {
                wp_enqueue_script('mailoptin-memberpress', MAILOPTIN_MEMBERPRESS_CONNECT_ASSETS_URL . 'memberpress.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);

                wp_localize_script('mailoptin-memberpress', 'moMemberPress', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-memberpress'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, 'memberpress') !== false) {
            wp_enqueue_script('mailoptin-memberpress-settings', MAILOPTIN_MEMBERPRESS_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, true);

            wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);

            wp_localize_script('mailoptin-memberpress-settings', 'moMemberPress', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-memberpress'),
                'select2_tag_connections' => Init::select2_tag_connections(),
                'text_tag_connections'    => Init::text_tag_connections()
            ]);
        }
    }

    public function admin_scripts()
    {
        global $post;

        if (isset($post) && isset($post->post_type)) {
            $page = $post->post_type;

            if (in_array($page, ['mpcs-course', 'memberpressproduct'])) {
                ob_start();
                ?>
                <style>
                    .mo-memberpress-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-memberpress-form-field label {
                        display: block;
                    }

                    .mo-memberpress-form-field select,
                    .mo-memberpress-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-memberpress-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-memberpress-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-memberpress-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-memberpress-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-memberpress-form-field .select2-container .select2-selection {
                        width: 225px;
                        border-color: #c3c4c7;
                    }
                </style>
                <?php
                echo mo_minify_css(ob_get_clean());
            }
        }
    }

    public function display_signup_field()
    {
        $is_optin_enabled      = MemberPressSettings::get_instance()->is_optin_enabled();
        $is_connection_enabled = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressSelectIntegration');
        $subscribe_members     = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressSubscribeMembers');

        if ( ! empty($is_connection_enabled) && $is_optin_enabled === true && $subscribe_members !== 'no') {
            $optin_label            = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressOptinFieldLabel', __('Subscribe to our newsletters', 'mailoptin'));
            $optin_checkbox_default = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressOptinCheckboxDefault', 'checked');
            ?>
            <div class="mo-memberpress-form-row">
                <div class="mo-memberpress-signup-field">
                    <div id="mo-memberpress-checkbox">
                        <label class="mo-memberpress-message">
                            <input type="checkbox" name="momemberpress_opt_in" id="momemberpress_opt_in" class="mo-memberpress-form-checkbox" <?php checked($optin_checkbox_default, 'checked'); ?> />
                            <?= $optin_label; ?>
                        </label>
                    </div>
                </div>
            </div>
            <?php
        }
    }


    public function process_signup($user)
    {
        $subscribe_members = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressSubscribeMembers');

        if ( ! MemberPressSettings::get_instance()->is_optin_enabled()) return;

        // if subscribe members is automatically or memberpress_opt_in is checked
        if ($subscribe_members !== 'yes' || isset($_POST['momemberpress_opt_in'])) {
            if ( ! empty($_POST['mepr_product_id'])) {
                $membership_product_id = (int)$_POST['mepr_product_id'];
                $connection            = get_post_meta($membership_product_id, 'mailoptinMemberPressSelectIntegration', true);

                if ( ! empty($connection)) {
                    Membership::get_instance()->process_submission($connection, $membership_product_id, $user);
                }
            }

            $connection = MemberPressSettings::get_instance()->get_options('mailoptinMemberPressSelectIntegration');

            if ( ! empty($connection)) {
                MemberPressSettings::get_instance()->process_submission($connection, $user);
            }
        }
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_memberpress_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['memberpress_product_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-memberpress', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        if (empty($_POST['memberpress_product_id'])) wp_send_json_error([]);

        ob_start();

        $memberpress_product_id = absint($_POST['memberpress_product_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_post_meta($memberpress_product_id, $connection . '[mailoptinMemberPressDoubleOptin]', true);
            $double_optin_settings = $this->memberpress_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_post_meta($memberpress_product_id, $connection . '[mailoptinMemberPressSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::mp_mailoptin_select_field(
            [
                'id'          => 'mailoptinMemberPressSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list, audience or segment to add members to.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::mp_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-memberpress', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['memberpress_product_id'])) wp_send_json_error([]);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        $memberpress_product_id = (int)$_POST['memberpress_product_id'];
        ?>
        <h2 class="mo-memberpress-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinMemberPressMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($memberpress_product_id, $connection . '[' . $mapped_key . ']', true);

            self::mp_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->memberpress_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinMemberPressTextTags]';
                $saved_tags = get_post_meta($memberpress_product_id, $tags_key, true);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinMemberPressSelectTags]';
                $saved_tags = json_decode(get_post_meta($memberpress_product_id, $tags_key, true), true);
            }
            $this->memberpress_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }


    /**
     * @return array|false
     */
    public function memberpress_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinMemberPressDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires members to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
            ];
        }

        return [];
    }

    /**
     * @return false|void
     */
    public function memberpress_lead_tag_settings($saved_tags, $saved_integration)
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

            self::mp_mailoptin_select_field(
                [
                    'id'          => 'mailoptinMemberPressSelectTags',
                    'name'        => 'mailoptinMemberPressSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'mo_memberpress_select2',
                    'description' => esc_html__('Select tags to assign to members that sign up.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.mo_memberpress_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::mp_mailoptin_text_input(
                [
                    'id'          => 'mailoptinMemberPressTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to subscribed members.', 'mailoptin'),
                ]
            );
        }
    }

    public function memberpress_fields()
    {
        $mepr_options = \MeprOptions::fetch();

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
            'description'   => __('Biographical Info ', 'mailoptin'),
        ];

        //Member press custom fields added
        $mp_custom_fields = $mepr_options->custom_fields;
        if ( ! empty($mp_custom_fields)) {
            $user_fields = array_merge($user_fields, $this->return_mmeberpress_fields($mp_custom_fields));
        }

        //Maybe show the address fields too
        if ($mepr_options->show_address_fields) {
            $address_fields = $mepr_options->address_fields;
            $user_fields    = array_merge($user_fields, $this->return_mmeberpress_fields($address_fields));
        }

        $user_fields = array_unique($user_fields);

        return apply_filters('mo_memberpress_custom_users_mapped_fields', $user_fields);
    }


    public function return_mmeberpress_fields($fields)
    {
        $memberpress_fields = [];
        if ( ! empty($fields)) {
            foreach ($fields as $field) {
                $field_key                      = $field->field_key;
                $field_name                     = $field->field_name;
                $memberpress_fields[$field_key] = $field_name;
            }
        }

        return $memberpress_fields;
    }

    /**
     * @return array
     */
    public function memberpress_select_integration_options()
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
     * @param $value
     * @param $user_id
     *
     * @return string
     */
    public function get_field_value($value, $user_id)
    {
        if ( ! empty($value)) {
            $user = get_userdata($user_id);
            if ($user && $user->exists() && isset($user->$value)) {
                return $user->$value;
            }
        }

        return '';
    }


    /**
     * Output a checkbox input box.
     *
     * @param array $field
     */
    public static function mp_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-memberpress-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-memberpress-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

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
    public static function mp_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-memberpress-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" /> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * Output a textarea box.
     *
     * @param array $field
     */
    public static function mp_mailoptin_textarea($field)
    {
        $field['class']       = isset($field['class']) ? $field['class'] : 'short';
        $field['value']       = isset($field['value']) ? $field['value'] : '';
        $field['name']        = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']        = isset($field['type']) ? $field['type'] : 'text';
        $field['rows']        = isset($field['rows']) ? $field['rows'] : '8';
        $field['cols']        = isset($field['cols']) ? $field['cols'] : '20';
        $field['placeholder'] = isset($field['placeholder']) ? $field['placeholder'] : '';

        echo '<div class="mo-memberpress-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<textarea rows="' . esc_attr($field['rows']) . '" cols="' . esc_attr($field['cols']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" placeholder="' . esc_attr($field['placeholder']) . '" >' . esc_attr($field['value']) . '</textarea> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }


    public static function mp_mailoptin_select_field($field)
    {
        $field = wp_parse_args(
            $field, array(
                'class'    => 'select short',
                'value'    => ! empty($field['value']) ? $field['value'] : '',
                'name'     => $field['id'],
                'multiple' => '',
            )
        );

        $field_attributes['id']    = $field['id'];
        $field_attributes['name']  = $field['name'];
        $field_attributes['class'] = $field['class'];

        ?>
        <div class="mo-memberpress-form-field">
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

    /**
     * @return MemberPressInit
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