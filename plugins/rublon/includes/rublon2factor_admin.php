<?php

/**
 * Code to be run while on administrator panel pages
 *
 * @author     Rublon Developers http://www.rublon.com
 * @copyright  Rublon Developers http://www.rublon.com
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */

use Rublon_WordPress\Libs\Classes\Features\RublonFeature;
use Rublon_WordPress\Libs\Classes\RublonRolesProtection;
use Rublon_WordPress\Libs\RublonImplemented\Rublon2FactorGUIWordPress;

/**
 * Add plugin css
 **/
function rublon2factor_admin_css()
{
    $currentPluginVersion = RublonHelper::getCurrentPluginVersion();

    // check if the site is running WordPress 3.8+, which brought
    // some visual style changes
    $wp_version = get_bloginfo('version');
    $addCompatStyles = false;
    if (version_compare($wp_version, '3.8', 'ge')) {
        $addCompatStyles = true;
    }

    $addCompatStyleV4_4 = false;
    if (version_compare($wp_version, '4.4', 'ge')) {
        $addCompatStyleV4_4 = true;
    }

    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $addSafari = (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false);

    if (!wp_style_is('rublon2factor_admin_css', 'registered')) {
        wp_register_style('rublon2factor_admin_css', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_admin.css', false, $currentPluginVersion);
        if ($addSafari) {
            wp_register_style('rublon2factor_admin_safari_css', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_admin_safari.css', false, $currentPluginVersion);
        }
        if ($addCompatStyles) {
            wp_register_style('rublon2factor_admin_wp_3.8_plus_css', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_admin_wp_3.8_plus.css', false, $currentPluginVersion);
        }
        if ($addCompatStyleV4_4) {
            wp_register_style('rublon2factor_admin_wp_4.4_plus_css', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_admin_wp_4.4_plus.css', false, $currentPluginVersion);
        }
    }

    if (did_action('wp_print_styles')) {
        wp_print_styles('rublon2factor_admin_css');
        if ($addSafari) {
            wp_print_styles('rublon2factor_admin_safari_css');
        }
        if ($addCompatStyles) {
            wp_print_styles('rublon2factor_admin_wp_3.8_plus_css');
        }
    } else {
        wp_enqueue_style('rublon2factor_admin_css');
        if ($addSafari) {
            wp_enqueue_style('rublon2factor_admin_safari_css');
        }
        if ($addCompatStyles) {
            wp_enqueue_style('rublon2factor_admin_wp_3.8_plus_css');
        }
        if ($addCompatStyleV4_4) {
            wp_enqueue_style('rublon2factor_admin_wp_4.4_plus_css');
        }
    }
}

add_action('admin_print_styles', 'rublon2factor_admin_css');

/**
 * Create Rublon page in the menu
 */
function rublon2factor_add_menu_entries()
{

    $main_title = __('Rublon MFA', 'rublon');
    add_menu_page($main_title, $main_title, 'read', 'rublon' , 'rublon2factor_render_settings_page', RUBLON2FACTOR_PLUGIN_URL . '/assets/images/icon.svg');

    $general_title = __('Settings', 'rublon');
    add_submenu_page('rublon', 'Rublon: ' . $general_title, $general_title, 'read', 'rublon', 'rublon2factor_render_settings_page');

    if (!RublonHelper::isNewVersion() && RublonHelper::isSiteRegistered()) {

        if (RublonHelper::canShowTDMWidget()) {
            $trusted_title = __('Trusted Devices', 'rublon');
            add_submenu_page('rublon', 'Rublon: ' . $trusted_title, $trusted_title, 'read', 'rublon_tdm', 'rublon2factor_render_tdm_page');

            $current_user = wp_get_current_user();
            if (RublonHelper::canShowACM() && RublonHelper::isUserAuthenticated($current_user)) {
                $acm_title = __('Account Sharing', 'rublon');
                add_submenu_page('rublon', 'Rublon: ' . $acm_title, $acm_title, 'read', 'rublon_acm', 'rublon2factor_render_acm_page');
            }
        }

    }

}

add_action('admin_menu', 'rublon2factor_add_menu_entries');

/**
 * Add plugin JS to the administrator pages
 *
 */
function rublon2factor_admin_scripts()
{
    $currentPluginVersion = RublonHelper::getCurrentPluginVersion();

    wp_enqueue_script('rublon_admin_js', RUBLON2FACTOR_PLUGIN_URL . '/assets/js/rublon-wordpress-admin.js', false, $currentPluginVersion);
    if (!RublonHelper::isSiteRegistered() || RublonHelper::isTrackingAllowed() === null) {
        wp_enqueue_script('rublon_admin_pointers_js', RUBLON2FACTOR_PLUGIN_URL . '/assets/js/rublon-wordpress-admin-pointers.js', 'rublon_admin_js', $currentPluginVersion);
    }
}

add_action('admin_enqueue_scripts', 'rublon2factor_admin_scripts');

/**
 * Register plugin settings and redirect to plugin page if this is the first activation
 *
 */
function rublon2factor_register_settings()
{
    if (!RublonHelper::isNewVersion()) {
        return;
    }

    // register main settings
    register_setting('rublon2factor_settings_group', RublonHelper::RUBLON_SETTINGS_KEY);

    add_settings_section('rublon2factor-settings', __('General', 'rublon'), 'rublon2factor_render_settings', 'rublon_api');
    add_settings_field('rublon2factor_api_credentials', __('Rublon API', 'rublon'), 'rublon2factor_render_api_credentials', 'rublon_api', 'rublon2factor-settings');

    //add_settings_section('rublon2factor-other-settings', __('Other settings', 'rublon'), 'rublon2factor_render_other_settings', 'rublon');
    add_settings_field('rublon2factor_disable_xmlrpc', __('XML-RPC', 'rublon'), 'rublon2factor_render_disable_xmlrpc', 'rublon_api', 'rublon2factor-settings');

    register_setting('rublon2factor_confirmations_settings_group', RublonHelper::RUBLON_CONFIRMATIONS_SETTINGS_KEY);
    add_settings_section('rublon2factor-confirmations-settings', __('Confirmations', 'rublon'), 'rublon2factor_render_confirmations_settings', 'rublon_confirmations');
    add_settings_field('rublon2factor_confirmations', __('Confirmations', 'rublon'), 'rublon2factor_render_confirmations', 'rublon_confirmations', 'rublon2factor-confirmations-settings');

    if (RublonHelper::canPluginAttemptRegistration() || RublonHelper::isSiteRegistered()) {
        if (RublonHelper::isTrackingAllowed() === null) {
            require_once dirname(__FILE__) . '/Libs/Classes/class-rublon-pointers.php';
            add_action('admin_enqueue_scripts', array('Rublon_Pointers', 'getInstance'));
        } elseif (!RublonHelper::isSiteRegistered()) {
            require_once dirname(__FILE__) . '/Libs/Classes/class-rublon-pointers.php';
            add_action('admin_enqueue_scripts', array('Rublon_Pointers', 'getInstance'));
        }
    }

    do_action('rublon_admin_init');
}

add_action('admin_init', 'rublon2factor_register_settings');

function rublon2factor_render_additional_settings()
{

}

function rublon2factor_render_settings()
{

}

function rublon2factor_render_confirmations_settings()
{

}


function rublon2factor_render_confirmations()
{
    echo '<p class="rublon-settings-desc">' . __('Choose operations which will be protected by the additional confirmation using Rublon.', 'rublon') . '</p>';
}


function rublon2factor_render_other_settings()
{

}


/**
 * Render "Email-based identity confirmation" settings.
 *
 * Callback for rendering the "Email-based identity confirmation"
 *  section settings on the plugin's page.
 *
 * @return void
 */
function rublon2factor_render_protection_types()
{

    echo '<p class="rublon-settings-desc">' . __('Every user is protected via email by default. You can turn this off for selected roles. For more security, you can also require selected roles to use the Rublon mobile app.', 'rublon') . '</p>';
    echo '<p class="rublon-settings-desc"><strong>' . __('Notice:', 'rublon') . ' </strong>'
        . __('Users of the Rublon mobile app are always protected, regardless of this setting. Users with no minimum protection can turn on protection via email themselves in their profile. Users with any level of minimum protection cannot turn it off.', 'rublon') . '</p>';

    $settings = RublonHelper::getSettings('additional');
    // Retrieve the roles used on this site.
    $roles = RublonHelper::getUserRoles();
    $role_ids = array();
    echo '<div class="rublon-settings-setting-name">';
    echo '  <div class="rublon-settings-setting-label"></div>';
    echo '  <div class="rublon-setting-header"><strong>' . __('Minimum Protection', 'rublon') . '</strong></div>';
    echo '</div>';
    foreach ($roles as $role) {
        // Prepare role IDs used as the option keys.
        $role_id = RublonHelper::prepareRoleId($role);
        $role_ids[] = '\'' . $role_id . '\'';
        if (!empty($settings[$role_id])) {
            $emailSelected = '';
            $noneSelected = '';
            $lock1Visibility = '';
            $lock2Visibility = '';
            $lock3Visibility = '';
            switch (RublonRolesProtection::getRoleProtectionType($role)) {
                case RublonHelper::PROTECTION_TYPE_MOBILE_EVERYTIME:
                    $lock1Visibility = 'hidden';
                    $lock2Visibility = 'visible';
                    $lock3Visibility = 'visible';
                    $lock4Visibility = 'visible';
                    break;
                case RublonHelper::PROTECTION_TYPE_MOBILE:
                    $lock1Visibility = 'hidden';
                    $lock2Visibility = 'visible';
                    $lock3Visibility = 'visible';
                    $lock4Visibility = 'hidden';
                    break;
                case RublonHelper::PROTECTION_TYPE_EMAIL:
                    $emailSelected = ' selected';
                    $lock1Visibility = 'hidden';
                    $lock2Visibility = 'visible';
                    $lock3Visibility = 'hidden';
                    $lock4Visibility = 'hidden';
                    break;
                case RublonHelper::PROTECTION_TYPE_NONE:
                    $noneSelected = ' selected';
                    $lock1Visibility = 'visible';
                    $lock2Visibility = 'hidden';
                    $lock3Visibility = 'hidden';
                    $lock4Visibility = 'hidden';
                    break;
            }
        }
        echo '<div class="rublon-settings-setting-name">';
        echo '	<label for="rublon-role-' . $role_id . '-dropdown" class="rublon-settings-setting-label"><div class="rublon-settings-setting-label">' . translate_user_role(before_last_bar($role)) . '</div></label>';
        echo '	<select id="rublon-role-' . $role_id . '-dropdown" name="' . RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY . '[' . $role_id . ']">';
        echo '		<option value="email"' . $emailSelected . '>' . __('Email', 'rublon') . '</option>';
        echo '		<option value="none"' . $noneSelected . '>' . __('None', 'rublon') . '</option>';
        echo '	</select>';
        echo '<label class="rublon-label rublon-label-' . $role_id . '" for="rublon-role-' . $role_id . '-dropdown">';
        echo '	<div class="rublon-lock-container rublon-unlocked-container rublon-' . $role_id . '-unlocked ' . $lock1Visibility . '"><img class="rublon-lock rublon-unlocked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/unlocked.png" /></div>';
        echo '	<div class="rublon-lock-container rublon-locked-container rublon-' . $role_id . '-locked ' . $lock2Visibility . '"><img class="rublon-lock rublon-locked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/locked.png" /></div>';
        echo '	<div class="rublon-lock-container rublon-locked-container rublon-' . $role_id . '-locked2 ' . $lock3Visibility . '"><img class="rublon-lock rublon-locked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/locked.png" /></div>';
        echo '	<div class="rublon-lock-container rublon-locked-container rublon-' . $role_id . '-locked3 ' . $lock4Visibility . '"><img class="rublon-lock rublon-locked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/locked.png" /></div>';
        echo '</label>';
        echo '</div>';
    }
    echo '<script>//<![CDATA[
		if (RublonWP) {
			RublonWP.roles = [' . implode(', ', $role_ids) . '];
			RublonWP.setUpRoleProtectionTypeChangeListener();  
		}
	//]]></script>';

}


/**
 * Callback for rendering the "Disable XML-RPC" setting on the plugin's settings page
 *
 */
function rublon2factor_render_disable_xmlrpc()
{

    $settings = RublonHelper::getSettings('');
    $offSelected = '';
    $onSelected = '';
    if (!empty($settings['disable-xmlrpc']) && $settings['disable-xmlrpc'] == 'on') {
        $offSelected = ' selected';
        $offVisible = 'visible';
        $onVisible = 'hidden';
    } else {
        $onSelected = ' selected';
        $onVisible = 'visible';
        $offVisible = 'hidden';
    }
    echo '<p class="rublon-settings-desc">' . __('XML-RPC allows external applications to administer your website without having to authenticate through Rublon. We strongly recommend keeping it disabled.', 'rublon') . '</p>';
    echo '<select id="rublon-xmlrpc-dropdown" name="' . RublonHelper::RUBLON_SETTINGS_KEY . '[disable-xmlrpc]">';
    echo '	<option value="on"' . $offSelected . '>' . __('Disabled', 'rublon') . '</option>';
    echo '	<option value="off"' . $onSelected . '>' . __('Enabled', 'rublon') . '</option>';
    echo '</select>';
    echo '<label class="rublon-label rublon-label-xmlrpc" for="rublon-xmlrpc-dropdown">';
    echo '	<div class="rublon-lock-container rublon-unlocked-container rublon-xmlrpc-unlocked ' . $onVisible . '"><img class="rublon-lock rublon-unlocked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/unlocked.png" /></div>';
    echo '	<div class="rublon-lock-container rublon-locked-container rublon-xmlrpc-locked ' . $offVisible . '"><img class="rublon-lock rublon-locked" src="' . RUBLON2FACTOR_PLUGIN_URL . '/assets/images/locked.png" /></div>';
    echo '</label>';
    echo '<script>//<![CDATA[
		if (RublonWP) {
			RublonWP.setUpXMLRPCChangeListener();
		}
	//]]></script>';

}

function rublon2factor_render_enable_adam()
{

    $settings = RublonHelper::getSettings('additional');
    $offSelected = '';
    $onSelected = '';

    if (!empty($settings['enable-adam']) && $settings['enable-adam'] == 'on') {
        $onSelected = ' selected';
    } else {
        $offSelected = ' selected';
    }

    echo '<p class="rublon-settings-desc">' . __('Enable or disable Adam talking about WordPress, Rublon and security beneath the login form on wp-login.php', 'rublon') . '</p>';
    echo '<select id="rublon-adam-dropdown" name="' . RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY . '[enable-adam]">';
    echo '	<option value="off"' . $offSelected . '>' . __('Disabled', 'rublon') . '</option>';
    echo '	<option value="on"' . $onSelected . '>' . __('Enabled', 'rublon') . '</option>';
    echo '</select>';
}

function rublon2factor_render_rl_activelistener()
{
    $offSelected = '';
    $onSelected = '';
    if (RublonHelper::isLogoutListenerEnabled()) {
        $onSelected = ' selected';
    } else {
        $offSelected = ' selected';
    }

    echo '<p class="rublon-settings-desc">' . __('Users get logged out from a trusted device in real-time directly upon removing it. Disable this if you experience slower page load times. Remote Logout will still work, but using Wordpress standard heart-beat mechanism.', 'rublon') . '</p>';
    echo '<select id="rublon-rl-activelistener-dropdown" name="' . RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY . '[rl-active-listener]">';
    echo '	<option value="on"' . $onSelected . '>' . __('Enabled', 'rublon') . '</option>';
    echo '	<option value="off"' . $offSelected . '>' . __('Disabled', 'rublon') . '</option>';
    echo '</select>';
}


function rublon2factor_render_access_control()
{
    $offSelected = '';
    $onSelected = '';
    if (RublonHelper::isAccessControlWidgetEnabled()) {
        $onSelected = ' selected';
    } else {
        $offSelected = ' selected';
    }

    echo '<p class="rublon-settings-desc">' . __('Users can choose other Rublon users to login to his accounts. Disable this if you want to avoid multiple users using the same WP account.', 'rublon') . '</p>';
    echo '<select id="rublon-access-control-dropdown" name="' . RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY . '[access-control]">';
    echo '	<option value="on"' . $onSelected . '>' . __('Enabled', 'rublon') . '</option>';
    echo '	<option value="off"' . $offSelected . '>' . __('Disabled', 'rublon') . '</option>';
    echo '</select>';
}

function rublon2factor_render_buffered_confirmation_time()
{
    echo '<p class="rublon-settings-desc">' . __('User\'s confirmations can be remembered for selected amount of time. Set 0 to disable.', 'rublon') . '</p>';
    $fieldName = RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY . '[' . RublonFeature::BUFFERED_CONFIRMATION_OPTION_KEY . ']';
    printf('<input type="number" name="%s" value="%s" style="width:5em" /> %s',
        esc_attr($fieldName),
        esc_attr(RublonFeature::getBufferedConfirmationOptionValue()),
        __('minutes', 'rublon')
    );
}


/**
 * Display the Rublon page
 *
 */
function rublon2factor_render_settings_page()
{
    ?>
    <div class="wrap">
        <div id="rublon2factor_page">
            <h2 class="rublon-header">
                <?php _e('Rublon Multi-Factor Authentication Settings', 'rublon'); ?>
            </h2>

            <?php

            $isProjectOwner = RublonHelper::isProjectOwner();
            $isAdministrator = current_user_can('manage_options');

            $title = $isProjectOwner ? __('Only your account is protected! Need Rublon for more accounts?', 'rublon') : __('Your account is not protected! Need Rublon for more accounts?', 'rublon');
            $text = $isProjectOwner ? __('You are currently using the Rublon Personal API, which limits protection to 1 account per website.', 'rublon') : __('Your website is currently using the Rublon Personal API, which limits protection to 1 account per website (the administrator who has installed and activated the plugin).', 'rublon');
            $text2 = $isProjectOwner ? __('If you\'d like to protect more accounts, you need to upgrade to the Rublon Business API.', 'rublon') : __('If you\'d like to protect your or more accounts, you need to upgrade to the Rublon Business API.', 'rublon');

            ?>

            <?php if (!RublonHelper::isNewVersion()): ?>

                <?php if ($isAdministrator): ?>

                    <div class="updated rublon-be-infobox-container<?php echo(!$isProjectOwner ? ' rublon-warning' : ''); ?>">

                        <div id="message" class="rublon-be-infobox-content">
                            <div class="rublon-buy-now-subcontainer">
                                <div class="rublon-buy-now-left">

                                    <h3><?php echo $title; ?></h3>

                                    <p>
                                        <?php echo $text; ?>
                                        <?php echo $text2; ?>
                                        <?php echo __('You can easily order online.', 'rublon'); ?>
                                    </p>
                                    <?php if ($isAdministrator): ?>
                                        <p>
                                            <a href="<?php echo RublonHelper::getBuyBusinessEditionURL(); ?>"
                                               class="rublon-button-buy-now"
                                               target="_blank"><?php echo __('Upgrade now', 'rublon') ?></a>
                                        </p>
                                        <p class="rublon-buy-now-tip">
                                            <?php echo __('After purchasing the upgrade, please click on the "Purge cache" button located in the bottom right corner of this page to activate your license.', 'rublon'); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="updated rublon-be-infobox rublon-warning">
                        <div id="message" class="rublon-be-infobox-content">
                            <div class="rublon-buy-now-subcontainer">
                                <div class="rublon-buy-now-left">
                                    <h3><?php echo __('Your account is not protected!', 'rublon'); ?></h3>
                                    <p>
                                        <?php echo __('You have logged in successfully, but due the Personal Edition limitation your account isn\'t protected by Rublon and thus vulnerable to password theft and brute force attacks. Upgrade to the Business Edition needed (sales@rublon.com). Please contact your administrator.', 'rublon'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; // isAdministrator ?>

            <?php endif; // isNewVersion ?>

            <?php

            // necessary, otherwise "updated" messages won't be visible
            settings_errors();

            // Header text
            echo '<p>'
                . ' ' . sprintf(__('Rublon protects WordPress logins using multi-factor authentication. Learn more at  <a href="%s" target="_blank">www.rublon.com</a>. Contact support at <a href="mailto:%s">support@rublon.com</a>.', 'rublon'), RublonHelper::rublonComURL(), RublonHelper::RUBLON_EMAIL_SUPPORT)
                . '</p>';


            if (RublonHelper::isSiteRegistered() || RublonHelper::isNewVersion()) {

                if (!RublonHelper::isNewVersion() && RublonHelper::isSiteRegistered()) {
                    $current_user = wp_get_current_user();
                    // No protection warning
                    if (!RublonHelper::isUserProtected($current_user) && is_user_member_of_blog()) {
                        printf(
                            '<span style="color: red; font-weight: bold;">' . __('Warning!', 'rublon') . '</span>'
                            . ' ' . __('Your account is not protected. Go to <a href="%s">your profile page</a> to enable account protection.', 'rublon'),
                            admin_url(RublonHelper::WP_PROFILE_PAGE . RublonHelper::WP_PROFILE_EMAIL2FA_SECTION)
                        );
                    }
                }

                // Rublon settings
                if (current_user_can('manage_options')): // START_BLOCK: Is user authorized to manage plugins?
                    ?>

                    <form method="post" action="options.php"
                          id="rublon-plugin-additional-settings">
                        <?php

                        settings_fields('rublon2factor_settings_group');
                        do_settings_sections('rublon_api');

                        submit_button();
                        ?>
                    </form>


                    <?php

                    RublonHelper::printSocialSection();

                    // Cache purge button
                    printf('<p style="float:right;"><a href="%s" class="button">%s</a></p>',
                        esc_attr(admin_url(
                            sprintf('admin.php?page=rublon&action=%s&nonce=%s',
                                esc_attr(urlencode(RublonHelper::CACHE_PURGE_ACTION)),
                                wp_create_nonce(RublonHelper::CACHE_PURGE_NONCE)
                            ))),
                        __('Purge Cache', 'rublon')
                    );

                endif; // END_BLOCK: Is user authorized to manage plugins?

            } ?>
        </div>
    </div>
    <?php
}


function rublon2factor_render_confirmations_page()
{
    ?>
    <div class="wrap">
        <div id="rublon2factor_page">
            <h2 class="rublon-header">
                <?php _e('Rublon', 'rublon'); ?>
            </h2>

            <?php

            // necessary, otherwise "updated" messages won't be visible
            settings_errors();

            // Header text
            echo '<p>'
                . __('Rublon instantly protects all accounts with effortless, email-based two-factor authentication.', 'rublon')
                . ' ' . __('Get the optional mobile app for more security!', 'rublon')
                . ' ' . sprintf(__('Learn more at <a href="%s" target="_blank">wordpress.rublon.com</a>.', 'rublon'), RublonHelper::wordpressRublonComURL())
                . '</p>';

            // Consumer script
            $current_user = wp_get_current_user();

            if (RublonHelper::isSiteRegistered()) {

                // No protection warning
                if (!RublonHelper::isNewVersion() && !RublonHelper::isUserProtected($current_user) && is_user_member_of_blog()) {
                    printf(
                        '<span style="color: red; font-weight: bold;">' . __('Warning!', 'rublon') . '</span>'
                        . ' ' . __('Your account is not protected. Go to <a href="%s">your profile page</a> to enable account protection.', 'rublon'),
                        admin_url(RublonHelper::WP_PROFILE_PAGE . RublonHelper::WP_PROFILE_EMAIL2FA_SECTION)
                    );
                }

                // Rublon settings
                if (current_user_can('manage_options')): // START_BLOCK: Is user authorized to manage plugins?
                    ?>

                    <form method="post" action="options.php"
                          id="rublon-plugin-confirmations-settings">
                        <?php

                        settings_fields('rublon2factor_confirmations_settings_group');
                        do_settings_sections('rublon_confirmations');

                        submit_button();
                        echo '<script>//<![CDATA[
						document.addEventListener(\'DOMContentLoaded\', function() {
 							if (RublonWP) {
								RublonWP.setUpFormSubmitListener("rublon-plugin-confirmations-settings", "rublon-confirmation-form");
 							}
 							if (RublonSDK) {
 								RublonSDK.initConfirmationForms();
 							}
						}, false);
					//]]></script>';


                        ?>
                    </form>
                <?php

                endif; // END_BLOCK: Is user authorized to manage plugins?

            } ?>
        </div>
    </div>
    <?php
}


function rublon2factor_render_tdm_page()
{
    ?>
    <div class="wrap">
        <div id="rublon2factor_tdm_page">
            <h2 class="rublon-header">
                <?php echo __('Rublon', 'rublon') . ' - ' . __('Trusted Device Manager', 'rublon'); ?>
            </h2>
            <p>
                <?php echo __('Rublon instantly protects all accounts with effortless, email-based two-factor authentication.', 'rublon')
                    . ' ' . __('Get the optional mobile app for more security!', 'rublon')
                    . ' ' . sprintf(__('Learn more at <a href="%s" target="_blank">wordpress.rublon.com</a>.', 'rublon'), RublonHelper::wordpressRublonComURL()) ?>
            </p>
        </div>
    </div>
    <?php
}

function rublon2factor_render_acm_page()
{

    ?>
    <div class="wrap">
        <div id="rublon2factor_acm_page">
            <h2 class="rublon-header">
                <?php echo __('Rublon', 'rublon') . ' - ' . __('Account Sharing Manager', 'rublon'); ?>
            </h2>
            <p>
                <?php echo __('Rublon instantly protects all accounts with effortless, email-based two-factor authentication.', 'rublon')
                    . ' ' . __('Get the optional mobile app for more security!', 'rublon')
                    . ' ' . sprintf(__('Learn more at <a href="%s" target="_blank">wordpress.rublon.com</a>.', 'rublon'), RublonHelper::wordpressRublonComURL()) ?>
            </p>
            <?php
            // ACM widget
            $current_user = wp_get_current_user();
            if (RublonHelper::canShowACM() && RublonHelper::isUserAuthenticated($current_user)) {
                $gui = Rublon2FactorGUIWordPress::getInstance();
                echo $gui->getACMWidget();
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Display plugin warnings and the registration ribbon
 */
function rublon2factor_no_settings_warning()
{

    global $pagenow;

    $screen = get_current_screen();

    if ($pagenow == 'plugins.php'
        || (!empty($screen->base) && $screen->base == 'toplevel_page_rublon')) {

        $installation_obstacles = array();

        if (!RublonHelper::canPluginAttemptRegistration($installation_obstacles)) {

            if ($installation_obstacles[RublonHelper::INSTALL_OBSTACLE_PHP_VERSION_TOO_LOW]) {
                echo "<div class='error'><p><strong>"
                    . __('Warning! The PHP version of your server is too old to run Rublon. Please upgrade your server\'s PHP version.', 'rublon')
                    . '</strong></p><p>' . __('Required PHP version:', 'rublon') . ' <strong>' . RublonHelper::PHP_VERSION_REQUIRED
                    . ' ' . __('(or above)', 'rublon') . '</strong></p><p>' . __('Your PHP version:', 'rublon')
                    . ' <strong>' . phpversion() . '</strong></p></div>';
            }


            if ($installation_obstacles[RublonHelper::INSTALL_OBSTACLE_CURL_NOT_AVAILABLE]) {
                echo "<div class='error'><p><strong>" . __('Warning! The cURL library has not been found on this server.', 'rublon') . '</strong> ' . __('It is a crucial component of the Rublon plugin and its absence will prevent it from working properly. Please have the cURL library installed or consult your server administrator about it.', 'rublon') . '</p></div>';
            }

            if ($installation_obstacles[RublonHelper::INSTALL_OBSTACLE_HASH_NOT_AVAILABLE]) {
                echo "<div class='error'><p><strong>" . __('Warning! The Hash PHP extension has not been found on this server.', 'rublon') . '</strong> ' . __('It is a crucial component of the Rublon plugin and its absence will prevent it from working properly. Please have the Hash extension installed or consult your server administrator about it.', 'rublon') . '</p></div>';
            }

        }

    }

}

add_action('admin_notices', 'rublon2factor_no_settings_warning');

/**
 *
 * Displays messages in admin pages
 *
 */
function rublon2factor_show_admin_messages()
{

    $messages = RublonHelper::getMessages();

    if (!empty($messages)) {
        foreach ($messages as $message) {
            echo "<div class='" . $message['type'] . " fade'><p>" . $message['message'] . "</p></div>";
        }
    }

}

add_action('admin_notices', 'rublon2factor_show_admin_messages');

/**
 * Handle the additional Rublon columns for a given user
 *
 * @param mixed $value Current column value
 * @param string $column_name Column name
 * @param int $user_id User's ID
 * @return string
 */
function rublon2factor_manage_rublon_columns($value, $column_name, $user_id)
{

    if (RublonHelper::isSiteRegistered()) {
        if ($column_name == 'rublon2factor_status') {
            $user = get_user_by('id', $user_id);
            if (
                RublonHelper::isProjectOwner($user_id)
                || (RublonHelper::getMobileUserStatus($user) && RublonRolesProtection::isGrater(RublonHelper::getUserProtectionType($user), RublonHelper::PROTECTION_TYPE_NONE))
            ) {
                $value = RublonHelper::get2FAToggleButton(1);
            } else {
                $value = RublonHelper::get2FAToggleButton(0);
            }
        }
        return $value;
    }

}

add_filter('manage_users_custom_column', 'rublon2factor_manage_rublon_columns', 10, 3);

/**
 * Add the Rublon icon and menu to the toolbar
 *
 */
function rublon2factor_modify_admin_toolbar()
{

    global $wp_admin_bar;
    $current_user = wp_get_current_user();

    if (RublonHelper::isSiteRegistered() && RublonHelper::isUserAuthenticated($current_user) && is_object($wp_admin_bar)) {

        // add a Rublon icon to the my-account node
        $my_account = $wp_admin_bar->get_node('my-account');
        if (is_object($my_account) && property_exists($my_account, 'title')) {
            $wp_admin_bar->remove_node('my-account');
            $wp_admin_bar->add_node($my_account);
        }

        // remove all my-account subnodes except user-info
        $nodes = $wp_admin_bar->get_nodes();
        if (is_array($nodes)) {
            $removed_nodes = array();
            foreach ($nodes as $node) {
                if (!empty($node->title)
                    && !empty($node->parent)
                    && !empty($node->id)
                    && $node->parent == 'user-actions'
                    && $node->id != 'user-info') {
                    array_push($removed_nodes, $node);
                    $wp_admin_bar->remove_node($node->id);
                }
            }

            // add Rublon node
            $wp_admin_bar->add_node(array(
                'id' => 'rublon',
                'title' => __('Protected by Rublon', 'rublon'),
                'href' => admin_url(RublonHelper::WP_RUBLON_PAGE),
                'parent' => 'user-actions',
            ));

            // restore all removed nodes
            foreach ($removed_nodes as $node) {
                $wp_admin_bar->add_node($node);
            }

        }

    }

}

add_action('wp_before_admin_bar_render', 'rublon2factor_modify_admin_toolbar', 999);

/**
 * Include the Rublon css and JS to the frontend and login page
 *
 */
function rublon2factor_add_login_page_files()
{

    // check if the site is running WordPress 3.8+, which brought
    // some visual style changes
    $wp_version = get_bloginfo('version');
    $addCompatStyles = false;
    if (version_compare($wp_version, '3.8', 'ge')) {
        $addCompatStyles = true;
    }

    $currentPluginVersion = RublonHelper::getCurrentPluginVersion();
    wp_enqueue_style('rublon2factor_frontend', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_frontend.css', false, $currentPluginVersion);
    if ($addCompatStyles) {
        wp_enqueue_style('rublon2factor_frontend_38plus', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon2factor_frontend_wp_3.8_plus.css', false, $currentPluginVersion);
    }
    if (is_rtl()) {
        wp_enqueue_style('rublon2factor_rtl', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rtl.css', false, $currentPluginVersion);
    }
    wp_enqueue_script('rublon2factor_js', RUBLON2FACTOR_PLUGIN_URL . '/assets/js/rublon-wordpress.js', false, $currentPluginVersion);

}

add_action('login_enqueue_scripts', 'rublon2factor_add_login_page_files');

// Shows Adam on the login page
if (RublonHelper::isAdamEnabled()) {
    function login_page_custom_css()
    {
        wp_enqueue_style('rublon_adam', RUBLON2FACTOR_PLUGIN_URL . '/assets/css/rublon_adam.css');
    }

    add_action('login_enqueue_scripts', 'login_page_custom_css');

    function add_login_footer()
    {
        ?>
        <div class="rublon_login_page_adam" id="rublon_login_page_adam">
            <div class="rublon_login_page_adam_message">
                <div><?php echo RublonHelper::adam_says(); ?></div>
            </div>
            <div class="rublon_login_page_adam_image"></div>
            <div class="rublon_login_page_adam_close rublon_adam_preload" id="rublon_login_page_adam_close">
                <span class="line-1"></span>
                <span class="line-2"></span>
            </div>
        </div>
        <div class="rublon_login_page_adam_close_message" id="rublon_login_page_adam_close_message">
            To permanently hide Adam for all visitors of this page, please go to the Rublon Settings after you log in to
            your WordPress Dashboard.
        </div>
        <script>
            var adamElement = document.getElementById('rublon_login_page_adam');
            var adamCloseElement = document.getElementById('rublon_login_page_adam_close');
            var adamCloseMessageElement = document.getElementById('rublon_login_page_adam_close_message');

            function rublon_adam_windows_onresize() {
                var loginHeight = document.getElementById('login').offsetHeight;
                var windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
                var adamHeight = adamElement.offsetHeight;
                var offset = 20;

                var sum = loginHeight + adamHeight + offset;
                if (sum > windowHeight) {
                    rublon_adam_add_class(adamElement, 'rublon_login_page_adam_static');
                } else {
                    rublon_adam_remove_class(adamElement, 'rublon_login_page_adam_static');
                }

                var adamCloseMessageHeight = adamCloseMessageElement.offsetHeight;
                sum = loginHeight + adamCloseMessageHeight + offset + 30;
                if (sum > windowHeight) {
                    rublon_adam_add_class(adamCloseMessageElement, 'rublon_login_page_adam_close_message_static');
                } else {
                    rublon_adam_remove_class(adamCloseMessageElement, 'rublon_login_page_adam_close_message_static');
                }
            };

            function rublon_adam_add_class(elemet, className) {
                if (elemet.className.indexOf(className) == -1) {
                    elemet.className = elemet.className + ' ' + className;
                }
            }

            function rublon_adam_remove_class(element, className) {
                element.className = element.className.replace(RegExp('\\b' + className + '\\b'), "");
            }

            window.onload = function () {
                adamCloseElement.className = adamCloseElement.className.replace(/\rublon_adam_preload\b/, "");
            };
            adamCloseElement.onclick = function () {
                adamElement.style.display = 'none';
                adamCloseMessageElement.style.display = 'block';
                rublon_adam_windows_onresize();
            };
            window.onresize = rublon_adam_windows_onresize;
            rublon_adam_windows_onresize();
            setTimeout(function () {
                rublon_adam_windows_onresize();
            }, 10);
        </script>
        <?php
    }

    add_action('login_footer', 'add_login_footer');
}

function rublon2factor_render_api_credentials()
{
    echo RublonHelper::renderApiCredentialsForm();
}
