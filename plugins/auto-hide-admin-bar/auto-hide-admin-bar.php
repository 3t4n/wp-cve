<?php
/*
Plugin Name: Auto Hide Admin Bar
Description: Automatically hides the Toolbar. Will show the Toolbar when hovering over the top of the site.
Author: Marcel Bootsman
Version: 1.6.3
Author URI: https://marcelbootsman.nl
Text Domain: auto-hide-admin-bar
Domain Path: /languages/

*/

/* ----------------------------------------------------------------------------
 *  Global data */
$plugin_file              = dirname(__FILE__) . '/auto-hide-admin-bar.php';
$plugin_path              = plugin_dir_path($plugin_file);
$keyboard_shortcut_fields = array(
	'Ctrl'  => 0,
	'Alt'   => 0,
	'Shift' => 0,
	'char'  => ''
);

/* Define some default values */
define('DEFAULT_SPEED', 200);
define('DEFAULT_DELAY', 1500);
define('DEFAULT_INTERVAL', 100);
define('DEFAULT_MOBILE', 1);
define('DEFAULT_TOGGLE', 1);
define('DEFAULT_ARROW', 1);
define('DEFAULT_ARROW_POS', 'left');

/**
 * Returns current plugin version.
 *
 * @return string Plugin version
 */
function plugin_get_version() {
	$plugin_data    = get_plugin_data(__FILE__);
	$plugin_version = $plugin_data['Version'];

	return $plugin_version;
}

/* Load CSS files */
function ahab_admin_styles() {
	// only load if a user is logged in
	if (is_user_logged_in()) {
		wp_enqueue_style('admin-styles', plugin_dir_url(__FILE__) . 'css/ahab.css');
	}
}
add_action('wp_enqueue_scripts', 'ahab_admin_styles');

/**
 * Include options page for admin area
 *
 */
if (is_admin()) {
	include_once $plugin_path . 'ahab_options.php';
}

/**
 * Add Settings link to plugin page
 *
 * @param Array $links , filename $file
 *
 * @return Array $links with new link=
 * @author Marcel Bootsman
 *
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ahab_add_plugin_actions_links');
function ahab_add_plugin_actions_links($links) {

	$ahab_links = array(
		'<a href="options-general.php?page=auto-hide-admin-bar">' . __("Settings", "auto-hide-admin-bar") . '</a>'
	);

	return array_merge($links, $ahab_links);
}

/**
 * Check if ahab is disabled (by user role)
 *
 * @param None
 *
 * @return true / false
 * @author Marcel Bootsman
 *
 */

function is_ahab_disabled() {
	$ahab_disabled = false;

	// Get options
	$options = get_option('ahab_plugin_options');

	// check if ahab is disabled for current user role
	global $wp_roles, $current_user, $ahab_disabled;

	foreach ($wp_roles->roles as $role_key => $role) {
		// disabled user roles are stored as a seperate array element

		if ($options) { // only continue if options exists

			if (!empty($options['disabled_user_roles_' . $role_key])) {
				// check if current user role matches the role

				if (in_array($role_key, $current_user->roles)) {

					$ahab_disabled = true;

					// leave the foreach loop
					break;
				}
			} else {
				//no role options set (thanks for updating/installing!), enable ahab for everyone.
				$ahab_disabled = false;
			}
		} else {
			//no options set, enable ahab for everyone.
			$ahab_disabled = false;
		}
	}

	return $ahab_disabled;
}

/* Add Toggle to admin bar */
add_action('admin_bar_menu', 'ahab_admin_bar_item', 0);
function ahab_admin_bar_item(WP_Admin_Bar $admin_bar) {
	$options = get_option('ahab_plugin_options');

	if (!is_admin()) {
		if ((!empty($options['toggle'])) && (2 == $options['toggle'])) {


			$admin_bar->add_menu(array(
				'id'    => 'ahab-toggle',
				'parent' => null,
				'group'  => null,
				'title' => '<div class="ahab"><label class="switch">
		<input id="toggle-checkbox" type="checkbox">
		<span class="slider round"></span>
	  </label></div>',
				'href'  => '',
				'meta' => [
					'title' => __('Toggle lock for the Admin bar', 'auto-hide-admin-bar'), //This title will show on hover
				]
			));
		}
	}
}


/**
 * The main function. Build JS code and output it.
 *
 * @param None
 *
 * @return None
 * @author Marcel Bootsman
 *
 */

function auto_hide_admin_bar() {
	// Get options
	$options = get_option('ahab_plugin_options');
	global $keyboard_shortcut_fields;

	if ((!empty($options['speed'])) && (is_numeric($options['speed']))) {
		$ahab_anim_speed = $options['speed'];
	} else {
		$ahab_anim_speed = DEFAULT_SPEED;
	}

	if ((!empty($options['delay'])) && (is_numeric($options['delay']))) {
		$ahab_delay = $options['delay'];
	} else {
		$ahab_delay = DEFAULT_DELAY;
	}

	if ((!empty($options['interval'])) && (is_numeric($options['interval']))) {
		$ahab_interval = $options['interval'];
	} else {
		$ahab_interval = DEFAULT_INTERVAL;
	}

	if ((!empty($options['arrow'])) && (is_numeric($options['arrow']))) {
		$ahab_arrow = $options['arrow'];
	} else {
		$ahab_arrow = DEFAULT_ARROW;
	}

	if ((!empty($options['arrow_pos'])) && (is_string($options['arrow_pos']))) {
		$ahab_arrow_pos = $options['arrow_pos'];
	} else {
		$ahab_arrow_pos = DEFAULT_ARROW_POS;
	}

	if ((!empty($options['mobile'])) && (is_numeric($options['mobile']))) {
		$ahab_mobile = $options['mobile'];
	} else {
		$ahab_mobile = DEFAULT_MOBILE;
	}

	// get keys and prepare to pass to JS
	$ahab_keyboard_shortcut_keys = array();

	foreach ($keyboard_shortcut_fields as $key => $value) {
		if ($options) { // only continue if options exists
			if (!empty($options['keyboard_shortcut_' . $key])) {
				if ('' != $options['keyboard_shortcut_' . $key]) {
					$ahab_keyboard_shortcut_keys[$key] = $options['keyboard_shortcut_' . $key];
				}
			}
		}
	}

	/**
	 * Theme name check - For now only for Twenty Fourteen
	 * because of the fixed header/menu
	 **/
	if (function_exists('wp_get_theme')) {
		$theme_name = (wp_get_theme()->Template);
	};
?>
	<script type='text/javascript'>
		// For passing the variables to the ahab.js file
		ahab = {
			'theme_name': '<?php echo $theme_name; ?>',
			'ahab_anim_speed': <?php echo $ahab_anim_speed; ?>,
			'ahab_delay': <?php echo $ahab_delay; ?>,
			'ahab_interval': <?php echo $ahab_interval; ?>,
			'ahab_mobile': '<?php echo $ahab_mobile; ?>',
			'ahab_arrow': '<?php echo $ahab_arrow; ?>',
			'ahab_arrow_pos': '<?php echo $ahab_arrow_pos; ?>',
			'ahab_keyboard_ctrl': <?php echo array_key_exists('Ctrl', $ahab_keyboard_shortcut_keys) ? '\'' . $ahab_keyboard_shortcut_keys['Ctrl'] . '\'' : 0; ?>,
			'ahab_keyboard_alt': <?php echo array_key_exists('Alt', $ahab_keyboard_shortcut_keys) ? '\'' . $ahab_keyboard_shortcut_keys['Alt'] . '\'' : 0; ?>,
			'ahab_keyboard_shift': <?php echo array_key_exists('Shift', $ahab_keyboard_shortcut_keys) ? '\'' . $ahab_keyboard_shortcut_keys['Shift'] . '\'' : 0; ?>,
			'ahab_keyboard_char': <?php echo array_key_exists('char', $ahab_keyboard_shortcut_keys) ? '\'' . $ahab_keyboard_shortcut_keys['char'] . '\'' : '\'\''; ?>
		};
	</script>
<?php
}

/**
 * Add jQuery and jQuery hoverIntent (No tents were harmed in the process)
 *
 * @param None
 *
 * @return None
 * @author Marcel Bootsman
 *
 */

add_action('wp_footer', 'ahab_add_jquery_stuff');
function ahab_add_jquery_stuff() {

	if (is_user_logged_in() && (!is_ahab_disabled())) {

		wp_enqueue_script('jquery');

		wp_register_script('jquery-hoverintent', plugins_url('js/jquery.hoverIntent.minified.js', __FILE__));
		wp_enqueue_script('jquery-hoverintent');

		wp_enqueue_script('jquery-hotkeys');

		wp_register_script('ahab', plugins_url('js/ahab.js', __FILE__));
		wp_enqueue_script('ahab');
	}
}

/**
 * Hook main function for logged in users
 *
 * @param None
 *
 * @return None
 * @author Marcel Bootsman
 *
 */
add_action('wp_footer', 'ahab_add_my_hide_stuff');
function ahab_add_my_hide_stuff() {
	if (is_user_logged_in() && (!is_ahab_disabled())) {
		auto_hide_admin_bar();
	}
}

/**
 * Load Text Domain
 *
 * @param None
 *
 * @return None
 * @author Marcel Bootsman
 *
 */
add_action('plugins_loaded', 'auto_hide_admin_bar_load_textdomain');
function auto_hide_admin_bar_load_textdomain() {
	load_plugin_textdomain('auto-hide-admin-bar', false, basename(dirname(__FILE__)) . '/languages/');
}
?>