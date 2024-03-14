<?php

if (!class_exists('CluevoProgressTab')) {
  class CluevoProgressTab
  {
    private static $screen_options = [];
    private static $settings;
    private static $columns = [];
    private static $defaultArgs = [
      "user_id" => null,
      "username" => null,
      "module_id" => null,
      "module_name" => null,
      "attempt_id" => null,
      "success_status" => null,
      "tags" => null,
      "completion_status" => null,
      "lesson_status" => null,
      "page" => 0,
      "per_page" => 100,
      "active_tab" => null,
      "active_page" => null
    ];
    private static $fixedCols = ["module_name", "user"];
    private static $args = null;

    public static function display()
    {
      self::init();
      self::render();
    }

    public static function init()
    {
      $userId = (!empty($_GET["user"]) && is_numeric($_GET["user"])) ? (int)$_GET["user"] : null;
      $moduleId = (!empty($_GET["module"]) && is_numeric($_GET["module"])) ? (int)$_GET["module"] : null;
      $attemptId = (isset($_GET["attempt"]) && is_numeric($_GET["attempt"]) && (int)$_GET["attempt"] >= 0) ? (int)$_GET["attempt"] : null;
      $successStatus = (!empty($_GET["success-status"])) ? cluevo_strip_non_alpha($_GET["success-status"]) : null;
      $tags = (!empty($_GET["tags"])) ? sanitize_text_field($_GET["tags"]) : null;
      $completionStatus = (!empty($_GET["completion-status"])) ? cluevo_strip_non_alpha($_GET["completion-status"]) : null;
      $lessonStatus = (!empty($_GET["lesson-status"])) ? cluevo_strip_non_alpha_blank($_GET["lesson-status"]) : null;
      $page = (isset($_GET["paged"]) && is_numeric($_GET["paged"]) && (int)$_GET["paged"] >= 0) ? (int)$_GET["paged"] : 0;
      $perPage = 100;
      $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : CLUEVO_ADMIN_TAB_REPORTS_MAIN;
      $active_page = CLUEVO_ADMIN_PAGE_REPORTS;

      $validStatus = ["completed", "incomplete", "not attempted", "unknown", "passed", "failed", "browsed"];
      $completionStatus = in_array($completionStatus, $validStatus) ? $completionStatus : '';
      $lessonStatus = in_array($lessonStatus, $validStatus) ? $lessonStatus : '';
      $successStatus = in_array($successStatus, $validStatus) ? $successStatus : '';

      $args = [
        "user_id" => $userId,
        "module_id" => $moduleId,
        "attempt_id" => $attemptId,
        "success_status" => $successStatus,
        "completion_status" => $completionStatus,
        "lesson_status" => $lessonStatus,
        "tags" => $tags,
        "page" => $page,
        "per_page" => $perPage,
        "active_tab" => $active_tab,
        "active_page" => $active_page
      ];
      self::$args = (object)wp_parse_args($args, self::$defaultArgs);
      self::init_settings();
      self::init_cols();

      add_filter('cluevo_progress_module_actions', __CLASS__ . '::add_module_actions', 10, 2);
      add_filter('cluevo_render_progress_table_rows', __CLASS__ . '::render_rows', 10, 2);
      add_filter('cluevo_render_progress_table_headers', __CLASS__ . '::render_headers', 10, 2);
      add_filter('cluevo_render_progress_table_filters', __CLASS__ . '::render_filters', 10, 2);
      add_filter('cluevo_render_progress_table_column', __CLASS__ . '::render_column', 10, 4);
    }

    private static function init_settings()
    {
      $defaults = [
        "cluevo-reports-progress-cols" => [],
        "cluevo-reports-progress-display" => ['username' => 'full_name']
      ];
      $cur = get_user_meta(get_current_user_id(), 'cluevo_reports_progress_settings', true);
      self::$settings = wp_parse_args($cur, $defaults);
    }

    private static function init_cols()
    {
      self::$columns = apply_filters(
        'cluevo_progress_table_column',
        [
          "user" => __("User", "cluevo"),
          "user_email" => __("E-Mail", "cluevo"),
          "module_name" => __("Module", "cluevo"),
          "tags" => __("Tags", "cluevo"),
          "attempt_id" => __("Attempt", "cluevo"),
          "date_started" => __("Start", "cluevo"),
          "date_modified" => __("Last Activity", "cluevo"),
          "score_min" => __("Min. Pts.", "cluevo"),
          "score_max" => __("Max. Pts.", "cluevo"),
          "score_raw" => __("Points", "cluevo"),
          "score_scaled" => __("Points %", "cluevo"),
          "completion_status" => __("Compl. Status", "cluevo"),
          "success_status" => __("Success Status", "cluevo"),
          "lesson_status" => __("Lesson Status", "cluevo"),
          "credit" => __("Credit", "cluevo")
        ]
      );
    }

    public static function render()
    {
      $args = (object)wp_parse_args((array)self::$args, self::$defaultArgs);
      $pagination = self::get_pagination([
        "module_id" => $args->module_id,
        "user_id" => $args->user_id,
        "attempt_id" => $args->attempt_id,
        "success_status" => $args->success_status,
        "completion_status" => $args->completion_status,
        "lesson_status" => $args->lesson_status,
        "tags" => $args->tags
      ], $args->per_page);

      $entries = self::get_entries(
        [
          "user_id" => $args->user_id,
          "module_id" => $args->module_id,
          "attempt_id" => $args->attempt_id,
          "module_id" => $args->module_id,
          "success_status" => $args->success_status,
          "completion_status" => $args->completion_status,
          "lesson_status" => $args->lesson_status,
          "tags" => $args->tags
        ],
        $args->page,
        $args->per_page
      );
      $html =  '<header class="cluevo-reporting-header">';
      $html .= '  <h1>' . esc_html(sprintf(_n('%d Entry found', '%d Entries Found', $pagination->items, "cluevo"), $pagination->items)) . '</h1>';
      if (!empty($entries)) {
        $html .= '  <a href="' . esc_url(add_query_arg('cluevo-export', 'progress')) . '"';
        $html .= '    target="_blank"';
        $html .= '    class="button auto cluevo-export-data">';
        $html .= esc_attr__("Export", "cluevo");
        $html .= '  </a>';
      }
      $html .= '</header>';
      $html .= apply_filters('cluevo_render_progress_table_filters', '', $args);
      if (!empty($entries)) {
        $html .= '<table class="wp-list-table widefat striped cluevo-filtered-table">';
        $html .= '  <thead>';
        $html .= '    <tr>';
        $html .=        apply_filters('cluevo_render_progress_table_headers', '', self::$columns);
        $html .= '    </tr>';
        $html .= '  </thead>';
        $html .= '  <tbody>';
        $html .=      apply_filters('cluevo_render_progress_table_rows', '', $entries);
        $html .= '  </tbody>';
        $html .= '</table>';
        $html .= self::render_pagination($pagination);
      } else {
        ob_start();
        cluevo_display_notice(__("Info", "cluevo"), __("No records found.", "cluevo"), "warning");
        $html .= ob_get_clean();
      }
      echo $html;
    }

    public static function render_headers($out, $cols)
    {
      foreach ($cols as $key => $value) {
        if (!in_array($key, self::$settings["cluevo-reports-progress-cols"]) || in_array($key, self::$fixedCols)) {
          $out .= '<th data-colname="' . esc_attr($key) . '">' . esc_html($value) . '</th>';
        }
      }
      return $out;
    }

    public static function render_filters($out, $args)
    {

      $attempts = self::get_filter_content("attempt_id", "attempt_id", (array)$args);
      if (!empty($attempts) && is_array($attempts)) {
        foreach ($attempts as $key => $value) {
          $attempts[$key]->label = (int)$value->value + 1;
        }
      }

      $success = ["passed", "failed", "unknown"];
      $successFilter = array_map(function ($value) {
        return (object)["value" => $value, "label" => apply_filters("cluevo_module_success_status_name", $value)];
      }, $success);
      $completion = ["completed", "incomplete", "not attempted", "unknown"];
      $completionFilter = array_map(function ($value) {
        return (object)["value" => $value, "label" => apply_filters("cluevo_module_completion_status_name", $value)];
      }, $completion);
      $lesson = ['passed', 'completed', 'failed', 'incomplete', 'browsed', 'not attempted'];
      $lessonFilter = array_map(function ($value) {
        return (object)["value" => $value, "label" => apply_filters("cluevo_module_lesson_status_name", $value)];
      }, $lesson);

      $open = '';
      foreach ((array)$args as $key => $value) {
        if (!in_array($key, ["page", "per_page", "active_tab", "active_page"]) && !empty($value)) {
          $open = 'open';
          break;
        }
      }

      $out .= '<details class="cluevo-reports-filter-list" ' . esc_attr($open) . '>';
      $out .= '  <summary>' . esc_html__("Filters", "cluevo") . '</summary>';
      $out .= '  <form action="' . esc_url(remove_query_arg('cur-page')) . '">';
      $out .= '    <input type="hidden" name="page" value="' . esc_attr($args->active_page) . '"/>';
      $out .= '    <input type="hidden" name="tab" value="' . esc_attr($args->active_tab) . '" />';
      $users = self::get_users();
      $out .= self::render_filter_field(__("User", "cluevo"), "user", __("All Users", "cluevo"), null, $users, $args->user_id);
      $modules = self::get_modules();
      $out .= self::render_filter_field(__("Module", "cluevo"), "module", __("All Modules", "cluevo"), null, $modules, $args->module_id);
      $out .= '    <label>' . esc_html__("Tags", "cluevo");
      $out .= '      <input type="text" name="tags" id="filter-tags" class="cluevo-filter-text" value="' . esc_attr($args->tags) . '" />';
      $out .= '    </label>';
      $out .= self::render_filter_field(__("Attempt", "cluevo"), "attempt", __("all attempts", "cluevo"), -1, $attempts, $args->attempt_id);
      $out .= self::render_filter_field(__("Completion Status", "cluevo"), "completion-status", __("all", "cluevo"), null, $completionFilter, $args->completion_status);
      $out .= self::render_filter_field(__("Success Status", "cluevo"), "success-status", __("all", "cluevo"), null, $successFilter, $args->success_status);
      $out .= self::render_filter_field(__("Lesson Status", "cluevo"), "lesson-status", __("all", "cluevo"), null, $lessonFilter, $args->lesson_status);
      $out .= '   <button type="submit" class="button auto">' . esc_attr__("Apply", "cluevo") . '</button>';
      $removeArgs = ["module", "user", "attempt", "tags", "completion-status", "success-status", "lesson-status"];
      $out .= '   <a href="' . esc_url(remove_query_arg($removeArgs)) . '" class="button auto">' . esc_attr__("Reset", "cluevo") . '</a>';
      $out .= '  </form>';
      $out .= '</details>';

      return $out;
    }

    public static function render_rows($tr, $entries)
    {
      $rowNum = 0;
      foreach ($entries as $key => $row) {
        $tr .= '<tr ';
        $tr .=   'data-user-id="' . esc_attr($row->user_id) . '" ';
        $tr .=   'data-module-id="' . esc_attr($row->module_id) . '" ';
        $tr .=   'data-attempt-id="' . esc_attr($row->attempt_id) . '"';
        $tr .= '>';
        $colNum = 0;
        foreach (self::$columns as $col => $_label) {
          if (property_exists($row, $col)) {
            $attributes = apply_filters(
              "cluevo_progress_table_column_attributes",
              [
                "class" => "",
                "data-colname" => $col
              ],
              $row
            );
            $attrs = '';
            foreach ($attributes as $att => $val) {
              $attrs .= ' ' . sanitize_key($att) . '="' . esc_attr(wp_strip_all_tags($val)) . '"';
            }
            if (!in_array($col, self::$settings["cluevo-reports-progress-cols"]) || in_array($col, self::$fixedCols)) {
              if (has_filter("cluevo_render_progress_table_column_{$col}")) {
                $tr .= apply_filters("cluevo_render_progress_table_column_{$col}", '', $row->{$col}, $row, $rowNum, $colNum);
              } else {
                $tr .= apply_filters("cluevo_render_progress_table_column", '', $col, $row->{$col}, $row, $rowNum, $colNum);
              }
              $colNum++;
            }
          }
        }
        $tr .= '</tr>';
        $rowNum++;
      }
      return $tr;
    }
    public static function render_column($td, $col, $value, $row, $rowNum = null, $colNum = null)
    {
      switch ($col) {
        case "user":
          $td .= '<td';
          $td .= ' class="left has-row-actions cluevo-nowrap"';
          $td .= '>';
          $td .= '  <a href="' . esc_url(add_query_arg("user", (int)sanitize_text_field($row->user_id))) . '">';
          $td .= esc_html($row->user);
          $td .= '  </a>';
          $td .= '  <div class="row-actions">';
          $td .= '  </div>';
          $td .= '</td>';
          break;
        case "user_email":
          $td .= '<td class="left cluevo-nowrap">';
          $td .= '  <a href="' . esc_url(add_query_arg("user", (int)sanitize_text_field($row->user_id))) . '">';
          $td .= esc_html($row->user_email);
          $td .= '  </a>';
          $td .= '</td>';
          break;
        case "module_name":
          $filterActions = apply_filters("cluevo_progress_module_actions", [], $row);
          $hasActions = (!empty($filterActions)) ? 'has-row-actions' : '';
          $td .= '<td class="left cluevo-table-filter cluevo-nowrap ' . esc_attr($hasActions) . '"';
          $td .= '>';
          $td .= '  <a href="' . esc_url(add_query_arg("module", (int)sanitize_text_field($row->module_id))) . '">';
          $td .=  esc_html($row->module_name);
          $td .= '  </a>';
          if (!empty($filterActions)) {
            $td .=  '<div class="row-actions">';
            $actions = '';
            foreach ($filterActions as $key => $value) {
              $actions .= '<span class="' . esc_attr($key) . '">';
              $actions .= wp_kses($value, ['a' => ["href" => [], "class" => [], "id" => [], "data-*" => []]]);
              $actions .= '</span> | ';
            }
            $td .= trim($actions, ' | ');
            $td .= '</div>';
          }
          $td .= '</td>';
          break;
        case "tags":
          $td .= '<td class="left cluevo-table-filter"';
          $td .= ' data-target="#filter-tags"';
          $td .= ' data-id="' . esc_attr($row->tags) . '"';
          $td .= '>';
          $td .= esc_html($row->tags);
          $td .= '</td>';
          break;
        case "attempt_id":
          $td .= '<td class="cluevo-table-filter"';
          $td .= ' data-target="#filter-attempt"';
          $td .= ' data-id="' . esc_attr($row->attempt_id) . '"';
          $td .= '>';
          $td .= esc_html((int)$row->attempt_id + 1);
          $td .= '</td>';
          break;
        case "date_started":
          $td .= '<td>' . esc_html(cluevo_format_datetime($row->date_started)) . '</td>';
          break;
        case "date_modified":
          $td .= '<td>' . esc_html(cluevo_format_datetime($row->date_modified)) . '</td>';
          break;
        case "score_min":
          $td .= '<td>' . esc_html(number_format_i18n($row->score_min, 2)) . '</td>';
          break;
        case "score_max":
          $td .= '<td>' . esc_html(number_format_i18n($row->score_max, 2)) . '</td>';
          break;
        case "score_raw":
          $td .= '<td>' . esc_html(number_format_i18n($row->score_raw, 2)) . '</td>';
          break;
        case "score_scaled":
          $td .= '<td>' . esc_html(number_format_i18n($row->score_scaled * 100, 2)) . '%</td>';
          break;
        case "completion_status":
          $td .= '<td class="status cluevo-module-completion-status-';
          $td .=  esc_attr(sanitize_key($row->completion_status));
          $td .= ($row->scorm_version == "1.2") ? " invalid" : "";
          $td .= '">';
          $td .= '  <a href="' . esc_url(add_query_arg("completion-status", sanitize_text_field($row->completion_status))) . '">';
          $td .= esc_html(apply_filters('cluevo_module_completion_status_name', $row->completion_status));
          $td .= '  </a>';
          $td .= '</td>';
          break;
        case "success_status":
          $td .= '<td';
          $td .= ' class="status cluevo-module-success-status-' . esc_attr(sanitize_key($row->success_status));
          $td .= ($row->scorm_version == "1.2") ? " invalid" : "";
          $td .= '">';
          $td .= '  <a href="' . esc_url(add_query_arg("success-status", sanitize_text_field($row->success_status))) . '">';
          $td .= esc_html(apply_filters('cluevo_module_success_status_name', $row->success_status));
          $td .= '  </a>';
          $td .= '</td>';
          break;
        case "lesson_status":
          $td .= '<td';
          $td .= ' class="status cluevo-module-lesson-status-';
          $td .= esc_attr(sanitize_key($row->lesson_status));
          $td .= ($row->scorm_version != "1.2") ? " invalid" : "";
          $td .= '">';
          $td .= '  <a href="' . esc_url(add_query_arg("lesson-status", sanitize_text_field($row->lesson_status))) . '">';
          $td .= esc_html(apply_filters('cluevo_module_lesson_status_name', $row->lesson_status));
          $td .= '  </a>';
          $td .= '</td>';
          break;
        case "credit":
          $td .= '<td class="credit-status toggle-credit ' . esc_attr($row->credit) . '">';
          $td .= '  <div class="button">';
          $td .=     ($row->credit == "credit") ? "🗸" : "🞨";
          $td .= '  </div>';
          $td .= '</td>';
          break;
        default:
      }
      return $td;
    }

    private static function get_entries($args = [], $intPage = 0, $intPerPage = null)
    {
      global $wpdb;
      $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
      $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
      $moduleTypeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;

      $sql = "SELECT p.*,
        IF(um.full_name != '', um.full_name, u.user_login) AS full_name,
        u.display_name,
        u.user_login,
        u.user_nicename,
        u.user_email,
        m.module_name,
        m.metadata_id,
        m.scorm_version,
        LOWER(t.type_name) AS type_name,
        m.tags
        FROM $progressTable p
        INNER JOIN {$wpdb->users} u ON p.user_id = u.ID
        INNER JOIN (
          SELECT user_id, TRIM(GROUP_CONCAT(meta_value ORDER BY meta_key ASC SEPARATOR ' ')) AS full_name
          FROM {$wpdb->usermeta}
          WHERE meta_key IN ('first_name', 'last_name')
          GROUP BY user_id
        ) um ON u.ID = um.user_id
        INNER JOIN $moduleTable m
        ON p.module_id = m.module_id
        LEFT JOIN $moduleTypeTable t
        ON m.type_id = t.type_id";

      $valid = ["user_id", "attempt_id", "success_status", "completion_status", "lesson_status", "module_id", "tags"];
      $where = [];
      $parms = [];
      foreach ($args as $arg => $value) {
        if (in_array($arg, $valid) && (!empty($value) || ($arg == "attempt_id" && $value > -1))) {
          if (empty($parms))
            $sql .= " WHERE ";
          if ($arg === "tags") {
            $tags = explode(",", $value);
            if (!empty($tags)) {
              $list = [];
              foreach ($tags as $tag) {
                $t = trim($tag);
                if (!empty($t)) {
                  $list[] = "m.tags LIKE CONCAT('%', %s, '%')";
                  $parms[] = $t;
                }
              }
              $where[] = "(" . implode(" OR ", $list) . ")";
            }
          } else {
            $where[] = "p.$arg = %s";
            $parms[] = $value;
          }
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
      if (!empty($result)) {
        foreach ($result as $key => $row) {
          switch (self::$settings["cluevo-reports-progress-display"]["username"]) {
            case "full_name":
              $result[$key]->user = $row->full_name;
              break;
            case "user_login":
              $result[$key]->user = $row->user_login;
              break;
            case "user_email":
              $result[$key]->user = $row->user_email;
              break;
            case "display_name":
              $result[$key]->user = $row->display_name;
              break;
            default:
              $result[$key]->user = $row->full_name;
          }
        }
      }
      return $result;
    }

    private static function get_pagination($args = [])
    {
      global $wpdb;
      $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
      $userTable = $wpdb->users;
      $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

      $sqlFields = "COUNT(*)";
      $sql = "SELECT $sqlFields
      FROM $progressTable p
      INNER JOIN $moduleTable m
        ON p.module_id = m.module_id
      INNER JOIN $userTable u
        ON p.user_id = u.ID";

      $valid = ["user_id", "attempt_id", "success_status", "completion_status", "lesson_status", "module_id", "tags"];
      $where = [];
      $parms = [];
      foreach ($args as $arg => $value) {
        if (in_array($arg, $valid) && (!empty($value) || ($arg == "attempt_id" && $value > -1))) {
          if (empty($parms))
            $sql .= " WHERE ";
          if ($arg === "tags") {
            $tags = explode(",", $value);
            if (!empty($tags)) {
              $list = [];
              foreach ($tags as $tag) {
                $t = trim($tag);
                if (!empty($t)) {
                  $list[] = "m.tags LIKE CONCAT('%', %s, '%')";
                  $parms[] = $t;
                }
              }
              $where[] = "(" . implode(" OR ", $list) . ")";
            }
          } else {
            $where[] = "p.$arg = %s";
            $parms[] = $value;
          }
        }
      }

      $sql .= implode(" AND ", $where);

      if (!empty($parms)) {
        $rows = $wpdb->get_var(
          $wpdb->prepare($sql, $parms)
        );
      } else {
        $rows = $wpdb->get_var($sql);
      }

      $pages = ceil($rows / self::$args->per_page);

      return (object)[
        "page" => (int)self::$args->page,
        "items_per_page" => (int)self::$args->per_page,
        "pages" => (int)$pages,
        "next" => self::$args->page > $pages ? self::$args->page + 1 : (int)$pages - 1,
        "prev" => self::$args->page > 0 ? self::$args->page - 1 : 0,
        "items" => (int)$rows
      ];
    }

    private static function render_filter_field($strLabel, $strName, $strEmptyLabel, $strEmptyValue, $values, $selected)
    {
      $html =  '<label>' . esc_html($strLabel);
      $html .= '  <select';
      $html .= '    class="cluevo-filter-input"';
      $html .= '    name="' . esc_attr($strName) . '"';
      $html .= '  >';
      $html .= '<option value="' . esc_attr($strEmptyValue) . '">' . esc_attr($strEmptyLabel) . '</option>';
      if (!empty($values)) {
        foreach ($values as $v) {
          $html .= '<option value="' . esc_attr($v->value) . '" ' . selected($v->value, $selected, false) . '>';
          $html .= esc_html(mb_strimwidth($v->label, 0, 20, "..."));
          $html .= '</option>';
        }
      }
      $html .= '  </select>';
      $html .= '</label>';
      return $html;
    }

    private static function render_pagination($args)
    {
      $default = [
        "page" => 0,
        "items_per_page" => 100,
        "next" => 0,
        "prev" => 0,
        "items" => 0
      ];
      $pagination = (object)wp_parse_args((array)$args, $default);
      $out = '<div class="tablenav bottom">';
      $out .= '  <div class="tablenav-pages">';
      $out .= '    <span class="displaying-num">' . sprintf(esc_html__("%d items", "cluevo"), (int)$pagination->items) . '</span>';
      $firstDisabled = $pagination->page === 0;
      $prevDisabled = $pagination->page === 0;
      $nextDisabled = $pagination->page + 1 === $pagination->pages;
      $lastDisabled = $pagination->page + 1 === $pagination->pages;
      $out .= '    <span class="pagination-links">';
      if ($firstDisabled) {
        $out .= '    <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>';
      } else {
        $out .= '    <a class="first-page button" href="' . remove_query_arg("paged") . '">';
        $out .= '      <span class="screen-reader-text">' . esc_html__("First Page", "cluevo") . '</span><span aria-hidden="true">«</span>';
        $out .= '    </a>';
      }
      if ($prevDisabled) {
        $out .= '    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>';
      } else {
        $out .= '    <a class="prev-page button" href="' . add_query_arg("paged", (int)$pagination->prev) . '">';
        $out .= '      <span class="screen-reader-text">' . esc_html__("Previous Page", "cluevo") . '</span><span aria-hidden="true">‹</span>';
        $out .= '    </a>';
      }
      $out .= '    <span class="screen-reader-text">' . esc_html__("Current Page", "cluevo") . '</span>';
      $out .= '    <span id="table-paging" class="paging-input">';
      $out .= '      <span class="tablenav-paging-text">';
      $out .=          ((int)$pagination->page + 1) . ' ' . esc_html__("of", "cluevo");
      $out .=        ' <span class="total-pages">' . (int)$pagination->pages . '</span>';
      $out .= '      </span>';
      $out .= '    </span>';
      if ($nextDisabled) {
        $out .= '    <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>';
      } else {
        $out .= '    <a class="next-page button ' . $nextDisabled . '" href="' . add_query_arg("paged", (int)$pagination->next) . '">';
        $out .= '      <span class="screen-reader-text">' . esc_html__("Next Page", "cluevo") . '</span><span aria-hidden="true">›</span>';
        $out .= '    </a>';
      }
      if ($lastDisabled) {
        $out .= '    <span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>';
      } else {
        $out .= '    <a class="last-page button" href="' . add_query_arg("paged", (int)$pagination->pages) . '">';
        $out .= '      <span class="screen-reader-text">' . esc_html__("Last Page", "cluevo") . '</span><span aria-hidden="true">»</span>';
        $out .= '    </a>';
      }
      $out .= '    </span>';
      $out .= '    <br class="clear">';
      $out .= '</div>';
      return $out;
    }

    private static function get_filter_content($strFieldValue, $strFieldLabel, $args = [])
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

    public static function add_screen_options()
    {
      if (empty($_GET["page"])) return;
      if (empty($_GET["tab"])) return;
      if ($_GET["tab"] !== CLUEVO_ADMIN_TAB_REPORTS_PROGRESS) return;

      add_screen_option(
        "cluevo-reports-progress-cols",
        [
          'default' => 1,
          'label'   => __("Columns", "cluevo"),
          'value'  => true
        ]
      );

      add_screen_option(
        "cluevo-reports-progress-display",
        [
          'default' => null,
          'label'   => __("Display Options", "cluevo"),
          'value'  => true
        ]
      );
    }

    public static function render_screen_options($status, $screen)
    {
      if ($screen->base !== "cluevo_page_cluevo-reporting") return $status;
      if (empty($_GET["tab"])) return $status;
      if ($_GET["tab"] !== CLUEVO_ADMIN_TAB_REPORTS_PROGRESS) return $status;
      $status .= '<input type="hidden" name="wp_screen_options[option]" value="cluevo-reports-progress-settings" />';
      $cur = get_user_meta(get_current_user_id(), 'cluevo_reports_progress_settings', true);
      if (!empty($cur) && !is_array($cur)) {
        $cur = ["cluevo-reports-progress-cols" => [], "cluevo-reports-progress-display" => ["username" => "full_name"]];
      }
      $status .= '<fieldset class="metabox-prefs cluevo-reports-progresss-cols metabox-prefs-cluevo">';
      $status .= '<legend>' . esc_html__("Columns", "cluevo") . '</legend>';
      $columns = [
        "user_email" => __("E-Mail", "cluevo"),
        "tags" => __("Tags", "cluevo"),
        "attempt_id" => __("Attempt", "cluevo"),
        "date_started" => __("Start", "cluevo"),
        "date_modified" => __("Last Activity", "cluevo"),
        "score_min" => __("Min. Pts.", "cluevo"),
        "score_max" => __("Max. Pts.", "cluevo"),
        "score_raw" => __("Points", "cluevo"),
        "score_scaled" => __("Points %", "cluevo"),
        "completion_status" => __("Compl. Status", "cluevo"),
        "success_status" => __("Success Status", "cluevo"),
        "lesson_status" => __("Lesson Status", "cluevo"),
        "credit" => __("Credit", "cluevo")
      ];
      foreach ($columns as $opt => $title) {
        $status .= '<label>';
        $status .= '<input ';
        $status .=   'type="checkbox" ';
        $status .=   'name="wp_screen_options[value][cluevo-reports-progress-cols][' . esc_attr($opt) . ']" ';
        if (empty($cur) || empty($cur['cluevo-reports-progress-cols']) || (!empty($cur['cluevo-reports-progress-cols']) && !in_array($opt, $cur['cluevo-reports-progress-cols']))) {
          $status .= 'checked="checked"';
        }
        $status .= '/> ';
        $status .= esc_html($title) . '</label>';
      }
      $status .= '</fieldset>';
      $status .= '<fieldset class="metabox-prefs cluevo-reports-progresss-cols metabox-prefs-cluevo">';
      $status .= '<legend>' . esc_html__("Display Options", "cluevo") . '</legend>';
      $status .= '<label>' . esc_html__("Display users with", "cluevo") . ' <select ';
      $status .=   'name="wp_screen_options[value][cluevo-reports-progress-display][username]" ';
      $status .= '>';
      $opts = [
        "full_name" => __("Full Name", "cluevo"),
        "user_login" => __("Username", "cluevo"),
        "display_name" => __("Display Name", "cluevo"),
        "user_email" => __("E-Mail", "cluevo")
      ];
      foreach ($opts as $key => $value) {
        $status .= '  <option value="' . esc_attr($key) . '"';
        if (!empty($cur['cluevo-reports-progress-display']["username"]) && $cur["cluevo-reports-progress-display"]["username"] === $key) {
          $status .= ' selected="selected"';
        }
        $status .= '  >' . esc_html($value) . '</option>';
      }
      $status .= '</select>';
      $status .= '</label>';
      $status .= '</fieldset>';
      $status .= get_submit_button(__("Apply", "cluevo"), 'primary large', 'screen-options-apply');

      return $status;
    }

    public static function set_options($status, $option, $value)
    {
      $defaults = [
        "cluevo-reports-progress-cols" => [],
        "cluevo-reports-progress-display" => ['username' => 'full_name']
      ];
      self::init_cols();
      if (!empty($value) && is_array($value)) {
        $clean = [];
        foreach ($value as $group => $opt) {
          if ($group === 'cluevo-reports-progress-cols') {
            $clean[$group] = [];
            foreach (self::$columns as $col => $_) {
              if (!in_array($col, array_keys($opt))) {
                $clean[$group][] = sanitize_text_field($col);
              }
            }
          } else if ($group === 'cluevo-reports-progress-display') {
            $validUserOpts = ["full_name", "display_name", "user_login", "user_email"];
            if (in_array($opt["username"], $validUserOpts)) {
              $clean[$group]["username"] = sanitize_text_field($opt["username"]);
            }
          }
        }
        return $clean;
      } else {
        return [
          "cluevo-reports-progress-cols" => null,
          "cluevo-reports-progress-display" => ["username" => 'full_name']
        ];
      }
    }

    private static function get_users()
    {
      global $wpdb;
      $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
      $sql = "SELECT wpu.ID as user_id,
        wpu.user_login AS user,
        IF(um.full_name != '', um.full_name, wpu.user_login) AS full_name,
        wpu.display_name,
        wpu.user_login,
        wpu.user_nicename,
        wpu.user_email
        FROM {$wpdb->users} wpu
        INNER JOIN (
          SELECT user_id, TRIM(GROUP_CONCAT(meta_value ORDER BY meta_key ASC SEPARATOR ' ')) AS full_name
          FROM {$wpdb->usermeta}
          WHERE meta_key IN ('first_name', 'last_name')
          GROUP BY user_id
        ) um ON wpu.ID = um.user_id
        WHERE EXISTS ( SELECT 1 FROM {$table} p WHERE p.user_id = wpu.ID)";

      $result = $wpdb->get_results($sql);
      $list = [];
      if (!empty($result)) {
        foreach ($result as $key => $row) {
          $name = self::$settings["cluevo-reports-progress-display"]["username"];
          $valid = ["full_name", "display_name", "user_login", "user_nicename", "user_email"];
          if (!empty($name) && in_array($name, $valid) && property_exists($row, $name)) {
            $result[$key]->user = $row->{$name};
          }
          $list[] = (object)["value" => (int)$row->user_id, "label" => $row->user];
        }
      }
      usort($list, function ($a, $b) {
        return strcasecmp($a->label, $b->label);
      });
      return $list;
    }

    private static function get_modules()
    {
      global $wpdb;
      $modulesTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
      $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
      $sql = "SELECT DISTINCT p.module_id AS value, m.module_name AS label
        FROM {$progressTable} p
        INNER JOIN {$modulesTable} m ON p.module_id = m.module_id
        ORDER BY m.module_name";
      $results = $wpdb->get_results($sql);
      return $results;
    }

    public static function add_module_actions($actions, $row)
    {
      if (!empty($row->type_name) && stripos($row->type_name, "scorm") !== false) {
        $view  = '    <a ';
        $view .= '      href="' . esc_url(
          esc_url(add_query_arg([
            "tab" => CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS,
            "module" => (int)$row->module_id,
            "user" => (int)$row->user_id,
            "attempt" => (int)$row->attempt_id
          ])),
          ['http', 'https']
        ) . '">';
        $view .=  esc_html__("Browse SCORM Parameters", "cluevo");
        $view .=      '</a>';
        $actions["view-parameters"] = $view;
      }
      $edit = '  <span class="edit-progress">';
      $edit .= '    <a href="#" class="cluevo-edit-progress">';
      $edit .=  esc_html__("Edit Progress", "cluevo");
      $edit .=      '</a>';
      $edit .= '  </span>';
      $actions['edit-progress'] = $edit;
      return $actions;
    }
  }
}
