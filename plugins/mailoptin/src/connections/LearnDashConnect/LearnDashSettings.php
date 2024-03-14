<?php

namespace MailOptin\LearnDashConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\PluginSettings\Settings;

class LearnDashSettings
{
    public function __construct()
    {
        add_filter('mailoptin_settings_page', [$this, 'learndash_settings']);
    }

    public function learndash_settings($settings)
    {
        $learndash_settings['section_title'] = __('LearnDash Integration Settings', 'mailoptin');

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) :
            $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=learndash_connection_settings';
            $doc_url    = 'https://mailoptin.io/article/learndash-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=learndash_connection_settings';

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to add all Learndash students and students that are enrolled to specific courses to your email marketing list.", 'mailoptin'),
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

            $learndash_settings['disable_submit_button'] = true;

            $learndash_settings['mailoptin_learndash_upsell'] = [
                'type' => 'arbitrary',
                'data' => $html
            ];

        else :
            $connections       = LearnDashInit::email_service_providers();
            $saved_connections = Settings::instance()->mailoptin_learndash_integration_connections();

            $learndash_settings['mailoptin_learndash_description'] = [
                'type' => 'arbitrary',
                'data' => '<p>' . sprintf(
                        esc_html__('Upon course enrollment, the LearnDash LMS integration subscribes students to your email marketing software and CRM. %sLearn more%s', 'mailoptin'),
                        '<a href="https://mailoptin.io/article/learndash-mailchimp-aweber-more/" target="_blank">', '</a>'
                    ) . '</p>',
            ];

            $learndash_settings['mailoptin_learndash_integration_connections'] = [
                'type'        => 'select',
                'label'       => __('Select Integration', 'mailoptin'),
                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                'options'     => $connections
            ];

            if ( ! empty($saved_connections) && $saved_connections != 'leadbank') {
                $saved_lists                                                 = Settings::instance()->mailoptin_learndash_integration_lists();
                $lists                                                       = Init::mo_select_list_options($saved_connections);
                $learndash_settings['mailoptin_learndash_integration_lists'] = [
                    'type'        => 'select',
                    'label'       => __('Select List', 'mailoptin'),
                    'description' => __('Select the email list, audience or contact list to add enrolled students to.', 'mailoptin'),
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

                    $learndash_settings['mailoptin_learndash_double_optin'] = [
                        'type'           => 'checkbox',
                        'label'          => $label,
                        'description'    => __('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin'),
                    ];
                }

                if ( ! empty($saved_lists)) {
                    $mappable_fields = Init::merge_vars_field_map($saved_connections, $saved_lists);

                    if ( ! empty($mappable_fields)) {

                        $learndash_settings['mailoptin_learndash_field_mapping_section'] = [
                            'type' => 'arbitrary',
                            'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Map Fields', 'mailoptin')),
                        ];
                    }

                    foreach ($mappable_fields as $key => $value) {
                        $mapped_key                      = rawurlencode('mailoptin_learndash_mapped_fields_' . $key);
                        $learndash_settings[$mapped_key] = [
                            'type'    => 'select',
                            'label'   => $value,
                            'options' => LearnDashInit::get_instance()->learndash_fields()
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
                            $learndash_settings['mailoptin_learndash_select_tags'] = [
                                'type'        => 'select2',
                                'label'       => __('Tags', 'mailoptin'),
                                'description' => __('Select tags to assign to enrolled students', 'mailoptin'),
                                'options'     => $options
                            ];
                        }
                    } elseif (in_array($saved_connections, Init::text_tag_connections())) {
                        $learndash_settings['mailoptin_learndash_text_tags'] = [
                            'type'        => 'text',
                            'label'       => __('Tags', 'mailoptin'),
                            'description' => __('Enter a comma-separated list of tags to assign to enrolled students. The course title is automatically included in the list of tags.', 'mailoptin')
                        ];
                    }
                }
            }

            $learndash_settings['mailoptin_learndash_other_setting_section'] = [
                'type' => 'arbitrary',
                'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Other Settings', 'mailoptin')),
            ];

            $learndash_settings['mailoptin_learndash_subscribe_students'] = [
                'type'        => 'select',
                'label'       => __('Subscribe Students', 'mailoptin'),
                'description' => __('Choose "Ask for permission" to show an opt-in checkbox during the course and group enrollment. Students will only be subscribed to the email marketing list if they check the checkbox. Choose Automatically to subscribe users silently upon enrollment. Caution, this is without the customer\'s consent.', 'mailoptin'),
                'options'     => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ]
            ];


            $learndash_settings['mailoptin_learndash_subscription_registration_message'] = [
                'type'        => 'textarea',
                'label'       => __('Subscription Registration Message', 'mailoptin'),
                'description' => __('This is only used if Subscribe students is set to "Ask for permission" and it is the text that will display beside the optin checkbox.', 'mailoptin'),
                'value'       => __('Subscribe to our newsletter', 'mailoptin'),
            ];

            $learndash_settings['mailoptin_learndash_subscription_success_message'] = [
                'type'        => 'textarea',
                'label'       => __('Subscription Success Message', 'mailoptin'),
                'description' => __('This is only used if Subscribe students is set to "Ask for permission" and it is the text that will be displayed when the students has already subscribed.', 'mailoptin'),
                'value'       => __('Subscription successful', 'mailoptin'),
            ];

        endif;

        $settings['learndash_settings'] = apply_filters('mailoptin_settings_learndash_settings_page', [
            'tab_title' => __('LearnDash Integration', 'mailoptin'),
            $learndash_settings
        ]);

        return $settings;

    }

    public function process_submission($course_id, $user_id)
    {
        $field_map = [];

        $connection_service = Settings::instance()->mailoptin_learndash_integration_connections();

        if (empty($connection_service)) return;

        $connection_email_list = Settings::instance()->mailoptin_learndash_integration_lists();

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mailoptin_learndash_mapped_fields_' . $key);
            $field_map[$key] = Settings::instance()->$mapped_key();
        }

        $user_data = get_userdata($user_id);

        //get the email
        $email = $user_data->user_email;

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = LearnDashInit::get_instance()->get_field_value($value, $user_id);
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = Settings::instance()->mailoptin_learndash_double_optin() == "true";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $course             = get_post($course_id);
            $form_tags          = Settings::instance()->mailoptin_learndash_text_tags();
            $exploded_form_tags = explode(',', $form_tags);
            array_push($exploded_form_tags, $course->post_title);

            $form_tags = implode(',', array_filter($exploded_form_tags));

        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_learndash_select_tags();
        }

        $optin_data = new ConversionDataBuilder();


        $first_name = $user_data->first_name;
        $last_name  = $user_data->last_name;
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'LearnDash';

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

            $field_value = LearnDashInit::get_instance()->get_field_value($value, $user_id);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * @return LearnDashSettings
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