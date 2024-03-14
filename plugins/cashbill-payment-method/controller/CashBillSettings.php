<?php

class CashBillSettingsController
{
    private $isSaved = false;
    private $isValid = false;
    
    public function init()
    {
        add_action('admin_menu', array( $this, 'add_options_page' ));
        add_action('admin_post', array( $this, 'save' ));
        add_action('admin_notices', array($this,'add_notice'));
    }
    
    public function add_options_page()
    {
        add_menu_page(
            'CashBill',
            'CashBill',
            'manage_options',
            'cashbill-payments-settings',
            array( $this, 'render' ),
            "none",
            56
        );
    }

    public function render()
    {
        $cashbill_id = CashBillSettingsModel::getId();
        $cashbill_secret = CashBillSettingsModel::getSecret();
        $cashbill_test = CashBillSettingsModel::isTestMode();
        $cashbill_psc_id = CashBillSettingsModel::getPSCId();
        $cashbill_psc_secret = CashBillSettingsModel::getPSCSecret();
        $cashbill_psc_mode = CashBillSettingsModel::isPSCMode();

        include_once(__DIR__.'/../view/admin/settings.php');
    }

    public function save()
    {
        if (! ($this->has_valid_nonce() && current_user_can('manage_options'))) {
        } elseif (isset($_POST['cashbill_settings_request'])) {
            $cashbill_id = sanitize_text_field($_POST['cashbill_id']);
            $cashbill_secret = sanitize_text_field($_POST['cashbill_secret']);
            $cashbill_test = isset($_POST['cashbill_test']) && $_POST['cashbill_test'] == true;

            $cashbill_psc_id = sanitize_text_field($_POST['cashbill_psc_id']);
            $cashbill_psc_secret = sanitize_text_field($_POST['cashbill_psc_secret']);
            $cashbill_psc_mode = isset($_POST['cashbill_psc_mode']) && $_POST['cashbill_psc_mode'] == true;

            CashBillSettingsModel::setId($cashbill_id);
            CashBillSettingsModel::setSecret($cashbill_secret);
            CashBillSettingsModel::setTestMode($cashbill_test);

            CashBillSettingsModel::setPSCId($cashbill_psc_id);
            CashBillSettingsModel::setPSCSecret($cashbill_psc_secret);
            CashBillSettingsModel::setPSCMode($cashbill_psc_mode);
            $this->isSaved = true;

            $paymentData = null;

            try {
                $shop = new \CashBill\Payments\Shop($cashbill_id, $cashbill_secret, !$cashbill_test);
                $paymentData = $shop->createPayment("Weryfikacja poprawności konfiguracji", array( "value"=>10.41, "currencyCode"=>"PLN" ), "Weryfikacja poprawności konfiguracji sklepu", "none");
            } catch (Exception $e) {
            }
            
            if ($paymentData !== null && isset($paymentData->id) && isset($paymentData->redirectUrl)) {
                $this->isValid = true;
            }

            if ($cashbill_psc_mode) {
                try {
                    $shop = new \CashBill\Payments\Shop($cashbill_psc_id, $cashbill_psc_secret, !$cashbill_test);
                    $paymentData = $shop->createPayment("Weryfikacja poprawności konfiguracji psc", array( "value"=>10.41, "currencyCode"=>"PLN" ), "Weryfikacja poprawności konfiguracji sklepu psc", "none");
                } catch (Exception $e) {
                }
                
                if ($paymentData === null || !isset($paymentData->id) || !isset($paymentData->redirectUrl)) {
                    $this->isValid = false;
                }
            }
        }
        
        $this->redirect();
    }

    private function has_valid_nonce()
    {
        if (! isset($_POST['cashbill_settings_request'])) {
            return false;
        }
     
        $field  = wp_unslash($_POST['cashbill_settings_request']);
        $action = 'cashbill_settings_save';
     
        return wp_verify_nonce($field, $action);
    }

    private function redirect()
    {
        if (! isset($_POST['_wp_http_referer'])) {
            $_POST['_wp_http_referer'] = wp_login_url();
        }
     
        $url = urldecode(sanitize_text_field(
            wp_unslash($_POST['_wp_http_referer'])
        ));

        $redirectUrl = CashBillHelpers::addVarToUrl($url, "cashbill_settings_save", $this->isSaved);
        $redirectUrl = CashBillHelpers::addVarToUrl($redirectUrl, "cashbill_settings_validation", $this->isValid);
    
        wp_safe_redirect($redirectUrl);
        exit;
    }

    public function add_notice()
    {
        if (!isset($_GET['cashbill_settings_save'])) {
            return;
        }

        if ($_GET['cashbill_settings_save'] == true) {
            include_once(__DIR__.'/../view/admin/notice/success_save.php');
        } else {
            include_once(__DIR__.'/../view/admin/notice/error_save.php');
        }

        if ($_GET['cashbill_settings_validation'] == true) {
            include_once(__DIR__.'/../view/admin/notice/success_validation.php');
        } else {
            include_once(__DIR__.'/../view/admin/notice/error_validation.php');
        }
    }
}
