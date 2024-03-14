<?php
class TableTablesWtbp extends TableWtbp {
	public function __construct() {
		$this->_table = '@__tables';
		$this->_id = 'id';
		$this->_alias = 'wtbp_tables';
		$this->_addField('id', 'text', 'int')
			 ->_addField('title', 'text', 'varchar')
			 ->_addField('meta', 'text', 'text')
			 ->_addField('setting_data', 'text', 'text');
	}
}
