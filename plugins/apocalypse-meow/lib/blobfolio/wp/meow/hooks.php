<?php
/**
 * Apocalypse Meow Hooks
 *
 * Custom actions and filters that can be implemented by developers into
 * themes or plugins.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class hooks {

	// -----------------------------------------------------------------
	// Init/Setup
	// -----------------------------------------------------------------

	protected static $_init = false;

	/**
	 * Hook It Up
	 *
	 * @return bool True/false.
	 */
	public static function init() {
		// Only need to do this once.
		if (static::$_init) {
			return true;
		}
		static::$_init = true;

		// Actions that Meow has to act on. Do these early.
		\add_action('meow_log_ban', array(static::class, 'log_ban'), 5, 1);
		\add_action('meow_log_fail', array(static::class, 'log_fail'), 5, 1);
		\add_action('meow_log_success', array(static::class, 'log_success'), 5, 1);

		// Filters that Meow returns data on. Do these at the normal time.
		\add_filter('meow_failures_remaining', array(static::class, 'failures_remaining'), 10, 2);
		\add_filter('meow_is_banned', array(static::class, 'is_banned'), 10, 2);
		\add_filter('meow_is_whitelisted', array(static::class, 'is_whitelisted'), 10, 2);

		return true;
	}

	// ----------------------------------------------------------------- end init



	// -----------------------------------------------------------------
	// Actions
	// -----------------------------------------------------------------

	/**
	 * Log a Login Success
	 *
	 * @param array $args Arguments.
	 *
	 * @args string $ip IP Address.
	 * @args string $username Username.
	 *
	 * @return bool True/false.
	 */
	public static function log_success($args=null) {
		$defaults = array(
			'ip'=>login::get_visitor_ip(),
			'username'=>'',
		);
		$data = common\data::parse_args($args, $defaults);
		$data['type'] = 'success';

		return login::login_log($data);
	}

	/**
	 * Log a Login Failure
	 *
	 * @param array $args Arguments.
	 *
	 * @args string $ip IP Address.
	 * @args string $username Username.
	 *
	 * @return bool True/false.
	 */
	public static function log_fail($args=null) {
		$defaults = array(
			'ip'=>login::get_visitor_ip(),
			'username'=>'',
		);
		$data = common\data::parse_args($args, $defaults);
		$data['type'] = 'fail';

		return login::login_log($data);
	}

	/**
	 * Log a Login Ban
	 *
	 * @param array $args Arguments.
	 *
	 * @args string $ip IP Address.
	 * @args string $subnet Subnet.
	 * @args string $date_expires Expiration.
	 *
	 * @return bool True/false.
	 */
	public static function log_ban($args=null) {
		$fail_window = options::get('login-fail_window');
		$defaults = array(
			'ip'=>'',
			'subnet'=>'',
			'date_expires'=>\date('Y-m-d H:i:s', \strtotime("+$fail_window seconds", \current_time('timestamp'))),
		);
		$data = common\data::parse_args($args, $defaults);
		$data['type'] = 'ban';

		return login::login_log($data);
	}

	// ----------------------------------------------------------------- end actions



	// -----------------------------------------------------------------
	// Filters
	// -----------------------------------------------------------------

	/**
	 * Is Banned?
	 *
	 * @param bool $value Value.
	 * @param string $ip IP.
	 * @return bool True/false.
	 */
	public static function is_banned($value=false, $ip=null) {
		$value = !! (login::is_banned($ip, false));
		return $value;
	}

	/**
	 * Is Whitelisted?
	 *
	 * @param bool $value Value.
	 * @param string $ip IP.
	 * @return bool True/false.
	 */
	public static function is_whitelisted($value=false, $ip=null) {
		$value = (login::is_whitelisted($ip) || login::is_server_ip($ip));
		return $value;
	}

	/**
	 * Remaining failures?
	 *
	 * @param int $value Value.
	 * @param string $ip IP.
	 * @return int Remaining failures.
	 */
	public static function failures_remaining($value=5, $ip=null) {
		// By defalut, use the visitor info.
		$modes = array(
			'ip'=>login::get_visitor_ip(),
			'subnet'=>login::get_visitor_subnet(),
		);

		if (! \is_null($ip)) {
			// An IP was passed. This is most likely.
			if (false === \strpos($ip, '/')) {
				$modes['ip'] = $ip;
				$modes['subnet'] = common\format::ip_to_subnet($ip);
			}
			// A subnet was passed.
			else {
				$modes['subnet'] = $ip;
				$modes['ip'] = \explode('/', $ip);
				$modes['ip'] = $modes['ip'][0];
			}
		}

		foreach ($modes as $k=>$v) {
			$modes[$k] = login::fails_remaining($k, $v);
		}

		// Return the smaller of the two numbers.
		$value = \min($modes);
		return $value;
	}

	// ----------------------------------------------------------------- end filters
}
