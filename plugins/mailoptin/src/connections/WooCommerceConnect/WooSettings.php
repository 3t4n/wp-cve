<?php

namespace MailOptin\WooCommerceConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\PluginSettings\Settings;

class WooSettings
{
    public function __construct()
    {
        add_filter('mailoptin_settings_page', [$this, 'woocommerce_settings']);
        $this->register_checkout_hooks();
    }

    public function register_checkout_hooks()
    {
        $woocommerce_opt_in_display_location = Settings::instance()->mailoptin_woocommerce_checkbox_location();

        if (empty($woocommerce_opt_in_display_location)) {
            $woocommerce_opt_in_display_location = 'woocommerce_review_order_before_submit';
        }

        add_action($woocommerce_opt_in_display_location, [$this, 'add_checkout_fields']);

        add_action('woocommerce_checkout_update_order_meta', [$this, 'save_checkout_fields']);
        add_action('woocommerce_before_pay_action', function ($order) {
            $this->save_checkout_fields($order->get_id());
        });
    }

    public function add_checkout_fields()
    {
        $display_opt_in = Settings::instance()->mailoptin_woocommerce_subscribe_customers();

        if ('yes' === $display_opt_in) {
            $checkbox_default = Settings::instance()->mailoptin_woocommerce_checkbox_default();
            $checkbox_label   = Settings::instance()->mailoptin_woocommerce_field_label();
            $checked          = '';

            if ($checkbox_default === 'checked') $checked = 'checked="checked"';

            ob_start();
            ?>
            <p class="form-row mo-woocommerce-opt-in">
                <label class="mo-woocommerce-label checkbox" for="mailoptin_woocommerce_optin_checkbox">
                    <input type="checkbox" name="mailoptin_woocommerce_optin_checkbox" id="mailoptin_woocommerce_optin_checkbox" class="mo-woocommerce-input-checkbox" value="yes" <?php echo $checked ?>/>
                    <span class="mo-woocommerce-checkbox-text">
                      <?php echo $checkbox_label; ?>
                    </span>
                </label>
            </p>
            <?php
            echo apply_filters('mailoptin_woocommerce_opt_in_checkbox', ob_get_clean());
        }
    }

    /**
     * @param $order_id
     */
    public function save_checkout_fields($order_id)
    {
        $display_opt_in = Settings::instance()->mailoptin_woocommerce_subscribe_customers();

        if ('yes' == $display_opt_in) {

            $opt_in = isset($_POST['mailoptin_woocommerce_optin_checkbox']) ? 'yes' : 'no';

            if (WooInit::is_use_post_meta_storage()) {
                update_post_meta($order_id, 'mailoptin_woocommerce_optin_checkbox', $opt_in);
            } else {
                $order = wc_get_order($order_id);
                if ($order) {
                    $order->update_meta_data('mailoptin_woocommerce_optin_checkbox', $opt_in);
                    $order->save();
                }
            }
        }
    }

    public function process_submission($order)
    {
        $field_map = [];

        $connection_service    = Settings::instance()->mailoptin_woocommerce_integration_connections();
        $connection_email_list = Settings::instance()->mailoptin_woocommerce_integration_lists();

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key = rawurlencode('mailoptin_woocommerce_mapped_fields_' . $key);
            if ( ! empty(Settings::instance()->$mapped_key())) {
                $field_map[$key] = Settings::instance()->$mapped_key();
            }
        }

        //get the email mapped
        $email = WooInit::get_instance()->get_email_address($order);

        if (empty($email)) {
            $logger = wc_get_logger();
            $logger->debug('A valid Email address must be provided.', array('source' => 'mailoptin'));

            return;
        }

        $payload = [];

        foreach ($field_map as $key => $value) {
            $payload[$key] = WooInit::get_instance()->get_field_value($value, $order);
        }

        $double_optin = false;

        if (in_array($connection_service, Init::double_optin_support_connections(true))) {
            $double_optin = Settings::instance()->mailoptin_woocommerce_double_optin() == "true";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_woocommerce_text_tags();
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $form_tags = Settings::instance()->mailoptin_woocommerce_select_tags();
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = WooInit::get_instance()->get_first_name($order);
        $last_name  = WooInit::get_instance()->get_last_name($order);
        $name       = Init::get_full_name($first_name, $last_name);

        $optin_data->optin_campaign_id   = 0; // since it's non mailoptin form, set it to zero.
        $optin_data->payload             = $payload;
        $optin_data->name                = Init::return_name($name, $first_name, $last_name);
        $optin_data->email               = $email;
        $optin_data->optin_campaign_type = 'WooCommerce';

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

            $field_value = WooInit::get_instance()->get_field_value($value, $order);

            if (empty($field_value)) continue;

            $optin_data->form_custom_field_mappings[$name] = $name;
        }

        $response = AjaxHandler::do_optin_conversion($optin_data);

        if ( ! AbstractConnect::is_ajax_success($response)) {
            $logger = wc_get_logger();
            $logger->debug('Unable to add subscriber via MailOptin: ', array('source' => 'mailoptin'));
        }
    }

    public function woocommerce_settings($settings)
    {
        $woocommerce_settings['section_title'] = __('WooCommerce Integration Settings', 'mailoptin');

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) :
            $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection_settings';
            $doc_url    = 'https://mailoptin.io/article/woocommerce-mailchimp-aweber-more/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection_settings';

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to add all WooCommerce customers and customers that purchase products belonging to a specific category or tag to your email marketing list.", 'mailoptin'),
                '<a target="_blank" href="' . $upsell_url . '">',
                '</a>'
            );

            $html = '<div class="mo-woo-upsell-block">';
            $html .= sprintf('<p>%s</p>', $content);
            $html .= sprintf('<p><a href="%s" style="margin-right: 10px;" class="button-primary" target="_blank">', $upsell_url);
            $html .= esc_html__('Upgrade to MailOptin Premium', 'mailoptin');
            $html .= '</a>';
            $html .= sprintf('<a href="%s" target="_blank">', $doc_url);
            $html .= esc_html__('Learn more', 'mailoptin');
            $html .= '</a>';
            $html .= '</p>';
            $html .= '</div>';

            $woocommerce_settings['disable_submit_button'] = true;

            $woocommerce_settings['mailoptin_woocommerce_upsell'] = [
                'type' => 'arbitrary',
                'data' => $html
            ];

        else :
            $connections       = WooInit::email_service_providers();
            $saved_connections = Settings::instance()->mailoptin_woocommerce_integration_connections();

            $woocommerce_settings['mailoptin_woocommerce_description'] = [
                'type' => 'arbitrary',
                'data' => '<p>' . sprintf(
                        esc_html__('The WooCommerce integration subscribes customers to your email marketing software and CRM upon order completion. You can also set this up on a per product, category and tag level. %sLearn more%s', 'mailoptin'),
                        '<a href="https://mailoptin.io/article/woocommerce-mailchimp-aweber-more/" target="_blank">', '</a>'
                    ) . '</p>',
            ];

            $woocommerce_settings['mailoptin_woocommerce_integration_connections'] = [
                'type'        => 'select',
                'label'       => __('Select Integration', 'mailoptin'),
                'description' => __('Select your email marketing software or CRM.', 'mailoptin'),
                'options'     => $connections
            ];

            if ( ! empty($saved_connections) && $saved_connections != 'leadbank') {
                $saved_lists                                                     = Settings::instance()->mailoptin_woocommerce_integration_lists();
                $lists                                                           = Init::mo_select_list_options($saved_connections);
                $woocommerce_settings['mailoptin_woocommerce_integration_lists'] = [
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

                    $woocommerce_settings['mailoptin_woocommerce_double_optin'] = [
                        'type'        => 'checkbox',
                        'label'       => $label,
                        'description' => __('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                    ];
                }

                if ( ! empty($saved_lists)) {

                    $mappable_fields = Init::merge_vars_field_map($saved_connections, $saved_lists);

                    if ( ! empty($mappable_fields)) {

                        $woocommerce_settings['mailoptin_woocommerce_field_mapping_section'] = [
                            'type' => 'arbitrary',
                            'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Map Fields', 'mailoptin')),
                        ];
                    }

                    foreach ($mappable_fields as $key => $value) {
                        $mapped_key                        = rawurlencode('mailoptin_woocommerce_mapped_fields_' . $key);
                        $woocommerce_settings[$mapped_key] = [
                            'type'    => 'select',
                            'label'   => $value,
                            'options' => WooInit::get_instance()->woo_checkout_fields()
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
                            $woocommerce_settings['mailoptin_woocommerce_select_tags'] = [
                                'type'        => 'select2',
                                'label'       => __('Tags', 'mailoptin'),
                                'description' => __('Select tags to assign to buyers or customers.', 'mailoptin'),
                                'options'     => $options
                            ];
                        }
                    } elseif (in_array($saved_connections, Init::text_tag_connections())) {
                        $woocommerce_settings['mailoptin_woocommerce_text_tags'] = [
                            'type'        => 'text',
                            'label'       => __('Tags', 'mailoptin'),
                            'description' => __('Enter a comma-separated list of tags to assign to buyers or customers.', 'mailoptin')
                        ];
                    }
                }
            }

            $woocommerce_settings['mailoptin_woocommerce_other_setting_section'] = [
                'type' => 'arbitrary',
                'data' => sprintf('<h2 class="mo-line-header"><span>%s</span></h2>', esc_html__('Other Settings', 'mailoptin')),
            ];

            $woocommerce_settings['mailoptin_woocommerce_subscribe_customers'] = [
                'type'        => 'select',
                'label'       => __('Subscribe Customers', 'mailoptin'),
                'description' => __('Choose "Ask for permission" to show an opt-in checkbox during checkout. Customers will only be subscribed to the email marketing list above if they check the checkbox. Choose Automatically to subscribe customers silently upon checkout. Caution, this is without the customer\'s consent.', 'mailoptin'),
                'options'     => [
                    'no'  => __('Automatically', 'mailoptin'),
                    'yes' => __('Ask for permission', 'mailoptin')
                ]
            ];


            $woocommerce_settings['mailoptin_woocommerce_field_label'] = [
                'type'        => 'textarea',
                'label'       => __('Opt-In Field Label', 'mailoptin'),
                'description' => __('Customize the label displayed next to the opt-in checkbox.', 'mailoptin'),
                'value'       => __('Subscribe to our newsletter', 'mailoptin'),
            ];

            $woocommerce_settings['mailoptin_woocommerce_checkbox_default'] = [
                'type'        => 'select',
                'label'       => __('Opt-In Checkbox Default', 'mailoptin'),
                'description' => __('The default state of the opt-in checkbox.', 'mailoptin'),
                'options'     => [
                    'unchecked' => __('Unchecked', 'mailoptin'),
                    'checked'   => __('Checked', 'mailoptin'),
                ]
            ];

            $woocommerce_settings['mailoptin_woocommerce_checkbox_location'] = [
                'type'        => 'select',
                'label'       => __('Opt-In Checkbox Location', 'mailoptin'),
                'description' => __('Where to display the opt-in checkbox on the checkout page.', 'mailoptin'),
                'options'     => [
                    'woocommerce_review_order_before_submit'           => __('Order review above submit', 'mailoptin'),
                    'woocommerce_review_order_after_submit'            => __('Order review below submit', 'mailoptin'),
                    'woocommerce_checkout_before_customer_details'     => __('Above customer details', 'mailoptin'),
                    'woocommerce_checkout_after_customer_details'      => __('Below customer details', 'mailoptin'),
                    'woocommerce_checkout_before_order_review'         => __('Order review above cart/product table.', 'mailoptin'),
                    'woocommerce_checkout_billing'                     => __('Above billing details', 'mailoptin'),
                    'woocommerce_checkout_shipping'                    => __('Above shipping details', 'mailoptin'),
                    'woocommerce_after_checkout_billing_form'          => __('Below Checkout billing form', 'mailoptin'),
                    'woocommerce_checkout_before_terms_and_conditions' => __('Above Checkout Terms and Conditions', 'mailoptin'),
                    'woocommerce_checkout_after_terms_and_conditions'  => __('Below Checkout Terms and Conditions', 'mailoptin'),
                ]
            ];

        endif;

        $settings['woocommerce_settings'] = apply_filters('mailoptin_settings_woocommerce_settings_page', [
            'tab_title' => __('WooCommerce Integration', 'mailoptin'),
            $woocommerce_settings
        ]);

        return $settings;
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