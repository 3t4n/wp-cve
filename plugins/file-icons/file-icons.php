<?php
/*
Plugin Name: File Icons
Plugin URI: http://wordpress.designpraxis.at
Description: Configure and displays icons before links on your WordPress website by css (on firefox, opera, etc.)
Version: 2.1
Author: Roland Rust
Author URI: http://wordpress.designpraxis.at
*/


add_action('init', 'dprx_fileicons_init_locale',98);
function dprx_fileicons_init_locale() {
	$locale = get_locale();
	$mofile = dirname(__FILE__) . "/locale/".$locale.".mo";
	load_textdomain('dprx_fileicons', $mofile);
}

function dprx_fileicons_cache($iconset) {
	$dir = ABSPATH."/wp-content/plugins/file-icons/icons/".$iconset."/";
	if (is_dir($dir)) {
	$iconcache[$iconset]=array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$iconcache[$iconset][] = $file;
				}
			}
			closedir($handle);
		}
	}
	update_option("dprx_fileicon_set_cache",$iconcache);
}

// Plugin activation and deactivation e.g.: set 'manage bkpwp' capabilities to admin
add_action('activate_file-icons/file-icons.php', 'dprx_fileicons_activate');
function dprx_fileicons_activate() {update_option("dprx_fileicon_set","silkicons");
	update_option("dprx_fileicon_set","silkicons");
	$iconset = get_option("dprx_fileicon_set");
	dprx_fileicons_cache($iconset);
	
	$icons = array();
	/* file extensions */
	$icons[] = array("pattern" => "a[href\$=\".pdf\"]","icon" => "page_white_acrobat.png");
	$icons[] = array("pattern" => "a[href\$=\".txt\"]","icon" => "page_white_text.png");
	
	$icons[] = array("pattern" => "a[href\$=\".mp3\"]","icon" => "music.png");
	$icons[] = array("pattern" => "a[href\$=\".aiff\"]","icon" => "music.png");
	$icons[] = array("pattern" => "a[href\$=\".wav\"]","icon" => "music.png");
	$icons[] = array("pattern" => "a[href\$=\".ogg\"]","icon" => "music.png");
	$icons[] = array("pattern" => "a[href\$=\".wma\"]","icon" => "music.png");
	
	$icons[] = array("pattern" => "a[href\$=\".zip\"]","icon" => "page_white_compressed.png");
	$icons[] = array("pattern" => "a[href\$=\".rar\"]","icon" => "page_white_compressed.png");
	$icons[] = array("pattern" => "a[href\$=\".ace\"]","icon" => "page_white_compressed.png");
	$icons[] = array("pattern" => "a[href\$=\".tar.gz\"]","icon" => "page_white_compressed.png");
	$icons[] = array("pattern" => "a[href\$=\".tgz\"]","icon" => "page_white_compressed.png");
	
	$icons[] = array("pattern" => "a[href\$=\".mpg\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".mpeg\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".avi\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".mv2\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".mov\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".mp4\"]","icon" => "film.png");
	$icons[] = array("pattern" => "a[href\$=\".wmv\"]","icon" => "film.png");
	
	$icons[] = array("pattern" => "a[href\$=\".swf\"]","icon" => "page_white_flash.png");
	
	$icons[] = array("pattern" => "a[href\$=\".xls\"]","icon" => "page_white_excel.png");
	
	$icons[] = array("pattern" => "a[href\$=\".doc\"]","icon" => "page_white_word.png");
	
	/* URI parts */
	
	$icons[] = array("pattern" => "a[href*=\"trackback\"]","icon" => "link.png");
	$icons[] = array("pattern" => "a[href*=\"@\"]","icon" => "email.png");
	
	$icons[] = array("pattern" => "a[href*=\"rss2\"]","icon" => "rss.png");
	$icons[] = array("pattern" => "a[href*=\"feed\"]","icon" => "rss.png");
	
	$icons[] = array("pattern" => "a[href*=\"postcomment\"]","icon" => "comment.png"); 
	$icons[] = array("pattern" => "a[href*=\"respond\"]","icon" => "comment.png");
	
	/* admin links */
	$icons[] = array("pattern" => "a[href*=\"action=edit\"]","icon" => "page_white_edit.png");
	update_option("dprx_fileicons",$icons);
}

add_action('deactivate_file-icons/file-icons.php', 'dprx_fileicons_deactivate');
function dprx_fileicons_deactivate() {
	delete_option("dprx_fileicons");
	delete_option("dprx_fileicon_set");
	delete_option("dprx_fileicon_set_cache");
}

function dprx_fileicons_generate_css($icons,$iconset,$styles) {
	$stylepatterns = array();
	foreach ($styles as $style) {
		foreach($icons as $icon) {
			$stylepatterns[] = $style." ".$icon['pattern'];
		}
	}
	
	$paddings = implode(",",$stylepatterns);
	echo $paddings ."\n{
		padding-bottom:2px;
		padding-left:22px;
		padding-top:2px;
		background:transparent;
	}\n";
	
	$groups = array();
	foreach($icons as $icon) {
		$ic = $icon['icon'];
		$groups[$ic][] = $icon;
	}
	
	foreach($groups as $keyicon => $g) {
		$stylepatterns = array();
		foreach ($styles as $style) {
			foreach($g as $icon) {
				$stylepatterns[] = $style." ".$icon['pattern'];
			}
		}
		$stylesn = implode(",",$stylepatterns);
		echo $stylesn."
		{
			background: url(".get_bloginfo('wpurl')."/wp-content/plugins/file-icons/icons/".$iconset."/".$keyicon.") no-repeat scroll left center;
		}\n";
	}
}

add_action('wp_head', 'dprx_fileicons_add_style');

function dprx_fileicons_add_style() {
	$icons = get_option("dprx_fileicons");
	if (!is_array($icons)) { return; }
	$iconset = get_option("dprx_fileicon_set");
	$css_classes_ids = get_option("dprx_fileicons_css_classes_ids");
	$css_classes_ids = explode(",",$css_classes_ids);
	echo "<style type=\"text/css\">\n\n";
	dprx_fileicons_generate_css($icons,$iconset,$css_classes_ids);
	echo "</style>";
}

add_action('admin_menu', 'dprx_fileicons_add_admin_pages');
function dprx_fileicons_add_admin_pages() {
	add_submenu_page('themes.php', 'File-Icons', 'File-Icons', 10, __FILE__, 'dprx_fileicons_manage_page');
}

function dprx_fileicons_save_pattern($pattern,$file) {
	$icons = get_option("dprx_fileicons",$icons);
	$iconset = get_option("dprx_fileicon_set");
	$updated_iconset=array();
	foreach($icons as $icon) {
		//echo htmlentities(stripslashes($icon['pattern']))." : ".htmlentities(stripslashes($pattern))."<br />";
		if (stripslashes($icon['pattern']) == stripslashes($pattern)) {
			$updated_iconset[] = array("pattern" => stripslashes($pattern),"icon" => $file); $updated = 1;
		} else {
			$updated_iconset[] = $icon; 
		}
	}
	if (empty($updated)) {
			$updated_iconset[] = array("pattern" => stripslashes($pattern),"icon" => $file);
	}
	update_option("dprx_fileicons",$updated_iconset);
}

function dprx_fileicons_delete_pattern($pattern) {
	$icons = get_option("dprx_fileicons",$icons);
	$updated_iconset=array();
	foreach($icons as $icon) {
		if ($icon['pattern'] != $pattern) {
			$updated_iconset[] = $icon; 
		}
	}
	update_option("dprx_fileicons",$updated_iconset);
}

function dprx_fileicons_manage_page() {
	if (!empty($_POST['dprx_fileicons_css_classes_ids'])) {
		update_option('dprx_fileicons_css_classes_ids',$_POST['dprx_fileicons_css_classes_ids']);
	}
	if (!empty($_POST['dprx_fileicons_new_pattern']) && !empty($_POST['dprx_fileicons_new_file'])) {
		dprx_fileicons_save_pattern($_POST['dprx_fileicons_new_pattern'],$_POST['dprx_fileicons_new_file']);
	}
	if (!empty($_REQUEST['dprx_fileicons_delete_pattern'])) {
		dprx_fileicons_delete_pattern($_REQUEST['dprx_fileicons_delete_pattern']);
	}
	$icons = get_option("dprx_fileicons",$icons);
	$iconset = get_option("dprx_fileicon_set");
	$css_classes_ids = get_option("dprx_fileicons_css_classes_ids");
	?>
	<div class=wrap>
	<h2><?php _e("File-Icons Settings","dprx_fileicons"); ?></h2>
	  <form method="post" action="<?php bloginfo("wpurl"); ?>/wp-admin/admin.php?page=<?php echo $_REQUEST['page']; ?>">
	  <p><?php _e("Specify css classes and IDs, to which file icons should be applied. Seperate with kommas. The more classes or ids you specify, the larger the resulting css will be. stick to something like '#content'. Leave empty, to apply site-wide.","dprx_fileicons"); ?></p>
	  <input size="30" name="dprx_fileicons_css_classes_ids" type="test" value="<?php echo $css_classes_ids; ?>" />
	  <input type="submit" class="button" value="<?php _e("Save","dprx_fileicons"); ?>" />
		<table class="widefat">
		<thead>
		<tr>
		<th scope="col"><?php _e("Pattern","dprx_fileicons"); ?></th>
		<th scope="col"><?php _e("Icon","dprx_fileicons"); ?></th>
		<th scope="col"><?php _e("Options","dprx_fileicons"); ?></th>
		</tr>
		</thead>
		
		<tr>
			<td><input type="text" name="dprx_fileicons_new_pattern" id="dprx_fileicons_new"
				value="<?php
				if (!empty($_REQUEST['dprx_fileicons_edit_pattern'])) { echo htmlspecialchars(stripslashes($_REQUEST['dprx_fileicons_edit_pattern'])); }
				?>" /></td>
			<td>
			<?php
			if (file_exists(ABSPATH."/wp-content/plugins/file-icons/icons/".$iconset."/".$_REQUEST['dprx_fileicons_edit_file'])) {
				?>
				<img id="dprx_current_icon" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/file-icons/icons/<?php echo $iconset; ?>/<?php echo $_REQUEST['dprx_fileicons_edit_file']; ?>" />
				<?php		
			}
			?><select name="dprx_fileicons_new_file" id="dprx_fileicons_new_file" onchange="javascript:document.getElementById('dprx_current_icon').src='<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/file-icons/icons/<?php echo $iconset; ?>/' + document.getElementById('dprx_fileicons_new_file').value">
				<?php
				$iconcache = get_option("dprx_fileicon_set_cache");
				$iconset_cached = $iconcache[$iconset];
				if (is_array($iconset_cached)) {
				foreach($iconset_cached as $file) {
						if (file_exists(ABSPATH."/wp-content/plugins/file-icons/icons/".$iconset."/".$file)) {
						?><option <?php
						if($_REQUEST['dprx_fileicons_edit_file'] == $file) {
						echo "selected ";
						}
						?>style="height: 18px; padding-left: 18px; background-repeat:no-repeat; background-image: url(<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/file-icons/icons/<?php echo $iconset; ?>/<?php echo $file; ?>)" value="<?php echo $file; ?>"><?php echo $file; ?></option><?php
						}
					}
				}
				?>
			</select></td>
			<td><input type="submit" class="button" value="<?php 
			if (!empty($_REQUEST['dprx_fileicons_edit_pattern'])) {
				_e("Save","dprx_fileicons");
			} else {
				_e("Add new","dprx_fileicons");
			} ?>" />
			</td>
		 </tr>
		 
		<?php
		$icons = array_reverse($icons);
		
		$i=0;
		foreach ($icons as $icon) {
		if ($i == 1) { $i = 0; } else { $i++; }
		?><tr <?php if ($i == 1) { echo "style=\"background-color:#eeeeee\""; } ?>>
			<td><?php echo stripslashes($icon['pattern']); ?></td>
			<td><img src="<?php bloginfo("wpurl"); ?>/wp-content/plugins/file-icons/icons/<?php echo $iconset; ?>/<?php echo $icon['icon']; ?>" /></td>
			<td><a href="<?php bloginfo("wpurl"); ?>/wp-admin/admin.php?page=<?php echo $_REQUEST['page']; ?>&amp;dprx_fileicons_edit_pattern=<?php echo urlencode(stripslashes($icon['pattern'])); ?>&amp;dprx_fileicons_edit_file=<?php echo urlencode($icon['icon']); ?>"><?php _e("edit","dprx_fileicons"); ?></a>
				<a href="<?php bloginfo("wpurl"); ?>/wp-admin/admin.php?page=<?php echo $_REQUEST['page']; ?>&amp;dprx_fileicons_delete_pattern=<?php echo urlencode(stripslashes($icon['pattern'])); ?>"><?php _e("delete","dprx_fileicons"); ?></a>
			</td>
		 </tr>
		<?php
		}
		?>
		</table>
		</form>
	</div>
	
	<div class="wrap">
	<p>
	<?php _e("Running into Troubles? Features to suggest?","dprx_fileicons"); ?>
	<a href="http://wordpress.designpraxis.at/">
	<?php _e("Drop me a line","dprx_fileicons"); ?> &raquo;
	</a>
	</p>
	<div style="display: block; height:30px;">
		<div style="float:left; font-size: 16px; padding:5px 5px 5px 0;">
		<?php _e("Do you like this Plugin?","dprx_fileicons"); ?>
		</div>
		<div style="float:left;">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="rol@rm-r.at">
		<input type="hidden" name="no_shipping" value="0">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="currency_code" value="EUR">
		<input type="hidden" name="tax" value="0">
		<input type="hidden" name="lc" value="AT">
		<input type="hidden" name="bn" value="PP-DonationsBF">
		<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Please donate via PayPal!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		</div>
	</div>
	</div>
	<?php
}

?>
