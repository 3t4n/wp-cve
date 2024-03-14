<?php
/**
 * Render Currency list table
 *
 * @since       1.0.2
 * @subpackage  Admin/Settings/Currency
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();

require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-currency-list-table.php';
$currency_table = new EverAccounting_Currency_List_Table();
$currency_table->prepare_items();
$add_url    = eaccounting_admin_url(
	array(
		'page'   => 'ea-settings',
		'tab'    => 'currencies',
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
<h1 class="wp-heading-inline"><?php esc_html_e( 'Currencies', 'wp-ever-accounting' ); ?></h1>
<a class="page-title-action" href="<?php echo esc_url( $add_url ); ?>">
		<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
</a>
<a class="page-title-action" href="<?php echo esc_url( $import_url ); ?>">
	<?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?>
</a>
<?php do_action( 'eaccounting_currencies_table_top' ); ?>
	<form id="ea-currency-table" method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<?php
		$currency_table->views();
		$currency_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-currencies' );
		$currency_table->display();
		?>
		<input type="hidden" name="page" value="ea-settings"/>
		<input type="hidden" name="tab" value="currencies"/>
	</form>
<?php do_action( 'eaccounting_currencies_table_bottom' ); ?>
<?php
eaccounting_enqueue_js(
	"
	jQuery('.currency-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_currency',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_currency' ) . "',
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
