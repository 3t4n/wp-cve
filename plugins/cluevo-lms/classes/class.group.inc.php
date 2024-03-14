<?php
class CluevoGroup {
  public $group_id;
  public $group_name;
  public $group_description;
  public $users = [];
  public $trainers = [];
  public $tags = [];
  public $date_added;
  public $date_modified;
  public $metadata_id;
  private $users_loaded = false;
  public $protected = false;
  public $is_trainer = false;

  public function __construct($mixedGroup = null) {
    if (!empty($mixedGroup)) {
      $group = cluevo_get_user_group($mixedGroup);
      if ($group !== false) {
        foreach ($group as $prop => $value) {
          $this->$prop = $value;
        }
      }
    }
  }

  public function load_users() {
    $emailUsers = $this->get_email_users();
    $this->users = array_unique(array_merge($this->users, $emailUsers));
    if (!$this->users_loaded && !empty($this->users)) {
      $users = get_users( [ "include" => $this->users ] );

      foreach ($users as $user) {
        foreach ($this->users as $key => $u) {
          if (is_numeric($u) && $user->ID == $u) {
            $this->users[$key] = $user;
          }
        }

        foreach ($this->trainers as $key => $t) {
          if (is_numeric($t) && $user->ID == $t) {
            $this->trainers[$key] = $user;
          }
        }
      }

      $this->users_loaded = true;
    }
  }

  public function get_email_users() {
    $result = [];
    if (strpos($this->group_name, '@') === 0) {
      global $wpdb;
      $table = $wpdb->users;
      $uids = $wpdb->get_results(
        $wpdb->prepare("SELECT ID FROM $table WHERE user_email RLIKE %s", [ $this->group_name ])
      );
      $result = array_map(function($el) { return $el->ID; }, $uids);
    }

    return $result;
  }

  public static function from_std_class($obj) {
    $result = new static();
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        switch ($prop) {
        case "tags":
        case "trainers":
        case "users":
          if (!empty($obj->$prop)) {
            $list = explode(",", $obj->$prop);
            $obj->$prop = $list;
          } else {
            $obj->$prop = array();
          }
        default: 
          $result->$prop = $obj->$prop;
        }
      }
    }

    return $result;
  }

  public static function from_array($arr) {
    $result = new static();
    foreach ($arr as $prop => $value) {
      if (property_exists($result, $prop)) {
        switch ($prop) {
        case "trainers":
        case "users":
          if (!empty($value)) {
            $value = explode(",", $value);
          } else {
            $value = array();
          }
        default: 
          $result->$prop = $value;
        }
      }
    }

    return $result;
  }
}
?>
