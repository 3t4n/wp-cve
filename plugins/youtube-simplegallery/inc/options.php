<?php

/**
 * ILC Tabbed Settings Page
 */

add_action( 'init', 'ytsg_admin_init' );
add_action( 'admin_menu', 'ytsg_settings_page_init' );

function ytsg_admin_init() {
	$settings = get_option( "youtube_gallery_option" );
	if ( empty( $settings ) ) {
		$settings = array(
			'thumbwidth' => '135',
			'cols' => '4',
			'autotitles' => 'fetch',
			'title' => 'below',
			'pb' => 'usepb',
			'jq' => 'usejq',
			'css' => 'usecss',
			'titlecss' => "text-align: center;\nfont-size: 1em;\nfont-style: italic;",

			'width' => '640',
			'height' => '370',
			'start' => 'autoplay',
			'hd' => 'usehd',
			'related' => 'dontshow',
			'thickbox' => 'thickbox',
		);
		add_option( "youtube_gallery_option", $settings, '', 'yes' );
	}	
}

function ytsg_settings_page_init() {
	$settings_page = add_options_page( 'YouTube SimpleGallery', 'YouTube SimpleGallery', '10', 'youtube-gallery-options', 'youtubegallery_options' );
	add_action( "load-{$settings_page}", 'ytsg_load_settings_page' );
}

function ytsg_load_settings_page() {
	if ( isset($_POST["youtube-gallery-options-submit"]) && $_POST["youtube-gallery-options-submit"] == 'Y' ) {
		check_admin_referer( "youtube-gallery-options" );
		ytsg_save_settings();
		$url_parameters = isset($_GET['tab'])? 'updated=true&tab='.$_GET['tab'] : 'updated=true';
		wp_redirect(admin_url('options-general.php?page=youtube-gallery-options&'.$url_parameters));
		exit;
	}
}

function ytsg_save_settings() {
	global $pagenow;
	$settings = get_option( "youtube_gallery_option" );

	if ( $pagenow == 'options-general.php' && $_GET['page'] == 'youtube-gallery-options' ){ 
		if ( isset ( $_GET['tab'] ) )
			$tab = $_GET['tab']; 
		else
			$tab = 'thumbnails'; 

		switch ( $tab ){ 
			case 'thumbnails' :
				$settings['thumbwidth'] = $_POST['youtubegallery_thumbwidth'];
				$settings['cols'] = $_POST['youtubegallery_cols'];
				$settings['autotitles'] = $_POST['youtubegallery_autotitles'];
				$settings['title'] = $_POST['youtubegallery_title'];
				$settings['pb'] = $_POST['youtubegallery_pb'];
				$settings['jq'] = $_POST['youtubegallery_jq'];
				$settings['css'] = $_POST['youtubegallery_css'];
				$settings['titlecss'] = $_POST['youtubegallery_titlecss'];
				$settings['timthumb'] = $_POST['youtubegallery_timthumb'];
			break; 
			case 'videos' : 
				$settings['width'] = $_POST['youtubegallery_width'];
				$settings['height'] = $_POST['youtubegallery_height'];
				$settings['start'] = $_POST['youtubegallery_start'];
				$settings['hd'] = $_POST['youtubegallery_hd'];
				$settings['related'] = $_POST['youtubegallery_related'];
				$settings['thickbox'] = $_POST['youtubegallery_thickbox'];
				$settings['openlinks'] = $_POST['youtubegallery_openlinks'];
				$settings['error'] = $_POST['youtubegallery_error'];
				$settings['api'] = $_POST['youtubegallery_api'];
			break;
		}
	}
	
	$updated = update_option( "youtube_gallery_option", $settings );
}

function ytsg_admin_tabs( $current = 'thumbnails' ) { 
	global $tabs;
	$tabs = array( 
		'thumbnails' => 'Thumbnails', 
		'videos' => 'Videos', 
		'usage' => 'Usage', 
		'faq' => 'FAQ', 
//	   	'changelog' => 'Changelog', 
		'donate' => 'Donate' 
	); 
	$links = array();
	echo '<div id="icon-options-general" class="icon32"><br></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=youtube-gallery-options&tab=$tab'>$name</a>";
		
	}
	echo '</h2>';
}

function youtubegallery_options() {
	global $pagenow, $youtube_gallery_version;
	$youtube_gallery_options = get_option('youtube_gallery_option');
	?>
	
	<div class="wrap">
		<h2>YouTube SimpleGallery (<a href="?page=youtube-gallery-options&tab=changelog">version <?php echo $youtube_gallery_version; ?></a>)</h2>
		
		<?php
			if ( isset ( $_GET['tab'] ) ) ytsg_admin_tabs($_GET['tab']); else ytsg_admin_tabs('thumbnails');
		?>

		<div id="poststuff">
		<?php 	
			global $current_user, $youtube_gallery_url;
			$user_id = $current_user->ID;
			if ( !get_user_meta($user_id, 'YTSG_20_whatsnew_ignore') ):
		?>
			<div id="whatsnew">
			<h2>YouTube SimpleGallery – new and improved!</h2>
			<ul>
				<li><strong>YouTube SimpleGallery</strong> and <strong>Vimeo SimpleGallery</strong> have merged, i.e. they now have the same codebase. Old shortcodes still functional.</li>
				<li><strong>NEW FEATURE:</strong> It is now possible to combine video services, i.e. you can add links from both <strong>YouTube</strong> and <strong>Vimeo</strong> in <strong>the same gallery</strong>.</li>
				<li><strong>NEW FEATURE:</strong> Option to automatically fetch titles from video service.</li>
				<li><strong>NEW FEATURE:</strong> User-defined attributes in shortcode overrides default settings: <code>cols=x</code> and <code>thumbwidth=y</code> allows for galleries with different thumb sizes, etc.</li>
				<li><strong>NEW SHORTCODE:</strong> <code>[youtubeuserfeed user=username service=youtube]</code> – outputs a gallery from a user’s video feed – works with both YouTube (<span class="description">service=youtube</span>) and Vimeo (<span class="description">service=vimeo</span>).</li>
			</ul>
			<a href="options-general.php?page=youtube-gallery-options&YTSG_20_whatsnew_ignore=0" class="button-secondary">Dismiss</a>
			</div><!-- #whatsnew -->
		<?php endif; ?>

				<?php
				if ( $pagenow == 'options-general.php' && $_GET['page'] == 'youtube-gallery-options' ){ 
				
					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
					else $tab = 'thumbnails'; 

					global $tabs;
//					echo '<h2>'.$tabs[$tab].'</h2>';

					switch ( $tab ){
						case 'thumbnails' :
							?>
							<form method="post" action="<?php admin_url( 'options-general.php?page=youtube-gallery' ); ?>">
							<?php wp_nonce_field( "youtube-gallery-options" ); ?>
				
							<table class="form-table" style="width: auto;">
								<tr>
									<th scope="row"><label for="youtubegallery_thumbwidth">Thumbnail width:</label></th>
									<td>
										<input type="number" step="1" min="50" max="480" id="youtubegallery_thumbwidth" name="youtubegallery_thumbwidth" value="<?php echo $youtube_gallery_options['thumbwidth'] ?>" class="small-text">
										<span class="description">Should not exceed 480px, the widest thumbnail supplied by YouTube. <!-- Override: <code>[youtubegallery <strong>thumbwidth=x</strong>]</code> --></span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="youtubegallery_cols">Columns:</label></th>
									<td>
										<input type="number" step="1" min="0" id="youtubegallery_cols" name="youtubegallery_cols" value="<?php echo $youtube_gallery_options['cols'] ?>" class="small-text">
										<span class="description">Number of thumbs per row, 0 = free float. <!-- Override: <code>[youtubegallery <strong>cols=x</strong>]</code> --></span>
									</td>
								</tr>
								<tr>
									<th scope="row">Automatic titles:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Automatic titles: </span></legend>
										<label for="youtubegallery_autotitles"><input type="checkbox" name="youtubegallery_autotitles" id="youtubegallery_autotitles" value="fetch"<?php if($youtube_gallery_options['autotitles'] == 'fetch') echo' checked'; ?>>
										Fetch titles from service</label>
										<p class="description">User-added titles will override titles from service</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Display titles:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Display titles</span></legend>
										<p>
											<label><input name="youtubegallery_title" type="radio" value="above"<?php if($youtube_gallery_options['title'] == 'above') echo' checked'; ?> /> Above thumbnails</label><br />
											<label><input name="youtubegallery_title" type="radio" value="below"<?php if($youtube_gallery_options['title'] == 'below') echo' checked'; ?> /> Below thumbnails</label>
										</p>
									</fieldset></td>

								</tr>
								<tr>
									<th scope="row">Play-button:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Show play-button: </span></legend>
										<label for="youtubegallery_pb"><input type="checkbox" name="youtubegallery_pb" id="youtubegallery_pb" value="usepb"<?php if($youtube_gallery_options['pb'] == 'usepb') echo' checked'; ?>>
										Display play-button over thumbnails</label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Center thumbnails:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Center thumbnails: </span></legend>
										<label for="youtubegallery_jq"><input type="checkbox" name="youtubegallery_jq" id="youtubegallery_jq" value="usejq"<?php if($youtube_gallery_options['jq'] == 'usejq') echo' checked'; ?>>
										Center thumbnails on Post/Page</label>
										<p class="description">If disabled, thumbnails are left-aligned</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Use CSS:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Use CSS: </span></legend>
										<label for="youtubegallery_css"><input type="checkbox" name="youtubegallery_css" id="youtubegallery_css" value="usecss"<?php if($youtube_gallery_options['css'] == 'usecss') echo' checked'; ?>>
										Use CSS included with plugin</label>
										<p class="description">Disable if you want to use your own CSS</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="youtubegallery_titlecss">Titles CSS:</label></th>
									<td>
										<p>
										<textarea name="youtubegallery_titlecss" rows="5" cols="50" id="youtubegallery_titlecss" class="regular-text code"><?php echo $youtube_gallery_options['titlecss'] ?></textarea>
										<br /><span class="description">Styling of captions; font-size, font-style, etc.</span>
										</p>
										
									</td>
								</tr>
								<tr class="alt">
									<th scope="row">Disable Timthumb:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Disable Timthumb: </span></legend>
										<label for="youtubegallery_timthumb"><input type="checkbox" name="youtubegallery_timthumb" id="youtubegallery_timthumb" value="off"<?php if($youtube_gallery_options['timthumb'] == 'off') echo' checked'; ?>>
										Do not use Timthumb on thumbnails</label>
										<p class="description">
											DEBUG: Disable only if you have problems with thumbnails not showing.<br />
											<a href="options-general.php?page=youtube-gallery-options&tab=faq">Read more in the FAQ</a>
										</p>
										</fieldset>
									</td>
								</tr>
							</table>
							
							<p class="submit" style="clear: both;">
								<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
								<input type="hidden" name="youtube-gallery-options-submit" value="Y" />
							</p>
							</form>
							<?php
						break; 
						case 'videos' : 
							?>
							<form method="post" action="<?php admin_url( 'options-general.php?page=youtube-gallery' ); ?>">
							<?php wp_nonce_field( "youtube-gallery-options" ); ?>
				
							<table class="form-table" style="width: auto;">
								<tr>
									<th scope="row"><label for="youtubegallery_width">Video width:</label></th>
									<td>
										<input type="number" step="1" min="50" id="youtubegallery_width" name="youtubegallery_width" value="<?php echo $youtube_gallery_options['width'] ?>" class="small-text">
										<span class="description">Width of embedded video</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="youtubegallery_height">Video height:</label></th>
									<td>
										<input type="number" step="1" min="50" id="youtubegallery_height" name="youtubegallery_height" value="<?php echo $youtube_gallery_options['height'] ?>" class="small-text">
										<span class="description">Height of embedded video</span>
									</td>
								</tr>

								<?php if($youtube_gallery_options['thickbox'] == 'fancybox'): ?>
								<tr>
									<td colspan="2" class="highlight">
									NOTE: Fancybox overrides the width/height setting above. Go to <a href="options-general.php?page=fancybox-for-wordpress#fbfw-other">Fancybox Options</a> to specify width/height.
									</td>
								</tr>
								<?php endif; ?>

								<tr>
									<th scope="row">Autoplay</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Autoplay: </span></legend>
										<label for="youtubegallery_start"><input type="checkbox" name="youtubegallery_start" id="youtubegallery_start" value="autoplay"<?php if($youtube_gallery_options['start'] == 'autoplay') echo' checked'; ?>>
										Start videos on click</label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">HD quality:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>HD: </span></legend>
										<label for="youtubegallery_hd"><input type="checkbox" name="youtubegallery_hd" id="youtubegallery_hd" value="usehd"<?php if($youtube_gallery_options['hd'] == 'usehd') echo' checked'; ?>>
										Embed videos in HD quality</label>
										<p class="description">Attempt to embed HD version where available</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Related videos:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Related video: </span></legend>
										<label for="youtubegallery_related"><input type="checkbox" name="youtubegallery_related" id="youtubegallery_related" value="dontshow"<?php if($youtube_gallery_options['related'] == 'dontshow') echo' checked'; ?>>
										Do not show related videos</label>
										<p class="description">Only relevant for YouTube</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Effect:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Effect: </span></legend>
										<p>
											<label><input name="youtubegallery_thickbox" type="radio" value="shadowbox"<?php if($youtube_gallery_options['thickbox'] == 'shadowbox') echo' checked'; ?> /> <a href="http://wordpress.org/extend/plugins/shadowbox-js/">Shadowbox JS</a></label><br />
											<label><input name="youtubegallery_thickbox" type="radio" value="fancybox"<?php if($youtube_gallery_options['thickbox'] == 'fancybox') echo' checked'; ?> /> <a href="http://wordpress.org/extend/plugins/fancybox-for-wordpress/">FancyBox for WordPress</a></label><br />
											<label><input name="youtubegallery_thickbox" type="radio" value="thickbox"<?php if($youtube_gallery_options['thickbox'] == 'thickbox') echo' checked'; ?> /> <a href="http://wordpress.org/extend/plugins/thickbox/">Thickbox</a></label><br />
											<label><input name="youtubegallery_thickbox" type="radio" value="none"<?php if($youtube_gallery_options['thickbox'] == 'none') echo' checked'; ?> /> None – <span class="description"><input type="checkbox" name="youtubegallery_openlinks"<?php if($youtube_gallery_options['openlinks']) echo' checked'; ?>> Open links in new window/tab</span></label><br />
										</p>
									</fieldset></td>
								</tr>
								<tr>
									<th scope="row">Disable Error Reporting:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Disable Error Reporting: </span></legend>
										<label for="youtubegallery_error"><input type="checkbox" name="youtubegallery_error" id="youtubegallery_error" value="off"<?php if($youtube_gallery_options['error'] == 'off') echo' checked'; ?>>
										Turn of error reporting on broken videos</label>
										<p class="description">
											When off, broken videos are skipped.
										</p>
										</fieldset>
									</td>
								</tr>
								<tr>
									<th scope="row">Disable YouTube API:</th>
									<td>
										<fieldset><legend class="screen-reader-text"><span>Disable YouTube API: </span></legend>
										<label for="youtubegallery_api"><input type="checkbox" name="youtubegallery_api" id="youtubegallery_api" value="off"<?php if($youtube_gallery_options['api'] == 'off') echo' checked'; ?>>
										Do not load video info from YouTube’s API</label>
										<p class="description">
											Improves load time on galleries with YouTube-videos. (Not relevent for Vimeo.)<br />
											IMPORTANT: Will disable automatic titles and error reporting on YouTube-videos!
											
										</p>
										</fieldset>
									</td>
								</tr>
							</table>
							
							<p class="submit" style="clear: both;">
								<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
								<input type="hidden" name="youtube-gallery-options-submit" value="Y" />
							</p>
							</form>

							<?php 
							if($youtube_gallery_options['thickbox']=='shadowbox'){
								if(!class_exists('Shadowbox')){
								echo '<div class="error">';
								echo '<p><strong>Important notice:</strong> Shadowbox JS is selected as the effect plugin, but it has not been installed and/or actived. Please install and activate <a href="http://wordpress.org/extend/plugins/shadowbox-js/">Shadowbox JS</a> if you want to use it with this plugin.</p>'; 
								echo '</div>';
								}
							}
							?>

							<?php 
							if($youtube_gallery_options['thickbox']=='fancybox'){
								if(!function_exists('mfbfw_init')){
								echo '<div class="error">';
								echo '<p><strong>Important notice:</strong> FancyBox for WordPress is selected as the effect plugin, but it has not been installed and/or actived. Please install and activate <a href="http://wordpress.org/extend/plugins/fancybox-for-wordpress/">FancyBox for WordPress</a> if you want to use it with this plugin.</p>'; 
								echo '</div>';
								}
							}
							?>

							<?php 
							if($youtube_gallery_options['thickbox']=='thickbox'){
								if(!function_exists('is_thickbox_enabled')){
								echo '<div class="error">';
								echo '<p><strong>Important notice:</strong> Thickbox is selected as the effect plugin, but it has not been installed and/or actived. Please install and activate <a href="http://wordpress.org/extend/plugins/thickbox/">Thickbox</a> if you want to use it with this plugin.</p>'; 
								echo '</div>';
								}
							}
							?>

							<?php
						break; 
						case 'usage' : 
							?>
							<div id="accordion">

								<h3>Galleries</h3>
								<div>
									<p><strong>You can now combine links from YouTube and Vimeo in the same gallery.</strong></p>
									<p>To embed a gallery in a Post use the following code:</p>
									<code>
										[youtubegallery]<br />
										http://www.youtube.com/watch?v=cRdxXPV9GNQ<br />
										http://vimeo.com/13470805<br />
										http://www.youtube.com/watch?v=jJK-G9-dLzw<br />
										http://www.youtube.com/watch?v=S4aqM_wu6Ns<br />
										http://vimeo.com/68180971<br />
										[/youtubegallery]		
									</code>
		
									<p>If you want to add titles to the videos, add it before the link and separate with | (pipe), like this:</p>

									<code>
										[youtubegallery]<br />
										<strong>Avatar Trailer HD|</strong>http://www.youtube.com/watch?v=cRdxXPV9GNQ<br />
										<strong>Eyes Close|</strong>http://vimeo.com/13470805<br />
										<strong>The Fast Show: Unlucky Alf|</strong>http://www.youtube.com/watch?v=jJK-G9-dLzw<br />
										<strong>Jožin z bažin|</strong>http://www.youtube.com/watch?v=S4aqM_wu6Ns<br />
										<strong>Mirage|</strong>http://vimeo.com/68180971<br />
										[/youtubegallery]		
									</code>
									
									<p>NOTE: If titles are added before videos, these will override titles fetched automatically from services.</p>
								</div>
		
		
		
								<h3>User Feeds</h3>
								<div>
									<p>To set up a gallery that subscribes to a user’s feed, use the following shortcode:</p>
									<code>[<strong>youtubeuserfeed</strong> user=username service=youtube]</code>
									<p>Note that both <span class="description">user</span> and <span class="description">service</span> are <strong>required</strong> for the feed to work. Service is either <code>youtube</code> or <code>vimeo</code>.</p>
									<p>Optional attributes are <span class="description">maxitems=x</span> where x is the number of items to fetch:</p><code>[youtubeuserfeed user=username service=youtube <strong>maxitems=4</strong>]</code></p>
								</div>


							
								<h3>Overrides</h3>
								<div>
									<p>Overrides are a way of suppressing the default settings of the plugin, with attributes of your own.</p> 

									<p>Override columns with <span class="description">cols=x</span>, where x is the number of thumbs per row:<br />
									<code>[youtubegallery <strong>cols=8</strong></span>]</code></p>

									<p>Override thumbnail width with <span class="description">thumbwidht=x</span>, where x is a pixel value:<br />
									<code>[youtubegallery <strong>thumbwidth=100]</strong></code></p>
									
									<p>Fetch titles from service with <span class="description">autotitles=fetch</span> (or <span class="description">autotitles=false</span> to not fetch):<br />
									<code>[youtubegallery <strong>autotitles=fetch]</strong></code></p>
									
									<p>And, of course, you can combine them: <code>[youtubegallery cols=8 thumbwidth=100 autotitles=false]</code>

									<p>These are also applicable for the <code>[youtubeuserfeed]</code> shortcode.</p>
								</div>


							
								<h3>Widgets</h3>
								<div>
									<p>Add a «YouTube SimpleGallery» widget to your sidebar(s), add links with linebreaks in the Links-area. You can add titles in the same way as for Posts.</p>
									<p>Widgets have their own fields to define columns and thumbnail width.</p>
									
									<p class="description">You cannot use shortcodes in Widgets.</p>
								</div>
							
							

								<h3>Supported Video Services</h3>
								<div>
									<p>Supported services are <a href="http://www.youtube.com/">YouTube</a> and <a href="http://vimeo.com/">Vimeo</a>. Other services might be added, but a requirement is that they can deliver video streams in a HTML5-compatible format.</p>
									<p>Note that it is possible to combine videos from both services in galleries.</p>
								</div>



							</div><!-- #accordion -->

							<?php
						break;
						case 'faq' : 
							?>
							<div class="ytsg_admin_wrap">
								<h2>Shadowbox JS/FancyBox for WordPress/Thickbox doesn’t work properly. What’s wrong?</h2>
								<p>Check if your current Theme has both <code>wp_head()</code> in header.php
								and <code>wp_footer()</code> in footer.php. Both are usually required for these scripts to function properly. 
								Also note that some plugins aren’t buddies and create conflicts with each other; try disabling the plugins for the effects you don’t use.
								</p>

								<h2>Thumbnails aren’t working. What’s up?<br />
								Or: Before you disable Timthumb, read this:</h2>
								<p>From version 2.0, <strong>YouTube SimpleGallery</strong> is employing <a href="https://code.google.com/p/timthumb/" target="_blank">Timthumb</a>
								to crop/resize thumbnails. This is mostly due to YouTube and Vimeo having a different aspect ratio on their thumbnails. 
								Timthumb may cause issues on certain webservers. If thumbnails are not showing up in your galleries, the cause might be a conflict between
								Timthumb and your webhost. Here are some things to try:</p>
								
								<ul>
									<li>Try setting permission (CHMOD) to 777 on the folder<br /> <code>wp-content/plugins/youtube-simplegallery/scripts/chache/</code></li>
									<li>Make sure <code>index.html</code> in the cache-directory has permissions set to 666. The parent folder should not have permissions higher than 644.</li>
									<li>Read more at <a href="http://www.binarymoon.co.uk/2010/11/timthumb-hints-tips/" target="_blank">TimThumb Troubleshooting Secrets</a></li>
									<li>If none of these tips help, try disabling Timthumb under <a href="options-general.php?page=youtube-gallery-options&tab=thumbnails">Thumbnails</a> (the bottom setting).
									This will result in different thumbnail sizes between YouTube and Vimeo.</li>
								</ul>
		
								<h2>Can I add a gallery to my Theme files, outside the Loop?</h2>
								<p>If you wish to add a gallery in a part of the Theme that is outside <a href="http://codex.wordpress.org/The_Loop">The Loop</a> and/or not within a Widget,
								you can use the <a href="http://codex.wordpress.org/Function_Reference/do_shortcode">do_shortcode()</a>-function like this:
								</p>
								<code>
									<strong>&lt;?php echo do_shortcode('</strong>[youtubegallery]<br />
									http://www.youtube.com/watch?v=cRdxXPV9GNQ<br />
									http://www.youtube.com/watch?v=jJK-G9-dLzw<br />
									http://www.youtube.com/watch?v=S4aqM_wu6Ns<br />
									[/youtubegallery<strong>]'); ?&gt;</strong>
								</code>
								<p>Make sure to keep the linebreaks!</p>

								<h2>I got an amazing idea for a great feature! Can you implement it? Pretty please?</h2>
								<p>The <strong>YouTube SimpleGallery</strong> is in constant development. A lot of features has been added since it’s birth, many
								of them requests, wishes and ideas from the users. If you got an idea, don’t hesitate to share it on the <a href="http://wpwizard.net/plugins/youtube-simplegallery/">plugin website</a>.</p>
		
								<h2>My problem’s not listed here! OMG! What do I do?</h2>
								<p>Don’t panic! The WordPress community is the best bunch of people in the world. Try posting your problem/question on the <a href="http://wpwizard.net/plugins/youtube-simplegallery/">plugin website</a>
								or in the <a href="http://wordpress.org/tags/youtube-simplegallery">WP forums</a>. You’ll probably get help in a jiffy!</p>

							</div>
						<?php
						break;
						case 'changelog' : 
							?>
								<div id="whatsnew">
								<h2>YouTube SimpleGallery – new and improved!</h2>
								<ul>
									<li><strong>YouTube SimpleGallery</strong> and <strong>Vimeo SimpleGallery</strong> have merged, i.e. they now have the same codebase. Old shortcodes still functional.</li>
									<li><strong>NEW FEATURE:</strong> It is now possible to combine video services, i.e. you can add links from YouTube and Vimeo in the same gallery.</li>
									<li><strong>NEW FEATURE:</strong> Automatically fetch titles from video service.</li>
									<li><strong>NEW FEATURE:</strong> User-defined attributes in shortcode overrides default settings: <code>cols=x</code> and <code>thumbwidth=y</code> allows for galleries with different thumb sizes, etc.</li>
									<li><strong>NEW SHORTCODE:</strong> <code>[youtubeuserfeed user=username service=youtube]</code> – outputs a gallery from a user’s video feed – works with both YouTube (<span class="description">service=youtube</span>) and Vimeo (<span class="description">service=vimeo</span>).</li>
								</ul>
								</div><!-- #whatsnew -->
								
							<div class="ytsg_admin_wrap">
								<h2>Changelog</h2>

								<h3>2.0.6</h3>
								<ul>
									<li>Added options to turn off error reporting and YouTube API</li>
									<li>Bug-fix for titles when autotitles = off</li>
								</ul>

								<h3>2.0.5</h3>
								<ul>
									<li>Added error reporting if video service reports video missing/broken</li>
									<li>General bug-fixes & code improvement</li>
								</ul>

								<h3>2.0.4</h3>
								<ul>
									<li>Improved employment of dynamic styles; works better with sites running cache-plugins</li>
									<li>General bug-fixes & code improvement</li>
								</ul>

								<h3>2.0.3</h3>
								<ul>
									<li>Added option to disable timthumb</li>
									<li>Bug-fix for built-in styles</li>
								</ul>

								<h3>2.0.2</h3>
								<ul>
									<li>The missing version!</li>
								</ul>

								<h3>2.0.1</h3>
								<ul>
									<li>Bug-fix for Widgets drag & drop freeze-up.</li>
								</ul>
																
								<h3>2.0</h3>
								<ul>
									<li>Merging of YouTube SimpleGallery and Vimeo SimpleGallery.</li>
									<li>Major refactoring of codebase.</li>
									<li>Complete refurbishment of Options Page.</li>
									<li>Automatic retrieval of titles from video service.</li>
									<li>User-defined attributes in shortcode overrides default settings.</li>
									<li>New shortcode: <code>[youtubeuserfeed user=username service=youtube|vimeo]</code></li>
									<li>Bug-fixes for broken thumbnails, and Widgets not updating. </li>
								</ul>
								<h3>1.6.1</h3>
								<ul>
									<li>Quick fix for new oEmbed in WP 3.3.</li>
								</ul>
								<h3>1.6</h3>
								<ul>
									<li>Added option for Play-button on thumbnails.</li>
									<li>Bug-fix for HTML/links in titles/descriptions.</li>
								</ul>
								<h3>1.5.1</h3>
								<ul>
									<li>Minor bugfix to conform with new oEmbed in WP 3.1.2.</li>
								</ul>
								<h3>1.5</h3>
								<ul>
									<li>Added option for thumbnail size</li>
									<li>Added option for columns w/breaking rows</li>
									<li>Added style option for titles</li>
								</ul>
								<h3>1.4.1</h3>
								<ul>
									<li>Fixed compatability issue with PHP 5.3.5.</li>
								</ul>
								<h3>1.4</h3>
								<ul>
									<li>Added support for Fancybox.</li>
									<li>Added option for autoplay on click.</li>
									<li>Changed all embedding to HTML5 compliant.</li>
									<li>Remodeled Settings page.</li>
								</ul>
								<h3>1.3</h3>
								<ul>
									<li>Fixes broken thumbs.</li>
									<li>Fixes broken Thickbox.</li>
									<li>Added HD option.</li>
									<li>Minor bug fixes.</li>
								</ul>
								<h3>1.2</h3>
								<ul>
									<li>Fixes bug with broken thumbs and videos when adding titles.</li>
								</ul>
								<h3>1.1</h3>
								<ul>
									<li>Added option to open links in new window/tab when going directly to YouTube.com</li>
								</ul>
								<h3>1.0</h3>
								<ul>
									<li>1st official release.</li>
									<li>Option for Shadowbox JS added.</li>
									<li>Bugfixes: Fixed broken thumbnails. Fixed URIs with special characters.</li>
								</ul>
								<h3>0.4.1 BETA</h3>
								<ul>
									<li>Minor bugfix.</li>
								</ul>
								<h3>0.4 BETA</h3>
								<ul>
									<li>Fixed issues with WP's auto embedding of YouTube-URIs, introduced in WP 2.9.</li>
								</ul>
								<h3>0.3.1 BETA</h3>
								<ul>
									<li>Minor bugfix.</li>
								</ul>
								<h3>0.3 BETA</h3>
								<ul>
									<li>Fixed errors when showing several galleries to one Page, or when showing Home or Archives with galleries in multiple Posts.</li>
								</ul>
								<h3>0.2 BETA</h3>
								<ul>
									<li>Option to include titles/description added</li>
								</ul>
								<h3>0.1 BETA</h3>
								<ul>
									<li>First version</li>
								</ul>
							</div>
						<?php
						break;
						case 'donate' : 
							?>
							<div class="ytsg_admin_wrap">
								<h2>Like this plugin? Please donate to support development.</h2>
								<p>
									Countless hours have gone into the development of this plugin. Hours spent not making money.
									To support further development, please consider giving a donation to the plugin author.
								</p>

								<h2>Support developtment</h2>

								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="P7AE724GG8MWN">
								<input type="image" src="https://www.paypalobjects.com/en_US/NO/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
								</form>

							</div>
						<?php
						break;
						case 'whatsnew' : 
							?>
							<div class="ytsg_admin_wrap">
							<h2>YouTube SimpleGallery – new and improved version!</h2>
							</div>
						<?php
						break;
					}
					echo '';
				}
				?>
				
		</div>

	</div>
<?php
}
?>
