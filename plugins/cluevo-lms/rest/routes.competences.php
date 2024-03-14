<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_api_get_competence($args)
{
  if (empty($args["id"])) {
    $results = cluevo_get_competences();
  } else {
    $results = cluevo_get_competence($args["id"]);
    if (!empty($results)) {
      $results->load_areas();
      $results->load_modules();
    }
  }

  return $results;
}

function cluevo_api_get_new_competence($args)
{
  $uid = get_current_user_id();
  if ($uid <= 0) {
    return new WP_REST_Response("Login required", 401);
  }
  $result = new CluevoCompetence();
  return $result;
}

function cluevo_api_get_new_competence_area($args)
{
  $uid = get_current_user_id();
  if ($uid <= 0) {
    return new WP_REST_Response("Login required", 401);
  }

  $result = new CluevoCompetenceArea();
  return $result;
}

function cluevo_api_update_competence($request)
{
  if (empty($request["id"])) {
    return "invalid id";
  } else {
    if (cluevo_can_edit_competence($request["id"])) {
      $comp = $request->get_json_params();
      $current = cluevo_get_competence($request["id"]);
      if ($current->competence_name != $comp["competence_name"] && cluevo_competence_exists($comp["competence_name"])) {
        $results = "competence exists";
      } else {
        $results = cluevo_update_competence($request["id"], $comp["competence_name"], $comp["competence_type"]);
      }
    } else {
      $results = "authorization error";
    }
  }

  return $results;
}

function cluevo_api_create_competence($request)
{
  $result = false;
  $comp = $request->get_json_params();
  if (!empty($comp["competence_name"]) && !empty(trim($comp["competence_name"]))) {
    if (!cluevo_competence_exists($comp["competence_name"])) {

      $type = (!empty($comp["competence_type"])) ? $comp["competence_type"] : "system";
      if (current_user_can('administrator') && $type == "system")
        $type = "user";

      $result = cluevo_add_competence(trim($comp["competence_name"]), $type);
      if ($result !== false) {
        if (!empty($comp["areas"])) {
          cluevo_set_competences_areas($result, $comp["areas"]);
        }
        if (!empty($comp["modules"])) {
          cluevo_set_competences_modules($result, $comp["modules"]);
        }
        $competence = cluevo_get_competence($result);
        if (!empty($competence)) {
          $competence->load_areas();
          $competence->load_modules();
          return $competence;
        }
      }
    }
  }
  return $result;
}

function cluevo_api_create_competence_area($request)
{
  $result = false;
  $comp = $request->get_json_params();
  if (!empty($comp["competence_area_name"]) && !empty(trim($comp["competence_area_name"]))) {
    if (!cluevo_competence_area_exists($comp["competence_area_name"])) {

      $type = (!empty($comp["competence_area_type"])) ? $comp["competence_area_type"] : "system";
      if (current_user_can('administrator') && $type == "system")
        $type = "user";

      $result = cluevo_add_competence_area(trim($comp["competence_area_name"]), $type);
      if ($result !== false) {
        if (!empty($comp["competences"])) {
          cluevo_set_areas_competences($result, $comp["competences"]);
        }
        $area = cluevo_get_competence_area($result);
        if ($area) {
          $area->load_competences();
          $area->load_modules();
          return $area;
        }
      }
    }
  }
  return $result;
}

function cluevo_api_update_competence_area($request)
{
  if (empty($request["id"])) {
    return "invalid id";
  } else {
    if (cluevo_can_edit_competence_area($request["id"])) {
      $comp = $request->get_json_params();
      $current = cluevo_get_competence_area($request["id"]);
      if ($current->competence_area_name != $comp["competence_area_name"] && cluevo_competence_area_exists($comp["competence_area_name"])) {
        $results = "competence area exists";
      } else {
        $results = cluevo_update_competence_area($request["id"], $comp["competence_area_name"], $comp["competence_area_type"]);
      }
    } else {
      $results = "authorization error";
    }
  }

  return $results;
}

function cluevo_api_delete_competence_area($request)
{
  if (empty($request["id"])) {
    return "invalid id";
  } else {
    if (cluevo_can_edit_competence_area($request["id"])) {
      $results = cluevo_del_competence_area($request["id"]);
    } else {
      $results = "authorization error";
    }
  }

  return $results;
}

function cluevo_api_delete_competence($request)
{
  if (empty($request["id"])) {
    return "invalid id";
  } else {
    if (cluevo_can_edit_competence($request["id"])) {
      $results = cluevo_del_competence($request["id"]);
    } else {
      $results = "authorization error";
    }
  }

  return $results;
}

function cluevo_api_set_competences_areas($request)
{
  $areas = $request->get_json_params();
  if (cluevo_can_edit_competence($request["id"])) {
    return cluevo_set_competences_areas($request["id"], $areas);
  } else {
    return "authorization error";
  }
}

function cluevo_api_set_competences_of_area($request)
{
  $comps = $request->get_json_params();
  if (cluevo_can_edit_competence_area($request["id"])) {
    return cluevo_set_areas_competences($request["id"], $comps);
  } else {
    return "authorization error";
  }
}

function cluevo_api_get_competence_area($args)
{
  if (empty($args["id"])) {
    $results = cluevo_get_competence_areas();
  } else {
    $results = cluevo_get_competence_area($args["id"]);
    $results->load_competences();
    $results->load_modules();
  }

  return $results;
}

function cluevo_api_set_competences_modules($request)
{
  $modules = $request->get_json_params();
  if (cluevo_can_edit_competence($request["id"])) {
    return cluevo_set_competences_modules($request["id"], $modules);
  } else {
    return "authorization error";
  }
}

function cluevo_api_get_competence_strings()
{
  return [
    'delete_competence' => __("Really delete the competence {name}?", "cluevo"),
    'edit' => __("Edit", "cluevo"),
    'delete' => __("Delete", "cluevo"),
    'delete_area' => __("Really delete the competence group {name}?", "cluevo"),
    'create_competence' => __("New Competence", "cluevo"),
    'create_area' => __("New Competence Group", "cluevo"),
    'competences_heading' => __("Competences", "cluevo"),
    'competence_areas_heading' => __("Competence Groups", "cluevo"),
    'edit_metadata' => __("Metadata", "cluevo")
  ];
}

function cluevo_api_get_user_competence_polygon_points()
{
  $uid = get_current_user_id();
  $points = [];
  if ($uid !== 0) {
    $results = cluevo_get_users_competences($uid);
    foreach ($results as $c) {
      if ($c->competence_score > 0) {
        $points[] = [
          "label" => $c->competence_name,
          "value" => $c->competence_score * 100
        ];
      }
    }
  }
  return $points;
}
add_action('rest_api_init', function () {

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/((?P<id>\d+))?', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_competence',
    'args' => ["id" => []],
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/new', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_new_competence',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/(?P<id>\d+)', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_update_competence',
    'args' => ["id" => []],
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_create_competence',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_competence',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/(?P<id>\d+)', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_delete_competence',
    'args' => ["id" => []],
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/(?P<id>\d+)/areas', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_set_competences_areas',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/((?P<id>\d+))?', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_competence_area',
    'args' => ["id" => []],
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/new', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_new_competence_area',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_create_competence_area',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/(?P<id>\d+)', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_update_competence_area',
    'args' => ["id" => []],
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/(?P<id>\d+)', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_delete_competence_area',
    'args' => ["id" => []],
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_competence_area',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/areas/(?P<id>\d+)/competences', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_set_competences_of_area',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/competence/competences/(?P<id>\d+)/modules', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_set_competences_modules',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can('administrator');
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/user/competences/points', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_user_competence_polygon_points',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route(CLUEVO_PLUGIN_NAMESPACE . '/v1', '/strings/competence/', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_competence_strings',
    'permission_callback' => function () {
      return true;
    }
  ));
});
