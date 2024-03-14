<?php

namespace WP_Rplg_Google_Reviews\Includes\Admin;

class Admin_Tophead {

    public function register() {
        add_action('wp_after_admin_bar_render', array($this, 'render'));
    }

    public function render() {
        $current_screen = get_current_screen();

        if (empty($current_screen)) {
            return;
        }

        if (strpos($current_screen->id, 'grw') !== false) {

            $current_screen->render_screen_meta();

            ?>
            <div class="grw-tophead">
                <div class="grw-tophead-title">
                    <!--span class="grw-tophead-logo" style="position: relative; margin-right: 10px; vertical-align: middle">
                        <svg width="32" height="32" viewBox="0 0 1792 1792"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="#FABB08"></path></svg>
                        <svg width="18" height="18" viewBox="-5 -5 10 10" style="position: absolute;bottom: 2px;right: 4px;outline: 2px solid #fff;border-radius: 50%;background: #fff;">
                            <defs>
                                <path id="a" d="M 3 0 A 3.1 3.1 0 0 0 0 -3 L 0 -5 A 5 5 0 0 1 5 0"/>
                            </defs>
                            <use xlink:href="#a" fill="#4285F4" transform="rotate(45)"/>
                            <use xlink:href="#a" fill="#34A853" transform="rotate(135)"/>
                            <use xlink:href="#a" fill="#FABB08" transform="rotate(225)"/>
                            <use xlink:href="#a" fill="#E94135" transform="rotate(315)"/>
                        </svg>
                        <svg width="12" height="12" viewBox="0 0 512 512" style="position: absolute; bottom: 7px; right: 9px; width: 8px; height: 8px; border-radius: 50%;"><path fill="#34A853" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" style="
                        "></path></svg>
                    </span!-->
                    <img src="<?php esc_attr_e(GRW_ASSETS_URL . 'img/logo.png') ?>" alt="logo">
                    Google Reviews
                </div>
                <div class="grw-version">
                    <div class="grw-version-free">Free Version: <?php echo GRW_VERSION; ?></div>
                    <div class="grw-version-upgrade">
                        <span>Upgrade to business</span>
                        <div id="grw-upgrade-tips">
                            <div class="grw-upgrade-head">Most easiest way to show all G reviews with business version</div>
                            No Place ID, No API key, No Billing needed, only Google My Business (GMB) owner account to show all G reviews with constantly auto synced
                            <!--div>30% off with promo code: GRGROW23</div-->
                            <a href="https://richplugins.com/business-reviews-bundle-wordpress-plugin?promo=GRGROW23" target="_blank">Upgrade today with 30% off!</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
