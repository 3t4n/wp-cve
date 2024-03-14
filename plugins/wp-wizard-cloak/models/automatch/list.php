<?php

class PMLC_Automatch_List extends PMLC_Model_List {
	
	public function __construct() {
		parent::__construct();
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'automatches');
	}
}