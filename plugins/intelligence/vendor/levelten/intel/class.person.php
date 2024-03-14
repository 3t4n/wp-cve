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

class ApiPerson {
	protected $pid;
	protected $email;
	protected $apiPerson;
	protected $apiClient;

  public function __construct($ids, $apiClientProperties = array()) {
    if (is_array($ids)) {
      foreach ($ids as $key => $id) {
        $this->$key = $id;
      }
    }
    $this->apiClient = new ApiClient($apiClientProperties);
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getEmail() {
    return $this->email;
  }

	
  public function load($params = array()){
  	$endpoint = 'person/load';
    if (empty($params['email'])) {
      $params['email'] = $this->email;
    }
  	try {
  	  $ret = $this->apiClient->getJSON($endpoint, $params);
      if (isset($ret['person'])) {
  	    $this->apiPerson = (object) $ret['person'];
      }
  		return $this->apiPerson;
  	} 
  	catch (Exception $e) {
  	  throw new Exception('API Error: ' . $e);
  	}
  }
  
  public function getPerson() {
    return $this->apiPerson;
  }
  
  public function getVar($scope, $namespace = '', $keys = '', $default = null) {
    require_once 'libs/class.intel_data.php';

    $data = IntelData::getVarData($this->apiPerson, $scope, $namespace);

    if (isset($data)) {
      return IntelData::getVar($data, $keys, $default);
    }
    return $default;
  }
  
  public function setVar($scope, $namespace, $keys, $value) {
    $data = $this->apiVisitor->{$scope . '_data'};
    if (empty($data[$namespace])) {
      return FALSE;
    }
    $data = $data[$namespace];
    require_once 'libs/class.intel_data.php';
    $data = IntelData::getVarData($this->apiPerson, $scope, $namespace);
    $this->apiVisitor->{$scope . '_data'}[$namespace] = IntelData::setVar($data, $keys, $value);
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
    if (isset($this->$name)) {
      return $this->$name;
    }
    return null;
  }

  public function __isset($name) {
    $v = $this->__get($name);
    return isset($v);
  }
  
  public function __set($name, $value) {
    return $this->$name = $value;
  }
    
  public function __unset($name) {
    if (isset($this->$name)) {
      unset($this->$name);
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