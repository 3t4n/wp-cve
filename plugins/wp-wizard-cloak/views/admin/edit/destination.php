<?php 
/* @var $this PMLC_Admin_Edit */
?>
<div class="wrap">
	<?php if ($this->errors->get_error_codes()): ?>
		<?php $this->error() ?>
	<?php endif ?>

	<form name="destination-set" method="post">
	<table class="form-table dest destination-set">
		<?php foreach ($post['url'] as $i => $url): ?>
			<tr class="form-field">
				<td class="url"><input type="text" name="url[]" maxlength="255" value="<?php echo esc_attr($url) ?>" /></td>
				<td class="weight"><input type="text" class="small-text" name="weight[]" maxlength="3" value="<?php echo esc_attr($post['weight'][$i]) ?>" style="width:50px" />%</td>
				<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
			</tr>
		<?php endforeach ?>
		<tr class="form-field template">
			<td class="url"><input type="text" name="url[]" maxlength="255" value="http://" /></td>
			<td class="weight"><input type="text" class="small-text" name="weight[]" maxlength="3" value="0" style="width:50px" />%</td>
			<td class="action remove"><a href="#remove"><?php _e('Remove', 'pmlc_plugin') ?></a></td>
		</tr>
		<tr>
			<td><a href="#add" title="<?php _e('add', 'pmlc_plugin')?>" class="action">Add Another URL</a></td>
			<td class="action auto"><a href="#auto" title="<?php _e('auto adjust weights', 'pmlc_plugin')?>"><span class="ui-icon ui-icon-refresh">%</span></a></td>
			<td></td>
		</tr>
	</table>
	<p>
		<?php wp_nonce_field('edit-destination', '_wpnonce_edit-destination') ?>
		<input type="hidden" name="is_submitted" value="1" />
		<input class="button-primary" type="submit" value="<?php echo esc_attr(__('Save Destination Set'), 'pmlc_plugin') ?>" />
		or
		<input class="button" type="submit" name="clear" value="<?php echo esc_attr(__('Clear'), 'pmlc_plugin') ?>" />
	</p>
	</form>
	
	<form name="destination-set-upload" method="post" action="<?php echo add_query_arg('action', 'destination_upload', $this->baseUrl) ?>" enctype="multipart/form-data">
		<div>
			Load URLs from file:
			<input id="destination-set-upload-file" type="file" name="upload" />
			<input type="submit" value="Upload" class="button" />
			<span class="loading" style="display:none">O</span>
		</div>
	</form>
	<br />
	<small>
	<?php _e('<strong>Warning:</strong> if you click Save or Clear in this dialog, even if you do not click Save Link on the main Create/Edit screen, your changes will still be saved. To cancel changes, click the <strong>X</strong> to close the dialog window.') ?>
	</small>
	
	
</div>
