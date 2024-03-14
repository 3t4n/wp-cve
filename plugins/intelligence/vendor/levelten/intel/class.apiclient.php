<?php
/**
 * @file
 * @author  Tom McCracken <tomm@levelten.net>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */
namespace LevelTen\Intel;
require_once 'class.exception.php';

if (!empty($_GET['debug'])) {
  require_once 'libs/class.debug.php';
}

class ApiClient {
  protected $apikey;
  protected $tid;
  protected $apiUrl = 'http://api.getlevelten.com/v1/intel';
  protected $apiConnector = '';
  protected $apiUrlCallMethod = 'curl';
  protected $isTest = FALSE;
  protected $host;
  protected $path;
  protected $userAgent = 'LevelTen\Intel\ApiClient';
  protected $urlrewrite = 0;
  protected $curlSetOp = array();
  protected $contentType = 'application/x-www-form-urlencoded';
  protected $headers = array();
  protected $returnRawResponse = 0;
  const STATUS_OK = 200;
  const STATUS_BAD_REQUEST = 400;
  const STATUS_UNAUTHORIZED = 401;
  const STATUS_NOT_FOUND = 404;

  /**
  * Constructor.
  *
  * @param $HAPIKey: String value of HubSpot API Key for requests
  **/
  public function __construct($properties = array()) {
    foreach ($properties AS $prop => $value) {
      $this->$prop = $value;
    }
    $this->host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $this->path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
  }

  public function getJSON($endpoint, $params = array(), $data = array()) {
    if (!$this->apiUrl && !$this->apiConnector) {
      return FALSE;
    }
    if (!isset($params['tid']) && !empty($this->tid)) {
      $params['tid'] = $this->tid;
    }
    if (!isset($data['apikey']) && !empty($this->apikey)) {
      $data['apikey'] = $this->apikey;
    }
    $url = $this->getJSONUrl($endpoint, $params);

    $data_str = '';
    if (is_array($data) && count($data)) {
      foreach ($data AS $key => $value) {
        if (is_object($value)) {
          $value = json_encode($value);
        }
        else if (is_array($value)) {
          $value = json_encode($value);
        }
        $data_str .= $key . '=' . urlencode($value) . '&';
      }
      $data_str = substr($data_str, 0, -1);
    }
    if (!empty($_GET['debug'])) {
      //Debug::printVar($url);
      //Debug::printVar($params);
      //Debug::printVar($data);
    }
    if ($this->apiUrlCallMethod == 'none') {
      $retjson = '{}';
      $errno = 0;
    }
    else if ($this->apiUrlCallMethod == 'file_get_contents') {
      $retjson = file_get_contents($url);
      $errno = 0;
    }
    else if ($this->apiConnector) {
      $get = $params;
      $get['q'] = $endpoint;
      $get['return'] = 'data';
      if (!file_exists($this->apiConnector)) {
        throw new \Exception ('apiConnector file "' . $this->apiConnector . '" does not exists.');
      }
      include_once $this->apiConnector;
      $ret = \l10iapi\init($get, $data);
      return $ret;
    } else {
       // intialize cURL and send POST data
      $ch = curl_init();
      if ($data_str) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
      }
      else {
        curl_setopt($ch, CURLOPT_POST, false);
      }
      curl_setopt($ch, CURLOPT_URL, $url);
      if (!empty($this->apiUrl['httpauth_userpwd'])) {
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiUrl['httpauth_userpwd']);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
      curl_setopt($ch, CURLOPT_REFERER, $this->host . $this->path);
      //curl_setopt($ch, CURLOPT_URL, "");
      //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

      if (!empty($this->contentType)) {
        $this->headers[] = 'Content-Type: ' . $this->contentType;
      }

      // set headers
      if (!empty($this->headers) && is_array($this->headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
      }

      // set custom ops
      foreach ($this->curlSetOp as $k => $v) {
        // convert strings to CURLOPT values
        if (is_string($k)) {
          if (defined($k)) {
            curl_setopt($ch, constant($k), $v);
          }
        }
      }

      $response = curl_exec($ch);
      $errno = curl_errno($ch);
      $error = curl_error($ch);
      //$this->setLastStatusFromCurl($ch);

      // check if header was set to captured
      $header = '';
      if (!empty($this->curlSetOp['CURLOPT_HEADER'])) {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $response = substr($response, $header_size);
      }

      curl_close($ch);
    }
    if ($errno > 0) {
      throw new LevelTen_Service_Exception ('cURL error: ' . $error);
    } else {
      if ($this->returnRawResponse) {

        return $response;
      }
      $ret = json_decode($response, true);
      if (!empty($_GET['debug'])) {
        //Debug::printVar($ret);
      }
      if (empty($ret['status'])) {
        $msg = !empty($ret['message']) ? $ret['message'] : $response;
        $msg = (strlen($msg) > 210) ? substr($msg, 0, 200) . '...' : $msg;
        throw new LevelTen_Service_Exception ('API response error. returned: ' . $msg, $ret['status']);
      }
      else if ((int)$ret['status'] >= 400) {
        $msg = !empty($ret['message']) ? $ret['message'] : $response;
        if (!empty($ret['error']['message'])) {
          $msg = $ret['error']['message'];
        }
        throw new LevelTen_Service_Exception ($msg, $ret['status'], NULL, self::getResponseErrors($ret));
      }
      if ($header) {
        $ret['_header'] = $header;
      }
      return $ret;
    }
  }

  /**
  * Creates the url to be used for the api request
  *
  * @param endpoint: String value for the endpoint to be used (appears after version in url)
  * @param params: Array containing query parameters and values
  *
  * @returns String
  **/
  public function getJSONUrl($endpoint, $params) {
    $url = $this->apiUrl;
    $demark = '';
    if ($this->urlrewrite) {
      $url .= $endpoint;
      $demark = '?';
    }
    else {
      $url .= 'index.php?q=' . $endpoint;
      $demark = '&';
    }
    if (!empty($params)) {
      $url .= $demark . $this->encodeQueryParams($params);
    }
    return $url;
  }

  /**
   * Converts array into query string parameters
   *
   * @param array $params:
   */
  public function encodeQueryParams($params) {
    $str = array();
    foreach($params AS $k => $v) {
      $str[] = urlencode($k) . "=" . urlencode($v);
    }
    return implode("&", $str);
  }

  /**
  * Utility function used to determine if variable is empty
  *
  * @param s: Variable to be evaluated
  *
  * @returns Boolean
  **/
  protected function isBlank ($s) {
      if ((trim($s)=='')||($s==null)) {
          return true;
      } else {
          return false;
      }
  }

  /**
   * Sets the status code from a curl request
   *
   * @param resource $ch
   */
  protected function setLastStatusFromCurl($ch) {
      $info = curl_getinfo($ch);
      $this->lastStatus = (isset($info['http_code'])) ? $info['http_code'] : null;
  }

  protected function getResponseErrors($response) {
    if (!empty($response['error']['errors'])) {
      return $response['error']['errors'];
    }
    else {
      return array();
    }
  }

  /**
   * Get magic method to retrieve object properties using ::get[property]
   */
  public function __get( $key ) {
    return property_exists($this, $key) ? $this->$key : NULL;
  }

  /**
   * Set magic method to retrieve object properties using ::set[property]
   */
  public function __set( $key, $value ) {
    if (property_exists($this, $key)) {
      $this->$key = $value;
    }
  }
}