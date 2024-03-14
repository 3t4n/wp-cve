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

require_once 'class.apiclient.php';

class ApiVisitor {
	protected $vtk;
  protected $uid;
  protected $cid;
	public $apiVisitor;
	protected $apiClient;
	protected $cookies = array();
  protected static $cookieStrings;

  public function __construct($vtk = '', $apiClientProperties = array()) {
    $this->apiClient = new ApiClient($apiClientProperties);
    $this->vtk = ($vtk == '') ? $this->extractVtk() : $vtk;
    $this->cookies['va'] = $this->extractCookieVa();
  }
	
  public function load($params = array()){
  	$endpoint = 'visitor/load';
    if (empty($params['vtk'])) {
      $params['vtk'] = $this->vtk;
    }
  	try {
  	  $ret = $this->apiClient->getJSON($endpoint, $params);
      if (isset($ret['visitor'])) {
  	    $this->apiVisitor = (object) $ret['visitor'];
      }
  		return $this->apiVisitor;
  	} 
  	catch (Exception $e) {
  	  throw new Exception('Unable to load visitor: ' . $e);
  	}
  }

  public static function extractVtk() {
    $d = self::extractCookieVtk();
    return !empty($d['vtk']) ? $d['vtk'] : '';
  }

  public static function extractUserId() {
    $d = self::extractCookieVtk();
    return !empty($d['userId']) ? $d['userId'] : '';
  }

  public static function extractCid() {
    $cid = self::getCookie('_ga');
    if (empty($cid)) {
      return '';
    }
    $cid = explode('.', $cid);
    if (!empty($cid[2]) && !empty($cid[3])) {
      return $cid[2] . '.' . $cid[3];
    }
    return '';
  }

  public static function extractCookieVtk() {
    $cookie = self::getCookie('l10ivtk');
    $d = array();
    if (!empty($cookie)) {
      $cookie = explode('.', $cookie);
      $d['vtk'] = $cookie[0];
      if (isset($cookie[1])) {
        $d['userId'] = !empty($cookie[1]) ? $cookie[1] : $d['vtk'];
      }
    }
    return $d;
  }
  
  public static function extractCookieVa() {
    $cookie = self::getCookie('__utmv');
    if (!empty($cookie)) {
      $a = explode('3=va=', $cookie);
      if (empty($a[1])) {
        return array();
      }
      $a = explode('^', $a[1]);
      return self::unserializeCustomVar($a[0]);      
    }
    return array();
  }
  
  public function getVtk() {
    return $this->vtk;
  }

  public function getUid() {
    return $this->uid;
  }

  public function getCid() {
    return $this->cid;
  }
  
  public function getVisitor() {
    return $this->apiVisitor;
  }

  public function getVar($scope, $namespace = 'default', $keys = '', $default = null) {
    if (!isset($this->apiVisitor->{$scope . '_data'})) {
      return $default;
    }
    $data = $this->apiVisitor->{$scope . '_data'};
    if (empty($data[$namespace])) {
      return $default;
    }
    $data = $data[$namespace];
    require_once 'libs/class.intel_data.php';
    return IntelData::getVar($data, $keys, $default);
  }
  
  public function setVar($scope, $namespace, $keys, $value) {
    $data = $this->apiVisitor->{$scope . '_data'};
    if (empty($data[$namespace])) {
      return FALSE;
    }
    $data = $data[$namespace];
    require_once 'libs/class.intel_data.php';
    $this->apiVisitor->{$scope . '_data'}[$namespace] = IntelData::setVar($data, $keys, $value);
  }
  
  public function getFlags($scope = '') {
    static $cached;
    if (!empty($cached[$scope])) {
      return $cached;
    }
    $flags = array();
    if (!empty($this->apiVisitor_data['flags'])) {
      $flags += $this->apiVisitor_data['flags'];
    }
    if (!empty($this->session_data['flags'])) {
      $flags += $this->session_data['flags'];
    }
    if (!empty($_COOKIE['l10i_f'])) {
      $elms = $this->decodeCookieElements($_COOKIE['l10i_f']);
      if (is_array($elms)) {
        $flags += $elms;
      }
    }
    $cached[$scope] = $flags;
    return $flags;
  }
  
  public function getFlag($name, $scope = '') {
    $flags = $this->getFlags();
    if (!empty($flags[$name])) {
      return $flags[$name];
    }
    return null;
  }

  public static function getCookie ($name) {
    // initialize cookieStrings property if not already done
    self::setCookieStrings();
    return (isset(self::$cookieStrings[$name])) ? self::$cookieStrings[$name] : null;
  }

  private static function setCookieStrings() {
    if (isset(self::$cookieStrings)) {
      return;
    }
    $cookieAlt = 'SESSl10i';

    self::$cookieStrings = array();
    if (isset($_COOKIE) && is_array($_COOKIE)) {
      self::$cookieStrings = $_COOKIE;
      if (isset($_COOKIE[$cookieAlt])) {
        $c = json_decode($_COOKIE[$cookieAlt], true);
        //unset(self::$cookieStrings[$cookieAlt]);
        if (!empty($c) && is_array($c)) {
          self::$cookieStrings += $c;
        }
      }
    }
    if (isset($_SESSION) && isset($_SESSION['l10i_cookie']) && is_array($_SESSION['l10i_cookie'])) {
      self::$cookieStrings += $_SESSION['l10i_cookie'];
      unset($_SESSION['l10i_cookie']);
    }
  }
  
  public static function encodeCookieElements ($elements) {
    $str = '';
    foreach ($elements AS $k => $v) {
      if (!$k) {
        continue;
      }
      $str .= $k . '=' . $v . '^';
    }
    $str = substr($str, 0, -1);
    return $str;
  }
   
  public static function decodeCookieElements($str) {
    $a = explode('^', $str);
    $elements = array();
    foreach ($a AS $e) {
      $b = explode('=', $e);
      $elements[$b[0]] = $b[1];
    }
    return $elements;
  }
  
  public static function  unserializeCustomVar($str) {
    $str = urldecode($str);
    $obj = array();
    $a = explode('&', $str);
    foreach ($a AS $i => $v) {
      $b = explode('=', $v);
      if ($b[0] == '') {
        continue;
      }
      $k = explode('.', $b[0]);
      if ((count($k) > 1) && (!isset($obj[$k[0]]))) {
        $obj[$k[0]] = array();
      }
      if (count($b) == 2) {
        if (count($k) > 1) {
          $obj[$k[0]][$k[1]] = (float) $b[1];
        }
        else {
          $obj[$k[0]] = (float) $b[1];
        }
      }
      else {
        if (count($k) > 1) {
          $obj[$k[0]][$k[1]] = '';
        }
        else {
          $obj[$k[0]] = '';
        }
      }
    }
    return $obj;
  }
  
  public function __get($name) {
    // unserialize data if needed
    if (($name == 'data') && (is_string($this->data))) {
      $this->data = unserialize($this->data);
    }
    elseif (($name == 'ext_data') && (is_string($this->ext_data))) {
      $this->ext_data = unserialize($this->ext_data);
    }
    // return property if exists
    if (property_exists($this, $name)) {
      return $this->$name;
    }
    if (!empty($this->apiVisitor) && property_exists($this->apiVisitor, $name)) {
      return $this->apiVisitor->$name;
    }
    return null;
  }

  public function __isset($name) {
    $v = $this->__get($name);
    return isset($v);
  }
  
  public function __set($name, $value) {
    if (isset($this->$name)) {
      return $this->$name = $value;
    }
    if (isset($this->apiVisitor->$name)) {
      $this->apiVisitor->$name = $value;
    }
    return null;
  }
    
  public function __unset($name) {
    if (isset($this->$name)) {
      unset($this->$name);
    }
    if (isset($this->apiVisitor->$name)) {
      unset($this->apiVisitor->$name);
    }
  }
  
  public function __toString() {
    if (!empty($_GET['debug'])) {
      return print_r($this->apiVisitor, 1);
    }
    else {
      return 'Visitor: ' . $this->vtk;
    }
  }
}