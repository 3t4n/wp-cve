<?php
Namespace Accordeonmenuck;
defined('CK_LOADED') or die;

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists('CKListTable')) {
	require_once( ACCORDEONMENUCK_PATH . '/helpers/cklisttable.php' );
}

if (!class_exists('CKListTableStyle')) {
	require_once( ACCORDEONMENUCK_PATH . '/helpers/cklisttablestyle.php' );
}


//Prepare Table of elements
$wp_list_table = new CKListTableStyle();
$wp_list_table->prepare_items();
?>
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/ckframework.css" type="text/css" />
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/admin.css" type="text/css" />
<script>
function CKsubmitform(action) {
	if (action == 'menu.delete') {
		var c = confirm('<?php echo CKText::_('Are you sure to want to delete ?') ?>');
		if (c == false) return;
	}

	var form = document.getElementById('ckform');
	var selector = form.task;
	selector.value = action;
	var selection = ckGetCheckedItems('cid[]');
	if (! selection.length) {
		alert('<?php echo CKText::_('Please select an item') ?>');
		return;
	}
	form.submit();
}

function ckGetCheckedItems(name) {
	var list = document.getElementsByName(name);
	var results = [];
	for(var i = 0; i < list.length; i++){
		list[i].checked ? results.push(list[i]):"";
	}
	return results;
}
</script>
<link rel="stylesheet" href="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/jscolor/jscolor.css" type="text/css" />
<script type="text/javascript" src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/assets/jscolor/jscolor.js"></script>
<div id="ckoptionswrapper" class="ckinterface">
	<?php echo CKFof::displayMessages(); ?>
	<a href="<?php echo ACCORDEONMENUCK_WEBSITE ?>" target="_blank" style="text-decoration:none;"><img src="<?php echo ACCORDEONMENUCK_MEDIA_URL ?>/images/logo_accordeonmenuck_64.png" style="margin: 5px;" class="cklogo" /><span class="cktitle">Accordeon Menu CK</span></a>
	<div style="clear:both;"></div>
	<?php if (CKFof::userCan('edit')) { ?>
	<div class="cktoolbar">
		<a href="admin.php?page=accordeonmenuck_edit_style&id=0&from=existing" class="btn-action"><span class="dashicons dashicons-welcome-add-page"></span><?php echo CKText::_('Add new') ?></a>
		<a href="javascript:void(0)" class="btn-action" onclick="CKsubmitform('style.edit')"><span class="dashicons dashicons-welcome-write-blog"></span><?php echo CKText::_('Edit') ?></a>
		<a href="javascript:void(0)" class="btn-action" onclick="CKsubmitform('style.copy')"><span class="dashicons dashicons-admin-page"></span><?php echo CKText::_('Copy') ?></a>
		<a href="javascript:void(0)" class="btn-action" onclick="CKsubmitform('style.delete')"><span class="dashicons dashicons-trash"></span><?php echo CKText::_('Delete') ?></a>
		<a class="button" href="https://www.ceikay.com/documentation/accordeon-menu-ck/" target="_blank"><img src="https://media.ceikay.com/images/page_white_acrobat.png" width="16" height="16" /> <?php echo CKText::_('Documentation') ?></a>
	</div>
	<?php } ?>
	<form id="ckform" method="post">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="page" value="<?php echo $this->input->get('page') ?>" />
		<?php
		$wp_list_table->display()
		?>
	</form>
</div>
<?php echo Helper::copyright() ?>