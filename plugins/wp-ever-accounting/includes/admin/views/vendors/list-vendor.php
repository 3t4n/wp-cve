<?php
/**
 * Render Vendor list table
 * Page: Expenses
 * Tab: Vendors
 *
 * @since       1.0.2
 * @subpackage  Admin/View/Vendors
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-vendor-list-table.php';
$vendors_table = new EverAccounting_Vendor_List_Table();
$vendors_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-expenses',
		'tab'    => 'vendors',
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
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Vendors', 'wp-ever-accounting' ); ?></h1>
	<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
		<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
	</a>
	<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
		<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
	</a>
<?php do_action( 'eaccounting_vendors_table_top' ); ?>
	<form id="ea-vendors-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<?php
		$vendors_table->views();
		$vendors_table->search_box( esc_html__( 'Search', 'wp-ever-accounting' ), 'ea-vendors' );
		$vendors_table->display();
		?>
		<input type="hidden" name="page" value="ea-expenses"/>
		<input type="hidden" name="tab" value="vendors"/>
	</form>
<?php do_action( 'eaccounting_vendors_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.vendor-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_vendor',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_vendor' ) . "',
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
