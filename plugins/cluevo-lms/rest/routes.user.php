<?php
function cluevo_api_fetch_me() {
  $uid = get_current_user_id();
  if (!$uid) return null;
  $user = cluevo_get_lms_users($uid);

  return $user;
}

function cluevo_api_fetch_me_competences() {
  $uid = get_current_user_id();
  if (!$uid) return null;
  $scores = cluevo_get_users_competence_scores($uid);
  return $scores;
}

function cluevo_api_fetch_me_competence_areas() {
  $uid = get_current_user_id();
  if (!$uid) return null;
  $scores = cluevo_get_users_competence_area_scores($uid);
  return $scores;
}

function cluevo_api_fetch_me_module_stats() {
  $uid = get_current_user_id();
  if (!$uid) return null;
  $stats = cluevo_get_users_module_stats($uid);
  return $stats;
}

add_action( 'rest_api_init', function () {

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/me', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_me',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/me/competences', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_me_competences',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/me/competence-areas', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_me_competence_areas',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

  register_rest_route( CLUEVO_PLUGIN_NAMESPACE . '/v1', '/me/modules/stats', array(
    'methods' => 'GET',
    'callback' => 'cluevo_api_fetch_me_module_stats',
    'permission_callback' => function () {
      return is_user_logged_in();
    }
  ));

});

?>
