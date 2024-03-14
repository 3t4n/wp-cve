<?php
/*
* Various folder popups
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}
?>

<!-- New folder -->
<div class="upfp-popup new-fldr upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Create New Folder", "user-private-files"); ?></h4>
		<span class="closePopup new_fldr_closePopup">X</span>
		<form id="upvf-new-fldr">
			<input type="text" id="new-fldr-name" placeholder="<?php echo __('Folder Name', 'user-private-files'); ?>" required="required">
			<input type="hidden" id="parent_fldr" value="0">
			<input type="submit" id="upvf-fldr-sbmt" value="<?php echo __('Create', 'user-private-files'); ?>">
		</form>
	</div>
</div>

<!-- Rename folder -->
<div class="upfp-popup upfp-rnm-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Rename this folder", "user-private-files"); ?></h4>
		<span class="closePopup upvf-rnm-cls">X</span>
		<form id="upvf-rename-fldr">
			<input type="text" id="rnm-fldr-name" required="required">
			<input type="hidden" id="upvf-rnm-fldr-id">
			<input type="submit" id="upvf-fldr-rnm-sbmt" value="<?php echo __("Save", "user-private-files"); ?>">
		</form>
	</div>
</div>

<!-- Delete folder -->
<div class="upfp-popup upvf-dlt-pp upfp-hidden" data-dlt-type="">
	<div class="upf_inner">
		<h4><?php echo __("Delete Folder?", "user-private-files"); ?></h4>
		<span class="closePopup upvf-dlt-cls">X</span>
		<form id="upvf-delete-fldr">
			<h4 id="dlt_folder_permanent" class="upfp-hidden"><?php echo __("This will permanently delete the files and sub folders.", "user-private-files"); ?></h4>
			<h4 id="dlt_folder_trash" class="upfp-hidden"><?php echo __("This will move files and sub folders to trash.", "user-private-files"); ?></h4>
			<input type="hidden" id="upvf-dlt-fldr-id">
			<input type="submit" id="upvf-fldr-dlt-sbmt" value="<?php echo __("Delete", "user-private-files"); ?>">
		</form>
	</div>
</div>

<!-- Empty Trash -->
<div class="upfp-popup upvf-empty-trash-pp upfp-hidden">
	<div class="upf_inner">
		<h4><?php echo __("Are You Sure?", "user-private-files"); ?></h4>
		<span class="closePopup upvf-et-cls">X</span>
		<form id="upvf-empty-trash">
			<input type="submit" value="<?php echo __("Empty Trash", "user-private-files"); ?>">
		</form>
	</div>
</div>
