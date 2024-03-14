<?php
/**
 * Page: Expenses
 * Tab: Vendors
 * Section: Transactions
 *
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require_once EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-payment-list-table.php';
$args       = array(
	'display_args' => array(
		'columns_to_hide'      => array( 'actions', 'cb', 'contact_id' ),
		'hide_extra_table_nav' => true,
		'hide_bulk_options'    => true,
	),
);
$list_table = new EverAccounting_Payment_List_Table( $args );
$list_table->prepare_items();
$list_table->views();
$list_table->display();
