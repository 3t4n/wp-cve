<?php
if (!defined("CLUEVO_ACTIVE")) exit;
// Competences

/**
 * cluevo_competence_exists
 *
 * Returns the competence_id if the competence exists, false if it does not exist
 *
 * @param mixed $mixedCompetence
 *
 * @return int|false
 */
function cluevo_competence_exists($mixedCompetence) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;

  $sql = "SELECT competence_id FROM $table WHERE ";
  $sql .= (is_numeric($mixedCompetence)) ? "competence_id = %d" : "competence_name = %s";
  $sql .= " LIMIT 1";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [ $mixedCompetence ])
  );

  return (!empty($result)) ? (int)$result : false;
}

/**
 * Retrieves a competence by id or name from the database
 *
 * Returns null if the competence was not found
 *
 * @param int|string $mixedCompetence
 *
 * @return CluevoCompetence|null
 */
function cluevo_get_competence($mixedCompetence) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $userTable = $wpdb->users;

  $sql = "SELECT c.*, GROUP_CONCAT(DISTINCT ca.competence_area_id SEPARATOR ';') AS areas, GROUP_CONCAT(DISTINCT CONCAT(cm.module_id, ':', cm.competence_coverage) SEPARATOR ';') AS modules, uc.display_name AS user_added, um.display_name AS user_modified
    FROM $compTable c
    LEFT JOIN $compToAreaTable ca
    ON c.competence_id = ca.competence_id
    LEFT JOIN $moduleCompTable cm
    ON c.competence_id = cm.competence_id
    INNER JOIN $userTable uc ON c.user_added_id = uc.ID
    INNER JOIN $userTable um ON c.user_modified_id = um.ID
    WHERE ";

  $sql .= (is_numeric($mixedCompetence)) ? "c.competence_id = %d" : "c.competence_name = %s";

  $sql .= " GROUP BY c.competence_id LIMIT 1";

  $result = $wpdb->get_row(
    $wpdb->prepare($sql, [ $mixedCompetence ] )
  );

  if (!empty($result)) {
    $comp = CluevoCompetence::from_std_class($result);
    return $comp;
  }

  return null;
}

function cluevo_get_competences($intUserId = null, $intPage = 0, $intPerPage = null) {
  global $wpdb;
  $parms = [];
  $result = [];

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $userTable = $wpdb->users;

  $sql = "SELECT c.*, GROUP_CONCAT(DISTINCT ca.competence_area_id SEPARATOR ';') AS areas, GROUP_CONCAT(DISTINCT CONCAT(cm.module_id, ':', cm.competence_coverage) SEPARATOR ';') AS modules, uc.display_name AS user_added, um.display_name AS user_modified
    FROM $compTable c
    LEFT JOIN $compToAreaTable ca
    ON c.competence_id = ca.competence_id
    LEFT JOIN $moduleCompTable cm
    ON c.competence_id = cm.competence_id
    INNER JOIN $userTable uc ON c.user_added_id = uc.ID
    INNER JOIN $userTable um ON c.user_modified_id = um.ID";
  if (!empty($intUserId)) {
    $sql .= " WHERE c.user_added_id = %d OR c.user_modified_id = %d";
    $parms = [ $intUserId, $intUserId ];
  }
  $sql .= " GROUP BY c.competence_id";

  if (!empty($intPerPage) && is_numeric($intPage) && is_numeric($intPerPage)) {
    $limit = $intPage * $intPerPage;
    $sql .= " LIMIT $limit, $intPerPage";
  }

  if (!empty($parms)) {
    $rows = $wpdb->get_results(
      $wpdb->prepare(
        $sql,
        $parms
      ), OBJECT
    );
  } else {
    $rows = $wpdb->get_results($sql);
  }

  foreach ($rows as $comp) {
    $obj = CluevoCompetence::from_std_class($comp);
    $result[] = $obj;
  }

  return $result;
}

function cluevo_get_competences_pagination($intUserId = null, $intPage = 0, $intPerPage = 0) {
  global $wpdb;
  $parms = [];
  $intPerPage = ($intPerPage > 0) ? $intPerPage : 0;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  $sql = "SELECT COUNT(*) FROM $compTable c";

  if (!empty($intUserId)) {
    $sql .= " WHERE c.user_added_id = %d OR c.user_modified_id = %d";
    $parms = [ $intUserId, $intUserId ];
    $rows = $wpdb->get_var(
      $wpdb->prepare(
        $sql,
        $parms
      )
    );
  } else {
    $rows = $wpdb->get_var($sql);
  }

  if ($intPerPage > 0)
    $pages = (int)ceil($rows / $intPerPage);
  else {
    $pages = 1;
    $intPerPage = (int)$rows;
  }

  return [ "pages" => $pages, "items_per_page" => (int)$intPerPage, "items" => (int)$rows ];
}

/**
 * Checks whether a user can edit a competence
 *
 * Returns true if the user can edit, returns true for admins in any case, users can only edit their own competences
 *
 * @param mixed $intCompId
 *
 * @return bool
 */
function cluevo_can_edit_competence($intCompId) {
  if (current_user_can("administrator"))
    return true;

  $comp = cluevo_get_competence($intCompId);

  if ($comp->competence_type == "system")
    return false;

  $curUserId = get_current_user_id();
  return ($comp->user_added_id == $curUserId);
}

/**
 * Adds a competence with the given name and type
 *
 * Returns an integer greater than 0 on success, 0 if the insert failed
 *
 * @param mixed $strName
 * @param string $strType
 *
 * @return int
 */
function cluevo_add_competence($strName, $strType = 'system') {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;

  $sql = "INSERT IGNORE INTO $table SET 
    competence_name = %s,
    competence_type = %s,
    user_added_id = %d,
    user_modified_id = %d";

  $strName = sanitize_text_field($strName);
  $strType = sanitize_text_field($strType);

  $userId = get_current_user_id();

  $result = $wpdb->query(
    $wpdb->prepare($sql,
    [
      $strName,
      $strType,
      $userId,
      $userId
    ])
  );

  if ($result !== false) {
    $compId = $wpdb->insert_id;
    if (($metaId = cluevo_metadata_page_exists($compId, CLUEVO_META_COMPETENCE_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE)) === false) {
      $metaId = cluevo_create_competence_metadata_page($compId, $strName);
    }
    if (is_numeric($metaId) && $metaId > 0) {
      $sql = "UPDATE $table SET metadata_id = %d WHERE competence_id = %d";
      $wpdb->query(
        $wpdb->prepare($sql, [ $metaId, $compId ])
      );
    }
    return $compId;
  }

  return $result;
}

/**
 * Updates a competences name and type
 *
 * Returns true on success, false in any other case
 *
 * @param int $intCompId
 * @param string $strName
 * @param string $strType
 *
 * @return bool
 */
function cluevo_update_competence($intCompId, $strName, $strType = null) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $strName = sanitize_text_field($strName);
  $parms = [ $strName ];

  $sql = "UPDATE IGNORE $table SET 
    competence_name = %s";
  if ($strType === "system" || $strType == "user") {
    $sql .= ", competence_type = %s";
    $parms[] = sanitize_text_field($strType);
  }

  $sql .= ", user_modified_id = %d
    WHERE competence_id = %d";

  $parms[] = get_current_user_id();
  $parms[] = $intCompId;

  $result = $wpdb->query(
    $wpdb->prepare($sql, $parms)
  );

  return ($result === 1);
}

/**
 * Deletes a competence
 *
 * Returns the number of affected rows
 *
 * @param int $intCompId
 *
 * @return int
 */
function cluevo_del_competence($intCompId) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  $sql = "DELETE c, a, m FROM $compTable c
    LEFT JOIN $compToAreaTable a ON c.competence_id = a.competence_id
    LEFT JOIN $moduleCompTable m ON c.competence_id = m.competence_id
    WHERE c.competence_id = %d OR a.competence_id = %d OR m.competence_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare( $sql, [ $intCompId, $intCompId, $intCompId ] )
  );

  if ($result !== false) {
    if (($metaId = cluevo_metadata_page_exists($intCompId, CLUEVO_META_COMPETENCE_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE)) !== false) {
      wp_delete_post($metaId);
    }
  }

  return ($result !== false) ? (int)$result : $result;
}

/**
 * Adds a competence to a module
 *
 * Updates the coverage value if the competence is already part of the module.
 * Returns true on success, false on error or if nothing was changed
 *
 * @param mixed $intCompId
 * @param mixed $intModuleId
 * @param float $coverage
 *
 * @return bool
 */
function cluevo_add_competence_to_module($intCompId, $intModuleId, $coverage = 0.00) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  if (cluevo_module_exists($intModuleId) && cluevo_competence_exists($intCompId) && is_numeric($coverage) && $coverage >= 0 && $coverage <= 1) {
    $sql = "INSERT INTO $moduleCompTable SET
      competence_id = %d,
      module_id = %d,
      competence_coverage = %s
      ON DUPLICATE KEY UPDATE competence_coverage = %s";

    $result = $wpdb->query(
      $wpdb->prepare($sql, [ $intCompId, $intModuleId, $coverage, $coverage ])
    );

    if ($result >= 0)
      return true;
  }

  return false;

}

/**
 * Removes a competence from a module
 *
 * @param mixed $intCompId
 * @param mixed $intModuleId
 *
 * @return bool;
 */
function cluevo_remove_competence_from_module($intCompId, $intModuleId) {
  global $wpdb;

  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  $sql = "DELETE FROM $moduleCompTable WHERE module_id = %d AND competence_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [ $intModuleId, $intCompId ])
  );

  return ($result > 0);
}

// Areas

/**
 * Checks whether a competence area exists
 *
 * Returns the area id if it exists, false if it does not
 *
 * @param mixed $mixedArea
 *
 * @return int|false
 */
function cluevo_competence_area_exists($mixedArea) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;

  $sql = "SELECT competence_area_id FROM $table WHERE ";
  $sql .= (is_numeric($mixedArea)) ? "competence_area_id = %d" : "competence_area_name = %s";
  $sql .= " LIMIT 1";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [ $mixedArea ])
  );

  return (!empty($result)) ? (int)$result : false;
}

/**
 * Retrieves a competence area from the database
 *
 * Returns a CluevoCompetenceArea object on success, null on fail
 *
 * @param mixed $mixedArea
 *
 * @return CluevoCompetenceArea|null
 */
function cluevo_get_competence_area($mixedArea) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $userTable = $wpdb->users;

  $sql = "SELECT a.*, GROUP_CONCAT(DISTINCT ca.competence_id SEPARATOR ';') AS competences, GROUP_CONCAT(DISTINCT cm.module_id SEPARATOR ';') AS modules, uc.display_name AS user_added, um.display_name AS user_modified
    FROM $areaTable a
    LEFT JOIN $compToAreaTable ca
    ON a.competence_area_id = ca.competence_area_id
    LEFT JOIN $moduleCompTable cm
    ON cm.competence_id = ca.competence_id
    INNER JOIN $userTable uc ON a.user_added_id = uc.ID
    INNER JOIN $userTable um ON a.user_modified_id = um.ID
    WHERE ";

  $sql .= (is_numeric($mixedArea)) ? "a.competence_area_id = %d" : "a.competence_area_name = %s";

  $sql .= " GROUP BY a.competence_area_id LIMIT 1";

  $result = $wpdb->get_row(
    $wpdb->prepare($sql, [ $mixedArea ] )
  );

  if (!empty($result)) {
    $area = CluevoCompetenceArea::from_std_class($result);
    return $area;
  }

  return null;
}

function cluevo_get_competence_areas($intUserId = null, $intPage = 0, $intPerPage = 0) {
  global $wpdb;
  $results = [];

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $userTable = $wpdb->users;

  $sql = "SELECT a.*, GROUP_CONCAT(DISTINCT ca.competence_id SEPARATOR ';') AS competences, GROUP_CONCAT(DISTINCT cm.module_id SEPARATOR ';') AS modules, uc.display_name AS user_added, um.display_name AS user_modified
    FROM $areaTable a
    LEFT JOIN $compToAreaTable ca
    ON a.competence_area_id = ca.competence_area_id
    LEFT JOIN $moduleCompTable cm
    ON cm.competence_id = ca.competence_id
    INNER JOIN $userTable uc ON a.user_added_id = uc.ID
    INNER JOIN $userTable um ON a.user_modified_id = um.ID";
  if (!empty($intUserId)) {
    $sql .= " WHERE a.user_added_id = %d OR a.user_modified_id = %d";
    $parms = [ $intUserId, $intUserId ];
  }

  $sql .= " GROUP BY a.competence_area_id";

  if (!empty($intPerPage) && is_numeric($intPage) && is_numeric($intPerPage)) {
    $limit = $intPage * $intPerPage;
    $sql .= " LIMIT $limit, $intPerPage";
  }

  $rows = [];
  if (!empty($parms)) {
    $rows = $wpdb->get_results(
      $wpdb->prepare($sql, $parms )
    );
  } else {
    $rows = $wpdb->get_results($sql);
  }

  if (!empty($rows)) {
    foreach ($rows as $a) {
      $area = CluevoCompetenceArea::from_std_class($a);
      $results[] = $area;
    }
  }

  return $results;
}

function cluevo_get_competence_areas_pagination($intUserId = null, $intPage = 0, $intPerPage = 0) {
  global $wpdb;
  $results = [];

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  $sql = "SELECT COUNT(*) FROM $areaTable a";

  if (!empty($intUserId)) {
    $sql .= " WHERE a.user_added_id = %d OR a.user_modified_id = %d";
    $parms = [ $intUserId, $intUserId ];
    $rows = $wpdb->get_var(
      $wpdb->prepare(
        $sql,
        $parms
      )
    );
  } else {
    $rows = $wpdb->get_var($sql);
  }

  if ($intPerPage > 0)
    $pages = (int)ceil($rows / $intPerPage);
  else {
    $pages = 1;
    $intPerPage = (int)$rows;
  }

  return [ "pages" => $pages, "items_per_page" => (int)$intPerPage, "items" => (int)$rows ];
}

function cluevo_add_competence_area($strName, $strType = "system") {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;

  $sql = "INSERT IGNORE INTO $table SET 
    competence_area_name = %s,
    competence_area_type = %s,
    user_added_id = %d,
    user_modified_id = %d";

  $userId = get_current_user_id();
  $strName = sanitize_text_field($strName);
  $strType = sanitize_text_field($strType);

  $result = $wpdb->query(
    $wpdb->prepare($sql,
    [
      $strName,
      $strType,
      $userId,
      $userId
    ])
  );

  if ($result !== false) {
    $areaId = $wpdb->insert_id;
    if (($metaId = cluevo_metadata_page_exists($areaId, CLUEVO_META_COMPETENCE_AREA_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA)) === false) {
      $metaId = cluevo_create_competence_area_metadata_page($areaId, $strName);
    }
    if (is_numeric($metaId) && $metaId > 0) {
      $sql = "UPDATE $table SET metadata_id = %d WHERE competence_area_id = %d";
      $wpdb->query(
        $wpdb->prepare($sql, [ $metaId, $areaId ])
      );
    }
    return $areaId;
  }

  return $result;
}

function cluevo_update_competence_area($intAreaId, $strName, $strType = null) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;

  $strName = sanitize_text_field($strName);
  $parms = [ $strName ];

  $sql = "UPDATE IGNORE $table SET 
    competence_area_name = %s";
  if ($strType === "system" || $strType == "user") {
    $sql .= ", competence_area_type = %s";
    $parms[] = sanitize_text_field($strType);
  }

  $sql .= ", user_modified_id = %d
    WHERE competence_area_id = %d";

  $parms[] = get_current_user_id();
  $parms[] = $intAreaId;

  $result = $wpdb->query(
    $wpdb->prepare($sql, $parms)
  );

  return ($result === 1);
}

function cluevo_del_competence_area($intAreaId) {
  global $wpdb;

  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;

  $sql = "DELETE a, ca FROM $areaTable a
    LEFT JOIN $compToAreaTable ca ON a.competence_area_id = ca.competence_area_id
    WHERE a.competence_area_id = %d OR ca.competence_area_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare( $sql, [ $intAreaId, $intAreaId ] )
  );

  if ($result !== false) {
    if (($metaId = cluevo_metadata_page_exists($intAreaId, CLUEVO_META_COMPETENCE_AREA_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA)) !== false) {
      wp_delete_post($metaId);
    }
  }

  return ($result !== false) ? (int)$result : $result;
}

function cluevo_add_competence_to_area($intCompId, $intAreaId) {
  global $wpdb;

  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;

  $sql = "INSERT IGNORE INTO $compToAreaTable SET
    competence_id = %d,
    competence_area_id = %d";

  if (cluevo_competence_exists($intCompId) && cluevo_competence_area_exists($intAreaId)) {

    $result = $wpdb->query(
      $wpdb->prepare( $sql, [
        $intCompId,
        $intAreaId
      ])
    );

    return ($result !== false) ? true : false;
  }

  return false;
}

/**
 * Removes a competence from a competence area
 *
 * Returns true if the competence was removed, false if it was not
 *
 * @param mixed $intCompId
 * @param mixed $intAreaId
 *
 * @return bool
 */
function cluevo_remove_competence_from_area($intCompId, $intAreaId) {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;

  $result = $wpdb->query(
    $wpdb->prepare(
      "DELETE FROM $table WHERE competence_id = %d AND competence_area_id = %d",
      [ $intCompId, $intAreaId ]
    )
  );

  return ($result == 1) ? true : false;
}

/**
 * Returns an array of areas the competence is part of
 *
 * @param int $intCompId
 * 
 * @return array
 */
function cluevo_get_areas_of_competence($mixedComp) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $moduleCompTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;

  $sql = "SELECT a.*,
    GROUP_CONCAT(DISTINCT ca.competence_id SEPARATOR ';') AS competences,
    GROUP_CONCAT(DISTINCT cm.module_id SEPARATOR ';') AS modules
    FROM ( 
      SELECT ta.* FROM $areaTable ta
      INNER JOIN $compToAreaTable tca
      ON ta.competence_area_id = tca.competence_area_id
      INNER JOIN $compTable tc
      ON tca.competence_id = tc.competence_id
      WHERE ";
  $sql .= (is_numeric($mixedComp)) ? "tc.competence_id = %d" : "tc.competence_name = %s";
  $sql .= ") a
    INNER JOIN $compToAreaTable ca
    ON a.competence_area_id = ca.competence_area_id
    INNER JOIN $compTable c
    ON ca.competence_id = c.competence_id
    LEFT JOIN $moduleCompTable cm
    ON cm.competence_id = ca.competence_id";
  $sql .= " GROUP BY ca.competence_area_id";

  $result = $wpdb->get_results(
    $wpdb->prepare($sql, [ $mixedComp ] )
  );

  $array = [];
  if (!empty($result)) {
    foreach ($result as $area) {
      $array[] = CluevoCompetenceArea::from_std_class($area);
    }
    return $array;
  }

  return null;
}

function cluevo_set_competences_areas($intCompId, $arrAreas) {
  global $wpdb;

  $opCount = 0;

  if (cluevo_competence_exists($intCompId)) {
    if (empty($arrAreas))
      $arrAreas = [];

    $existingAreas = cluevo_get_areas_of_competence($intCompId);
    $tmpIds = [];
    if (!empty($existingAreas)) {
      foreach ($existingAreas as $a) {
        $tmpIds[] = $a->competence_area_id;
      }
    }

    if (!empty($arrAreas)) {
      foreach ($arrAreas as $a) {

        if (gettype($a) == "object")
          $a = $a->competence_area_id;
        if (gettype($a) == "array")
          $a = $a["competence_area_id"];

        if (($key = array_search($a, $tmpIds)) !== false) {
          unset($tmpIds[$key]);
          continue;
        } else {
          if (cluevo_add_competence_to_area($intCompId, $a) === true) {
            $opCount++;
          }
        }
      }
    }

    if (!empty($tmpIds)) {
      foreach ($tmpIds as $a) {
        cluevo_remove_competence_from_area($intCompId, $a);
        $opCount++;
      }
    }
  }

  return ($opCount > 0);
}

function cluevo_set_competences_modules($intCompId, $arrModules) {
  global $wpdb;

  $opCount = 0;

  if (cluevo_competence_exists($intCompId)) {
    if (empty($arrModules))
      $arrModules = [];

    $comp = cluevo_get_competence($intCompId);
    $tmpIds = $comp->modules;
    $tmpIds = [];
    if (!empty($comp->modules)) {
      foreach ($comp->modules as $arr) {
        if (!empty($arr))
          $tmpIds[] = $arr[0];
      }
    }

    if (!empty($arrModules)) {
      foreach ($arrModules as $m) {
        $module = $m;
        $coverage = null;
        if (is_array($m)) {
          if (count($m) > 1) {
            $module = $m[0];
            if (isset($m[1])) {
              $coverage = $m[1];
              $coverage = ($coverage > 1) ? $coverage / 100.0 : $coverage;
              $coverage = ($coverage < 0) ? 0 : $coverage;
            }
          } else {
            return false;
          }
        }
        if (($key = array_search($module, $tmpIds)) !== false) {
          unset($tmpIds[$key]);
        }
        if ($coverage !== null) {
          if (cluevo_add_competence_to_module($intCompId, $module, $coverage) === true) {
            $opCount++;
          }
        } else {
          if (cluevo_add_competence_to_module($intCompId, $module) === true) {
            $opCount++;
          }
        }
      }
    }

    if (!empty($tmpIds)) {
      foreach ($tmpIds as $m) {
        cluevo_remove_competence_from_module($intCompId, $m);
        $opCount++;
      }
    }
  }

  return ($opCount > 0);
}

function cluevo_set_areas_competences($intAreaId, $arrComps) {
  global $wpdb;

  $opCount = 0;

  if (cluevo_competence_area_exists($intAreaId)) {
    if (empty($arrComps))
      $arrComps = [];

    $existingComps = cluevo_get_competences_of_area($intAreaId);
    $tmpIds = [];
    if (!empty($existingComps)) {
      foreach ($existingComps as $c) {
        $tmpIds[] = $c->competence_id;
      }
    }

    if (!empty($arrComps)) {
      foreach ($arrComps as $c) {

        if (gettype($c) == "object")
          $c = $c->competence_id;
        if (gettype($c) == "array")
          $c = $c["competence_id"];

        if (($key = array_search($c, $tmpIds)) !== false) {
          unset($tmpIds[$key]);
          continue;
        } else {
          if (cluevo_add_competence_to_area($c, $intAreaId) === true) {
            $opCount++;
          }
        }
      }
    }

    if (!empty($tmpIds)) {
      foreach ($tmpIds as $a) {
        cluevo_remove_competence_from_area($a, $intAreaId);
        $opCount++;
      }
    }
  }

  return ($opCount > 0);
}

function cluevo_get_competences_of_area($mixedArea) {
  global $wpdb;

  $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $compToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;

  $sql = "SELECT c.* FROM $compTable c
    INNER JOIN $compToAreaTable ca
    ON c.competence_id = ca.competence_id
    INNER JOIN $areaTable a
    ON ca.competence_area_id = a.competence_area_id
    WHERE ";
  $sql .= (is_numeric($mixedArea)) ? "a.competence_area_id = %d" : "a.competence_area_name = %s";

  $results = $wpdb->get_results(
    $wpdb->prepare($sql, [ $mixedArea ])
  );

  return $results;
}

/**
 * Checks whether a user can edit a competence area
 *
 * Returns true if the user can edit, returns true for admins in any case, users can only edit their own competence areas
 *
 * @param mixed $intCompId
 *
 * @return bool
 */
function cluevo_can_edit_competence_area($intAreaId) {
  if (current_user_can("administrator"))
    return true;

  $area = cluevo_get_competence_area($intAreaId);

  if ($area->competence_area_type == "system")
    return false;

  $curUserId = get_current_user_id();
  return ($area->user_added_id == $curUserId);
}

function cluevo_create_competence_metadata_page($intCompId, $strName) {

  $strName = trim($strName);
  if (empty($strName))
    return false;

  $meta = [ CLUEVO_META_COMPETENCE_ID => (int)$intCompId ];
  $id = wp_insert_post([
    'post_title' => sanitize_title($strName),
    'post_type' => CLUEVO_METADATA_POST_TYPE_COMPETENCE,
    'post_status' => 'publish',
    'meta_input' => $meta
    ]
  );

  $terms = get_terms([ 'taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false ]);
  if (is_array($terms)) {
    foreach($terms as $term) {
      if ($term->name == __("Competence", "cluevo")) {
        wp_set_post_terms($id, [$term->term_id], CLUEVO_TAXONOMY);
        break;
      }
    }
  }

  return $id;
}

function cluevo_update_competence_metadata_page($intCompId, $strName) {
  $title = trim($strName);
  if (!empty($title)) {
    if (($id = cluevo_metadata_page_exists($intCompId, CLUEVO_META_COMPETENCE_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE)) !== false) {
      $result = wp_update_post( ['ID' => (int)$id, "post_title" => sanitize_title($strName) ]);
      return true;
    }
  }

  return false;
}

function cluevo_create_competence_area_metadata_page($intAreaId, $strName) {

  $strName = trim($strName);
  if (empty($strName))
    return false;

  $meta = [ CLUEVO_META_COMPETENCE_AREA_ID => (int)$intAreaId ];
  $id = wp_insert_post([
    'post_title' => sanitize_title($strName),
    'post_type' => CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA,
    'post_status' => 'publish',
    'meta_input' => $meta
    ]
  );

  $terms = get_terms([ 'taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false ]);
  if (is_array($terms)) {
    foreach($terms as $term) {
      if ($term->name == __("Competence Group", "cluevo")) {
        wp_set_post_terms($id, [$term->term_id], CLUEVO_TAXONOMY);
        break;
      }
    }
  }

  return $id;
}

function cluevo_update_competence_area_metadata_page($intAreaId, $strName) {
  $title = trim($strName);
  if (!empty($title)) {
    if (($id = cluevo_metadata_page_exists($intAreaId, CLUEVO_META_COMPETENCE_AREA_ID, CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA)) !== false) {
      $result = wp_update_post( ['ID' => (int)$id, "post_title" => sanitize_title($strName) ]);
      return true;
    }
  }

  return false;
}
?>
