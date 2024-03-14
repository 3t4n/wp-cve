<?php

if (!defined('ABSPATH')) {
	die( 'Access Disabled' );
}
	function google_recaptcha_options_page() {
	echo "<div class=\"wrap\">
	<h1>".__("Wordpress Google re-CAPTCHA Setting Form", "wp-google-recaptcha")."</h1>
	<form method=\"post\" action=\"options.php\">";
	settings_fields("google_recaptcha_header_section");
	do_settings_sections("google_recaptcha-options");
	submit_button();
	echo "</form>
	</div>";
}

function google_recaptcha_menu() {
	add_submenu_page("options-general.php", "reCAPTCHA", "reCAPTCHA", "manage_options", "google_recaptcha-options", "google_recaptcha_options_page");
}
add_action("admin_menu", "google_recaptcha_menu");

function google_recaptcha_display_content() {
	echo "<p>".__("You have to <a target=\"_blank\" href=\"https://www.google.com/recaptcha/admin\" rel=\"external\">Click here</a> to create google map api site key and secret key.", "wp-google-recaptcha")."</p>";
}

function google_recaptcha_display_site_key_element() {
	$google_recaptcha_site_key = filter_var(get_option("google_recaptcha_site_key"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	echo "<input type=\"text\" name=\"google_recaptcha_site_key\" class=\"regular-text\" id=\"google_recaptcha_site_key\" value=\"{$google_recaptcha_site_key}\" />";
}

function google_recaptcha_display_secret_key_element() {
	$google_recaptcha_secret_key = filter_var(get_option("google_recaptcha_secret_key"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	echo "<input type=\"text\" name=\"google_recaptcha_secret_key\" class=\"regular-text\" id=\"google_recaptcha_secret_key\" value=\"{$google_recaptcha_secret_key}\" />";
}


function google_recaptcha_display_options() {

	add_settings_field("google_recaptcha_site_key", __("Site Key", "wp-google-recaptcha"), "google_recaptcha_display_site_key_element", "google_recaptcha-options", "google_recaptcha_header_section");
	add_settings_field("google_recaptcha_secret_key", __("Secret Key", "wp-google-recaptcha"), "google_recaptcha_display_secret_key_element", "google_recaptcha-options", "google_recaptcha_header_section");

	add_settings_section("google_recaptcha_header_section", __("Where Find belows details in Google?", "wp-google-recaptcha"), "google_recaptcha_display_content", "google_recaptcha-options");

	register_setting("google_recaptcha_header_section", "google_recaptcha_site_key");
	register_setting("google_recaptcha_header_section", "google_recaptcha_secret_key");
	register_setting("google_recaptcha_header_section", "google_recaptcha_login_check_disable");
}
add_action("admin_init", "google_recaptcha_display_options");

function load_language_google_recaptcha() {
	load_plugin_textdomain("wp-google-recaptcha", false, dirname(plugin_basename(__FILE__))."/languages/");
}
add_action("plugins_loaded", "load_language_google_recaptcha");

function frontend_google_recaptcha_script() {
	$google_recaptcha_site_key = filter_var(get_option("google_recaptcha_site_key"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$google_recaptcha_display_list = array("comment_form_after_fields", "register_form", "lost_password", "lostpassword_form", "retrieve_password", "resetpass_form", "woocommerce_register_form", "woocommerce_lostpassword_form", "woocommerce_after_order_notes", "bp_after_signup_profile_fields");
	
	if (!get_option("google_recaptcha_login_check_disable")) {
		array_push($google_recaptcha_display_list, "login_form", "woocommerce_login_form");
	}
	
	foreach($google_recaptcha_display_list as $google_recaptcha_display) {
		add_action($google_recaptcha_display, "google_recaptcha_display");
	}
	
	wp_register_script("google_recaptcha_recaptcha_main", plugin_dir_url(__FILE__)."main.js?v=2.9");
	wp_enqueue_script("google_recaptcha_recaptcha_main");
	wp_localize_script("google_recaptcha_recaptcha_main", "google_recaptcha_recaptcha", array("site_key" => $google_recaptcha_site_key));
		
	wp_register_script("google_recaptcha_recaptcha", "https://www.google.com/recaptcha/api.js?hl=".get_locale()."&onload=google_recaptcha&render=explicit");
	wp_enqueue_script("google_recaptcha_recaptcha");
		
	wp_enqueue_style("style", plugin_dir_url(__FILE__)."style.css?v=2.9");
}

function google_recaptcha_display() {
	echo "<div class=\"google_recaptcha-recaptcha\"></div>";
}

function google_recaptcha_verify($input) { 
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["g-recaptcha-response"])) {
		$google_recaptcha_secret_key = filter_var(get_option("google_recaptcha_secret_key"), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$recaptcha_response = filter_input(INPUT_POST, "g-recaptcha-response", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$google_recaptcha_secret_key}&response={$recaptcha_response}");
		$response = json_decode($response["body"], 1);
		
		if ($response["success"]) {
			return $input;
		} elseif (is_array($input)) { // Array = Comment else Object
			wp_die("<p><strong>".__("ERROR:", "wp-google-recaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wp-google-recaptcha")."</p>", "reCAPTCHA", array("response" => 403, "back_link" => 1));
		} else {
			return new WP_Error("reCAPTCHA", "<strong>".__("ERROR:", "wp-google-recaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wp-google-recaptcha"));
		}
	} else {
		wp_die("<p><strong>".__("ERROR:", "wp-google-recaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wp-google-recaptcha")." ".__("Do you have JavaScript enabled?", "wp-google-recaptcha")."</p>", "reCAPTCHA", array("response" => 403, "back_link" => 1));
	}
}
function google_recaptcha_shortcode($atts) {
    return "<div class=\"google_recaptcha-recaptcha\"></div>";
}
add_shortcode('google_recaptcha', 'google_recaptcha_shortcode');

function google_recaptcha_form_elements($form) {
    $form = do_shortcode($form);
    return $form;
}
add_filter('wpcf7_form_elements', 'google_recaptcha_form_elements');

function google_recaptcha_check() {
	if (get_option("google_recaptcha_site_key") && get_option("google_recaptcha_secret_key") && !is_user_logged_in() && !function_exists("wpcf7_contact_form_shortcode") && function_exists("google_recaptcha_shortcode") ) {
		add_action("login_enqueue_scripts", "frontend_google_recaptcha_script");
		add_action("wp_enqueue_scripts", "frontend_google_recaptcha_script");
		
		$google_recaptcha_verify_list = array("preprocess_comment", "registration_errors", "lostpassword_post", "resetpass_post", "woocommerce_register_post","wpcf7_posted_data");
		
		if (!get_option("google_recaptcha_login_check_disable")) {
			array_push($google_recaptcha_verify_list, "wp_authenticate_user", "bp_signup_validate");
		}
		
		foreach($google_recaptcha_verify_list as $google_recaptcha_verify) {
			add_action($google_recaptcha_verify, "google_recaptcha_verify");
		}
	}
}

add_action("init", "google_recaptcha_check");
?>