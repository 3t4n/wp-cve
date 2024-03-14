<?php

namespace WcGetnet\WooCommerce\GateWays\AdminSettingsFields;

class Privacy_Policy {
    
    public static function save_privacy_policy_meta_accept() {
        if($_POST['page'] == "getnet-settings"){
            update_option( '_policy_privacy_accept', true );
            update_option( '_policy_privacy_accept_date', date('Y-m-d H:i:s') );
            return true;
        }
    }
}
