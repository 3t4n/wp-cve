<?php declare(strict_types=1);

/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="mailup-plugin-container">
    <div class="mailup-masthead">
        <div class="mailup-masthead__inside-container inner-pages">
            <div class="mailup-masthead__logo-container">
                <a href="<?php _e('https://www.mailup.com', 'mailup'); ?>" target="_blank" class="mailup-masthead__logo-link">
                    <img class="mailup-masthead__logo"
                        src="<?php echo plugin_dir_url(__DIR__).'/images/logo_vector.png'; ?>" alt="MailUp">
                </a>
            </div>
        </div>
    </div>
    <div class="mailup-lower inner-pages">
        <div class="mailup-boxes">
            <div class="mailup-card">
                <div class="mailup-section-header">
                    <div class="mailup-section-header__label" id="btn-general-settings" data="general-settings">
                        <span><?php _e('General Settings', 'mailup'); ?></span>
                    </div>
                    <div class="mailup-section-header__label" id="btn-form-fields" data="form-fields">
                        <span><?php _e('Form Fields', 'mailup'); ?></span>
                    </div>
                    <div class="mailup-section-header__label" id="btn-advanced-settings" data="advanced-settings">
                        <span><?php _e('Advanced Settings', 'mailup'); ?></span>
                    </div>
                    <div class="shortcode">
                        <span>shortcode: <b>[mailup_form]</b></span>
                    </div>
                </div>
                <div class="mailup-section-content__box" id="general-settings">
                    <?php require __DIR__.'/mailup-admin-form.php'; ?>
                </div>
                <div class="mailup-section-content__box" id="form-fields">
                    <?php require __DIR__.'/mailup-admin-form-fields.php'; ?>
                </div>
                <div class="mailup-section-content__box" id="advanced-settings">
                    <?php require __DIR__.'/mailup-admin-advanced-settings.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>