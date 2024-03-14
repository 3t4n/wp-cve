<?php
/**
 * Render Accounts list table
 * Page: Banking
 * Tab: Accounts
 *
 * @since       1.0.2
 * @subpackage  Admin/Views/Accounts
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit;

$add_url    = add_query_arg(
	array(
		'page'   => 'ea-banking',
		'tab'    => 'accounts',
		'action' => 'add',
	),
	admin_url( 'admin.php' )
);
$import_url = add_query_arg(
	array(
		'page' => 'ea-tools',
		'tab'  => 'import',
	),
	admin_url( 'admin.php' )
);
require EACCOUNTING_ABSPATH . '/includes/admin/list-tables/class-account-list-table.php';
$accounts_table = new EverAccounting_Account_List_Table();
$accounts_table->prepare_items();
?>
	<h1>
		<?php esc_html_e( 'Accounts', 'wp-ever-accounting' ); ?>
		<a href="<?php echo esc_url( $add_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?></a>
		<a class="page-title-action" href=" <?php echo esc_url( $import_url ); ?>"><?php esc_html_e( 'Import', 'wp-ever-accounting' ); ?></a>
	</h1>
	<?php do_action( 'eaccounting_accounts_table_top' ); ?>
	<form method="get" id="ea-accounts-table" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<?php $accounts_table->views(); ?>
		<?php $accounts_table->search_box( __( 'Search', 'wp-ever-accounting' ), 'ea-accounts' ); ?>
		<?php $accounts_table->display(); ?>

		<input type="hidden" name="page" value="ea-banking"/>
		<input type="hidden" name="tab" value="accounts"/>
	</form>
	<?php do_action( 'eaccounting_accounts_table_bottom' ); ?>

<?php
eaccounting_enqueue_js(
	"
	jQuery('.account-status').on('change', function(e){
		jQuery.post('" . eaccounting()->ajax_url() . "', {
			action:'eaccounting_edit_account',
			id: $(this).data('id'),
			enabled: $(this).is(':checked'),
			nonce: '" . wp_create_nonce( 'ea_edit_account' ) . "',
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
