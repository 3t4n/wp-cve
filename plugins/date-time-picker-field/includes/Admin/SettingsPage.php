<?php

/**
 * Settings Page. 
 * This class is responsible for creating the settings page.
 * The structure of the page is created using the Settings API.
 * 
 * The settings page contains the following sections:
 * General - General settings for the plugin.
 * Integration - Integration with other plugins.
 * Advanced - Advanced settings for the plugin.
 * 
 * @package date-time-picker-field
 * @author InputWP <support@inputwp.com>
 * @link https://www.inputwp.com InputWP
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * 
 */

namespace CMoreira\Plugins\DateTimePicker\Admin;

use CMoreira\Plugins\DateTimePicker\Integration\IntegrationHelper as IntegrationHelper;

if ( ! class_exists( 'SettingsPage' ) ) {
	class SettingsPage {

		private $settings_api;

		private $integration_api;

		public static $menu_svg = 'PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDEyOCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMzIuOTY5NyAxOS4zOTM5QzI1LjQ3MiAxOS4zOTM5IDE5LjM5MzkgMjUuNDcyIDE5LjM5MzkgMzIuOTY5N1Y5NS4wMzAzQzE5LjM5MzkgMTAyLjUyOCAyNS40NzIgMTA4LjYwNiAzMi45Njk3IDEwOC42MDZIMzguMTc5NUMzOS43MjI1IDEwOC42MDYgNDEuMjAyNCAxMDcuOTkzIDQyLjI5MzUgMTA2LjkwMkw3Ni41MzcxIDcyLjY1ODNMOTAuMjUwNyA4Ni4zNzE5TDU2LjAwNzEgMTIwLjYxNkM1MS4yNzg5IDEyNS4zNDQgNDQuODY2MSAxMjggMzguMTc5NSAxMjhIMzIuOTY5N0MxNC43NjEgMTI4IDAgMTEzLjIzOSAwIDk1LjAzMDNWMzIuOTY5N0MwIDE0Ljc2MSAxNC43NjEgMCAzMi45Njk3IDBWMTkuMzkzOVpNNzEuNzU3NiAxOS4zOTM5SDU2LjI0MjRWMEg3MS43NTc2VjE5LjM5MzlaTTk1LjAzMDMgMEMxMTMuMjM5IDAgMTI4IDE0Ljc2MSAxMjggMzIuOTY5N1Y0OC40ODQ4SDEwOC42MDZWMzIuOTY5N0MxMDguNjA2IDI1LjQ3MiAxMDIuNTI4IDE5LjM5MzkgOTUuMDMwMyAxOS4zOTM5VjBaIiBmaWxsPSIjOUVBM0E5Ii8+CjxwYXRoIG9wYWNpdHk9IjAuNCIgZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMjUuMTYgODYuMzcxOUw4Ni4zNzE5IDEyNS4xNkM4Mi41ODUgMTI4Ljk0NyA3Ni40NDUyIDEyOC45NDcgNzIuNjU4MyAxMjUuMTZMNjUuNzczNCAxMTguMjc1TDc5LjQ4NyAxMDQuNTYxTDc5LjUxNTEgMTA0LjU4OUwxMTEuNDQ2IDcyLjY1ODNMMTI1LjE2IDg2LjM3MTlaIiBmaWxsPSIjOUVBM0E5Ii8+CjxyZWN0IHg9IjMyLjk2OTciIHk9IjE5LjM5MzkiIHdpZHRoPSIyMy4yNzI3IiBoZWlnaHQ9IjE5LjM5MzkiIGZpbGw9IiM5RUEzQTkiLz4KPHJlY3QgeD0iNzEuNzU3OCIgeT0iMTkuMzkzOSIgd2lkdGg9IjIzLjI3MjciIGhlaWdodD0iMTkuMzkzOSIgZmlsbD0iIzlFQTNBOSIvPgo8L3N2Zz4K';

		public function __construct() {
			$this->settings_api = new SettingsAPI();
			$this->integration_api = new IntegrationAPI();
			$this->integration = new IntegrationHelper();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_init() {

			// set the settings.
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_navigation( $this->get_settings_navigation() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			// initialize settings.
			$this->settings_api->admin_init();
		}

		public function admin_menu() {

			$title = __( 'InputWP', 'date-time-picker-field' );
			$settings_title = __( 'Settings', 'date-time-picker-field' );
			$integration_title = __( 'Integration', 'date-time-picker-field' );

			add_menu_page( $title, $title, 'manage_options', 'dtpicker', array( $this, 'plugin_page' ), 'data:image/svg+xml;base64,' . self::$menu_svg );
			add_submenu_page( 'dtpicker', $settings_title, $settings_title, 'manage_options', 'dtpicker', array( $this, 'plugin_page' ));
			add_submenu_page( 'dtpicker', $integration_title, $integration_title, 'manage_options', 'dtp_integration', array( $this, 'integration_page' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		/**
		 * Enqueue scripts and styles
		 */
		public function admin_enqueue_scripts() {

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );

			wp_register_style( 'dtpkr-slider-style', plugins_url( '../../assets/css/', __FILE__  ) . 'slider.css', false, '1.0.0' );
			wp_register_style( 'dtpkr-admin-style', plugins_url( '../../assets/css/', __FILE__  ) . 'custom-admin-styles.css', false, '1.0.0' );
			wp_enqueue_style( 'dtpkr-slider-style' );
			wp_enqueue_style( 'dtpkr-admin-style' );

			if( is_admin() ) {

					$pickers = $this->integration->get_date_time_pickers(false);
					$pickers_n_selectors = $this->integration->get_pickers_n_selectors();

					//wp_enqueue_script( 'dtpicker-lite-integratrion', plugins_url( '../../assets/js/', __FILE__  ) . 'integration.js', array( 'wp-color-picker' ), false, true );
					wp_localize_script( 'dtpicker-lite-integratrion', 'intregation_obj_lite',
						array(
							'ajaxurl'           => admin_url( 'admin-ajax.php' ),
							'pickers'           => $pickers,
							'pickers_n_selectors' => $pickers_n_selectors,
						)
					);
			}
		}


		public function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'dtpicker',
					'title' => __( 'Basic Settings', 'date-time-picker-field' ),
				),

				array(
					'id'    => 'dtpicker_advanced',
					'title' => __( 'Advanced Settings', 'date-time-picker-field' ),
				),
			);
			return $sections;
		}

		public function get_settings_navigation() {
			$sections = array(
				array(
					'id'    => 'dateTimePicker',
					'title' => __( 'Date and Time picker', 'date-time-picker-field' ),
				),
				array(
					'id'    => 'timePicker',
					'title' => __( 'Time picker', 'date-time-picker-field' ),
				),
				array(
					'id'    => 'datePicker',
					'title' => __( 'Date picker', 'date-time-picker-field' ),
				),
				array(
					'id'    => 'dateRange',
					'title' => '<span class="pro-tab">' . __( 'Date range', 'date-time-picker-field' ) . '</span><sup class="red"><small>PRO</small></sup>',
				),
			);
			return $sections;
		}


		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		public function get_settings_fields() {

			global $wp_locale;

			$tzone = get_option( 'timezone_string' );

			// existing languages in datetime jquery script.
			$available = $this->available_lang_codes();
			$langs     = array_keys( $available );

			$languages         = array();
			$languages['auto'] = __( 'Default - Detect page language', 'date-time-picker-field' );

			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
			$translations = wp_get_available_translations();
			foreach ( $langs as $locale ) {
				if ( isset( $translations[ $locale ] ) ) {
					$translation                        = $translations[ $locale ];
					$languages[ $available[ $locale ] ] = $translation['native_name'];
				} else {
					if ( $locale === 'en_US' ) {
						// we don't translate this string, since we are displaying in native name.
						$languages['en'] = 'English (US)';
					}
				}
			}

			/* translators: %s is a day of the week */
			$allowed_string = __( 'Allowed times', 'date-time-picker-field' );

			$settings_fields = array(
				'dtpicker' => array(

					array(
						'name'              => 'selector',
						'label'             => __( 'CSS Selector', 'date-time-picker-field' ),
						'desc'              => __( 'Selector of the input field you want to target and transform into a picker. You can enter multiple selectors separated by commas.', 'date-time-picker-field' ),
						'placeholder'       => __( 'eg. \'.birthday\'', 'date-time-picker-field' ),
						'type'              => 'hidden',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'all',
					),
					array(
						'name'    => 'datepicker',
						'label'   => __( 'Display Calendar', 'date-time-picker-field' ),
						'desc'    => __( 'Display date picker calendar.', 'date-time-picker-field' ),
						'type'    => 'hidden',
						'value'   => '1',
						'default' => 'on',
						'tab'	  => 'all',
					),

					array(
						'name'    => 'timepicker',
						'label'   => __( 'Display Time', 'date-time-picker-field' ),
						'desc'    => __( 'Display time picker.', 'date-time-picker-field' ),
						'type'    => 'hidden',
						'value'   => '1',
						'default' => 'on',
						'tab'	  => 'all',
					),

					array(
						'name'    => 'minDate',
						'label'   => __( 'Disable past dates', 'date-time-picker-field' ),
						'desc'    => sprintf(
							// translators: the %s will be a timezone name
							__( 'If enabled, past dates (and times) can\'t be selected. Consider the plugin will use the timezone you have in your general settings to perform this calculation. Your current timezone is %s.', 'date-time-picker-field' ),
							wp_timezone_string()
						),
						'type'    => 'togglebutton',
						'value'   => 'on',
						'default' => 'off',
						'tab'	  => 'datePicker',
					),

					array(
						'name'    => 'disabled_calendar_days',
						'label'   => __( 'Disable specific dates', 'date-time-picker-field' ),
						'type'    => 'text',
						'desc'    => __( 'Add the dates you want to disable divided by commas, in the format you have selected. Useful to disable holidays for example.', 'date-time-picker-field' ),
						'default' => '',
						'data'	  => 'dtpicker_advanced',
						'tab'	  => 'datePicker',
					),

					array(
						'name'    => 'disabled_days',
						'label'   => __( 'Disable week days', 'date-time-picker-field' ),
						'desc'    => __( 'Select days you want to disable.', 'date-time-picker-field' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'0' => $wp_locale->get_weekday( 0 ),
							'1' => $wp_locale->get_weekday( 1 ),
							'2' => $wp_locale->get_weekday( 2 ),
							'3' => $wp_locale->get_weekday( 3 ),
							'4' => $wp_locale->get_weekday( 4 ),
							'5' => $wp_locale->get_weekday( 5 ),
							'6' => $wp_locale->get_weekday( 6 ),
						),
						'tab'			=> 'datePicker',
						'data' 		=> 'dtpicker_advanced',
					),

					array(
						'name'              => 'min_date',
						'label'             => __( 'Minimum date', 'date-time-picker-field' ),
						'desc'              => __( 'Use the European day-month-year format or an english string that is accepted by the <a target="_blank" href="https://php.net/manual/en/function.strtotime.php">strtotime PHP function</a>. (Ex: "+5 days") Leave empty to set no limit.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'datePicker',
					),

					array(
						'name'              => 'max_date',
						'label'             => __( 'Maximum date', 'date-time-picker-field' ),
						'desc'              => __( 'Use the European day-month-year format or an english string that is accepted by the <a target="_blank" href="https://php.net/manual/en/function.strtotime.php">strtotime PHP function</a>. (Ex: "+5 days") Leave empty to set no limit.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'datePicker',
					),

					array(
						'name'				=> 'use_max_date_as_default',
						'label'    			=> __( 'Use Maximum Date as Default', 'date-time-picker-field' ),
						'desc'				=> __( 'By default the plugin will consider today or the min date as the default value. You can enable this option to use the Maximum Date as the default value.', 'date-time-picker-field' ),
						'type' 				=> 'checkbox',
						'default'			=> false,
						'dependency'		=> array( 'max_date', '!=', '' ),
						'tab'				=> 'datePicker',
					),

					array(
						'name'              => 'days_offset',
						'label'             => __( 'Days offset ', 'date-time-picker-field' ),
						'desc'              => __( 'Set the next available slot to advance at least X available days. Write the number of days here.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '0',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'datePicker',
					),

					array(
						'name'    => 'dateformat',
						'label'   => __( 'Date format', 'date-time-picker-field' ),
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'YYYY-MM-DD'  => __( 'Year-Month-Day', 'date-time-picker-field' ) . ' ' . current_time( 'Y-m-d' ),
							'DD-MM-YYYY'  => __( 'Day-Month-Year', 'date-time-picker-field' ) . ' ' . current_time( 'd-m-Y' ),
							'MM-DD-YYYY'  => __( 'Month-Day-Year', 'date-time-picker-field' ) . ' ' . current_time( 'm-d-Y' ),
							'MMM-DD-YYYY' => __( 'MONTH-Day-Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'M-d-Y' ),
							'DD-MMM-YYYY' => __( 'MONTH-Day-Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'd-M-Y' ),
							'YYYY/MM/DD'  => __( 'Year/Month/Day', 'date-time-picker-field' ) . ' ' . current_time( 'Y/m/d' ),
							'DD/MM/YYYY'  => __( 'Day/Month/Year', 'date-time-picker-field' ) . ' ' . current_time( 'd/m/Y' ),
							'MM/DD/YYYY'  => __( 'Month/Day/Year', 'date-time-picker-field' ) . ' ' . current_time( 'm/d/Y' ),
							'MMM/DD/YYYY' => __( 'MONTH/Day/Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'M/d/Y' ),
							'DD/MMM/YYYY' => __( 'Day/MONTH/Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'd/M/Y' ),
							'DD.MM.YYYY'  => __( 'Day.Month.Year', 'date-time-picker-field' ) . ' ' . current_time( 'd.m.Y' ),
							'MM.DD.YYYY'  => __( 'Month.Day.Year', 'date-time-picker-field' ) . ' ' . current_time( 'm.d.Y' ),
							'YYYY.MM.DD'  => __( 'Year.Month.Dat', 'date-time-picker-field' ) . ' ' . current_time( 'Y.m.d' ),
							'MMM.DD.YYYY' => __( 'MONTH.Day.Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'M.d.Y' ),
							'DD.MMM.YYYY' => __( 'Day.MONTH.Year (english only)', 'date-time-picker-field' ) . ' ' . current_time( 'd.M.Y' ),
							'YYYYMMDD'    => __( 'YearMonthDay', 'date-time-picker-field' ) . ' ' . current_time( 'Ymd' ),
						),
						'default' => 'YYYY-MM-DD',
						'tab'			=> 'datePicker',
					),

					array(
						'name'    => 'picker_type',
						'label'   => __( 'Type', 'date-time-picker-field' ),
						'desc'    => __( 'Use this optional field to provide a description of your event.', 'date-time-picker-field' ),
						'type'    => 'radiogroup',
						'options' => array(
							'datetimepicker' => 'Date and Time picker (Default)',
							'datepicker' => 'Date picker',
							'timepicker' => 'Time picker',
							'daterange' => 'Date range (PRO Feature)'
						),
						'default' => 'datetimepicker',
						'disabled'=> 'daterange',
						'tab'	  => 'general',
					),

					array(
						'name'    => 'inline',
						'label'   => __( 'Display Inline', 'date-time-picker-field' ),
						'desc'    => __( 'Display calendar and/or time picker inline.', 'date-time-picker-field' ),
						'type'    => 'togglebutton',
						'value'   => '1',
						'default' => 'off',
						'tab'	  => 'general',
					),

					array(
						'name'    => 'placeholder',
						'label'   => __( 'Placeholder', 'date-time-picker-field' ),
						'desc'    => __( 'If enabled, original placeholder will be kept. If disabled it will be replaced with current date or next available time depending on your settings.', 'date-time-picker-field' ),
						'type'    => 'togglebutton',
						'value'   => '1',
						'default' => 'off',
						'tab'	  => 'general',
					),

					array(
						'name'    => 'preventkeyboard',
						'label'   => __( 'Prevent keyboard edit', 'date-time-picker-field' ),
						'desc'    => __( 'If enabled, it wont be possible to edit the text. This will also prevent the keyboard on mobile devices to display when selecting the date.', 'date-time-picker-field' ),
						'type'    => 'togglebutton',
						'value'   => 'on',
						'default' => 'off',
						'tab'	  => 'general',
					),

					array(
						'name'    => 'locale',
						'label'   => __( 'Language', 'date-time-picker-field' ),
						'desc'    => __( 'Language to display the month and day labels.', 'date-time-picker-field' ),
						'type'    => 'select',
						'default' => 'auto',
						'options' => $languages,
						'tab'	  => 'general',
					),

					array(
						'name'    => 'theme',
						'label'   => __( 'Theme', 'date-time-picker-field' ),
						'desc'    => __( 'Calendar visual style.', 'date-time-picker-field' ),
						'type'    => 'select',
						'default' => 'default',
						'options' => array(
							'default' => __( 'Default', 'date-time-picker-field' ),
							'dark'    => __( 'Dark', 'date-time-picker-field' ),
						),
						'tab'	  => 'general',
					),


					array(
						'name'    => 'load',
						'label'   => __( 'When to Load', 'date-time-picker-field' ),
						'desc'    => __( 'Choose to search for the css selector across the website or only when the shortcode [datetimepicker] exists on a page. Use the shortcode to prevent the script from loading across all pages.', 'date-time-picker-field' ),
						'type'    => 'select',
						'options' => array(
							'full'      => __( 'Across the full website', 'date-time-picker-field' ),
							'admin'     => __( 'Admin panel only', 'date-time-picker-field' ),
							'fulladmin' => __( 'Full website including admin panel', 'date-time-picker-field' ),
							'shortcode' => __( 'Only when shortcode [datetimepicker] exists on a page.', 'date-time-picker-field' ),
						),
						'default' => 'full',
						'tab'	  => 'general',
					),

					array(
						'name'              => 'step',
						'label'             => __( 'Time Step', 'date-time-picker-field' ),
						'desc'              => __( 'Time interval in minutes for time picker options.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '60',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'timePicker',
					),

					array(
						'name'              => 'minTime',
						'label'             => __( 'Minimum time', 'date-time-picker-field' ),
						'desc'              => __( 'Time options will start from this. Leave empty for none. Use the format you selected for the time. For example: 08:00 AM.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'timePicker',
					),

					array(
						'name'              => 'maxTime',
						'label'             => __( 'Maximum time', 'date-time-picker-field' ),
						'desc'              => __( 'Time options will not be later than this specified time. Leave empty for none. Use the format you selected for the time. For example: 08:00 PM.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'timePicker',
					),

					array(
						'name'              => 'offset',
						'label'             => __( 'Time offset', 'date-time-picker-field' ),
						'desc'              => __( 'Time interval in minutes to advance next available time. For example, set "45" if you only want time entries 45m from now to be available. Works better when option to disable past dates is also enabled.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
						'tab'				=> 'timePicker',
					),

					array(
						'name'    => 'allowed_times',
						'label'   => __( 'Global allowed times', 'date-time-picker-field' ),
						'type'    => 'text',
						'desc'    => __( 'Write the allowed times to override the time step and serve as default if you use the options below.<br> Values still need to be within minimum and maximum times defined in the basic settings.<br> Use the time format separated by commas. <br>Example: 09:00,11:00,12:00,21:00 You need to list all the options.', 'date-time-picker-field' ),
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),

					array(
						'name'    => 'sunday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 0 ),
						'type'    => 'text',
						'default' => '',
						'tab'			=> 'timePicker',
						'data' 		=> 'dtpicker_advanced',
					),

					array(
						'name'    => 'monday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 1 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),

					array(
						'name'    => 'tuesday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 2 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),

					array(
						'name'    => 'wednesday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 3 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),
					array(
						'name'    => 'thursday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 4 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),
					array(
						'name'    => 'friday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 5 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),
					array(
						'name'    => 'saturday_times',
						'label'   => 'Allowed times for '. $wp_locale->get_weekday( 6 ),
						'type'    => 'text',
						'default' => '',
						'tab'	  => 'timePicker',
						'data' 	  => 'dtpicker_advanced',
					),

					array(
						'name'    => 'hourformat',
						'label'   => __( 'Hour Format', 'date-time-picker-field' ),
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'HH:mm'   => 'H:M ' . current_time( 'H:i' ),
							'hh:mm A' => 'H:M AM/PM ' . current_time( 'h:i A' ),
						),
						'default' => 'hh:mm A',
						'tab'	  => 'timePicker',
					),
				),
			);

			return $settings_fields;
		}

		public function plugin_page() {
			echo $this->settings_api->top_bar();
			echo '<div class="dtpkr-wrap settings-page">';
			$this->settings_api->show_forms();
			echo '</div>';
		}

		public function integration_page() {

			$this->integration_api->show_forms();
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		public function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}


		/**
		 * Get array with available languages where key is the WordPress lang code and value is the jquery script lang code.
		 *
		 * @return array of language codes
		 */
		public function available_lang_codes() {

			$available = array(
				'ar'    => 'ar',
				'az'    => 'az',
				'bg_BG' => 'bg',
				'bs_BG' => 'bs',
				'ca'    => 'ca',
				'zh_CN' => 'ch',
				'cz_CZ' => 'cs',
				'da_DK' => 'da',
				'de_DE' => 'de',
				'el'    => 'el',
				'en_US' => 'en',
				'en_GB' => 'en-GB',
				'es_ES' => 'es',
				'et'    => 'et',
				'eu'    => 'eu',
				'fa_IR' => 'fa',
				'fi'    => 'fi',
				'fr_FR' => 'fr',
				'gl_ES' => 'gl',
				'he_IL' => 'he',
				'hr'    => 'hr',
				'hu_HU' => 'hu',
				'id_ID' => 'id',
				'it_IT' => 'it',
				'ja   ' => 'ja',
				'ko_KO' => 'ko',
				'kr_KR' => 'kr',
				'lt_LT' => 'lt',
				'lv'    => 'lv',
				'mk_MK' => 'mk',
				'mn'    => 'mn',
				'nl_NL' => 'nl',
				'nb_NO' => 'no',
				'pl_PL' => 'pl',
				'pt_PT' => 'pt',
				'pt_BR' => 'pt-BR',
				'ro_RO' => 'ro',
				'ru_RU' => 'ru',
				'sv_SE' => 'se',
				'sk_SK' => 'sk',
				'sl_SL' => 'sl',
				'sq'    => 'sq',
				'sr_RS' => 'sr',
				'sr_YU' => 'sr-YU',
				'sv_SE' => 'sv',
				'th'    => 'th',
				'tr_TR' => 'tr',
				'uk'    => 'uk',
				'vi'    => 'vi',
				'zh_ZH' => 'zh',
				'zh_TW' => 'zh-TW',
			);

			return $available;

		}

	}
}
