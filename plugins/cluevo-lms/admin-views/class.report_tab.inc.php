<?php

if (!class_exists('CluevoReportTab')) {
  class CluevoReportTab
  {
    private static $settings;
    private static $parms;
    private static $modules;
    private static $competences;
    private static $competence_areas;
    private static $ratings = [];
    private static $item_tree_map = [];
    private static $_items = [];
    private static $item;
    private static $initialized = false;
    private static $children;
    private static $completed_items = [];

    public static function init()
    {
      do_action('cluevo_progress_report_page_init');
      self::init_settings();
      if (empty(self::$item_tree_map)) {
        self::load_item_tree_map();
      }
      self::load_parms();
      self::get_used_modules();
      self::load_competences();
      self::$initialized = true;
    }

    public static function init_actions()
    {
      add_action('cluevo_progress_report_display_default', 'CluevoReportTab::display_default');
      add_action('cluevo_progress_report_display_item', 'CluevoReportTab::display_item', 10, 1);
    }

    private static function init_settings()
    {
      $defaults = [
        "cluevo-reports-main-cols" => [],
        "cluevo-reports-main-display" => ['username' => 'full_name']
      ];
      $cur = get_user_meta(get_current_user_id(), 'cluevo_reports_main_settings', true);
      self::$settings = wp_parse_args($cur, $defaults);
    }

    public static function display_default()
    {
      self::display_item(1);
    }

    public static function display()
    {
      self::init();
      echo '<div class="cluevo-report-tab-main">';
      if (empty(self::$parms->item_id)) {
        do_action('cluevo_progress_report_display_default');
      } else {
        do_action('cluevo_progress_report_display_item', (int)self::$parms->item_id);
      }
      echo '</div>';
    }

    public static function display_item($intItemId = 1)
    {
      if (!self::$initialized) {
        self::init();
      }
      if (!is_numeric($intItemId) || $intItemId < 1) {
        cluevo_display_notice(
          __("Error", "cluevo"),
          __("Item not found", "cluevo"),
          'error'
        );
        return;
      }
      self::build_tree($intItemId);
      if (empty(self::$item)) {
        cluevo_display_notice(
          __("Error", "cluevo"),
          __("Item not found", "cluevo"),
          "error"
        );
        return;
      }
      $item = self::$item;
      $out  = '<header>';
      $out .= '  <h1>';
      foreach ($item->path->string as $key => $el) {
        if ((int)$item->path->id[$key] !== (int)$intItemId) {
          $out .= '  <a href="' . esc_url(add_query_arg("item", (int)$item->path->id[$key])) . '">' . esc_html($el) . '</a> / ';
        } else {
          $out .= '  <span>' . esc_html($el) . '</span>';
        }
      }
      $out .= '  </h1>';
      $out .= '</header>';
      if (!empty($item->children)) {
        $out .= '<h2>';
        $out .= esc_html(
          sprintf(
            _n("%d Child in this element", "%d Children in this Element", count($item->children), "cluevo"),
            count($item->children)
          )
        );
        $out .= '</h2>';
        $modOut = '';
        foreach ($item->children as $child) {
          // $out .= self::render_child($child);
          $modOut .= '<div class="cluevo-module">';
          // if (empty($child->module) || (!is_object($child->module) && (int)$child->module === -1)) {
          if (empty($child->module_id) || (int)$child->module_id === -1) {
            $modOut .= '  <div class="cluevo-module-header">';
            $modOut .= '    <a href="' . esc_url(add_query_arg("item", $child->item_id)) . '">';
            $modOut .= esc_html($child->name);
            $modOut .= '    </a>';
            $modOut .= '  </div>';
            $modOut .= '  <table class="wp-list-table striped">';
            $modOut .= '    <tbody>';
            $modOut .= '    <tr>';
            $modOut .= '      <th class="cluevo-children">' . esc_html__("Children", "cluevo") . '</th>';
            $modOut .= '      <td>' . esc_html(count($child->children)) . '</td>';
            // $modOut .= '      <td>';
            // if (!empty($child->children)) {
            //   $modOut .= '      <ul>';
            //   foreach ($child->children as $child) {
            //     $modOut .= self::render_child($child, true);
            //   }
            //   $modOut .= '      </ul>';
            // }
            // $modOut .= '      </td>';
            $modOut .= '    </tr>';
            $modOut .= '    <tr>';
            $modOut .= '      <th>' . esc_html__("Modules", "cluevo") . '</th>';
            $modOut .= '      <td>' . esc_html(count($child->modules)) . '</td>';
            $modOut .= '    </tr>';
            $modOut .= '  </tbody>';
            $modOut .= '</table>';
          } else {
            $modOut .= '  <div class="cluevo-module-header">';
            $modOut .= '    <a href="' . esc_url(
              remove_query_arg("item", add_query_arg(["tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS, "module" => $child->module->module_id]))
            ) . '">';
            $modOut .= esc_html($child->module->module_name);
            $modOut .= '    </a>';
            $modOut .= '  </div>';
            if (!empty($child->module->stats)) {
              $stats = $child->module->stats;
              $modOut .= '  <table class="wp-list-table striped">';
              $modOut .= '    <tbody>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Attempts", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html($stats->total_attempts) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Passed", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html($stats->passed) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Failing", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html($stats->failing) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Completed", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html($stats->completed) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Users", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html($stats->users) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Worst Score", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html(number_format_i18n($stats->worst_score * 100, 2)) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Worst User", "cluevo") . '</th>';
              $modOut .= '      <td>';
              if (!empty($stats->worst_user)) {
                $modOut .= '      <a href="' . esc_url(add_query_arg([
                  "tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS,
                  "module" => $stats->module_id
                ])) . '">';
                $modOut .= esc_html(self::get_username($stats->worst_user));
                $modOut .= '      </a>';
              }
              $modOut .= '      </td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Best Score", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html(number_format_i18n($stats->best_score * 100, 2)) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Best User", "cluevo") . '</th>';
              $modOut .= '      <td>';
              if (!empty($stats->best_user)) {
                $modOut .= '      <a href="' . esc_url(add_query_arg([
                  "tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS,
                  "module" => $stats->module_id
                ])) . '">';
                $modOut .= esc_html(self::get_username($stats->best_user));
                $modOut .= '      </a>';
              }
              $modOut .= '      </td>';
              $modOut .= '    </tr>';
              $modOut .= '    <tr>';
              $modOut .= '      <th>' . esc_html__("Average Score", "cluevo") . '</th>';
              $modOut .= '      <td>' . esc_html(number_format_i18n($stats->average_score * 100, 2)) . '</td>';
              $modOut .= '    </tr>';
              $modOut .= '    </tbody>';
              $modOut .= '  </table>';
            } else {
              $modOut .= '<p>' . esc_html__("No data available", "cluevo") . '</p>';
            }
          }
          $modOut .= '</div>';
        }
        if (!empty($modOut)) {
          $out .= '<div class="cluevo-module-list">' . $modOut . '</div>';
        }
      }
      if (!empty($item->modules)) {
        $out .= '<h2>';
        $out .= esc_html(
          sprintf(
            _n("This Item contains %d Module in Total", "This Item contains a Total of %d Modules", count($item->modules), "cluevo"),
            count($item->modules)
          )
        );
        $out .= '</h2>';
        $modOut = '';
        foreach ($item->modules as $id) {
          if (!array_key_exists($id, self::$modules)) continue;
          $modOut .= '<tr>';
          $filterActions = apply_filters("cluevo_reports_module_actions", [], self::$modules[$id]);
          $hasActions = (!empty($filterActions)) ? 'has-row-actions' : '';
          $modOut .= '  <td class="primary ' . esc_attr($hasActions) . '">';
          $modOut .= '    <a href="' . esc_url(
            remove_query_arg("item", add_query_arg(["tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS, "module" => $id]))
          ) . '">';
          $modOut .= esc_html(self::$modules[$id]->module_name);
          $modOut .= '    </a>';
          if (!empty($filterActions)) {
            $modOut .=  '<div class="row-actions">';
            $actions = '';
            foreach ($filterActions as $key => $value) {
              $actions .= '<span class="' . esc_attr($key) . '">';
              $actions .= wp_kses($value, ['a' => ["href" => [], "class" => [], "id" => [], "data-*" => []]]);
              $actions .= '</span> | ';
            }
            $modOut .= trim($actions, ' | ');
            $modOut .= '</div>';
          }
          $modOut .= '  </td>';
          if (empty(self::$modules[$id]->stats)) {
            $modOut .= '  <td colspan="10">' . esc_html__("No data available", "cluevo") . '</td>';
            $modOut .= '</tr>';
          } else {
            $stats = self::$modules[$id]->stats;
            $modOut .= '  <td>' . esc_html($stats->total_attempts) . '</td>';
            $modOut .= '  <td>' . esc_html($stats->passed) . '</td>';
            $modOut .= '  <td>' . esc_html($stats->failing) . '</td>';
            $modOut .= '  <td>' . esc_html($stats->completed) . '</td>';
            $modOut .= '  <td>' . esc_html($stats->users) . '</td>';
            $modOut .= '  <td>' . esc_html(number_format_i18n($stats->best_score * 100, 2)) . '</td>';
            $modOut .= '  <td>';
            if (!empty($stats->best_user)) {
              $modOut .= '  <a href="' . esc_url(add_query_arg([
                "tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS,
                "module" => $stats->module_id
              ])) . '">';
              $modOut .= esc_html(self::get_username($stats->best_user));
              $modOut .= '  </a>';
            }
            $modOut .= '  </td>';
            $modOut .= '  <td>' . esc_html(number_format_i18n($stats->worst_score * 100, 2)) . '</td>';
            $modOut .= '  <td>';
            if (!empty($stats->worst_user)) {
              $modOut .= '  <a href="' . esc_url(add_query_arg([
                "tab" => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS,
                "module" => $stats->module_id
              ])) . '">';
              $modOut .= esc_html(self::get_username($stats->worst_user));
              $modOut .= '  </a>';
            }
            $modOut .= '  </td>';
            $modOut .= '  <td>' . esc_html(number_format_i18n($stats->average_score * 100, 2)) . '</td>';
          }
          $modOut .= '</tr>';
        }
        if (!empty($modOut)) {
          $out .= '<table class="wp-list-table widefat striped">';
          $out .= '  <thead>';
          $out .= '    <th>' . esc_html__("Module", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Attempts", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Passed", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Failing", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Completed", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Users", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Best Score", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Best User", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Worst Score", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Worst User", "cluevo") . '</th>';
          $out .= '    <th>' . esc_html__("Average Score", "cluevo") . '</th>';
          $out .= '  </thead>';
          $out .= '  <tbody>' . $modOut . '</tbody>';
          $out .= '</table>';
        }
      }
      echo $out;
    }

    private static function render_child($item, $boolDeep = false)
    {
      $out = '<li>';
      $out .= '  <a href="' . esc_url(add_query_arg("item", $item->item_id)) . '">';
      $out .= esc_html($item->name);
      $out .= '  </a>';
      // if ($item->modules) {
      //   $out .= "(" . esc_html(sprintf(_n("%d Module", "%d Modules", count($item->modules), "cluevo"), count($item->modules))) . ')';
      // }
      if ($boolDeep && !empty($item->children)) {
        $out .= '  <ul>';
        foreach ($item->children as $child) {
          $out .= self::render_child($child);
        }
        $out .= '  </ul>';
      }
      $out .= '</li>';
      return $out;
    }

    private static function load_parms()
    {
      $itemId = (!empty($_GET["item"]) && is_numeric($_GET["item"])) ? (int)$_GET["item"] : null;
      $userId = (!empty($_GET["user"]) && is_numeric($_GET["user"])) ? (int)$_GET["user"] : null;
      $groupId = (!empty($_GET["group"]) && is_numeric($_GET["group"])) ? (int)$_GET["group"] : null;

      self::$parms = (object)[
        "item_id" => $itemId,
        "user_id" => $userId,
        "group_id" => $groupId,
        "tab" => CLUEVO_ADMIN_TAB_REPORTS_MAIN,
        "page" => CLUEVO_ADMIN_PAGE_REPORTS
      ];

      return self::$parms;
    }

    private static function get_used_modules($intItemId = null)
    {
      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $treeModuleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
      $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
      $moduleTypesTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
      $sql = <<<SQL
        SELECT m.*, t.item_id, t.parent_id, t.path, t.name, mt.type_name
        FROM {$moduleTable} m
        INNER JOIN {$moduleTypesTable} mt ON m.type_id = mt.type_id
        INNER JOIN {$treeModuleTable} tm ON m.module_id = tm.module_id
        INNER JOIN {$treeTable} t ON tm.item_id = t.item_id
SQL;
      if (!empty($intItemId)) {
        $sql .= " WHERE t.path LIKE CONCAT('%/', %d, '/%')";
        $parms = [(int)$intItemId];
        $modules = $wpdb->get_results($wpdb->prepare($sql, $parms));
      } else {
        $modules = $wpdb->get_results($sql);
      }

      self::$modules = [];
      $stats = self::get_module_stats();
      if (!empty($modules)) {
        foreach ($modules as $m) {
          $m->competences = [];
          $m->competence_areas = [];

          if (empty(self::$modules[$m->module_id])) {
            $m->items = [$m->item_id];
            $m->parents = [$m->parent_id];
            $m->paths = [$m->path . $m->item_id . "/"];
            if (array_key_exists($m->module_id, $stats) && (int)$stats[$m->module_id]->module_id === (int)$m->module_id) {
              $m->stats = $stats[$m->module_id];
            }
            self::$modules[$m->module_id] = $m;
          } else {
            self::$modules[$m->module_id]->items[] = $m->item_id;
            if (in_array($m->parent_id, self::$modules[$m->module_id]->parents)) {
              self::$modules[$m->module_id]->parents[] = $m->parent_id;
            }
            self::$modules[$m->module_id]->paths[] = $m->path . $m->item_id . "/";
          }
        }
      }
      return self::$modules;
    }

    private static function merge_items_modules($items)
    {
      $results = [];
      $allIds = array_column($items, "item_id");
      if (!empty($allIds)) {
        foreach ($allIds as $id) {
          $results[$id] = (object)[
            "all" => [],
            "completed" => [],
            "passed" => [],
            "failed" => [],
            "competences" => [],
            "competence_areas" => []
          ];
        }
      }
      foreach (self::$modules as $row) {
        $moduleId = (int)$row->module_id;
        foreach ($row->paths as $completePath) {
          $path = explode('/', $completePath);
          if (!empty($path)) {
            foreach ($path as $id) {
              if (!array_key_exists($id, $results)) continue;
              if (!in_array($moduleId, $results[$id]->all)) {
                $results[$id]->all[] = $moduleId;
              }
              if (!empty(self::$modules[$moduleId]->competences)) {
                foreach (self::$modules[$moduleId]->competences as $c) {
                  if (!empty(self::$competences[$c->competence_id])) {
                    $results[$id]->competences[$c->competence_id] = self::$competences[$c->competence_id];
                  }
                }
              }
              if (!empty(self::$modules[$moduleId]->competence_areas)) {
                foreach (self::$modules[$moduleId]->competence_areas as $c) {
                  if (!empty(self::$competence_areas[$c->competence_area_id])) {
                    $results[$id]->competence_areas[$c->competence_area_id] = self::$competence_areas[$c->competence_area_id];
                  }
                }
              }
            }
          }
        }
      }
      return $results;
    }

    private static function get_entries($args = [])
    {
      $defaults = ["user_id" => null, "group_id" => null, "item_id" => 0];
      $args = (object)wp_parse_args($args, $defaults);

      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $usersToGroupsTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
      $treeModulesTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;

      $sql = <<<SQL
      SELECT t.* FROM {$treeTable} t WHERE t.parent_id = %d";
SQL;
      $parms = [$args->item_id];
      $rows = $wpdb->get_results($wpdb->prepare($sql, $parms));
      return $rows;
    }

    private static function get_trees()
    {
      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $sql = <<<SQL
        SELECT t.* FROM {$treeTable} t WHERE t.parent_id = 0;
SQL;
      $trees = $wpdb->get_results($sql);
      $trees = self::merge_items_modules($trees);
      foreach ($trees as $tree) {
        $tree->children = self::get_children($tree->item_id);
      }
      return $trees;
    }

    private static function get_item_data($intItemId)
    {
      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $sql = <<<SQL
        SELECT t.* FROM {$treeTable} t WHERE t.item_id = %d;
SQL;
      $item = $wpdb->get_row($wpdb->prepare($sql, [(int)$intItemId]));
      self::get_used_modules();
      $items = self::merge_items_modules([$item]);
      $children = self::get_children((int)$intItemId);
      // $children = self::merge_items_modules([$children]);
      $item = $items[0];
      $item->children = $children;
      return $item;
    }

    private static function get_children($intParentId)
    {
      //       global $wpdb;
      //       $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      //       $sql = <<<SQL
      //         SELECT t.* FROM {$treeTable} t WHERE t.parent_id = %d;
      // SQL;
      //       $children = $wpdb->get_results($wpdb->prepare($sql, [(int)$intParentId]));
      //       return $children;

      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $sql = <<<SQL
        SELECT t.* FROM {$treeTable} t WHERE t.parent_id = %d;
SQL;
      $items = $wpdb->get_results($wpdb->prepare($sql, [(int)$intParentId]));
      self::get_used_modules();
      $items = self::merge_items_modules($items);
      return $items;
    }

    private static function get_items($intItemId)
    {
      global $wpdb;
      $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $treeModulesTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
      $sql = "SELECT t.*, tm.module_id,
      GROUP_CONCAT(DISTINCT t2.item_id) AS children
    FROM {$treeTable} t
    LEFT JOIN {$treeTable} t2 ON t.item_id = t2.parent_id
    LEFT JOIN {$treeModulesTable} tm ON t.item_id = tm.item_id
    WHERE t.path LIKE CONCAT('%/', %d, '/%') OR t.item_id = %d
    GROUP BY t.item_id
    ORDER BY t.level DESC, t.sort_order ASC";
      $result = $wpdb->get_results($wpdb->prepare($sql, [(int)$intItemId, (int)$intItemId]));
      foreach ($result as $key => $value) {
        $value->module = $value->module_id;
        $value->permalink = get_permalink($value->metadata_id);
        if (!$value->children) {
          $value->children = [];
        }
        if (empty($value->children)) continue;
        $children = array_filter(explode(',', $value->children), function ($c) {
          return !empty($c);
        });
        $result[$key]->children = $children;
      }
      return $result;
    }

    private static function load_competences()
    {
      global $wpdb;
      $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
      $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
      $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
      $modulesToCompetences = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

      $sqlComps = <<<SQL
      SELECT
        cm.competence_module_id,
        cm.competence_id,
        c.*,
        cm.module_id,
        cm.competence_coverage,
        ( SELECT GROUP_CONCAT(DISTINCT competence_area_id)
            FROM {$compToAreaTable}
            WHERE competence_id = cm.competence_id
        ) AS competence_areas
      FROM {$modulesToCompetences} cm
      INNER JOIN {$compTable} c ON cm.competence_id = c.competence_id;
SQL;

      $comps = $wpdb->get_results($sqlComps);
      if (!empty($comps)) {
        $list = [];
        foreach ($comps as $c) {
          if (!empty($c->competence_areas)) {
            $c->competence_areas = explode(',', $c->competence_areas);
          } else {
            $c->competence_areas = [];
          }
          $c->score = 0;
          $module = null;
          if (!empty(self::$modules[$c->module_id])) {
            $module = self::$modules[$c->module_id];
          }
          if (empty($list[$c->competence_id])) {
            $tmpComp = clone $c;
            $tmpComp->total_coverage = 0;
            $tmpComp->score = 0;
            foreach (["competence_module_id", "module_id", "competence_coverage"] as $key) {
              if (!property_exists($tmpComp, $key)) continue;
              unset($tmpComp->{$key});
            }
            $tmpComp->modules = [];
            $list[$c->competence_id] = $tmpComp;
          }
          $list[$c->competence_id]->modules[] = (object)["module_id" => $c->module_id, "coverage" => $c->competence_coverage];
          $total = $list[$c->competence_id]->total_coverage + $c->competence_coverage;
          $list[$c->competence_id]->total_coverage = ($total > 1) ? 1.0 : $total;
          if (!empty($module)) {
            if (empty($module->competences)) {
              $module->competences = [];
            }
            if (!empty($module->best_attempt->score_scaled) && !empty($c->competence_coverage)) {
              $c->score = $module->best_attempt->score_scaled * $c->competence_coverage;
              if (!empty($list[$c->competence_id])) {
                $list[$c->competence_id]->score += $c->score;
              }
            }
            $module->competences[$c->competence_id] = $c;
          }
        }
        self::$competences = $list;
      }

      $sqlAreas = <<<SQL
        SELECT 
          ca.competence_area_id,
          ca.competence_area_name,
          ca.metadata_id,
          MAX(ca.date_added) AS date_added,
          MAX(ca.date_modified) AS date_modified,
          GROUP_CONCAT(DISTINCT cta.competence_id) AS competences,
          GROUP_CONCAT(DISTINCT cm.module_id) AS modules
        FROM {$areaTable} ca
        INNER JOIN {$compToAreaTable} cta ON ca.competence_area_id = cta.competence_area_id
        INNER JOIN {$modulesToCompetences} cm ON cta.competence_id = cm.competence_id
        GROUP BY ca.competence_area_id, ca.competence_area_name, ca.metadata_id;
SQL;

      $areas = $wpdb->get_results($sqlAreas);
      if (!empty($areas)) {
        $list = [];
        foreach ($areas as $area) {
          $area->score = 0;
          if (!empty($area->competences)) {
            $area->competences = explode(",", $area->competences);
            if (!empty($area->competences)) {
              $totalScore = 0;
              foreach ($area->competences as $c) {
                if (empty(self::$competences[$c]->score)) continue;
                $totalScore += self::$competences[$c]->score;
              }
              $area->score = $totalScore / count($area->competences);
            }
            if (!empty($area->modules)) {
              $area->modules = explode(",", $area->modules);
              if (!empty($area->modules)) {
                foreach ($area->modules as $m) {
                  if (!empty(self::$modules[$m])) {
                    self::$modules[$m]->competence_areas[$area->competence_area_id] = $area;
                  }
                }
              }
            } else {
              $area->modules = [];
            }
          }
        }
        self::$competence_areas = $list;
      }
    }

    private static function process_leaf($leaf, $map)
    {
      $path = array_values(array_filter(explode('/', $leaf->path), function ($value) {
        return trim($value) !== '';
      }));
      if (empty($path) || !is_array($path)) {
        $path = [];
      }
      $path[] = $leaf->item_id;
      $leaf->path = (object)["id" => $path, "string" => []];

      if (!empty($leaf->module_id) && array_key_exists($leaf->module_id, self::$modules)) {
        $leaf->module = self::$modules[$leaf->module_id];
      }
      $leaf->modules = $map[$leaf->item_id]->all;
      $leaf->competences = $map[$leaf->item_id]->competences;
      $leaf->competence_areas = $map[$leaf->item_id]->competence_areas;
      $leaf->completed = count($map[$leaf->item_id]->all) == count($map[$leaf->item_id]->completed);
      return $leaf;
    }

    private static function get_module_stats($intModuleId = null)
    {
      global $wpdb;
      $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
      $usersToGroupsTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
      $parms = [];
      if (empty(self::$parms->group_id)) {
        $sqlBest = <<<SQL
        SELECT
            module_id,
            MAX(score_scaled) AS best_score,
            MIN(score_scaled) AS worst_score,
            AVG(score_scaled) AS average_score
        FROM
            {$progressTable}
        WHERE
            user_id IN (
                SELECT DISTINCT user_id
                FROM {$progressTable}
                WHERE completion_status = 'completed'
                    OR lesson_status IN ('passed', 'failed', 'completed')
            )
        GROUP BY
            module_id
SQL;
        $sqlBestUser = <<<SQL
        LEFT JOIN (
            SELECT
                module_id,
                user_id
            FROM
                {$progressTable}
            WHERE
                score_scaled = (
                    SELECT MAX(score_scaled)
                    FROM {$progressTable}
                    WHERE module_id = {$progressTable}.module_id
                )
        ) best_user ON p.module_id = best_user.module_id
        LEFT JOIN (
            SELECT
                module_id,
                user_id
            FROM
                {$progressTable}
            WHERE
                score_scaled = (
                    SELECT MIN(score_scaled)
                    FROM {$progressTable}
                    WHERE module_id = {$progressTable}.module_id
                )
        ) worst_user ON p.module_id = worst_user.module_id
SQL;
      } else {
        $sqlBest = <<<SQL
        SELECT
            module_id,
            MAX(score_scaled) AS best_score,
            MIN(score_scaled) AS worst_score,
            AVG(score_scaled) AS average_score
        FROM
            {$progressTable} p
        INNER JOIN {$usersToGroupsTable} ug ON p.user_id = ug.user_id AND ug.group_id = %d
        WHERE
            user_id IN (
                SELECT DISTINCT user_id
                FROM {$progressTable}
                WHERE completion_status = 'completed'
                    OR lesson_status IN ('passed', 'failed', 'completed')
            )
        GROUP BY
            module_id
SQL;
        $parms[] = (int)self::$parms->group_id;
      }

      $sql = <<<SQL
        SELECT p.module_id,
               ma.best_score,
               ma.average_score,
               ma.worst_score,
               best_user.user_id best_user,
               worst_user.user_id worst_user,
               COALESCE(passed, 0) AS passed,
               COALESCE(failing, 0) AS failing,
               COUNT(*) AS total_attempts,
               COUNT(DISTINCT CASE
                                  WHEN success_status = 'failed'
                                       OR lesson_status = 'failed' THEN p.user_id
                              END) AS total_failed_users,
               COUNT(DISTINCT p.user_id) AS users,
               COUNT(DISTINCT CASE
                                  WHEN completion_status = 'completed'
                                       OR lesson_status IN('passed', 'completed') THEN p.user_id
                              END) AS completed
        FROM {$progressTable} p
SQL;

      if (!empty(self::$parms->group_id)) {
        $sql .= " INNER JOIN {$usersToGroupsTable} ug ON p.user_id = ug.user_id AND ug.group_id = %d";
        $parms[] = self::$parms->group_id;
      }

      $sql .= <<<SQL
        LEFT JOIN ({$sqlBest}) ma ON p.module_id = ma.module_id
        {$sqlBestUser}
        LEFT JOIN
          (SELECT module_id,
                  COUNT(DISTINCT user_id) AS passed
           FROM {$progressTable}
           WHERE success_status = 'passed'
             OR lesson_status = 'passed'
           GROUP BY module_id) pass ON p.module_id = pass.module_id
        LEFT JOIN
          (SELECT module_id,
                  COUNT(DISTINCT user_id) AS failing
           FROM {$progressTable}
           WHERE user_id NOT IN
               (SELECT DISTINCT user_id
                FROM {$progressTable}
                WHERE success_status = 'passed'
                  OR lesson_status = 'passed' ) GROUP BY module_id) fail ON p.module_id = fail.module_id
SQL;

      $where = [];
      if (!empty($intModuleId)) {
        $where[] = "p.module_id = %d";
        $parms[] = $intModuleId;
      }
      if (!empty(self::$parms->user_id)) {
        $where[] = "p.user_id = %d";
        $parms[] = self::$parms->user_id;
      }

      if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
      }

      $sql .= ' GROUP BY p.module_id';

      if (!empty($parms)) {
        $result = $wpdb->get_results($wpdb->prepare($sql, $parms));
      } else {
        $result = $wpdb->get_results($sql);
      }
      $list = [];
      if (!empty($result)) {
        foreach ($result as $row) {
          $list[$row->module_id] = $row;
        }
      }
      return $list;
    }

    private static function load_item_tree_map()
    {
      global $wpdb;
      $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
      $sql = "SELECT
    item_id,
    SUBSTRING_INDEX(SUBSTRING_INDEX(path, '/', 2), '/', -1) AS tree_id
    FROM {$table}";
      $rows = $wpdb->get_results($sql);
      if (!empty($rows)) {
        foreach ($rows as $row) {
          if (empty($row->tree_id)) {
            self::$item_tree_map[$row->item_id] = (int)$row->item_id;
          } else {
            self::$item_tree_map[$row->item_id] = (int)$row->tree_id;
          }
        }
      }
    }

    private static function build_tree($intItemId)
    {
      $treeId = self::$item_tree_map[$intItemId];
      $items = self::get_items($treeId);
      $map = self::merge_items_modules($items, self::$modules);
      self::$completed_items = [];

      $pathMap = [];
      $list = [];

      foreach ($items as $leaf) {
        $pathMap[$leaf->item_id] = $leaf->name;
        self::process_leaf($leaf, $map);
        $list[] = $leaf;
      }

      foreach ($list as $item) {
        foreach ($item->path->id as $id) {
          if (array_key_exists($id, $pathMap)) {
            $item->path->string[] = $pathMap[$id];
          } else {
            $item->path->string[] = __("Unknown item", "cluevo");
          }
        }
        self::$_items[$item->item_id] = $item;
      }
      $children = [];
      foreach ($list as $key => $child) {
        $children[$key] = self::load_leaf($child->item_id);
      }
      self::$item = self::load_leaf($intItemId);
      return $list;
    }

    private static function load_leaf($leaf)
    {
      $item = self::$_items[$leaf];
      if (!empty($item->children)) {
        foreach ($item->children as $key => $child) {
          if (is_numeric($child)) {
            $item->children[$key] = self::load_leaf($child);
          }
        }
      }
      return $item;
    }

    private static function get_username($intUserId)
    {
      $name = '';
      $parts = [];
      $user = get_userdata($intUserId);
      $opt = self::$settings["cluevo-reports-main-display"]["username"];
      switch ($opt) {
        case "user_login":
          $name = $user->user_login;
          break;
        case "user_email":
          $name = $user->user_email;
          break;
        case "display_name":
          $name = $user->display_name;
          break;
        case "full_name":
        default:
          if (!empty($user->first_name)) {
            $parts[] = $user->first_name;
          }
          if (!empty($user->last_name)) {
            $parts[] = $user->last_name;
          }
          if (!empty($parts)) {
            $name = implode(" ", $parts);
          }
          break;
      }
      if (empty($name)) {
        $user = get_userdata($intUserId);
        $name = $user->user_login;
      }

      return $name;
    }

    public static function add_screen_options()
    {
      if (empty($_GET["page"])) return;
      if (empty($_GET["tab"])) return;
      if ($_GET["tab"] !== CLUEVO_ADMIN_TAB_REPORTS_MAIN) return;

      add_screen_option(
        "cluevo-reports-main-cols",
        [
          'default' => 1,
          'label'   => __("Columns", "cluevo"),
          'value'  => true
        ]
      );

      add_screen_option(
        "cluevo-reports-main-display",
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
      if (!empty($_GET["tab"]) && $_GET["tab"] !== CLUEVO_ADMIN_TAB_REPORTS_MAIN) return $status;
      $status .= '<input type="hidden" name="wp_screen_options[option]" value="cluevo-reports-main-settings" />';
      $cur = get_user_meta(get_current_user_id(), 'cluevo_reports_main_settings', true);
      if (!empty($cur) && !is_array($cur)) {
        $cur = ["cluevo-reports-main-cols" => [], "cluevo-reports-main-display" => ["username" => "full_name"]];
      }
      $status .= '<fieldset class="metabox-prefs cluevo-reports-mains-cols metabox-prefs-cluevo">';
      $status .= '<legend>' . esc_html__("Display Options", "cluevo") . '</legend>';
      $status .= '<label>' . esc_html__("Display Users with", "cluevo") . ' <select ';
      $status .=   'name="wp_screen_options[value][cluevo-reports-main-display][username]" ';
      $status .= '>';
      $opts = [
        "full_name" => __("Full Name", "cluevo"),
        "user_login" => __("Username", "cluevo"),
        "display_name" => __("Display Name", "cluevo"),
        "user_email" => __("E-Mail", "cluevo")
      ];
      foreach ($opts as $key => $value) {
        $status .= '  <option value="' . esc_attr($key) . '"';
        if (!empty($cur['cluevo-reports-main-display']["username"]) && $cur["cluevo-reports-main-display"]["username"] === $key) {
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
        "cluevo-reports-main-cols" => [],
        "cluevo-reports-main-display" => ['username' => 'full_name']
      ];
      if (!empty($value) && is_array($value)) {
        $clean = [];
        foreach ($value as $group => $opt) {
          if ($group === 'cluevo-reports-main-cols') {
            $clean[$group] = [];
          } else if ($group === 'cluevo-reports-main-display') {
            $validUserOpts = ["full_name", "display_name", "user_login", "user_email"];
            if (in_array($opt["username"], $validUserOpts)) {
              $clean[$group]["username"] = sanitize_text_field($opt["username"]);
            }
          }
        }
        return $clean;
      } else {
        return $defaults;
      }
    }
  }
}
