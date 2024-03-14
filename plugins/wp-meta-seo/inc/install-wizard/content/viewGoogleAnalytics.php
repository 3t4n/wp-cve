<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
$wizard = new WpmsInstallWizard();
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View request, no action
$step      = isset($_GET['step']) ? sanitize_key($_GET['step']) : '';
$next_link = $wizard->getNextLink($step);

require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
$client = WpmsGaTools::initClient(WPMS_CLIENTID, WPMS_CLIENTSECRET);
$authUrl = $client->createAuthUrl();

?>

<form method="post">
    <?php wp_nonce_field('wpms-setup-wizard', 'wizard_nonce'); ?>
    <input type="hidden" name="wpms_save_step" value="1"/>
    <div class="wizard-header">
        <div class="title font-size-35"><?php esc_html_e('Google Analytics', 'wp-meta-seo'); ?></div>
        <p class="ju-description"><?php esc_html_e('Enable Google Analytics tracking and reports using a Google Analytics direct connection. It require a Google Analytics account creation first', 'wp-meta-seo') ?></p>
    </div>
    <div class="wizard-content">
        <div class="ju-settings-option  wpms_width_100 p-d-20">
            <div class="wpms_row_full">
                <label class="ju-setting-label text">
                    <?php esc_html_e('Google Analytics tracking only', 'wp-meta-seo') ?></label>
                <p class="description text_left p-d-20">
                    <?php esc_html_e('Enable google analytics tracking only, you won’t be able to display statistic in your wordpress admin, only google analytics webiste', 'wp-meta-seo'); ?>
                </p>

                <div class="ju-settings-option wpms-no-shadow wpms_width_100 p-d-20">
                    <label class="wpms_width_100 p-b-20 wpms_left text label_text">
                        <?php esc_html_e('Analytics UA-X refrence', 'wp-meta-seo'); ?>
                    </label>

                    <label>
                        <input type="text" class="wpms_ga_uax_reference wpms_width_100 text_field"
                               name="wpms_ga_uax_reference">
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="wizard-footer">
        <div class="wpms_row_full">
            <input type="submit" value="<?php esc_html_e('Continue', 'wp-meta-seo'); ?>" class="m-tb-20"
                   name="wpms_save_step"/>
        </div>

        <a href="<?php echo esc_url(admin_url('admin.php?page=metaseo_dashboard')) ?>"
           class="go-to-dash"><span><?php esc_html_e('Skip installer and go to Dashboard', 'wp-meta-seo'); ?></span></a>
    </div>
</form>