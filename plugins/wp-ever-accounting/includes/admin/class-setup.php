<?php
/**
 * Setup Wizard Class
 *
 * Takes new users through some basic steps to setup the environment.
 *
 * @since       1.0.2
 * @subpackage  Admin
 * @package     EverAccounting
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Setup_Wizard
 *
 * @since   1.0.2
 *
 * @package EverAccounting\Admin
 */
class Setup_Wizard {

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
	 * Hook in tabs.
	 */
	public function __construct() {
		if ( current_user_can( 'manage_eaccounting' ) ) {
			add_action( 'admin_menu', array( $this, 'admin_menus' ) );
			add_action( 'admin_init', array( $this, 'setup_wizard' ) );
		}
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {
		add_dashboard_page( '', '', 'manage_options', 'ea-setup', '' );
	}

	/**
	 * Show the setup wizard.
	 */
	public function setup_wizard() {
		$page      = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$save_step = filter_input( INPUT_POST, 'save_step', FILTER_SANITIZE_STRING );
		if ( empty( $page ) || 'ea-setup' !== $page ) {
			return;
		}
		$default_steps = array(
			'introduction' => array(
				'name'    => __( 'Introduction', 'wp-ever-accounting' ),
				'view'    => array( $this, 'setup_introduction' ),
				'handler' => '',
			),
			'company'      => array(
				'name'    => __( 'Company setup', 'wp-ever-accounting' ),
				'view'    => array( $this, 'company_settings' ),
				'handler' => array( $this, 'company_settings_save' ),
			),
			'currency'     => array(
				'name'    => __( 'Currency setup', 'wp-ever-accounting' ),
				'view'    => array( $this, 'currency_settings' ),
				'handler' => array( $this, 'currency_settings_save' ),
			),
			'finish'       => array(
				'name'    => __( 'Finish!', 'wp-ever-accounting' ),
				'view'    => array( $this, 'finish_setup' ),
				'handler' => '',
			),
		);

		$this->steps = apply_filters( 'eaccounting_setup_wizard_steps', $default_steps );
		$step        = filter_input( INPUT_GET, 'step', FILTER_SANITIZE_STRING );
		$this->step  = ! empty( $step ) ? sanitize_key( $step ) : current( array_keys( $this->steps ) );

		$version = eaccounting()->get_version();
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'ea-admin-styles', eaccounting()->plugin_url() . '/dist/css/admin.min.css', array(), $version );
		wp_enqueue_style( 'ea-setup', eaccounting()->plugin_url() . '/dist/css/setup.min.css', array( 'install', 'common' ), $version );

		// Add RTL support for admin styles.
		wp_style_add_data( 'ea-setup', 'rtl', 'replace' );
		wp_register_script( 'jquery-select2', eaccounting()->plugin_url( '/dist/js/select2.full' . $suffix . '.js' ), array( 'jquery' ), $version, true );
		wp_enqueue_script( 'ea-select2', eaccounting()->plugin_url( '/dist/js/ea-select2' . $suffix . '.js' ), array( 'jquery', 'jquery-select2' ), $version, true );
		wp_enqueue_script( 'ea-setup', eaccounting()->plugin_url( '/dist/js/ea-setup' . $suffix . '.js' ), array( 'jquery', 'ea-select2' ), $version, true );

		if ( ! empty( $save_step ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
			call_user_func( $this->steps[ $this->step ]['handler'], $this );
		}

		// @codingStandardsIgnoreEnd
		header( 'Content-Type: text/html; charset=utf-8' );
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
	 * @param string $step slug (default: current step).
	 *
	 * @return string       URL for next step if a next step exists.
	 *                      Admin URL if it's the last step.
	 *                      Empty string on failure.
	 * @since 1.0.2
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
	 */
	public function setup_wizard_header() {
		// same as default WP from wp-admin/admin-header.php.
		$wp_version_class = 'branch-' . str_replace( array( '.', ',' ), '-', floatval( get_bloginfo( 'version' ) ) );

		set_current_screen();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<title><?php esc_html_e( 'Ever Accounting &rsaquo; Setup Wizard', 'wp-ever-accounting' ); ?></title>
			<?php wp_print_scripts( array( 'ea-setup' ) ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
		</head>
		<body class="ea-setup wp-core-ui <?php echo esc_attr( 'ea-setup-step__' . $this->step ); ?> <?php echo esc_attr( $wp_version_class ); ?>">
		<h1 class="ea-logo"><a href="https://wpeveraccounting.com/" target="_blank"><img src="<?php echo esc_url( eaccounting()->plugin_url( '/dist/images/logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Ever Accounting', 'wp-ever-accounting' ); ?>"/></a></h1>
		<?php
	}

	/**
	 * Setup Wizard Footer.
	 */
	public function setup_wizard_footer() {
		$current_step = $this->step;
		?>
		<?php do_action( 'eaccounting_setup_footer' ); ?>
		</body>
		</html>
		<?php
	}

	/**
	 * Output the steps.
	 */
	public function setup_wizard_steps() {
		$output_steps = $this->steps;
		?>
		<ol class="ea-setup-steps">
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
	 */
	public function setup_wizard_content() {
		echo '<div class="ea-setup-content">';
		if ( ! empty( $this->steps[ $this->step ]['view'] ) ) {
			call_user_func( $this->steps[ $this->step ]['view'], $this );
		}
		echo '</div>';
	}

	/**
	 * Introducing the setup.
	 *
	 * @since 1.0.2
	 */
	public function setup_introduction() {
		?>
		<h1><?php esc_html_e( 'Welcome!', 'wp-ever-accounting' ); ?></h1>
		<p><?php esc_html_e( 'Thank you for choosing WP Ever Accounting to manage your accounting! This quick setup wizard will help you configure the basic settings.', 'wp-ever-accounting' ); ?></p>
		<p class="ea-setup-actions step">
			<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( 'Let\'s Go!', 'wp-ever-accounting' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Company info setup.
	 *
	 * @since 1.0.2
	 */
	public function company_settings() {
		?>
		<h1><?php esc_html_e( 'Company Setup', 'wp-ever-accounting' ); ?></h1>
		<form method="post">
			<?php

			eaccounting_text_input(
				array(
					'label'    => __( 'Company Name', 'wp-ever-accounting' ),
					'name'     => 'company_name',
					'required' => true,
					'value'    => eaccounting()->settings->get( 'company_name' ),
				)
			);
			eaccounting_text_input(
				array(
					'label'    => __( 'Company Email', 'wp-ever-accounting' ),
					'name'     => 'company_email',
					'default'  => get_option( 'admin_email' ),
					'required' => true,
					'type'     => 'email',
					'value'    => eaccounting()->settings->get( 'company_email' ),
				)
			);

			eaccounting_textarea(
				array(
					'label' => __( 'Company Address', 'wp-ever-accounting' ),
					'name'  => 'company_address',
					'value' => eaccounting()->settings->get( 'company_address' ),
				)
			);
			eaccounting_country_dropdown(
				array(
					'label'    => __( 'Country', 'wp-ever-accounting' ),
					'name'     => 'company_country',
					'required' => true,
				)
			);
			?>

			<p class="ea-setup-actions step">
				<input
						type="submit"
						class="button-primary button button-large button-next"
						value="<?php esc_attr_e( 'Continue', 'wp-ever-accounting' ); ?>"
						name="save_step"/>
				<?php wp_nonce_field( 'company-setup' ); ?>

			</p>
		</form>
		<?php
	}

	/**
	 * Company settings save.
	 *
	 * @since 1.0.2
	 */
	public function company_settings_save() {
		check_admin_referer( 'company-setup' );
		$company_name    = filter_input( INPUT_POST, 'company_name', FILTER_SANITIZE_STRING );
		$company_email   = filter_input( INPUT_POST, 'company_email', FILTER_SANITIZE_EMAIL );
		$company_address = filter_input( INPUT_POST, 'company_address', FILTER_SANITIZE_STRING );
		$company_country = filter_input( INPUT_POST, 'company_country', FILTER_SANITIZE_STRING );

		if ( ! empty( $company_name ) ) {
			eaccounting_update_option( 'company_name', sanitize_text_field( $company_name ) );
		}
		if ( ! empty( $company_email ) ) {
			eaccounting_update_option( 'company_email', sanitize_email( $company_email ) );
		}
		if ( ! empty( $company_address ) ) {
			eaccounting_update_option( 'company_address', sanitize_textarea_field( $company_address ) );
		}
		if ( ! empty( $company_country ) ) {
			eaccounting_update_option( 'company_country', sanitize_text_field( $company_country ) );
		}

		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}

	/**
	 * Currency settings.
	 *
	 * @since 1.0.2
	 */
	public function currency_settings() {
		$codes   = eaccounting_get_global_currencies();
		$options = array();
		foreach ( $codes as $code => $props ) {
			$options[ $code ] = sprintf( '%s (%s)', $props['code'], $props['symbol'] );
		}

		$currencies = eaccounting_get_currencies( array( 'return' => 'array' ) );

		?>
		<h1><?php esc_html_e( 'Currency Setup', 'wp-ever-accounting' ); ?></h1>
		<p><?php esc_html__( 'Default currency rate should be always 1 & additional currency rates should be equivalent of default currency. e.g. If USD is your default currency then USD rate is 1 & GBP rate will be 0.77', 'wp-ever-accounting' ); ?></p>
		<form action="" method="post">
			<table class="wp-list-table widefat fixed stripes">
				<thead>
				<tr>
					<th style="width: 50%;"><?php esc_html_e( 'Code', 'wp-ever-accounting' ); ?></th>
					<th style="width: 30%;"><?php esc_html_e( 'Rate', 'wp-ever-accounting' ); ?></th>
					<th style="width: 20%;"><?php esc_html_e( 'Default', 'wp-ever-accounting' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ( $currencies as $id => $currency ) : ?>
					<tr>
						<td>
							<?php
							eaccounting_select2(
								array(
									'name'     => "code[$id]",
									'options'  => [ '' => __( 'Select', 'wp-ever-accounting' ) ] + $options,
									'value'    => $currency->code,
									'required' => true,
									'id'       => "$id-code",
								)
							);
							?>
						</td>

						<td>
							<?php
							eaccounting_text_input(
								array(
									'name'     => "rate[$id]",
									'value'    => eaccounting_format_decimal( $currency->rate ),
									'required' => true,
									'id'       => "$id-rate",
								)
							);
							?>
						</td>

						<td>
							<input type="radio" name="default" value="<?php echo esc_attr( $currency->code ); ?>" <?php checked( 'USD', $currency->code ); ?>>
						</td>
					</tr>

				<?php endforeach; ?>

				<tr>
					<td colspan="3">
						<strong><?php esc_html_e( 'Additional currency', 'wp-ever-accounting' ); ?></strong>
					</td>
				</tr>

				<tr>
					<td>
						<?php
						eaccounting_select2(
							array(
								'name'    => 'code[custom]',
								'options' => [ '' => __( 'Select', 'wp-ever-accounting' ) ] + $options,
								'id'      => '6-code',
							)
						);
						?>
					</td>

					<td>
						<?php
						eaccounting_text_input(
							array(
								'name'  => 'rate[custom]',
								'value' => '',
								'id'    => '6-rate',
							)
						);
						?>
					</td>

					<td>
						<input type="radio" name="default" value="custom">
					</td>
				</tr>

				</tbody>

			</table>

			<p class="ea-setup-actions step">
				<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'wp-ever-accounting' ); ?>" name="save_step"/>
				<?php wp_nonce_field( 'currency_settings' ); ?>

			</p>
		</form>
		<?php
	}

	/**
	 * Currency settings save.
	 *
	 * @since 1.0.2
	 */
	public function currency_settings_save() {
		check_admin_referer( 'currency_settings' );
		$new_currency = false;
		$default      = filter_input( INPUT_POST, 'default', FILTER_SANITIZE_STRING );
		if ( ! empty( $_REQUEST['code']['custom'] ) && ! empty( $_REQUEST['rate']['custom'] ) ) {
			$new_currency = eaccounting_insert_currency(
				array(
					'code' => sanitize_key( wp_unslash( $_REQUEST['code']['custom'] ) ),
					'rate' => (float) sanitize_text_field( wp_unslash( $_REQUEST['rate']['custom'] ) ),
				)
			);
		}

		$currency = eaccounting_get_currency( $default );

		if ( ! empty( $currency ) && $currency->exists() ) {
			eaccounting_update_option( 'default_currency', $currency->get_code() );
		} elseif ( 'custom' === $default && $new_currency->exists() ) {
			eaccounting_update_option( 'default_currency', $new_currency->get_code() );
		}

		update_option( 'ea_setup_wizard_complete', 'yes' );
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}

	/**
	 * Finishing the setup.
	 *
	 * @since 1.0.2
	 */
	public function finish_setup() {
		?>
		<h1><?php esc_html_e( 'Finish!', 'wp-ever-accounting' ); ?></h1>
		<p><?php esc_html_e( 'You are done with the basic setup of the plugin and ready to use.', 'wp-ever-accounting' ); ?></p>
		<p class="ea-setup-actions step">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=eaccounting' ) ); ?>" class="button button-primary"><?php esc_html_e( 'View Dashboard', 'wp-ever-accounting' ); ?></a>
		</p>
		<?php
	}
}

new Setup_Wizard();
