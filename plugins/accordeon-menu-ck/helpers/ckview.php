<?php
Namespace Accordeonmenuck;

defined('CK_LOADED') or die;

class CKView {

	protected $view;

	protected $model;

	protected $input;

	public function __construct() {
		// check if the user has the rights to access this page
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		$this->input = new CKInput();
	}

	public function display($tpl = 'default') {
		// check if the user has the rights to access this page
//		if (!current_user_can('manage_options')) {
//			wp_die(__('You do not have sufficient permissions to access this page.'));
//		}

		require_once ACCORDEONMENUCK_PATH . '/views/' . strtolower($this->view) . '/tmpl/' . $tpl . '.php';
	}

	public function get($view, $func, $params) {
		$this->view = $view;
		$model = $this->getModel();
		$funcName = 'get' . ucfirst($func);
		return $model->$funcName($params);
	}

	public function getModel() {
		if (empty($this->model)) {
			require_once(ACCORDEONMENUCK_PATH . '/models/' . strtolower($this->view) . '.php');
			$className = '\Accordeonmenuck\CKModel' . ucfirst($this->view);
			$this->model = new $className;
		}
		return $this->model;
	}
}