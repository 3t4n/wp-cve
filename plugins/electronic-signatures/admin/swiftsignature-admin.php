<?php
/**
 *      Swift Signature admin
 */
/**
 *  On plugin activation notice */
// check is plugin active
if (version_compare($GLOBALS['wp_version'], SWIFTSIGN_MINIMUM_WP_VERSION, '>=')) {
    add_action('admin_notices', 'ssign_admin_notice');
}

function ssign_admin_notice() {
    $SSIGN_MESSAGES = swiftsign_global_msg();
    if (!get_option('ssign_notice') && !get_option('swiftsign_pages')) {
        ?>
        <div class="notice notice-success is-dismissible ssing-notice">
            <p><b>SwiftCloud Signature Plugin</b></p>
            <form method="post">
                <p class="sr-notice-msg"><?php echo $SSIGN_MESSAGES['ssing_want_to_create']; ?></p>
                <ul>
                    <li>Thanks (return page after e-signature)</li>
                </ul>
                <?php wp_nonce_field('ssign_autogen_pages', 'ssign_autogen_pages'); ?>
                <button type="submit" value="yes" name="ssing_autogen_yes" class="button button-green"><i class="fa fa-check"></i> Yes</button>  <button type="submit" name="ssing_autogen_no" value="no" class="button button-default button-red"><i class="fa fa-ban"></i> No</button>
            </form>
        </div>
        <?php
    }
}

// admin menu.
add_action('admin_menu', 'ssign_control_panel');

function ssign_control_panel() {
    $SSIGN_MESSAGES = swiftsign_global_msg();
    $icon_url = plugins_url('/images/swiftcloud.png', __FILE__);
    $menu_slug = 'ss_control_panel';
    add_menu_page('Swift Signature', 'Swift Signature', 'manage_options', $menu_slug, 'ssign_settings_cb', $icon_url);

    add_submenu_page($menu_slug, $SSIGN_MESSAGES['ssing_nav_settings'], $SSIGN_MESSAGES['ssing_nav_settings'], 'manage_options', $menu_slug, null);
    add_submenu_page($menu_slug, $SSIGN_MESSAGES['ssing_nav_signed_docs'], $SSIGN_MESSAGES['ssing_nav_signed_docs'], 'manage_options', "ss_signed_docs", 'ssign_signed_docs_cb');
    add_submenu_page("", $SSIGN_MESSAGES['ssing_nav_signed_docs_details'], $SSIGN_MESSAGES['ssing_nav_signed_docs_details'], 'manage_options', 'ssign_signed_docs_details', 'ssign_signed_docs_details_cb');
    add_submenu_page($menu_slug, $SSIGN_MESSAGES['ssing_nav_help_setup'], $SSIGN_MESSAGES['ssing_nav_help_setup'], 'manage_options', "ssign_help_setup", 'ssign_help_setup_cb');
    add_submenu_page($menu_slug, $SSIGN_MESSAGES['ssing_nav_update_tips'], $SSIGN_MESSAGES['ssing_nav_update_tips'], 'manage_options', 'ssign_dashboard', 'ssign_dashboard_cb');
}

// styles and scripts
add_action('admin_enqueue_scripts', 'ssign_enqueue_admin_scripts_styles');

function ssign_enqueue_admin_scripts_styles() {
    wp_enqueue_style('ss-admin-style', plugins_url('/css/swiftsignature-admin-style.css', __FILE__), '', '', '');
    wp_enqueue_script('ss-admin-script', plugins_url('/js/swiftsignature-admin-script.js', __FILE__), array('jquery'), '', true);
    wp_localize_script('ss-admin-script', 'ssign_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_style('swift-cloud-jquery-ui', plugins_url('/css/jquery-ui.min.css', __FILE__), '', '', '');
}

include("section/swiftsignature-messages.php");
include("section/swiftsignature-dashboard.php"); //done
include("section/swiftsignature-settings.php");
include("section/swiftsignature-signed-docs.php");
include("section/swiftsignature-help-setup.php");

add_action("init", "ssign_form_submit");

function ssign_form_submit() {
    /*
     * Notice
     * on plugin active auto generate pages and options  */
    if (isset($_POST['ssign_autogen_pages']) && wp_verify_nonce($_POST['ssign_autogen_pages'], 'ssign_autogen_pages')) {
        if ($_POST['ssing_autogen_yes'] == 'yes') {
            ssign_initial_data();
        }
        update_option('ssign_notice', true);
    }
}

