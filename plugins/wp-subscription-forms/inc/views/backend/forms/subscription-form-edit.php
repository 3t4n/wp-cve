<?php
defined('ABSPATH') or die('No script kiddies please!!');
$form_details = maybe_unserialize($form_row->form_details);
$preview_url = site_url() . '?wpsf_preview=true&form_alias=' . esc_attr($form_row->form_alias) . '&_wpnonce=' . wp_create_nonce('wpsf_form_preview_nonce');
?>
<div class="wrap wpsf-wrap">
    <div class="wpsf-header wpsf-clearfix">
        <h1 class="wpsf-floatLeft">
            <img src="<?php echo WPSF_URL . 'images/logo.png' ?>" class="wpsf-plugin-logo" />
            <span class="wpsf-sub-header"><?php esc_html_e('Edit Subscription Form', 'wp-subscription-forms'); ?></span>
        </h1>
        <div class="wpsf-add-wrap">
            <a href="javascript:void(0);" class="wpsf-form-save-trigger"><input type="button" class="wpsf-button-primary" value="<?php esc_html_e('Save', 'wp-subscription-forms'); ?>"></a>
            <a href="<?php echo esc_url($preview_url); ?>" target="_blank"><input type="button" class="wpsf-button-orange" value="<?php esc_html_e('Preview', 'wp-subscription-forms'); ?>"></a>
            <a href="<?php echo admin_url('admin.php?page=wp-subscription-forms'); ?>"><input type="button" class="wpsf-button-red" value="<?php esc_html_e('Cancel', 'wp-subscription-forms'); ?>"></a>
        </div>
    </div>
    <div class="wpsf-form-wrap wpsf-form-add-block wpsf-clearfix">
        <form method="post" action="" class="wpsf-subscription-form wpsf-left-wrap">
            <input type="hidden" name="form_id" value="<?php echo intval($form_row->form_id); ?>"/>
            <?php
            /**
             * Navigation Menu
             */
            include(WPSF_PATH . 'inc/views/backend/form-sections/navigation.php');
            ?>
            <div class="wpsf-settings-section-wrap">
                <div class="wpsf-field-wrap">
                    <label><?php esc_html_e('Shortcode', 'wp-subscription-forms') ?></label>
                    <div class="wpsf-field">
                        <span class="wpsf-shortcode-preview">[wp_subscription_forms alias="<?php echo esc_attr($form_row->form_alias); ?>"]</span>
                        <span class="wpsf-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                </div>
                <?php
                /**
                 * General Settings
                 */
                include(WPSF_PATH . 'inc/views/backend/form-sections/general-settings.php');
                ?>
                <?php
                /**
                 * Form Settings
                 */
                include(WPSF_PATH . 'inc/views/backend/form-sections/form-settings.php');
                ?>
                <?php
                /**
                 * Layout Settings
                 */
                include(WPSF_PATH . 'inc/views/backend/form-sections/layout-settings.php');
                ?>
                <?php
                /**
                 * Email Settings
                 */
                include(WPSF_PATH . 'inc/views/backend/form-sections/email-settings.php');
                ?>

            </div>

        </form>

        <?php include(WPSF_PATH . 'inc/views/backend/upgrade-to-pro.php'); ?>


    </div>
</div>
<div class="wpsf-form-message"></div>