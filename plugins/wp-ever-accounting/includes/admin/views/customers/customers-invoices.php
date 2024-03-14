<?php
/**
 * Page: Sales
 * Tab: Customers
 * Section: Invoices
 *
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require_once EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-invoice-list-table.php';
$args       = array(
	'display_args' => array(
		'columns_to_hide'   => array( 'actions', 'cb', 'name' ),
		'hide_bulk_options' => true,
	),
);
$list_table = new EverAccounting_Invoice_List_Table( $args );
$list_table->prepare_items();
$list_table->views();
$list_table->display();
