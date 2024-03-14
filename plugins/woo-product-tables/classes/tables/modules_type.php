<?php
class TableModules_TypeWtbp extends TableWtbp {
	public function __construct() {
		$this->_table = '@__modules_type';
		$this->_id = 'id';     /*Let's associate it with posts*/
		$this->_alias = 'sup_m_t';
		$this->_addField($this->_id, 'text', 'int', '', esc_html__('ID', 'woo-product-tables'))->
				_addField('label', 'text', 'varchar', '', esc_html__('Label', 'woo-product-tables'), 128);
	}
}
