<?php

namespace platy\etsy\api;

/**
 *  HTTP request utilities.
 *
 * @author Rhys Hall hello@rhyshall.com
 */
class RequestUtil {

  /**
   * Prepares the request query parameters.
   *
   * @param array $params
   * @return string
   */
  public static function prepareParameters(array $params) {
    // $query = [];
    // foreach($params as $key => $value){
    //   $query[]= $key . "=" . $value;
    // }
    return \http_build_query($params);
  }

  /**
   * Prepares any files in the POST data. Expects a path for files.
   *
   * @param array $params
   * @return array
   */
  public static function prepareFile(array $params) {
    if(!isset($params['image']) && !isset($params['file'])) {
      return false;
    }
    $type = isset($params['image']) ? 'image' : 'file';
    $ret = [
      [
        'name' => $type,
        'contents' => fopen($params[$type], 'r')
      ]
    ];
    unset($params[$type]);
    foreach($params as  $key => $value) {
      $ret[] = [
        'name' => $key,
        'contents' => $value
      ];
    }
    return $ret;
  }

  /**
   * Returns a query string as an array.
   *
   * @param string $query
   * @return array
   */
  public static function getParamaters($query) {
    parse_str($query, $params);
    return $params;
  }

}