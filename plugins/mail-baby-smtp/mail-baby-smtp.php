<?php
/**
 * Plugin Name: Mail Baby SMTP
 * Plugin URI: https://www.mail.baby/
 * Description: MailBaby is an email smart host that offers outbound filtering. Emails are sent to MailBaby systems, and are analyzed for content. Email is then routed through an email zone based on the email content, and score of the email, or bounced as spam. IP reputation is handled by MailBaby. MailBaby monitors all our ips for blacklists, and works with email providers through feedback loops and other abuse monitoring to ensure email delivery.
 * Version: 2.8
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Author: Mail.Baby
 * Author URI: https://www.mail.baby
 **/

if (!defined('ABSPATH')) {
	exit;
}

function mbs_plugin_activated()
{

	global $wpdb;

	$table_name = $wpdb->prefix . 'mbs_mail_log';

	// $wpdb_collate = $wpdb->collate;
	$wpdb_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table_name} (

            `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,

            `error_log` VARCHAR(1000) NOT NULL,

     	) $wpdb_collate;";

	//echo $sql;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql, true);
}

register_activation_hook(__FILE__, 'mbs_plugin_activated');

add_action('admin_enqueue_scripts', 'register_mbsmtp_admin_scripts');

function register_mbsmtp_admin_scripts()
{
	$assets_url  = plugins_url( '', __FILE__ ). '/inc/assets';
	if ((isset($_GET['page'])) and ($_GET['page'] == 'mail-baby-smtp-settings')) {
		wp_enqueue_style('bootstrap-ui-css', $assets_url . '/css/bootstrap/bootstrap/css/bootstrap.min.css');
		wp_enqueue_script('mbsmtp_js_bootstrap_bundle', $assets_url . '/js/bootstrap/js/bootstrap.bundle.min.js');
		wp_enqueue_script('mbsmtp_popper', $assets_url . '/js/popper.min.js');
		wp_enqueue_script('mbsmtp_js_bootstrap_min', $assets_url . '/js/bootstrap/js/bootstrap.min.js');
	}
}


add_action('wp_mail_failed', 'onMailError', 10, 1);

function onMailError($wp_error)
{

	$mbsmtp_options = get_option('MAIL_BABY_SMTP_options');

	$errors = $wp_error->errors['wp_mail_failed'];

	$mailer = $mbsmtp_options['mailer'];

	if ($mailer != 'othersmtp') {

		update_option('smtp_error_log', $errors);
	} else {

		return;
	}
}



$options = get_option('MAIL_BABY_SMTP_options');


require_once trailingslashit(dirname(__FILE__)) . 'inc/Gmail_SMTP_Manager.php';


if ($options['mailer'] === 'sendgrid') {

	require_once trailingslashit(dirname(__FILE__)) . 'inc/Sendgrid_SMTP_Manager.php';
}

if ($options['mailer'] === 'smtp') {

	require_once trailingslashit(dirname(__FILE__)) . 'inc/Smtpcom_Manager.php';
}

if ($options['mailer'] === 'mailgun') {

	require_once trailingslashit(dirname(__FILE__)) . 'inc/Mailgun_SMTP_Manager.php';
}

if ($options['mailer'] === 'sendinblue ') {

	require_once trailingslashit(dirname(__FILE__)) . 'inc/Sendinblue.php';
}

if ($options['mailer'] === 'mailbaby') {



	require_once trailingslashit(dirname(__FILE__)) . 'inc/Mailbaby_SMTP_Manager.php';
}

if ($options['mailer'] === 'othersmtp') {

	require_once trailingslashit(dirname(__FILE__)) . 'inc/Other_SMTP_Manager.php';
}
