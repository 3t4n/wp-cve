<?php
if (!class_exists('PopupAllyDisplaySettings')) {
	class PopupAllyDisplaySettings {
		const SETTING_KEY_DISPLAY = '_popupally_setting_general';

		private static $default_popup_display_settings = null;
		private static $default_display_settings = null;

		private static $display_setting_template_replace = array('timed-popup-delay', 'priority', 'cookie-duration');
		private static $display_setting_template_regular_selection_replace = array('embedded-location');
		private static $display_setting_template_checked_replace = array('timed', 'enable-exit-intent-popup', 'enable-embedded', 'show-all');
		private static $display_setting_template_embedded_locations = array('none', 'post-start', 'post-end', 'page-end');

		public static function do_activation_actions() {
			delete_transient(self::SETTING_KEY_DISPLAY);
		}
		public static function do_deactivation_actions() {
			delete_transient(self::SETTING_KEY_DISPLAY);
		}
		public static function show_display_settings() {
			$display = self::get_display_settings();
			$style = PopupAllyStyleSettings::get_style_settings();

			$templates = self::load_display_template_arguments();
			echo '<h3>Display Settings</h3>';

			foreach ($style as $id => $style_setting) {
				$setting = $display[$id];
				$display_detail = self::generate_individual_display_code($id, $setting, $style_setting, $templates);
				echo $display_detail;
			}
			
			echo '<div class="popupally-setting-section"><div class="popupally-setting-section-header">Want more popups? <a class="popupally-trial-link" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">Get PopupAlly Pro now!</a></div></div></div>';
		}
		private static $cached_display_settings = null;
		public static function get_display_settings() {
			if (self::$cached_display_settings === null) {
				$display = get_transient(self::SETTING_KEY_DISPLAY);
				$default_display_settings = self::get_default_display_setting();

				if (!is_array($display)) {
					$display = get_option(self::SETTING_KEY_DISPLAY, $default_display_settings);

					set_transient(self::SETTING_KEY_DISPLAY, $display, PopupAlly::CACHE_PERIOD);
				}
				if (!is_array($display)) {
					$display = $default_display_settings;
				}
				foreach ($default_display_settings as $key => $value) {
					if (!isset($display[$key])) {
						$display[$key] = $value;
					}
				}

				$style_settings = PopupAllyStyleSettings::get_style_settings();
				foreach($display as $id => $setting) {
					if (is_int($id)) {
						$display[$id] = self::merge_default_display_settings($id, $setting, $style_settings[$id]);
					}
				}
				self::$cached_display_settings = $display;
			}
			return self::$cached_display_settings;
		}
		private static function get_default_display_setting() {
			if (self::$default_display_settings === null) {
				self::$default_display_settings = array(1 => self::get_default_popup_display_setting(1),
					2 => self::get_default_popup_display_setting(2));
			}
			return self::$default_display_settings;
		}

		public static function get_default_popup_display_setting($id = false) {
			if (self::$default_popup_display_settings === null) {
				self::$default_popup_display_settings = array('timed' => 'false',
					'timed-popup-delay' => -1,
					'enable-exit-intent-popup' => 'false',
					'enable-embedded' => 'false',
					'embedded-location' => 'none',
					'priority' => 0,
					'show-all' => 'false',
					'include' => array(),
					'exclude' => array(),
					'cookie-duration' => 14,
					'thank-you' => array(),
					'is-open' => 'false',
				);
			}
			if (false === $id) {
				return self::$default_popup_display_settings;
			}
			return PopupAllyUtilites::customize_parameter_array(self::$default_popup_display_settings, $id);
		}

		public static function merge_default_display_settings($id, $display, $style) {
			$display = wp_parse_args($display, self::get_default_popup_display_setting($id));

			// ensure backwards compatibility by converting all arrays to new ones
			$display['include'] = self::convert_array_list($display['include']);
			$display['exclude'] = self::convert_array_list($display['exclude']);
			$display['thank-you'] = self::convert_array_list($display['thank-you']);
			return $display;
		}
		public static function sanitize_display_settings($input) {
			$to_remove = array();	// used to remove any unwanted <input> that got captured by the serialization
			foreach ($input as $id => &$setting) {
				if (is_int($id)) {
					$setting = wp_parse_args($setting, self::get_default_popup_display_setting($id));
					$setting['timed-popup-delay'] = intval($setting['timed-popup-delay']);
					$setting['priority'] = intval($setting['priority']);
					$setting['cookie-duration'] = intval($setting['cookie-duration']);
				} else {
					$to_remove []= $id;
				}
			}
			foreach ($to_remove as $id) {
				unset($input[$id]);
			}
			update_option(self::SETTING_KEY_DISPLAY, $input);
			set_transient(self::SETTING_KEY_DISPLAY, $input, PopupAlly::CACHE_PERIOD);
			return $input;
		}
		public static function load_display_template_arguments() {
			$advanced = PopupAllyAdvancedSettings::get_advanced_settings();
			$result = array();
			$result['disable'] = file_get_contents(dirname(__FILE__) . '/../../frontend/disable.php');
			$result['page_template'] = $page_template = self::generate_page_template($advanced);
			$result['display_selection_template'] = self::generate_page_post_selection_template($advanced, $page_template);
			$result['display_detail_template'] = file_get_contents(dirname(__FILE__) . '/setting-display-popup-template.php');
			$result['host_url'] = esc_attr(get_bloginfo('url'));

			$style_settings = PopupAllyStyleSettings::get_style_settings();
			$popup_selection_code = '<option s--select-0--d value="0">None</option>';
			foreach ($style_settings as $popup_id => $style_setting) {
				$popup_selection_code .= '<option s--select-' . $popup_id . '--d value="' . $popup_id . '">' . esc_html($popup_id . '. ' . $style_setting['name']) . '</option>';
			}
			$result['popup_selection'] = $popup_selection_code;
			return $result;
		}

		// <editor-fold defaultstate="collapsed" desc="Page checkbox selection generation">
		/* generate separately because it is also used for Thank You page selection */
		private static function generate_page_template($advanced) {
			if ($advanced['max-page'] < 0) {
				$pages = PopupAllySettingShared::get_all_hierarchical_posts('page');
			} else {
				$pages = PopupAllySettingShared::get_all_hierarchical_posts('page', $advanced['max-page']);
			}
			return self::generate_page_checkbox_template($pages);
		}
		private static function generate_page_post_selection_template($advanced, $page_template) {
			$categories = get_categories(array('hide_empty' => false));
			$category_template = self::generate_category_template($categories);

			$page_selection_template = file_get_contents(dirname(__FILE__) . '/setting-display-page-selection-template.php');
			$page_selection_template = str_replace('{{page-selection}}', $page_template, $page_selection_template);

			$page_category_selection = str_replace('{{type}}', 'page', $category_template);
			$page_category_selection = str_replace('{{is-post}}', '', $page_category_selection);
			$page_selection_template = str_replace('{{category-page-selection}}', $page_category_selection, $page_selection_template);

			/* generate post code */
			$posts = PopupAllySettingShared::get_all_posts('post', false, $advanced['max-post']);
			$post_template = self::generate_post_checkbox_template($posts);

			$post_selection_template = file_get_contents(dirname(__FILE__) . '/setting-display-post-selection-template.php');
			$post_selection_template = str_replace('{{post-selection}}', $post_template, $post_selection_template);

			return '<td>' . $page_selection_template . '</td><td>' . $post_selection_template . '</td>';
		}
		// </editor-fold>

		private static function customize_selection_template($template, $selection_type, $setting) {
			$template = str_replace('{{selection-type}}', $selection_type, $template);
			foreach($setting as $selected => $value){
				$template = str_replace('c--' . $selected . '--d', 'checked="checked"', $template);
			}
			return $template;
		}
		public static function generate_individual_display_code($id, $display, $style, $templates) {
			$disable = $templates['disable'];
			$page_template = $templates['page_template'];
			$display_selection_template = $templates['display_selection_template'];
			$display_detail_template = $templates['display_detail_template'];
			$host_url = $templates['host_url'];

			$display_detail = $display_detail_template;
			foreach(self::$display_setting_template_replace as $replace) {
				$display_detail = str_replace("{{{$replace}}}", esc_attr($display[$replace]), $display_detail);
			}
			foreach(self::$display_setting_template_checked_replace as $replace) {
				if ($display[$replace] === 'true') {
					$display_detail = str_replace("{{{$replace}}}", 'checked="checked"', $display_detail);
				} else {
					$display_detail = str_replace("{{{$replace}}}", '', $display_detail);
				}
			}
			foreach(self::$display_setting_template_regular_selection_replace as $replace) {
				$display_detail = str_replace('s--' . $replace . '--' . $display[$replace]. '--d', 'selected="selected"', $display_detail);
			}
			$display_detail = str_replace("{{selected_item_opened}}", $display['is-open'] === 'true'?'popupally-item-opened':'', $display_detail);
			$display_detail = str_replace("{{selected_item_checked}}", $display['is-open'] === 'true'?'checked="checked"':'', $display_detail);
			$display_detail = str_replace("{{name}}", $style['name'], $display_detail);
			$display_detail = str_replace("{{cookie-js}}", esc_attr(str_replace('##cookie_name##', $style['cookie-name'], $disable)), $display_detail);
			$display_detail = str_replace("{{show-thank-you}}", empty($display['thank-you']) ? '' : 'checked="checked"', $display_detail);
			$display_detail = str_replace("{{show-thank-you-hide}}", empty($display['thank-you']) ? 'style="display:none;"' : '', $display_detail);
			$display_detail = str_replace("{{host-url}}", $host_url, $display_detail);

			$has_display_option_selected = 'true' === $display['timed'] || 'true' === $display['enable-exit-intent-popup'] ||
					'true' === $display['enable-embedded'];
			if ($has_display_option_selected) {
				$display_detail = str_replace("{{display-page-selection}}", '', $display_detail);
			} else {
				$display_detail = str_replace("{{display-page-selection}}", 'style="display:none;"', $display_detail);
			}

			$display_detail = str_replace("{{thank-you-page-selection}}", self::customize_selection_template($page_template, 'thank-you', $display['thank-you']), $display_detail);

			$display_detail = str_replace('{{include-selection}}', self::customize_selection_template($display_selection_template, 'include', $display['include']), $display_detail);
			$display_detail = str_replace('{{exclude-selection}}', self::customize_selection_template($display_selection_template, 'exclude', $display['exclude']), $display_detail);

			$display_detail = preg_replace('/c--.*?--d/', '', $display_detail);
			$display_detail = preg_replace('/s--.*?--d/', '', $display_detail);

			foreach(self::$display_setting_template_embedded_locations as $location) {
				$display_detail = str_replace("{{embedded-location-" . $location . "}}", ($display['embedded-location'] == $location ? 'selected="selected"' : ''), $display_detail);
			}

			$display_detail = str_replace("{{id}}", $id, $display_detail);
			$display_detail = PopupAllySettingShared::replace_all_toggle($display_detail, $display);
			return $display_detail;
		}
		private static function generate_post_checkbox_template($posts) {
			$post_template = '';
			if ($posts) {
				foreach ($posts as $post) {
					$post_template .= '<li><input class="{{selection-type}}-page-checkbox {{selection-type}}-post-{{id}}" c--' . $post->ID . '--d id="{{selection-type}}-{{id}}-' . $post->ID .
							'" type="checkbox" value="true" name="[{{id}}][{{selection-type}}][' . $post->ID . ']"><label for="{{selection-type}}-{{id}}-' . $post->ID . '">' .
							esc_attr($post->post_title) . ' (' . $post->ID . ')</label></li>';
				}
			}
			return $post_template;
		}
		private static function generate_category_template($categories) {
			$category_selection = '';
			foreach ($categories as $category) {
				$category_selection .= '<li><input class="{{selection-type}}-page-checkbox {{selection-type}}-{{type}}-{{id}}" c--category-{{is-post}}' . $category->cat_ID . '--d id="{{selection-type}}-{{id}}-category-{{is-post}}' . $category->cat_ID .
						'" type="checkbox" value="true" name="[{{id}}][{{selection-type}}][category-{{is-post}}' . $category->cat_ID .
						']"><label for="{{selection-type}}-{{id}}-category-{{is-post}}' . $category->cat_ID . '">' . esc_attr($category->name) . '</label></li>';
			}
			return $category_selection;
		}
		private static function generate_page_checkbox_template($pages) {
			$depth = array();
			$page_template = '';
			if ($pages) {
				for ($i = 0; $i < count($pages); ++$i) {
					$page = $pages[$i];
					if (0 == $page->post_parent) {
						if (count($depth) > 0) {
							$page_template .= str_repeat('</ul></li>', count($depth));
							$depth = array();
						}
					} elseif (end($depth) === $page->post_parent) {
					} elseif (in_array($page->post_parent, $depth)) {
						while(end($depth) !== $page->post_parent) {
							array_pop($depth);
							$page_template .= '</ul></li>';
						}
					} else {
						$depth []= $page->post_parent;
						$page_template .= '<ul>';
					}
					$has_child_code = '';
					if ($i + 1 < count($pages)) {
						if ($pages[$i+1]->post_parent === $page->ID) {
							$has_child_code = '<input type="checkbox" value="closed" class="checkbox-parent-expand" id="expand-{{selection-type}}-{{id}}-' . $page->ID .
							'" /><label for="expand-{{selection-type}}-{{id}}-' . $page->ID .'"></label>';
						}
					}
					$page_template .= '<li>' . $has_child_code .
						'<input class="{{selection-type}}-page-checkbox {{selection-type}}-page-{{id}}" c--' . $page->ID . '--d id="{{selection-type}}-{{id}}-' . $page->ID .
							'" type="checkbox" value="true" name="[{{id}}][{{selection-type}}][' . $page->ID . ']"><label for="{{selection-type}}-{{id}}-' . $page->ID . '">' .
							esc_attr($page->post_title) . ' (' . $page->ID . ')</label>';
				}
			}

			if (count($depth) > 0) {
				while(count($depth) > 0) {
					array_pop($depth);
					$page_template .= '</ul></li>';
				}
			} else {
				$page_template .= '</li>';
			}
			return $page_template;
		}
		private static function convert_array_list($list) {
			if (!is_array($list)) {
				return array();
			}
			if (empty($list) || reset($list) === 'true') {
				return $list;
			}
			$result = array();
			foreach($list as $id) {
				$result[$id] = 'true';
			}
			return $result;
		}
	}
}