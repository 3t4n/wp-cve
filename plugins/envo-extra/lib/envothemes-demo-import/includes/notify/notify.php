<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * @review_dismiss()
 * @review_pending()
 * @envothemes_review_notice_message()
 * Make all the above functions working.
 */
function envothemes_review_notice() {

    envothemes_review_dismiss();
    envothemes_review_pending();

    $activation_time = get_site_option('envothemes_active_time');
    $review_dismissal = get_site_option('envothemes_review_dismiss');
    $maybe_later = get_site_option('envothemes_maybe_later');
	$onboard = get_site_option('elementor_onboarded');
	if (!$onboard) {
        add_site_option('elementor_onboarded', true);
    }

    if ('yes' == $review_dismissal) {
        return;
    }

    if (!$activation_time) {
        add_site_option('envothemes_active_time', time());
    }

    $daysinseconds = 2419200; // 1209600 14 Days in seconds.
    if ('yes' == $maybe_later) {
        $daysinseconds = 4819200; // 28 Days in seconds.
    }

    if (time() - $activation_time > $daysinseconds) {
        add_action('admin_notices', 'envothemes_review_notice_message');
    }
}

add_action('admin_init', 'envothemes_review_notice');

/**
 * For the notice preview.
 */
function envothemes_review_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envothemes_review_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'envo-review-nonce');

    $_later_link = $server . $scheme . 'envothemes_review_later=yes';
    $later_url = wp_nonce_url($_later_link, 'envo-review-nonce');
    $theme = wp_get_theme();
    $themetemplate = get_stylesheet();
    $themename = $theme->name;
    ?>

    <div class="envo-review-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_URL) . 'img/et-logo.png'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Leave A Review?', 'envothemes-demo-import') ?></h3>
            <p><?php echo sprintf(esc_html__('We hope you\'ve enjoyed using %1$s theme! Would you consider leaving us a review on WordPress.org?', 'envothemes-demo-import'), esc_html($themename)) ?></p>
            <ul class="envo-review-ul">
                <li>
                    <a href="https://wordpress.org/support/theme/<?php echo esc_html($themetemplate); ?>/reviews/?rate=5#new-post" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Sure! I\'d love to!', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('I\'ve already left a review', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($later_url) ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php esc_html_e('Maybe Later', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="https://envothemes.com/contact/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php esc_html_e('I need help!', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php esc_html_e('Never show again', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For Dismiss! 
 */
function envothemes_review_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-review-nonce') ||
            !isset($_GET['envothemes_review_dismiss'])) {

        return;
    }

    add_site_option('envothemes_review_dismiss', 'yes');
}

/**
 * For Maybe Later Update.
 */
function envothemes_review_pending() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-review-nonce') ||
            !isset($_GET['envothemes_review_later'])) {

        return;
    }
    // Reset Time to current time.
    update_site_option('envothemes_active_time', time());
    update_site_option('envothemes_maybe_later', 'yes');
}

function envothemes_pro_notice() {

    envothemes_pro_dismiss();
	
	$theme = wp_get_theme();
    $themetemplate = $theme->template;
	$activetheme = str_replace('-', '_', strtoupper($themetemplate));

    $activation_time = get_site_option('envothemes_active_pro_time');

    if (!$activation_time) {
        add_site_option('envothemes_active_pro_time', time());
    }

    $daysinseconds = 86400; // 1 Day in seconds.

    if (time() - $activation_time > $daysinseconds) {
        if (defined($activetheme . '_PRO_CURRENT_VERSION')) {
            return;
        }
		if (preg_match('[envo|enwoo|entr]', $themetemplate) ) {
			add_action('admin_notices', 'envothemes_pro_notice_message');
		}
    }
}

add_action('admin_init', 'envothemes_pro_notice');

/**
 * For PRO notice 
 */
function envothemes_pro_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envothemes_pro_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'envo-pro-nonce');
	$theme = wp_get_theme();
    $themetemplate = $theme->template;
	if ($themetemplate == 'enwoo') {
		$templateurl = 'https://enwoo-wp.com/enwoo-pro/';
	} elseif ($themetemplate == 'entr') {
		$templateurl = 'https://envothemes.com/envo-pro/';
	} else {
		$templateurl = 'https://envothemes.com/product/' . $themetemplate . '-pro/';
	}
	
    ?>

    <div class="envo-review-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_URL) . 'img/et-logo.png'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Go PRO for More Features', 'envothemes-demo-import') ?></h3>
            <p>
                <?php echo sprintf(esc_html__('Get the %1$s for more stunning elements, demos and customization options.', 'envothemes-demo-import'), '<a href="'.esc_url($templateurl).'" target="_blank">PRO addon</a>') ?>
            </p>
            <ul class="envo-review-ul">
                <li class="show-mor-message">
                    <a href="<?php echo esc_url($templateurl) ?>" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo esc_url($dismiss_url) ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For PRO Dismiss! 
 */
function envothemes_pro_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'envo-pro-nonce') ||
            !isset($_GET['envothemes_pro_dismiss'])) {

        return;
    }
    $daysinseconds = 604800; // 14 Days in seconds (1209600).
    $newtime = time() + $daysinseconds;
    update_site_option('envothemes_active_pro_time', $newtime);
}

/**
 * Enwoo notice
 */
function envo_extra_enwoo_notice() {

    envo_extra_enwoo_dismiss();
    
    $theme = wp_get_theme();
    $activation_time = get_site_option('active_enwoo_time');

    if (!$activation_time) {
        add_site_option('active_enwoo_time', time());
    }

    $daysinseconds = 600; // 1 Day in seconds.

    if (time() - $activation_time > $daysinseconds) {
        if (defined('ENWOO_PRO_CURRENT_VERSION') || defined('ENVO_SHOPPER_PRO_CURRENT_VERSION') || defined('ENVO_ECOMMERCE_PRO_CURRENT_VERSION') || defined('ENVO_STOREFRONT_PRO_CURRENT_VERSION') || defined('ENVO_SHOP_PRO_CURRENT_VERSION') || defined('ENVO_ONLINE_STORE_PRO_CURRENT_VERSION') || defined('ENVO_MARKETPLACE_PRO_CURRENT_VERSION') || defined('ENVO_SHOPPER_PRO_CURRENT_VERSION')) {
            return;
        }
        if ( 'Enwoo' != $theme->name || 'enwoo' != $theme->template ) {
            add_action('admin_notices', 'envo_extra_enwoo_notice_message');
        }
    }
}

add_action('admin_init', 'envo_extra_enwoo_notice');

/**
 * For shop notice 
 */
function envo_extra_enwoo_notice_message() {
	if (isset($_SERVER['REQUEST_URI'])) {
		$server = sanitize_text_field( wp_unslash($_SERVER['REQUEST_URI']));
	}
    $scheme = (parse_url($server, PHP_URL_QUERY)) ? '&' : '?';
    $url = $server . $scheme . 'envo_extra_enwoo_dismiss=yes';
    $dismiss_url = wp_nonce_url($url, 'enwoo-nonce');
    ?>

    <div class="envo-review-notice envo-shop-notice">
        <div class="envo-review-thumbnail">
            <img src="<?php echo esc_url(ENVO_EXTRA_PLUGIN_URL) . 'images/enwoo.jpg'; ?>" alt="">
        </div>
        <div class="envo-review-text">
            <h3><?php esc_html_e('Try our new FREE Multipurpose and WooCommerce WordPress Theme - Enwoo', 'envothemes-demo-import') ?></h3>
            <p>
                <?php
                echo sprintf(
                        esc_html__('%1$s - new free Multipurpose and WooCommerce theme. Check out theme %2$s, that can be imported for FREE with simple click.', 'envothemes-demo-import'),
                        '<a href="https://enwoo-wp.com/" target="_blank">Enwoo</a>',
                        '<a href="https://enwoo-wp.com/demos/" target="_blank">Demos</a>')
                ?>
            </p>
            <ul class="envo-review-ul">
                <li class="show-mor-message">
                    <a href="https://enwoo-wp.com/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php esc_html_e('Show me more', 'envothemes-demo-import') ?>
                    </a>
                </li>
                <li class="hide-message">
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php esc_html_e('Hide this message', 'envothemes-demo-import') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

/**
 * For shop Dismiss! 
 */
function envo_extra_enwoo_dismiss() {

    if (!is_admin() ||
            !current_user_can('manage_options') ||
            !isset($_GET['_wpnonce']) ||
            !wp_verify_nonce(sanitize_key(wp_unslash($_GET['_wpnonce'])), 'enwoo-nonce') ||
            !isset($_GET['envo_extra_enwoo_dismiss'])) {

        return;
    }
    $daysinseconds = 1209600; // 14 Days in seconds (1209600).
    $newtime = time() + $daysinseconds;
    update_site_option('active_enwoo_time', $newtime);
}
