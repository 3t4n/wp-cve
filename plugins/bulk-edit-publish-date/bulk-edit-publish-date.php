<?php
/*
Plugin Name: Bulk edit publish date
Description: Allows bulk editing of the publish date
Version: 1.0
Author: Felix Eve
License: GPLv2 or later
Text Domain: bulk-edit-publish-date
*/

class BulkEditPublishDate
{
    /**
     * Used to keep track of which post types we have bound bulk actions to.
     *
     * @var array
     */
    private $bulk_actions_applied = [];

    /**
     * BulkEditPublishDate constructor.
     */
    public function __construct() {

        // Add required javascript.
        add_action('admin_init', [$this, 'enqueue_scripts']);

        // Only bind bulk actions after all post types have been registered.
        add_filter('registered_post_type', [$this, 'after_registered_post_type'], 999);

        // Create admin notice.
        add_action('admin_notices', [$this, 'bulk_action_admin_notice']);

    }

    /**
     * Once all post types have been registered apply custom bulk action callbacks.
     */
    public function after_registered_post_type() {

        $post_types = get_post_types(['public' => true]);

        // Allow other plugins the chance to change which post types should have this bulk action.
        $post_types = apply_filters('bulk_edit_publish_date_post_types', $post_types);

        foreach ($post_types as $post_type) {

            // Don't bind actions to each post type more than once.
            if (in_array($post_type, $this->bulk_actions_applied)) {
                continue;
            }

            // Create custom bulk action.
            add_filter('bulk_actions-edit-' . $post_type, [$this, 'register_bulk_actions']);

            // Handle processing of bulk action.
            add_filter('handle_bulk_actions-edit-' . $post_type, [$this, 'bulk_action_handler'], 10, 3);

            // Record that this custom post types bulk actions have been bound.
            $this->bulk_actions_applied[] = $post_type;
        }

    }

    /**
     * Add required javascript.
     */
    public function enqueue_scripts() {
        wp_enqueue_script('bulk-edit-publish-date', plugin_dir_url(__FILE__) . 'js/bulk-edit-publish-date.js', ['jquery'], false, true);
    }

    /**
     * Create custom bulk action.
     */
    public function register_bulk_actions($bulk_actions) {
        $bulk_actions['set_publish_date'] = __('Set publish date', 'bulk-edit-publish-date');
        return $bulk_actions;
    }

    /**
     * Handle processing of bulk action.
     */
    public function bulk_action_handler($redirect_to, $doaction, $post_ids) {
        if ($doaction !== 'set_publish_date') {
            return $redirect_to;
        }

        $post_date = date('Y-m-d H:i:s', strtotime($_GET['publish_date'] . ' ' . $_GET['publish_time']));
        $post_date_gmt = gmdate('Y-m-d H:i:s', strtotime($post_date));
        $post_status = strtotime($post_date) > strtotime('now') ? 'future' : 'publish';

        foreach ($post_ids as $post_id) {
            $post_data = [
                'ID'            => $post_id,
                'post_date'     => $post_date,
                'post_date_gmt' => $post_date_gmt,
                'post_status'   => $post_status,
                'edit_date'     => true,
            ];
            // Allow other plugins to alter the post_data before the post is updated.
            $post_data = apply_filters('bulk_edit_publish_date_post_update_data', $post_data);
            wp_update_post($post_data);
        }

        $query_args = [
            'bepd_updated_count' => count($post_ids),
            'bepd_date'          => $post_date,
        ];
        $redirect_to = add_query_arg($query_args, $redirect_to);
        return $redirect_to;
    }

    /**
     * Create admin notice.
     */
    public function bulk_action_admin_notice() {
        if (!empty($_REQUEST['bepd_updated_count'])) {
            $count = intval($_REQUEST['bepd_updated_count']);
            $date = date(get_option('date_format'), strtotime($_REQUEST['bepd_date']));
            $message = _n('Set publish date to %s for %s post.', 'Set publish date to %s for %s posts.', $count, 'bulk-edit-publish-date');
            $format = '<div id="message" class="updated fade">' . $message . '</div>';

            // Allow other plugins to make changes to the admin notice before we print it.
            $format = apply_filters('bulk_edit_publish_date_admin_notice', $format);
            printf($format, $date, $count);
        }
    }

}

$BulkEditPublishDate = new BulkEditPublishDate();
