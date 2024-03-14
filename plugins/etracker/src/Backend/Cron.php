<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Backend;

use Etracker\Database\LoggingTable;
use Etracker\Database\ReportingDataTable;
use Etracker\Reporting\Exceptions\ClientSessionException;
use Etracker\Reporting\Client;
use Etracker\Reporting\Report\EAPageReportInterface;
use Etracker\Reporting\Report\EAPageReport;
use Etracker\Reporting\Report\ReportConfig;
use Etracker\Reporting\Report\Report;
use Etracker\Reporting\Report\ReportConfigFilter\ReportConfigFilterFactory;
use Etracker\Util\Logger;

/**
 * The cron-specific functionality of the plugin.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Cron {
	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 *
	 * @access   private
	 *
	 * @var string $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Admin Object.
	 *
	 * @var Admin $admin  Reference to admin object.
	 */
	private $admin;

	/**
	 * Logger Object.
	 *
	 * @var Logger $logger Reference to Logger object.
	 */
	protected $logger;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->admin       = new Admin( $plugin_name, $version );
		$this->logger      = new Logger();

		$this->logger->set_prefix( __CLASS__ );
	}

	/**
	 * Query EAPageReport with proper filter set by etracker_fetch_report_by setting.
	 *
	 * @param EAPageReportInterface $report_factory Prepared EAPageReport to query report with.
	 * @param string                $url            URL to query report for.
	 * @param string                $page_name      PageName to query report for.
	 *
	 * @return EAPageReportInterface Filled EAPageReport object.
	 */
	private function fetch_eapagereport_by( EAPageReportInterface $report_factory, string $url, string $page_name ): EAPageReportInterface {
		// Set default fetch_report_by.
		$fetch_report_by = 'url';
		/**
		 * Filter allows to change page and post identifiers used to fetch etracker report for.
		 *
		 * Possible mode identifiers are:
		 *
		 * - `url_and_page_name` URL and page name must match.
		 * - `page_name` Use page name as single match.
		 * - `url` Use just URL (permalink) as single match (default).
		 *
		 * @since 2.0.0
		 *
		 * @param string $fetch_report_by Mode identifier used to fetch report.
		 */
		$fetch_report_by = \apply_filters( 'etracker_fetch_report_by', $fetch_report_by );

		switch ( $fetch_report_by ) {
			case 'url_and_page_name':
				return $report_factory->get_report_by_url_and_page_name( $url, $page_name );
			case 'page_name':
				return $report_factory->get_report_by_page_name( $page_name );
			case 'url':
			default:
				return $report_factory->get_report_by_url( $url );
		}
	}

	/**
	 * Builds ReportConfig and applies all filters.
	 *
	 * @since 2.0.0
	 *
	 * @return ReportConfig ReportConfig with all filters applied.
	 */
	private function build_report_config(): ReportConfig {
		$report_config = new ReportConfig();

		$rcf_slug = $this->admin->get_etracker_setting_or_default( 'etracker_reporting_timespan', 'last-30-days' );

		// Ensure ReportConfigFilterFactory knows a filter with slug $rcf_slug.
		if ( ReportConfigFilterFactory::has_filter_with_slug( $rcf_slug ) ) {
			// Get ReportConfigFilter defined by setting etracker_reporting_timespan.
			$rcf = ReportConfigFilterFactory::get_instance( $rcf_slug );
			// Apply ReportConfigFilter to $report_config.
			$report_config = $rcf->apply_etracker_report_config_filter( $report_config );
		}

		// We will apply custom filters to `etracker_report_config` after ReportConfigFilter to keep customizations
		// of our customers working.

		/**
		 * Filters etracker ReportConfig to allow modification of start and end dates.
		 *
		 * Adding a filter to `etracker_report_config` allows you to modify
		 * the `$report_config` `startDate` and `endDate` fields.
		 *
		 * Example usage:
		 *
		 * ```
		 * function my_theme_etracker_reports_for_this_quarter( $report_config ) {
		 *   // Calculate first day of this quarter.
		 *   $offset_first_month_of_this_quarter = ( date( 'n' ) - 1 ) % 3;
		 *   $first_day_of_this_quarter          = new DateTime( "first day of -$offset_first_month_of_this_quarter month midnight" );
		 *   // Update `$report_config` `startDate` field.
		 *   $report_config['startDate'] = $first_day_of_this_quarter->format( 'Y-m-d' );
		 *   // Keep `endDate` unmodified.
		 *   // Return updated `$report_config`.
		 *   return $report_config;
		 * };
		 *
		 * add_filter( 'etracker_report_config', 'my_theme_etracker_reports_for_this_quarter' );
		 * ```
		 *
		 * @since 2.0.0
		 *
		 * @param ReportConfig $report_config ReportConfig
		 */
		$report_config = \apply_filters( 'etracker_report_config', $report_config );

		return $report_config;
	}

	/**
	 * Action hook, called via etracker_cron_fetch_reports schedule.
	 *
	 * Fetches reports from etracker and stores them in database.
	 *
	 * @return bool
	 *
	 * @since    2.0.0
	 */
	public function action_fetch_reports(): bool {
		// Fetch etracker reports.
		$this->logger->notice( __FUNCTION__ . ' started' );
		$reporting_token = $this->admin->get_etracker_setting_or_default( 'etracker_reporting_token', '' );
		if ( empty( $reporting_token ) ) {
			// Required $reporting_token is not set by user. Skip fetching reports.
			$this->logger->notice( 'empty etracker_reporting_token. Skip fetching reports.' );
			return true;
		}
		$client = new Client( $this->plugin_name, $this->version );
		$client->set_token( $reporting_token );
		try {
			$client->ensure_connected();
			$this->logger->info( 'client is connected' );
		} catch ( ClientSessionException $e ) {
			$this->logger->warning( 'client is not connected: ' . $e->getMessage() );
			return false;
		}

		$report_config        = $this->build_report_config();
		$report_factory       = new EAPageReport( $client->ensure_connected(), $report_config );
		$reporting_data_table = new ReportingDataTable();

		$posts = \get_posts(
			array(
				'orderby'     => 'ID',
				'post_type'   => \get_post_types( array( 'public' => true ) ),
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$url   = \get_permalink( $post->ID );
			$title = $post->post_title;
			$this->logger->debug( "Type: {$post->post_type} URL: {$url} Title: {$title}" );
			try {
				$report        = $this->fetch_eapagereport_by( $report_factory, $url, $title );
				$unique_visits = $report->get_unique_visits();
				if ( is_numeric( $unique_visits ) ) {
					$reporting_data_table->store_unique_visits( $post->ID, $unique_visits, $report->get_start_date(), $report->get_end_date() );
				}
				$this->logger->debug( "Type: {$post->post_type} URL: {$url} Title: {$title} UniqueVisits: " . ( $unique_visits ? $unique_visits : 'NULL' ) . " Start Date: {$report->get_start_date()} End Date: {$report->get_end_date()}" );
			} catch ( Exception $e ) {
				$this->logger->error( "Error while fetching report: $e" );
			}
		}

		$this->logger->notice( __FUNCTION__ . ' finished. ' . count( $posts ) . ' published posts processed and ' . $reporting_data_table->get_store_count() . ' figures stored.' );
		return true;
	}

	/**
	 * Returns filterable value for maximum age of log messages in days.
	 *
	 * Filter: etracker_log_maxage_days.
	 *
	 * @return integer Max age in days.
	 */
	public function log_maxage_days(): int {
		$maxage = 10; // default maxage.
		/**
		 * Filters the maximum age of log messages stored by this plugin.
		 *
		 * Adding a filter to `etracker_log_maxage_days` allows you to change
		 * the maximum age of log messages stored by this plugin.
		 *
		 * Example usage:
		 *
		 * ```
		 * function my_theme_etracker_set_logs_maxage( $current_maxage ) {
		 *  // Keep log messages for only 2 days.
		 *  return 2;
		 * };
		 *
		 * add_filter( 'etracker_log_maxage_days', 'my_theme_etracker_set_logs_maxage' );
		 * ```
		 *
		 * @since 2.0.0
		 *
		 * @param integer $maxage Max age in days.
		 */
		$maxage = (int) \apply_filters( 'etracker_log_maxage_days', $maxage );
		return $maxage;
	}

	/**
	 * Action hook, called via etracker_cron_cleanup_logging schedule.
	 *
	 * Calls LoggingTable cleanup.
	 *
	 * @return void
	 */
	public function action_cleanup_logging() {
		$this->logger->debug( __FUNCTION__ . ' started' );
		$maxage           = $this->log_maxage_days();
		$logging_table    = new LoggingTable();
		$messages_dropped = $logging_table->delete_messages_older_x_days( $maxage );
		$this->logger->notice( __FUNCTION__ . ' ' . $messages_dropped . ' log messages older than ' . $maxage . ' days deleted' );
		$this->logger->debug( __FUNCTION__ . ' finished' );
	}

	/**
	 * Action hook, called by 'updated_option' hook to trigger customer polling notice.
	 *
	 * Fires after the value of an option has been successfully updated.
	 *
	 * @param string $option    Option name changed.
	 * @param mixed  $old_value Old option value.
	 * @param mixed  $value     New option value.
	 *
	 * @return void
	 */
	public function action_schedule_customer_polling( string $option, $old_value, $value ) {
		// Run only if etracker_settings option was modified.
		if ( 'etracker_settings' !== $option ) {
			return;
		}
		// Abort if $value is not an array.
		if ( false === $value || ! is_array( $value ) ) {
			return;
		}

		// Abort if etracker_reporting_token was not set.
		if ( false === array_key_exists( 'etracker_reporting_token', $value ) ) {
			return;
		}

		// Abort if etracker_reporting_token is empty.
		if ( empty( $value['etracker_reporting_token'] ) ) {
			return;
		}

		// Trigger customer polling in 7 days.
		\wp_schedule_single_event( time() + ( 3600 * 24 * 7 ), 'etracker_cron_trigger_customer_polling' );
	}

	/**
	 * Action hook, called via etracker_cron_trigger_customer_polling single event.
	 *
	 * @return void
	 */
	public function action_enable_customer_polling() {
		// Store transient 'etracker_customer_polling' to tell admin backend to show polling notice.
		$actual = get_transient( 'etracker_customer_polling' );
		if ( 'done' !== $actual ) {
			// If polling was not done before, request it now.
			\set_transient( 'etracker_customer_polling', 'requested', 0 );
		}
	}

	/**
	 * Action hook, called by 'updated_option' hook to trigger fetching reports once after setting reporting_token.
	 *
	 * Fires after the value of an option has been successfully updated.
	 *
	 * @param string $option    Option name changed.
	 * @param mixed  $old_value Old option value.
	 * @param mixed  $value     New option value.
	 *
	 * @return void
	 */
	public function action_schedule_fetch_reports_on_settings_changes( string $option, $old_value, $value ) {
		// Run only if etracker_settings option was modified.
		if ( 'etracker_settings' !== $option ) {
			return;
		}
		// Abort if $value is not an array.
		if ( false === $value || ! is_array( $value ) ) {
			return;
		}

		// Abort if etracker_reporting_token was not set.
		if ( false === array_key_exists( 'etracker_reporting_token', $value ) ) {
			return;
		}

		// Abort if etracker_reporting_token is empty.
		if ( empty( $value['etracker_reporting_token'] ) ) {
			return;
		}

		$this->logger->notice( 'Settings changed. Fetching reports triggered.' );

		// Trigger action_fetch_reports.
		\wp_schedule_single_event( time(), 'etracker_cron_fetch_reports' );
	}
}
