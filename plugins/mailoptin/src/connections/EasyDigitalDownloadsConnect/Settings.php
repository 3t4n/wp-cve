<?php

namespace MailOptin\EasyDigitalDownloadsConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;

class Settings
{
    public function __construct()
    {
        $settings_tab = $this->get_settings_tab();
        add_filter("edd_settings_sections_{$settings_tab}", [$this, 'subsection'], 10, 1);
        add_filter("edd_settings_{$settings_tab}", [$this, 'settings']);
        add_action('edd_mailoptin_upsell', [$this, 'upsell_block']);
    }

    /**
     * Register our subsection for EDD 2.5
     *
     * @param array $sections The subsections
     *
     * @return array           The subsections with Mailchimp added
     */
    public function subsection($sections)
    {
        $sections['mailoptin'] = __('MailOptin', 'mailoptin');

        return $sections;
    }

    public function upsell_block()
    {
        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=edd_connection_settings';
        $doc_url    = 'https://mailoptin.io/article/edd-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=edd_connection_settings';

        $content = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add all customers and customers that purchase products belonging to specific categories or tags to your email marketing list.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );

        $html = '<div class="mo-edd-upsell-block">';
        $html .= sprintf('<p>%s</p>', $content);
        $html .= sprintf('<p><a href="%s" style="margin-right: 10px;" class="button-primary" target="_blank">', $upsell_url);
        $html .= esc_html__('Upgrade to MailOptin Premium', 'mailoptin');
        $html .= '</a>';
        $html .= sprintf('<a href="%s" target="_blank">', $doc_url);
        $html .= esc_html__('Learn more', 'mailoptin');
        $html .= '</a>';
        $html .= '</p>';
        $html .= '</div>';

        echo $html;
    }

    /**
     * Registers the plugin settings
     */
    public function settings($settings)
    {

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            $connections       = EDDInit::get_instance()->email_service_providers();
            $saved_connections = edd_get_option('mailoptin_edd_integration_connections');

            $mo_edd_settings = [
                [
                    'id'    => 'mailoptin_edd_settings',
                    'name'  => '<strong>' . __('MailOptin Integration', 'mailoptin') . '</strong>',
                    'desc'  => __('Configure MailOptin integration', 'mailoptin'),
                    'type'  => 'header',
                    'class' => 'mailoptin_edd_header'
                ],
                [
                    'id'      => 'mailoptin_edd_integration_connections',
                    'name'    => __('Select Integration', 'mailoptin'),
                    'desc'    => __('Select your email marketing software or CRM.', 'mailoptin'),
                    'options' => $connections,
                    'type'    => 'select',
                    'class'   => 'mailoptin_edd_fields'
                ],
            ];

            if ( ! empty($saved_connections)) {

                $lists = Init::mo_select_list_options($saved_connections);

                array_push($mo_edd_settings, [
                    'id'      => 'mailoptin_edd_integration_lists',
                    'name'    => __('Select List', 'mailoptin'),
                    'desc'    => __('Select the email list, audience or contact list to add customers.', 'mailoptin'),
                    'options' => $lists,
                    'type'    => 'select',
                    'class'   => 'mailoptin_edd_fields'
                ]);

                $double_optins = $this->edd_double_optin_settings($saved_connections);

                array_push($mo_edd_settings, $double_optins);

                $saved_lists = edd_get_option('mailoptin_edd_integration_lists');

                if ( ! empty($saved_lists)) {
                    foreach (Init::merge_vars_field_map($saved_connections, $saved_lists) as $key => $value) {
                        $mapped_key = rawurlencode('mailoptin_edd_mapped_fields-' . $key);

                        array_push($mo_edd_settings, [
                            'id'      => $mapped_key,
                            'name'    => $value,
                            'options' => EDDInit::get_instance()->edd_fields(),
                            'type'    => 'select',
                            'class'   => 'mailoptin_edd_fields'
                        ]);
                    }
                }

                if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                    array_push($mo_edd_settings, $this->edd_lead_tag_settings($saved_connections));
                }
            }

            $mo_edd_settings[] = [
                'id'    => 'mailoptin_edd_other_settings',
                'name'  => '<strong>' . __('Other Settings', 'mailoptin') . '</strong>',
                'type'  => 'header',
                'class' => 'mailoptin_edd_sub_header'
            ];

            $mo_edd_settings[] = [
                'id'      => 'mailoptin_edd_subscribe_customers',
                'name'    => __('Subscribe Customers', 'mailoptin'),
                'desc'    => __('Choose "Ask for permission" to show an opt-in checkbox during the checkout. Customers will only be subscribed to the email marketing list if they check the checkbox. Choose Automatically to subscribe customers silently after purchase.', 'mailoptin'),
                'options' => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ],
                'type'    => 'select',
                'class'   => 'mailoptin_edd_fields'
            ];

            $mo_edd_settings[] = [
                'id'   => 'mailoptin_edd_subscription_checked_default_value',
                'name' => __('Subscription Checked by Default', 'mailoptin'),
                'desc' => __('Decide whether the newsletter signup checkbox displayed in checkout page should be checked by default or not? Only used if Subscribe customers is set to "Ask for permission"', 'mailoptin'),
                'type' => 'checkbox'
            ];

            $mo_edd_settings[] = [
                'id'    => 'mailoptin_edd_subscription_registration_message',
                'name'  => __('Optin Checkbox Label', 'mailoptin'),
                'desc'  => __('This is only used if Subscribe customers is set to "Ask for permission" and It is the text that will display beside the optin checkbox.', 'mailoptin'),
                'std'   => __('Subscribe to our newsletter', 'mailoptin'),
                'type'  => 'textarea',
                'class' => 'mailoptin_edd_fields'
            ];
        } else {
            $mo_edd_settings = [
                [
                    'id'   => 'mailoptin_upsell',
                    'type' => 'hook',
                ],
            ];
        }

        if (version_compare(EDD_VERSION, 2.5, '>=')) {
            $mo_edd_settings = array('mailoptin' => $mo_edd_settings);
        }

        return array_merge($settings, $mo_edd_settings);
    }

    /**
     * @param $saved_integration
     *
     * @return array|false
     */
    public function edd_double_optin_settings($saved_integration)
    {
        if (empty($saved_integration)) return false;

        $is_double_optin          = false;
        $double_optin_connections = \MailOptin\Connections\Init::double_optin_support_connections();
        foreach ($double_optin_connections as $key => $value) {
            if ($saved_integration === $key) {
                $is_double_optin = $value;
            }
        }

        if (in_array($saved_integration, Init::double_optin_support_connections(true))) {
            return [
                'id'   => 'mailoptin_edd_double_optin',
                'name' => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'desc' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'type' => 'checkbox'
            ];
        }

        return [];
    }


    /**
     *
     * @param $saved_integration
     *
     * @return array|false|void
     */
    public function edd_lead_tag_settings($saved_integration)
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

            return [
                'id'       => 'mailoptin_edd_select_tags',
                'name'     => esc_html__('Tags', 'mailoptin'),
                'desc'     => esc_html__('Select tags to assign to customers.', 'mailoptin'),
                'options'  => $options,
                'class'    => 'mo_edd_select2 mailoptin_edd_fields',
                'type'     => 'select',
                'multiple' => 'multiple'
            ];

        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            return [
                'id'    => 'mailoptin_edd_text_tags',
                'name'  => esc_html__('Tags', 'mailoptin'),
                'desc'  => esc_html__('Enter a comma-separated list of tags to assign to customers.', 'mailoptin'),
                'type'  => 'text',
                'class' => 'mailoptin_edd_fields'
            ];
        }
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

    public function process_submission($payment_meta)
    {
        $field_map = [];

        $connection_service = edd_get_option('mailoptin_edd_integration_connections');

        if (empty($connection_service)) return;

        $connection_email_list = edd_get_option('mailoptin_edd_integration_lists');

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key      = rawurlencode('mailoptin_edd_mapped_fields-' . $key);
            $field_map[$key] = edd_get_option($mapped_key);
        }

        $user_info = $payment_meta['user_info'];

        $email = $payment_meta['email'];

        if (empty($email)) return;

        $payload = [];

        foreach ($field_map as $key => $value) {
            if ($value) {
                $payload[$key] = EDDInit::get_instance()->get_field_value($value, $payment_meta['email']);
            }
        }

        $double_optin = false;
        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = edd_get_option('mailoptin_edd_double_optin') == '1';
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {

            $downloads = $payment_meta['downloads'];

            $form_tags = edd_get_option('mailoptin_edd_text_tags', '');

            $exploded_form_tags = explode(',', $form_tags);

            foreach ($downloads as $download) {
                array_push($exploded_form_tags, get_the_title($download['id']));
            }

            $form_tags = implode(',', array_filter($exploded_form_tags));

        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            if ( ! empty(edd_get_option('mailoptin_edd_select_tags'))) {
                $form_tags = edd_get_option('mailoptin_edd_select_tags');
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
        $optin_data->optin_campaign_type = 'Easy Digital Downloads';

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

            $field_value = EDDInit::get_instance()->get_field_value($value, $payment_meta['email']);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        return AbstractConnect::is_ajax_success($response);
    }

    /**
     * Gets the correct settings tab for MailOptin.
     * In EDD 2.11.4, email marketing settings have been moved to the marketing tab, not extensions.
     *
     * @return string
     */
    private function get_settings_tab()
    {
        return version_compare(EDD_VERSION, '2.11.4', '>=') && array_key_exists('marketing', edd_get_settings_tabs()) ? 'marketing' : 'extensions';
    }

    /**
     * @return Settings|null
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