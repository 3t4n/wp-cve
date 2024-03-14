<?php
//Load bootstrap file
require_once( dirname( dirname(__FILE__) ) .'/sh-tinymce-button-bootstrap.php');

global $wpdb;

//Check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to access this file.", "shtb_adv_lang"));

$shtb_box_url = plugin_dir_url( __FILE__ );
$shtb_adv_setting_opt = get_option('shtb_adv_setting_opt');
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SHTB CodeBox</title>
<!-- 	<meta http-equiv="Content-Type" content="<?php// bloginfo('html_type'); ?>; charset=<?php //echo get_option('blog_charset'); ?>" /> -->
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $shtb_box_url; ?>tinymce.js?ver=0.7.8.1"></script>
<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('shtb_adv_codebox_code').focus();" style="display: none">

<?php
$syntaxhighlighter_settings = get_option('syntaxhighlighter_settings');
if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'syntaxhighlighter_evolved' && function_exists('SyntaxHighlighter') && $syntaxhighlighter_settings['loadallbrushes'] == 0) {
	_e("<div style=\"margin-bottom:5px\">WARNING!: \"Load All Brushes\" option must be enabled on the \"SyntaxHighlighter\" setting panel.</div>", "shtb_adv_lang");
}
?>

<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="shtb_adv_codebox" action="#">
		<table border="0" cellpadding="4" cellspacing="0">
			<tr>
				<td nowrap="nowrap"><label for="shtb_adv_codebox_language"><?php _e("Select Language", "shtb_adv_lang"); ?></label></td>
				<td>
					<select id="shtb_adv_codebox_language" name="shtb_adv_codebox_language" style="width: 200px">
					<?php 
					if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'wp_syntaxhighlighter' && function_exists('wp_sh_register_menu_item') && is_array(get_option('wp_sh_language3')) && is_array(get_option('wp_sh_language2'))) {
						$wp_sh_setting_opt = get_option('wp_sh_setting_opt');
						if ($wp_sh_setting_opt) {
							$wp_sh_version = $wp_sh_setting_opt['lib_version'];
						} else {
							$wp_sh_version = get_option('wp_sh_version');
						}
						if ($wp_sh_version == '3.0') {
							$shtb_adv_language = get_option('wp_sh_language3');
						} elseif ($wp_sh_version == '2.1') {
							$shtb_adv_language = get_option('wp_sh_language2');
						}
						if (is_array($shtb_adv_language)) {
							asort($shtb_adv_language);
							echo "\n";
							foreach ($shtb_adv_language as $key => $val) {
								if ($val[1] == 'true' || $val[1] =='added') {
									echo '						<option value="'.$key.'">'.$val[0]."</option>\n";
								}
							}
							echo "\n";
						}
					} else {
						$shtb_adv_language = get_option('shtb_adv_languages');
						if (is_array($shtb_adv_language)) {
							asort($shtb_adv_language);
							echo "\n";
							foreach ($shtb_adv_language as $key => $val) {
								if ($val[1] == 'true' || $val[1] =='added') {
									echo '						<option value="'.$key.'">'.$val[0]."</option>\n";
								}
							}
							echo "\n";
						}
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top"><label for="shtb_adv_codebox_linenumbers"><?php _e("Show Line Number", "shtb_adv_lang"); ?></label></td>
<?php
if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'wp_syntaxhighlighter' && function_exists('wp_sh_register_menu_item')) {
	$wp_sh_setting_opt = get_option('wp_sh_setting_opt');
	if (is_array($wp_sh_setting_opt)) {
		if ($wp_sh_setting_opt['gutter'] == "false") {
			$shtb_adv_codebox_linenumbers_check = ' ';
		} else {
			$shtb_adv_codebox_linenumbers_check = 'checked="checked" ';
		}
	} elseif (get_option('wp_sh_gutter') == 0) {
		$shtb_adv_codebox_linenumbers_check = ' ';
	} else {
		$shtb_adv_codebox_linenumbers_check = 'checked="checked" ';
	}
} elseif ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'syntax_highlighter_compress' && function_exists('shc_install')) {
	$shc_opt = get_option('shc_opt');
	if ($shc_opt[shc_gutter] == 0) {
		$shtb_adv_codebox_linenumbers_check = ' ';
	} else {
		$shtb_adv_codebox_linenumbers_check = 'checked="checked" ';
	}
} elseif ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'syntaxhighlighter_evolved' && function_exists('SyntaxHighlighter')) {
	$syntaxhighlighter_settings = get_option('syntaxhighlighter_settings');
	if ($syntaxhighlighter_settings['gutter'] == 0) {
		$shtb_adv_codebox_linenumbers_check = ' ';
	} else {
		$shtb_adv_codebox_linenumbers_check = 'checked="checked" ';
	}
} else {
	if ($shtb_adv_setting_opt['gutter'] == "0") {
		$shtb_adv_codebox_linenumbers_check = ' ';
	} else {
		$shtb_adv_codebox_linenumbers_check = 'checked="checked" ';
	}
}
?>
				<td><label><input name="shtb_adv_codebox_linenumbers" id='shtb_adv_codebox_linenumbers' type="checkbox" <?php echo $shtb_adv_codebox_linenumbers_check; ?>/></label></td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top"><label for="shtb_adv_codebox_starting_linenumber"><?php _e("Starting Line Number", "shtb_adv_lang"); ?></label></td>
<?php
if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'wp_syntaxhighlighter' && function_exists('wp_sh_register_menu_item')) {
	$wp_sh_setting_opt = get_option('wp_sh_setting_opt');
	if (is_array($wp_sh_setting_opt)) {
		if ($wp_sh_setting_opt['gutter'] == "ture" || get_option('wp_sh_gutter') == 1) {
			$shtb_adv_codebox_starting_linenumber_value = $wp_sh_setting_opt['first_line'];
		} else {
			$shtb_adv_codebox_starting_linenumber_value = $shtb_adv_setting_opt['first_line'];
		}
	} else {
		$shtb_adv_codebox_starting_linenumber_value = $shtb_adv_setting_opt['first_line'];
		if (!preg_match("/^[0-9]+$/", $shtb_adv_codebox_starting_linenumber_value)) {
			$shtb_adv_codebox_starting_linenumber_value  = "1";
		}
	}
} elseif ($shtb_adv_setting_opt['using_syntaxhighlighter'] == 'syntaxhighlighter_evolved' && function_exists('SyntaxHighlighter')) {
	$syntaxhighlighter_settings = get_option('syntaxhighlighter_settings');
	if ($syntaxhighlighter_settings['gutter'] == 1) {
		$shtb_adv_codebox_starting_linenumber_value = $syntaxhighlighter_settings['firstline'];
	} else {
		$shtb_adv_codebox_starting_linenumber_value = $shtb_adv_setting_opt['first_line'];
	}
} else {
	$shtb_adv_codebox_starting_linenumber_value = $shtb_adv_setting_opt['first_line'];
}
?>
				<td><label><input name="shtb_adv_codebox_starting_linenumber" id='shtb_adv_codebox_starting_linenumber' type="text" value="<?php echo $shtb_adv_codebox_starting_linenumber_value; ?>" /></label></td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top"><label for="shtb_adv_codebox_highlighted_lines"><?php _e("Highlighted Lines", "shtb_adv_lang"); ?></label></td>
				<td><label><input name="shtb_adv_codebox_highlighted_lines" id='shtb_adv_codebox_highlighted_lines' type="text" /></label><br /><?php _e("Enter comma-separated linenumbers", "shtb_adv_lang"); ?></td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top"><label for="shtb_adv_codebox_html_script"><?php _e("html-script", "shtb_adv_lang"); ?></label></td>
<?php
if ($shtb_adv_setting_opt['html_script'] == "0") {
	$shtb_adv_codebox_html_script_check = ' ';
} else {
	$shtb_adv_codebox_html_script_check = 'checked="checked" ';
}
?>
				<td><label><input name="shtb_adv_codebox_html_script" id='shtb_adv_codebox_html_script' type="checkbox" <?php echo $shtb_adv_codebox_html_script_check; ?>/></label></td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top" colspan="2">
				<label for="shtb_adv_codebox_code"><?php _e("Your Code:", "shtb_adv_lang"); ?></label><br />
				<textarea id="shtb_adv_codebox_code" name="shtb_adv_codebox_code" style="width: 340px; height: 225px" /></textarea>
				</td>
			</tr>
		</table>
		<div class="mceActionPanel">
			<div style="float: left">
				<input type="submit" id="insert" name="insert" value="<?php _e("Insert", "shtb_adv_lang"); ?>" onclick="insertSHTBADVCODEBOXcode();" />
			</div>
			<div style="float: right">
				<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", "shtb_adv_lang"); ?>" onclick="tinyMCEPopup.close();" />
			</div>
		</div>
	</form>
</body>
</html>