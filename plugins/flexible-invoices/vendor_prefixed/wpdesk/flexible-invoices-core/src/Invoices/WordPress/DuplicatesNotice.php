<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\Notice\Notice;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Show notice for administrator if the documents have the same number or title.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class DuplicatesNotice implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const POST_TYPE_NAME = 'inspire_invoice';
    const TRANSIENT_NAME = 'flexible_invoices_duplicates';
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('save_post', [$this, 'check_for_duplicates'], 10, 2);
        \add_action('admin_init', [$this, 'add_duplicates_notice_error']);
    }
    /**
     * @return void
     */
    public function add_duplicates_notice_error()
    {
        if ($this->has_duplicates()) {
            // Translators: %s url.
            new \WPDeskFIVendor\WPDesk\Notice\Notice(\sprintf(\__('<strong>Warning!</strong> There are documents with the same number in the invoice list. Check <a href="%s">here</a>.', 'flexible-invoices'), \admin_url('edit.php?post_type=' . self::POST_TYPE_NAME . '&filter=show_duplicated')), \WPDeskFIVendor\WPDesk\Notice\Notice::NOTICE_TYPE_ERROR, \true);
            /**
             * Fires when duplicates exist.
             *
             * @since 3.0.0
             */
            \do_action('fi/core/duplicates', \true);
        }
    }
    private function has_duplicates() : bool
    {
        return 'yes' === \get_transient(self::TRANSIENT_NAME);
    }
    public function check_for_duplicates($post_id, $post)
    {
        if ($post->post_type === self::POST_TYPE_NAME) {
            $has_duplicates = $this->find_duplicates();
            if ($has_duplicates) {
                \set_transient(self::TRANSIENT_NAME, 'yes', MONTH_IN_SECONDS);
            } else {
                \delete_transient(self::TRANSIENT_NAME);
            }
        }
    }
    /**
     * @return int
     */
    private function find_duplicates() : int
    {
        global $wpdb;
        //phpcs:ignore
        return (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(post_title) FROM {$wpdb->posts} WHERE `post_type` = %s AND `post_status` = 'publish' GROUP BY `post_title` HAVING COUNT(post_title) > 1", self::POST_TYPE_NAME));
        //phpcs:ignore
    }
}
