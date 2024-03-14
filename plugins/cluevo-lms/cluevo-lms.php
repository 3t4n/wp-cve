<?php

/**
 *
 * @link              https://wp-lms.cluevo.at
 * @since             1.0.0
 * @package           cluevo
 *
 * @wordpress-plugin
 * Plugin Name:       CLUEVO LMS
 * Description:       CLUEVO LMS ist ein Plugin das deine WordPress Installation in ein Learning Management System verwandelt
 * Version:           1.13.1
 * Author:            CLUEVO
 * Author URI:        https://wp-lms.cluevo.at/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cluevo
 * Domain Path:       /lang

 cluevo is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.

 cluevo is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with cluevo. If not, see http://www.gnu.org/licenses/gpl-2.0.txt
 */

define('CLUEVO_VERSION', '1.13.1');
require_once('conf/config.inc.php');  // various config variables
define('CLUEVO_REQUIRED_PHP_VERSION', '5.6');
define('CLUEVO_REQUIRED_MYSQL_VERSION', '5.6');

if (version_compare(phpversion(), CLUEVO_REQUIRED_PHP_VERSION) <= 0) {
  define("PHP_VERSION_OUTDATED", true);
} else {
  define("PHP_VERSION_OUTDATED", false);
}

add_action('init', 'cluevo_load_plugin_textdomain');
function cluevo_load_plugin_textdomain()
{
  $moFile = WP_LANG_DIR . '/' . CLUEVO_TEXT_DOMAIN . '/' . CLUEVO_TEXT_DOMAIN . '-' . get_locale() . '.mo';

  load_textdomain(CLUEVO_TEXT_DOMAIN, $moFile);
  $result = load_plugin_textdomain(CLUEVO_TEXT_DOMAIN); //, false, dirname(plugin_basename(__FILE__)) . '/lang/');
  if (!$result) {
    $locale = get_locale();
    if (strtolower(substr($locale, 0, 2)) == 'de') {
      $moFile = plugin_dir_path(__FILE__) . '/lang/cluevo-de_DE.mo';
    } else {
      if (file_exists(plugin_dir_path(__FILE__) . '/lang/cluevo-' . $locale)) {
      } else {
        $moFile = plugin_dir_path(__FILE__) . '/lang/cluevo-en.mo';
      }
    }
    $dir = plugin_dir_path(__FILE__);
    load_textdomain(CLUEVO_TEXT_DOMAIN, $moFile);
  }
}

if (PHP_VERSION_OUTDATED === false) {

  define("CLUEVO_ACTIVE", true);
  define("CLUEVO_DEV_MODE", false);

  require_once('classes/class.leaf.inc.php');
  require_once('classes/class.tree.inc.php');
  require_once('classes/class.cluevo.inc.php');
  require_once('classes/class.item.inc.php');
  require_once('classes/class.user.inc.php');
  require_once('classes/class.group.inc.php');
  require_once('classes/class.competence.inc.php');
  require_once('classes/class.competence_area.inc.php');
  require_once('classes/class.acl.inc.php');
  require_once('classes/class.permission.inc.php');
  require_once('admin-views/class.db_upgrade.inc.php');

  require_once('functions/functions.inc.php');
  require_once('functions/functions.deprecated.inc.php');
  require_once('functions/functions.core.inc.php');
  require_once('functions/functions.core.frontend.inc.php');
  require_once('functions/functions.module-management.inc.php');
  require_once('functions/functions.users.inc.php');
  require_once('functions/functions.permissions.inc.php');
  require_once('functions/functions.tree.inc.php');
  require_once('functions/functions.metadata.inc.php');
  require_once('functions/functions.progress.inc.php');
  require_once('functions/functions.competence.inc.php');
  require_once('functions/functions.user-profile.inc.php');
  require_once('functions/functions.utilities.inc.php');
  require_once('functions/functions.core.admin.inc.php');
  require_once('install/plugin-activate.inc.php');  // contains functions that are used then the plugin is activated, like db table creation
  require_once('install/plugin-uninstall.inc.php');
  require_once 'rest/rest-api.php';

  register_activation_hook(__FILE__, 'cluevo_plugin_install');  // Creates database tables
  register_activation_hook(__FILE__, 'cluevo_flush_rewrite_rules');
  register_activation_hook(__FILE__, 'cluevo_create_lms_pages');  // Creates posts for frontend pages
  register_activation_hook(__FILE__, 'cluevo_create_directories');  // Creates directories needed for module storage
  register_activation_hook(__FILE__, 'cluevo_create_module_archive_htaccess');  // Creates .htaccess to protect module zips from external access
  register_activation_hook(__FILE__, 'cluevo_create_cluevo_uploads_htaccess');  // Creates .htaccess to protect the uploads directory from rogue php files
  register_uninstall_hook(__FILE__, 'cluevo_plugin_uninstall');

  add_action('init', 'cluevo_create_metadata_post_type');  // Register metadata post type
  add_action('init', 'cluevo_create_lms_page_post_type');  // Register lms pages posts type
  add_action('init', 'cluevo_meta_taxonomy_init');  // init metadata taxonomy
  add_action('init', 'cluevo_set_user_last_seen');  // Sets the last seen timestamp for a user
  add_action('init', 'cluevo_init_shortcodes');
  add_action('wp', 'cluevo_redir_single_tree_index');

  add_action('wp_enqueue_scripts', 'cluevo_scorm_plugin_scripts');  // Frontend scorm javascripts
  add_action("save_post_" . CLUEVO_METADATA_POST_TYPE, "cluevo_add_change_date_to_post", 10, 3);

  add_filter('single_template', 'cluevo_page_template');  // Display cluevo templates

  add_action('wp_enqueue_scripts', 'cluevo_enque_theme_files');  // Frontend styles

  add_action('user_register', 'cluevo_create_lms_user_on_wp_registration');
  add_filter('body_class', 'cluevo_remove_sidebar_class', 20);

  add_action('cluevo_module_icon_pdf', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_oembed', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_googledocs', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_scorm', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_scorm2004', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_video', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_module_icon_audio', 'cluevo_display_module_icon', 10, 1);
  add_action('cluevo_display_module', 'cluevo_display_media_module');
  add_action('cluevo_display_module', 'cluevo_display_scorm_module');
  add_action('cluevo_display_module', 'cluevo_display_pdf_module');
  add_action('cluevo_enqueue_module_scripts', 'cluevo_enqueue_module_scripts');
  add_action('cluevo_save_module_progress_video', 'cluevo_save_media_module_progress');
  add_action('cluevo_save_module_progress_audio', 'cluevo_save_media_module_progress');
  add_action('cluevo_save_module_progress_pdf', 'cluevo_save_media_module_progress');
  // add_action('cluevo_part_lms_item_meta', 'cluevo_add_pdf_download_icon', 10, 1);

  add_action('wp_enqueue_scripts', 'cluevo_load_frontend_dashicons');
  add_action("wp_ajax_cluevo-rate-module", "cluevo_rate_module");
  add_action("delete_user", "cluevo_cleanup_after_user_delete", 10, 1);
  add_action("cluevo_user_cleared_module_first_time", "cluevo_send_first_clear_notifications", 10, 1);

  add_filter('cluevo_output_module_type', 'cluevo_output_module_type');
  add_filter('cluevo_module_success_status_name', 'cluevo_module_success_status_name');
  add_filter('cluevo_module_completion_status_name', 'cluevo_module_completion_status_name');
  add_filter('cluevo_module_lesson_status_name', 'cluevo_module_lesson_status_name');

  // add_action('cluevo_render_tree_index', 'cluevo_render_tree_index');
  // add_action('cluevo_render_tree_tile', 'cluevo_render_tree_tile', 10, 1);

  if (is_admin()) {
    require_once('admin-views/plugin-settings-page-general-settings.php');
    require_once('admin-views/plugin-settings-page-lms.php');
    require_once('admin-views/plugin-settings-page-reports.php');
    require_once('admin-views/plugin-settings-page-competence.php');
    require_once('admin-views/plugin-settings-page-users.php');
    require_once('admin-views/class.module-ratings-page.php');
    require_once('functions/functions.site-report.inc.php');
    add_action('after_setup_theme', 'cluevo_enable_post_features');

    // Menu
    add_action("admin_menu", "cluevo_init_menu_items");  // Adds menu entries in admin area
    add_action("admin_menu", "cluevo_init_menu_items_at_end", 90);  // Adds menu entries in admin area
    add_action("admin_init", "cluevo_link_to_extension_page");
    add_action("admin_init", "cluevo_init_admin_styles");  // Admin styles
    add_action("admin_init", "cluevo_init_admin_scripts");
    add_action("admin_init", "cluevo_init_module_download");  // Module downloads
    add_action("admin_init", "cluevo_init_module_zip");  // zip modules
    add_action("admin_init", "cluevo_delete_module_zip");  // delete module zip
    add_action("admin_init", "cluevo_init_group_cache");
    add_action("admin_init", "cluevo_handle_progress_export");
    add_action("admin_init", "cluevo_handle_scorm_parm_export");
    add_action("admin_init", "cluevo_add_report_extension_tabs");
    add_filter("display_post_states", 'cluevo_cluevo_page_post_state', 10, 2);

    add_action('admin_notices', 'cluevo_display_db_upgrade_result');
    add_action('admin_notices', 'cluevo_display_db_upgrade_notice');

    add_action('upgrader_process_complete', 'cluevo_plugin_updated', 10, 2);
    add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'cluevo_add_plugin_update_message', 10, 2);
    // add_action('after_plugin_row_' . plugin_basename(__FILE__), 'cluevo_add_after_plugin_row', 10, 3);
    add_filter('extra_plugin_headers', 'add_cluevo_plugin_headers');
    add_filter('plugin_row_meta', 'add_cluevo_plugin_meta', 10, 2);
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_cluevo_plugin_action_links', 10, 1);
    add_filter('page_row_actions', 'cluevo_remove_custom_post_type_actions', 10, 2);
    add_filter('bulk_actions-edit-' . CLUEVO_METADATA_POST_TYPE, 'cluevo_remove_custom_post_type_bulk_actions');

    add_action('in_admin_header', 'cluevo_add_help_tab');
    add_action('load-edit.php', 'cluevo_add_help_tab');
    add_action('load-post.php', 'cluevo_add_help_tab');

    add_action('wp_ajax_cluevo-install-pending-module', 'cluevo_ajax_install_pending_module');
    add_action('wp_ajax_cluevo-delete-pending-module', 'cluevo_ajax_delete_pending_module');
    add_action('wp_ajax_toggle-progress-credit', 'cluevo_ajax_toggle_credit');
    add_action('wp_ajax_cluevo-get-progress-entry', 'cluevo_ajax_get_progress_entry');
    add_action('wp_ajax_cluevo-update-progress-entry', 'cluevo_ajax_update_progress_entry');
    add_action('wp_ajax_cluevo-dismiss-notice', 'cluevo_dismiss_notice', 10, 1);

    add_action('cluevo_render_learning_structure_ui', 'cluevo_render_learning_structure_ui');
    add_action('cluevo_enqueue_lms_structure_js', 'cluevo_enqueue_lms_structure_js');
    add_action('cluevo_render_lms_modules_ui', 'cluevo_render_module_ui');
    add_action('cluevo_enqueue_lms_modules_ui_js', 'cluevo_enqueue_lms_modules_ui_js');
    add_action('cluevo_render_lms_page_tabs', 'cluevo_render_lms_module_ui_tab');
    add_action('cluevo_render_lms_page_tabs', 'cluevo_render_lms_structure_tab');
    add_action("update_option_lms-tree-new", "cluevo_redirect_on_tree_change", 10, 2);  // Needed for learning structure admin page, redirects to the tree page where the selected tree is loaded

    add_filter('cluevo_output_module_rating', 'cluevo_output_admin_module_rating');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_close_button_setting');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_empty_setting');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_is_link_setting');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_limited_attempts');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_allow_pdf_download');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_tags');
    add_action('cluevo_tree_item_settings', 'cluevo_add_tree_item_complete_notifications');
    add_action('cluevo_user_module_progress_updated', 'cluevo_add_completed_module_to_list');

    add_action('cluevo_register_module_types', 'cluevo_register_default_module_types', 0);
    add_filter('cluevo_check_item_access', 'cluevo_check_item_access');

    add_action('cluevo_install_pending_module_scorm2004', 'cluevo_install_pending_scorm_module', 10, 1);
    add_action('cluevo_install_pending_module_audio', 'cluevo_install_pending_audio_module', 10, 1);
    add_action('cluevo_install_pending_module_video', 'cluevo_install_pending_video_module', 10, 1);
    add_action('cluevo_install_pending_module_pdf', 'cluevo_install_pending_pdf_module', 10, 1);
    add_action('cluevo_register_general_settings', 'cluevo_register_extension_settings');
    add_action('admin_post_cluevo-save-tree', 'cluevo_handle_tree_save');
    add_action('admin_post_cluevo-run-db-upgrade', 'CluevoDatabaseUpdatePage::handle');

    if (!empty($_GET["cluevo-update-db"])) {
      $curDatabaseVersion = get_option(CLUEVO_DB_VERSION_OPT_KEY);
      if ($curDatabaseVersion != CLUEVO_PLUGIN_DB_VERSION) {
        cluevo_create_database();
        update_option('cluevo-display-db-update-result', true);
      }
    }
  }
} else {
  add_action('admin_notices', 'cluevo_php_compat_notice');
}
