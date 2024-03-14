<?php 
/**
 * Admin link add/edit page
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
class PMLC_Admin_Edit extends PMLC_Controller_Admin {
	
	/**
	 * Add / Edit link
	 */		
	public function index() {
		$this->data['id'] = $id = $this->input->getpost('id');
		$this->data['item'] = $item = new PMLC_Link_Record();
		if ($id and $item->getById($id)->isEmpty()) { // ID corresponds to no link
			wp_redirect(add_query_arg('page', 'pmlc-admin-links', admin_url('admin.php')));
			die();
		}
		
		// read/set link record properties
		if ( ! $id) {
			$item->set(array(
				'name' => '',
				'slug' => '',
				'preset' => '_temp',
				'redirect_type' => '301',
				'destination_type' => 'ONE_SET',
				'expire_on' => '0000-00-00',
				'forward_url_params' => 1,
				'no_global_tracking_code' => 0,
				'header_tracking_code' => '',
				'footer_tracking_code' => '',
			));
		}
		$this->data['post'] = $post = $this->input->post(array(
			'name' => $item->name,
			'slug' => $item->slug,
			'preset' => $item->preset,
			'redirect_type' => $item->redirect_type,
			'destination_type' => $item->destination_type,
			'expire_on' => $item->expire_on,
			'forward_url_params' => $item->forward_url_params,
			'no_global_tracking_code' => $item->no_global_tracking_code,
			'header_tracking_code' => $item->header_tracking_code,
			'footer_tracking_code' => $item->footer_tracking_code,
		));
		if ( ! $this->input->post('is_link_expires', 1)) {
			$this->data['post']['expire_on'] = $post['expire_on'] = '';
		}
		if ( ! $this->input->post('is_header_tracking_code', 1)) {
			$this->data['post']['header_tracking_code'] = $post['header_tracking_code'] = '';
		}
		if ( ! $this->input->post('is_footer_tracking_code', 1)) {
			$this->data['post']['footer_tracking_code'] = $post['footer_tracking_code'] = '';
		}
		
		$preset = $this->input->getpost('preset', '');
		if ( ! $this->input->post('is_save_as_preset', 1) or '_temp' == $preset) {
			$preset = '';
		}
		// read/set link destination rules
		$default_rules = array(
			'country' => array(),
			'destination_by_country' => array(),
			'rule_name' => array(),
			'rule_pattern' => array(),
			'destination_by_rule' => array(),
			'destination_url' => array(
				'ONE_SET' => array(''),
				'BY_COUNTRY' => array(),
				'BY_RULE' => array(),
				'EXPIRED' => array(''),
				'REFERER_MASK' => array(''), 
			),
		);
		$rules_by_country = new PMLC_Rule_List();
		$rules_by_rule = new PMLC_Rule_List();
		if ($id) {
			foreach (array('ONE_SET', 'EXPIRED', 'REFERER_MASK') as $type) {
				$default_rules['destination_url'][$type] = array($item->getRule($type)->getRelated('PMLC_Destination_Record')->getUrl());
			}
			$rules_by_country->getBy(array('link_id' => $item->id, 'type' => 'BY_COUNTRY'))->convertRecords();
			foreach ($rules_by_country as $r) {
				$default_rules['country'][] = $r->rule;
				$default_rules['destination_by_country'][] = $r->id;
				$default_rules['destination_url']['BY_COUNTRY'][] = $r->getRelated('PMLC_Destination_Record')->getUrl();
			}
			$rules_by_rule->getBy(array('link_id' => $item->id, 'type' => 'BY_RULE'))->convertRecords();
			foreach ($rules_by_rule as $r) {
				list($default_rules['rule_name'][], $default_rules['rule_pattern'][]) = explode(':', $r->rule, 2) + array('', '');
				$default_rules['destination_by_rule'][] = $r->id;
				$default_rules['destination_url']['BY_RULE'][] = $r->getRelated('PMLC_Destination_Record')->getUrl();
			}
		}
		$rules = $this->input->post($default_rules); $this->data['rules'] =& $rules;
		if ('advanced' == PMLC_Plugin::getInstance()->getOption('destination_mode')) {
			// remove records where both country and destination_by_country are empty
			$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($rules['country'])), array_keys(array_filter($rules['destination_by_country'])))));
			$rules['country'] = array_intersect_key($rules['country'], $not_empty);
			$rules['destination_by_country'] = array_intersect_key($rules['destination_by_country'], $not_empty);
			// remove records where rule_name, rule_pattern and destination_by_rule are empty at the same time
			$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($rules['rule_name'])), array_keys(array_filter($rules['rule_pattern'])), array_keys(array_filter($rules['destination_by_rule'])))));
			$rules['rule_name'] = array_intersect_key($rules['rule_name'], $not_empty);
			$rules['rule_pattern'] = array_intersect_key($rules['rule_pattern'], $not_empty);
			$rules['destination_by_rule'] = array_intersect_key($rules['destination_by_rule'], $not_empty);
		} else { // simple mode
			// remove records where both country and destination_by_country are empty
			$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($rules['country'])), array_keys(array_filter($rules['destination_url']['BY_COUNTRY'])))));
			$rules['country'] = array_intersect_key($rules['country'], $not_empty);
			$rules['destination_by_country'] = array_intersect_key($rules['destination_by_country'], $not_empty);
			$rules['destination_url']['BY_COUNTRY'] = array_intersect_key($rules['destination_url']['BY_COUNTRY'], $not_empty);
			// remove records where rule_name, rule_pattern and destination_by_rule are empty at the same time
			$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($rules['rule_name'])), array_keys(array_filter($rules['rule_pattern'])), array_keys(array_filter($default_rules['destination_url']['BY_RULE'])))));
			$rules['rule_name'] = array_intersect_key($rules['rule_name'], $not_empty);
			$rules['rule_pattern'] = array_intersect_key($rules['rule_pattern'], $not_empty);
			$rules['destination_by_rule'] = array_intersect_key($rules['destination_by_rule'], $not_empty);
			$default_rules['destination_url']['BY_RULE'] = array_intersect_key($default_rules['destination_url']['BY_RULE'], $not_empty);
		}
		
		// read/set automatches
		if ($id) {
			$automatch_list = $item->getRelated('PMLC_Automatch_List');
			$automatch_urls = array();
			foreach ($automatch_list as $am) {
				$automatch_urls[] = $am->url;
			}
		} else {
			$automatch_list = new PMLC_Automatch_List();
			$automatch_urls = array();
		}
		$this->data['automatches'] = $automatches = array_unique(array_filter($this->input->post('automatches', $automatch_urls)));
		
		if ($this->input->post('is_submitted')) {
			check_admin_referer('edit-link', '_wpnonce_edit-link');
			
			if ( ! $id) {
				$item->insert();
				$this->data['id'] = $id = $item->id;
			}
			
			array_filter($post);
			$item->set($post);
			empty($item->expire_on) and $item->expire_on = '0000-00-00'; // tranform empty string to empty date
			
			// validate link fields
			if ('' == $preset) { // preset links can have empty names
				if (empty($post['name'])) {
					$this->errors->add('form-validation', __('Link Name is empty', 'pmlc_plugin'));
					unset($item->name);
				} else {
					$check = new PMLC_Link_Record();
					if ( ! $check->getBy(array('name LIKE' => $post['name'], 'preset' => '') + ( ! is_null($id) ? array('id <>' => $id) : array()))->isEmpty()) {
						$this->errors->add('form-validation', sprintf(__('Link with `%s` name already exists (may exist in Trash)', 'pmlc_plugin'), $post['name']));
						unset($item->name);
					}
				}
			}
			if ( ! empty($post['slug'])) {
				$check = new PMLC_Link_Record();
				if ( ! $check->getBy(array('slug LIKE' => $post['slug'], 'preset' => '') + ( ! is_null($id) ? array('id <>' => $id) : array()))->isEmpty()) {
					$this->errors->add('form-validation', sprintf(__('Link with `%s` cloaked URL part already exists (may exist in Trash)', 'pmlc_plugin'), $post['slug']));
					unset($item->slug);
				}
			}
			if ( ! empty($post['expire_on'])) {
				if ( ! preg_match('%^\d{4}-\d{2}-\d{2}$%', $post['expire_on'])) {
					$this->errors->add('form-validation', __('Wrong format of expiration date', 'pmlc_plugin'));
					unset($item->expire_on);
				}
			}
			if ( ! empty($automatches)) {
				$automatch_urls = array(); $is_automatch_error = FALSE;
				foreach ($automatches as $auto_url) {
					$auto_url = trim($auto_url);
					if ('' != $auto_url) {
						if ( ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $auto_url)) {
							$this->errors->add('form-validation', sprintf(__('Auto-Match URL `%s` has wrong format', 'pmlc_plugin'), $auto_url));
							$is_automatch_error = TRUE;
						} else {
							if ('' == $preset) {
								$check_links = new PMLC_Automatch_List();
								$check_links->setColumns($item->getTable() . '.name')
									->join($item->getTable(), $item->getFieldName('id') . ' = ' . $check_links->getFieldName('link_id'))
									->getBy(array(
										$check_links->getFieldName('url') . ' LIKE' => $auto_url,
										$item->getFieldName('id') . ' !=' => $item->id,
										$item->getFieldName('preset') . ' =' => '',
									), 1);
								if ($check_links->total() > 0) {
									$this->errors->add('form-validation', sprintf(__('`%s` URL is already configured to be auto-matched by `%s` link', 'pmlc_plugin'), $auto_url, $check_links[0]['name']));
									$is_automatch_error = TRUE;
								} else {
									$automatch_urls[] = $auto_url;
								}
							} else {
								$automatch_urls[] = $auto_url;
							}
						}
					}
				}
				if ( ! $is_automatch_error) {
					foreach ($automatch_list as $am) { // delete old automatches
						$am->delete();
					}
					$am = new PMLC_Automatch_Record();
					foreach ($automatch_urls as $auto_url) {
						$am->clear()->set(array(
							'link_id' => $item->id,
							'url' => $auto_url,
						))->insert();
					}
				}
			}
			
			if ('advanced' == PMLC_Plugin::getInstance()->getOption('destination_mode')) {
				// [save destinations regardless of detected errors]
				// NOTE: ONE_SET, EXPIRED and REFERER_MASK destination have been already saved by ajax calls from edit page
				if ($this->input->post('is_expired_show_404')) { // fix expired destinations depending on user selection
					$rules['destination_url']['EXPIRED'][0] = '';
					$rule = $item->getRule('EXPIRED');
					if ( ! $rule->isEmpty()) { // delete old rule
						$rule->delete()->clear();
					}
				}
				if ($this->input->post('is_referer_mask_show_404')) { // fix referer mask destinations depending on user selection
					$rules['destination_url']['REFERER_MASK'][0] = '';
					$rule = $item->getRule('REFERER_MASK');
					if ( ! $rule->isEmpty()) { // delete old rule
						$rule->delete()->clear();
					}
				}
				// BY_COUNTRY
				foreach ($rules_by_country as $r) { // delete not existing rules
					if ( ! in_array($r->id, $rules['destination_by_country'])) {
						$r->delete();
					}
				}
				$rule = new PMLC_Rule_Record();
				foreach (array_filter($rules['destination_by_country']) as $i => $rid) {
					$rule->clear()->set(array('id' => $rid, 'rule' => $rules['country'][$i]))->update();
				}
				// BY_RULE
				foreach ($rules_by_rule as $r) { // delete not existing rules
					if ( ! in_array($r->id, $rules['destination_by_rule'])) {
						$r->delete();
					}
				}
				$rule = new PMLC_Rule_Record();
				foreach (array_filter($rules['destination_by_rule']) as $i => $rid) {
					$rule->clear()->set(array('id' => $rid, 'rule' => $rules['rule_name'][$i] . ':' . $rules['rule_pattern'][$i]))->update();
				}
				// [/save destinations regardless of detected errors]
				
				// display validation errors if any
				if ('ONE_SET' == $post['destination_type']) {
					if ($item->getRule('ONE_SET')->isEmpty()) {
						$this->errors->add('form-validation', __('Destination for all visitors must be specified', 'pmlc_plugin'));
					}
				}
				if ('BY_COUNTRY' == $post['destination_type']) {
					// validate rules & destination sets
					if (array_keys(array_filter($rules['country'])) != array_keys(array_filter($rules['destination_by_country']))) {
						$this->errors->add('form-validation', __('Both country and destination must be sepcified for all rules', 'pmlc_plugin'));
					}
					if (array_unique(array_filter($rules['country'])) != array_filter($rules['country'])) {
						$this->errors->add('form-validation', __('Each rule must have unique country specified', 'pmlc_plugin'));
					}
				}
				if ('BY_RULE' == $post['destination_type']) {
					// validate rules & destination sets
					if (array_keys(array_filter($rules['rule_name'])) != array_keys(array_filter($rules['rule_pattern'])) or array_keys(array_filter($rules['rule_name'])) != array_keys(array_filter($rules['destination_by_rule']))) {
						$this->errors->add('form-validation', __('Rule, pattern and destination must be sepcified for all rules', 'pmlc_plugin'));
					}
					$unique_keys = array_keys(array_unique(array_filter($rules['rule_name'])));
					if ($unique_keys != array_keys(array_filter($rules['rule_name'])) and $unique_keys == array_keys(array_unique(array_filter($rules['rule_pattern'])))) {
						$this->errors->add('form-validation', __('Each rule must be unique', 'pmlc_plugin'));
					}
					foreach (array_filter($rules['rule_pattern']) as $i => $pattern) {
						if ('REMOTE_ADDR' == $rules['rule_name'][$i]) {
							if ( ! preg_match('%^(\d{1,3}(\.\d{1,3}){3})( *- *(\d{1,3}(\.\d{1,3}){3}))?$%', $pattern, $mtch) or isset($mtch[4]) and ipcmp($mtch[1], $mtch[4]) > 0) {
								$this->errors->add('form-validation', sprintf(__('Pattern `%s` is invalid IP range', 'pmlc_plugin'), $pattern));
							}
						} else if ('*' != $rules['rule_name'][$i]) {
							if (FALSE === @preg_match('%' . addcslashes($pattern, '%') . '%i', '')) {
								$this->errors->add('form-validation', sprintf(__('Pattern `%s` is invalid', 'pmlc_plugin'), $pattern));
							}
						}
					} 
				}
			} else { // simple destination mode
				// [save destinations regardless of detected errors]
				if ($this->input->post('is_expired_show_404')) { // fix expired destinations depending on user selection
					$rules['destination_url']['EXPIRED'][0] = '';
				}
				if ($this->input->post('is_referer_mask_show_404')) { // fix referer mask destinations depending on user selection
					$rules['destination_url']['REFERER_MASK'][0] = '';
				}
				foreach (array('ONE_SET', 'EXPIRED', 'REFERER_MASK') as $type) { // ONE_SET, EXPIRED, REFERER_MASK
					$rule = $item->getRule($type);
					if ( ! $rule->isEmpty()) { // delete old rule
						$rule->delete()->clear();
					}
					$url = $rules['destination_url'][$type][0];
					if ('' != $url and ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $url)) {
						$this->errors->add('form-validation', sprintf(__('Specified URL `%s` has wrong format', 'pmlc_plugin'), $url));
						$url = ''; // reset wrong url
					}
					if ('' != $url) {
						$rule->set(array(
							'link_id' => $item->id,
							'type' => $type,
						))->insert();
						
						$destination = new PMLC_Destination_Record();
						$destination->set(array(
							'rule_id' => $rule->id,
							'url' => $url,
							'weight' => '100',
						))->insert();
					}
				}
				// BY_COUNTRY
				foreach ($rules_by_country as $r) { // delete old rules
					$r->delete();
				}
				foreach ($rules['destination_url']['BY_COUNTRY'] as $i => $url) {
					if ('' != $url and ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $url)) {
						$this->errors->add('form-validation', sprintf(__('Specified URL `%s` has wrong format', 'pmlc_plugin'), $url));
						$url = ''; // reset wrong url
					}
					if ('' != $url) {
						$rule = new PMLC_Rule_Record();
						$rule->set(array(
							'link_id' => $item->id,
							'type' => 'BY_COUNTRY',
							'rule' => $rules['country'][$i],
						))->insert();
						
						$destination = new PMLC_Destination_Record();
						$destination->set(array(
							'rule_id' => $rule->id,
							'url' => $url,
							'weight' => '100',
						))->insert();
						$rule['destination_by_country'][$i] = $rule->id;
					} else {
						$rule['destination_by_country'][$i] = '';
					}
				}
				// BY_RULE
				foreach ($rules_by_rule as $r) { // delete old rules
					$r->delete();
				}
				foreach ($rules['destination_url']['BY_RULE'] as $i => $url) {
					if ('' != $url and ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $url)) {
						$this->errors->add('form-validation', sprintf(__('Specified URL `%s` has wrong format', 'pmlc_plugin'), $url));
						$url = ''; // reset wrong url
					}
					if ('' != $url) {
						$rule = new PMLC_Rule_Record();
						$rule->set(array(
							'link_id' => $item->id,
							'type' => 'BY_RULE',
							'rule' => $rules['rule_name'][$i] . ':' . $rules['rule_pattern'][$i],
						))->insert();
						
						$destination = new PMLC_Destination_Record();
						$destination->set(array(
							'rule_id' => $rule->id,
							'url' => $url,
							'weight' => '100',
						))->insert();
						$rule['destination_by_rule'][$i] = $rule->id;
					} else {
						$rule['destination_by_rule'][$i] = '';
					}
				}
				// [/save destinations regardless of detected errors]
				
				// display validation errors if any
				if ('ONE_SET' == $post['destination_type']) {
					if ($item->getRule('ONE_SET')->isEmpty()) {
						$this->errors->add('form-validation', __('Destination for all visitors must be specified', 'pmlc_plugin'));
					}
				}
				if ('BY_COUNTRY' == $post['destination_type']) {
					// validate rules & destination sets
					if (array_keys(array_filter($rules['country'])) != array_keys(array_filter($rules['destination_url']['BY_COUNTRY']))) {
						$this->errors->add('form-validation', __('Both country and destination must be sepcified for all rules', 'pmlc_plugin'));
					}
					if (array_unique(array_filter($rules['country'])) != array_filter($rules['country'])) {
						$this->errors->add('form-validation', __('Each rule must have unique country specified', 'pmlc_plugin'));
					}
				}
				if ('BY_RULE' == $post['destination_type']) {
					// validate rules & destination sets
					if (array_keys(array_filter($rules['rule_name'])) != array_keys(array_filter($rules['rule_pattern'])) or array_keys(array_filter($rules['rule_name'])) != array_keys(array_filter($rules['destination_url']['BY_RULE']))) {
						$this->errors->add('form-validation', __('Rule, pattern and destination must be sepcified for all rules', 'pmlc_plugin'));
					}
					$unique_keys = array_keys(array_unique(array_filter($rules['rule_name'])));
					if ($unique_keys != array_keys(array_filter($rules['rule_name'])) and $unique_keys == array_keys(array_unique(array_filter($rules['rule_pattern'])))) {
						$this->errors->add('form-validation', __('Each rule must be unique', 'pmlc_plugin'));
					}
					foreach (array_filter($rules['rule_pattern']) as $i => $pattern) {
						if ('REMOTE_ADDR' == $rules['rule_name'][$i]) {
							if ( ! preg_match('%^(\d{1,3}(\.\d{1,3}){3})( *- *(\d{1,3}(\.\d{1,3}){3}))?$%', $pattern, $mtch) or isset($mtch[4]) and ipcmp($mtch[1], $mtch[4]) > 0) {
								$this->errors->add('form-validation', sprintf(__('Pattern `%s` is invalid IP range', 'pmlc_plugin'), $pattern));
							}
						} else if ('*' != $rules['rule_name'][$i]) {
							if (FALSE === @preg_match('%' . addcslashes($pattern, '%') . '%i', '')) {
								$this->errors->add('form-validation', sprintf(__('Pattern `%s` is invalid', 'pmlc_plugin'), $pattern));
							}
						}
					} 
				}
			}
			
			$load_preset = $this->input->getpost('load_preset');
			if ($load_preset) {
				$this->errors = new WP_Error(); // empty errors if some were present
				$preset_link = new PMLC_Link_Record();
				if ( ! $preset_link->getById($load_preset)->isEmpty()) {
					$item->applyPreset($preset_link, $this->input->getpost('load_preset_rewrite', FALSE));
					wp_redirect(add_query_arg('id', $item->id));
				}
			} else {
				if ($this->input->post('is_save_as_preset') and '' == $preset) {
					$this->errors->errors['form-validation'] = array(); // clear errors
					$this->errors->add('form-validation', __('Preset name must be specified', 'pmlc_plugin'));
				}
				if ('' != $preset) {
					$check = new PMLC_Link_Record();
					if ($check->getBy(array('preset' => $preset, 'id !=' => $item->id))->isEmpty()) {
						$item->set('preset', $preset)->update();
						wp_redirect(add_query_arg(array('page' => 'pmlc-admin-links', 'pmlc_nt' => urlencode(__('Link saved as preset', 'pmlc_plugin'))) + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)), admin_url('admin.php')));
						die();
					} else {
						$this->errors->errors['form-validation'] = array(); // clear errors
						$this->errors->add('form-validation', __('Preset with specified name already exists', 'pmlc_plugin'));
						$item->set('preset', '_temp')->update();
					}
				} else if ( ! $this->errors->get_error_codes()) { // no validation errors detected
					empty($item->slug) and $item->generateSlug(); // generate slug automatically if none set
					$item->preset = ''; // make link a usual one if everything is ok
					
					$item->update();
					if ($this->input->get('id')) {
						$msg = __('Link updated', 'pmlc_plugin');
					} else {
						$msg = __('Link added', 'pmlc_plugin');
					}
					if ('' == $item->preset) {
						$msg .= '. ' . sprintf(__('Cloaked URL is %s', 'pmlc_plugin'), '<a href="' . $item->getUrl() . '">' . $item->getUrl() . '</a>');
					}
					wp_redirect(add_query_arg(array('page' => 'pmlc-admin-links', 'pmlc_nt' => urlencode($msg)) + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)), admin_url('admin.php')));
					die();
				} else { // save record as draft even if there are validation errors
					$item->set('preset', '_temp')->update();
				}
			}
			
		}
		
		$this->render();
	}
	
	public function edit() {
		$this->index();
	}
	
	/**
	 * Create / Edit Destination Set
	 */
	public function destination() {
		$link_id = $this->input->getpost('link_id');
		$link = new PMLC_Link_Record();
		if ($link_id and $link->getById($link_id)->isEmpty()) { // ID corresponds to no link
			wp_redirect_or_javascript(add_query_arg(array('page' => 'pmlc-admin-links'), admin_url('admin.php')));
			die();
		}
		$id = $this->input->getpost('id'); $type = $this->input->getpost('type', 'ONE_SET');
		$this->data['rule'] = $rule = new PMLC_Rule_Record();
		if ($id and ($rule->getById($id)->isEmpty() or $rule->link_id != $link_id or $rule->type != $type)) { // ID corresponds to no destination or rule id corresponds to wrong rule
			wp_redirect_or_javascript(add_query_arg(array('page' => 'pmlc-admin-links', 'action' => 'edit', 'link_id' => $link_id), admin_url('admin.php')), 'jQuery("#__modal").dialog("close");');
			die();
		}
		if ( ! $rule->isEmpty()) {
			$default = array();
			foreach ($rule->getRelated('PMLC_Destination_List') as $destination) {
				$default['url'][] = $destination->url;
				$default['weight'][] = $destination->weight;
			}
		} else {
			$default = array(
				'url' => array(''),
				'weight' => array('100'),
			);
		}
		$this->data['post'] = $post = $this->input->post($default);
		$this->data['post']['url'] = $post['url'] = array_filter($post['url']); // remove empty values
		if (empty($this->data['post']['url'])) { // make sure at least 1 row is displayed in form table
			$this->data['post']['url'][] = 'http://';
		}
		if ($this->input->post('is_submitted')) {
			check_admin_referer('edit-destination', '_wpnonce_edit-destination');
			
			if ($this->input->getpost('clear')) { // remove destination at all
				$rule->isEmpty() or $rule->delete();
				wp_redirect_or_javascript(add_query_arg(array('page' => 'pmlc-admin-links', 'action' => 'edit', 'link_id' => $link_id), admin_url('admin.php')), '
					(function ($) {
						$("#__modal").dialog("close");
						var $adest = jQuery(__destination_tag);
						$adest.addClass("empty");
						$adest.attr("href", $adest.attr("href").replace(/([?&])id=[^?&]+&?/, "$1").replace(/[?&]$/, ""));
						$adest.removeAttr("title").removeAttr("original-title");
						$adest.parent().find("input[name^=\'destination_\']").val("");
					})(jQuery);
				');
				die();
			}
			
			if (empty($post['url'])) {
				$this->errors->add('form-validation', __('At least one destination must be set', 'pmlc_plugin'));
			} else {
				$weight_total = 0;
				foreach ($post['url'] as $i => $destination) {
					$weight_total += $post['weight'][$i];
					if ( ! preg_match('%^https?://[\w\d:#@\%/;$()\[\]~_?+=\\\\&.-]+$%i', $destination)) {
						$this->errors->add('form-validation', sprintf(__('Specified URL `%s` has wrong format', 'pmlc_plugin'), $destination));
					}
				}
				if (100 != $weight_total) {
					$this->errors->add('form-validation', __('Specified weights for links must add up to 100%', 'pmlc_plugin'));
				}
			}
				
			if ( ! $this->errors->get_error_codes()) { // no validation errors detected
				$link->isEmpty()
					and $link->set(array(
						'name' => '',
						'slug' => '',
						'preset' => '_temp',
						'redirect_type' => '301',
						'destination_type' => 'ONE_SET',
						'expire_on' => '0000-00-00',
					))->insert(); // create link if not present
					
				$link_id = $link->id;
				
				$rule->isEmpty() and $rule->set(array(
					'link_id' => $link_id,
					'type' => $type,
				))->insert();
				// delete old destinations
				foreach ($rule->getRelated('PMLC_Destination_List') as $destination) {
					$destination->delete();
				}
				// add new destinations & compose tooltip along the way
				$destinationHtml = "\n";
				foreach ($post['url'] as $i => $url) {
					$destination = new PMLC_Destination_Record();
					$destination->set(array(
						'rule_id' => $rule->id,
						'url' => $url,
						'weight' => $post['weight'][$i],
					))->insert();
					$destinationHtml .= '<div>' . $url . ' - ' . $post['weight'][$i] . '%</div>' . "\n";
				}
				wp_redirect_or_javascript(add_query_arg(array('page' => 'pmlc-admin-links', 'action' => 'edit', 'link_id' => $link_id), admin_url('admin.php')), '
					(function ($) {
						$("#__modal").dialog("close");
						var $adest = jQuery(__destination_tag);
						$adest.removeClass("empty");
						' . ( ! $id ? '$adest.attr("href", $adest.attr("href") + (/\?/.test($adest.attr("href")) ? "&" : "?") + "id=' . $rule->id . '");' : '') . '
						$adest.parent().find("input[name^=\\"destination_\\"]").val("' . $rule->id . '");
						$adest.attr("original-title", "' . addcslashes($destinationHtml, "\"\n") . '");
						var $form = $adest.parents("form");
						$form.find("input[name=\\"id\\"]").val("' . $link_id . '");
						$form.find("a.destination-set").each(function () {
							$(this).attr("href", $(this).attr("href").replace(/([&?])link_id=[^&]*/, "$1link_id=' . $link_id . '"));
						});
					})(jQuery);
				');
				die();
			}
		}
		
		$this->render();
		
	}
	
	/**
	 * Handle file upload with URLs for destination set
	 */
	public function destination_upload() {
		if (empty($_FILES['upload']) or empty($_FILES['upload']['name'])) {
			$this->errors->add('form-validation', __('File must be selected', 'pmlc_plugin'));
		} else {
			$urls = array_values(array_filter(preg_split('%\s*[,\n\r]\s*%', file_get_contents($_FILES['upload']['tmp_name']))));
			if (empty($urls)) {
				$this->errors->add('form-validation', __('No URLs found in the file uploaded', 'pmlc_plugin'));
			}
		}
		if ( ! $this->errors->get_error_codes()) {
			echo json_encode(array(
				'urls' => $urls,
			));
		} else {
			echo json_encode(array(
				'error' => $this->errors->get_error_message(),
			));
		}
		
		die();
	}
}