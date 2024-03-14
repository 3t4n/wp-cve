<?php

class WP_BNAV_Utils {
    public static function isProActivated() {

        global $bnav_pro_license;
        if ($bnav_pro_license) {
            return $bnav_pro_license->is_valid();
        }

        return false;
    }
}