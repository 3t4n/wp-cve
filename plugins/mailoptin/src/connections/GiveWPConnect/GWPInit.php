<?php

namespace MailOptin\GiveWPConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_GWP_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/GiveWPConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_GWP_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class GWPInit
{
    public function __construct()
    {
        add_filter('give-settings_get_settings_pages', [$this, 'mailoptin_settings'], 10, 1);

        add_filter('mo_mailoptin_js_globals', [$this, 'set_gwp_global_variables'], 10, 1);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);
        Forms::get_instance();

        add_action('wp_ajax_mo_gwp_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_gwp_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('give_donation_form_before_submit', [$this, 'checkout_fields'], 999);

        add_action('give_insert_payment', array($this, 'save_optin_checkbox_state'), 10, 2);
        add_action('give_update_payment_status', function ($ID, $status) {
            if ('publish' == $status) {
                $this->subscribe_customer(give_get_payment_by('id', $ID));
            }
        }, 10, 2);
    }

    public function mailoptin_settings($settings)
    {
        $settings[] = new Settings();

        return $settings;
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_gwp_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['gwp_form_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if ($post->post_type == 'give_forms') {
                wp_enqueue_script('mailoptin-gwp', MAILOPTIN_GWP_CONNECT_ASSETS_URL . 'gwp.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
                wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);

                wp_localize_script('mailoptin-gwp', 'moGwp', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-gwp'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, 'give') !== false) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);
            wp_enqueue_style('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
            wp_enqueue_script('mailoptin-gwp-settings', MAILOPTIN_GWP_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore', 'mailoptin-select2'], MAILOPTIN_VERSION_NUMBER, true);
        }
    }

    public function admin_scripts()
    {
        global $post;

        $screen = get_current_screen();

        if ((isset($post->post_type) && $post->post_type == 'give_forms') || strpos($screen->id, 'give') !== false) {
            ob_start();
            ?>
            <style>
                .mo-gwp-form-field {
                    margin-bottom: 15px;
                }

                .mo-gwp-form-field label {
                    display: block;
                }

                .mo-gwp-form-field select,
                .mo-gwp-form-field input {
                    display: block;
                    margin-top: 10px;
                }

                .mo-gwp-form-field span {
                    display: block;
                    margin-top: 6px;
                }

                .mo-gwp-map-field-title {
                    padding: 0 !important;
                    font-weight: bold;
                    width: 100%;
                    display: inline-block;
                    text-align: left;
                    border-bottom: 1px solid #c3c4c7;
                    line-height: 0.1em !important;
                    margin: 10px 0 10px !important;
                }

                .mo-gwp-map-field-title span {
                    background: #f0f0f1;
                    padding-right: 10px;
                    font-size: 14px;
                }

                #mo_gwp_form_metabox .mo-gwp-map-field-title span {
                    background: #ffffff;
                }

                .mo-gwp-form-field .select2-container {
                    display: inline-block;
                    max-width: 100% !important;
                }

                .mo-gwp-form-field .select2-container .select2-selection {
                    width: 225px;
                    border-color: #c3c4c7;
                }
            </style>
            <?php
            echo mo_minify_css(ob_get_clean());
        }

        if (strpos($screen->id, 'give') !== false) {
            ob_start();
            ?>
            <style>
                .mo_gwp_select2 .select2 {
                    min-width: 300px;
                }

                .mo-gwp-upsell-block {
                    background-color: #d9edf7;
                    border: 1px solid #bce8f1;
                    box-sizing: border-box;
                    color: #31708f;
                    outline: 0;
                    margin: 0;
                    padding: 10px;
                }

                .mo-gwp-upsell-block p {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                }

            </style>
            <?php
            echo mo_minify_css(ob_get_clean());
        }
    }

    public function checkout_fields($form_id)
    {
        $auto_subscribe = give_get_option('mailoptin_gwp_subscribe_customers', 'no');

        if ($auto_subscribe !== 'no') {

            $checkout_label = give_get_option('mailoptin_gwp_optin_checkbox_label', __('Subscribe to our newsletterz', 'mailoptin'));

            ?>
            <fieldset id="mailoptin_gwp_<?php echo $form_id; ?>" class="give-mailoptin-fieldset">
                <p>
                    <input name="mailoptin_gwp_signup" id="mailoptin_gwp_signup" value="yes" type="checkbox"/>
                    <label for="mailoptin_gwp_signup"><?php echo $checkout_label; ?></label>
                </p>
            </fieldset>
            <?php
        }
    }

    /**
     * @param $payment_id
     * @param $payment_data array
     */
    public function save_optin_checkbox_state($payment_id, $payment_data)
    {
        if ( ! isset($_POST['mailoptin_gwp_signup']) || $_POST['mailoptin_gwp_signup'] !== 'yes') {
            return;
        }

        give_update_meta($payment_id, '_mailoptin_gwp_optin_status', 'true');
    }

    /**
     * @param \Give_Payment $payment
     *
     * @return false|void
     */
    public function subscribe_customer($payment)
    {
        if (give_get_option('mailoptin_gwp_subscribe_customers', 'no') == 'yes') {

            if (give_get_meta($payment->ID, '_mailoptin_gwp_optin_status', true) != 'true') return;
        }

        Forms::get_instance()->process_submission($payment);

        $this->process_submission($payment);
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-gwp', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        if (empty($_POST['form_id'])) wp_send_json_error([]);

        ob_start();

        $form_id = absint($_POST['form_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_post_meta($form_id, $connection . '[mailoptinGWPDoubleOptin]', true);
            $double_optin_settings = $this->gwp_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_post_meta($form_id, $connection . '[mailoptinGWPSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::gwp_mailoptin_select_field(
            [
                'id'          => 'mailoptinGWPSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list or audience to add customers.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::gwp_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-gwp', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['form_id'])) wp_send_json_error([]);

        ob_start();

        $form_id = absint($_POST['form_id']);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ?>
        <h2 class="mo-gwp-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinGWPMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($form_id, $connection . '[' . $mapped_key . ']', true);

            self::gwp_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->gwp_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinGWPTextTags]';
                $saved_tags = get_post_meta($form_id, $tags_key, true);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinGWPSelectTags]';
                $saved_tags = json_decode(get_post_meta($form_id, $tags_key, true));
            }
            $this->gwp_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @param $field
     */
    public static function gwp_mailoptin_select_field($field)
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
        <div class="mo-gwp-form-field">
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
    public static function gwp_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-gwp-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-gwp-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * @return false|void
     */
    public function gwp_lead_tag_settings($saved_tags, $saved_integration)
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

            self::gwp_mailoptin_select_field(
                [
                    'id'          => 'mailoptinGWPSelectTags',
                    'name'        => 'mailoptinGWPSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'mogwp_select2',
                    'description' => esc_html__('Select tags to assign to users that made purchases.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.mogwp_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::gwp_mailoptin_text_input(
                [
                    'id'          => 'mailoptinGWPTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to customers. The form title is automatically included in the list of tags.', 'mailoptin'),
                ]
            );
        }
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public function gwp_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-gwp-form-field">
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
    public function gwp_select_integration_options()
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

    public function get_field_value($value, $email)
    {
        if ( ! empty($value)) {

            $user_data = get_user_by('email', $email);

            if ($user_data instanceof \WP_User) {
                return $user_data->$value;
            }
        }

        return '';
    }

    /**
     * @return array|false
     */
    public function gwp_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinGWPDoubleOptin',
                'name'        => 'mailoptinGWPDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
                'type'        => 'checkbox'
            ];
        }

        return [];
    }

    public function gwp_fields()
    {
        $user_fields = [
            ''              => '&mdash;&mdash;&mdash;',
            'ID'            => __('User ID', 'mailoptin'),
            'user_login'    => __('Username', 'mailoptin'),
            'user_nicename' => __('User Nicename', 'mailoptin'),
            'user_url'      => __('Website URL', 'mailoptin'),
            'display_name'  => __('Display Name', 'mailoptin'),
            'nickname'      => __('Nickname', 'mailoptin'),
            'first_name'    => __('First Name', 'mailoptin'),
            'last_name'     => __('Last Name', 'mailoptin'),
            'description'   => __('Biographical Info ', 'mailoptin')
        ];

        return apply_filters('mo_gwp_custom_users_mapped_fields', $user_fields);
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
     * @param \Give_Payment $payment
     *
     * @return bool|void
     */
    public function process_submission($payment)
    {
        $field_map = [];

        $connection_service = give_get_option('mailoptin_gwp_integration_connections');

        if (empty($connection_service)) return;

        $connection_email_list = give_get_option('mailoptin_gwp_integration_lists');

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mailoptin_gwp_mapped_fields-' . $key);
            $field_map[$key] = give_get_option($mapped_key);
        }

        $user_info = $payment->user_info;

        $email = $user_info['email'];

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = GWPInit::get_instance()->get_field_value($value, $email);
            }
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = give_get_option('mailoptin_gwp_double_optin') == 'on';
        }

        $form_tags = '';

        if (in_array($connection_service, Init::text_tag_connections())) {

            $form_tags = give_get_option('mailoptin_gwp_text_tags', '');

            $exploded_form_tags = explode(',', $form_tags);

            array_push($exploded_form_tags, $payment->form_title);

            $form_tags = implode(',', array_filter($exploded_form_tags));

        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            if ( ! empty(give_get_option('mailoptin_gwp_select_tags'))) {
                $form_tags = give_get_option('mailoptin_gwp_select_tags');
            }
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_info['first_name'];
        $last_name  = $user_info['last_name'];

        $name = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'GiveWP';

        $optin_data->connection_service    = $connection_service;
        $optin_data->connection_email_list = $connection_email_list;

        $optin_data->user_agent                = esc_html($_SERVER['HTTP_USER_AGENT']);
        $optin_data->is_timestamp_check_active = false;
        $optin_data->is_double_optin           = $double_optin;

        if ( ! empty($form_tags)) {
            $optin_data->form_tags = $form_tags;
        }

        // Loop through field map.
        foreach ($field_map as $name => $value) {
            // If no field is mapped, skip it.
            if (empty($value)) continue;

            $field_value = GWPInit::get_instance()->get_field_value($value, $email);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return GWPInit
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