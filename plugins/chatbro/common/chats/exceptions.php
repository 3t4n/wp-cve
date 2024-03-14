<?php

class CBroInvalidChat extends Exception {
  public function __construct() {
    parent::__construct('Invalid setting');
  }
}

class CBroChatsNotFound extends Exception {
  public function __construct($id = null) {
    $msg = 'Chats not found';

    if ($id)
      $msg .= ": {$id}";
    parent::__construct($msg);
  }
}

?>
