<?php

namespace MailOptin\RCPConnect;

use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;
use function MailOptin\Core\moVar;
use function MailOptin\Core\moVarGET;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_RCP_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/RCPConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_RCP_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class RCPInit
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('rcp_before_registration_submit_field', [$this, 'display_signup_field'], 99);


        add_action('rcp_form_processing', [$this, 'save_optin_checkbox_state'], 10, 6);

        add_action('rcp_successful_registration', array($this, 'process_signup'), 10, 3);
    }

    public function enqueue_scripts()
    {
        if (moVarGET('page') == 'rcp-member-levels' && defined('MAILOPTIN_DETACH_LIBSODIUM')) {
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, false);
            wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
        }

        $screen = get_current_screen();

        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-rcp-settings', MAILOPTIN_RCP_CONNECT_ASSETS_URL . 'settings.js', ['jquery'], MAILOPTIN_VERSION_NUMBER, true);
            wp_enqueue_script('mailoptin-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.js', ['jquery'], false, true);
            wp_enqueue_style('mailoptin-core-select2', MAILOPTIN_ASSETS_URL . 'js/customizer-controls/select2/select2.min.css', null);
        }
    }

    protected function is_auto_subscribe_enabled()
    {
        return Settings::instance()->mailoptin_rcp_subscribe_method() != 'yes';
    }

    public function display_signup_field()
    {
        $saved_connections = Settings::instance()->mailoptin_rcp_integration_connections();

        if ( ! empty($saved_connections) && ! $this->is_auto_subscribe_enabled()) {

            $optin_label = Settings::instance()->mailoptin_rcp_optin_checkbox_label();

            if (empty($optin_label)) $optin_label = __('Subscribe to our newsletters', 'mailoptin');

            ?>
            <p>
                <input name="morcp_opt_in" type="checkbox" value="1" id="morcp_opt_in">
                <label for="morcp_opt_in"><?= $optin_label; ?></label>
            </p>
            <?php
        }
    }

    public function save_optin_checkbox_state($postedData, $user_id, $price, $payment_id, $customer, $membership_id)
    {
        if ( ! $this->is_auto_subscribe_enabled() && moVar($postedData, 'morcp_opt_in') == '1') {
            update_option(sprintf('mo_rcp_subscribed_checked_%s', $membership_id), 'yes');
        }
    }

    public function process_signup($member, $customer = false, $membership = false)
    {
        if ($this->is_auto_subscribe_enabled() || get_option(sprintf('mo_rcp_subscribed_checked_%s', $membership->get_id())) == 'yes') {

            if (is_a($membership, 'RCP_Membership')) {

                delete_option(sprintf('mo_rcp_subscribed_checked_%s', $membership->get_id()));

                Membership::get_instance()->process_submission($membership);

                RCPSettings::get_instance()->process_submission($membership);
            }
        }
    }

    public function rcp_fields()
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

        return apply_filters('mo_rcp_custom_users_mapped_fields', $user_fields);
    }

    public function return_rcp_fields($fields)
    {
        $rcp_fields = [];

        if ( ! empty($fields)) {

            foreach ($fields as $field) {

                if (is_array($field)) {

                    foreach ($field as $item) {

                        if (isset($item->meta_key)) {
                            $rcp_fields[$item->meta_key] = $item->label;
                        }
                    }

                } elseif (isset($field->meta_key)) {
                    $rcp_fields[$field->meta_key] = $field->label;
                }
            }
        }

        return $rcp_fields;
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