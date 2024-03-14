<?php
/**
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */

Namespace Accordeonmenuck;

// No direct access.
defined('CK_LOADED') or die;

/**
 * Accordeonmenuck model.
 */
class CKModelStyle extends CKModel {

	protected $tableName = '#__accordeonmenuck_styles';

	var $_item = null;

	function __construct() {
		parent::__construct();
	}

	public function &getData($id = null) {
		global $wpdb;
		if ($this->_item === null) {
			if ($id) {
				$this->_item = new \stdClass();
				$this->_item = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'accordeonmenuck_styles WHERE id=' . (int) $id);
			} else {
				$this->_item = new \stdClass();
				$this->_item->id = 0;
				$this->_item->name = '';
				$this->_item->state = '';
				$this->_item->params = '';
//				$this->_item->checked_out = '';
				$this->_item->layoutcss = '';
			}
		}

		return $this->_item;
	}

	public function save($data) {
		// load the object
		$row = CKFof::dbLoad($this->tableName, (int) $data['id'] );
		$row->id = $data['id'];
		$row->name = $data['name'];
		$row->state = $data['state'];
		$row->params = $data['params'];
		$row->layoutcss = $data['layoutcss'];

		$format = $this->getPostFormat();

		$id = CKFof::dbStore($this->tableName, $row, $format);

		return $id;
	}

	public function copy($id) {
		$row = CKFof::dbLoad($this->tableName, (int) $id );
		$format = $this->getPostFormat();
		$data = $this->getPostData(0, $row->name . '-copy', 0, $row->params, $row->layoutcss);

		$result = CKFof::dbStore( $this->tableName, $data, $format );
		return $result;
	}

	public function getPostFormat() {
		$format = array( 
			'%d', // id
			'%s', // name
			'%d', // state
			'%s', // params
//			'%d', // checked_out
			'%s'  // layoutcss
		);

		return $format;
	}

	public function getPostData($id = 0, $name = '', $state = 0, $params = '', $layoutcss = '', $checked_out = 0) {
		$post = array(
			'id' => (int) $id,
			'name' => sanitize_text_field($name),
			'state' => $state,
			'params' => $params,
//			'checked_out' => $checked_out,
			'layoutcss' => $layoutcss,
		);

		return $post;
	}
}