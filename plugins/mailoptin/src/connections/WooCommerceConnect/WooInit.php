<?php

namespace MailOptin\WooCommerceConnect;


use MailOptin\Core\Connections\ConnectionFactory;
use MailOptin\Connections\Init;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\ConnectionsRepository;

if (strpos(__FILE__, 'mailoptin' . DIRECTORY_SEPARATOR . 'src') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL', MAILOPTIN_URL . 'src/connections/WooCommerceConnect/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

class WooInit
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_footer', [$this, 'admin_scripts']);

        add_action('wp_ajax_mo_woocommerce_fetch_lists', [$this, 'fetch_lists']);
        add_action('wp_ajax_mo_woocommerce_fetch_custom_fields', [$this, 'fetch_custom_fields']);

        add_action('woocommerce_order_status_changed', [$this, 'process_optin'], 20, 3);

        Product::get_instance();
        Category::get_instance();
        Tags::get_instance();
        WooSettings::get_instance();
    }

    public function enqueue_scripts()
    {
        global $post;
        global $tag;

        if ( ! defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

        $page = '';
        if ( ! empty($post->post_type)) {
            $page = $post->post_type;
        } elseif ( ! empty($tag->taxonomy)) {
            $page = $tag->taxonomy;
        }

        if (in_array($page, ['product_tag', 'product_cat', 'product'])) {
            wp_enqueue_script('mailoptin-woocommerce', MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL . 'woocommerce.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);

            wp_localize_script('mailoptin-woocommerce', 'moWooCommerce', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-woocommerce'),
                'select2_tag_connections' => Init::select2_tag_connections(),
                'text_tag_connections'    => Init::text_tag_connections()
            ]);
        }

        $screen = get_current_screen();
        if (strpos($screen->id, MAILOPTIN_SETTINGS_SETTINGS_SLUG) !== false) {
            wp_enqueue_script('mailoptin-woocommerce-settings', MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL . 'settings.js', ['jquery', 'underscore'], MAILOPTIN_VERSION_NUMBER, true);
            wp_localize_script('mailoptin-woocommerce-settings', 'moWooCommerce', [
                'fields'                  => [],
                'ajax_url'                => admin_url('admin-ajax.php'),
                'nonce'                   => wp_create_nonce('mailoptin-woocommerce'),
                'select2_tag_connections' => Init::select2_tag_connections(),
                'text_tag_connections'    => Init::text_tag_connections()
            ]);
        }
    }

    /**
     *
     */
    public function admin_scripts()
    {
        global $post;
        global $tag;

        $page_slug = '';
        if ( ! empty($post->post_type)) {
            $page_slug = $post->post_type;
        } elseif ( ! empty($tag->taxonomy)) {
            $page_slug = $tag->taxonomy;
        }

        if (in_array($page_slug, ['product_tag', 'product_cat', 'product'])) {
            ob_start();
            ?>
            <style>
                #woocommerce-product-data ul.wc-tabs li.mailoptin_options a:before {
                    content: url("<?php echo MAILOPTIN_WOOCOMMERCE_CONNECT_ASSETS_URL.'mailoptin-icon.svg' ?>");
                }

                #poststuff h2.mo-woocommerce-map-field-title {
                    padding: 0 20px 0 0 !important;
                    margin-left: 10px !important;
                    margin-right: 162px !important;
                }

                #mailoptin_email_integration .options_group p.form-field {
                    display: grid;
                    grid-template-columns: 150px auto;
                    grid-gap: 5px;
                    padding-left: 0 !important;
                    margin-left: 12px;
                }

                #mailoptin_email_integration .options_group p.form-field label {
                    margin-left: 0;
                }

                #mailoptin_email_integration .options_group p.form-field .description {
                    grid-column: 2;
                }

                h2.mo-woocommerce-map-field-title {
                    width: 100%;
                    text-align: left;
                    border-bottom: 1px solid #c3c4c7;
                    line-height: 0.1em !important;
                    margin: 10px 0 20px !important;
                    font-weight: bold !important;
                }

                h2.mo-woocommerce-map-field-title span {
                    background: #fff;
                    padding-right: 10px;
                }

                .mailoptin_woocommerce_custom_fields_tags,
                .mailoptin_woocommerce_email_list {
                    margin-top: 20px;
                }

                #mo-woocommerce-product-category-modal {
                    display: inline-block;
                    width: 600px;
                    max-width: 80vw;
                    margin: 30px;
                    padding: 30px;
                    height: 80vh;
                }

                #mo-woocommerce-product-category-modal .mo-modal .mo-content p {
                    display: grid;
                    grid-gap: 10px;
                    grid-template-columns: 150px auto;
                }

                #mo-woocommerce-product-category-modal .mo-modal .mo-content p label {
                    display: flex;
                    align-items: center;
                }

                #mo-woocommerce-product-category-modal .mo-modal .mo-content p .description {
                    grid-column: 2;
                }

                #mo-woocommerce-product-category-modal .mo-modal .mo-content .mailoptin_close_button {
                    text-align: center;
                    margin-top: 15px;
                    width: 100%;
                }

                #poststuff .mailoptin_woocommerce_custom_fields_tags .select2-container {
                    width: 300px !important;
                    max-width: 95%;
                }

                #poststuff .mailoptin_woocommerce_custom_fields_tags .select2-container ~ .description {
                    display: inline-block;
                    margin-left: 0;
                }

                .mo-woo-upsell-block {
                    background-color: #d9edf7;
                    border: 1px solid #bce8f1;
                    box-sizing: border-box;
                    color: #31708f;
                    outline: 0;
                    margin: 10px;
                    padding: 10px;
                }

                .mo-woo-upsell-block p {
                    margin: 0 0 5px 0;
                    font-size: 14px;
                }
            </style>
            <?php echo mo_minify_css(ob_get_clean()); ?>
            <script type="text/javascript">
                (function ($) {
                    $('#mo-woocommerce-product-category').on('click', function (e) {
                        e.preventDefault();
                        $.fancybox.open({
                            src: '#mo-woocommerce-product-category-modal',
                            type: 'inline'
                        });
                    });
                })(jQuery);
            </script>';
            <?php
        }
    }

    public function fetch_lists()
    {
        check_ajax_referer('mailoptin-woocommerce', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection'])) wp_send_json_error([]);

        $connection = sanitize_text_field($_POST['connection']);

        $type = 'product';
        if ( ! empty($_POST['type'])) $type = sanitize_text_field($_POST['type']);

        ob_start();

        if ($type === 'product') {
            if (empty($_POST['product_id'])) wp_send_json_error([]);

            $product_object = wc_get_product($_POST['product_id']);

            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                $saved_double_optin    = $product_object->get_meta($connection . '[mailoptinWooCommerceDoubleOptin]');
                $double_optin_settings = $this->woo_double_optin_settings($saved_double_optin, $connection);
            }

            $lists       = [];
            $saved_lists = '';
            if ( ! empty($connection) && $connection != 'leadbank') {
                $lists       = Init::mo_select_list_options($connection);
                $saved_lists = $product_object->get_meta($connection . '[mailoptinWooCommerceSelectList]');
            }

            if (empty($lists)) wp_send_json_error([]);

            woocommerce_wp_select(
                [
                    'id'          => 'mailoptinWooCommerceSelectList',
                    'label'       => esc_html__('Select List', 'mailoptin'),
                    'value'       => $saved_lists,
                    'options'     => $lists,
                    'description' => __('Select the email list, audience or segment to add customers to.', 'mailoptin'),
                ]
            );

            if ( ! empty($double_optin_settings)) {
                woocommerce_wp_checkbox($double_optin_settings);
            }
        } else {

            if (empty($_POST['product_cat_id'])) wp_send_json_error([]);
            $product_cat_id = absint($_POST['product_cat_id']);

            $lists       = [];
            $saved_lists = '';
            if ( ! empty($connection) && $connection != 'leadbank') {
                $lists       = Init::mo_select_list_options($connection);
                $saved_lists = get_term_meta($product_cat_id, $connection . '[mailoptinWooCommerceSelectList]', true);
            }

            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                $saved_double_optin    = get_term_meta($product_cat_id, $connection . '[mailoptinWooCommerceDoubleOptin]', true);
                $double_optin_settings = $this->woo_double_optin_settings($saved_double_optin, $connection);
            }
            ?>
            <p>
            <label for="mailoptinWooCommerceSelectList"><?php _e('Select List', 'mailoptin'); ?></label>
            <select class="select short" name="mailoptinWooCommerceSelectList" id="mailoptinWooCommerceSelectList">
                <?php
                foreach ($lists as $key => $value) {
                    ?>
                    <option value="<?php echo $key ?>" <?php selected($key, $saved_lists) ?>><?php echo $value ?></option>
                    <?php
                }
                ?>
            </select>
            <span class="description"><?php esc_html_e('Select the email list, audience or segment to add customers to.', 'mailoptin') ?>
          </span>
            <?php
            if ( ! empty($double_optin_settings)) {
                ?>
                <p>
                    <label for="<?php echo $double_optin_settings['id'] ?>"><?php echo $double_optin_settings['label'] ?></label>
                    <input type="checkbox" class="checkbox" name="<?php echo $double_optin_settings['id'] ?>" id="<?php echo $double_optin_settings['id'] ?>" <?php if ($double_optin_settings['value'] === 'yes') echo 'checked'; ?> />
                    <span class="description"><?php echo $double_optin_settings['description'] ?></span>
                </p>
                <?php
            }

        }

        $response = [
            'lists' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    public function fetch_custom_fields()
    {
        check_ajax_referer('mailoptin-woocommerce', 'nonce');

        \MailOptin\Core\current_user_has_privilege() || exit;

        if (empty($_POST['connection']) || empty($_POST['connection_email_list'])) wp_send_json_error([]);

        $connection            = sanitize_text_field($_POST['connection']);
        $connection_email_list = sanitize_text_field($_POST['connection_email_list']);

        $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

        if (empty($mappable_fields)) wp_send_json_error([]);

        $type = 'product';
        if ( ! empty($_POST['type'])) $type = sanitize_text_field($_POST['type']);

        ob_start();

        if ($type === 'product') {
            if (empty($_POST['product_id'])) wp_send_json_error([]);
            $product_object = wc_get_product($_POST['product_id']);
            ?>
            <h2 class="mo-woocommerce-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
            <?php
            foreach ($mappable_fields as $key => $value) {
                $mapped_key         = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
                $saved_mapped_field = $product_object->get_meta($connection . '[' . $mapped_key . ']');

                // quickly mapped billing email to the moEmail field
                if (empty($product_object->get_meta($connection . '[mailoptinWooCommerceMappedFields-' . $key . ']')) && $key === 'moEmail') {
                    $saved_mapped_field = 'billing_email';
                }

                woocommerce_wp_select(
                    [
                        'id'                => $mapped_key,
                        'label'             => $value,
                        'value'             => $saved_mapped_field,
                        'options'           => $this->woo_checkout_fields(),
                        'custom_attributes' => [
                            'data-type' => 'product'
                        ]
                    ]
                );
            }

            $saved_tags = '';
            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                if (in_array($connection, Init::text_tag_connections())) {
                    $tags_key   = $connection . '[mailoptinWooCommerceTextTags]';
                    $saved_tags = $product_object->get_meta($tags_key);
                } elseif (in_array($connection, Init::select2_tag_connections())) {
                    $tags_key   = $connection . '[mailoptinWooCommerceSelectTags]';
                    $saved_tags = json_decode($product_object->get_meta($tags_key), true);
                }
                $this->woo_lead_tag_settings($saved_tags, $connection);
            }
        } else {
            if (empty($_POST['product_cat_id'])) wp_send_json_error([]);

            $mappable_fields = Init::merge_vars_field_map($connection, $connection_email_list);

            if (empty($mappable_fields)) wp_send_json_error([]);

            $product_cat_id = (int)$_POST['product_cat_id'];
            ?>
            <h2 class="mo-woocommerce-map-field-title"><span><?= __('Map Fields', 'mailoptin') ?></span></h2>
            <?php

            foreach ($mappable_fields as $key => $value) {
                $mapped_key         = rawurlencode('mailoptinWooCommerceMappedFields-' . $key);
                $saved_mapped_field = get_term_meta($product_cat_id, $connection . '[' . $mapped_key . ']', true);
                ?>
                <p>
                    <label for="<?php echo $mapped_key; ?>"><?php echo $value; ?></label>
                    <select class="select short" name="<?php echo $mapped_key; ?>" id="<?php echo $mapped_key; ?>">
                        <?php
                        foreach ($this->woo_checkout_fields() as $checkout_key => $checkout_value) {
                            ?>
                            <option value="<?php echo $checkout_key ?>" <?php selected($checkout_key, $saved_mapped_field) ?>><?php echo $checkout_value ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <?php
            }

            $saved_tags = '';
            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {
                if (in_array($connection, Init::text_tag_connections())) {
                    $tags_key   = $connection . '[mailoptinWooCommerceTextTags]';
                    $saved_tags = get_term_meta($product_cat_id, $tags_key, true);
                } elseif (in_array($connection, Init::select2_tag_connections())) {
                    $tags_key   = $connection . '[mailoptinWooCommerceSelectTags]';
                    $saved_tags = json_decode(get_term_meta($product_cat_id, $tags_key, true));
                }
                $this->woo_lead_tag_settings($saved_tags, $connection);
            }
        }

        $response = [
            'fields' => ob_get_clean()
        ];

        wp_send_json_success($response);
    }

    /**
     * @return array|false
     */
    public function woo_double_optin_settings($saved_double_optin, $saved_integration)
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
                'id'          => 'mailoptinWooCommerceDoubleOptin',
                'label'       => ($is_double_optin === false) ? esc_html__('Enable Double Optin', 'mailoptin') : esc_html__('Disable Double Optin', 'mailoptin'),
                'description' => esc_html__('Double optin requires customers to confirm their email address before they are added or subscribed.', 'mailoptin'),
                'value'       => wc_bool_to_string($saved_double_optin),
            ];
        }

        return [];
    }

    /**
     * @return array
     */
    public function woo_checkout_fields()
    {
        $fields          = [];
        $checkout_fields = WC()->checkout()->get_checkout_fields();

        if ( ! empty($checkout_fields)) {
            $fields[''] = esc_html__('Select one', 'mailoptin');
            foreach ($checkout_fields as $key => $checkout_field) {
                foreach ($checkout_field as $checkout_key => $value) {
                    $append_str            = ' (' . ucwords($key) . ')';
                    $fields[$checkout_key] = $value['label'] . $append_str;
                }
            }
        }

        $fields['mowoo_product_names']        = esc_html__('Last Order Product Names', 'mailoptin');
        $fields['mowoo_order_total']          = esc_html__('Last Order Total', 'mailoptin');
        $fields['mowoo_order_date']           = esc_html__('Last Order Date', 'mailoptin');
        $fields['mowoo_order_payment_method'] = esc_html__('Last Order Payment Method', 'mailoptin');
        $fields['mowoo_order_notes']          = esc_html__('Last Order Notes', 'mailoptin');

        return apply_filters('mailoptin_woocommerce_mapping_checkout_fields', $fields, $this);
    }

    /**
     * @return false|void
     */
    public function woo_lead_tag_settings($saved_tags, $saved_integration)
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

            woocommerce_wp_select(
                [
                    'id'                => 'mailoptinWooCommerceSelectTags',
                    'name'              => 'mailoptinWooCommerceSelectTags[]',
                    'label'             => esc_html__('Tags', 'mailoptin'),
                    'value'             => $saved_tags,
                    'options'           => $options,
                    'class'             => 'mowoo_select2',
                    'description'       => esc_html__('Select tags to assign to buyers or customers.', 'mailoptin'),
                    'custom_attributes' => [
                        'multiple' => 'multiple'
                    ]
                ]
            );
            ?>
            <script type="text/javascript">
                var run = function () {
                    var cache = jQuery('.mowoo_select2');
                    if (typeof cache.select2 !== 'undefined') {
                        cache.select2()
                    }
                };
                jQuery(window).on('load', run);
                run();
            </script>
            <?php
        } elseif (in_array($saved_integration, Init::text_tag_connections())) {

            woocommerce_wp_text_input(
                [
                    'id'          => 'mailoptinWooCommerceTextTags',
                    'value'       => $saved_tags,
                    'label'       => esc_html__('Tags', 'mailoptin'),
                    'description' => esc_html__('Enter a comma-separated list of tags to assign to buyers or customers.', 'mailoptin'),
                ]
            );
        }
    }

    /**
     * @return array
     */
    public function woo_select_integration_options()
    {
        $integrations = self::email_service_providers();

        if ( ! empty($integrations)) {

            $options[''] = esc_html__('Select...', 'mailoptin');

            foreach ($integrations as $value => $label) {

                if (empty($value)) continue;

                // Add list to select options.
                $options[$value] = $label;
            }

            return $options;
        }

        return [];
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

        //escape webhook connection
        unset($connections['WebHookConnect']);
        unset($connections['WordPressUserRegistrationConnect']);

        return $connections;
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
     * @return mixed|string
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
     * @param \WC_Order $order
     *
     * @return array
     */
    private function get_product_names_from_order($order)
    {
        $product_names = [];

        // Get items in the order
        $items = $order->get_items();

        // Loop through each item in the order
        foreach ($items as $item_id => $item) {
            /** @var \WC_Product $product */
            $product         = $item->get_product();
            $product_names[] = $product->get_name();
        }

        return implode(',', $product_names);
    }

    /**
     * @param \WC_Order $order
     *
     *
     * @return string
     */
    private function get_order_paid_date($order)
    {
        $date = $order->get_date_paid();

        if (is_object($date)) {
            return $date->date('Y-m-d');
        }

        return '';
    }

    /**
     * @param $field_id
     * @param \WC_Order $order
     *
     * @return mixed
     */
    public function get_field_value($field_id, $order)
    {
        $user = $order->get_user();

        $hashmap = apply_filters('mailoptin_woocommerce_mapping_field_hashmap_value', [
            'order_comments'             => $order->get_customer_note(),
            'account_username'           => $user ? $user->user_login : '',
            'account_password'           => '',
            'mowoo_product_names'        => $this->get_product_names_from_order($order),
            'mowoo_order_total'          => $order->get_total(),
            'mowoo_order_date'           => $this->get_order_paid_date($order),
            'mowoo_order_payment_method' => $order->get_payment_method_title()
        ], $field_id, $order);

        if (isset($hashmap[$field_id])) return $hashmap[$field_id];

        $field_method = 'get_' . $field_id;

        if (is_callable([$order, $field_method])) return $order->$field_method();

        $val = $order->get_meta($field_id);

        if ($val) return $val;

        if ($user) return get_user_meta($user->ID, $field_id, true);

        return '';
    }

    /**
     * @param $order_id
     * @param $old_status
     * @param $new_status
     */
    public function process_optin($order_id, $old_status, $new_status)
    {
        $order = wc_get_order($order_id);

        $subscription_type = Settings::instance()->mailoptin_woocommerce_subscribe_customers();

        if ($subscription_type == 'yes') {

            if (self::is_use_post_meta_storage()) {
                $subscribe_customer = get_post_meta($order_id, 'mailoptin_woocommerce_optin_checkbox', true);
            } else {
                $subscribe_customer = $order->get_meta('mailoptin_woocommerce_optin_checkbox', true);
            }

            //don't add customer if the customer did not tick the checkbox
            if ('no' === $subscribe_customer) return;
        }

        $product_items = $order->get_items();

        foreach ($product_items as $product_item) {
            $product_id = $product_item->get_product_id();

            $order_statuses = apply_filters('mailoptin_woocommerce_valid_product_order_status', ['processing', 'completed']);

            $product_object  = wc_get_product($product_id);
            $product_tag_ids = wc_get_product_term_ids($product_id, 'product_tag');
            $product_cat_ids = wc_get_product_term_ids($product_id, 'product_cat');

            if (in_array($new_status, $order_statuses) && ! in_array($old_status, $order_statuses)) {

                //check if the product has an existing connected integration
                if (is_object($product_object) && method_exists($product_object, 'get_meta') && ! empty($product_object->get_meta('mailoptinWooCommerceSelectIntegration'))) {
                    Product::get_instance()->process_submission($product_object, $order);
                }

                // loop through each category and check if any on these categories has an existing connected integration
                foreach ($product_cat_ids as $product_cat_id) {
                    if ( ! empty(get_term_meta($product_cat_id, 'mailoptinWooCommerceSelectIntegration', true))) {
                        Category::get_instance()->process_submission($product_cat_id, $order);
                    }
                }

                // loop through each tag and check if any on these tags has an existing connected integration
                foreach ($product_tag_ids as $product_tag_id) {
                    if ( ! empty(get_term_meta($product_tag_id, 'mailoptinWooCommerceSelectIntegration', true))) {
                        Tags::get_instance()->process_submission($product_tag_id, $order);
                    }
                }

                // check if mailoptin is connected in the settings
                if ( ! empty(Settings::instance()->mailoptin_woocommerce_integration_connections())) {
                    WooSettings::get_instance()->process_submission($order);
                }
            }
        }
    }

    public static function is_use_post_meta_storage()
    {
        return apply_filters('mailoptin_woocommerce_use_post_meta_storage', false);
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