<?php

namespace MailOptin\GiveWPConnect;

use MailOptin\Core\Repositories\ConnectionsRepository;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;

class Settings extends \Give_Settings_Page
{
    public function __construct()
    {
        $this->id    = 'give-mailoptin';
        $this->label = __('MailOptin', 'mailoptin');

        add_action('give_admin_field_mailoptin_upsell', [$this, 'upsell_block']);
        add_action('give_admin_field_mailoptin_header', [$this, 'header_block']);

        parent::__construct();
    }

    public function get_settings()
    {
        $mo_gwp_settings = [
            [
                'id'   => 'mailoptin_gwp_settings',
                'name' => __('MailOptin Integration', 'mailoptin'),
                'type' => 'title'
            ]
        ];

        if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

            $connections       = GWPInit::get_instance()->email_service_providers();
            $saved_connections = give_get_option('mailoptin_gwp_integration_connections');

            $mo_gwp_settings[] = [
                'id'      => 'mailoptin_gwp_integration_connections',
                'name'    => __('Select Integration', 'mailoptin'),
                'desc'    => __('Select your email marketing software or CRM.', 'mailoptin'),
                'options' => $connections,
                'type'    => 'select',
                'class'   => 'mailoptin_gwp_fields'
            ];

            if ( ! empty($saved_connections)) {

                $lists = Init::mo_select_list_options($saved_connections);

                array_push($mo_gwp_settings, [
                    'id'      => 'mailoptin_gwp_integration_lists',
                    'name'    => __('Select List', 'mailoptin'),
                    'desc'    => __('Select the email list, audience or contact list to add customers.', 'mailoptin'),
                    'options' => $lists,
                    'type'    => 'select',
                    'class'   => 'mailoptin_gwp_fields'
                ]);

                $double_optins = $this->gwp_double_optin_settings($saved_connections);

                array_push($mo_gwp_settings, $double_optins);

                $saved_lists = give_get_option('mailoptin_gwp_integration_lists');

                if ( ! empty($saved_lists)) {

                    $mappable_fields = Init::merge_vars_field_map($saved_connections, $saved_lists);

                    if ( ! empty($mappable_fields)) {

                        $mo_gwp_settings[] = [
                            'name' => __('Map Fields', 'mailoptin'),
                            'type' => 'mailoptin_header'
                        ];

                        foreach ($mappable_fields as $key => $value) {

                            $mapped_key = rawurlencode('mailoptin_gwp_mapped_fields-' . $key);

                            array_push($mo_gwp_settings, [
                                'id'      => $mapped_key,
                                'name'    => $value,
                                'options' => GWPInit::get_instance()->gwp_fields(),
                                'type'    => 'select',
                                'class'   => 'mailoptin_gwp_fields'
                            ]);
                        }
                    }

                    array_push($mo_gwp_settings, $this->gwp_lead_tag_settings($saved_connections, true));
                }
            }

            $mo_gwp_settings[] = [
                'name' => __('Other Settings', 'mailoptin'),
                'type' => 'mailoptin_header'
            ];

            $mo_gwp_settings[] = [
                'id'      => 'mailoptin_gwp_subscribe_customers',
                'name'    => __('Subscription Method', 'mailoptin'),
                'desc'    => __('Choose "Ask for permission" to show an opt-in checkbox during the checkout. Customers will only be subscribed to the email marketing list if they check the checkbox. Choose Automatically to subscribe customers silently after purchase.', 'mailoptin'),
                'options' => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ],
                'type'    => 'select',
                'class'   => 'mailoptin_gwp_fields'
            ];

            $mo_gwp_settings[] = [
                'id'      => 'mailoptin_gwp_optin_checkbox_label',
                'name'    => __('Optin Checkbox Label', 'mailoptin'),
                'desc'    => __('This is only used if Subscribe customers is set to "Ask for permission" and It is the text that will display beside the optin checkbox.', 'mailoptin'),
                'default' => __('Subscribe to our newsletter', 'mailoptin'),
                'type'    => 'text',
                'class'   => 'mailoptin_gwp_fields'
            ];

        } else {
            $GLOBALS['give_hide_save_button'] = true;
            $mo_gwp_settings[]                = ['type' => 'mailoptin_upsell'];
        }

        // Docs link is always last.
        $mo_gwp_settings[] = [
            'name'  => __('MailOptin Docs Link', 'give-mailchimp'),
            'id'    => 'mailoptin_settings_docs_link',
            'url'   => esc_url('https://mailoptin.io/article/givewp-mailchimp-aweber-more/'),
            'title' => __('MailOptin Settings', 'give-mailchimp'),
            'type'  => 'give_docs_link',
        ];

        $mo_gwp_settings[] = [
            'id'   => 'give_mailoptin_settings',
            'type' => 'sectionend',
        ];

        return $mo_gwp_settings;
    }

    /**
     * @param array $sections The subsections
     *
     * @return array           The subsections with Mailchimp added
     */
    public function subsection($sections)
    {
        $sections['mailoptin'] = __('MailOptin', 'mailoptin');

        return $sections;
    }

    public function header_block($args)
    {
        echo '<tr><td colspan="2"><div class="mo-gwp-map-field-title"><span>' . esc_html($args['name']) . '</span></div></td><td></td></tr>';
    }

    public function upsell_block()
    {
        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=givewp_connection_settings';
        $doc_url    = 'https://mailoptin.io/article/givewp-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=givewp_connection_settings';

        $content = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add all donors and only donors that donated via specific donation forms to your email marketing list.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );

        $html = '<div class="mo-gwp-upsell-block">';
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
     * @param $saved_integration
     *
     * @return array|false
     */
    public function gwp_double_optin_settings($saved_integration)
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
                'id'   => 'mailoptin_gwp_double_optin',
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
    public function gwp_lead_tag_settings($saved_integration, $is_give_settings = false)
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

            $argss = [
                'id'       => 'mailoptin_gwp_select_tags',
                'name'     => esc_html__('Tags', 'mailoptin'),
                'desc'     => esc_html__('Select tags to assign to customers.', 'mailoptin'),
                'options'  => $options,
                'class'    => 'mo_gwp_select2 mailoptin_gwp_fields',
                'type'     => 'select',
                'multiple' => 'multiple'
            ];

            if ($is_give_settings) {
                $argss['type']      = 'chosen';
                $argss['data_type'] = 'multiselect';
                unset($argss['class']);
            }

            return $argss;

        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            return [
                'id'    => 'mailoptin_gwp_text_tags',
                'name'  => esc_html__('Tags', 'mailoptin'),
                'desc'  => esc_html__('Enter a comma-separated list of tags to assign to customers.', 'mailoptin'),
                'type'  => 'text',
                'class' => 'mailoptin_gwp_fields'
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
}