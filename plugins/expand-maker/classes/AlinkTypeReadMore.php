<?php
class AlinkTypeReadMore extends ReadMore {
	
	public function __construct() {
		add_filter('yrmDefaultOptions', array($this, 'defaultOptions'), 1, 1);
		add_filter('yrmAllSavedOptions', array($this, 'allSavedOptions'), 1, 1);
	}
	
	public function defaultOptions($defaultData) {
		$defaultData['btn-background-color'] = '';
		$defaultData['add-button-next-content'] = '1';
		return $defaultData;
	}
	
	public function allSavedOptions($options) {
		$options['btn-background-color'] = '';
		$options['add-button-next-content'] = '1';
		return $options;
	}
	
	public function getRemoveOptions() {
		
		return array(
			'button-width' => 1,
			'button-height' => 1,
			'btn-background-color' => 1,
			'btn-border-radius' => 1,
			'button-border' => 1,
			'button-box-shadow' => 1,
			'btn-hover-bg-color' => 1,
			'btn-dimension-mode' => 1,
			'button-border-bottom' => 1
		);
	}
	
	public static function params() {
		
		$data = array();
		
		return $data;
	}
	
	public function includeOptionsBlock($dataObj) {
		wp_register_script('YrmLink', YRM_JAVASCRIPT.'YrmLink.js', array('readMoreJs', 'jquery-effects-core'), EXPM_VERSION);
		wp_enqueue_script('YrmLink');
		require_once(YRM_VIEWS_SECTIONS.'aLinkCutsomOptions.php');
	}
}