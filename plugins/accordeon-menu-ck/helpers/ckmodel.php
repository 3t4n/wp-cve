<?php
/**
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */
Namespace Accordeonmenuck;

// No direct access.
defined('CK_LOADED') or die;

require_once 'ckfof.php';

class CKModel {

	var $_item = null;

	private $input;

	protected $tableName;

	function __construct() {
		$this->input = new CKInput();
	}

	// public function &getData($id = null) {
		// global $wpdb;
		// if ($this->_item === null) {
			// if ($id) {
				// $this->_item = new stdClass();
				// $this->_item = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'accordeonmenuck_menus WHERE id=' . (int) $id);
				// $this->_item->params =  new CKParams(unserialize($this->_item->params));
			// } else {
				// $this->_item = new stdClass();
				// $this->_item->id = 0;
				// $this->_item->title = '';
				// $this->_item->state = '1';
				// $this->_item->params = new CKParams();
				// $this->_item->type = '';
			// }
		// }

		// return $this->_item;
	// }

	public function save($data) {
		/*global $wpdb;
		$id = (int) $data['id'];
		$table = $wpdb->prefix . 'accordeonmenuck_menus';
		$ck_post = array(
			'id' => $id,
			'title' => $data['title'],
			'state' =>  (int)$data['state'],
			'params' => serialize($data['params']),
			'type' => $data['type'],
		);
		$format = $this->getPostFormat();

		// save the post into the database
		// $wpdb->show_errors();
		if ($id === 0) {
			$save = $wpdb->insert( $table, $ck_post, $format );
			$ck_post_id = $wpdb->insert_id;

		} else {
			$where = array( 'id' => $id );
			$save = $wpdb->update( $table, $ck_post, $where, $format );
			$ck_post_id = $id;
		}
		// $wpdb->print_error();

		$return = $ck_post_id;

		return $return;*/
	}

	// public function getPostFormat() {
		// $format = array( 
			// '%d',
			// '%s',
			// '%d',
			// '%s',
			// '%s'
		// );

		// return $format;
	// }

	// public function getPostData($data) {
		// $post = array(
			// 'id' => (int) $data['id'],
			// 'name' => $data['name'],
			// 'state' => $data['state'],
			// 'params' => $data['params'],
			// 'type' => $data['type']
		// );

		// return $post;
	// }

	// public function copy($id) {
		// global $wpdb;

		// $query = 'SELECT * FROM ' . $wpdb->prefix . 'accordeonmenuck_menus WHERE id=' . (int) $id;

		// $this->_item = $wpdb->get_row($query);
		// $format = $this->getPostFormat();
		// $post = $this->getPostData(0, $this->_item->title . '-copy', 0, $this->_item->params, $this->_item->type);
		// return $wpdb->insert( $wpdb->prefix . 'accordeonmenuck_menus', $post, $format );
	// }

	public function delete($id) {
		return CKFof::dbDelete( $this->tableName, $id );
	}
}