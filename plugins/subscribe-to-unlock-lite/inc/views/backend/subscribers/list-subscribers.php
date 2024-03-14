<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<div class="wrap stul-wrap">
    <div class="stul-header stul-clearfix">
        <h1 class="stul-floatLeft">
            <img src="<?php echo STUL_URL . 'images/logo.png' ?>" class="stul-plugin-logo" />
            <span class="stul-sub-header"><?php esc_html_e('Subscribers', 'subscribe-to-unlock-lite'); ?></span>
        </h1>

        <div class="stul-add-wrap">
            <?php
            $export_url = admin_url('admin-post.php?action=stul_export_csv&_wpnonce=' . wp_create_nonce('stul_export_csv_nonce'));
            ?>
            <a href="<?php echo esc_url($export_url); ?>"><input type="button" class="stul-button-orange" value="<?php esc_html_e('Export to CSV', 'subscribe-to-unlock-lite'); ?>"></a>
        </div>
        <div class="stul-social">
            <a href="https://www.facebook.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-facebook-alt"></i></a>
            <a href="https://twitter.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-twitter"></i></a>
        </div>
    </div>
    <div class="stul-form-wrap">
        <table class="wp-list-table widefat fixed stul-form-lists-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Subscriber Name', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Subscriber Email', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Verified', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Action', 'subscribe-to-unlock-lite'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $subscriber_table = STUL_SUBSCRIBERS_TABLE;
                $form_table = STUL_FORM_TABLE;
                $subscriber_query = "select * from $subscriber_table";
                $subscriber_rows = $wpdb->get_results($subscriber_query);
                if (!empty($subscriber_rows)) {
                    foreach ($subscriber_rows as $subscriber_row) {
                        ?>
                        <tr>
                            <td><?php echo esc_attr($subscriber_row->subscriber_name); ?></td>
                            <td><?php echo esc_attr($subscriber_row->subscriber_email); ?></td>
                            <td><?php echo (!empty($subscriber_row->subscriber_verification_status)) ? esc_html__('Yes', 'subscribe-to-unlock-lite') : esc_html__('No', 'subscribe-to-unlock-lite'); ?></td>
                            <td>
                                <a class="stul-delete stul-subscriber-delete" href="javascript:void(0)" data-subscriber-id="<?php echo intval($subscriber_row->subscriber_id); ?>" title="<?php esc_attr_e('Delete Subscriber', 'subscribe-to-unlock-lite'); ?>"><?php esc_html_e('Delete', 'subscribe-to-unlock-lite'); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5"><?php esc_html_e('No subscribers found.', 'subscribe-to-unlock-lite'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php esc_html_e('Subscriber Name', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Subscriber Email', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Verified', 'subscribe-to-unlock-lite'); ?></th>
                    <th><?php esc_html_e('Action', 'subscribe-to-unlock-lite'); ?></th>
                </tr>
            </tfoot>
        </table>
        <?php include(STUL_PATH . 'inc/views/backend/upgrade-to-pro-sidebar.php'); ?>
    </div>
</div>
<div class="stul-form-message"></div>
