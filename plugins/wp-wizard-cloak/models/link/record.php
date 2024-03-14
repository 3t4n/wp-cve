<?php

class PMLC_Link_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'links');
	}
	
	/**
	 * Create unique slug based on `name`
	 * @return string
	 */
	public function generateSlug() {
		$slug = '';
		if ($this->name) {
			strlen($slug = trim(preg_replace('%[^a-z0-9]+%', '-', strtolower($this->name)), '-')) or $slug = '1';
			$check = new self(); $alt = 1;
			while ( ! $check->getBy(array('slug LIKE' => $slug) + ( ! empty($this->id) ? array('id <>' => $this->id) : array()))->isEmpty()) {
				$slug = trim(preg_replace('%((^|-)\d+)?$%', "-$alt", $slug), '-');
				$alt++;
			}
			$this->slug = $slug;
		}
		return $slug;
	}
	/**
	 * Return full url which corresponds to this link
	 */
	public function getUrl($sub_id = NULL) {
		if ( ! empty($this->slug)) {
			if (PMLC_Plugin::getInstance()->isPermalinks()) {
				$url = site_url(PMLC_Plugin::getInstance()->getOption('url_prefix') . '/' . $this->slug);
				if ( ! (is_null($sub_id) or '' === $sub_id)) {
					$url .= '/' . $sub_id;
				}
			} else {
				$url = add_query_arg('cloaked', $this->slug, site_url('/'));
				if ( ! (is_null($sub_id) or '' === $sub_id)) {
					$url = add_query_arg('subid', $sub_id, $url);
				}
			}
			return $url;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get related rule for type specified
	 * @param string[optinal] $type
	 * @param string[optional] $ruleVal
	 * @return PMLC_Rule_Record
	 */
	public function getRule($type = NULL, $ruleVal = '') {
		is_null($type) and $type = $this->destination_type;
		$rule = new PMLC_Rule_Record();
		if (isset($this->id)) {
			$rule->getBy(array('type' => $type, 'link_id' => $this->id, 'rule' => $ruleVal));
		}
		return $rule;
	}
	
	/**
	 * Return Tracking code for the link
	 * @param string $type Tracking code type: `header` or `footer`
	 */
	public function getTrackingCode($type) {
		$html = '';
		$field = $type . '_tracking_code';
		if ( ! $this->no_global_tracking_code) {
			$html .= PMLC_Plugin::getInstance()->getOption($field);
		}
		$html .= $this->$field;
		return $html;
	}
	
	/**
	 * Fill current link with values from specified preset link
	 * @param PMLC_Link_Record $preset Preset to apply
	 * @param bool[optional] $is_rewrite Whether to rewrite not empty fields
	 * @return bool
	 */
	public function applyPreset(PMLC_Link_Record $preset, $is_rewrite = FALSE) {
		if ($preset->id != $this->id) {
			$this->getBy(array_intersect_key($this->toArray(TRUE), array_flip($this->primary))); // get data from database
			// copy ordinary fields
			foreach ($preset as $field => $value) {
				if ( ! in_array($field, $this->primary) and $field != 'preset' and ($is_rewrite or '' === $this->$field)) {
					$this->$field = $value;
				}
			}
			$this->update();
			
			if ($is_rewrite) {
				// delete all rules link has
				foreach ($this->getRelated('PMLC_Rule_List') as $rule) {
					$rule->delete();
				}
				// delete all automatches link has
				foreach ($this->getRelated('PMLC_Automatch_List') as $automatch) {
					$automatch->delete();
				}
			}
			// copy destination sets
			foreach ($preset->getRelated('PMLC_Rule_List') as $preset_rule) {
				if ($this->getRule($preset_rule->type, $preset_rule->rule)->isEmpty()) {
					$preset_rule->copyToLink($this->id);
				}
			}
			// copy automatches if those are empty for target
			$automatches = new PMLC_Automatch_List();
			if (0 == $automatches->countBy('link_id', $this->id)) {
				$automatch = new PMLC_Automatch_Record();
				foreach ($preset->getRelated('PMLC_Automatch_List') as $preset_automatch) {
					$automatch->clear()->set(array(
						'link_id' => $this->id,
						'url' => $preset_automatch->url,
					))->insert();
				}
			}
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Reset all statistic for the link
	 * @return PMLC_Link_Record
	 * @chainable
	 */
	public function clearStats() {
		$stat = new PMLC_Stat_Record();
		$this->wpdb->query($this->wpdb->prepare('DELETE FROM ' . $stat->getTable() . ' WHERE link_id = %s', $this->id));
		return $this;
	}
	
	/**
	 * @see parent::delete()
	 */
	public function delete() {
		// cascade deletion
		$this->clearStats();
		foreach ($this->getRelated('PMLC_Rule_List') as $rule) {
			$rule->delete();
		}
		return parent::delete();
	}
	
}