<?php

class ControllerOverview_cf7b {

  public $view;
  public $plugin_dir;
  /* Constructor */
  public function __construct() {
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/views/overview.php') );
    $this->view = new ViewOverview_cf7b();

    $this->view->display();

  }
}