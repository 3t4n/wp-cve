<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}
/**
 * Main FooEvents Calendar class
 *
 * @since 1.0.0
 * @package fooevents-calendar
 */
class FooEvents_Calendar {

	/**
	 * The main FooEvents config.
	 *
	 * @since 1.0.0
	 * @var $config
	 */
	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$plugin = plugin_basename( __FILE__ );

		add_shortcode( 'fooevents_calendar', array( $this, 'display_calendar' ) );
		add_shortcode( 'fooevents_events_list', array( $this, 'events_list' ) );
		add_shortcode( 'fooevents_event', array( $this, 'event' ) );
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'include_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'include_styles' ) );
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		add_action( 'admin_init', array( $this, 'register_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_styles' ) );
		add_action( 'admin_init', array( $this, 'assign_admin_caps' ) );
		add_action( 'admin_init', array( $this, 'register_calendar_options' ) );
		add_action( 'init', array( $this, 'register_eventbrite_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_posts_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_posts_meta_box' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 12 );
		add_action( 'admin_notices', array( $this, 'display_meta_errors' ) );
		add_action( 'wp_ajax_fooevents-eventbrite-import', array( $this, 'import_events_from_eventbrite' ) );

		register_deactivation_hook( __FILE__, array( &$this, 'remove_event_user_caps' ) );

		add_filter( 'plugin_action_links_fooevents-calendar/fooevents-calendar.php', array( $this, 'plugins_settings_link' ) );

		$this->plugin_init();

	}

	public function plugins_settings_link( $links ) {

		$url           = 'admin.php?page=fooevents-settings&tab=calendar';
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

		array_push(
			$links,
			$settings_link
		);

		return $links;

	}

	/**
	 * Include front-end styles
	 */
	public function include_styles() {

		wp_enqueue_style( 'fooevents-calendar-full-callendar-style', $this->config->stylesPath . 'fullcalendar.css', array(), '1.0.0' );
		wp_enqueue_style( 'fooevents-calendar-full-callendar-print-style', $this->config->stylesPath . 'fullcalendar.print.css', array(), '1.0.0', 'print' );
		wp_enqueue_style( 'fooevents-calendar-full-callendar-styles', $this->config->stylesPath . 'style.css', array(), '1.0.1' );

		$calendar_theme = get_option( 'globalFooEventsCalendarTheme', true );

		if ( 'light' === $calendar_theme ) {

			wp_enqueue_style( 'fooevents-calendar-full-callendar-light', $this->config->stylesPath . 'fooevents-fullcalendar-light.css', array(), $this->config->plugin_data['Version'] );

		} elseif ( 'dark' === $calendar_theme ) {

			wp_enqueue_style( 'fooevents-calendar-full-callendar-dark', $this->config->stylesPath . 'fooevents-fullcalendar-dark.css', array(), $this->config->plugin_data['Version'] );

		} elseif ( 'flat' === $calendar_theme ) {

			wp_enqueue_style( 'fooevents-calendar-full-callendar-flat', $this->config->stylesPath . 'fooevents-fullcalendar-flat.css', array(), $this->config->plugin_data['Version'] );

		} elseif ( 'minimalist' === $calendar_theme ) {

			wp_enqueue_style( 'fooevents-calendar-full-callendar-minimalist', $this->config->stylesPath . 'fooevents-fullcalendar-minimalist.css', array(), $this->config->plugin_data['Version'] );

		}

		$list_theme = get_option( 'globalFooEventsCalendarListTheme', true );

		if ( 'light-card' === $list_theme ) {

			wp_enqueue_style( 'fooevents-calendar-list-light-card', $this->config->stylesPath . 'fooevents-list-light-card.css', array(), $this->config->plugin_data['Version'] );

		} elseif ( 'dark-card' === $list_theme ) {

			wp_enqueue_style( 'fooevents-calendar-list-dark-card', $this->config->stylesPath . 'fooevents-list-dark-card.css', array(), $this->config->plugin_data['Version'] );

		}

	}

	/**
	 * Include front-end scripts
	 */
	public function include_scripts() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'fooevents-calendar-moment', $this->config->scriptsPath . 'moment.js', array( 'jquery' ), '2.29.3', false );
		wp_enqueue_script( 'fooevents-calendar-full-callendar', $this->config->scriptsPath . 'fullcalendar.min.js', array( 'jquery' ), '1.0.0', false );
		wp_enqueue_script( 'fooevents-calendar-full-callendar-locale', $this->config->scriptsPath . 'locale-all.js', array( 'jquery' ), '1.0.0', false );

	}

	/**
	 * Register admin plugin scripts.
	 */
	public function register_scripts() {

		global $wp_locale;

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'fooevents-calendar-admin-script', $this->config->scriptsPath . 'calendar-admin.js', array( 'jquery', 'jquery-ui-datepicker', 'wp-color-picker' ), $this->config->plugin_data['Version'], true );

		$calendar_local_args = array(
			'closeText'       => __( 'Done', 'fooevents-calendar' ),
			'currentText'     => __( 'Today', 'fooevents-calendar' ),
			'monthNames'      => $this->strip_array_indices( $wp_locale->month ),
			'monthNamesShort' => $this->strip_array_indices( $wp_locale->month_abbrev ),
			'monthStatus'     => __( 'Show a different month', 'fooevents-calendar' ),
			'dayNames'        => $this->strip_array_indices( $wp_locale->weekday ),
			'dayNamesShort'   => $this->strip_array_indices( $wp_locale->weekday_abbrev ),
			'dayNamesMin'     => $this->strip_array_indices( $wp_locale->weekday_initial ),
			'dateFormat'      => $this->date_format_php_to_js( get_option( 'date_format' ) ),
			'firstDay'        => get_option( 'start_of_week' ),
			'isRTL'           => $wp_locale->is_rtl(),
		);

		wp_localize_script( 'fooevents-calendar-admin-script', 'localObj', $calendar_local_args );

	}

	/**
	 * Register admin plugin styles.
	 */
	public function register_styles() {

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'fooevents-calendar-admin-style', $this->config->stylesPath . 'calendar-admin.css', array(), $this->config->plugin_data['Version'] );

		if ( ( isset( $_GET['post'] ) && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) || ( isset( $_GET['page'] ) && 'fooevents-event-report' === $_GET['page'] ) || ( isset( $_GET['post_type'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification

			wp_enqueue_style( 'fooevents-calendar-jquery', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', array(), '1.0.0' );

		}

		if ( ! is_plugin_active( 'fooevents/fooevents.php' ) ) {

			wp_enqueue_style( 'fooevents-calendar-tooltip', $this->config->stylesPath . 'calendar-tooltip.css', array(), $this->config->plugin_data['Version'] );

		}

	}

	/**
	 * Initializes plugin
	 */
	public function plugin_init() {

		// Main config.
		$this->config = new FooEvents_Calendar_Config();

		if ( ! function_exists( 'get_plugin_data' ) ) {

			require_once ABSPATH . 'wp-admin/includes/plugin.php';

		}

				$this->config->plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/fooevents-calendar/fooevents-calendar.php' );

	}

	/**
	 * Adds the FooEvents menu item if not exists
	 */
	public function add_admin_menu() {

		global $menu;

		$menu_exist = false;
		foreach ( $menu as $item ) {

			if ( strtolower( $item[2] ) === strtolower( 'fooevents' ) ) {

				$menu_exist = true;

			}
		}

		if ( ! $menu_exist ) {

			add_menu_page(
				null,
				__( 'FooEvents', 'woocommerce-events' ),
				'edit_posts',
				'fooevents',
				array( $this, 'redirect_to_tickets' ),
				'dashicons-tickets-alt',
				'55.9'
			);

			add_submenu_page( 'fooevents', __( 'Calendar Settings', 'woocommerce-events' ), __( 'Calendar Settings', 'woocommerce-events' ), 'edit_posts', 'fooevents-settings', array( $this, 'display_settings_page' ) );

			remove_submenu_page( 'fooevents', 'fooevents' );

		}

	}

	/**
	 * Register calendar options
	 */
	public function register_calendar_options() {

		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsTwentyFourHour' );
		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsStartDay' );
		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsAllDayEvent' );
		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsCalendarTheme' );
		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsCalendarListTheme' );
		register_setting( 'fooevents-calendar-settings-calendar', 'globalFooEventsCalendarPostTypes' );
		register_setting( 'fooevents-settings-integration', 'globalFooEventsEventbriteToken' );

	}

	/**
	 * Display and processes the FooEvents Settings page
	 */
	public function display_settings_page() {

		if ( ! current_user_can( 'publish_fooevents_calendar' ) ) {

			wp_die( esc_attr( __( 'You do not have sufficient permissions to access this page.' ) ) );

		}

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		if ( ! is_plugin_active( 'fooevents/fooevents.php' ) || ! is_plugin_active_for_network( 'fooevents/fooevents.php' ) ) {

			$this->display_calendar_settings();

		}

	}

	/**
	 * Displays Calendar settings
	 */
	public function display_calendar_settings() {

		$calendar_options = $this->get_calendar_options();

		require $this->config->templatePath . 'calendar-options-layout.php';

	}

	/**
	 * Display PDF options
	 */
	public function get_calendar_options() {

		ob_start();

		$global_fooevents_twentyfour_hour         = get_option( 'globalFooEventsTwentyFourHour' );
		$global_fooevents_twentyfour_hour_checked = '';

		if ( 'yes' === $global_fooevents_twentyfour_hour ) {

			$global_fooevents_twentyfour_hour_checked = 'checked="checked"';

		}

		$global_fooevents_start_day             = get_option( 'globalFooEventsStartDay' );
		$global_fooevents_all_day_event         = get_option( 'globalFooEventsAllDayEvent' );
		$global_fooevents_all_day_event_checked = '';
		if ( 'yes' === $global_fooevents_all_day_event ) {

			$global_fooevents_all_day_event_checked = 'checked="checked"';

		}

		$global_fooevents_calendar_theme      = get_option( 'globalFooEventsCalendarTheme' );
		$global_fooevents_calendar_list_theme = get_option( 'globalFooEventsCalendarListTheme' );
		$global_fooevents_calendar_post_types = get_option( 'globalFooEventsCalendarPostTypes' );

		if ( empty( $global_fooevents_calendar_post_types ) ) {

			$global_fooevents_calendar_post_types = array();

		}

		$associated_post_types = $this->get_custom_post_types();

		require $this->config->templatePath . 'calendar-options.php';

		return ob_get_clean();

	}

	/**
	 * Display Eventbrite options
	 */
	public function get_eventbrite_options() {

		ob_start();

		$global_fooevents_eventbrite_token = get_option( 'globalFooEventsEventbriteToken' );

		require $this->config->templatePath . 'calendar-options-eventbrite.php';

		return ob_get_clean();

	}

	/**
	 * Register Eventbrite custom post type for imported events
	 */
	public function register_eventbrite_post_type() {

		$global_eventbrite_token = get_option( 'globalFooEventsEventbriteToken' );

		if ( ! empty( $global_eventbrite_token ) ) {

			register_post_type(
				'fe_eventbrite_event',
				array(
					'labels'      => array(
						'name'          => __( 'Imported Events', 'fooevents-calendar' ),
						'singular_name' => __( 'Imported Event', 'fooevents-calendar' ),
					),
					'public'      => true,
					'has_archive' => true,
				)
			);

		}

	}

	/**
	 * Include widget class
	 */
	public function include_widgets() {

		require 'classes/class-fooevents-calendar-widget.php';

	}

	/**
	 * Adds meta-box to non-product events
	 */
	public function add_posts_meta_box() {

		$global_fooevents_calendar_post_types = get_option( 'globalFooEventsCalendarPostTypes' );

		if ( empty( $global_fooevents_calendar_post_types ) ) {

			$global_fooevents_calendar_post_types = array( 'post', 'page' );

		}

		foreach ( $global_fooevents_calendar_post_types as $post_type ) {

			add_meta_box(
				'fooevents-event-meta-box123',
				__( 'Event Settings', 'fooevents-calendar' ),
				array( $this, 'display_metabox' ),
				$post_type,
				'normal',
				'high'
			);

		}

	}

	/**
	 * Displays calendar option metabox on post pages
	 *
	 * @global object $post
	 */
	public function display_metabox() {

		global $post;

		$event_date                  = get_post_meta( $post->ID, 'WooCommerceEventsDate', true );
		$event_event                 = get_post_meta( $post->ID, 'WooCommerceEventsEvent', true );
		$event_hour                  = get_post_meta( $post->ID, 'WooCommerceEventsHour', true );
		$event_period                = get_post_meta( $post->ID, 'WooCommerceEventsPeriod', true );
		$event_minutes               = get_post_meta( $post->ID, 'WooCommerceEventsMinutes', true );
		$event_hour_end              = get_post_meta( $post->ID, 'WooCommerceEventsHourEnd', true );
		$event_minutes_end           = get_post_meta( $post->ID, 'WooCommerceEventsMinutesEnd', true );
		$event_end_period            = get_post_meta( $post->ID, 'WooCommerceEventsEndPeriod', true );
		$event_timezone              = get_post_meta( $post->ID, 'WooCommerceEventsTimeZone', true );
		$event_date                  = get_post_meta( $post->ID, 'WooCommerceEventsDate', true );
		$event_end_date              = get_post_meta( $post->ID, 'WooCommerceEventsEndDate', true );
		$woocommerce_events_num_days = get_post_meta( $post->ID, 'WooCommerceEventsNumDays', true );
		$event_type                  = get_post_meta( $post->ID, 'WooCommerceEventsType', true );

		$woocommerce_events_background_color = get_post_meta( $post->ID, 'WooCommerceEventsBackgroundColor', true );
		$woocommerce_events_text_color       = get_post_meta( $post->ID, 'WooCommerceEventsTextColor', true );

		if ( empty( $event_type ) || 1 === (int) $event_type ) {

			$event_type = 'single';

		}

		$event_add_eventbrite         = get_post_meta( $post->ID, 'WooCommerceEventsAddEventbrite', true );
		$event_add_eventbrite_checked = '';

		if ( $event_add_eventbrite ) {

			$event_add_eventbrite_checked = 'checked="checked"';

		}

		$multi_day_active                           = false;
		$multi_day_type                             = '';
		$woocommerce_events_select_date             = '';
		$woocommerce_events_select_date_hour        = '';
		$woocommerce_events_select_date_minutes     = '';
		$woocommerce_events_select_date_period      = '';
		$woocommerce_events_select_date_hour_end    = '';
		$woocommerce_events_select_date_minutes_end = '';
		$woocommerce_events_select_date_period_end  = '';
		$woocommerce_events_select_global_time      = '';
		$woocommerce_events_hour                    = '';
		$woocommerce_events_minutes                 = '';
		$woocommerce_events_period                  = '';
		$woocommerce_events_hour_end                = '';
		$woocommerce_events_minutes_end             = '';
		$woocommerce_events_end_period              = '';

		$day_term = __( 'Day', 'fooevents-calendar' );

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		if ( is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network( 'fooevents_multi_day/fooevents-multi-day.php' ) ) {

			$fooevents_multiday_events      = new Fooevents_Multiday_Events();
			$multi_day_active               = true;
			$event_type                     = get_post_meta( $post->ID, 'WooCommerceEventsType', true );
			$woocommerce_events_select_date = get_post_meta( $post->ID, 'WooCommerceEventsSelectDate', true );

			$woocommerce_events_select_date_hour        = get_post_meta( $post->ID, 'WooCommerceEventsSelectDateHour', true );
			$woocommerce_events_select_date_minutes     = get_post_meta( $post->ID, 'WooCommerceEventsSelectDateMinutes', true );
			$woocommerce_events_select_date_period      = get_post_meta( $post->ID, 'WooCommerceEventsSelectDatePeriod', true );
			$woocommerce_events_select_date_hour_end    = get_post_meta( $post->ID, 'WooCommerceEventsSelectDateHourEnd', true );
			$woocommerce_events_select_date_minutes_end = get_post_meta( $post->ID, 'WooCommerceEventsSelectDateMinutesEnd', true );
			$woocommerce_events_select_date_period_end  = get_post_meta( $post->ID, 'WooCommerceEventsSelectDatePeriodEnd', true );
			$woocommerce_events_select_global_time      = get_post_meta( $post->ID, 'WooCommerceEventsSelectGlobalTime', true );

			$woocommerce_events_hour        = get_post_meta( $post->ID, 'WooCommerceEventsHour', true );
			$woocommerce_events_minutes     = get_post_meta( $post->ID, 'WooCommerceEventsMinutes', true );
			$woocommerce_events_period      = get_post_meta( $post->ID, 'WooCommerceEventsPeriod', true );
			$woocommerce_events_hour_end    = get_post_meta( $post->ID, 'WooCommerceEventsHourEnd', true );
			$woocommerce_events_minutes_end = get_post_meta( $post->ID, 'WooCommerceEventsMinutesEnd', true );
			$woocommerce_events_end_period  = get_post_meta( $post->ID, 'WooCommerceEventsEndPeriod', true );

		}

		$global_eventbrite_token = get_option( 'globalFooEventsEventbriteToken' );
		$eventbrite_option       = false;

		if ( ! empty( $global_eventbrite_token ) ) {

			$eventbrite_option = true;

		}

		require $this->config->templatePath . 'eventmetabox.php';

		wp_nonce_field( 'fooevents_metabox_nonce', 'fooevents_metabox_nonce' );

	}

	/**
	 * Generate eventbrite options to be displayed on FooEvents plugin
	 *
	 * @param object $post WordPress post object.
	 * @return string
	 */
	public function generate_eventbrite_option( $post ) {

		$event_add_eventbrite         = get_post_meta( $post->ID, 'WooCommerceEventsAddEventbrite', true );
		$event_add_eventbrite_checked = '';

		if ( $event_add_eventbrite ) {

			$event_add_eventbrite_checked = 'checked="checked"';

		}

		ob_start();

		require $this->config->templatePath . 'eventbrite-options.php';

		$eventbrite_option = ob_get_clean();

		return $eventbrite_option;

	}

	/**
	 * Processes and saves calendar options on pages
	 *
	 * @param int $post_id ID of post being saved.
	 * @return null
	 */
	public function save_posts_meta_box( $post_id ) {

		if ( ! isset( $_POST['fooevents_metabox_nonce'] ) ) {

			return;

		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fooevents_metabox_nonce'] ) ), 'fooevents_metabox_nonce' ) ) {

			return;

		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;

		}

		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {

				return;

			}
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {

				return;

			}
		}

		if ( isset( $_POST['WooCommerceEventsNonProductEvent'] ) ) {

			if ( isset( $_POST['WooCommerceEventsEvent'] ) ) {

					$events_event = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsEvent'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsEvent', $events_event );

			}

			$format = get_option( 'date_format' );

			$min    = 60 * get_option( 'gmt_offset' );
			$sign   = $min < 0 ? '-' : '+';
			$absmin = abs( $min );

			try {

					$tz = new DateTimeZone( sprintf( '%s%02d%02d', $sign, $absmin / 60, $absmin % 60 ) );

			} catch ( Exception $e ) {

					$server_timezone = date_default_timezone_get();
					$tz              = new DateTimeZone( $server_timezone );

			}

			$event_date_original = '';
			$event_date          = '';
			if ( isset( $_POST['WooCommerceEventsDate'] ) ) {

					$event_date_original = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsDate'] ) );
					$event_date          = $event_date_original;

			}

			if ( isset( $event_date ) ) {

				if ( isset( $_POST['WooCommerceEventsSelectDate'][0] ) && isset( $_POST['WooCommerceEventsMultiDayType'] ) && 'select' === $_POST['WooCommerceEventsMultiDayType'] ) {

							$event_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsSelectDate'][0] ) );

				}

					$event_date = str_replace( '/', '-', $event_date );
					$event_date = str_replace( ',', '', $event_date );

					update_post_meta( $post_id, 'WooCommerceEventsDate', $event_date_original );

					$dtime = DateTime::createFromFormat( $format, $event_date, $tz );

					$timestamp = '';
				if ( $dtime instanceof DateTime ) {

					if ( isset( $_POST['WooCommerceEventsHour'] ) && isset( $_POST['WooCommerceEventsMinutes'] ) ) {

							$event_hour    = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHour'] ) );
							$event_minutes = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutes'] ) );
							$dtime->setTime( (int) $event_hour, (int) $event_minutes );

					}

								$timestamp = $dtime->getTimestamp();

				} else {

					$timestamp = 0;

				}

								update_post_meta( $post_id, 'WooCommerceEventsDateTimestamp', $timestamp );

			}

			if ( isset( $_POST['WooCommerceEventsEndDate'] ) ) {

					$event_end_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsEndDate'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsEndDate', $event_end_date );

					$dtime = DateTime::createFromFormat( $format, $event_end_date, $tz );

					$timestamp = '';
				if ( $dtime instanceof DateTime ) {

					if ( isset( $_POST['WooCommerceEventsHourEnd'] ) && isset( $_POST['WooCommerceEventsMinutesEnd'] ) ) {

						$event_hour_end    = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHourEnd'] ) );
						$event_minutes_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutesEnd'] ) );
						$dtime->setTime( (int) $event_hour_end, (int) $event_minutes_end );

					}

					$timestamp = $dtime->getTimestamp();

				} else {

					$timestamp = 0;

				}

				update_post_meta( $post_id, 'WooCommerceEventsEndDateTimestamp', $timestamp );

			}

			if ( isset( $_POST['WooCommerceEventsHour'] ) ) {

					$event_hour = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHour'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsHour', $event_hour );

			}

			if ( isset( $_POST['WooCommerceEventsMinutes'] ) ) {

					$event_minutes = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutes'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsMinutes', $event_minutes );

			}

			if ( isset( $_POST['WooCommerceEventsPeriod'] ) ) {

					$event_period = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsPeriod'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsPeriod', $event_period );

			}

			if ( isset( $_POST['WooCommerceEventsHourEnd'] ) ) {

					$event_hour_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHourEnd'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsHourEnd', $event_hour_end );

			}

			if ( isset( $_POST['WooCommerceEventsMinutesEnd'] ) ) {

					$event_minutes_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutesEnd'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsMinutesEnd', $event_minutes_end );

			}

			if ( isset( $_POST['WooCommerceEventsEndPeriod'] ) ) {

					$event_end_period = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsEndPeriod'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsEndPeriod', $event_end_period );

			}

			if ( isset( $_POST['WooCommerceEventsTimeZone'] ) ) {

					$event_timezone = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsTimeZone'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsTimeZone', $event_timezone );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDate'] ) && isset( $_POST['WooCommerceEventsNonProductEvent'] ) ) {

					$event_select_date = $_POST['WooCommerceEventsSelectDate'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDate', $event_select_date );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDateHour'] ) ) {

					$woocommerce_events_select_date_hour = $_POST['WooCommerceEventsSelectDateHour'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDateHour', $woocommerce_events_select_date_hour );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDateMinutes'] ) ) {

					$woocommerce_events_select_date_minutes = $_POST['WooCommerceEventsSelectDateMinutes'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDateMinutes', $woocommerce_events_select_date_minutes );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDatePeriod'] ) ) {

					$woocommerce_events_select_date_period = $_POST['WooCommerceEventsSelectDatePeriod'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDatePeriod', $woocommerce_events_select_date_period );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDateHourEnd'] ) ) {

					$woocommerce_events_select_date_hour_end = $_POST['WooCommerceEventsSelectDateHourEnd'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDateHourEnd', $woocommerce_events_select_date_hour_end );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDateMinutesEnd'] ) ) {

					$woocommerce_events_select_date_minutes_end = $_POST['WooCommerceEventsSelectDateMinutesEnd'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDateMinutesEnd', $woocommerce_events_select_date_minutes_end );

			}

			if ( isset( $_POST['WooCommerceEventsSelectDatePeriodEnd'] ) ) {

					$woocommerce_events_select_date_period_end = $_POST['WooCommerceEventsSelectDatePeriodEnd'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectDatePeriodEnd', $woocommerce_events_select_date_period_end );

			}

			if ( isset( $_POST['WooCommerceEventsSelectGlobalTime'] ) ) {

					$woocommerce_events_select_global_time = $_POST['WooCommerceEventsSelectGlobalTime'];
					update_post_meta( $post_id, 'WooCommerceEventsSelectGlobalTime', $woocommerce_events_select_global_time );

			}

			if ( isset( $_POST['WooCommerceEventsType'] ) ) {

					$event_type = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsType'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsType', $event_type );

			}

			if ( isset( $_POST['WooCommerceEventsNumDays'] ) ) {

					$event_num_days = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsNumDays'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsNumDays', $event_num_days );

			}

			if ( isset( $_POST['WooCommerceEventsNumDays'] ) ) {

					$event_num_days = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsNumDays'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsNumDays', $event_num_days );

			}

			if ( isset( $_POST['WooCommerceEventsAddEventbrite'] ) ) {

					$events_add_eventbrite = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsAddEventbrite'] ) );
					update_post_meta( $post_id, 'WooCommerceEventsAddEventbrite', $events_add_eventbrite );

			} else {

					update_post_meta( $post_id, 'WooCommerceEventsAddEventbrite', '' );

			}

			if ( isset( $_POST['WooCommerceEventsBackgroundColor'] ) ) {

				$woocommerce_events_background_color = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsBackgroundColor'] ) );
				update_post_meta( $post_id, 'WooCommerceEventsBackgroundColor', $woocommerce_events_background_color );

			}

			if ( isset( $_POST['WooCommerceEventsTextColor'] ) ) {

				$woocommerce_events_text_color = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsTextColor'] ) );
				update_post_meta( $post_id, 'WooCommerceEventsTextColor', $woocommerce_events_text_color );

			}

			if ( isset( $_POST['WooCommerceEventsAddEventbrite'] ) ) {

					$this->process_eventbrite( $post_id );

			}
		}

	}

	/**
	 * Submit event to Eventbrite
	 *
	 * @param int $post_id ID of post.
	 */
	public function process_eventbrite( $post_id ) {

		if ( ! isset( $_POST['fooevents_metabox_nonce'] ) ) {

			return;

		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fooevents_metabox_nonce'] ) ), 'fooevents_metabox_nonce' ) ) {

			return;

		}

		$error = '';
		if ( ! session_id() ) {

			session_start();

		}

		$eventbrite_id = get_post_meta( $post_id, 'WooCommerceEventsEventbriteID', true );

		if ( empty( $_POST['WooCommerceEventsDate'] ) ) {

			$errors[] = __( 'Event start date required for Eventbrite.', 'fooevents-calendar' );

		}

		if ( isset( $_POST['WooCommerceEventsEndDate'] ) && empty( $_POST['WooCommerceEventsEndDate'] ) && $_POST['WooCommerceEventsType'] !== 'single' ) {

			$errors[] = __( 'Event end date required for Eventbrite.', 'fooevents-calendar' );

		}

		if ( empty( $_POST['post_title'] ) ) {

			$errors[] = __( 'Event title required for Eventbrite.', 'fooevents-calendar' );

		}

		if ( isset( $errors ) ) {

			$_SESSION['fooevents_calendar_errors'] = $errors;
			session_write_close();

			return;
		}

		$event_date = '';
		if ( isset( $_POST['WooCommerceEventsSelectDate'][0] ) && isset( $_POST['WooCommerceEventsMultiDayType'] ) && 'select' === $_POST['WooCommerceEventsMultiDayType'] ) {

			$event_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsSelectDate'][0] ) );

		} else {

			$event_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsDate'] ) );

		}

		$event_hour = '';
		if ( isset( $_POST['WooCommerceEventsHour'] ) ) {

			$event_hour = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHour'] ) );

		}

		$event_minutes = '';
		if ( isset( $_POST['WooCommerceEventsMinutes'] ) ) {

			$event_minutes = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutes'] ) );

		}

		$event_period = '';
		if ( isset( $_POST['WooCommerceEventsPeriod'] ) ) {

			$event_period = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsPeriod'] ) );

		}

		$event_date = $event_date . ' ' . $event_hour . ':' . $event_minutes . $event_period;

		$event_date = str_replace( '/', '-', $event_date );
		$event_date = str_replace( ',', '', $event_date );
		$event_date = date( 'Y-m-d H:i:s', strtotime( $event_date ) );
		$event_date = str_replace( ' ', 'T', $event_date );
		$event_date = $event_date . 'Z';

		$event_end_date = '';
		if ( ! empty( $_POST['WooCommerceEventsEndDate'] ) && $_POST['WooCommerceEventsType'] !== 'single' ) {

			$event_end_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsEndDate'] ) );

		} else {

			$event_end_date = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsDate'] ) );

		}

		$event_hour_end = '';
		if ( isset( $_POST['WooCommerceEventsHourEnd'] ) ) {

			$event_hour_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsHourEnd'] ) );

		}

		$event_minutes_end = '';
		if ( isset( $_POST['WooCommerceEventsMinutesEnd'] ) ) {

			$event_minutes_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsMinutesEnd'] ) );

		}

		$event_period_end = '';
		if ( isset( $_POST['WooCommerceEventsEndPeriod'] ) ) {

			$event_period_end = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsEndPeriod'] ) );

		}

		$event_end_date = $event_end_date . ' ' . $event_hour_end . ':' . $event_minutes_end . $event_period_end;

		$event_end_date = str_replace( '/', '-', $event_end_date );
		$event_end_date = str_replace( ',', '', $event_end_date );
		$event_end_date = date( 'Y-m-d H:i:s', strtotime( $event_end_date ) );
		$event_end_date = str_replace( ' ', 'T', $event_end_date );
		$event_end_date = $event_end_date . 'Z';

		$eventbrite_token = get_option( 'globalFooEventsEventbriteToken' );

		$client                = new HttpClient( $eventbrite_token );
		$user                  = $client->get( '/users/me/' );
				$organizations = $client->get_user_organizations( $user['id'] );

		$description = '';
		if ( isset( $_POST['excerpt'] ) ) {

			$description = sanitize_text_field( wp_unslash( $_POST['excerpt'] ) );

		} elseif ( isset( $_POST['post_content'] ) ) {

			$description = sanitize_text_field( wp_unslash( $_POST['post_content'] ) );

		}

		$timezone = '';
		if ( isset( $_POST['WooCommerceEventsTimeZone'] ) ) {

			$timezone = sanitize_text_field( wp_unslash( $_POST['WooCommerceEventsTimeZone'] ) );

		}

		$title = '';
		if ( isset( $_POST['post_title'] ) ) {

			$title = sanitize_text_field( wp_unslash( $_POST['post_title'] ) );

		}

		$event_params = array(
			'event.name.html'        => $title,
			'event.description.html' => $description,
			'event.start.utc'        => $event_date,
			'event.end.utc'          => $event_end_date,
			'event.start.timezone'   => 'UTC',
			'event.end.timezone'     => 'UTC',
			'event.currency'         => 'USD',

		);

		$resp = array();

		if ( empty( $eventbrite_id ) ) {

			// post_events has been modified to new endpoint.
			$resp = $client->post_events( $organizations['organizations'][0]['id'], $event_params );

		} else {

			// post_events has been modified to new endpoint.
			$resp = $client->post_event( $eventbrite_id, $organizations['organizations'][0]['id'], $event_params );

		}

		if ( isset( $resp['id'] ) ) {

			$id = sanitize_text_field( $resp['id'] );
			update_post_meta( $post_id, 'WooCommerceEventsEventbriteID', $id );

		}

		if ( isset( $resp['error'] ) ) {

			$errors[] = $resp['error'] . ': ' . $resp['error_description'];

		}

		if ( isset( $errors ) ) {

			$_SESSION['fooevents_calendar_errors'] = $errors;
			session_write_close();

			return;
		}

		session_write_close();

	}

	/**
	 * Connect to Eventbrite and import events
	 */
	public function import_events_from_eventbrite() {

		$eventbrite_token = get_option( 'globalFooEventsEventbriteToken' );

		$client = new HttpClient( $eventbrite_token );

		$user = $client->get( '/users/me/' );

		if ( ! empty( $user['error'] ) ) {

			echo esc_attr( $user['error_description'] );
			exit();

		}

		$event_params = array(
			'user.id' => $user['id'],
		);

		$organizations = $client->get_user_organizations( $user['id'], $event_params );
		$events        = $client->get_user_owned_events( $organizations['organizations'][0]['id'], $event_params );

		$local_eventbrite_events = $this->get_local_eventbrite_events();

		$added_events   = 0;
		$updated_events = 0;

		if ( ! empty( $events['events'] ) ) {

			foreach ( $events['events'] as $event ) {

				if ( ! in_array( $event['id'], $local_eventbrite_events ) ) {

					$origin_start_date = $event['start']['local'];
					$origin_end_date   = $event['end']['local'];

					$post_id = '';

					$event_date     = date( 'Y-m-d', strtotime( $origin_start_date ) );
					$events_hour    = date( 'H', strtotime( $origin_start_date ) );
					$events_minutes = date( 'i', strtotime( $origin_start_date ) );

					$event_end_date     = date( 'Y-m-d', strtotime( $origin_end_date ) );
					$event_hour_end     = date( 'H', strtotime( $origin_end_date ) );
					$events_minutes_end = date( 'i', strtotime( $origin_end_date ) );

					$post         = array();
					$origin_query = new WP_Query(
						array(
							'post_type'      => 'fe_eventbrite_event',
							'posts_per_page' => -1,
							'meta_query'     => array(
								array(
									'key'   => 'WooCommerceEventsEventbriteID',
									'value' => $event['id'],
								),
							),
						)
					);
					$origin       = $origin_query->get_posts();

					$content = '';

					if ( ! empty( $event['description']['text'] ) ) {

						$content = $event['description']['text'];

					} else {

						$content = $event['name']['text'];

					}

					if ( empty( $origin ) ) {

						$post = array(
							'post_content' => $content,
							'post_status'  => 'publish',
							'post_title'   => $event['name']['text'],
							'post_type'    => 'fe_eventbrite_event',
						);

						$post_id = wp_insert_post( $post );
						update_post_meta( $post_id, 'WooCommerceEventsEventbriteID', $event['id'] );

						$added_events++;

					} else {

						$origin = $origin[0];

						$post = array(
							'ID'           => $origin->ID,
							'post_content' => $content,
							'post_status'  => 'publish',
							'post_title'   => $event['name']['text'],
							'post_type'    => 'fe_eventbrite_event',
						);

						$post_id = wp_update_post( $post );

						$updated_events++;

					}

					update_post_meta( $post_id, 'WooCommerceEventsDate', $event_date );
					update_post_meta( $post_id, 'WooCommerceEventsHour', $events_hour );
					update_post_meta( $post_id, 'WooCommerceEventsMinutes', $events_minutes );

					update_post_meta( $post_id, 'WooCommerceEventsEndDate', $event_end_date );
					update_post_meta( $post_id, 'WooCommerceEventsHourEnd', $event_hour_end );
					update_post_meta( $post_id, 'WooCommerceEventsMinutesEnd', $events_minutes_end );

					update_post_meta( $post_id, 'WooCommerceEventsEvent', 'Event' );

				}
			}
		}

		/* translators: %1$d is replaced with the number of events added. %2$d is replaced with the number of events updated */
		printf( esc_attr( __( '%1$d events added. %2$d events updated.', 'fooevents-calendar' ) ), esc_attr( $added_events ), esc_attr( $updated_events ) );
		exit();

	}

	/**
	 * Get products that are Eventbrite events
	 */
	public function get_local_eventbrite_events() {

		$calendar_post_types = get_option( 'globalFooEventsCalendarPostTypes' );

		$events_query = new WP_Query(
			array(
				'post_type'      => $calendar_post_types,
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'WooCommerceEventsEventbriteID',
						'compare' => 'EXISTS',
					),
				),
			)
		);
		$events       = $events_query->get_posts();

		$return_ids = array();

		if ( ! empty( $events ) ) {

			foreach ( $events as $event ) {

				$eventbrite_id = get_post_meta( $event->ID, 'WooCommerceEventsEventbriteID', true );
				$return_ids[]  = $eventbrite_ids;

			}
		}

		return $return_ids;

	}

	/**
	 * Displays a shortcode event
	 *
	 * @param array $attributes shortcode attributes.
	 */
	public function event( $attributes ) {

		$product_id = '';

		if ( ! empty( $attributes['product'] ) ) {

			$product_id = $attributes['product'];

		}

		ob_start();
		if ( ! empty( $product_id ) ) {

			$event = get_post( $product_id );

			$ticket_term = get_post_meta( $product_id, 'WooCommerceEventsTicketOverride', true );

			if ( empty( $ticket_term ) ) {

				$ticket_term = get_option( 'globalWooCommerceEventsTicketOverride', true );

			}

			if ( empty( $ticket_term ) || 1 === (int) $ticket_term ) {

				$ticket_term = __( 'Book ticket', 'woocommerce-events' );

			}

			if ( ! empty( $event ) ) {

				$thumbnail = get_the_post_thumbnail_url( $event->ID );

				// Check theme directory for template first.
				if ( file_exists( $this->config->templatePathTheme . 'event.php' ) ) {

					include $this->config->templatePathTheme . 'event.php';

				} else {

					require $this->config->templatePath . 'event.php';

				}
			}
		}

		$event_output = ob_get_clean();

		return $event_output;

	}

	/**
	 * Displays a shortcode list of events
	 *
	 * @param array $attributes shortcode attributes.
	 */
	public function events_list( $attributes ) {

		$num_events   = '';
		$sort         = '';
		$cat          = '';
		$include_cats = array();

		if ( ! empty( $attributes['num'] ) ) {

			$num_events = $attributes['num'];

		} else {

			$num_events = 10;

		}

		if ( ! empty( $attributes['sort'] ) ) {

			$sort = strtolower( $attributes['sort'] );

		} else {

			$sort = 'asc';

		}

		$product_ids = '';

		if ( ! empty( $attributes['post'] ) ) {

			$product_ids = array_map( 'trim', explode( ',', $attributes['post'] ) );

		}

		if ( ! empty( $attributes['include_cat'] ) ) {

			$include_cats = array_map( 'trim', explode( ',', $attributes['include_cat'] ) );

		}

		if ( ! empty( $attributes['cat'] ) ) {

			$cat = $attributes['cat'];

		} else {

			$cat = '';

		}

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		$events             = array();
		$non_product_events = array();

		if ( is_plugin_active( 'fooevents/fooevents.php' ) || is_plugin_active_for_network( 'fooevents/fooevents.php' ) ) {

			$events = $this->get_events( $include_cats, $product_ids );
			$events = $this->fetch_events( $events, 'events_list', true );

		}

		if ( is_plugin_active( 'fooevents_bookings/fooevents-bookings.php' ) || is_plugin_active_for_network( 'fooevents_bookings/fooevents-bookings.php' ) ) {

			$fooevents_bookings = new FooEvents_Bookings();
			$booking_events     = $fooevents_bookings->get_bookings_for_calendar( $include_cats );

			$events = array_merge_recursive( $events, $booking_events );

		}

		$non_product_events = $this->get_non_product_events( $include_cats );
		$non_product_events = $this->fetch_events( $non_product_events, 'events_list', true );

		$events = array_merge_recursive( $events, $non_product_events );

		$events = $this->sort_events_by_date( $events, $sort );

		if ( 'asc' === $sort && ! empty( $num_events ) && is_numeric( $num_events ) ) {

			$events = array_slice( $events, 0, $num_events, true );

		} elseif ( ! empty( $num_events ) && is_numeric( $num_events ) ) {

			$events = array_slice( $events, -$num_events, $num_events, true );

		}

		if ( empty( $attributes['type'] ) ) {

			ob_start();

		}

		foreach ( $events as $key => $event ) {

			if ( empty( $event ) ) {

				unset( $events[ $key ] );

			}

			$ticket_term = get_post_meta( $event['post_id'], 'WooCommerceEventsTicketOverride', true );

			if ( empty( $ticket_term ) ) {

				$ticket_term = get_option( 'globalWooCommerceEventsTicketOverride', true );

			}

			if ( empty( $ticket_term ) || 1 === (int) $ticket_term ) {

				$ticket_term = __( 'Book ticket', 'woocommerce-events' );

			}

			$events[ $key ]['ticketTerm'] = $ticket_term;

		}

		// Check theme directory for template first.
		if ( file_exists( $this->config->templatePathTheme . 'list-of-events.php' ) ) {

			include $this->config->templatePathTheme . 'list-of-events.php';

		} else {

			require $this->config->templatePath . 'list-of-events.php';

		}

		if ( empty( $attributes['type'] ) ) {

			$event_list = ob_get_clean();

			return $event_list;

		}

	}

	/**
	 * Outputs calendar to screen
	 *
	 * @param array $attributes shortcode attributes.
	 */
	public function display_calendar( $attributes ) {

		$include_cats = array();

		if ( empty( $attributes ) ) {

			$attributes = array();

		}

		$calendar_id = 'fooevents_calendar';
		$product_ids = '';

		if ( ! empty( $attributes['post'] ) ) {

			$product_ids = array_map( 'trim', explode( ',', $attributes['post'] ) );

		}

		if ( ! empty( $attributes['id'] ) ) {

			$calendar_id      = $attributes['id'] . '_fooevents_calendar';
			$attributes['id'] = $attributes['id'] . '_fooevents_calendar';

		} else {

			$attributes['id'] = $calendar_id;

		}

		if ( ! empty( $attributes['include_cat'] ) ) {

			$include_cats = array_map( 'trim', explode( ',', $attributes['include_cat'] ) );

		}

		if ( ! empty( $attributes['cat'] ) ) {

			$cat = $attributes['cat'];

		} else {

			$cat = '';

		}

		$attributes = $this->process_shortcodes( $attributes );

		$events_twenty_four_hour = get_option( 'globalFooEventsTwentyFourHour' );

		if ( 'yes' === $events_twenty_four_hour ) {

			$attributes['timeFormat'] = 'H:mm';

		}

		$attributes['buttonText'] = array( 'today' => __( 'Today', 'fooevents-calendar' ) );

		if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		}

		$display_type = '';
		if ( ! empty( $attributes['defaultView'] ) ) {

			$display_type = $attributes['defaultView'];

		} else {

			$display_type = 'calendar';

		}

		if ( 'listWeek' === $display_type || 'listMonth' === $display_type ) {

			$attributes['displayEventEnd'] = false;

		}

		if ( ! empty( $attributes['weekends'] ) && 'false' === $attributes['weekends'] ) {

			$attributes['weekends'] = false;

		}

		$events             = array();
		$non_product_events = array();

		if ( is_plugin_active( 'fooevents/fooevents.php' ) || is_plugin_active_for_network( 'fooevents/fooevents.php' ) ) {

			$events = $this->get_events( $include_cats, $product_ids );
			$events = $this->fetch_events( $events, $display_type, false );

		}

		$non_product_events = $this->get_non_product_events( $include_cats, $product_ids );
		$non_product_events = $this->fetch_events( $non_product_events, $display_type, false );

		$events = array_merge_recursive( $events, $non_product_events );

		if ( is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network( 'fooevents_multi_day/fooevents-multi-day.php' ) ) {

			$fooevents_multiday_events = new Fooevents_Multiday_Events();
			$events                    = $fooevents_multiday_events->process_events_calendar( $events, $attributes );

		}

		if ( is_plugin_active( 'fooevents_bookings/fooevents-bookings.php' ) || is_plugin_active_for_network( 'fooevents_bookings/fooevents-bookings.php' ) ) {

			$fooevents_bookings = new FooEvents_Bookings( true );
			$booking_events     = $fooevents_bookings->get_bookings_for_calendar( $include_cats, $product_ids );

			$events = array_merge_recursive( $events, $booking_events );

		}

		$json_events = array_merge( $attributes, $events );
		$json_events = addslashes( json_encode( $json_events, JSON_HEX_QUOT | JSON_HEX_APOS ) );

		$local_args = array( 'json_events' => $json_events );

		if ( empty( $attributes['type'] ) ) {

			ob_start();

		}

		// Check theme directory for template first.
		if ( file_exists( $this->config->templatePathTheme . 'calendar.php' ) ) {

			include $this->config->templatePathTheme . 'calendar.php';

		} else {

			include $this->config->templatePath . 'calendar.php';

		}

		if ( empty( $attributes['type'] ) ) {

			$calendar = ob_get_clean();

			return $calendar;

		}

	}

	/**
	 * Sorts events either ascending or descending
	 *
	 * @param array  $events events.
	 * @param string $sort asc/desc.
	 * @return array
	 */
	public function sort_events_by_date( $events, $sort ) {

		if ( ! empty( $events ) ) {

			$events = $events['events'];

			if ( 'asc' === strtolower( $sort ) ) {

				usort( $events, array( $this, 'event_date_compare_asc' ) );

			} else {

				usort( $events, array( $this, 'event_date_compare_desc' ) );

			}

			foreach ( $events as $key => $event ) {

				if ( empty( $event['title'] ) ) {

					unset( $events[ $key ] );

				}
			}
		}
		return $events;

	}

	/**
	 * Compares two dates in ascending order
	 *
	 * @param array $a first date.
	 * @param array $b second date.
	 * @return array
	 */
	public function event_date_compare_asc( $a, $b ) {

		if ( empty( $a ) ) {

			$a = array( 'start' => '' );

		}

		if ( empty( $a['start'] ) ) {

			$a = array( 'start' => '' );

		}

		if ( empty( $b ) ) {

			$b = array( 'start' => '' );

		}

		if ( empty( $b['start'] ) ) {

			$b = array( 'start' => '' );

		}

		$t1 = strtotime( $a['start'] );
		$t2 = strtotime( $b['start'] );

		return $t1 - $t2;

	}

	/**
	 * Compares two dates in descending order
	 *
	 * @param array $a first date.
	 * @param array $b second date.
	 * @return array
	 */
	public function event_date_compare_desc( $a, $b ) {

		if ( empty( $a ) ) {

			$a = array( 'start' => '' );

		}

		if ( empty( $a['start'] ) ) {

			$a = array( 'start' => '' );

		}

		if ( empty( $b ) ) {

			$b = array( 'start' => '' );

		}

		if ( empty( $b['start'] ) ) {

			$b = array( 'start' => '' );

		}

		$t2 = strtotime( $a['start'] );
		$t1 = strtotime( $b['start'] );

		return $t1 - $t2;

	}

	/**
	 * Get all events
	 *
	 * @param array $include_cats category slugs.
	 * @return array
	 */
	public function get_events( $include_cats = array(), $product_ids = array() ) {

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'WooCommerceEventsEvent',
					'value'   => 'Event',
					'compare' => '=',
				),
			),
		);

		if ( ! empty( $include_cats ) ) {

			$args['tax_query'] = array( 'relation' => 'OR' );

			foreach ( $include_cats as $include_cat ) {

				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $include_cat,
				);

			}
		}

		if ( ! empty( $product_ids ) ) {

			$args['post__in'] = $product_ids;

		}

		$events = new WP_Query( $args );

		return $events->get_posts();

	}

	/**
	 * Get custom post type events that are not WooCommerce products
	 *
	 * @param array $include_cats category slugs.
	 * @return array
	 */
	public function get_non_product_events( $include_cats = array(), $product_ids = array() ) {

		$calendar_post_types = get_option( 'globalFooEventsCalendarPostTypes' );

		if ( empty( $calendar_post_types ) ) {

			$calendar_post_types = array( 'post' );

		}

		array_push( $calendar_post_types, 'fe_eventbrite_event' );

		$args = array(
			'post_type'      => $calendar_post_types,
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'WooCommerceEventsEvent',
					'value'   => 'Event',
					'compare' => '=',
				),
			),
		);

		if ( ! empty( $include_cats ) ) {

			$args['tax_query'] = array( 'relation' => 'OR' );

			foreach ( $include_cats as $include_cat ) {

				$args['tax_query'][] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $include_cat,
				);

			}
		}

		if ( ! empty( $product_ids ) ) {

			$args['post__in'] = $product_ids;

		}

		$events = new WP_Query( $args );

		return $events->get_posts();

	}

	/**
	 * Process fetched events
	 *
	 * @param array  $events events.
	 * @param string $display_type output display type.
	 * @param bool   $include_desc include description.
	 * @return array
	 */
	public function fetch_events( $events, $display_type, $include_desc = true ) {

		$json_events    = array();
		$wp_date_format = get_option( 'date_format' );

		$x = 0;
		foreach ( $events as $event ) {

			$fooevents_multiday_events = '';
			$multi_day_type            = '';

			if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

				require_once ABSPATH . '/wp-admin/includes/plugin.php';

			}

			if ( is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network( 'fooevents_multi_day/fooevents-multi-day.php' ) ) {

				$fooevents_multiday_events = new Fooevents_Multiday_Events();
				$multi_day_type            = $fooevents_multiday_events->get_multi_day_type( $event->ID );

			}

			$event_date_unformated  = get_post_meta( $event->ID, 'WooCommerceEventsDate', true );
			$event_type             = get_post_meta( $event->ID, 'WooCommerceEventsType', true );
			$event_hour             = get_post_meta( $event->ID, 'WooCommerceEventsHour', true );
			$event_minutes          = get_post_meta( $event->ID, 'WooCommerceEventsMinutes', true );
			$event_period           = get_post_meta( $event->ID, 'WooCommerceEventsPeriod', true );
			$event_background_color = get_post_meta( $event->ID, 'WooCommerceEventsBackgroundColor', true );
			$event_text_color       = get_post_meta( $event->ID, 'WooCommerceEventsTextColor', true );
			$stock                  = get_post_meta( $event->ID, '_stock', true );
			$event_expire           = get_post_meta( $event->ID, 'WooCommerceEventsExpireTimestamp', true );
			$events_expire_option   = get_option( 'globalWooCommerceEventsExpireOption' );
			$today                  = current_time( 'timestamp' );

			// Check if event has expired.
			if ( 'hide' === $events_expire_option && ! empty( $event_expire ) && $today >= $event_expire ) {

				continue;

			}

			if ( empty( $event_date_unformated ) ) {

				if ( 'select' !== $multi_day_type ) {

					continue;

				}
			}

			$event_date = $event_date_unformated . ' ' . $event_hour . ':' . $event_minutes . $event_period;
			$event_date = $this->convert_month_to_english( $event_date );
			$format     = get_option( 'date_format' );
			$event_date = str_replace( ',', '', $event_date );

			if ( 'd/m/Y' === $format ) {

				$event_date = str_replace( '/', '-', $event_date );

			}

			$event_date = date_i18n( 'Y-m-d H:i:s', strtotime( $event_date ) );
			$event_date = str_replace( ' ', 'T', $event_date );

			$all_day_event        = false;
			$global_all_day_event = get_option( 'globalFooEventsAllDayEvent' );

			if ( 'yes' === $global_all_day_event ) {

				$all_day_event = true;

			}

			if ( 'bookings' !== $event_type ) {

				$json_events['events'][ $x ] = array(
					'title'           => $event->post_title,
					'allDay'          => $all_day_event,
					'start'           => $event_date,
					'unformated_date' => $event_date_unformated,
					'url'             => get_permalink( $event->ID ),
					'post_id'         => $event->ID,
				);

			}

			if ( ! empty( $event_background_color ) ) {

				$json_events['events'][ $x ]['color'] = $event_background_color;

			}

			if ( ! empty( $event_text_color ) ) {

				$json_events['events'][ $x ]['textColor'] = $event_text_color;

			}

			if ( $include_desc ) {

				$json_events['events'][ $x ]['desc'] = $event->post_excerpt;

			}

			if ( 'select' === $multi_day_type ) {

				unset( $json_events['events'][ $x ] );
				$x--;

			}

			if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

				require_once ABSPATH . '/wp-admin/includes/plugin.php';

			}

			if ( is_plugin_active( 'fooevents_multi_day/fooevents-multi-day.php' ) || is_plugin_active_for_network( 'fooevents_multi_day/fooevents-multi-day.php' ) ) {

				$event_end_date  = $fooevents_multiday_events->get_end_date( $event->ID );
				$event_start_day = get_option( 'globalFooEventsStartDay' );

				$multi_day_dates = array();

				if ( 'select' === $multi_day_type ) {

					$multi_day_dates   = get_post_meta( $event->ID, 'WooCommerceEventsSelectDate', true );
					$multi_day_hours   = get_post_meta( $event->ID, 'WooCommerceEventsSelectDateHour', true );
					$multi_day_minutes = get_post_meta( $event->ID, 'WooCommerceEventsSelectDateMinutes', true );
					$multi_day_period  = get_post_meta( $event->ID, 'WooCommerceEventsSelectDatePeriod', true );

					if ( 'events_list' === $display_type ) {

						// $multi_day_dates = array($multi_day_dates[0]);

					}

					if ( ! empty( $multi_day_dates ) ) {

						$y = 0;
						$z = 1;
						foreach ( $multi_day_dates as $date ) {

							if ( ( 'eventlist' === $event_start_day || 'both' === $event_start_day ) && 'events_list' === $display_type && $y > 0 ) {

									continue;

							}

							if ( ( 'calendar' === $event_start_day || 'both' === $event_start_day ) && 'events_list' !== $display_type && $y > 0 ) {

									continue;

							}

							$x++;

							$event_date = '';
							if ( isset( $multi_day_hours[ $y ] ) && isset( $multi_day_minutes[ $y ] ) ) {

								$event_date = $date . ' ' . $multi_day_hours[ $y ] . ':' . $multi_day_minutes[ $y ] . $multi_day_period[ $y ];

							} else {

								$event_date = $date . ' ' . $event_hour . ':' . $event_minutes . $event_period;

							}

							$event_date = $this->convert_month_to_english( $event_date );
							$event_date = str_replace( ',', '', $event_date );

							if ( 'd/m/Y' === $format ) {

								$event_date = str_replace( '/', '-', $event_date );

							}

							$event_date = date( 'Y-m-d H:i:s', strtotime( $event_date ) );
							$event_date = str_replace( ' ', 'T', $event_date );

							$json_events['events'][ $x ] = array(
								'title'           => $event->post_title,
								'allDay'          => $all_day_event,
								'start'           => $event_date,
								'unformated_date' => $date,
								'url'             => get_permalink( $event->ID ),
								'post_id'         => $event->ID,
								'multi_day'       => 'selected',
							);

							if ( $include_desc ) {

								$json_events['events'][ $x ]['desc'] = $event->post_excerpt;

							}

							if ( ! empty( $event_background_color ) ) {

								$json_events['events'][ $x ]['color'] = $event_background_color;

							}

							if ( ! empty( $event_text_color ) ) {

								$json_events['events'][ $x ]['textColor'] = $event_text_color;

							}

							$product = '';

							if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {

								$product = wc_get_product( $event->ID );

							}

							if ( ! empty( $product ) ) {

								if ( $product->is_in_stock() ) {

									$json_events['events'][ $x ]['in_stock'] = 'yes';

								} else {

									$json_events['events'][ $x ]['in_stock'] = 'no';

								}
							} else {

								// Not a product so make in stock.
								$json_events['events'][ $x ]['in_stock'] = 'yes';

							}

							$y++;
							$z++;

						}
					} else {

						if ( ! empty( $event_end_date ) ) {

							$event_end_date_formatted = $fooevents_multiday_events->format_end_date( $event->ID, '', $display_type );

							if ( 'yes' !== $event_start_day ) {

								$json_events['events'][ $x ]['end']                 = $event_end_date_formatted;
								$json_events['events'][ $x ]['unformated_end_date'] = $event_end_date;

							}
						}
					}
				} else {

					if ( ! empty( $event_end_date ) ) {

						$event_end_date_formatted = $fooevents_multiday_events->format_end_date( $event->ID, true, $display_type );

						if ( ( 'calendar' !== $event_start_day && 'both' !== $event_start_day ) ) {

								$json_events['events'][ $x ]['end']             = $event_end_date_formatted;
							$json_events['events'][ $x ]['unformated_end_date'] = $event_end_date;

						}
					}
				}
			}

			$product = '';

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {

				$product = wc_get_product( $event->ID );

			}

			if ( ! empty( $product ) ) {

				if ( $product->is_in_stock() ) {

					$json_events['events'][ $x ]['in_stock'] = 'yes';

				} else {

					$json_events['events'][ $x ]['in_stock'] = 'no';

				}
			} else {

				// Not a product so make in stock.
				$json_events['events'][ $x ]['in_stock'] = 'yes';

			}

			$timestamp   = get_post_meta( $event->ID, 'WooCommerceEventsExpireTimestamp', true );
			$event_event = get_post_meta( $event->ID, 'WooCommerceEventsEvent', true );
			$today       = time();

			if ( ! empty( $timestamp ) && 'Event' === $event_event && $today > $timestamp ) {

				$json_events['events'][ $x ]['className'] = 'fooevents-expired-event-calendar';

			}

			$x++;

		}

		return $json_events;

	}

	/**
	 * Process shortcodes
	 *
	 * @param array $attributes shortcode attributes.
	 * @return array
	 */
	public function process_shortcodes( $attributes ) {

		$processed_attributes = array();

		if ( empty( $attributes['locale'] ) ) {

			$attributes['locale'] = get_locale();

		}

		foreach ( $attributes as $key => $attribute ) {

			if ( strpos( $attribute, ':' ) !== false ) {

				$att_ret = array();
				$parts   = explode( ';', $attribute );

				foreach ( $parts as $part ) {

					if ( strpos( $part, '{' ) !== false ) {

						$att_ret_sub = array();

						$start   = strpos( $part, '{' );
						$end     = strpos( $part, '}', $start + 1 );
						$length  = $end - $start;
						$att_sub = substr( $part, $start + 1, $length - 1 );

						$atts    = explode( ':', $part );
						$att_key = trim( $atts[0] );

						$atts = explode( ':', $att_sub );

						$att_sub_key = trim( $atts[0] );
						$atts[1]     = str_replace( "'", '', $atts[1] );
						$att_att     = trim( $atts[1] );

						$att_ret_sub[ $this->process_key( $att_sub_key ) ] = $att_att;

						$att_ret[ $this->process_key( $att_key ) ] = $att_ret_sub;

					} else {

						$atts = explode( ':', $part );

						$att_key = trim( $atts[0] );
						$atts[1] = str_replace( "'", '', $atts[1] );
						$att_att = trim( $atts[1] );

						$att_ret[ $this->process_key( $att_key ) ] = $att_att;

					}
				}

				$processed_attributes[ $this->process_key( $key ) ] = $att_ret;

			} else {

				$processed_attributes[ $this->process_key( $key ) ] = $attribute;

			}
		}

		return $processed_attributes;

	}

	/**
	 * Adds global calendar options to the WooCommerce Event settings panel
	 *
	 * @return array
	 */
	public function get_tab_settings() {

		$settings = array(
			'section_title'                    => array(
				'name' => __( 'Calendar Settings', 'fooevents-calendar' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'wc_settings_fooevents_pdf_tickets_settings_title',
			),
			'globalFooEventsTwentyFourHour'    => array(
				'name'  => __( 'Enable 24 hour time format', 'fooevents-calendar' ),
				'type'  => 'checkbox',
				'id'    => 'globalFooEventsTwentyFourHour',
				'value' => 'yes',
				'desc'  => __( 'Uses 24 hour time format on the calendar.', 'fooevents-calendar' ),
				'class' => 'text uploadfield',
			),
			'globalFooEventsStartDay'          => array(
				'name'  => __( 'Only display start day', 'fooevents-calendar' ),
				'type'  => 'checkbox',
				'id'    => 'globalFooEventsStartDay',
				'value' => 'yes',
				'desc'  => __( 'When multi-day plugin is active only display the event start day', 'fooevents-calendar' ),
				'class' => 'text uploadfield',
			),
			'globalFooEventsAllDayEvent'       => array(
				'name'  => __( 'Enable full day events', 'fooevents-calendar' ),
				'type'  => 'checkbox',
				'id'    => 'globalFooEventsAllDayEvent',
				'value' => 'yes',
				'desc'  => __( 'Removes event time from calendar entry titles.', 'fooevents-calendar' ),
				'class' => 'text uploadfield',
			),
			'globalFooEventsCalendarTheme'     => array(
				'name'    => __( 'Calendar theme', 'fooevents-calendar' ),
				'type'    => 'select',
				'id'      => 'globalFooEventsCalendarTheme',
				'std'     => '',
				'default' => '',
				'options' => array(
					'default'    => __( 'Default', 'fooevents-calendar' ),
					'light'      => __( 'Light', 'fooevents-calendar' ),
					'dark'       => __( 'Dark', 'fooevents-calendar' ),
					'flat'       => __( 'Flat', 'fooevents-calendar' ),
					'minimalist' => __( 'Minimalist', 'fooevents-calendar' ),
				),
				'desc'    => __( 'Selects calendar theme to be used on Wordpress frontend.', 'fooevents-calendar' ),
				'class'   => 'text uploadfield',
			),
			'globalFooEventsCalendarListTheme' => array(
				'name'    => __( 'Events list theme', 'fooevents-calendar' ),
				'type'    => 'select',
				'id'      => 'globalFooEventsCalendarListTheme',
				'std'     => '',
				'default' => '',
				'options' => array(
					'default'    => __( 'Default', 'fooevents-calendar' ),
					'light-card' => __( 'Light Card', 'fooevents-calendar' ),
					'dark-card'  => __( 'Dark Card', 'fooevents-calendar' ),
				),
				'desc'    => __( 'Selects events list theme to be used on Wordpress frontend.', 'fooevents-calendar' ),
				'class'   => 'text uploadfield',
			),
		);

		$settings['section_end'] = array(
			'type' => 'sectionend',
			'id'   => 'wc_settings_fooevents_pdf_tickets_settings_end',
		);

		return $settings;

	}


	/**
	 * Assign admin permissions
	 */
	public function assign_admin_caps() {

		$role = get_role( 'administrator' );
		$role->add_cap( 'publish_fooevents_calendar' );

	}

	/**
	 * Removes user permissions
	 *
	 * @global array $wp_roles
	 */
	public function remove_event_user_caps() {

		$delete_caps = array(

			'publish_fooevents_calendar',

		);

		global $wp_roles;
		foreach ( $delete_caps as $cap ) {

			foreach ( array_keys( $wp_roles->roles ) as $role ) {

				$wp_roles->remove_cap( $role, $cap );

			}
		}

	}

	/**
	 * Process keys and bride FullCalendar js
	 *
	 * @param array $key option key.
	 * @return array
	 */
	public function process_key( $key ) {

		$check_key = $this->check_general( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_views( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_agenda( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_listview( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_currentdate( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_texttimecust( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_clickinghovering( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_selection( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_eventdata( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_eventrendering( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		$check_key = $this->check_timelineview( $key );
		if ( $check_key !== $key ) {

			return $check_key;

		}

		return $key;

	}

	/**
	 * Check generals options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_general( $key ) {

		switch ( $key ) {
			case 'defaultview':
				return 'defaultView';
			case 'defaultdate':
				return 'defaultDate';
			case 'custombuttons':
				return 'customButtons';
			case 'buttonicons':
				return 'buttonIcons';
			case 'themebuttonicons':
				return 'themeButtonIcons';
			case 'firstday':
				return 'firstDay';
			case 'isrtl':
				return 'isRTL';
			case 'hiddendays':
				return 'hiddenDays';
			case 'fixedweekcount':
				return 'fixedWeekCount';
			case 'weeknumbers':
				return 'weekNumbers';
			case 'weeknumberswithindays':
				return 'weekNumbersWithinDays';
			case 'weeknumbercalculation':
				return 'weekNumberCalculation';
			case 'businesshours':
				return 'businessHours';
			case 'contentheight':
				return 'contentHeight';
			case 'aspectratio':
				return 'aspectRatio';
			case 'handlewindowresize':
				return 'handleWindowResize';
			case 'windowresizedelay':
				return 'windowResizeDelay';
			case 'eventlimit':
				return 'eventLimit';
			case 'eventlimitclick':
				return 'eventLimitClick';
			case 'viewrender':
				return 'viewRender';
			case 'viewdestroy':
				return 'viewDestroy';
			case 'dayrender':
				return 'dayRender';
			case 'windowresize':
				return 'windowResize';
		}

		return $key;

	}

	/**
	 * Check view options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_views( $key ) {

		switch ( $key ) {
			case 'defaultview':
				return 'defaultView';
			case 'getview':
				return 'getView';
			case 'changeview':
				return 'changeView';
		}

		return $key;

	}

	/**
	 * Check agenda options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_agenda( $key ) {

		switch ( $key ) {
			case 'alldayslot':
				return 'allDaySlot';
			case 'alldaytext':
				return 'allDayText';
			case 'slotduration':
				return 'slotDuration';
			case 'slotlabelformat':
				return 'slotLabelFormat';
			case 'slotlabelinterval':
				return 'slotLabelInterval';
			case 'snapduration':
				return 'snapDuration';
			case 'scrolltime':
				return 'scrollTime';
			case 'mintime':
				return 'minTime';
			case 'maxtime':
				return 'maxTime';
			case 'sloteventoverlap':
				return 'slotEventOverlap';
		}

		return $key;

	}

	/**
	 * Check listview options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_listview( $key ) {

		switch ( $key ) {
			case 'listdayformat':
				return 'listDayFormat';
			case 'listdayaltformat':
				return 'listDayAltFormat';
			case 'noeventsmessage':
				return 'noEventsMessage';
		}

		return $key;

	}

	/**
	 * Check currentdate options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_currentdate( $key ) {

		switch ( $key ) {
			case 'defaultdate':
				return 'defaultDate';
			case 'nowindicator':
				return 'nowIndicator';
		}

		return $key;

	}

	/**
	 * Check text time custom options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_texttimecust( $key ) {

		switch ( $key ) {
			case 'timeformat':
				return 'timeFormat';
			case 'columnformat':
				return 'columnFormat';
			case 'titleformat':
				return 'titleFormat';
			case 'columnformat':
				return 'columnFormat';
			case 'titleformat':
				return 'titleFormat';
			case 'buttontext':
				return 'buttonText';
			case 'monthnames':
				return 'monthNames';
			case 'monthnamesshort':
				return 'monthNamesShort';
			case 'daynames':
				return 'dayNames';
			case 'daynamesshort':
				return 'dayNamesShort';
			case 'weeknumbertitle':
				return 'weekNumberTitle';
			case 'displayeventtime':
				return 'displayEventTime';
			case 'displayeventend':
				return 'displayEventEnd';
			case 'eventlimittext':
				return 'eventLimitText';
			case 'daypopoverformat':
				return 'dayPopoverFormat';
		}

		return $key;

	}

	/**
	 * Check clicking hovering options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_clickinghovering( $key ) {

		switch ( $key ) {
			case 'navlinks':
				return 'navLinks';
		}

		return $key;

	}

	/**
	 * Check selection options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_selection( $key ) {

		switch ( $key ) {
			case 'selecthelper':
				return 'selectHelper';
			case 'unselectauto':
				return 'unselectAuto';
			case 'unselectcancel':
				return 'unselectCancel';
			case 'selectoverlap':
				return 'selectOverlap';
			case 'selectconstraint':
				return 'selectConstraint';
			case 'selectallow':
				return 'selectAllow';
		}

		return $key;

	}

	/**
	 * Check event data options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_eventdata( $key ) {

		switch ( $key ) {
			case 'eventsources':
				return 'eventSources';
			case 'alldaydefault':
				return 'allDayDefault';
			case 'unselectcancel':
				return 'unselectCancel';
			case 'startparam':
				return 'startParam';
			case 'endparam':
				return 'endParam';
			case 'timezoneparam':
				return 'timezoneParam';
			case 'lazyfetching':
				return 'lazyFetching';
			case 'defaulttimedeventduration':
				return 'defaultTimedEventDuration';
			case 'defaultalldayeventduration':
				return 'defaultAllDayEventDuration';
			case 'forceeventduration':
				return 'forceEventDuration';
		}

		return $key;

	}

	/**
	 * Check event rendering options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_eventrendering( $key ) {

		switch ( $key ) {
			case 'eventcolor':
				return 'eventColor';
			case 'eventbackgroundcolor':
				return 'eventBackgroundColor';
			case 'eventbordercolor':
				return 'eventBorderColor';
			case 'eventtextcolor':
				return 'eventTextColor';
			case 'nextdaythreshold':
				return 'nextDayThreshold';
			case 'eventorder':
				return 'eventOrder';
		}

		return $key;

	}

	/**
	 * Check timeline view options
	 *
	 * @param string $key option key.
	 * @return string
	 */
	public function check_timelineview( $key ) {

		switch ( $key ) {
			case 'resourceareawidth':
				return 'resourceAreaWidth';
			case 'resourcelabeltext':
				return 'resourceLabelText';
			case 'resourcecolumns':
				return 'resourceColumns';
			case 'slotwidth':
				return 'slotWidth';
			case 'slotduration':
				return 'slotDuration';
			case 'slotlabelformat':
				return 'slotLabelFormat';
			case 'slotlabelinterval':
				return 'slotLabelInterval';
			case 'slotlabelinterval':
				return 'slotLabelInterval';
			case 'snapduration':
				return 'snapDuration';
			case 'snapduration':
				return 'snapDuration';
			case 'scrolltime':
				return 'scrollTime';
		}

		return $key;

	}

	/**
	 * Loads text-domain for localization
	 */
	public function load_text_domain() {

		$path   = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$loaded = load_plugin_textdomain( 'fooevents-calendar', false, $path );

	}

	/**
	 * Format array for the datepicker
	 *
	 * WordPress stores the locale information in an array with a alphanumeric index, and
	 * the datepicker wants a numerical index. This function replaces the index with a number
	 *
	 * @param array $array_to_strip array to process.
	 * @return array
	 */
	private function strip_array_indices( $array_to_strip ) {

		$new_array = array();
		foreach ( $array_to_strip as $array_item ) {
			$new_array[] = $array_item;
		}

		return( $new_array );

	}

	/**
	 * Get custom post types, unset unusable types
	 *
	 * @return array
	 */
	private function get_custom_post_types() {

		$post_types = get_post_types();

		unset( $post_types['attachment'] );
		unset( $post_types['revision'] );
		unset( $post_types['nav_menu_item'] );
		unset( $post_types['custom_css'] );
		unset( $post_types['customize_changeset'] );
		unset( $post_types['oembed_cache'] );
		unset( $post_types['product'] );
		unset( $post_types['product_variation'] );
		unset( $post_types['shop_order'] );
		unset( $post_types['shop_order_refund'] );
		unset( $post_types['shop_coupon'] );
		unset( $post_types['event_magic_tickets'] );
		unset( $post_types['user_request'] );
		unset( $post_types['wp_block'] );
		unset( $post_types['scheduled-action'] );
		unset( $post_types['fe_eventbrite_event'] );

		return $post_types;

	}

	/**
	 * Convert the php date format string to a js date format
	 *
	 * @param string $format WordPress date format.
	 * @return string
	 */
	private function date_format_php_to_js( $format ) {

		switch ( $format ) {
			case 'D d-m-y':
				return( 'D dd-mm-yy' );
			case 'D d-m-Y':
				return( 'D dd-mm-yy' );
			case 'l d-m-Y':
				return( 'DD dd-mm-yy' );
			case 'jS F Y':
				return( 'd MM, yy' );
			case 'F j, Y':
				return( 'MM dd, yy' );
			case 'M. j, Y':
				return( 'M. dd, yy' );
			case 'M. d, Y':
				return( 'M. dd, yy' );
			case 'mm/dd/yyyy':
				return( 'mm/dd/yy' );
			case 'j F Y':
				return( 'd MM yy' );
			case 'Y/m/d':
				return( 'yy/mm/dd' );
			case 'm/d/Y':
				return( 'mm/dd/yy' );
			case 'd/m/Y':
				return( 'dd/mm/yy' );
			case 'Y-m-d':
				return( 'yy-mm-dd' );
			case 'm-d-Y':
				return( 'mm-dd-yy' );
			case 'd-m-Y':
				return( 'dd-mm-yy' );
			case 'j. FY':
				return( 'd. MMyy' );
			case 'j. F Y':
				return( 'd. MM yy' );
			case 'j.m.Y':
				return( 'd.mm.yy' );
			case 'd.m.Y':
				return( 'dd.mm.yy' );
			case 'j.n.Y':
				return( 'd.m.yy' );
			case 'j. n. Y':
				return( 'd. m. yy' );
			case 'j.n. Y':
				return( 'd.m. yy' );
			case 'j \d\e F \d\e Y':
				return( "d 'de' MM 'de' yy" );
			case 'D j M Y':
				return( 'D d M yy' );
			case 'D F j':
				return( 'D MM d' );
			case 'l j F Y':
				return( 'DD d MM yy' );
			case 'l, j M Y':
				return( 'DD, d M yy' );
			default:
				return( 'yy-mm-dd' );
		}

	}

	/**
	 * Array of month names for translation to English
	 *
	 * @param string $event_date unprocessed date.
	 * @return string
	 */
	private function convert_month_to_english( $event_date ) {

		$months = array(
			// French.
			'janvier'        => 'January',
			'février'        => 'February',
			'mars'           => 'March',
			'avril'          => 'April',
			'mai'            => 'May',
			'juin'           => 'June',
			'juillet'        => 'July',
			'aout'           => 'August',
			'août'           => 'August',
			'septembre'      => 'September',
			'octobre'        => 'October',

			// German.
			'Januar'         => 'January',
			'Februar'        => 'February',
			'März'           => 'March',
			'Mai'            => 'May',
			'Juni'           => 'June',
			'Juli'           => 'July',
			'Oktober'        => 'October',
			'Dezember'       => 'December',
			'Montag'         => '',
			'Dienstag'       => '',
			'Mittwoch'       => '',
			'Donnerstag'     => '',
			'Freitag'        => '',
			'Samstag'        => '',
			'Sonntag'        => '',

			// Spanish.
			'enero'          => 'January',
			'febrero'        => 'February',
			'marzo'          => 'March',
			'abril'          => 'April',
			'mayo'           => 'May',
			'junio'          => 'June',
			'julio'          => 'July',
			'agosto'         => 'August',
			'septiembre'     => 'September',
			'setiembre'      => 'September',
			'octubre'        => 'October',
			'noviembre'      => 'November',
			'diciembre'      => 'December',
			'novembre'       => 'November',
			'décembre'       => 'December',
			'lunes'          => '',
			'martes'         => '',
			'miércoles'      => '',
			'jueves'         => '',
			'viernes'        => '',
			'sábado'         => '',
			'domingo'        => '',

			// Catalan - Spain
			'gener'          => 'January',
			'febrer'         => 'February',
			'març'           => 'March',
			'abril'          => 'April',
			'maig'           => 'May',
			'juny'           => 'June',
			'juliol'         => 'July',
			'agost'          => 'August',
			'setembre'       => 'September',
			'octubre'        => 'October',
			'novembre'       => 'November',
			'desembre'       => 'December',

			// Dutch.
			'januari'        => 'January',
			'februari'       => 'February',
			'maart'          => 'March',
			'april'          => 'April',
			'mei'            => 'May',
			'juni'           => 'June',
			'juli'           => 'July',
			'augustus'       => 'August',
			'september'      => 'September',
			'oktober'        => 'October',
			'november'       => 'November',
			'december'       => 'December',
			'maandag'        => '',
			'dinsdag'        => '',
			'woensdag'       => '',
			'donderdag'      => '',
			'vrijdag'        => '',
			'zaterdag'       => '',
			'zondag'         => '',

			// Italian.
			'Gennaio'        => 'January',
			'Febbraio'       => 'February',
			'Marzo'          => 'March',
			'Aprile'         => 'April',
			'Maggio'         => 'May',
			'Giugno'         => 'June',
			'Luglio'         => 'July',
			'Agosto'         => 'August',
			'Settembre'      => 'September',
			'Ottobre'        => 'October',
			'Novembre'       => 'November',
			'Dicembre'       => 'December',

			// Polish.
			'Styczeń'        => 'January',
			'Luty'           => 'February',
			'Marzec'         => 'March',
			'Kwiecień'       => 'April',
			'Maj'            => 'May',
			'Czerwiec'       => 'June',
			'Lipiec'         => 'July',
			'Sierpień'       => 'August',
			'Wrzesień'       => 'September',
			'Październik'    => 'October',
			'Listopad'       => 'November',
			'Grudzień'       => 'December',

			// Afrikaans.
			'Januarie'       => 'January',
			'Februarie'      => 'February',
			'Maart'          => 'March',
			'Mei'            => 'May',
			'Junie'          => 'June',
			'Julie'          => 'July',
			'Augustus'       => 'August',
			'Oktober'        => 'October',
			'Desember'       => 'December',

			// Turkish.
			'Ocak'           => 'January',
			'Şubat'          => 'February',
			'Mart'           => 'March',
			'Nisan'          => 'April',
			'Mayıs'          => 'May',
			'Haziran'        => 'June',
			'Temmuz'         => 'July',
			'Ağustos'        => 'August',
			'Eylül'          => 'September',
			'Ekim'           => 'October',
			'Kasım'          => 'November',
			'Aralık'         => 'December',

			// Portuguese.
			'janeiro'        => 'January',
			'fevereiro'      => 'February',
			'março'          => 'March',
			'abril'          => 'April',
			'maio'           => 'May',
			'junho'          => 'June',
			'julho'          => 'July',
			'agosto'         => 'August',
			'setembro'       => 'September',
			'outubro'        => 'October',
			'novembro'       => 'November',
			'dezembro'       => 'December',

			// Swedish.
			'Januari'        => 'January',
			'Februari'       => 'February',
			'Mars'           => 'March',
			'April'          => 'April',
			'Maj'            => 'May',
			'Juni'           => 'June',
			'Juli'           => 'July',
			'Augusti'        => 'August',
			'September'      => 'September',
			'Oktober'        => 'October',
			'November'       => 'November',
			'December'       => 'December',

			// Czech.
			'leden'          => 'January',
			'únor'           => 'February',
			'březen'         => 'March',
			'duben'          => 'April',
			'květen'         => 'May',
			'červen'         => 'June',
			'červenec'       => 'July',
			'srpen'          => 'August',
			'září'           => 'September',
			'říjen'          => 'October',
			'listopad'       => 'November',
			'prosinec'       => 'December',

			// Norwegian.
			'januar'         => 'January',
			'februar'        => 'February',
			'mars'           => 'March',
			'april'          => 'April',
			'mai'            => 'May',
			'juni'           => 'June',
			'juli'           => 'July',
			'august'         => 'August',
			'september'      => 'September',
			'oktober'        => 'October',
			'november'       => 'November',
			'desember'       => 'December',

			// Danish.
			'januar'         => 'January',
			'februar'        => 'February',
			'marts'          => 'March',
			'april'          => 'April',
			'maj'            => 'May',
			'juni'           => 'June',
			'juli'           => 'July',
			'august'         => 'August',
			'september'      => 'September',
			'oktober'        => 'October',
			'november'       => 'November',
			'december'       => 'December',

			// Finnish.
			'tammikuu'       => 'January',
			'helmikuu'       => 'February',
			'maaliskuu'      => 'March',
			'huhtikuu'       => 'April',
			'toukokuu'       => 'May',
			'kesäkuu'        => 'June',
			'heinäkuu'       => 'July',
			'elokuu'         => 'August',
			'syyskuu'        => 'September',
			'lokakuu'        => 'October',
			'marraskuu'      => 'November',
			'joulukuu'       => 'December',

			// Russian.
			'Январь'         => 'January',
			'Февраль'        => 'February',
			'Март'           => 'March',
			'Апрель'         => 'April',
			'Май'            => 'May',
			'Июнь'           => 'June',
			'Июль'           => 'July',
			'Август'         => 'August',
			'Сентябрь'       => 'September',
			'Октябрь'        => 'October',
			'Ноябрь'         => 'November',
			'Декабрь'        => 'December',

			// Icelandic.
			'Janúar'         => 'January',
			'Febrúar'        => 'February',
			'Mars'           => 'March',
			'Apríl'          => 'April',
			'Maí'            => 'May',
			'Júní'           => 'June',
			'Júlí'           => 'July',
			'Ágúst'          => 'August',
			'September'      => 'September',
			'Oktober'        => 'October',
			'Nóvember'       => 'November',
			'Desember'       => 'December',

			// Latvian.
			'janvāris'       => 'January',
			'februāris'      => 'February',
			'marts'          => 'March',
			'aprīlis'        => 'April',
			'maijs'          => 'May',
			'jūnijs'         => 'June',
			'jūlijs'         => 'July',
			'augusts'        => 'August',
			'septembris'     => 'September',
			'oktobris'       => 'October',
			'novembris'      => 'November',
			'decembris'      => 'December',

			// Lithuanian.
			'sausio'         => 'January',
			'vasario'        => 'February',
			'kovo'           => 'March',
			'balandžio'      => 'April',
			'gegužės'        => 'May',
			'birželio'       => 'June',
			'liepos'         => 'July',
			'rugpjūčio'      => 'August',
			'rugsėjo'        => 'September',
			'spalio'         => 'October',
			'lapkričio'      => 'November',
			'gruodžio'       => ' December',

			// Greek.
			'Ιανουάριος'     => 'January',
			'����εβρουάριος' => 'February',
			'Μάρτιος'        => 'March',
			'Απρίλιος'       => 'April',
			'Μάιος'          => 'May',
			'Ιούνιος'        => 'June',
			'Ιούλιος'        => 'July',
			'Αύγουστος'      => 'August',
			'Σεπτέμβριος'    => 'September',
			'Οκτώβριος'      => 'October',
			'Νοέμβριο����'   => 'November',
			'Δεκέμβριος'     => 'December',

			// Slovak - Slovakia.
			'január'         => 'January',
			'február'        => 'February',
			'marec'          => 'March',
			'apríl'          => 'April',
			'máj'            => 'May',
			'jún'            => 'June',
			'júl'            => 'July',
			'august'         => 'August',
			'september'      => 'September',
			'október'        => 'October',
			'november'       => 'November',
			'december'       => 'December',

			// Slovenian - Slovenia
			'januar'         => 'January',
			'februar'        => 'February',
			'marec'          => 'March',
			'april'          => 'April',
			'maj'            => 'May',
			'junij'          => 'June',
			'julij'          => 'July',
			'avgust'         => 'August',
			'september'      => 'September',
			'oktober'        => 'October',
			'november'       => 'November',
			'december'       => 'December',

			// Romanian - Romania
			'ianuarie'       => 'January',
			'februarie'      => 'February',
			'martie'         => 'March',
			'aprilie'        => 'April',
			'mai'            => 'May',
			'iunie'          => 'June',
			'iulie'          => 'July',
			'august'         => 'August',
			'septembrie'     => 'September',
			'octombrie'      => 'October',
			'noiembrie'      => 'November',
			'decembrie'      => 'December',

			// Croatian - Croatia.

			'siječanj'       => 'January',
			'veljača'        => 'February',
			'ožujak'         => 'March',
			'travanj'        => 'April',
			'svibanj'        => 'May',
			'lipanj'         => 'June',
			'srpanj'         => 'July',
			'kolovoz'        => 'August',
			'rujan'          => 'September',
			'listopad'       => 'October',
			'studeni'        => 'November',
			'prosinac'       => 'December',

		);

		$pattern     = array_keys( $months );
		$replacement = array_values( $months );

		foreach ( $pattern as $key => $value ) {
			$pattern[ $key ] = '/\b' . $value . '\b/iu';
		}

		$replaced_event_date = preg_replace( $pattern, $replacement, $event_date );

		$replaced_event_date = str_replace( ' de ', ' ', $replaced_event_date );

		return $replaced_event_date;

	}

	/**
	 * Output FooEvents Calendar errors to admin screen
	 */
	public function display_meta_errors() {

		if ( ! session_id() ) {

			session_start();

		}

		if ( ! empty( $_SESSION ) ) {

			if ( array_key_exists( 'fooevents_calendar_errors', $_SESSION ) ) {

				echo '<div class="error">';
				foreach ( $_SESSION['fooevents_calendar_errors'] as $error ) {
					echo '<p>' . esc_attr( $error ) . '</p>';
				}
				echo '</div>';

			}

			unset( $_SESSION['fooevents_calendar_errors'] );

		}

	}

}

add_action( 'admin_init', 'fooevents_calendar_redirect' );
add_action( 'activated_plugin', 'fooevents_calendar_activate' );

/**
 *  Add option to redirect on plugin activation.
 *
 * @param string $plugin the plugin.
 */
function fooevents_calendar_activate( $plugin ) {

	if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

		require_once ABSPATH . '/wp-admin/includes/plugin.php';

	}

	if ( ! is_plugin_active( 'fooevents/fooevents.php' ) && 'fooevents-calendar/fooevents-calendar.php' === $plugin ) {

		add_option( 'fooevents_calendar_do_activation_redirect', true );

	}

}

/**
 *  Do activation redirect if required.
 */
function fooevents_calendar_redirect() {

	if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'is_plugin_active_for_network' ) ) {

		require_once ABSPATH . '/wp-admin/includes/plugin.php';

	}

	$option = get_option( 'fooevents_calendar_do_activation_redirect', false );

	if ( $option ) {

		delete_option( 'fooevents_calendar_do_activation_redirect' );

		if ( ! isset( $_GET['activate-multi'] ) && ! is_plugin_active( 'fooevents/fooevents.php' ) ) {

			wp_redirect( 'admin.php?page=fooevents-settings' );

		}
	}

}
