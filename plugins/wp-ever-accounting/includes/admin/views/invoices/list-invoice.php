<?php
/**
 * Render Invoice list table
 * Page: Sales
 * Tab: Invoices
 *
 * @since       1.1.0
 * @subpackage  Admin/Views/Invoices
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-invoice-list-table.php';
$invoice_table = new EverAccounting_Invoice_List_Table();
$invoice_table->prepare_items();
$add_url = eaccounting_admin_url(
	array(
		'page'   => 'ea-sales',
		'tab'    => 'invoices',
		'action' => 'add',
	)
);
?>
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Invoices', 'wp-ever-accounting' ); ?></h1>
	<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
		<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
	</a>
<?php do_action( 'eaccounting_invoices_table_top' ); ?>
	<form id="ea-invoices-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<?php
		$invoice_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-invoices' );
		$invoice_table->display();
		?>
		<input type="hidden" name="page" value="ea-sales"/>
		<input type="hidden" name="tab" value="invoices"/>
	</form>
<?php do_action( 'eaccounting_invoices_table_bottom' ); ?>
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
