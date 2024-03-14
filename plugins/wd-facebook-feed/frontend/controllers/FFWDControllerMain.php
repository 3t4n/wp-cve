<?php

class FFWDControllerMain {

	private $view;

  public function __construct($params = array(), $from_shortcode = 0, $ffwd = 0, $view = '') {
    $this->view = $view;
    $this->display($params, $from_shortcode, $ffwd);
  }

  public function display($params, $from_shortcode = 0, $ffwd = 0) {
		require_once WD_FFWD_DIR . "/frontend/models/FFWDModelMain.php";
		$model_name = "FFWDModel" . $this->view;
    $view_name = "FFWDView" . $this->view;
		require_once WD_FFWD_DIR . "/frontend/views/".$view_name.".php";
		require_once WD_FFWD_DIR . "/frontend/models/".$model_name.".php";
    $model = new $model_name();
    $view = new $view_name($model);
    $view->display($params, $from_shortcode, $ffwd);
  }
}