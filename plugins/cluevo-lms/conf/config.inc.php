<?php
define("CLUEVO_DEBUG", false);
define('CLUEVO_TEXT_DOMAIN', "cluevo");

define('CLUEVO_SHORTCODE', 'cluevo');
define('CLUEVO_TOC_SHORTCODE', 'cluevo-toc');
define('CLUEVO_GUIDELINE_SHORTCODE', 'cluevo-guideline');
define('CLUEVO_USER_WIDGET_SHORTCODE', 'cluevo-user-widget');
define('CLUEVO_PLUGIN_DIR_NAME', 'cluevo-lms');
define('CLUEVO_MODULE_INDEX_FILE', 'index_lms_html5.html');
define('CLUEVO_PLUGIN_NAMESPACE', 'cluevo');
define("CLUEVO_TAXONOMY", "CLUEVO");

$cluevo_tmp_locale_vars = [
  __("course", "cluevo"),
  __("chapter", "cluevo"),
  __("module", "cluevo"),
  __("tree", "cluevo"),
  __("course group", "cluevo")
];

function cluevo_get_conf_const($strName) {

  switch ($strName) {
    case 'CLUEVO_TYPES_TO_TERMS':
      return [
        "tree" => __("Course Group", "cluevo"),
        "course" => __("Course", "cluevo"),
        "chapter" => __("Chapter", "cluevo"),
        "module" => __("Module", "cluevo")
      ];
      break;
    case 'CLUEVO_ABS_UPLOAD_PATH':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/";
      break;
    case 'CLUEVO_ABS_MODULE_PATH':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules/";
      break;
    case 'CLUEVO_ABS_MODULE_ARCHIVE_PATH':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules-archive/";
      break;
    case 'CLUEVO_MODULE_URL':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      $forceHttps = get_option("cluevo-force-https-embeds", "");
      $dir = $cluevo_tmp_upload_base_dir["baseurl"];
      if ($forceHttps == "on") {
        $dir = str_ireplace("http://", "https://", $dir);
      }
      return $dir . "/cluevo/modules/";
      break;
    case 'CLUEVO_MODULE_ARCHIVE_URL':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return $cluevo_tmp_upload_base_dir["baseurl"] . "/cluevo/modules-archive/";
      break;
    case 'CLUEVO_PLUGIN_PATH':
      return plugin_dir_path(__DIR__);
      break;
    case 'CLUEVO_PLUGIN_DIR_NAME':
      return basename(dirname(__DIR__));
      break;
    case 'CLUEVO_DIRECTORIES':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return [
        $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules/",
        $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules-archive/",
        $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules-archive/tmp/"
      ];
      break;
    case 'CLUEVO_EMPTY_DEPENDENCY_ARRAY':
      return [
        'modules' => [ 'normal' => [], 'blocked' => [], 'inherited' => [], 'all' => [] ],
        'other' => [ 'normal' => [], 'blocked' => [], 'inherited' => [], 'all' => [] ]
      ];
      break;
    case 'CLUEVO_IMAGE_DIR':
      return plugin_dir_path(__DIR__) . "images/";
      break;
    case 'CLUEVO_PLUGIN_URL':
      return plugin_dir_url(__DIR__);
      break;
    case 'CLUEVO_IMAGE_URL':
      return plugin_dir_url(__DIR__) . "/images/";
      break;
    case 'CLUEVO_MODULE_DIR':
      $cluevo_tmp_upload_base_dir = wp_upload_dir();
      return $cluevo_tmp_upload_base_dir["basedir"] . "/cluevo/modules/";
      break;
    case 'CLUEVO_DEFAULT_USER_ROLES':
      return [
        "admin" => __("Admin", "cluevo"),
        "trainer" => __("Trainer", "cluevo"),
        "user" => __("User", "cluevo"),
        "guest" => __("Guest", "cluevo")
      ];
      break;
    case 'CLUEVO_TRAINER_USER_ROLES':
      return [
        __("Admin", "cluevo"),
        __("Trainer", "cluevo")
      ];
      break;
    case 'CLUEVO_DEFAULT_MODULE_TYPES':
      return [
        CLUEVO_SCORM_MODULE_TYPE_ID => 'SCORM 2004',
        CLUEVO_VIDEO_MODULE_TYPE_ID => 'Video',
        CLUEVO_AUDIO_MODULE_TYPE_ID => 'Audio',
        CLUEVO_PDF_MODULE_TYPE_ID => 'PDF'
      ];
      break;
    default:
      throw new Exception("Undefined constant: $strName");
  }
}

const CLUEVO_LEARNING_STRUCTURE_LEVELS = ['tree', 'course', 'chapter', 'module'];
define('CLUEVO_DB_TABLE_MODULE_PARMS', 'cluevo_scorm_parameters');
define('CLUEVO_DB_TABLE_STUDENTS', 'cluevo_students');
define('CLUEVO_DB_TABLE_MODULES', 'cluevo_modules');
define('CLUEVO_DB_TABLE_MODULES_PROGRESS', 'cluevo_modules_progress');
define('CLUEVO_DB_TABLE_TREE', 'cluevo_tree');
define('CLUEVO_DB_TABLE_TREE_ITEM_TYPES', 'cluevo_tree_item_types');
define('CLUEVO_DB_TABLE_TREE_MODULES', 'cluevo_tree_modules');
define('CLUEVO_DB_TABLE_TREE_DEPENDENCIES', 'cluevo_tree_dependencies');
define('CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES', 'cluevo_tree_module_dependencies');
define('CLUEVO_DB_TABLE_USER_DATA', 'cluevo_user_data');
define('CLUEVO_DB_TABLE_USER_EXP_LOG', 'cluevo_user_exp_log');
define('CLUEVO_DB_TABLE_COMPETENCE_AREAS', 'cluevo_competence_areas');
define('CLUEVO_DB_TABLE_COMPETENCES', 'cluevo_competences');
define('CLUEVO_DB_TABLE_MODULES_COMPETENCES', 'cluevo_modules_competences');
define('CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS', 'cluevo_competences_to_areas');
define('CLUEVO_DB_TABLE_LANGUAGES', 'cluevo_languages');
define('CLUEVO_DB_TABLE_USER_GROUPS', 'cluevo_user_groups');
define('CLUEVO_DB_TABLE_USERS_TO_GROUPS', 'cluevo_users_to_groups');
define('CLUEVO_DB_TABLE_TREE_PERMS', 'cluevo_tree_perms');
define('CLUEVO_DB_TABLE_MODULE_TYPES', 'cluevo_module_types');
define('CLUEVO_DB_TABLE_MODULE_MIME_TYPES', 'cluevo_module_mime_types');

define('CLUEVO_METADATA_POST_TYPE', 'cluevo-lms-metadata');
define('CLUEVO_METADATA_POST_TYPE_COMPETENCE', 'cluevo-competence');
define('CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA', 'cluevo-comp-area');
define('CLUEVO_METADATA_POST_TYPE_SCORM_MODULE', 'cluevo-scorm-module');
define('CLUEVO_PAGE_POST_TYPE', 'cluevo-lms-page');
define('CLUEVO_METADATA_KEY', 'cluevo-metadata-key');
define('CLUEVO_META_TREE_ITEM_ID', 'cluevo-lms-tree-item-id');
define('CLUEVO_META_COMPETENCE_ID', 'cluevo-lms-competence-id');
define('CLUEVO_META_COMPETENCE_AREA_ID', 'cluevo-lms-competence-area-id');
define('CLUEVO_META_TREE_ID', 'cluevo-lms-tree-id');
define('CLUEVO_META_MODULES_KEY', 'cluevo-lms-modules');
define('CLUEVO_META_TREE_ITEM_KEY', 'cluevo-lms-tree-item');
define('CLUEVO_META_DATA_PREFIX', '_cluevo-lms-meta-');
define('CLUEVO_METADATA_TYPE', CLUEVO_META_DATA_PREFIX . 'type');
define('CLUEVO_METADATA_NAME', CLUEVO_META_DATA_PREFIX . 'name');

define('CLUEVO_THEME_TPL_PATH' , 'cluevo');

const CLUEVO_REPEAT_INTERVAL_TYPES = [ 'day' => 'Tage', 'month' => 'Monate', 'week' => 'Wochen', 'year' => 'Jahre'];
const CLUEVO_PAGES = ['user', 'trainer', 'index', 'module archive', 'login', 'logout'];

define('CLUEVO_EMPTY_DATE', '9999-12-31 23:59:59');
define('CLUEVO_COUNTER_PARM', 'cluevo.module_counter');

define('CLUEVO_ABS_MAX_LEVEL', 999);
define('CLUEVO_DEFAULT_MAX_LEVEL', 100);
define('CLUEVO_DEFAULT_FIRST_LEVEL_EXP', 100);
define('CLUEVO_ABS_MAX_FIRST_LEVEL_EXP', 1000);

define('CLUEVO_USER_META_PREFIX' , '_sourcenova-lms-');
define('CLUEVO_USER_SCORE_RAW_META_KEY', CLUEVO_USER_META_PREFIX . 'module-score-raw-');
define('CLUEVO_USER_SCORE_SCALED_META_KEY', CLUEVO_USER_META_PREFIX . 'module-score-scaled-');
define('CLUEVO_USER_META_LAST_SEEN', CLUEVO_USER_META_PREFIX . 'last-seen');

define("CLUEVO_MAX_GUEST_CREATION_TRIES", 10);

define("CLUEVO_ADMIN_PAGE_SLUG", "cluevo");
define("CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE", "cluevo-learning-structure");
define("CLUEVO_ADMIN_PAGE_SCORM_PARMS", "cluevo-scorm-parameters");
define("CLUEVO_ADMIN_PAGE_MODULE_PROGRESS", "cluevo-module-progress");
define("CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS", "cluevo-settings");
define("CLUEVO_ADMIN_PAGE_ITEM_REPORTS", "cluevo-item-reports");
define("CLUEVO_ADMIN_PAGE_MODULES", "cluevo-modules");
define("CLUEVO_ADMIN_PAGE_WEBINARS", "cluevo-webinars");
define("CLUEVO_ADMIN_PAGE_COMPETENCE", "cluevo-competence");
define("CLUEVO_ADMIN_PAGE_USER_MANAGEMENT", "cluevo-users");
define("CLUEVO_ADMIN_PAGE_PERMISSIONS", "cluevo-permissions");
define("CLUEVO_ADMIN_PAGE_REPORTS", "cluevo-reporting");
define("CLUEVO_ADMIN_PAGE_LMS", "cluevo-lms");
define("CLUEVO_ADMIN_PAGE_DATABASE", "cluevo-database");

define("CLUEVO_ADMIN_TAB_REPORTS_MAIN", "reports");
define("CLUEVO_ADMIN_TAB_REPORTS_PROGRESS", "progress");
define("CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS", "scorm");

define("CLUEVO_ADMIN_TAB_LMS_STRUCTURE", "structure");
define("CLUEVO_ADMIN_TAB_LMS_MODULES", "modules");

define("CLUEVO_ADMIN_TAB_WEBINAR_ROOMS", "rooms");
define("CLUEVO_ADMIN_TAB_WEBINAR_USERS", "users");
define("CLUEVO_ADMIN_TAB_WEBINAR_SETTINGS", "settings");

define("CLUEVO_ADMIN_TAB_COMPETENCE_MAIN", "competences");
define("CLUEVO_ADMIN_TAB_COMPETENCE_AREAS", "areas");

define('CLUEVO_UPDATE_URL', 'https://update.cluevo.at/plugin/cluevo');
define('CLUEVO_DB_VERSION_OPT_KEY', 'cluevo-db-version');
define('CLUEVO_PLUGIN_DB_VERSION', '1.9.0');
define('CLUEVO_DB_VERSION_TAGS', '1.8.0');

define('CLUEVO_DEFAULT_GROUP_GUEST', 1);
define('CLUEVO_DEFAULT_GROUP_PREMIUM', 2);
define('CLUEVO_DEFAULT_GROUP_USER', 3);

define('CLUEVO_SCORM_MODULE_TYPE_ID' , 1);
define('CLUEVO_VIDEO_MODULE_TYPE_ID' , 2);
define('CLUEVO_AUDIO_MODULE_TYPE_ID' , 3);
define('CLUEVO_PDF_MODULE_TYPE_ID' , 6);

const CLUEVO_DEFAULT_MODULE_MIME_TYPES = [
  'application/x-zip-compressed' => 1,
  'application/zip' => 1,
  'video/mp4' => 2,
  'video/webm' => 2,
  'audio/wav' => 3,
  'audio/mp3' => 3,
  'audio/webm' => 3,
  'audio/mpeg' => 3,
  'application/pdf' => 6
];
?>
