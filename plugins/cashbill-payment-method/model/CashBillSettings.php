<?php

class CashBillSettingsModel
{
    public static function getId()
    {
        return get_option('cashbill_id', '');
    }

    public static function getSecret()
    {
        return get_option('cashbill_secret', '');
    }

    public static function getPSCId()
    {
        return get_option('cashbill_psc_id', '');
    }

    public static function getPSCSecret()
    {
        return get_option('cashbill_psc_secret', '');
    }

    public static function isTestMode()
    {
        return get_option('cashbill_test', false) == true;
    }

    public static function isPSCMode()
    {
        return get_option('cashbill_psc_mode', false) == true;
    }

    public static function setId($cashbill_id)
    {
        update_option('cashbill_id', $cashbill_id);
    }

    public static function setSecret($cashbill_secret)
    {
        update_option('cashbill_secret', $cashbill_secret);
    }

    public static function setPSCId($cashbill_id)
    {
        update_option('cashbill_psc_id', $cashbill_id);
    }

    public static function setPSCSecret($cashbill_secret)
    {
        update_option('cashbill_psc_secret', $cashbill_secret);
    }

    public static function setTestMode($cashbill_test = false)
    {
        update_option('cashbill_test', $cashbill_test == true);
    }

    public static function setPSCMode($cashbill_psc_mode = false)
    {
        update_option('cashbill_psc_mode', $cashbill_psc_mode == true);
    }

    public function save()
    {
        if (null !== wp_unslash($_POST['cashbill_settings_request'])) {
            $cashbill_id = sanitize_text_field($_POST['cashbill_id']);
            $cashbill_secret = sanitize_text_field($_POST['cashbill_secret']);
            $cashbill_test = isset($_POST['cashbill_test']) && $_POST['cashbill_test'] == true;

            update_option('cashbill_id', $cashbill_id);
            update_option('cashbill_secret', $cashbill_secret);
            update_option('cashbill_test', $cashbill_test);

            $cashbill_psc_id = sanitize_text_field($_POST['cashbill_psc_id']);
            $cashbill_psc_secret = sanitize_text_field($_POST['cashbill_psc_secret']);
            $cashbill_psc_mode = isset($_POST['cashbill_psc_mode']) && $_POST['cashbill_psc_mode'] == true;
            
            update_option('cashbill_psc_id', $cashbill_psc_id);
            update_option('cashbill_psc_secret', $cashbill_psc_secret);
            update_option('cashbill_psc_mode', $cashbill_psc_mode);
        }
    }
}
