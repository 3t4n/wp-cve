<?php
$userId = get_current_user_id();
if (!empty($userId)) {
  $modules = scandir(cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH'));
  $module = sanitize_file_name(get_query_var('download-scorm-module')) . '.zip';
  $path = cluevo_get_conf_const('CLUEVO_ABS_MODULE_ARCHIVE_PATH') . $module;
  $filename = strtolower(pathinfo($path,  PATHINFO_BASENAME));
  if (!empty($module) && file_exists($path) && in_array($module, $modules)) {
    header("Content-type: application/zip"); 
    header("Content-Disposition: attachment; filename=$filename"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile("$path");
  } else {
  http_response_code(404);
  die('file not found');
  }
} else {
  http_response_code(403);
  die('Forbidden');
}
?>
