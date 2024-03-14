<?php
class InlineTypeReadMore extends ReadMore {
	
	public function __construct() {
		add_filter('yrmDefaultOptions', array($this, 'defaultOptions'), 1, 1);
	}
	
	public function defaultOptions($defaultData) {
		$defaultData['btn-background-color'] = '';
		return $defaultData;
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
			'btn-dimension-mode' => 1
		);
	}

	public static function params() {

		$data = array();

		return $data;
	}

	public function includeOptionsBlock($dataObj) {

	}
}