<?php

/**
 * Drupal wrapper class.
 *
 * This class is used as a wrapper to shim Drupal functions to be compatible
 * with WordPress.
 *
 * The original source for this code from Drupal 7, created by a community
 * core contributors. All original code is Copyright by the original authors
 * and licensed under GNU General Public License v2 or later.
 *
 */
class Intel_Df  {

	private static $instance;

	const LANGUAGE_NONE = 'UND';

	const MENU_IS_ROOT = 0x0001;

	/**
	 * Internal menu flag -- menu item is visible in the menu tree.
	 */
	const MENU_VISIBLE_IN_TREE = 0x0002;

	/**
	 * Internal menu flag -- menu item is visible in the breadcrumb.
	 */
	const MENU_VISIBLE_IN_BREADCRUMB = 0x0004;

	/**
	 * Internal menu flag -- menu item links back to its parent.
	 */
	const MENU_LINKS_TO_PARENT = 0x0008;

	/**
	 * Internal menu flag -- menu item can be modified by administrator.
	 */
	const MENU_MODIFIED_BY_ADMIN = 0x0020;

	/**
	 * Internal menu flag -- menu item was created by administrator.
	 */
	const MENU_CREATED_BY_ADMIN = 0x0040;

	/**
	 * Internal menu flag -- menu item is a local task.
	 */
	const MENU_IS_LOCAL_TASK = 0x0080;

	/**
	 * Internal menu flag -- menu item is a local action.
	 */
	const MENU_IS_LOCAL_ACTION = 0x0100;

	/**
	 * Menu type -- A "normal" menu item that's shown in menu and breadcrumbs.
	 *
	 * Normal menu items show up in the menu tree and can be moved/hidden by
	 * the administrator. Use this for most menu items. It is the default value if
	 * no menu item type is specified.
	 */
	//const MENU_NORMAL_ITEM = self::MENU_VISIBLE_IN_TREE | self::MENU_VISIBLE_IN_BREADCRUMB;
	const MENU_NORMAL_ITEM = 0x0006;

	/**
	 * Menu type -- A hidden, internal callback, typically used for API calls.
	 *
	 * Callbacks simply register a path so that the correct function is fired
	 * when the URL is accessed. They do not appear in menus or breadcrumbs.
	 */
	const MENU_CALLBACK = 0x0000;

	/**
	 * Menu type -- A normal menu item, hidden until enabled by an administrator.
	 *
	 * Modules may "suggest" menu items that the administrator may enable. They act
	 * just as callbacks do until enabled, at which time they act like normal items.
	 * Note for the value: 0x0010 was a flag which is no longer used, but this way
	 * the values of MENU_CALLBACK and MENU_SUGGESTED_ITEM are separate.
	 */
	//const MENU_SUGGESTED_ITEM = self::MENU_VISIBLE_IN_BREADCRUMB | 0x0010;
	const MENU_SUGGESTED_ITEM = 0x0014;

	/**
	 * Menu type -- A task specific to the parent item, usually rendered as a tab.
	 *
	 * Local tasks are menu items that describe actions to be performed on their
	 * parent item. An example is the path "node/52/edit", which performs the
	 * "edit" task on "node/52".
	 */
	//const MENU_LOCAL_TASK = self::MENU_IS_LOCAL_TASK | self::MENU_VISIBLE_IN_BREADCRUMB;
	const MENU_LOCAL_TASK = 0x0084;

	/**
	 * Menu type -- The "default" local task, which is initially active.
	 *
	 * Every set of local tasks should provide one "default" task, that links to the
	 * same path as its parent when clicked.
	 */
	//const MENU_DEFAULT_LOCAL_TASK = self::MENU_IS_LOCAL_TASK | self::MENU_LINKS_TO_PARENT | self::MENU_VISIBLE_IN_BREADCRUMB;
	const MENU_DEFAULT_LOCAL_TASK = 0x008C;

	/**
	 * Menu type -- An action specific to the parent, usually rendered as a link.
	 *
	 * Local actions are menu items that describe actions on the parent item such
	 * as adding a new user, taxonomy term, etc.
	 */
	//const MENU_LOCAL_ACTION = self::MENU_IS_LOCAL_TASK | self::MENU_IS_LOCAL_ACTION | self::MENU_VISIBLE_IN_BREADCRUMB;
	const MENU_LOCAL_ACTION = 0x0184;

	/**
	 * Internal menu flag: Invisible local task.
	 *
	 * This flag may be used for local tasks like "Delete", so custom modules and
	 * themes can alter the default context and expose the task by altering menu.
	 */
	const MENU_CONTEXT_NONE = 0x0000;

	/**
	 * Internal menu flag: Local task should be displayed in page context.
	 */
	const MENU_CONTEXT_PAGE = 0x0001;

	/**
	 * Internal menu flag: Local task should be displayed inline.
	 */
	const MENU_CONTEXT_INLINE = 0x0002;

  /**
   * Watchdog status flags
   */
  const WATCHDOG_EMERGENCY = 0;
  const WATCHDOG_ALERT = 1;
  const WATCHDOG_CRITICAL = 2;
  const WATCHDOG_ERROR = 3;
  const WATCHDOG_WARNING = 4;
  const WATCHDOG_NOTICE = 5;
  const WATCHDOG_INFO = 6;
  const WATCHDOG_DEBUG = 7;

	private function __construct() {

	}

	public static function getInstance() {
		if (empty(self::$instance)) {
			self::$instance = new Intel_Df();
		}
		return self::$instance;
	}

	public static function check_plain($text) {
		return esc_html( $text );
		//return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Returns the GA page path of current page
	 */
	public static function current_pagepath() {
		return $_SERVER['REQUEST_URI'];
		global $wp;
		$current_url = home_url(add_query_arg(array(),$wp->request));


		Intel_Df::watchdog('$request_uri', $request_uri);//

		Intel_Df::watchdog('current_url', $current_url);//
		$site_url = get_site_url();
		Intel_Df::watchdog('get_site_url', $site_url);//
		$path = str_replace($site_url, '', $current_url);//
		Intel_Df::watchdog('path', $path);//
		return $path;
	}

	public static function current_path() {
		global $wp;
		if (!empty($_GET['q'])) {
			return $_GET['q'];
		}
		else {
			$current_url = home_url(add_query_arg(array(),$wp->request));
			return $current_url;
		}
	}

  /**
   * Adds a JavaScript file, setting, or inline code to the page.
   *
   * The behavior of this function depends on the parameters it is called with.
   * Generally, it handles the addition of JavaScript to the page, either as
   * reference to an existing file or as inline code. The following actions can be
   * performed using this function:
   * - Add a file ('file'): Adds a reference to a JavaScript file to the page.
   * - Add inline JavaScript code ('inline'): Executes a piece of JavaScript code
   *   on the current page by placing the code directly in the page (for example,
   *   to tell the user that a new message arrived, by opening a pop up, alert
   *   box, etc.). This should only be used for JavaScript that cannot be executed
   *   from a file. When adding inline code, make sure that you are not relying on
   *   $() being the jQuery function. Wrap your code in
   *   @code (function ($) {... })(jQuery); @endcode
   *   or use jQuery() instead of $().
   * - Add external JavaScript ('external'): Allows the inclusion of external
   *   JavaScript files that are not hosted on the local server. Note that these
   *   external JavaScript references do not get aggregated when preprocessing is
   *   on.
   * - Add settings ('setting'): Adds settings to Drupal's global storage of
   *   JavaScript settings. Per-page settings are required by some modules to
   *   function properly. All settings will be accessible at Drupal.settings.
   *
   * Examples:
   * @code
   *   drupal_add_js('misc/collapse.js');
   *   drupal_add_js('misc/collapse.js', 'file');
   *   drupal_add_js('jQuery(document).ready(function () { alert("Hello!"); });', 'inline');
   *   drupal_add_js('jQuery(document).ready(function () { alert("Hello!"); });',
   *     array('type' => 'inline', 'scope' => 'footer', 'weight' => 5)
   *   );
   *   drupal_add_js('http://example.com/example.js', 'external');
   *   drupal_add_js(array('myModule' => array('key' => 'value')), 'setting');
   * @endcode
   *
   * Calling drupal_static_reset('drupal_add_js') will clear all JavaScript added
   * so far.
   *
   * If JavaScript aggregation is enabled, all JavaScript files added with
   * $options['preprocess'] set to TRUE will be merged into one aggregate file.
   * Preprocessed inline JavaScript will not be aggregated into this single file.
   * Externally hosted JavaScripts are never aggregated.
   *
   * The reason for aggregating the files is outlined quite thoroughly here:
   * http://www.die.net/musings/page_load_time/ "Load fewer external objects. Due
   * to request overhead, one bigger file just loads faster than two smaller ones
   * half its size."
   *
   * $options['preprocess'] should be only set to TRUE when a file is required for
   * all typical visitors and most pages of a site. It is critical that all
   * preprocessed files are added unconditionally on every page, even if the
   * files are not needed on a page. This is normally done by calling
   * drupal_add_js() in a hook_init() implementation.
   *
   * Non-preprocessed files should only be added to the page when they are
   * actually needed.
   *
   * @param $data
   *   (optional) If given, the value depends on the $options parameter, or
   *   $options['type'] if $options is passed as an associative array:
   *   - 'file': Path to the file relative to base_path().
   *   - 'inline': The JavaScript code that should be placed in the given scope.
   *   - 'external': The absolute path to an external JavaScript file that is not
   *     hosted on the local server. These files will not be aggregated if
   *     JavaScript aggregation is enabled.
   *   - 'setting': An associative array with configuration options. The array is
   *     merged directly into Drupal.settings. All modules should wrap their
   *     actual configuration settings in another variable to prevent conflicts in
   *     the Drupal.settings namespace. Items added with a string key will replace
   *     existing settings with that key; items with numeric array keys will be
   *     added to the existing settings array.
   * @param $options
   *   (optional) A string defining the type of JavaScript that is being added in
   *   the $data parameter ('file'/'setting'/'inline'/'external'), or an
   *   associative array. JavaScript settings should always pass the string
   *   'setting' only. Other types can have the following elements in the array:
   *   - type: The type of JavaScript that is to be added to the page. Allowed
   *     values are 'file', 'inline', 'external' or 'setting'. Defaults
   *     to 'file'.
   *   - scope: The location in which you want to place the script. Possible
   *     values are 'header' or 'footer'. If your theme implements different
   *     regions, you can also use these. Defaults to 'header'.
   *   - group: A number identifying the group in which to add the JavaScript.
   *     Available constants are:
   *     - JS_LIBRARY: Any libraries, settings, or jQuery plugins.
   *     - JS_DEFAULT: Any module-layer JavaScript.
   *     - JS_THEME: Any theme-layer JavaScript.
   *     The group number serves as a weight: JavaScript within a lower weight
   *     group is presented on the page before JavaScript within a higher weight
   *     group.
   *   - every_page: For optimal front-end performance when aggregation is
   *     enabled, this should be set to TRUE if the JavaScript is present on every
   *     page of the website for users for whom it is present at all. This
   *     defaults to FALSE. It is set to TRUE for JavaScript files that are added
   *     via module and theme .info files. Modules that add JavaScript within
   *     hook_init() implementations, or from other code that ensures that the
   *     JavaScript is added to all website pages, should also set this flag to
   *     TRUE. All JavaScript files within the same group and that have the
   *     'every_page' flag set to TRUE and do not have 'preprocess' set to FALSE
   *     are aggregated together into a single aggregate file, and that aggregate
   *     file can be reused across a user's entire site visit, leading to faster
   *     navigation between pages. However, JavaScript that is only needed on
   *     pages less frequently visited, can be added by code that only runs for
   *     those particular pages, and that code should not set the 'every_page'
   *     flag. This minimizes the size of the aggregate file that the user needs
   *     to download when first visiting the website. JavaScript without the
   *     'every_page' flag is aggregated into a separate aggregate file. This
   *     other aggregate file is likely to change from page to page, and each new
   *     aggregate file needs to be downloaded when first encountered, so it
   *     should be kept relatively small by ensuring that most commonly needed
   *     JavaScript is added to every page.
   *   - weight: A number defining the order in which the JavaScript is added to
   *     the page relative to other JavaScript with the same 'scope', 'group',
   *     and 'every_page' value. In some cases, the order in which the JavaScript
   *     is presented on the page is very important. jQuery, for example, must be
   *     added to the page before any jQuery code is run, so jquery.js uses the
   *     JS_LIBRARY group and a weight of -20, jquery.once.js (a library drupal.js
   *     depends on) uses the JS_LIBRARY group and a weight of -19, drupal.js uses
   *     the JS_LIBRARY group and a weight of -1, other libraries use the
   *     JS_LIBRARY group and a weight of 0 or higher, and all other scripts use
   *     one of the other group constants. The exact ordering of JavaScript is as
   *     follows:
   *     - First by scope, with 'header' first, 'footer' last, and any other
   *       scopes provided by a custom theme coming in between, as determined by
   *       the theme.
   *     - Then by group.
   *     - Then by the 'every_page' flag, with TRUE coming before FALSE.
   *     - Then by weight.
   *     - Then by the order in which the JavaScript was added. For example, all
   *       else being the same, JavaScript added by a call to drupal_add_js() that
   *       happened later in the page request gets added to the page after one for
   *       which drupal_add_js() happened earlier in the page request.
   *   - requires_jquery: Set this to FALSE if the JavaScript you are adding does
   *     not have a dependency on jQuery. Defaults to TRUE, except for JavaScript
   *     settings where it defaults to FALSE. This is used on sites that have the
   *     'javascript_always_use_jquery' variable set to FALSE; on those sites, if
   *     all the JavaScript added to the page by drupal_add_js() does not have a
   *     dependency on jQuery, then for improved front-end performance Drupal
   *     will not add jQuery and related libraries and settings to the page.
   *   - defer: If set to TRUE, the defer attribute is set on the <script>
   *     tag. Defaults to FALSE.
   *   - cache: If set to FALSE, the JavaScript file is loaded anew on every page
   *     call; in other words, it is not cached. Used only when 'type' references
   *     a JavaScript file. Defaults to TRUE.
   *   - preprocess: If TRUE and JavaScript aggregation is enabled, the script
   *     file will be aggregated. Defaults to TRUE.
   *
   * @return
   *   The current array of JavaScript files, settings, and in-line code,
   *   including Drupal defaults, anything previously added with calls to
   *   drupal_add_js(), and this function call's additions.
   *
   * @see drupal_get_js()
   */
  public static function drupal_add_js($data = NULL, $options = NULL) {
    intel()->add_js($data, $options);
  }

	/**
	 * Sets a value in a nested array with variable depth.
	 *
	 * This helper function should be used when the depth of the array element you
	 * are changing may vary (that is, the number of parent keys is variable). It
	 * is primarily used for form structures and renderable arrays.
	 *
	 * Example:
	 * @code
	 * // Assume you have a 'signature' element somewhere in a form. It might be:
	 * $form['signature_settings']['signature'] = array(
	 *   '#type' => 'text_format',
	 *   '#title' => t('Signature'),
	 * );
	 * // Or, it might be further nested:
	 * $form['signature_settings']['user']['signature'] = array(
	 *   '#type' => 'text_format',
	 *   '#title' => t('Signature'),
	 * );
	 * @endcode
	 *
	 * To deal with the situation, the code needs to figure out the route to the
	 * element, given an array of parents that is either
	 * @code array('signature_settings', 'signature') @endcode in the first case or
	 * @code array('signature_settings', 'user', 'signature') @endcode in the second
	 * case.
	 *
	 * Without this helper function the only way to set the signature element in one
	 * line would be using eval(), which should be avoided:
	 * @code
	 * // Do not do this! Avoid eval().
	 * eval('$form[\'' . implode("']['", $parents) . '\'] = $element;');
	 * @endcode
	 *
	 * Instead, use this helper function:
	 * @code
	 * drupal_array_set_nested_value($form, $parents, $element);
	 * @endcode
	 *
	 * However if the number of array parent keys is static, the value should always
	 * be set directly rather than calling this function. For instance, for the
	 * first example we could just do:
	 * @code
	 * $form['signature_settings']['signature'] = $element;
	 * @endcode
	 *
	 * @param $array
	 *   A reference to the array to modify.
	 * @param $parents
	 *   An array of parent keys, starting with the outermost key.
	 * @param $value
	 *   The value to set.
	 * @param $force
	 *   (Optional) If TRUE, the value is forced into the structure even if it
	 *   requires the deletion of an already existing non-array parent value. If
	 *   FALSE, PHP throws an error if trying to add into a value that is not an
	 *   array. Defaults to FALSE.
	 *
	 * @see drupal_array_get_nested_value()
	 */
	public static function drupal_array_set_nested_value(array &$array, array $parents, $value, $force = FALSE) {
		$ref = &$array;
		foreach ($parents as $parent) {
			// PHP auto-creates container arrays and NULL entries without error if $ref
			// is NULL, but throws an error if $ref is set, but not an array.
			if ($force && isset($ref) && !is_array($ref)) {
				$ref = array();
			}
			$ref = &$ref[$parent];
		}
		$ref = $value;
	}

	/**
	 * Retrieves a value from a nested array with variable depth.
	 *
	 * This helper function should be used when the depth of the array element being
	 * retrieved may vary (that is, the number of parent keys is variable). It is
	 * primarily used for form structures and renderable arrays.
	 *
	 * Without this helper function the only way to get a nested array value with
	 * variable depth in one line would be using eval(), which should be avoided:
	 * @code
	 * // Do not do this! Avoid eval().
	 * // May also throw a PHP notice, if the variable array keys do not exist.
	 * eval('$value = $array[\'' . implode("']['", $parents) . "'];");
	 * @endcode
	 *
	 * Instead, use this helper function:
	 * @code
	 * $value = drupal_array_get_nested_value($form, $parents);
	 * @endcode
	 *
	 * A return value of NULL is ambiguous, and can mean either that the requested
	 * key does not exist, or that the actual value is NULL. If it is required to
	 * know whether the nested array key actually exists, pass a third argument that
	 * is altered by reference:
	 * @code
	 * $key_exists = NULL;
	 * $value = drupal_array_get_nested_value($form, $parents, $key_exists);
	 * if ($key_exists) {
	 *   // ... do something with $value ...
	 * }
	 * @endcode
	 *
	 * However if the number of array parent keys is static, the value should always
	 * be retrieved directly rather than calling this function. For instance:
	 * @code
	 * $value = $form['signature_settings']['signature'];
	 * @endcode
	 *
	 * @param $array
	 *   The array from which to get the value.
	 * @param $parents
	 *   An array of parent keys of the value, starting with the outermost key.
	 * @param $key_exists
	 *   (optional) If given, an already defined variable that is altered by
	 *   reference.
	 *
	 * @return
	 *   The requested nested value. Possibly NULL if the value is NULL or not all
	 *   nested parent keys exist. $key_exists is altered by reference and is a
	 *   Boolean that indicates whether all nested parent keys exist (TRUE) or not
	 *   (FALSE). This allows to distinguish between the two possibilities when NULL
	 *   is returned.
	 *
	 * @see drupal_array_set_nested_value()
	 */
	public static function &drupal_array_get_nested_value(array &$array, array $parents, &$key_exists = NULL) {
		$ref = &$array;
		foreach ($parents as $parent) {
			if (is_array($ref) && array_key_exists($parent, $ref)) {
				$ref = &$ref[$parent];
			}
			else {
				$key_exists = FALSE;
				$null = NULL;
				return $null;
			}
		}
		$key_exists = TRUE;
		return $ref;
	}

	/**
	 * Determines whether a nested array contains the requested keys.
	 *
	 * This helper function should be used when the depth of the array element to be
	 * checked may vary (that is, the number of parent keys is variable). See
	 * drupal_array_set_nested_value() for details. It is primarily used for form
	 * structures and renderable arrays.
	 *
	 * If it is required to also get the value of the checked nested key, use
	 * drupal_array_get_nested_value() instead.
	 *
	 * If the number of array parent keys is static, this helper function is
	 * unnecessary and the following code can be used instead:
	 * @code
	 * $value_exists = isset($form['signature_settings']['signature']);
	 * $key_exists = array_key_exists('signature', $form['signature_settings']);
	 * @endcode
	 *
	 * @param $array
	 *   The array with the value to check for.
	 * @param $parents
	 *   An array of parent keys of the value, starting with the outermost key.
	 *
	 * @return
	 *   TRUE if all the parent keys exist, FALSE otherwise.
	 *
	 * @see drupal_array_get_nested_value()
	 */
	public static function drupal_array_nested_key_exists(array $array, array $parents) {
		// Although this function is similar to PHP's array_key_exists(), its
		// arguments should be consistent with drupal_array_get_nested_value().
		$key_exists = NULL;
		self::drupal_array_get_nested_value($array, $parents, $key_exists);
		return $key_exists;
	}

	public static function drupal_array_merge_deep() {
		$args = func_get_args();
		return self::drupal_array_merge_deep_array($args);
	}

	public static function drupal_array_merge_deep_array($arrays) {
		$result = array();

		foreach ($arrays as $array) {
			foreach ($array as $key => $value) {
				// Renumber integer keys as array_merge_recursive() does. Note that PHP
				// automatically converts array keys that are integer strings (e.g., '1')
				// to integers.
				if (is_integer($key)) {
					$result[] = $value;
				}
				// Recurse when both values are arrays.
				elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
					$result[$key] = self::drupal_array_merge_deep_array(array($result[$key], $value));
				}
				// Otherwise, use the latter value, overriding any previous value.
				else {
					$result[$key] = $value;
				}
			}
		}

		return $result;
	}

	public static function drupal_attributes(array $attributes = array()) {
		foreach ($attributes as $attribute => &$data) {
			$data = implode(' ', (array) $data);
			$data = $attribute . '="' . self::check_plain($data) . '"';
		}
		return $attributes ? ' ' . implode(' ', $attributes) : '';
	}

	public static function drupal_clean_machinename($identifier) {
		$identifier = self::drupal_clean_css_identifier($identifier);
		return str_replace('-', '_', $identifier);
	}

	public static function drupal_clean_css_identifier($identifier, $filter = array(' ' => '-', '_' => '-', '/' => '-', '[' => '-', ']' => '')) {
		// Use the advanced drupal_static() pattern, since this is called very often.
		static $drupal_static_fast;
		if (!isset($drupal_static_fast)) {
			$drupal_static_fast['allow_css_double_underscores'] = &self::drupal_static(__FUNCTION__ . ':allow_css_double_underscores');
		}
		$allow_css_double_underscores = &$drupal_static_fast['allow_css_double_underscores'];
		if (!isset($allow_css_double_underscores)) {
			//$allow_css_double_underscores = variable_get('allow_css_double_underscores', FALSE);
			$allow_css_double_underscores = FALSE;
		}

		// Preserve BEM-style double-underscores depending on custom setting.
		if ($allow_css_double_underscores) {
			$filter['__'] = '__';
		}

		// By default, we filter using Drupal's coding standards.
		$identifier = strtr($identifier, $filter);

		// Valid characters in a CSS identifier are:
		// - the hyphen (U+002D)
		// - a-z (U+0030 - U+0039)
		// - A-Z (U+0041 - U+005A)
		// - the underscore (U+005F)
		// - 0-9 (U+0061 - U+007A)
		// - ISO 10646 characters U+00A1 and higher
		// We strip out any character not in the above list.
		$identifier = preg_replace('/[^\x{002D}\x{0030}-\x{0039}\x{0041}-\x{005A}\x{005F}\x{0061}-\x{007A}\x{00A1}-\x{FFFF}]/u', '', $identifier);

		return $identifier;
	}

	public static function drupal_get_destination() {
		$destination = &self::drupal_static(__FUNCTION__);

		if (isset($destination)) {
			return $destination;
		}

		if (isset($_GET['destination'])) {
			$destination = array('destination' => $_GET['destination']);
		}
		else {
			$path = $_GET['q'];
			$query = self::drupal_http_build_query(self::drupal_get_query_parameters());
			if ($query != '') {
				$path .= '?' . $query;
			}
			$destination = array('destination' => $path);
		}
		return $destination;
	}

	public static function drupal_get_path($type, $name) {
		if ($type == 'module' && $name == 'intel') {
			return INTEL_DIR;
		}
	}

	public static function drupal_goto($path = '', array $options = array(), $http_response_code = 302) {
		// A destination in $_GET always overrides the function arguments.
		// We do not allow absolute URLs to be passed via $_GET, as this can be an attack vector.
		if (isset($_GET['destination']) && !self::url_is_external($_GET['destination'])) {
			$destination = self::drupal_parse_url($_GET['destination']);
			$path = $destination['path'];
			$options['query'] = $destination['query'];
			$options['fragment'] = $destination['fragment'];
		}

		// In some cases modules call drupal_goto(current_path()). We need to ensure
		// that such a redirect is not to an external URL.
		if ($path === self::current_path() && empty($options['external']) && self::url_is_external($path)) {
			// Force url() to generate a non-external URL.
			$options['external'] = FALSE;
		}

		//drupal_alter('drupal_goto', $path, $options, $http_response_code);
		$hook_data = array(
			'path' => &$path,
			'options' => &$options,
			'http_response_code' => &$http_response_code,
		);
		$hook_data = apply_filters('drupal_goto_alter', $hook_data);

		// The 'Location' HTTP header must be absolute.
		$options['absolute'] = TRUE;

		$url = self::url($path, $options);

		// remove anything set to the output buffer as to not block the location
		// header
		wp_redirect( $url, $http_response_code );
		exit;

		header('Location: ' . $url, TRUE, $http_response_code);

		// The "Location" header sends a redirect status code to the HTTP daemon. In
		// some cases this can be wrong, so we make sure none of the code below the
		// drupal_goto() call gets executed upon redirection.
		//drupal_exit($url);
		exit;
	}

	/**
	 * Prepares a string for use as a valid HTML ID and guarantees uniqueness.
	 *
	 * This function ensures that each passed HTML ID value only exists once on the
	 * page. By tracking the already returned ids, this function enables forms,
	 * blocks, and other content to be output multiple times on the same page,
	 * without breaking (X)HTML validation.
	 *
	 * For already existing IDs, a counter is appended to the ID string. Therefore,
	 * JavaScript and CSS code should not rely on any value that was generated by
	 * this function and instead should rely on manually added CSS classes or
	 * similarly reliable constructs.
	 *
	 * Two consecutive hyphens separate the counter from the original ID. To manage
	 * uniqueness across multiple Ajax requests on the same page, Ajax requests
	 * POST an array of all IDs currently present on the page, which are used to
	 * prime this function's cache upon first invocation.
	 *
	 * To allow reverse-parsing of IDs submitted via Ajax, any multiple consecutive
	 * hyphens in the originally passed $id are replaced with a single hyphen.
	 *
	 * @param $id
	 *   The ID to clean.
	 *
	 * @return
	 *   The cleaned ID.
	 */
	public static function drupal_html_id($id) {
		// If this is an Ajax request, then content returned by this page request will
		// be merged with content already on the base page. The HTML IDs must be
		// unique for the fully merged content. Therefore, initialize $seen_ids to
		// take into account IDs that are already in use on the base page.
		$seen_ids_init = &self::drupal_static(__FUNCTION__ . ':init');
		if (!isset($seen_ids_init)) {
			// Ideally, Drupal would provide an API to persist state information about
			// prior page requests in the database, and we'd be able to add this
			// function's $seen_ids static variable to that state information in order
			// to have it properly initialized for this page request. However, no such
			// page state API exists, so instead, ajax.js adds all of the in-use HTML
			// IDs to the POST data of Ajax submissions. Direct use of $_POST is
			// normally not recommended as it could open up security risks, but because
			// the raw POST data is cast to a number before being returned by this
			// function, this usage is safe.
			if (empty($_POST['ajax_html_ids'])) {
				$seen_ids_init = array();
			}
			else {
				// This function ensures uniqueness by appending a counter to the base id
				// requested by the calling function after the first occurrence of that
				// requested id. $_POST['ajax_html_ids'] contains the ids as they were
				// returned by this function, potentially with the appended counter, so
				// we parse that to reconstruct the $seen_ids array.
				if (isset($_POST['ajax_html_ids'][0]) && strpos($_POST['ajax_html_ids'][0], ',') === FALSE) {
					$ajax_html_ids = $_POST['ajax_html_ids'];
				}
				else {
					// jquery.form.js may send the server a comma-separated string as the
					// first element of an array (see http://drupal.org/node/1575060), so
					// we need to convert it to an array in that case.
					$ajax_html_ids = explode(',', $_POST['ajax_html_ids'][0]);
				}
				foreach ($ajax_html_ids as $seen_id) {
					// We rely on '--' being used solely for separating a base id from the
					// counter, which this function ensures when returning an id.
					$parts = explode('--', $seen_id, 2);
					if (!empty($parts[1]) && is_numeric($parts[1])) {
						list($seen_id, $i) = $parts;
					}
					else {
						$i = 1;
					}
					if (!isset($seen_ids_init[$seen_id]) || ($i > $seen_ids_init[$seen_id])) {
						$seen_ids_init[$seen_id] = $i;
					}
				}
			}
		}
		$seen_ids = &self::drupal_static(__FUNCTION__, $seen_ids_init);

		$id = strtr(strtolower($id), array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));

		// As defined in http://www.w3.org/TR/html4/types.html#type-name, HTML IDs can
		// only contain letters, digits ([0-9]), hyphens ("-"), underscores ("_"),
		// colons (":"), and periods ("."). We strip out any character not in that
		// list. Note that the CSS spec doesn't allow colons or periods in identifiers
		// (http://www.w3.org/TR/CSS21/syndata.html#characters), so we strip those two
		// characters as well.
		$id = preg_replace('/[^A-Za-z0-9\-_]/', '', $id);

		// Removing multiple consecutive hyphens.
		$id = preg_replace('/\-+/', '-', $id);
		// Ensure IDs are unique by appending a counter after the first occurrence.
		// The counter needs to be appended with a delimiter that does not exist in
		// the base ID. Requiring a unique delimiter helps ensure that we really do
		// return unique IDs and also helps us re-create the $seen_ids array during
		// Ajax requests.
		if (isset($seen_ids[$id])) {
			$id = $id . '--' . ++$seen_ids[$id];
		}
		else {
			$seen_ids[$id] = 1;
		}

		return $id;
	}

	public static function drupal_http_build_query(array $query, $parent = '') {
		$params = array();

		foreach ($query as $key => $value) {
			$key = ($parent ? $parent . '[' . rawurlencode($key) . ']' : rawurlencode($key));

			// Recurse into children.
			if (is_array($value)) {
				$params[] = self::drupal_http_build_query($value, $key);
			}
			// If a query parameter value is NULL, only append its key.
			elseif (!isset($value)) {
				$params[] = $key;
			}
			else {
				// For better readability of paths in query strings, we decode slashes.
				$params[] = $key . '=' . str_replace('%2F', '/', rawurlencode($value));
			}
		}

		return implode('&', $params);
	}

	public static function drupal_map_assoc($array, $function = NULL) {
		// array_combine() fails with empty arrays:
		// http://bugs.php.net/bug.php?id=34857.
		$array = !empty($array) ? array_combine($array, $array) : array();
		if (is_callable($function)) {
			$array = array_map($function, $array);
		}
		return $array;
	}

	public static function drupal_set_message($message = NULL, $type = 'status', $repeat = TRUE) {
		if ($message || $message === '0' || $message === 0) {
			if (!isset($_SESSION['intel_messages'][$type])) {
				$_SESSION['intel_messages'][$type] = array();
			}

			if ($repeat || !in_array($message, $_SESSION['intel_messages'][$type])) {
				$_SESSION['intel_messages'][$type][] = $message;
			}

			// Mark this page as being uncacheable.
			//drupal_page_is_cacheable(FALSE);
		}

		// Messages not set when DB connection fails.
		return isset($_SESSION['intel_messages']) ? $_SESSION['intel_messages'] : NULL;
	}

	public static function drupal_get_messages($type = NULL, $clear_queue = TRUE) {
		if ($messages = self::drupal_set_message()) {
			if ($type) {
				if ($clear_queue) {
					unset($_SESSION['intel_messages'][$type]);
				}
				if (isset($messages[$type])) {
					return array($type => $messages[$type]);
				}
			}
			else {
				if ($clear_queue) {
					unset($_SESSION['intel_messages']);
				}
				return $messages;
			}
		}
		return array();
	}

	public static function drupal_set_title($title) {
		intel()->set_page_title($title);
	}

	public static function drupal_get_title() {
		return intel()->get_page_title();
	}

	public static function drupal_match_path($path, $patterns) {
		$regexps = &self::drupal_static(__FUNCTION__);

		if (!isset($regexps[$patterns])) {
			// Convert path settings to a regular expression.
			// Therefore replace newlines with a logical or, /* with asterisks and the <front> with the frontpage.
			$to_replace = array(
				'/(\r\n?|\n)/', // newlines
				'/\\\\\*/', // asterisks
				'/(^|\|)\\\\<home\\\\>($|\|)/' // <front>
			);
			$replacements = array(
				'|',
				'.*',
				'\1' . preg_quote(get_option('intel_site_frontpage', ''), '/') . '\2',
			);
			$patterns_quoted = preg_quote($patterns, '/');
			$regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';
		}
		return (bool) preg_match($regexps[$patterns], $path);
	}

	/**
	 * Similar to php parse_url but produces components needed for Intel_Df::url
	 *
	 * @param $url
	 * @return array
	 */
	public static function drupal_parse_url($url) {
		$options = array(
			'path' => NULL,
			'query' => array(),
			'fragment' => '',
		);

		// External URLs: not using parse_url() here, so we do not have to rebuild
		// the scheme, host, and path without having any use for it.
		if (strpos($url, '://') !== FALSE) {
			// Split off everything before the query string into 'path'.
			$parts = explode('?', $url);
			$options['path'] = $parts[0];
			// If there is a query string, transform it into keyed query parameters.
			if (isset($parts[1])) {
				$query_parts = explode('#', $parts[1]);
				parse_str($query_parts[0], $options['query']);
				// Take over the fragment, if there is any.
				if (isset($query_parts[1])) {
					$options['fragment'] = $query_parts[1];
				}
			}
		}
		// Internal URLs.
		else {
			// parse_url() does not support relative URLs, so make it absolute. E.g. the
			// relative URL "foo/bar:1" isn't properly parsed.
			$parts = parse_url('http://example.com/' . $url);
			// Strip the leading slash that was just added.
			$options['path'] = substr($parts['path'], 1);
			if (isset($parts['query'])) {
				parse_str($parts['query'], $options['query']);
			}
			if (isset($parts['fragment'])) {
				$options['fragment'] = $parts['fragment'];
			}

			$intel = intel();
			// parse off base_paths
			// check if admin page
			if (strpos($options['path'], 'admin.php') !== FALSE) {
				if (substr($options['path'], 0, strlen($intel->base_path_admin)) == $intel->base_path_admin) {
					$options['path'] = substr($options['path'], strlen($intel->base_path_admin));
				}
			}
			else {
				if (substr($options['path'], 0, strlen($intel->base_path_front)) == $intel->base_path_front) {
					$options['path'] = substr($options['path'], strlen($intel->base_path_front));
				}
			}
		}
		// The 'q' parameter contains the path of the current page if clean URLs are
		// disabled. It overrides the 'path' of the URL when present, even if clean
		// URLs are enabled, due to how Apache rewriting rules work.
		if (isset($options['query']['q'])) {
			$options['path'] = $options['query']['q'];
			unset($options['query']['q']);
		}

		return $options;
	}

	public static function drupal_pre_render_link($element) {
		// By default, link options to pass to l() are normally set in #options.
		$element += array('#options' => array());
		// However, within the scope of renderable elements, #attributes is a valid
		// way to specify attributes, too. Take them into account, but do not override
		// attributes from #options.
		if (isset($element['#attributes'])) {
			$element['#options'] += array('attributes' => array());
			$element['#options']['attributes'] += $element['#attributes'];
		}

		// This #pre_render callback can be invoked from inside or outside of a Form
		// API context, and depending on that, a HTML ID may be already set in
		// different locations. #options should have precedence over Form API's #id.
		// #attributes have been taken over into #options above already.
		if (isset($element['#options']['attributes']['id'])) {
			$element['#id'] = $element['#options']['attributes']['id'];
		}
		elseif (isset($element['#id'])) {
			$element['#options']['attributes']['id'] = $element['#id'];
		}

		// Conditionally invoke ajax_pre_render_element(), if #ajax is set.
		if (isset($element['#ajax']) && !isset($element['#ajax_processed'])) {
			// If no HTML ID was found above, automatically create one.
			if (!isset($element['#id'])) {
				$element['#id'] = $element['#options']['attributes']['id'] = self::drupal_html_id('ajax-link');
			}
			// If #ajax['path] was not specified, use the href as Ajax request URL.
			if (!isset($element['#ajax']['path'])) {
				$element['#ajax']['path'] = $element['#href'];
				$element['#ajax']['options'] = $element['#options'];
			}
			$element = ajax_pre_render_element($element);
		}

		$element['#markup'] = self::l($element['#title'], $element['#href'], $element['#options']);
		return $element;
	}


	public static function drupal_pre_render_markup($elements) {
		$elements['#children'] = $elements['#markup'];
		return $elements;
	}

	public static function drupal_render_children(&$element, $children_keys = NULL) {
		if ($children_keys === NULL) {
			$children_keys = self::element_children($element);
		}
		$output = '';
		foreach ($children_keys as $key) {
			if (!empty($element[$key])) {
				$output .= self::drupal_render($element[$key]);
			}
		}
		return $output;
	}

	public static function drupal_sort_weight($a, $b) {
		$a_weight = is_array($a) && isset($a['weight']) ? $a['weight'] : 0;
		$b_weight = is_array($b) && isset($b['weight']) ? $b['weight'] : 0;
		if ($a_weight == $b_weight) {
			return 0;
		}
	  return $a_weight < $b_weight ? -1 : 1;
	}

	public static function &drupal_static($name, $default_value = NULL, $reset = FALSE) {
		static $data = array(), $default = array();
		// First check if dealing with a previously defined static variable.
		if (isset($data[$name]) || array_key_exists($name, $data)) {
			// Non-NULL $name and both $data[$name] and $default[$name] statics exist.
			if ($reset) {
				// Reset pre-existing static variable to its default value.
				$data[$name] = $default[$name];
			}
			return $data[$name];
		}
		// Neither $data[$name] nor $default[$name] static variables exist.
		if (isset($name)) {
			if ($reset) {
				// Reset was called before a default is set and yet a variable must be
				// returned.
				return $data;
			}
			// First call with new non-NULL $name. Initialize a new static variable.
			$default[$name] = $data[$name] = $default_value;
			return $data[$name];
		}
		// Reset all: ($name == NULL). This needs to be done one at a time so that
		// references returned by earlier invocations of drupal_static() also get
		// reset.
		foreach ($default as $name => $value) {
			$data[$name] = $value;
		}
		// As the function returns a reference, the return should always be a
		// variable.
		return $data;
	}

	public static function drupal_static_reset($name = NULL) {
		self::drupal_static($name, NULL, TRUE);
	}

	public static function element_children(&$elements, $sort = FALSE) {
		// Do not attempt to sort elements which have already been sorted.
		$sort = isset($elements['#sorted']) ? !$elements['#sorted'] : $sort;

		// Filter out properties from the element, leaving only children.
		$children = array();
		$sortable = FALSE;
		foreach ($elements as $key => $value) {
			if ($key === '' || $key[0] !== '#') {
				$children[$key] = $value;
				if (is_array($value) && isset($value['#weight'])) {
					$sortable = TRUE;
				}
			}
		}
		// Sort the children if necessary.
		if ($sort && $sortable) {
			uasort($children, 'self::element_sort');
			// Put the sorted children back into $elements in the correct order, to
			// preserve sorting if the same element is passed through
			// element_children() twice.
			foreach ($children as $key => $child) {
				unset($elements[$key]);
				$elements[$key] = $child;
			}
			$elements['#sorted'] = TRUE;
		}

		return array_keys($children);
	}

	public static function element_set_attributes(array &$element, array $map) {
		foreach ($map as $property => $attribute) {
			// If the key is numeric, the attribute name needs to be taken over.
			if (is_int($property)) {
				$property = '#' . $attribute;
			}
			// Do not overwrite already existing attributes.
			if (isset($element[$property]) && !isset($element['#attributes'][$attribute])) {
				$element['#attributes'][$attribute] = $element[$property];
			}
		}
	}

	public static function element_sort($a, $b) {
		$a_weight = (is_array($a) && isset($a['#weight'])) ? $a['#weight'] : 0;
		$b_weight = (is_array($b) && isset($b['#weight'])) ? $b['#weight'] : 0;
		if ($a_weight == $b_weight) {
			return 0;
		}
		return ($a_weight < $b_weight) ? -1 : 1;
	}

  public static function entity_get_controller($entity_type) {
    return intel()->get_entity_controller($entity_type);
  }

  public static function entity_get_info($entity_type = NULL) {
    return intel()->entity_info($entity_type);
  }

	public static function format_date($time, $format = '') {
		$formats = array(
			'' => 'Y-m-d G:i:s',
			'short' => 'm/d/Y - H:i',
			'medium' => 'Y-m-d G:i',
			'long' => 'l, F j, Y - g:i a',
			'duration' => ($time > 3600) ? 'G:m:s' : 'm:s',
		);
		if (!empty($formats[$format])) {
			$format = $formats[$format];
		}
		if (empty($format)) {
			$format = $formats[''];
		}
		return date_i18n($format, $time);
		return date($format, $time);
	}

	public static function format_plural($count, $singular, $plural, array $args = array(), array $options = array()) {
		$args['@count'] = $count;
		if ($count == 1) {
			return self::t($singular, $args, $options);
		}

		// Get the plural index through the gettext formula.
		$index = (function_exists('locale_get_plural')) ? locale_get_plural($count, isset($options['langcode']) ? $options['langcode'] : NULL) : -1;
		// If the index cannot be computed, use the plural as a fallback (which
		// allows for most flexiblity with the replaceable @count value).
		if ($index < 0) {
			return self::t($plural, $args, $options);
		}
		else {
			switch ($index) {
				case "0":
					return t($singular, $args, $options);
				case "1":
					return self::t($plural, $args, $options);
				default:
					unset($args['@count']);
					$args['@count[' . $index . ']'] = $count;
					return self::t(strtr($plural, array('@count' => '@count[' . $index . ']')), $args, $options);
			}
		}
	}

	public static function url($path = NULL, array $options = array()) {
    // Merge in defaults.
		$options += array(
			'fragment' => '',
			'query' => array(),
			'absolute' => FALSE,
			'alias' => FALSE,
			'prefix' => ''
		);

		$path0 = $path;

		if ($path == '.') {
			$path = self::current_path();
			$cur = array(
				'query' => self::drupal_get_query_parameters(),
			);
			$options += $cur;
		}

		$hook_data = array(
			'path' => &$path,
			'options' => &$options,
		);

		if (!isset($options['external'])) {
			$options['external'] = self::url_is_external($path);
		}

		// Preserve the original path before altering or aliasing.
		$original_path = $path;

		// Allow other modules to alter the outbound URL and options.
		//drupal_alter('url_outbound', $path, $options, $original_path);
		$hook_data = array(
			'path' => &$path,
			'options' => &$options,
			'original_path' => &$original_path,
		);

		if ($options['external'] && strpos($path, '//') == FALSE) {
			$hook_data = apply_filters('intel_url_urn_resolver', $hook_data);
			// check if path returned from resolver is external
			$options['external'] = self::url_is_external($path);
		}

		$hook_data = apply_filters('intel_url_outbound_alter', $hook_data);

		if (isset($options['fragment']) && $options['fragment'] !== '') {
			$options['fragment'] = '#' . $options['fragment'];
		}

		if ($options['external']) {
			// Split off the fragment.
			if (strpos($path, '#') !== FALSE) {
				list($path, $old_fragment) = explode('#', $path, 2);
				// If $options contains no fragment, take it over from the path.
				if (isset($old_fragment) && !$options['fragment']) {
					$options['fragment'] = '#' . $old_fragment;
				}
			}
			// Append the query.
			if ($options['query']) {
				$path .= (strpos($path, '?') !== FALSE ? '&' : '?') . self::drupal_http_build_query($options['query']);
			}

      //if (isset($options['https']) && variable_get('https', FALSE)) {
      if (isset($options['https'])) {
				if ($options['https'] === TRUE) {
					$path = str_replace('http://', 'https://', $path);
				}
				elseif ($options['https'] === FALSE) {
					$path = str_replace('https://', 'http://', $path);
				}
			}
			// Reassemble.
			return $path . $options['fragment'];
		}

		// Strip leading slashes from internal paths to prevent them becoming external
		// URLs without protocol. /example.com should not be turned into
		// //example.com.
		$path = ltrim($path, '/');

		//global $base_url, $base_secure_url, $base_insecure_url;
		$intel = intel();
		$base_url = $intel->base_url;
		$base_secure_url = $intel->base_secure_url;
		$base_insecure_url = $intel->base_insecure_url;


		// The base_url might be rewritten from the language rewrite in domain mode.
		if (!isset($options['base_url'])) {
			if (isset($options['https']) && get_option('https', FALSE)) {
				if ($options['https'] === TRUE) {
					$options['base_url'] = $base_secure_url;
					$options['absolute'] = TRUE;
				}
				elseif ($options['https'] === FALSE) {
					$options['base_url'] = $base_insecure_url;
					$options['absolute'] = TRUE;
				}
			}
			else {
				$options['base_url'] = $base_url;
			}
		}

		// The special path '<front>' links to the default front page.
		if ($path == '<front>') {
			$path = '';
		}
		elseif (!empty($path) && !$options['alias']) {
			//$language = isset($options['language']) && isset($options['language']->language) ? $options['language']->language : '';
			//$alias = drupal_get_path_alias($original_path, $language);
			//if ($alias != $original_path) {
			//	$path = $alias;
			//}
		}

		// WP shim start
		$admin_page = '';
		$path_args = explode('/', $path);
		if ($path_args[0] == 'admin') {
			if (count($path_args) > 1) {
				if ($path_args[1] == 'intel') {
					$admin_page = 'intel_index';
				}
				elseif ($path_args[1] == 'config') {
					$admin_page = 'intel_config';
				}
				elseif ($path_args[1] == 'reports') {
					$admin_page = 'intel_reports';
				}
				elseif ($path_args[1] == 'util') {
					$admin_page = 'intel_util';
				}
				elseif ($path_args[1] == 'help') {
					$admin_page = 'intel_help';
				}
				elseif ($path_args[1] == 'people') {
					$admin_page = 'intel_visitor';
				}
				elseif ($path_args[1] == 'annotations') {
					$admin_page = 'intel_annotation';
				}
			}
		}
		elseif($path_args[0] == 'visitor') {
			$admin_page = 'intel_visitor';
		}
		elseif($path_args[0] == 'submission') {
			$admin_page = 'intel_admin';
		}
		elseif($path_args[0] == 'annotation') {
			$admin_page = 'intel_annotation';
		}

		// run admin path checks
		$is_admin_path = 0;
		if (substr($path, 0, 9) == 'admin.php') {
			$is_admin_path = 1;
		}

		// if path uses /wp-admin/ or /subdir/wp-admin/ format, strip off base_path_admin
		if (substr($path0, 0, strlen($intel->base_path_admin)) == $intel->base_path_admin) {
			$path = substr($path0, strlen($intel->base_path_admin));
			$is_admin_path = 1;
		}
		// strip off wp-admin/ if on path
		elseif (substr($path, 0, 9) == 'wp-admin/') {
			$path = substr($path, 9);
			$is_admin_path = 1;
		}

		//
		if ($admin_page) {
			$options['query']['page'] = $admin_page;
			$options['query']['q'] = $path;
			$path = 'admin.php';
			$is_admin_path = 1;
		}

		if ($is_admin_path) {
			$base = $options['absolute'] ? $options['base_url'] . $intel->base_path_admin : $intel->base_path_admin;
			// check if current context is network admin and add correct url to stay on network admin
			if ($intel->is_network_admin) {
			  $base .= 'network/';
      }
			$prefix = empty($path) ? rtrim($options['prefix'], '/') : $options['prefix'];
		}
		else {
			if (substr($path0, 0, strlen($intel->base_path_front)) == $intel->base_path_front) {
				$path = substr($path0, strlen($intel->base_path_front));
			}
			$base = $options['absolute'] ? $options['base_url'] . $intel->base_path_front : $intel->base_path_front;
			$prefix = empty($path) ? rtrim($options['prefix'], '/') : $options['prefix'];
		}
		// WP shim for creating urls.
		if ($options['query']) {
			return $base . $path . '?' . self::drupal_http_build_query($options['query']) . $options['fragment'];
		}
		else {
			return $base . $path . $options['fragment'];
		}
		// WP shim end



		// With Clean URLs.
		if (!empty($GLOBALS['conf']['clean_url'])) {
			$path = drupal_encode_path($prefix . $path);
			if ($options['query']) {
				return $base . $path . '?' . self::drupal_http_build_query($options['query']) . $options['fragment'];
			}
			else {
				return $base . $path . $options['fragment'];
			}
		}
		// Without Clean URLs.
		else {
			$path = $prefix . $path;
			$query = array();
			if (!empty($path)) {
				$query['q'] = $path;
			}
			if ($options['query']) {
				// We do not use array_merge() here to prevent overriding $path via query
				// parameters.
				$query += $options['query'];
			}
			$query = $query ? ('?' . self::drupal_http_build_query($query)) : '';
			$script = isset($options['script']) ? $options['script'] : '';
			return esc_url($base . $script . $query . $options['fragment']);
		}
	}

	public static function l($text, $path, array $options = array()) {
		$options += array(
			'attributes' => array(),
			'html' => FALSE,
		);
		if (isset($options['attributes']['title']) && strpos($options['attributes']['title'], '<') !== FALSE) {
			$options['attributes']['title'] = strip_tags($options['attributes']['title']);
		}
		return '<a href="' . self::check_plain(self::url($path, $options)) . '"' . self::drupal_attributes($options['attributes']) . '>' . ($options['html'] ? $text : self::check_plain($text)) . '</a>';
	}

	public static function l_options_add_class ($class, $l_options = array()) {
		if (is_string($class)) {
			$class = array($class);
		}
		if (!isset($l_options['attributes'])) {
			$l_options['attributes'] = array();
		}
		if (!isset($l_options['attributes']['class'])) {
			$l_options['attributes']['class'] = array();
		}
		$l_options['attributes']['class'] = array_merge($l_options['attributes']['class'], $class);
		return $l_options;
	}

	public static function l_options_add_destination ($destination, $l_options = array()) {
		$query = array(
			'destination' => $destination,
		);
		$l_options = self::l_options_add_query($query, $l_options);
		return $l_options;
	}

	public static function l_options_add_query ($query, $l_options = array()) {
		if (!isset($l_options['query'])) {
			$l_options['query'] = array();
		}
		$l_options['query'] += $query;
		return $l_options;
	}

	public static function l_options_add_target ($target, $l_options = array()) {
		if (!isset($l_options['attributes'])) {
			$l_options['attributes'] = array();
		}
		$l_options['attributes']['target'] = $target;
		return $l_options;
	}

	public static function t($string, array $args = array(), array $options = array()) {
		// if replace value args, convert into sprintf format
		$wp_args = array();
		foreach ($args as $k => $v) {
			$search = '';
			if (strpos($string, $k) !== FALSE) {
			  if (!is_string($v)) {
          continue;
        }
				if (substr($k, 1, 0) == '@' ) {
					$v = self::check_plain($v);
				}
				if (substr($k, 1, 0) == '%' ) {
					$v = '<em class="placeholder">' . self::check_plain($v) . '</em>';
				}
				$wp_args[] = $v;
				$replace = '%' . count($wp_args) . '$s' ;
				$string = str_replace($k, $replace, $string);
			}
		}
		if (!empty($options['html'])) {
			$string = __($string, 'intel');
		}
		else {
			$string = esc_html__($string, 'intel');
		}
		if (!empty($wp_args)) {
			array_unshift($wp_args, $string);
			return call_user_func_array('sprintf', $wp_args);
		}
		else {
			return $string;
		}
	}

	/*
	 * theme function that can be called with array arguments
	 */
	public static function theme($hook, $variables = array()) {
		return self::theme_ref($hook, $variables);
	}

	/**
	 * Main theme function
	 *
	 * $variables processed as reference
	 *
	 * @param $hook
	 * @param array $variables
	 * @return mixed|string
	 */
	public static function theme_ref($hook, &$variables) {
		$output = '';
		$theme_info = intel()->theme_info();

		// DWP not sure why functions would send hook as an array, but form render
		// does
		if (!is_string($hook) || empty($theme_info[$hook])) {
			return '';
		}
		$info = $theme_info[$hook];

		// If a renderable array is passed as $variables, then set $variables to
		// the arguments expected by the theme function.
		if (isset($variables['#theme']) || isset($variables['#theme_wrappers'])) {
			$element = $variables;
			$variables = array();
			if (isset($info['variables'])) {
				foreach (array_keys($info['variables']) as $name) {
					if (isset($element["#$name"])) {
						$variables[$name] = $element["#$name"];
					}
				}
			}
			else {
				$variables[$info['render element']] = $element;
			}
		}

		// Merge in argument defaults.
		if (!empty($info['variables'])) {
			$variables += $info['variables'];
		}
		elseif (!empty($info['render element'])) {
			$variables += array($info['render element'] => array());
		}

		// include file if set
		if (!empty($info['file'])) {
			$fn = INTEL_DIR . $info['file'];
			if (file_exists($fn)) {
				include_once $fn;
			}
		}

		$func_prefix = !empty($info['function_prefix']) ? $info['function_prefix'] . '_' : '';
		//$func_prefix = '';

		// call preprocess functions
		if (is_callable($func_prefix . 'template_preprocess_' . $hook)) {
			call_user_func_array($func_prefix . 'template_preprocess_' . $hook, array(&$variables));
		}

		// fire hook_intel_preprocess_HOOK()
		// allow plugins to preprocess variables
		$variables = apply_filters('intel_preprocess_' . $hook, $variables);

		// call process functions
		if (is_callable($func_prefix . 'template_process_' . $hook)) {
			call_user_func_array($func_prefix . 'template_process_' . $hook, array(&$variables));
		}

		// fire hook_intel_process_HOOK()
		// allow plugins to preprocess variables
		$variables = apply_filters('intel_process_' . $hook, $variables);

		// function prop can be called callback or function
		if (!isset($theme_info[$hook]['function']) && isset($theme_info[$hook]['callback'])) {
			$theme_info[$hook]['function'] = $theme_info[$hook]['callback'];
		}

		// if template file, process variables via template file
		if (!empty($info['template'])) {
      if (!empty($info['path'])) {
        $file = $info['path'] . $info['template'] . '.tpl.php';
      }
      else {
        $file = INTEL_DIR . 'templates/' . $info['template'] . '.tpl.php';
      }

			if (file_exists($file)) {
				$output = self::process_template($file, $variables);
			}
		}
		elseif (!empty($info['function']) || !empty($info['callback'])) {
			$func = !empty($info['callback']) ? $info['callback'] : $func_prefix . $info['function'];
			if (is_callable($func)) {
				$output = call_user_func_array($func, array(&$variables));
			}
		}

		return $output;
	}

	public static function process_template($file, $variables) {
		foreach ($variables as $k => $v) {
			${$k} = $v;
		}
		ob_start();
		include $file;
		$output = ob_get_clean();
		return $output;
	}

	public static function request_uri() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		}
		else {
			if (isset($_SERVER['argv'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
			}
			elseif (isset($_SERVER['QUERY_STRING'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
			}
			else {
				$uri = $_SERVER['SCRIPT_NAME'];
			}
		}
		// Prevent multiple slashes to avoid cross site requests via the Form API.
		$uri = '/' . ltrim($uri, '/');

		return $uri;
	}

	public static function drupal_render($elements) {
		return self::render($elements);
	}

	/**
	 * @param $elements
	 * @return string
	 */
	public static function render($elements) {
		// Early-return nothing if user does not have access.
		if (empty($elements) || (isset($elements['#access']) && !$elements['#access'])) {
			return '';
		}

		// Do not print elements twice.
		if (!empty($elements['#printed'])) {
			return '';
		}

		// if already rendered as string, return the string
		if (is_string($elements)) {
			return $elements;
		}

		// Try to fetch the element's markup from cache and return.
		//if (isset($elements['#cache'])) {
		//	$cached_output = drupal_render_cache_get($elements);
		//	if ($cached_output !== FALSE) {
		//		return $cached_output;
		//	}
		//}

		// If #markup is set, ensure #type is set. This allows to specify just #markup
		// on an element without setting #type.
		if (isset($elements['#markup']) && !isset($elements['#type'])) {
			$elements['#type'] = 'markup';
		}

		// If the default values for this element have not been loaded yet, populate
		// them.
		if (isset($elements['#type']) && empty($elements['#defaults_loaded'])) {
			$elements += intel()->element_info($elements['#type']);
		}

		// Make any final changes to the element before it is rendered. This means
		// that the $element or the children can be altered or corrected before the
		// element is rendered into the final text.
		if (isset($elements['#pre_render'])) {
			foreach ($elements['#pre_render'] as $function) {
				if (is_callable($function)) {
					//$elements = $function($elements);
					$elements = call_user_func($function, $elements);
				}
				$function = 'intel_df_' . $function;
				if (is_callable($function)) {
					//$elements = $function($elements);
					$elements = call_user_func($function, $elements);
				}
			}
		}

		// Allow #pre_render to abort rendering.
		if (!empty($elements['#printed'])) {
			return '';
		}

		// Get the children of the element, sorted by weight.
		$children = self::element_children($elements, TRUE);

		// Initialize this element's #children, unless a #pre_render callback already
		// preset #children.
		if (!isset($elements['#children'])) {
			$elements['#children'] = '';
		}
		// Call the element's #theme function if it is set. Then any children of the
		// element have to be rendered there.
		if (isset($elements['#theme'])) {
			$elements['#children'] = self::theme($elements['#theme'], $elements);
		}
		// If #theme was not set and the element has children, render them now.
		// This is the same process as drupal_render_children() but is inlined
		// for speed.
		if ($elements['#children'] == '') {
			foreach ($children as $key) {
				$elements['#children'] .= self::render($elements[$key]);
			}
		}

		// Let the theme functions in #theme_wrappers add markup around the rendered
		// children.
		if (isset($elements['#theme_wrappers'])) {
			foreach ($elements['#theme_wrappers'] as $theme_wrapper) {
				$elements['#children'] = self::theme($theme_wrapper, $elements);
			}
		}

		// Filter the outputted content and make any last changes before the
		// content is sent to the browser. The changes are made on $content
		// which allows the output'ed text to be filtered.
		if (isset($elements['#post_render'])) {
			foreach ($elements['#post_render'] as $function) {
				if (is_callable($function)) {
					$elements['#children'] = $function($elements['#children'], $elements);
				}
				$function = 'intel_df_' . $function;
				if (is_callable($function)) {
					$elements = $function($elements);
				}
			}
		}

		// Add any JavaScript state information associated with the element.
		if (!empty($elements['#states'])) {
			//drupal_process_states($elements);
		}

		// Add additional libraries, CSS, JavaScript an other custom
		// attached data associated with this element.
		if (!empty($elements['#attached'])) {
			//drupal_process_attached($elements);
		}

		$prefix = isset($elements['#prefix']) ? $elements['#prefix'] : '';
		$suffix = isset($elements['#suffix']) ? $elements['#suffix'] : '';
		$output = $prefix . $elements['#children'] . $suffix;

		// Cache the processed element if #cache is set.
		//if (isset($elements['#cache'])) {
		//	drupal_render_cache_set($output, $elements);
		//}

		$elements['#printed'] = TRUE;
		return $output;
	}

	public static function theme_markup($element) {
		return (isset($element['#value']) ? $element['#value'] : '') . (isset($element['#children']) ? $element['#children'] : '');
	}

	public static function theme_html_tag($variables) {
		$element = isset($variables['element']) ? $variables['element'] : array();
		$keys = array(
			'tag',
			'attributes',
			'value',
			'value_prefix',
			'value_suffix',
		);
		foreach ($keys as $k) {
			if (isset($variables[$k])) {
				$element["#$k"] = $variables[$k];
			}
		}

		$attributes = isset($element['#attributes']) ? self::drupal_attributes($element['#attributes']) : '';
		if (!isset($element['#value'])) {
			return '<' . $element['#tag'] . $attributes . " />\n";
		}
		else {
			$output = '<' . $element['#tag'] . $attributes . '>';
			if (isset($element['#value_prefix'])) {
				$output .= $element['#value_prefix'];
			}
			$output .= (is_array($element['#value'])) ? self::theme_html_tag($element['#value']) : $element['#value'];
			if (isset($element['#value_suffix'])) {
				$output .= $element['#value_suffix'];
			}
			$output .= '</' . $element['#tag'] . ">\n";
			return $output;
		}
	}

	public static function theme_image($variables) {
		$attributes = $variables['attributes'];
		//$attributes['src'] = file_create_url($variables['path']);
		$attributes['src'] = $variables['path'];

		foreach (array('width', 'height', 'alt', 'title') as $key) {

			if (isset($variables[$key])) {
				$attributes[$key] = $variables[$key];
			}
		}
		return '<img' . self::drupal_attributes($attributes) . ' />';
	}

	/**
	 * Returns HTML for a link.
	 *
	 * All Drupal code that outputs a link should call the l() function. That
	 * function performs some initial preprocessing, and then, if necessary, calls
	 * theme('link') for rendering the anchor tag.
	 *
	 * To optimize performance for sites that don't need custom theming of links,
	 * the l() function includes an inline copy of this function, and uses that
	 * copy if none of the enabled modules or the active theme implement any
	 * preprocess or process functions or override this theme implementation.
	 *
	 * @param array $variables
	 *   An associative array containing the keys:
	 *   - text: The text of the link.
	 *   - path: The internal path or external URL being linked to. It is used as
	 *     the $path parameter of the url() function.
	 *   - options: (optional) An array that defaults to empty, but can contain:
	 *     - attributes: Can contain optional attributes:
	 *       - class: must be declared in an array. Example: 'class' =>
	 *         array('class_name1','class_name2').
	 *       - title: must be a string. Example: 'title' => 'Example title'
	 *       - Others are more flexible as long as they work with
	 *         drupal_attributes($variables['options']['attributes]).
	 *     - html: Boolean flag that tells whether text contains HTML or plain
	 *       text. If set to TRUE, the text value will not be sanitized so the
	calling function must ensure that it already contains safe HTML.
	 *   The elements $variables['options']['attributes'] and
	 *   $variables['options']['html'] are used in this function similarly to the
	 *   way that $options['attributes'] and $options['html'] are used in l().
	 *   The link itself is built by the url() function, which takes
	 *   $variables['path'] and $variables['options'] as arguments.
	 *
	 * @see l()
	 * @see url()
	 */
	public static function theme_link($variables) {
		return '<a href="' . Intel_Df::check_plain( Intel_Df::url($variables['path'], $variables['options'])) . '"' . Intel_Df::drupal_attributes($variables['options']['attributes']) . '>' . ($variables['options']['html'] ? $variables['text'] : Intel_Df::check_plain($variables['text'])) . '</a>';
	}

	public static function theme_item_list($variables) {

		$items = $variables['items'];
		$title = $variables['title'];
		$type = $variables['type'];
		$attributes = $variables['attributes'];

		if (empty($type)) {
			$type = 'ul';
		}

		// Only output the list container and title, if there are any list items.
		// Check to see whether the block title exists before adding a header.
		// Empty headers are not semantic and present accessibility challenges.
		$output = '<div class="item-list">';
		if (isset($title) && $title !== '') {
			$output .= '<h3>' . $title . '</h3>';
		}

		if (!empty($items)) {
			$output .= "<$type" . Intel_Df::drupal_attributes($attributes) . '>';
			$num_items = count($items);
			$i = 0;
			foreach ($items as $item) {
				$attributes = array();
				$children = array();
				$data = '';
				$i++;
				if (is_array($item)) {
					foreach ($item as $key => $value) {
						if ($key == 'data') {
							$data = $value;
						}
						elseif ($key == 'children') {
							$children = $value;
						}
						else {
							$attributes[$key] = $value;
						}
					}
				}
				else {
					$data = $item;
				}
				if (count($children) > 0) {
					// Render nested list.
					$data .= Intel_Df::theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
				}
				if ($i == 1) {
					$attributes['class'][] = 'first';
				}
				if ($i == $num_items) {
					$attributes['class'][] = 'last';
				}
				$output .= '<li' . Intel_Df::drupal_attributes($attributes) . '>' . $data . "</li>\n";
			}
			$output .= "</$type>";
		}
		$output .= '</div>';
		return $output;
	}

	public static function theme_table($variables) {
		$defs = array(
		  'header' => array(),
			'rows' => array(),
			'attributes' => array(),
			'caption' => '',
			'sticky' => 0,
			'empty' => 0,
			'colgroups' => array(),
		);
		$variables += $defs;
		$header = $variables['header'];
		$rows = $variables['rows'];
		$attributes = $variables['attributes'];
		$caption = $variables['caption'];
		$colgroups = $variables['colgroups'];
		$sticky = $variables['sticky'];
		$empty = $variables['empty'];

		// Add sticky headers, if applicable.
		if (count($header) && $sticky) {
			//drupal_add_js('misc/tableheader.js');
			// Add 'sticky-enabled' class to the table to identify it for JS.
			// This is needed to target tables constructed by this function.
			$attributes['class'][] = 'sticky-enabled';
		}

		// add bootstrap classes
		$attributes['class'][] = 'table';
		$attributes['class'][] = 'table-striped';
		$attributes['class'][] = 'table-hover';

		$output = '';
		//$output .= '<div class="row">';
		$output .= '<table' . Intel_Df::drupal_attributes($attributes) . ">\n";

		if (!empty($caption)) {
			$output .= '<caption>' . $caption . "</caption>\n";
		}

		// Format the table columns:
		if (count($colgroups)) {
			foreach ($colgroups as $number => $colgroup) {
				$attributes = array();

				// Check if we're dealing with a simple or complex column
				if (isset($colgroup['data'])) {
					foreach ($colgroup as $key => $value) {
						if ($key == 'data') {
							$cols = $value;
						}
						else {
							$attributes[$key] = $value;
						}
					}
				}
				else {
					$cols = $colgroup;
				}

				// Build colgroup
				if (is_array($cols) && count($cols)) {
					$output .= ' <colgroup' . Intel_Df::drupal_attributes($attributes) . '>';
					$i = 0;
					foreach ($cols as $col) {
						$output .= ' <col' . Intel_Df::drupal_attributes($col) . ' />';
					}
					$output .= " </colgroup>\n";
				}
				else {
					$output .= ' <colgroup' . Intel_Df::drupal_attributes($attributes) . " />\n";
				}
			}
		}

		// Add the 'empty' row message if available.
		if (!count($rows) && $empty) {
			$header_count = 0;
			foreach ($header as $header_cell) {
				if (is_array($header_cell)) {
					$header_count += isset($header_cell['colspan']) ? $header_cell['colspan'] : 1;
				}
				else {
					$header_count++;
				}
			}
			$rows[] = array(array('data' => $empty, 'colspan' => $header_count, 'class' => array('empty', 'message')));
		}

		// Format the table header:
		if (count($header)) {
			$ts = self::tablesort_init($header);
			// HTML requires that the thead tag has tr tags in it followed by tbody
			// tags. Using ternary operator to check and see if we have any rows.
			$output .= (count($rows) ? ' <thead><tr>' : ' <tr>');
			foreach ($header as $cell) {
				$cell = self::tablesort_header($cell, $header, $ts);
				$output .= self::_theme_table_cell($cell, TRUE);
			}
			// Using ternary operator to close the tags based on whether or not there are rows
			$output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
		}
		else {
			$ts = array();
		}

		// Format the table rows:
		if (count($rows)) {
			$output .= "<tbody>\n";
			$flip = array('even' => 'odd', 'odd' => 'even');
			$class = 'even';
			foreach ($rows as $number => $row) {
				// Check if we're dealing with a simple or complex row
				if (isset($row['data'])) {
					$cells = $row['data'];
					$no_striping = isset($row['no_striping']) ? $row['no_striping'] : FALSE;

					// Set the attributes array and exclude 'data' and 'no_striping'.
					$attributes = $row;
					unset($attributes['data']);
					unset($attributes['no_striping']);
				}
				else {
					$cells = $row;
					$attributes = array();
					$no_striping = FALSE;
				}
				if (count($cells)) {
					// Add odd/even class
					if (!$no_striping) {
						$class = $flip[$class];
						$attributes['class'][] = $class;
					}

					// Build row
					$output .= ' <tr' . Intel_Df::drupal_attributes($attributes) . '>';
					$i = 0;
					foreach ($cells as $cell) {
						$cell = self::tablesort_cell($cell, $header, $ts, $i++);
						$output .= self::_theme_table_cell($cell);
					}
					$output .= " </tr>\n";
				}
			}
			$output .= "</tbody>\n";
		}

		$output .= "</table>\n";
		//$output .= "</div>\n";
		return $output;
	}

	public static function tablesort_init($header) {
		$ts = self::tablesort_get_order($header);
		$ts['sort'] = self::tablesort_get_sort($header);
		$ts['query'] = self::tablesort_get_query_parameters();
		return $ts;
	}

	public static function tablesort_get_order($headers) {
		$order = isset($_GET['order']) ? $_GET['order'] : '';
		foreach ($headers as $header) {
			if (is_array($header)) {
				if (isset($header['data']) && $order == $header['data']) {
					$default = $header;
					break;
				}

				if (empty($default) && isset($header['sort']) && ($header['sort'] == 'asc' || $header['sort'] == 'desc')) {
					$default = $header;
				}
			}
		}

		if (!isset($default)) {
			$default = reset($headers);
			if (!is_array($default)) {
				$default = array('data' => $default);
			}
		}

		$default += array('data' => NULL, 'field' => NULL);
		return array('name' => $default['data'], 'sql' => $default['field']);
	}

	public static function tablesort_get_sort($headers) {
		if (isset($_GET['sort'])) {
			return (strtolower($_GET['sort']) == 'desc') ? 'desc' : 'asc';
		}
		// The user has not specified a sort. Use the default for the currently sorted
		// header if specified; otherwise use "asc".
		else {
			// Find out which header is currently being sorted.
			$ts = self::tablesort_get_order($headers);
			foreach ($headers as $header) {
				if (is_array($header) && isset($header['data']) && $header['data'] == $ts['name'] && isset($header['sort'])) {
					return $header['sort'];
				}
			}
		}
		return 'asc';
	}

	public static function tablesort_get_query_parameters() {
		return self::drupal_get_query_parameters($_GET, array('q', 'sort', 'order'));
	}

	public static function drupal_get_query_parameters(array $query = NULL, array $exclude = array('q'), $parent = '') {
		// Set defaults, if none given.
		if (!isset($query)) {
			$query = $_GET;
		}
		// If $exclude is empty, there is nothing to filter.
		if (empty($exclude)) {
			return $query;
		}
		elseif (!$parent) {
			$exclude = array_flip($exclude);
		}

		$params = array();
		foreach ($query as $key => $value) {
			$string_key = ($parent ? $parent . '[' . $key . ']' : $key);
			if (isset($exclude[$string_key])) {
				continue;
			}

			if (is_array($value)) {
				$params[$key] = self::drupal_get_query_parameters($value, $exclude, $string_key);
			}
			else {
				$params[$key] = $value;
			}
		}

		return $params;
	}

	public static function tablesort_header($cell, $header, $ts) {
		// Special formatting for the currently sorted column header.
		if (is_array($cell) && isset($cell['field'])) {
			$title = sprintf(__('sort by %s', 'intl'), array($cell['data']));
			if ($cell['data'] == $ts['name']) {
				$ts['sort'] = (($ts['sort'] == 'asc') ? 'desc' : 'asc');
				$cell['class'][] = 'active';
				$image = Intel_Df::theme('tablesort_indicator', array('style' => $ts['sort']));
			}
			else {
				// If the user clicks a different header, we want to sort ascending initially.
				$ts['sort'] = 'asc';
				$image = '';
			}
			$cell['data'] = Intel_Df::l($cell['data'] . $image, $_GET['q'], array('attributes' => array('title' => $title), 'query' => array_merge($ts['query'], array('sort' => $ts['sort'], 'order' => $cell['data'])), 'html' => TRUE));

			unset($cell['field'], $cell['sort']);
		}
		return $cell;
	}

	public static function tablesort_cell($cell, $header, $ts, $i) {
		if (isset($header[$i]['data']) && $header[$i]['data'] == $ts['name'] && !empty($header[$i]['field'])) {
			if (is_array($cell)) {
				$cell['class'][] = 'active';
			}
			else {
				$cell = array('data' => $cell, 'class' => array('active'));
			}
		}
		return $cell;
	}

	public static function _theme_table_cell($cell, $header = FALSE) {
		$attributes = '';

		if (is_array($cell)) {
			$data = isset($cell['data']) ? $cell['data'] : '';
			// Cell's data property can be a string or a renderable array.
			if (is_array($data)) {
				$data = drupal_render($data);
			}
			$header |= isset($cell['header']);
			unset($cell['data']);
			unset($cell['header']);
			$attributes = self::drupal_attributes($cell);
		}
		else {
			$data = $cell;
		}

		if ($header) {
			$output = "<th$attributes>$data</th>";
		}
		else {
			$output = "<td$attributes>$data</td>";
		}

		return $output;
	}

	public static function url_is_external($path) {
		$colonpos = strpos($path, ':');
		// Some browsers treat \ as / so normalize to forward slashes.
		$path = str_replace('\\', '/', $path);
		// If the path starts with 2 slashes then it is always considered an external
		// URL without an explicit protocol part.
		return (strpos($path, '//') === 0)
		// Leading control characters may be ignored or mishandled by browsers, so
		// assume such a path may lead to an external location. The \p{C} character
		// class matches all UTF-8 control, unassigned, and private characters.
		|| (preg_match('/^\p{C}/u', $path) !== 0)
		// Avoid calling drupal_strip_dangerous_protocols() if there is any slash
		// (/), hash (#) or question_mark (?) before the colon (:) occurrence - if
		// any - as this would clearly mean it is not a URL.
		|| ($colonpos !== FALSE
			&& !preg_match('![/?#]!', substr($path, 0, $colonpos))
			//	&& drupal_strip_dangerous_protocols($path) == $path);
		);
	}

	public static function user_access($capability, $account = NULL) {
		if (empty($account)) {
			return current_user_can( $capability );
		}
		else {
			return user_can( $account, $capability );
		}
	}

	public static function variable_get($name, $default) {
		get_option($name, $default);
	}

	public static function variable_set($name, $value) {
		update_option($name, $value);
	}

	public static function watchdog($type, $message = '', $variables = array(), $severity = Intel_Df::WATCHDOG_NOTICE, $link = NULL) {
		//$option_log = get_option('intel_debug_log', '');
    static $in_error_state = FALSE;

    // It is possible that the error handling will itself trigger an error. In that case, we could
    // end up in an infinite loop. To avoid that, we implement a simple static semaphore.
    if (!$in_error_state && function_exists('apply_filters')) {
      $in_error_state = TRUE;

      // The user object may not exist in all conditions, so 0 is substituted if needed.
      $user_uid = get_current_user_id();

      // Prepare the fields to be logged
      $log_entry = [
        'type' => $type,
        'message' => $message,
        'variables' => $variables,
        'severity' => $severity,
        'link' => $link,
        //'user' => $user,
        'uid' => $user_uid,
        'request_uri' => self::request_uri(), //$base_root . request_uri(),
        'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        //'ip' => ip_address(),
        // Request time isn't accurate for long processes, use time() instead.
        'timestamp' => time(),
      ];

      $log_entry = apply_filters('intel_watchdog', $log_entry);

      $mode = self::watchdog_mode();

      if ($mode == 'db') {
        $intel_log = intel_log_create($log_entry);
        intel_log_save($intel_log);
      }
      else if ($mode == 'error_log') {
        $msg = __('WATCHDOG', 'intel') . ': ' . $type . ":\n" . $message;
        //self::drupal_set_message($msg);
        error_log(self::t($log_entry['message'], $log_entry['variables'], ['html' => 1]));
      }

      // It is critical that the semaphore is only cleared here, in the parent
      // watchdog() call (not outside the loop), to prevent recursive execution.
      $in_error_state = FALSE;
    }
	}

	public static function watchdog_mode() {

	  if (isset($intel_wp_config_options['intel_watchdog_mode'])) {
      return $intel_wp_config_options['intel_watchdog_mode'];
    }

    if (intel()->is_network_active) {
      return get_site_option('intel_watchdog_mode', '');
    }

    return get_option('intel_watchdog_mode', '');
  }
}

/**
 * Factory for queues
 */
class IntelQueue {

	/**
	 * Returns the queue object for a given name
	 * @param $name
	 */
	public static function get($name) {
		static $queues;
		if (!isset($queues)) {
			$queues = array();
		}
		if (!isset($queues[$name])) {
			$queues[$name] = new IntelCronQueue($name);
		}
		return $queues[$name];
	}
}

class IntelCronQueue {
	/**
	 * The name of the queue this instance is working with.
	 *
	 * @var string
	 */
	protected $name;
	protected $option_name;

	public function __construct($name) {
		$this->name = $name;
		$this->option_name = $name . '_queue';
	}

	public function getInstance($name) {

	}

	public function numberOfItems() {
		$items = get_option($this->option_name, array());
		return count($items);
	}

	public function createItem($data, $id = NULL) {
		$items = get_option($this->option_name, array());
		$id = !empty($id) ? $id : "" . time() . count($items);
		$items[$id] = array(
			'created' => time(),
			'data' => serialize($data),
			'item_id' => $id,
		);
		update_option($this->option_name, $items);
	}

	public function deleteItem($item) {
		$items = get_option($this->option_name, array());
		unset($items[$item['item_id']]);
		update_option($this->option_name, $items);
	}

	public function claimItem($lease_time = 60) {
		$items = get_option($this->option_name, array());
		foreach ($items as $i => $item) {
			if (empty($item['expire']) || (time() >= $item['expire']) ) {
				$items[$i]['expire'] = time() + $lease_time;
				update_option($this->option_name, $items);
				$item['data'] = unserialize($item['data']);
				return $item;
			}
		}
		return FALSE;
	}

	public function claimItemById($id, $lease_time = 60) {
		$items = get_option($this->option_name, array());
		if (!empty($items[$id])) {
			$item = $items[$id];
			$items[$id]['expire'] = time() + $lease_time;
			update_option($this->option_name, $items);
			$item['data'] = unserialize($item['data']);
			return $item;
		}
		return FALSE;
	}

	public function releaseItem($item) {
		$items = get_option($this->option_name, array());
		unset($items[$item['item_id']]['expire']);
		update_option($this->option_name, $items);
	}


}
