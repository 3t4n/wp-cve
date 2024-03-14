<?php
/**
 * Render Category list table
 *
 * @since       1.0.2
 * @subpackage  Admin/Settings/Category
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-category-list-table.php';
$category_table = new EverAccounting_Category_List_Table();
$category_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-settings',
		'tab'    => 'categories',
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
<h1 class="wp-heading-inline"><?php esc_html_e( 'Categories', 'wp-ever-accounting' ); ?></h1>
<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
	<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
</a>
<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
	<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
</a>
<?php do_action( 'eaccounting_categories_table_top' ); ?>
<form id="ea-categories-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
	<?php
	$category_table->views();
	$category_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-categories' );
	$category_table->display();
	?>
	<input type="hidden" name="page" value="ea-settings"/>
	<input type="hidden" name="tab" value="categories"/>
</form>
<?php do_action( 'eaccounting_categories_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.category-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_category',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_category' ) . "',
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
