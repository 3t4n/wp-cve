<?php
if (!defined("CLUEVO_ACTIVE")) exit;

require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");

/**
 * Returns all available modules
 *
 * @return array
 */
function cluevo_get_modules($strLangCode = "")
{
  return cluevo_turbo_get_modules($strLangCode);
}

/**
 * Attempts to find the index file of a given module
 *
 * @param CluevoItem $module
 *
 * @return string
 */
function cluevo_find_module_index($strDir, $boolUrl = true)
{

  $subDirPath = "";
  $manifest = null;
  $result = null;
  $subDirPathName = "";
  $realDir = null;

  if (file_exists($strDir)) {
    if (is_dir($strDir)) {
      $it = new RecursiveDirectoryIterator($strDir);
      $subIt = new RecursiveIteratorIterator($it);
      foreach ($subIt as $file) {
        $name = basename($file);
        $curDir = $it->getBasename();
        if ($name == "imsmanifest.xml") {
          $manifest = $file;
          $subDirPathName = $subIt->getSubPathName();
          $subDirPath = $subIt->getSubPath();
          break;
        }
      }
    }

    if (empty($manifest) || !file_exists($manifest))
      return null;

    $href = null;
    if (!empty($manifest) && file_exists($manifest)) {
      $xml = null;
      try {
        $xml = @simplexml_load_file($manifest);
      } catch (Exception $e) {
        return null;
      }
      if (empty($xml)) return null;

      $namespaces = $xml->getNamespaces(true);
      foreach ($namespaces as $name => $url) {
        if (empty($name)) $name = "ns";
        $xml->registerXPathNamespace($name, $url);
      }
      $elements = $xml->xpath('//ns:resource[@adlcp:scormType="sco"]/@href | //ns:resource[@adlcp:scormtype="sco"]/@href');
      if (!empty($elements)) {
        $href = (string)$elements[0];
      }
    }

    if (!empty($href) && !empty($manifest)) {
      if (!empty($subDirPath))
        $result = "$subDirPath/$href";
      else
        $result = "$href";
    } else {
      $haystack = ["index_lms_html5.html", "index.html", "index_scorm.html", "launchpage.html"];

      if (file_exists($strDir)) {
        $it = new RecursiveDirectoryIterator($strDir);
        $subIt = new RecursiveIteratorIterator($it);
        foreach ($subIt as $file) {
          $name = basename($file);
          $curDir = $it->getBasename();
          $subDirPathName = $subIt->getSubPathName();
          $subDirPath = $subIt->getSubPath();
          if (in_array($name, $haystack)) {
            if (!empty($subDirPath)) {
              $result = "$subDirPath/$name";
            } else {
              $result = "$name";
            }
          }
        }
      }
    }
  }

  if ($boolUrl) {
    if (!empty($result))
      return cluevo_get_conf_const('CLUEVO_MODULE_URL') . "/" . $result;
    else
      return null;
  } else {
    if (!empty($result))
      return $result;
    return null;
  }

  return null;
}

function cluevo_find_scos($strDir)
{
  $subDirPath = "";
  $manifest = null;
  $result = null;
  $subDirPathName = "";
  $realDir = null;
  $scos = [];

  if (file_exists($strDir)) {
    $it = new RecursiveDirectoryIterator($strDir);
    $subIt = new RecursiveIteratorIterator($it);
    foreach ($subIt as $file) {
      $name = basename($file);
      $curDir = $it->getBasename();
      if ($name == "imsmanifest.xml") {
        $manifest = $file;
        $subDirPathName = $subIt->getSubPathName();
        $subDirPath = $subIt->getSubPath();
        break;
      }
    }

    if (empty($manifest) || !file_exists($manifest))
      return null;

    if (!empty($manifest) && file_exists($manifest)) {
      try {
        $xml = @simplexml_load_file($manifest);
        if (empty($xml)) return [];
        $xml->registerXPathNamespace("ns", "http://www.imsglobal.org/xsd/imscp_v1p1");
        $xml->registerXPathNamespace("adlcp", "http://www.adlnet.org/xsd/adlcp_v1p3");
        $xpath = new DOMXPath(dom_import_simplexml($xml)->ownerDocument);
        $elements = $xml->xpath('//ns:resource[@adlcp:scormType="sco"]');
        if (!empty($elements)) {
          foreach ($elements as $el) {
            $sco = trim($subDirPath, "/") . "/" . (string)$el["href"];
            $identifier = (string)$el["identifier"];
            $item = $xml->xpath('//ns:item[@identifierref="' . $identifier . '"]');
            if (!empty($item)) {
              if (!empty($item[0]) && !empty($item[0]->title)) {
                $title = (string)$item[0]->title[0];
                $scos[] = ["title" => $title, "href" => $sco];
              }
            }
          }
        }
      } catch (Exception $e) {
        return [];
      }
    }
  }

  return $scos;
}

function cluevo_find_module_manifest($strDir)
{
  $manifest = null;
  if (!file_exists($strDir)) return null;
  $it = new RecursiveDirectoryIterator($strDir);
  foreach (new RecursiveIteratorIterator($it) as $file) {
    $name = basename($file);
    $curDir = $it->getBasename();
    if ($name == "imsmanifest.xml") {
      $manifest = $file;
      break;
    }
  }

  return $manifest;
}

function cluevo_get_blacklisted_extensions()
{
  return [
    "php",
    "php3",
    "htaccess",
    "exe",
    "phtml"
  ];
}

function cluevo_get_blacklisted_filenames()
{
  return [
    "htaccess"
  ];
}

function cluevo_extract_scorm_module($strFile, $strDest)
{
  $zip = new ZipArchive();
  $isOpen = $zip->open($strFile);
  $files = [];
  $blacklistExt = cluevo_get_blacklisted_extensions();
  $blacklistNames = cluevo_get_blacklisted_filenames();
  for ($i = 0; $i < $zip->numFiles; $i++) {
    $stat = $zip->statIndex($i);
    $filename = strtolower(pathinfo($stat["name"],  PATHINFO_FILENAME));
    $ext = strtolower(pathinfo($stat["name"],  PATHINFO_EXTENSION));
    if (in_array($ext, $blacklistExt) || in_array($filename, $blacklistNames)) continue;

    $files[] = $stat["name"];
  }
  if ($isOpen === TRUE) {
    $extracted = $zip->extractTo($strDest, $files);
    $zip->close();
    if ($extracted) {
      return true;
    } else {
      return false;
    }
  }

  return false;
}

function cluevo_extract_file($strFile, $strDest)
{
  $zip = new ZipArchive();
  $isOpen = $zip->open($strFile);

  if ($isOpen === TRUE) {
    $stat = $zip->statIndex(0);
    $extracted = $zip->extractTo($strDest, $stat["name"]);
    $zip->close();
    if ($extracted) {
      return cluevo_path_join($strDest, $stat["name"]);
    } else {
      return false;
    }
  }

  return false;
}

/**
 * Returns an array with a progress summary over multiple modules
 *
 * This is used in the progress ui to get a summary for any item and the modules it contains
 *
 * @param mixed $modules
 */
function cluevo_get_progress_summary_for_modules($modules)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
  $userTable = $wpdb->users;
  if (empty($modules)) return null;
  if (!is_array($modules)) $modules = [$modules];

  $placeholders = array_fill(0, count($modules), '%d');
  $format = implode(', ', $placeholders);
  if (!is_array($modules))
    $modules = [$modules];

  $sql = "SELECT
      COUNT(DISTINCT user_id) AS users,
      COUNT(attempt_id) AS attempts,
      AVG(score_raw) AS avg_score_raw,
      AVG(IF(score_max > 0, score_raw / score_max, 0)) AS avg_score_scaled,
      (SELECT COUNT(DISTINCT user_id) FROM $table WHERE completion_status = 'completed' && success_status = 'passed' AND module_id IN ($format)) AS completed_count,
      (SELECT IF(score > 0, CONCAT(display_name, ' (', FORMAT(score, 2), ')'), display_name)
        FROM (
          SELECT GROUP_CONCAT(DISTINCT u.display_name) AS display_name, MAX(score_raw) AS score
          FROM $table p 
          INNER JOIN $userTable u
            ON p.user_id = u.ID
          WHERE module_id IN ($format)
          GROUP BY p.user_id
          ORDER BY score DESC
          LIMIT 1
        ) AS tmp_best_user
      ) AS best_user,
      (SELECT IF(score > 0, CONCAT(display_name, ' (', FORMAT(score, 2), ')'), display_name)
        FROM (
          SELECT GROUP_CONCAT(DISTINCT u.display_name) AS display_name, MIN(score_raw) AS score
          FROM $table p 
          INNER JOIN $userTable u
            ON p.user_id = u.ID
          WHERE module_id IN ($format)
          GROUP BY p.user_id
          ORDER BY score ASC
          LIMIT 1
        ) AS tmp_worst_user
      ) AS worst_user
    FROM $table
    WHERE
      module_id IN ($format)";

  $parms = array_merge($modules, $modules, $modules, $modules);

  $results = $wpdb->get_row(
    $wpdb->prepare($sql, $parms)
  );
  if (!empty($results)) {
    $results->modules = count($modules);
  }

  return $results;
}

/**
 * Recursively deletes a directory and its contents
 *
 * @throws InvalidArgumentException
 *
 * @param string $dirPath
 */
function cluevo_delete_directory($strDir)
{
  if (!is_dir($strDir)) {
    if (is_file($strDir))
      return unlink($strDir);
    return false;
  }
  $mainIt = new RecursiveDirectoryIterator($strDir, FilesystemIterator::SKIP_DOTS);
  $it = new RecursiveIteratorIterator($mainIt, RecursiveIteratorIterator::CHILD_FIRST);
  foreach ($it as $file) {
    if ($file->isDir() && file_exists($file->getPathname())) rmdir($file->getPathname() . "/");
    else unlink($file->getPathname());
  }
  rmdir($strDir);
}

/**
 * Outputs an array containing the exp table
 *
 * The array contains the exp table calculated from the amount of exp needed for
 * the first levelup
 *
 */
function cluevo_get_exp_table()
{
  $maxLevel = (int)get_option('cluevo-max-level', CLUEVO_DEFAULT_MAX_LEVEL);
  $first = (int)get_option('cluevo-exp-first-level', CLUEVO_DEFAULT_FIRST_LEVEL_EXP);
  $sum = $first;
  $levels = [1 => 0];
  for ($level = 1; $level < $maxLevel; $level++) {
    $coeff = 20;
    if ($level > 9)
      $coeff = 10;
    if ($level > 15)
      $coeff = 5;
    $sum += (($sum / 100) * $coeff);
    $levels[$level] = floor($sum);
  }

  return $levels;
}

/**
 * Sanitizes the given level
 *
 * Returns a sanitized level, checking whether the given level is within the
 * allowed level range and making sure it's numeric
 *
 * @param mixed $level
 *
 * @return integer
 */
function cluevo_sanitize_level_input($level)
{
  if (!is_numeric($level) || empty($level) || $level < 0)
    $level = CLUEVO_DEFAULT_MAX_LEVEL;

  $level = ($level <= CLUEVO_ABS_MAX_LEVEL) ? $level : CLUEVO_ABS_MAX_LEVEL;
  return $level;
}

/**
 * Sanitizes the given exp amount
 *
 * Makes sure the first level exp is numeric and not empty
 *
 * @param mixed $exp
 *
 * @return integer
 */
function cluevo_sanitize_first_level_exp($exp)
{
  if (!is_numeric($exp) || empty($exp) || $exp < 0)
    $exp = CLUEVO_DEFAULT_FIRST_LEVEL_EXP;

  $exp = ($exp <= CLUEVO_ABS_MAX_FIRST_LEVEL_EXP) ? $exp : CLUEVO_DEFAULT_FIRST_LEVEL_EXP;

  return $exp;
}

/**
 * Sanitizes level titles
 *
 * Parses and sorts the given string into an array
 *
 * @param string $titles
 *
 * @return array
 */
function cluevo_sanitize_level_titles($titles)
{
  if (!empty($titles)) {
    $lines = explode("\n", $titles);
    $results = [];
    foreach ($lines as $line) {
      $parts = explode(':', $line, 2);
      if (count($parts) == 2) {
        if (!is_numeric($parts[0]))
          continue;
        $results[trim($parts[0])] = trim($parts[1]);
      }
    }

    ksort($results);
    return $results;
  }

  return [];
}

function cluevo_sanitize_post_id($id)
{
  if (!is_numeric($id)) return 0;
  $page = get_post($id);
  if (empty($page)) return 0;
  return (int)$id;
}

/**
 * Sanitizes checkbox values
 *
 * @param mixed $box
 *
 * @return integer
 */
function cluevo_sanitize_checkbox($box)
{
  return (!empty($box)) ? 1 : 0;
}

/**
 * Sanitizes the bbb server url
 *
 * Returns an empty string if the url is not valid
 *
 * @param string $strUrl
 *
 * @return string
 */
function cluevo_sanitize_bbb_url($strUrl)
{
  if (filter_var($strUrl, FILTER_VALIDATE_URL)) {
    return $strUrl;
  }

  add_settings_error("cluevo-bbb-url", "error-bbb-url", __("Invalid Server URL"), "error", "cluevo");

  return "";
}

function cluevo_sanitize_int_string($strInput)
{
  return (int)preg_replace('/[^\d]+/i', "", $strInput);
}

/**
 * Displays a cluevo template
 *
 * Checks for overriden Cluevo templates in the current theme and returns the
 * overriden template or the default template if no override was found
 *
 * @param string $template
 */
function cluevo_display_template($template)
{
  $template = sanitize_file_name($template);
  if (($tpl = locate_template(array(CLUEVO_THEME_TPL_PATH . '/' . $template . '.php'))) != '') {
    include($tpl);
  } else {
    include cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/' . $template . '.php';
  }
}

/**
 * Outputs a filter select box
 *
 * Build a select box using the passed parameters and outputs it
 *
 * @param string $strName The name of the filter
 * @param string $strEmptyLabel The label used for the unfiltered option
 * @param string $strEmptyValue The value used to get unfiltered results
 * @param array $values The various filter values
 * @param mixed $selected The currently selected filter value
 */
function cluevo_render_admin_table_filter($strName, $strEmptyLabel, $strEmptyValue, $values, $selected)
{ ?>
  <select id="filter-<?php echo esc_attr($strName); ?>" class="cluevo-filter-input" name="<?php echo esc_attr($strName); ?>" onChange="this.form.submit();">
    <option value="<?php echo esc_attr($strEmptyValue); ?>"><?php echo esc_attr($strEmptyLabel); ?></option>
    <?php if (!empty($values)) { ?>
      <?php foreach ($values as $v) { ?>
        <option value="<?php echo esc_attr($v->value); ?>" <?php echo ($v->value == $selected) ? "selected" : ""; ?>><?php echo esc_html(mb_strimwidth($v->label, 0, 20, "...")); ?></option>
      <?php } ?>
    <?php } ?>
  </select>
  <?php
}

function cluevo_get_languages()
{
  global $wpdb;

  $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_LANGUAGES);

  return $result;
}

function cluevo_strip_non_alphanum($string)
{
  $string = sanitize_textarea_field($string);
  return preg_replace("/[^A-Za-z0-9]/", '', $string);
}

function cluevo_strip_non_alphanum_dash($string)
{
  $string = sanitize_textarea_field($string);
  return preg_replace("/[^A-Za-z0-9_-]/", '', $string);
}

function cluevo_strip_non_scorm_parm_chars($string)
{
  $string = sanitize_textarea_field($string);
  return preg_replace("/[^a-z0-9_\-\.]/", '', strtolower($string));
}

function cluevo_strip_non_alpha($string)
{
  $string = sanitize_textarea_field($string);
  return preg_replace("/[^A-Za-z]/", '', $string);
}

function cluevo_strip_non_alpha_blank($string)
{
  $string = sanitize_textarea_field($string);
  return preg_replace("/[^A-Za-z ]/", '', $string);
}

/**
 * Returns the module type associated with the given mime type
 *
 * @param mixed $strMime
 */
function cluevo_get_module_type_from_mime_type($strMime)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_MIME_TYPES;
  $sql = "SELECT type_id FROM $table WHERE mime_type = %s";
  return $wpdb->get_var(
    $wpdb->prepare($sql, [$strMime])
  );
}

function cluevo_get_module_type_name_from_mime_type($strMime)
{
  global $wpdb;
  $mimeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_MIME_TYPES;
  $typeTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_TYPES;
  $sql = "SELECT t.type_name FROM $typeTable t INNER JOIN $mimeTable m ON t.type_id = m.type_id WHERE m.mime_type = %s";
  $type = $wpdb->get_var(
    $wpdb->prepare($sql, [$strMime])
  );
  if (!empty($type)) {
    return $type;
  } else {
    return "unknown";
  }
}

function cluevo_activate_module_zip($args)
{
  $mime = $args["mime"];
  $args["messages"][] = __("Running ZIP module handler", "cluevo");
  if ($mime != "application/zip" || $args["handled"] === true) {
    $args["messages"][] = __("File already handled", "cluevo");
    return;
  }

  if (!cluevo_is_scorm_zip($args["module"])) {
    $args["messages"][] = __("File is not a scorm zip module", "cluevo");
    return;
  }

  $args["handled"] = true;
  $archivePath = $args["module"];
  $filename = sanitize_file_name(pathinfo($archivePath, PATHINFO_FILENAME));
  $title = (!empty($args["title"])) ? $args["title"] : $filename;
  $type = sanitize_key(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));
  $realPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename" . "/";
  $dir = "$type/$filename";
  $zip = $type . "/" . basename($args["module"]);
  $lang = $args["lang"];
  $parentModuleId = $args["parentModuleId"];
  //if (!array_key_exists("result", $args)) $args["result"] = [];

  $targetDirExists = true;
  if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/")) {
    $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/", 0755, true);
  }

  $overwrite = false;
  if (file_exists($realPath)) {
    cluevo_delete_directory($realPath);
    $overwrite = true;
  }

  $args["messages"][] = __("Extracting scorm zip archive", "cluevo");
  cluevo_extract_scorm_module($archivePath, $realPath);

  $id = null;
  if (!empty($parentModule)) {
    if (!cluevo_module_exists($parentModuleId, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
      $id = cluevo_create_module_metadata_post($title . " - $lang");
    }
  } else {
    if (!cluevo_module_exists($title, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
      $id = cluevo_create_module_metadata_post($title);
    }
  }

  $moduleType = CLUEVO_SCORM_MODULE_TYPE_ID; // module_type = scorm
  $href = cluevo_find_module_index($realPath, false);
  $scormVersion = cluevo_get_scorm_version_from_manifest($realPath);

  if (!$overwrite) {
    $args["messages"][] = __("Creating module", "cluevo");
    cluevo_create_module($title, $moduleType, $id, $dir, $zip, $href, $lang, $parentModuleId, $scormVersion);
    $args["messages"][] = __("New module created", "cluevo");
    $args["messages"][] = __("Module activated.", "cluevo");
    $module = cluevo_get_module($title, $lang, true);
    $args["result"] = $module;
  } else {
    $module = cluevo_get_module($title, $lang, true);
    $args["messages"][] = __("The existing module has been overwritten", "cluevo");
    $args["result"] = $module;

    if (!empty($module)) {
      $post = get_post($module->metadata_id);
      if (empty($post) || empty($module->metadata_id)) {
        $id = cluevo_create_module_metadata_post($title);
        cluevo_update_module_metadata_id($module->module_id, $id);
      }
    } else {
      cluevo_create_module($title, $moduleType, $id, $dir, $zip, $href, $lang, $parentModuleId, $scormVersion);
      $module = cluevo_get_module($title, $lang, true);
      $args["result"] = $module;
    }
  }
}

function cluevo_is_scorm_zip($file)
{
  $found = false;

  $za = new ZipArchive();
  $za->open($file);

  for ($i = 0; $i < $za->numFiles; $i++) {
    $stat = $za->statIndex($i);
    $file = basename($stat['name']);
    if (strtolower($file) == "imsmanifest.xml") {
      $found = true;
      break;
    }
  }

  return $found;
}

function cluevo_is_media_zip($file)
{
  $za = new ZipArchive();
  $za->open($file);

  $stat = $za->statIndex(0);
  $ext = strtolower(pathinfo($stat["name"],  PATHINFO_EXTENSION));
  if (in_array($ext, ["mp4", "webm", "mpeg", "wav", "mp3"])) {
    return true;
  }
  return false;
}

function cluevo_is_pdf_zip($file)
{
  $za = new ZipArchive();
  $za->open($file);

  $stat = $za->statIndex(0);
  $ext = strtolower(pathinfo($stat["name"],  PATHINFO_EXTENSION));
  if ($ext === "pdf") {
    return true;
  }
  return false;
}

function cluevo_js_redirect($strUrl)
{
  $string = '<script type="text/javascript">';
  $string .= 'window.location = "' . esc_url_raw($strUrl) . '"';
  $string .= '</script>';
  echo wp_kses($string, ["script" => ["type" => 1]]);
}

function cluevo_activate_module_media($args)
{
  $acceptedMimeTypes = [
    'audio/mp3',
    'audio/wav',
    'audio/webm',
    'video/webm',
    'video/mp4',
    'audio/mpeg',
  ];
  $mime = $args["mime"];
  if (!in_array($mime, $acceptedMimeTypes) || $args["handled"] === true)
    return;

  $args["handled"] = true;

  if (!array_key_exists("module", $args)) $args["module"] = '';

  $archivePath = $args["module"];
  $filename = sanitize_file_name(pathinfo($archivePath, PATHINFO_FILENAME));
  $title = (!empty($args["title"])) ? $args["title"] : $filename;
  $type = sanitize_key(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));
  $basename = sanitize_file_name(pathinfo($archivePath, PATHINFO_BASENAME));
  $realPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename" . "/";
  $dir = "$type/$filename";
  $zip = $type . "/" . $basename;
  $lang = $args["lang"];
  $parentModuleId = $args["parentModuleId"];

  $overwrite = false;
  $moduleType = cluevo_get_module_type_from_mime_type($mime);
  $destPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/" . $filename . '/' . $basename;

  if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename")) {
    //echo "creating targetdir: " . cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename";
    $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename", 0755, true);
  } else {
    //echo "deleting targetdir: " . cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename";
    cluevo_delete_directory(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename");
    $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename", 0755, true);
    $overwrite = true;
  }

  if (@copy($archivePath, $destPath)) {
    $id = null;
    if (!empty($parentModule)) {
      if (!cluevo_module_exists($parentModuleId, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
        $metaTitle = (!empty($lang)) ? $title . " - $lang" : $filename;
        $id = cluevo_create_module_metadata_post($metaTitle);
      }
    } else {
      if (!cluevo_module_exists($title, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
        $id = cluevo_create_module_metadata_post($title);
      }
    }

    if (!$overwrite) {
      cluevo_create_module($title, $moduleType, $id, $dir, $zip, $basename, $lang, $parentModuleId);
      $module = cluevo_get_module($title);
      $args["result"] = $module;
    } else {
      $module = cluevo_get_module($title);
      $args["result"] = $module;

      if (!empty($module)) {
        $post = get_post($module->metadata_id);
        if (empty($post) || empty($module->metadata_id)) {
          $id = cluevo_create_module_metadata_post($title);
          cluevo_update_module_metadata_id($module->module_id, $id);
        }
      } else {
        $args["errors"][] = __("An error occurred while activating the module.", "cluevo");
      }
    }
    if ($overwrite) {
      $args["messages"][] = __("The existing module has been overwritten.", "cluevo");
    } else {
      $args["messages"][] = __("Modul activated.", "cluevo");
    }
  } else {
    $args["errors"][] = __("An error occurred while activating the module.", "cluevo");
  }
}

function cluevo_activate_module_pdf($args)
{
  $acceptedMimeTypes = [
    'application/pdf'
  ];

  $mime = $args["mime"];
  if (!in_array($mime, $acceptedMimeTypes) || $args["handled"] === true)
    return;

  $args["handled"] = true;

  if (!array_key_exists("module", $args)) $args["module"] = '';

  $archivePath = $args["module"];
  $filename = sanitize_file_name(pathinfo($archivePath, PATHINFO_FILENAME));
  $title = (!empty($args["title"])) ? $args["title"] : $filename;
  $type = sanitize_key(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));
  $basename = sanitize_file_name(pathinfo($archivePath, PATHINFO_BASENAME));
  $realPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename" . "/";
  $dir = "$type/$filename";
  $zip = $type . "/" . $basename;
  $lang = $args["lang"];
  $parentModuleId = $args["parentModuleId"];

  $overwrite = false;
  $moduleType = cluevo_get_module_type_from_mime_type($mime);
  $destPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/" . $filename . '/' . $basename;

  $targetDirExists = true;
  if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename")) {
    //echo "creating targetdir: " . cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename";
    $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename", 0755, true);
  } else {
    //echo "deleting targetdir: " . cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename";
    cluevo_delete_directory(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename");
    $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . "$type/$filename", 0755, true);
    $overwrite = true;
  }

  if (@copy($archivePath, $destPath)) {
    $id = null;
    if (!empty($parentModule)) {
      if (!cluevo_module_exists($parentModuleId, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
        $metaTitle = (!empty($lang)) ? $title . " - $lang" : $filename;
        $id = cluevo_create_module_metadata_post($metaTitle);
      }
    } else {
      if (!cluevo_module_exists($title, $lang)) { // create metadata page for the uploaded module if the page doesn't yet exist
        $id = cluevo_create_module_metadata_post($title);
      }
    }

    if (!$overwrite) {
      cluevo_create_module($title, $moduleType, $id, $dir, $zip, $basename, $lang, $parentModuleId);
      $module = cluevo_get_module($title);
      $args["result"] = $module;
    } else {
      $module = cluevo_get_module($title);
      $args["result"] = $module;

      if (!empty($module)) {
        $post = get_post($module->metadata_id);
        if (empty($post) || empty($module->metadata_id)) {
          $id = cluevo_create_module_metadata_post($title);
          cluevo_update_module_metadata_id($module->module_id, $id);
        }
      } else {
        $args["errors"][] = __("An error occurred while activating the module.", "cluevo");
      }
    }
    if ($overwrite) {
      $args["messages"][] = __("The existing module has been overwritten.", "cluevo");
    } else {
      $args["messages"][] = __("Modul activated.", "cluevo");
    }
  } else {
    $args["errors"][] = __("An error occurred while activating the module.", "cluevo");
  }
}

function cluevo_display_help_tab_posts()
{
  include(plugin_dir_path(__DIR__) . "help/posts.php");
}

function cluevo_display_help_tab_lms()
{
  include(plugin_dir_path(__DIR__) . "help/lms.php");
}

function cluevo_display_help_tab_competence()
{
  include(plugin_dir_path(__DIR__) . "help/competence.php");
}

function cluevo_display_help_tab_reports()
{
  include(plugin_dir_path(__DIR__) . "help/reports.php");
}

function cluevo_display_help_tab_permissions()
{
  include(plugin_dir_path(__DIR__) . "help/permissions.php");
}

function cluevo_display_help_tab_settings()
{
  include(plugin_dir_path(__DIR__) . "help/settings.php");
}

function cluevo_display_help_tab_shortcodes()
{
  include(plugin_dir_path(__DIR__) . "help/shortcodes.php");
}

function cluevo_get_scorm_version($module)
{
  $dir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_dir;
  return cluevo_get_scorm_version_from_manifest($dir);
}

function cluevo_get_scorm_version_from_manifest($strDir)
{
  $manifest = cluevo_find_module_manifest($strDir);
  if (empty($manifest) || !file_exists($manifest)) return false;
  try {
    $xml = @simplexml_load_file($manifest);
  } catch (Exception $e) {
    return "1.2";
  }
  if (!empty($xml->metadata) && count($xml->metadata) > 0) {
    $version = $xml->metadata[0]->schemaversion;
    if ("$version" == "1.2") return "1.2";
    else return "2004";
  } else {
    if (!empty($xml["version"])) {
      return (string)$xml["version"];
    } else {
      return "1.2";
    }
  }
}

function cluevo_human_filesize($bytes, $decimals = 2)
{
  $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function cluevo_kebab_to_camel($strInput)
{
  $result = str_replace('-', '', ucwords($strInput, '-'));
  $result = lcfirst($result);
  return $result;
}

function cluevo_get_extensions()
{
  $plugins = get_plugins();
  $ext = [];
  foreach ($plugins as $file => $plugin) {
    //if (!is_plugin_active($file)) continue;
    if ($plugin['Name'] !== "CLUEVO LMS" && (stristr($plugin['Name'], 'cluevo') || stristr($plugin['Description'], 'cluevo'))) {
      $ext[] = $plugin;
    }
  }

  return $ext;
}

function cluevo_get_mysql_server_version()
{
  global $wpdb;
  $version = $wpdb->get_var('SELECT VERSION()');
  return $version;
}

function cluevo_get_item_data_string($item)
{
  $data = [];
  $data[] = 'data-item-id="' . esc_attr($item->item_id) . '"';
  $data[] = 'data-module-id="' . esc_attr($item->module_id) . '"';
  foreach ($item->settings as $key => $value) {
    if (is_array($value) && count($value) == 1) {
      $value = maybe_unserialize($value[0]);
    } else {
      $value = maybe_unserialize($value);
    }
    if (!empty($value)) {
      if (!is_string($value)) {
        $value = json_encode($value);
      }
      $key = str_replace(CLUEVO_META_DATA_PREFIX, '', $key);
      $key = str_replace('_', '-', $key);
      $data[] = "data-" . sanitize_key($key) . "='" . esc_attr($value) . "'";
    }
  }
  $string = implode(" ", $data);
  return $string;
}

function cluevo_display_db_upgrade_notice()
{
  if (!current_user_can("administrator")) return;
  $curDatabaseVersion = get_option(CLUEVO_DB_VERSION_OPT_KEY);
  $url = admin_url("index.php?cluevo-update-db=go");
  if ($curDatabaseVersion != CLUEVO_PLUGIN_DB_VERSION) {
    $html = '<div class="notice notice-warning">';
    $html .= '<p>';
    $html .= esc_html__("The CLUEVO LMS database has to be updated to a newer version. Click here to start the udpate process: ", 'cluevo');
    $html .= '<a href="' . esc_url(admin_url('admin.php?page=') . CLUEVO_ADMIN_PAGE_DATABASE) . '">' . __("Update", "cluevo") . '</a>';
    $html .= '</p>';
    $html .= '</div>';
    echo wp_kses($html, wp_kses_allowed_html("post"));
  }
}

function cluevo_display_db_upgrade_result()
{
  if (!current_user_can("administrator")) return;
  $display = get_option('cluevo-display-db-update-result');
  update_option('cluevo-display-db-update-result', false);
  if ($display) {
  ?>
    <div class="notice notice-success is-dismissible">
      <p>
        <?php esc_html_e("The CLUEVO LMS database has been updated.", 'cluevo'); ?>
      </p>
    </div>
<?php
  }
}

function cluevo_get_page_by_title($page_title, $output = OBJECT, $post_type = 'page')
{
  $args = [
    'title'                  => $page_title,
    'post_type'              => $post_type,
    'post_status'            => 'all',
    'posts_per_page'         => 1,
    'ignore_sticky_posts'    => true,
    'update_post_term_cache' => false,
    'update_post_meta_cache' => false,
    'no_found_rows'          => true,
    'orderby'                => 'post_date ID',
    'order'                  => 'ASC',
  ];
  $query = new WP_Query($args);
  $pages = $query->posts;

  if (empty($pages)) {
    return null;
  }

  return get_post($pages[0], $output);
}

// these hooks are here because so they are active for rest api calls
add_action('cluevo_activate_module', 'cluevo_activate_module_zip');
add_action('cluevo_activate_module', 'cluevo_activate_module_media');
add_action('cluevo_activate_module', 'cluevo_activate_module_pdf');
?>
