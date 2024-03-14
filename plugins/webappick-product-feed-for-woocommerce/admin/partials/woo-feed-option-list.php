<?php
/**
 * Category Mapping List View
 *
 * @link       https://webappick.com/
 * @since      4.3.33
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 */

$myListTable = new Woo_Feed_Option_list();
$myListTable->prepare_items();
global $plugin_page;
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Option List', 'woo-feed' ); ?><a href="<?php echo esc_url( admin_url( 'admin.php?page=webappick-wp-options&action=add-option' ) ); ?>" class="woo-feed-btn-bg-gradient-blue page-title-action"><?php esc_html_e( 'Add New Option', 'woo-feed' ); ?></a></h2>
	<?php WPFFWMessage()->displayMessages(); ?>
	<form id="contact-filter" method="post">
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="hidden" name="page" value="<?php echo esc_attr( $plugin_page ); ?>"/>
		<?php // $myListTable->search_box('search', 'search_id'); ?>
		<!-- Now we can render the completed list table -->
		<?php $myListTable->display(); ?>
	</form>
</div>

<script type="text/javascript">
	(function($, window, document){
		$(document).ready(function () {
			$('body').find(".single-option-delete").click(function () {
				if (confirm('<?php esc_html_e( 'Are You Sure to Delete?', 'woo-feed' ); ?>')) {
					window.location.href = $(this).attr('val');
				}
			});
			$('#doaction').click(function () {
				return confirm('<?php esc_html_e( 'Are You Sure to Delete?', 'woo-feed' ); ?>');
			});
			$('#doaction2').click(function () {
				return confirm('<?php esc_html_e( 'Are You Sure to Delete?', 'woo-feed' ); ?>');
			});
		});
	})(jQuery, window, document);
</script>
