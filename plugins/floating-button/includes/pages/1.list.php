<?php
/*
 * Page Name: List
 */

use FloatingButton\Dashboard\ListTable;
use FloatingButton\WOW_Plugin;

defined( 'ABSPATH' ) || exit;

$list_table = new ListTable();
$list_table->prepare_items();
?>

<form method="post" class="wowp-list">
	<?php
	$list_table->search_box( esc_attr__( 'Search', 'floating-button' ), WOW_Plugin::PREFIX );
	$list_table->display();
	?>
    <input type="hidden" name="page" value="<?php echo sanitize_text_field( $_REQUEST['page'] ); ?>"/>
	<?php wp_nonce_field( WOW_Plugin::PREFIX . '_nonce', WOW_Plugin::PREFIX . '_list_action' ); ?>
</form>
<?php
