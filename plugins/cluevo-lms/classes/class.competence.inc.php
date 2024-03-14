<?php
require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");

class CluevoCompetence
{
  public $competence_id = 0;
  public $competence_name = "";
  public $competence_type = "system";
  public $metadata_id = 0;
  public $metadata_post;
  public $user_added_id;
  public $user_added;
  public $date_added;
  public $user_modified_id;
  public $user_modified;
  public $date_modified;

  public $areas = [];
  public $modules = [];
  public $items = [];
  public $score;
  public $total_coverage = 0;

  public function load_areas()
  {
    $list = [];
    if (!empty($this->areas)) {
      foreach ($this->areas as $a) {
        $area = cluevo_get_competence_area($a);
        if (!empty($area))
          $list[] = $area;
      }
    }

    $this->areas = $list;
  }

  public function load_modules()
  {
    $list = [];
    if (!empty($this->modules)) {
      $this->total_coverage = 0;
      foreach ($this->modules as $m) {
        $id = $m[0];
        $coverage = $m[1];
        $module = cluevo_get_module($id);
        if (!empty($module)) {
          $module->competence_coverage = (float)$coverage;
          $this->total_coverage += (float)$coverage;
          if ($this->total_coverage > 1) {
            $this->total_coverage = 1;
          }
          $items = cluevo_get_modules_items($module->module_id, true, false);
          if (!empty($items)) {
            $module->items = [];
            foreach ($items as $item) {
              $treeItem = cluevo_get_learning_structure_item($item);
              if (!empty($treeItem) && $treeItem->access_level > 0) {
                $module->items[] = $treeItem;
              }
            }
          }
          if (!empty($module->items) || current_user_can("administrator")) {
            $list[] = $module;
          }
        }
      }
    }

    $this->modules = $list;
  }

  public function load_metadata()
  {
    if ($this->metadata_id !== 0) {
      $this->metadata_post = get_post($this->metadata_id);
    }
  }

  public function load_score()
  {
    $intUserId = get_current_user_id();
    if (empty($intUserId)) return [];
    global $wpdb;

    $competenceModuleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
    $competenceTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
    $moduleProgressTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

    $sql = "SELECT IF(SUM(s.score) > 1,
1, SUM(s.score)) AS score, GROUP_CONCAT(CONCAT(s.module_id, ':', s.coverage, ':', s.score) SEPARATOR ';') AS modules
    FROM (
      SELECT c.competence_id, c.competence_name, c.metadata_id, m.module_id, COALESCE(mp.score_scaled,0) * COALESCE(mc.competence_coverage, 0) AS 'score', COALESCE(mc.competence_coverage, 0) AS 'coverage'
      FROM $competenceTable c
      LEFT JOIN $competenceModuleTable mc ON mc.competence_id = c.competence_id
      LEFT JOIN $moduleTable m ON m.module_id = mc.module_id
      LEFT JOIN (
        SELECT module_id, MAX(score_scaled) AS score_scaled FROM $moduleProgressTable WHERE user_id = %d GROUP BY module_id
      ) mp ON mc.module_id = mp.module_id
  ) s
  WHERE s.competence_id = %d
  GROUP BY s.competence_id, s.metadata_id";

    $res = $wpdb->get_row(
      $wpdb->prepare($sql, [$intUserId, $this->competence_id])
    );

    $result = new stdClass();
    if (!empty($res) && !empty($res->modules)) {
      $modules = explode(";", $res->modules);
      $list = [];
      if (!empty($modules)) {
        foreach ($modules as $m) {
          $parts = explode(":", $m);
          if (!empty($parts) && count($parts) === 3 && cluevo_can_user_access_module($parts[0])) {
            $module = cluevo_get_module($parts[0]);
            $list[] = (object)[
              "id" => $parts[0],
              "name" => $module->module_name,
              "coverage" => $parts[1],
              "score" => $parts[2]
            ];
          }
        }
      }
      $result->value = $res->score;
      $result->modules = $list;
    }
    return $this->score = $result;
  }

  public static function from_std_class($obj)
  {
    $result = new CluevoCompetence();
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        if ($prop === "areas" || $prop === "modules") {
          $values =  (!empty($obj->$prop)) ? explode(";", $obj->$prop) : [];
          $result->$prop = [];
          if (!empty($values)) {
            foreach ($values as $v) {
              if (!empty(trim($v)))
                if ($prop === "modules") {
                  $tmp = explode(":", $v);
                  $result->total_coverage += (float)$tmp[1];
                  array_push($result->$prop, [(int)trim($tmp[0]), (float)trim($tmp[1])]);
                  //$result->$prop[] = [ (int)trim($tmp[0]), (float)trim($tmp[1]) ];
                } else {
                  array_push($result->$prop, trim($v));
                  //$result->$prop[] = trim($v);
                }
            }
          }
        } else {
          $result->$prop = $obj->$prop;
        }
      }
    }

    return $result;
  }

  public static function from_array($arr)
  {
    $result = new CluevoCompetence();
    foreach ($arr as $prop => $value) {
      if (property_exists($result, $prop)) {
        if ($prop === "areas" || $prop === "modules") {
          $values =  explode(";", $value);
          $result->$prop = [];
          if (!empty($values)) {
            foreach ($values as $v) {
              if (!empty(trim($v)))
                array_push($result->$prop, trim($v));
              //$result->$prop[] = trim($v);
            }
          }
        } else {
          $result->$prop = $value;
        }
      }
    }

    return $result;
  }
}
