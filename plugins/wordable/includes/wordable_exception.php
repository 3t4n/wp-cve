<?php

class WordableException extends Exception {
  private $meta;

  public function __construct($message, $meta = array()) {
    $this->meta = $meta;
    parent::__construct($message);
  }

  public function getMeta() {
    return $this->meta;
  }
}
