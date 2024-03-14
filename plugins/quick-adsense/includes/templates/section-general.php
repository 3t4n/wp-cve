<div class="quick_adsense_block">
	<div class="quick_adsense_block_labels"><span>Options</span></div>
	<div class="quick_adsense_block_controls">
		<a id="quick_adsense_settings_reset_to_default" href="javascript:;">Reset to Default Settings</a>
	</div>
	<div class="clear"></div>
</div>
<?php
quick_adsense_load_file( 'templates/block-general-adsense.php', $args, true );
quick_adsense_load_file( 'templates/block-general-appearance.php', $args, true );
quick_adsense_load_file( 'templates/block-general-assign-position.php', $args, true );
if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
	quick_adsense_load_file( 'templates/block-general-quicktag.php', $args, true );
}
?>
