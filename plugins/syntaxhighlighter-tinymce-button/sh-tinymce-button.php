<?php
/*
Plugin Name: SyntaxHighlighter TinyMCE Button
Plugin URI: http://www.near-mint.com/blog/software/syntaxhighlighter-tinymce-button
Description: 'SyntaxHighlighter TinyMCE Button' provides additional buttons for Visual Editor and these buttons will help to type or edit <code>&lt;pre&gt;</code> tag for Alex Gorbatchev's <a href='http://alexgorbatchev.com/SyntaxHighlighter/'>SyntaxHighlighter</a>. This plugin is based on '<a href='http://wordpress.org/extend/plugins/codecolorer-tinymce-button/'>CodeColorer TinyMCE Button</a>'.
Version: 0.7.8.4
Author: redcocker
Author URI: http://www.near-mint.com/blog/
Text Domain: shtb_adv_lang
Domain Path: /languages
*/
/*
Last modified: 2011/12/24
License: GPL v2
*/
load_plugin_textdomain('shtb_adv_lang', false, dirname(plugin_basename(__FILE__)).'/languages');
$shtb_adv_plugin_url = plugin_dir_url(__FILE__);
$shtb_adv_db_ver = "0.7.8";
$shtb_adv_setting_opt = get_option('shtb_adv_setting_opt');

// Create language list array
function shtb_adv_language_array() {
	$shtb_adv_language = array(
		"applescript" => array('AppleScript', 'true'),
		"actionscript3" => array('Actionscript3', 'true'),
		"bash" => array('Bash shell', 'true'),
		"coldfusion" => array('ColdFusion', 'true'),
		"c" => array('C', 'true'),
		"cpp" => array('C++', 'true'),
		"csharp" => array('C#', 'true'),
		"css" => array('CSS', 'true'),
		"delphi" => array('Delphi', 'true'),
		"diff" => array('Diff', 'true'),
		"erlang" => array('Erlang', 'true'),
		"groovy" => array('Groovy', 'true'),
		"html" => array('HTML', 'true'),
		"java" => array('Java', 'true'),
		"javafx" => array('JavaFX', 'true'),
		"javascript" => array('JavaScript', 'true'),
		"pascal" => array('Pascal', 'true'),
		"patch" => array('Patch', 'true'),
		"perl" => array('Perl', 'true'),
		"php" => array('PHP', 'true'),
		"text" => array('Plain Text', 'true'),
		"powershell" => array('PowerShell', 'true'),
		"python" => array('Python', 'true'),
		"ruby" => array('Ruby', 'true'),
		"rails" => array('Ruby on Rails', 'true'),
		"sass" => array('Sass', 'true'),
		"scala" => array('Scala', 'true'),
		"scss" => array('Scss', 'true'),
		"shell" => array('Shell', 'true'),
		"sql" => array('SQL', 'true'),
		"vb" => array('Visual Basic', 'true'),
		"vbnet" => array('Visual Basic .NET', 'true'),
		"xhtml" => array('XHTML', 'true'),
		"xml" => array('XML', 'true'),
		"xslt" => array('XSLT', 'true'),
		);
	// Store in DB
	update_option('shtb_adv_languages', $shtb_adv_language);
}

// Create settings array
function shtb_adv_setting_array() {
	$shtb_adv_setting_opt = array(
		"using_syntaxhighlighter" => "other",
		"insert" => "1",
		"codebox" => "1",
		"button_window_size" => "100",
		"button_row" => "1",
		"gutter" => "1",
		"first_line" => "1",
		"html_script" => "0",
		);
	// Store in DB
	add_option('shtb_adv_setting_opt', $shtb_adv_setting_opt);
	add_option('shtb_adv_updated', 'false');
}

// Check DB table version and create table
add_action('plugins_loaded', 'shtb_adv_check_db_ver');

function shtb_adv_check_db_ver(){
	global $shtb_adv_db_ver;
	$current_checkver_stamp = get_option('shtb_adv_checkver_stamp');
	if (!$current_checkver_stamp || version_compare($current_checkver_stamp, $shtb_adv_db_ver, "!=")) {
		$updated_count = 0;
		// For new installation, update from ver. 0.6 or older
		if (!$current_checkver_stamp) {
			// Register array
			shtb_adv_language_array();
			$updated_count = $updated_count + 1;
		}
		// For new installation, update from ver. 0.7.7 or older
		if (!$current_checkver_stamp || ($current_checkver_stamp && version_compare($current_checkver_stamp, "0.7.5", "<="))) {
			// Register array
			shtb_adv_setting_array();
			// Data migration when updated from ver.0.6 or older
			include_once('data-migration.php');
			$updated_count = $updated_count + 1;
			// Delete options since ver.0.6 or older
			include_once('del-old-options.php');
		}
		update_option('shtb_adv_checkver_stamp', $shtb_adv_db_ver);
		// Stamp for showing messages
		if ($updated_count != 0) {
			update_option('shtb_adv_updated', 'true');
		}
	}
}

// Register the setting panel and hooks
add_action('admin_menu', 'shtb_adv_register_menu_item');

function shtb_adv_register_menu_item() {
	$shtb_adv_page_hook = add_options_page('SyntaxHighlighter TinyMCE Button Options', 'SH TinyMCE Button', 'manage_options', 'syntaxhighlighter-tinymce-button-options', 'shtb_adv_options_panel');
	if ($shtb_adv_page_hook != null) {
		$shtb_adv_page_hook = '-'.$shtb_adv_page_hook;
	}
	add_action('admin_print_scripts'.$shtb_adv_page_hook, 'shtb_adv_load_jscript_for_admin');
	if (get_option('shtb_adv_updated') == "true" && !(isset($_POST['SHTB_ADV_Setting_Submit']) && $_POST['shtb_adv_hidden_value'] == "true") && !(isset($_POST['SHTB_ADV_Reset']) && $_POST['shtb_adv_reset'] == "true")) {
		add_action('admin_notices', 'shtb_adv_admin_updated_notice');
	}
}

// Message for admin when DB table updated
function shtb_adv_admin_updated_notice(){
    echo '<div id="message" class="updated"><p>'.__("SyntaxHighlighter TinyMCE Button has successfully created new DB table.<br />If you upgraded to this version, some setting options may be added or reset to the default values.<br />Go to the <a href=\"options-general.php?page=syntaxhighlighter-tinymce-button-options\">setting panel</a> and configure SyntaxHighlighter TinyMCE Button now. Once you save your settings, this message will be cleared.", "shtb_adv_lang").'</p></div>';
}

// Show plugin info in the footer
function shtb_adv_add_admin_footer() {
	$shtb_adv_plugin_data = get_plugin_data(__FILE__);
	printf('%1$s by %2$s<br />', $shtb_adv_plugin_data['Title'].' '.$shtb_adv_plugin_data['Version'], $shtb_adv_plugin_data['Author']);
}

// Load stylesheet for fullscreen mode
if (version_compare(get_bloginfo('version'), "3.2", ">=")) {
	add_action('admin_print_styles-post.php', 'shtb_editor_css');
	add_action('admin_print_styles-post-new.php', 'shtb_editor_css');
	add_action('admin_print_styles-page.php', 'shtb_editor_css');
	add_action('admin_print_styles-page-new.php', 'shtb_editor_css');
}

function shtb_editor_css() {
	global $shtb_adv_plugin_url;
	wp_enqueue_style('shtb-editor', $shtb_adv_plugin_url.'shtb_fullscreen.css', false, '1.0');
}

// Load script in setting panel
function shtb_adv_load_jscript_for_admin(){
	global $shtb_adv_plugin_url;
	wp_enqueue_script('rc_admin_js', $shtb_adv_plugin_url.'rc-admin-js.js', false, '1.1');
}

// Register the setting panel
add_filter( 'plugin_action_links', 'shtb_adv_setting_link', 10, 2);

function shtb_adv_setting_link($links, $file){
	static $this_plugin;
	if (! $this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=syntaxhighlighter-tinymce-button-options">'.__("Settings", "shtb_adv_lang").'</a>';
		array_unshift($links, $settings_link);
	}  
	return $links;
}

// Load SyntaxHighlighter TinyMCE Buttons
if ($shtb_adv_setting_opt['insert'] == 1) {
	include_once('sh-tinymce-button-ins/sh-tinymce-button-ins.php');
}
if ($shtb_adv_setting_opt['codebox'] == 1) {
	include_once('sh-tinymce-button-box/sh-tinymce-button-box.php');
}

// Allow tabs to indent in TinyMCE.
if ($shtb_adv_setting_opt['insert'] == 1 || $shtb_adv_setting_opt['codebox'] == 1) {
	add_filter('tiny_mce_before_init', 'shtb_adv_insert_allow_tab');
}

function shtb_adv_insert_allow_tab($initArray) {
    $initArray['plugins']=preg_replace("|[,]+tabfocus|i","",$initArray['plugins']);
    return $initArray;
}

// Register 'pre' tag and 'class' attribte as TinyMCE valid_elements.
add_filter('tiny_mce_before_init', 'shtb_adv_mce_valid_elements');

function shtb_adv_mce_valid_elements($init) {
	if ( isset( $init['extended_valid_elements'] ) 
	&& ! empty( $init['extended_valid_elements'] ) ) {
		$init['extended_valid_elements'] .= ',' . 'pre[class]';
	} else {
		$init['extended_valid_elements'] = 'pre[class]';
	}
	return $init;
}

// Setting panel
function shtb_adv_options_panel(){
	global $shtb_adv_plugin_url, $shtb_adv_db_ver;
	if(!function_exists('current_user_can') || !current_user_can('manage_options')){
			die(__('Cheatin&#8217; uh?'));
	} 
	add_action('in_admin_footer', 'shtb_adv_add_admin_footer');

	$shtb_adv_setting_opt = get_option('shtb_adv_setting_opt');

	// Update setting options
	if (isset($_POST['SHTB_ADV_Setting_Submit']) && $_POST['shtb_adv_hidden_value'] == "true" && check_admin_referer("shtb_adv_update_options", "_wpnonce_update_options")) {
		$shtb_adv_setting_opt['using_syntaxhighlighter'] = $_POST['using_syntaxhighlighter'];

		if ($_POST['insert'] == "1") {
			$shtb_adv_setting_opt['insert'] = "1";
		} else {
			$shtb_adv_setting_opt['insert'] = "0";
		}
		if ($_POST['codebox'] == "1") {
			$shtb_adv_setting_opt['codebox'] = "1";
		} else {
			$shtb_adv_setting_opt['codebox'] = "0";
		}
		$shtb_adv_setting_opt['button_window_size'] = $_POST['button_window_size'];
		$shtb_adv_setting_opt['button_row'] = $_POST['button_row'];
		$shtb_adv_setting_opt['gutter'] = $_POST['gutter'];
		$shtb_adv_setting_opt['button_row'] = $_POST['button_row'];
		if ($_POST['gutter'] == "1") {
			$shtb_adv_setting_opt['gutter'] = "1";
		} else {
			$shtb_adv_setting_opt['gutter'] = "0";
		}
		$shtb_adv_setting_opt['first_line'] = $_POST['first_line'];
		if ($_POST['html_script'] == "1") {
			$shtb_adv_setting_opt['html_script'] = "1";
		} else {
			$shtb_adv_setting_opt['html_script'] = "0";
		}
		// Validate values
		if (!preg_match("/^[0-9]+$/", $shtb_adv_setting_opt['first_line'])) {
			wp_die(__("Invalid value. Settings could not be saved.<br />Your \"Starting Line Number\" must be entered in numbers.", "shtb_adv_lang"));
		}
		// Rebuild language list
		$shtb_adv_languages = get_option('shtb_adv_languages');
		foreach ($shtb_adv_languages as  $alias => $val) {
			$brush_lang = $val[0];
			$key = 'lang_'.$alias;
			$shtb_adv_new_languages[$alias]= array($brush_lang, $_POST[$key]);
		}
		// Store in DB
		update_option('shtb_adv_setting_opt', $shtb_adv_setting_opt);
		update_option('shtb_adv_languages', $shtb_adv_new_languages);
		update_option('shtb_adv_updated', 'false');
		// Show message for admin
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("Settings saved.","shtb_adv_lang")."</strong></p></div>";
	}
	// Reset all settings
	if (isset($_POST['SHTB_ADV_Reset']) && $_POST['shtb_adv_reset'] == "true" && check_admin_referer("shtb_adv_reset_options", "_wpnonce_reset_options")) {
		include_once('uninstall.php');
		shtb_adv_language_array();
		shtb_adv_setting_array();
		update_option('shtb_adv_checkver_stamp', $shtb_adv_db_ver);
		// Show message for admin
		echo "<div id='setting-error-settings_updated' class='updated fade'><p><strong>".__("All settings were reset. Please <a href=\"options-general.php?page=syntaxhighlighter-tinymce-button-options\">reload the page</a>.", "shtb_adv_lang")."</strong></p></div>";
	}

	?> 
	<div class="wrap">
	<?php screen_icon(); ?>
	<h2>SyntaxHighlighter TinyMCE Button</h2>
	<form method="post" action="">
	<?php wp_nonce_field("shtb_adv_update_options", "_wpnonce_update_options"); ?>
	<input type="hidden" name="shtb_adv_hidden_value" value="true" />
	<h3><?php _e("1. Basic settings", "shtb_adv_lang") ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><strong><?php _e("Using with", "shtb_adv_lang") ?></strong></th> 
				<td>
					<input type="radio" name="using_syntaxhighlighter" value="wp_syntaxhighlighter" <?php if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == "wp_syntaxhighlighter") {echo 'checked=\"checked\"';} ?>/><?php _e("<a href=\"http://wordpress.org/extend/plugins/wp-syntaxhighlighter/\" style=\"text-decoration: none\">WP SyntaxHighlighter</a>", "shtb_adv_lang") ?> <input type="radio" name="using_syntaxhighlighter" value="syntax_highlighter_compress" <?php if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == "syntax_highlighter_compress") {echo 'checked=\"checked\"';} ?>/><?php _e("<a href=\"http://wordpress.org/extend/plugins/syntax-highlighter-compress/\" style=\"text-decoration: none\">Syntax Highlighter Compress</a>", "shtb_adv_lang") ?> <input type="radio" name="using_syntaxhighlighter" value="syntaxhighlighter_evolved" <?php if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == "syntaxhighlighter_evolved") {echo 'checked=\"checked\"';} ?>/><?php _e("<a href=\"http://wordpress.org/extend/plugins/syntaxhighlighter/\" style=\"text-decoration: none\">SyntaxHighlighter Evolved</a>", "shtb_adv_lang") ?> <input type="radio" name="using_syntaxhighlighter" value="other" <?php if ($shtb_adv_setting_opt['using_syntaxhighlighter'] == "other") {echo 'checked=\"checked\"';} ?>/><?php _e("Other", "shtb_adv_lang") ?>
					<p><small><?php _e("Select your using plugin based on Alex Gorbatchev's <a href='http://alexgorbatchev.com/SyntaxHighlighter/' style='text-decoration: none'>SyntaxHighlighter</a>.<br />If your plugin is not in the options, Select 'Other'.<br />It is the same if you use <a href='http://wordpress.org/extend/plugins/auto-syntaxhighlighter/' style='text-decoration: none'>Auto SyntaxHighlighter</a>, <a href='http://wordpress.org/extend/plugins/syntax-highlighter-and-code-prettifier/' style='text-decoration: none'>Syntax Highlighter and Code Colorizer for WordPress</a> or <a href='http://wordpress.org/extend/plugins/syntax-highlighter-mt/' style='text-decoration: none'>Syntax Highlighter MT</a>.<br />If you don't know about your using plugin, Select 'Other'. When you select 'Other', this plugin will act innocuously.<br />When using with 'SyntaxHighlighter Evolved', 'Load All Brushes' option must be enabled on the 'SyntaxHighlighter' setting panel.<br />If you want to use full options of 'Dafault settings for your buttons', you should select 'Other'.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e("Buttons", "shtb_adv_lang") ?></strong></th>
				<td>
					<input type="checkbox" name="insert" value="1" <?php if ($shtb_adv_setting_opt['insert'] == '1') {echo 'checked=\"checked\"';} ?>/> Select &amp; Insert <input type="checkbox" name="codebox" value="1" <?php if ($shtb_adv_setting_opt['codebox'] == '1') {echo 'checked=\"checked\"';} ?>/> CodeBox
					<p><small><?php _e("Enable/Disable buttons.<br />'Select &amp; Insert' will help you to wrap your code on the post or page in <code>&lt;pre&gt;</code> tag or to update values of previously-markuped code.<br />'CodeBox' will allow you to paste your code into the post or page and wrap in <code>&lt;pre&gt;</code> tag automatically, keeping indent by tabs.<br />In 'Visual Editor', 'Select &amp; Insert' will appear as 'pre' icon and 'CodeBox' will appear as 'CODE' icon.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e("Window size", "shtb_adv_lang") ?></strong></th> 
				<td>
					<select name="button_window_size">
						<option value="100" <?php if ($shtb_adv_setting_opt['button_window_size'] == "100") {echo 'selected="selected"';} ?>>100%</option>
						<option value="105" <?php if ($shtb_adv_setting_opt['button_window_size'] == "105") {echo 'selected="selected"';} ?>>105%</option>
						<option value="110" <?php if ($shtb_adv_setting_opt['button_window_size'] == "110") {echo 'selected="selected"';} ?>>110%</option>
					</select>
					<p><small><?php _e("Choose size of pop-up window at the click of buttons.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e("Place the buttons in", "shtb_adv_lang") ?></strong></th> 
				<td>
					<select name="button_row">
						<option value="1" <?php if ($shtb_adv_setting_opt['button_row'] == "1") {echo 'selected="selected"';} ?>><?php _e("1st row", "shtb_adv_lang") ?></option>
						<option value="2" <?php if ($shtb_adv_setting_opt['button_row'] == "2") {echo 'selected="selected"';} ?>><?php _e("2nd row", "shtb_adv_lang") ?></option>
						<option value="3" <?php if ($shtb_adv_setting_opt['button_row'] == "3") {echo 'selected="selected"';} ?>><?php _e("3rd row", "shtb_adv_lang") ?></option>
						<option value="4" <?php if ($shtb_adv_setting_opt['button_row'] == "4") {echo 'selected="selected"';} ?>><?php _e("4th row", "shtb_adv_lang") ?></option>
					</select> <?php _e("of TinyMCE toolbar.", "shtb_adv_lang") ?>
					<p><small><?php _e("Choose TinyMCE toolbar row which buttons will be placed in.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
		</table>
		<h3><a href="javascript:showhide('id1');" name="button_settings"><?php _e("2. Dafault settings for your buttons", "shtb_adv_lang") ?></a></h3> 
		<div id="id1" style="display:none;">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><strong><?php _e("Show Line Number", "shtb_adv_lang") ?></strong></th>
				<td>
					<input type="checkbox" name="gutter" value="1" <?php if ($shtb_adv_setting_opt['gutter'] == '1') {echo 'checked=\"checked\"';} ?>/>
					<p><small><?php _e("Enable/Disable 'Show Line number' option by default.<br />Only when 'Other' is selected in 'Using with' option, this option can work.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e("Starting Line Number", "shtb_adv_lang") ?></strong></th>
				<td>
					<input type="text" name="first_line" size="2" value="<?php echo $shtb_adv_setting_opt['first_line']; ?>" />
					<p><small><?php _e("Enter default starting line number.<br />When 'WP SyntaxHighlighter' or 'SyntaxHighlighter Evolved' is selected in 'Using with' option, this option can't work.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e("html-script", "shtb_adv_lang") ?></strong></th>
				<td>
					<input type="checkbox" name="html_script" value="1" <?php if ($shtb_adv_setting_opt['html_script'] == '1') {echo 'checked=\"checked\"';} ?>/>
					<p><small><?php _e("Enable/Disable 'html-script' option by default.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
			<tr valign="top"> 
				<th scope="row"><strong><?php _e("Default languages", "shtb_adv_lang") ?></strong></th>
				<td>
					<p><small><?php _e("Choose languages listed in the drop-down list by default.<br />When 'WP SyntaxHighlighter' is selected in 'Using with' option, this option can't work.", "shtb_adv_lang") ?></small></p>
				</td>
			</tr>
		<?php 
		$shtb_adv_languages = get_option('shtb_adv_languages');
		if (is_array($shtb_adv_languages)) {
			foreach ($shtb_adv_languages as $alias => $val) {
				$brush_lang = $val[0];
				$brush_enable = $val[1];
			?>
			<tr valign="top">
				<th scope="row"><?php echo $alias; ?></strong></th>
				<td>
				<label for="<?php echo 'lang_'.$alias; ?>_Yes"> <input type="radio" id="<?php echo $lang; ?>_Yes" name="<?php echo 'lang_'.$alias; ?>" value="true" <?php if($brush_enable == 'true'){echo 'checked="checked"';}?> /><?php _e("Yes", "shtb_adv_lang") ?></label>
				<label for="<?php echo 'lang_'.$alias; ?>_No"><input type="radio" id="<?php echo $lang; ?>_No" name="<?php echo 'lang_'.$alias; ?>" value="false" <?php if($brush_enable == 'false'){echo 'checked="checked"';}?> /><?php _e("No", "shtb_adv_lang") ?></label>
				</td>
			</tr>
			<?php }
		 } ?>
		</table>
		</div>
		<p class="submit">
		<input type="submit" name="SHTB_ADV_Setting_Submit" value="<?php _e("Save Changes", "shtb_adv_lang") ?>" />
		</p>
	</form>
	<h3><?php _e("3. Restore all settings to default", "shtb_adv_lang") ?></h3>
	<form method="post" action="" onsubmit="return confirmreset()">
	<?php wp_nonce_field("shtb_adv_reset_options", "_wpnonce_reset_options"); ?>
		<p class="submit">
		<input type="hidden" name="shtb_adv_reset" value="true" />
		<input type="submit" name="SHTB_ADV_Reset" value="<?php _e("Reset All Settings", "shtb_adv_lang") ?>" />
		</p>
	</form>
	<h3><a href="javascript:showhide('id2');" name="system_info"><?php _e("4. Your System Info", "shtb_adv_lang") ?></a></h3>
	<div id="id2" style="display:none; margin-left:20px">
	<p>
	<?php _e("Server OS:", "shtb_adv_lang") ?> <?php echo php_uname('s').' '.php_uname('r'); ?><br />
	<?php _e("PHP version:", "shtb_adv_lang") ?> <?php echo phpversion(); ?><br />
	<?php _e("MySQL version:", "shtb_adv_lang") ?> <?php echo mysql_get_server_info(); ?><br />
	<?php _e("WordPress version:", "shtb_adv_lang") ?> <?php bloginfo("version"); ?><br />
	<?php _e("Site URL:", "shtb_adv_lang") ?> <?php if(function_exists("home_url")) { echo home_url(); } else { echo get_option('home'); } ?><br />
	<?php _e("WordPress URL:", "shtb_adv_lang") ?> <?php echo site_url(); ?><br />
	<?php _e("WordPress language:", "shtb_adv_lang") ?> <?php bloginfo("language"); ?><br />
	<?php _e("WordPress character set:", "shtb_adv_lang") ?> <?php bloginfo("charset"); ?><br />
	<?php _e("WordPress theme:", "shtb_adv_lang") ?> <?php $shtb_theme = get_theme(get_current_theme()); echo $shtb_theme['Name'].' '.$shtb_theme['Version']; ?><br />
	<?php _e("SyntaxHighlighter TinyMCE Button version:", "shtb_adv_lang") ?> <?php $shtb_plugin_data = get_plugin_data(__FILE__); echo $shtb_plugin_data['Version']; ?><br />
	<?php _e("SyntaxHighlighter TinyMCE Button DB version:", "shtb_adv_lang") ?> <?php echo get_option('shtb_adv_checkver_stamp'); ?><br />
	<?php _e("SyntaxHighlighter TinyMCE Button URL:", "shtb_adv_lang") ?> <?php echo $shtb_adv_plugin_url; ?><br />
	<?php _e("Your browser:", "shtb_adv_lang") ?> <?php echo esc_html($_SERVER['HTTP_USER_AGENT']); ?>
	</p>
	</div>
	<p>
	<?php _e("To report a bug ,submit requests and feedback, ", "shtb_adv_lang") ?><?php _e("Use <a href=\"http://wordpress.org/tags/syntaxhighlighter-tinymce-button?forum_id=10\">Forum</a> or <a href=\"http://www.near-mint.com/blog/contact\">Mail From</a>", "shtb_adv_lang") ?>
	</p>
	</div>
<?php } 
?>