<?php
/**
 * Peachpay Settings.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

// Admin actions.
require_once PEACHPAY_ABSPATH . 'core/admin/actions/apple-pay-domain-registration.php';
require_once PEACHPAY_ABSPATH . 'core/admin/actions/connect-payments-later.php';
require_once PEACHPAY_ABSPATH . 'core/admin/actions/saved-settings-banner.php';

require_once PEACHPAY_ABSPATH . 'core/util/util.php';
require_once PEACHPAY_ABSPATH . 'core/modules/dashboard/settings-homepage.php';
require_once PEACHPAY_ABSPATH . 'core/admin/settings-payment.php';
require_once PEACHPAY_ABSPATH . 'core/admin/plugin-deactivation.php';
require_once PEACHPAY_ABSPATH . 'core/modules/field-editor/admin/settings-field-editor.php';
require_once PEACHPAY_ABSPATH . 'core/modules/recommended-products/admin/settings-recommended-products.php';
require_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/admin/settings-express-checkout.php';
require_once PEACHPAY_ABSPATH . 'core/modules/currency-switcher/admin/settings-currency-switcher.php';

// Onboarding tour.
require_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-onboarding-tour.php';

/**
 * Enqueues CSS style for the PeachPay settings.
 *
 * @param string $hook Page level hook.
 */
function peachpay_enqueue_settings_styles( $hook ) {
	if ( 'toplevel_page_peachpay' !== $hook ) {
		return;
	}
	wp_enqueue_style(
		'peachpay-settings',
		peachpay_url( 'core/admin/assets/css/admin.css' ),
		array(),
		peachpay_file_version( 'core/admin/assets/css/admin.css' )
	);
	wp_enqueue_style(
		'peachpay-settings-payment-settings',
		peachpay_url( 'core/admin/assets/css/payment.css' ),
		array(),
		peachpay_file_version( 'core/admin/assets/css/payment.css' )
	);
	wp_enqueue_style(
		'peachpay-admin-core',
		peachpay_url( 'public/dist/admin.bundle.css' ),
		array(),
		peachpay_file_version( 'public/dist/admin.bundle.css' )
	);
	wp_enqueue_style(
		'peachpay-settings-button-preview',
		peachpay_url( 'public/dist/express-checkout-button.bundle.css' ),
		array(),
		peachpay_file_version( 'public/dist/express-checkout-button.bundle.css' )
	);
}
add_action( 'admin_enqueue_scripts', 'peachpay_enqueue_settings_styles' );

/**
 * Enqueues the JS for the peachpay settings.
 *
 * @param string $hook Page level hook.
 */
function peachpay_enqueue_settings_scripts( $hook ) {
	if ( 'toplevel_page_peachpay' !== $hook ) {
		return;
	}
	wp_enqueue_media();

	wp_enqueue_script(
		'peachpay-settings',
		peachpay_url( 'core/admin/assets/js/settings.js' ),
		array(),
		peachpay_file_version( 'core/admin/assets/js/settings.js' ),
		false
	);
	wp_enqueue_script(
		'peachpay-admin-core',
		peachpay_url( 'public/dist/admin.bundle.js' ),
		array(),
		peachpay_file_version( 'public/dist/admin.bundle.js' ),
		false
	);
}
add_action( 'admin_enqueue_scripts', 'peachpay_enqueue_settings_scripts' );
/**
 * Hide WordPress nags in our settings page. This is because it interferes with our styling so we supress them in Peachpay's settings.
 */
function peachpay_hide_nag() {
	if ( get_current_screen()->base === 'toplevel_page_peachpay' ) {
		remove_action( 'admin_notices', 'update_nag', 10 );
		remove_action( 'admin_notices', 'maintenance_nag', 10 );
	}
}
add_action( 'admin_head', 'peachpay_hide_nag', 10 );

/**
 * Makes the Home submenu highlighted when the url slug is simply 'peachpay'.
 * Keeps Express checkout and Field editor tabs highlighted regardless of section.
 *
 * @param string $file The parent file.
 */
function peachpay_render_active_submenu( $file ) {
	global $plugin_page, $submenu_file;
	// phpcs:ignore
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;
	if ( 'peachpay' === $plugin_page && ! $tab ) {
		// phpcs:ignore
		$plugin_page = 'peachpay&tab=home';
	} elseif ( 'peachpay' === $plugin_page && 'field' === $tab ) {
		// phpcs:ignore
		$submenu_file = 'peachpay&tab=field';
	} elseif ( 'peachpay' === $plugin_page && 'express_checkout' === $tab ) {
		// phpcs:ignore
		$submenu_file = 'peachpay&tab=express_checkout';
	}
	return $file;
}
add_action( 'parent_file', 'peachpay_render_active_submenu' );

/**
 * Registers each peachpay settings tab.
 */
function peachpay_settings_init() {
	// Payment
	register_setting( 'peachpay_payment', 'peachpay_payment_options' );

	// Currency
	register_setting( 'peachpay_currency', 'peachpay_currency_options' );

	// Field editor
	register_setting( 'peachpay_field_editor_additional', 'peachpay_field_editor_additional' );
	register_setting( 'peachpay_field_editor_billing', 'peachpay_field_editor_billing' );
	register_setting( 'peachpay_field_editor_shipping', 'peachpay_field_editor_shipping' );

	// Product recommendations
	register_setting( 'peachpay_related_products', 'peachpay_related_products_options' );

	// Express checkout
	register_setting( 'peachpay_express_checkout_branding', 'peachpay_express_checkout_branding' );
	register_setting( 'peachpay_express_checkout_window', 'peachpay_express_checkout_window' );
	register_setting( 'peachpay_express_checkout_product_recommendations', 'peachpay_express_checkout_product_recommendations' );
	register_setting( 'peachpay_express_checkout_button', 'peachpay_express_checkout_button' );
	register_setting( 'peachpay_express_checkout_advanced', 'peachpay_express_checkout_advanced' );

	// Landing page generator (currently on hold)
	register_setting( 'peachpay_product_links', 'peachpay_product_links' );

	// phpcs:ignore
	if ( isset( $_GET['tab'] ) && 'payment' === $_GET['tab'] && current_user_can( 'manage_options' ) ) {
		peachpay_settings_payment();
	}
	// phpcs:ignore
	if ( isset( $_GET['tab'] ) && 'currency' === $_GET['tab'] && current_user_can( 'manage_options' ) ) {
		peachpay_settings_currency_switch();
	}
	// phpcs:ignore
	if ( isset( $_GET['tab'] ) && 'field' === $_GET['tab'] && current_user_can( 'manage_options' )  ) {

		peachpay_field_editor();
	}
	// phpcs:ignore
	if ( isset( $_GET['tab'] ) && 'related_products' === $_GET['tab'] && current_user_can( 'manage_options' ) ) {
		peachpay_related_products();
	}
	// phpcs:ignore
	if ( isset( $_GET['tab'] ) && 'express_checkout' === $_GET['tab'] && current_user_can( 'manage_options' ) ) {
		peachpay_express_checkout();
	}
}
add_action( 'admin_init', 'peachpay_settings_init' );

/**
 * Rendering function for the premium locked feature WordPress admin notice.
 */
function peachpay_display_premium_locked_notice() {
	?>
		<div id='peachpay-premium-locked-banner' class='notice notice-warning'>
			<p>
				<strong>
					<?php echo esc_html__( 'This is a Premium feature. Upgrade to unlock it!', 'peachpay-for-woocommerce' ); ?>
				</strong>
			</p>
			<button type="button" class="pp-button-continue-premium">
				<?php echo esc_html__( 'Get Premium', 'peachpay-for-woocommerce' ); ?>
				<?php require_once PeachPay::get_plugin_path() . 'core/admin/views/html-premium-modal.php'; ?>
			</button>
		</div>
	<?php
}

/**
 * Rendering function for the premium trial availability WordPress admin notice.
 */
function peachpay_display_premium_trial_notice() {
	update_option( 'peachpay_premium_trial_notice_shown', '1' );
	?>
	<div id='peachpay-premium-trial-notice' class='notice notice-warning'>
		<p>
			<strong>
				<?php echo esc_html__( 'Get PeachPay Premium for Express Checkout and other additional features!', 'peachpay-for-woocommerce' ); ?>
			</strong>
		</p>
		<div class="pp-banner-buttons">
			<button type="button" class="pp-button-continue-premium">
				<?php echo esc_html__( 'Get Premium', 'peachpay-for-woocommerce' ); ?>
				<?php require_once PeachPay::get_plugin_path() . 'core/admin/views/html-premium-modal.php'; ?>
			</button>
			<button type="button" class="pp-notice-dismiss notice-dismiss"></button>
		</div>
	</div>
	<?php
}

/**
 * Rendering function for the premium trial ending WordPress admin notice.
 */
function peachpay_display_premium_trial_ending_notice() {
	$premium_capability_config = PeachPay_Capabilities::get( 'woocommerce_premium', 'config' );
	if ( ! isset( $premium_capability_config['trialEnd'] ) ) {
		return;
	}

	$now       = new DateTime();
	$trial_end = ( new DateTime() )->setTimestamp( $premium_capability_config['trialEnd'] );
	$days_left = $now->diff( $trial_end )->days + 1;

	if ( $days_left <= 4 ) {
		?>
			<div id='peachpay-premium-trial-ending-notice' class='notice notice-warning'>
				<p>
					<strong>
						<?php
							// translators: %d: days left in trial, %s: trial end date
							echo esc_html( sprintf( __( 'Your free trial ends in %1$d day(s) on %2$s. Add a payment method to keep using PeachPay\'s premium features uninterrupted.', 'peachpay-for-woocommerce' ), $days_left, $trial_end->format( 'M d, Y' ) ) );
						?>
					</strong>
				</p>
				<div class="pp-banner-buttons">
					<button
						type='submit'
						form='peachpay-premium-subscription-portal-form'
						class='trial-end-action'
						>
						<?php echo esc_html_e( 'Add payment method', 'peachpay-for-woocommerce' ); ?>
					</button>
					<button type="button" class="pp-notice-dismiss notice-dismiss"></button>
				</div>
			</div>
		<?php
	}
}

/**
 * Disable all premium features while leaving configuration settings untouched.
 */
function peachpay_turn_off_premium_features() {
	peachpay_set_settings_option( 'peachpay_currency_options', 'enabled', false );
	peachpay_set_settings_option( 'peachpay_related_products_options', 'peachpay_related_enable', false );

	require_once PEACHPAY_ABSPATH . 'core/modules/field-editor/pp-field-editor-functions.php';
	// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch
	try {
		$field_editor_billing = get_option( 'peachpay_field_editor_billing' );
		if ( isset( $field_editor_billing['billing'] ) && is_array( $field_editor_billing['billing'] ) ) {
			foreach ( $field_editor_billing['billing'] as &$field ) {
				if ( ! peachpay_is_default_field( 'billing', $field['field_name'] ) ) {
					$field['field_enable'] = 'no';
				}
			}
			update_option( 'peachpay_field_editor_billing', $field_editor_billing );
		}
	} catch ( Exception $e ) {
		// Do no harm
	}

	try {
		$field_editor_shipping = get_option( 'peachpay_field_editor_shipping' );
		if ( isset( $field_editor_shipping['shipping'] ) && is_array( $field_editor_shipping['shipping'] ) ) {
			foreach ( $field_editor_shipping['shipping'] as &$field ) {
				if ( ! peachpay_is_default_field( 'shipping', $field['field_name'] ) ) {
					$field['field_enable'] = 'no';
				}
			}
			update_option( 'peachpay_field_editor_shipping', $field_editor_shipping );
		}
	} catch ( Exception $e ) {
		// Do no harm
	}

	try {
		$field_editor_additional = get_option( 'peachpay_field_editor_additional' );
		if ( isset( $field_editor_additional['additional'] ) && is_array( $field_editor_additional['additional'] ) ) {
			foreach ( $field_editor_additional['additional'] as &$field ) {
				$field['field_enable'] = 'no';
			}
			update_option( 'peachpay_field_editor_additional', $field_editor_additional );
		}
	} catch ( Exception $e ) {
		// Do no harm
	}
	// phpcs:enable Generic.CodeAnalysis.EmptyStatement.DetectedCatch
}

/**
 * Renders the settings page.
 */
function peachpay_options_page_html() {
	// Don't show the PeachPay settings to users who are not allowed to view
	// administration screens: https://wordpress.org/support/article/roles-and-capabilities/#read.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	PeachPay_Capabilities::refresh();

	do_action( 'peachpay_settings_admin_action' );

	$has_premium = PeachPay_Capabilities::connected( 'woocommerce_premium' );

	if ( ! $has_premium ) {
		peachpay_turn_off_premium_features();
	}

	// Show error/success messages.
	settings_errors( 'peachpay_messages' );

	//phpcs:ignore
	$section = isset( $_GET['section'] ) ? wp_unslash( $_GET['section'] ) : '';

	//phpcs:ignore
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'home';

	$premium_tab = in_array( $tab, array( 'currency', 'field', 'related_products', 'express_checkout' ), true );

	$has_hashed_tabs = ( 'payment' === $tab ) || ( 'express_checkout' === $tab && 'product_recommendations' === $section ) || ( 'express_checkout' === $tab && 'button' === $section );

	if ( isset( PeachPay_Onboarding_Tour::$onboarding_endpoints[ $tab ] ) ) {
		PeachPay_Onboarding_Tour::complete_section( $tab );
	} elseif ( isset( PeachPay_Onboarding_Tour::$onboarding_tab_translations[ $tab ] ) ) {
		PeachPay_Onboarding_Tour::complete_section( PeachPay_Onboarding_Tour::$onboarding_tab_translations[ $tab ] );
	}
	PeachPay_Onboarding_Tour::display_onboarding_tour( ! $has_premium );

	?>
	<div class="peachpay peachpay-container">
		<?php
		require PeachPay::get_plugin_path() . '/core/admin/views/html-primary-navigation.php';
		?>
		<div class="pp-admin-content-wrapper <?php echo esc_attr( $has_hashed_tabs ? 'has-hashed-tabs' : '' ); ?>">
			<?php
			require PeachPay::get_plugin_path() . '/core/admin/views/html-side-navigation.php';

			?>
			<div class="pp-admin-content">
				<?php
				if ( ! $has_premium && ! $premium_tab && false === get_option( 'peachpay_premium_trial_notice_shown', false ) ) {
					peachpay_display_premium_trial_notice();
				} else {
					update_option( 'peachpay_premium_trial_notice_shown', true );
				}
				if ( ! $has_premium && $premium_tab ) {
					peachpay_display_premium_locked_notice();
				}
				if ( $has_premium ) {
					peachpay_display_premium_trial_ending_notice();
				}
				if ( 'home' === $tab ) {
					peachpay_render_settings_homepage();
				} else {
					?>
					<form action="options.php" method="post">
						<div class="wrap">
							<div id='peachpay_settings_container' class="
								<?php
								if ( ! $has_premium && $premium_tab ) {
									echo esc_html( 'peachpay_premium_locked_settings ' );
								}
								if ( 'currency' === $tab ) {
									echo esc_html( 'peachpay_settings_container_currency' );
								}
								if ( 'field' === $tab ) {
									echo esc_html( 'peachpay_settings_container_field_editor' );
								}
								if ( 'express_checkout' === $tab ) {
									echo esc_html( 'peachpay_settings_container_express_checkout' );
								}
								?>
							">
								<?php
								// Output security fields for the registered setting "peachpay".
								// phpcs:ignore
								if ( isset( $_GET['section'] ) ) {
									settings_fields( 'peachpay_' . ( 'field' === $tab ? 'field_editor' : $tab ) . '_' . $section );
								} else {
									settings_fields( 'peachpay_' . $tab );
								}

								// Output setting sections and their fields
								// (sections are registered for "peachpay", each field is registered to a specific section).
								do_settings_sections( 'peachpay' );
								?>
								<div class="peachpay-notices-container"></div>
							</div>
						</div>
					</form>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}


/**
 * A helper function that generate href for nav tab use.
 *
 * @param array $array_query_arg This is an array of query arguments that is to be inserted.
 */
function peachpay_href_url_builder( $array_query_arg ) {
	return add_query_arg( $array_query_arg, admin_url( 'admin.php?page=peachpay' ) );
}

/**
 * Renders a PeachPay admin input.
 *
 * @param string $id The id of the input element.
 * @param string $option_group The group setting key the option is for.
 * @param string $option_key The specific key for setting in the option group.
 * @param mixed  $default The default value for the settings.
 * @param string $title The title of the setting.
 * @param string $description The description for the setting.
 * @param array  $options Extra configuration options.
 */
function peachpay_admin_input( $id, $option_group, $option_key, $default, $title, $description, $options = array( 'input_type' => 'text' ) ) {

	$name_attr       = $option_group . '[' . $option_key . ']';
	$value_attr      = peachpay_get_settings_option( $option_group, $option_key, $default );
	$input_type_attr = $options['input_type'];

	$disabled_value = array_key_exists( 'disabled', $options ) ? $options['disabled'] : 0;
	$placeholder    = array_key_exists( 'placeholder', $options ) ? $options['placeholder'] : '';

	?>
	<div class="pp-admin-input">
		<?php if ( 'text' === $input_type_attr ) { ?>
			<h4><?php echo esc_html( $title ); ?></h4>
			<input
				id="<?php echo esc_attr( $id ); ?>"
				name="<?php echo esc_attr( $name_attr ); ?>"
				type="<?php echo esc_attr( $input_type_attr ); ?>"
				value="<?php echo esc_attr( $value_attr ); ?>"
				class="pp-text-box"
				style='width: 300px'
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				<?php disabled( 1, $disabled_value, true ); ?>
			>
				<p class="description">
					<?php echo esc_html( $description ); ?>
				</p>
		<?php } elseif ( 'textarea' === $input_type_attr ) { ?>
			<h4><?php echo esc_html( $title ); ?></h4>
			<textarea
				id="<?php echo esc_attr( $id ); ?>"
				name="<?php echo esc_attr( $name_attr ); ?>"
				type="<?php echo esc_attr( $input_type_attr ); ?>"
				class="pp-text-box"
				style='width: 300px; min-height: 100px; resize: auto;'
				<?php disabled( 1, $disabled_value, true ); ?>
			><?php echo esc_attr( $value_attr ); ?></textarea>
			<p class="description">
			<?php echo esc_html( $description ); ?>
			</p>
		<?php } elseif ( 'checkbox' === $input_type_attr ) { ?>
			<!-- code or function call to code to render checkbox -->
			<div class="pp-switch-section">
				<div>
					<label class="pp-switch">
						<input
							id="<?php echo esc_attr( $id ); ?>"
							name="<?php echo esc_attr( $name_attr ); ?>"
							type="<?php echo esc_attr( $input_type_attr ); ?>"
							value="1"
							<?php checked( $default, peachpay_get_settings_option( $option_group, $option_key ), true ); ?>
							<?php disabled( 1, $disabled_value, true ); ?>
						>
						<span class="pp-slider round"></span>
					</label>
				</div>
				<div style="pointer-events: none;">
					<label class="pp-setting-label" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
					<p class="description"><?php echo esc_html( $description ); ?></p>
				</div>
			</div>
			<?php
		} elseif ( 'select' === $input_type_attr ) {
			/**
			 * Select box admin input template.
			 *
			 * Custom options:
			 *  options = array(
			 *      "input_type"     => "select",
			 *      "select_options" => Array("value" => "label", ...)
			 *  );
			 */
			$select_options = ( isset( $options['select_options'] ) && is_array( $options['select_options'] ) ) ? $options['select_options'] : array();
			?>

			<h4 style="margin: 0 0 8px 0;"><?php echo esc_html( $title ); ?></h4>
			<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name_attr ); ?>" style='min-width: 300px'>
				<?php foreach ( $select_options as $value => $label ) { ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, peachpay_get_settings_option( $option_group, $option_key, $default ), true ); ?>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
			<p><?php echo esc_html( $description ); ?></p>
			<?php
		} elseif ( 'number' === $input_type_attr ) {
			?>
			<h4 style="margin: 0px 0px 8px 0px"><?php echo esc_html( $title ); ?></h4>
			<input
				id="<?php echo esc_attr( $id ); ?>"
				name="<?php echo esc_attr( $name_attr ); ?>"
				type="number"
				style="width: 5rem"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				value="<?php echo esc_attr( peachpay_get_settings_option( $option_group, $option_key ) ); ?>"
			>
			<?php
		} else {
			?>
			<!-- ... other inputs -->
		<?php } ?>
		</div>
	<?php
}

// List of URL query args that can be dropped after being consumed.
define(
	'PEACHPAY_REMOVABLE_QUERY_ARGS',
	array(
		'connected_stripe',
		'unlink_stripe',

		'connected_paypal',
		'unlink_paypal',

		'connected_square',
		'unlink_square',

		'link_poynt',
		'unlink_poynt',

		'link_authnet',
		'unlink_authnet',

		'onboarding',
		'dismiss-service-fee-notice',
		'dismiss-tos-notice',
	)
);

add_filter( 'removable_query_args', 'peachpay_removable_query_args' );

/**
 * Adds various peachpay specific removable query args to the removable query arg array.
 *
 * @param array $removable_query_args WordPress removable query args.
 */
function peachpay_removable_query_args( $removable_query_args ) {
	return array_merge( $removable_query_args, PEACHPAY_REMOVABLE_QUERY_ARGS );
}

add_filter( 'wp_redirect', 'peachpay_reformat_url', 10, 2 );

/**
 * WP redirect filter will call this to drop any url parameters needed.
 *
 * @param string $url original redirect url.
 */
function peachpay_reformat_url( $url ) {
	return remove_query_arg( PEACHPAY_REMOVABLE_QUERY_ARGS, $url );
}
