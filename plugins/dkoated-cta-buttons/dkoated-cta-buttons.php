<?php
/**
 * Plugin name: DKOATED CTA Buttons
 * Description: Easy to use, beautiful and CSS-only SEO ready Call to Action buttons for WordPress. No external resources, no javascript and no images necessary!
 * Author: dkoated, David Klein
 * Author URI: http://DKOATED.com
 * Plugin URI: http://DKOATED.com/dkoated-cta-buttons-wordpress-plugin/
 * Version: 1.5.0
 */

add_action('admin_init','dkb_settings_init');
function dkb_settings_init(){
	register_setting('dkb_settings_options','fallback_url');
	register_setting('dkb_settings_options','fallback_text');
	register_setting('dkb_settings_options','fallback_desc');
	register_setting('dkb_settings_options','fallback_title');
	register_setting('dkb_settings_options','fallback_type');
	register_setting('dkb_settings_options','fallback_style');
	register_setting('dkb_settings_options','fallback_color');
	register_setting('dkb_settings_options','fallback_height');
	register_setting('dkb_settings_options','fallback_width');
	register_setting('dkb_settings_options','fallback_customcss');
	register_setting('dkb_settings_options','fallback_opennewwindow');
	register_setting('dkb_settings_options','fallback_nofollow');
	register_setting('dkb_settings_options','fallback_textcolor');
	register_setting('dkb_settings_options','fallback_customvi');
	register_setting('dkb_settings_options','fallback_customho');
	register_setting('dkb_settings_options','fallback_donated');
}
add_action('init','dkb_settings_scripts');
function dkb_settings_scripts(){wp_enqueue_style('farbtastic');wp_enqueue_script('farbtastic');}
add_filter('plugin_action_links','dkoated_cta_buttons_plugin_action_links',10,2);
function dkoated_cta_buttons_plugin_action_links($links,$file){
	static $this_plugin;
	if(!$this_plugin){$this_plugin = plugin_basename(__FILE__);}
	if($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=/dkoated-cta-buttons/dkoated-cta-buttons.php">'._e('Settings').'</a>';
		array_unshift($links,$settings_link);
	}
	return $links;
}
add_filter('widget_text','shortcode_unautop');
add_filter('widget_text','do_shortcode');
function hexDarker($hex,$factor = 30){
	$nhex = '';
	$base['R'] = hexdec($hex{0}.$hex{1});
	$base['G'] = hexdec($hex{2}.$hex{3});
	$base['B'] = hexdec($hex{4}.$hex{5});
	foreach($base as $k => $v){
		$amount = $v / 100;
		$amount = round($amount * $factor);
		$new_decimal = $v - $amount;
		$nhex_component = dechex($new_decimal);
		if(strlen($nhex_component) < 2){$nhex_component = "0".$nhex_component;}
		$nhex .= $nhex_component;
	}
	return $nhex;
}
if(!class_exists("dkoated_cta_buttons_plugin_adminmenu")){
	class dkoated_cta_buttons_plugin_adminmenu{
		function dkoated_cta_buttons_plugin_adminmenu(){
			add_action('admin_menu',array(&$this,'add_dkoated_cta_buttons_menu'));
		}
		function add_dkoated_cta_buttons_menu(){
			if(function_exists('add_menu_page')){
				add_options_page('CTA Buttons','<img src="'.plugins_url(basename(dirname(__FILE__)).'/img/icon.png').'" style="width:11px;height:9px;border:0;" alt="DKOATED CTA Buttons" />CTA Buttons','manage_options',__FILE__,array($this,'dkoated_cta_buttons_menu_page'));
			}
		}
		function dkoated_cta_buttons_menu_page(){
			?>
			<div class="wrap">
				<div style="background:url('<?php echo plugins_url(basename(dirname(__FILE__)).'/img/icon32.png');?>') no-repeat;float:left;height:34px;margin:7px 0 0 0;width:36px;"><a href="http://dkoated.com/" target="_blank" title="DKOATED" style="height:34px;width:36px;display:block;"></a></div>
				<h2>DKOATED CTA Buttons</h2>
				<p>Welcome to the DKOATED CTA Buttons plugin. This plugin enables you to add easy to use, beautiful, CSS-only and SEO-ready Call to Action buttons through shortcodes to your WordPress. No external resources, no javascript and no images necessary!</p>
				<div style="width:100%;">
					<div style="float:left;margin:0 330px 0 0;">
						<form action="options.php" method="post">
							<?php settings_fields('dkb_settings_options');?>
							<h3><?php _e('Default');?> <?php _e('Settings');?></h3>
							<p>The default fallback settings listed below determine the default fallback to use when the corresponding attribute is unspecified with the shortcode.</p>
							<p>To get started just add one of the following codes to any post or page and fill in the attributed with your information.</p>
							<p>Standard Button (without Sub-Headline):<br />
							<code>[DKB url="" text="" title="" type="" style="" color="" height="" width="" opennewwindow="" nofollow=""]</code></p>
							<p>Standard Button (with Sub-Headline):<br />
							<code>[DKB url="" text="" desc="" title="" type="" style="" color="" height="" width="" opennewwindow="" nofollow=""]</code></p>
							<p>Standard Button (with custom colors):<br />
							<code>[DKB url="" text="" title="" type="" style="" height="" width="" opennewwindow="" nofollow="" custom="yes"]</code></p>
							<p>Standard Button (with custom text color):<br />
							<code>[DKB url="" text="" title="" type="" style="" height="" width="" opennewwindow="" nofollow="" textcolor="#ff0080"]</code></p>
							<style>.farbtastic,.farbtastic .wheel{display:inline-block!important;float:left!important;padding:0 20px 0 0}</style>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_url"><strong>URL</strong></label></th>
										<td><input name="fallback_url" type="text" id="fallback_url" value="<?php echo get_option('fallback_url');?>" class="regular-text">
										<br /><span class="description">The URL attribute is the link of the button. If unspecified, the attribute defaults to your homepage URL.<br />Default fallback: <code><?php echo get_bloginfo('wpurl') ?></code> | Usage: <code>[DKB <strong>url="<?php echo get_bloginfo('wpurl') ?>"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_text"><strong>Text</strong></label></th>
										<td><input name="fallback_text" type="text" id="fallback_text" value="<?php echo get_option('fallback_text');?>" class="regular-text">
										<br /><span class="description">The Text attribute is the text of the button. If unspecified, the attribute defaults to whatever you chose in the URL attribute.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>text="Your button text"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_desc"><strong>Desc</strong></label></th>
										<td><input name="fallback_desc" type="text" id="fallback_desc" value="<?php echo get_option('fallback_desc');?>" class="regular-text">
										<br /><span class="description">The Desc attribute is the text of the button's sub-headline. If unspecified, the attribute defaults nothing, thus a button with no sub-headline will be generated.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>desc="Your sub-headline"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_title"><strong>Title</strong></label></th>
										<td><input name="fallback_title" type="text" id="fallback_title" value="<?php echo get_option('fallback_title');?>" class="regular-text">
										<br /><span class="description">The Title attribute is the link-title of the button's link. If unspecified, the attribute defaults to whatever you chose in the URL attribute.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>title="Your SEO link title"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_type"><strong>Type</strong></label></th>
										<td><select name="fallback_type" id="fallback_type" class="select">
											<option value=""<?php if(get_option('fallback_type') == ''){echo ' selected';}else{};?>>- empty -</option>
											<option value="extralarge"<?php if(get_option('fallback_type') == 'extralarge'){echo 'selected';}else{};?>>Extra Large</option>
											<option value="large"<?php if(get_option('fallback_type') == 'large'){echo 'selected';}else{};?>>Large</option>
											<option value="normal"<?php if(get_option('fallback_type') == 'normal'){echo 'selected';}else{};?>>Normal</option>
											<option value="small"<?php if(get_option('fallback_type') == 'small'){echo 'selected';}else{};?>>Small</option>
											<option value="extrasmall"<?php if(get_option('fallback_type') == 'extrasmall'){echo 'selected';}else{};?>>Extra Small</option>
										</select><br />
										<br /><span class="description">The Type attribute is the size of the button. If unspecified, the attribute defaults to its standard normal size.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>type="extralarge|large|normal|small|extrasmall"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_style"><strong>Style</strong></label></th>
										<td><select name="fallback_style" id="fallback_style" class="select">
											<option value="normal"<?php if(get_option('fallback_style') == ''){echo ' selected';}else{};?>>- empty -</option>
											<option value="normal"<?php if(get_option('fallback_style') == 'normal'){echo 'selected';}else{};?>>Normal</option>
											<option value="gradient"<?php if(get_option('fallback_style') == 'gradient'){echo 'selected';}else{};?>>Gradient</option>
											<option value="stitched"<?php if(get_option('fallback_style') == 'stitched'){echo 'selected';}else{};?>>Stitched</option>
										</select><br />
										<br /><span class="description">The Style attribute is the style of the button. If unspecified, the attribute defaults to its standard normal style.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>style="normal|gradient|stitched"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_color"><strong>Color</strong></label></th>
										<td><input name="fallback_color" type="text" id="fallback_color" value="<?php echo get_option('fallback_color');?>" class="small-text code">
										<br /><span class="description">The Color attribute is the color of the button. If unspecified, the attribute defaults to the black color.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>color="black|white|grey|red|green|blue|orange|yellow|pink|brown|#000000|#ff0066|..."</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_width"><strong>Height</strong></label></th>
										<td><input name="fallback_width" type="text" id="fallback_width" value="<?php echo get_option('fallback_height');?>" class="small-text code">
										<br /><span class="description">The Height attribute is the height of the button. If unspecified, the attribute defaults to automatic and adapts to the button text and the sub-headline's text (if specified).<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>height="your size in pixel without <em>px</em>"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_width"><strong>Width</strong></label></th>
										<td><input name="fallback_width" type="text" id="fallback_width" value="<?php echo get_option('fallback_width');?>" class="small-text code">
										<br /><span class="description">The Width attribute is the width of the button. If unspecified, the attribute defaults to automatic and adapts to either the button text or the sub-headline's text (whichever is longer).<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>width="your size in pixel without <em>px</em>"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_opennewwindow"><strong>Opennewwindow</strong></label></th>
										<td><select name="fallback_opennewwindow" id="fallback_opennewwindow" class="select">
											<option value=""<?php if(get_option('fallback_opennewwindow') == ''){echo ' selected';}else{};?>>- empty -</option>
											<option value="yes"<?php if(get_option('fallback_opennewwindow') == 'yes'){echo 'selected';}else{};?>>Yes</option>
											<option value="no"<?php if(get_option('fallback_opennewwindow') == 'no'){echo 'selected';}else{};?>>No</option>
										</select><br />
										<br /><span class="description">The Opennewwindow attribute forces the link to either open in a new window or open the link in the same window. If unspecified, the attribute defaults to yes.<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>opennewwindow="yes|no"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_nofollow"><strong>Nofollow</strong></label></th>
										<td><select name="fallback_nofollow" id="fallback_nofollow" class="select">
											<option value=""<?php if(get_option('fallback_nofollow') == ''){echo ' selected';}else{};?>>- empty -</option>
											<option value="yes"<?php if(get_option('fallback_nofollow') == 'yes'){echo 'selected';}else{};?>>Yes</option>
											<option value="no"<?php if(get_option('fallback_nofollow') == 'no'){echo 'selected';}else{};?>>No</option>
										</select><br />
										<br /><span class="description">The Nofollow attribute forces search engines to either follow or not follow the link for indexation. If unspecified, the attribute defaults to yes (search engine bots will not follow the link).<br />Default fallback: <code>empty</code> | Usage: <code>[DKB ... <strong>nofollow="yes|no"</strong>]</code></span>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes');?>"></p>
							<p>&nbsp;</p>
							<h3><?php _e('Custom:');?> Color <?php _e('Settings');?></h3>
							<p>The custom color settings listed below determine the normal, visited and hover colors to use for the buttons if the attribute custom is set to yes with the shortcode. The code to activate the custom colors is <code>[DKB ... <strong>custom="yes"</strong>]</code></p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_customvi"><strong>Custom Color</strong></label></th>
										<td><input name="fallback_customvi" type="text" id="fallback_customvi" value="<?php echo get_option('fallback_customvi');?>" class="small-text code"><div id="fallback_customvi_select">.</div>
										<script>jQuery(document).ready(function($){$('#fallback_customvi_select').farbtastic('#fallback_customvi');});</script>
										<br /><span class="description">The Custom Color is the default color of the button (unhovered) and is required to be set if the custom attribute is set to "yes". It's a standard hex color and requires the '#' sign in front of the 6 digit hex color.<br />Default fallback: <code>empty</code> | Usage: Color needs to be specified here. For example: <code><strong>#ff0066</strong></code></span></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_customho"><strong>Custom Hover Color</strong></label></th>
										<td><input name="fallback_customho" type="text" id="fallback_customho" value="<?php echo get_option('fallback_customho');?>" class="small-text code"><div id="fallback_customho_select">.</div>
										<script>jQuery(document).ready(function($){$('#fallback_customho_select').farbtastic('#fallback_customho');});</script>
										<br /><span class="description">The Custom Hover Color is the default color of the button when hovered and is required to be set if the custom attribute is set to "yes". It's a standard hex color and requires the '#' sign in front of the 6 digit hex color.<br />Default fallback: <code>empty</code> | Usage: Color needs to be specified here. For example: <code><strong>#ff0066</strong></code></span></td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes');?>"></p>
							<p>&nbsp;</p>
							<h3><?php _e('Custom:');?> Stylesheet <?php _e('Settings');?></h3>
							<p>The custom stylesheet settings are intended for advanced users with CSS knowledge. You may define whatever CSS code you see fit within the code. The CSS is added <code>&#60;a class="buttonclass colorclass buttonstyle" style="lorem ipsum;<strong>YOUR-CUSTOM-CSS-ADDED-HERE;</strong>" ...&#62;Button Text&#60;/a&#62;</code></p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_customcss"><strong>Stylesheet</strong></label></th>
										<td><input name="fallback_customcss" type="text" id="fallback_customcss" value="<?php echo get_option('fallback_customcss');?>" class="regular-text">
										<br /><span class="description"><strong>IMPORTANT: The styles defined here will always be added to all buttons!</strong><br />Do not, I repeat, DO NOT prepend the complete CSS style, but rather only additional CSS attributes and values. Keep in mind to also append the important declaration followed by a semicolon: <code>!important;</code> after each css attribute and value.<br />Example: <code>line-height:1em <strong>!important;</strong>text-transform:uppercase <strong>!important;</strong>etc.</code></span>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes');?>"></p>
							<p>&nbsp;</p>
							<h3>Already donated?</h3>
							<p>The DKOATED CTA Buttons plugin for WordPress is free (as in beer). We really really really appreciate if you'd <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UR3YE88FGAU88" rel="nofollow" title="I want to donate coffee">donate some coffee</a> to continue developing this awesome plugin.<br />Thank you.</p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_donated"><strong>I donated!</strong></label></th>
										<td><select name="fallback_donated" id="fallback_donated" class="select">
											<option value="a"<?php if(get_option('fallback_donated') == 'a'){echo 'selected';}else{};?>>No</option>
											<option value="b"<?php if(get_option('fallback_donated') == 'b'){echo 'selected';}else{};?>>Yes</option>
										</select><br />
										<br /><span class="description">We know not everybody has the spare change to donate a couple of bucks, but if you are one of the few donators, please choose 'Yes' and we'll remove some commented code place after the buttons which is not visible anyway (and we'll appreciate it even more if you wouldn't pretend you donated if you haven't).</span>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes');?>"></p>
						</form>
					</div>
					<div style="float:right;width:300px;position:absolute;right:20px;">
						<table class="widefat">
							<thead>
								<tr>
									<th>Show some love!</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p><strong>Want to help make this plugin even more awesome?</strong><br />All donations are used to improve this plugin, so donate what you can and are willing to spend.<br /><strong>Every penny counts and is highly appreciated, starting from $5, $10, $20, $50 or more!</strong></p>
									<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="UR3YE88FGAU88">
									<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Thank you for your donation!!!">
									<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1"><br /></form></p></td>
								</tr>
							</tbody>
						</table>
						&nbsp;
						<table class="widefat">
							<thead>
								<tr>
									<th>Help Spread the Word!</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p><a href="http://wordpress.org/extend/plugins/dkoated-cta-buttons/" title="Please rate the plugin 5 stars on WordPress.org" rel="nofollow" target="_blank">Rate the plugin 5 stars on WordPress.org</a></p>
									<p><a href="https://twitter.com/share" class="twitter-share-button" data-url="dkoated.com/dkoated-cta-buttons-wordpress-plugin/" data-text="ZOMG! Awesome CSS-only Call to Action Buttons for WordPress. Check it out! #WordPress #Plugins" data-via="DKOATED" data-related="DKOATED">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></p>
									<p><div id="fb-root"></div><script>(function(d,s,id){var js,fjs = d.getElementsByTagName(s)[0];if(d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=276876738994781";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));</script><div class="fb-like" data-href="http://dkoated.com" data-send="false" data-layout="button_count" data-width="250" data-show-faces="false" data-action="recommend"></div></p>
									<p><div class="g-plusone" data-href="http://dkoated.com"></div><script type="text/javascript">(function(){var po = document.createElement('script');po.type = 'text/javascript';po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();</script></p></td>
								</tr>
							</tbody>
						</table>
						&nbsp;
						<table class="widefat">
							<thead>
								<tr>
									<th>Need support?</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p>If you are having problems with this plugin, please let me know about it on the <a href="http://dkoated.com/dkoated-cta-buttons-wordpress-plugin/" target="_blank" title="DKOATED.com Plugin Page">plugin page on DKOATED.com</a>.</p></td>
								</tr>
							</tbody>
						</table>
						&nbsp;
						<table class="widefat">
							<thead>
								<tr>
									<th>Latest news from DKOATED.com</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p><a href="http://dkoated.com/" target="_blank" title="DKOATED.com">DKOATED.com</a><br /><a href="http://www.facebook.com/DKOATED" target="_blank" title="DKOATED on Facebook">DKOATED on Facebook</a><br /><a href="http://twitter.com/DKOATED" target="_blank" title="DKOATED.com">DKOATED on Twitter</a><br /><a href="https://plus.google.com/u/0/b/116249477495808918165/" target="_blank" title="DKOATED.com">DKOATED on Google+</a><br /><a href="http://dkoated.com/feed/" target="_blank" title="DKOATED.com">DKOATED RSS Feed</a></p></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<p><small><a href="http://wordpress.org/extend/plugins/dkoated-cta-buttons/" target="_blank">DKOATED CTA Buttons</a> plugin brought to you by <a href="https://plus.google.com/u/0/103198314695328331300" target="_blank">David Klein</a> from <a href="http://dkoated.com" target="_blank">DKOATED.com</a> | <a href="http://dkoated.com/donate/" target="_blank">Donate me coffee &hearts;</a>.</small></p>
			</div>
			<?php 
		}
	}
}
$wpdpd = new dkoated_cta_buttons_plugin_adminmenu();
if(!is_admin()){
	define('DKOATED_CTA_BUTTONS_VERSION','1.5.0');
	$css_url = plugins_url(basename(dirname(__FILE__)).'/css/dkoated-cta-buttons.css');
	wp_register_style('dkoated-cta-buttons',$css_url,array(),DKOATED_CTA_BUTTONS_VERSION,'screen');
	wp_enqueue_style('dkoated-cta-buttons');
	/* @param $atts */
	/* These are the attributes */
	function sc_DKOATEDCTABUTTONS($atts){
		extract(shortcode_atts(array(
			"url" => '',
			"text" => '',
			"desc" => '',
			"title" => '',
			"type" => 'normal',
			"style" => 'normal',
			"color" => 'black',
			"textcolor" => '',
			"height" => '',
			"width" => '',
			"opennewwindow" => 'yes',
			"nofollow" => 'yes',
			"custom" => ''
		),$atts));
		if($url == '' && get_option('fallback_url') == ''){$url = get_bloginfo('url');}
		if($url == '' && get_option('fallback_url') != ''){$url = get_option('fallback_url');}
		if($url != '' && get_option('fallback_url') == ''){$url = $url;}
		if($url != '' && get_option('fallback_url') != ''){$url = $url;}
		if($text == '' && get_option('fallback_text') == ''){$text = get_bloginfo('url');}
		if($text == '' && get_option('fallback_text') != ''){$text = get_option('fallback_text');}
		if($text != '' && get_option('fallback_text') == ''){$text = $text;}
		if($text != '' && get_option('fallback_text') != ''){$text = $text;}
		if($desc == '' && get_option('fallback_desc') == ''){$desc = '';}
		if($desc == '' && get_option('fallback_desc') != ''){$desc = '<span><br /><em>'.get_option('fallback_desc').'</em></span>';}
		if($desc != '' && get_option('fallback_desc') == ''){$desc = '<span><br /><em>'.$desc.'</em></span>';}
		if($desc != '' && get_option('fallback_desc') != ''){$desc = '<span><br /><em>'.$desc.'</em></span>';}
		if($title == '' && get_option('fallback_title') == ''){$title = $text;}
		if($title == '' && get_option('fallback_title') != ''){$title = get_option('fallback_title');}
		if($title != '' && get_option('fallback_title') == ''){$title = $title;}
		if($title != '' && get_option('fallback_title') != ''){$title = $title;}
		if($type == '' && get_option('fallback_type') == ''){$type = 'normal';}
		if($type == '' && get_option('fallback_type') != ''){$type = get_option('fallback_type');}
		if($type != '' && get_option('fallback_type') == ''){$type = $type;}
		if($type != '' && get_option('fallback_type') != ''){$type = $type;}
		if($style == '' && get_option('fallback_style') == ''){$style = 'normal';}
		if($style == '' && get_option('fallback_style') != ''){$style = get_option('fallback_style');}
		if($style == 'normal' && get_option('fallback_style') == ''){$style = 'normal';}
		if($style == 'normal' && get_option('fallback_style') != ''){$style = 'normal';}
		if($style == 'gradient' && get_option('fallback_style') == ''){$style = 'gradient';}
		if($style == 'gradient' && get_option('fallback_style') != ''){$style = 'gradient';}
		if($style == 'stitched' && get_option('fallback_style') == ''){$style = 'stitched';}
		if($style == 'stitched' && get_option('fallback_style') != ''){$style = 'stitched';}
		if($style != 'normal' && $style != 'gradient' && $style != 'stitched' && get_option('fallback_style') == ''){$style = 'normal';}
		if($style != 'normal' && $style != 'gradient' && $style != 'stitched' && get_option('fallback_style') != ''){$style = get_option('fallback_style');}
		if($color == '' && get_option('fallback_color') == '' && $custom == ''){$color = 'black';}
		if($color == '' && get_option('fallback_color') == '' && $custom == 'no'){$color = 'black';}
		if($color == '' && get_option('fallback_color') == '' && $custom == 'yes'){$color = 'custom';}
		if($color == '' && get_option('fallback_color') != '' && $custom == ''){$color = get_option('fallback_color');}
		if($color == '' && get_option('fallback_color') != '' && $custom == 'no'){$color = get_option('fallback_color');}
		if($color == '' && get_option('fallback_color') != '' && $custom == 'yes'){$color = 'custom';}
		if($color == 'black' && get_option('fallback_color') != '' && $custom == ''){$color = 'black';}
		if($color == 'black' && get_option('fallback_color') == '' && $custom == ''){$color = 'black';}
		if($color == 'grey' && get_option('fallback_color') != '' && $custom == ''){$color = 'grey';}
		if($color == 'grey' && get_option('fallback_color') == '' && $custom == ''){$color = 'grey';}
		if($color == 'white' && get_option('fallback_color') != '' && $custom == ''){$color = 'white';}
		if($color == 'white' && get_option('fallback_color') == '' && $custom == ''){$color = 'white';}
		if($color == 'red' && get_option('fallback_color') != '' && $custom == ''){$color = 'red';}
		if($color == 'red' && get_option('fallback_color') == '' && $custom == ''){$color = 'red';}
		if($color == 'green' && get_option('fallback_color') != '' && $custom == ''){$color = 'green';}
		if($color == 'green' && get_option('fallback_color') == '' && $custom == ''){$color = 'green';}
		if($color == 'blue' && get_option('fallback_color') != '' && $custom == ''){$color = 'blue';}
		if($color == 'blue' && get_option('fallback_color') == '' && $custom == ''){$color = 'blue';}
		if($color == 'pink' && get_option('fallback_color') != '' && $custom == ''){$color = 'pink';}
		if($color == 'pink' && get_option('fallback_color') == '' && $custom == ''){$color = 'pink';}
		if($color == 'orange' && get_option('fallback_color') != '' && $custom == ''){$color = 'orange';}
		if($color == 'orange' && get_option('fallback_color') == '' && $custom == ''){$color = 'orange';}
		if($color == 'yellow' && get_option('fallback_color') != '' && $custom == ''){$color = 'yellow';}
		if($color == 'yellow' && get_option('fallback_color') == '' && $custom == ''){$color = 'yellow';}
		if($color == 'brown' && get_option('fallback_color') != '' && $custom == ''){$color = 'brown';}
		if($color == 'brown' && get_option('fallback_color') == '' && $custom == ''){$color = 'brown';}
		if($textcolor == '' && get_option('fallback_textcolor') == ''){$textcolor = 'color:#000000;';}
		if($textcolor != '' && get_option('fallback_textcolor') == ''){$textcolor = 'color:'.$textcolor.';';}
		if($textcolor != '' && get_option('fallback_textcolor') != ''){$textcolor = 'color:'.$textcolor.';';}
		if($height == '' && get_option('fallback_height') == ''){$height = '';}
		if($height == '' && get_option('fallback_height') != '' && is_numeric(get_option('fallback_height'))){$height = 'height:'.get_option('fallback_height').'px!important;max-height:'.get_option('fallback_height').'px!important;vertical-align:middle;display:table-cell;';}
		if($height != '' && is_numeric($height)){$height = 'height:'.$height.'px!important;max-height:'.$height.'px!important;vertical-align:middle;display:table-cell;';}
		if($width == '' && get_option('fallback_width') == ''){$width = '';}
		if($width == '' && get_option('fallback_width') != '' && is_numeric(get_option('fallback_width'))){$width = 'width:'.get_option('fallback_width').'px!important;max-width:'.get_option('fallback_width').'px!important;';}
		if($width != '' && is_numeric($width)){$width = 'width:'.$width.'px!important;max-width:'.$width.'px!important;';}
		if($customcss == ''){$customcss = '';}
		if($customcss != ''){$customcss = get_option('fallback_customcss');}
		if($opennewwindow == '' && get_option('fallback_opennewwindow') == ''){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == '' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == '' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = '';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == ''){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == ''){$opennewwindow = '';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = '';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = '';}
		if($nofollow == '' && get_option('fallback_nofollow') == ''){$nofollow = ' rel="nofollow"';}
		if($nofollow == '' && get_option('fallback_nofollow') == 'yes'){$nofollow = ' rel="nofollow"';}
		if($nofollow == '' && get_option('fallback_nofollow') == 'no'){$nofollow = '';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == ''){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == 'yes'){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == 'no'){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == ''){$nofollow = '';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == 'yes'){$nofollow = '';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == 'no'){$nofollow = '';}
		if($style == 'normal'){
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonnormal,.hex'.substr($color,1).'.dkoatedbuttonnormal:visited{background-color:'.$color.'!important}.hex'.substr($color,1).'.dkoatedbuttonnormal:hover{background-color:'.$color.'!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonnormal,.hex'.substr($color,1).'.dkoatedbuttonnormal:visited{background-color:'.$color.'!important;}.hex'.substr($color,1).'.dkoatedbuttonnormal:hover{background-color:'.$color.'!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonnormal,.hex'.substr($color,1).'.dkoatedbuttonnormal:visited{background-color:'.$color.'!important;}.hex'.substr($color,1).'.dkoatedbuttonnormal:hover{background-color:'.$color.'!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonnormal,.hex'.substr($color,1).'.dkoatedbuttonnormal:visited{background-color:'.$color.'!important;}.hex'.substr($color,1).'.dkoatedbuttonnormal:hover{background-color:'.$color.'!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonnormal,.custom.dkoatedbuttonnormal:visited{background-color:'.get_option('fallback_customvi').'!important}.custom.dkoatedbuttonnormal:hover{background-color:'.get_option('fallback_customho').'!important}</style>';}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonnormal,.custom.dkoatedbuttonnormal:visited{background-color:'.get_option('fallback_customvi').'!important}.custom.dkoatedbuttonnormal:hover{background-color:'.get_option('fallback_customho').'!important}</style>';}
			if($custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonnormal,.custom.dkoatedbuttonnormal:visited{background-color:'.get_option('fallback_customvi').'!important}.custom.dkoatedbuttonnormal:hover{background-color:'.get_option('fallback_customho').'!important}</style>';}
		}
		if($style == 'gradient'){
			$colordark = hexDarker($color,50);
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttongradient,.hex'.substr($color,1).'.dkoatedbuttongradient:visited{background:'.$color.'!important;;background:-moz-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=1)!important}.hex'.substr($color,1).'.dkoatedbuttongradient:hover{background:'.$color.'!important;background:-moz-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=0)!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttongradient,.hex'.substr($color,1).'.dkoatedbuttongradient:visited{background:'.$color.'!important;;background:-moz-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=1)!important}.hex'.substr($color,1).'.dkoatedbuttongradient:hover{background:'.$color.'!important;background:-moz-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=0)!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttongradient,.hex'.substr($color,1).'.dkoatedbuttongradient:visited{background:'.$color.'!important;;background:-moz-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=1)!important;}.hex'.substr($color,1).'.dkoatedbuttongradient:hover{background:'.$color.'!important;background:-moz-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=0)!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttongradient,.hex'.substr($color,1).'.dkoatedbuttongradient:visited{background:'.$color.'!important;;background:-moz-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(-45deg,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=1)!important;}.hex'.substr($color,1).'.dkoatedbuttongradient:hover{background:'.$color.'!important;background:-moz-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.$colordark.'))!important;background:-webkit-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-o-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:-ms-linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;background:linear-gradient(top,'.$color.' 0%,'.$colordark.' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.$color.'\',endColorstr=\''.$colordark.'\',GradientType=0)!important}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttongradient,.custom.dkoatedbuttongradient:visited{background:'.get_option('fallback_customvi').'!important;;background:-moz-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.get_option('fallback_customvi').'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=1)!important;}.custom.dkoatedbuttongradient:hover{background:'.get_option('fallback_customvi').'!important;background:-moz-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=0)!important}</style>';}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttongradient,.custom.dkoatedbuttongradient:visited{background:'.get_option('fallback_customvi').'!important;;background:-moz-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.get_option('fallback_customvi').'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=1)!important;}.custom.dkoatedbuttongradient:hover{background:'.get_option('fallback_customvi').'!important;background:-moz-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=0)!important}</style>';}
			if($custom == 'yes'){$custom = '<style>.custom.dkoatedbuttongradient,.custom.dkoatedbuttongradient:visited{background:'.get_option('fallback_customvi').'!important;;background:-moz-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,right bottom,color-stop(0%,'.get_option('fallback_customvi').'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(-45deg,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=1)!important;}.custom.dkoatedbuttongradient:hover{background:'.get_option('fallback_customvi').'!important;background:-moz-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,'.$color.'),color-stop(100%,'.get_option('fallback_customho').'))!important;background:-webkit-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-o-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:-ms-linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;background:linear-gradient(top,'.get_option('fallback_customvi').' 0%,'.get_option('fallback_customho').' 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\''.get_option('fallback_customvi').'\',endColorstr=\''.get_option('fallback_customho').'\',GradientType=0)!important}</style>';}
		}
		if(get_option('fallback_donated') == '' OR get_option('fallback_donated') == 'a'){$kakuni = '<!-- DKOATED CTA Buttons by http://dkoated.com -->';}
		if(get_option('fallback_donated') == 'b'){$kakuni = '';}
		if($style == 'stitched'){
			$colordark = hexDarker($color,50);
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonstitched,.hex'.substr($color,1).'.dkoatedbuttonstitched:visited{background:'.$color.';-moz-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);}.hex'.substr($color,1).'.dkoatedbuttonstitched:hover{background:'.$colordark.';-moz-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5)}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == ''){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonstitched,.hex'.substr($color,1).'.dkoatedbuttonstitched:visited{background:'.$color.';-moz-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);}.hex'.substr($color,1).'.dkoatedbuttonstitched:hover{background:'.$colordark.';-moz-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5)}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonstitched,.hex'.substr($color,1).'.dkoatedbuttonstitched:visited{background:'.$color.';-moz-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);}.hex'.substr($color,1).'.dkoatedbuttonstitched:hover{background:'.$colordark.';-moz-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5)}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'no'){$custom = '<style>.hex'.substr($color,1).'.dkoatedbuttonstitched,.hex'.substr($color,1).'.dkoatedbuttonstitched:visited{background:'.$color.';-moz-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$color.',0 4px 3px rgba(0,0,0,0.5);}.hex'.substr($color,1).'.dkoatedbuttonstitched:hover{background:'.$colordark.';-moz-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.$colordark.',0 4px 3px rgba(0,0,0,0.5)}</style>';$color = 'hex'.substr($color,1);}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonstitched,.custom.dkoatedbuttonstitched:visited{background:'.get_option('fallback_customvi').';-moz-box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);}.custom.dkoatedbuttonstitched:hover{background-color:'.get_option('fallback_customho').'!important}</style>';}
			if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonstitched,.custom.dkoatedbuttonstitched:visited{background:'.get_option('fallback_customvi').';-moz-box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.get_option('fallback_customvi').',0 4px 3px rgba(0,0,0,0.5);}.custom.dkoatedbuttonstitched:hover{background:'.get_option('fallback_customho').';-moz-box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5)}</style>';}
			if($custom == 'yes'){$custom = '<style>.custom.dkoatedbuttonstitched,.custom.dkoatedbuttonstitched:visited{background-color:'.get_option('fallback_customvi').'!important;}.custom.dkoatedbuttonstitched:hover{background:'.get_option('fallback_customho').';-moz-box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5);box-shadow:0 0 0 3px '.get_option('fallback_customho').',0 4px 3px rgba(0,0,0,0.5)}</style>';}
		}
		/* @var string */
		/* This is the output */
		$var_sHTML = '';
		$var_sHTML .= $custom.$kakuni.'<a class="'.$type.' '.$color.' dkoatedbutton'.$style.'" style="'.$width.''.$height.''.$customcss .''.$textcolor.'" href="'.$url.'" title="'.$title.'" '.$opennewwindow.' '.$nofollow .'>'.$text.$desc.'</a>';
		return $var_sHTML;
	}
	add_shortcode('DKB','sc_DKOATEDCTABUTTONS');
}
function dkoated_donate($links,$file){
	$plugin = plugin_basename(__FILE__);
	if($file == $plugin){
		return array_merge($links,array(sprintf('<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UR3YE88FGAU88" target="_blank" title="Please donate and buy me coffee">Donate and buy me coffee</a>',$plugin,__('Donate'))));
	}
	return $links;
}
add_filter('plugin_row_meta','dkoated_donate',10,2);
?>