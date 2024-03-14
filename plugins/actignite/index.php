<?php 

/*
 * Plugin Name: Actignite
 * Description: WordPress Email's Default Sender Name And Email Address Customizer
 * Version:     0.0.1
 * Author:      Actignite
 * Author URI:  https://profiles.wordpress.org/actignite/
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: actignite
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Load plugin textdomain.
 */

function actignite_campaign_load_textdomain() {
  load_plugin_textdomain( 'actignite', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

add_action( 'init', 'actignite_campaign_load_textdomain' );

function actignite_campaign_sender_register() {
	add_settings_section('actignite_campaign_wp_default_se_customizer_section', __('WordPress Email\'s Default Sender Name and Email Address Customizer: Actignite', 'actignite'), 'actignite_campaign_wp_default_se_customizer_text', 'actignite_campaign_wp_default_se_mail_sender');

	add_settings_field('actignite_campaign_wp_default_mail_sender_name_id', __('Email Sender Name','actignite'), 'actignite_campaign_wp_default_se_customizer_function', 'actignite_campaign_wp_default_se_mail_sender',  'actignite_campaign_wp_default_se_customizer_section');

	register_setting('actignite_campaign_wp_default_se_customizer_section', 'actignite_campaign_wp_default_mail_sender_name_id');

	add_settings_field('actignite_campaign_wp_default_mail_sender_email_id', __('Sender Email Address', 'actignite'), 'actignite_campaign_wp_default_sender_email', 'actignite_campaign_wp_default_se_mail_sender',  'actignite_campaign_wp_default_se_customizer_section');

	register_setting('actignite_campaign_wp_default_se_customizer_section', 'actignite_campaign_wp_default_mail_sender_email_id');

}
add_action('admin_init', 'actignite_campaign_sender_register');



function actignite_campaign_wp_default_se_customizer_function(){

	printf('<input name="actignite_campaign_wp_default_mail_sender_name_id" type="text" class="regular-text" value="%s" placeholder="WordPress"/>', get_option('actignite_campaign_wp_default_mail_sender_name_id'));

}
function actignite_campaign_wp_default_sender_email() {
	printf('<input name="actignite_campaign_wp_default_mail_sender_email_id" type="email" class="regular-text" value="%s" placeholder="wordpress@yourdomain.com"/>', get_option('actignite_campaign_wp_default_mail_sender_email_id'));


}

function actignite_campaign_wp_default_se_customizer_text() {

	printf('%s By default, it uses "WordPress" as the sender\'s name and a non-existent email address (wordpress@yourdomain.com) as the sender email. <br>

	To configure it according to your own preference you will need to enter the name and email address you want to be used for outgoing WordPress emails. Don’t forget to click on the save changes button to store your settings. <br>
	
	That’s all, your WordPress notification emails will now show the name and email address you entered in plugin settings. <hr> %s', '<p>', '</p>'); 

}

function actignite_campaign_admin_menu() {
	add_menu_page(__('Actignite Campaign Options', 'actignite'), __('Actignite', 'actignite'), 'manage_options', 'actignite_campaign', 'actignite_campaign_wp_default_mail_sender_output', 'dashicons-yes-alt');


}
add_action('admin_menu', 'actignite_campaign_admin_menu');

function actignite_campaign_wp_default_mail_sender_output(){
?>	
	<?php settings_errors();?>
	<form action="options.php" method="POST">
		<?php do_settings_sections('actignite_campaign_wp_default_se_mail_sender');?>
		<?php settings_fields('actignite_campaign_wp_default_se_customizer_section');?>
		<?php submit_button();?>
	</form>
<?php }

// Change the default Sender (wordpress@exampleyourdomain.com) email address
add_filter('wp_mail_from', 'actignite_campaign_wp_new_form');
add_filter('wp_mail_from_name', 'actignite_campaign_wp_new_form_name');
 
function actignite_campaign_wp_new_form($old) {
	return get_option('actignite_campaign_wp_default_mail_sender_email_id');
}
function actignite_campaign_wp_new_form_name($old) {
	return get_option('actignite_campaign_wp_default_mail_sender_name_id');
}