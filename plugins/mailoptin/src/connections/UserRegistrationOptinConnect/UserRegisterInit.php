<?php

namespace MailOptin\UserRegistrationOptinConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Connections\Init;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_USER_REGISTER_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/UserRegistrationOptinConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_USER_REGISTER_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class UserRegisterInit
{
    public function __construct()
    {
        UserRegisterSettings::get_instance();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('register_form', [$this, 'add_subscription_checkbox'], PHP_INT_MAX - 5);
        add_action('user_new_form', [$this, 'add_subscription_checkbox_to_admin']);
        add_action('user_register', [$this, 'subscribe_user']);
        add_action('edit_user_created_user', [$this, 'admin_subscribe_user']);
    }


    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-user-register-settings', MAILOPTIN_USER_REGISTER_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);
        }
    }

    public function subscribe_user($user_id)
    {
        $auto_subscribe = Settings::instance()->mailoptin_user_registration_optin_subscribe_users();

        if ($auto_subscribe == 'yes') {
            if (empty($_POST['mo_user_registration_optin_checkbox_user'])) return;
        }

        if ( ! empty(Settings::instance()->mailoptin_user_registration_optin_integration_connections())) {
            self::process_submission($user_id);
        }
    }

    public function admin_subscribe_user($user_id)
    {
        $auto_subscribe = Settings::instance()->mailoptin_user_registration_optin_subscribe_users();

        if (( ! empty(Settings::instance()->mailoptin_user_registration_optin_integration_connections())) && ( ! empty($_POST['mo_user_registration_optin_checkbox_user']) || $auto_subscribe)) {
            self::process_submission($user_id);
        }
    }

    public function process_submission($user_id)
    {
        $field_map = [];

        $connection_service = Settings::instance()->mailoptin_user_registration_optin_integration_connections();

        if (empty($connection_service)) return;

        $connection_email_list = Settings::instance()->mailoptin_user_registration_optin_integration_lists();

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mailoptin_user_registration_optin_mapped_fields_' . $key);
            $field_map[$key] = Settings::instance()->$mapped_key();
        }

        $user_data = get_userdata($user_id);

        //get the email
        $email = $user_data->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = self::get_instance()->get_field_value($value, $user_id);
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = Settings::instance()->mailoptin_user_registration_optin_double_optin() == "true";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_user_registration_optin_text_tags();
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_user_registration_optin_select_tags();
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user_data->first_name;
        $last_name  = $user_data->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = esc_html__('User Registration Optin', 'mailoptin');

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
            if (empty($value)) {
                continue;
            }

            if (in_array($name, ['moEmail', 'moName', 'moFirstName', 'moLastName'])) continue;

            $field_value = self::get_instance()->get_field_value($value, $user_id);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    public function add_subscription_checkbox()
    {
        $auto_subscribe = Settings::instance()->mailoptin_user_registration_optin_subscribe_users();

        if ($auto_subscribe !== 'no') {
            $global_connection = Settings::instance()->mailoptin_user_registration_optin_integration_connections();

            if ( ! $global_connection) return;

            $subscribe_text = Settings::instance()->mailoptin_user_registration_optin_subscription_registration_message();

            ob_start();
            ?>
            <p id="mo-user-register-field">
                <input type="checkbox" id="mo-user-register-subscribe-user" name="mo_user_registration_optin_checkbox_user" value="1"/>
                <label for="mo-user-register-subscribe-user"><?= $subscribe_text ?></label>
            </p>
            <?php

            echo ob_get_clean();
        }
    }

    public function add_subscription_checkbox_to_admin($operation)
    {
        if ($operation !== 'add-new-user') return;

        $auto_subscribe = Settings::instance()->mailoptin_user_registration_optin_subscribe_users();

        if ($auto_subscribe !== 'no') {
            $global_connection = Settings::instance()->mailoptin_user_registration_optin_integration_connections();

            if ( ! $global_connection) return;

            $subscribe_text = Settings::instance()->mailoptin_user_registration_optin_subscription_registration_message();

            ob_start();
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="mo-user-register-subscribe-user"><?= $subscribe_text ?></label></th>
                    <td>
                        <input type="checkbox" id="mo-user-register-subscribe-user" name="mo_user_registration_optin_checkbox_user" value="1"/>
                    </td>
                </tr>
            </table>
            <?php

            echo ob_get_clean();
        }
    }

    public function user_registration_optin_fields()
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

        return apply_filters('mo_user_registration_optin_custom_users_mapped_fields', $user_fields);
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

    /**
     * @return UserRegisterInit
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