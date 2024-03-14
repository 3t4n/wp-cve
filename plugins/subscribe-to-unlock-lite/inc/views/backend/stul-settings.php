<?php
defined('ABSPATH') or die('No script kiddies please!!');
$form_details = get_option('stul_settings');
$preview_url = site_url() . '?stul_preview=true&_wpnonce=' . wp_create_nonce('stul_form_preview_nonce');
?>
<div class="wrap stul-wrap">
    <div class="stul-header stul-clearfix">
        <h1 class="stul-floatLeft">
            <img src="<?php echo STUL_URL . 'images/logo.png' ?>" class="stul-plugin-logo" />
            <span class="stul-sub-header"><?php esc_html_e('Settings', 'subscribe-to-unlock-lite'); ?></span>
        </h1>
        <div class="stul-add-wrap">
            <a href="javascript:void(0);" class="stul-form-save-trigger"><input type="button" class="stul-button-white" value="<?php esc_html_e('Save', 'subscribe-to-unlock-lite'); ?>"></a>
            <a href="https://wpshuffle.com/contact-us/?enquiry_for=subscribe-to-unlock-lite" target="_blank"><input type="button" class="stul-button-white" value="<?php esc_html_e('Need Assistance', 'subscribe-to-unlock-lite'); ?>"></a>
            <a href="<?php echo esc_url($preview_url); ?>" target="_blank"><input type="button" class="stul-button-orange" value="<?php esc_html_e('Preview', 'subscribe-to-unlock-lite'); ?>"></a>
        </div>
        <div class="stul-social">
            <a href="https://www.facebook.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-facebook-alt"></i></a>
            <a href="https://twitter.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-twitter"></i></a>
        </div>
    </div>

    <div class="stul-form-wrap stul-form-add-block stul-clearfix">
        <form method="post" action="" class="stul-subscription-form" data-form-action="stul_settings_save_action">
            <?php
            /**
             * Navigation Menu
             */
            include(STUL_PATH . 'inc/views/backend/form-sections/navigation.php');
            ?>
            <div class="stul-settings-section-wrap">
                <div class="stul-field-wrap">
                    <label><?php esc_html_e('Dynamic Content Lock Shortcode', 'subscribe-to-unlock-lite') ?></label>
                    <div class="stul-field">
                        <div class="stul-shortcode-preview">
                            [subscribe_to_unlock_form]<br />
                            The <br />
                            content<br />
                            to be<br />
                            locked<br />
                            [/subscribe_to_unlock_form]
                        </div>
                        <p class="description"><?php esc_html_e('Please use our shortcode wrap to lock the content directly from the editor. When any HTML inside the content is wrapped with our plugin\'s shortcode like above manner, it will lock that specific HTML.', 'subscribe-to-unlock-lite'); ?></p>
                    </div>
                </div>
                <div class="stul-field-wrap">
                    <label><?php esc_html_e('Static Content Lock Shortcode', 'subscribe-to-unlock-lite') ?></label>
                    <div class="stul-field">
                        <span class="stul-shortcode-preview">[subscribe_to_unlock_form]</span>
                        <span class="stul-clipboard-copy"><i class="fas fa-clipboard-list"></i></span>
                    </div>
                </div>

                <?php
                /**
                 * General Settings
                 */
                include(STUL_PATH . 'inc/views/backend/form-sections/general-settings.php');
                ?>
                <?php
                /**
                 * Form Settings
                 */
                include(STUL_PATH . 'inc/views/backend/form-sections/form-settings.php');
                ?>
                <?php
                /**
                 * Layout Settings
                 */
                include(STUL_PATH . 'inc/views/backend/form-sections/layout-settings.php');
                ?>
                <?php
                /**
                 * Email Settings
                 */
                include(STUL_PATH . 'inc/views/backend/form-sections/email-settings.php');
                ?>

            </div>

        </form>

        <?php include(STUL_PATH . 'inc/views/backend/upgrade-to-pro-sidebar.php'); ?>

    </div>
</div>
<div class="stul-form-message"></div>