<?php

if (!class_exists('CluevoDatabaseUpdatePage')) {
  class CluevoDatabaseUpdatePage
  {
    private static $tables;
    private static $upgrades;
    private static $remaining;

    private static function init()
    {
      global $wpdb;
      $tables = (object)[
        "modules" => $wpdb->prefix . CLUEVO_DB_TABLE_MODULES,
        "tree" => $wpdb->prefix . CLUEVO_DB_TABLE_TREE,
        "tree_modules" => $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES,
        "users_to_groups" => $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS,
        "parms" => $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS,
        "progress" => $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS,
        "perms" => $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS,
        "tree_deps" => $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES,
        "exp_log" => $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG,
        "comp_areas" => $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS,
        "comps" => $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES,
        "module_comps" => $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES
      ];
      self::$tables = $tables;
      global $wpdb;
      $t = self::$tables;
      self::$upgrades = [
        "1.9.0" => [
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->users_to_groups),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->users_to_groups} WHERE Key_name = 'user_id'"),
                "query" => "ALTER TABLE {$t->users_to_groups} ADD INDEX user_id (user_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->users_to_groups} WHERE Key_name = 'group_id'"),
                "query" => "ALTER TABLE {$t->users_to_groups} ADD INDEX group_id (group_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->modules),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->modules} WHERE Key_name = 'module_id'"),
                "query" => "ALTER TABLE {$t->modules} ADD INDEX module_id (module_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->tree),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree} WHERE Key_name = 'metadata_id'"),
                "query" => "ALTER TABLE {$t->tree} ADD INDEX metadata_id (metadata_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree} WHERE Key_name = 'parent_id'"),
                "query" => "ALTER TABLE {$t->tree} ADD INDEX parent_id (parent_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree} WHERE Key_name = 'path'"),
                "query" => "ALTER TABLE {$t->tree} ADD INDEX path (path)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->perms),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->perms} WHERE Key_name = 'item_id'"),
                "query" => "ALTER TABLE {$t->perms} ADD INDEX item_id (item_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->perms} WHERE Key_name = 'perm'"),
                "query" => "ALTER TABLE {$t->perms} ADD INDEX perm (perm)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->tree_deps),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree_deps} WHERE Key_name = 'item_id'"),
                "query" => "ALTER TABLE {$t->tree_deps} ADD INDEX item_id (item_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree_deps} WHERE Key_name = 'dep_id'"),
                "query" => "ALTER TABLE {$t->tree_deps} ADD INDEX dep_id (dep_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->tree_modules),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->tree_modules} WHERE Key_name = 'module_id'"),
                "query" => "ALTER TABLE {$t->tree_modules} ADD INDEX module_id (module_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->exp_log),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->exp_log} WHERE Key_name = 'user_id'"),
                "query" => "ALTER TABLE {$t->exp_log} ADD INDEX user_id (user_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->comps),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->comps} WHERE Key_name = 'metadata_id'"),
                "query" => "ALTER TABLE {$t->comps} ADD INDEX metadata_id (metadata_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->comp_areas),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->comp_areas} WHERE Key_name = 'metadata_id'"),
                "query" => "ALTER TABLE {$t->comp_areas} ADD INDEX metadata_id (metadata_id)",
                "result" => null
              ]
            ]
          ],
          (object)[
            "description" => sprintf(__("Optimize '%s'", "cluevo"), $t->module_comps),
            "statements" => [
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->module_comps} WHERE Key_name = 'module_id'"),
                "query" => "ALTER TABLE {$t->module_comps} ADD INDEX module_id (module_id)",
                "result" => null
              ],
              (object)[
                "description" => __("Add Index", "cluevo"),
                "status" => $wpdb->get_row("SHOW INDEX FROM {$t->module_comps} WHERE Key_name = 'competence_id'"),
                "query" => "ALTER TABLE {$t->module_comps} ADD INDEX competence_id (competence_id)",
                "result" => null
              ]
            ]
          ]
        ]
      ];
      self::$remaining = array_filter(self::$upgrades, function ($upgrade) {
        foreach ($upgrade as $group) {
          foreach ($group->statements as $stmt) {
            if (empty($stmt->status)) return true;
          }
        }
      });
      @set_time_limit(0);
    }

    public static function render()
    {
      wp_register_script(
        "cluevo-db-upgrade",
        plugins_url("/js/database-upgrade-page.js", plugin_dir_path(__FILE__)),
        "",
        CLUEVO_VERSION,
        true
      );
      if (function_exists('wp_set_script_translations')) {
        wp_set_script_translations('cluevo-db-upgrade', 'cluevo', plugin_dir_path(__DIR__) . 'lang');
      }
      wp_enqueue_script("cluevo-db-upgrade");
      self::init();
      echo '<div class="cluevo-admin-page">';
      echo '<h1>' . esc_html__("Database Administration", "cluevo") . '</h1>';
      $curDatabaseVersion = get_option(CLUEVO_DB_VERSION_OPT_KEY);
      if (empty(self::$remaining) && $curDatabaseVersion != CLUEVO_PLUGIN_DB_VERSION) {
        cluevo_create_database();
        self::init();
      }
      self::list_upgrades();
      echo '</div>';
    }

    public static function handle()
    {
      if (!empty($_GET) && check_admin_referer('cluevo-run-db-upgrade-nonce', 'nonce')) {
        if (!isset($_GET["action"]) || !wp_verify_nonce($_GET['nonce'], 'cluevo-run-db-upgrade-nonce')) {
          die("invalid nonce");
        }
        if (!current_user_can("administrator")) {
          die("no admin");
        }
        $version = sanitize_text_field($_GET["version"]);
        $group = (int)$_GET["group"];
        $stmt = (int)$_GET["stmt"];
        self::init();
        $result = self::run($version, $group, $stmt);
        die(wp_json_encode($result));
      }
    }

    private static function run($strVersion, $intGroup, $intStmt = null)
    {
      global $wpdb;
      if (empty(self::$upgrades[$strVersion][$intGroup])) return;
      $group = self::$upgrades[$strVersion][$intGroup];
      if ($intStmt === null) {
        foreach ($group->statements as $sid => $stmt) {
          if (!empty($stmt->status)) {
            $stmt->result = 1;
          } else {
            $stmt->result = $wpdb->query($stmt->query);
          }
        }
        return $group;
      } else {
        if (empty(self::$upgrades[$strVersion][$intGroup]->statements[$intStmt])) return;
        $stmt = self::$upgrades[$strVersion][$intGroup]->statements[$intStmt];
        if (empty($stmt->status)) {
          $stmt->result = $wpdb->query($stmt->query);
        }
        return $stmt;
      }
    }

    private static function list_upgrades()
    {
      echo '<div id="cluevo-db-upgrade-notice-container" style="display: none;">';
      cluevo_display_notice("", "", "");
      echo '</div>';
      if (!empty(self::$remaining)) {
        cluevo_display_notice(
          __("Warning", "cluevo"),
          __("Please make sure to backup your database before performing any database updates.", "cluevo"),
          'error'
        );
        $html = '';
        $versionDone = false;
        foreach (self::$remaining as $version => $list) {
          if ($versionDone) {
            ob_start();
            cluevo_display_notice(
              __("Information", "cluevo"),
              __("There are more tasks pending but the current upgrade must be finished before continuing. Refresh this page after all tasks have run continue the process.", "cluevo")
            );
            $html .= ob_get_clean();
            break;
          } else {
            $button = '<button
                         type="button"
                         class="button button-primary cluevo-run-all-upgrades"
                         style="float: right;"
                       >' . esc_html__("Run All", "cluevo") . '</button>';
            $html .= '<h2>' . esc_html(sprintf(_n("%s: %d Task", "%s: %d Tasks", count($list), "cluevo"), $version, count($list))) . $button . '</h2>';
            $html .= '<table class="wp-list-table widefat striped">';
            $html .= '  <thead>';
            $html .= '    <tr>';
            $html .= '      <th>' . esc_html__("Description", "cluevo") . '</th>';
            $html .= '      <th>#</th>';
            $html .= '      <th>' . esc_html__("Description", "cluevo") . '</th>';
            $html .= '      <th>' . esc_html__("Status", "cluevo") . '</th>';
            $html .= '    </tr>';
            $html .= '  </thead>';
            $html .= '    <tbody>';
            $lastGid = null;

            foreach ($list as $gid => $group) {
              foreach ($group->statements as $sid => $stmt) {
                $html .= '    <tr>';
                $html .= '      <td>' . ($gid !== $lastGid ? esc_html($group->description) : '') . '</td>';
                $html .= '      <td>' . ($sid + 1) . ' / ' . count($group->statements) . '</td>';
                $html .= '      <td>' . esc_html($stmt->description) . '</td>';
                $html .= '      <td>';
                if (!empty($stmt->status)) {
                  $html .= '      <span class="dashicons dashicons-saved"></span>';
                } else {
                  $html .= '      <a href="';
                  $html .= esc_url(add_query_arg([
                    "action" => "cluevo-run-db-upgrade",
                    "version" => $version,
                    "group" => $gid,
                    "stmt" => $sid,
                    "nonce" => wp_create_nonce('cluevo-run-db-upgrade-nonce')
                  ], admin_url('admin-post.php')));
                  $html .= '"
                class="button cluevo-db-upgrade-link"
                data-version="' . esc_attr($version) . '"
                data-group="' . esc_attr($gid) . '"
                data-stmt="' . esc_attr($sid) . '"
              >' . esc_html__("Run", "cluevo") . '</a>';
                }
                $html .= '      </td>';
                $html .= '    </tr>';
                $lastGid = $gid;
              }
            }
            $html .= '    </tbody>';
            $html .= '    <tfoot>';
            $html .= '      <tr>';
            $html .= '        <td></td>';
            $html .= '        <td></td>';
            $html .= '        <td></td>';
            $html .= '        <td>';
            $html .= '          <button
                            type="button"
                            class="button button-primary cluevo-run-all-upgrades"
                          >' . esc_html__("Run All", "cluevo") . '</button>';
            $html .= '        </td>';
            $html .= '      </tr>';
            $html .= '    </tfoot>';
            $html .= '  </table>';
            $versionDone = true;
          }
        }
        echo wp_kses($html, wp_kses_allowed_html("post"));
      } else {
        cluevo_display_notice(
          __("Information", "cluevo"),
          __("All tasks have been completed.", "cluevo"),
          "success"
        );
      }
    }
  }
}
