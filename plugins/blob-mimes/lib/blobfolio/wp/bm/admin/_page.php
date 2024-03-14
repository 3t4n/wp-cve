<?php
/**
 * Lord of the Files: Admin Page (Abstract)
 *
 * This base class helps take care of a lot of the repetitive bits of
 * dealing with custom back-end pages.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\admin;

use WP_Error;



abstract class page {
	/**
	 * Required Capability
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Parent Menu
	 */
	const MENU = 'tools.php';

	/**
	 * Page Slug
	 */
	const SLUG = '';

	/**
	 * Success Message
	 */
	const SUCCESS = '';

	/**
	 * Page Title
	 */
	const TITLE = '';

	/**
	 * Bound
	 *
	 * Keep track of the classes that have been initialized so we don't
	 * do it twice.
	 *
	 * @var array
	 */
	private static $_bound = array();

	/**
	 * (Nearly) Raw POST Data
	 *
	 * @var array
	 */
	private static $_raw = array();

	/**
	 * POST Errors
	 *
	 * @var array
	 */
	private static $_errors = array();

	/**
	 * Page Hook
	 *
	 * @var array
	 */
	private static $_hook = array();



	// -----------------------------------------------------------------
	// Admin
	// -----------------------------------------------------------------

	/**
	 * Bind Hooks
	 *
	 * @return void Nothing.
	 */
	public static function bind() : void {
		// Don't do it twice.
		if (isset(self::$_bound[static::class])) {
			return;
		}
		self::$_bound[static::class] = true;

		// Are we suppressing menus?
		$yes_menus = ! \defined('LOTF_HIDE_MENUS') || ! \LOTF_HIDE_MENUS;

		// The menu.
		if ($yes_menus) {
			\add_action('admin_menu', array(static::class, 'admin_menu'), 10, 0);
		}

		// Admin scripts.
		\add_action(
			'admin_enqueue_scripts',
			array(static::class, 'admin_enqueue_scripts'),
			10,
			1
		);

		// Plugin links.
		if (! \LOTF_MUST_USE && $yes_menus) {
			\add_filter(
				'plugin_action_links_' . \plugin_basename(\LOTF_INDEX),
				array(static::class, 'plugin_action_links'),
				10,
				1
			);
		}
	}

	/**
	 * Admin Menu
	 *
	 * @return void Nothing.
	 */
	public static function admin_menu() : void {
		$title = \esc_attr__(static::TITLE, 'blob-mimes');

		self::$_hook[static::class] = \add_submenu_page(
			static::MENU,
			$title,
			$title,
			static::CAPABILITY,
			static::slug(),
			array(static::class, 'admin_page')
		);
	}

	/**
	 * Admin Page
	 *
	 * @return void Nothing.
	 */
	public static function admin_page() : void {
		// We can't do it!
		if (! static::current_user_can()) {
			\wp_die(
				\sprintf(
					'<p>%s</p>',
					\esc_html__('You are not authorized to view this page.', 'blob-mimes')
				),
				'',
				array('response'=>403)
			);
		}

		// Run early stuff in case it's needed.
		static::_admin_page_early();

		// Are we posting?
		if (\getenv('REQUEST_METHOD') === 'POST') {
			// Cache the post data so we have it.
			self::$_raw[static::class] = \stripslashes_deep(($_POST ?? array()));

			// Check the nonce.
			if (! static::_verify_nonce()) {
				static::_add_error(\__(
					'The form had expired. Please reload the page and try again.',
					'blob-mimes'
				));
			}
			// Run the class-specific POST-handling routines, if any.
			else {
				static::_admin_page_post();
			}
		}

		// Load up the body!
		static::_admin_page_body();
	}

	/**
	 * Admin Page: Scripts
	 *
	 * Enqueue scripts and styles, if any.
	 *
	 * @param string $hook Hook.
	 * @return void Nothing.
	 */
	public static function admin_enqueue_scripts(string $hook) : void {
		if (static::hook() === $hook) {
			static::_admin_page_scripts();
		}
	}

	/**
	 * Admin Page: Early Cycle
	 *
	 * This method is executed immediately after the `current_user_can`
	 * test, before any POST or GET handling is done.
	 *
	 * If there is a need to short-circuit execution or bootstrap extra
	 * initialization tasks, this is the place to do it.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_early() : void {
	}

	/**
	 * Admin Page: Body
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_body() : void {
		static::_admin_page_header();
		static::_print_errors();

		// If we posted happily and have a generic success message,
		// print it.
		if (static::SUCCESS && static::is_posted() && static::is_happy()) {
			static::_print_notice(
				\esc_html__(static::SUCCESS, 'blob-mimes'),
				'success'
			);
		}

		?>
		<div id="wrap-<?php echo static::slug(); ?>" class="wrap">
			<h1><?php echo static::title(); ?></h1>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php static::_admin_page_main(); ?>
						</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<?php static::_admin_page_sidebar(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		static::_admin_page_footer();
	}

	/**
	 * Admin Page: Scripts
	 *
	 * This version can be overloaded by the child safe in the knowledge
	 * that all conditions have been met.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_scripts() : void {
	}

	/**
	 * Admin Page: Header
	 *
	 * Run class-specific header tasks, if any.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_header() : void {
	}

	/**
	 * Admin Page: Main Content
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_main() : void {
		$template = \sprintf(
			'%s/templates/%s.php',
			\LOTF_BASE_PATH,
			static::SLUG
		);

		require $template;
	}

	/**
	 * Admin Page: Sidebar Content
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_sidebar() : void {
		$template = \sprintf(
			'%s/templates/sidebar-%s.php',
			\LOTF_BASE_PATH,
			static::SLUG
		);

		require $template;
	}

	/**
	 * Admin Page: Footer
	 *
	 * Run class-specific footer tasks, if any.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_footer() : void {
	}

	/**
	 * Admin Page: POST
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_post() : void {
	}

	/**
	 * Plugin Action Link
	 *
	 * @param array $links Links.
	 * @return array Links.
	 */
	public static function plugin_action_links(array $links) : array {
		if (static::current_user_can()) {
			$links[] = \sprintf(
				'<a href="%s">%s</a>',
				static::url(),
				\esc_attr__(static::TITLE, 'blob-mimes')
			);
		}

		return $links;
	}



	// -----------------------------------------------------------------
	// Getters
	// -----------------------------------------------------------------

	/**
	 * Errors as Key/Value List
	 *
	 * @return ?array Error(s).
	 */
	public static function error_list() : ?array {
		if (! isset(self::$_errors[static::class])) {
			return null;
		}

		// Build it!
		$out = array();
		foreach (self::$_errors[static::class]->errors as $k=>$v) {
			$out[$k] = \implode("\n", $v);
		}

		return empty($out) ? null : $out;
	}

	/**
	 * Errors
	 *
	 * @return ?WP_Error Error(s).
	 */
	public static function errors() : ?WP_Error {
		return self::$_errors[static::class] ?? null;
	}

	/**
	 * Hook
	 *
	 * @return ?string Hook.
	 */
	public static function hook() : ?string {
		return self::$_hook[static::class] ?? null;
	}

	/**
	 * Nonce
	 *
	 * @return string Nonce.
	 */
	public static function nonce() : string {
		return \wp_create_nonce(static::slug());
	}

	/**
	 * Raw POST Data
	 *
	 * @param ?string $key Key.
	 * @return mixed Data or null.
	 */
	public static function raw(?string $key = null) {
		if (! $key) {
			return self::$_raw[static::class] ?? null;
		}

		return self::$_raw[static::class][$key] ?? null;
	}

	/**
	 * Slug
	 *
	 * @param bool $full Return the full slug rather than the partial.
	 * @return string Slug.
	 */
	public static function slug(bool $full = true) : string {
		return ($full ? 'blob-mimes-' : '') . static::SLUG;
	}

	/**
	 * Title
	 *
	 * @return string Title.
	 */
	public static function title() : string {
		return \__(static::TITLE, 'blob-mimes');
	}

	/**
	 * URL
	 *
	 * @return string URL.
	 */
	public static function url() : string {
		return \admin_url(\sprintf(
			'%s?page=%s',
			static::MENU,
			static::slug()
		));
	}



	// -----------------------------------------------------------------
	// Evaluation
	// -----------------------------------------------------------------

	/**
	 * Current User Can
	 *
	 * @return bool True/false.
	 */
	public static function current_user_can() : bool {
		return ! static::CAPABILITY || \current_user_can(static::CAPABILITY);
	}

	/**
	 * Is Current
	 *
	 * @return bool True/false.
	 */
	public static function is_current() : bool {
		return \is_admin() && isset($_GET['page']) && static::slug() === $_GET['page'];
	}

	/**
	 * Is Happy?
	 *
	 * @return bool True/false.
	 */
	public static function is_happy() : bool {
		return ! isset(self::$_errors[static::class]);
	}

	/**
	 * Is Posted?
	 *
	 * @return bool True/false.
	 */
	public static function is_posted() : bool {
		return ! empty(self::$_raw[static::class]);
	}



	// -----------------------------------------------------------------
	// Template Helpers
	// -----------------------------------------------------------------

	/**
	 * Print Error(s)
	 *
	 * @return void Nothing.
	 */
	protected static function _print_errors() : void {
		if (null !== $errors = static::error_list()) {
			foreach ($errors as $error) {
				static::_print_notice($error, 'error');
			}
		}
	}

	/**
	 * Print Success
	 *
	 * @param string $msg Message.
	 * @param string $type Type.
	 * @return void Nothing.
	 */
	protected static function _print_notice(string $msg, string $type = 'info') : void {
		// There are only so many types.
		if (
			! $type ||
			! \in_array($type, array('error', 'info', 'success', 'warning'), true)
		) {
			$type = 'info';
		}

		\printf(
			'<div class="notice notice-%s"><p>%s</p></div>',
			$type,
			$msg
		);
	}



	// -----------------------------------------------------------------
	// Internal Helpers
	// -----------------------------------------------------------------

	/**
	 * Set Error
	 *
	 * @param string $msg Message.
	 * @param string $code Code.
	 * @return void Nothing.
	 */
	protected static function _add_error(string $msg, string $code = 'other') : void {
		if ($msg && $code) {
			if (! isset(self::$_errors[static::class])) {
				self::$_errors[static::class] = new WP_Error($code, $msg);
			}
			else {
				self::$_errors[static::class]->add($code, $msg);
			}
		}
	}

	/**
	 * Verify Nonce
	 *
	 * @return bool True/false.
	 */
	protected static function _verify_nonce() : bool {
		return (
			(null !== $nonce = static::raw('n')) &&
			\wp_verify_nonce($nonce, static::slug())
		);
	}
}
