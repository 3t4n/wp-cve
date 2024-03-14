<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_api_user_admin_get_lms_users(WP_REST_Request $req) {
  $search = (!empty($req["search"]) && is_string($req["search"])) ? $req["search"] : "";
  $users = cluevo_get_lms_users($search);
  if (!is_array($users))
    $users = [ $users ];
  return $users;
}

function cluevo_api_user_admin_get_lms_users_paged(WP_REST_Request $req) {
  $search = (!empty($req["search"]) && is_string($req["search"])) ? $req["search"] : "";
  $page = (!empty($req["page"]) && is_numeric($req["page"])) ? $req["page"] : "";
  $users = cluevo_get_lms_users_paginated($search, $page, 50);
  return $users;
}

function cluevo_api_user_admin_reset_progress(WP_REST_Request $req) {
  $uid = (!empty($req["uid"]) && is_string($req["uid"])) ? $req["uid"] : "";
  cluevo_reset_user_progress($uid);
}

function cluevo_api_user_admin_get_lms_user(WP_REST_Request $req) {
  $user = cluevo_get_lms_users($req["uid"]);
  return $user;
}

function cluevo_api_user_admin_make_lms_user(WP_REST_Request $req) {
  $uid = $req["user_id"];
  $result = cluevo_make_lms_user($uid);
  return $result;
}

function cluevo_api_user_admin_make_lms_users(WP_REST_Request $req) {
  $users = $req["users"];
  $results = array();
  if (!empty($users)) {
    foreach ($users as $uid) {
      $results[] = cluevo_make_lms_user($uid);
    }
  }
  return $results;
}

function cluevo_api_user_admin_delete_lms_user(WP_REST_Request $req) {
  $uid = $req["id"];
  $result = cluevo_delete_lms_user($uid);
  return $result;
}

function cluevo_api_user_admin_get_trainer_eligible_users(WP_REST_Request $req) {
  $result = cluevo_get_trainer_eligible_lms_users();
  return $result;
}

function cluevo_api_user_admin_get_wp_users(WP_REST_Request $req) {
  $search = (!empty($req["search"]) && is_string($req["search"])) ? $req["search"] : "";
  $result = cluevo_get_wp_users($search);
  return $result;
}

function cluevo_api_user_admin_get_user_groups(WP_REST_Request $req) {
  $search = (!empty($req["search"]) && is_string($req["search"])) ? $req["search"] : "";
  $result = cluevo_get_user_groups($search);
  return $result;
}

function cluevo_api_user_admin_get_user_group(WP_REST_Request $req) {
  $result = cluevo_get_user_group($req["gid"]);
  return $result;
}

function cluevo_api_user_admin_update_user_group(WP_REST_Request $req) {
  $id = (!empty($req["group_id"])) ? $req["group_id"] : "";
  $name = (!empty($req["group_name"])) ? $req["group_name"] : "";

  if (empty($id))
    return new WP_Error('cluevo_group_invalid_id', 'Invalid group id', array ('status' => 500));
  if (empty($name))
    return new WP_Error('cluevo_group_invalid_name', 'Invalid group name', array ('status' => 500));

  $desc = (!empty($req["group_description"])) ? $req["group_description"] : "";
  $users = (!empty($req["users"]) && is_array($req["users"])) ? $req["users"] : [];
  $tags = (!empty($req["tags"])) ? $req["tags"] : null;
  $trainers = (!empty($req["trainers"]) && is_array($req["trainers"])) ? $req["trainers"] : [];

  $result = cluevo_update_user_group($id, $name, $desc, $tags);
  $mRes = cluevo_set_user_groups_members($id, $users);
  return [ $result, $mRes ];
}

function cluevo_api_user_admin_create_user_group(WP_REST_Request $req) {
  $name = (!empty($req["name"])) ? $req["name"] : "";
  $description = (!empty($req["description"])) ? $req["description"] : "";
  $tags = (!empty($req["tags"])) ? $req["tags"] : null;
  $users = (!empty($req["users"]) && is_array($req["users"])) ? $req["users"] : [];
  //return [ "name" => $name, "desc" => $description, "users" => $users ];
  if (empty($name))
    return new WP_Error('cluevo_group_invalid_name', 'Invalid group name', array ('status' => 500));

  $result = cluevo_create_user_group($name, $description, $tags);
  if ($result !== false) {
    if (!empty($users)) {
      $uRes = cluevo_add_users_to_group($users, $result);
      return $uRes;
    }
  }
  return $result;
}

function cluevo_api_user_admin_remove_user_from_group(WP_REST_Request $req) {
  $result = cluevo_remove_users_from_group($req["uid"], $req["gid"]);
  return $result;
}

function cluevo_api_user_admin_add_user_to_group(WP_REST_Request $req) {
  $result = cluevo_add_users_to_group($req["uid"], $req["gid"]);
  return $result;
}

function cluevo_api_user_admin_promote_user_to_trainer(WP_REST_Request $req) {
  $result = cluevo_promote_users_to_group_trainers($req["uid"], $req["gid"]);
  return $result;
}

function cluevo_api_user_admin_remove_group_trainer(WP_REST_Request $req) {
  $result = cluevo_remove_group_trainers($req["uid"], $req["gid"]);
  return $result;
}

function cluevo_api_user_admin_set_users_groups(WP_REST_Request $req) {
  $groups = (!empty($req["groups"]) && is_array($req["groups"])) ? $req["groups"] : [];
  if (is_array($groups)) {
    $result = cluevo_set_users_group_memberships($req["uid"], $groups);
    return $result;
  }
  return false;
}

function cluevo_api_user_admin_delete_user_group(WP_REST_Request $req) {
  $gid = $req["gid"];
  $result = cluevo_delete_user_group($gid);
  return $result;
}

add_action( 'rest_api_init', function () {

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_user_admin_get_lms_users',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_get_lms_users',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/paged/(?P<page>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_user_admin_get_lms_users_paged',
    'args' => [ "page" => [] ],
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/paged/(?P<page>\d+)', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_get_lms_users_paged',
    'args' => [ "page" => [] ],
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)', array(
    'methods' => 'VIEW',
    'callback' => 'cluevo_api_user_admin_get_lms_user',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/progress', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_user_admin_reset_progress',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/make/many', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_make_lms_users',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/make/(?P<id>\d+)', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_user_admin_make_lms_user',
    'args' => [ "id" => [] ],
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/delete/(?P<id>\d+)', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_user_admin_delete_lms_user',
    'args' => [ "id" => [] ],
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/trainers/eligible', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_user_admin_get_trainer_eligible_users',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/wordpress', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_get_wp_users',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/groups/(?P<gid>\d+)/add', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_add_user_to_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/groups/(?P<gid>\d+)/promote', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_promote_user_to_trainer',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/groups/(?P<gid>\d+)/demote', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_remove_group_trainer',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/groups/(?P<gid>\d+)/remove', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_user_admin_remove_user_from_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/(?P<uid>\d+)/groups', array(
    'methods' => 'PUT',
    'callback' => 'cluevo_api_user_admin_set_users_groups',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/groups', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_user_admin_get_user_groups',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/groups/(?P<gid>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_user_admin_get_user_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/groups/delete/(?P<gid>\d+)', array(
    'methods' => 'DELETE',
    'callback' => 'cluevo_api_user_admin_delete_user_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/groups', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_update_user_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/users/groups/create', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_user_admin_create_user_group',
    'permission_callback' => function () {
      return current_user_can( 'administrator' );
    }
  ) );

});
