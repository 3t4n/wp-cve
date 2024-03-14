<?php
class Cluevo_StatusTests
{
  public static function init()
  {
    add_filter('site_status_tests', __CLASS__ . '::add_tests');
    add_filter('debug_information', __CLASS__ . '::add_info');
  }

  public static function add_tests($tests)
  {
    $tests['direct']['cluevo-lms-zip-archive'] = [
      'label' => __("CLUEVO LMS", "cluevo"),
      'test' => __CLASS__ . '::check_zip_archive'
    ];

    return $tests;
  }

  public static function add_info($info)
  {
    $progress = self::get_progress_count();
    $scormParams = self::get_scorm_param_count();
    $moduleCount = self::get_module_count();
    $compCount = self::get_competences_count();
    $areaCount = self::get_competence_areas_count();
    $comps = self::get_competences();
    $areas = self::get_competence_areas();
    $treeItemCount = self::get_tree_item_count();
    $treeCount = self::get_tree_count();
    $treeDeps = self::get_tree_dependency_count();
    $moduleDeps = self::get_module_dependency_count();
    $groupCount = self::get_user_group_count();
    $groups = self::get_user_groups();
    $treeModules = self::get_tree_modules();
    $moduleDirSize = get_dirsize(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'));
    $moduleArchiveDirSize = get_dirsize(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'));
    $totalSize = $moduleDirSize + $moduleArchiveDirSize;
    $moduleDirSize = cluevo_human_filesize($moduleDirSize);
    $moduleArchiveDirSize = cluevo_human_filesize($moduleArchiveDirSize);
    $totalSize = cluevo_human_filesize($totalSize);
    $itemTypes = self::get_tree_item_types();
    $moduleTypes = self::get_module_types();
    $userLogCount = self::get_user_log_count();
    $userData = self::get_user_data();

    $info['cluevo-lms'] = [
      'label' => __("CLUEVO LMS", "cluevo"),
      'description' => __("Various information regarding the CLUEVO LMS", "cluevo"),
      'fields' => [
        'version' => [
          'label' => __('Version', "cluevo"),
          'value' => CLUEVO_VERSION,
          'debug' => CLUEVO_VERSION
        ],
        'tables' => [
          'label' => __('Database Tables', "cluevo"),
          'value' => self::get_table_count()
        ],
        'progress' => [
          'label' => __('Progress Records', "cluevo"),
          'value' => sprintf(__('%s records by %s users', "cluevo"), $progress->records, $progress->users),
          'debug' => $progress->records . " records by " . $progress->users . " users"
        ],
        'scorm-params' => [
          'label' => __('SCORM Parameters', "cluevo"),
          'value' => sprintf(
            __('%s records (%s distinct parameters) by %s users over %s modules', "cluevo"),
            $scormParams->records,
            $scormParams->parameters,
            $scormParams->users,
            $scormParams->modules
          ),
          'debug' => $scormParams->records . " records, " . $scormParams->parameters . " distinct types, " . $scormParams->users . " users, " . $scormParams->modules . " modules"
        ],
        'module-count' => [
          'label' => __('Module Count', "cluevo"),
          'value' => sprintf(__('%s records with %s types', "cluevo"), $moduleCount->records, $moduleCount->types),
          'debug' => $moduleCount->records . " records with " . $moduleCount->types . " types"
        ],
        'module-types' => [
          'label' => __('Module Types', "cluevo"),
          'value' => $moduleTypes,
          'debug' => $moduleTypes
        ],
        'module-dir-size' => [
          'label' => __('Module Directory Size', "cluevo"),
          'value' => $moduleDirSize,
          'debug' => $moduleDirSize,
        ],
        'module-archive-dir-size' => [
          'label' => __('Module Archive Directory Size', "cluevo"),
          'value' => $moduleArchiveDirSize,
          'debug' => $moduleArchiveDirSize,
        ],
        'module-dir-size-total' => [
          'label' => __('Total Module Directory Size', "cluevo"),
          'value' => $totalSize,
          'debug' => $totalSize,
        ],
        'competences' => [
          'label' => __('Competences', "cluevo"),
          'value' => $comps,
          'debug' => $comps
        ],
        'competence-count' => [
          'label' => __('Competence Count', "cluevo"),
          'value' => sprintf(__('%s records with %s types', "cluevo"), $compCount->records, $compCount->types),
          'debug' => $compCount->records . " records with " . $compCount->types . " types"
        ],
        'competence-areas' => [
          'label' => __('Competence Areas', "cluevo"),
          'value' => $areas,
          'debug' => $areas
        ],
        'competence-area-count' => [
          'label' => __('Competence Area Count', "cluevo"),
          'value' => sprintf(__('%s records with %s types', "cluevo"), $areaCount->records, $areaCount->types),
          'debug' => $areaCount->records . " records with " . $areaCount->types . " types"
        ],
        'tree-count' => [
          'label' => __('Tree Count', "cluevo"),
          'value' => sprintf(__("%s trees (%s)", "cluevo"), $treeCount->records, $treeCount->names),
          'debug' => $treeCount->records . " trees: " . $treeCount->names,
        ],
        'tree-item-count' => [
          'label' => __('Tree Item Count', "cluevo"),
          'value' => sprintf(__('%s records on %s levels', "cluevo"), $treeItemCount->records, $treeItemCount->levels),
          'debug' => $treeItemCount->records . " records on " . $treeItemCount->levels . " levels"
        ],
        'tree-item-types' => [
          'label' => __('Tree Levels', "cluevo"),
          'value' => $itemTypes,
          'debug' => $itemTypes
        ],
        'tree-module-count' => [
          'label' => __('Modules in Tree', "cluevo"),
          'value' => sprintf(__('%s modules (%s unique)', "cluevo"), $treeModules->modules, $treeModules->records),
          'debug' => $treeModules->modules . ", " . $treeModules->records . " total"
        ],
        'tree-dependency-count' => [
          'label' => __('Tree Dependency Count', "cluevo"),
          'value' => sprintf(__('%s records with %s types', "cluevo"), $treeDeps->records, $treeDeps->types),
          'debug' => $treeDeps->records . " records with " . $treeDeps->types . " types"
        ],
        'module-dependency-count' => [
          'label' => __('Module Dependency Count', "cluevo"),
          'value' => sprintf(__('%s records with %s types', "cluevo"), $moduleDeps->records, $moduleDeps->types),
          'debug' => $moduleDeps->records . " records with " . $moduleDeps->types . " types"
        ],
        'group-count' => [
          'label' => __('Group Count', "cluevo"),
          'value' => sprintf(__('%s users in %s groups', "cluevo"), $groupCount->records, $groupCount->groups),
          'debug' => $groupCount->records . " users in " . $groupCount->groups . " groups"
        ],
        'groups' => [
          'label' => __('User Groups', "cluevo"),
          'value' => $groups,
          'debug' => $groups
        ],
        'user-exp-log' => [
          'label' => __('User Exp Data', "cluevo"),
          'value' => sprintf(__("%s records for %s users, %s modules and %s sources", "cluevo"),
            $userLogCount->records, $userLogCount->users, $userLogCount->modules, $userLogCount->types),
          'debug' => $userLogCount->records . " records, " . $userLogCount->users . " users, " . $userLogCount->modules . " modules, " . $userLogCount->types . " types"
        ],
        'user-data' => [
          'label' => __('User Data', "cluevo"),
          'value' => sprintf(__("%s records for %s users, with a total of %s points and %s exp", "cluevo"),
            $userData->records, $userData->users, $userData->points, $userData->exp),
          'debug' => $userData->records . " records, " . $userData->users . " users, " . $userData->points . " points, " . $userData->exp . " exp"
        ],
        'module-archive-writable' => [
          'label' => __('Archive Writable', "cluevo"),
          'value' => wp_is_writable(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH')),
          'debug' => wp_is_writable(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'))
        ],
        'module-writable' => [
          'label' => __('Modules Writable', "cluevo"),
          'value' => wp_is_writable(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH')),
          'debug' => wp_is_writable(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'))
        ],
        'zip-archive' => [
          'label' => __('ZipArchive Available', "cluevo"),
          'value' => class_exists('ZipArchive'),
          'debug' => class_exists('ZipArchive')
        ],
      ]
    ];

    foreach ($GLOBALS["cluevo_settings"] as $key => $value) {

      foreach ($value["sub_sections"] as $subKey => $sub) {

        foreach ($sub["settings"] as $settingKey => $setting) {
          $info["cluevo-lms"]["fields"]["setting-" . $settingKey] = [
            'label' => sprintf(__("Setting: %s/%s/%s", "cluevo"), $value["title"], $sub["title"], $setting["title"]),
            'value' => get_option($settingKey),
            'debug' => json_encode(get_option($settingKey)),
            'private' => stripos($settingKey, 'license') !== false
          ];
        }
      }
    }

    return $info;
  }

  public static function check_zip_archive()
  {
    $result = [
      'label' => __('CLUEVO LMS: ZipArchive available', "cluevo"),
      'status' => 'good',
      'badge' => array(
        'label' => __('Requirement', "cluevo"),
        'color' => 'blue',
      ),
      'description' => sprintf(
        '<p>%s</p>',
        __('ZipArchive is required for zipping and unzipping modules.')
      ),
      'actions' => '',
      'test' => 'cluevo-lms-zip-archive'
    ];

    if (!class_exists('ZipArchive')) {
      $result['label'] = __("CLUEVO LMS: ZipArchive is not available.", "cluevo");
      $result['status'] = 'critical';
      $result['badge']['color'] = 'red';
    }
    return $result;
  }

  private static function get_table_count()
  {
    global $wpdb;
    $sql = "SHOW TABLES LIKE '%cluevo%'";
    $results = $wpdb->get_results($sql);
    if (empty($results)) return 0;
    return count($results);
  }

  private static function get_progress_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT user_id) AS users
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_module_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT type_id) AS types
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_competences_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT competence_type) AS types
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_competence_areas_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT competence_area_type) AS types
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_scorm_param_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT user_id) AS users,
      COUNT(DISTINCT module_id) AS modules,
      COUNT(DISTINCT parameter) AS parameters
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_competences()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
    $sql = "SELECT GROUP_CONCAT(competence_name SEPARATOR ', ') AS records
      FROM $table";
    $result = $wpdb->get_var($sql);
    return $result;
  }

  private static function get_competence_areas()
  {
    global $wpdb;
    $compTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
    $areaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
    $compsToAreaTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
    $sql = "SELECT a.competence_area_name AS name, GROUP_CONCAT(competence_name SEPARATOR ', ') AS comps
      FROM $areaTable a LEFT JOIN $compsToAreaTable cta ON a.competence_area_id = cta.competence_area_id
      LEFT JOIN $compTable c ON cta.competence_id = c.competence_id
      GROUP BY a.competence_area_name";
    $result = $wpdb->get_results($sql);
    if (!empty($result)) {
      return join(", ", array_map(function($row) { return $row->name . " (" . $row->comps . ")"; }, $result));
    }
    return $result;
  }

  private static function get_tree_item_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $sql = "SELECT COUNT(*) AS records,
      COUNT(DISTINCT level) AS levels
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_tree_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $sql = "SELECT COUNT(*) AS records, GROUP_CONCAT(name) AS names
      FROM $table WHERE level = 0 GROUP BY level";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_tree_dependency_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT dep_type) AS types
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_module_dependency_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT dep_type) AS types
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_user_group_count()
  {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT group_id) AS groups
      FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_user_groups()
  {
    global $wpdb;
    $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;
    $usersTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
    $sql = "SELECT g.group_name, COUNT(user_id) AS count
      FROM $groupTable g
      LEFT JOIN $usersTable u ON g.group_id = u.group_id
      GROUP BY g.group_id";
    $result = $wpdb->get_results($sql);
    if (!empty($result)) {
      return join(", ", array_map(function ($item) {
        return $item->group_name . " (" . $item->count . ")";
      }, $result));
    }
    return "";
  }

  private static function get_tree_modules() {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT module_id) AS modules FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_tree_item_types() {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
    $sql = "SELECT level, COUNT(*) AS count FROM $table GROUP BY level";
    $result = $wpdb->get_results($sql);
    if (!empty($result)) {
      return join(", ", array_map(function ($item) {
        return $item->level . " (" . $item->count . ")";
      }, $result));
    }
    return 0;
  }

  private static function get_module_types() {
    global $wpdb;
    $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
    $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
    $sql = "SELECT t.type_name, COUNT(m.type_id) AS count FROM $typeTable t LEFT JOIN $moduleTable m ON t.type_id = m.type_id
      GROUP BY t.type_id";
    $result = $wpdb->get_results($sql);
    if (!empty($result)) {
      return join(", ", array_map(function ($item) {
        return $item->type_name . " (" . $item->count . ")";
      }, $result));
    }
    return 0;
  }

  private static function get_user_log_count() {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT user_id) AS users, COUNT(DISTINCT source_type) AS types, COUNT(DISTINCT source_module_id) AS modules FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }

  private static function get_user_data() {
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_USER_DATA;
    $sql = "SELECT COUNT(*) AS records, COUNT(DISTINCT user_id) AS users, SUM(total_points) AS points, SUM(total_exp) AS exp FROM $table";
    $result = $wpdb->get_row($sql);
    return $result;
  }
}

Cluevo_StatusTests::init();
