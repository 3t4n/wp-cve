<?php
/**
 * Render Transaction list table
 * Page: Banking
 * Tab: Transactions
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Transactions
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-transaction-list-table.php';
$transactions_table = new EverAccounting_Transaction_List_Table();
$transactions_table->prepare_items();
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Transactions', 'wp-ever-accounting' ); ?></h1>
<?php do_action( 'eaccounting_transactions_table_top' ); ?>
<form id="ea-transactions-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
	<?php
	$transactions_table->views();
	$transactions_table->display();
	?>
	<input type="hidden" name="page" value="ea-banking"/>
	<input type="hidden" name="tab" value="transactions"/>
</form>
<?php do_action( 'eaccounting_transactions_table_bottom' ); ?>
