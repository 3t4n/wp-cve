<?php
/*
Plugin Name: Yandex.Metrika
Description: Allow you to insert counter code for YandexMetrika to your blog.
Version: 1.1
License: GPL-2.0+
*/
add_action('init', 'yandexmetrika_init');
add_action('admin_menu', 'yandexmetrika_options_page_init');
function yandexmetrika_options_page_init() {
	$options_page = add_options_page(__('Settings') . ' ' . __('Yandex.Metrika', 'yandexmetrika'), __('Yandex.Metrika', 'yandexmetrika'), 'administrator', 'yandexmetrika', 'yandexmetrika_options_page');
	add_action("load-{$options_page}", 'yandexmetrika_load_options_page');
}
function yandexmetrika_load_options_page() {
	if ($_POST["yandexmetrika-options-submit"] == 'Y') {
	check_admin_referer("yandexmetrika");
	yandexmetrika_save_options();
	wp_redirect(admin_url('options-general.php?page=yandexmetrika&' ));
	exit;
	}
}
function yandexmetrika_save_options() {
	global $pagenow;
	$options = get_option("wp_yandexmetrika");
	if ($pagenow == 'options-general.php' && $_GET['page'] == 'yandexmetrika') {
		$options['metrika_admintracking'] = $_POST['metrika_admintracking'];
		$options['metrika_counter'] = $_POST['metrika_counter'];
		$options['metrika_position'] = $_POST['metrika_position'];
	}
	if (!current_user_can('unfiltered_html')) {
		if ($options['metrika_admintracking']) $options['metrika_admintracking'] = stripslashes(esc_textarea(wp_filter_post_kses($options['metrika_admintracking'])));
		if ($options['metrika_counter']) $options['metrika_counter'] = stripslashes(esc_textarea(wp_filter_post_kses($options['metrika_counter'])));
		if ($options['metrika_position']) $options['metrika_position'] = stripslashes(esc_textarea(wp_filter_post_kses($options['metrika_position'])));
	}
	$updated = update_option("wp_yandexmetrika", $options);
}
function yandexmetrika_options_page() {
	global $pagenow;
	$options = get_option("wp_yandexmetrika"); ?>
<div class="wrap">
	<img src="<?php echo plugin_dir_url(__FILE__) ?>metrika.png" class="icon32">
	<h2><?php _e('Yandex.Metrika', 'yandexmetrika'); ?></h2>
	</div>
	<form action="" method="post" id="yandexmetrika">
		<table class="form-table" style="clear:none;">
		<?php wp_nonce_field('yandexmetrika'); ?>
		<td>
			<?php _e('<a href="http://metrika.yandex.com/">Your Yandex.Metrika counters</a>', 'yandexmetrika'); ?></a>
		</td>
		<tr>
			<th scope="row" valign="top">
				<label for="metrika_counter"><?php _e('Yandex.Metrika counter code:', 'yandexmetrika'); ?></label>
			</th>
			<td>
				<textarea id="metrika_counter" class="code" name="metrika_counter" rows="10" cols="70"><?php echo esc_html(stripslashes($options["metrika_counter"])); ?></textarea>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="position"><?php _e('Place of counter code', 'yandexmetrika'); ?></label>
			</th>
			<td>
				<select id="position" name="metrika_position" style="width:200px;">
					<option value="footer"<?php if ($options['metrika_position'] == 'footer' || $options['metrika_position'] == "") { echo ' selected="selected"';} ?>><?php _e('Footer (default)', 'yandexmetrika'); ?></option>
					<option value="header"<?php if ($options['metrika_position'] == 'header') { echo ' selected="selected"';} ?>><?php _e('Header', 'yandexmetrika'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">
				<label for="admintracking"><?php _e('Track administrators', 'yandexmetrika'); ?></label>
			</th>
			<td>
				<input type="checkbox" id="admintracking" name="metrika_admintracking" <?php if ($options['metrika_admintracking']) echo ' checked="checked" '; ?>/> 
			</td>
		</tr>	
		</table>
		<p class="submit" style="clear: both;">
			<input type="submit" name="Submit"  class="button-primary" value="<?php echo __('Save Draft'); ?>" />
			<input type="hidden" name="yandexmetrika-options-submit" value="Y" />
		</p>
	</form>
</div>
<?php
}
class YM_Paste {
	function yandexmetrika_paste() {
		$options = get_option("wp_yandexmetrika");
		if ( trim( $options["metrika_counter"] != "" ) && (!current_user_can('administrator') || $options["metrika_admintracking"]) && !is_preview() ) {
			echo  stripslashes($options['metrika_counter']);
		} else if ((current_user_can('administrator') && !$options["metrika_admintracking"])) {
			echo "\n<!-- YandexMetrika Counter: administrator tracking disabled -->\n";
		}
	}
}
$WP_YandexMetrika = new YM_Paste();
if (function_exists('register_uninstall_hook')) register_uninstall_hook(__FILE__, 'yandexmetrika_uninstall');
function yandexmetrika_uninstall() {
	delete_option('wp_yandexmetrika');
}
function yandexmetrika_init() {
	$options = get_option("wp_yandexmetrika");
	if (empty($options)) {
		$options = array('metrika_counter' => get_option('metrika_counter'), 'metrika_admintracking' => get_option('metrika_admintracking'), 'metrika_position' => get_option('metrika_position'));
		add_option("wp_yandexmetrika", $options, '', 'yes');
	}
	if ($options['metrika_position'] == 'footer' || $options['metrika_position'] == "") {
		add_action('wp_footer', array('YM_Paste','yandexmetrika_paste'));
	} else {
		add_action('wp_head', array('YM_Paste','yandexmetrika_paste'),20);
	}
}
load_plugin_textdomain('yandexmetrika', false, basename( dirname( __FILE__ ) ) . '/languages' );
?>