<?php

class TWL_Page_In_Page {

	protected $errors = array();

	protected $successes = array();
	
	protected function addError($s) {
		$this->errors[] = $s;
	}
	
	protected function addSuccess($s) {
		$this->successes[] = $s;
	}

	protected function hasErrors() {
		return !empty($this->errors);
	}
	
	protected function hasSuccess() {
		return !empty($this->successes);
	}
	
	protected  function getErrors() {
		return $this->errors;
	}
	
	protected function getSuccesses() {
		return $this->successes;
	}
	
	protected function resetMessges() {
		$this->errors = array();
		$this->successes = array();
	}

}
