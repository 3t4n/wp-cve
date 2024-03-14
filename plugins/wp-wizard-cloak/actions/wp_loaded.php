<?php

function pmlc_wp_loaded() {
	// enable sessions
	if ( ! session_id()) session_start();
	
	// detect if cloaked link is requested and execute redirect.php if so
	if (preg_match('%^' . preg_quote(site_url_no_domain(PMLC_Plugin::getInstance()->getOption('url_prefix')), '%') . '/([\w-]+)(/([^/?]+))?/?($|\?)%', $_SERVER['REQUEST_URI'], $mtch) or preg_match('%^' . preg_quote(site_url_no_domain(), '%') . '/?\?(.*?&)?cloaked=([\w-]+)(&|$)%', $_SERVER['REQUEST_URI'], $mtch_alt)) {
		if ($mtch) {
			$slug = $mtch[1];
			$_GET['subid'] = $mtch[3];
		} else {
			$slug = $mtch_alt[2];
		}
		$link = new PMLC_Link_Record();
		if ( ! $link->getBySlug($slug)->isEmpty() and '' == $link->preset and ! $link->is_trashed) { // link is registered and not draft or preset
			$rule = NULL; $destination = NULL;
			if (empty($link->expire_on) or '0000-00-00' == $link->expire_on or $link->expire_on >= date('Y-m-d')) { // link is not expired
				switch ($link->destination_type) {
					case 'ONE_SET':
						$rule = $link->getRule('ONE_SET');
						break;
					case 'BY_COUNTRY':
						// detect user country
						$geoip = new PMLC_GeoIPCountry_Record();
						if ( ! $geoip->getByIp($_SERVER['REMOTE_ADDR'])->isEmpty()) {
							$rule = $link->getRule('BY_COUNTRY', $geoip->country);
						}
						! is_null($rule) and ! $rule->isEmpty() or $rule = $link->getRule('BY_COUNTRY', '*'); // apply coubnry fallback rule if there is no exact match found
						break;
					case 'BY_RULE':
						$rules = new PMLC_Rule_List();
						foreach ($rules->getBy(array('link_id' => $link->id, 'type' => 'BY_RULE'))->convertRecords() as $r) {
							list($rule_name, $rule_pattern) = explode(':', $r->rule, 2);
							if ('*' != $rule_name) {
								if ('REMOTE_ADDR' == $rule_name) {
									list($min_ip, $max_ip) = preg_split('% *- *%', $r->rule, 2) + array('0.0.0.0', '255.255.255.255');
									if (ipcmp($min_ip, $_SERVER['REMOTE_ADDR']) <= 0 and ipcmp($_SERVER['REMOTE_ADDR'], $max_ip) <= 0) {
										$rule = $r;
										break; // rule found
									}
								} else if (isset($_SERVER[$rule_name]) and preg_match('%' . addcslashes($rule_pattern, '%') . '%i', $_SERVER[$rule_name])) {
									$rule = $r;
								}
							} else {
								$default_rule = $r;
							}
						}
						! is_null($rule) or isset($default_rule) and $rule = $default_rule; // no exact rule matched, apply default one (i.e. `*:*` one)
						break;
				}
			} else { // output expired destination
				$rule = $link->getRule('EXPIRED');
			}
			if ( ! empty($rule) and ! $rule->isEmpty()) {
				if ( ! isset($_GET['d'])) { // no specific destination requested: apply weight rules
					$destination = $rule->getDestination();
				} else { // a specific destination requested, i.e. weight rule was applied
					$destination = new PMLC_Destination_Record();
					$destination->getById($_GET['d']);
				}
			}
			! empty($destination) and ! $destination->isEmpty() and $destination->rule_id == $rule->id
				and $destination->redirect($link->redirect_type) !== FALSE // redirect method returns FALSE if referer cannot be masked
				or	$rule = $link->getRule('REFERER_MASK') and ! $rule->isEmpty()
					and $destination = $rule->getDestination() and $destination->redirect('META_REFRESH');
		}
	}
}