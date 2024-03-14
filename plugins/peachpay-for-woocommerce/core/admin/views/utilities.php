<?php
/**
 * Helper functions for rendering parts of the view.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

if ( function_exists( 'peachpay_generate_top_nav_link' ) ) {
	// Since the view templates can be included multiple times, if one of these
	// functions has already been defined, then we return to avoid an error.
	return;
}

/**
 * Returns the current top navigation tab that should be highlighted.
 */
function peachpay_get_current_nav_top_tab() {
	if ( peachpay_nav_is_analytics_page() ) {
		return 'analytics';
	}
	if ( peachpay_nav_is_gateway_page() ) {
		return 'settings';
	}
	if ( ! peachpay_nav_is_peachpay_page() ) {
		return '';
	}
	// PHPCS:ignore
	if ( ! isset( $_GET['tab'] ) || isset( $_GET['tab'] ) && 'home' === $_GET['tab'] ) {
		return 'dashboard';
	}
	// PHPCS:ignore
	if ( peachpay_nav_is_account_page() ) {
		return 'account';
	}

	return 'settings';
}

/**
 * Returns the key of the tab that should be active in the navigation.
 */
function peachpay_get_current_nav_tab() {
	if ( peachpay_nav_is_peachpay_page() ) {
		// PHPCS:ignore
		return isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'home';
	}
	if ( peachpay_nav_is_analytics_page() ) {
		// PHPCS:ignore
		return isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'payment_methods';
	}
	if ( peachpay_nav_is_gateway_page() ) {
		// PHPCS:ignore
		return '';
	}
	return '';
}

/**
 * Returns the key of the section that should be active in the navigation.
 */
function peachpay_get_current_nav_section() {
	// PHPCS:ignore
	return isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
}

/**
 * Returns the current section in the tab if applicable.
 */
function peachpay_get_current_nav_sub_tab() {
	$current_tab = peachpay_get_current_nav_tab();
	if ( 'field' === $current_tab ) {
		// PHPCS:ignore
		return isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'billing';
	}
	if ( 'express_checkout' === $current_tab ) {
		// PHPCS:ignore
		return isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : 'branding';
	}
	return '';
}

/**
 * Returns true if this is a PeachPay settings page (but not a gateway page).
 */
function peachpay_nav_is_peachpay_page() {
	// PHPCS:ignore
	return isset( $_GET['page'] ) && ( 'peachpay' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) );
}

/**
 * Returns true if this is the PeachPay analytics page.
 */
function peachpay_nav_is_analytics_page() {
	// PHPCS:ignore
	return isset( $_GET['page'] ) && ( 'peachpay' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) && isset( $_GET['section'] ) && ( 'analytics' === sanitize_text_field( wp_unslash( $_GET['section'] ) ) );
}

/**
 * Returns true if this is the PeachPay Account Settings page.
 */
function peachpay_nav_is_account_page() {
	// PHPCS:ignore
	return isset( $_GET['page'] ) && ( 'peachpay' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) && isset( $_GET['section'] ) && ( 'account' === sanitize_text_field( wp_unslash( $_GET['section'] ) ) );
}

/**
 * Returns true if this is a PeachPay gateway page.
 */
function peachpay_nav_is_gateway_page() {
	// PHPCS:ignore
	return isset( $_GET['page'] ) && ( 'wc-settings' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) && isset( $_GET['section'] ) && ( 'peachpay' === substr( sanitize_text_field( wp_unslash( $_GET['section'] ) ), 0, 8 ) );
}

/**
 * Returns true if the feature in this tab is active.
 *
 * @param string $section The navigation section key.
 *
 * @param string $tab The navigation tab key.
 */
function peachpay_nav_feature_is_active( $section, $tab ) {
	if ( peachpay_should_mark_premium( $section, $tab ) ) {
		return false;
	}

	switch ( $section ) {
		case 'address_autocomplete':
			return 'yes' === PeachPay_Address_Autocomplete_Settings::get_setting( 'enabled' );
	}

	switch ( $tab ) {
		case 'payment':
			return ! peachpay_is_test_mode();
		case 'currency':
			return peachpay_get_settings_option( 'peachpay_currency_options', 'enabled' );
		case 'field':
			return true;
		case 'related_products':
			return peachpay_get_settings_option( 'peachpay_related_products_options', 'peachpay_related_enable' );
		case 'bot_protection':
			return 'yes' === PeachPay_Bot_Protection_Settings::get_setting( 'enabled' );
		case 'express_checkout':
			return PeachPay_Express_Checkout::enabled();
		default:
			return false;
	}
}

/**
 * If the given tab has sub-tabs, generates the sub-tabs for the side navigation.
 *
 * @param string $tab The tab key.
 */
function peachpay_get_subtabs( $tab ) {
	$tabs_with_subtabs           = array(
		'field'            => array(
			'billing'    => 'Billing',
			'shipping'   => 'Shipping',
			'additional' => 'Additional',
		),
		'express_checkout' => array(
			'branding'                => 'Branding',
			'button'                  => 'Checkout button',
			'window'                  => 'Checkout window',
			'product_recommendations' => 'Product recommendations',
			'advanced'                => 'Advanced',
		),
	);
	$tabs_with_hashed_navigation = array(
		'payment' => array(
			'stripe'   => 'Stripe',
			'square'   => 'Square',
			'paypal'   => 'PayPal',
			'poynt'    => 'GoDaddy Poynt',
			'authnet'  => 'Authorize.net',
			'peachpay' => 'Purchase order',
		),
	);

	$sub_tabs_exist           = array_key_exists( $tab, $tabs_with_subtabs ) && is_array( $tabs_with_subtabs[ $tab ] );
	$hashed_navigation_exists = array_key_exists( $tab, $tabs_with_hashed_navigation ) && is_array( $tabs_with_hashed_navigation[ $tab ] );

	if ( ! $sub_tabs_exist && ! $hashed_navigation_exists ) {
		return;
	}
	?>
	<div class="nav-sub-tabs accordion-content">
		<?php
		if ( $sub_tabs_exist ) {
			foreach ( $tabs_with_subtabs[ $tab ] as $subtab => $title ) {
				peachpay_generate_subtab( $tab, $subtab, null, $title );
			}
		} elseif ( $hashed_navigation_exists ) {
			foreach ( $tabs_with_hashed_navigation[ $tab ] as $hash => $title ) {
				peachpay_generate_subtab( $tab, '', "#$hash", $title );
			}
		}
		?>
	</div>
	<?php
}

/**
 * Generates a sub-tab.
 *
 * @param string $tab    The tab key.
 * @param string $subtab The sub-tab key.
 * @param string $hash   The hash value.
 * @param string $title  the sub-tab title.
 */
function peachpay_generate_subtab( $tab, $subtab, $hash, $title ) {
	?>
		<a class="nav-sub-tab <?php echo esc_attr( $hash ? 'hashed' : ( peachpay_get_current_nav_sub_tab() === $subtab ? 'current' : '' ) ); ?>" href="<?php echo esc_url( PeachPay_Admin::admin_settings_url( 'peachpay', $tab, $subtab, $hash, false ) ); ?>">
			<?php peachpay_generate_nav_tab_title( $title ); ?>
		</a>
		<?php
}

/**
 * Generates a styled link on the top right of PeachPay settings header.
 *
 * @param string $key    A unique identifier for the link.
 * @param string $link   The url.
 * @param string $icon   The file name of the icon.
 * @param string $title  The text to display on the link.
 */
function peachpay_generate_top_nav_link( $key, $link, $icon, $title ) {
	?>
	<a class="top-nav-link <?php echo esc_attr( ( peachpay_get_current_nav_top_tab() === $key ) ? 'current' : '' ); ?> <?php echo esc_attr( $icon ); ?>-link" href="<?php echo esc_url( $link ); ?>"<?php echo ( ( 'Docs' === $title || 'Twitter' === $title ) ? ' target="_blank"' : '' ); ?>>
		<div class="icon <?php echo esc_attr( $icon ); ?>"></div>
		<?php peachpay_generate_nav_tab_title( $title ); ?>
		<?php if ( null !== $key ) { ?>
			<div class="icon chevron-down"></div>
		<?php } ?>
	</a>
	<?php
}

/**
 * Renders the dropdown for the top navigation.
 */
function peachpay_top_nav_dropdown() {
	?>
	<div class="dropdown accordion-content">
		<a class="<?php echo esc_attr( 'dashboard' === peachpay_get_current_nav_top_tab() ? 'current' : '' ); ?>" href="<?php echo esc_url( PeachPay_Admin::admin_settings_url( 'peachpay', 'home', '', '', false ) ); ?>">
			<div class="icon dashboard-icon"></div>
			<?php peachpay_generate_nav_tab_title( 'Dashboard' ); ?>
		</a>
		<a class="<?php echo esc_attr( 'settings' === peachpay_get_current_nav_top_tab() ? 'current' : '' ); ?>" href="<?php echo esc_url( PeachPay_Admin::admin_settings_url( 'peachpay', 'payment', '', '', false ) ); ?>">
			<div class="icon settings-icon"></div>
			<?php peachpay_generate_nav_tab_title( 'Settings' ); ?>
		</a>
		<a class="<?php echo esc_attr( 'analytics' === peachpay_get_current_nav_top_tab() ? 'current' : '' ); ?>" href="<?php echo esc_url( PeachPay_Admin::admin_settings_url( 'peachpay', 'payment_methods', 'analytics', '', false ) ); ?>">
			<div class="icon analytics-icon"></div>
			<?php peachpay_generate_nav_tab_title( 'Analytics' ); ?>
		</a>
		<a class="<?php echo esc_attr( 'account' === peachpay_get_current_nav_top_tab() ? 'current' : '' ); ?>" href="<?php echo esc_url( PeachPay_Admin::admin_settings_url( 'peachpay', 'data', 'account', '', false ) ); ?>">
			<div class="icon account-icon"></div>
			<?php peachpay_generate_nav_tab_title( 'My account' ); ?>
		</a>
	</div>
	<?php
}

/**
 * Generates a single navigation tab for the given tab and section.
 *
 * @param string $page        The page key.
 * @param string $tab         The tab key.
 * @param string $section     The section key.
 * @param string $title       The text to display on the tab.
 * @param string $has_subtabs If true, chevron down will be rendered.
 */
function peachpay_generate_nav_tab( $page, $tab, $section, $title, $has_subtabs = false ) {
	if ( $has_subtabs ) {
		?>
		<div class="tab-with-subtabs-container <?php echo esc_attr( peachpay_should_mark_premium( $section, $tab ) ? 'pp-popup-mousemove-trigger' : '' ); ?>">
			<div class="nav-tab has-subtabs accordion-tab <?php echo esc_attr( peachpay_is_currently_viewed( $section, $tab ) ? 'current expanded' : '' ); ?>">
				<div class="title">
					<div class="icon <?php echo esc_attr( get_class_name_for_icon( $section, $tab ) ); ?>-icon">
					</div>
					<?php if ( peachpay_nav_feature_is_active( $section, $tab ) ) { ?>
						<div class="active-status"></div>
					<?php } ?>
					<div class="flex-row"><?php peachpay_generate_nav_tab_title( $title ); ?></div>
					<?php peachpay_premium_crown( $section, $tab ); ?>
				</div>
				<?php if ( $has_subtabs ) { ?>
					<div class="icon chevron-down"></div>
				<?php } ?>
				<?php if ( peachpay_should_mark_premium( $section, $tab ) ) { ?>
					<div class="pp-popup pp-popup-right pp-tooltip-popup"> <?php esc_html_e( 'Premium Feature', 'peachpay-for-woocommerce' ); ?> </div>
				<?php } ?>
			</div>
			<?php peachpay_get_subtabs( $tab ); ?>
		</div>
	<?php } else { ?>
		<a class="nav-tab <?php echo esc_attr( peachpay_is_currently_viewed( $section, $tab ) ? 'current' : '' ); ?> <?php echo esc_attr( peachpay_should_mark_premium( $section, $tab ) ? 'pp-popup-mousemove-trigger' : '' ); ?>" href="<?php esc_url( PeachPay_Admin::admin_settings_url( $page, $tab, $section ) ); ?>">
			<div class="title">
				<div class="icon <?php echo esc_attr( get_class_name_for_icon( $section, $tab ) ); ?>-icon">
				</div>
				<?php if ( peachpay_nav_feature_is_active( $section, $tab ) ) { ?>
					<div class="active-status"></div>
				<?php } ?>
				<div class="flex-row"><?php peachpay_generate_nav_tab_title( $title ); ?></div>
				<?php peachpay_premium_crown( $section, $tab ); ?>
			</div>
			<?php if ( peachpay_should_mark_premium( $section, $tab ) ) { ?>
				<div class="pp-popup pp-popup-right pp-tooltip-popup"> <?php esc_html_e( 'Premium Feature', 'peachpay-for-woocommerce' ); ?> </div>
			<?php } ?>
		</a>
		<?php
	}
}

/**
 * Returns whether the tab/section is currently being viewed.
 *
 * @param string $section The navigation section key.
 *
 * @param string $tab The navigation tab key.
 */
function peachpay_is_currently_viewed( $section, $tab ) {
	$current_tab = peachpay_get_current_nav_tab();

	switch ( $tab ) {
		case 'payment':
		case 'currency':
		case 'field':
		case 'related_products':
		case 'bot_protection':
		case 'express_checkout':
			return $tab === $current_tab;
	}

	// some addons have the same tab name so we have to compare the section names for these
	$current_section = peachpay_get_current_nav_section();

	return $tab === $current_tab && $section === $current_section;
}


/**
 * Generates escaped and translated text for the navigation tab title.
 * This is a workaround for esc_html_e only accepting string literals.
 *
 * @param string $title The text to display on the tab.
 */
function peachpay_generate_nav_tab_title( $title ) {
	switch ( $title ) {
		case 'Home':
			echo esc_html_e( 'Home', 'peachpay-for-woocommerce' );
			break;
		case 'Payments':
			echo esc_html_e( 'Payments', 'peachpay-for-woocommerce' );
			break;
		case 'Currency':
			echo esc_html_e( 'Currency', 'peachpay-for-woocommerce' );
			break;
		case 'Field editor':
			echo esc_html_e( 'Field editor', 'peachpay-for-woocommerce' );
			break;
		case 'Related products':
			echo esc_html_e( 'Related products', 'peachpay-for-woocommerce' );
			break;
		case 'Bot protection':
			echo esc_html_e( 'Bot protection', 'peachpay-for-woocommerce' );
			break;
		case 'Express checkout':
			echo esc_html_e( 'Express checkout', 'peachpay-for-woocommerce' );
			break;
		case 'Billing':
			echo esc_html_e( 'Billing', 'peachpay-for-woocommerce' );
			break;
		case 'Shipping':
			echo esc_html_e( 'Shipping', 'peachpay-for-woocommerce' );
			break;
		case 'Additional':
			echo esc_html_e( 'Additional', 'peachpay-for-woocommerce' );
			break;
		case 'Branding':
			echo esc_html_e( 'Branding', 'peachpay-for-woocommerce' );
			break;
		case 'Checkout button':
			echo esc_html_e( 'Button', 'peachpay-for-woocommerce' );
			break;
		case 'Checkout window':
			echo esc_html_e( 'Window', 'peachpay-for-woocommerce' );
			break;
		case 'Address autocomplete':
			echo esc_html_e( 'Address autocomplete', 'peachpay-for-woocommerce' );
			break;
		case 'Product recommendations':
			echo esc_html_e( 'Product recommendations', 'peachpay-for-woocommerce' );
			break;
		case 'Advanced':
			echo esc_html_e( 'Advanced', 'peachpay-for-woocommerce' );
			break;
		case 'Docs':
			echo esc_html_e( 'Docs', 'peachpay-for-woocommerce' );
			break;
		case 'Support':
			echo esc_html_e( 'Support', 'peachpay-for-woocommerce' );
			break;
		case 'Analytics':
			echo esc_html_e( 'Analytics', 'peachpay-for-woocommerce' );
			break;
		case 'Billing':
			echo esc_html_e( 'Billing', 'peachpay-for-woocommerce' );
			break;
		case 'Shipping':
			echo esc_html_e( 'Shipping', 'peachpay-for-woocommerce' );
			break;
		case 'Additional':
			echo esc_html_e( 'Additional', 'peachpay-for-woocommerce' );
			break;
		case 'Branding':
			echo esc_html_e( 'Branding', 'peachpay-for-woocommerce' );
			break;
		case 'Checkout button':
			echo esc_html_e( 'Checkout button', 'peachpay-for-woocommerce' );
			break;
		case 'Checkout window':
			echo esc_html_e( 'Checkout window', 'peachpay-for-woocommerce' );
			break;
		case 'Product recommendations':
			echo esc_html_e( 'Product recommendations', 'peachpay-for-woocommerce' );
			break;
		case 'Advanced':
			echo esc_html_e( 'Advanced', 'peachpay-for-woocommerce' );
			break;
		case 'Settings':
			echo esc_html_e( 'Settings', 'peachpay-for-woocommerce' );
			break;
		case 'Dashboard':
			echo esc_html_e( 'Dashboard', 'peachpay-for-woocommerce' );
			break;
		case 'Twitter':
			echo esc_html_e( 'Twitter', 'peachpay-for-woocommerce' );
			break;
		case 'Payment methods':
			echo esc_html_e( 'Payment methods', 'peachpay-for-woocommerce' );
			break;
		case 'Device breakdown':
			echo esc_html_e( 'Device breakdown', 'peachpay-for-woocommerce' );
			break;
		case 'Abandoned carts':
			echo esc_html_e( 'Abandoned carts', 'peachpay-for-woocommerce' );
			break;
		case 'Analytics settings':
			echo esc_html_e( 'Analytics settings', 'peachpay-for-woocommerce' );
			break;
		case 'Stripe':
			echo esc_html_e( 'Stripe', 'peachpay-for-woocommerce' );
			break;
		case 'Square':
			echo esc_html_e( 'Square', 'peachpay-for-woocommerce' );
			break;
		case 'PayPal':
			echo esc_html_e( 'PayPal', 'peachpay-for-woocommerce' );
			break;
		case 'GoDaddy Poynt':
			echo esc_html_e( 'GoDaddy Poynt', 'peachpay-for-woocommerce' );
			break;
		case 'Authorize.net':
			echo esc_html_e( 'Authorize.net', 'peachpay-for-woocommerce' );
			break;
		case 'Purchase order':
			echo esc_html_e( 'Purchase Order', 'peachpay-for-woocommerce' );
			break;
		case 'My account':
			echo esc_html_e( 'My account', 'peachpay-for-woocommerce' );
			break;
		case 'Data':
			echo esc_html_e( 'Data', 'peachpay-for-woocommerce' );
			break;
		case 'Region':
			echo esc_html_e( 'Region', 'peachpay-for-woocommerce' );
			break;
	}
}

/**
 * Returns what the class should be for the icon display .
 *
 * @param string $section The navigation section key.
 *
 * @param string $tab The navigation tab key.
 */
function get_class_name_for_icon( $section, $tab ) {
	if ( 'address_autocomplete' === $section ) {
		return str_replace( '_', '-', $section );
	} else {
		return str_replace( '_', '-', $tab );
	}
}

/**
 * Returns true if the feature located at the given tab needs to be marked as premium.
 *
 * @param string $section The navigation section key.
 *
 * @param string $tab The navigation tab key.
 */
function peachpay_should_mark_premium( $section, $tab ) {
	$not_premium = ! PeachPay::has_premium();

	if ( $not_premium && 'address_autocomplete' === $section ) {
		return true;
	}

	return $not_premium && in_array(
		$tab,
		array(
			'currency',
			'field',
			'related_products',
			'express_checkout',
		),
		true
	);
}

/**
 * Renders the peachpay premium misc link code.
 */
function peachpay_premium_misc_link() {
	//phpcs:ignore
	if ( ! isset( $_GET['page'] ) && strpos( $_GET['page'], 'peachpay' ) ) {
		return;
	}

	$premium_config = PeachPay_Capabilities::get( 'woocommerce_premium', 'config' );
	if ( $premium_config && isset( $premium_config['bypass'] ) ) {
		return;
	}

	if ( PeachPay::has_premium() ) {
		?>
			<button
				type='submit'
				form='peachpay-premium-subscription-portal-form'
				class='button-to-anchor top-nav-link <?php echo esc_attr( 'crown-icon' ); ?>-link'
			>
				<div class="icon <?php echo esc_attr( 'crown-icon' ); ?>"></div>
				<?php
					echo esc_html_e( 'Premium Portal', 'peachpay-for-woocommerce' );
				?>
			</button>
		<?php
		require_once PeachPay::get_plugin_path() . '/core/admin/views/html-premium-portal.php';
	} else {
		?>
				<button type="button" class="pp-button-continue-premium pp-button-premium button-to-anchor top-nav-link <?php echo esc_attr( 'crown-icon' ); ?>-link'">
					<div class="icon <?php echo esc_attr( 'crown-icon' ); ?>"></div>
				<?php echo esc_html_e( 'Get Premium', 'peachpay-for-woocommerce' ); ?>
				</button>
			<?php
			require_once PeachPay::get_plugin_path() . 'core/admin/views/html-premium-modal.php';
	}
}

/**
 * Renders the premium crown if the feature located at the given tab needs to be marked as premium.
 *
 * @param string $section The navigation section key.
 *
 * @param string $tab The navigation tab key.
 */
function peachpay_premium_crown( $section, $tab ) {
	if ( peachpay_should_mark_premium( $section, $tab ) ) {
		?>
		<div class="icon crown-icon"></div>
		<?php
	}
}
