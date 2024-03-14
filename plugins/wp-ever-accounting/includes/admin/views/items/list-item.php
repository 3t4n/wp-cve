<?php
/**
 * Admin Items List Page.
 * Page: Items
 * Tab: Items
 *
 * @since       1.1.0
 * @subpackage  Admin/Items
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-item-list-table.php';
$items_table = new EverAccounting_Item_List_Table();
$items_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-items',
		'tab'    => 'items',
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
<h1 class="wp-heading-inline"><?php esc_html_e( 'Items', 'wp-ever-accounting' ); ?></h1>
<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
	<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
</a>
<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
	<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
</a>
<?php do_action( 'eaccounting_items_table_top' ); ?>
<form id="ea-items-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
	<?php
	$items_table->views();
	$items_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-items' );
	$items_table->display();
	?>
	<input type="hidden" name="page" value="ea-items"/>
	<input type="hidden" name="tab" value="items"/>
</form>
<?php do_action( 'eaccounting_items_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.item-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_item',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_item' ) . "',
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
