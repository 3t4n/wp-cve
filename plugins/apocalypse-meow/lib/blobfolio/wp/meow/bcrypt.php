<?php
/**
 * Apocalypse Meow - Bcrypt
 *
 * This overrides a handful of pluggable WordPress password functions to
 * improve the strength of its hashes.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\options;

/**
 * Hash Password
 *
 * @param string $password Password.
 * @return string Hash.
 */
function wp_hash_password($password) {
	return \password_hash(
		$password,
		\PASSWORD_BCRYPT,
		array('cost'=>options::get('password-bcrypt_cost'))
	);
}

/**
 * Verify Password
 *
 * @param string $password Password.
 * @param string $hash Hash.
 * @param string|int $user_id User ID.
 * @return bool True/false.
 */
function wp_check_password($password, $hash, $user_id='') {
	// This might be an old-fashioned hash. We'll mimic WP's default
	// behaviors, except fuck MD5. Seriously.
	if ('$P$' === \substr($hash, 0, 3)) {
		global $wp_hasher;
		if (empty($wp_hasher)) {
			require_once \ABSPATH . \WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash(8, true);
		}

		$check = $wp_hasher->CheckPassword($password, $hash);

		// While we're here, let's fix the password if we can. No need
		// to override this function; the default calls
		// wp_hash_password().
		if ($check && \is_numeric($user_id) && $user_id > 0) {
			\wp_set_password($password, $user_id);
		}
	}
	// Should be ours.
	elseif (60 === \strlen($hash)) {
		$check = \password_verify($password, $hash);

		// Maybe we should update this too?
		if ($check && \is_numeric($user_id) && $user_id > 0) {
			\preg_match('/^\$2y\$(\d+)\$/', $hash, $match);
			if (
				\is_array($match) &&
				\count($match) &&
				(\intval($match[1]) !== options::get('password-bcrypt_cost'))
			) {
				\wp_set_password($password, $user_id);
			}
		}
	}

	// We can still use the filter.
	return \apply_filters('check_password', $check, $password, $hash, $user_id);
}
