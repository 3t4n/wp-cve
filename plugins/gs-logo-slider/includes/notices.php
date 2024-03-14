<?php

namespace GSLOGO;

class Notices {
    public function __construct() {
        add_action('admin_init', [$this, 'gslogo_review_notice']);
        add_action('admin_notices', [ $this, 'gslogo_admin_notice' ] );
        add_action('admin_init', [ $this, 'gslogo_nag_ignore' ] );
    }

    public function gslogo_review_notice() {

        $this->review_dismiss();
        $this->review_pending();

        $activation_time    = get_site_option('gslogo_active_time');
        $review_dismissal   = get_site_option('gslogo_review_dismiss');
        $maybe_later        = get_site_option('gslogo_maybe_later');

        if ('yes' == $review_dismissal) {
            return;
        }

        if (!$activation_time) {
            add_site_option('gslogo_active_time', time());
        }

        $daysinseconds = 259200; // 3 Days in seconds.

        if ('yes' == $maybe_later) {
            $daysinseconds = 604800; // 7 Days in seconds.
        }

        if (time() - $activation_time > $daysinseconds) {
            add_action('admin_notices', [$this, 'gslogo_review_notice_message']);
            // delete_user_meta($userId, 'terms_and_conditions');
        }
    }

    /**
     * For the notice preview.
     */
    public function gslogo_review_notice_message() {

        $server_req_uri = sanitize_url($_SERVER['REQUEST_URI']);

        $scheme      = (parse_url($server_req_uri, PHP_URL_QUERY)) ? '&' : '?';
        $url         = $server_req_uri . $scheme . 'gslogo_review_dismiss=yes';
        $dismiss_url = wp_nonce_url($url, 'gslogo-review-nonce');

        $_later_link = $server_req_uri . $scheme . 'gslogo_review_later=yes';
        $later_url   = wp_nonce_url($_later_link, 'gslogo-review-nonce');

        ?>

        <div class="gslogo-review-notice">
            <div class="gslogo-review-thumbnail">
                <img src="<?php echo GSL_PLUGIN_URI . 'assets/img/gsl.png'; ?>" alt="">
            </div>
            <div class="gslogo-review-text">
                <h3><?php _e('Leave A Review?', 'gslogo') ?></h3>
                <p><?php _e('We hope you\'ve enjoyed using <b>GS Logo Slider</b>! Would you consider leaving us a review on WordPress.org?', 'gslogo') ?></p>
                <ul class="gslogo-review-ul">
                    <li>
                        <a href="https://wordpress.org/support/plugin/gs-logo-slider/reviews/" target="_blank">
                            <span class="dashicons dashicons-external"></span>
                            <?php _e('Sure! I\'d love to!', 'gslogo') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url_raw($dismiss_url); ?>">
                            <span class="dashicons dashicons-smiley"></span>
                            <?php _e('I\'ve already left a review', 'gslogo') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url_raw($later_url); ?>">
                            <span class="dashicons dashicons-calendar-alt"></span>
                            <?php _e('Maybe Later', 'gslogo') ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.gsplugins.com/support" target="_blank">
                            <span class="dashicons dashicons-sos"></span>
                            <?php _e('I need help!', 'gslogo') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url_raw($dismiss_url); ?>">
                            <span class="dashicons dashicons-dismiss"></span>
                            <?php _e('Never show again', 'gslogo') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <style>
            .gslogo-review-notice {
                padding: 15px 15px 15px 0;
                background-color: #fff;
                border-radius: 3px;
                margin: 20px 20px 0 0;
                border-left: 4px solid transparent;
            }

            .gslogo-review-notice:after {
                content: '';
                display: table;
                clear: both;
            }

            .gslogo-review-thumbnail {
                width: 114px;
                float: left;
                line-height: 80px;
                text-align: center;
                border-right: 4px solid transparent;
            }

            .gslogo-review-thumbnail img {
                width: 72px;
                vertical-align: middle;
                opacity: .85;
                -webkit-transition: all .3s;
                -o-transition: all .3s;
                transition: all .3s;
            }

            .gslogo-review-thumbnail img:hover {
                opacity: 1;
            }

            .gslogo-review-text {
                overflow: hidden;
            }

            .gslogo-review-text h3 {
                font-size: 24px;
                margin: 0 0 5px;
                font-weight: 400;
                line-height: 1.3;
            }

            .gslogo-review-text p {
                font-size: 13px;
                margin: 0 0 5px;
            }

            .gslogo-review-ul {
                margin: 0;
                padding: 0;
            }

            .gslogo-review-ul li {
                display: inline-block;
                margin-right: 15px;
            }

            .gslogo-review-ul li a {
                display: inline-block;
                color: #10738B;
                text-decoration: none;
                padding-left: 26px;
                position: relative;
            }

            .gslogo-review-ul li a span {
                position: absolute;
                left: 0;
                top: -2px;
            }
        </style>

         <?php
    }

    public function review_dismiss() {

        if (
            !is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'gslogo-review-nonce') ||
            !isset($_GET['gslogo_review_dismiss'])
        ) {

            return;
        }

        add_site_option('gslogo_review_dismiss', 'yes');
    }

    public function review_pending() {

        if (
            !is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'gslogo-review-nonce') ||
            !isset($_GET['gslogo_review_later'])
        ) {

            return;
        }
        // Reset Time to current time.
        update_site_option('gslogo_active_time', time());
        update_site_option('gslogo_maybe_later', 'yes');
    }

    public function gslogo_admin_notice() {
        if (current_user_can('install_plugins')) {
            global $current_user;
            $user_id = $current_user->ID;
            /* Check that the user hasn't already clicked to ignore the message */
            if (!get_user_meta($user_id, 'gslogo_ignore_notice279')) {
                echo '<div class="gslogo-admin-notice updated" style="display: flex; align-items: center; padding-left: 0; border-left-color: #EF4B53"><p style="width: 32px; padding-left: 8px; padding-right: 6px;">';
                echo '<img style="width: 100%; display: block;"  src="' . GSL_PLUGIN_URI . 'assets/img/gsl.png' . '" ></p><p> ';
                printf(__('<strong>GS Logo Slider</strong> now powering <strong>20,000+</strong> websites. Use the coupon code <strong>CELEBRATE20K</strong> to redeem a <strong>25&#37; </strong> discount on Pro. <a href="https://www.gsplugins.com/product/gs-logo-slider" target="_blank" style="text-decoration: none;"><span class="dashicons dashicons-smiley" style="margin-left: 10px;"></span> Apply Coupon</a>
                    <a href="%1$s" style="text-decoration: none; margin-left: 10px;"><span class="dashicons dashicons-dismiss"></span> I\'m good with free version</a>'),  admin_url('edit.php?post_type=gs-logo-slider&page=gs-logo-shortcode&gslogo_nag_ignore=0'));
                echo "</p></div>";
            }
        }
    }

    public function gslogo_nag_ignore() {

        global $current_user;
        $user_id = $current_user->ID;
    
        /* If user clicks to ignore the notice, add that to their user meta */
        if (isset($_GET['gslogo_nag_ignore']) && '0' == $_GET['gslogo_nag_ignore']) {
            add_user_meta($user_id, 'gslogo_ignore_notice279', 'true', true);
        }
    }

    public function gsadmin_signup_notice_message() {
        $server_req_uri = sanitize_url($_SERVER['REQUEST_URI']);
        $scheme      = (parse_url($server_req_uri, PHP_URL_QUERY)) ? '&' : '?';
        $_later_link = $server_req_uri . $scheme . 'gsadmin_signup_later=yes';
        $later_url   = wp_nonce_url($_later_link, 'gsadmin-signup-nonce');
    ?>
        <div class=" gstesti-admin-notice updated gsteam-review-notice">
            <div class="gsteam-review-text">
                <h3><?php _e('GS Plugins Affiliate Program is now LIVE!', 'gst') ?></h3>
                <p>Join GS Plugins affiliate program. Share our 80% OFF lifetime bundle deals or any plugin with your friends/followers and earn up to 50% commission. <a href="https://www.gsplugins.com/affiliate-registration/?utm_source=wporg&utm_medium=admin_notice&utm_campaign=aff_regi" target="_blank">Click here to sign up.</a></p>
                <ul class="gsteam-review-ul">
                    <li style="display: inline-block;margin-right: 15px;">
                        <a href="<?php echo esc_url_raw($later_url); ?>" style="display: inline-block;color: #10738B;text-decoration: none;position: relative;">
                            <span class="dashicons dashicons-dismiss"></span>
                            <?php _e('Hide Now', 'gst') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    <?php
    }



















    













    /**
     * For Maybe Later signup.
     */
    function gsadmin_signup_pending() {

        if (
            !is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'gsadmin-signup-nonce') ||
            !isset($_GET['gsadmin_signup_later'])
        ) {

            return;
        }
        // Reset Time to current time.
        update_site_option('gsadmin_active_time', time());
        update_site_option('gsadmin_maybe_later', 'yes');
    }
}
