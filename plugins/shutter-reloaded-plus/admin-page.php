<?php // This file is part of Shutter Reloaded Plus WordPress plugin
if ( !defined('SREL_SETTINGS') )
	exit;
if ( ! current_user_can('manage_options') )
		die( __('Permission denied', 'srel_l10n') );
	$opt = get_option('srel_main');
	wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
	if ( !$opt )
		$opt = 'auto_set';
	srel_txtdomain();
	$srel_options = get_option('srel_options', array());
	$def = array( 'shcolor' => '#000000', 'opacity' => '70', 'capcolor' => '#ffffff', 'menucolor' => '#000000', 'btncolor' => '#cccccc', 'countcolor' => '#999999', 'headload' => 0, 'oneset' => 1, 'imageCount' => 1, 'textBtns' => 0, 'custom' => 0, 'showfblike' => 1, 'onlyonsingle' => 0 , 'overwritenextgen' => 1 );
	if ( empty($srel_options) ) {
		$srel_options = $def;
		update_option('srel_options', $srel_options);
	}
	if ( isset($_POST['srel_main']) ) {
		check_admin_referer('srel-save-options');
		$newopt = $_POST['srel_all'] ? 'srel_all' : '';
		$newopt = $_POST['srel_class'] ? 'srel_class' : $newopt;
		$newopt = $_POST['auto_set'] ? 'auto_set' : $newopt;
		$newopt = $_POST['srel_pages'] ? 'srel_pages' : $newopt;
		$newopt = $_POST['srel_lb'] ? 'srel_lb' : $newopt;
		if ( $newopt != $opt ) {
			$opt = $newopt;
			update_option('srel_main', $newopt);
		}
	}
	if ( isset($_POST['srel_saveopt']) ) {
		check_admin_referer('srel-save-options');
		$new_opt['shcolor'] = preg_match("/[0-9A-Fa-f#]{7}/", $_POST['shcolor']) ? strtolower($_POST['shcolor']) : '#000000';
		$new_opt['capcolor'] = preg_match("/[0-9A-Fa-f#]{7}/", $_POST['capcolor']) ? strtolower($_POST['capcolor']) : '#ffffff';
		$new_opt['menucolor'] = preg_match("/[0-9A-Fa-f#]{7}/", $_POST['menucolor']) ? strtolower($_POST['menucolor']) : '#000000';
		$new_opt['btncolor'] = preg_match("/[0-9A-Fa-f#]{7}/", $_POST['btncolor']) ? strtolower($_POST['btncolor']) : '#cccccc';
		$new_opt['countcolor'] = preg_match("/[0-9A-Fa-f#]{7}/", $_POST['countcolor']) ? strtolower($_POST['countcolor']) : '#999999';
		$new_opt['imageCount'] = isset($_POST['imageCount']) ? 1 : 0;
		$new_opt['textBtns'] = isset($_POST['textBtns']) ? 1 : 0;
		$new_opt['opacity'] = -1 < (int) $_POST['opacity'] && (int) $_POST['opacity'] < 101 ? (int) $_POST['opacity'] : '70';
		$new_opt['headload'] = isset($_POST['headload']) ? 1 : 0;
		$new_opt['startFull'] = isset($_POST['startFull']) ? 1 : 0;
		$new_opt['oneset'] = isset($_POST['oneset']) ? 1 : 0;
		$new_opt['showfblike'] = isset($_POST['showfblike']) ? 1 : 0;
		$new_opt['onlyonsingle'] = isset($_POST['onlyonsingle']) ? 1 : 0;
		$new_opt['overwritenextgen'] = isset($_POST['overwritenextgen']) ? 1 : 0;
		$new_opt['custom'] = ( $new_opt['shcolor'] != '#000000' ||
			$new_opt['capcolor'] != '#ffffff' ||
			$new_opt['menucolor'] != '#000000' ||
			$new_opt['btncolor'] != '#cccccc' ||
			$new_opt['countcolor'] != '#999999' ||
			$new_opt['opacity'] != '70' ) ? 1 : 0;
		if ( $new_opt != $srel_options ) {
			$srel_options = $new_opt;
			update_option('srel_options', $new_opt);
		}
		if($new_opt['overwritenextgen']==1){
			$ngg_options = get_option('ngg_options');
			$ngg_options['thumbEffect'] = 'none';
			update_option('ngg_options', $ngg_options);
		}
	}
	$excluded = get_option('srel_excluded', array());
	$included = get_option('srel_included', array());
	if ( isset($_POST['srel_add_excluded']) ) {
		check_admin_referer('srel-save-options');
		$exclude = (int) $_POST['srel_exclude'];
		if ( $exclude < 1 )
			wp_die(__('Please enter valid post ID.', 'srel-l10n'));

		$excluded[] = $exclude;
		$excluded = array_values(array_unique($excluded));
		sort($excluded);
		update_option('srel_excluded', $excluded);
	}
	if ( isset($_POST['srel_rem_excluded']) ) {
		check_admin_referer('srel-save-options');
		$rem_exclude = (int) $_POST['srel_exclude'];
		if ( ! in_array($rem_exclude, $excluded) ) { ?>
			<div class="error"><p><?php _e('This post ID is not currently excluded.', 'srel-l10n'); ?></p></div>
<?php	} else {
			$excluded = array_diff($excluded, (array) $rem_exclude );
			if ( is_array($excluded) ) sort($excluded);
			else $excluded = array();
			update_option('srel_excluded', $excluded);
		}
	}
	if ( isset($_POST['srel_add_included']) ) {
		check_admin_referer('srel-save-options');
		$include = (int) $_POST['srel_include'];
		if ( $include < 1 )
			wp_die(__('Please enter valid post ID.', 'srel-l10n'));

		$included[] = $include;
		$included = array_values(array_unique($included));
		sort($included);
		update_option('srel_included', $included);
	}
	if ( isset($_POST['srel_rem_included']) ) {
		check_admin_referer('srel-save-options');
		$rem_include = (int) $_POST['srel_include'];
		if ( ! in_array($rem_include, $included) ) { ?>
			<div class="error"><p><?php _e('This post ID is not currently included.', 'srel-l10n'); ?></p></div>
<?php   } else {
			$included = array_diff($included, (array) $rem_include);
			if ( is_array($included) ) sort($included);
			else $included = array();
			update_option('srel_included', $included);
		}
	}
	if ( isset($_POST['srel_delete_options']) ) {
		check_admin_referer('srel-save-options');
		delete_option('srel_options');
		delete_option('srel_main');
		delete_option('srel_included');
		delete_option('srel_excluded'); ?>
		<div id="message" class="updated fade"><p><?php _e('All options reset! You can either ', 'srel-l10n'); ?><a href="<?php echo admin_url('plugins.php'); ?>"><?php _e( 'deactivate Shutter Reloaded Plus,', 'srel-l10n' ); ?></a> <a href=""><?php _e( 'or return to the settings page.', 'srel-l10n' ); ?></a></p></div><?php
		return;
	} elseif ( isset($_POST['srel_saveopt']) ) { ?>
	<div id="message" class="updated fade"><p><?php _e('Options saved!', 'srel-l10n'); ?></p></div>
<?php }
?>
	<div class="wrap">
	<h2><?php _e('Shutter Reloaded Plus Options', 'srel-l10n'); ?></h2>
	<h3>Activation</h3>
	<form method="post" name="srel_mainform" id="srel_mainform" action="">
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('You can add Shutter Reloaded Plus to your site in five different ways:', 'srel-l10n'); ?></th>
				<td>
					<?php wp_nonce_field( 'srel-save-options' ); ?>
						<input type="hidden" name="srel_main" value="srel_main" />
					<?php
						if ( $opt == 'srel_class' )
							echo '<div style="background-color:#ffffe0;border:1px solid #e6db55;" class="fade"><p style="margin:8px;"><strong>'.__('Active: ', 'srel-l10n').'</strong>';
						else
							echo '<div><p><input class="button" type="submit" name="srel_class" value="'. __('Activate', 'srel-l10n').'" /> ';
						echo __('Shutter on all image links with class=&quot;shutter&quot; or &quot;shutterset&quot; or &quot;shutterset_setname&quot;.', 'srel-l10n')."</p></div>\n";

						if ( $opt == 'srel_all' )
							echo '<div style="background-color:#ffffe0;border:1px solid #e6db55;" class="fade"><p style="margin:8px;"><strong>'.__('Active: ', 'srel-l10n').'</strong>';
						else
							echo '<div><p><input class="button" type="submit" name="srel_all" value="'.__('Activate', 'srel-l10n').'" /> ';
						echo __('Shutter on all image links. Sets created with class=&quot;shutterset&quot;, &quot;shutterset_setname&quot; or rel=&quot;lightbox[...]&quot; will still work.', 'srel-l10n')."</p></div>\n";

						if ( $opt == 'auto_set' )
							echo '<div style="background-color:#ffffe0;border:1px solid #e6db55;" class="fade"><p style="margin:8px;"><strong>'.__('Active: ', 'srel-l10n').'</strong>';
						else
							echo '<div><p><input class="button" type="submit" name="auto_set" value="'.__('Activate', 'srel-l10n').'" /> ';
						echo __('Shutter on all image links and automatically make image sets for each Post/Page.', 'srel-l10n')."</p></div>\n";

						if ( $opt == 'srel_pages' )
							echo '<div style="background-color:#ffffe0;border:1px solid #e6db55;" class="fade"><p style="margin:8px;"><strong>'.__('Active: ', 'srel-l10n').'</strong>';
						else
							echo '<div><p><input class="button" type="submit" name="srel_pages" value="'.__('Activate', 'srel-l10n').'" /> ';
						echo __('Shutter on all image links on specific page(s).', 'srel-l10n')."</p></div>\n";

						if ( $opt == 'srel_lb' )
							echo '<div style="background-color:#ffffe0;border:1px solid #e6db55;" class="fade"><p style="margin:8px;"><strong>'.__('Active: ', 'srel-l10n').'</strong>';
						else
							echo '<div><p><input class="button" type="submit" name="srel_lb" value="'.__('Activate', 'srel-l10n').'" /> ';
						echo __('Shutter on all image links and use LightBox style (rel=&quot;lightbox[...]&quot;) activation and sets.', 'srel-l10n')."</p></div>\n"; ?>
				</td>
			</tr>
		</tbody>
		</table>
	</form>


<?php
	if ( $opt == 'srel_all' || $opt == 'auto_set' ) { ?>
	<form method="post" name="srel_excluded" id="srel_excluded" action="">
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Shutter is activated for all links pointing to an image.', 'srel-l10n'); ?></th>
				<td>
					<?php
						if ( $opt == 'srel_all' ) { ?>
						<p><strong><?php _e('Shutter is activated for all links pointing to an image.', 'srel-l10n'); ?></strong></p>
						<?php }
						else { ?><p><strong><?php _e('Shutter is activated for all links pointing to an image and will create different image set for each Post/Page.', 'srel-l10n'); ?></strong><br /><?php _e('This option is most suitable if you display several Posts on your home page and want to have different image set for each Post. It adds shutter\'s activation class at runtime and doesn\'t modify the html.', 'srel-l10n'); ?></p><?php }
					?>
					<p><?php _e('Excluded Posts or Pages (by ID):', 'srel-l10n'); ?> <?php
							if ( is_array($excluded) && !empty($excluded) ) {
								foreach( $excluded as $excl ) { ?>
								  <span style="border: 1px solid #dfdfdf;padding:2px 4px;cursor:pointer;" onclick="document.forms.srel_excluded.srel_exclude.value = '<?php echo $excl; ?>'"><?php echo $excl; ?></span>
						<?php   }
							} else { ?>
								<?php _e('[none]', 'srel-l10n'); ?>
						<?php
							} ?>
					</p>

					<input type="text" name="srel_exclude" size="4" maxlength="4" tabindex="4" value="" />
					<input class="button" type="submit" name="srel_add_excluded" value="<?php _e('Add Excluded ID', 'srel-l10n'); ?>"
						onclick="if (form.srel_exclude.value == ''){alert('<?php echo js_escape(__("Please enter the Page/Post ID that you want to add to this list.", "srel-l10n")); ?>');return false;}" />
					<input class="button" type="submit" name="srel_rem_excluded" value="<?php _e('Remove Excluded ID', 'srel-l10n'); ?>"
					onclick="if (form.srel_exclude.value == ''){alert('<?php echo js_escape(__("Please enter the Page/Post ID that you want to remove from this list.", "srel-l10n")); ?>');return false;}" />
					<span class="description"><?php _e('Please enter the ID for the post/page you want to exclude. You can see it in your browser\'s status bar(at the bottom of the window) when hovering over the name at the <a href="edit.php?post_type=page">Edit Pages</a> or the <a href="edit.php">Edit Posts</a> page.', 'srel-l10n'); ?></span>
					<?php wp_nonce_field( 'srel-save-options' ); ?>
				</td>
			</tr>
		</tbody>
		</table>
	</form>


	<?php
		}
	if ( $opt == 'srel_class' ) { ?>
	<form method="post" name="srel_excluded" id="srel_excluded" action="">
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Shutter is activated for all links pointing to an image that have class = &quot;shutter&quot;, &quot;shutterset&quot; or &quot;shutterset_setname&quot;', 'srel-l10n'); ?></th>
				<td>
					<p><?php _e('Class = &quot;shutter&quot; will display a single image, class = &quot;shutterset&quot; will create a single set for all images and class=&quot;shutterset_setname&quot;, where setname is a short ASCII word and/or number, will create multiple sets on the same page.', 'srel-l10n'); ?></p>
				</td>
			</tr>
		</tbody>
		</table>
	</form>
	<?php
		}
	if ( $opt == 'srel_pages' ) { ?>
	<form method="post" name="srel_included" id="srel_included" action="">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Shutter is activated for the following Posts and Pages (by ID):', 'srel-l10n'); ?></th>
				<td>
					<?php
						if ( is_array($included) && !empty($included) ) {
							foreach( $included as $incl ) { ?>
								<span style="border: 1px solid #dfdfdf;padding:2px 4px;cursor:pointer;" onclick="document.forms.srel_included.srel_include.value = '<?php echo $incl; ?>'"><?php echo $incl; ?></span>
					<?php   }
						} else { ?>
							<?php _e('[none]', 'srel-l10n'); ?>
					<?php
						} ?>
						</strong></p>
						<input type="text" name="srel_include" size="4" maxlength="4" tabindex="4" value="" />
						<input class="button" type="submit" name="srel_add_included" value="<?php _e('Add ID', 'srel-l10n'); ?>"
							onclick="if (form.srel_include.value == ''){alert('<?php echo js_escape(__("Please enter the Page/Post ID that you want to add to this list.", "srel-l10n")); ?>');return false;}" />
						<input class="button" type="submit" name="srel_rem_included" value="<?php _e('Remove ID', 'srel-l10n'); ?>"
						onclick="if (form.srel_include.value == ''){alert('<?php echo js_escape(__("Please enter the Page/Post ID that you want to remove from this list.", "srel-l10n")); ?>');return false;}" />
						<span class="description"><?php _e('Please enter the ID for the post/page you want to exclude. You can see it in your browser\'s status bar(at the bottom of the window) when hovering over the name at the <a href="edit-pages.php">Manage Pages</a> or the <a href="edit.php">Manage Posts</a> page.', 'srel-l10n'); ?></span>
						<?php wp_nonce_field( 'srel-save-options' ); ?>
				</td>
			</tr>
		</tbody>
		</table>
	</form>
	<?php
	}

	if ( $opt == 'srel_lb' ) { ?>
	<form method="post" name="srel_excluded" id="srel_excluded" action="">
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e('Shutter uses Lightbox style activation.', 'srel-l10n'); ?></th>
				<td>
					<p><?php _e('Shutter is activated for all links pointing to an image, that have rel=&quot;lightbox&quot; or rel=&quot;lightbox[...]&quot;. To make sets of images, you will have to add rel=&quot;lightbox[abc]&quot;, where &quot;abc&quot; can be any short ASCII word and/or number.', 'srel-l10n'); ?></p>
				</td>
			</tr>
		</tbody>
		</table>
	</form>
	<?php
		}
	?>

	<h3><?php _e('Customization', 'srel-l10n'); ?></h3>
	<form method="post" name="srel_saveoptform" id="srel_saveoptform" action="">
		<table class="form-table">
			<tbody>
				<tr>
					<th>Facebook</th>
					<td>
						<input type="checkbox" class="checkbox"  name="showfblike" id="showfblike" <?php if ($srel_options['showfblike'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="showfblike"><?php _e('Show Facebook Like Buttons?', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th>Load on</th>
					<td>
						<input type="checkbox" class="checkbox"  name="onlyonsingle" id="onlyonsingle" <?php if ($srel_options['onlyonsingle'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="onlyonsingle"><?php _e('Only load on single pages and posts?', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th>Nextgen Gallery</th>
					<td>
						<input type="checkbox" class="checkbox"  name="overwritenextgen" id="overwritenextgen" <?php if ($srel_options['overwritenextgen'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="overwritenextgen"><?php _e('Overwrite Nextgen Gallery Lightbox?', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th>Sets</th>
					<td>
						<input type="checkbox" class="checkbox"  name="oneset" id="oneset" <?php if ($srel_options['oneset'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="oneset"><?php _e('Make one set for all image links on the page that are not part of another set.', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th><label for="shcolor"><?php _e('Shutter color:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="shcolor" id="shcolor" size="7" maxlength="7" tabindex="" value="<?php if (strpos($srel_options['shcolor'],'#') === false) {?>#<?php }?><?php echo $srel_options['shcolor']; ?>" />
						<span class="description" style="vertical-align: top;"><?php _e('Please enter valid HTML color codes, from #000000 to #FFFFFF.', 'srel-l10n'); ?> (default #000000)</span>
					</td>
				</tr>
				<tr>
					<th><label for="opacity"><?php _e('Shutter opacity:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="opacity" id="opacity" size="7" maxlength="3" tabindex="" value="<?php echo $srel_options['opacity']; ?>" />
						<input type="text" name="opacity2" size="7" disabled style="padding:4px;border:1px solid #888;background-color:<?php echo $srel_options['shcolor']; ?>;opacity:<?php echo ($srel_options['opacity']/100); ?>;filter:alpha(opacity=<?php echo $srel_options['opacity']; ?>);" />
						<span class="description" style="vertical-align: top;"><?php _e('Enter a number between 1 (see-through) and 99 (solid color).', 'srel-l10n'); ?> (default 70)</span>
					</td>
				</tr>
				<tr>
					<th><label for="capcolor"><?php _e('Caption text color:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="capcolor" id="capcolor" size="7" maxlength="7" tabindex="" value="<?php if (strpos($srel_options['capcolor'],'#') === false) {?>#<?php }?><?php echo $srel_options['capcolor']; ?>" />
						<span class="description" style="vertical-align: top;">(default #FFFFFF)</span>
					</td>
				</tr>
				<tr>
					<th><label for="menucolor"><?php _e('Menubar color:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="menucolor" id="menucolor" size="7" maxlength="7" tabindex="" value="<?php if (strpos($srel_options['menucolor'],'#') === false) {?>#<?php }?><?php echo $srel_options['menucolor']; ?>" />
						<span class="description" style="vertical-align: top;">(default #000000)</span>
					</td>
				</tr>
				<tr>
					<th>Images count</th>
					<td>
						<input type="checkbox" class="checkbox" name="imageCount" id="imageCount" <?php if ($srel_options['imageCount'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="imageCount"><?php _e('Show images count for sets (Image 1 of ...):', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th><label for="countcolor"><?php _e('Images count color:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="countcolor" id="countcolor" size="7" maxlength="7" tabindex="" value="<?php if (strpos($srel_options['countcolor'],'#') === false) {?>#<?php }?><?php echo $srel_options['countcolor']; ?>" />
						<span class="description" style="vertical-align: top;">(default #999999)</span>
					</td>
				</tr>
				<tr>
					<th>Text buttons</th>
					<td>
						<input type="checkbox" class="checkbox"  name="textBtns" id="textBtns" <?php if ($srel_options['textBtns'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="textBtns"><?php _e('Text buttons (instead of images):', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th><label for="btncolor"><?php _e('Text buttons color:', 'srel-l10n'); ?></label></th>
					<td>
						<input type="text" name="btncolor" id="btncolor" size="7" maxlength="7" value="<?php if (strpos($srel_options['btncolor'],'#') === false) {?>#<?php }?><?php echo $srel_options['btncolor']; ?>" />
						<span class="description" style="vertical-align: top;">(default #CCCCCC)</span>
					</td>
				</tr>
				<tr>
					<th>Size</th>
					<td>
						<input type="checkbox" class="checkbox"  name="startFull" id="startFull" <?php if ($srel_options['startFull'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="startFull"><?php _e('Open the images in full size:', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th>Size</th>
					<td>
						<input type="checkbox" class="checkbox"  name="headload" id="headload" <?php if ($srel_options['headload'] == 1) { echo ' checked="checked"'; } ?> />
						<label for="headload"><?php _e('Alternate loading (select if Shutter does not start):', 'srel-l10n'); ?></label>
					</td>
				</tr>
				<tr>
					<th colspan="2"><?php _e('To restore the defaults, delete the current value(s) and submit the form.', 'srel-l10n'); ?></th>
				</tr>
			</tbody>
		</table>

	<p class="submit">
	<input type="submit" name="srel_delete_options" class="button" value="<?php _e('Reset all options', 'srel-l10n'); ?>" />
	<input class="button-primary" type="submit" name="srel_saveopt" value="<?php _e('Save Options', 'srel-l10n'); ?>" />
	</p>
	<?php wp_nonce_field( 'srel-save-options' ); ?>
	</form>

	<style>
		.wp-picker-holder{
			position: absolute;
			z-index: 9999;
		}
	</style>
	<script>
		$=jQuery();
		(function($) {
			$(document).ready(function() {
				var shcolor = $("#shcolor");
				shcolor.wpColorPicker({
					change: function(event, ui) {
						$(this).val(shcolor.wpColorPicker("color"));
					},
					clear: function() {
						pickColor("");
					}
				});
				var capcolor = $("#capcolor");
				capcolor.wpColorPicker({
					change: function(event, ui) {
						$(this).val(capcolor.wpColorPicker("color"));
					},
					clear: function() {
						pickColor("");
					}
				});
				var menucolor = $("#menucolor");
				menucolor.wpColorPicker({
					change: function(event, ui) {
						$(this).val(menucolor.wpColorPicker("color"));
					},
					clear: function() {
						pickColor("");
					}
				});
				var countcolor = $("#countcolor");
				countcolor.wpColorPicker({
					change: function(event, ui) {
						$(this).val(countcolor.wpColorPicker("color"));
					},
					clear: function() {
						pickColor("");
					}
				});
				var btncolor = $("#btncolor");
				btncolor.wpColorPicker({
					change: function(event, ui) {
						$(this).val(btncolor.wpColorPicker("color"));
					},
					clear: function() {
						pickColor("");
					}
				});
			});

		})(jQuery);
	</script>
	</div><?php //wrap ?>