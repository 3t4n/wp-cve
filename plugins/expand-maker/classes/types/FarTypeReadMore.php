<?php

class FarTypeReadMore extends ReadMore {

	public function getDBData() {
		$id = $this->getSavedId();
		global $wpdb;
		$prepare = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.YRM_FIND_TABLE.' WHERE id=%d', $id);
		$res = $wpdb->get_row($prepare, ARRAY_A);
		if (!empty($res)) {
			$options = json_decode($res['options'], true);
			$savedData = array();
			$savedData['title'] = $res['title'];
			$savedData['enable'] = $res['enable'];
			$savedData += $options;
			$this->setSavedData($savedData);
		}
	}
	public function save() {

		$data = $this->getSanitizedData();

		$title = (!empty($data['yrm-find-title'])) ? $data['yrm-find-title']: '';
		$saveData = array(
			'title' => $title,
			'enable' => 1,
			'options' => json_encode($data)
		);
		$format = array('%s','%s', '%s');

		if (empty($data['yrm-id'])) {
			$id = $this->insert($saveData, $format);
		}
		else {
			$id = $data['yrm-id'];
			$this->update($saveData, $format, $id);
		}
		$this->setSavedId($id);
	}

	private function insert($data, $format) {
		global $wpdb;
		$wpdb->insert($wpdb->prefix.YRM_FIND_TABLE, $data, $format);

		return $wpdb->insert_id;
	}

	private function update($data, $format, $id) {
		global $wpdb;
		$where = array('id' => $id);
		$whereFormat = array('%d');
		$wpdb->update($wpdb->prefix.YRM_FIND_TABLE, $data, $where, $format, $whereFormat);
	}

	public function delete() {
		global $wpdb;

		$id = $this->getSavedId();
		$wpdb->delete($wpdb->prefix.YRM_FIND_TABLE, array('id' => $id));
	}

	public static function filterContent($content) {

		global $wpdb;
		$rules = $wpdb->get_results("SELECT * FROM ".sanitize_text_field($wpdb->prefix).YRM_FIND_TABLE, ARRAY_A);

		foreach ($rules as $rule) {
			$options = $rule['options'];
			$options = json_decode($options, true);

			if (!self::allowReplace($rule)) {
				continue;
			}

			$findName = $options['yrm-find-name'];
			$replaceName = $options['yrm-replace-name'];

			$content = str_replace($findName, $replaceName, $content);
		}

		return $content;
	}

	private static function allowReplace($rule) {
		$status = true;

		if (empty($rule) || $rule['enable'] == '0') {
			$status = false;
			return $status;
		}

		return apply_filters('yrmEnableFarReplace', $status, $rule);
	}
}