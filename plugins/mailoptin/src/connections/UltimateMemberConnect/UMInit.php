<?php

namespace MailOptin\UltimateMemberConnect;

use MailOptin\Connections\UltimateMemberConnect\UMSettings;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;
use MailOptin\Core\PluginSettings\Settings;
use function MailOptin\Core\moVar;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_UM_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/UltimateMemberConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_UM_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class UMInit
{
    public function __construct()
    {
        add_filter('mo_mailoptin_js_globals', [$this, 'set_um_global_variables'], 10, 1);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);

        UMSettings::get_instance();
        Forms::get_instance();

        add_action('wp_ajax_mo_um_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_um_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('um_after_form_fields', [$this, 'add_optin_field']);

        add_action('um_registration_complete', [$this, 'process_optin'], 20, 2);
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_um_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['um_form_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if ($page == 'um_form') {
                wp_enqueue_script('mailoptin-um', MAILOPTIN_UM_CONNECT_ASSETS_URL . 'um.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
                wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);

                wp_localize_script('mailoptin-um', 'moUm', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-um'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, 'um') !== false) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);
            wp_enqueue_style('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
            wp_enqueue_script('mailoptin-um-settings', MAILOPTIN_UM_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore', 'mailoptin-select2'], MAILOPTIN_VERSION_NUMBER, true);
        }

        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-ultimate-member-settings', MAILOPTIN_UM_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);
        }
    }

    public function admin_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if ($page == 'um_form') {
                ob_start();
                ?>
                <style>
                    .mo-um-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-um-form-field label {
                        display: block;
                    }

                    .mo-um-form-field select,
                    .mo-um-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-um-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-um-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-um-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-um-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-um-form-field .select2-container .select2-selection {
                        width: 225px;
                        border-color: #c3c4c7;
                    }
                </style>
                <?php
                echo mo_minify_css(ob_get_clean());
            }
        }
    }

    /**
     * Output the signup checkbox on the registration form, if enabled
     */
    public function add_optin_field()
    {
        $display_opt_in = Settings::instance()->mailoptin_ultimatemember_subscribe_customers();

        if ('yes' === $display_opt_in) {
            $checkbox_default = Settings::instance()->mailoptin_ultimatemember_checkbox_default();
            $checkbox_label   = Settings::instance()->mailoptin_ultimatemember_field_label();
            $checked          = '';
            $checkbox_class   = 'um-icon-android-checkbox-outline-blank';
            $wrapper_class    = '';

            if ($checkbox_default === 'checked') {
                $checked        = ' checked="checked"';
                $checkbox_class = 'um-icon-android-checkbox-outline';
                $wrapper_class  = ' active';
            }

            ob_start();
            ?>
            <div class="um-field mo-ultimatemember-opt-in">
                <div class="um-field-area">
                    <label class="um-field-checkbox<?php echo $wrapper_class; ?>">
                        <input type="checkbox" name="mailoptin_ultimatemember_optin_checkbox" value="yes"<?php echo $checked ?>>
                        <span class="um-field-checkbox-state"><i class="<?php echo $checkbox_class; ?>"></i></span>
                        <span class="um-field-checkbox-option"><?php echo esc_html($checkbox_label); ?></span>
                    </label>
                </div>
            </div>
            <?php
            echo apply_filters('mailoptin_ultimatemember_opt_in_checkbox', ob_get_clean());
        }
    }

    public function process_optin($user_id, $args)
    {
        $form_id           = moVar($args, 'form_id', 0);
        $subscription_type = Settings::instance()->mailoptin_ultimatemember_subscribe_customers();

        if ($subscription_type == 'yes') {

            $optin_checkbox_value = moVar($args, 'mailoptin_ultimatemember_optin_checkbox', 'no');

            //don't add customer if the customer did not tick the checkbox
            if ($optin_checkbox_value !== 'yes') return;
        }

        Forms::get_instance()->process_submission($form_id, $user_id);

        UMSettings::get_instance()->process_submission($user_id);
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-um', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        ob_start();

        if (empty($_POST['form_id'])) wp_send_json_error([]);

        $form_id = absint($_POST['form_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_post_meta($form_id, $connection . '[mailoptinUMDoubleOptin]', true);
            $double_optin_settings = $this->um_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_post_meta($form_id, $connection . '[mailoptinUMSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::um_mailoptin_select_field(
            [
                'id'          => 'mailoptinUMSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list or audience to add customers.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::um_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-um', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['form_id'])) wp_send_json_error([]);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        $form_id = absint($_POST['form_id']);
        ?>
        <h2 class="mo-um-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinUMMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($form_id, $connection . '[' . $mapped_key . ']', true);

            self::um_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => UMSettings::get_instance()->um_custom_fields()
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinUMTextTags]';
                $saved_tags = get_post_meta($form_id, $tags_key, true);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinUMSelectTags]';
                $saved_tags = json_decode(get_post_meta($form_id, $tags_key, true));
            }
            $this->um_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @param $field
     */
    public static function um_mailoptin_select_field($field)
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
        <div class="mo-um-form-field">
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
    public static function um_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-um-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-um-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * @return false|void
     */
    public function um_lead_tag_settings($saved_tags, $saved_integration)
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

            self::um_mailoptin_select_field(
                [
                    'id'          => 'mailoptinUMSelectTags',
                    'name'        => 'mailoptinUMSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'moUm_select2',
                    'description' => esc_html__('Select tags to assign to users that made purchases.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.moUm_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::um_mailoptin_text_input(
                [
                    'id'          => 'mailoptinUMTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to customers. The Registration Form title is automatically included in the list of tags.', 'mailoptin'),
                ]
            );
        }
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public function um_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-um-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="' . esc_attr($field['type']) . '" class="' . esc_attr($field['class']) . '" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['value']) . '" /> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * @return array
     */
    public function um_select_integration_options()
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
     * @return array|false
     */
    public function um_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinUMDoubleOptin',
                'name'        => 'mailoptinUMDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
                'type'        => 'checkbox'
            ];
        }

        return [];
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
     * @return UMInit|null
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