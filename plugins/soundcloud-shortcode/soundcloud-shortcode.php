<?php
/**
 * Plugin Name: SoundCloud Shortcode
 * Plugin URI: http://wordpress.org/extend/plugins/soundcloud-shortcode/
 * Description: Converts SoundCloud WordPress shortcodes to a SoundCloud widget.
 * Version: 4.0.2
 * Author: SoundCloud Inc., Lawrie Malen
 * Author URI: http://soundcloud.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://wordpress.org/plugins/soundcloud-shortcode/
 * Text Domain: soundcloud-shortcode
 * Requires PHP: 5.6
 * Requires at least: 3.1.0
 * Domain Path: /languages
 *
 * @package soundcloud-shortcode
 *
 * Original version: Johannes Wagener <johannes@soundcloud.com>
 * Options support: Tiffany Conroy <tiffany@soundcloud.com>
 * HTML5 & oEmbed support: Tim Bormans <tim@soundcloud.com>
 * PHP8 compatibility, refactoring, sanitization & modernisation: Lawrie Malen <soundcloud@indextwo.net>
 *
 * SoundCloud Shortcode is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * SoundCloud Shortcode is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SoundCloud Shortcode. If not, see https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
	exit;	//	Exit if accessed directly.
}

/**
 * Register oEmbed provider
 */

wp_oembed_add_provider('#https?://(?:api\.)?soundcloud\.com/.*#i', 'http://soundcloud.com/oembed', true);

/**
 * Register SoundCloud shortcode
 */

add_shortcode('soundcloud', 'soundcloud_shortcode');

/**
 *
 * SoundCloud shortcode handler
 * @param	{string|array}	$atts	The attributes passed to the shortcode like [soundcloud attr1="value" /].
 *									Is an empty string when no arguments are given.
 * @param	{string}				$content	The content between non-self closing [soundcloud]…[/soundcloud] tags.
 * @return {string}					Widget embed code HTML.
 */

function soundcloud_shortcode($atts, $content = null) {
	//	Custom shortcode options
	
	$shortcode_options = array_merge(array('url' => trim($content)), is_array($atts) ? $atts : array());
	
	//	Turn shortcode option "param" (param=value&param2=value) into array
	
	$shortcode_params = array();
	
	if (isset($shortcode_options['params'])) {
		parse_str(html_entity_decode($shortcode_options['params']), $shortcode_params);
	}
	
	$shortcode_options['params'] = $shortcode_params;

	//	`$player_type` is actually the visual style. `true` means it will show the full artwork behind the player

	$player_type = soundcloud_get_option('player_type');
	$is_visual = ($player_type === 'visual');

	//	User preference options
	
	$fields_array = soundcloud_return_fields();
	$params_array = array();

	foreach ($fields_array as $key=>$arr) {
		$params_array[$key] = soundcloud_get_option($key);
	}

	$params_array['visual'] = ($is_visual ? 'true' : 'false');

	$plugin_options = array_filter(
		array(
			'width'		=> soundcloud_get_option('player_width'),
			'height'	=> soundcloud_url_has_tracklist($shortcode_options['url']) ? soundcloud_get_option('player_height_multi') : soundcloud_get_option('player_height'),
			'params'	=> $params_array,
		)
	);

	// Needs to be an array
	
	if (!isset($plugin_options['params'])) {
		$plugin_options['params'] = array();
	}

	//	Plugin options < shortcode options
	
	$options = array_merge(
		$plugin_options,
		$shortcode_options
	);

	//	Plugin params < shortcode params
	
	$options['params'] = array_merge(
		$plugin_options['params'],
		$shortcode_options['params']
	);

	//	The `url` option is required, and it must be from soundcloud.com
	
	if (!isset($options['url'])) {
		return '';
	} else if (!soundcloud_check_domain($options['url'])) {
		return '';
	} else {
		$options['url'] = trim($options['url']);
	}

	//	Remove visual parameter from widget when it's false because that's the default
	
	if ($options['params']['visual'] && (!soundcloud_booleanize($options['params']['visual']))) {
		unset($options['params']['visual']);
	}

	// Merge in "url" value
	
	$options['params'] = array_merge(
		array(
			'url' => $options['url'],
		),
		$options['params']
	);

	//	Apply a filter to the options

	$options = apply_filters('soundcloud_shortcode_options', $options);

	//	Now let's clean EVERYTHING

	$param_sanitization_array = soundcloud_safe_shortcode_params();

	//	This *modifies* the passed array rather than returning it

	soundcloud_sanitize_array($options, $param_sanitization_array);

	//	Now let's sanitize them all AGAIN

	foreach ($param_sanitization_array as $key=>$type) {
		$value = $options[$key];

		if (is_array($type)) {
			$child_array = $type;

			foreach ($child_array as $param_key=>$param_type) {
				$param_value = $options[$key][$param_key];

				$sanitize_function = 'soundcloud_sanitize_' . $param_type;
				$param_value = call_user_func($sanitize_function, $param_value);

				$options[$key][$param_key] = esc_attr($param_value);
			}
		} else {
			$sanitize_function = 'soundcloud_sanitize_' . $type;
			$value = call_user_func($sanitize_function, $value);
			
			$options[$key] = esc_attr($value);
		}		
	}

	// Return iframe embed code
	
	return soundcloud_iframe_widget($options);
}

/**
 * Plugin options getter
 * @param	{string|array}	$option	 Option name
 * @param	{mixed}			$default	Default value
 * @return {mixed}			Option value
 */

function soundcloud_get_option($option, $default = false) {
	$valid_options_array = soundcloud_return_fields();

	$value = get_option('soundcloud_' . $option);

	if (isset($valid_options_array[$option])) {
		$sanitize_function = 'soundcloud_sanitize_' . $valid_options_array[$option]['type'];
		$value = call_user_func($sanitize_function, $value);
	} else {
		//	All else fails, blank it
		
		$value = '';
	}

	return $value === '' ? $default : $value;
}

/**
 * Sanitize a number
 * @param {mixed} $data Original value (either from plugin options or shortcode)
 * @return {mixed} Sanitized value
 */

function soundcloud_sanitize_number($data) {
	//	Return only numbers; allow floats and percentages
	
	$data = preg_replace('/[^0-9.%]/', '', $data);

	//	...but! Only allow floats IF it's a percentage!

	if ($data != '') {
		if (strripos($data, '%') === false) {
			$data = intval($data);
		}
	}

	return $data;
}

/**
 * Sanitize a 'type'
 * @param {mixed} $data Original value (either from plugin options or shortcode)
 * @return {mixed} Sanitized value
 */

function soundcloud_sanitize_type($data) {
	//	`html5` isn't a real option, but we're keeping it for backward compatibility

	if ($data != 'visual' && $data != 'html5') {
		$data = 'visual';
	}

	return $data;
}

/**
 * Sanitize a boolean
 * @param {mixed} $data Original value (either from plugin options or shortcode)
 * @return {mixed} Sanitized value
 */

function soundcloud_sanitize_bool($data) {
	if ($data != 'true' && $data != 'false') {
		$data = '';
	}

	return $data;
}

/**
 * Sanitize a hex value
 * @param {mixed} $data Original value (either from plugin options or shortcode)
 * @return {mixed} Sanitized value
 */

function soundcloud_sanitize_hex($data) {
	//	Force hex sanitization on the submitted string & removes the hash. It *is* valid in SoundCloud's options, but not necessary

	preg_match('/([a-f0-9]{6}|[a-f0-9]{3})$/i', $data, $output_array);

	$data = $output_array[1];

	return $data;
}

/**
 * Sanitize a URL to ensure it only allows soundcloud.com
 * @param {mixed} $data Original value (either from plugin options or shortcode)
 * @return {mixed} Sanitized value
 */

function soundcloud_sanitize_url($data) {
	$is_soundcloud = soundcloud_check_domain($data);

	if ($is_soundcloud) {
		return $data;
	}

	return '';
}

/**
 * Return an array of fields and field types for sanitization
 */

function soundcloud_return_fields() {
	return array(
		'player_height'			=> array('type' => 'number'),
		'player_height_multi'	=> array('type' => 'number'),
		'player_width'			=> array('type' => 'number'),
		'player_type'			=> array('type' => 'type'),
		
		'color'					=> array('type' => 'hex'),	//	Hexidecimal color code for button and waveform
		
		'auto_play'				=> array('type' => 'bool', 'title' => 'Auto-play', 'desc' => 'auto-play track. Note that auto-play may not work depending on the browser and security settings.'),	//	Auto-play the track
		'show_comments'			=> array('type' => 'bool', 'title' => 'Show comments', 'desc' => 'Show/hide user comments along the waveform'),	//	Show user comments along waveform
		'show_user'				=> array('type' => 'bool', 'title' => 'Show username', 'desc' => 'Show/hide user that created the track'),	//	Show the user that created the track
		'buying'			 	=> array('type' => 'bool', 'title' => 'Show buy button', 'desc' => 'Show/hide buy button, if track purchase is available'),	//	Show buy button
		'sharing' 				=> array('type' => 'bool', 'title' => 'Show share &amp; like', 'desc' => 'Show/hide the share & like button (note: this may depend on SoundCloud settings)'),	//	Show the share & like button
		'download' 				=> array('type' => 'bool', 'title' => 'Show download', 'desc' => 'Show/hide download button, if downloads enabled'),	//	Show download button
		'show_artwork'			=> array('type' => 'bool', 'title' => 'Show artwork', 'desc' => 'Show/hide the artwork (only if \'Visual style\' is set to \'Standard\')'),	//	Shows the artwork (only when visual = false)
		'show_playcount'		=> array('type' => 'bool', 'title' => 'Show play count', 'desc' => 'Show/hide play count for track'),	//	Shows the play count for the track
		'hide_related'			=> array('type' => 'bool', 'title' => 'Hide related tracks', 'desc' => 'Hide/show related/next-up tracks when audio is finished'),	//	Hides related / next-up tracks
	);
}

/**
 * Return an array of 'safe' parameters used by the shortcode: we can discard any keys that don't match, and use the value to sanitize the attribute
 */

function soundcloud_safe_shortcode_params() {
	return array(
		'width'		=> 'number',
		'height'	=> 'number',
		'url'		=> 'url',
		'params'	=> array(
			'url'		=> 'url',
			'player_height'			=> 'number',
			'player_height_multi'	=> 'number',
			'player_width'			=> 'number',
			'player_type'			=> 'type',
			'color'					=> 'hex',
			'auto_play'				=> 'bool',
			'show_comments'			=> 'bool',
			'show_user'				=> 'bool',
			'buying'				=> 'bool',
			'sharing'				=> 'bool',
			'download'				=> 'bool',
			'show_artwork'			=> 'bool',
			'show_playcount'		=> 'bool',
			'hide_related'			=> 'bool',
		),
	);
}

/**
 * Sanitize the passed shortcode params to make sure they only match what's in the 'safe params' list
 * @param {array} $options The array of shortcode options to modify
 * @param {array} $check_array The array of allowed shortcode keys to check against
 */

function soundcloud_sanitize_array(&$options, $check_array) {
	foreach ($options as $key => &$value) {
		if (is_array($value)) {
			if (isset($check_array[$key])) {
				soundcloud_sanitize_array($value, $check_array[$key]);
			} else {
				unset($options[$key]);
			}
		} elseif (!isset($key, $check_array[$key])) {
			unset($options[$key]);
		}
	}
}

/**
 * Fetch the saved parameters
 */

function soundcloud_return_saved_parameters() {
	$params_array = soundcloud_return_fields();
	$fetched_params_array = array();

	foreach ($params_array as $key=>$arr) {
		if ($arr['type'] == 'bool' || $arr['type'] == 'hex') {
			$fetched_params_array[$key] = soundcloud_get_option($key);
		}
	}

	$fetched_params_array['visual'] = (soundcloud_get_option('player_type') == 'visual') ? 'true' : 'false';	//	This is very annoying

	$params = http_build_query(array_filter($fetched_params_array));

	return $params;
}

/**
 * Make sure any passed URL is actually from soundcloud
 * @param {mixed} $url URL passed to the shortcode
 * @return {bool} Whether it's a valid soundcloud.com URL
 */

function soundcloud_check_domain($url) {
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	
	$actual_domain = '';

	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		$actual_domain = strtolower($regs['domain']);
	}

	if ($actual_domain == 'soundcloud.com') {
		return true;
	}

	return false;
}

/**
 * Enqueue plugin CSS
 * @param {string}	$hook The name of the page this function is called on
 */

function soundcloud_admin_css($hook) {
	global $post;
	
	$script_path = plugin_dir_path(__FILE__) . '/assets/';
	$script_uri = plugins_url('/assets/', __FILE__);
	
	if ($hook == 'settings_page_soundcloud-shortcode') {
		wp_enqueue_style('soundcloud-admin', $script_uri . '/soundcloud-admin.css', array(), filemtime($script_path . '/soundcloud-admin.css'));
	}
}

add_action('admin_enqueue_scripts', 'soundcloud_admin_css', 20, 1);

/**
 * Booleanize a value
 * @param	{boolean|string}	$value The intended value for a string boolean ('true'|'false')
 * @return {boolean} Actual boolean
 */

function soundcloud_booleanize($value) {
	return (is_bool($value) ? $value : $value === 'true') ? true : false;
}

/**
 * Decide if a URL has a tracklist
 * @param	{string}	 $url SoundCloud URL
 * @return {boolean} Whether the passed URL is for a playlist
 */

function soundcloud_url_has_tracklist($url) {
	return preg_match('/^(.+?)\/(sets|groups|playlists)\/(.+?)$/', $url);
}

/**
 * Parameterize URL
 * @param	{array}	$match	Matched regex
 * @return {string}	Parameterized URL
 */

function soundcloud_oembed_params_callback($match) {
	global $soundcloud_oembed_params;

	//	Convert URL to array
	
	$url = parse_url(urldecode($match[1]));

	//	Convert URL query to array
	
	parse_str($url['query'], $query_array);

	//	Build new query string

	$query = http_build_query(array_merge($query_array, $soundcloud_oembed_params));

	return 'src="' . $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . $query;
}

/**
 * Widget iframe embed code
 * @param	{array}	$options	Parameters
 * @return {string}	The iframe embed code
 */

function soundcloud_iframe_widget($options) {
	//	Build URL
	
	$url = 'https://w.soundcloud.com/player?' . http_build_query($options['params']);
	
	//	Set default width if not defined
	
	$width = isset($options['width']) && $options['width'] !== 0 ? $options['width'] : '100%';
	
	//	Set default height if not defined

	$height = isset($options['height']) && $options['height'] !== 0
		? $options['height']
		: (soundcloud_url_has_tracklist($options['url']) || (isset($options['params']['visual']) && soundcloud_booleanize($options['params']['visual'])) ? '450' : '166');

	//	Set `autoplay="true"` for the actual iframe if the `auto_play` option is true

	$autoplay = (isset($options['params']) && isset($options['params']['auto_play']) && $options['params']['auto_play'] == 'true') ? ' allow="autoplay"' : '';

	return sprintf('<iframe width="%s" height="%s" scrolling="no" frameborder="no" src="%s"%s></iframe>', $width, $height, $url, $autoplay);
}

/**
 * Add settings link on Plugins menu
 * @param {array} $links Array of current WP settings links
 * @return {array} Array of WP settings links
 */

function soundcloud_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=soundcloud-shortcode">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'soundcloud_settings_link');

/**
 * Add admin menu
 */
	
function soundcloud_shortcode_options_menu() {
	add_options_page('SoundCloud Options', 'SoundCloud', 'manage_options', 'soundcloud-shortcode', 'soundcloud_shortcode_options');
	add_action('admin_init', 'soundcloud_register_settings');
}

add_action('admin_menu', 'soundcloud_shortcode_options_menu');

/**
 * Register settings
 */

function soundcloud_register_settings() {
	foreach (soundcloud_return_fields() as $key=>$arr) {
		register_setting('soundcloud-settings', 'soundcloud_' . $key, array('sanitize_callback' => 'soundcloud_sanitize_' . $arr['type']));
	}
}

/**
 * Settings Page
 */

function soundcloud_shortcode_options() {
	if (!current_user_can('manage_options')) {
		wp_die(esc_html(__('You do not have sufficient permissions to access this page.')));
	}

	$number_helper = 'Enter either a number in pixels, e.g. <code>166</code>, or a percentage; e.g. <code>50%</code>. Leave blank to use the default SoundCloud option.';

	?>

	<div class="wrap soundcloud-admin-wrapper">
		<h2 class="soundcloud-title">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50" height="50" viewBox="0 0 50 50" preserveAspectRatio="xMinYMin meet">
				<path fill="#FF5500" d="M24.6 14.55 Q24.35 14.55 24.2 14.8 L24 15.35 23.6 24.75 24 30.8 24.2 31.35 Q24.35 31.55 24.6 31.55 L25.05 31.35 25.25 30.9 25.6 24.75 25.25 15.25 25.1 14.85 25.05 14.8 Q24.85 14.55 24.6 14.55 M50 25 Q50 35.35 42.65 42.65 35.35 50 25 50 14.6 50 7.3 42.65 0 35.35 0 25 0 14.6 7.3 7.3 14.6 0 25 0 35.35 0 42.65 7.3 50 14.6 50 25 M45.35 22.6 Q43.8 21.05 41.6 21.05 L39.55 21.45 Q39.4 19.7 38.6 18.15 37.85 16.6 36.6 15.45 L34.85 14.15 33.75 13.65 Q32.1 13 30.3 13 28.55 13 26.95 13.6 L26.55 13.85 Q26.4 14 26.4 14.25 L26.4 30.95 26.6 31.4 Q26.75 31.55 27 31.55 L41.6 31.55 Q43.8 31.55 45.35 30.05 46.85 28.5 46.85 26.3 46.85 24.15 45.35 22.6 M13.9 18.3 L13.85 18.25 13.4 18.05 Q13.15 18.05 13 18.25 L12.8 18.7 12.4 26.15 12.8 31 13 31.4 13.4 31.55 13.85 31.4 14.05 31.05 14.05 31 14.4 26.15 14.05 18.6 13.9 18.3 M18.6 16.75 L18.4 17.25 18 25.55 18.4 30.9 18.6 31.35 Q18.75 31.55 19 31.55 L19.45 31.35 19.65 30.95 19.65 30.9 20 25.55 19.65 17.15 19.5 16.8 19.45 16.75 19 16.55 Q18.75 16.55 18.6 16.75 M16.65 16.5 L16.2 16.35 Q15.95 16.35 15.75 16.5 L15.75 16.55 Q15.55 16.7 15.55 17 L15.2 26.45 15.55 30.95 15.75 31.4 16.2 31.55 16.65 31.4 16.9 30.95 16.9 30.9 17.2 26.45 16.9 17 16.7 16.55 16.65 16.5 M7.35 20.75 L7.25 20.95 7.2 21.05 6.8 26.1 6.8 26.15 7.2 31.05 7.25 31.1 7.35 31.3 7.4 31.45 7.75 31.55 8.1 31.5 8.25 31.3 8.3 31.05 8.3 31 8.8 26.15 8.8 26.1 8.3 21.05 8.2 20.75 7.75 20.55 7.35 20.75 M10.05 21.7 L10 21.8 9.6 26.5 9.6 26.55 10 31.1 10.05 31.15 10.15 31.35 10.2 31.45 10.55 31.55 10.9 31.5 11.05 31.35 11.1 31.1 11.1 31.05 11.6 26.55 11.6 26.5 11.1 21.8 11 21.5 10.55 21.3 10.15 21.5 10.05 21.7 M4.95 22.1 Q4.75 22.1 4.65 22.25 L4.45 22.45 4 25.65 4 25.7 4.45 28.85 4.65 29.05 4.95 29.1 5.3 29.05 5.45 28.85 5.45 28.8 6 25.7 6 25.65 5.45 22.45 5.3 22.25 Q5.15 22.1 4.95 22.1 M22.4 18.1 L22.3 17.8 22.25 17.75 21.75 17.55 Q21.55 17.55 21.4 17.75 21.15 17.9 21.15 18.2 L20.8 25.95 21.15 30.95 Q21.15 31.25 21.4 31.35 21.55 31.55 21.75 31.55 L22.25 31.35 22.4 31 22.4 30.95 22.8 25.95 22.4 18.1"/>
			</svg>

			<span>
				SoundCloud Shortcode Default Settings
			</span>
		</h2>

		<p>
			These settings will become the new defaults used by the SoundCloud shortcode throughout your site.
		</p>

		<p>
			You can always override these settings on a per-shortcode basis. Setting the <code>params</code> attribute within in a shortcode overrides these defaults for that instance.
		</p>

		<p>
			You can see an example of how these <code>params</code> are rendered at the bottom of the page.
		</p>

		<form method="post" action="options.php">

			<?php settings_fields('soundcloud-settings'); ?>

			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<span>Visual style</span>
					</th>
					<td>
						<?php
							//	Confusing, but: for the sake of legacy support, the 'non-visual' option is staying as 'html5'
							
							$visual_checked = '';
							$html5_checked = '';

							if (!soundcloud_get_option('player_type') || strtolower(soundcloud_get_option('player_type')) === 'visual')	{
								$visual_checked = 'checked';
							}

							if (strtolower(soundcloud_get_option('player_type')) === 'html5') {
								$html5_checked = 'checked';
							}
						?>
						<div>
							<input type="radio" id="player_type_visual" name="soundcloud_player_type" value="visual" <?php echo esc_attr($visual_checked); ?> />
							<label for="player_type_visual" class="radio-label">
								Visual (show artwork)
							</label>
						</div>
						
						<div>
							<input type="radio" id="player_type_html5" name="soundcloud_player_type" value="html5" <?php echo esc_attr($html5_checked); ?> />
							<label for="player_type_html5" class="radio-label">
								Standard (no artwork)
							</label>
						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<span>Player height for tracks</span>
					</th>

					<td>
						<input type="text" name="soundcloud_player_height" value="<?php echo esc_attr(soundcloud_get_option('player_height')); ?>" />
						<p class="description">
							<?php echo esc_html($number_helper); ?>
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<span>Player height for groups/sets</span>
					</th>

					<td>
						<input type="text" name="soundcloud_player_height_multi" value="<?php echo esc_attr(soundcloud_get_option('player_height_multi')); ?>" />
						<p class="description">
							<?php echo esc_html($number_helper); ?>
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<span>Player width</span>
					</th>

					<td>
						<input type="text" name="soundcloud_player_width" value="<?php echo esc_attr(soundcloud_get_option('player_width')); ?>" />
						<p class="description">
							<?php echo esc_html($number_helper); ?>
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<span>Color</span>
					</th>

					<td>
						<?php $color = soundcloud_get_option('color'); ?>

						<div class="hex-wrapper">
							<span class="hex">
								#
							</span>
							<input type="text" name="soundcloud_color" value="<?php echo esc_attr($color); ?>" />

							<span class="desc">
								(Hexidecimal color code <strong>without the #</strong> e.g. <code>FF5500</code>)
							</span>
						</div>

						<p class="description">
							Defines the color to paint the play button, waveform and selections.
						</p>

						<div class="sc-preview">
							<svg width="200" height="43" viewBox="0 0 200 43" xmlns="http://www.w3.org/2000/svg">
								<circle fill="#<?php echo esc_attr($color); ?>" cx="21.5" cy="21.5" r="21"></circle>
								<circle fill="#000" fill-opacity="0.08" cx="21.5" cy="21.5" r="21"></circle>

								<g fill="#FFF">
									<path d="M31,21.5L17,33l2.5-11.5L17,10L31,21.5z"></path>
								</g>

								<g class="waveform" fill="#<?php echo esc_attr($color); ?>">
									<?php
										for ($i = 0; $i < 30; $i++) {
											$_x = ($i * 5) + 50;
											$_h = rand(5, 43);
											$_y = 43 - $_h;
											$_d = $i * 0.05;

											echo '<rect x="' . esc_attr($_x) . '" y="' . esc_attr($_y) . '" width="3" height="' . esc_attr($_h) . '" style="--delay: ' . esc_attr($_d) . 's;" />';
										}
									?>
								</g>
							</svg>
						</div>
					</td>
				</tr>

				<?php
					//	Loop through all the `bool` options.

					$params_array = soundcloud_return_fields();
				?>

				<?php foreach ($params_array as $key=>$arr) : ?>
					<?php if ($arr['type'] == 'bool') : ?>
						<tr valign="top">
							<th scope="row">
								<span><?php echo esc_html($arr['title']); ?></span>
							</th>

							<?php
								$checked_blank = '';
								$checked_true = '';
								$checked_false = '';

								if (soundcloud_get_option($key) == '') {
									$checked_blank = 'checked';
								}

								if (soundcloud_get_option($key) == 'true') {
									$checked_true = 'checked';
								}

								if (soundcloud_get_option($key) == 'false') {
									$checked_false = 'checked';
								}
							?>

							<td>
								<div>
									<input type="radio" id="<?php echo esc_attr($key); ?>_none" name="soundcloud_<?php echo esc_attr($key); ?>" value="" <?php echo esc_attr($checked_blank); ?> />
									<label for="<?php echo esc_attr($key); ?>_none" class="radio-label">Default</label>
								</div>
								
								<div>
									<input type="radio" id="<?php echo esc_attr($key); ?>_true" name="soundcloud_<?php echo esc_attr($key); ?>" value="true" <?php echo esc_attr($checked_true); ?> />
									<label for="<?php echo esc_attr($key); ?>_true"	class="radio-label">Yes</label>
								</div>
								
								<div>
									<input type="radio" id="<?php echo esc_attr($key); ?>_false" name="soundcloud_<?php echo esc_attr($key); ?>" value="false" <?php echo esc_attr($checked_false); ?> />
									<label for="<?php echo esc_attr($key); ?>_false" class="radio-label">No</label>
								</div>
								
								<?php if (isset($arr['desc']) && $arr['desc'] != '') : ?>
									<p class="description">
										<?php echo esc_html($arr['desc']); ?>
									</p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php
					//	Params preview
				?>

				<tr valign="top">
					<th scope="row">
						<span>
							How current settings would render as <code>params</code> in a shortcode
						</span>
					</th>

					<td>
						<code>
						<?php
							$params = soundcloud_return_saved_parameters();

							echo esc_html('[soundcloud url="https://api.soundcloud.com/tracks/30013625" params="' . $params . '"]');
						?>
						</code>
					</td>
				</tr>
			</table>

				<p class="submit">
					<button type="submit" class="button-primary">
						<?php esc_html_e('Save Changes'); ?>
					</button>
				</p>

		</form>
	</div>

	<?php
}