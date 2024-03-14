<?php
/**
 * Content Writer
 * Copyright (C) 2020 SteadyContent
 * Plugin Name: Content Writer
 * Plugin URI:  https://wordpress.org/plugins/content-writer/
 * Description: Allows users to order, post and socially share uniquely written content to their blog.
 * Author:      SteadyContent
 * Version:     3.6.6
 * Author URI:  https://www.steadycontent.com
 */ 

 
// Constants
if (!defined('CONWR_VERSION')) define('CONWR_VERSION', '3.6.6');
if (!defined('CONWR_BASE_DIR')) define('CONWR_BASE_DIR', dirname(__file__));
if (!defined('CONWR_BASE_URL')) define('CONWR_BASE_URL', plugins_url() . DIRECTORY_SEPARATOR . dirname(plugin_basename(__FILE__)) . DIRECTORY_SEPARATOR);
if (!defined('CONWR_PLUGIN_NAME')) define('CONWR_PLUGIN_NAME', 'Content Writer');
if (!defined('CONWR_PLUGIN_MENU_FUNCTION')) define('CONWR_PLUGIN_MENU_FUNCTION', 'conwr_render_options_page');
if (!defined('CONWR_PLUGIN_MENU_SLUG_NAME')) define('CONWR_PLUGIN_MENU_SLUG_NAME', 'sc-settings');
if (!defined('CONWR_IDENTIFIER_URL')) define('CONWR_IDENTIFIER_URL', 'http://app.steadycontent.com/token/verify.aspx');

require_once(dirname( __FILE__ ) . '/lib/menu.php');
require_once(dirname( __FILE__ ) . '/lib/sc_functions.php');
require_once(dirname( __FILE__ ) . '/lib/rest_api.php');
require_once(dirname( __FILE__ ) . '/lib/sc_api.php');
require_once(dirname( __FILE__ ) . '/lib/conwr_xmlrpc_api.php');

// Set up initial actions
add_action('init', 'conwr_api_init');
add_action('admin_init', 'conwr_tinymce_init');
add_filter('mce_css', 'conwr_mce_css');
add_action('admin_menu', 'conwr_admin_menu_action');
add_action('admin_enqueue_scripts', 'conwr_enqueue_scripts');
add_action('transition_post_status', 'conwr_post_transition_status', 10, 3);
add_action('post_updated', 'conwr_post_updated_action', 10, 3);
add_action('delete_post', 'conwr_post_deleted_action');
add_action('rest_api_init', 'conwr_rest_api_init');
//add_action('edit_form_after_title', 'conwr_post_keywords_metabox', 1);

//Multiple Tiles
global $conwr_db_version;
$conwr_db_version = "1.0";

include('lib/conwr-user-agents.php');


function conwr_admin_menu_action() {
	add_filter('plugin_action_links', 'conwr_add_settings_link', 10, 2); //add Settings link on main Plugins page
}

function conwr_add_settings_link($links, $file) {
    if ($file == 'content-writer/content-writer.php') {
        $settings_link = '<a href="admin.php?page=sc-settings">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;   
}

function conwr_enqueue_scripts() {
	wp_enqueue_script('stconjs', CONWR_BASE_URL . 'assets/js/conwr_base.js');
}

function conwr_tinymce_init() {
	add_filter('mce_external_plugins', 'conwr_tinymce_plugin');
}

function conwr_tinymce_plugin($init) {
    // We create a new plugin... linked to a js file.
	$init['keyup_event'] = CONWR_BASE_URL . 'assets/js/conwr_tinymce.js';
	
    return $init;
}

function conwr_render_options_page() {
	global $titleEx;

	if (isset($_REQUEST['save_settings'])) {
		$email = sanitize_email($_REQUEST['sc_email']);
		$psw = sanitize_text_field($_REQUEST['sc_password']);

		add_action('admin_notices', 'conwr_admin_message');
		$url = "https://app.steadycontent.com/app/account/pluginverification.aspx?action=verify&email={$email}&psw={$psw}";

		$response = conwr_get_request($url, false, 10);

		if (isset($response) && strpos($response, "key:") !== false) {
			$api_key = str_replace("key:", "", $response);

			update_option("conwr_email", $email);
			update_option("conwr_api_key", $api_key);

			echo '<div class="notice notice-success is-dismissible"><p>You are successfully connected now!</p></div>';
		}
		else {
			echo '<div class="notice notice-error is-dismissible"><p>Your account could not be verified, please click the link to create a new account, send an email to <a href="mailto:support@steadycontent.com">support@steadycontent.com</a> or click the chat box within the <a href="https://steadycontent.com/" target="_blank">app</a>.</p></div>';
		}
	}

	if (isset($_REQUEST['disconnect'])) {
		delete_option("conwr_api_key");
		echo '<div class="notice notice-success is-dismissible"><p>You have successfully disconnected now!</p></div>';
	}

	$sc_email = get_option("conwr_email", false);
	$sc_api_key = get_option("conwr_api_key", false);
	$sc_connected = conwr_check_api_key();

	include ('lib/conwr-general-settings.php');
}

function conwr_post_keywords_metabox() {
	global $post;

	//store post ID which is being edited
	update_option("conwr_edited_post_id", $post->ID);

	$sc_keywords_meta = trim(conwr_get_post_keywords_meta($post->ID));
	$sc_keywords_meta = rtrim($sc_keywords_meta, ',');

	if ($sc_keywords_meta != null && !empty($sc_keywords_meta)) {
		$sc_keywords_arr = explode(",", $sc_keywords_meta);

		$content = wpautop($post->post_content);

		$first_p_contains_kw = conwr_is_first_paragraph_contains_kw($content, $sc_keywords_arr) ? "Yes" : "No";

		$first_kw_no_of_occ = count($sc_keywords_arr) > 0 ? conwr_get_number_of_occurrences($post->post_content, trim($sc_keywords_arr[0])) : 0;
		$second_kw_no_of_occ = count($sc_keywords_arr) > 1 ? conwr_get_number_of_occurrences($post->post_content, trim($sc_keywords_arr[1])) : 0;
		$third_kw_no_of_occ = count($sc_keywords_arr) > 2 ? conwr_get_number_of_occurrences($post->post_content, trim($sc_keywords_arr[2])) : 0;

		$first_kw_css_class = $first_kw_no_of_occ == "0" ? "first-value" : "first-value green";
		$second_kw_css_class = $second_kw_no_of_occ == "0" ? "first-value" : "first-value green";
		$third_kw_css_class = $third_kw_no_of_occ == "0" ? "first-value" : "first-value green";

		$writer_json = '';
		$writer_code = '';
		$content_id = '';
		$show_writer_info = '';

		$sc_id = get_post_meta($post->ID, "steady_content_id", true);
		if ($sc_id && !empty($sc_id)) {
			$writer_json = conwr_get_writer_details($sc_id);
			$writer_code = conwr_get_writer_code($sc_id);
			$content_id = conwr_get_content_id($sc_id);
			$show_writer_info = conwr_show_writer_info($sc_id);

			if ($writer_json != null && !empty($writer_json)) {
				$writer_arr = json_decode($writer_json, true);

				if ($writer_arr != null) {
					$writer_id = $writer_arr["Writer"]["RecID"];
					$writer_status = $writer_arr["Writer"]["Status"];
					$is_writer_favorited = conwr_is_writer_is_favorited($sc_id, $writer_id);
				}
			}
		}

		?>
		<div class="conwr-kw-wrapper">
			<div class="conwr-kw-inner">
				<table class="conwr-kw-table">
					<?php
					if ($show_writer_info && $writer_id != null && !empty($writer_id) && $writer_code && !empty($writer_code)) {
						?>
						<tr>
							<td class="kw-label">
								Writer Info:
							</td>
							<td class="kw-value">
								<div class="writer-info-wrapper">
									<span style="position: relative; top: -6px;"><?php echo $writer_code; ?></span>
									<span><a href="javascript:void(0)" onclick="ShowHideWriterPopup(true, 0)" title="Message Writer"><i class="material-icons md-20">email</i></a></span>
									<span id="spanFavoriteAction">
										<?php if ($is_writer_favorited) { ?>
											<span title="Writer is already favorited"><i class="fa fa-heart" style="color: #a0a0a0;"></i></span>
										<?php } else { ?>
											<a href="javascript:void(0)" onclick="ShowHideWriterPopup(true, 1)" title="Favorite Writer"><i class="fa fa-heart"></i></a>
										<?php } ?>
									</span>
									<span id="spanFlaggedAction">
										<?php if ($writer_status != "Paused") { ?>
											<a href="javascript:void(0)" onclick="ShowHideWriterPopup(true, 2)" title="Flag Writer (for campaign)"><i class="material-icons md-20">flag</i></a>
										<?php } else { ?>
											<span title="Writer is already flagged"><i class="material-icons md-20" style="color: #a0a0a0;">flag</i></span>
										<?php } ?>
									</span>
								</div>
							</td>
						</tr>
						<?php 
					}
					?>
					<tr>
						<td class="kw-label">
							Word Count:
						</td>
						<td class="kw-value">
							<span id="spanWordCount" class="first-value green"></span>
						</td>
					</tr>
					<tr>
						<td class="kw-label">
							KW in 1st <i class="fa fa-paragraph"></i>:
						</td>
						<td class="kw-value">
							<span id="span1stPContainsKW" class="first-value green"><?php echo $first_p_contains_kw; ?></span>
						</td>
					</tr>
					<?php
					if ( !is_null($sc_keywords_arr) && count($sc_keywords_arr) > 0 && !empty($sc_keywords_arr[0]) != "" && $sc_keywords_arr[0] != " ") {
						?>
						<tr>
							<td class="kw-label">
								Keyword:
							</td>
							<td class="kw-value">
								<span id="span1stKWCount" class="<?php echo $first_kw_css_class; ?>"><?php echo $first_kw_no_of_occ; ?></span>
								<span class="second-value"><?php echo $sc_keywords_arr[0]; ?></span>
							</td>
						</tr>
						<?php 
					}
					if (!is_null($sc_keywords_arr) &&  count($sc_keywords_arr) > 1 && !empty($sc_keywords_arr[1]) != "" && $sc_keywords_arr[1] != " ") {
						?>
						<tr>
							<td class="kw-label">
								Keyword:
							</td>
							<td class="kw-value">
								<span id="span2ndKWCount" class="<?php echo $second_kw_css_class; ?>"><?php echo $second_kw_no_of_occ; ?></span>
								<span class="second-value"><?php echo $sc_keywords_arr[1]; ?></span>
							</td>
						</tr>
						<?php 
					}
					if (!is_null($sc_keywords_arr) && count($sc_keywords_arr) > 2 && !empty($sc_keywords_arr[2]) != "" && $sc_keywords_arr[2] != " ") {
						?>
						<tr>
							<td class="kw-label">
								Keyword:
							</td>
							<td class="kw-value">
								<span id="span3rdKWCount" class="<?php echo $third_kw_css_class; ?>"><?php echo $third_kw_no_of_occ; ?></span>
								<span class="second-value"><?php echo $sc_keywords_arr[2]; ?></span>
							</td>
						</tr>
						<?php 
					}
					?>
				</table>
			</div>
		</div>
		<div class="conwr-modal-background"></div>
		<div class="conwr-writer-popup-wrapper">
			<div class="conwr-writer-popup-inner">
				<div class="popup-error">
					Please enter some comment.
				</div>
				<div class="popup-title">
					<span>Writer Feedback</span>
				</div>
				<div class="popup-close" onclick="ShowHideWriterPopup(false, 0)">
					<i class="fa fa-close"></i>
				</div>
				<div class="popup-tab">
					<a class="popup-tablinks" href="javascript:void(0)" onclick="OpenWriterPopupTab(this, 'Message')">Message</a>
					<a class="popup-tablinks" href="javascript:void(0)" onclick="OpenWriterPopupTab(this, 'Favorite')">Favorite</a>
					<a class="popup-tablinks" href="javascript:void(0)" onclick="OpenWriterPopupTab(this, 'Problem')">Problem</a>
				</div>
				<div id="Message" class="popup-tabcontent">
					<table id="writerSubmitTable" class="conwr-kw-table">
						<tr>
							<td class="kw-label">
								Writer ID:
							</td>
							<td class="kw-value">
								<span class="first-value"><?php echo $writer_code; ?></span>
							</td>
						</tr>
						<tr>
							<td class="kw-label">
								Content ID:
							</td>
							<td class="kw-value">
								<span class="first-value"><?php echo $content_id; ?></span>
							</td>
						</tr>
						<tr>
							<td class="kw-label">
								Title:
							</td>
							<td class="kw-value">
								<span class="first-value"><?php echo $post->post_title; ?></span>
							</td>
						</tr>
						<tr>
							<td class="kw-label" style="vertical-align: top;">
								Comments:
							</td>
							<td class="kw-value">
								<textarea id="txtComments" rows="6" style="width: 95%;"></textarea>
							</td>
						</tr>
					</table>
					<div class="action-buttons">
						<img class="popup-loader" src="<?php echo CONWR_BASE_URL ?>assets/images/loading.gif">
						<a class="submit-wf-button" href="javascript:void(0)" onclick="SubmitWriterFeedback(<?php echo $sc_id; ?>)">SEND</a>
						<a class="cancel-button" href="javascript:void(0)" onclick="ShowHideWriterPopup(false, 0)">CANCEL</a>
					</div>
				</div>
				<div id="Favorite" class="popup-tabcontent">
					
				</div>
				<div id="Problem" class="popup-tabcontent">
					
				</div>
			</div>
		</div>
		<?php
	}
}



function conwr_mce_css($mce_css) {
	//add custom css to tinymce editor
	if (!empty($mce_css))
		$mce_css .= ',';

	$mce_css .= plugins_url('assets/css/tinymce-custom.css', __FILE__);

	return $mce_css;
}
?>