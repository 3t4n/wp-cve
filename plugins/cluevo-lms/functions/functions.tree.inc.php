<?php
if (!defined("CLUEVO_ACTIVE")) exit;

/**
 * Updates the given item in the database
 *
 * @param CluevoItem $item
 *
 * @return int|false
 */
function cluevo_update_learning_structure_item($item, $boolSkipDeps = false)
{
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $path = cluevo_get_item_path($item->parent_id) . "/";
  $sql = "UPDATE $treeTable SET
    parent_id = %d,
    metadata_id = %d,
    level = %d,
    name = %s,
    path = %s,
    sort_order = %d,
    points_worth = %d,
    points_required = %d,
    practice_points = %d,
    level_required = %d,
    login_required = %d,
    published = %d
    WHERE item_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare(
      $sql,
      array(
        $item->parent_id,
        $item->metadata_id,
        $item->level,
        sanitize_text_field($item->name),
        $path,
        $item->sort_order,
        $item->points_worth,
        $item->points_required,
        $item->practice_points,
        $item->level_required,
        $item->login_required,
        $item->published,
        $item->item_id
      )
    )
  );

  $parentMetaId = null;
  if (!empty($item->parent_id)) {
    $parent = cluevo_get_learning_structure_item($item->parent_id);
    if (!empty($parent)) $parentMetaId = $parent->metadata_id;
  }
  cluevo_update_metadata_page($item, $parentMetaId);

  if (!$boolSkipDeps) {
    cluevo_create_learning_structure_item_dependencies($item);
    if (!empty($item->module_id) && $item->module_id > 0) {
      cluevo_create_module_dependencies($item);
    }
  }

  if (!empty($item->module_id) && $item->module_id > 0) {
    cluevo_create_learning_structure_module_item($item->item_id, $item->module_id, $item->display_mode);
  }

  return $result;
}

function cluevo_save_learning_structure_item_settings($item)
{
  if (!empty($item->settings) && is_array($item->settings)) {
    foreach ($item->settings as $key => $value) {
      if (empty($value)) {
        delete_post_meta($item->metadata_id, CLUEVO_META_DATA_PREFIX . $key);
      } else {
        update_post_meta($item->metadata_id, CLUEVO_META_DATA_PREFIX . $key, $value);
      }
    }
  }
}

/**
 * Links a tree item id with a module id in the database
 *
 * @param int $intItemId
 * @param int $intModuleId
 *
 * @return int|false
 */
function cluevo_create_learning_structure_module_item($intItemId, $intModuleId, $strDisplayMode = "")
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;

  $sql = "INSERT INTO $table SET item_id = %d, module_id = %d, display_mode = %s ON DUPLICATE KEY UPDATE module_id = %d, display_mode = %s";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId, $intModuleId, sanitize_key($strDisplayMode), $intModuleId, sanitize_key($strDisplayMode)]));
}

function cluevo_remove_learning_structure_module_item($intItemId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;

  $sql = "DELETE FROM $table WHERE item_id = %d";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId]));
}

/**
 * Writes item dependencies to the database
 *
 * @param CluevoItem $item
 *
 * @return void
 */
function cluevo_create_learning_structure_item_dependencies($item)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;

  $wpdb->query(
    $wpdb->prepare("DELETE FROM $depTable WHERE item_id = %d", [$item->item_id])
  );
  $deps = json_decode(json_encode($item->dependencies), true);

  if (!empty($deps)) {
    foreach ($deps["other"] as $type => $deps) {
      foreach ($deps as $d => $access) {
        cluevo_create_learning_structure_item_dependency($item->item_id, $d, $type);
      }
    }
  }
}

/**
 * Writes an item dependency to the database
 *
 * @param int $intItemId
 * @param int $intDepId
 * @param string $strType Can be normal, inherited or blocked
 *
 * @return int|false
 */
function cluevo_create_learning_structure_item_dependency($intItemId, $intDepId, $strType)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;

  $sql = "INSERT IGNORE INTO $depTable SET item_id = %d, dep_id = %d, dep_type = %s";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId, $intDepId, $strType]));
}

/**
 * Creates module dependency entries in the database for given item
 *
 * @param CluevoItem $item
 *
 * @return void
 */
function cluevo_create_module_dependencies($item)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;

  $wpdb->query(
    $wpdb->prepare(
      "DELETE FROM $depTable WHERE module_id = %d",
      [$item->module_id]
    )
  );

  $dependencies = json_decode(json_encode($item->dependencies), true);

  if (!empty($dependencies)) {
    foreach ($dependencies['modules'] as $type => $deps) {
      foreach ($deps as $d => $act) {
        cluevo_create_module_dependency_db_entry($item->module_id, $d, $type);
      }
    }
  }
}

/**
 * Writes module dependency to the database
 *
 * @param int $intItemId
 * @param int $intDepId
 * @param string $strType Can be either normal, inherited of blocked
 *
 * @return int|false
 */
function cluevo_create_module_dependency_db_entry($intItemId, $intDepId, $strType)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;

  $sql = "INSERT IGNORE INTO $depTable SET module_id = %d, dep_id = %d, dep_type = %s";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId, $intDepId, $strType]));
}

/**
 * Removes an item dependency
 *
 * @param int $intItemId
 * @param int $intDepId
 *
 * @return int|false
 */
function cluevo_remove_learning_structure_item_dependency($intItemId, $intDepId)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;

  $sql = "DELETE FROM $depTable WHERE item_id = %d AND dep_id = %d";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId, $intDepId]));
}

/**
 * Removes a module dependency
 *
 * @param int $intItemId
 * @param int $intDepId
 *
 * @return int|false
 */
function cluevo_remove_module_dependency($intItemId, $intDepId)
{
  global $wpdb;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;

  $sql = "DELETE FROM $depTable WHERE module_id = %d AND dep_id = %d";
  return $wpdb->query($wpdb->prepare($sql, [$intItemId, $intDepId]));
}

/**
 * Retrieves the dependencies of an item from the database
 *
 * If a user id is passed as well the dependency status is included
 *
 * @param int $intItemId
 * @param int $intUserId (optional)
 *
 * @return array
 */
function cluevo_get_learning_structure_item_dependencies($intItemId, $intUserId = null)
{
  if (empty($intUserId) || $intUserId == get_current_user_id()) return cluevo_turbo_get_item_dependencies($intItemId);

  global $wpdb;
  $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $stateTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  $sql = "SELECT d.*, COUNT(m.module_id) AS module_count, GROUP_CONCAT(m.module_id) AS modules, COUNT(s.module_id) AS completed_module_count, GROUP_CONCAT(s.module_id) AS completed_modules, IF(COUNT(m.module_id) = COUNT(s.module_id), true, false) AS status
    FROM $depTable d
    LEFT JOIN $treeTable t ON t.path LIKE CONCAT('%/', d.dep_id, '%')
    LEFT JOIN $moduleTable m ON t.item_id = m.item_id AND m.module_id != -1
    LEFT JOIN $stateTable s ON s.module_id = m.module_id AND s.user_id = %d AND ((s.completion_status = 'completed' AND s.success_status = 'passed') OR $scorm12Success) AND s.attempt_id = (
      SELECT MAX(attempt_id) FROM $stateTable
        WHERE module_id = s.module_id AND user_id = %d AND (
        (success_status = 'passed' AND completion_status = 'completed') OR $scorm12Success) LIMIT 1
      )
    WHERE d.item_id = %d
    GROUP BY d.item_id, d.dep_id";

  $rows = $wpdb->get_results(
    $wpdb->prepare(
      $sql,
      [$intUserId, $intUserId, $intItemId]
    ),
    ARRAY_A
  );

  if (!empty($rows)) {
    foreach ($rows as $row) {
      if (!array_key_exists($row['dep_type'], $results))
        $results[$row['dep_type']] = [];

      $results[$row['dep_type']][$row["dep_id"]] = false;

      if (!empty($intUserId) && !empty($row["dep_id"]))
        $results[$row['dep_type']][$row["dep_id"]] = $row['status'];

      if (!empty($row["dep_id"]) && ($row["dep_type"] == "normal" || $row["dep_type"] == "inherited")) {
        $results['all'][$row["dep_id"]] = false;

        if (!empty($intUserId) && !empty($row["dep_id"]))
          $results['all'][$row["dep_id"]] = $row['status'];
      }
    }
  }

  return $results;
}

/**
 * Retrieves the module dependencies of a given item id
 *
 * @param int $itemId
 *
 * @return array
 */
function cluevo_get_module_dependencies($intItemId, $arrCompleted = [])
{
  if (empty($intUserId) || $intUserId == get_current_user_id()) return cluevo_turbo_get_module_dependencies($intItemId);
  global $wpdb;
  $results = ["normal" => [], "inherited" => [], "blocked" => [], "all" => []];
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;
  $sql = "SELECT * FROM $depTable WHERE module_id = %d";
  $rows = $wpdb->get_results($wpdb->prepare($sql, [$intItemId]), ARRAY_A);
  if (!empty($rows)) {
    foreach ($rows as $row) {
      if (!array_key_exists($row['dep_type'], $results))
        $results[$row['dep_type']] = [];

      $results[$row['dep_type']][$row['dep_id']] = false;
      if (!empty($row["dep_id"]) && in_array($row["dep_id"], $arrCompleted)) $results[$row['dep_type']][$row['dep_id']] = true;

      if (!empty($row["dep_id"]) && ($row["dep_type"] == "normal" || $row["dep_type"] == "inherited")) {
        $results['all'][$row['dep_id']] = false;
        if (in_array($row["dep_id"], $arrCompleted)) $results["all"][$row['dep_id']] = true;
      }
    }
  }

  return $results;
}

/**
 * Checks whether a module exists in the database
 *
 * @param mixed $mixedModule Can either be a module id of module name
 *
 * @return bool
 */
function cluevo_module_exists($mixedModule, $strLang = "")
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT COUNT(*) FROM $table WHERE ";
  $sql .= (is_numeric($mixedModule)) ? "module_id = %d" : "module_name = %s";
  $parms = [$mixedModule];
  if (!empty($strLang)) {
    $sql .= " AND lang_code = %s";
    $parms[] = $strLang;
  }
  $result = $wpdb->get_var($wpdb->prepare($sql, $parms));
  return ($result > 0);
}

/**
 * Retrieves a module entry from the database
 *
 * @param mixed $mixedId Can either be a module id or module name
 *
 * @return object|null
 */
function cluevo_get_module($mixedId, $strLangCode = "", $boolRefresh = false)
{
  return cluevo_turbo_get_module($mixedId, $strLangCode, $boolRefresh);
}

/**
 * Retrieves learning structure items from the database
 *
 * Results can be paginated, if a user id is supplied dependency status is included
 *
 * @param int $intItemId (optional)
 * @param int $intUserId (optional)
 * @param int $intPage (optional)
 * @param int $intPerPage (optional)
 *
 * @return array
 */
function cluevo_get_learning_structure_items($intItemId = 0, $intUserId = null, $intPage = 0, $intPerPage = null)
{
  return CluevoTree::find_children($intItemId, $intUserId);
}

/**
 * Finds the learning structure item id from a given metadata post id
 *
 * @param int $intMetadataId
 *
 * @return int|null
 */
function cluevo_get_item_id_from_metadata_id($intMetadataId)
{
  return cluevo_turbo_get_item_id_by_meta_id($intMetadataId);
}

function cluevo_get_metadata_id_from_item_id($intItemId)
{
  return cluevo_turbo_get_meta_id_from_item_id($intItemId);
}

/**
 * Retrieves the children of a given item id
 *
 * Result can be paginated if parameters are supplied. 
 *
 * @param int $intItemId (optional)
 * @param int $intUserId (optional)
 * @param int $intPage (optional)
 * @param int$intPerPage (optional)
 *
 * @return array
 */
function cluevo_get_learning_structure_item_children($intItemId = null, $intUserId = null, $intPage = 0, $intPerPage = null, $boolSkipCache = false)
{
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;
  $stateTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
  $usersToGroupsTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
  $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;
  $wpUserTable = $wpdb->users;
  $collate = $wpdb->collate;

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  $path = (empty($intItemId)) ? "/" : "%/$intItemId/";

  $from = $treeTable;
  $args = [];
  if (current_user_can("administrator")) {
    $from = "(
      SELECT DISTINCT p.perm_id, p.perm, p.access_level, tree.*
      FROM $treeTable tree
      LEFT JOIN $permTable p ON tree.item_id = p.item_id 
    )";
  } else {
    if (empty($intUserId)) {
      $from = "(
        SELECT DISTINCT p.perm_id, p.perm, p.access_level, tree.*
        FROM $treeTable tree
        LEFT JOIN $permTable p ON tree.item_id = p.item_id 
        WHERE p.perm = CONCAT('g:', %d) OR ISNULL(p.perm)
        ORDER BY access_level DESC
    )";
      $args = [CLUEVO_DEFAULT_GROUP_GUEST];
    } else {
      $from = "(
        SELECT DISTINCT p.perm_id, p.perm, MAX(p.access_level) AS access_level, tree.*
        FROM $treeTable tree
        LEFT JOIN (
          SELECT p.perm_id, p.item_id, p.perm, p.access_level
          FROM $permTable p
          INNER JOIN (
            SELECT item_id, MAX(access_level) AS access_level
            FROM $permTable
            GROUP BY item_id
          ) p2 ON p.item_id = p2.item_id AND p2.access_level >= p.access_level
        ) p ON tree.item_id = p.item_id 
        WHERE ISNULL(p.perm) OR p.perm = CONCAT('u:', %d) OR p.perm IN (
          SELECT CONCAT('g:', g.group_id)
          FROM $groupTable g
          LEFT JOIN $wpUserTable wp ON LOCATE('@', g.group_name) AND wp.user_email COLLATE $collate RLIKE g.group_name
          LEFT JOIN $usersToGroupsTable utg ON g.group_id = utg.group_id 
          WHERE user_id = %d OR ID = %d
      )
      GROUP BY tree.item_id
      ORDER BY access_level DESC
    )";
      $args = [$intUserId, $intUserId, $intUserId];
    }
  }

  $sql = "SELECT t.*,
      COUNT(DISTINCT m.module_id) AS 'module_count',
      GROUP_CONCAT(DISTINCT m.module_id) AS 'modules',
      im.module_id AS 'module',
      TRUE AS 'access',
      it.type,
      COUNT(DISTINCT s.module_id) AS completed_module_count,
      GROUP_CONCAT(DISTINCT s.module_id) AS completed_modules,
      IF(COUNT(COALESCE(t2.item_id, t.item_id)) = COUNT(s.module_id), TRUE, FALSE) AS completed,
      m.display_mode
    FROM $from t
    INNER JOIN $typeTable it ON
      t.level = it.level
    LEFT JOIN $treeTable t2 ON
      t2.path LIKE CONCAT('%/', t.item_id, '/%')
    LEFT JOIN $moduleTable im ON t.item_id = im.item_id AND im.module_id > -1
    LEFT JOIN $moduleTable m ON
      t2.item_id = m.item_id OR
      t.item_id = m.item_id
    LEFT JOIN $stateTable s ON
      s.module_id = m.module_id AND
      s.user_id = %d AND
      ((s.completion_status = 'completed' AND
      s.success_status = 'passed') OR
      $scorm12Success) AND
      s.attempt_id = (
        SELECT MAX(attempt_id)
        FROM $stateTable
        WHERE
          module_id = s.module_id AND
          user_id = %d AND
          ((success_status = 'passed' AND
          completion_status = 'completed') OR
          $scorm12Success)
          LIMIT 1
      )
    WHERE t.path LIKE %s ";
  if (!current_user_can('administrator')) $sql .= "AND t.published = 1 ";
  $sql .= "GROUP BY t.item_id
    ORDER BY t.sort_order";

  if (!empty($intPerPage) && is_numeric($intPage) && is_numeric($intPerPage)) {
    $limit = $intPage * $intPerPage;
    $sql .= " LIMIT $limit, $intPerPage";
  }

  $args[] = $intUserId;
  $args[] = $intUserId;
  $args[] = $path;


  $result = $wpdb->get_results(
    $wpdb->prepare(
      $sql,
      $args
    ),
    OBJECT
  );

  $completedModules = cluevo_get_users_completed_modules($intUserId);
  foreach ($result as $key => $item) {
    $result[$key]->access_status = ["dependencies" => true, "points" => true, "level" => true];
    if (!empty($item->modules)) {
      $result[$key]->modules = explode(",", $item->modules);
    } else {
      $result[$key]->modules = [];
    }
    $result[$key]->dependencies["other"] = cluevo_get_learning_structure_item_dependencies($item->item_id, $intUserId);
    $result[$key]->completed = false;

    if (!empty($item->module) && $item->module > 0) {
      $result[$key]->dependencies["modules"] = cluevo_get_module_dependencies($item->item_id, $completedModules);
      if (in_array($item->module, $completedModules)) $result[$key]->completed = true;
    }

    $granted = true;
    $access = true;
    foreach ($result[$key]->dependencies["other"]["all"] as $dep => $value) {
      if ($value == false) {
        $granted = false;
        $access = false;
        break;
      }
    }
    if ($granted && !empty($result[$key]->dependencies["modules"])) {
      foreach ($result[$key]->dependencies["modules"]["all"] as $dep => $value) {
        if (!in_array($value, $completedModules)) {
          $granted = false;
          $access = false;
          break;
        }
      }
    }
    $result[$key]->access_status["dependencies"] = $granted;
    $access_level = 0;
    if (current_user_can('administrator')) {
      $access_level = 999;
    } else {
      $tmpLevel = (!empty($result[$key]->access_level)) ? $result[$key]->access_level : 0;
      if ($tmpLevel) {
        $access_level = (int)$result[$key]->access_level;
      }
    }
    $result[$key]->access_level = $access_level;
    $result[$key]->access_status["access_level"] = $access_level;

    foreach ($result[$key]->access_status as $type => $value) {
      if ($value == false || ($type == "access_level" && $value < 2)) {
        $access = false;
      }
    }
    $result[$key]->access = ($access || current_user_can("administrator"));;

    $children = cluevo_get_learning_structure_item_children($item->item_id, $intUserId);
    $result[$key]->children = [];
    $result[$key]->completed_children = [];
    if (!empty($children)) {
      foreach ($children as $child) {
        if (!empty($child->module) && $child->module > 0) {
          $child->completed = cluevo_user_completed_module($intUserId, $child->module_id);
        }
        $childItem = CluevoItem::from_std_class($child);
        if ($childItem->access_level < 1) continue;
        $result[$key]->children[] = $childItem;
        if ($child->completed)
          $result[$key]->completed_children[] = $child->item_id;
      }

      //$result[$key]->completed = (count($result[$key]->completed_children) > 0 && count($result[$key]->completed_children) == count($result[$key]->children));

      $result[$key]->completed = false;
      if (!empty($result[$key]->children)) {
        $result[$key]->completed = (count($result[$key]->children) == count($result[$key]->completed_children));
      }
      if (!empty($result[$key]->module) && $result[$key]->module > 0) {
        $result[$key]->completed = cluevo_user_completed_module($intUserId, $result[$key]->module);
      }
    }
  }


  $list = [];
  foreach ($result as $obj) {
    $list[] = CluevoItem::from_std_class($obj);
  }

  return $list;
}

/**
 * Returns pagination information for the supplied item id children
 *
 * @param int $intItemId (optional)
 * @param int $intPerPage (optional)
 *
 * @return array
 */
function cluevo_get_learning_structure_item_children_pagination($intItemId = null, $intPerPage = 100)
{
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;

  $sql = "SELECT COUNT(t.item_id)
      FROM $treeTable t
      WHERE t.path LIKE %s";

  $path = (empty($intItemId)) ? "/" : "%/$intItemId/";

  $rows = $wpdb->get_var(
    $wpdb->prepare(
      $sql,
      $path
    )
  );

  $pages = ceil($rows / $intPerPage);

  return ["pages" => $pages, "items_per_page" => $intPerPage, "items" => $rows];
}

/**
 * Retrieves a specific learning structure item from the database
 *
 * Includes access status and dependency status if a user id is passed
 *
 * @param int $intItemId
 * @param int $intUserId (optional)
 *
 * @return CluevoItem|false
 */
function cluevo_get_learning_structure_item($intItemId, $intUserId = null, $boolSkipChildren = false, $boolSkipCache = false)
{
  if ($boolSkipCache) {
    CluevoTree::clear_cache($intUserId);
  }
  return CluevoTree::find_item($intItemId, $intUserId);
}

function cluevo_get_modules_under_path($path)
{
  if (empty($path)) return [];
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $itemPath = rtrim($path, "/");

  $sql = "SELECT DISTINCT m.module_id FROM {$moduleTable} m WHERE m.item_id IN (SELECT item_id FROM {$treeTable} t WHERE PATH LIKE CONCAT(%s, '/%'))";

  $rows = $wpdb->get_results($wpdb->prepare($sql, [$itemPath]));
  $results = [];
  if (!empty($rows)) {
    foreach ($rows as $row) {
      $results[] = $row->module_id;
    }
  }
  return $results;
}

/**
 * Retrieves a learning structure item by it's metadata post id
 *
 * Includes depedency/access status if a user is specified
 *
 * @param int $intMetadataId
 * @param int $intUserId (optional)
 *
 * @return object
 */
function cluevo_get_learning_structure_item_from_metadata_id($intMetadataId, $intUserId = null)
{
  return CluevoTree::find_item_by_post_id($intMetadataId, $intUserId);
}

/**
 * Deletes a learning structure item
 *
 * @param int $intItemId
 *
 * @return bool
 */
function cluevo_remove_learning_structure_item($intItemId)
{
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $metaId = cluevo_get_metadata_id_from_item_id($intItemId);

  $sql = "DELETE t, m, d, p FROM $treeTable t
    LEFT JOIN $moduleTable m ON t.item_id = m.item_id
    LEFT JOIN $depTable d ON t.item_id = d.item_id
    LEFT JOIN $depTable d2 ON t.item_id = d.dep_id
    LEFT JOIN $permTable p ON t.item_id = p.item_id
    WHERE t.item_id = %d OR t.parent_id = %d OR t.path LIKE CONCAT('%/', %s, '/%')";

  $result = $wpdb->query(
    $wpdb->prepare(
      $sql,
      [$intItemId, $intItemId, $intItemId]
    )
  );

  if ($result !== false) {
    if (!empty($metaId)) {
      wp_delete_post($metaId, true);
    }
  }

  return ($result !== false);
}

/**
 * Transforms an array of objects/arrays into a hierarchical array
 *
 * @param mixed $array
 * @param string $strIdProp
 * @param string $strParentProp
 * @param string $strChildProp
 * @param int $intParentId
 *
 * @return array
 */
function cluevo_array_to_tree(&$array, $intParentId = 0, $strIdProp = "item_id", $strParentProp = "parent_id", $strChildProp = "children")
{
  $items = [];
  foreach ($array as $key => $item) {
    if (is_array($item)) {
      if ($item[$strParentProp] == $intParentId) {
        $result = cluevo_array_to_tree($array, $item[$strIdProp], $strIdProp, $strParentProp, $strChildProp);
        $children = [];

        if ($result) {
          foreach ($result as $c) {
            $children[] = $c;
          }
        }
        $item[$strChildProp] = $children;
        $items[] = $item;
        unset($array[$key]);
      }
    } else {
      if ($item->{$strParentProp} == $intParentId) {
        $result = cluevo_array_to_tree($array, $item->{$strIdProp}, $strIdProp, $strParentProp, $strChildProp);
        $children = [];

        if ($result) {
          foreach ($result as $c) {
            $children[] = $c;
          }
        }
        $item->{$strChildProp} = $children;
        $items[] = $item;
        unset($array[$key]);
      }
    }
  }

  return $items;
}

/**
 * Retrieves all root level items (course groups)
 *
 * @return array|null
 */
function cluevo_get_learning_structures($intUserId = null, $boolSkipChildren = false)
{
  $trees = CluevoTree::load_all($intUserId);
  return array_column($trees, "item");
}

function cluevo_get_learning_structure_items_downwards($intItemId, $intUserId = null)
{
  $item = cluevo_get_learning_structure_item($intItemId, $intUserId);
  if (!empty($item)) {
    if (!empty($item->children)) {
      foreach ($item->children as $key => $child) {
        $item->children[$key] = cluevo_get_learning_structure_items_downwards($child->item_id, $intUserId);
      }
    }
  }
  return $item;
}

/**
 * Returns pagination for course groups
 *
 * @param int $intPerPage (optional)
 */
function cluevo_get_learning_structures_pagination($intPerPage = 100)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "SELECT COUNT(*) FROM $table WHERE level = 0";

  $rows = $wpdb->get_var($sql);

  $pages = ceil($rows / $intPerPage);

  return ["pages" => $pages, "items_per_page" => $intPerPage, "items" => $rows];
}

/**
 * Creates a new course group
 *
 * @param string $strName
 * @param int $intMetaId (optional)
 *
 * @return int|false
 */
function cluevo_create_learning_structure($strName, $intMetaId = null)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "INSERT IGNORE INTO $table SET name = %s, level = 0, metadata_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare($sql, [$strName, $intMetaId])
  );

  if ($result !== false)
    return $wpdb->insert_id;
  else
    return false;
}

/**
 * Updates the name and metadata post id of a course group
 *
 * @param int $intTreeId
 * @param string $strName
 * @param int $intMetaId (optional)
 */
function cluevo_update_learning_structure($intTreeId, $strName, $intMetaId = null)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "UPDATE $table SET name = %s, metadata_id = %d WHERE item_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare($sql, [sanitize_text_field($strName), $intMetaId, $intTreeId])
  );
}

/**
 * Returns the string path as array of the given id path
 *
 * Explodes an id path like /1/2/3 into it's components, looks up each
 * item name and returns an array with item names
 *
 * @param string $strPath
 *
 * @return array
 */
function cluevo_get_string_path($strPath)
{
  if (!is_string($strPath)) return [];

  $parts = explode('/', $strPath);
  $path = [];
  foreach ($parts as $id) {
    if (!empty($id)) {
      $name = cluevo_get_item_name($id);
      if (!empty($name)) {
        $path[] = $name;
      }
    }
  }

  return $path;
}

function cluevo_get_item_name($intItemId)
{
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $result = $wpdb->get_row(
    $wpdb->prepare("SELECT name FROM $treeTable WHERE item_id = %d", [$intItemId])
  );
  if (empty($result)) return false;
  return $result->name;
}

/**
 * Updates the metadata post of a module
 *
 * @param int $intModuleId
 * @param int $intMetadataId
 *
 * @return int|false
 */
function cluevo_update_module_metadata_id($intModuleId, $intMetadataId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "UPDATE $table SET metadata_id = %d WHERE module_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare($sql, [$intMetadataId, $intModuleId])
  );

  return $result;
}

/**
 * Retrieves the first course group from the database
 *
 * @return int|null
 */
function cluevo_get_first_course_group()
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;

  $sql = "SELECT item_id FROM $table WHERE level = 0 ORDER BY item_id ASC LIMIT 1";

  $result = $wpdb->get_var($sql);

  return (!empty($result)) ? (int)$result : null;
}

/**
 * Retrieves the type name (tree, course, etc.) of an item level
 *
 * @param int $intLevel
 *
 * @return string|null
 */
function cluevo_get_item_level_name($intLevel)
{
  global $wpdb;

  $sql = "SELECT type FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES . " WHERE level = %d";

  return $wpdb->get_var(
    $wpdb->prepare($sql, [$intLevel])
  );
}

/**
 * Returns the path of a given item
 *
 * @param int $intItemId
 *
 * @return string|null
 */
function cluevo_get_item_path($intItemId)
{
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sql = "SELECT CONCAT(COALESCE(path, ''), item_id) FROM $treeTable WHERE item_id = %d";
  return $wpdb->get_var($wpdb->prepare($sql, [$intItemId]));
}

/**
 * Handles saving of course groups
 *
 * @param mixed $tree
 */
function cluevo_save_learning_structure($tree, $intTreeId = null, $strTreeName = null)
{
  $treeId = (isset($_POST["lms-tree-id"]))
    ? (int)$_POST["lms-tree-id"]
    : null;
  $treeId = !empty($intTreeId) ? $intTreeId : $treeId;
  $treeName = (!empty($_POST["lms-tree-name"])) ? sanitize_text_field($_POST["lms-tree-name"]) : __("Learning Tree", "cluevo");

  $curItems = cluevo_get_learning_structure_items($treeId);

  $tree = json_decode(stripslashes($tree));
  $array = json_decode(json_encode($tree), true); // create an assoc array from the json object

  $page = cluevo_get_metadata_page($treeId); // get tree metadata page, and create/update
  $treeMetadataId = 0;
  if (!empty($page)) {
    $treeMetadataId = $page->ID;
    if ($page->post_title != $treeName) {
      wp_update_post(["ID" => $treeMetadataId, "post_title" => $treeName]);
      cluevo_update_learning_structure($treeId, $treeName, $treeMetadataId); // update tree item with metadata id
    }
  } else {
    $treeMetadataId = wp_insert_post(["post_title" => $treeName, "post_status" => "publish", 'post_type' => CLUEVO_METADATA_POST_TYPE]);
    $terms = get_terms(['taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false]);
    if (is_array($terms)) {
      foreach ($terms as $term) {
        if ($term->name == __("Course Group", "cluevo")) {
          wp_set_post_terms($treeMetadataId, [$term->term_id], CLUEVO_TAXONOMY);
          break;
        }
      }
    }
    cluevo_update_learning_structure($treeId, $treeName, $treeMetadataId); // update tree item with metadata id
  }

  //$idMap = []; // the tree uses temp. ids for new items, we map the tmp ids to the real db ids
  foreach ($array as $key => $item) {
    $item["item_id"] = !empty($item["id"]) ? $item["id"] : $item["item_id"]; // items come without item_id prop from the ui
    $item["parent_id"] = (empty($item["parent_id"])) ? $treeId : $item["parent_id"];
    //$idMap[$item["item_id"]] = $item["item_id"];
    $array[$key] = CluevoItem::from_array($item);
  }

  foreach ($array as $key => $item) { // create/update posts for tree items
    $item = CluevoItem::from_array($item);
    $cur = cluevo_get_learning_structure_item($item->item_id);
    $metadataId = $item->metadata_id;
    if (empty($metadataId)) { // if the metadata id is empty we need to create a new post
      $metadataId = cluevo_create_metadata_post($item, $treeMetadataId, $array);
    } else {
      if ($item->name != $cur->name) {
        remove_action("save_post_" . CLUEVO_METADATA_POST_TYPE, "cluevo_add_change_date_to_post", 10);
        wp_update_post(['ID' => $metadataId, 'post_title' => sanitize_text_field($item->name)]);
        add_action("save_post_" . CLUEVO_METADATA_POST_TYPE, "cluevo_add_change_date_to_post", 10, 3);
      }
    }
  }

  $depList = []; // this is for generating a list of dependencies that is later used to update the dependency entries in the database at once instead of each dependency individually
  foreach ($array as $key => $item) {
    $item = CluevoItem::from_array($item);
    $cur = cluevo_get_learning_structure_item($item->item_id);
    if (!empty($cur)) {
      $cur->load_settings();
    } else {
      $item->new = true;
    }

    if ($treeId !== null) $item->tree_id = $treeId;  // set the tree id for each element

    $item->parent_id = (empty($item->parent_id)) ? $treeId : $item->parent_id; // if the parent is empty it is a child of the tree

    if ($item->level == 1) // if the item level is 1 (course) the parent id is the tree item
      $item->parent_id = $treeId;


    $tree->{$key}->metadata_id = $item->metadata_id;

    $itemId = $item->item_id;
    if (!empty($item->new)) { // if the item is new we create a db entry, otherwise we update the existing entry
      $itemId = cluevo_create_learning_structure_item($item);
      $item->item_id = $itemId; // we got a new id and replace the tmp id with this one and update the map
      $depList[$itemId] = $item->dependencies;
    } else {
      $depList[$item->item_id] = $item->dependencies;
      $needsUpdate = cluevo_tree_item_needs_update($item, $cur);
      if ($needsUpdate) {
        cluevo_update_learning_structure_item($item, true);
      }
    }
  }

  // generate the needed sql statements for the dependency updates
  $delKeys = [];
  $inserts = [];
  $deletes = [];
  $args = [];
  $modKeys = [];
  $modInserts = [];
  $modDeletes = [];
  $modDelKeys = [];
  if (!empty($depList)) {
    foreach ($depList as $itemId => $deps) {
      $deletes[] = $itemId;
      foreach ($deps as $type => $types) {
        foreach ($deps[$type] as $t => $list) {
          if ($type == "other") {
            $keys = array_keys($list);
            $delKeys[] = $keys;
            if (empty($keys)) continue;
            foreach ($keys as $dep) {
              $inserts[] = "(%d, %d, %s)";
              $args[] = $itemId;
              $args[] = $dep;
              $args[] = $t;
            }
          } else {
            $modKeys = array_keys($list);
            $modDelKeys[] = $modKeys;
            if (empty($modKeys)) continue;
            foreach ($modKeys as $dep) {
              $modInserts[] = "(%d, %d, %s)";
              $modArgs[] = $dep;
              $modArgs[] = $itemId;
              $modArgs[] = $t;
            }
          }
        }
      }
    }
  }

  global $wpdb;
  if (!empty($deletes)) {
    $in = implode(", ", array_fill(0, count($deletes), "%d"));
    $sql = "DELETE FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES . " WHERE item_id IN ({$in})";
    $modSql = "DELETE FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES . " WHERE dep_id IN ({$in})";
    $wpdb->query(
      $wpdb->prepare($sql, $deletes)
    );
    $wpdb->query(
      $wpdb->prepare($modSql, $deletes)
    );
  }

  if (!empty($inserts)) {
    $values = implode(", ", $inserts);
    $sql = "INSERT IGNORE INTO " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES . " (item_id, dep_id, dep_type) VALUES {$values}";
    $wpdb->query(
      $wpdb->prepare($sql, $args)
    );
  }

  if (!empty($modInserts)) {
    $values = implode(", ", $modInserts);
    $sql = "INSERT IGNORE INTO " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES . " (module_id, dep_id, dep_type) VALUES {$values}";
    $wpdb->query(
      $wpdb->prepare($sql, $modArgs)
    );
  }

  foreach ($array as $newItem) {
    $newItem = CluevoItem::from_array($newItem);
    foreach ($curItems as $key => $item) {
      if ($item->item_id == $newItem->item_id) {
        unset($curItems[$key]);
      }
    }
  }

  foreach ($curItems as $item) {
    cluevo_remove_learning_structure_item($item->item_id);
  }
}


/**
 * Creates a learning structures database entry
 *
 * Returns the new item id on success
 *
 * @param CluevoItem $item
 *
 * @return int|false
 */
function cluevo_create_learning_structure_item($item)
{
  global $wpdb;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $path = "";
  if (!empty($item->path)) {
    if (is_array($item->path)) {
      $path = "/" . implode("/", $item->path["id"]);
    } else {
      if (is_object($item->path)) {
        $path = "/" . implode("/", $item->path->id);
      } else {
        if (is_string($item->path)) {
          $path = $item->path;
        }
      }
    }
  }
  $path = preg_replace("/\/$item->item_id$/", "/", $path);
  $data = array(
    "parent_id" => $item->parent_id,
    "metadata_id" => $item->metadata_id,
    "level" => $item->level,
    "name" => sanitize_text_field($item->name),
    "path" => $path,
    "sort_order" => $item->sort_order,
    "points_worth" => $item->points_worth,
    "points_required" => $item->points_required,
    "practice_points" => $item->practice_points,
    "level_required" => $item->level_required,
    "login_required" => $item->login_required,
    "published" => $item->published
  );

  $result = $wpdb->insert($treeTable, $data);

  if ($result !== false) {
    $insertId = $wpdb->insert_id;
    $item->item_id = $insertId;

    cluevo_save_learning_structure_item_settings($item);
    cluevo_create_learning_structure_item_dependencies($item);

    if (!empty($item->module_id) && $item->module_id > 0) {
      cluevo_create_module_dependencies($item);
      cluevo_create_learning_structure_module_item($item->item_id, $item->module_id, $item->display_mode);
    }

    return $insertId;
  }

  return false;
}

function cluevo_update_module($intModuleId, $args)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $parms = [];
  $validFields = ["type_id", "module_name", "module_zip", "module_dir", "module_index", "scorm_version", "lang_code", "metadata_id"];
  $sql = "UPDATE $table SET ";
  foreach ($args as $col => $value) {
    if (!in_array($col, $validFields)) continue;
    $sql .= " $col = %s,";
    $parms[] = $value;
  }
  $sql = trim($sql, ',');
  $sql .= " WHERE module_id = %d";
  $parms[] = $intModuleId;

  $result = $wpdb->query(
    $wpdb->prepare($sql, $parms)
  );
  cluevo_clear_module_cache();

  return ($result == 1);
}

function cluevo_get_users_completed_modules($intUserId = null, $useCache = true)
{
  if (empty($intUserId)) {
    $userId = get_current_user_id();
  } else {
    $userId = $intUserId;
  }
  if ($userId == get_current_user_id() && $useCache == true) return cluevo_turbo_get_users_completed_modules();

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sql = "SELECT DISTINCT module_id
    FROM $table
    WHERE user_id = %d AND credit = 'credit' AND (
      (completion_status = 'completed' AND success_status = 'passed') OR ($scorm12Success)
    )";
  $results = $wpdb->get_results(
    $wpdb->prepare($sql, [$userId])
  );

  $ids = [];
  if (!empty($results)) {
    foreach ($results as $r) {
      $ids[] = $r->module_id;
    }
  }

  return $ids;
}

function cluevo_get_modules_items($mixedModules, $boolLeavesOnly = false, $boolParents = false)
{
  global $wpdb;
  if (empty($mixedModules)) return [];
  $list = !is_array($mixedModules) ? [$mixedModules] : $mixedModules;
  $hash = implode("-", $list);
  if (isset($GLOBALS["cluevo-modules-items"][$hash])) {
    return $GLOBALS["cluevo-modules-items"][$hash];
  } else {
    $args = null;
    $sql = "SELECT tm.item_id, path, t.parent_id FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES . " tm
      INNER JOIN " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE . " t ON tm.item_id = t.item_id ";
    $placeholders = implode(",", array_fill(0, count($list), "%s"));
    $sql .= "WHERE module_id IN ($placeholders) ORDER BY t.level, t.path, t.sort_order";
    $args = $list;

    $results = $wpdb->get_results(
      $wpdb->prepare(
        $sql,
        $args
      )
    );

    $ids = [];
    if (!empty($results)) {
      foreach ($results as $result) {
        if ($boolParents) {
          if (!empty($result->parent_id) && !in_array($result->parent_id, $ids)) {
            $ids[] = $result->parent_id;
          }
        } else {
          $ids[] = $result->item_id;
        }
        if (!$boolLeavesOnly) {
          $parts = explode("/", $result->path);
          if (!empty($parts)) {
            foreach ($parts as $id) {
              if (!empty($id) && !in_array($id, $ids)) {
                $ids[] = $id;
              }
            }
          }
        }
      }
    }
    $GLOBALS["cluevo-modules-items"][$hash] = $ids;
    return $ids;
  }
}

function cluevo_get_module_id_from_metadata_id($intMetadataId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT m.module_id FROM {$table} m WHERE m.metadata_id = %d";
  return $wpdb->get_var($wpdb->prepare($sql, [$intMetadataId]));
}

function cluevo_get_lms_item_list()
{

  if (isset($GLOBALS["cluevo-lms-item-list"])) return $GLOBALS["cluevo-lms-item-list"];

  global $wpdb;
  $sql = "SELECT t.item_id, t.path, t.name, m.module_id FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE . " t
    LEFT JOIN " . $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES . " m ON t.item_id = m.item_id ORDER BY sort_order";
  $results = $wpdb->get_results($sql);
  if (!empty($results)) {
    foreach ($results as $key => $item) {
      $parts = explode("/", $item->path);
      $path = "";
      if (!empty($parts)) {
        foreach ($parts as $part) {
          foreach ($results as $p) {
            if ($p->item_id != $part) continue;
            $path .= $p->name . "/";
            break;
          }
        }
      }
      $results[$key]->path = $path;
    }
  }

  $GLOBALS["cluevo-lms-item-list"] = $results;
  return $results;
}

function cluevo_get_tree_from_metadata_id($intItemId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;

  $sql = "SELECT path from $table WHERE metadata_id = %d";
  $path = $wpdb->get_var(
    $wpdb->prepare($sql, [$intItemId])
  );
  if (!empty($path)) {
    $parts = explode("/", $path);
    if (!empty($parts)) return array_pop($parts);
  }
  return null;
}

function cluevo_tree_item_needs_update($newItem, $oldItem)
{
  $updateProps = [
    "name",
    "parent_id",
    "path",
    "published",
    "points_worth",
    "points_required",
    "practice_points",
    "level_required",
    "published",
    "settings",
    "module_id",
    "sort_order"
  ];
  foreach ($newItem as $prop => $value) {
    if (!in_array($prop, $updateProps)) continue;
    foreach ($oldItem as $oProp => $oValue) {
      if ($oProp != $prop) continue;
      if ($prop == "path") {
        $value = $newItem->{$prop}["id"];
        $oValue = $oldItem->{$prop}->id;
      }
      $a = (is_array($value) || is_object($value))
        ? json_encode($value)
        : "$value";
      $b = (is_array($oValue) || is_object($oValue))
        ? json_encode($oValue)
        : "$oValue";
      if (strcmp($a, $b) != 0) {
        return true;
      }
    }
  }
  return false;
}
