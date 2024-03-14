<?php

abstract class Zopim_Base_View
{
  protected $_messages = array();

  public function __construct()
  {
    $this->set_messages();
  }

  // Force inheriting classes to set messages
  abstract protected function set_messages();

  public function get_message( $key )
  {
    return $this->_messages[ $key ];
  }
}
