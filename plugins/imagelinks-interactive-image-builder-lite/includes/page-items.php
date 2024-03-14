<?php
defined('ABSPATH') || exit;

$list_table = new ImageLinks_List_Table_Items();
$list_table->prepare_items();
?>
<div class="wrap imagelinks">
	<?php require 'page-info.php'; ?>
	<div class="imagelinks-page-header">
		<div class="imagelinks-title"><?php esc_html_e('ImageLinks Items', 'imagelinks'); ?></div>
		<div class="imagelinks-actions">
			<a href="?page=imagelinks_item"><?php esc_html_e('Add Item', 'imagelinks'); ?></a>
		</div>
	</div>
	<!-- imagelinks app -->
	<div id="imagelinks-app-items" class="imagelinks-app">
		<form method="get">
			<input type="hidden" name="page" value="<?php echo sanitize_key(filter_var($_REQUEST['page'], FILTER_DEFAULT)) ?>">
			<?php $list_table->display() ?>
		</form>
	</div>
	<!-- /end imagelinks app -->
</div>