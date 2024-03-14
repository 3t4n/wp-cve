<?php

/**
 * Plugin Name: Request Call Back
 * Plugin URI: http://www.scottsalisbury.co.uk/development/wordpress/plugins/request-callback
 * Description: Adds a simple, configurable request call back form to WordPress. Visitors can request a call back by providing their name and number via lightbox (or embedded form), which is then sent to the site owner via email.
 * Author: Scott Salisbury
 * Author URI: http://www.scottsalisbury.co.uk
 * Version: 1.4.1
 * License: GPLv3
 */


$wpcallback_plugin_option  = get_option('wpcallback_plugin_option');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'request-callback-admin.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'callback-form.php');

/* Colorbox and plugin scripts */
function wpcallback_register_scripts() {
	if (!is_admin()) {
		if(wpcallback_get_option('colorbox') == 'enabled' && wpcallback_get_option('lightbox') == 'enabled') {
			wp_register_script('wpcolorbox_js', plugins_url('colorbox/jquery.colorbox-min.js', __FILE__), array( 'jquery' ));
			wp_enqueue_script('wpcolorbox_js');
		}
		wp_register_script('wpcallback_js', plugins_url('js/request-callback.js', __FILE__), array( 'jquery' ));
		wp_enqueue_script('wpcallback_js');
	}
}

add_action('wp_print_scripts', 'wpcallback_register_scripts');

/* Colorbox and plugin css styles */
function wpcallback_register_styles() {
	if(wpcallback_get_option('colorbox') == 'enabled' && wpcallback_get_option('lightbox') == 'enabled') {
		wp_register_style('wpcolorbox_css', plugins_url('colorbox/colorbox.css', __FILE__));
		wp_enqueue_style('wpcolorbox_css');
	}
	wp_register_style('wpcallback_css', plugins_url('css/request-callback.css', __FILE__));
	wp_enqueue_style('wpcallback_css');
}

add_action('wp_print_styles', 'wpcallback_register_styles');

function build_time_intervals($start = 0, $end = 24, $interval = 0.25) {
	$output = array();

	$steps = 60 / ($interval * 60);
	$total = (24-$interval) * $steps;

	for($i=0;$i<=$total;$i++) {
		$decimal_time = $i * $interval;
		$hour = floor($decimal_time);
		$min = round(60*($decimal_time-$hour));

		$hour = ($hour < 10 ? '0' . $hour : $hour);
		$min = ($min < 10 ? '0' . $min : $min);

		if($decimal_time >= $start && $decimal_time <= $end) {
			$output[] = array('time' => $hour . ':' . $min, 'decimal' => $decimal_time);
		}
	}

	return $output;
}

/* Define and handle option defaults */
function wpcallback_get_option($option) {
	global $wpcallback_plugin_option;

	$wpcallback_plugin_default_option = array(
		"label" => "Request a call back",
		"custom_css" => "a.callback-btn-style {\n\n}",
		"email" => get_bloginfo('admin_email'),
		"colorbox" => "enabled",
		"lightbox" => "enabled",
		"classes" => "",
		"allowable_from" => 8.5,
		"allowable_to" => 19.5,
		"field_email" => "disabled",
		"field_time" => "disabled",
		"field_message" => "disabled",
		"field_option_label_name" => "Name",
		"field_option_placeholder_name" => "Your name",
		"field_option_label_telephone" => "Telephone",
		"field_option_placeholder_telephone" => "Your telephone number",
		"field_option_label_email" => "Email",
		"field_option_placeholder_email" => "Your email",
		"field_option_label_time" => "When to call",
		"field_option_placeholder_time" => "Anytime",
		"field_option_label_message" => "Message",
		"field_option_placeholder_message" => "Your message",
		"field_option_label_submit" => "Submit",
		"width" => "400px"
	);

	if($value = $wpcallback_plugin_option[$option]) {
		return $value;
	}

	return $wpcallback_plugin_default_option[$option];
}

function wpcallback_get_description() {
	global $wpcallback_plugin_option;

	if(isset($wpcallback_plugin_option['description'])) {
		return $wpcallback_plugin_option['description'];
	}
	else {
		return 'Enter your details below to request a call back and we will get back in touch as soon as possible.';
	}
}

function wpcallback_get_styling() {
	global $wpcallback_plugin_option;

	if($wpcallback_plugin_option['styling'] != 'custom')
		return 'callback-btn';

	return false;
}

function wpcallback_get_colour() {
	global $wpcallback_plugin_option;

	if($wpcallback_plugin_option['colour'] && $wpcallback_plugin_option['styling'] != 'custom')
		return 'callback-btn-' . $wpcallback_plugin_option['colour'];

	return false;
}

function wpcallback_get_position() {
	global $wpcallback_plugin_option;

	if($wpcallback_plugin_option['position'])
		return 'callback-float-' . $wpcallback_plugin_option['position'];

	return 'callback-float-right';
}

function wpcallback_get_target() {
	global $wpcallback_plugin_option;

	if($value = $wpcallback_plugin_option['target'])
		return get_permalink($value);

	return get_site_url();
}

function wpcallback_get_callback_page() {
	global $wpcallback_plugin_option;

	if($wpcallback_plugin_option['lightbox'] == 'disabled') {
		return get_permalink($wpcallback_plugin_option['callback_page']);
	}

	return get_site_url() . '/?wpcallback_action=form';
}

function wpcallback_output_custom_css() {
	global $wpcallback_plugin_option;

	if($value = $wpcallback_plugin_option['custom_css']) {
		echo '<style type="text/css">' . str_replace(array("\r\n"), " ", strip_tags($value)) . '</style>';
	}
}

function wpcallback_link_to_page($content) {
	global $post;
	global $wpcallback_plugin_option;

	/* If colorbox is disabled and "select page" post ID matches current ID, append the callback form */
	if ($post->ID == $wpcallback_plugin_option['callback_page']) {
		$content .= wpcallback_display_form(false);
		return $content;
	} else {
		return $content;
	}
}

/* If colorbox is disabled and the option to add form to a WordPress page is selected, modify the_content filter to display form */
if($wpcallback_plugin_option['lightbox'] == 'disabled') {
	add_filter( 'the_content', 'wpcallback_link_to_page' );
}

function wpcallback_action() {
	if(isset($_GET['wpcallback_action'])) {

		/* If form is loaded via lightbox, get form content */
		if($_GET['wpcallback_action'] == 'form') {
			echo wpcallback_display_form();
		}

		/* If form is submitted */
		elseif($_GET['wpcallback_action'] == 'email') {

			/* The "Hear about us" field is a honeypot captcha hidden using CSS, if this field has data in it then assume it is a bot and cancel the request */
			if(isset($_POST['hear_about_us']) && !empty($_POST['hear_about_us'])) {
				echo 'Error sending mail';
			}
			elseif(isset($_POST['callback_name']) && isset($_POST['callback_telephone'])) {
				$admin_email = wpcallback_get_option('email');

				$name = strip_tags(stripslashes($_POST['callback_name']));
				$telephone = strip_tags(stripslashes($_POST['callback_telephone']));

				$email = null;
				$time = null;
				$message = null;

				if(isset($_POST['callback_email'])) {
					$email = strip_tags(stripslashes($_POST['callback_email']));
				}

				if(isset($_POST['callback_time'])) {
					$time = strip_tags(stripslashes($_POST['callback_time']));
				}

				if(isset($_POST['callback_message'])) {
					$message = strip_tags(stripslashes($_POST['callback_message']));
				}

				$extra_fields = null;
				if($email) {
					$extra_fields .= "\nEmail address: " . $email;
				}

				if($time) {
					if($time != 'anytime') {
						$decimal_time = $time;
						$hour = floor($decimal_time);
						$min = round(60*($decimal_time-$hour));
						$hour = ($hour < 10 ? '0' . $hour : $hour);
						$min = ($min < 10 ? '0' . $min : $min);
						$string_time = $hour . ":" . $min;
					}
					else {
						$string_time = 'Any time';
					}

					$extra_fields .= "\nWhen to call: " . $string_time;
				}

				if($message) {
					$extra_fields .= "\n\nMessage:\n" . $message;
				}

				$subject = "Call Back Request";
				$message = "A call back request has been sent with the following details:\n\nName: " . $name . "\nTelephone: " . $telephone . $extra_fields . "\n\n\nSent from " . get_site_url() . " via the Request Call Back plugin.";

				/* Send email */
				if(wp_mail($admin_email, $subject, $message)) {
					header("Location:" . wpcallback_get_target());
					exit;
				}
				else {
					echo 'There was a problem submitting your details, please try again later or manually write to us at <a href="mailto:' . $admin_email . '">' . $admin_email . '</a>';
				}
			}
		}

		exit;
	}

}

/* Display the button */
function wpcallback_display_button() {
	$form_width = wpcallback_get_option('width');
	echo '<a href="' . wpcallback_get_callback_page() . '" data-formwidth="' . $form_width . '" class="callback-btn-style ' . wpcallback_get_styling() . ' ' . wpcallback_get_colour() . ' ' . wpcallback_get_position() . ' ' . wpcallback_get_option("classes") . ' callback-form-show">' . wpcallback_get_option("label") . '</a>';
}

add_action('wpcallback_button', 'wpcallback_display_button');

/* Add settings link to plugins list */
function wpcallback_settings_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	if ($file == $plugin) {
		return array_merge(
			$links,
			array( '<a href="' . get_admin_url() . 'options-general.php?page=wpcallback">Settings</a>' )
		);
	}
	return $links;
}

add_action('wp_head', 'wpcallback_output_custom_css');
add_action('parse_request', 'wpcallback_action');
add_filter('plugin_row_meta', 'wpcallback_settings_meta', 10, 2);


