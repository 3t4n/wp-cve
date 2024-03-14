<?php
/*
Plugin Name: Fast Tube
Plugin URI: http://blog.caspie.net/2009/02/19/fast-tube-wordpress-plugin/
Description: Fast and easy way to insert videos from YouTube right into your WordPress blog posts.
Version: 2.3.1
Author: Casper
Author URI: http://blog.caspie.net/
*/
add_filter('the_content','fast_youtube_content',50);
function fast_youtube_content($the_content) {
 	$options = get_option('fast_tube_options');
 	$skinspath = plugins_url( 'skins/', __FILE__ );
 	$author = $options['author'] ? '<br /><small>Fast Tube by <a title="Casper\'s Blog" href="http://blog.caspie.net/">Casper</a></small>' : '';
 	$align_class = $options['alignclass'] != '' ? ' class="'.$options['alignclass'].'"' : '';
 	$align_after = $options['align'] ? '</'.$options['aligntag'].'>' : '</span>';
 	$align_display = $options['aligntag'] == 'span' ? 'display:block;' : '';
 	$thumb_dims = $options['thumbnail'] ? 'width="128" height="96"' : 'width="320" height="240"';
 	$thumb_size = $options['thumbnail'] ? 'small' : 'big';
	$top = $options['skins'] ? '<img src="'.$skinspath.$options['skin'].'/top_'.$thumb_size.'.png" border="0" /><br />' : '';
	$bottom = $options['skins'] ? '<br /><img src="'.$skinspath.$options['skin'].'/bottom_'.$thumb_size.'.png" border="0" />' : '';
	$pat = "/\[(?:(?:http:\/\/)?(?:www\.)?youtube\.com\/)?(?:(?:watch\?)?v=|v\/)?([a-zA-Z0-9\-\_]{11})(?:&[a-zA-Z0-9\-\_]+=[a-zA-Z0-9\-\_]+)*\]/";
	if(preg_match_all($pat,$the_content,$matches,PREG_SET_ORDER)) {
		$ap = count($matches) > 1 ? 0 : (int)$options['autoplay'];
		foreach ($matches as $match) {
			$m0 = $match[0]; $m1 = $match[1];
			if(@file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$m1) != "Video not found") {
 			$align_before = $options['align'] ? '<'.$options['aligntag'].' id="'.$m1.'"'.$align_class.' style="text-align:'.$options['alignment'].';'.$align_display.'">' : '<span id="'.$m1.'" style="display:block;">';
			$video = 'http://www.youtube.com/v/'.$m1.'?version=3&amp;hl=en&amp;fs='.(int)$options['fullscreen'].'&amp;border='.(int)$options['border'].'&amp;color1=0x'.$options['color1'].'&amp;color2=0x'.$options['color2'].'&amp;egm='.(int)$options['egm'].'&amp;disablekb='.(int)$options['disablekb'].'&amp;autoplay='.$ap.'&amp;loop='.(int)$options['loop'].'&amp;rel='.(int)$options['related'].'&amp;showinfo='.(int)$options['info'].'&amp;showsearch='.(int)$options['search'].'&amp;iv_load_policy='.$options['annotations'].'&amp;start='.(int)$options['start'];
				if((!is_singular() && $options['thumb']) || is_feed()) {
					$vid = '<!--[Fast Tube]-->'.$align_before.$top.'<a title="Click here to watch this video!" href="'.get_permalink().'#'.$m1.'"><img src="http://i.ytimg.com/vi/'.$m1.'/'.(int)$options['thumbnail'].'.jpg" alt="Fast Tube" border="0" '.$thumb_dims.' /></a>'.$bottom.$author.$align_after.'<!--[/Fast Tube]-->';
				}
				else {
					$options['valid'] ? $vid = '<!-- [Fast Tube] -->'.$align_before.'<object type="application/x-shockwave-flash" width="'.$options['width'].'" height="'.$options['height'].'" data="'.$video.'"><param name="movie" value="'.$video.'"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent"></param></object>'.$author.$align_after.'<!-- [/Fast Tube] -->' : $vid = '<!-- [Fast Tube] -->'.$align_before.'<object width="'.$options['width'].'" height="'.$options['height'].'"><param name="movie" value="'.$video.'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'.$video.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$options['width'].'" height="'.$options['height'].'"></embed></object>'.$author.$align_after.'<!-- [/Fast Tube] -->';
				}
			}
			else { $vid = $m0; }
			$the_content = str_replace($m0,$vid,$the_content);
		}
	}
	return $the_content;
}
add_action('admin_menu', 'fast_tube_menu');
function fast_tube_menu() {
	add_menu_page( 'Fast Tube', 'Fast Tube', 'manage_options', __FILE__, 'fast_tube_options', plugins_url( 'img/ft.gif', __FILE__ ) );
	add_submenu_page( __FILE__, 'Fast Tube Video Options', 'Video Options', 'manage_options', __FILE__, 'fast_tube_options' );
	add_submenu_page( __FILE__, 'Fast Tube Video Gallery', 'Video Gallery', 'manage_options', dirname( __FILE__ ) . '/fast-tube-gallery.php' );
}
function fast_tube_options() {
	if(isset($_POST['submit'])) {
		check_admin_referer('fast_tube');
		$new_options = $_POST['fast_tube'];
		$option = array ('border','fullscreen','egm','disablekb','autoplay','loop','related','info','search','annotations','align','thumb','skins','valid','author');
		foreach($option as $set) {
			$set == 'annotations' ? $options[$set] = isset($new_options[$set]) ? 1 : 3 : $options[$set] = isset($new_options[$set]);
		}
		$options['width'] = $new_options['width'] == '' ? 425 : (int)$new_options['width'];
		$options['height'] = $new_options['height'] == '' ? 344 : (int)$new_options['height'];
		$options['border'] = isset($new_options['border']);
		$options['color1'] = $new_options['color1'] == '' ? '999999' : strtolower(ltrim($new_options['color1'],'#'));
		$options['color2'] = $new_options['color2'] == '' ? 'cccccc' : strtolower(ltrim($new_options['color2'],'#'));
		$options['start'] = $new_options['start'] == '' ? 0 : (int)$new_options['start'];
		$options['alignment'] = $new_options['alignment'];
		$options['aligntag'] = $new_options['aligntag'];
		$options['alignclass'] = $new_options['alignclass'];
		$options['thumbnail'] = (bool)$new_options['thumbnail'];
		$options['skin'] = $new_options['skin'];
		update_option('fast_tube_options',$options);
		echo '<div id="message" class="updated fade"><p><strong>Settings Saved.</strong></p></div>';
	}
	if(isset($_POST['reset'])) {
		fast_tube_start(true);
		echo '<div id="message" class="updated fade"><p><strong>Default Settings Loaded.</strong></p></div>';
	}
	$options = get_option('fast_tube_options');
	$action = $_SERVER['REQUEST_URI'];
	$check = array ('border','fullscreen','egm','disablekb','autoplay','loop','related','info','search','annotations','align','thumb','skins','valid','author');
	foreach($check as $checked) {
		$checked = 'annotations' ? $$checked = $options[$checked] == 1 ? ' checked="checked"' : '' : $$checked = $options[$checked] ? ' checked="checked"' : '';
	}
	$center = $options['alignment'] == 'center' ? ' selected="selected"' : '';
	$left = $options['alignment'] == 'left' ? ' selected="selected"' : '';
	$right = $options['alignment'] == 'right' ? ' selected="selected"' : '';
	$span = $options['aligntag'] == 'span' ? ' selected="selected"' : '';
	$div = $options['aligntag'] == 'div' ? ' selected="selected"' : '';
	$p = $options['aligntag'] == 'p' ? ' selected="selected"' : '';
	$small = $big = '';
	$thumbnail = $options['thumbnail'] ? $small = ' selected="selected"' : $big = ' selected="selected"';
	$default = $options['skin'] == 'default' ? ' selected="selected"' : '';
	$path = trailingslashit( plugins_url( '', __FILE__ ) );
?>
<style type="text/css" media="screen">
a { text-decoration:none; }
a:hover { text-decoration:underline; }
input[type=checkbox] { border:0 none; }
input[type=text] { vertical-align:middle; }
td { border-top:1px solid #ccc;background:#eee; }
select { width:120px; }
.more, .info { border:1px dotted #ccc;background:#fff;margin-top:10px;padding:10px;text-align: justify; }
.info { width: 510px; }
.ri { text-align:right; }
.nb { border:0 none; }
</style>
<div class="wrap">
<script src="<?php echo $path; ?>js/func.js" type="text/javascript"></script>
<script src="<?php echo $path; ?>js/301a.js" type="text/javascript"></script>
	<h2><img src="<?php echo $path; ?>img/ft.gif" alt="Fast Tube" /> Fast Tube <?php echo FAST_TUBE_VERSION; ?> by <a title="Casper's Blog" href="http://blog.caspie.net/">Casper</a></h2>
	<h6><em>Fast and easy way to insert any amount of YouTube videos right into your blog's posts or pages!</em></h6>
	<input type="button" class="button" onclick="javascript:moreInfo('information');" value="Information" />
	<input type="button" class="button" onclick="javascript:moreInfo('usage');" value="Fast Tube Usage" />
	<input type="button" class="button" onclick="javascript:moreInfo('skins');" value="Fast Tube Skins" />
	<div id="information" class="info" style="display:none;">
		<div><small><strong>Check out my other WordPress plugins at <a title="Downloads @ Casper's Blog" href="http://blog.caspie.net/downloads/">http://blog.caspie.net/downloads</a><br />Nice plugin eh? Feel free to <a href="http://donate.caspie.net/" target="_blank">donate</a> if you like it. Thanks and have fun! :)</strong></small></div>
	</div>
	<div id="usage" class="info" style="display:none;">	
		<div><small><strong>Visit youtube.com and play some video. Copy the video URL and insert it in post or page, surrounded by square brackets - [URL]. You can insert as many as you like in the same post or page. All the variations will work as well...</strong>
		<br /><br />
		[http://www.youtube.com/watch?v=gOAra5f0qlk&amp;feature=related]<br />
		[http://www.youtube.com/watch?v=gOAra5f0qlk]<br />
		[http://youtube.com/watch?v=gOAra5f0qlk]<br />
		[www.youtube.com/watch?v=gOAra5f0qlk]<br />
		[youtube.com/watch?v=gOAra5f0qlk]<br />
		[watch?v=gOAra5f0qlk]<br />
		[v=gOAra5f0qlk]<br />
		[gOAra5f0qlk]<br />
		</small></div>
	</div>
	<div id="skins" class="info" style="display:none;">
		<div><small><strong>Skin support started with Fast Tube 2.2 and now everything looks even better when using Fast Tube ThumbView. It's easy for you to choose from the 10 variations of the default skin as it's easy to add your own skins. Adding new skins has a few requirements.</strong><br /><br />
		1. Create 4 PNG files with the exact names and width as follows:<br />
		- top_big.png (width 320px)<br />
		- bottom_big.png (width 320px)<br />
		- top_small.png (width 128px)<br />
		- bottom_small.png (width 128px)<br />
		Notice that the height is up to you. Free your mind.<br /><br />
		2. Create new folder. Don't use spaces in the name.<br />
		Notice that the folder name is the skin name.<br /><br />
		3.Put the four files from step 1 into the folder and upload it into fast-tube/skins/folder of your WordPress installation. That's it! Go to Fast Tube options page and you will be able to use your new skin.
		</small></div>
	</div>
	<h2 id="start">Video Options:</h2>
	<form name="fasttube" method="post" action="<?php echo $action; ?>">
	<?php wp_nonce_field('fast_tube'); ?>
	<div id="colorpicker301" class="colorpicker301"></div>
	<table class="form-table" style="width:530px;">
		<tr valign="top">
		<td width="72%">&rarr; <em>Set custom video width and height in pixels</em></td>
		<td class="ri"><input type="text" name="fast_tube[width]" value="<?php echo $options['width']; ?>" size="4" />x<input type="text" name="fast_tube[height]" value="<?php echo $options['height']; ?>" size="4" /></td>
		</tr>
		<tr>
		<td class="ri nb">... or select from the YouTube default sizes list &rarr;</td>
		<td class="ri nb">
		<select onchange="whNew(this.value)">
			<option value="425,344" selected="selected">Default</option>
			<option value="320,265">320x265</option>
			<option value="480,385">480x385</option>
			<option value="640,505">640x505</option>
		</select>
		</td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more1');" value="more" />
		<div id="more1" class="more" style="display:none;">
		<div><small>This is the width and height (in pixels) of the YouTube videos.</small><h5>YouTube default setting: 425 x 344 pixels</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Enable a border around the video player</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[border]" value="1"<?php echo $border; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="text" id="sample_1" size="1" value="" style="background:#<?php echo $options['color1']; ?>" /><input type="text" name="fast_tube[color1]" id="color_1" size="9" value="#<?php echo $options['color1']; ?>" /> &rarr; <a title="Set Color 1" href="javascript:showColorGrid3('color_1','sample_1');">Select Primary Border Color</a><br /><input type="text" id="sample_2" size="1" value="" style="background:#<?php echo $options['color2']; ?>" /><input type="text" name="fast_tube[color2]" id="color_2" size="9" value="#<?php echo $options['color2']; ?>" /> &rarr; <a title="Set Color 2" href="javascript:showColorGrid3('color_2','sample_2');">Select Secondary Border Color</a></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more2');" value="more" />
		<div id="more2" class="more" style="display:none;">
		<div><small>If checked, this option enables a border around the entire video player. The two colors are the primary and the secondary border colors. Feel free to play with the colors until you reach the result that will satisfy your needs.</small><h5>YouTube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Enable Fast Tube ThumbView</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[thumb]" value="1"<?php echo $thumb; ?> /></td>
		</tr>
		<tr><td class="ri nb">Select the size of the thumbnail &rarr;</td><td class="ri nb">
		<select name="fast_tube[thumbnail]">
			<option value="0"<?php echo $big; ?>>320x240</option>
			<option value="1"<?php echo $small; ?>>128x96</option>
		</select>
		</td>
		</tr>
		<tr valign="top">
		<td class="nb">&rarr; <em>Enable Fast Tube ThumbView skins</em></td>
		<td class="ri nb"><input type="checkbox" name="fast_tube[skins]" value="1"<?php echo $skins; ?> /></td>
		</tr>
		<tr><td class="ri nb">Select skin for the thumbnails &rarr;</td><td class="ri nb">
		<select name="fast_tube[skin]">
		<?php
		$dir = dirname(__FILE__).'/skins/';
		if(is_dir($dir)) {
			if($handle = opendir($dir)) {
				while($file = readdir($handle)) {
					if(is_dir($dir.$file) && $file != '.' && $file != '..') {
						$allskins[] = $file;
					}
				}
				closedir($handle);
				sort($allskins);
				foreach($allskins as $oneskin) {
					$sel = $options['skin'] == $oneskin ? ' selected="selected"' : '';
					echo '<option value="'.$oneskin.'"'.$sel.'>'.$oneskin.'</option>';			
				}			
			}
		}
		?>
		</select>
		</td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more3');" value="more" />
		<div id="more3" class="more" style="display:none;">
		<div><small>Enable this if you want Fast Tube to show thumbnail instead of video player if not on a single post or page. It is very useful if you put a lot of videos on your index page for example. Using thumbnails will cause your blog to load faster. There are two default thumbnail sizes you can choose from. Note that clicking the thumb will lead you right to the video player. It's the same as when clicking the permalink of your post. Choose a skin to make the thumbnails look even better. If you want to create your own skins, navigate to the top of this page and hit the button called Fast Tube Skins.</small><h5>Fast Tube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Enable Fast Tube AutoAlign</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[align]" value="1"<?php echo $align; ?> /></td>
		</tr>
		<tr><td class="ri nb">Select the way you want Fast Tube to align your videos &rarr;</td><td class="ri nb">
		<select name="fast_tube[alignment]">
			<option value="center"<?php echo $center; ?>>Center</option>
			<option value="left"<?php echo $left; ?>>Left</option>
			<option value="right"<?php echo $right; ?>>Right</option>
		</select>
		</td>
		</tr>
		<tr><td class="ri nb">Select the tag you want Fast Tube to use for alignment &rarr;</td><td class="ri nb">
		<select name="fast_tube[aligntag]">
			<option value="span"<?php echo $span; ?>>span</option>
			<option value="div"<?php echo $div; ?>>div</option>
			<option value="p"<?php echo $p; ?>>p</option>
		</select>
		</td>
		</tr>
		<tr><td class="ri nb">Enter class name that you want to assign to the align tag &rarr;</td><td class="ri nb">
		<input type="text" name="fast_tube[alignclass]" value="<?php echo $options['alignclass']; ?>" size="10" />
		</td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more4');" value="more" />
		<div id="more4" class="more" style="display:none;">
		<div><small>If you don't want to waste time aligning the videos through the Post/Page editor, Fast Tube can do it automatically. You can choose the direction of alignment - center, left or right and the block element you want to put the videos into - div, p, span. Keep in mind that "span" is an inline element, so it is always styled with "display:block;" here. Use div or p if you don't mind that your code won't be a valid XHTML code. Block elements just break it. Additionally, you can add class or classes (separated by space, of course) to style the selected alignment tag. Use classes from the style.css of your current theme or just add some new there.</small><h5>Fast Tube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Start video, skipping the first N seconds</em></td>
		<td class="ri"><input type="text" name="fast_tube[start]" value="<?php echo $options['start']; ?>" size="4" /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more5');" value="more" />
		<div id="more5" class="more" style="display:none;">
		<div><small>Enable this if you want to start the videos, skipping the amount of seconds you set.</small><h5>YouTube default setting: Starts from 0</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Enable fullscreen button</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[fullscreen]" value="1"<?php echo $fullscreen; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more6');" value="more" />
		<div id="more6" class="more" style="display:none;">
		<div><small>If this is checked, the fullscreen button will be visible. Otherwise it will be hidden.</small><h5>YouTube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Enable Enhanced Genie Menu</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[egm]" value="1"<?php echo $egm; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more7');" value="more" />
		<div id="more7" class="more" style="display:none;">
		<div><small>If checked, this option causes the genie menu to appear when the user's mouse enters the video display area, as opposed to only appearing when the menu button is pressed. Works if "Show related videos" option is also enabled.</small><h5>YouTube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Disable player keyboard controls</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[disablekb]" value="1"<?php echo $disablekb; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more8');" value="more" />
		<div id="more8" class="more" style="display:none;">
		<div><small>If checked, this option disables all player keyboard controls: 1) Spacebar: Play / Pause, 2) Arrow Left: Restart current video, 3)Arrow Right: Jump ahead 10% in the current video, 4) Arrow Up: Volume up, 5) Arrow Down: Volume Down.</small><h5>YouTube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Autoplay the videos</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[autoplay]" value="1"<?php echo $autoplay; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more9');" value="more" />
		<div id="more9" class="more" style="display:none;">
		<div><small>If this option is checked, the initial video will autoplay when the player loads. When there are more than 1 video in the same post or page, autoplay option is ignored, preventing multiple videos from start playing at the same time.</small><h5>YouTube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Loop the videos</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[loop]" value="1"<?php echo $loop; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more10');" value="more" />
		<div id="more10" class="more" style="display:none;">
		<div><small>If this option is checked and it's a single video, the player will loop the video. But if it's a playlist player, the player will loop the whole playlist, starting from the first video.</small><h5>YouTube default setting: OFF</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Show related videos once playback starts</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[related]" value="1"<?php echo $related; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more11');" value="more" />
		<div id="more11" class="more" style="display:none;">
		<div><small>If this option is checked, the player will load related videos. Keep im mind that if this option is not checked the Enhanced Genie Menu and the Search box won't work.</small><h5>YouTube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Show title and ratings before start</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[info]" value="1"<?php echo $info; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more12');" value="more" />
		<div id="more12" class="more" style="display:none;">
		<div><small>If checked you will be able to see the video title and ratings right before you start playing the video.</small><h5>YouTube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Show search box when minimized</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[search]" value="1"<?php echo $search; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more13');" value="more" />
		<div id="more13" class="more" style="display:none;">
		<div><small>Enables the search box when the video is minimized. "Show related videos" must be enabled if you want this option to work".</small><h5>YouTube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Show video annotations</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[annotations]" value="1"<?php echo $annotations; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more14');" value="more" />
		<div id="more14" class="more" style="display:none;">
		<div><small>If this option is checked, it will cause video annotations to be shown by default.</small><h5>YouTube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Use Valid XHTML Code</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[valid]" value="1"<?php echo $valid; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more15');" value="more" />
		<div id="more15" class="more" style="display:none;">
		<div><small>You already know that YouTube gives you some old and junky code, which validators don't like very much. Keep this enabled and forget about the validation errors.</small><h5>Fast Tube default setting: ON</h5></div>
		</div></td></tr>
		<tr valign="top">
		<td>&rarr; <em>Display small info about the author under the video</em></td>
		<td class="ri"><input type="checkbox" name="fast_tube[author]" value="1"<?php echo $author; ?> /></td>
		</tr>
		<tr><td colspan="2" class="nb"><input type="button" class="button" onclick="javascript:moreInfo('more16');" value="more" />
		<div id="more16" class="more" style="display:none;">
		<div><small>This will show a small text - <strong>Fast Tube by Casper</strong> - under your videos. If you appreciate my work you are free to support this plugin by keeping this option enabled as you are free to <a title="WordPress Plugins by Casper" href="http://blog.caspie.net/downloads/" target="_blank">check out my other WordPress plugins or to make a donation</a>. Thanks in advance!</small><h5>Fast Tube default setting: ON</h5></div>
		</div></td></tr>
		<tr><td colspan="2" class="ri nb">&larr; <small><a title="Back to top" href="#start"><strong>top</strong></a></small> &rarr;</td></tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<p class="submit" style="width:520px;text-align:center;">
		<input type="submit" name="submit" class="button" value="Save Changes" /><input type="submit" name="reset" class="button" value="Reset to Defaults" />
		</p>
	</form>
</div>

<?php
}
add_action('plugins_loaded', 'fast_tube_start');
function fast_tube_start($reset) {
	$all_options = array(
		'width' => 425,
		'height' => 344,
		'border' => false,
		'color1' => '999999',
		'color2' => 'cccccc',
		'fullscreen' => true,
		'egm' => false,
		'disablekb' => false,
		'autoplay' => false,
		'loop' => false,
		'related' => true,
		'info' => true,
		'search' => true,
		'annotations' => 1,
		'start' => 0,
		'align' => false,
		'alignment' => 'center',
		'aligntag' => 'span',
		'alignclass' => '',
		'thumb' => false,
		'thumbnail' => false,
		'skins' => false,
		'skin' => 'default-blue',
		'valid' => true,
		'author' => true
	);
	$reset ? update_option('fast_tube_options',$all_options) : add_option('fast_tube_options',$all_options);
}
function fast_tube_excerpt($text) {
	return preg_replace('/(?:<br \/>)?Fast Tube '.FAST_TUBE_VERSION.' by Casper(?:<br \/>)?/', '', $text);
}
add_filter('the_excerpt', 'fast_tube_excerpt');
define('FAST_TUBE_VERSION','2.3.1');
?>