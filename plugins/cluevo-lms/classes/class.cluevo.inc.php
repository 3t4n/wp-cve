<?php

/**
 * Class: Cluevo
 *
 */
class Cluevo
{
  public $user;
  public $tree;

  public $user_id = 0;

  public $modules = [];
  public $module = null;
  public $current_module = -1;
  public $module_count = 0;
  public $in_the_module_loop = false;

  public $current_page = null;

  public $items = [];
  public $item = null;
  public $current_item = -1;
  public $item_count = 0;
  public $in_the_item_loop = false;
  public $item_history = [];

  public $students = [];
  public $student = null;
  public $current_student = -1;
  public $student_count = 0;
  public $student_tree = null;
  public $in_the_student_loop = false;

  public $in_the_dependency_loop = false;
  public $current_dependency = -1;
  public $dependency_count = 0;
  public $dependency = null;
  public $dependencies = [];

  public $shortcode = false;
  public $shortcode_content = "";
  public $shortcode_atts = [];

  public function __construct($intItemId = null, $intUserId = null)
  {
    if (!empty($intUserId)) {
      $this->user = cluevo_get_lms_users($intUserId);
      $this->user_id = $intUserId;
      if (!empty($this->user)) {
        $this->user->competences = cluevo_get_users_competence_scores($this->user_id);
      }
    } else {
      $this->user = new CluevoUser();
    }

    $items = [];
    if (!empty($intItemId)) {
      $item = cluevo_get_learning_structure_item($intItemId, $this->user_id);
      $this->current_page = $item;
      if (!empty($item->children)) {
        $items = $item->children;
      }
    } else {
      $trees = array_values(cluevo_turbo_get_trees($intUserId));
      $items = array_column($trees, "item");
    }

    $this->items = array_values($items);
    $this->item = (!empty($this->items)) ? $this->items[0] : null;

    $this->load_dependencies();

    $this->item_count = count($this->items);

    $this->modules = cluevo_get_modules();
    $this->module_count = count($this->modules);
    if ($this->module_count > 0)
      $this->module = $this->modules[0];
  }

  public function get_item($intItemId)
  {
    if (empty($this->items)) return null;
    foreach ($this->items as $item) {
      if ($item->item_id == $intItemId) return $item;
    }
    return null;
  }

  public function have_modules()
  {
    if ($this->current_module + 1 < $this->module_count) {
      return true;
    } elseif ($this->current_module + 1 == $this->module_count && $this->module_count > 0) {
      $this->rewind_modules();
    }

    $this->in_the_module_loop = false;

    return false;
  }

  public function the_module()
  {
    $this->in_the_module_loop = true;
    $this->next_module();
    return $this->module;
  }

  public function next_module()
  {
    $this->current_module++;
    $keys = array_keys($this->modules);
    $this->module = $this->modules[$keys[$this->current_module]];
    return $this->module;
  }

  public function rewind_modules()
  {
    $this->current_module = -1;
    if ($this->module_count > 0) {
      $keys = array_keys($this->modules);
      $this->module = $this->modules[$keys[0]];
    } else {
      $this->module = null;
    }
  }

  public function the_module_metadata()
  {
    if (!empty($this->module) && property_exists($this->module, "metadata")) {
      return $this->module->metadata;
    }

    return null;
  }

  public function the_module_progress()
  {
    if (!empty($this->module)) {
      return $this->module->progress;
    }

    return 0;
  }

  public function have_items()
  {
    if ($this->current_item + 1 < $this->item_count) {
      return true;
    } elseif ($this->current_item + 1 == $this->item_count && $this->item_count > 0) {
      $this->rewind_items();
    }

    $this->in_the_item_loop = false;
    return false;
  }

  public function have_visible_items()
  {
    foreach ($this->items as $item) {
      if (is_object($item)) {
        if (!empty($item->access) || $item->access_status["access_level"] > 0) return true;
      } elseif (is_array($item)) {
        if (!empty($item["access"]) || $item["access_status"]["access_level"] > 0) return true;
      } else {
        return false;
      }
    }

    return false;
  }

  public function the_item()
  {
    $this->in_the_item_loop = true;
    $this->next_item();
    return $this->item;
  }

  public function next_item()
  {
    $this->current_item++;
    $this->item = (!empty($this->items[$this->current_item]))
      ? $this->items[$this->current_item]
      : null;
    return $this->item;
  }

  public function rewind_items()
  {
    $this->current_item = -1;
    if ($this->item_count > 0) {
      if (!empty($this->item_history)) {
        $this->pop_history();
      } else {
        $this->item = $this->items[0];
        $this->load_dependencies($this->items[0]->item_id);
      }
    } else {
      $this->item = null;
      $this->dependencies = [];
      $this->dependency = null;
      $this->current_dependency = -1;
    }
  }

  public function have_dependencies()
  {
    if ($this->current_dependency + 1 < $this->dependency_count) {
      return true;
    } elseif ($this->current_dependency + 1 == $this->dependency_count && $this->dependency_count > 0) {
      $this->rewind_dependencies();
    }

    $this->in_the_dependency_loop = false;
    return false;
  }

  public function the_dependency()
  {
    global $sourcenova_lms_dependency, $sourcenova_lms_item;
    $this->in_the_dependency_loop = true;
    $this->dependency = $this->next_dependency();
    $sourcenova_lms_dependency = $this->dependency;
    $sourcenova_lms_item = $this->dependency;
    return $this->dependency;
  }

  public function next_dependency()
  {
    $this->current_dependency++;
    $this->dependency = $this->dependencies[$this->current_dependency];
    return $this->dependency;
  }

  public function rewind_dependencies()
  {
    $this->current_dependency = -1;
    if ($this->dependency_count > 0 && !empty($this->dependencies)) {
      $this->dependency = $this->dependencies[0];
    } else {
      $this->dependency = null;
    }
  }

  public function the_item_metadata()
  {
    if (!empty($this->item)) {
      return get_post($this->item->metadata_id);

      return null;
    }
  }

  public function the_item_type()
  {
    global $sourcenova_lms_item;
    if (!empty($sourcenova_lms_item))
      return $sourcenova_lms_item->type;

    return null;
  }

  private function add_history()
  {
    $cur = ['index' => $this->current_item, 'loop' => $this->in_the_item_loop, 'item' => $this->item->id];
    foreach ($this->items as $item) {
      $cur['items'][] = $item->item_id;
    }

    $this->item_history[] = $cur;
  }

  private function pop_history()
  {
    $prev = array_pop($this->item_history);
    $this->current_item = $prev['index'];
    $this->in_the_item_loop = $prev['loop'];
    if (!empty($prev['item']))
      $this->item = $this->tree[$prev['item']];
    $this->items = [];
    foreach ($prev['items'] as $item) {
      $this->items[] = $this->tree[$item];
    }
    $this->item_count = count($prev['items']);
    $this->load_dependencies($this->item->item_id);
  }

  public function load_item($id)
  {
    $this->add_history();
    $this->items = [];
    $this->item = $this->tree[$id];
    $this->load_dependencies($id);
    if (!empty($this->item->children)) {
      foreach ($this->item->children as $c) {
        $this->items[] = $this->tree[$c];
      }
    }
    $this->current_item = -1;
    $this->item_count = count($this->items);
  }

  public function load_module($strModule)
  {
    if (array_key_exists($strModule, $this->modules))
      $this->module = $this->modules[$strModule];
  }

  public function have_students()
  {
    if ($this->current_student + 1 < $this->student_count) {
      return true;
    } elseif ($this->current_student + 1 == $this->student_count && $this->student_count > 0) {
      $this->rewind_students();
    }

    $this->in_the_student_loop = false;
    return false;
  }

  public function the_student()
  {
    global $sourcenova_lms_student, $sourcenova_lms_student_tree;
    $this->in_the_student_loop = true;
    $sourcenova_lms_student = $this->next_student();
    $sourcenova_lms_student_tree = $this->student_tree;
    return $this->student;
  }

  public function next_student()
  {
    $this->current_student++;
    $keys = array_keys($this->students);
    $id = $this->students[$keys[$this->current_student]]->ID;
    $this->student_tree = getTree($id);
    $this->student = getUser($id, $this->student_tree);
    return $this->student;
  }

  public function rewind_students()
  {
    $this->current_student = -1;
    if ($this->student_count > 0) {
      $keys = array_keys($this->students);
      $this->student = $this->students[$keys[0]];
    } else {
      $this->student = null;
      $this->student_tree = null;
    }
  }

  public function init_loop($items)
  {
    if (!empty($items)) {
      $this->items = $items;
      $this->current_item = -1;
      $this->in_the_item_loop = false;
      $this->item_count = count($items);
      $this->item = null;
      $this->load_dependencies($this->items[0]['id']);
    }
  }

  private function load_dependencies()
  {
    $this->dependencies = [];
    if (empty($this->item->dependencies)) return;
    if (is_array($this->item->dependencies)) {
      if (!empty($this->item->dependencies['all'])) {
        if (empty($this->user_id)) {
          foreach ($this->item->dependencies['all'] as $d) {
            $this->dependencies[] = cluevo_get_learning_structure_item($d, $this->user_id);
          }
        } else {
          foreach ($this->item->dependencies['all'] as $d => $status) {
            $this->dependencies[] = cluevo_get_learning_structure_item($d, $this->user_id);
          }
        }
      }
    } else {
      if (!empty($this->item->dependencies->all)) {
        if (empty($this->user_id)) {
          foreach ($this->item->dependencies->all as $d) {
            $this->dependencies[] = cluevo_get_learning_structure_item($d, $this->user_id);
          }
        } else {
          foreach ($this->item->dependencies->all as $d => $status) {
            $this->dependencies[] = cluevo_get_learning_structure_item($d, $this->user_id);
          }
        }
      }
    }
    $this->dependency_count = count($this->dependencies);
    $this->rewind_dependencies();
  }

  private function load_dependencies1($intId)
  {
    $this->dependencies = [];
    if (!empty($this->tree[$intId]->dependencies['all'])) {
      foreach ($this->tree[$intId]->dependencies['all'] as $d) {
        $this->dependencies[] = $this->tree[$d];
      }
      $this->dependency_count = count($this->dependencies);
    }
    $this->rewind_dependencies();
  }
}
