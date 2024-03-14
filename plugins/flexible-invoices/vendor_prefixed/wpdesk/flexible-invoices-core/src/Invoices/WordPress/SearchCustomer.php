<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_User_Query;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Search customer.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class SearchCustomer implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    const NONCE_ARG = 'security';
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_woocommerce-invoice-user-select', [$this, 'select_ajax_user_search']);
    }
    /*
     * Search user via AJAX for user list
     *
     * @internal You should not use this directly from another application
     */
    public function select_ajax_user_search()
    {
        $client_options = [];
        if (\check_ajax_referer('inspire_invoices', self::NONCE_ARG, \false)) {
            $name = isset($_POST['name']) ? \sanitize_text_field(\wp_unslash($_POST['name'])) : '';
            if (!empty($name)) {
                if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
                    $users = new \WP_User_Query(['meta_query' => [
                        // phpcs:ignore
                        'relation' => 'OR',
                        ['key' => 'billing_first_name', 'value' => \esc_sql($name), 'compare' => 'LIKE'],
                        ['key' => 'billing_last_name', 'value' => \esc_sql($name), 'compare' => 'LIKE'],
                        ['key' => 'billing_company', 'value' => \esc_sql($name), 'compare' => 'LIKE'],
                    ]]);
                } else {
                    $users = new \WP_User_Query(['search' => '*' . \esc_sql($name) . '*', 'search_columns' => ['user_login', 'user_nicename', 'user_email', 'user_url']]);
                }
                $results = $users->get_results();
                foreach ($results as $user) {
                    $data = $this->prepare_user_data($user);
                    $client_options['users:' . $user->ID] = ['id' => $user->ID, 'text' => $data['name'], 'details' => $data];
                }
            }
            $post_meta_results = $this->should_get_user_data_from_post_meta($name);
            foreach ($post_meta_results as $post_id => $meta_data) {
                $client_options['meta:' . $post_id] = ['id' => $post_id, 'text' => $meta_data['name'], 'details' => $meta_data];
            }
            \wp_send_json(['items' => \array_values($client_options)]);
        }
        \wp_send_json($client_options);
    }
    private function should_get_user_data_from_post_meta($term) : array
    {
        global $wpdb;
        $search = \is_integer($term) ? (int) $term : \esc_sql($term);
        $sql_where = "`meta_key` = '_client_filter_field' AND `meta_value` LIKE '%" . $search . "%'";
        if (\is_int($search)) {
            // Find as vat number.
            $sql_where = "`meta_key` = '_client_vat_number' AND `meta_value` LIKE '" . $search . "%'";
        }
        $results = $wpdb->get_results("SELECT MAX(meta_id) as meta_id, `meta_value`, `post_id` FROM {$wpdb->postmeta} WHERE {$sql_where} ");
        if (!empty($results)) {
            return $this->get_meta_data($results);
        }
        return [];
    }
    private function get_meta_data($meta_results) : array
    {
        $user_data = [];
        foreach ($meta_results as $meta) {
            $post_meta = \get_post_meta($meta->post_id);
            $user_data[$meta->post_id] = ['name' => $meta->meta_value, 'street' => $post_meta['_client_street'][0] ?? '', 'street2' => $post_meta['_client_street2'][0] ?? '', 'postcode' => $post_meta['_client_postcode'][0] ?? '', 'city' => $post_meta['_client_city'][0] ?? '', 'nip' => $post_meta['_client_vat_number'][0] ?? '', 'country' => $post_meta['_client_country'][0] ?? '', 'phone' => $post_meta['_client_phone'][0] ?? '', 'email' => $post_meta['_client_email'][0] ?? '', 'state' => $post_meta['_client_state'][0] ?? ''];
        }
        return $user_data;
    }
    private function prepare_user_data(\WP_User $user) : array
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $user_data = ['name' => empty($user->billing_company) ? $user->billing_first_name . ' ' . $user->billing_last_name : $user->billing_company, 'street' => $user->billing_address_1, 'street2' => !empty($user->billing_address_2) ? $user->billing_address_2 : '', 'postcode' => $user->billing_postcode, 'city' => $user->billing_city, 'nip' => $user->vat_number, 'country' => $user->billing_country, 'phone' => $user->billing_phone, 'email' => $user->user_email, 'state' => $user->billing_state];
        } else {
            return ['name' => $user->first_name . ' ' . $user->last_name, 'street' => '', 'street2' => '', 'postcode' => '', 'city' => '', 'nip' => '', 'country' => '', 'phone' => '', 'email' => $user->user_email, 'state' => ''];
        }
        return $user_data;
    }
}
