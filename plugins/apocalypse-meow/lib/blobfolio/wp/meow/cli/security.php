<?php
/**
 * CLI: Security Tools
 *
 * Various security-related tools and helpers.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow\cli;

use blobfolio\wp\meow\tools;
use blobfolio\wp\meow\vendor\common;
use WP_CLI;

// Add the main command.
if (! \class_exists('WP_CLI') || ! \class_exists('WP_CLI_Command')) {
	return;
}

// Add the main command.
if (WP_CLI::add_command(
	'meow security',
	\MEOW_BASE_CLASS . 'cli\\security',
	array(
		'before_invoke'=>function() {
			if (\is_multisite()) {
				WP_CLI::error(\__('This plugin cannot be used on Multi-Site.', 'apocalypse-meow'));
			}
		},
	)
)) {
	// @codingStandardsIgnoreStart
	$workaround =
	/**
	 * Revoke MD5 Passwords
	 *
	 * For historical reasons, WordPress has retained backward
	 * compatibility with the outdated MD5 hashing algorithm. Should a
	 * hacker obtain a copy of your users table, any user with an
	 * MD5-hashed password could be in serious trouble.
	 *
	 * This tool will override any insecure MD5 password hash with
	 * securely-hashed gibberish. This will lock affected users out of
	 * their account (until they reset their passwords), however these
	 * users have likely been absent from the site for many years.
	 *
	 * @return bool True.
	 *
	 * @subcommand revoke-md5-passwords
	 */
	function() {
		$affected = tools::rehash_md5_passwords();

		if (! $affected) {
			WP_CLI::success(
				\__('No passwords were hashed with MD5.', 'apocalypse-meow')
			);
		}
		else {
			WP_CLI::success(
				common\format::inflect(
					$affected,
					\__('%d password has', 'apocalypse-meow'),
					\__('%d passwords have', 'apocalypse-meow')
				) . ' ' . \__('been securely reset and rehashed.', 'apocalypse-meow')
			);
		}

		return true;
	};

	// There appears to be a bug in the WP_CLI @subcommand handling that
	// is causing the registration to explode on commands with a number
	// in the name.
	//
	// Registering subcommands manually via WP_CLI::add_command() seems
	// to work just fine, however.
	//
	// So rather than changing the command name to something confusing,
	// we'll just move the code to somewhere confusing.
	WP_CLI::add_command(
		'meow security revoke-md5-passwords',
		$workaround
	);
	// @codingStandardsIgnoreEnd
}

/**
 * Security Tools
 *
 * These commands allow system administrators to address common
 * security issues, such as en masse password resets, user login
 * renaming, WP session management, etc.
 */
class security extends \WP_CLI_Command {

	/**
	 * Revoke Session
	 *
	 * WordPress generates a unique Session ID each time a user logs
	 * into the site. Any given user might have one or more active
	 * sessions if they have accessed the site from multiple devices.
	 *
	 * Use this tool to revoke a single user session, or all sessions
	 * for a given user. Any devices connected to those sessions will
	 * then be immediately logged out.
	 *
	 * ## OPTIONS
	 *
	 * <user_id|user_login|user_email>
	 * : The WordPress user via ID, username, or email address.
	 *
	 * [<session_id>]
	 * : To remove a single session, specify the session ID here. To
	 * view active user sessions, use `wp meow activity sessions`.
	 * ---
	 * default: all
	 * ---
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 *
	 * @subcommand revoke-session
	 */
	public function revoke_session($args=null) {
		global $wpdb;

		// First things first, validate the user.
		$user_key = $args[0];
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
		}
		// Bad user?
		if (false === $user) {
			WP_CLI::error(
				\__('This user login is not valid.', 'apocalypse-meow')
			);
		}

		$session_id = $args[1];

		// Kill it all?
		if ('all' === $session_id) {
			\delete_user_meta($user->ID, 'session_tokens');
			WP_CLI::success(
				\sprintf(
					\__('All sessions for %s have been revoked.', 'apocalypse-meow'),
					$user->user_login
				)
			);
		}
		else {
			$meta = \get_user_meta($user->ID, 'session_tokens', true);
			if (
				\is_array($meta) &&
				\count($meta) &&
				isset($meta[$session_id])
			) {
				tools::kill_session($user->ID, $session_id);
				WP_CLI::success(
					\__('The session has been revoked.', 'apocalypse-meow')
				);
			}
			else {
				WP_CLI::error(
					\sprintf(
						\__('No session matching %s was found.', 'apocalypse-meow'),
						$session_id
					)
				);
			}
		}

		return true;
	}

	/**
	 * Rename User
	 *
	 * WordPress usernames are set in stone. Historically, to workaround
	 * this limitation it was necessary to create a new user, login as
	 * that user, delete the original user, and reassign all content to
	 * the new one.
	 *
	 * But that's stupid.
	 *
	 * This tool simply renames the login for an existing user,
	 * preserving the original user ID, etc.
	 *
	 * ## OPTIONS
	 *
	 * <old_username>
	 * : The original user login.
	 *
	 * <new_username>
	 * : The replacement user login.
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 *
	 * @subcommand rename-user
	 */
	public function rename_user($args=null) {
		$user_old = \strtolower(\sanitize_user($args[0]));
		$user_new = \strtolower(\sanitize_user($args[1]));

		$user_id = (int) \username_exists($user_old);

		if (! $user_old) {
			WP_CLI::error(
				\__('The original username is not valid.', 'apocalypse-meow')
			);
		}
		elseif (! $user_id) {
			WP_CLI::error(
				\sprintf(
					\__('The username %s does not exist.', 'apocalypse-meow'),
					$user_old
				)
			);
		}

		if (! $user_new) {
			WP_CLI::error(
				\__('The replacement username is not valid.', 'apocalypse-meow')
			);
		}
		elseif (!! \username_exists($user_new)) {
			WP_CLI::error(
				\sprintf(
					\__('The username %s already exists.', 'apocalypse-meow'),
					$user_new
				)
			);
		}

		if (tools::rename_user($user_old, $user_new)) {
			WP_CLI::success(
				\sprintf(
					\__('The username %s has been renamed to %s.', 'apocalypse-meow'),
					$user_old,
					$user_new
				)
			);
		}
		else {
			WP_CLI::warning(
				\__('An error was encountered; the user may or may not have been renamed successfully.', 'apocalypse-meow')
			);
		}

		return true;
	}

	/**
	 * Reset ALL Passwords
	 *
	 * This will immediately reset all user passwords site-wide. To
	 * regain account access, each user will need to complete the
	 * "Forgot Password" process.
	 *
	 * If your site or database has been breached, or you suspect it
	 * has, run this tool immediately.
	 *
	 * Note: this operation does not trigger any email notifications to
	 * affected users. (Email functionality is not always available in
	 * CLI mode.)
	 *
	 * If you need to communicate the change to your users, please use
	 * the web-based, wp-admin version instead.
	 *
	 * @return bool True.
	 *
	 * @subcommand reset-passwords
	 */
	public function reset_passwords() {
		// This one's easy!
		tools::reset_passwords();

		WP_CLI::success(
			\__('All user passwords have been reset.', 'apocalypse-meow')
		);

		return true;
	}
}
