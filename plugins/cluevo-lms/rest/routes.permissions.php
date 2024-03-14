<?php
if (!defined("CLUEVO_ACTIVE")) exit;

function cluevo_api_get_group_permissions(WP_REST_REQUEST $req) {
  $result = cluevo_get_tree_perms_for_group((int)$req["gid"]);
  return $result;
}

function cluevo_api_get_user_permissions(WP_REST_REQUEST $req) {
  $result = cluevo_get_tree_perms_for_user((int)$req["uid"]);
  return $result;
}

function cluevo_api_get_group_permissions_for_item(WP_REST_REQUEST $req) {
  $result = cluevo_get_tree_perms_for_group((int)$req["gid"], (int)$req["id"]);
  return $result;
}

function cluevo_api_get_user_permissions_for_item(WP_REST_REQUEST $req) {
  $result = cluevo_get_tree_perms_for_user((int)$req["uid"], (int)$req["id"]);
  return $result;
}

function cluevo_api_save_permission(WP_REST_REQUEST $req) {
  $p = CluevoPermission::from_std_class((object)$req['perm']);
  if (!empty($p) && !empty($p->item_id))
    return $p->save();

  return false;
}

add_action( 'rest_api_init', function () {
  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/permissions/groups/(?P<gid>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_group_permissions',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/permissions/users/(?P<uid>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_user_permissions',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/permissions/save', array(
    'methods' => 'POST',
    'callback' => 'cluevo_api_save_permission',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/permissions/groups/(?P<gid>\d+)/items/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_group_permissions_for_item',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can( 'administrator' );
    }
  ) );

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/admin/permissions/users/(?P<uid>\d+)/items/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_get_user_permissions_for_item',
    'permission_callback' => function () {
      //return true; // for development
      return current_user_can( 'administrator' );
    }
  ) );
});

?>
