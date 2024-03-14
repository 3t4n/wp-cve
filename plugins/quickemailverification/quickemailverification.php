<?php

/*
Plugin Name:  QuickEmailVerification
Description:  The QuickEmailVerification plugin verifies the email addresses entered on the Wordpress forms and allows you to confirm their deliverability before the form submission. Please <a href="https://quickemailverification.com/register" target="_blank">sign up</a> with QuickEmailVerification to get an <a href="https://quickemailverification.com/apisettings" target="_blank">API key</a> and free verification credits.
Version:      1.9.0
Author:       QuickEmailVerification
Author URI:   https://quickemailverification.com
License:      GNU
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

*/
// Plugin version.
define( 'QEV_PLUGIN_VER', '1.9.0' );

// Need to include because some plugin called is_email in front end.
include_once(ABSPATH.'wp-admin/includes/plugin.php');

qev_plugin_setup();

add_action('admin_notices', 'qev_general_admin_notice');
add_action( 'admin_enqueue_scripts', 'qev_plugin_enqueues' );
add_action( 'wp_ajax_qev_submit_feedback', 'qev_submit_feedback' );
add_action( 'admin_footer_text', 'qev_admin_footer_text' );

// Enqueue the script.
function qev_plugin_enqueues( $hook ) {
    
    if ( $hook == 'plugins.php' ) {
        // Add in required libraries for feedback modal
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );

        wp_register_script( 'qev_admin_script', plugins_url('/feedback.js', __FILE__), array('jquery'));
        wp_enqueue_script( 'qev_admin_script' );
    }
}

function qev_general_admin_notice()
{
	$options = get_option('qev_email_validator');

	// Show notice if the QuickEmailVerification API key has been not saved yet
	if ( trim($options['api_key']) == '' ) {
		 echo '<div class="notice notice-warning is-dismissible">
			 <p>Please get your QuickEmailVerification API key from <a href="https://quickemailverification.com/apisettings">API settings</a> page of QuickEmailVerification and save in <a href="options-general.php?page=quickemailverification">setting page</a> of QuickEmailVerification plugin</p>
		 </div>';
	}

	// Show notice if the remaining credits is running low (less than 20)
	if ( trim($options['api_key']) != '' && $options['remaining_credits'] < 20 ) {
	    echo '<div class="notice notice-warning is-dismissible">
			 <p>Your QuickEmailVerification API credits is low. Please <a href="https://quickemailverification.com/BuyCredits">purchase verification credits</a> to continue using QuickEmailVerification service.</p>
		 </div>';
	}
}

// add the admin options page
add_action('admin_menu', 'qev_plugin_admin_add_page');

function qev_plugin_admin_add_page()
{
	add_options_page('QuickEmailVerification', 'QuickEmailVerification', 'manage_options', 'quickemailverification', 'qev_plugin_options_page');
	wp_register_script( 'plugin_script', plugins_url('/qev.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'plugin_script' );
	wp_register_script( 'plugin_script', plugins_url('/jquery-ui.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'plugin_script' );
	wp_register_style( 'qev_plugin_css', plugins_url('/jquery-ui.css', __FILE__));
	wp_enqueue_style( 'qev_plugin_css' );  
}

// display the admin options page
function qev_plugin_options_page()
{
?>

<div>
	<h2 style="font-size: 1.5em;">QuickEmailVerification</h2>
	<p style="font-size: 14px;">The QuickEmailVerification plugin verifies the email addresses entered on the Wordpress forms and allows you to confirm their deliverability before the form submission. Please <a href="https://quickemailverification.com/register" target="_blank">sign up</a> with QuickEmailVerification to get an <a href="https://quickemailverification.com/apisettings" target="_blank">API key</a> and 100 free verification credits daily.</p>
	<form action="options.php" method="post">
	<?php settings_fields('qev_email_validator'); ?>
	<?php do_settings_sections('qev_plugin'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>

<?php
}

// add the admin settings and such
add_action('admin_init', 'qev_plugin_admin_init');

function qev_plugin_admin_init()
{
	register_setting( 'qev_email_validator', 'qev_email_validator');
	
	add_settings_section('qev_plugin_main', 'Get Ready to Verify Emails!', 'qev_plugin_section_text', 'qev_plugin');
	add_settings_field('qev_api_key', 'QuickEmailVerification API Key', 'qev_api_key_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field( 'qev_remaining_credits', 'Remaining Credits', 'qev_remaining_credits', 'qev_plugin', 'qev_plugin_main' );
	add_settings_field('qev_results_to_exclude', 'Results to Exclude', 'qev_results_to_exclude_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_error_message', 'Error Message for Rejected Email', 'qev_error_message_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_cf7', 'Hook to Contact Form 7 Forms', 'qev_cf7_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_ninja_forms', 'Hook to Ninja forms', 'qev_ninja_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_formidable_forms', 'Hook to Formidable forms', 'qev_formidable_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_profile_builder_forms', 'Hook to Profile Builder', 'qev_profile_builder_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_bws_forms', 'Hook to BWS forms', 'qev_bws_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_um_forms', 'Hook to Ultimate Member forms', 'qev_um_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_wp_forms', 'Hook to WP forms', 'qev_wp_forms_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_hook_to_wc_forms', 'Hook to WooCommerce checkout forms', 'qev_wc_forms_setting', 'qev_plugin', 'qev_plugin_main');
        add_settings_field('qev_hook_to_fluent_forms', 'Hook to Fluent forms', 'qev_fluent_forms_setting', 'qev_plugin', 'qev_plugin_main');
        add_settings_field('qev_hook_to_wp_everest_forms', 'Hook to WPEverest forms', 'qev_wp_everest_forms_setting', 'qev_plugin', 'qev_plugin_main');
        add_settings_field('qev_hook_to_wp_buddypress_forms', 'Hook to Buddypress forms', 'qev_wp_buddypress_forms_setting', 'qev_plugin', 'qev_plugin_main');	
        add_settings_field('qev_hook_to_is_email', 'Hook to is_email() function', 'qev_is_email_hook_setting', 'qev_plugin', 'qev_plugin_main');
	add_settings_field('qev_debug_log', 'Debug log', 'qev_debug_log_setting', 'qev_plugin', 'qev_plugin_main');
}

function qev_plugin_section_text()
{
	echo '<p style="font-size: 14px;">Select the forms to which you want to hook the QuickEmailVerification plugin. If you are using some other form plugin which is not mentioned below, you can still use the plugin by using "is_email()" function as we are attaching that by default. But "is_email()" hook will automatically be disabled if any of the other hooks, which are listed below, is enabled.<br /><br />You can also change the default setting to reject email addresses with specific result flag and change the error message displayed to the user upon email rejection. To get more idea about email verification results, please refer <a href="http://docs.quickemailverification.com/getting-started/understanding-email-verification-result">QuickEmailVerification knowledge base</a> doc.</p>';
}

function qev_api_key_setting()
{
	$options = get_option('qev_email_validator');

	$api_key = isset($options['api_key']) ? $options['api_key'] : ' ';
	echo '<input id="api_key" name="qev_email_validator[api_key]" size="40" type="text" value="' . $api_key. '" style="margin-bottom: 5px;"/><br />';
	echo '<label style="color:#666;font-style:italic">Copy the API key from the QuickEmailVerification <a href="https://quickemailverification.com/apisettings" target="_blank">API settings</a> page and paste here. Plugin can work only when an API key is set here.</label>';
}

function qev_remaining_credits() {
    $options = get_option( 'qev_email_validator' );

    // Send the data to QuickEmailVerification sandbox API to get verification credit.
    $url = 'http://api.quickemailverification.com/v1/verify/sandbox?apikey=' . str_replace(' ','',$options['api_key']) . '&email=valid@example.com';

    $results = wp_remote_get($url, array('headers' => array( 'user-agent' => 'quickemailverification-wp-plugin/v1.9.0')));
    if (!is_wp_error( $results ))
 	$options['remaining_credits'] = wp_remote_retrieve_header( $results, 'x-qev-remaining-credits' );

    $remaining_credits = isset( $options['remaining_credits'] ) ? $options['remaining_credits'] : '0';
    echo $remaining_credits . '<br/>';
    echo '<input id="remaining_credits" name="qev_email_validator[date]" type="hidden" value="'. date("Y-m-d").'"/><br />';
    echo '<input id="remaining_credits" name="qev_email_validator[remaining_credits]" type="hidden" value="' . esc_attr( $remaining_credits ). '" style="margin-bottom: 5px;"/><br />';
}

function qev_results_to_exclude_setting()
{
	$options = get_option('qev_email_validator');

	$unknown = isset($options['exclude_result']['unknown']) ? $options['exclude_result']['unknown'] : '';
	$accept_all = isset($options['exclude_result']['accept_all']) ? $options['exclude_result']['accept_all'] : '';
	$role = isset($options['exclude_result']['role']) ? $options['exclude_result']['role'] : '';
	$free = isset($options['exclude_result']['free']) ? $options['exclude_result']['free'] : '';
	echo '<label for="unknown"><b>Unknown</b></label><input id="unknown" name="qev_email_validator[exclude_result][unknown]" type="checkbox"' .((!empty($unknown)) ? 'checked' : '').' style="margin: 5px;"/>';
	echo '<label for="accept_all"><b>Accept All</b></label><input id="accept_all" name="qev_email_validator[exclude_result][accept_all]" type="checkbox"' .((!empty($accept_all)) ? 'checked' : '').' style="margin: 5px;"/>';
	echo '<label for="role"><b>Role</b></label><input id="role" name="qev_email_validator[exclude_result][role]" type="checkbox"' .((!empty($role)) ? 'checked' : '').' style="margin: 5px;"/>';
	echo '<label for="free"><b>Free</b></label><input id="free" name="qev_email_validator[exclude_result][free]" type="checkbox"' .((!empty($free)) ? 'checked' : '').' style="margin: 5px;"/><br />';
	echo '<label style="color:#666;font-style:italic">This plugin will reject Invalid and Disposable email addresses by default.</label>';
}

function qev_error_message_setting()
{
	$options = get_option('qev_email_validator');

	$error_message = !empty($options['error_message']) ? $options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
	echo '<input id="error_message" name="qev_email_validator[error_message]" style="width:100%;margin-bottom: 5px;" type="text" value="' . $error_message. '" disabled="disabled"/><br />';
	echo '<label for="error_message" id="error_msg_help_text" disabled="disabled" style="color:#666;font-style:italic">This plugin is not supporting custom error message with is_email() hook.</label>';
}

function qev_is_email_hook_setting()
{
	$options = get_option('qev_email_validator');
	$is_email_hook = isset($options['is_email_hook']) ? $options['is_email_hook'] : '';
	echo '<input id="frm_is_email_hook" name="qev_email_validator[is_email_hook]" type="checkbox"' .((empty($options['contact_form']) || !empty($is_email_hook)) ? 'checked' : '').' style="margin-bottom: 5px;" onclick="disableCustomMsg();"/>';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all forms that uses is_email function()"';
}

function qev_cf7_setting()
{
	$options = get_option('qev_email_validator');
	$cf7_hook = isset($options['contact_form']['cf7_hook']) ? $options['contact_form']['cf7_hook'] : '';
	echo '<input id="frm_cf7_hook" name="qev_email_validator[contact_form][cf7_hook]" type="checkbox"' .((!empty($cf7_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Contact Form 7 Forms having an email field"';
}

function qev_ninja_forms_setting()
{
	$options = get_option('qev_email_validator');
	$ninja_form_hook = isset($options['contact_form']['ninja_form_hook']) ? $options['contact_form']['ninja_form_hook'] : '';
	echo '<input id="frm_ninja_form_hook" name="qev_email_validator[contact_form][ninja_form_hook]" type="checkbox"' .((!empty($ninja_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Ninja Forms having an email field"';
}

function qev_formidable_forms_setting()
{
	$options = get_option('qev_email_validator');
	$formidable_form_hook = isset($options['contact_form']['formidable_form_hook']) ? $options['contact_form']['formidable_form_hook'] : '';
	echo '<input id="frm_formidable_form_hook" name="qev_email_validator[contact_form][formidable_form_hook]" type="checkbox"' .((!empty($formidable_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Formidable Forms having an email field"';
}

function qev_profile_builder_forms_setting()
{
	$options = get_option('qev_email_validator');
	$pb_form_hook = isset($options['contact_form']['pb_form_hook']) ? $options['contact_form']['pb_form_hook'] : '';
	echo '<input id="frm_pb_form_hook" name="qev_email_validator[contact_form][pb_form_hook]" type="checkbox"' .((!empty($pb_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Profile Builder forms having an email field"';
}

function qev_bws_forms_setting()
{
	$options = get_option('qev_email_validator');
	$bws_form_hook = isset($options['contact_form']['bws_form_hook']) ? $options['contact_form']['bws_form_hook'] : '';
	echo '<input id="frm_bws_form_hook" name="qev_email_validator[contact_form][bws_form_hook]" type="checkbox"' .((!empty($bws_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the BWS forms having an email field"';
}

function qev_um_forms_setting()
{
	$options = get_option('qev_email_validator');
	$um_form_hook = isset($options['contact_form']['um_form_hook']) ? $options['contact_form']['um_form_hook'] : '';
	echo '<input id="frm_um_form_hook" name="qev_email_validator[contact_form][um_form_hook]" type="checkbox"' .((!empty($um_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate email address on registration page, login page and user profile page"';
}

function qev_wp_forms_setting()
{
	$options = get_option('qev_email_validator');
	$wp_form_hook = isset($options['contact_form']['wp_form_hook']) ? $options['contact_form']['wp_form_hook'] : '';
	echo '<input id="frm_wp_form_hook" name="qev_email_validator[contact_form][wp_form_hook]" type="checkbox"' .((!empty($wp_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
	echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the WP forms having an email field"';
}

function qev_wc_forms_setting()
{
        $options = get_option('qev_email_validator');
        $wc_form_hook = isset($options['contact_form']['wc_form_hook']) ? $options['contact_form']['wc_form_hook'] : '';
        echo '<input id="frm_wc_form_hook" name="qev_email_validator[contact_form][wc_form_hook]" type="checkbox"' .((!empty($wc_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
        echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate email address on checkout page and registration page"';
}

function qev_fluent_forms_setting()
{
        $options = get_option('qev_email_validator');
        $fluent_form_hook = isset($options['contact_form']['fluent_form_hook']) ? $options['contact_form']['fluent_form_hook'] : '';
        echo '<input id="frm_fluent_form_hook" name="qev_email_validator[contact_form][fluent_form_hook]" type="checkbox"' .((!empty($fluent_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
        echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Fluent forms having an email field"';
}

function qev_wp_everest_forms_setting()
{
        $options = get_option('qev_email_validator');
        $wp_everest_form_hook = isset($options['contact_form']['wp_everest_form_hook']) ? $options['contact_form']['wp_everest_form_hook'] : '';
        echo '<input id="frm_fluent_form_hook" name="qev_email_validator[contact_form][wp_everest_form_hook]" type="checkbox"' .((!empty($wp_everest_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
        echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the WPEverest forms having an email field"';
}

function qev_wp_buddypress_forms_setting()
{
    $options = get_option('qev_email_validator');
    $wp_buddypress_form_hook = isset($options['contact_form']['wp_buddypress_form_hook']) ? $options['contact_form']['wp_buddypress_form_hook'] : '';
    echo '<input id="frm_buddypress_form_hook" name="qev_email_validator[contact_form][wp_buddypress_form_hook]" type="checkbox"' .((!empty($wp_buddypress_form_hook)) ? 'checked' : ''). ' style="margin-bottom: 5px;" />';
    echo '<img src="'.plugins_url("images/info_icon.png", __FILE__).'" width="12px" height="12px" title="Validate all the Buddypress forms having an email field"';
}

function qev_debug_log_setting()
{
	$options = get_option('qev_email_validator');
	$log_on_off = isset($options['log_on_off']) ? $options['log_on_off'] : 0;
	echo '<label><input type="radio" name="qev_email_validator[log_on_off]" id="log_on_off" value=1' . (!empty($log_on_off) ? ' checked' : '') . ' /> On</label>
	<label><input type="radio" name="qev_email_validator[log_on_off]" id="log_on_off" value=0' . (empty($log_on_off) ? ' checked' : '') . ' style="margin-left: 5px;" /> Off</label><br />';
	echo '<label style="color:#666;font-style:italic">Store the QuickEmailVerification API results in a "uploads/quickemailverification/qev_API_result.log" file. It is recommended to keep it off for production.</label>';
}

function qev_admin_footer_text($footer_text) {
    $plugin_name = 'quickemailverification';
    $current_screen = get_current_screen();
    
    if ($current_screen->id == 'plugins') {
         return $footer_text . '
		<div id="qev-feedback-modal" class="hidden" style="max-width:800px">
			<span id="qev-feedback-response"></span>
			<p>
				<strong>Would you mind sharing with us the reason to deactivate the plugin?</strong>
			</p>
			<p>
				<label>
					<input type="radio" name="qev-feedback" value="1"> I no longer need the plugin
				</label>
			</p>
			<p>
				<label>
					<input type="radio" name="qev-feedback" value="2"> I couldn\'t get the plugin to work
				</label>
			</p>
			<p>
				<label>
					<input type="radio" name="qev-feedback" value="3"> The plugin doesn\'t meet my requirements
				</label>
			</p>
			<p>
				<label>
					<input type="radio" name="qev-feedback" value="4"> Other concerns
					<br><br>
					<textarea id="qev-feedback-other" style="display:none;width:100%"></textarea>
				</label>
			</p>
			<p>
				<div style="float:left">
					<input type="button" id="qev-submit-feedback-button" class="button button-danger" value="Submit & Deactivate" />
				</div>
				<div style="float:right">
					<a href="#">Skip & Deactivate</a>
				</div>
			</p>
		</div>';
    }
    
    return $footer_text;
}

function qev_submit_feedback() {
    $feedback = (isset($_POST['feedback'])) ? $_POST['feedback'] : '';
    $others = sanitize_text_field ((isset($_POST['others'])) ? $_POST['others'] : '');
    
    $options = [
        1 => "I no longer need the plugin",
        2 => "I couldn't get the plugin to work",
        3 => "The plugin doesn't meet my requirements",
        4 => "Other concerns" . (($others) ? (" - " . $others) : ""),
    ];
    
    if (isset($options[$feedback])) {
        if (!class_exists('WP_Http')) {
            include_once ABSPATH . WPINC . '/http.php';
        }
        
        $qev_options = get_option('qev_email_validator');

        $args = array('method' => 'POST',
                      'body' => array(
                        'apikey' => $qev_options['api_key'],
                	    'name' => 'quickemailverification',
                        'message' => $options[$feedback]
                	    )
	                 );

        $response = wp_remote_post('https://api.quickemailverification.com/v1/wp-plugin-feedback', $args);
    }
}

function qev_single($emailAddress,$api_key, $qev_options)
{
	try {
                $emailAddress = urlencode($emailAddress);
		// Send the data to QuickEmailVerification API Key and get verification result.
		 $url = 'http://api.quickemailverification.com/v1/verify?apikey=' . str_replace(' ','',$api_key) . '&email=' . str_replace(' ','',$emailAddress);

		// Used WordPress HTTP API method to get the result from QuickEmailVerification API.
                $results = wp_remote_get($url, array('headers' => array( 'user-agent' => 'quickemailverification-wp-plugin-'.$qev_options['form_name'].'/v1.9.0'),'timeout'=> 60));

		if (!is_wp_error( $results )) {
			$body = wp_remote_retrieve_body( $results );
			// Decode the return json results and return the data.
			$data = json_decode($body,true);

			$remaining_credits = wp_remote_retrieve_header( $results, 'x-qev-remaining-credits' );

			//update remaining_credits
			$qev_options = get_option( 'qev_email_validator' );
			$qev_options['remaining_credits'] = $remaining_credits;
			update_option('qev_email_validator', $qev_options );

			if (isset($qev_options['log_on_off']) && $qev_options['log_on_off'] == 1)
			{
				$upload_dir   = wp_upload_dir();
				$user_dirname = $upload_dir['basedir'].'/quickemailverification';

				wp_mkdir_p( $user_dirname );
				file_put_contents (  $user_dirname . '/qev_API_result.log' , var_export($data, true) . PHP_EOL, FILE_APPEND);
			}

			return $data;
		} else  // if connection error, let it pass
			return true;
	} catch(Exception $e) {
		return true;
	}
}

function qev_is_valid_email($api_result, $qev_options)
{
	if ($api_result != '') {
		if ($api_result['message'] == '') {
			if ($api_result['result'] == 'invalid' || ($api_result['result'] == 'valid' && $api_result['disposable'] == 'true') || isset($qev_options['exclude_result'])) {
				if($api_result['result'] == 'invalid' || ($api_result['result'] == 'valid' && $api_result['disposable'] == 'true'))
					return false;

				foreach ($qev_options['exclude_result'] as $exclude_res => $value) {
					if($api_result[$exclude_res] == 'true' || ($exclude_res == 'unknown' && $api_result['result'] == 'unknown'))
							return false;
				}
				return true;
			} else {
				return true;
			}
		} else {
			// If error message occured, let it pass first.
			return true;
		}
	} else {
		// If error message occured, let it pass first.
		return true;
	}
}

function qev_email_validation($email, $qev_options)
{

	$qev_validation_result = array();

	$single_result = qev_single($email, $qev_options['api_key'], $qev_options);

	$is_valid_email = $single_result != '' ? qev_is_valid_email($single_result, $qev_options) : true;

	if($is_valid_email == false) {
		$qev_validation_result['status'] = false;

		if($single_result['result'] == 'invalid' && !empty($single_result['did_you_mean']))
 			$qev_validation_result['did_you_mean'] = $single_result['did_you_mean'];

	} else {
		$qev_validation_result['status'] = true;
	}

	return $qev_validation_result;
}

function qev_is_email_validator_filter($email)
{
	$email = sanitize_email($email);
	if($email != '')
	{
		// Get option settings to know which validator is been called
		$qev_options = get_option('qev_email_validator');	
                $qev_options['form_name'] = 'is-email';

		// if wp-login.php is been called for login to dashboard, skip the check.
		if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true'|| $_SERVER['REQUEST_URI'] == '/wp-cron.php')
			return true;

		if ($qev_options['api_key'] != '' && $email != '') { 
			// do the email validation
			$validation_result = qev_email_validation($email, $qev_options);

			if (is_array($validation_result) && array_key_exists('status', $validation_result)) {
				if ($validation_result['status'] == false)
					return false;
				else					
					return true;
			} else
				return true;
		} else  // If the user do not enter the API key, or ignore the admin notice, or the $email is empty, just let it pass.
			return true;
	}
}

function qev_wpcf7_custom_email_validator_filter($result, $tags)
{
    $qev_options = get_option('qev_email_validator');
    $qev_options['form_name'] = 'cf7';
    $tags = new WPCF7_FormTag( $tags );

    $type = $tags->type;
    $name = $tags->name;

    $email = sanitize_email($_POST[$name]);

    // if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

    if (('email' == $type || 'email*' == $type) && $qev_options['api_key'] != '' && $email != '') {
    	$validation_result = qev_email_validation($email, $qev_options);

        if (is_array($validation_result) && array_key_exists('status', $validation_result)) {
            if ($validation_result['status'] == false) {
            	$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

				$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
				$result->invalidate( $tags, __($message, 'quickemailverification' ));
			}
        }
    }
    else if($email == '') {
    	$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
    	$result->invalidate( $tags, __($error_message, 'quickemailverification' ));
    }
    return $result;
}

function qev_formidable_forms_validate_email($errors, $values)
{
	// if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

	foreach ($values['item_meta'] as $key=>$value) {
		if (preg_match("/^\S+@\S+\.\S+$/", $value)) {
			$qev_options = get_option('qev_email_validator');
                        $qev_options['form_name'] = 'formidable';
			$email = sanitize_email($value);

			if ($qev_options['api_key'] != '' && $email != '') {
				$validation_result = qev_email_validation($email, $qev_options);
				if ((is_array($validation_result)) && array_key_exists('status', $validation_result)) {
					if ($validation_result['status'] == false) {
						$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

						$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
 						$errors['ct_error'] = $message;
 					}
				}
			}
		}
	}
	return $errors;
}

function qev_bws_validate_email()
{
	global $cntctfrm_error_message;
	
	// if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

	if (!empty($_POST['cntctfrm_contact_email']) && $_POST['cntctfrm_contact_email'] != '') {
		$qev_options = get_option('qev_email_validator');
		$qev_options['form_name'] = 'bws-form';
		$email = sanitize_email($_POST['cntctfrm_contact_email']);

		if ($qev_options['api_key'] != '' && $email != '') {
			$validation_result = qev_email_validation($email, $qev_options);

			if (is_array($validation_result) && array_key_exists('status', $validation_result)) {
				if ($validation_result['status'] == false) {
					$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

					$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
 					$cntctfrm_error_message['error_email'] = $message;
 				}
			}
		}
		else if($email == '') {
			$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
			$cntctfrm_error_message['error_email'] = $error_message;
		}
	}
	return $cntctfrm_error_message;
}

function qev_wppb_validate_email($message, $field, $request_data, $form_location)
{
	// if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

	if (!empty($request_data['email']) && $request_data['email'] != '') {
		$qev_options = get_option('qev_email_validator');
		$qev_options['form_name'] = 'profile-builder';
		$email = sanitize_email($request_data['email']);

		if ($qev_options['api_key'] != '' && $email != '') {
			$validation_result = qev_email_validation($email, $qev_options);
			if (is_array($validation_result) && array_key_exists('status', $validation_result)) {
				if ($validation_result['status'] == false) {
					$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

					$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
 					return __($message, 'profile-builder' );
 				}
			}
		}
	}
}

function qev_ninja_forms_validate_email($formdata)
{
	// if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

	foreach( $formdata[ 'fields' ] as $key => $field ) {
		$qev_options = get_option('qev_email_validator');
		$qev_options['form_name'] = 'ninja-form';

		$email = sanitize_email($field['value']);
		if (preg_match('/@.+\./', $email) && strpos($email, "\n") === false && strpos($email, '\n') === false) {
			$validation_result = qev_email_validation($email, $qev_options);
		    if ($validation_result['status'] == false) {
				$field_id = $field['id'];
				$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

				$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
				$formdata['errors']['fields'][$field_id] = __($message, 'quickemailverification');
			}
		}
	}
	return $formdata;
}

function qev_um_validate_email($args)
{
	remove_filter('is_email', 'qev_is_email_validator_filter');

	// if wp-login.php is been called for login to dashboard, skip the check.
    if ($_SERVER['REQUEST_URI'] == '/wp-login.php' || $_SERVER['REQUEST_URI'] == '/wp-login.php?loggedout=true')
        return true;

    if(is_array($args)) {
	    foreach($args as $key=>$value) {
			if ($key == 'user_email' || $key == 'username' || $key == 'username_b') {
				$qev_options = get_option('qev_email_validator');
				$qev_options['form_name'] = 'ultimate-member';

				$email = sanitize_email($value);

				if ($qev_options['api_key'] != '' && $email != '') {
					$validation_result = qev_email_validation($email, $qev_options);
				    if ($validation_result['status'] == false) {
						$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

						$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

						UM()->form()->add_error($key, $message);
					}
				}
			}
		}
	}
	return UM()->form();
}

function qev_wp_form_validate_email($fields, $entry, $form_data)
{
	foreach($fields as $field)
	{
		if(strtolower($field['name']) == 'email')
		{
			$form_id = $form_data['id'];
			$field_id = $field['id'];
			$email = sanitize_email($field['value']);

			$qev_options = get_option('qev_email_validator');
			$qev_options['form_name'] = 'wp-form';
			if ($qev_options['api_key'] != '' && $email != '') {

				$validation_result = qev_email_validation($email, $qev_options);

			    if ($validation_result['status'] == false) {
					$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

					$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

					wpforms()->process->errors[$form_id][$field_id] = esc_html__($message, 'wpforms-lite');
					wpforms()->process->errors[$form_id][$field_id] = esc_html__($message, 'wpforms');
				}
			}
		}
	}
	return $fields;
}

function qev_wc_validate_email_checkout($data, $errors)
{
        $email = sanitize_email($_POST['billing_email']);
        $qev_options = get_option('qev_email_validator');
  	$qev_options['form_name'] = 'woocommerce-checkout';
        if ($qev_options['api_key'] != '' && $email != '') {
                $validation_result = qev_email_validation($email, $qev_options);

                if ($validation_result['status'] == false) {
                        $error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

                        $message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

                        $errors->add( 'validation', $message);
                }
        }
        return $errors;
}

function qev_wc_validate_email_register($username, $email, $errors)
{
        $qev_options = get_option('qev_email_validator');
	$qev_options['form_name'] = 'woocommerce-register';
        if ($qev_options['api_key'] != '' && $email != '') {
                $validation_result = qev_email_validation($email, $qev_options);

                if ($validation_result['status'] == false) {
                        $error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';

                        $message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

                        $errors->add( 'validation', $message);
                }
        }
        return $errors;
}

function qev_fluent_form_validate_email($errorMessage, $field, $formData, $fields, $form)
{
	$qev_options = get_option('qev_email_validator');
	$qev_options['form_name'] = 'fluent';
	$fieldName = $field['name'];
	$email = $formData[$fieldName];

	if ($qev_options['api_key'] != '' && $email != '') {
		$validation_result = qev_email_validation($email, $qev_options);

		if ($validation_result['status'] == false) {
			$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
			$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;
			return [$message];
		}
	}

}

function qev_wp_everest_reg_form_validate_email($single_form_field, $data, $filter_hook, $form_id)
{
	$qev_options = get_option('qev_email_validator');
	$qev_options['form_name'] = 'wp_everest_reg_form';

	$email = isset( $data->value ) ? $data->value : '';

	if ($qev_options['api_key'] != '' && $email != '') {
		$validation_result = qev_email_validation($email, $qev_options);

		if ($validation_result['status'] == false) {
			$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
			$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

			add_filter( $filter_hook, function ( $msg ) use ( $message ) {
                return __( $message, 'user-registration' );
           });
		}
	}
}

function qev_wp_everest_forms_validate_email($field_id, $email, $form_data, $field_type)
{
	$qev_options = get_option('qev_email_validator');
	$qev_options['form_name'] = 'wp_everest_other_form';

	if ($qev_options['api_key'] != '' && $email != '') {
		$validation_result = qev_email_validation($email, $qev_options);

		if ($validation_result['status'] == false) {
			$error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
			$message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

			evf()->task->errors[ $form_data['id'] ][ $field_id ] = $message;
		}
	}
}

function qev_wp_buddypress_reg_validate_email() {
    $qev_options = get_option('qev_email_validator');
    $qev_options['form_name'] = 'wp_buddypress_reg_form';

    $email = sanitize_email($_POST['signup_email']);

    if ($qev_options['api_key'] != '' && $email != '') {
        $validation_result = qev_email_validation($email, $qev_options);

        if ($validation_result['status'] == false) {
            $error_message = !empty($qev_options['error_message']) ? $qev_options['error_message'] : 'The email address you entered cannot be accepted. Please enter another email address.';
            $message = isset($validation_result['did_you_mean']) ? $error_message.' Did you mean '.$validation_result['did_you_mean'].'?' : $error_message;

            // Display an error message to the user and prevent registration
            bp_core_add_message($message, 'error');
            bp_core_redirect(bp_get_signup_page());
        }
    }
}

function qev_plugin_setup()
{
	$qev_options = get_option('qev_email_validator');

	// Contact Form 7
	if (isset($qev_options['contact_form']['cf7_hook']) && is_plugin_active("contact-form-7/wp-contact-form-7.php")) {
		add_filter('wpcf7_validate_email', 'qev_wpcf7_custom_email_validator_filter',5,2); // Email field
		add_filter('wpcf7_validate_email*', 'qev_wpcf7_custom_email_validator_filter',5,2); // Req. Email field
	}

	// Ninja Form
	if (isset($qev_options['contact_form']['ninja_form_hook']) && is_plugin_active("ninja-forms/ninja-forms.php"))
	    add_filter( 'ninja_forms_submit_data', 'qev_ninja_forms_validate_email', 10, 1 );

	// Formidable Form
	if (isset($qev_options['contact_form']['formidable_form_hook']) && is_plugin_active('formidable/formidable.php'))
		add_filter('frm_validate_entry', 'qev_formidable_forms_validate_email', 1, 2);

	// Profile Builder Form
	if (isset($qev_options['contact_form']['pb_form_hook']) &&  is_plugin_active('profile-builder/index.php'))
		add_filter('wppb_check_form_field_default-e-mail', 'qev_wppb_validate_email', 11, 4);

	//contact form BWS
	if (isset($qev_options['contact_form']['bws_form_hook']) && is_plugin_active('contact-form-plugin/contact_form.php'))
		add_filter('cntctfrm_check_form', 'qev_bws_validate_email', 11);

	//Ultimate Member form
	if (isset($qev_options['contact_form']['um_form_hook']) && is_plugin_active('ultimate-member/ultimate-member.php'))
	{
		add_filter('um_submit_form_errors_hook', 'qev_um_validate_email', 10, 1);
		add_filter('um_submit_account_errors_hook', 'qev_um_validate_email', 10, 1);
		add_filter('um_reset_password_errors_hook', 'qev_um_validate_email', 10, 1);
		add_filter('um_access_profile', 'qev_um_validate_email', 10, 1);
	}
	
	//WooCommerce
        if (isset($qev_options['contact_form']['wc_form_hook']) && is_plugin_active('woocommerce/woocommerce.php'))
        {
                add_action('woocommerce_after_checkout_validation', 'qev_wc_validate_email_checkout', 20, 2 );
                add_action('woocommerce_register_post', 'qev_wc_validate_email_register', 10, 3 );
        }

	// WP Forms
	if (isset($qev_options['contact_form']['wp_form_hook']) && (is_plugin_active('wpforms-lite/wpforms.php') || is_plugin_active('wpforms/wpforms.php')))
      		add_filter('wpforms_process_after_filter', 'qev_wp_form_validate_email', 10, 3 );

        // Fluent Forms
	if (isset($qev_options['contact_form']['fluent_form_hook']) && is_plugin_active('fluentform/fluentform.php'))
      	        add_filter('fluentform_validate_input_item_input_email', 'qev_fluent_form_validate_email', 10, 5 );

	// WP Everest forms
	if (isset($qev_options['contact_form']['wp_everest_form_hook']))
	{
		// Registration form
		if(is_plugin_active('user-registration/user-registration.php'))
      		add_action('user_registration_validate_user_email', 'qev_wp_everest_reg_form_validate_email', 10, 4);
		
		// Contact us and other forms
		if(is_plugin_active('everest-forms/everest-forms.php'))
			add_action('everest_forms_process_validate_email', 'qev_wp_everest_forms_validate_email', 10, 4);
	}

	// Buddypress register form
        if (isset($qev_options['contact_form']['wp_buddypress_form_hook'])) {
                // Registration form
                if (is_plugin_active('buddypress/bp-loader.php')) {
                        add_action('bp_signup_pre_validate', 'qev_wp_buddypress_reg_validate_email',5 ,2);
                }
        }

	// Other plugins that used is_email
	if (isset($qev_options['is_email_hook']))
		add_filter('is_email', 'qev_is_email_validator_filter');
}
