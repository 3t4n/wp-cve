<?php
if (!defined("CLUEVO_ACTIVE")) exit;

/**
 * Creates/updates database tables
 *
 * @return bool
 */
function cluevo_plugin_install() {
  cluevo_create_database();
  cluevo_update_module_mime_types();
  cluevo_create_terms();
  cluevo_rename_sono_posts();
  cluevo_create_default_groups();
  cluevo_create_default_tree();
  cluevo_set_default_options();
  cluevo_migrate_modules_to_type_dirs();
  cluevo_create_directories();
  cluevo_update_module_version_fields();
  cluevo_create_module_archive_htaccess();
  cluevo_create_cluevo_uploads_htaccess();
  return true;
}

function cluevo_update_module_mime_types() {
  global $wpdb;
  $moduleTypesMimeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_MIME_TYPES;
  foreach (CLUEVO_DEFAULT_MODULE_MIME_TYPES as $moduleMimeType => $moduleTypeId) {
    $wpdb->replace($moduleTypesMimeTable, [
      'type_id' => $moduleTypeId,
      'mime_type' => $moduleMimeType
    ]);
  }
}

function cluevo_set_default_options() {
  if (get_option("cluevo-modules-display-mode") == "") update_option("cluevo-modules-display-mode", "Lightbox");
  if (get_option("cluevo-modules-display-position") == "") update_option("cluevo-modules-display-position", "start");
  if (get_option("cluevo-auto-add-new-users") == "") update_option("cluevo-auto-add-new-users", "on");
  if (get_option("cluevo-max-level") == "") update_option("cluevo-max-level", 100);
  if (get_option("cluevo-exp-first-level") == "") update_option("cluevo-exp-first-level", 100);
}

function cluevo_create_default_groups() {
  global $wpdb;
  $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;

  $defaultGroups = array(
    CLUEVO_DEFAULT_GROUP_GUEST => __("Guest", "cluevo"),
    CLUEVO_DEFAULT_GROUP_PREMIUM => __("Premium", "cluevo"),
    CLUEVO_DEFAULT_GROUP_USER => __("User", "cluevo")
  );
  foreach ($defaultGroups as $id => $name) {
    $wpdb->query(
      $wpdb->prepare("REPLACE INTO $groupTable SET group_name = %s, group_id = %d, protected = 1", [ $name, $id ])
    );
  }
}

function cluevo_create_database() {
  global $wpdb;

  $parmsTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS;
  $curDatabaseVersion = get_option(CLUEVO_DB_VERSION_OPT_KEY);

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $parmsTable (
    id BIGINT(11) NOT NULL AUTO_INCREMENT,
    module_id INTEGER NOT NULL,
    user_id bigint(20) NOT NULL,
    attempt_id INTEGER(11) NOT NULL default '0',
    parameter varchar(64) NOT NULL,
    value text NOT NULL,
    user_deleted TINYINT(1) DEFAULT '0',
    date_user_deleted DATETIME,
    date_added datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    date_modified timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    UNIQUE INDEX parm (module_id, user_id, attempt_id, parameter)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  $groupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_GROUPS;
  $sqlUserGroups = "CREATE TABLE $groupTable (
    group_id BIGINT(20) AUTO_INCREMENT NOT NULL,
    group_name VARCHAR(64) NOT NULL,
    group_description TEXT,
    protected TINYINT(1) DEFAULT '0',
    tags TEXT,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (group_id),
    UNIQUE INDEX group_name (group_name)) $charset_collate;";
  dbDelta( $sqlUserGroups );

  $inc = $wpdb->get_var(
    $wpdb->prepare(
      "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s",
      [ $wpdb->dbname, $groupTable ]
    )
  );

  if (!empty($inc)) {
    if ((int)$inc < 1000) {
      $wpdb->query("ALTER TABLE $groupTable AUTO_INCREMENT = 1000");
    }
  }

  $usersToGroupTable = $wpdb->prefix . CLUEVO_DB_TABLE_USERS_TO_GROUPS;
  $sqlUsersToGroups = "CREATE TABLE $usersToGroupTable (
    user_id BIGINT(20) NOT NULL,
    group_id BIGINT(20) NOT NULL,
    is_trainer TINYINT(1) DEFAULT '0',
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, group_id),
    INDEX user_id (user_id),
    INDEX group_id (group_id)) $charset_collate;";
  dbDelta( $sqlUsersToGroups );

  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $db_version = get_option(CLUEVO_DB_VERSION_OPT_KEY);
  if (version_compare($db_version, CLUEVO_PLUGIN_DB_VERSION) === -1) {
    $mtExists = $wpdb->get_var(
      $wpdb->prepare("SHOW TABLES LIKE %s", [ $moduleTable ])
    );
    if ($mtExists == $moduleTable) {
      try {
        $wpdb->query("ALTER TABLE $moduleTable DROP INDEX module_name");
        $wpdb->query("ALTER TABLE $moduleTable DROP PRIMARY KEY, ADD PRIMARY KEY (module_id, lang_code)");
      } catch (Exception $ex) { }
    }
  }

  $sqlModules = "CREATE TABLE $moduleTable (
    module_id INTEGER NOT NULL AUTO_INCREMENT,
    type_id INTEGER,
    module_name VARCHAR(191) NOT NULL,
    module_zip VARCHAR(255) NOT NULL,
    module_dir VARCHAR(255) NOT NULL,
    module_index TEXT NOT NULL,
    scorm_version VARCHAR(32),
    lang_code VARCHAR(2),
    metadata_id INTEGER,
    tags TEXT,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY module_id_lang (module_id, lang_code),
    UNIQUE INDEX module_name (module_name, lang_code),
    INDEX module_id (module_id)) $charset_collate;";
  dbDelta( $sqlModules );


  $moduleTypeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
  $sqlModuleTypes = "CREATE TABLE $moduleTypeTable (
    type_id INTEGER AUTO_INCREMENT NOT NULL,
    type_name VARCHAR(128) NOT NULL,
    type_description TEXT,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY type_id (type_id),
    UNIQUE INDEX type_name (type_name)) $charset_collate;";

  dbDelta( $sqlModuleTypes );

  foreach (cluevo_get_conf_const('CLUEVO_DEFAULT_MODULE_TYPES') as $moduleTypeId => $moduleType) {
    $wpdb->replace($moduleTypeTable, [
      'type_id' => $moduleTypeId,
      'type_name' => $moduleType
    ]);
  }

  $moduleTypesMimeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_MIME_TYPES;

  $sqlModuleTypesMimeTable = "CREATE TABLE $moduleTypesMimeTable (
    type_id INTEGER,
    mime_type VARCHAR(32) NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY mime_type (mime_type)) $charset_collate;";

  dbDelta( $sqlModuleTypesMimeTable );

  $langTable = $wpdb->prefix . CLUEVO_DB_TABLE_LANGUAGES;
  $sqlLangs = "CREATE TABLE $langTable (
    lang_id INTEGER NOT NULL AUTO_INCREMENT,
    lang_name VARCHAR(32) NOT NULL,
    lang_code varchar(3) NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY lang_id (lang_id),
    UNIQUE INDEX lang_code (lang_code)) AUTO_INCREMENT = 1000 $charset_collate;";
  dbDelta ( $sqlLangs );

  $langs = [ "de" => "Deutsch", "en" => "English", "fr" => "Français", "it" => "Italiano", "es" => "Español" ];
  foreach ($langs as $code => $name) {
    $wpdb->query(
      $wpdb->prepare("INSERT IGNORE INTO $langTable SET lang_code = %s, lang_name = %s", [ $code, $name ] )
    );
  }

  $moduleStateTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $sqlModuleStates = "CREATE TABLE $moduleStateTable (
    user_id INTEGER NOT NULL,
    module_id INTEGER(11) NOT NULL,
    attempt_id INTEGER(11) default '0',
    date_started DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    progress DECIMAL(4, 2) NOT NULL DEFAULT '0.00',
    score_min DECIMAL(10, 7) DEFAULT '0',
    score_max DECIMAL(10, 7) DEFAULT '0',
    score_raw DECIMAL(10, 7) DEFAULT '0',
    score_scaled DECIMAL(3, 2) DEFAULT '0.00',
    is_practice TINYINT(1) DEFAULT '0',
    completion_status ENUM ('completed', 'incomplete', 'not attempted', 'unknown') NOT NULL DEFAULT 'not attempted',
    success_status ENUM ('passed', 'failed', 'unknown') NOT NULL DEFAULT 'unknown',
    lesson_status ENUM ('passed', 'completed', 'failed', 'incomplete', 'browsed', 'not attempted') NOT NULL DEFAULT 'not attempted',
    credit ENUM ('credit', 'no-credit') NOT NULL DEFAULT 'credit',
    user_deleted TINYINT(1) DEFAULT '0',
    date_user_deleted DATETIME,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE INDEX user_id_module_id_attempt_id (user_id, module_id, attempt_id)) $charset_collate;";
  dbDelta( $sqlModuleStates );

  $treeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;
  $sqlTree = "CREATE TABLE $treeTable (
    item_id INTEGER AUTO_INCREMENT NOT NULL,
    parent_id INTEGER NOT NULL DEFAULT '0',
    metadata_id INTEGER,
    level INTEGER NOT NULL DEFAULT '0',
    name VARCHAR(255) NOT NULL,
    path VARCHAR(255) DEFAULT '/',
    sort_order INTEGER NOT NULL DEFAULT '0',
    points_worth INTEGER NOT NULL DEFAULT '0',
    points_required INTEGER NOT NULL DEFAULT '0',
    practice_points INTEGER NOT NULL DEFAULT '0',
    level_required INTEGER NOT NULL DEFAULT '0',
    login_required INTEGER NOT NULL DEFAULT '1',
    published TINYINT(1) default '1',
    tags TEXT,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (item_id),
    INDEX metadata_id (metadata_id),
    INDEX parent_id (parent_id),
    INDEX path (path)
  )$charset_collate;";
  dbDelta ( $sqlTree );

  $permTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_PERMS;
  $sqlTreePerms = "CREATE TABLE $permTable (
    perm_id INTEGER AUTO_INCREMENT NOT NULL,
    item_id INTEGER NOT NULL,
    perm VARCHAR(32) NOT NULL,
    access_level TINYINT(1) NOT NULL DEFAULT '0',
    date_expired DATETIME,
    date_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (perm_id),
    UNIQUE INDEX item_perm (item_id, perm),
    INDEX item_id (item_id),
    INDEX perm (perm)
  ) $charset_collate;";
  dbDelta ( $sqlTreePerms );

  $itemTypeTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_ITEM_TYPES;

  $sqlItemTypes = "CREATE TABLE $itemTypeTable (
    level INTEGER(11) NOT NULL,
    type VARCHAR(32) NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY level (level)) $charset_collate;";
  dbDelta ( $sqlItemTypes );

  foreach (CLUEVO_LEARNING_STRUCTURE_LEVELS as $level => $name ) {
    $wpdb->query($wpdb->prepare("INSERT IGNORE INTO $itemTypeTable SET level = %d, type = %s", [ $level, $name ] ));
  }

  $depTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_DEPENDENCIES;
  $sqlDeps = "CREATE TABLE $depTable (
    item_id INTEGER NOT NULL,
    dep_id INTEGER NOT NULL,
    dep_type ENUM('normal', 'inherited', 'blocked', 'modules'),
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (item_id, dep_id),
    INDEX item_id (item_id),
    INDEX dep_id (dep_id)
  ) $charset_collate;";
  dbDelta ( $sqlDeps );

  $moduleDepTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES;
  $sqlModuleDeps = "CREATE TABLE $moduleDepTable (
    module_id INTEGER NOT NULL,
    dep_id INTEGER NOT NULL,
    dep_type ENUM('normal', 'inherited', 'blocked', 'modules'),
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (module_id, dep_id),
    INDEX module_id (module_id),
    INDEX dep_id (dep_id)
  ) $charset_collate;";
  dbDelta ( $sqlModuleDeps );

  $modTable = $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES;
  $sqlMods = "CREATE TABLE $modTable (
    item_id INTEGER NOT NULL,
    module_id INTEGER NOT NULL,
    display_mode VARCHAR(32),
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (item_id),
    INDEX module_id (module_id)
  ) $charset_collate;";
  dbDelta ( $sqlMods );

  $userDataTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_DATA;
  $sqlMods = "CREATE TABLE $userDataTable (
    user_id BIGINT NOT NULL,
    role_id INTEGER NOT NULL DEFAULT '0',
    total_points INTEGER NOT NULL DEFAULT '0',
    total_exp INTEGER NOT NULL DEFAULT '0',
    date_last_seen DATETIME,
    date_role_since TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)) $charset_collate;";
  dbDelta ( $sqlMods );

  $userExpLogTable = $wpdb->prefix . CLUEVO_DB_TABLE_USER_EXP_LOG;
  $sqlUserExpLogTable = "CREATE TABLE $userExpLogTable (
    log_id BIGINT AUTO_INCREMENT NOT NULL,
    user_id BIGINT NOT NULL,
    added_by_user_id BIGINT default '0',
    source_type VARCHAR(32) NOT NULL,
    source_module_id INTEGER,
    source_module_attempt_id INTEGER,
    exp_before INTEGER NOT NULL,
    exp_added INTEGER NOT NULL,
    exp_after INTEGER NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (log_id),
    INDEX user_id (user_id)
  ) $charset_collate;";
  dbDelta ( $sqlUserExpLogTable );

  $competenceAreasTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCE_AREAS;
  $sqlCompetenceAreasTable = "CREATE TABLE $competenceAreasTable (
    competence_area_id BIGINT AUTO_INCREMENT NOT NULL,
    competence_area_name VARCHAR(191),
    metadata_id INTEGER default '0',
    competence_area_type ENUM('system', 'user') DEFAULT 'system',
    user_added_id BIGINT default '0',
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_modified_id BIGINT default'0',
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (competence_area_id),
    UNIQUE KEY competence_area_name (competence_area_name),
    INDEX metadata_id (metadata_id)
  ) $charset_collate;";
  dbDelta ( $sqlCompetenceAreasTable);

  $moduleCompetenceTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES;
  $sqlModuleCompetencesTable = "CREATE TABLE $moduleCompetenceTable (
    competence_module_id BIGINT AUTO_INCREMENT NOT NULL,
    competence_id BIGINT NOT NULL,
    module_id INTEGER NOT NULL,
    competence_coverage DECIMAL(3,2) DEFAULT '0',
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (competence_module_id),
    UNIQUE INDEX competence_module (competence_id, module_id),
    INDEX competence_id (competence_id),
    INDEX module_id (module_id)
  ) $charset_collate;";
  dbDelta ( $sqlModuleCompetencesTable);

  $competencesToAreasTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES_TO_AREAS;
  $sqlCompetencesToAreas = "CREATE TABLE $competencesToAreasTable (
    competence_id BIGINT NOT NULL,
    competence_area_id BIGINT NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (competence_id, competence_area_id)
  ) $charset_collate;";
  dbDelta ( $sqlCompetencesToAreas );

  $competenceTable = $wpdb->prefix . CLUEVO_DB_TABLE_COMPETENCES;
  $sqlCompetenceTable = "CREATE TABLE $competenceTable (
    competence_id BIGINT AUTO_INCREMENT NOT NULL,
    competence_name VARCHAR(191),
    competence_type ENUM('system', 'user') DEFAULT 'system',
    metadata_id INTEGER default '0',
    user_added_id BIGINT default '0',
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_modified_id BIGINT default'0',
    date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (competence_id),
    UNIQUE KEY competence_name (competence_name),
    INDEX metadata_id (metadata_id)) $charset_collate;";
  dbDelta ( $sqlCompetenceTable );

  update_option( CLUEVO_DB_VERSION_OPT_KEY, CLUEVO_PLUGIN_DB_VERSION);
}

/**
 * Renames old metadata posts and pages to the new cluevo post types
 *
 * @return bool
 */
function cluevo_rename_sono_posts() {
  global $wpdb;
  $success = true;
  $sql = "UPDATE " . $wpdb->prefix . "posts SET post_type = %s WHERE post_type = %s";
  $dataResult = $wpdb->query(
    $wpdb->prepare($sql, [ CLUEVO_METADATA_POST_TYPE, "sono-lms-data" ] )
  );

  $sql = "UPDATE " . $wpdb->prefix . "posts SET post_type = %s WHERE post_type = %s";
  $pageResult = $wpdb->query(
    $wpdb->prepare($sql, [ CLUEVO_PAGE_POST_TYPE, "sono-lms-page" ] )
  );

  return ($dataResult !== false && $pageResult !== false);
}

/**
 * Creates taxonomy terms
 *
 * Returns an array with the created term ids
 *
 * @return array
 */
function cluevo_create_terms() {
  cluevo_create_metadata_post_type();
  cluevo_create_lms_page_post_type();
  cluevo_meta_taxonomy_init();

  $results = [];

  $treeResult = wp_insert_term(
    __("Course group", "cluevo"),
    CLUEVO_TAXONOMY,
    array(
      'label' => __('Course group', "cluevo"),
      'description' => __('Course group made up of courses', "cluevo"),
      'slug' => __('course-group', "cluevo")
    )
  );
  $results[] = $treeResult;

  if (is_array($treeResult)) {
    $courseResult = wp_insert_term(
      __("Course", "cluevo"),
      CLUEVO_TAXONOMY,
      array(
        'label' => __('Course', "cluevo"),
        'description' => __('Course made up of chapters', "cluevo"),
        'slug' => __('course', "cluevo"),
        'parent' => $treeResult['term_id']
      )
    );
    $results[] = $courseResult;

    if (is_array($courseResult)) {
      $chapterResult = wp_insert_term(
        __("Chapter", "cluevo"),
        CLUEVO_TAXONOMY,
        array(
          'label' => __('Chapter', "cluevo"),
          'description' => __('Chapter made up of modules', "cluevo"),
          'slug' => __('chapter', "cluevo"),
          'parent' => $courseResult['term_id']
        )
      );
      $results[] = $chapterResult;

      if (is_array($chapterResult)) {
        $moduleResult = wp_insert_term(
          __("Module", "cluevo"),
          CLUEVO_TAXONOMY,
          array(
            'label' => __('Module', "cluevo"),
            'description' => __('A learning module', "cluevo"),
            'slug' => __('module', "cluevo"),
            'parent' => $chapterResult['term_id']
          )
        );
        $results[] = $moduleResult;
      }
    }
  }

  $scormResult = wp_insert_term(
    __("SCORM Module", "cluevo"),
    CLUEVO_TAXONOMY,
    array(
      'label' => __('SCORM Module', "cluevo"),
      'description' => __('A SCORM module', "cluevo"),
      'slug' => __('scorm-module', "cluevo")
    )
  );
  $results[] = $scormResult;

  $competencesResult = wp_insert_term(
    __("Competences", "cluevo"),
    CLUEVO_TAXONOMY,
    array(
      'label' => __('Competences', "cluevo"),
      'description' => __('Competences', "cluevo"),
      'slug' => __('competences', "cluevo")
    )
  );
  $results[] = $competencesResult;

  if (is_array($competencesResult)) {
    $competenceResult = wp_insert_term(
      __("Competence", "cluevo"),
      CLUEVO_TAXONOMY,
      array(
        'label' => __('Competence', "cluevo"),
        'description' => __('Competence', "cluevo"),
        'slug' => __('competence', "cluevo"),
        'parent' => $competencesResult['term_id']
      )
    );
    $results[] = $competenceResult;

    $competenceAreaResult = wp_insert_term(
      __("Competence Group", "cluevo"),
      CLUEVO_TAXONOMY,
      array(
        'label' => __('Competence Group', "cluevo"),
        'description' => __('Competence Group', "cluevo"),
        'slug' => __('competence-group', "cluevo"),
        'parent' => $competencesResult['term_id']
      )
    );
    $results[] = $competenceAreaResult;
  }

  return $results;
}

/**
 * Flush rewrite rules
 *
 * @return bool
 */
function cluevo_flush_rewrite_rules() {
  $postRes = cluevo_create_metadata_post_type();
  $pageRes = cluevo_create_lms_page_post_type();
  flush_rewrite_rules();
  return true;
}

/**
 * Creates frontend LMS Pages
 *
 * Returns an array with the created page ids
 *
 * @return array
 */
function cluevo_create_lms_pages() {
  global $wpdb;

  cluevo_create_metadata_post_type();
  cluevo_create_lms_page_post_type();
  $pages = [];

  if ( cluevo_get_page_by_title( 'User', ARRAY_A, CLUEVO_PAGE_POST_TYPE) == NULL ) {
    $page = array(
      'post_title'  => 'User',
      'post_status' => 'publish',
      'post_type'   => CLUEVO_PAGE_POST_TYPE,
    );

    $pages[] = wp_insert_post( $page );
  }

  if ( cluevo_get_page_by_title( 'Index', ARRAY_A, CLUEVO_PAGE_POST_TYPE) == NULL ) {
    $page = array(
      'post_title'  => 'Index',
      'post_status' => 'publish',
      'post_type'   => CLUEVO_PAGE_POST_TYPE,
    );

    $pages[] = wp_insert_post( $page );
  }

  if ( cluevo_get_page_by_title( 'Login', ARRAY_A, CLUEVO_PAGE_POST_TYPE) == NULL ) {
    $page = array(
      'post_title'  => 'Login',
      'post_status' => 'publish',
      'post_type'   => CLUEVO_PAGE_POST_TYPE,
    );

    $pages[] = wp_insert_post( $page );
  }

  if ( cluevo_get_page_by_title( 'Logout', ARRAY_A, CLUEVO_PAGE_POST_TYPE) == NULL ) {
    $page = array(
      'post_title'  => 'Logout',
      'post_status' => 'publish',
      'post_type'   => CLUEVO_PAGE_POST_TYPE,
    );

    $pages[] = wp_insert_post( $page );
  }

  return $pages;
}

/**
 * Creates directories for module storage
 *
 * @return bool
 */
function cluevo_create_directories() {
  $success = true;
  foreach (cluevo_get_conf_const('CLUEVO_DIRECTORIES') as $dir) {
    if (!file_exists($dir)) {
      $status = mkdir($dir, 0755, true);
      $success = ($status == false) ? false : $success;
    }
  }

  return $success;
}

function cluevo_create_module_archive_htaccess() {
  $file = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . ".htaccess";
  file_put_contents($file, "DENY FROM ALL");
}

function cluevo_create_cluevo_uploads_htaccess() {
  $file = cluevo_get_conf_const('CLUEVO_ABS_UPLOAD_PATH') . ".htaccess";
  $content = "<FilesMatch \"\.(php|php3|phtml|zip|php\.)$\">\n";
  $content .= "Order Allow,Deny\n";
  $content .= "Deny from all\n";
  $content .= "</FilesMatch>";
  file_put_contents($file, $content);
}

function cluevo_migrate_module_dirs() {
  $plugin_dir = plugin_dir_path(__DIR__);
  $scorm_dir = $plugin_dir . "scorm-modules/";
  $archive_dir = $plugin_dir . "scorm-modules-archive/";
  $dirs = [ $scorm_dir => cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $archive_dir => cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') ];
  $errors = [];
  foreach ($dirs as $curDir => $destDir) {
    if (file_exists($curDir)) {
      if (!file_exists($destDir)) mkdir($destDir, null, true);
      $curDir = rtrim($curDir, "/");
      $modules = glob("$curDir/*");
      if (!empty($modules)) {
        foreach ($modules as $m) {
          if (basename($m) === "." || basename($m) === "..")
            continue;
          $name = basename($m);
          $dest = $destDir . $name . "/";
          $success = true;
          if (!file_exists($dest)) {
            if (is_dir($m)) {
              if (@mkdir($dest)) {
                $dir_iterator = new RecursiveDirectoryIterator($m, FilesystemIterator::SKIP_DOTS);
                $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
                foreach ($iterator as $file) {
                  if (basename($file) === "." || basename($file) === "..")
                    continue;
                  $newPath = $iterator->getSubPathName();
                  $destPath = $dest . $newPath;
                  if (is_dir($file) && !file_exists($destPath)) {
                    if (!@mkdir($destPath, 755, true)) {
                      $errors[] = sprintf(__("An error occurred while trying to create the directory %s", "cluevo"), $destPath);
                      $success = false;
                    }
                  } else {
                    if (!@copy($file, $destPath)) {
                      $errors[] = sprintf(__("An error occurred while trying to move the file %s", "cluevo"), $file);
                      $success = false;
                    } else {
                      @unlink($file);
                    }
                  }
                }
              } else {
                $success = false;
                $errors[] = sprintf(__("The module %s could not be migrated. The module already exists at the new path", "cluevo"), basename($m));
              }
            } else {
              $destArchive = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . basename($m);
              if (!file_exists($destArchive)) {
                if (@copy($m, $destArchive)) {
                  @unlink($m);
                }
              } else {
                $success = false;
                $errors[] = sprintf(__("The module %s could not be migrated. The module already exists at the new path", "cluevo"), basename($m));
              }
            }
          } else {
            $success = false;
            $errors[] = sprintf(__("The module %s could not be migrated. The module already exists at the new path", "cluevo"), basename($m));
          }
          if ($success && is_dir($m)) {
            cluevo_delete_directory($m . "/");
          }
        }
      }
    }
  }
  // TODO: Figure out why this doesn't work. Directory should be empty but is not even though it really is...
  //if (empty($errors)) {
  //echo "removing main dirs\n";
  //foreach ($dirs as $dir => $dest) {
  //if (file_exists($dir))
  //cluevo_delete_directory($dir);
  //}
  //}
  if (!empty($errors))
    return $errors;
  else
    return true;
}

function cluevo_create_default_tree() {
  global $wpdb;

  $name = __("My Learning Tree", "cluevo");
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_TREE;

  $sql = "INSERT IGNORE INTO $table SET item_id = %d, name = %s";
  $name = __("My Learning Tree", "cluevo");

  $itemId = $wpdb->query(
    $wpdb->prepare($sql, [ 1, $name ] )
  );

  $item = cluevo_get_learning_structure_item($itemId, null, false, true);
  if (!empty($item)) {
    $id = wp_insert_post( [ "post_title" => $name, "post_status" => "publish", 'post_type' => CLUEVO_METADATA_POST_TYPE ] );
    $item->metadata_id = $id;
    cluevo_update_learning_structure_item($item);
  }
}

function cluevo_get_module_type_directires() {
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
  $sql = "SELECT type_name FROM $table";
  $results = $wpdb->get_results($sql);
  $dirs = [];
  foreach ($results as $row) {
    $dirs[] = sanitize_file_name(strtolower($row->type_name));
  }

  return $dirs;
}

function cluevo_migrate_modules_to_type_dirs() {
  if (file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH')) && file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'))) {
    $files = scandir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'));
    $zips = scandir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'));
    $modules = cluevo_get_modules();
    $errors = [];
    global $wpdb;

    if (!empty($modules)) {
      foreach ($modules as $m) {
        $type = sanitize_file_name(strtolower($m->type_name));
        $newDir = sanitize_file_name(strtolower($m->type_name)) . "/" . $m->module_dir;
        if (in_array($m->module_dir, $files)) {
          $srcDir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $m->module_dir;
          $destDir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $newDir;

          if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type")) {
            @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type", 0755, true);
          }

          if (file_exists($srcDir)) {
            if (!file_exists($destDir)) {
              if(!@rename($srcDir, $destDir)) {
                $errors[] = sprintf(__("An error occurred while migrating the module: %s", "cluevo"), $m->module_name);
              } else {
                if (!cluevo_update_module($m->module_id, [ "module_dir" => $newDir ])) {
                  $errors[] = sprintf(__("Failed to update the module directory in the database: %s", "cluevo"), $m->module_name);
                }
              }
            } else {
              $errors[] = sprintf(__("Target already exists: %s", "cluevo"), $destDir);
            }
          } else {
            $errors[] = sprintf(__("Source file does not exist: %s", "cluevo"), $srcDir);
          }

        }

        if (in_array($m->module_zip, $zips)) {
          $srcZip = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $m->module_zip;
          $destZip = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $type . "/" . $m->module_zip;

          if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type")) {
            @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type", 0755, true);
          }

          if (file_exists($srcZip)) {
            if (!file_exists($destZip)) {
              if(!@rename($srcZip, $destZip)) {
                $errors[] = sprintf(__("An error occurred while trying to migrate the module ZIP file: %s", "cluevo"), $m->module_name);
              } else {
                if (!cluevo_update_module($m->module_id, [ "module_zip" => "$type/$m->module_zip" ])) {
                  $errors[] = sprintf(__("Failed to update the module zip file in the database: %s", "cluevo"), $m->module_name);
                }
              }
            } else {
              $errors[] = sprintf(__("Target file already exists: %s", "cluevo"), $destZip);
            }
          } else {
            $errors[] = sprintf(__("Source file does not exist: %s", "cluevo"), $srcZip);
          }
        }
      }
    }

    if (empty($errors)) {
      return true;
    } else {
      return $errors;
    }
  }
}

function cluevo_update_module_version_fields() {
  $modules = cluevo_get_modules();
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  if (!empty($modules)) {
    foreach ($modules as $module) {
      if ($module->type_id != 1) continue;

      if (empty($module->scorm_version)) {
        $version = cluevo_get_scorm_version($module);
        if (!empty($version)) {
          $wpdb->query(
            $wpdb->prepare("UPDATE $table SET scorm_version = %s WHERE module_id = %d", [ $version, $module->module_id ])
          );
        }
      }
    }
  }
}
?>
