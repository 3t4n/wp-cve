<?php

namespace MailOptin\Connections\UltimateMemberConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\UltimateMemberConnect\UMInit;

class UMSettings
{
    public function __construct()
    {
        add_filter('mailoptin_settings_page', [$this, 'ultimatemember_settings']);
    }

    public function process_submission($user_id)
    {
        $connection_service = Settings::instance()->mailoptin_ultimatemember_integration_connections();

        if (empty($connection_service)) return;

        $connection_email_list = Settings::instance()->mailoptin_ultimatemember_integration_lists();

        $field_map = [];

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key = rawurlencode('mailoptin_ultimatemember_mapped_fields_' . $key);
            if ( ! empty(Settings::instance()->$mapped_key())) {
                $field_map[$key] = Settings::instance()->$mapped_key();
            }
        }

        $user = get_userdata($user_id);

        //get the email mapped
        $email = $user->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = isset($user->$value) ? $user->$value : '';
        }

        $double_optin = false;

        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = Settings::instance()->mailoptin_ultimatemember_double_optin() == "true";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_ultimatemember_text_tags();
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_ultimatemember_select_tags();
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $user->first_name ?? '';
        $last_name  = $user->last_name ?? '';
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'Ultimate Member';

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

            $field_value = isset($user->$value) ? $user->$value : '';

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        AjaxHandler::do_optin_conversion($optin_data);
    }

    public function ultimatemember_settings($settings)
    {
        $ultimatemember_settings['section_title'] = __('Ultimate Member Integration Settings', 'mailoptin');

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) :
            $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=ultimatemember_connection_settings';
            $doc_url    = 'https://mailoptin.io/article/ultimate-member-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=ultimatemember_connection_settings';

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to add all users that register via Ultimate Member registration forms to your email marketing list.", 'mailoptin'),
                '<a target="_blank" href="' . $upsell_url . '">',
                '</a>'
            );

            $html = '<div class="mo-external-upsell-block">';
            $html .= sprintf('<p>%s</p>', $content);
            $html .= sprintf('<p><a href="%s" style="margin-right: 10px;" class="button-primary" target="_blank">', $upsell_url);
            $html .= esc_html__('Upgrade to MailOptin Premium', 'mailoptin');
            $html .= '</a>';
            $html .= sprintf('<a href="%s" target="_blank">', $doc_url);
            $html .= esc_html__('Learn more', 'mailoptin');
            $html .= '</a>';
            $html .= '</p>';
            $html .= '</div>';

            $ultimatemember_settings['disable_submit_button'] = true;

            $ultimatemember_settings['mailoptin_ultimatemember_upsell'] = [
                'type' => 'arbitrary',
                'data' => $html
            ];

        else :
            $connections       = UMInit::email_service_providers();
            $saved_connections = Settings::instance()->mailoptin_ultimatemember_integration_connections();

            $ultimatemember_settings['mailoptin_ultimatemember_description'] = [
                'type' => 'arbitrary',
                'data' => '<p>' . sprintf(
                        esc_html__('The Ultimate Member integration subscribes customers to your email marketing software and CRM upon order completion. You can also set this up on a per product, category and tag level. %sLearn more%s', 'mailoptin'),
                        '<a href="https://mailoptin.io/article/ultimate-member-mailchimp-aweber-more/" target="_blank">', '</a>'
                    ) . '</p>',
            ];

            $ultimatemember_settings['mailoptin_ultimatemember_integration_connections'] = [
                'type'        => 'select',
                'label'       => __('Select Integration', 'mailoptin'),
                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                'options'     => $connections
            ];

            if ( ! empty($saved_connections) && $saved_connections != 'leadbank') {
                $saved_lists                                                           = Settings::instance()->mailoptin_ultimatemember_integration_lists();
                $lists                                                                 = Init::mo_select_list_options($saved_connections);
                $ultimatemember_settings['mailoptin_ultimatemember_integration_lists'] = [
                    'type'        => 'select',
                    'label'       => __('Select List', 'mailoptin'),
                    'description' => __('Select the email list, audience or contact list to add customers to.', 'mailoptin'),
                    'options'     => $lists
                ];

                if (in_array($saved_connections, Init::double_optin_support_connections(true)) && defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                    $is_double_optin          = false;
                    $double_optin_connections = Init::double_optin_support_connections();
                    foreach ($double_optin_connections as $key => $value) {
                        if ($saved_connections === $key) {
                            $is_double_optin = $value;
                        }
                    }

                    $label = ($is_double_optin === false) ? __('Enable Double Optin', 'mailoptin') : __('Disable Double Optin', 'mailoptin');

                    $ultimatemember_settings['mailoptin_ultimatemember_double_optin'] = [
                        'type'        => 'checkbox',
                        'label'       => $label,
                        'checkbox_label' => esc_html__('Disable', 'mailoptin'),
                        'description' => __('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                    ];
                }

                if ( ! empty($saved_lists)) {

                    $mappable_fields = Init::merge_vars_field_map($saved_connections, $saved_lists);

                    if ( ! empty($mappable_fields)) {

                        $ultimatemember_settings['mailoptin_ultimatemember_field_mapping_section'] = [
                            'type' => 'arbitrary',
                            'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Map Fields', 'mailoptin')),
                        ];
                    }

                    foreach ($mappable_fields as $key => $value) {
                        $mapped_key                           = rawurlencode('mailoptin_ultimatemember_mapped_fields_' . $key);
                        $ultimatemember_settings[$mapped_key] = [
                            'type'    => 'select',
                            'label'   => $value,
                            'options' => $this->um_custom_fields()
                        ];
                    }

                    if (in_array($saved_connections, Init::select2_tag_connections())) {
                        $tags     = [];
                        $instance = ConnectionFactory::make($saved_connections);
                        if (is_object($instance) && method_exists($instance, 'get_tags')) {
                            $tags = $instance->get_tags();
                        }

                        $options = [];

                        foreach ($tags as $value => $label) {
                            if (empty($value)) continue;

                            $options[$value] = $label;
                        }

                        if ( ! empty($options)) {
                            $ultimatemember_settings['mailoptin_ultimatemember_select_tags'] = [
                                'type'        => 'select2',
                                'label'       => __('Tags', 'mailoptin'),
                                'description' => __('Select tags to assign to buyers or customers.', 'mailoptin'),
                                'options'     => $options
                            ];
                        }
                    } elseif (in_array($saved_connections, Init::text_tag_connections())) {
                        $ultimatemember_settings['mailoptin_ultimatemember_text_tags'] = [
                            'type'        => 'text',
                            'label'       => __('Tags', 'mailoptin'),
                            'description' => __('Enter a comma-separated list of tags to assign to buyers or customers.', 'mailoptin')
                        ];
                    }
                }
            }

            $ultimatemember_settings['mailoptin_ultimatemember_other_setting_section'] = [
                'type' => 'arbitrary',
                'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Other Settings', 'mailoptin')),
            ];

            $ultimatemember_settings['mailoptin_ultimatemember_subscribe_customers'] = [
                'type'        => 'select',
                'label'       => __('Subscription Method', 'mailoptin'),
                'description' => __('Choose "Ask for permission" to show an opt-in checkbox during checkout. Customers will only be subscribed to the email marketing list above if they check the checkbox. Choose Automatically to subscribe customers silently upon checkout. Caution, this is without the customer\'s consent.', 'mailoptin'),
                'options'     => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ]
            ];


            $ultimatemember_settings['mailoptin_ultimatemember_field_label'] = [
                'type'        => 'textarea',
                'label'       => __('Opt-In Field Label', 'mailoptin'),
                'description' => __('Customize the label displayed next to the opt-in checkbox.', 'mailoptin'),
                'value'       => __('Subscribe to our newsletter', 'mailoptin'),
            ];

            $ultimatemember_settings['mailoptin_ultimatemember_checkbox_default'] = [
                'type'        => 'select',
                'label'       => __('Opt-In Checkbox Default', 'mailoptin'),
                'description' => __('The default state of the opt-in checkbox.', 'mailoptin'),
                'options'     => [
                    'unchecked' => __('Unchecked', 'mailoptin'),
                    'checked'   => __('Checked', 'mailoptin'),
                ]
            ];

        endif;

        $settings['ultimatemember_settings'] = apply_filters('mailoptin_settings_ultimatemember_settings_page', [
            'tab_title' => __('Ultimate Member Integration', 'mailoptin'),
            $ultimatemember_settings
        ]);

        return $settings;
    }

    public function um_custom_fields()
    {
        $core_user_fields = [
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

        $fields = $core_user_fields;

        $user_fields = UM()->builtin()->all_user_fields;

        $skips = array_merge(['username', 'user_password'], array_keys($core_user_fields));

        foreach ($user_fields as $key => $user_field) {

            if ($user_field['account_only'] === true) continue;

            if (in_array($key, $skips)) continue;

            $fields[$key] = $user_field['title'];
        }

        return apply_filters('mo_ultimate_member_custom_users_mapped_fields', $fields);
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