<?php
abstract class PMCS_Setting_Abstract {
	public $id = '';
	public $title = '';
	public function get_settings() {
		return array();
	}
	public function save() {
		return array();
	}
}
