<?php
class TableFavoritesWtbp extends TableWtbp {
	public function __construct() {
		$this->_table = '@__favorites';
		$this->_id = 'id';
		$this->_alias = 'wtbp_favorites';
		$this->_addField('user_id', 'hidden', 'int')
			 ->_addField('product_id', 'hidden', 'int')
			 ->_addField('from_order', 'hidden', 'int');
	}
}
