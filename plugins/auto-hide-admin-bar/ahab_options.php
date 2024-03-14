<?php

/**
 * This takes care of all the options for Auto Hide Admin Bar
 *
 * @author Marcel Bootsman
 */

add_action('admin_menu', 'ahab_plugin_add_options_page');
/**
 * Add an option page as subpage to Plugins.
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_add_options_page() {
	add_options_page('Auto Hide Admin Bar Options', 'Auto Hide Admin Bar', 'manage_options', 'auto-hide-admin-bar', 'ahab_options_page');
}

/**
 * Display the plugin options page
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_options_page() {
?>
	<div class="wrap">
		<h2><?php _e('Auto Hide Admin Bar Options', 'auto-hide-admin-bar'); ?></h2>
		<?php _e('Options for Auto Hide Admin Bar.', 'auto-hide-admin-bar'); ?>
		<form action="options.php" method="post">
			<?php settings_fields('ahab_plugin_options'); ?>
			<?php do_settings_sections('ahab_plugin'); ?>
			<input name="Submit" type="submit" class="button button-primary save" value="<?php _e('Save Changes', 'auto-hide-admin-bar'); ?>" />
		</form>
		<p><?php _e('Version: ', 'auto-hide-admin-bar'); ?><?php echo plugin_get_version(); ?>
	</div>
<?php
}

add_action('admin_init', 'ahab_plugin_options_init');
/**
 * Add the plugin options, sections and fields
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_options_init() {
	register_setting('ahab_plugin_options', 'ahab_plugin_options', 'ahab_validate_input');

	# Speed settings
	add_settings_section(
		'ahab_plugin_section_speed',
		__('Set speed', 'auto-hide-admin-bar'),
		'ahab_plugin_section_speed_text',
		'ahab_plugin'
	);
	add_settings_field(
		'ahab_plugin_option_speed',
		__('Animation speed:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_speed',
		'ahab_plugin',
		'ahab_plugin_section_speed'
	);

	# Delay settings
	add_settings_section('ahab_plugin_section_delay', __(
		'Set amount of delay for hiding the Toolbar',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_delay_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_option_delay',
		__('Delay:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_delay',
		'ahab_plugin',
		'ahab_plugin_section_delay'
	);

	# Mouse polling settings
	add_settings_section('ahab_plugin_section_interval', __(
		'Set the interval for mouse polling',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_interval_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_option_interval',
		__('Interval:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_interval',
		'ahab_plugin',
		'ahab_plugin_section_interval'
	);

	# Visual options
	add_settings_section('ahab_plugin_section_visual', __(
		'Visual options',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_visual_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_toggle_button',
		__('Show or hide the toggle button:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_toggle',
		'ahab_plugin',
		'ahab_plugin_section_visual'
	);
	add_settings_field(
		'ahab_plugin_option_arrow',
		__('Show or hide an arrow:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_arrow',
		'ahab_plugin',
		'ahab_plugin_section_visual'
	);
	add_settings_field(
		'ahab_plugin_option_arrow_position',
		__('Arrow position:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_arrow_position',
		'ahab_plugin',
		'ahab_plugin_section_visual'
	);

	# Mobile settings
	add_settings_section('ahab_plugin_section_mobile', __(
		'Always show if on mobile (smaller than 782px)',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_mobile_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_option_mobile',
		__('Show or hide on small screens:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_mobile',
		'ahab_plugin',
		'ahab_plugin_section_mobile'
	);

	# User role settings
	add_settings_section('ahab_plugin_section_user_roles', __(
		'Disable for user roles',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_user_roles_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_option_user_roles',
		__('Disable for user role:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_user_roles',
		'ahab_plugin',
		'ahab_plugin_section_user_roles'
	);

	# Keyboard settings
	add_settings_section('ahab_plugin_section_keyboard_shortcut', __(
		'Keyboard shortcut',
		'auto-hide-admin-bar'
	), 'ahab_plugin_section_keyboard_shortcut_text', 'ahab_plugin');
	add_settings_field(
		'ahab_plugin_option_keyboard_shortcut',
		__('Set keyboard shortcut to:', 'auto-hide-admin-bar'),
		'ahab_plugin_setting_keyboard_shortcut',
		'ahab_plugin',
		'ahab_plugin_section_keyboard_shortcut'
	);
}

/**
 * Output section text for speed
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_speed_text() {
?>
	<p> <?php _e('This option allows you to set the animation speed of the hiding/unhiding process. If a non-number is provided, the default value will be used. Provide a number in milliseconds. Default is: 200', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for delay
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_delay_text() {
?>
	<p> <?php _e('This option allows you to set the delay of the hiding process. This makes sure your Toolbar doesn\'t go haywire when moving quickly in the top of your site. If a non-number is provided, the default value will be used. Provide a number in milliseconds. Default is: 1500', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for interval
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_interval_text() {
?>
	<p> <?php _e('The number of milliseconds Auto Hide Admin Bar waits between reading/comparing mouse coordinates. When the user\'s mouse first enters the element its coordinates are recorded. Setting the polling interval higher will increase the delay before the Toolbar gets hidden. If a non-number is provided, the default value will be used. Provide a number in milliseconds. Default is: 100', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for visual
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_visual_text() {
?>
	<p> <?php _e('Use this to set visual options, show an arrow to trigger the showing/hiding of the Toolbar, or add a toggle to temporarily stop the Toolbar from hiding.', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for mobile
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_mobile_text() {
?>
	<p> <?php _e('This option allows you to enable or disable the plugin, when on small screens (< 782px). The
    Default is "Hide the Toolbar". The behaviour of the Toolbar in larger screens will not be affected by this option.', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for user roles
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_user_roles_text() {
?>
	<p> <?php _e('This option allows you to disable the plugin for certain user roles', 'auto-hide-admin-bar'); ?> </p>
<?php
}

/**
 * Output section text for user roles
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_section_keyboard_shortcut_text() {
?>
	<p> <?php _e('Set a keyboard shortcut to hide/show the Toolbar', 'auto-hide-admin-bar'); ?> </p>
<?php
}


/**
 * Output input field for speed
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_speed() {
	$options    = get_option('ahab_plugin_options');
	$ahab_speed = DEFAULT_SPEED;

	if (!empty(($options['speed']))) {
		$ahab_speed = sanitize_text_field($options['speed']);
	}

?>
	<input id='ahab_setting_speed' name='ahab_plugin_options[speed]' type='text' value='<?php echo $ahab_speed; ?>' />
<?php
}

/**
 * Output input field for delay
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_delay() {
	$options    = get_option('ahab_plugin_options');
	$ahab_delay = DEFAULT_DELAY;

	if (!empty($options['delay'])) {
		$ahab_delay = sanitize_text_field($options['delay']);
	}

?>
	<input id='ahab_setting_delay' name='ahab_plugin_options[delay]' type='text' value='<?php echo $ahab_delay; ?>' />
<?php
}

/**
 * Output input field for interval
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_interval() {
	$options       = get_option('ahab_plugin_options');
	$ahab_interval = DEFAULT_INTERVAL;

	if (!empty($options['interval'])) {
		$ahab_interval = sanitize_text_field($options['interval']);
	}
?>
	<input id='ahab_setting_interval' name='ahab_plugin_options[interval]' type='text' value='<?php echo $ahab_interval; ?>' />
<?php
}

/**
 * Arrow options
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_arrow() {
	$options = get_option('ahab_plugin_options');

	if (!empty($options['arrow'])) {
		$options_arrow = $options['arrow'];
	} else {
		$options_arrow = DEFAULT_ARROW;
	}

?>
	<p>
		<input type="radio" id="ahab_setting_arrow" name="ahab_plugin_options[arrow]" value="1" <?php checked(1, $options_arrow, true); ?> />
		<label for="ahab_setting_arrow"><?php _e('No arrow', 'auto-hide-admin-bar'); ?></label>
	</p>
	<p>
		<input type="radio" id="ahab_setting_arrow" name="ahab_plugin_options[arrow]" value="2" <?php checked(2, $options_arrow, true); ?> />
		<label for="ahab_setting_arrow"><?php _e('Show an arrow', 'auto-hide-admin-bar'); ?></label>
	</p>
<?php
}

/**
 * Toggle button options
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_toggle() {
	$options = get_option('ahab_plugin_options');

	if (!empty($options['toggle'])) {
		$options_toggle = $options['toggle'];
	} else {
		$options_toggle = DEFAULT_TOGGLE;
	}

?>
	<p>
		<input type="radio" id="ahab_setting_toggle" name="ahab_plugin_options[toggle]" value="1" <?php checked(1, $options_toggle, true); ?> />
		<label for="ahab_setting_toggle"><?php _e('Hide toggle button for locking the admin bar', 'auto-hide-admin-bar'); ?></label>
	</p>
	<p>
		<input type="radio" id="ahab_setting_toggle" name="ahab_plugin_options[toggle]" value="2" <?php checked(2, $options_toggle, true); ?> />
		<label for="ahab_setting_toggle"><?php _e('Show toggle button for locking the admin bar', 'auto-hide-admin-bar'); ?></label>
	</p>
<?php
}

/**
 * Arrow position options
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_arrow_position() {
	$options = get_option('ahab_plugin_options');

	if (!empty($options['arrow_pos'])) {
		$options_arrow_pos = $options['arrow_pos'];
	} else {
		$options_arrow_pos = DEFAULT_ARROW_POS;
	}

?>
	<p>
		<input type="radio" id="ahab_setting_arrow_pos" name="ahab_plugin_options[arrow_pos]" value="right" <?php checked('right', $options_arrow_pos, true); ?> />
		<label for="ahab_setting_arrow_pos"><?php _e('Right', 'auto-hide-admin-bar'); ?></label>
	</p>
	<p>
		<input type="radio" id="ahab_setting_arrow_pos" name="ahab_plugin_options[arrow_pos]" value="left" <?php checked('left', $options_arrow_pos, true); ?> />
		<label for="ahab_setting_arrow_pos"><?php _e('Left', 'auto-hide-admin-bar'); ?></label>
	</p>

<?php
}

/**
 * Output radio buttons for showing Toolbar on small screens
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_mobile() {
	$options = get_option('ahab_plugin_options');

	if (!empty($options['mobile'])) {
		$options_mobile = $options['mobile'];
	} else {
		$options_mobile = DEFAULT_MOBILE;
	}

?>

	<p>
		<input type="radio" id="ahab_setting_mobile" name="ahab_plugin_options[mobile]" value="1" <?php checked(1, $options_mobile, true); ?> />
		<label for="ahab_setting_mobile"><?php _e('Hide the Toolbar', 'auto-hide-admin-bar'); ?></label>
	</p>
	<p>
		<input type="radio" id="ahab_setting_mobile" name="ahab_plugin_options[mobile]" value="2" <?php checked(2, $options_mobile, true); ?> />
		<label for="ahab_setting_mobile"><?php _e('Always show the Toolbar', 'auto-hide-admin-bar'); ?></label>
	</p>

	<?php
}

/**
 * Output checkboxes for user roles
 *
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_user_roles() {
	$options = get_option('ahab_plugin_options');

	// get all user roles
	global $wp_roles;

	foreach ($wp_roles->roles as $role_key => $role) {
		// disabled user roles are stored as a separate array element
		if (!empty($options['disabled_user_roles_' . $role_key])) {
			$ahab_disabled_user_role = $options['disabled_user_roles_' . $role_key];
		} else {
			$ahab_disabled_user_role = '';
		}

	?>
		<p>
			<input type="checkbox" id="ahab_setting_disable_user_roles_<?php echo $role_key; ?>" name="ahab_plugin_options[disabled_user_roles_<?php echo $role_key; ?>]" value="<?php echo $role_key; ?>" <?php checked($role_key, $ahab_disabled_user_role, true); ?> />
			<label for="ahab_setting_disable_user_roles_<?php echo $role_key; ?>"><?php echo translate_user_role($role['name']); ?></label>
		</p>
		<?php
	}
}

/**
 * Output checkboxes for user roles
 *
 * @author Marcel Bootsman
 *
 * @param none
 *
 * @return none
 */
function ahab_plugin_setting_keyboard_shortcut() {
	$options = get_option('ahab_plugin_options');
	global $keyboard_shortcut_fields;

	foreach ($keyboard_shortcut_fields as $key => $value) {
		if (!empty($options['keyboard_shortcut_' . $key])) {
			if ('' != $options['keyboard_shortcut_' . $key]) {
				$ahab_keyboard_shortcut_key = $options['keyboard_shortcut_' . $key];
			}
		} else {
			$ahab_keyboard_shortcut_key = '';
		}
		if ('char' != $key) {
		?>
			<p>
				<input type="checkbox" id="ahab_setting_keyboard_shortcut_<?php echo $key; ?>" name="ahab_plugin_options[keyboard_shortcut_<?php echo $key; ?>]" value="<?php echo $key; ?>" <?php checked($key, $ahab_keyboard_shortcut_key, true); ?> />
				<label for="ahab_setting_keyboard_shortcut_<?php echo $key; ?>"><?php echo $key; ?></label>
			</p>
	<?php
		}
	}

	// add input field for character
	$ahab_keyboard_shortcut_character = (!empty(sanitize_text_field(esc_attr(substr($options['keyboard_shortcut_char'], 0, 1))))) ? sanitize_text_field(esc_attr(substr($options['keyboard_shortcut_char'], 0, 1))) : '';
	?>
	<p>
		<label for="ahab_setting_keyboard_shortcut_char"><?php _e(' Character', 'auto-hide-admin-bar'); ?></label>
		<input size="4" type="text" maxlength="1" id="ahab_setting_keyboard_shortcut_char" name="ahab_plugin_options[keyboard_shortcut_char]" value="<?php echo $ahab_keyboard_shortcut_character; ?>" />

	</p>
<?php
}

/**
 * Validate the user input
 *
 * @author Marcel Bootsman
 *
 * @param array $input Existing input fields
 *
 * @return array Sanitized input fields
 */
function ahab_validate_input($input) {
	// Create our array for storing the validated options
	$output = array();
	// Loop through each of the incoming options
	foreach ($input as $key => $value) {
		// Check to see if the current option has a value. If so, process it.
		if (isset($input[$key])) {
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags(stripslashes($input[$key]));
		} // end if
	} // end foreach
	// Return the array processing any additional functions filtered by this action
	return apply_filters('ahab_validate_input', $output, $input);
}
?>