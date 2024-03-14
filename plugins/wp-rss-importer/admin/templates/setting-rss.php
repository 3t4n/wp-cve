<?php 
$slink = get_option('logics_sourcelink_enable');
if(get_option('logics_sourcelink')) {
	$logics_sourcelink = get_option('logics_sourcelink');
} else {
	$logics_sourcelink = '';
} 
?>
<div id="wpsl-store-overview" class="wrap">
    <h2>RSS Feed Syndication Setting </h2>
    <form method="post">
        <p><input type="checkbox" name="logics[sourcelink]" value="1" <?php if($slink == 1) { ?> checked <?php } ?> /> Add Source link in post</p>

		<p>Text for Source Link <input type="text" name="logics[sourcelinktext]" value="<?php echo $logics_sourcelink; ?>" /> </p>

		<p><input type="checkbox" name="logics[source_url_meta]" value="1" <?php if(get_option('logics_source_url_meta') == 1) { ?> checked <?php } ?> /> Save source URL as custom meta of post for future use</p>

		<!-- Save feed id as custom meta feature added by Elliot Sabitov -->
		<p><input type="checkbox" name="logics[feed_id_meta]" value="1" <?php if(get_option('logics_feed_id_meta') == 1) { ?> checked <?php } ?> /> Save Feed ID as custom meta of post for future use</p>
		<!-- end of save feed id -->

		 <p>
               <input type="hidden" name="logics_actions" value="logics_setting_rss" />
				<input id="logics-update-rss" type="submit" name="logics-update-rss" class="button-primary" value="<?php _e( 'Save', 'logics' ); ?>" />
            </p>

    </form>
</div>