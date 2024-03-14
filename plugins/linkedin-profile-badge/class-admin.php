<?php
define("PLUGIN_NAME","Linkedin Profile Badge");
define("PLUGIN_TAGLINE","Add a Linkedin Profile Badge to your WordPress Site!");
define("PLUGIN_URL","http://3doordigital.com/wordpress/plugins/linkedin-profile-badge/");
define("EXTEND_URL","http://wordpress.org/extend/plugins/linkedin-profile-badge/");
define("AUTHOR_TWITTER","alexmoss");
define("DONATE_LINK","https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WFVJMCGGZTDY4");

add_action('admin_init', 'linkedinbadge_init' );
function linkedinbadge_init(){
	register_setting( 'linkedinbadge_options', 'linkedinbadge' );
	$new_options = array(
		'JS' => 'on',
		'url' => 'http://www.linkedin.com/in/alexmoss1',
		'mode' => 'inline',
		'liname' => 'Alex Moss',
		'connections' => 'off',
		'behavior' => 'hover'
	);
	add_option( 'linkedinbadge', $new_options );
}


add_action('admin_menu', 'show_linkedinbadge_options');
function show_linkedinbadge_options() {
	add_options_page('Linkedin Profile Badge Options', 'Linkedin Profile Badge', 'manage_options', 'linkedinbadge', 'linkedinbadge_options');
}

function linkedinbadge_fetch_rss_feed() {
    include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed("http://3doordigital.com/feed");
	if ( is_wp_error($rss) ) { return false; }
	$rss_items = $rss->get_items(0, 3);
    return $rss_items;
}

function libadge_admin_notice(){
$options = get_option('linkedinbadge');
if ($options['url']=="http://www.linkedin.com/in/alexmoss1") {
	$liadminurl = get_admin_url()."options-general.php?page=linkedinbadge";
    echo '<div class="error">
       <p>Please enter your Linkedin URL for Linkedin Profile Badge to work properly. <a href="'.$liadminurl.'"><input type="submit" value="Enter Linkedin URL" class="button-secondary" /></a></p>
    </div>';
}
}
add_action('admin_notices', 'libadge_admin_notice');

// ADMIN PAGE
function linkedinbadge_options() {
$domain = get_option('siteurl');
$domain = str_replace('http://', '', $domain);
$domain = str_replace('www.', '', $domain);
?>
    <link href="<?php echo plugins_url( 'admin.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">
    <div class="pea_admin_wrap">
        <div class="pea_admin_top">
            <h1><?php echo PLUGIN_NAME?> <small> - <?php echo PLUGIN_TAGLINE?></small></h1>
        </div>

        <div class="pea_admin_main_wrap">
            <div class="pea_admin_main_left">
                <div class="pea_admin_signup">
                    Want to know about updates to this plugin without having to log into your site every time? Want to know about other cool plugins we've made? Add your email and we'll add you to our very rare mail outs.

                    <!-- Begin MailChimp Signup Form -->
                    <div id="mc_embed_signup">
                    <form action="http://peadig.us5.list-manage2.com/subscribe/post?u=e16b7a214b2d8a69e134e5b70&amp;id=eb50326bdf" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div class="mc-field-group">
                        <label for="mce-EMAIL">Email Address
                    </label>
                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL"><button type="submit" name="subscribe" id="mc-embedded-subscribe" class="pea_admin_green">Sign Up!</button>
                    </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>	<div class="clear"></div>
                    </form>
                    </div>

                    <!--End mc_embed_signup-->
                </div>

		<form method="post" action="options.php" id="options">
			<?php settings_fields('linkedinbadge_options'); ?>
			<?php $options = get_option('linkedinbadge');
if (!isset($options['url'])) {$options['url'] = "http://www.linkedin.com/in/alexmoss1";}
?>

<?php if ($options['url']=="http://www.linkedin.com/in/alexmoss1") { ?>
<div class="error">
			<h3 class="title">You Need to Set Up your Linkedin URL!</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row">Your Linkedin URL</th>
					<td><strong>Linkedin URL: </strong><input id="url" type="text" name="linkedinbadge[url]" value="<?php echo $options['url']; ?>" size="50" /><br><small>you can find your Public Linkedin Profile URL by clicking <a href="http://www.linkedin.com/profile/edit" target="_blank">here</a>. Just below your profile picture you should see a URL - enter that URL in the space above. For now, my Linkedin Profile URL is shown above</small><br><br>
</td>
				</tr>
			</table>
</div>
<?php } ?>
			<h3 class="title">Initial Setup</h3>
			<table class="form-table">
<?php if ($options['url']!="http://www.linkedin.com/in/alexmoss1") { ?>
				<tr valign="top"><th scope="row">Your Linkedin URL</th>
					<td><strong>Linkedin URL: </strong><input id="url" type="text" name="linkedinbadge[url]" value="<?php echo $options['url']; ?>" size="50" /><br><small>you can find your Public Linkedin Profile URL by clicking <a href="http://www.linkedin.com/profile/edit" target="_blank">here</a>. Just below your profile picture you should see a URL - enter that URL in the space above. For now, my Linkedin Profile URL is shown above</small><br><br>
</td>
				</tr>
<?php } ?>
				<tr valign="top"><th scope="row"><label for="JS">Enable Linkedin JS call</label></th>
					<td><input id="JS" name="linkedinbadge[JS]" type="checkbox" value="on" <?php checked('on', $options['JS']); ?> /> <small>only disable this if you already have Linkedin's JS call elsewhere on the site.</small></td>
				</tr>
			</table>

			<h3 class="title">Display Options</h3>
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="mode">Default Display Mode</label></th>
					<td>
						<select name="linkedinbadge[mode]">
							  <option value="inline"<?php if ($options['mode'] == 'inline') { echo ' selected="selected"'; } ?>>Inline</option>
							  <option value="icon"<?php if ($options['mode'] == 'icon') { echo ' selected="selected"'; } ?>>Icon</option>
						</select>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><label for="liname">Default Name</label></th>
					<td><input id="liname" type="text" name="linkedinbadge[liname]" value="<?php echo $options['liname']; ?>" /> <small>this is the name that will appear next to the icon. This option only works when in Icon mode</small></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="connections">Show Connections</label></th>
					<td><input id="connections" name="linkedinbadge[connections]" type="checkbox" value="on" <?php checked('on', $options['connections']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="behavior">Behavior</label></th>
					<td>
						<select name="linkedinbadge[behavior]">
							  <option value="click"<?php if ($options['behavior'] == 'click') { echo ' selected="selected"'; } ?>>On Click</option>
							  <option value="hover"<?php if ($options['behavior'] == 'hover') { echo ' selected="selected"'; } ?>>On Hover</option>
						</select> <small>this option does not apply with the Inline display mode.</small>
					</td>
				</tr>
			</table>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

               <div class="pea_admin_box">
			<h3 class="title">Using the Shortcode</h3>
			<table class="form-table">
				<tr valign="top"><td>
<p>The settings above are for automatic insertion of the Linkedin Profile badge.</p>
<p>You can insert the badge/icon manually in any page or post or template by simply using the shortcode <strong>[linkedinbadge]</strong>. To enter the shortcode directly into templates using PHP, enter <strong>echo do_shortcode('[linkedinbadge]');</strong></p>
<p>You can also use the options below to override the the settings above.</p>
<ul>
<li><strong>URL</strong> - the Linkedin public URL</li>
<li><strong>mode</strong> -  choose between <strong>inline</strong> or <strong>icon</strong></li>
<li><strong>liname</strong> - if you have chosen <strong>icon</strong> mode then the name will appear to the right of the icon</li>
<li><strong>connections</strong> - on/off</li>
</ul>
<p>Here's an example of using the shortcode:<br><code>[linkedinbadge URL="http://www.linkedin.com/in/alexmoss1" connections="on" mode="icon" liname="Alex Moss"]</code></p>
<p>You can also insert the shortcode directly into your theme with PHP:<br><code>&lt;?php echo do_shortcode('[linkedinbadge URL="http://www.linkedin.com/in/alexmoss1" connections="on" mode="icon" liname="Alex Moss"]'); ?&gt;</code>

					</td>
				</tr>
			</table>
</div>

</div>
            <div class="pea_admin_main_right">
                <div class="pea_admin_logo">

            <a href="http://3doordigital.com/?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Facebook%2BComments%2BAdmin" target="_blank"><img src="<?php echo plugins_url( '3dd-logo.png' , __FILE__ ); ?>" width="250" height="92" title="3 Door Digital"></a>

                </div>


                <div class="pea_admin_box">
                    <h2>Like this Plugin?</h2>
<a href="<?php echo EXTEND_URL; ?>" target="_blank"><button type="submit" class="pea_admin_green">Rate this plugin	&#9733;	&#9733;	&#9733;	&#9733;	&#9733;</button></a><br><br>
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=181590835206577";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                    <div class="fb-like" data-href="<?php echo PLUGIN_URL; ?>" data-send="true" data-layout="button_count" data-width="250" data-show-faces="true"></div>
                    <br>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo PLUGIN_URL; ?>" data-text="Just been using <?php echo PLUGIN_NAME; ?> #WordPress plugin" data-via="<?php echo AUTHOR_TWITTER; ?>" data-related="WPBrewers">Tweet</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <br>
<a href="http://bufferapp.com/add" class="buffer-add-button" data-text="Just been using <?php echo PLUGIN_NAME; ?> #WordPress plugin" data-url="<?php echo PLUGIN_URL; ?>" data-count="horizontal" data-via="<?php echo AUTHOR_TWITTER; ?>">Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>
<br>
                    <div class="g-plusone" data-size="medium" data-href="<?php echo PLUGIN_URL; ?>"></div>
                    <script type="text/javascript">
                      window.___gcfg = {lang: 'en-GB'};

                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>
                    <br>
                    <su:badge layout="3" location="<?php echo PLUGIN_URL?>"></su:badge>
                    <script type="text/javascript">
                      (function() {
                        var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
                        li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
                      })();
                    </script>
                </div>

<center><a href="<?php echo DONATE_LINK; ?>" target="_blank"><img class="paypal" src="<?php echo plugins_url( 'paypal.gif' , __FILE__ ); ?>" width="147" height="47" title="Please Donate - it helps support this plugin!"></a></center>

                <div class="pea_admin_box">
                    <h2>About the Author</h2>

                    <?php
                    $default = "http://reviews.evanscycles.com/static/0924-en_gb/noAvatar.gif";
                    $size = 70;
                    $alex_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( "alex@peadig.com" ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
                    ?>

                    <p class="pea_admin_clear"><img class="pea_admin_fl" src="<?php echo $alex_url; ?>" alt="Alex Moss" /> <h3>Alex Moss</h3><br><a href="https://twitter.com/alexmoss" class="twitter-follow-button" data-show-count="false">Follow @alexmoss</a>
<div class="fb-subscribe" data-href="https://www.facebook.com/alexmoss1" data-layout="button_count" data-show-faces="false" data-width="220"></div>
</p>
                    <p class="pea_admin_clear">Alex Moss is the Co-Founder and Technical Director of 3 Door Digital. With offices based in Manchester, UK and Tel Aviv, Israel he manages WordPress development as well as technical aspects of digital consultancy. He has developed several WordPress plugins (which you can <a href="http://3doordigital.com/wordpress/plugins/?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Facebook%2BComments%2BAdmin" target="_blank">view here</a>) totalling over 200,000 downloads.</p>
</div>

                <div class="pea_admin_box">
                    <h2>More from 3 Door Digital</h2>
    <p class="pea_admin_clear">
                    <?php
$linkedinbadgefeed = linkedinbadge_fetch_rss_feed();
                echo '<ul>';
                foreach ( $linkedinbadgefeed as $item ) {
			    	$url = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), $protocolls=null, 'display' ) );
					echo '<li>';
					echo '<a href="'.$url.'?utm_source=<?php echo $domain; ?>&utm_medium=referral&utm_campaign=Facebook%2BComments%2BRSS">'. esc_html( $item->get_title() ) .'</a> ';
					echo '</li>';
			    }
                echo '</ul>';
                    ?></p>
                </div>


            </div>
        </div>
    </div>



<?php
}

?>