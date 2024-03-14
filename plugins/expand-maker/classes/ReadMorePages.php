<?php
Class ReadMorePages {

	public $functionsObj;
	public $readMoreDataObj;

	public function __construct() {
		
	}

	public function mainPage() {
		require_once(YRM_VIEWS.'readMorePagesView.php');
	}

	public function addNewPage() {
		require_once(YRM_VIEWS.'readMoreAddNew.php');
	}

	public function morePlugins() {
		require_once(YRM_VIEWS.'morePlugins.php');
	}

	public function support() {
		require_once(YRM_VIEWS.'support.php');
	}
	
	public function videoTutorials() {
		require_once(YRM_VIEWS.'videoTutorials.php');
	}

	public function help() {
		require_once(YRM_VIEWS.'help.php');
	}

	public function settings() {
		$functions = $this->functionsObj;
		require_once(YRM_VIEWS.'settings.php');
	}


	public function findAndReplace() {
		$functions = $this->functionsObj;
		require_once(YRM_VIEWS_FIND.'main.php');
	}

	public function accordionBuilder() {
		$functions = $this->functionsObj;
		require_once(YRM_VIEWS_ACCORDION.'main.php');
	}

	public function addNewButtons() {

		global $YrmRemoveOptions;

		$id = @(int)$_GET['readMoreId'];
		$type = 'button';

		if(!empty($_GET['yrm_type'])) {
			$type = esc_attr($_GET['yrm_type']);
		}

		$className = ucfirst($type).'TypeReadMore';

		$classPaths = YRM_CLASSES;
		global $YRM_TYPES;
		global $YRM_EXTENSIONS;

		if(!empty($YRM_TYPES[$type])) {
			$classPaths = $YRM_TYPES[$type];
		}
		$extensionsInfo = YrmConfig::extensions();
		if (in_array($type, $YRM_TYPES['customTypes'])) {
			$this->renderExtension($classPaths, $className);
			return;
		}
		if(in_array($type, $YRM_EXTENSIONS) && !empty($extensionsInfo[$type]) && empty($extensionsInfo[$type]['useMainOptions'])) {
			$this->renderExtension($classPaths, $className);
			return;
		}

		if(file_exists($classPaths.$className.'.php')) {
			require_once($classPaths.$className.'.php');
			$typeObj = new $className();
			$YrmRemoveOptions = $typeObj->getRemoveOptions();
		}
		$dataObj = $this->readMoreDataObj;
		$dataObj->setId($id);

		$savedObj = $dataObj;
        $typeObj->mainSavedObj = $savedObj;
		$dataParams = $dataObj->getOptionsData();
		$functions = $this->functionsObj;
		require_once(YRM_VIEWS."readMoreAddNewButton.php");
	}

	private function renderExtension($classPaths, $className) {

        $id = @(int)$_GET['readMoreId'];
        $dataObj = $this->readMoreDataObj;
        $dataObj->setId($id);

        $savedObj = $dataObj;
        $dataParams = $dataObj->getOptionsData();
        $functions = $this->functionsObj;

		if(file_exists($classPaths.$className.'.php')) {
			require_once($classPaths.$className.'.php');
			$typeObj = new $className($dataObj);
			if($typeObj instanceof ReadMoreTypes) {
				if(!empty($_GET['readMoreId'])) {
					$typeObj->setId($_GET['readMoreId']);
				}
				$typeObj->prepareSavedValue();
				$typeObj->functionsObj = $functions;
				$typeObj->renderView();
			}
		}
	}
}