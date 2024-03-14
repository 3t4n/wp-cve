<?php
defined('ABSPATH') or die('No script kiddies please!!');
?>
<div class="wrap wpsf-wrap">
    <div class="wpsf-header wpsf-clearfix">
        <h1 class="wpsf-floatLeft">
            <img src="<?php echo WPSF_URL . 'images/logo.png' ?>" class="wpsf-plugin-logo" />
            <span class="wpsf-sub-header"><?php esc_html_e('Subscription Forms', 'wp-subscription-forms'); ?></span>
        </h1>
        <div class="wpsf-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=add-subscription-form'); ?>"><input type="button" class="wpsf-button-primary" value="<?php esc_html_e('Add New Form', 'wp-subscription-forms'); ?>"></a>
        </div>

    </div>
    <div class="wpsf-form-wrap wpsf-left-wrap">

        <table class="wp-list-table widefat fixed wpsf-form-lists-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Form Title', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Alias', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Shortcode', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Status', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Action', 'wp-subscription-forms'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $form_table = WPSF_FORM_TABLE;
                $form_rows = $wpdb->get_results("select * from $form_table order by form_title asc");
                if (!empty($form_rows)) {
                    foreach ($form_rows as $form_row) {
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=wp-subscription-forms&form_id=' . $form_row->form_id . '&action=edit_form'); ?>" title="<?php esc_html_e('Edit Form', 'wp-subscription-forms'); ?>"><?php echo esc_attr($form_row->form_title); ?></a>
                            </td>
                            <td><?php echo esc_attr($form_row->form_alias); ?></td>
                            <td>
                                <span class="wpsf-shortcode-preview">[wp_subscription_forms alias="<?php echo esc_attr($form_row->form_alias); ?>"]</span>
                                <span class="wpsf-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
                            </td>
                            <td><?php echo (!empty($form_row->form_status)) ? esc_html__('Active', 'wp-subscription-forms') : esc_html__('Inactive', 'wp-subscription-forms'); ?></td>
                            <td>
                                <a class="wpsf-edit" href="<?php echo admin_url('admin.php?page=wp-subscription-forms&form_id=' . $form_row->form_id . '&action=edit_form'); ?>" title="<?php esc_html_e('Edit Form', 'wp-subscription-forms'); ?>"><?php esc_html_e('Edit', 'wp-subscription-forms'); ?></a>
                                <a class="wpsf-copy wpsf-form-copy" href="javascript:void(0);" data-form-id="<?php echo intval($form_row->form_id); ?>" title="<?php esc_html_e('Copy Form', 'wp-subscription-forms'); ?>"><?php esc_html_e('Copy', 'wp-subscription-forms'); ?></a>
                                <a class="wpsf-preview" href="<?php echo site_url() . '?wpsf_preview=true&form_alias=' . esc_attr($form_row->form_alias) . '&_wpnonce=' . wp_create_nonce('wpsf_form_preview_nonce'); ?>" target="_blank" title="<?php esc_html_e('Preview', 'wp-subscription-forms'); ?>"><?php esc_html_e('Preview', 'wp-subscription-forms'); ?></a>
                                <a class="wpsf-delete wpsf-form-delete" href="javascript:void(0)" data-form-id="<?php echo intval($form_row->form_id); ?>" title="<?php esc_html_e('Delete Form', 'wp-subscription-forms'); ?>"><?php esc_html_e('Delete', 'wp-subscription-forms'); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5"><?php esc_html_e('No forms added yet.', 'wp-subscription-forms'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><?php esc_html_e('Form Title', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Alias', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Shortcode', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Status', 'wp-subscription-forms'); ?></th>
                    <th><?php esc_html_e('Action', 'wp-subscription-forms'); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php include(WPSF_PATH . 'inc/views/backend/upgrade-to-pro.php'); ?>


</div>
<div class="wpsf-form-message"></div>
