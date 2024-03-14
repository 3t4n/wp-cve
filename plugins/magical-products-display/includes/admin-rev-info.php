<?php
/*
* Magical products display info
*
*
*/

/**
 * Rev notice text
 *
 */
function mpd_display_rev_want()
{

?>
    <div class="mgadin-hero">
        <div class="mge-info-content">
            <div class="mge-info-hello">
                <?php
                $current_user = wp_get_current_user();
                $rev_link = 'https://wordpress.org/support/plugin/wp-edit-password-protected/reviews/?filter=5';

                esc_html_e('Hello, ', 'magical-products-display');
                echo esc_html($current_user->display_name);
                ?>

                <?php esc_html_e('👋🏻', 'magical-products-display'); ?>
            </div>
            <div class="mge-info-desc">
                <div><?php echo esc_html('We hope you are enjoying using Magical Products Display plugin for your WordPress website. If you find this plugin helpful, please consider leaving a review on our plugin page. Your review will help us improve our plugin and serve you better.', 'magical-products-display'); ?></div>
                <div class="mge-offer"><?php echo esc_html('Your Good feedback is valuable to us, and it helps us improve the plugin.', 'magical-products-display'); ?></div>
            </div>
            <div class="mge-info-actions">
                <a href="<?php echo esc_url($rev_link); ?>" target="_blank" class="button button-primary upgrade-btn">
                    <?php esc_html_e('Give A 5stars Review', 'magical-products-display'); ?>
                </a>
                <button class="button button-info mgpd-revdismiss"><?php esc_html_e('Already Did', 'magical-products-display') ?></button>
                <button class="button button-info mgpd-dismiss"><?php esc_html_e('Don\'t Like This Plugin', 'magical-products-display') ?></button>
            </div>

        </div>

    </div>
<?php
}


//Admin notice 
function mpd_display_new_optins_texts()
{
    $hide_date = get_option('mpd_revhide_date');
    $mpd_install_date = get_option('mpd_install_date');

    global $pagenow;
    if (get_option('mpd_rev_added')) {
        return;
    }
    if (!empty($hide_date)) {
        $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
        if ($clickhide < 25) {
            return;
        }
    }
    $mpd_install_date = get_option('mpd_install_date');
    if (!empty($mpd_install_date)) {
        $mpd_install_date = round((time() - strtotime($mpd_install_date)) / 24 / 60 / 60);
        if ($mpd_install_date < 3) {
            return;
        }
    }

    wp_enqueue_style('admin-info-style');
?>
    <div class="mgadin-notice notice notice-success mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
        <?php mpd_display_rev_want(); ?>
    </div>
<?php


}
add_action('admin_notices', 'mpd_display_new_optins_texts');

function mpd_display_new_optins_texts_init()
{
    if (isset($_GET['dismissed']) && $_GET['dismissed'] == 1) {
        update_option('mpd_revhide_date', current_time('mysql'));
    }
    if (isset($_GET['revadded']) && $_GET['revadded'] == 1) {
        update_option('mpd_rev_added', 1);
    }
}
add_action('init', 'mpd_display_new_optins_texts_init');
