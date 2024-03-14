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
function mpd_display_pro_want()
{

?>
    <div class="mgadin-hero">
        <div class="mge-info-content">
            <div class="mge-info-hello">
                <?php
                $current_user = wp_get_current_user();
                $mppro_link = 'https://wpthemespace.com/product/magical-products-display-pro/';
                $mppro_link1 = 'https://wpthemespace.com/product/magical-products-display-pro/?add-to-cart=9177';

                esc_html_e('Hello, ', 'magical-products-display');
                echo esc_html($current_user->display_name);
                ?>

                <?php esc_html_e('👋🏻', 'magical-products-display'); ?>
            </div>
            <div class="mge-info-desc">
                <div class="mge-offer"><?php echo esc_html(' 🌟 Upgrade to Pro Magic Now! ✨', 'magical-products-display'); ?></div>
                <div><?php echo esc_html('Welcome the enchantment of the Magical Products Display Pro plugin. Experience advanced icons with tooltips, mesmerizing product image flips, stock sliders, discount badges, and a world of Pro widgets that will cast a spell on your audience. Don\'t wait – conjure the Pro version now and watch your product displays come alive like never before!', 'magical-products-display'); ?></div>
            </div>
            <div class="mge-info-actions">
                <a href="<?php echo esc_url($mppro_link1); ?>" target="_blank" class="button button-primary upgrade-btn" style="background:#b40000">
                    <?php esc_html_e('Quick Upgrade', 'magical-products-display'); ?>
                </a>
                <a href="<?php echo esc_url($mppro_link); ?>" target="_blank" class="button button-primary upgrade-btn">
                    <?php esc_html_e('View Pricing', 'magical-products-display'); ?>
                </a>
                <button class="button button-info mgpd-dismiss"><?php esc_html_e('Hide Notice', 'magical-products-display') ?></button>
            </div>

        </div>

    </div>
<?php
}


//Admin notice 
function mpd_display_pinfo_optins_texts()
{
    $hide_date = get_option('mpdpro_hide_date2');
    $mpd_install_date = get_option('mpd_install_date');


    $mpd_install_date = get_option('mpd_install_date');
    if (!empty($mpd_install_date)) {
        $mpd_install_date = round((time() - strtotime($mpd_install_date)) / 24 / 60 / 60);
        if ($mpd_install_date < 3) {
            return;
        }
    }
    if (!empty($hide_date)) {
        $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
        if ($clickhide < 25) {
            return;
        }
    }

    wp_enqueue_style('admin-info-style');
?>
    <div class="mgadin-notice notice notice-success mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
        <?php mpd_display_pro_want(); ?>
    </div>
<?php


}
add_action('admin_notices', 'mpd_display_pinfo_optins_texts');

function mpd_display_proinfo_texts_init()
{
    if (isset($_GET['dismissed']) && $_GET['dismissed'] == 1) {
        update_option('mpdpro_hide_date2', current_time('mysql'));
    }
}
add_action('init', 'mpd_display_proinfo_texts_init');
