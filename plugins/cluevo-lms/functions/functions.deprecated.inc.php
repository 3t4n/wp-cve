<?php


function cluevo_rename_learning_structure_item($intItemId, $strName)
{
  _deprecated_function(__FUNCTION__, '1.13.0');

  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;

  $sql = "UPDATE $table SET name = %s WHERE item_id = %d";

  return $wpdb->query(
    $wpdb->prepare($sql, [$strName, $intItemId])
  );
}

/**
 * Returns an array with pagination information for an item id
 *
 * @param int $intItemId
 * @param int $intUserId (optional)
 * @param int $intPage (optional)
 * @param int $intPerPage (optional)
 */
function cluevo_get_learning_structure_items_pagination($intItemId, $intUserId = null, $intPage = 0, $intPerPage = null)
{
  _deprecated_function(__FUNCTION__, '1.13.0');
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;
  $stateTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  $sql = "SELECT COUNT(*)
    FROM $treeTable t
    INNER JOIN $typeTable it ON t.level = it.level
    LEFT JOIN $treeTable t2 ON t2.path LIKE CONCAT('%/', t.item_id, '/%')
    LEFT JOIN $moduleTable m ON t2.item_id = m.item_id OR t.item_id = m.item_id
    LEFT JOIN $stateTable s ON s.module_id = m.module_id AND s.user_id = %d AND ((s.completion_status = 'completed' AND s.success_status = 'passed') OR $scorm12Success) AND s.attempt_id = (SELECT MAX(attempt_id) FROM $stateTable WHERE module_id = s.module_id AND user_id = %d AND ((success_status = 'passed' AND completion_status = 'completed') OR $scorm12Success) LIMIT 1)
    WHERE t.path LIKE %s
    GROUP BY t.item_id
    ORDER BY t.sort_order";

  $rows = $wpdb->get_var(
    $wpdb->prepare(
      $sql,
      [$intUserId, $intUserId, "/$intItemId/%"]
    ),
    OBJECT
  );

  $pages = ceil($rows / $intPerPage);

  return ["pages" => $pages, "items_per_page" => $intPerPage, "items" => $rows];
}

/**
 * Retrieves multiple items from the database
 *
 * Includes dependency/access status if a user is specified
 *
 * @param int $arrDeps
 * @param int $intUserId (optional)
 *
 * @return array
 */
function cluevo_get_multiple_learning_structure_items($arrDeps, $intUserId = null)
{
  _deprecated_function(__FUNCTION__, '1.13.0');
  global $wpdb;

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;
  $stateTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  $sql = "SELECT t.*, COUNT(COALESCE(t2.item_id, t.item_id)) AS 'module_count', GROUP_CONCAT(m.module_id) AS 'modules', TRUE AS 'access', it.type, COUNT(s.module_id) AS completed_module_count, GROUP_CONCAT(s.module_id) AS completed_modules, IF(COUNT(COALESCE(t2.item_id, t.item_id)) = COUNT(s.module_id), true, false) AS completed
    FROM $treeTable t
    INNER JOIN $typeTable it ON t.level = it.level
    LEFT JOIN $treeTable t2 ON t2.path LIKE CONCAT('%/', t.item_id, '/%')
    LEFT JOIN $moduleTable m ON t2.item_id = m.item_id OR t.item_id = m.item_id
    LEFT JOIN $stateTable s ON s.module_id = m.module_id AND s.user_id = %d AND ((s.completion_status = 'completed' AND s.success_status = 'passed') OR $scorm12Success) AND s.attempt_id = (SELECT MAX(attempt_id) FROM $stateTable WHERE module_id = s.module_id AND user_id = %d AND ((success_status = 'passed' AND completion_status = 'completed') OR $scorm12Success) LIMIT 1)
    WHERE t.item_id IN (" . implode(", ", array_fill(0, count($arrDeps), "%s")) . ")
    GROUP BY t.item_id
    ORDER BY t.sort_order";

  $parms = [$intUserId, $intUserId];
  if (!empty($arrDeps) && is_array($arrDeps)) {
    foreach ($arrDeps as $d) {
      $parms[] = $d;
    }
  }

  $result = $wpdb->get_results(
    $wpdb->prepare(
      $sql,
      $parms
    ),
    ARRAY_A
  );

  foreach ($result as $key => $item) {
    $result[$key]["access_status"] = ["dependencies" => true, "points" => true, "level" => true];
    $result[$key]["modules"] = explode(",", $item["modules"]);
    $result[$key]["dependencies"] = cluevo_get_learning_structure_item_dependencies($item["item_id"], $intUserId);
    if (!empty($intUserId)) {
      $granted = true;
      foreach ($result[$key]["dependencies"]["all"] as $dep => $value) {
        if ($value == false) {
          $granted = false;
          break;
        }
      }
      $result[$key]["access_status"]["dependencies"] = $granted;
    }

    $access = true;
    foreach ($result[$key]["access_status"] as $type => $value) {
      if ($value == false) {
        $access = false;
      }
    }
    $result[$key]["access"] = $access;

    $children = cluevo_get_learning_structure_item_children($item["item_id"], $intUserId);
    $result[$key]["children"] = [];
    $result[$key]["completed_children"] = [];
    foreach ($children as $child) {
      $childItem = CluevoItem::from_std_class($child);
      if ($childItem->access_level < 1) continue;
      $result[$key]["children"][] = $childItem;
      if ($child["completed"])
        $result[$key]["completed_children"][] = $child["item_id"];
    }
  }

  return $result;
}

