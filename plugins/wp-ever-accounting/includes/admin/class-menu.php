<?php
/**
 * Handles admin related menus.
 *
 * @package EverAccounting
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Menu
 *
 * @package EverAccounting\Admin
 */
class Menu {

	/**
	 * Menu constructor.
	 */
	public function __construct() {
		// Register menus.
		add_action( 'admin_menu', array( $this, 'register_parent_page' ), 1 );
		add_action( 'admin_menu', array( $this, 'register_items_page' ), 20 );
		add_action( 'admin_menu', array( $this, 'register_sales_page' ), 30 );
		add_action( 'admin_menu', array( $this, 'register_expenses_page' ), 40 );
		add_action( 'admin_menu', array( $this, 'register_banking_page' ), 50 );
		add_action( 'admin_menu', array( $this, 'register_tools_page' ), 70 );
		add_action( 'admin_menu', array( $this, 'register_reports_page' ), 80 );

		// Register tabs.
		add_action( 'eaccounting_items_page_tab_items', array( $this, 'render_items_tab' ), 20 );
		add_action( 'eaccounting_sales_page_tab_revenues', array( $this, 'render_revenues_tab' ) );
		add_action( 'eaccounting_sales_page_tab_invoices', array( $this, 'render_invoices_tab' ), 20 );
		add_action( 'eaccounting_sales_page_tab_customers', array( $this, 'render_customers_tab' ) );
		add_action( 'eaccounting_expenses_page_tab_payments', array( $this, 'render_payments_tab' ) );
		add_action( 'eaccounting_expenses_page_tab_bills', array( $this, 'render_bills_tab' ), 20 );
		add_action( 'eaccounting_expenses_page_tab_vendors', array( $this, 'render_vendors_tab' ) );
		add_action( 'eaccounting_banking_page_tab_accounts', array( $this, 'render_accounts_tab' ) );
		add_action( 'eaccounting_banking_page_tab_transactions', array( $this, 'render_transactions_tab' ), 20 );
		add_action( 'eaccounting_banking_page_tab_transfers', array( $this, 'render_transfers_tab' ) );
		add_action( 'eaccounting_tools_page_tab_export', array( $this, 'render_export_page' ), 20 );
		add_action( 'eaccounting_tools_page_tab_import', array( $this, 'render_import_page' ), 20 );
		add_action( 'eaccounting_reports_tab_sales', array( $this, 'render_sales_report_tab' ) );
		add_action( 'eaccounting_reports_tab_expenses', array( $this, 'render_expenses_report_tab' ) );
		add_action( 'eaccounting_reports_tab_profits', array( $this, 'render_profits_report_tab' ) );
		add_action( 'eaccounting_reports_tab_cashflow', array( $this, 'render_cashflow_report_tab' ) );
		add_filter( 'eaccounting_settings_tabs', array( $this, 'add_setting_tabs' ) );
		add_action( 'eaccounting_settings_tab_currencies', array( $this, 'render_currencies_tab' ) );
		add_action( 'eaccounting_settings_tab_categories', array( $this, 'render_categories_tab' ) );
	}

	/**
	 * Registers the overview page.
	 *
	 * @since 1.1.0
	 */
	public function register_parent_page() {
		global $menu;

		if ( current_user_can( 'manage_eaccounting' ) ) {
			$menu[] = array( '', 'read', 'ea-separator', '', 'wp-menu-separator accounting' );
		}
		$icons = 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( eaccounting()->plugin_path( 'dist/images/icon.svg' ) ) );

		add_menu_page(
			__( 'Accounting', 'wp-ever-accounting' ),
			__( 'Accounting', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'eaccounting',
			null,
			$icons,
			'54.5'
		);
		add_submenu_page(
			'eaccounting',
			__( 'Overview', 'wp-ever-accounting' ),
			__( 'Overview', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'eaccounting',
			array( $this, 'render_overview_page' )
		);
	}

	/**
	 * Registers the items page.
	 */
	public function register_items_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Items', 'wp-ever-accounting' ),
			__( 'Items', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'ea-items',
			array( $this, 'render_items_page' )
		);
	}

	/**
	 * Registers the sales page.
	 */
	public function register_sales_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Sales', 'wp-ever-accounting' ),
			__( 'Sales', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'ea-sales',
			array( $this, 'render_sales_page' )
		);
	}

	/**
	 * Registers the expenses page.
	 */
	public function register_expenses_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Expenses', 'wp-ever-accounting' ),
			__( 'Expenses', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'ea-expenses',
			array( $this, 'render_expenses_page' )
		);
	}

	/**
	 * Registers the banking page.
	 */
	public function register_banking_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Banking', 'wp-ever-accounting' ),
			__( 'Banking', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'ea-banking',
			array( $this, 'render_banking_page' )
		);
	}

	/**
	 * Registers the tools page.
	 */
	public function register_tools_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Tools', 'wp-ever-accounting' ),
			__( 'Tools', 'wp-ever-accounting' ),
			'manage_eaccounting',
			'ea-tools',
			array( $this, 'render_tools_page' )
		);
	}

	/**
	 * Registers the reports page.
	 */
	public function register_reports_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Reports', 'wp-ever-accounting' ),
			__( 'Reports', 'wp-ever-accounting' ),
			'ea_manage_report',
			'ea-reports',
			array( $this, 'render_reports_page' )
		);
	}

	/**
	 * Render overview page.
	 *
	 * @since 1.1.0
	 */
	public function render_overview_page() {
		include dirname( __FILE__ ) . '/views/admin-page-overview.php';
	}

	/**
	 * Render items page.
	 *
	 * @since 1.1.0
	 */
	public function render_items_page() {
		$tabs = array();
		if ( current_user_can( 'ea_manage_item' ) ) {
			$tabs['items'] = __( 'Items', 'wp-ever-accounting' );
		}
		$tabs        = apply_filters( 'eaccounting_item_tabs', $tabs );
		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		include dirname( __FILE__ ) . '/views/admin-page-items.php';
	}

	/**
	 * Render sales page.
	 *
	 * @since 1.1.0
	 */
	public function render_sales_page() {
		$tabs = array();
		if ( current_user_can( 'ea_manage_revenue' ) ) {
			$tabs['revenues'] = __( 'Revenues', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_invoice' ) ) {
			$tabs['invoices'] = __( 'Invoices', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_customer' ) ) {
			$tabs['customers'] = __( 'Customers', 'wp-ever-accounting' );
		}
		$tabs = apply_filters( 'eaccounting_sales_tabs', $tabs );

		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		include dirname( __FILE__ ) . '/views/admin-page-sales.php';
	}

	/**
	 * Render page.
	 *
	 * @since 1.1.0
	 */
	public function render_expenses_page() {
		$tabs = array();
		if ( current_user_can( 'ea_manage_payment' ) ) {
			$tabs['payments'] = __( 'Payments', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_bill' ) ) {
			$tabs['bills'] = __( 'Bills', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_vendor' ) ) {
			$tabs['vendors'] = __( 'Vendors', 'wp-ever-accounting' );
		}
		$tabs = apply_filters( 'eaccounting_expenses_tabs', $tabs );

		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		if ( empty( $current_tab ) ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'ea-expenses',
						'tab'  => $current_tab,
					),
					admin_url( 'admin.php' )
				)
			);
			exit();
		}
		include dirname( __FILE__ ) . '/views/admin-page-expenses.php';
	}

	/**
	 * Render banking page.
	 *
	 * @since 1.1.0
	 */
	public function render_banking_page() {
		$tabs = array();
		if ( current_user_can( 'ea_manage_account' ) ) {
			$tabs['accounts'] = __( 'Accounts', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_payment' ) && current_user_can( 'ea_manage_revenue' ) ) {
			$tabs['transactions'] = __( 'Transactions', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_manage_transfer' ) ) {
			$tabs['transfers'] = __( 'Transfers', 'wp-ever-accounting' );
		}

		$tabs = apply_filters( 'eaccounting_banking_tabs', $tabs );

		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		include dirname( __FILE__ ) . '/views/admin-page-banking.php';
	}

	/**
	 * Render tools page.
	 *
	 * @since 1.1.0
	 */
	public function render_tools_page() {
		$tabs = array();
		if ( current_user_can( 'ea_import' ) ) {
			$tabs['import'] = __( 'Import', 'wp-ever-accounting' );
		}
		if ( current_user_can( 'ea_export' ) ) {
			$tabs['export'] = __( 'Export', 'wp-ever-accounting' );
		}
		$tabs = apply_filters( 'eaccounting_tools_tabs', $tabs );

		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		if ( empty( $current_tab ) ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page' => 'ea-tools',
						'tab'  => $current_tab,
					),
					admin_url( 'admin.php' )
				)
			);
			exit();
		}
		include dirname( __FILE__ ) . '/views/admin-page-tools.php';
	}

	/**
	 * Render the reports page.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function render_reports_page() {
		$tabs = array(
			'sales'    => __( 'Sales', 'wp-ever-accounting' ),
			'expenses' => __( 'Expenses', 'wp-ever-accounting' ),
			'profits'  => __( 'Profits', 'wp-ever-accounting' ),
			'cashflow' => __( 'Cashflow', 'wp-ever-accounting' ),
		);

		$tabs = apply_filters( 'eaccounting_reports_tabs', $tabs );

		$first_tab   = current( array_keys( $tabs ) );
		$tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = ! empty( $tab ) && array_key_exists( $tab, $tabs ) ? sanitize_title( $tab ) : $first_tab;
		include dirname( __FILE__ ) . '/views/admin-page-reports.php';
	}

	/**
	 * Render Items tab.
	 *
	 * @since 1.1.0
	 */
	public function render_items_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			$item_id = filter_input( INPUT_GET, 'item_id', FILTER_SANITIZE_NUMBER_INT );
			include dirname( __FILE__ ) . '/views/items/edit-item.php';
		} else {
			include dirname( __FILE__ ) . '/views/items/list-item.php';
		}
	}

	/**
	 * Render revenues tab.
	 *
	 * @since 1.1.0
	 */
	public function render_revenues_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			$invoice_id = filter_input( INPUT_GET, 'revenue_id', FILTER_SANITIZE_NUMBER_INT );
			include dirname( __FILE__ ) . '/views/revenues/edit-revenue.php';
		} else {
			include dirname( __FILE__ ) . '/views/revenues/list-revenue.php';
		}
	}

	/**
	 * Render tab.
	 *
	 * @since 1.1.0
	 */
	public function render_invoices_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$invoice_id     = filter_input( INPUT_GET, 'invoice_id', FILTER_SANITIZE_NUMBER_INT );
		if ( 'view' === $requested_view && ! empty( $invoice_id ) ) {
			Invoice_Actions::view_invoice( $invoice_id );
		} elseif ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			Invoice_Actions::edit_invoice( $invoice_id );
		} else {
			include dirname( __FILE__ ) . '/views/invoices/list-invoice.php';
		}
	}

	/**
	 * Render customers tab.
	 *
	 * @since 1.1.0
	 */
	public function render_customers_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$customer_id    = filter_input( INPUT_GET, 'customer_id', FILTER_SANITIZE_NUMBER_INT );
		if ( 'view' === $requested_view && ! empty( $customer_id ) ) {
			include dirname( __FILE__ ) . '/views/customers/view-customer.php';
		} elseif ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/customers/edit-customer.php';
		} else {
			include dirname( __FILE__ ) . '/views/customers/list-customer.php';
		}
	}

	/**
	 * Render payments tab.
	 *
	 * @since 1.1.0
	 */
	public function render_payments_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$payment_id     = filter_input( INPUT_GET, 'payment_id', FILTER_SANITIZE_NUMBER_INT );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/payments/edit-payment.php';
		} else {
			include dirname( __FILE__ ) . '/views/payments/list-payment.php';
		}
	}

	/**
	 * Render bills tab
	 *
	 * @since 1.1.0
	 */
	public function render_bills_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$bill_id        = filter_input( INPUT_GET, 'bill_id', FILTER_SANITIZE_NUMBER_INT );
		if ( 'view' === $requested_view && ! empty( $bill_id ) ) {
			Bill_Actions::view_bill( $bill_id );
		} elseif ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			Bill_Actions::edit_bill( $bill_id );
		} else {
			include dirname( __FILE__ ) . '/views/bills/list-bill.php';
		}
	}

	/**
	 * Render vendors tab.
	 *
	 * @since 1.1.0
	 */
	public function render_vendors_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$vendor_id      = filter_input( INPUT_GET, 'vendor_id', FILTER_SANITIZE_NUMBER_INT );
		if ( 'view' === $requested_view && ! empty( $vendor_id ) ) {
			include dirname( __FILE__ ) . '/views/vendors/view-vendor.php';
		} elseif ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/vendors/edit-vendor.php';
		} else {
			include dirname( __FILE__ ) . '/views/vendors/list-vendor.php';
		}
	}

	/**
	 * Render accounts tab.
	 *
	 * @since 1.1.0
	 */
	public function render_accounts_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$account_id     = filter_input( INPUT_GET, 'account_id', FILTER_SANITIZE_NUMBER_INT );
		if ( 'view' === $requested_view && ! empty( $account_id ) ) {
			include dirname( __FILE__ ) . '/views/accounts/view-account.php';
		} elseif ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/accounts/edit-account.php';
		} else {
			include dirname( __FILE__ ) . '/views/accounts/list-account.php';
		}
	}

	/**
	 * Render transactions tab.
	 *
	 * @since 1.1.0
	 */
	public function render_transactions_tab() {
		include dirname( __FILE__ ) . '/views/transactions/list-transactions.php';
	}

	/**
	 * Render transfers tab.
	 *
	 * @since 1.1.0
	 */
	public function render_transfers_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$transfer_id    = filter_input( INPUT_GET, 'transfer_id', FILTER_SANITIZE_NUMBER_INT );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/transfers/edit-transfer.php';
		} else {
			include dirname( __FILE__ ) . '/views/transfers/list-transfer.php';
		}
	}

	/**
	 * Render Export tab.
	 *
	 * @since 1.0.2
	 */
	public function render_export_page() {
		include dirname( __FILE__ ) . '/views/tools/export.php';
	}

	/**
	 * Render Import tab.
	 *
	 * @since 1.0.2
	 */
	public function render_import_page() {
		include dirname( __FILE__ ) . '/views/tools/import.php';
	}

	/**
	 * Render sales report tab.
	 *
	 * @since 1.0.2
	 */
	public function render_sales_report_tab() {
		require_once dirname( __FILE__ ) . '/reports/class-sales.php';
		$report = new \EverAccounting\Admin\Report\Sales();
		$report->output();
	}

	/**
	 * Render expenses report tab.
	 *
	 * @since 1.0.2
	 */
	public function render_expenses_report_tab() {
		require_once dirname( __FILE__ ) . '/reports/class-expenses.php';
		$report = new \EverAccounting\Admin\Report\Expenses();
		$report->output();
	}

	/**
	 * Render profits report tab.
	 *
	 * @since 1.0.2
	 */
	public function render_profits_report_tab() {
		require_once dirname( __FILE__ ) . '/reports/class-profits.php';
		$report = new \EverAccounting\Admin\Report\Profits();
		$report->output();
	}

	/**
	 * Render cashflow report tab.
	 *
	 * @since 1.0.2
	 */
	public function render_cashflow_report_tab() {
		require_once dirname( __FILE__ ) . '/reports/class-cashflow.php';
		$report = new \EverAccounting\Admin\Report\CashFlow();
		$report->output();
	}

	/**
	 * Register settings tabs.
	 *
	 * @param array $tabs Settings tabs.
	 * @return array
	 */
	public function add_setting_tabs( $tabs ) {
		$tabs['currencies'] = __( 'Currencies', 'wp-ever-accounting' );
		$tabs['categories'] = __( 'Categories', 'wp-ever-accounting' );
		return $tabs;
	}

	/**
	 * Render currencies tab
	 *
	 * @since 1.1.0
	 */
	public function render_currencies_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$currency_id    = filter_input( INPUT_GET, 'currency_id', FILTER_SANITIZE_NUMBER_INT );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/currencies/edit-currency.php';
		} else {
			include dirname( __FILE__ ) . '/views/currencies/list-currency.php';
		}
	}

	/**
	 * Render categories tab
	 *
	 * @since 1.1.0
	 */
	public function render_categories_tab() {
		$requested_view = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$category_id    = filter_input( INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT );
		if ( in_array( $requested_view, array( 'add', 'edit' ), true ) ) {
			include dirname( __FILE__ ) . '/views/categories/edit-category.php';
		} else {
			include dirname( __FILE__ ) . '/views/categories/list-category.php';
		}
	}
}

new Menu();
