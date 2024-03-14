<?php

class FFWDControllerLicensing_ffwd {
  public function __construct() {
  }

  public function execute() {
    $task = ((isset($_POST['task'])) ? sanitize_text_field(stripslashes($_POST['task'])) : '');
    if ( $task != '' ) {
      if ( !WDWLibrary::verify_nonce('licensing_bwg') ) {
        die('Sorry, your nonce did not verify.');
      }
    }
    if ( method_exists($this, $task) ) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_FFWD_DIR . "/admin/views/FFWDViewLicensing_ffwd.php";
    $view = new FFWDViewLicensing_ffwd();
    $view->display();
  }
}