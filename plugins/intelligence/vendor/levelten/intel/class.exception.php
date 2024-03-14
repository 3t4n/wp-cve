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
/**
 * An exception for Intelligence errors
 *
 * @author Tom McCracken <tomm@levelten.net>
 */
class L10IntelException extends \Exception {}

class LevelTen_Service_Exception extends \Exception {
  /**
   * Optional list of errors returned in a JSON body of an HTTP error response.
   */
  protected $errors = array();

  /**
   * Sets a type of error
   * @var null
   */
  protected $type = NULL;

  /**
   * Override default constructor to add the ability to set $errors and a retry
   * map.
   *
   * @param string $message
   * @param int $code
   * @param Exception|null $previous
   * @param [{string, string}] errors List of errors returned in an HTTP
   * response.  Defaults to [].
   * @param array|null $retryMap Map of errors with retry counts.
   */
  public function __construct(
    $message,
    $code = 0,
    Exception $previous = null,
    $errors = array(),
    $options = array()
  ) {
    if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
      parent::__construct($message, $code, $previous);
    } else {
      parent::__construct($message, $code);
    }

    $this->errors = $errors;

    if (!empty($options['type'])) {
      $this->type = $options['type'];
    }
  }

  /**
   * An example of the possible errors returned.
   *
   * {
   *   "domain": "global",
   *   "reason": "authError",
   *   "message": "Invalid Credentials",
   *   "locationType": "header",
   *   "location": "Authorization",
   * }
   *
   * @return [{string, string}] List of errors return in an HTTP response or [].
   */
  public function getErrors() {
    return $this->errors;
  }

  public function getType() {
    return $this->type;
  }

  public function getResponse() {
    return $this->type;
  }
}