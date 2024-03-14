<?php

/**
 * Class Themes_controller_wdi
 */
class Themes_controller_wdi {
  private $view;

  public function __construct() {
    require_once(WDI_DIR . "/admin/views/themes.php");
    $this->view = new Themes_view_wdi();
  }

  public function execute() {
    $this->view->display();
  }
}