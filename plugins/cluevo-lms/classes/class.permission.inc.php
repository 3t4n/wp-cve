<?php
class CluevoPermission
{
  public $perm_id;
  public $item_id;
  public $perm;
  public $access_level;
  public $date_added;
  public $date_expired;

  public static function from_std_class($obj)
  {
    $result = new static();
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        $result->$prop = $obj->$prop;
      }
    }

    return $result;
  }

  public function save()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;

    $insert = false;
    if ($this->access_level === null) {
      $sql = "DELETE FROM $table WHERE item_id = %d AND perm = %s";
      $args = [$this->item_id, $this->perm];
    } else {
      $insert = true;
      $sql = "INSERT INTO $table SET item_id = %d, perm = %s, access_level = %d
        ON DUPLICATE KEY UPDATE access_level = %d";
      $args = [$this->item_id, $this->perm, $this->access_level, $this->access_level];
      if (version_compare(get_option(CLUEVO_DB_VERSION_OPT_KEY), CLUEVO_PLUGIN_DB_VERSION, ">=")) {
        if ($this->date_expired) {
          $sql = "INSERT INTO $table SET item_id = %d, perm = %s, access_level = %d, date_expired = FROM_UNIXTIME(%d)
            ON DUPLICATE KEY UPDATE access_level = %d, date_expired = FROM_UNIXTIME(%d)";
          $args = [$this->item_id, $this->perm, $this->access_level, $this->date_expired, $this->access_level, $this->date_expired];
        } else {
          $sql = "INSERT INTO $table SET item_id = %d, perm = %s, access_level = %d, date_expired = NULL
            ON DUPLICATE KEY UPDATE access_level = %d, date_expired = NULL";
          $args = [$this->item_id, $this->perm, $this->access_level, $this->access_level];
        }
      }
    }

    $result = $wpdb->query(
      $wpdb->prepare($sql, $args)
    );

    if ($insert && empty($this->perm_id)) {
      $perm_id = $wpdb->get_var($wpdb->prepare("SELECT perm_id FROM {$table} WHERE item_id = %d AND perm = %s", [$this->item_id, $this->perm]));
      if (!empty($perm_id)) {
        $this->perm_id = (int)$perm_id;
      }
    }

    return ($result !== false);
  }
}
