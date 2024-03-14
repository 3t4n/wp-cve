<?php
defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Review')) {
    class WPSF_Review {
        function __construct() {
            add_action('admin_init', [$this, 'ask_for_review']);
            add_action('admin_post_wpsf_hide_review_notice', [$this, 'save_review_notice_preference']);
        }

        function save_review_notice_preference() {
            if (isset($_POST['wpsf_hide_review_notice'], $_POST['wpsf_hide_review_notice_field']) && $_POST['wpsf_hide_review_notice'] == 1 && wp_verify_nonce($_POST['wpsf_hide_review_notice_field'], 'wpsf_hide_review_notice')) {
                update_user_meta(get_current_user_id(), 'wpsf_hide_review_notice', true);
                wp_redirect(add_query_arg(array('wpsf_review_notice_saved' => '1'), wp_get_referer()));
            }
        }

        function ask_for_review() {
            $plugin_install_date = get_option('wpsf_plugin_install_date');
            $days_since_install = absint((time() - strtotime($plugin_install_date)) / DAY_IN_SECONDS);
            if ($days_since_install >= 7 && !get_user_meta(get_current_user_id(), 'wpsf_hide_review_notice', true)) {
                // Show a notice or popup on the WordPress dashboard asking the user to leave a review
                add_action('admin_notices', [$this, 'review_notice']);
            }
        }

        function review_notice() {
?>
            <div class="notice notice-success is-dismissible" id="wpsf-review-notice">
                <p><?php _e('🚀 Loving <strong>WP Subscription Forms</strong>? Your feedback matters! Help us grow by sharing your experience. 💖 Please take a moment to leave a review. It only takes a minute, and your insights are invaluable in making the plugin even better. Thank you for being part of our community!', 'wp-subscription-forms'); ?></p>
                <p><a href="https://wordpress.org/support/plugin/wp-subscription-forms/reviews/#new-post" target="_blank" class="button-secondary"><?php _e('Click here to leave a review', 'wp-subscription-forms'); ?></a><label><input type="checkbox" id="wpsf_hide_review_notice" name="wpsf_hide_review_notice" value="1" /><?php _e('Do not show this notice again', 'wp-subscription-forms') ?></label></p>

                <form id="wpsf-hide-review-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="wpsf_hide_review_notice" />
                    <input type="hidden" name="wpsf_hide_review_notice" value="1" />
                    <?php wp_nonce_field('wpsf_hide_review_notice', 'wpsf_hide_review_notice_field'); ?>
                </form>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('wpsf_hide_review_notice').addEventListener('change', function() {
                        if (this.checked) {
                            document.getElementById('wpsf-hide-review-form').submit();
                        }
                    });
                });
            </script>
<?php
        }
    }

    new WPSF_Review();
}
