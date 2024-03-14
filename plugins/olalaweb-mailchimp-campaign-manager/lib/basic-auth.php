<?php
/**
 * Add Basic Auth capabilities to WordPress.
 *
 * Required by our distant application to post Campaigns through WP REST API.
 *
 * This is a copy fromm "JSON Basic Authentication" plugin.
 *
 * @author WordPress API Team https://github.com/WP-API
 *
 * @see https://github.com/WP-API/Basic-Auth
 */

if (!function_exists('json_basic_auth_handler')) {
  function json_basic_auth_handler($user) {
    global $wp_json_basic_auth_error;
    $wp_json_basic_auth_error = null;

    // Don't authenticate twice
    if (!empty($user)) {
      return $user;
    }
    //account for issue where some servers remove the PHP auth headers
    //so instead look for auth info in a custom environment variable set by rewrite rules
    //probably in .htaccess
    //and, as a last resort, look in the querystring
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
      if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $header = $_SERVER['HTTP_AUTHORIZATION'];
      } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
      } elseif (isset($_GET['_authorization'])) {
        $header = $_GET['_authorization'];
        //and now remove this special header so it doesn't interfere with other parts of the request
        unset($_GET['authorization']);
      } else {
        $header = null;
      }
      if (!empty($header)) {
        //make sure there's the word 'Basic ' at the start, or else it's not for us
        if (strpos($header, 'Basic ') === 0) {
          $header_sans_word_basic = str_replace('Basic ', '', $header);
          $auth_parts             = explode(':', base64_decode($header_sans_word_basic), 2);
          if (is_array($auth_parts) && isset($auth_parts[0], $auth_parts[1])) {
            $_SERVER['PHP_AUTH_USER'] = $auth_parts[0];
            $_SERVER['PHP_AUTH_PW']   = $auth_parts[1];
          }
        }
      }
    }

    // Check that we're trying to authenticate
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
      return $user;
    }
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    /**
     * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
     * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
     * recursion and a stack overflow unless the current function is removed from the determine_current_user
     * filter during authentication.
     */
    remove_filter('determine_current_user', 'json_basic_auth_handler', 20);
    remove_filter('authenticate', 'wp_authenticate_spam_check', 99);

    $user = wp_authenticate($username, $password);

    add_filter('determine_current_user', 'json_basic_auth_handler', 20);
    add_filter('authenticate', 'wp_authenticate_spam_check', 99);

    if (is_wp_error($user)) {
      $wp_json_basic_auth_error = $user;
      return null;
    }

    $wp_json_basic_auth_error = true;
    //if we found a user, remove regular cookie filters because
    //they're just going to overwrite what we've found
    if ($user->ID) {
      remove_filter('determine_current_user', 'wp_validate_auth_cookie');
      remove_filter('determine_current_user', 'wp_validate_logged_in_cookie', 20);
    }
    return $user->ID;
  }
  add_filter('determine_current_user', 'json_basic_auth_handler', 20);
}

if (!function_exists('json_basic_auth_error')) {
  function json_basic_auth_error($error) {
    // Passthrough other errors
    if (!empty($error)) {
      return $error;
    }

    global $wp_json_basic_auth_error;

    return $wp_json_basic_auth_error;
  }
  add_filter('json_authentication_errors', 'json_basic_auth_error');
  add_filter('rest_authentication_errors', 'json_basic_auth_error');
}
