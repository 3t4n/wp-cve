<?php
class CluevoTreeLeaf
{
  public $item_id;
  public $parent_id;
  public $metadata_id;
  public $children = [];
  public $completed_children = [];
  public $completed;
  public $name = '';

  public $access_level = 0;
  public $access = false;
  public $access_status = null;
  public $permission = null;
  public $is_trainer = null;
  public $expires = null;

  public function load_settings() {}
  public function get_setting($strKey, $strPrefix = '') {}
}
