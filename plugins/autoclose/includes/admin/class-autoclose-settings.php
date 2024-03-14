<?php
/**
 * Register AutoClose Settings.
 *
 * @link  https://webberzone.com
 * @since 2.2.0
 *
 * @package AutoClose
 * @subpackage Admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'AutoClose_Settings' ) ) :
	/**
	 * AutoClose Settings class to register the settings.
	 *
	 * @version 1.0
	 * @since   2.2.0
	 */
	class AutoClose_Settings {

		/**
		 * Class instance.
		 *
		 * @var class Class instance.
		 */
		public static $instance;

		/**
		 * Settings API.
		 *
		 * @since 2.2.0
		 *
		 * @var object Settings API.
		 */
		public $settings_api;

		/**
		 * Tools Page in Admin area.
		 *
		 * @since 2.2.0
		 *
		 * @var string Tools Page.
		 */
		public $tools_page;

		/**
		 * Prefix which is used for creating the unique filters and actions.
		 *
		 * @since 2.2.0
		 *
		 * @var string Prefix.
		 */
		public static $prefix;

		/**
		 * Settings Key.
		 *
		 * @since 2.2.0
		 *
		 * @var string Settings Key.
		 */
		public $settings_key;

		/**
		 * The slug name to refer to this menu by (should be unique for this menu).
		 *
		 * @since 2.2.0
		 *
		 * @var string Menu slug.
		 */
		public $menu_slug;

		/**
		 * Main constructor class.
		 *
		 * @since 2.2.0
		 */
		protected function __construct() {
			$this->settings_key = 'acc_settings';
			self::$prefix       = 'acc';
			$this->menu_slug    = 'acc_options_page';

			$args = array(
				'menu_slug'         => $this->menu_slug,
				'default_tab'       => 'general',
				'help_sidebar'      => $this->get_help_sidebar(),
				'help_tabs'         => $this->get_help_tabs(),
				'admin_footer_text' => sprintf(
					/* translators: 1: Opening achor tag with Plugin page link, 2: Closing anchor tag, 3: Opening anchor tag with review link. */
					__( 'Thank you for using %1$sAutoClose%2$s! Please %3$srate us%2$s on %3$sWordPress.org%2$s', 'autoclose' ),
					'<a href="https://webberzone.com/plugins/autoclose/" target="_blank">',
					'</a>',
					'<a href="https://wordpress.org/support/plugin/autoclose/reviews/#new-post" target="_blank">'
				),
			);

			$this->settings_api = new AutoClose_Admin\Settings_API( $this->settings_key, self::$prefix );
			$this->settings_api->set_translation_strings( $this->get_translation_strings() );
			$this->settings_api->set_props( $args );
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_registered_settings( $this->get_registered_settings() );
			$this->settings_api->set_upgraded_settings( $this->get_upgrade_settings() );

			add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
			add_action( 'admin_head', array( $this, 'admin_head' ), 11 );
			add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . plugin_basename( ACC_PLUGIN_FILE ), array( $this, 'plugin_actions_links' ) );
			add_action( 'acc_settings_page_header', array( $this, 'settings_page_header' ), 11 );
			add_action( 'acc_settings_sanitize', array( $this, 'change_settings_on_save' ), 99 );
		}

		/**
		 * Singleton instance
		 *
		 * @since 2.2.0
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Array containing the settings' sections.
		 *
		 * @since 2.2.0
		 *
		 * @return array Settings array
		 */
		public function get_translation_strings() {
			$strings = array(
				'page_title'           => esc_html__( 'AutoClose', 'autoclose' ),
				'menu_title'           => esc_html__( 'AutoClose', 'autoclose' ),
				'page_header'          => esc_html__( 'Automatically Close Comments, Pingbacks and Trackbacks Settings', 'autoclose' ),
				'reset_message'        => esc_html__( 'Settings have been reset to their default values. Reload this page to view the updated settings.', 'autoclose' ),
				'success_message'      => esc_html__( 'Settings updated.', 'autoclose' ),
				'save_changes'         => esc_html__( 'Save Changes', 'autoclose' ),
				'reset_settings'       => esc_html__( 'Reset all settings', 'autoclose' ),
				'reset_button_confirm' => esc_html__( 'Do you really want to reset all these settings to their default values?', 'autoclose' ),
				'checkbox_modified'    => esc_html__( 'Modified from default setting', 'autoclose' ),
			);

			/**
			 * Filter the array containing the settings' sections.
			 *
			 * @since 2.2.0
			 *
			 * @param array $strings Translation strings.
			 */
			return apply_filters( self::$prefix . '_translation_strings', $strings );

		}

		/**
		 * Array containing the settings' sections.
		 *
		 * @since 2.2.0
		 *
		 * @return array Settings array
		 */
		public function get_settings_sections() {
			$acc_settings_sections = array(
				'general'    => __( 'General', 'autoclose' ),
				'comments'   => __( 'Comments', 'autoclose' ),
				'pingtracks' => __( 'Pingbacks/Trackbacks', 'autoclose' ),
				'revisions'  => __( 'Revisions', 'autoclose' ),
			);

			/**
			 * Filter the array containing the settings' sections.
			 *
			 * @since 2.0.0
			 *
			 * @param array $acc_settings_sections Settings array
			 */
			return apply_filters( self::$prefix . '_settings_sections', $acc_settings_sections );

		}


		/**
		 * Retrieve the array of plugin settings
		 *
		 * @since 2.2.0
		 *
		 * @return array Settings array
		 */
		public static function get_registered_settings() {

			$acc_settings = array(
				'general'    => self::settings_general(),
				'comments'   => self::settings_comments(),
				'pingtracks' => self::settings_pingtracks(),
				'revisions'  => self::settings_revisions(),
			);

			/**
			 * Filters the settings array
			 *
			 * @since 2.0.0
			 *
			 * @param array $acc_setings Settings array
			 */
			return apply_filters( self::$prefix . '_registered_settings', $acc_settings );

		}

		/**
		 * Returns the Header settings.
		 *
		 * @since 2.2.0
		 *
		 * @return array Header settings.
		 */
		public static function settings_general() {

			$settings = array(
				'cron_on'         => array(
					'id'      => 'cron_on',
					'name'    => esc_html__( 'Activate scheduled closing', 'autoclose' ),
					'desc'    => esc_html__( 'This creates a WordPress cron job using the schedule settings below. This cron job will execute the tasks to close comments, pingbacks/trackbacks or delete post revisions based on the settings from the other tabs.', 'autoclose' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'cron_range_desc' => array(
					'id'   => 'cron_range_desc',
					'name' => '<strong>' . esc_html__( 'Time to run closing', 'autoclose' ) . '</strong>',
					'desc' => esc_html__( 'The next two options allow you to set the time to run the cron. The cron job will run now if the hour:min set below if before the current time. e.g. if the time now is 20:30 hours and you set the schedule to 9:00. Else it will run later today at the scheduled time.', 'autoclose' ),
					'type' => 'descriptive_text',
				),
				'cron_hour'       => array(
					'id'      => 'cron_hour',
					'name'    => esc_html__( 'Hour', 'autoclose' ),
					'desc'    => '',
					'type'    => 'number',
					'options' => '0',
					'min'     => '0',
					'max'     => '23',
					'size'    => 'small',
				),
				'cron_min'        => array(
					'id'      => 'cron_min',
					'name'    => esc_html__( 'Minute', 'autoclose' ),
					'desc'    => '',
					'type'    => 'number',
					'options' => '0',
					'min'     => '0',
					'max'     => '59',
					'size'    => 'small',
				),
				'cron_recurrence' => array(
					'id'      => 'cron_recurrence',
					'name'    => esc_html__( 'Run maintenance', 'autoclose' ),
					'desc'    => '',
					'type'    => 'radio',
					'default' => 'daily',
					'options' => array(
						'daily'       => esc_html__( 'Daily', 'autoclose' ),
						'weekly'      => esc_html__( 'Weekly', 'autoclose' ),
						'fortnightly' => esc_html__( 'Fortnightly', 'autoclose' ),
						'monthly'     => esc_html__( 'Monthly', 'autoclose' ),
					),
				),
			);

			/**
			 * Filters the Header settings array
			 *
			 * @since 2.0.0
			 *
			 * @param array $settings Header Settings array
			 */
			return apply_filters( self::$prefix . '_settings_general', $settings );
		}

		/**
		 * Returns the Comments settings.
		 *
		 * @since 2.2.0
		 *
		 * @return array Comments settings.
		 */
		public static function settings_comments() {

			$settings = array(
				'close_comment'      => array(
					'id'      => 'close_comment',
					'name'    => esc_html__( 'Close comments', 'autoclose' ),
					'desc'    => esc_html__( 'Enable to close comments - used for the automatic schedule as well as one time runs under the Tools tab.', 'autoclose' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'comment_post_types' => array(
					'id'      => 'comment_post_types',
					'name'    => esc_html__( 'Post types to include', 'autoclose' ),
					'desc'    => esc_html__( 'At least one option should be selected above. Select which post types on which you want comments closed.', 'autoclose' ),
					'type'    => 'posttypes',
					'options' => 'post',
				),
				'comment_age'        => array(
					'id'      => 'comment_age',
					'name'    => esc_html__( 'Close comments on posts/pages older than', 'autoclose' ),
					'desc'    => esc_html__( 'Comments that are older than the above number, in days, will be closed automatically if the schedule is enabled', 'autoclose' ),
					'type'    => 'number',
					'options' => '90',
				),
				'comment_pids'       => array(
					'id'      => 'comment_pids',
					'name'    => esc_html__( 'Keep comments on these posts/pages open', 'autoclose' ),
					'desc'    => esc_html__( 'Comma-separated list of post, page or custom post type IDs. e.g. 188,320,500', 'autoclose' ),
					'type'    => 'numbercsv',
					'options' => '',
					'size'    => 'large',
				),
			);

			/**
			 * Filters the Comments settings array
			 *
			 * @since 2.0.0
			 *
			 * @param array $settings Comments Settings array
			 */
			return apply_filters( self::$prefix . '_settings_comments', $settings );
		}

		/**
		 * Returns the Pingbacks/Trackbacks settings.
		 *
		 * @since 2.2.0
		 *
		 * @return array Pingbacks/Trackbacks settings.
		 */
		public static function settings_pingtracks() {

			$settings = array(
				'close_pbtb'      => array(
					'id'      => 'close_pbtb',
					'name'    => esc_html__( 'Close Pingbacks/Trackbacks', 'autoclose' ),
					'desc'    => esc_html__( 'Enable to close pingbacks and trackbacks - used for the automatic schedule as well as one time runs under the Tools tab.', 'autoclose' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'pbtb_post_types' => array(
					'id'      => 'pbtb_post_types',
					'name'    => esc_html__( 'Post types to include', 'autoclose' ),
					'desc'    => esc_html__( 'At least one option should be selected above. Select which post types on which you want pingbacks/trackbacks closed.', 'autoclose' ),
					'type'    => 'posttypes',
					'options' => 'post',
				),
				'pbtb_age'        => array(
					'id'      => 'pbtb_age',
					'name'    => esc_html__( 'Close pingbacks/trackbacks on posts/pages older than', 'autoclose' ),
					'desc'    => esc_html__( 'Pingbacks/Trackbacks that are older than the above number, in days, will be closed automatically if the schedule is enabled', 'autoclose' ),
					'type'    => 'number',
					'options' => '90',
				),
				'pbtb_pids'       => array(
					'id'      => 'pbtb_pids',
					'name'    => esc_html__( 'Keep pingbacks/trackbacks on these posts/pages open', 'autoclose' ),
					'desc'    => esc_html__( 'Comma-separated list of post, page or custom post type IDs. e.g. 188,320,500', 'autoclose' ),
					'type'    => 'numbercsv',
					'options' => '',
					'size'    => 'large',
				),
			);

			/**
			 * Filters the Pingbacks/Trackbacks settings array
			 *
			 * @since 2.0.0
			 *
			 * @param array $settings Pingbacks/Trackbacks Settings array
			 */
			return apply_filters( self::$prefix . '_settings_pingtracks', $settings );
		}

		/**
		 * Returns the Revisions settings.
		 *
		 * @since 2.2.0
		 *
		 * @return array Revisions settings.
		 */
		public static function settings_revisions() {

			$settings = array(
				'delete_revisions'    => array(
					'id'      => 'delete_revisions',
					'name'    => esc_html__( 'Delete post revisions', 'autoclose' ),
					'desc'    => esc_html__( 'The WordPress revisions system stores a record of each saved draft or published update. This can gather up a lot of overhead in the long run. Use this option to delete old post revisions.', 'autoclose' ),
					'type'    => 'checkbox',
					'options' => false,
				),
				'revision_post_types' => array(
					'id'   => 'revision_post_types',
					'name' => '<strong>' . esc_html__( 'Number of revisions', 'autoclose' ) . '</strong>',
					/* translators: 1: Code. */
					'desc' => sprintf( esc_html__( 'Limit the number of revisions that WordPress stores in the database for each of the post types below. %1$s -2: ignore setting from this plugin, %1$s -1: store every revision, %1$s 0: do not store any revisions, %1$s >0: store that many revisions per post. Old revisions are automatically deleted.', 'autoclose' ), '<br />' ),
					'type' => 'descriptive_text',
				),
			);

			// Create array of settings for post types that support revisions.
			$revision_post_types = acc_get_revision_post_types();

			foreach ( $revision_post_types as $post_type => $name ) {
				$settings[ 'revision_' . $post_type ] = array(
					'id'      => 'revision_' . $post_type,
					'name'    => $name,
					'desc'    => '',
					'type'    => 'number',
					'options' => -2,
					'min'     => -2,
					'size'    => 'small',
				);
			}

			/**
			 * Filters the Revisions settings array
			 *
			 * @since 2.0.0
			 *
			 * @param array $settings Revisions Settings array
			 */
			return apply_filters( self::$prefix . '_settings_revisions', $settings );
		}

		/**
		 * Upgrade v1.1.0 settings to v2.0.0.
		 *
		 * @since 2.2.0
		 * @return array Settings array
		 */
		public function get_upgrade_settings() {
			$old_settings = get_option( 'ald_acc_settings' );

			if ( empty( $old_settings ) ) {
				return false;
			} else {

				$settings = $old_settings;

				$settings['cron_on'] = $old_settings['daily_run'];

				// Rename the cron job.
				if ( wp_next_scheduled( 'ald_acc_hook' ) ) {
					$next_event = wp_get_scheduled_event( 'ald_acc_hook' );
					wp_schedule_event( $next_event->timestamp, $next_event->schedule, 'acc_cron_hook' );
					wp_clear_scheduled_hook( 'ald_acc_hook' );
				}

				delete_option( 'ald_acc_settings' );

				return $settings;
			}

		}

		/**
		 * Adding WordPress plugin action links.
		 *
		 * @since 2.2.0
		 *
		 * @param array $links Array of links.
		 * @return array
		 */
		public function plugin_actions_links( $links ) {

			return array_merge(
				array(
					'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->menu_slug ) . '">' . esc_html__( 'Settings', 'autoclose' ) . '</a>',
				),
				$links
			);
		}

		/**
		 * Add meta links on Plugins page.
		 *
		 * @since 2.2.0
		 *
		 * @param array  $links Array of Links.
		 * @param string $file Current file.
		 * @return array
		 */
		public function plugin_row_meta( $links, $file ) {

			if ( false !== strpos( $file, 'autoclose.php' ) ) {
				$new_links = array(
					'support'    => '<a href = "https://wordpress.org/support/plugin/autoclose">' . esc_html__( 'Support', 'autoclose' ) . '</a>',
					'donate'     => '<a href = "https://ajaydsouza.com/donate/">' . esc_html__( 'Donate', 'autoclose' ) . '</a>',
					'contribute' => '<a href = "https://github.com/WebberZone/autoclose">' . esc_html__( 'Contribute', 'autoclose' ) . '</a>',
				);

				$links = array_merge( $links, $new_links );
			}
			return $links;
		}

		/**
		 * Get the help sidebar content to display on the plugin settings page.
		 *
		 * @since 2.2.0
		 */
		public function get_help_sidebar() {

			$help_sidebar =
				/* translators: 1: Plugin support site link. */
				'<p>' . sprintf( __( 'For more information or how to get support visit the <a href="%s">support site</a>.', 'autoclose' ), esc_url( 'https://webberzone.com/support/' ) ) . '</p>' .
				/* translators: 1: WordPress.org support forums link. */
					'<p>' . sprintf( __( 'Support queries should be posted in the <a href="%s">WordPress.org support forums</a>.', 'autoclose' ), esc_url( 'https://wordpress.org/support/plugin/autoclose' ) ) . '</p>' .
				'<p>' . sprintf(
					/* translators: 1: Github issues link, 2: Github plugin page link. */
					__( '<a href="%1$s">Post an issue</a> on <a href="%2$s">GitHub</a> (bug reports only).', 'autoclose' ),
					esc_url( 'https://github.com/ajaydsouza/autoclose/issues' ),
					esc_url( 'https://github.com/ajaydsouza/autoclose' )
				) . '</p>';

			/**
			 * Filter to modify the help sidebar content.
			 *
			 * @since 2.2.0
			 *
			 * @param array $help_sidebar Help sidebar content.
			 */
			return apply_filters( self::$prefix . '_settings_help_sidebar', $help_sidebar );
		}

		/**
		 * Get the help tabs to display on the plugin settings page.
		 *
		 * @since 2.2.0
		 */
		public function get_help_tabs() {

			$help_tabs = array(
				array(
					'id'      => 'acc-settings-general',
					'title'   => __( 'General', 'autoclose' ),
					'content' =>
					'<p>' . __( 'This screen provides the basic settings for configuring AutoClose.', 'autoclose' ) . '</p>' .
						'<p>' . __( 'Set up the schedule at which this will take place automatically.', 'autoclose' ) . '</p>',
				),
				array(
					'id'      => 'acc-settings-comments',
					'title'   => __( 'Comments', 'autoclose' ),
					'content' =>
					'<p>' . __( 'This screen provides options to configure options for Comments.', 'autoclose' ) . '</p>' .
						'<p>' . __( 'Select the post types on which comments will be closed, period to close and exceptions.', 'autoclose' ) . '</p>',
				),
				array(
					'id'      => 'acc-settings-pingtracks',
					'title'   => __( 'Pingbacks / Trackbacks', 'autoclose' ),
					'content' =>
					'<p>' . __( 'This screen provides options to configure options for Pingbacks/Trackbacks.', 'autoclose' ) . '</p>' .
						'<p>' . __( 'Select the post types on which pingbacks/trackbacks will be closed, period to close and exceptions.', 'autoclose' ) . '</p>',
				),
				array(
					'id'      => 'acc-settings-revisions',
					'title'   => __( 'Revisions', 'autoclose' ),
					'content' =>
					'<p>' . __( 'This screen provides options to configure options for managing revisions.', 'autoclose' ) . '</p>' .
						'<p>' . __( 'Delete post revisions or limit the number of revisions for each post type.', 'autoclose' ) . '</p>',
				),
			);

			/**
			 * Filter to add more help tabs.
			 *
			 * @since 2.2.0
			 *
			 * @param array $help_tabs Associative array of help tabs.
			 */
			return apply_filters( self::$prefix . '_settings_help_tabs', $help_tabs );
		}

		/**
		 * Add admin menu.
		 *
		 * @since 2.2.0
		 */
		public function admin_menu() {
			$menu = array(
				'type'       => 'management',
				'page_title' => esc_html__( 'AutoClose Tools', 'autoclose' ),
				'menu_title' => esc_html__( 'AutoClose Tools', 'autoclose' ),
				'capability' => 'manage_options',
				'menu_slug'  => self::$prefix . '_tools_page',
				'function'   => 'acc_tools_page',
			);

			$this->tools_page = $this->settings_api->add_custom_menu_page( $menu );

			// Load the settings contextual help.
			add_action( 'load-' . $this->tools_page, array( $this, 'settings_help' ) );
		}

		/**
		 * Add admin head.
		 *
		 * @since 2.2.0
		 */
		public function admin_head() {
			if ( ! is_customize_preview() ) {
				$css = '
					<style type="text/css">
						#adminmenu a[href="options-general.php?page=acc_tools_page"]:before {
							content: "\21B3";
							margin-right: 0.5em;
							opacity: 0.5;
						}
						a.acc_button {
							background: green;
							padding: 10px;
							color: white;
							text-decoration: none;
							text-shadow: none;
							border-radius: 3px;
							transition: all 0.3s ease 0s;
							border: 1px solid green;
						}
						a.acc_button:hover {
							box-shadow: 3px 3px 10px #666;
						}
					</style>';

				echo $css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Function to add the contextual help in the settings page.
		 *
		 * @since 2.2.0
		 */
		public function settings_help() {
			$screen = get_current_screen();

			if ( $screen->id === $this->tools_page ) {

				$screen->set_help_sidebar( $this->get_help_sidebar() );

				$screen->add_help_tab(
					array(
						'id'      => 'acc-tools-general',
						'title'   => __( 'Tools', 'autoclose' ),
						'content' =>
						'<p>' . __( 'This screen gives you a few tools namely one click buttons to run the closing algorithm or open comments, pingbacks/trackbacks.', 'autoclose' ) . '</p>' .
							'<p>' . __( 'You can also delete the old settings from prior to v2.0.0', 'autoclose' ) . '</p>',
					)
				);
			}

		}

		/**
		 * Function to add a link below the page header of the settings page.
		 *
		 * @since 2.2.0
		 */
		public function settings_page_header() {
			?>
			<p>
				<a class="acc_button" href="<?php echo admin_url( 'tools.php?page=acc_tools_page' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
					<?php esc_html_e( 'Visit the Tools page', 'autoclose' ); ?>
				</a>
			<p>
			<?php

		}


		/**
		 * Modify settings when they are being saved.
		 *
		 * @since 2.2.0
		 *
		 * @param  array $settings Settings array.
		 * @return string  $settings  Sanitized settings array.
		 */
		public function change_settings_on_save( $settings ) {

			$settings['cron_hour'] = min( 23, absint( $settings['cron_hour'] ) );
			$settings['cron_min']  = min( 59, absint( $settings['cron_min'] ) );

			if ( ! empty( $settings['cron_on'] ) ) {
				acc_enable_run( $settings['cron_hour'], $settings['cron_min'], $settings['cron_recurrence'] );
			} else {
				acc_disable_run();
			}

			return $settings;
		}

	}

	/**
	 * Register settings function
	 *
	 * @since 2.0.0
	 */
	function acc_register_settings() {
		AutoClose_Settings::get_instance();
	}
	add_action( 'init', 'acc_register_settings', 999 );

endif;
