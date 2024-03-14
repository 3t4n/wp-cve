<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php _e('Insert Cloaked Link', 'pmlc_plugin') ?></title>
	<?php 
	wp_enqueue_script('jquery');
	wp_head();
	?>
	<script type="text/javascript" src="<?php echo site_url('wp-includes/js/tinymce/tiny_mce_popup.js') ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('wp-includes/js/tinymce/utils/mctabs.js') ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('wp-includes/js/tinymce/utils/form_utils.js') ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('wp-includes/js/tinymce/utils/validate.js') ?>"></script>
	<script type="text/javascript" src="<?php echo PMLC_Plugin::ROOT_URL. '/static/js/tinymce/clink-popup.js' ?>"></script>
	
</head>
<body id="link" style="display: none" class="pmlc_plugin">
<form onsubmit="CLinkDialog.update(); return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;"><?php _e('Insert Cloaked Link', 'pmlc_plugin') ?></a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper" style="height: 172px;">
		<div id="general_panel" class="panel current" style="overflow: auto; height: 172px;">

			<table border="0" cellpadding="4" cellspacing="0" class="form-table">
				<tr>
					<td class="nowrap" style="width: 55px;"><label><?php _e('Main Link', 'pmlc_plugin') ?></label></td>
					<td>
						<select name="main" style="width: 180px">
							<option value=""><?php _e(' -- Not Set -- ', 'pmlc_plugin') ?></option>
							<?php foreach ($links as $l): ?>
							<option value="<?php echo $l->id ?>"><?php echo $l->name ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td class="action add"><a href="#add" title="<?php _e('add', 'pmlc_plugin')?>" class="action"><span class="ui-icon ui-icon-circle-plus">+</span></a></td>
				</tr>
				<tr class="template" style="display: none;">
					<td class="nowrap"><label><?php _e('Additional', 'pmlc_plugin') ?></label></td>
					<td>
						<select name="additional[]" style="width: 180px">
							<option value=""><?php _e(' -- Not Set -- ', 'pmlc_plugin') ?></option>
							<?php foreach ($links as $l): ?>
							<option value="<?php echo $l->id ?>"><?php echo $l->name ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td class="action remove"><a href="#remove" title="remove"><span class="ui-icon ui-icon-circle-close">x</span></a></td>
				</tr>
				<tr>
					<td><label id="targetlistlabel" for="targetlist"><?php _e('Target', 'pmlc_plugin') ?></label></td>
					<td>
						<select id="target_list" name="target_list" style="width: 180px">
							<option value=""><?php _e(' -- Not Set -- ', 'pmlc_plugin') ?></option>
							<option value="_blank"><?php _e('New window', '_blank') ?></option>
							<option value="_self"><?php _e('Same window', '_self') ?></option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td class="nowrap"><label for="linktitle"><?php _e('Title', 'pmlc_plugin') ?></label></td>
					<td><input id="linktitle" name="linktitle" type="text" value="" style="width: 180px" /></td>
					<td></td>
				</tr>
				<tr>
					<td><label for="class_list"><?php _e('Class', 'pmlc_plugin') ?></label></td>
					<td><select id="class_list" name="class_list" style="max-width: 180px"></select></td>
					<td></td>
				</tr>
				<tr>
					<td><label for="sub_id"><?php _e('Sub ID', 'pmlc_plugin') ?></label></td>
					<td><input id="sub_id" name="sub_id" type="text" value="" style="width: 180px" /></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td><input id="nofollow" name="nofollow" type="checkbox" /><label for="nofollow" style="vertical-align:35%"> <?php _e('rel="nofollow"', 'pmlc_plugin') ?></label></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', 'pmlc_plugin') ?>" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e('Insert', 'pmlc_plugin') ?>" />
		</div>
	</div>
</form>

</body>
</html>