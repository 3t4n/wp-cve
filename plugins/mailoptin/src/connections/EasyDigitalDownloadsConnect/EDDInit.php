<?php

namespace MailOptin\EasyDigitalDownloadsConnect;

use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_EDD_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/EasyDigitalDownloadsConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_EDD_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class EDDInit
{
    public function __construct()
    {
        add_filter('mo_mailoptin_js_globals', [$this, 'set_edd_global_variables'], 10, 1);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);
        Settings::get_instance();
        Downloads::get_instance();

        add_action('wp_ajax_mo_edd_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_edd_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('edd_purchase_form_before_submit', [$this, 'checkout_fields'], 100);

        add_action('edd_checkout_before_gateway', array($this, 'checkout_signup'));
        add_action('edd_complete_purchase', array($this, 'store_payment_meta'), 10, 1);
        add_action('edd_complete_purchase', [$this, 'subscribe_customer'], 10, 2);

    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_edd_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['edd_download_id'] = $post->ID;
        }

        return $localize_strings;
    }

    public function enqueue_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if ($page == 'download') {
                wp_enqueue_script('mailoptin-edd', MAILOPTIN_EDD_CONNECT_ASSETS_URL . 'edd.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

                wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
                wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);

                wp_localize_script('mailoptin-edd', 'moEdd', [
                    'fields'                  => [],
                    'ajax_url'                => admin_url('admin-ajax.php'),
                    'nonce'                   => wp_create_nonce('mailoptin-edd'),
                    'select2_tag_connections' => Init::select2_tag_connections(),
                    'text_tag_connections'    => Init::text_tag_connections()
                ]);
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, 'edd') !== false) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', array('jquery'), false, true);
            wp_enqueue_style('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
            wp_enqueue_script('mailoptin-edd-settings', MAILOPTIN_EDD_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore', 'mailoptin-select2'], MAILOPTIN_VERSION_NUMBER, true);
        }
    }

    public function admin_scripts()
    {
        global $post;

        if ( ! empty($post)) {
            $page = $post->post_type;

            if ($page == 'download') {
                ob_start();
                ?>
                <style>
                    .mo-edd-form-field {
                        margin-bottom: 15px;
                    }

                    .mo-edd-form-field label {
                        display: block;
                    }

                    .mo-edd-form-field select,
                    .mo-edd-form-field input {
                        display: block;
                        margin-top: 10px;
                    }

                    .mo-edd-form-field span {
                        display: block;
                        margin-top: 6px;
                    }

                    .mo-edd-map-field-title {
                        padding: 0 !important;
                        font-weight: bold;
                        width: 100%;
                        display: inline-block;
                        text-align: left;
                        border-bottom: 1px solid #c3c4c7;
                        line-height: 0.1em !important;
                        margin: 10px 0 10px !important;
                    }

                    .mo-edd-map-field-title span {
                        background: #fff;
                        padding-right: 10px;
                        font-size: 14px;
                    }

                    .mo-edd-form-field .select2-container {
                        display: inline-block;
                        max-width: 100% !important;
                    }

                    .mo-edd-form-field .select2-container .select2-selection {
                        width: 225px;
                        border-color: #c3c4c7;
                    }
                </style>
                <?php
                echo mo_minify_css(ob_get_clean());
            }
        }

        $screen = get_current_screen();
        if (strpos($screen->id, 'edd') !== false) {
            ob_start();
            ?>
            <style>
                .mo_edd_select2 .select2 {
                    min-width: 300px;
                }

                .mailoptin_edd_fields label {
                    display: block;
                }

                .mailoptin_edd_sub_header {
                    position: relative;
                }

                .mailoptin_edd_sub_header th {
                    line-height: 0.1em;
                    margin: 10px 0 20px;
                    text-align: left;
                    border-bottom: 1px solid #c3c4c7;
                    padding: 9px 0 0;
                    position: absolute;
                    width: 100%;
                }

                .mailoptin_edd_sub_header th strong {
                    background: #F0F0F1;
                    padding-right: 10px;
                }

                .mo-edd-upsell-block {
                    background-color: #d9edf7;
                    border: 1px solid #bce8f1;
                    box-sizing: border-box;
                    color: #31708f;
                    outline: 0;
                    margin: 10px;
                    padding: 10px;
                }

                .mo-edd-upsell-block p {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                }

            </style>
            <?php
            echo mo_minify_css(ob_get_clean());
        }
    }

    /**
     * Output the signup checkbox on the checkout screen, if enabled
     */
    public function checkout_fields()
    {
        $auto_subscribe = edd_get_option('mailoptin_edd_subscribe_customers', 'no');

        if ($auto_subscribe !== 'no') {
            $checkout_label = edd_get_option('mailoptin_edd_subscription_registration_message', __('Subscribe to our newsletter', 'mailoptin'));
            $checked        = edd_get_option('mailoptin_edd_subscription_checked_default_value', false);
            ?>
            <fieldset id="mailoptin_edd">
                <p>
                    <input name="mailoptin_edd_signup" id="mailoptin_edd_signup" value="yes" type="checkbox" <?php if ($checked == '1') {
                        echo 'checked="checked"';
                    } ?>/>
                    <label for="mailoptin_edd_signup"><?php echo $checkout_label; ?></label>
                </p>
            </fieldset>
            <?php
        }
    }

    public function checkout_signup($posted)
    {
        if (edd_get_option('mailoptin_edd_subscribe_customers', 'no') == 'yes') {

            if (empty($posted['mailoptin_edd_signup'])) {
                EDD()->session->set('mailoptin_edd_subscribed_at_checkout', null);

                return;
            }

            EDD()->session->set('mailoptin_edd_subscribed_at_checkout', 1);
        }
    }

    /**
     * Store the opt-in status on the payment meta, so we can use it after it is processed.
     *
     * @param int $payment_id
     * @param \EDD_Payment $payment
     */
    public function store_payment_meta($payment_id, $payment = null)
    {
        $opt_in_status = EDD()->session->get('mailoptin_edd_subscribed_at_checkout');
        if ( ! empty($opt_in_status)) {
            edd_update_payment_meta($payment_id, '_moedd_subscribed', 1);
        }
    }

    public function subscribe_customer($payment_id, $payment = null)
    {
        if ( ! $payment_id) return false;

        if (edd_get_option('mailoptin_edd_subscribe_customers', 'no') == 'yes') {

            $opted_in = edd_get_payment_meta($payment_id, '_moedd_subscribed');

            //don't add customer if the customer did not tick the checkbox
            if ( ! $opted_in) return;
        }

        $payment_meta = edd_get_payment_meta($payment_id);

        if ( ! empty($payment_meta['downloads'])) {

            $downloads = $payment_meta['downloads'];

            foreach ($downloads as $download) {
                Downloads::get_instance()->process_submission($download['id'], $payment_meta);
            }

            Settings::get_instance()->process_submission($payment_meta);
        }
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-edd', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        ob_start();

        if (empty($_POST['download_id'])) wp_send_json_error([]);

        $download_id = absint($_POST['download_id']);

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            $saved_double_optin    = get_post_meta($download_id, $connection . '[mailoptinEDDDoubleOptin]', true);
            $double_optin_settings = $this->edd_double_optin_settings($saved_double_optin, $connection);
        }

        $lists       = [];
        $saved_lists = '';
        if ( ! empty($connection) && $connection != 'leadbank') {
            $lists       = Init::mo_select_list_options($connection);
            $saved_lists = get_post_meta($download_id, $connection . '[mailoptinEDDSelectList]', true);
        }

        if (empty($lists)) wp_send_json_error([]);

        self::edd_mailoptin_select_field(
            [
                'id'          => 'mailoptinEDDSelectList',
                'label'       => esc_html__('Select List', 'mailoptin'),
                'value'       => $saved_lists,
                'options'     => $lists,
                'description' => __('Select the email list or audience to add customers.', 'mailoptin'),
            ]
        );

        if ( ! empty($double_optin_settings)) {
            self::edd_mailoptin_checkbox($double_optin_settings);
        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-edd', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        if (empty($_POST['download_id'])) wp_send_json_error([]);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        ob_start();

        $download_id = absint($_POST['download_id']);
        ?>
        <h2 class="mo-edd-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
        <?php
        foreach ($mappable_fields as $key => $value) {
            $mapped_key         = rawurlencode('mailoptinEDDMappedFields-' . $key);
            $saved_mapped_field = get_post_meta($download_id, $connection . '[' . $mapped_key . ']', true);

            self::edd_mailoptin_select_field(
                [
                    'id'      => $mapped_key,
                    'label'   => $value,
                    'value'   => $saved_mapped_field,
                    'options' => $this->edd_fields(),
                ]
            );
        }

        $saved_tags = '';
        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            if (in_array($connection, Init::text_tag_connections())) {
                $tags_key   = $connection . '[mailoptinEDDTextTags]';
                $saved_tags = get_post_meta($download_id, $tags_key, true);
            } elseif (in_array($connection, Init::select2_tag_connections())) {
                $tags_key   = $connection . '[mailoptinEDDSelectTags]';
                $saved_tags = json_decode(get_post_meta($download_id, $tags_key, true));
            }
            $this->edd_lead_tag_settings($saved_tags, $connection);
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @param $field
     */
    public static function edd_mailoptin_select_field($field)
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
        <div class="mo-edd-form-field">
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
    public static function edd_mailoptin_checkbox($field)
    {
        $field['value']   = isset($field['value']) ? $field['value'] : '';
        $field['cbvalue'] = isset($field['cbvalue']) ? $field['cbvalue'] : 'yes';
        $field['name']    = isset($field['name']) ? $field['name'] : $field['id'];

        echo '<div class="mo-edd-form-field">
		    <label for="' . esc_attr($field['id']) . '">' . wp_kses_post($field['label']) . '</label>';

        echo '<input type="checkbox" class="mo-edd-checkbox" name="' . esc_attr($field['name']) . '" id="' . esc_attr($field['id']) . '" value="' . esc_attr($field['cbvalue']) . '" ' . checked($field['value'], $field['cbvalue'], false) . '/> ';

        if ( ! empty($field['description'])) {
            echo '<span class="description">' . wp_kses_post($field['description']) . '</span>';
        }

        echo '</div>';
    }

    /**
     * @return false|void
     */
    public function edd_lead_tag_settings($saved_tags, $saved_integration)
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

            self::edd_mailoptin_select_field(
                [
                    'id'          => 'mailoptinEDDSelectTags',
                    'name'        => 'mailoptinEDDSelectTags[]',
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'value'       => $saved_tags,
                    'options'     => $options,
                    'class'       => 'moEdd_select2',
                    'description' => esc_html__('Select tags to assign to users that made purchases.', 'mailoptin'),
                    'multiple'    => 'multiple'
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.moEdd_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            self::edd_mailoptin_text_input(
                [
                    'id'          => 'mailoptinEDDTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to customers. The Download title is automatically included in the list of tags.', 'mailoptin'),
                ]
            );
        }
    }

    /**
     * Output a text input box.
     *
     * @param array $field
     */
    public function edd_mailoptin_text_input($field)
    {
        $field['class'] = isset($field['class']) ? $field['class'] : 'short';
        $field['value'] = isset($field['value']) ? $field['value'] : '';
        $field['name']  = isset($field['name']) ? $field['name'] : $field['id'];
        $field['type']  = isset($field['type']) ? $field['type'] : 'text';

        echo '<div class="mo-edd-form-field">
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
    public function edd_select_integration_options()
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
    public function edd_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinEDDDoubleOptin',
                'name'        => 'mailoptinEDDDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => $saved_double_optin == '1' ? 'yes' : 'no',
                'type'        => 'checkbox'
            ];
        }

        return [];
    }

    public function edd_fields()
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

        return apply_filters('mo_edd_custom_users_mapped_fields', $user_fields);
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
     * @return EDDInit|null
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