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
require_once 'class.exception.php';

class ApiTracker {

	protected $apiClient;
  protected $domain;
  protected $vtk;
  protected $uid;
  protected $cid;

  public function __construct($apiClientProperties = array()) {
    $this->apiClient = new ApiClient($apiClientProperties);
  }

  public function setDomain($domain) {
    $this->domain = $domain;
  }

  public function setVtk($vtk) {
    $this->vtk = $vtk;
  }

  public function setUid($uid) {
    $this->uid = $uid;
  }

  public function setCid($cid) {
    $this->cid = $cid;
  }

  public function sessionInit($event) {
    $endpoint = 'session/init';
    $params = array(
      't' => REQUEST_TIME,
    );

    try {
      $ret = $this->apiClient->getJSON($endpoint, $params);
      return $ret;
    }
    catch (Exception $e) {
      throw new Exception('Unable to trackEvent: ' . $e);
    }
  }

  /**
   * @param $event : either array
   * @param $action
   * @param null $value
   * @param null $noninteraction
   * @return array|bool|mixed|string
   * @throws Exception
   */
  public function sendEvent($event) {
    $endpoint = 'tracker/event';

    $params = array(
      'domain' => $this->domain,
    );
    $params += $event;
    if (!empty($this->vtk)) {
      $params['vtk'] = $this->vtk;
    }
    if (!empty($this->uid)) {
      $params['uid'] = $this->uid;
    }
    if (!empty($this->cid)) {
      $params['cid'] = $this->cid;
    }
    try {
      $ret = $this->apiClient->getJSON($endpoint, $params);
      return $ret;
    }
    catch (Exception $e) {
      throw new Exception('Unable to trackEvent: ' . $e);
    }
  }

  /**
   * @param $event : either array
   * @param $action
   * @param null $value
   * @param null $noninteraction
   * @return array|bool|mixed|string
   * @throws Exception
   */
  public function trackEvent($event) {
    $endpoint = 'tracker/trackevent';

    $params = array(
      'domain' => $this->domain,
    );
    $params += $event;
    if (!empty($this->vtk)) {
      $params['vtk'] = $this->vtk;
    }
    try {
      $ret = $this->apiClient->getJSON($endpoint, $params);
      return $ret;
    }
    catch (Exception $e) {
      throw new Exception('Unable to trackEvent: ' . $e);
    }
  }
	
  public function __toString() {
    return 'ApiTracker: ' . $this->$tid;
  }
}