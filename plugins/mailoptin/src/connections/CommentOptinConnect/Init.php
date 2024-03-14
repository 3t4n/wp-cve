<?php

namespace MailOptin\CommentOptinConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Connections\Init as ConnectionsInit;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_USER_COMMENT_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/CommentOptinConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_USER_COMMENT_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class Init
{
    public function __construct()
    {
        CommentOptinSettings::get_instance();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('comment_post', [$this, 'subscribe_comments'], 10, 3);
        add_filter('mo_comment_optin_custom_users_mapped_fields', [$this, 'add_user_fields']);
        add_filter('comment_form_submit_button', [$this, 'add_checkbox_field'], PHP_INT_MAX - 5, 2);
    }

    public function enqueue_scripts()
    {
        $screen = get_current_screen();
        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-user-comment-settings', MAILOPTIN_USER_COMMENT_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);
            wp_localize_script('mailoptin-user-comment-settings', 'moUserComment', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-user-comment'),
                'select2_tag_connections' => ConnectionsInit::select2_tag_connections(),
                'text_tag_connections'    => ConnectionsInit::text_tag_connections()
            ]);
        }
    }


    public static function process_submission($commentdata, $user_id = 0)
    {
        $field_map = [];

        $connection_service = Settings::instance()->mailoptin_comment_optin_integration_connections();

        if (empty($connection_service)) return;

        $connection_email_list = Settings::instance()->mailoptin_comment_optin_integration_lists();

        foreach (ConnectionsInit::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mailoptin_comment_optin_mapped_fields_' . $key);
            $field_map[$key] = Settings::instance()->$mapped_key();
        }

        if (empty($commentdata['comment_author_email'])) return;

        //get the email
        $email = $commentdata['comment_author_email'];

        $payload = [];

        foreach ($field_map as $key => $value) {
            if (isset($commentdata[$value])) {
                $payload[$key] = $commentdata[$value];
            } else {
                $payload[$key] = self::get_instance()->get_field_value($value, $user_id);
            }
        }

        $double_optin = false;
        if (in_array($connection_service, ConnectionsInit::double_optin_support_connections(true))) {
            $double_optin = Settings::instance()->mailoptin_comment_optin_double_optin() == "true";
        }

        $form_tags = '';
        if (in_array($connection_service, ConnectionsInit::text_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_comment_optin_text_tags();
        } elseif (in_array($connection_service, ConnectionsInit::select2_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_comment_optin_select_tags();
        }

        $optin_data = new ConversionDataBuilder();

        $name = $commentdata['comment_author'];

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = $name;
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = esc_html__('Comment Form Optin', 'mailoptin');

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

            if (isset($commentdata[$value])) {
                $field_value = $commentdata[$value];
            } else {
                $field_value = self::get_instance()->get_field_value($value, $user_id);
            }

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }


    public function subscribe_comments($comment_id, $comment_approved, $commentdata)
    {
        $auto_subscribe = Settings::instance()->mailoptin_comment_optin_subscribe_users();

        $user_id = 0;
        if (is_user_logged_in()) $user_id = get_current_user_id();

        if ($auto_subscribe == 'yes') {
            if (empty($_POST['mo_comment_optin_checkbox_user'])) return;
        }

        if ( ! empty(Settings::instance()->mailoptin_comment_optin_integration_connections())) {
            self::process_submission($commentdata, $user_id);
        }
    }


    public function get_field_value($value, $user_id)
    {
        $user_field = '';

        if ( ! empty($value)) {

            $user = get_userdata($user_id);

            if ($user && $user->exists()) {
                $user_field = $user->$value;
                if ($user_field) return $user_field;
            }
        }

        return $user_field;
    }

    public function add_checkbox_field($submit_button, $args)
    {
        $auto_subscribe = Settings::instance()->mailoptin_comment_optin_subscribe_users();

        if ($auto_subscribe !== 'no') {

            $global_connection = Settings::instance()->mailoptin_comment_optin_integration_connections();

            if ( ! empty($global_connection)) {

                $subscribe_text = Settings::instance()->mailoptin_comment_optin_subscription_registration_message();

                ob_start();
                ?>
                <div id="mo-user-comment-field" style="margin-bottom:10px;">
                    <input type="checkbox" id="mo-user-comment-subscribe-user" name="mo_comment_optin_checkbox_user" value="1"/>
                    <label for="mo-user-comment-subscribe-user"><?= $subscribe_text ?></label>
                </div>
                <?php
                return ob_get_clean() . $submit_button;
            }
        }

        return $submit_button;
    }

    /**
     * @return array
     */
    public function comment_optin_fields()
    {
        $user_fields = [
            ''                     => '&mdash;&mdash;&mdash;',
            'comment_author'       => __('Comment Author Name', 'mailoptin'),
            'comment_author_email' => __('Comment Author Email', 'mailoptin'),
            'comment_author_url'   => __('Comment Author Website', 'mailoptin'),
            'comment_content'      => __('Comment Content', 'mailoptin'),
        ];

        return apply_filters('mo_comment_optin_custom_users_mapped_fields', $user_fields);
    }

    /**
     * @return array
     */
    public function add_user_fields($comment_fields)
    {
        $user_fields = [
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

        return array_unique(array_merge($comment_fields, $user_fields));
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