<?php

class PMLC_Stat_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'stats');
	}
	
	
	/**
	 * @see PMLC_Model_Record::insert()
	 */
	public function insert() {
		parent::insert();
		$list = new PMLC_Stat_List();
		$list->sweepHistory();
		return $this;
	}
	
}