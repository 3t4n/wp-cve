<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Plugin;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// remove etracker_rewrite_rules.
		add_filter( 'mod_rewrite_rules', 'Etracker\Plugin\Deactivator::remove_rewrite_rules' );
		// phpcs:disable WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
		flush_rewrite_rules();
		// phpcs:enable WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
		self::disable_cron();
	}

	/**
	 * Filter for mod_rewrite_rules.
	 *
	 * Remove etracker_rewrite_rules before flushing rules to disk.
	 *
	 * @param string $rules mod_rewrite Rewrite rules formatted for .htaccess.
	 *
	 * @return string $rules mod_rewrite Rewrite rules formatted for .htaccess.
	 *
	 * @since 1.4.0
	 */
	public static function remove_rewrite_rules( $rules ) {
		// explode rules string.
		$rules_list = explode( "\n", $rules );
		// filter signalize rules.
		foreach ( $rules_list as $line_nr => $rule ) {
			// looking for:
			// RewriteRule ^sw.js$ /wp-content/plugins/etracker/public/js/sw.js [QSA,L].
			if ( preg_match( '/\^sw\.js\$/', $rule ) ) {
				unset( $rules_list[ $line_nr ] );
			}
			// RewriteRule ^sw\.js$ /wp-content/plugins/etracker/public/js/sw.js [QSA,L].
			if ( preg_match( '/\^sw\\\.js\$/', $rule ) ) {
				unset( $rules_list[ $line_nr ] );
			}
		}
		// rebuild rules string and return.
		return implode( "\n", $rules_list );
	}

	/**
	 * Disable etracker cron hook.
	 *
	 * @return void
	 */
	public static function disable_cron() {
		wp_clear_scheduled_hook( 'etracker_cron_fetch_reports' );
		wp_clear_scheduled_hook( 'etracker_cron_cleanup_logging' );
	}
}
