<?php
/**
 * Render Transactions list table
 *
 * Page: Banking
 * Tab: Accounts
 * Section: Transactions
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Accounts
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require_once EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-transaction-list-table.php';
$args       = array(
	'display_args' => array(
		'columns_to_hide'      => array( 'actions', 'cb', 'account_id' ),
		'hide_bulk_options'    => true,
		'hide_extra_table_nav' => true,
	),
);
$list_table = new EverAccounting_Transaction_List_Table( $args );
$list_table->prepare_items();
$list_table->views();
$list_table->display();
