<div class="save"><?php _e('Saving...','onetrt'); ?></div>
<?php if (is_array($opts->thumbs) && count($opts->thumbs)) : ?>
<div class="lister">
	<p class="head">
		<strong><?php _e("Custom dimensions","onetrt"); ?></strong> &mdash;
		<small><abbr class="hint" title="<?php _e('This list contains custom sizes for this plugin. Custom sizes which has been added by another plugin or theme will not be listed.','onetrt'); ?>">help</abbr></small>
	<p>
	<ul class="custom-thumbnails">
	<?php foreach($opts->thumbs AS $id => $cont) : ?>
		<li>
			<span class="txt"><strong><?php echo $cont['name']; ?></strong> (<?php echo $cont['width']."x".$cont['height']; ?>)</span>
			<span class="opt">
				<a href="<?php echo $own->admin_uri(array("thumb_remove"=>$id));?>"><?php _e('Remove', 'onetrt'); ?></a>
				<a href="<?php echo $own->admin_uri(array("thumb_edit"=>$id));?>"><?php _e('Edit', 'onetrt'); ?></a>
			</span>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<form action="<?php echo $this->admin_uri(); ?>" method="post" class="editor">
	<input type="hidden" name="ename" value="<?php echo $onetrt_edit_thumb['name']; ?>" />
	<p class="head">
		<strong><?php strlen($onetrt_edit_thumb['name']) ? _e('Edit custom size', 'onetrt') : _e('Add custom size', 'onetrt'); ?></strong> &mdash;
		<small><abbr class="hint" title="<?php _e('Add thumbnail sizes for test purposes only. You better integrate custom sizes in you themes/plugins.', 'onetrt'); ?>">help</abbr></small>
	</h3>
	<div>
		<label for="thumb_name" title="<?php _e('Special characters and spaces will be remover or/and replaced. Also you can refer to this name as a thumbnail size in your code.', 'onetrt'); ?>"><?php _e('Name', 'onetrt'); ?></label>
		<input type="input" name="thumb_name" id="thumb_name" class="form-input-tip" value="<?php echo $onetrt_edit_thumb['name']; ?>"/>
	</div><div>
		<label for="thumb_width"><?php _e('Width', 'onetrt'); ?></label>
		<input type="number" name="thumb_width" id="thumb_width" value="<?php echo $onetrt_edit_thumb['width']; ?>"/>
	</div><div>
		<label for="thumb_height"><?php _e('Height', 'onetrt'); ?></label>
		<input type="number" name="thumb_height" id="thumb_height" value="<?php echo $onetrt_edit_thumb['height']; ?>"/>
	</div>
	<div>
		<label for="thumb_crop"><?php _e('Autocrop', 'onetrt'); ?></label>
		<input type="checkbox" name="thumb_crop" id="thumb_crop" <?php echo $onetrt_edit_thumb['crop'] == 1 ? 'checked="checked"' : "" ?> />
	</div>
	<?php if(strlen($onetrt_edit_thumb['name'])) : ?>
		<input type="submit" name="update_thumb" id="updateThumb" class="button" value="<?php _e('Update size','onetrt'); ?>" />
		<a href="<?php echo $this->admin_uri(); ?>"><?php _e("Cancel",'onetrt'); ?></a>
	<?php else: ?>
		<input type="submit" name="save_thumb" id="saveThumb" class="button" value="<?php _e('Add size','onetrt'); ?>" />
	<?php endif; ?>
</form>