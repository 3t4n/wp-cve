<?php

namespace WPAdminify\Inc\Classes;

// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}

class Helper
{

	// Admin Path
	public static function jltwp_adminify_admin_path($path)
	{
		// Get custom filter path
		if (has_filter('jltwp_adminify_admin_path')) {
			return apply_filters('jltwp_adminify_admin_path', $path);
		}

		// Get plugin path
		return plugins_url($path, __DIR__);
	}

	/**
	 * Get the editor/ builder of the given post.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return string The content editor name.
	 */
	public static function get_content_editor($post_id)
	{
		$content_editor = 'default';
		$content_editor = apply_filters('udb_content_editor', $content_editor, $post_id);

		return $content_editor;
	}

	/**
	 * Sanitize Checkbox.
	 *
	 * @param string|bool $checked Customizer option.
	 */
	public function sanitize_checkbox($checked)
	{
		return ((isset($checked) && true === $checked) ? true : false);
	}



	/**
	 * Check if Gutenberg Block Editor Page
	 *
	 * WordPress v5+
	 *
	 * @return boolean
	 */
	public static function is_block_editor_page()
	{
		if (function_exists('is_gutenberg_page') && is_gutenberg_page()) {
			// The Gutenberg plugin is on.
			return true;
		}

		if (!function_exists('get_current_screen')) {
			require_once ABSPATH . '/wp-admin/includes/screen.php';
			$current_screen = get_current_screen();
			if (function_exists('get_current_screen')) {
				if (!is_null($current_screen) && $current_screen->is_block_editor()) {
					// Gutenberg page on 5+.
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Classic Editor Page
	 *
	 * @return boolean
	 */
	public static function is_classic_editor_page()
	{
		if (function_exists('get_current_screen')) {
			$current_screen = get_current_screen();
			if ($current_screen->base == 'post' && empty($current_screen->is_block_editor)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if Elementor Page
	 *
	 * @return boolean
	 */
	public static function is_elementor_editor_page()
	{
		return !empty($_GET['elementor-preview']);
	}

	/**
	 * Image sanitization callback.
	 *
	 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
	 * send back the filename, otherwise, return the setting default.
	 *
	 * - Sanitization: image file extension
	 * - Control: text, WP_Customize_Image_Control
	 *
	 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
	 *
	 * @version 1.2.2
	 *
	 * @param string               $image   Image filename.
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	public static function sanitize_image($image, $setting)
	{

		/**
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		 */
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
		);

		// Allowed svg mime type in version 1.2.2.
		$allowed_mime   = get_allowed_mime_types();
		$svg_mime_check = isset($allowed_mime['svg']) ? true : false;

		if ($svg_mime_check) {
			$allow_mime = array('svg' => 'image/svg+xml');
			$mimes      = array_merge($mimes, $allow_mime);
		}

		// Return an array with file extension and mime_type.
		$file = wp_check_filetype($image, $mimes);

		// If $image has a valid mime_type, return it; otherwise, return the default.
		return esc_url_raw(($file['ext'] ? $image : $setting->default));
	}

	/**
	 * Get User Capabilities
	 *
	 * @param [type] $user
	 *
	 * @return void
	 */
	public static function get_user_capabilities($user = null)
	{
		$user = $user ? new \WP_User($user) : wp_get_current_user();

		return array_keys($user->allcaps);
	}

	/**
	 * Get User Roles
	 *
	 * @param [type] $user
	 *
	 * @return void
	 */
	static function get_user_roles($user)
	{
		return $user->roles;
	}

	static function nocache_headers()
	{
		if (headers_sent()) {
			return;
		}

		$headers = wp_get_nocache_headers();

		unset($headers['Last-Modified']);

		header_remove('Last-Modified');

		foreach ($headers as $name => $field_value) {
			header("{$name}: {$field_value}");
		}
	}


	/**
	 * Set allowed Hosts
	 *
	 * @param [type] $url
	 *
	 * @return void
	 */
	public static function allowed_host($url)
	{
		$url_parsed = parse_url($url);
		if (isset($url_parsed['host'])) {
			$allowed_hosts[] = $url_parsed['host'];
			add_filter('allowed_redirect_hosts', function ($hosts) use ($allowed_hosts) {
				return array_merge($hosts, $allowed_hosts);
			});
		}
	}


	/**
	 * Get Capabilites
	 *
	 * @return void
	 */
	public static function get_capabilities()
	{
		global $wp_roles;

		$caps = array();

		foreach ($wp_roles->roles as $wp_role) {
			if (isset($wp_role['capabilities']) && is_array($wp_role['capabilities'])) {
				$caps = array_merge($caps, array_keys($wp_role['capabilities']));
			}
		}

		$caps = array_unique($caps);

		$caps = (array) apply_filters('adminify_capabilities', $caps);

		sort($caps);

		return array_combine($caps, $caps);
	}

	/**
	 * Get User Capability Options
	 *
	 * @return void
	 */
	public static function get_capability_options()
	{
		$capabilities = self::get_capabilities();
		$_capabilities = [];

		foreach ($capabilities as $capability) {
			$_capabilities[] = $capability;
		}
		return $_capabilities;
	}


	/**
	 * Remove spaces from Plugin Slug
	 */
	public static function jltwp_adminify_slug_cleanup()
	{
		return str_replace('-', '_', strtolower(WP_ADMINIFY_SLUG));
	}

	/**
	 * Function current_datetime() compability for wp version < 5.3
	 *
	 * @return DateTimeImmutable
	 */
	public static function jltwp_adminify_current_datetime()
	{
		if (function_exists('current_datetime')) {
			return current_datetime();
		}

		return new \DateTimeImmutable('now', self::jltwp_adminify_wp_timezone());
	}

	/**
	 * Function jltwp_adminify_wp_timezone() compability for wp version < 5.3
	 *
	 * @return DateTimeZone
	 */
	public static function jltwp_adminify_wp_timezone()
	{
		if (function_exists('wp_timezone')) {
			return wp_timezone();
		}

		return new \DateTimeZone(self::jltwp_adminify_wp_timezone_string());
	}

	/**
	 * API Endpoint
	 *
	 * @return string
	 */
	public static function api_endpoint()
	{
		$api_endpoint_url = 'https://bo.jeweltheme.com';
		$api_endpoint     = apply_filters('jltwp_adminify_endpoint', $api_endpoint_url);

		return trailingslashit($api_endpoint);
	}

	/**
	 * CRM Endpoint
	 *
	 * @return string
	 */
	public static function crm_endpoint()
	{
		$crm_endpoint_url = 'https://bo.jeweltheme.com/wp-json/jlt-api/v1/subscribe'; // Endpoint .
		$crm_endpoint     = apply_filters('jltwp_adminify_crm_crm_endpoint', $crm_endpoint_url);

		return trailingslashit($crm_endpoint);
	}

	/**
	 * CRM Endpoint
	 *
	 * @return string
	 */
	public static function crm_survey_endpoint()
	{
		$crm_feedback_endpoint_url = 'https://bo.jeweltheme.com/wp-json/jlt-api/v1/survey'; // Endpoint .
		$crm_feedback_endpoint     = apply_filters('jltwp_adminify_crm_crm_endpoint', $crm_feedback_endpoint_url);

		return trailingslashit($crm_feedback_endpoint);
	}

	/**
	 * Function jltwp_adminify_wp_timezone_string() compability for wp version < 5.3
	 *
	 * @return string
	 */
	public static function jltwp_adminify_wp_timezone_string()
	{
		$timezone_string = get_option('timezone_string');

		if ($timezone_string) {
			return $timezone_string;
		}

		$offset  = (float) get_option('gmt_offset');
		$hours   = (int) $offset;
		$minutes = ($offset - $hours);

		$sign      = ($offset < 0) ? '-' : '+';
		$abs_hour  = abs($hours);
		$abs_mins  = abs($minutes * 60);
		$tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);

		return $tz_offset;
	}

	/**
	 * Get Merged Data
	 *
	 * @param [type] $data .
	 * @param string $start_date .
	 * @param string $end_data .
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public static function get_merged_data($data, $start_date = '', $end_data = '')
	{
		$_data = shortcode_atts(
			array(
				'image_url'        => WP_ADMINIFY_ASSETS_IMAGE . '/promo-image.png',
				'start_date'       => $start_date,
				'end_date'         => $end_data,
				'counter_time'     => '',
				'is_campaign'      => 'false',
				'button_text'      => 'Get Premium',
				'button_url'       => 'https://wpadminify.com/pricing',
				'btn_color'        => '#CC22FF',
				'notice'           => '',
				'notice_timestamp' => '',
			),
			$data
		);

		if (empty($_data['image_url'])) {
			$_data['image_url'] = WP_ADMINIFY_ASSETS_IMAGE . '/promo-image.png';
		}

		return $_data;
	}


	/**
	 * wp_kses attributes map
	 *
	 * @param array $attrs .
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public static function wp_kses_atts_map(array $attrs)
	{
		return array_fill_keys(array_values($attrs), true);
	}

	/**
	 * Custom method
	 *
	 * @param [type] $content .
	 *
	 * @author Jewel Theme <support@jeweltheme.com>
	 */
	public static function wp_kses_custom($content)
	{
		$allowed_tags = wp_kses_allowed_html('post');

		$custom_tags = array(
			'select'         => self::wp_kses_atts_map(array('class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'multiple', 'required', 'size')),
			'input'          => self::wp_kses_atts_map(array('class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'required', 'size', 'type', 'checked', 'readonly', 'placeholder', 'value', 'maxlength', 'min', 'max', 'multiple', 'pattern', 'step', 'autocomplete')),
			'textarea'       => self::wp_kses_atts_map(array('class', 'id', 'style', 'width', 'height', 'title', 'data', 'name', 'autofocus', 'disabled', 'required', 'rows', 'cols', 'wrap', 'maxlength')),
			'option'         => self::wp_kses_atts_map(array('class', 'id', 'label', 'disabled', 'label', 'selected', 'value')),
			'optgroup'       => self::wp_kses_atts_map(array('disabled', 'label', 'class', 'id')),
			'form'           => self::wp_kses_atts_map(array('class', 'id', 'data', 'style', 'width', 'height', 'accept-charset', 'action', 'autocomplete', 'enctype', 'method', 'name', 'novalidate', 'rel', 'target')),
			'svg'            => self::wp_kses_atts_map(array('class', 'xmlns', 'viewbox', 'width', 'height', 'fill', 'aria-hidden', 'aria-labelledby', 'role')),
			'rect'           => self::wp_kses_atts_map(array('rx', 'width', 'height', 'fill')),
			'path'           => self::wp_kses_atts_map(array('d', 'fill')),
			'g'              => self::wp_kses_atts_map(array('fill')),
			'defs'           => self::wp_kses_atts_map(array('fill')),
			'linearGradient' => self::wp_kses_atts_map(array('id', 'x1', 'x2', 'y1', 'y2', 'gradientUnits')),
			'stop'           => self::wp_kses_atts_map(array('stop-color', 'offset', 'stop-opacity')),
			'style'          => self::wp_kses_atts_map(array('type')),
			'div'            => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'ul'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'li'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'label'          => self::wp_kses_atts_map(array('class', 'for')),
			'span'           => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h1'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h2'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h3'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h4'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h5'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'h6'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'a'              => self::wp_kses_atts_map(array('class', 'href', 'target', 'rel')),
			'p'              => self::wp_kses_atts_map(array('class', 'id', 'style', 'data')),
			'table'          => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'thead'          => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'tbody'          => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'tr'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'th'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'td'             => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'i'              => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'button'         => self::wp_kses_atts_map(array('class', 'id')),
			'nav'            => self::wp_kses_atts_map(array('class', 'id', 'style')),
			'time'           => self::wp_kses_atts_map(array('datetime')),
			'br'             => array(),
			'strong'         => array(),
			'style'          => array(),
			'img'            => self::wp_kses_atts_map(array('class', 'src', 'alt', 'height', 'width', 'srcset', 'id', 'loading')),
		);

		$allowed_tags = array_merge_recursive($allowed_tags, $custom_tags);

		return wp_kses(stripslashes_deep($content), $allowed_tags);
	}
}
