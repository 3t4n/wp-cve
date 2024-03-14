<?php
/**
 * Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their reports.
 *
 * @package     WooCommerce\Admin
 * @version     2.6.0
 * @deprecated  4.6.0
 */

// use Automattic\Jetpack\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reports_Setup_Wizard class.
 */
class Reports_Setup_Wizard {

	/**
	 * Current step
	 *
	 * @var string
	 */
	private $step = '';

	/**
	 * Steps for the setup wizard
	 *
	 * @var array
	 */
	private $steps = array();

	/**
	 * Actions to be executed after the HTTP response has completed
	 *
	 * @var array
	 */
	private $deferred_actions = array();

	/**
	 * Hook in tabs.
	 *
	 * @deprecated 4.6.0
	 */
	public function __construct() {
		// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.6.0', 'Onboarding is maintained in WooCommerce Admin.' );
		add_action('admin_menu', array($this, 'admin_menus'));
		add_action('admin_init', array($this, 'setup_wizard'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	/**
	 * Add admin menus/screens.
	 *
	 * @deprecated 4.6.0
	 */
	public function admin_menus() {
		// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.6.0', 'Onboarding is maintained in WooCommerce Admin.' );
		add_dashboard_page( 'admin.php', '', 'manage_options', 'wrld-setup', '' );
	}

	/**
	 * Is the WooCommerce Admin actively included in the WooCommerce core?
	 * Based on presence of a basic WC Admin function.
	 *
	 * @deprecated 4.6.0
	 * @return boolean
	 */
	protected function is_Reports_active() {
		return defined( 'WRLD_REPORTS_FILE' );
	}

	/**
	 * Should we show the WooCommerce Admin install option?
	 * True only if the user can install plugins,
	 * and is running the correct version of WordPress.
	 *
	 * @see Reports_Setup_Wizard::$Reports_plugin_minimum_wordpress_version
	 *
	 * @deprecated 4.6.0
	 * @return boolean
	 */
	protected function should_show_Reports() {
		// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.6.0', 'Onboarding is maintained in WooCommerce Admin.' );
		$wordpress_minimum_met = version_compare( get_bloginfo( 'version' ), '5.3', '>=' );
		return current_user_can( 'install_plugins' ) && $wordpress_minimum_met && ! $this->is_Reports_active();
	}

	/**
	 * Should we show the new WooCommerce Admin onboarding experience?
	 *
	 * @deprecated 4.6.0
	 * @return boolean
	 */
	protected function should_show_Reports_onboarding() {
		// As of WooCommerce 4.1, all new sites should use the latest OBW from wc-admin package.
		// This filter will allow for forcing the old wizard while we migrate e2e tests.
		return ! apply_filters( 'woocommerce_setup_wizard_force_legacy', true );
	}

	/**
	 * Should we display the 'Recommended' step?
	 * True if at least one of the recommendations will be displayed.
	 *
	 * @deprecated 4.6.0
	 * @return boolean
	 */
	protected function should_show_recommended_step() {
		return $this->should_show_Reports();
	}

	/**
	 * Register/enqueue scripts and styles for the Setup Wizard.
	 *
	 * Hooked onto 'admin_enqueue_scripts'.
	 *
	 * @deprecated 4.6.0
	 */
	public function enqueue_scripts() {
		if (is_rtl()) {
			wp_enqueue_style('reports_setup_css', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/wizard.rtl.css');
		} else {
			wp_enqueue_style('reports_setup_css', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/wizard.css');
		}
		wp_enqueue_script('reports_setup_js', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/js/wizard.js', array('jquery'), WRLD_PLUGIN_VERSION, true);
		wp_localize_script( 'reports_setup_js', 'admin_url', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Show the setup wizard.
	 *
	 * @deprecated 4.6.0
	 */
	public function setup_wizard() {
		if ( empty( $_GET['page'] ) || 'wrld-setup' !== $_GET['page'] ) { // WPCS: CSRF ok, input var ok.
			return;
		}
		$default_steps = array(
			'new_onboarding' => array(
				'name'    => '',
				'view'    => array( $this, 'wc_setup_new_onboarding' ),
				'handler' => array( $this, 'wc_setup_new_onboarding_save' ),
			),
			'reports_setup'    => array(
				'name'    => __( 'Reports setup', 'learndash-reports-by-wisdmlabs' ),
				'view'    => array( $this, 'wc_setup_reports_setup' ),
				'handler' => array( $this, 'wc_setup_reports_setup_save' ),
			),
			'migration'        => array(
				'name'    => __( 'Data migration', 'learndash-reports-by-wisdmlabs' ),
				'view'    => array( $this, 'wc_setup_migration' ),
				'handler' => array( $this, 'wc_setup_migration_save' ),
			),
			'next_steps'     => array(
				'name'    => __( 'Ready!', 'learndash-reports-by-wisdmlabs' ),
				'view'    => array( $this, 'wc_setup_ready' ),
				'handler' => '',
			),
		);

		// Hide the new/improved onboarding experience screen if the user is not part of the a/b test.
		if ( ! $this->should_show_Reports_onboarding() ) {
			unset( $default_steps['new_onboarding'] );
		}

		$this->steps = apply_filters( 'wrld_setup_wizard_steps', $default_steps );
		$this->step  = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) ); // WPCS: CSRF ok, input var ok.

		// @codingStandardsIgnoreStart
		if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
			call_user_func( $this->steps[ $this->step ]['handler'], $this );
		}
		// @codingStandardsIgnoreEnd

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_steps();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
	}

	/**
	 * Get the URL for the next step's screen.
	 *
	 * @param string $step  slug (default: current step).
	 * @return string       URL for next step if a next step exists.
	 *                      Admin URL if it's the last step.
	 *                      Empty string on failure.
	 *
	 * @deprecated 4.6.0
	 * @since 3.0.0
	 */
	public function get_next_step_link( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}

		$keys = array_keys( $this->steps );
		if ( end( $keys ) === $step ) {
			return admin_url();
		}

		$step_index = array_search( $step, $keys, true );
		if ( false === $step_index ) {
			return '';
		}

		return add_query_arg( 'step', $keys[ $step_index + 1 ], remove_query_arg( 'activate_error' ) );
	}

	/**
	 * Setup Wizard Header.
	 *
	 * @deprecated 4.6.0
	 */
	public function setup_wizard_header() {
		// same as default WP from wp-admin/admin-header.php.
		$wp_version_class = 'branch-' . str_replace( array( '.', ',' ), '-', floatval( get_bloginfo( 'version' ) ) );

		set_current_screen();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php esc_html_e( 'Wisdm Reports For LearnDash &rsaquo; Setup Wizard', 'learndash-reports-by-wisdmlabs' ); ?></title>
			<?php do_action( 'admin_enqueue_scripts' );?>
			<?php wp_print_scripts( 'reports_setup_js' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php //do_action( 'admin_print_scripts' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="wrld-setup wp-core-ui <?php echo esc_attr( 'wrld-setup-step__' . $this->step ); ?> <?php echo esc_attr( $wp_version_class ); ?>">
		<h1 class="wc-logo"><a href="https://wisdmlabs.com/" target="_blank"><img src="<?php echo esc_url( WRLD_REPORTS_SITE_URL ); ?>/assets/images/wisdmlabs.png" alt="<?php esc_attr_e( 'Reports', 'learndash-reports-by-wisdmlabs' ); ?>" /></a></h1>
		<?php
	}

	/**
	 * Setup Wizard Footer.
	 *
	 * @deprecated 4.6.0
	 */
	public function setup_wizard_footer() {
		$current_step = $this->step;
		?>
			<?php if ( 'new_onboarding' === $current_step || 'reports-setup' === $current_step ) : ?>
				<a class="wrld-setup-footer-links" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Not right now', 'learndash-reports-by-wisdmlabs' ); ?></a>
			<?php elseif ( 'recommended' === $current_step || 'activate' === $current_step ) : ?>
				<a class="wrld-setup-footer-links" href="<?php echo esc_url( $this->get_next_step_link() ); ?>"><?php esc_html_e( 'Skip this step', 'learndash-reports-by-wisdmlabs' ); ?></a>
			<?php endif; ?>
			<?php do_action( 'woocommerce_setup_footer' ); ?>
			</body>
		</html>
		<?php
	}

	/**
	 * Output the steps.
	 *
	 * @deprecated 4.6.0
	 */
	public function setup_wizard_steps() {
		$output_steps      = $this->steps;

		// Hide the activate step if Jetpack is already active, unless WooCommerce Services
		// features are selected, or unless the Activate step was already taken.

		unset( $output_steps['new_onboarding'] );

		?>
		<ol class="wrld-setup-steps">
			<?php
			foreach ( $output_steps as $step_key => $step ) {
				$is_completed = array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true );

				if ( $step_key === $this->step ) {
					?>
					<li class="active"><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				} elseif ( $is_completed ) {
					?>
					<li class="done">
						<a href="<?php echo esc_url( add_query_arg( 'step', $step_key, remove_query_arg( 'activate_error' ) ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
					</li>
					<?php
				} else {
					?>
					<li><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				}
			}
			?>
		</ol>
		<?php
	}

	/**
	 * Output the content for the current step.
	 *
	 * @deprecated 4.6.0
	 */
	public function setup_wizard_content() {
		echo '<div class="wrld-setup-content">';
		if ( ! empty( $this->steps[ $this->step ]['view'] ) ) {
			call_user_func( $this->steps[ $this->step ]['view'], $this );
		}
		echo '</div>';
	}

	/**
	 * Display's a prompt for users to try out the new improved WooCommerce onboarding experience in WooCommerce Admin.
	 *
	 * @deprecated 4.6.0
	 */
	public function wc_setup_new_onboarding() {
		_deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.6.0', 'Onboarding is maintained in WooCommerce Admin.' );
		?>
			<div class="wrld-setup-step__new_onboarding-wrapper">
				<p class="wrld-setup-step__new_onboarding-welcome"><?php esc_html_e( 'Welcome to', 'learndash-reports-by-wisdmlabs' ); ?></p>
				<h1 class="wc-logo"><a href="https://woocommerce.com/"><img src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/woocommerce_logo.png" alt="<?php esc_attr_e( 'learndash-reports-by-wisdmlabs', 'learndash-reports-by-wisdmlabs' ); ?>" /></a></h1>
				<p><?php esc_html_e( 'Get your reports up and running more quickly with our new and improved setup experience', 'learndash-reports-by-wisdmlabs' ); ?></p>

				<form method="post" class="activate-new-onboarding">
					<?php wp_nonce_field( 'wrld-setup' ); ?>
					<input type="hidden" name="save_step" value="new_onboarding" />
					<p class="wrld-setup-actions step">
						<button class="button-primary button button-large" value="<?php esc_attr_e( 'Yes please', 'learndash-reports-by-wisdmlabs' ); ?>" name="save_step"><?php esc_html_e( 'Yes please', 'learndash-reports-by-wisdmlabs' ); ?></button>
					</p>
				</form>
				<?php if ( ! $this->is_Reports_active() ) : ?>
					<p class="wrld-setup-step__new_onboarding-plugin-info"><?php esc_html_e( 'The "WooCommerce Admin" plugin will be installed and activated', 'learndash-reports-by-wisdmlabs' ); ?></p>
				<?php endif; ?>
			</div>
		<?php
	}

	/**
	 * Installs WooCommerce admin and redirects to the new onboarding experience.
	 *
	 * @deprecated 4.6.0
	 */
	public function wc_setup_new_onboarding_save() {
		// _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '4.6.0', 'Onboarding is maintained in WooCommerce Admin.' );
	}

	/**
	 * Initial "reports setup" step.
	 * Location, product type, page setup, and tracking opt-in.
	 */
	public function wc_setup_reports_setup() {
		
		?>
		<form method="post" class="address-step">
			<input type="hidden" name="save_step" value="reports_setup" />
			<?php wp_nonce_field( 'wrld-setup' ); ?>
			<p class="reports-setup"><?php esc_html_e( 'The following wizard will help you configure your reports and get you started quickly.', 'learndash-reports-by-wisdmlabs' ); ?></p>

			<p class="wrld-setup-actions step">
				<button class="button-primary button button-large" value="<?php esc_attr_e( "Let's go!", 'learndash-reports-by-wisdmlabs' ); ?>" name="save_step"><?php esc_html_e( "Let's go!", 'learndash-reports-by-wisdmlabs' ); ?></button>
			</p>
		</form>
		<?php
	}

	/**
	 * Save initial reports settings.
	 *
	 * @deprecated 4.6.0
	 */
	public function wc_setup_reports_setup_save() {
		wp_safe_redirect($this->get_next_step_link());
		exit;
	}

	/**
	 * Initial "reports setup" step.
	 * Location, product type, page setup, and tracking opt-in.
	 */
	public function wc_setup_migration() {
		
		?>
		<form method="post" class="address-step">
			<input type="hidden" name="save_step" value="reports_setup" />
			<?php wp_nonce_field( 'wrld-setup' ); ?>
			<p class="reports-setup"><?php esc_html_e( 'The following wizard will help you configure your reports and get you started quickly.', 'learndash-reports-by-wisdmlabs' ); ?></p>

			<div class="migration-steps">
				<ol>
					<!-- <li>
						<div>
							<span class="title">Time Tracking data</span>
							<span class="description">Running this will update the time tracking data to a better format so that the reports gets loaded faster</span>
						</div>
						<progress value="0" max="100" class="hidden"> 0% </progress>
					</li> -->
					<li>
						<div>
							<span class="title"> <?php esc_html_e('Course Users data', 'learndash-reports-by-wisdmlabs'); ?></span>
							<span class="description"><?php esc_html_e('Running this will update the course user enrollment data to a better format in the database so that the reports gets loaded faster.', 'learndash-reports-by-wisdmlabs'); ?></span>
						</div>
						<button class="start-course-migration button"> <?php esc_html_e('Start Data Migration', 'learndash-reports-by-wisdmlabs'); ?> </button>
						<progress value="0" max="100" class="hidden"> 0% </progress>
					</li>
					<li>
						<div>
							<span class="title"> <?php esc_html_e('Group Users data', 'learndash-reports-by-wisdmlabs'); ?></span>
							<span class="description"><?php esc_html_e('Running this will update the group user enrollment data to a better format in the database so that the reports gets loaded faster.', 'learndash-reports-by-wisdmlabs'); ?></span>
						</div>
						<button class="start-group-migration button">  <?php esc_html_e('Start Data Migration', 'learndash-reports-by-wisdmlabs'); ?></button>
						<progress value="0" max="100" class="hidden"> 0% </progress>
					</li>
					<li>
						<div>
							<span class="title"> <?php esc_html_e('Course time data', 'learndash-reports-by-wisdmlabs'); ?></span>
							<span class="description"><?php esc_html_e('Running this will update the group user enrollment data to a better format in the database so that the reports gets loaded faster.', 'learndash-reports-by-wisdmlabs'); ?></span>
						</div>
						<button class="start-timespent-migration button">  <?php esc_html_e('Start Data Migration', 'learndash-reports-by-wisdmlabs'); ?></button>
						<progress value="0" max="100" class="hidden"> 0% </progress>
					</li>
				</ol>
			</div>
			<div></div>

			<p class="wrld-setup-actions step">
				<button class="button-primary button button-large" value="<?php esc_attr_e( "Let's go!", 'learndash-reports-by-wisdmlabs' ); ?>" name="save_step"><?php esc_html_e( "Let's go!", 'learndash-reports-by-wisdmlabs' ); ?></button>
			</p>
		</form>
		<?php
	}

	/**
	 * Save initial reports settings.
	 *
	 * @deprecated 4.6.0
	 */
	public function wc_setup_migration_save() {
		wp_safe_redirect($this->get_next_step_link());
		exit;
	}

	/**
	 * Helper method to retrieve the current user's email address.
	 *
	 * @deprecated 4.6.0
	 * @return string Email address
	 */
	protected function get_current_user_email() {
		$current_user = wp_get_current_user();
		$user_email   = $current_user->user_email;

		return $user_email;
	}

	/**
	 * Final step.
	 *
	 * @deprecated 4.6.0
	 */
	public function wc_setup_ready() {
		// We've made it! Don't prompt the user to run the wizard again.

		$user_email   = $this->get_current_user_email();
		$docs_url     = 'https://wisdmlabs.com/docs/product/wisdm-learndash-reports/lr-getting-started/';
		$wrld_page         = get_option( 'ldrp_reporting_page', false );
		$help_text    = sprintf(
			/* translators: %1$s: link to docs */
			__( 'Visit wisdmlabs.com to learn more about <a href="%1$s" target="_blank">getting started</a>.', 'learndash-reports-by-wisdmlabs' ),
			$docs_url
		);
		?>
		<h1><?php esc_html_e( "You're ready to get started!", 'learndash-reports-by-wisdmlabs' ); ?></h1>
		<p class="next-steps-help-text"><?php echo wp_kses_post( $help_text ); ?></p>
		<?php if ( $wrld_page && $wrld_page > 0 && 'publish' === get_post_status( $wrld_page ) ) : ?>
			<a href="<?php echo esc_url(get_post_permalink( $wrld_page ));?>">View Dashboard</a>
		<?php endif; ?>
		<a href="<?php echo esc_url(admin_url());?>">Exit Wizard</a>
		<?php
	}
}
new Reports_Setup_Wizard();
if ( ! get_option('wrld-setup') && version_compare(WRLD_PLUGIN_VERSION, '1.8.0', '<=') ) {
	update_option('wrld-setup', 1);	
	// wp_safe_redirect(admin_url('admin.php?page=wrld-setup'));
	// exit;
}
