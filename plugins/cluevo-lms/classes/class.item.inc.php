<?php
require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");

class CluevoItem extends CluevoTreeLeaf
{
  public $item_id = 0;
  public $parent_id = 0;
  public $metadata_id = 0;
  public $tree_id;
  public $level = 0;
  public $name = "";
  public $type = "";
  public $path = "/";
  public $sort_order = 0;
  public $points_worth = 50;
  public $points_required = 0;
  public $practice_points = 0;
  public $level_required = 0;
  public $login_required = 0;
  public $repeat_interval = 0;
  public $repeat_interval_type = "d";
  public $date_added = "";
  public $date_modified = "";
  public $module_id = null;
  public $module_index = null;
  public $access_level;
  public $display_mode = "";
  public $scorm_version = "";
  public $published = 1;
  public $completed = 1;
  public $expires = null;
  public $permalink = null;
  public $thumb = null;
  public $permission = null;
  public $competences = [];
  public $competence_areas = [];

  public $dependencies = [
    "other" => [
      "normal" => [], "inherited" => [], "blocked" => [], "all" => []
    ],
    "modules" => [
      "normal" => [], "inherited" => [], "blocked" => [], "all" => []
    ]
  ];

  public $access = true;
  public $access_status = ["dependencies" => true, "points" => true, "level" => true, "access_level" => false];
  public $tags = [];
  public $is_trainer = false;
  public $modules = [];
  public $module = null;
  public $children = [];
  public $completed_children;
  public $acl;
  public $attempts = 0;

  public $new = false;
  public $id;
  public $iframe_index = "";

  public $settings = [];
  public $post = null;

  public $scos = [];
  public $hydrated = false;
  public $attempt_count = 0;
  public $rating_avg = 0.0;
  public $rating_user = 0.0;
  public $cert = null;
  public $settings_loaded = false;

  public function __construct()
  {
    $this->dependencies = cluevo_get_conf_const('CLUEVO_EMPTY_DEPENDENCY_ARRAY');
  }

  public function __get($prop)
  {
    switch ($prop) {
      case "module":
      case "module_id":
        if (!empty($this->module) && $this->module > 0) return $this->module;
        if (count($this->modules) > 0)
          return $this->modules[0];
        else
          return null;
        break;
      case "tree_id":
        $parts = explode('/', trim($this->path, "/"));
        if (!empty($parts)) {
          return (!empty($parts[0])) ? $parts[0] : $this->item_id;
        }
        break;
      case "item_id":
        if (!empty($this->item_id))
          return $this->item_id;
        else
          return $this->tree_id;
        break;
      case "type":
        if (!empty($this->module) && $this->module > 0) return cluevo_get_item_level_name(3);
        return cluevo_get_item_level_name($this->level);
        break;
      default:
        if (property_exists($this, $prop))
          return $this->$prop;
    }
  }

  public function load_module()
  {
    if (empty($this->module_id)) return;
    if (!is_numeric($this->module_id)) return;
    $module = cluevo_get_module($this->module_id);
    if (!empty($module)) {
      if (isset(parse_url($module->module_index)["scheme"])) {
        $this->iframe_index = $module->module_index;
      } else if (!empty($module->module_dir)) {
        $tmpIndex = parse_url($module->module_index, PHP_URL_PATH);
        $dir = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $module->module_dir) . '/';
        if (empty($module->module_index) || ($module->type_id == 1 && !file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_dir . "/" . $tmpIndex))) {
          $this->iframe_index = cluevo_find_module_index(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_dir);
        } else {
          $this->iframe_index = cluevo_get_conf_const('CLUEVO_MODULE_URL') . $module->module_dir . "/" . $module->module_index;
        }
        $this->scos = [];
        if (file_exists($dir)) {
          $this->scos = cluevo_find_scos($dir);
          if (!empty($this->scos) && is_array($this->scos)) {
            foreach ($this->scos as $key => $sco) {
              $this->scos[$key]['href'] = cluevo_get_conf_const('CLUEVO_MODULE_URL') . $module->module_dir . "/" . $sco["href"];
            }
          }
        }
      }
    }
  }

  public static function from_std_class($obj, $skipModule = false)
  {
    $result = new CluevoItem();
    if (!is_object($obj)) return $result;
    foreach (array_keys(get_object_vars($obj)) as $prop) {
      if (property_exists($result, $prop)) {
        $result->$prop = $obj->$prop;
      }
    }

    if (!$skipModule) {
      if (!empty($result->module) > 0 && (int)$result->module > 0) {
        $result->module_id = $result->module;
      }

      if (!empty($result->module_id) > 0 && (int)$result->module_id > 0) {
      }
    }
    if (!empty($result->metadata_id)) {
      $result->permalink = get_permalink($result->metadata_id);
    }

    do_action("cluevo_learning_structure_item_loaded", $result);
    return $result;
  }

  public function get_setting($strKey, $strPrefix = "")
  {
    $key = $strPrefix . $strKey;
    if (is_array($this->settings) && array_key_exists($key, $this->settings)) {
      return $this->settings[$key];
    }
  }

  public function load_settings()
  {
    $meta = get_post_meta($this->metadata_id);
    if (!empty($meta) && is_array($meta)) {
      $this->settings = [];
      if (!empty($meta[CLUEVO_META_DATA_PREFIX . "settings"])) {
        $settings = maybe_unserialize($meta[CLUEVO_META_DATA_PREFIX . "settings"][0]);
        foreach ($settings as $key => $value) {
          $prop = str_ireplace(CLUEVO_META_DATA_PREFIX, "", $key);
          $this->settings[$prop] = $value; // prefix and value in array for backcompat
        }
      }
    }
    $this->settings_loaded = true;
  }

  public function load_post()
  {
    $this->post = get_post($this->metadata_id);
  }

  public function has_content()
  {
    if (empty($this->post)) {
      $this->load_post();
    }
    if (empty($this->post->post_content)) return false;
    return (!empty(trim(str_replace('&nbsp;', '', strip_tags($this->post->post_content)))));
  }

  public static function from_array($arr)
  {
    $result = new CluevoItem();
    foreach ($arr as $prop => $value) {
      if (property_exists($result, $prop)) {
        $result->$prop = $value;
      }
    }

    if (count($result->modules) > 0 && array_key_exists(0, $result->modules)) {
      $result->module_id = $result->modules[0];
    }

    if ($result->type == "module") {
      $module = cluevo_get_module($result->module_id);
      if (!empty($module)) {
        if (empty($module->module_index) || !file_exists(cluevo_get_conf_const('CLUEVO_MODULE_URL') . $module->module_name . "/" . $module->module_index)) {
          $result->iframe_index = cluevo_find_module_index(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_dir);
        } else {
          $result->iframe_index = cluevo_get_conf_const('CLUEVO_MODULE_URL') . $module->module_name . "/" . $module->module_index;
        }
      }
    }
    if (!empty($result->metadata_id)) {
      $result->permalink = get_permalink($result->metadata_id);
    }

    do_action("cluevo_learning_structure_item_loaded", $result);
    return $result;
  }
}
