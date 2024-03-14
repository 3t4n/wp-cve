<?php
/**
 * Iubenda radar dashboard widget.
 *
 * Includes all radar dashboard widget functions.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Radar_Dashboard_Widget
 */
class Radar_Dashboard_Widget {

	/**
	 * Radar_Dashboard_Widget constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_dashboard_compliance', array( $this, 'get_dashboard_compliance_content' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_radar_dashboard_widget' ) );
	}

	/**
	 * Getting content of dashboard widget compliance status
	 */
	public function get_dashboard_compliance_content() {
		iub_verify_ajax_request( 'iub_dashboard_compliance_nonce', 'iub_nonce' );
		$this->get_widget_html();
		wp_die();
	}

	/**
	 * Adds the iubenda Compliance Status dashboard widget.
	 */
	public function add_radar_dashboard_widget() {
		// Add the dashboard widget.
		wp_add_dashboard_widget(
			'iubenda-compliance-status',
			'iubenda Compliance Status',
			array( $this, 'get_widget_html' )
		);

		// Enqueue styles and scripts for the widget.
		wp_enqueue_style( 'iubenda-compliance-status', IUBENDA_PLUGIN_URL . '/assets/css/radar_dashboard_widget.css', array(), iubenda()->version );
		wp_enqueue_script( 'iubenda-compliance-status', IUBENDA_PLUGIN_URL . '/assets/js/radar_dashboard_widget.js', array( 'jquery' ), iubenda()->version, true );
	}

	/**
	 * Generates the HTML content for the iubenda Compliance Status widget.
	 */
	public function get_widget_html() {
		global $pagenow;

		// Create a new Radar_Service instance.
		$radar = new Radar_Service();

		// Get redirect URL based on menu position.
		if ( 'admin.php' === $pagenow && 'submenu' === iubenda()->options['cs']['menu_position'] ) {
			$redirect_to = admin_url( 'options-general.php?page=iubenda' ); // Sub menu.
		} else {
			$redirect_to = admin_url( 'admin.php?page=iubenda' ); // Top menu.
		}

		// Retrieve various status and configuration information.
		$is_any_service_activated          = iubenda()->settings->is_any_service_activated( iubenda()->settings->services );
		$is_any_service_configured         = iubenda()->settings->is_any_service_configured( iubenda()->settings->services );
		$has_quick_generator_response      = ! empty( ( new Quick_Generator_Service() )->qg_response );
		$is_first_time_plugin_installation = ( ! $is_any_service_activated && ! $is_any_service_configured && ! $has_quick_generator_response );
		$is_product_configured             = ( $is_any_service_activated || $is_any_service_configured );
		$radar_services_percentage         = (int) iubenda()->service_rating->services_percentage();
		$is_radar_completed                = ! empty( $radar->api_configuration ) && 'completed' === (string) iub_array_get( $radar->api_configuration, 'status' );

		$html_content = $this->get_html_content(
			$is_first_time_plugin_installation,
			$is_radar_completed,
			$radar_services_percentage,
			$is_product_configured
		);

		$button_classes    = iub_array_get( $html_content, 'button.classes', 'iubenda-action-button button-green' );
		$button_text       = iub_array_get( $html_content, 'button.text', 'View configuration' );
		$header_text       = iub_array_get( $html_content, 'header.text', 'We have analyzed your website and this is the result.' );
		$header_title      = iub_array_get( $html_content, 'header.title', 'Your rating' );
		$show_alert        = iub_array_get( $html_content, 'show_alert', false );
		$show_party_popper = iub_array_get( $html_content, 'show_party_popper', false );

		?>
		<div class="iubenda-compliance-widget-container">
			<?php if ( $show_alert ) : ?>
				<div class="iubenda-alert-container">
					<img class="iubenda-warning-icon" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon-colored.svg" alt="Warning">
					<p><?php esc_html_e( 'Your compliance status requires attention!', 'iubenda' ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $is_radar_completed ) : ?>
				<div class="circularBar" id="iubendaRadarCircularBar" data-perc="<?php echo esc_attr( $radar_services_percentage ); ?>"></div>
			<?php else : ?>
				<span class="iubenda-compliance-spinner"></span>
			<?php endif; ?>

			<h1 class="iubenda-header-title">
				<?php echo esc_html( $header_title ); ?>
			</h1>
			<p class="iubenda-header-text"><?php echo esc_html( $header_text ); ?></p>

			<?php if ( $show_party_popper ) : ?>
				<div content="iubenda-party-popper-container">
					<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/party-popper.svg" alt="Party Popper">
				</div>
			<?php endif; ?>

			<a class="<?php echo esc_attr( $button_classes ); ?>" href="<?php echo esc_url( $redirect_to ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
	}

	/**
	 * Gets HTML content based on different scenarios of plugin installation and compliance status.
	 *
	 * @param   bool $is_first_time_plugin_installation  Whether it's the first-time plugin installation.
	 * @param   bool $is_radar_completed                 Whether the compliance radar (scan) is completed.
	 * @param   int  $compliance_score                   The compliance score.
	 * @param   bool $is_product_configured              Whether the product is configured.
	 *
	 * @return array Associative array containing HTML content based on the specified scenarios.
	 */
	private function get_html_content( $is_first_time_plugin_installation, $is_radar_completed, $compliance_score, $is_product_configured ) {
		// Scenario 1: First-time plugin installation, without scan results or radar completed.
		if ( $is_first_time_plugin_installation && ! $is_radar_completed ) {
			return array(
				'header' => array(
					'text'  => __( 'We are scanning your website and shortly will show you the results.', 'iubenda' ),
					'title' => __( 'Analyzing compliance status…', 'iubenda' ),
				),
				'button' => array(
					'text' => __( 'Help me get compliant', 'iubenda' ),
				),
			);
		}

		// Scenario 2: First-time plugin installation, with scan results ready.
		if ( $is_first_time_plugin_installation && $is_radar_completed && $compliance_score < 100 ) {
			return array(
				'header'     => array(
					'text'  => __( 'We have analyzed your website and this is the result.', 'iubenda' ),
					'title' => __( 'Your rating', 'iubenda' ),
				),
				'button'     => array(
					'text' => __( 'Help me get compliant', 'iubenda' ),
				),
				'show_alert' => true,
			);
		}

		// Scenario 3: Scans done, score available, not 100%, and product not configured.
		if ( ! $is_first_time_plugin_installation && $is_radar_completed && $compliance_score < 100 && ! $is_product_configured ) {
			return array(
				'header'     => array(
					'text'  => __( 'You have missing configurations to complete in order to setup our products.', 'iubenda' ),
					'title' => __( 'Your rating', 'iubenda' ),
				),
				'button'     => array(
					'text' => __( 'Configure now', 'iubenda' ),
				),
				'show_alert' => true,
			);
		}

		// Scenario 4: Product configured, but the score is not 100%.
		if ( ! $is_first_time_plugin_installation && $is_radar_completed && $compliance_score < 100 && $is_product_configured ) {
			return array(
				'header'     => array(
					'text'  => __( 'Our plugin can quickly help you achieve full rating and avoid compliance breach.', 'iubenda' ),
					'title' => __( 'Your rating', 'iubenda' ),
				),
				'button'     => array(
					'text' => __( 'Fix my rating', 'iubenda' ),
				),
				'show_alert' => true,
			);
		}

		// Scenario 5: Score is 100%.
		if ( $is_radar_completed && 100 === $compliance_score ) {
			return array(
				'header'            => array(
					'text'  => __( 'Congratulations on achieving full score.', 'iubenda' ),
					'title' => __( 'Your rating', 'iubenda' ),
				),
				'button'            => array(
					'classes' => 'iubenda-action-button button-gray',
					'text'    => __( 'View configuration', 'iubenda' ),
				),
				'show_party_popper' => true,
			);
		}

		return array();
	}
}
