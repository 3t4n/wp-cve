<?php

namespace WPPayForm\App\Http\Controllers;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\GlobalSettings;
use WPPayForm\App\Modules\PaymentMethods\Stripe\Stripe;
use WPPayForm\App\Services\AccessControl;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Services\Turnstile\Turnstile;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Modules\Debug\Debug;
use WPPayForm\App\Modules\Notices\DashboardNotices;

class GlobalSettingsController extends Controller
{
    public function roles(AccessControl $accessControl)
    {
        try {
            $roles = $accessControl->getAccessRoles();
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }

        return array('roles' => $roles);
    }

    public function setRoles()
    {
        try {
            return (new AccessControl())->setAccessRoles($this->request->all());
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }
    // Customer dashboard user rule settings start
    // Api end point : get-paymattic-user-dashboard-data
    public function getPaymatticUserDashboardData()
    {
        $pages = (new Form())->getAllPages()->toArray();
        $activePage = get_option('_wppayform_user_dashboard_page', 'Paymattic Dashboard');

        try {
            if (current_user_can('manage_options')) {
                $capability = get_option('_wppayform_enable_paymattic_user_dashboard', 'no');
                return array(
                    'status' => $capability == 'no' ? false : true,
                    'data' => $capability,
                    'pages' => $pages,
                    'activePage' => $activePage
                );
            } else {
                throw new \Exception(__('Sorry, You can not update permissions. Only administrators can update permissions', 'wp-payment-form'));
            }

        } catch (\Exception $e) {

            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);

        }
    }

    public function getPaymatticUserRoles()
    {

        $paymatticUserPermissions = [
            "paymattic_user" => array(
                "name" => "Paymattic User",
                "capabilities" => [
                    "read_entry" => true,
                    "read_subscription_entry" => false,
                    "can_sync_subscription_billings" => false,
                    "cancel_subscription" => false,
                ]
            )
        ];

        return array(
            'paymatticUserPermissions' => $paymatticUserPermissions
        );
    }

    public function updatePaymatticUserPermission()
    {
        return $this->updateOrInsertPaymatticUserPermission($this->request->all());
    }

    public function addPaymatticCustomUser($paymatticUserPermissions)
    {
        global $wp_roles;
        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }
        foreach ($paymatticUserPermissions as $key => $value) {
            $wp_roles->remove_role($key);
            $wp_roles->add_role($key, __($value['name']), $value['capabilities']);
        }
    }
    //Api endpoint: enable-paymattic-user-dashboard
    public function enablePaymatticUserDashboard()
    {
        $this->createPaymatticUserDashboardPage();

        $paymatticUserRoles = $this->getPaymatticUserRoles();
        $message = __('Successfully enable customer/donor dashboard module', 'wp-payment-form');
        return $this->updateOrInsertPaymatticUserPermission($paymatticUserRoles, $message);
    }

    public function createPaymatticUserDashboardPage()
    {
        $options = get_option('wppayform_user_dashboard_page');
        if (false === $options || !array_key_exists('paymattic_dashboard_page', $options)) {
            $charge_confirmation = wp_insert_post(
                array(
                    'post_title' => __('Paymattic Dashboard', 'wp-payment-form'),
                    'post_content' => '[wppayform_dashboard]',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                )
            );
            $options['paymattic_dashboard_page'] = $charge_confirmation;
        }
        update_option('wppayform_user_dashboard_page', $options);
    }

    public function updateOrInsertPaymatticUserPermission($paymatticUserPermissions, $message = 'Paymattic User Permissions Updated Successfully')
    {

        try {
            if (current_user_can('manage_options')) {
                $this->addPaymatticCustomUser($paymatticUserPermissions['paymatticUserPermissions']);
                update_option('_wppayform_enable_paymattic_user_dashboard', $paymatticUserPermissions, 'no');
                update_option('_wppayform_user_dashboard_page', Arr::get($paymatticUserPermissions, 'activePage'));
                return array(
                    'message' => $message
                );
            } else {
                throw new \Exception(__('Sorry, You can not update permissions. Only administrators can update permissions', 'wp-payment-form'));
            }

        } catch (\Exception $e) {

            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);

        }
    }
    // End of Customer dashboard user rule settings
    public function dashboardNotice(DashboardNotices $notices)
    {
        return array(
            'close' => $notices->updateNotices($this->request->args)
        );
    }

    public function getNoticeStatus(DashboardNotices $notices)
    {
        return array(
            'displayNotice' => $notices->getNoticesStatus()
        );
    }

    public function currencies()
    {
        return array(
            'currency_settings' => GeneralSettings::getGlobalCurrencySettings(),
            'currencies' => GeneralSettings::getCurrencies(),
            'locales' => GeneralSettings::getLocales(),
            'ip_logging_status' => GeneralSettings::ipLoggingStatus(),
            'honeypot_status' => GeneralSettings::honeypot_status(),
            'abandoned_time' => GeneralSettings::getAbandonedTime(),
            'business_name' => GeneralSettings::getBusinesssName(),
            'business_address' => GeneralSettings::getBusinessAddress(),
            'business_logo' => GeneralSettings::getBusinessLogo()
        );
    }


    public function saveCurrencies()
    {
        try {
            GlobalSettings::updateSettings($this->request->all());
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }


        return array(
            'message' => __('Settings successfully updated', 'wp-payment-form')
        );
    }

    public function donorLeaderboardSettings()
    {
        return array(
            'settings' => get_option('wppayform_donation_leaderboard_settings',  array(
                'enable_donation_for' => 'all',
                'template_id' => 3,
                'enable_donation_for_specific' => [],
                'orderby' => 'grand_total'
            )),
            'forms' => Form::getAllForms()
        );
    }

    public function saveDonationLeaderboardSettings()
    {
        $donation_leaderboard_settings = $this->request->donation_leaderboard_settings;
        update_option('wppayform_donation_leaderboard_settings', $donation_leaderboard_settings, false);
        return array(
            'message' => 'Settings successfully updated'
        );
    }

    public function stripe()
    {
        return (new Stripe())->getPaymentSettings();
    }

    public function saveStripe()
    {
        return (new Stripe())->savePaymentSettings($this->request->all());
    }

    public function forms()
    {
        return array(
            'forms' => Form::getAllForms()
        );
    }

    public function getRecaptcha()
    {
        return array(
            'settings' => GeneralSettings::getRecaptchaSettings()
        );
    }

    public function saveRecaptcha()
    {
        $settings = $this->request->settings;

        $sanitizedSettings = [];
        foreach ($settings as $settingKey => $setting) {
            $sanitizedSettings[$settingKey] = sanitize_text_field($setting);
        }

        if ($sanitizedSettings['recaptcha_version'] != 'none') {
            if (empty($sanitizedSettings['site_key']) || empty($sanitizedSettings['secret_key'])) {
                wp_send_json_error([
                    'message' => 'Please provide site key and secret key for enable reCAPTCHA'
                ], 423);
            }
        }

        update_option('wppayform_recaptcha_settings', $sanitizedSettings);

        return array(
            'message' => 'Settings successfully updated'
        );
    }

    public function getTurnstile()
    {
        return array(
            'settings' => get_option('wppayform_turnstile_settings'),
            'status' => get_option('wppayform_turnstile_validation_status'),
        );
    }

    public function saveTurnstile()
    {
        $settings = $this->request->settings;

        if ($settings == 'clear-settings') {
            delete_option('wppayform_turnstile_settings');

            update_option('wppayform_turnstile_validation_status', false, 'no');

            wp_send_json_success([
                'message' => __('Your Turnstile settings are deleted.', 'wppayform'),
                'status' => false
            ], 200);
        }

        $sanitizedSettings = [];

        foreach ($settings as $settingKey => $setting) {
            $sanitizedSettings[$settingKey] = sanitize_text_field($setting);
        }

        if (empty($sanitizedSettings['siteKey']) || empty($sanitizedSettings['secretKey'])) {
            wp_send_json_error([
                'message' => 'Please provide site key and secret key to enable turnstile security'
            ], 423);
        }

        $token = Arr::get($settings, 'token');
        $secretKey = Arr::get($settings, 'secretKey');

        // If token is not empty meaning user verified their captcha.
        if ($token) {
            // Validate the turnstile response.
            $status = Turnstile::validate($token, $secretKey);

            // turnstile is valid. So proceed to store.
            if ($status) {
                // Update the turnstile details with siteKey & secretKey.
                update_option('wppayform_turnstile_settings', $sanitizedSettings, 'no');

                // Update the turnstile validation status.
                update_option('wppayform_turnstile_validation_status', $status, 'no');

                // Send success response letting the user know that
                // that the turnstile is valid and saved properly.
                wp_send_json_success([
                    'message' => __('Your Turnstile is valid and saved.', 'wppayform'),
                    'status' => $status
                ], 200);
            } else {
                // turnstile is not valid.
                $message = __('Sorry, Your Turnstile is not valid or token timed out. Please try again', 'wppayform');

                // if already validated
                $isalreadyValied = get_option('wppayform_turnstile_settings');
                if (Arr::get($isalreadyValied, 'siteKey')) {
                    $message = __('Your Turnstile is already valid! Clear your turnstile settings to renew.', 'wppayform');
                }
            }
        } else {
            // The token is empty, so the user didn't verify their turnstile.
            $message = __('Please validate your Turnstile siteKey first and then hit save.', 'wppayform');

            // Get the already stored turnstile status.
            $status = get_option('wppayform_turnstile_validation_status');

            if ($status) {
                $message = __('Your Turnstile details are already valid. So no need to save again.', 'wppayform');
            }
        }

        wp_send_json_error([
            'message' => $message,
            'status' => $status
        ], 400);
    }

    public function handleFileUpload()
    {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $uploadedfile = $_FILES['file'];

        $acceptedFilles = array(
            'image/png',
            'image/jpeg'
        );

        if (!in_array($uploadedfile['type'], $acceptedFilles)) {
            wp_send_json(__('Please upload a valid image file', 'wp-payment-form'), 423);
        }

        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        if ($movefile && !isset($movefile['error'])) {
            wp_send_json_success(
                array(
                    'file' => $movefile
                ),
                200
            );
        } else {
            wp_send_json(__('Something is wrong when uploading the file', 'wp-payment-form'), 423);
        }
    }

    public function generateDebug($type)
    {
        return Debug::getDebugInfos($type);
    }
}
