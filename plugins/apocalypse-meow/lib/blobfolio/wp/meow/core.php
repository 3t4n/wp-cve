<?php
/**
 * Apocalypse Meow Core/Template Functions
 *
 * Security actions relating to the core/template settings groups are
 * located here.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use WP_Error;

class core {

	// The username saved when tracking user enumeration attempts.
	const ENUMERATION_USERNAME = 'enumeration-attempt';


	// -----------------------------------------------------------------
	// Init/Setup
	// -----------------------------------------------------------------

	protected static $_init = false;

	/**
	 * Register Actions
	 *
	 * Almost everything relevant to this category of actions can be
	 * determined once WordPress fires the 'init' hook.
	 *
	 * @return bool True/false.
	 */
	public static function init() {
		// Only need to do this once.
		if (static::$_init) {
			return true;
		}
		static::$_init = true;

		$settings = options::get();

		// Disable file editor.
		if ($settings['core']['file_edit'] && ! \defined('DISALLOW_FILE_EDIT')) {
			\define('DISALLOW_FILE_EDIT', true);
		}

		// Disable XML-RPC.
		if ($settings['core']['xmlrpc']) {
			// Disable XML-RPC methods requiring authentication.
			\add_filter('xmlrpc_enabled', '__return_false');

			// Clean up a few other stupid things.
			\remove_action('wp_head', 'rsd_link');
			\add_filter('wp_headers', array(static::class, 'core_xmlrpc_pingback'));
			\add_filter('pings_open', '__return_false', \PHP_INT_MAX);
		}

		// Disable Browse Happy.
		if (
			$settings['core']['browse_happy'] &&
			! empty($_SERVER['HTTP_USER_AGENT'])
		) {
			$key = \md5($_SERVER['HTTP_USER_AGENT']);
			\add_filter("pre_site_transient_browser_{$key}", array(static::class, 'generic_browse_happy'));
		}

		// Disable News & Events Dashboard widget.
		if ($settings['core']['dashboard_news']) {
			\add_action('wp_network_dashboard_setup', array(static::class, 'core_dashboard_news'), 20);
			\add_action('wp_user_dashboard_setup', array(static::class, 'core_dashboard_news'), 20);
			\add_action('wp_dashboard_setup', array(static::class, 'core_dashboard_news'), 20);
		}

		// Disable adjacent posts.
		if ($settings['template']['adjacent_posts']) {
			\add_filter('previous_post_rel_link', '__return_false');
			\add_filter('next_post_rel_link', '__return_false');
		}

		// Disable generator.
		if ($settings['template']['generator_tag']) {
			\add_filter('the_generator', '__return_false');
		}

		// Remove the readme.html file.
		$readme = \trailingslashit(\ABSPATH) . 'readme.html';
		if ($settings['template']['readme'] && @\file_exists($readme)) {
			@\unlink($readme);
		}

		// Enqueue the rel=noopener script. This is hooked into both the
		// front- and backend sites.
		if ($settings['template']['noopener']) {
			\add_action('wp_footer', array(static::class, 'template_noopener'));
			\add_action('admin_footer', array(static::class, 'template_noopener'));
		}

		// User Enumeration.
		if ($settings['core']['enumeration']) {
			// Regular request.
			if (isset($_GET['author']) && \get_option('permalink_structure')) {
				static::core_enumeration();
			}

			// WP-REST requests.
			\add_filter('rest_authentication_errors', array(static::class, 'core_enumeration_api'), 100);
		}

		// Referrer-Policy.
		if ('none' === $settings['template']['referrer_policy']) {
			@\header('Referrer-Policy: no-referrer');
		}
		elseif ('limited' === $settings['template']['referrer_policy']) {
			@\header('Referrer-Policy: origin-when-cross-origin');
		}

		// X-Content-Type-Options.
		if ($settings['template']['x_content_type']) {
			@\header('X-Content-Type-Options: nosniff');
		}

		// X-Frame-Options.
		if ($settings['template']['x_frame']) {
			\add_action('wp', array(static::class, 'template_x_frame'));
		}

		return true;
	}

	// ----------------------------------------------------------------- end init



	// -----------------------------------------------------------------
	// XML-RPC
	// -----------------------------------------------------------------

	/**
	 * Remove XML-RPC Pingback Header
	 *
	 * @param array $headers Headers.
	 * @return array Headers.
	 */
	public static function core_xmlrpc_pingback($headers) {
		if (! \is_array($headers)) {
			$headers = array();
		}

		if (isset($headers['X-Pingback'])) {
			unset($headers['X-Pingback']);
		}

		return $headers;
	}

	// ----------------------------------------------------------------- end xml-rpc



	// -----------------------------------------------------------------
	// Browse Happy
	// -----------------------------------------------------------------

	/**
	 * Disable Browse Happy
	 *
	 * WordPress does not provide any official bypass mechanism for this
	 * feature. Instead, we'll short-circuit the transient.
	 *
	 * @param mixed $value Value.
	 * @return array Value.
	 */
	public static function generic_browse_happy($value) {
		$value = array(
			'name'=>'Generic',
			'version'=>'1.0',
			'platform'=>'',
			'update_url'=>'',
			'img_src'=>'',
			'img_src_ssl'=>'',
			'current_version'=>'1.0',
			'upgrade'=>false,
			'insecure'=>false,
			'mobile'=>false,
		);

		return $value;
	}

	// ----------------------------------------------------------------- end browse happy



	// -----------------------------------------------------------------
	// Dashboard News & Events
	// -----------------------------------------------------------------

	/**
	 * Remove Dashboard News/Events
	 *
	 * @return void Nothing.
	 */
	public static function core_dashboard_news() {
		// This lives in the Dashboard Primary box.
		\remove_meta_box('dashboard_primary', \get_current_screen(), 'side');
	}

	// ----------------------------------------------------------------- end dashboard news



	// -----------------------------------------------------------------
	// Rel=Noopener
	// -----------------------------------------------------------------

	/**
	 * Enqueue Noopener Script
	 *
	 * This script will run after everything's loaded and add
	 * rel=noopener to links with target=_blank.
	 *
	 * @see {https://github.com/danielstjules/blankshield}
	 *
	 * @return void Nothing.
	 */
	public static function template_noopener() {
		// This script is small, so let's inline it.
		\printf(
			'<script id="meow-inline-js-noopener">%s</script>',
			\file_get_contents(\MEOW_PLUGIN_DIR . 'js/noopener.min.js')
		);
	}

	// ----------------------------------------------------------------- end noopener



	// -----------------------------------------------------------------
	// X-Frame-Options
	// -----------------------------------------------------------------

	/**
	 * Disable Embedding
	 *
	 * This sets the X-Frame-Options header and also gets rid of WP's
	 * stupid embed helper script.
	 *
	 * @return void Nothing.
	 */
	public static function template_x_frame() {
		@\header('X-Frame-Options: SAMEORIGIN');
		\wp_deregister_script('wp-embed');
	}

	// ----------------------------------------------------------------- end X-Frame



	// -----------------------------------------------------------------
	// User Enumeration
	// -----------------------------------------------------------------

	/**
	 * Prevent User Enumeration
	 *
	 * This stops user enumeration attempts for regular HTTP requests.
	 *
	 * @return bool|void True/nothing.
	 */
	public static function core_enumeration() {
		// Enumeration not applicable in these cases.
		if (
			(\defined('DOING_CRON') && \DOING_CRON) ||
			(\defined('DOING_AJAX') && \DOING_AJAX) ||
			(\defined('WP_CLI') && \WP_CLI) ||
			\is_admin()
		) {
			return true;
		}

		// Track this as a failure?
		if (options::get('core-enumeration_fail')) {
			login::login_log_fail(static::ENUMERATION_USERNAME);
		}

		// Trigger an error page.
		if (options::get('core-enumeration_die')) {
			\wp_die(
				\__('Author archives are not accessible by user ID.', 'apocalypse-meow'),
				\__('Invalid Request', 'apocalypse-meow'),
				400
			);
		}

		// Otherwise send them to the home page.
		\wp_redirect(\site_url());
		exit;
	}

	/**
	 * Prevent User Enumeration: WP-REST
	 *
	 * Same as above, but for WP-REST requests.
	 *
	 * @param mixed $access Access.
	 * @return mixed Error or access.
	 */
	public static function core_enumeration_api($access) {
		global $wp;

		$route = $wp->query_vars['rest_route'] ?? '';
		if (
			! \is_user_logged_in() &&
			$route &&
			0 === \strpos(\strtolower(\trailingslashit($route)), '/wp/v2/users/')
		) {
			// Track this as a failure?
			if (options::get('core-enumeration_fail')) {
				login::login_log_fail(static::ENUMERATION_USERNAME);
			}

			$access = new WP_Error(
				'rest_access_forbidden_enumeration',
				\__('WP-REST user access is disabled.', 'apocalypse-meow'),
				array('status'=>403)
			);
		}

		return $access;
	}

	// ----------------------------------------------------------------- end enumeration
}
