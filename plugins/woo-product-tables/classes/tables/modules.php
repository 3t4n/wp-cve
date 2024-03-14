<?php
class TableModulesWtbp extends TableWtbp {
	public function __construct() {
		$this->_table = '@__modules';
		$this->_id = 'id';     /*Let's associate it with posts*/
		$this->_alias = 'sup_m';
		$this->_addField('label', 'text', 'varchar', 0, esc_html__('Label', 'woo-product-tables'), 128)
				->_addField('type_id', 'selectbox', 'smallint', 0, esc_html__('Type', 'woo-product-tables'))
				->_addField('active', 'checkbox', 'tinyint', 0, esc_html__('Active', 'woo-product-tables'))
				->_addField('params', 'textarea', 'text', 0, esc_html__('Params', 'woo-product-tables'))
				->_addField('code', 'hidden', 'varchar', '', esc_html__('Code', 'woo-product-tables'), 64)
				->_addField('ex_plug_dir', 'hidden', 'varchar', '', esc_html__('External plugin directory', 'woo-product-tables'), 255);
	}
}
