<?php
/**
 * New user approve helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('new-user-approve/new-user-approve.php') ) {
    return;
}
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * NewUserApprove class
 */
class NewUserApprove
{
    /**
     * Construct function.
     */
    public function __construct()
    {
        add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_setting', 1);
        add_action('new_user_approve_user_approved', array( $this, 'sendSmsApproved' ), 1);
        add_action('new_user_approve_user_denied', array( $this, 'sendSmsDenied' ), 1);
        add_action('sa_addTabs', array( $this, 'addTabs' ), 100);
    }

    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs( $tabs = array() )
    {
        $newuserapprove_param = array(
        'checkTemplateFor' => 'newuserapprove',
        'templates'        => self::getNewUserApproveTemplates(),
        );

        $tabs['user_registration']['inner_nav']['newuserapprove']['title']       = 'New User Approve';
        $tabs['user_registration']['inner_nav']['newuserapprove']['tab_section'] = 'cartbountytemplates';
        $tabs['user_registration']['inner_nav']['newuserapprove']['tabContent']  = $newuserapprove_param;
        $tabs['user_registration']['inner_nav']['newuserapprove']['filePath']    = 'views/message-template.php';
        $tabs['user_registration']['inner_nav']['newuserapprove']['icon']        = 'dashicons-admin-users';
        return $tabs;
    }

    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting( $defaults = array() )
    {
        $defaults['smsalert_nua_general']['approved_notify'] = 'off';
        $defaults['smsalert_nua_message']['approved_notify'] = '';
        $defaults['smsalert_nua_general']['denied_notify']   = 'off';
        $defaults['smsalert_nua_message']['denied_notify']   = '';
        return $defaults;
    }

    /**
     * Get new user approve templates.
     *
     * @return array
     */
    public static function getNewUserApproveTemplates()
    {
        // customer template.
        $current_val      = smsalert_get_option('approved_notify', 'smsalert_nua_general', 'on');
        $checkbox_name_id = 'smsalert_nua_general[approved_notify]';
        $textarea_name_id = 'smsalert_nua_message[approved_notify]';
        $text_body        = smsalert_get_option('approved_notify', 'smsalert_nua_message', SmsAlertMessages::showMessage('DEFAULT_NEW_USER_APPROVED'));

        $templates = array();

        $templates['approved']['title']          = 'When account is Approved';
        $templates['approved']['enabled']        = $current_val;
        $templates['approved']['status']         = 'approved';
        $templates['approved']['text-body']      = $text_body;
        $templates['approved']['checkboxNameId'] = $checkbox_name_id;
        $templates['approved']['textareaNameId'] = $textarea_name_id;
        $templates['approved']['token']          = self::getNewUserApprovevariables();

        // admin template.
        $current_val      = smsalert_get_option('denied_notify', 'smsalert_nua_general', 'on');
        $checkbox_name_id = 'smsalert_nua_general[denied_notify]';
        $textarea_name_id = 'smsalert_nua_message[denied_notify]';
        $text_body        = smsalert_get_option('denied_notify', 'smsalert_nua_message', SmsAlertMessages::showMessage('DEFAULT_NEW_USER_REJECTED'));

        $templates['deny']['title']          = 'When account is Deny';
        $templates['deny']['enabled']        = $current_val;
        $templates['deny']['status']         = 'deny';
        $templates['deny']['text-body']      = $text_body;
        $templates['deny']['checkboxNameId'] = $checkbox_name_id;
        $templates['deny']['textareaNameId'] = $textarea_name_id;
        $templates['deny']['token']          = self::getNewUserApprovevariables();

        return $templates;
    }

    /**
     * Send sms approved.
     *
     * @param int $user_id user_id.
     *
     * @return void
     */
    public function sendSmsApproved( $user_id )
    {
        $user  = new WP_User($user_id);
        $phone = get_the_author_meta('billing_phone', $user->ID);

        $smsalert_nua_approved_notify  = smsalert_get_option('approved_notify', 'smsalert_nua_general', 'on');
        $smsalert_nua_approved_message = smsalert_get_option('approved_notify', 'smsalert_nua_message', '');

        if ('on' === $smsalert_nua_approved_notify && '' !== $smsalert_nua_approved_message ) {
            do_action('sa_send_sms', $phone, $this->parseSmsBody($user_id, $smsalert_nua_approved_message));
        }
    }

    /**
     * Send sms denied.
     *
     * @param int $user_id user_id.
     *
     * @return void
     */
    public function sendSmsDenied( $user_id )
    {
        $user = new WP_User($user_id);

        $phone = get_the_author_meta('billing_phone', $user->ID);

        $smsalert_nua_denied_notify  = smsalert_get_option('denied_notify', 'smsalert_nua_general', 'on');
        $smsalert_nua_denied_message = smsalert_get_option('denied_notify', 'smsalert_nua_message', '');

        if ('on' === $smsalert_nua_denied_notify && '' !== $smsalert_nua_denied_message ) {
            do_action('sa_send_sms', $phone, $this->parseSmsBody($user_id, $smsalert_nua_denied_message));
        }
    }

    /**
     * Get new user approvevariables.
     *
     * @return array
     */
    public static function getNewUserApprovevariables()
    {
        $variables = array(
        '[username]'   => 'Username',
        '[store_name]' => 'Store Name',
        );
        return $variables;
    }

    /**
     * Parse sms body.
     *
     * @param array  $data    data.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody( $data = array(), $content = null )
    {
        $user     = new WP_User($data);
        $username = $user->user_login;

        $find = array(
        '[username]',
        );

        $replace = array(
        $username,
        );

        $content = str_replace($find, $replace, $content);
        return $content;
    }
}
new NewUserApprove();
