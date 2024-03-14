<?php
/**
 * Jetpack crm helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (defined('ABSPATH') === false) {
    exit;
}

if (is_plugin_active('zero-bs-crm/ZeroBSCRM.php') === false) {
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
 * jetPackCRM class
 */
class jetPackCRM extends FormInterface
{


    /**
     * Construct function.
     *
     * @return array
     */
    public function handleForm()
    {
        add_action('zbs_new_customer', [$this, 'sendsmsNewContact'], 10, 1);
        add_action('zerobs_save_contact', [$this, 'sendsmsContactUpdate'], 10, 2);

    }//end handleForm()


    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults=[])
    {
        $bookingStatuses = [
            'new',
            'customer',
            'lead',
            'refused',
            'blacklisted',
        ];

        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_jcm_general']['customer_jcm_notify_'.$vs]   = 'off';
            $defaults['smsalert_jcm_message']['customer_sms_jcm_body_'.$vs] = '';
            $defaults['smsalert_jcm_general']['admin_jcm_notify_'.$vs]      = 'off';
            $defaults['smsalert_jcm_message']['admin_sms_jcm_body_'.$vs]    = '';
        }

        return $defaults;

    }//end add_default_setting()


    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs($tabs=[])
    {
        $customerParam = [
            'checkTemplateFor' => 'jcm_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $admin_param = [
            'checkTemplateFor' => 'jcm_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $tabs['jetpack_crm']['nav']  = 'Jetpack CRM';
        $tabs['jetpack_crm']['icon'] = 'dashicons-id-alt';

        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_cust']['title']        = 'Customer Notifications';
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_cust']['tab_section']  = 'jetpackcrmcusttemplates';
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_cust']['first_active'] = true;
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_cust']['tabContent']   = $customerParam;
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_cust']['filePath']     = 'views/message-template.php';

        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_admin']['title']       = 'Admin Notifications';
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_admin']['tab_section'] = 'jetpackcrmadmintemplates';
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_admin']['tabContent']  = $admin_param;
        $tabs['jetpack_crm']['inner_nav']['jetpack_crm_admin']['filePath']    = 'views/message-template.php';
        $tabs['jetpack_crm']['help_links'] = [
            /* 'youtube_link' => [
                'href'   => 'https://youtu.be/4BXd_XZt9zM',
                'target' => '_blank',
                'alt'    => 'Watch steps on Youtube',
                'class'  => 'btn-outline',
                'label'  => 'Youtube',
                'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

            ], */
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/jetpack-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with jetpackcrm',
                'class'  => 'btn-outline',
                'label'  => 'Documentation',
                'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
            ],
        ];
        return $tabs;

    }//end addTabs()


    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        $bookingStatuses = [
            '[new]'         => 'New',
            '[customer]'    => 'Customer',
            '[lead]'        => 'Lead',
            '[refused]'     => 'Refused',
            '[blacklisted]' => 'Blacklisted',
        ];

        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_jcm_notify_'.strtolower($vs), 'smsalert_jcm_general', 'on');

            $checkboxNameId = 'smsalert_jcm_general[customer_jcm_notify_'.strtolower($vs).']';
            $textareaNameId = 'smsalert_jcm_message[customer_sms_jcm_body_'.strtolower($vs).']';

            $defaultTemplate = smsalert_get_option('admin_sms_jcm_body_'.strtolower($vs), 'smsalert_jcm_message', sprintf(__('Hello %1$s, status of your contact #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '[contact_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_jcm_body_'.strtolower($vs), 'smsalert_jcm_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When contact status is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getJetpackCrmvariables();
        }

        return $templates;

    }//end getCustomerTemplates()


    /**
     * Get admin templates.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $bookingStatuses = [
            '[new]'         => 'New',
            '[customer]'    => 'Customer',
            '[lead]'        => 'Lead',
            '[refused]'     => 'Refused',
            '[blacklisted]' => 'Blacklisted',
        ];

        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_jcm_notify_'.strtolower($vs), 'smsalert_jcm_general', 'on');
            $checkboxNameId = 'smsalert_jcm_general[admin_jcm_notify_'.strtolower($vs).']';
            $textareaNameId = 'smsalert_jcm_message[admin_sms_jcm_body_'.strtolower($vs).']';

            $defaultTemplate = smsalert_get_option('admin_sms_jcm_body_'.strtolower($vs), 'smsalert_jcm_message', sprintf(__('Hello admin, status of your contact with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('admin_sms_jcm_body_'.strtolower($vs), 'smsalert_jcm_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When contact status is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getJetpackCrmvariables();
        }

        return $templates;

    }//end getAdminTemplates()


    /**
     * Send sms new Contact.
     *
     * @param int $cID cID
     *
     * @return void
     */
    public function sendsmsNewContact($cID)
    {

        $cust = zeroBS_getCustomer($cID, true, true, true);

        $buyerNumber = $cust['mobtel'];

        $buyerSmsData     = [];
        $customerMessage  = smsalert_get_option('customer_sms_jcm_body_new', 'smsalert_jcm_message', '');
        $customerRrNotify = smsalert_get_option('customer_jcm_notify_new', 'smsalert_jcm_general', 'on');
        if ($customerRrNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseSmsBody($cust, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        $nos = explode(',', $adminPhoneNumber);
        $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
        $adminPhoneNumber = implode(',', $adminPhoneNumber);

        if (empty($adminPhoneNumber) === false) {
            $adminRrNotify = smsalert_get_option('admin_jcm_notify_new', 'smsalert_jcm_general', 'on');
            $adminMessage  = smsalert_get_option('admin_sms_jcm_body_new', 'smsalert_jcm_message', '');

            if ('on' === $adminRrNotify && '' !== $adminMessage) {
                $adminMessage = $this->parseSmsBody($cust, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }

    }//end sendsmsNewContact()


    /**
     * Send sms Contact Update.
     *
     * @param int $cID  cID
     * @param int $data data
     *
     * @return void
     */
    public function sendsmsContactUpdate($cID, $data)
    {
        $cust = zeroBS_getCustomer($cID, true, true, true);

        $buyerNumber     = $cust['mobtel'];
        $bookingStatus   = strtolower($cust['status']);
        $customerMessage = smsalert_get_option('customer_sms_jcm_body_'.$bookingStatus, 'smsalert_jcm_message', '');
        $customerNotify  = smsalert_get_option('customer_jcm_notify_'.$bookingStatus, 'smsalert_jcm_general', 'on');
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($cust, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        if (empty($adminPhoneNumber) === false) {
            $adminNotify = smsalert_get_option('admin_jcm_notify_'.$bookingStatus, 'smsalert_jcm_general', 'on');

            $adminMessage = smsalert_get_option('admin_sms_jcm_body_'.$bookingStatus, 'smsalert_jcm_message', '');

            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
            $adminPhoneNumber = implode(',', $adminPhoneNumber);

            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseSmsBody($cust, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }

    }//end sendsmsContactUpdate()


    /**
     * Parse sms body.
     *
     * @param array  $data    data.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($data, $content=null)
    {

        $contactId   = $data['id'];
        $name        = $data['name'];
        $status      = $data['status'];
        $email       = $data['email'];
        $phone       = $data['mobtel'];
        $createdTime = $data['created'];
        $createdDate = $data['created_date'];
        $find        = [
            '[contact_id]',
            '[name]',
            '[status]',
            '[email]',
            '[phone]',
            '[created_time]',
            '[created_date]',
        ];

        $replace = [
            $contactId,
            $name,
            $status,
            $email,
            $phone,
            $createdTime,
            $createdDate,

        ];

        $content = str_replace($find, $replace, $content);
        return $content;

    }//end parseSmsBody()


    /**
     * Get jetpack crm variables.
     *
     * @return array
     */
    public static function getJetpackCrmvariables()
    {
        $variable['[contact_id]']   = 'Contact Id';
        $variable['[created_date]'] = 'Request Date';
        $variable['[created_time]'] = 'Request Time';
        $variable['[name]']         = 'Name';
        $variable['[email]']        = 'Email';
        $variable['[status]']       = 'Status';
        $variable['[phone]']        = 'Mobile Number';

        return $variable;

    }//end getJetpackCrmvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('zero-bs-crm/ZeroBSCRM.php') === true) {
            add_filter('sAlertDefaultSettings', __CLASS__.'::add_default_setting', 1);
            add_action('sa_addTabs', [$this, 'addTabs'], 10);
        }

    }//end handleFormOptions()


    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public function isFormEnabled()
    {
        $userAuthorize = new smsalert_Setting_Options();
        $islogged      = $userAuthorize->is_user_authorised();
        if ((is_plugin_active('zero-bs-crm/ZeroBSCRM.php') === true) && ($islogged === true)) {
            return true;
        } else {
            return false;
        }

    }//end isFormEnabled()


    /**
     * Handle after failed verification
     *
     * @param object $userLogin   users object.
     * @param string $userEmail   user email.
     * @param string $phoneNumber phone number.
     *
     * @return void
     */
    public function handle_failed_verification($userLogin, $userEmail, $phoneNumber)
    {

    }//end handle_failed_verification()


    /**
     * Handle after post verification
     *
     * @param string $redirectTo  redirect url.
     * @param object $userLogin   user object.
     * @param string $userEmail   user email.
     * @param string $password    user password.
     * @param string $phoneNumber phone number.
     * @param string $extraData   extra hidden fields.
     *
     * @return void
     */
    public function handle_post_verification($redirectTo, $userLogin, $userEmail, $password, $phoneNumber, $extraData)
    {

    }//end handle_post_verification()


    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {

    }//end unsetOTPSessionVariables()


    /**
     * Check current form submission is ajax or not
     *
     * @param bool $isAjax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play($isAjax)
    {
        return $isAjax;
    }//end is_ajax_form_in_play()


}//end class
new jetPackCRM();
