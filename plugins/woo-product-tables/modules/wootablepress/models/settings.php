<?php
/**
 * Class contain table settings api
 */

/**
 * Class contain table settings api
 * You can use it in any part of your code with construction
 * FrameWtbp::_()->getModule('wootablepress')->getModel('settings');
 */

class SettingsModelWtbp extends ModelWtbp {
	/**
	 * Get table settings
	 *
	 * @param int $filterId
	 *
	 * @return array
	 */
	public function getTableSettings( $tableId ) {
		$settings = array();
		$table = FrameWtbp::_()->getModule('wootablepress')->getModel('wootablepress')->getById($tableId);

		if (!$table) {
			return $settings;
		}

		$settings = FrameWtbp::_()->getModule('wootablepress')->unserialize($table['setting_data']);

		if (!empty($settings['settings'])) {
			$settings = $settings['settings'];
		}

		return $settings;
	}

	/**
	 * Get table column order in table
	 * Except column order we have some settings specific to individual table column
	 * that we keep in order settings too
	 *
	 * @param array $tableSettings
	 * @param int $tableId
	 *
	 * @return array
	 */
	public function getTableOrder( $tableSettings = array(), $tableId = 0 ) {
		$order = array();

		if (!$tableSettings) {
			$tableSettings = $this->getTableSettings($tableId);
		}

		if (!$tableSettings) {
			return $order;
		}

		if (!empty($tableSettings['order'])) {
			$order = UtilsWtbp::jsonDecode($tableSettings['order']);
		}

		return $order;
	}

	/**
	 * Get specific column in table order
	 *
	 * @param array $tableOrder
	 * @param array $tableSettings
	 * @param int $tableId
	 *
	 * @return array
	 */
	public function getColumnInOrder( $columnSlug, $tableOrder = array(), $tableSettings = array(), $tableId = 0  ) {
		$column = array();

		if (!$tableOrder) {
			$tableOrder = $this->getTableOrder($tableSettings, $tableId);
		}

		$index = array_search($columnSlug, array_column($tableOrder, 'slug'));

		if (false !== $index) {
			$column = $tableOrder[$index];
		}

		return $column;
	}
}
