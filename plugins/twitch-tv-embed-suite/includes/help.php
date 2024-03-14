<?php $plugin_url = plugins_url();?>
<div id="wrap">
<h1 id="twitchh1">Twitch TV Embed Settings</h1>

<div class="twitch-help">
<h2>Help</h2>
<p>The plugin contains a series of shortcodes to allow you to embed a Twitch TV Live Stream, Chat, and past stream list feed to your page.</p>
<h3>Streamlist</h3>
<p>This shortcode outputs a series of thumbnails taken directly from the specified Twitch TV stream list and displays them in order from newest to oldest. While it does not play the video on screen,
it links to the video at Twitch.</p>
<ul>
<li>To add a Twitch TV stream list to your posts, pages, or widgets use the following shortcode:<br/><code>[plumwd_twitch_streamlist]</code></li>
<li>To add the Twitch TV stream plugin to your WordPress theme use the following shortcode inside your template:<br/><code>echo do_shortcode('[plumwd_twitch_streamlist]');</code></li>
</ul>

The plugin also supports several attributes for the shortcode, below is a listing of the attributes and what their purpose is:
<ol>
<li>channel -&gt; this <strong>must</strong> be set or the feed will not display. Usage:<br/><code>[plumwd_twitch_streamlist channel="plumwd"]</code></li>
<li>videonum -&gt; The number of streams to display. Will return the most recent streams in order from newest to oldest. Usage:<br/><code>[plumwd_twitch_display channel="plumwd" videonum="4"]</code></li>
<li>display -&gt; accepts two different options: horizontal or vertical. Usage:<br/><code>[plumwd_twitch_streamlist channel="plumwd" display="horizonal"]</code></li>
</ol>
<h3>Twitch Stream</h3>
<p>This shortcode embeds the Twitch TV live stream player.</p>
<ul>
<li>To add a Twitch TV live stream player to your posts, pages, or widgets use the following shortcode:<br/><code>[plumwd_twitch_stream]</code></li>
<li>This shortcode takes no attributes and can be inserted using the button <img src="<?php echo $plugin_url;?>/twitch-tv-embed-suite/images/tv.png" alt="tv button"/> located in WordPress WYSIWYG editor, or by inserting the shortcode to your theme or php enabled widget:<br/>
<code>echo do_shortcode('[plumwd_twitch_stream]');</code></li>
</ul>
<h3>Twitch Chat</h3>
<p>This shortcode embeds the Twitch TV live stream chat box.</p>
<ul>
<li>To add a Twitch TV live stream box to your posts, pages, or widgets use the following shortcode:<br/><code>[plumwd_twitch_chat]</code></li>
<li>This shortcode takes no attributes and can be inserted using the button <img src="<?php echo $plugin_url;?>/twitch-tv-embed-suite/images/chat.png" alt="chat button"/> located in WordPress WYSIWYG editor, or by inserting the shortcode to your theme or php enabled widget:<br/>
<code>echo do_shortcode('[plumwd_twitch_chat]');</code></li>
</ul>
<h3>Twitch TV Widget</h3>
<p>This widget is to display the Twitch TV Stream status (offline/online).</p>
<p>The widget includes the option to add a title, and also to display sharing buttons on both Facebook and Twitter. Add a Twitter username to send the tweet via @username.
</div>
<div style="width:45%;float:right;">
  <div class="metabox-holder postbox" style="padding-top:0;margin:10px;cursor:auto;width:30%;float:left;min-width:320px">
    <h3 class="hndle" style="cursor: auto;"><span><?php  _e( 'Thank you for using Twitch Embed Suite', 'twitch-tv-embed-suite' ); ?></span></h3>
    <div class="inside twitch-tv-embed-suite">
      <img src="<?php echo $plugin_url;?>/twitch-tv-embed-suite/images/preview.jpg" alt="Twitch Preview" />
  	  <?php _e( 'Please support Plumeria Web Design so we can continue making rocking plugins for you. If you enjoy this plugin, please consider offering a small donation. We also look forward
	  to your comments and suggestions so that we may further improve our plugins to better serve you.', 'twitch-tv-embed-suite' ); ?>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SLYFNBZU8V87W">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
    </div>
  </div>
</div>

</div>
