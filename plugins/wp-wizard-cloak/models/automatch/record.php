<?php

class PMLC_Automatch_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'automatches');
	}
	
	/**
	 * Find matchin url for a specified one
	 * @param string $url
	 * @return string
	 */
	public static function findMatch($url) {
		$automatch = new self();
		$links = new PMLC_Link_List();
		$links->setColumns($links->getFieldName('*'))
			->join($automatch->getTable(), $links->getFieldName('id') . ' = ' . $automatch->getFieldName('link_id'))
			->getBy(array(
				$automatch->getFieldName('url') . ' LIKE' => $url,
				$links->getFieldName('preset') . ' =' => '',
				$links->getFieldName('is_trashed') . ' =' => 0,
			), $links->getFieldName('id'));
		if ($links->convertRecords()->total() > 0) {
			return $links[0]->getUrl();
		}
		return $url; // fallback rule is to return original url
	}
}