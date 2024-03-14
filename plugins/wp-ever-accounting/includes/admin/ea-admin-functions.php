<?php
/**
 * EverAccounting Admin Functions.
 *
 * @since      1.0.2
 * @subpackage Admin
 * @package    EverAccounting
 */

defined( 'ABSPATH' ) || exit();

/**
 * Get settings tabs.
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_settings_tabs() {
	static $tabs = false;

	if ( false !== $tabs ) {
		return $tabs;
	}

	$tabs = array(
		'general'    => __( 'General', 'wp-ever-accounting' ),
		'currencies' => __( 'Currencies', 'wp-ever-accounting' ),
		'categories' => __( 'Categories', 'wp-ever-accounting' ),
		'extensions' => __( 'Extensions', 'wp-ever-accounting' ),
		'licenses'   => __( 'Licenses', 'wp-ever-accounting' ),
	);

	if ( ! has_filter( 'eaccounting_settings_sections_extensions' ) ) {
		unset( $tabs['extensions'] );
	}

	if ( ! has_filter( 'eaccounting_settings_licenses' ) ) {
		unset( $tabs['licenses'] );
	}

	return apply_filters( 'eaccounting_settings_tabs', $tabs );
}


/**
 * Get the settings sections for each tab
 * Uses a static to avoid running the filters on every request to this function
 *
 * @return array Array of tabs and sections
 * @since  1.1.0
 */
function eaccounting_get_settings_sections() {
	static $sections = false;

	if ( false !== $sections ) {
		return $sections;
	}

	$sections = array(
		'general'    => apply_filters(
			'eaccounting_settings_sections_general',
			array(
				'main'     => __( 'General', 'wp-ever-accounting' ),
				'invoices' => __( 'Invoices', 'wp-ever-accounting' ),
				'bills'    => __( 'Bills', 'wp-ever-accounting' ),
			)
		),
		'extensions' => apply_filters(
			'eaccounting_settings_sections_extensions',
			array()
		),
		'licenses'   => apply_filters(
			'eaccounting_settings_sections_licenses',
			array()
		),
	);

	if ( eaccounting_tax_enabled() ) {
		$sections['general']['taxes'] = __( 'Taxes', 'wp-ever-accounting' );
	}

	$sections = apply_filters( 'eaccounting_settings_sections', $sections );

	return $sections;
}

/**
 * Retrieve settings tabs
 *
 * @param bool $tab Tab to retrieve.
 *
 * @return array $section
 * @since 1.1.0
 */
function eaccounting_get_settings_tab_sections( $tab = false ) {
	$tabs     = array();
	$sections = eaccounting_get_settings_sections();
	if ( $tab && ! empty( $sections[ $tab ] ) ) {
		$tabs = $sections[ $tab ];
	} elseif ( $tab ) {
		$tabs = array();
	}

	return $tabs;
}


/**
 * Get all EverAccounting screen ids.
 *
 * @return array
 * @since  1.0.2
 */
function eaccounting_get_screen_ids() {
	$eaccounting_screen_id = sanitize_title( __( 'Accounting', 'wp-ever-accounting' ) );

	$screen_ids = array(
		'toplevel_page_' . $eaccounting_screen_id,
		$eaccounting_screen_id . '_page_ea-transactions',
		$eaccounting_screen_id . '_page_ea-sales',
		$eaccounting_screen_id . '_page_ea-expenses',
		$eaccounting_screen_id . '_page_ea-misc',
		$eaccounting_screen_id . '_page_ea-banking',
		$eaccounting_screen_id . '_page_ea-items',
		$eaccounting_screen_id . '_page_ea-reports',
		$eaccounting_screen_id . '_page_ea-tools',
		$eaccounting_screen_id . '_page_ea-settings',
		$eaccounting_screen_id . '_page_ea-extensions',
		'toplevel_page_eaccounting',
	);

	return apply_filters( 'eaccounting_screen_ids', $screen_ids );
}

/**
 * Check current page if admin page.
 *
 * @param string $page Page name.
 *
 * @return mixed|void
 * @since 1.0.2
 */
function eaccounting_is_admin_page( $page = '' ) {
	if ( ! is_admin() || ! did_action( 'wp_loaded' ) ) {
		$ret = false;
	}
	$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
	if ( empty( $page ) && ! empty( $current_page ) ) {
		$page = eaccounting_clean( $current_page );
	} else {
		$ret = false;
	}
	// When translate the page name becomes different so use translated.
	$eaccounting_screen_id = sanitize_title( esc_html__( 'Accounting', 'wp-ever-accounting' ) );
	$pages                 = str_replace(
		array(
			'toplevel_page_',
			'accounting_page_',
			$eaccounting_screen_id . '_page_',
		),
		'',
		eaccounting_get_screen_ids()
	);

	if ( ! empty( $page ) && in_array( $page, $pages, true ) ) {
		$ret = true;
	} else {
		$ret = in_array( $page, $pages, true );
	}

	return apply_filters( 'eaccounting_is_admin_page', $ret );
}

/**
 * Generates an EverAccounting admin URL based on the given type.
 *
 * @param array  $query_args Optional. Query arguments to append to the admin URL. Default empty array.
 * @param string $page Optional Type of admin URL. Accepts 'transactions', 'sales', 'purchases', 'banking', 'reports', 'settings', 'tools', 'add-ons'.
 *
 * @return string Constructed admin URL.
 * @since 1.0.2
 */
function eaccounting_admin_url( $query_args = array(), $page = null ) {
	if ( null === $page ) {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
	}

	// When translate the page name becomes different so use translated.
	$eaccounting_screen_id = sanitize_title( esc_html__( 'Accounting', 'wp-ever-accounting' ) );
	$whitelist             = str_replace(
		array(
			'toplevel_page_',
			'accounting_page_',
			$eaccounting_screen_id . '_page_',
		),
		'',
		eaccounting_get_screen_ids()
	);

	if ( ! in_array( $page, $whitelist, true ) ) {
		$page = '';
	}

	$admin_query_args = array_merge( array( 'page' => $page ), $query_args );

	$url = add_query_arg( $admin_query_args, admin_url( 'admin.php' ) );

	/**
	 * Filters the EverAccounting admin URL.
	 *
	 * @param array $query_args Query arguments originally passed to eaccounting_admin_url().
	 *
	 * @param string $url Admin URL.
	 *
	 * @param string $type Admin URL type.
	 *
	 * @since 1.0.2
	 */
	return apply_filters( 'eaccounting_admin_url', $url, $page, $query_args );
}

/**
 * Get activate tab.
 *
 * @param array $tabs Tabs array.
 * @param null  $default Default tab.
 *
 * @return array|mixed|string
 * @since 1.0.2
 */
function eaccounting_get_active_tab( $tabs, $default = null ) {
	$tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
	if ( ! empty( $tab ) && array_key_exists( $tab, $tabs, true ) ) {
		$active_tab = eaccounting_clean( $tab );
	} elseif ( ! empty( $default ) ) {
		$active_tab = $default;
	} else {
		$array      = array_keys( $tabs );
		$active_tab = reset( $array );
	}

	return $active_tab;
}

/**
 * Outputs navigation tabs markup in core screens.
 *
 * @param array  $tabs Navigation tabs.
 * @param string $active_tab Active tab slug.
 * @param array  $query_args Optional. Query arguments used to build the tab URLs. Default empty array.
 * @param string $tab Optional. Tab to highlight. Default empty string.
 * @since 1.0.2
 * @since 1.1.0 add $tab argument.
 */
function eaccounting_navigation_tabs( $tabs, $active_tab, $query_args = array(), $tab = 'tab' ) {
	$tabs = (array) $tabs;

	if ( empty( $tabs ) ) {
		return;
	}

	$tabs = apply_filters( 'eaccounting_navigation_tabs', $tabs, $active_tab, $query_args );

	foreach ( $tabs as $tab_id => $tab_name ) {
		$args    = wp_parse_args( $query_args, array( $tab => $tab_id ) );
		$tab_url = eaccounting_admin_url( $args );
		printf(
			'<a href="%1$s" alt="%2$s" class="%3$s">%4$s</a>',
			esc_url( $tab_url ),
			esc_attr( $tab_name ),
			$active_tab === $tab_id ? 'nav-tab nav-tab-active' : 'nav-tab',
			esc_html( $tab_name )
		);
	}

	do_action( 'eaccounting_after_navigation_tabs', $tabs, $active_tab, $query_args );
}

/**
 * Get current tab.
 *
 * @param string $tab Tab name.
 *
 * @return array|string
 * @since 1.0.2
 * @since 1.1.0 add $tab argument.
 */
function eaccounting_get_current_tab( $tab = 'tab' ) {
	return ( isset( $tab ) ) ? eaccounting_clean( $tab ) : '';
}

/**
 * Per page screen option value for the Affiliates list table
 *
 * @param bool|int $status The status of the screen option.
 * @param string   $option The screen option name.
 * @param mixed    $value The current screen option value.
 *
 * @return mixed
 * @since  1.0.2
 */
function eaccounting_accounts_set_screen_option( $status, $option, $value ) {
	if ( 'eaccounting_edit_accounts_per_page' === $option ) {
		return $value;
	}

	return $status;

}

add_filter( 'set-screen-option', 'eaccounting_accounts_set_screen_option', 10, 3 );


/**
 * Get import export headers.
 *
 * @param string $type Type of headers.
 *
 * @return mixed|void
 * @since 1.0.2
 */
function eaccounting_get_io_headers( $type ) {
	$headers = array();
	switch ( $type ) {
		case 'customer':
		case 'vendor':
			$headers = array(
				'name'          => 'Name',
				'company'       => 'Company',
				'email'         => 'Email',
				'phone'         => 'Phone',
				'birth_date'    => 'Birth Date',
				'street'        => 'Street',
				'city'          => 'City',
				'state'         => 'State',
				'postcode'      => 'Postcode',
				'country'       => 'Country',
				'website'       => 'Website',
				'vat_number'    => 'Vat Number',
				'currency_code' => 'Currency Code',
			);
			break;
		case 'category':
			$headers = array(
				'name'  => 'Name',
				'type'  => 'Type',
				'color' => 'Color',
			);
			break;
		case 'account':
			$headers = array(
				'name'            => 'Name',
				'number'          => 'Number',
				'currency_code'   => 'Currency Code',
				'opening_balance' => 'Opening Balance',
				'bank_name'       => 'Bank Name',
				'bank_phone'      => 'Bank Phone',
				'bank_address'    => 'Bank Address',
				'enabled'         => 'Enabled',
			);
			break;
		case 'payment':
			$headers = array(
				'payment_date'   => 'Payment Date',
				'amount'         => 'Amount',
				'currency_code'  => 'Currency Code',
				'currency_rate'  => 'Currency Rate',
				'account_name'   => 'Account Name',
				'vendor_name'    => 'Vendor Name',
				'category_name'  => 'Category Name',
				'description'    => 'Description',
				'payment_method' => 'Payment Method',
				'reference'      => 'Reference',
				'reconciled'     => 'Reconciled',
			);
			break;
		case 'revenue':
			$headers = array(
				'payment_date'   => 'Payment Date',
				'amount'         => 'Amount',
				'currency_code'  => 'Currency Code',
				'currency_rate'  => 'Currency Rate',
				'account_name'   => 'Account Name',
				'customer_name'  => 'Customer Name',
				'category_name'  => 'Category Name',
				'description'    => 'Description',
				'payment_method' => 'Payment Method',
				'reference'      => 'Reference',
			);
			break;
		case 'currency':
			$headers = array(
				'name'               => 'Name',
				'code'               => 'Code',
				'rate'               => 'Rate',
				'precision'          => 'Precision',
				'symbol'             => 'Symbol',
				'position'           => 'Position',
				'decimal_separator'  => 'Decimal Separator',
				'thousand_separator' => 'Thousand Separator',
			);
			break;
		case 'item':
			$headers = array(
				'name'           => 'Name',
				'category_name'  => 'Category',
				'sale_price'     => 'Sale Price',
				'purchase_price' => 'Purchase Price',
				'sales_tax'      => 'Sales Tax',
				'purchase_tax'   => 'Purchase Tax',
			);
			break;

		default:
			break;
	}

	return apply_filters( 'eaccounting_get_io_headers_' . $type, $headers );
}

/**
 * Render the importer mapping table.
 *
 * @param string $type Type of importer.
 *
 * @since 1.0.2
 */
function eaccounting_do_import_fields( $type ) {
	$fields = eaccounting_get_io_headers( $type );

	if ( ! empty( $fields ) ) {

		foreach ( $fields as $key => $label ) {
			?>
			<tr>
				<td><?php echo esc_html( $label ); ?></td>
				<td>
					<select name="mapping[<?php echo esc_attr( $key ); ?>]" class="ea-importer-map-column">
						<option value=""><?php esc_html_e( '- Do not import -', 'wp-ever-accounting' ); ?></option>
					</select>
				</td>
				<td class="ea-importer-preview-field"><?php esc_html_e( '- Select field to preview data -', 'wp-ever-accounting' ); ?></td>
			</tr>
			<?php
		}
	}
}


/**
 * Meta-Box template function.
 *
 * @param  string $screen Screen name.
 * @param string $context Context.
 * @param $object $object Object.
 * @global array $wp_meta_boxes
 */
function eaccounting_do_meta_boxes( $screen, $context, $object ) {
	global $wp_meta_boxes;
	if ( empty( $screen ) ) {
		$screen = get_current_screen();
	} elseif ( is_string( $screen ) ) {
		$screen = convert_to_screen( $screen );
	}
	$page = $screen->id;
	if ( isset( $wp_meta_boxes[ $page ][ $context ] ) ) {
		foreach ( array( 'high', 'sorted', 'core', 'default', 'low' ) as $priority ) {
			if ( isset( $wp_meta_boxes[ $page ][ $context ][ $priority ] ) ) {
				foreach ( (array) $wp_meta_boxes[ $page ][ $context ][ $priority ] as $box ) {
					$args         = wp_parse_args( $box['args'], array( 'col' => '' ) );
					$col          = ! empty( $args['col'] ) ? "ea-col-{$args['col']}" : '';
					$custom_class = isset( $args['class'] ) ? wp_parse_list( $args['class'] ) : array();
					$classes      = implode( ' ', array_map( 'sanitize_html_class', $custom_class ) );
					?>
						<div id="id-<?php echo esc_attr( $box['id'] ); ?>" class="ea-metabox <?php echo esc_attr( $col ); ?>" >
							<div class="ea-card <?php echo esc_attr( $classes ); ?>" >
								<?php if ( ! empty( $box['title'] ) ) : ?>
									<div class="ea-card__header">
										<h3 class="ea-card__title"><?php echo esc_html( $box['title'] ); ?></h3>
										<?php if ( isset( $args['toolbar_callback'] ) && is_callable( $args['toolbar_callback'] ) ) : ?>
											<div class="ea-card__toolbar">
												<?php call_user_func( $box['toolbar_callback'], $object, $box ); ?>
											</div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php call_user_func( $box['callback'], $object, $box ); ?>
							</div>
						</div>
					<?php
				}
			}
		}
	}
}

/**
 * Get report years.
 *
 * @return array
 * @since 1.1.0
 */
function eaccounting_get_report_years() {
	$years = range( wp_date( 'Y' ), ( wp_date( 'Y' ) - 10 ), 1 );

	return array_combine( array_values( $years ), $years );
}

/**
 * Get month of years
 *
 * return array
 *
 * @since 1.1.2
 */
function eaccounting_get_months() {
	$months = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	);

	return array_combine( array_values( $months ), $months );
}
