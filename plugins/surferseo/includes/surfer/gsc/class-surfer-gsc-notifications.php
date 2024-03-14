<?php
/**
 * Class to handle notifications related to GSC.
 *
 * @package SurferSEO
 */

namespace SurferSEO\Surfer\GSC;

/**
 * Class to handle notifications in wp-admin related to GSC.
 */
class Surfer_GSC_Notifications {

	use Surfer_GSC_Common;

	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init function.
	 */
	public function init() {

		add_action( 'admin_notices', array( $this, 'notify_enable_gsc' ) );
		add_action( 'admin_notices', array( $this, 'notify_new_gsc_data_collected' ) );
		add_action( 'admin_init', array( $this, 'save_enable_email_performance_report' ) );
	}

	/**
	 * Displays notification asking for tracking permission.
	 *
	 * @return void
	 */
	public function notify_enable_gsc() {
		$connected = Surfer()->get_surfer()->is_surfer_connected();

		if ( ! $connected || Surfer()->get_surfer()->get_gsc()->check_if_gsc_connected() ) {
			return;
		}

		$dismissals = (array) get_option( 'surfer_notification_dismissals' );

		?>
		<?php if ( ! in_array( 'gsc_question', $dismissals, true ) ) : ?>
		<div class="notice surfer-notice surfer-layout is-dismissible">
			<h3><?php esc_html_e( 'Data-driven SEO optimization with Surfer and Google Search Console', 'surferseo' ); ?></h3>
			<p><?php esc_html_e( 'Would you like to add Google Search Console (GSC) data to this plugin and see how your website performs?', 'surferseo' ); ?></p>
			<span class="surfer-notice_action_buttons">
				<a href="<?php echo esc_url( admin_url( 'index.php?page=surfer#surfer_gsc_connection' ) ); ?>" class="surfer-button surfer-button--primary surfer-button--small surfer-button--icon-left surfer-analytics" data-event-name="banner_enable_gsc" data-event-data="notification_click">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="currentColor">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M16.7045 4.43777C17.034 4.6888 17.0976 5.1594 16.8466 5.48887L8.84657 15.9889C8.71541 16.161 8.51627 16.2681 8.30033 16.2827C8.08439 16.2972 7.87271 16.2177 7.71967 16.0647L3.21967 11.5647C2.92678 11.2718 2.92678 10.7969 3.21967 10.504C3.51256 10.2111 3.98744 10.2111 4.28033 10.504L8.17351 14.3972L15.6534 4.57981C15.9045 4.25033 16.3751 4.18674 16.7045 4.43777Z" fill="white"/>
					</svg>

					<?php esc_html_e( 'Yes, I want to measure my website performance', 'surferseo' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( sprintf( 'index.php?%s', http_build_query( array_merge( $_GET, array( 'surfer-dismiss-and-save' => 'gsc_question' ) ) ) ) ) ); ?>" class="surfer-button surfer-button--secondary surfer-button--small surfer-button--icon-left">
					<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
					</svg>

					<?php esc_html_e( 'No, thanks', 'surferseo' ); ?>
				</a>
			</span>
		</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Displays wp-admin notification about new data collected from GSC.
	 */
	public function notify_new_gsc_data_collected() {

		$connected = Surfer()->get_surfer()->is_surfer_connected();

		if ( ! $connected || Surfer()->get_surfer()->get_gsc()->check_if_gsc_connected() ) {
			return;
		}

		$report_is_ready = get_transient( 'surfer_gsc_weekly_report_ready' );

		?>
		<?php if ( true === $report_is_ready ) : ?>
		<div class="notice surfer-notice surfer-layout is-dismissible">
			<h3><?php esc_html_e( 'New site performance data is ready!', 'surferseo' ); ?></h3>
			<p><?php esc_html_e( 'We\'ve gathered new performance data from your Google Search Console and you can now check your posts\' performance. Want to get this info delivered directly to your inbox? Go to the configuration page and select “Send me a weekly report on my site\'s performance."', 'surferseo' ); ?></p>
			<span class="surfer-notice_action_buttons">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer-performance-report' ) ); ?>" class="surfer-button surfer-button--primary surfer-button--small surfer-button--icon-left surfer-analytics" data-event-name="banner_gsc_emails" data-event-data="enable_email_notifications">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="currentColor">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M16.7045 4.43777C17.034 4.6888 17.0976 5.1594 16.8466 5.48887L8.84657 15.9889C8.71541 16.161 8.51627 16.2681 8.30033 16.2827C8.08439 16.2972 7.87271 16.2177 7.71967 16.0647L3.21967 11.5647C2.92678 11.2718 2.92678 10.7969 3.21967 10.504C3.51256 10.2111 3.98744 10.2111 4.28033 10.504L8.17351 14.3972L15.6534 4.57981C15.9045 4.25033 16.3751 4.18674 16.7045 4.43777Z" fill="white"/>
					</svg>

					<?php esc_html_e( 'I want to check my site\'s performance', 'surferseo' ); ?>
				</a>
				<?php if ( ! $this->performance_report_email_notification_endabled() ) : ?>
				<a href="<?php echo esc_url( admin_url( 'index.php?page=surfer&surfer_enable_email_notification=1' ) ); ?>" class="surfer-button surfer-button--secondary surfer-button--small surfer-button--icon-left surfer-analytics" data-event-name="banner_gsc_emails" data-event-data="show_results">
					<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
					</svg>

					<?php esc_html_e( 'I want to enable email reports in the settings', 'surferseo' ); ?>
				</a>
				<?php endif; ?>
			</span>
		</div>
			<?php
		endif;
		?>
		<?php
	}

	/**
	 * Enable tracking from GET param and redirect to Surfer config.
	 *
	 * @return void
	 */
	public function save_enable_email_performance_report() {
		if ( isset( $_GET['surfer_enable_email_notification'] ) && 1 === (int) $_GET['surfer_enable_email_notification'] ) {
			Surfer()->get_surfer_settings()->save_option( 'content-importer', 'surfer_position_monitor_summary', true );
			wp_safe_redirect( admin_url( 'admin.php?page=surfer#header_position_monitor' ) );
			exit;
		}
	}
}
