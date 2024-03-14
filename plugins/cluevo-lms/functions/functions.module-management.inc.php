<?php
if (!defined("CLUEVO_ACTIVE")) exit;

/**
 * Retrieves a module id by it's name from the database
 *
 * @param string $strName
 *
 * @return int|null
 */
function cluevo_get_module_id_by_name($strName)
{
  global $wpdb;
  $sql = "SELECT module_id FROM " . $wpdb->prefix . CLUEVO_DB_TABLE_MODULES . " WHERE module_name = %s";
  $result = $wpdb->get_var($wpdb->prepare($sql, [$strName]));

  return (!empty($result)) ? (int)$result : null;
}

/**
 * Removes a module from the database
 *
 * @param int $intId
 *
 * @return int|false
 */
function cluevo_remove_module($intId)
{
  $module = cluevo_get_module($intId);
  if (!empty($module)) {
    $delModule = $module->module_dir;
    $delPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $delModule;
    $delZip = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $module->module_zip;
    if (!empty($module->module_dir) && file_exists($delPath) && !empty($delPath)) {
      if (!empty($delModule)) {
        cluevo_delete_directory($delPath);
      }
      if (!empty($module->module_zip) && file_exists($delZip) && !empty($delZip))
        @unlink($delZip);
    }
  }

  global $wpdb;
  $tables = [
    $wpdb->prefix . CLUEVO_DB_TABLE_MODULES,
    $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULES,
    $wpdb->prefix . CLUEVO_DB_TABLE_TREE_MODULE_DEPENDENCIES,
    $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS,
    $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_COMPETENCES,
    $wpdb->prefix . CLUEVO_DB_TABLE_MODULE_PARMS
  ];
  $results = [];
  foreach ($tables as $table) {
    $sql = "DELETE FROM $table WHERE module_id = %d";
    $results[] = $wpdb->query($wpdb->prepare($sql, [$intId]));
  }
  $result = (!empty($results[0])) ? $results[0] : false;
  if ($result !== false) {
    $metaId = cluevo_get_metadata_id_from_module_id($intId);
    if (!empty($metaId)) {
      wp_delete_post($metaId, true);
    }
  }
  return $results;
}

/**
 * Returns the metadata page id of a module
 *
 * @param int $intModuleId
 *
 * @return int|false
 */
function cluevo_get_metadata_id_from_module_id($intModuleId)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT metadata_id FROM $table WHERE module_id = %d";
  $result = $wpdb->get_var(
    $wpdb->prepare(
      $sql,
      [$intModuleId]
    )
  );

  return (!empty($result)) ? (int)$result : false;
}

/**
 * Creates or updates a module's database entry
 *
 * @param int $strModule
 * @param int $intMetadataId
 * @param string $strDir
 * @param string $strZipFile
 * @param string $strIndex
 *
 * @return int|false
 */
function cluevo_create_module($strModule, $intType, $intMetadataId, $strDir, $strZipFile, $strIndex, $strLang = null, $intParentId = null, $strScormVersion = null)
{
  global $wpdb;
  $sql = "REPLACE INTO " . $wpdb->prefix . CLUEVO_DB_TABLE_MODULES . " SET module_name = %s, metadata_id = %d, module_dir = %s, module_zip = %s, module_index = %s, lang_code = %s, type_id = %d, scorm_version = %s";
  $parms = [$strModule, $intMetadataId, $strDir, $strZipFile, $strIndex, $strLang, $intType, $strScormVersion];

  if (!empty($intParentId)) {
    $sql .= ", module_id = %d";
    $parms[] = $intParentId;
  }

  $result = $wpdb->query($wpdb->prepare($sql, $parms));
  cluevo_clear_turbo_cache();
  return $result;
}

function cluevo_module_name_exists($strName)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $sql = "SELECT COUNT(*) AS count FROM {$table} WHERE module_name = %s";
  $result = $wpdb->get_var($wpdb->prepare($sql, [$strName]));
  return (int)$result > 0;
}

function cluevo_set_module_language($intModuleId, $strLangCodeOld, $strLangCodeNew)
{
  global $wpdb;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

  //if (!cluevo_module_language_exists($intModuleId, $strLangCodeNew)) {
  $sql = "UPDATE IGNORE $moduleTable SET lang_code = %s WHERE module_id = %d AND lang_code = %s";

  $result = $wpdb->query(
    $wpdb->prepare($sql, [sanitize_key($strLangCodeNew), $intModuleId, sanitize_key($strLangCodeOld)])
  );

  return ($result !== false && is_numeric($result) && $result > 0);
  //}

  //return false;
}

function cluevo_module_language_exists($intModuleId, $strLangCode)
{
  global $wpdb;
  $moduleTable = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;

  $sql = "SELECT COUNT(*) FROM $moduleTable  WHERE module_id = %d AND lang_code = %s";

  $result = $wpdb->get_var(
    $wpdb->prepare($sql, [$intModuleId, $strLangCode])
  );

  return ((int)$result == 0);
}
/**
 * Handles the module upload
 *
 * Unpacks files, creates db entries and metadata posts
 */
function cluevo_handle_module_upload(&$errors, &$messages, &$handled, &$result = null)
{
  $messages[] = __("Handling module upload", "cluevo");
  if (!empty($result) && is_numeric($result)) {
    $messages[] = __("Attempting to update module", "cluevo");
    $oldModule = cluevo_get_module((int)$result);
    $moduleDir = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $oldModule->module_dir);
    $messages[] = esc_html(sprintf(__("Existing module found: %s", "cluevo"), $oldModule->module_name));
    $archivePath = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'), $oldModule->module_zip);
    $file = sanitize_text_field($_FILES['module-file']['tmp_name']);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = sanitize_mime_type(finfo_file($finfo, $file));
    finfo_close($finfo);
    $tmp = uniqid();
    if ($mime === 'application/zip' && cluevo_is_scorm_zip($file)) {
      $handled = true;
      $messages[] = __("SCORM file detected", "cluevo");

      if (!empty($oldModule->module_zip) && $archivePath !== cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') && file_exists($archivePath)) {
        $messages[] = esc_html(sprintf(__("Backing up zip: %s", "cluevo"), $archivePath));
        @rename($archivePath, $archivePath . $tmp);
      }
      if (empty($oldModule->module_zip)) {
        $filename = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_FILENAME));
        $ext = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_EXTENSION));
        $type = sanitize_file_name(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));

        $blacklistExt = cluevo_get_blacklisted_extensions();
        $blacklistNames = cluevo_get_blacklisted_filenames();
        if (in_array($ext, $blacklistExt) || in_array($filename, $blacklistNames)) {
          $errors[] = __("This type of file is not allowed", "cluevo");
          return;
        }

        $archivePath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . strtolower($type . '/' . sanitize_file_name($_FILES["module-file"]['name']));
      }
      if (move_uploaded_file($file, $archivePath)) {
        $backedUp = false;
        if (file_exists($moduleDir)) {
          $messages[] = esc_html(sprintf(__("Backing up: %s", "cluevo"), $moduleDir));
          $backedUp = rename($moduleDir, $moduleDir . $tmp);
        }
        if ($backedUp) {
          cluevo_extract_scorm_module($archivePath, $moduleDir);
          $href = cluevo_find_module_index($moduleDir, false);
          $scormVersion = cluevo_get_scorm_version_from_manifest($moduleDir);
          cluevo_create_module(
            $oldModule->module_name,
            $oldModule->type_id,
            $oldModule->metadata_id,
            $oldModule->module_dir,
            $oldModule->module_zip,
            $href,
            $oldModule->lang_code,
            $oldModule->module_id,
            $scormVersion
          );
          $messages[] = __("Cleaning up", "cluevo");
          if (file_exists($archivePath . $tmp)) {
            unlink($archivePath . $tmp);
          }
          if (file_exists($moduleDir . $tmp)) {
            cluevo_delete_directory($moduleDir . $tmp);
          }
          $messages[] = __("Module extracted", "cluevo");
        }
      }
    } else {
      if ($mime !== 'application/zip') {
        if (file_exists($archivePath)) {
          $messages[] = esc_html(sprintf(__("Backing up zip: %s", "cluevo"), $archivePath));
          @rename($archivePath, $archivePath . $tmp);
        }
        if (move_uploaded_file($file, $archivePath)) {
          if (file_exists($moduleDir)) {
            $messages[] = esc_html(sprintf(__("Backing up: %s", "cluevo"), $moduleDir));
            $backedUp = rename($moduleDir, $moduleDir . $tmp);
          }
          $handled = false;
          $result = null;
          do_action('cluevo_activate_module', [
            "module" => $archivePath,
            "mime" => $mime,
            "messages" => &$messages,
            "errors" => &$errors,
            "parentModuleId" => $oldModule->module_id,
            "lang" => $oldModule->lang_code,
            "handled" => &$handled,
            "result" => &$result
          ]);
        }
        if ($handled) {
          $messages[] = __("Cleaning up", "cluevo");
          if (file_exists($archivePath . $tmp)) {
            unlink($archivePath . $tmp);
          }
          if (file_exists($moduleDir . $tmp)) {
            cluevo_delete_directory($moduleDir . $tmp);
          }
          $messages[] = __("Module extracted", "cluevo");
        } else {
          $errors[] = __("No handler for this content type could be found.", "cluevo");
          unlink($archivePath);
        }
      }
    }
    return;
  }
  if (!empty($_FILES["module-file"]["name"])) {
    $file = sanitize_text_field($_FILES['module-file']['tmp_name']);
    $filename = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_FILENAME));
    $ext = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_EXTENSION));

    $blacklistExt = cluevo_get_blacklisted_extensions();
    $blacklistNames = cluevo_get_blacklisted_filenames();
    if (in_array($ext, $blacklistExt) || in_array($filename, $blacklistNames)) {
      $errors[] = __("This type of file is not allowed", "cluevo");
      return;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = sanitize_mime_type(finfo_file($finfo, $file));
    $type = sanitize_key(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));

    if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'))) {
      mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'), 0755, true);
    }

    $targetDirExists = true;
    if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type/")) {
      $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type/", 0755, true);
    }
    $archivePath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . strtolower($type . '/' . sanitize_file_name($_FILES["module-file"]['name']));
    $zipFile = strtolower(sanitize_file_name($_FILES["module-file"]['name']));
    $realPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $filename . '/';
    $overwrite = false;

    $lang = (!empty($_POST["language"]) && ctype_alpha($_POST["language"])) ? sanitize_text_field($_POST["language"]) : null;
    $parentModule = null;
    $parentModuleId = null;
    $tmp_pid = (!empty($_POST["parent-module-id"]) && is_numeric($_POST["parent-module-id"])) ? (int)$_POST["parent-module-id"] : null;
    if (!empty($tmp_pid)) {
      $parentModule = cluevo_get_module($tmp_pid);
      $parentModuleId = $parentModule->module_id;
    }

    if (move_uploaded_file($file, $archivePath)) {
      $handled = false;
      do_action('cluevo_activate_module', [
        "module" => $archivePath,
        "mime" => $mime,
        "messages" => &$messages,
        "errors" => &$errors,
        "parentModuleId" => $parentModuleId,
        "lang" => $lang,
        "handled" => &$handled,
        "result" => &$result
      ]);
      if (!$handled) {
        $errors[] = __("No handler for this content type could be found.", "cluevo");
        unlink($archivePath);
      }
    } else {
      $errors[] = __("Failed to move uploaded file.", "cluevo");
    }
  }
}

function cluevo_url_exists($strUrl)
{
  $headers = @get_headers($strUrl);
  foreach ($headers as $h) {
    if (!$h || strpos($h, "200")) {
      return true;
    }
  }

  return false;
}

function cluevo_handle_module_download($strUrl, &$errors, &$messages, &$handled, &$result = null)
{
  if (empty($strUrl)) {
    $errors[] = __("URL should not be empty", "cluevo");
    return;
  }

  $url = esc_url_raw($strUrl, ['http', 'https', 'ftp']);
  $path = parse_url(urldecode($url), PHP_URL_PATH);
  $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
  $validExtensions = ["zip", "mp3", "wav", "mp4", "webm"];
  $handled = false;
  if (in_array($ext, $validExtensions)) {
    $title = sanitize_text_field(pathinfo($path, PATHINFO_FILENAME));
    $filename = strtolower(pathinfo(sanitize_file_name(trim(basename($path), '/')),  PATHINFO_FILENAME)) . ".$ext";
    $tmpPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . strtolower('tmp/' . $filename);
    $res = @fopen($url, 'r');
    if ($res !== false) {
      file_put_contents($tmpPath, $res);
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime = finfo_file($finfo, $tmpPath);
      $type = sanitize_file_name(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));

      // handle updates
      if (!empty($result) && is_numeric($result)) {
        $messages[] = __("Attempting to update module", "cluevo");
        $oldModule = cluevo_get_module((int)$result);
        $moduleDir = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $oldModule->module_dir);
        $messages[] = esc_html(sprintf(__("Existing module found: %s", "cluevo"), $oldModule->module_name));
        $archivePath = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'), $oldModule->module_zip);
        $tmp = uniqid();
        if ($mime === 'application/zip' && cluevo_is_scorm_zip($tmpPath)) {
          $handled = true;
          $messages[] = __("SCORM file detected", "cluevo");

          if (!empty($oldModule->module_zip) && $archivePath !== cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') && file_exists($archivePath)) {
            $messages[] = esc_html(sprintf(__("Backing up zip: %s", "cluevo"), $archivePath));
            @rename($archivePath, $archivePath . $tmp);
          }
          if (empty($oldModule->module_zip)) {
            $filename = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_FILENAME));
            $ext = strtolower(pathinfo(sanitize_file_name($_FILES["module-file"]['name']),  PATHINFO_EXTENSION));
            $type = sanitize_file_name(strtolower(cluevo_get_module_type_name_from_mime_type($mime)));

            $blacklistExt = cluevo_get_blacklisted_extensions();
            $blacklistNames = cluevo_get_blacklisted_filenames();
            if (in_array($ext, $blacklistExt) || in_array($filename, $blacklistNames)) {
              $errors[] = __("This type of file is not allowed", "cluevo");
              return;
            }

            $archivePath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . strtolower($type . '/' . sanitize_file_name($_FILES["module-file"]['name']));
          }
          $backedUp = false;
          if (file_exists($moduleDir)) {
            $messages[] = esc_html(sprintf(__("Backing up: %s", "cluevo"), $moduleDir));
            $backedUp = rename($moduleDir, $moduleDir . $tmp);
          }
          if ($backedUp) {
            cluevo_extract_scorm_module($tmpPath, $moduleDir);
            $href = cluevo_find_module_index($moduleDir, false);
            $scormVersion = cluevo_get_scorm_version_from_manifest($moduleDir);
            $messages[] = esc_html(sprintf(__("Updating module data. Index: %s, version: %s", "cluevo"), $href, $scormVersion));
            cluevo_create_module(
              $oldModule->module_name,
              $oldModule->type_id,
              $oldModule->metadata_id,
              $oldModule->module_dir,
              $oldModule->module_zip,
              $href,
              $oldModule->lang_code,
              $oldModule->module_id,
              $scormVersion
            );
            $messages[] = __("Cleaning up", "cluevo");
            if (file_exists($archivePath . $tmp)) {
              unlink($archivePath . $tmp);
            }
            if (file_exists($moduleDir . $tmp)) {
              cluevo_delete_directory($moduleDir . $tmp);
            }
            $messages[] = __("Module extracted", "cluevo");
          }
        } else {
          if ($mime !== 'application/zip') {
            if (file_exists($archivePath)) {
              $messages[] = esc_html(sprintf(__("Backing up zip: %s", "cluevo"), $archivePath));
              @rename($archivePath, $archivePath . $tmp);
            }
            if (move_uploaded_file($tmpPath, $archivePath)) {
              if (file_exists($moduleDir)) {
                $messages[] = esc_html(sprintf(__("Backing up: %s", "cluevo"), $moduleDir));
                $backedUp = rename($moduleDir, $moduleDir . $tmp);
              }
              $handled = false;
              $result = null;
              do_action('cluevo_activate_module', [
                "module" => $archivePath,
                "mime" => $mime,
                "messages" => &$messages,
                "errors" => &$errors,
                "parentModuleId" => $oldModule->module_id,
                "lang" => $oldModule->lang_code,
                "handled" => &$handled,
                "result" => &$result
              ]);
            }
            if ($handled) {
              $messages[] = __("Cleaning up", "cluevo");
              if (file_exists($archivePath . $tmp)) {
                unlink($archivePath . $tmp);
              }
              if (file_exists($moduleDir . $tmp)) {
                cluevo_delete_directory($moduleDir . $tmp);
              }
              $messages[] = __("Module extracted", "cluevo");
            } else {
              $errors[] = __("No handler for this content type could be found.", "cluevo");
              unlink($archivePath);
            }
          }
        }
        return;
      }

      $targetDirExists = true;
      if (!file_exists(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type/")) {
        $targetDirExists = @mkdir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . "$type/", 0755, true);
      }
      $archivePath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . strtolower("$type/" . $filename);
      if ($targetDirExists && @rename($tmpPath, $archivePath)) {
        $result = [];
        $module = null;
        do_action('cluevo_activate_module', [
          "module" => $archivePath,
          "title" => $title,
          "mime" => $mime,
          "messages" => &$messages,
          "errors" => &$errors,
          "parentModuleId" => null,
          "lang" => null,
          "handled" => &$handled,
          "result" => &$result
        ]);
        if ($handled) {
          return $result;
        }
      } else {
        $errors[] = __("An error occurred while moving the file to the target directory.", "cluevo");
      }
    } else {
      $errors[] = __("File does not exist.", "cluevo");
    }
  } else {
    $result = [];
    do_action('cluevo_handle_module_url_install', [
      'url' => $url,
      "messages" => &$messages,
      "errors" => &$errors,
      "parentModuleId" => null,
      "lang" => null,
      "handled" => &$handled,
      "result" => &$result,
      "module" => &$module
    ]);
    if ($handled) {
      return $result;
    } else {
      do_action('cluevo_handle_misc_module_url_input', [
        "input" => $url,
        "messages" => &$messages,
        "errors" => &$errors,
        "handled" => &$handled,
        "result" => &$result,
        "module" => &$module
      ]);
    }
  }
  if (!$handled) {
    $errors[] = __("The module failed to install.", "cluevo");
  }
}

/**
 * Recursively creates metadata posts of a given item
 *
 * Recursively creates parent's posts if necessary
 *
 * @param mixed $item
 * @param mixed $intParentId
 * @param mixed $tree
 */
function cluevo_create_metadata_post($item, $intParentId, &$tree)
{
  $parentPostId = $intParentId;

  if (!empty($item->parent_id)) { // if the item has a parent we need to have the parent post first before we can create the item's post

    if ($item->new) {
      if (array_key_exists($item->parent_id, $tree)) {
        $parentItem = $tree[$item->parent_id];
      } else {
        $parentItem = cluevo_get_learning_structure_item($item->tree_id);
      }
    } else {
      $parentItem = cluevo_get_learning_structure_item($item->parent_id);
    }

    $parentPostId = $parentItem->metadata_id;
    $post = get_post($parentPostId);
    if (empty($post) || empty($parentPostId)) { // check if post exists or metadata id is empty
      if (!empty($post)) {
        $parentPostId = $post->ID;
      }
      if (!array_key_exists($item->parent_id, $tree)) {
        $tree[$item->parent_id] = cluevo_get_learning_structure_item($item->parent_id);
      }
      $parentPostId = cluevo_create_metadata_post($tree[$item->parent_id], $parentPostId, $tree);
    }
  }

  if (get_post($item->metadata_id) === null || empty($item->metadata_id)) {
    $id = cluevo_create_metadata_page($item, $parentPostId);
    $tree[$item->item_id]->metadata_id = $id;
  } else {
    cluevo_update_metadata_page($item, $parentPostId);
    $id = $item->metadata_id;
  }
  return $id;
}

/**
 * Returns a textbox and dropdown containing the selected modules as options
 *
 * @param mixed $name
 * @param mixed $modules
 * @param mixed $intSelected
 */
function cluevo_render_module_list($name, $modules, $intSelected = null, $boolHideButton = false)
{
  $out = "<input type=\"text\" data-target=\"name\" class=\"module-name sortable-name\" value=\"" . esc_attr($name) . "\" /> ";
  $labelVisible = 'hidden';
  $buttonVisible = 'hidden';
  $id = '';
  $name = '';
  if (!empty($intSelected)) {
    $selected = null;
    foreach ($modules as $module) {
      if ($module->module_id == $intSelected) {
        $selected = $module;
        break;
      }
    }
    $labelVisible = '';
    if (!empty($selected)) {
      $id = $selected->module_id;
      $name = $selected->module_name;
    }
  } else {
    $buttonVisible = '';
  }
  $buttonVisible = ($boolHideButton) ? 'hidden' : $buttonVisible;
  $out .= "<div class=\"module-name-label $labelVisible\" data-value=\"" . esc_attr($id) . "\" data-target=\"module-id\">
    <div class=\"dashicons dashicons-welcome-learn-more label-icon\"></div>
    <div class=\"content\">" . esc_attr($name) . "</div>
    <div class=\"dashicons dashicons-edit cluevo-edit-module label-icon-right\"></div>
    <div class=\"dashicons dashicons-dismiss remove-module label-icon-right\"></div>
  </div>";
  $out .= "<div class=\"cluevo-btn cluevo-make-module " . esc_attr($buttonVisible) . "\">" . esc_html__("Insert Module", "cluevo") . "</div>";
  $allowed = wp_kses_allowed_html("post");
  $allowed["input"] = ["type" => 1, "data-*" => 1, "class" => 1, "value" => 1];
  return wp_kses($out, $allowed);;
}

/**
 * Callback to handle creation of new course groups
 *
 * Creates the database entries and metadata pages for tree items
 *
 * @param mixed $name
 */
function cluevo_handle_create_learning_structure($name)
{
  $treeMetadataId = wp_insert_post(["post_title" => sanitize_title($name), "post_status" => "publish", 'post_type' => CLUEVO_METADATA_POST_TYPE]);
  $terms = get_terms(['taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false]);
  if (is_array($terms)) {
    foreach ($terms as $term) {
      if ($term->name == __("Course Group", "cluevo")) {
        wp_set_post_terms($treeMetadataId, [$term->term_id], CLUEVO_TAXONOMY);
        break;
      }
    }
  }
  $treeIndex = cluevo_create_learning_structure($name, $treeMetadataId);
  update_option("cluevo-selected-course-group", $treeIndex);
}

/**
 * Creates the metadata post for a module
 *
 * Returns the new post id on success
 *
 * @param string $strFilename
 *
 * @return int|WP_Error
 */
function cluevo_create_module_metadata_post($strFilename)
{
  $id = wp_insert_post(
    [
      "post_title" => sanitize_title($strFilename),
      "post_status" => "publish",
      "post_type" => CLUEVO_METADATA_POST_TYPE_SCORM_MODULE
    ]
  );
  $terms = get_terms(['taxonomy' => 'CLUEVO', 'hide_empty' => false]);
  if (is_array($terms)) {
    foreach ($terms as $term) {
      if ($term->name == __("SCORM Module", "cluevo")) {
        wp_set_post_terms($id, [$term->term_id], __("SCORM MODULE", "cluevo"));
        break;
      }
    }
  }

  return $id;
}

/**
 * Renders an item recursively
 *
 * @param mixed $item
 * @param array $modules
 * @param mixed $forceId
 */
function cluevo_render_courses($item, $modules = array(), $forceId = null)
{
  if (empty($item)) return;
  // these variables contain learn unit information. TODO: develop a system of storing and retrieving this information to make it easily extensible. Maybe create an interface or include php files
  $url = remove_query_arg(['create-metadata-page']);
  if (get_class($item) === 'CluevoItem') $item->load_settings();
  $name = (!empty($item->name)) ? $item->name : '';
  $level = (!empty($item->level)) ? $item->level : 0;
  $pointsNeeded = (!empty($item->points_required)) ? $item->points_required : 0;
  $pointsWorth = (is_numeric($item->points_worth)) ? (int)$item->points_worth : 0;
  $levelRequired = (!empty($item->level_required)) ? $item->level_required : 0;
  $dependencies = (!empty($item->dependencies)) ? $item->dependencies : ['modules' => ['normal' => [], 'blocked' => [], 'inherited' => []], 'other' => ['normal' => [], 'blocked' => [], 'inherited' => []]];
  $repeatInterval = (!empty($item->repeat_interval)) ? $item->repeat_interval : 0;
  $repeatIntervalType = (!empty($item->repeat_interval_type)) ? $item->repeat_interval_type : 'day';
  $moduleId = (!empty($item->module_id)) ? ' data-module-id="' . esc_attr($item->module_id) . '"' : '';
  $displayMode = ($item->type == "module") ? ' data-display-mode="' . esc_attr($item->display_mode) . '"' : '';
  $defaultDisplayMode = strtolower(get_option('cluevo-modules-display-mode'));
  $link = $item->get_setting("item-is-link");
  $isLink = false;
  if (!empty($link) && is_string($link)) {
    $isLink = (trim($link) !== "") ? true : false;
  }
  //$moduleId = 0;
  $metadataId = 0;
  if (!empty($item)) {
    $item->id = (empty($item->id)) ? 0 : $item->id;
    $item->id = (empty($item->item_id)) ? $item->id : $item->item_id;
    $id = 'item-' . $item->id;
    $id = (!empty($forceId)) ? $forceId : $id;
    $metadataId = $item->metadata_id;
  } else {
    $id = (!empty($forceId)) ? $forceId : 0;
  }

  $hasDependencies = false;
  foreach ($dependencies as $depType => $deps) {
    foreach ($deps as $dep) {
      if (!empty($dep)) {
        $hasDependencies = true;
        break;
      }
    }
    if ($hasDependencies) break;
  }


  $classes = [];
  $classes[] = ($item->published) ? "published" : "draft";
  if (!empty($item->module_id) && (int)$item->module_id > 0) $classes[] = "module-assigned";
  if ($hasDependencies) $classes[] = "has-dependencies";
  if ($isLink) $classes[] = "is-link";
  $classes = implode(" ", $classes);

  echo '<li id="' . esc_attr($id) . '"
    class="lms-tree-item ' . esc_attr($classes) . '"
    data-item-id="' . esc_attr($item->item_id) . '"
    data-name="' . esc_attr($item->name) . '"
    data-id="' . esc_attr($item->id) . '"
    data-module-id="' . esc_attr($item->module_id) . '"
    data-level="' . esc_attr($item->level) . '"
    data-type="' . esc_attr(CLUEVO_LEARNING_STRUCTURE_LEVELS[$item->level]) . '"
    data-dependencies=\'' . json_encode($item->dependencies) . '\'
    data-repeat-interval="' . esc_attr($item->repeat_interval) . '"
    data-repeat-interval-type="' . esc_attr($item->repeat_interval_type)  . '"
    data-metadata-id="' . esc_attr($item->metadata_id) . '"
    data-published="' . esc_attr($item->published) . '"
    data-item-is-link="' . esc_attr($isLink) . '"
    data-login-required="' . esc_attr($item->login_required) . '"' . $displayMode . '>';
?>
  <div class="handle">
    <div class="move-container">
      <div class="up"><span class="dashicons dashicons-arrow-up-alt"></span></div>
      <div class="down"><span class="dashicons dashicons-arrow-down-alt"></span></div>
    </div>
    <span class="title">
      <?php echo cluevo_render_module_list($name, $modules, (!empty($item->module) && $item->module > 0) ? $item->module : null, (empty($item->children) ? false : true)); ?>
      <span class="type fade">
        <?php
        $nextItemType = 'element';
        switch ($item->type) {
          case "course":
            echo __("Course", "cluevo");
            $nextItemType = __("Chapter", "cluevo");
            break;
          case "chapter":
            echo __("Chapter", "cluevo");
            $nextItemType = __("Module", "cluevo");
            break;
          case "module":
            echo __("Module", "cluevo");
            break;
          default:
            echo "";
        }
        ?>
      </span>
      <span class="tree-item-id fade shortcode copy-shortcode" title="<?php esc_attr_e("Copy Shortcode", "cluevo"); ?>">[<?php echo (!empty($item)) ? esc_attr($item->id) : ""; ?>]</span>
    </span>
    <div class="buttons">
      <span class="cluevo-item-is-link dashicons dashicons-admin-links"></span>
      <img class="has-dependencies-icon" alt="<?php esc_attr_e("This item has dependencies", "cluevo"); ?>" title="<?php esc_attr_e("This item has dependencies", "cluevo"); ?>" src="<?php echo plugins_url("/images/icon-dependency-neg.svg", plugin_dir_path(__FILE__)); ?>" />
      <?php do_action("cluevo_lms_item_handle_tools", $item); ?>
      <div class="publish cluevo-btn cluevo-btn-square cluevo-button-primary" title="<?php ($item->published) ? esc_attr_e("Published", "cluevo") : esc_attr_e('Draft', "cluevo"); ?>">
        <?php if ($item->published) { ?><span class="dashicons dashicons-visibility"></span>
        <?php } else { ?><span class="dashicons dashicons-hidden"></span><?php } ?>
      </div>
      <?php if ($item->level < 3) { ?>
        <div class="add cluevo-btn cluevo-btn-square cluevo-button-primary" <?php echo (!empty($item->module_id) && $item->module_id > 0) ? 'disabled="disabled"' : ''; ?> title="<?php esc_attr_e(sprintf(__("Add %s to this item", "cluevo"), $nextItemType)); ?>"><img class="plugin-logo" src="<?php echo plugins_url("/images/icon-add-child.svg", plugin_dir_path(__FILE__)); ?>" /></div>
      <?php } ?>
      <?php if (get_page($metadataId) !== null) { ?>
        <a class="metadata cluevo-btn cluevo-btn-square metadata-edit-link" title="<?php _e("Open this elements post", "cluevo"); ?>" href="<?php echo get_edit_post_link($metadataId); ?>" target="_blank"><span class="dashicons dashicons-wordpress"></span></a>
      <?php } else { ?>
        <a class="cluevo-btn cluevo-btn-square cluevo-btn-primary" title="<?php esc_attr_e("Create item page", "cluevo"); ?>" href="<?php echo add_query_arg('create-metadata-page', $item->id, $url); ?>"><span class="dashicons dashicons-wordpress"></span></a>
      <?php } ?>
      <div class="shortcode cluevo-btn cluevo-btn-square copy-shortcode" title="<?php _e("Copy Shortcode", "cluevo"); ?>">[s]</div>
      <div class="meta-toggle cluevo-btn cluevo-btn-square" title="<?php _e("Element Settings", "cluevo"); ?>"><span class="dashicons dashicons-admin-generic"></span></div>
      <div class="remove cluevo-btn cluevo-btn-square" title="<?php _e("Delete Element", "cluevo"); ?>"><span class="dashicons dashicons-trash"></span></div>
      <div class="expand cluevo-btn cluevo-btn-square <?php if ($item->level > 2) echo "disabled"; ?>"><span class="dashicons dashicons-arrow-down"></span></div>
    </div>
  </div>

  <div class="meta">

    <div class="meta-content-container">
      <h2><?php echo __("Settings", "cluevo"); ?></h2>

      <?php if ($item->type == "module" || (!empty($item->module_id) && (int)$item->module_id > 0)) { ?>
        <?php $tmpMode = (!empty($item->display_mode)) ? $item->display_mode : ""; ?>
        <?php $iframePosition = $item->get_setting('iframe-position'); ?>
        <div class="meta-container display-mode">
          <details>
            <summary class="label"><?php _e("Display Mode", "cluevo"); ?>
              <p class="help">
                <?php _e("Determines how modules are displayed.", "cluevo"); ?>
              </p>
            </summary>
            <div class="display-mode-container display-mode input-container">
              <label><?php _e("Mode", "cluevo"); ?></label>
              <select data-target="display-mode" class="setting">
                <option value="">Standard</option>
                <option value="iframe" <?php if ($tmpMode == "iframe") echo "selected"; ?>><?php esc_html_e("Display on Page (Iframe)", "cluevo"); ?></option>
                <option value="popup" <?php if ($tmpMode == "popup") echo "selected"; ?>><?php esc_html_e("Pop-Up", "cluevo"); ?></option>
                <option value="lightbox" <?php if ($tmpMode == "lightbox") echo "selected"; ?>><?php esc_html_e("Lightbox", "cluevo"); ?> <?php esc_html_e("(Recommended)", "cluevo"); ?></option>
              </select>
            </div>
            <div class="iframe-position input-container <?php if ($tmpMode === "iframe" || $defaultDisplayMode === 'iframe') echo "visible"; ?> <?php if ($defaultDisplayMode === "iframe") echo "forced"; ?>">
              <label><?php _e("Position", "cluevo"); ?></label>
              <select data-target="iframe-position" class="setting">
                <option value="start" <?php if ($iframePosition == "start") echo "selected"; ?>><?php esc_html_e('Top of the page', "cluevo"); ?></option>
                <option value="end" <?php if ($iframePosition == "end") echo "selected"; ?>><?php esc_html_e('Bottom of the page', 'cluevo'); ?></option>
              </select>
            </div>
          </details>
        </div>
      <?php } ?>

      <?php do_action('cluevo_tree_item_settings', $item); ?>

      <div class="meta-container points global">
        <details>
          <summary class="label"><?php _e("Points", "cluevo"); ?>

            <p class="help">
              <?php _e("Defines the worth in points of an item or respectively how many points a user has to have to gain access.", "cluevo"); ?>
            </p>
          </summary>

          <div class="point-wrap input-list-container inline">
            <div class="points-container points-worth input-container">
              <label><?php _e("Worth", "cluevo"); ?></label>
              <input type="number" min="0" value="<?php echo esc_attr($pointsWorth); ?>" data-target="points-worth" />
            </div>

            <div class="points-container practice-points input-container">
              <label><?php _e("Practice points", "cluevo"); ?></label>
              <input type="number" min="0" value="<?php echo esc_attr($item->practice_points); ?>" data-target="practice-points" />
            </div>

            <div class="points-container points-required input-container">
              <label><?php _e("Required", "cluevo"); ?></label>
              <input type="number" min="0" value="<?php echo esc_attr($pointsNeeded); ?>" data-target="points-required" />
            </div>
          </div>
        </details>
      </div>

      <div class="meta-container level global">
        <details>
          <summary class="label"><?php _e("Required level", "cluevo"); ?>
            <p class="help">
              <?php _e("Defines the level a user has to have reached to access an item.", "cluevo"); ?>
            </p>
          </summary>
          <div class="input-list-container inline">
            <div class="input-container">
              <label><?php echo __("Level", "cluevo"); ?></label>
              <input type="number" min="0" value="<?php echo esc_attr((int)$levelRequired); ?>" data-target="level-required" />
            </div>
          </div>
        </details>
      </div>

      <div class="meta-container dependency-container">
        <div class="label"><?php _e("Requirements", "cluevo"); ?></div>
        <p class="help">
          <?php _e("Defines the requirements users have to fulfill to access this element.", "cluevo"); ?>
        </p>
        <div class="dep-checkbox-container" data-target="dependencies">
        </div>
      </div>

      <?php if ($level == count(CLUEVO_LEARNING_STRUCTURE_LEVELS) - 1) { ?>
        <!-- <div class="meta-container repeating global">
  <div class="label"><?php _e("Module must be repeated periodically", "cluevo"); ?></div>
    <p class="help">
      <?php _e("Defines the interval in which a module has to be repeated.", "cluevo"); ?>
    </p>
    <div class="meta-input-fields-container">
      <input type="number" min="0" value="<?php echo esc_attr($repeatInterval); ?>" data-target="repeat-interval"/>
      <select class="repeat-interval-type" data-target="repeat-interval-type">
        <?php foreach (CLUEVO_REPEAT_INTERVAL_TYPES as $key => $value) { ?>
        <option value="<?php echo esc_attr($key); ?>"<?php if ($repeatIntervalType === $key) echo ' selected="selected"'; ?>><?php echo esc_attr($value); ?></option>
        <?php } ?>
      </select>
    </div>
  </div> -->
      <?php } ?>

    </div>
  </div>

  <?php
  // render children of the current item
  $nextLevel = $level + 1;
  echo "<ol id=\"level-$nextLevel\" data-level=\"$nextLevel\">\n";
  if (!empty($item->children)) {
    foreach ($item->children as $key => $child) {
      cluevo_render_courses($child, $modules);
    }
  }
  echo "</ol>\n";
  echo "\t</li>\n";
}

/**
 * Outputs the module ui and handles deletion of modules
 *
 */
function cluevo_render_module_ui($errors = [], $messages = [])
{
  $pending = cluevo_find_pending_modules();
  $tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : CLUEVO_ADMIN_TAB_LMS_STRUCTURE;

  $plugin_dir = plugin_dir_path(__DIR__);
  $scorm_dir = $plugin_dir . "scorm-modules";
  $archive_dir = $plugin_dir . "scorm-modules-archive/";

  if (file_exists($scorm_dir) || file_exists($archive_dir)) {
    $messages[] = __("There are modules inside the old module Directory. Click here to start the migration: ", "cluevo") . " <a href=\"" . add_query_arg("migrate", "true") . "\">[" . __("migrate modules", "cluevo") . "]</a>";
  }

  // handle module deletion
  $deleted = false;
  $del_module = (!empty($_GET["delete-module"]) && is_numeric($_GET["delete-module"])) ? (int)$_GET["delete-module"] : null;
  if (!empty($del_module)) {
    // check if modules exists in database, delete module and archive zip and remove from database
    $modules = cluevo_get_modules();
    $moduleId = $del_module;
    $module = cluevo_get_module($moduleId);
    if (!empty($module)) {
      $delModule = $module->module_dir;
      $delPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $delModule;
      $delZip = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $module->module_zip;
      if (!empty($module->module_dir) && file_exists($delPath) && !empty($delPath)) {
        if (!empty($delModule)) {
          cluevo_delete_directory($delPath);
        }
        if (!empty($module->module_zip) && file_exists($delZip) && !empty($delZip))
          unlink($delZip);
        cluevo_remove_module($moduleId);
        $deleted = true;
      } else {
        cluevo_remove_module($moduleId);
        $deleted = true;
      }
    }
    do_action("cluevo_module_deleted", $moduleId);
  }

  // create metadata page
  $pageCreated = false;
  $create_page = (!empty($_GET["create-metadata-page"]) && is_numeric($_GET["create-metadata-page"])) ? (int)$_GET["create-metadata-page"] : null;
  if (!empty($create_page)) {
    $moduleId = $create_page;
    if (!cluevo_get_module_metadata_page($moduleId)) { // create metadata page for the uploaded module if the page doesn't yet exist
      $module = cluevo_get_module($moduleId);
      if (!empty($module)) {
        $id = wp_insert_post(
          [
            "post_title" => sanitize_title($module->module_name),
            "post_status" => "publish",
            "post_type" => CLUEVO_METADATA_POST_TYPE_SCORM_MODULE
          ]
        );
        $terms = get_terms(['taxonomy' => CLUEVO_TAXONOMY, 'hide_empty' => false]);
        if (is_array($terms)) {
          foreach ($terms as $term) {
            if ($term->name == __("SCORM Module", "cluevo")) {
              wp_set_post_terms($id, [$term->term_id], __('SCORM Module', "cluevo"));
              break;
            }
          }
        }
        cluevo_update_module_metadata_id($moduleId, $id);
        $pageCreated = true;
      }
    }
  }

  $url = remove_query_arg(['create-metadata-page', 'delete-module']);
  $modules = cluevo_get_modules();
  $page = CLUEVO_ADMIN_PAGE_LMS;

  $moduleTypes = [];
  do_action('cluevo_register_module_types', ["types" => &$moduleTypes]);
  $execTime = ini_get('max_execution_time');

  cluevo_display_notice(
    __("Info", "cluevo"),
    sprintf(__("Depending on the size of your modules installation can take quite a bit of time. On most hosts the time available for scripts is limited, on your server it seems to be limited to %s seconds. The bigger your modules are the longer the installation process takes and the bigger your max. execution time needs to be. We will attempt to increase this value during module instalaltion but not all hosts support this. If larger modules fail to install you should ask your hosting provider to increase your max. execution time.", "cluevo"), $execTime),
    "warning",
    "cluevo-max-exec-time-info"
  );
  if (!class_exists('ZipArchive')) {
    cluevo_display_notice(
      __("Missing PHP Extension", "cluevo"),
      __("CLUEVO LMS requires the ZipArchive class from the PHP zip extension. You won't be able to upload zipped modules until zip support is enabled. ZipArchive is required because CLUEVO LMS does some checks before extracting modules to protect you from malicious zip files. Please contact your hosting provider to enable this extension.", "cluevo"),
      "warning"
    );
  }
  ?>
  <?php if (!empty($moduleTypes) && is_array($moduleTypes)) { ?>
    <div class="cluevo-add-module-overlay" id="cluevo-add-module-overlay" data-max-upload-size="<?php echo esc_attr(wp_max_upload_size()); ?>">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h3><?php esc_html_e('Add Module', 'cluevo'); ?></h3>
              <button class="close"><span class="dashicons dashicons-no-alt"></span></button>
            </div>
            <div class="modal-body">
              <div class="cluevo-add-module-content-container module-type cluevo-update-module-content-container">
                <div class="cluevo-notice cluevo-notice-warning">
                  <p><?php esc_html_e("Please make sure the module update you are uploading is of the same type as the existing module. Changing SCORM versions is fine but going from e.g. SCORM to PDF will not work and will probably have unforseen consequences.", "cluevo"); ?>
                </div>
                <form method="post" enctype="multipart/form-data" class="cluevo-module-form" action="<?php echo esc_url(admin_url("admin.php?page=$page&tab=$tab"), ['http', 'https']); ?>">
                  <div class="input-switch">
                    <input type="text" name="module-dl-url" placeholder="https://" />
                    <label class="cluevo cluevo-module-install-type-file">
                      <div class="cluevo-btn"><?php esc_html_e("Browse...", "cluevo"); ?></div>
                      <input type="file" name="module-file" value="" />
                    </label>
                  </div>
                  <input type="submit" class="cluevo-btn auto cluevo-btn-primary disabled" value="<?php echo __("Install Module", "cluevo"); ?>" disabled />
                </form>
                <p><?php _e("Max. Filesize: ", "cluevo") . esc_html_e(cluevo_human_filesize(wp_max_upload_size())); ?></p>
                <div class="cluevo-notice cluevo-notice-error cluevo-filesize hidden">
                  <p><?php esc_html_e(sprintf(__("The file you are trying to upload is too big. The maximum upload size is %s", "cluevo"), cluevo_human_filesize(wp_max_upload_size()))); ?>
                </div>
              </div>
              <div class="upload-progress">
                <h2 class="progress-text"><?php esc_html_e("The module is being uploaded, one moment please...", "cluevo"); ?></h2>
                <div class="cluevo-progress-container">
                  <span class="cluevo-progress" data-value="" data-max="100"></span>
                </div>
                <div class="result-container"></div>
                <div class="cluevo-btn continue"><?php esc_html_e("Continue", "cluevo"); ?></div>
                <div class="cluevo-btn force"><?php esc_html_e("Try to install anyway", "cluevo"); ?></div>
              </div>
              <div class="cluevo-add-module-content-container">
                <div class="module-type-selection">
                  <div class="add-module-text"><?php esc_html_e('You can add new modules to your LMS here.', 'cluevo'); ?></div>
                  <h2><?php esc_html_e('The following module types are currently available', 'cluevo'); ?></h2>
                  <div class="module-list">
                    <?php foreach ($moduleTypes as $key => $type) { ?>
                      <div class="module-type <?php if (!empty($type["alt-icon-class"])) {
                                                echo esc_attr($type["alt-icon-class"]);
                                              } ?>" data-module-index="<?php echo esc_attr($key); ?>">
                        <?php if (!empty($type['icon'])) { ?><div class="module-icon"><img src="<?php echo esc_attr($type['icon']); ?>" /></div><?php } ?>
                        <?php if (!empty($type["name"])) { ?>
                          <div class="module-type-name"><?php echo esc_html($type['name']); ?></div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="module-description-container">
                  <div class="module-type hint">
                    <p><?php esc_html_e('Select a module type to display further information.', 'cluevo'); ?></p>
                  </div>
                  <?php foreach ($moduleTypes as $key => $type) { ?>
                    <div class="module-type <?php if (!empty($type["alt-icon-class"])) {
                                              echo esc_attr($type["alt-icon-class"]);
                                            } ?>" data-module-index="<?php echo esc_attr($key); ?>" data-module-type="<?php echo esc_attr($type['key']); ?>">
                      <div class="description-container">
                        <?php if (!empty($type['icon'])) { ?><div class="module-icon"><img src="<?php echo esc_attr($type['icon']); ?>" /></div><?php } ?>
                        <div>
                          <h3><?php echo esc_html($type['name']); ?></h3>
                          <p><?php echo esc_html($type['description']); ?></p>
                        </div>
                      </div>
                      <?php if (!empty($type["field"])) { ?>
                        <form method="post" enctype="multipart/form-data" class="cluevo-module-form <?php if (!empty($type['form-class'])) echo esc_attr($type["form-class"]); ?>" action="<?php echo esc_url(admin_url("admin.php?page=$page&tab=$tab"), ['http', 'https']); ?>" data-type="<?php echo esc_attr($type["name"]); ?>">
                          <?php if (!empty($type["field"]) && $type["field"] == "text") { ?>
                            <input type="text" name="module-dl-url" placeholder="<?php echo esc_attr($type['field-placeholder']); ?>" />
                          <?php } ?>
                          <?php if (!empty($type["field"]) && $type["field"] == "file") { ?>
                            <input type="file" name="module-file" placeholder="<?php echo esc_attr($type['field-placeholder']); ?>" />
                          <?php } ?>
                          <?php if (!empty($type["field"]) && $type["field"] == "mixed") { ?>
                            <div class="input-switch">
                              <input type="text" name="module-dl-url" placeholder="https://" />
                              <label class="cluevo cluevo-module-install-type-file">
                                <div class="button"><?php echo esc_html($type["button-label"]); ?></div>
                                <input type="file" name="module-file" value="" <?php if (!empty($type['filter'])) echo 'accept="' . $type['filter'] . '"'; ?> />
                              </label>
                            </div>
                          <?php } ?>
                          <?php if (!empty($type["field"]) && $type["field"] == "textarea") { ?>
                            <textarea name="module-dl-url" placeholder="<?php echo esc_attr($type['field-placeholder']); ?>"></textarea>
                          <?php } ?>
                          <input type="submit" class="button auto button-primary disabled" value="<?php echo __("Install Module", "cluevo"); ?>" disabled />
                        </form>
                        <?php if (!empty($type["field"]) && ($type["field"] == "file" || $type["field"] == 'mixed')) { ?>
                          <p><?php _e("Max. Filesize: ", "cluevo") . esc_html_e(cluevo_human_filesize(wp_max_upload_size())); ?></p>
                          <?php cluevo_display_notice(__('Attention', "cluevo"), __("CLUEVO LMS has no influence on the max. filesize. This limit has to be adjusted server side either by you or your hosting provider", "cluevo"), 'warning', 'module-upload-size-warning'); ?>
                          <div class="cluevo-notice cluevo-notice-error cluevo-filesize hidden">
                            <p><?php esc_html_e(sprintf(__("The file you are trying to upload is too big. The maximum upload size is %s", "cluevo"), cluevo_human_filesize(wp_max_upload_size()))); ?>
                          </div>
                        <?php } ?>
                      <?php } elseif (!empty($type["alt-content"])) { ?>
                        <div class="cluevo-add-module-alt-content">
                          <?php echo wp_kses(
                            $type["alt-content"],
                            [
                              'a' => [
                                "href" => true,
                                "title" => true,
                                "class" => true
                              ],
                              'p' => [
                                "class" => true
                              ],
                              'span' => [
                                "class" => true
                              ],
                              'div' => [
                                "class" => true
                              ],
                              'details' => [
                                "class" => true
                              ],
                              'summary' => [
                                "class" => true
                              ],
                              'ul' => [
                                "class" => true
                              ],
                              'ol' => [
                                "class" => true
                              ],
                              'li' => [
                                "class" => true
                              ],
                              'img' => [
                                'src' => true,
                                'alt' => true,
                                'class' => true,
                                'width' => true,
                                'height' => true
                              ],
                              'button' => [
                                "type" => true
                              ]
                            ]
                          ); ?>
                        </div>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="button select-type"><?php esc_html_e('Select another module type', 'cluevo'); ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php if ($deleted) cluevo_display_notice("Hinweis", __("Module deleted.", "cluevo")); ?>
  <form method="post" enctype="multipart/form-data" id="add-module-form" class="cluevo-module-form" action="<?php echo esc_url(admin_url("admin.php?page=$page&tab=$tab"), ['http', 'https']); ?>">
    <div class="button button-primary add-module"><?php _e('Add Module', 'cluevo'); ?></div>
    <input type="hidden" name="page" value="<?php echo esc_attr($page); ?>" />
    <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>" />
  </form>
  <?php if (!empty($modules)) { ?>
    <h2><?php esc_html_e("Modules", "cluevo"); ?></h2>
    <?php if ($pageCreated) echo "<p>" . esc_html_e("Module metadata post created.", "cluevo") . "</p>"; ?>
    <div id="module-lang-overlay" class="module-lang-overlay" data-module-id="">
      <div class="module-lang-select-container">
        <div class="module-lang-select" id="module-lang-select">
          <h3><?php echo __("Select Language", "cluevo"); ?></h3>
          <ul>
            <?php
            $langs = cluevo_get_languages();
            foreach ($langs as $lang) {
              echo "<li><label><input type=\"radio\" name=\"module-lang\" value=\"$lang->lang_code\" class=\"module-lang-radio\"> $lang->lang_name</label></li>";
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
    <?php
    if (!empty($errors)) {
      foreach ($errors as $err) {
        cluevo_display_notice(__("Error", "cluevo"), $err, 'error');
      }
    }
    if (!empty($messages)) {
      foreach ($messages as $msg) {
        cluevo_display_notice(__("Notice", "cluevo"), $msg);
      }
    }
    ?>
    <table class="cluevo-scorm-modules wp-list-table widefat striped">
      <thead>
        <tr>
          <th class="check-column module-id"></th>
          <th class="module-name left"><?php esc_html_e("Module", "cluevo"); ?></th>
          <th class="module-type left"><?php esc_html_e("Type", "cluevo"); ?></th>
          <th class="module-rating left"><?php esc_html_e("User Ratings", "cluevo"); ?></th>
        </tr>
      </thead>
      <?php
      $tmpId = null;
      $border = false;
      ?>
      <tbody>
        <?php foreach ($modules as $key => $m) { ?>
          <?php
          if ($tmpId !== $m->module_id && $tmpId !== null) {
            $border = true;
          }
          $tmpId = $m->module_id;
          $border = false;
          ?>
          <?php $link = get_edit_post_link($m->metadata_id, "module"); ?>
          <tr <?php if ($border) echo 'class="bordered" '; ?> data-module-id="<?php echo esc_attr($m->module_id); ?>">
            <td class="cluevo-nowrap"><?php echo esc_html($m->module_id); ?></td>
            <td class="title left column-title has-row-actions column-primary" data-id="<?php echo esc_attr($m->module_id); ?>">
              <div class="cluevo-module-name">
                <?php echo esc_html($m->module_name); ?>
              </div>
              <div class="cluevo-module-tags">
                <?php if (!empty($m->tags)) {
                  echo esc_html(implode(', ', $m->tags));
                } ?>
              </div>
              <div class="row-actions">
                <span class="edit-name"><a class="edit-module-name" title="<?php esc_attr_e("Change Name", "cluevo"); ?>" href="#" data-id="<?php echo esc_attr($m->module_id); ?>"><?php esc_html_e("Rename Module", "cluevo"); ?></a></span> |
                <span class="tags"><a class="cluevo-edit-module-tags" href="#" data-id="<?php echo esc_attr($m->module_id); ?>"><?php esc_html_e("Edit Tags", "cluevo"); ?></a></span> |
                <?php if (!empty($link)) { ?>
                  <span class="edit-module-page"><a class="" href="<?php echo esc_url($link); ?>" target="_blank"><?php esc_html_e("Edit Module Page", "cluevo"); ?></a></span> |
                <?php } else { ?>
                  <span class="create-module-page"><a class="" href="<?php echo add_query_arg('create-metadata-page', $m->module_id, $url); ?>"><?php esc_html_e("Create Module Page", "cluevo"); ?></a></span> |
                <?php } ?>
                <span class="delete"><a class="del-module" href="<?php echo add_query_arg('delete-module', $m->module_id, $url); ?>"><?php esc_html_e("Delete Module", "cluevo"); ?></a></span> |
                <span class="update"><a class="update-module" href="#"><?php esc_html_e("Update Module", "cluevo"); ?></a></span> |
                <?php if (strpos($m->type_name, 'scorm') !== false) { ?>
                  <span class="scorm-parameters"><a class="" href="<?php echo admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_REPORTS . "&tab=" . CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS . "&module=" . $m->module_id); ?>"><?php esc_html_e("Browse SCORM Parameters", "cluevo"); ?></a></span> |
                <?php } ?>
                <span class="reports"><a class="" href="<?php echo admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_REPORTS . "&tab=" . CLUEVO_ADMIN_TAB_REPORTS_PROGRESS . "&module=" . $m->module_id); ?>"><?php esc_html_e("Browse Reports", "cluevo"); ?></a></span> |
                <span class="dl"><a class="" title="" href="<?php if (!empty($m->module_zip)) {
                                              echo admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_LMS . "&tab=" . CLUEVO_ADMIN_TAB_LMS_MODULES . "&dl=" . $m->module_id);
                                            } else {
                                              echo admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_LMS . "&tab=" . CLUEVO_ADMIN_TAB_LMS_MODULES . "&zip=" . $m->module_id);
                                            } ?>"><?php (!empty($m->module_zip)) ? esc_attr_e("Download Module", "cluevo") : esc_attr_e("Archive Module", "cluevo"); ?></a></span>
                <?php if (!empty($m->module_zip)) { ?>
                  | <span class="delete"><a class="" href="<?php echo add_query_arg('del-zip', $m->module_id); ?>">
                    <?php esc_attr_e("Delete ZIP", "cluevo"); ?>
                  </a></span>
                <?php } ?>
              </div>
            </td>
            <td class="type left "><?php echo (!empty($m->type_name)) ? esc_html(apply_filters('cluevo_output_module_type', $m)) : esc_html__('Unknown', "cluevo"); ?></td>
            <td class="rating"><?php echo (!empty($m->rating_avg)) ? apply_filters('cluevo_output_module_rating', $m) : cluevo_output_empty_admin_module_rating(); ?></td>
          </tr>
          <?php $border = false; ?>
        <?php } ?>
      </tbody>
    </table>
    <?php if (!empty($pending)) {
      $table = '<p>' . esc_html__('Pending modules found. You can add these modules by clicking the install button of any module you wish to add.', 'cluevo') . '</p>';
      $table .= '<table class="wp-list-table striped widefat">';
      $table .= '<thead>';
      $table .= '<tr><th class="left">' . esc_html('Module', 'cluevo') . '</th><th></th></tr>';
      $table .= '</thead>';
      $table .= '<tbody>';
      foreach ($pending as $m) {
        $table .= "<tr><td class=\"left\">" . esc_html($m["module"]) . "</td>";
        $table .= '<td><div class="cluevo-buttons">';
        $table .= '<span class="dashicons dashicons-trash cluevo-pending-module-delete" data-module="' . esc_attr($m["module"]) . '"></span>';
        if ($m["installable"]) {
          $table .= '<span class="dashicons dashicons-plus cluevo-pending-module" data-module="' . esc_attr($m["module"]) . '"></span>';
        } else {
          $table .= esc_html__('No install handler found', 'cluevo');
        }
        $table .= '</tr>';
        $table .= '</div></td>';
      }
      $table .= '</tbody>';
      $table .= '</table>';
      cluevo_display_notice_html(__('Information', 'cluevo'), $table, 'info');
    }
    ?>
    </div>
  <?php
  } else {
    cluevo_display_notice_html(
      __("Notice", "cluevo"),
      __("No modules have been added yet.", "cluevo"),
      "info"
    );
  }
}

function cluevo_render_lms_structure_tab($tab)
{
  $tabClass = ($tab == CLUEVO_ADMIN_TAB_LMS_STRUCTURE) ? 'nav-tab-active' : '';
  echo '<a href="' . admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_LMS . "&tab=" . CLUEVO_ADMIN_TAB_LMS_STRUCTURE) . "\" class=\"nav-tab $tabClass\">" . esc_html__("Learning tree", "cluevo") . "</a>";
}

function cluevo_render_lms_module_ui_tab($tab)
{
  $tabClass = ($tab == CLUEVO_ADMIN_TAB_LMS_MODULES) ? 'nav-tab-active' : '';
  echo "<a href=\"" . admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_LMS . "&tab=" . CLUEVO_ADMIN_TAB_LMS_MODULES) . "\" class=\"nav-tab $tabClass\">" .  esc_html__("Modules", "cluevo") . "</a>";
}

function cluevo_render_lms_page()
{
  $active_tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : CLUEVO_ADMIN_TAB_LMS_STRUCTURE;
  do_action('cluevo_init_admin_page');
  ?>
  <div class="wrap cluevo-admin-page-container">
    <h1 class="cluevo-admin-page-title-container">
      <div><?php esc_html_e("Learning Management", "cluevo"); ?></div>
      <img class="plugin-logo" src="<?php echo plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)); ?>" />
    </h1>
    <div class="cluevo-admin-page-content-container">
      <h2 class="nav-tab-wrapper cluevo"><?php do_action('cluevo_render_lms_page_tabs', $active_tab); ?></h2>
      <?php
      switch ($active_tab) {
        case CLUEVO_ADMIN_TAB_LMS_STRUCTURE:
          do_action('cluevo_enqueue_lms_structure_js');
          do_action('cluevo_render_learning_structure_ui');
          break;
        case CLUEVO_ADMIN_TAB_LMS_MODULES:
          $errors = [];
          $messages = [];
          if (!empty($errors)) {
            foreach ($errors as $err) {
              cluevo_display_notice(__("Error", "cluevo"), $err, 'error', true);
            }
          }
          if (!empty($messages)) {
            foreach ($messages as $msg) {
              cluevo_display_notice(__("Notice", "cluevo"), $msg);
            }
          }
          do_action('cluevo_enqueue_lms_modules_ui_js');
          do_action('cluevo_render_lms_modules_ui');
          break;
        default:
          do_action('cluevo_enqueue_lms_structure_js');
          do_action('cluevo_render_learning_structure_ui');
          break;
      }
      ?>
    </div>
  </div>
  <?php
}

function cluevo_enqueue_lms_structure_js()
{

  // development version
  //wp_register_script(
  //"vue-js",
  //"https://cdn.jsdelivr.net/npm/vue/dist/vue.js",
  //"",
  //"",
  //true
  //);

  // production version
  wp_register_script(
    "vue-js",
    plugins_url("/js/vue.min.js", plugin_dir_path(__FILE__)),
    "",
    CLUEVO_VERSION,
    true
  );

  wp_enqueue_script('vue-js');

  wp_register_script('nested-sortable-js', plugins_url('/js/jquery.mjs.nestedSortable.js', plugin_dir_path(__FILE__)), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-selectmenu'), CLUEVO_VERSION, false);  // provides drag'n'drop tree
  wp_register_script('lodash-js', plugins_url('/js/lodash.min.js', plugin_dir_path(__FILE__)), null, false, false);  // utilities
  wp_register_script(
    'scorm-plugin-js',
    plugins_url('/js/module-nav.js', plugin_dir_path(__FILE__)),
    array('nested-sortable-js', 'lodash-js', 'vue-js'),
    CLUEVO_VERSION,
    false
  );  // tree management
  wp_add_inline_script('lodash-js', 'window.lodash = _.noConflict();', 'after'); // gutenberg compatibility
  wp_localize_script('scorm-plugin-js', 'cluevoWpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));  // needed for ajax requests
  wp_enqueue_script('nested-sortable-js');
  wp_enqueue_script('scorm-plugin-js');

  wp_localize_script(
    'scorm-plugin-js',
    'strings',
    array(
      'new_course' => __('New Course', "cluevo"),
      'new_chapter' => __('New Chapter', "cluevo"),
      'new_module' => __('New Module', "cluevo"),
      'course' => __('Course', "cluevo"),
      'chapter' => __('Chapter', "cluevo"),
      'module' => __('Module', "cluevo"),
      'without_module' => __('Without Module', "cluevo"),
      'delete_item' => __('Really delete this element?', "cluevo"),
      'shortcode_copied' => __('Shortcode copied!', "cluevo"),
      'msg_install_demos' => __("Install demo course and modules? The modules will be downloaded from our homepage and installed to your LMS.", "cluevo"),
      'published' => __("Published", "cluevo"),
      'draft' => __("Draft", "cluevo"),
      'tree_rebuild_warning' => __("It looks like your learning tree needs to be rebuilt. If you save now you will overwrite your current learning tree with this empty one.\nAre you sure you want to save?", "cluevo"),
      'course_group_name_cant_be_empty' => __("The name of the course group must not be empty.", "cluevo"),
      'filter_placeholder' => __("'Search Query' or '!Search Query' / '-Seach Query'", "cluevo"),
      'results' => __("Results: ", "cluevo"),
      'tags' => __("Tags", "cluevo"),
      'tag' => __("Tag", "cluevo"),
      'with_tag' => __("With Tag", "cluevo"),
      'without_tag' => __("Without Tag", "cluevo"),
      'get_cert_extension' => __("Get the Certificates Extension to issue certificates", "cluevo"),
    )
  );

  wp_register_script(
    'cluevo-module-selector',
    plugins_url('/js/module-selector.admin.js', plugin_dir_path(__FILE__)),
    ['scorm-plugin-js'],
    CLUEVO_VERSION,
    true
  );
  wp_localize_script(
    'cluevo-module-selector',
    'cluevoApiSettings',
    array(
      'root' => esc_url_raw(rest_url()),
      'nonce' => wp_create_nonce('wp_rest')
    )
  );
  wp_localize_script(
    'cluevo-module-selector',
    'lang_strings',
    array(
      "insert_module" => __("Insert Module", "cluevo"),
      "label_search" => __("Search", "cluevo"),
      "placeholder_modulename" => __("Search term", "cluevo"),
      "module_search_result_count" => __("Modules", "cluevo"),
      "filter_tile_all" => __("All", "cluevo")
    )
  );
  wp_enqueue_script('cluevo-module-selector');
}

function cluevo_enqueue_lms_modules_ui_js()
{
  wp_register_script('cluevo-admin-module-page', plugins_url('/js/module-admin-page.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, true);
  wp_enqueue_script("cluevo-admin-module-page");
  wp_localize_script(
    'cluevo-admin-module-page',
    'moduleApiSettings',
    array(
      'root' => esc_url_raw(rest_url()),
      'nonce' => wp_create_nonce('wp_rest')
    )
  );
  wp_localize_script(
    'cluevo-admin-module-page',
    'strings',
    array(
      'confirm_module_delete' => __("Really delete this module? This action can't be undone.", "cluevo"),
      'toggle_install_type_file' => __('Install module from URL', "cluevo"),
      'toggle_install_type_url' => __('Upload module file', "cluevo"),
      'msg_install_demos' => __("Install demo course and modules. The modules will be downloaded from our homepage and installed to your LMS", "cluevo"),
      'rename_module_prompt' => __("New Name", "cluevo"),
      'rename_module_error' => __("The module could not be renamed. A module with the same name may already exist or the module is no longer available.", "cluevo"),
      "upload_success" => __("The module has been uploaded. One moment please while it is being installed.", "cluevo"),
      "module_upload_finished" => __("Installation completed.", "cluevo"),
      "refresh_to_enable" => __("Module uploaded. Refresh the page to enable the tools.", "cluevo"),
      "module_upload_failed" => __("Installation failed.", "cluevo"),
      "upload_error" => __("Upload failed.", "cluevo"),
      "pending_install_error" => __("Installation failed.", "cluevo"),
      "pending_delete_error" => __("Removal failed.", "cluevo"),
      "update_module" => __("Update Module", "cluevo"),
      "update_module_description" => __("Please select a module or enter a URL", "cluevo")
    )
  );
  wp_localize_script('cluevo-admin-module-page', 'cluevoWpApiSettings', [
    'root' => esc_url_raw(rest_url()),
    'nonce' => wp_create_nonce('wp_rest'),
    'pendingModuleNonce' => wp_create_nonce('##cluevo-install-pending-module'),
    'deletePendingModuleNonce' => wp_create_nonce('##cluevo-delete-pending-module')
  ]);  // needed for ajax requests
}

function cluevo_update_module_name($intModuleId, $strNewName)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $result = $wpdb->query(
    $wpdb->prepare("UPDATE $table SET module_name = %s WHERE module_id = %d", [sanitize_text_field($strNewName), $intModuleId])
  );
  return ($result !== false);
}

function cluevo_update_module_tags($intModuleId, $mixedTags = [])
{
  $tags = cluevo_create_tag_string($mixedTags);
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $result = $wpdb->query(
    $wpdb->prepare("UPDATE $table SET tags = %s WHERE module_id = %d", [sanitize_text_field($tags), $intModuleId])
  );
  return ($result !== false);
}

function cluevo_search_modules($strName)
{
  global $wpdb;
  $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
  $results = $wpdb->get_results(
    $wpdb->prepare("SELECT module_id, module_name FROM {$table} WHERE module_name LIKE CONCAT('%', %s, '%')", [$strName])
  );
  return $results;
}

function cluevo_output_empty_admin_module_rating()
{
  $out = '<div class="cluevo-module-rating-container cluevo-unrated">';
  $out .= '<div class="cluevo-module-stars">';
  for ($i = 1; $i < 6; $i++) {
    $out .= '<div class="cluevo-module-rating"></div>';
  }
  $out .= '</div>';
  $out .= '</div>';
  return $out;
}

function cluevo_output_admin_module_rating($module)
{
  if (empty($module)) return '';
  if (empty($module->rating_avg)) return '';
  if (empty($module->rating_avg["value"])) return '';
  $rating = $module->rating_avg["value"];
  $out = '<a href="' . add_query_arg('module_id', $module->module_id, admin_url("admin.php?page=cluevo-module-ratings")) . '" class="cluevo-module-rating-container">';
  $out .= '<div class="cluevo-module-stars">';
  for ($i = 1; $i < 6; $i++) {
    $out .= '<div class="cluevo-module-rating';
    $out .= ($rating >= $i) ? ' filled' : '';
    $out .= '"></div>';
  }
  $out .= '</div>';
  $out .= '<div class="cluevo-rating-stats">';
  $out .= '<div class="cluevo-rating-count">' . $module->rating_avg["count"] . ' ' . esc_html__("Ratings", "cluevo") . '</div>';
  $out .= '<div class="cluevo-rating-value">' . number_format($module->rating_avg["value"], 2) . '</div>';
  $out .= '</div>';
  $out .= '</a>';
  return $out;
}

function cluevo_find_pending_modules()
{
  $baseDir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH');
  $baseIterator = new DirectoryIterator($baseDir);
  if (!is_dir($baseDir)) return [];
  $modules = cluevo_get_modules();
  $existing = array_map(function ($module) use ($baseDir) {
    return cluevo_path_join($baseDir, $module->module_dir);
  }, $modules);
  $pending = [];
  foreach ($baseIterator as $type) {
    if ($type->isDot()) continue;
    $typePath = cluevo_path_join($baseDir, $type->getFilename());
    if (!is_dir($typePath)) continue;
    $typeIterator = new DirectoryIterator($typePath);
    foreach ($typeIterator as $module) {
      if ($module->isDot()) continue;
      if (!$module->isDir()) continue;
      $modulePath = cluevo_path_join($typePath, $module->getFilename());
      if (!in_array($modulePath, $existing)) {
        $pending[] = [
          "module" => cluevo_path_join($type->getFilename(), $module->getFilename()),
          "installable" => has_action("cluevo_install_pending_module_{$type->getFilename()}") > 0,
        ];
      }
    }
  }
  return $pending;
}

function cluevo_ajax_delete_pending_module()
{
  $nonce_name = isset($_POST["cluevo-pending-module-nonce"]) ? sanitize_text_field($_POST["cluevo-pending-module-nonce"]) : "";
  $nonce_action = "##cluevo-delete-pending-module";

  if (!wp_verify_nonce($nonce_name, $nonce_action)) return;
  if (!current_user_can("administrator")) return;

  $moduleInput = sanitize_text_field($_POST["cluevo-pending-module"]);
  $parts = explode('/', $moduleInput);
  if (empty($parts)) wp_send_json_error(false);
  $modulePathParts = array_map(function ($el) {
    return sanitize_file_name($el);
  }, $parts);
  if (empty($modulePathParts)) wp_send_json_error(false);
  $module = implode('/', $modulePathParts);

  $pending = cluevo_find_pending_modules();
  $list = array_map(function ($item) {
    return $item["module"];
  }, $pending);
  if (!in_array($module, $list)) return;

  $path = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), $module);
  if (is_dir($path) && $path != cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') && stripos($path, cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH')) === 0) {
    cluevo_delete_directory($path);
    wp_send_json_success(true);
  } else {
    wp_send_json_error(false);
  }
}

function cluevo_ajax_install_pending_module()
{
  $nonce_name = isset($_POST["cluevo-pending-module-nonce"]) ? sanitize_text_field($_POST["cluevo-pending-module-nonce"]) : "";
  $nonce_action = "##cluevo-install-pending-module";

  if (!wp_verify_nonce($nonce_name, $nonce_action)) return;
  if (!current_user_can("administrator")) return;

  $moduleInput = sanitize_text_field($_POST["cluevo-pending-module"]);
  $parts = explode('/', $moduleInput);
  $parts = array_values(array_filter($parts, function ($value) {
    return !empty($value);
  }));
  if (empty($parts)) wp_send_json_error(false);
  $modulePathParts = array_map(function ($el) {
    return sanitize_key($el);
  }, $parts);
  if (empty($modulePathParts)) wp_send_json_error(false);
  $module = cluevo_path_join($modulePathParts);

  $pending = cluevo_find_pending_modules();
  $list = array_map(function ($item) {
    return $item["module"];
  }, $pending);
  if (!in_array($module, $list)) return;
  $module = trim($module, '/');

  $parts = explode('/', $module);
  if (count($parts) !== 2) return;
  $type = sanitize_key($parts[0]);

  do_action("cluevo_install_pending_module_{$type}", $parts[1]);
}

function cluevo_install_pending_scorm_module($strName)
{
  $realPath = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . cluevo_path_join("scorm2004", $strName) . "/";
  $moduleType = CLUEVO_SCORM_MODULE_TYPE_ID; // module_type = scorm
  $href = cluevo_find_module_index($realPath, false);
  $scormVersion = cluevo_get_scorm_version_from_manifest($realPath);
  $id = cluevo_create_module_metadata_post($strName);
  $result = cluevo_create_module($strName, $moduleType, $id, cluevo_path_join('scorm2004', $strName), null, $href, null, 0, $scormVersion);
  wp_die($result);
}

function cluevo_install_pending_audio_module($strName)
{
  $validExtensions = ["mp3", "wav", "m4a"];
  $path = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), 'audio', $strName);
  if (!file_exists($path)) wp_die(false);
  $dirIterator = new DirectoryIterator($path);
  $file = null;
  foreach ($dirIterator as $file) {
    if ($file->isDot()) continue;
    if ($file->isDir()) continue;
    if (!in_array($file->getExtension(), $validExtensions)) continue;
    $file = $file->getFilename();
    break;
  }
  if (empty($file)) {
    wp_die(false);
  }
  $moduleType = CLUEVO_AUDIO_MODULE_TYPE_ID; // module_type = audio
  $id = cluevo_create_module_metadata_post($strName);
  $result = cluevo_create_module(
    basename($file),
    $moduleType,
    $id,
    "audio/{$strName}",
    null,
    basename($file),
    null,
    0,
    null
  );
  wp_die($result);
}

function cluevo_install_pending_video_module($strName)
{
  $validExtensions = ["mp4", "webm", "mpeg"];
  $path = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), 'video', $strName);
  if (!file_exists($path)) wp_die(false);
  $dirIterator = new DirectoryIterator($path);
  $file = null;
  foreach ($dirIterator as $file) {
    if ($file->isDot()) continue;
    if ($file->isDir()) continue;
    if (!in_array($file->getExtension(), $validExtensions)) continue;
    $file = $file->getFilename();
    break;
  }
  if (empty($file)) {
    wp_die(false);
  }
  $moduleType = CLUEVO_VIDEO_MODULE_TYPE_ID; // module_type = video
  $id = cluevo_create_module_metadata_post($strName);
  $result = cluevo_create_module(
    basename($file),
    $moduleType,
    $id,
    "video/{$strName}",
    null,
    basename($file),
    null,
    0,
    null
  );
  wp_die($result);
}

function cluevo_install_pending_pdf_module($strName)
{
  $validExtensions = ["pdf"];
  $path = cluevo_path_join(cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH'), 'pdf', $strName);
  if (!file_exists($path)) wp_die(false);
  $dirIterator = new DirectoryIterator($path);
  $file = null;
  foreach ($dirIterator as $file) {
    if ($file->isDot()) continue;
    if ($file->isDir()) continue;
    if (!in_array($file->getExtension(), $validExtensions)) continue;
    $file = $file->getFilename();
    break;
  }
  if (empty($file)) {
    wp_die(false);
  }
  $moduleType = CLUEVO_PDF_MODULE_TYPE_ID; // module_type = video
  $id = cluevo_create_module_metadata_post($strName);
  $result = cluevo_create_module(
    basename($file),
    $moduleType,
    $id,
    "pdf/{$strName}",
    null,
    basename($file),
    null,
    0,
    null
  );
  wp_die($result);
}

function cluevo_add_tree_item_close_button_setting($item)
{
  if ($item->type === "module" || (!empty($item->module_id) && (int)$item->module_id > 0)) {
  ?>
    <div class="meta-container hide-lightbox-close-button">
      <details>
        <summary class="label"><?php _e("Lightbox Settings", "cluevo"); ?>
          <p class="help">
            <?php _e("These settings configure the lightbox.", "cluevo"); ?>
          </p>
        </summary>
        <div class="label sub">Schließen Button</div>
        <table class="cluevo-meta-settings-group">
          <tr>
            <td>
              <label><?php _e("Hide Button", "cluevo"); ?></label>
            </td>
            <td>
              <input type="checkbox" value="1" class="setting" data-target="hide-lightbox-close-button" <?php echo esc_attr($item->get_setting('hide-lightbox-close-button') ? 'checked' : ''); ?> />
            </td>
          </tr>
          <tr>
            <td>
              <label><?php _e("Button Text", "cluevo"); ?></label>
            </td>
            <td>
              <input placeholder="&times;" type="text" value="<?php echo esc_attr($item->get_setting('lightbox-close-button-text')); ?>" class="setting" data-target="lightbox-close-button-text" />
            </td>
          </tr>
          <tr>
            <td>
              <label><?php _e("Button Position", "cluevo"); ?></label>
            </td>
            <td>
              <select size="1" name="lightbox-close-button-position" class="setting" data-target="lightbox-close-button-position">
                <option value=""><?php esc_html_e("Default", "cluevo"); ?></option>
                <option value="top-left" <?php if ($item->get_setting('lightbox-close-button-position') == "top-left") echo "selected"; ?>><?php esc_html_e("top left", "cluevo"); ?></option>
                <option value="top-center" <?php if ($item->get_setting('lightbox-close-button-position') == "top-center") echo "selected"; ?>><?php esc_html_e("top center", "cluevo"); ?></option>
                <option value="top-right" <?php if ($item->get_setting('lightbox-close-button-position') == "top-right") echo "selected"; ?>><?php esc_html_e("top right", "cluevo"); ?></option>
                <option value="bottom-left" <?php if ($item->get_setting('lightbox-close-button-position') == "bottom-left") echo "selected"; ?>><?php esc_html_e("bottom left", "cluevo"); ?></option>
                <option value="bottom-center" <?php if ($item->get_setting('lightbox-close-button-position') == "bottom-center") echo "selected"; ?>><?php esc_html_e("bottom center", "cluevo"); ?></option>
                <option value="bottom-right" <?php if ($item->get_setting('lightbox-close-button-position') == "bottom-right") echo "selected"; ?>><?php esc_html_e("bottom right", "cluevo"); ?></option>
              </select>
            </td>
          </tr>
        </table>
        <details>
    </div>
  <?php }
}

function cluevo_add_tree_empty_setting($item)
{
  ?>
  <div class="meta-container info-box-settings">
    <details>
      <summary class="label"><?php _e("Info Box Settings", "cluevo"); ?>
        <p class="help">
          <?php _e("Customize the messages that are displayed if an element is empty or permissions are required.", "cluevo"); ?>
        </p>
      </summary>
      <table class="cluevo-meta-settings-group">
        <?php if ($item->type != "module") { ?>
          <tr>
            <td><?php esc_html_e("Hide Info Box", "cluevo"); ?></td>
            <td>
              <input type="checkbox" name="hide-info-box" <?php if ($item->get_setting("hide-info-box")) echo esc_attr("checked"); ?> data-target="hide-info-box" class="setting" />
            </td>
          </tr>
          <tr>
            <td><?php esc_html_e("Element is empty text", "cluevo"); ?></td>
            <td>
              <input type="text" name="element-is-empty-text" value="<?php echo esc_attr($item->get_setting("element-is-empty-text")); ?>" data-target="element-is-empty-text" class="setting" />
            </td>
          </tr>
        <?php } ?>
        <tr>
          <td><?php esc_html_e("Hide access denied box", "cluevo"); ?></td>
          <td>
            <input type="checkbox" name="hide-access-denied-box" <?php if ($item->get_setting("hide-access-denied-box")) echo esc_attr("checked"); ?> data-target="hide-access-denied-box" class="setting" />
          </td>
        </tr>
        <tr>
          <td><?php esc_html_e("Access denied text", "cluevo"); ?></td>
          <td>
            <input type="text" name="access-denied-text" value="<?php echo esc_attr($item->get_setting("access-denied-text")); ?>" data-target="access-denied-text" class="setting" />
          </td>
        </tr>
      </table>
    </details>
  </div>

<?php }

function cluevo_add_tree_item_limited_attempts($item)
{
  $max = $item->get_setting("max-attempts");
  $valid = true;
  if (empty($item->module)) {
    $max = null;
    $valid = false;
  };
  $hidden = empty($item->module);
?>
  <div class="meta-container info-box-settings <?php if ($hidden) echo esc_attr('hidden'); ?>" data-module-only>
    <details>
      <summary class="label"><?php _e("Max. Number of Attempts", "cluevo"); ?>
        <p class="help">
          <?php _e("You can customize how many times a user can attempt a module", "cluevo"); ?>
        </p>
      </summary>
      <table class="cluevo-meta-settings-group">
        <tr>
          <td><?php esc_html_e("Attempts", "cluevo"); ?></td>
          <td>
            <input type="number" name="max-attempts" value="<?php echo esc_attr($max); ?>" data-target="max-attempts" class="setting" <?php if (!$valid) echo "disabled"; ?> />
          </td>
        </tr>
      </table>
    </details>
  </div>

<?php }

function cluevo_add_tree_item_tags($item)
{
  $tags = $item->get_setting("tags");
?>
  <div class="meta-container info-box-settings">
    <details>
      <summary class="label"><?php _e("Tags", "cluevo"); ?>
        <p class="help">
          <?php _e("Tags help to organize your learning tree elements", "cluevo"); ?>
        </p>
      </summary>
      <table class="cluevo-meta-settings-group">
        <tr>
          <td><?php esc_html_e("Tags", "cluevo"); ?></td>
          <td>
            <input type="text" name="tags" value="<?php echo esc_attr($tags); ?>" data-target="tags" class="setting tags" />
          </td>
        </tr>
      </table>
    </details>
  </div>

<?php }

function cluevo_add_tree_item_complete_notifications($item)
{
  $enabled = $item->get_setting("notifications-enabled");
  $recipients = $item->get_setting("notification-recipients");
  $hidden = empty($item->module);
?>
  <div class="meta-container info-box-settings <?php if ($hidden) echo esc_attr('hidden'); ?>" data-module-only>
    <details>
      <summary class="label">
        <?php _e("Notifications", "cluevo"); ?>
        <p class="help">
          <?php _e("Set up notifications for when users complete content", "cluevo"); ?>
        </p>
      </summary>
      <table class="cluevo-meta-settings-group">
        <tr>
          <td><?php esc_html_e("Enabled", "cluevo"); ?></td>
          <td>
            <input type="checkbox" value="success" name="notifications-enabled" <?php if ($enabled && !empty($item->module)) echo esc_attr("checked"); ?> data-target="notifications-enabled" class="setting notifications-enabled" />
          </td>
        </tr>
        <tr>
          <td><?php esc_html_e("Recipients", "cluevo"); ?></td>
          <td>
            <input type="text" name="notification-recipients" value="<?php esc_attr_e($recipients); ?>" data-target="notification-recipients" class="setting notification-recipients" />
            <?php esc_html_e("You can add multiple recipients by entering e-mail addresses separated by commas.", "cluevo"); ?>
          </td>
        </tr>
      </table>
    </details>
  </div>

<?php }

function cluevo_add_tree_item_allow_pdf_download($item)
{
  $checked = $item->get_setting("allow-pdf-download");
  $valid = false;
  if (!empty($item->module)) {
    $module = cluevo_get_module($item->module);
    if (!empty($module) && $module->type_name === 'pdf') {
      $valid = true;
    } else {
      $checked = false;
    }
  } else {
    $checked = false;
  }
  $hidden = empty($item->module);
?>
  <div class="meta-container info-box-settings <?php if ($hidden) echo esc_attr('hidden'); ?>" data-module-only>
    <details>
      <summary class="label"><?php _e("Allow download of PDF file", "cluevo"); ?>
        <p class="help">
          <?php _e("Adds a download button to allow downloading the source PDF file", "cluevo"); ?>
        </p>
      </summary>
      <table class="cluevo-meta-settings-group">
        <tr>
          <td><?php esc_html_e("Enable", "cluevo"); ?></td>
          <td>
            <input type="checkbox" name="allow-pdf-download" value="1" data-target="allow-pdf-download" class="setting" <?php if (!$valid) echo "disabled"; ?> <?php if ($checked) echo "checked"; ?> />
          </td>
        </tr>
      </table>
    </details>
  </div>

<?php }

function cluevo_check_item_access($item)
{
  if (empty($item->module)) return $item;
  $uid = get_current_user_id();
  $item->load_settings();
  $max = (!empty($item->settings["max-attempts"])) ? (int)$item->settings["max-attempts"] : 0;
  if (empty($max)) return $item;
  $curCount = (int)cluevo_get_users_attempt_count($uid, $item->module);
  $item->attempt_count = $curCount;
  $attemptAccess = (!empty($max) && $curCount >= $max) ? 0 :  1;
  $item->access_status["attempts"] = $attemptAccess;
  $access = true;
  foreach ($item->access_status as $type => $value) {
    if ($value == false) {
      $access = false;
      break;
    }
  }
  $item->access = $access || current_user_can("administrator");
  return $item;
}

function cluevo_add_completed_module_to_list($args)
{
  if (empty($args["state"])) return;
  if (empty($args["module_id"])) return;
  cluevo_clear_turbo_cache();
  $modules = cluevo_turbo_get_users_completed_modules($args["user_id"], false);
  $GLOBALS["cluevo-users-completed-modules"] = $modules;
}

function cluevo_add_tree_item_is_link_setting($item)
{
  $link = $item->get_setting("item-is-link");
?>
  <div class="meta-container item-is-link">
    <details>
      <summary class="label"><?php _e("Item is a link", "cluevo"); ?>
        <p class="help">
          <?php _e("This item links to some other content. Assigned modules won't start, users will be sent the entered link instead.", "cluevo"); ?>
        </p>
      </summary>
      <input type="url" name="item-is-link" value="<?php echo esc_attr($link); ?>" data-target="item-is-link" class="setting" placeholder="https://" />
      <label>
        <input type="checkbox" name="open-link-in-new-window" <?php if ($item->get_setting("open-link-in-new-window")) echo esc_attr("checked"); ?> data-target="open-link-in-new-window" class="setting" />
        <?php esc_html_e("Open link in new window", "cluevo"); ?>
      </label>
    </details>
  </div>
  <?php }

function cluevo_register_default_module_types($args)
{

  $scorm12 = [
    'name' => __('SCORM 1.2', 'cluevo'),
    'key' => 'scorm',
    'icon' => plugins_url("/images/icon-module-ui-scorm-1-2_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Upload a SCORM module or enter a link to a SCORM module file.', 'cluevo'),
    'field' => 'mixed',
    'filter' => '.zip',
    'field-placeholder' => __('https://', 'cluevo'),
    'button-label' => __('select file', 'cluevo')
  ];

  $scorm2004 = [
    'name' => __('SCORM 2004', 'cluevo'),
    'key' => 'scorm',
    'icon' => plugins_url("/images/icon-module-ui-scorm-2004_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Upload a SCORM module or enter a link to a SCORM module file.', 'cluevo'),
    'field' => 'mixed',
    'filter' => '.zip',
    'field-placeholder' => __('https://', 'cluevo'),
    'button-label' => __('select file', 'cluevo')
  ];

  $audio = [
    'name' => __('Audio File', 'cluevo'),
    'key' => 'audio',
    'icon' => plugins_url("/images/icon-module-ui-audio_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Upload a audio file or enter a link to a audio file.', 'cluevo'),
    'field' => 'mixed',
    'filter' => '.mp3,.wav,.webm',
    'field-placeholder' => __('', 'cluevo'),
    'button-label' => __('select audio file', 'cluevo')
  ];

  $video = [
    'name' => __('Video File', 'cluevo'),
    'key' => 'video',
    'icon' => plugins_url("/images/icon-module-ui-video_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Upload a video file or enter a link to a video file.', 'cluevo'),
    'field' => 'mixed',
    'filter' => '.mp4,.webm,.mpeg',
    'field-placeholder' => __('https://', 'cluevo'),
    'button-label' => __('select video file', 'cluevo')
  ];

  $pdf = [
    'name' => __('PDF Document', 'cluevo'),
    'key' => 'pdf',
    'icon' => plugins_url("/images/icon-module-ui-pdf_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Upload a PDF document', 'cluevo'),
    'field' => 'mixed',
    'filter' => '.pdf',
    'field-placeholder' => __('https://', 'cluevo'),
    'button-label' => __('select PDF document', 'cluevo')
  ];

  $oembed = [
    'name' => __('YouTube, Vimeo, etc.', 'cluevo'),
    'key' => 'oembed',
    'icon' => plugins_url("/images/icon_module-ui-oembed_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Install the CLUEVO Video Tutorial Manager extension to embed content from YouTube and many other sites.', 'cluevo'),
    'field' => null,
    'filter' => null,
    'field-placeholder' => null,
    'button-label' => null,
    'alt-type' => "cluevo-lms-extension-oembed",
    'alt-icon-class' => "extension",
    'alt-content' => '<p><a class="button button-primary cluevo-btn-install-type" href="' . esc_attr(admin_url('plugin-install.php?s=cluevo&tab=search&type=term')) . '">' . esc_html__("Install CLUEVO oEmbed extension", "cluevo") . '</a></p>'
  ];

  $gdocs = [
    'name' => __('Google Documents', 'cluevo'),
    'key' => 'gdocs',
    'icon' => plugins_url("/images/icon-module-ui-gdocs_256x256.png", plugin_dir_path(__FILE__)),
    'description' => __('Install the CLUEVO Google Documents extension to use your Google Documents as modules in your LMS.', 'cluevo'),
    'field' => null,
    'filter' => null,
    'field-placeholder' => null,
    'button-label' => null,
    'alt-type' => "cluevo-lms-extension-gdocs",
    'alt-icon-class' => "extension",
    'alt-content' => '<p><a class="button button-primary cluevo-btn-install-type" href="' . esc_attr(admin_url('plugin-install.php?s=cluevo&tab=search&type=term')) . '">' . esc_html__("Install CLUEVO Google Docs extension", "cluevo") . '</a></p>'
  ];

  $args["types"][] = $scorm12;
  $args["types"][] = $scorm2004;
  $args["types"][] = $audio;
  $args["types"][] = $video;
  $args["types"][] = $pdf;
  $args["types"][] = $oembed;
  $args["types"][] = $gdocs;
}

add_action('cluevo_register_module_types', 'cluevo_register_default_module_types', 0);
add_action('cluevo_lms_item_handle_tools', function ($item) {
  if (!is_plugin_active('cluevo-lms-extension-certificates/cluevo-lms-extension-certificates.php')) { ?>
    <div class="cluevo-ext-tease cluevo-btn cluevo-btn-square" title="<?php esc_attr_e("Get the Certificates Extension", "cluevo"); ?>"><img class="plugin-logo" src="<?php echo plugins_url("/images/cert.svg", __DIR__); ?>" /></div>
<?php
  }
});

?>
