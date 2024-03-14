<?php
namespace WordressLaravel\Wp;

class Activation {
    const TAG = 'wordress-laravel-plugin';

    public static function do_my_hook() {
        Activation::doFunction();
    }
    
    /**
     * Function changing data type of columns
     *
     */
    public static function doFunction() {
        /*
        wp_redirect( admin_url('admin.php?page=woocommerce_access') );
        exit;
        */
    }

}
