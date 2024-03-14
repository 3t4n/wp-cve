<?php

/**
 * Helper functions
 *
 * @package Skt_Addons_Elementor
 */

use Skt_Addons_Elementor\Extension\Mega_Menu;

defined('ABSPATH') || die();

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.0
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function skt_addons_elementor_do_shortcode($tag, array $atts = [], $content = null) {
	global $shortcode_tags;
	if (!isset($shortcode_tags[$tag])) {
		return false;
	}
	return call_user_func($shortcode_tags[$tag], $atts, $content, $tag);
}

/**
 * Sanitize html class string
 *
 * @param $class
 * @return string
 */
function skt_addons_elementor_sanitize_html_class_param($class) {
	$classes   = !empty($class) ? explode(' ', $class) : [];
	$sanitized = [];
	if (!empty($classes)) {
		$sanitized = array_map(function ($cls) {
			return sanitize_html_class($cls);
		}, $classes);
	}
	return implode(' ', $sanitized);
}

function skt_addons_elementor_is_script_debug_enabled() {
	return (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG);
}

/**
 * @param $settings
 * @param array $field_map
 */

function skt_addons_elementor_prepare_data_prop_settings(&$settings, $field_map = []) {
	$data = [];
	foreach ($field_map as $key => $data_key) {
		$setting_value                          = skt_addons_elementor_get_setting_value($settings, $key);
		list($data_field_key, $data_field_type) = explode('.', $data_key);
		$validator                              = $data_field_type . 'val';

		if (is_callable($validator)) {
			$val = call_user_func($validator, $setting_value);
		} else {
			$val = $setting_value;
		}
		$data[$data_field_key] = $val;
	}
	return wp_json_encode($data);
}

/**
 * @param $settings
 * @param $keys
 * @return mixed
 */
function skt_addons_elementor_get_setting_value(&$settings, $keys) {
	if (!is_array($keys)) {
		$keys = explode('.', $keys);
	}
	if (is_array($settings[$keys[0]])) {
		return skt_addons_elementor_get_setting_value($settings[$keys[0]], array_slice($keys, 1));
	}
	return $settings[$keys[0]];
}

function skt_addons_elementor_is_localhost() {
	return sanitize_text_field(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']));
}

function skt_addons_elementor_get_css_cursors() {
	return [
		'default'      => __('Default', 'skt-addons-elementor'),
		'alias'        => __('Alias', 'skt-addons-elementor'),
		'all-scroll'   => __('All scroll', 'skt-addons-elementor'),
		'auto'         => __('Auto', 'skt-addons-elementor'),
		'cell'         => __('Cell', 'skt-addons-elementor'),
		'context-menu' => __('Context menu', 'skt-addons-elementor'),
		'col-resize'   => __('Col-resize', 'skt-addons-elementor'),
		'copy'         => __('Copy', 'skt-addons-elementor'),
		'crosshair'    => __('Crosshair', 'skt-addons-elementor'),
		'e-resize'     => __('E-resize', 'skt-addons-elementor'),
		'ew-resize'    => __('EW-resize', 'skt-addons-elementor'),
		'grab'         => __('Grab', 'skt-addons-elementor'),
		'grabbing'     => __('Grabbing', 'skt-addons-elementor'),
		'help'         => __('Help', 'skt-addons-elementor'),
		'move'         => __('Move', 'skt-addons-elementor'),
		'n-resize'     => __('N-resize', 'skt-addons-elementor'),
		'ne-resize'    => __('NE-resize', 'skt-addons-elementor'),
		'nesw-resize'  => __('NESW-resize', 'skt-addons-elementor'),
		'ns-resize'    => __('NS-resize', 'skt-addons-elementor'),
		'nw-resize'    => __('NW-resize', 'skt-addons-elementor'),
		'nwse-resize'  => __('NWSE-resize', 'skt-addons-elementor'),
		'no-drop'      => __('No-drop', 'skt-addons-elementor'),
		'not-allowed'  => __('Not-allowed', 'skt-addons-elementor'),
		'pointer'      => __('Pointer', 'skt-addons-elementor'),
		'progress'     => __('Progress', 'skt-addons-elementor'),
		'row-resize'   => __('Row-resize', 'skt-addons-elementor'),
		's-resize'     => __('S-resize', 'skt-addons-elementor'),
		'se-resize'    => __('SE-resize', 'skt-addons-elementor'),
		'sw-resize'    => __('SW-resize', 'skt-addons-elementor'),
		'text'         => __('Text', 'skt-addons-elementor'),
		'url'          => __('URL', 'skt-addons-elementor'),
		'w-resize'     => __('W-resize', 'skt-addons-elementor'),
		'wait'         => __('Wait', 'skt-addons-elementor'),
		'zoom-in'      => __('Zoom-in', 'skt-addons-elementor'),
		'zoom-out'     => __('Zoom-out', 'skt-addons-elementor'),
		'none'         => __('None', 'skt-addons-elementor'),
	];
}

function skt_addons_elementor_get_css_blend_modes() {
	return [
		'normal'      => __('Normal', 'skt-addons-elementor'),
		'multiply'    => __('Multiply', 'skt-addons-elementor'),
		'screen'      => __('Screen', 'skt-addons-elementor'),
		'overlay'     => __('Overlay', 'skt-addons-elementor'),
		'darken'      => __('Darken', 'skt-addons-elementor'),
		'lighten'     => __('Lighten', 'skt-addons-elementor'),
		'color-dodge' => __('Color Dodge', 'skt-addons-elementor'),
		'color-burn'  => __('Color Burn', 'skt-addons-elementor'),
		'saturation'  => __('Saturation', 'skt-addons-elementor'),
		'difference'  => __('Difference', 'skt-addons-elementor'),
		'exclusion'   => __('Exclusion', 'skt-addons-elementor'),
		'hue'         => __('Hue', 'skt-addons-elementor'),
		'color'       => __('Color', 'skt-addons-elementor'),
		'luminosity'  => __('Luminosity', 'skt-addons-elementor'),
	];
}

/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
function skt_addons_elementor_is_elementor_version($operator = '<', $version = '2.6.0') {
	return defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, $version, $operator);
}

/**
 * Render icon html with backward compatibility
 *
 * @param array $settings
 * @param string $old_icon_id
 * @param string $new_icon_id
 * @param array $attributes
 */
function skt_addons_elementor_render_icon($settings = [], $old_icon_id = 'icon', $new_icon_id = 'selected_icon', $attributes = []) {
	// Check if its already migrated
	$migrated = isset($settings['__fa4_migrated'][$new_icon_id]);
	// Check if its a new widget without previously selected icon using the old Icon control
	$is_new = empty($settings[$old_icon_id]);

	$attributes['aria-hidden'] = 'true';

	if (skt_addons_elementor_is_elementor_version('>=', '2.6.0') && ($is_new || $migrated)) {
		\Elementor\Icons_Manager::render_icon($settings[$new_icon_id], $attributes);
	} else {
		if (empty($attributes['class'])) {
			$attributes['class'] = $settings[$old_icon_id];
		} else {
			if (is_array($attributes['class'])) {
				$attributes['class'][] = $settings[$old_icon_id];
			} else {
				$attributes['class'] .= ' ' . $settings[$old_icon_id];
			}
		}
		printf('<i %s></i>', \Elementor\Utils::render_html_attributes($attributes));
	}
}

/**
 * List of skt icons
 *
 * @return array
 */
function skt_addons_elementor_get_skt_addons_elementor_icons() {
	return \Skt_Addons_Elementor\Elementor\Icons_Manager::get_skt_addons_elementor_icons();
}

/**
 * Get elementor instance
 *
 * @return \Elementor\Plugin
 */
function skt_addons_elementor() {
	return \Elementor\Plugin::instance();
}

/**
 * Escaped title html tags
 *
 * @param string $tag input string of title tag
 * @return string $default default tag will be return during no matches
 */

function skt_addons_elementor_escape_tags($tag, $default = 'span', $extra = []) {

	$supports = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];

	$supports = array_merge($supports, $extra);

	if (!in_array($tag, $supports, true)) {
		return $default;
	}

	return $tag;
}

/**
 * Get a list of all the allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return array
 */
function skt_addons_elementor_get_allowed_html_tags($level = 'basic') {
	$allowed_html = [
		'b'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'i'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'u'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		's'      => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'br'     => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'em'     => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'del'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'ins'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'sub'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'sup'    => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'code'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'mark'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'small'  => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'strike' => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'abbr'   => [
			'title' => [],
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'span'   => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
		'strong' => [
			'class' => [],
			'id'    => [],
			'style' => [],
		],
	];

	if ('intermediate' === $level) {
		$tags = [
			'a'       => [
				'href'  => [],
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'q'       => [
				'cite'  => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'img'     => [
				'src'    => [],
				'alt'    => [],
				'height' => [],
				'width'  => [],
				'class'  => [],
				'id'     => [],
				'style'  => [],
			],
			'dfn'     => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'time'    => [
				'datetime' => [],
				'class'    => [],
				'id'       => [],
				'style'    => [],
			],
			'cite'    => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'acronym' => [
				'title' => [],
				'class' => [],
				'id'    => [],
				'style' => [],
			],
			'hr'      => [
				'class' => [],
				'id'    => [],
				'style' => [],
			],
		];

		$allowed_html = array_merge($allowed_html, $tags);
	}

	return $allowed_html;
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function skt_addons_elementor_kses_intermediate($string = '') {
	return wp_kses($string, skt_addons_elementor_get_allowed_html_tags('intermediate'));
}

/**
 * Strip all the tags except allowed html tags
 *
 * The name is based on inline editing toolbar name
 *
 * @param string $string
 * @return string
 */
function skt_addons_elementor_kses_basic($string = '') {
	return wp_kses($string, skt_addons_elementor_get_allowed_html_tags('basic'));
}

/**
 * Get a translatable string with allowed html tags.
 *
 * @param string $level Allowed levels are basic and intermediate
 * @return string
 */
function skt_addons_elementor_get_allowed_html_desc($level = 'basic') {
	if (!in_array($level, ['basic', 'intermediate'])) {
		$level = 'basic';
	}

	$tags_str = '<' . implode('>,<', array_keys(skt_addons_elementor_get_allowed_html_tags($level))) . '>';
	return sprintf(__('This input field has support for the following HTML tags: %1$s', 'skt-addons-elementor'), '<code>' . esc_html($tags_str) . '</code>');
}

function skt_addons_elementor_has_pro() {
	return defined('SKT_ADDONS_ELEMENTOR_PRO_VERSION');
}

function skt_addons_elementor_get_b64_icon() {
	return 'data:image/png;base64,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';
}

/**
 * @param $suffix
 */
function skt_addons_elementor_get_dashboard_link($suffix = '#home') {
	return add_query_arg(['page' => 'skt-addons' . $suffix], admin_url('admin.php'));
}

/**
 * @return mixed
 */
function skt_addons_elementor_get_current_user_display_name() {
	$user = wp_get_current_user();
	$name = 'user';
	if ($user->exists() && $user->display_name) {
		$name = $user->display_name;
	}
	return $name;
}

/**
 * Get All Post Types
 * @param array $args
 * @param array $diff_key
 * @return array|string[]|WP_Post_Type[]
 */
function skt_addons_elementor_get_post_types($args = [], $diff_key = []) {
	$default = [
		'public'            => true,
		'show_in_nav_menus' => true,
	];
	$args       = array_merge($default, $args);
	$post_types = get_post_types($args, 'objects');
	$post_types = wp_list_pluck($post_types, 'label', 'name');

	if (!empty($diff_key)) {
		$post_types = array_diff_key($post_types, $diff_key);
	}
	return $post_types;
}

/**
 * Get All Taxonomies
 * @param array $args
 * @param string $output
 * @param bool $list
 * @param array $diff_key
 * @return array|string[]|WP_Taxonomy[]
 */
function skt_addons_elementor_get_taxonomies($args = [], $output = 'object', $list = true, $diff_key = []) {

	$taxonomies = get_taxonomies($args, $output);
	if ('object' === $output && $list) {
		$taxonomies = wp_list_pluck($taxonomies, 'label', 'name');
	}

	if (!empty($diff_key)) {
		$taxonomies = array_diff_key($taxonomies, $diff_key);
	}

	return $taxonomies;
}

/**
 * Contain masking shape list
 * @param $element
 * @return array
 */
function sktaddonselementorextra_masking_shape_list($element) {
	$dir = SKT_ADDONS_ELEMENTOR_ASSETS . 'imgs/masking-shape/';
	$shape_name = 'shape';
	$extension = '.svg';
	$list = [];
	if ('list' == $element) {
		for ($i = 1; $i <= 39; $i++) {
			$list[$shape_name . $i] = [
				'title' => ucwords($shape_name . ' ' . $i),
				'url' => $dir . $shape_name . $i . $extension,
			];
		}
	} elseif ('url' == $element) {
		for ($i = 1; $i <= 39; $i++) {
			$list[$shape_name . $i] = $dir . $shape_name . $i . $extension;
		}
	}
	return $list;
}

/**
 * Compare value.
 *
 * Compare two values based on Comparison operator
 *
 * @param mixed $left_value  First value to compare.
 * @param mixed $right_value  Second value to compare.
 * @param string $operator  Comparison operator.
 * @return bool
 */
function sktaddonselementorextra_compare($left_value, $right_value, $operator) {
	switch ($operator) {
		case 'is':
			return $left_value == $right_value;
		case 'not':
			return $left_value != $right_value;
		default:
			return $left_value === $right_value;
	}
}

/**
 * Get User Browser name
 *
 * @param $user_agent
 * @return string
 */
function sktaddonselementorextra_get_browser_name($user_agent) {

	if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'opera';
	elseif (strpos($user_agent, 'Edge')) return 'edge';
	elseif (strpos($user_agent, 'Chrome')) return 'chrome';
	elseif (strpos($user_agent, 'Safari')) return 'safari';
	elseif (strpos($user_agent, 'Firefox')) return 'firefox';
	elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'ie';
	return 'other';
}

/**
 * Get Client Site Time
 * @param string $format
 * @return string
 */
function sktaddonselementorextra_get_local_time($format = 'Y-m-d h:i:s A') {
	$local_time_zone = sanitize_text_field(isset($_COOKIE['SktLocalTimeZone']) && !empty($_COOKIE['SktLocalTimeZone']) ? str_replace('GMT ', 'GMT+', $_COOKIE['SktLocalTimeZone']) : date_default_timezone_get());
	$now_date = new \DateTime('now', new \DateTimeZone($local_time_zone));
	$today = $now_date->format($format);
	return $today;
}

/**
 * Get Server Time
 * @param string $format
 * @return string
 */
function sktaddonselementorextra_get_server_time($format = 'Y-m-d h:i:s A') {
	$today 	= date($format, strtotime("now") + (get_option('gmt_offset') * HOUR_IN_SECONDS));
	return $today;
}

/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
function sktaddonselementorextra_is_elementor_version($operator = '>=', $version = '2.8.0') {
	return defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, $version, $operator);
}

/**
 * Get the list of all section templates
 *
 * @return array
 */
function sktaddonselementorextra_get_section_templates() {
	$items = skt_addons_elementor()->templates_manager->get_source('local')->get_items(['type' => 'section']);

	if (!empty($items)) {
		$items = wp_list_pluck($items, 'title', 'template_id');
		return $items;
	}

	return [];
}

if (!function_exists('skt_addons_elementor_get_section_icon')) {
	/**
	 * Get skt addons icon for panel section heading
	 *
	 * @return string
	 */
	function skt_addons_elementor_get_section_icon() {
		return '<img style="float: right" class="skt-section-icon" src=" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAABcVBMVEX////yWADyUADxQADxSgDyTwDyWgDyUgD0YgD0YwDyVwD0bQD1dQD0cAD0bwD1hAD1egD1fwD1hwD1hQD3iAD3jwD3jQD3kADxRQDxQgDxQgDxQgDxQgDxQwDxRQDxQgDxQgDxQgDxQgDxQgDxQgDxRwDxRQDxRQDxRwDxRwDyTQDxTQDxSwDxTQDyTwDyTQDySgDxQgDxQADxRwDySgDxQgDxQgDyVwDyWADyWgDyWADyVwDyWADyWAD0YAD0YgD0ZQD0ZQD0YgDyXwD0YwD0YgD0awD0bwD0bwD1cwD0bwD0bwD1egD1egD1egD1egD1egD1egD1hwD1hwD1hQD1hAD1ggD1hAD1hQD1hAD1hAD3jAD3jQD3jAD3igD3jwD3jQD3jQD3kAD3kAD3kAD3kAD3jQD3jwD3kAD3kAD3kAD3kAD3kAD3kADxRQDxQwDxQgDyTQDySwDyVwDyWAD0YgD0bwD1egD1hAD3jAD3jQDQUG+zAAAAbnRSTlMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIbqmuqAwMlaWnoaScSvD4DWuhcx4YX/6STk9vvFBJfbM++K4hlxbeiNbMDiycSP7ICSychdvxqCycPUF2pUn6gSyd9+ZFYGIsnpWin2EDCIynbBiTWFq9TrsAAAC0SURBVBjTY2AgHUhISknLyMrJK8jIKCopy6gwqKrl5alraObl5ReAAYNWobaOrp5+oYGhkXGRiakZg3mxBaOlVbE1EzOLTYktKzODnX2pg2OpkzMbO4dLqSsnFwO3m3tZWZmHJw8vn1eZNx8fA7+AT7mvX7m/oKBAQHmggABDUHBIRWhYeEWEkHBkRRQ/P0N0ZVVMrEhcVVW8aEJVopgYQ1JySmqaWHpGZpZ4dk6uuDgZfgMAZB8ocpbh2qEAAAAASUVORK5CYII="></img>';
	}
}

/**
 * Contain Divider shape list
 * @param $shape
 * @return array
 */
function sktaddonselementorextra_devider_shape($shape) {

	if (empty($shape) || $shape === 'none') {
		return;
	}

	$shape_svg_list = [
		'clouds' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 61.7" preserveAspectRatio="none"><path class="st0" opacity="0.2" d="M399.9,61.7V25.3c-1.7-0.6-3.6-0.9-5.5-0.9c-1.9,0-3.7,0.3-5.4,0.8c-2.8-6.1-8.9-10.4-16.1-10.4c-3.3,0-6.3,0.9-8.9,2.4c-5.3-8.2-14.5-13.6-25-13.6c-12.3,0-22.9,7.5-27.4,18.3C308.2,11,298.1,3.1,286.1,3.1c-7,0-13.3,2.7-18.1,7c-1.6-0.5-3.2-0.8-4.9-0.8c-4.4,0-8.4,1.8-11.4,4.6c-3.6-4.9-9.4-8.1-15.9-8.1c-4.6,0-8.9,1.6-12.3,4.3c-3.4-2.7-7.6-4.3-12.3-4.3c-9.7,0-17.8,7-19.5,16.3c-1.4-0.6-3-1-4.7-1c-3.8,0-7.2,1.8-9.4,4.5c-3-7-9.9-11.9-18-11.9c-3.9,0-7.5,1.2-10.6,3.1c-4.9-7-13-11.6-22.2-11.6c-7.3,0-13.9,2.9-18.8,7.6c-4-3-9-4.8-14.5-4.8c-8.4,0-15.8,4.3-20.2,10.8c-1.5-0.7-3.3-1.1-5.1-1.1c-2.6,0-5.1,0.8-7,2.3c-3.8-3-8.6-4.8-13.8-4.8c-7.2,0-13.5,3.4-17.6,8.7c-2.1-1-4.4-1.6-6.8-1.6c-5.1,0-9.6,2.5-12.4,6.4C7.3,27.3,3.8,26.3,0,25.9v35.8H399.9z"/><path class="st0" opacity="0.2" d="M399.9,25.1c-1.6-0.3-3.3-0.5-5-0.5c-6.6,0-12.6,2.5-17.1,6.5c-3.1-10.7-13.1-18.6-24.8-18.6c-8.3,0-15.6,3.9-20.3,9.9c-4.7-6-12.1-9.9-20.3-9.9c-14.3,0-25.8,11.6-25.8,25.8c0,1,0.1,2,0.2,3c-4.7-5.7-11.9-9.4-19.9-9.4c-8,0-15.1,3.6-19.8,9.2c-4.9-4.5-11.4-7.2-18.5-7.2c-8.4,0-15.9,3.8-20.9,9.7c-4.4-9.7-14.2-16.4-25.5-16.4c-5.4,0-10.5,1.5-14.8,4.2c-3.5-10.1-13.1-17.3-24.4-17.3c-9,0-16.9,4.6-21.5,11.5c-3.9-5.3-10.2-8.7-17.3-8.7c-4.8,0-9.2,1.6-12.8,4.2c-3.7-6.4-10.6-10.6-18.5-10.6c-7.2,0-13.5,3.5-17.4,8.9c-2.4-4.2-7-7.1-12.2-7.1c-7.1,0-12.9,5.2-13.9,12c-0.5,0-1,0-1.4,0c-14.1,0-25.6,11.4-25.6,25.6c0,1.2,0.1,2.5,0.3,3.7c-0.8,0.1-1.7,0.3-2.5,0.5v7.6h400L399.9,25.1z"/><path class="st1" d="M399.9,61v-4.9c-2.3-1-4.8-1.5-7.5-1.5c-0.4,0-0.8,0-1.2,0c-2.7-7.3-9.8-12.6-18.1-12.6c-6,0-11.3,2.7-14.8,6.9c-4.8-7.1-12.8-11.7-22-11.7c-8.9,0-16.8,4.4-21.6,11.2C312.6,40.2,305,34,296,34c-6.5,0-12.2,3.2-15.7,8.1c-1.9-0.7-4-1-6.2-1c-5.4,0-10.3,2.2-13.8,5.9c-2.7-7.4-9.8-12.8-18.2-12.8c-6.5,0-12.2,3.2-15.7,8.1c-3.7-2.7-8.2-4.2-13.1-4.2c-9.1,0-16.9,5.4-20.4,13.2c-1.4-0.7-3-1.1-4.7-1.1c-3.2,0-6.1,1.3-8.1,3.5c-3.4-5.4-9.5-9-16.3-9c-3.9,0-7.6,1.2-10.6,3.2c-3.8-8.1-12.1-13.7-21.6-13.7c-6.9,0-13.2,2.9-17.5,7.7c-4.3-4.2-10.2-6.7-16.7-6.7c-9.2,0-17.2,5.1-21.2,12.7c-1.9-1.1-4-1.8-6.4-1.8c-3.9,0-7.3,1.8-9.6,4.7c-3.4-4.4-8.7-7.2-14.7-7.2c-7.3,0-13.6,4.1-16.7,10.2c-2.5-2.4-5.9-3.8-9.7-3.8c-5.2,0-9.7,2.8-12.2,6.9c-1.9-1.4-4.3-2.4-6.8-2.6V61v0.7h399.9V61L399.9,61L399.9,61z"/></svg>',

		'corner' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 45" preserveAspectRatio="none"><polygon class="st0" points="0,38.7 200,0 400,38.7 400,45 0,45 "/></svg>',

		'cross-line' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 59.7" preserveAspectRatio="none"><path class="st0" d="M0,59.7V19.8C0,17.7,1.7,16,3.8,16h0c2.1,0,3.8,1.7,3.8,3.8v14.2c0,2.1,1.7,3.8,3.8,3.8c2.1,0,3.8-1.7,3.8-3.8  V27c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v16.4c0,2.1,1.7,3.8,3.8,3.8c2.1,0,3.8-1.7,3.8-3.8V16.2c0-2.1,1.7-3.8,3.8-3.8h0  c2.1,0,3.8,1.7,3.8,3.8v17.7c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v27.8  c0,2,1.7,3.7,3.7,3.7h0.3c2,0,3.7-1.7,3.7-3.7V3.8c0-2.1,1.7-3.8,3.8-3.8c2.1,0,3.8,1.7,3.8,3.8v34.7c0,2,1.7,3.7,3.7,3.7l0,0  c2,0,3.7-1.7,3.7-3.7V23.4c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v6.7c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V8.9  c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v31.9c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8c0-2.1,1.7-3.8,3.8-3.8h0  c2.1,0,3.8,1.7,3.8,3.8v5.7c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7v-10c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v16.1  c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v27.3c0,2.1,1.7,3.8,3.8,3.8h0  c2.1,0,3.7-1.7,3.7-3.8V5.3c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v32.9c0,2.1,1.7,3.8,3.8,3.8s3.8-1.7,3.8-3.8v-13  c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8V52c0,2.1,1.7,3.8,3.8,3.8h0.1c2.1,0,3.8-1.7,3.8-3.8l-0.1-41.3  c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v29.2c0,2.1,1.7,3.8,3.8,3.8h0c2.1,0,3.8-1.7,3.8-3.8V19.8c0-2.1,1.7-3.8,3.8-3.8h0  c2.1,0,3.8,1.7,3.8,3.8v4.9c0,2.1,1.7,3.8,3.8,3.8c2.1,0,3.8-1.7,3.8-3.8v-9.6c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v15.8  c0,2.1,1.7,3.8,3.8,3.8c2.1,0,3.8-1.7,3.8-3.8V19.8c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v14.2c0,2.1,1.7,3.8,3.8,3.8  c2.1,0,3.8-1.7,3.8-3.8V27c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v16.4c0,2.1,1.7,3.8,3.8,3.8c2.1,0,3.8-1.7,3.8-3.8V16.2  c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v17.7c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8c0-2.1,1.7-3.8,3.8-3.8h0  c2.1,0,3.8,1.7,3.8,3.8v27.8c0,2,1.7,3.7,3.7,3.7h0.3c2,0,3.7-1.7,3.7-3.7V3.8c0-2.1,1.7-3.8,3.8-3.8s3.8,1.7,3.8,3.8v34.7  c0,2,1.7,3.7,3.7,3.7l0,0c2,0,3.7-1.7,3.7-3.7V23.4c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v6.7c0,2,1.7,3.7,3.7,3.7h0.1  c2,0,3.7-1.7,3.7-3.7V8.9c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v31.9c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8  c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v5.7c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7v-10c0-2.1,1.7-3.8,3.8-3.8h0  c2.1,0,3.8,1.7,3.8,3.8v16.1c0,2,1.7,3.7,3.7,3.7h0.1c2,0,3.7-1.7,3.7-3.7V19.8c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v27.3  c0,2.1,1.7,3.8,3.8,3.8h0c2.1,0,3.7-1.7,3.7-3.8V5.3c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v32.9c0,2.1,1.7,3.8,3.8,3.8  s3.8-1.7,3.8-3.8v-13c0-2.1,1.7-3.8,3.8-3.8h0c2.1,0,3.8,1.7,3.8,3.8v34.5H0z"/></svg>',

		'curve' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 35" preserveAspectRatio="none"><path class="st0" d="M0,33.6C63.8,11.8,130.8,0.2,200,0.2s136.2,11.6,200,33.4v1.2H0V33.6z"/></svg>',

		'drops' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 70.8" preserveAspectRatio="none"><path class="st0" d="M400,68c0,0-7.1-0.8-7.1-8.5s6.4-22.1,0-26s-7.3,2.6-6,6.5c1.3,3.9,6.2,14.5-2.6,14.8  c-6.3,0.2-0.8-8.3-7.4-10.2c-3.7-1.1-2,4.6-6.3,4.8c-6.3,0.4-7.8-10.1-7.8-12.4c0-2.3,1.9-28.7,0-32.9c-1.9-4.2-3.6-4.5-5.3-3.7  c-1.7,0.7-4.6,2.5-2,11.2c2.7,8.7,3.4,26,3.8,29.5c0.4,3.6,0.2,8.9-5.3,8.3c-5.6-0.6-0.9-16.1-6.6-15.7c-5.7,0.4-0.6,9-6.5,11.2  s-7.6,0.2-8-4.2c-0.3-4.4-5.9-5-8.6,6c-2.7,11-10.5,9-11.4,0.4s3.6-22.9-4.3-21.9s-3.6,11.9-2.2,14.4s6.3,22.2-6,27.3  c-12.3,5.1-1.7-33.5-10.4-32.9c-8.7,0.7,2.7,24.4-7.5,27.2c-10.2,2.8,0-15.1-7.1-17.6c-7.1-2.5,3.7,10.7-4.1,13.4  c-7.9,2.7-6.6-26.4-6.6-26.4s2.9-14.9-3.2-13.9c-6.1,1-1.7,14.7-0.7,18.6c0.9,3.9,1.7,22.2-7.4,22.5c-12.2,0.4-2.4-23.9-12.2-23.1  c-9.8,0.7,0.2,11.1-8.5,15.2c-2.5,1.2-5.6-5.9-8.7-4.9c-3.2,1-4.2,10.9-9.2,10.1c-5-0.7-4.4-11.5-4.3-18.7c0.1-7.1-3.9-7.9-3.7-2.4  c0.2,5,2.5,16.2-0.2,20.6c-0.5,0.7-1.4,1-2.2,0.5c-1.4-0.8-2.1-1.6-1.9,2.6c0.2,4.9-1.5,7.4-3.7,8c-0.3,0.1-0.6,0.1-0.9,0.1l0,0  c-0.6,0-1.2-0.1-1.8-0.3c-1.4-0.5-3.5-2-3.4-6c0.2-7.6,6.4-22.1,0-26c-6.4-3.9-7.3,2.6-6,6.5s6.2,14.5-2.6,14.8  c-6.3,0.2-0.8-8.3-7.4-10.2c-3.7-1.1-2,4.6-6.3,4.8c-6.3,0.4-7.8-10.1-7.8-12.4c0-2.3,1.9-28.7,0-32.9s-3.6-4.5-5.3-3.7  s-4.6,2.5-2,11.2c2.7,8.7,3.4,26,3.8,29.5c0.4,3.6,0.2,8.9-5.3,8.3c-5.6-0.6-0.9-16.1-6.6-15.7c-5.7,0.4-0.6,9-6.5,11.2  c-5.9,2.2-7.6,0.2-8-4.2s-5.9-5-8.6,6c-2.7,11-10.5,9-11.4,0.4c-0.8-8.6,3.6-22.9-4.3-21.9s-2.7,11.6-2.2,14.4  c0.9,4.8,2.7,25.8-5.4,24.6c-13.1-2-2.2-30.8-11-30.2c-8.7,0.7,2.7,24.4-7.5,27.2C72.5,64,83,41.9,75.6,43.6  C69.9,45,79.4,54.3,71.5,57c-7.9,2.7-6.6-26.4-6.6-26.4s2.9-14.9-3.2-13.9S60.1,31.4,61,35.3s1.7,22.2-7.4,22.5  c-12.2,0.4-2.4-23.9-12.2-23.1c-9.8,0.7,0.8,14.8-8.4,17.4c-7.9,2.3-3.6-10.5-9.5-10.1c-4,0.3,2.9,13.9-8.5,13  c-5-0.4-4.4-11.5-4.3-18.7c0.1-7.1-3.9-7.9-3.7-2.4c0.2,5.5,3,18.5-1.2,21.7c-2.2-0.6-3.4-3.2-3.2,2.1S2,67,0,68v2.8h400V68z"/></svg>',

		'mountains' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 86.4" preserveAspectRatio="none"><path class="st0" opacity="0.2" d="M0,69.3c0,0,76.2-89.2,215-32.8s185,32.8,185,32.8v17H0V69.3z"/><path class="st0" opacity="0.2" d="M0,69.3v17h400v-17c0,0-7.7-93.8-145.8-59.1S89.7,119,0,69.3z"/><path class="st1" d="M0,69.3c0,0,50.3-63.1,197.3-14.2S400,69.3,400,69.3v17H0V69.3z"/></svg>',

		'pyramids' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 45" preserveAspectRatio="none"><polygon class="st0" points="0.5,40.1 49.9,21.2 138.1,40.1 276.4,-0.2 400.5,40.1 400.5,45 0.5,45 "/></svg>',

		'splash' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 50" preserveAspectRatio="none"><g><path class="st0" d="M158.3,19.5c2.6,0.3,1.2-4.2-0.5-2C157.1,18.3,157.4,19.4,158.3,19.5z"/><path class="st0" d="M157.2,8.9c-0.8-0.7-1.8,1.1-1.7,1.4C156.2,11.4,158,9.6,157.2,8.9z"/><path class="st0" d="M171.8,19.1c2.2-0.6,1.1-3.2-0.6-1.3C170.7,18.6,170.9,19.4,171.8,19.1z"/><path class="st0" d="M154,23.1c0.9-0.1,1.2-1.2,0.5-2C152.8,18.9,151.4,23.4,154,23.1z"/><path class="st0" d="M140.5,22.7c0.8,0.2,1.1-0.6,0.6-1.3C139.4,19.6,138.3,22.1,140.5,22.7z"/><path class="st0" d="M140.5,26.9c-1.8,1.9,3.9,2,1.6,0.3C141.7,26.9,140.9,26.5,140.5,26.9z"/><path class="st0" d="M350.3,37.3c-0.1-0.1-0.1-0.2-0.2-0.3c-1-1.3-0.3-3-3.6-3.5c-1.9-0.4-5.3,0.3-7.2,0.7c-3,1-4.3,2.5-7.7,3   c-3.2,0.5-7,0.7-10.3,0.9c-2.8,0.1-5.4,0.1-7.4-1.2c-1.9-1.1-1.7-2.5-4-3.3c-9.3-3.1-15.9,2.9-23.9,4.9c-1.9-1.1-0.2-2.4-0.1-3.6   c-0.3-5-7.3-0.3-10.6,1c-1.7,0.7-3,1-4,1c-0.5-0.1-0.9-0.2-1.4-0.2c-1.7-0.6-2.4-2.3-3.6-3.7c1-0.4,2.1-0.7,2.9-1.4   c1-0.9,0.8-2.2-0.7-2.2c-1.1,0-2.6,1.4-3.3,2c-0.1,0.1-0.2,0.2-0.3,0.4c-2-1.2-5.1-1.4-8.2-0.6c0.5-1.1,1-2.2,0.4-2.8   c-1.1-1.1-3.2,0.6-4.4-0.2c-1.7-1.1,4.7-4.4-0.6-4.7c-1.1,0-2.8,0.3-2.6-1.2c0.1-1.1,2-1,2.2-2.3c0.1-1.4-2.2-1.1-1.1-3   c0.7-1,2.7-1.4,3-2.6c0.3-1.7-1.4-1-2.2-0.5c-1.2,0.8-1.9,2.7-3.3,3.4c-1.1,0.7-2.3,0.2-3,1.8c-0.3,0.8,0.1,3-1,3.4   c-2.4,0.8,0.8-6.1-2.5-4.7c-1.8,0.9-1,4.7-1.7,6.1c-0.5,0.9-1.4,2.6-2.7,2c-0.3-0.2-0.4-0.4-0.5-0.6c0-0.1,0-0.3,0-0.4   c0.1-0.7,0.5-1.5-0.2-1.9c-0.7-0.5-1.8,0.4-2.5,1.2c-0.1,0.1-0.2,0.1-0.2,0.2c-0.1,0.2-0.2,0.3-0.3,0.4c-1.4,1.7-1.1,4.9-3.1,6.8   c-0.6,0.6-2.5,2.1-3.5,1.4c-0.9,2.1-1.6,4.3-2.2,6.6c-0.4,0.3-0.9,0.6-1.4,0.9c-0.9-0.8-1.5-1.9-1.6-3   c-0.4-5.6,11.4-12.7,11.2-16.2c0,0-0.2-0.8-0.2-1.4c-0.3-4.7,6.6-8.2,6.9-9.7c0.1-0.5-0.6-1.2-2.1-1.1c-7.1,0.5-10.1,13-12.6,13.2   c-0.7,0.1-1.7-0.8-1.8-2.2c-0.1-1.4,0.7-2.8,0.6-4.3c-0.1-1.1-0.7-1.1-1.3-1.1c-3.2,0.2-4,13.6-11.1,14.1c-8.7,0.6-5-14-9.5-13.7   c-2.3,0.2-5.4,4-6.1,4.1c-1.9,0.1-0.6-4.3-5.5-4c-0.8,0.1-1.8,0.3-1.8,0.3c-1.8,0.1-0.8-2.9-2.5-2.8c-0.5,0-0.9,0.4-1.4,0.5   c-1.3,0.1-3.6-3-5.8-2.8c-0.9,0.1-2.3,0.8-2.2,2.2c0.2,3.1,5.3,0.9,7.2,5.5c0.7,1.8,0.3,4.4,2.1,5.8c2.2,1.7,10.8,9.5,11.1,13.1   c0.1,1.6-0.1,3.1-0.6,4.2c-0.2,0-0.3,0-0.5,0c-0.2-0.1-0.5-0.2-0.7-0.3c-1.2-2.8-2.5-5.5-4-8.1c-0.9,0.8-3-0.4-3.7-0.9   c-2.2-1.6-2.4-4.8-4-6.3c-0.5-0.6-2.5-2.2-3.3-1.4c-0.9,0.8,0.9,2.2-0.3,3c-1.3,0.8-2.4-0.8-3-1.6c-0.9-1.4-0.6-5.2-2.5-5.8   c-3.5-1,0.6,5.4-1.8,5c-0.3-0.1-0.5-0.3-0.7-0.6c0.6-0.2,0.9-0.7,0.3-1.4c-0.2-0.2-0.5-0.2-0.7-0.1c-0.1-0.5-0.2-0.9-0.3-1.2   c-0.9-1.5-2-0.9-3.2-1.4c-1.4-0.5-2.4-2.3-3.7-2.9c-0.9-0.5-2.6-0.9-2.1,0.8c0.4,1.2,2.5,1.3,3.3,2.2c1.3,1.7-1,1.7-0.7,3.1   c0.3,1.2,2.2,0.8,2.5,1.9c0.4,1.5-1.3,1.4-2.4,1.6c-5.1,1,1.6,3.3,0.1,4.7c-0.6,0.5-1.4,0.4-2.2,0.3c-0.3-0.2-0.8-0.4-1.3-0.6   c-3.7-1.2-7.6-0.9-9.6,0.7c-1,0.8-1.6,2-2.3,2.9c0,0-0.1,0-0.1,0c-0.9,0.1-1.3,0.7-1.1,1.2c-0.2,0.1-0.5,0.3-0.7,0.4   c-0.5,0.1-0.9,0.1-1.4,0.2c-1,0-2.3-0.3-4-1c-3.3-1.3-10.3-6-10.6-1c0.2,1.2,1.8,2.5-0.1,3.6c-8-2-14.7-8-23.9-4.9   c-2.4,0.9-2.1,2.3-4,3.3c-2,1.3-4.6,1.3-7.4,1.2c-3.3-0.2-7.1-0.4-10.3-0.9c-3.4-0.5-4.7-2-7.7-3c-1.9-0.5-5.3-1.1-7.2-0.7   c-3.3,0.5-2.6,2.2-3.6,3.5c-0.3,0.3-0.5,0.7-0.9,0.9c-43.7-2.6-66-5.7-66-5.7V50h400V32.3C400,32.3,385.5,34.9,350.3,37.3z"/><path class="st0" d="M237.9,15c-0.3,0-0.4,0.3-0.4,0.6c0,0.5,0.7,0.9,1.2,0.8c0.4,0,0.6-0.3,0.6-0.7C239.4,15.2,238.5,15,237.9,15z   "/><path class="st0" d="M232.2,9.1c0.7-0.6,1-1.1,1.8-1.4c2.4-1,2.9-2.5,2.8-3c0-0.6-0.5-0.7-1.4-0.6c-2.3,0.2-4,2.2-4.3,3.9   C230.9,9.1,231.4,9.8,232.2,9.1z"/><path class="st0" d="M228.5,5.5c0.4,0,1.5-1.1,1.4-2.5c-0.1-0.7-0.4-1.4-1.1-1.4c-0.6,0-1.6,0.7-1.5,1.7   C227.4,4.4,227.9,5.6,228.5,5.5z"/><path class="st0" d="M222.7,11.8c0.8-0.1,1.6-1.4,1.6-2.2c-0.1-0.8-0.6-1.2-1-1.2c-0.5,0-1.3,0.6-1.2,2   C222,10.7,222.2,11.8,222.7,11.8z"/><path class="st0" d="M196.8,13c0.2-0.2,0.3-0.5,0.2-0.8c0-0.3-1.8-5.6-2.5-5.5c-0.9,0.1-1.3,0.9-1.2,1.8   C193.4,9.4,196.4,13.5,196.8,13z"/><path class="st0" d="M184,11.2c0.3,0,1.1-1,1-2c-0.1-1-0.8-1.6-1.4-1.5c-0.5,0-0.8,1.3-0.7,2C183,10.7,183.6,11.2,184,11.2z"/><path class="st0" d="M178.5,8.7c0.5,0,0.9-0.1,0.9-0.7c-0.1-0.9-0.8-1.9-1.6-1.8c-0.6,0-0.7,0.3-0.7,0.8   C177.1,7.9,177.6,8.8,178.5,8.7z"/><path class="st0" d="M174.4,14.3c0.3-0.1,0.2-0.4,0.2-0.7c0-0.6-0.6-1-1.1-1C172.3,12.8,173.4,14.6,174.4,14.3z"/><path class="st0" d="M202.1,10.7c0.7,0.2,0.7-1.1,0.6-1.6c-0.1-1.3-1.7-2.8-1.6-1.2C201.2,8.6,201.3,10.6,202.1,10.7z"/><path class="st0" d="M203.1,4.1c0,0,0.8,0,0.7-0.7c0-0.4-0.7-1.1-1.2-1.1c-0.3,0-0.5,0.3-0.5,0.7C202.1,3.5,202.5,4,203.1,4.1z"/><path class="st0" d="M227.2,15.6c0.7,0.1,1.6-1.1,1.5-2.3c0-0.5-0.2-0.5-0.5-0.5c-0.8,0.1-1.4,0.8-1.4,1.5   C226.8,14.6,227,15.6,227.2,15.6z"/></g></svg>',

		'split' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 50" preserveAspectRatio="none"><path class="st0" d="M247.4,2.6C221.2,2.6,200,23.8,200,50c0-26.2-21.2-47.4-47.4-47.4H0V50h200h200V2.6H247.4z"/></svg>',

		'tilt' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 55" preserveAspectRatio="none"><polygon class="st0" points="0,55 400,55 0,0 "/></svg>',

		'torn-paper' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 61.7" preserveAspectRatio="none"><path class="st0" d="M400,61.7V17.9c-0.1-1.1-0.4-3.5-0.4-3.5c0-0.4-4-1.2-4,1.2c0,1.3-3.9,1.7-3.9,3.1c0-1-5.1-0.5-5.1,0  c0,0.1-4,2.6-4,2.7c0-0.6-4.8,2.1-4.8,0.9c0-1.3-5,0-5,0.2c0,0.8-3.8,2.6-3.8,3.3c0,0.3-4.7,0.5-4.7,1c0-1.1-3.9,3.4-3.9,2.9  c0-0.2-5.1-0.4-5.1,0.1c0-1.1-4.4,1.1-4.4,1.6c0,2.3-4.3,2.6-4.3,3.4c0,0.8-5.3-2.8-5.3-2.1c0-1.2-5.3-0.6-5.3,0.2  c0-0.4-5.1,2.8-5.1,1.8c0,0.6-4.7-0.1-4.7,0.6c0,0.9-3.4,3-3.4,2.9c0,0.2-3.4,2.4-3.4,2.8c0,1.2-4.8,0.3-4.8,0.1  c0-0.5-4.5,0.5-4.5,0.4c0-0.3-4.6,2.7-4.6,3c0-0.5-4.7-1.4-4.7-2c0,1-4.6,2-4.6,0.9c0,1-4.7,0.8-4.7-0.7c0,0-4.7-0.8-4.7,0.1  c0-0.2-4.6,2.1-4.6,2.2c0-0.1-4.6-0.7-4.6,0.5c0,0.3-4.3-1-4.3-0.6c0-0.1-3.9-2.8-3.9-1.9c0-0.5-3.9-2.1-3.9-2.7c0,0.8-3-3.4-3-3.7  c0,0.1-3.9-1.1-3.9-2.7c0-0.2-4.6-0.2-4.6-0.9c0-0.4-5.1-0.5-5.1-0.7c0-0.2-3.7,3.5-3.7,3.8c0-1.4-3.9,3.1-3.9,2.8  c0-1-4.7,0.5-4.7,1.7c0,0.5-4,0.5-4,1.7c0,0.1-3.6,3.3-3.6,2.9c0-1.3-4.4-0.2-4.4,0.9c0,0.7-4.6-1.2-4.6-0.1c0-1.2-4.1,1.4-4.1,1.7  c0,0.9-4.5-1.6-4.5-0.9c0,1.1-4.1,3.2-4.1,2.7c0-0.1-4.4,1.1-4.4,0.7c0,0.6-4.4,0.1-4.4-0.9c0,1.6-4.4-2.1-4.4-1.8  c0,1.2-4.3,3.9-4.3,2.9c0,0.1-4.3-1.3-4.3-0.6c0,2.1-4.4,0.8-4.4,0.4c0-0.7-3.8-4-3.8-3.4c0-1.7-3.6-2.5-3.6-2.1  c0-0.3-4.2-1.4-4.2-1c0-0.4-4.1,0.4-4.1-1.5c0-1.1-4.6,0.8-4.6-0.6c0,0.2-3.1-3.9-3.1-4c0,0.7-4.4-2.2-4.4-1.6c0,1-4.4-1.6-4.4-1.6  c0-0.4-4.9,0.8-4.9,0.3c0,0.7-4.6-0.2-4.6-0.3c0,0.3-4.4-0.6-4.4-1.4c0-0.4-4.6-1.7-4.6-1.8c0,0.4-4.7,0.6-4.7,0.9  c0,0-4.5,3-4.5,1.4c0,1.9-4.4,0.9-4.4,1.4c0-0.2-5.2-2-5.2-1.8c0,0.6-4.2,2.5-4.2,2.2c0,1.2-3.7,3.9-3.7,3.5c0-0.2-4.4,0.9-4.4,1.6  c0,0.8-5.1-0.2-5.1-0.8c0,0.4-4.5,1.5-4.5,1.5c0,0.7-4.7-0.9-4.7-0.8c0,0.3-4.4-0.7-4.4-1.4c0-0.8-4.7-0.9-4.7-0.1  c0-0.1-4.4-2.5-4.4-1.4c0-0.3-5.1,2-5.1,0.9c0-1.3-4.1-2.5-4.1-2.5c0,0.1-4.5-1.3-4.5-2.5c0-0.6-4.8,3.5-4.8,2.2  c0-0.9-4.3,2.5-4.3,2.4c0-0.6-5.4-1.1-5.4-1.8c0-1.3-4.1,4.2-4.1,3c0-0.2-5.1-0.5-5.1-0.7c0-0.5-3.9,1.2-3.9,1.3  c0-0.3-4.1-0.6-4.1-0.3c0-0.4-4.3,1.3-4.3,1c0,0.2-4.6,0.8-4.6-0.2c0-0.1-2.8-2.7-2.8-3.8c0-2-4.3-0.8-4.3-2c0,0.6-4.5,0.8-4.5-1.6  c0,0.2-2.6-5.2-2.6-4.7c0,1-1.9,0.1-3.2-0.5l0,33.9H400z"/></svg>',

		'triangle' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 45" preserveAspectRatio="none"><polygon class="st0" points="0,39.2 272.4,3.5 400,39.2 400,45 0,45 "/></svg>',

		'wave' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 35" preserveAspectRatio="none"><path class="st0" d="M0,35h400V24.9c0,0-43.8-25.4-114.9-3.4s-107.7,4.1-142.2-9S34.1-6.7,0,12.6V35z"/></svg>',

		'zigzag' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 12.4 400 35" preserveAspectRatio="none"><polygon class="st0" points="400,47.3 400,14.2 375,30.7 350,14.2 325,30.7 300,14.2 275,30.7 250,14.2 225,30.7 200,14.2 175,30.7   150,14.2 125,30.7 100,14.2 75,30.7 50,14.2 25,30.7 0,14.2 0,47.3 "/></svg>',
	];

	return $shape_svg_list[$shape];
}

/**
 * Check if TablePress is activated
 *
 * @return bool
 */
function sktaddonselementorextra_is_table_press_activated() {
	return class_exists('TablePress');
}

/**
 * TablePress Tables List
 *
 * @return array
 */
function sktaddonselementorextra_get_table_press_list() {
	$lists = [];
	if (!sktaddonselementorextra_is_table_press_activated()) return $lists;

	$tables = TablePress::$model_table->load_all(true);
	if ($tables) {
		foreach ($tables as $table) {
			$table = TablePress::$model_table->load($table, false, false);
			$lists[$table['id']] = $table['name'];
		}
	}

	return $lists;
}

/**
 * Database Table List
 *
 * @return array
 */
function sktaddonselementorextra_db_tables_list() {
	global $wpdb;

	$tables_list = [];
	$tables = $wpdb->get_results('show tables', ARRAY_N);

	if ($tables) {
		$tables = wp_list_pluck($tables, 0);

		foreach ($tables as $table) {
			$tables_list[$table] = $table;
		}
	}

	return $tables_list;
}

function skt_addons_elementor_mini_cart_count_total_fragments($fragments) {

	$fragments['.skt-mini-cart-count'] = '<span class="skt-mini-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
	$fragments['.skt-mini-cart-total'] = '<span class="skt-mini-cart-total">' . WC()->cart->get_cart_total() . '</span>';

	$fragments['.skt-mini-cart-popup-count'] = '<span class="skt-mini-cart-popup-count">' . WC()->cart->get_cart_contents_count() . '</span>';

	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'skt_addons_elementor_mini_cart_count_total_fragments', 5, 1);

/**
 * Get Menu Image Meta
 */
function skt_addons_elementor_img_meta($id) {
	$attachment = get_post($id);
	if ($attachment == null || $attachment->post_type != 'attachment') {
		return null;
	}
	return [
		'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink($attachment->ID),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	];
}

/**
 * Render icon html with backward compatibility
 *
 * @param array $settings
 * @param string $old_icon_id
 * @param string $new_icon_id
 * @param array $attributes
 */
function skt_addons_elementor_render_button_icon($settings = [], $old_icon_id = 'icon', $new_icon_id = 'selected_icon', $attributes = []) {
	// Check if its already migrated
	$migrated = isset($settings['__fa4_migrated'][$new_icon_id]);
	// Check if its a new widget without previously selected icon using the old Icon control
	$is_new = empty($settings[$old_icon_id]);

	$attributes['aria-hidden'] = 'true';
	$is_svg                    = (isset($settings[$new_icon_id], $settings[$new_icon_id]['library']) && 'svg' === $settings[$new_icon_id]['library']);

	if (skt_addons_elementor_is_elementor_version('>=', '2.6.0') && ($is_new || $migrated)) {
		if ($is_svg) {
			echo wp_kses_post('<span class="skt-btn-icon skt-btn-icon--svg">');
		}
		\Elementor\Icons_Manager::render_icon($settings[$new_icon_id], $attributes);
		if ($is_svg) {
			echo wp_kses_post('</span>');
		}
	} else {
		if (empty($attributes['class'])) {
			$attributes['class'] = $settings[$old_icon_id];
		} else {
			if (is_array($attributes['class'])) {
				$attributes['class'][] = $settings[$old_icon_id];
			} else {
				$attributes['class'] .= ' ' . $settings[$old_icon_id];
			}
		}
		printf('<i %s></i>', \Elementor\Utils::render_html_attributes($attributes));
	}
}

/**
 * Get database settings of a widget by widget id and element
 *
 * @param array $elements
 * @param string $widget_id
 * @param array $value
 */

function skt_addons_elementor_get_ele_widget_element_settings($elements, $widget_id) {

	if (is_array($elements)) {
		foreach ($elements as $d) {
			if ($d && !empty($d['id']) && $d['id'] == $widget_id) {
				return $d;
			}
			if ($d && !empty($d['elements']) && is_array($d['elements'])) {
				$value = skt_addons_elementor_get_ele_widget_element_settings($d['elements'], $widget_id);
				if ($value) {
					return $value;
				}
			}
		}
	}

	return false;
}

/**
 * Get database settings of a widget by widget id and post id
 *
 * @param number $post_id
 * @param string $widget_id
 * @param array
 */

function skt_addons_elementor_get_ele_widget_settings($post_id, $widget_id) {

	$elementor_data = @json_decode(get_post_meta($post_id, '_elementor_data', true), true);

	if ($elementor_data) {
		$element = skt_addons_elementor_get_ele_widget_element_settings($elementor_data, $widget_id);
		return isset($element['settings'])? $element['settings']: '';
	}

	return false;
}

/**
 * get credentials function
 *
 * @param string $key
 *
 * @return void
 * @since 1.0
 */
function skt_addons_elementor_get_credentials($key = '') {
	if ( ! class_exists( 'Skt_Addons_Elementor\Elementor\Credentials_Manager' ) ) {
    	include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/credentials-manager.php' );
	}
	$creds = Skt_Addons_Elementor\Elementor\Credentials_Manager::get_saved_credentials();
	if(!empty($key)) {
		return isset($creds[$key])? $creds[$key]: esc_html__('invalid key', 'skt-addons-elementor');
	}
	return $creds;
}

/**
 * Get plugin missing notice
 *
 * @param string $plugin
 * @return void
 */
function skt_addons_elementor_show_plugin_missing_alert( $plugin ) {
	if ( current_user_can( 'activate_plugins' ) && $plugin ) {
		printf(
			'<div %s>%s</div>',
			'style="margin: 1rem;padding: 1rem 1.25rem;border-left: 5px solid #f5c848;color: #856404;background-color: #fff3cd;"',
			$plugin . __( ' is missing! Please install and activate ', 'skt-addons-elementor' ) . $plugin . '.'
			);
	}
}

/**
 * Get inactive skt feature list
 *
 * @return array
 */
function skt_addons_elementor_get_inactive_features() {
	return get_option( 'sktaddonselementor_inactive_features', [] );
}

/**
 * Get post date link
 *
 * @param int $post_id
 * @return string
 */
function skt_get_date_link($post_id = null)
{
	if (empty($post_id)) {
		$post_id = get_the_ID();
	}

	$year = get_the_date('Y', $post_id);
	$month = get_the_time('m', $post_id);
	$day = get_the_time('d', $post_id);
	$url = get_day_link($year, $month, $day);

	return $url;
}

/**
 * Get post excerpt by length
 *
 * @param integer $length
 * @return string
 */
function skt_get_excerpt($post_id = null, $length = 15)
{
	if (empty($post_id)) {
		$post_id = get_the_ID();
	}

	return wp_trim_words(get_the_excerpt($post_id), $length);
}