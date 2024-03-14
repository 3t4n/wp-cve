<?php
if (!defined("CLUEVO_ACTIVE")) exit;

require_once(plugin_dir_path(__DIR__) . "conf/config.inc.php");
require_once(plugin_dir_path(__DIR__) . "functions/functions.inc.php");

function cluevo_api_get_modules()
{
  $modules = cluevo_get_modules();
  return $modules;
}

function cluevo_api_get_module($args)
{
  $module = cluevo_get_module($args["module_id"]);
  return $module;
}

function cluevo_api_upload_module(WP_REST_Request $request)
{
  // @ini_set('max_execution_time', 6000);
  $errors = [];
  $messages = [];
  $handled = false;
  $module = (!empty($request["module-id"])) ? (int)$request["module-id"] : null;
  $messages[] = __("Handling module upload request", "cluevo");

  if (!empty($_FILES) && !empty($_FILES["module-file"]) && $_FILES["module-file"]["error"] === UPLOAD_ERR_OK) {
    if (!empty($_FILES["module-file"])) {
      cluevo_handle_module_upload($errors, $messages, $handled, $module);
    } else {
      $messages[] = __("Module file filed was empty", "cluevo");
    }
  } else {
    if (!empty($_FILES["module-file"]) && $_FILES["module-file"]["error"] !== UPLOAD_ERR_OK) {
      $err = (int)$_FILES["module-file"]["error"];
      switch ($err) {
        case UPLOAD_ERR_INI_SIZE:
          $errors[] = __("Upload error: Uploaded file exceeds the upload_max_filesize directive in php.ini", "cluevo");
          break;
        case UPLOAD_ERR_FORM_SIZE:
          $errors[] = __("Upload error: Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", "cluevo");
          break;
        case UPLOAD_ERR_PARTIAL:
          $errors[] = __("Upload error: Uploaded file was only partially uploaded", "cluevo");
          break;
        case UPLOAD_ERR_NO_FILE:
          $errors[] = __("Upload error: No file was uploaded", "cluevo");
          break;
        case UPLOAD_ERR_NO_TMP_DIR:
          $errors[] = __("Upload error: Missing temporary folder", "cluevo");
          break;
        case UPLOAD_ERR_CANT_WRITE:
          $errors[] = __("Upload error: Failed to write file to disk", "cluevo");
          break;
        case UPLOAD_ERR_EXTENSION:
          $errors[] = __("Upload error: A PHP extensions stopped the file upload", "cluevo");
          break;
        default:
          $errors[] = __("Upload error: Unknown error", "cluevo");
      }
      return new WP_REST_Response(['errors' => $errors, 'messages' => $messages, 'handled' => $handled, "module" => $module], 500);
    }
    if (!empty($request["module-dl-url"])) {
      $messages[] = __("Running URL handler", "cluevo");
      if (filter_var($request["module-dl-url"], FILTER_VALIDATE_URL)) {
        $url = esc_url_raw($request["module-dl-url"], ['http', 'https', 'ftp']);
        if (!empty($url)) {
          if (cluevo_url_exists($url) || $request["force"] === "true") {
            cluevo_handle_module_download($request["module-dl-url"], $errors, $messages, $handled, $module);
          } else {
            $errors[] = __("Url does not exist", "cluevo");
          }
        }
      } else {
        $messages[] = __("Running module url handlers", "cluevo");
        do_action('cluevo_handle_misc_module_url_input', [
          "input" => $request["module-dl-url"],
          "handled" => &$handled,
          "result" => &$result,
          "errors" => &$errors,
          "messages" => &$messages,
          "result" => &$module
        ]);
        if (!$handled) {
          $messages[] = __("No handler for this module type could be found.", "cluevo");
        }
      }
    }
  }

  if (!$handled) {
    return new WP_REST_Response(['errors' => $errors, 'messages' => $messages, 'handled' => $handled, "module" => $module], 400);
  } else {
    return ['errors' => $errors, 'messages' => $messages, 'handled' => $handled, "module" => $module];
  }
}

function cluevo_api_fetch_module_progress(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  $itemId = (int)$request["item_id"];
  if ($userId > 0) {
    $item = cluevo_get_module($itemId);
    // if (!$item) return false;
    return cluevo_get_module_progress($userId, $itemId);
  } else {
    return new WP_Error('cluevo_access_denied', __("Authentication required to load ongoing progress.", "cluevo"), ['status' => 403]);
  }
}

function cluevo_api_fetch_module_parameters($args)
{
  $userId = get_current_user_id();
  if ($userId > 0) {
    $item = cluevo_get_learning_structure_item($args["item_id"], $userId);
    $item->load_settings();
    $module = cluevo_get_module($item->module_id);
    // always ask for resume for admins and trainers
    if (current_user_can("administrator") || $item->is_trainer) {
      $attemptMode = "ask";
    } else {
      $attemptMode = get_option("cluevo-modules-attempt-prompt", "ask");
      $lastProgress = cluevo_get_module_progress($userId, $item->module); // Check whether last attempt was completed?
      $hasAttemptsLeft = !empty((int)$item->settings["max-attempts"]) && (int)$item->attempts < (int)$item->settings["max-attempts"];
      if (!empty($lastProgress) && $lastProgress["completion_status"] !== "completed" && $lastProgress["lesson_status"] !== "completed" && $lastProgress["credit"]) {
        if ($module->scorm_version === "2004" && $lastProgress["success_status"] === "unknown") {
          if (!$hasAttemptsLeft) {
            $attemptMode = "resume";
          }
        } else if ($module->scorm_version === "1.2" && in_array($lastProgress["lesson_status"], ["incomplete", "browsed", "not attempted"])) {
          if (!$hasAttemptsLeft) {
            $attemptMode = "resume";
          }
        }
      }
    }
    switch ($attemptMode) {
      case "new":
        $state = cluevo_init_module_progress($userId, $item->module_id, [], $module->scorm_version);
        $parms = cluevo_get_module_parameters($item->module_id, $userId, $state["attempt_id"]);
        break;
      case "resume":
        $parms = cluevo_get_module_parameters($item->module_id, $userId);
        $parms["_resume"] = true;
        break;
      case "ask":
      default:
        $parms = cluevo_get_module_parameters($item->module_id, $userId);
    }
    $parms["_scorm_version"] = $module->scorm_version;
    do_action("cluevo_api_fetch_module_parameters", $userId, $item, $module);
    return $parms;
  } else {
    $item = cluevo_get_learning_structure_item($args["item_id"]);
    $module = cluevo_get_module($item->module_id);
    $parms = cluevo_get_module_parameters($item->module_id);
    $parms["_scorm_version"] = $module->scorm_version;
    return $parms;
  }
}

function cluevo_api_init_new_attempt(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  if ($userId > 0) {
    $item = cluevo_get_learning_structure_item($request["item_id"], $userId, true, true);
    $intModuleId = $item->module_id;
    if (empty($intModuleId)) {
      return new WP_Error('cluevo_invalid_module_id', __("Invalid module id", "cluevo"), ['status' => 500]);
    }
    $moduleData = cluevo_get_module($intModuleId);
    $version = $moduleData->scorm_version;
    $state = cluevo_init_module_progress($userId, $intModuleId, [], $version);
    $parms = cluevo_get_module_parameters($intModuleId, $userId, $state["attempt_id"]);
    return $parms;
  } else {
    return new WP_Error('cluevo_access_denied', __("Authentication required to initialize new module attempts.", "cluevo"), ['status' => 403]);
  }
}

function cluevo_api_fetch_learning_structure_item($args)
{
  $userId = get_current_user_id();
  $item = cluevo_get_learning_structure_item($args["item_id"], $userId);
  if ($item) $item->load_settings();
  // always allow access if user is admin or trainer
  if (current_user_can("administrator")) return $item;
  if ($item->is_trainer) return $item;
  if ($item->access) {
    $lastProgress = cluevo_get_module_progress($userId, $item->module);
    global $wpdb;
    $table = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES_PROGRESS;
    if (!empty($userId)) {
      $item->attempts = (int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE module_id = %d AND user_id = %d", [(int)$item->module, (int)$userId]));
    } else {
      $item->attempts = 0;
    }
    $hasAttemptsLeft = !empty((int)$item->settings["max-attempts"]) && (int)$item->attempts <= (int)$item->settings["max-attempts"];
    // if user has attempts left and the module is not completed allow access
    if ($hasAttemptsLeft && !empty($lastProgress) && $lastProgress["completion_status"] !== "completed" && $lastProgress["lesson_status"] !== "completed") return $item;
    if (!empty((int)$item->settings["max-attempts"]) && (int)$item->attempts >= (int)$item->settings["max-attempts"]) {
      return new WP_Error(
        'cluevo_access_denied',
        sprintf(
          esc_html__("You have reached the maximum number of attempts (%d/%d) for this module", "cluevo"),
          (int)$item->attempts,
          (int)$item->settings["max-attempts"]
        ),
        ['status' => 403]
      );
    } else {
      return $item;
    }
  } else {
    $text = cluevo_get_lms_item_access_denied_text($item);
    return new WP_Error('cluevo_access_denied', $text, ['status' => 403]);
  }
}

function cluevo_api_fetch_learning_structure_items()
{
  $userId = get_current_user_id();
  $items = cluevo_get_learning_structure_items(0, $userId);

  return $items;
}

function cluevo_save_module_progress(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  if ($userId > 0) {
    $module = cluevo_get_module((int)$request['id']);
    if (!empty($module)) {
      switch ($module->type_name) {
        case "audio":
        case "video":
        case "pdf":
          cluevo_update_media_module_progress($userId, (int)$request['id'], (float)$request['max'], (float)$request["score"]);
          break;
        default:
          do_action('cluevo_save_module_progress_' . strtolower($module->type_name), [
            "user_id" => $userId,
            "module" => $module,
            "request" => $request
          ]);
          do_action('cluevo_save_module_progress', [
            "user_id" => $userId,
            "module" => $module,
            "request" => $request
          ]);
      }
    }
  } else {
    return new WP_Error('cluevo_no_saved_progress_for_guests', __("Authentication required to save module progress. To keep a record of your progress please login or sign up.", "cluevo"), ['status' => 403]);
  }
  return true;
}

function cluevo_save_item_progress(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  if ($userId > 0) {
    $item = cluevo_get_learning_structure_item($request["itemId"], $userId);
    if (empty($item->module_id)) {
      return new WP_Error('cluevo_no_module_assigned', __("This item does not have a module assigned", "cluevo"), ['status' => 500]);
    }
    $module = cluevo_get_module($item->module_id);
    if (empty($module->module_id)) {
      return new WP_Error('cluevo_module_not_found', __("Module not found", "cluevo"), ['status' => 500]);
    }
    if (!empty($module)) {
      do_action('cluevo_save_module_progress_' . strtolower($module->type_name), [
        "user_id" => $userId,
        "module" => $module,
        "item_id" => (int)$request["itemId"],
        "request" => $request
      ]);
      do_action('cluevo_save_module_progress', [
        "user_id" => $userId,
        "module" => $module,
        "item_id" => (int)$request["itemId"],
        "request" => $request
      ]);
    }
  } else {
    return new WP_Error('cluevo_no_saved_progress_for_guests', __("Authentication required to save module progress. To keep a record of your progress please login or sign up.", "cluevo"), ['status' => 403]);
  }
  return true;
}

function cluevo_save_module_data(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  if ($userId > 0) {
    return cluevo_write_module_parameters($request['id'], $userId, $request->get_json_params());
  } else {
    return new WP_Error('cluevo_no_saved_progress_for_guests', __("Authentication required to save module progress. To keep a record of your progress please login or sign up.", "cluevo"), ['status' => 403]);
  }
}

function cluevo_api_create_item(WP_REST_Request $request)
{
  $userId = get_current_user_id();
  $parentId = (int)$request['id'];
  $parentPostId = 0;
  $level = 0;
  $path = "/";
  if ($parentId != 0) {
    $parent = cluevo_get_learning_structure_item($parentId);
    if (!empty($parent)) {
      $parentPostId = $parent->metadata_id;
      if (is_string($parent->path)) {
      $path = $parent->path . $parent->item_id . "/";
      } else if (is_object($parent->path)) {
        $path = "/" . implode("/", $parent->path->id) . "/";
      }
      $level = $parent->level + 1;
    } else {
      return new WP_Error('error', 'parent item not found');
    }
  }
  $tmpItem = new CluevoItem();
  $tmpItem->parent_id = $parentId;
  $tmpItem->path = $path;
  $tmpItem->level = $level;
  switch ($level) {
    case 1:
      $name = __("New Course", "cluevo");
      break;
    case 2:
      $name = __("New Chapter", "cluevo");
      break;
    case 3:
      $name = __("New Module", "cluevo");
      break;
    default:
      $name = __("New Item", "cluevo");
  }
  $tmpItem->name = $name;
  $metaId = cluevo_create_metadata_page($tmpItem, $parentPostId);
  $tmpItem->metadata_id = $metaId;

  $result = cluevo_create_learning_structure_item($tmpItem);
  return ["item_id" => $result, "meta_id" => $metaId];
}

function cluevo_api_make_item_module(WP_REST_Request $request)
{
  $id = (int)$request["id"];
  $moduleId = (int)$request["mid"];
  $userId = get_current_user_id();
  $item = cluevo_get_learning_structure_item($id, $userId);
  $module = cluevo_get_module($moduleId);
  if (!empty($item) && !empty($module)) {
    //cluevo_create_learning_structure_module_item($id, $moduleId);
    $item->module_id = $moduleId;
    return cluevo_update_learning_structure_item($item);
  }

  return false;
}

function cluevo_api_remove_item_module(WP_REST_Request $request)
{
  $id = (int)$request["id"];
  $userId = get_current_user_id();
  $item = cluevo_get_learning_structure_item($id, $userId);
  if (!empty($item)) {
    $result = cluevo_remove_learning_structure_module_item($id);
    if ($result == 1) return true;
  }
  return false;
}

function cluevo_api_delete_item(WP_REST_Request $request)
{
  $id = (int)$request['id'];
  if (!empty(cluevo_remove_learning_structure_item($id))) {
    return true;
  }
  return false;
}

function cluevo_api_change_module_language(WP_REST_Request $request)
{
  $id = (int)$request['id'];
  $from = $request['from'];
  $to = $request['to'];
  //return [$id, $langCode ];

  return cluevo_set_module_language($id, $from, $to);
}

function cluevo_api_update_module_name(WP_REST_Request $request)
{
  $id = (int)$request['id'];
  $name = $request['name'];
  $module = cluevo_get_module($id);
  $result = false;
  if ($module) {
    if (!cluevo_module_exists($name)) {
      $result = cluevo_update_module_name($id, $name);
    }
  }
  return $result;
}

function cluevo_api_update_module_tags(WP_REST_Request $request)
{
  $id = (int)$request['id'];
  $tags = $request['tags'];
  $module = cluevo_get_module($id);
  $result = false;
  if ($module) {
    $result = cluevo_update_module_tags($id, $tags);
  }
  return $result;
}

add_action('rest_api_init', function () {

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_modules',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/upload', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_upload_module',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<module_id>[\d]+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_module',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<item_id>[\d]+)/parameters', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_module_parameters',
    'args' => ['item_id' => []],
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<item_id>[\d]+)/progress', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_module_progress',
    'args' => ['item_id' => []],
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<item_id>[\d]+)/new-attempt', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_init_new_attempt',
    'args' => ['item_id' => []],
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<item_id>[\d]+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_learning_structure_item',
    'args' => ['item_id' => []],
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_learning_structure_items',
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<id>[\d]+)/create', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_create_item',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<id>[\d]+)/make-module/(?P<mid>[\d]+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_make_item_module',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<id>[\d]+)/remove-module', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_remove_item_module',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<id>[\d]+)/delete', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_delete_item',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<id>[\d-]+)/parameters', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_save_module_data',
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<id>[\d-]+)/progress', array(
    'methods' => 'POST',
    'callback' => 'cluevo_save_module_progress',
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/items/(?P<itemId>[\d-]+)/progress', array(
    'methods' => 'POST',
    'callback' => 'cluevo_save_item_progress',
    'permission_callback' => function () {
      return true;
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<id>[\d]+)/language', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_change_module_language',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<id>[\d-]+)/name', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_update_module_name',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/modules/(?P<id>[\d-]+)/tag', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_update_module_tags',
    'permission_callback' => function () {
      return current_user_can('administrator');
    }
  ));
});

include "routes.competences.php";
include "routes.user-management.php";
include "routes.permissions.php";
include "routes.user.php";
include "routes.autocomplete.php";
