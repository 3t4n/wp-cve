<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_get_user_module_stats($intUserId) {
  global $wpdb;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $progressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;

  $sql = "SELECT
    m.module_id,
    m.module_name,
    COUNT(mp.attempt_id) AS attempts,
    MAX(mp.score_raw) AS score_highest,
    MIN(mp.score_raw) AS score_lowest,
    AVG(mp.score_raw) AS score_average,
    IF(mp.completion_status = 'completed', true, false) AS completed,
    IF(mp.success_status = 'passed', true, false) AS passed
    FROM $moduleTable m
    INNER JOIN $progressTable mp ON m.module_id = mp.module_id
    WHERE mp.user_id = %d
    GROUP BY m.module_id";

  $results = $wpdb->get_results(
    $wpdb->prepare($sql, [ $intUserId ])
  );

  return $results;
}

function cluevo_get_level_from_exp($curExp) {
  $table = cluevo_get_exp_table();
  $userLevel = 0;
  $next = 0;
  $remaining = 0;
  foreach ($table as $level => $exp) {
    $userLevel = $level;
    $next = $exp;
    $remaining = $exp - $curExp;

    if ($curExp< $next)
      break;
  }

  $titles = get_option('cluevo-level-titles');
  $title = '';
  if (!empty($titles) && is_array($titles)) {
    foreach ($titles as $lvl => $t) {
      if ($userLevel < $lvl)
        break;

      $title = $t;
    }
  }

  $pct = $next > 0 ? round(($curExp / $next) * 100, 2) : 100;

  return ["current" => $userLevel, "next" => $next, "remaining" => $remaining, "exp" => $curExp, 'title' => $title, 'pct' =>  $pct];
}

function cluevo_get_users_competences($intUserId = null) {
  $userId = !empty($intUserId) ? $intUserId : get_current_user_id();
  $comps = cluevo_get_competences();

  foreach ($comps as $c) {
    $c->load_modules();
    $c->load_score();
  }

  return $comps;
}

function cluevo_get_users_competence_areas($intUserId = null) {
  $userId = !empty($intUserId) ? $intUserId : get_current_user_id();
  $areas = cluevo_get_competence_areas();

  foreach ($areas as $a) {
    $a->load_competences();
    $a->load_modules();
    $a->load_score();
  }

  return $areas;
}

function cluevo_get_users_competence_scores($intUserId) {
  if (empty($intUserId)) return [];
  global $wpdb;

  $competenceModuleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $competenceTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $moduleProgressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $competencesToAreasTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $competenceAreasTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;

  $sql = "SELECT s.competence_id, s.competence_name, s.metadata_id, s.competence_area_id, s.competence_area_name, s.competence_area_metadata_id, IF(SUM(s.score) > 1,
1, SUM(s.score)) AS score, GROUP_CONCAT(CONCAT(s.module_id, ':', s.coverage, ':', s.score) SEPARATOR ';') AS modules
    FROM (
      SELECT c.competence_id, c.competence_name, c.metadata_id, mp.module_id, ca.competence_area_id, ca.competence_area_name, ca.metadata_id AS competence_area_metadata_id, COALESCE(mp.score_scaled,0) * COALESCE(mc.competence_coverage, 0) AS 'score', COALESCE(mc.competence_coverage, 0) AS 'coverage'
      FROM $competenceTable c
      LEFT JOIN $competencesToAreasTable cta ON cta.competence_id = c.competence_id
      LEFT JOIN $competenceAreasTable ca ON ca.competence_area_id = cta.competence_area_id
      LEFT JOIN $competenceModuleTable mc ON mc.competence_id = c.competence_id
      INNER JOIN (
        SELECT module_id, MAX(score_scaled) AS score_scaled FROM $moduleProgressTable WHERE user_id = %d GROUP BY module_id
      ) mp ON mc.module_id = mp.module_id
  ) s
  GROUP BY s.competence_id, s.competence_area_id, s.metadata_id, s.competence_area_metadata_id";

  $results = $wpdb->get_results(
    $wpdb->prepare($sql, [ $intUserId ])
  );

  if (!empty($results)) {
    foreach ($results as $key => $res) {
      if (empty($res) || empty($res->modules)) continue;
      $modules = explode(";", $res->modules);
      $list = [];
      if (!empty($modules)) {
        foreach ($modules as $m) {
          $parts = explode(":", $m);
          if (!empty($parts) && count($parts) === 3 && cluevo_can_user_access_module($parts[0])) {
            $list[] = (object)[
              "id" => $parts[0],
              "coverage" => $parts[1],
              "score" => $parts[2]
            ];
          }
        }
      }
      $results[$key]->modules = $list;
    }
  }
  return $results;
}

function cluevo_get_users_competence_area_scores($intUserId) {
  if (empty($intUserId)) return [];

  $comps = cluevo_get_users_competence_scores($intUserId);
  $areas = [];
  if (!empty($comps)) {
    foreach ($comps as $comp) {
      $curArea = $comp->competence_area_id;
      $name = (empty($comp->competence_area_name)) ? __("Standalone Competences", "cluevo") : $comp->competence_area_name;
      if (!array_key_exists($curArea, $areas)) {
        $areas[$curArea] = [
          "competence_area_id" => $curArea,
          "competence_area_name" => $name,
          "metadata_id" => $comp->competence_area_metadata_id,
          "score" => 0, 
          "competences" => [] 
        ];
      }
      $areas[$curArea]["competences"][] = $comp;
    }
  }
  $result = [];
  foreach ($areas as $area) {
    $score = 0.0;
    if (!empty($area["competences"])) {
      foreach ($area["competences"] as $c) {
        $score += $c->score;
      }
      $score /= count($area["competences"]);
      $area["score"] = $score;
    }
    $result[] = (object)$area;
  }
  return $result;
}

function cluevo_get_users_module_stats($intUserId = null) {
  if (empty($intUserId)) return;

  global $wpdb;

  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $moduleProgressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $scormParmTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;

  $sql = "SELECT m.module_id, m.module_name,
    COUNT(p.attempt_id) AS attempt_count, MIN(p.progress) AS min_progress, MAX(p.progress) AS max_progress,
    MIN(p.score_scaled) AS min_score, MAX(p.score_scaled) AS max_score,
    MIN(p.date_started) AS first_attempt, MAX(p.date_started) AS last_attempt,
    (SELECT COUNT(id) FROM $scormParmTable WHERE user_id = p.user_id AND module_id = m.module_id) AS scorm_parameter_count
    FROM $moduleTable m
    LEFT JOIN $moduleProgressTable p ON m.module_id = p.module_id
    WHERE p.user_id = %d
    GROUP BY m.module_id, m.module_name";

  $result = $wpdb->get_results(
    $wpdb->prepare($sql, [ $intUserId ])
  );

  return $result;
}
?>
