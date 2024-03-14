<?php
/**
 * Various WP hooks
 *
 * @author     Rublon Developers http://www.rublon.com
 * @copyright  Rublon Developers http://www.rublon.com
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */

use Rublon\Core\Exceptions\RublonException;
use Rublon_WordPress\Libs\Classes\RublonRolesProtection;
use Rublon_WordPress\Libs\RublonImplemented\RublonAPICheckProtection;

/**
 * Display Rublon messages on the login screen
 *
 * @param array $message WP message displayed on the login screen
 * @return void
 */
function rublon2factor_login_message($message)
{

    $messages = RublonHelper::getMessages();
    if (!empty($message)) {
        $result = $message;
    } else {
        $result = '';
    }
    if (!empty($messages)) {
        $wpVersion = get_bloginfo('version');
        if (version_compare($wpVersion, '3.8', 'ge')) {
            $result .= RublonHelper::transformMessagesToVersion($messages);
        } else {
            $result .= RublonHelper::transformMessagesToVersion($messages, '3.7');
        }
    }
    return $result;

}

add_filter('login_message', 'rublon2factor_login_message');

/**
 * Transfers any plugin messages back to the cookie on redirection
 *
 * @param string $location
 * @param int $status
 * @return string
 */
function rublon2factor_wp_redirect($location, $status = 302)
{

    RublonHelper::cookieTransferBack();
    return $location;

}

add_filter('wp_redirect', 'rublon2factor_wp_redirect');


/**
 * Save the page the user needs to be redirected to after a successful authentication
 *
 * @param string $redirect_to
 * @return string
 */
function rublon2factor_login_redirect($redirect_to)
{

    if (!empty($redirect_to)) {
        RublonCookies::storeReturnURL($redirect_to);
        Rublon_Transients::setTransient('rublon_login_redirect', $redirect_to);
    }
    return $redirect_to;

}

add_filter('login_redirect', 'rublon2factor_login_redirect', 10, 3);


/**
 * Makes sure the  is always run before other plugins
 *
 * @return void
 */
function rublon2factor_plugin_activated_mefirst()
{

    RublonHelper::meFirst();

}

add_action('activated_plugin', 'rublon2factor_plugin_activated_mefirst');


function rublon2factor_authenticate($user, $username, $password)
{

    if (is_email($username)) {
        $temp_user = get_user_by('email', $username);
        $username = $temp_user->user_login;
    }

    $user = wp_authenticate_username_password($user, $username, $password);

    if (is_wp_error($user)) {
        return $user;
    } else {
        do_action('rublon_pre_authenticate', $user);
        $user_id = RublonHelper::getUserId($user);

        /*
        $site_login_url = null;
        if (!is_user_member_of_blog($user_id) && RublonMultisiteHelper::isMultisite()) {
            $userBlogs = get_blogs_of_user($user_id, true);
            if (!empty($userBlogs)) {
                $site_login_url = get_site_url($userBlogs[1]->userblog_id, 'wp-login.php', 'login');
            }
        }
        */

        if ((is_user_member_of_blog($user_id) || (RublonMultisiteHelper::isMultisite() && !empty($site_login_url))) &&
            RublonHelper::isSiteRegistered() && !RublonHelper::isXMLRPCEnabled()) {

            RublonHelper::my_wp_logout();
            $remember = !empty($_POST['rememberme']);
            $authURL = RublonHelper::authenticateWithRublon($user, $remember, null); //, $site_login_url
            if (empty($authURL)) {

                if (RublonHelper::canShowBusinessEditionUpgradeBoxAfterLogin($user)) {
                    RublonHelper::setMessage('BUSINESS_EDITION_UPGRADE_BOX', 'updated', 'RC');
                }

                $levels = RublonRolesProtection::getProtectionTypesLevels();
                if ($levels[RublonHelper::getUserProtectionType()] >= $levels[RublonHelper::PROTECTION_TYPE_MOBILE]) {
                    $user_email = RublonHelper::getUserEmail($user);
                    $obfuscated_email = RublonHelper::obfuscateEmail($user_email);
                    RublonHelper::setMessage('ROLE_BLOCKED|' . base64_encode($obfuscated_email), 'error', 'LM');
                    $return_page = RublonHelper::getReturnPage();
                    wp_safe_redirect(wp_login_url($return_page));
                    exit();
                } else {
                    RublonHelper::setMobileUserStatus($user, RublonHelper::NO);
                    return $user;
                }
            } else {
                RublonHelper::setLoginToken($user);
                wp_redirect($authURL);
                exit();
            }
        } else {

            if (!is_user_member_of_blog($user_id) && RublonMultisiteHelper::isMultisite()) {
                RublonHelper::setMessage('NETWORK_USER_NOT_BELONGS_TO_BLOG', 'error', 'RC');
                RublonHelper::my_wp_logout();
                wp_safe_redirect(wp_login_url());
                exit;
            }

            return $user;
        }
    }
}

remove_filter('authenticate', 'wp_authenticate_username_password', 20);
add_filter('authenticate', 'rublon2factor_authenticate', 10, 3);


/**
 * Main initialization hook
 *
 * Check if we should start the 2FA authentication or just retrieve the
 * plugin cookies and display current page.
 *
 * @return void
 */
function rublon2factor_init()
{

    RublonHelper::cookieTransfer();

}

add_action('init', 'rublon2factor_init');

function rublon2factor_login_init()
{

    RublonHelper::checkForActions(RublonHelper::PAGE_LOGIN);

}

add_action('login_init', 'rublon2factor_login_init');

/**
 * Store WP authentication cookie params in the settings for future use
 *
 * Since the plugin needs to duplicate the WP cookie settings, we need to
 * store them for future use, as on this stage we do not authenticate
 * the user with the second factor yet - this will happen in a moment.
 *
 * @param string $auth_cookie Set cookie
 * @param int $expire Whether the cookie is a session or permanent one
 * @param int $expiration Expiration date (timestamp)
 * @param id $user_id Logged in user's WP ID
 * @param string $scheme Whether the cookie is secure
 * @return void
 */
function rublon2factor_store_auth_cookie_params($auth_cookie, $expire, $expiration, $user_id = null, $scheme)
{

    // Deprecated?
    if ($user_id) {
        $user = get_user_by('id', $user_id);
        $secure = ($scheme == 'secure_auth');
        if ($user) {
            $secure_logged_in_cookie = apply_filters('secure_logged_in_cookie', false, $user_id, $secure);
            $cookieParams = array(
                'secure' => $secure,
                'remember' => ($expire > 0),
                'logged_in_secure' => $secure_logged_in_cookie,
            );
            $settings = RublonHelper::getSettings();
            $settings['wp_cookie_params'] = $cookieParams;
            $settings['wp_cookie_expiration'] = array(
                'expire' => $expire,
                'expiration' => $expiration,
            );
            RublonHelper::saveSettings($settings);
        }
        $flag = RublonHelper::flag(
            $user,
            RublonHelper::TRANSIENT_FLAG_UPDATE_AUTH_COOKIE
        );
        if ($flag === RublonHelper::YES) {
            RublonCookies::setAuthCookie($user);
        }
    }

}

add_action('set_auth_cookie', 'rublon2factor_store_auth_cookie_params', 10, 5);


/**
 * Clear Rublon auth cookie on logout
 *
 * @return void
 */
function rublon2factor_wp_logout()
{

    RublonCookies::clearAuthCookie();

}

add_action('wp_logout', 'rublon2factor_wp_logout');


/**
 * Perform any post-login operations
 *
 * Checks if the user has been protected by an earlier
 * version of the Rublon plugin
 *
 * @param string $user_login
 * @param WP_User $user
 */
function rublon2factor_wp_login($user_login, $user)
{

    if (RublonHelper::isUserSecured($user) && !RublonHelper::isUserAuthenticated($user)) {
        $msg_meta = get_user_meta(RublonHelper::getUserId($user), RublonHelper::RUBLON_META_AUTH_CHANGED_MSG, true);
        if ($msg_meta === '') {
            $msg_meta = -1;
        } else {
            $msg_meta = (int)$msg_meta;
        }
        if ($msg_meta > 8) {
            delete_user_meta(RublonHelper::getUserId($user), RublonHelper::RUBLON_META_AUTH_CHANGED_MSG);
            RublonHelper::disconnectRublon2Factor($user);
        } else {
            $msg_meta++;
            if ($msg_meta % 3 == 0) {
                RublonHelper::setMessage('AUTHENTICATION_TYPE_CHANGED', 'updated', 'POSTL');
            }
            update_user_meta(RublonHelper::getUserId($user), RublonHelper::RUBLON_META_AUTH_CHANGED_MSG, $msg_meta);
        }
    }

}

add_action('wp_login', 'rublon2factor_wp_login', 10, 2);

/**
 *
 * @deprecated
 * @TODO remove function
 * @param unknown $errors
 * @param unknown $update
 * @param unknown $user
 */
function rublon2factor_user_profile_update_errors(&$errors, $update, &$user)
{

    global $pagenow;

    if (RublonHelper::isSiteRegistered()) {
        $current_user = wp_get_current_user();
        $current_user_id = RublonHelper::getUserId($current_user);
        $updated_user_id = (!empty($user->ID)) ? $user->ID : $user->Id;

        if ($pagenow == RublonHelper::WP_PROFILE_PAGE
            && $current_user_id == $updated_user_id
            && empty($errors->errors)
            && $update) {
            if (!empty($_POST)) {
                $post = $_POST;
                RublonHelper::checkPostDataProfileUpdate($post);
            }
        }
    }

}

// add_action('user_profile_update_errors', 'rublon2factor_user_profile_update_errors', 10, 3);

function rublon2factor_update_field_additional_settings($new_value, $old_value)
{

    global $pagenow;

    if ($pagenow == 'options.php' && is_array($new_value) && !empty($_POST)) {
        $post = $_POST;
        $update = RublonHelper::checkPostDataAddSettUpdate($post, $new_value, $old_value);
        if (!empty($new_value['rublon_system_token']) && !empty($new_value['rublon_secret_key'])) {
            RublonHelper::checkApplicationAfterSetup($new_value['rublon_system_token'], $new_value['rublon_secret_key']);
        }
        return $update;
    } else {
        return $new_value;
    }

}

add_filter('pre_update_option_rublon2factor_settings', 'rublon2factor_update_field_additional_settings', 10, 2);

function rublon2factor_user_new_form()
{

    $roles = get_editable_roles();
    $roles_js = array();
    $roles_js['protection_levels'] = array();
    foreach ($roles as $role_name => $role) {
        $role_id = RublonHelper::prepareRoleId(esc_attr($role_name));
        $settings = RublonHelper::getSettings('additional');
        $role_protection_type = !empty($settings[$role_id]) ? $settings[$role_id] : '';
        switch ($role_protection_type) {
            case RublonHelper::PROTECTION_TYPE_MOBILE:
                $role_protection_level = 2;
                break;
            case RublonHelper::PROTECTION_TYPE_EMAIL:
                $role_protection_level = 1;
                break;
            default:
                $role_protection_level = 0;
        }
        $roles_js['protectionLevels'][esc_attr($role_name)] = $role_protection_level;
    }
    echo '<label class="hidden rublon-label rublon-label-newuserrole" for="rublon-newuserrole-dropdown">';
    echo '	<div class="rublon-lock-container rublon-locked-container rublon-newuserrole-locked"><img class="rublon-lock rublon-locked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/locked.png" /></div>';
    echo '</label>';
}

add_action('user_new_form', 'rublon2factor_user_new_form');


/**
 * Perform tasks after WP is fully loaded
 *
 * Plugin registration in the Rublon API moved here, as
 * too early initialization conflicted with some plugins.
 */
function rublon2factor_wp_loaded()
{

    // Check if plugin registration is in effect
    RublonHelper::checkForActions(RublonHelper::PAGE_WP_LOADED);

}

add_action('wp_loaded', 'rublon2factor_wp_loaded');

function rublon2factor_dismiss_api_registration()
{

    $post = $_POST;
    $other_settings = RublonHelper::getSettings('other');
    if (!empty($post['nonce']) && wp_verify_nonce($post['nonce'], Rublon_Pointers::API_REGISTRATION_DISMISSED)) {
        $other_settings[Rublon_Pointers::API_REGISTRATION_DISMISSED] = RublonHelper::YES;
        $current_user = wp_get_current_user();
        if (!empty($post['newsletter_signup']) && $post['newsletter_signup'] == 'true') {
            $other_settings['newsletter_signup'] = array(RublonHelper::getUserEmail($current_user));
        }
        RublonHelper::saveSettings($other_settings, 'other');
    }

}

add_action('wp_ajax_' . Rublon_Pointers::AJAX_API_REGISTRATION_ACTION, 'rublon2factor_dismiss_api_registration');



/**
 * Show box regarding the Business Edition upgrade
 */
function businessEditionUpgrade()
{
    if (RublonHelper::canShowBusinessEditionUpgradeBox()) {
        echo RublonHelper::showUpgradeBox('wide');
    }
}

add_action('admin_notices', 'businessEditionUpgrade');


function hide_business_edition_upgrade_box()
{
    $userId = $_POST['data'];
    if (!RublonHelper::saveHideBusinessEditionUpgradeBox($userId)) {
        echo __('Error');
    } else {
        echo __('Saved');
    }
}

add_action('wp_ajax_hide_business_edition_upgrade_box', 'hide_business_edition_upgrade_box');

function disable_tml_ajax($form_name, $form)
{
    $form->remove_attribute('data-ajax');
}

add_action('tml_registered_form', 'disable_tml_ajax', 10, 2);
