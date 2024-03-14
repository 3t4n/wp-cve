<?php
namespace Photonic_Plugin\Core;

class Front_End {
	private static $instance = null;

	private function __construct() {
		// Blank constructor, for now. Setting it up for singleton.
	}

	public static function get_instance(): Front_End {
		if (null === self::$instance) {
			self::$instance = new Front_End();
		}
		return self::$instance;
	}

	/**
	 * @return array
	 */
	public function get_localized_js_variables(): array {
		global $photonic_lightbox_no_loop, $photonic_slideshow_mode, $photonic_slideshow_interval,
			   $photonic_cb_transition_effect, $photonic_cb_transition_speed,
			   $photonic_fbox_title_position, $photonic_fb3_transition_effect, $photonic_fb3_transition_speed, $photonic_fb3_show_fullscreen, $photonic_enable_fb3_fullscreen,
			   $photonic_fb3_hide_thumbs, $photonic_enable_fb3_thumbnail, $photonic_fb3_disable_zoom, $photonic_fb3_disable_slideshow, $photonic_fb3_enable_download, $photonic_fb3_disable_right_click,
			   $photonic_lc_transition_effect, $photonic_lc_transition_speed_in, $photonic_lc_transition_speed_out, $photonic_lc_enable_shrink,
			   $photonic_lg_transition_effect, $photonic_lg_transition_speed, $photonic_disable_lg_download, $photonic_lg_hide_bars_delay,
			   $photonic_lg_mobile_show_controls, $photonic_lg_mobile_show_close, $photonic_lg_mobile_show_download,
			   $photonic_tile_spacing, $photonic_tile_min_height, $photonic_pphoto_theme, $photonic_pp_animation_speed,
			   $photonic_sp_download, $photonic_sp_hide_bars,
			   $photonic_enable_swipebox_mobile_bars, $photonic_sb_hide_mobile_close, $photonic_sb_hide_bars_delay,
			   $photonic_vb_display_vertical_scroll, $photonic_vb_title_position, $photonic_vb_title_style,
			   $photonic_lightbox_for_all, $photonic_lightbox_for_videos, $photonic_popup_panel_width, $photonic_deep_linking, $photonic_social_media,
			   $photonic_slideshow_prevent_autostart, $photonic_wp_slide_adjustment, $photonic_masonry_min_width, $photonic_mosaic_trigger_width,
			   $photonic_debug_on;

		global $photonic_js_variables;

		$slideshow_library = esc_js(Photonic::$library);
		$js_array = [
			'ajaxurl'    => admin_url('admin-ajax.php'),
			'plugin_url' => PHOTONIC_URL,
			'debug_on'   => !empty($photonic_debug_on),

			'slide_adjustment' => esc_js($photonic_wp_slide_adjustment ?: 'adapt-height-width'),

			'deep_linking' => esc_js($photonic_deep_linking ?? 'none'),
			'social_media' => isset($photonic_deep_linking) ? 'none' !== $photonic_deep_linking && empty($photonic_social_media) : '',

			'lightbox_library'     => $slideshow_library,
			'tile_spacing'         => (empty($photonic_tile_spacing) || !absint($photonic_tile_spacing)) ? 0 : absint($photonic_tile_spacing),
			'tile_min_height'      => (empty($photonic_tile_min_height) || !absint($photonic_tile_min_height)) ? 200 : absint($photonic_tile_min_height),
			'masonry_min_width'    => (empty($photonic_masonry_min_width) || !absint($photonic_masonry_min_width)) ? 200 : absint($photonic_masonry_min_width),
			'mosaic_trigger_width' => (empty($photonic_mosaic_trigger_width) || !absint($photonic_mosaic_trigger_width)) ? 200 : absint($photonic_mosaic_trigger_width),

			'slideshow_mode'     => isset($photonic_slideshow_mode) && 'on' === $photonic_slideshow_mode,
			'slideshow_interval' => (isset($photonic_slideshow_interval) && absint($photonic_slideshow_interval)) ? absint($photonic_slideshow_interval) : 5000,
			'lightbox_loop'      => empty($photonic_lightbox_no_loop),

			'gallery_panel_width' => (empty($photonic_popup_panel_width) || !absint($photonic_popup_panel_width) || absint($photonic_popup_panel_width) > 100) ? 80 : absint($photonic_popup_panel_width),

			'lightbox_for_all'    => !empty($photonic_lightbox_for_all),
			'lightbox_for_videos' => !empty($photonic_lightbox_for_videos),

			'slideshow_autostart' => !(isset($photonic_slideshow_prevent_autostart) && 'on' === $photonic_slideshow_prevent_autostart),

			'password_failed'    => esc_attr__('This album is password-protected. Please provide a valid password.', 'photonic'),
			'incorrect_password' => esc_attr__('Incorrect password.', 'photonic'),
			'maximize_panel'     => esc_attr__('Show', 'photonic'),
			'minimize_panel'     => esc_attr__('Hide', 'photonic'),
		];

		if ('colorbox' === $slideshow_library) {
			$lb_options = [
				'cb_transition_effect' => esc_js($photonic_cb_transition_effect ?: 'elastic'),
				'cb_transition_speed'  => (isset($photonic_cb_transition_speed) && absint($photonic_cb_transition_speed)) ? absint($photonic_cb_transition_speed) : 350,
			];
		}
		elseif ('fancybox' === $slideshow_library || 'fancybox2' === $slideshow_library) {
			$lb_options = [
				'fbox_show_title'     => 'none' !== $photonic_fbox_title_position,
				'fbox_title_position' => 'none' === $photonic_fbox_title_position ? 'outside' : esc_js($photonic_fbox_title_position),
			];
		}
		elseif ('fancybox3' === $slideshow_library) {
			$lb_options = [
				'fb3_transition_effect'   => esc_js($photonic_fb3_transition_effect ?: 'zoom'),
				'fb3_transition_speed'    => (isset($photonic_fb3_transition_speed) && absint($photonic_fb3_transition_speed)) ? absint($photonic_fb3_transition_speed) : 366,
				'fb3_fullscreen_button'   => !empty($photonic_fb3_show_fullscreen),
				'fb3_fullscreen'          => isset($photonic_enable_fb3_fullscreen) && 'on' === $photonic_enable_fb3_fullscreen ? true : false,
				'fb3_thumbs_button'       => empty($photonic_fb3_hide_thumbs),
				'fb3_thumbs'              => isset($photonic_enable_fb3_thumbnail) && 'on' === $photonic_enable_fb3_thumbnail ? true : false,
				'fb3_zoom'                => empty($photonic_fb3_disable_zoom),
				'fb3_slideshow'           => empty($photonic_fb3_disable_slideshow),
				'fb3_download'            => !empty($photonic_fb3_enable_download),
				'fb3_disable_right_click' => !empty($photonic_fb3_disable_right_click),
			];
		}
		elseif ('lightcase' === $slideshow_library) {
			$lb_options = [
				'lc_transition_effect'    => esc_js($photonic_lc_transition_effect ?: 'scrollHorizontal'),
				'lc_transition_speed_in'  => (isset($photonic_lc_transition_speed_in) && absint($photonic_lc_transition_speed_in)) ? absint($photonic_lc_transition_speed_in) : 350,
				'lc_transition_speed_out' => (isset($photonic_lc_transition_speed_out) && absint($photonic_lc_transition_speed_out)) ? absint($photonic_lc_transition_speed_out) : 250,
				'lc_disable_shrink'       => empty($photonic_lc_enable_shrink),
			];
		}
		elseif ('lightgallery' === $slideshow_library) {
			$lightgallery_plugins = Photonic::get_lightgallery_plugins();
			$lightgallery_plugins = implode(',', $lightgallery_plugins);

			$lb_options = [
				'lg_plugins'           => $lightgallery_plugins,
				'lg_transition_effect' => esc_js($photonic_lg_transition_effect ?: 'lg-slide'),
				'lg_enable_download'   => empty($photonic_disable_lg_download),
				'lg_hide_bars_delay'   => (isset($photonic_lg_hide_bars_delay) && absint($photonic_lg_hide_bars_delay)) ? absint($photonic_lg_hide_bars_delay) : 6000,
				'lg_transition_speed'  => (isset($photonic_lg_transition_speed) && absint($photonic_lg_transition_speed)) ? absint($photonic_lg_transition_speed) : 600,
				'lg_mobile_controls'   => !empty($photonic_lg_mobile_show_controls),
				'lg_mobile_close'      => !empty($photonic_lg_mobile_show_close),
				'lg_mobile_download'   => !empty($photonic_lg_mobile_show_download),
			];
		}
		elseif ('prettyphoto' === $slideshow_library) {
			$lb_options = [
				'pphoto_theme'           => esc_js($photonic_pphoto_theme ?? 'pp_default'),
				'pphoto_animation_speed' => esc_js($photonic_pp_animation_speed ?: 'fast'),
			];
		}
		elseif ('spotlight' === $slideshow_library) {
			$lb_options = [
				'sp_download'  => !empty($photonic_sp_download),
				'sp_hide_bars' => $photonic_sp_hide_bars,
			];
		}
		elseif ('swipebox' === $slideshow_library) {
			$lb_options = [
				'enable_swipebox_mobile_bars' => !empty($photonic_enable_swipebox_mobile_bars),
				'sb_hide_mobile_close'        => !empty($photonic_sb_hide_mobile_close),
				'sb_hide_bars_delay'          => (isset($photonic_sb_hide_bars_delay) && absint($photonic_sb_hide_bars_delay)) ? absint($photonic_sb_hide_bars_delay) : 0,
			];
		}
		elseif ('venobox' === $slideshow_library) {
			$lb_options = [
				'vb_disable_vertical_scroll' => empty($photonic_vb_display_vertical_scroll),
				'vb_title_position'          => esc_js($photonic_vb_title_position),
				'vb_title_style'             => esc_js($photonic_vb_title_style),
			];
		}

		if (!empty($lb_options)) {
			$js_array = array_merge($js_array, $lb_options);
		}

		$photonic_js_variables = $js_array;
		return $js_array;
	}

	/**
	 * @param array $attr
	 * @return string
	 */
	public function get_gallery_images(array $attr): string {
		global $photonic_nested_shortcodes, $photonic_load_mode, $photonic_thumbnail_style;
		$attr = array_merge(
			[
				// Especially for Photonic
				'type'    => 'default',  // default, flickr, smugmug, google, zenfolio, instagram
				'style'   => 'default',  // default, strip-below, strip-above, strip-right, strip-left, no-strip, launch
				'display' => 'local',
			],
			$attr
		);

		if ($photonic_nested_shortcodes) {
			$attr = array_map('do_shortcode', $attr);
		}

		$type = strtolower($attr['type']);

		if ('picasa' === $type) {
			$message = esc_html__('Google has deprecated the Picasa API. Please consider switching over to Google Photos', 'photonic');
			return "<div class='photonic-error'>\n\t<span class='photonic-error-icon photonic-icon'>&nbsp;</span>\n\t<div class='photonic-message'>\n\t\t$message\n\t</div>\n</div>\n";
		}

		if ('500px' === $type) {
			$message = esc_html__('The API for 500px.com is no longer available for public use.', 'photonic');
			return "<div class='photonic-error'>\n\t<span class='photonic-error-icon photonic-icon'>&nbsp;</span>\n\t<div class='photonic-message'>\n\t\t$message\n\t</div>\n</div>\n";
		}

		$layout = ('default' === $type || 'wp' === $type)
			? $attr['style']
			: (!empty($attr['layout'])
				? $attr['layout']
				: $photonic_thumbnail_style);
		$layout = esc_attr($layout);

		$lazy_allowed = in_array($type, ['flickr', 'smugmug', 'google', 'zenfolio', 'instagram'], true)
			&& in_array($layout, ['square', 'circle', 'random', 'masonry', 'mosaic'], true);

		if (!empty($attr['show_gallery']) && $lazy_allowed) { // Lazy button not for WP galleries
			$images = $this->get_lazy_load_button($attr, 'show_gallery');
		}
		elseif ((('js' === $photonic_load_mode && (empty($attr['load_mode']) || 'js' === trim(esc_attr($attr['load_mode'])))) || ('php' === $photonic_load_mode && (!empty($attr['load_mode']) && 'js' === trim(sanitize_text_field($attr['load_mode'])))))
			&& $lazy_allowed) { // Lazy button not for WP galleries
			$attr['load_mode'] = 'js'; // Need to set this for cases where the shortcode didn't have the setting; get_lazy_load_button fails
			$images = $this->get_lazy_load_button($attr, 'js_load');
		}
		else {
			$gallery = new Gallery($attr);
			$images = $gallery->get_contents();
		}

		return $images;
	}

	public function get_lazy_load_button($attr = [], $button_type = 'show_gallery'): string {
		$types = [
			'show_gallery' => 'show_gallery',
			'js_load'      => 'load_mode'
		];
		$type = $types[$button_type];
		$button = esc_attr($attr[$type]);
		$button_attr = [];

		if (!empty($attr["{$type}_button_type"]) && 'image' === $attr["{$type}_button_type"] && !empty($attr["{$type}_button_image"])) {
			$button_attr['type'] = 'image';
			$button_attr['alt'] = $button;
			$button_attr['src'] = esc_url($attr["{$type}_button_image"]);
		}
		else {
			$button_attr['type'] = 'button';
			$button_attr['value'] = $button;
		}

		$class = str_replace('_', '-', $button_type);
		$button_attr['class'] = "photonic-{$class}-button";

		unset($attr["{$type}"]);
		unset($attr["{$type}_button_type"]);
		unset($attr["{$type}_button_image"]);

		$attr['load_mode'] = 'php'; // doesn't matter what the $button_type is.

		$attr_str = http_build_query($attr);
		$button_attr['data-photonic-shortcode'] = $attr_str;

		$input_attr = [];
		foreach ($button_attr as $name => $value) {
			$input_attr[] = "$name='$value'";
		}
		$input_attr = implode(' ', $input_attr);

		return "<input $input_attr/>";
	}

	/**
	 * Constructs the CSS for a "background" option
	 *
	 * @param $option
	 * @return string
	 */
	public function get_bg_css($option): string {
		global ${$option};
		$option_val = ${$option};
		if (!is_array($option_val)) {
			$val_array = [];
			$vals = explode(';', $option_val);
			foreach ($vals as $val) {
				if ('' === trim($val)) {
					continue;
				}
				$pair = explode('=', $val);
				$val_array[$pair[0]] = $pair[1];
			}
			$option_val = $val_array;
		}
		$bg_string = "";
		$bg_rgba_string = "";
		if (!isset($option_val['colortype']) || 'transparent' === $option_val['colortype']) {
			$bg_string .= " transparent ";
		}
		else {
			if (isset($option_val['color'])) {
				if ('#' === substr($option_val['color'], 0, 1)) {
					$color_string = sanitize_hex_color_no_hash(substr($option_val['color'], 1));
				}
				else {
					$color_string = sanitize_hex_color($option_val['color']);
				}
				$rgb_str_array = [];
				if (3 === strlen($color_string)) {
					$rgb_str_array[] = substr($color_string, 0, 1) . substr($color_string, 0, 1);
					$rgb_str_array[] = substr($color_string, 1, 1) . substr($color_string, 1, 1);
					$rgb_str_array[] = substr($color_string, 2, 1) . substr($color_string, 2, 1);
				}
				else {
					$rgb_str_array[] = substr($color_string, 0, 2);
					$rgb_str_array[] = substr($color_string, 2, 2);
					$rgb_str_array[] = substr($color_string, 4, 2);
				}
				$rgb_array = [];
				$rgb_array[] = hexdec($rgb_str_array[0]);
				$rgb_array[] = hexdec($rgb_str_array[1]);
				$rgb_array[] = hexdec($rgb_str_array[2]);
				$rgb_string = implode(',', $rgb_array);
				$rgb_string = ' rgb(' . $rgb_string . ') ';

				if (isset($option_val['trans'])) {
					$bg_rgba_string = $bg_string;
					$transparency = (int)$option_val['trans'];
					if (0 !== $transparency) {
						$trans_dec = $transparency / 100;
						$rgba_string = implode(',', $rgb_array);
						$rgba_string = ' rgba(' . $rgba_string . ',' . $trans_dec . ') ';
						$bg_rgba_string .= $rgba_string;
					}
				}

				$bg_string .= $rgb_string;
			}
		}
		if (isset($option_val['image']) && '' !== trim($option_val['image'])) {
			$bg_string .= " url(" . esc_url($option_val['image']) . ") ";
			$bg_string .= $option_val['position'] . " " . $option_val['repeat'];

			if ('' !== trim($bg_rgba_string)) {
				$bg_rgba_string .= " url(" . esc_url($option_val['image']) . ") ";
				$bg_rgba_string .= $option_val['position'] . " " . $option_val['repeat'];
			}
		}

		if ('' !== trim($bg_string)) {
			$bg_string = "background: " . $bg_string . " !important;\n";
			if ('' !== trim($bg_rgba_string)) {
				$bg_string .= "\tbackground: " . $bg_rgba_string . " !important;\n";
			}
		}
		return $bg_string;
	}

	/**
	 * Generates the CSS for borders. Each border, top, right, bottom and left is generated as a separate line.
	 *
	 * @param $option
	 * @return string
	 */
	public function get_border_css($option): string {
		global ${$option};
		$option_val = ${$option};
		if (!is_array($option_val)) {
			$option_val = stripslashes($option_val);
			$edge_array = $this->build_edge_array($option_val);
			$option_val = $edge_array;
		}
		$border_string = '';
		foreach ($option_val as $edge => $selections) {
			$border_string .= "\tborder-$edge: ";
			if (!isset($selections['style'])) {
				$selections['style'] = 'none';
			}
			if ('none' === $selections['style']) {
				$border_string .= "none";
			}
			else {
				if (isset($selections['border-width'])) {
					$border_string .= $selections['border-width'];
				}
				if (isset($selections['border-width-type'])) {
					$border_string .= $selections['border-width-type'];
				}
				else {
					$border_string .= "px";
				}
				$border_string .= " " . $selections['style'] . " ";
				if ('transparent' === $selections['colortype']) {
					$border_string .= "transparent";
				}
				else {
					if ('#' === substr($selections['color'], 0, 1)) {
						$border_string .= $selections['color'];
					}
					else {
						$border_string .= '#' . $selections['color'];
					}
				}
			}
			$border_string .= ";\n";
		}
		return "\n" . $border_string;
	}

	private function build_edge_array($option_val): array {
		$edge_array = [];
		$edges = explode('||', $option_val);
		foreach ($edges as $edge_val) {
			if ('' !== trim($edge_val)) {
				$edge_options = explode('::', trim($edge_val));
				if (is_array($edge_options) && count($edge_options) > 1) {
					$val_array = [];
					$vals = explode(';', $edge_options[1]);
					foreach ($vals as $val) {
						$pair = explode('=', $val);
						if (is_array($pair) && count($pair) > 1) {
							$val_array[$pair[0]] = $pair[1];
						}
					}
					$edge_array[$edge_options[0]] = $val_array;
				}
			}
		}
		return $edge_array;
	}
}
