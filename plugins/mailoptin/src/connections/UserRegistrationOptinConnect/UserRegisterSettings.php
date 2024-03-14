<?php

namespace MailOptin\UserRegistrationOptinConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\PluginSettings\Settings;

class UserRegisterSettings
{

    public function __construct()
    {
        add_filter('mailoptin_settings_page', [$this, 'user_registration_optin_settings']);
    }

    public function user_registration_optin_settings($settings)
    {
        $user_registration_optin_settings['section_title'] = __('User Registration Optin', 'mailoptin');

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) :
            $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=user_registration_optin_connection_settings';
            $doc_url    = 'https://mailoptin.io/article/user-registration-optin-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=user_registration_optin_connection_settings';

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to subscribe new WordPress users after they register to your email marketing list.", 'mailoptin'),
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

            $user_registration_optin_settings['disable_submit_button'] = true;

            $user_registration_optin_settings['mailoptin_user_registration_optin_upsell'] = [
                'type' => 'arbitrary',
                'data' => $html
            ];

        else:
            $connections       = UserRegisterInit::email_service_providers();
            $saved_connections = Settings::instance()->mailoptin_user_registration_optin_integration_connections();

            $user_registration_optin_settings['mailoptin_user_registration_optin_description'] = [
                'type' => 'arbitrary',
                'data' => '<p>' . sprintf(
                        esc_html__('Upon user registration, this integration will subscribe users to your email marketing software and CRM. %sLearn more%s', 'mailoptin'),
                        '<a href="https://mailoptin.io/article/user-registration-optin-mailchimp-aweber-more/" target="_blank">', '</a>'
                    ) . '</p>',
            ];

            $user_registration_optin_settings['mailoptin_user_registration_optin_integration_connections'] = [
                'type'        => 'select',
                'label'       => __('Select Integration', 'mailoptin'),
                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                'options'     => $connections
            ];

            if ( ! empty($saved_connections) && $saved_connections != 'leadbank') {
                $saved_lists                                                                             = Settings::instance()->mailoptin_user_registration_optin_integration_lists();
                $lists                                                                                   = Init::mo_select_list_options($saved_connections);
                $user_registration_optin_settings['mailoptin_user_registration_optin_integration_lists'] = [
                    'type'        => 'select',
                    'label'       => __('Select List', 'mailoptin'),
                    'description' => __('Select the email list, audience or contact list to add sign-up users to.', 'mailoptin'),
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

                    $user_registration_optin_settings['mailoptin_user_registration_optin_double_optin'] = [
                        'type'           => 'checkbox',
                        'label'          => $label,
                        'description'    => __('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin'),
                    ];
                }

                if ( ! empty($saved_lists)) {
                    $mappable_fields = Init::merge_vars_field_map($saved_connections, $saved_lists);

                    if ( ! empty($mappable_fields)) {

                        $user_registration_optin_settings['mailoptin_user_registration_optin_field_mapping_section'] = [
                            'type' => 'arbitrary',
                            'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Map Fields', 'mailoptin')),
                        ];
                    }

                    foreach ($mappable_fields as $key => $value) {
                        $mapped_key                                    = rawurlencode('mailoptin_user_registration_optin_mapped_fields_' . $key);
                        $user_registration_optin_settings[$mapped_key] = [
                            'type'    => 'select',
                            'label'   => $value,
                            'options' => UserRegisterInit::get_instance()->user_registration_optin_fields()
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
                            $user_registration_optin_settings['mailoptin_user_registration_optin_select_tags'] = [
                                'type'        => 'select2',
                                'label'       => __('Tags', 'mailoptin'),
                                'description' => __('Select tags to assign to registered users', 'mailoptin'),
                                'options'     => $options
                            ];
                        }
                    } elseif (in_array($saved_connections, Init::text_tag_connections())) {
                        $user_registration_optin_settings['mailoptin_user_registration_optin_text_tags'] = [
                            'type'        => 'text',
                            'label'       => __('Tags', 'mailoptin'),
                            'description' => __('Enter a comma-separated list of tags to assign to registered users.', 'mailoptin')
                        ];
                    }
                }
            }

            $user_registration_optin_settings['mailoptin_user_registration_optin_other_setting_section'] = [
                'type' => 'arbitrary',
                'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Other Settings', 'mailoptin')),
            ];

            $user_registration_optin_settings['mailoptin_user_registration_optin_subscribe_users'] = [
                'type'        => 'select',
                'label'       => __('Subscribe Users', 'mailoptin'),
                'description' => __('Choose "Ask for permission" to show an opt-in checkbox during user registration. Users will only be subscribed to the email marketing list if they check the checkbox. Choose Automatically to subscribe users silently upon registration. Caution, this is without the user\'s consent.', 'mailoptin'),
                'options'     => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ]
            ];


            $user_registration_optin_settings['mailoptin_user_registration_optin_subscription_registration_message'] = [
                'type'        => 'textarea',
                'label'       => __('Optin Checkbox Label', 'mailoptin'),
                'description' => __('This is only used if Subscribe Users is set to "Ask for permission" and it is the text that will display beside the optin checkbox.', 'mailoptin'),
                'value'       => __('Subscribe to our newsletter', 'mailoptin'),
            ];

        endif;

        $settings['user_registration_optin_settings'] = apply_filters('mailoptin_settings_user_registration_optin_settings_page', [
            'tab_title' => __('User Registration Optin', 'mailoptin'),
            $user_registration_optin_settings
        ]);

        return $settings;
    }

    /**
     * @return UserRegisterSettings
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