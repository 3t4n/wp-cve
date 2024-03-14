<?php
class TableColumnsWtbp extends TableWtbp {
	public function __construct() {
		$this->_table = '@__columns';
		$this->_id = 'id';
		$this->_alias = 'wtbp_columns';
		$this->_addField('id', 'text', 'int')
			 ->_addField('columns_name', 'text', 'text')
			 ->_addField('columns_nice_name', 'text', 'text')
			 ->_addField('columns_order', 'text', 'int')
			 ->_addField('is_default', 'text', 'int');
	}
}
