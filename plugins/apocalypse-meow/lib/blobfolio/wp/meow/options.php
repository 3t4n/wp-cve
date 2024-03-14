<?php
/**
 * Apocalypse Meow options.
 *
 * An options wrapper.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class options {

	const OPTION_NAME = 'meow_options';
	const OPTIONS = array(
		// Core settings.
		'core'=>array(
			'browse_happy'=>false,		// Disable Browse Happy API lookup.
			'dashboard_news'=>false,	// Disable Events/News Dashboard widget.
			'enumeration'=>false,		// Try to stop user enumeration.
			'enumeration_die'=>false,	// Die on attempt.
			'enumeration_fail'=>true,	// Count enumeration attempts as login failures.
			'file_edit'=>false,			// Disable file editor.
			'xmlrpc'=>false,			// Disable xmlrpc.
		),

		// Login settings.
		'login'=>array(
			'alert_by_subnet'=>true,	// Use subnet to determine newness.
			'alert_on_new'=>true,		// Alert on new login.
			'blacklist'=>array(),		// Always blocked IPs.
			'community'=>false,			// Community pooling.
			'fail_limit'=>5,			// Max fails.
			'fail_window'=>43200,		// Fail window.
			'key'=>'REMOTE_ADDR',		// Where in $_SERVER to find IP.
			'nonce'=>false,				// Add a nonce to login form.
			'reset_on_success'=>true,	// Reset fail count on success.
			'subnet_fail_limit'=>20,	// Limit to ban whole subnet.
			'whitelist'=>array(),		// Exempt IPs.
		),

		// Password strength requirements.
		'password'=>array(
			'alpha'=>'required',		// Require letters.
			'length'=>10,				// Min length.
			'numeric'=>'required',		// Require numbers.
			'symbol'=>'optional',		// Require symbols.
			'exempt_length'=>30,		// Minimum length to bypass other requirements.
			'bcrypt'=>false,			// Use bcrypt hashes.
			'bcrypt_cost'=>0,			// Strength setting.
			'retroactive'=>false,		// Force users to upgrade bad passwords.
		),

		// Prune db after X days.
		'prune'=>array(
			'active'=>true,
			'limit'=>90,				// Clear after X days.
		),

		// Registration helpers.
		'register'=>array(
			'cookie'=>false,			// Test cookie support.
			'honeypot'=>false,			// Honeypot field.
			'jail'=>true,				// Integrate w/ login jail.
			'javascript'=>false,		// Test JS support.
			'nonce'=>false,				// Add Nonce.
			'speed'=>false,				// Disallow immediate submission.
		),

		// Template settings.
		'template'=>array(
			'adjacent_posts'=>true,		// Remove previous/next post tags.
			'generator_tag'=>true,		// Remove generator tag.
			'noopener'=>false,			// Add rel="noopener" to vulnerable links.
			'readme'=>false,			// Remove readme file.
			'referrer_policy'=>'',		// Referrer-Policy header.
			'x_content_type'=>false,	// X-Content-Type-Options header.
			'x_frame'=>false,			// X-Frame-Options header.
		),
	);

	// The minimum minimum.
	const MIN_PASSWORD_LENGTH = 10;
	const MIN_PASSWORD_CHARS = 4;
	const MIN_PASSWORD_EXEMPT_LENGTH = 20;

	// Once upon a time, settings were saved to separate options.
	const OLD_OPTIONS = array(
		'meow_alerts'=>'login-alert_on_new',
		'meow_apocalypse_content'=>'',
		'meow_apocalypse_title'=>'',
		'meow_clean_database'=>'prune-active',
		'meow_data_expiration'=>'prune-limit',
		'meow_disable_editor'=>'core-file_edit',
		'meow_disable_xmlrpc'=>'core-xmlrpc',
		'meow_fail_limit'=>'login-fail_limit',
		'meow_fail_reset_on_success'=>'login-reset_on_success',
		'meow_fail_window'=>'login-fail_window',
		'meow_ip_exempt'=>'login-whitelist',
		'meow_ip_key'=>'login-key',
		'meow_login_nonce'=>'login-nonce',
		'meow_password_alpha'=>'password-alpha',
		'meow_password_length'=>'password-length',
		'meow_password_numeric'=>'password-numeric',
		'meow_password_symbol'=>'password-symbol',
		'meow_protect_login'=>'',
		'meow_remove_adjacent_posts_tag'=>'template-adjacent_posts',
		'meow_remove_generator_tag'=>'template-generator_tag',
		'meow_store_ua'=>'',
	);

	// Constants didn't always follow a consistent structure.
	const OLD_CONSTANTS = array(
		'core'=>array(
			'xmlrpc'=>'MEOW_DISABLE_XMLRPC',
		),
		'prune'=>array(
			'active'=>'MEOW_CLEAN_DATABASE',
			'limit'=>'MEOW_DATA_EXPIRATION',
		),
		'login'=>array(
			'alert_on_new'=>'MEOW_ALERTS',
			'fail_limit'=>'MEOW_FAIL_LIMIT',
			'fail_window'=>'MEOW_FAIL_WINDOW',
			'reset_on_success'=>'MEOW_FAIL_RESET_ON_SUCCESS',
		),
		'template'=>array(
			'adjacent_posts'=>'MEOW_REMOVE_ADJACENT_POSTS_TAG',
			'generator_tag'=>'MEOW_REMOVE_GENERATOR_TAG',
		),
	);

	// Misc enum settings.
	const API_ACCESS = array('all', 'users', 'none');
	const PASSWORD_ALPHA = array('optional', 'required', 'required-both');
	const PASSWORD_NUMERIC = array('optional', 'required');
	const REFERRER_POLICY = array('all', 'limited', 'none');

	protected static $options;
	protected static $readonly;


	/**
	 * Load Options
	 *
	 * @param bool $refresh Refresh.
	 * @return bool True/false.
	 */
	public static function load($refresh=false) {
		if ($refresh || ! \is_array(static::$options)) {
			// Nothing saved yet? Or maybe an older version?
			if (false === (static::$options = \get_option(static::OPTION_NAME, false))) {
				static::$options = static::OPTIONS;
				foreach (static::OLD_OPTIONS as $k=>$v) {
					if ('notfound' !== ($option = \get_option($k, 'notfound'))) {
						list($a, $b) = \explode('-', $v);
						static::$options[$a][$b] = $option;
						\delete_option($k);
					}
				}
				static::$options = common\data::parse_args(static::$options, static::OPTIONS);
				\update_option(static::OPTION_NAME, static::$options);
			}

			// Before.
			$before = \json_encode(static::$options);

			// Sanitize them.
			static::sanitize(static::$options);

			// After.
			$after = \json_encode(static::$options);
			if ($before !== $after) {
				\update_option(static::OPTION_NAME, static::$options);
			}
		}

		return true;
	}

	/**
	 * Sanitize Options
	 *
	 * @param array $options Options.
	 * @return bool True/false.
	 */
	protected static function sanitize(&$options) {
		// We also moved the whitelist.
		if (isset($options['login']['exempt'])) {
			$options['login']['whitelist'] = $options['login']['exempt'];
		}

		// Referrer Policy used to be a bool, now it is more granular.
		if (
			isset($options['template']['referrer_policy']) &&
			(true === $options['template']['referrer_policy'])
		) {
			$options['template']['referrer_policy'] = 'none';
		}

		// Make sure it fits the appropriate format.
		$options = common\data::parse_args($options, static::OPTIONS);

		// Apply our read-only constants.
		static::apply_readonly($options);

		// Logins.
		common\ref\sanitize::to_range($options['login']['fail_limit'], 3, 50);
		common\ref\sanitize::to_range($options['login']['subnet_fail_limit'], 10, 100);
		common\ref\sanitize::to_range($options['login']['subnet_fail_limit'], $options['login']['fail_limit']);
		common\ref\sanitize::to_range($options['login']['fail_window'], 600, 86400);

		// The server key should exist...
		if (! $options['login']['key']) {
			$options['login']['key'] = static::OPTIONS['login']['key'];
		}
		$keys = login::get_server_keys();
		if (
			! isset($keys[$options['login']['key']]) &&
			(! \defined('WP_CLI') || ! \WP_CLI)
		) {
			if (! \count($keys) || \array_key_exists(static::OPTIONS['login']['key'], $keys)) {
				$options['login']['key'] = static::OPTIONS['login']['key'];
			}
			// Just pick the first available, I guess.
			else {
				$keys = \array_keys($keys);
				$options['login']['key'] = $keys[0];
			}
		}

		static::sanitize_whitelist($options['login']['whitelist']);
		static::sanitize_whitelist($options['login']['blacklist']);

		// Pruning.
		common\ref\sanitize::to_range($options['prune']['limit'], 30, 365);

		// Passwords.
		$options['password']['alpha'] = \strtolower($options['password']['alpha']);
		if (! \in_array($options['password']['alpha'], static::PASSWORD_ALPHA, true)) {
			$options['password']['alpha'] = static::OPTIONS['password']['alpha'];
		}
		$options['password']['numeric'] = \strtolower($options['password']['numeric']);
		if (! \in_array($options['password']['numeric'], static::PASSWORD_NUMERIC, true)) {
			$options['password']['numeric'] = static::OPTIONS['password']['numeric'];
		}
		$options['password']['symbol'] = \strtolower($options['password']['symbol']);
		if (! \in_array($options['password']['symbol'], static::PASSWORD_NUMERIC, true)) {
			$options['password']['symbol'] = static::OPTIONS['password']['symbol'];
		}
		common\ref\sanitize::to_range($options['password']['length'], static::MIN_PASSWORD_LENGTH, 500);
		common\ref\sanitize::to_range($options['password']['exempt_length'], \max(static::MIN_PASSWORD_EXEMPT_LENGTH, $options['password']['length'] + 1), 500);

		if ($options['password']['bcrypt']) {
			if (0 !== $options['password']['bcrypt_cost']) {
				common\ref\sanitize::to_range($options['password']['bcrypt_cost'], 4, 31);
			}
			else {
				$options['password']['bcrypt_cost'] = static::bcrypt_cost();
			}
		}
		else {
			$options['password']['bcrypt_cost'] = 0;
		}

		// Referrer Policy.
		$options['template']['referrer_policy'] = \strtolower($options['template']['referrer_policy']);
		if (! \in_array($options['template']['referrer_policy'], static::REFERRER_POLICY, true)) {
			$options['template']['referrer_policy'] = 'all';
		}

		return true;
	}

	/**
	 * Sanitize Whitelist
	 *
	 * There is enough going on here that it pays to offload this set
	 * of routines to its own function.
	 *
	 * @param array $whitelist IPs, etc.
	 * @return void Nothing.
	 */
	public static function sanitize_whitelist(&$whitelist) {
		common\ref\cast::to_array($whitelist);

		// Gotta check it line by line.
		foreach ($whitelist as $k=>$v) {
			common\ref\cast::to_string($whitelist[$k], true);
			$whitelist[$k] = \preg_replace('/[^\da-f\.\:\/\-]/i', '', $v);

			// A regular IP.
			if (\filter_var($whitelist[$k], \FILTER_VALIDATE_IP)) {
				common\ref\sanitize::ip($whitelist[$k], false);
				if (! $whitelist[$k]) {
					unset($whitelist[$k]);
				}
				continue;
			}

			// An arbitrary range?
			if (1 === \substr_count($whitelist[$k], '-')) {
				$tmp = \explode('-', $whitelist[$k]);
				common\ref\sanitize::ip($tmp[0], false);
				common\ref\sanitize::ip($tmp[1], false);

				// One of the IPs is bad.
				if (! $tmp[0] || ! $tmp[1]) {
					unset($whitelist[$k]);
					continue;
				}

				// The same? No range.
				if ($tmp[0] === $tmp[1]) {
					$whitelist[$k] = $tmp[0];
				}
				else {
					// Fix order?
					if (common\format::ip_to_number($tmp[0]) > common\format::ip_to_number($tmp[1])) {
						common\data::switcheroo($tmp[0], $tmp[1]);
					}
					$whitelist[$k] = \implode('-', $tmp);
				}

				continue;
			}

			// A CIDR?
			if (false !== ($range = common\format::cidr_to_range($whitelist[$k]))) {
				list($ip, $bits) = \explode('/', $whitelist[$k]);
				$ip = common\sanitize::ip($range['min'], false);

				// Continue if the IP is valid, at least.
				if ($ip) {
					common\ref\cast::to_int($bits, true);
					$max = \filter_var($ip, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) ? 32 : 128;
					common\ref\sanitize::to_range($bits, 0, $max);
					$whitelist[$k] = "{$ip}/{$bits}";
					continue;
				}

				unset($whitelist[$k]);
				continue;
			}

			unset($whitelist[$k]);
		}

		if (\count($whitelist)) {
			$whitelist = \array_unique($whitelist);
			\sort($whitelist);
		}
	}

	/**
	 * Get Option
	 *
	 * @param string $key Key.
	 * @return mixed Value or false.
	 */
	public static function get($key=null) {
		static::load();

		// Return everything?
		if (\is_null($key)) {
			return static::$options;
		}

		common\ref\cast::to_string($key, true);

		// A single option.
		if (\array_key_exists($key, static::$options)) {
			return static::$options[$key];
		}

		// It could also be a split.
		if (1 === \substr_count($key, '-')) {
			list($a,$b) = \explode('-', $key);
			if (
				\array_key_exists($a, static::$options) &&
				\is_array(static::$options[$a]) &&
				\array_key_exists($b, static::$options[$a])
			) {
				return static::$options[$a][$b];
			}
		}

		// Must not exist.
		return false;
	}

	/**
	 * Save Option
	 *
	 * @param string $key Key.
	 * @param mixed $value Value.
	 * @param bool $force Force resaving.
	 * @return bool True/false.
	 */
	public static function save($key, $value, $force=false) {
		static::load();
		common\ref\cast::to_string($key, true);

		// Everything else...
		if (! \array_key_exists($key, static::$options)) {
			return false;
		}

		// No change?
		if (! $force && static::$options[$key] === $value) {
			return true;
		}

		$original = static::$options[$key];

		static::$options[$key] = $value;
		\update_option(static::OPTION_NAME, static::$options);
		static::load(true);

		return true;
	}

	/**
	 * Get Read-Only Options
	 *
	 * @return array Options.
	 */
	public static function get_readonly() {
		static::load();
		return static::$readonly;
	}

	/**
	 * Apply Read-Only Values
	 *
	 * Take any pre-defined constants and throw them into the settings.
	 *
	 * @param array $options Options.
	 * @return bool True/false.
	 */
	protected static function apply_readonly(&$options) {
		static::$readonly = array();

		// Run through everything.
		foreach ($options as $k=>$v) {
			foreach ($v as $k2=>$v2) {
				$key = "$k-$k2";
				$value = static::get_hard_value($k, $k2);
				if (! \is_null($value)) {
					$options[$k][$k2] = $value;
					static::$readonly[] = $key;
				}
			}
		}

		\sort(static::$readonly);

		return true;
	}

	/**
	 * Retrieve Read-Only Value
	 *
	 * Constants can be used to hard-set any Apocalypse Meow settings.
	 * That's optional and annoying to check, so, here we are.
	 *
	 * @param string $class Classification.
	 * @param string $option Sub-option.
	 * @return mixed Value or null.
	 */
	protected static function get_hard_value($class, $option) {
		common\ref\cast::to_string($class, true);
		common\ref\cast::to_string($option, true);

		// Bad arguments.
		if (! $class || ! $option) {
			return null;
		}

		$constant = \strtoupper("MEOW_{$class}_{$option}");

		// Can't set white or blacklists this way.
		if (
			('MEOW_LOGIN_WHITELIST' === $constant) ||
			('MEOW_LOGIN_BLACKLIST' === $constant)
		) {
			return null;
		}

		// Regular constant.
		if (\defined($constant)) {
			return \constant($constant);
		}

		// Deprecated constant?
		if (
			\array_key_exists($class, static::OLD_CONSTANTS) &&
			\array_key_exists($option, static::OLD_CONSTANTS[$class]) &&
			\defined(static::OLD_CONSTANTS[$class][$option])
		) {
			return \constant(static::OLD_CONSTANTS[$class][$option]);
		}

		return null;
	}

	/**
	 * Benchmark Bcrypt Costs
	 *
	 * Password hashing should take as long as possible without taking
	 * too long. This will try to find the right cost/time ratio for the
	 * server.
	 *
	 * @return int Cost.
	 */
	protected static function bcrypt_cost() {
		// Isn't this everyone's password?
		$password = 'Björk was born on 21 November 1965 in Reykjavík.';

		// Benchmark hashes. We'll stop when we reach our target.
		for ($x = 5; $x <= 31; ++$x) {
			$start = \microtime(true);
			\password_hash($password, \PASSWORD_BCRYPT, array('cost'=>$x));
			$duration = \microtime(true) - $start;

			// Don't want to go over a second.
			if ($duration > 1) {
				return ($x - 1);
			}
			elseif ($duration > .5) {
				return $x;
			}
		}

		// I'd love to see this server!
		return 31;
	}
}
