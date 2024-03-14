<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<div class="wrap wpsf-wrap">
    <div class="wpsf-header wpsf-clearfix">
        <h1 class="wpsf-floatLeft">
            <img src="<?php echo WPSF_URL . 'images/logo.png' ?>" class="wpsf-plugin-logo" />
            <span class="wpsf-sub-header"><?php esc_html_e('Subscribers', 'wp-subscription-forms'); ?></span>
        </h1>

        <div class="wpsf-add-wrap">
            <?php
            if (!empty($_GET['form_alias'])) {
                $form_alias = sanitize_text_field($_GET['form_alias']);
                $export_url = admin_url('admin-post.php?action=wpsf_export_csv&form_alias=' . $form_alias . '&_wpnonce=' . wp_create_nonce('wpsf_export_csv_nonce'));
            } else {
                $export_url = admin_url('admin-post.php?action=wpsf_export_csv&_wpnonce=' . wp_create_nonce('wpsf_export_csv_nonce'));
            }
            ?>
            <a href="<?php echo esc_url($export_url); ?>"><input type="button" class="wpsf-button-orange" value="<?php esc_html_e('Export to CSV', 'wp-subscription-forms'); ?>"></a>
        </div>
    </div>
    <div class="wpsf-form-wrap wpsf-left-wrap">
        <div class="wpsf-export-wrap">
            <form method="get" action="<?php echo admin_url('admin.php'); ?>" class="wpsf-subscriber-export-form">
                <input type="hidden" name="page" value="wpsf-subscribers"/>
                <select name="form_alias" class="wpsf-export-alias-trigger">
                    <option value=""><?php esc_html_e('All', 'wp-subscription-forms'); ?></option>
                    <?php
                    global $wpdb;
                    $form_table = WPSF_FORM_TABLE;
                    $form_rows = $wpdb->get_results("select * from $form_table order by form_title asc");
                    $selected_form_alias = (!empty($_GET['form_alias'])) ? sanitize_text_field($_GET['form_alias']) : '';
                    if (!empty($form_rows)) {
                        foreach ($form_rows as $form_row) {
                            ?>
                            <option value="<?php echo esc_attr($form_row->form_alias); ?>" <?php selected($selected_form_alias, $form_row->form_alias); ?>><?php echo esc_attr($form_row->form_title); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </form>

        </div>
        <table class="wp-list-table widefat fixed wpsf-form-lists-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Subscriber Name', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Subscriber Email', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Form', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Action', 'wp-subscription-forms'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $subscriber_table = WPSF_SUBSCRIBERS_TABLE;
                $form_table = WPSF_FORM_TABLE;
                if (!empty($_GET['form_alias'])) {
                    $form_alias = sanitize_text_field($_GET['form_alias']);
                    $subscriber_query = $wpdb->prepare("select * from $subscriber_table inner join $form_table on $subscriber_table.subscriber_form_alias = $form_table.form_alias where subscriber_form_alias = %s", $form_alias);
                } else {
                    $subscriber_query = "select * from $subscriber_table inner join $form_table on $subscriber_table.subscriber_form_alias = $form_table.form_alias";
                }
                $subscriber_rows = $wpdb->get_results($subscriber_query);
                if (!empty($subscriber_rows)) {
                    foreach ($subscriber_rows as $subscriber_row) {
                        ?>
                        <tr>
                            <td><?php echo esc_attr($subscriber_row->subscriber_name); ?></td>
                            <td><?php echo esc_attr($subscriber_row->subscriber_email); ?></td>
                            <td><?php echo esc_attr($subscriber_row->form_title); ?></td>
                            <td>
                                <a class="wpsf-delete wpsf-subscriber-delete" href="javascript:void(0)" data-subscriber-id="<?php echo intval($subscriber_row->subscriber_id); ?>" title="<?php esc_html_e('Delete Subscriber', 'wp-subscription-forms'); ?>"><?php esc_html_e('Delete', 'wp-subscription-forms'); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5"><?php esc_html_e('No subscribers found.', 'wp-subscription-forms'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php esc_html_e('Subscriber Name', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Subscriber Email', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Form', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Action', 'wp-subscription-forms'); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php include(WPSF_PATH . 'inc/views/backend/upgrade-to-pro.php'); ?>


</div>
<div class="wpsf-form-message"></div>
