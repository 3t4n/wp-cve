<?php

namespace MailOptin\WordPressUserRegistrationConnect;

use MailOptin\Core\Connections\AbstractConnect;

class Subscription extends AbstractConnect
{
    public $email;
    public $name;
    public $role;
    public $extras;

    public function __construct($email, $name, $role, $extras)
    {
        $this->email  = $email;
        $this->name   = $name;
        $this->role   = sanitize_text_field($role);
        $this->extras = $extras;

        parent::__construct();
    }

    public function subscribe()
    {
        $name_split = self::get_first_last_names($this->name);

        $user_fields = [];

        $password_mapped_flag = true;

        $custom_field_mappings = $this->form_custom_field_mappings();

        if (is_array($custom_field_mappings) && ! empty($custom_field_mappings)) {

            foreach ($custom_field_mappings as $wordpressUserFieldKey => $customFieldKey) {
                $value = $this->extras[$customFieldKey];

                $user_fields[$wordpressUserFieldKey] = esc_html($value);
            }
        }

        $user_data = array_filter($user_fields, [$this, 'data_filter']);

        if ( ! isset($user_data['user_login']) || empty($user_data['user_login'])) {
            $user_data['user_login'] = $this->email;
        }

        if ( ! isset($user_data['user_pass']) || empty($user_data['user_pass'])) {
            $password_mapped_flag   = false;
            $user_data['user_pass'] = wp_generate_password(12, false);
        }

        $lead_data = [
            'user_email' => sanitize_email($this->email),
            'first_name' => sanitize_textarea_field($name_split[0]),
            'last_name'  => sanitize_textarea_field($name_split[1]),
        ];

        $lead_data = array_merge($user_data, $lead_data);

        $lead_data['role'] = $this->role == 'administrator' ? '' : $this->role;

        $lead_data = apply_filters('mo_connections_wordpress_user_registration_parameters', $lead_data, $this);

        $lead_data = array_filter($lead_data, [$this, 'data_filter']);

        $user_id = wp_insert_user($lead_data);

        if (is_wp_error($user_id)) {
            return parent::ajax_failure($user_id->get_error_message());
        }

        $autologin = apply_filters(
            'mo_connections_wordpress_user_registration_autologin',
            $this->get_integration_data('WordPressUserRegistrationConnect_autologin', [], false),
            $lead_data,
            $this
        );

        if (true === $autologin) {
            $secure_cookie = '';
            // If the user wants ssl but the session is not ssl, force a secure cookie.
            if ( ! force_ssl_admin()) {
                if (get_user_option('use_ssl', $user_id)) {
                    $secure_cookie = true;
                    force_ssl_admin(true);
                }
            }

            wp_set_auth_cookie($user_id, true, $secure_cookie);
            wp_set_current_user($user_id);
        }

        if (apply_filters('mo_connections_wordpress_user_registration_admin_email', true, $lead_data, $this)) {
            wp_send_new_user_notifications($user_id, 'admin');
        }

        if (apply_filters('mo_connections_wordpress_user_registration_user_email', true, $lead_data, $this) && ! $password_mapped_flag) {
            wp_send_new_user_notifications($user_id, 'user');
        }

        return parent::ajax_success();
    }
}