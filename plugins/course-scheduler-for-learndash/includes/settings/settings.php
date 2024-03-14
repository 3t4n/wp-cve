<?php
/**
 * WN DASHBOARD SETTINGS
 *
 * @author   WooNinjas
 * @category Admin
 * @package  WN_DASHBOARD_SETTINGS
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WN_DASHBOARD_SETTINGS
 */
if( !class_exists('WN_DASHBOARD_SETTINGS') ) {
	class WN_DASHBOARD_SETTINGS {

		private $settings_api;

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			$this->settings_api = new Wn_Plugin_Settings_API();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_filter( 'wn_dashboard_setting_sections', [ $this, 'set_setting_section' ], 10, 1 );
			add_filter( 'wn_dashboard_setting_fields', [ $this, 'set_setting_fields' ], 10, 1 );
		}

		function admin_init() {

			//initialize settings
			$this->settings_api->admin_init();
		}

		function set_setting_section( $sections ) {

			$sections['wn_course_schedular'] = array(
				'id'         => 'wn_course_schedular',
				'title'      => __( 'Course Scheduler', 'cs_ld_addon' ),
				'has_subtab' => 'Yes',
				'subtabs'    => array(
					array(
						'id'    => 'wn_course_schedular_general_settings',
						'title' => __( 'General Settings', 'cs_ld_addon' ),
					),
					array(
						'id'    => 'wn_course_schedular_course_settings',
						'title' => __( LearnDash_Custom_Label::get_label( 'course' ) . ' Message', 'cs_ld_addon' ),
					),
					array(
						'id'    => 'wn_course_schedular_lesson_settings',
						'title' => __( LearnDash_Custom_Label::get_label( 'lesson' ) . ' Message', 'cs_ld_addon' ),
					),
					array(
						'id'    => 'wn_course_schedular_topic_settings',
						'title' => __( LearnDash_Custom_Label::get_label( 'topic' ) . ' Message', 'cs_ld_addon' ),
					),
					array(
						'id'    => 'wn_course_schedular_quiz_settings',
						'title' => __( LearnDash_Custom_Label::get_label( 'quiz' ) . ' Message', 'cs_ld_addon' ),
					),
				)
			);

			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function set_setting_fields( $settings_fields ) {
			$settings_fields['wn_course_schedular_general_settings'] = array(
				array(
					'name'    => 'show_courses',
					'label'   => __( 'Course Scheduler Behavior?', 'cs_ld_addon' ),
					'desc'    => array(
						'1' => __( 'Set this option if you want to show the ' . LearnDash_Custom_Label::get_label( 'courses' ) . ' "only" on the dates set on the calendar', 'cs_ld_addon' ),
						'0' => __( 'Set this option if you want to hide the ' . LearnDash_Custom_Label::get_label( 'courses' ) . ' on the dates set on the calendar', 'cs_ld_addon' ),
					),
					'type'    => 'radio',
					'class'   => 'wn_course_schedular_general_settings_show_courses',
					'options' => array(
						'1' => __( 'Show Courses on specified dates', 'cs_ld_addon' ),
						'0' => __( 'Hide Courses on the specified dates', 'cs_ld_addon' ),
					)
				),
			);
			$settings_fields['wn_course_schedular_course_settings']  = array(
				array(
					'name'    => 'show_message',
					'label'   => __( 'Message when "Show Courses on specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits Course page and that Course has been scheduled for a later date with the first setting option being selected in the general settings. You can use [cs_scheduled_dates] to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
				array(
					'name'    => 'hide_message',
					'label'   => __( 'Message when "Hide Courses on the specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits Course page and that Course has been scheduled for dates except the ones set on calendar, with the second setting option being selected in the general settings. You can use [cs_scheduled_dates] to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
			);
			$settings_fields['wn_course_schedular_lesson_settings']  = array(
				array(
					'name'    => 'show_message',
					'label'   => __( 'Message when "Show ' . LearnDash_Custom_Label::get_label( 'courses' ) . ' on specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits ' . LearnDash_Custom_Label::get_label( 'lesson' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'lesson' ) . ' has been scheduled for a later date with the first setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
				array(
					'name'    => 'hide_message',
					'label'   => __( 'Message when "Hide Courses on the specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits ' . LearnDash_Custom_Label::get_label( 'lesson' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'lesson' ) . ' has been scheduled for dates except the ones set on calendar, with the second setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
			);
			$settings_fields['wn_course_schedular_topic_settings']   = array(
				array(
					'name'    => 'show_message',
					'label'   => __( 'Message when "Show ' . LearnDash_Custom_Label::get_label( 'courses' ) . ' on specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user ' . LearnDash_Custom_Label::get_label( 'topic' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'topic' ) . ' has been scheduled for a later date with the first setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
				array(
					'name'    => 'hide_message',
					'label'   => __( 'Message when "Hide Courses on the specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits ' . LearnDash_Custom_Label::get_label( 'topic' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'topic' ) . ' has been scheduled for dates except the ones set on calendar, with the second setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
			);
			$settings_fields['wn_course_schedular_quiz_settings']    = array(
				array(
					'name'    => 'show_message',
					'label'   => __( 'Message when "Show ' . LearnDash_Custom_Label::get_label( 'courses' ) . ' on specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits ' . LearnDash_Custom_Label::get_label( 'quiz' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'quiz' ) . ' has been scheduled for a later date with the first setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "The " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
				array(
					'name'    => 'hide_message',
					'label'   => __( 'Message when "Hide Courses on the specified dates" setting enabled', 'cs_ld_addon' ),
					'desc'    => __( '<b>Note:</b>This message will be shown on frontend when user visits ' . LearnDash_Custom_Label::get_label( 'quiz' ) . ' page and that ' . LearnDash_Custom_Label::get_label( 'quiz' ) . ' has been scheduled for dates except the ones set on calendar, with the second setting option being selected in the general settings. You can use <strong>[cs_scheduled_dates]</strong> to get the scheduled dates', 'cs_ld_addon' ),
					'type'    => 'wysiwyg',
					'default' => __( "This " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", 'cs_ld_addon' ),
				),
			);

			return $settings_fields;
		}


		/**
		 * Setting notification
		 */
		public function save_settings_notification() {
			$class   = 'notice notice-success is-dismissible';
			$message = __( 'Settings Saved.', 'cs_ld_addon' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		}

		/**
		 * Setting page data
		 */
		public function settings_page() {
			wp_enqueue_script( 'wn-plugin-dashboard' );
			echo '<div class=" wn_dashboard_tab_content">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}
	}

	new WN_DASHBOARD_SETTINGS();
}