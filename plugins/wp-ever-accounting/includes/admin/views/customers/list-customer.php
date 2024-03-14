<?php
/**
 * Render Customer list table
 * Page: Sales
 * Tab: Customers
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Customers
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-customer-list-table.php';
$customers_table = new EverAccounting_Customer_List_Table();
$customers_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-sales',
		'tab'    => 'customers',
		'action' => 'add',
	)
);
$import_url = eaccounting_admin_url(
	array(
		'page' => 'ea-tools',
		'tab'  => 'import',
	),
	admin_url( 'admin.php' )
);
?>
<h1 class="wp-heading-inline"><?php esc_html_e( 'Customers', 'wp-ever-accounting' ); ?></h1>
<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
	<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
</a>

<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
	<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
</a>
<?php do_action( 'eaccounting_customers_table_top' ); ?>
<form id="ea-customers-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
	<?php
	$customers_table->views();
	$customers_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-customers' );
	$customers_table->display();
	?>
	<input type="hidden" name="page" value="ea-sales"/>
	<input type="hidden" name="tab" value="customers"/>
</form>
<?php do_action( 'eaccounting_customers_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.customer-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_customer',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_customer' ) . "',
		}, function(json){
			$.eaccounting_notice(json);
		});
	});

	jQuery('.del').on('click',function(e){
		if(confirm('Are you sure you want to delete?')){
			return true;
		} else {
			return false;
		}
	});
"
);
