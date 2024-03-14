<?php
require_once(plugin_dir_path(__DIR__) . "admin-views/class.report_tab.inc.php");
require_once(plugin_dir_path(__DIR__) . "admin-views/class.progress_tab.inc.php");
require_once(plugin_dir_path(__DIR__) . "admin-views/class.scorm_parms_tab.inc.php");

/**
 * Returns possible filter values for progress entries
 *
 * @param string $strFieldValue
 * @param string $strFieldLabel
 * @param mixed $args (optional) Possible keys: user_id, attempt_id, success_status, completion_status, lesson_status, module_id
 *
 * @return array|null
 */
function cluevo_get_progress_filter_content($strFieldValue, $strFieldLabel, $args = [])
{
  global $wpdb;
  $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $userTable = $wpdb->users;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

  if (strpos($strFieldLabel, ".") === false)
    $strFieldLabel = "p.$strFieldLabel";

  $sql = "SELECT  p.$strFieldValue AS value, $strFieldLabel AS label
      FROM $progressTable p
      INNER JOIN $userTable u
        ON p.user_id = u.ID
      INNER JOIN $moduleTable m

        ON p.module_id = m.module_id";

  $valid = ["user_id", "attempt_id", "success_status", "completion_status", "lesson_status", "module_id"];
  $where = [];
  $parms = [];

  foreach ($args as $arg => $value) {
    if ($arg == $strFieldValue)
      continue;

    if (in_array($arg, $valid) && (!empty($value) || ($arg == "attempt_id" && $value > -1))) {
      if (empty($parms))
        $sql .= " WHERE ";

      if (strpos($arg, ".") === false)
        $where[] = "p.$arg = %s";
      else
        $where[] = "$arg = %s";
      $parms[] = $value;
    }
  }

  $sql .= implode(" AND ", $where);
  $sql .= " GROUP BY p.$strFieldValue";

  if (!empty($parms)) {
    return $wpdb->get_results(
      $wpdb->prepare($sql, $parms)
    );
  }

  return $wpdb->get_results($sql);
}

/**
 * Retrieves scorm parameters from the database
 *
 * @param array $args (optional) Possible keys: module_id, user_id, attempt_id, parameter
 * @param int $intPage (optional)
 * @param int $intPerPage (optional)
 *
 * @return array|null
 */
function cluevo_get_parameters($args = [], $intPage = 0, $intPerPage = null)
{
  global $wpdb;
  $parmTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
  $userTable = $wpdb->users;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

  $sqlFields = "p.*, LEFT(p.value, 50) AS value, m.module_name, u.display_name, u.user_nicename, u.user_email, user_login";
  $sql = "SELECT $sqlFields
      FROM $parmTable p
      INNER JOIN $moduleTable m
        ON p.module_id = m.module_id
      INNER JOIN $userTable u

        ON p.user_id = u.ID";

  $valid = ["module_id", "user_id", "attempt_id", "parameter"];
  $where = [];
  $parms = [];
  foreach ($args as $arg => $value) {
    if (in_array($arg, $valid) && (!empty($value) || ($arg == "attempt_id" && $value > -1))) {
      if (empty($parms))
        $sql .= " WHERE ";

      $where[] = "p.$arg = %s";
      $parms[] = $value;
    }
  }

  $sql .= implode(" AND ", $where);

  if (!empty($intPerPage)) {
    $limit = $intPage * $intPerPage;
    $sql .= " LIMIT $limit, $intPerPage";
  }

  if (!empty($parms)) {
    $result = $wpdb->get_results(
      $wpdb->prepare($sql, $parms)
    );
  } else {
    $result = $wpdb->get_results($sql);
  }

  return $result;
}

function cluevo_render_reports_page()
{
  $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alphanum_dash($_GET["tab"]) : CLUEVO_ADMIN_TAB_REPORTS_MAIN;
  $out  = '<div class="wrap cluevo-admin-page-container">';
  $out .= '  <h1 class="cluevo-admin-page-title-container">';
  $out .= '    <div><?php esc_html_e("Reporting", "cluevo"); ?></div>';
  $out .= '    <img class="plugin-logo" src="' .  esc_url(plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)), ['http', 'https']) . '" />';
  $out .= '  </h1>';
  $out .= '  <div class="cluevo-admin-page-content-container">';
  $out .= '    <h2 class="nav-tab-wrapper cluevo">';
  ob_start();
  do_action('cluevo_output_reports_tabs');
  $out .= ob_get_clean();
  $out .= '    </h2>';
  if (!empty($active_tab)) {
    if (has_action("cluevo_render_report_tab_{$active_tab}")) {
      ob_start();
      do_action("cluevo_render_report_tab_{$active_tab}");
      $out .= ob_get_clean();
    } else {
      ob_start();
      cluevo_render_reports_tab_reports();
      $out .= ob_get_clean();
    }
  } else {
    ob_start();
    cluevo_render_reports_tab_reports();
    $out .= ob_get_clean();
  }
  $out .= '</div>';
  $allowed = wp_kses_allowed_html("post");
  $allowed["input"] = ["type" => 1, "data-*" => 1, "class" => 1, "value" => 1, "name" => 1, "list" => 1, "checked" => 1, "disabled" => 1];
  $allowed["form"] = ["method" => 1, "action" => 1, "class" => 1, "id" => 1];
  $allowed["select"] = ["value" => 1, "class" => 1, "id" => 1, "name" => 1, "disabled" => 1];
  $allowed["datalist"] = ["id" => 1];
  $allowed["option"] = ["value" => 1, "data-*" => 1, "selected" => 1, "disabled" => 1];
  $allowed["svg"] = ["value" => 1, "data-*" => 1, "selected" => 1, "disabled" => 1];
  $allowed["canvas"] = ["id" => 1, "width" => 1, "height" => 1, "class" => 1];
  echo $out;
  // echo wp_kses($out, $allowed);
}

function cluevo_render_reports_tab_reports()
{
  CluevoReportTab::display();
}

function cluevo_render_reports_tab_progress()
{
  CluevoProgressTab::display();
  echo '<div id="cluevo-progress-editor"></div>';
  wp_register_script(
    "vue-js",
    plugins_url("/js/vue.min.js", plugin_dir_path(__FILE__)),
    "",
    CLUEVO_VERSION,
    false
  );
  wp_enqueue_script('vue-js');
  wp_register_script(
    'cluevo-progress-editor',
    plugins_url(
      '/js/progress-editor.js',
      plugin_dir_path(__FILE__)
    ),
    ["vue-js"],
    CLUEVO_VERSION,
    true
  );
  wp_localize_script(
    'cluevo-progress-editor',
    'cluevoProgressSettings',
    [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('cluevo-progress---'),
    ]
  );
  if (function_exists('wp_set_script_translations')) {
    wp_set_script_translations('cluevo-progress-editor', 'cluevo', plugin_dir_path(__DIR__) . 'lang');
  }
  wp_enqueue_script("cluevo-progress-editor");
}

function cluevo_render_reports_tab_scorm()
{
  CluevoScormParmsTab::display();
}

add_action('cluevo_output_reports_tabs', function () {
  $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alphanum_dash($_GET["tab"]) : CLUEVO_ADMIN_TAB_REPORTS_MAIN;
  $html = '<a href="';
  $html .= esc_url(admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_REPORTS . "&tab=" . CLUEVO_ADMIN_TAB_REPORTS_MAIN), ['http', 'https']);
  $html .= '"';
  $html .= '  class="nav-tab';
  $html .= ($active_tab == CLUEVO_ADMIN_TAB_REPORTS_MAIN) ? ' nav-tab-active' : '';
  $html .= '">' . esc_html__("Reports", "cluevo") . '</a>';
  echo wp_kses($html, wp_kses_allowed_html("post"));
});

add_action('cluevo_output_reports_tabs', function () {
  $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alphanum_dash($_GET["tab"]) : CLUEVO_ADMIN_TAB_REPORTS_MAIN;
  $html = '<a href="';
  $html .= esc_url(admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_REPORTS . "&tab=" . CLUEVO_ADMIN_TAB_REPORTS_PROGRESS), ['http', 'https']);
  $html .= '"';
  $html .= '  class="nav-tab';
  $html .= ($active_tab == CLUEVO_ADMIN_TAB_REPORTS_PROGRESS) ? ' nav-tab-active' : '';
  $html .= '">' . esc_html__("Progress", "cluevo") . '</a>';
  echo wp_kses($html, wp_kses_allowed_html("post"));
});

add_action('cluevo_output_reports_tabs', function () {
  $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alphanum_dash($_GET["tab"]) : CLUEVO_ADMIN_TAB_REPORTS_MAIN;
  $html = '<a href="';
  $html .= esc_url(admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_REPORTS . "&tab=" . CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS), ['http', 'https']);
  $html .= '"';
  $html .= '  class="nav-tab';
  $html .= ($active_tab == CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS) ? ' nav-tab-active' : '';
  $html .= '">' . esc_html__("SCORM Parameters", "cluevo") . '</a>';
  echo wp_kses($html, wp_kses_allowed_html("post"));
});
add_action('cluevo_progress_report_page_init', 'CluevoReportTab::init_actions', 1);
add_action('cluevo_render_report_tab_scorm', 'cluevo_render_reports_tab_scorm');
add_action('cluevo_render_report_tab_progress', 'cluevo_render_reports_tab_progress');
add_action('cluevo_render_report_tab_reports', 'cluevo_render_reports_tab_reports');

add_action("load-cluevo_page_cluevo-reporting", "CluevoProgressTab::add_screen_options");
add_filter("screen_settings", "CluevoProgressTab::render_screen_options", 10, 2);
add_filter("set_screen_option_cluevo_reports_progress_settings", "CluevoProgressTab::set_options", 10, 3);

add_action("load-cluevo_page_cluevo-reporting", "CluevoScormParmsTab::add_screen_options");
add_filter("screen_settings", "CluevoScormParmsTab::render_screen_options", 10, 2);
add_filter("set_screen_option_cluevo_reports_scorm_parms_settings", "CluevoScormParmsTab::set_options", 10, 3);

add_action("load-cluevo_page_cluevo-reporting", "CluevoReportTab::add_screen_options");
add_filter("screen_settings", "CluevoReportTab::render_screen_options", 10, 2);
add_filter("set_screen_option_cluevo_reports_main_settings", "CluevoReportTab::set_options", 10, 3);
?>
