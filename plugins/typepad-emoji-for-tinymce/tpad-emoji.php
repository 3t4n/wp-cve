<?php
/*
Plugin Name:TypePad emoji for TinyMCE
Plugin URI: http://www.picmo.net/dl/
Description: This plug-in is done by will being able to use the pictograph of TypePad with TinyMCE.
Version:1.5
Author: Tsubasa
Author URI:  http://www.picmo.net/
Text Domain: tpademoji
Domain Path: /locale/
*/

#------------------------------------------------------------------------------
# Plugin init
#------------------------------------------------------------------------------
load_plugin_textdomain('tpademoji', false , 'typepad-emoji-for-tinymce/locale');
$tpademoji_plugin_url = plugin_dir_url( __FILE__ );
$tpademoji_ver = "1.5";
$tpademoji_setting_opt = get_option('tpademoji_setting_opt');

#------------------------------------------------------------------------------
# version check
#------------------------------------------------------------------------------
add_action('plugins_loaded', 'tpademoji_check_ver');

function tpademoji_check_ver(){
	global $tpademoji_ver;
	if ($tpademoji_ver != get_option('tpademoji_checkver_stamp')) {
		tpademoji_setting_init();
		add_action('admin_notices', 'tpademoji_admin_updated_notice');
	}
}

#------------------------------------------------------------------------------
# setting init
#------------------------------------------------------------------------------
function tpademoji_setting_init(){
	global $tpademoji_ver;

	$tpademoji_setting_opt['tpademoji_button_line'] = "1";

	update_option('tpademoji_setting_opt', $tpademoji_setting_opt);
	update_option('tpademoji_checkver_stamp', $tpademoji_ver);
}

#------------------------------------------------------------------------------
# table updated message
#------------------------------------------------------------------------------
function tpademoji_admin_updated_notice(){
    echo '<div id="message" class="updated"><p>'.__("TypePad emoji for TinyMCE has successfully created new DB table.(If you updated, Plugin settings were reset to default.)<br />Go to the setting panel and <strong>configure TypePad emoji for TinyMCE</strong> now.","tpademoji").'</p></div>';
}

#------------------------------------------------------------------------------
# plugin footer
#------------------------------------------------------------------------------
function tpademoji_admin_footer(){
	$tpademoji_plugin_data = get_plugin_data(__FILE__);
	printf('%1$s by %2$s<br />', $tpademoji_plugin_data['Title'].' '.$tpademoji_plugin_data['Version'], $tpademoji_plugin_data['Author']);
}

#------------------------------------------------------------------------------
# add links option menu
#------------------------------------------------------------------------------
add_action('admin_menu', 'tpademoji_register_menu_item');

function tpademoji_register_menu_item() {
	$tpademoji_adv_page_hook = add_options_page('TypePad emoji for TinyMCE Options', __("TypePad Emoji","tpademoji"), 'manage_options', 'TypePad-emoji-button-options', 'TypePad_emoji_options_panel');
	if ($tpademoji_adv_page_hook != null) {
		$tpademoji_adv_page_hook = '-'.$tpademoji_adv_page_hook;
	}
}

#------------------------------------------------------------------------------
# Setting panel
#------------------------------------------------------------------------------
function TypePad_emoji_options_panel(){
	global $tpademoji_plugin_url, $tpademoji_ver;
	if (!function_exists('current_user_can') || !current_user_can('manage_options')) {
		die(__('Security Error.'));
	}
	add_action('in_admin_footer', 'tpademoji_admin_footer');

	if (isset($_POST['TPADEMOJI_Setting_Submit']) && $_POST['TPADEMOJI_hidden_value'] == "true") {
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("Settings saved.","tpademoji")."</strong></p></div>";
	} elseif (isset($_POST['TPADEMOJI_ADV_Reset']) && $_POST['tpademoji_adv_reset']='true') {
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("All settings were reset. Please <a href=\"options-general.php?page=TypePad-emoji-button-options\">reload the page</a>.","tpademoji")."</strong></p></div>";
	}

	$tpademoji_setting_opt = get_option('tpademoji_setting_opt');

	if ((!is_array($tpademoji_setting_opt) || $tpademoji_ver != get_option('tpademoji_checkver_stamp')) && !isset($_POST['SHTB_ADV_Reset'])) {
		echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".__("Error: Missing Database Table. The plugin may fail to create the databese table when install or update. Please re-install.","tpademoji")."</strong></p></div>";
	}

	// Update setting options
	if (isset($_POST['TPADEMOJI_Setting_Submit']) && $_POST['TPADEMOJI_hidden_value'] == "true") {
		$tpademoji_setting_opt['tpademoji_button_line'] = $_POST['tpademoji_button_line'];

		update_option('tpademoji_setting_opt', $tpademoji_setting_opt);
	}

	// Reset all settings
	if (isset($_POST['TPADEMOJI_ADV_Reset']) && $_POST['tpademoji_adv_reset']='true') {
		include_once('uninstall.php');
		tpademoji_setting_init();
	}


	?> 
	<div class="wrap">
	<h2><?php _e("TypePad emoji for TinyMCE Options", "tpademoji")?></h2>
	<form method="post" action="<?php echo($_SERVER['REQUEST_URI']);?>">
	<input type="hidden" name="TPADEMOJI_hidden_value" value="true" />
	<h3><?php _e("1. Basic settings", 'tpademoji') ?></h3> 
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><strong><?php _e('Place the buttons in', 'tpademoji') ?></strong></th> 
				<td>
					<select name="tpademoji_button_line">
						<option value="1" <?php if ($tpademoji_setting_opt['tpademoji_button_line'] == "1") {echo 'selected="selected"';} ?>><?php _e("1st row", "tpademoji") ?></option>
						<option value="2" <?php if ($tpademoji_setting_opt['tpademoji_button_line'] == "2") {echo 'selected="selected"';} ?>><?php _e("2nd row", "tpademoji") ?></option>
						<option value="3" <?php if ($tpademoji_setting_opt['tpademoji_button_line'] == "3") {echo 'selected="selected"';} ?>><?php _e("3rd row", "tpademoji") ?></option>
						<option value="4" <?php if ($tpademoji_setting_opt['tpademoji_button_line'] == "4") {echo 'selected="selected"';} ?>><?php _e("4th row", "tpademoji") ?></option>
					</select> <?php _e("of TinyMCE toolbar.", "tpademoji") ?>
					<p><small><?php _e("Choose TinyMCE toolbar row which buttons will be placed in.", "tpademoji") ?></small></p>
				</td>
			</tr>
		</table>
		<p class="submit">
		<input type="submit" name="TPADEMOJI_Setting_Submit" value="<?php _e('Save Changes', 'tpademoji') ?>" />
		</p>
	</form>
	<h3><?php _e("2. Icons List", 'tpademoji') ?></h3>
	<p>
	<img src="<?php echo $tpademoji_plugin_url; ?>palette.gif">
	</p>
	<h3><?php _e("3. Your System Info", 'tpademoji') ?></h3>
	<p>
	<?php _e('Server OS:', 'shtb_adv_lang') ?> <?php echo php_uname('s').' '.php_uname('r'); ?><br />
	<?php _e('PHP version:', 'shtb_adv_lang') ?> <?php echo phpversion(); ?><br />
	<?php _e('MySQL version:', 'shtb_adv_lang') ?> <?php echo mysql_get_server_info(); ?><br />
	<?php _e('WordPress version:', 'shtb_adv_lang') ?> <?php bloginfo("version"); ?><br />
	<?php _e('Site URL:', 'shtb_adv_lang') ?> <?php bloginfo("url"); ?><br />
	<?php _e('WordPress URL:', 'shtb_adv_lang') ?> <?php bloginfo("wpurl"); ?><br />
	<?php _e('WordPress language:', 'shtb_adv_lang') ?> <?php bloginfo("language"); ?><br />
	<?php _e('WordPress character set:', 'shtb_adv_lang') ?> <?php bloginfo("charset"); ?><br />
	<?php _e('WordPress theme:', 'shtb_adv_lang') ?> <?php $tpademoji_theme = get_theme(get_current_theme()); echo $tpademoji_theme['Name'].' '.$tpademoji_theme['Version']; ?><br />
	<?php _e('TypePad emoji for TinyMCE version:', 'shtb_adv_lang') ?> <?php $tpademoji_plugin_data = get_plugin_data(__FILE__); echo $tpademoji_plugin_data['Version']; ?><br />
	<?php _e('TypePad emoji for TinyMCE URL:', 'shtb_adv_lang') ?> <?php echo $tpademoji_plugin_url; ?><br />
	<?php _e('Your browser:', 'shtb_adv_lang') ?> <?php echo $_SERVER['HTTP_USER_AGENT']; ?>
	</p>
	<h3><?php _e("4. contact us", 'tpademoji') ?></h3>
	<p>
	<?php _e("PiCMO -engineer side blog-", 'tpademoji') ?> [ <a href="http://www.picmo.net">http://www.picmo.net</a> ] 
	</p>
	<p>
	<?php _e("To report a bug ,submit requests and feedback, ", 'tpademoji') ?><?php _e('Use <a href="http://www.picmo.net/contact">Mail From</a>', 'tpademoji') ?>
	</p>
	</div>
<?php } 

#------------------------------------------------------------------------------
# Insert TypePad Icon for tinyMCE
#------------------------------------------------------------------------------
function tpademoji_addbuttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
	// add the button for wp25 in a new way
		add_filter("mce_external_plugins", 'add_tpademoji_tinymce_plugin');
		$tpademoji_setting_opt = get_option('tpademoji_setting_opt');
		$button_line = $tpademoji_setting_opt['tpademoji_button_line'];
		if ($button_line == "2" || $button_line == "3" || $button_line == "4") {
			add_filter('mce_buttons_'.$button_line, 'register_tpademoji_button');
		} else {
			add_filter('mce_buttons', 'register_tpademoji_button');
		}
	}
}

#------------------------------------------------------------------------------
# register TypePad button
#------------------------------------------------------------------------------
function register_tpademoji_button($buttons) {
	array_push($buttons, "separator", "tpademoji");
	return $buttons;
}

#------------------------------------------------------------------------------
# Load editor_plugin.js
#------------------------------------------------------------------------------
function add_tpademoji_tinymce_plugin($plugin_array) {
	global $tpademoji_plugin_url;
	$tpademoji_setting_opt = get_option('tpademoji_setting_opt');
	$plugin_array['tpademoji'] = $tpademoji_plugin_url.'editor_plugin.js';
	return $plugin_array;
}

#------------------------------------------------------------------------------
# change version
#------------------------------------------------------------------------------
function tpademoji_change_tinymce_version($version) {
	return ++$version;
}

#------------------------------------------------------------------------------
# WordPress filter and action
#------------------------------------------------------------------------------
add_filter('tiny_mce_version', 'tpademoji_change_tinymce_version');
add_action('init', 'tpademoji_addbuttons');
?>
