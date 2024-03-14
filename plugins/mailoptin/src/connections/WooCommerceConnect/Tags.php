<?php

namespace MailOptin\WooCommerceConnect;

use MailOptin\Connections\Init;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Core\OptinForms\ConversionDataBuilder;

class Tags
{
    public function __construct()
    {
        add_action('product_tag_edit_form_fields', [$this, 'edit_tag_fields'], 10, 1);
        add_action('edit_term', [$this, 'save_product_tag_fields'], 10, 3);
        add_filter('mo_mailoptin_js_globals', [$this, 'set_woo_global_variables'], 10, 1);
    }

    /**
     * @param $localize_strings
     *
     * @return array
     */
    public function set_woo_global_variables($localize_strings)
    {
        global $tag;
        if ( ! empty($tag->term_id)) {
            $localize_strings['woo_product_cat_id'] = $tag->term_id;
        }

        return $localize_strings;
    }

    public function edit_tag_fields()
    {
        global $tag;

        $product_tag_id    = $tag->term_id;
        $integrations      = WooInit::get_instance()->woo_select_integration_options();
        $saved_integration = get_term_meta($product_tag_id, 'mailoptinWooCommerceSelectIntegration', true);

        $connect_text = esc_html__('Connect MailOptin', 'mailoptin');

        if ( ! empty($saved_integration)) {
            $connect_text = esc_html__('Connected', 'mailoptin');
        }

        $upsell_url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection';
        $doc_url    = 'https://mailoptin.io/article/add-woocommerce-customers-email-list-by-purchased-product/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=woocommerce_connection';

        $content = sprintf(
            __("Upgrade to %sMailOptin Premium%s to add customers that purchase products with this tag to a specific email list, add custom field data and assign tags to them.", 'mailoptin'),
            '<a target="_blank" href="' . $upsell_url . '">',
            '</a>'
        );

        ?>
        <tr class="form-field term-mailoptin-wrap">
        <th scope="row">
            <label><?php esc_html_e('Connect MailOptin', 'mailoptin'); ?></label>
        </th>
        <td>
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
        <input id="mo-woocommerce-product-category" type="button" value="<?php echo $connect_text ?>" class="button button-secondary"/>
        <p class="description"><?php esc_html_e('Add buyers or customers that purchase products with this tag to your email marketing or CRM list.', 'mailoptin'); ?></p>
        </td>
        </tr>
        <div style="display: none">
            <div id="mo-woocommerce-product-category-modal">
                <div class="mo-modal">
                    <div class="mo-header">
                        <h2><?php _e('Connect MailOptin', 'mailoptin'); ?></h2>
                    </div>
                    <div class="mo-content">
                        <?php if ( ! empty($integrations)) { ?>
                            <p>
                                <label for="mailoptinWooCommerceSelectIntegration"><?php _e('Select Integration', 'mailoptin'); ?></label>
                                <select class="select short" name="mailoptinWooCommerceSelectIntegration" id="mailoptinWooCommerceSelectIntegration" data-type="product_tagegory">
                                    <?php
                                    foreach ($integrations as $key => $value) {
                                        ?>
                                        <option value="<?php echo $key ?>" <?php selected($key, $saved_integration) ?>><?php echo $value ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="description"><?php esc_html_e('Select your email marketing software or CRM.', 'mailoptin') ?></span>
                            </p>
                        <?php } ?>
                        <div class="mailoptin_woocommerce_email_list"></div>
                        <div class="mailoptin_woocommerce_custom_fields_tags"></div>
                        <div class="mailoptin_close_button">
                            <input type="button" name="mailoptinWooCommerceCloseBox" class="button button-primary" value="<?php esc_html_e('Save Settings', 'mailoptin'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;
    }

    public function save_product_tag_fields($term_id, $tt_id, $taxonomy)
    {
        if ('product_tag' === $taxonomy) {
            if (isset($_POST['mailoptinWooCommerceSelectIntegration'])) {
                update_term_meta($term_id, 'mailoptinWooCommerceSelectIntegration', sanitize_text_field($_POST['mailoptinWooCommerceSelectIntegration']));
            }

            if (isset($_POST['mailoptinWooCommerceSelectList']) && isset($_POST['mailoptinWooCommerceSelectIntegration'])) {
                $connection            = sanitize_text_field($_POST['mailoptinWooCommerceSelectIntegration']);
                $connection_email_list = sanitize_text_field($_POST['mailoptinWooCommerceSelectList']);

                update_term_meta($term_id, $connection . '[mailoptinWooCommerceSelectList]', $connection_email_list);

                foreach (Init::merge_vars_field_map($connection, $connection_email_list) as $key => $value) {
                    $mapped_key = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
                    if ( isset($_POST[$mapped_key])) {
                        update_term_meta($term_id, $connection . '[' . $mapped_key . ']', sanitize_text_field($_POST[$mapped_key]));
                    }
                }

                if (isset($_POST['mailoptinWooCommerceDoubleOptin'])) {
                    update_term_meta($term_id, $connection . '[mailoptinWooCommerceDoubleOptin]', true);
                } else {
                    update_term_meta($term_id, $connection . '[mailoptinWooCommerceDoubleOptin]', false);
                }

                if (isset($_POST['mailoptinWooCommerceTextTags'])) {
                    $text_tags = sanitize_text_field($_POST['mailoptinWooCommerceTextTags']);
                    update_term_meta($term_id, $connection . '[mailoptinWooCommerceTextTags]', $text_tags);
                }

                if (isset($_POST['mailoptinWooCommerceSelectTags'])) {
                    $select_tags = json_encode($_POST['mailoptinWooCommerceSelectTags']);
                    update_term_meta($term_id, $connection . '[mailoptinWooCommerceSelectTags]', $select_tags);
                }
            }
        }
    }

    /**
     * @param $order
     *
     * @return mixed|string
     */
    public function get_first_name($order)
    {
        $current_user          = wp_get_current_user();
        $billing_first_name_fn = 'get_billing_first_name';

        $return_data = '';
        if (is_callable([$order, $billing_first_name_fn])) {
            $return_data = $order->$billing_first_name_fn();
        }

        if (empty($return_data)) {
            $return_data = $current_user->user_firstname;
        }

        return $return_data;
    }

    /**
     * @param $order
     *
     * @return string
     */
    public function get_last_name($order)
    {
        $current_user         = wp_get_current_user();
        $billing_last_name_fn = 'get_billing_last_name';

        $return_data = '';
        if (is_callable([$order, $billing_last_name_fn])) {
            $return_data = $order->$billing_last_name_fn();
        }

        if (empty($return_data)) {
            $return_data = $current_user->user_lastname;
        }

        return $return_data;
    }

    /**
     * @param $order
     *
     * @return mixed|string
     */
    public function get_email_address($order)
    {
        $current_user     = wp_get_current_user();
        $billing_email_fn = 'get_billing_email';

        $return_data = '';
        if (is_callable([$order, $billing_email_fn])) {
            $return_data = $order->$billing_email_fn();
        }

        if (empty($return_data)) {
            $return_data = $current_user->user_email;
        }

        return $return_data;
    }


    public function process_submission($product_tag_id, $order)
    {
        $field_map = [];

        $connection_service = get_term_meta($product_tag_id, 'mailoptinWooCommerceSelectIntegration', true);

        if (empty($connection_service)) return;

        $connection_email_list = get_term_meta($product_tag_id, $connection_service . '[mailoptinWooCommerceSelectList]', true);

        foreach (Init::merge_vars_field_map($connection_service, $connection_email_list) as $key => $value) {
            $mapped_key = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
            if ( ! empty(get_term_meta($product_tag_id, $connection_service . '[' . $mapped_key . ']', true))) {
                $field_map[$key] = get_term_meta($product_tag_id, $connection_service . '[' . $mapped_key . ']', true);
            }
        }

        //get the email mapped
        $email = $this->get_email_address($order);

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
            $double_optin = get_term_meta($product_tag_id, $connection_service . '[mailoptinWooCommerceDoubleOptin]', true) === "1";
        }


        $form_tags = '';
        if (in_array($connection_service, Init::text_tag_connections())) {
            $tags_key  = $connection_service . '[mailoptinWooCommerceTextTags]';
            $form_tags = get_term_meta($product_tag_id, $tags_key, true);
        } elseif (in_array($connection_service, Init::select2_tag_connections())) {
            $tags_key  = $connection_service . '[mailoptinWooCommerceSelectTags]';
            $form_tags = json_decode(get_term_meta($product_tag_id, $tags_key, true), true);
        }

        $optin_data = new ConversionDataBuilder();

        $first_name = $this->get_first_name($order);
        $last_name  = $this->get_last_name($order);
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