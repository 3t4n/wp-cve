<?php
	function selectionGen(&$option,&$array) {
		foreach($array as $key=> $ms) {
			$selected = ($option != $ms)? '' : ' selected';
			echo "<option value='$ms'$selected>".(($ms=='1'&&$array[0]!='0')?__('Disabled', 'wp-slimbox2'):$ms)."</option>\n";
		}
	}
	?>
<div class="wrap">
	<h2><?php _e('WP Slimbox2 Plugin', 'wp-slimbox2'); ?></h2>
	<form method="post" action="options.php">
<?php
	settings_fields('wp_slimbox_options');
	$easingArray = array('swing','easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic','easeOutCubic','easeInOutCubic','easeInQuart','easeOutQuart','easeInOutQuart','easeInQuint','easeOutQuint','easeInOutQuint','easeInSine','easeOutSine','easeInOutSine','easeInExpo','easeOutExpo','easeInOutExpo','easeInCirc','easeOutCirc','easeInOutCirc','easeInElastic','easeOutElastic','easeInOutElastic','easeInBack','easeOutBack','easeInOutBack','easeInBounce','easeOutBounce','easeInOutBounce');
	$overlayOpacity = array(0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1);
	$msArray = array(1,100,200,300,400,500,600,700,800,900,1000);
	$captions = array('a-title','img-alt','img-title','href','None');
?>
	<div style="clear:both;padding-top:5px;"></div>
		<h2><?php _e('Settings', 'wp-slimbox2');?></h2>
		<table class="widefat" cellspacing="0" id="inactive-plugins-table">
			<thead>
			<tr>
				<th scope="col" colspan="2"><?php _e('Setting', 'wp-slimbox2'); ?></th>
				<th scope="col"><?php _e('Description', 'wp-slimbox2'); ?></th>
			</tr>
			</thead>

			<tfoot>
			<tr>
				<th scope="col" colspan="3"><?php _e('Use the various options above to control some of the advanced settings of the plugin', 'wp-slimbox2'); ?></th>
			</tr>
			</tfoot>

			<tbody class="plugins">

			<tr class='inactive'>
				<td class='name'><?php _e('Autoload?', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[autoload]"<?php if (isset($options['autoload'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to automatically activate Slimbox on all links pointing to ".jpg", ".jpeg", ".png", ".bmp" or ".gif". All image links will automatically be grouped together in a gallery according to the selector chosen below. If this isn\'t activated you will need to manually add <b><code>rel="lightbox"</code></b> for individual images or <b><code>rel="lightbox-imagesetname"</code></b> for groups on all links you wish to use the Slimbox effect. <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Enable Picasaweb Integration?', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[picasaweb]"<?php if (isset($options['picasaweb'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to automatically add the Slimbox effect to Picasaweb links when provided an appropriate url (this is separate from the autoload script which only functions on direct image links). <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Enable Flickr Integration?', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[flickr]"<?php if (isset($options['flickr'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to automatically add the Slimbox effect to Flickr links when provided an appropriate url (this is separate from the autoload script which only functions on direct image links). <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Loop?', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[loop]"<?php if (isset($options['loop'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to navigate between the first and last images of a Slimbox gallery group when there is more than one image to display. <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Overlay Opacity', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[overlayOpacity]">
					<?php selectionGen($options['overlayOpacity'],$overlayOpacity); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the opacity of the background overlay. 1 is completely opaque, 0 is completely transparent. <b>Default is 0.8.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Overlay Color', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" id="wp_slimbox_overlayColor" name="wp_slimbox[overlayColor]" value="<?php echo $options['overlayColor']; ?>" size="7" maxlength="7"/><div id="picker"></div>

				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to set the color of the overlay by selecting your hue from the circle and color gradient from the square. Alternatively you may manually enter a valid HTML color code. The color of the entry field will change to reflect your selected color. <b>Default is #000000.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Overlay Fade Duration', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[overlayFadeDuration]">
					<?php selectionGen($options['overlayFadeDuration'],$msArray); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the duration of the overlay fade-in and fade-out animations, in milliseconds. <b>Default is 400.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Resize Duration', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[resizeDuration]">
					<?php selectionGen($options['resizeDuration'],$msArray); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the duration of the resize animation for width and height, in milliseconds. <b>Default is 400.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Resize Easing', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[resizeEasing]">
					<?php selectionGen($options['resizeEasing'],$easingArray); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the easing effect that you want to use for the resize animation (easings other than swing load an additional jQuery Easing Plugin). Many easings require a longer execution time to look good, so you should adjust the resizeDuration option above as well. <b>Default is swing.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Initial Width', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[initialWidth]" value="<?php echo $options['initialWidth']; ?>" />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the initial width of the box, in pixels. <b>Default is 250.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Initial Height', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[initialHeight]" value="<?php echo $options['initialHeight']; ?>" />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the initial height of the box, in pixels. <b>Default is 250.</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Image Fade Duration', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[imageFadeDuration]">
					<?php selectionGen($options['imageFadeDuration'],$msArray); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the duration of the image fade-in animation, in milliseconds. Disabling this effect will make the image appear instantly. <b>Default is 400.</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Caption Animation Duration', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[captionAnimationDuration]">
					<?php selectionGen($options['captionAnimationDuration'],$msArray); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to adjust the duration of the caption animation, in milliseconds. Disabling this effect will make the caption appear instantly. <b>Default is 400.</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Image Caption Source Order', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<select name="wp_slimbox[caption][]">
					<?php selectionGen($options['caption'][0],$captions); ?>
					</select>
					<select name="wp_slimbox[caption][]">
					<?php selectionGen($options['caption'][1],$captions); ?>
					</select>
					<select name="wp_slimbox[caption][]">
					<?php selectionGen($options['caption'][2],$captions); ?>
					</select>
					<select name="wp_slimbox[caption][]">
					<?php selectionGen($options['caption'][3],$captions); ?>
					</select>
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to select the order in which to search various locations for the caption text. If you\'d like no caption just select "None" in the first block. You can also leave out an option by replacing it with "None", but be sure to place any option you\'d like to search in front of it. If a caption can\'t be found, and "None" wasn\'t selected, it will default to the URL ("href"). <b>Default is "a-title", followed by "img-alt", "img-title", and "href".</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Caption is URL', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[url]"<?php if (isset($options['url'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option will render the caption as a hyperlink.  <b>Default is Enabled.</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Autoload Selector', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[selector]" value="<?php echo $options['selector']; ?>" />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to change how images are grouped when autoload is enabled. It uses jQuery selectors, as described <a href="http://api.jquery.com/category/selectors/">here</a>. <b>Default is "div.entry-content, div.gallery, div.entry, div.post, div#page, body".</b>', 'wp-slimbox2'); ?>
					</p>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Counter Text', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[counterText]" value="<?php echo $options['counterText']; ?>" />
				</th>
				<td class='desc'>
					<p> <?php _e('This option allows the user to customize, translate or disable the counter text which appears in the captions when multiple images are shown. Inside the text, {x} will be replaced by the current image index, and {y} will be replaced by the total number of images in the group. Set it to false (boolean value, without quotes) or "" to disable the counter display. <b>Default is "Image {x} of {y}".</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Close Keys', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[closeKeys]" class="keys" value="<?php echo $options['closeKeys']; ?>"/>
				</th>
				<td class='desc' rowspan=3>
					<p> <?php _e('These options allow the user to specify an array of <a href="http://www.webonweboff.com/tips/js/event_key_codes.aspx" TARGET="_blank">key codes</a> representing the keys to press to close or navigate to the next or previous images.</p><p>Just select the corresponding text box and press the keys you would like to use. Alternately check the box below to manually enter or clear key codes.</p><b><p>Default close values are [27, 88, 67] which means Esc (27), "x" (88) and "c" (67).</p><p>Default previous values are [37, 80] which means Left arrow (37) and "p" (80).</p><p>Default next values are [39, 78] which means Right arrow (39) and "n" (78).', 'wp-slimbox2'); ?>
					</p></b><br /><b><?php _e('Enable Manual Key Code Entry?', 'wp-slimbox2'); ?></b><input type="checkbox" id="wp_slimbox_manual_key" value="manual_key"/><input type="hidden" id="wp_slimbox_key_defined" value="<?php _e('That key has already been defined.', 'wp-slimbox2'); ?>"/>
				</td>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Previous Keys', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[previousKeys]" class="keys" value="<?php echo $options['previousKeys']; ?>"/>
				</th>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Next Keys', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="text" name="wp_slimbox[nextKeys]" class="keys" value="<?php echo $options['nextKeys']; ?>"/>
				</th>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Enable on mobiles?', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[mobile]"<?php if (isset($options['mobile'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option enables Slimbox on mobile phones. <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
			<tr class='inactive'>
				<td class='name'><?php _e('Maintenance mode', 'wp-slimbox2'); ?></td>
				<th scope='row' class='check-column'>
					<input type="checkbox" name="wp_slimbox[maintenance]"<?php if (isset($options['maintenance'])) echo ' checked="yes"';?> />
				</th>
				<td class='desc'>
					<p> <?php _e('This option enables a maintenance mode for testing purposes. When enabled slimbox will be disabled until you enable it by appending <b><code>?slimbox=on</code></b> to a url. It will then remain on until you disable it by appending <b><code>?slimbox=off</code></b> to a url, you clear your cookies, or in certain cases you clear your browser cache. This setting only impacts things at an individual vistor level, not a site wide level. <b>Default is Disabled.</b>', 'wp-slimbox2'); ?>
					</p>
			</tr>
		</tbody>
		</table>
		<div style="clear:both;padding-top:20px;"></div>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Update Options','wp-slimbox2'); ?>" />
			</p>
		<div style="clear:both;padding-top:20px;"></div>
	</form>
	<div class="clear"></div>
	<h2><?php _e('Notes', 'wp-slimbox2');?></h2>
	<table class="widefat" cellspacing="0" id="active-plugins-table">
		<tfoot>
		<tr>
			<th scope="col"></th>
		</tr>

		</tfoot>
		<tbody class="plugins">
			<tr class="inactive">
				<td class="desc">
					<p><?php _e('Check out the <a href="http://transientmonkey.com/wp-slimbox2-user-guide">WP-Slimbox2 User Guide</a>!','wp-slimbox2'); ?></p>
					<p><?php _e('Support is graciously being hosted at <a href="http://pixopoint.com/forum/index.php?board=6.0">PixoPoint.com</a> I\'ll make an effort to stay apprised of any questions that may arise.','wp-slimbox2'); ?></p>
					<p><?php _e('The plugin webpage can be found at <a href="http://transientmonkey.com/wp-slimbox2">TransientMonkey.com</a>, feel free to leave comments, but don\'t post support questions, that\'s what the forum is for! Stay tuned in to all new Transient Monkey projects!','wp-slimbox2'); ?></p>
					<p><?php _e('If you\'d like to add or update a translation to WP-Slimbox2, please visit <a href="http://pixopoint.com/forum/index.php?topic=1383.0">this forum</a>. The more people who can easily use this plugin the better!','wp-slimbox2'); ?></p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="<?php _e('11145898','wp-slimbox2'); ?>">
			<input type="image" src="https://www.paypal.com/<?php _e('en_US','wp-slimbox2'); ?>/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - <?php _e('The safer, easier way to pay online!','wp-slimbox2'); ?>">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
        <p><b style="font-size:11px;"><?php _e('Support this plugin!','wp-slimbox2'); ?></b></p>
				</td>
			</tr>
		</tbody>
	</table>
</div>