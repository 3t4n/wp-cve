<?php

class CBroPermissionsSaveError extends Exception
{
  public function __construct($msg)
  {
    parent::__construct($msg);
  }
}

class AccessException extends Exception
{
  public function __construct($msg)
  {
    parent::__construct($msg);
  }
}

?>