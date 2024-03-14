<?php

namespace cnb\admin\templates;

// don't load directly
defined( 'ABSPATH' ) || die( '-1' );

use cnb\admin\api\CnbAppRemote;

/**
 * Contains a collection of hardcoded templates to work with (for now).
 *
 * This can later be replaced by an API call or something.
 */
class Templates {

	/**
	 * @return Template[]
	 */
	function get_templates() {
		$endpoint = 'https://nowbuttons.com/src/preview/templates.json';
		$transient_name = 'cnb_' . $endpoint;

		// If cached, use that
		$cached = get_transient($transient_name);
		if ($cached) return $cached;

		// Get the result and cache it
		$cnb_remote = new CnbAppRemote();
		$result = $cnb_remote->cnb_get($endpoint, false);
		if ($result && !is_wp_error($result)) {
			set_transient($transient_name, $result, DAY_IN_SECONDS);
		}
		return $result;
	}
}
