<?php
class CluevoUser {
  public $ID;
  public $user_id;
  public $user_login;
  public $user_nicename;
  public $display_name;
  public $first_name;
  public $last_name;
  public $user_email;
  public $total_points;
  public $total_exp;
  public $date_last_seen;
  public $date_added;
  public $date_modified;
  public $current_level;
  public $level;
  public $groups = [];
  public $trainer_groups = [];
  private $groups_loaded = false;

  public $current_competence_area = -1;
  public $competence_area_count = 0;
  public $in_the_competence_area_loop = false;
  public $competence_area_history = [];

  public $competences = [];
  public $competence_areas = [];
  public $competence_area = null;
  public $competence = null;
  public $current_competence = -1;
  public $competence_count = 0;
  public $in_the_competence_loop = false;
  public $competence_history = [];

  public $competence_modules = [];
  public $competence_module = null;
  public $in_the_competence_module_loop = false;
  public $competence_module_count = 0;
  public $current_competence_module = -1;

  public function __construct($intUserId = null) {
    if (!$intUserId !== null) {
      $this->ID = $intUserId;
      $this->user_id = $intUserId;
    }
  }

  public function __get($prop) {
    switch ($prop) {
    case "user_id":
      return $this->ID;
      break;
    case "groups":
      if (!$this->groups_loaded) $this->load_groups();
      return $this->groups;
      break;
    default:
      if (property_exists($this, $prop))
        return $this->$prop;
      break;
    }
  }

  public function load_groups() {
    $cluevo_groups = cluevo_turbo_get_groups();
    $addedEmailGroups = [];
    $domain = null;
    if (!empty($cluevo_groups)) {
      if (!empty($this->user_email)) {
        $domain = '@' . explode('@', $this->user_email)[1];
      }
      foreach ($this->groups as $key => $group) {
        foreach ($cluevo_groups as $cGroup) {
          if ((int)$group == (int)$cGroup->group_id) {
            $this->groups[$key] = $cGroup;
            if (in_array($this->user_id, $this->groups[$key]->trainers)) {
              $this->groups[$key]->is_trainer = true;
            }
            if ($cGroup->group_name == $domain) $addedEmailGroups[] = $cGroup->group_id;
          }
        }
      }
      foreach ($cluevo_groups as $key => $cGroup) {
        if (in_array($cGroup->group_id, $addedEmailGroups)) continue;
        if ($cGroup->group_name === $domain) {
          if (in_array($this->user_id, $cGroup->trainers)) {
            $cGroup->is_trainer = true;
          }
          $this->groups[] = $cGroup;
        }
      }
    }
    $this->groups_loaded = true;
  }

  public function load_competence_areas() {
    $this->competence_areas = cluevo_get_users_competence_area_scores((int)$this->ID);
    $this->competence_area = (!empty($this->competence_areas)) ? $this->competence_areas[0] : null;
    $this->competence_area_count = count($this->competence_areas);

    $this->competences = (!empty($this->competence_area) && !empty($this->competence_area->competences))
      ? $this->competence_area->competences
      : [];
    $this->competence = (!empty($this->competences)) ? $this->competences[0] : null;
    $this->competence_count = count($this->competences);
    $this->rewind_competences_modules();
  }

  public function has_competence_areas() {
    if ($this->current_competence_area + 1 < $this->competence_area_count) {
      return true;
    } elseif ( $this->current_competence_area + 1 == $this->competence_area_count && $this->competence_area_count > 0 ) {
      $this->rewind_competence_areas();
    }

    $this->in_the_competence_area_loop = false;
    return false;
  }

  public function the_competence_area() {
    $this->in_the_competence_area_loop = true;
    $this->next_competence_area();
    return $this->competence_area;
  }

  public function next_competence_area() {
    $this->current_competence_area++;
    $this->competence_area = $this->competence_areas[$this->current_competence_area];

    $this->rewind_competence_area_competences();
    return $this->competence_area;
  }

  public function rewind_competence_areas() {
    $this->current_competence_area = -1;
    $this->competence_area = (!empty($this->competence_areas)) ? $this->competence_areas[0] : null;
  }

  public function rewind_competence_area_competences() {
    $this->current_competence = -1;
    $this->competences = (!empty($this->competence_area) && !empty($this->competence_area->competences))
      ? $this->competence_area->competences
      : null;
    $this->competence = (!empty($this->competences)) ? $this->competences[0] : null;
    $this->competence_count = count($this->competences);
    $this->rewind_competences_modules();
  }

  public function load_competences() {
    $this->competences = cluevo_get_users_competence_scores((int)$this->ID);
    $this->competence = (!empty($this->competences)) ? $this->competences[0] : null;
    $this->competence_count = count($this->competences);

    $this->competence_modules = $this->competence->modules;
    $this->competence_module = (!empty($this->competence->modules)) ? $this->competence->modules[0] : null;
    $this->competence_module_count = count($this->competence_modules);
  }

  public function has_competences() {
    if ($this->current_competence + 1 < $this->competence_count) {
      return true;
    } elseif ( $this->current_competence + 1 == $this->competence_count && $this->competence_count > 0 ) {
      $this->rewind_competences();
    }

    $this->in_the_competence_loop = false;
    return false;
  }

  public function the_competence() {
    $this->in_the_competence_loop = true;
    $this->next_competence();
    return $this->competence;
  }

  public function next_competence() {
    $this->current_competence++;
    $this->competence = $this->competences[$this->current_competence];

    $this->competence_modules = $this->competence->modules;
    $this->competence_module = (!empty($this->competence->modules)) ? $this->competence->modules[0] : null;
    $this->competence_module_count = (!empty($this->competence_modules)) ? count($this->competence_modules) : 0;

    $this->rewind_competences_modules();
    return $this->competence;
  }

  public function rewind_competences() {
    $this->current_competence = -1;
    $this->competence = (!empty($this->competences)) ? $this->competences[0] : null;
  }

  public function competence_has_modules() {
    if ($this->current_competence_module + 1 < $this->competence_module_count) {
      return true;
    } elseif ( $this->current_competence_module + 1 == $this->competence_module_count && $this->competence_module_count > 0 ) {
      $this->rewind_competences_modules();
    }

    $this->in_the_competence_module_loop = false;
    return false;
  }

  public function the_competence_module() {
    $this->in_the_competence_module_loop = true;
    $this->next_competence_module();
    return $this->competence_module;
  }

  public function next_competence_module() {
    $this->current_competence_module++;
    $this->competence_module = $this->competence_modules[$this->current_competence_module];
    return $this->competence_module;
  }


  public function rewind_competences_modules() {
    $this->current_competence_module = -1;
    $this->competence_module = (!empty($this->competence_modules)) ? $this->competence_modules[0] : null;
  }

  public static function from_std_class($obj) {
    $result = new static();
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        switch($prop) {
          case "trainer_groups":
          case "groups":
            if (!empty($obj->$prop))
              $obj->$prop = explode(",", $obj->$prop);
            else
              $obj->$prop = [];
            break;
          case "ID":
            $result->user_id = $obj->$prop;
          default:
        }
        $result->$prop = $obj->$prop;
      }
    }

    $result->level = cluevo_get_level_from_exp($result->total_exp);
    $result->current_level = (!empty($result->level)) ? $result->level["current"] : 1;

    return $result;
  }

  public static function from_array($arr) {
    $result = new static();
    foreach ($arr as $prop => $value) {
      if (property_exists($result, $prop)) {
        switch($prop) {
          case "groups":
            if (!empty($value))
              $value = explode(",", $value);
            else
              $value = [];
          default:
        }
        $result->$prop = $value;
      }
    }

    return $result;
  }

  public static function get_guest_user() {
    global $wpdb;
    $userDataTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_DATA;

    //cluevo_start_session();
    $userId = null;
    if (!empty($_SESSION["cluevo_guest_user_id"]) && is_numeric($_SESSION["cluevo_guest_user_id"])) {
      (int)$_SESSION["cluevo_guest_user_id"];
    }
    if (!empty($userId) && $userId > 0) {
      $sql = "SELECT * FROM $userDataTable WHERE user_id = %d";
      $row = $wpdb->get_row(
        $wpdb->prepare( $sql, [ $userId ] )
      );
      if (!empty($row)) {
        $user = new CluevoUser();
        $user->ID = null;
        $user->user_id = $row->user_id;
        $user->user_login = __("Guest", "cluevo");
        $user->user_nicename = __("Guest", "cluevo");
        $user->display_name = __("Guest", "cluevo");
        $user->total_exp = $row->total_exp;
        $user->total_points = $row->total_points;
        $user->date_last_seen = $row->date_last_seen;
        $user->date_added = $row->date_added;
        $user->date_modified = $row->date_modified;
        $user->groups = [ CLUEVO_DEFAULT_GROUP_GUEST ];
        $user->load_groups();
        return $user;
      }
    } else {
      do {
        $randId = rand(100000, 9999999);
      } while (cluevo_get_user($randId) !== null);
      $sql = "INSERT INTO $userDataTable SET user_id = %d";
      $result = $wpdb->query(
        $wpdb->prepare( $sql, [ "user_id" => $randId ] )
      );
      $addGroupRes = cluevo_add_users_to_group($randId, CLUEVO_DEFAULT_GROUP_GUEST, true);

      if ($result !== false) {
        $user = new CluevoUser();
        $user->ID = null;
        $user->user_id = $randId;
        $user->user_login = __("Guest", "cluevo");
        $user->user_nicename = __("Guest", "cluevo");
        $user->display_name = __("Guest", "cluevo");
        $user->groups = [ CLUEVO_DEFAULT_GROUP_GUEST ];
        $user->load_groups();
        $_SESSION["cluevo_guest_user_id"] = $randId;
        return $user;
      }
    }

    return false;
  }
}
