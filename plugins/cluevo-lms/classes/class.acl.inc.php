<?php
class CluevoAcl {
  public $permissions = [];

  public $item_id;
  public $users = [];
  public $groups = [];

  public function add_permission(CluevoPermission $perm) {
    $parts = explode(':', $perm->perm);
    if (count($parts) == 2) {
      $type = $parts[0];
      $id = $parts[1];
      switch($type) {
        case "g":
          $this->groups[$id] = $perm->access_level;
          break;
        case "u":
          $this->users[$id] = $perm->access_level;
          break;
      }
    }
    $this->permissions[] = $perm;
  }
}
?>
