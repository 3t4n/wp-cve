<?php
/**
 * Render Bill list table
 * Page: Expenses
 * Tab: Bills
 *
 * @since       1.1.0
 * @subpackage  Admin/Views/Bills
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-bill-list-table.php';
$bill_table = new EverAccounting_Bill_List_Table();
$bill_table->prepare_items();
$add_url = eaccounting_admin_url(
	array(
		'page'   => 'ea-expenses',
		'tab'    => 'bills',
		'action' => 'add',
	)
);
?>
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Bills', 'wp-ever-accounting' ); ?></h1>
	<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
		<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
	</a>
	<hr class="wp-header-end">
<?php do_action( 'eaccounting_bills_table_top' ); ?>
	<form id="ea-bills-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<?php
		$bill_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-bills' );
		$bill_table->display();
		?>
		<input type="hidden" name="page" value="ea-expenses"/>
		<input type="hidden" name="tab" value="bills"/>
	</form>
<?php do_action( 'eaccounting_bills_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"jQuery('.del').on('click',function(e){
							if(confirm('Are you sure you want to delete?')){
								return true;
							} else {
								return false;
							}
						});"
);
