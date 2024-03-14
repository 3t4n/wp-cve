<?php
/**
 * @file
 * Provides meta data about site pages
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

/**
 * Similar to PHP's parse_url function. Parses a url and returns its components.
 * Includes CMS centric components such as system path and able to handle'
 * relative urls.
 *
 * returns:
 * scheme - e.g. http
 * host - domain name
 * port
 * user
 * pass
 * path
 * query
 * fragment
 * system_path - Drupal path
 * system_alias - Drupal path alias (default)
 *
 * @param $url URL to be parsed.
 * @param string $location used to designate url of page if href is a link on a
 *   page. This is used to add context for relative href urls.
 * @return array|bool
 */
function intel_parse_url($url, $location = '') {
  $comps = intel_parse_href($url, $location);
  $c = parse_url($comps['location']);
  if (!empty($c) && is_array($c)) {
    $comps = $c + $comps;
  }
  // correct relative urn issues. If url is relative urn, parse_url will treat
  // nid as scheme.
  if (
    (!empty($comps['scheme']) && ($comps['scheme'] != 'urn'))
    || (empty($comps['scheme']) && !empty($comps['path']) && substr($comps['path'], 0, 1) == ':')
  ) {
    $comps['is_urn'] = 1;

  }
  if (!empty($comps['system_path']) && (substr($comps['system_path'], 0, 5) == 'node/')) {
    $a = explode('/', $comps['system_path']);
    $comps['nid'] = (int)$a[1];
  }

  return $comps;
}

/*
 * Normalizes Drupal urls.
 */
function intel_parse_href($href, $location = '') {
  $base_url = intel()->base_url;
  $base_path = intel()->base_path;
  // removed urls that cause errors
  if (substr($href, 0, 10) == '/404.html?') {
    return FALSE;
  }
  
  // remove trailing slash
  if (substr($href, -1) == '/') {
    $href = substr($href, 0, -1);
  }

  $params = array(
    'location' => $href,
  );
  $is_external = 0;

  $urlc = parse_url($href);

  $internal_domains = array();
  $a = explode('//', $base_url);
  if (isset($a[1])) {
    $internal_domains = array(
      $a[1] => $a[1],
    );
  }
  
  $locc = parse_url($location);
  $loc_domain = '';
  $loc_root = '';
  if (!empty($locc['host'])) {
    $params['authority'] = $loc_domain = $locc['host'] . ((!empty($locc['port'])) ? ':' . $locc['port'] : '');
    if (!empty($locc['scheme'])) {
      $loc_root = $locc['scheme'] . '://' . $loc_domain;
    }    
  }  
  
  // absolute url
  if (!empty($urlc['scheme']) && !empty($urlc['host'])) {
    $is_external = 1;
    $href_domain = $urlc['host'] . ((!empty($urlc['port'])) ? ':' . $urlc['port'] : '');
    foreach ($internal_domains AS $idom) {
      if ($idom == $href_domain) {
        $is_external = 0;
        break;
      }
    }    
  }

  // internal url
  if (INTEL_PLATFORM == 'drupal' && !$is_external) {
    // is homepage
    if (empty($urlc['path'])) {
      $params['system_path'] = get_option('site_frontpage', 'node');
      $params['system_alias'] = '';
      return $params;
    }
    $path = $urlc['path'];
    $source = drupal_lookup_path('source', $path);
    if ($source) {
      $alias = $path;
    }
    elseif (strpos($path, $base_path) === 0) {
      $p = substr($path, strlen($base_path));
      $source = drupal_lookup_path('source', $p);
      if ($source) {
        $alias = $p;
      }
    }
    if (!$source) {
      $source = $path;
    }

    $item = menu_get_item($source);
    if (empty($item) && (strpos($source, $base_path) === 0)) {
      $source = substr($source, strlen($base_path));
      $item = menu_get_item($source);
    }
    if ($item) {
      $params['system_path'] = $source;
      if (!empty($alias)) {
        $params['system_alias'] = $alias;
      } 
      else {
        $alias = drupal_lookup_path('alias', $source);
        if (!empty($alias)) {
          $params['system_alias'] = $alias;
        }         
      }
    }
    if (empty($urlc['scheme'])) {
      if (substr($params['location'], 0 , 1) == '/') {
        $params['location'] = $loc_root . $params['location'];
      }
      else {
        $params['location'] = $loc_root . '/' . $params['location'];
      }
    }
    
  }
  return $params; 
}

function intel_get_nid_from_path($url) {
  $nids = &Intel_Df::drupal_static(__FUNCTION__);

  if (!isset($nids)) {
    $nids = array();
  }
  if (isset($nids[$url])) {
    return $nids[$url];
  }
  $params = intel_parse_href($url);
  if (!empty($params['system_path']) && (substr($params['system_path'], 0, 5) == 'node/')) {
    $a = explode('/', $params['system_path']);
    $nids[$url] = (int)$a[1];
    return $nids[$url];
  }
  return FALSE;    
}

function intel_get_page_meta_callback($path) {
  return intel_get_node_meta_from_path($path);
}

function intel_get_node_meta_from_path($url) {
  if ($nid = intel_get_nid_from_path($url)) {    
    $meta = intel_get_node_meta($nid);
    $meta->intent = intel_get_page_intent($nid, 'nid');    
    return $meta;
  }
  return FALSE;
}

function intel_get_node_meta($nid) {
  $query = db_select('node', 'n')
    ->fields('n')
    ->condition('nid', $nid);
  return $query->execute()->fetchObject();  
}

function intel_get_node_created($url) {
  if ($nid = intel_get_nid_from_path($url)) {
    $query = db_select('node', 'n')
      ->fields('n', array('created'))
      ->condition('nid', $nid);
    return $query->execute()->fetchField();    
  }
  return FALSE;
}

function intel_get_node_title($url) {
  $params = intel_parse_href($url);
  if (!empty($params['system_path']) && (substr($params['system_path'], 0, 5) == 'node/')) {
    $a = explode('/', $params['system_path']);
    $nid = $a[1];
    $query = db_select('node', 'n')
      ->fields('n', array('title'))
      ->condition('nid', $nid);
    return $query->execute()->fetchField();
  }
  return FALSE;    
}
/**
 * Returns the page intent of a url
 * TODO change this so that page intent is based on GA page attribute
 * @param unknown_type $id
 * @param unknown_type $id_type
 * @param unknown_type $return
 */
function intel_get_page_intent($id, $id_type = '', $return = 'key') {
  $intents = intel_get_page_intents();
  $key = '';
  if (is_string($id) || ($id_type == 'url')) {
    $params = intel_parse_href($id);
    if ($params['system_path']) {
      if ((substr($params['system_path'], 0, 5) == 'node/')) {
        $a = explode('/', $params['system_path']);
        $nid = $a[1];
        $node_meta = intel_get_node_meta($nid);
      }
      elseif (path_is_admin($params['system_path'])) {
        $key = 'a';
      }
    }
    
  }
  elseif (is_int($id) || $id_type == 'nid') {
     $node_meta = intel_get_node_meta($id);
  }
  elseif (is_object($id) || $id_type == 'node') {
     $node_meta = $id;
  }
  if (empty($node_meta)) {
    return FALSE;
  }
  if (
    ($node_meta->type == 'enterprise_blog')
    || ($node_meta->type == 'enterprise_audio') 
    || ($node_meta->type == 'enterprise_video')
    || ($node_meta->type == 'enterprise_wiki')
  ) {
    $key =  't';
  }
  elseif (($node_meta->type == 'enterprise_landingpage') || ($node_meta->type == 'webform')) {
    $key =  'l';
  }
  elseif ($node_meta->type == 'enterprise_thankyou') {
    $key =  'u';
  }
  else {
    $key = 'i';
  }
  drupal_alter('intel_page_intent_key', $key, $id, $id_type);
  if ($return == 'key') {
    return $key;
  }
  else {
    return $intents[$key];
  }
}


/**
 * Adds http_build_url if does not exist
 * URL constants as defined in the PHP Manual under "Constants usable with
 * http_build_url()".
 *
 * @see http://us2.php.net/manual/en/http.constants.php#http.constants.url
 */
if (!defined('HTTP_URL_REPLACE')) {
  define('HTTP_URL_REPLACE', 1);
}
if (!defined('HTTP_URL_JOIN_PATH')) {
  define('HTTP_URL_JOIN_PATH', 2);
}
if (!defined('HTTP_URL_JOIN_QUERY')) {
  define('HTTP_URL_JOIN_QUERY', 4);
}
if (!defined('HTTP_URL_STRIP_USER')) {
  define('HTTP_URL_STRIP_USER', 8);
}
if (!defined('HTTP_URL_STRIP_PASS')) {
  define('HTTP_URL_STRIP_PASS', 16);
}
if (!defined('HTTP_URL_STRIP_AUTH')) {
  define('HTTP_URL_STRIP_AUTH', 32);
}
if (!defined('HTTP_URL_STRIP_PORT')) {
  define('HTTP_URL_STRIP_PORT', 64);
}
if (!defined('HTTP_URL_STRIP_PATH')) {
  define('HTTP_URL_STRIP_PATH', 128);
}
if (!defined('HTTP_URL_STRIP_QUERY')) {
  define('HTTP_URL_STRIP_QUERY', 256);
}
if (!defined('HTTP_URL_STRIP_FRAGMENT')) {
  define('HTTP_URL_STRIP_FRAGMENT', 512);
}
if (!defined('HTTP_URL_STRIP_ALL')) {
  define('HTTP_URL_STRIP_ALL', 1024);
}
if (!function_exists('http_build_url')) {
  /**
   * Build a URL.
   *
   * The parts of the second URL will be merged into the first according to
   * the flags argument.
   *
   * @param mixed $url     (part(s) of) an URL in form of a string or
   *                       associative array like parse_url() returns
   * @param mixed $parts   same as the first argument
   * @param int   $flags   a bitmask of binary or'ed HTTP_URL constants;
   *                       HTTP_URL_REPLACE is the default
   * @param array $new_url if set, it will be filled with the parts of the
   *                       composed url like parse_url() would return
   * @return string
   */
  function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = array())
  {
    is_array($url) || $url = parse_url($url);
    is_array($parts) || $parts = parse_url($parts);
    isset($url['query']) && is_string($url['query']) || $url['query'] = null;
    isset($parts['query']) && is_string($parts['query']) || $parts['query'] = null;
    $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');
    // HTTP_URL_STRIP_ALL and HTTP_URL_STRIP_AUTH cover several other flags.
    if ($flags & HTTP_URL_STRIP_ALL) {
      $flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS
        | HTTP_URL_STRIP_PORT | HTTP_URL_STRIP_PATH
        | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT;
    } elseif ($flags & HTTP_URL_STRIP_AUTH) {
      $flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS;
    }
    // Schema and host are alwasy replaced
    foreach (array('scheme', 'host') as $part) {
      if (isset($parts[$part])) {
        $url[$part] = $parts[$part];
      }
    }
    if ($flags & HTTP_URL_REPLACE) {
      foreach ($keys as $key) {
        if (isset($parts[$key])) {
          $url[$key] = $parts[$key];
        }
      }
    } else {
      if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
        if (isset($url['path']) && substr($parts['path'], 0, 1) !== '/') {
          // Workaround for trailing slashes
          $url['path'] .= 'a';
          $url['path'] = rtrim(
              str_replace(basename($url['path']), '', $url['path']),
              '/'
            ) . '/' . ltrim($parts['path'], '/');
        } else {
          $url['path'] = $parts['path'];
        }
      }
      if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
        if (isset($url['query'])) {
          parse_str($url['query'], $url_query);
          parse_str($parts['query'], $parts_query);
          $url['query'] = http_build_query(
            array_replace_recursive(
              $url_query,
              $parts_query
            )
          );
        } else {
          $url['query'] = $parts['query'];
        }
      }
    }
    if (isset($url['path']) && $url['path'] !== '' && substr($url['path'], 0, 1) !== '/') {
      $url['path'] = '/' . $url['path'];
    }
    foreach ($keys as $key) {
      $strip = 'HTTP_URL_STRIP_' . strtoupper($key);
      if ($flags & constant($strip)) {
        unset($url[$key]);
      }
    }
    $parsed_string = '';
    if (!empty($url['scheme'])) {
      $parsed_string .= $url['scheme'] . '://';
    }
    if (!empty($url['user'])) {
      $parsed_string .= $url['user'];
      if (isset($url['pass'])) {
        $parsed_string .= ':' . $url['pass'];
      }
      $parsed_string .= '@';
    }
    if (!empty($url['host'])) {
      $parsed_string .= $url['host'];
    }
    if (!empty($url['port'])) {
      $parsed_string .= ':' . $url['port'];
    }
    if (!empty($url['path'])) {
      $parsed_string .= $url['path'];
    }
    if (!empty($url['query'])) {
      $parsed_string .= '?' . $url['query'];
    }
    if (!empty($url['fragment'])) {
      $parsed_string .= '#' . $url['fragment'];
    }
    $new_url = $url;
    return $parsed_string;
  }
}