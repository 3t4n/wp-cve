<?php
/*
Plugin Name: Simple Membership After Login Redirection
Version: v1.6
Plugin URI: https://simple-membership-plugin.com/
Author: smp7, wp.insider
Author URI: https://simple-membership-plugin.com/
Description: An addon for the simple membership plugin to do the after login redirection to a specific page based on the member's level.
*/

if (!defined('ABSPATH')){
    exit; //Exit if accessed directly
}

define('SWPM_ALR_CONTEXT', 'swpm_alr');

include_once('swpm-after-login-settings-menu.php');//Settings menu handling file.

add_action('swpm_after_login', 'swpm_alr_do_after_login_redirection');
add_filter('swpm_after_login_url', 'swpm_alr_after_login_url');
add_filter('swpm_get_login_link_url', 'swpm_alr_append_query_arg_if_applicable');

// The following 6 filters are used to allow the usage of the swpm_redirect_to parameter, if it is currently present in the page's URL
add_filter('swpm_after_reg_callback_login_page_url', 'swpm_alr_append_custom_redirection_if_exists');
add_filter('swpm_send_reg_email_activation_link', 'swpm_alr_append_custom_redirection_if_exists');
add_filter('swpm_register_front_end_login_page_url', 'swpm_alr_append_custom_redirection_if_exists');
add_filter('swpm_email_activation_login_page_url', 'swpm_alr_append_custom_redirection_if_exists');
add_filter('swpm_resend_activation_email_login_page_url', 'swpm_alr_append_custom_redirection_if_exists');
add_filter('swpm_after_registration_redirect_url', 'swpm_alr_override_registration_redirect_url');

if (is_admin()) {//Do admin side stuff
    add_filter('swpm_admin_add_membership_level_ui', 'swpm_alr_admin_add_membership_level_ui');
    add_filter('swpm_admin_edit_membership_level_ui', 'swpm_alr_admin_edit_membership_level_ui', 10, 2);

    add_filter('swpm_admin_add_membership_level', 'swpm_alr_admin_add_membership_level');
    add_filter('swpm_admin_edit_membership_level', 'swpm_alr_admin_edit_membership_level', 10, 2);
}

function swpm_alr_admin_add_membership_level_ui($to_filter) {
    return $to_filter . '<tr>
            <th scope="row">After Login Redirection Page</th>
            <td>
            <input type="text" class="regular-text" name="custom[swpm_alr_after_login_page_field]" value="" />
            <p class="description">Enter the URL of the page where you want members of this level to be redirected to after they login.</p>
            </td>
            </tr>';
}

function swpm_alr_admin_edit_membership_level_ui($to_filter, $id) {
    $fields = SwpmMembershipLevelCustom::get_value_by_context($id, SWPM_ALR_CONTEXT);
    $swpm_alr_after_login_page_field = isset($fields['swpm_alr_after_login_page_field']) ? $fields['swpm_alr_after_login_page_field']['meta_value'] : '';
    return $to_filter . '<tr>
            <th scope="row">After Login Redirection Page</th>
            <td>
            <input type="text" class="regular-text" name="custom[swpm_alr_after_login_page_field]" value="' . $swpm_alr_after_login_page_field . '" />
            <p class="description">Enter the URL of the page where you want members of this level to be redirected to after they login.</p>
            </td>
            </tr>';
}

function swpm_alr_admin_add_membership_level($to_filter) {
    $custom_field = $_POST['custom']['swpm_alr_after_login_page_field'];
    $field = array(
        'meta_key' => 'swpm_alr_after_login_page_field', // required
        'meta_value' => sanitize_text_field($custom_field), //required
        'meta_context' => SWPM_ALR_CONTEXT, // optional but recommended
        'meta_label' => '', // optional
        'meta_type' => 'text'// optional
    );
    $to_filter['swpm_alr_after_login_page_field'] = $field;
    return $to_filter;
}

function swpm_alr_admin_edit_membership_level($to_filter, $id) {
    $custom_field = $_POST['custom']['swpm_alr_after_login_page_field'];
    $field = array(
        'meta_key' => 'swpm_alr_after_login_page_field', // required
        'meta_value' => sanitize_text_field($custom_field), //required
        'meta_context' => SWPM_ALR_CONTEXT, // optional but recommended
        'meta_label' => '', // optional
        'meta_type' => 'text'// optional
    );
    $to_filter['swpm_alr_after_login_page_field'] = $field;
    return $to_filter;
}

function swpm_alr_do_after_login_redirection() {
    if (class_exists('SwpmLog')) {
        SwpmLog::log_simple_debug("After login redirection addon. Checking if member need to be redirected.", true);
    }

    $auth = SwpmAuth::get_instance();
    if ($auth->is_logged_in()) {
        //Member is logged in. Lets check if redirection needs to be done.

        //First check if a the swpm_redirect_to argument is set (meaning the user needs to be redirected to the last page).
        if(isset($_REQUEST['swpm_redirect_to']) && !empty($_REQUEST['swpm_redirect_to'])){
            $redirect_to = esc_url_raw(sanitize_text_field($_REQUEST['swpm_redirect_to']));
            //Redirect to the membership level specific after login page.
            if( class_exists('SwpmLog') ){
                SwpmLog::log_simple_debug("The 'swpm_redirect_to' request parameter is present. Redirecting to: " . $redirect_to, true);
            }
            wp_redirect($redirect_to);
            exit;
        }

        //Check if there is a membership level specific after login redirection
        $level = $auth->get('membership_level');
        $level_id = $level;
        $key = 'swpm_alr_after_login_page_field';
        $after_login_page_url = SwpmMembershipLevelCustom::get_value_by_key($level_id, $key);
        if (!empty($after_login_page_url)) {
            //Redirect to the membership level specific after login page.
            if( class_exists('SwpmLog') ){
                SwpmLog::log_simple_debug("After login redirection is configured in the membership level. Redirecting to: " . $after_login_page_url, true);
            }            
            wp_redirect($after_login_page_url);
            exit;
        }

        //No redirection found. So stay on the current page.
    }
}

function swpm_alr_after_login_url($url) {
    $auth = SwpmAuth::get_instance();
    if ($auth->is_logged_in()) {
        $level = $auth->get('membership_level');
        $level_id = $level;
        $key = 'swpm_alr_after_login_page_field';
        $after_login_page_url = SwpmMembershipLevelCustom::get_value_by_key($level_id, $key);
        if (!empty($after_login_page_url)) {
            return $after_login_page_url;
        }
    }
    return $url;
}

/*
 * This function will append redirect URL to the login link if the user has enabled the feature.
 * This can be used to redirect the user to the post/page they were viewing after the login (ignoring any other redirection)
 */
function swpm_alr_append_query_arg_if_applicable($login_url){

    //Check if the redirect to last page settings is enabled.
    $swpm_alr_settings = get_option('swpm_alr_settings');
    if(empty($swpm_alr_settings['redirect_to_last_page_enabled'])){
        $swpm_alr_settings['redirect_to_last_page_enabled'] = '';
    }

    if($swpm_alr_settings['redirect_to_last_page_enabled'] != '1'){
        //The redirect to last page option is disabled. No need to add the query arg.
        return $login_url;
    }

    //The feature is enabled. Lets add the necessary query arg to the login url.
    $current_url = SwpmMiscUtils::get_current_page_url();
    if(!empty($current_url)){
        //Add this URL to the redirect to query arg.
        $current_url = urlencode($current_url);
        $login_url = add_query_arg('swpm_redirect_to', $current_url, $login_url);
    }

    return $login_url;
}

/*
 * This function will append the value of $_GET['swpm_redirect_to'] to the url provided in parameter, if the user has enabled the feature.
 * This can be used to redirect the user to a custom post/page (ignoring any other redirection)
 */
function swpm_alr_append_custom_redirection_if_exists($url){

    //Check if the option 'allow_custom_redirections' is enabled or not.
    $swpm_alr_settings = get_option('swpm_alr_settings');
    if(empty($swpm_alr_settings['allow_custom_redirections'])){
        $swpm_alr_settings['allow_custom_redirections'] = '';
    }

    if($swpm_alr_settings['allow_custom_redirections'] != '1'){
        //The option 'allow_custom_redirections' is disabled. No need to edit the url.
        return $url;
    }

    // Get the $_GET['swpm_redirect_to'] parameter. If does not exist, set to 'false'.
    $swpm_redirect_to = !empty($_GET['swpm_redirect_to']) ? filter_input(INPUT_GET, 'swpm_redirect_to', FILTER_SANITIZE_ENCODED) : false;

    // If swpm_redirect_to is defined, add it the swpm_redirect_to parameter to the url variable, to allow redirecting the user to a custom URL
    if(!empty($swpm_redirect_to)){
       $url = add_query_arg('swpm_redirect_to', $swpm_redirect_to, $url);
    }

    return $url;
}

/*
 * This function will empty the redirection URL if the variable $_GET['swpm_redirect_to'] parameter is defined, and if the user has enabled the feature.
 * This is needed to allow the swpm_redirect_to parameter to take priority over some of the redirection behavior of SWPM.
 */
function swpm_alr_override_registration_redirect_url($url){

    //Check if the option 'allow_custom_redirections' is enabled or not.
    $swpm_alr_settings = get_option('swpm_alr_settings');
    if(empty($swpm_alr_settings['allow_custom_redirections'])){
        $swpm_alr_settings['allow_custom_redirections'] = '';
    }

    if($swpm_alr_settings['allow_custom_redirections'] != '1'){
        //The option 'allow_custom_redirections' is disabled. No need to edit the url.
        return $url;
    }

    // Get the $_GET['swpm_redirect_to'] parameter. If does not exist, set to 'false'.
    $swpm_redirect_to = !empty($_GET['swpm_redirect_to']) ? filter_input(INPUT_GET, 'swpm_redirect_to', FILTER_SANITIZE_ENCODED) : false;

    // If $_GET['swpm_redirect_to'] is not defined, keep the existing URL
    if(empty($swpm_redirect_to)) { return $url; }
    // But if $_GET['swpm_redirect_to'] is defined, empty the existing redirect_to URL to allow redirection override.
    else { return ''; }
}
