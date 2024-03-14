<?php
/**
 * Apocalypse Meow Tools.
 *
 * These are miscellaneous helpers accessed via AJAX and CLI methods.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class tools {

	/**
	 * Remove User Session
	 *
	 * Get rid of a user session token.
	 *
	 * @param int $user_id User ID.
	 * @param string $session_id Session ID.
	 * @return bool True/false.
	 */
	public static function kill_session($user_id, $session_id) {
		common\ref\cast::to_int($user_id, true);
		if ($user_id <= 0) {
			return false;
		}

		common\ref\cast::to_string($session_id, true);
		if (! $session_id) {
			return false;
		}

		$meta = \get_user_meta($user_id, 'session_tokens', true);
		if (\is_array($meta) && \count($meta)) {
			if (isset($meta[$session_id])) {
				unset($meta[$session_id]);
				if (\count($meta)) {
					\update_user_meta($user_id, 'session_tokens', $meta);
				}
				else {
					\delete_user_meta($user_id, 'session_tokens');
				}
			}
		}
		else {
			\delete_user_meta($user_id, 'session_tokens');
		}

		return true;
	}

	/**
	 * Tools: MD5 Passwords
	 *
	 * Find and destroy any passwords still hashed with MD5.
	 *
	 * @return int Number affected.
	 */
	public static function rehash_md5_passwords() {
		global $wpdb;
		$affected = 0;

		$users = $wpdb->get_var("
			SELECT GROUP_CONCAT(`ID`)
			FROM `{$wpdb->users}`
			WHERE `user_pass` REGEXP '^[A-Fa-f0-9]{32}$'
		");
		if (\is_null($users)) {
			return 0;
		}

		$users = \explode(',', $users);
		$affected = \count($users);

		// We can hijack the general resetter.
		static::reset_passwords($users);

		return $affected;
	}

	/**
	 * Tools: Rename Username
	 *
	 * This takes an admirable stab at replacing one username with
	 * another.
	 *
	 * Renaming the current user login will probably log them out.
	 *
	 * @param string $user_old Old username.
	 * @param string $user_new New username.
	 * @return bool True/false.
	 */
	public static function rename_user($user_old, $user_new) {
		global $wpdb;

		common\ref\cast::to_string($user_old, true);
		$user_old = \sanitize_user($user_old);

		common\ref\cast::to_string($user_new, true);
		$user_new = \sanitize_user($user_new);

		$user_id = (int) \username_exists($user_old);

		// This is conditional on a lot of things...
		if (
			$user_old &&
			$user_new &&
			$user_id &&
			\validate_username($user_new) &&
			! \username_exists($user_new)
		) {
			// The main entry.
			$wpdb->update(
				$wpdb->users,
				array('user_login'=>$user_new),
				array('ID'=>$user_id),
				'%s',
				'%d'
			);

			// Display name and nicename might also need to change.
			foreach (array('display_name', 'user_nicename') as $field) {
				$wpdb->update(
					$wpdb->users,
					array($field=>$user_new),
					array(
						'ID'=>$user_id,
						$field=>$user_old,
					),
					'%s',
					array('%d', '%s')
				);
			}

			// Nickname is hiding in usermeta.
			$nickname = \get_user_meta($user_id, 'nickname', true);
			if ($nickname === $user_old) {
				\update_user_meta($user_id, 'nickname', $user_new);
			}

			return true;
		}

		return false;
	}

	/**
	 * Tools: Reset Passwords
	 *
	 * Reset passwords for the chosen users to something random and
	 * secure.
	 *
	 * Users will only be explicitly notified if the optional $message
	 * is passed.
	 *
	 * Resetting the current user's password this way will probably log
	 * them out. FYI. Haha.
	 *
	 * @param array $user_id User IDs. Null for everyone.
	 * @param string $message Email message.
	 * @return bool True/false.
	 */
	public static function reset_passwords($user_id=null, $message=null) {
		global $wpdb;

		$conds = 1;
		if (! \is_null($user_id)) {
			common\ref\cast::to_array($user_id);
			foreach ($user_id as $k=>$v) {
				common\ref\cast::to_int($user_id[$k], true);
				if ($user_id[$k] <= 0) {
					unset($user_id[$k]);
				}
			}
			$user_id = \array_unique($user_id);
			\sort($user_id);

			// If users were passed but they were all wrong, fail.
			if (! \count($user_id)) {
				return false;
			}

			$conds = '`ID` IN (' . \implode(',', $user_id) . ')';
		}
		unset($user_id);

		// Check out the message early so we know whether or not to do
		// some extra work.
		common\ref\cast::to_string($message, true);
		common\ref\sanitize::whitespace($message, 2);
		if (! $message) {
			$message = false;
		}

		// It is (a lot) more efficient if we bypass the default WP
		// functions for this. Mostly this means A) not running lots of
		// unnecessary fallback/conditional checks and B) letting MySQL
		// do what it does best. This does, however, bypass some filter
		// and action calls that would potentially prevent a password
		// from being reset.
		$passwords = array();
		$keys = array();
		$users = array();

		// Step one, pull users and generate replacement data.
		$dbResult = $wpdb->get_results("
			SELECT
				`ID`,
				`user_email`,
				`user_login`
			FROM `{$wpdb->users}`
			WHERE $conds
			ORDER BY `ID` ASC
		", \ARRAY_A);
		if (! \is_array($dbResult) || ! \count($dbResult)) {
			return false;
		}

		foreach ($dbResult as $Row) {
			$user_id = (int) $Row['ID'];

			// More random than wp_rand().
			$pass = common\data::random_string(60);
			$passwords[$user_id] = \esc_sql(\wp_hash_password($pass));

			// Additional tasks.
			if ($message) {
				$key = common\data::random_string(20);
				$hashed = \time() . ':' . \wp_hash_password($key);
				$keys[$user_id] = \esc_sql($hashed);

				// We'll need these pieces to send email notifications.
				$users[$user_id] = array(
					'email'=>$Row['user_email'],
					'login'=>$Row['user_login'],
					'key'=>$key,
				);
			}
		}

		// Update passwords en masse, but in chunks.
		$passwords = \array_chunk($passwords, 100, true);
		foreach ($passwords as $u) {
			$query = "UPDATE `{$wpdb->prefix}users` SET `user_pass` = CASE `ID`";
			foreach ($u as $k=>$v) {
				$query .= "\nWHEN $k THEN '$v'";
			}
			$query .= "\nEND WHERE `ID` IN (" . \implode(',', \array_keys($u)) . ')';

			$wpdb->query($query);

			// Kill their sessions too.
			$wpdb->query("
				DELETE FROM `{$wpdb->usermeta}`
				WHERE
					`meta_key`='session_tokens' AND
					`user_id` IN (" . \implode(',', \array_keys($u)) . ')
			');
		}
		unset($passwords);

		// Done?
		if (! $message) {
			return true;
		}

		// Update activation keys en masse, but in chunks.
		$keys = \array_chunk($keys, 100, true);
		foreach ($keys as $u) {
			$query = "UPDATE `{$wpdb->prefix}users` SET `user_activation_key` = CASE `ID`";
			foreach ($u as $k=>$v) {
				$query .= "\nWHEN $k THEN '$v'";
			}
			$query .= "\nEND WHERE `ID` IN (" . \implode(',', \array_keys($u)) . ')';

			$wpdb->query($query);
		}
		unset($keys);

		// Last thing, send emails out!
		$url = \network_site_url('wp-login.php?action=rp', 'login');
		$subject = '[' . common\format::decode_entities(\get_bloginfo('name')) . '] ' . \__('Password Reset', 'apocalypse-meow');
		foreach ($users as $u) {
			$body = "$message\n\n$url&key={$u['key']}&login=" . \rawurlencode($u['login']);
			\wp_mail($u['email'], $subject, $body);
		}

		return true;
	}

}
