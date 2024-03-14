<?php

namespace Photonic_Plugin\Admin;

if (!current_user_can('edit_theme_options')) {
	wp_die(esc_html__('You are not authorized to use this capability.', 'photonic'));
}

use Photonic_Plugin\Core\Photonic;

use Photonic_Plugin\Options\Defaults;
use Photonic_Plugin\Options\Flickr;
use Photonic_Plugin\Options\Generic;
use Photonic_Plugin\Options\Google;
use Photonic_Plugin\Options\Instagram;
use Photonic_Plugin\Options\Lightbox;
use Photonic_Plugin\Options\SmugMug;
use Photonic_Plugin\Options\Zenfolio;

require_once 'Admin_Page.php';

class Options_Manager extends Admin_Page {
	private $options;
	private $tab;
	private $tab_options;
	private $reverse_options;
	private $shown_options;
	private $option_defaults;
	private $allowed_values;
	private $hidden_options;
	private $nested_options;
	private $displayed_sections;
	private $option_structure;
	private $previous_displayed_section;
	private $file;
	private $tab_name;
	private $core;

	/**
	 * Options_Manager constructor.
	 *
	 * @param $file
	 * @param Photonic $core
	 */
	public function __construct($file, $core) {
		global $photonic_setup_options;
		$options_page_array = [
			'Generic.php'   => Generic::get_instance()->get_options(),
			'Flickr.php'    => Flickr::get_instance()->get_options(),
			'Google.php'    => Google::get_instance()->get_options(),
			'SmugMug.php'   => SmugMug::get_instance()->get_options(),
			'Zenfolio.php'  => Zenfolio::get_instance()->get_options(),
			'Instagram.php' => Instagram::get_instance()->get_options(),
			'Lightbox.php'  => Lightbox::get_instance()->get_options(),
		];

		$tab_name_array = [
			'Generic.php'   => 'Generic Options',
			'Flickr.php'    => 'Flickr Options',
			'Google.php'    => 'Google Photos Options',
			'SmugMug.php'   => 'SmugMug Options',
			'Zenfolio.php'  => 'Zenfolio Options',
			'Instagram.php' => 'Instagram Options',
			'Lightbox.php'  => 'Lightbox Options',
		];

		$this->core = $core;
		$this->file = $file;
		$this->tab = 'Generic.php';
		if (isset($_REQUEST['tab']) && array_key_exists($_REQUEST['tab'], $tab_name_array)) {
			$this->tab = sanitize_text_field($_REQUEST['tab']);
		}

		$this->tab_options = $options_page_array[$this->tab];
		$this->tab_name = $tab_name_array[$this->tab];
		$this->options = $photonic_setup_options;
		$this->reverse_options = [];
		$this->nested_options = [];
		$this->displayed_sections = 0;
		$this->option_structure = $this->get_option_structure();

		$all_options = get_option('photonic_options');
		if (!isset($all_options)) {
			$this->hidden_options = [];
		}
		else {
			$this->hidden_options = $all_options;
		}

		foreach ($this->tab_options as $option) {
			if (isset($option['id'])) {
				$this->shown_options[] = $option['id'];
				if (isset($this->hidden_options[$option['id']])) {
					unset($this->hidden_options[$option['id']]);
				}
			}
		}

		$defaults = Defaults::get_options();
		foreach ($photonic_setup_options as $option) {
			if (isset($option['category']) && !isset($this->nested_options[$option['category']])) {
				$this->nested_options[$option['category']] = [];
			}

			if (isset($option['id'])) {
				$this->reverse_options[$option['id']] = $option['type'];

				$this->option_defaults[$option['id']] = $defaults[$option['id']];
				$option['std'] = $defaults[$option['id']];

				if (isset($option['options'])) {
					if ('radio-group' !== $option['type']) {
						$this->allowed_values[$option['id']] = $option['options'];
					}
					else {
						$this->allowed_values[$option['id']] = [];
						foreach ($option['options'] as $radio_group) {
							$this->allowed_values[$option['id']] = array_merge($this->allowed_values[$option['id']], $radio_group['options']);
						}
					}
				}

				if (isset($option['grouping'])) {
					if (!isset($this->nested_options[$option['grouping']])) {
						$this->nested_options[$option['grouping']] = [];
					}
					$this->nested_options[$option['grouping']][] = $option['id'];
				}
			}
		}
	}

	public function render_content() {
		$saved_options = get_option('photonic_options');
		if (isset($saved_options) && !empty($saved_options) && current_user_can('edit_theme_options')) {
			$generated_css = $this->core->generate_css(false);
			update_option('photonic_css', $generated_css);
			if (!empty($saved_options['css_in_file'])) {
				$this->save_css_to_file($generated_css);
			}
		}
		?>
		<div class="photonic-tabbed-options">
			<div class="photonic-header-nav">
				<div class="photonic-header-nav-top fix">
				</div>
				<div class="photonic-options-header-bar fix">
					<h2 class='nav-tab-wrapper'>
						<a class='nav-tab <?php echo ('Generic.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-generic' href='?page=photonic-options-manager&amp;tab=Generic.php'><span
									class="icon">&nbsp;</span> Generic Options</a>
						<a class='nav-tab <?php echo ('Flickr.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-flickr' href='?page=photonic-options-manager&amp;tab=Flickr.php'><span
									class="icon">&nbsp;</span> Flickr</a>
						<a class='nav-tab <?php echo ('SmugMug.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-smugmug' href='?page=photonic-options-manager&amp;tab=SmugMug.php'><span
									class="icon">&nbsp;</span> SmugMug</a>
						<a class='nav-tab <?php echo ('Google.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-google' href='?page=photonic-options-manager&amp;tab=Google.php'><span
									class="icon">&nbsp;</span> Google Photos</a>
						<a class='nav-tab <?php echo ('Zenfolio.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-zenfolio'
						   href='?page=photonic-options-manager&amp;tab=Zenfolio.php'><span class="icon">&nbsp;</span>
							Zenfolio</a>
						<a class='nav-tab <?php echo ('Instagram.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-instagram'
						   href='?page=photonic-options-manager&amp;tab=Instagram.php'><span class="icon">&nbsp;</span>
							Instagram</a>
						<a class='nav-tab <?php echo ('Lightbox.php' === $this->tab) ? 'nav-tab-active' : ''; ?>'
						   id='photonic-options-lightbox'
						   href='?page=photonic-options-manager&amp;tab=Lightbox.php'><span class="icon">&nbsp;</span>
							Lightboxes</a>
					</h2>
				</div>
			</div>
			<?php
			$option_structure = $this->get_option_structure();
			$group = substr($this->tab, 0, stripos($this->tab, '.'));

			echo "<div class='photonic-options photonic-options-" . esc_attr($group) . "' id='photonic-options'>";
			echo "<ul class='photonic-section-tabs'>";
			foreach ($option_structure as $l1_slug => $l1) {
				echo wp_kses_post("<li><a href='#$l1_slug'>" . $l1['name'] . "</a></li>\n");
			}
			echo "</ul>";

			do_settings_sections($this->file);

			$last_option = end($option_structure);
			$last_slug = key($option_structure);
			$this->show_buttons($last_slug, $last_option);

			echo "</form>\n";
			echo "</div><!-- /photonic-options-panel -->\n";

			echo "</div><!-- /#photonic-options -->\n";
			?>
		</div><!-- /#photonic-tabbed-options -->
		<?php
	}

	public function show_buttons($slug, $option) {
		if (!isset($option['buttons']) || ('no-buttons' !== $option['buttons'] && 'special-buttons' !== $option['buttons'])) {
			echo "<div class=\"photonic-button-bar photonic-button-bar-" . esc_attr($slug) . "\">\n";
			echo "<input name=\"photonic_options[submit-" . esc_attr($slug) . "]\" type='submit' value=\"Save page &ldquo;" . esc_attr($option['name']) . "&rdquo;\" class=\"button button-primary\" />\n";
			echo "<input name=\"photonic_options[submit-" . esc_attr($slug) . "]\" type='submit' value=\"Reset page &ldquo;" . esc_attr($option['name']) . "&rdquo;\" class=\"button\" />\n";
			echo "<input name=\"photonic_options[submit-" . esc_attr($slug) . "]\" type='submit' value=\"Delete all options\" class=\"button\" />\n";
			echo "</div><!-- photonic-button-bar -->\n";
		}
	}

	public function init() {
		foreach ($this->option_structure as $slug => $option) {
			if (!in_array($slug, Defaults::get_options_pages(), true)) {
				wp_die(esc_html("Invalid option section: $slug"));
			}
			// Since we are not including this file on all admin pages due to the size and associated load, register_setting cannot be done here.
			// It is instead done via Admin_Menu, which is loaded for all admin pages.
			// register_setting('photonic_options-'.$slug, 'photonic_options', [&$this, 'validate_options']);

			add_settings_section($slug, "", [&$this, "create_settings_section"], $this->file);
			$this->add_settings_fields($this->file);
		}
	}

	public function validate_options($options) {
		foreach ($options as $option => $option_value) {
			if (isset($this->reverse_options[$option])) {
				// Sanitize options
				switch ($this->reverse_options[$option]) {
					// For all text type of options make sure that the eventual text is properly escaped.
					case "text":
					case "background":
					case "border":
						$options[$option] = sanitize_text_field($option_value);
						break;

					case "textarea":
						$options[$option] = sanitize_textarea_field($option_value);
						break;

					case "select":
					case "radio":
					case "radio-group":
						if (isset($this->allowed_values[$option])) {
							if (!array_key_exists($option_value, $this->allowed_values[$option])) {
								$options[$option] = $this->option_defaults[$option];
							}
						}
						break;

					case "multi-select":
						$selections = explode(',', $option_value);
						$final_selections = [];
						foreach ($selections as $selection) {
							if (array_key_exists($selection, $this->allowed_values[$option])) {
								$final_selections[] = $selection;
							}
						}
						$options[$option] = implode(',', $final_selections);
						break;

					case "sortable-list":
						$selections = explode(',', $option_value);
						$final_selections = [];
						$master_list = $this->option_defaults[$option]; // Sortable lists don't have their values in ['options']
						foreach ($selections as $selection) {
							if (array_key_exists($selection, $master_list)) {
								$final_selections[] = $selection;
							}
						}
						$options[$option] = implode(',', $final_selections);
						break;

					case "checkbox":
						if (!in_array($option_value, ['on', 'off', 'true', 'false'], true) && isset($this->option_defaults[$option])) {
							$options[$option] = $this->option_defaults[$option];
						}
						break;
				}
			}
		}

		/*
		 * The Settings API does an update_option($option, $value), overwriting the $photonic_options array with the values on THIS page
		 * This is problematic because all options are stored in a single array, but are displayed on different options pages.
		 * Hence the overwrite kills the options from the other pages.
		 * So this is a workaround to include the options from other pages as hidden fields on this page, so that the array gets properly updated.
		 * The alternative would be to separate options for each page, but that would cause a migration headache for current users.
		 */
		$current_options = array_keys($options);
		if (isset($this->hidden_options) && is_array($this->hidden_options)) {
			foreach ($this->hidden_options as $hidden_option => $hidden_value) {
				if (strlen($hidden_option) >= 7 && ('submit-' === substr($hidden_option, 0, 7) || 'reset-' === substr($hidden_option, 0, 6)) || in_array($hidden_option, $current_options, true)) {
					continue;
				}
				$options[$hidden_option] = esc_attr($hidden_value);
			}
		}

		foreach ($this->nested_options as $section => $children) {
			if (isset($options['submit-' . $section])) {
				$options['last-set-section'] = $section;
				if ('Save page' === substr($options['submit-' . $section], 0, 9) || 'Reset page' === substr($options['submit-' . $section], 0, 10)) {
					global $photonic_options;
					foreach ($this->nested_options as $inner_section => $inner_children) {
						if ($section !== $inner_section) {
							foreach ($inner_children as $inner_child) {
								if (isset($photonic_options[$inner_child]) && !in_array($inner_child, $current_options, true)) {
									$options[$inner_child] = $photonic_options[$inner_child];
								}
							}
						}
					}

					if ('Reset page' === substr($options['submit-' . $section], 0, 10)) {
						unset($options['submit-' . $section]);
						// This is a reset for an individual section. So we will unset the child fields.
						foreach ($children as $child) {
							unset($options[$child]);
						}
					}
					unset($options['submit-' . $section]);
				}
				elseif ('Delete' === substr($options['submit-' . $section], 0, 6)) {
					return;
				}
				break;
			}
		}
		return $options;
	}

	public function get_option_structure() {
		if (isset($this->option_structure)) {
			return $this->option_structure;
		}
		$options = $this->tab_options;
		$option_structure = [];
		foreach ($options as $value) {
			switch ($value['type']) {
				case "title":
				case "section":
					$option_structure[$value['category']] = [];
					$option_structure[$value['category']]['slug'] = $value['category'];
					$option_structure[$value['category']]['name'] = $value['name'];
					$option_structure[$value['category']]['children'] = [];

					if (isset($value['help'])) {
						$option_structure[$value['category']]['help'] = $value['help'];
					}
					if (isset($value['buttons'])) {
						$option_structure[$value['category']]['buttons'] = $value['buttons'];
					}
					break;

				default:
					if (isset($value['id'])) {
						$option_structure[$value['grouping']]['children'][$value['id']] = $value['name'];
					}
			}
		}
		return $option_structure;
	}

	private function add_settings_fields($page) {
		$ctr = 0;
		foreach ($this->tab_options as $value) {
			$ctr++;
			switch ($value['type']) {
				case "blurb":
					add_settings_field($value['grouping'] . '-' . $ctr, $value['name'], [&$this, "create_section_for_blurb"], $page, $value['grouping'], $value);
					break;

				case "text":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_text"], $page, $value['grouping'], $value);
					break;

				case "textarea":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_textarea"], $page, $value['grouping'], $value);
					break;

				case "select":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_select"], $page, $value['grouping'], $value);
					break;

				case "multi-select":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_multi_select"], $page, $value['grouping'], $value);
					break;

				case "radio":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_radio"], $page, $value['grouping'], $value);
					break;

				case "radio-group":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_radio_group"], $page, $value['grouping'], $value);
					break;

				case "checkbox":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_checkbox"], $page, $value['grouping'], $value);
					break;

				case "border":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_border"], $page, $value['grouping'], $value);
					break;

				case "background":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_background"], $page, $value['grouping'], $value);
					break;

				case "padding":
					add_settings_field($value['id'], $value['name'], [&$this, "create_section_for_padding"], $page, $value['grouping'], $value);
					break;
			}
		}
	}

	public function create_section_for_radio($value) {
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		foreach ($value['options'] as $option_value => $option_text) {
			$this->create_individual_radio_item($value, $option_value, $option_text, $defaults);
		}
		$this->create_closing_tag();
	}

	public function create_section_for_radio_group($value) {
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);

		foreach ($value['options'] as $radio_group) {
			if (!empty($radio_group['header'])) {
				echo "<h4>" . wp_kses_post($radio_group['header']) . "</h4>\n";
			}
			if (!empty($radio_group['description'])) {
				echo wp_kses_post($radio_group['description']) . "\n";
			}
			foreach ($radio_group['options'] as $option_value => $option_text) {
				$this->create_individual_radio_item($value, $option_value, $option_text, $defaults);
			}
		}
		$this->create_closing_tag();
	}

	private function create_individual_radio_item($value, $option_value, $option_text, $defaults) {
		global $photonic_options;
		$replacements = Defaults::get_migrated_options();

		if (isset($photonic_options[$value['id']])) {
			$check_value = (isset($replacements[$value['id']]) && isset($replacements[$value['id']][$photonic_options[$value['id']]])) ? $replacements[$value['id']][$photonic_options[$value['id']]] : $photonic_options[$value['id']];
			$checked = checked($check_value, $option_value, false);
		}
		else {
			$checked = checked($defaults[$value['id']], $option_value, false);
		}
		echo '<div class="photonic-radio"><label><input type="radio" name="photonic_options[' . esc_attr($value['id']) . ']" value="' . esc_attr($option_value) . '" ' . esc_attr($checked) . "/>" . wp_kses_post($option_text) . "</label></div>\n";
	}

	public function create_section_for_text($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		if (!isset($photonic_options[$value['id']])) {
			$text = $defaults[$value['id']];
		}
		else {
			$text = $photonic_options[$value['id']];
			$text = stripslashes($text);
		}

		echo '<input type="text" name="photonic_options[' . esc_attr($value['id']) . ']" value="' . esc_attr($text) . '" />' . "\n";
		if (isset($value['hint'])) {
			echo "<em> &laquo; " . wp_kses_post($value['hint']) . "<br /></em>\n";
		}
		$this->create_closing_tag();
	}

	public function create_section_for_textarea($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		echo '<textarea name="photonic_options[' . esc_attr($value['id']) . ']" cols="" rows="">' . "\n";
		if (isset($photonic_options[$value['id']]) && "" !== $photonic_options[$value['id']]) {
			$text = stripslashes($photonic_options[$value['id']]);
		}
		else {
			$text = $defaults[$value['id']];
		}
		echo esc_attr($text);
		echo '</textarea>';
		if (isset($value['hint'])) {
			echo "<em> &laquo; " . wp_kses_post($value['hint']) . "<br /></em>\n";
		}
		$this->create_closing_tag();
	}

	public function create_section_for_select($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$replacements = Defaults::get_migrated_options();

		$this->create_opening_tag($value);
		echo '<select name="photonic_options[' . esc_attr($value['id']) . ']">' . "\n";
		foreach ($value['options'] as $option_value => $option_text) {
			echo "<option ";
			if (isset($photonic_options[$value['id']])) {
				$check_value = (isset($replacements[$value['id']]) && isset($replacements[$value['id']][$photonic_options[$value['id']]])) ? $replacements[$value['id']][$photonic_options[$value['id']]] : $photonic_options[$value['id']];
				selected($check_value, $option_value);
			}
			else {
				selected($defaults[$value['id']], $option_value);
			}
			echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
		}
		echo "</select>\n";
		$this->create_closing_tag();
	}

	public function create_section_for_multi_select($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		echo '<div class="photonic-checklist">' . "\n";
		echo '<ul class="photonic-checklist" id="' . esc_attr($value['id']) . '-chk" >' . "\n";
		if (isset($defaults[$value['id']])) {
			$consolidated_value = $defaults[$value['id']];
		}
		if (isset($photonic_options[$value['id']])) {
			$consolidated_value = $photonic_options[$value['id']];
		}
		if (!isset($consolidated_value)) {
			$consolidated_value = "";
		}
		$consolidated_value = trim($consolidated_value);
		$exploded = [];
		if ('' !== $consolidated_value) {
			$exploded = explode(',', $consolidated_value);
		}

		foreach ($value['options'] as $option_value => $option_list) {
			$checked = " ";
			if ($consolidated_value) {
				foreach ($exploded as $checked_value) {
					$checked = checked($checked_value, $option_value, false);
					if ('' !== trim($checked)) {
						break;
					}
				}
			}
			echo "<li>\n";
			$depth = 0;
			if (isset($option_list['depth'])) {
				$depth = $option_list['depth'];
			}
			echo '<label><input type="checkbox" name="' . esc_attr($value['id']) . "_" . esc_attr($option_value) . '" value="true" ' . esc_attr($checked) . ' class="depth-' . esc_attr($depth + 1) . ' photonic-options-checkbox-' . esc_attr($value['id']) . '" data-photonic-selection-for="' . esc_attr($value['id']) . '" data-photonic-value="' . esc_attr($option_value) . '" />' .
				wp_kses_post($option_list['title']) . "</label>\n";
			echo "</li>\n";
		}
		echo "</ul>\n";

		if (isset($photonic_options[$value['id']])) {
			$set_value = $photonic_options[$value['id']];
		}
		elseif (isset($defaults[$value['id']])) {
			$set_value = $defaults[$value['id']];
		}
		else {
			$set_value = "";
		}
		echo '<input type="hidden" name="photonic_options[' . esc_attr($value['id']) . ']" id="' . esc_attr($value['id']) . '" value="' . esc_attr($set_value) . '"/>' . "\n";
		echo "</div>\n";
		$this->create_closing_tag();
	}

	public function create_settings_section($section) {
		$option_structure = $this->option_structure;
		if (0 !== $this->displayed_sections) {
			$this->show_buttons($this->previous_displayed_section, $option_structure[$this->previous_displayed_section]);
			echo "</form>\n";
			echo "</div><!-- /photonic-options-panel -->\n";
		}

		echo "<div id='" . esc_attr($section['id']) . "' class='photonic-options-panel'> \n";
		echo "<form method=\"post\" action=\"options.php\" id=\"photonic-options-form-" . esc_attr($section['id']) . "\" class='photonic-options-form'>\n";
		echo '<h3>' . wp_kses_post($option_structure[$section['id']]['name']) . "</h3>\n";

		/*
		 * We store all options in one array, but display them across multiple pages. Hence we need the following hack.
		 * We are registering the same setting across multiple pages, hence we need to pass the "page" parameter to options.php.
		 * Otherwise options.php returns an error saying "Options page not found"
		 */
		echo "<input type='hidden' name='page' value='" . esc_attr($_REQUEST['page']) . "' />\n";
		if (!isset($_REQUEST['tab'])) {
			$tab = 'Generic.php';
		}
		else {
			$tab = sanitize_text_field($_REQUEST['tab']);
		}
		echo "<input type='hidden' name='tab' value='" . esc_attr($tab) . "' />\n";

		settings_fields("photonic_options-{$section['id']}");
		$this->displayed_sections++;
		$this->previous_displayed_section = $section['id'];
	}

	public function create_section_for_blurb($value) {
		$this->create_opening_tag($value);
		$this->create_closing_tag();
	}

	/**
	 * Renders an option whose type is "checkbox". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	public function create_section_for_checkbox($value) {
		global $photonic_options;
		$checked = '';
		if (isset($photonic_options[$value['id']])) {
			$checked = checked(esc_attr($photonic_options[$value['id']]), 'on', false);
		}
		$this->create_opening_tag($value);
		echo '<label><input type="checkbox" name="photonic_options[' . esc_attr($value['id']) . ']" ' . esc_attr($checked) . "/>" . wp_kses_post($value['desc']) . "</label>\n";
		$this->create_closing_tag();
	}

	/**
	 * Renders an option whose type is "border". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	public function create_section_for_border($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		$original = $defaults[$value['id']];
		if (!isset($photonic_options[$value['id']])) {
			$default = $defaults[$value['id']];
			$default_txt = "";
			foreach ($default as $edge => $edge_val) {
				$default_txt .= $edge . '::';
				foreach ($edge_val as $opt => $opt_val) {
					$default_txt .= $opt . "=" . $opt_val . ";";
				}
				$default_txt .= "||";
			}
		}
		else {
			$default_txt = $photonic_options[$value['id']];
			$default = $default_txt;
			$edge_array = explode('||', $default);
			$default = [];
			if (is_array($edge_array)) {
				foreach ($edge_array as $edge_vals) {
					if ('' !== trim($edge_vals)) {
						$edge_val_array = explode('::', $edge_vals);
						if (is_array($edge_val_array) && count($edge_val_array) > 1) {
							$vals = explode(';', $edge_val_array[1]);
							$default[$edge_val_array[0]] = [];
							foreach ($vals as $val) {
								$pair = explode("=", $val);
								if (isset($pair[0]) && isset($pair[1])) {
									$default[$edge_val_array[0]][$pair[0]] = $pair[1];
								}
								elseif (isset($pair[0]) && !isset($pair[1])) {
									$default[$edge_val_array[0]][$pair[0]] = "";
								}
							}
						}
					}
				}
			}
		}
		$edges = ['top' => 'Top', 'right' => 'Right', 'bottom' => 'Bottom', 'left' => 'Left'];
		$styles = [
			"none"   => "No border",
			"hidden" => "Hidden",
			"dotted" => "Dotted",
			"dashed" => "Dashed",
			"solid"  => "Solid",
			"double" => "Double",
			"groove"  => "Groove",
			"ridge"  => "Ridge",
			"inset"  => "Inset",
			"outset" => "Outset"
		];

		$border_width_units = ["px" => "Pixels (px)", "em" => "Em"];

		foreach ($value['options'] as $option_value => $option_text) {
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($defaults[$value['id']], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="' . esc_attr($value['id']) . '" value="' . esc_attr($option_value) . '" ' . esc_attr($checked) . "/>" . wp_kses_post($option_text) . "</div>\n";
		}
		?>
		<div class='photonic-border-options'>
			<p>For any edge set style to "No Border" if you don't want a border.</p>
			<table class='opt-sub-table-5'>
				<col class='opt-sub-table-col-51'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>

				<tr>
					<th scope="col">&nbsp;</th>
					<th scope="col">Border Style</th>
					<th scope="col">Color</th>
					<th scope="col">Border Width</th>
					<th scope="col">Border Width Units</th>
				</tr>

				<?php
				foreach ($edges as $edge => $edge_text) {
					?>
					<tr>
						<th scope="row"><?php echo wp_kses_post($edge_text); ?></th>
						<td style='vertical-align: top'>
							<select name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-style"
									id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-style">
								<?php
								foreach ($styles as $option_value => $option_text) {
									echo "<option ";
									if (isset($default[$edge]) && isset($default[$edge]['style'])) {
										selected($default[$edge]['style'], $option_value);
									}
									echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
								}
								?>
							</select>
						</td>

						<td style='vertical-align: top'>
							<div class="color-picker-group">
								<input type="radio" name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-colortype"
									   value="transparent" <?php checked($default[$edge]['colortype'], 'transparent'); ?> />
								Transparent / No color<br/>
								<input type="radio" name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-colortype"
									   value="custom" <?php checked($default[$edge]['colortype'], 'custom'); ?>/> Custom
								<input type="text" id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-color"
									   name="<?php echo esc_attr($value['id']); ?>-color"
									   value="<?php echo esc_attr($default[$edge]['color']); ?>"
									   data-photonic-default-color="<?php echo esc_attr($original[$edge]['color']); ?>"
									   class="color"/><br/>
								Default: <span> <?php echo esc_attr($original[$edge]['color']); ?> </span>
							</div>
						</td>

						<td style='vertical-align: top'>
							<input type="text" id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-border-width"
								   name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-border-width"
								   value="<?php echo esc_attr($default[$edge]['border-width']); ?>"/><br/>
						</td>

						<td style='vertical-align: top'>
							<select name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-border-width-type"
									id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-border-width-type">
								<?php
								foreach ($border_width_units as $option_value => $option_text) {
									echo "<option ";
									selected($default[$edge]['border-width-type'], $option_value);
									echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
								}
								?>
							</select>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<input type='hidden' id="<?php echo esc_attr($value['id']); ?>" name="photonic_options[<?php echo esc_attr($value['id']); ?>]"
				   value="<?php echo esc_attr($default_txt); ?>"/>
		</div>
		<?php
		$this->create_closing_tag();
	}

	/**
	 * Renders an option whose type is "background". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	public function create_section_for_background($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();

		$this->create_opening_tag($value);
		$original = $defaults[$value['id']];
		if (!isset($photonic_options[$value['id']])) {
			$default = $defaults[$value['id']];
			$default_txt = "";
			foreach ($defaults[$value['id']] as $opt => $opt_val) {
				$default_txt .= $opt . "=" . $opt_val . ";";
			}
		}
		else {
			$default_txt = $photonic_options[$value['id']];
			$default = $default_txt;
			$vals = explode(";", $default);
			$default = [];
			foreach ($vals as $val) {
				$pair = explode("=", $val);
				if (isset($pair[0]) && isset($pair[1])) {
					$default[$pair[0]] = $pair[1];
				}
				elseif (isset($pair[0]) && !isset($pair[1])) {
					$default[$pair[0]] = "";
				}
			}
		}
		$repeats = [
			"repeat"    => "Repeat horizontally and vertically",
			"repeat-x"  => "Repeat horizontally only",
			"repeat-y"  => "Repeat vertically only",
			"no-repeat" => "Do not repeat"
		];

		$positions = [
			"top left"      => "Top left",
			"top center"    => "Top center",
			"top right"     => "Top right",
			"center left"   => "Center left",
			"center center" => "Middle of the page",
			"center right"  => "Center right",
			"bottom left"   => "Bottom left",
			"bottom center" => "Bottom center",
			"bottom right"  => "Bottom right"
		];

		foreach ($value['options'] as $option_value => $option_text) {
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($defaults[$value['id']], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="' . esc_attr($value['id']) . '" value="' . esc_attr($option_value) . '" ' . esc_attr($checked) . "/>" . wp_kses_post($option_text) . "</div>\n";
		}
		?>
		<div class='photonic-background-options'>
			<table class='opt-sub-table'>
				<colgroup>
					<col class='opt-sub-table-cols'/>
					<col class='opt-sub-table-cols'/>
				</colgroup>
				<tr>
					<td style='vertical-align: top'>
						<div class="color-picker-group">
							<strong>Background Color:</strong><br/>
							<label><input type="radio" name="<?php echo esc_attr($value['id']); ?>-colortype"
										  value="transparent" <?php checked($default['colortype'], 'transparent'); ?> />
								Transparent / No color</label><br/>
							<label><input type="radio" name="<?php echo esc_attr($value['id']); ?>-colortype"
										  value="custom" <?php checked($default['colortype'], 'custom'); ?>/>
								Custom</label>
							<input type="text" id="<?php echo esc_attr($value['id']); ?>-bgcolor"
								   name="<?php echo esc_attr($value['id']); ?>-bgcolor" value="<?php echo esc_attr($default['color']); ?>"
								   data-photonic-default-color="<?php echo esc_attr($original['color']); ?>" class="color"/><br/>
							Default: <span> <?php echo esc_attr($original['color']); ?> </span>
						</div>
					</td>
					<td style='vertical-align: top'>
						<strong>Image URL:</strong><br/>
						<?php $this->display_upload_field($default['image'], $value['id'] . "-bgimg", $value['id'] . "-bgimg"); ?>
					</td>
				</tr>

				<tr>
					<td style='vertical-align: top'>
						<label>
							<strong>Image Position:</strong><br/>
							<select name="<?php echo esc_attr($value['id']); ?>-position"
									id="<?php echo esc_attr($value['id']); ?>-position">
								<?php
								foreach ($positions as $option_value => $option_text) {
									echo "<option ";
									selected($default['position'], $option_value);
									echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
								}
								?>
							</select>
						</label>
					</td>

					<td style='vertical-align: top'>
						<label>
							<strong>Image Repeat:</strong><br/>
							<select name="<?php echo esc_attr($value['id']); ?>-repeat"
									id="<?php echo esc_attr($value['id']); ?>-repeat">
								<?php
								foreach ($repeats as $option_value => $option_text) {
									echo "<option ";
									selected($default['repeat'], $option_value);
									echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
								}
								?>
							</select>
						</label>
					</td>
				</tr>
				<tr>
					<td style='vertical-align: top' colspan='2'>
						<div class='slider'>
							<p>
								<label>
									<strong>Layer Transparency (not for IE):</strong>
									<select id="<?php echo esc_attr($value['id']); ?>-trans"
											name="<?php echo esc_attr($value['id']); ?>-trans">
										<?php
										for ($i = 0; $i <= 100; $i++) {
											?>
											<option <?php echo selected($default['trans'], $i); ?> value="<?php echo esc_attr($i); ?>" ><?php echo esc_html($i); ?></option>
											<?php
										}
										?>
									</select>
								</label>
							</p>
						</div>
					</td>
				</tr>
			</table>
			<input type='hidden' id="<?php echo esc_attr($value['id']); ?>" name="photonic_options[<?php echo esc_attr($value['id']); ?>]"
				   value="<?php echo esc_attr($default_txt); ?>"/>
		</div>
		<?php
		$this->create_closing_tag();
	}

	/**
	 * Renders an option whose type is "background". Invoked by add_settings_field.
	 *
	 * @param  $value
	 * @return void
	 */
	public function create_section_for_padding($value) {
		global $photonic_options;
		$defaults = Defaults::get_options();
		$this->create_opening_tag($value);
		if (!isset($photonic_options[$value['id']])) {
			$default = $defaults[$value['id']];
			$default_txt = "";
			foreach ($default as $edge => $edge_val) {
				$default_txt .= $edge . '::';
				foreach ($edge_val as $opt => $opt_val) {
					$default_txt .= $opt . "=" . $opt_val . ";";
				}
				$default_txt .= "||";
			}
		}
		else {
			$default_txt = $photonic_options[$value['id']];
			$default = $default_txt;
			$edge_array = explode('||', $default);
			$default = [];
			if (is_array($edge_array)) {
				foreach ($edge_array as $edge_vals) {
					if ('' !== trim($edge_vals)) {
						$edge_val_array = explode('::', $edge_vals);
						if (is_array($edge_val_array) && count($edge_val_array) > 1) {
							$vals = explode(';', $edge_val_array[1]);
							$default[$edge_val_array[0]] = [];
							foreach ($vals as $val) {
								$pair = explode("=", $val);
								if (isset($pair[0]) && isset($pair[1])) {
									$default[$edge_val_array[0]][$pair[0]] = $pair[1];
								}
								elseif (isset($pair[0]) && !isset($pair[1])) {
									$default[$edge_val_array[0]][$pair[0]] = "";
								}
							}
						}
					}
				}
			}
		}
		$edges = ['top' => 'Top', 'right' => 'Right', 'bottom' => 'Bottom', 'left' => 'Left'];
		$padding_units = ["px" => "Pixels (px)", "em" => "Em"];

		foreach ($value['options'] as $option_value => $option_text) {
			if (isset($photonic_options[$value['id']])) {
				$checked = checked($photonic_options[$value['id']], $option_value, false);
			}
			else {
				$checked = checked($defaults[$value['id']], $option_value, false);
			}
			echo '<div class="photonic-radio"><input type="radio" name="' . esc_attr($value['id']) . '" value="' . esc_attr($option_value) . '" ' . esc_attr($checked) . "/>" . wp_kses_post($option_text) . "</div>\n";
		}
		?>
		<div class='photonic-padding-options'>
			<table class='opt-sub-table-5'>
				<col class='opt-sub-table-col-51'/>
				<col class='opt-sub-table-col-5'/>
				<col class='opt-sub-table-col-5'/>

				<tr>
					<th scope="col">&nbsp;</th>
					<th scope="col">Padding</th>
					<th scope="col">Padding Units</th>
				</tr>

				<?php
				foreach ($edges as $edge => $edge_text) {
					?>
					<tr>
						<th scope="row"><?php echo esc_attr($edge_text); ?></th>
						<td style='vertical-align: top'>
							<input type="text" id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-padding"
								   name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-padding"
								   value="<?php echo esc_attr($default[$edge]['padding']); ?>"/><br/>
						</td>

						<td style='vertical-align: top'>
							<select name="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-padding-type"
									id="<?php echo esc_attr($value['id'] . '-' . $edge); ?>-padding-type">
								<?php
								foreach ($padding_units as $option_value => $option_text) {
									echo "<option ";
									selected($default[$edge]['padding-type'], $option_value);
									echo " value='" . esc_attr($option_value) . "' >" . esc_attr($option_text) . "</option>\n";
								}
								?>
							</select>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<input type='hidden' id="<?php echo esc_attr($value['id']); ?>" name="photonic_options[<?php echo esc_attr($value['id']); ?>]"
				   value="<?php echo esc_attr($default_txt); ?>"/>
		</div>
		<?php
		$this->create_closing_tag();
	}

	/**
	 * Creates the opening markup for each option.
	 *
	 * @param  $value
	 * @return void
	 */
	public function create_opening_tag($value) {
		echo "<div class='photonic-section fix'>\n";
		if (isset($value['desc']) && 'checkbox' !== $value['type']) {
			echo wp_kses_post($value['desc']) . "<br />";
		}
		if (isset($value['note'])) {
			echo "<span class=\"note\">" . wp_kses_post($value['note']) . "</span><br />";
		}
	}

	/**
	 * Creates the closing markup for each option.
	 *
	 * @return void
	 */
	public function create_closing_tag() {
		echo "</div><!-- photonic-section -->\n";
	}

	/**
	 * This method displays an upload field and button. This has been separated from the create_section_for_upload method,
	 * because this is used by the create_section_for_background as well.
	 *
	 * @param $upload
	 * @param $id
	 * @param $name
	 * @param null $hint
	 * @return void
	 */
	private function display_upload_field($upload, $id, $name, $hint = null) {
		echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($upload) . '" />' . "\n";
		if (null !== $hint) {
			echo "<em> &laquo; " . wp_kses_post($hint) . "<br /></em>\n";
		}
	}

	/**
	 * Save generated options to a file. This uses the WP_Filesystem to validate the credentials of the user attempting to save options.
	 *
	 * @param $custom_css
	 * @return bool
	 */
	public function save_css_to_file($custom_css): bool {
		if (!isset($_GET['settings-updated'])) {
			return false;
		}

		$url = wp_nonce_url('admin.php?page=photonic-options-manager');
		$creds = request_filesystem_credentials($url, '', false, false);
		if (false === $creds) {
			return true;
		}

		if (!WP_Filesystem($creds)) {
			request_filesystem_credentials($url, '', true, false);
			return true;
		}

		/** @var $wp_filesystem \WP_Filesystem_Base */
		global $wp_filesystem;
		if (!is_dir(PHOTONIC_UPLOAD_DIR)) {
			if (!$wp_filesystem->mkdir(PHOTONIC_UPLOAD_DIR)) {
				echo "<div class='error'><p>Failed to create directory " . esc_attr(PHOTONIC_UPLOAD_DIR) . ". Please check your folder permissions.</p></div>";
				return false;
			}
		}

		$filename = trailingslashit(PHOTONIC_UPLOAD_DIR) . 'custom-styles.css';

		if (empty($custom_css)) {
			return false;
		}

		if (!$wp_filesystem->put_contents($filename, $custom_css, FS_CHMOD_FILE)) {
			echo wp_kses_post("<div class='error'><p>Failed to save file $filename. Please check your folder permissions.</p></div>");
			return false;
		}
		return true;
	}
}
