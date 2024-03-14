<?php
/**
 * CLI: Activity
 *
 * Manage and view login activity data.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow\cli;

use blobfolio\wp\meow\core;
use blobfolio\wp\meow\login;
use blobfolio\wp\meow\tools;
use blobfolio\wp\meow\vendor\common;
use WP_CLI;
use WP_CLI\Utils;

// Add the main command.
if (! \class_exists('WP_CLI') || ! \class_exists('WP_CLI_Command')) {
	return;
}

// Add the main command.
WP_CLI::add_command(
	'meow activity',
	\MEOW_BASE_CLASS . 'cli\\activity',
	array(
		'before_invoke'=>function() {
			if (\is_multisite()) {
				WP_CLI::error(\__('This plugin cannot be used on Multi-Site.', 'apocalypse-meow'));
			}

			if (! \defined('FS_CHMOD_DIR')) {
				\define('FS_CHMOD_DIR', (@\fileperms(\ABSPATH) & 0777 | 0755));
			}
			if (! \defined('FS_CHMOD_FILE')) {
				\define('FS_CHMOD_FILE', (@\fileperms(\ABSPATH . 'index.php') & 0777 | 0644));
			}
		},
	)
);

/**
 * Login and Session Activity
 *
 * View and export a history of login attempts, WordPress user sessions,
 * and manage record storage.
 */
class activity extends \WP_CLI_Command {

	/**
	 * List Login Activity
	 *
	 * Apocalypse Meow records every login attempt, whether successful
	 * or not, in order to detect and mitigate brute-force attacks
	 * being made against the web site.
	 *
	 * This tool allows those records to be searched and/or exported.
	 *
	 * ## OPTIONS
	 *
	 * [--from=<mindate>]
	 * : Earliest date.
	 *
	 * [--to=<maxdate>]
	 * : Latest date.
	 *
	 * [--type=<type>]
	 * : The type of records to include.
	 * ---
	 * options:
	 *   - ban
	 *   - fail
	 *   - success
	 * ---
	 *
	 * [--reverse]
	 * : Reverse the order of the list.
	 *
	 * [--limit=<num>]
	 * : Limit the number of records displayed.
	 *
	 * [--export=<path>]
	 * : Dump the results to a CSV or XLS at <path>. The path should end
	 * in ".csv" or ".xls" accordingly.
	 *
	 * [--overwrite]
	 * : Overwrite <path> if it exists.
	 *
	 * @param array $args N/A.
	 * @param array $assoc_args Flags.
	 * @return bool True.
	 *
	 * @subcommand list
	 */
	public function _list($args=null, $assoc_args=array()) {
		global $wpdb;

		$args = null;
		$date_min = common\sanitize::date(Utils\get_flag_value($assoc_args, 'from'));
		$date_max = common\sanitize::date(Utils\get_flag_value($assoc_args, 'to'));
		$type = common\mb::strtolower(Utils\get_flag_value($assoc_args, 'type'));
		$export = Utils\get_flag_value($assoc_args, 'export');
		$overwrite = !! Utils\get_flag_value($assoc_args, 'overwrite');
		$reverse = !! Utils\get_flag_value($assoc_args, 'reverse');
		$limit = common\cast::to_int(Utils\get_flag_value($assoc_args, 'limit'), true);

		// The search criteria.
		$conds = array();
		if ('0000-00-00' !== $date_min) {
			$conds[] = "DATE(`date_created`) >= '$date_min'";
		}
		if ('0000-00-00' !== $date_max) {
			$conds[] = "DATE(`date_created`) <= '$date_min'";
		}
		if ($type && ! \in_array($type, login::LOGIN_LOG_TYPES, true)) {
			WP_CLI::error(
				\__('Invalid record type. Should be "ban", "fail", or "success".', 'apocalypse-meow')
			);
		}
		elseif ($type) {
			$conds[] = "`type`='$type'";
		}
		else {
			$conds[] = "`type` != 'alert'";
		}

		$conds = \implode(' AND ', $conds);

		$translated = array(
			'Created'=>\__('Created', 'apocalypse-meow'),
			'Expires'=>\__('Expires', 'apocalypse-meow'),
			'Type'=>\__('Type', 'apocalypse-meow'),
			'IP'=>\__('IP', 'apocalypse-meow'),
			'Subnet'=>\__('Subnet', 'apocalypse-meow'),
			'Username'=>\__('Username', 'apocalypse-meow'),
			'Persistence'=>\__('Persistence', 'apocalypse-meow'),
			'Pardoned'=>\__('Pardoned', 'apocalypse-meow'),
		);

		// The search.
		$dbResult = $wpdb->get_results("
			SELECT
				`date_created` AS `{$translated['Created']}`,
				`date_expires` AS `{$translated['Expires']}`,
				`type` AS `{$translated['Type']}`,
				`ip` AS `{$translated['IP']}`,
				`subnet` AS `{$translated['Subnet']}`,
				`username` AS `{$translated['Username']}`,
				`count` AS `{$translated['Persistence']}`,
				`pardoned` AS `{$translated['Pardoned']}`
			FROM `{$wpdb->prefix}meow2_log`
			WHERE $conds
			ORDER BY `date_created` " . ($reverse ? 'DESC' : 'ASC') .
			($limit > 0 ? " LIMIT $limit" : ''), \ARRAY_A);
		if (! \is_array($dbResult) || ! \count($dbResult)) {
			WP_CLI::success(
				\__('No login data is available.', 'apocalypse-meow')
			);
			return true;
		}

		// Crunch the data.
		$data = array();
		$headers = null;
		foreach ($dbResult as $Row) {
			if ('0' === $Row[$translated['IP']]) {
				$Row[$translated['IP']] = '';
			}
			if ('0' === $Row[$translated['Subnet']]) {
				$Row[$translated['Subnet']] = '';
			}

			// There are a few ban-specific fields. If we are searching
			// by type, we can just remove them.
			if ($type && ('ban' !== $type)) {
				unset($Row[$translated['Pardoned']]);
				unset($Row[$translated['Persistence']]);
				unset($Row[$translated['Expires']]);
			}
			else {
				// Don't need username if only searching bans.
				if ($type && ('ban' === $type)) {
					unset($Row[$translated['Username']]);
				}

				common\ref\cast::to_int($Row[$translated['Persistence']]);
				$Row[$translated['Persistence']] = $Row[$translated['Persistence']] > 1 ? $Row[$translated['Persistence']] : '';
				$Row[$translated['Pardoned']] = \intval($Row[$translated['Pardoned']]) ? \__('Yes', 'apocalypse-meow') : '';

				if ('ban' !== $Row[$translated['Type']]) {
					$Row[$translated['Pardoned']] = '';
					$Row[$translated['Persistence']] = '';
					$Row[$translated['Expires']] = '';
					if (core::ENUMERATION_USERNAME === $Row[$translated['Username']]) {
						$Row[$translated['Username']] = '(' . \__('User Enumeration', 'apocalypse-meow') . ')';
					}
				}
			}

			$data[] = $Row;
			if (! \is_array($headers)) {
				$headers = \array_keys($Row);
			}
		}

		WP_CLI\Utils\format_items('table', $data, $headers);

		WP_CLI::success(
			common\format::inflect(
				\count($data),
				\__('%d matching login record was found.', 'apocalypse-meow'),
				\__('%d matching login records were found.', 'apocalypse-meow')
			)
		);

		// Try to kick it into a file!

		if ($export) {
			common\ref\file::path($export, false);
			if (! $export) {
				WP_CLI::error(
					\__('The export file path is not valid.', 'apocalypse-meow')
				);
			}

			$ext = \strtolower(\pathinfo($export, \PATHINFO_EXTENSION));
			if (('csv' !== $ext) && ('xls' !== $ext)) {
				WP_CLI::error(
					\__('The export file path should end in ".csv" or ".xls".', 'apocalypse-meow')
				);
			}

			if (! $overwrite && @\file_exists($export)) {
				WP_CLI::error(
					"$export " . \__('already exists. Use --overwrite to replace it.', 'apocalypse-meow')
				);
			}

			if ('csv' === $ext) {
				$out = common\format::to_csv($data, $headers);
			}
			else {
				$out = common\format::to_xls($data, $headers);
			}
			@\file_put_contents($export, $out);
			@\chmod($export, \FS_CHMOD_FILE);
			if (@\file_exists($export)) {
				WP_CLI::success(
					\__('The data has been saved to', 'apocalypse-meow') . " $export."
				);
			}
			else {
				WP_CLI::error(
					\__('The data could not be written to', 'apocalypse-meow') . " $export."
				);
			}
		}

		return true;
	}

	/**
	 * Prune Old Records
	 *
	 * Brute-force login prevention relies on record-keeping. Over time,
	 * with lots of activity, that data might start to pose storage or
	 * performance problems.
	 *
	 * Use this tool to manually remove old records, or all records.
	 *
	 * ## OPTIONS
	 *
	 * <limit|all>
	 * : Removal criteria, either an age as a date or number of days,
	 * or "all" to start from scratch. Numeric values are assumed to
	 * represent days, so please pass dates in YYYY-MM-DD format to
	 * avoid confusion.
	 *
	 * ## EXAMPLES
	 *
	 *     wp meow activity prune 2015-01-01
	 *     wp meow activity prune 60
	 *     wp meow activity prune all
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 */
	public function prune($args=null) {
		global $wpdb;

		if (! \is_array($args) || ! \count($args)) {
			WP_CLI::error(
				\__('This action requires a date, a number of days, or "all".', 'apocalypse-meow')
			);
		}
		$args = common\data::array_pop_top($args);

		if ('all' === \strtolower($args)) {
			$conds = '';
		}
		elseif (\is_numeric($args)) {
			$args = (int) $args;
			common\ref\sanitize::to_range($args, 0);
			$conds = "DATE(`date_created`) < '" . \date('Y-m-d', \strtotime("-$args days", \current_time('timestamp'))) . "'";
		}
		else {
			common\ref\sanitize::date($args);
			if ('0000-00-00' === $args) {
				WP_CLI::error(
					\__('The date could not be parsed. Please try YYYY-MM-DD format.', 'apocalypse-meow')
				);
			}
			$conds = "DATE(`date_created`) < '$args'";
		}

		// Easy enough...
		$before = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow2_log`");
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow2_log` WHERE $conds");
		$after = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow2_log`");

		$changed = \abs($before - $after);

		if (! $changed) {
			WP_CLI::warning(
				\__('No records were removed.', 'apocalypse-meow')
			);
			return false;
		}

		WP_CLI::success(
			common\format::inflect(
				$changed,
				\__('%d record was deleted.', 'apocalypse-meow'),
				\__('%d records were deleted.', 'apocalypse-meow')
			)
		);

		return true;
	}

	/**
	 * List WP User Sessions
	 *
	 * WordPress generates a unique Session ID each time a user logs
	 * into the site. Aside from providing some useful diagnostic
	 * information, such as browser and network information, it also
	 * provides a server-side mechanism for continually revalidating
	 * the session (i.e. regardless of whether or not the user's
	 * computer has the right cookie).
	 *
	 * This tool allows session records to be displayed and/or exported.
	 *
	 * See also: wp meow security revoke-session
	 *
	 * ## OPTIONS
	 *
	 * [--user_login=<id|login|email>]
	 * : Pull sessions for a specific user login.
	 *
	 * [--relative]
	 * : Show expiration relative to now.
	 *
	 * [--export=<path>]
	 * : Dump the results to a CSV or XLS at <path>. The path should end
	 * in ".csv" or ".xls" accordingly.
	 *
	 * [--overwrite]
	 * : Overwrite <path> if it exists.
	 *
	 * @see {wp meow security revoke-session}
	 *
	 * @param array $args N/A.
	 * @param array $assoc_args Flags.
	 * @return bool True.
	 */
	public function sessions($args=null, $assoc_args=array()) {
		global $wpdb;

		// User input.
		$args = null;
		$relative = !! Utils\get_flag_value($assoc_args, 'relative');
		$export = Utils\get_flag_value($assoc_args, 'export');
		$overwrite = !! Utils\get_flag_value($assoc_args, 'overwrite');
		$user_key = Utils\get_flag_value($assoc_args, 'user_login');

		// Validate the user, if applicable.
		$user = false;
		if ($user_key) {
			// Maybe an ID?
			if (\is_numeric($user_key)) {
				$user = \get_user_by('ID', $user_key);
			}
			// Maybe this is an email address?
			if ((false === $user) && (false !== \strpos($user_key, '@'))) {
				$user = \get_user_by('email', $user_key);
			}
			// Try for username instead.
			if ((false === $user)) {
				$user = \get_user_by('login', $user_key);
			}

			// Bad user?
			if (false === $user) {
				WP_CLI::error(
					\__('This user login is not valid.', 'apocalypse-meow')
				);
			}
		}

		// Search!
		$conds = array(
			"m.meta_key='session_tokens'",
		);
		if (false !== $user) {
			$conds[] = "m.user_id={$user->ID}";
		}
		$conds = \implode(' AND ', $conds);

		$dbResult = $wpdb->get_results("
			SELECT
				u.ID AS user_id,
				u.user_login AS login,
				u.user_email AS email,
				m.meta_value
			FROM
				`{$wpdb->usermeta}` AS m LEFT JOIN
				`{$wpdb->users}` AS u ON m.user_id=u.ID
			WHERE $conds
			ORDER BY u.user_login ASC
		", \ARRAY_A);
		if (! \is_array($dbResult) || ! \count($dbResult)) {
			WP_CLI::error(
				\__('No matching sessions were found.', 'apocalypse-meow')
			);
		}

		$translated = array(
			'user_id'=>\__('User ID', 'apocalypse-meow'),
			'email'=>\__('Email', 'apocalypse-meow'),
			'username'=>\__('Username', 'apocalypse-meow'),
			'session_id'=>\__('Session ID', 'apocalypse-meow'),
			'created'=>\__('Created', 'apocalypse-meow'),
			'expires'=>\__('Expires', 'apocalypse-meow'),
			'ip'=>\__('IP', 'apocalypse-meow'),
			'ua'=>\__('Browser', 'apocalypse-meow'),
		);

		$data = array();
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

			foreach ($meta as $k=>$v) {
				// If it is expired, remove it to save overhead
				// down the road.
				if ($v['expiration'] < \time()) {
					tools::kill_session($Row['user_id'], $k);
					continue;
				}

				$v['login'] = \date('Y-m-d H:i:s', $v['login']);
				if ($relative) {
					$v['expiration'] = \human_time_diff($v['expiration'], \current_time('timestamp'));
				}
				else {
					$v['expiration'] = \date('Y-m-d H:i:s', $v['expiration']);
				}

				$data[] = array(
					$translated['user_id']=>$Row['user_id'],
					$translated['email']=>$Row['email'],
					$translated['username']=>$Row['login'],
					$translated['session_id']=>$k,
					$translated['created']=>$v['login'],
					$translated['expires']=>$v['expiration'],
					$translated['ip']=>common\sanitize::ip($v['ip']),
					$translated['ua']=>common\sanitize::whitespace($v['ua']),
				);
			}
		}

		// Let's do some sorting.
		\usort(
			$data,
			function($a, $b) use($translated) {
				return $a[$translated['expires']] <=> $b[$translated['expires']];
			}
		);

		$headers = \array_values($translated);
		WP_CLI\Utils\format_items('table', $data, $headers);

		WP_CLI::success(
			common\format::inflect(
				\count($data),
				\__('%d matching login session was found.', 'apocalypse-meow'),
				\__('%d matching login sessions were found.', 'apocalypse-meow')
			)
		);

		// Try to kick it into a file!
		if ($export) {
			common\ref\file::path($export, false);
			if (! $export) {
				WP_CLI::error(
					\__('The export file path is not valid.', 'apocalypse-meow')
				);
			}

			$ext = \strtolower(\pathinfo($export, \PATHINFO_EXTENSION));
			if (('csv' !== $ext) && ('xls' !== $ext)) {
				WP_CLI::error(
					\__('The export file path should end in ".csv" or ".xls".', 'apocalypse-meow')
				);
			}

			if (! $overwrite && @\file_exists($export)) {
				WP_CLI::error(
					"$export " . \__('already exists. Use --overwrite to replace it.', 'apocalypse-meow')
				);
			}

			if ('csv' === $ext) {
				$out = common\format::to_csv($data, $headers);
			}
			else {
				$out = common\format::to_xls($data, $headers);
			}
			@\file_put_contents($export, $out);
			@\chmod($export, \FS_CHMOD_FILE);
			if (@\file_exists($export)) {
				WP_CLI::success(
					\__('The data has been saved to', 'apocalypse-meow') . " $export."
				);
			}
			else {
				WP_CLI::error(
					\__('The data could not be written to', 'apocalypse-meow') . " $export."
				);
			}
		}

		return true;
	}
}
