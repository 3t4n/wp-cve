<?php

/**
 * Class ListItem
 *
 * Database record
 * $id
 * $list_id
 * $description
 */
class ListItem {
	private $text = null;

	function __construct($id, $opts) {
		global $wpdb;

		$this->id = $id;
		$wpdb->query('CREATE TABLE IF NOT EXISTS pc_list_item (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, list_id INT(11), description VARCHAR(256) NOT NULL);');
	}

	/**
	 * Set the text value (does not save to database)
	 *
	 * @method setText
	 * @param $text
	 */
	public function setText($text) {
		$this->text = esc_html($text);
	}

	/**
	 * Return list items description
	 *
	 * @method getText
	 * @return {String}
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Return validity of model
	 *
	 * @method isValid
	 * @return Boolean
	 */
	public function isValid() {
		if ($this->getText() !== null) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Attempt to save the new or existing list item
	 *
	 * @method save
	 * @return Array
	 */
	public function save() {
		// check that text is set and is valid
		if ($this->isValid() === true) {

			if ($this->id) {
				// update
				$result = $this->_update();
			} else {
				// save
				$result = $this->_save();
			}

			return array('status' => 'success', 'data' => $result);
		} else {
			// return error
			return array('status' => 'invalid');
		}
	}

	/**
	 * Internal function for saving a valid list item
	 *
	 * @function _save
	 */
	protected function _save() {
		global $wpdb;

		// save the items
		$results = $wpdb->query($wpdb->prepare('INSERT INTO pc_list_item (description) VALUES (%s)', $this->getText()));
		$results = $wpdb->get_results('SELECT * FROM pc_list_item WHERE id = LAST_INSERT_ID()');

		return $results[0];
	}

	/**
	 * Internal function for updating a valid list item
	 *
	 * @function _update
	 */
	protected function _update() {
		global $wpdb;
		$itemId = $this->id;
		$newText = $this->getText();

		$updateResult = $wpdb->query($wpdb->prepare('UPDATE pc_list_item SET description = %s WHERE id = %d', $newText, $itemId));

		// if a row was actually effected
		if ($updateResult === 1) {
			$result = $wpdb->get_results($wpdb->prepare('SELECT * FROM pc_list_item WHERE id = %d', $this->id));
			return $result[0];
		}

		return null;
	}

	/**
	 * Deletes a list item
	 *
	 * @function delete
	 * @return Boolean True if a record was actually removed
	 */
	public function delete() {
		global $wpdb;

		$deleteStatement = $wpdb->prepare('DELETE FROM pc_list_item WHERE id = %d', $this->id);
		$results = $wpdb->query($deleteStatement);

		return ($results >= 1);
	}
}

?>