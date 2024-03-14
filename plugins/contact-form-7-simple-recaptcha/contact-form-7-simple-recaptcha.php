<?php
/*
Plugin Name: Contact Form 7 Captcha
Description: Add reCAPTCHA or hCAPTCHA to Contact Form 7 using [cf7sr-recaptcha] or [cf7sr-hcaptcha] shortcode
Version: 0.1.4
Author: 247wd
Text Domain: cf7sr-free
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CF7SR_VERSION', '1.0' );
define( 'CF7SR_PLUGIN', __FILE__ );
define( 'CF7SR_PLUGIN_BASENAME', plugin_basename( CF7SR_PLUGIN ) );
define( 'CF7SR_PLUGIN_NAME', untrailingslashit( dirname( CF7SR_PLUGIN_BASENAME ) ) );
define( 'CF7SR_PLUGIN_DIR', untrailingslashit( dirname( CF7SR_PLUGIN ) ) );
define( 'CF7SR_PLUGIN_URL', untrailingslashit( plugin_dir_url( CF7SR_PLUGIN ) ) );

require_once CF7SR_PLUGIN_DIR . '/includes/languages.php';
require_once CF7SR_PLUGIN_DIR . '/includes/recaptcha.php';
require_once CF7SR_PLUGIN_DIR . '/includes/hcaptcha.php';

function cf7sr_load_admin_css() {
    $page = ! empty( $_GET['page'] ) ? $_GET['page'] : '';

    if ( 'cf7sr-edit' == $page ) {
        wp_enqueue_style( 'cf7sr-admin-css', CF7SR_PLUGIN_URL . '/assets/css/admin.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'cf7sr_load_admin_css' );

function cf7sr_load_textdomain() {
    load_plugin_textdomain( 'cf7sr-free', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    if ( isset( $_GET['cf7sr-notice-hcaptcha'] ) ) {
        update_option( 'cf7sr_notice_hcaptcha', 1 );
    }
}
add_action( 'init', 'cf7sr_load_textdomain' );

function cf7sr_add_action_links($links) {
    array_unshift($links , '<a href="' . admin_url( 'options-general.php?page=cf7sr-edit' ) . '">Settings</a>');
    array_unshift($links , '<a target="_blank" style="color: #df7128; font-weight: 700;" href="https://lukasapps.de/wordpress/plugins/cf7-captcha-pro/">Get Captcha Pro</a>');
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'cf7sr_add_action_links', 10, 2 );

function cf7sr_activation_notice() {
    $cf7sr_notice_hcaptcha = get_option('cf7sr_notice_hcaptcha');
    if (empty($cf7sr_notice_hcaptcha)) { ?>
        <div class="notice notice-success" style="position: relative">
            <p><?php echo __( 'Contact Form 7 Captcha plugin updated to support reCAPTCHA and hCAPTCHA.', 'cf7sr-free' ); ?></p>
            <p>
                <a href="<?php echo admin_url( 'options-general.php?page=cf7sr-edit' ); ?>"><?php echo __( 'Settings', 'cf7sr-free' ); ?></a>
                <a style="text-decoration: none" href="<?php admin_url() ?>?cf7sr-notice-hcaptcha=0" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
            </p>
        </div>
   <?php }
}
add_action( 'admin_notices', 'cf7sr_activation_notice' );

function cf7sr_adminhtml() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'cf7sr-free' ) );
    }

    if ( ! class_exists( 'WPCF7_Submission' ) ) {
        echo __( '<p>To use <strong>Contact Form 7 Captcha</strong> please install or update <strong>Contact Form 7</strong> plugin as current version is not supported.</p>', 'cf7sr-free' );
        return;
    }

    $tabs = array(
        'pro'         => 'Captcha PRO',
        'recaptcha'   => 'reCaptcha',
        'hcaptcha'    => 'hCaptcha'
    );

    $tab = ! empty( $_GET['tab'] ) && isset( $tabs[ $_GET['tab'] ] ) ? $_GET['tab'] : 'pro';

    ?>
    <div class="wrap">
        <nav class="nav-tab-wrapper">
            <?php foreach ( $tabs as $tabKey => $tabLabel ) { ?>
                <a href="<?php echo esc_attr( admin_url( 'options-general.php?page=cf7sr-edit&tab=' . $tabKey ) ); ?>"
                   class="nav-tab <?php echo $tab == $tabKey ? 'nav-tab-active' : ''; ?>">
                    <?php echo __( $tabLabel, 'cf7sr-free' ); ?>
                </a>
            <?php } ?>
        </nav>
        <div class="cf7sr-content">
            <?php require_once CF7SR_PLUGIN_DIR . '/includes/admin-' . $tab . '.php'; ?>
        </div>
    </div>
    <script>
        var cf7srMsg = document.querySelector('.cf7sr-msg');
        if (cf7srMsg) {
            setTimeout(function() {
                cf7srMsg.remove();
            }, 3000);
        }
    </script>
    <?php
}

function cf7sr_addmenu() {
    add_submenu_page ('options-general.php', 'Contact Form 7 Captcha', 'Contact Form 7 Captcha', 'manage_options', 'cf7sr-edit', 'cf7sr_adminhtml' );
}
add_action('admin_menu', 'cf7sr_addmenu');

