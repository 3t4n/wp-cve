<?php
/**
 * CLI: Jail
 *
 * View and manage the login jail and whitelist.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow\cli;

use blobfolio\wp\meow\login;
use blobfolio\wp\meow\options;
use blobfolio\wp\meow\vendor\common;
use WP_CLI;
use WP_CLI\Utils;

// Add the main command.
if (! \class_exists('WP_CLI') || ! \class_exists('WP_CLI_Command')) {
	return;
}

// Add the main command.
WP_CLI::add_command(
	'meow jail',
	\MEOW_BASE_CLASS . 'cli\\jail',
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
 * Login Jail
 *
 * The primary function of Apocalypse Meow is to detect and mitigate
 * brute-force login attacks being conducted against the site. This is
 * accomplished by tracking failed login attempts and temporarily
 * banning offending network addresses.
 *
 * The jail is where offenders go to wait out their sentence.
 *
 * These commands allow for viewing, exporting, and altering the current
 * jail, as well as managing the global whitelist.
 */
class jail extends \WP_CLI_Command {

	/**
	 * List of Banned Networks
	 *
	 * Network addresses responsible for too many failed login attempts
	 * within a certain window of time are temporarily jailed to prevent
	 * further attempts.
	 *
	 * Use this function to display or export a list of each IP address
	 * and subnet currently banned.
	 *
	 * ## OPTIONS
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
	 * @param array $args N/A.
	 * @param array $assoc_args Flags.
	 * @return bool True.
	 *
	 * @subcommand list
	 */
	public function _list($args=null, $assoc_args=array()) {
		global $wpdb;

		$args = null;
		$export = Utils\get_flag_value($assoc_args, 'export');
		$overwrite = !! Utils\get_flag_value($assoc_args, 'overwrite');
		$relative = !! Utils\get_flag_value($assoc_args, 'relative');

		// The search.
		$cutoff = \current_time('mysql');
		$dbResult = $wpdb->get_results("
			SELECT
				`ip`,
				`subnet`,
				`date_created`,
				`date_expires`,
				`count`,
				`community`
			FROM `{$wpdb->prefix}meow2_log`
			WHERE
				`date_expires` > '$cutoff' AND
				`pardoned`=0
			ORDER BY `date_expires` ASC
		", \ARRAY_A);
		if (! \is_array($dbResult) || ! \count($dbResult)) {
			WP_CLI::success(
				\__('The jail is currently empty!', 'apocalypse-meow')
			);
			return true;
		}

		$translated = array(
			'Created'=>\__('Created', 'apocalypse-meow'),
			'Expires'=>\__('Expires', 'apocalypse-meow'),
			'IP'=>\__('IP', 'apocalypse-meow'),
			'Subnet'=>\__('Subnet', 'apocalypse-meow'),
			'Persistence'=>\__('Persistence', 'apocalypse-meow'),
			'Community'=>\__('Community', 'apocalypse-meow'),
		);
		$headers = \array_values($translated);

		// Crunch the data.
		$data = array();
		foreach ($dbResult as $Row) {
			if ('0' === $Row['ip']) {
				$Row['ip'] = '';
			}
			if ('0' === $Row['subnet']) {
				$Row['subnet'] = '';
			}

			$Row['count'] = (int) $Row['count'];
			$Row['community'] = (int) $Row['community'];

			$data[] = array(
				$translated['Created']=>$Row['date_created'],
				$translated['Expires']=>$relative ? \human_time_diff(\strtotime($Row['date_expires']), \current_time('timestamp')) : $Row['date_expires'],
				$translated['IP']=>$Row['ip'],
				$translated['Subnet']=>$Row['subnet'],
				$translated['Persistence']=>$Row['count'] > 1 ? $Row['count'] : '',
				$translated['Community']=>$Row['community'] ? \__('Yes', 'apocalypse-meow') : '',
			);
		}

		// Print it.
		WP_CLI\Utils\format_items('table', $data, $headers);

		WP_CLI::success(
			common\format::inflect(
				\count($data),
				\__('There is currently %d banned network.', 'apocalypse-meow'),
				\__('There are currently %d banned networks.', 'apocalypse-meow')
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
	 * Ban a Network
	 *
	 * Use this function to manually block an IP address or subnet from
	 * the login form for a specified period of time.
	 *
	 * ## OPTIONS
	 *
	 * <IP|Subnet>...
	 * : One or more network addresses to ban.
	 *
	 * [--expires=<datetime>]
	 * : An expiration date. If omitted, the expiration will be set
	 * according to the fail window.
	 *
	 * @param array $args N/A.
	 * @param array $assoc_args Flags.
	 * @return bool True.
	 */
	public function add($args=null, $assoc_args=array()) {
		global $wpdb;

		// First pass at the arguments.
		$bans = array();
		foreach ($args as $v) {
			$v = \preg_replace('/\s/u', '', $v);
			if (! $v) {
				continue;
			}
			// A likely subnet.
			elseif (false !== \strpos($v, '/')) {
				login::sanitize_subnet($v);
				if ($v) {
					$bans[] = $v;
				}
			}
			else {
				common\ref\sanitize::ip($v);
				if ($v) {
					$bans[] = $v;
				}
			}
		}
		$bans = \array_unique($bans);

		// Second pass, take a closer look.
		foreach ($bans as $k=>$v) {
			if (\apply_filters('meow_is_banned', false, $v)) {
				WP_CLI::warning(
					"$v " . \__('is already banned.', 'apocalypse-meow')
				);
				unset($bans[$k]);
				continue;
			}

			$ip = $v;
			if (false !== \strpos($v, '/')) {
				list($ip,$b) = \explode('/', $v);
			}

			if (\apply_filters('meow_is_whitelisted', false, $ip)) {
				WP_CLI::warning(
					"$v " . \__('cannot be banned because it is either whitelisted or belongs to the server.', 'apocalypse-meow')
				);
				unset($bans[$k]);
				continue;
			}
		}

		if (! \count($bans)) {
			WP_CLI::error(
				\__('At least one valid network address is required.', 'apocalypse-meow')
			);
		}

		// Work out the expiration.
		$expires = common\sanitize::datetime(Utils\get_flag_value($assoc_args, 'expires'));
		if ($expires <= \current_time('mysql') || ('0000-00-00 00:00:00' === $expires)) {
			$fail_window = options::get('login-fail_window');
			$expires = \date('Y-m-d H:i:s', \strtotime("+$fail_window seconds", \current_time('timestamp')));
		}

		// Add them!
		\sort($bans);
		foreach ($bans as $v) {
			$data = array(
				'date_created'=>\current_time('mysql'),
				'date_expires'=>$expires,
				'ip'=>'',
				'subnet'=>'',
				'type'=>'ban',
			);
			if (false !== \strpos($v, '/')) {
				$data['subnet'] = $v;
				$data['ip'] = 0;
			}
			else {
				$data['subnet'] = 0;
				$data['ip'] = $v;
			}

			$wpdb->insert(
				"{$wpdb->prefix}meow2_log",
				$data,
				'%s'
			);
		}

		WP_CLI::success(
			common\format::inflect(
				\count($bans),
				\__('%d network has been banned.', 'apocalypse-meow'),
				\__('%d networks have been banned.', 'apocalypse-meow')
			)
		);

		return true;
	}

	/**
	 * Unblock a Network Address
	 *
	 * Use this function to manually remove (or "pardon") an IP address
	 * or subnet that is currently banned from logging into the site.
	 *
	 * ## OPTIONS
	 *
	 * <IP|Subnet>...
	 * : One or more network addresses to pardon.
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 */
	public function remove($args=null) {
		global $wpdb;

		// First pass at the arguments.
		$bans = array();
		foreach ($args as $v) {
			$v = \preg_replace('/\s/u', '', $v);
			if (! $v) {
				continue;
			}
			// A likely subnet.
			elseif (false !== \strpos($v, '/')) {
				login::sanitize_subnet($v);
			}
			else {
				common\ref\sanitize::ip($v);
			}

			if ($v) {
				$bans[] = $v;
			}
		}
		$bans = \array_unique($bans);

		// Second pass, take a closer look.
		foreach ($bans as $k=>$v) {
			if (! \apply_filters('meow_is_banned', false, $v)) {
				WP_CLI::warning(
					"$v " . \__('is not currently banned.', 'apocalypse-meow')
				);
				unset($bans[$k]);
				continue;
			}
		}

		if (! \count($bans)) {
			WP_CLI::error(
				\__('At least one valid network address is required.', 'apocalypse-meow')
			);
		}

		// Pardon them!
		\sort($bans);
		foreach ($bans as $v) {
			$data = array(
				'date_expires'=>\current_time('mysql'),
				'pardoned'=>1,
			);

			$where = array('type'=>'ban');
			if (false !== \strpos($v, '/')) {
				$where['subnet'] = $v;
			}
			else {
				$where['ip'] = $v;
			}

			$wpdb->update(
				"{$wpdb->prefix}meow2_log",
				$data,
				$where,
				array('%s', '%d'),
				'%s'
			);
		}

		WP_CLI::success(
			common\format::inflect(
				\count($bans),
				\__('%d ban has been pardoned.', 'apocalypse-meow'),
				\__('%d bans have been pardoned.', 'apocalypse-meow')
			)
		);

		return true;
	}

	/**
	 * Update White- or Blacklist
	 *
	 * @param string $mode Mode.
	 * @param array $networks Network address(es).
	 * @return array Changes.
	 */
	protected function network_list($mode='whitelist', $networks=null) {
		common\ref\cast::to_string($mode, true);
		common\ref\cast::to_array($networks);

		$out = array(
			'added'=>0,
			'removed'=>0,
		);

		// Bad mode.
		if (! \in_array($mode, array('whitelist', 'blacklist'), true) || ! \count($networks)) {
			return $out;
		}

		$login = options::get('login');

		$adding = array();
		$removing = array();

		// Parse adding vs removing.
		foreach ($networks as $v) {
			// Removing.
			if ('-' === \substr($v, 0, 1)) {
				$removing[] = \substr($v, 1);
			}
			else {
				if ('+' === \substr($v, 0, 1)) {
					$v = \substr($v, 1);
				}
				$adding[] = $v;
			}
		}

		options::sanitize_whitelist($adding);
		options::sanitize_whitelist($removing);

		// New entries.
		if (\count($adding)) {
			foreach ($adding as $v) {
				if (! \in_array($v, $login[$mode], true)) {
					$login[$mode][] = $v;
					++$out['added'];
				}
			}
		}

		// Removing entries.
		$removed = 0;
		if (\count($removing)) {
			foreach ($removing as $v) {
				if (false !== ($key = \array_search($v, $login[$mode], true))) {
					unset($login[$mode][$key]);
					++$out['removed'];
				}
			}
		}

		options::save('login', $login);
		$login = options::get('login');

		return $out;
	}

	/**
	 * Network Whitelist
	 *
	 * A global whitelist allows individual IP addresses or ranges to
	 * be exempted from the automatic brute-force login detection and
	 * ban policy.
	 *
	 * Because bans work at the level of an individual IP address,
	 * it is important to whitelist shared networks, like offices,
	 * otherwise a few simultaneous failures from a couple coworkers
	 * could prevent everyone from getting in.
	 *
	 * Use this function to add or remove one or more networks to this
	 * list.
	 *
	 * ## OPTIONS
	 *
	 * <IP|Subnet>...
	 * : One or more network addresses. To remove, prefix the entry with
	 * a "-"; to add use "+".
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 */
	public function whitelist($args=null) {
		$changed = static::network_list('whitelist', $args);

		if (! $changed['added'] && ! $changed['removed']) {
			WP_CLI::warning(
				\__('No changes were made to the whitelist.', 'apocalypse-meow')
			);
		}
		else {
			if ($changed['added']) {
				WP_CLI::success(
					common\format::inflect(
						$changed['added'],
						\__('Added %d network to the whitelist.', 'apocalypse-meow'),
						\__('Added %d networks to the whitelist.', 'apocalypse-meow')
					)
				);
			}
			if ($changed['removed']) {
				WP_CLI::success(
					common\format::inflect(
						$changed['removed'],
						\__('Removed %d network from the whitelist.', 'apocalypse-meow'),
						\__('Removed %d networks from the whitelist.', 'apocalypse-meow')
					)
				);
			}
		}

		return true;
	}

	/**
	 * Network Blacklist
	 *
	 * A global blacklist prevents individual IP addresses or ranges
	 * from every being able to access the login form.
	 *
	 * Use this function to add or remove one or more networks to this
	 * list.
	 *
	 * ## OPTIONS
	 *
	 * <IP|Subnet>...
	 * : One or more network addresses. To remove, prefix the entry with
	 * a "-"; to add use "+".
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 */
	public function blacklist($args=null) {
		$changed = static::network_list('blacklist', $args);

		if (! $changed['added'] && ! $changed['removed']) {
			WP_CLI::warning(
				\__('No changes were made to the blacklist.', 'apocalypse-meow')
			);
		}
		else {
			if ($changed['added']) {
				WP_CLI::success(
					common\format::inflect(
						$changed['added'],
						\__('Added %d network to the blacklist.', 'apocalypse-meow'),
						\__('Added %d networks to the blacklist.', 'apocalypse-meow')
					)
				);
			}
			if ($changed['removed']) {
				WP_CLI::success(
					common\format::inflect(
						$changed['removed'],
						\__('Removed %d network from the blacklist.', 'apocalypse-meow'),
						\__('Removed %d networks from the blacklist.', 'apocalypse-meow')
					)
				);
			}
		}

		return true;
	}
}
