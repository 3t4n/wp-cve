<?php
/**
 * Render Payment list table
 * Page: Expenses
 * Tab: Payment
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Payments
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-payment-list-table.php';
$payments_table = new EverAccounting_Payment_List_Table();
$payments_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-expenses',
		'tab'    => 'payments',
		'action' => 'add',
	)
);
$import_url = add_query_arg(
	array(
		'page' => 'ea-tools',
		'tab'  => 'import',
	),
	admin_url( 'admin.php' )
);
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Payments', 'wp-ever-accounting' ); ?></h1>
<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
	<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
</a>
<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
		<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
</a>
<?php do_action( 'eaccounting_payments_table_top' ); ?>
<form id="ea-payments-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
	<?php
	$payments_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-payments' );
	$payments_table->display();
	?>
	<input type="hidden" name="page" value="ea-expenses"/>
	<input type="hidden" name="tab" value="payments"/>
</form>
<?php do_action( 'eaccounting_payments_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.del').on('click',function(e){
		if(confirm('Are you sure you want to delete?')){
			return true;
		} else {
			return false;
		}
	});
"
);
