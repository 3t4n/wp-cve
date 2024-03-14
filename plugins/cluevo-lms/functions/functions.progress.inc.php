<?php
if (!defined("CLUEVO_ACTIVE")) exit;
/**
 * Saves the scorm parameters of the given item/user combination to the database
 *
 * Returns an array with progress state information if parameters have been written
 *
 * @param int $intItemId
 * @param int $intUserId
 * @param array $data
 *
 * @return array|null
 */
function cluevo_write_module_parameters($intItemId, $intUserId, $data)
{
  global $wpdb;

  $item = cluevo_get_learning_structure_item($intItemId, $intUserId);
  $intModuleId = $item->module_id;

  $tableName = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;

  $moduleData = cluevo_get_module($intModuleId);
  $version = $moduleData->scorm_version;
  $prefix = ($version == "2004") ? "cmi.score" : "cmi.core.score";

  $state = cluevo_get_module_progress($intUserId, $intModuleId);  // get current progress for user
  if (empty($state))
    $state = cluevo_init_module_progress($intUserId, $intModuleId, $data, $version);  // init progress if no attempt is in progress

  if (!array_key_exists("$prefix.raw", $data))
    $data["$prefix.raw"] = 0.0;

  if (!array_key_exists("$prefix.scaled", $data)) {
    $raw = (array_key_exists("$prefix.raw", $data)) ? $data["$prefix.raw"] : 0;
    $max = (array_key_exists("$prefix.max", $data)) ? $data["$prefix.max"] : 0;

    if ($max > 0) {
      $data["$prefix.scaled"] = round($raw / $max, 2);
    }
  }

  if (!empty($data) && !empty($moduleData) && !empty($state)) { // insert all parameters into the database
    $totalParm = ($version === "2004") ? "cmi.total_time" : "cmi.core.total_time";
    // due to multiple commits per session we need to remove the previously added session time from the cumulative total time for this session
    // for this we get the current session time that has alreay been added to the total and remove it before adding the /new/ session time again
    $sessionTimeOffset = 0;
    if (!empty($data["cluevo.session_id"])) {
      $curSessionId = $wpdb->get_var(
        $wpdb->prepare(
          "SELECT value FROM {$tableName} WHERE module_id = %d AND user_id = %d AND attempt_id = %d AND parameter = 'cluevo.session_id'",
          [$intModuleId, $intUserId, $state["attempt_id"]]
        )
      );
      if ($curSessionId === $data["cluevo.session_id"]) {
        $curSessionTime = $wpdb->get_var(
          $wpdb->prepare(
            "SELECT value FROM {$tableName} WHERE module_id = %d AND user_id = %d AND attempt_id = %d AND parameter = %s",
            [$intModuleId, $intUserId, $state["attempt_id"], $totalParm]
          )
        );
        if (!empty($curSessionTime)) {
          $sessionTimeOffset = cluevo_scorm_time_to_seconds($curSessionTime);
        }
      }
    }
    foreach ($data as $param => $value) {
      if (in_array($param, ['cmi.core.total_time', 'cmi.total_time'])) continue; // total time is read-only

      $results = $wpdb->query(
        $wpdb->prepare(
          'INSERT INTO ' . $tableName . ' SET module_id = %d, user_id = %d, attempt_id = %d, parameter = %s, value = %s ON DUPLICATE KEY UPDATE value = %s',
          [$intModuleId, $intUserId, $state["attempt_id"], $param, $value, $value]
        )
      );
      if (in_array($param, ['cmi.core.session_time', 'cmi.session_time'])) { // add session time to total time
        try {
          $sessionTotal = cluevo_get_session_total_time($intUserId, $intModuleId, $state["attempt_id"], $version);
          $sessionTotalSeconds = cluevo_scorm_time_to_seconds($sessionTotal);
          $sessionSeconds = cluevo_scorm_time_to_seconds($value);
          if (!empty($sessionTimeOffset)) {
            $sessionTotalSeconds -= $sessionTimeOffset;
          }
          $newSessionTotalSeconds = $sessionTotalSeconds + $sessionSeconds;
          $newSessionTotal = cluevo_seconds_to_scorm_time($newSessionTotalSeconds);
          $wpdb->query(
            $wpdb->prepare(
              'INSERT INTO ' . $tableName . ' SET module_id = %d, user_id = %d, attempt_id = %d, parameter = %s, value = %s ON DUPLICATE KEY UPDATE value = %s',
              [$intModuleId, $intUserId, $state["attempt_id"], $totalParm, $newSessionTotal, $newSessionTotal]
            )
          );
        } catch (Exception $e) {
        }
      }
    }

    $newScore = (!empty($data["$prefix.raw"]) && is_numeric($data["$prefix.raw"])) ? (float)$data["$prefix.raw"] : 0.0;
    $curScore = (!empty($data["score_raw"]) && is_numeric($data["score_raw"])) ? (float)$data["score_raw"] : 0.0;

    if (!array_key_exists("cmi.completion_status", $data))
      $data["cmi.completion_status"] = "unknown";

    if (!array_key_exists("cmi.success_status", $data))
      $data["cmi.success_status"] = "unknown";

    $data["item_id"] = (int)$intItemId;
    return cluevo_update_module_progress($intUserId, $intModuleId, $state["attempt_id"], $data);
  }

  return null;
}

/**
 * Converts a scorm time string to seconds
 *
 * Returns the time in seconds of the scorm timespan
 *
 * @param mixed $strTime
 *
 * @return float|int
 */
function cluevo_scorm_time_to_seconds($strTime)
{
  $years = 0;
  $months = 0;
  $days = 0;
  $hours = 0;
  $minutes = 0;
  $seconds = 0;
  $result = preg_match('/^P(?=\w*\d)(?:(?<years>\d+)Y|Y)?(?:(?<month>\d+)M|M)?(?:(?<days>\d+)D|D)?(?:T(?:(?<hours>\d+)H|H)?(?:(?<minutes>\d+)M|M)?(?:(?<seconds>\d+(?:\.\d{1,2})?)S|S)?)?$/x', $strTime, $matches);
  if ($result) {
    $years = (!empty($matches["years"])) ? (int)$matches["years"] : 0;
    $months = (!empty($matches["months"])) ? (int)$matches["months"] : 0;
    $days = (!empty($matches["days"])) ? (int)$matches["days"] : 0;
    $hours = (!empty($matches["hours"])) ? (int)$matches["hours"] : 0;
    $minutes = (!empty($matches["minutes"])) ? (int)$matches["minutes"] : 0;
    $seconds = (!empty($matches["seconds"])) ? $matches["seconds"] : 0;
  } else {
    if (strstr($strTime, ':')) {
      $parts = explode(':', $strTime);
      if (count($parts) === 3) {
        list($hours, $minutes, $seconds) = $parts;
      }
      if (count($parts) === 2) {
        list($minutes, $seconds) = $parts;
      }
    }
  }

  $time = $seconds + ($minutes * 60) + ($hours * 3600) + ($days * 24 * 3600); // ignoring months as attempts are already unlikely to exceed days of session times
  return $time;
}

/**
 * Converts a timespan in seconds to a scorm timespan
 *
 * Returns a scorm timespan
 *
 * @param mixed $seconds
 *
 * @return string
 */
function cluevo_seconds_to_scorm_time($seconds)
{
  $date1 = new DateTime("@0"); // @ for unix timestamp
  $decimals = substr(round(fmod($seconds, 1), 2), 2); // fmod -> decimals -> round to two places, remove leading 0 and .
  if (!empty($decimals)) $decimals = "." . $decimals;
  $date2 = new DateTime("@" . (int)$seconds);
  $interval =  date_diff($date1, $date2);
  $years = $interval->format('%y');
  $months = $interval->format('%m');
  $days = $interval->format('%d');
  $hours = $interval->format('%h');
  $minutes = $interval->format('%i');
  $seconds = $interval->format('%s');
  $scorm = "PT";
  $scorm .= (!empty($years)) ? $years . "Y" : "";
  $scorm .= (!empty($months)) ? $months . "M" : "";
  $scorm .= (!empty($days)) ? $days . "D" : "";
  $scorm .= (!empty($hours)) ? $hours . "H" : "";
  $scorm .= (!empty($minutes)) ? $minutes . "M" : "";
  $scorm .= (!empty($seconds)) ? $seconds . $decimals . "S" : "";
  if ($scorm === 'PT') {
    return "PT0M0S";
  }
  return $scorm;
}

/**
 * Retrieves the current total time spent on a module attempt by a user
 *
 * Returns the scorm timespan value of the parameter
 *
 * @param int $intUserId
 * @param int $intModuleId
 * @param int $intAttemptId
 * @param string $version
 */
function cluevo_get_session_total_time($intUserId, $intModuleId, $intAttemptId, $version = "2004")
{
  global $wpdb;
  $totalParm = ($version === "2004") ? "cmi.total_time" : "cmi.core.total_time";
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
  $sql = "SELECT value FROM $table WHERE parameter = %s AND user_id = %d AND module_id = %d AND attempt_id = %d";
  $value = $wpdb->get_var(
    $wpdb->prepare($sql, [$totalParm, $intUserId, $intModuleId, $intAttemptId])
  );
  if (!empty($value)) return $value;
  return "";
}

/**
 * Updates the module progress for the given parameter combination
 *
 * Calculates the progress for the given user/item/attempt combination
 * and awards points if applicable, returns the new state information as array
 *
 * @param int $intUserId
 * @param int $intItemId
 * @param int $intAttemptId
 * @param int $data
 *
 * @return array
 */
function cluevo_update_module_progress($intUserId, $intModuleId, $intAttemptId, $data)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $module = cluevo_get_module($intModuleId);
  $version = $module->scorm_version;
  $prefix = ($version == "2004") ? "cmi.score" : "cmi.core.score";
  $itemId = (!empty($data["item_id"])) ? (int)$data["item_id"] : null;
  $item = cluevo_get_learning_structure_item($itemId, $intUserId);

  $state = cluevo_get_module_progress($intUserId, $intModuleId);
  $oldProgress = $state["score_scaled"];

  if (in_array($version, ["2004", "1.2"])) {
    $fields = [
      "score_raw" => ["$prefix.raw" => 0],
      "score_min" => ["$prefix.min" => 0],
      "score_max" => ["$prefix.max" => 0],
      "score_scaled" => ["$prefix.scaled" => 0],
      "completion_status" => ["cmi.completion_status" => "not attempted"],
      "lesson_status" => ["cmi.core.lesson_status" => "not attempted"],
      "success_status" => ["cmi.success_status" => "unknown"],
      "credit" => ["cmi.credit" => "credit"]
    ];

    foreach ($fields as $field => $value) {
      $keys = array_keys($value);
      if (array_key_exists($keys[0], $data)) {
        $state[$field] = $data[$keys[0]];
      } else {
        $state[$field] = $value[$keys[0]];
      }
    }
  } else {
    foreach ($state as $key => $value) {
      if (array_key_exists($key, $data)) {
        $state[$key] = $data[$key];
      }
    }
  }

  $newProgress = round($state["score_scaled"], 2);

  $practiceMode = cluevo_user_module_progress_complete($intUserId, $intModuleId);

  if (!empty($module->scorm_version)) {
    $pointsToAdd = 0;
    $sourceType = "";
    if (!$practiceMode) {
      if ($newProgress > $oldProgress) {
        $progressPoints = cluevo_get_user_module_progression_points($intUserId, $intModuleId);
        $pointsWorth = !empty($item->points_worth) ? $item->points_worth : 0;
        $calcPoints = floor($pointsWorth * $state["score_scaled"]);
        $pointsToAdd = $calcPoints - $progressPoints;
        $sourceType = "scorm-module";
        if ($pointsToAdd > 0) {
          cluevo_add_points_to_user($intUserId, $pointsToAdd, "scorm-module", $intModuleId, $intAttemptId);
          do_action('cluevo_award_user_progress_points_from_module', [
            "user_id" => $intUserId,
            "points_added" => $pointsToAdd,
            "module_id" => $intModuleId,
            "item_id" => $itemId,
            "attempt_id" => $intAttemptId
          ]);
        }
      }
    } else {
      if ($state["completion_status"] == "completed" || $state["lesson_status"] == "completed" || $state["lesson_status"] == "passed") {
        if ($newProgress > $oldProgress) {
          $pointsToAdd = (!empty($item->practice_points)) ? $item->practice_points : 0;
          $sourceType = "scorm-module-practice";
          cluevo_add_points_to_user(
            $intUserId,
            $pointsToAdd,
            "scorm-module-practice",
            $intModuleId,
            $intAttemptId
          );
          do_action('cluevo_award_user_practice_points_from_module', [
            "user_id" => $intUserId,
            "points_added" => $pointsToAdd,
            "module_id" => $intModuleId,
            "item_id" => $itemId,
            "attempt_id" => $intAttemptId
          ]);
        }
      }
    }
    if ($pointsToAdd > 0) {
      do_action('cluevo_user_points_awarded_from_module', [
        "user_id" => $intUserId,
        "module_id" => $intModuleId,
        "attempt_id" => $intAttemptId,
        "points_added" => $pointsToAdd,
        "is_practice" => $practiceMode,
        "source-type" => $sourceType
      ]);
    }
  }

  $sql = "UPDATE $table SET
    score_min = %s,
    score_max = %s,
    score_raw = %s,
    score_scaled = %s,
    completion_status = %s,
    success_status = %s,
    lesson_status = %s,
    credit = %s
    WHERE user_id = %d AND module_id = %d AND attempt_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare(
      $sql,
      [
        $state["score_min"],
        $state["score_max"],
        $state["score_raw"],
        $state["score_scaled"],
        $state["completion_status"],
        $state["success_status"],
        $state["lesson_status"],
        $state["credit"],
        $intUserId,
        $intModuleId,
        $intAttemptId
      ]
    )
  );

  if ($newProgress != $oldProgress) {
    do_action('cluevo_user_module_progress_updated', [
      "user_id" => $intUserId,
      "module_id" => $intModuleId,
      "item_id" => $itemId,
      "attempt_id" => $intAttemptId,
      "state" => $state
    ]);
  }

  if (!$practiceMode && $state["completion_status"] === "completed" && $state["success_status"] === "passed" && $state["credit"] === "credit" || (!$practiceMode && $state["lesson_status"] === "passed")) {
    do_action('cluevo_user_cleared_module_first_time', [
      "user_id" => $intUserId,
      "item_id" => $itemId,
      "module_id" => $intModuleId,
      "attempt_id" => $intAttemptId,
      "state" => $state,
    ]);
  }

  return $state;
}

function cluevo_update_module_progress_entry($intUserId, $intModuleId, $intAttemptId, $state)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sql = "UPDATE $table SET
    score_min = %s,
    score_max = %s,
    score_raw = %s,
    score_scaled = %s,
    completion_status = %s,
    success_status = %s,
    lesson_status = %s,
    credit = %s
    WHERE user_id = %d AND module_id = %d AND attempt_id = %d";

  $result = $wpdb->query(
    $wpdb->prepare(
      $sql,
      [
        $state["score_min"],
        $state["score_max"],
        $state["score_raw"],
        $state["score_scaled"],
        $state["completion_status"],
        $state["success_status"],
        $state["lesson_status"],
        $state["credit"],
        $intUserId,
        $intModuleId,
        $intAttemptId
      ]
    )
  );

  do_action('cluevo_user_module_progress_updated', [
    "user_id" => $intUserId,
    "module_id" => $intModuleId,
    "attempt_id" => $intAttemptId,
    "state" => $state
  ]);
}

function cluevo_update_media_module_progress($intUserId, $intItemId, $max, $score)
{
  if ($max <= 0)
    return false;

  $attempt = 0;
  $attempt = cluevo_get_current_attempt_id($intUserId, $intItemId);
  $attempt++;
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $complete = ($score >= $max) ? 'completed' : 'incomplete';
  $success = ($score >= $max) ? 'passed' : 'unknown';

  $sql = "INSERT INTO $table SET
    user_id = %d,
    module_id = %d,
    attempt_id = %d,
    score_min = 0,
    score_max = %s,
    score_raw = %s,
    score_scaled = %s,
    is_practice = %s,
    completion_status = %s,
    success_status = %s
    ON DUPLICATE KEY UPDATE
    score_raw = %s,
    score_scaled = %s,
    completion_status = %s,
    success_status = %s";

  $scaled = ($score / $max);
  $practice = ($attempt == 0) ? 0 : 1;

  $wpdb->query(
    $wpdb->prepare($sql, [
      $intUserId,
      $intItemId,
      $attempt,
      $max,
      $score,
      $scaled,
      $practice,
      $complete,
      $success,
      $score,
      $scaled,
      $complete,
      $success
    ])
  );
}

function cluevo_init_media_module_attempt($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $attemptId = cluevo_get_current_attempt_id($intUserId, $intModuleId) + 1;

  $sql = "INSERT INTO $table SET
    user_id = %d,
    module_id = %d,
    attempt_id = %d,
    score_min = %d,
    score_max = %s,
    is_practice = %s";

  $wpdb->query(
    $wpdb->prepare($sql, [
      $intUserId,
      $intModuleId,
      $attemptId,
      0,
      100,
      false,
    ])
  );
  return cluevo_get_module_progress($intUserId, $intModuleId);
}

function cluevo_save_media_module_progress($args)
{
  $userId = (int)$args["user_id"];
  $itemId = !empty($args["item_id"]) ? (int)$args["item_id"] : null;
  $module = $args["module"];
  $validTypes = ["audio", "video", "pdf"];
  if (empty($module) || !property_exists($module, "type_name") || !in_array(strtolower($module->type_name), $validTypes)) return;
  $moduleId = $module->module_id;
  $request = $args["request"];
  $max = (float)$request['max'];
  $score = (float)$request["score"];
  $score_scaled = (!empty($max)) ? $score / $max : 1.0;

  if ($max <= 0)
    return false;

  if (!empty($userId) && !empty($module)) {

    $state = cluevo_get_module_progress($userId, $moduleId);
    if (empty($state) || $state["completion_status"] === "completed") {
      $state = cluevo_init_media_module_attempt($userId, $moduleId);
    }
    $state["score_max"] = $max > $state["score_max"] ? $max : $state["score_max"];
    $state["score_scaled"] = $score_scaled > $state["score_scaled"] ? $score_scaled : $state["score_scaled"];
    $state["score_raw"] = $score > $state["score_raw"] ? $score : $state["score_raw"];
    $state["completion_status"] = $score_scaled >= 1 ? "completed" : "incomplete";
    $state["success_status"] = $score_scaled >= 1 ? "passed" : "unknown";
    $state["item_id"] = $itemId;
    $attempt = (int)$state["attempt_id"];
    $practiceMode = cluevo_user_module_progress_complete($userId, $module->module_id);
    $pointsToAdd = 0;
    $sourceType = "";
    $item = null;
    if (!empty($itemId)) {
      $item = cluevo_get_learning_structure_item($itemId);
    }
    if (!$item) return;
    if (!$practiceMode) {
      $pointsWorth = !empty($item->points_worth) ? $item->points_worth : 0;
      $sourceType = "media-module";
      if ($pointsWorth > 0) {
        $progressPoints = cluevo_get_user_module_progression_points($userId, $moduleId);
        $pointsWorth = !empty($item->points_worth) ? $item->points_worth : 0;
        $calcPoints = floor($pointsWorth * $state["score_scaled"]);
        $pointsToAdd = $calcPoints - $progressPoints;
        if ($pointsToAdd > 0) {
          cluevo_add_points_to_user($userId, $pointsToAdd, $sourceType, $module->module_id, $attempt);
          do_action('cluevo_award_user_progress_points_from_module', [
            "user_id" => $userId,
            "points_added" => $pointsToAdd,
            "module_id" => $module->module_id,
            "item_id" => $itemId,
            "attempt_id" => $attempt
          ]);
        }
      }
    } else {
      if ($state["completion_status"] == "completed" && $state["success_status"] == "passed") {
        $pointsToAdd = (!empty($item->practice_points)) ? $item->practice_points : 0;
        $sourceType = "media-module-practice";
        cluevo_add_points_to_user(
          $userId,
          $pointsToAdd,
          $sourceType,
          $module->module_id,
          $attempt
        );
        do_action('cluevo_award_user_practice_points_from_module', [
          "user_id" => $userId,
          "points_added" => $pointsToAdd,
          "module_id" => $module->module_id,
          "item_id" => $itemId,
          "attempt_id" => $attempt
        ]);
      }
    }
    if ($pointsToAdd > 0) {
      do_action('cluevo_user_points_awarded_from_module', [
        "user_id" => $userId,
        "module_id" => $module->module_id,
        "attempt_id" => $attempt,
        "points_added" => $pointsToAdd,
        "is_practice" => false,
        "source-type" => $sourceType
      ]);
    }
    return cluevo_update_module_progress($userId, $moduleId, $state["attempt_id"], $state);
  }
}

/**
 * Awards points to the user
 *
 * Writes an award entry to the database including the source if applicable
 *
 * @param int $intUserId
 * @param int $intPoints
 * @param int $strSource (optional)
 * @param int $intModuleId (optional)
 * @param int $intAttemptId (optional)
 * @param int $intAddedByUserId (optional)
 */
function cluevo_add_points_to_user($intUserId, $intPoints, $strSource = null, $intModuleId = null, $intAttemptId = null, $intAddedByUserId = 0)
{

  if ($intPoints == 0)
    return;

  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG;

  $sql = "INSERT INTO $table SET
    user_id = %d,
    added_by_user_id = %d,
    source_type = %s,
    source_module_id = %d,
    source_module_attempt_id = %d,
    exp_before = %d,
    exp_added = %d,
    exp_after = %d";

  $lastEntry = cluevo_get_latest_exp_log_entry($intUserId);

  if (!empty($lastEntry))
    $before = $lastEntry->exp_after;
  else
    $before = 0;

  $after = $before + $intPoints;

  $result = $wpdb->query(
    $wpdb->prepare(
      $sql,
      [$intUserId, $intAddedByUserId, $strSource, $intModuleId, $intAttemptId, $before, $intPoints, $after]
    )
  );

  if ($result !== false) {
    do_action('cluevo_user_points_awarded', [
      "user_id" => $intUserId,
      "points" => $intPoints,
      "source" => $strSource,
      "module_id" => $intModuleId,
      "attempt_id" => $intAttemptId,
      "added_by_user_id" => $intAddedByUserId
    ]);
    cluevo_update_user_data($intUserId, $after, $after);
  }
}

function cluevo_update_user_data($intUserId, $points, $exp)
{
  global $wpdb;

  $table = $wpdb->prefix . CLUEVO_DB_TABLE_USER_DATA;

  $sql = "INSERT INTO $table
    SET
    user_id = %d,
    total_points = %d,
    total_exp = %d,
    date_last_seen = NOW()
    ON DUPLICATE KEY UPDATE
    total_points = %d,
    total_exp = %d,
    date_last_seen = NOW()";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [
      $intUserId,
      $points,
      $exp,
      $points,
      $exp
    ])
  );

  do_action('cluevo_update_user_data', [
    "user_id" => $intUserId,
    "points" => $points,
    "exp" => $exp
  ]);
}

/**
 * Retrieves the latest exp log entry for the given user from the database
 *
 * @param int $intUserId
 *
 * @return object|null
 */
function cluevo_get_latest_exp_log_entry($intUserId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG;

  $sql = "SELECT * FROM $table WHERE user_id = %d ORDER BY log_id DESC LIMIT 1";

  $result = $wpdb->get_row(
    $wpdb->prepare($sql, [$intUserId])
  );

  return $result;
}

/**
 * Checks whether a user has completed the given module
 *
 * Completion is determined by checking whether there is any progress
 * entry where the scaled score (percentage completion) is >= 1
 *
 * @param int $intUserId
 * @param int $intModuleId
 *
 * @return bool
 */
function cluevo_user_module_progress_complete($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $sql = "SELECT COUNT(*) FROM $table WHERE user_id = %d AND module_id = %d AND
    ((completion_status = 'completed' AND success_status = 'passed') OR
    (lesson_status = 'passed' OR lesson_status = 'completed'))";

  $count = $wpdb->get_var(
    $wpdb->prepare($sql, [$intUserId, $intModuleId])
  );

  return ((int)$count >= 1);
}

/**
 * Retrieves the best attempt of a user for a given module id as scaled score
 *
 * @param int $intUserId
 * @param int $intModuleId
 *
 * @return int
 */
function cluevo_get_users_best_module_attempt($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $sql = "SELECT COALESCE(MAX(score_scaled), 0) FROM $table WHERE user_id = %d AND module_id = %d AND credit = 'credit'";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$intUserId, $intModuleId])
  );

  return (!empty($result)) ? $result : 0;
}

function cluevo_user_completed_module($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $scorm12Success = (get_option("cluevo-modules-scorm-success") == "completed")
    ? "lesson_status = 'passed' OR lesson_status = 'completed'"
    : "lesson_status = 'passed'";

  $sql = "SELECT COUNT(*) FROM $table WHERE user_id = %d AND module_id = %d AND credit = 'credit' AND ( (completion_status = 'completed' AND success_status = 'passed') OR ($scorm12Success))";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$intUserId, $intModuleId])
  );

  return (!empty($result)) ? $result : 0;
}

/**
 * Retrieves progression points of a user for a given module
 *
 * Returns the sum of added exp
 *
 * @param mixed $intUserId
 * @param mixed $intModuleId
 *
 * @return int
 */
function cluevo_get_user_module_progression_points($intUserId, $intModuleId)
{
  global $wpdb;
  $tableLog = $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG;
  $tableProgress = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $sql = "SELECT COALESCE(SUM(exp_added), 0) AS points
    FROM $tableLog l
    INNER JOIN $tableProgress p ON
    l.source_module_id = p.module_id AND
    l.source_module_attempt_id = p.attempt_id AND
    l.user_id = p.user_id
    WHERE
    l.user_id = %d AND
    l.source_module_id = %d";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$intUserId, $intModuleId])
  );

  return (!empty($result)) ? (int)$result : 0;
}

/**
 * Retrieves the latest module progress entry of a user for given module id
 *
 * @param int $intUserId
 * @param int $intModuleId
 *
 * @return object|null
 */
function cluevo_get_module_progress($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sql = "SELECT * FROM $table WHERE module_id = %d AND user_id = %d ORDER BY attempt_id DESC LIMIT 1";
  $record = $wpdb->get_row($wpdb->prepare($sql, [$intModuleId, $intUserId]), ARRAY_A);
  $record = apply_filters('cluevo_get_module_progress', $record);
  return $record;
}

/**
 * Initializes module progress for a user and module
 *
 * @param int $intUserId
 * @param int $intModuleId
 * @param array $data
 *
 * @return object|null
 */
function cluevo_init_module_progress($intUserId, $intModuleId, $data, $strVersion)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $prefix = ($strVersion == "2004") ? "cmi.score" : "cmi.core.score";
  $scores = ["$prefix.raw" => 0, "$prefix.min" => 0, "$prefix.max" => 0, "$prefix.scaled" => 0];
  foreach ($scores as $score => $value) {
    if (array_key_exists($score, $data)) {
      $scores[$score] = $data[$score];
    }
  }

  $attemptId = cluevo_get_current_attempt_id($intUserId, $intModuleId) + 1;

  $sql = "INSERT IGNORE INTO $table SET score_raw = %s, score_min = %s, score_max = %s, score_scaled = %s, module_id = %d, user_id = %d, attempt_id = %d, completion_status = 'incomplete', success_status = 'unknown'";

  $wpdb->query($wpdb->prepare($sql, [$scores["$prefix.raw"], $scores["$prefix.min"], $scores["$prefix.max"], $scores["$prefix.scaled"], $intModuleId, $intUserId, $attemptId]));

  return cluevo_get_module_progress($intUserId, $intModuleId);
}

/**
 * Retrieves the current attempt id of a user for a given module
 *
 * Returns negative 1 if no attempt was found
 *
 * @param int $intUserId
 * @param int $intModuleId
 *
 * @return int
 */
function cluevo_get_current_attempt_id($intUserId, $intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $sql = "SELECT MAX(attempt_id) FROM $table WHERE user_id = %s AND module_id = %d";
  $result = $wpdb->get_var($wpdb->prepare($sql, [$intUserId, $intModuleId]));

  return (is_numeric($result)) ? $result : -1;
}

/**
 * Checks wheter a module is completed
 *
 * Determined by checking cmi.completion_status and cmi.succcess_status
 *
 * @param array $params
 *
 * @return bool
 */
function cluevo_is_module_completed($params)
{
  if (!empty($params['cmi.completion_status']) && !empty($params['cmi.success_status'])) {
    if ($params['cmi.completion_status']['value'] == 'completed' && $params['cmi.success_status']['value'] == 'passed') {
      return true;
    }
  }

  return false;
}

/**
 * Retrieves the scorm parameter set of a given attempt for a user and module
 *
 * If no attempt id is supplied the latest attempt is assumed. Also initializes
 * various parameters like learner name and scores. Accesses the imsmanifest
 * to initialize some of this data
 *
 * @param int $intModuleId
 * @param int $intUserId
 * @param int $intAttemptId (optional)
 *
 * @return array
 */
function cluevo_get_module_parameters($intModuleId, $intUserId = null, $intAttemptId = null)
{
  global $wpdb;

  $module = cluevo_get_module($intModuleId);
  $version = $module->scorm_version;
  $params = [];

  do_action("cluevo_start_get_module_parameters", $intUserId, $module, $intAttemptId);

  $dir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_name;
  $manifest = null;
  if (file_exists($dir)) {
    $it = new RecursiveDirectoryIterator($dir);
    foreach (new RecursiveIteratorIterator($it) as $file) {
      $name = basename($file);
      $curDir = $it->getBasename();
      if ($name == "imsmanifest.xml") {
        $manifest = $file;
        break;
      }
    }
  }

  if (!empty($manifest)) {
    $xml = simplexml_load_file($manifest);
    $ns = $xml->getNamespaces(true);
    foreach ($ns as $name => $value) {
      $xml->registerXPathNamespace($name, $value);
    }
    $objectives = json_decode(json_encode($xml->xpath("//imsss:objectives//@objectiveID")), TRUE);

    $objectiveIds = [];
    foreach ($objectives as $key => $attr) {
      $objectiveIds[] = $attr["@attributes"]["objectiveID"];
    }

    $objKeys = [
      "score._children" => 4,
      "score.scaled" => 0,
      "score.raw" => 0,
      "score.min" => 0,
      "score.max" => 0,
      "success_status" => "unknown",
      "completion_status" => "unknown",
      "progress_measure" => 0,
      "description" => ""
    ];

    $i = 0;
    $notFound = [];
    foreach ($objectiveIds as $id) {
      $found = false;
      foreach ($params as $parm => $value) {
        if (preg_match('/cmi.objectives\.\d*\.id/', $parm)) {
          if ($value == $id) {
            $found = true;
            $i++;
            break;
          }
        }
      }
      if (!$found) {
        $params["cmi.objectives.$i.id"] = ["value" => $id];
        foreach ($objKeys as $key => $default) {
          $params["cmi.objectives.$i.$key"] = ["value" => $default];
        }
        $i++;
      }
    }
    $params["cmi.objectives._children"] = ["value" => "id,score,success_status,completion_status,description"];
    $params["cmi.interactions._children"] = ["value" => "id,type,objectives,timestamp,correct_responses,weighting,learner_response,result,latency,description"];
  }

  $params["cmi.learner_name"] = ["value" =>  __("Guest", "cluevo")];
  $params["cmi.core.student_name"] = ["value" => __("User, Guest", "cluevo")];
  $params["cmi.core.student_id"] = ["value" => get_current_user_id()];
  $params["cmi.learner_id"] = ["value" => get_current_user_id()];
  //$params["cmi.core._children"] = [ "value" => "student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,lesson_mode,exit,session_time"];
  if ($version == "2004") {
    $params["cmi.mode"] = ["value" => "normal"];
  } else {
    $params["cmi.core.lesson_mode"] = ["value" => "normal"];
  }

  if (empty($params["cmi.core.lesson_status"])) {
    $params["cmi.core.lesson_status"] = ["value" => "not attempted"];
  }

  if (empty($params["cmi.completion_status"])) {
    $params["cmi.completion_status"] = ["value" => "not attempted"];
  }

  if (empty($params["cmi.success_status"])) {
    $params["cmi.success_status"] = ["value" => "unknown"];
  }

  if (!empty($intUserId)) {

    if (array_key_exists("cmi.learner_name", $params) || !array_key_exists("cmi.core.student_name", $params)) {
      $user = cluevo_get_user($intUserId);
      if (!empty($user)) {
        $params["cmi.learner_name"] = ["value" =>  $user->display_name];
        $studentName = $user->display_name;
        if (!empty($user->last_name)) {
          $studentName = $user->last_name;
          if (!empty($user->first_name)) {
            $studentName .= ', ' . $user->first_name;
          }
        }
        $params["cmi.core.student_name"] = ["value" => $studentName];
        $params["USER_EMAIL"] = ["value" => $user->user_email];
      }
    }

    $tableName = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
    $sql = "SELECT p.* FROM $tableName p WHERE module_id = %d AND user_id = %d AND attempt_id = %d";

    if (empty($intAttemptId)) {
      $intAttemptId = cluevo_get_current_attempt_id($intUserId, $intModuleId);
    }

    $results = $wpdb->get_results(
      $wpdb->prepare(
        $sql,
        [$intModuleId, $intUserId, $intAttemptId]
      ),
      ARRAY_A
    );

    foreach ($results as $row) {
      $params[$row["parameter"]] = $row;
    }
  }
  $params["cluevo.session_id"] = uniqid();

  do_action("cluevo_end_get_module_parameters", $intUserId, $module, $intAttemptId, $params);

  return $params;
}

/**
 * Retrieves all scorm parameters of a given user
 *
 * @param int $intUserId
 *
 * @return array
 */
function cluevo_get_users_module_parameters($intUserId)
{
  global $wpdb;
  $tableName = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;

  $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $tableName . ' WHERE user_id = %d ORDER BY module_id', array($intUserId)));

  $params = [];
  foreach ($results as $p) {
    if (!array_key_exists($p->module_id, $params)) {
      $params[$p->module_id] = [];
    }

    $params[$p->module_id][$p->parameter] = get_object_vars($p);
  }

  return $params;
}

function cluevo_get_users_attempt_count($intUserId, $intModuleId)
{
  if (empty($intUserId)) return 0;
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sql = "SELECT COUNT(*) AS count FROM {$table} WHERE module_id = %d AND user_id = %d AND credit = 'credit' AND (
    completion_status = 'completed' OR lesson_status IN ('passed', 'failed', 'completed'))";
  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$intModuleId, $intUserId])
  );
  return $result;
}
/**
 * Returns progress entries for the given arguments
 *
 * Possible arguments are: user_id, module_id, attempt_id, success_status, completion_status, lesson_status
 * Result can be paginated if pagination arguments are passed
 *
 * @param mixed $args
 * @param int $intPage
 * @param mixed $intPerPage
 *
 * @return array
 */
function cluevo_get_modules_progress_entries($args = [], $intPage = 0, $intPerPage = null)
{
  global $wpdb;
  $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $userTable = $wpdb->users;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $moduleTypeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;

  $sql = "SELECT p.*,
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
    INNER JOIN $userTable u
    ON p.user_id = u.ID
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
          foreach ($tags as $tag) {
            $t = trim($tag);
            if (!empty($t)) {
              $where[] = "m.tags LIKE '%%%s%%'";
              $parms[] = $t;
            }
          }
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
  return $result;
}

function cluevo_get_progress_entry($intUserId, $intModuleId, $intAttemptId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sql = "SELECT * FROM {$table} WHERE user_id = %d AND module_id = %d AND attempt_id = %d";
  $result = $wpdb->get_row(
    $wpdb->prepare($sql, [$intUserId, $intModuleId, $intAttemptId])
  );

  return $result;
}

function cluevo_set_progress_credit($intUserId, $intModuleId, $intAttemptId, $boolCredit)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $credit = ($boolCredit) ? 'credit' : 'no-credit';
  $sql = "UPDATE {$table} SET credit = %s WHERE user_id = %d AND module_id = %d AND attempt_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare($sql, [$credit, $intUserId, $intModuleId, $intAttemptId])
  );

  return $result;
}

function cluevo_delete_progress_entry($intUserId, $intModuleId, $intAttemptId)
{
  global $wpdb;
  $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $parmsTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
  $sql = "DELETE p, s FROM {$progressTable} p
    INNER JOIN {$parmsTable} s ON p.user_id = s.user_id AND p.module_id = s.module_id AND p.attempt_id = s.attempt_id
    WHERE p.user_id = %d AND p.module_id = %d AND p.attempt_id = %d";
  $result = $wpdb->query(
    $wpdb->prepare($sql, [$intUserId, $intModuleId, $intAttemptId])
  );
  return $result;
}

function cluevo_rate_module()
{
  $enabled = (empty(get_option('cluevo-module-ratings', false))) ? false : true;
  if (!$enabled) return;

  $uid = get_current_user_id();
  if (empty($uid)) return;
  check_ajax_referer("cluevo-module-rating", "nonce");

  $moduleId = (int)$_POST["moduleId"];
  $rating = (int)$_POST["rating"];
  $page = cluevo_get_module_metadata_page($moduleId);
  if (empty($page)) return;

  $pageId = $page->ID;

  $pageKey = "cluevo-module-rating-{$uid}";
  $averageKey = "cluevo-module-rating-avg";
  $userKey = "cluevo-module-rating-{$moduleId}";

  $attemptId = (!empty($_POST["progress"]["attempt_id"]) && is_numeric($_POST["progress"]["attempt_id"]))
    ? (int)$_POST["progress"]["attempt_id"]
    : 0;
  $progress = (!empty($_POST["progress"]["progress"]) && is_numeric($_POST["progress"]["progress"]))
    ? (float)$_POST["progress"]["progress"]
    : null;

  $pageRating = get_post_meta($pageId, $pageKey, true);
  if (empty($pageRating)) {
    $pageRating = [["user_id" => $uid, "rating" => $rating, "ts" => time()]];
  } else {
    $pageRating[] = ["user_id" => $uid, "rating" => $rating, "ts" => time()];
  }
  update_post_meta($pageId, $pageKey, $pageRating);

  $pageAverage = get_post_meta($pageId, $averageKey, true);
  if (empty($pageAverage)) {
    $pageAverage = [
      "value" => $rating,
      "count" => 1,
      "total" => $rating,
      "min" => $rating,
      "max" => $rating,
      "ts" => time()
    ];
  } else {
    $pageAverage["ts"] = time();
    $pageAverage["total"] += $rating;
    if ($rating < $pageAverage["min"] || empty($pageAverage["min"])) {
      $pageAverage["min"] = $rating;
    }
    if ($rating > $pageAverage["max"] || empty($pageAverage["max"])) {
      $pageAverage["max"] = $rating;
    }
    $pageAverage["count"]++;
    $pageAverage["value"] = round($pageAverage["total"] / $pageAverage["count"], 2);
  }
  update_post_meta($pageId, $averageKey, $pageAverage);

  $userRating = get_user_meta($uid, $userKey, true);
  if (empty($userRating)) {
    $userRating = [
      "ratings" => [[
        "rating" => $rating,
        "attempt" => $attemptId,
        "progress" => $progress,
        "ts" => time()
      ]],
      "avg" => [
        "value" => $rating,
        "count" => 1,
        "total" => $rating,
        "min" => $rating,
        "max" => $rating,
        "ts" => time()
      ]
    ];
  } else {
    $userRating["ratings"][] = [
      "rating" => $rating,
      "attempt" => $attemptId,
      "progress" => $progress,
      "ts" => time()
    ];
    $userRating["avg"]["ts"] = time();
    if ($rating < $userRating["avg"]["min"] || empty($userRating["avg"]["min"])) {
      $userRating["avg"]["min"] = $rating;
    }
    if ($rating > $userRating["avg"]["max"] || empty($userRating["avg"]["max"])) {
      $userRating["avg"]["max"] = $rating;
    }
    $userRating["avg"]["total"] += $rating;
    $userRating["avg"]["count"]++;
    $userRating["avg"]["value"] = round($userRating["avg"]["total"] / $userRating["avg"]["count"], 2);
  }
  update_user_meta($uid, $userKey, $userRating);

  $userAverage = get_user_meta($uid, $averageKey, true);
  if (empty($userAverage)) {
    $userAverage = [
      "value" => $rating,
      "count" => 1,
      "total" => $rating,
      "min" => $rating,
      "max" => $rating,
      "ts" => time()
    ];
  } else {
    $userAverage["ts"] = time();
    if ($rating < $userAverage["min"] || empty($userAverage["min"])) {
      $userAverage["min"] = $rating;
    }
    if ($rating > $userAverage["max"] || empty($userAverage["max"])) {
      $userAverage["max"] = $rating;
    }
    $userAverage["total"] += $rating;
    $userAverage["count"]++;
    $userAverage["value"] = round($userAverage["total"] / $userAverage["count"], 2);
  }
  update_user_meta($uid, $averageKey, $userAverage);

  wp_die(true);
}

function cluevo_send_first_clear_notifications($args)
{
  if (empty($itemId)) return;
  $userId = (int)$args["user_id"];
  $moduleId = (int)$args["module_id"];
  $itemId = (int)$args["item_id"];
  $attemptId = (int)$args["attempt_id"];
  $state = $args["state"];
  $item = cluevo_get_learning_structure_item($itemId, $userId);
  $item->load_settings();
  if (empty($item)) return;
  $enabled = $item->get_setting("notifications-enabled");
  if (empty($enabled)) return;
  $targets = $item->get_setting("notification-recipients");
  $mails = explode(",", $targets);
  $recipients = [];
  if (is_array($mails)) {
    foreach ($mails as $mail) {
      $addr = trim($mail);
      if (is_email($addr)) {
        $recipients[] = $addr;
      }
    }
  } else {
    $targets = trim((string)$targets);
    if (is_email($targets)) {
      $recipients = [$targets];
    }
  }
  if (empty($recipients)) return;
  $module = cluevo_get_module($moduleId);
  $user = get_user_by('id', $userId);
  $name = $user->data->user_login;
  $email = $user->data->user_email;
  $displayName = $user->data->display_name;
  if (empty($module)) return;
  $subject = esc_html(__("[CLUEVO LMS] Module successfully completed", "cluevo"));
  $text = esc_html(sprintf(
    __("A user has completed the module %s on attempt #%d:\n\nUser ID: %d\nUsername: %s\nE-Mail: %s\nDisplay Name: %s", "cluevo"),
    $module->module_name,
    (int)$attemptId + 1,
    (int)$userId,
    $name,
    $email,
    $displayName
  ));
  $headers = array('Content-Type: text/plain; charset=UTF-8'); // Add from header for local testing: 'From: test@test.test'
  if (is_array($recipients)) {
    foreach ($recipients as $mail) {
      wp_mail($mail, $subject, $text, $headers);
    }
  } else {
    wp_mail($recipients, $subject, $text, $headers);
  }
}
