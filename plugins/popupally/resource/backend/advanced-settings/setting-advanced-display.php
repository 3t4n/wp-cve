<h3>Advanced Settings</h3>
<input type="hidden" name="no-inline" value="<?php echo $advanced['no-inline']; ?>"/>
<div class="popupally-setting-section">
	<div class="popupally-setting-section-header">Max number of pages to load</div>
	<div class="popupally-setting-section-help-text">-1 to show all. Displaying too many pages could prevent the PopupAlly Settings from loading due to time out.</div>
	<div class="popupally-setting-configure-block">
		Load <input id="max-page" type="text" name="max-page" value="<?php echo $advanced['max-page']; ?>"/> Pages
	</div>
</div>
<div class="popupally-setting-section">
	<div class="popupally-setting-section-header">Max number of posts to load</div>
	<div class="popupally-setting-section-help-text">-1 to show all. Displaying too many posts could prevent the PopupAlly Settings from loading due to time out.</div>
	<div class="popupally-setting-configure-block">
		Load <input id="max-post" type="text" name="max-post" value="<?php echo $advanced['max-post']; ?>"/> Posts
	</div>
</div>
<div class="popupally-setting-section">
	<div class="popupally-setting-section-header">Add &quot;!important&quot; modifier to popup styling</div>
	<div class="popupally-setting-section-help-text">enable this option will add &quot;!important&quot; modifier to popup styling. <strong>Caution</strong>: this should only be used as a last resort when the theme CSS also uses the &quot;!important&quot; modifier which cannot be changed.</div>
	<div class="popupally-setting-configure-block">
		<input id="use-important" type="checkbox" name="use-important" <?php checked($advanced['use-important'], 'true'); ?> value="true"/>
		<label for="use-important">Add &quot;!important&quot; modifier</label>
	</div>
</div>