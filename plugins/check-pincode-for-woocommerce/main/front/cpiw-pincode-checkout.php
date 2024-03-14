<?php
    function CPIW_OnCheckoutPincodeCheck() {

        if(isset($_REQUEST['pincode']) && $_REQUEST['pincode'] != '') {
            $pincode = sanitize_text_field($_REQUEST['pincode']);
            $expiry = strtotime('+7 day');
            CPIW_PincodeCookieSet($pincode,$expiry);
            $record = CPIW_PincodeCheckInDataTable($pincode);
            $totalrec = count($record);
            if ($totalrec == 1) {
                echo 'true';
            } else {
                echo 'false';
            }

            exit;
        }
    }
    add_action( 'wp_ajax_CPIW_OnCheckoutPincodeCheck','CPIW_OnCheckoutPincodeCheck' );
    add_action( 'wp_ajax_nopriv_CPIW_OnCheckoutPincodeCheck', 'CPIW_OnCheckoutPincodeCheck');