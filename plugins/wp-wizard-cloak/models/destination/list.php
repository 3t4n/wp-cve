<?php

class PMLC_Destination_List extends PMLC_Model_List {
	
	public function __construct() {
		parent::__construct();
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'destinations');
	}
}