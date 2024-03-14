<?php
require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");

class CluevoCompetenceArea {
  public $competence_area_id = 0;
  public $competence_area_name = "";
  public $competence_area_type = "system";
  public $metadata_id = 0;
  public $user_added_id;
  public $user_added;
  public $date_added;
  public $user_modified_id;
  public $user_modified;
  public $date_modified;

  public $competences = [];
  public $modules = [];
  public $score = 0;

  public function load_competences() {
    $list = [];
    if (!empty($this->competences)) {
      foreach ($this->competences as $c) {
        $comp = cluevo_get_competence($c);
        $comp->load_modules();
        $comp->load_score();
        if (!empty($comp))
          $list[] = $comp;
      }
    }

    $this->competences = $list;
  }

  public function load_modules() {
    $list = [];
    if (!empty($this->modules)) {
      foreach ($this->modules as $m) {
        $module = cluevo_get_module($m);
        if (!empty($module))
          $list[] = $module;
      }
    }

    $this->modules = $list;
  }

  public function load_score()
  {
    if (empty($this->competences)) return;
    $sum = 0;
    foreach ($this->competences as $c) {
      if (empty($c->score->value)) continue;
      $sum += $c->score->value;
    }
    $this->score = $sum / count($this->competences);
  }

  public static function from_std_class($obj) {
    $result = new CluevoCompetenceArea();
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        if ($prop === "competences" || $prop === "modules") {
          $values =  (!empty($obj->$prop)) ? explode(";", $obj->$prop) : [];
          $result->$prop = [];
          if (!empty($values)) {
            foreach ($values as $v) {
              if (!empty(trim($v)))
                array_push($result->$prop, trim($v));
                //$result->$prop[] = trim($v);
            }
          }
        } else {
          $result->$prop = $obj->$prop;
        }
      }
    }

    return $result;
  }

  public static function from_array($arr) {
    $result = new CluevoCompetenceArea();
    foreach ($arr as $prop => $value) {
      if (property_exists($result, $prop)) {
        if ($prop === "competences" || $prop === "modules") {
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

?>
