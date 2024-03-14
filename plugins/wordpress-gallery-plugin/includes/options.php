<div class="wrap">
	<h2><?php _e('Wordpress Gallery Options','ns_wp_gallery_plugin'); ?></h2>

	<table cellspacing="5" cellpadding="5" border="0" width="100%">
	<tr>
	<td width="70%" valign="top">
		<?php
	if(isset($_POST['ns_wpg_options']))
	{
		echo '<div class="updated fade" id="message"><p>';
		_e('Wordpress Gallery Plugin Settings <strong>Updated</strong>');
		echo '</p></div>';
		unset($_POST['update']);
		update_option('ns_wpg_options', $_POST['ns_wpg_options']);
	}
	?>
	<?php
	$options=get_option('ns_wpg_options');
	$form_url=admin_url().'admin.php?page=wordpress-gallery-plugin/wp-gallery-plugin.php';
	?>

		<form name="ns_wpg_options_form" id="ns_wpg_options_form" method="POST" action="<?php echo $form_url;?>">
		<table cellspacing="5" cellpadding="5" class="widefat" width="400">
			<thead>
			<tr>
			<th scope="col" colspan="2"><?php _e('Slideshow Default Settings','ns_wp_gallery_plugin'); ?>
			</th>
			</tr>
			</thead>
			<tr>
			<td><?php _e('Effect : ','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[effect]" style="width:120px;">
<option value="sliceDown" <?php selected('sliceDown', $options['effect']); ?>><?php _e('sliceDown','ns_wp_gallery_plugin'); ?></option>
<option value="sliceDownLeft" <?php selected('sliceDownLeft', $options['effect']); ?>><?php _e('sliceDownLeft','ns_wp_gallery_plugin'); ?></option>
<option value="sliceUp" <?php selected('sliceUp', $options['effect']); ?>><?php _e('sliceUp','ns_wp_gallery_plugin'); ?></option>
<option value="sliceUpLeft" <?php selected('sliceUpLeft', $options['effect']); ?>><?php _e('sliceUpLeft','ns_wp_gallery_plugin'); ?></option>
<option value="sliceUpDown" <?php selected('sliceUpDown', $options['effect']); ?>><?php _e('sliceUpDown','ns_wp_gallery_plugin'); ?></option>
<option value="sliceUpDownLeft" <?php selected('sliceUpDownLeft', $options['effect']); ?>><?php _e('sliceUpDownLeft','ns_wp_gallery_plugin'); ?></option>
<option value="fold" <?php selected('fold', $options['effect']); ?>><?php _e('fold','ns_wp_gallery_plugin'); ?></option>
<option value="fade" <?php selected('fade', $options['effect']); ?>><?php _e('fade','ns_wp_gallery_plugin'); ?></option>
<option value="random" <?php selected('random', $options['effect']); ?>><?php _e('random','ns_wp_gallery_plugin'); ?></option>
<option value="slideInRight" <?php selected('slideInRight', $options['effect']); ?>><?php _e('slideInRight','ns_wp_gallery_plugin'); ?></option>
<option value="slideInLeft" <?php selected('slideInLeft', $options['effect']); ?>><?php _e('slideInLeft','ns_wp_gallery_plugin'); ?></option>
<option value="boxRandom" <?php selected('boxRandom', $options['effect']); ?>><?php _e('boxRandom','ns_wp_gallery_plugin'); ?></option>
<option value="boxRain" <?php selected('boxRain', $options['effect']); ?>><?php _e('boxRain','ns_wp_gallery_plugin'); ?></option>
<option value="boxRainReverse" <?php selected('boxRainReverse', $options['effect']); ?>><?php _e('boxRainReverse','ns_wp_gallery_plugin'); ?></option>
<option value="boxRainGrow" <?php selected('boxRainGrow', $options['effect']); ?>><?php _e('boxRainGrow','ns_wp_gallery_plugin'); ?></option>
<option value="boxRainGrowReverse" <?php selected('boxRainGrowReverse', $options['effect']); ?>><?php _e('boxRainGrowReverse','ns_wp_gallery_plugin'); ?></option>
</select><br /><span style="color:#666;"><small><?php _e('Select Default Effect for you slideshow. You can override this effect in shortcode.','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Slide transition speed :','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[animSpeed]" value="<?php echo $options['animSpeed']; ?>" size="15" /><br /><span style="color:#666;"><small><?php _e('Animation Speed in miliseconds eg. 500','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Pause Time :','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[pauseTime]" value="<?php echo $options['pauseTime']; ?>" size="15" /><br /><span style="color:#666;"><small><?php _e('How long each slide will show eg. 2000','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Next &amp; Previous Nav :','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[directionNav]" style="width:120px;">
<option value="true" <?php selected('true', $options['directionNav']); ?>><?php _e('True','ns_wp_gallery_plugin'); ?></option>
<option value="false" <?php selected('false', $options['directionNav']); ?>><?php _e('False','ns_wp_gallery_plugin'); ?></option>
</select>
<span style="color:#666;"><small><?php _e('Direction Nav','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Direction Nav Hide :','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[directionNavHide]" style="width:120px;">
<option value="true" <?php selected('true', $options['directionNavHide']); ?>><?php _e('True','ns_wp_gallery_plugin'); ?></option>
<option value="false" <?php selected('false', $options['directionNavHide']); ?>><?php _e('False','ns_wp_gallery_plugin'); ?></option>
</select><br />
<span style="color:#666;"><small><?php _e('Only show Next &amp; Previous nav on mouse hover ','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Control Nav:','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[controlNav]" style="width:120px;">
<option value="true" <?php selected('true', $options['controlNav']); ?>><?php _e('True','ns_wp_gallery_plugin'); ?></option>
<option value="false" <?php selected('false', $options['controlNav']); ?>><?php _e('False','ns_wp_gallery_plugin'); ?></option>
</select>
<span style="color:#666;"><small><?php _e('Display Bullet/1,2.3 navigation ','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
		<!--	<tr>
			<td><?php _e('Control Nav Position:','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[navPosition]" style="width:120px;">
<option value="topleft" <?php selected('topleft', $options['navPosition']); ?>><?php _e('Top Left','ns_wp_gallery_plugin'); ?></option>
<option value="topright" <?php selected('topright', $options['navPosition']); ?>><?php _e('Top Right','ns_wp_gallery_plugin'); ?></option>
<option value="bottomleft" <?php selected('bottomleft', $options['navPosition']); ?>><?php _e('Bottom Left','ns_wp_gallery_plugin'); ?></option>
<option value="bottomright" <?php selected('bottomright', $options['navPosition']); ?>><?php _e('Bottom Right','ns_wp_gallery_plugin'); ?></option>

</select><br />
<span style="color:#666;"><small><?php _e('Display Bullet/1,2.3 navigation at which location ','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			-->
			<tr>
			<td><?php _e('Keyboard Navigation using arrow keys:','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[keyboardNav]" style="width:120px;">
<option value="true" <?php selected('true', $options['keyboardNav']); ?>><?php _e('True','ns_wp_gallery_plugin'); ?></option>
<option value="false" <?php selected('false', $options['keyboardNav']); ?>><?php _e('False','ns_wp_gallery_plugin'); ?></option>
</select>
<span style="color:#666;"><small><?php _e('Keyboard navigation using arrow keys ','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Pause On Hover:','ns_wp_gallery_plugin'); ?></td>
			<td><select name="ns_wpg_options[pauseOnHover]" style="width:120px;">
<option value="true" <?php selected('true', $options['pauseOnHover']); ?>><?php _e('True','ns_wp_gallery_plugin'); ?></option>
<option value="false" <?php selected('false', $options['pauseOnHover']); ?>><?php _e('False','ns_wp_gallery_plugin'); ?></option>
</select><br />
<span style="color:#666;"><small><?php _e('Stop / Pause slideshow when you mouseover images.','ns_wp_gallery_plugin'); ?></small></span></td>
			</tr>
			<tr>
			<td><?php _e('Border Color : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" class="ns_wpg_color" name="ns_wpg_options[border_color]" value="<?php echo $options['border_color']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Select Border Color','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Border Size : ','ns_wp_gallery_plugin'); ?></td>
			<td>
			<select name="ns_wpg_options[border_size]" id="maxi_position" style="width:120px;">
			<option value="0px" <?php selected('0px', $options['border_size']); ?>><?php _e('0px','ns_wp_gallery_plugin'); ?></option>
			<option value="1px" <?php selected('1px', $options['border_size']); ?>><?php _e('1px','ns_wp_gallery_plugin'); ?></option>
			<option value="2px" <?php selected('2px', $options['border_size']); ?>><?php _e('2px','ns_wp_gallery_plugin'); ?></option>
			<option value="3px" <?php selected('3px', $options['border_size']); ?>><?php _e('3px','ns_wp_gallery_plugin'); ?></option>
<option value="4px" <?php selected('4px', $options['border_size']); ?>><?php _e('4px','ns_wp_gallery_plugin'); ?></option>
<option value="5px" <?php selected('5px', $options['border_size']); ?>><?php _e('5px','ns_wp_gallery_plugin'); ?></option>
<option value="6px" <?php selected('6px', $options['border_size']); ?>><?php _e('6px','ns_wp_gallery_plugin'); ?></option>
<option value="7px" <?php selected('6px', $options['border_size']); ?>><?php _e('7px','ns_wp_gallery_plugin'); ?></option>
<option value="8px" <?php selected('8px', $options['border_size']); ?>><?php _e('8px','ns_wp_gallery_plugin'); ?></option>
<option value="9px" <?php selected('9px', $options['border_size']); ?>><?php _e('9px','ns_wp_gallery_plugin'); ?></option>
<option value="10px" <?php selected('10px', $options['border_size']); ?>><?php _e('10px','ns_wp_gallery_plugin'); ?></option>
<option value="11px" <?php selected('11px', $options['border_size']); ?>><?php _e('11px','ns_wp_gallery_plugin'); ?></option>
<option value="12px" <?php selected('12px', $options['border_size']); ?>><?php _e('12px','ns_wp_gallery_plugin'); ?></option>
<option value="13px" <?php selected('13px', $options['border_size']); ?>><?php _e('13px','ns_wp_gallery_plugin'); ?></option>
<option value="14px" <?php selected('14px', $options['border_size']); ?>><?php _e('14px','ns_wp_gallery_plugin'); ?></option>
<option value="15px" <?php selected('15px', $options['border_size']); ?>><?php _e('15px','ns_wp_gallery_plugin'); ?></option>
<option value="16px" <?php selected('16px', $options['border_size']); ?>><?php _e('16px','ns_wp_gallery_plugin'); ?></option>
<option value="17px" <?php selected('17px', $options['border_size']); ?>><?php _e('17px','ns_wp_gallery_plugin'); ?></option>
<option value="18px" <?php selected('18px', $options['border_size']); ?>><?php _e('18px','ns_wp_gallery_plugin'); ?></option>
<option value="19px" <?php selected('19px', $options['border_size']); ?>><?php _e('19px','ns_wp_gallery_plugin'); ?></option>
<option value="20px" <?php selected('20px', $options['border_size']); ?>><?php _e('20px','ns_wp_gallery_plugin'); ?></option>
			</select>
			<span style="color:#666;"><small><?php _e('Select Border size.','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Width : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[width]" value="<?php echo $options['width']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Enter width in PX eg. 500','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Height : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[height]" value="<?php echo $options['height']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Enter width in PX eg. 300','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('LightBox : ','ns_wp_gallery_plugin'); ?></td>
			<td>
			<select name="ns_wpg_options[lightbox]" style="width:120px;">
			<option value="yes" <?php selected('yes', $options['lightbox']); ?>><?php _e('Yes','ns_wp_gallery_plugin'); ?></option>
			<option value="no" <?php selected('no', $options['lightbox']); ?>><?php _e('No','ns_wp_gallery_plugin'); ?></option>
			</select>
			<span style="color:#666;"><small><?php _e('Select Yes If you want to open images in Lightbox.'); ?></small></span>
			</td>
			</tr>
			
			
			
			<tr><td colspan="2">
			<input type="hidden" name="ns_wpg_options[update]" value="UPDATED" />
            <input type="submit" class="button-primary" value="<?php _e('Save Settings','ns_wp_gallery_plugin') ?>" />
			</td></tr>
		</table>
		<br />
		<table cellspacing="5" cellpadding="5" class="widefat" width="400">
			<thead>
			<tr>
			<th scope="col" colspan="2"><?php _e('Thumbnail Gallery Settings','ns_wp_gallery_plugin'); ?>
			</th>
			</tr>
			</thead>
			<tr>
			<td><?php _e('Border Color : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" id="ns_wpg_border_thumb" name="ns_wpg_options[thumb_border_color]" class="ns_wpg_color" value="<?php echo $options['thumb_border_color']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Select Border Color','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Mouse Over Border Color : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" id="ns_wpg_border_thumb_m" class="ns_wpg_color" name="ns_wpg_options[thumb_border_color_mover]" value="<?php echo $options['thumb_border_color_mover']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Select Border Color','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Border Size : ','ns_wp_gallery_plugin'); ?></td>
			<td>
			<select name="ns_wpg_options[thumb_border_size]" style="width:120px;">
			<option value="0px" <?php selected('0px', $options['thumb_border_size']); ?>><?php _e('0px','ns_wp_gallery_plugin'); ?></option>
			<option value="1px" <?php selected('1px', $options['thumb_border_size']); ?>><?php _e('1px','ns_wp_gallery_plugin'); ?></option>
			<option value="2px" <?php selected('2px', $options['thumb_border_size']); ?>><?php _e('2px','ns_wp_gallery_plugin'); ?></option>
			<option value="3px" <?php selected('3px', $options['thumb_border_size']); ?>><?php _e('3px','ns_wp_gallery_plugin'); ?></option>
<option value="4px" <?php selected('4px', $options['thumb_border_size']); ?>><?php _e('4px','ns_wp_gallery_plugin'); ?></option>
<option value="5px" <?php selected('5px', $options['thumb_border_size']); ?>><?php _e('5px','ns_wp_gallery_plugin'); ?></option>
<option value="6px" <?php selected('6px', $options['thumb_border_size']); ?>><?php _e('6px','ns_wp_gallery_plugin'); ?></option>
<option value="7px" <?php selected('6px', $options['thumb_border_size']); ?>><?php _e('7px','ns_wp_gallery_plugin'); ?></option>
<option value="8px" <?php selected('8px', $options['thumb_border_size']); ?>><?php _e('8px','ns_wp_gallery_plugin'); ?></option>
<option value="9px" <?php selected('9px', $options['thumb_border_size']); ?>><?php _e('9px','ns_wp_gallery_plugin'); ?></option>
<option value="10px" <?php selected('10px', $options['thumb_border_size']); ?>><?php _e('10px','ns_wp_gallery_plugin'); ?></option>
<option value="11px" <?php selected('11px', $options['thumb_border_size']); ?>><?php _e('11px','ns_wp_gallery_plugin'); ?></option>
<option value="12px" <?php selected('12px', $options['thumb_border_size']); ?>><?php _e('12px','ns_wp_gallery_plugin'); ?></option>
<option value="13px" <?php selected('13px', $options['thumb_border_size']); ?>><?php _e('13px','ns_wp_gallery_plugin'); ?></option>
<option value="14px" <?php selected('14px', $options['thumb_border_size']); ?>><?php _e('14px','ns_wp_gallery_plugin'); ?></option>
<option value="15px" <?php selected('15px', $options['thumb_border_size']); ?>><?php _e('15px','ns_wp_gallery_plugin'); ?></option>
<option value="16px" <?php selected('16px', $options['thumb_border_size']); ?>><?php _e('16px','ns_wp_gallery_plugin'); ?></option>
<option value="17px" <?php selected('17px', $options['thumb_border_size']); ?>><?php _e('17px','ns_wp_gallery_plugin'); ?></option>
<option value="18px" <?php selected('18px', $options['thumb_border_size']); ?>><?php _e('18px','ns_wp_gallery_plugin'); ?></option>
<option value="19px" <?php selected('19px', $options['thumb_border_size']); ?>><?php _e('19px','ns_wp_gallery_plugin'); ?></option>
<option value="20px" <?php selected('20px', $options['thumb_border_size']); ?>><?php _e('20px','ns_wp_gallery_plugin'); ?></option>
			</select>
			<span style="color:#666;"><small><?php _e('Select Border size.','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Thumbnail Width : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[thumb_width]" value="<?php echo $options['thumb_width']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Enter width in PX eg. 500','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Thumbnail Height : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[thumb_height]" value="<?php echo $options['thumb_height']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Enter height in PX eg. 300','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('Number of Images Per row : ','ns_wp_gallery_plugin'); ?></td>
			<td><input type="text" name="ns_wpg_options[prow]" value="<?php echo $options['prow']; ?>" size="15" />
			<span style="color:#666;"><small><?php _e('Number of images to display in one row.','ns_wp_gallery_plugin'); ?></small></span>
			</td>
			</tr>
			<tr>
			<td><?php _e('LightBox : ','ns_wp_gallery_plugin'); ?></td>
			<td>
			<select name="ns_wpg_options[thumb_lightbox]" style="width:120px;">
			<option value="yes" <?php selected('yes', $options['thumb_lightbox']); ?>><?php _e('Yes','ns_wp_gallery_plugin'); ?></option>
			<option value="no" <?php selected('no', $options['thumb_lightbox']); ?>><?php _e('No','ns_wp_gallery_plugin'); ?></option>
			</select><br />
			<span style="color:#666;"><small><?php _e('Select Yes If you want to open images in Lightbox.'); ?></small></span>
			</td>
			</tr>
			
			<tr><td colspan="2">
			<input type="hidden" name="ns_wpg_options[update]" value="UPDATED" />
            <input type="submit" class="button-primary" value="<?php _e('Save Settings','ns_wp_gallery_plugin') ?>" />
			</td></tr>
		</table>
		<br />
	</td>
	<td width="70%" valign="top">
		<?php include_once dirname(__FILE__).'/our_feeds.php'; ?>
	</td>
	</tr>
	</table>
</div>