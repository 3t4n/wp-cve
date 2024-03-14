<?php

/**
 * Plugin Name:       Easy Font Resize
 * Plugin URI:        https://www.upwork.com/freelancers/~0145929f1d88e4c19e
 * Description:       Allow your visitors to increase or decrease font size of the "main" section of your website.
 * Version:           1.0.15
 * Stable tag:        1.0.15
 * Author:            Alex M.
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpave-font-resizer
 */

if (!defined('WPINC')) {
	die;
}

define('WPAVE_FONT_RESIZER_VERSION', '1.0.15');
define('WPAVE_FONT_RESIZER_ELEMENTS', [
	'h1' => false,
	'h2' => false,
	'h3' => false,
	'h4' => true,
	'h5' => true,
	'h6' => true,
	'p' => true,
	'a' => true,
	'span' => true,
	'ul' => true,
	'ol' => true,
	'li' => true,
	'blockquote' => true,
	'caption' => false,
	'div' => false,
	'table' => false,
	'colgroup' => false,
	'col' => false,
	'thead' => false,
	'tbody' => false,
	'tfoot' => false,
	'tr' => false,
	'td' => false,
	'th' => false,
	'abbr' => false,
	'button' => false,
	'code' => false
]);

add_action('wp_enqueue_scripts', function () {
	if (!wp_style_is('wpavefrsz-style')) {
		wp_enqueue_style('wpavefrsz-style', plugins_url('style.css', __FILE__), [], WPAVE_FONT_RESIZER_VERSION);
	}
	
	if (!wp_script_is('wpavefrsz-script')) {
		wp_enqueue_script('wpavefrsz-script', plugins_url('script.js', __FILE__), ['jquery'], WPAVE_FONT_RESIZER_VERSION, true);
	}
	
	$elements = get_option('wpavefrsz_elements_array');
	if (!empty($elements)) {
		$elements = array_keys($elements);
	} else {
		$elements = [];
	}
	
	wp_localize_script('wpavefrsz-script', 'wpavefrsz', [
		'elements' => $elements,
		'main_selector' => esc_js(get_option('wpavefrsz_main_selector')),
		'min_modifier' => esc_js(get_option('wpavefrsz_min_modifier')),
		'max_modifier' => esc_js(get_option('wpavefrsz_max_modifier')),
		'step_modifier' => esc_js(get_option('wpavefrsz_step_modifier')),
		'remember_font_size_sitewide' => get_option('wpavefrsz_remember_font_size_sitewide') == '1',
		'wpavefrsz_remember_font_size_enforce' => get_option('wpavefrsz_remember_font_size_enforce') == '1',
		'include_selectors' => esc_js(get_option('wpavefrsz_include_selectors')),
		'exclude_selectors' => esc_js(get_option('wpavefrsz_exclude_selectors'))
	]);
});

add_action('wp_footer', function () {
	$position = get_option('wpavefrsz_position');
	
	if ($position != 'hide') {
		echo wpavefrsz_output_resizer($position);
	}
});

function wpavefrsz_output_resizer($position = '') {
	global $post;
	
	$render = apply_filters('wpavefrsz_render_flag', true, $post);
	
	if ($render === false) {
		return '';
	}
	
	if (wp_is_mobile()) {
		if (get_option('wpavefrsz_show_on_mobile') != '1') {
			return '';
		}
	}
	
	$text = get_option('wpavefrsz_hide_text') == '1' ? '' : __(esc_attr(get_option('wpavefrsz_text')));
	
	$text = apply_filters('wpavefrsz_filter_text', $text);
	
	$instructions_icon_text = '';
	if (get_option('wpavefrsz_hide_text') != '1') {
		$instructions_icon_id = get_option('wpavefrsz_instructions_icon');
		
		if (!empty($instructions_icon_id)) {
			$instructions_icon_text .= '<img src="' . esc_url(wp_get_attachment_image_url($instructions_icon_id)) . '">';
		}
	}
	
	$use_icons = get_option('wpavefrsz_use_wp_icons') == '1';
	
	$offset_style = '';
	
	if ($position == 'fixed-top-left' || $position == 'fixed-top-right' || $position == 'fixed-bottom-left' || $position == 'fixed-bottom-right') {
		if (wp_is_mobile()) {
			$offset = esc_attr(get_option('wpavefrsz_main_offset_mobile'));
		} else {
			$offset = esc_attr(get_option('wpavefrsz_main_offset'));
		}
		
		if ($position == 'fixed-top-left') {
			$offset_style = 'left: ' . $offset . 'px;top:' . $offset . 'px;';
		}
		if ($position == 'fixed-top-right') {
			$offset_style = 'right: ' . $offset . 'px;top:' . $offset . 'px;';
		}
		if ($position == 'fixed-bottom-left') {
			$offset_style = 'left: ' . $offset . 'px;bottom:' . $offset . 'px;';
		}
		if ($position == 'fixed-bottom-right') {
			$offset_style = 'right: ' . $offset . 'px;bottom:' . $offset . 'px;';
		}
	}
	
	$theme = esc_attr(get_option('wpavefrsz_theme'));
	
	$notranslate_class = get_option('wpavefrsz_add_notranslate_class') == '1' ? ' notranslate' : '';
	
	$minus = '-';
	$minus = apply_filters('wpavefrsz_filter_minus', $minus);
	
	$plus = '+';
	$plus = apply_filters('wpavefrsz_filter_plus', $plus);
	
	$equals = '=';
	$equals = apply_filters('wpavefrsz_filter_equals', $equals);
	
	$html = '';
	
	$html .= '<div style="' . $offset_style . '" class="wpavefrsz wpavefrsz-' . $position . ' wpavefrsz-theme-' . $theme . '">';
	
	$html .= '<span class="wpavefrsz-text" aria-label="' . esc_attr($text) . '">' . wp_kses_post($text) . '</span>';
	
	if (!empty($instructions_icon_text)) {
		$html .= '<span class="wpavefrsz-text-icon">' . wp_kses_post($instructions_icon_text) . '</span>';
	}
	
	if ($use_icons) {
		if (!wp_style_is('dashicons')) {
			wp_enqueue_style('dashicons');
		}
		
		$minus = '';
		$plus = '';
		$equals = '';
	}
	
	$html .= '<span class="wpavefrsz-minus' . esc_attr($notranslate_class) . ($use_icons === true ? ' dashicons dashicons-minus' : '') . '" tabindex="0" aria-label="' . esc_attr(get_option('wpavefrsz_text_decrease')) . '" title="' . esc_attr(get_option('wpavefrsz_text_decrease')) . '" role="button">' . wp_kses_post($minus) . '</span>';
	
	$html .= '<span class="wpavefrsz-plus' . esc_attr($notranslate_class) . ($use_icons === true ? ' dashicons dashicons-plus' : '') . '" tabindex="0" aria-label="' . esc_attr(get_option('wpavefrsz_text_increase')) . '" title="' . esc_attr(get_option('wpavefrsz_text_increase')) . '" role="button">' . wp_kses_post($plus) . '</span>';
	
	$html .= '<span class="wpavefrsz-reset' . esc_attr($notranslate_class) . ($use_icons === true ? ' dashicons dashicons-image-rotate' : '') . '" tabindex="0" aria-label="' . esc_attr(get_option('wpavefrsz_text_reset')) . '" title="' . esc_attr(get_option('wpavefrsz_text_reset')) . '" role="button">' . wp_kses_post($equals) . '</span>';
	
	$html .= '</div>'; // .wpavefrsz
	
	return $html;
}

add_action('admin_init', function () {
	add_option('wpavefrsz_position', 'fixed-bottom-right');
	add_option('wpavefrsz_hide_text', false);
	add_option('wpavefrsz_use_wp_icons', false);
	add_option('wpavefrsz_text', 'Resize text');
	add_option('wpavefrsz_instructions_icon', '');
	add_option('wpavefrsz_text_decrease', 'Decrease text size');
	add_option('wpavefrsz_text_increase', 'Increase text size');
	add_option('wpavefrsz_text_reset', 'Reset text size');
	add_option('wpavefrsz_include_selectors', '');
	add_option('wpavefrsz_exclude_selectors', '');
	add_option('wpavefrsz_min_modifier', 0.7);
	add_option('wpavefrsz_max_modifier', 1.3);
	add_option('wpavefrsz_step_modifier', 0.1);
	add_option('wpavefrsz_show_on_mobile', false);
	add_option('wpavefrsz_remember_font_size_sitewide', true);
	add_option('wpavefrsz_remember_font_size_enforce', false);
	add_option('wpavefrsz_add_notranslate_class', false);
	add_option('wpavefrsz_elements_array', WPAVE_FONT_RESIZER_ELEMENTS);
	add_option('wpavefrsz_main_selector', 'main');
	add_option('wpavefrsz_main_offset', 30);
	add_option('wpavefrsz_main_offset_mobile', 15);
	add_option('wpavefrsz_theme', 'dark');
	
	register_setting('wpavefrsz_options_group', 'wpavefrsz_position');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_hide_text');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_use_wp_icons');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_text');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_instructions_icon');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_text_decrease');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_text_increase');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_text_reset');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_include_selectors');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_exclude_selectors');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_min_modifier');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_max_modifier');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_step_modifier');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_show_on_mobile');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_remember_font_size_sitewide');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_remember_font_size_enforce');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_add_notranslate_class');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_elements_array');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_main_selector');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_main_offset');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_main_offset_mobile');
	register_setting('wpavefrsz_options_group', 'wpavefrsz_theme');
});

add_action('admin_menu', function () {
	add_submenu_page('options-general.php', 'Settings', 'Easy Font Resize', 'administrator', 'options-wpavefrsz-settings', 'wpavefrsz_settings_page');
});

function wpavefrsz_settings_page() { ?>
	<h2>Settings</h2>
	<hr>
	<br>
	<div>
		Use shortcode <code>[wpavefrsz-resizer]</code>
	</div>
	<form action="options.php" method="post">
		<table class="form-table">
			<?php
			settings_fields('wpavefrsz_options_group');
			do_settings_sections('wpavefrsz_options_group');
			
			$position = get_option('wpavefrsz_position');
			$theme = get_option('wpavefrsz_theme');
			$elements = get_option('wpavefrsz_elements_array');
			?>
			<tr valign="top">
				<th scope="row">Position<br>
					<small><i>When not using shortcode</i></small>
				</th>
				<td>
					<select id="wpavefrsz_position" name='wpavefrsz_position'>
						<option value='hide' <?php selected($position, 'hide'); ?>>Don't display</option>
						<option value='fixed-top-left' <?php selected($position, 'fixed-top-left'); ?>>Fixed top left</option>
						<option value='fixed-top-right' <?php selected($position, 'fixed-top-right'); ?>>Fixed top right</option>
						<option value='fixed-bottom-left' <?php selected($position, 'fixed-bottom-left'); ?>>Fixed bottom left</option>
						<option value='fixed-bottom-right' <?php selected($position, 'fixed-bottom-right'); ?>>Fixed bottom right</option>
						<option value='floating-left' <?php selected($position, 'floating-left'); ?>>Floating left (desktop)</option>
						<option value='floating-right' <?php selected($position, 'floating-right'); ?>>Floating right (desktop)</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Show only controls?<br>
					<small><i>Without the "Resize text" instructions text - only buttons</i></small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_hide_text" value="1"<?php checked(1 == get_option('wpavefrsz_hide_text')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Use native WordPress dashicons for buttons?<br>
					<small>Icons look like this
						<span class="dashicons dashicons-minus"></span><span class="dashicons dashicons-plus"></span><span class="dashicons dashicons-image-rotate"></span>
					</small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_use_wp_icons" value="1"<?php checked(1 == get_option('wpavefrsz_use_wp_icons')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Show on mobile?<br>
					<small><i>Mobile users can zoom in easily so the widget becomes useless</i></small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_show_on_mobile" value="1"<?php checked(1 == get_option('wpavefrsz_show_on_mobile')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Remember font size site-wide?<br>
					<small><i>Use localStorage to set font size for users only once across whole website</i></small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_remember_font_size_sitewide" value="1"<?php checked(1 == get_option('wpavefrsz_remember_font_size_sitewide')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Force font sizes by adding "!important" CSS selector?<br>
					<small><i>This can help overriding themes/plugins inline CSS styles</i></small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_remember_font_size_enforce" value="1"<?php checked(1 == get_option('wpavefrsz_remember_font_size_enforce')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Add "notranslate" class to resizer buttons?<br>
					<small><i>This will prevent Google Translator widget from interacting with resizer buttons</i>
					</small>
				</th>
				<td>
					<input type="checkbox" name="wpavefrsz_add_notranslate_class" value="1"<?php checked(1 == get_option('wpavefrsz_add_notranslate_class')); ?> />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Theme</th>
				<td>
					<select id="wpavefrsz_theme" name="wpavefrsz_theme">
						<option value='dark' <?php selected($theme, 'dark'); ?>>Dark</option>
						<option value='light' <?php selected($theme, 'light'); ?>>Light</option>
						<option value='grey' <?php selected($theme, 'grey'); ?>>Grey</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">jQuery selector that will select children elements<br>
					<small><i>Defaults to "main" but this can depend on your theme. (eg. #content or .content)</i>
					</small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_main_selector" value="<?php echo esc_attr(get_option('wpavefrsz_main_selector')); ?>"/>
					<small>If resizing is not working - try changing this. If this field is empty resizing will work for the whole page.</small>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Instructions for users</th>
				<small><i>Will appear next to the resizing buttons</i></small>
				<td>
					<input type="text" name="wpavefrsz_text" value="<?php echo esc_attr(get_option('wpavefrsz_text')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Instructions for users (icon)</th>
				<small><i>Will appear next to the resizing buttons</i></small>
				<td>
					<a href="#" id="wpavefrsz_instructions_icon">Add Icon</a>
					<br><br>
					<?php
					$instructions_icon_id = get_option('wpavefrsz_instructions_icon');
					
					if (!empty($instructions_icon_id)) {
						echo '<img style="width: 100px;" src="' . esc_attr(esc_url(wp_get_attachment_image_url($instructions_icon_id))) . '" id="wpavefrsz_instructions_selected_icon">';
					} else {
						echo '<img style="display:none; width: 100px;" src="#" id="wpavefrsz_instructions_selected_icon">';
					}
					?>
					<br>
					<a href="#" id="wpavefrsz_instructions_icon_remove">Remove Icon</a>

					<input type="hidden" name="wpavefrsz_instructions_icon" value="<?php echo get_option('wpavefrsz_instructions_icon'); ?>">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Min/Max/Step modifiers<br>
					<small><i>Text minimum, maximum and step sizes</i></small>
				</th>
				<td>
					<input type="number" step="0.1" min="0.1" name="wpavefrsz_min_modifier" value="<?php echo esc_attr(get_option('wpavefrsz_min_modifier')); ?>"/>
					<input type="number" step="0.1" min="0.1" name="wpavefrsz_max_modifier" value="<?php echo esc_attr(get_option('wpavefrsz_max_modifier')); ?>"/>
					<input type="number" step="0.1" min="0.1" name="wpavefrsz_step_modifier" value="<?php echo esc_attr(get_option('wpavefrsz_step_modifier')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Edge distance<br>
					<small><i>From the edge of the screen/viewport</i></small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_main_offset" value="<?php echo esc_attr(get_option('wpavefrsz_main_offset')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Edge distance (mobile)<br>
					<small><i>From the edge of the screen/viewport</i></small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_main_offset_mobile" value="<?php echo esc_attr(get_option('wpavefrsz_main_offset_mobile')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Decrease font size text title<br>
					<small><i>Will not be visible, for accessibility only (when tabbing through elements)</i></small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_text_decrease" value="<?php echo esc_attr(get_option('wpavefrsz_text_decrease')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Increase font size text title<br>
					<small><i>Will not be visible, for accessibility only (when tabbing through elements)</i></small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_text_increase" value="<?php echo esc_attr(get_option('wpavefrsz_text_increase')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Reset font size text title<br>
					<small><i>Will not be visible, for accessibility only (when tabbing through elements)</i></small>
				</th>
				<td>
					<input type="text" name="wpavefrsz_text_reset" value="<?php echo esc_attr(get_option('wpavefrsz_text_reset')); ?>"/>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Include following CSS/jQuery selector(s)<br>
					<small>
						<i><b style="color: red;">THIS CAN BREAK YOUR WEBSITE!</b><br>If more than one - separate by a comma: ".my_class, #aSelector_ID"<br>Elements that match those selectors
							<b>will be forcefully resized</b>.</i>
					</small>
				</th>
				<td>
					<textarea name="wpavefrsz_include_selectors" rows="8" cols="50"><?php echo esc_attr(get_option('wpavefrsz_include_selectors')); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Exclude following CSS/jQuery selector(s)<br>
					<small>
						<i><b style="color: red;">THIS CAN BREAK YOUR WEBSITE!</b><br>If more than one - separate by a comma: ".my_class, #aSelector_ID"<br>Elements that match those selectors
							<b>will not be resized</b>.</i>
					</small>
				</th>
				<td>
					<textarea name="wpavefrsz_exclude_selectors" rows="8" cols="50"><?php echo esc_attr(get_option('wpavefrsz_exclude_selectors')); ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">Resize following elements</th>
				<td>
					<?php
					if (!empty($elements)) {
						foreach (WPAVE_FONT_RESIZER_ELEMENTS as $key => $element) {
							?>
							<input type="checkbox" class="wpavefrsz_elements_array" name="wpavefrsz_elements_array[<?php echo esc_attr($key); ?>]" data-key="<?php echo esc_attr($key); ?>" value="1" <?php @checked($elements[$key] == true); ?> />
							<?php
							echo esc_attr($key) . '<br>';
						}
					} else {
						foreach (WPAVE_FONT_RESIZER_ELEMENTS as $key => $element) {
							?>
							<input type="checkbox" class="wpavefrsz_elements_array" name="wpavefrsz_elements_array[<?php echo esc_attr($key); ?>]" data-key="<?php echo esc_attr($key); ?>" value="1"/>
							<?php
							echo esc_attr($key) . '<br>';
						}
					} ?>
					<br>
					<button type="button" class="wpavefrsz_elements_reset" onclick="wpavefrszResetElements()">Reset to default</button>
				</td>
			</tr>
			<tr valign="top" class="wpavefrsz_wpave_promotion">
				<th scope="row">Try our new FREE Advanced Visual Elements plugin!</th>
				<td>
					<a href="https://wordpress.org/plugins/advanced-visual-elements/" target="_blank">
						<img src="https://ps.w.org/advanced-visual-elements/assets/banner-772x250.png?rev=2901464" alt="Advanced Visual Elements">
					</a>
					<br>
					<br>
					<p>
						<b>Advanced Visual Elements</b> is a collection of the <b>most popular</b> visual elements<br>
						from around the web that you <b>can't find in standard builders</b>.<br>
						Customize and implement visuals to <b>save time, money and spice up your website's looks!</b>
					</p>
					<br>
					<p>You can see the <b>demos</b> and <b>what it can do</b> by clicking on
						<a href="https://wp-ave.com/" target="_blank">this link</a>.
					</p>
					<br>
					<p>
						<a href="https://wordpress.org/plugins/advanced-visual-elements/" target="_blank">Download it now</a> from the
						<b>official WordPress plugin repository</b> - it's free!
					</p>
				</td>
			</tr>
			<tr valign="top" class="wpavefrsz_wpave_buymeacoffee">
				<th scope="row">...Or just buy me a coffee to support this plugin! :)</th>
				<td>
					<img style="float: left; margin-right: 15px; width: 140px;" src="<?php echo plugins_url('/easy-font-resize/images/bmc_qr.png'); ?>" alt="Your support is much appreciated!">
					<script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="wpave" data-color="#FFDD00" data-emoji="" data-font="Cookie" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff"></script>
				</td>
			</tr>
		</table>
		<?php
		submit_button('Save settings');
		?>
	</form>
	<script>
		function wpavefrszResetElements() {
			let originalElements = <?php echo json_encode(WPAVE_FONT_RESIZER_ELEMENTS); ?>;
			let elements = document.getElementsByClassName('wpavefrsz_elements_array');

			if (elements.length > 0) {
				for (let i = 0; i < elements.length; i++) {
					let att = elements[i].getAttribute('data-key');

					elements[i].checked = originalElements[att];
				}
			}
		}
	</script>
	<hr>
	<div>
		<h4>Do you need help with WordPress development? You can hire me by sending an e-mail to
			<a href="mailto:aleksandarziher@gmail.com">aleksandarziher@gmail.com</a> or by filling in
			<a href="https://forms.gle/3TZmcCXru6HTZrQu8" target="_blank">this form</a>.</h4>
	</div>
<?php }

add_action('init', function () {
	add_shortcode('wpavefrsz-resizer', function ($atts) {
		return wpavefrsz_output_resizer('shortcode');
	});
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
	$links[] = '<a href="' .
		admin_url('options-general.php?page=options-wpavefrsz-settings') .
		'">' . __('Settings') . '</a>';
	
	return $links;
});

add_action('admin_notices', function () {
	global $current_user;
	
	$user_id = $current_user->ID;
	
	if (!get_user_meta($user_id, 'wpavefrsz-wpave-ignore-notice')) {
		?>
		<div class="notice notice-info">
			<p>
				<b>Try our new FREE
					<a target="_blank" href="https://wordpress.org/plugins/advanced-visual-elements/">Advanced Visual Elements plugin</a>! It's a shortcode addon plugin that can create some amazing visual elements for your website!</b>
				<a style="float: right;" href="?wpavefrsz-wpave-ignore-notice">Dismiss</a>
			</p>
		</div>
		<?php
	}
});

add_action('admin_init', function () {
	global $current_user;
	
	$user_id = $current_user->ID;
	
	if (isset($_GET['wpavefrsz-wpave-ignore-notice'])) {
		add_user_meta($user_id, 'wpavefrsz-wpave-ignore-notice', 'true', true);
	}
});

add_action('admin_enqueue_scripts', function () {
	$screen = get_current_screen();
	
	if ($screen) {
		wp_enqueue_media();
		
		if ($screen->id === 'settings_page_options-wpavefrsz-settings') {
			if (!wp_script_is('wpavefrsz-admin-script')) {
				wp_enqueue_script('wpavefrsz-admin-script', plugin_dir_url(__FILE__) . 'admin-script.js', ['jquery'], false);
			}
		}
	}
});

add_action('elementor/widgets/widgets_registered', function () {
	class WPAVE_Font_Resizer_Elementor_Shortcode extends \Elementor\Widget_Base {
		
		public function get_name() {
			return 'wpavefrsz-shortcode-widget';
		}
		
		public function get_title() {
			return __('Font resizer', 'wp-ave');
		}
		
		public function get_icon() {
			return 'eicon-zoom-in';
		}
		
		public function get_categories() {
			return ['general'];
		}
		
		protected function render() {
			echo do_shortcode('[wpavefrsz-resizer]');
		}
	}
	
	\Elementor\Plugin::instance()->widgets_manager->register(new WPAVE_Font_Resizer_Elementor_Shortcode());
});