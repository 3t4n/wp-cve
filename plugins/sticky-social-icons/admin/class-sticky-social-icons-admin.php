<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.sanil.com.np
 * @since      1.0.0
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/admin
 * @author     Sanil Shakya <sanilshakya@gmail.com>
 */
class Sticky_Social_Icons_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Default settings_page_slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $settings_page_slug
	 */
	private $settings_page_slug = 'sticky-social-icons';


	/**
	 * Store names of all the option fields used by this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $all_used_db_field_names
	 */
	private $all_used_db_field_names;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_styles() {

		// load styles in plugin page only
		if (isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug) {

			if (isset($_GET['tabs']) && $_GET['tabs'] == 'icons') {

				wp_enqueue_style('wp-color-picker');

				$sel_icon_pack = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package', 'fontawesome5');

				if ($sel_icon_pack == 'fontawesome5') {
					// fontawesome5
					wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css');
				} elseif ($sel_icon_pack == 'fontawesome6') {
					// fontawesome6
					wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css');
				} else {
					// fontawesome6
					wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
				}
			}

			// admin css
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url(__FILE__) . 'assets/build/css/sticky-social-icons-admin.css',
				array(),
				STICKY_SOCIAL_ICONS_ENVIRONMENT === 'dev' ? time() : $this->version,
				'all'
			);
		}
	}


	/**
	 * Register the scripts for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_scripts() {

		// load scripts in plugin plage only
		if (isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug) {

			if (isset($_GET['tabs']) && $_GET['tabs'] == 'icons') {

				$sel_icon_pack = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package', 'fontawesome5');

				// load correct icon pack.
				wp_enqueue_script('fontawesome_icon_names', plugin_dir_url(__FILE__) . 'assets/build/js/' . $sel_icon_pack . '_icons.js', array('jquery'), $this->version);

				wp_enqueue_script(
					$this->plugin_name,
					plugin_dir_url(__FILE__) . 'assets/build/js/sticky-social-icons-admin.min.js',
					array('jquery', 'wp-color-picker', 'jquery-ui-sortable'),
					STICKY_SOCIAL_ICONS_ENVIRONMENT === 'dev' ? time() : $this->version,
				);

				wp_localize_script(
					$this->plugin_name,
					'sanil_ssi_objects',
					array(
						'text_more_options' 		=> __('More Options', 'sticky-social-icons'),
						'text_remove' 				=> __('Remove', 'sticky-social-icons'),
						'text_drag' 				=> __('Drag', 'sticky-social-icons'),
						'text_drag_msg' 			=> __('Click & drag up or down to change position', 'sticky-social-icons'),
						'text_close' 				=> __('Close', 'sticky-social-icons'),
						'text_url_to_open' 			=> __('URL to open', 'sticky-social-icons'),
						'text_open_in_new_tab' 		=> __('Open In New Tab', 'sticky-social-icons'),
						'text_colors' 				=> __('Colors', 'sticky-social-icons'),
						'text_icon_color' 			=> __('Icon Color', 'sticky-social-icons'),
						'text_icon_color_on_hover' 	=> __('Icon Color On Hover', 'sticky-social-icons'),
						'text_bck_color' 			=> __('Background Color', 'sticky-social-icons'),
						'text_bck_color_on_hover' 	=> __('Background Color On Hover', 'sticky-social-icons'),
						'text_tooltip_label' 		=> __('Tooltip Label', 'sticky-social-icons'),
						'enable_tooltip' 			=> get_option('sanil_ssi_db_enable_tooltip', 1),
					)
				);
			}
		}
	}


	/**
	 * Register admin menu for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function add_menu_callback() {

		// inside settings menu
		add_submenu_page('options-general.php', 'Sticky Social Icons', 'Sticky Social Icons', 'manage_options', $this->settings_page_slug, array($this, 'get_settings_screen_contents'), 6);
	}


	/**
	 * Add custom "settings" link in the plugins.php page
	 *
	 * @since    1.0.0
	 */

	public function custom_plugin_link_callback($links, $file) {

		if ($file == plugin_basename(dirname(__FILE__, 2) . '/sticky-social-icons.php')) {
			// add "Settings" link
			$links[] = '<a href="options-general.php?page=' . $this->settings_page_slug . '">' . __('Settings', 'sticky-social-icons') . '</a>';
		}

		return $links;
	}



	/**
	 * Show contents for settings page of the menu
	 *
	 * @since    1.0.0
	 */

	public function get_settings_screen_contents() {
		$current_tab = (isset($_GET['tabs'])) ? $_GET['tabs'] : 'settings';
		$tab_url = "options-general.php?page=$this->settings_page_slug&tabs=";

		ob_start();
		require_once dirname(__FILE__) . '/templates/settings-screen.php';
		echo ob_get_clean();
	}


	/**
	 * Generate settings page form elements
	 *
	 * @since    1.0.0
	 */

	public function settings_page_ui() {

		// ---------------------------------------------
		// Settings
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'sticky_social_icons_settings',
			'section_id' 			=> 'settings',
			'section_label'			=> '',
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug . '-settings',
			'fields'				=> array(
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_animation',
					'field_label'			=> __('Enable Animation', 'sticky-social-icons'),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_animation',
							'checked' 			=> 1,
						)
					)
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_tooltip',
					'field_label'			=> __('Enable Tooltip', 'sticky-social-icons'),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_tooltip',
							'checked' 			=> 1,
						)
					)
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'hide_in_mobile',
					'field_label'			=> __('Hide in Smaller Screen', 'sticky-social-icons'),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'hide_in_mobile',
							'checked' 			=> 1,
						)
					)
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'load_fontawesome_icons',
					'field_label'			=> __('Load Fontawesome Icons', 'sticky-social-icons'),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'load_fontawesome_icons',
							'checked' 			=> 1,
							'end_label'			=> 'Uncheck if FontAwesome CSS is already loaded by theme or another plugin.'
						)
					)
				)
			)
		);

		// create settings fields
		$this->create_settings($settings_args);


		// ---------------------------------------------
		// Appearance
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'sticky_social_icons_settings',
			'section_id' 			=> 'appearance',
			'section_label'			=> 'Appearance',
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug . '-settings',
			'fields'				=> array(
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'design',
					'field_label'			=> __('Design', 'sticky-social-icons'),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array(
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'design',
							'options' 			=> array(
								'rounded'		=> __('Rounded Corner', 'sticky-social-icons'),
								'sharp' 		=> __('Sharp Corner', 'sticky-social-icons'),
							),
						)
					),
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'alignment',
					'field_label'			=> __('Alignment', 'sticky-social-icons'),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'alignment',
							'options' 			=> array(
								'left'			=> __('Left side of the screen', 'sticky-social-icons'),
								'right' 		=> __('Right side of the screen', 'sticky-social-icons'),
							),
						),
					)
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'offset_from_top',
					'field_label'			=> __('Offset From Top', 'sticky-social-icons'),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array(
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'offset_from_top',
							'default'		 	=> STICKY_SOCIAL_ICONS_DEFAULTS[0],
							'sanitize_callback'	=> 'validate_offset_from_top',
							'css_class'			=> 'number',
							'end_label'			=> 'px'
						)
					),
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_font_size',
					'field_label'			=> __('Icon Font Size', 'sticky-social-icons'),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array(
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_font_size',
							'default'		 	=> STICKY_SOCIAL_ICONS_DEFAULTS[1],
							'sanitize_callback'	=> 'validate_icon_font_size',
							'css_class'			=> 'number',
							'end_label'			=> 'px'
						)
					),
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'width',
					'field_label'			=> __('Width', 'sticky-social-icons'),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array(
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'width',
							'default'		 	=> STICKY_SOCIAL_ICONS_DEFAULTS[2],
							'sanitize_callback'	=> 'validate_icon_container_width',
							'css_class'			=> 'number',
							'end_label'			=> 'px'
						)
					),
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'height',
					'field_label'			=> __('Height', 'sticky-social-icons'),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array(
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'height',
							'default'		 	=> STICKY_SOCIAL_ICONS_DEFAULTS[3],
							'sanitize_callback'	=> 'validate_icon_container_height',
							'css_class'			=> 'number',
							'end_label'			=> 'px'
						)
					),
				),
			)
		);

		// create settings fields
		$this->create_settings($settings_args);


		// ---------------------------------------------
		// Icons
		// ---------------------------------------------
		$settings_args = array(
			'settings_group_name'	=> 'sticky_social_icons_icons',
			'section_id' 			=> 'icons',
			'section_label'			=> '',
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug . '-icons',
			'fields'				=> array(
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package',
					'field_label'			=> __('Select Icon Package', 'sticky-social-icons'),
					'field_callback'		=> array($this, "select_icon_package_dropdown"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package',
							'options' 			=> array(
								'fontawesome5'	=> __('Font Awesome 5', 'sticky-social-icons'),
								'fontawesome6'	=> __('Font Awesome 6', 'sticky-social-icons'),
								'fontawesome6_5'	=> __('Font Awesome 6.5', 'sticky-social-icons'),
							),
						),

					),
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons',
					'field_label'			=> '',
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array(
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons',
							'css_class'			=> 'hidden',
						),
					)
				),

			)
		);



		// create settings fields
		$this->create_settings($settings_args);

		// store all_used_db_field_names as option for future use
		update_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'all_options_names', serialize($this->all_used_db_field_names));
	}


	/**
	 * Register settings, section and fields in a single callback
	 *
	 * @since    1.0.0
	 */

	private function create_settings($args) {

		// define section ---------------------------
		add_settings_section($args['section_id'], $args['section_label'], $args['section_callback'], $args['screen']);

		foreach ($args['fields'] as $field) {

			// create label
			add_settings_field($field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args']);

			foreach ($field['field_callback_args'] as $sub_field) {
				register_setting($args['settings_group_name'],  $sub_field['name'], array(
					'sanitize_callback' => isset($sub_field['sanitize_callback']) ? array($this, $sub_field['sanitize_callback']) : 'sanitize_text_field'
				));

				$this->all_used_db_field_names[] = $sub_field['name'];
			}
		}
	}


	/**
	 * Form Validation for Offset From Top
	 *
	 * @since    1.0.0
	 */

	public function validate_offset_from_top($post_data) {
		return $this->validate_numeric_fields($post_data, STICKY_SOCIAL_ICONS_DEFAULTS[0], 'Offset From Top field should not be empty. Default value is used.');
	}


	/**
	 * Form Validation for Icon Font Size
	 *
	 * @since    1.0.0
	 */

	public function validate_icon_font_size($post_data) {
		return $this->validate_numeric_fields($post_data, STICKY_SOCIAL_ICONS_DEFAULTS[1], 'Icon Font Size field should not be empty. Default value is used.');
	}


	/**
	 * Form Validation for Icon Padding Horizontal
	 *
	 * @since    1.0.0
	 */

	public function validate_icon_container_width($post_data) {
		return $this->validate_numeric_fields($post_data, STICKY_SOCIAL_ICONS_DEFAULTS[2], 'Width field should not be empty. Default value is used.');
	}



	/**
	 * Form Validation for Icon Padding Vertical
	 *
	 * @since    1.0.0
	 */

	public function validate_icon_container_height($post_data) {
		return $this->validate_numeric_fields($post_data, STICKY_SOCIAL_ICONS_DEFAULTS[3], 'Height field should not be empty. Default value is used.');
	}


	/**
	 * Form Validation Helper Function
	 *
	 * @since    1.0.0
	 */

	private function validate_numeric_fields($post_data, $default_value, $msg = NULL) {

		if ($post_data != null && is_numeric($post_data)) {
			return sanitize_text_field($post_data);
		}

		if (!$msg) $msg = 'Input field should not be empty. Default value is used.';

		add_settings_error(
			'sticky_social_icons',
			esc_attr('settings_updated'),
			$msg
		);

		return $default_value;
	}


	// -------------------------------------------------
	// form element helpers
	// -------------------------------------------------

	public function select_icon_package_dropdown($arguments) {
		ob_start();
		$this->select($arguments);
		echo '<p>More Icon Package will be added in future updates</p>';
		echo ob_get_clean();
	}

	public function text_box($arguments) {
		foreach ($arguments as $args) {
			$default = isset($args['default']) ? $args['default'] : '';
			$db_value = get_option($args['name'], $default);
			$css_class = isset($args['css_class']) ? $args['css_class'] : '';
			$end_label = isset($args['end_label']) ? $args['end_label'] : '';

			ob_start();
			require dirname(__FILE__) . '/templates/input_textbox.php';
			echo ob_get_clean();
		}
	}

	public function checkbox($arguments) {

		ob_start();
		foreach ($arguments as $args) {
			$default_state = (array_key_exists('checked', $args)) ? $args['checked'] : 1;
			$db_value = get_option($args['name'], $default_state);
			$is_checked = ($db_value) ? 'checked' : '';
			$attr = (array_key_exists('attr', $args)) ? $args['attr'] : '';
			$end_label = (array_key_exists('end_label', $args)) ? $args['end_label'] : '';

			require dirname(__FILE__) . '/templates/input_checkbox.php';
		}
		echo ob_get_clean();
	}


	public function select($arguments) {
		foreach ($arguments as $args) {

			$db_value = get_option($args['name']);
			$options = (array_key_exists('options', $args)) ? $args['options'] : array();

			ob_start();
			require dirname(__FILE__) . '/templates/input_select.php';
			echo ob_get_clean();
		}
	}
}
