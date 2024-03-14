<?php

class PcPage {

	function __construct($id, $pageId) {
		global $wpdb;
		$this->id     = $id;
		$this->pageId = $pageId;

		$wpdb->query('CREATE TABLE IF NOT EXISTS pc_list_item_instance ('
		             . 'id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,'
		             . 'page_id INT(11) NOT NULL,'
		             . 'list_item_id INT(11) NOT NULL,'
		             . 'status INT(11) NOT NULL);');

		// set up a foreign key to connect with pc_list_item id
	}

	public function markItemComplete($itemId) {
		global $wpdb;

		// check if an unchecked record exists for the page/item ids
		$rowCount = $wpdb->query($wpdb->prepare(
			'SELECT * FROM pc_list_item_instance WHERE page_id = %d AND list_item_id = %d',
			$this->pageId,
			$itemId
		));

		if ($rowCount > 0) {
			// update
			$wpdb->get_results($wpdb->prepare(
				'UPDATE pc_list_item_instance SET status = %d WHERE page_id = %d AND list_item_id = %d',
				1,
				$this->pageId,
				$itemId
			));
		} else {
			// create

			// TODO TEST THIS SHIT OUT
			$results = $wpdb->query($wpdb->prepare(
				'INSERT INTO pc_list_item_instance (page_id, list_item_id, status) VALUES (%d, %d, 1)',
				$this->pageId,
				$itemId
			));

			$results = $wpdb->get_results('SELECT * FROM pc_list_item WHERE id = LAST_INSERT_ID()');
		}

		// else, create a new record for the page/item ids
	}

	public function markItemIncomplete($itemId) {
		global $wpdb;

		// check if an unchecked record exists for the page/item ids
		$rowCount = $wpdb->query($wpdb->prepare(
			'SELECT * FROM pc_list_item_instance WHERE page_id = %d AND list_item_id = %d',
			$this->pageId,
			$itemId
		));

		if ($rowCount > 0) {
			// update
			$wpdb->get_results($wpdb->prepare(
				'UPDATE pc_list_item_instance SET status = %d WHERE page_id = %d AND list_item_id = %d',
				0,
				$this->pageId,
				$itemId
			));
		}
	}

	//
	private function updateItem() {

	}

	public function getItems () {

	}
}

?>