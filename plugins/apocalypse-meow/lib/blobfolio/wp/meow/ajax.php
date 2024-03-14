<?php
/**
 * Apocalypse Meow AJAX handlers.
 *
 * All AJAX methods point back to here.
 *
 * Program error: 500
 * Bad request: 400
 * Invalid/missing authorization: 401
 * Authorized but not allowed: 403
 * Rate limiting: 429
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;
use WP_Error;

class ajax {

	// The true action name matches the callback, but is prefixed.
	const ACTIONS = array(
		'activity',
		'activity_csv',
		'pardon',
		'reset',
		'retroactive_reset',
		'retroactive_reset_generate',
		'settings',
		'stats',
		'tools_admin',
		'tools_md5',
		'tools_reset',
		'tools_sessions',
		'tools_session_delete',
	);

	// All responses are JSON, with these parts.
	const RESPONSE = array(
		'data'=>array(),
		'errors'=>array(),
		'msg'=>'',
		'status'=>200,
	);

	// Action for Nonces.
	const NONCE = 'meow-nonce';



	// -----------------------------------------------------------------
	// Init/Setup
	// -----------------------------------------------------------------

	protected static $_init = false;

	/**
	 * Register Actions
	 *
	 * @return bool True/false.
	 */
	public static function init() {
		// Only need to do this once.
		if (static::$_init) {
			return true;
		}
		static::$_init = true;

		foreach (static::ACTIONS as $action) {
			\add_action("wp_ajax_meow_ajax_$action", array(static::class, $action));
		}

		return true;
	}

	/**
	 * Generate Nonce
	 *
	 * @return string Nonce.
	 */
	public static function get_nonce() {
		return \wp_create_nonce(static::NONCE);
	}

	/**
	 * Parse Request
	 *
	 * A generic action to sanitize $_POST and do some global error
	 * checking.
	 *
	 * @param array $data Data.
	 * @param bool $nonce Check Nonce.
	 * @param string $cap Capability.
	 * @return array Response.
	 */
	protected static function parse(&$data, $nonce=true, $cap='manage_options') {
		common\ref\cast::to_array($data);
		$data = \stripslashes_deep($data);
		$out = static::RESPONSE;

		// Check Nonce?
		if ($nonce) {
			if (! isset($data['n']) || ! \wp_verify_nonce($data['n'], static::NONCE)) {
				$out['errors']['other'] = \__('The form had expired. Please try again.', 'apocalypse-meow');
				$out['status'] = 400;
			}
		}

		// There are no non-admin AJAX calls.
		if (! \is_user_logged_in()) {
			$out['errors']['other'] = \__('You must be logged in to complete this action.', 'apocalypse-meow');
			$out['status'] = 401;
		}
		elseif (! \current_user_can($cap)) {
			$out['errors']['other'] = \__('You are not authorized to complete this action.', 'apocalypse-meow');
			$out['status'] = 403;
		}

		return $out;
	}

	/**
	 * Send Response
	 *
	 * AJAX responses are JSON formatted. Status codes indicate yay/nay.
	 *
	 * @param array $data Data.
	 * @return void Nothing.
	 */
	protected static function send(&$data) {
		$out = common\data::parse_args($data, static::RESPONSE);

		// Errors should indicate an errory response, while the lack of
		// errors should mean success.
		if (\count($out['errors']) && common\data::in_range($out['status'], 200, 399)) {
			$out['status'] = 400;
		}
		elseif (! \count($out['errors']) && ! common\data::in_range($out['status'], 200, 399)) {
			$out['status'] = 200;
		}

		// Pass it on!
		common\ref\sanitize::utf8($out);
		\wp_send_json($out, $out['status']);
	}

	// ----------------------------------------------------------------- end init



	// -----------------------------------------------------------------
	// Handlers
	// -----------------------------------------------------------------

	/**
	 * Activity Search
	 *
	 * Pull login activity data.
	 *
	 * @return void Nothing.
	 */
	public static function activity() {
		global $wpdb;
		$out = static::parse($_POST);
		$out['data'] = array(
			'page'=>0,
			'pages'=>0,
			'total'=>0,
			'items'=>array(),
			'bans'=>array(),
		);

		$search = array(
			'date_min'=>'0000-00-00',
			'date_max'=>'0000-00-00',
			'username'=>'',
			'usernameExact'=>1,
			'ip'=>'',
			'subnet'=>'',
			'type'=>'',
			'page'=>0,
			'pageSize'=>10,
			'orderby'=>'date_created',
			'order'=>'desc',
		);

		$orders = array(
			'date_created'=>'Date',
			'ip'=>'IP',
			'type'=>'Status',
			'username'=>'Username',
		);

		$now = \current_time('mysql');

		// If we have errors, we should go ahead and leave.
		if (\count($out['errors'])) {
			static::send($out);
		}

		// Before we get into th emain search, let's go ahead and pull a
		// list of currently-banned users. We always want to return
		// these, even if they don't match the search criteria.
		$dbResult = $wpdb->get_results("
			SELECT *
			FROM `{$wpdb->prefix}meow2_log`
			WHERE
				`type`='ban' AND
				`date_expires` > '$now'
			ORDER BY `date_expires` ASC
		", \ARRAY_A);
		if (\is_array($dbResult) && \count($dbResult)) {
			foreach ($dbResult as $Row) {
				common\ref\cast::to_int($Row['id']);
				common\ref\cast::to_int($Row['count']);
				common\ref\cast::to_bool($Row['pardoned']);
				common\ref\cast::to_bool($Row['community']);

				$Row['banRemaining'] = \strtotime($Row['date_expires']) - \current_time('timestamp');

				$Row['userExists'] = false;

				$out['data']['bans'][] = $Row;
			}
		}

		// Sort out search parameters.
		$data = common\data::parse_args($_POST, $search);

		foreach (array('date_min', 'date_max') as $field) {
			common\ref\sanitize::date($data[$field]);
			if ('0000-00-00' === $data[$field]) {
				$data[$field] = \date('Y-m-d');
			}
		}
		if ($data['date_min'] > $data['date_max']) {
			common\data::switcheroo($data['date_min'], $data['date_max']);
		}

		common\ref\sanitize::whitespace($data['username']);
		if ($data['username'] && common\mb::strlen($data['username']) < 3) {
			$data['usernameExact'] = 1;
		}
		else {
			common\ref\sanitize::to_range($data['usernameExact'], 0, 1);
		}

		common\ref\sanitize::ip($data['ip']);
		if (! $data['ip']) {
			$data['ip'] = '';
		}

		if ($data['subnet']) {
			login::sanitize_subnet($data['subnet']);
		}

		$data['type'] = \strtolower($data['type']);
		if (! $data['type'] && ! \in_array($data['type'], login::LOGIN_LOG_TYPES, true)) {
			$data['type'] = '';
		}

		if (! \array_key_exists($data['orderby'], $orders)) {
			$data['orderby'] = 'date_created';
		}
		if ('asc' !== $data['order'] && 'desc' !== $data['order']) {
			$data['order'] = 'desc';
		}

		common\ref\sanitize::to_range($data['pageSize'], 1, 500);

		// Build the query conditions.
		$conds = array();
		$conds[] = "DATE(`date_created`) >= '{$data['date_min']}'";
		$conds[] = "DATE(`date_created`) <= '{$data['date_max']}'";
		$conds[] = "`type` != 'alert'";

		if ($data['username']) {
			if ($data['usernameExact']) {
				$conds[] = "`username` = '" . \esc_sql($data['username']) . "'";
			}
			else {
				$conds[] = "`username` LIKE '%" . \esc_sql($wpdb->esc_like($data['username'])) . "%'";
			}
		}

		if ($data['ip']) {
			$conds[] = "`ip`='{$data['ip']}'";
		}

		if ($data['subnet']) {
			$conds[] = "`subnet`='{$data['subnet']}'";
		}

		if ($data['type']) {
			$conds[] = "`type`='{$data['type']}'";
		}

		$conds = \implode(' AND ', $conds);

		$out['data']['total'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow2_log` WHERE $conds");

		if ($out['data']['total'] > 0) {
			$out['data']['pages'] = (int) (\ceil($out['data']['total'] / $data['pageSize']) - 1);
			common\ref\sanitize::to_range($data['page'], 0, $out['data']['pages']);
			$out['data']['page'] = $data['page'];

			$from = $out['data']['page'] * $data['pageSize'];

			$dbResult = $wpdb->get_results("
				SELECT *
				FROM `{$wpdb->prefix}meow2_log`
				WHERE $conds
				ORDER BY `{$data['orderby']}` {$data['order']}
				LIMIT $from, {$data['pageSize']}
			", \ARRAY_A);
			if (\is_array($dbResult) && \count($dbResult)) {
				foreach ($dbResult as $Row) {
					common\ref\cast::to_int($Row['id']);
					common\ref\cast::to_int($Row['count']);
					common\ref\cast::to_bool($Row['community']);
					common\ref\cast::to_bool($Row['pardoned']);

					// Keep track of current bans.
					$Row['banned'] = (
						('ban' === $Row['type']) &&
						$Row['date_expires'] > $now
					);

					$Row['banRemaining'] = 0;
					if ($Row['banned']) {
						$Row['banRemaining'] = \strtotime($Row['date_expires']) - \current_time('timestamp');
					}

					if (core::ENUMERATION_USERNAME === $Row['username']) {
						$Row['username'] = '(' . \__('User Enumeration', 'apocalypse-meow') . ')';
						$Row['userExists'] = false;
					}
					else {
						// And whether or not the username exists.
						$Row['userExists'] = login::username_exists($Row['username']);
					}

					$out['data']['items'][] = $Row;
				}
			}
		}

		$out['status'] = 200;

		static::send($out);
	}

	/**
	 * Export Activity CSV
	 *
	 * Pull all login data and format as a CSV for download.
	 *
	 * @return void Nothing.
	 */
	public static function activity_csv() {
		global $wpdb;
		$out = static::parse($_POST, true);
		$out['data'] = array(
			'download'=>'',
			'downloadName'=>common\sanitize::domain(\site_url()) . '_meow-' . \time() . '.csv',
		);

		if (! \count($out['errors'])) {
			$dbResult = $wpdb->get_results("
				SELECT
					`ip`,
					`subnet`,
					`date_created`,
					`date_expires`,
					`type`,
					`username`,
					`count`,
					`pardoned`
				FROM `{$wpdb->prefix}meow2_log`
				WHERE `type` != 'alert'
				ORDER BY `date_created` ASC
			", \ARRAY_A);
			if (! \is_array($dbResult) || ! \count($dbResult)) {
				$out['errors']['data'] = \__('There is no login data to export.', 'apocalypse-meow');
			}
			else {
				$data = array();
				foreach ($dbResult as $Row) {
					if ('0' === $Row['ip']) {
						$Row['ip'] = '';
					}

					if ('0' === $Row['subnet']) {
						$Row['subnet'] = '';
					}

					if ('0000-00-00 00:00:00' === $Row['date_expires']) {
						$Row['date_expires'] = '';
					}

					if ('fail' === $Row['type']) {
						$Row['type'] = 'Failure';
					}
					else {
						\ucfirst($Row['type']);
					}

					if ('Ban' !== $Row['type']) {
						$Row['count'] = '';
					}
					else {
						common\ref\cast::to_int($Row['count']);
					}

					common\ref\cast::to_int($Row['pardoned']);
					$Row['pardoned'] = $Row['pardoned'] ? $Row['date_expires'] : '';

					$data[] = array(
						'DATE'=>$Row['date_created'],
						'TYPE'=>$Row['type'],
						'IP ADDRESS'=>$Row['ip'],
						'SUBNET'=>$Row['subnet'],
						'USERNAME'=>$Row['username'],
						'BAN EXPIRATION'=>$Row['date_expires'],
						'BAN PARDONED'=>$Row['pardoned'],
						'ATTEMPTS WHILE BANNED'=>$Row['count'],
					);
				}

				$csv = common\format::to_csv($data) . "\n";
				$out['data']['download'] = 'data:text/csv;base64,' . \base64_encode($csv);
			}
		}

		static::send($out);
	}

	/**
	 * Restore Default Settings
	 *
	 * This handles all settings.
	 *
	 * @return void Nothing.
	 */
	public static function reset() {
		$out = static::parse($_POST);

		// Try to save the options.
		if (! \count($out['errors'])) {

			// The default.
			$data = options::OPTIONS;

			// And save the values. No need to validate; it will
			// sanitize as soon as the values are loaded, which
			// we'll do shortly.
			\update_option(options::OPTION_NAME, $data);
			options::load(true);
			$out['data'] = options::get();

			// Almost done. We need to convert the data a little bit to
			// match the calling function's expectations.

			// The fail window gets translated to minutes to make the
			// numbers easier to deal with.
			$out['data']['login']['fail_window'] = \ceil($out['data']['login']['fail_window'] / 60);

			// The whitelist and blacklist need to be collapsed.
			$out['data']['login']['blacklist'] = \trim(\implode("\n", $out['data']['login']['blacklist']));
			$out['data']['login']['whitelist'] = \trim(\implode("\n", $out['data']['login']['whitelist']));

			// Lastly, convert any boolean values to integers.
			foreach ($out['data'] as $k=>$v) {
				foreach ($v as $k2=>$v2) {
					if (\is_bool($v2)) {
						$out['data'][$k][$k2] = $v2 ? 1 : 0;
					}
				}
			}

			$out['data']['status'] = 200;
		}

		static::send($out);
	}

	/**
	 * Retroactive Reset
	 *
	 * Force users to upgrade weak passwords at login.
	 *
	 * @return void Nothing.
	 */
	public static function retroactive_reset() {
		$out = static::parse($_POST, true, 'read');

		if (! isset($_POST['password'])) {
			$_POST['password'] = '';
		}

		// Validate the password.
		if (! \count($out['errors'])) {
			// Convert error messages in the event the new password
			// doesn't meet all the requirements.
			if (! login::password_rules($_POST['password'], $_POST['password'])) {
				$error = new WP_Error();
				login::password_rules_error($error);
				$num = 0;
				foreach ($error->errors as $v) {
					if (\is_array($v)) {
						foreach ($v as $v2) {
							++$num;
							$out['errors']["password-{$num}"] = $v2;
						}
					}
				}
			}
		}

		// Update the user password!
		if (! \count($out['errors'])) {
			$current_user = \wp_get_current_user();
			if ($current_user->ID) {
				$out['data']['success'] = true;
				\wp_set_password($_POST['password'], $current_user->ID);
			}
			else {
				$out['errors']['other'] = \__('The password could not be reset.', 'apocalypse-meow');
			}
		}

		static::send($out);
	}

	/**
	 * Retroactive Reset
	 *
	 * Generate a nice random password for a person.
	 *
	 * @return void Nothing.
	 */
	public static function retroactive_reset_generate() {
		$out = static::parse($_POST, true, 'read');

		if (! \count($out['errors'])) {
			$out['data']['password'] = \wp_generate_password(options::MIN_PASSWORD_EXEMPT_LENGTH + 5, true);
		}

		static::send($out);
	}

	/**
	 * Save Settings
	 *
	 * This handles all settings.
	 *
	 * @return void Nothing.
	 */
	public static function settings() {
		$out = static::parse($_POST);

		// Try to save the options.
		if (! \count($out['errors'])) {

			// There are two variables that are presented differently
			// than they are stored. We need to convert them back
			// before trying to save.
			if (isset($_POST['login']['fail_window'])) {
				// Convert this back to seconds.
				common\ref\cast::to_int($_POST['login']['fail_window'], true);
				$_POST['login']['fail_window'] *= 60;
			}
			if (isset($_POST['login']['whitelist'])) {
				// This should be an array.
				common\ref\cast::to_string($_POST['login']['whitelist'], true);
				$_POST['login']['whitelist'] = \explode("\n", $_POST['login']['whitelist']);
			}
			if (isset($_POST['login']['blacklist'])) {
				// This should be an array.
				common\ref\cast::to_string($_POST['login']['blacklist'], true);
				$_POST['login']['blacklist'] = \explode("\n", $_POST['login']['blacklist']);
			}

			// Now we can shove it into the mold.
			$original = options::get();
			$data = common\data::parse_args($_POST, $original);

			// And save the values. No need to validate; it will
			// sanitize as soon as the values are loaded, which
			// we'll do shortly.
			\update_option(options::OPTION_NAME, $data);
			options::load(true);
			$out['data'] = options::get();

			// Almost done. We need to convert the data a little bit to
			// match the calling function's expectations.

			// The fail window gets translated to minutes to make the
			// numbers easier to deal with.
			$out['data']['login']['fail_window'] = \ceil($out['data']['login']['fail_window'] / 60);

			// The whitelist and blacklist need to be collapsed.
			$out['data']['login']['blacklist'] = \trim(\implode("\n", $out['data']['login']['blacklist']));
			$out['data']['login']['whitelist'] = \trim(\implode("\n", $out['data']['login']['whitelist']));

			// Lastly, convert any boolean values to integers.
			foreach ($out['data'] as $k=>$v) {
				foreach ($v as $k2=>$v2) {
					if (\is_bool($v2)) {
						$out['data'][$k][$k2] = $v2 ? 1 : 0;
					}
				}
			}

			$out['data']['status'] = 200;
		}

		static::send($out);
	}

	/**
	 * Pardon
	 *
	 * Remove a ban.
	 *
	 * @return void Nothing.
	 */
	public static function pardon() {
		$out = static::parse($_POST);

		if (! isset($_POST['id'])) {
			$out['errors']['id'] = \__('A record ID is required.', 'apocalypse-meow');
		}

		// Try to save the options.
		if (! \count($out['errors'])) {
			if (! login::pardon($_POST['id'])) {
				$out['errors']['id'] = \__('Invalid record ID.', 'apocalypse-meow');
			}
			// Okedoke!
			else {
				$out['data']['success'] = true;
				$out['status'] = 200;
			}
		}

		static::send($out);
	}

	/**
	 * Stats!
	 *
	 * Make pretty data.
	 *
	 * @return void Nothing.
	 */
	public static function stats() {
		global $wpdb;
		$out = static::parse($_POST, true);

		// Bail now if there are problems.
		if (\count($out['errors'])) {
			static::send($out);
		}

		$out['data'] = array(
			'stats'=>array(
				'total'=>0,
				'date_min'=>'',
				'date_max'=>'',
				'days'=>0,
				'bans'=>array(
					'total'=>0,
					'pardons'=>0,
					'attempts'=>0,
				),
				'fails'=>array(
					'total'=>0,
					'enumeration'=>0,
					'usernames'=>array(
						'valid'=>0,
						'invalid'=>0,
						'unique'=>array(),
					),
					'subnets'=>array(),
					'ips'=>array(),
				),
				'country'=>array(),
				'status'=>array(
					'ban'=>0,
					'fail'=>0,
					'success'=>0,
				),
				'ip'=>array(
					'IPv4'=>0,
					'IPv6'=>0,
				),
				'username'=>array(),
				'volume'=>array(),
			),
		);

		// If there is not a minimum date, there is nothing to pull.
		$date_min = $wpdb->get_var("SELECT MIN(DATE(`date_created`)) FROM `{$wpdb->prefix}meow2_log` WHERE `type` != 'alert'");
		if (\is_null($date_min)) {
			static::send($out);
		}
		$date_max = \current_time('Y-m-d');

		$out['data']['stats']['date_min'] = $date_min;
		$out['data']['stats']['date_max'] = $date_max;
		$out['data']['stats']['days'] = common\data::datediff($date_min, $date_max);

		// Fill in the dates.
		for ($x = 0; \date('Y-m-d', \strtotime("+$x days", \strtotime($date_min))) <= $date_max; ++$x) {
			$date = \date('Y-m-d', \strtotime("+$x days", \strtotime($date_min)));
			$out['data']['stats']['volume'][$date] = array(
				'ban'=>0,
				'fail'=>0,
				'success'=>0,
			);
		}

		// Pull stats!
		$dbResult = $wpdb->get_results("
			SELECT
				`ip`,
				`subnet`,
				`username`,
				DATE(`date_created`) AS `date`,
				`type`,
				`count`,
				`pardoned`
			FROM `{$wpdb->prefix}meow2_log`
			WHERE `type` != 'alert'
			ORDER BY `date_created` ASC
		", \ARRAY_A);
		foreach ($dbResult as $Row) {
			common\ref\cast::to_int($Row['count']);
			common\ref\cast::to_int($Row['pardoned']);

			++$out['data']['stats']['total'];
			++$out['data']['stats']['status'][$Row['type']];
			++$out['data']['stats']['volume'][$Row['date']][$Row['type']];

			if ('fail' === $Row['type']) {
				++$out['data']['stats']['fails']['total'];

				if ($Row['username'] && (core::ENUMERATION_USERNAME !== $Row['username'])) {
					if (! isset($out['data']['stats']['username'][$Row['username']])) {
						$out['data']['stats']['username'][$Row['username']] = 1;
					}
					else {
						++$out['data']['stats']['username'][$Row['username']];
					}
				}

				if (core::ENUMERATION_USERNAME === $Row['username']) {
					++$out['data']['stats']['fails']['enumeration'];
				}
				else {
					if ($Row['username'] && login::username_exists($Row['username'])) {
						++$out['data']['stats']['fails']['usernames']['valid'];
					}
					else {
						++$out['data']['stats']['fails']['usernames']['invalid'];
					}
				}

				$out['data']['stats']['fails']['usernames']['unique'][] = $Row['username'];

				if ($Row['ip']) {
					$out['data']['stats']['fails']['ips'][] = $Row['ip'];
				}

				if ($Row['subnet']) {
					$out['data']['stats']['fails']['subnets'][] = $Row['subnet'];
					if (false === \strpos($Row['subnet'], ':')) {
						++$out['data']['stats']['ip']['IPv4'];
					}
					else {
						++$out['data']['stats']['ip']['IPv6'];
					}
				}
			}
			elseif ('ban' === $Row['type']) {
				$out['data']['stats']['bans']['pardons'] += $Row['pardoned'];
				$out['data']['stats']['bans']['attempts'] += $Row['count'];
				++$out['data']['stats']['bans']['total'];
			}
		}

		// That's the hard part out of the way. Now some fixing.
		$out['data']['stats']['fails']['subnets'] = \count(\array_unique($out['data']['stats']['fails']['subnets']));
		$out['data']['stats']['fails']['ips'] = \count(\array_unique($out['data']['stats']['fails']['ips']));
		$out['data']['stats']['fails']['usernames']['unique'] = \count(\array_unique($out['data']['stats']['fails']['usernames']['unique']));

		$translated = array(
			'ban'=>\__('Ban', 'apocalypse-meow'),
			'fail'=>\__('Failure', 'apocalypse-meow'),
			'success'=>\__('Success', 'apocalypse-meow'),
			'other'=>\__('Other', 'apocalypse-meow'),
		);

		// Reformat volume.
		$raw = array(
			$translated['ban']=>array(),
			$translated['fail']=>array(),
			$translated['success']=>array(),
		);
		$labels = array();
		foreach ($out['data']['stats']['volume'] as $k=>$v) {
			foreach (array('ban', 'fail', 'success') as $field) {
				$raw[$translated[$field]][] = $v[$field];
			}
			$labels[] = \strtotime($k);
		}
		$out['data']['stats']['volume'] = array(
			'labels'=>$labels,
			'series'=>\array_values($raw),
		);

		// Reformat status.
		$raw = array(
			$translated['ban']=>$out['data']['stats']['status']['ban'],
			$translated['fail']=>$out['data']['stats']['status']['fail'],
			$translated['success']=>$out['data']['stats']['status']['success'],
		);
		\arsort($raw);
		$out['data']['stats']['status'] = array(
			'labels'=>\array_keys($raw),
			'series'=>\array_values($raw),
		);

		// Reformat usernames.
		$raw = common\data::array_otherize($out['data']['stats']['username'], 6, $translated['other']);
		if (\is_array($raw) && \count($raw)) {
			$out['data']['stats']['username'] = array(
				'labels'=>\array_keys($raw),
				'series'=>\array_values($raw),
			);
		}
		else {
			$out['data']['stats']['username'] = array(
				'labels'=>array(),
				'series'=>array(),
			);
		}

		// Reformat IP types.
		$out['data']['stats']['ip'] = array(
			'labels'=>\array_keys($out['data']['stats']['ip']),
			'series'=>\array_values($out['data']['stats']['ip']),
		);

		static::send($out);
	}

	/**
	 * Tools: MD5 Passwords
	 *
	 * Find and destroy any passwords still hashed with MD5.
	 *
	 * @return void Nothing.
	 */
	public static function tools_md5() {
		$out = static::parse($_POST, true);

		if (! \count($out['errors'])) {
			$affected = tools::rehash_md5_passwords();

			$out['data']['success'] = true;
			$out['status'] = 200;
			$out['msg'] = common\format::inflect(
				$affected,
				\__('%d password has', 'apocalypse-meow'),
				\__('%d passwords have', 'apocalypse-meow')
			) . ' ' . \__('been securely reset and rehashed.', 'apocalypse-meow');
		}

		static::send($out);
	}

	/**
	 * Tools: Reset All Passwords!
	 *
	 * Reset all user passwords, but make sure the current user is the
	 * last one done.
	 *
	 * @return void Nothing.
	 */
	public static function tools_reset() {
		global $wpdb;
		$out = static::parse($_POST, true);
		$out['data'] = array(
			'remaining'=>0,
			'last'=>0,
		);

		if (! \count($out['errors'])) {
			$user_id = \wp_get_current_user();
			$user_id = (int) $user_id->ID;

			$last = $_POST['last'] ?? 0;
			common\ref\cast::to_int($last, true);
			common\ref\sanitize::to_range($last, 0);

			$message = isset($_POST['message']) && isset($_POST['email']) && \intval($_POST['email']) ? $_POST['message'] : '';
			common\ref\cast::to_string($message, true);
			common\ref\sanitize::whitespace($message, 2);

			// If sending notifications, we can't do nearly as much in
			// one go.
			$limit = $message ? 15 : 150;

			$max = (int) $wpdb->get_var("SELECT MAX(`ID`) FROM `{$wpdb->users}`");

			// Grab users within range, excepting the current user.
			$users = array();
			$dbResult = $wpdb->get_results("
				SELECT `ID`
				FROM `{$wpdb->users}`
				WHERE
					`ID` > $last AND
					`ID` != $user_id
				ORDER BY `ID` ASC
				LIMIT $last, $limit
			", \ARRAY_A);
			if (\is_array($dbResult) && \count($dbResult)) {
				// Add the IDs.
				foreach ($dbResult as $Row) {
					$users[] = (int) $Row['ID'];
				}

				// Run it.
				tools::reset_passwords($users, $message);

				$out['data']['last'] = \max($users);
			}

			// If we're at the end, time to tackle the current user.
			if (
				$user_id &&
				(\count($users) < $limit || (\max($users) === $max))
			) {
				$users = array($user_id);
				tools::reset_passwords($users, $message);
				$out['data']['last'] = $max;
			}

			if ($out['data']['last'] < $max) {
				$out['data']['remaining'] = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->users}` WHERE `ID` > {$out['data']['last']}");
				$out['msg'] = \sprintf(
					\__('Password resets are underway with %d users remaining. Please do not leave this page.', 'apocalypse-meow'),
					$out['data']['remaining']
				);
			}
			else {
				$out['msg'] = \__('All user passwords have been reset.', 'apocalypse-meow');
				if ($message) {
					$out['msg'] .= ' ' . \__('Notification emails have been sent as well.', 'apocalypse-meow');
				}
				$out['msg'] .= ' ' . \__('This page will reload in a few seconds to ensure you are still logged in.', 'apocalypse-meow');
			}

			$out['status'] = 200;
		}

		static::send($out);
	}

	/**
	 * Tools: Rename Admin User(s)
	 *
	 * People should not still be using "admin" or "administrator".
	 *
	 * @return void Nothing.
	 */
	public static function tools_admin() {
		global $wpdb;
		$out = static::parse($_POST, true);
		$out['data'] = array(
			'hasAdmin'=>!! \username_exists('admin'),
			'hasAdministrator'=>!! \username_exists('administrator'),
			'reload'=>false,
		);

		if (! \count($out['errors'])) {
			$users = array();
			$user_login = \wp_get_current_user();
			$user_login = \strtolower($user_login->user_login);

			// Gotta make sure we tackle these in an order such that if
			// the current user is using one, it appears last.
			if ($out['data']['hasAdmin'] && ('admin' !== $user_login)) {
				$users[] = 'admin';
			}
			if ($out['data']['hasAdministrator']) {
				$users[] = 'administrator';
			}
			if ('admin' === $user_login) {
				$users[] = 'admin';
			}

			foreach ($users as $user_old) {
				// Replacement not provided?
				$user_new = $_POST[$user_old] ?? '';
				$user_new = \sanitize_user($user_new);
				if (! $user_new) {
					continue;
				}

				// Invalid username.
				if (! \validate_username($user_new)) {
					$out['errors'][$user_old] = \sprintf(
						\__('"%s" is not a valid username.', 'apocalypse-meow'),
						$user_new
					);
				}
				// Duplicate username.
				elseif (\username_exists($user_new)) {
					$out['errors'][$user_old] = \sprintf(
						\__('The user "%s" already exists.', 'apocalypse-meow'),
						$user_new
					);
				}
				// Go for it.
				elseif (false === ($out['data']['has' . \ucwords($user_old)] = ! tools::rename_user($user_old, $user_new))) {
					$out['msg'] .= ' ' . \sprintf(
						\__('The login "%s" was replaced with "%s".', 'apocalypse-meow'),
						$user_old,
						$user_new
					);
				}
			}

			$out['msg'] = \trim($out['msg']);

			// Do we need to reload?
			if (
				(('admin' === $user_login) && ! $out['data']['hasAdmin']) ||
				(('administrator' === $user_login) && ! $out['data']['hasAdministrator'])
			) {
				$out['data']['reload'] = true;
				$out['msg'] .= ' ' . \__('This page will reload in a few seconds to ensure you are still logged in.', 'apocalypse-meow');
			}
		}

		static::send($out);
	}

	/**
	 * Tools: Rename Admin User(s)
	 *
	 * People should not still be using "admin" or "administrator".
	 *
	 * @return void Nothing.
	 */
	public static function tools_sessions() {
		global $wpdb;
		$out = static::parse($_POST, true);
		$out['data']['sessions'] = array();

		if (! \count($out['errors'])) {
			$dbResult = $wpdb->get_results("
				SELECT
					u.ID AS user_id,
					u.user_login AS login,
					u.user_email AS email,
					m.meta_value
				FROM
					`{$wpdb->usermeta}` AS m LEFT JOIN
					`{$wpdb->users}` AS u ON m.user_id=u.ID
				WHERE
					m.meta_key = 'session_tokens'
				ORDER BY u.user_login ASC
			", \ARRAY_A);
			if (\is_array($dbResult) && \count($dbResult)) {
				foreach ($dbResult as $Row) {
					$Row['user_id'] = (int) $Row['user_id'];
					if (! $Row['user_id']) {
						continue;
					}

					common\ref\sanitize::email($Row['email']);
					$Row['login'] = \strtolower($Row['login']);

					try {
						$meta = \unserialize($Row['meta_value']);
						if (! \is_array($meta) || ! \count($meta)) {
							\delete_user_meta($Row['user_id'], 'session_tokens');
							continue;
						}
					} catch (\Throwable $e) {
						\delete_user_meta($Row['user_id'], 'session_tokens');
						continue;
					}

					if (! isset($out['data']['sessions'][$Row['user_id']])) {
						$out['data']['sessions'][$Row['user_id']] = array(
							'user_id'=>$Row['user_id'],
							'email'=>$Row['email'],
							'login'=>$Row['login'],
							'sessions'=>array(),
						);
					}

					foreach ($meta as $k=>$v) {
						// If it is expired, remove it to save overhead
						// down the road.
						if ($v['expiration'] < \time()) {
							tools::kill_session($Row['user_id'], $k);
							continue;
						}

						$out['data']['sessions'][$Row['user_id']]['sessions'][] = array(
							'session_id'=>$k,
							'date_created'=>\date('Y-m-d H:i:s', $v['login']),
							'date_expires'=>\date('Y-m-d H:i:s', $v['expiration']),
							'ip'=>common\sanitize::ip($v['ip'], true),
							'ua'=>common\sanitize::whitespace($v['ua']),
						);
					}

					if (! \count($out['data']['sessions'][$Row['user_id']]['sessions'])) {
						unset($out['data']['sessions'][$Row['user_id']]);
					}
				}

				// Let's do some sorting.
				$out['data']['sessions'] = \array_values($out['data']['sessions']);
				foreach ($out['data']['sessions'] as $k=>$v) {
					\usort(
						$out['data']['sessions'][$k]['sessions'],
						function($a, $b) {
							return $a['date_expires'] <=> $b['date_expires'];
						}
					);
				}
			}

			$out['status'] = 200;
		}

		static::send($out);
	}

	/**
	 * Tools: Rename Admin User(s)
	 *
	 * People should not still be using "admin" or "administrator".
	 *
	 * @return void Nothing.
	 */
	public static function tools_session_delete() {
		global $wpdb;
		$out = static::parse($_POST, true);

		if (! \count($out['errors'])) {
			$user_id = $_POST['user_id'] ?? 0;
			$session_id = $_POST['session_id'] ?? '';

			// We don't really care about the status.
			tools::kill_session($user_id, $session_id);

			$out['data']['success'] = true;
			$out['msg'] = \__('The session has been deleted.', 'apocalypse-meow');
			$out['status'] = 200;
		}

		static::send($out);
	}

	// ----------------------------------------------------------------- end handlers
}
