<?php
/**
 * The Metadata and Options.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta & Option class.
 */
trait REVIVESO_Admin_Settings {

	public function get_settings_fields() {
		$settings = apply_filters(
			'reviveso_admin_settings',
			array(
				'general' => array(
					'query'     => array(
						'title'  => __( 'General Settings', 'revive-so' ),
						'name'   => __( 'General', 'revive-so' ),
						'fields' => array(
							'republish_post_age' => array(
								'type'        => 'select',
								'name'        => 'reviveso_republish_post_age',
								'label'       => __( 'Post Republish Eligibility Age', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_republish_post_age', 30 ),
								'description' => __( 'Select the post age for republishing. Post originally published before this, will be available for republish. Note: If a post is already republished, then plugin will consider the new republished date, not the actual published date.', 'revive-so' ),
								'options'     => $this->do_filter( 'republish_eligibility_age', array(
									'premium_1' => __( 'No Age Limit (Premium)', 'revive-so' ),
									'30'        => __( '30 Days (1 month)', 'revive-so' ),
									'45'        => __( '45 Days (1.5 months)', 'revive-so' ),
									'60'        => __( '60 Days (2 months)', 'revive-so' ),
									'90'        => __( '90 Days (3 months)', 'revive-so' ),
									'120'       => __( '120 Days (4 months)', 'revive-so' ),
									'180'       => __( '180 Days (6 months)', 'revive-so' ),
									'240'       => __( '240 Days (8 months)', 'revive-so' ),
									'365'       => __( '365 Days (1 year)', 'revive-so' ),
									'730'       => __( '730 Days (2 years)', 'revive-so' ),
									'1095'      => __( '1095 Days (3 years)', 'revive-so' ),
									'premium_2' => __( 'Custom Age Limit (Premium)', 'revive-so' ),
								) ),
								'priority'    => 10,
							),
							'republish_order'    => array(
								'type'        => 'select',
								'name'        => 'reviveso_republish_method',
								'label'       => __( 'Select Published Posts Order', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_republish_method', 'old_first' ),
								'description' => __( 'Select the method of getting old posts from database.', 'revive-so' ),
								'options'     => array(
									'old_first' => __( 'Select Old Post First (ASC)', 'revive-so' ),
									'new_first' => __( 'Select New Post First (DESC)', 'revive-so' ),
								),
								'priority'    => 15,
							),
							'republish_orderby'  => array(
								'type'        => 'select',
								'name'        => 'reviveso_republish_orderby',
								'label'       => __( 'Select Published Posts Order by', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_republish_orderby', 'date' ),
								'description' => __( 'Select the method of getting old posts order by parameter. Default: Post Date', 'revive-so' ),
								'options'     => $this->do_filter( 'republish_orderby_items', array(
									'date'      => __( 'Post Date', 'revive-so' ),
									'premium_1' => __( 'Post ID (Premium)', 'revive-so' ),
									'premium_2' => __( 'Post Author (Premium)', 'revive-so' ),
									'premium_3' => __( 'Post Title (Premium)', 'revive-so' ),
									'premium_4' => __( 'Post Name (Premium)', 'revive-so' ),
									'premium_5' => __( 'Random Selection (Premium)', 'revive-so' ),
									'premium_6' => __( 'Comment Count (Premium)', 'revive-so' ),
									'premium_7' => __( 'Relevance (Premium)', 'revive-so' ),
									'premium_8' => __( 'Menu Order (Premium)', 'revive-so' ),
								) ),
								'priority'    => 20,
							),
						),
					),
					'filter'    => array(
						'title'  => __( 'Filter Options', 'revive-so' ),
						'name'   => __( 'Filter', 'revive-so' ),
						'fields' => array(
							'post_types_list'   => array(
								'type'        => 'multiple',
								'name'        => 'reviveso_post_types',
								'label'       => __( 'Select Post Type(s) to Republish', 'revive-so' ),
								'class'       => 'reviveso-post-types',
								'value'       => $this->get_data( 'reviveso_post_types', array( 'post' ) ),
								'description' => __( 'Select the post types of which you want to republish using global method.', 'revive-so' ),
								'options'     => $this->get_post_types(),
								'priority'    => 10,
							),
							'taxonomies_filter' => array(
								'type'        => 'select',
								'name'        => 'reviveso_exclude_by_type',
								'label'       => __( 'Post Types Taxonomies Filter', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_exclude_by_type', 'none' ),
								'description' => __( 'Select how you want to include or exclude a post category from republishing. If you choose Exclude Taxonomies, selected taxonomies will be ignored and Include will add them only.', 'revive-so' ),
								'options'     => array(
									'none'    => __( 'Disable', 'revive-so' ),
									'include' => __( 'Include Taxonomies', 'revive-so' ),
									'exclude' => __( 'Exclude Taxonomies', 'revive-so' ),
								),
								'priority'    => 15,
							),
							'post_taxonomy'     => array(
								'type'        => 'multiple_tax',
								'name'        => 'reviveso_post_taxonomy',
								'label'       => __( 'Select Post Type(s) Taxonomies', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_post_taxonomy', array() ),
								'description' => __( 'Select taxonimies which you want to include to republishing or exclude from republishing.', 'revive-so' ),
								'class'       => 'reviveso-taxonomies',
								'condition'   => array( 'reviveso_exclude_by_type', '!=', 'none' ),
								'priority'    => 20,
							),
							'force_include'     => array(
								'type'        => 'multiple',
								'name'        => 'force_include',
								'label'       => __( 'Force Include Post IDs', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_force_include', '' ),
								'class'       => 'reviveso-force-include',
								'description' => __( 'Write the post IDs which you want to include forcefully in the republish process. But it doesn\'t mean that it will republish every time, rather it will added to the republish eligible post list. These posts will be republished only if the orther conditions are met.', 'revive-so' ),
								'priority'    => 25,
							),
							'force_exclude'     => array(
								'type'        => 'multiple',
								'name'        => 'reviveso_override_category_tag',
								'label'       => __( 'Force Exclude Post IDs', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_force_exclude', '' ),
								'class'       => 'reviveso-force-exclude',
								'description' => __( 'Write the post IDs which you want to exclude forcefully from the republish process.', 'revive-so' ),
								'priority'    => 30,
							),
						),
					),
					'display'   => array(
						'title'  => __( 'Frontend Visibility', 'revive-so' ),
						'name'   => __( 'Visibility', 'revive-so' ),
						'fields' => array(
							'republish_info'      => array(
								'type'        => 'select',
								'name'        => 'reviveso_republish_position',
								'label'       => __( 'Show Original Publication Date', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_republish_position', 'disable' ),
								'description' => __( 'This will show original published date of the post on frontend only for Republished Posts. Before Content option will push Republish info to top and After Content will push post content to top. You can keep it disable if you don\'t want to use this.', 'revive-so' ),
								'options'     => array(
									'disable'        => __( 'Disable', 'revive-so' ),
									'before_content' => __( 'Before Content', 'revive-so' ),
									'after_content'  => __( 'After Content', 'revive-so' ),
								),
								'priority'    => 10,
							),
							'republish_info_text' => array(
								'name'        => 'reviveso_republish_position_text',
								'label'       => __( 'Original Publication Message', 'revive-so' ),
								'class'       => 'expand',
								'value'       => $this->get_data( 'reviveso_republish_position_text', 'Originally posted on ' ),
								'description' => __( 'Message before original published date of the post on frontend. It will work like prefix. Post Republish info will be added after this prefix if actually exists.', 'revive-so' ),
								'required'    => true,
								'condition'   => array( 'reviveso_republish_info', '!=', 'disable' ),
								'priority'    => 15,
							),
						),
					),
					'configure' => array(
						'title'  => __( 'Advanced Settings', 'revive-so' ),
						'name'   => __( 'Advanced', 'revive-so' ),
						'fields' => array(
							'republish_interval_days'    => array(
								'type'        => 'number',
								'name'        => 'republish_interval_days',
								'label'       => __( 'Schedule Auto Republish Process Every (in days)', 'revive-so' ),
								'value'       => $this->get_data( 'republish_interval_days', '1' ),
								'description' => __( 'Set custom interval in Days. Default 1 day means plugin will republish on everyday if all weekdays are selected.', 'revive-so' ),
								'attributes'  => array(
									'min' => 1,
								),
								'priority'    => 10,
							),
							'minimun_republish_interval' => array(
								'type'        => 'select',
								'name'        => 'reviveso_minimun_republish_interval',
								'label'       => __( 'Republish Process Interval within a Day', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_minimun_republish_interval', 300 ),
								'description' => __( 'Select post republish interval between two post republish events. It will be added to the Last Run time (see top right) and will re-run at a point of time which resolves Last Run plus this settings. If Last Run is Today at 10:05 AM and this settings is set to 30 Minutes, then next process will run at 10:35 AM. Although, running of this process doesn\'t mean that it will republish a post every time. It will also check if the below conditions are met, if not, republish will not work at that particular point of time.', 'revive-so' ),
								'options'     => $this->do_filter( 'minimum_republish_interval', array(
									'300'     => __( '5 Minutes', 'revive-so' ),
									'600'     => __( '10 Minutes', 'revive-so' ),
									'900'     => __( '15 Minutes', 'revive-so' ),
									'1200'    => __( '20 Minutes', 'revive-so' ),
									'1800'    => __( '30 Minutes', 'revive-so' ),
									'2700'    => __( '45 Minutes', 'revive-so' ),
									'3600'    => __( '1 hour', 'revive-so' ),
									'7200'    => __( '2 hours', 'revive-so' ),
									'14400'   => __( '4 hours', 'revive-so' ),
									'21600'   => __( '6 hours', 'revive-so' ),
									'28800'   => __( '8 hours', 'revive-so' ),
									'43200'   => __( '12 hours', 'revive-so' ),
									'premium' => __( 'Custom Interval (Premium)', 'revive-so' ),
								) ),
								'priority'    => 15,
							),
							'random_republish_interval'  => array(
								'type'        => 'select',
								'name'        => 'reviveso_random_republish_interval',
								'label'       => __( 'Date Time Random Interval', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_random_republish_interval', 3600 ),
								'description' => __( 'Select randomness interval from here which will be added to post republish time. If republish process runs at 11.25 AM and this option is set to Upto 1 Hour, post will be republished at anytime between 11.25 AM and 12.25 PM. It can make the republishing seem more natural to Readers and SERPs.', 'revive-so' ),
								'options'     => $this->do_filter( 'random_republish_interval', array(
									'premium_1'  => __( 'No Randomness (Premium)', 'revive-so' ),
									'premium_2'  => __( 'Upto 5 Minutes (Premium)', 'revive-so' ),
									'premium_3'  => __( 'Upto 10 Minutes (Premium)', 'revive-so' ),
									'premium_4'  => __( 'Upto 15 Minutes (Premium)', 'revive-so' ),
									'premium_5'  => __( 'Upto 20 Minutes (Premium)', 'revive-so' ),
									'premium_6'  => __( 'Upto 30 Minutes (Premium)', 'revive-so' ),
									'premium_7'  => __( 'Upto 45 Minutes (Premium)', 'revive-so' ),
									'3600'       => __( 'Upto 1 hour', 'revive-so' ),
									'7200'       => __( 'Upto 2 hours', 'revive-so' ),
									'14400'      => __( 'Upto 4 hours', 'revive-so' ),
									'21600'      => __( 'Upto 6 hours', 'revive-so' ),
									'premium_8'  => __( 'Upto 8 hours (Premium)', 'revive-so' ),
									'premium_9'  => __( 'Upto 12 hours (Premium)', 'revive-so' ),
									'premium_10' => __( 'Upto 24 hours (Premium)', 'revive-so' ),
								) ),
								'priority'    => 20,
							),
							'republish_post_position'    => array(
								'type'        => 'select',
								'name'        => 'reviveso_republish_post_position',
								'label'       => __( 'Republish Post to Position', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_republish_post_position', 'one' ),
								'description' => __( 'Select the position of republished post (choosing the 2nd position will leave the most recent post in 1st place). Let\'s say, your last post/republished post time is 02:45 PM, First Position will push the current republished post to before the last post and Second Position will attach current republished post just after last post with a interval of 5 minutes (can be modified by filter).', 'revive-so' ),
								'options'     => array(
									'one' => __( 'First Position', 'revive-so' ),
									'two' => __( 'Second Position', 'revive-so' ),
								),
								'priority'    => 25,
							),
							'republish_time_specific'    => array(
								'type'        => 'select',
								'name'        => 'republish_time_specific',
								'label'       => __( 'Time Specific Republishing', 'revive-so' ),
								'value'       => $this->get_data( 'republish_time_specific', 'no' ),
								'description' => sprintf( '%s <div class="reviveso-time-limit-msg red">%s <a href="">%s</a></div>', __( 'Enable or Disable Time Specifc Republish from here. If you Enable this, plugin will only republish between the Start Time and End Time. If Start Time is grater than End Time, plugin will assume the end time in on the next available day (if the next day is eligible for republish). No Time Limit will republish at any time.', 'revive-so' ), __( 'Note: If you are using Time Limits then you have to set the "Republish Process Interval within a Day" option as less than the interval between Start Time and End Time so that process interval fits within the interval. Otherwise, it will not work.', 'revive-so' ), '' ),
								'options'     => array(
									'no'  => __( 'No Time Limit', 'revive-so' ),
									'yes' => __( 'Set Time Limit', 'revive-so' ),
								),
								'priority'    => 30,
							),
							'republish_time_start'       => array(
								'name'        => 'reviveso_start_time',
								'label'       => __( 'Start Time for Republishing', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_start_time', '05:00:00' ),
								'description' => __( 'Set the starting time period for republish old posts from here. Republish will start from this time.', 'revive-so' ),
								'class'       => 'reviveso-timepicker',
								'attributes'  => array(
									'placeholder' => '05:00:00',
								),
								'required'    => true,
								'readonly'    => true,
								'condition'   => array( 'republish_time_specific', '=', 'yes' ),
								'priority'    => 35,
							),
							'republish_time_end'         => array(
								'name'        => 'reviveso_end_time',
								'label'       => __( 'End Time for Republishing', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_end_time', '23:59:59' ),
								'description' => __( 'Set the ending time period for republish old posts from here. Republish will not occur after this time.', 'revive-so' ),
								'class'       => 'reviveso-timepicker',
								'attributes'  => array(
									'placeholder' => '05:00:00',
								),
								'required'    => true,
								'readonly'    => true,
								'condition'   => array( 'republish_time_specific', '=', 'yes' ),
								'priority'    => 40,
							),
							'republish_days'             => array(
								'type'        => 'multiple',
								'name'        => 'reviveso_days',
								'label'       => __( 'Select Weekdays to Republish', 'revive-so' ),
								'value'       => $this->get_data( 'reviveso_days', array( 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ) ),
								'description' => __( 'Select the weekdays when you want to republish old posts. If you want to disable republish on any weekday, you can easily do it from here. Just remove that day and save your settings.', 'revive-so' ),
								'options'     => array(
									'sun' => __( 'Sunday', 'revive-so' ),
									'mon' => __( 'Monday', 'revive-so' ),
									'tue' => __( 'Tuesday', 'revive-so' ),
									'wed' => __( 'Wednesday', 'revive-so' ),
									'thu' => __( 'Thursday', 'revive-so' ),
									'fri' => __( 'Friday', 'revive-so' ),
									'sat' => __( 'Saturday', 'revive-so' ),
								),
								'priority'    => 45,
							),
							'remove_plugin_data'         => array(
								'type'        => 'checkbox',
								'name'        => 'reviveso_remove_plugin_data',
								'label'       => __( 'Delete Plugin Data on Uninstall?', 'revive-so' ),
								'checked'     => 1 == $this->get_data( 'reviveso_remove_plugin_data' ),
								'description' => __( 'Enable this if you want to remove all the plugin data from your website.', 'revive-so' ),
								'priority'    => 50,
							),
						),
					),
					'rewriting' => array(
						'title'       => __( 'Rewriting', 'revive-so' ),
						'name'        => __( 'Rewriting', 'revive-so' ),
						'fields'      => array(
							'reviveso_pro_rewrite_status' => array(
								'label'    => __( 'Status', 'revive-so' ),
								'type'     => 'rewrite_info_upsell',
								'name'     => '',
								'priority' => 10,
							),
						),
						'save_button' => false,
						'class'       => 'reviveso_danger',
						'type'        => 'upsell',
					),
				),
			)
		);

		// Let's sort the fields by priority

		// Loop every tab
		foreach ( $settings as $key => $setting ) {
			// Loop every section
			foreach ( $setting as $skey => $section ) {
				// Check if we have fields
				if ( ! empty( $section['fields'] ) ) {
					// Sort the 'fields' array by 'priority' value
					uasort( $settings[ $key ][ $skey ]['fields'], array( $this, 'sort_data_by_priority' ) );
				}
			}
		}

		return $settings;
	}

	public function get_tools_settings_fields() {
		$tools_settings = apply_filters(
			'reviveso_admin_tools_settings',
			array(
				'tools' => array(
					'tools' => array(
						'save_button' => false,
						'fields'      => array(
							'export'                => array(
								'name'        => 'export_settings',
								'type'        => 'tool-export',
								'label'       => __( 'Export Settings', 'revive-so' ),
								'description' => __( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'revive-so' ),
							),
							'import'                => array(
								'name'        => 'import_settings',
								'type'        => 'tool-import',
								'label'       => __( 'Import Settings', 'revive-so' ),
								'description' => __( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'revive-so' ),
							),
							'reset_settings'        => array(
								'type'        => 'tool',
								'label'       => __( 'Reset Settings', 'revive-so' ),
								'description' => __( 'Resetting will delete all custom options to the default settings of the plugin in your database.', 'revive-so' ),
								'notice'      => __( 'It will delete all the data relating to this plugin settings. You have to re-configure this plugin again. Do you want to still continue?', 'revive-so' ),
								'name'        => 'remove_data',
								'reload'      => true,
							),
							'de_schedule'           => array(
								'type'        => 'tool',
								'label'       => __( 'De-Schedule Posts', 'revive-so' ),
								'description' => __( 'It will change the republish date to the original post published date on all posts.', 'revive-so' ),
								'notice'      => __( 'It will change the republish date to the original post published date on all posts. Leave if you are not sure what you are doing. Do you want to still continue?', 'revive-so' ),
								'name'        => 'deschedule_posts',
							),
							're_create_db'          => array(
								'type'        => 'tool',
								'label'       => __( 'Re-Create Missing Database Tables', 'revive-so' ),
								'description' => __( 'Check if required tables exist and create them if not.', 'revive-so' ),
								'name'        => 'recreate_tables',
								'button'      => __( 'Re-Create Tables', 'revive-so' ),
								'button-type' => 'blue',
							),
							're_generate_republish' => array(
								'type'        => 'tool',
								'label'       => __( 'Re-Generate Republish Interval', 'revive-so' ),
								'description' => __( 'It will regenerate Schedule Auto Republish Process Interval.', 'revive-so' ),
								'name'        => 'regenerate_interval',
								'button'      => __( 'Re-Generate Interval', 'revive-so' ),
								'button-type' => 'blue',
							),
							're_generate_schedule'  => array(
								'type'        => 'tool',
								'label'       => __( 'Re-Generate Republish Schedule', 'revive-so' ),
								'description' => __( 'It will regenerate Schedule Auto Republish Schedules of Single Posts and Custom Rules.', 'revive-so' ),
								'notice'      => __( 'It will remove and re-create all the scheduled or missed republish events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'revive-so' ),
								'name'        => 'regenerate_schedule',
								'button'      => __( 'Re-Generate Schedule', 'revive-so' ),
								'show'        => apply_filters( 'reviveso_show_regenerate_republish_button', false ),
							),
							'remove_post_actions'   => array(
								'type'        => 'tool',
								'label'       => __( 'Remove Post Meta & Actions', 'revive-so' ),
								'description' => __( 'Resetting will delete all post metadatas and future action events associated with Post Republish.', 'revive-so' ),
								'notice'      => __( 'It will delete all the post meta data & action events relating to global and single post republishing. It may stop previous scheduled republished event. Leave if you are not sure what you are doing. Do you want to still continue?', 'revive-so' ),
								'name'        => 'remove_meta',
								'button'      => __( 'Clear Post Metas & Events', 'revive-so' ),
							),
						),
					),
				),
			),
		);

		/**
		 * Hook to add or remove tools settings fields.
		 *
		 * @hook reviveso_display_tools_settings
		 *       - To display the tools settings.
		 *       - Default: false
		 *       - Use add_filter( 'reviveso_display_tools_settings', '__return_true' );
		 */
		return ( apply_filters( 'reviveso_display_tools_settings', false ) ? $tools_settings : array() );
	}

	public function get_tabs() {
		$tabs            = array();
		$tabs['general'] = array( 'name' => __( 'General', 'revive-so' ) );
		/**
		 * Hook to add or remove tools settings fields.
		 *
		 * @hook reviveso_display_tools_settings
		 *       - To display the tools settings.
		 *       - Default: false
		 *       - Use add_filter( 'reviveso_display_tools_settings', '__return_true' );
		 */
		if ( apply_filters( 'reviveso_display_tools_settings', false ) ) {
			$tabs['tools'] = array( 'name' => __( 'Tools', 'revive-so' ) );
		}

		return apply_filters( 'reviveso_admin_tabs', $tabs );
	}

	/**
	 * Register plugin settings.
	 */
	public function setSettings() {
		$args = array(
			array(
				'option_group' => 'reviveso_plugin_settings_fields',
				'option_name'  => 'reviveso_plugin_settings',
			),
		);

		$args = apply_filters( 'reviveso_register_settings_setting', $args );

		foreach ( $args as $setting ) {
			register_setting( $setting["option_group"], $setting["option_name"], ( isset( $setting["callback"] ) ? $setting["callback"] : '' ) );
		}
	}

	public function render_settings_tabs() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
		foreach ( $this->get_tabs() as $key => $tab ) {
			$link = add_query_arg(
				array(
					'page' => 'reviveso',
					'tab'  => $key,
				),
				admin_url( 'admin.php' )
			);
			?>
			<a href="<?php
			echo esc_attr( $link ); ?>" class="reviveso-tab <?php
			echo( $active_tab == $key ? 'is-active' : '' );
			echo isset( $tab['class'] ) ? esc_attr( $tab['class'] ) : ''; ?> " id="reviveso-tab-<?php
			echo esc_attr( $key ); ?>"><?php
				echo esc_html( $tab['name'] ); ?><?php
				echo isset( $tab['badge'] ) ? '<span class="reviveso-badge">' . esc_attr( $tab['badge'] ) . '</span>' : ''; ?></a>
			<?php
		}
	}

	public function render_settings() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
		// Plugin Settings
		do_action( 'reviveso_before_settings_block' );
		echo '<form method="post" action="options.php" >';
		foreach ( $this->get_settings_fields() as $key => $items ) {
			echo '<div id="reviveso-settings-block-' . esc_attr( $key ) . '" class="reviveso-settings-block ' . ( $key != $tab ? 'd-none' : '' ) . '">';
			do_action( 'reviveso_before_' . $key . '_setting_block', $items );

			if ( ! empty( $items ) ) {
				settings_fields( 'reviveso_plugin_settings_fields' );
				$this->render_settings_subtabs( $items, $key );
				$this->render_settings_block( $items, $key );
			}

			echo '</div>';
			$first = false;
		}
		echo '</form>';

		// Plugin Tools
		foreach ( $this->get_tools_settings_fields() as $key => $items ) {
			echo '<div id="reviveso-settings-block-' . esc_attr( $key ) . '" class="reviveso-settings-block ' . ( $key != $tab ? 'd-none' : '' ) . '">';
			do_action( 'reviveso_before_' . $key . '_setting_block', $items );
			if ( ! empty( $items ) ) {
				settings_fields( 'reviveso_plugin_settings_fields' );
				$this->render_settings_subtabs( $items, $key );
				$this->render_settings_block( $items, $key );
			}
			do_action( 'reviveso_after_' . $key . '_setting_block', $items );
			echo '</div>';
		}
		do_action( 'reviveso_after_settings_block' );
	}

	public function render_settings_subtabs( $items, $key ) {
		if ( count( $items ) < 2 ) {
			return;
		}
		$tab          = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : false;
		$allowed_html = array( 'i' => array( 'class' => array() ) );
		$sub_items    = array();
		echo '<div class="postbox sub-links reviveso-' . esc_attr( $key ) . '">';
		foreach ( $items as $item => $subtab ) {
			$icon  = isset( $subtab['has_icon'] ) ? '<i class="dashicons ' . esc_attr( $subtab['has_icon'] ) . '"></i>' : '';
			$class = isset( $subtab['class'] ) ? esc_attr( $subtab['class'] ) : '';
			$badge = isset( $subtab['badge'] ) ? '<span class="reviveso-badge">' . esc_html( $subtab['badge'] ) . '</span>' : '';
			if ( ! $tab ) {
				$tab = $item;
			}
			$active = ( $item == $tab ? 'sub-active' : '' );
			$link   = add_query_arg(
				array(
					'page'    => 'reviveso',
					'tab'     => $key,
					'section' => $item,
				),
				admin_url( 'admin.php' )
			);
			echo wp_kses_post( sprintf( '<a href="%s" class="sub-link sub-link-%s %s %s" data-type="%s"> %s %s %s </a>', esc_attr( $link ), esc_attr( $item ), esc_attr( $active ), esc_attr( $class ), esc_attr( $item ), $icon, wp_kses( $subtab['name'], $allowed_html ), $badge ) );
		}
		echo '</div>';

		?>

		<?php
	}

	public function render_settings_block( $items, $key ) {
		$tab = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : false;

		$first = true;
		foreach ( $items as $item => $subtab ) {
			if ( ! $tab ) {
				$tab = $item;
			}

			?>
			<div id="reviveso-<?php
			echo esc_attr( $item ); ?>" class="reviveso_settings_pannel <?php
			echo 'reviveso-' . esc_attr( $item );
			echo( $item != $tab ? ' d-none' : '' ); ?>">
				<?php
				do_action( 'reviveso_settings_pannel_start', $subtab, $item ); ?>
				<?php
				if ( ! empty( $subtab['fields'] ) ): ?>
					<div class="inside">
						<table class="form-table" role="presentation" cellspacing="0">
							<tbody>
							<?php
							foreach ( $subtab['fields'] as $field ) {
								if ( isset( $field['type'] ) && 'section_header' == $field['type'] ): ?>
									<tr class="reviveso_section_head">
										<th scope="row" colspan="2">
											<h3><?php
												echo esc_html( $field['label'] ); ?></h3>
											<?php
											if ( 'tools' != $item && isset( $field['label_description'] ) ) : ?>
												<p class="description"> <?php
													echo wp_kses_post( $field['label_description'] ); ?> </p>
											<?php
											endif; ?>
										</th>
									</tr>
								<?php
								else: ?>
									<tr class="<?php
									echo esc_attr( $field['name'] ); ?>">
										<th scope="row">
											<label for="<?php
											echo esc_attr( $field['name'] ); ?>"><?php
												echo esc_html( $field['label'] ); ?></label>
											<?php
											if ( 'tools' == $item && isset( $field['description'] ) ) : ?>
												<p class="description"> <?php
													echo wp_kses_post( $field['description'] ); ?> </p>
											<?php
											endif; ?>
											<?php
											if ( 'tools' != $item && isset( $field['label_description'] ) ) : ?>
												<p class="description"> <?php
													echo wp_kses_post( $field['label_description'] ); ?> </p>
											<?php
											endif; ?>
										</th>
										<td>
											<?php
											$this->do_field( $field ); ?>
										</td>
									</tr>
								<?php
								endif;
							}
							?>
							</tbody>
						</table>
						<?php
						do_action( 'reviveso_settings_pannel_after_input', $subtab ); ?>
					</div>
				<?php
				endif; ?>
				<?php
				if ( ! isset( $subtab['save_button'] ) || ( isset( $subtab['save_button'] ) && $subtab['save_button'] ) ): ?>
					<p class="reviveso-control-area">
						<?php
						submit_button( __( 'Save Settings', 'revive-so' ), 'primary reviveso-save', '', false ); ?>
					</p>
				<?php
				endif; ?>
				<?php
				do_action( 'reviveso_settings_pannel_end', $subtab ); ?>
			</div>
			<?php
			$first = false;
		}
		if ( 'tools' == $item ) {
			$sys = array(
				'name'        => '',
				'type'        => 'tool-status',
				'label'       => __( 'System Status', 'revive-so' ),
				'description' => __( 'In order to use this plugin, please ensure your server meets the following PHP configurations. Your hosting provider will help you modify server configurations, if required.', 'revive-so' ),
			);
			echo '<div class="reviveso_settings_pannel reviveso_system_status postbox">';
			$this->do_field( $sys );
			echo '</div>';
		}
	}

	public function systemStatus() {
		$info = array();

		$info['memory_limit'] = array(
			'label'       => __( 'PHP memory limit', 'revive-so' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'value'       => ini_get( 'memory_limit' ),
			'minimum'     => '256M',
			'recommended' => '512M',
		);

		$info['max_execution_time'] = array(
			'label'       => __( 'PHP time limit', 'revive-so' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'value'       => ini_get( 'max_execution_time' ),
			'minimum'     => 300,
			'recommended' => 600,
		);

		$info['max_input_time'] = array(
			'label'       => __( 'Max input time', 'revive-so' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			'value'       => ini_get( 'max_input_time' ),
			'minimum'     => 120,
			'recommended' => 300,
		); ?>

		<div class="table-php-requirements-container">
			<table class="table-php-requirements" style="text-align: left;">
				<thead>
				<tr>
					<th><?php
						esc_html_e( 'Name', 'revive-so' ); ?></th>
					<th><?php
						esc_html_e( 'Directive', 'revive-so' ); ?></th>
					<th><?php
						esc_html_e( 'Least Suggested', 'revive-so' ); ?></th>
					<th><?php
						esc_html_e( 'Recommended', 'revive-so' ); ?></th>
					<th><?php
						esc_html_e( 'Current Value', 'revive-so' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ( $info as $key => $data ) { ?>
					<tr>
						<td><?php
							echo esc_html( $data['label'] ); ?></td>
						<td><?php
							echo esc_html( $key ); ?></td>
						<td class="bold"><?php
							echo esc_html( $data['minimum'] ); ?></td>
						<td class="bold"><?php
							echo esc_html( $data['recommended'] ); ?></td>
						<td class="bold"><?php
							echo esc_html( $data['value'] ); ?></td>
					</tr>
					<?php
				} ?>
				</tbody>
			</table>
			<p>
				<?php
				printf(
				/* translators: 1: <a> tag start, 2: </a> tag end. */
					esc_html__( 'To change PHP directives you need to modify php.ini file, more information about this you can %1$ssearch here%2$s or contact your hosting provider. See Site Health for more.', 'revive-so' ), '<a href="http://goo.gl/I9f74U" target="_blank" rel="noopener">', '</a>'
				); ?>
				<?php
				if ( defined( 'DISABLE_WP_CRON' ) && true === DISABLE_WP_CRON ) {
					esc_html_e( 'WordPress Cron is currently disabled. Please enable it if you are facing asny issue.', 'revive-so' );
				} ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Callback to sort tabs/fields on priority.
	 *
	 * @param  mixed  $a  Current element from array.
	 * @param  mixed  $b  Next element from array.
	 *
	 * @return array
	 */
	public static function sort_data_by_priority( $a, $b ) {
		if ( ! isset( $a['priority'], $b['priority'] ) ) {
			return - 1;
		}
		if ( $a['priority'] === $b['priority'] ) {
			return 0;
		}

		return $a['priority'] < $b['priority'] ? - 1 : 1;
	}
}
