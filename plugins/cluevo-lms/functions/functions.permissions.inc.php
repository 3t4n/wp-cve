<?php
if (!defined("CLUEVO_ACTIVE")) exit;
/**
 * Retrieves an items access control list
 *
 * @param int $intItemId
 */
function cluevo_get_item_perms($intItemId)
{
  global $wpdb;

  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $usersToGroupsTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;

  $sql = "SELECT p.* FROM $permTable p
    LEFT JOIN $treeTable tree ON tree.path LIKE CONCAT('%/', p.item_id, '/%') OR tree.item_id = p.item_id
    WHERE tree.item_id = %d";

  $results = $wpdb->get_results(
    $wpdb->prepare($sql, [$intItemId])
  );

  $acl = new CluevoAcl();
  $acl->item_id = $intItemId;
  if (!empty($results)) {
    foreach ($results as $row) {
      $perm = CluevoPermission::from_std_class($row);
      $acl->add_permission($perm);
    }
  }

  return $acl;
}

/**
 * Adds a permission record to an item
 *
 * @param int $intItemId
 * @param string $strType
 * @param int $intId
 */
function cluevo_add_item_perm($intItemId, $strType, $intId, $intLevel)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $perm = "";
  switch ($strType) {
    case "u":
    case "user":
      $perm = "u:$intId";
      break;
    case "g":
    case "group":
      $perm = "g:$intId";
      break;
  }

  if (empty($perm))
    return false;

  $sql = "INSERT INTO $permTable SET
    item_id = %d,
    perm = %s,
    access_level = %d
    ON DUPLICATE KEY UPDATE access_level = %d";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [$intItemId, $perm, $intLevel, $intLevel])
  );
  $insertId = $wpdb->insert_id;

  if ($result !== false && is_numeric($insertId) && $insertId != 0)
    return $wpdb->insert_id;

  return false;
}

/**
 * Removes a specific permission record by id from an item
 *
 * @param int $permId
 */
function cluevo_remove_item_perm($permId)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $sql = "DELETE FROM $permTable WHERE perm_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [$permId])
  );

  return ($result == 1);
}

/**
 * Adds one or more user permissions to an item
 *
 * @param int $intItemId
 * @param mixed $mixedUsers
 */
function cluevo_add_user_perms_to_item($intItemId, $mixedUsers, $intLevel)
{
  $list = array();
  if (!is_array($mixedUsers)) {
    $list[] = $mixedUsers;
  } else {
    $list = $mixedUsers;
  }

  $results = [];
  foreach ($list as $user) {
    $results[$user] = false;
    if (is_numeric($user)) {
      $results[$user] = cluevo_add_item_perm($intItemId, "u", $user, $intLevel);
    }
  }

  return $results;
}

/**
 * Adds one or more group permissions to an item
 *
 * @param int $intItemId
 * @param mixed $mixedGroups
 */
function cluevo_add_group_perms_to_item($intItemId, $mixedGroups, $intLevel)
{
  $list = array();
  if (!is_array($mixedGroups)) {
    $list[] = $mixedGroups;
  } else {
    $list = $mixedGroups;
  }

  $results = [];
  foreach ($list as $group) {
    $results[$group] = false;
    if (is_numeric($group)) {
      $results[$group] = cluevo_add_item_perm($intItemId, "g", $group, $intLevel);
    }
  }

  return $results;
}

/**
 * Clears user permissions for all the passed users from all items
 *
 * @param mixed $mixedUsers
 */
function cluevo_clear_user_perms($mixedUsers)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $list = array();
  if (!is_array($mixedUsers)) {
    $list[] = $mixedUsers;
  } else {
    $list = $mixedUsers;
  }

  $del = "";
  foreach ($list as $u) {
    $del .= " perm = CONCAT('u:', %d) OR";
  }
  $del = trim($del, " OR");

  $sql = "DELETE FROM $permTable WHERE $del";

  $result = $wpdb->query(
    $wpdb->prepare($sql, $list)
  );

  if ($result !== false && is_numeric($result) && $result != 0)
    return $result;

  return false;
}

/**
 * Clears group permissions for all the passed groups from all items
 *
 * @param mixed $mixedGroups
 */
function cluevo_clear_group_perms($mixedGroups)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $list = array();
  if (!is_array($mixedGroups)) {
    $list[] = $mixedGroups;
  } else {
    $list = $mixedGroups;
  }

  $del = "";
  foreach ($list as $g) {
    $del .= " perm = CONCAT('g:', %d) OR";
  }
  $del = trim($del, " OR");

  $sql = "DELETE FROM $permTable WHERE $del";

  $result = $wpdb->query(
    $wpdb->prepare($sql, $list)
  );

  if ($result !== false && is_numeric($result) && $result != 0)
    return $result;

  return false;
}

function cluevo_remove_group_perms_from_item($intItemId, $mixedGroups)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $list = array();
  if (!is_array($mixedGroups)) {
    $list[] = $mixedGroups;
  } else {
    $list = $mixedGroups;
  }

  $del = "";
  $args = [$intItemId];
  foreach ($list as $g) {
    $del .= " perm = CONCAT('g:', %d) OR";
    $args[] = $g;
  }
  $del = trim($del, " OR");

  $sql = "DELETE FROM $permTable WHERE item_id = %d AND ($del)";

  $result = $wpdb->query(
    $wpdb->prepare($sql, $args)
  );

  if ($result !== false && is_numeric($result) && $result != 0)
    return $result;

  return false;
}

/**
 * Removes one or more user permissions from an item
 *
 * @param int $intItemId
 * @param mixed $mixedUsers
 */
function cluevo_remove_user_perms_from_item($intItemId, $mixedUsers)
{
  global $wpdb;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $list = array();
  if (!is_array($mixedUsers)) {
    $list[] = $mixedUsers;
  } else {
    $list = $mixedUsers;
  }

  $del = "";
  $args = [$intItemId];
  foreach ($list as $u) {
    $del .= " perm = CONCAT('u:', %d) OR";
    $args[] = $u;
  }
  $del = trim($del, " OR");

  $sql = "DELETE FROM $permTable WHERE item_id = %d AND ($del)";

  $result = $wpdb->query(
    $wpdb->prepare($sql, $args)
  );

  if ($result !== false && is_numeric($result) && $result != 0)
    return $result;

  return false;
}

/**
 * Clears all permission records from an item
 *
 * @param int $intItemId
 */
function cluevo_clear_acl_from_item($intItemId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

  $sql = "DELETE FROM $table WHERE item_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [$intItemId])
  );

  if ($result !== false && is_numeric($result) && $result != 0)
    return $result;

  return false;
}

function cluevo_get_tree_perms_for_group($intGroupId, $intItemId = null)
{
  return cluevo_get_tree_perms("g", $intGroupId, $intItemId);
}

function cluevo_get_tree_perms_for_user($intUserId, $intItemId = null)
{
  return cluevo_get_tree_perms("u", $intUserId, $intItemId);
}

function cluevo_get_tree_perms_for_users_groups($intUserId, $intItemId = null)
{
  return cluevo_get_tree_perms("ug", $intUserId, $intItemId);
}

function cluevo_get_tree_perms($strType, $intId, $intItemId = null)
{
  global $wpdb;
  $cluevo_groups = cluevo_turbo_get_groups();
  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;
  $usersToGroupsTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;

  $joinCondition = "p.perm = CONCAT('%s', ':', %d)";

  switch (strtolower($strType)) {
    case "g":
    case "group":
      $strType = "g";
      $args = [$strType, $intId, $strType, $intId];
      break;
    case "u":
    case "user":
      $strType = "u";
      $joinCondition = "p.perm IN (
        SELECT CONCAT('g:', group_id) FROM $usersToGroupsTable WHERE user_id = %d
        UNION ALL SELECT CONCAT(%s, ':', %d)
      )";
      $args = [$strType, $intId, $intId, $strType, $intId];
      break;
    default:
      $strType = null;
  }

  if (!empty($strType)) {

    if (version_compare(get_option(CLUEVO_DB_VERSION_OPT_KEY), CLUEVO_PLUGIN_DB_VERSION) === -1) {
      $sql = "SELECT t.item_id, t.parent_id, t.name, t.level, p.perm_id, COALESCE(p.perm, CONCAT(%s, ':', %d)) AS perm, COALESCE(p.access_level, 0) AS access_level, tt.type
      FROM $treeTable t
      LEFT JOIN $permTable p ON t.item_id = p.item_id AND $joinCondition
      LEFT JOIN $typeTable tt ON t.level = tt.level";
    } else {
      $sql = "SELECT t.item_id, t.parent_id, t.name, t.level, p.perm_id, COALESCE(p.perm, CONCAT(%s, ':', %d)) AS perm, COALESCE(p.access_level, 0) AS access_level, tt.type, UNIX_TIMESTAMP(p.date_expired) AS date_expired
      FROM $treeTable t
      LEFT JOIN $permTable p ON t.item_id = p.item_id AND $joinCondition
      LEFT JOIN $typeTable tt ON t.level = tt.level";
    }
    if (!empty($intItemId)) {
      $sql .= " AND t.path LIKE CONCAT('%/', %d, '/%') OR t.item_id = %d";
      $args[] = $intItemId;
      $args[] = $intItemId;
    }
    $sql .= " ORDER BY t.parent_id, t.sort_order, t.name ASC";

    $results = $wpdb->get_results(
      $wpdb->prepare($sql, $args)
    );

    $tree = [];
    if (!empty($results)) {

      if ($strType == "u") {
        // if we're getting user permissions we have to collect all group permissions for tree items
        $final = []; // contains the user's permissions
        $grouped = []; // contains all group permissions the user has for an item
        foreach ($results as $key => $row) {
          $id = $row->item_id;
          if (!array_key_exists($id, $grouped)) $grouped[$id] = [];
          $grouped[$id][] = $row;
          $permType = substr($row->perm, 0, 1);
          if ($permType == "u") {
            $final[$id] = $row;
          } else {
            if (!empty($cluevo_groups)) {
              $gid = explode(":", $row->perm);
              $gid = $gid[count($gid) - 1];
              foreach ($cluevo_groups as $cg) {
                if ($cg->group_id == $gid) {
                  $row->group = $cg;
                  break;
                }
              }
            }
            if (empty($final[$id])) { // if a user has no specific user permission but has group permissions for an item
              $perm = new CluevoPermission();
              foreach ($row as $prop => $value) {
                $perm->{$prop} = $value;
              }
              $perm->access_level = 0;
              $perm->perm_id = null;
              $perm->perm = "u:$intId";
              $perm->highest_group_access_level = json_decode(
                json_encode([
                  "perm" => $row->perm,
                  "access_level" => $row->access_level,
                  "group" => $row->group,
                  "date_expired" => (isset($row->date_expired)) ? $row->date_expired : null
                ])
              );
              $final[$id] = $perm;
            }
          }
        }

        foreach ($final as $item) {
          $id = $item->item_id;
          if (array_key_exists($id, $grouped)) {
            foreach ($grouped[$id] as $row) {
              $gType = substr($row->perm, 0, 1);
              if ($row->perm_id == $item->perm_id) continue;
              if (empty($item->highest_group_access_level)) $item->highest_group_access_level = $row;
              if ($item->highest_group_access_level->access_level < $row->access_level) $item->highest_group_access_level = $row;
            }
          }
        }

        $results = [];
        foreach ($final as $row) {
          $results[] = $row;
        }
      }

      $tree = cluevo_array_to_tree($results, $results[0]->parent_id);
    }

    return $tree;
  }
  return [];
}
