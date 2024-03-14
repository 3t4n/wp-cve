<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WC_Order;
use WP_Post;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class SequentialOrderNumber implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Meta name for order number.
     */
    const META_NAME_ORDER_NUMBER = '_order_number';
    const OLD_NAMESPACE = 'inspire_invoices_woocommerce';
    const NEW_NAMESPACE = 'inspire_invoices_woocommerce_hpos';
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        if ('yes' === $this->settings->get('woocommerce_sequential_orders')) {
            $function = 'option_inspire_invoices_start_invoice_number';
            \add_filter('option_inspire_invoices_order_start_invoice_number', [$this, $function], 10, 2);
            \add_filter('option_inspire_invoices_correction_start_invoice_number', [$this, $function], 10, 2);
            \add_filter('option_inspire_invoices_proforma_start_invoice_number', [$this, $function], 10, 2);
            \add_action('woocommerce_new_order', [$this, 'set_order_num_action'], 10, 2);
            \add_action('wp_insert_post', [$this, 'set_order_num_action'], 10, 2);
            \add_action('woocommerce_process_shop_order_meta', [$this, 'set_order_num_action'], 10, 2);
            \add_filter('woocommerce_order_number', [$this, 'order_number_filter'], 10, 2);
            \add_filter('woocommerce_shop_order_search_fields', [$this, 'search_using_order_number_filter']);
            \add_action('init', [$this, 'install_numbering']);
        }
    }
    /**
     * @param array $search_fields Search fields.
     *
     * @return array
     *
     * @internal You should not use this directly from another application
     */
    public function search_using_order_number_filter(array $search_fields) : array
    {
        $search_fields[] = '_order_number';
        return $search_fields;
    }
    /**
     * Order number replacement filter.
     *
     * @param string   $order_number Order number.
     * @param WC_Order $order        Order.
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function order_number_filter(string $order_number, \WC_Order $order) : string
    {
        $replaced_num = $order->get_meta(self::META_NAME_ORDER_NUMBER, \true);
        if (!empty($replaced_num)) {
            return $replaced_num;
        }
        return $order_number;
    }
    /**
     * @param mixed  $value
     * @param string $option
     *
     * @return string
     *
     * @internal You should not use this directly from another application
     */
    public function option_inspire_invoices_start_invoice_number($value, string $option) : string
    {
        global $wpdb;
        //phpcs:disable
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->options} SET option_value = option_value WHERE option_name = %s", $option));
        $row = $wpdb->get_row($wpdb->prepare("SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1", $option));
        //phpcs:enable
        if (\is_object($row)) {
            $value = $row->option_value;
        }
        return $value;
    }
    /**
     * Invoices WooCommerce method.
     *
     * Set order num action
     *
     * @param int     $post_id Post ID.
     * @param object $post    Post object.
     *
     * @internal You should not use this directly from another application
     */
    public function set_order_num_action($post_id, $post_or_order)
    {
        if ($post_or_order instanceof \WP_Post && ('shop_order' !== $post_or_order->post_type || 'auto-draft' === $post_or_order->post_status)) {
            return;
        }
        $order = \wc_get_order($post_id);
        $order_number = $order->get_meta(self::META_NAME_ORDER_NUMBER, \true);
        if (!$order_number) {
            if ($this->settings->get('woocommerce_sequential_orders') === 'yes') {
                $this->is_hpos_active() ? $this->insert_order_number_to_order($order) : $this->insert_order_number_to_post($order);
            } else {
                $order->update_meta_data(self::META_NAME_ORDER_NUMBER, $order->get_id());
                $order->save();
            }
        }
    }
    private function insert_order_number_to_post($order)
    {
        global $wpdb;
        $order_id = $order->get_id();
        // Attempt the query up to 3 times for a much higher success rate if it fails (due to Deadlock).
        $success = \false;
        for ($i = 0; $i < 3 && !$success; $i++) {
            //phpcs:disable
            // This seems to me like the safest way to avoid order number clashes.
            $success = $wpdb->query('INSERT INTO ' . $wpdb->postmeta . ' (post_id,meta_key,meta_value) SELECT ' . $order_id . ',"_order_number",if(max(cast(meta_value as UNSIGNED)) is null,1,max(cast(meta_value as UNSIGNED))+1) from ' . $wpdb->postmeta . ' where meta_key="_order_number"');
            //phpcs:ignore
            //phpcs:enable
        }
    }
    private function insert_order_number_to_order($order)
    {
        global $wpdb;
        $order_id = $order->get_id();
        $success = \false;
        $wc_orders_meta = $wpdb->prefix . 'wc_orders_meta';
        if ($wpdb->get_var("SHOW TABLES LIKE '{$wc_orders_meta}'") == $wc_orders_meta) {
            for ($i = 0; $i < 3 && !$success; $i++) {
                //phpcs:disable
                // This seems to me like the safest way to avoid order number clashes.
                $success = $wpdb->query('INSERT INTO ' . $wc_orders_meta . ' (order_id,meta_key,meta_value) SELECT ' . $order_id . ',"_order_number",if(max(cast(meta_value as UNSIGNED)) is null,1,max(cast(meta_value as UNSIGNED))+1) from ' . $wc_orders_meta . ' where meta_key="_order_number"');
                //phpcs:ignore
                //phpcs:enable
            }
        }
    }
    private function is_hpos_active()
    {
        if (\class_exists('\\Automattic\\WooCommerce\\Utilities\\OrderUtil')) {
            return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
        }
        return \false;
    }
    /**
     * Invoices WooCommerce method.
     *
     * Install invoice numbering.
     *
     * @internal You should not use this directly from another application
     */
    public function install_numbering()
    {
        $namespace = $this->is_hpos_active() ? self::NEW_NAMESPACE : self::OLD_NAMESPACE;
        if (!\get_option($namespace)) {
            $orders = \wc_get_orders(['numberposts' => '', 'nopaging' => \true]);
            if (\is_array($orders)) {
                foreach ($orders as $order) {
                    if ($order->get_meta(self::META_NAME_ORDER_NUMBER, \true) === '') {
                        $order->add_meta_data(self::META_NAME_ORDER_NUMBER, $order->get_id());
                        $order->save();
                    }
                }
            }
            \update_option($namespace, 1);
        }
    }
}
