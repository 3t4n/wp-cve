<?php
/**
 * Iubenda notice.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Iubenda_Notice
 */
class Iubenda_Notice {
	const IUB_NOTIFICATIONS = 'iubenda_notifications';

	/**
	 * Iubenda notice default title.
	 *
	 * @var string
	 */
	public $iub_default_title = 'iubenda | All-in-one Compliance for GDPR / CCPA Cookie Consent + more';

	/**
	 * Iubenda notices list.
	 *
	 * Type of the notice.
	 * (error - warning - success - info).
	 *
	 * Location of showing notice.
	 * (all - inside - outside).
	 *
	 * One time notice.
	 * (true - false).
	 *
	 * @var array
	 */
	public $iub_predefined_notices = array();

	/**
	 * Iubenda notices stored in database.
	 *
	 * @var array
	 */
	public $iub_notices = array();

	/**
	 * Iubenda_Notice constructor.
	 */
	public function __construct() {
		if ( 'iubenda' === iub_get_request_parameter( 'page' ) ) {
			add_action( 'admin_head', array( $this, 'iubenda_hide_notices_wp' ) );
		}

		$this->iub_notices = array_filter( (array) get_option( self::IUB_NOTIFICATIONS, array() ) );

		add_action( 'admin_init', array( $this, 'maybe_show_notice' ) );
		add_action( 'wp_ajax_iubenda_dismiss_general_notice', array( $this, 'iub_dismiss_general_notice' ) );
		add_action( 'wp_ajax_iubenda_dismiss_rating_notice', array( $this, 'dismiss_rating_notice' ) );
		add_action( 'after_setup_theme', array( $this, 'load_defaults_notices' ) );
	}

	/**
	 * Load default notices.
	 */
	public function load_defaults_notices() {
		$user_needs_to_verify_his_account_url = 'javascript:void(0)';
		if ( ! empty( iubenda()->settings->links['privacy_policy_generator_edit'] ) ) {
			$user_needs_to_verify_his_account_url = iubenda()->settings->links['privacy_policy_generator_edit'];
		} elseif ( iub_array_get( iubenda()->options['global_options'], 'site_id' ) ) {
			$user_needs_to_verify_his_account_url = iubenda()->settings->links['user_account'];
		}

		$this->iub_predefined_notices = array(
			'iub_legal_documents_generated_success' => array(
				'type'            => 'success',
				'location'        => 'inside',
				'one_time_notice' => true,
				'message'         => __( 'Your website has been created and your legal documents have been generated. Setup your cookie banner and privacy policy button to complete the integration.', 'iubenda' ),
			),
			'iub_user_needs_to_verify_his_account'  => array(
				'type'            => 'error',
				'location'        => 'all',
				'one_time_notice' => false,
				/* translators: 1: AMP file path, 2: Iubenda AMP permission support link. */
				'message'         => sprintf( __( 'To ensure regular scans and full support, <span class="text-bold">verify your account</span>. Check your mailbox now and validate your email address, or check <a href="%s" target="_blank" class="link-underline">your account</a> on iubenda.com. If you already did that, you can safely <a href="#" class="notice-dismiss-by-text dismiss-notification-alert link-underline" data-dismiss-key="iub_user_needs_to_verify_his_account">dismiss this reminder</a>.', 'iubenda' ), $user_needs_to_verify_his_account_url ),
			),
			'iub_products_integrated_success'       => array(
				'type'            => 'success',
				'location'        => 'inside',
				'one_time_notice' => true,
				'message'         => __( 'Our products has been integrated successfully, now customize all products to increase the compliance rating and make your website fully compliant.', 'iubenda' ),
			),
			'iub_comment_cookies_disabled'          => array(
				'type'            => 'error',
				'location'        => 'inside',
				'one_time_notice' => false,
				/* translators: %s: Options-discussion URL. */
				'message'         => sprintf( __( 'Please enable comments cookies opt-in checkbox in the <a href="%s" target="_blank">Discussion settings</a>.', 'iubenda' ), esc_url( admin_url( 'options-discussion.php' ) ) ),
			),
			'iub_amp_file_creation_fail'            => array(
				'type'            => 'error',
				'location'        => 'inside',
				'one_time_notice' => true,
				/* translators: 1: AMP file path, 2: Iubenda AMP permission support link. */
				'message'         => sprintf( __( 'Currently, you do not have write permission for <i class="text-bold">%1$s</i>. For instructions on how to fix this, please read <a class="link-underline" target="_blank" href="%2$s">our guide</a>.', 'iubenda' ), IUBENDA_PLUGIN_PATH . 'templates' . DIRECTORY_SEPARATOR, iubenda()->settings->links['amp_permission_support'] ),
			),
			'iub_us_legislation_handle'             => array(
				'type'            => 'error',
				'location'        => 'all',
				'one_time_notice' => false,
				'title'           => 'iubenda | All-in-one Compliance for GDPR / CCPA Cookie Consent + more',
				/* translators: %s: cs-configuration url. */
				'message'         => sprintf( __( 'Our plugin now offers support for the new US privacy regulations.</br>We highly recommend that you update your configuration: within the legislation section of the Privacy Controls and Cookie Solution, the <span class="text-bold">CCPA</span> option has been replaced with <span class="text-bold">US State Laws</span>. Revise your setup, save your settings, and you’re done! <a href="%s" class="link-underline">Check it out now</a>', 'iubenda' ), add_query_arg( array( 'view' => 'cs-configuration' ), iubenda()->base_url ) ),
			),
			'iub_form_fields_missing'               => array(
				'type'            => 'error',
				'location'        => 'inside',
				'one_time_notice' => true,
				'message'         => __( 'Form saving failed. Please fill the Subject fields.', 'iubenda' ),
			),
			'iub_settings_updated'                  => array(
				'type'            => 'success',
				'location'        => 'inside',
				'one_time_notice' => true,
				'message'         => __( 'Settings saved.', 'iubenda' ),
			),
		);
	}

	/**
	 * Dismiss rating notice.
	 *
	 * @return void
	 */
	public function dismiss_rating_notice() {
		iub_verify_ajax_request( 'iub_dismiss_notice', 'iub_nonce' );
		$result = false;

		if ( ! empty( iub_get_request_parameter( 'iub_nonce' ) ) ) {
			$delay      = ! empty( iub_get_request_parameter( 'delay' ) ) ? absint( iub_get_request_parameter( 'delay' ) ) : 0;
			$activation = (array) get_option( 'iubenda_activation_data', iubenda()->activation );

			// delay notice.
			if ( $delay > 0 ) {
				$activation = array_merge( $activation, array( 'update_delay_date' => time() + $delay ) );
				// hide notice permanently.
			} else {
				$activation = array_merge(
					$activation,
					array(
						'update_delay_date' => 0,
						'update_notice'     => false,
					)
				);
			}

			// update activation options.
			$result = iubenda()->iub_update_options( 'iubenda_activation_data', $activation );
		}

		wp_send_json( $result );
	}

	/**
	 * Checking for iubenda notice.
	 */
	public function maybe_show_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->has_outside_plugin_notice() ) {
			add_action( 'admin_print_scripts', array( $this, 'iub_general_scripts' ), 999 );
			add_action( 'admin_notices', array( $this, 'iub_general_div' ) );
		}

		if ( $this->check_for_showing_rating_notice() ) {
			add_action( 'admin_print_scripts', array( $this, 'iub_rating_scripts' ), 999 );
			add_action( 'admin_notices', array( $this, 'iub_rating_div' ) );
		}
	}

	/**
	 * Show iubenda notice inside plugin.
	 */
	public function show_notice_inside_plugin() {
		$this->load_defaults_notices();
		foreach ( $this->iub_notices as $notice_key => $notice_status ) {
			if ( empty( $notice_status ) ) {
				return;
			}

			$notice_data = $this->iub_predefined_notices[ $notice_key ];

			if ( (string) iub_array_get( $notice_data, 'location' ) === 'inside' || (string) iub_array_get( $notice_data, 'location' ) === 'all' ) {
				require IUBENDA_PLUGIN_PATH . '/views/partials/alert.php';
			}

			if ( (bool) iub_array_get( $notice_data, 'one_time_notice' ) ) {
				$this->remove_notice( $notice_key );
			}
		}
	}

	/**
	 * Add iubenda notice in database.
	 *
	 * @param   string $notice_key Notice key.
	 */
	public function add_notice( string $notice_key ) {
		$this->iub_notices[ $notice_key ] = true;
		iubenda()->iub_update_options( self::IUB_NOTIFICATIONS, $this->iub_notices );
	}

	/**
	 * Remove iubenda notice from database.
	 *
	 * @param   string $notice_key Notice key.
	 */
	public function remove_notice( string $notice_key ) {
		$this->iub_notices[ $notice_key ] = false;
		iubenda()->iub_update_options( self::IUB_NOTIFICATIONS, $this->iub_notices );
	}

	/**
	 * Check if iubenda has notice
	 *
	 * @return bool
	 */
	public function has_outside_plugin_notice() {
		$this->load_defaults_notices();
		$outside_notice = array();

		foreach ( $this->iub_notices as $key => $value ) {
			if ( iub_array_get( $this->iub_predefined_notices, "$key.location" ) === 'all' || iub_array_get( $this->iub_predefined_notices, "$key.location" ) === 'outside' ) {
				$outside_notice[] = $key;
			}
		}

		return ! empty( $outside_notice );
	}

	/**
	 * Check if iubenda has inside plugin notice
	 *
	 * @return bool
	 */
	public function has_inside_plugin_notice() {
		$this->load_defaults_notices();
		$plugin_notice = array();

		foreach ( $this->iub_notices as $key => $value ) {
			if ( iub_array_get( $this->iub_predefined_notices, "$key.location" ) === 'all' || iub_array_get( $this->iub_predefined_notices, "$key.location" ) === 'inside' ) {
				$plugin_notice[] = $key;
			}
		}

		return ! empty( $plugin_notice );
	}

	/**
	 * Scripts for general notices
	 */
	public function iub_general_scripts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<script type="text/javascript">
			( function ( $ ) {
				$( document ).ready( function () {
					$( '.iubenda-notice-outside-plugin.is-dismissible' ).on( 'click', '.notice-dismiss, .notice-dismiss-by-text', function ( e ) {
						// console.log( $( e ) );
						// console.log( $(e.target.parentElement).data('dismiss-key') );
						$.post( ajaxurl, {
							action: "iubenda_dismiss_general_notice",
							dismiss_key: $(e.target.parentElement).data('dismiss-key'),
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							iub_nonce: '<?php echo esc_html( wp_create_nonce( 'iub_dismiss_general_notice' ) ); ?>'
						} );

						$( e.delegateTarget ).slideUp( 'fast' );
					} );
				} );
			} )( jQuery );
		</script>
		<?php
	}

	/**
	 * Div container for general notices
	 */
	public function iub_general_div() {
		foreach ( $this->iub_notices as $notice_key => $notice_status ) {
			if ( empty( $notice_status ) ) {
				return;
			}
			$notice_data = $this->iub_predefined_notices[ $notice_key ];

			if ( (string) iub_array_get( $notice_data, 'location' ) === 'all' || (string) iub_array_get( $notice_data, 'location' ) === 'outside' ) {
				?>
				<div id="iub-notice-<?php echo esc_html( sanitize_title( $notice_key ) ); ?>" class="iubenda-notice-outside-plugin notice <?php echo esc_html( iub_array_get( $notice_data, 'type' ) ); ?> is-dismissible" data-dismiss-key="<?php echo esc_html( $notice_key ); ?>">
					<div>
						<h4><?php echo esc_html( iub_array_get( $notice_data, 'title', $this->iub_default_title ) ); ?></h4>
						<p data-dismiss-key="<?php echo esc_html( $notice_key ); ?>"><?php echo wp_kses_post( iub_array_get( $notice_data, 'message' ) ); ?></p>
					</div>
				</div>
				<?php
			}
		}
	}

	/**
	 * Dismiss iubenda general notices.
	 *
	 * @return void
	 */
	public function iub_dismiss_general_notice() {
		iub_verify_ajax_request( 'iub_dismiss_general_notice', 'iub_nonce' );
		$result    = false;
		$iub_nonce = iub_get_request_parameter( 'iub_nonce' );

		if ( ! empty( $iub_nonce ) ) {
			$dismiss_key = iub_get_request_parameter( 'dismiss_key' );

			if ( iub_array_get( $this->iub_notices, $dismiss_key ) === true ) {
				$this->remove_notice( $dismiss_key );
				$result = true;
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Check if rating notice must show.
	 *
	 * @return bool
	 */
	private function check_for_showing_rating_notice() {
		$current_update = 10;
		$activation     = (array) get_option( 'iubenda_activation_data', iubenda()->activation );

		// get current time.
		$current_time = time();

		if ( $activation['update_version'] < $current_update ) {
			// check version, if update ver is lower than plugin ver, set update notice to true.
			$activation = array_merge(
				$activation,
				array(
					'update_version' => $current_update,
					'update_notice'  => true,
				)
			);

			// set activation date if not set.
			if ( false === (bool) $activation['update_date'] ) {
				$activation = array_merge( $activation, array( 'update_date' => $current_time ) );
			}

			iubenda()->iub_update_options( 'iubenda_activation_data', $activation );
		}

		// display current version notice.
		if ( true === $activation['update_notice'] ) {
			// include notice js, only if needed.
			add_action( 'admin_print_scripts', array( $this, 'iub_rating_scripts' ), 999 );

			// get activation date.
			$activation_date = $activation['update_date'];

			// set delay in seconds.
			$delay = WEEK_IN_SECONDS;

			if ( 0 === (int) $activation['update_delay_date'] ) {
				if ( $activation_date + $delay > $current_time ) {
					$activation['update_delay_date'] = $activation_date + $delay;
				} else {
					$activation['update_delay_date'] = $current_time;
				}

				iubenda()->iub_update_options( 'iubenda_activation_data', $activation );
			}

			if ( ( ! empty( $activation['update_delay_date'] ) ? (int) $activation['update_delay_date'] : $current_time ) <= $current_time ) {
				// add notice.
				return true;
			}
		}

		return false;
	}

	/**
	 * Scripts for rating notice
	 */
	public function iub_rating_scripts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$delay = MONTH_IN_SECONDS * 6;
		?>
		<script type="text/javascript">
			( function ( $ ) {
				$( document ).ready( function () {
					// step 1.
					$( '.iubenda-notice .step-1 a' ).on( 'click', function ( e ) {
						e.preventDefault();

						$( '.iubenda-notice .step-1' ).slideUp( 'fast' );
						$( '.iubenda-notice .step-1' ).hide( 'fast' );

						if ( $( e.target ).hasClass( 'reply-yes' ) ) {
							$( '.iubenda-notice .step-2.step-yes' ).show( 'fast' );
						} else {
							$( '.iubenda-notice .step-2.step-no' ).show( 'fast' );
						};
					} );
					// step 2.
					$( '.iubenda-notice.is-dismissible' ).on( 'click', '.notice-dismiss, .step-2 a', function ( e ) {
						// console.log( $( e ) );.

						var delay = <?php echo esc_html( $delay ); ?>;

						if ( $( e.target ).hasClass( 'reply-yes' ) ) {
							delay = 0;
						}

						$.post( ajaxurl, {
							action: 'iubenda_dismiss_rating_notice',
							delay: delay,
							url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
							iub_nonce: '<?php echo esc_html( wp_create_nonce( 'iub_dismiss_notice' ) ); ?>'
						} );

						$( e.delegateTarget ).slideUp( 'fast' );
					} );
				} );
			} )( jQuery );
		</script>
		<?php
	}

	/**
	 * Div container for rating notice
	 */
	public function iub_rating_div() {
		?>
		<div id="iubenda-rate" class="iubenda-notice notice is-dismissible">
			<div>
				<p class="step-1">
					<span class="notice-question"><?php esc_html_e( 'Enjoying the iubenda Cookie & Consent Database Plugin?', 'iubenda' ); ?></span>
					<span class="notice-reply">
							<a href="#" class="reply-yes"><?php esc_html_e( 'Yes', 'iubenda' ); ?></a>
							<a href="#" class="reply-no"><?php esc_html_e( 'No', 'iubenda' ); ?></a>
						</span>
				</p>
				<p class="step-2 step-yes">
					<span class="notice-question"><?php esc_html_e( "Whew, what a relief!? We've worked countless hours to make this plugin as useful as possible - so we're pretty happy that you're enjoying it. While you here, would you mind leaving us a 5 star rating? It would really help us out.", 'iubenda' ); ?></span>
					<span class="notice-reply">
							<a href="https://wordpress.org/support/plugin/iubenda-cookie-law-solution/reviews/?filter=5" target="_blank" class="reply-yes"><?php esc_html_e( 'Sure!', 'iubenda' ); ?></a>
							<a href="javascript:void(0)" class="reply-no"><?php esc_html_e( 'No thanks', 'iubenda' ); ?></a>
						</span>
				</p>
				<p class="step-2 step-no">
					<span class="notice-question"><?php esc_html_e( "We're sorry to hear that. Would you mind giving us some feedback?", 'iubenda' ); ?></span>
					<span class="notice-reply">
							<a href="https://iubenda.typeform.com/to/BXuSMZ" target="_blank" class="reply-yes"><?php esc_html_e( 'Ok sure!', 'iubenda' ); ?></a>
							<a href="javascript:void(0)" class="reply-no"><?php esc_html_e( 'No thanks', 'iubenda' ); ?></a>
						</span>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Hide all notices except iubenda notice
	 */
	public function iubenda_hide_notices_wp() {
		?>
		<style>
			.error, .iubenda-notice-outside-plugin, .notice:not(.iubenda-notice) {
				display: none;
			}
		</style>
		<?php
	}
}
