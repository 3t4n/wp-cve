<?php
/**
 * @package   ads.txt
 * @author    Stefan Böttcher
 *
 * @wordpress-plugin
 * Plugin Name: ads.txt for WordPress
 * Description: easy ads.txt integration for your WordPress
 * Version:     0.1
 * Author:      wp-hotline.com ~ Stefan
 * Author URI:  wp-hotline.com/m/ads-txt-for-wordpress/
 * License: GPLv2 or later
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
	die;
}
add_action('admin_menu', 'ads_txt_admin_add_page');
function ads_txt_admin_add_page() {
	add_options_page('current ads.txt for your site:', 'ads.txt', 'manage_options', 'ads.txt', 'ads_txt_options_page');
}

if(!function_exists('var_dumpp')) {
	function var_dumpp( $data ) {
		echo '<pre>'; var_dump($data); echo '</pre>';
	}
}

function ads_txt_options_page() {

	$options = get_option('ads_txt_options');
	#var_dump($options);

	$file = get_home_path() . DIRECTORY_SEPARATOR . 'ads.txt'; //The robots file.

	#var_dumpp('file_put_contents');
	if(!file_exists( $file )) { $file = file_put_contents($file, $options["ads_txt"]); }

	if(is_writable( $file )) {
		$file_new = file_put_contents($file, $options["ads_txt"]);
	} else {
		$file_new = false;
	}
	#var_dumpp( $file_new );

	#if(isset($_POST["Submit"])) {

	#}

?>
	<div class="wrap">
	<h1><?php echo __('my ads.txt'); ?></h1>

	<form action="options.php" method="post">

		<?php if($file_new==false) { ?>
		<div class="notice notice-warning"><p><strong><?php echo __('ads.txt not writable!'); ?></strong></p>
			<p><?php echo __('you need to create an ads.txt file in your wordpress root directory manually or change the file permissions of your current'); ?> <code><?php echo get_home_path(); ?>ads.txt</code></p>
		</div>
		<?php } ?>


	<?php settings_fields('ads_txt_options'); ?>
	<?php do_settings_sections('ads_txt_content'); ?>
	<textarea class="large-text" style="padding: 1em;" id='ads_txt_text_string' name='ads_txt_options[ads_txt]' rows="16" cols="4"><?php echo $options["ads_txt"]; ?></textarea>

	<button name="Submit" type="submit" value="true" class="button button-primary button-large"><?php esc_attr_e('Save Changes'); ?></button>
	</form>

	<br /><br />
	<p><h1>common ads.txt snippets:</h1>
		<br /><strong>#Google Adsense</strong><br />
		<i>google.com, pub-PUBID, DIRECT, f08c47fec0942fa0 #ADSENSE<br />
		google.com, pub-PUBID, RESELLER, f08c47fec0942fa0 #AdX</i><br />

		<br /><strong>#Outbrain</strong><br />
		<i>outbrain.com, PUBID, DIRECT</i><br />

		<br /><strong>#appnexus</strong><br />
		<i>appnexus.com, PUBID, DIRECT</i><br />

		<br /><strong>#SmartAdserver</strong><br />
		<i>smartadserver.com, PUBID, DIRECT</i><br />

	</p>
	</div>

<?php
}

add_action('admin_init', 'ads_txt_admin_init');
function ads_txt_admin_init(){
register_setting( 'ads_txt_options', 'ads_txt_options', 'ads_txt_options_validate' );
add_settings_section('ads_txt_main', false, false, 'plugin');
#add_settings_field('ads_txt_text_string', 'current content', 'ads_txt_setting_string', 'plugin', 'ads_txt_main');
}

function ads_txt_setting_string() {
#$options = get_option('ads_txt_options');
#echo "<textarea id='ads_txt_text_string' name='ads_txt_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

// validate our options
function ads_txt_options_validate($input) {
$newinput['ads_txt'] = trim($input['ads_txt']);
#if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
#$newinput['text_string'] = '';
#}



return $newinput;
}
