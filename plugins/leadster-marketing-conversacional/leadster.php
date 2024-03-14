<?php
/**
 * @package Leadster
 */

/**
* Plugin Name: Leadster
* Description: Make the Leadster script implementation process easier. | Facilite o processo de implementação do script da Leadster.
* Version: 1.2.1
* Author: Leadster
* License: GPL v3
* Author URI: https://leadster.com.br/
* Text Domain: leadster
* Domain Path: /i18n/
*/

if (!defined("ABSPATH")) die("go away!");

/**
 * CONSTANTS
 */
define("LEADSTER_KEY_CODE", "leadster");
define("LEADSTER_DIR_PATH", plugin_dir_path(__FILE__));
define("LEADSTER_DIR_URL", plugin_dir_url(__FILE__));

/**
 * Load I18n
 */
function leadster_load_text_domain()
{
    load_plugin_textdomain("leadster", false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
}

/**
 * Admin Menu
 */
function leadster_admin_menu()
{
    add_options_page(
        "Leadster",
        "Leadster",
        "manage_options",
        LEADSTER_KEY_CODE,
        "leadster_options_page"
    );
}

/**
 * Options Page
 * @throws Exception
 */
function leadster_options_page()
{
    if (isset($_GET["settings-updated"])) {
        $leadsterCode = sanitize_text_field(get_option("leadster-script-code"));

        if ($leadsterCode == "") {
            set_notices("warning");
        }
    }

    leadster_add_page_template();
}

/**
 * Load custom styles.
 */
function leadster_admin_css()
{
    $plugin_url = LEADSTER_DIR_URL . "assets/css/style.css";
    wp_enqueue_style("style", $plugin_url);
}

/**
 * Page Settings Link
 * @param $links
 * @return mixed
 */
function leadster_add_plugin_page_settings_link($links)
{
    $plugin_page_url = admin_url("options-general.php?page=" . LEADSTER_KEY_CODE);
    $links[] = '<a href="'. $plugin_page_url .'">' . __("Settings") . '</a>';

    return $links;
}

/**
 *  Load content template HTML.
 */
function leadster_add_page_template()
{
    leadster_admin_notices();
    include(LEADSTER_DIR_PATH . "views/content.php");
}


/**
 * Save script code id to options
 * @throws Exception
 */
function leadster_script_code_action()
{
    try{
        if (!leadster_is_nonce_valid()) {
            throw new Exception("nonce error");
        }

        $codeId = sanitize_text_field($_POST["leadster-script-code"]);
        $codeId = trim($codeId);

        $leadsterCode = sanitize_text_field(get_option("leadster-script-code"));

        if ($leadsterCode !== false) {
            update_option("leadster-script-code", $codeId);

            if (empty($codeId)) {
                set_notices("warning");
                redirect_to_leadster_config_admin();
                return;
            }

            set_notices("success");
            redirect_to_leadster_config_admin();

            return;
        }

        add_option("leadster-script-code", $codeId);
        set_notices("success");

        redirect_to_leadster_config_admin();
    } catch (Exception $exception) {
        set_notices("error");

        redirect_to_leadster_config_admin();
    }
}

/**
 * @return bool
 */
function leadster_is_nonce_valid()
{
    return isset($_POST["leadster_nonce"])
        && wp_verify_nonce($_POST["leadster_nonce"], "leadster-nonce")
        && is_user_logged_in();
}

/**
 * Include script to footer
 */
function leadster_add_widget_to_footer()
{
    $leadster_script_code = esc_attr(get_option("leadster-script-code"));

    if (! empty($leadster_script_code)){
        include(LEADSTER_DIR_PATH . "views/script-code.php");
    }
}

/**
 * @param $type
 * @throws Exception
 */
function set_notices($type)
{
    $types = ["success", "warning", "error"];

    if (!in_array($type, $types)) {
        throw new Exception("notice invalid");
    }

    set_transient("leadster_admin_notice_{$type}", true, 5 );
}

/**
 * Load notices
 */
function leadster_admin_notices()
{
    if (get_transient("leadster_admin_notice_warning")) {
        include(LEADSTER_DIR_PATH . "views/notices/warning.php");

        delete_transient("leadster_admin_notice_warning");
        return;
    }

    if (get_transient("leadster_admin_notice_success")) {
        include(LEADSTER_DIR_PATH . "views/notices/success.php");

        delete_transient("leadster_admin_notice_success");
        return;
    }

    if (get_transient("leadster_admin_notice_error")) {
        include(LEADSTER_DIR_PATH . "views/notices/error.php");

        delete_transient("leadster_admin_notice_error");
    }
}


/**
 * Redirect to Leadster configuration page
 */
function redirect_to_leadster_config_admin()
{
    wp_safe_redirect(admin_url("/options-general.php?page=" . LEADSTER_KEY_CODE));
}

##################################################################################################
## ACTIONS
##################################################################################################

## MENU ##
add_action("plugins_loaded", "leadster_load_text_domain" );

add_action("admin_menu", "leadster_admin_menu");

## OPTIONS ##
add_action("admin_notices", "leadster_admin_notices" );

add_filter("plugin_action_links_" . plugin_basename(__FILE__), "leadster_add_plugin_page_settings_link");

add_action("admin_print_styles", "leadster_admin_css");

add_action("admin_post_leadster_script_code", "leadster_script_code_action");

add_action("wp_footer", "leadster_add_widget_to_footer");