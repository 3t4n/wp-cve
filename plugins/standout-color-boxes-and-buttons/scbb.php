<?php
/*
Plugin Name: Standout Color Boxes and Buttons
Plugin URI: http://www.jimmyscode.com/wordpress/standout-color-boxes-buttons/
Description: This plugin implements colored content boxes and buttons as described in a Studiopress blog post as shortcodes.
Version: 0.7.0
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
if (!defined('SCBB_PLUGIN_NAME')) {
	// plugin constants
	define('SCBB_PLUGIN_NAME', 'Standout Color Boxes and Buttons');
	define('SCBB_VERSION', '0.7.0');
	define('SCBB_SLUG', 'standout-color-boxes-and-buttons');
	define('SCBB_LOCAL', 'scbb');
	define('SCBB_OPTION', 'scbb');
	define('SCBB_OPTIONS_NAME', 'scbb_options');
	define('SCBB_PERMISSIONS_LEVEL', 'manage_options');
	define('SCBB_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('SCBB_DEFAULT_ENABLED', true);
	define('SCBB_DEFAULT_COLOR', '');
	define('SCBB_DEFAULT_ROUNDED', false);
	define('SCBB_DEFAULT_SHADOW', false);
	define('SCBB_DEFAULT_URL', '');
	define('SCBB_DEFAULT_CUSTOM_CSS', '');
	define('SCBB_DEFAULT_NOFOLLOW', false);
	define('SCBB_DEFAULT_SHOW', false);
	define('SCBB_DEFAULT_NEWWINDOW', false);
	define('SCBB_DEFAULT_CUSTOMCOLOR', '');
	define('SCBB_AVAILABLE_COLORS', 'blue,gray,green,purple,red,yellow,black,white,orange,pink,bluebell');
	/* option array member names */
	define('SCBB_DEFAULT_ENABLED_NAME', 'enabled');
	define('SCBB_DEFAULT_COLOR_NAME', 'color');
	define('SCBB_DEFAULT_ROUNDED_NAME', 'rounded');
	define('SCBB_DEFAULT_SHADOW_NAME', 'dropshadow');
	define('SCBB_DEFAULT_URL_NAME', 'href');
	define('SCBB_DEFAULT_CUSTOM_CSS_NAME', 'customcss');
	define('SCBB_DEFAULT_NOFOLLOW_NAME', 'nofollow');
	define('SCBB_DEFAULT_SHOW_NAME', 'show');
	define('SCBB_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');
	define('SCBB_DEFAULT_CUSTOMCOLOR_NAME', 'customcolorpicker');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', scbb_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'scbb_translation_file');
	function scbb_translation_file() {
		$plugin_path = scbb_get_path() . '/translations';
		load_plugin_textdomain(scbb_get_local(), '', $plugin_path);
		register_scbb_style();
	}
	// tell WP that we are going to use new options
	add_action('admin_init', 'scbb_options_init');
	function scbb_options_init() {
		register_setting(SCBB_OPTIONS_NAME, scbb_get_option(), 'scbb_validation');
		register_scbb_admin_style();
		register_scbb_admin_script();
		register_scbb_admin_colorpicker_script();
	}
	// validation function
	function scbb_validation($input) {
		if (!empty($input)) {
			// validate all form fields
			$input[SCBB_DEFAULT_ENABLED_NAME] = (bool)$input[SCBB_DEFAULT_ENABLED_NAME];
			$input[SCBB_DEFAULT_COLOR_NAME] = sanitize_text_field($input[SCBB_DEFAULT_COLOR_NAME]);
			$input[SCBB_DEFAULT_ROUNDED_NAME] = (bool)$input[SCBB_DEFAULT_ROUNDED_NAME];
			$input[SCBB_DEFAULT_SHADOW_NAME] = (bool)$input[SCBB_DEFAULT_SHADOW_NAME];
			$input[SCBB_DEFAULT_URL_NAME] = esc_url($input[SCBB_DEFAULT_URL_NAME]);
			$input[SCBB_DEFAULT_CUSTOM_CSS_NAME] = sanitize_text_field($input[SCBB_DEFAULT_CUSTOM_CSS_NAME]);
			$input[SCBB_DEFAULT_NOFOLLOW_NAME] = (bool)$input[SCBB_DEFAULT_NOFOLLOW_NAME];
			$input[SCBB_DEFAULT_NEWWINDOW_NAME] = (bool)$input[SCBB_DEFAULT_NEWWINDOW_NAME];
			$input[SCBB_DEFAULT_CUSTOMCOLOR_NAME] = scbb_filter_hex_color($input[SCBB_DEFAULT_CUSTOMCOLOR_NAME]);
		}
		return $input;
	}
	// add Settings sub-menu
	add_action('admin_menu', 'scbb_plugin_menu');
	function scbb_plugin_menu() {
		add_options_page(SCBB_PLUGIN_NAME, SCBB_PLUGIN_NAME, SCBB_PERMISSIONS_LEVEL, scbb_get_slug(), 'scbb_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	// http://www.onedesigns.com/tutorials/how-to-create-a-wordpress-theme-options-page
	function scbb_page() {
		// check perms
		if (!current_user_can(SCBB_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', scbb_get_local()));
		}
	?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo scbb_getimagefilename('colors.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo SCBB_PLUGIN_NAME; ?> by <a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', scbb_get_local()); ?> <strong><?php echo SCBB_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo scbb_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', scbb_get_local()); ?></a>
				<a href="?page=<?php echo scbb_get_slug(); ?>&tab=parameters" class="nav-tab <?php echo $active_tab == 'parameters' ? 'nav-tab-active' : ''; ?>"><?php _e('Parameters', scbb_get_local()); ?></a>
				<a href="?page=<?php echo scbb_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', scbb_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
			<?php settings_fields(SCBB_OPTIONS_NAME); ?>
			<?php $options = scbb_getpluginoptions(); ?>
			<?php update_option(scbb_get_option(), $options); ?>
			<?php if ($active_tab == 'settings') { ?>
			<h3 id="settings"><img src="<?php echo scbb_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /><?php _e('Plugin Settings', scbb_get_local()); ?></h3>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', scbb_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', scbb_checkifset(SCBB_DEFAULT_ENABLED_NAME, SCBB_DEFAULT_ENABLED, $options)); ?> /></td>
					</tr>
					<?php scbb_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', scbb_get_local())); ?>
					<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the color you would like to use as the default.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_COLOR_NAME; ?>]"><?php _e('Default color', scbb_get_local()); ?></label></strong></th>
						<td><select id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_COLOR_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_COLOR_NAME; ?>]">
									<?php $colors = explode(",", SCBB_AVAILABLE_COLORS);
												foreach($colors as $color) {
					echo '<option value="' . $color . '"' . selected($color, scbb_checkifset(SCBB_DEFAULT_COLOR_NAME, SCBB_DEFAULT_COLOR, $options), false)  . '>' . $color . '</option>';
												} ?>
							</select></td>
					</tr>
					<?php scbb_explanationrow(__('Select the built-in color you would like to use as the default.', scbb_get_local())); ?>
					<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Use a custom color', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOMCOLOR_NAME; ?>]"><?php _e('Select custom color', scbb_get_local()); ?></label></strong></th>
						<td><input type="text" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOMCOLOR_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOMCOLOR_NAME; ?>]" value="<?php echo scbb_checkifset(SCBB_DEFAULT_CUSTOMCOLOR_NAME, SCBB_DEFAULT_CUSTOMCOLOR, $options); ?>" class="scbb-color-picker" /></td>
					</tr>
					<?php scbb_explanationrow(__('Select or enter a custom color to use by default. <strong>This will override the color selected above. Must be a hex color value such as #EEE or #FFCC00</strong>', scbb_get_local())); ?>
					<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to use rounded corner CSS.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ROUNDED_NAME; ?>]"><?php _e('Enabled CSS rounded corners?', scbb_get_local()); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ROUNDED_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', scbb_checkifset(SCBB_DEFAULT_ROUNDED_NAME, SCBB_DEFAULT_ROUNDED, $options)); ?> /></td>
					</tr>
			<?php scbb_explanationrow(__('Check this box to use rounded corner CSS. You can override this at the shortcode level.', scbb_get_local())); ?>
			<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to use drop shadow CSS.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_SHADOW_NAME; ?>]"><?php _e('Enabled CSS drop shadow?', scbb_get_local()); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_SHADOW_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_SHADOW_NAME; ?>]" value="1" <?php checked('1', scbb_checkifset(SCBB_DEFAULT_SHADOW_NAME, SCBB_DEFAULT_SHADOW, $options)); ?> /></td>
					</tr>
			<?php scbb_explanationrow(__('Check this box to use drop shadow CSS. You can override this at the shortcode level.', scbb_get_local())); ?>
			<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter default URL to use for color buttons.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_URL_NAME; ?>]"><?php _e('Default button URL', scbb_get_local()); ?></label></strong></th>
			<td><input type="text" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_URL_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_URL_NAME; ?>]" value="<?php echo scbb_checkifset(SCBB_DEFAULT_URL_NAME, SCBB_DEFAULT_URL, $options); ?>" /></td>
					</tr>
			<?php scbb_explanationrow(__('Enter default URL to use for color buttons. This URL will be used if you do not override it at the shortcode level.', scbb_get_local())); ?>
			<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to add rel=nofollow to button links.', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow button link?', scbb_get_local()); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NOFOLLOW_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', scbb_checkifset(SCBB_DEFAULT_NOFOLLOW_NAME, SCBB_DEFAULT_NOFOLLOW, $options)); ?> /></td>
					</tr>
			<?php scbb_explanationrow(__('Check this box to add rel="nofollow" to button links only. You can override this at the shortcode level.', scbb_get_local())); ?>
			<?php scbb_getlinebreak(); ?>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to open links in a new window (color buttons only).', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', scbb_get_local()); ?></label></strong></th>
			<td><input type="checkbox" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NEWWINDOW_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', scbb_checkifset(SCBB_DEFAULT_NEWWINDOW_NAME, SCBB_DEFAULT_NEWWINDOW, $options)); ?> /></td>
					</tr>
			<?php scbb_explanationrow(__('Check this box to open links in a new window (color buttons only). You can override this at the shortcode level.', scbb_get_local())); ?>
			<?php scbb_getlinebreak(); ?>
				<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter custom CSS', scbb_get_local()); ?>" for="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOM_CSS_NAME; ?>]"><?php _e('Enter custom CSS', scbb_get_local()); ?></label></strong></th>
			<td><textarea rows="12" cols="75" id="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOM_CSS_NAME; ?>]" name="<?php echo scbb_get_option(); ?>[<?php echo SCBB_DEFAULT_CUSTOM_CSS_NAME; ?>]"><?php echo scbb_checkifset(SCBB_DEFAULT_CUSTOM_CSS_NAME, SCBB_DEFAULT_CUSTOM_CSS, $options); ?></textarea></td>
			</tr>
			<?php scbb_explanationrow(__('If you use your own custom class names, enter the CSS here. Include the "scbb-button" or "scbb-content-box" prefix in your CSS. In the shortcode or PHP, use the custom class name but DO NOT include the "scbb-button" or "scbb-content-box" prefix.', scbb_get_local())); ?>
			<?php scbb_explanationrow(__('Example: If the custom CSS is <strong>.scbb-content-box-mycustomcolor { background-color:#FF9900 }</strong> then the shortcode is <br /><strong>[color-box color="mycustomcolor"]my text here[/color-box]</strong>', scbb_get_local())); ?>
			</table>
				<?php submit_button(); ?>
			<?php } elseif ($active_tab == 'parameters') { ?>
			<h3 id="parameters"><img src="<?php echo scbb_getimagefilename('parameters.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Plugin Parameters and Default Values', scbb_get_local()); ?></h3>
			These are the parameters for using the shortcode, or calling the plugin from your PHP code.
			
			For available colors, see the dropdown list above.

			<h4>Color Boxes</h4>
			<?php echo scbb_parameters_table(scbb_get_local(), scbb_colorbox_shortcode_defaults(), scbb_box_required_parameters()); ?>
			
			<h4>Color Buttons</h4>
			<?php echo scbb_parameters_table(scbb_get_local(), scbb_colorbutton_shortcode_defaults(), scbb_button_required_parameters()); ?>
			
			<h3 id="examples"><img src="<?php echo scbb_getimagefilename('examples.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Shortcode and PHP Examples', scbb_get_local()); ?></h3>
			<h4><?php _e('Shortcode Format (Color Boxes):', scbb_get_local()); ?></h4>
			<?php echo '<pre style="background:#FFF">' . scbb_get_example_shortcode('color-box', scbb_colorbox_shortcode_defaults(), scbb_get_local()) . 'Content goes here[/color-box]</pre>'; ?>
			<h4><?php _e('Shortcode Format (Color Buttons):', scbb_get_local()); ?></h4>
			<?php echo '<pre style="background:#FFF">' . scbb_get_example_shortcode('color-button', scbb_colorbutton_shortcode_defaults(), scbb_get_local()) . 'Content goes here[/color-button]</pre>'; ?>
			
			<h4><?php _e('PHP Format (Color Boxes):', scbb_get_local()); ?></h4>
			<?php echo scbb_get_example_php_code('color-box', 'color_box_shortcode', scbb_colorbox_shortcode_defaults()); ?>
			<h4><?php _e('PHP Format (Color Buttons):', scbb_get_local()); ?></h4>
			<?php echo scbb_get_example_php_code('color-button', 'color_button_shortcode', scbb_colorbutton_shortcode_defaults()); ?>			
			<?php _e('<small>Note: \'show\' is false by default; set it to <strong>true</strong> echo the output, or <strong>false</strong> to return the output to your PHP code.</small>', scbb_get_local()); ?>
			<?php } else { ?>
			<h3 id="support"><img src="<?php echo scbb_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', scbb_get_local()); ?></h3>
			<div class="support">
				<?php echo scbb_getsupportinfo(scbb_get_slug(), scbb_get_local()); ?>
			</div>
			<?php } ?>
			</form>
		</div>
		<?php }
	// shortcodes for boxes and buttons
	add_shortcode('color-box', 'color_box_shortcode');
	add_shortcode('color-button', 'color_button_shortcode');
	// -------------------------------------------------------------------
	// shortcode output
	// -------------------------------------------------------------------
	function color_box_shortcode($atts, $content = null) {
		// get parameters
		extract(shortcode_atts(scbb_colorbox_shortcode_defaults(), $atts));
		// plugin is enabled/disabled from settings page only
		$options = scbb_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[SCBB_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = SCBB_DEFAULT_ENABLED;
		}
		$output = '';
		
		// ******************************
		// derive shortcode values from constants
		// ******************************
		if ($enabled) {
			$temp_color = constant('SCBB_DEFAULT_COLOR_NAME');
			$boxcolor = $$temp_color;
			$temp_rounded = constant('SCBB_DEFAULT_ROUNDED_NAME');
			$rounded = $$temp_rounded;
			$temp_shadow = constant('SCBB_DEFAULT_SHADOW_NAME');
			$dropshadow = $$temp_shadow;
			$temp_show = constant('SCBB_DEFAULT_SHOW_NAME');
			$show = $$temp_show;
			$temp_customcolor = constant('SCBB_DEFAULT_CUSTOMCOLOR_NAME');
			$customcolor = $$temp_customcolor;
		}
		// ******************************
		// sanitize user input
		// ******************************
		if ($enabled) {
			$boxcolor = sanitize_html_class($boxcolor);
			$rounded = (bool)$rounded;
			$dropshadow = (bool)$dropshadow;
			$show = (bool)$show;
			$customcolor = scbb_filter_hex_color($customcolor);
		}
		// ******************************
		// check for parameters, then settings, then defaults
		// ******************************
		if ($enabled) {
			if ($content === null) {
				// what is the point of a color box w/ no content?
				$enabled = false;
				$output = '<!-- ' . SCBB_PLUGIN_NAME . ': ';
				$output .= __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', scbb_get_local());
				$output .= ' -->';
			} else {
				// plugin enabled and there is content
				// check for overriden parameters, if nonexistent then get from DB
				if (!$customcolor) {
					$customcolor = $options[SCBB_DEFAULT_CUSTOMCOLOR_NAME];
					if ($customcolor == false) {
						$customcolor = SCBB_DEFAULT_CUSTOMCOLOR;
					}
				}
				$rounded = scbb_setupvar($rounded, SCBB_DEFAULT_ROUNDED, SCBB_DEFAULT_ROUNDED_NAME, $options);
				$dropshadow = scbb_setupvar($dropshadow, SCBB_DEFAULT_SHADOW, SCBB_DEFAULT_SHADOW_NAME, $options);

				// check if color value was passed
				if (($boxcolor == false) || ($boxcolor == SCBB_DEFAULT_COLOR)) {
					// not passed by shortcode, use default
					$boxcolor = $options[SCBB_DEFAULT_COLOR_NAME];
				} else { // a value was passed, check if it is one of the available colors. if not, assume a custom css name and load custom.css
					$colors = explode(",", SCBB_AVAILABLE_COLORS);
					if (!in_array($boxcolor, $colors)) {
						// write CSS to custom.css file
						$myFile = dirname(__FILE__) . '/css/custom.css';
						$fh = @fopen($myFile, 'w+');
						@fwrite($fh, $options[SCBB_DEFAULT_CUSTOM_CSS_NAME]);
						@fclose($fh);
						// enqueue custom css file
						scbb_custom_styles();
					}
				} // end color

				// enqueue CSS on pages w/ shortcode/function
				color_box_styles();

				$output = '<div';
				$output .= ' class="scbb-content-box';

				if ($rounded) {
					$output .= ' scbb-rounded-corners';
					if ($dropshadow) {
						$output .= ' scbb-dropshadow';
					}
				} elseif ($dropshadow) {
					$output .= ' scbb-dropshadow';
				}
				if (!$customcolor) {
					$output .= ' scbb-content-box-' . $boxcolor;
				}
				$output .= '"';
				
				if ($customcolor) { // color picker was used, use color picker name
					$output .= ' style="background-color: ' . $customcolor . '"';
				}
				$output .= '>';
				$output .= do_shortcode(wp_kses_post(force_balance_tags($content)));
				$output .=  '</div>';
				
			} // end content check
		} else { // not enabled
			$output = '<!-- ' . SCBB_PLUGIN_NAME . ': ';
			$output .= __('is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', scbb_get_local());
			$output .= ' -->';
		} // end enabled check

		if ($show) {
			echo $output;
		} else {
			return $output;
		}
	} // end color box shortcode function

	function color_button_shortcode($atts, $content = null) {
		// get parameters
		extract(shortcode_atts(scbb_colorbutton_shortcode_defaults(), $atts));
		// plugin is enabled/disabled from settings page only
		$options = scbb_getpluginoptions();
		$enabled = (bool)$options[SCBB_DEFAULT_ENABLED_NAME];

		$output = '';
		
		// ******************************
		// derive shortcode values from constants
		// ******************************
		if ($enabled) {
			$temp_color = constant('SCBB_DEFAULT_COLOR_NAME');
			$buttoncolor = $$temp_color;
			$temp_rounded = constant('SCBB_DEFAULT_ROUNDED_NAME');
			$rounded = $$temp_rounded;
			$temp_shadow = constant('SCBB_DEFAULT_SHADOW_NAME');
			$dropshadow = $$temp_shadow;
			$temp_show = constant('SCBB_DEFAULT_SHOW_NAME');
			$show = $$temp_show;
			$temp_url = constant('SCBB_DEFAULT_URL_NAME');
			$linkurl = $$temp_url;
			$temp_window = constant('SCBB_DEFAULT_NEWWINDOW_NAME');
			$opennewwindow = $$temp_window;
			$temp_nofollow = constant('SCBB_DEFAULT_NOFOLLOW_NAME');
			$nofollow = $$temp_nofollow;
			$temp_customcolor = constant('SCBB_DEFAULT_CUSTOMCOLOR_NAME');
			$customcolor = $$temp_customcolor;
		}
		// ******************************
		// sanitize user input
		// ******************************
		if ($enabled) {
			$linkurl = esc_url($linkurl);
			$buttoncolor = sanitize_text_field($buttoncolor);
			$rounded = (bool)$rounded;
			$dropshadow = (bool)$dropshadow;
			$nofollow = (bool)$nofollow;
			$opennewwindow = (bool)$opennewwindow;
			$show = (bool)$show;
			$customcolor = scbb_filter_hex_color($customcolor);
			
			// allow alternate parameter names for url
			if (!empty($atts['url'])) {
				$linkurl = esc_url($atts['url']);
			} elseif (!empty($atts['link'])) {
				$linkurl = esc_url($atts['link']);
			}
		}
		// ******************************
		// check for parameters, then settings, then defaults
		// ******************************
		if ($enabled) {
			if ($content === null) {
				// what is the point of a color button w/ no content?
				$enabled = false;
				$output = '<!-- ' . SCBB_PLUGIN_NAME . ': ' . __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', scbb_get_local()) . ' -->';
			} elseif ($linkurl === SCBB_DEFAULT_URL) { // no url passed to function, try settings page
					$linkurl = $options[SCBB_DEFAULT_URL_NAME];
					if (($linkurl === SCBB_DEFAULT_URL) || ($linkurl === false)) { // no url on settings page either
						$enabled = false;
						$output = '<!-- ' . SCBB_PLUGIN_NAME . ': ';
						$output .= __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', scbb_get_local());
						$output .= ' -->';
					}
			} else {
				// plugin is enabled and there is content
				// check for overriden parameters, if nonexistent then get from DB
				if (!$customcolor) {
					$customcolor = $options[SCBB_DEFAULT_CUSTOMCOLOR_NAME];
					if ($customcolor == false) {
						$customcolor = SCBB_DEFAULT_CUSTOMCOLOR;
					}
				}
				$rounded = scbb_setupvar($rounded, SCBB_DEFAULT_ROUNDED, SCBB_DEFAULT_ROUNDED_NAME, $options);
				$dropshadow = scbb_setupvar($dropshadow, SCBB_DEFAULT_SHADOW, SCBB_DEFAULT_SHADOW_NAME, $options);
				$nofollow = scbb_setupvar($nofollow, SCBB_DEFAULT_NOFOLLOW, SCBB_DEFAULT_NOFOLLOW_NAME, $options);
				$opennewwindow = scbb_setupvar($opennewwindow, SCBB_DEFAULT_NEWWINDOW, SCBB_DEFAULT_NEWWINDOW_NAME, $options);

				// check if color value was passed
				if (($buttoncolor == false) || ($buttoncolor == SCBB_DEFAULT_COLOR)) {
					// not passed by shortcode, use default
					$buttoncolor = $options[SCBB_DEFAULT_COLOR_NAME];
				} else { // a value was passed, check if it is one of the available colors. if not, assume a custom css name and load custom.css
					$colors = explode(",", SCBB_AVAILABLE_COLORS);
					if (!in_array($buttoncolor, $colors)) {
						// write CSS to custom.css file
						$myFile = dirname(__FILE__) . '/css/custom.css';
						$fh = @fopen($myFile, 'w+');
						@fwrite($fh, $options[SCBB_DEFAULT_CUSTOM_CSS_NAME]);
						@fclose($fh);
						// enqueue custom css file
						scbb_custom_styles();
					}
				} // end color

				// enqueue CSS on pages w/ shortcode/function
				color_box_styles();
				
				$output = '<a';
				$output .= ' class="scbb-button';

				if ($rounded) {
					$output .= ' scbb-rounded-corners';
					if ($dropshadow) {
						$output .= ' scbb-dropshadow';
					}
				} elseif ($dropshadow) {
					$output .= ' scbb-dropshadow';
				}
				if (!$customcolor) {
					$output .= ' scbb-button-' . $buttoncolor;
				}
				$output .= '"';

				if ($customcolor) { // color picker was used, use color picker name
					$output .= ' style="background-color: ' . $customcolor . '"';
				}
				$output .= ($opennewwindow ? ' onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;" ' : '');
				$output .= ($nofollow ? ' rel="nofollow"' : '');
				$output .= ' href="' . $linkurl . '"';
				$output .= '>';
				$output .= do_shortcode(wp_kses_post(force_balance_tags($content)));
				$output .=  '</a>';
				
			} // end content check
		} else { // not enabled
			$output = '<!-- ' . SCBB_PLUGIN_NAME . ': ';
			$output .= __('plugin is disabled. Either you did not pass a necessary setting to the plugin, or did not configure a default. Check Settings page.', scbb_get_local());
			$output .= ' -->';
		} // end enabled check
		if ($show) {
			echo $output;
		} else {
			return $output;
		}
	} // end color button shortcode function
	// show admin messages to plugin user
	add_action('admin_notices', 'scbb_showAdminMessages');
	function scbb_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(SCBB_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if (isset($_GET['page'])) {
					if ($_GET['page'] == scbb_get_slug()) { // we are on this plugin's settings page
						$options = scbb_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[SCBB_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . SCBB_PLUGIN_NAME . ' ' . __('is currently disabled.', scbb_get_local()) . '</div>';
							}
						} 
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function

	// enqueue admin CSS if we are on the plugin options page
	// also load built-in color picker
	add_action('admin_head', 'insert_scbb_admin_css');
	function insert_scbb_admin_css() {
		global $pagenow;
		if (current_user_can(SCBB_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if (isset($_GET['page'])) {
					if ($_GET['page'] == scbb_get_slug()) { // we are on this plugin's settings page
						scbb_admin_styles();
						// http://www.wpinsite.com/code-snippets/add-new-wordpress-color-picker-plugin-themes
						wp_enqueue_script('scbb_add_colorpicker');
						scbb_colorpicker_load();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'scbb_plugin_settings_link');
	add_filter('plugin_row_meta', 'scbb_meta_links', 10, 2);
	
	function scbb_plugin_settings_link($links) {
		return scbb_settingslink($links, scbb_get_slug(), scbb_get_local());
	}
	function scbb_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', scbb_get_local()), scbb_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', scbb_get_local()), scbb_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', scbb_get_local()), scbb_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the plugin CSS file
	function color_box_styles() {
		wp_enqueue_style('scbb_style');
	}
	function register_scbb_style() {
		wp_register_style('scbb_style', 
			plugins_url(scbb_get_path() . '/css/scbb.css'), 
			array(), 
			SCBB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/scbb.css')), 
			'all' );
	}
	// enqueue/register the admin CSS file
	function scbb_admin_styles() {
		wp_enqueue_style('scbb_admin_style');
	}
	function register_scbb_admin_style() {
		wp_register_style('scbb_admin_style',
			plugins_url(scbb_get_path() . '/css/admin.css'),
			array(),
			SCBB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// enqueue/register the custom CSS file
	function scbb_custom_styles() {
		wp_register_style('scbb_custom_style',
			plugins_url(scbb_get_path() . '/css/custom.css'),
		array(),
		SCBB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/custom.css')),
		'all');
		wp_enqueue_style('scbb_custom_style');
	}
	// enqueue/register the admin JS file
	add_action('admin_enqueue_scripts', 'scbb_ed_buttons');
	function scbb_ed_buttons($hook) {
		if (($hook == 'post-new.php') || ($hook == 'post.php')) {
			wp_enqueue_script('scbb_add_editor_button');
		}
	}
	function register_scbb_admin_script() {
		wp_register_script('scbb_add_editor_button',
			plugins_url(scbb_get_path() . '/js/editor_button.js'), 
			array('quicktags'), 
			SCBB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/editor_button.js')), 
			true);
	}
	function scbb_colorpicker_load() {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}
	function register_scbb_admin_colorpicker_script() {
		wp_register_script('scbb_add_colorpicker',
			plugins_url(scbb_get_path() . '/js/scbb-color-picker.js'), 
			array('jquery'), 
			SCBB_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/scbb-color-picker.js')), 
			true);
	}

	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'scbb_activate');
	function scbb_activate() {
		$options = scbb_getpluginoptions();
		update_option(scbb_get_option(), $options);

		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_scbb_plugin');
	}
	function uninstall_scbb_plugin() {
		delete_option(scbb_get_option());
	}

	// function to validate hex color values
	function scbb_filter_hex_color($colorvalue) {
		if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $colorvalue)) {
			return $colorvalue;
		}
	}
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function scbb_getpluginoptions() {
		return get_option(scbb_get_option(), array(
		SCBB_DEFAULT_NOFOLLOW_NAME => SCBB_DEFAULT_NOFOLLOW, 
		SCBB_DEFAULT_URL_NAME => SCBB_DEFAULT_URL, 
		SCBB_DEFAULT_SHADOW_NAME => SCBB_DEFAULT_SHADOW, 
		SCBB_DEFAULT_ROUNDED_NAME => SCBB_DEFAULT_ROUNDED, 
		SCBB_DEFAULT_COLOR_NAME => SCBB_DEFAULT_COLOR, 
		SCBB_DEFAULT_CUSTOMCOLOR_NAME => SCBB_DEFAULT_CUSTOMCOLOR, 
		SCBB_DEFAULT_ENABLED_NAME => SCBB_DEFAULT_ENABLED, 
		SCBB_DEFAULT_NEWWINDOW_NAME => SCBB_DEFAULT_NEWWINDOW, 
		SCBB_DEFAULT_CUSTOM_CSS_NAME => SCBB_DEFAULT_CUSTOM_CSS
		));
	}
	// function to return shortcode defaults for color boxes
	function scbb_colorbox_shortcode_defaults() {
		return array(
			SCBB_DEFAULT_COLOR_NAME => SCBB_DEFAULT_COLOR, 
			SCBB_DEFAULT_CUSTOMCOLOR_NAME => SCBB_DEFAULT_CUSTOMCOLOR, 
			SCBB_DEFAULT_ROUNDED_NAME => SCBB_DEFAULT_ROUNDED, 
			SCBB_DEFAULT_SHADOW_NAME => SCBB_DEFAULT_SHADOW, 
			SCBB_DEFAULT_SHOW_NAME => SCBB_DEFAULT_SHOW
			);
	}
	// function to return parameter status for color boxes (required or not)
	function scbb_box_required_parameters() {
		return array(
			false, 
			false, 
			false,
			false, 
			false
		);
	}
	// function to return shortcode defaults for color buttons
	function scbb_colorbutton_shortcode_defaults() {
		return array(
				SCBB_DEFAULT_URL_NAME => SCBB_DEFAULT_URL, 
				SCBB_DEFAULT_COLOR_NAME => SCBB_DEFAULT_COLOR, 
				SCBB_DEFAULT_CUSTOMCOLOR_NAME => SCBB_DEFAULT_CUSTOMCOLOR, 
				SCBB_DEFAULT_ROUNDED_NAME => SCBB_DEFAULT_ROUNDED, 
				SCBB_DEFAULT_SHADOW_NAME => SCBB_DEFAULT_SHADOW, 
				SCBB_DEFAULT_NOFOLLOW_NAME => SCBB_DEFAULT_NOFOLLOW, 
				SCBB_DEFAULT_NEWWINDOW_NAME => SCBB_DEFAULT_NEWWINDOW, 
				SCBB_DEFAULT_SHOW_NAME => SCBB_DEFAULT_SHOW
				);
	}
	// function to return parameter status for color buttons (required or not)
	function scbb_button_required_parameters() {
		return array(
			true, 
			false, 
			false, 
			false, 
			false, 
			false, 
			false, 
			false, 
		);
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function scbb_get_slug() { return SCBB_SLUG; }
	function scbb_get_local() { return SCBB_LOCAL; }
	function scbb_get_option() { return SCBB_OPTION; }
	function scbb_get_path() { return SCBB_PATH; }
	
	function scbb_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function scbb_setupvar($var, $defaultvalue, $defaultvarname, $optionsarr) {
		if ($var == $defaultvalue) {
			$var = $optionsarr[$defaultvarname];
			if (!$var) {
				$var = $defaultvalue;
			}
		}
		return $var;
	}
	function scbb_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	
	function scbb_parameters_table($localname = '', $sc_defaults, $reqparms) {
	  $output = '<table class="widefat">';
		$output .= '<thead><tr>';
		$output .= '<th title="' . __('The name of the parameter', $localname) . '"><strong>' . __('Parameter Name', $localname) . '</strong></th>';
		$output .= '<th title="' . __('Is this parameter required?', $localname) . '"><strong>' . __('Is Required?', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What data type this parameter accepts', $localname) . '"><strong>' . __('Data Type', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What, if any, is the default if no value is specified', $localname) . '"><strong>' . __('Default Value', $localname) . '</strong></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody>';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		$required = $reqparms;
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '<tr>';
			$output .= '<td><strong>' . $plugin_defaults_keys[$i] . '</strong></td>';
			$output .= '<td>';
			
			if ($required[$i] === true) {
				$output .= '<strong>';
				$output .= __('Yes', $localname);
				$output .= '</strong>';
			} else {
				$output .= __('No', $localname);
			}
			
			$output .= '</td>';
			$output .= '<td>' . gettype($plugin_defaults_values[$i]) . '</td>';
			$output .= '<td>';
			
			if ($plugin_defaults_values[$i] === true) {
				$output .= '<strong>';
				$output .= __('true', $localname);
				$output .= '</strong>';
			} elseif ($plugin_defaults_values[$i] === false) {
				$output .= __('false', $localname);
			} elseif ($plugin_defaults_values[$i] === '') {
				$output .= '<em>';
				$output .= __('this value is blank by default', $localname);
				$output .= '</em>';
			} elseif (is_numeric($plugin_defaults_values[$i])) {
				$output .= $plugin_defaults_values[$i];
			} else { 
				$output .= '"' . $plugin_defaults_values[$i] . '"';
			} 
			$output .= '</td>';
			$output .= '</tr>';
		}
		$output .= '</tbody>';
		$output .= '</table>';
		
		return $output;
	}
	function scbb_get_example_shortcode($shortcodename = '', $sc_defaults, $localname = '') {
		$output = '[' . $shortcodename . ' ';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			if ($plugin_defaults_keys[$i] !== 'show') {
				if (gettype($plugin_defaults_values[$i]) === 'string') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=\'' . $plugin_defaults_values[$i] . '\'';
				} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . ($plugin_defaults_values[$i] == false ? 'false' : 'true');
				} else {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . $plugin_defaults_values[$i];
				}
				if ($i < count($plugin_defaults_keys) - 2) {
					$output .= ' ';
				}
			}
		}
		$output .= ']';
		
		return $output;
	}
	
	function scbb_get_example_php_code($shortcodename = '', $internalfunctionname = '', $sc_defaults) {
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		$output = '<pre style="background:#FFF">';
		$output .= 'if (shortcode_exists(\'' . $shortcodename . '\')) {<br />';
		$output .= '  $atts = array(<br />';
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '    \'' . $plugin_defaults_keys[$i] . '\' => ';
			if (gettype($plugin_defaults_values[$i]) === 'string') {
				$output .= '\'' . $plugin_defaults_values[$i] . '\'';
			} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
				$output .= ($plugin_defaults_values[$i] == false ? 'false' : 'true');
			} else {
				$output .= $plugin_defaults_values[$i];
			}
			if ($i < count($plugin_defaults_keys) - 1) {
				$output .= ', <br />';
			}
		}
		$output .= '<br />  );<br />';
		$output .= '   echo ' . $internalfunctionname . '($atts);';
		$output .= '<br />}';
		$output .= '</pre>';
		return $output;	
	}
	function scbb_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function scbb_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function scbb_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function scbb_getimagefilename($fname = '') {
		return plugins_url(scbb_get_path() . '/images/' . $fname);
	}
?>