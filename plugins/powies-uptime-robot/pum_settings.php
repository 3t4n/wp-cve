<div class="wrap">
<div class="icon32" id="icon-options-general"></div>
<h2><?php _e('Uptime Robot Settings', 'powies-uptime-robot') ?></h2>
<form method="post" action="options.php">
<?php
settings_fields( 'pum-settings' );
if  ( get_option('postfield-legend') == '' ) {
	update_option('postfield-legend',__('Post Content','powies-uptime-robot'));
}
?>
<div id="poststuff">
<div class="postbox">
<h3><?php _e('API Settings', 'powies-uptime-robot') ?></h3>
	<div class="inside">
    <table class="form-table">
        <tr valign="top">
        	<th scope="row"><?php _e('API Key', 'powies-uptime-robot') ?></th>
        	<td><input type="text" size="50" name="pum-apikey" value="<?php echo get_option('pum-apikey'); ?>" /></td>
        </tr>

    </table>
    </div>
</div>

<div id="poststuff">
<div class="postbox">
<h3><?php _e('Display Settings', 'powies-uptime-robot') ?></h3>
	<div class="inside">
    <table class="form-table">
        <tr valign="top">
        	<th scope="row"><?php _e('Hide Monitors', 'powies-uptime-robot') ?></th>
        	<td><input type="text" size="80" name="pum-hidemonitors" value="<?php echo get_option('pum-hidemonitors'); ?>" /></td>
        </tr>
    </table>
    <?php _e('Comma separated list of monitors to hide from displaying', 'powies-uptime-robot') ?>
    </div>
</div>

</div>
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</form>
<br />

<div id="poststuff">
<div class="postbox">
<h3><?php _e('Shortcode Usage', 'powies-uptime-robot') ?></h3>
	<div class="inside">
		<?php _e('Use the shortcode <code>[pum]</code> to show the status list on a page or post.', 'powies-uptime-robot') ?><br />
		<?php _e('Use <code>[pum monitor=friendlyname]</code> to show the status of a single monitor only.', 'powies-uptime-robot') ?>
    </div>
</div>

<div class="postbox">
<h3><?php _e('About', 'powies-uptime-robot') ?></h3>
	<div class="inside" style="overflow:auto">
		<div style="float:left;margin-right: 10px; display:inline;">
		<!-- www -->
		WWW: <a href="https://powie.de">powie.de</a>
		</div>
		<div style="float:left;margin-right: 10px; display:inline;">
		<!-- twitter -->
		<a href="https://twitter.com/PowieT" class="twitter-follow-button" data-show-count="false" data-lang="de">@PowieT folgen</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<div style="float:left;margin-right: 10px; display:inline;">
		<!-- fb -->
		<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpowiede&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:35px;" allowTransparency="true"></iframe>
		</div>

		<div style="float:left;margin-right: 10px; display:inline;">
			<div class="g-plusone" data-size="small" data-href="http://www.powie.de"></div>
			<script type="text/javascript">
			  (function() {
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/plusone.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>

    </div>
</div>

</div>

</div>