<?php
namespace LevelTen\Intel\Realtime;
use PDO;
/**
 * @file
 * @author  Tom McCracken <tomm@getlevelten.com>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */

file_include('settings.php');
global $settings, $db, $db_tableprefix, $exec_mode;

/**
 * decorator class to enable JIT db connection logic
 * @author TomMcCracken
 *
 */
class ApiPDO {
  private $db = null;
  private $dsn;
  private $username;
  private $password;
  private $options;
  private $attributes = array();
  
  public function __construct($dsn, $username = null, $password = null, $options = null) {
    $this->dsn = $dsn;
    $this->username = $username;
    $this->password = $password;
    $this->options = $options;
  }
  
  private function init() {
    try {
      $this->db = new PDO($this->dsn, $this->username, $this->password);
    }
    catch (PDOException $e) {
      return_error_set(STATUS_SERVER_ERROR, "Database connection error: " . $e->getMessage());
      return;
    }
    foreach ($this->attributes AS $key => $value) {
      $this->db->setAttribute($key, $value);
    }
  }
  
  function setAttribute($key, $value) {
    $this->attributes[$key] = $value;
  }
  
  function __call($method_name, $args) {
    if (!isset($this->db)) {
      $this->init();
    }
    return call_user_func_array(array($this->db, $method_name), $args);
  }    
}

if (isset($settings['db']['dsn'])) {
  $db = new ApiPDO($settings['db']['dsn'], $settings['db']['username'], $settings['db']['password']);
  //$db = new PDO($db_settings['dsn'], $db_settings['username'], $db_settings['password'], array(PDO::ATTR_PERSISTENT => true));

  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $db_tableprefix = $settings['db']['tableprefix'];
}
else {
  return_error_set(STATUS_SERVER_ERROR, "Database settings not set for host.");
  return;
}



/****
 * DB modes
 * 0 = no access
 * r = read only (no saving)
 * w = read/write - check if record exists before writing
 * f = read/write - don't check 
 * 
 */