<?php
/*
* Various file popups
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}
?>

<!-- Rename File -->
<div class="upfp-popup upfp-rnm-file-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Rename this file", "user-private-files"); ?></h4>
		<span class="closePopup upvf-rnm-file-cls">X</span>
		<form id="upvf-rename-file">
			<input type="text" id="rnm-file-name" required>
			<input type="hidden" id="upvf-rnm-file-id">
			<input type="submit" id="upvf-file-rnm-sbmt" value="<?php echo __("Save", "user-private-files"); ?>">
		</form>
	</div>
</div>

<!-- Edit file dsc -->
<div class="upfp-popup upfp-file-dsc-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Edit file description", "user-private-files"); ?></h4>
		<span class="closePopup upvf-file-dsc-cls">X</span>
		<form id="upvf-file-dsc">
			<input type="text" id="update-file-dsc">
			<input type="hidden" id="upvf-file-dsc-id">
			<input type="submit" value="<?php echo __("Save", "user-private-files"); ?>">
		</form>
	</div>
</div>

<!-- Delete a file -->
<div class="upfp-popup upfp-dlt-file-pp upfp-hidden" data-dlt-type="">
	<div class="upf_inner">
		<h4><?php echo __("Delete File?", "user-private-files"); ?></h4>
		<span class="closePopup upvf-dlt-file-cls">X</span>
		<form id="upvf-delete-file">
			<h4 id="upfp_dlt_msg_permanent" class="upfp-hidden"><?php echo __("This will permanently delete the file.", "user-private-files"); ?></h4>
			<h4 id="upfp_dlt_msg_trash" class="upfp-hidden"><?php echo __("This will move the file to trash.", "user-private-files"); ?></h4>
			<input type="hidden" id="upvf-dlt-file-id">
			<input type="submit" id="upvf-file-dlt-sbmt" value="<?php echo __("Delete", "user-private-files"); ?>">
		</form>
	
	</div>
</div>

<!-- Move a file -->
<div class="upfp-popup upfp-move-file-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Move this file", "user-private-files"); ?></h4>
		<span class="closePopup upvf-move-file-cls">X</span>
		<form id="upvf-move-file">
			<input type="hidden" id="upvf-move-file-id">
			<input type="submit" id="upvf-file-move-sbmt" value="<?php echo __("Move", "user-private-files"); ?>">
		</form>
	</div>
</div>

<!-- Move Bulk Files -->
<div class="upfp-popup upfp-move-bulk-files-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Move bulk files", "user-private-files"); ?></h4>
		<span class="closePopup upvf-move-bulk-files-cls">X</span>
		<form id="upvf-move-bulk-files">
			<input type="submit" id="upvf-file-move-bulk-sbmt" value="<?php echo __("Move", "user-private-files"); ?>">
		</form>
	</div>
</div>



