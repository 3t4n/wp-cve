<?php
/**
 * Admin Reports Page.
 *
 * @since       1.0.2
 * @subpackage  Admin/Reports
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
require_once dirname( __FILE__ ) . '/expense-summary.php';
require_once dirname( __FILE__ ) . '/income-summary.php';
require_once dirname( __FILE__ ) . '/income-expense.php';

/**
 * render reports page.
 *
 * @since 1.0.2
 */
function eaccounting_admin_reports_page() {
	$tabs       = eaccounting_get_reports_tabs();
	$active_tab = eaccounting_get_active_tab( $tabs, 'income_summary' );
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<?php eaccounting_navigation_tabs( $tabs, $active_tab ); ?>
		</h2>
		<div id="tab_container">
			<?php
			/**
			 * Fires in the Tabs screen tab.
			 *
			 * The dynamic portion of the hook name, `$active_tab`, refers to the slug of
			 * the currently active reports tab.
			 *
			 * @since 1.0.2
			 */
			do_action( 'eaccounting_reports_tab_' . $active_tab );
			?>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php
}

/**
 * Retrieve reports tabs
 *
 * @since 1.0.2
 * @return array $tabs
 */
function eaccounting_get_reports_tabs() {
	$tabs                    = array();
	$tabs['income_summary']  = __( 'Income Summary', 'wp-ever-accounting' );
	$tabs['expense_summary'] = __( 'Expense Summary', 'wp-ever-accounting' );
	$tabs['income_expense']  = __( 'Income vs Expense', 'wp-ever-accounting' );

	return apply_filters( 'eaccounting_reports_tabs', $tabs );
}

/**
 * Setup reports pages.
 *
 * @since 1.0.2
 */
function eaccounting_load_reports_page() {
	$tab = eaccounting_get_current_tab();
	if ( empty( $tab ) ) {
		wp_safe_redirect( add_query_arg( array( 'tab' => 'income_summary' ) ) );
		exit();
	}

	do_action( 'eaccounting_load_reports_page_tab' . $tab );
}
