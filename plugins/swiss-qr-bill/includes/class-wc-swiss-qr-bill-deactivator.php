<?php
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes
 */
class WC_Swiss_Qr_Bill_Deactivator {

    /**
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        delete_option('wc_swiss_qr_bill_may_deactivate');
    }

}
