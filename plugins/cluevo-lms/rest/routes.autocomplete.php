<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_api_search_autocomplete_users($request) {
  $parms = $request->get_json_params();
  $input = $parms["search"];
  if (empty($input)) {
    global $wpdb;
    $sql = "SELECT ID AS id, display_name AS name FROM {$wpdb->users}";
    $results = $wpdb->get_results($sql);
    return $results;
  } else {
    $users = get_users(["search" => "*$input*"]);
    $results = [];
    if (!empty($users)) {
      foreach ($users as $user) {
        $results[] = [
          "id" => $user->data->ID,
          "name" => $user->data->display_name
        ];
      }
    }
    return $results;
  }
}

function cluevo_api_search_autocomplete_modules($request) {
  $parms = $request->get_json_params();
  if (!empty($parms["search"])) {
    $input = $parms["search"];
    $modules = cluevo_search_modules($input);
  } else {
    $modules = cluevo_get_modules();
  }
  if (!empty($modules)) {
    foreach ($modules as $module) {
      $results[] = [
        "id" => $module->module_id,
        "name" => $module->module_name
      ];
    }
  }
  return $results;
}

add_action( "rest_api_init", function () {
  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . "/v1", "/admin/autocomplete/users", array(
    "methods" => "POST",
    "callback" => "cluevo_api_search_autocomplete_users",
    "permission_callback" => function () {
      //return true; // for development
      return current_user_can( "administrator" );
    }
  ));
  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . "/v1", "/admin/autocomplete/modules", array(
    "methods" => "POST",
    "callback" => "cluevo_api_search_autocomplete_modules",
    "permission_callback" => function () {
      //return true; // for development
      return current_user_can( "administrator" );
    }
  ));
});
?>
