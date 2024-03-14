<?php

namespace MailOptin\WooCommerceConnect;

use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Connections\Init;

class Product
{
    public function __construct()
    {
        add_filter('mo_mailoptin_js_globals', [$this, 'set_woo_global_variables'], 10, 1);
        add_filter('woocommerce_product_data_tabs', [$this, 'mailoptin_product_tabs'], 10, 1);
        add_action('woocommerce_product_data_panels', [$this, 'mailoptin_email_integration_panels']);
        add_action('woocommerce_admin_process_product_object', [$this, 'save_mailoptin_integration'], 10, 1);
    }

    /**
     * @param $tabs
     *
     * @return mixed
     */
    public function mailoptin_product_tabs($tabs)
    {
        $tabs['mailoptin'] = [
            'label'    => __('MailOptin', 'mailoptin'),
            'target'   => 'mailoptin_email_integration',
            'class'    => array(),
            'priority' => 75,
        ];

        return $tabs;
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_woo_global_variables($localize_strings)
    {
        global $post;
        if ( ! empty($post->ID)) {
            $localize_strings['woo_product_id'] = $post->ID;
        }

        return $localize_strings;
    }

    /**
     *
     */
    public function mailoptin_email_integration_panels()
    {
        global $product_object;
        $integrations      = WooInit::get_instance()->woo_select_integration_options();
        $saved_integration = $product_object->get_meta('mailoptinWooCommerceSelectIntegration');
        $upsell_url        = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection';
        $doc_url           = 'https://mailoptin.io/article/add-woocommerce-customers-email-list-by-purchased-product/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection';

        $content = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add customers that purchase this product to a specific email list, assign tags and custom field data to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );

        ?>
        <div id="mailoptin_email_integration" class="panel woocommerce_options_panel hidden">
            <?php if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) : ?>
                <div class="mo-woo-upsell-block">
                    <p><?= $content ?></p>
                    <p>
                        <a href="<?= $upsell_url ?>" style="margin-right: 10px;" class="button-primary" target="_blank">
                            <?php esc_html_e('Upgrade to MailOptin Premium', 'mailoptin'); ?>
                        </a>
                        <a href="<?= $doc_url ?>" target="_blank">
                            <?php esc_html_e('Learn more', 'mailoptin'); ?>
                        </a>
                    </p>
                </div>
            <?php else : ?>
                <div class="options_group">
                    <?php if ( ! empty($integrations)) {
                        woocommerce_wp_select(
                            [
                                'id'                => 'mailoptinWooCommerceSelectIntegration',
                                'label'             => esc_html__('Select Integration', 'mailoptin'),
                                'value'             => $saved_integration,
                                'options'           => $integrations,
                                'description'       => __('Select your email marketing software or CRM.', 'mailoptin'),
                                'custom_attributes' => [
                                    'data-type' => 'product'
                                ]
                            ]
                        );

                        ?>
                        <div class="mailoptin_woocommerce_email_list"></div>
                        <div class="mailoptin_woocommerce_custom_fields_tags"></div>
                        <?php
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * @param $product_object
     * @param $order
     */
    public function process_submission($product_object, $order)
    {
        $field_map = [];

        $connection_service = $product_object->get_meta('mailoptinWooCommerceSelectIntegration');

        if (empty($connection_service)) return;

        $connection_email_list = $product_object->get_meta($connection_service . '[mailoptinWooCommerceSelectList]');

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
            if ( ! empty($product_object->get_meta($connection_service . '[' . $mapped_key . ']'))) {
                $field_map[$key] = $product_object->get_meta($connection_service . '[' . $mapped_key . ']');
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
            $double_optin = $product_object->get_meta($connection_service . '[mailoptinWooCommerceDoubleOptin]') === "1";
        }

        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $tags_key  = $connection_service . '[mailoptinWooCommerceTextTags]';
            $form_tags = $product_object->get_meta($tags_key);
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $tags_key  = $connection_service . '[mailoptinWooCommerceSelectTags]';
            $form_tags = json_decode($product_object->get_meta($tags_key), true);
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

        if (apply_filters('mailoptin_woocommerce_enable_optin_delay', false)) {
            sleep(1);
        }
    }

    /**
     * @param $product
     */
    public function save_mailoptin_integration($product)
    {
        if (isset($_POST['mailoptinWooCommerceSelectIntegration'])) {
            $product->update_meta_data('mailoptinWooCommerceSelectIntegration', sanitize_text_field($_POST['mailoptinWooCommerceSelectIntegration']));
        }

        if ( ! empty($_POST['mailoptinWooCommerceSelectList']) && ! empty($_POST['mailoptinWooCommerceSelectIntegration'])) {
            $connection            = sanitize_text_field($_POST['mailoptinWooCommerceSelectIntegration']);
            $connection_email_list = sanitize_text_field($_POST['mailoptinWooCommerceSelectList']);

            $product->update_meta_data($connection . '[mailoptinWooCommerceSelectList]', $connection_email_list);

            foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                $mapped_key = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
                if (isset($_POST[$mapped_key])) {
                    $insert_mapped_key = $connection . '[' . $mapped_key . ']';
                    $product->update_meta_data($insert_mapped_key, sanitize_text_field($_POST[$mapped_key]));
                }
            }

            if ( ! empty($_POST['mailoptinWooCommerceDoubleOptin'])) {
                $product->update_meta_data($connection . '[mailoptinWooCommerceDoubleOptin]', true);
            } else {
                $product->update_meta_data($connection . '[mailoptinWooCommerceDoubleOptin]', false);
            }

            if ( ! empty($_POST['mailoptinWooCommerceTextTags'])) {
                $text_tags = sanitize_text_field($_POST['mailoptinWooCommerceTextTags']);
                $product->update_meta_data($connection . '[mailoptinWooCommerceTextTags]', $text_tags);
            }

            if ( ! empty($_POST['mailoptinWooCommerceSelectTags'])) {
                $select_tags = json_encode($_POST['mailoptinWooCommerceSelectTags']);
                $product->update_meta_data($connection . '[mailoptinWooCommerceSelectTags]', $select_tags);
            }
        }
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