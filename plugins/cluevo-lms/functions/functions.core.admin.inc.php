<?php
if (!defined("CLUEVO_ACTIVE")) exit;

/**
 * Initialize submenu pages
 *
 */
function cluevo_init_menu_items()
{
  add_menu_page(
    __("CLUEVO", "cluevo"),
    __("CLUEVO", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_LMS,
    "cluevo_render_lms_page",
    "dashicons-welcome-learn-more"
  );

  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Learning Management", "cluevo"),
    __("Learning Management", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_LMS,
    "cluevo_render_lms_page"
  );

  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("User Management", "cluevo"),
    __("User Management", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_USER_MANAGEMENT,
    "cluevo_render_user_management_page"
  );

  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Reporting", "cluevo"),
    __("Reporting", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_REPORTS,
    "cluevo_render_reports_page"
  );

  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Competence", "cluevo"),
    __("Competence", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_COMPETENCE,
    "cluevo_render_competence_areas_page"
  );

  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("LMS Posts", "cluevo"),
    __("LMS Posts", "cluevo"),
    "manage_options",
    "edit.php?post_type=" . CLUEVO_METADATA_POST_TYPE
  );
}

function cluevo_init_menu_items_at_end()
{
  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Database", "cluevo"),
    __("Database", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_DATABASE,
    "CluevoDatabaseUpdatePage::render",
    98
  );
  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Settings", "cluevo"),
    __("Settings", "cluevo"),
    "manage_options",
    CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS,
    "CluevoSettingsPage::render",
    99
  );
  add_submenu_page(
    CLUEVO_ADMIN_PAGE_LMS,
    __("Extensions", "cluevo"),
    __("Extensions", "cluevo"),
    "manage_options",
    'cluevo-ext-page',
    "cluevo_link_to_extension_page",
    100
  );
}

function cluevo_link_to_extension_page()
{
  if (!empty($_GET["page"]) && $_GET["page"] === 'cluevo-ext-page') {
    wp_redirect("https://wp-lms.cluevo.at/pricing/");
    exit;
  }
}

/**
 * Register admin styles
 *
 * Also removes emojis from admin pages
 *
 */
function cluevo_init_admin_styles()
{
  wp_register_style('lms-admin-css', plugins_url('/styles/admin.css', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION);  // admin page styles
  wp_enqueue_style('lms-admin-css');
}

/**
 * Used to let admins download modules
 *
 */
function cluevo_init_module_download()
{
  if (!current_user_can("administrator")) return;

  $page = (!empty($_GET["page"])) ? cluevo_strip_non_alphanum_dash($_GET["page"]) : null;
  $tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : null;
  $dl = (!empty($_GET["dl"]) && is_numeric($_GET["dl"])) ? (int)$_GET["dl"] : null;
  if (empty($dl)) return;

  if (!empty($page) && !empty($tab)) {
    if ($page === CLUEVO_ADMIN_PAGE_LMS && $tab === CLUEVO_ADMIN_TAB_LMS_MODULES) {
      if (!empty($dl) && is_numeric($dl)) {
        $module = cluevo_get_module($dl);
        if (!empty($module->module_zip)) {
          $file = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $module->module_zip;
          if (file_exists($file)) {
            $mime = wp_check_filetype($file);

            header('Content-Type: ' . $mime["type"]); // always send this
            header('Content-Length: ' . filesize($file));
            $filename = sanitize_file_name($module->module_name) . ".zip";
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $last_modified = gmdate('D, d M Y H:i:s', filemtime($file));
            $etag = '"' . md5($last_modified) . '"';
            header("Last-Modified: $last_modified GMT");
            header('ETag: ' . $etag);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 100000000) . ' GMT');
            readfile($file);
            $loc = remove_query_arg("dl");
            header("Location: $loc");
            exit;
          } else {
            header("HTTP/1.0 404 Not Found");
            echo "File not found.\n";
            die();
          }
        } else {
          header("HTTP/1.0 404 Not Found");
          echo "File not found.\n";
          die();
        }
      }
    }
  }
}

function cluevo_init_module_zip()
{
  if (!current_user_can("administrator")) return;

  $page = (!empty($_GET["page"])) ? cluevo_strip_non_alphanum_dash($_GET["page"]) : null;
  $tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : null;
  $id  = (!empty($_GET["zip"]) && is_numeric($_GET["zip"])) ? (int)$_GET["zip"] : null;
  if (empty($id)) return;

  if (!empty($page) && !empty($tab)) {
    if ($page === CLUEVO_ADMIN_PAGE_LMS && $tab === CLUEVO_ADMIN_TAB_LMS_MODULES) {
      if (!empty($id) && is_numeric($id)) {
        $module = cluevo_get_module($id);
        $dir = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $module->module_dir);
        $zipDir = sanitize_title($module->type_name) . "/" . sanitize_file_name($module->module_id . ".zip");
        $zip = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'), $zipDir);
        cluevo_zip_directory($dir, $zip);
        global $wpdb;
        $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
        $wpdb->query(
          $wpdb->prepare("UPDATE $table SET module_zip = %s WHERE module_id = %d", [$zipDir, $id])
        );
        $loc = remove_query_arg("zip");
        header("Location: $loc");
        exit();
      }
    }
  }
}

function cluevo_zip_directory($strSource, $strDest)
{
  $zip = new ZipArchive();
  $zip->open($strDest, ZipArchive::CREATE | ZipArchive::OVERWRITE);

  if (!file_exists(dirname($strDest))) {
    @mkdir(dirname($strDest), 0777, true);
  }

  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($strSource),
    RecursiveIteratorIterator::LEAVES_ONLY
  );

  foreach ($files as $name => $file) {
    if (!$file->isDir()) {
      $filePath = $file->getRealPath();
      $relativePath = substr($filePath, strlen($strSource) + 1);
      $zip->addFile($filePath, $relativePath);
    }
  }

  $zip->close();
}

function cluevo_delete_module_zip()
{
  $page = (!empty($_GET["page"])) ? cluevo_strip_non_alphanum_dash($_GET["page"]) : null;
  $tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : null;
  $id  = (!empty($_GET["del-zip"]) && is_numeric($_GET["del-zip"])) ? (int)$_GET["del-zip"] : null;
  if (empty($id)) return;

  $loc = remove_query_arg("del-zip");
  if (!empty($page) && !empty($tab)) {
    if ($page === CLUEVO_ADMIN_PAGE_LMS && $tab === CLUEVO_ADMIN_TAB_LMS_MODULES) {
      $id  = (!empty($_GET["del-zip"]) && is_numeric($_GET["del-zip"])) ? (int)$_GET["del-zip"] : null;
      if (!empty($id) && is_numeric($id)) {
        $module = cluevo_get_module($id);
        if (!empty($module->module_zip)) {
          $file = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'), $module->module_zip);
          if (file_exists($file)) {
            $result = @unlink($file);
            if ($result) {
              global $wpdb;
              $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
              $wpdb->query(
                $wpdb->prepare("UPDATE $table SET module_zip = null WHERE module_id = %d", [$id])
              );
            } else {
              $loc = add_query_arg('cluevo-error', 'failed-to-delete-module-zip');
            }
          }
        }
        header("Location: $loc");
        exit();
      }
    }
  }
}

function cluevo_create_lms_user_on_wp_registration($intUserId)
{
  $auto = get_option("cluevo-auto-add-new-users", "on");
  if ($auto === "on") {
    $result = cluevo_make_lms_user($intUserId);
    if ($result) {
      cluevo_add_users_to_group($intUserId, CLUEVO_DEFAULT_GROUP_USER);
    }
  }
}

function cluevo_inject_admin_style_overrides()
{
  wp_register_style('admin-overrides-css', plugins_url('/styles/admin-overrides.css', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION);
  wp_enqueue_style('admin-overrides-css');
}

function cluevo_enable_post_features()
{
  $posts = [
    CLUEVO_METADATA_POST_TYPE_COMPETENCE,
    CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA,
    CLUEVO_METADATA_POST_TYPE_SCORM_MODULE,
    CLUEVO_METADATA_POST_TYPE
  ];
  add_theme_support('post-thumbnails', $posts);
}

function cluevo_add_help_tab()
{
  $screen = get_current_screen();

  if ($screen->post_type != CLUEVO_METADATA_POST_TYPE && strpos($screen->base, 'cluevo') === false)
    return;

  $args = [
    'id'      => 'cluevo-help',
    'title'   => __("CLUEVO Posts / Pages", "cluevo"),
    'callback' => 'cluevo_display_help_tab_posts'
  ];

  $screen->add_help_tab($args);
  $screen->add_help_tab([
    'id' => 'cluevo-lms',
    'title' => __("LMS", "cluevo"),
    'callback' => 'cluevo_display_help_tab_lms'
  ]);
  $screen->add_help_tab([
    'id' => 'cluevo-competence',
    'title' => __("Competence", "cluevo"),
    'callback' => 'cluevo_display_help_tab_competence'
  ]);
  $screen->add_help_tab([
    'id' => 'cluevo-reports',
    'title' => __("Reporting", "cluevo"),
    'callback' => 'cluevo_display_help_tab_reports'
  ]);
  $screen->add_help_tab([
    'id' => 'cluevo-permissions',
    'title' => __("Users / Permissions", "cluevo"),
    'callback' => 'cluevo_display_help_tab_permissions'
  ]);
  $screen->add_help_tab([
    'id' => 'cluevo-settings',
    'title' => __("Settings", "cluevo"),
    'callback' => 'cluevo_display_help_tab_settings'
  ]);
  $screen->add_help_tab([
    'id' => 'cluevo-settings',
    'title' => __("Shortcodes", "cluevo"),
    'callback' => 'cluevo_display_help_tab_shortcodes'
  ]);

  apply_filters('cluevo_append_help_tab', $screen);
}

function cluevo_plugin_updated($upgrade, $opt)
{
  $name = plugin_basename(__FILE__);
  if ($opt['action'] == 'update' && $opt['type'] == 'plugin' && isset($opt['plugins'])) {
    foreach ($opt['plugins'] as $plugin) {
      if ($plugin == $name) {
        cluevo_plugin_install();
        break;
      }
    }
  }
}

function cluevo_add_plugin_update_message($data, $response)
{
  $plugins = cluevo_get_extensions();
  $notices = [];
  $curVersion = get_file_data(__FILE__, array('Version'))[0];
  $newVersion = $data["new_version"];
  foreach ($plugins as $file => $plugin) {
    $least = (!empty($plugin["CLUEVO requires at least"])) ? $plugin["CLUEVO requires at least"] : __("Unknown", "cluevo");
    $tested = (!empty($plugin["CLUEVO tested up to"])) ? $plugin["CLUEVO tested up to"] : __("Unknown", "cluevo");
    $notices[$plugin["Name"]] = ["tested" => false, "compatible" => false, "tested_version" => $tested, "compatible_version" => $least];
    if (!empty($least)) {
      $result = version_compare($newVersion, $least);
      switch ($result) {
        case -1:
          $notices[$plugin["Name"]]["compatible"] = false;
          break;
        case 0:
          $notices[$plugin["Name"]]["compatible"] = true;
          break;
        case 1:
          $notices[$plugin["Name"]]["compatible"] = false;
          break;
      }
    }
    if (!empty($tested)) {
      $result = version_compare($newVersion, $tested);
      switch ($result) {
        case -1:
          $notices[$plugin["Name"]]["tested"] = false;
          break;
        case 0:
          $notices[$plugin["Name"]]["tested"] = true;
          break;
        case 1:
          $notices[$plugin["Name"]]["tested"] = false;
          break;
      }
    }
  }
  if (!empty($notices)) {
    $out =  "<div class=\"cluevo-plugin-update-info\">";
    $out .=  "<div class=\"cluevo-update-compat-text\">" . esc_html__("You have one or more CLUEVO extensions installed. Please check their compatibility before updating.", "cluevo") . "</div>";
    $out .=  "<table class=\"cluevo-ext-update-info\" cellspacing=\"0\">";
    $out .=  "<tr><th>" . __("Plugin", "cluevo") . "</th><th>" . __("tested", "cluevo") . "</th><th>" . __("compatible", "cluevo") . "</th></tr>";
    foreach ($notices as $plugin => $msg) {
      $comp = ($msg["compatible"] === true) ? "yes" : "no";
      $test = ($msg["tested"] === true) ? "yes" : "no";
      $out .=  "<tr><td>$plugin</td><td><span class=\"dashicons dashicons-" . esc_attr($test) . "\"></span>(" . $msg["tested_version"] . ")</td><td><span class=\"dashicons dashicons-" . esc_attr($comp) . "\"></span></td></tr>";
    }
    $out .=  "</table>\n";
    $out .=  "</div>";
    echo "</p>" . wp_kses_post($out) . "<p class=\"dummy\">";
  }
}

function cluevo_add_after_plugin_row($file, $data, $status)
{
  $plugins = cluevo_get_extensions();
  $notices = [];
  $curVersion = $data["Version"];

  foreach ($plugins as $file => $plugin) {
    $tested = (!empty($plugin["CLUEVO tested up to"])) ? $plugin["CLUEVO tested up to"] : __("Unknown", "cluevo");
    $notices[$plugin["Name"]] = ["tested" => false, "compatible" => false, "tested_version" => $tested];
    if (!empty($tested)) {
      $result = version_compare($curVersion, $tested);
      switch ($result) {
        case -1:
          $notices[$plugin["Name"]]["tested"] = false;
          break;
        case 0:
          $notices[$plugin["Name"]]["tested"] = true;
          break;
        case 1:
          $notices[$plugin["Name"]]["tested"] = false;
          break;
      }
    }
  }
  if (!empty($notices)) {
    $display = false;
    $statusClass = "active";
    if ($data["new_version"] && version_compare($curVersion, $data["new_version"]) != 0) {
      $statusClass .= " update";
    }
    $out = '<tr class="' . $statusClass . '"><th class="check-column"></th><td colspan="3"><div class="cluevo-plugin-update-info notice inline notice-warning notice-alt">';
    $out .=  "<details><summary class=\"cluevo-update-compat-text\">" . esc_html__("One or more of your CLUEVO extensions are either not compatible or have not been tested with the currently installed CLUEVO LMS version. Please deactivate or update these extensions if you run into any issues.", "cluevo") . "</summary>";
    $out .=  "<table class=\"cluevo-ext-update-info\" cellspacing=\"0\">";
    $out .=  "<tr><th>" . __("Plugin", "cluevo") . "</th><th>" . __("tested", "cluevo") . "</th></tr>";
    foreach ($notices as $plugin => $msg) {
      if (!$msg["tested"]) {
        $display = true;
        $test = ($msg["tested"] === true) ? "yes" : "no";
        $out .=  "<tr><td>" . esc_html($plugin) . "</td><td><span class=\"dashicons dashicons-" . esc_attr($test) . "\"></span>(" . esc_html($msg["tested_version"]) . ")</td></tr>";
      }
    }
    $out .=  "</table>\n";
    $out .= '</details></td></tr>';
    if ($display) echo wp_kses($out, wp_kses_allowed_html("post"));
  }
}

function add_cluevo_plugin_headers($headers)
{
  if (!in_array('CLUEVO requires at least', $headers))
    $headers[] = 'CLUEVO requires at least';

  if (!in_array('CLUEVO tested up to', $headers))
    $headers[] = 'CLUEVO tested up to';

  return $headers;
}

function add_cluevo_plugin_meta($links, $file)
{
  if ($file !== "cluevo-lms/cluevo-lms.php") return $links;
  $meta = [
    "Get Premium" => "<a href=\"" . esc_url("https://wp-lms.cluevo.at/pricing/") . "\" style=\"font-weight: bold;\">" . __("Get Extensions", "cluevo") . "</a>"
  ];

  if (empty($links)) $links = [];

  return array_merge($links, $meta);
}

function add_cluevo_plugin_action_links($links)
{
  $actions = [
    "settings" => "<a href=\"" . esc_url(admin_url("admin.php?page=cluevo-settings")) . "\">" . __("Settings", "cluevo") . "</a>",
    "premium" => "<a href=\"" . esc_url("https://wp-lms.cluevo.at/pricing/") . "\" style=\"font-weight: bold;\">" . __("Get Premium", "cluevo") . "</a>"
  ];

  if (empty($links)) $links = [];

  return array_merge($actions, $links);
}

/**
 * Redirect to the new tree when the option is changed
 *
 * @param mixed $old
 * @param mixed $new
 */
function cluevo_redirect_on_tree_change($old, $new)
{
  wp_redirect("admin.php?page=" . CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE . "&tree_id=$new");
  exit;
}

function cluevo_init_admin_scripts()
{
  wp_register_script('cluevo-admin-common', plugins_url('/js/admin-common.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, true);
  wp_localize_script(
    'cluevo-admin-common',
    'cluevoWpCommonApiSettings',
    array(
      'root' => esc_url_raw(rest_url()),
      'nonce' => wp_create_nonce('wp_rest'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'noticeNonce' => wp_create_nonce('cluevo-notice---'),
    )
  );  // needed for ajax requests
  wp_enqueue_script('cluevo-admin-common');
}

function cluevo_php_compat_notice()
{
  echo '<div class="notice notice-error">
    <p>' . sprintf(__("CLUEVO LMS requires at least PHP version %s. Your PHP version: %s. Please update your PHP version to use this plugin.", "cluevo"), CLUEVO_REQUIRED_PHP_VERSION, phpversion()) . '</p>
    </div>';
}

function cluevo_add_change_date_to_post($intPostId, $post, $update)
{
  $time = time();
  update_post_meta($intPostId, "cluevo-last-change-date", $time);
  $path = get_post_meta($intPostId, "_cluevo-lms-meta-path", true);
  if (!empty($path)) {
    if (is_array($path)) {
      if (array_key_exists("id", $path)) {
        foreach ($path["id"] as $id) {
          $metaId = cluevo_get_metadata_id_from_item_id($id);
          if (!empty($metaId)) {
            update_post_meta($metaId, "cluevo-last-change-date", $time);
          }
        }
      }
    }
  }
}

function cluevo_init_group_cache()
{
  cluevo_turbo_get_groups();
}

function cluevo_ajax_toggle_credit()
{
  if (!current_user_can("administrator")) return;
  $params = json_decode(file_get_contents('php://input'), true);
  $userId = (int)$params["user_id"];
  $moduleId = (int)$params["module_id"];
  $attemptId = (int)$params["attempt_id"];
  if (empty($userId)) return;
  if (empty($moduleId)) return;

  $cur = cluevo_get_progress_entry($userId, $moduleId, $attemptId);
  if (empty($cur)) {
    wp_send_json(false);
    die();
  }

  $credit = null;
  if ($cur->credit === 'credit') {
    cluevo_set_progress_credit($userId, $moduleId, $attemptId, 0);
    $credit = 'no-credit';
  } else {
    cluevo_set_progress_credit($userId, $moduleId, $attemptId, 1);
    $credit = 'credit';
  }

  wp_send_json($credit);
  die();
}

function cluevo_ajax_get_progress_entry()
{
  $nonce_name = isset($_GET["cluevo-progress-nonce"]) ? sanitize_text_field($_GET["cluevo-progress-nonce"]) : "";
  $userId = (int)$_GET["user_id"];
  $moduleId = (int)$_GET["module_id"];
  $attemptId = (int)$_GET["attempt_id"];
  $nonce_action = "cluevo-progress---";

  if (!wp_verify_nonce($nonce_name, $nonce_action)) return;

  if (!current_user_can("administrator")) return;
  if (empty($userId)) return;
  if (empty($moduleId)) return;

  $cur = cluevo_get_progress_entry($userId, $moduleId, $attemptId);
  if (empty($cur)) {
    wp_send_json(false);
    die();
  }

  wp_send_json($cur);
  die();
}

function cluevo_ajax_update_progress_entry()
{
  $params = json_decode(file_get_contents('php://input'), true);
  $nonce_action = "cluevo-progress---";
  $nonce_name = isset($params["cluevo-progress-nonce"]) ? $params["cluevo-progress-nonce"] : "";

  if (!wp_verify_nonce($nonce_name, $nonce_action)) return;

  if (!current_user_can("administrator")) return;
  $userId = (!empty($params["user_id"])) ? $params["user_id"] : "";
  $moduleId = (!empty($params["module_id"])) ? $params["module_id"] : "";
  $attemptId = (!empty($params["attempt_id"])) ? $params["attempt_id"] : "";
  if (empty($userId)) return;
  if (empty($moduleId)) return;

  $cur = cluevo_get_progress_entry($userId, $moduleId, $attemptId);
  if (empty($cur)) {
    wp_send_json(false);
    die();
  }

  cluevo_update_module_progress_entry($userId, $moduleId, $attemptId, $params);
  $new = cluevo_get_progress_entry($userId, $moduleId, $attemptId);
  wp_send_json($new);
  die();
}

function cluevo_dismiss_notice()
{
  $nonce_name = isset($_POST["cluevo-notice-nonce"]) ? sanitize_text_field($_POST["cluevo-notice-nonce"]) : "";
  $nonce_action = "cluevo-notice---";
  $key = sanitize_key($_POST["cluevo-notice-key"]);

  if (!wp_verify_nonce($nonce_name, $nonce_action)) return;
  update_user_meta(get_current_user_id(), sanitize_key("cluevo-admin-notice-dismissed-{$key}"), true);
  wp_die(true);
}

function cluevo_handle_scorm_parm_export()
{
  if (!current_user_can("administrator")) return;
  if (empty($_GET["cluevo-export"])) return;
  if ($_GET["cluevo-export"] !== 'scorm') return;

  $userId = (!empty($_GET["user"]) && is_numeric($_GET["user"])) ? (int)$_GET["user"] : null;
  $moduleId = (!empty($_GET["module"]) && is_numeric($_GET["module"])) ? (int)$_GET["module"] : null;
  $attemptId = (isset($_GET["attempt"]) && is_numeric($_GET["attempt"]) && (int)$_GET["attempt"] >= 0) ? (int)$_GET["attempt"] : null;
  $parameter = (!empty($_GET["parameter"])) ? cluevo_strip_non_scorm_parm_chars($_GET["parameter"]) : null;
  $entries = cluevo_get_parameters(["module_id" => $moduleId, "user_id" => $userId, "attempt_id" => $attemptId, "parameter" => $parameter], null, null);
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=cluevo-scorm-parameters.csv');
  $out = fopen('php://output', 'w');
  if (!empty($entries)) {
    $head = $entries[0];
    $fields = [];
    foreach ($head as $prop => $value) {
      $fields[] = $prop;
    }
    fputcsv($out, $fields, ';');
  }
  foreach ($entries as $entry) {
    $fields = [];
    foreach ($entry as $value) {
      $fields[] = $value;
    }
    fputcsv($out, $fields, ';');
  }
  die();
}

function cluevo_handle_progress_export()
{
  if (!current_user_can("administrator")) return;
  if (empty($_GET["cluevo-export"])) return;
  if ($_GET["cluevo-export"] !== 'progress') return;

  $userId = (!empty($_GET["user"]) && is_numeric($_GET["user"])) ? (int)$_GET["user"] : null;
  $moduleId = (!empty($_GET["module"]) && is_numeric($_GET["module"])) ? (int)$_GET["module"] : null;
  $attemptId = (isset($_GET["attempt"]) && is_numeric($_GET["attempt"]) && (int)$_GET["attempt"] >= 0) ? (int)$_GET["attempt"] : null;
  $successStatus = (!empty($_GET["success-status"])) ? cluevo_strip_non_alpha($_GET["success-status"]) : null;
  $completionStatus = (!empty($_GET["completion-status"])) ? cluevo_strip_non_alpha($_GET["completion-status"]) : null;
  $lessonStatus = (!empty($_GET["lesson-status"])) ? cluevo_strip_non_alpha_blank($_GET["lesson-status"]) : null;

  $entries = cluevo_get_modules_progress_entries(["user_id" => $userId, "module_id" => $moduleId, "attempt_id" => $attemptId, "module_id" => $moduleId, "success_status" => $successStatus, "completion_status" => $completionStatus, "lesson_status" => $lessonStatus], null, null);
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=cluevo-progress.csv');
  $out = fopen('php://output', 'w');
  if (!empty($entries)) {
    $head = $entries[0];
    $fields = [];
    foreach ($head as $prop => $value) {
      $fields[] = $prop;
    }
    fputcsv($out, $fields, ';');
  }
  foreach ($entries as $entry) {
    $fields = [];
    foreach ($entry as $value) {
      $fields[] = $value;
    }
    fputcsv($out, $fields, ';');
  }
  die();
}

function cluevo_add_report_extension_tabs()
{
  if (!is_plugin_active('cluevo-lms-extension-user-learning-progress/cluevo-lms-extension-user-learning-progress.php')) {
    add_action('cluevo_output_reports_tabs', function () {
      echo "<a href=\"" . esc_url("https://wp-lms.cluevo.at/pricing/") . "\" class=\"nav-tab\"><span class=\"dashicons dashicons-external\"></span> " . esc_html__("User Learning Progress", "cluevo") . " <span class=\"cluevo-extension-badge\">" . esc_html__("Extension", "cluevo") . "</span></a>";
    }, 99);
  }
  if (!is_plugin_active('cluevo-lms-extension-quiz/cluevo-lms-extension-quiz.php')) {
    add_action('cluevo_output_reports_tabs', function () {
      echo "<a href=\"" . esc_url("https://wp-lms.cluevo.at/pricing/") . "\" class=\"nav-tab\"><span class=\"dashicons dashicons-external\"></span> " . esc_html__("Quiz", "cluevo") . " <span class=\"cluevo-extension-badge\">" . esc_html__("Extension", "cluevo") . "</span></a>";
    }, 99);
  }
}

function cluevo_remove_custom_post_type_actions($actions, $post)
{
  global $current_screen;
  if ($current_screen->post_type !== CLUEVO_METADATA_POST_TYPE) return $actions;
  unset($actions["trash"]);
  return $actions;
}

function cluevo_remove_custom_post_type_bulk_actions($actions)
{
  global $current_screen;
  if ($current_screen->post_type !== CLUEVO_METADATA_POST_TYPE) return $actions;
  unset($actions["trash"]);
  return $actions;
}

function cluevo_register_extension_settings()
{
  if (!is_plugin_active('cluevo-lms-extension-quiz/cluevo-lms-extension-quiz.php')) {
    cluevo_register_settings_section(
      "quiz",
      __("AI Quiz Maker", "cluevo"),
      "",
      [
        "title" => __("Extension", "cluevo"),
        "class" => "cluevo-pink"
      ]
    );
    add_action("cluevo_settings_tab_after_description-quiz", function () {
      include(plugin_dir_path(__DIR__) . 'extensions/quiz.php');
    });
  }
  // if (!is_plugin_active('cluevo-lms-extension-certificates/cluevo-lms-extension-certificates.php')) {
  //   cluevo_register_settings_section(
  //     "certs",
  //     __("Certificates", "cluevo"),
  //     "",
  //     [
  //       "title" => __("Extension", "cluevo"),
  //       "class" => "cluevo-pink"
  //     ]
  //   );
  //   add_action("cluevo_settings_tab_after_description-certs", function() {
  //     include(plugin_dir_path(__DIR__) . 'extensions/certs.php');
  //   });
  // }
}

function cluevo_handle_tree_save()
{
  if (!empty($_POST) && check_admin_referer('cluevo-save-tree', 'cluevo-save-tree-nonce')) {
    if (!isset($_POST["action"]) || !wp_verify_nonce($_POST['cluevo-save-tree-nonce'], 'cluevo-save-tree')) {
      die("invalid nonce");
    }
    if (!current_user_can("administrator")) {
      die("no admin");
    }
    $treeFlat = (!empty($_POST["lms-tree-flat"]) && json_decode(stripslashes($_POST["lms-tree-flat"]))) ? (string)$_POST["lms-tree-flat"] : null;
    if (!empty($treeFlat)) {
      cluevo_save_learning_structure($treeFlat);
    }
    $url = admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_LMS . "&tab=" . CLUEVO_ADMIN_TAB_LMS_STRUCTURE);
    header("Location: " . $url);
    exit;
  }
}

function cluevo_cluevo_page_post_state($post_states, $post)
{
  $indexPage = (int)get_option("cluevo-tree-index-page", null);
  if ($post->ID === $indexPage) {
    $post_states[] = __("CLUEVO LMS Index", "cluevo");
  }
  $userPage = (int)get_option("cluevo-user-profile-page", null);
  if ($post->ID === $userPage) {
    $post_states[] = __("CLUEVO User Page", "cluevo");
  }
  $loginPage = (int)get_option("cluevo-login-page", null);
  if ($post->ID === $loginPage) {
    $enabled = get_option("cluevo-login-enabled", "");
    if (empty($enabled)) {
      $post_states[] = __("CLUEVO Login Page (disabled)", "cluevo");
    } else {
      $post_states[] = __("CLUEVO Login Page", "cluevo");
    }
  }
  return $post_states;
}
