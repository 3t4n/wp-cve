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
            <a href="javascript:void(0);" class="wpsf-form-save-trigger"><input type="button" class="wpsf-button-primary" value="<?php esc_html_e('Save', 'wp-subscription-forms'); ?>"></a>
            <a href="<?php echo admin_url('admin.php?page=wp-subscription-forms'); ?>"><input type="button" class="wpsf-button-red" value="<?php esc_html_e('Cancel', 'wp-subscription-forms'); ?>"></a>
        </div>
    </div>

    <div class="wpsf-form-wrap wpsf-form-add-block wpsf-clearfix">
        <form method="post" action="" class="wpsf-subscription-form wpsf-left-wrap">
            <?php
            /**
             * Navigation Menu
             */
            include(WPSF_PATH . 'inc/views/backend/form-sections/navigation.php');
            ?>
            <div class="wpsf-settings-section-wrap">

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