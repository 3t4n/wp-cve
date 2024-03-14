<?php

require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");

class CluevoTree
{

  public $user_id;
  public $user;
  public $item;
  public $item_id;
  public $children = [];

  private $_items = [];
  private $permissions = [];
  private $completed_items = [];
  private $completed_modules = [];
  private $modules = [];
  private $competences = [];
  private $competence_areas = [];
  private $best_progress = [];
  private $last_progress = [];
  private $ratings = [];

  private static $post_map = [];
  private static $item_tree_map = [];
  private static $cache = [];

  public function __construct($intItemId, $intUserId = null)
  {
    if (empty(self::$item_tree_map)) {
      self::load_item_tree_map();
    }
    if (empty(self::$post_map)) {
      self::load_post_map();
    }
    if (empty($intUserId) || !is_numeric($intUserId)) {
      $this->user_id = get_current_user_id();
    } else {
      $this->user_id = (int)$intUserId;
    }
    if (!empty($this->user_id)) {
      $this->user = cluevo_turbo_get_user($this->user_id);
      $this->load_best_progress();
      $this->load_last_progress();
      $this->load_completed_modules();
      $this->ratings = cluevo_turbo_get_ratings($intUserId);
    }
    $this->item_id = (int)$intItemId;
    $this->load_used_modules();
    $this->load_permissions();
    $this->load_competences();
    $this->build_tree();
    if (!empty($this->user_id)) {
      if (empty(self::$cache[$this->user_id])) {
        self::$cache[$this->user_id] = [];
      }
      self::$cache[$this->user_id][$intItemId] = $this;
    }
  }

  public static function clear_cache($intUserId = null)
  {
    $userId = empty($intUserId) ? get_current_user_id() : (int)$intUserId;
    if ($intUserId === -1) {
      self::$cache = [];
    }
    if (empty(self::$cache[$userId])) return;
    unset(self::$cache[$userId]);
  }

  public function __get($prop)
  {
    switch ($prop) {
      case "items":
        return $this->children;
      default:
        if (property_exists($this, $prop))
          return $this->$prop;
    }
  }

  public static function load($intItemId, $intUserId = null)
  {
    if (empty($intUserId) || !is_numeric($intUserId)) {
      $userId = get_current_user_id();
    } else {
      $userId = (int)$intUserId;
    }
    if (!empty($userId) && !empty(self::$cache[$userId][$intItemId])) {
      return self::$cache[$userId][$intItemId];
    }
    return new CluevoTree($intItemId, $intUserId);
  }

  public static function load_all($intUserId = null)
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $sql = "SELECT item_id FROM {$table} WHERE parent_id = 0";
    $ids = $wpdb->get_col($sql);
    if (empty($ids)) return [];
    $trees = [];
    foreach ($ids as $id) {
      $t = self::load($id, $intUserId);
      if (empty($t) || empty($t->item)) continue;
      if (empty($t->item->access_level)) continue;
      $trees[] = $t;
    }
    // cluevo_print_r($trees[0]->item, [
    // "item_id",
    // "name",
    // "access",
    // "access_status",
    // "completed",
    // "completed_children",
    // "children"
    // ], true);
    return $trees;
  }

  public static function find_item($intItemId, $intUserId = null)
  {
    $userId = (!empty($intUserId)) ? (int)$intUserId : get_current_user_id();
    if (empty(self::$item_tree_map)) self::load_item_tree_map();
    if (empty(self::$item_tree_map[$intItemId])) return null;
    $tree = self::load(self::$item_tree_map[$intItemId], $userId);
    if (!empty($tree->item->item_id) && (int)$tree->item->item_id === (int)$intItemId) {
      return $tree->item;
    } else {
      return $tree->get_item($intItemId, $userId);
    }
  }

  public static function find_item_by_post_id($intPostId, $intUserId)
  {
    if (empty(self::$post_map[$intPostId])) return null;
    $itemId = self::$post_map[$intPostId];
    if (empty($itemId)) return null;
    return self::find_item($itemId, $intUserId);
  }

  public static function find_children($intItemId, $intUserId = null)
  {
    $item = self::find_item($intItemId, $intUserId);
    if (empty($item->children)) return [];
    return $item->children;
  }

  public function get_current_item()
  {
    $post_id = get_the_ID();
    if (empty($post_id)) return null;
    if (empty(self::$post_map[$post_id])) return null;
    return $this->get_item(self::$post_map[$post_id]);
  }

  /**
   * Builds the tree from raw items and modules.
   * Resolves dependencies and access
   *
   * @access private
   * @return void
   */
  private function build_tree()
  {
    // 1. Get all items of the tree
    // 2. Get all modules that are used in the tree
    // 3. Create a map with all items and all modules each item contains
    $items = $this->get_items();
    $map = $this->merge_items_modules($items, $this->modules);
    $this->completed_items = [];

    $pathMap = [];
    $list = [];
    // iterate through all items and resolve permissions and completion
    foreach ($items as $leaf) {
      $pathMap[$leaf->item_id] = $leaf->name;
      $this->process_leaf($leaf, $map);
      if ($leaf->access_level > 0) {
        $list[] = $leaf;
      }
    }
    $list = $this->resolve_dependencies($list);
    foreach ($list as $item) {
      foreach ($item->path->id as $id) {
        if (array_key_exists($id, $pathMap)) {
          $item->path->string[] = $pathMap[$id];
        } else {
          $item->path->string[] = __("Unknown item", "cluevo");
        }
      }
      $cluevoItem = CluevoItem::from_std_class($item);
      if (!empty($cluevoItem->module->module_id)) {
        $cluevoItem->load_module();
      }
      $this->_items[$item->item_id] = $cluevoItem;
    }
    foreach ($this->children as $key => $child) {
      $c = $this->load_leaf($child);
      if (empty($c)) {
        unset($item->children[$key]);
        continue;
      }
      $this->children[$key] = $c;
    }
    $this->item = $this->load_leaf($this->item_id);
  } // End function build_tree

  private function load_leaf($leaf)
  {
    if (empty($this->_items[$leaf])) return null;
    $item = $this->_items[$leaf];
    if (!empty($item->children)) {
      foreach ($item->children as $key => $child) {
        $c = $this->load_leaf($child);
        if (empty($c)) {
          unset($item->children[$key]);
          continue;
        }
        $item->children[$key] = $c;
      }
    }
    return $item;
  }

  private function process_leaf($leaf, $map)
  {
    $path = array_values(array_filter(explode('/', $leaf->path), function ($value) {
      return trim($value) !== '';
    }));
    if (empty($path) || !is_array($path)) {
      $path = [];
    }
    $path[] = $leaf->item_id;
    $leaf->path = (object)["id" => $path, "string" => []];

    $leaf->modules = $map[$leaf->item_id]->all;
    $leaf->competences = $map[$leaf->item_id]->competences;
    $leaf->competence_areas = $map[$leaf->item_id]->competence_areas;

    $this->resolve_access($leaf);
    $this->process_children($leaf);

    // $leaf->completed = false;
    $leaf->completed = !empty($leaf->access) && !empty($map[$leaf->item_id]->completed) && count($map[$leaf->item_id]->all) == count($map[$leaf->item_id]->completed);

    if (!empty($leaf->access) && $leaf->access_level > 0 && $leaf->completed) {
      $this->completed_items[] = (int)$leaf->item_id;
    }
    return $leaf;
  }

  private function process_children($leaf)
  {
    if (empty($leaf->completed_children) || !is_array($leaf->completed_children)) {
      $leaf->completed_children = [];
    }
    if (!empty($leaf->children)) {
      foreach ($leaf->children as $child) {
        if (in_array((int)$child, $this->completed_items)) {
          $leaf->completed_children[] = $child;
        }
        $children = array_filter($leaf->children, function($c) {
          return !empty($c->access);
        });
        if (!empty($leaf->access) && count($children) <= count($leaf->completed_children)) {
          $leaf->completed = true;
        }
      }
    }
  }

  private function resolve_access($leaf)
  {
    $leaf->access_status = ["dependencies" => true, "points" => true, "level" => true, "access_level" => false];
    $this->resolve_permissions($leaf);
    if (!empty((int)$leaf->level_required)) {
      $leaf->access_status["level"] = !empty($this->user->total_points) && (int)$leaf->level_required <= (int)$this->user->current_level;
    } else {
      $leaf->access_status["level"] = true;
    }
    if (!empty((int)$leaf->points_required)) {
      $leaf->access_status["points"] = !empty($this->user->total_points) && (int)$leaf->points_required <= (int)$this->user->total_points;
    } else {
      $leaf->access_status["points"] = true;
    }
    $leaf->access = true;
    foreach ($leaf->access_status as $type => $value) {
      if (!user_can($this->user_id, "administrator") && empty($value) || ($type === 'access_level' && $value < 2)) {
        $leaf->access = false;
        break;
      }
    }
    return $leaf;
  }

  private function resolve_permissions($leaf)
  {
    if (!empty($this->permissions[$leaf->item_id])) {
      $leaf->permission = $this->permissions[$leaf->item_id];
    } else {
      $leaf->permission = (object)[
        'level' => !user_can($this->user_id, "administrator") ? 0 : 999,
        'trainer' => 0,
        'expired' => null
      ];
    }
    $level = !user_can($this->user_id, "administrator") ? $leaf->permission->level : 999;
    $leaf->access_level = $level;
    $leaf->access_status["access_level"] = $level;
    $leaf->expires = $leaf->permission->expired;
    $leaf->is_trainer = $leaf->permission->trainer;
    return $leaf;
  }

  /**
   * Returns an item from the tree
   *
   * @param int $intItemId
   * @access public
   * @return CluevoItem|null
   */
  public function get_item($intItemId)
  {
    if (!empty($this->_items[$intItemId])) {
      if (!$this->_items[$intItemId]->settings_loaded) {
        $this->_items[$intItemId]->load_settings();
      }
      if (!empty($this->_items[$intItemId]->module_id)) {
        $this->_items[$intItemId]->load_module();
      }
      return $this->_items[$intItemId];
    }
    return null;
  } // End function get_item

  /**
   * Resolves dependency status by checking each items against the completed items.
   * Also resolves the access status for dependencies.
   *
   * @param stdClass[] $items
   * @access private
   * @return stdClass[]
   */
  private function resolve_dependencies($items)
  {
    foreach ($items as $item) {
      $item->dependencies = json_decode(json_encode([
        'modules' => ['normal' => (object)[], 'blocked' => (object)[], 'inherited' => (object)[], 'all' => (object)[]],
        'other' => ['normal' => (object)[], 'blocked' => (object)[], 'inherited' => (object)[], 'all' => (object)[]]
      ]));
      if (!empty($item->normal_dependencies)) {
        $normal = explode(',', $item->normal_dependencies);
        if (!empty($normal)) {
          foreach ($normal as $id) {
            if (empty($id)) continue;
            $val = in_array($id, $this->completed_items);
            if (!$val) $item->access_status["dependencies"] = false;
            $item->dependencies->other->normal->{$id} = $val;
            $item->dependencies->other->all->{$id} = $val;
          }
        }
      }
      if (!empty($item->inherited_dependencies)) {
        $inherited = explode(',', $item->inherited_dependencies);
        if (!empty($inherited)) {
          foreach ($inherited as $id) {
            if (empty($id)) continue;
            $val = in_array($id, $this->completed_items);
            if (!$val) $item->access_status["dependencies"] = false;
            $item->dependencies->other->inherited->{$id} = $val;
            $item->dependencies->other->all->{$id} = $val;
          }
        }
      }
      if (!empty($item->blocked_dependencies)) {
        $blocked = explode(',', $item->blocked_dependencies);
        if (!empty($blocked)) {
          foreach ($blocked as $id) {
            if (empty($id)) continue;
            $item->dependencies->other->blocked->{$id} = in_array($id, $this->completed_items);
          }
        }
      }
      if (!empty((array)$item->dependencies->other->all) && empty($item->access_status["dependencies"])) {
        $item->access = false;
      }
    }
    return $items;
  } // End function resolve_dependencies

  /**
   * Returns a list of all items of a tree.
   *
   * @access private
   * @return stdClass[]
   */
  private function get_items()
  {
    global $wpdb;
    $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
    $treeModulesTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
    $sql = "SELECT t.*, tm.module_id,
      GROUP_CONCAT(DISTINCT t2.item_id) AS children,
      GROUP_CONCAT(CASE WHEN d.dep_type = 'normal' THEN d.dep_id END) AS normal_dependencies,
      GROUP_CONCAT(CASE WHEN d.dep_type = 'inherited' THEN d.dep_id END) AS inherited_dependencies,
      GROUP_CONCAT(CASE WHEN d.dep_type = 'blocked' THEN d.dep_id END) AS blocked_dependencies
    FROM {$treeTable} t
    LEFT JOIN {$depTable} d ON t.item_id = d.item_id
    LEFT JOIN {$treeTable} t2 ON t.item_id = t2.parent_id
    LEFT JOIN {$treeModulesTable} tm ON t.item_id = tm.item_id
    WHERE t.path LIKE CONCAT('%/', %d, '/%') OR t.item_id = %d
    GROUP BY t.item_id
    ORDER BY t.level DESC, t.sort_order ASC";
    $result = $wpdb->get_results($wpdb->prepare($sql, [$this->item_id, $this->item_id]));
    foreach ($result as $key => $value) {
      $value->module = $value->module_id;
      if (!empty($this->ratings)) {
        if (!empty($this->ratings["post"]["cluevo-module-rating-avg-" . $value->module_id])) {
          $value->rating_avg = $this->ratings["post"]["cluevo-module-rating-avg-" . $value->module_id];
        }
        if (!empty($this->ratings["user"]["cluevo-module-rating-" . $value->module_id])) {
          $value->rating_user = $this->ratings["user"]["cluevo-module-rating-" . $value->module_id];
        }
      }
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
  } // End function get_items

  /**
   * Returns a list of modules used throughout a tree.
   *
   * @param int|null $intItemId
   * @access private
   * @return stdClass[]
   */
  private function load_used_modules($intItemId = null)
  {
    global $wpdb;
    $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $treeModuleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
    $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
    $moduleTypesTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
    $sql = "SELECT m.*, t.item_id, t.parent_id, t.path, t.name, mt.type_name
    FROM {$moduleTable} m
    INNER JOIN {$moduleTypesTable} mt ON m.type_id = mt.type_id
    INNER JOIN {$treeModuleTable} tm ON m.module_id = tm.module_id
    INNER JOIN {$treeTable} t ON tm.item_id = t.item_id";
    $parms = [];
    if (!empty($intItemId)) {
      $sql .= " WHERE t.path LIKE CONCAT('%/', %d, '/%')";
      $parms = [(int)$intItemId];
      $modules = $wpdb->get_results($wpdb->prepare($sql, $parms));
    } else {
      $modules = $wpdb->get_results($sql);
    }
    $this->modules = [];
    if (!empty($modules)) {
      foreach ($modules as $m) {
        $m->competences = [];
        $m->competence_areas = [];
        $m->best_attempt = null;
        $m->last_attempt = null;
        if (!empty($this->best_progress[$m->module_id])) {
          $m->best_attempt = $this->best_progress[$m->module_id];
        }
        if (!empty($this->last_progress[$m->module_id])) {
          $m->last_attempt = $this->last_progress[$m->module_id];
        }
        if (empty($this->modules[$m->module_id])) {
          $m->items = [$m->item_id];
          $m->parents = [$m->parent_id];
          $m->paths = [$m->path . $m->item_id . "/"];
          $this->modules[$m->module_id] = $m;
        } else {
          $this->modules[$m->module_id]->items[] = $m->item_id;
          if (!in_array($m->parent_id, $this->modules[$m->module_id]->parents)) {
            $this->modules[$m->module_id]->parents[] = $m->parent_id;
          }
          $this->modules[$m->module_id]->paths[] = $m->path . $m->item_id . "/";
        }
      }
    }
  } // End function load_used_modules

  /**
   * Merges items and modules so each item has a list of modules that are contained within it.
   *
   * @param stdClass[] $items
   * @param stdClass[] $modules
   * @access private
   * @return stdClass[]
   */
  private function merge_items_modules($items, $modules)
  {
    $results = [];
    $allIds = array_column($items, "item_id");
    if (!empty($allIds)) {
      foreach ($allIds as $id) {
        $results[$id] = (object)["all" => [], "completed" => [], "competences" => [], "competence_areas" => []];
      }
    }
    foreach ($modules as $row) {
      $moduleId = (int)$row->module_id;
      foreach ($row->paths as $completePath) {
        $path = explode('/', $completePath);
        if (!empty($path)) {
          foreach ($path as $id) {
            if (!array_key_exists($id, $results)) continue;
            if (!in_array($moduleId, $results[$id]->all)) {
              $results[$id]->all[] = $moduleId;
            }
            if (in_array($moduleId, $this->completed_modules) && !in_array($moduleId, $results[$id]->completed)) {
              $results[$id]->completed[] = $moduleId;
            }
            if (!empty($this->modules[$moduleId]->competences)) {
              foreach ($this->modules[$moduleId]->competences as $c) {
                if (!empty($this->competences[$c->competence_id])) {
                  $results[$id]->competences[$c->competence_id] = $this->competences[$c->competence_id];
                }
              }
            }
            if (!empty($this->modules[$moduleId]->competence_areas)) {
              foreach ($this->modules[$moduleId]->competence_areas as $c) {
                if (!empty($this->competence_areas[$c->competence_area_id])) {
                  $results[$id]->competence_areas[$c->competence_area_id] = $this->competence_areas[$c->competence_area_id];
                }
              }
            }
          }
        }
      }
    }
    return $results;
  } // End function merge_items_modules

  /**
   * Loads the permissions for the user
   *
   * @access private
   * @return void
   */
  private function load_permissions()
  {
    $email = null;
    if (!empty($this->user->user_email)) {
      $email = substr($this->user->user_email, strpos($this->user->user_email, '@'));
    }

    global $wpdb;
    $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
    $userGroupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
    $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;

    $args = [];
    if (!empty($this->user_id)) {
      $sql = "SELECT
        item_id,
        MAX(access_level) AS access_level,
        MAX(COALESCE(utg.is_trainer, 0)) AS is_trainer,
        MAX(tp.date_expired) AS date_expired
      FROM
        {$permTable} tp
      LEFT JOIN {$userGroupTable} utg ON utg.user_id = %d AND tp.perm = CONCAT('g:', utg.group_id)
      WHERE perm = CONCAT('u:', %d) OR CONCAT(tp.item_id, ':', perm) IN (
        SELECT CONCAT(tp.item_id, ':', perm)
        FROM {$permTable} tp
        INNER JOIN {$userGroupTable} utg ON utg.user_id = %d AND CONCAT('g:', utg.group_id) = tp.perm
        WHERE tp.date_expired IS NULL OR tp.date_expired >= NOW()
      ) OR CONCAT(tp.item_id, ':', perm) IN (
        SELECT CONCAT(tp.item_id, ':', perm)
        FROM {$permTable} tp
        LEFT JOIN $groupTable g ON tp.perm = CONCAT('g:', g.group_id)
        WHERE g.group_name = %s AND (tp.date_expired IS NULL OR tp.date_expired >= NOW())
      )
      GROUP BY item_id";
      $args = [$this->user_id, $this->user_id, $this->user_id, $email];
    } else {
      $sql = "SELECT item_id, MAX(access_level) AS access_level, MAX(tp.date_expired) AS date_expired FROM
        {$permTable} tp WHERE perm = CONCAT('g:', %d)
        GROUP BY item_id";
      $args = [CLUEVO_DEFAULT_GROUP_GUEST];
    }

    $results = $wpdb->get_results(
      $wpdb->prepare($sql, $args)
    );

    $perms = [];
    if (!empty($results)) {
      foreach ($results as $row) {
        $perms[$row->item_id] = (object)[
          'level' => !user_can($this->user_id, "administrator") ? (int)$row->access_level : 999,
          'trainer' => (!empty($row->is_trainer)) ? (int)$row->is_trainer : false,
          'expired' => !empty($row->date_expired) ? strtotime($row->date_expired) : null,
        ];
      }
    }
    $this->permissions = $perms;
  } // End function load_permissions

  private function load_competences()
  {
    global $wpdb;
    $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
    $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
    $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
    $modulesToCompetences = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

    $sqlComps = "SELECT
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
      INNER JOIN {$compTable} c ON cm.competence_id = c.competence_id";

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
        if (!empty($this->modules[$c->module_id])) {
          $module = $this->modules[$c->module_id];
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
      $this->competences = $list;
    }

    $sqlAreas = "SELECT 
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
      GROUP BY ca.competence_area_id, ca.competence_area_name, ca.metadata_id";

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
              if (empty($this->competences[$c]->score)) continue;
              $totalScore += $this->competences[$c]->score;
            }
            $area->score = $totalScore / count($area->competences);
          }
          if (!empty($area->modules)) {
            $area->modules = explode(",", $area->modules);
            if (!empty($area->modules)) {
              foreach ($area->modules as $m) {
                if (!empty($this->modules[$m])) {
                  $this->modules[$m]->competence_areas[$area->competence_area_id] = $area;
                }
              }
            }
          } else {
            $area->modules = [];
          }
        }
      }
      $this->competence_areas = $list;
    }
  }

  /**
   * Loads the best progress records for modules
   *
   * @access private
   * @return void
   */
  private function load_best_progress()
  {
    if (empty($this->user_id)) return;
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    $sql = " SELECT mp.*
      FROM {$table} mp
      INNER JOIN (
          SELECT user_id, module_id, MAX(score_scaled) AS best_score
          FROM {$table}
          WHERE user_id = %d AND credit = 'credit'
            AND (lesson_status = 'passed' OR (completion_status = 'completed' AND success_status = 'passed'))
          GROUP BY user_id, module_id
      ) AS best_scores
      ON mp.user_id = best_scores.user_id
         AND mp.module_id = best_scores.module_id
         AND mp.score_scaled = best_scores.best_score";
    $results = $wpdb->get_results($wpdb->prepare($sql, [$this->user_id]));

    $this->best_progress = [];
    if (!empty($results) && is_array($results)) {
      foreach ($results as $row) {
        $this->best_progress[$row->module_id] = $row;
      }
    }
  } // End function load_best_progress

  /**
   * Loads the latest progress records for all modules
   *
   * @access private
   * @return void
   */
  private function load_last_progress()
  {
    if (empty($this->user_id)) return;
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    $sql = "SELECT mp.*
      FROM {$table} mp JOIN (
          SELECT user_id, module_id, MAX(date_modified) AS latest_date
          FROM {$table}
          WHERE user_id = %d
          GROUP BY user_id, module_id
      ) AS latest_dates
      ON mp.user_id = latest_dates.user_id
         AND mp.module_id = latest_dates.module_id
         AND mp.date_modified = latest_dates.latest_date";
    $results = $wpdb->get_results($wpdb->prepare($sql, [$this->user_id]));

    $this->last_progress = [];
    if (!empty($results) && is_array($results)) {
      foreach ($results as $row) {
        $this->last_progress[$row->module_id] = $row;
      }
    }
  } // End function load_last_progress

  private function load_completed_modules()
  {
    $this->completed_modules = cluevo_get_users_completed_modules($this->user_id);
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

  private static function load_post_map()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $sql = "SELECT item_id, metadata_id FROM {$table}";
    $rows = $wpdb->get_results($sql);
    if (!empty($rows)) {
      $ids = array_column($rows, "metadata_id");
      if (!empty($ids)) {
        get_posts(["include" => $ids, "post_type" => CLUEVO_METADATA_POST_TYPE]);
      }
      foreach ($rows as $row) {
        if (empty($row->metadata_id)) {
          self::$post_map[$row->metadata_id] = (int)$row->item_id;
        } else {
          self::$post_map[$row->metadata_id] = (int)$row->item_id;
        }
      }
    }
  }
}
