<?php
if (!defined('ABSPATH'))
    die('Restricted Access');


// wrong username password handling
add_action('wp_login_failed', 'MJTC_login_failed', 10, 2);
function MJTC_login_failed($username)
{
    $referrer = wp_get_referer();
    if ($referrer && !MJTC_majesticsupportphplib::MJTC_strstr($referrer, 'wp-login') && !MJTC_majesticsupportphplib::MJTC_strstr($referrer, 'wp-admin')) {
        if (isset($_POST['wp-submit'])) {
            MJTC_message::MJTC_setMessage(esc_html(__('Username / password is incorrect', 'majestic-support')), 'error');
            wp_redirect(majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'login', 'mspageid' => majesticsupport::getPageid())));
            exit;
        } else {
            return;
        }
    }
}

// Updates authentication to return an error when one field or both are blank
add_filter('authenticate', 'MJTC_authenticate_username_password', 30, 3);

function MJTC_authenticate_username_password($user, $username, $password)
{
    if (is_a($user, 'WP_User')) {
        return $user;
    }
    if (isset($_POST['wp-submit']) && (empty($_POST['pwd']) || empty($_POST['log']))) {
        return false;
    }
    return $user;
}

// ------------------- ms registrationFrom request handler--------
// register a new user
function MJTC_add_new_member()
{
    if (isset($_POST["ms_user_login"]) && wp_verify_nonce($_POST['ms_support_register_nonce'], 'ms-support-register-nonce')) {
        $user_login = majesticsupport::MJTC_sanitizeData($_POST["ms_user_login"]);// MJTC_sanitizeData() function uses wordpress santize functions
        $user_email = sanitize_email($_POST["ms_user_email"]);
        $user_first = sanitize_text_field($_POST["ms_user_first"]);
        $user_last = sanitize_text_field($_POST["ms_user_last"]);
        $user_pass = sanitize_text_field($_POST["ms_user_pass"]);
        $pass_confirm = sanitize_text_field($_POST["ms_user_pass_confirm"]);

        // this is required for username checks
        // require_once(ABSPATH . WPINC . '/registration.php');

        if (username_exists($user_login)) {
            // Username already registered
            MJTC_errors()->add('username_unavailable', esc_html(__('Username already taken', 'majestic-support')));
        }
        if (!validate_username($user_login)) {
            // invalid username
            MJTC_errors()->add('username_invalid', esc_html(__('Invalid username', 'majestic-support')));
        }
        if ($user_login == '') {
            // empty username
            MJTC_errors()->add('username_empty', esc_html(__('Please enter a username', 'majestic-support')));
        }
        if (!is_email($user_email)) {
            //invalid email
            MJTC_errors()->add('email_invalid', esc_html(__('Invalid email', 'majestic-support')));
        }
        if (email_exists($user_email)) {
            //Email address already registered
            MJTC_errors()->add('email_used', esc_html(__('Email already registered', 'majestic-support')));
        }
        if ($user_pass == '') {
            // passwords do not match
            MJTC_errors()->add('password_empty', esc_html(__('Please enter a password', 'majestic-support')));
        }
        if ($user_pass != $pass_confirm) {
            // passwords do not match
            MJTC_errors()->add('password_mismatch', esc_html(__('Passwords do not match', 'majestic-support')));
        }
        if (majesticsupport::$_config['captcha_on_registration'] == 1) {
            if (majesticsupport::$_config['captcha_selection'] == 1) { // Google recaptcha
                $gresponse = majesticsupport::MJTC_sanitizeData($_POST['g-recaptcha-response']);// MJTC_sanitizeData() function uses wordpress santize functions
                $resp = MJTC_googleRecaptchaHTTPPost(majesticsupport::$_config['recaptcha_privatekey'], $gresponse);
                if (!$resp) {
                    MJTC_errors()->add('invalid_captcha', esc_html(__('Invalid captcha', 'majestic-support')));
                }
            } else { // own captcha
                $captcha = new MJTC_captcha;
                $result = $captcha->MJTC_checkCaptchaUserForm();
                if ($result != 1) {
                    MJTC_errors()->add('invalid_captcha', esc_html(__('Invalid captcha', 'majestic-support')));
                }
            }
        }


        $errors = MJTC_errors()->get_error_messages();

        // only create the user in if there are no errors
        if (empty($errors)) {
            // handled for useroptions addon
            $default_role = majesticsupport::$_config['wp_default_role'];
            if ($default_role == 0) {
                $default_role = 'subscriber';
            }

            $wperrors = register_new_user($user_login, $user_email);
            $new_user_id = "";
            if (!is_wp_error($wperrors)) {
                $new_user_id = $wperrors;
                wp_set_password($user_pass, $new_user_id);
                update_user_option($new_user_id, 'first_name', $user_first, true);
                update_user_option($new_user_id, 'last_name', $user_last, true);
                MJTC_message::MJTC_setMessage(esc_html(__("User has been successfully registered", 'majestic-support')), 'updated');
            } else {
                //Something's wrong
                MJTC_errors()->add('email_invalid', majesticsupport::MJTC_getVarValue($wperrors->get_error_message()));
            }
            if ($new_user_id) {

                $row = MJTC_includer::MJTC_getTable('users');
                $data['id'] = '';
                $data['wpuid'] = $new_user_id;
                $data['display_name'] = $user_first . ' ' . $user_last;
                $data['name'] = $user_login;
                $data['user_email'] = $user_email;
                $data['issocial'] = 0;
                $data['socialid'] = null;
                $data['status'] = 1;
                $data['autogenerated'] = 0;
                $row->bind($data);
                $row->store();

                //mailchimp subscribe for newsletter
                if (in_array('mailchimp', majesticsupport::$_active_addons)) {
                    if (isset($_POST['ms_mailchimp_subscribe']) && $_POST['ms_mailchimp_subscribe'] == 1) {
                        $res = MJTC_includer::MJTC_getModel('mailchimp')->subscribe($user_email, $user_first, $user_last);
                        if (!$res) {
                            MJTC_message::MJTC_setMessage(esc_html(__("Could not subscribe to the newsletter", 'majestic-support')), 'error');
                        } else {
                            $dboptin = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('mailchimp_double_optin');
                            if ($dboptin == 1) {
                                MJTC_message::MJTC_setMessage(esc_html(__("Please check confirmation email to complete your subscription for the newsletter", 'majestic-support')), 'updated');
                            } else {
                                MJTC_message::MJTC_setMessage(esc_html(__("You have successfully subscribed to the newsletter", 'majestic-support')), 'updated');
                            }
                        }
                    }
                }


                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);
                // log the new user in
                wp_set_current_user($new_user_id, $user_login);
                wp_set_auth_cookie($new_user_id);
                $url = majesticsupport::makeUrl(array('mjsmod' => 'majesticsupport', 'mjslay' => 'controlpanel', 'mspageid' => majesticsupport::getPageid()));
                // send the newly created user to the home page after logging them in
                wp_redirect($url);
                exit;
            }
        }
    }
}

add_action('init', 'MJTC_add_new_member');

// used for tracking error messages
function MJTC_errors()
{
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function MJTC_show_error_messages()
{
    if ($codes = MJTC_errors()->get_error_codes()) {
        $html = '<div class="MJTC_errors">';
        // Loop error codes and display errors
        foreach ($codes as $code) {
            $message = MJTC_errors()->get_error_message($code);
            $html .= '<span class="error"><strong>' . esc_html(__('Error','majestic-support')) . '</strong>: ' . wp_kses($message, MJTC_ALLOWED_TAGS) . '</span><br/>';
        }
        $html .= '</div>';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }
}

//to give signature option for admin
add_action('show_user_profile', 'MJTC_add_admin_signature_field');
add_action('edit_user_profile', 'MJTC_add_admin_signature_field');
function MJTC_add_admin_signature_field($user)
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <h2><?php echo esc_html(__("Majestic Support", 'majestic-support')); ?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label id="mssignature"><?php echo esc_html(__("Signature", 'majestic-support')); ?></label>
            </th>
            <td>
                <?php wp_editor(get_user_meta($user->ID, 'ms_signature', true), 'ms_signature', array('media_buttons' => false)); ?>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'MJTC_save_admin_signature_field');
add_action('edit_user_profile_update', 'MJTC_save_admin_signature_field');
function MJTC_save_admin_signature_field($uid)
{
    $nonce = majesticsupport::$_data['sanitized_args']['_wpnonce'];
    if (! wp_verify_nonce( $nonce, 'VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE') ) {
        die( 'Security check Failed' );
    }
    if (!is_numeric($uid) || !current_user_can('manage_options')) {
        return;
    }
    $signature = MJTC_includer::MJTC_getModel('majesticsupport')->getSanitizedEditorData($_POST['ms_signature']);
    update_user_meta($uid, 'ms_signature', $signature);
}

// ---------------Remove wp user ---------------

function MJTC_remove_user($user_id)
{
    $mjtc_class = MJTC_includer::MJTC_getObjectClass('user');
    $userid = $mjtc_class->MJTC_getUserIDByWPUid($user_id);

    if (isset($_POST['delete_option']) and $_POST['delete_option'] == 'delete') {

        $row = MJTC_includer::MJTC_getTable('users');
        $data['id'] = $userid;
        $data['wpuid'] = 0;
        $data['status'] = 0;
        $row->bind($data);
        $row->store();
    }
}

add_action('delete_user', 'MJTC_remove_user');

add_action('personal_options_update', 'MJTC_update_user_profile');


function MJTC_update_user_profile($user_id)
{

    $nonce = majesticsupport::$_data['sanitized_args']['_wpnonce'];
    if (! wp_verify_nonce( $nonce, 'VERIFY-MAJESTIC-SUPPORT-INTERNAL-NONCE') ) {
        die( 'Security check Failed' );
    }
    $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "users` WHERE id = " . esc_sql($user_id);
    $user = majesticsupport::$_db->get_row($query);

    $uid = "";
	$post_user_id = '';
	$id = '';
	$post_user_login='';
    $post_display_name='';
	$post_nickname='';
	
	if(isset($_POST['user_id'])) $post_user_id = majesticsupport::MJTC_sanitizeData($_POST['user_id']);// MJTC_sanitizeData() function uses wordpress santize functions
    if ($post_user_id == $user_id) {
        $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_users` WHERE wpuid = " . esc_sql($user_id);
        $id = majesticsupport::$_db->get_var($query);
    }
	$name = "";
	if(isset($_POST['first_name'])) $name = majesticsupport::MJTC_sanitizeData($_POST['first_name']);// MJTC_sanitizeData() function uses wordpress santize functions
	if(isset($_POST['last_name'])) $name = $name. ' ' . esc_html(majesticsupport::MJTC_sanitizeData($_POST['last_name']));// MJTC_sanitizeData() function uses wordpress santize functions
	if(isset($_POST['user_login'])) $post_user_login = majesticsupport::MJTC_sanitizeData($_POST['user_login']);// MJTC_sanitizeData() function uses wordpress santize functions
    if(isset($_POST['display_name'])) $post_display_name = majesticsupport::MJTC_sanitizeData($_POST['display_name']);// MJTC_sanitizeData() function uses wordpress santize functions
	if(isset($_POST['nickname'])) $post_nickname = majesticsupport::MJTC_sanitizeData($_POST['nickname']);// MJTC_sanitizeData() function uses wordpress santize functions
	
	if (isset($_POST['email'])) {
		$row = MJTC_includer::MJTC_getTable('users');
		$data['id'] = $id;
		$data['wpuid'] = $user_id;
		$data['name'] = $post_user_login;
		$data['display_name'] = $name;
		$data['user_nicename'] = $post_nickname;
		$data['user_email'] = majesticsupport::MJTC_sanitizeData($_POST['email']);// MJTC_sanitizeData() function uses wordpress santize functions
		$data['issocial'] = 0;
		$data['socialid'] = null;
		$data['status'] = 1;
		$data['created'] = date_i18n('Y-m-d H:i:s');
		$row->bind($data);
		$row->store();
	}
}

add_action('edit_user_profile_update', 'MJTC_update_user_profile');
add_action('user_register', 'MJTC_update_user_profile'); // creating a new user


?>
