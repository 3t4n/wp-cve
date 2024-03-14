<?php

function nggcb_options_page() {

global $nggcb_options;

ob_start();

?>
	
<div class="wrap">
<h2>NextGEN Gallery ColorBoxer</h2>

	<div class="nggcb_box">	
		<form method="post" action="options.php">
		<?php settings_fields('nextgen_gallery_colorboxer_settings_group'); ?>
			
			<p>
				<label><b>ColorBox opacity:&nbsp;&nbsp;</b>
					<?php $styles = array('0.00', '0.05', '0.10', '0.15', '0.20', '0.25', '0.30', '0.35', '0.40', '0.45', '0.50', '0.55', '0.60', '0.65', '0.70', '0.75', '0.80', '0.85', '0.90', '0.95', '1.00'); ?>
					<select name="nextgen_gallery_colorboxer_settings[colorbox_opacity]" id="nextgen_gallery_colorboxer_settings[colorbox_opacity]">
						<?php foreach($styles as $style) { ?>
							<?php if ($nggcb_options['colorbox_opacity'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
							<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option>
						<?php } ?>
					</select>
				</label>
			</p>

			<p>
				<label><b>ColorBox transition:&nbsp;&nbsp;</b>
					<?php $styles = array('none', 'fade', 'elastic'); ?>
					<select name="nextgen_gallery_colorboxer_settings[colorbox_transition]" id="nextgen_gallery_colorboxer_settings[colorbox_transition]">
						<?php foreach($styles as $style) { ?>
							<?php if ($nggcb_options['colorbox_transition'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
							<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option>
						<?php } ?>
					</select>
				</label>
			</p>


		<input type="submit" class="button-primary" value="<?php _e('Save Options', 'nextgen_colorboxer_domain'); ?>" />


</div><!-- end .nggcb_box -->		


	<div class="nggcb_box">
		<h2><?php _e('Tips:', 'nggcb_domain'); ?></h2>
		1. If ColorBox isn't working as it should, try deactivating other ColorBox/lightbox plugins which may be causing a conflict, 
		and try removing any duplicate ColorBox scripts hard-coded into your theme.<br /><br />
		
		2. Lightbox scripts such as ColorBox aren't generally compatible with minification/caching/combining plugins. 
		If you're using a plugin such as WP-Minify, be sure to list the already minified <b><?php echo plugins_url( 'colorbox/js/jquery.colorbox-min.js' , __FILE__); ?></b>
		in its file exclusion options and clear the cache.
	</div>

	<!-- hidden fields for persistent settings in options array -->
	<input id="nextgen_gallery_colorboxer_settings[version]" name="nextgen_gallery_colorboxer_settings[version]" type="hidden" value="<?php echo $nggcb_options['version']; ?>"/>
	<input id="nextgen_gallery_colorboxer_settings[original_nextgen_thumbEffect]" name="nextgen_gallery_colorboxer_settings[original_nextgen_thumbEffect]" type="hidden" value="<?php echo $nggcb_options['auto_colorbox_install']; ?>"/>	
	<input id="nextgen_gallery_colorboxer_settings[original_nextgen_thumbEffect]" name="nextgen_gallery_colorboxer_settings[original_nextgen_thumbEffect]" type="hidden" value="<?php echo $nggcb_options['original_nextgen_thumbEffect']; ?>"/>
	<input id="nextgen_gallery_colorboxer_settings[original_nextgen_thumbCode]" name="nextgen_gallery_colorboxer_settings[original_nextgen_thumbCode]" type="hidden" value="<?php echo htmlspecialchars($nggcb_options['original_nextgen_thumbCode']); ?>"/>
	
</form>

	
	<div class="nggcb_box">
		<div class="nggcb_inner">
			<h2>Donate!</h2>
			If you would like to support further development of this plugin, or the creation of other helpful add-ons...please consider a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HRACRNYABWT7G">donation</a>!<br />
			It would be greatly appreciated...as would a <a href="http://wordpress.org/extend/plugins/nextgen-gallery-colorboxer">good rating</a> on WordPress.org.
		</div>

		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="HRACRNYABWT7G">
		<input type="image" <?php echo 'src="' . plugins_url( 'images/donate-button.gif' , __FILE__) . '" width="92" height="26" '; ?> border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>



	<div class="nggcb_box">
		<h2>Support:</h2>
		Any questions or suggestions?<br />
		Please <a href='mailto:mark@markstechnologynews.com'>send me an email</a>, or leave a message at the <a href="http://wordpress.org/support/plugin/nextgen-gallery-colorboxer">Support Forum</a>.
	</div>
		
</div><!-- end wrap -->



<?php
	echo ob_get_clean();
}